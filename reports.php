<?php

include('veritas/connectdb.php');

session_start();

if(isset($_SESSION['employeeid']))
	$employeeid=$_SESSION['employeeid'];

	
$qryreportaccess = mysql_query("SELECT DISTINCT rh.REPORTID,rh.REPORT,rh.DESCRIPTION
								FROM reportaccess ra
								LEFT JOIN reportshdr rh ON rh.REPORTID=ra.REPORTID
								WHERE ra.EMPLOYEEID='$employeeid'
								ORDER BY rh.POSITION
								") or die(mysql_error());

//$qryreporthdr = mysql_query("SELECT * FROM reportshdr ORDER BY POSITION") or die(mysql_error());

echo "
<html>
<head>
<link rel=\"stylesheet\" type=\"text/css\" href=\"veripro.css\">
<script type='text/javascript' src='../veritas/veripro.js'></script>
</head>
<body>
<form name=\"formmain\" method=\"POST\">
	<div style=\"width:100%;height:100%;\">
		<div id=\"report\">
			<table width=\"90%\" class=\"mainmenu\">
";
			while ($rowreportaccess=mysql_fetch_array($qryreportaccess))
			{
				$reportid = $rowreportaccess["REPORTID"];
				$report = $rowreportaccess["REPORT"];
				$description = $rowreportaccess["DESCRIPTION"];
				
				echo "
				<tr>
					<th align=\"left\" width=\"30%\" style=\"cursor:pointer;color:blue;\"
						onclick=\"openWindow('reportdeploy.php?reportid=$reportid','reportdeploy','0','0');\">
						$report
					</th>
					<td align=\"right\">$description</td>
				</tr>
				<tr>
					<td colspan=\"2\" style=\"border-bottom:2px solid silver;\">&nbsp;</td>
				</tr>
				";
			}
echo "
			</table>
		</div>
	</div>
</form>
</body>
</html>
	
";

?>

