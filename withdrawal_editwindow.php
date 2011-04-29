<?php
include('veritas/connectdb.php');
include("veritas/include/stylephp.inc");
include("veritas/include/functions.inc");

session_start();

if(isset($_SESSION['employeeid']))
	$employeeid=$_SESSION['employeeid'];
	
$currentdate = date("Y-m-d H:i:s");
$datenow = date("m/d/Y");
$errormsg = "";
$checked = "checked=\"checked\"";

if(isset($_POST['ccid']))
	$ccid=$_POST['ccid'];
else 
	$ccid=$_GET['ccid'];
	
if(isset($_POST['applicantno']))
	$applicantno=$_POST['applicantno'];
else 
	$applicantno=$_GET['applicantno'];
	
if(isset($_POST['action']))
	$action=$_POST['action'];
else 
	$action=$_GET['action'];

if(isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];


//POSTS FOR Arrive Manila Date

$qrygetdefault = mysql_query("SELECT cc.ARRMNLDATE,cc.DISEMBREASONCODE,d.REASON,cc.DATEEMB,
							IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,cc.DEPMNLDATE
							FROM crewchange cc
							LEFT JOIN disembarkreason d ON d.DISEMBREASONCODE=cc.DISEMBREASONCODE
							WHERE CCID=$ccid") or die(mysql_error());

$rowgetdefault = mysql_fetch_array($qrygetdefault);

$getdepmnldate = date("dMY",strtotime($rowgetdefault["DEPMNLDATE"]));
$getdateemb = date("dMY",strtotime($rowgetdefault["DATEEMB"]));
$getdatedisemb = date("dMY",strtotime($rowgetdefault["DATEDISEMB"]));

$getarrmnldate = $rowgetdefault["ARRMNLDATE"];
$getdisembreasoncode = $rowgetdefault["DISEMBREASONCODE"];
$getdisembreason = $rowgetdefault["REASON"];

if(isset($_POST['arrivemnla2']) && !empty($_POST['arrivemnla2']))
	$arrivemnla2 = date("m/d/Y",strtotime($_POST['arrivemnla2']));
else 
	$arrivemnla2 = "";

if(isset($_POST['arrivemnla']))
	$arrivemnla = date("m/d/Y",strtotime($_POST['arrivemnla']));
else 
{
	if (!empty($getarrmnldate))
		$arrivemnla = date("m/d/Y",strtotime($getarrmnldate));
}

if(isset($_POST['reportdate']))
	$reportdate = date("m/d/Y",strtotime($_POST['reportdate']));
else 
	$reportdate = $datenow;
	
if(isset($_POST['disembreasoncode']))
	$disembreasoncode = $_POST['disembreasoncode'];
//else 
//	$disembreasoncode = $getdisembreasoncode;
	
$qrydisembreason = mysql_query("SELECT DISEMBREASONCODE,REASON FROM disembarkreason WHERE STATUS=1") or die(mysql_error());
$dismbarkreasonsel = "<option value=\"\">--Select One--</option>";
while ($rowdisembreason = mysql_fetch_array($qrydisembreason))
{
	$reasoncode = $rowdisembreason["DISEMBREASONCODE"];
	$reason = $rowdisembreason["REASON"];
	
	$selected = "";
	if ($reasoncode == $disembreasoncode)
		$selected = "SELECTED";
		
	$dismbarkreasonsel .= "<option $selected value=\"$reasoncode\">$reason</option>";
}
	

// POSTS FOR Documents Surrender

$surr_F1 = 0;
$surr_41 = 0;
$surr_F2 = 0;
$surr_P1 = 0;
$surr_P2 = 0;
$surr_42 = 0;
$surr_A4 = 0;
$surr_32 = 0;
$surr_18 = 0;
$surr_C0 = 0;

if(isset($_POST['surr_41']))
{
	$surr_41 = 1;
	$chksur41 = $checked;
}
if(isset($_POST['surr_F2']))
{
	$surr_F2 = 1;
	$chksurF2 = $checked;
}
if(isset($_POST['surr_F1']))
{
	$surr_F1 = 1;
	$chksurF1 = $checked;
}
if(isset($_POST['surr_32']))
{
	$surr_32 = 1;
	$chksur32 = $checked;
}
if(isset($_POST['surr_P2']))
{
	$surr_P2 = 1;
	$chksurP2 = $checked;
}
if(isset($_POST['surr_P1']))
{
	$surr_P1 = 1;
	$chksurP1 = $checked;
}
if(isset($_POST['surr_42']))
{
	$surr_42 = 1;
	$chksur42 = $checked;
}
if(isset($_POST['surr_A4']))
{
	$surr_A4 = 1;
	$chksurA4 = $checked;
}
if(isset($_POST['surr_18']))
{
	$surr_18 = 1;
	$chksur18 = $checked;
}
if(isset($_POST['surr_C0']))
{
	$surr_C0 = 1;
	$chksurC0 = $checked;
}

if(isset($_POST['surrender_remarks']))
	$surrender_remarks = $_POST['surrender_remarks'];


//POSTS FOR Expiring Docs (JIS License)

$jis_3gr = 0;
$jis_goc = 0;
$jis_roc = 0;
$jis_license = 0;
$jis_meca = 0;
$jis_scaf = 0;
$jis_sso = 0;

if(isset($_POST['jis_3gr']))
{
	$jis_3gr = 1;
	$chkjis_3gr = $checked;
}
if(isset($_POST['jis_goc']))
{
	$jis_goc = 1;
	$chkjis_goc = $checked;
}
if(isset($_POST['jis_roc']))
{
	$jis_roc = 1;
	$chkjis_roc = $checked;
}
if(isset($_POST['jis_license']))
{
	$jis_license = 1;
	$chkjis_license = $checked;
}
if(isset($_POST['jis_meca']))
{
	$jis_meca = 1;
	$chkjis_meca = $checked;
}
if(isset($_POST['jis_scaf']))
{
	$jis_scaf = 1;
	$chkjis_scaf = $checked;
}
if(isset($_POST['jis_sso']))
{
	$jis_sso = 1;
	$chkjis_sso = $checked;
}

if(isset($_POST['jis_remarks']))
	$jis_remarks = $_POST['jis_remarks'];


//POSTS FOR US VISA ENDORSEMENT

$chkendorse = "";

if(isset($_POST['visa_remarks']))
	$visa_remarks = $_POST['visa_remarks'];
	
if(isset($_POST['visaendorse']))
{
	$visaendorse = 1;
	$chkendorse = $checked;
}

//POSTS FOR Scanning Docs

$scan_F1 = 0;
$scan_41 = 0;
$scan_F2 = 0;
$scan_P1 = 0;
$scan_P2 = 0;
$scan_42 = 0;
$scan_A4 = 0;
$scan_32 = 0;
$scan_C0 = 0;
$scan_18 = 0;

if(isset($_POST['scan_41']))
{
	$scan_41 = 1;
	$chkscan41 = $checked;
}
if(isset($_POST['scan_F2']))
{
	$scan_F2 = 1;
	$chkscanF2 = $checked;
}
if(isset($_POST['scan_F1']))
{
	$scan_F1 = 1;
	$chkscanF1 = $checked;
}
if(isset($_POST['scan_32']))
{
	$scan_32 = 1;
	$chkscan32 = $checked;
}
if(isset($_POST['scan_P2']))
{
	$scan_P2 = 1;
	$chkscanP2 = $checked;
}
if(isset($_POST['scan_P1']))
{
	$scan_P1 = 1;
	$chkscanP1 = $checked;
}
if(isset($_POST['scan_42']))
{
	$scan_42 = 1;
	$chkscan42 = $checked;
}
if(isset($_POST['scan_A4']))
{
	$scan_A4 = 1;
	$chkscanA4 = $checked;
}
if(isset($_POST['scan_18']))
{
	$scan_18 = 1;
	$chkscan18 = $checked;
}
if(isset($_POST['scan_C0']))
{
	$scan_C0 = 1;
	$chkscanC0 = $checked;
}

if(isset($_POST['scanned_remarks']))
	$scanned_remarks = $_POST['scanned_remarks'];


//POSTS FOR Storing Docs

$store_F1 = 0;
$store_41 = 0;
$store_F2 = 0;
$store_P1 = 0;
$store_P2 = 0;
$store_42 = 0;
$store_A4 = 0;
$store_32 = 0;
$store_C0 = 0;
$store_18 = 0;

if(isset($_POST['store_41']))
{
	$store_41 = 1;
	$chkstore41 = $checked;
}
if(isset($_POST['store_F2']))
{
	$store_F2 = 1;
	$chkstoreF2 = $checked;
}
if(isset($_POST['store_F1']))
{
	$store_F1 = 1;
	$chkstoreF1 = $checked;
}
if(isset($_POST['store_32']))
{
	$store_32 = 1;
	$chkstore32 = $checked;
}
if(isset($_POST['store_P2']))
{
	$store_P2 = 1;
	$chkstoreP2 = $checked;
}
if(isset($_POST['store_P1']))
{
	$store_P1 = 1;
	$chkstoreP1 = $checked;
}
if(isset($_POST['store_42']))
{
	$store_42 = 1;
	$chkstore42 = $checked;
}
if(isset($_POST['store_A4']))
{
	$store_A4 = 1;
	$chkstoreA4 = $checked;
}
if(isset($_POST['store_18']))
{
	$store_18 = 1;
	$chkstore18 = $checked;
}
if(isset($_POST['store_C0']))
{
	$store_C0 = 1;
	$chkstoreC0 = $checked;
}

if(isset($_POST['stored_remarks']))
	$stored_remarks = $_POST['stored_remarks'];

//POSTS FOR Final Printing


//	1 - updating arrive manila date
//	2 - Surrendering of Docs/Lic
//	3 - Expiring docs
//	4 - US VISA endorsements
//	5 - Scanning of Docs/Lic
//	6 - Storing of Doc/Lic
//	7 - Printing

$content = "";
	
$fontsize = "font-size:0.9em;";

switch ($action)  //CONTENT OF EACH POP-UP
{
	case "1"	:
		
			$content = "
			<span class=\"sectiontitle\">UPDATE ARRIVE MANILA DATE</span>
			<br />
			
			<table style=\"width:100%;$fontsize\">
				<tr>
					<td>Reported Date</td>
					<td>:</td>
					<td style=\"font-size:1em;font-weight:Bold;color:Blue;\">$reportdate</td>			
				</tr>
				<tr><td colspan=\"3\"><hr /></td></tr>
				<tr>
					<td>Depart Manila Date</td>
					<td>:</td>
					<td style=\"font-size:1em;font-weight:Bold;color:Green;\">$getdepmnldate</td>
				</tr>
				<tr>
					<td>Date Embark</td>
					<td>:</td>
					<td style=\"font-size:1em;font-weight:Bold;color:Green;\">$getdateemb</td>
				</tr>
				<tr>
					<td>Date Disembark</td>
					<td>:</td>
					<td style=\"font-size:1em;font-weight:Bold;color:Green;\">$getdatedisemb</td>
				</tr>
				<tr><td colspan=\"3\">&nbsp;</td></tr>
				<tr>
					<td>Arrive Manila Date</td>
					<td>:</td>
					<td style=\"font-size:1em;font-weight:Bold;color:Blue;\">$arrivemnla</td>
				</tr>
				<tr>
					<td>Change To</td>
					<td>:</td>
					<td><input type=\"text\" name=\"arrivemnla2\" value=\"$arrivemnla2\" 
							onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\"
							onkeydown=\"if(event.keyCode==13){if(this.value!=''){chkdate(this);}}\" />
						&nbsp;&nbsp;(mm/dd/yyyy)
					</td>
				</tr>
				<tr>
					<td>Disembark Reason</td>
					<td>:</td>
					<td style=\"font-size:1em;font-weight:Bold;color:Blue;\">$getdisembreason</td>
				</tr>
				<tr>
					<td>Change To</td>
					<td>:</td>
					<td><select name=\"disembreasoncode\">
							$dismbarkreasonsel
						</select>
					</td>			
				</tr>
			</table>
			
			";
		
		break;
	
	case "2"	:
		

		break;
	case "3"	:
			
		break;
	case "4"	:
			
		break;
	case "5"	:

		break;
	case "6"	:

		break;
	case "7"	:
			
		break;
}



switch ($actiontxt)
{
	case "save"	:
		
		
		break;
}




echo "
<html>
<head>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>
</head>
<body style=\"overflow:hidden;\">

<form name=\"withdrawalremarks\" method=\"POST\">

	$content
	<br />
	
	<center>
	";

	if (empty($errormsg))
	{
		$btnsaveclick = "actiontxt.value='save';submit();";
		
//		switch ($action)
//		{
//			case "2":
//				if ($surrender_remarks == "")
//					$btnsaveclick = "alert('Please encode your Remarks.');";
//				else 
//					$btnsaveclick = "actiontxt.value='save';submit();";
//			break;
//			case "4":
//				if ($visa_remarks == "")
//					$btnsaveclick = "alert('Please encode your Remarks.');";
//				else 
//					$btnsaveclick = "actiontxt.value='save';submit();";
//			break;
//			case "5":
//				if ($scanned_remarks == "")
//					$btnsaveclick = "alert('Please encode your Remarks.');";
//				else 
//					$btnsaveclick = "actiontxt.value='save';submit();";
//			break;
//		}
		
		echo "<input type=\"button\" value=\"Save\" onclick=\"$btnsaveclick\" />";
	}
	else 
		echo "<span style=\"color:Green;font-size:0.8em;font-weight:Bold;\">$errormsg</span>";
	
	echo "
	</center>

	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"action\" value=\"$action\"/>
	<input type=\"hidden\" name=\"ccid\" value=\"$ccid\" />
	<input type=\"hidden\" name=\"applicantno\" value=\"$applicantno\" />
	
</form>
</body>

</html>
";