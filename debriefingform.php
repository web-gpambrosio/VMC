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

if(isset($_SESSION["employeeid"]))
	$employeeid=$_SESSION["employeeid"];
else 
	$employeeid = "CRW";

if (isset($_POST["actiontxt"]))
	$actiontxt = $_POST["actiontxt"];
	
if (isset($_POST["applicantno"]))
	$applicantno = $_POST["applicantno"];

if (empty($applicantno))
{
	if (isset($_GET["applicantno"]))
		$applicantno = $_GET["applicantno"];
	else 
	{
		$applicantno = 1;
//		$disabled = "disabled=\"disabled\"";
	}
}

if ($applicantno != 1)
	$disabled = "";


if (isset($_POST["load"]))
	$load = $_POST["load"];
else 
	$load = $_GET["load"];

if (isset($_POST["ccid"]))
	$ccid = $_POST["ccid"];
	
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

	$q1 = "NULL";
	$q2 = "NULL";
	$q3a = "NULL";
	$q3b = "NULL";
	$q4a = "NULL";
	$q4b = "NULL";
	$q4c = "NULL";
	
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
	
	$q5mastera = "NULL";
	$q5masterb = "NULL";
	$q5masterc = "NULL";
	$q5masterd = "NULL";
	$q5mastere = "NULL";
	$q5masterf = "NULL";
	$q5masterg = "NULL";
	
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
	
	$q5chiefenga = "NULL";
	$q5chiefengb = "NULL";
	$q5chiefengc = "NULL";
	$q5chiefengd = "NULL";
	$q5chiefenge = "NULL";
	$q5chiefengf = "NULL";
	$q5chiefengg = "NULL";
	
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
	
	$q6chiefcooka = "NULL";
	$q6chiefcookb = "NULL";
	$q6chiefcookc = "NULL";
	$q6chiefcookd = "NULL";
	$q6chiefcooke = "NULL";
	
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
	
	$q7a = "NULL";
	$q7b = "NULL";
	$q7c = "NULL";
	$q7d = "NULL";
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

	
if (isset($_POST["chkseafarersman"]))
{
	$chkseafarersman = 1;
	$checkdoc1 = $checked;
}

if (isset($_POST["chktrainingman"]))
{
	$chktrainingman = 1;
	$checkdoc2 = $checked;
}

if (isset($_POST["chkpayoffslip"]))
{
	$chkpayoffslip = 1;
	$checkdoc3 = $checked;
}

if (isset($_POST["chkcompanyman"]))
{
	$chkcompanyman = 1;
	$checkdoc4 = $checked;
}

if (isset($_POST["chkfujitransman"]))
{
	$chkfujitransman = 1;
	$checkdoc5 = $checked;
}

if (isset($_POST["chkveritasid"]))
{
	$chkveritasid = 1;
	$checkdoc6 = $checked;
}

// END OF DOCUMENT TURN-OVER

if (isset($_POST["undertakingdate1"]))
	$undertakingdate1 = $_POST["undertakingdate1"];

if (isset($_POST["undertakingdate2"]))
	$undertakingdate2 = $_POST["undertakingdate2"];
	
if (isset($_POST["xmyemailadd"]))
	$xmyemailadd = $_POST["xmyemailadd"];


// CREW QUESTIONS

if (isset($_POST["q1"]))
	$q1 = $_POST["q1"];

if (isset($_POST["q1remarks"]))
	$q1remarks = $_POST["q1remarks"];

if (isset($_POST["q2"]))
	$q2 = $_POST["q2"];

if (isset($_POST["q2remarks"]))
	$q2remarks = $_POST["q2remarks"];

if (isset($_POST["q3a"]))
	$q3a = $_POST["q3a"];

if (isset($_POST["q3aremarks"]))
	$q3aremarks = $_POST["q3aremarks"];

if (isset($_POST["q3b"]))
	$q3b = $_POST["q3b"];

if (isset($_POST["q3bremarks"]))
	$q3bremarks = $_POST["q3bremarks"];

if (isset($_POST["q4a"]))
	$q4a = $_POST["q4a"];

if (isset($_POST["q4b"]))
	$q4b = $_POST["q4b"];

if (isset($_POST["q4c"]))
	$q4c = $_POST["q4c"];

if (isset($_POST["q4cremarks"]))
	$q4cremarks = $_POST["q4cremarks"];
	
switch ($q1)
{
	case "1"	: $chkq1yes = $checked; break;
	case "0"	: $chkq1no = $checked; break;
	case "2"	: $chkq1uncertain = $checked; break;
}

switch ($q2)
{
	case "1"	: $chkq2yes = $checked; break;
	case "0"	: $chkq2no = $checked; break;
	case "2"	: $chkq2uncertain = $checked; break;
}

switch ($q3a)
{
	case "1"	: $chkq3ayes = $checked; break;
	case "0"	: $chkq3ano = $checked; break;
	case "2"	: $chkq3auncertain = $checked; break;
}

switch ($q3b)
{
	case "1"	: $chkq3byes = $checked; break;
	case "0"	: $chkq3bno = $checked; break;
	case "2"	: $chkq3buncertain = $checked; break;
}

switch ($q4a)
{
	case "1"	: $chkq4asuff = $checked; break;
	case "0"	: $chkq4ainsuff = $checked; break;
}

switch ($q4b)
{
	case "1"	: $chkq4bsuff = $checked; break;
	case "0"	: $chkq4binsuff = $checked; break;
}

switch ($q4c)
{
	case "1"	: $chkq4cH = $checked; break;
	case "2"	: $chkq4cL = $checked; break;
	case "3"	: $chkq4cR = $checked; break;
	case "4"	: $chkq4cO = $checked; break;
}

// ------------------------------------------------------------------


if (isset($_POST["name_master"]))
	$name_master = $_POST["name_master"];
	
if (isset($_POST["q5mastera"]))
	$q5mastera = $_POST["q5mastera"];

if (isset($_POST["q5masterb"]))
	$q5masterb = $_POST["q5masterb"];
	
if (isset($_POST["q5masterc"]))
	$q5masterc = $_POST["q5masterc"];

if (isset($_POST["q5masterd"]))
	$q5masterd = $_POST["q5masterd"];

if (isset($_POST["q5mastere"]))
	$q5mastere = $_POST["q5mastere"];
	
if (isset($_POST["q5masterf"]))
	$q5masterf = $_POST["q5masterf"];

if (isset($_POST["q5masterg"]))
	$q5masterg = $_POST["q5masterg"];

switch ($q5mastera)
{
	case "2"	: $chkq5mastera2 = $checked; break;
	case "1"	: $chkq5mastera1 = $checked; break;
	case "0"	: $chkq5mastera0 = $checked; break;
}
switch ($q5masterb)
{
	case "2"	: $chkq5masterb2 = $checked; break;
	case "1"	: $chkq5masterb1 = $checked; break;
	case "0"	: $chkq5masterb0 = $checked; break;
}
switch ($q5masterc)
{
	case "2"	: $chkq5masterc2 = $checked; break;
	case "1"	: $chkq5masterc1 = $checked; break;
	case "0"	: $chkq5masterc0 = $checked; break;
}
switch ($q5masterd)
{
	case "2"	: $chkq5masterd2 = $checked; break;
	case "1"	: $chkq5masterd1 = $checked; break;
	case "0"	: $chkq5masterd0 = $checked; break;
}
switch ($q5mastere)
{
	case "2"	: $chkq5mastere2 = $checked; break;
	case "1"	: $chkq5mastere1 = $checked; break;
	case "0"	: $chkq5mastere0 = $checked; break;
}
switch ($q5masterf)
{
	case "2"	: $chkq5masterf2 = $checked; break;
	case "1"	: $chkq5masterf1 = $checked; break;
	case "0"	: $chkq5masterf0 = $checked; break;
}
switch ($q5masterg)
{
	case "2"	: $chkq5masterg2 = $checked; break;
	case "1"	: $chkq5masterg1 = $checked; break;
	case "0"	: $chkq5masterg0 = $checked; break;
}

if (isset($_POST["name_chiefeng"]))
	$name_chiefeng = $_POST["name_chiefeng"];	

if (isset($_POST["q5chiefenga"]))
	$q5chiefenga = $_POST["q5chiefenga"];

if (isset($_POST["q5chiefengb"]))
	$q5chiefengb = $_POST["q5chiefengb"];
	
if (isset($_POST["q5chiefengc"]))
	$q5chiefengc = $_POST["q5chiefengc"];

if (isset($_POST["q5chiefengd"]))
	$q5chiefengd = $_POST["q5chiefengd"];

if (isset($_POST["q5chiefenge"]))
	$q5chiefenge = $_POST["q5chiefenge"];
	
if (isset($_POST["q5chiefengf"]))
	$q5chiefengf = $_POST["q5chiefengf"];

if (isset($_POST["q5chiefengg"]))
	$q5chiefengg = $_POST["q5chiefengg"];

