<?php
/**
 * pre formated text
 */
class NyaaWikiPre extends NyaaWikiTag 
{
	const REGEX = '^\{\{\{(.*)(\}\}\}){0,1}';

	function __construct( $parser, $line )
	{
		parent::__construct('pre');

		$this->parser = $parser;
		$this->text = $line;
		$this->addClass('wiki-box');
		if(preg_match('/'.self::REGEX.'/x', $line, $m ) ){
			if( empty($m[2]) ){
				$this->capStart( array( $this, 'input' ) );
			}
		}
	}

	/**
	 * input
	 */
	function input( $line )
	{
		$this->text .= "\n".$line;
		if( preg_match('/^\}\}\}/', $line) )
		{
			if(preg_match('/^\{\{\{ (?:([^{}|]+)\|){0,1} (.*) \}\}\}(.*)/xms', $this->text, $m) 
			){
				$this->setAttrs( $m[1] );
				$this->text = $text = trim($m[2],"\n");
				$after = $m[3];
				// execute source-highlight command
				if( $this->hasAttr('highlight') ){
					$this->tag = "div";
					$type = $this->getAttr('highlight');
					$cmd = "/usr/bin/source-highlight -n -s $type";
					$proc = proc_open( $cmd, array(
						0=>array("pipe","r"), 
						1=>array("pipe","w"), 
						2=>array("file","/tmp/error","w")
					), $pipes);

					fwrite($pipes[0], $this->text);
					fclose($pipes[0]);
					$this->text = stream_get_contents($pipes[1]);
					fclose($pipes[1]);
					$return_value = proc_close($proc);
				}
				$this->children[] = $this->text;
				return $after;
			}
			return true;
		}
		return false;
	}

}
?>
