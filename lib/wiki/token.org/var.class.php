<?php
/**
 * ----
 * Wiki Token
 *
 * @author Hajime MATSUMOTO
 */
require_once 'wiki/token.class.php';

/**
 * var token for function
 */
class NyaaWikiVarTK extends NyaaParserTokenInline
{
	/**
	 * For Recurcive {{ }}
	 */
	const REGEXP = '\{\{
		(   
			(?:[^{}]  | 
			(?<!\{)\{ | 
			\}(?!=\}) |  
			\{\{.*?\}\}
		)*    
	)\}\}';

	public $name = "";


	function canChild( $t )
	{
		return false;
	}


	function __construct( $parser, $v )
	{
		parent::__construct( $parser );

		if( preg_match('/'.$this::REGEXP.'/x', $v, $m) )
		{
				$parser->subParse( $this, $m[1] );
		}

		if( $this->isIncomplate( $m ) )
		{
			$this->capStart( array($this, 'input') );
		}
	}

	/**
	 * is incomplate
	 *
	 * @param $m array
	 * @return bool
	 */
	function isIncomplate( $m )
	{
		return false;
	}
}
?>
