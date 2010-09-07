<?php
/**
 * ----
 * Wiki Token
 *
 * @author Hajime MATSUMOTO
 */
require_once 'wiki/token.class.php';

/**
 * link token for function
 */
class NyaaWikiLinkTK extends NyaaParserTokenInline
{
	const REGEXP = '\[\[([^\[\]]*)\]\]';

	public $name = "";


	function canChild( $t )
	{
		return false;
	}


	function __construct( $parser, $v )
	{
		parent::__construct( $parser );

		if( preg_match('/'.$this::REGEXP.'/x', $v, $m) )
		{
			$parser->subParse( $this, $m[1] );
		}

	}

}
?>
