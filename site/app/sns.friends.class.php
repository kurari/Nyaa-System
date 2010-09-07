<?php
require_once dirname(__FILE__).'/sns.class.php';

class SnsFriendsApp extends SnsApp
{
	protected $view = 'sns.friends.html';

	function init()
	{
		parent::init();

	}

	function snipSearch( $opt )
	{
		// プロフィールから検索用プロフィールを取得する
		$profile = $this->Ctrl->appFactory('profile');
		$data = $profile->getAll();
		$keys = array('screenName','location');
		$list = array( );
		foreach($data as $k=>$v)
		{
			if( in_array($v['name'],$keys) )
			{
				$list[$v['userid']][$v['name']] = $v['value'];
			}
		}
		return array('list'=>$list);
	}
}
?>
