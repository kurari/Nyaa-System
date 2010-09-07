<?php
/**
 * paragraph
 */
class NyaaWikiParagraph extends NyaaWikiTag
{
	//const REGEX = "^~(.*)";

	var $tag = "p";

	function __construct( $text = false )
	{
		parent::__construct("p");
		if( $text !== false ) {
			$this->children[] = $text;
		}
	}

	function canChild( $tk )
	{
		return is_a($tk,'NyaaParserTokenInline');
	}

	function insert( $tk )
	{
		if( 
			count($this->children) == 0  && 
			is_a($tk, 'NyaaParserTokenInline') &&
			is_string($tk->children[0])
		){
			if( '~' === $tk->children[0][0] ){
				$tk->children[0] = substr($tk->children[0],1);
				$this->addClass('wiki-paragraph-indent');
			}
		}
		return parent::insert($tk);
	}
}
?>
