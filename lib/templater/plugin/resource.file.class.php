<?php
class NyaaTemplaterResourceFile extends NyaaTemplaterResource
{
	public $expire = 86400; // 60 * 60 * 24

	function get( $path, $templater )
	{
		$tpldir = $templater->getTemplateDir( );
		$file   = $tpldir.'/'.$path;
		return file_get_contents( $file );
	}

	function hasCache( $path, $templater )
	{
		if($templater->cache == false)
			return false;
		$tpldir = $templater->getTemplateDir( );
		$org    = $tpldir.'/'.$path;

		$file   = $this->getCacheName( $path, $templater );
		if(!file_exists($file)) 
			return false;
		if( filemtime($org) > filemtime($file) || time() - filemtime($file) > $this->expire )
		{
			$templater->info('cache file expired %s', $file);
			return false;
		}
		return true;
	}

	function getCache( $path, $templater)
	{
		$file = $this->getCacheName( $path, $templater );
		return file_get_contents($file);
	}

	function writeCache( $path, $data, $templater )
	{
		$file = $this->getCacheName( $path, $templater );
		if( 0 < file_put_contents($file, $data))
		{
			return true;
		}
		$templater->warning("Can't write data to %s", $file);
	}

	function getCacheName( $path, $templater )
	{
		$dir = $templater->getCacheDir( );
		$type = __CLASS__;
		$file = sprintf("%s/%s.%s",$dir,$type,urlencode($path));
		return $file;
	}
}
?>
