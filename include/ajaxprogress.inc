<?php
if(empty($leftmarg))
	$leftmarg="300px";
if(empty($topmarg))
	$topmarg="200px";

echo "
<div id=\"ajaxprogress\" 
	style=\"background:white;z-index:200;position:absolute;left:$leftmarg;top:$topmarg;width:450px;height:250px;
		border:3px solid black;display:none;\">
	<!--
	<img src=\"images/veritas_logo_anim.gif\" width=\"200px\">
	-->
	<iframe src=\"images/veritas_logo_anim.gif\" width=\"450px\" height=\"250px\"></iframe>
	Loading. Please wait.....
</div>
";
?>