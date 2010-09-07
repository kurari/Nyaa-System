<?php
require_once 'wiki/parser.class.php';
require_once 'wiki/render.class.php';

class WikiApp extends NyaaControllerApp
{
	public $stylesheets = array( );
	public $dataDir = "";
	public $baseUrl = "";

	function init( )
	{
		$this->dataDir = $this->Ctrl->getConf('root.dir').'/data/wiki';
		$this->baseUrl = $this->Ctrl->getConf('site.url').'/app/wiki/page/';
		$this->stylesheets[] = 'wiki.css';
		$this->parser = new NyaaWikiParser( );
		$this->render = new NyaaWikiRender( );
	}

	function snipWiki( $opt, $template )
	{
		$res = $this->parser->parse($template);
		return $res->accept( $this->render );
	}


	function getPage( $pageName )
	{
		$page   = $this->dataDir . '/' . urlencode($pageName);
		$parser = new NyaaWikiParser( );
		$render = new NyaaWikiRender( );
		$render->set('PAGENAME',$pageName);
		$render->set('BASEURL',$this->baseUrl);
		$res   = $parser->parse(file_get_contents($page));
		return array('main'=>$res->accept( $render ));
	}

	function run( )
	{
		$pageName =  $this->Request->getOr('page','FrontPage');
		$data     =  $this->getPage( $pageName );
		$Tpl      =  $this->getTemplater( );
		$main     =& $Tpl->getRef('wikiMain');
		$main     =  $data['main'];
		return $Tpl->fetch('wiki.main.html');
	}
}
?>