switch ($q5chiefenga)
{
	case "2"	: $chkq5chiefenga2 = $checked; break;
	case "1"	: $chkq5chiefenga1 = $checked; break;
	case "0"	: $chkq5chiefenga0 = $checked; break;
}
switch ($q5chiefengb)
{
	case "2"	: $chkq5chiefengb2 = $checked; break;
	case "1"	: $chkq5chiefengb1 = $checked; break;
	case "0"	: $chkq5chiefengb0 = $checked; break;
}
switch ($q5chiefengc)
{
	case "2"	: $chkq5chiefengc2 = $checked; break;
	case "1"	: $chkq5chiefengc1 = $checked; break;
	case "0"	: $chkq5chiefengc0 = $checked; break;
}
switch ($q5chiefengd)
{
	case "2"	: $chkq5chiefengd2 = $checked; break;
	case "1"	: $chkq5chiefengd1 = $checked; break;
	case "0"	: $chkq5chiefengd0 = $checked; break;
}
switch ($q5chiefenge)
{
	case "2"	: $chkq5chiefenge2 = $checked; break;
	case "1"	: $chkq5chiefenge1 = $checked; break;
	case "0"	: $chkq5chiefenge0 = $checked; break;
}
switch ($q5chiefengf)
{
	case "2"	: $chkq5chiefengf2 = $checked; break;
	case "1"	: $chkq5chiefengf1 = $checked; break;
	case "0"	: $chkq5chiefengf0 = $checked; break;
}
switch ($q5chiefengg)
{
	case "2"	: $chkq5chiefengg2 = $checked; break;
	case "1"	: $chkq5chiefengg1 = $checked; break;
	case "0"	: $chkq5chiefengg0 = $checked; break;
}

if (isset($_POST["name_chiefcook"]))
	$name_chiefcook = $_POST["name_chiefcook"];	

if (isset($_POST["q6chiefcooka"]))
	$q6chiefcooka = $_POST["q6chiefcooka"];

if (isset($_POST["q6chiefcookb"]))
	$q6chiefcookb = $_POST["q6chiefcookb"];
	
if (isset($_POST["q6chiefcookc"]))
	$q6chiefcookc = $_POST["q6chiefcookc"];

if (isset($_POST["q6chiefcookd"]))
	$q6chiefcookd = $_POST["q6chiefcookd"];

if (isset($_POST["q6chiefcooke"]))
	$q6chiefcooke = $_POST["q6chiefcooke"];

if (isset($_POST["q6chiefcookf"]))
	$q6chiefcookf = $_POST["q6chiefcookf"]; //SUGGESTION

switch ($q6chiefcooka)
{
	case "2"	: $chkq6chiefcooka2 = $checked; break;
	case "1"	: $chkq6chiefcooka1 = $checked; break;
	case "0"	: $chkq6chiefcooka0 = $checked; break;
}
switch ($q6chiefcookb)
{
	case "2"	: $chkq6chiefcookb2 = $checked; break;
	case "1"	: $chkq6chiefcookb1 = $checked; break;
	case "0"	: $chkq6chiefcookb0 = $checked; break;
}
switch ($q6chiefcookc)
{
	case "2"	: $chkq6chiefcookc2 = $checked; break;
	case "1"	: $chkq6chiefcookc1 = $checked; break;
	case "0"	: $chkq6chiefcookc0 = $checked; break;
}
switch ($q6chiefcookd)
{
	case "2"	: $chkq6chiefcookd2 = $checked; break;
	case "1"	: $chkq6chiefcookd1 = $checked; break;
	case "0"	: $chkq6chiefcookd0 = $checked; break;
}
switch ($q6chiefcooke)
{
	case "2"	: $chkq6chiefcooke2 = $checked; break;
	case "1"	: $chkq6chiefcooke1 = $checked; break;
	case "0"	: $chkq6chiefcooke0 = $checked; break;
}

if (isset($_POST["q7a"]))
	$q7a = $_POST["q7a"];

if (isset($_POST["q7b"]))
	$q7b = $_POST["q7b"];
	
if (isset($_POST["q7c"]))
	$q7c = $_POST["q7c"];
	
if (isset($_POST["q7d"]))
	$q7d = $_POST["q7d"];
	
if (isset($_POST["q7dremarks"]))
	$q7dremarks = $_POST["q7dremarks"];

switch ($q7a)
{
	case "2"	: $chkq7a2 = $checked; break;
	case "1"	: $chkq7a1 = $checked; break;
	case "0"	: $chkq7a0 = $checked; break;
}
switch ($q7b)
{
	case "2"	: $chkq7b2 = $checked; break;
	case "1"	: $chkq7b1 = $checked; break;
	case "0"	: $chkq7b0 = $checked; break;
}
switch ($q7c)
{
	case "2"	: $chkq7c2 = $checked; break;
	case "1"	: $chkq7c1 = $checked; break;
	case "0"	: $chkq7c0 = $checked; break;
}
switch ($q7d)
{
	case "2"	: $chkq7d2 = $checked; break;
	case "1"	: $chkq7d1 = $checked; break;
	case "0"	: $chkq7d0 = $checked; break;
}
	

$checked = "checked=\"checked\"";
	
if (isset($_POST["last1"]))
{
	$last1 = 1;
	$chklast1 = $checked;
}
else
{
	$last1 = 0;
	$chklast1 = "";
}
	
if (isset($_POST["last2"]))
{
	$last2 = 1;
	$chklast2 = $checked;
}
else
{
	$last2 = 0;
	$chklast2 = "";
}
	
if (isset($_POST["last3"]))
{
	$last3 = 1;
	$chklast3 = $checked;
}
else
{
	$last3 = 0;
	$chklast3 = "";
}
	
if (isset($_POST["last4"]))
{
	$last4 = 1;
	$chklast4 = $checked;
}
else
{
	$last4 = 0;
	$chklast4 = "";
}
	
if (isset($_POST["last5"]))
{
	$last5 = 1;
	$chklast5 = $checked;
}
else
{
	$last5 = 0;
	$chklast5 = "";
}
	
if (isset($_POST["last6"]))
{
	$last6 = 1;
	$chklast6 = $checked;
}
else
{
	$last6 = 0;
	$chklast6 = "";
}
	
if (isset($_POST["last7"]))   //text field
	$last7 = $_POST["last7"];
	
if (isset($_POST["last8"]))
{
	$last8 = 1;
	$chklast8 = $checked;
}
else
{
	$last8 = 0;
	$chklast8 = "";
}
	
if (isset($_POST["last9"]))   //text field
	$last9 = $_POST["last9"];
	

