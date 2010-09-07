<?php
/**
 * Controller
 * ---
 *
 *
 */
require_once 'controller/web.templater.class.php';
require_once 'controller/app.class.php';
require_once 'controller/result.class.php';

/**
 * Controller Web
 */
class NyaaControllerWeb extends NyaaController
{
	private $resourceMapping = array( );
	private $results = array( );
	private $Session = array();

	function init( )
	{
		session_start( );

		$this->Session = new NyaaStore( );
		$this->Session->swapRoot( $_SESSION );

		if(isset($_COOKIE['userid']) && $this->Session->isEmpty('user'))
		{
			$User = $this->appFactory('user');
			$data = $User->getUser($_COOKIE['userid']);
			$this->Session->set('user', $data);
		}
	}


	/**
	 * get templater
	 */
	function getTemplater( )
	{
		$Tpl = new NyaaControllerWebTemplater( $this->Conf );
		$Tpl->set('Ctrl', $this);
		$Tpl->set('conf', $this->Conf->get());
		$Tpl->set('session', $this->Session->get());
		return $Tpl;
	}

	/**
	 * get apply
	 * ---
	 * if path includes method 2nd argument must be true
	 */
	function appFactory( $path, $withMethod = false )
	{
		if( $withMethod == true )
		{
			$aWork    = explode('.',$path);
			$method   = array_pop( $aWork );
			$path     = implode('.', $aWork);
		}

		$file = $this->Conf->get('app.dir').'/'.$path.'.class.php';
		if( !file_exists( $file ) ) 
		{
			$this->error('app file %s not exists', $file);
			return false;
		}
		require_once $file;
		$class = preg_replace('/([^.]+)\.{0,1}/e','ucfirst("\1")', $path);
		$class.= 'App';
		$app = new $class( $this );
		$app->setRequest( $this->Request );
		$app->init( );

		if( $withMethod == true )
			return array($app,$method);
		return $app;
	}

	function resultFactory( $option )
	{
		$result = new NyaaControllerResult( $this );
		$result->set($option);
		return $result;
	}

	/**
	 * apply snippet
	 */
	function snippet( $opt, $template, $templater )
	{
		list($app,$method) = $this->appFactory( $opt['app'], true );
		$data              = $app->runSnippet( $method, $opt, $template );

		// raw string
		if( is_string($data) )
			return $data;

		$cloneTpl          = clone($templater);
		$cloneTpl->set( $data );

		if( isset($opt['template'] ) )
			return $cloneTpl->fetch( $opt['template'] );

		return $cloneTpl->fetch('string://'.$template);
	}

	/**
	 * run resource
	 */
	function runResource( )
	{
		$Req  = $this->Request;
		$dir  = $this->getConf('resource.'.$Req->get('resource'));
		$file = $dir.$Req->slicePath($Req->get('resource'));
		echo file_get_contents($file);
		return true;
	}

	/**
	 * get result
	 */
	function getResult( $key )
	{
		return isset($this->results[$key]) ? $this->results[$key]: array();
	}

	/**
	 * get session
	 */
	function getSession( )
	{
		return $this->Session;
	}

	/**
	 * redirector
	 */
	function redirect( $to, $request = array() )
	{
		$to = preg_replace('/#([^\s]+)/e','$this->getConf("redirect.\1")', $to);
		$url = $this->getConf('site.url').'/app/'.$to;
		$Req = new NyaaStoreRequest($request);
		$Req->set($request);
		$Req->set('redirect-to', $to);

		$app = $this->appFactory('system.redirect');
		$app->setRequest( $Req );
		echo $app->run( );
		die( );
	}

	function getMessage( $msg )
	{
		return preg_replace('/#([^\s]+)/e','$this->getConf("message.\1")', $msg);
	}


	function run( )
	{
		$Req = $this->Request;

		// For resource
		if( $Req->has('resource') ) return $this->runResource( );

		// If form submitted
		if( $Req->has('__FORM__') )
		{
			list($app,$method) = $this->appFactory($Req->get('__FORM__'), true);
			$result = $app->applyForm( $method, $Req );
			$this->results[$Req->get('__APPLY_FROM__')] = $result;

			if( $result->isRedirect() )
			{
				return $this->redirect( $result->get('redirect'), $result->get('option') );
			}
		}

		// Create Application
		if($app = $this->appFactory( $Req->getOr('app',$this->getConf('app.default')) ))
		{
			// Get Theme
			$Tpl = $this->getTemplater( );
			$Tpl->set('stylesheets', $app->stylesheets);
			$contents =& $Tpl->getRef('contents');
			$contents = $app->run( );

			$Tpl->display('theme.main.html');
		}
	}

}
?>
