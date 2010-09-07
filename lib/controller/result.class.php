<?php
/**
 * Result 
 */

class NyaaControllerResult extends NyaaStore
{
	protected $Ctrl;
	public $status = "success";

	function __construct( $Ctrl )
	{
		parent::__construct( );
		$this->set('status',$this->status);
	}

	function getStatus( )
	{
		return $this->get('status');
	}

	function isRedirect( )
	{
		return $this->get('status') == 'redirect' ? true : false;
	}
	function isError( )
	{
		return $this->get('status') == 'error' ? true : false;
	}
}

?>