//if ($applicantno > 1)
//{

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
					$disabled = "";
				}
				elseif (mysql_num_rows($qrysearch) > 1)  //MULTIPLE ENTRY FOUND
				{
					$showmultiple = "visibility:show;";
					$multiple = 1;
				}
				else 
				{
					$applicantno="";
//					$errormsg = "Search Key -- '$searchkey' Not Found.";
					echo "<script>alert('Search Key: $searchkey - Not Found. Please try again. ');</script>";
					$disabled = "disabled=\"disabled\"";
				}
				
			
			break;
			
		case "save"	:
				if (!empty($undertakingdate1))
					$undertakingdate1raw = "'" . date("Y-m-d",strtotime($undertakingdate1)) . "'";
				else 
					$undertakingdate1raw = "NULL";
			
				if (!empty($undertakingdate2))
					$undertakingdate2raw = "'" . date("Y-m-d",strtotime($undertakingdate2)) . "'";
				else 
					$undertakingdate2raw = "NULL";
			
					
				$q1remarks = mysql_real_escape_string($q1remarks);
				$q2remarks = mysql_real_escape_string($q2remarks);
				$q3aremarks = mysql_real_escape_string($q3aremarks);
				$q3bremarks = mysql_real_escape_string($q3bremarks);
				$q4cremarks = mysql_real_escape_string($q4cremarks);
				$q7dremarks = mysql_real_escape_string($q7dremarks);
				$q6chiefcookf = mysql_real_escape_string($q6chiefcookf);
					
				$qryupdate = mysql_query("UPDATE debriefinghdr SET 
											FILLUPDATE = '$currentdate',
											SEAFARERSMANUAL = $chkseafarersman,
											TRAININGBOOK = $chktrainingman,
											PAYOFFSLIP = $chkpayoffslip,
											COMPANYMANUAL = $chkcompanyman,
											FUJITRANSMANUAL = $chkfujitransman,
											VERITASID = $chkveritasid,
											UNDERTAKINGDATE1 = $undertakingdate1raw,
											UNDERTAKINGDATE2 = $undertakingdate2raw,
											Q1 = $q1,
											Q1_REMARKS = '$q1remarks',
											Q2 = $q2,
											Q2_REMARKS = '$q2remarks',
											Q3A = $q3a,
											Q3A_REMARKS = '$q3aremarks',
											Q3B = $q3b,
											Q3B_REMARKS = '$q3bremarks',
											Q4A = $q4a,
											Q4B = $q4b,
											Q4C = $q4c,
											Q4C_REMARKS = '$q4cremarks',
											NAME_MASTER = '$name_master',
											Q5_MASTERA = $q5mastera,
											Q5_MASTERB = $q5masterb,
											Q5_MASTERC = $q5masterc,
											Q5_MASTERD = $q5masterd,
											Q5_MASTERE = $q5mastere,
											Q5_MASTERF = $q5masterf,
											Q5_MASTERG = $q5masterg,
											NAME_CHIEFENG = '$name_chiefeng',
											Q5_CHIEFENGA = $q5chiefenga,
											Q5_CHIEFENGB = $q5chiefengb,
											Q5_CHIEFENGC = $q5chiefengc,
											Q5_CHIEFENGD = $q5chiefengd,
											Q5_CHIEFENGE = $q5chiefenge,
											Q5_CHIEFENGF = $q5chiefengf,
											Q5_CHIEFENGG = $q5chiefengg,
											NAME_CHIEFCOOK = '$name_chiefcook',
											Q6_CHIEFCOOKA = $q6chiefcooka,
											Q6_CHIEFCOOKB = $q6chiefcookb,
											Q6_CHIEFCOOKC = $q6chiefcookc,
											Q6_CHIEFCOOKD = $q6chiefcookd,
											Q6_CHIEFCOOKE = $q6chiefcooke,
											Q6_CHIEFCOOKF = '$q6chiefcookf',
											Q7A = $q7a,
											Q7B = $q7b,
											Q7C = $q7c,
											Q7D = $q7d,
											Q7D_REMARKS = '$q7dremarks',
											Q8_1 = $last1,
											Q8_2 = $last2,
											Q8_3 = $last3,
											Q8_4 = $last4,
											Q8_5 = $last5,
											Q8_6 = $last6,
											Q8_7 = '$last7',
											Q8_8 = $last8,
											Q8_9 = '$last9'
									WHERE CCID=$ccid
									") or die(mysql_error());
			
$paint_query=mysql_query("SELECT cr.APPLICANTNO As paintapplicantno FROM crewchange cr LEFT JOIN crew c ON c.APPLICANTNO=cr.APPLICANTNO 
						  where cr.ccid='$ccid'");	  
	$cpaint_query=mysql_num_rows($paint_query);
	if ($cpaint_query != 0) {
		$paintapplicantno=mysql_result($paint_query,0,"paintapplicantno");
		mysql_query("update crew set EMAIL='$xmyemailadd' where APPLICANTNO='$paintapplicantno'");
	}

				$q1remarks = stripslashes($q1remarks);
				$q2remarks = stripslashes($q2remarks);
				$q3aremarks = stripslashes($q3aremarks);
				$q3bremarks = stripslashes($q3bremarks);
				$q4cremarks = stripslashes($q4cremarks);
				$q7dremarks = stripslashes($q7dremarks);
				$q6chiefcookf = stripslashes($q6chiefcookf);

			break;
	}
	
	
	if (!empty($applicantno))
	{
		$qrygetheader = mysql_query("SELECT * FROM (
										SELECT cc.APPLICANTNO,cc.CCID,cc.DEPMNLDATE,v.VESSEL,cc.DISEMBREASONCODE,d.REASON,r.ALIAS1 AS RANKALIAS,
										IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,
										cc.ARRMNLDATE,dh.STATUS AS DEBRIEFSTATUS,cc.DATEEMB,
										dh.REPORTEDDATE,dh.AVAILABILITY,dh.JOININGMONTH
										FROM crewchange cc
										LEFT JOIN vessel v ON v.VESSELCODE=cc.VESSELCODE
										LEFT JOIN disembarkreason d ON d.DISEMBREASONCODE=cc.DISEMBREASONCODE
										LEFT JOIN rank r ON r.RANKCODE=cc.RANKCODE
										LEFT JOIN debriefinghdr dh ON dh.CCID=cc.CCID
										WHERE dh.STATUS = 0
									) x
									WHERE DATEDISEMB BETWEEN '2007-01-01' AND CURRENT_DATE
									AND APPLICANTNO=$applicantno
									ORDER BY DATEDISEMB DESC
									LIMIT 1
								") or die(mysql_error());
		
	//	$qrygetheader = mysql_query("SELECT cc.APPLICANTNO,cc.CCID,cc.DEPMNLDATE,v.VESSEL,cc.DISEMBREASONCODE,d.REASON,r.ALIAS1 AS RANKALIAS,
	//									IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,
	//									cc.ARRMNLDATE,dh.STATUS AS DEBRIEFSTATUS,cc.DATEEMB,
	//									dh.REPORTEDDATE,dh.AVAILABILITY,dh.JOININGMONTH
	//									FROM crewchange cc
	//									LEFT JOIN crew c ON c.APPLICANTNO=cc.APPLICANTNO
	//									LEFT JOIN vessel v ON v.VESSELCODE=cc.VESSELCODE
	//									LEFT JOIN rank r ON r.RANKCODE=cc.RANKCODE
	//									LEFT JOIN disembarkreason dr ON dr.DISEMBREASONCODE=cc.DISEMBREASONCODE
	//									LEFT JOIN debriefinghdr dh ON dh.CCID=cc.CCID
	//									WHERE cc.APPLICANTNO=$applicantno
	//									ORDER BY DATEDISEMB
	//									LIMIT 2
	//							") or die(mysql_error());
		
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
				$rankalias2 = "";
				
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
														WHERE dh.STATUS = 0
													) x
													WHERE DATEDISEMB BETWEEN '2007-01-01' AND CURRENT_DATE
													AND APPLICANTNO=$applicantno
													ORDER BY DATEDISEMB DESC
													LIMIT 1
												") or die(mysql_error());
					
						if (mysql_num_rows($qrygetdebriefing) > 0)
						{
							$rowgetdebriefing = mysql_fetch_array($qrygetdebriefing);
							
							if ($rowgetdebriefing["SEAFARERSMANUAL"] == 1)
							{
								$chkseafarersman = 1;
								$checkdoc1 = $checked;
							}
							
							if ($rowgetdebriefing["TRAININGBOOK"] == 1)
							{
								$chktrainingman = 1;
								$checkdoc2 = $checked;
							}
							
							if ($rowgetdebriefing["PAYOFFSLIP"] == 1)
							{
								$chkpayoffslip = 1;
								$checkdoc3 = $checked;
							}
							
							if ($rowgetdebriefing["COMPANYMANUAL"] == 1)
							{
								$chkcompanyman = 1;
								$checkdoc4 = $checked;
							}
							
							if ($rowgetdebriefing["FUJITRANSMANUAL"] == 1)
							{
								$chkfujitransman = 1;
								$checkdoc5 = $checked;
							}
							
							if ($rowgetdebriefing["VERITASID"] == 1)
							{
								$chkveritasid = 1;
								$checkdoc6 = $checked;
							}
							
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
								case "1"	: $chkq1yes = $checked; break;
								case "0"	: $chkq1no = $checked; break;
								case "2"	: $chkq1uncertain = $checked; break;
							}
							
							$q1remarks = stripslashes($rowgetdebriefing["Q1_REMARKS"]);
							
							switch ($rowgetdebriefing["Q2"])
							{
								case "1"	: $chkq2yes = $checked; break;
								case "0"	: $chkq2no = $checked; break;
								case "2"	: $chkq2uncertain = $checked; break;
							}
							
							$q2remarks = stripslashes($rowgetdebriefing["Q2_REMARKS"]);
							
							switch ($rowgetdebriefing["Q3A"])
							{
								case "1"	: $chkq3ayes = $checked; break;
								case "0"	: $chkq3ano = $checked; break;
								case "2"	: $chkq3auncertain = $checked; break;
							}
							
							$q3aremarks = stripslashes($rowgetdebriefing["Q3A_REMARKS"]);
							
							switch ($rowgetdebriefing["Q3B"])
							{
								case "1"	: $chkq3byes = $checked; break;
								case "0"	: $chkq3bno = $checked; break;
								case "2"	: $chkq3buncertain = $checked; break;
							}
							
							$q3bremarks = stripslashes($rowgetdebriefing["Q3B_REMARKS"]);
							
							switch ($rowgetdebriefing["Q4A"])
							{
								case "1"	: $chkq4asuff = $checked; break;
								case "0"	: $chkq4ainsuff = $checked; break;
							}
							
							switch ($rowgetdebriefing["Q4B"])
							{
								case "1"	: $chkq4bsuff = $checked; break;
								case "0"	: $chkq4binsuff = $checked; break;
							}
							
							switch ($rowgetdebriefing["Q4C"])
							{
								case "1"	: $chkq4cH = $checked; break;
								case "2"	: $chkq4cL = $checked; break;
								case "3"	: $chkq4cR = $checked; break;
								case "4"	: $chkq4cO = $checked; break;
							}
							
							$q4cremarks = stripslashes($rowgetdebriefing["Q4C_REMARKS"]);
							
							$name_master = $rowgetdebriefing["NAME_MASTER"];
							
							switch ($rowgetdebriefing["Q5_MASTERA"])
							{
								case "2"	: $chkq5mastera2 = $checked; break;
								case "1"	: $chkq5mastera1 = $checked; break;
								case "0"	: $chkq5mastera0 = $checked; break;
							}
							switch ($rowgetdebriefing["Q5_MASTERB"])
							{
								case "2"	: $chkq5masterb2 = $checked; break;
								case "1"	: $chkq5masterb1 = $checked; break;
								case "0"	: $chkq5masterb0 = $checked; break;
							}
							switch ($rowgetdebriefing["Q5_MASTERC"])
							{
								case "2"	: $chkq5masterc2 = $checked; break;
								case "1"	: $chkq5masterc1 = $checked; break;
								case "0"	: $chkq5masterc0 = $checked; break;
							}
							switch ($rowgetdebriefing["Q5_MASTERD"])
							{
								case "2"	: $chkq5masterd2 = $checked; break;
								case "1"	: $chkq5masterd1 = $checked; break;
								case "0"	: $chkq5masterd0 = $checked; break;
							}
							switch ($rowgetdebriefing["Q5_MASTERE"])
							{
								case "2"	: $chkq5mastere2 = $checked; break;
								case "1"	: $chkq5mastere1 = $checked; break;
								case "0"	: $chkq5mastere0 = $checked; break;
							}
							switch ($rowgetdebriefing["Q5_MASTERF"])
							{
								case "2"	: $chkq5masterf2 = $checked; break;
								case "1"	: $chkq5masterf1 = $checked; break;
								case "0"	: $chkq5masterf0 = $checked; break;
							}
							switch ($rowgetdebriefing["Q5_MASTERG"])
							{
								case "2"	: $chkq5masterg2 = $checked; break;
								case "1"	: $chkq5masterg1 = $checked; break;
								case "0"	: $chkq5masterg0 = $checked; break;
							}
							
							$name_chiefeng = $rowgetdebriefing["NAME_CHIEFENG"];
							
							switch ($rowgetdebriefing["Q5_CHIEFENGA"])
							{
								case "2"	: $chkq5chiefenga2 = $checked; break;
								case "1"	: $chkq5chiefenga1 = $checked; break;
								case "0"	: $chkq5chiefenga0 = $checked; break;
							}
							switch ($rowgetdebriefing["Q5_CHIEFENGB"])
							{
								case "2"	: $chkq5chiefengb2 = $checked; break;
								case "1"	: $chkq5chiefengb1 = $checked; break;
								case "0"	: $chkq5chiefengb0 = $checked; break;
							}
							switch ($rowgetdebriefing["Q5_CHIEFENGC"])
							{
								case "2"	: $chkq5chiefengc2 = $checked; break;
								case "1"	: $chkq5chiefengc1 = $checked; break;
								case "0"	: $chkq5chiefengc0 = $checked; break;
							}
							switch ($rowgetdebriefing["Q5_CHIEFENGD"])
							{
								case "2"	: $chkq5chiefengd2 = $checked; break;
								case "1"	: $chkq5chiefengd1 = $checked; break;
								case "0"	: $chkq5chiefengd0 = $checked; break;
							}
							switch ($rowgetdebriefing["Q5_CHIEFENGE"])
							{
								case "2"	: $chkq5chiefenge2 = $checked; break;
								case "1"	: $chkq5chiefenge1 = $checked; break;
								case "0"	: $chkq5chiefenge0 = $checked; break;
							}
							switch ($rowgetdebriefing["Q5_CHIEFENGF"])
							{
								case "2"	: $chkq5chiefengf2 = $checked; break;
								case "1"	: $chkq5chiefengf1 = $checked; break;
								case "0"	: $chkq5chiefengf0 = $checked; break;
							}
							switch ($rowgetdebriefing["Q5_CHIEFENGG"])
							{
								case "2"	: $chkq5chiefengg2 = $checked; break;
								case "1"	: $chkq5chiefengg1 = $checked; break;
								case "0"	: $chkq5chiefengg0 = $checked; break;
							}
							
							$name_chiefcook = $rowgetdebriefing["NAME_CHIEFCOOK"];
							
							switch ($rowgetdebriefing["Q6_CHIEFCOOKA"])
							{
								case "2"	: $chkq6chiefcooka2 = $checked; break;
								case "1"	: $chkq6chiefcooka1 = $checked; break;
								case "0"	: $chkq6chiefcooka0 = $checked; break;
							}
							switch ($rowgetdebriefing["Q6_CHIEFCOOKB"])
							{
								case "2"	: $chkq6chiefcookb2 = $checked; break;
								case "1"	: $chkq6chiefcookb1 = $checked; break;
								case "0"	: $chkq6chiefcookb0 = $checked; break;
							}
							switch ($rowgetdebriefing["Q6_CHIEFCOOKC"])
							{
								case "2"	: $chkq6chiefcookc2 = $checked; break;
								case "1"	: $chkq6chiefcookc1 = $checked; break;
								case "0"	: $chkq6chiefcookc0 = $checked; break;
							}
							switch ($rowgetdebriefing["Q6_CHIEFCOOKD"])
							{
								case "2"	: $chkq6chiefcookd2 = $checked; break;
								case "1"	: $chkq6chiefcookd1 = $checked; break;
								case "0"	: $chkq6chiefcookd0 = $checked; break;
							}
							switch ($rowgetdebriefing["Q6_CHIEFCOOKE"])
							{
								case "2"	: $chkq6chiefcooke2 = $checked; break;
								case "1"	: $chkq6chiefcooke1 = $checked; break;
								case "0"	: $chkq6chiefcooke0 = $checked; break;
							}
							switch ($rowgetdebriefing["Q6_CHIEFCOOKF"])
							{
								case "2"	: $chkq6chiefcookf2 = $checked; break;
								case "1"	: $chkq6chiefcookf1 = $checked; break;
								case "0"	: $chkq6chiefcookf0 = $checked; break;
							}
							
							$q6chiefcookf = stripslashes($rowgetdebriefing["Q6_CHIEFCOOKF"]);
							
							switch ($rowgetdebriefing["Q7A"])
							{
								case "2"	: $chkq7a2 = $checked; break;
								case "1"	: $chkq7a1 = $checked; break;
								case "0"	: $chkq7a0 = $checked; break;
							}
							switch ($rowgetdebriefing["Q7B"])
							{
								case "2"	: $chkq7b2 = $checked; break;
								case "1"	: $chkq7b1 = $checked; break;
								case "0"	: $chkq7b0 = $checked; break;
							}
							switch ($rowgetdebriefing["Q7C"])
							{
								case "2"	: $chkq7c2 = $checked; break;
								case "1"	: $chkq7c1 = $checked; break;
								case "0"	: $chkq7c0 = $checked; break;
							}
							switch ($rowgetdebriefing["Q7D"])
							{
								case "2"	: $chkq7d2 = $checked; break;
								case "1"	: $chkq7d1 = $checked; break;
								case "0"	: $chkq7d0 = $checked; break;
							}
							
							$q7dremarks = stripslashes($rowgetdebriefing["Q7D_REMARKS"]);
								
							$chklast1 = "";
							$chklast2 = "";
							$chklast3 = "";
							$chklast4 = "";
							$chklast5 = "";
							$chklast6 = "";
							$chklast8 = "";
								
							if ($rowgetdebriefing["Q8_1"] == 1)
								$chklast1 = "checked=\"checked\"";
								
							if ($rowgetdebriefing["Q8_2"] == 1)
								$chklast2 = "checked=\"checked\"";
								
							if ($rowgetdebriefing["Q8_3"] == 1)
								$chklast3 = "checked=\"checked\"";
								
							if ($rowgetdebriefing["Q8_4"] == 1)
								$chklast4 = "checked=\"checked\"";
								
							if ($rowgetdebriefing["Q8_5"] == 1)
								$chklast5 = "checked=\"checked\"";
								
							if ($rowgetdebriefing["Q8_6"] == 1)
								$chklast6 = "checked=\"checked\"";
								
							$last7 = $rowgetdebriefing["Q8_7"];
								
							if ($rowgetdebriefing["Q8_8"] == 1)
								$chklast8 = "checked=\"checked\"";
								
							$last9 = $rowgetdebriefing["Q8_9"];
						}
					
					//END OF DATA FROM TABLE
		}
	
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

