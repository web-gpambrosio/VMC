<?php
// $kups = "gino";
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

if (isset($_GET['vesselcode']))
	$vesselcode = $_GET['vesselcode'];
	
if (isset($_GET['dateonboard']))
	$dateonboard = $_GET['dateonboard'];
	

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

if(!empty($rowvesseldetails["CONTRACTTERM"]))
	$contractterm=$rowvesseldetails["CONTRACTTERM"]. " months";
else 
	$contractterm = "9 months";
	
if(!empty($dateonboard))
{
	$datenowshow = date("d M Y",strtotime($dateonboard)). " (HISTORY)";
}
	
$principal=$rowvesseldetails["PRINCIPAL"];
$nocrew=$rowvesseldetails["NOCREW"];


//$mainheader="
//<table cellspacing=\"0\" cellpadding=\"0\" width=\"$tablewidth\">
//	<tr height=\"20px\">\n
//		<td style=\"text-align:center;font-size:16pt;font-weight:bold;\">VERITAS MARITIME CORPORATION</td>\n
//	</tr>
//	<tr height=\"20px\">\n
//		<td style=\"text-align:center;font-size:13pt;font-weight:bold;\">CREW ONBOARD LIST, BY VESSEL</td>\n
//	</tr>
//	<tr height=\"20px\">\n
//		<td style=\"text-align:center;font-size:10pt;\">As of $datenowshow</td>\n 
//	</tr>
//</table>
//<br>
//<table style=\"font-size:0.8em;\">
//	<tr height=\"20px\">
//		<td>Name of Vessel</td>
//		<td>:</td>
//		<td><b>$vesselname</b></td>
//	</tr>
//	<tr height=\"20px\">
//		<td>Principal</td>
//		<td>:</td>
//		<td><b>$principal</b></td>
//	</tr>
//	<tr height=\"20px\">
//		<td>No. of Crew</td>
//		<td>:</td>
//		<td><b>--</b></td>
//	</tr>
//	<tr height=\"20px\">
//		<td>Contract Term</td>
//		<td>:</td>
//		<td><b>$contractterm months</b></td>
//	</tr>
//</table>
//<br>";

	#*******************POST VALUES*************



//$qrybyvessel=mysql_query("SELECT cc.APPLICANTNO,CONCAT(FNAME,', ',GNAME,' ',LEFT(MNAME,1)) AS NAME,CIVILSTATUS,RANK,
//	BIRTHDATE,DATEEMB,IF(DATECHANGEDISEMB IS NULL,DATEDISEMB,DATECHANGEDISEMB) AS DATEDISEMB,cc.RANKCODE
//	FROM crewchange cc
//	LEFT JOIN crew c ON cc.APPLICANTNO=c.APPLICANTNO
//	LEFT JOIN rank r ON cc.RANKCODE=r.RANKCODE
//	LEFT JOIN crewpromotionrelation cp ON cp.CCIDPROMOTE=cc.CCID
//	WHERE IF(DATECHANGEDISEMB IS NULL,DATEDISEMB,DATECHANGEDISEMB)>=CURRENT_DATE AND (DEPMNLDATE IS NOT NULL OR cp.CCID IS NOT NULL) 
//	AND cc.VESSELCODE='$vesselcode'
//	ORDER BY RANKING") or die(mysql_error());

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

