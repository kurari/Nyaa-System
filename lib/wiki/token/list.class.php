<?php
/**
 * ----
 * Wiki Token
 *
 * @author Hajime MATSUMOTO
 */
require_once 'wiki/token.class.php';

/**
 * List
 */
class NyaaWikiList extends NyaaWikiListContainer
{
	const REGEX = '^\s*\*(.*)';

	function __construct( $parser, $line )
	{
		$this->level = strspn( $line, ' ');
		parent::__construct( 'ul', 'li', $this->level );
		$line = substr( $line, $this->level + 1 );
		// use subparse
		//$this->last = $this->last->insert( $parser->lineFactory( $line ) );
		$parser->subParse( $this->last, $line );
	}

	function __toString( )
	{
		$ret = parent::__toString( ) .":". $this->level;
		return $ret;
	}
}

/**
 * List Container
 */
class NyaaWikiListContainer extends NyaaWikiTag
{
	public $level = 0;

	function __construct( $tag, $optTag, $level )
	{
		parent::__construct( $tag );
		$this->level = $level;
		$opt = new NyaaWikiListLeaf( $optTag, $level );
		$this->last = $this->last->insert( $opt );
	}

	function canChild( $tk )
	{
		return is_a( $tk, 'NyaaWikiListContainer' ) && $tk->level == $this->level;
	}

	function insert( $tk )
	{
		if( is_a( $tk, 'NyaaWikiListContainer' ) && $tk->level == $this->level )
		{
			foreach($tk->children as $c) {
				$c->setParent($this);
				$this->children[] = $c;
				$this->last = $c;
			}
			return $c;
		}
		return parent::insert( $tk );
	}
}

/**
 * List Leaf
 */
class NyaaWikiListLeaf extends NyaaWikiTag
{
	public $level = 0;

	function __construct( $tag, $level )
	{
		parent::__construct( $tag );
		$this->level = $level;
	}


	function canChild( $tk )
	{
		return !is_a( $tk, 'NyaaParserTokenNull') && 
			( !is_a( $tk, 'NyaaWikiListContainer' ) || $this->level < $tk->level );
	}

}
?>