<script>

if (document.layers) { // Netscape
    document.captureEvents(Event.MOUSEMOVE);
    document.onmousemove = captureMousePosition;
} else if (document.all) { // Internet Explorer
    document.onmousemove = captureMousePosition;
} else if (document.getElementById) { // Netcsape 6
    document.onmousemove = captureMousePosition;
}



//Mouse Position Function

//Usage :
//	document.getElementById('name_of_div').style.left=xMousePos+75;
//	document.getElementById('name_of_div').style.top=yMousePos-20;


// Global variables
xMousePos = 0; // Horizontal position of the mouse on the screen
yMousePos = 0; // Vertical position of the mouse on the screen
xMousePosMax = 0; // Width of the page
yMousePosMax = 0; // Height of the page

function captureMousePosition(e) {
    if (document.layers) {
        // When the page scrolls in Netscape, the event's mouse position
        // reflects the absolute position on the screen. innerHight/Width
        // is the position from the top/left of the screen that the user is
        // looking at. pageX/YOffset is the amount that the user has
        // scrolled into the page. So the values will be in relation to
        // each other as the total offsets into the page, no matter if
        // the user has scrolled or not.
        xMousePos = e.pageX;
        yMousePos = e.pageY;
        xMousePosMax = window.innerWidth+window.pageXOffset;
        yMousePosMax = window.innerHeight+window.pageYOffset;
    } else if (document.all) {
        // When the page scrolls in IE, the event's mouse position
        // reflects the position from the top/left of the screen the
        // user is looking at. scrollLeft/Top is the amount the user
        // has scrolled into the page. clientWidth/Height is the height/
        // width of the current page the user is looking at. So, to be
        // consistent with Netscape (above), add the scroll offsets to
        // both so we end up with an absolute value on the page, no
        // matter if the user has scrolled or not.
        xMousePos = window.event.x+document.body.scrollLeft;
        yMousePos = window.event.y+document.body.scrollTop;
        xMousePosMax = document.body.clientWidth+document.body.scrollLeft;
        yMousePosMax = document.body.clientHeight+document.body.scrollTop;
    } else if (document.getElementById) {
        // Netscape 6 behaves the same as Netscape 4 in this regard
        xMousePos = e.pageX;
        yMousePos = e.pageY;
        xMousePosMax = window.innerWidth+window.pageXOffset;
        yMousePosMax = window.innerHeight+window.pageYOffset;
    }
}


</script>

</head>
<body style=\"overflow:hidden;\">\n

<form name=\"debriefingform\" method=\"POST\">\n

<span class=\"wintitle\">ARRIVING SEAMAN - DEBRIEFING FORM</span>

