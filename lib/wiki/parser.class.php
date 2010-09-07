<?php
/**
 * Wiki Parser
 * ----
 *
 */
require_once 'parser/parser.class.php';


/**
 *
 */
class NyaaWikiParser extends NyaaParser
{
	/**
	 * construct
	 */
	function __construct( )
	{
		require_once 'wiki/token.class.php';
		require_once 'wiki/token/heading.class.php';
		require_once 'wiki/token/pre.class.php';
		require_once 'wiki/token/paragraph.class.php';
		require_once 'wiki/token/list.class.php';
		require_once 'wiki/token/link.class.php';
		require_once 'wiki/token/macro.class.php';
		$this->enable(array(
			'NyaaWikiHeading',
			'NyaaWikiPre',
			'NyaaWikiList',
			'NyaaWikiLink',
			'NyaaWikiMacroToken'
		));
	}

	function quickParse( $text )
	{
		require_once 'wiki/render.class.php';
		$render = new NyaaWikiRender( );
		return $this->parse($text)->accept($render);
	}
}
?>
