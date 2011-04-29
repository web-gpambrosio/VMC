<?php

include('veritas/connectdb.php');
//include('connectdb.php');

session_start();

include('../include/stylephp.inc');

$datetimenow=date("Y-m-d H:i:s");
$datenow=date("Y-m-d");
$datenowshow=date("d M Y");
$tablewidth="915px";

if (isset($_GET['divcode']))
	$divcode = $_GET['divcode'];

if (isset($_GET['rankcode']))
	$rankcode = $_GET['rankcode'];

if (isset($_GET['fleetno']))
	$fleetno = $_GET['fleetno'];

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

$mainheader="
<table cellspacing=\"0\" cellpadding=\"0\" width=\"$tablewidth\">
	<tr height=\"20px\">\n
		<td style=\"text-align:center;font-size:16pt;font-weight:bold;\">VERITAS MARITIME CORPORATION</td>\n
	</tr>
	<tr height=\"20px\">\n
		<td style=\"text-align:center;font-size:13pt;font-weight:bold;color:Blue;\">CREW ON STANDBY LIST, BY RANK $placehdr</td>\n
	</tr>
<!--
	<tr height=\"20px\">\n
		<td style=\"text-align:center;font-size:10pt;\">Division $divcode</td>\n 
	</tr>
-->
	<tr height=\"20px\">\n
		<td style=\"text-align:center;font-size:10pt;\">As of $datenowshow</td>\n 
	</tr>
</table>";

