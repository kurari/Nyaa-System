<?php
/**
 * Nyaa Project
 * ----
 * this is Nyaa Project
 * ----
 * Parser Token
 *
 * @author Hajime Matumoto
 * @version 0.1
 */

/**
 * Parser Token
 */
class NyaaParserToken extends NyaaObject
{
	/**
	 * Internal Last Token
	 */
	public $last;

	/**
	 * Parent Token
	 */
	public $parent;

	/**
	 * child nodes
	 */
	public $children = array( );

	/**
	 * is capturing mode
	 */
	public $isCapt = false;

	/**
	 * construct
	 */
	public function __construct( )
	{
		parent::__construct( );
		$this->last = $this;
	}

	/**
	 * add node
	 *
	 * @param NyaaParserToken
	 * @return NyaaParserToken Last Token
	 */
	public function add( NyaaParserToken $n )
	{
		if( $this->canChild( $n ) )
		{
			return $this->insert( $n );
		}
		else
		{
			return $this->parent->add( $n );
		}
	}

	/**
	 * check before adding childnode
	 * 
	 * @param NyaaParserToken
	 * @return bool
	 */
	public function canChild( NyaaParserToken $n )
	{
		return true;
	}

	/**
	 * insert node
	 *
	 * @param NyaaParserToken
	 * @return NyaaParserToken 
	 */
	public function insert( NyaaParserToken $n )
	{
		$n->setParent( $this );
		$this->children[] = $n;
		return $n->last;
	}

	/**
	 * set parent node
	 *
	 * @param NyaaParserToken
	 */
	public function setParent( NyaaParserToken $n )
	{
		$this->parent = $n;
	}

	/**
	 * To String
	 */
	public function __toString( )
	{
		return get_class($this);
	}

	/**
	 * Visitor
	 *
	 * @param NyaaParserVisitor
	 */
	public function visitor( NyaaParserTokenVisitor $v )
	{
		if(false !== $v->visit( $this )){
			foreach( $this->children as $c ) 
				if(is_object($c) && is_subclass_of( $c, __CLASS__)) $c->visitor( $v );
		}
		$v->out( $this );
	}

	/**
	 * visitor accept
	 */
	public function accept( $v )
	{
		return $v->visit( $this );
	}

	/**
	 * Start Capturing
	 *
	 * @param function
	 */
	public function capStart( $func )
	{
		$this->isCapt = $func;
	}

	/**
	 * stop capturing
	 */
	public function capEnd( )
	{
		$this->isCapt = false;
	}

	/**
	 * is capturing
	 *
	 * @return bool
	 */
	public function isCap( )
	{
		return false !== $this->isCapt ? true: false;
	}

	/**
	 * @param $line string
	 */
	public function doCap( $line )
	{
		return call_user_func( $this->isCapt, $line );
	}

	/**
	 * Dump
	 */
	function dump( )
	{
		echo '<pre>';
		echo $this->accept( new NyaaParserTokenDumpVisitor( ) );
		echo '</pre>';
	}

	/**
	 * set attributes
	 * array or text 
	 * if it's text it will be parsed
	 * @param $attributes mixed  /([^=]+)=(?:"([^"]*)"|([^"\s]*))/
	 */
	function setAttrs( $mix )
	{
		if( is_string( $mix )  )
		{
			if( preg_match_all('/([^\s=]+)=(?|"([^"]*)"|([^"\s]*))/', $mix, $mm) )
				$this->setAttrs( array_combine( $mm[1], $mm[2] ) );
			return false;
		}
		foreach( $mix as $k=>$v )
			$this->setAttr( $k, $v );
	}

	/**
	 * set attribute
	 * @param $key string
	 * @param $val mixed
	 */
	function setAttr( $k, $v )
	{
		$this->attrs[$k] = $v;
	}

	/**
	 * if has attribute
	 * @param $key string
	 * @return bool
	 */
	function hasAttr( $k )
	{
		return isset($this->attrs[$k]);
	}

	/**
	 * get attr
	 * @param $key string
	 * @return mixed
	 */
	function getAttr( $k )
	{
		if( $this->hasAttr( $k ) )
		{
			return $this->attrs[$k];
		}
	}
}

?>
