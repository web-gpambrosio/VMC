<?php

include('veritas/connectdb.php');
//include('connectdb.php');

session_start();

include('../include/stylephp.inc');

$datetimenow=date("Y-m-d H:i:s");
$datenow=date("Y-m-d");
$datenowshow=date("d M Y");
$tablewidth="1050px";

if (isset($_GET['bdate']))
	$bdate = $_GET['bdate'];

if (isset($_GET['edate']))
	$edate = $_GET['edate'];


$bdateshow=date("dMY",strtotime($bdate));
$edateshow=date("dMY",strtotime($edate));
$bdateraw=date("Y-m-d",strtotime($bdate));
$edateraw=date("Y-m-d",strtotime($edate));
$addhdr1="From $bdateshow to $edateshow";
$addwhereonboard="AND DATEDISEMB BETWEEN '$bdateraw' AND '$edateraw'";
$addorderby="DATEDISEMB DESC";

	
$mainheader="
<table cellspacing=\"0\" cellpadding=\"0\" width=\"$tablewidth\">
	<tr height=\"20px\">\n
		<td style=\"text-align:center;font-size:16pt;font-weight:bold;\">VERITAS MARITIME CORPORATION</td>\n
	</tr>
	<tr height=\"20px\">\n
		<td style=\"text-align:center;font-size:13pt;font-weight:bold;\">Crew Withdrawal Summary</td>\n
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
<title>Crew Withdrawal Summary</title>
<body onload=\"\" style=\"overflow:auto;\">\n

