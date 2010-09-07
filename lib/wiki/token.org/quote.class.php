<?php
/**
 * ----
 * Wiki Token
 *
 * @author Hajime MATSUMOTO
 */
require_once 'wiki/token.class.php';

/**
 * wiki token for quote
 */
class NyaaWikiQuoteTK extends NyaaWikiTag
{
	const REGEXP = <<<FIN
^\{\{\{ 
(?: ([^|]+) \|){0,1}
([^{}]*)
(\}\}\}){0,1}
FIN;

	function canChild( $t )
	{
		return false;
	}

	function __construct( $parser, $v )
	{
		parent::__construct( 'blockquote' );

		$this->parser = $parser;

		if( preg_match('/'.$this::REGEXP.'/x', $v, $m) )
		{
			$this->setAttrs( $m[1] );
			$this->text = $m[2];
		}

		if( $this->isIncomplate( $m ) )
		{
			$this->capStart( array($this, 'input') );
		}
	}

	function getHeadTag( )
	{
		if( $this->getAttr('breakline') )
		{
			$this->tag = 'pre';
		}
	
		$ret = $this->hasAttr('title') ? $this->getAttr('title').":<br />": "";
		$ret.= parent::getHeadTag( );
		return $ret;
	}

	/**
	 * is incomplate
	 *
	 * @param $m array
	 * @return bool
	 */
	function isIncomplate( $m )
	{
		return $m[1] == "}}}" ? false: true;
	}

	/**
	 * input 
	 */
	function input( $line )
	{
		$this->text .= "\n". $line;

		if( preg_match('/^\}\}\}/xms', $line ) )
		{
			if( preg_match('/(.*)\}\}\}(.*)/xms', $this->text, $m ) )
			{
				$this->text = $m[1];
				//$this->parser->subParse( $this, $this->text );
				foreach( preg_split('/\n/', $this->text) as $t ){
					$this->parser->subParse( $this->last, $t );
				}
				return $m[2];
			}
		}
		return false;
	}
}
?>
