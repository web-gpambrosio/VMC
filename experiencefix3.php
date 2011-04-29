<?php
include('veritas/connectdb.php');
include("veritas/include/stylephp.inc");
include("veritas/include/functions.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');
$basedirid = "idpics/";
$basedirdocs = "docimages/";

$dateformat = "dMY";


//$qryexperience = mysql_query("
//SELECT cc.APPLICANTNO,x.CRW1,x.XPV5 AS XVESSELCODE,cc.VESSELCODE,x.XPV6 AS XRANKCODE,cc.RANKCODE,x.XPV1 AS XDEPMNLDATE,cc.DEPMNLDATE,
//x.XPV2 AS XDATEEMB,cc.DATEEMB,DATEDIFF(x.XPV2,cc.DATEEMB) AS DATEEMBDIFF,x.XPV3 AS XDATEDISEMB,IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,cc.DATECHANGEDISEMB,
//DATEDIFF(x.XPV3,IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB)) AS DISEMBDIFF,x.XPV4 AS XARRMNLDATE,cc.ARRMNLDATE,x.XPV12 AS XDISEMBREASON,cc.DISEMBREASONCODE
//FROM crewchange cc
//INNER JOIN (
//	SELECT c.APPLICANTNO,CRW1,XPV1,XPV2,XPV3,XPV4,XPV5,XPV6,XPV12
//	FROM m_08xpv m
//	LEFT JOIN crew c ON c.CREWCODE=m.CRW1
//	WHERE c.APPLICANTNO IS NOT NULL
//) x ON x.APPLICANTNO=cc.APPLICANTNO AND XPV5=VESSELCODE AND x.XPV2 = cc.DATEEMB AND x.XPV6=cc.RANKCODE
//ORDER BY cc.APPLICANTNO,DATEEMB
//") or die(mysql_error());

//$qryexperience = mysql_query("
//SELECT cc.CCID,cc.APPLICANTNO,x.CRW1,x.XPV5 AS XVESSELCODE,cc.VESSELCODE,x.XPV6 AS XRANKCODE,cc.RANKCODE,x.XPV1 AS XDEPMNLDATE,cc.DEPMNLDATE,
//x.XPV2 AS XDATEEMB,cc.DATEEMB,DATEDIFF(x.XPV2,cc.DATEEMB) AS DATEEMBDIFF,x.XPV3 AS XDATEDISEMB,IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,cc.DATECHANGEDISEMB,
//DATEDIFF(x.XPV3,IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB)) AS DISEMBDIFF,x.XPV4 AS XARRMNLDATE,cc.ARRMNLDATE,x.XPV12 AS XDISEMBREASON,cc.DISEMBREASONCODE
//FROM crewchange cc
//INNER JOIN (
//	SELECT c.APPLICANTNO,CRW1,XPV1,XPV2,XPV3,XPV4,XPV5,XPV6,XPV12
//	FROM m_08xpv m
//	LEFT JOIN crew c ON c.CREWCODE=m.CRW1
//	WHERE c.APPLICANTNO IS NOT NULL
//) x ON x.APPLICANTNO=cc.APPLICANTNO AND XPV5=VESSELCODE AND x.XPV2 = cc.DATEEMB AND x.XPV6=cc.RANKCODE
//WHERE DATEEMB < '2008-01-01' AND DATEDIFF(x.XPV3,IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB)) <> 0
//ORDER BY cc.APPLICANTNO,DATEEMB
//") or die(mysql_error());  //UPDATING OF DISEMBARK DATE

$qryexperience = mysql_query("
SELECT cc.CCID,cc.APPLICANTNO,x.CRW1,x.XPV5 AS XVESSELCODE,cc.VESSELCODE,x.XPV6 AS XRANKCODE,cc.RANKCODE,x.XPV1 AS XDEPMNLDATE,cc.DEPMNLDATE,
x.XPV2 AS XDATEEMB,cc.DATEEMB,DATEDIFF(x.XPV2,cc.DATEEMB) AS DATEEMBDIFF,x.XPV3 AS XDATEDISEMB,IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,cc.DATECHANGEDISEMB,
DATEDIFF(x.XPV3,IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB)) AS DISEMBDIFF,x.XPV4 AS XARRMNLDATE,cc.ARRMNLDATE,x.XPV12 AS XDISEMBREASON,cc.DISEMBREASONCODE
FROM crewchange cc
INNER JOIN (
	SELECT c.APPLICANTNO,CRW1,XPV1,XPV2,XPV3,XPV4,XPV5,XPV6,XPV12
	FROM m_08xpv m
	LEFT JOIN crew c ON c.CREWCODE=m.CRW1
	WHERE c.APPLICANTNO IS NOT NULL
) x ON x.APPLICANTNO=cc.APPLICANTNO AND XPV5=VESSELCODE AND x.XPV6=cc.RANKCODE AND x.XPV3 = DATEDISEMB
WHERE DATEEMB < '2008-01-01' AND DATEDIFF(x.XPV2,DATEEMB) <> 0
ORDER BY cc.APPLICANTNO,DATEEMB
") or die(mysql_error());  //UPDATING OF EMBARK DATE


$cnt = 1;
$cnt2 = 1;

while ($rowexperience = mysql_fetch_array($qryexperience))
{
	$ccid = $rowexperience["CCID"];
	$applicantno = $rowexperience["APPLICANTNO"];
	$xvesselcode = $rowexperience["XVESSELCODE"];
	$vesselcode = $rowexperience["VESSELCODE"];
	$xrankcode = $rowexperience["XRANKCODE"];
	$rankcode = $rowexperience["RANKCODE"];
	
	if (!empty($rowexperience["XDEPMNLDATE"]))
		$xdepmnldate = "'" . date("Y-m-d",strtotime($rowexperience["XDEPMNLDATE"])) . "'";
	else 
		$xdepmnldate = "NULL";
	
	if (!empty($rowexperience["DEPMNLDATE"]))
		$depmnldate = "'" . date("Y-m-d",strtotime($rowexperience["DEPMNLDATE"])) . "'";
	else 
		$depmnldate = "NULL";
		
	if (!empty($rowexperience["XDATEEMB"]))
		$xdateemb = "'" . date("Y-m-d",strtotime($rowexperience["XDATEEMB"])) . "'";
	else 
		$xdateemb = "NULL";
	
	if (!empty($rowexperience["DATEEMB"]))
		$dateemb = "'" . date("Y-m-d",strtotime($rowexperience["DATEEMB"])) . "'";
	else 
		$dateemb = "NULL";
	
	$dateembdiff = $rowexperience["DATEEMBDIFF"];
	
	if (!empty($rowexperience["XDATEDISEMB"]))
		$xdatedisemb = "'" . date("Y-m-d",strtotime($rowexperience["XDATEDISEMB"])) . "'";
	else 
		$xdatedisemb = "NULL";
		
		
	if (!empty($rowexperience["DATEDISEMB"]))
		$datedisemb = "'" . $rowexperience["DATEDISEMB"] . "'";
	else 
		$datedisemb = "NULL";
		
	$datechangedisemb = $rowexperience["DATECHANGEDISEMB"];
	$disembdiff = $rowexperience["DISEMBDIFF"];
	
	if (!empty($rowexperience["XARRMNLDATE"]))
		$xarrmnldate = "'" . date("Y-m-d",strtotime($rowexperience["XARRMNLDATE"])) . "'";
	else 
		$xarrmnldate = "NULL";
		
	if (!empty($rowexperience["ARRMNLDATE"]))
		$arrmnldate = "'" . date("Y-m-d",strtotime($rowexperience["ARRMNLDATE"])) . "'";
	else 
		$arrmnldate = "NULL";
		
	$xdisembreason = $rowexperience["XDISEMBREASON"];
	$disembreasoncode = $rowexperience["DISEMBREASONCODE"];
	
//	if ($xdisembreason == 'FC')
//	{
//		if ($xarrmnldate != "NULL")
//		{
//			$qryupdate = mysql_query("UPDATE crewchange SET DATECHANGEDISEMB=$xdatedisemb,ARRMNLDATE=$xarrmnldate,DISEMBREASONCODE='FC' WHERE CCID=$ccid") or die(mysql_error());
//			echo "$cnt) CCID: $ccid / Applicant No. $applicantno -- Updated from $datedisemb ----> $xdatedisemb <br />";
//		}
//		else 
//		{
//			$qryupdate = mysql_query("UPDATE crewchange SET DATECHANGEDISEMB=$xdatedisemb,DISEMBREASONCODE='FC' WHERE CCID=$ccid") or die(mysql_error());
//			echo "$cnt) CCID: $ccid / Applicant No. $applicantno -- Updated from $datedisemb ----> $xdatedisemb (No ARRMNLDATE) <br />";
//		}
//		
//		$cnt++;
//	}
//	else 
//		$cnt2++;
	
	if ($xdisembreason == 'FC')
	{
		if ($xdepmnldate != "NULL")
		{
			$qryupdate = mysql_query("UPDATE crewchange SET DATEEMB=$xdateemb,DEPMNLDATE=$xdepmnldate,DISEMBREASONCODE='FC' WHERE CCID=$ccid") or die(mysql_error());
			echo "$cnt) CCID: $ccid / Applicant No. $applicantno -- Updated from $dateemb ----> $xdateemb <br />";
		}
		else 
		{
			$newdepmnldate = "'" . date("Y-m-d",strtotime("$xdateemb - 1 day")) . "'";
			$qryupdate = mysql_query("UPDATE crewchange SET DATEEMB=$xdateemb,DEPMNLDATE=$newdepmnldate,DISEMBREASONCODE='FC' WHERE CCID=$ccid") or die(mysql_error());
			echo "$cnt) CCID: $ccid / Applicant No. $applicantno -- Updated from $dateemb ----> $xdateemb (No ARRMNLDATE) <br />";
		}
		
		$cnt++;
	}
	else 
		$cnt2++;
}


echo "
<html>\n
<head>\n
<title>Experience FIX</title>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>

<body onload=\"\" style=\"overflow:auto;background-color:White;\">\n




</body>

</html>

";




?>