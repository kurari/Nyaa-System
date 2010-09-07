<?php
require_once 'object/object.class.php';

/**
 * Store
 */
class NyaaStore extends NyaaObject implements IteratorAggregate{

	const CREATE_IF = true;

	private $_vars = array( );

	/**
	 * Construct
	 *
	 * @var $default
	 */
	public function __construct( $default = false ){
		parent::__construct( );

		if(is_array($default)){
			$this->_vars = $default;
		}
	}

	/**
	 * Swap Root
	 */
	public function swapRoot( &$value )
	{
		$this->_vars =& $value;
	}

	/**
	 * For Iterator
	 *
	 * @return array all_vars
	 */
	public function getIterator( ){
		return $this->_vars;
	}

	/**
	 * For String
	 *
	 */
	public function __toString( ){
		ob_start();
		$this->dump( );
		return ob_get_clean();
	}

	/**
	 * Getter For All
	 *
	 * @var $key
	 * @return mixed
	 */
	function __get( $k ) {
		return $this->get($k);
	}

	/**
	 * For Dump
	 */
	function dump( ){
		echo '<pre>';
		var_dump( $this->getIterator( ) );
		echo '</pre>';
	}

	/**
	 * Set
	 *
	 * @var $key
	 * @var $val
	 */
	function set( $key, $value = false ){
		if(is_array($key)){
			foreach( $key as $k=>$v ) $this->set( $k, $v);
			return true;
		}
		$w =& $this->getRef( $key, self::CREATE_IF );
		$w = $value;
	}

	/**
	 * Check If Store Has Key
	 *
	 * @var $key
	 * @return bool
	 */
	function has( $key ){
		$w = $this->getRef($key);
		return isset($w) ? true: false;
	}

	/**
	 * For get
	 *
	 * @var $key
	 * @return $mixed
	 */
	function get( $key = false ){
		if(func_num_args( ) > 1){
			$values = array();
			for($i=0;$i<func_num_args();$i++){
				$k = func_get_arg($i);
				$values[$k] = $this->get($k);
			}
			return $values;
		}
		return $this->getRef( $key );
	}

	/**
	 * For Get Or 
	 *
	 * @var $key
	 * @return mixed
	 */
	function getOr( $key, $default ){
		if( $this->has($key) && !$this->isEmpty($key)){
			return $this->get($key);
		}
		return $default;
	}

	/**
	 * Check If Empty
	 */
	function isEmpty( $key )
	{
		if( !$this->get($key) )
		{
			return true;
		}
		$val = $this->get($key);
		if(is_array($val) && count($val) == 0)
		{
			return true;
		}
		if(is_string($val) && empty($val) )
		{
			return true;
		}
	}

	/**
	 * Format
	 *
	 * @var $tpl
	 * @return string
	 */
	function format( $tpl ){
		$tpl = preg_replace('/\{\$(.*?)\}/e', '$this->get("\1")', $tpl);
		//$tpl = preg_replace('/\{lang:\s*(.*?)\s*\}/e', '$this->lang->getLangData("\1")', $tpl);
		$args = func_get_args();
		return vsprintf( $tpl, array_slice( $args, 1 ));
	}

	/** 
	 * Get Reference
	 *
	 * @var $key
	 * @var $ifCreates If key is not exists create
	 */
	function &getRef( $key = false, $flg = false ){
		$null = null;
		$keys = explode( '.', $key );

		if($key == false ) return $this->_vars;
		if(count($keys) == 1) return $this->_vars[$key];

		$w =& $this->_vars;
		foreach( $keys as $k ){
			// for {}
			if(substr($k, strlen($k)-2) == '{}'){
				$real = substr($k, 0, strlen($k)-2);
				if(!is_array($w[$real])) $w[$real] = array( );
				$w=& $w[$real][count($w[$real])];
				continue;
			}
			if(isset($w[$k]) or $flg) $w =& $w[$k];
			else return $null;
		}
		return $w;
	}

}
?>
