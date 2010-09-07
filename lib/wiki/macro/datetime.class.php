<?php
/**
 * date time macro
 */
require_once 'wiki/macro.class.php';

class NyaaWikiMacroDatetime extends NyaaWikiMacro
{
	/**
	 * Date Time
	 */
	function execute( $text, $args )
	{
		$dateF = !empty($args[0]) ? $args[0]: "Y/m/d G:i:s";
		return date($dateF);
	}
}
?>
