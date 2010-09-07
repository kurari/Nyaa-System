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
	protected $Ctrl;
	protected $Request;
	public $stylesheets = array();
	protected $view = 'system.default.html';

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
