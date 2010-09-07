<?php

class NyaaTemplaterCompilerSnippet
{

	function isBlock( )
	{
		return true;
	}

	function compile( $name, $opt, $text, $templater)
	{
		$ret = '<?php ob_start(); ?>';
		$ret.= $text;
		$ret.= '<?php $this->snippet(';
		$ret.= $templater->getOptExported($opt);
		$ret.= ',';
		$ret.= 'ob_get_clean()';
		$ret.= ');?>';
		return $ret;
	}

}
?>
