<?php
/**
 * Inline Token
 *
 * @author Hajime MATSUMOTO
 */

/**
 * Inline Token
 */
class NyaaParserTokenInline extends NyaaParserToken
{
	public $text;

	/**
	 * set text
	 *
	 * @parser $parser
	 * @parser $line
	 */
	function __construct( $parser, $line = false )
	{
		parent::__construct( );
		if( !empty($line) )
			$this->children[] = $line;
	}

	function __toString( )
	{
		$text = parent::__toString( );
		$text.= ":";
		return $text;
	}

	/**
	 * insert
	 */
	function insert( $t )
	{
		// merge inline's
		if( get_class($t) ==  __CLASS__ )
		{
			foreach( $t->children as $c ) $this->children[] = $c;
			return $this;
		}
		else
		{
			return parent::insert( $t );
		}
	}

	/**
	 * @param $t NyaaParserToken
	 */
	function canChild( NyaaParserToken $t )
	{
		return false;
	}
}
?>
