<?php
/**
 * Nyaa Project
 * ----
 * escape token
 *
 * @author Hajime MATSUMOTO
 */

/**
 * Escape 
 */
class NyaaParserTokenEscape extends NyaaParserToken
{
	const REGEX = '`( (?:[^`]|(?<=[\\\\])[`])* )`';

	/**
	 * set text
	 */
	function __construct( $parser, $line = "" )
	{
		parent::__construct( );
		if( preg_match('/'.self::REGEX.'/x', $line, $m) )
		{
			$this->children[] = preg_replace('/[\\\\](.)/x', '\1', $m[1]);
		}
	}

	function canChild( $tk )
	{
		return false;
	}
}
?>
