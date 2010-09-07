<?php
/**
 * Wiki Render
 * ----
 */
require_once 'plugin/manager.class.php';

/**
 * Wiki Render
 */
class NyaaWikiRender extends NyaaStore
{
	function __construct( )
	{
		$this->macroManager = new NyaaPluginManager( $this );
	}

	/**
	 * wiki macro load
	 * @param Directory
	 */
	function loadMacro( $macroDir )
	{
		$this->macroManager->loadDir( $macroDir, 'NyaaWikiMacro' );
	}
	/**
	 * do macro
	 * @param $text
	 * @param $node
	 * @return mixed
	 */
	function doMacro( $text, $node )
	{
		$name = $text;
		$args = array();

		if(preg_match('/([^\(]*)(?:\((.*)\))/', $text, $m )) {
			$name = $m[1];
			$arg = isset($m[2]) ? $m[2]: false;
			if( false !== $arg ){
				foreach( explode(',',$arg) as $k=>$v ) $args[$k] = $v;
			}
		}
		if( false == $this->macroManager->has($name) )
			return $this->getOr( $text, "no:". $text);
		
		return $this->macroManager->execute( $name, $args, $node );
	}

	/**
	 * make link
	 * @param $link
	 * @return html
	 */
	function makeLink( $text )
	{
		if( preg_match('/ (?:(?P<alias>[^>]*)\>){0,1} (?P<path>.*) /x', $text, $m )){
			$name = isset($m['alias']) ? $m['alias']:  $m['path'];
			$path = $m['path'];
		}
		return sprintf('<a href="%s/%s">%s</a>', $this->get('BASEURL'), $path, $name);
	}

	/**
	 * visit
	 */
	function visit( $n, $cnt = 0 )
	{
		$ret = "";
		foreach( $n->children as $c )
		{
			if(!is_object($c)) $ret.=$c;
			else $ret.=$this->visit($c, $cnt+1);
		}

		if( is_a( $n, 'NyaaWikiLink' ) ){
			$n->setAttr( 'href', $this->get('BASEURL').$ret );
			return $n->wrap( $ret, $cnt );
		}
		if( is_a( $n, 'NyaaWikiTag' ) ){
			return $n->wrap( $ret, $cnt );
		}
		if( is_a( $n, 'NyaaWikiMacroToken' ) ){
			return call_user_func( array($this,'doMacro'), $ret, $n );
		}
		return $ret;
	}
}
?>
