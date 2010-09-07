<?php
class NyaaWebApp 
{
	function __construct( $handler )
	{
		$this->handler = $handler;
	}

	function getHandler( )
	{
		return $this->handler;
	}

	function getTemplater( )
	{
		$tpl = $this->getHandler()->getTemplater( );
		return $tpl;
	}

	function run( )
	{
		$tpl = $this->getTemplater( );
		$tpl->set('handler', $this->getHandler());
		return $tpl->fetch('system.default.html');
	}
}
?>
