<?php
session_start();
include('veritas/connectdb.php');
include("veritas/include/stylephp.inc");

if(isset($_GET["doctype"]))
	$doctype=$_GET["doctype"];

if($doctype=="D")
	$header="Document";
if($doctype=="L")
	$header="License";
if($doctype=="C")
	$header="Certificate";

$qrylist=mysql_query("SELECT DOCCODE,DOCUMENT FROM crewdocuments
	WHERE TYPE='$doctype' AND STATUS=1") or die(mysql_error());
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
</script>
</head>
<body style=\"overflow:hidden;\">
<form name=\"departstatusmodal\" method=\"POST\">

	<div style=\"width:100%;height:420px;overflow:hidden;background:White;\">
		<table id=\"listheader\" cellspacing=\"1\" cellpadding=\"0\" width=\"100%\">
			<tr style=\"background:black;color:white;\">
				<th>$header</th>
				<th>---</th>
			</tr>
		</table>
		<div style=\"width:100%;height:400px;overflow:auto;background:White;\">
			<table id=\"listdetails\" cellspacing=\"1\" cellpadding=\"1\" style=\"width:100%;font-size:0.8em;\">";
			
			$classtype = "odd";
			$cntdata=0;
			while($rowlist=mysql_fetch_array($qrylist))
			{
				$doccode=$rowlist["DOCCODE"];
				$document=$rowlist["DOCUMENT"];
				//docimages/100989/D/P7.pdf
				echo "
				<tr $mouseovereffect class=\"$classtype\">
					<td>&nbsp;$document</td>
					<td align=\"center\" style=\"color:red;font-weight:bold;cursor:pointer;font-size:1.2em;\"
						onclick=\"parent.showdoc('$doccode','$document');\">
						<nobr>--></nobr>
					</td>
				</tr>";
				$cntdata++;
				if($classtype=="even") { $classtype="odd";} else { $classtype="even"; }
			}
			echo "
				
			</table>
			<script>setWidth('listheader','listdetails')</script>";
		echo "
		</div>
	</div>
	<input type=\"hidden\" name=\"retvalhidden\" />
</form>
</body>
</html>";
?>
