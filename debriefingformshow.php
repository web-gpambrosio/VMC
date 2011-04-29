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
$disabled = "";
$disabledshow = "disabled=\"disabled\"";

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
	$disabledshow = "";
}
else 
	$print = 0;	

	
//echo "applicantno = " . $applicantno . " applicantno2 = " . $applicantno2 . "<br />";

if (isset($_POST["searchkey"]))
	$searchkey = $_POST["searchkey"];

if (isset($_POST["searchby"]))
	$searchby = $_POST["searchby"];
	
$showmultiple = "visibility:hidden;";
$multiple = 0;
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

	$q1 = "";
	$q2 = "";
	$q3a = "";
	$q3b = "";
	$q4a = "";
	$q4b = "";
	
	$chkq1yes = "";
	$chkq1no = "";
	$chkq1uncertain = "";
	$chkq2yes = "";
	$chkq2no = "";
	$chkq2uncertain = "";
	$chkq3ayes = "";
	$chkq3ano = "";
	$chkq3auncertain = "";
	$chkq3byes = "";
	$chkq3bno = "";
	$chkq3buncertain = "";
	$chkq4asuff = "";
	$chkq4ainsuff = "";
	$chkq4bsuff = "";
	$chkq4binsuff = "";
	$chkq4cH = "";
	$chkq4cL = "";
	$chkq4cR = "";
	$chkq4cO = "";
	
	$q1remarks = "";
	$q2remarks = "";
	$q3aremarks = "";
	$q3bremarks = "";
	$q4cremarks = "";
	
	$q5mastera = "";
	$q5masterb = "";
	$q5masterc = "";
	$q5masterd = "";
	$q5mastere = "";
	$q5masterf = "";
	$q5masterg = "";
	
	$name_master = "";
	
	$chkq5mastera2 = "";
	$chkq5mastera1 = "";
	$chkq5mastera0 = "";
	$chkq5masterb2 = "";
	$chkq5masterb1 = "";
	$chkq5masterb0 = "";
	$chkq5masterc2 = "";
	$chkq5masterc1 = "";
	$chkq5masterc0 = "";
	$chkq5masterd2 = "";
	$chkq5masterd1 = "";
	$chkq5masterd0 = "";
	$chkq5mastere2 = "";
	$chkq5mastere1 = "";
	$chkq5mastere0 = "";
	$chkq5masterf2 = "";
	$chkq5masterf1 = "";
	$chkq5masterf0 = "";
	$chkq5masterg2 = "";
	$chkq5masterg1 = "";
	$chkq5masterg0 = "";
	
	$name_chiefeng = "";
	
	$q5chiefenga = "";
	$q5chiefengb = "";
	$q5chiefengc = "";
	$q5chiefengd = "";
	$q5chiefenge = "";
	$q5chiefengf = "";
	$q5chiefengg = "";
	
	$chkq5chiefenga2 = "";
	$chkq5chiefenga1 = "";
	$chkq5chiefenga0 = "";
	$chkq5chiefengb2 = "";
	$chkq5chiefengb1 = "";
	$chkq5chiefengb0 = "";
	$chkq5chiefengc2 = "";
	$chkq5chiefengc1 = "";
	$chkq5chiefengc0 = "";
	$chkq5chiefengd2 = "";
	$chkq5chiefengd1 = "";
	$chkq5chiefengd0 = "";
	$chkq5chiefenge2 = "";
	$chkq5chiefenge1 = "";
	$chkq5chiefenge0 = "";
	$chkq5chiefengf2 = "";
	$chkq5chiefengf1 = "";
	$chkq5chiefengf0 = "";
	$chkq5chiefengg2 = "";
	$chkq5chiefengg1 = "";
	$chkq5chiefengg0 = "";
	
	$name_chiefcook = "";
	
	$q6chiefcooka = "";
	$q6chiefcookb = "";
	$q6chiefcookc = "";
	$q6chiefcookd = "";
	$q6chiefcooke = "";
	
	$chkq6chiefcooka2 = "";
	$chkq6chiefcooka1 = "";
	$chkq6chiefcooka0 = "";
	$chkq6chiefcookb2 = "";
	$chkq6chiefcookb1 = "";
	$chkq6chiefcookb0 = "";
	$chkq6chiefcookc2 = "";
	$chkq6chiefcookc1 = "";
	$chkq6chiefcookc0 = "";
	$chkq6chiefcookd2 = "";
	$chkq6chiefcookd1 = "";
	$chkq6chiefcookd0 = "";
	$chkq6chiefcooke2 = "";
	$chkq6chiefcooke1 = "";
	$chkq6chiefcooke0 = "";
	
	$q6chiefcookf = "";
	
	$q7a = "";
	$q7b = "";
	$q7c = "";
	$q7d = "";
	$q7dremarks = "";
	
	$chkq7a2 = "";
	$chkq7a1 = "";
	$chkq7a0 = "";
	$chkq7b2 = "";
	$chkq7b1 = "";
	$chkq7b0 = "";
	$chkq7c2 = "";
	$chkq7c1 = "";
	$chkq7c0 = "";
	$chkq7d2 = "";
	$chkq7d1 = "";
	$chkq7d0 = "";




