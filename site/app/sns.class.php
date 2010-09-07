<?php
/**
 * User Home
 */
class SnsApp extends NyaaControllerApp
{
	protected $view = 'home.html';

	function init( )
	{
		$user = $this->Ctrl->appFactory('user');
		$uid  = $this->Ctrl->getSession()->get('user.id');
	}


	function snipMenus( )
	{
		$menu = array();
		$menu[] = array(
			'url' => $this->Ctrl->getConf('site.url').'/app/home',
			'title' => 'ホーム'
		);
		$menu[] = array(
			'url' => $this->Ctrl->getConf('site.url').'/app/profile',
			'title' => 'プロフィール'
		);
		$menu[] = array(
			'url' => $this->Ctrl->getConf('site.url').'/app/conf',
			'title' => '設定'
		);
		/*
		$menu[] = array(
			'url' => $this->Ctrl->getConf('site.url').'/app/sns.friends',
			'title' => '友達'
		);
		$menu[] = array(
			'url' => $this->Ctrl->getConf('site.url').'/app/home',
			'title' => '日記'
		);
		$menu[] = array(
			'url' => $this->Ctrl->getConf('site.url').'/app/home',
			'title' => '写真'
		);
		$menu[] = array(
			'url' => $this->Ctrl->getConf('site.url').'/app/home',
			'title' => 'メッセージ'
		);
		$menu[] = array(
			'url' => $this->Ctrl->getConf('site.url').'/app/home',
			'title' => '評価'
		);
		$menu[] = array(
			'url' => $this->Ctrl->getConf('site.url').'/app/home',
			'title' => 'コミュニティ'
		);
		$menu[] = array(
			'url' => $this->Ctrl->getConf('site.url').'/app/home',
			'title' => '設定'
		);
		$menu[] = array(
			'url' => $this->Ctrl->getConf('site.url').'/app/home',
			'title' => '足跡'
		);
		$menu[] = array(
			'url' => $this->Ctrl->getConf('site.url').'/app/home',
			'title' => 'ショップ'
		);
		 */
		return array('menus'=>$menu);
	}


	function snipFriendsmenus( $opt )
	{
		$id = $opt['id'];

		$menu = array();
		$menu[] = array(
			'url' => $this->Ctrl->getConf('site.url').'/app/sns.showProfile/id/'.$id,
			'title' => 'プロフィール'
		);
		$menu[] = array(
			'url' => $this->Ctrl->getConf('site.url').'/app/home',
			'title' => '日記'
		);
		$menu[] = array(
			'url' => $this->Ctrl->getConf('site.url').'/app/home',
			'title' => '写真'
		);
		$menu[] = array(
			'url' => $this->Ctrl->getConf('site.url').'/app/home',
			'title' => 'メッセージを送る'
		);
		$menu[] = array(
			'url' => $this->Ctrl->getConf('site.url').'/app/home',
			'title' => 'ショップ'
		);
		$menu[] = array(
			'url' => $this->Ctrl->getConf('site.url').'/app/home',
			'title' => '寄付'
		);
		$menu[] = array(
			'url' => $this->Ctrl->getConf('site.url').'/app/home',
			'title' => '自分のページ'
		);
		return array('menus'=>$menu);
	}
}
?>
