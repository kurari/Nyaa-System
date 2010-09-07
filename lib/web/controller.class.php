<?php
require_once 'web/app.class.php';

class NyaaWebController 
{

	function factory( $conf )
	{
		$obj = new NyaaWebController( );
		$obj->conf = $conf;
		return $obj;
	}

	function getConf( )
	{ 
		return call_user_func_array( array($this->conf,'get'), func_get_args());
	}

	function getTemplater( ){
		require_once 'tpl/tpl.class.php';
		$tpl = new NyaaTpl( );
		$tpl->setTemplateDir( $this->getConf('template.dir') );
		return $tpl;
	}

	function call( $key )
	{
		$info = explode('.',$key,2);
		$service = $info[0];
		$function = $info[1];

		$file = $this->getConf('service.dir').'/'.$service.'.class.php';
		require_once $file;
		$class = ucfirst($service).'Service';
		$service = new $class($this);
		return call_user_func_array(array($service,$function),array());
	}

	function run( )
	{
		$file = $this->getConf('app.dir').'/system.default.class.php';
		require_once $file;
		$class = 'SystemDefaultApp';
		$app = new $class($this);
		echo $app->run( );
	}
}
?>
