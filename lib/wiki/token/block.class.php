<?php
/**
 * usefull block
 */

class NyaaWikiBlock extends NyaaWikiTag 
{
	const REGEX = '\{\{\{(.*)(\}\}\}){0,1}';

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
		$num = substr_count( $this->text, '}}}') - substr_count( $this->text, '{{{' );
		if( $num >= 0 && preg_match('/
			\{\{\{  
				(?:(.*?)\|){0,1}
				(.*)
			\}\}\}(.*)
			/xms', $this->text, $m) ){
			$this->text = $text =  trim( $m[2] );
			$after = $m[3];

			$this->children[] = $this->text;
			return $after;


			if(preg_match_all('/\s*([^=]+)="([^"]*)"\s*/', $m[1], $mm)){
				$opt = array_combine( $mm[1], $mm[2] );
			}

			if(isset($opt['br'])){
				$text = str_replace("\n", "<br />", $this->text);
			}

			if(isset($opt['parse'])){
				foreach($this->parser->parse( $text )->children as $c)
					$this->children[] = $c;
			}else{
				$this->children[] = $text;
			}
			if(isset($opt['title'])){
				$last = array_pop($this->parent->children);
				$this->parent->children[] = $opt['title'].":";
				$this->parent->children[] = $last;
			}
			return $after;
		}
		return false;
	}

}
?>
