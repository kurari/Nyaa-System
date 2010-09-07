<?php
/**
 * Nyaa Project
 * ----
 * visitor
 *
 * @author Hajime MATSUMOTO
 */
require_once 'store/store.class.php';
require_once 'parser/visitor.class.php';

/**
 * Wiki Visitor
 */
class NyaaWikiVisitor extends NyaaParserTokenVisitor
{

	function __construct(  )
	{
		parent::__construct( );
	}

	function loadMacro( $macroDir )
	{
		$d = dir($macroDir);
		while( false !== ($e = $d->read( )) )
		{
			if( $e[0] == "." ) continue;
			$info = explode(".",$e,2);
			$key = strtoupper($info[0]);
			$class = 'NyaaWikiMacro'.ucfirst($key);
			$this->macro->addMacro( $key, $macroDir.'/'.$e, $class );
		}
	}

	function defHandler( )
	{
		ob_start( );
		nyaa_dump_html( func_get_args( ) );
		return ob_get_clean( );
	}

	function setMacroHandler( $callback )
	{
		$this->macroHandler = $callback;
	}

	function setLinkHandler( $callback )
	{
		$this->linkHandler = $callback;
	}


	function visit( $n, $cnt = 0 )
	{
		$ret = "";
		foreach( $n->children as $c )
		{
			if(!is_object($c)) $ret.=$c;
			else $ret.=$this->visit($c, $cnt+1);
		}
		if( is_a( $n, 'NyaaWikiLink' ) ){
			$n->setAttr( 'href', '/wiki/index.php/'.$ret );
			return $n->wrap( $ret, $cnt );
		}
		if( is_a( $n, 'NyaaWikiTag' ) ){
			return $n->wrap( $ret, $cnt );
		}
		if( is_a( $n, 'NyaaWikiMacroToken' ) ){
			return call_user_func( $this->macroHandler, $ret, $n );
		}
		return $ret;
	}
}

/**
 * Bread Crumbs Link
 function doBreadCrumbs( )
 {
	 $pagename = urldecode($this->get('PAGENAME'));
	 $info = explode("/",$pagename);
	 $last = $this->doBaseName( $pagename );
	 $link = array( );
	 while( $name = array_pop($info) ){
		 $path =implode("/", $info);
		 if( $path == "" ) continue;
		 $link[] = sprintf('%s>%s', $path, $this->doBaseName($path) );
	 }
	 $link[] = sprintf('%s>%s', 'FrontPage', 'TOP');
	 $link = array_reverse($link);
	 array_walk( $link, array($this, 'doLinkWalk') );
	 $link[] = $last;
	 return join(">", $link);
 }

/**
 * @param val
 * @param key
 function doLinkWalk( &$val, $key ) {
	 $alias = $url = $val;
	 if( preg_match('/([^>]*)[>]{0,1}(.*)/', $val, $m) ){
		 $alias = $m[2];
		 $url = $m[1];
	 }
	 $url = '/wiki/index.php/'.$url;
	 $val = '<a href="'.$url.'">'.$alias.'</a>';
 }
 */

?>
