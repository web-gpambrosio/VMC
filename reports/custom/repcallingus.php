<?php

include('veritas/connectdb.php');
//include('connectdb.php');

session_start();

include('../include/stylephp.inc');

$datetimenow=date("Y-m-d H:i:s");
$datenow=date("Y-m-d");
$datenowshow=date("d M Y");

//CREATE COLUMNS
$fixedcolumn="
<tr height=\"18px\" style=\"display:none;\">\n
	<td style=\"width:200px;\">&nbsp;</td>\n		<!--  1  -->
	<td style=\"width:100px;\">&nbsp;</td>\n		<!--  2  -->
	<td style=\"width:100px;\">&nbsp;</td>\n		<!--  3  -->
	<td style=\"width:100px;\">&nbsp;</td>\n		<!--  4  -->
</tr>
";

	#*******************POST VALUES*************
	
	$qrycrewdetails = mysql_query("
		SELECT VESSEL,COUNT(*) AS TOTALCREW,SUM(HASDOCS) AS TOTALDOCS,CALLINGUS,VISA,CALLINGUS,SUM(VISA) AS TOTVISA,COUNT(*) AS ONBOARD 
			FROM (
			SELECT * FROM (
				SELECT IF (c.ARRMNLDATE IS NOT NULL,'0','1') AS CREWONBOARD,c.CCID,c.APPLICANTNO,cr.CREWCODE,
				CONCAT(cr.FNAME,', ',cr.GNAME,' ',LEFT(cr.MNAME,1),'.') AS NAME,cr.CIVILSTATUS,cr.BIRTHDATE,
				r.RANKCODE,r.RANKFULL,r.RANKLEVELCODE,r.RANKING,r.ALIAS1,v.VESSEL,v.ALIAS1 AS VALIAS,
				IF(c.DATECHANGEDISEMB IS NULL,c.DATEDISEMB,c.DATECHANGEDISEMB) AS DATEDISEMB,c.DATEEMB,c.ARRMNLDATE,
				IF(DATEDIFF(CURRENT_DATE,IF(c.DATECHANGEDISEMB IS NULL,c.DATEDISEMB,c.DATECHANGEDISEMB)) > 365,1,0) AS INACTIVE,
				DATEDIFF(CURRENT_DATE,c.DATEEMB) AS DAYS,IF(cd.APPLICANTNO IS NULL,0,1) AS HASDOCS,
				IF(CALLINGUS=1,'CALLING U.S.','NOT CALLING U.S.') AS CALLINGUS,IF(cd.DOCCODE IS NOT NULL,1,0) AS VISA
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
				LEFT JOIN crewdocstatus cd ON cd.APPLICANTNO=c.APPLICANTNO AND cd.DOCCODE='42'
				WHERE cr.STATUS=1 AND ct.APPLICANTNO IS NULL AND cn.APPLICANTNO IS NULL
			) z
			WHERE CREWONBOARD = 1 AND INACTIVE = 0
			GROUP BY APPLICANTNO
		) a
		GROUP BY VESSEL
		ORDER BY CALLINGUS,VESSEL
		") or die(mysql_error());
	
$qrycrewdetails1=mysql_query("SELECT VESSEL,CALLINGUS,SUM(VISA) AS TOTVISA,COUNT(*) AS ONBOARD 
	FROM (
		SELECT v.VESSEL,IF(CALLINGUS=1,'CALLING U.S.','NOT CALLING U.S.') AS CALLINGUS,IF(cd.DOCCODE IS NOT NULL,1,0) AS VISA
		FROM vessel v
		LEFT JOIN crewchange cc ON v.VESSELCODE=cc.VESSELCODE
	  	LEFT JOIN crewdocstatus cd ON cc.APPLICANTNO=cd.APPLICANTNO AND cd.DOCCODE='42'
		LEFT JOIN crewtransfer ct ON ct.APPLICANTNO=cc.APPLICANTNO
		LEFT JOIN crewnfr cn ON cn.APPLICANTNO=cc.APPLICANTNO
		LEFT JOIN crew cr ON cr.APPLICANTNO=cc.APPLICANTNO
		LEFT JOIN crewforeign cf ON cc.APPLICANTNO=cf.APPLICANTNO
		WHERE CURRENT_DATE BETWEEN cc.DATEEMB AND IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB)
		AND cf.APPLICANTNO IS NULL AND cr.STATUS=1 AND ct.APPLICANTNO IS NULL AND cn.APPLICANTNO IS NULL
	) x
	GROUP BY VESSEL
	ORDER BY CALLINGUS,VESSEL") or die(mysql_error());
$qrycrewdetailsstandby=mysql_query("SELECT SUM(VISA) AS VISASTANDBY,COUNT(*) AS STANDBY 
	FROM (
		SELECT IF(cd.DOCCODE IS NOT NULL,1,0) AS VISA,
		SUM(IF(CURRENT_DATE BETWEEN cc.DATEEMB AND IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB),1,0)) AS ONBOARD,
		SUM(IF(cc.DISEMBREASONCODE IN ('CM','PI','PS'),1,0)) AS CREWOUT
		FROM vessel v
		LEFT JOIN crewchange cc ON v.VESSELCODE=cc.VESSELCODE
	  	LEFT JOIN crewdocstatus cd ON cc.APPLICANTNO=cd.APPLICANTNO AND cd.DOCCODE='42'
		LEFT JOIN crewtransfer ct ON ct.APPLICANTNO=cc.APPLICANTNO
		LEFT JOIN crewnfr cn ON cn.APPLICANTNO=cc.APPLICANTNO
		LEFT JOIN crew cr ON cr.APPLICANTNO=cc.APPLICANTNO
		LEFT JOIN crewforeign cf ON cc.APPLICANTNO=cf.APPLICANTNO
		WHERE DATEDIFF(CURRENT_DATE,IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB)) < 366
	     AND cf.APPLICANTNO IS NULL AND cr.STATUS=1 AND ct.APPLICANTNO IS NULL AND cn.APPLICANTNO IS NULL
	     GROUP BY cc.APPLICANTNO
	) x
	WHERE ONBOARD=0 AND CREWOUT=0") or die(mysql_error());
