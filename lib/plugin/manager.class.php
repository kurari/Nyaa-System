<?php
/**
 * plugin manager
 */
class NyaaPluginManager
{
	public $plugins = array( );
	public $caller = "";

	/**
	 * caller
	 */
	function __construct( $caller = false )
	{
		$this->caller = $caller;
	}

	/**
	 * load directory
	 * @param Directory
	 */
	function loadDir( $dir, $prefix = "Nyaa" )
	{
		$d = dir($dir);
		while( false !== ($e = $d->read( )) )
		{
			if( $e[0] == "." ) continue;
			$info = explode(".",$e,2);
			$key = strtoupper($info[0]);
			$class = $prefix.ucfirst(strtolower($key));
			$this->add( $key, "$dir/$e", $class);
		}
	}

	/**
	 * add plugin
	 * @param $key
	 * @param $filePath
	 * @param $className
	 */
	function add( $key, $file, $class )
	{
		$this->plugins[$key] = array(
			'file'=>$file,
			'class'=>$class
		);
	}

	/**
	 * get plugin
	 * @param $name
	 * @rerun mixed
	 */
	function get( $name )
	{
		if( isset( $this->plugins[$name] ) ){
			$plugin = $this->plugins[$name];
			require_once $plugin['file'];
			$class = $plugin['class'];
			return new $class($this->caller);
		}
		return false;
	}

	/**
	 * has
	 * @param $name
	 * @return bool
	 */
	function has( $name )
	{
		return isset($this->plugins[$name]) ? true: false;
	}

	/**
	 * do execute
	 * @param $text
	 * @param ...
	 * @return mixed
	 */
	function execute( $name )
	{
		if( false !== $plugin = $this->get( $name ) )
		{
			return call_user_func_array( array($plugin,'execute'), func_get_args());
		}
		return false;
	}

	/**
	 * dump
	 */
	function dump( )
	{
		echo "<pre>";
		var_dump( $this->plugins );
		echo "</pre>";
	}
}
?>
