<?php

class NyaaMacroHandler
{
	/**
	 * wiki macro load
	 * @param Directory
	 */
	function loadMacro( $macroDir )
	{
		$d = dir($macroDir);
		while( false !== ($e = $d->read( )) )
		{
			if( $e[0] == "." ) continue;
			$info = explode(".",$e,2);
			$key = strtoupper($info[0]);
			$class = 'NyaaWikiMacro'.ucfirst($key);
			$this->addMacro( $key, $macroDir.'/'.$e, $class );
		}
	}

	/**
	 * add macro
	 * @param $key
	 * @param $filePath
	 * @param $className
	 */
	function addMacro( $key, $file, $class )
	{
		$this->macros[$key] = array(
			'file'=>$file,
			'class'=>$class
		);
	}

	/**
	 * get macro
	 * @param $name
	 * @rerun Macro
	 */
	function getMacro( $name )
	{
		if( isset( $this->macros[$name] ) ){
			$macro = $this->macros[$name];
			require_once $macro['file'];
			$class = $macro['class'];
			return new $class($this);
		}
		return false;
	}

	/**
	 * do macro
	 * @param $text
	 * @param $node
	 * @return mixed
	 */
	function doMacro( $text, $node )
	{
		$name = $text;
		$args = array();

		if(preg_match('/([^\(]*)(?:\((.*)\)){0,1}/', $text, $m )) {
			$name = $m[1];
			$args_text = isset($m[2]) ? $m[2]: false;

			if( false !== $args_text ){
				foreach( explode(',',$args_text) as $k=>$v ) 
				{
					$args[$k] = $v;
				}
			}
		}

		if( false !== $macro = $this->getMacro( $name ) )
		{
			return $macro->execute( $name, $args,  $node );
		}
		return $this->getOr( $text, "no:". $text);
	}
}
?>
