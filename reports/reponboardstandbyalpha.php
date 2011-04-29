<?php

include('veritas/connectdb.php');
//include('connectdb.php');

session_start();

include('../include/stylephp.inc');

$datetimenow=date("Y-m-d H:i:s");
$datenow=date("Y-m-d");
$datenowshow=date("d M Y");
$tablewidth="620px";

if (isset($_GET['reptype']))
	$reptype = $_GET['reptype'];

if (isset($_GET['bdate']))
	$bdate = $_GET['bdate'];

if (isset($_GET['edate']))
	$edate = $_GET['edate'];



	
if($reptype==0)
{
	$addhdr="CREW STANDBY ALPHABETICAL LISTING";
	$addhdr1="As of $datenowshow";
	$addwhereonboard="AND CREWONBOARD = 0";
	$addorderby="NAME";
}
else if($reptype==1)
{
	$addhdr="CREW ONBOARD ALPHABETICAL LISTING";
	$addhdr1="As of $datenowshow";
	$addwhereonboard="AND CREWONBOARD = 1";
	$addorderby="NAME";
}
else if($reptype==2)
{
	$addhdr="ONBOARD CREW OVER 9 MONTHS";
	$addhdr1="As of $datenowshow";
	$addwhereonboard="AND CREWONBOARD = 1 AND DAYS/30 > 9";
	$addorderby="DAYS DESC";
}
else if($reptype==3)
{
	$bdateshow=date("dMY",strtotime($bdate));
	$edateshow=date("dMY",strtotime($edate));
	$bdateraw=date("Y-m-d",strtotime($bdate));
	$edateraw=date("Y-m-d",strtotime($edate));
	$addhdr="DISEMBARKING CREW";
	$addhdr1="From $bdateshow to $edateshow";
	$addwhereonboard="AND DATEDISEMB BETWEEN '$bdateraw' AND '$edateraw'";
	$addorderby="DATEDISEMB DESC";
}
	
$mainheader="
<table cellspacing=\"0\" cellpadding=\"0\" width=\"$tablewidth\">
	<tr height=\"20px\">\n
		<td style=\"text-align:center;font-size:16pt;font-weight:bold;\">VERITAS MARITIME CORPORATION</td>\n
	</tr>
	<tr height=\"20px\">\n
		<td style=\"text-align:center;font-size:13pt;font-weight:bold;\">$addhdr</td>\n
	</tr>
	<tr height=\"20px\">\n
		<td style=\"text-align:center;font-size:10pt;\">$addhdr1</td>\n 
	</tr>
</table>";
	#*******************POST VALUES*************


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
<title>Alpha List</title>
<body onload=\"\" style=\"\">\n