$rowcrewdetailsstandby=mysql_fetch_array($qrycrewdetailsstandby);
$visastandby=$rowcrewdetailsstandby["VISASTANDBY"];
$standby=$rowcrewdetailsstandby["STANDBY"];
$visastandbypercent=number_format(($visastandby/$standby)*100,2);


echo	"<html>\n
<title>Reports - Calling US</title>
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
<table cellspacing=\"0\" cellpadding=\"0\">
	<tr height=\"27px\">\n
		<td style=\"width:500px;text-align:center;font-size:16pt;font-weight:bold;\">VERITAS MARITIME CORPORATION</td>\n
	</tr>
	<tr height=\"27px\">\n
		<td style=\"width:500px;text-align:center;font-size:12pt;font-weight:bold;\">as of $datenowshow</td>\n
	</tr>
</table>
<br>
<table style=\"border-left:2px solid black;border-top:2px solid black;table-layout:fixed;\" cellspacing=\"0\" cellpadding=\"0\">
	$fixedcolumn
	<tr height=\"35px\" style=\"text-align:center;valign:middle;\">\n
		<td style=\"border-right:2px solid black;border-bottom:1px solid black;font-size:9pt;font-family:Arial ;font-weight:bold;\">VESSEL</td>\n
		<td style=\"border-right:2px solid black;border-bottom:1px solid black;font-size:9pt;font-family:Arial ;font-weight:bold;\"\">WITH US VISA</td>\n
		<td style=\"border-right:2px solid black;border-bottom:1px solid black;font-size:9pt;font-family:Arial ;font-weight:bold;\">CREW FIL O/B</td>\n
		<td style=\"border-right:2px solid black;border-bottom:1px solid black;font-size:9pt;font-family:Arial ;font-weight:bold;\">PERCENTAGE %</td>\n
	</tr>
