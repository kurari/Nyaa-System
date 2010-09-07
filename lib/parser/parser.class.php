<?php
/**
 * Nyaa Project
 * ----
 * this is Nyaa Project
 * ----
 * 
 * Extendable Parser
 *
 * @author Hajime Matumoto
 * @version 0.1
 */
require_once "object/object.class.php";
require_once "parser/visitor.class.php";
require_once "parser/token.class.php";
require_once "parser/token/escape.class.php";
require_once "parser/token/inline.class.php";
require_once "parser/token/null.class.php";
require_once "parser/visitor/dump.class.php";

/**
 * Parser Root Class
 */
class NyaaParser extends NyaaObject
{
	/**
	 * patterns
	 */
	public $patterns = array('ptn0'=> array(
		'regex'=>NyaaParserTokenEscape::REGEX,
		'class'=>'NyaaParserTokenEscape'
	));

	/**
	 * Parser Factory
	 *
	 * @param $name
	 */
	public static function create( $name )
	{
		require_once "parser/parser_$name.class.php";
		$class =  'NyaaParser'.ucfirst($name);
		$parser = new $class(  );
		return $parser;
	}

	/**
	 * Create Root Node
	 *
	 * @return NyaaParserNode
	 */
	public function createRoot( )
	{
		return new NyaaParserToken( );
	}

	/**
	 * Create Line
	 * @param $text string
	 */
	public function lineFactory( $line )
	{
		if($line == ""){
			return new NyaaParserTokenNull( );
		}
		return new NyaaParserTokenInline( $this, $line );
	}

	/**
	 * create node
	 * @param $m array
	 */
	public function tokenFactory( $m )
	{
		foreach( $m as $k=>$v )
		{
			if(substr($k,0,3) == "ptn" && !empty($v))
			{
				$class = $this->patterns[$k]['class'];
				return new $class( $this, $v );
			}
		}
	}

	/**
	 * enable module
	 *
	 * @param $class
	 */
	public function enable( $class )
	{
		if( is_array($class) ){
			foreach( $class as $c ) $this->enable($c);
			return false;
		}

		// For less than php 5.2 
		//
		// Original code was
		// {{{ 
		//  $this->addPattern( $class::REGEX, $class);
		// }}}
		//
		eval("\$regex = $class::REGEX;");
		$this->addPattern( $regex, $class);
	}

	/**
	 * add pattern
	 *
	 * @param $regex string regex
	 * @param $class string class name must extends NyaaParserToken
	 */
	public function addPattern( $regex, $class )
	{
		$id = count($this->patterns);
		$this->patterns['ptn'.$id] = array(
			'regex' => $regex,
			'class' => $class
		);
	}

	/**
	 * get all match regex
	 */
	public function getRegex( )
	{
		$regex = array();
		foreach( $this->patterns as $k=>$v )
		{
			// 名前付サブパターンに入れる？
			$regex[] = '(?P<'.$k.'>'.$v['regex'].')';
		}
		$regex = '/(?P<bef>.*?)(?:'.join('|', $regex).')+(?<aft>.*)/smx';
		return $regex;
	}

	/**
	 * do parse file
	 *
	 * @param $file string file path
	 * @return root token
	 */
	public function parseFile( $file )
	{
		$text = file_get_contents( $file );
		return $this->parse( $text );
	}

	/**
	 * do parse
	 *
	 * @param $data text
	 * @return root token
	 */
	public function parse( $data )
	{
		$this->regex = $this->getRegex( );
		$lines       = explode("\n", trim($data));
		$root        = $this->createRoot( );
		$last        = $root;
		while( !empty($lines) )
		{
			$line = array_shift($lines);

			// If capturemode true
			if( $last->isCap( ) ) {
				if( false === $line = $last->doCap( $line ) ) {
					continue;
				}
			}
			if( false !== $line = $this->doParse( $last, $line, $lines ) ){
				$last = $last->add( $this->lineFactory( $line ) );
			}
		}
		return $root;
	}

	/**
	 * this method should be overwrited
	 *
	 * @param $last NyaaParserNode
	 * @param $line string current line
	 */
	public function doParse( &$last, &$line )
	{
		if( empty( $line ) ) return $line;

		while( !empty($line) )
		{
			if( preg_match( $this->regex, $line, $m ) )
			{
				// escape from endless loop
				if( $line == $m['aft'] ) return $line;

				if( !empty($m['bef']) )
					$last = $last->add( $this->lineFactory( $m['bef'] ) );
				$last = $last->add( $this->tokenFactory( $m ) );
				$line = $m['aft'];
				continue;
			}
			break;
		}
		if( empty( $line ) ) return false;
		return $line;
	}

	/**
	 * sub parse for sub text
	 *
	 * @param $parent NyaaParentToken
	 * @param $line string
	 */
	public function subParse( $parent, $text )
	{
		$root = $this->createRoot( );
		if(false !== $line = $this->doParse( $root->last, $text ))
		{
			$root->last = $root->last->add( $this->lineFactory( $line ) );
		}
		foreach( $root->children as $c ) $parent->children[] = $c;
	}
}
?>