<div style=\"width:100%;height:50px;background-color:#DCDCDC;overflow:hidden;padding:10px;\">
	<center>
	<table width=\"80%\" style=\"font-size:0.8em;font-weight:Bold;\">
		<tr>
			<th>Search Key &nbsp;&nbsp;
				<select name=\"searchby\" onchange=\"searchkey.focus();\">

				";

				$selected1 = "";
				$selected2 = "";
				$selected3 = "";
				$selected4 = "";

				switch ($searchby)
				{
					case "1"	: //BY APPLICANT NO
							$selected1 = "SELECTED";
						break;
					case "2"	: //BY CREW CODE
							$selected2 = "SELECTED";
						break;
					case "3"	: //BY FAMILY NAME
							$selected3 = "SELECTED";
						break;
					case "4"	: //BY GIVEN NAME
							$selected4 = "SELECTED";
						break;
				}

			echo "
					<option $selected3 value=\"3\">FAMILY NAME</option>
					<option $selected1 value=\"1\">APPLICANT NO</option>
					<option $selected2 value=\"2\">CREW CODE</option>
					<option $selected4 value=\"4\">GIVEN NAME</option>
				</select>
			</th>
			<td><input type=\"text\" name=\"searchkey\" size=\"40\" value=\"$searchkey\" onkeyup=\"this.value=this.value.toUpperCase()\" 
					style=\"color:Red;font-weight:Bold;\" onkeydown=\"if(event.keyCode==13) {btnfind.focus();}\"/>
					
				<input type=\"button\" name=\"btnfind\" value=\"Find\" onclick=\"actiontxt.value='find';submit();\" />
			</td>
			<td>
				<input type=\"button\" name=\"btnprint\" value=\"Print\" 
					onclick=\"openWindow('debriefingformshow.php?applicantno=$applicantno&ccid=$ccid&load=1&print=1', 'debriefingfleet' ,0, 0);\" />
			</td>
			<td>
				<input type=\"button\" value=\"Save Form\" $disabled onclick=\"if(undertakingdate1.value != '' && undertakingdate2.value != '') { if(last1.checked==true && xmyemailadd.value=='') { alert('Please enter your Email Address.'); } else { actiontxt.value='save';submit(); } } else { alert('Please enter Date Range in the UNDERTAKING Section.'); }\" />
			</td>
		</tr>
	</table>
	</center>
