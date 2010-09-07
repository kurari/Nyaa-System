<?php
/**
 * ----
 * Parser Node Visitors
 *
 * ----
 * @author Hajime MATSUMOTO
 */
require_once 'store/store.class.php';

/**
 * Parser Node Visitor
 */
class NyaaParserTokenVisitor extends NyaaObject
{
	/**
	 * nest level
	 */
	public $nestLv = 0;

	/**
	 * store
	 */
	public $store;

	function __construct( )
	{
		parent::__construct( );
		$this->store = new NyaaStore( );
	}

	/**
	 * alias of set to store
	 */
	function set( )
	{
		return call_user_func_array( array($this->store, 'set'), func_get_args() );
	}

	/**
	 * alias of get to store
	 */
	function get( )
	{
		return call_user_func_array( array($this->store, 'get'), func_get_args() );
	}

	/**
	 * alias of getOr to store
	 */
	function getOr( )
	{
		return call_user_func_array( array($this->store, 'getOr'), func_get_args() );
	}

	/**
	 * Visit
	 *
	 * @param $n NyaaParserNode
	 */
	function visit( NyaaParserToken $n )
	{

	}
}
?>
