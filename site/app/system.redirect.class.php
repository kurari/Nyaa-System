<?php
/**
 * System Default
 */
class SystemRedirectApp extends NyaaControllerApp
{

	public function run( )
	{
		$Tpl = $this->getTemplater( );
		return $Tpl->fetch('system.redirect.html');
	}


}
?>
