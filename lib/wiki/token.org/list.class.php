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
class NyaaWikiListTK extends NyaaWikiListContainer
{
	const REGEXP = '^\s*\*(.*)';

	function __construct( $parser, $line )
	{
		$this->level = strspn( $line, ' ');
		parent::__construct( 'ul', 'li', $this->level );
		$line = substr( $line, $this->level + 1 );
		$this->last = $this->last->insert( $parser->lineFactory( $line ) );
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
		$opt = new NyaaWikiList( $optTag );
		$this->last = $this->last->insert( $opt );
	}

	function canChild( $tk )
	{
		return !is_a( $tk, 'NyaaParserTokenNull') && 
			( !is_a( $tk, 'NyaaWikiListContainer' ) || $this->level < $tk->level );
	}
}

class NyaaWikiList extends NyaaWikiTag
{
	function canChild( $tk )
	{
		return 
			!is_a( $tk, 'NyaaParserTokenNull' ) && 
			!is_a( $tk, 'NyaaWikiListContainer');
	}
}
?>
