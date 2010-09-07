<?php
class NyaaTemplaterModifierNl2br extends NyaaTemplaterModifier 
{
	function execute( $name, $text, $templater )
	{
		return nl2br( $text );
	}
}
?>