<form name=\"alphalist\" method=\"POST\">\n";
	echo $mainheader;
	
	
	$qrygetlist = mysql_query("
			SELECT * FROM (
			SELECT IF (c.ARRMNLDATE IS NOT NULL,'0','1') AS CREWONBOARD,c.CCID,c.APPLICANTNO,cr.CREWCODE,
			CONCAT(cr.FNAME,', ',cr.GNAME,' ',LEFT(cr.MNAME,1),'.') AS NAME,v.DIVCODE,
			r.RANKCODE,r.RANKFULL,r.RANKLEVELCODE,r.RANKING,r.ALIAS2,LEFT(v.VESSEL,10) AS VESSEL,v.ALIAS1 AS VALIAS,
			IF(c.DATECHANGEDISEMB IS NULL,c.DATEDISEMB,c.DATECHANGEDISEMB) AS DATEDISEMB,IF(cc.DATEEMB IS NULL,c.DATEEMB,cc.DATEEMB) AS DATEEMB,c.ARRMNLDATE,
			IF(DATEDIFF(CURRENT_DATE,IF(c.DATECHANGEDISEMB IS NULL,c.DATEDISEMB,c.DATECHANGEDISEMB)) > 365,1,0) AS INACTIVE,
			DATEDIFF(CURRENT_DATE,IF(cc.DATEEMB IS NULL,c.DATEEMB,cc.DATEEMB)) AS DAYS,
			DATEDIFF(CURRENT_DATE,IF(c.DATECHANGEDISEMB IS NULL,c.DATEDISEMB,c.DATECHANGEDISEMB)) AS DIFF,v.FLEETNO,
			IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS PROMOTEDATEDISEMB,IF(cc.DATEEMB<>c.DATEEMB AND cc.DATEEMB IS NOT NULL,c.DATEEMB,NULL) AS PROMOTEDATEEMB
			FROM
			(
				SELECT CCID,APPLICANTNO,CCIDPROMOTE,MAX(DATEEMB) AS DATEEMB
				FROM
				(
					SELECT c.CCID,APPLICANTNO,DATEEMB,IF(DATECHANGEDISEMB IS NULL,DATEDISEMB,DATECHANGEDISEMB) AS DATEDISEMB,DEPMNLDATE,ARRMNLDATE,cpr.CCID AS CCIDPROMOTE
					FROM crewchange c
					LEFT JOIN vessel v ON v.VESSELCODE=c.VESSELCODE
					LEFT JOIN crewpromotionrelation cpr ON cpr.CCIDPROMOTE=c.CCID
					LEFT JOIN crewpromotionrelation cr ON cr.CCID=c.CCID
					LEFT JOIN rank r ON r.RANKCODE=c.RANKCODE
					WHERE (DEPMNLDATE IS NOT NULL OR cpr.CCID IS NOT NULL) AND cr.CCID IS NULL 
					ORDER BY c.APPLICANTNO,DATEEMB DESC
				) x
				GROUP BY APPLICANTNO
				ORDER BY APPLICANTNO,DATEEMB DESC
			) y
			LEFT JOIN crewchange c ON c.CCID=y.CCID
			LEFT JOIN crew cr ON cr.APPLICANTNO=c.APPLICANTNO
			LEFT JOIN rank r ON r.RANKCODE=c.RANKCODE
			LEFT JOIN vessel v ON v.VESSELCODE=c.VESSELCODE
			LEFT JOIN crewchange cc ON cc.CCID=y.CCIDPROMOTE
			LEFT JOIN crewwithdrawal cw ON cr.APPLICANTNO=cw.APPLICANTNO
			LEFT JOIN crewdeceased cd ON cr.APPLICANTNO=cd.APPLICANTNO
			WHERE cr.STATUS=1 AND cd.APPLICANTNO IS NULL
			AND (cw.APPLICANTNO IS NULL OR cw.LIFTWITHDRAWAL=1)
			) z
			WHERE INACTIVE = 0 AND CREWONBOARD = 0 $addwhereonboard
			ORDER BY $addorderby
	") or die(mysql_error());
	
	
	
	$style = "font-size:9pt;font-family:Arial;";
	
	echo "
	<br />
	<table cellspacing=\"0\" cellpadding=\"0\" width=\"$tablewidth\" style=\"table-layout:fixed;\">
		
	<tr height=\"30px\" style=\"text-align:center;valign:middle;\">\n
		<td style=\"$style width:30px;\">&nbsp;</td>\n
		<td style=\"$style width:160px;text-align:left;\"><b>Name of Seaman</b></td>\n
		<td style=\"$style width:30px;\"><b>Rank</b></td>\n
		<td style=\"$style width:75px;text-align:left;\"><b>Vessel</b></td>\n";
		if($reptype==3) // for crew over 9 months
			echo "
			<td style=\"$style width:75px;\"><b>Disembarked</b></td>\n
			<td style=\"$style width:75px;\"><b>Arrive</b></td>\n
			<td style=\"$style width:150px;\"><b>Debrief/Report</b></td>\n";
		else 
			echo "
			<td style=\"$style width:75px;\"><b>Date Embarked</b></td>\n
			<td style=\"$style width:75px;\"><b>Date Expire</b></td>\n";
		if($reptype==2) // for crew over 9 months
			echo "
			<td style=\"$style width:75px;\"><b>OB Pro</b></td>\n
			<td style=\"$style width:75px;\"><b>Months Onboard</b></td>\n";
		else if($reptype<=1)
			echo "
			<td style=\"$style width:130px;\"><b>Remarks</b></td>\n";
	echo "
	</tr>
	";
	
	$format = "dMY";
	
	$cntdata=1;
	$totaldata = 0;
//	$tmprank = "";
//	$tmpfleetgrp = "";
//	$tmpdiv = "";
	
	while($rowgetlist=mysql_fetch_array($qrygetlist))
	{
		$ccid=$rowgetlist["CCID"];
		$rankcode=$rowgetlist["RANKCODE"];
		$alias2=$rowgetlist["ALIAS2"];
		$name=$rowgetlist["NAME"];
		$vessel=$rowgetlist["VESSEL"];
		$divcodegrp=$rowgetlist["DIVCODE"];
		$fleetnogrp=$rowgetlist["FLEETNO"];
		$arrmnldate=$rowgetlist["ARRMNLDATE"];
		if(empty($arrmnldate))
			$arrmnldateshow="";
		else 
			$arrmnldateshow=date($format,strtotime($arrmnldate));
		$promotedateembraw=$rowgetlist["PROMOTEDATEEMB"];
		$days=$rowgetlist["DAYS"];
		$datedisemb=date($format,strtotime($rowgetlist["DATEDISEMB"]));
		$dateemb=date($format,strtotime($rowgetlist["DATEEMB"]));
		
		if(empty($promotedateembraw))
			$promotedate="";
		else 
			$promotedate=date($format,strtotime($promotedateembraw));
		$months1 = number_format(($days / 30),2);
		
		$style2 = "font-size:9pt;font-family:Arial;";
		if($reptype==3)
		{
			$qrydebrief=mysql_query("SELECT FILLUPDATE FROM debriefinghdr WHERE CCID=$ccid") or die(mysql_error());
			$rowdebrief=mysql_fetch_array($qrydebrief);
			$debriefdate=$rowdebrief["FILLUPDATE"];
			if(empty($debriefdate))
			{
				$debriefdateshow="";
				$addstyle="color:red;";
			}
			else 
			{
				$debriefdateshow=$debriefdate;
				$addstyle="";
			}
		}
		echo "
		<tr height=\"20px\" style=\"valign:middle;font-size:0.9em;$addstyle\">\n
			<td style=\"$style2 text-align:left;\">$cntdata.</td>\n
			<td style=\"$style2 text-align:left;\"><nobr>$name</nobr></td>\n
			<td style=\"$style2 text-align:center;\">$alias2</td>\n
			<td style=\"$style2 text-align:left;font-size:0.8em;\"><nobr>$vessel</nobr></td>\n
			<td style=\"$style2 text-align:center;\">$datedisemb</td>\n
			<td style=\"$style2 text-align:center;\">$arrmnldateshow</td>\n";
			if($reptype<=1)
				echo "<td style=\"$style2 border-bottom:1px solid black\">&nbsp;</td>\n";
			else if($reptype==2) // for crew over 9 months
				echo "
				<td style=\"$style2 text-align:center;\">$promotedate</td>\n
				<td style=\"$style2 text-align:center;\">$months1</td>\n";
			else if($reptype==3)
			{
				
				echo "<td style=\"$style2 text-align:center;\">$debriefdateshow</td>\n";
			}
			echo "
		</tr>";
		$cntdata++;
		$totaldata++;
		
//		$tmprank = $rankcode;
//		$tmpdiv = $divcodegrp;
//		$tmpfleetgrp = $fleetnogrp;
	}
echo "
	</table>
	<br />
	<span style=\"font-size:12pt;font-family:Arial;color:Red;\"><b>Total Results: $totaldata</b></span>
</form>";
include('veritas/include/printclose.inc');
echo "
</body>\n
</html>\n";

?>
