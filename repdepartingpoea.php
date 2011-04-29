<?php
$kups="gino";
include("veritas/connectdb.php");
include("veritas/include/stylephp.inc");
include("veritas/include/functions.inc");
require_once "PrintAnything/class.PrintAnything.php";

session_start();

$currentdate = date('Y-m-d H:i:s');
$datenow = date('d F Y');
$basedir = "idpics/";
$basedirdocs = "docimages/";

$dateformat = "dMY";
//$new = 0;
$disabled = "disabled=\"disabled\"";

$marked = "<span style=\"font-size:1em;font-weight:Bold;\">X</span>";

if(isset($_SESSION["employeeid"]))
	$employeeid=$_SESSION["employeeid"];

if (isset($_POST["actiontxt"]))
	$actiontxt = $_POST["actiontxt"];
	
if (isset($_POST["selcourse"]))
	$selcourse = $_POST["selcourse"];
	
if (isset($_POST["applicantno"]))
	$applicantno = $_POST["applicantno"];

if (empty($applicantno))
{
	if (isset($_GET["applicantno"]))
		$applicantno = $_GET["applicantno"];
}

if (isset($_POST["type"]))
	$type = $_POST["type"];
else 
	$type = $_GET["type"];

if (isset($_POST["ccid"]))
	$ccid = $_POST["ccid"];
else
	$ccid = $_GET["ccid"];
	
if (isset($_POST["naia"]))
	$naia = $_POST["naia"];
else
	$naia = $_GET["naia"];
	
if (isset($_POST["title"]))
	$title = $_POST["title"];
else
	$title = $_GET["title"];
	
if (isset($_GET["print"]))
{
	$print = $_GET["print"];
	$disabled = "";
}
else 
	$print = 0;
	
$checked= "checked=\"checked\"";

