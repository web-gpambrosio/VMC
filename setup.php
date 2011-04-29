<?php

include('veritas/connectdb.php');

session_start();

if(isset($_SESSION['employeeid']))
	$employeeid=$_SESSION['employeeid'];

	
$qrysetupaccess = mysql_query("SELECT DISTINCT SETUPID
								FROM setupaccess
								WHERE EMPLOYEEID='$employeeid'
								") or die(mysql_error());



echo "
<html>
<head>
<link rel=\"stylesheet\" type=\"text/css\" href=\"veripro.css\">
<script type='text/javascript' src='../veritas/veripro.js'></script>
</head>
<body>
<form name=\"formmain\" method=\"POST\">
	<div style=\"width:100%;height:100%;\">
		<div id=\"setup\">
			<table width=\"90%\" class=\"mainmenu\">
";
			while ($rowsetupaccess=mysql_fetch_array($qrysetupaccess))
			{
				$setupid = $rowsetupaccess["SETUPID"];
				
				$qrysetuphdr = mysql_query("SELECT SETUP,DESCRIPTION 
										FROM setuphdr WHERE SETUPID='$setupid' 
										") or die(mysql_error());
				
				$rowsetuphdr = mysql_fetch_array($qrysetuphdr);
				
				$setup = $rowsetuphdr["SETUP"];
				$description = $rowsetuphdr["DESCRIPTION"];
				
				echo "
				<tr>
					<th align=\"left\" width=\"30%\" style=\"cursor:pointer;color:blue;\"
						onclick=\"openWindow('setupdeploy.php?setupid=$setupid','setupdeploy','0','0');\">
						$setup
					</th>
					<td align=\"right\"><span style=\"color:#FF6600;font-size:0.8em;font-weight:bold;\">$description</span></td>
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

