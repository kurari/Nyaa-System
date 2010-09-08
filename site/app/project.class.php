<?php
/**
 * プロジェクト管理パッケージ
 * ----
 *
 */
class ProjectApp extends NyaaControllerApp
{
	protected $view = 'project.html';

	function init( )
	{
		$this->db = $this->dbFactory('sqlite:localhost/'.$this->getConf('root.dir').'/data/project.db');
		$this->uid = $this->getSession('user.id');
	}

	function getProjectById( $id )
	{
		$sth = $this->db->prepare('SELECT * FROM project WHERE id=:id');
		$sth->bindParam('id', $id, 'int');
		$sth->execute();
		return $sth->fetch();
	}

	function snipMenus( )
	{
		$menu = array();
		$menu[] = array(
			'url' => $this->Ctrl->getConf('site.url').'/app/project.new',
			'title' => 'プロジェクト作成'
		);
		$menu[] = array(
			'url' => $this->Ctrl->getConf('site.url').'/app/project.myproject',
			'title' => 'マイプロジェクト'
		);
		$menu[] = array(
			'url' => $this->Ctrl->getConf('site.url').'/app/project.mytask',
			'title' => 'マイタスク'
		);
		return array('menus'=>$menu);
	}

	function snipList( )
	{
		return array('list'=>$this->db->query('SELECT * FROM project;'));
	}
}
?>
