<?php
require_once dirname(__FILE__).'/user.class.php';

class UserLoginApp extends UserApp
{
	function run( )
	{
		$Tpl      =  $this->getTemplater( );
		return $Tpl->fetch('user.login.html');
	}

	function getValidater( $Req )
	{
		require_once 'validater/validate.class.php';
		require_once 'validater/validater.class.php';
		// Build validaters
		$validater = new NyaaValidater( );
		$notnull = NyaaValidate::factory(
			array(
				'type'=>'notnull',
				'target'=>array('email'),
				'message'=>'%fieldは必須です',
				'con'=>'と'
			)
		);
		// If To Login Required Password is not null
		if( $Req->isEmpty('toRegist') ) {
			$notnull->addTarget('password');
			$login = NyaaValidate::factory(
				array(
					'type'=>'custom',
					'message'=>'ログイン情報に誤りがあります',
					'func'=>array($this,'validateLogin'),
					'target'=>array('email','password')
				)
			);
			$validater->addValidate( $login );
		}
		$validater->addValidate( $notnull );
		return $validater;
	}

	function formApply( $Req )
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
		else
		{
			if( $Req->isEmpty('toRegist') ) {
				if( !$Req->isEmpty('saveLogin') )
					setcookie('userid',$this->Ctrl->getSession()->get('user.id'),time()+(60*60*24*7),'/');
				else
					setcookie('userid','',-1,'/');
				$result = $this->Ctrl->resultFactory(
					array(
						'status'=>'redirect',
						'redirect'=>'#home',
						'option'=>array(
							'message'=> $this->Ctrl->getMessage('#login_complate')
						)
					)
				);
			}else{
				$result = $this->Ctrl->resultFactory(
					array(
						'status'=>'redirect',
						'redirect'=>'user.register',
						'option'=>array(
							'message'=> 'redirecting to user.register application',
							'vars'=> $Req->get('email','password')
						)
					)
				);
			}
			return $result;
		}
	}

	function snipForm( )
	{
		require_once 'form/form.class.php';
		$formFile = $this->Ctrl->getConf('root.dir').'/conf/form.user.login.conf';
		$form     = new NyaaForm( );
		$form->loadFile($formFile);
		$form->addHidden('__FORM__','user.login.apply');
		$form->addHidden('__APPLY_FROM__','user.login.form');
		$bind = array(
			'form'      => $form,
			'email'     => $form->getInput('email'),
			'password'  => $form->getInput('password'),
			'saveLogin' => $form->getInput('saveLogin'),
			'toRegist'  => $form->getInput('toRegist'),
			'signin'    => $form->getInput('signin')
		);

		$result = $this->Ctrl->getResult('user.login.form');
		if(!empty($result) )
		{
			$bind['errors'] = $result->getOr('errors', array());
			$form->setValues($result->getOr('request',array()));
		}
		return $bind;
	}
}
?>
