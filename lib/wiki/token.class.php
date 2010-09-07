<?php
/**
 * ----
 * Wiki Token
 *
 * @author Hajime MATSUMOTO
 */
require_once 'parser/token.class.php';

/**
 * Wiki Token
 */
class NyaaWikiToken extends NyaaParserToken
{
	/**
	 * @return bool
	 */
	function canChild( ){ 
		return false; 
	}
}
?>
