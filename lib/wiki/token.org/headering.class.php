<?php
/**
 * ----
 * Wiki Token
 *
 * @author Hajime MATSUMOTO
 */
require_once 'wiki/token.class.php';

/**
 * headering
 */
class NyaaWikiHeaderingTK extends NyaaWikiToken
{
	const REGEXP = '^=.*=$';

	function __construct( $parser, $line )
	{
		parent::__construct( );

		$this->level = strspn( $line, '=');

		$line = trim(trim( $line, '='));

		$header = $this->last->insert( new NyaaWikiTag('h'.$this->level) );
		$parser->subParse( $header, $line );
		$this->last = $this->last->insert( new NyaaWikiSection( $this->level ) );
	}

	function __toString( )
	{
		$ret = parent::__toString( ) .":". $this->level;
		return $ret;
	}


	function canChild( $token )
	{
		return false;
		return !is_a($token, 'NyaaWikiHeaderingTK') || $token->level > $this->level;
	}
}

/**
 * tag
 */
class NyaaWikiTag extends NyaaWikiToken
{
	public $tag;

	function __construct( $tag )
	{
		parent::__construct( );
		$this->tag = $tag;
	}

	function __toString( )
	{
		return parent::__toString()."->".$this->tag;
	}

	function getHeadTag( )
	{
		return '<'.$this->tag.'>';
	}

	function getEndTag( )
	{
		return '</'.$this->tag.'>';
	}
}

/**
 * single tag
 */
class NyaaWikiSingleTag extends NyaaWikiTag
{
	function getHeadTag( )
	{
		return '<'.$this->tag.'/>';
	}

	function getEndTag( )
	{
		return false;
	}
}

/**
 * section
 */
class NyaaWikiSection extends NyaaWikiTag
{
	public $level;

	function __construct( $level )
	{
		$this->level = $level;
		parent::__construct('div');
	}

	function canChild( $token )
	{
		return !is_a($token, 'NyaaWikiHeaderingTK') || $token->level > $this->level;
	}
}
?>
