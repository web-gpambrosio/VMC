<?php
session_start();
include("benjie/include/stylephp.inc");

if(isset($_GET["remval"]))
	$remval=$_GET["remval"];
	
if(isset($_GET["putttl"]))
	$putttl=$_GET["putttl"];
	
if(isset($_GET["applicantno"]))
	$applicantno=$_GET["applicantno"];
	
echo "
<html>
<head>
<script language=\"JavaScript\">
</script>
<title>Document Viewer</title>
<link rel=\"stylesheet\" type=\"text/css\" href=\"veripro.css\">
<script type='text/javascript' src='veripro.js'></script>
<script language=\"JavaScript\"> 
function retval()
{
	window.returnValue=document.documentviewer.remarks.value;
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
function selectcheck(x)
{
	document.getElementById(x).checked='true';
	var getlast=x.substring(7,8);
	document.documentviewer.doctypehidden.value=getlast;
	document.getElementById('listdoc').src='documentviewerlist.php?doctype='+getlast;
	focusend();
}
function showdoc(doccode,docname)
{
	document.getElementById('docname').innerHTML=docname;
	document.getElementById('viewdoc').src='docimages/$applicantno/'+document.documentviewer.doctypehidden.value+'/'+doccode+'.pdf';
	focusend();
}
function focusend()
{
	document.getElementById('placefocus').focus();
	var endtext=document.getElementById('placefocus').value;
	document.getElementById('placefocus').value=endtext;
}
</script>
</head>
<body style=\"overflow:hidden;\" onload=\"focusend();\" 
	onunload=\"retval();\" onkeyup=\"chkescape();\">
<form name=\"documentviewer\" method=\"POST\">

	<div style=\"width:40%;height:600px;overflow:hidden;background:green;float:left;\">
		<span class=\"sectiontitle\">Document List</span>
		<div style=\"width:100%;height:32px;overflow:hidden;background:white;\">
			<center>
			<table class=\"setup\">	
				<tr>
					<th>
						<input checked=\"checked\" type=\"radio\" onclick=\"selectcheck('doctypeD');\" id=\"doctypeD\" name=\"doctype\" value=\"D\"><span style=\"cursor:pointer;\" onclick=\"selectcheck('doctypeD');\"><b style=\"color:black;\">Document </b></span>&nbsp;
						<input type=\"radio\" onclick=\"selectcheck('doctypeL');\" id=\"doctypeL\" name=\"doctype\" value=\"L\"><span style=\"cursor:pointer;\" onclick=\"selectcheck('doctypeL');\"><b style=\"color:black;\">License </b></span>&nbsp;
						<input type=\"radio\" onclick=\"selectcheck('doctypeC');\" id=\"doctypeC\" name=\"doctype\" value=\"C\"><span style=\"cursor:pointer;\" onclick=\"selectcheck('doctypeC');\"><b style=\"color:black;\">Certificate </b></span>&nbsp;
					</th>
				</tr>
			</table>
			</center>
		</div>
		<div style=\"width:100%;height:420px;overflow:hidden;background:yellow;\">
			<iframe id=\"listdoc\" src=\"documentviewerlist.php?doctype=D\" height=\"100%\" width=\"100%\"></iframe>
		</div>
		<div style=\"width:100%;height:130px;overflow:hidden;background:#FFFF9E;\">
			<center>
			<table cellspacing=\"2\" cellpadding=\"1\">
				<tr>
					<td style=\"\"><b>Remarks</b></td>
				</tr>
				<tr>
					<td>
						<textarea name=\"remarks\" scroll=\"none\" id=\"placefocus\"
							onkeydown=\"chkCR();\"
							style=\"font-size:1em;background:inherit;scroll:none;font-weight:Bold;\" 
							cols=\"30\" rows=\"3\">$remval</textarea>
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
	</div>
	<div style=\"width:60%;height:600px;overflow:hidden;background:white;float:left;\">
		<span class=\"sectiontitle\" id=\"docname\">Document View</span>
		<div style=\"width:100%;height:582px;overflow:hidden;background:silver;\">
			<iframe id=\"viewdoc\" src=\"\" height=\"100%\" width=\"100%\"></iframe>
		</div>
	</div>
		
	<input type=\"hidden\" name=\"retvalhidden\" />
	<input type=\"hidden\" name=\"doctypehidden\" value=\"D\" />
</form>
</body>
</html>";
?>
