<?php
require_once dirname(__FILE__).'/user.class.php';

class UserLogoutApp extends UserApp
{
	function run( )
	{
		$this->logout( );
		$this->Ctrl->redirect('#home');
	}
}
?>
