<?php

include("veritas/connectdb.php");
include("veritas/include/stylephp.inc");
include("veritas/include/functions.inc");

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
	
if (isset($_POST["applicantno"]))
	$applicantno = $_POST["applicantno"];

if (empty($applicantno))
{
	if (isset($_GET["applicantno"]))
		$applicantno = $_GET["applicantno"];
}


if (isset($_GET["ccid"]))
	$ccid = $_GET["ccid"];

if (isset($_POST["load"]))
	$load = $_POST["load"];
else 
	$load = $_GET["load"];

if (isset($_POST["ccid"]))
	$ccid = $_POST["ccid"];
	
if (isset($_GET["print"]))
{
	$print = $_GET["print"];
	$disabled = "";
}
else 
	$print = 0;
	
$checked= "checked=\"checked\"";

	$chkseafarersman = 0;
	$chktrainingman = 0;
	$chkpayoffslip = 0;
	$chkcompanyman = 0;
	$chkfujitransman = 0;
	$chkveritasid = 0;
	
	$checkdoc1 = "";
	$checkdoc2 = "";
	$checkdoc3 = "";
	$checkdoc4 = "";
	$checkdoc5 = "";
	$checkdoc6 = "";
//
//
//
//	
//if (isset($_POST["chkseafarersman"]))
//{
//	$chkseafarersman = 1;
//	$checkdoc1 = $checked;
//}
//
//if (isset($_POST["chktrainingman"]))
//{
//	$chktrainingman = 1;
//	$checkdoc2 = $checked;
//}
//
//if (isset($_POST["chkpayoffslip"]))
//{
//	$chkpayoffslip = 1;
//	$checkdoc3 = $checked;
//}
//
//if (isset($_POST["chkcompanyman"]))
//{
//	$chkcompanyman = 1;
//	$checkdoc4 = $checked;
//}
//
//if (isset($_POST["chkfujitransman"]))
//{
//	$chkfujitransman = 1;
//	$checkdoc5 = $checked;
//}
//
//if (isset($_POST["chkveritasid"]))
//{
//	$chkveritasid = 1;
//	$checkdoc6 = $checked;
//}


$checkinsurance_inclusion = "";
$checkcompute_settlement = "";
$checkfinal_settlement_hold = "";
$checkfinal_settlement_release = "";
$checkstandbypay_hold = "";
$checkstandbypay_release = "";
$checkquitclaim = "";
$checkemploymentcert = "";
$checkforevaluation = "";
$checkexitinterview = "";

$chkinsurance_inclusion = 0;
$chkcompute_settlement = 0;
$chkfinal_settlement = 0;
$chkstandbypay = 0;
$chkquitclaim = 0;
$chkemploymentcert = 0;
$chkexitinterview = 0;
$chkforevaluation = 0;

$others = "";

if (isset($_POST["chkinsurance_inclusion"]))
{
	$chkinsurance_inclusion = 1;
	$checkinsurance_inclusion = $checked;
}

if (isset($_POST["chkcompute_settlement"]))
{
	$chkcompute_settlement = 1;
	$checkcompute_settlement = $checked;
}

$chkfinal_settlement = $_POST["chkfinal_settlement"];

switch ($chkfinal_settlement)
{
	case "0"	: $checkfinal_settlement_hold = $checked; break;
	case "1"	: $checkfinal_settlement_release = $checked; break;
}

$chkstandbypay = $_POST["chkstandbypay"];	

switch ($chkstandbypay)
{
	case "0"	: $checkstandbypay_hold = $checked; break;
	case "1"	: $checkstandbypay_release = $checked; break;
}

if (isset($_POST["chkquitclaim"]))
{
	$chkquitclaim = 1;
	$checkquitclaim = $checked;
}

if (isset($_POST["chkemploymentcert"]))
{
	$chkemploymentcert = 1;
	$checkemploymentcert = $checked;
}

if (isset($_POST["chkexitinterview"]))
{
	$chkexitinterview = 1;
	$checkexitinterview = $checked;
}

if (isset($_POST["chkforevaluation"]))
{
	$chkforevaluation = 1;
	$checkforevaluation = $checked;
}

if (isset($_POST["others"]))
	$others = $_POST["others"];

$designation = "";

