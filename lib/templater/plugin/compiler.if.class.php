<?php

class NyaaTemplaterCompilerIf extends NyaaTemplaterCompiler
{

	function isBlock( )
	{
		return true;
	}

	function compile( $name, $opt, $text, $templater)
	{
		$exp  = $templater->compileVarLine($opt);
		$ret 	= "";
		$ret .= '<?php if('.$exp.'):?>';
		$ret .= $templater->compile( $text );
		$ret .= '<?php endif; ?>';
		return $ret;
	}

}
?>
