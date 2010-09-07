<?php
/**
 * macro set
 */
class NyaaWikiMacroSet extends NyaaWikiMacro
{
	/**
	 * set
	 */
	function execute( $text, $args )
	{
		$key = $args[0];
		$val = $args[1];
		$this->getCaller()->set( $key, $val );
		return false;
	}
	
}
?>
