<?php

class NyaaTemplaterCompilerElse extends NyaaTemplaterCompiler
{

	function isBlock( )
	{
		return false;
	}

	function compile( $name, $opt, $text, $templater)
	{
		$ret  = '<?php else: ?>';
		return $ret;
	}

}
?>