</div>

							<div id=\"multiple\" style=\"position:absolute;left:112px;width:600px;height:400px;background-color:#6699FF;
											border:2px solid black;overflow:auto;$showmultiple \">
								<span class=\"sectiontitle\">MULTIPLE ENTRY FOUND&nbsp;-&nbsp;
									<a href=\"#\" onclick=\"document.getElementById('multiple').style.display='none';\">[Close Window]</a>
								</span>
								<br />
								
								<table width=\"100%\" class=\"listcol\">
									<tr>
										<th width=\"15%\">APPLICANT NO</th>
										<th width=\"15%\">CREW CODE</th>
										<th width=\"20%\">FNAME</th>
										<th width=\"20%\">GNAME</th>
										<th width=\"20%\">MNAME</th>
										<th width=\"10%\">STATUS</th>
									</tr>
								";
									if ($multiple == 1)
									{
										while ($rowmultisearch = mysql_fetch_array($qrysearch))
										{
											$appno = $rowmultisearch["APPLICANTNO"];
											
											$qrygetinfo = mysql_query("SELECT APPLICANTNO,CREWCODE,FNAME,GNAME,MNAME,STATUS,EMAIL
																		FROM crew 
																		WHERE APPLICANTNO=$appno
																	") or die(mysql_error());
											
											$rowgetinfo = mysql_fetch_array($qrygetinfo);
							
											$info1 = $rowgetinfo["APPLICANTNO"];
											$info2 = $rowgetinfo["CREWCODE"];
											$info3 = $rowgetinfo["FNAME"];
											$info4 = $rowgetinfo["GNAME"];
											$info5 = $rowgetinfo["MNAME"];
											$info_email = $rowgetinfo["EMAIL"];
											if ($rowgetinfo["STATUS"] == 1)
												$info6 = "ACTIVE";
											else 
												$info6 = "INACTIVE";
											
											echo "
											<tr $mouseovereffect style=\"cursor:pointer;\" 
															ondblclick=\"load.value='1';applicantno.value='$info1';submit();
															document.getElementById('multiple').style.display='none';
															\">
												<td align=\"center\">$info1</td>
												<td align=\"center\">$info2</td>
												<td>$info3&nbsp;</td>
												<td>$info4&nbsp;</td>
												<td>$info5&nbsp;</td>
												<td align=\"center\">$info6</td>
											</tr>
											
											";
										}
									}
										
								echo "
								</table>
								<br />
								<center>
									<a href=\"#\" style=\"font-size:0.8em;font-weight:Bold;\" onclick=\"document.getElementById('multiple').style.display='none';\">Close Window</a>
								</center>
								<br />
							</div>

	<center>
	";
	
	$styleborder = "style=\"border-bottom:1px solid Black;\"";
	$styledata = "style=\"font-weight:Bold;color:Blue;text-align:center;\"";
	$styleencode = "style=\"color:Red;font-weight:Bold;\"";
	
	echo "
	<div style=\"margin:5 5 5 5;width:755px;height:600px;border:1px solid black;overflow:auto;\" $disabled>
	
		<div style=\"width:80%;height:60px;float:left;background-color:White;\">
			<div style=\"width:85%;float:left;\">
				<center>
					<span style=\"font-size:1em;font-weight:Bold;\">VERITAS MARITIME CORPORATION</span><br />
					<span style=\"font-size:0.9em;font-weight:Bold;\">ARRIVING SEAMAN DEBRIEFING FORM</span><br />
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
				<span style=\"font-size:0.7em;font-weight:Bold;\">REV. August 2010</span>
			</div>
		</div>
		
		<div style=\"width:20%;float:right;\">
	";
			$dirfilename = "$basedir/$applicantno.JPG";
			if (checkpath($dirfilename))
			{
				$scale = imageScale($dirfilename,-1,98);
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
		
		<div style=\"width:100%;background-color:White;\">
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
		
		<div style=\"width:100%;background-color:White;\">
			
			<table style=\"width:40%;font-size:0.75em;float:left;\" cellspacing=\"0\">
				<tr $mouseovereffect>
					<td width=\"30%\" valign=\"top\">
						<a href=\"#\" $styleencode onclick=\"document.getElementById('formeditfile').src='formeditwindow.php?applicantno=$applicantno&ccid=$ccid&action=1';
								document.getElementById('formeditwindow').style.left=xMousePos+30;
								document.getElementById('formeditwindow').style.top=yMousePos-20;
								document.getElementById('formeditwindow').style.width=500;
								document.getElementById('formeditfile').style.width=500;
								document.getElementById('formeditfile').style.height=300;
								document.getElementById('formeditwindow').style.height=300;
								document.getElementById('formeditwindow').style.display='block';\" >
						Current Address
						</a>
					</td>
					<td width=\"2%\" valign=\"top\">:</td>
					<td $styledata>$crewaddress&nbsp;</a></td>
				</tr>
				<tr $mouseovereffect>
					<td valign=\"top\">
						<a href=\"#\" $styleencode onclick=\"document.getElementById('formeditfile').src='formeditwindow.php?applicantno=$applicantno&ccid=$ccid&action=2';
								document.getElementById('formeditwindow').style.left=xMousePos+30;
								document.getElementById('formeditwindow').style.top=yMousePos-20;
								document.getElementById('formeditwindow').style.width=300;
								document.getElementById('formeditfile').style.width=300;
								document.getElementById('formeditfile').style.height=200;
								document.getElementById('formeditwindow').style.height=200;
								document.getElementById('formeditwindow').style.display='block';\" >
						Land Line
						</a>
					</td>
					<td valign=\"top\">:</td>
					<td $styledata>$crewtelno1&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td><a href=\"#\" $styleencode onclick=\"document.getElementById('formeditfile').src='formeditwindow.php?applicantno=$applicantno&ccid=$ccid&action=3';
								document.getElementById('formeditwindow').style.left=xMousePos+30;
								document.getElementById('formeditwindow').style.top=yMousePos-20;
								document.getElementById('formeditwindow').style.width=400;
								document.getElementById('formeditfile').style.width=400;
								document.getElementById('formeditfile').style.height=250;
								document.getElementById('formeditwindow').style.height=250;
								document.getElementById('formeditwindow').style.display='block';\" >
						Cellphone No.
						</a>
					</td>
					<td valign=\"top\">:</td>
					<td $styledata>$crewmobile&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td><a href=\"#\" $styleencode onclick=\"document.getElementById('formeditfile').src='formeditwindow.php?applicantno=$applicantno&ccid=$ccid&action=4';
								document.getElementById('formeditwindow').style.left=xMousePos+30;
								document.getElementById('formeditwindow').style.top=yMousePos-20;
								document.getElementById('formeditwindow').style.width=300;
								document.getElementById('formeditfile').style.width=300;
								document.getElementById('formeditfile').style.height=200;
								document.getElementById('formeditwindow').style.height=200;
								document.getElementById('formeditwindow').style.display='block';\" >
						Weight (kls)
						</a>
					</td>
					<td valign=\"top\">:</td>
					<td $styledata>$crewweight&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td><a href=\"#\" $styleencode onclick=\"document.getElementById('formeditfile').src='formeditwindow.php?applicantno=$applicantno&ccid=$ccid&action=5';
								document.getElementById('formeditwindow').style.left=xMousePos+30;
								document.getElementById('formeditwindow').style.top=yMousePos-20;
								document.getElementById('formeditwindow').style.width=300;
								document.getElementById('formeditfile').style.width=300;
								document.getElementById('formeditfile').style.height=200;
								document.getElementById('formeditwindow').style.height=200;
								document.getElementById('formeditwindow').style.display='block';\" >
						Height (cm)
						</a>
					</td>
					<td valign=\"top\">:</td>
					<td $styledata>$crewheight&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td><a href=\"#\" $styleencode onclick=\"document.getElementById('formeditfile').src='formeditwindow.php?applicantno=$applicantno&ccid=$ccid&action=6';
								document.getElementById('formeditwindow').style.left=xMousePos+30;
								document.getElementById('formeditwindow').style.top=yMousePos-20;
								document.getElementById('formeditwindow').style.width=300;
								document.getElementById('formeditfile').style.width=300;
								document.getElementById('formeditfile').style.height=200;
								document.getElementById('formeditwindow').style.height=200;
								document.getElementById('formeditwindow').style.display='block';\" >
						Civil Status
						</a>
					</td>
					<td valign=\"top\">:</td>
					<td $styledata>$crewcivilstatus&nbsp;</td>
				</tr>
				<tr $mouseovereffect>
					<td><a href=\"#\" $styleencode onclick=\"document.getElementById('formeditfile').src='formeditwindow.php?applicantno=$applicantno&ccid=$ccid&action=7';
								document.getElementById('formeditwindow').style.left=xMousePos+30;
								document.getElementById('formeditwindow').style.top=yMousePos-20;
								document.getElementById('formeditwindow').style.width=400;
								document.getElementById('formeditfile').style.width=400;
								document.getElementById('formeditfile').style.height=250;
								document.getElementById('formeditwindow').style.height=250;
								document.getElementById('formeditwindow').style.display='block';\" >
						Allottee Name
						</a>
					</td>
					<td valign=\"top\">:</td>
					<td $styledata>
					";
					// if ($crewkinrelation == "WIFE")
						echo "$crewkinname&nbsp;";
					echo "
					</td>
				</tr>
				<tr $mouseovereffect>
					<td>
						Allottee Cellphone No.
					</td>
					<td valign=\"top\">:</td>
					<td $styledata>
					";
					
// onclick=\"document.getElementById('formeditfile').src='formeditwindow.php?applicantno=$applicantno&ccid=$ccid&action=8';
								// document.getElementById('formeditwindow').style.left=xMousePos+30;
								// document.getElementById('formeditwindow').style.top=yMousePos-20;
								// document.getElementById('formeditwindow').style.width=400;
								// document.getElementById('formeditfile').style.width=400;
								// document.getElementById('formeditfile').style.height=200;
								// document.getElementById('formeditwindow').style.height=200;
								// document.getElementById('formeditwindow').style.display='block';\"
					
					// if ($crewkinrelation == "WIFE")
						echo "$crewkintelno&nbsp;";
					echo "
					</td>
				</tr>
				<tr $mouseovereffect>
					<td>
						Email Address
					</td>
					<td valign=\"top\">:</td>
					<td $styledata>
					";
					
// onclick=\"document.getElementById('formeditfile').src='formeditwindow.php?applicantno=$applicantno&ccid=$ccid&action=8';
								// document.getElementById('formeditwindow').style.left=xMousePos+30;
								// document.getElementById('formeditwindow').style.top=yMousePos-20;
								// document.getElementById('formeditwindow').style.width=400;
								// document.getElementById('formeditfile').style.width=400;
								// document.getElementById('formeditfile').style.height=200;
								// document.getElementById('formeditwindow').style.height=200;
								// document.getElementById('formeditwindow').style.display='block';\"
					
					// if ($crewkinrelation == "WIFE")
						echo "$info_email&nbsp;";
					echo "
					</td>
				</tr>
			</table>
			";

			
			echo "
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
					<td><a href=\"#\" $styleencode onclick=\"document.getElementById('formeditfile').src='formeditwindow.php?applicantno=$applicantno&ccid=$ccid&action=9';
								document.getElementById('formeditwindow').style.left=xMousePos+30;
								document.getElementById('formeditwindow').style.top=yMousePos-20;
								document.getElementById('formeditwindow').style.width=300;
								document.getElementById('formeditfile').style.width=300;
								document.getElementById('formeditfile').style.height=200;
								document.getElementById('formeditwindow').style.height=200;
								document.getElementById('formeditwindow').style.display='block';\" >
						Availability
						</a>
					</td>
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
					<td align=\"center\"><input type=\"checkbox\" $checkdoc1 name=\"chkseafarersman\" /></td>
				</tr>
				<tr $mouseovereffect>
					<td>VERITAS ID</td>
					<td align=\"center\"><input type=\"checkbox\" $checkdoc6 name=\"chkveritasid\" /></td>
				</tr>
				<tr $mouseovereffect>
					<td>PAY-OFF SLIP</td>
					<td align=\"center\"><input type=\"checkbox\" $checkdoc3 name=\"chkpayoffslip\" /></td>
				</tr>
				<tr $mouseovereffect>
					<td>COMPANY MANUAL</td>
					<td align=\"center\"><input type=\"checkbox\" $checkdoc4 name=\"chkcompanyman\" /></td>
				</tr>
				<tr $mouseovereffect>
					<td>FUJITRANS MANUAL</td>
					<td align=\"center\"><input type=\"checkbox\" $checkdoc5 name=\"chkfujitransman\" /></td>
				</tr>
				<tr $mouseovereffect>
					<td>TRAINING BOOK</td>
					<td align=\"center\"><input type=\"checkbox\" $checkdoc2 name=\"chktrainingman\" /></td>
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
					{
						echo $docnoC0;
					}
					else 
					{
						echo $docno18;
					}
						
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
			<table style=\"width:100%;font-size:0.8em;border:1px solid black;\" cellspacing=\"0\">
				<tr>
					<td align=\"left\"><b>UNDERTAKING</b></td>
				</tr>
				<tr $mouseovereffect>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I hereby undertake to process my expiring documents starting &nbsp;&nbsp;<input type=\"text\" name=\"undertakingdate1\" id=\"undertakingdate1\" $styledata value=\"$undertakingdate1\" onKeyPress=\"return dateonly(this);\" onkeydown=\"if(event.keyCode==13){if(this.value!=''){chkdate(this);}}\" size=\"10\" maxlength=\"10\" />
						&nbsp; to be completed on &nbsp;&nbsp;<input type=\"text\" name=\"undertakingdate2\" id=\"undertakingdate2\" $styledata value=\"$undertakingdate2\" onKeyPress=\"return dateonly(this);\" onkeydown=\"if(event.keyCode==13){if(this.value!=''){chkdate(this);}}\" size=\"10\" maxlength=\"10\" /> </td>
				</tr>
			</table>
		</div>
		<div style=\"width:97%;\">
			<table style=\"width:100%;font-size:0.75em;\" cellspacing=\"0\">
				<tr>
					<td align=\"left\" colspan=\"4\"><b>Crew Comments (Your honest answers are very important to us.)</b></td>
				</tr>
				<tr $mouseovereffect>
					<td width=\"60%\" style=\"font-weight:Bold;\">I. Did you like your job?</td>
					<td width=\"10%\"><input type=\"radio\" name=\"q1\" $chkq1yes value=\"1\" />YES</td>
					<td width=\"10%\"><input type=\"radio\" name=\"q1\" $chkq1no value=\"0\" />NO</td>
					<td width=\"20%\"><input type=\"radio\" name=\"q1\" $chkq1uncertain value=\"2\" />Uncertain</td>
				</tr>
				<tr $mouseovereffect>
					<td $styleborder colspan=\"4\">Reason <input type=\"text\" $styledata name=\"q1remarks\" value=\"$q1remarks\" size=\"60\" /></td>
				</tr>
				<tr $mouseovereffect>
					<td style=\"font-weight:Bold;\">II. Was there too much pressure in your job?</td>
					<td><input type=\"radio\" name=\"q2\" $chkq2yes value=\"1\" />YES</td>
					<td><input type=\"radio\" name=\"q2\" $chkq2no value=\"0\" />NO</td>
					<td><input type=\"radio\" name=\"q2\" $chkq2uncertain value=\"2\" />Uncertain</td>
				</tr>
				<tr $mouseovereffect>
					<td $styleborder colspan=\"4\">Explain <input type=\"text\" $styledata name=\"q2remarks\" value=\"$q2remarks\" size=\"60\" /></td>
				</tr>
				<tr $mouseovereffect>
					<td style=\"font-weight:Bold;\">III. Were there any obstacles that prevented you from doing your job while onboard?&nbsp;
						<input type=\"text\" $styledata name=\"q3aremarks\" value=\"$q3aremarks\" size=\"40\" />
					</td>
					<td><input type=\"radio\" name=\"q3a\" $chkq3ayes value=\"1\" />YES</td>
					<td><input type=\"radio\" name=\"q3a\" $chkq3ano value=\"0\" />NO</td>
					<td><input type=\"radio\" name=\"q3a\" $chkq3auncertain value=\"2\" />Uncertain</td>
				</tr>
				<tr $mouseovereffect>
					<td style=\"font-weight:Bold;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Were there any obstacles that prevented you from doing your job while onshore?
						<input type=\"text\" $styledata name=\"q3bremarks\" value=\"$q3bremarks\" size=\"40\" />
					</td>
					<td><input type=\"radio\" name=\"q3b\" $chkq3byes value=\"1\" />YES</td>
					<td><input type=\"radio\" name=\"q3b\" $chkq3bno value=\"0\" />NO</td>
					<td><input type=\"radio\" name=\"q3b\" $chkq3buncertain value=\"2\" />Uncertain</td>
				</tr>
				<tr $mouseovereffect>
					<td style=\"font-weight:Bold;\">IV. How would you evaluate the training you received onboard?</td>
					<td colspan=\"2\"><input type=\"radio\" name=\"q4a\" $chkq4asuff value=\"1\" />SUFFICIENT</td>
					<td><input type=\"radio\" name=\"q4a\" $chkq4ainsuff value=\"0\" />INSUFFICIENT</td>
				</tr>
				<tr $mouseovereffect>
					<td style=\"font-weight:Bold;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;How would you evaluate the training you received onshore?</td>
					<td colspan=\"2\"><input type=\"radio\" name=\"q4b\" $chkq4bsuff value=\"1\" />SUFFICIENT</td>
					<td><input type=\"radio\" name=\"q4b\" $chkq4binsuff value=\"0\" />INSUFFICIENT</td>
				</tr>
				<tr $mouseovereffect>
					<td $styleborder style=\"font-weight:Bold;\">
						While in Manila do you stay in?<br />
						How much do you pay per day for Hotel/Lodging House?
						<input type=\"text\" $styledata name=\"q4cremarks\" value=\"$q4cremarks\" size=\"10\" />
					</td>
					<td $styleborder colspan=\"3\">
									<input type=\"radio\" name=\"q4c\" $chkq4cH value=\"1\" />Hotel
									<input type=\"radio\" name=\"q4c\" $chkq4cL value=\"2\" />Lodging House
									<input type=\"radio\" name=\"q4c\" $chkq4cR value=\"3\" />w/ Relatives
									<input type=\"radio\" name=\"q4c\" $chkq4cO value=\"4\" />Own House
					</td>
				</tr>
				<tr $mouseovereffect>
					<td style=\"font-weight:Bold;\" colspan=\"4\">V. How would you rate your Master / Chief Engineer using the following scales?</td>
				</tr>
			</table>
		</div>
		
		<div style=\"width:97%;\">
			<table style=\"width:48%;float:left;font-size:0.75em;\" border=1 cellspacing=\"0\">
				<tr $mouseovereffect>
					<td width=\"10%\">Master:</td>
					<td width=\"45%\">
						<input type=\"text\" name=\"name_master\" $styledata value=\"$name_master\" />
					</td>
					<td width=\"15%\" align=\"center\">Good</td>
					<td width=\"15%\" align=\"center\" style=\"font-size:0.9em;\">Satisfactory</td>
					<td width=\"15%\" align=\"center\">Poor</td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">a)</td>
					<td>Relationship with crew</td>
					<td align=\"center\"><input type=\"radio\" name=\"q5mastera\" $chkq5mastera2 value=\"2\"/></td>
					<td align=\"center\"><input type=\"radio\" name=\"q5mastera\" $chkq5mastera1 value=\"1\" /></td>
					<td align=\"center\"><input type=\"radio\" name=\"q5mastera\" $chkq5mastera0 value=\"0\" /></td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">b)</td>
					<td>Attitude towards work</td>
					<td align=\"center\"><input type=\"radio\" name=\"q5masterb\" $chkq5masterb2 value=\"2\"/></td>
					<td align=\"center\"><input type=\"radio\" name=\"q5masterb\" $chkq5masterb1 value=\"1\" /></td>
					<td align=\"center\"><input type=\"radio\" name=\"q5masterb\" $chkq5masterb0 value=\"0\" /></td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">c)</td>
					<td>Well-liked by crew</td>
					<td align=\"center\"><input type=\"radio\" name=\"q5masterc\" $chkq5masterc2 value=\"2\"/></td>
					<td align=\"center\"><input type=\"radio\" name=\"q5masterc\" $chkq5masterc1 value=\"1\" /></td>
					<td align=\"center\"><input type=\"radio\" name=\"q5masterc\" $chkq5masterc0 value=\"0\" /></td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">d)</td>
					<td>Concern for crew</td>
					<td align=\"center\"><input type=\"radio\" name=\"q5masterd\" $chkq5masterd2 value=\"2\"/></td>
					<td align=\"center\"><input type=\"radio\" name=\"q5masterd\" $chkq5masterd1 value=\"1\" /></td>
					<td align=\"center\"><input type=\"radio\" name=\"q5masterd\" $chkq5masterd0 value=\"0\" /></td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">e)</td>
					<td>Ability to communicate</td>
					<td align=\"center\"><input type=\"radio\" name=\"q5mastere\" $chkq5mastere2 value=\"2\"/></td>
					<td align=\"center\"><input type=\"radio\" name=\"q5mastere\" $chkq5mastere1 value=\"1\" /></td>
					<td align=\"center\"><input type=\"radio\" name=\"q5mastere\" $chkq5mastere0 value=\"0\" /></td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">f)</td>
					<td>Food management</td>
					<td align=\"center\"><input type=\"radio\" name=\"q5masterf\" $chkq5masterf2 value=\"2\"/></td>
					<td align=\"center\"><input type=\"radio\" name=\"q5masterf\" $chkq5masterf1 value=\"1\" /></td>
					<td align=\"center\"><input type=\"radio\" name=\"q5masterf\" $chkq5masterf0 value=\"0\" /></td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">g)</td>
					<td>Over-All Performance</td>
					<td align=\"center\"><input type=\"radio\" name=\"q5masterg\" $chkq5masterg2 value=\"2\"/></td>
					<td align=\"center\"><input type=\"radio\" name=\"q5masterg\" $chkq5masterg1 value=\"1\" /></td>
					<td align=\"center\"><input type=\"radio\" name=\"q5masterg\" $chkq5masterg0 value=\"0\" /></td>
				</tr>
			</table>
			
			<table style=\"width:48%;float:right;font-size:0.75em;\" border=1 cellspacing=\"0\">
				<tr $mouseovereffect>
					<td width=\"10%\">Ch.Engr:</td>
					<td width=\"45%\">
						<input type=\"text\" name=\"name_chiefeng\" $styledata value=\"$name_chiefeng\" />
					</td>
					<td width=\"15%\" align=\"center\">Good</td>
					<td width=\"15%\" align=\"center\" style=\"font-size:0.9em;\">Satisfactory</td>
					<td width=\"15%\" align=\"center\"\">Poor</td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">a)</td>
					<td>Relationship with crew</td>
					<td align=\"center\"><input type=\"radio\" name=\"q5chiefenga\" $chkq5chiefenga2 value=\"2\"/></td>
					<td align=\"center\"><input type=\"radio\" name=\"q5chiefenga\" $chkq5chiefenga1 value=\"1\" /></td>
					<td align=\"center\"><input type=\"radio\" name=\"q5chiefenga\" $chkq5chiefenga0 value=\"0\" /></td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">b)</td>
					<td>Attitude towards work</td>
					<td align=\"center\"><input type=\"radio\" name=\"q5chiefengb\" $chkq5chiefengb2 value=\"2\"/></td>
					<td align=\"center\"><input type=\"radio\" name=\"q5chiefengb\" $chkq5chiefengb1 value=\"1\" /></td>
					<td align=\"center\"><input type=\"radio\" name=\"q5chiefengb\" $chkq5chiefengb0 value=\"0\" /></td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">c)</td>
					<td>Well-liked by crew</td>
					<td align=\"center\"><input type=\"radio\" name=\"q5chiefengc\" $chkq5chiefengc2 value=\"2\"/></td>
					<td align=\"center\"><input type=\"radio\" name=\"q5chiefengc\" $chkq5chiefengc1 value=\"1\" /></td>
					<td align=\"center\"><input type=\"radio\" name=\"q5chiefengc\" $chkq5chiefengc0 value=\"0\" /></td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">d)</td>
					<td>Concern for crew</td>
					<td align=\"center\"><input type=\"radio\" name=\"q5chiefengd\" $chkq5chiefengd2 value=\"2\"/></td>
					<td align=\"center\"><input type=\"radio\" name=\"q5chiefengd\" $chkq5chiefengd1 value=\"1\" /></td>
					<td align=\"center\"><input type=\"radio\" name=\"q5chiefengd\" $chkq5chiefengd0 value=\"0\" /></td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">e)</td>
					<td>Ability to communicate</td>
					<td align=\"center\"><input type=\"radio\" name=\"q5chiefenge\" $chkq5chiefenge2 value=\"2\"/></td>
					<td align=\"center\"><input type=\"radio\" name=\"q5chiefenge\" $chkq5chiefenge1 value=\"1\" /></td>
					<td align=\"center\"><input type=\"radio\" name=\"q5chiefenge\" $chkq5chiefenge0 value=\"0\" /></td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">f)</td>
					<td>Food management</td>
					<td align=\"center\"><input type=\"radio\" name=\"q5chiefengf\" $chkq5chiefengf2 value=\"2\"/></td>
					<td align=\"center\"><input type=\"radio\" name=\"q5chiefengf\" $chkq5chiefengf1 value=\"1\" /></td>
					<td align=\"center\"><input type=\"radio\" name=\"q5chiefengf\" $chkq5chiefengf0 value=\"0\" /></td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">g)</td>
					<td>Over-All Performance</td>
					<td align=\"center\"><input type=\"radio\" name=\"q5chiefengg\" $chkq5chiefengg2 value=\"2\"/></td>
					<td align=\"center\"><input type=\"radio\" name=\"q5chiefengg\" $chkq5chiefengg1 value=\"1\" /></td>
					<td align=\"center\"><input type=\"radio\" name=\"q5chiefengg\" $chkq5chiefengg0 value=\"0\" /></td>
				</tr>
			</table>
		</div>
		
		<br />
		
		<div style=\"width:97%;\">
		
			<table style=\"width:46%;float:left;font-size:0.75em;\" border=1 cellspacing=\"0\">
				<tr>
					<td align=\"left\" colspan=\"5\"><b>VI. How would you rate your Chief Cook and the food he served?</b></td>
				</tr>
				<tr $mouseovereffect>
					<td width=\"10%\">C/Ck:</td>
					<td width=\"45%\">
						<input type=\"text\" name=\"name_chiefcook\" $styledata value=\"$name_chiefcook\" />
					</td>
					<td width=\"15%\" align=\"center\">Good</td>
					<td width=\"15%\" align=\"center\" style=\"font-size:0.9em;\">Satisfactory</td>
					<td width=\"15%\" align=\"center\"\">Poor</td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">a)</td>
					<td>Food quantity sufficient</td>
					<td align=\"center\"><input type=\"radio\" name=\"q6chiefcooka\" $chkq6chiefcooka2 value=\"2\"/></td>
					<td align=\"center\"><input type=\"radio\" name=\"q6chiefcooka\" $chkq6chiefcooka1 value=\"1\" /></td>
					<td align=\"center\"><input type=\"radio\" name=\"q6chiefcooka\" $chkq6chiefcooka0 value=\"0\" /></td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">b)</td>
					<td>Food quality/taste acceptable</td>
					<td align=\"center\"><input type=\"radio\" name=\"q6chiefcookb\" $chkq6chiefcookb2 value=\"2\"/></td>
					<td align=\"center\"><input type=\"radio\" name=\"q6chiefcookb\" $chkq6chiefcookb1 value=\"1\" /></td>
					<td align=\"center\"><input type=\"radio\" name=\"q6chiefcookb\" $chkq6chiefcookb0 value=\"0\" /></td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">c)</td>
					<td>Hygiene and sanitation</td>
					<td align=\"center\"><input type=\"radio\" name=\"q6chiefcookc\" $chkq6chiefcookc2 value=\"2\"/></td>
					<td align=\"center\"><input type=\"radio\" name=\"q6chiefcookc\" $chkq6chiefcookc1 value=\"1\" /></td>
					<td align=\"center\"><input type=\"radio\" name=\"q6chiefcookc\" $chkq6chiefcookc0 value=\"0\" /></td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">d)</td>
					<td>Food Handling & Management</td>
					<td align=\"center\"><input type=\"radio\" name=\"q6chiefcookd\" $chkq6chiefcookd2 value=\"2\"/></td>
					<td align=\"center\"><input type=\"radio\" name=\"q6chiefcookd\" $chkq6chiefcookd1 value=\"1\" /></td>
					<td align=\"center\"><input type=\"radio\" name=\"q6chiefcookd\" $chkq6chiefcookd0 value=\"0\" /></td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">e)</td>
					<td>Human Relations</td>
					<td align=\"center\"><input type=\"radio\" name=\"q6chiefcooke\" $chkq6chiefcooke2 value=\"2\"/></td>
					<td align=\"center\"><input type=\"radio\" name=\"q6chiefcooke\" $chkq6chiefcooke1 value=\"1\" /></td>
					<td align=\"center\"><input type=\"radio\" name=\"q6chiefcooke\" $chkq6chiefcooke0 value=\"0\" /></td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">f)</td>
					<td>Suggestions</td>
					<td align=\"left\" colspan=\"3\"><input type=\"text\" name=\"q6chiefcookf\" $styledata value=\"$q6chiefcookf\" />&nbsp;</td>
				</tr>
			</table>
			
			<table style=\"width:46%;float:right;font-size:0.75em;\" border=1 cellspacing=\"0\">
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
					<td align=\"center\"><input type=\"radio\" name=\"q7a\" $chkq7a2 value=\"2\"/></td>
					<td align=\"center\"><input type=\"radio\" name=\"q7a\" $chkq7a1 value=\"1\" /></td>
					<td align=\"center\"><input type=\"radio\" name=\"q7a\" $chkq7a0 value=\"0\" /></td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">b)</td>
					<td>Benefit Package</td>
					<td align=\"center\"><input type=\"radio\" name=\"q7b\" $chkq7b2 value=\"2\"/></td>
					<td align=\"center\"><input type=\"radio\" name=\"q7b\" $chkq7b1 value=\"1\" /></td>
					<td align=\"center\"><input type=\"radio\" name=\"q7b\" $chkq7b0 value=\"0\" /></td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">c)</td>
					<td>Welfare Service, Work conditions, Recreational facilities</td>
					<td align=\"center\"><input type=\"radio\" name=\"q7c\" $chkq7c2 value=\"2\"/></td>
					<td align=\"center\"><input type=\"radio\" name=\"q7c\" $chkq7c1 value=\"1\" /></td>
					<td align=\"center\"><input type=\"radio\" name=\"q7c\" $chkq7c0 value=\"0\" /></td>
				</tr>
				<tr $mouseovereffect>
					<td align=\"center\">d)</td>
					<td>Others, specify <br /><input type=\"text\" $styledata name=\"q7dremarks\" value=\"$q7dremarks\" /></td>
					<td align=\"center\"><input type=\"radio\" name=\"q7d\" $chkq7d2 value=\"2\"/></td>
					<td align=\"center\"><input type=\"radio\" name=\"q7d\" $chkq7d1 value=\"1\" /></td>
					<td align=\"center\"><input type=\"radio\" name=\"q7d\" $chkq7d0 value=\"0\" /></td>
				</tr>
				<tr><td colspan=\"5\">&nbsp;</td></tr>
			</table>
			
			<br />
			
		</div>
		
		<div style=\"width:100%;\">
			<table style=\"width:40%;float:left;font-size:0.7em;\" cellpadding=\"0\" cellspacing=\"0\">
				<tr>
					<td style=\"font-weight:Bold;\" width=\"80%\">VIII. Computer Proficiency</td>
				</tr>
				<tr>
					<td width=\"80%\">Do you have an email address?<br>			
					<input type=\"text\" name=\"xmyemailadd\" value=\"$xmyemailadd\" id=\"xmyemailadd\" style=\"width:150px\"/></td>
					<td width=\"2%\">:</td>
					<td width=\"18%\" align=\"center\"><input type=\"checkbox\" $chklast1 name=\"last1\" /></td>
				</tr>
				<tr>
					<td>Does your family have an email address?</td>
					<td>:</td>
					<td align=\"center\"><input type=\"checkbox\" $chklast2 name=\"last2\" /></td>
				</tr>
				<tr>
					<td>Do you have a personal laptop or computer?</td>
					<td>:</td>
					<td align=\"center\"><input type=\"checkbox\" $chklast3 name=\"last3\" /></td>
				</tr>
				<tr>
					<td>Do you have a family computer in your house?</td>
					<td>:</td>
					<td align=\"center\"><input type=\"checkbox\" $chklast4 name=\"last4\" /></td>
				</tr>
				<tr>
					<td>Is there an internet shop near home?</td>
					<td>:</td>
					<td align=\"center\"><input type=\"checkbox\" $chklast5 name=\"last5\" /></td>
				</tr>
			</table>
			
			<table style=\"width:59%;float:right;font-size:0.70em;\" cellpadding=\"0\" cellspacing=\"0\">
				<tr>
					<td width=\"60%\">Do you have an internet connection in your house?</td>
					<td width=\"5%\">:</td>
					<td width=\"35%\" align=\"center\"><input type=\"checkbox\" $chklast6 name=\"last6\" /></td>
				</tr>
				<tr>
					<td>If not, how do you access your email?</td>
					<td>:</td>
					<td><input type=\"text\" name=\"last7\" value=\"$last7\" style=\"font-size:0.8em;\" size=\"20\" /></td>
				</tr>
				<tr>
					<td>Are you aware of the Veritas Official website?</td>
					<td>:</td>
					<td align=\"center\"><input type=\"checkbox\" $chklast8 name=\"last8\" /></td>
				</tr>
				<tr>
					<td>How often do you access it?</td>
					<td>:</td>
					<td><input type=\"text\" name=\"last9\" value=\"$last9\" style=\"font-size:0.8em;\" size=\"20\" /></td>
				</tr>

				<tr>
					<td colspan=\"2\" align=\"left\">Certified true and correct:</td>
					<td colspan=\"3\" style=\"border-bottom:1px solid Black;\">&nbsp;</td>
				</tr>
				<tr>
					<td colspan=\"2\">&nbsp;</td>
					<td colspan=\"3\" align=\"center\" style=\"font-size:0.7em;\">(Signature of 	Crew)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date</td>
				</tr>


			</table>

			<table style=\"width:48%;float:left;font-size:0.75em;\" border=1 cellspacing=\"0\">
				<tr>
					<td align=\"center\"><input type=\"button\" value=\"Save Debriefing Form\" onclick=\"if(undertakingdate1.value != '' && undertakingdate2.value != '') { if(last1.checked==true && xmyemailadd.value=='') { alert('Please enter your Email Address.'); } else { actiontxt.value='save';submit(); } } else { alert('Please enter Date Range in the UNDERTAKING Section.'); }\" />
					</td>
				</tr>
			</table>
		</div>
	</div>
	</center>	
<div id=\"formeditwindow\" 
	style=\"background-color:Red;z-index:200;position:absolute;padding:5 0 5 0;
		border:3px solid black;display:none;\">
	<br />
	<iframe marginwidth=0 marginheight=0 id=\"formeditfile\" frameborder=\"0\" name=\"content\" src=\"\" scrolling=\"auto\" 
		style=\"\">
	</iframe>
	<br /><br />

	<input type=\"button\" value=\"Close\" onclick=\"document.getElementById('formeditwindow').style.display='none';submit();\" />
</div>

	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"applicantno\" value=\"$applicantno\"/>
	<input type=\"hidden\" name=\"ccid\" value=\"$ccid\"/>
	<input type=\"hidden\" name=\"load\" value=\"$load\"/>
</form>
</body>

</html>

";