</table>
<table style=\"border-left:1px solid black;border-top:1px solid black;table-layout:fixed;\" cellspacing=\"0\" cellpadding=\"0\">
	$fixedcolumn";
	echo "
	<tr height=\"30px\">\n
		<td colspan=\"4\" style=\"$styledetails;border-right:1px solid black;font-size:16pt; font-family:Arial ;\"><b>&nbsp;<u><i>ONBOARD</i></u></b></td>\n
	</tr>";
	$callingustemp="";
	$totvisa=0;
	$totonboard=0;
	$gtotvisa=0;
	$gtotonboard=0;
	while($rowcrewdetails=mysql_fetch_array($qrycrewdetails))
	{//VESSEL,CALLINGUS,SUM(VISA) AS TOTVISA,COUNT(*) AS ONBOARD 
		$vessel=$rowcrewdetails["VESSEL"];
		$callingus=$rowcrewdetails["CALLINGUS"];
		$visa=$rowcrewdetails["TOTVISA"];
		$onboard=$rowcrewdetails["ONBOARD"];
		$visapercent=number_format(($visa/$onboard)*100,2);
		if($callingustemp!=$callingus)
		{
			if(!empty($callingustemp))
			{
				$totvisapercent=number_format(($totvisa/$totonboard)*100,2);
				echo "	
				<tr height=\"20px\">\n
					<td style=\"$styledetails;border-right:1px solid black;border-bottom:1px solid black; font-family:Arial ;font-size:12pt;text-align:right;\"><b>Total $callingustemp&nbsp;&nbsp;</b></td>\n
					<td style=\"$styledetails;border-right:1px solid black;border-bottom:1px solid black; font-family:Arial ;font-size:12pt;text-align:center;\"><b>$totvisa</b></td>\n
					<td style=\"$styledetails;border-right:1px solid black;border-bottom:1px solid black; font-family:Arial ;font-size:12pt;text-align:center;\"><b>&nbsp;$totonboard</b></td>\n
					<td style=\"$styledetails;border-right:1px solid black;border-bottom:1px solid black; font-family:Arial ;font-size:12pt;text-align:center;\"><b>&nbsp;$totvisapercent%</b></td>\n
				</tr>";
				$totvisa=0;
				$totonboard=0;
			}
			echo "
			<tr height=\"25px\">\n
				<td colspan=\"4\" style=\"$styledetails;border-right:1px solid black;border-bottom:1px solid black;text-align:center;font-size:14pt; font-family:Arial ;\"><b>$callingus</b></td>\n
			</tr>";
		}
		echo "	
		<tr height=\"18px\">\n
			<td style=\"$styledetails;border-right:1px solid black;border-bottom:1px solid black; font-family:Arial;font-size:9pt;\">&nbsp;$vessel</td>\n
			<td style=\"$styledetails;border-right:1px solid black;border-bottom:1px solid black; font-family:Arial;font-size:9pt;text-align:center;\">$visa</td>\n
			<td style=\"$styledetails;border-right:1px solid black;border-bottom:1px solid black; font-family:Arial;font-size:9pt;text-align:center;\">&nbsp;$onboard</td>\n
			<td style=\"$styledetails;border-right:1px solid black;border-bottom:1px solid black; font-family:Arial;font-size:9pt;text-align:center;\">&nbsp;$visapercent%</td>\n
		</tr>";
		$callingustemp=$callingus;
		$totvisa+=$visa;
		$totonboard+=$onboard;
		$gtotvisa+=$visa;
		$gtotonboard+=$onboard;
	}
	$totvisapercent=number_format(($totvisa/$totonboard)*100,2);
	echo "	
	<tr height=\"20px\">\n
		<td style=\"$styledetails;border-right:1px solid black;border-bottom:1px solid black; font-family:Arial ;font-size:12pt;text-align:right;\"><b>Total $callingus&nbsp;&nbsp;</b></td>\n
		<td style=\"$styledetails;border-right:1px solid black;border-bottom:1px solid black; font-family:Arial ;font-size:12pt;text-align:center;\"><b>$totvisa</b></td>\n
		<td style=\"$styledetails;border-right:1px solid black;border-bottom:1px solid black; font-family:Arial ;font-size:12pt;text-align:center;\"><b>&nbsp;$totonboard</b></td>\n
		<td style=\"$styledetails;border-right:1px solid black;border-bottom:1px solid black; font-family:Arial ;font-size:12pt;text-align:center;\"><b>&nbsp;$totvisapercent%</b></td>\n
	</tr>";
	$gtotvisapercent=number_format(($gtotvisa/$gtotonboard)*100,2);
	echo "	
	<tr height=\"25px\">\n
		<td style=\"$styledetails;border-right:1px solid black;border-bottom:1px solid black; font-family:Arial ;font-size:14pt;text-align:right;\"><b>Total Onboard&nbsp;&nbsp;</b></td>\n
		<td style=\"$styledetails;border-right:1px solid black;border-bottom:1px solid black; font-family:Arial ;font-size:14pt;text-align:center;\"><b>$gtotvisa</b></td>\n
		<td style=\"$styledetails;border-right:1px solid black;border-bottom:1px solid black; font-family:Arial ;font-size:14pt;text-align:center;\"><b>&nbsp;$gtotonboard</b></td>\n
		<td style=\"$styledetails;border-right:1px solid black;border-bottom:1px solid black; font-family:Arial ;font-size:14pt;text-align:center;\"><b>&nbsp;$gtotvisapercent%</b></td>\n
	</tr>
