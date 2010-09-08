<?php
/**
 * プロジェクト管理パッケージ
 * プロジェクト閲覧
 * ----
 *
 */
require_once dirname(__FILE__).'/project.class.php';

class ProjectShowApp extends ProjectApp
{
	protected $view = 'project.show.html';

	function init( )
	{
		$this->view = 'project.show.html';
		$this->uid = $this->getSession('user.id');

		$this->set('uid', $this->uid);
		parent::init( );
	}

	function run( )
	{
		$this->set('project_id', $this->Request->id);
		return parent::run();
	}

	function snipInfo( $opt )
	{
		if(!isset($opt['id']))
			return  "閲覧権限がありません";

		$project = $this->getProjectById( $opt['id'] );
		if(!$project)
			return "データがありません";


		return $project;
	}
}
?>
