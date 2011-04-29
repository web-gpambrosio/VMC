<?php

include("veritas/connectdb.php");
session_start();


if(isset($_SESSION['employeeid']))
	$employeeid=$_SESSION['employeeid'];

if (isset($_POST['setupfile']))
	$setupfile = $_POST['setupfile'];
	
if (isset($_GET['setupid']))
	$setupid = $_GET['setupid'];
else 
	$setupid = $_POST['setupid'];
	

$qrysetupdtl = mysql_query("SELECT sd.SUBSETUP,sd.PHPFILE
								FROM setupaccess sa
								LEFT JOIN setupdtl sd ON sd.SETUPID=sa.SETUPID AND sd.SUBSETUPID=sa.SUBSETUPID
								WHERE sa.EMPLOYEEID='$employeeid' AND sa.SETUPID=$setupid
								ORDER BY SUBPOSITION
								") or die(mysql_error());
	
$qrygetsetuphdr = mysql_query("SELECT SETUP FROM setuphdr WHERE SETUPID=$setupid") or die(mysql_error());
$rowgetsetuphdr = mysql_fetch_array($qrygetsetuphdr);
$setuptitle = $rowgetsetuphdr["SETUP"];
	
//$qrysetupdtl = mysql_query("SELECT * FROM setupdtl WHERE SETUPID=$setupid ORDER BY SUBPOSITION") or die(mysql_error());
	
	
echo "
<html>
<head>
<title>
VERIPRO - Setup
</title>
<link rel=\"stylesheet\" type=\"text/css\" href=\"veripro.css\">
<script language=\"javascript\" src=\"veripro.js\"></script>

</head>

<body style=\"overflow:hidden;\">
<form name=\"setupform\" method=\"POST\">
	
	<center>
	<div style=\"width:100%;height:700px;padding:2px;border:1px solid black;overflow:auto;background:green;\">
	
		<span class=\"wintitle\">$setuptitle - SETUP</span>
			
			<div style=\"width:20%;height:600px;float:left;padding:25px 0 0 10px;overflow:hidden;\">
				
				<table class=\"listcol\" style=\"width:100%;\" >
";
				while ($rowsetupdtl = mysql_fetch_array($qrysetupdtl))
				{
					$subsetup = $rowsetupdtl["SUBSETUP"];
					$subphpfile = "setup/" . $rowsetupdtl["PHPFILE"];
					
					echo "
					<tr>
						<td><a href=\"#\" onclick=\"setupfile.value='$subphpfile';document.getElementById('content').src='$subphpfile';\">$subsetup</a></td>
					</tr>
					";
				}
				
echo "
				</table>
				<br />
			</div>

			<div style=\"width:80%;height:650px;float:left;padding:25px 0 0 10px;overflow:hidden;\">
			<iframe marginwidth=0 marginheight=0 id=\"content\" frameborder=\"0\" name=\"content\" src=\"$setupfile\" scrolling=\"yes\" 
				style=\"width:100%;height:100%;float:left;\">
			</iframe>	
			</div>

	</div>
	</center>
	
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"setupfile\" />
	
</form>
	
</body>

</html>
";
?>