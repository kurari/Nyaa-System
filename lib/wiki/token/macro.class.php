<?php
/**
 * macro 
 */
class NyaaWikiMacroToken extends NyaaParserTokenInline
{
	const REGEX = '\{\{ (.*) \}\}';

	function __construct( $parser, $line )
	{
		parent::__construct( $parser );
		if( preg_match('/'.self::REGEX.'/x',$line,$m) )
		{
			$parser->subParse( $this, $m[1] );
		}
	}


}
?>
