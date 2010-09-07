<?php
/**
 * Visitor Dump
 *
 * @author Hajime MATSUMOTO
 */

/**
 * dumper
 */
class NyaaParserTokenDumpVisitor extends NyaaParserTokenVisitor
{
	function visit( $n, $cnt = 0 )
	{
		// if it's not object
		if( !is_object( $n ) )
			return false;
		$ret = str_repeat('_',$cnt).$n."\n";
		foreach( $n->children as $c ) 
			$ret.= $this->visit($c, $cnt+1);
		return $ret;
	}
}
?>