switch ($actiontxt)
{
	case "find"		:
		
			switch ($searchby)
			{
			case "1"	: //APPLICANT NO
				
					$qrysearch = mysql_query("SELECT * FROM (
								SELECT cc.APPLICANTNO,cc.CCID,
								IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,
								cc.ARRMNLDATE,dh.STATUS AS DEBRIEFSTATUS,
								CONCAT(c.FNAME,', ',c.GNAME,' ',c.MNAME) AS NAME,c.CREWCODE
								FROM crewchange cc
								LEFT JOIN crew c ON c.APPLICANTNO=cc.APPLICANTNO
								LEFT JOIN debriefinghdr dh ON dh.CCID=cc.CCID
								WHERE dh.STATUS = 0
							) x
							WHERE DATEDISEMB BETWEEN '2007-01-01' AND CURRENT_DATE
							AND APPLICANTNO LIKE '$searchkey%'
							ORDER BY DATEDISEMB DESC
						") or die(mysql_error());
				
				break;
			case "2"	: //CREW CODE
				
					$qrysearch = mysql_query("SELECT * FROM (
								SELECT cc.APPLICANTNO,c.FNAME,c.GNAME,cc.CCID,
								IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,
								cc.ARRMNLDATE,dh.STATUS AS DEBRIEFSTATUS,
								CONCAT(c.FNAME,', ',c.GNAME,' ',c.MNAME) AS NAME,c.CREWCODE
								FROM crewchange cc
								LEFT JOIN crew c ON c.APPLICANTNO=cc.APPLICANTNO
								LEFT JOIN debriefinghdr dh ON dh.CCID=cc.CCID
								WHERE dh.STATUS = 0
							) x
							WHERE DATEDISEMB BETWEEN '2007-01-01' AND CURRENT_DATE
							AND CREWCODE LIKE '$searchkey%'
							ORDER BY DATEDISEMB DESC
						") or die(mysql_error());
				
				break;
			case "3"	: //FAMILY NAME
				
					$qrysearch = mysql_query("SELECT * FROM (
								SELECT cc.APPLICANTNO,c.FNAME,c.GNAME,cc.CCID,
								IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,
								cc.ARRMNLDATE,dh.STATUS AS DEBRIEFSTATUS,
								CONCAT(c.FNAME,', ',c.GNAME,' ',c.MNAME) AS NAME,c.CREWCODE
								FROM crewchange cc
								LEFT JOIN crew c ON c.APPLICANTNO=cc.APPLICANTNO
								LEFT JOIN debriefinghdr dh ON dh.CCID=cc.CCID
								WHERE dh.STATUS = 0
							) x
							WHERE DATEDISEMB BETWEEN '2007-01-01' AND CURRENT_DATE
							AND FNAME LIKE '$searchkey%'
							ORDER BY DATEDISEMB DESC
						") or die(mysql_error());
				
				break;
			case "4"	: //GIVEN NAME
				
					$qrysearch = mysql_query("SELECT * FROM (
								SELECT cc.APPLICANTNO,c.FNAME,c.GNAME,cc.CCID,
								IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,
								cc.ARRMNLDATE,dh.STATUS AS DEBRIEFSTATUS,
								CONCAT(c.FNAME,', ',c.GNAME,' ',c.MNAME) AS NAME,c.CREWCODE
								FROM crewchange cc
								LEFT JOIN crew c ON c.APPLICANTNO=cc.APPLICANTNO
								LEFT JOIN debriefinghdr dh ON dh.CCID=cc.CCID
								WHERE dh.STATUS = 0
							) x
							WHERE DATEDISEMB BETWEEN '2007-01-01' AND CURRENT_DATE
							AND GNAME LIKE '$searchkey%'
							ORDER BY DATEDISEMB DESC
						") or die(mysql_error());
				
				break;
			}
		
			if (mysql_num_rows($qrysearch) == 1)  //SINGLE ENTRY FOUND
			{
				$rowsearch = mysql_fetch_array($qrysearch);
				$applicantno = $rowsearch["APPLICANTNO"];
				$load = 1;
			}
			elseif (mysql_num_rows($qrysearch) > 1)  //MULTIPLE ENTRY FOUND
			{
				$showmultiple = "visibility:show;";
				$multiple = 1;
			}
			else 
			{
				$applicantno="";
				$errormsg = "Search Key -- '$searchkey' Not Found.";
				$disabled = "disabled=\"disabled\"";
			}
			
		
		break;
}

$qrygetheader = mysql_query("SELECT * FROM (
								SELECT cc.APPLICANTNO,cc.CCID,cc.DEPMNLDATE,v.VESSEL,d.REASON,r.ALIAS1 AS RANKALIAS,
								IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,
								cc.ARRMNLDATE,dh.STATUS AS DEBRIEFSTATUS,cc.DATEEMB,
								dh.REPORTEDDATE,dh.AVAILABILITY,dh.JOININGMONTH
								FROM crewchange cc
								LEFT JOIN vessel v ON v.VESSELCODE=cc.VESSELCODE
								LEFT JOIN disembarkreason d ON d.DISEMBREASONCODE=cc.DISEMBREASONCODE
								LEFT JOIN rank r ON r.RANKCODE=cc.RANKCODE
								LEFT JOIN debriefinghdr dh ON dh.CCID=cc.CCID
							) x
							WHERE DATEDISEMB BETWEEN '2007-01-01' AND CURRENT_DATE
							AND APPLICANTNO=$applicantno AND ccid=$ccid
							ORDER BY DATEDISEMB DESC
							LIMIT 1
						") or die(mysql_error());

if (mysql_num_rows($qrygetheader) > 0)
{
	while ($rowgetheader = mysql_fetch_array($qrygetheader))
	{
		$ccid = $rowgetheader["CCID"];
		
		$vesselname = $rowgetheader["VESSEL"];
		$rankalias = $rowgetheader["RANKALIAS"];
		$disembreason = $rowgetheader["REASON"];
		
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
			}	
			
			//END OF CHECKING IF CREW IS PROMOTED
	}
}

//GET NEXT EMBARK DATE

