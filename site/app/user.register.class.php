<?php
require_once dirname(__FILE__).'/user.class.php';

class UserRegisterApp extends UserApp
{
	function run( )
	{
		$Tpl      =  $this->getTemplater( );
		return $Tpl->fetch('user.register.html');
	}

	function getValidater( )
	{
		require_once 'validater/validate.class.php';
		require_once 'validater/validater.class.php';
		// Build validaters
		$validater = new NyaaValidater( );
		$notnull = NyaaValidate::factory(
			array(
				'type'=>'notnull',
				'target'=>array('familyName','firstName','email','password'),
				'message'=>'%fieldは必須です',
				'con'=>'と'
			)
		);
		$validater->addValidate( $notnull );
		return $validater;
	}

	function valid( $Req )
	{
		$validater = $this->getValidater( $Req );

		if( true !== $res = $validater->validate( $Req->get( ) ) )
		{
			foreach( $res as $e ) $errors[] = $e->getMessage( );
			$result = $this->Ctrl->resultFactory(
				array(
					'status'=>'error',
					'request'=>$Req->get(),
					'errors'=>$errors
				)
			);
			return $result;
		}
		return true;
	}

	function formApply( $Req )
	{
		$result = $this->valid($Req);
		if($result !== true && $result->isError())
			return $result;

		$data = $Req->get('familyName','firstName','email', 'password','gender');
		$data['birthday'] = implode('-',$Req->get('birthday.year','birthday.month','birthday.day'));
		$id = $this->createNewUser( $data );
		if($id > 0)
		{
			// Login Process
			$data = $this->getUser($id);
			$this->Ctrl->getSession()->set('user', $data);

			$result = $this->Ctrl->resultFactory(
				array(
					'status'   => 'redirect',
					'redirect' => '#home',
					'option'   => array(
						'message'  => '#new_user_registed'
					)
				)
			);
			return $result;
		}

		$this->error('Undefined Error Occured');
	}

	function snipForm( )
	{
		$conf = 'form.user.register.conf';
		$formName = 'user.register.form';
		$applyName = 'user.register.apply';

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
			$bind['errors'] = $result->getOr('errors', array());
			$form->setValues($result->getOr('request', array()));
		}
		return $bind;
	}
}
?>
