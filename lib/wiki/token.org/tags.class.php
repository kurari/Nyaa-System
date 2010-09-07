<?php
/**
 * ----
 * Wiki Token
 *
 * @author Hajime MATSUMOTO
 */
require_once 'wiki/token.class.php';

/**
 * hr
 */
class NyaaWikiHrTK extends NyaaWikiToken
{
	const REGEXP = '^----';

	function __construct( $parser, $line )
	{
		parent::__construct( );

		$this->insert( new NyaaWikiSingleTag('hr') );
	}

	function canChild( $token )
	{
		return false;
	}
}

?>