$qrygetlineup = mysql_query("SELECT cc.CCID,cc.VESSELCODE,v.VESSEL,cc.DATEEMB
							FROM crewchange cc
							LEFT JOIN vessel v ON v.VESSELCODE=cc.VESSELCODE
							LEFT JOIN disembarkreason d ON d.DISEMBREASONCODE=cc.DISEMBREASONCODE
							LEFT JOIN rank r ON r.RANKCODE=cc.RANKCODE
							LEFT JOIN crewpromotionrelation cpr ON cpr.CCIDPROMOTE=cc.CCID
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

if ($load == 1)
{
	$load = 0;
	
			//DATA FROM TABLE
			
				$qrygetdebriefing = mysql_query("SELECT * FROM (
												SELECT cc.APPLICANTNO,cc.CCID,cc.DEPMNLDATE,v.VESSEL,d.REASON,r.ALIAS1 AS RANKALIAS,
												IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,
												cc.ARRMNLDATE,dh.STATUS AS DEBRIEFSTATUS,cc.DATEEMB,
												dh.REPORTEDDATE,dh.AVAILABILITY,dh.JOININGMONTH,dh.SEAFARERSMANUAL,dh.TRAININGBOOK,dh.PAYOFFSLIP,dh.COMPANYMANUAL,
												dh.FUJITRANSMANUAL,dh.VERITASID,dh.UNDERTAKINGDATE1,dh.UNDERTAKINGDATE2,dh.Q1,dh.Q1_REMARKS,dh.Q2,dh.Q2_REMARKS,
												dh.Q3A,dh.Q3A_REMARKS,dh.Q3B,dh.Q3B_REMARKS,dh.Q4A,dh.Q4B,dh.Q4C,dh.Q4C_REMARKS,
												dh.NAME_MASTER,dh.Q5_MASTERA,dh.Q5_MASTERB,dh.Q5_MASTERC,dh.Q5_MASTERD,dh.Q5_MASTERE,dh.Q5_MASTERF,dh.Q5_MASTERG,
												dh.NAME_CHIEFENG,dh.Q5_CHIEFENGA,dh.Q5_CHIEFENGB,dh.Q5_CHIEFENGC,dh.Q5_CHIEFENGD,dh.Q5_CHIEFENGE,dh.Q5_CHIEFENGF,dh.Q5_CHIEFENGG,
												dh.NAME_CHIEFCOOK,dh.Q6_CHIEFCOOKA,dh.Q6_CHIEFCOOKB,dh.Q6_CHIEFCOOKC,dh.Q6_CHIEFCOOKD,dh.Q6_CHIEFCOOKE,dh.Q6_CHIEFCOOKF,
												dh.Q7A,dh.Q7B,dh.Q7C,dh.Q7D,dh.Q7D_REMARKS,Q8_1,Q8_2,Q8_3,Q8_4,Q8_5,Q8_6,Q8_7,Q8_8,Q8_9,
												dh.PRINTBY,dh.PRINTDATE,dh.MADEBY,dh.MADEDATE
												FROM crewchange cc
												LEFT JOIN vessel v ON v.VESSELCODE=cc.VESSELCODE
												LEFT JOIN disembarkreason d ON d.DISEMBREASONCODE=cc.DISEMBREASONCODE
												LEFT JOIN rank r ON r.RANKCODE=cc.RANKCODE
												LEFT JOIN debriefinghdr dh ON dh.CCID=cc.CCID
											) x
											WHERE DATEDISEMB BETWEEN '2007-01-01' AND CURRENT_DATE
											AND APPLICANTNO=$applicantno AND ccid=$ccid
											ORDER BY DATEDISEMB DESC
											LIMIT 1
										") or die(mysql_error());
			
				if (mysql_num_rows($qrygetdebriefing) > 0)
				{
					$rowgetdebriefing = mysql_fetch_array($qrygetdebriefing);
						
					if ($rowgetdebriefing["SEAFARERSMANUAL"] == 1)
						$checkdoc1 = $marked;
					
					if ($rowgetdebriefing["TRAININGBOOK"] == 1)
						$checkdoc2 = $marked;
					
					if ($rowgetdebriefing["PAYOFFSLIP"] == 1)
						$checkdoc3 = $marked;
					
					if ($rowgetdebriefing["COMPANYMANUAL"] == 1)
						$checkdoc4 = $marked;
					
					if ($rowgetdebriefing["FUJITRANSMANUAL"] == 1)
						$checkdoc5 = $marked;
					
					if ($rowgetdebriefing["VERITASID"] == 1)
						$checkdoc6 = $marked;
					
					if (!empty($rowgetdebriefing["UNDERTAKINGDATE1"]))
						$undertakingdate1 = date("m/d/Y",strtotime($rowgetdebriefing["UNDERTAKINGDATE1"]));
					else 
						$undertakingdate1 = "";
					
					if (!empty($rowgetdebriefing["UNDERTAKINGDATE2"]))
						$undertakingdate2 = date("m/d/Y",strtotime($rowgetdebriefing["UNDERTAKINGDATE2"]));
					else 
						$undertakingdate2 = "";
					
					switch ($rowgetdebriefing["Q1"])
					{
						case "1"	: $chkq1yes = $marked; break;
						case "0"	: $chkq1no = $marked; break;
						case "2"	: $chkq1uncertain = $marked; break;
					}
					
					$q1remarks = $rowgetdebriefing["Q1_REMARKS"];
					
					switch ($rowgetdebriefing["Q2"])
					{
						case "1"	: $chkq2yes = $marked; break;
						case "0"	: $chkq2no = $marked; break;
						case "2"	: $chkq2uncertain = $marked; break;
					}
					
					$q2remarks = $rowgetdebriefing["Q2_REMARKS"];
					
					switch ($rowgetdebriefing["Q3A"])
					{
						case "1"	: $chkq3ayes = $marked; break;
						case "0"	: $chkq3ano = $marked; break;
						case "2"	: $chkq3auncertain = $marked; break;
					}
					
					$q3aremarks = $rowgetdebriefing["Q3A_REMARKS"];
					
					switch ($rowgetdebriefing["Q3B"])
					{
						case "1"	: $chkq3byes = $marked; break;
						case "0"	: $chkq3bno = $marked; break;
						case "2"	: $chkq3buncertain = $marked; break;
					}
					
					$q3bremarks = $rowgetdebriefing["Q3B_REMARKS"];
					
					switch ($rowgetdebriefing["Q4A"])
					{
						case "1"	: $chkq4asuff = $marked; break;
						case "0"	: $chkq4ainsuff = $marked; break;
					}
					
					switch ($rowgetdebriefing["Q4B"])
					{
						case "1"	: $chkq4bsuff = $marked; break;
						case "0"	: $chkq4binsuff = $marked; break;
					}
						
					switch ($rowgetdebriefing["Q4C"])
					{
						case "1"	: $chkq4cH = $marked; break;
						case "2"	: $chkq4cL = $marked; break;
						case "3"	: $chkq4cR = $marked; break;
						case "4"	: $chkq4cO = $marked; break;
					}
					
					$q4cremarks = $rowgetdebriefing["Q4C_REMARKS"];
					
					$name_master = $rowgetdebriefing["NAME_MASTER"];
					
					switch ($rowgetdebriefing["Q5_MASTERA"])
					{
						case "2"	: $chkq5mastera2 = $marked; break;
						case "1"	: $chkq5mastera1 = $marked; break;
						case "0"	: $chkq5mastera0 = $marked; break;
					}
					switch ($rowgetdebriefing["Q5_MASTERB"])
					{
						case "2"	: $chkq5masterb2 = $marked; break;
						case "1"	: $chkq5masterb1 = $marked; break;
						case "0"	: $chkq5masterb0 = $marked; break;
					}
					switch ($rowgetdebriefing["Q5_MASTERC"])
					{
						case "2"	: $chkq5masterc2 = $marked; break;
						case "1"	: $chkq5masterc1 = $marked; break;
						case "0"	: $chkq5masterc0 = $marked; break;
					}
					switch ($rowgetdebriefing["Q5_MASTERD"])
					{
						case "2"	: $chkq5masterd2 = $marked; break;
						case "1"	: $chkq5masterd1 = $marked; break;
						case "0"	: $chkq5masterd0 = $marked; break;
					}
					switch ($rowgetdebriefing["Q5_MASTERE"])
					{
						case "2"	: $chkq5mastere2 = $marked; break;
						case "1"	: $chkq5mastere1 = $marked; break;
						case "0"	: $chkq5mastere0 = $marked; break;
					}
					switch ($rowgetdebriefing["Q5_MASTERF"])
					{
						case "2"	: $chkq5masterf2 = $marked; break;
						case "1"	: $chkq5masterf1 = $marked; break;
						case "0"	: $chkq5masterf0 = $marked; break;
					}
					switch ($rowgetdebriefing["Q5_MASTERG"])
					{
						case "2"	: $chkq5masterg2 = $marked; break;
						case "1"	: $chkq5masterg1 = $marked; break;
						case "0"	: $chkq5masterg0 = $marked; break;
					}
					
					$name_chiefeng = $rowgetdebriefing["NAME_CHIEFENG"];
					
					switch ($rowgetdebriefing["Q5_CHIEFENGA"])
					{
						case "2"	: $chkq5chiefenga2 = $marked; break;
						case "1"	: $chkq5chiefenga1 = $marked; break;
						case "0"	: $chkq5chiefenga0 = $marked; break;
					}
					switch ($rowgetdebriefing["Q5_CHIEFENGB"])
					{
						case "2"	: $chkq5chiefengb2 = $marked; break;
						case "1"	: $chkq5chiefengb1 = $marked; break;
						case "0"	: $chkq5chiefengb0 = $marked; break;
					}
					switch ($rowgetdebriefing["Q5_CHIEFENGC"])
					{
						case "2"	: $chkq5chiefengc2 = $marked; break;
						case "1"	: $chkq5chiefengc1 = $marked; break;
						case "0"	: $chkq5chiefengc0 = $marked; break;
					}
					switch ($rowgetdebriefing["Q5_CHIEFENGD"])
					{
						case "2"	: $chkq5chiefengd2 = $marked; break;
						case "1"	: $chkq5chiefengd1 = $marked; break;
						case "0"	: $chkq5chiefengd0 = $marked; break;
					}
					switch ($rowgetdebriefing["Q5_CHIEFENGE"])
					{
						case "2"	: $chkq5chiefenge2 = $marked; break;
						case "1"	: $chkq5chiefenge1 = $marked; break;
						case "0"	: $chkq5chiefenge0 = $marked; break;
					}
					switch ($rowgetdebriefing["Q5_CHIEFENGF"])
					{
						case "2"	: $chkq5chiefengf2 = $marked; break;
						case "1"	: $chkq5chiefengf1 = $marked; break;
						case "0"	: $chkq5chiefengf0 = $marked; break;
					}
					switch ($rowgetdebriefing["Q5_CHIEFENGG"])
					{
						case "2"	: $chkq5chiefengg2 = $marked; break;
						case "1"	: $chkq5chiefengg1 = $marked; break;
						case "0"	: $chkq5chiefengg0 = $marked; break;
					}
					
					$name_chiefcook = $rowgetdebriefing["NAME_CHIEFCOOK"];
					
					switch ($rowgetdebriefing["Q6_CHIEFCOOKA"])
					{
						case "2"	: $chkq6chiefcooka2 = $marked; break;
						case "1"	: $chkq6chiefcooka1 = $marked; break;
						case "0"	: $chkq6chiefcooka0 = $marked; break;
					}
					switch ($rowgetdebriefing["Q6_CHIEFCOOKB"])
					{
						case "2"	: $chkq6chiefcookb2 = $marked; break;
						case "1"	: $chkq6chiefcookb1 = $marked; break;
						case "0"	: $chkq6chiefcookb0 = $marked; break;
					}
					switch ($rowgetdebriefing["Q6_CHIEFCOOKC"])
					{
						case "2"	: $chkq6chiefcookc2 = $marked; break;
						case "1"	: $chkq6chiefcookc1 = $marked; break;
						case "0"	: $chkq6chiefcookc0 = $marked; break;
					}
					switch ($rowgetdebriefing["Q6_CHIEFCOOKD"])
					{
						case "2"	: $chkq6chiefcookd2 = $marked; break;
						case "1"	: $chkq6chiefcookd1 = $marked; break;
						case "0"	: $chkq6chiefcookd0 = $marked; break;
					}
					switch ($rowgetdebriefing["Q6_CHIEFCOOKE"])
					{
						case "2"	: $chkq6chiefcooke2 = $marked; break;
						case "1"	: $chkq6chiefcooke1 = $marked; break;
						case "0"	: $chkq6chiefcooke0 = $marked; break;
					}
					switch ($rowgetdebriefing["Q6_CHIEFCOOKF"])
					{
						case "2"	: $chkq6chiefcookf2 = $marked; break;
						case "1"	: $chkq6chiefcookf1 = $marked; break;
						case "0"	: $chkq6chiefcookf0 = $marked; break;
					}
					
					$q6chiefcookf = $rowgetdebriefing["Q6_CHIEFCOOKF"];
					
					switch ($rowgetdebriefing["Q7A"])
					{
						case "2"	: $chkq7a2 = $marked; break;
						case "1"	: $chkq7a1 = $marked; break;
						case "0"	: $chkq7a0 = $marked; break;
					}
					switch ($rowgetdebriefing["Q7B"])
					{
						case "2"	: $chkq7b2 = $marked; break;
						case "1"	: $chkq7b1 = $marked; break;
						case "0"	: $chkq7b0 = $marked; break;
					}
					switch ($rowgetdebriefing["Q7C"])
					{
						case "2"	: $chkq7c2 = $marked; break;
						case "1"	: $chkq7c1 = $marked; break;
						case "0"	: $chkq7c0 = $marked; break;
					}
					switch ($rowgetdebriefing["Q7D"])
					{
						case "2"	: $chkq7d2 = $marked; break;
						case "1"	: $chkq7d1 = $marked; break;
						case "0"	: $chkq7d0 = $marked; break;
					}
					
					$q7dremarks = $rowgetdebriefing["Q7D_REMARKS"];
						
					$chklast1 = "NO";
					$chklast2 = "NO";
					$chklast3 = "NO";
					$chklast4 = "NO";
					$chklast5 = "NO";
					$chklast6 = "NO";
					$chklast8 = "NO";
						
					if ($rowgetdebriefing["Q8_1"] == 1)
						$chklast1 = "YES";
						
					if ($rowgetdebriefing["Q8_2"] == 1)
						$chklast2 = "YES";
						
					if ($rowgetdebriefing["Q8_3"] == 1)
						$chklast3 = "YES";
						
					if ($rowgetdebriefing["Q8_4"] == 1)
						$chklast4 = "YES";
						
					if ($rowgetdebriefing["Q8_5"] == 1)
						$chklast5 = "YES";
						
					if ($rowgetdebriefing["Q8_6"] == 1)
						$chklast6 = "YES";
						
					$last7 = $rowgetdebriefing["Q8_7"];
						
					if ($rowgetdebriefing["Q8_8"] == 1)
						$chklast8 = "YES";
						
					$last9 = $rowgetdebriefing["Q8_9"];
						
				}
			
			//END OF DATA FROM TABLE
}

include("include/datasheet.inc");


echo "
<html>\n
<head>\n
<title>
Debriefing Form - Arriving Seaman
</title>

<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>


</head>
<body style=\"\">\n

<form name=\"datasheet\" method=\"POST\">\n

	";
	
	$styleborder = "style=\"border-bottom:1px solid Black;\"";
	$styledata = "style=\"font-weight:Bold;color:Blue;text-align:center;\"";
	$styleencode = "style=\"color:Red;font-weight:Bold;\"";
	
	echo "
	<div style=\"margin:5 5 5 10;width:755px;height:100%;\" $disabled>
	
	
		<div style=\"width:80%;height:60px;float:left;\">
			<div style=\"width:85%;float:left;\">
				<center>
					<span style=\"font-size:1em;font-weight:Bold;\">VERITAS MARITIME CORPORATION</span><br />
					<span style=\"font-size:0.8em;font-weight:Bold;\">ARRIVING SEAMAN DEBRIEFING FORM</span><br />
					<span style=\"font-size:0.8em;font-weight:Bold;\">Date: $datenow</span><br />
				";
				if (!empty($crewrecommendedby) && $crewrecommendedby != ' ')
				{
					echo "<span style=\"font-size:0.7em;color:Green;\">Recommended By: $crewrecommendedby</span>";
				}
				
				echo "
				</center>
			</div>
			<div style=\"width:13%;float:right;\">
				<span style=\"font-size:0.7em;font-weight:Bold;\">FM-227</span><br />
				<span style=\"font-size:0.7em;font-weight:Bold;\">REV. AUG 2010</span>
			</div>
		</div>
		
		<div style=\"width:20%;height:60px;float:right;\">
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
		
		<div style=\"width:100%;\">
			<table style=\"width:100%;\">
				<tr>
					<td width=\"42%\"><span style=\"font-size:0.70em;\">NAME:</span> <br /> <span style=\"font-size:0.9em;font-weight:Bold;color:Blue;\">$crewname</span></td>
					<td width=\"20%\"><span style=\"font-size:0.70em;\">CREWCODE:</span> <br /><span style=\"font-size:0.9em;font-weight:Bold;color:Blue;\">$crewcode</span></td>
					<td width=\"20%\"><span style=\"font-size:0.70em;\">APPLICANT NO:</span> <br /><span style=\"font-size:0.9em;font-weight:Bold;color:Blue;\">$applicantno</span></td>
					<td width=\"18%\"><span style=\"font-size:0.70em;\">REPORT DATE:</span> <br /><span style=\"font-size:0.9em;font-weight:Bold;color:Blue;\">$reporteddate</span></td>
				</tr>
			</table>
			<hr />
		</div>
			
		<div style=\"width:100%;\">
		
			<table style=\"width:40%;font-size:0.75em;float:left;\" cellspacing=\"0\">
				<tr $mouseovereffect>
					<td width=\"30%\" valign=\"top\">Current Address</td>
					<td width=\"2%\" valign=\"top\">:</td>
					<td $styledata>$crewaddress&nbsp;</a></td>
				</tr>
				<tr $mouseovereffect>
					<td valign=\"top\">Land Line</td>
					<td valign=\"top\">:</td>
					<td $styledata>$crewtelno1&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td>Cellphone No.</td>
					<td valign=\"top\">:</td>
					<td $styledata>$crewmobile&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td>Weight (kls)</td>
					<td valign=\"top\">:</td>
					<td $styledata>$crewweight&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td>Height (cm)</td>
					<td valign=\"top\">:</td>
					<td $styledata>$crewheight&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td>Civil Status</td>
					<td valign=\"top\">:</td>
					<td $styledata>$crewcivilstatus&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td>Allottee Name</td>
					<td valign=\"top\">:</td>
					<td $styledata>
					";
					if ($crewkinrelation == "WIFE")
						echo "$crewkinname&nbsp;";
					echo "
					</td>
				</tr>
				<tr $mouseovereffect>
					<td>Allottee Cellphone No.</td>
					<td valign=\"top\">:</td>
					<td $styledata>
					";
					if ($crewkinrelation == "WIFE")
						echo "$crewkintelno&nbsp;";
					echo "
					</td>
				</tr>
				<tr $mouseovereffect>
					<td>Email Address</td>
					<td valign=\"top\">:</td>
					<td $styledata>$crewemail&nbsp;</td>
				</tr>
			</table>

			<table style=\"width:35%;font-size:0.75em;float:left;border-left:1px solid Black;\" cellspacing=\"0\" cellpadding=\"2\">
				<tr $mouseovereffect>
					<td width=\"40%\" valign=\"top\">Vessel</td>
					<td width=\"2%\" valign=\"top\">:</td>
					<td $styledata>$vesselname&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td valign=\"top\">Rank</td>
					<td valign=\"top\">:</td>
					<td $styledata>$rankalias&nbsp;</td>
				</tr>
				$rankalias2
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
			
			<table style=\"width:23%;font-size:0.75em;float:left;\" border=1 cellspacing=\"0\">
				<tr>
					<td align=\"left\" colspan=\"2\"><b>DOCUMENTS TURN-OVER</b></td>
				</tr>
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
			
		</div>
		
		<div style=\"width:97%;\">
			";
			
			while ($rowdocuments = mysql_fetch_array($qrydocuments))
			{
				$doccode = $rowdocuments["DOCCODE1"];
				
				if (!empty($rowdocuments["DOCNO"]) && $rowdocuments["DOCNO"] != " ")
					$docno = $rowdocuments["DOCNO"];
				else 
					$docno = "N/A";
				
				if (!empty($rowdocuments["DATEISSUED"]))
					$dateissued = date($dateformat,strtotime($rowdocuments["DATEISSUED"]));
				else 
					$dateissued = "N/A";
				
				if (!empty($rowdocuments["DATEEXPIRED"]))
					$dateexpired = date($dateformat,strtotime($rowdocuments["DATEEXPIRED"]));
				else 
					$dateexpired = "N/A";
					
				$doccode1 = "docno" . $doccode;
					
				switch ($doccode)
				{
					case "F1"	: $$doccode1=$docno; $issuedF1 = $dateissued; break; //PHIL LICENSE
					case "F2"	: $$doccode1=$docno; $expiredF2 = $dateexpired; break; //PHIL SEAMANS BOOK
					case "41"	: $$doccode1=$docno; $expired41 = $dateexpired; break; //PASSPORT
					case "32"	: $$doccode1=$docno; $expired32 = $dateexpired; break; //SRC
					case "18"	: $$doccode1=$docno; $expired18 = $dateexpired; break; //COC FOR OFFICERS
					case "C0"	: $$doccode1=$docno; $expiredC0 = $dateexpired; break; //COC FOR RATINGS
					case "P1"	: $$doccode1=$docno; $expiredP1 = $dateexpired; break; //PANAMA LICENSE
					case "P2"	: $$doccode1=$docno; $expiredP2 = $dateexpired; break; //PANAMA SEAMANS BOOK
					case "42"	: $$doccode1=$docno; $expired42 = $dateexpired; break; //U.S. VISA
					case "A4"	: $$doccode1=$docno; $expiredA4 = $dateexpired; break; //AUSTRALIAN VISA
					case "51"	: $$doccode1=$docno; $expired51 = $dateexpired; break; //YELLOW FEVER
//					case "40"	: $$doccode1=$docno; $expired40 = $dateexpired; break; //BSC (BASIC SAFETY COURSES)
					case "16"	: $$doccode1=$docno; $expired16 = $dateexpired; break; //ENDORSEMENT
				}
				
			}
			
			//GET "BSC" BASIC SAFETY COURSES - DOCCODE='40' under TYPE='C' (CERTIFICATES)
			
//			$qrygetdoc40 = mysql_query("SELECT ccs.DOCCODE,ccs.DOCNO,ccs.DATEISSUED,ccs.GRADE,ccs.RANKCODE
//										FROM crewcertstatus ccs
//										WHERE ccs.DOCCODE ='40'
//										AND ccs.APPLICANTNO=$applicantno") or die(mysql_error());
//			
//			if (mysql_num_rows($qrygetdoc40) > 0)
//			{
//				$rowgetdoc40 = mysql_fetch_array($qrygetdoc40);
//				
////				$doccode = $rowgetdoc40["DOCCODE"];
//				
//				if (!empty($rowgetdoc40["DOCNO"]) && $rowgetdoc40["DOCNO"] != " ")
//					$docno40 = $rowgetdoc40["DOCNO"];
//				else 
//					$docno40 = "N/A";
//				
//				if (!empty($rowgetdoc40["DATEISSUED"]))
//					$issued40 = date($dateformat,strtotime($rowgetdoc40["DATEISSUED"]));
//				else 
//					$issued40 = "N/A";
//				
//			}
			
			//END OF "BSC"
			
			echo "
			<table style=\"width:50%;font-size:0.70em;float:left;\" border=1 cellspacing=\"0\">
				<tr>
					<td align=\"left\" colspan=\"6\"><b>PHILIPPINE DOCUMENTS</b></td>
				</tr>
				<tr $mouseovereffect>
					<td width=\"25%\">PHIL LICENSE</td>
					<td width=\"35%\" $styledata align=\"center\">$docnoF1&nbsp;</td>
					<td width=\"10%\">Issued</td>
					<td width=\"20%\" $styledata align=\"center\">$issuedF1&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td>PHIL SCDB#</td>
					<td $styledata align=\"center\">$docnoF2&nbsp;</td>
					<td>Valid</td>
					<td $styledata align=\"center\">$expiredF2&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td>PASSPORT#</td>
					<td $styledata align=\"center\">$docno41&nbsp;</td>
					<td>Valid</td>
					<td $styledata align=\"center\">$expired41&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td>SRC#(IF OLD)</td>
					<td $styledata align=\"center\">$docno32&nbsp;</td>
					<td>Valid</td>
					<td $styledata align=\"center\">$expired32&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td>C O C#</td>
					<td $styledata align=\"center\">
					";
					if ($docno18 == "N/A")
						echo $docnoC0;
					else 
						echo $docno18;
					echo "&nbsp;
					</td>
					<td>Valid</td>
					<td $styledata align=\"center\">
					";
					if ($docno18 == "N/A")
						echo $expiredC0;
					else 
						echo $expired18;
					echo "&nbsp;
					</td>
				</tr>
				<tr $mouseovereffect>
					<td>Endorsement</td>
					<td $styledata align=\"center\">$docno16&nbsp;</td>
					<td>Valid</td>
					<td $styledata align=\"center\">$expired16&nbsp;</td>
				</tr>
			</table>
			
			<table style=\"width:49%;font-size:0.70em;float:right;\" border=1 cellspacing=\"0\">
				<tr>
					<td align=\"left\" colspan=\"6\"><b>FOREIGN DOCUMENTS</b></td>
				</tr>
				<tr $mouseovereffect>
					<td width=\"25%\">PANAMA BK</td>
					<td width=\"35%\" $styledata align=\"center\">$docnoP2&nbsp;</td>
					<td width=\"10%\">Valid</td>
					<td width=\"20%\" $styledata align=\"center\">$expiredP2&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td>PANAMA LIC</td>
					<td $styledata align=\"center\">$docnoP1&nbsp;</td>
					<td>Valid</td>
					<td $styledata align=\"center\">$expiredP1&nbsp;</td>
				</tr>
<!--
				<tr $mouseovereffect>
					<td>BSC</td>
					<td $styledata align=\"center\">$docno40&nbsp;</td>
					<td>Issued</td>
					<td $styledata align=\"center\">$issued40&nbsp;</td>
				</tr>
-->
				<tr $mouseovereffect>
					<td>U.S. VISA</td>
					<td $styledata align=\"center\">$docno42&nbsp;</td>
					<td>Valid</td>
					<td $styledata align=\"center\">$expired42&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td>AU MCV</td>
					<td $styledata align=\"center\">$docnoA4&nbsp;</td>
					<td>Valid</td>
					<td $styledata align=\"center\">$expiredA4&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td>YELLOW FEVER</td>
					<td $styledata align=\"center\">$docno51&nbsp;</td>
					<td>Valid</td>
					<td $styledata align=\"center\">$expired51&nbsp;</td>
				</tr>
			</table>
		</div>
			
			<span style=\"font-size:0.7em;font-weight:Bold;color:Red;\">
				<i>Reminder: Passport not less than 18 months from the date of departure. All other Documents not less than one (1) year.</i>
			</span>
		
		<div style=\"width:100%;\">
<!--
			<table style=\"width:100%;font-size:0.75em;border:1px solid black;\" border=1 cellspacing=\"0\">
				<tr>
					<td colspan=\"5\" align=\"left\"><b>ADDITIONAL DOCUMENTS TO SURRENDER TO VERITAS</b></td>
				</tr>
				<tr>
					<td width=\"20%\">PASSPORT</td>
					<td width=\"20%\">SEAMAN'S BOOK</td>
					<td width=\"20%\">&nbsp;</td>
					<td width=\"20%\">&nbsp;</td>
					<td width=\"20%\">&nbsp;</td>
				</tr>
			</table>
-->
			<table style=\"width:97%;font-size:0.8em;border:1px solid black;\" cellspacing=\"0\">
				<tr>
					<td align=\"left\"><b>UNDERTAKING</b></td>
				</tr>
				<tr $mouseovereffect>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I hereby undertake to process my expiring documents starting &nbsp;&nbsp;$undertakingdate1<input type=\"hidden\" name=\"undertakingdate1\" $styledata readonly=\"readonly\" value=\"$undertakingdate1\" onKeyPress=\"return dateonly(this);\" onkeydown=\"if(event.keyCode==13){if(this.value!=''){chkdate(this);}}\" size=\"10\" maxlength=\"10\" />
						&nbsp; to be completed on &nbsp;&nbsp;$undertakingdate2 <input type=\"hidden\" name=\"undertakingdate2\" $styledata readonly=\"readonly\" value=\"$undertakingdate2\" onKeyPress=\"return dateonly(this);\" onkeydown=\"if(event.keyCode==13){if(this.value!=''){chkdate(this);}}\" size=\"10\" maxlength=\"10\" /> </td>
				</tr>
			</table>
			
		</div>
		
		<div style=\"width:97%;\">		
			
			<table style=\"width:100%;font-size:0.70em;\" cellspacing=\"0\">
				<tr>
					<td align=\"left\" colspan=\"4\"><b>Crew Comments (Your honest answers are very important to us.)</b></td>
				</tr>
				<tr $mouseovereffect>
					<td width=\"60%\" style=\"font-weight:Bold;\">I. Did you like your job?</td>
					<td width=\"10%\" align=\"center\">[&nbsp;$chkq1yes&nbsp;] - YES</td>
					<td width=\"10%\" align=\"center\">[&nbsp;$chkq1no&nbsp;] - NO</td>
					<td width=\"20%\" align=\"center\">[&nbsp;$chkq1uncertain&nbsp;] - Uncertain</td>
				</tr>
				<tr $mouseovereffect>
					<td $styleborder colspan=\"4\">Reason $q1remarks <input type=\"hidden\" readonly=\"readonly\" $styledata name=\"q1remarks\" value=\"$q1remarks\" size=\"40\" /></td>
				</tr>
				<tr $mouseovereffect>
					<td style=\"font-weight:Bold;\">II. Was there too much pressure in your job?</td>
					<td align=\"center\">[&nbsp;$chkq2yes&nbsp;] - YES</td>
					<td align=\"center\">[&nbsp;$chkq2no&nbsp;] - NO</td>
					<td align=\"center\">[&nbsp;$chkq2uncertain&nbsp;] - Uncertain</td>
				</tr>
				<tr $mouseovereffect>
					<td $styleborder colspan=\"4\">Explain $q2remarks<input type=\"hidden\" readonly=\"readonly\" $styledata name=\"q2remarks\" value=\"$q2remarks\" size=\"40\" /></td>
				</tr>
				<tr $mouseovereffect>
					<td style=\"font-weight:Bold;\">III. Were there any obstacles that prevented you from doing your job while onboard?&nbsp;
						<input type=\"hidden\" readonly=\"readonly\" $styledata name=\"q3aremarks\" value=\"$q3aremarks\" size=\"40\" />
						$q3aremarks
					</td>
					<td align=\"center\">[&nbsp;$chkq3ayes&nbsp;] - YES</td>
					<td align=\"center\">[&nbsp;$chkq3ano&nbsp;] - NO</td>
					<td align=\"center\">[&nbsp;$chkq3auncertain&nbsp;] - Uncertain</td>
				</tr>
				<tr $mouseovereffect>
					<td style=\"font-weight:Bold;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Were there any obstacles that prevented you from doing your job while onshore?
						<input type=\"hidden\" readonly=\"readonly\" $styledata name=\"q3bremarks\" value=\"$q3bremarks\" size=\"40\" />
						$q3bremarks
					</td>
					<td align=\"center\">[&nbsp;$chkq3byes&nbsp;] - YES</td>
					<td align=\"center\">[&nbsp;$chkq3bno&nbsp;] - NO</td>
					<td align=\"center\">[&nbsp;$chkq3buncertain&nbsp;] - Uncertain</td>
				</tr>
				<tr $mouseovereffect>
					<td style=\"font-weight:Bold;\">IV. How would you evaluate the training you received onboard?</td>
					<td colspan=\"2\" align=\"center\">[&nbsp;$chkq4asuff&nbsp;] - SUFFICIENT</td>
					<td align=\"center\">[&nbsp;$chkq4ainsuff&nbsp;] - INSUFFICIENT</td>
				</tr>
				<tr $mouseovereffect>
					<td style=\"font-weight:Bold;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;How would you evaluate the training you received onshore?</td>
					<td colspan=\"2\" align=\"center\">[&nbsp;$chkq4bsuff&nbsp;] - SUFFICIENT</td>
					<td align=\"center\">[&nbsp;$chkq4binsuff&nbsp;] - INSUFFICIENT</td>
				</tr>
				<tr $mouseovereffect>
					<td $styleborder style=\"font-weight:Bold;\">
						While in Manila do you stay in?<br />
						How much do you pay per day for Hotel/Lodging House?
						<input type=\"hidden\" $styledata name=\"q4cremarks\" readonly=\"readonly\" value=\"$q4cremarks\" size=\"10\" />
						$q4cremarks
					</td>
					<td $styleborder colspan=\"3\">
									[&nbsp;$chkq4cH&nbsp;] - Hotel &nbsp;&nbsp;&nbsp;&nbsp;
									[&nbsp;$chkq4cL&nbsp;] - Lodging House &nbsp;&nbsp;&nbsp;&nbsp;
									[&nbsp;$chkq4cR&nbsp;] - w/ Relatives <br />
									[&nbsp;$chkq4cO&nbsp;] - Own House
					</td>
				</tr>
				<tr $mouseovereffect>
					<td style=\"font-weight:Bold;\" colspan=\"4\">V. How would you rate your Master / Chief Engineer using the following scales?</td>
				</tr>
			</table>
		</div>
		
		<div style=\"width:97%;\">
			
			<table style=\"width:49%;float:left;font-size:0.70em;\" border=1 cellspacing=\"0\">
				<tr $mouseovereffect>
					<td width=\"10%\">Master:</td>
					<td width=\"45%\">
						<input type=\"hidden\" name=\"name_master\" readonly=\"readonly\" $styledata value=\"$name_master\" />
						$name_master
					</td>
					<td width=\"15%\" align=\"center\">Good</td>
					<td width=\"15%\" align=\"center\" style=\"font-size:0.9em;\">Satisfactory</td>
					<td width=\"15%\" align=\"center\">Poor</td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">a)</td>
					<td>Relationship with crew</td>
					<td align=\"center\">$chkq5mastera2&nbsp;</td>
					<td align=\"center\">$chkq5mastera1&nbsp;</td>
					<td align=\"center\">$chkq5mastera0&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">b)</td>
					<td>Attitude towards work</td>
					<td align=\"center\">$chkq5masterb2&nbsp;</td>
					<td align=\"center\">$chkq5masterb1&nbsp;</td>
					<td align=\"center\">$chkq5masterb0&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">c)</td>
					<td>Well-liked by crew</td>
					<td align=\"center\">$chkq5masterc2&nbsp;</td>
					<td align=\"center\">$chkq5masterc1&nbsp;</td>
					<td align=\"center\">$chkq5masterc0&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">d)</td>
					<td>Concern for crew</td>
					<td align=\"center\">$chkq5masterd2&nbsp;</td>
					<td align=\"center\">$chkq5masterd1&nbsp;</td>
					<td align=\"center\">$chkq5masterd0&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">e)</td>
					<td>Ability to communicate</td>
					<td align=\"center\">$chkq5mastere2&nbsp;</td>
					<td align=\"center\">$chkq5mastere1&nbsp;</td>
					<td align=\"center\">$chkq5mastere0&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">f)</td>
					<td>Food management</td>
					<td align=\"center\">$chkq5masterf2&nbsp;</td>
					<td align=\"center\">$chkq5masterf1&nbsp;</td>
					<td align=\"center\">$chkq5masterf0&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">g)</td>
					<td>Over-All Performance</td>
					<td align=\"center\">$chkq5masterg2&nbsp;</td>
					<td align=\"center\">$chkq5masterg1&nbsp;</td>
					<td align=\"center\">$chkq5masterg0&nbsp;</td>
				</tr>
			</table>
			
			<table style=\"width:49%;float:right;font-size:0.70em;\" border=1 cellspacing=\"0\">
				<tr $mouseovereffect>
					<td width=\"10%\">Ch.Engr:</td>
					<td width=\"45%\">
						<input type=\"hidden\" name=\"name_chiefeng\" readonly=\"readonly\" $styledata value=\"$name_chiefeng\" />
						$name_chiefeng
					</td>
					<td width=\"15%\" align=\"center\">Good</td>
					<td width=\"15%\" align=\"center\" style=\"font-size:0.9em;\">Satisfactory</td>
					<td width=\"15%\" align=\"center\"\">Poor</td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">a)</td>
					<td>Relationship with crew</td>
					<td align=\"center\">$chkq5chiefenga2&nbsp;</td>
					<td align=\"center\">$chkq5chiefenga1&nbsp;</td>
					<td align=\"center\">$chkq5chiefenga0&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">b)</td>
					<td>Attitude towards work</td>
					<td align=\"center\">$chkq5chiefengb2&nbsp;</td>
					<td align=\"center\">$chkq5chiefengb1&nbsp;</td>
					<td align=\"center\">$chkq5chiefengb0&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">c)</td>
					<td>Well-liked by crew</td>
					<td align=\"center\">$chkq5chiefengc2&nbsp;</td>
					<td align=\"center\">$chkq5chiefengc1&nbsp;</td>
					<td align=\"center\">$chkq5chiefengc0&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">d)</td>
					<td>Concern for crew</td>
					<td align=\"center\">$chkq5chiefengd2&nbsp;</td>
					<td align=\"center\">$chkq5chiefengd1&nbsp;</td>
					<td align=\"center\">$chkq5chiefengd0&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">e)</td>
					<td>Ability to communicate</td>
					<td align=\"center\">$chkq5chiefenge2&nbsp;</td>
					<td align=\"center\">$chkq5chiefenge1&nbsp;</td>
					<td align=\"center\">$chkq5chiefenge0&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">f)</td>
					<td>Food management</td>
					<td align=\"center\">$chkq5chiefengf2&nbsp;</td>
					<td align=\"center\">$chkq5chiefengf1&nbsp;</td>
					<td align=\"center\">$chkq5chiefengf0&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">g)</td>
					<td>Over-All Performance</td>
					<td align=\"center\">$chkq5chiefengg2&nbsp;</td>
					<td align=\"center\">$chkq5chiefengg1&nbsp;</td>
					<td align=\"center\">$chkq5chiefengg0&nbsp;</td>
				</tr>
			</table>
			
		</div>
		
		<br />
		
		<div style=\"width:97%;\">

			<table style=\"width:48%;float:left;font-size:0.70em;\" border=1 cellspacing=\"0\">
				<tr>
					<td align=\"left\" colspan=\"5\"><b>VI. How would you rate your Chief Cook and the food he served?</b></td>
				</tr>
				<tr $mouseovereffect>
					<td width=\"10%\">C/Ck:</td>
					<td width=\"45%\">
						<input type=\"hidden\" name=\"name_chiefcook\" readonly=\"readonly\" $styledata value=\"$name_chiefcook\" />
						$name_chiefcook
					</td>
					<td width=\"15%\" align=\"center\">Good</td>
					<td width=\"15%\" align=\"center\" style=\"font-size:0.9em;\">Satisfactory</td>
					<td width=\"15%\" align=\"center\"\">Poor</td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">a)</td>
					<td>Food quantity sufficient</td>
					<td align=\"center\">$chkq6chiefcooka2&nbsp;</td>
					<td align=\"center\">$chkq6chiefcooka1&nbsp;</td>
					<td align=\"center\">$chkq6chiefcooka0&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">b)</td>
					<td>Food quality/taste acceptable</td>
					<td align=\"center\">$chkq6chiefcookb2&nbsp;</td>
					<td align=\"center\">$chkq6chiefcookb1&nbsp;</td>
					<td align=\"center\">$chkq6chiefcookb0&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">c)</td>
					<td>Hygiene and sanitation</td>
					<td align=\"center\">$chkq6chiefcookc2&nbsp;</td>
					<td align=\"center\">$chkq6chiefcookc1&nbsp;</td>
					<td align=\"center\">$chkq6chiefcookc0&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">d)</td>
					<td>Food Handling & Management</td>
					<td align=\"center\">$chkq6chiefcookd2&nbsp;</td>
					<td align=\"center\"> $chkq6chiefcookd1&nbsp;</td>
					<td align=\"center\">$chkq6chiefcookd0&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">e)</td>
					<td>Human Relations</td>
					<td align=\"center\">$chkq6chiefcooke2&nbsp;</td>
					<td align=\"center\">$chkq6chiefcooke1&nbsp;</td>
					<td align=\"center\">$chkq6chiefcooke0&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">f)</td>
					<td>Suggestions</td>
					<td align=\"left\" colspan=\"3\"><input type=\"hidden\" readonly=\"readonly\" name=\"q6chiefcookf\" $styledata value=\"$q6chiefcookf\" size=\"15\" />&nbsp;$q6chiefcookf</td>
				</tr>
			</table>
			
			<table style=\"width:48%;float:right;font-size:0.70em;\" border=1 cellspacing=\"0\">
				<tr>
					<td align=\"left\" colspan=\"5\"><b>VII. Gears/Office Administration/Others</b></td>
				</tr>
				<tr $mouseovereffect>
					<td width=\"10%\">&nbsp;</td>
					<td width=\"45%\">&nbsp;</td>
					<td width=\"15%\" align=\"center\">Good</td>
					<td width=\"15%\" align=\"center\" style=\"font-size:0.9em;\">Satisfactory</td>
					<td width=\"15%\" align=\"center\"\">Poor</td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">a)</td>
					<td>Safety Gears</td>
					<td align=\"center\">$chkq7a2&nbsp;</td>
					<td align=\"center\">$chkq7a1&nbsp;</td>
					<td align=\"center\">$chkq7a0&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">b)</td>
					<td>Benefit Package</td>
					<td align=\"center\">$chkq7b2&nbsp;</td>
					<td align=\"center\">$chkq7b1&nbsp;</td>
					<td align=\"center\">$chkq7b0&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">c)</td>
					<td>Welfare Service, Work conditions, Recreational facilities</td>
					<td align=\"center\">$chkq7c2&nbsp;</td>
					<td align=\"center\">$chkq7c1&nbsp;</td>
					<td align=\"center\">$chkq7c0&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">d)</td>
					<td>Others, specify <br /><input type=\"hidden\" readonly=\"readonly\" $styledata name=\"q7dremarks\" value=\"$q7dremarks\" size=\"15\" />$q7dremarks</td>
					<td align=\"center\">$chkq7d2&nbsp;</td>
					<td align=\"center\">$chkq7d1&nbsp;</td>
					<td align=\"center\">$chkq7d0&nbsp;</td>
				</tr>
				<tr><td colspan=\"5\">&nbsp;</td></tr>
				<tr>
					<td colspan=\"2\" align=\"left\">Certified true and correct:</td>
					<td colspan=\"3\" style=\"border-bottom:1px solid Black;\">&nbsp;</td>
				</tr>
				<tr>
					<td colspan=\"2\">&nbsp;</td>
					<td colspan=\"3\" align=\"center\" style=\"font-size:0.7em;\">(Signature of Crew)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date</td>
				</tr>
			</table>
		</div>
		<div style=\"width:97%;font-size:0.70em;\">&nbsp;</div>
