<?php

include('veritas/connectdb.php');
//include('connectdb.php');

session_start();

include('../include/stylephp.inc');

$datetimenow=date("Y-m-d H:i:s");
$datenow=date("Y-m-d");
$datenowshow=date("d M Y");
$tablewidth="950px";
if (isset($_GET['divcode']))
	$divcode = $_GET['divcode'];

if (isset($_GET['vesselcode']))
	$vesselcode = $_GET['vesselcode'];
	
//get vessel details
$qryvesseldetails=mysql_query("SELECT VESSEL,PRINCIPAL,CONTRACTTERM,COUNT(c.VESSELCODE) AS NOCREW 
	FROM vessel v
	LEFT JOIN management m ON v.MANAGEMENTCODE=m.MANAGEMENTCODE
	LEFT JOIN principal p ON m.PRINCIPALCODE=p.PRINCIPALCODE
	LEFT JOIN crewcomplement c ON v.VESSELCODE=c.VESSELCODE
	WHERE v.VESSELCODE='$vesselcode'
	GROUP BY VESSEL,PRINCIPAL") or die(mysql_error());
$rowvesseldetails=mysql_fetch_array($qryvesseldetails);
$vesselname=$rowvesseldetails["VESSEL"];
$contractterm=$rowvesseldetails["CONTRACTTERM"];
$principal=$rowvesseldetails["PRINCIPAL"];
$nocrew=$rowvesseldetails["NOCREW"];

//CREATE COLUMNS
$fixedcolumn="
<tr height=\"15px\" style=\"display:none;\">\n
	<td style=\"width:15px;\">&nbsp;</td>\n		<!--  1  -->
	<td style=\"width:120px;\">&nbsp;</td>\n	<!--  2  -->
	<td style=\"width:30px;\">&nbsp;</td>\n		<!--  3  -->
	<td style=\"width:80px;\">&nbsp;</td>\n		<!--  4  -->
	<td style=\"width:60px;\">&nbsp;</td>\n		<!--  5  -->
	<td style=\"width:80px;\">&nbsp;</td>\n		<!--  6  -->
	<td style=\"width:80px;\">&nbsp;</td>\n		<!--  7  -->
	<td style=\"width:100px;\">&nbsp;</td>\n	<!--  8  -->
	<td style=\"width:60px;\">&nbsp;</td>\n		<!--  9  -->
	<td style=\"width:60px;\">&nbsp;</td>\n		<!--  10  -->
	<td style=\"width:60px;\">&nbsp;</td>\n		<!--  11  -->
	<td style=\"width:80px;\">&nbsp;</td>\n		<!--  12  -->
</tr>
";
$header="
<table cellspacing=\"0\" cellpadding=\"0\" width=\"$tablewidth\" style=\"table-layout:fixed;border-bottom:2px solid black;border-top:2px solid black;\">
$fixedcolumn
	<tr height=\"30px\" style=\"text-align:center;valign:middle;\">\n
		<td style=\"font-size:8pt;font-family:Arial;\">&nbsp;</td>\n
		<td style=\"font-size:8pt;font-family:Arial;text-align:left;\">Name of Seaman</td>\n
		<td style=\"font-size:8pt;font-family:Arial;text-align:left;\">Civil Status</td>\n
		<td style=\"font-size:8pt;font-family:Arial;text-align:left;\">Rank</td>\n
		<td style=\"font-size:8pt;font-family:Arial;text-align:left;\">Date Birth</td>\n
		<td style=\"font-size:8pt;font-family:Arial;text-align:left;\">Seaman Book</td>\n
		<td style=\"font-size:8pt;font-family:Arial;text-align:left;\">Paasport</td>\n
		<td style=\"font-size:8pt;font-family:Arial;text-align:left;\">Last Vsl/ Company</td>\n
		<td style=\"font-size:8pt;font-family:Arial;text-align:left;\">Date OB Promotion</td>\n
		<td style=\"font-size:8pt;font-family:Arial;\">Date Embarked</td>\n
		<td style=\"font-size:8pt;font-family:Arial;\">Date F.C.</td>\n
		<td style=\"font-size:8pt;font-family:Arial;\">Months Onboard</td>\n
	</tr>
</table>";
$mainheader="
<table cellspacing=\"0\" cellpadding=\"0\" width=\"$tablewidth\">
	<tr height=\"20px\">\n
		<td style=\"text-align:center;font-size:16pt;font-weight:bold;\">VERITAS MARITIME CORPORATION</td>\n
	</tr>
	<tr height=\"20px\">\n
		<td style=\"text-align:center;font-size:10pt;font-weight:bold;\">CREW ONBOARD LIST, BY VESSEL</td>\n
	</tr>
	<tr height=\"20px\">\n
		<td style=\"text-align:center;font-size:10pt;\">As of $datenowshow</td>\n 
	</tr>
</table>
<br>
<table>
	<tr height=\"20px\">
		<td>Name of Vessel</td>
		<td>:</td>
		<td>$vesselname</td>
	</tr>
	<tr height=\"20px\">
		<td>Principal</td>
		<td>:</td>
		<td>$principal</td>
	</tr>
	<tr height=\"20px\">
		<td>No. of Crew</td>
		<td>:</td>
		<td>$nocrew</td>
	</tr>
	<tr height=\"20px\">
		<td>Term of Contract</td>
		<td>:</td>
		<td>$contractterm months</td>
	</tr>
</table>
<br>";

	#*******************POST VALUES*************



$qrybyvessel=mysql_query("SELECT cc.APPLICANTNO,CONCAT(FNAME,', ',GNAME,' ',LEFT(MNAME,1)) AS NAME,CIVILSTATUS,RANK,
	BIRTHDATE,DATEEMB,DATEDISEMB,cc.RANKCODE
	FROM crewchange cc
	LEFT JOIN crew c ON cc.APPLICANTNO=c.APPLICANTNO
	LEFT JOIN rank r ON cc.RANKCODE=r.RANKCODE
	WHERE DATEDISEMB>=NOW() AND DEPMNLDATE IS NOT NULL AND cc.VESSELCODE='P68'
	ORDER BY RANKING") or die(mysql_error());

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
	<table cellspacing=\"0\" cellpadding=\"0\" width=\"$tablewidth\" style=\"table-layout:fixed;\">
		$fixedcolumn";
	$cntdata=1;
	while($rowbyvessel=mysql_fetch_array($qrybyvessel))
	{
		$applicantno=$rowbyvessel["APPLICANTNO"];
		$name=$rowbyvessel["NAME"];
		$civilstatus=$rowbyvessel["CIVILSTATUS"];
		$rank=$rowbyvessel["RANK"];
		$rankcode=$rowbyvessel["RANKCODE"];
		$birthdate=$rowbyvessel["BIRTHDATE"];
		$dateemb=$rowbyvessel["DATEEMB"];
		$datedisemb=$rowbyvessel["DATEDISEMB"];
		//get seaman's book
		$qryseamanbook=mysql_query("SELECT DOCNO 
			FROM crewdocstatus 
			WHERE APPLICANTNO=$applicantno AND DOCCODE='F2'") or die(mysql_error());
		$rowseamanbook=mysql_fetch_array($qryseamanbook);
		$seamanbook=$rowseamanbook["DOCNO"];
		//get passport
		$qrypassport=mysql_query("SELECT DOCNO 
			FROM crewdocstatus 
			WHERE APPLICANTNO=$applicantno AND DOCCODE='41'") or die(mysql_error());
		$rowpassport=mysql_fetch_array($qrypassport);
		$passport=$rowpassport["DOCNO"];
		//get last vessel
		$qrylastvessel=mysql_query("SELECT VESSEL 
			FROM crewchange c
			LEFT JOIN vessel v ON c.VESSELCODE=v.VESSELCODE
			WHERE DATEDISEMB<'$datenow' AND c.APPLICANTNO=$applicantno
			ORDER BY DATEDISEMB DESC
			LIMIT 1") or die(mysql_error());
		$rowlastvessel=mysql_fetch_array($qrylastvessel);
		$lastvessel=$rowlastvessel["VESSEL"];
		echo "
		<tr height=\"20px\" style=\"valign:middle;\">\n
			<td style=\"font-size:7pt;font-family:Arial;text-align:left;\">$cntdata.</td>\n
			<td style=\"font-size:7pt;font-family:Arial;text-align:left;\">$name</td>\n
			<td style=\"font-size:7pt;font-family:Arial;text-align:left;\">$civilstatus</td>\n
			<td style=\"font-size:7pt;font-family:Arial;text-align:left;\">$rank</td>\n
			<td style=\"font-size:7pt;font-family:Arial;text-align:left;\">$birthdate</td>\n
			<td style=\"font-size:7pt;font-family:Arial;text-align:left;\">$seamanbook</td>\n
			<td style=\"font-size:7pt;font-family:Arial;text-align:left;\">$passport</td>\n
			<td style=\"font-size:7pt;font-family:Arial;text-align:left;\">$lastvessel</td>\n
			<td style=\"font-size:7pt;font-family:Arial;text-align:left;\">&nbsp;</td>\n
			<td style=\"font-size:7pt;font-family:Arial;text-align:center;\">$dateemb</td>\n
			<td style=\"font-size:7pt;font-family:Arial;text-align:center;\">$datedisemb</td>\n
			<td style=\"font-size:7pt;font-family:Arial;border-bottom:1px solid black;\">&nbsp;</td>\n
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
