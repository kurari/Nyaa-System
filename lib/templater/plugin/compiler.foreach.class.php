<?php
class NyaaTemplaterCompilerForeach extends NyaaTemplaterCompiler
{

	function isBlock( )
	{
		return true;
	}

	function compile( $name, $opt, $text, $templater)
	{
		$opt    = $templater->getOpt($opt);
		$from   = $templater->compileVarLine($opt['from']);
		$key    = $opt['key'];
		$value  = $opt['value'];
		$ret   	= "";
		$ret   .= '<?php'."\n";
		$ret   .= '$from   = '.$from.';'."\n";
		$ret   .= 'if(is_array($from) || is_object($from))'."\n";
		$ret   .= 'foreach( $from as $key=>$value ):'."\n";
		$ret   .= '$this->set("'.$key.'", $key);'."\n";
		$ret   .= '$this->set("'.$value.'", $value);'."\n";
		$ret   .= '?>';
		$ret   .= $templater->compile( $text );
		$ret   .= '<?php'."\n";
		$ret   .= 'endforeach;'."\n";
		$ret   .= '?>';
		return $ret;
	}

}
?>