<div style=\"width:100%;\">
		
<table width=\"97%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" style=\"width:97%;font-size:0.70em;\">
  <tr align=\"left\">
    <td colspan=\"4\">&nbsp;VIII. COMPUTER PROFICIENCY</td>
  </tr>
  <tr align=\"left\">
    <td>&nbsp;Do you have an email address?</td>
    <td width=\"9%\" align=\"center\"><strong>&nbsp;$chklast1</strong></td>
    <td>&nbsp;Do you have an internet connection in your house?</td>
    <td width=\"9%\" align=\"center\"><strong>&nbsp;$chklast6</strong></td>
  </tr>
  <tr align=\"left\">
    <td>&nbsp;Does your family have an email address?</td>
    <td align=\"center\"><strong>&nbsp;$chklast2</strong></td>
    <td>&nbsp;If not, how do you access your email?</td>
    <td align=\"center\"><strong>&nbsp;$last7</strong></td>
  </tr>
  <tr align=\"left\">
    <td>&nbsp;Do you have a personal laptop or computer?</td>
    <td align=\"center\"><strong>&nbsp;$chklast3</strong></td>
    <td>&nbsp;Are you aware of the Veritas Official website?</td>
    <td align=\"center\"><strong>&nbsp;$chklast8</strong></td>
  </tr>
  <tr align=\"left\">
    <td >&nbsp;Do you have a family computer in your house?</td>
    <td align=\"center\"><strong>&nbsp;$chklast4</strong></td>
    <td>&nbsp;How often do you access it?</td>
    <td align=\"center\"><strong>&nbsp;$last9</strong></td>
  </tr>
  <tr align=\"left\">
    <td>&nbsp;Is there an internet shop near home?</td>
    <td align=\"center\"><strong>&nbsp;$chklast5</strong></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
			
		</div>
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