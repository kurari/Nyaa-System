<?php
/**
 * Default Templater
 * ---
 * custom set up sample
 *
 */
require_once 'templater/templater.class.php';

// plugins 
require_once 'templater/plugin/resource.file.class.php';
require_once 'templater/plugin/resource.string.class.php';
require_once 'templater/plugin/compiler.foreach.class.php';
require_once 'templater/plugin/function.debug.class.php';
require_once 'templater/plugin/modifier.nl2br.class.php';
require_once 'templater/plugin/modifier.nohtml.class.php';
require_once 'templater/plugin/modifier.url.class.php';
require_once 'templater/plugin/compiler.if.class.php';
require_once 'templater/plugin/compiler.else.class.php';

class NyaaTemplaterDefault extends NyaaTemplater 
{
	function __construct( )
	{
		parent::__construct( );
		$this->_store = new NyaaStore( );
		$this->registerResourceHandler ( 'file',      new NyaaTemplaterResourceFile( ));
		$this->registerResourceHandler ( 'string',    new NyaaTemplaterResourceString( ));
		$this->registerCompilerHandler ( 'foreach',   new NyaaTemplaterCompilerForeach( ));
		$this->registerCompilerHandler ( 'if',        new NyaaTemplaterCompilerIf( ));
		$this->registerCompilerHandler ( 'else',      new NyaaTemplaterCompilerElse( ));
		$this->registerFunctionHandler ( 'debug',     new NyaaTemplaterFunctionDebug( ));
		$this->registerModifierHandler ( 'nl2br',     new NyaaTemplaterModifierNl2br( ));
		$this->registerModifierHandler ( 'url',       new NyaaTemplaterModifierUrl( ));
		$this->registerModifierHandler ( 'nohtml',    new NyaaTemplaterModifierNohtml( ));
	}
}
?>
