<?php
session_start();
include("benjie/include/stylephp.inc");

if(isset($_GET["remval"]))
	$remval=$_GET["remval"];
	
if(isset($_GET["putttl"]))
	$putttl=$_GET["putttl"];
	
echo "
<html>
<head>
<script language=\"JavaScript\">
</script>
<title>Departing Seaman Remarks</title>
<link rel=\"stylesheet\" type=\"text/css\" href=\"veripro.css\">
<script type='text/javascript' src='veripro.js'></script>
<script language=\"JavaScript\"> 
function retval()
{
	window.returnValue=document.departstatusmodal.remarks.value;
	window.close();
}
function chkescape()
{
	if(event.keyCode==27)
	{
		window.returnValue='';
		window.close();
	}
}
function focusend()
{
	document.departstatusmodal.remarks.focus();
	var endtext=document.departstatusmodal.remarks.value;
	document.departstatusmodal.remarks.value=endtext;
}
</script>
</head>
<body style=\"overflow:hidden;\" onload=\"focusend();\"
	onunload=\"retval();\" onkeyup=\"chkescape();\">
<form name=\"departstatusmodal\" method=\"POST\">
	<span class=\"sectiontitle\">$putttl</span>

	<div style=\"width:100%;height:190px;overflow:hidden;background:White;float:left;\">
		<br>
		<center>
		<table cellspacing=\"2\" cellpadding=\"1\">
			<tr>
				<td style=\"\"><b>Remarks</b></td>
			</tr>
			<tr>
				<td>
					<textarea name=\"remarks\" scroll=\"none\"
						onkeydown=\"chkCR();\"
						style=\"font-size:1.2em;background:inherit;scroll:none;font-weight:Bold;\" 
						cols=\"35\" rows=\"4\">$remval</textarea>
				</td>
			</tr>
			<tr>
				<td colspan=\"2\" align=\"center\">
					<input type=\"button\" style=\"width:70px;height:30px;\" onfocus=\"this.click();\"
							onclick=\"retval();\" value=\"DONE\" name=\"btndone\">
				</td>
			</tr>
		</table>
		</center>
	</div>
	<input type=\"hidden\" name=\"retvalhidden\" />
</form>
</body>
</html>";
?>
