<?php
/**
 * Link
 */
class NyaaWikiLink extends NyaaWikiTag
{
	const REGEX = '\[\[(.*)\]\]';

	function __construct( $parser, $line )
	{
		parent::__construct( 'a' );
		if( preg_match('/'.self::REGEX.'/',$line,$m) )
		{
			$parser->subParse( $this, $m[1] );
		}
	}

	/**
	 * extend getTagAttrs method
	 * @return text
	 */
	function getTagAttrs( )
	{
		$text = parent::getTagAttrs( );
		$text.= sprintf(' href="%s"', $this->getAttr('href'));
		return $text;
	}

}
?>
