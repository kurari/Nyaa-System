<?php
/**
 * Nyaa Object File
 *
 *
 */

/**
 * Class Nyaa Object
 */
require_once 'log/log.class.php';
class NyaaObject 
{
	/**
	 * Logger
	 */
	private $log;

	/**
	 * Construct
	 */
	public function __construct( )
	{
		$this->log = NyaaLog::getStack( );
	}

	/**
	 * Set
	 */
	public function setLogger( $log )
	{
		$this->log = $log;
		return $this->log;
	}

	/**
	 * Get Logger
	 */
	public function getLogger(  )
	{
		return $this->log;
	}

	/**
	 * Set Parent
	 */
	public function setParent( NyaaObject $o )
	{
		$this->setLogger( $o->getLogger() );
	}

	/**
	 * Prop Setter
	 * 
	 * @params string, mixed
	 */
	public function setProp( $key, $value )
	{
		if( in_array( $key, $this->accept ) )
		{
			$this->$key = $value;
			$this->debug( 'Proparty %s is set %s', $key, $value );
			return true;
		}
		$this->warning( 'Proparty %s is not able to set', $key );
		return false;
	}

	/**
	 * Prop Setter
	 * 
	 * @params string, mixed
	 */
	public function getProp( $key, $value = false )
	{
		if( in_array( $key, $this->accept ) )
		{
			return $this->$key;
		}
		$this->warning( 'Proparty %s is not acceccible', $key );
		return false;
	}

	/**
	 * Log Handling
	 */
	public function log( $type, $args )
	{
		$args[0] .= ' in '.get_class($this);
		if( is_a($this->log, 'NyaaLog') )
		{
			return call_user_func_array( array($this->log, $type), $args);
		}
		vprintf("[$type] %s<br />\n", vsprintf( $args[0], array_slice( $args, 1 ) ) );
	}

	/**
	 * Call Undefined
	 */
	public function __call( $func, $args )
	{
		if( in_array( $func, array('debug', 'info', 'notice', 'warning', 'error') ) )
		{
			return $this->log( $func, $args );
		}
		

		$trace = debug_backtrace( );
		@$file = isset($trace[1]['file']) ?$trace[1]['file']: $trace[0]['file'];
		@$line = isset($trace[1]['line']) ?$trace[1]['line']: $trace[0]['line'];
		$this->__call('error', array(
			'%s is not exists at %s in %s', 
			$func,
			$file,
			$line
		));
	}
}
?>
