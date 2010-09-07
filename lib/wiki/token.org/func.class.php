<?php
/**
 * ----
 * Wiki Token
 *
 * @author Hajime MATSUMOTO
 */
require_once 'wiki/token.class.php';

/**
 * wiki token for function
 */
class NyaaWikiFuncTK extends NyaaParserTokenInline
{
	const REGEXP = <<<FIN
\&
([^\(]+)\( ([^)]*) \)  
(?:
	(\{)( (?:[^\{\}] | [\{][^\}]*[\}] )*)(\}{0,1})
){0,1}
FIN;

	public $text;
	public $func;
	public $args;

	function canChild( $t )
	{
		return false;
	}


	function __construct( $parser, $v )
	{
		parent::__construct( $parser );

		$this->parser = $parser;

		if( preg_match('/'.$this::REGEXP.'/x', $v, $m) )
		{
			$this->func = $m[1];
			$this->args = $m[2];
			$this->text = isset($m[4]) && !empty($m[4]) ? $m[4]: "";
		}

		if( $this->isIncomplate( $m ) )
		{
			$this->capStart( array($this, 'input') );
		}
	}

	function getFunc( )
	{
		return $this->func;
	}
	function getArgs( )
	{
		return $this->args;
	}
	function getText( )
	{
		return $this->text;
	}

	function __toString( )
	{
		$ret = parent::__toString( );
		$ret.= ' (func='.$this->func.')';
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
		if( !isset($m[3]) || !isset($m[5]) ){
			return false;
		}
		return $m[3] == "{" && $m[5] == "" ? true: false;
	}

	/**
	 * input 
	 */
	function input( $line )
	{
		$this->text .= "\n". $line;
		$num = substr_count( $this->text, '}' ) - substr_count( $this->text, '{' );
		if( $num == 1 )
		{
			if( preg_match('/(.*)\}(.*)/xms', $this->text, $m ) )
			{
				$this->text = $m[1];
				$this->parser->subParse( $this, $this->text );
				return $m[2];
			}
			return true;
		}
		return false;
	}
}
?>
