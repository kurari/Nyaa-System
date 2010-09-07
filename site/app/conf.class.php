<?php
/**
 * Configurater
 * ----
 *
 */
class ConfApp extends NyaaControllerApp
{
	function init( )
	{
		$this->view = 'conf.html';
	}

	function snipMenus()
	{
		// $files = glob(dirname(__FILE__).'/conf.*.class.php');
		$menus = array();

		$menus[] = array(
			'title'=>"基本設定",
			'url'=>"/app/conf"
		);
		$menus[] = array(
			'title'=>"Twitter",
			'url'=>"/app/conf.twitter"
		);

		return compact('menus');
	}

	function run( )
	{
		return parent::run( );
	}

}
?>
