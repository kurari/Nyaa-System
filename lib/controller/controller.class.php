<?php
/**
 * Controller
 * ----
 *
 */
require_once 'conf/conf.class.php';
require_once 'store/request.class.php';

class NyaaController extends NyaaStore
{
	protected $Conf;
	protected $Request;

	public function setConf( $Conf )
	{
		$this->Conf = $Conf;
	}

	public function getConf( )
	{
		$args = func_get_args( );
		return call_user_func_array( array($this->Conf,'get'), $args );
	}

	public function request( $path )
	{
		$request = new NyaaStoreRequest( );
		$request->addPathInfo( $path );
		$args = func_get_args( );
		array_shift($args);
		while( !empty($args) )
			$request->set( array_shift($args ) );
		$this->Request = $request;
	}

	public static function factory( $name, $confFile, $addOption = array() )
	{
		$Conf = NyaaConf::load($confFile, $addOption);
		require_once dirname(__FILE__).'/'.$name.'.class.php';
		$class = 'NyaaController'.ucfirst(strtolower($name));
		$ctrl = new $class( );
		$ctrl->setConf( $Conf );
		return $ctrl;
	}


}
?>
