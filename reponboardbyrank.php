<?php

include('veritas/connectdb.php');
//include('connectdb.php');

session_start();

include('../include/stylephp.inc');

$datetimenow=date("Y-m-d H:i:s");
$datenow=date("Y-m-d");
$datenowshow=date("d M Y");

if (isset($_GET['divcode']))
	$divcode = $_GET['divcode'];

if (isset($_GET['rankcode']))
	$rankcode = $_GET['rankcode'];

if($rankcode!="All")
{
	$addwhererank="AND cc.RANKCODE='$rankcode'";
	$qrygetrank=mysql_query("SELECT RANK FROM rank WHERE RANKCODE='$rankcode'") or die(mysql_error());
	$rowgetrank=mysql_fetch_array($qrygetrank);
	$rank=$rowgetrank["RANK"];
}
else 
	$rank=$rankcode;
	
//CREATE COLUMNS
$fixedcolumn="
<tr height=\"18px\" style=\"display:none;\">\n
	<td style=\"width:20px;\">&nbsp;</td>\n		<!--  1  -->
	<td style=\"width:200px;\">&nbsp;</td>\n	<!--  2  -->
	<td style=\"width:150px;\">&nbsp;</td>\n	<!--  3  -->
	<td style=\"width:100px;\">&nbsp;</td>\n	<!--  4  -->
	<td style=\"width:100px;\">&nbsp;</td>\n	<!--  5  -->
	<td style=\"width:80px;\">&nbsp;</td>\n	<!--  6  -->
</tr>
";
$header="
<table cellspacing=\"0\" cellpadding=\"0\" width=\"700px\" style=\"table-layout:fixed;border-bottom:2px solid black;border-top:2px solid black;\">
$fixedcolumn
	<tr height=\"25px\" style=\"text-align:center;valign:middle;\">\n
		<td style=\"font-size:10pt;font-family:Arial;\">&nbsp;</td>\n
		<td style=\"font-size:10pt;font-family:Arial;text-align:left;\">Name of Seaman</td>\n
		<td style=\"font-size:10pt;font-family:Arial;text-align:left;\">Name of Vessel</td>\n
		<td style=\"font-size:10pt;font-family:Arial;\">Date Embarked</td>\n
		<td style=\"font-size:10pt;font-family:Arial;\">Date Expire</td>\n
		<td style=\"font-size:10pt;font-family:Arial;\">Remarks</td>\n
	</tr>
</table>";
$mainheader="
<table cellspacing=\"0\" cellpadding=\"0\" width=\"700px\">
	<tr height=\"20px\">\n
		<td style=\"text-align:center;font-size:16pt;font-weight:bold;\">VERITAS MARITIME CORPORATION</td>\n
	</tr>
	<tr height=\"20px\">\n
		<td style=\"text-align:center;font-size:10pt;font-weight:bold;\">CREW ONBOARD LIST, BY RANK $rank</td>\n
	</tr>
	<tr height=\"20px\">\n
		<td style=\"text-align:center;font-size:10pt;\">Division $divcode</td>\n 
	</tr>
	<tr height=\"20px\">\n
		<td style=\"text-align:center;font-size:10pt;\">As of $datenowshow</td>\n 
	</tr>
</table>";
	#*******************POST VALUES*************



$qrybyrank=mysql_query("SELECT CONCAT(FNAME,', ',GNAME,' ',LEFT(MNAME,1),'.') AS NAME,VESSEL,DATEEMB,DATEDISEMB
	FROM crewchange cc
	LEFT JOIN crew c ON cc.APPLICANTNO=c.APPLICANTNO
	LEFT JOIN vessel v ON cc.VESSELCODE=v.VESSELCODE
	WHERE DATEDISEMB>=NOW() AND DEPMNLDATE IS NOT NULL $addwhererank AND DIVCODE=$divcode
	ORDER BY DATEDISEMB;") or die(mysql_error());

//print_r($summarray["1991SD"][1]);
echo	"<html>\n
<head>\n
<link rel=\"StyleSheet\" type=\"text/css\" href=\"../veripro.css\" />
<style>
#noprint
{
	display: none;
}
</style>
<script>

</script>\n

</head>\n

<body onload=\"\" style=\"\">\n

<form name=\"scholarfasttrack\" id=\"scholarfasttrack\" method=\"POST\">\n";
	echo $mainheader;
	echo $header;
	echo "
	<table cellspacing=\"0\" cellpadding=\"0\" width=\"700px\" style=\"table-layout:fixed;\">
		$fixedcolumn";
	$cntdata=1;
	while($rowbyrank=mysql_fetch_array($qrybyrank))
	{
		$name=$rowbyrank["NAME"];
		$vessel=$rowbyrank["VESSEL"];
		$dateemb=$rowbyrank["DATEEMB"];
		$datedisemb=$rowbyrank["DATEDISEMB"];
		echo "
		<tr height=\"20px\" style=\"valign:middle;\">\n
			<td style=\"font-size:9pt;font-family:Arial;text-align:left;\">$cntdata.</td>\n
			<td style=\"font-size:9pt;font-family:Arial;text-align:left;\">$name</td>\n
			<td style=\"font-size:9pt;font-family:Arial;text-align:left;\">$vessel</td>\n
			<td style=\"font-size:9pt;font-family:Arial;text-align:center;\">$dateemb</td>\n
			<td style=\"font-size:9pt;font-family:Arial;text-align:center;\">$datedisemb</td>\n
			<td style=\"font-size:9pt;font-family:Arial;border-bottom:1px solid black;\">&nbsp;</td>\n
		</tr>";
		$cntdata++;
	}
echo "
	</table>
</form>";
include('veritas/include/printclose.inc');
echo "
</body>\n
</html>\n";

?>