<form name=\"reponboardvessel\" id=\"reponboardvessel\" method=\"POST\">\n";
//	echo $mainheader;
//	echo $header;
	$addwhereonboard="WHERE (INACTIVE = 0 AND CREWONBOARD = 1) OR FOREIGNER = 1";
	// $addinnerwhere="AND cn.APPLICANTNO IS NULL";
	if(!empty($dateonboard))
	{
		$dateonboardraw=date("Y-m-d",strtotime($dateonboard));
		$addwheredateonboard="AND '$dateonboardraw' BETWEEN c.DATEEMB AND IF(DATECHANGEDISEMB IS NULL,DATEDISEMB,DATECHANGEDISEMB)";
		$addwhereonboard="";
		$addinnerwhere="";
	}
	
	$qry="
							SELECT * FROM (
							SELECT IF (c.ARRMNLDATE IS NOT NULL,'0','1') AS CREWONBOARD,c.CCID,
							IF (c.APPLICANTNO IS NULL,cf.APPLICANTNO,c.APPLICANTNO) AS APPLICANTNO,
							IF (c.APPLICANTNO IS NULL,'1','0') AS FOREIGNER,
							cr.CREWCODE,
							CONCAT(cr.FNAME,', ',cr.GNAME,' ',LEFT(cr.MNAME,1),'.') AS NAME,
							CONCAT(cf.FNAME,', ',cf.GNAME) AS FOREIGNERNAME,
							cr.CIVILSTATUS,cr.BIRTHDATE,
							r.RANKCODE,r.RANKFULL,r.RANKLEVELCODE,r.RANKING,r.ALIAS1,v.VESSEL,v.ALIAS1 AS VALIAS,
							IF(c.DATECHANGEDISEMB IS NULL,c.DATEDISEMB,c.DATECHANGEDISEMB) AS DATEDISEMB,c.DATEEMB,c.ARRMNLDATE,
							IF(DATEDIFF(CURRENT_DATE,IF(c.DATECHANGEDISEMB IS NULL,c.DATEDISEMB,c.DATECHANGEDISEMB)) > 365,1,0) AS INACTIVE,
							DATEDIFF(CURRENT_DATE,c.DATEEMB) AS DAYS,DATEDIFF(c.ARRMNLDATE,c.DATEEMB) AS DAYS1,
							IF(cs.APPLICANTNO IS NULL,0,1) AS SCHOLAR,s.DESCRIPTION AS SCHTYPE,y.CCIDPROMOTE,
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
									AND v.VESSELCODE='$vesselcode' $addwheredateonboard
									ORDER BY c.APPLICANTNO,IF(DATECHANGEDISEMB IS NULL,DATEDISEMB,DATECHANGEDISEMB) DESC
								) x
								GROUP BY APPLICANTNO
								ORDER BY APPLICANTNO,DATEDISEMB DESC
							) y
							LEFT JOIN crewchange c ON c.CCID=y.CCID
							LEFT JOIN crew cr ON cr.APPLICANTNO=c.APPLICANTNO
							LEFT JOIN crewforeign cf ON cf.APPLICANTNO=c.APPLICANTNO
							LEFT JOIN crewscholar cs ON cs.APPLICANTNO=c.APPLICANTNO
							LEFT JOIN scholar s ON s.SCHOLASTICCODE=cs.SCHOLASTICCODE
							LEFT JOIN rank r ON r.RANKCODE=c.RANKCODE
							LEFT JOIN vessel v ON v.VESSELCODE=c.VESSELCODE
							LEFT JOIN crewwithdrawal cw ON cw.APPLICANTNO=c.APPLICANTNO
							LEFT JOIN crewchange cc ON cc.CCID=y.CCIDPROMOTE
							LEFT JOIN crewdeceased cd ON cr.APPLICANTNO=cd.APPLICANTNO
							WHERE cr.STATUS=1 OR cf.APPLICANTNO IS NOT NULL AND cd.APPLICANTNO IS NULL
							AND (cw.APPLICANTNO IS NULL OR cw.LIFTWITHDRAWAL=1) $addinnerwhere
							) z
							$addwhereonboard
							GROUP BY APPLICANTNO
							ORDER BY RANKING,DATEDISEMB DESC
	
						";
