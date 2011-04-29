<?php

// include("veritas/connectdb.php");
include("veritas/include/functions.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');
$datenow = date('d F Y');
$basedir = "idpics/";
$basedirdocs = "docimages/";

if(isset($_SESSION["employeeid"]))
	$employeeid=$_SESSION["employeeid"];

//if (isset($_POST["actiontxt"]))
//	$actiontxt = $_POST["actiontxt"];
	
if (isset($_GET["applicantno"]))
	$applicantno = $_GET["applicantno"];
else 
	$applicantno = $_POST["applicantno"];
	
if (isset($_GET["directprint"]))
	$directprint = $_GET["directprint"];

include("veritas/connectdb.php");
include("include/datasheet.inc");

$dateformat = "dMY";
$title = "Experiences Outside Veritas";

$qryexperience_out = mysql_query("SELECT * FROM
								(
									SELECT '2' AS POS,NULL AS CCID,IF (ce.MANNINGCODE = '',LEFT(ce.MANNINGOTHERS,10),LEFT(m.MANNING,10)) AS COMPANY,
									NULL,LEFT(ce.VESSEL,10) AS VESSEL,ce.VESSEL AS VESSELNAME,NULL AS VESSELCODE,r.ALIAS1 AS RANKALIAS,ce.GROSSTON,ce.ENGINETYPE AS ENGINE,
									ce.VESSELTYPECODE,ce.TRADEROUTECODE,NULL AS ENGINEPOWER,DATEEMB,DATEDISEMB,ce.DISEMBREASONCODE
									FROM crewexperience ce
									LEFT JOIN rank r ON r.RANKCODE=ce.RANKCODE
									LEFT JOIN manning m ON m.MANNINGCODE=ce.MANNINGCODE
									LEFT JOIN disembarkreason dr ON dr.DISEMBREASONCODE=ce.DISEMBREASONCODE
									WHERE ce.APPLICANTNO=$applicantno
								) x
								ORDER BY x.DATEDISEMB DESC
							") or die(mysql_error());

echo "
<html>\n
<head>\n
<title>
Printing - Crew Experiences Outside Veritas
</title>

<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>
<script language=\"javascript\" src=\"popcalendar.js\"></script>	
<style type='text/css'>
@media print 
{
	#PAScreenOut { display: none; }
}
</style>

</head>
<body style=\"overflow:auto;\">

<div style=\"width:755px;background-color:white;margin:10 10 10 10;\">

";

include("repheader.php");
	
if (empty($directprint))
	echo "<input type=\"button\" value=\"Print\" id=\"PAScreenOut\" onclick=\"window.print();\">";
	
echo "
	<table style=\"width:100%;font-size:1.2em;\">
		<tr><td>&nbsp;</td></tr>
	";
	
	$cnt = 1;
	$withentry = 0;
	
	
	while ($rowexperience_out = mysql_fetch_array($qryexperience_out))
	{
		$pos = $rowexperience_out["POS"];
		
		if ($pos == 2)
		{
			$withentry = 1;
			
			$ccid = $rowexperience_out["CCID"];
			$vesselname = $rowexperience_out["VESSELNAME"];
			$rankalias = $rowexperience_out["RANKALIAS"];
			
			if (!empty($rowexperience_out["DATEEMB"]))
				$dateemb = date($dateformat,strtotime($rowexperience_out["DATEEMB"]));
			else 
				$dateemb = "";
			
			if (!empty($rowexperience_out["DATEDISEMB"]))
				$datedisemb = date($dateformat,strtotime($rowexperience_out["DATEDISEMB"]));
			else 
				$datedisemb = "";
				
			$months = floor((strtotime($rowexperience_out["DATEDISEMB"]) - strtotime($rowexperience_out["DATEEMB"])) / 2592000);
				
			$grosston = $rowexperience_out["GROSSTON"];
			$engine = $rowexperience_out["ENGINE"];
			$enginepower = $rowexperience_out["ENGINEPOWER"];
			$vesseltypecode = $rowexperience_out["VESSELTYPECODE"];
			$vesseltype = $rowexperience_out["VESSELTYPE"];
			$traderoutecode = $rowexperience_out["TRADEROUTECODE"];
			$traderoute = $rowexperience_out["TRADEROUTE"];
			$disembreason = $rowexperience_out["DISEMBREASON"];
			$manning = $rowexperience_out["MANNING"];
			
			echo "
			<tr>
				<td style=\"border-bottom:1px solid Gray;\">
					<table class=\"listrow\" style=\"width:49%;float:left;\">
						<tr>
							<th width=\"5%\">$cnt.</th>
							<th width=\"40%\">Rank</th>
							<th width=\"5%\">:</th>
							<td width=\"50%\" style=\"font-size:1.2em;font-weight:Bold;\">$rankalias</td>
						</tr>
						<tr>
							<th>&nbsp;</th>
							<th>Embark</th>
							<th>:</th>
							<th>$dateemb</th>
						</tr>
						<tr>
							<th>&nbsp;</th>
							<th>Disembark</th>
							<th>:</th>
							<th>$datedisemb</th>
						</tr>
						<tr>
							<th>&nbsp;</th>
							<th>No. of Months</th>
							<th>:</th>
							<th>$months</th>
						</tr>
						<tr>
							<th>&nbsp;</th>
							<th>Remarks</th>
							<th>:</th>
							<th>$disembreason</th>
						</tr>
					
					</table>
					
					<table class=\"listrow\" style=\"width:50%;float:right;\">
						<tr>
							<th width=\"35%\">Vessel</th>
							<th width=\"5%\">:</th>
							<td width=\"60%\" style=\"font-size:1.2em;font-weight:Bold;\">$vesselname</td>
						</tr>
						<tr>
							<th>Type</th>
							<th>:</th>
							<th>$vesseltype</th>
						</tr>
						<tr>
							<th>Trade Route</th>
							<th>:</th>
							<th>$traderoute</th>
						</tr>
						<tr>
							<th>Engine</th>
							<th>:</th>
							<th>$engine</th>
						</tr>
<!--
						<tr>
							<th>Engine Power</th>
							<th>:</th>
							<th>$enginepower</th>
						</tr>
-->
						<tr>
							<th>Manning</th>
							<th>:</th>
							<th>$manning</th>
						</tr>
					</table>
				
				</td>			
			</tr>
			";
			
		}
		
		$cnt++;
	}
	
	if ($withentry == 0)
		echo "
			<br /><br />
			<span style=\"font-size:1.2em;font-weight:Bold;\">[NO EXPERIENCE WITH OTHER COMPANIES]</span>
		";

echo "	
	</table>

";



echo "
</div>
";
// include('include/printclose.inc');
echo "
</body>

";

?>