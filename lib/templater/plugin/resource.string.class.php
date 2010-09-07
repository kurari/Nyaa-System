<?php
class NyaaTemplaterResourceString extends NyaaTemplaterResource
{
	public $expire = 20;

	function get( $path, $templater )
	{
		return $path;
	}

	function hasCache( $path, $templater )
	{
		return false;
	}

	function getCache( $path, $templater)
	{
		return "";
	}

	function writeCache( $path, $data, $templater )
	{
		return "";
	}

	function getCacheName( $path, $templater )
	{
		return false;
	}
}
?>