function getname ($user,&$designation)
{
	$qrygetuser = mysql_query("SELECT FNAME,GNAME,left(MNAME,1) as MNAME,DESIGNATION
								FROM employee e
								LEFT JOIN designation d ON d.DESIGNATIONCODE=e.DESIGNATIONCODE
								WHERE EMPLOYEEID='$user'
							") or die(mysql_error());
	
	if (mysql_num_rows($qrygetuser) > 0)
	{
		$rowgetuser = mysql_fetch_array($qrygetuser);
		$fname = $rowgetuser["FNAME"];
		$gname = $rowgetuser["GNAME"];
		$mname = $rowgetuser["MNAME"];
		
		$name = $gname . " " . $mname . ". " . $fname;
		
		$designation = $rowgetuser["DESIGNATION"];
		
		return $name;
	}
}
	
// END OF DOCUMENT TURN-OVER

$qrygetheader = mysql_query("SELECT * FROM (
								SELECT cc.APPLICANTNO,cc.CCID,cc.DEPMNLDATE,v.VESSEL,d.REASON,r.ALIAS1 AS RANKALIAS,
								IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,
								cc.ARRMNLDATE,dh.STATUS AS DEBRIEFSTATUS,cc.DATEEMB,cc.EMBPORTID,p.PORTCOUNTRY,p.PORT,
								dh.REPORTEDDATE,dh.AVAILABILITY,dh.JOININGMONTH,
								FLEETBY,DIVISIONBY,MANAGEMENTBY,
								CONCAT(e1.GNAME,' ',LEFT(e1.MNAME,1),'. ',e1.FNAME) AS FLEETNAME,
								CONCAT(e2.GNAME,' ',LEFT(e2.MNAME,1),'. ',e2.FNAME) AS DIVISIONNAME,
								CONCAT(e3.GNAME,' ',LEFT(e3.MNAME,1),'. ',e3.FNAME) AS MANAGEMENTNAME,
								d1.DESIGNATION AS FLEETDESIG,d2.DESIGNATION AS DIVISIONDESIG,d3.DESIGNATION AS MANAGEMENTDESIG,
								p1.PORTCOUNTRY AS DISEMBCOUNTRY,p1.PORT AS DISEMBPORT
								FROM crewchange cc
								LEFT JOIN vessel v ON v.VESSELCODE=cc.VESSELCODE
								LEFT JOIN disembarkreason d ON d.DISEMBREASONCODE=cc.DISEMBREASONCODE
								LEFT JOIN rank r ON r.RANKCODE=cc.RANKCODE
								LEFT JOIN port p ON p.PORTID=cc.EMBPORTID
								LEFT JOIN port p1 ON p1.PORTID=cc.DISEMBPORTID
								LEFT JOIN debriefinghdr dh ON dh.CCID=cc.CCID
								LEFT JOIN debriefingclearance dc ON dc.CCID=dh.CCID
								LEFT JOIN employee e1 ON e1.EMPLOYEEID=FLEETBY
								LEFT JOIN employee e2 ON e2.EMPLOYEEID=DIVISIONBY
								LEFT JOIN employee e3 ON e3.EMPLOYEEID=MANAGEMENTBY
								LEFT JOIN designation d1 ON d1.DESIGNATIONCODE=e1.DESIGNATIONCODE
								LEFT JOIN designation d2 ON d2.DESIGNATIONCODE=e2.DESIGNATIONCODE
								LEFT JOIN designation d3 ON d3.DESIGNATIONCODE=e3.DESIGNATIONCODE
							) x
							WHERE DATEDISEMB BETWEEN '2007-01-01' AND CURRENT_DATE
							AND APPLICANTNO=$applicantno and ccid = $ccid
							ORDER BY DATEDISEMB DESC
							LIMIT 1
						") or die(mysql_error());

if (mysql_num_rows($qrygetheader) > 0)
{
	while ($rowgetheader = mysql_fetch_array($qrygetheader))
	{
		$ccid = $rowgetheader["CCID"];
		
		$showfleetby = "";
		$showfleetdesig = "";
		$showdivisionby = "";
		$showdivisiondesig = "";
		$showmanagementby = "";
		$showmanagementdesig = "";
		
		if (!empty($rowgetheader["FLEETBY"]))
		{
			$fleetby = $rowgetheader["FLEETBY"];
			$showfleetby = $rowgetheader["FLEETNAME"];
			$showfleetdesig = $rowgetheader["FLEETDESIG"];
		}
	
		if (!empty($rowgetheader["DIVISIONBY"]))
		{
			$divisionby = $rowgetheader["DIVISIONBY"];
			
			$showdivisionby = $rowgetheader["DIVISIONNAME"];
			$showdivisiondesig = $rowgetheader["DIVISIONDESIG"];
		}
		
		if (!empty($rowgetheader["MANAGEMENTBY"]))
		{
			$managementby = $rowgetheader["MANAGEMENTBY"];
			
			$showmanagementby = $rowgetheader["MANAGEMENTNAME"];
			$showmanagementdesig = $rowgetheader["MANAGEMENTDESIG"];
		}
	
		
		$vesselname = $rowgetheader["VESSEL"];
		$rankalias = $rowgetheader["RANKALIAS"];
		$disembreason = $rowgetheader["REASON"];
		
		$embportcountry = $rowgetheader["PORTCOUNTRY"];
		$embport = $rowgetheader["PORT"];
		
		$disembportcountry = $rowgetheader["DISEMBCOUNTRY"];
		$disembport = $rowgetheader["DISEMBPORT"];
		
		if (!empty($rowgetheader["DATEEMB"]))
			$dateemb = date($dateformat,strtotime($rowgetheader["DATEEMB"]));
		else 
			$dateemb = "";
		
		if (!empty($rowgetheader["DEPMNLDATE"]))
			$depmnldate = date($dateformat,strtotime($rowgetheader["DEPMNLDATE"]));
		else 
			$depmnldate = "";
			
		if (!empty($rowgetheader["DATEDISEMB"]))
			$datedisemb = date($dateformat,strtotime($rowgetheader["DATEDISEMB"]));
		else 
			$datedisemb = "";
		
		if (!empty($rowgetheader["ARRMNLDATE"]))
			$arrmnldate = date($dateformat,strtotime($rowgetheader["ARRMNLDATE"]));
		else 
			$arrmnldate = "";
			
		if (!empty($rowgetheader["REPORTEDDATE"]))
			$reporteddate = date($dateformat,strtotime($rowgetheader["REPORTEDDATE"]));
		else 
			$reporteddate = "";
			
		if (!empty($rowgetheader["AVAILABILITY"]))
			$availability = date($dateformat,strtotime($rowgetheader["AVAILABILITY"]));
		else 
			$availability = "";
		
		//CHECK IF CREW IS PROMOTED
		
		$qrypromotion = mysql_query("SELECT cpr.CCIDPROMOTE,cc.CCID,cc.DATEEMB,cc.DEPMNLDATE,cc.RANKCODE,r.ALIAS1,r.RANKFULL
									FROM crewpromotionrelation cpr
									LEFT JOIN crewchange cc ON cc.CCID=cpr.CCID
									LEFT JOIN rank r ON r.RANKCODE=cc.RANKCODE
									WHERE CCIDPROMOTE=$ccid") or die(mysql_error());
		
		if (mysql_num_rows($qrypromotion) > 0)
		{
			$rowpromotion = mysql_fetch_array($qrypromotion);
			$promotefromccid = $rowpromotion["CCID"];
			$promotedateemb = $rowpromotion["DATEEMB"];
			$promotedepmnldate = $rowpromotion["DEPMNLDATE"];
			$promoterankalias = $rowpromotion["ALIAS1"];
			$promoterankfull = $rowpromotion["RANKFULL"];
			
			$rankalias2 = "
			<tr>
				<td colspan=\"3\">
					<span style=\"font-size:1em;color:Green;\"><i>(&nbsp;Promoted last $dateemb from [$promoterankalias]&nbsp;)</i></span>
				</td>
			</tr>	
			";
				
			if (!empty($rowpromotion["DATEEMB"]))
				$dateemb = date($dateformat,strtotime($rowpromotion["DATEEMB"]));
			else 
				$dateemb = "";
			
			if (!empty($rowpromotion["DEPMNLDATE"]))
				$depmnldate = date($dateformat,strtotime($rowpromotion["DEPMNLDATE"]));
			else 
				$depmnldate = "";
		
		//END OF CHECKING IF CREW IS PROMOTED
		}
	}
		
}

//GET NEXT EMBARK DATE

$qrygetlineup = mysql_query("SELECT cc.CCID,cc.VESSELCODE,v.VESSEL,cc.DATEEMB
							FROM crewchange cc
							LEFT JOIN vessel v ON v.VESSELCODE=cc.VESSELCODE
							LEFT JOIN disembarkreason d ON d.DISEMBREASONCODE=cc.DISEMBREASONCODE
							LEFT JOIN rank r ON r.RANKCODE=cc.RANKCODE
							WHERE cc.DATEEMB > CURRENT_DATE
							AND APPLICANTNO=$applicantno 
							ORDER BY cc.DATEEMB
							LIMIT 1
							") or die(mysql_error());

$joiningmonth = "---";
$vessellineup = "---";

if (mysql_num_rows($qrygetlineup) > 0)
{
	$rowgetlineup = mysql_fetch_array($qrygetlineup);
	
	if (!empty($rowgetlineup["DATEEMB"]))
		$joiningmonth = date("F Y",strtotime($rowgetlineup["DATEEMB"]));
		
	if (!empty($rowgetlineup["VESSEL"]))
		$vessellineup = $rowgetlineup["VESSEL"];
}

// END OF GET NEXT EMBARK DATE

switch ($actiontxt)
{
	case "save"	:
		
//			$qryupdate1 = mysql_query("UPDATE debriefinghdr SET 
//										SEAFARERSMANUAL = $chkseafarersman,
//										TRAININGBOOK = $chktrainingman,
//										PAYOFFSLIP = $chkpayoffslip,
//										COMPANYMANUAL = $chkcompanyman,
//										FUJITRANSMANUAL = $chkfujitransman,
//										VERITASID = $chkveritasid
//								WHERE CCID=$ccid
//								") or die(mysql_error());
		
//			$qryverify = mysql_query("SELECT * FROM debriefingclearance WHERE CCID=$ccid") or die(mysql_error());
//			
//			if (mysql_num_rows($qryverify) > 0)
//			{
//				$qryupdate2 = mysql_query("UPDATE debriefingclearance SET 
//													EXIT_INTERVIEW = $chkexitinterview,
//													INSURANCE_INCLUSION = $chkinsurance_inclusion,
//													FINALSETTLEMENT_COMPUTE = $chkcompute_settlement,
//													FINALSETTLEMENT = $chkfinal_settlement,
//													QUITCLAIM = $chkquitclaim,
//													EMPLOYMENTCERT = $chkemploymentcert,
//													FURTHER_EVALUATION = $chkforevaluation,
//													OTHERS = '$others'
//											WHERE CCID=$ccid
//										") or die(mysql_error());
//			}
//			else 
//			{
//				$qryinsert = mysql_query("INSERT INTO debriefingclearance(CCID,EXIT_INTERVIEW,INSURANCE_INCLUSION,FINALSETTLEMENT_COMPUTE,
//											FINALSETTLEMENT,QUITCLAIM,EMPLOYMENTCERT,FURTHER_EVALUATION,OTHERS)
//											VALUES($ccid,$chkexitinterview,$chkinsurance_inclusion,$chkcompute_settlement,$chkfinal_settlement,
//											$chkquitclaim,$chkemploymentcert,$chkforevaluation,'$others')
//										") or die(mysql_error());
//			}
		
		break;
}



if ($load == 1)
{
	$load = 0;
	
			//DATA FROM TABLE
			
				$qrygetdebriefing = mysql_query("SELECT * FROM (
												SELECT cc.APPLICANTNO,cc.CCID,cc.DEPMNLDATE,v.VESSEL,d.REASON,r.ALIAS1 AS RANKALIAS,
												IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,
												cc.ARRMNLDATE,dh.STATUS AS DEBRIEFSTATUS,cc.DATEEMB,
												dh.SEAFARERSMANUAL,dh.TRAININGBOOK,dh.PAYOFFSLIP,dh.COMPANYMANUAL,
												dh.FUJITRANSMANUAL,dh.VERITASID,dh.PRINTBY,dh.PRINTDATE,dh.MADEBY,dh.MADEDATE,
												dc.EXIT_INTERVIEW,dc.INSURANCE_INCLUSION,dc.FINALSETTLEMENT_COMPUTE,dc.FINALSETTLEMENT,
												dc.QUITCLAIM,dc.EMPLOYMENTCERT,dc.FURTHER_EVALUATION,dc.OTHERS,dc.STANDBYPAY,
												ds.SURRENDERED_F1,ds.SCANNED_F1,ds.STORED_F1,
												ds.SURRENDERED_41,ds.SCANNED_41,ds.STORED_41,
												ds.SURRENDERED_F2,ds.SCANNED_F2,ds.STORED_F2,
												ds.SURRENDERED_P1,ds.SCANNED_P1,ds.STORED_P1,
												ds.SURRENDERED_P2,ds.SCANNED_P2,ds.STORED_P2,
												ds.SURRENDERED_42,ds.SCANNED_42,ds.STORED_42,
												ds.SURRENDERED_A4,ds.SCANNED_A4,ds.STORED_A4,
												ds.SURRENDERED_32,ds.SCANNED_32,ds.STORED_32,
												ds.SURRENDERED_18,ds.SCANNED_18,ds.STORED_18,
												ds.SURRENDERED_C0,ds.SCANNED_C0,ds.STORED_C0
												FROM crewchange cc
												LEFT JOIN vessel v ON v.VESSELCODE=cc.VESSELCODE
												LEFT JOIN disembarkreason d ON d.DISEMBREASONCODE=cc.DISEMBREASONCODE
												LEFT JOIN rank r ON r.RANKCODE=cc.RANKCODE
												LEFT JOIN debriefinghdr dh ON dh.CCID=cc.CCID
												LEFT JOIN debriefingclearance dc ON dc.CCID=dh.CCID
												LEFT JOIN docsurrenderhdr ds ON ds.CCID=dh.CCID
											) x
											WHERE DATEDISEMB BETWEEN '2007-01-01' AND CURRENT_DATE
											AND APPLICANTNO=$applicantno and ccid = $ccid
											ORDER BY DATEDISEMB DESC
											LIMIT 1
										") or die(mysql_error());
			
				if (mysql_num_rows($qrygetdebriefing) > 0)
				{
					$rowgetdebriefing = mysql_fetch_array($qrygetdebriefing);
					
					if ($rowgetdebriefing["SEAFARERSMANUAL"])
						$chkseafarersman = $marked;
					
					if ($rowgetdebriefing["TRAININGBOOK"])
						$checkdoc2 = $marked;
					
					if ($rowgetdebriefing["PAYOFFSLIP"])
						$checkdoc3 = $marked;
					
					if ($rowgetdebriefing["COMPANYMANUAL"])
						$checkdoc4 = $marked;
					
					if ($rowgetdebriefing["FUJITRANSMANUAL"])
						$checkdoc5 = $marked;
					
					if ($rowgetdebriefing["VERITASID"])
						$checkdoc6 = $marked;
					
					//----------------------------------------------------------------//

					$chksur41 = "";
					$chksurF2 = "";
					$chksurF1 = "";
					$chksur32 = "";
					$chksurP2 = "";
					$chksurP1 = "";
					$chksur42 = "";
					$chksurA4 = "";
					$chksur18 = "";
					$chksurC0 = "";
					
					if($rowgetdebriefing["SURRENDERED_41"])
						$chksur41 = $marked;
						
					if($rowgetdebriefing["SURRENDERED_F2"])
						$chksurF2 = $marked;
						
					if($rowgetdebriefing["SURRENDERED_F1"])
						$chksurF1 = $marked;
						
					if($rowgetdebriefing["SURRENDERED_32"])
						$chksur32 = $marked;
						
					if($rowgetdebriefing["SURRENDERED_P2"])
						$chksurP2 = $marked;
						
					if($rowgetdebriefing["SURRENDERED_P1"])
						$chksurP1 = $marked;
						
					if($rowgetdebriefing["SURRENDERED_42"])
						$chksur42 = $marked;
						
					if($rowgetdebriefing["SURRENDERED_A4"])
						$chksurA4 = $marked;
						
					if($rowgetdebriefing["SURRENDERED_18"])
						$chksur18 = $marked;
						
					if($rowgetdebriefing["SURRENDERED_C0"])
						$chksurC0 = $marked;

					$chkscan41 = "";
					$chkscanF2 = "";
					$chkscanF1 = "";
					$chkscan32 = "";
					$chkscanP2 = "";
					$chkscanP1 = "";
					$chkscan42 = "";
					$chkscanA4 = "";
					$chkscan18 = "";
					$chkscanC0 = "";
					
					if($rowgetdebriefing["SCANNED_41"])
						$chkscan41 = $marked;
						
					if($rowgetdebriefing["SCANNED_F2"])
						$chkscanF2 = $marked;
						
					if($rowgetdebriefing["SCANNED_F1"])
						$chkscanF1 = $marked;
						
					if($rowgetdebriefing["SCANNED_32"])
						$chkscan32 = $marked;
						
					if($rowgetdebriefing["SCANNED_P2"])
						$chkscanP2 = $marked;
						
					if($rowgetdebriefing["SCANNED_P1"])
						$chkscanP1 = $marked;
						
					if($rowgetdebriefing["SCANNED_42"])
						$chkscan42 = $marked;
						
					if($rowgetdebriefing["SCANNED_A4"])
						$chkscanA4 = $marked;
						
					if($rowgetdebriefing["SCANNED_18"])
						$chkscan18 = $marked;
						
					if($rowgetdebriefing["SCANNED_C0"])
						$chkscanC0 = $marked;
					
					$chkstore41 = "";
					$chkstoreF2 = "";
					$chkstoreF1 = "";
					$chkstore32 = "";
					$chkstoreP2 = "";
					$chkstoreP1 = "";
					$chkstore42 = "";
					$chkstoreA4 = "";
					$chkstore18 = "";
					$chkstoreC0 = "";
		
					if($rowgetdebriefing["STORED_41"])
						$chkstore41 = $marked;
						
					if($rowgetdebriefing["STORED_F2"])
						$chkstoreF2 = $marked;
						
					if($rowgetdebriefing["STORED_F1"])
						$chkstoreF1 = $marked;
						
					if($rowgetdebriefing["STORED_32"])
						$chkstore32 = $marked;
						
					if($rowgetdebriefing["STORED_P2"])
						$chkstoreP2 = $marked;
						
					if($rowgetdebriefing["STORED_P1"])
						$chkstoreP1 = $marked;
						
					if($rowgetdebriefing["STORED_42"])
						$chkstore42 = $marked;
						
					if($rowgetdebriefing["STORED_A4"])
						$chkstoreA4 = $marked;
						
					if($rowgetdebriefing["STORED_18"])
						$chkstore42 = $marked;
						
					if($rowgetdebriefing["STORED_C0"])
						$chkstoreA4 = $marked;
					
					//----------------------------------------------------------------//
					
					if ($rowgetdebriefing["EXIT_INTERVIEW"])
					{
						$chkexitinterview = 1;
						$checkexitinterview = $marked;
					}
					
					if ($rowgetdebriefing["INSURANCE_INCLUSION"])
					{
						$chkinsurance_inclusion = 1;
						$checkinsurance_inclusion = $marked;
					}
					
					if ($rowgetdebriefing["FINALSETTLEMENT_COMPUTE"])
					{
						$chkcompute_settlement = 1;
						$checkcompute_settlement = $marked;
					}
					
					$chkfinal_settlement = $rowgetdebriefing["FINALSETTLEMENT"];
					
					switch ($chkfinal_settlement)
					{
						case "0"	: $checkfinal_settlement_hold = $marked; break;
						case "1"	: $checkfinal_settlement_release = $marked; break;
					}
					
					if (empty($rowgetdebriefing["STANDBYPAY"]) && $rowgetdebriefing["STANDBYPAY"] != 0)
						$chkstandbypay = "";
					else 
						$chkstandbypay = $rowgetdebriefing["STANDBYPAY"];
					
					switch ($chkstandbypay)
					{
						case "0"	: $checkstandbypay_hold = $marked; break;
						case "1"	: $checkstandbypay_release = $marked; break;
					}
					
					if ($rowgetdebriefing["QUITCLAIM"])
					{
						$chkquitclaim = 1;
						$checkquitclaim = $marked;
					}
					
					if ($rowgetdebriefing["EMPLOYMENTCERT"])
					{
						$chkemploymentcert = 1;
						$checkemploymentcert = $marked;
					}
					
					if ($rowgetdebriefing["FURTHER_EVALUATION"])
					{
						$chkforevaluation = 1;
						$checkforevaluation = $marked;
					}
					
					$others = $rowgetdebriefing["OTHERS"];
					
					
				}
			
			//END OF DATA FROM TABLE
}

include("include/datasheet.inc");


echo "
<html>\n
<head>\n
<title>
Crew Clearance Checklist
</title>

<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>


</head>
<body style=\"\">\n

<form name=\"clearanceform\" method=\"POST\">\n

	";
	
	$styleborder = "style=\"border-bottom:1px solid Black;\"";
	$styledata = "style=\"font-weight:Bold;color:Blue;text-align:center;\"";
	$styleencode = "style=\"color:Red;font-weight:Bold;\"";
	
	echo "
	<div style=\"margin:5 5 5 10;width:755px;height:600px;\">
	
		<div style=\"width:80%;height:60px;float:left;background-color:White;\">
			<div style=\"width:85%;float:left;\">
				<center>
					<span style=\"font-size:1.1em;font-weight:Bold;\">VERITAS MARITIME CORPORATION</span><br />
					<span style=\"font-size:0.9em;font-weight:Bold;\">CREW CLEARANCE CHECKLIST</span><br />
					<span style=\"font-size:0.8em;\">(TO BE ACCOMPLISHED FOR ALL DISEMBARKED CREW)</span><br />
					<span style=\"font-size:0.8em;font-weight:Bold;\">Date: $datenow</span><br />
				</center>
			</div>
			<div style=\"width:13%;float:right;\">
				<span style=\"font-size:0.6em;font-weight:Bold;\">FM-229</span><br />
				<span style=\"font-size:0.6em;font-weight:Bold;\">REV. MARCH 2008</span>
			</div>
		</div>
		
		<div style=\"width:20%;float:right;\">
	";
			$dirfilename = "$basedir/$applicantno.JPG";
			if (checkpath($dirfilename))
			{
				$scale = imageScale($dirfilename,-1,90);
				$width = $scale[0];
				$height = $scale[1];
				
	echo "			<center><img src=\"$dirfilename\" width=\"$width\" height=\"$height\" border=\"1\" /></center> ";
			}
			else 
			{
	echo "			<center><b>[NO PICTURE]</b></center>";
			}
	echo "
		</div>
		<br /><br />
		<div style=\"width:100%;background-color:White;\">
			<table style=\"width:100%;\">
				<tr>
					<td width=\"42%\"><span style=\"font-size:0.70em;\">NAME:</span> <br /> <span style=\"font-size:1em;font-weight:Bold;color:Blue;\">$crewname</span></td>
					<td width=\"20%\"><span style=\"font-size:0.70em;\">CREWCODE:</span> <br /><span style=\"font-size:1em;font-weight:Bold;color:Blue;\">$crewcode</span></td>
					<td width=\"20%\"><span style=\"font-size:0.70em;\">APPLICANT NO:</span> <br /><span style=\"font-size:1em;font-weight:Bold;color:Blue;\">$applicantno</span></td>
					<td width=\"18%\"><span style=\"font-size:0.70em;\">REPORT DATE:</span> <br /><span style=\"font-size:1em;font-weight:Bold;color:Blue;\">$reporteddate</span></td>
				</tr>
			</table>
			<hr />
		</div>
		
		<div style=\"width:100%;background-color:White;\">
			<table style=\"width:60%;font-size:0.8em;float:left;\" cellspacing=\"0\" cellpadding=\"2\">
				<tr $mouseovereffect>
					<td width=\"40%\" valign=\"top\">Vessel</td>
					<td width=\"2%\" valign=\"top\">:</td>
					<td $styledata>$vesselname&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td>Rank</td>
					<td valign=\"top\">:</td>
					<td $styledata>$rankalias&nbsp;</td>
				</tr>
				$rankalias2
				<tr $mouseovereffect>
					<td>Civil Status</td>
					<td valign=\"top\">:</td>
					<td $styledata>$crewcivilstatus&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td>Embark Port (Country)</td>
					<td valign=\"top\">:</td>
					<td $styledata>$embport (&nbsp;$embportcountry&nbsp;)</td>
				</tr>
				<tr $mouseovereffect>
					<td>Departure Manila</td>
					<td valign=\"top\">:</td>
					<td $styledata>$depmnldate&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td>Date Joined</td>
					<td valign=\"top\">:</td>
					<td $styledata>$dateemb&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td>Disembark Port (Country)</td>
					<td valign=\"top\">:</td>
					<td $styledata>$disembport (&nbsp;$disembportcountry&nbsp;)</td>
				</tr>
				<tr $mouseovereffect>
					<td>Disembark Date</td>
					<td valign=\"top\">:</td>
					<td $styledata>$datedisemb&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td>Arrival Manila</td>
					<td valign=\"top\">:</td>
					<td $styledata>$arrmnldate&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td>Reason</td>
					<td valign=\"top\">:</td>
					<td $styledata>$disembreason&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td>Availability</td>
					<td valign=\"top\">:</td>
					<td $styledata>$availability</td>
				</tr>
				<tr $mouseovereffect>
					<td>Tentative Joining Month</td>
					<td valign=\"top\">:</td>
					<td $styledata>$joiningmonth</td>
				</tr>
				<tr $mouseovereffect>
					<td>Vessel Line-up</td>
					<td valign=\"top\">:</td>
					<td $styledata>$vessellineup</td>
				</tr>
			</table>
			
			<table style=\"width:36%;font-size:0.75em;float:left;border-left:1px solid Black;\" cellspacing=\"0\" cellpadding=\"2\">
				<tr>
					<td style=\"font-weight:Bold;\">DOCUMENTS</td>
					<td style=\"font-size:0.75em;font-weight:Bold;\">SURR</td>
					<td style=\"font-size:0.75em;font-weight:Bold;\">SCAN</td>
					<td style=\"font-size:0.75em;font-weight:Bold;\">STOR</td>
				</tr>
				<tr><td colspan=\"4\">&nbsp;</td></tr>
				<tr $mouseovereffect>
					<td>PASSPORT</td>
					<td align=\"center\">$chksur41</td>
					<td align=\"center\">$chkscan41</td>
					<td align=\"center\">$chkstore41</td>
				</tr>
				<tr $mouseovereffect>
					<td>PHIL SEAMAN'S BOOK</td>
					<td align=\"center\">$chksurF2</td>
					<td align=\"center\">$chkscanF2</td>
					<td align=\"center\">$chkstoreF2</td>
				</tr>
<!--
				<tr $mouseovereffect>
					<td>PHIL LICENSE</td>
					<td align=\"center\">$chksurF1</td>
					<td align=\"center\">$chkscanF1</td>
					<td align=\"center\">$chkstoreF1</td>
				</tr>
-->
				<tr $mouseovereffect>
					<td>SRC</td>
					<td align=\"center\">$chksur32</td>
					<td align=\"center\">$chkscan32</td>
					<td align=\"center\">$chkstore32</td>
				</tr>
				<tr $mouseovereffect>
					<td>PANAMA SEAMAN'S BOOK</td>
					<td align=\"center\">$chksurP2</td>
					<td align=\"center\">$chkscanP2</td>
					<td align=\"center\">$chkstoreP2</td>
				</tr>
<!--
				<tr $mouseovereffect>
					<td>PANAMA LICENSE</td>
					<td align=\"center\">$chksurP1</td>
					<td align=\"center\">$chkscanP1</td>
					<td align=\"center\">$chkstoreP1</td>
				</tr>
-->
				<tr $mouseovereffect>
					<td>U.S. VISA</td>
					<td align=\"center\">$chksur42</td>
					<td align=\"center\">$chkscan42</td>
					<td align=\"center\">$chkstore42</td>
				</tr>
<!--
				<tr $mouseovereffect>
					<td>AU MCV</td>
					<td align=\"center\">$chksurA4</td>
					<td align=\"center\">$chkscanA4</td>
					<td align=\"center\">$chkstoreA4</td>
				</tr>
-->
				<tr $mouseovereffect>
					<td>COC (OFFICERS)</td>
					<td align=\"center\">$chksur18</td>
					<td align=\"center\">$chkscan18</td>
					<td align=\"center\">$chkstore18</td>
				</tr>
				<tr $mouseovereffect>
					<td>COC (RATINGS)</td>
					<td align=\"center\">$chksurC0</td>
					<td align=\"center\">$chkscanC0</td>
					<td align=\"center\">$chkstoreC0</td>
				</tr>
			</table>
		</div>
		
		<hr />
		<center>
		<div style=\"width:80%;\">
			<table style=\"width:100%;font-size:0.75em;float:left;\" border=1 cellspacing=\"0\" cellpadding=\"2\">
				<tr $mouseovereffect>
					<td colspan=\"2\" align=\"center\">$checkinsurance_inclusion&nbsp;</td>
					<td>FOR INCLUSION IN INSURANCE FOR VACATIONING CREW</td>
				</tr>
				<tr $mouseovereffect>
					<td colspan=\"2\" align=\"center\">$checkcompute_settlement&nbsp;</td>
					<td>COMPUTE FINAL SETTLEMENT</td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">Hold <br />
						$checkfinal_settlement_hold&nbsp;
					</td>
					<td align=\"center\">Release <br />
						$checkfinal_settlement_release&nbsp;
					</td>
					<td>FINAL SETTLEMENT</td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">$checkstandbypay_hold &nbsp;
					</td>
					<td align=\"center\">$checkstandbypay_release &nbsp;
					</td>
					<td>STANDBY PAY</td>
				</tr>
				<tr $mouseovereffect>
					<td colspan=\"2\" align=\"center\">$checkquitclaim&nbsp;</td>
					<td>PREPARE QUIT CLAIM DOCUMENTS</td>
				</tr>
				<tr $mouseovereffect>
					<td colspan=\"2\" align=\"center\">$checkemploymentcert&nbsp;</td>
					<td>CERTIFICATE OF EMPLOYMENT</td>
				</tr>
				<tr $mouseovereffect>
					<td colspan=\"2\" align=\"center\">$checkexitinterview&nbsp;</td>
					<td>EXIT INTERVIEW COMPLETED</td>
				</tr>
				<tr $mouseovereffect>
					<td colspan=\"2\" align=\"center\">$checkforevaluation&nbsp;</td>
					<td>FOR FURTHER EVALUATION</td>
				</tr>
				<tr>
					<td colspan=\"2\">&nbsp;</td>
					<td>
						<table style=\"width:50%;font-size:0.9em;float:left;padding:2;\" cellspacing=\"0\">
							<tr>
								<td align=\"left\" colspan=\"2\"><b>DOCUMENTS TURN-OVER</b></td>
							</tr>
							<tr><td colspan=\"2\">&nbsp;</td></tr>
							<tr $mouseovereffect>
								<td>SEAFARER'S MANUAL</td>
								<td align=\"center\">$checkdoc1&nbsp;</td>
							</tr>
							<tr $mouseovereffect>
								<td>VERITAS ID</td>
								<td align=\"center\">$checkdoc6&nbsp;</td>
							</tr>
							<tr $mouseovereffect>
								<td>PAY-OFF SLIP</td>
								<td align=\"center\">$checkdoc3&nbsp;</td>
							</tr>
							<tr $mouseovereffect>
								<td>COMPANY MANUAL</td>
								<td align=\"center\">$checkdoc4&nbsp;</td>
							</tr>
							<tr $mouseovereffect>
								<td>FUJITRANS MANUAL</td>
								<td align=\"center\">$checkdoc5&nbsp;</td>
							</tr>
							<tr $mouseovereffect>
								<td>TRAINING BOOK</td>
								<td align=\"center\">$checkdoc2&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr $mouseovereffect>
					<td colspan=\"2\" align=\"center\">Others: (Please Specify)</td>
					<td>
						<textarea rows=\"4\" cols=\"50\" name=\"others\" $disabled>$others</textarea>
					</td>
				</tr>
			</table>
		</div>
		
		</center>
		<br /><br /><br /><br />
		
		<table style=\"width:100%;font-size:1em;font-weight:Bold;\">
			<tr>
				<td width=\"30%\" style=\"border-bottom:1px solid Black;\">&nbsp;</td>
				<td>&nbsp;</td>
				<td width=\"30%\" style=\"border-bottom:1px solid Black;\">&nbsp;</td>
				<td>&nbsp;</td>
				<td width=\"30%\" style=\"border-bottom:1px solid Black;\">&nbsp;</td>
			</tr>
			<tr>
				<td style=\"font-size:0.8em;\">$showfleetby <br/ > <span style=\"font-size:0.8em;\">$showfleetdesig</span></td>
				<td>&nbsp;</td>
				<td style=\"font-size:0.8em;\">$showdivisionby <br /> <span style=\"font-size:0.8em;\">$showdivisiondesig</span></td>
				<td>&nbsp;</td>
				<td style=\"font-size:0.8em;\">$showmanagementby <br /> <span style=\"font-size:0.8em;\">$showmanagementdesig</span></td>
			</tr>
<!--
			<tr>
				<td style=\"font-size:0.6em;\">$showfleetdesig</td>
				<td>&nbsp;</td>
				<td style=\"font-size:0.6em;>$showdivisiondesig</td>
				<td>&nbsp;</td>
				<td style=\"font-size:0.6em;\">$showmanagementdesig</td>
			</tr>
-->
		</table>
		<hr />
		<br />
		
	</div>
	
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"applicantno\" value=\"$applicantno\"/>
	<input type=\"hidden\" name=\"ccid\" value=\"$ccid\"/>
	<input type=\"hidden\" name=\"load\" value=\"$load\"/>
</form>";

if ($print == 1)
	include('include/printclose.inc');

echo "
</body>

</html>

";