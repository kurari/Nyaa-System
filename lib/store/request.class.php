<?php
/**
 * Request
 *
 */
require_once 'store/store.class.php';

/**
 * Request
 *
 * @package store
 */
class NyaaStoreRequest extends NyaaStore {

	function __construct( ){
		parent::__construct( );
	}

	function addPathInfo( $path )
	{
		if( preg_match_all('/[\/]{0,1}([^\/]+)\/([^\/]+)[\/]{0,1}/',$path, $m ) ){
			$info = array_combine($m[1], $m[2]);
			$this->set( $info );
		}
		$this->set('PATHINFO', $path);
	}

	function addPost( $POST )
	{
		$this->set( $POST );
	}

	function addGet( $GET )
	{
		$this->set( $GET );
	}

	function slicePath( $key )
	{
		$flg = false;
		$info = array();
		foreach( explode('/',$this->get('PATHINFO')) as $v )
		{
			if( $v == $key ) {
				$flg = true;
				continue;
			}
			if($flg) $info[] = $v;
		}
		return '/'.implode('/', $info);
	}
	
}
?>
