<?php
include('veritas/connectdb.php');
include("veritas/include/functions.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');
$basedirid = "idpics/";
$basedirdocs = "docimages/";

$dateformat = "dMY";


$qryrecommend = mysql_query("
SELECT APPLICANTNO,CRW74 AS RECOMMENDATION
FROM recommended r
LEFT JOIN crew c ON c.CREWCODE=r.CRW1
WHERE APPLICANTNO IS NOT NULL
") or die(mysql_error());

$cnt = 1;
$content = "Starting...<br /><br />";

while ($rowrecommend = mysql_fetch_array($qryrecommend))
{
	$applicantno = $rowrecommend["APPLICANTNO"];
	$recommendation = $rowrecommend["RECOMMENDATION"];
	
	$qryupdate = mysql_query("UPDATE crew SET RECOMMENDEDBY='$recommendation' WHERE APPLICANTNO=$applicantno") or die(mysql_error());
	
	$content .= $cnt . ") Updated --> ApplicantNo = $applicantno / Recommended By = $recommendation <br />";
	
	$cnt++;
}


echo "
<html>\n
<head>\n
<title>Update Recommended By</title>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>

<body onload=\"\" style=\"overflow:auto;background-color:White;\">\n

$content


</body>

</html>

";




?>