switch ($actiontxt)
{
	case "updateshoes":
		
		if (isset($_POST["sizeshoes"]))
			$sizeshoes = $_POST["sizeshoes"];
		if (isset($_POST["sizecoverall"]))
			$sizecoverall = $_POST["sizecoverall"];
		if (isset($_POST["sizeraincoat"]))
			$sizeraincoat = $_POST["sizeraincoat"];
		if (isset($_POST["waistline"]))
			$waistline = $_POST["waistline"];
		if (isset($_POST["sizeuniform"]))
			$sizeuniform = $_POST["sizeuniform"];
		if (isset($_POST["weight"]))
			$weight = $_POST["weight"];
		if (isset($_POST["height"]))
			$height = $_POST["height"];
		
		if(empty($weight))
			$weight="NULL";
		if(empty($height))
			$height="NULL";
			
		mysql_query("UPDATE crew SET SIZEUNIFORM='$sizeuniform',SIZECOVERALL='$sizecoverall',SIZERAINCOAT='$sizeraincoat',
			SIZESHOES='$sizeshoes',WAISTLINE='$waistline',HEIGHT=$height,WEIGHT=$weight WHERE APPLICANTNO=$applicantno") or die(mysql_error());
		
		if(!empty($height) || !empty($weight))
			mysql_query("UPDATE crewchange SET EMBHEIGHT=$height,EMBWEIGHT=$weight WHERE CCID=$ccid") or die(mysql_error());
	break;
}

	
	$qrypersonal = mysql_query("SELECT CREWCODE,CONCAT(FNAME,', ',GNAME,' ' ,MNAME) AS NAME,c.STATUS,FNAME,GNAME,MNAME,
									CONCAT(GNAME,' ',LEFT(MNAME,1),'.', ' ',FNAME) AS NAME2,CONCAT(FNAME,', ',GNAME,' ' ,LEFT(MNAME,1),'.') AS NAME3,
									c.ADDRESS,c.MUNICIPALITY,c.CITY,c.ZIPCODE,ab.BARANGAY,at.TOWN,ap.PROVINCE,
									CONCAT(ADDRESS,', ',MUNICIPALITY,', ',CITY,' ',ZIPCODE) AS ADDRESS1,
									CONCAT(ADDRESS,', ',ab.BARANGAY,', ',at.TOWN,', ',ap.PROVINCE,' ',ZIPCODE) AS ADDRESS2,
									ab.BRGYCODE,at.TOWNCODE,ap.PROVCODE,c.BIRTHDATE,c.BIRTHPLACE,c.GENDER,c.CIVILSTATUS,
									TELNO,CEL1,CEL2,CEL3,EMAIL
									FROM crew c
									LEFT JOIN applicant a ON a.APPLICANTNO=c.APPLICANTNO
									LEFT JOIN rank r1 ON r1.RANKCODE=a.CHOICE1
									LEFT JOIN addrprovince ap ON ap.PROVCODE=c.PROVINCECODE
									LEFT JOIN addrtown at ON at.TOWNCODE=c.TOWNCODE AND at.PROVCODE=c.PROVINCECODE
									LEFT JOIN addrbarangay ab ON ab.BRGYCODE=c.BARANGAYCODE AND ab.TOWNCODE=c.TOWNCODE AND ab.PROVCODE=c.PROVINCECODE
									WHERE c.APPLICANTNO=$applicantno") or die(mysql_error());
	

	$rowpersonal = mysql_fetch_array($qrypersonal);
	
	$crewcode = $rowpersonal["CREWCODE"];
	$crewname = $rowpersonal["NAME"];
	$crewname2 = $rowpersonal["NAME2"];
	$crewname3 = $rowpersonal["NAME3"];
	$crewfname = $rowpersonal["FNAME"];
	$crewgname = $rowpersonal["GNAME"];
	$crewmname = $rowpersonal["MNAME"];
	
	$xaddress = $rowpersonal["ADDRESS"];
	$xmunicipality = $rowpersonal["MUNICIPALITY"];
	$xcity = $rowpersonal["CITY"];
	$xzipcode = $rowpersonal["ZIPCODE"];
	
	$xbarangay = $rowpersonal["BARANGAY"];
	$xtown = $rowpersonal["TOWN"];
	$xprovince = $rowpersonal["PROVINCE"];
	
	$xprovcode = $rowpersonal["PROVCODE"];
	$xtowncode = $rowpersonal["TOWNCODE"];
	$xbrgycode = $rowpersonal["BRGYCODE"];
	
	if (!empty($xprovcode) && !empty($xtowncode))
	{
		$crewaddress = $xaddress . ", ";
		
		if (!empty($xbrgycode))
			$crewaddress .= $xbarangay;
			
		if (!empty($xtowncode))
			$crewaddress .= $xtown . ", ";
			
		if (!empty($xprovcode))
			$crewaddress .= $xprovince;
			
		if (!empty($xzipcode))
			$crewaddress .= " " . $xzipcode;
	}
	else 
		$crewaddress = $rowpersonal["ADDRESS1"];
		
	if (empty($crewaddress))
		$crewaddress = $xaddress . " " . $xmunicipality . " " . $xcity . " " . $xzipcode;
		
	$crewtelno1 = $rowpersonal["TELNO"];
	$crewtelno = $rowpersonal["TELNO"];
	$crewcel1 = $rowpersonal["CEL1"];
	$crewcel2 = $rowpersonal["CEL2"];
	$crewcel3 = $rowpersonal["CEL3"];
	
	$crewmobile = $crewcel1;
	
	if (!empty($crewcel1))
		$crewtelno .= " / $crewcel1";
	
	if (!empty($crewcel2))
	{
		$crewtelno .= " / $crewcel2";
		$crewmobile .= " / $crewcel2";
	}
	
	if (!empty($crewcel3))
	{
		$crewtelno .= " / $crewcel3";
		$crewmobile .= " / $crewcel3";
	}
	
	$crewbdate = date("dMY",strtotime($rowpersonal["BIRTHDATE"]));
//	$crewage = floor((strtotime($currentdate) - strtotime($crewbdate)) / (86400*365.25));
	$crewbplace = $rowpersonal["BIRTHPLACE"];
	$crewemail = $rowpersonal["EMAIL"];
	$crewgender1 = $rowpersonal["GENDER"];
	
	switch ($crewgender1)
	{
		case "M" : $crewgender = "MALE"; break;
		case "F" : $crewgender = "FEMALE"; break;
	}
	
	$crewcivil = $rowpersonal["CIVILSTATUS"];
		
	switch ($crewcivil)
	{
		case "M" : $crewcivilstatus = "MARRIED"; break;
		case "S" : $crewcivilstatus = "SINGLE"; break;
		case "W" : $crewcivilstatus = "WIDOWER"; break;
		case "P" : $crewcivilstatus = "SEPARATED"; break;
	}
	
	if (empty($selcourse))
	{
		$qrygeteducation = mysql_query("SELECT ce.COURSEID,IF(mc.COURSE IS NULL,ce.COURSEOTHERS,mc.COURSE) AS COURSE
								FROM creweducation ce
								LEFT JOIN maritimecourses mc ON mc.COURSEID=ce.COURSEID
								WHERE ce.APPLICANTNO=$applicantno
								ORDER BY COURSE
								LIMIT 1
								") or die(mysql_error());
		
		$crewcourse = "";
		
		if (mysql_num_rows($qrygeteducation) > 1) 
		{  //MULTIPLE ENTRY
			
			$crewcourse = "<select name=\"selcourse\">
				<option value=\"\">--Select One--</option>
			";
			
			while ($rowgeteducation = mysql_fetch_array($qrygeteducation))
			{
				$courseid = $rowgeteducation["COURSEID"];
				$course = $rowgeteducation["COURSE"];
			
				$crewcourse .= "<option value=\"$courseid\">$course</option>";
			}
			
			$crewcourse .= "</select>";
		}
		else  //ONLY ONE ENTRY
		{
			$rowgeteducation = mysql_fetch_array($qrygeteducation);
			
			$courseid = $rowgeteducation["COURSEID"];
			$course = $rowgeteducation["COURSE"];
			
			$crewcourse = $course;
		}
	}
	else 
	{
		$qrygetcourse = mysql_query("SELECT COURSE FROM maritimecourses WHERE COURSEID=$selcourse") or die(mysql_error());
		
		$rowgetcourse = mysql_fetch_array($qrygetcourse);
		
		$crewcourse = $rowgetcourse["COURSE"];
	}
	

	$qrygetdocs = mysql_query("SELECT DOCCODE,DOCNO,DATEISSUED,DATEEXPIRED FROM crewdocstatus WHERE DOCCODE IN ('F1','F2','32','41') AND APPLICANTNO=$applicantno") or die(mysql_error());
	
	if (mysql_num_rows($qrygetdocs) > 0)
	{
		while ($rowgetdocs = mysql_fetch_array($qrygetdocs))
		{
			
			$xdoccode = $rowgetdocs["DOCCODE"];
			$xdocno = $rowgetdocs["DOCNO"];
			
			if (!empty($rowgetdocs["DATEEXPIRED"]))
				$xdateexpired = date("dMY",strtotime($rowgetdocs["DATEEXPIRED"]));
			else
				$xdateexpired = "&nbsp;";
			
			switch ($xdoccode)
			{
				case "F1"	: $xlicno = $xdocno; $xlicnoexpiry = $xdateexpired; break;
				case "F2"	: $xseabookno = $xdocno; $xseabooknoexpiry = $xdateexpired; break;
				case "32"	: $xsrcno = $xdocno; $xsrcnoexpiry = $xdateexpired; break;
				case "41"	: $xpassportno = $xdocno; $xpassportnoexpiry = $xdateexpired; break;
			}
		}
	}
	
	$qrygetfamily = mysql_query("SELECT FNAME,GNAME,MNAME,cf.RELCODE,fr.RELATION,cf.ALLOTTEE,
								cf.ADDRESS,cf.MUNICIPALITY,cf.CITY,cf.ZIPCODE,ab.BARANGAY,at.TOWN,ap.PROVINCE,
								CONCAT(cf.ADDRESS,', ',cf.MUNICIPALITY,', ',cf.CITY,' ',cf.ZIPCODE) AS ADDRESS1,
								CONCAT(cf.ADDRESS,', ',ab.BARANGAY,', ',at.TOWN,', ',ap.PROVINCE,' ',ZIPCODE) AS ADDRESS2,
								ab.BRGYCODE,at.TOWNCODE,ap.PROVCODE
								FROM crewfamily cf
								LEFT JOIN familyrelation fr ON fr.RELCODE=cf.RELCODE
								LEFT JOIN addrprovince ap ON ap.PROVCODE=cf.PROVINCECODE
								LEFT JOIN addrtown at ON at.TOWNCODE=cf.TOWNCODE AND at.PROVCODE=cf.PROVINCECODE
								LEFT JOIN addrbarangay ab ON ab.BRGYCODE=cf.BARANGAYCODE AND ab.TOWNCODE=cf.TOWNCODE AND ab.PROVCODE=cf.PROVINCECODE
								WHERE cf.APPLICANTNO=$applicantno AND cf.RELCODE<>'HIM'
								ORDER BY fr.POSITION
								") or die(mysql_error());
	
	$familycontent = "
		<table style=\"width:100%;font-size:0.7em;\">
			<tr>
				<td width=\"40%\" align=\"center\">Name</td>
				<td width=\"20%\" align=\"center\">Relationship to Worker</td>
				<td width=\"40%\" align=\"center\">Address</td>
			</tr>
	";
	$allottee = "";
	
	for ($cnt=1;$cnt <= 3;$cnt++)
	{
		$rowgetfamily = mysql_fetch_array($qrygetfamily);
		
		$fallottee = 0;
		
		$familyfname = $rowgetfamily["FNAME"];
		$familygname = $rowgetfamily["GNAME"];
		$familymname = $rowgetfamily["MNAME"];
		
		$fallottee = $rowgetfamily["ALLOTTEE"];
		$relcode = $rowgetfamily["RELCODE"];
		$relation = $rowgetfamily["RELATION"];
		
		
				$xaddress = $rowgetfamily["ADDRESS"];
				$xmunicipality = $rowgetfamily["MUNICIPALITY"];
				$xcity = $rowgetfamily["CITY"];
				$xzipcode = $rowgetfamily["ZIPCODE"];
				
				$xbarangay = $rowgetfamily["BARANGAY"];
				$xtown = $rowgetfamily["TOWN"];
				$xprovince = $rowgetfamily["PROVINCE"];
				
				$xprovcode = $rowgetfamily["PROVCODE"];
				$xtowncode = $rowgetfamily["TOWNCODE"];
				$xbrgycode = $rowgetfamily["BRGYCODE"];

					if (!empty($xprovcode) && !empty($xtowncode))
					{
						$crewaddress = $xaddress . ", ";
						
						if (!empty($xbrgycode))
							$crewaddress .= $xbarangay;
							
						if (!empty($xtowncode))
							$crewaddress .= $xtown . ", ";
							
						if (!empty($xprovcode))
							$crewaddress .= $xprovince;
							
						if (!empty($xzipcode))
							$crewaddress .= " " . $xzipcode;
					}
					else 
						$crewaddress = $rowpersonal["ADDRESS1"];
					
				if (empty($crewaddress))
					$crewaddress = $xaddress . " " . $xmunicipality . " " . $xcity . " " . $xzipcode;
					
				if (empty($crewaddress))
					$crewaddress = "SAME AS ABOVE";
		
		
//		$address1 = $rowgetfamily["ADDRESS1"];
//		$address2 = $rowgetfamily["ADDRESS2"];
		
//		if (!empty($address1))
//			$showaddress = $address1;
//			
//		if (!empty($address2))
//			$showaddress = $address2;
//			
//		if (empty($showaddress))
//			$showaddress = "SAME AS ABOVE";
		
		if ($relcode == "WFE")
			$wifename = $familyfname . ", " . $familygname . " " . $familymname;
		
		if ($relcode == "MOT")
			$mothername = $familyfname . ", " . $familygname . " " . $familymname;
			
		$familyname = $familyfname . ", " . $familygname . " " . $familymname;
		$familyname2 = $familygname . " " . substr($familymname,0,1) . ". " .$familyfname;
		
		if ($cnt <= 3)
		{
			$familycontent .= "
				<tr>
					<td style=\"border-bottom:1px solid black;font-size:0.9em;\">$familyname2&nbsp;</td>
					<td style=\"border-bottom:1px solid black;font-size:0.9em;\">$relation&nbsp;</td>
					<td style=\"border-bottom:1px solid black;font-size:0.9em;\">$showaddress&nbsp;</td>
				</tr>
			";
		}
		
		if ($fallottee == 1)
		{
			if (!empty($allottee))
				$allottee .= "; " . $familyname;
			else 
				$allottee = $familyname;
		}
	}
	
	$familycontent .= "</table>";


	$qrygetdependents = mysql_query("SELECT FNAME,GNAME,MNAME,fr.RELATION,GENDER,BIRTHDATE
								FROM crewfamily cf
								LEFT JOIN familyrelation fr ON fr.RELCODE=cf.RELCODE
								WHERE cf.APPLICANTNO=$applicantno AND cf.RELCODE<>'HIM'
								ORDER BY fr.POSITION
								LIMIT 4
								") or die(mysql_error());
	
	$dependentcontent = "
		<table style=\"width:100%;font-size:0.7em;\" cellpadding=\"0\">
			<tr>
				<td width=\"30%\" align=\"center\">Name of Spouse/Children/Parents</td>
				<td width=\"10%\" align=\"center\">Sex</td>
				<td width=\"25%\" align=\"center\">Relationship</td>
				<td width=\"25%\" align=\"center\">Date of Birth</td>
			</tr>
	";
	
	while ($rowgetdependents = mysql_fetch_array($qrygetdependents))
	{
		$depfname = $rowgetdependents ["FNAME"];
		$depgname = $rowgetdependents ["GNAME"];
		$depmname = $rowgetdependents ["MNAME"];
		
		$deprelation = $rowgetdependents ["RELATION"];
		$depgender = $rowgetdependents ["GENDER"];
		
		if (!empty($rowgetdependents ["BIRTHDATE"]))
			$depbirthdate = date("dMY",strtotime($rowgetdependents ["BIRTHDATE"]));
		else
			$depbirthdate = "";
	
		$depname = $depfname . ", " . $depgname . " " . $depmname;
		$depname2 = $depgname . " " . substr($depmname,0,1) . ". " .$depfname;

		$dependentcontent .= "
			<tr>
				<td style=\"border-bottom:1px solid black;font-size:0.9em;\">$depname2&nbsp;</td>
				<td style=\"border-bottom:1px solid black;font-size:0.9em;\" align=\"center\">$depgender&nbsp;</td>
				<td style=\"border-bottom:1px solid black;font-size:0.9em;\" align=\"center\">$deprelation&nbsp;</td>
				<td style=\"border-bottom:1px solid black;font-size:0.9em;\" align=\"center\">$depbirthdate&nbsp;</td>
			</tr>
		";
	}
	
	$dependentcontent .= "</table>";
	
	
// if (!empty($ccid))
// {
	// $qrygetalldata = mysql_query("SELECT VESSEL,MANAGEMENT,MANNING,PRINCIPAL,p.ALIAS AS PRINCIPAL_ALIAS,OWNER,sch.SALNAME,sch.CONTRACTPERIOD,sch.VLPMONTH AS VLPAMT,scd.*
									// FROM crewchange cc
									// LEFT JOIN vessel v ON v.VESSELCODE=cc.VESSELCODE
									// LEFT JOIN owner o ON o.OWNERCODE=v.OWNERCODE
									// LEFT JOIN management m ON m.MANAGEMENTCODE=v.MANAGEMENTCODE
									// LEFT JOIN principal p ON p.PRINCIPALCODE=m.PRINCIPALCODE
									// LEFT JOIN manning mn ON mn.MANNINGCODE=m.MANNINGCODE
									// LEFT JOIN salaryscalehdr sch ON sch.SALCODE=v.SALCODE
									// LEFT JOIN salaryscaledtl scd ON scd.SALCODE=sch.SALCODE AND scd.RANKCODE='D11'
									// WHERE cc.CCID=$ccid") or die(mysql_error());
									
	// $rowgetalldata = mysql_fetch_array($qrygetalldata);
	
	// $zvessel = $rowgetalldata["VESSEL"];
	// $zmanagement = $rowgetalldata["MANAGEMENT"];
	// $zmanning = $rowgetalldata["MANNING"];
	// $zprincipal = $rowgetalldata["PRINCIPAL"];
	// $zprincipal_alias = $rowgetalldata["PRINCIPAL_ALIAS"];
	// $zowner = $rowgetalldata["OWNER"];
	// $zsalname = $rowgetalldata["SALNAME"];
	// $zcontractperiod = $rowgetalldata["CONTRACTPERIOD"];
	// $zvlpamt = $rowgetalldata["VLPAMT"];
	// $zbasicsalary = $rowgetalldata["BASICSALARY"];
	// $zfixedot = $rowgetalldata["FIXEDOT"];
	// $zssm5 = $rowgetalldata["SSM5"];
	// $zvlpmonth = $rowgetalldata["VLPMONTH"];
	// $zsubsistence = $rowgetalldata["SUBSISTENCE"];
	// $zretirement = $rowgetalldata["RETIREMENT"];
	// $zotperhour = $rowgetalldata["OTPERHOUR"];
	// $zsupervisorallow = $rowgetalldata["SUPERVISORALLOW"];
	// $zssm11 = $rowgetalldata["SSM11"];
	// $zssm12 = $rowgetalldata["SSM12"];
	// $zssm13 = $rowgetalldata["SSM13"];
// }
	
	
//GET NEXT EMBARK DATE

$qrygetlineup = mysql_query("SELECT cc.CCID,cc.VESSELCODE,v.VESSEL,cc.DATEEMB,
							IF(DATECHANGEDISEMB IS NULL,DATEDISEMB,DATECHANGEDISEMB) AS DATEDISEMB,
							v.OFFICIALNO,v.GROSSTON,DATE_FORMAT(v.BUILTYEAR,'%Y') AS BUILTYEAR,v.CLASSIFICATION,v.DEADWT,t.TRADEROUTE,
							vs.ENGINEPOWER,vs.MAKER,vs.ENGINEMAIN,vs.RPM,vt.VESSELTYPE,po.PORT,po.PORTCOUNTRY,
							IF (cy.COUNTRY IS NULL, v.FLAGOTHERS,cy.COUNTRY) AS FLAG,v.CONTRACTTERM,r.RANKFULL,r.ALIAS1,r.ALIAS2,r.RANKCODE,v.FLAGCODE,
							sch.SALCODE,sch.SALNAME,sch.CONTRACTPERIOD,sch.VLPMONTH,
							scd.BASICSALARY,scd.FIXEDOT,scd.VLPMONTH AS VLPAMOUNT,scd.SUBSISTENCE,scd.OTPERHOUR,scd.SUPERVISORALLOW,
							p.PRINCIPAL,m.MANAGEMENTCODE,m.MANAGEMENT,
							CEILING(DATEDIFF(cc.DATEDISEMB,cc.DATEEMB)/30) AS CONTRACTLEN
							FROM crewchange cc
							LEFT JOIN vessel v ON v.VESSELCODE=cc.VESSELCODE
							LEFT JOIN vesselspecs vs ON vs.VESSELCODE=v.VESSELCODE
							LEFT JOIN vesseltype vt ON vt.VESSELTYPECODE=v.VESSELTYPECODE
							LEFT JOIN country cy ON cy.COUNTRYCODE=v.FLAGCODE
							LEFT JOIN port po ON po.PORTID=cc.EMBPORTID
							LEFT JOIN disembarkreason d ON d.DISEMBREASONCODE=cc.DISEMBREASONCODE
							LEFT JOIN rank r ON r.RANKCODE=cc.RANKCODE
							LEFT JOIN crewpromotionrelation cpr ON cpr.CCIDPROMOTE=cc.CCID
							LEFT JOIN salaryscalehdr sch ON sch.SALCODE=v.SALCODE
							LEFT JOIN salaryscaledtl scd ON scd.SALCODE=sch.SALCODE AND scd.RANKCODE=r.RANKCODE
							LEFT JOIN management m ON m.MANAGEMENTCODE=v.MANAGEMENTCODE
							LEFT JOIN principal p ON p.PRINCIPALCODE=m.PRINCIPALCODE
							LEFT JOIN traderoute t ON t.TRADEROUTECODE=v.TRADEROUTECODE
							WHERE cc.CCID=$ccid
							") or die(mysql_error());



$joiningmonth = "---";
$vessellineup = "---";

if (mysql_num_rows($qrygetlineup) > 0)
{
	$rowgetlineup = mysql_fetch_array($qrygetlineup);
	
	if (!empty($rowgetlineup["DATEEMB"]))
		$joiningmonth = date("F d Y",strtotime($rowgetlineup["DATEEMB"]));
		
	$vessellineup = $rowgetlineup["VESSEL"];
	$vesseltype = $rowgetlineup["VESSELTYPE"];
	$enginepower = $rowgetlineup["ENGINEPOWER"];
	$enginemain = $rowgetlineup["ENGINEMAIN"];
	$enginemaker = $rowgetlineup["MAKER"];
	$rpm = $rowgetlineup["RPM"];
	$officialno = $rowgetlineup["OFFICIALNO"];
	$grosston = $rowgetlineup["GROSSTON"];
	$deadwt = $rowgetlineup["DEADWT"];
	$builtyear = $rowgetlineup["BUILTYEAR"];
	$classification = $rowgetlineup["CLASSIFICATION"];
	$flag = $rowgetlineup["FLAG"];
	$flagcode = $rowgetlineup["FLAGCODE"];
	$traderoute = $rowgetlineup["TRADEROUTE"];
	$port = $rowgetlineup["PORT"];
	$portcountry = $rowgetlineup["PORTCOUNTRY"];
	$contractterm = $rowgetlineup["CONTRACTTERM"];
	$rankfull = $rowgetlineup["RANKFULL"];
	$alias1 = $rowgetlineup["ALIAS1"];
	$alias2 = $rowgetlineup["ALIAS2"];
	$rankcode = $rowgetlineup["RANKCODE"];
	
	$salcode = $rowgetlineup["SALCODE"];
	$salname = $rowgetlineup["SALNAME"];
	$contractperiod = $rowgetlineup["CONTRACTPERIOD"];
	$contractlen = $rowgetlineup["CONTRACTLEN"];
	$vlpmonth = $rowgetlineup["VLPMONTH"];
	$basicsalary = $rowgetlineup["BASICSALARY"];
	$fixedot = $rowgetlineup["FIXEDOT"];
	$vlpamount = $rowgetlineup["VLPAMOUNT"];
	$subsistence = $rowgetlineup["SUBSISTENCE"];
	$otperhour = $rowgetlineup["OTPERHOUR"];
	$supervisorallow = $rowgetlineup["SUPERVISORALLOW"];
	
	$management = $rowgetlineup["MANAGEMENT"];
	$principal = $rowgetlineup["PRINCIPAL"];
	
	$employperiod = round((strtotime($rowgetlineup["DATEDISEMB"]) - strtotime($rowgetlineup["DATEEMB"])) / 2592000);
	
	if ($employperiod < $contractperiod)
		$contractperiod = $employperiod;
}

// END OF GET NEXT EMBARK DATE

$qrylastarrive = mysql_query("SELECT IF(DATECHANGEDISEMB IS NULL,DATEDISEMB,DATECHANGEDISEMB) AS LASTARRIVE,v.VESSEL
								FROM crewchange cc
								LEFT JOIN vessel v ON v.VESSELCODE=cc.VESSELCODE
								WHERE cc.APPLICANTNO=$applicantno AND ARRMNLDATE IS NOT NULL
								ORDER BY DATEDISEMB DESC
								LIMIT 1
							") or die(mysql_error());

$rowlastarrive = mysql_fetch_array($qrylastarrive);
$lastarrived = date("F d Y",strtotime($rowlastarrive["LASTARRIVE"]));
$lastvessel = $rowlastarrive["VESSEL"];

//include("include/datasheet.inc");


echo "
<html>\n
<head>\n
<title>$title</title>

<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>
<style>
#skin0
{
	display: none;
}
</style>

</head>
<body style=\"overflow:auto;\">\n

<form name=\"departingpoea\" method=\"POST\">\n

	";
	
	$styleborder = "style=\"border-bottom:1px solid Black;\"";
	$styledata = "style=\"font-weight:Bold;color:Black;\"";
	$styleencode = "style=\"color:Red;font-weight:Bold;\"";
	
switch ($type)
{
	case "1"	:  //Contract of Employment
		echo "
		<div style=\"margin:5 5 5 10;width:755px;height:600px;\">
		
			<div style=\"width:100%;height:60px;background-color:White;\">
				<center>
					<span style=\"font-size:0.8em;\">Republic of the Philippines</span><br />
					<span style=\"font-size:0.8em;\">Department of Labor and Employment</span><br />
					<span style=\"font-size:0.8em;\">PHILIPPINE OVERSEAS EMPLOYMENT ADMINISTRATION</span><br />
					
					<br />
					<span style=\"font-size:0.9em;font-weight:Bold;\">CONTRACT OF EMPLOYMENT</span>
					
				</center>
			</div>
			
			<div style=\"width:100%;font-size:0.9em;\">
				<br />
				<span style=\"font-weight:Bold;\">KNOW ALL MEN BY THESE PRESENTS</span> <br /><br />
				
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				This contract, entered into voluntarily by and between: 
				<br /><br />
				
				<table style=\"width:100%;font-size:1em;\">
					<tr>
						<td>Name of Seafarer</td>
						<td colspan=\"3\" align=\"left\" $styleborder $styledata>$crewname</td>
					</tr>
					<tr>
						<td>Address</td>
						<td colspan=\"3\" align=\"left\" $styleborder $styledata>$crewaddress</td>
					</tr>
					<tr>
						<td width=\"20%\">SIRB No.</td>
						<td align=\"left\" $styleborder $styledata>&nbsp;$xseabookno</td>
						<td width=\"10%\">SRC No.</td>
						<td align=\"left\" $styleborder $styledata>&nbsp;$xsrcno</td>
					</tr>
					<tr>
						<td>License No.</td>
						<td colspan=\"3\" align=\"left\" $styleborder $styledata>&nbsp;$xlicno</td>
					</tr>
				</table>
				<br />
				hereinafter referred to as the Employee <br />
				<center>and</center>
				
				<table style=\"width:100%;font-size:1em;\">
					<tr>
						<td width=\"20%\">Name of Agent</td>
						<td width=\"80%\" align=\"left\" $styleborder $styledata>VERITAS MARITIME CORPORATION</td>
					</tr>
					<tr>
						<td>For and behalf of</td>
						<td align=\"left\" $styleborder $styledata>$principal</td>
					</tr>
				</table> <br />
				
				<center>(Principal/Country)</center>
				
				for the following vessel: <br />
				
				<table style=\"width:100%;font-size:1em;\">
					<tr>
						<td width=\"20%\">Name of Vessel</td>
						<td align=\"left\" $styleborder $styledata>$vessellineup</td>
					</tr>
				</table>
				
				<table style=\"width:100%;font-size:1em;\">
					<tr>
						<td width=\"20%\">Official Number</td>
						<td width=\"30%\" align=\"center\" $styleborder $styledata>&nbsp;$officialno</td>
						<td width=\"35%\">Gross Registered Tonnage(GRT)</td>
						<td width=\"15%\" align=\"left\" $styleborder $styledata>&nbsp;$grosston</td>
					</tr>
				</table>
				<table style=\"width:100%;font-size:1em;\">
					<tr>
						<td width=\"7%\">Flag</td>
						<td width=\"22%\" align=\"left\" $styleborder $styledata>&nbsp;$flag</td>
						<td width=\"10%\">Year Built</td>
						<td width=\"10%\" align=\"left\" $styleborder $styledata>&nbsp;$builtyear</td>
						<td width=\"30%\">Classification Society</td>
						<td width=\"20%\" align=\"left\" $styleborder $styledata>&nbsp;$classification</td>
					</tr>
				</table>
				<br />
				hereinafter referred to as the Employee <br />
				
				<center>WITNESSETH</center>
				<br />
				
				1. That the Employee shall be employed on board under the following terms and conditions: <br />
				
				<table style=\"width:100%;font-size:1em;\">
					<tr>
						<td width=\"5%\">1.1</td>
						<td width=\"25%\">Duration of Contract</td>
						<td width=\"70%\" $styleborder $styledata>$contractterm months</td>
					</tr>
					<tr>
						<td>1.2</td>
						<td>Position</td>
						<td $styleborder $styledata>$rankfull</td>
					</tr>
					<tr>
						<td>1.3</td>
						<td>Basic Monthly Salary</td>
						<td $styleborder ><span $styledata>$$basicsalary</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							Subsistence Allowance&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span $styledata> $$subsistence Per Month </span>
						</td>
					</tr>
					<tr>
						<td>1.4</td>
						<td>Hours of Work</td>
						<td $styleborder $styledata>40 hours/week</td>
					</tr>
					<tr>
						<td>1.5</td>
						<td>Overtime</td>
						<td $styleborder $styledata>$$fixedot&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Fixed Overtime</td>
					</tr>
					<tr>
						<td>1.6</td>
						<td>Vacation Leave with Pay</td>
						<td $styleborder $styledata>$vlpmonth&nbsp;&nbsp;&nbsp;days/month</td>
					</tr>
					<tr>
						<td>1.7</td>
						<td>Point of Hire</td>
						<td $styleborder $styledata>MANILA, PHILIPPINES</td>
					</tr>
				</table>
				<br />
				
				2. The herein terms and conditions in accordance with Department Order No.4 and Memorandum Circular 
				No. 09, both Series of 2000, shall be strictly and faithfully observed.
				<br /><br />
				3. Any alterations or changes, in any part of the Contract shall be evaluated, verified, processed and approved by the 
				Philippine Overseas Employment Administration (POEA).  Upon approval, the same shall be deemed an integral part of the 
				Standard Terms and Conditions Governing the Employment of Filipino Seafarers On Board Ocean-Going Vessels.
				<br /><br />
				4. Violations of the terms and conditions of the Contract with its approved addendum shall be a ground for disciplinary
				action against the erring party.
				<br /><br />
				
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				IN WITNESS WHEREOF, the parties have hereto set their hands this day of 23rd January 2008 at Manila, Philippines.
				
				<br /><br />
				
				<table style=\"width:100%;font-size:1em;\">
					<tr>
						<td width=\"43%\" align=\"center\" $styleborder $styledata>$rankalias $crewname2</td>
						<td width=\"13%\">&nbsp;</td>
						<td width=\"43%\" align=\"center\" $styleborder>&nbsp;</td>
					</tr>
					<tr>
						<td align=\"center\">Seafarer</td>
						<td>&nbsp;</td>
						<td align=\"center\">For the Employer</td>
					</tr>
				</table>
				
				<br /><br />
				
				Verified and approved by the POEA:
				
				<br /><br />
				
				<table style=\"width:100%;font-size:1em;\">
					<tr>
						<td width=\"43%\" align=\"center\" $styleborder $styledata>January 23, 2008</td>
						<td width=\"13%\">&nbsp;</td>
						<td width=\"43%\" align=\"center\" $styleborder>&nbsp;</td>
					</tr>
					<tr>
						<td width=\"43%\" align=\"center\">Date</td>
						<td width=\"13%\">&nbsp;</td>
						<td width=\"43%\" align=\"center\">Signature of POEA Official</td>
					</tr>
				</table>
				
			</div>
			
		</div>
		";
	break;
	
	case "2"	: //AMOSUP Contract
		echo "
		<div style=\"margin:5 5 5 10;width:755px;height:600px;\">
			";
		
			$stylesection = "style=\"font-size:1.1em;font-weight:Bold;\"";
			
			$fontsize2 = "font-size:0.7em;";
		
		echo "	
			<div style=\"width:100%;\">
				<center>
					<span style=\"font-size:1em;font-weight:Bold;\">EMPLOYMENT CONTRACT</span>
				</center>
				<br />
			
				<table style=\"width:100%;$fontsize2\">
					<tr>
						<td width=\"50%\" align=\"left\" $stylesection>THE SEAMAN</td>
						<td width=\"50%\" align=\"center\">Date : <span $styledata>$datenow</span></td>
					</tr>
				</table>
				
				<table style=\"width:100%;$fontsize2\" cellpadding=\"3\" cellspacing=\"0\" border=1>
					<tr>
						<td width=\"50%\" align=\"left\">Surname:&nbsp;<span $styledata>$crewfname&nbsp;</span></td>
						<td width=\"50%\" align=\"left\">Given Name:&nbsp;<span $styledata>$crewgname&nbsp;</span></td>
					</tr>
					<tr>
						<td colspan=\"2\" align=\"left\">Home Address:&nbsp;&nbsp;&nbsp;
							<span $styledata>$crewaddress</span>
						</td>
					</tr>
					<tr>
						<td align=\"left\">Position:&nbsp;<span $styledata>$rankfull</span></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td align=\"left\">Estimated time of taking up position: &nbsp;&nbsp;<span $styledata> ASAP</span></td>
						<td>Port where position is taken up:</td>
					</tr>
				</table>
				
				<table style=\"width:100%;$fontsize2\" cellpadding=\"3\" cellspacing=\"0\" border=1>
					<tr>
						<td align=\"left\">Nationality:<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<span $styledata>FILIPINO</span>
						</td>
						<td align=\"left\">Passport No.<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<span $styledata>$xpassportno</span>
						</td>
						<td align=\"left\">Seaman's Book No.<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<span $styledata>$xseabookno</span>
						</td>
					</tr>
				</table>
				
				<table style=\"width:100%;$fontsize2\">
					<tr>
						<td align=\"left\" $stylesection>EMPLOYER AND VESSEL</td>
					</tr>
				</table>
				
				<table style=\"width:100%;$fontsize2\" cellpadding=\"3\" cellspacing=\"0\" border=1>
					<tr>
						<td width=\"50%\" align=\"left\">Shipping Company:&nbsp;<span $styledata>$principal&nbsp;</span></td>
						<td width=\"50%\" align=\"left\"><span $styledata>VERITAS MARITIME CORPORATION&nbsp;</span></td>
					</tr>
					<tr>
						<td align=\"left\" valign=\"top\">Address:<br />&nbsp;&nbsp;&nbsp;
							<span $styledata>&nbsp;</span>
						</td>
						<td align=\"left\" valign=\"top\">Address:<br />&nbsp;&nbsp;&nbsp;
							<span $styledata>15th Floor, MARC 2000 Tower, 1973 Taft Avenue, Malate<br />Manila, Philippines</span>
						</td>
					</tr>
				</table>
				
				<table style=\"width:100%;$fontsize2\" cellpadding=\"3\" cellspacing=\"0\" border=1>
					<tr>
						<td witdh=\"40%\" align=\"left\">Vessel:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<span $styledata>$vessellineup</span>
						</td>
						<td witdh=\"30%\" align=\"left\">Official No:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<span $styledata>$officialno</span>
						</td>
						<td witdh=\"30%\" align=\"left\">Flag:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<span $styledata>$flagcode</span>
						</td>
					</tr>
				</table>
				
				<table style=\"width:100%;$fontsize2\" cellpadding=\"2\" cellspacing=\"1\">
					<tr>
						<td align=\"left\" $stylesection>TERMS OF CONTRACT</td>
					</tr>
				</table>
				
				<table style=\"width:100%;font-size:0.7em;\" cellpadding=\"2\" cellspacing=\"1\" border=1>
					<tr>
						<td colspan=\"3\">
							<span style=\"font-size=1em;\">
							The current ITF Collective Agreement, brought into force by the Special Agreement between the International Transport Worker's Federation and dated ______________________________ shall govern the contract provided that should the ITF and this Company enter into a further
							Special Agreement the ITF Collective Agreement brought into force by that Special Agreement shall, from the date on which the further Special Agreement is made, govern this contract.
							</span>
						</td>
					</tr>
					<tr>
						<td valign=\"top\" witdh=\"33%\">
							<table width=\"100%\" style=\"font-size=1em;\">
								<tr>
									<td>Period of Employment:<br />&nbsp;&nbsp;&nbsp;
										<span $styledata>$contractperiod Months</span>
									</td>
								</tr>
								<tr>
									<td>Hours of work:<span $styledata>&nbsp;40 hours / week</span> </td>
								</tr>
								<tr>
									<td>8 per day  Monday - Friday</td>
								</tr>
							</table>
						</td>
						<td valign=\"top\" witdh=\"33%\">
							<table width=\"100%\" style=\"font-size=1em;\">
								<tr>
									<td>Basic monthly wage:<br />&nbsp;&nbsp;&nbsp;
										<span $styledata>$$basicsalary</span>
									</td>
								</tr>
								<tr>
									<td>Weekday overtime rate:<br />
										<span $styledata>$$fixedot</span>
										&nbsp;&nbsp;&nbsp; 
										in excess w/ hourly rate of
									</td>
								</tr>
							</table>
						</td>
						<td valign=\"top\" witdh=\"33%\">
							<table width=\"100%\" style=\"font-size=1em;\">
								<tr>
									<td>Wages from and including:<br />
										&nbsp;&nbsp;&nbsp;
										<span $styledata>upon departure Manila</span>
									</td>
								</tr>
								<tr>
									<td>Saturday, Sunday and Public Holiday-overtime:<br />
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										 <span $styledata>NIL</span>
										 &nbsp;&nbsp;&nbsp;
										 rate per hour
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td valign=\"top\">Leave:<br />
							&nbsp;&nbsp;&nbsp;
							<span $styledata>$vlpmonth days/month</span>
						</td>
						<td valign=\"top\">Daily leave pay:<br />
							&nbsp;&nbsp;&nbsp;
							<span $styledata>Per hour</span>
						</td>
						<td valign=\"top\">Daily Subsistence allowance on leave:<br />
							<span $styledata>$$subsistence&nbsp;&nbsp; per month</span>
						</td>
					</tr>
					<tr>
						<td valign=\"top\" colspan=\"3\">
							Other Terms:<br />
								&nbsp;&nbsp;&nbsp;&nbsp;  
								<span $styledata>$salname</span> <br />
								
							The ITF may vary the terms and conditions in accordance with Article 5 of the Special Agreement.  The varied terms shall then apply.
						</td>
					</tr>
				</table>
				
				<table style=\"width:100%;$fontsize2\" cellpadding=\"2\" cellspacing=\"1\">
					<tr>
						<td align=\"left\" $stylesection>CONFIRMATION OF THE CONTRACT</td>
					</tr>
				</table>
				
				<table style=\"width:100%;$fontsize2\" cellpadding=\"2\" cellspacing=\"1\" border=1>
					<tr>
						<td valign=\"top\" width=\"50%\">
							Signature of Employer/Master:<br />
							<br />
						</td>
						<td valign=\"top\" width=\"50%\">
							Signature of Seaman:<br /><br />
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<span $styledata>$alias1 $crewname2</span>
						</td>
					</tr>
				</table>
				<hr>
				<table style=\"width:100%;$fontsize2\" cellpadding=\"2\" cellspacing=\"1\">
					<tr>
						<td align=\"left\" $stylesection>TERMINATION OF SERVICE</td>
					</tr>
				</table>
				
				<table style=\"width:100%;$fontsize2\" cellpadding=\"2\" cellspacing=\"1\" border=1>
					<tr>
						<td valign=\"top\" width=\"100%\">
							Fourteen day notice may be given by either party: <br />
							Notice was given on (date)
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							by seaman/master: <br />
							
							Signature of party giving notice:
						</td>
					</tr>
				</table>
				
				<table style=\"width:100%;$fontsize2\" cellpadding=\"2\" cellspacing=\"1\">
					<tr>
						<td align=\"left\" $stylesection>CERTIFICATION WHEN LEAVING POSITION</td>
					</tr>
				</table>
				
				<table style=\"width:100%;$fontsize2\" cellpadding=\"2\" cellspacing=\"1\" border=1>
					<tr>
						<td valign=\"top\" width=\"50%\">
							Position left:<br /><br />
							Date:<br /><br />
							Port:<br /><br />
						</td>
						<td valign=\"top\" width=\"50%\">
							Reason:<br /><br />
							__ Illness  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  __ Injury <br /><br />
							__ Annual leave  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  __ Expiry <br /><br />
							
							Other reason:<br /><br />
						</td>
					</tr>
					<tr>
						<td valign=\"top\">SIGNATURE OF MASTER: <br /><br /></td>
						<td valign=\"top\">SIGNATURE OF SEAMAN: <br /><br /></td>
					</tr>
				</table>
				<br /><br />
				
			</div>
			
		</div>
		";
	break;
	
	case "3"	: //Info Sheet
	
		echo "
		<div style=\"margin:5 5 5 10;width:755px;height:600px;\">
			";
		
			$stylesection = "style=\"font-size:1.1em;font-weight:Bold;\"";
			$fontsize3 = "font-size:0.7em;";
		
		echo "	
			<div style=\"width:100%;\">
				<table style=\"width:100%;$fontsize3\">
					<tr>
						<td width=\"25%\">
							<table style=\"width:100%;font-size:0.8em;border:1px solid black;\" cellpadding=0 cellspacing=0>
								<tr>
									<td>
										LATEST PAYMENT DATE: <br />
										1. OWWA Membership:&nbsp;______________<br />
										2. Philhealth/Medicare:&nbsp;_______________ <br />
									</td>
								</tr>
							</table>
							<br />
							<table style=\"width:100%;font-size:0.8em;\" cellpadding=0 cellspacing=0>
								<tr>
									<td>OFW e-Card/ID No:</td>
									<td style=\"border-bottom:1px solid black;\">&nbsp;</td>
								</tr>
								<tr>
									<td>SSS No:</td>
									<td style=\"border-bottom:1px solid black;\">03-3928781-1</td>
								</tr>
								<tr>
									<td>SID No:</td>
									<td style=\"border-bottom:1px solid black;\">&nbsp;</td>
								</tr>
								<tr>
									<td>Philhealth No:</td>
									<td style=\"border-bottom:1px solid black;\">&nbsp;</td>
								</tr>
							</table>
						</td>
						
						<td width=\"50%\" style=\"font-size:1em;\">

							<center>
								PHILIPPINE OVERSEAS EMPLOYMENT ADMINISTRATION <br />
								OVERSEAS WORKERS WELFARE ADMINISTRATION <br />
								PHILIPPINE HEALTH INSURANCE CORPORATION
								
								<br /><br /><br />
								
								<span style=\"font-size:1.2em;font-weight:Bold;\">INFORMATION SHEET</span>
							
							</center>
						
						</td>
						
						<td width=\"25%\">
							
							<b>FM-264</b><br />
							<table style=\"width:100%;font-size:0.8em;border:1px solid black;\" cellpadding=0 cellspacing=0>
								<tr>
									<td colspan=\"2\" align=\"center\">(For POEA,OWWA,Philhealth Use Only)</td>
								</tr>
								<tr>
									<td width=\"50%\">CG No:</td>
									<td style=\"border-bottom:1px solid black;\">&nbsp;</td>
								</tr>
								<tr>
									<td>RPS No:</td>
									<td style=\"border-bottom:1px solid black;\">&nbsp;</td>
								</tr>
								<tr>
									<td>Assessment No:</td>
									<td style=\"border-bottom:1px solid black;\">&nbsp;</td>
								</tr>
								<tr>
									<td colspan=\"2\" align=\"left\">Assessment Amount:</td>
								</tr>
								<tr>
									<td align=\"right\">POEA:</td>
									<td style=\"border-bottom:1px solid black;\">&nbsp;</td>
								</tr>
								<tr>
									<td align=\"right\">OWWA:</td>
									<td style=\"border-bottom:1px solid black;\">&nbsp;</td>
								</tr>
								<tr>
									<td align=\"right\">PHILHEALTH:</td>
									<td style=\"border-bottom:1px solid black;\">&nbsp;</td>
								</tr>
								<tr><td>&nbsp;</td></tr>
							</table>
						
						</td>
						
					</tr>
<!--					<tr><td colspan=\"3\">&nbsp;</td></tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align=\"right\">
							<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Change/s (if any)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u> <br />
							(for balik-manggagawa only)
						</td>
					</tr>	
!-->					
				</table>
				<br />
				
				<table style=\"width:100%;$fontsize3\" cellpadding=\"2\" cellspacing=\"1\">
					<tr>
						<td align=\"left\" $stylesection>I. PERSONAL DATA</td>
					</tr>
				</table>
				<table style=\"width:100%;$fontsize3\" cellpadding=\"2\" cellspacing=\"1\">
					<tr>
						<td width=\"10%\">Name:</td>
						<td width=\"30%\" align=\"center\" $styleborder $styledata>$crewfname&nbsp;</td>
						<td width=\"30%\" align=\"center\" $styleborder $styledata>$crewgname&nbsp;</td>
						<td width=\"30%\" align=\"center\" $styleborder $styledata>$crewmname&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td align=\"center\">Family Name(Apelyido)</td>
						<td align=\"center\">First Name(Pangalan)</td>
						<td align=\"center\">Middle Name(Gitnang Apelyido)</td>
					</tr>
				</table>
				
				<table style=\"width:100%;$fontsize3\" cellpadding=\"2\" cellspacing=\"1\">
					<tr>
						<td width=\"25%\" align=\"left\">Address in the Philippines(Tirahan):</td>
						<td align=\"left\" $styleborder $styledata>$crewaddress</td>
					</tr>
				</table>
				
				<table style=\"width:100%;$fontsize3\" cellpadding=\"2\" cellspacing=\"1\">
					<tr>
						<td width=\"12%\" align=\"left\">Telephone No:</td>
						<td width=\"20%\" align=\"left\" $styleborder $styledata>$crewtelno1&nbsp;</td>
						<td width=\"10%\" align=\"left\">Cellphone No:</td>
						<td width=\"20%\" align=\"left\" $styleborder $styledata>$crewmobile&nbsp;</td>
						<td width=\"5%\" align=\"left\">Email:</td>
						<td width=\"28%\" align=\"left\" $styleborder $styledata>$crewemail&nbsp;</td>
					</tr>
				</table>
				
				<table style=\"width:49%;float:left;$fontsize3\" cellpadding=\"2\" cellspacing=\"1\">
					<tr>
						<td>Date of Birth</td>
						<td $styleborder $styledata>$crewbdate&nbsp;</td>
					</tr>
					<tr>
						<td>Place of Birth</td>
						<td $styleborder $styledata>$crewbplace&nbsp;</td>
					</tr>
					<tr>
						<td>Passport No.</td>
						<td $styleborder $styledata>$xpassportno&nbsp;</td>
					</tr>
				</table>
				
				<table style=\"width:49%;float:right;$fontsize3\" cellpadding=\"2\" cellspacing=\"1\">
					<tr>
						<td>Gender</td>
						<td $styleborder $styledata>$crewgender&nbsp;</td>
					</tr>
					<tr>
						<td>Civil Status</td>
						<td $styleborder $styledata>$crewcivilstatus&nbsp;</td>
					</tr>
					<tr>
						<td>Educational Attainment</td>
						<td $styleborder $styledata>&nbsp;$crewcourse</td>
					</tr>
				</table>
				
				<table style=\"width:100%;$fontsize3\" cellpadding=\"2\" cellspacing=\"1\">
					<tr>
						<td width=\"20%\">Name of Spouse (if married):</td>
						<td width=\"30%\" $styleborder $styledata>$wifename&nbsp;</td>
						<td width=\"20%\">Mother's Full Maiden Name:</td>
						<td width=\"30%\" $styleborder $styledata>$mothername&nbsp;</td>
					</tr>
				</table>
				
				<span style=\"$fontsize3\">Legal Beneficiaries (Mga tatanggap ng benepisyo mula sa OWWA)</span> <br />
				
				$familycontent
				
				<span style=\"$fontsize3\">Allottee (Itinalaga ng padadalhan ng bahagi ng sahod ng OFW/Seafarer)</span> <br />
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<span style=\"width:100%;$fontsize3\">$allottee</span> <br />
				
				<span style=\"$fontsize3\">Legal Dependents (Mga tatanggap ng benepisyo mula sa Philhealth):</span> <br />
				
				$dependentcontent <br />
				
				<table style=\"width:80%;float:left;$fontsize3\" cellpadding=\"2\" cellspacing=\"1\">
					<tr>
						<td align=\"left\" $stylesection>II. CONTRACT DETAILS</td>
						<td>&nbsp;</td>
					</tr>
				</table>
				<table style=\"width:19%;float:right;$fontsize3\" cellpadding=\"2\" cellspacing=\"1\">
					<tr>
						<td>Changes (if any) <br /> (for balik-manggagawa)</td>
					</tr>
					<tr>
						<td $styleborder>&nbsp;</td>
					</tr>
					<tr>
						<td $styleborder>&nbsp;</td>
					</tr>
					<tr>
						<td $styleborder>&nbsp;</td>
					</tr>
					<tr>
						<td $styleborder>&nbsp;</td>
					</tr>
					<tr>
						<td $styleborder>&nbsp;</td>
					</tr>
					<tr>
						<td $styleborder>&nbsp;</td>
					</tr>
					<tr>
						<td $styleborder>&nbsp;</td>
					</tr>
					<tr>
						<td $styleborder>&nbsp;</td>
					</tr>
					<tr>
						<td $styleborder>&nbsp;</td>
					</tr>
				</table>
				
				<table style=\"width:80%;float:left;$fontsize3\" cellpadding=\"2\" cellspacing=\"1\">
					<tr>
						<td width=\"32%\">Name of Principal/Company/Employer</td>
						<td $styleborder>&nbsp;$principal</td>
					</tr>
					<tr>
						<td>Address:</td>
						<td $styleborder>&nbsp;</td>
					</tr>
				</table>
				
				<table style=\"width:80%;float:left;\" cellpadding=\"2\" cellspacing=\"1\">
					<tr>
						<td>
							<table style=\"width:49%;float:left;$fontsize3\" cellpadding=\"2\" cellspacing=\"1\">
								<tr>
									<td width=\"30%\">NAME OF VESSEL:</td>
									<td $styleborder $styledata>$vessellineup&nbsp;</td>
								</tr>
								<tr>
									<td>Position of OFW/Seafarer:</td>
									<td $styleborder $styledata>$rankfull&nbsp;</td>
								</tr>
								<tr>
									<td>Monthly Salary:</td>
									<td $styleborder $styledata>&nbsp&nbsp$basicsalary</td>
								</tr>
							</table>
							
							<table style=\"width:49%;float:right;$fontsize3\" cellpadding=\"2\" cellspacing=\"1\">
								<tr>
									<td>E-mail:</td>
									<td $styleborder $styledata>vmcgroup@veritas.com.ph</td>
								</tr>
								<tr>
									<td>Tel. No.:</td>
									<td $styleborder $styledata>(632)524-2116/524-1691</td>
								</tr>
								<tr>
									<td>Contract Duration:</td>
									<td $styleborder $styledata>&nbsp;&nbsp;&nbsp;&nbsp;$contractterm&nbsp;&nbsp;month(s)</td>
								</tr>
								<tr>
									<td>Currency:</td>
									<td $styleborder $styledata>US Dollar - $</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				
				<table style=\"width:80%;float:left;$fontsize3\" cellpadding=\"2\" cellspacing=\"1\">
					<tr>
						<td width=\"60%\">Last Date of Arrival in the Phils as OFW balik-manggagawa/seafarer:</td>
						<td $styleborder $styledata>&nbsp;$lastarrived</td>
					</tr>
					<tr>
						<td>Date of scheduled departure/return of balik-manggagawa to the jobsite:</td>
						<td $styleborder $styledata>&nbsp;$joiningmonth</td>
					</tr>
					<tr>
						<td>Name of Philippine Recruitment/Manning Agency (if applicable):</td>
						<td $styleborder $styledata>VERITAS MARITIME CORPORATION</td>
					</tr>
				</table>
				<br />
				
				<table width=\"100%\">
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td style=\"font-size:0.7em;font-weight:Bold;\" align=\"center\">
							I hereby certify that the above statements are true and correct and further declare that the above named dependents have<br />
							not been declared by my spouse/brother/sister. (Ako ay nagpapatunay na ang nasa itaas na pahayag ay totoo at tama at<br />
							dagdag kong inihahayag na ang nasabing makikinabang sa itaas ay hindi ng aking asawa o kapatid).<br />
						</td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td style=\"font-size:0.7em;font-weight:Bold;\" align=\"center\">
							_____________________________________________ <br />
							Signature over Printed Name
						</td>
					</tr>
				</table>

		
				
				
			</div>
			
			
			
			
		</div>
		";
	break;
	
	case "4"	: //RPS
	
		echo "
		<div style=\"margin:5 5 5 10;width:900px;height:600px;\"> 
		<div style=\"width:100%;height:50px;background-color:White;\">
			<div style=\"width:80%;height:30px;float:right;background-color:White;\">
				<center>
					<span style=\"font-size:0.8em;font-weight:Bold;\">
						PHILIPPINE OVERSEAS EMPLOYMENT ADMINISTRATION <br />
						Department of Labor and Employment <br />
						Republic of the Philippines
					</span>
				</center>
			</div>
			
			<div style=\"width:16%;float:left;background-color:White;\">
				<span style=\"font-size:0.6em;font-weight:Bold;\">FM-POEA-03-SB-08 ( )<br />
					<nobr>Effectivity Date:08 December 2003</nobr></span>
			</div>
			<br /><br /><br /><br />
			<div style=\"width:70%;height:20px;float:left;background-color:White;\">
				<span style=\"font-size:0.8em;font-weight:Bold;\">
					RPS - REQUEST FOR AMENDMENT FORM
				</span>
			</div>
			
			<div style=\"width:15%;float:left;background-color:White;text-align:right;\">
				<span style=\"font-size:0.6em;font-weight:Bold;\">RPS Amendment No:<br />
					<nobr>Date Received:</nobr></span>
			</div>
		</div>


		";
		
		$stylefont = "style=\"font-size:0.8em;\"";
		$styleborderbody = "style=\"border-top:1px solid Black;border-left:1px solid Black;\"";
		$styleborderdtl = "style=\"border-right:1px solid Black;border-bottom:1px solid Black;\"";
		$styledata = "style=\"font-weight:Bold;color:Black;\"";
		$styleencode = "style=\"color:Red;font-weight:Bold;\"";
		$fontsize3 = "font-size:0.9em;";
		$styleborderbottom="style=\"border-bottom:1px solid Black;\"";
		
		echo "
		
			<table width=\"100%\" style=\"$fontsize3\" $styleborderbody cellpadding=\"5\" cellspacing=\"0\">
				<tr height=\"30px\">
					<td $styleborderdtl width=\"60%\" $stylefont>NAME OF AGENCY: <b>VERITAS MARITIME CORPORATION</b></td>
					<td $styleborderdtl rowspan=\"3\" $stylefont>
						<center>
						<table style=\"font-size:1em;\">
							<tr>
								<td>[ ] Name/Spelling</td>
								<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
								<td>[ ] Transfer of Vessel</td>
							</tr>
							<tr>
								<td>[ ] Position</td>
								<td>&nbsp;</td>
								<td>[ ] Salary</td>
							</tr>
							<tr>
								<td>[ ] Contract Duration</td>
								<td>&nbsp;</td>
								<td>[ ] Re-issuance</td>
							</tr>
							<tr>
								<td>[ ] Contract Continuation</td>
								<td>&nbsp;</td>
								<td>[ ] Change Principal</td>
							</tr>
						</table>
						</center>
					</td>
				</tr>
				<tr height=\"30px\">
					<td $styleborderdtl $stylefont>NAME OF ACCREDITED PRINCIPAL: <b>$principal</b></td>
				</tr>
				<tr height=\"30px\">
					<td $styleborderdtl $stylefont>NAME OF VESSEL: <b>$vessellineup</b></td>
				</tr>
			</table>
			<br>
			<table width=\"100%\" style=\"$fontsize3\" $styleborderbody cellpadding=\"5\" cellspacing=\"0\">
				<tr height=\"40px\" style=\"text-align:center;\">
					<td $styleborderdtl $stylefont width=\"10%\">SRC NO.</td>
					<td $styleborderdtl $stylefont width=\"20%\">NAME OF SEAFARER</td>
					<td $styleborderdtl $stylefont width=\"10%\">SIRB#</td>
					<td $styleborderdtl $stylefont width=\"7%\">GENDER</td>
					<td $styleborderdtl $stylefont width=\"15%\">POSITION</td>
					<td $styleborderdtl $stylefont width=\"10%\">SALARY</td>
					<td $styleborderdtl $stylefont width=\"15%\"><nobr>CONTRACT DURATION</nobr> (Mo./Day)</td>
					<td $styleborderdtl $stylefont><nobr>ENGAGED SEAFARER/S</nobr> <nobr>RE-ENGAGED/CADET/PRV</nobr></td>
				</tr>
				<tr height=\"20px\" style=\"text-align:center;\">
					<td $styleborderdtl $stylefont><b>&nbsp;</b></td>
					<td $styleborderdtl $stylefont><nobr><b>$crewname</b></nobr></td>
					<td $styleborderdtl $stylefont><b>&nbsp;</b></td>
					<td $styleborderdtl $stylefont><b>$crewgender1</b></td>
					<td $styleborderdtl $stylefont><b>$rankfull</b></td>
					<td $styleborderdtl $stylefont><b>&nbsp;$basicsalary</b></td>
					<td $styleborderdtl $stylefont><b>$employperiod</b></td>
					<td $styleborderdtl $stylefont><b>&nbsp;</b></td>
				</tr>
			</table>
		<table width=\"100%\" style=\"$stylefont\" cellpadding=\"0\" cellspacing=\"0\">
			<tr height=\"60px\">
				<td width=\"30%\" $stylefont valign=\"top\">Submitted by:</td>
				<td>&nbsp;</td>
				<td width=\"30%\" $stylefont valign=\"top\">Requesting Party:</td>
			</tr>
			<tr>
				<td $stylefont $styleborderbottom>&nbsp;</td>
				<td>&nbsp;</td>
				<td $stylefont $styleborderbottom>&nbsp;</td>
			</tr>
			<tr>
				<td $stylefont align=\"center\">Name and Signature</td>
				<td>&nbsp;</td>
				<td $stylefont align=\"center\">Name and Signature</td>
			</tr>
			<tr height=\"30px\">
				<td $stylefont align=\"center\" valign=\"top\">(Liaison Officer)</td>
				<td>&nbsp;</td>
				<td $stylefont align=\"center\" valign=\"top\">(President / GM)</td>
			</tr>
		</table>

		<table width=\"100%\" $stylefont style=\"border-top:1px Dotted Black;border-bottom:1px Dotted Black;\" cellpadding=\"0\" cellspacing=\"0\">
			<tr>
				<td width=\"50%\" $stylefont style=\";border-right:1px Dotted Black;\">
					<table width=\"100%\">
						<tr $stylefont>
							<td><b>For POEA Use Only</b></td>
						</tr>
						<tr $stylefont align=\"center\">
							<td style=\"font-size:0.9em;\"><b><u>ORDER OF PAYMENT</u></b></td>
						</tr>
						<tr $stylefont>
							<td style=\"font-size:0.8em;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<b>Please accept from the above-named Agency the payment for:</b>
							</td>
						</tr>
						<tr $stylefont>
							<td style=\"font-size:0.8em;\">
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								[ ] POEA Processing Fee only
							</td>
						</tr>
						<tr $stylefont>
							<td style=\"font-size:0.8em;\">
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								[ ] POEA and OWWA Fees
							</td>
						</tr>
						<tr $stylefont>
							<td style=\"font-size:0.8em;\" align=\"right\">
								_______________________________
							</td>
						</tr>
						<tr $stylefont>
							<td style=\"font-size:0.8em;\" align=\"right\">
								POEA Evaluator
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;
							</td>
						</tr>
					</table>
				</td>
				<td $stylefont>
					<center>
					<table width=\"90%\">
						<tr $stylefont align=\"center\">
							<td colspan=\"3\" style=\"font-size:0.9em;\"><b><u>PAYMENT OF FEES</u></b></td>
						</tr>
						<tr $stylefont align=\"center\">
							<td style=\"font-size:0.8em;\"><b>POEA Processing Fee O.R. No:</b></td>
							<td style=\"font-size:0.8em;\"><b>&nbsp;&nbsp;&nbsp;</b></td>
							<td style=\"font-size:0.8em;\"><b>OWWA Processing Fee O.R. No:</b></td>
						</tr>
						<tr $stylefont align=\"center\">
							<td style=\"font-size:0.8em;border-bottom:1px Solid Black;\">&nbsp;</td>
							<td style=\"font-size:0.8em;\"><b>&nbsp;&nbsp;&nbsp;</b></td>
							<td style=\"font-size:0.8em;border-bottom:1px Solid Black;\">&nbsp;</td>
						</tr>
						<tr $stylefont align=\"center\" height=\"40px\">
							<td style=\"font-size:0.8em;border-bottom:1px Solid Black;\">&nbsp;</td>
							<td style=\"font-size:0.8em;\"><b>&nbsp;&nbsp;&nbsp;</b></td>
							<td style=\"font-size:0.8em;border-bottom:1px Solid Black;\">&nbsp;</td>
						</tr>
						<tr $stylefont align=\"center\">
							<td style=\"font-size:0.8em;\">POEA Cashier/Collecting Officer</td>
							<td style=\"font-size:0.8em;\"><b>&nbsp;&nbsp;&nbsp;</b></td>
							<td style=\"font-size:0.8em;\">OWWA Cashier/Collecting Officer</td>
						</tr>
					</table>
					</center>
				</td>
			</tr>
		</table>
	</div>
		";
	break;
	
	case "5"	: //TENTATIVE VESSEL LINEUP
	
		echo "
		<div style=\"margin:5 5 5 10;width:755px;height:600px;\">
		
			<div style=\"width:87%;height:60px;float:left;background-color:White;\">
				<center>
					<span style=\"font-size:1.2em;font-weight:Bold;\">
						VERITAS MARITIME CORPORATION <br />
						TENTATIVE VESSEL LINE-UP APPROVAL
					</span>
				</center>
			</div>
			
			<div style=\"width:12%;float:right;background-color:White;\">
				<span style=\"font-size:0.7em;font-weight:Bold;\">FM-212<br />REV. MAY 2008</span>
			</div>
			<br /><br /><br /><br />
			";
			
			$stylefont = "style=\"font-size:0.8em;\"";
			
			echo "
			<div style=\"width:100%;background-color:White;\">
			
				<table width=\"100%\" style=\"$fontsize3\" cellpadding=\"2\" cellspacing=\"2\">
					<tr>
						<td width=\"15%\" $stylefont>DATE</td>
						<td width=\"1%\" $stylefont>:</td>
						<td width=\"29%\" $stylefont $styledata $styleborder>$datenow</td>
						<td width=\"15%\" $stylefont>&nbsp;</td>
						<td width=\"1%\" $stylefont>&nbsp;</td>
						<td width=\"29%\" $stylefont>&nbsp;</td>
					</tr>
					<tr>
						<td $stylefont>NAME</td>
						<td $stylefont>:</td>
						<td $stylefont $styleborder $styledata>$crewname2</td>
						<td $stylefont>RANK</td>
						<td $stylefont>:</td>
						<td $stylefont $styleborder $styledata>$rankfull</td>
					</tr>
					<tr>
						<td $stylefont $styledata>[ ] EX-CREW/<br />EX-VESSEL</td>
						<td $stylefont>:</td>
						<td $stylefont $styleborder $styledata>$lastvessel</td>
						<td $stylefont $styledata colspan=\"3\">[ ] NEW HIRE &nbsp;&nbsp;[ ] VMC CADET PROGRAM</td>
					</tr>
					<tr>
						<td $stylefont>VESSEL</td>
						<td $stylefont>:</td>
						<td $stylefont $styleborder $styledata>$vessellineup</td>
						<td $stylefont>FLAG</td>
						<td $stylefont>:</td>
						<td $stylefont $styleborder $styledata>$flagcode &nbsp;</td>
					</tr>
					<tr>
						<td $stylefont>GRT</td>
						<td $stylefont>:</td>
						<td $stylefont $styleborder $styledata>$grosston</td>
						<td $stylefont>DWT</td>
						<td $stylefont>:</td>
						<td $stylefont $styleborder $styledata>$deadwt</td>
					</tr>
					<tr>
						<td $stylefont>TRADE ROUTE</td>
						<td $stylefont>:</td>
						<td $stylefont $styleborder $styledata>$traderoute</td>
						<td $stylefont>TYPE OF VESSEL</td>
						<td $stylefont>:</td>
						<td $stylefont $styleborder $styledata>$vesseltype</td>
					</tr>
					<tr>
						<td $stylefont>YEAR BUILT</td>
						<td $stylefont>:</td>
						<td $stylefont $styleborder $styledata>$builtyear</td>
						<td $stylefont>HORSEPOWER</td>
						<td $stylefont>:</td>
						<td $stylefont $styleborder $styledata>$enginepower &nbsp;&nbsp;/&nbsp;&nbsp;$rpm</td>
					</tr>
					<tr>
						<td $stylefont>ENGINE</td>
						<td $stylefont>:</td>
						<td $stylefont $styleborder $styledata>$enginemaker</td>
						<td $stylefont>TYPE</td>
						<td $stylefont>:</td>
						<td $stylefont $styleborder $styledata>$enginemaker $enginemain</td>
					</tr>
					<tr>
						<td $stylefont>EST. JOINING DATE</td>
						<td $stylefont>:</td>
						<td $stylefont $styleborder $styledata>$joiningmonth</td>
						<td $stylefont>JOINING PORT</td>
						<td $stylefont>:</td>
						<td $stylefont $styleborder $styledata>$port, $portcountry</td>
					</tr>
				</table>
			
				<table width=\"38%\" style=\"float:left;$fontsize3\" cellpadding=\"2\" cellspacing=\"2\">
					<tr><td colspan=\"3\">&nbsp;</td></tr>
					<tr><td colspan=\"3\"><u><b>SALARY BREAKDOWN (MONTHLY)</b></u></td></tr>
					<tr>
						<td $stylefont width=\"40%\">BASIC</td>
						<td width=\"1%\">:</td>
						<td width=\"59%\" $stylefont $styleborder $styledata align=\"right\">$basicsalary</td>
					</tr>
					<tr>
						<td $stylefont>FIXED O.T.</td>
						<td>:</td>
						<td $stylefont $styleborder $styledata align=\"right\">$fixedot</td>
					</tr>
					<tr>
						<td $stylefont>ALLOWANCE</td>
						<td>:</td>
						<td $stylefont $styleborder $styledata align=\"right\">$subsistence</td>
					</tr>
					<tr>
						<td $stylefont>V.L.P</td>
						<td>:</td>
						<td $stylefont $styleborder $styledata align=\"right\">$vlpamount</td>
					</tr>
					<tr>
						<td $stylefont>LENGTH OF CONTRACT</td>
						<td>:</td>
						<td $stylefont $styleborder $styledata align=\"right\">$contractperiod &nbsp;&nbsp;&nbsp;months</td>
					</tr>
				</table>
			
				<table width=\"55%\" style=\"float:right;$fontsize3\" cellpadding=\"2\" cellspacing=\"2\">
					<tr><td colspan=\"4\">&nbsp;</td></tr>
					<tr>
						<th $stylefont width=\"50%\">&nbsp;</th>
						<th width=\"5%\" $stylefont>Y</th>
						<th width=\"5%\" $stylefont>N</th>
						<th width=\"40%\" $stylefont>REMARKS</th>
					</tr>
					<tr>
						<td $stylefont>TECHNICAL ASSESSMENT</td>
						<td>[ ]</td>
						<td>[ ]</td>
						<td $stylefont $styleborder>&nbsp;</td>
					</tr>
					<tr>
						<td $stylefont>CREW EVALUATION REPORT</td>
						<td>[ ]</td>
						<td>[ ]</td>
						<td $stylefont $styleborder>&nbsp;</td>
					</tr>
					<tr>
						<td $stylefont>WATCHLIST</td>
						<td>[ ]</td>
						<td>[ ]</td>
						<td $stylefont $styleborder>&nbsp;</td>
					</tr>
					<tr>
						<td $stylefont>CHARACTER CHECK</td>
						<td>[ ]</td>
						<td>[ ]</td>
						<td $stylefont $styleborder>&nbsp;</td>
					</tr>
					<tr>
						<td $stylefont>CORRECTIVE TRAINING NEEDS</td>
						<td>[ ]</td>
						<td>[ ]</td>
						<td $stylefont $styleborder>&nbsp;</td>
					</tr>
				</table>
				<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
				";
				
				$stylesmall = "style=\"font-size:0.9em;font-weight:Bold;\"";
				
				echo "
				<center>
				<table width=\"90%\" cellpadding=\"2\" cellspacing=\"2\">
					<tr>
						<td $stylefont width=\"50%\">
							<table width=\"100%\">
								<tr>
									<td $stylefont>PREPARED BY:</td>
								</tr>
								<tr><td>&nbsp;</td></tr>
								<tr><td>&nbsp;</td></tr>
								<tr>
									<td $styleborder $styledata $stylefont >&nbsp;</td>
								</tr>
								<tr>
									<td $stylefont align=\"center\"><span $stylesmall>FLEET MANAGER / CREWING OFFICER</span></td>
								</tr>
							</table>
						
<!--						
							PREPARED BY: <br /><br /><br />
							______________________________________________ <br />
							<span $stylesmall>FLEET MANAGER / CREWING OFFICER</span>
							
!-->
						</td>
						<td width=\"50%\">
							<table width=\"100%\">
								<tr>
									<td $stylefont>CONFORM:</td>
								</tr>
								<tr><td>&nbsp;</td></tr>
								<tr><td>&nbsp;</td></tr>
								<tr>
									<td $styleborder $styledata $stylefont align=\"center\" >$crewname2</td>
								</tr>
								<tr>
									<td $stylefont align=\"center\"><span $stylesmall align=\"center\">(SEAFARER - SIGNATURE OVER PRINTED NAME)</span></td>
								</tr>
							</table>
							
						</td>
					</tr>
					<tr><td colspan=\"2\">&nbsp;</td></tr>
					<tr>
						<td colspan=\"2\" $stylefont>
							<center>
							RECOMMENDING APPROVAL: <br /><br /><br />
							_____________________________________________ <br />
							<span $stylesmall align=\"center\">OPERATIONS MANAGER</span>
							</center>
						</td>
					</tr>
					<tr><td colspan=\"2\">&nbsp;</td></tr>
					<tr>
						<td $stylefont>
							<table width=\"100%\">
								<tr>
									<td $stylefont>APPROVED:</td>
								</tr>
								<tr><td>&nbsp;</td></tr>
								<tr><td>&nbsp;</td></tr>
								<tr>
									<td $styleborder $styledata $stylefont align=\"center\">&nbsp;</td>
								</tr>
								<tr>
									<td $stylefont align=\"center\"><span $stylesmall>GENERAL MANAGER</span></td>
								</tr>
							</table>
						
						<!--
							<center>
							APPROVED: <br /><br /><br />
							_____________________________________________ <br />
							<span $stylesmall align=\"center\">GENERAL MANAGER</span>
							</center>
						!-->
						</td>
						<td $stylefont>
							<table width=\"100%\">
								<tr>
									<td $stylefont>CONFIRMED:</td>
								</tr>
								<tr><td>&nbsp;</td></tr>
								<tr><td>&nbsp;</td></tr>
								<tr>
									<td $styleborder $styledata $stylefont align=\"center\">CAPT. MAKOTO KONO</td>
								</tr>
								<tr>
									<td $stylefont align=\"center\"><span $stylesmall>PRINCIPAL'S REPRESENTATIVE</span></td>
								</tr>
							</table>
							
<!--							
							<center>
							CONFIRMED: <br /><br /><br />
							_____________________________________________ <br />
							<span $stylesmall align=\"center\">PRINCIPAL'S REPRESENTATIVE</span>
							</center>
!-->
						</td>
					</tr>
				</table>
				</center>
				
				<br /><br />
				
				<span style=\"font-size:0.8em;font-weight:Bold;\">
				INSTRUCTIONS: <br /><br />
				(1) THIS FORM SHOULD BE ACCOMPLISHED/PREPARED BY EITHER THE FLEET MANAGER OR THE CREWING OFFICER <br />
				(2) BEFORE PRESENTING TO THE CREW FOR SIGNATURE, IT SHOULD PASS THROUGH THE GENERAL MANAGER <br />
				FOR CONCURRENCE AND SIGNATURE.
				<br />
				
				</span>
				
				
			
			</div>
			
		</div>
		";
	break;
	
	
	case "9"	:  //Pre-Departure Checklist
	
		
	
		echo "
		<div style=\"margin:5 5 5 10;width:755px;height:600px;text-align:left;\">
		
			<div style=\"width:87%;height:40px;float:left;background-color:White;\">
				&nbsp;

				<span style=\"font-size:1em;font-weight:Bold;\">
					<center>
					PRE-DEPARTURE DOCUMENTS CHECK LIST FORM<br />
					</center>
				</span>
			</div>
			
			<div style=\"width:12%;float:right;background-color:White;\">
				<span style=\"font-size:0.7em;font-weight:Bold;\">FM-262<br />REV. FEB 2007</span>
			</div>
			<br /><br /><br /><br /><br />

			<center>
			<div style=\"width:100%;background-color:White;\">
			
					<table width=\"100%\" style=\"font-size:0.7em;\">
						<tr>
							<td width=\"10%\">NAME</td>
							<td width=\"5%\">:</td>
							<td width=\"25%\" style=\"border-bottom:1px solid black;font-weight:Bold;\">$crewname2 &nbsp;</td>
							<td width=\"20%\">&nbsp;</td>
							<td width=\"10%\">RANK</td>
							<td width=\"5%\">:</td>
							<td width=\"25%\" style=\"border-bottom:1px solid black;font-weight:Bold;\">$rankfull &nbsp;</td>
						</tr>
						<tr>
							<td>VESSEL</td>
							<td>:</td>
							<td style=\"border-bottom:1px solid black;font-weight:Bold;\">$vessellineup &nbsp;</td>
							<td>&nbsp;</td>
							<td>DEPARTURE</td>
							<td>:</td>
							<td style=\"border-bottom:1px solid black;font-weight:Bold;\">$joiningmonth &nbsp;</td>
						</tr>
						
					</table>
			
			</div>
			
			";
	
	
	
	break;
	case "10":
		
		if($naia=="NAIA1")
		{
			echo "
			<div style=\"margin:5 5 5 10;width:755px;height:1040px;text-align:left;\">
				
				<div style=\"width:100%;height:40px;float:left;background-color:White;text-align:center;\">
					&nbsp;
					<span style=\"font-size:1em;\">
						<center>
						<b>VERITAS MARITIME CORPORATION</b><br />
						CREW FINAL BRIEFING<br><br>
						<b>NAIA TERMINAL 1</b><br />
						(DEPARTURE LEVEL)
						</center>
					</span>
				</div>
				<div style=\"width:755px;height:706px;text-align:left;\">
					&nbsp;
					<img src=\"images/NAIA1.jpg\" width=\"100%\" height=\"100%\">
				</div><br>
				<div style=\"z-index:100;position:absolute;left:20px;top:145px;width:350px;height:230px;font-size:0.9em;\">
					<span><b>VESSEL</b> : <i>$vessellineup</i></span><br>
					<span><b>GROUP LEADER</b> : <i>$alias2 $crewname3</i></span><br>
					<span><b>NO. OF CREW</b> : &nbsp;<input type=\"text\" style=\"border:0;border-bottom:1px Solid Black;\" size=\"20\" 
						onkeydown=\"chkCR();\"></span><br>
					<span><b>DATE</b> : &nbsp;<input type=\"text\" style=\"border:0;border-bottom:1px Solid Black;\" size=\"30\" 
						onkeydown=\"chkCR();\"></span><br>
					<span><b>FLIGHT DETAILS</b> : &nbsp;<input type=\"text\" style=\"border:0;border-bottom:1px Solid Black;\" size=\"24\" 
						onkeydown=\"chkCR();\"></span><br>
					<span><b>CONNECTING FLIGHT</b> : &nbsp;<input type=\"text\" style=\"border:0;border-bottom:1px Solid Black;\" size=\"25\" 
						onkeydown=\"chkCR();\"></span><br>
					<span><b>HANDLING AGENT</b> :<br>
						<input type=\"text\" style=\"border:0;border-bottom:1px Solid Black;\" onkeydown=\"chkCR();\" size=\"23\"><br>
						<input type=\"text\" style=\"border:0;border-bottom:1px Solid Black;\" size=\"23\">
					</span>
				</div>
				<div style=\"width:100%;\">
					<hr>
					<div style=\"width:50%;float:left;\">
						<table width=\"100%\">
							<tr><td colspan=\"1\">NOTES:</td></tr>
							<tr>
								<td>&nbsp;</td>
								<td>From the meeting place (1) only the</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>passenger / crew can use the stairs in</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>going to the Departure Area (2)</td>
							</tr>
						</table>
					</div>
					<div style=\"width:49%;float:right;\">
						<table width=\"100%\">
							<tr><td colspan=\"4\">LETTER OF GUARANTEE (L/G)</td></tr>
							<tr>
								<td>1)</td>
								<td>Original</td>
								<td>-</td>
								<td>Airline Copy</td>
							</tr>
							<tr>
								<td>2)</td>
								<td>Duplicate</td>
								<td>-</td>
								<td>Immigration Copy</td>
							</tr>
							<tr>
								<td>3)</td>
								<td>Triplicate</td>
								<td>-</td>
								<td>Seaman's Copy</td>
							</tr>
						</table>
					</div>
				</div>
			</div>";
		}
		else if($naia=="NAIA2")
		{
			echo "
			<div style=\"margin:5 5 5 10;width:755px;height:1040px;text-align:left;\">
				<div style=\"width:100%;height:40px;float:left;background-color:White;text-align:center;\">
					&nbsp;
					<span style=\"font-size:1em;\">
						<center>
						<b>VERITAS MARITIME CORPORATION</b><br />
						CREW FINAL BRIEFING<br><br>
						<b>NAIA CENTENNIAL TERMINAL 2</b><br />
						(Departure Area)
						</center>
					</span>
				</div><br><br>
				<div style=\"width:100%;height:300px;\">
					<div style=\"width:464px;height:300px;float:left;\">
						<br><br>
						<span><b>VESSEL</b> : <i>$vessellineup</i></span><br>
						<span><b>GROUP LEADER</b> : <i>$alias2 $crewname3</i></span><br>
						<span><b>NO. OF CREW</b> : &nbsp;<input type=\"text\" style=\"border:0;border-bottom:1px Solid Black;\" size=\"40\" 
							onkeydown=\"chkCR();\"></span><br>
						<span><b>DATE</b> : &nbsp;<input type=\"text\" style=\"border:0;border-bottom:1px Solid Black;\" size=\"40\" 
							onkeydown=\"chkCR();\"></span><br>
						<span><b>FLIGHT DETAILS</b> : &nbsp;<input type=\"text\" style=\"border:0;border-bottom:1px Solid Black;\" size=\"47\" 
							onkeydown=\"chkCR();\"></span><br>
						<span><b>CONNECTING FLIGHT</b> : &nbsp;
							<input type=\"text\" style=\"border:0;border-bottom:1px Solid Black;\" size=\"40\" onkeydown=\"chkCR();\"><br>
							<input type=\"text\" style=\"border:0;border-bottom:1px Solid Black;\" size=\"72\" onkeydown=\"chkCR();\">
						</span><br>
						<span><b>HANDLING AGENT</b> :
							<input type=\"text\" style=\"border:0;border-bottom:1px Solid Black;\" size=\"46\" onkeydown=\"chkCR();\"><br>
							<input type=\"text\" style=\"border:0;border-bottom:1px Solid Black;\" onkeydown=\"chkCR();\" size=\"72\"><br>
							<input type=\"text\" style=\"border:0;border-bottom:1px Solid Black;\" size=\"72\">
						</span>
					</div>
					<div style=\"width:291px;height:274px;float:right;\">
						<img src=\"images/NAIA3.jpg\" width=\"100%\" height=\"100%\">
					</div>
				</div>
				<div style=\"width:755px;height:523px;\">
					<img src=\"images/NAIA2.jpg\" width=\"100%\" height=\"100%\">
				</div>
				<div style=\"width:100%;height:40px;\">
					<hr>
					<div style=\"width:100%;float:left;\">
						<table width=\"50%\">
							<tr><td colspan=\"4\">LETTER OF GUARANTEE (L/G)</td></tr>
							<tr>
								<td>1)</td>
								<td>Original</td>
								<td>-</td>
								<td>Airline Copy</td>
							</tr>
							<tr>
								<td>2)</td>
								<td>Duplicate</td>
								<td>-</td>
								<td>Immigration Copy</td>
							</tr>
							<tr>
								<td>3)</td>
								<td>Triplicate</td>
								<td>-</td>
								<td>Seaman's Copy</td>
							</tr>
						</table>
					</div>
				</div>
			</div>";
		}
	break;
	
	case "13"	:  //Issue Shoes (VMC Gears)
		require_once "PrintAnything/class.PrintAnything.php";
		$pa = new PrintAnything();
		
		$qrydtl=mysql_query("SELECT SIZESHOES,SIZECOVERALL,SIZERAINCOAT,SIZEUNIFORM,WAISTLINE FROM crew WHERE APPLICANTNO=$applicantno") or die(mysql_error());
		$rowdtl=mysql_fetch_array($qrydtl);
		$sizeshoes=$rowdtl["SIZESHOES"];
		$sizecoverall=$rowdtl["SIZECOVERALL"];
		$sizeraincoat=$rowdtl["SIZERAINCOAT"];
		$sizeuniform=$rowdtl["SIZEUNIFORM"];
		$waistline=$rowdtl["WAISTLINE"];
		
	
		$printshow = "
		<div style=\"margin:5 5 5 10;width:450px;height:100%;text-align:left;\">
		
			<div style=\"width:70%;height:40px;float:left;background-color:White;\">
				<span style=\"font-size:1em;font-weight:Bold;\">
					VMC GEARS REQUISITION FORM<br />
					PERSONAL GEARS (VOM-TNKC)
				</span>
			</div>
			<div style=\"width:20%;float:right;background-color:White;text-align:right\">
				<span style=\"font-size:0.7em;font-weight:Bold;text-align:right;\">Form AD-026<br />1-Sep-01</span>
			</div>
			<br /><br /><br /><br /><br />

			<div style=\"width:100%;background-color:White;\">
			
					<table style=\"font-size:1em;\">
						<tr>
							<td><b>Line-up Vessel</b></td>
							<td><b>:</b></td>
							<td>$vessellineup</td>
						</tr>
						<tr>
							<td><b>Name of Seaman</b></td>
							<td><b>:</b></td>
							<td>$crewname3</td>
						</tr>
						<tr>
							<td><b>Rank</b></td>
							<td><b>:</b></td>
							<td>$rankfull</td>
						</tr>
					</table>
					<br>
					<table style=\"font-size:1em;\">
						<tr>
							<td><b>Size of Shoes (Fixed Size)</b></td>
							<td><b>:</b></td>
							<td>$sizeshoes</td>
						</tr>
						<tr>
							<td><b>Size of Coverall (S/M/L/XL/XXL)</b></td>
							<td><b>:</b></td>
							<td>$sizecoverall</td>
						</tr>
						<tr>
							<td><b>Size of Parka Jacket (S/M/L/XL/XXL)</b></td>
							<td><b>:</b></td>
							<td>$sizeraincoat</td>
						</tr>
					</table>
					<br>
					<table style=\"font-size:1em;\">
						<tr>
							<td colspan=\"3\"><b>FOR OFFICERS (DECK/ENGINE) & STEWARDS ONLY</b></td>
						</tr>
					</table>
					<table style=\"font-size:1em;\">
						<tr>
							<td><b>Waistline</b></td>
							<td><b>:</b></td>
							<td>$waistline</td>
						</tr>
						<tr>
							<td><b>Size of Polo (S/M/L/XL/XXL)</b></td>
							<td><b>:</b></td>
							<td>$sizeuniform</td>
						</tr>
					</table>
			
			</div>
		</div>";
		echo "
		$printshow
		<div style=\"margin:5 5 5 10;width:450px;\">
			<center>";
				$con2 = $pa->addPrintContext($printshow);
				echo '<span>';
				$btnCss = '';
				$pa->showPrintButton($con2, 'Print', $btnCss);
				echo '</span>';
				echo "
			</center>
		</div>
		";
	break;
	
	case "16":
		$pa = new PrintAnything();
		
		$url = (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on' ? 'http://' : 'https://');
		$url .= $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
		$url = trim(str_replace('\\', '/', $url));
		if (substr($url, -1) != '/') {
		    $url .= '/';
		}
		
		//201 SHEET
		$url201sheet = $url.'rep201sheet.php?directprint=1&applicantno='.$applicantno;
		$print201sheet = PrintAnything::readBody($url201sheet);
		$add201sheet = $pa->addPrintContext($print201sheet);
		//DOC/LIC
		$urldoclic = $url.'repdoclic.php?directprint=1&applicantno='.$applicantno;
		$printdoclic = PrintAnything::readBody($urldoclic);
		$adddoclic = $pa->addPrintContext($printdoclic);
		//CERTIFICATE
		$urlcert = $url.'repcert.php?directprint=1&applicantno='.$applicantno;
		$printcert = PrintAnything::readBody($urlcert);
		$addcert = $pa->addPrintContext($printcert);
		//EXPRIENCE VMC 
		$urlexperience_vmc = $url.'repexperience_vmc.php?directprint=1&applicantno='.$applicantno;
		$printexperience_vmc = PrintAnything::readBody($urlexperience_vmc);
		$addexperience_vmc = $pa->addPrintContext($printexperience_vmc);
		//EXPRIENCE OUT
		$urlexperience_out = $url.'repexperience_out.php?directprint=1&applicantno='.$applicantno;
		$printexperience_out = PrintAnything::readBody($urlexperience_out);
		$addexperience_out = $pa->addPrintContext($printexperience_out);
		//TRAININGS
		$urltrainings = $url.'reptrainings.php?directprint=1&applicantno='.$applicantno;
		$printtrainings = PrintAnything::readBody($urltrainings);
		$addtrainings = $pa->addPrintContext($printtrainings);
		
		echo "
		<div style=\"width:100%;height:300px;overflow:hidden;z-index:200;\">
			<br>
			<center>";
				$btnCss='style="width:110px;border:2px solid black;background-color:red;color:yellow;font-weight:bold;"';
				echo "<font style=\"color:blue;font-weight:bold;\">Report 201</font><br><br>";
				$pa->showPrintButton($add201sheet, '201 Sheet', $btnCss);
				echo "<br><br>";
				$pa->showPrintButton($adddoclic, 'DOC/LIC', $btnCss);
				echo "<br><br>";
				$pa->showPrintButton($addcert, 'CERT', $btnCss);
				echo "<br><br>";
				$pa->showPrintButton($addexperience_vmc, 'EXP-VMC', $btnCss);
				echo "<br><br>";
				$pa->showPrintButton($addexperience_out, 'EXP-OUT', $btnCss);
				echo "<br><br>";
				$pa->showPrintButton($addtrainings, 'TRAININGS', $btnCss);
				echo "<br><br>";
			echo "
			</center>
		</div>";
	break;
	
	case "22"	:  //Update Shoes and Uniform Size (same as case 13 but editable)
		
		$pa = new PrintAnything();
		
		$qrydtl=mysql_query("SELECT SIZESHOES,SIZECOVERALL,SIZERAINCOAT,SIZEUNIFORM,WAISTLINE,WEIGHT,HEIGHT FROM crew WHERE APPLICANTNO=$applicantno") or die(mysql_error());
		$rowdtl=mysql_fetch_array($qrydtl);
		$sizeshoes=$rowdtl["SIZESHOES"];
		$sizecoverall=$rowdtl["SIZECOVERALL"];
		$sizeraincoat=$rowdtl["SIZERAINCOAT"];
		$sizeuniform=$rowdtl["SIZEUNIFORM"];
		$waistline=$rowdtl["WAISTLINE"];
		$weight=$rowdtl["WEIGHT"];
		$height=$rowdtl["HEIGHT"];
		
	
		$printshow = "
		<div style=\"margin:5 5 5 10;width:450px;height:80%;text-align:left;\">
		
			<div style=\"width:70%;height:40px;float:left;background-color:White;\">
				<span style=\"font-size:1em;font-weight:Bold;\">
					VMC GEARS REQUISITION FORM<br />
					PERSONAL GEARS (VOM-TNKC)
				</span>
			</div>
			<div style=\"width:20%;float:right;background-color:White;text-align:right\">
				<span style=\"font-size:0.7em;font-weight:Bold;text-align:right;\">Form AD-026<br />1-Sep-01</span>
			</div>
			<br /><br /><br /><br />

			<div style=\"width:100%;background-color:White;\">
			
				<table style=\"font-size:1em;\">
					<tr>
						<td><b>Line-up Vessel</b></td>
						<td><b>:</b></td>
						<td>$vessellineup</td>
					</tr>
					<tr>
						<td><b>Name of Seaman</b></td>
						<td><b>:</b></td>
						<td>$crewname3</td>
					</tr>
					<tr>
						<td><b>Rank</b></td>
						<td><b>:</b></td>
						<td>$rankfull</td>
					</tr>
				</table>
				<br>
				<table style=\"font-size:1em;\">
					<tr>
						<td><b>Size of Shoes (Fixed Size)</b></td>
						<td><b>:</b></td>
						<td><input type=\"text\" name=\"sizeshoes\" value=\"$sizeshoes\" size=\"8\"
							style=\"border:0;border-bottom:1px Solid Black;text-align:center;\"></td>
					</tr>
					<tr>
						<td><b>Size of Coverall (S/M/L/XL/XXL)</b></td>
						<td><b>:</b></td>
						<td><input type=\"text\" name=\"sizecoverall\" value=\"$sizecoverall\" size=\"8\"
							style=\"border:0;border-bottom:1px Solid Black;text-align:center;\"></td>
					</tr>
					<tr>
						<td><b>Size of Parka Jacket (S/M/L/XL/XXL)</b></td>
						<td><b>:</b></td>
						<td><input type=\"text\" name=\"sizeraincoat\" value=\"$sizeraincoat\" size=\"8\"
							style=\"border:0;border-bottom:1px Solid Black;text-align:center;\"></td>
					</tr>
				</table>
				<br>
				<table style=\"font-size:1em;\">
					<tr>
						<td colspan=\"3\"><b>FOR OFFICERS (DECK/ENGINE) & STEWARDS ONLY</b></td>
					</tr>
				</table>
				<table style=\"font-size:1em;\">
					<tr>
						<td><b>Waistline</b></td>
						<td><b>:</b></td>
						<td><input type=\"text\" name=\"waistline\" value=\"$waistline\" size=\"8\"
							style=\"border:0;border-bottom:1px Solid Black;text-align:center;\"></td>
					</tr>
					<tr>
						<td><b>Size of Polo (S/M/L/XL/XXL)</b></td>
						<td><b>:</b></td>
						<td><input type=\"text\" name=\"sizeuniform\" value=\"$sizeuniform\" size=\"8\"
							style=\"border:0;border-bottom:1px Solid Black;text-align:center;\"></td>
					</tr>
				</table><br>
			</div>
		</div>";
		echo "
		$printshow
		<div style=\"margin:5 5 5 10;width:450px;\">
			<table style=\"font-size:1em;\">
				<tr>
					<td colspan=\"3\"><b>Height & Weight <font style=\"color:red;\"><i>(not shown in print-out)</i></font></b></td>
				</tr>
			</table>
			<table style=\"font-size:1em;\">
				<tr>
					<td><b>Height (cm)</b></td>
					<td><b>:</b></td>
					<td><input type=\"text\" name=\"height\" value=\"$height\" size=\"8\"
						style=\"border:0;border-bottom:1px Solid Black;text-align:center;\"></td>
				</tr>
				<tr>
					<td><b>Weight (kg)</b></td>
					<td><b>:</b></td>
					<td><input type=\"text\" name=\"weight\" value=\"$weight\" size=\"8\"
						style=\"border:0;border-bottom:1px Solid Black;text-align:center;\"></td>
				</tr>
			</table><br>
			<center>
				<input type=\"button\" value=\"Save\" onclick=\"actiontxt.value='updateshoes';submit();\">&nbsp;";
				$con2 = $pa->addPrintContext($printshow);
				echo '<span>';
				$btnCss = '';
				$pa->showPrintButton($con2, 'Print', $btnCss);
				echo '</span>';
				echo "
				<input type=\"button\" value=\"Close\" onclick=\"window.close();\">
			</center>
		</div>
		";
		
	
	
	break;
	
	case "23"	:  // Addendum to Employment Contract
	
		echo "
		<div style=\"margin:5 5 5 10;width:755px;height:600px;\">
		
			<div style=\"width:100%;height:60px;float:left;background-color:White;\">
				<center>
					<span style=\"font-size:1.2em;font-weight:Bold;\">
						ADDENDUM TO <br />
						EMPLOYMENT CONTRACT
					</span>
				</center>
			</div>
			<br /><br /><br /><br />
			";
			
			$stylefont = "style=\"font-size:0.8em;\"";
			
			echo "
			<center>
			<div style=\"width:90%;background-color:White;text-align:left;\">
			It is hereby mutually agreed between VERITAS MARITIME CORPORATION, Acting as <br />
			Agents for and in behalf of Kobe Kisen Kaisha, Ltd., Tokyo, Japan. <br/>
			<br />
			<center>and</center> 
			<br />
			<span style=\"border-bottom:1px solid black;font-weight:Bold;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$crewname2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>, with Seaman's Book Number 
			<span style=\"border-bottom:1px solid black;font-weight:Bold;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$xseabookno&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>,  joining the vessel M.V. <span style=\"border-bottom:1px solid black;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$vessellineup&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
			in the capacity of <span style=\"border-bottom:1px solid black;font-weight:Bold;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$rankfull&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> do hereby states as follows: <br />
			<br /><br />
			

				<center>
				<br />
				<table width=\"90%\">
					<tr>
						<td colspan=\"4\">
							I. That on addition to the list under the POEA Table of offenses and Corresponding<br />
								&nbsp;&nbsp;&nbsp; Administrative Penalties (Appendix 2) we hereby agree to include the following:
						</td>
					</tr>
					<tr>
						<td colspan=\"4\">&nbsp;</td>
					</tr>
					<tr>
						<th width=\"5%\" valign=\"top\">&nbsp;</th>
						<th width=\"45%\">OFFENSES</th>
						<th width=\"5%\">&nbsp;</th>
						<th width=\"45%\">PENALTY</th>
					</tr>
					<tr>
						<td colspan=\"2\">Willful Violation of Proper Handling Procedures and Regulations on Pure Car Carriers (PCC), whether causing damage or not to vehicles, including but not limited to:</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td colspan=\"4\">&nbsp;</td>
					</tr>
					<tr>
						<td valign=\"top\">a)</td>
						<td>Unauthorized driving of any vehicle cargo either at the terminal or on board:</td>
						<td>&nbsp;</td>
						<td>Dismissal and to pay cost</td>
					</tr>
					<tr>
						<td valign=\"top\">b)</td>
						<td valign=\"top\">Unauthorized use of any vehicle parts</td>
						<td>&nbsp;</td>
						<td>Dismissal and to pay Reprimand<br /><br />
							2nd OFFENSE-Dismissal and to pay cost</td>
					</tr>
					<tr>
						<td valign=\"top\">c)</td>
						<td>Theft of any vehicle parts</td>
						<td>&nbsp;</td>
						<td>Dismissal and to pay cost</td> 
						</td>
					</tr>
					<tr>
						<td valign=\"top\">d)</td>
						<td>Any other prohibited acts which caused damage to the vehicle</td>
						<td>&nbsp;</td>
						<td>Dismissal and to pay cost</td> 
					</tr>
					<tr>
						<td colspan=\"4\">&nbsp;</td>
					</tr>
					<tr>
						<td colspan=\"4\">II. The parties mutually agree that the above shall form part of the POEA approved Contract
											&nbsp;&nbsp;&nbsp;of Employment and the Collective Bargaining Agreement 
						</td>
					</tr>
					<tr>
						<td colspan=\"4\">&nbsp;</td>
					</tr>
					<tr>
						<td colspan=\"4\">Signed this day of <span style=\"border-bottom:1px solid black;font-weight:Bold;\">&nbsp;&nbsp;&nbsp;$datenow&nbsp;&nbsp;&nbsp;</span>  in Manila, Philippines. </td>
					</tr>
				</table>
				</center>
			
			
			<br /><br />
			<br />
			
			<center>
				<table width=\"90%\">
					<tr>
						<th colspan=\"2\" align=\"center\">VERITAS MARITIME CORPORATION</th>
						<th>&nbsp;</th>
						<th colspan=\"2\" align=\"center\">SEAMAN</th>
					</tr>
					<tr>
						<td colspan=\"4\">&nbsp;</td>
					</tr>
					<tr>
						<td width=\"10%\">By:</td>
						<td width=\"40%\" style=\"border-bottom:1px solid black;\">&nbsp;</td>
						<td width=\"5%\"></td>
						<td width=\"10%\">Witness:</td>
						<td width=\"40%\" style=\"border-bottom:1px solid black;\">&nbsp;</td>
					</tr>
					<tr>
						<td colspan=\"4\">&nbsp;</td>
					</tr>
					<tr>
						<td width=\"10%\">&nbsp;</td>
						<td width=\"40%\" style=\"border-bottom:1px solid black;\">&nbsp;</td>
						<td width=\"5%\"></td>
						<td width=\"10%\">&nbsp;</td>
						<td width=\"40%\" style=\"border-bottom:1px solid black;\">&nbsp;</td>
					</tr>
				</table>
			</center>
				
			</div>
			</center>
			

			
			";
	
	
	break;
	
	case "24"	:  //Affidavit  
	
		echo "
		<div style=\"margin:5 5 5 10;width:755px;height:600px;\">
		
			<div style=\"width:87%;height:60px;float:left;background-color:White;\">
				&nbsp;
			</div>
			
			<div style=\"width:12%;float:right;background-color:White;\">
				<span style=\"font-size:0.7em;font-weight:Bold;\">FM-215<br />OCT94</span>
			</div>
			<br /><br /><br />

			<center>
				<span style=\"font-size:1.2em;font-weight:Bold;\">
					AFFIDAVIT
				</span>
			</center>
			<br /><br />
			";
			
			$stylefont = "style=\"font-size:0.8em;\"";
			
			echo "
			<center>
			<div style=\"width:80%;text-align:left;\">
			
			KNOW ALL MEN BY THESE PRESENTS: <br /><br /><br /><br />
			I, <span style=\"border-bottom:1px solid black;font-weight:Bold;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$crewname2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>, with Seaman's Book Number 
			<span style=\"border-bottom:1px solid black;font-weight:Bold;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$xseabookno&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>,  joining the vessel M.V. <span style=\"border-bottom:1px solid black;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$vessellineup&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
			in the capacity of <span style=\"border-bottom:1px solid black;font-weight:Bold;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$rankfull&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> do hereby states as follows: <br />
			<br />
			
			<center>
			<table style=\"font-size:1em;width:80%;\">
				<tr>
					<td width=\"5%\" valign=\"top\">a)</td>
					<td width=\"3%\">&nbsp;</td>
					<td width=\"90%\" valign=\"top\">I have been fully debriefed, understood and shall fully abide by the Company policies and guidelines on the use of alcoholic beverages on board vessels and while on shore liberty.</td>
				</tr>
				<tr>
					<td width=\"5%\" valign=\"top\">b)</td>
					<td width=\"3%\">&nbsp;</td>
					<td width=\"90%\" valign=\"top\">I am fully aware of the administrative penalties on drunkness as provided for under Section 6 of \"POEA Table of Offenses and Corresponding Administrative Penalties\" and/or as provided for in the covering Collective Bargaining Agreement;</td>
				</tr>
				<tr>
					<td width=\"5%\" valign=\"top\">c)</td>
					<td width=\"3%\">&nbsp;</td>
					<td width=\"90%\" valign=\"top\">I have been fully briefed, understood and shall abide by the Company policies and regulations on shore liberty as provided for in the Veritas Manual for Seafarers and/or as may be required by the Master;</td>
				</tr>
				<tr>
					<td width=\"5%\" valign=\"top\">d)</td>
					<td width=\"3%\">&nbsp;</td>
					<td width=\"90%\" valign=\"top\">I have never been involved in any alcohol related incident on board my previous vessel/s and while on shore liberty;</td>
				</tr>
				<tr>
					<td width=\"5%\" valign=\"top\">e)</td>
					<td width=\"3%\">&nbsp;</td>
					<td width=\"90%\" valign=\"top\">I fully understand that any involvement in any serious alcohol related incident on board and / or during shore liberty will result in serious sanctions and / or dismissal from service with the corresponding cost charged to my personal account;</td>
				</tr>
				<tr>
					<td width=\"5%\" valign=\"top\">f)</td>
					<td width=\"3%\">&nbsp;</td>
					<td width=\"90%\" valign=\"top\">I hereby authorize Veritas Maritime Corporation to deduct from any money due me all costs reulting from my dismissal and / ar damages incurred to vessel or third party.</td>
				</tr>
			</table>
			</center>
			<br /><br />
			
			IN WITNESS WHEREOF, I have hereunto affix my signature this <span style=\"border-bottom:1px solid black;font-weight:Bold;\">&nbsp;&nbsp;&nbsp;$datenow&nbsp;&nbsp;&nbsp;</span>
			<br /><br /><br /><br /><br />
			
			<table width=\"40%\">
				<tr>
					<td style=\"border-bottom:1px solid black;font-weight:Bold;><span font-weight:Bold;\">&nbsp;&nbsp;&nbsp;$crewname2&nbsp;&nbsp;&nbsp;</span></td>
				</tr>
				<tr><td align=\"center\">AFFIANT</td></tr>
			</table>
			
			</div>
			</center>
			";
	
	break;
	
	case "25"	:  //Anti Drug Abuse Affidavit 
	
		echo "
		<div style=\"margin:5 5 5 10;width:755px;height:600px;text-align:left;\">
		
			<div style=\"width:87%;height:40px;float:left;background-color:White;\">
				&nbsp;

				<span style=\"font-size:1.2em;font-weight:Bold;\">
					<center>
					VERITAS MARITIME CORPORATION<br />
					<span style=\"font-size:0.9em;\">MANILA, PHILIPPINES</span>
					</center>
				</span>
			</div>
			
			<div style=\"width:12%;float:right;background-color:White;\">
				<span style=\"font-size:0.7em;font-weight:Bold;\">FM-214<br />OCT94</span>
			</div>
			<br /><br /><br /><br /><br />

			";
			
			$stylefont = "style=\"font-size:0.8em;\"";
			
			echo "
			<center>
			<div style=\"width:90%;background-color:White;\">
			
				<center>
					<table width=\"80%\" cellpadding=0 cellspacing=0>
						<tr>
							<td width=\"500px\" align=\"center\" valign=\"middle\"><span style=\"font-weight:Bold;font-size:0.9em;\">ANTI-DRUG ABUSE AFFIDAVIT</span></td>
							<td width=\"5%\">&nbsp;</td>
							<td width=\"150px\" style=\"border:1px solid black;height:150px;\">
							";
								$basedir = "idpics/";
									$dirfilename = "$basedir/$applicantno.JPG";
									if (checkpath($dirfilename))
									{
										$scale = imageScale($dirfilename,-1,130);
										$width = $scale[0];
										$height = $scale[1];
										
							echo "			<center><img src=\"$dirfilename\" width=\"$width\" height=\"$height\" /></center> ";
									}
									else 
									{
							echo "			<center><b>[NO PICTURE]</b></center>";
									}
							echo "
							</td>
						</tr>
					</table>
				</center>
				<br />
				
				<center>
				<div width=\"90%\" style=\"text-align:left;\">
					<table width=\"100%\" style=\"font-size:0.8em;\">
						<tr>
							<td width=\"15%\">NAME</td>
							<td width=\"5%\">:</td>
							<td width=\"80%\" style=\"border-bottom:1px solid black;font-weight:Bold;\">$crewname2</td>
						</tr>
						<tr>
							<td valign=\"top\">ADDRESS</td>
							<td valign=\"top\">:</td>
							<td style=\"border-bottom:1px solid black;font-weight:Bold;\">$crewaddress &nbsp;</td>
						</tr>
					</table>
					<br /><br />
					
					<table width=\"80%\" style=\"font-size:0.8em;\">
						<tr>
							<td width=\"20%\">SEAMAN'S BOOK</td>
							<td width=\"5%\">:</td>
							<td width=\"20%\" style=\"border-bottom:1px solid black;font-weight:Bold;\">$xseabookno &nbsp;</td>
							<td width=\"5%\">&nbsp;</td>
							<td width=\"15%\">VALIDITY</td>
							<td width=\"5%\">:</td>
							<td width=\"25%\" style=\"border-bottom:1px solid black;font-weight:Bold;\">$xseabooknoexpiry</td>
						</tr>
						<tr>
							<td>PASSPORT NO.</td>
							<td>:</td>
							<td style=\"border-bottom:1px solid black;font-weight:Bold;\">$xpassportno &nbsp;</td>
							<td>&nbsp;</td>
							<td>VALIDITY</td>
							<td>:</td>
							<td style=\"border-bottom:1px solid black;font-weight:Bold;\">$xpassportnoexpiry</td>
						</tr>
						
					</table>
					<br /><br />
					
					I, <span style=\"border-bottom:1px solid black;font-weight:Bold;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$crewname2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> do hereby truthfully state I have not at any time presently or past willfully or otherwise consumed, traded, trafficked or otherwise handled, or have had the knowledge or illegal drugs.
					
					<br /><br /><br />
					Signed: ________________________________________ &nbsp;&nbsp;&nbsp;Date: <span style=\"border-bottom:1px solid black;font-weight:Bold;\">&nbsp;&nbsp;&nbsp;$datenow&nbsp;&nbsp;&nbsp;</span>
					
					<br /><br /><br />
					
					NB: This form is to be filled in, signed and returned to VERITAS MARITIME CORPORATION for every seaman for any and all ranks whether or not officer or rating.
				</div>
				</center>
			</div>
			</center>
			";
	
	
	break;
}
	
echo "
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"applicantno\" value=\"$applicantno\"/>
	<input type=\"hidden\" name=\"ccid\" value=\"$ccid\"/>
	<input type=\"hidden\" name=\"type\" value=\"$type\"/>
</form>";

if ($print == 1)
	include('include/printclose.inc');

echo "
</body>

</html>

";

?>