<?php
class BbsApp extends NyaaControllerApp
{
	function run( )
	{
		$Tpl = $this->getTemplater( );
		return $Tpl->fetch('bbs.html');
	}
}
?>