//	echo $qry;
	$qrygetlist = mysql_query($qry) or die(mysql_error());
	
	$style = "font-size:8pt;font-family:Arial;font-weight:Bold;";
	$nocrew = mysql_num_rows($qrygetlist);
	
	echo "
	
	<table cellspacing=\"0\" cellpadding=\"0\" width=\"$tablewidth\">
		<tr height=\"20px\">\n
			<td style=\"text-align:center;font-size:16pt;font-weight:bold;\">VERITAS MARITIME CORPORATION</td>\n
		</tr>
		<tr height=\"20px\">\n
			<td style=\"text-align:center;font-size:13pt;font-weight:bold;\">CREW ONBOARD LIST, BY VESSEL</td>\n
		</tr>
		<tr height=\"20px\">\n
			<td style=\"text-align:center;font-size:10pt;\">As of $datenowshow</td>\n 
		</tr>
	</table>
	<br>
	<table style=\"font-size:0.8em;\">
		<tr height=\"20px\">
			<td>Name of Vessel</td>
			<td>:</td>
			<td><b>$vesselname</b></td>
		</tr>
		<tr height=\"20px\">
			<td>Principal</td>
			<td>:</td>
			<td><b>$principal</b></td>
		</tr>
		<tr height=\"20px\">
			<td>No. of Crew</td>
			<td>:</td>
			<td><b>$nocrew</b></td>
		</tr>
		<tr height=\"20px\">
			<td>Contract Term</td>
			<td>:</td>
			<td><b>$contractterm</b></td>
		</tr>
	</table>
	<br>
	
	
	<table cellspacing=\"0\" cellpadding=\"0\" width=\"$tablewidth\" style=\"table-layout:fixed;\">
	<tr height=\"30px\" style=\"text-align:center;valign:middle;\">\n
		<td style=\"$style width:15px;\">&nbsp;</td>\n
		<td style=\"$style text-align:center;width:120px;\">Name of Seaman</td>\n
		<td style=\"$style text-align:center;width:30px;\">Civil Status</td>\n
		<td style=\"$style text-align:center;width:50px;\">Rank</td>\n
		<td style=\"$style text-align:center;width:50px;\">Date Birth</td>\n
		<td style=\"$style text-align:center;width:50px;\">Seaman Book</td>\n
		<td style=\"$style text-align:center;width:60px;\">Passport</td>\n
		<td style=\"$style text-align:center;width:50px;\">Last Vsl/ Co.</td>\n
		<td style=\"$style text-align:center;width:50px;\">Date OB Prom.</td>\n
		<td style=\"$style text-align:center;width:60px;\">Date Embarked</td>\n
		<td style=\"$style text-align:center;width:60px;\">Date F.C.</td>\n
		<td style=\"$style text-align:center;width:40px;\">Months Onboard</td>\n
	</tr>	
	<tr><td colspan=\"12\">&nbsp;</td></tr>
	
	";
	
	$format = "dMY";
	$cntdata=1;
	while($rowgetlist=mysql_fetch_array($qrygetlist))
	{
		$applicantno=$rowgetlist["APPLICANTNO"];
		
		if (!empty($rowgetlist["NAME"]))
			$name=$rowgetlist["NAME"];
		else
			$name=$rowgetlist["FOREIGNERNAME"];
			
		$civilstatus=$rowgetlist["CIVILSTATUS"];
		$rank=$rowgetlist["ALIAS1"];
		$rankcode=$rowgetlist["RANKCODE"];
		$days=$rowgetlist["DAYS"];
		$days1=$rowgetlist["DAYS1"];
		
		if (!empty($rowgetlist["BIRTHDATE"]))
			$birthdate= date($format,strtotime($rowgetlist["BIRTHDATE"]));
		else
			$birthdate="";
			
		$dateemb= date($format,strtotime($rowgetlist["DATEEMB"]));
		$datedisemb= date($format,strtotime($rowgetlist["DATEDISEMB"]));
		
		$ccidpromote=$rowgetlist["CCIDPROMOTE"];
		$promotedateemb=$rowgetlist["PROMOTEDATEEMB"];
		$promotedatedisemb=$rowgetlist["PROMOTEDATEDISEMB"];
		
		if(empty($dateonboard))
			$months1 = round($days / 30);
		else
			$months1 = round($days1 / 30);
		
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
		
		//get last vessel
//		$qrylastvessel=mysql_query("SELECT VESSEL 
//			FROM crewchange c
//			LEFT JOIN vessel v ON c.VESSELCODE=v.VESSELCODE
//			WHERE (IF(DATECHANGEDATE IS NULL,DATEDISEMB,DATECHANGEDATE) < CURRENT_DATE AND c.APPLICANTNO=$applicantno
//			ORDER BY DATEDISEMB DESC
//			LIMIT 1") or die(mysql_error());
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
		
		// if(strtotime($datedisemb)<strtotime($datenow))
			// $months=number_format(((strtotime($datedisemb)-strtotime($dateemb))/(86400*30)),2);
		// else 
			// $months=number_format(((strtotime($dateonboard)-strtotime($dateemb))/(86400*30)),2);
		
		
		echo "
		<tr height=\"20px\" style=\"valign:middle;\">\n
			<td style=\"$style2 text-align:left;\">$cntdata.</td>\n
			<td style=\"$style2 text-align:left;\">$name</td>\n
			<td style=\"$style2 text-align:center;\">$civilstatus</td>\n
			<td style=\"$style2 text-align:left;\">$rank</td>\n
			<td style=\"$style2 text-align:left;\">$birthdate</td>\n
			<td style=\"$style2 text-align:left;\">$seamanbook</td>\n
			<td style=\"$style2 text-align:left;\">$passport</td>\n
			<td style=\"$style2 text-align:left;\">$lastvesselalias</td>\n
			<td style=\"$style2 text-align:left;\">$obpromote</td>\n
			<td style=\"$style2 text-align:left;\">$dateemb</td>\n
			<td style=\"$style2 text-align:left;\">$datedisemb</td>\n
			<td style=\"$style2 text-align:center;\">&nbsp;$months</td>\n
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
