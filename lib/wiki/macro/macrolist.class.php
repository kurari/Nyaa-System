<?php
/**
 * macro list
 */
require_once 'wiki/macro.class.php';

class NyaaWikiMacroMacrolist extends NyaaWikiMacro
{
	/**
	 * Date Time
	 */
	function execute( )
	{
		$text = "";
		foreach( $this->getCaller()->macroManager->plugins as $m )
		{
			$text.= $m['class'];
			$text.= "<br />";
		}
		return $text;
	}
	
}
?>
