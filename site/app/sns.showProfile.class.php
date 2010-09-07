<?php
require_once dirname(__FILE__).'/sns.class.php';

class SnsShowProfileApp extends SnsApp
{
	protected $view = 'sns.showProfile.html';

	function snipProfile( $opt )
	{
		$Profile = $this->Ctrl->appFactory('profile');
		return $Profile->getProfile($opt['id']);
	}

	function run( )
	{
		$id = $this->Request->get('id');
		$profile = $this->snipProfile(array('id'=>$id));
		$this->set('profile', $profile);
		$this->set('id', $id);
		return parent::run();
	}

}
?>
