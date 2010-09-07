<?php
/**
 * Web Templater
 * ----
 *
 *
 */
require_once 'templater/templater.default.class.php';

/**
 * Snippet Template Compiler
 * ----
 * Type: Template Plugin
 */
class NyaaControllerTplCompilerSnippet extends NyaaTemplaterCompiler
{
	function isBlock( $name, $opt)
	{
		if( "/" == $opt[strlen($opt)-1]) 
		{
			return false;
		}
		return true;
	}

	function compile( $name, $opt, $text, $templater)
	{
		/*
		$copt  = $templater->getOpt($opt);
		if(isset($copt['template'])){
			$text = $templater->getResource(trim($copt['template'],'"'));
		}
		 */
		$opt  = $templater->getOptExported($opt);
		$ret  = "";
		$ret .= '<?php'."\n";
		$ret .= 'ob_start();';
		$ret .= '?>';
		$ret .= $text;
		$ret .= '<?php'."\n";
		$ret .= '$contents = ob_get_clean();'."\n";
		$ret .= 'echo $this->get(\'Ctrl\')->snippet('.$opt.',$contents,$this);'."\n";
		$ret .= '?>';
		return $ret;
	}
}

/**
 * View Templater
 */
class NyaaControllerWebTemplater extends NyaaTemplaterDefault
{
	function __construct( $Conf )
	{
		parent::__construct( );
		$this->templateDir     = $Conf->get('template.dir');
		$this->cacheDir        = $Conf->get('template.cache');
		$this->leftDelimiter   = '{{';
		$this->rightDelimiter  = '}}';
		$this->registerCompilerHandler ( 'snippet',   new NyaaControllerTplCompilerSnippet( ));
		parent::__construct( );
	}
}
?>
