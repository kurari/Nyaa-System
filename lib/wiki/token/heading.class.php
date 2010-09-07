<?php
/**
 *
 */
require_once 'wiki/token/tag.class.php';

/**
 * Headering
 */
class NyaaWikiHeading extends NyaaWikiTag
{
	const REGEX = '^=.*=$';


	/**
	 * construct
	 */
	function __construct( $parser, $line)
	{
		$this->level = strspn( $line, '=' );
		$line = trim( $line, '=' );

		parent::__construct('div');
		$this->addClass("wiki-section-wrapper wiki-section-wrapper-".$this->level);

		$head = $this->last->insert( new NyaaWikiTag( 'h'.$this->level ) );
		$head->addClass("wiki-header wiki-header-".$this->level);
		$parser->subParse( $head->last, $line );
		$this->last = $this->last->insert( new NyaaWikiSection( $this->level ) );
	}

}

/**
 * Section
 */
class NyaaWikiSection extends NyaaWikiTag
{
	public $level;

	public $useParagraph = true;

	/**
	 * construct
	 */
	function __construct( $level )
	{
		parent::__construct( 'div' );
		$this->addClass("wiki-section wiki-section-$level");
		$this->level = $level;
	}

	/**
	 */
	function canChild( $tk )
	{
		return !is_a($tk, 'NyaaWikiHeading')  || $tk->level > $this->level;
	}
	/**
	 * wrap
	 */
	function wrap( $text, $indent = 0 )
	{
		$html = "\n".str_repeat("\t", $indent).$this->getHeadTag( );
		$html.= "\n".str_repeat("\t", $indent);
		$html.= $text;
		$html.= "\n".str_repeat("\t", $indent).$this->getEndTag( );
		return $html;
	}
}
?>
