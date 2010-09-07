<?php
/**
 * コンポーネント
 */

class NyaaFormInputComp extends NyaaFormInput
{
	public $template = ':html';
	public $from = '1980-01-01';
	public $to = '1982-12-01';
	public $parseFormat = '([0-9]+)-([0-9]+)-([0-9]+)';

	public function __construct( )
	{
		$this->to = date('Y-m-d');
	}

	function setChild( $child )
	{
		$this->child = $child;
	}



	function toHtml( )
	{
		$html="<table>";
		foreach($this->child as $k=>$v)
		{
			$e = $this->factory(array_merge(array('name'=>$k), $v));
			$html.= '<tr><th align="left">'.$e->toLabel()."</th></tr><tr><td>".$e->toHtml()."</td></tr>";
		}
			$html.= '</table>';

		return $html;

	}
}
?>
