<?php

include('veritas/connectdb.php');
//include('connectdb.php');

session_start();

include('../include/stylephp.inc');

$datetimenow=date("Y-m-d H:i:s");
$datenow=date("Y-m-d");
$datenowshow=date("d M Y");
$tablewidth="620px";

if (isset($_GET['divcode']))
	$divcode = $_GET['divcode'];

if (isset($_GET['rankcode']))
	$rankcode = $_GET['rankcode'];

if (isset($_GET['fleetno']))
	$fleetno = $_GET['fleetno'];

if (isset($_GET['dateonboard']))
	$dateonboard = $_GET['dateonboard'];

//if($rankcode!="All" && $rankcode!="M" && $rankcode!="O" && $rankcode!="S")
//{
//	$addwhererank="AND cc.RANKCODE='$rankcode'";
//	$qrygetrank=mysql_query("SELECT RANK FROM rank WHERE RANKCODE='$rankcode'") or die(mysql_error());
//	$rowgetrank=mysql_fetch_array($qrygetrank);
//	$rank=$rowgetrank["RANK"];
//}
//else 
//	$rank=$rankcode;
	
//place additional header if fleetno is given
if(!empty($fleetno))
{
	$placehdr="(Div-$divcode / Fleet-$fleetno)";
}

if(!empty($dateonboard))
	$datenowshow = date("d M Y",strtotime($dateonboard)). " (HISTORY)";

$mainheader="
<table cellspacing=\"0\" cellpadding=\"0\" width=\"$tablewidth\">
	<tr height=\"20px\">\n
		<td style=\"text-align:center;font-size:16pt;font-weight:bold;\">VERITAS MARITIME CORPORATION</td>\n
	</tr>
	<tr height=\"20px\">\n
		<td style=\"text-align:center;font-size:13pt;font-weight:bold;\">CREW ONBOARD LIST, BY RANK $placehdr</td>\n
	</tr>
	<tr height=\"20px\">\n
		<td style=\"text-align:center;font-size:10pt;\">As of $datenowshow</td>\n 
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

<body onload=\"\" style=\"\">\n

<form name=\"scholarfasttrack\" id=\"scholarfasttrack\" method=\"POST\">\n";
	echo $mainheader;
