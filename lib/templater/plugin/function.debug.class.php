<?php
class NyaaTemplaterFunctionDebug extends NyaaTemplaterFunction
{
	function execute( $name, $option, $templater )
	{
		$status = $templater->get('status');

		echo "<h2>Template Infomation</h2>";
		echo "<p>";
		foreach(array('TYPE','PATH','CACHED') as $k )
		{
			echo "$k : ".$status[$k]."<br />";
		}
		echo "</p>";

		echo "<h2>Plugin Infomation</h2>";
		echo "<table>";
		echo "<tr><th>Name</th><th>Binded ClassName</th></tr>";
		foreach($templater->plugin as $k=>$v)
		{
			echo '<tr><th align="left" colspan="2" style="padding-top:10px;font-size:120%">'.$k.'</th></tr>';
			foreach($v as $kk=>$vv)
			{
				echo "<tr><th align=\"left\">$kk</th><td>".get_class($vv)."</td></tr>";
			}
		}
		echo "</table>";

		echo "<br />";
		echo "<h2>CompiledCode</h2>";
	
		highlight_string( $status['code'] );
	}
}
?>