<form name=\"alphalist\" method=\"POST\">\n";
	echo $mainheader;
	
	
	$qrygetlist = mysql_query("
			SELECT * FROM (
			SELECT EFFECTDATE,CONCAT(c.FNAME,', ',c.GNAME,' ',LEFT(c.MNAME,1),'.') AS NAME,DATEDIFF(CURRENT_DATE,BIRTHDATE)/365.25 AS AGE,
			BIRTHDATE,SAFEKEEPREMARKS,cw.APPLICANTNO,s.DESCRIPTION AS CLASSCHOLAR,f.FASTTRACK AS CLASSFASTTRACK,cw.MANNINGCOMPANY,cw.SALARYOFFERED,cw.VMCSALARY
			FROM crewwithdrawal cw
			LEFT JOIN crew c ON cw.APPLICANTNO=c.APPLICANTNO
			LEFT JOIN crewscholar cs ON c.APPLICANTNO=cs.APPLICANTNO
			LEFT JOIN scholar s ON cs.SCHOLASTICCODE=s.SCHOLASTICCODE
			LEFT JOIN crewfasttrack cf ON c.APPLICANTNO=cf.APPLICANTNO
			LEFT JOIN fasttrack f ON cf.FASTTRACKCODE=f.FASTTRACKCODE
			WHERE LIFTWITHDRAWAL = 0 AND EFFECTDATE BETWEEN '$bdateraw' AND '$edateraw'
			ORDER BY EFFECTDATE DESC,cw.APPLICANTNO DESC) x
			GROUP BY APPLICANTNO
			ORDER BY APPLICANTNO DESC
	") or die(mysql_error());
	
	
	
	$style = "font-size:8pt;font-family:Arial;border-bottom:1px solid black;border-right:1px solid black;";
	
	echo "
	<br />
	<table cellspacing=\"0\" cellpadding=\"2\" width=\"\" style=\"table-layout:fixed;border-top:1px solid black;border-left:1px solid black;\">
		
		<tr height=\"15px\" style=\"text-align:center;valign:middle;\">\n
			<td rowspan=\"2\" style=\"$style width:30px;\">&nbsp;</td>\n
			<td rowspan=\"2\" style=\"$style width:75px;\">DATE</td>\n
			<td rowspan=\"2\" style=\"$style width:130px;\">NAME</td>\n
			<td rowspan=\"2\" style=\"$style width:30px;\">AGE</td>\n
			<td rowspan=\"2\" style=\"$style width:90px;\">CLASSIFICATION</td>\n
			<td rowspan=\"2\" style=\"$style width:65px;\">RANK</td>\n
			<td rowspan=\"2\" style=\"$style width:65px;\">SCHOOL</td>\n
			<td rowspan=\"2\" style=\"$style width:85px;\">LAST VESSEL ASSIGNMENT</td>\n
			<td rowspan=\"2\" style=\"$style width:125px;\">REASON FOR TRANSFER</td>\n
			<td rowspan=\"2\" style=\"$style width:70px;\">MANNING COMPANY</td>\n
			<td rowspan=\"2\" style=\"$style width:52px;font-size:6pt;\">NO. OF CONTRACTS</td>\n
			<td colspan=\"4\" style=\"$style width:225px;\">AVAILED CREW PROGRAMS</td>\n
			<td rowspan=\"2\" style=\"$style width:55px;\">SALARY OFFERED</td>\n
			<td rowspan=\"2\" style=\"$style width:55px;\">VERITAS SALARY</td>\n
		</tr>
		<tr height=\"59px\" style=\"text-align:center;valign:center;\">\n
			<td style=\"$style;width:47px;text-align:center;\">US VISA</td>\n
			<td style=\"$style;width:47px;text-align:center;\">HIGHER LICENSE SUPPORT</td>\n
			<td style=\"$style;width:47px;text-align:center;\">JIS LICENSE</td>\n
			<td style=\"$style;width:84px;text-align:center;\">TRAINING with unfinished MOA</td>\n
		</tr>
	";
	
	$format = "dMY";
	
	$cntdata=1;
	$totaldata = 0;
//	$tmprank = "";
//	$tmpfleetgrp = "";
//	$tmpdiv = "";
	$style2 = "font-size:7pt;font-family:Arial;border-bottom:1px solid black;border-right:1px solid black;";
	while($rowgetlist=mysql_fetch_array($qrygetlist))
	{//cw.MANNINGCOMPANY,cw.SALARYOFFERED,cw.VMCSALARY
		$effectdate=date($format,strtotime($rowgetlist["EFFECTDATE"]));
		$age=number_format($rowgetlist["AGE"],0);
		$name=$rowgetlist["NAME"];
		$safekeepremarks=$rowgetlist["SAFEKEEPREMARKS"];
		$birthdate=$rowgetlist["BIRTHDATE"];
		$applicantno=$rowgetlist["APPLICANTNO"];
		$classcholar=$rowgetlist["CLASSCHOLAR"];
		$classfasttrack=$rowgetlist["CLASSFASTTRACK"];
		$manningcompany=$rowgetlist["MANNINGCOMPANY"];
		$salaryoffered=$rowgetlist["SALARYOFFERED"];
		$vmcsalary=$rowgetlist["VMCSALARY"];
		
		if(empty($manningcompany))
			$manningcompany="NONE";
		if(empty($salaryoffered))
			$salaryoffered="NONE";
		if(empty($vmcsalary))
			$vmcsalary="NONE";
			
		if(!empty($classcholar))
			$classification=$classcholar;
		else if(!empty($classfasttrack))
			$classification=$classfasttrack;
		else 
			$classification="&nbsp;";
		
		//rank, last vessel, contracts
		
//			LEFT JOIN crewpromotionrelation cpr ON cc.CCID=cpr.CCID
//			LEFT JOIN crewchange cc1 ON cpr.CCIDPROMOTE=cc1.CCID
		$qryrank=mysql_query("SELECT ALIAS2 AS RANK,VESSEL,COUNT(*) AS CONTRACTS,SCHOOL,RANKING FROM (
			SELECT cc.APPLICANTNO,r.ALIAS2,v.VESSEL,IF(ce.SCHOOLID IS NULL,ce.SCHOOLOTHERS,m.SCHOOL) AS SCHOOL,RANKING
			FROM crewchange cc
			LEFT JOIN rank r ON cc.RANKCODE=r.RANKCODE
			LEFT JOIN vessel v ON cc.VESSELCODE=v.VESSELCODE
			LEFT JOIN creweducation ce ON cc.APPLICANTNO=ce.APPLICANTNO
			LEFT JOIN maritimeschool m ON ce.SCHOOLID=m.SCHOOLID
			WHERE cc.APPLICANTNO=$applicantno AND cc.DEPMNLDATE IS NOT NULL AND cc.ARRMNLDATE IS NOT NULL
			ORDER BY cc.ARRMNLDATE DESC) x
			GROUP BY APPLICANTNO") or die(mysql_error());
		$rowrank=mysql_fetch_array($qryrank);
		$rank=$rowrank["RANK"];
		$vessel=$rowrank["VESSEL"];
		$contracts=$rowrank["CONTRACTS"];
		$school=$rowrank["SCHOOL"];
		$ranking=$rowrank["RANKING"];
		
		//US VISA & JIS LICENSE
		$qryusvisa=mysql_query("SELECT * FROM crewdocstatus c WHERE APPLICANTNO=$applicantno AND DOCCODE='42' AND DATEEXPIRED<='$datenow'") or die(mysql_error());
		if(mysql_num_rows($qryusvisa)>0)
			$usvisa="YES";
		else
			$usvisa="NO";
		$qryjis=mysql_query("SELECT * FROM crewdocstatus c WHERE APPLICANTNO=$applicantno AND DOCCODE='J1' AND DATEEXPIRED<='$datenow'") or die(mysql_error());
		if(mysql_num_rows($qryjis)>0)
			$jis="YES";
		else
			$jis="NO";
			
		//higher license
		$qryhigherrank=mysql_query("SELECT RANKING
			FROM crewdocstatus cds
			LEFT JOIN rank r ON cds.RANKCODE=r.RANKCODE
			WHERE cds.APPLICANTNO=$applicantno AND DOCCODE='F1' AND DATEEXPIRED<='$datenow'") or die(mysql_error());
		$rowhigherrank=mysql_fetch_array($qryhigherrank);
		$higherrank=$rowhigherrank["RANKING"];
		if($higherrank<$ranking && !empty($higherrank) && !empty($ranking))
			$higherlicense="YES";
		else
			$higherlicense="NO";
		
		
		
		echo "
		<tr height=\"40px\" style=\"valign:middle;$addstyle\">\n
			<td style=\"$style2 text-align:left;\">$cntdata.</td>\n
			<td style=\"$style2 text-align:center;\">$effectdate</td>\n
			<td style=\"$style2 text-align:left;\"><nobr>$name</nobr></td>\n
			<td style=\"$style2 text-align:center;\">$age</td>\n
			<td style=\"$style2 text-align:center;\">$classification</td>\n
			<td style=\"$style2 text-align:center;\">&nbsp;$rank</td>\n
			<td style=\"$style2 text-align:center;\">&nbsp;$school</td>\n
			<td style=\"$style2 text-align:center;\">&nbsp;$vessel</td>\n
			<td style=\"$style2 text-align:center;\">&nbsp;$safekeepremarks</td>\n
			<td style=\"$style2 text-align:center;\">$manningcompany</td>\n
			<td style=\"$style2 text-align:center;\">&nbsp;$contracts</td>\n
			<td style=\"$style2 text-align:center;\">$usvisa</td>\n
			<td style=\"$style2 text-align:center;\">$higherlicense</td>\n
			<td style=\"$style2 text-align:center;\">$jis</td>\n
			<td style=\"$style2 text-align:center;\">&nbsp;</td>\n
			<td style=\"$style2 text-align:center;\">$salaryoffered</td>\n
			<td style=\"$style2 text-align:center;\">$vmcsalary</td>\n
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
