<?php
/**
 * date time macro
 */
require_once 'wiki/macro.class.php';

class NyaaWikiMacroBasename extends NyaaWikiMacro
{
	/**
	 * Date Time
	 */
	function execute( $text, $args )
	{
		return basename($args[0]);
	}
}
?>
