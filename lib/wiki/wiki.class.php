<?php
/**
 * Wiki
 *
 */
require_once 'wiki/parser.class.php';
require_once 'wiki/render.class.php';

class Wiki 
{
	function __construct( )
	{
		parent::__construct( );
	}

	function init( )
	{
	}

	/**
	 * Run
	 */
	function run( )
	{
		// get file path
		$arrInfo = explode('/', $this->request->get('PATHINFO'), 2 );
		$page    = isset($arrInfo[1]) && !empty($arrInfo[1])? $arrInfo[1]: "FrontPage";
		$page    = urlencode( $page );
		$file    = $this->get('WikiDataDir').'/'.$page;

		// set enviroment
		$this->set('PAGENAMEE', $page );
		$this->set('PAGENAME', urldecode($page));

		if( !file_exists($file) ) {
			$file = ROOT.'/WikiData/'.urlencode("System/NotFound");
		}

		$result = $this->parser->parseFile( $file );
		echo $result->accept( $this );
	}
}
?>
