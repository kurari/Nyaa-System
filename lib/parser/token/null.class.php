<?php
/**
 * Nyaa Project
 * ----
 * Null Tolken
 *
 * @author Hajime MATSUMOTO
 */


/**
 * Null Token
 */
class NyaaParserTokenNull extends NyaaParserToken
{
	function canChild( NyaaParserToken $t )
	{
		return is_a( $t, __CLASS__ );
	}
	function insert( $t )
	{
		return $this;
	}
}
?>
