<?php

include('veritas/connectdb.php');
//include('connectdb.php');

session_start();

include('../include/stylephp.inc');

$datetimenow=date("Y-m-d H:i:s");
$datenow=date("Y-m-d");
$datenowshow=date("d M Y");

$getdoccode=$_GET["doccode"];
$getvesselcode=$_GET["vesselcode"];

if(empty($getvesselcode))
{
	$wherepart="";
	
	$qrygetlist = mysql_query("
		SELECT VESSEL,COUNT(*) AS TOTALCREW,SUM(HASDOCS) AS TOTALDOCS
			FROM (
			SELECT * FROM (
				SELECT IF (c.ARRMNLDATE IS NOT NULL,'0','1') AS CREWONBOARD,c.CCID,c.APPLICANTNO,cr.CREWCODE,
				CONCAT(cr.FNAME,', ',cr.GNAME,' ',LEFT(cr.MNAME,1),'.') AS NAME,cr.CIVILSTATUS,cr.BIRTHDATE,
				r.RANKCODE,r.RANKFULL,r.RANKLEVELCODE,r.RANKING,r.ALIAS1,v.VESSEL,v.ALIAS1 AS VALIAS,
				IF(c.DATECHANGEDISEMB IS NULL,c.DATEDISEMB,c.DATECHANGEDISEMB) AS DATEDISEMB,c.DATEEMB,c.ARRMNLDATE,
				IF(DATEDIFF(CURRENT_DATE,IF(c.DATECHANGEDISEMB IS NULL,c.DATEDISEMB,c.DATECHANGEDISEMB)) > 365,1,0) AS INACTIVE,
				DATEDIFF(CURRENT_DATE,c.DATEEMB) AS DAYS,IF(cd.APPLICANTNO IS NULL,0,1) AS HASDOCS
				FROM
				(
					SELECT MAX(CCID) AS CCID,APPLICANTNO
					FROM
					(
						SELECT c.CCID,APPLICANTNO,IF(DATECHANGEDISEMB IS NULL,DATEDISEMB,DATECHANGEDISEMB) AS DATEDISEMB,
						DEPMNLDATE,ARRMNLDATE
						FROM crewchange c
						LEFT JOIN vessel v ON v.VESSELCODE=c.VESSELCODE
						LEFT JOIN crewpromotionrelation cpr ON cpr.CCIDPROMOTE=c.CCID
						WHERE EXISTS
					    (SELECT CCID FROM crewchange x
					      WHERE x.APPLICANTNO=c.APPLICANTNO
					    ) AND (DEPMNLDATE IS NOT NULL OR cpr.CCID IS NOT NULL)
					) x
					GROUP BY APPLICANTNO
					ORDER BY APPLICANTNO,DATEDISEMB DESC
				) y
				LEFT JOIN crewchange c ON c.CCID=y.CCID
				LEFT JOIN crew cr ON cr.APPLICANTNO=c.APPLICANTNO
				LEFT JOIN rank r ON r.RANKCODE=c.RANKCODE
				LEFT JOIN vessel v ON v.VESSELCODE=c.VESSELCODE
				LEFT JOIN crewtransfer ct ON ct.APPLICANTNO=c.APPLICANTNO
				LEFT JOIN crewnfr cn ON cn.APPLICANTNO=c.APPLICANTNO
				LEFT JOIN crewdocstatus cd ON cd.APPLICANTNO=c.APPLICANTNO AND cd.DOCCODE='$getdoccode'
				WHERE cr.STATUS=1 AND ct.APPLICANTNO IS NULL AND cn.APPLICANTNO IS NULL
			) z
			WHERE CREWONBOARD = 1 AND INACTIVE = 0
		) a
		GROUP BY VESSEL
		") or die(mysql_error());
	
}
else //SHOW ALL ONBOARD CREW 
{
	$wherepart="AND (v.VESSELCODE='$getvesselcode' OR v.DIVCODE='$getvesselcode')";
	
	$qrygetlist = mysql_query("
			SELECT * FROM (
			SELECT IF (c.ARRMNLDATE IS NOT NULL,'0','1') AS CREWONBOARD,c.CCID,c.APPLICANTNO,cr.CREWCODE,
			CONCAT(cr.FNAME,', ',cr.GNAME,' ',LEFT(cr.MNAME,1),'.') AS NAME,cr.CIVILSTATUS,cr.BIRTHDATE,
			r.RANKCODE,r.RANKFULL,r.RANKLEVELCODE,r.RANKING,r.ALIAS1,v.VESSEL,v.ALIAS1 AS VALIAS,
			IF(c.DATECHANGEDISEMB IS NULL,c.DATEDISEMB,c.DATECHANGEDISEMB) AS DATEDISEMB,c.DATEEMB,c.ARRMNLDATE,
			IF(DATEDIFF(CURRENT_DATE,IF(c.DATECHANGEDISEMB IS NULL,c.DATEDISEMB,c.DATECHANGEDISEMB)) > 365,1,0) AS INACTIVE,
			DATEDIFF(CURRENT_DATE,c.DATEEMB) AS DAYS,IF(cd.APPLICANTNO IS NULL,0,1) AS HASDOCS,y.CCIDPROMOTE,
			IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS PROMOTEDATEDISEMB,cc.DATEEMB AS PROMOTEDATEEMB
			FROM
			(
				SELECT MAX(CCID) AS CCID,APPLICANTNO,CCIDPROMOTE
				FROM
				(
					SELECT c.CCID,APPLICANTNO,IF(DATECHANGEDISEMB IS NULL,DATEDISEMB,DATECHANGEDISEMB) AS DATEDISEMB,
					DEPMNLDATE,ARRMNLDATE,cpr.CCID AS CCIDPROMOTE
					FROM crewchange c
					LEFT JOIN vessel v ON v.VESSELCODE=c.VESSELCODE
					LEFT JOIN crewpromotionrelation cpr ON cpr.CCIDPROMOTE=c.CCID
					LEFT JOIN crewpromotionrelation cr ON cr.CCID=c.CCID
					WHERE (DEPMNLDATE IS NOT NULL OR cpr.CCID IS NOT NULL) AND cr.CCID IS NULL
					$wherepart
				) x
				GROUP BY APPLICANTNO,CCIDPROMOTE
				ORDER BY APPLICANTNO,DATEDISEMB DESC
			) y
			LEFT JOIN crewchange c ON c.CCID=y.CCID
			LEFT JOIN crew cr ON cr.APPLICANTNO=c.APPLICANTNO
			LEFT JOIN rank r ON r.RANKCODE=c.RANKCODE
			LEFT JOIN vessel v ON v.VESSELCODE=c.VESSELCODE
			LEFT JOIN crewtransfer ct ON ct.APPLICANTNO=c.APPLICANTNO
			LEFT JOIN crewnfr cn ON cn.APPLICANTNO=c.APPLICANTNO
			LEFT JOIN crewdocstatus cd ON cd.APPLICANTNO=c.APPLICANTNO AND cd.DOCCODE='$getdoccode'
			LEFT JOIN crewchange cc ON cc.CCID=y.CCIDPROMOTE
			WHERE cr.STATUS=1 AND ct.APPLICANTNO IS NULL AND cn.APPLICANTNO IS NULL
			) z
			WHERE CREWONBOARD = 1 AND INACTIVE = 0
			GROUP BY APPLICANTNO
			ORDER BY VESSEL,RANKING
		") or die(mysql_error());
	
}


$qrygetdocument=mysql_query("SELECT DOCUMENT FROM crewdocuments WHERE DOCCODE='$getdoccode'") or die(mysql_error());
$rowgetdocument=mysql_fetch_array($qrygetdocument);
$document=$rowgetdocument["DOCUMENT"];



$tablewidth="620px;";

echo	"<html>\n
<title>Reports - Statistical Data</title>
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

<form name=\"scholarfasttrack\" id=\"scholarfasttrack\" method=\"POST\">\n
<table cellspacing=\"0\" cellpadding=\"0\" width=\"$tablewidth\">
	<tr height=\"27px\">\n
		<td style=\"text-align:center;font-size:16pt;font-weight:bold;\">VERITAS MARITIME CORPORATION</td>\n
	</tr>
	<tr height=\"27px\">\n
		<td style=\"text-align:center;font-size:14pt;font-weight:bold;\">DOCUMENT: $document</td>\n
	</tr>
	<tr height=\"27px\">\n
		<td style=\"text-align:center;font-size:12pt;font-weight:bold;\">as of $datenowshow</td>\n
	</tr>
</table>
<br>";

	
	if(empty($getvesselcode))  //ALL VESSELS
	{
		
		
		
	}
	else  //SPECIFIC VESSEL 
	{
		$style = "font-size:8pt;font-family:Arial;font-weight:Bold;";
		$qrydocstat=mysql_query("SELECT IDNO FROM crewdocstatus 
			WHERE DOCCODE='$getdoccode' LIMIT 1") or die(mysql_error());
		if(mysql_num_rows($qrydocstat)==0)
			$doccol="ISSUED";
		else 
			$doccol="EXPIRY";
		echo "
		<table cellspacing=\"0\" cellpadding=\"0\" width=\"$tablewidth\" style=\"table-layout:fixed;\">
		<tr height=\"30px\" style=\"text-align:center;valign:middle;\">\n
			<td style=\"$style width:15px;\">&nbsp;</td>\n
			<td style=\"$style text-align:center;width:120px;\">Name of Seaman</td>\n
			<td style=\"$style text-align:center;width:30px;\">Civil Status</td>\n
			<td style=\"$style text-align:center;width:50px;\">Rank</td>\n
			<td style=\"$style text-align:center;width:50px;\">Date Birth</td>\n
			<td style=\"$style text-align:center;width:50px;\">DocNo</td>\n
			<td style=\"$style text-align:center;width:60px;\">$doccol</td>\n
			<td style=\"$style text-align:center;width:50px;\">Last Vsl/ Co.</td>\n
			<td style=\"$style text-align:center;width:50px;\">Date OB Prom.</td>\n
			<td style=\"$style text-align:center;width:60px;\">Date Embarked</td>\n
			<td style=\"$style text-align:center;width:60px;\">Date F.C.</td>\n
			<td style=\"$style text-align:center;width:40px;\">Months Onboard</td>\n
		</tr>	
		
		";
		
		$format = "dMY";
		$cntdata=1;
		$vesseltmp="";
		while($rowgetlist=mysql_fetch_array($qrygetlist))
		{
			$applicantno=$rowgetlist["APPLICANTNO"];
			$name=$rowgetlist["NAME"];
			$civilstatus=$rowgetlist["CIVILSTATUS"];
			$rank=$rowgetlist["ALIAS1"];
			$rankcode=$rowgetlist["RANKCODE"];
			$vessel=$rowgetlist["VESSEL"];
			$days=$rowgetlist["DAYS"];
			$birthdate= date($format,strtotime($rowgetlist["BIRTHDATE"]));
			$dateemb= date($format,strtotime($rowgetlist["DATEEMB"]));
			$datedisemb= date($format,strtotime($rowgetlist["DATEDISEMB"]));
			
			$ccidpromote=$rowgetlist["CCIDPROMOTE"];
			$promotedateemb=$rowgetlist["PROMOTEDATEEMB"];
			$promotedatedisemb=$rowgetlist["PROMOTEDATEDISEMB"];
			
			
			$months1 = round($days / 30);
			if($months1>0)
				$months=$months1;
			else 
				$months=0;
			//get seaman's book
			$qryseamanbook=mysql_query("SELECT DOCNO 
				FROM crewdocstatus 
				WHERE APPLICANTNO=$applicantno AND DOCCODE='F2'
				ORDER BY DATEISSUED DESC LIMIT 1
				") or die(mysql_error());
			$rowseamanbook=mysql_fetch_array($qryseamanbook);
			$seamanbook=$rowseamanbook["DOCNO"];
			
			//get passport
			$qrypassport=mysql_query("SELECT DOCNO 
				FROM crewdocstatus 
				WHERE APPLICANTNO=$applicantno AND DOCCODE='41'
				ORDER BY DATEISSUED DESC LIMIT 1
				") or die(mysql_error());
			$rowpassport=mysql_fetch_array($qrypassport);
			$passport=$rowpassport["DOCNO"];
			
			$qrylastvessel=mysql_query("SELECT VESSEL,v.ALIAS1,IF(DATECHANGEDATE IS NULL,DATEDISEMB,DATECHANGEDATE) AS DATEDISEMB2,DISEMBREASONCODE
				FROM crewchange c
				LEFT JOIN vessel v ON c.VESSELCODE=v.VESSELCODE
				WHERE c.APPLICANTNO=$applicantno AND (IF(DATECHANGEDATE IS NULL,DATEDISEMB,DATECHANGEDATE)) < CURRENT_DATE AND DISEMBREASONCODE NOT IN ('PR')
				ORDER BY DATEDISEMB2 DESC
				LIMIT 1") or die(mysql_error());
			$rowlastvessel=mysql_fetch_array($qrylastvessel);
			
			$lastvessel=$rowlastvessel["VESSEL"];
			if(!empty($rowlastvessel["ALIAS1"]))
				$lastvesselalias=$rowlastvessel["ALIAS1"];
			else 
				$lastvesselalias="&nbsp;";
			
			$style2 = "font-size:7pt;font-family:Arial;";
			
			if(!empty($ccidpromote))
			{
				$obpromote = $promotedatedisemb;
				$dateemb = $promotedateemb;
			}
			else 
				$obpromote = "&nbsp;";
			
			
			//get docno and issued/expiry
			if($doccol=="EXPIRY")
			{
				$qrydocstat=mysql_query("SELECT DOCNO,DATEEXPIRED FROM crewdocstatus 
					WHERE APPLICANTNO=$applicantno AND DOCCODE='$getdoccode'
					ORDER BY DATEEXPIRED DESC LIMIT 1") or die(mysql_error());
				if(mysql_num_rows($qrydocstat)!=0)
				{
					$rowdocstat=mysql_fetch_array($qrydocstat);
					$docno=$rowdocstat["DOCNO"];
					if (!empty($rowdocstat["DATEEXPIRED"]))
						$docdate=date("dMY",strtotime($rowdocstat["DATEEXPIRED"]));
					else
						$docdate="---";
				}
				else 
				{
					$docno="---";
					$docdate="---";
				}
			}
			else
			{
				$qrycertstat=mysql_query("SELECT DOCNO,DATEISSUED FROM crewdocstatus 
					WHERE APPLICANTNO=$applicantno AND DOCCODE='$getdoccode'
					ORDER BY DATEISSUED DESC LIMIT 1") or die(mysql_error());
				if(mysql_num_rows($qrycertstat)!=0)
				{
					$rowcertstat=mysql_fetch_array($qrycertstat);
					$docno=$rowcertstat["DOCNO"];
					$docdate=date("dMY",strtotime($rowcertstat["DATEISSUED"]));
				}
				else 
				{
					$docno="---";
					$docdate="---";
				}
			}
			if($vesseltmp!=$vessel)
			{
				if(!empty($vesseltmp))
				{
					echo "<tr height=\"20px\" style=\"valign:middle;\"><td colspan=\"12\"><hr></td></tr>";
				}
				echo "
				<tr height=\"20px\" style=\"valign:middle;\">\n
					<td colspan=\"12\" style=\"font-size:10pt;font-family:Arial;text-align:left;\">&nbsp;&nbsp;<u>&nbsp;$vessel&nbsp;</u></td>\n
				</tr>";
				$cntdata=1;
			}
			
			echo "
			<tr height=\"20px\" style=\"valign:middle;\">\n
				<td style=\"$style2 text-align:left;\">$cntdata.</td>\n
				<td style=\"$style2 text-align:left;\" title=\"$applicantno-$getdoccode\">$name</td>\n
				<td style=\"$style2 text-align:center;\">$civilstatus</td>\n
				<td style=\"$style2 text-align:center;\">$rank</td>\n
				<td style=\"$style2 text-align:center;\">$birthdate</td>\n
				<td style=\"$style2 text-align:center;\">$docno</td>\n
				<td style=\"$style2 text-align:center;\">$docdate</td>\n
				<td style=\"$style2 text-align:center;\">$lastvesselalias</td>\n
				<td style=\"$style2 text-align:center;\">$obpromote</td>\n
				<td style=\"$style2 text-align:center;\">$dateemb</td>\n
				<td style=\"$style2 text-align:center;\">$datedisemb</td>\n
				<td style=\"$style2 text-align:center;\">$months</td>\n
			</tr>";
			$cntdata++;
			$vesseltmp=$vessel;
		}
	echo "
		</table>";
	}
	echo "
	</form>";
	
include('../../include/printclose.inc');
echo "
</body>\n
</html>\n";

?>
