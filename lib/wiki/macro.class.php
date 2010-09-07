<?php
/**
 * Macro
 */
class NyaaWikiMacro
{
	var $handler;

	function __construct( $handler )
	{
		$this->handler = $handler;
	}

	function execute( $text, $node  )
	{
		nyaa_dump_html( func_get_args( ) );
	}

	function getArg( $text )
	{
		if(preg_match('/([^\(]*)(?:\((.*)\)){0,1}/', $text, $m )) {
			return isset($m[2]) ? $m[2]: false;
		}
	}

	function getCaller( )
	{
		return $this->handler;
	}
}
?>
