<?php
/**
 * For controller application
 * ----
 *
 */


/**
 * App
 */
class NyaaControllerApp extends NyaaStore
{
	protected   $Ctrl;
	protected   $Conf;
	protected   $Request;
	public      $stylesheets   =   array();
	protected   $view          =   'system.default.html';

	public function runSnippet( $method, $opt = array(), $template )
	{
		return call_user_func( array($this,"snip".ucfirst($method)), $opt, $template);
	}

	public function applyForm( $method, $Req )
	{
		$method = "form".ucfirst($method);
		if( method_exists( $this, $method)){
			return call_user_func_array( array($this,$method), array( $Req ));
		}else{
			$this->error("on apply %s apply handler %s is not exists", get_class($this), $method);
			$Req->dump( );
		}
	}

	public function __construct( $Ctrl )
	{
		parent::__construct( );
		$this->Ctrl = $Ctrl;
		$this->Conf = new NyaaStore();
		$this->Conf->set($Ctrl->getConf( ));
		$this->Session = $Ctrl->getSession( );
	}

	public function init( )
	{

	}

	public function getTemplater( )
	{
		$Tpl = $this->Ctrl->getTemplater( );
		$Tpl->set('Request', $this->Request);
		return $Tpl;
	}

	public function setRequest( $Req )
	{
		$this->Request = $Req;
	}

	public function run( )
	{
		$Tpl = $this->getTemplater( );
		$Tpl->set($this->get());
		return $Tpl->fetch( $this->view );
	}

	public function getConf( )
	{
		$args = func_get_args( );
		return call_user_func_array(array($this->Conf,'get'),$args);
	}

	function getSession( )
	{
		$args = func_get_args( );
		return call_user_func_array(array($this->Session,'get'),$args);
	}
		

	function dbFactory( $con )
	{
		require_once 'db/db.class.php';
		$handler = NyaaDB::factory( $con );
		return $handler;
	}

	function formFactory( $conf,  $formName, $applyName )
	{
		require_once 'form/form.class.php';
		$form     = new NyaaForm( );
		$form->loadFile($conf);
		$form->addHidden('__FORM__', $applyName);
		$form->addHidden('__APPLY_FROM__', $formName);
		return $form;
	}
	function validaterFactory( $conf )
	{
		require_once 'validater/validate.class.php';
		require_once 'validater/validater.class.php';

		$conf = NyaaConf::load( $conf );
		$validater = new NyaaValidater( );
		foreach($conf->get( ) as $k=>$v)
		{
			$validate = NyaaValidate::factory(
				array(
					'type'    => $v['type'],
					'target'  => $v['target'],
					'message' => $v['message'],
					'con'     => $v['message_sep']
				)
			);
			$validater->addValidate( $validate );
		}
		return $validater;
	}

	function getResult( $key )
	{
		$result = $this->Ctrl->getResult( $key );
		if(!is_object($result))
			return $this->resultSuccess();
		return $result = $this->Ctrl->getResult( $key );
	}

	function resultError( $Req, $errors = array() )
	{
		$result  = $this->Ctrl->resultFactory(array(
			'status'  => 'error',
			'request' => $Req->get(),
			'errors'  => $errors
		));
		return $result;
	}

	function resultSuccess( $opt = array() )
	{
		$result  = $this->Ctrl->resultFactory(array(
			'status'  => 'success',
			'option' => $opt
		));
		return $result;
	}
	function resultRedirect( $url, $opt = array() )
	{
		$result  = $this->Ctrl->resultFactory(array(
			'status'  => 'redirect',
			'redirect' => $url,
			'option' => $opt
		));
		return $result;
	}

	function resultFactory( $name, $Req )
	{
		$result  = $this->Ctrl->resultFactory(array(
			'status'=>$name,
			'request'=>$Req->get()
		));

		return $result;
	}

	public function templateSnipForm( $conf, $formName, $applyName)
	{
		require_once 'form/form.class.php';
		$formFile = $this->Ctrl->getConf('root.dir').'/conf/'.$conf;
		$form     = new NyaaForm( );
		$form->setEnctype('multipart/form-data');
		$form->loadFile($formFile);
		$form->addHidden('__FORM__', $applyName);
		$form->addHidden('__APPLY_FROM__', $formName);
		$bind = array(
			'form'      => $form,
		);
		$email  = $this->Request->isEmpty('email') ? '': $this->Request->get('email');
		$result = $this->Ctrl->getResult( $formName );
		if(!empty($email)) 
			$form->getInput('email')->setValue($email);
		if(!empty($result))
		{
			$bind['errors']  = $result->getOr('errors', array());
			$bind['request'] = $result->getOr('request', array());
			$form->setValues($result->getOr('request', array()));
		}
		return $bind;
	}
}
?>