//	echo $header;
	
	if($rankcode!="All" && $rankcode!="M" && $rankcode!="O" && $rankcode!="S")
		$addwherebyrank="AND r.RANKCODE='$rankcode'";
	else 
	{
		if($rankcode=="M" || $rankcode=="O" || $rankcode=="S")
			$addwherebyrank="AND r.RANKLEVELCODE='$rankcode'";
		else
			$addwherebyrank="";
	}
	
	if(!empty($divcode))
	{
		$addwheredivcode = "AND v.DIVCODE='$divcode'";
		if(!empty($fleetno))
			$addwheredivcode .= " AND v.FLEETNO='$fleetno'";
	}
	else 
		$addwheredivcode = "";
	
	$addwhereonboard="AND CREWONBOARD = 1";
	if(!empty($dateonboard))
	{
		$dateonboardraw=date("Y-m-d",strtotime($dateonboard));
		$addwheredateonboard = "AND '$dateonboardraw' BETWEEN DATEEMB AND IF(DATECHANGEDISEMB IS NULL,DATEDISEMB,DATECHANGEDISEMB)";
		$addwhereonboard="";
	}
	else 
		$addwheredateonboard = "";
	
	$qrygetlist = mysql_query("
			SELECT * FROM (
			SELECT IF (c.ARRMNLDATE IS NOT NULL,'0','1') AS CREWONBOARD,c.CCID,c.APPLICANTNO,cr.CREWCODE,
			CONCAT(cr.FNAME,', ',cr.GNAME,' ',LEFT(cr.MNAME,1),'.') AS NAME,v.DIVCODE,
			r.RANKCODE,r.RANKFULL,r.RANKLEVELCODE,r.RANKING,r.ALIAS1,LEFT(v.VESSEL,10) AS VESSEL,v.ALIAS1 AS VALIAS,
			IF(c.DATECHANGEDISEMB IS NULL,c.DATEDISEMB,c.DATECHANGEDISEMB) AS DATEDISEMB,c.DATEEMB,c.ARRMNLDATE,
			IF(DATEDIFF(CURRENT_DATE,IF(c.DATECHANGEDISEMB IS NULL,c.DATEDISEMB,c.DATECHANGEDISEMB)) > 365,1,0) AS INACTIVE,
			DATEDIFF(CURRENT_DATE,IF(DATECHANGEDISEMB IS NULL,DATEDISEMB,DATECHANGEDISEMB)) AS DIFF,v.FLEETNO
			FROM
			(
				SELECT MAX(CCID) AS CCID,APPLICANTNO
				FROM
				(
					SELECT c.CCID,APPLICANTNO,IF(DATECHANGEDISEMB IS NULL,DATEDISEMB,DATECHANGEDISEMB) AS DATEDISEMB,DEPMNLDATE,ARRMNLDATE
					FROM crewchange c
					LEFT JOIN vessel v ON v.VESSELCODE=c.VESSELCODE
					LEFT JOIN crewpromotionrelation cpr ON cpr.CCIDPROMOTE=c.CCID
					LEFT JOIN crewpromotionrelation cr ON cr.CCID=c.CCID
					WHERE (DEPMNLDATE IS NOT NULL OR cpr.CCID IS NOT NULL) AND cr.CCID IS NULL $addwheredivcode $addwheredateonboard
					ORDER BY c.APPLICANTNO,IF(DATECHANGEDISEMB IS NULL,DATEDISEMB,DATECHANGEDISEMB) DESC
				) x
				GROUP BY APPLICANTNO
				ORDER BY APPLICANTNO,DATEDISEMB DESC
			) y
			LEFT JOIN crewchange c ON c.CCID=y.CCID
			LEFT JOIN crew cr ON cr.APPLICANTNO=c.APPLICANTNO
			LEFT JOIN rank r ON r.RANKCODE=c.RANKCODE
			LEFT JOIN vessel v ON v.VESSELCODE=c.VESSELCODE
			LEFT JOIN crewwithdrawal cw ON cr.APPLICANTNO=cw.APPLICANTNO
			LEFT JOIN crewdeceased cd ON cr.APPLICANTNO=cd.APPLICANTNO
			WHERE cr.STATUS=1 AND cd.APPLICANTNO IS NULL
			AND (cw.APPLICANTNO IS NULL OR cw.LIFTWITHDRAWAL=1)
			$addwherebyrank 
			) z
			WHERE INACTIVE = 0 $addwhereonboard
			ORDER BY RANKING,DIVCODE,FLEETNO,DATEDISEMB DESC
	") or die(mysql_error());
	
	$style = "font-size:9pt;font-family:Arial;";
	
	echo "
	<br />
	<table cellspacing=\"0\" cellpadding=\"0\" width=\"$tablewidth\" style=\"table-layout:fixed;\">
		
	<tr height=\"30px\" style=\"text-align:center;valign:middle;\">\n
		<td style=\"$style width:30px;\">&nbsp;</td>\n
		<td style=\"$style width:160px;text-align:left;\"><b>Name of Seaman</b></td>\n
		<td style=\"$style width:75px;text-align:left;\"><b>Name of Vessel</b></td>\n
		<td style=\"$style width:75px;\"><b>Date Embarked</b></td>\n
		<td style=\"$style width:75px;\"><b>Date Expire</b></td>\n
		<td style=\"$style width:130px;\"><b>Remarks</b></td>\n
	</tr>
	";
	
	$format = "dMY";
	
	$cntdata=1;
	$totaldata = 0;
	$tmprank = "";
	$tmpfleetgrp = "";
	$tmpdiv = "";
	
	while($rowgetlist=mysql_fetch_array($qrygetlist))
	{
		$rankcode=$rowgetlist["RANKCODE"];
		$rankfull=$rowgetlist["RANKFULL"];
		$name=$rowgetlist["NAME"];
		$vessel=$rowgetlist["VESSEL"];
		$divcodegrp=$rowgetlist["DIVCODE"];
		$fleetnogrp=$rowgetlist["FLEETNO"];
		$datedisemb=date($format,strtotime($rowgetlist["DATEDISEMB"]));
		$dateemb=date($format,strtotime($rowgetlist["DATEEMB"]));

//		if($tmprank != $getrankcode)
//		{
//			echo "
//			<tr><td>&nbsp;</td></tr>
//			<tr height=\"20px\" style=\"valign:middle;\">\n
//				<td colspan=\"6\" style=\"font-size:10pt;font-family:Arial;border-bottom:1px solid black;font-weight:bold;text-align:left;\">$rankfull</td>\n
//			</tr>";
//			
//			$cntdata = 1;
//		}
		
		if (empty($divcode)) //Only when All Divisions
		{
			if($tmprank != $rankcode)
			{
				echo "
				<tr><td colspan=\"6\">&nbsp;</td></tr>
				<tr height=\"20px\" style=\"valign:middle;\">\n
					<td colspan=\"6\" style=\"font-size:11pt;font-family:Arial;border-bottom:1px solid black;font-weight:bold;text-align:left;color:Blue;\">$rankfull</td>\n
				</tr>";
				
				if($tmpdiv == $divcodegrp)
				{
					echo "
					<tr><td colspan=\"6\">&nbsp;</td></tr>
					<tr height=\"20px\" style=\"valign:middle;\">\n
						<td colspan=\"6\" style=\"font-size:11pt;font-family:Arial;border-bottom:1px solid black;font-weight:bold;text-align:left;\"><i>Division $divcodegrp / Fleet $fleetnogrp</i></td>\n
					</tr>";
				}
				
				$cntdata = 1;
			}
			
			if($tmpdiv != $divcodegrp || $tmpfleetgrp != $fleetnogrp)
			{
				echo "
				<tr><td colspan=\"6\">&nbsp;</td></tr>
				<tr height=\"20px\" style=\"valign:middle;\">\n
					<td colspan=\"6\" style=\"font-size:11pt;font-family:Arial;border-bottom:1px solid black;font-weight:bold;text-align:left;\"><i>Division $divcodegrp / Fleet $fleetnogrp</i></td>\n
				</tr>";
				
				$cntdata = 1;
			}
		}
		else 
		{
			if($tmprank != $rankcode)
			{
				echo "
				<tr><td colspan=\"6\">&nbsp;</td></tr>
				<tr height=\"20px\" style=\"valign:middle;\">\n
					<td colspan=\"6\" style=\"font-size:11pt;font-family:Arial;border-bottom:1px solid black;font-weight:bold;text-align:left;color:Blue;\">$rankfull</td>\n
				</tr>";
				
				$cntdata = 1;
			}
			if(empty($fleetno))
			{
				if($tmpdiv != $divcodegrp || $tmpfleetgrp != $fleetnogrp)
				{
					echo "
					<tr><td colspan=\"6\">&nbsp;</td></tr>
					<tr height=\"20px\" style=\"valign:middle;\">\n
						<td colspan=\"6\" style=\"font-size:11pt;font-family:Arial;border-bottom:1px solid black;font-weight:bold;text-align:left;\"><i>Division $divcodegrp / Fleet $fleetnogrp</i></td>\n
					</tr>";
					$cntdata = 1;
				}
			}
			
		}

		$style2 = "font-size:9pt;font-family:Arial;";
		
		echo "
		<tr height=\"20px\" style=\"valign:middle;\">\n
			<td style=\"$style2 text-align:left;\">$cntdata.</td>\n
			<td style=\"$style2 text-align:left;\">$name</td>\n
			<td style=\"$style2 text-align:left;\">$vessel</td>\n
			<td style=\"$style2 text-align:center;\">$dateemb</td>\n
			<td style=\"$style2 text-align:center;\">$datedisemb</td>\n
			<td style=\"$style2 border-bottom:1px solid black\">&nbsp;</td>\n
		</tr>";
		$cntdata++;
		$totaldata++;
		
		$tmprank = $rankcode;
		$tmpdiv = $divcodegrp;
		$tmpfleetgrp = $fleetnogrp;
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