</table>
<br>

<table style=\"border-left:2px solid black;border-top:2px solid black;table-layout:fixed;\" cellspacing=\"0\" cellpadding=\"0\">
	$fixedcolumn
	<tr height=\"35px\" style=\"text-align:center;valign:middle;\">\n
		<td style=\"border-right:2px solid black;border-bottom:1px solid black;font-size:9pt;font-family:Arial ;font-weight:bold;\">&nbsp;</td>\n
		<td style=\"border-right:2px solid black;border-bottom:1px solid black;font-size:9pt;font-family:Arial ;font-weight:bold;\"\">WITH US VISA</td>\n
		<td style=\"border-right:2px solid black;border-bottom:1px solid black;font-size:9pt;font-family:Arial ;font-weight:bold;\">CREW FIL O/B</td>\n
		<td style=\"border-right:2px solid black;border-bottom:1px solid black;font-size:9pt;font-family:Arial ;font-weight:bold;\">PERCENTAGE %</td>\n
	</tr>
</table>
<table style=\"border-left:1px solid black;border-top:1px solid black;table-layout:fixed;\" cellspacing=\"0\" cellpadding=\"0\">
	$fixedcolumn
	<tr height=\"30px\">\n
		<td style=\"$styledetails;border-right:1px solid black;border-bottom:1px solid black;font-size:16pt; font-family:Arial ;\"><b>&nbsp;<u><i>STANDBY</i></u></b></td>\n
		<td style=\"$styledetails;border-right:1px solid black;border-bottom:1px solid black; font-family:Arial ;font-size:14pt;text-align:center;\"><b>$visastandby</b></td>\n
		<td style=\"$styledetails;border-right:1px solid black;border-bottom:1px solid black; font-family:Arial ;font-size:14pt;text-align:center;\"><b>&nbsp;$standby</b></td>\n
		<td style=\"$styledetails;border-right:1px solid black;border-bottom:1px solid black; font-family:Arial ;font-size:14pt;text-align:center;\"><b>&nbsp;$visastandbypercent%</b></td>\n
	</tr>
</table>

</form>";
	
include('../../include/printclose.inc');
echo "
</body>\n
</html>\n";

?>
