<?php
/**
 * macro breadcrumbs
 */
require_once 'wiki/macro.class.php';

class NyaaWikiMacroBreadcrumbs extends NyaaWikiMacro
{
	function execute(  )
	{
		$pageName = $this->getCaller()->get("PAGENAME");
		$info = explode('/', $pageName);
		array_unshift( $info, "FrontPage" );
		while( $title = array_pop( $info ) ) {
			$link[] = $this->getCaller()->makeLink( $title.">".implode('/', $info)."/".$title );
		}
		return join('&nbsp;&gt;&nbsp;', array_reverse( $link ));
	}
	
}
?>
