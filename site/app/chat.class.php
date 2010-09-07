<?php
class ChatApp extends NyaaControllerApp
{
	function run( )
	{
		$Tpl = $this->getTemplater( );
		return $Tpl->fetch('chat.html');
	}
}
?>
