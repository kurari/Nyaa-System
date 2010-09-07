<?php
/**
 * Nyaa Projece
 * ----
 *
 * @author Hajime MATSUMOTO
 */
require_once 'parser/decorater.class.php';
require_once 'wiki/token/func.class.php';
require_once 'wiki/token/var.class.php';
require_once 'wiki/token/link.class.php';
require_once 'wiki/token/headering.class.php';
require_once 'wiki/token/tags.class.php';
require_once 'wiki/token/quote.class.php';
require_once 'wiki/token/list.class.php';

/**
 * wiki decorater
 */
class NyaaWikiParserDecorater extends NyaaParserDecorater 
{
	static public function decorate( $Parser )
	{
		$Parser->addPattern( NyaaWikiFuncTK::REGEXP, 'NyaaWikiFuncTK');
		$Parser->addPattern( NyaaWikiVarTK::REGEXP, 'NyaaWikiVarTK');
		$Parser->addPattern( NyaaWikiLinkTK::REGEXP, 'NyaaWikiLinkTK');
		$Parser->addPattern( NyaaWikiHrTK::REGEXP, 'NyaaWikiHrTK');
		$Parser->addPattern( NyaaWikiHeaderingTK::REGEXP, 'NyaaWikiHeaderingTK');
		$Parser->addPattern( NyaaWikiQuoteTK::REGEXP, 'NyaaWikiQuoteTK');
		$Parser->addPattern( NyaaWikiListTK::REGEXP, 'NyaaWikiListTK');
		return $Parser;
	}
}
?>
