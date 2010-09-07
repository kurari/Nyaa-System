<?php
class NyaaTemplaterModifierUrl extends NyaaTemplaterModifier 
{
	function execute( $name, $text, $templater )
	{
		return preg_replace('#((?<!")http://[^\s]+)#','<a href="\1" target="_blank">\1</a>', $text );
	}
}
?>
