<?php
/**
 * tag token
 *
 * @author Hajime MATSUMOTO
 */

/**
 * tag token
 */
class NyaaWikiTag extends NyaaWikiToken
{
	/**
	 * tag name
	 */
	public $tag;

	/**
	 * if set true inline whould be in paragraph
	 */
	public $useParagraph = false;

	/**
	 * style
	 */
	public $style = array( );

	/**
	 * class
	 */
	public $classes = array('wiki');

	/**
	 * @param $tag string tagname
	 */
	function __construct( $tag )
	{
		parent::__construct( );
		$this->tag = $tag;
	}

	function __toString( )
	{
		return parent::__toString()."->".$this->tag;
	}

	/**
	 * add style's
	 * @param $text string
	 */
	function addStyle( $text )
	{
		if( preg_match_all('/\s*([^:]+)\s*:\s*([^;]+)[;]{0,1}/', $text, $m) )
		{
			$this->style = array_unique( 
				array_merge( $this->style, array_combine( $m[1], $m[2] ) ) 
			);
		}
	}
	/**
	 * add class
	 * @param $text string
	 */
	function addClass( $text )
	{
		foreach( preg_split('/\s+/', $text) as $v )
		{
			$this->classes[] = $v;
		}
		$this->classes = array_unique( $this->classes );
	}

	/**
	 * get tag etc
	 */
	function getTagAttrs( )
	{
		$etc = "";
		if( is_array( $this->style ) ){
			$etc .= ' style="';
			foreach( $this->style as $k=>$v ) $etc .= "$k:$v;";
			$etc .= '"';
		}
		if( is_array( $this->classes ) ){
			$etc .= ' class="'.join(" ", $this->classes).'"';
		}
		return $etc;
	}

	function getHeadTag( )
	{
		return '<'.$this->tag.$this->getTagAttrs().'>';
	}

	function getEndTag( )
	{
		return '</'.$this->tag.'>';
	}

	/**
	 * wrap
	 */
	function wrap( $text, $indent = 0 )
	{
		$html = "\n".str_repeat("\t", $indent).$this->getHeadTag( );
		$html.= $text;
		$html.= $this->getEndTag( );
		return $html;
	}

	/**
	 * @param $tk
	 */
	function insert( $tk )
	{
		if( 
			$this->useParagraph === true && 
			is_a($tk, 'NyaaParserTokenInline')
		){
			$p = new NyaaWikiParagraph( );
			$p->insert( $tk );
			return parent::insert( $p );
		}
		return parent::insert( $tk );
	}
}
?>