echo	"
<html>\n
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
	// echo $addwherebyrank;
	$qrygetlist = mysql_query("
					SELECT * FROM (
					SELECT IF (c.ARRMNLDATE IS NOT NULL,'0','1') AS CREWONBOARD,c.CCID,c.APPLICANTNO,cr.CREWCODE,
					CONCAT(cr.FNAME,', ',cr.GNAME,' ',LEFT(cr.MNAME,1),'.') AS NAME,
					r.RANKCODE,r.RANKFULL,r.RANKLEVELCODE,r.RANKING,r.ALIAS1,v.VESSEL,v.ALIAS1 AS VALIAS,v.DIVCODE,
					IF(c.DATECHANGEDISEMB IS NULL,c.DATEDISEMB,c.DATECHANGEDISEMB) AS DATEDISEMB,c.ARRMNLDATE,
					IF(DATEDIFF(CURRENT_DATE,IF(c.DATECHANGEDISEMB IS NULL,c.DATEDISEMB,c.DATECHANGEDISEMB)) > 365,1,0) AS INACTIVE,
					DATEDIFF(CURRENT_DATE,IF(c.DATECHANGEDISEMB IS NULL,c.DATEDISEMB,c.DATECHANGEDISEMB)) AS DIFF,
					IF(cs.APPLICANTNO IS NULL,NULL,cs.SCHOLASTICCODE) AS SCHOLAR,s.DESCRIPTION AS SCHTYPE,y.CCIDPROMOTE,v.FLEETNO
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
					LEFT JOIN crewscholar cs ON cs.APPLICANTNO=c.APPLICANTNO
					LEFT JOIN scholar s ON s.SCHOLASTICCODE=cs.SCHOLASTICCODE
					LEFT JOIN rank r ON r.RANKCODE=c.RANKCODE
					LEFT JOIN vessel v ON v.VESSELCODE=c.VESSELCODE
					LEFT JOIN crewchange cc ON cc.CCID=y.CCIDPROMOTE
					LEFT JOIN crewdeceased cd ON cr.APPLICANTNO=cd.APPLICANTNO
					WHERE cr.STATUS=1 AND cd.APPLICANTNO IS NULL
					$addwherebyrank $addwheredivcode
					) z
					WHERE CREWONBOARD = 0 AND INACTIVE = 0
					ORDER BY RANKING,DIVCODE,FLEETNO,DATEDISEMB DESC
				") or die(mysql_error());
	
	$style = "font-size:9pt;font-family:Arial;";
	echo "
	<br />
	<table cellspacing=\"0\" cellpadding=\"0\" style=\"table-layout:fixed;\" width=\"$tablewidth\">
	<tr height=\"30px\" style=\"text-align:center;valign:middle;\">\n
		<td style=\"$style width:20px;\">&nbsp;</td>\n
		<td style=\"$style width:180px;text-align:left;\"><b>Name of Seaman</b></td>\n
		<td style=\"$style width:40px;text-align:left;\"><b>HL</b></td>\n
		<td style=\"$style width:60px;text-align:left;\"><b>Last Vessel</b></td>\n
		<td style=\"$style width:60px;\"><b>Vessel Type</b></td>\n
		<td style=\"$style width:60px;\"><b>Arrived Manila</b></td>\n
		<td style=\"$style width:60px;\"><b>Available Date</b></td>\n
		<td style=\"$style width:100px;\"><b>US Visa</b></td>\n
		<td style=\"$style width:95px;\"><b>Tentative Vessel Assignment</b></td>\n
		<td style=\"$style width:60px;\"><b>ETD</b></td>\n
		<td style=\"$style width:60px;\"><b>SCH</b></td>\n
		<td style=\"$style width:120px;\"><b>Remarks</b></td>\n
	</tr>
	";
	
	$format = "dMY";
	
	$cntdata=1;
	$totaldata = 0;
	$tmprank = "";
	$tmpdiv = "";
	$tmpfleetgrp = "";
	
	while($rowgetlist=mysql_fetch_array($qrygetlist))
	{
		$applicantno=$rowgetlist["APPLICANTNO"];
		$crewcode=$rowgetlist["CREWCODE"];
		$name=$rowgetlist["NAME"];
		$rankcode=$rowgetlist["RANKCODE"];
		$rankfull=$rowgetlist["RANKFULL"];
		$ranklevelcode=$rowgetlist["RANKLEVELCODE"];
		$alias1=$rowgetlist["ALIAS1"];
		$vessel=$rowgetlist["VESSEL"];
		$valias=$rowgetlist["VALIAS"];
		$datedisemb=date($format,strtotime($rowgetlist["DATEDISEMB"]));
		$arrmnldate=date($format,strtotime($rowgetlist["ARRMNLDATE"]));
		$inactive=$rowgetlist["INACTIVE"];
		$diff=$rowgetlist["DIFF"];
		$scholar=$rowgetlist["SCHOLAR"];
		$schtype=$rowgetlist["SCHTYPE"];
		$divcodegrp=$rowgetlist["DIVCODE"];
		$fleetnogrp=$rowgetlist["FLEETNO"];
//		$ccidpromote=$rowgetlist["CCIDPROMOTE"];
//		$promotedateemb=$rowgetlist["PROMOTEDATEEMB"];
//		$promotedatedisemb=$rowgetlist["PROMOTEDATEDISEMB"];

		// $qrygetmaxccid = mysql_query("") or die(mysql_error());
		
		
		$qrychkwithdrawal = mysql_query("
							SELECT APPLICANTNO,FORMDATE,NFR,LIFTWITHDRAWAL
							FROM crewwithdrawal
							WHERE APPLICANTNO=$applicantno
							ORDER BY FORMDATE DESC
							LIMIT 1
						") or die(mysql_error());
						
		if (mysql_num_rows($qrychkwithdrawal) > 0)
		{
			$rowchkwithdrawal = mysql_fetch_array($qrychkwithdrawal);
			$nfr = $rowchkwithdrawal["NFR"];
			$liftwithdrawal = $rowchkwithdrawal["LIFTWITHDRAWAL"];
			
			if ($liftwithdrawal == 0)
				$visible = 0;
			else
				$visible = 1;
		}
		else
			$visible = 1;
			
			
		if ($visible == 1)
		{
		
			if (empty($divcode)) //Only when All Divisions
			{
				if($tmprank != $rankcode)
				{
					echo "
					<tr><td colspan=\"11\">&nbsp;</td></tr>
					<tr height=\"20px\" style=\"valign:middle;\">\n
						<td colspan=\"12\" style=\"font-size:11pt;font-family:Arial;border-bottom:1px solid black;font-weight:bold;text-align:left;color:Blue;\">$rankfull ($alias1)</td>\n
					</tr>";
					
					if($tmpdiv == $divcodegrp)
					{
						echo "
						<tr><td colspan=\"11\">&nbsp;</td></tr>
						<tr height=\"20px\" style=\"valign:middle;\">\n
							<td colspan=\"12\" style=\"font-size:11pt;font-family:Arial;border-bottom:1px solid black;font-weight:bold;text-align:left;\"><i>Division $divcodegrp / Fleet $fleetnogrp</i></td>\n
						</tr>";
					}
					
					$cntdata = 1;
				}
				
				if($tmpdiv != $divcodegrp || $tmpfleetgrp != $fleetnogrp)
				{
					echo "
					<tr><td colspan=\"12\">&nbsp;</td></tr>
					<tr height=\"20px\" style=\"valign:middle;\">\n
						<td colspan=\"12\" style=\"font-size:11pt;font-family:Arial;border-bottom:1px solid black;font-weight:bold;text-align:left;\"><i>Division $divcodegrp / Fleet $fleetnogrp</i></td>\n
					</tr>";
					
				}
			}
			else 
			{
				
				if($tmprank != $rankcode)
				{
					echo "
					<tr><td colspan=\"12\">&nbsp;</td></tr>
					<tr height=\"20px\" style=\"valign:middle;\">\n
						<td colspan=\"12\" style=\"font-size:11pt;font-family:Arial;border-bottom:1px solid black;font-weight:bold;text-align:left;color:Blue;\">$rankfull ($alias1)</td>\n
					</tr>";
					
					$cntdata = 1;
				}
				if(empty($fleetno))
				{
					if($tmpdiv != $divcodegrp || $tmpfleetgrp != $fleetnogrp)
					{
						echo "
						<tr><td colspan=\"12\">&nbsp;</td></tr>
						<tr height=\"20px\" style=\"valign:middle;\">\n
							<td colspan=\"12\" style=\"font-size:11pt;font-family:Arial;border-bottom:1px solid black;font-weight:bold;text-align:left;\"><i>Division $divcodegrp / Fleet $fleetnogrp</i></td>\n
						</tr>";
						$cntdata = 1;
					}
				}
			}
			

			$qrytypelist=mysql_query("SELECT DISTINCT vt.ALIAS
				FROM crewchange cc
				LEFT JOIN vessel v ON cc.VESSELCODE=v.VESSELCODE
				LEFT JOIN vesseltype vt ON v.VESSELTYPECODE=vt.VESSELTYPECODE
				WHERE ARRMNLDATE IS NOT NULL AND cc.APPLICANTNO=$applicantno
				ORDER BY vt.ALIAS") or die(mysql_error());
			$vesseltype="";
			while($rowtypelist=mysql_fetch_array($qrytypelist))
			{
				if($vesseltype=="")
					$vesseltype=$rowtypelist["ALIAS"];
				else 
					$vesseltype.="/".$rowtypelist["ALIAS"];
			}

			
			//get AVAILABILITY from Latest Debriefing...
			
			$qryavailability = mysql_query("SELECT AVAILABILITY
									FROM debriefinghdr d
									LEFT JOIN crewchange cc ON cc.CCID=d.CCID
									WHERE AVAILABILITY IS NOT NULL AND cc.APPLICANTNO=$applicantno
									ORDER BY FILLUPDATE DESC
									LIMIT 1
								") or die(mysql_error());
								
			if (mysql_num_rows($qryavailability) > 0)
			{
				$rowavailability = mysql_fetch_array($qryavailability);
				$availability = date("dMY",strtotime($rowavailability["AVAILABILITY"]));
			}
			else
			{
				$availability = "---";
			}
			
			
			
			//get TENTATIVE ASSIGNMENT
			$qrytentative=mysql_query("SELECT v.ALIAS1,DATEEMB,LEFT(v.VESSEL,6) AS VESSEL
				FROM crewchange cc 
				LEFT JOIN vessel v ON cc.VESSELCODE=v.VESSELCODE
				WHERE DATEEMB>LEFT(NOW(),10) AND cc.APPLICANTNO=$applicantno") or die(mysql_error());
			$cnttent=1;
			$tenttitle="";
			$tentvessel="";
			$tentdateemb="";
			$tenttitle="";
			while($rowtentative=mysql_fetch_array($qrytentative))
			{
				$tentvesseltmp=$rowtentative["ALIAS1"];
				$tentvesselnametmp=$rowtentative["VESSEL"];
				$tentdateembtmp=date($format,strtotime($rowtentative["DATEEMB"]));
				if($cnttent==1)
				{
					$tentvessel=$tentvesseltmp;
					$tentdateemb=$tentdateembtmp;
				}
				else 
				{
					$tenttitle.=$tentvesseltmp."(".$tentdateembtmp.")\n";
					$tentvessel.="*"; //to indicate that tentative vessel is more than one
				}
				$cnttent++;
			}

			//get US VISA
			$qryusvisa=mysql_query("SELECT DOCNO,DATEEXPIRED
									FROM crewdocstatus
									WHERE APPLICANTNO=$applicantno AND DOCCODE='42'
									ORDER BY DATEISSUED DESC LIMIT 1
								") or die(mysql_error());
			$rowusvisa=mysql_fetch_array($qryusvisa);
			if(empty($rowusvisa["DOCNO"]))
				$usvisa="--";
			else
				$usvisa=$rowusvisa["DOCNO"];
			
			if(!empty($rowusvisa["DATEEXPIRED"]))
				$visaexpiry = date($format,strtotime($rowusvisa["DATEEXPIRED"]));
			else
				$visaexpiry = "---";
				
			//JIS LICENSE
			$qryjislic = mysql_query("SELECT DOCNO 
									FROM crewdocstatus 
									WHERE APPLICANTNO=$applicantno AND DOCCODE='J1'
									ORDER BY DATEISSUED DESC LIMIT 1") or die(mysql_error());
			
			$rowjislic = mysql_fetch_array($qryjislic);
			if(empty($rowjislic["DOCNO"]))
				$jis="--";
			else
				$jis=$rowjislic["DOCNO"];
				
			$style2 = "font-size:8pt;font-family:Arial;";
			
			echo "
			<tr height=\"17px\" style=\"valign:middle;\">\n
				<td style=\"$style2 text-align:left;\">$cntdata.</td>\n
				<td style=\"$style2 text-align:left;\" title=\"$applicantno\">$name</td>\n
				<td style=\"$style2 text-align:center;\">&nbsp;</td>\n
				<td style=\"$style2 text-align:center;\">&nbsp;$valias</td>\n
				<td style=\"$style2 text-align:center;\">&nbsp;$vesseltype</td>\n
				<td style=\"$style2 text-align:center;\">&nbsp;$arrmnldate</td>\n
				<td style=\"$style2 text-align:center;\">&nbsp;$availability</td>\n
				<td style=\"$style2 text-align:center;\">&nbsp;$visaexpiry</td>\n
				<td style=\"$style2 text-align:center;\" title=\"$tenttitle\">$tentvessel</td>\n
				<td style=\"$style2 text-align:center;\">&nbsp;$tentdateemb</td>\n
				<td style=\"font-size:7pt;font-family:Arial; text-align:center;\">&nbsp;$scholar</td>\n
				<td style=\"$style2 border-bottom:1px solid black;\">&nbsp;</td>\n
			</tr>";
			
			$cntdata++;
			$totaldata++;
			
			$tmprank = $rankcode;
			$tmpdiv = $divcodegrp;
			$tmpfleetgrp = $fleetnogrp;
		}
	}
echo "
	</table>
	<br />
	<span style=\"font-size:12pt;font-family:Arial;color:Red;\"><b>Total Results: $totaldata</b></span>
	<br /><br />
	
	<table style=\"width:90%;font-size:0.7em;font-weight:Bold;\">
		<tr><td>Legend:</td></tr>
		<tr><td>AA=Excellent,BB=Good,CC=Satisfactory,DD=Poor,EE=Bad / All date format is YMD</td></tr>
		<tr><td>Vessel Type: B=Bulk,P=PCC,G=G. Cargo,O=Others</td></tr>
		<tr><td>Engine Type: M=Mitsubishi,S=Sulzer,B=BMW,H=Hanshin,A=Akasaki,V=V-type,P=Pielstic,O=Others</td></tr>
		<tr><td>HL=Higher License,CER=Crew Evaluation Report,ETD=Estimated Time of Departure,SCH=Scholar</td></tr>
	</table>
	
</form>";
include('veritas/include/printclose.inc');
echo "
</body>\n
</html>\n";

?>
