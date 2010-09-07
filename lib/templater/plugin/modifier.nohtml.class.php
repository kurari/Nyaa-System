<?php


class NyaaTemplaterModifierNohtml extends NyaaTemplaterModifier 
{
	function execute( $name, $text, $templater )
	{
		return htmlspecialchars( $text );
	}
}
?>
