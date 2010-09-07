<?php
class SystemApp extends NyaaControllerApp
{
	function init( )
	{
		$this->user = $user = $this->Ctrl->appFactory('user');
		$this->uid  = $this->Ctrl->getSession()->get('user.id');
	}

	function snipMenus( )
	{
		$menu = array();

		if(!empty($this->uid))
		{
			// Logined
			$menu[] = array(
				'url' => $this->Ctrl->getConf('site.url').'/app/home',
				'title' => 'HOME'
			);
		}
		$menu[] = array(
			'url' => $this->Ctrl->getConf('site.url').'/app/system.default',
			'title' => 'TOP'
		);
		$menu[] = array(
			'url' => $this->Ctrl->getConf('site.url').'/app/chat',
			'title' => 'CHAT'
		);
		$menu[] = array(
			'url' => $this->Ctrl->getConf('site.url').'/app/bbs',
			'title' => 'BBS'
		);
		$menu[] = array(
			'url' => $this->Ctrl->getConf('site.url').'/app/wiki',
			'title' => 'WIKI'
		);
		return array('menus'=>$menu);
	}
}
?>
