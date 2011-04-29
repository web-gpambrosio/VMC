<?php

include("veritas/connectdb.php");

session_start();

if(isset($_SESSION['employeeid']))
	$employeeid=$_SESSION['employeeid'];

if (isset($_POST['reportfile']))
	$reportfile = $_POST['reportfile'];
	
if (isset($_GET['reportid']))
	$reportid = $_GET['reportid'];
else 
	$reportid = $_POST['reportid'];
	

$qryreportdtl = mysql_query("SELECT rd.SUBREPORT,rd.PHPFILE,rd.MAXIMIZE
								FROM reportaccess ra
								LEFT JOIN reportsdtl rd ON rd.REPORTID=ra.REPORTID AND rd.SUBREPORTID=ra.SUBREPORTID
								WHERE ra.EMPLOYEEID='$employeeid' AND ra.REPORTID=$reportid
								ORDER BY rd.SUBPOSITION
								") or die(mysql_error());
	
	
$qrygetreporthdr = mysql_query("SELECT REPORT FROM reportshdr WHERE REPORTID=$reportid") or die(mysql_error());
$rowgetreporthdr = mysql_fetch_array($qrygetreporthdr);
$reporttitle = $rowgetreporthdr["REPORT"];
	
//$qryreportdtl = mysql_query("SELECT * FROM reportsdtl WHERE REPORTID=$reportid ORDER BY SUBPOSITION") or die(mysql_error());
	
	
echo "
<html>
<head>
<title>
VERIPRO - Reports
</title>
<link rel=\"stylesheet\" type=\"text/css\" href=\"veripro.css\">
<script language=\"javascript\" src=\"veripro.js\"></script>

</head>

<body>
<form name=\"reportform\" method=\"POST\">
	
	<center>
	<div style=\"width:100%;height:620px;padding:2px;border:1px solid black;\">
	
		<span class=\"wintitle\">$reporttitle - REPORTS</span>
			
			<div style=\"width:20%;height:600px;float:left;overflow:auto;background-color:Silver;
						border-left:1px solid Black;padding-left:3px;\">
				
				<table class=\"listcol\" style=\"width:100%;\" >
";
				$cnt = 0;
				while ($rowreportdtl = mysql_fetch_array($qryreportdtl))
				{
					$subreport = $rowreportdtl["SUBREPORT"];
					$subphpfile = $rowreportdtl["PHPFILE"];
					$maximize = $rowreportdtl["MAXIMIZE"];
					
					$cnt += 1;
					
					if ($maximize == 0)
					{
						echo "
						<tr>
							<td><a href=\"#\" onclick=\"document.getElementById('content').src='$subphpfile';\">$subreport</a></td>
						</tr>
						";
					}
					else
					{
						echo "
						<tr>
							<td><a href=\"#\" onclick=\"openWindow('$subphpfile', 'repmax$cnt' ,0, 0);\">$subreport</a></td>
						</tr>
						";
					}
				
				}
				
echo "
				</table>
			</div>


	<iframe marginwidth=0 marginheight=0 id=\"content\" frameborder=\"0\" name=\"content\" src=\"\" scrolling=\"yes\" 
		style=\"width:80%;height:600px;float:right;\">
	</iframe>	

	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"reportfile\" />
	
</form>
	
</body>

</html>
";
?>