<?php
require_once dirname(__FILE__).'/sns.class.php';

class SnsFriendsApp extends SnsApp
{
	protected $view = 'sns.friends.html';

	function snipSearch( $opt )
	{
		$user = $this->Ctrl->appFactory('user');
		$users = $user->search( );
		return array('users'=>$users);
	}
}
?>
