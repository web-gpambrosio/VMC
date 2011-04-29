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

if(isset($_POST["ccid"]))
	$ccid=$_POST["ccid"];
else 
	$ccid=$_GET["ccid"];
	
if(isset($_POST["applicantno"]))
	$applicantno=$_POST["applicantno"];
else 
	$applicantno=$_GET["applicantno"];
	
if(isset($_POST["action"]))
	$action=$_POST["action"];
else 
	$action=$_GET["action"];

if(isset($_POST["actiontxt"]))
	$actiontxt = $_POST["actiontxt"];

if(isset($_POST["height"]))
	$height = $_POST["height"];

if(isset($_POST["weight"]))
	$weight = $_POST["weight"];


//get Height & Weight
$qryhtwt=mysql_query("SELECT HEIGHT,WEIGHT FROM crew WHERE APPLICANTNO=$applicantno") or die(mysql_error());
$rowhtwt=mysql_fetch_array($qryhtwt);
if(empty($height))
	$height=$rowhtwt["HEIGHT"];
if(empty($weight))
	$weight=$rowhtwt["WEIGHT"];
	

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

if(isset($_POST["arrivemnla2"]) && !empty($_POST["arrivemnla2"]))
	$arrivemnla2 = date("m/d/Y",strtotime($_POST["arrivemnla2"]));
else 
	$arrivemnla2 = "";

if(isset($_POST["arrivemnla"]))
	$arrivemnla = date("m/d/Y",strtotime($_POST["arrivemnla"]));
else 
{
	if (!empty($getarrmnldate))
		$arrivemnla = date("m/d/Y",strtotime($getarrmnldate));
}

if(isset($_POST["reportdate"]))
	$reportdate = date("m/d/Y",strtotime($_POST["reportdate"]));
else 
	$reportdate = $datenow;
	
if(isset($_POST["disembreasoncode"]))
	$disembreasoncode = $_POST["disembreasoncode"];
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

if(isset($_POST["surr_41"]))
{
	$surr_41 = 1;
	$chksur41 = $checked;
}
if(isset($_POST["surr_F2"]))
{
	$surr_F2 = 1;
	$chksurF2 = $checked;
}
if(isset($_POST["surr_F1"]))
{
	$surr_F1 = 1;
	$chksurF1 = $checked;
}
if(isset($_POST["surr_32"]))
{
	$surr_32 = 1;
	$chksur32 = $checked;
}
if(isset($_POST["surr_P2"]))
{
	$surr_P2 = 1;
	$chksurP2 = $checked;
}
if(isset($_POST["surr_P1"]))
{
	$surr_P1 = 1;
	$chksurP1 = $checked;
}
if(isset($_POST["surr_42"]))
{
	$surr_42 = 1;
	$chksur42 = $checked;
}
if(isset($_POST["surr_A4"]))
{
	$surr_A4 = 1;
	$chksurA4 = $checked;
}
if(isset($_POST["surr_18"]))
{
	$surr_18 = 1;
	$chksur18 = $checked;
}
if(isset($_POST["surr_C0"]))
{
	$surr_C0 = 1;
	$chksurC0 = $checked;
}

if(isset($_POST["surrender_remarks"]))
	$surrender_remarks = $_POST["surrender_remarks"];


//POSTS FOR Expiring Docs (JIS License)

$jis_3gr = 0;
$jis_goc = 0;
$jis_roc = 0;
$jis_license = 0;
$jis_meca = 0;
$jis_scaf = 0;
$jis_sso = 0;

if(isset($_POST["jis_3gr"]))
{
	$jis_3gr = 1;
	$chkjis_3gr = $checked;
}
if(isset($_POST["jis_goc"]))
{
	$jis_goc = 1;
	$chkjis_goc = $checked;
}
if(isset($_POST["jis_roc"]))
{
	$jis_roc = 1;
	$chkjis_roc = $checked;
}
if(isset($_POST["jis_license"]))
{
	$jis_license = 1;
	$chkjis_license = $checked;
}
if(isset($_POST["jis_meca"]))
{
	$jis_meca = 1;
	$chkjis_meca = $checked;
}
if(isset($_POST["jis_scaf"]))
{
	$jis_scaf = 1;
	$chkjis_scaf = $checked;
}
if(isset($_POST["jis_sso"]))
{
	$jis_sso = 1;
	$chkjis_sso = $checked;
}

if(isset($_POST["jis_remarks"]))
	$jis_remarks = $_POST["jis_remarks"];


//POSTS FOR US VISA ENDORSEMENT

$chkendorse = "";

if(isset($_POST["visa_remarks"]))
	$visa_remarks = $_POST["visa_remarks"];
	
if(isset($_POST["visaendorse"]))
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

if(isset($_POST["scan_41"]))
{
	$scan_41 = 1;
	$chkscan41 = $checked;
}
if(isset($_POST["scan_F2"]))
{
	$scan_F2 = 1;
	$chkscanF2 = $checked;
}
if(isset($_POST["scan_F1"]))
{
	$scan_F1 = 1;
	$chkscanF1 = $checked;
}
if(isset($_POST["scan_32"]))
{
	$scan_32 = 1;
	$chkscan32 = $checked;
}
if(isset($_POST["scan_P2"]))
{
	$scan_P2 = 1;
	$chkscanP2 = $checked;
}
if(isset($_POST["scan_P1"]))
{
	$scan_P1 = 1;
	$chkscanP1 = $checked;
}
if(isset($_POST["scan_42"]))
{
	$scan_42 = 1;
	$chkscan42 = $checked;
}
if(isset($_POST["scan_A4"]))
{
	$scan_A4 = 1;
	$chkscanA4 = $checked;
}
if(isset($_POST["scan_18"]))
{
	$scan_18 = 1;
	$chkscan18 = $checked;
}
if(isset($_POST["scan_C0"]))
{
	$scan_C0 = 1;
	$chkscanC0 = $checked;
}

if(isset($_POST["scanned_remarks"]))
	$scanned_remarks = $_POST["scanned_remarks"];


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

if(isset($_POST["store_41"]))
{
	$store_41 = 1;
	$chkstore41 = $checked;
}
if(isset($_POST["store_F2"]))
{
	$store_F2 = 1;
	$chkstoreF2 = $checked;
}
if(isset($_POST["store_F1"]))
{
	$store_F1 = 1;
	$chkstoreF1 = $checked;
}
if(isset($_POST["store_32"]))
{
	$store_32 = 1;
	$chkstore32 = $checked;
}
if(isset($_POST["store_P2"]))
{
	$store_P2 = 1;
	$chkstoreP2 = $checked;
}
if(isset($_POST["store_P1"]))
{
	$store_P1 = 1;
	$chkstoreP1 = $checked;
}
if(isset($_POST["store_42"]))
{
	$store_42 = 1;
	$chkstore42 = $checked;
}
if(isset($_POST["store_A4"]))
{
	$store_A4 = 1;
	$chkstoreA4 = $checked;
}
if(isset($_POST["store_18"]))
{
	$store_18 = 1;
	$chkstore18 = $checked;
}
if(isset($_POST["store_C0"]))
{
	$store_C0 = 1;
	$chkstoreC0 = $checked;
}

if(isset($_POST["stored_remarks"]))
	$stored_remarks = $_POST["stored_remarks"];

	
if (isset($_POST["jis_doccode"]))
	$jis_doccode = $_POST["jis_doccode"];
if (isset($_POST["jis_examdate"]))
	$jis_examdate = $_POST["jis_examdate"];
if (isset($_POST["jis_remarks_ofcr"]))
	$jis_remarks_ofcr = $_POST["jis_remarks_ofcr"];
	
if (isset($_POST["jis_doccode2"]))
	$jis_doccode2 = $_POST["jis_doccode2"];
if (isset($_POST["jis_examdate2"]))
	$jis_examdate2 = $_POST["jis_examdate2"];
if (isset($_POST["jis_remarks_staff"]))
	$jis_remarks_staff = $_POST["jis_remarks_staff"];
	
	
if (isset($_POST["delidno"]))
	$delidno = $_POST["delidno"];
	


//POSTS FOR Final Printing


//	1 - updating arrive manila date
//	2 - Surrendering of Docs/Lic
//	3 - Expiring docs
//	4 - US VISA endorsements
//	5 - Scanning of Docs/Lic
//	6 - Storing of Doc/Lic
//	7 - Printing


switch ($actiontxt)
{
	case "save"	:
		
			switch ($action)
			{
				case "1"	:  //Arrive Manila Update
					
						if (!empty($reportdate))
							$reportdateraw = "'" . date("Y-m-d",strtotime($reportdate)) . "'";
						else 
							$reportdateraw = "NULL";
					
						if (!empty($arrivemnla2))
						{
							$arrivemnla2raw = "'" . date("Y-m-d",strtotime($arrivemnla2)) . "'";
							
							$qryupdatearrive = mysql_query("UPDATE crewchange SET ARRMNLDATE=$arrivemnla2raw,
																				CONFIRMARRMNLBY='$employeeid',
																				CONFIRMARRMNLDATE='$currentdate'
															WHERE CCID=$ccid") or die(mysql_error());
						}
						if (!empty($disembreasoncode))
						{
							$qryupdatereason = mysql_query("UPDATE crewchange SET DISEMBREASONCODE='$disembreasoncode' WHERE CCID=$ccid") or die(mysql_error());
						}

						
						$qrychkexist=mysql_query("SELECT CCID FROM debriefinghdr WHERE CCID=$ccid") or die(mysql_error());
						if(mysql_num_rows($qrychkexist)==0)
							$qrydebriefinsert = mysql_query("INSERT INTO debriefinghdr(CCID,STATUS,REPORTEDDATE,MADEBY,MADEDATE)
														VALUES($ccid,0,$reportdateraw,'$employeeid','$currentdate')") or die(mysql_error());
						
						$qrychkexist=mysql_query("SELECT CCID FROM docsurrenderhdr WHERE CCID=$ccid") or die(mysql_error());
						if(mysql_num_rows($qrychkexist)==0)
							$qrydocinsert = mysql_query("INSERT INTO docsurrenderhdr(CCID) VALUES($ccid)") or die(mysql_error());
						
						$qrychkexist=mysql_query("SELECT CCID FROM debrieflicense WHERE CCID=$ccid") or die(mysql_error());
						if(mysql_num_rows($qrychkexist)==0)
							$qryjisinsert = mysql_query("INSERT INTO debrieflicense(CCID) VALUES($ccid)") or die(mysql_error());
						
						$qryupdatewtht = mysql_query("UPDATE crewchange SET DISEMBHEIGHT=$height,DISEMBWEIGHT=$weight
															WHERE CCID=$ccid") or die(mysql_error());
						$qryupdatewtht = mysql_query("UPDATE crew SET HEIGHT=$height,WEIGHT=$weight
															WHERE APPLICANTNO=$applicantno") or die(mysql_error());
						
						$errormsg = "Updated successfully!";
					
					break;
				case "2"	:  //Surrendering Documents
				
						$surrender_remarks = mysql_real_escape_string($surrender_remarks);

						$qryupdate = mysql_query("UPDATE docsurrenderhdr SET 
														SURRENDERED_F1 = $surr_F1,
														SURRENDERED_41 = $surr_41,
														SURRENDERED_F2 = $surr_F2,
														SURRENDERED_P1 = $surr_P1,
														SURRENDERED_P2 = $surr_P2,
														SURRENDERED_42 = $surr_42,
														SURRENDERED_A4 = $surr_A4,
														SURRENDERED_32 = $surr_32,
														SURRENDERED_18 = $surr_18,
														SURRENDERED_C0 = $surr_C0
												WHERE CCID=$ccid") or die(mysql_error());
						
						//Update Debriefing table
						
						$qrydebriefupdate = mysql_query("UPDATE debriefinghdr SET 
														SURRENDERTO = '$employeeid',
														SURRENDERDATE = '$currentdate',
														SURRENDERREMARKS = '$surrender_remarks'
													WHERE CCID=$ccid
												") or die(mysql_error());
												
												
						$scanned_remarks = mysql_real_escape_string($scanned_remarks);
				
						$qryupdate = mysql_query("UPDATE docsurrenderhdr SET 
														SCANNED_F1 = $scan_F1,
														SCANNED_41 = $scan_41,
														SCANNED_F2 = $scan_F2,
														SCANNED_P1 = $scan_P1,
														SCANNED_P2 = $scan_P2,
														SCANNED_42 = $scan_42,
														SCANNED_A4 = $scan_A4,
														SCANNED_32 = $scan_32,
														SCANNED_18 = $scan_18,
														SCANNED_C0 = $scan_C0
												WHERE CCID=$ccid") or die(mysql_error());
						
						//Update Debriefing table
						
						$qrydebriefupdate = mysql_query("UPDATE debriefinghdr SET 
														SCANNEDBY = '$employeeid',
														SCANNEDDATE = '$currentdate',
														SCANNEDREMARKS = '$scanned_remarks'
													WHERE CCID=$ccid
												") or die(mysql_error());
					
					break;
				// case "3"	: //Update Expiring Docs
					
						// $jis_remarks = mysql_real_escape_string($jis_remarks);

						// $qryupdate = mysql_query("UPDATE debrieflicense SET 
														// JIS_3GR = $jis_3gr,
														// JIS_GOC = $jis_goc,
														// JIS_ROC = $jis_roc,
														// JIS_MECA = $jis_meca,
														// JIS_LICENSE = $jis_license,
														// JIS_SCAF = $jis_scaf,
														// JIS_SSO = $jis_sso
												// WHERE CCID=$ccid") or die(mysql_error());
						
						// Update Debriefing table
						
						// $qrydebriefupdate = mysql_query("UPDATE debriefinghdr SET 
														// UPDATEDOCBY = '$employeeid',
														// UPDATEDOCDATE = '$currentdate',
														// UPDATEDOCREMARKS = '$jis_remarks'
													// WHERE CCID=$ccid
												// ") or die(mysql_error());
						
					// break;
				case "4"	:  //US VISA ENDORSEMENT
				
						if ($visaendorse == 1)
						{
							$visa_remarks = mysql_real_escape_string($visa_remarks);
							
							$qryusvisaverify = mysql_query("SELECT USVISAID FROM usvisaendorsement WHERE APPLICANTNO=$applicantno AND STATUS IS NULL") or die(mysql_error());
							
							if (mysql_num_rows($qryusvisaverify) == 0)
							{
								$qryusvisainsert = mysql_query("INSERT INTO usvisaendorsement(APPLICANTNO,ENDORSEDBY,ENDORSEDDATE,REMARKS) 
														VALUES ($applicantno,'$employeeid','$currentdate','$visa_remarks')") or die(mysql_error());
							}
							else 
							{
								$qryusvisaupdate = mysql_query("UPDATE usvisaendorsement SET 
																ENDORSEDBY = '$employeeid',
																ENDORSEDDATE = '$currentdate',
																REMARKS = '$visa_remarks'
													WHERE APPLICANTNO=$applicantno AND STATUS IS NULL") or die(mysql_error());
							}
							

						}
					
					break;
				case "5"	: //Scanning of Documents
					
						$scanned_remarks = mysql_real_escape_string($scanned_remarks);
				
						$qryupdate = mysql_query("UPDATE docsurrenderhdr SET 
														SCANNED_F1 = $scan_F1,
														SCANNED_41 = $scan_41,
														SCANNED_F2 = $scan_F2,
														SCANNED_P1 = $scan_P1,
														SCANNED_P2 = $scan_P2,
														SCANNED_42 = $scan_42,
														SCANNED_A4 = $scan_A4,
														SCANNED_32 = $scan_32,
														SCANNED_18 = $scan_18,
														SCANNED_C0 = $scan_C0
												WHERE CCID=$ccid") or die(mysql_error());
						
						//Update Debriefing table
						
						$qrydebriefupdate = mysql_query("UPDATE debriefinghdr SET 
														SCANNEDBY = '$employeeid',
														SCANNEDDATE = '$currentdate',
														SCANNEDREMARKS = '$scanned_remarks'
													WHERE CCID=$ccid
												") or die(mysql_error());
					
					break;
				case "6"	:
					
						$stored_remarks = mysql_real_escape_string($stored_remarks);
					
						$qryupdate = mysql_query("UPDATE docsurrenderhdr SET 
														STORED_F1 = $store_F1,
														STORED_41 = $store_41,
														STORED_F2 = $store_F2,
														STORED_P1 = $store_P1,
														STORED_P2 = $store_P2,
														STORED_42 = $store_42,
														STORED_A4 = $store_A4,
														STORED_32 = $store_32,
														STORED_18 = $store_18,
														STORED_C0 = $store_C0
												WHERE CCID=$ccid") or die(mysql_error());
						
						//Update Debriefing table
						
						$qrydebriefupdate = mysql_query("UPDATE debriefinghdr SET 
														STOREDBY = '$employeeid',
														STOREDDATE = '$currentdate',
														STOREDREMARKS = '$stored_remarks'
													WHERE CCID=$ccid
												") or die(mysql_error());
					
					break;
				case "7"	:
					
						//Update Debriefing table
						
						$qrydebriefupdate = mysql_query("UPDATE debriefinghdr SET 
														PRINTBY = '$employeeid',
														PRINTDATE = '$currentdate',
														STATUS = 1
													WHERE CCID=$ccid
												") or die(mysql_error());
						
						$qrydebriefclearupdate = mysql_query("UPDATE debriefingclearance SET 
														PRINTBY = '$employeeid',
														PRINTDATE = '$currentdate'
													WHERE CCID=$ccid
												") or die(mysql_error());
						
						$errormsg = "Debriefing Completed!";
					
					
					break;
			}
		
		break;
		
		case "save_ofcr":

			if (!empty($jis_examdate))
				$jis_examdateraw = "'" . date("Y-m-d",strtotime($jis_examdate)) . "'";
			else
				$jis_examdateraw = "NULL";

			$qryverify = mysql_query("SELECT DOCCODE FROM debriefjislicense WHERE CCID=$ccid AND DOCCODE='$jis_doccode'") or die(mysql_error());
			
			if (mysql_num_rows($qryverify) == 0)
			{
				$qryjisinsert = mysql_query("INSERT INTO debriefjislicense(CCID,DOCCODE,EXAMDATE,REMARKS_OFFICER,MADEBY_OFCR,MADEDATE_OFCR)
										VALUES($ccid,'$jis_doccode',$jis_examdateraw,'$jis_remarks_ofcr','$employeeid','$currentdate')
									") or die(mysql_error());
			}
			else
			{
				$qryjisupdate = mysql_query("UPDATE debriefjislicense SET 
													EXAMDATE = $jis_examdateraw,
													REMARKS_OFFICER = '$jis_remarks_ofcr',
													MADEBY_OFCR = '$employeeid',
													MADEDATE_OFCR = '$currentdate'
											WHERE CCID=$ccid AND DOCCODE='$jis_doccode'
									") or die(mysql_error());
			}
			
									
			$jis_doccode = "";
			$jis_examdate = "";
			$jis_remarks_ofcr = "";
		
		break;
		
		case "save_staff":

			if (!empty($jis_examdate2))
				$jis_examdate2raw = "'" . date("Y-m-d",strtotime($jis_examdate2)) . "'";
			else
				$jis_examdate2raw = "NULL";

			$qryverify = mysql_query("SELECT DOCCODE FROM debriefjislicense WHERE CCID=$ccid AND DOCCODE='$jis_doccode2'") or die(mysql_error());
			
			if (mysql_num_rows($qryverify) == 0)
			{
				$qryjisinsert = mysql_query("INSERT INTO debriefjislicense(CCID,DOCCODE,EXAMDATE,REMARKS_STAFF,MADEBY_STAFF,MADEDATE_STAFF)
										VALUES($ccid,'$jis_doccode2',$jis_examdate2raw,'$jis_remarks_staff','$employeeid','$currentdate')
									") or die(mysql_error());
			}
			else
			{
				$qryjisupdate = mysql_query("UPDATE debriefjislicense SET 
													EXAMDATE = $jis_examdate2raw,
													REMARKS_STAFF = '$jis_remarks_staff',
													MADEBY_STAFF = '$employeeid',
													MADEDATE_STAFF = '$currentdate'
											WHERE CCID=$ccid AND DOCCODE='$jis_doccode2'
									") or die(mysql_error());
			}
			
			$jis_doccode2 = "";
			$jis_examdate2 = "";
			$jis_remarks_staff = "";
			$jis_remarks_ofcr = "";
		
		break;
		
		case "cancel":
		
			$jis_doccode2 = "";
			$jis_examdate2 = "";
			$jis_remarks_staff = "";
			$jis_remarks_ofcr = "";
			
			$jis_doccode = "";
			$jis_examdate = "";
			$jis_remarks_ofcr = "";
		
		break;
		
		case "delete":
		
			switch($action)
			{
				case "3":  //FOR OFFICERS
					
					$qryjisdelete = mysql_query("DELETE FROM debriefjislicense WHERE IDNO=$delidno") or die(mysql_error());
					
				break;
				case "8":  //FOR STAFF
				
					$qrygetdel = mysql_query("SELECT DELETED,MADEDATE_OFCR FROM debriefjislicense WHERE IDNO=$delidno") or die(mysql_error());
					$rowgetdel = mysql_fetch_array($qrygetdel);
					
					$deltmp = $rowgetdel["DELETED"];
					$delbytmp = $rowgetdel["MADEDATE_OFCR"];
					
					if (!empty($delbytmp))
					{
						if ($deltmp == 0)
							$qryjisdelete = mysql_query("UPDATE debriefjislicense SET DELETED=1 WHERE IDNO=$delidno") or die(mysql_error());
						else
							$qryjisdelete = mysql_query("UPDATE debriefjislicense SET DELETED=0 WHERE IDNO=$delidno") or die(mysql_error());
					}
					else
					{
						$qryjisdelete = mysql_query("DELETE FROM debriefjislicense WHERE IDNO=$delidno") or die(mysql_error());
					}
				
				break;
			}
		
		break;
	
}

$content = "";
	
$fontsize = "font-size:0.9em;";

switch ($action)
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
				<tr>
					<td>Height</td>
					<td>:</td>
					<td><input type=\"text\" name=\"height\" value=\"$height\" 
							onKeyPress=\"return numbersonly(this);\" size=\"10\" maxlength=\"10\"
							onkeydown=\"chkCR();\" />
						&nbsp;&nbsp;cm.
					</td>
				</tr>
				<tr>
					<td>Weight</td>
					<td>:</td>
					<td><input type=\"text\" name=\"weight\" value=\"$weight\" 
							onKeyPress=\"return numbersonly(this);\" size=\"10\" maxlength=\"10\"
							onkeydown=\"chkCR();\" />
						&nbsp;&nbsp;kls.
					</td>
				</tr>
			</table>
			
			";
		
		break;
	
	case "2"	:
		
			if ($actiontxt != "save")
			{
				$qrygetvalues = mysql_query("SELECT d.CCID,SURRENDERED_F1,SURRENDERED_41,SURRENDERED_F2,SURRENDERED_P1,
												SURRENDERED_P2,SURRENDERED_42,SURRENDERED_A4,SURRENDERED_32,SURRENDERED_18,SURRENDERED_C0,
												dh.SURRENDERREMARKS
												FROM docsurrenderhdr d
												LEFT JOIN debriefinghdr dh ON dh.CCID=d.CCID
												WHERE d.CCID=$ccid") or die(mysql_error());
				
				if (mysql_num_rows($qrygetvalues) > 0)
				{
					$rowgetvalues = mysql_fetch_array($qrygetvalues);
					
					if($rowgetvalues["SURRENDERED_41"])
						$chksur41 = $checked;
						
					if($rowgetvalues["SURRENDERED_F2"])
						$chksurF2 = $checked;
						
					if($rowgetvalues["SURRENDERED_F1"])
						$chksurF1 = $checked;
						
					if($rowgetvalues["SURRENDERED_32"])
						$chksur32 = $checked;
						
					if($rowgetvalues["SURRENDERED_P2"])
						$chksurP2 = $checked;
						
					if($rowgetvalues["SURRENDERED_P1"])
						$chksurP1 = $checked;
						
					if($rowgetvalues["SURRENDERED_42"])
						$chksur42 = $checked;
						
					if($rowgetvalues["SURRENDERED_A4"])
						$chksurA4 = $checked;
						
					if($rowgetvalues["SURRENDERED_18"])
						$chksur18 = $checked;
						
					if($rowgetvalues["SURRENDERED_C0"])
						$chksurC0 = $checked;
						
					$surrender_remarks = stripslashes($rowgetvalues["SURRENDERREMARKS"]);
				}
				
				$qrygetvalues = mysql_query("SELECT d.CCID,SCANNED_F1,SCANNED_41,SCANNED_F2,SCANNED_P1,
												SCANNED_P2,SCANNED_42,SCANNED_A4,SCANNED_32,SCANNED_18,SCANNED_C0,
												dh.SCANNEDREMARKS
												FROM docsurrenderhdr d
												LEFT JOIN debriefinghdr dh ON dh.CCID=d.CCID
												WHERE d.CCID=$ccid") or die(mysql_error());
				
				if (mysql_num_rows($qrygetvalues) > 0)
				{
					$rowgetvalues = mysql_fetch_array($qrygetvalues);
					
					if($rowgetvalues["SCANNED_41"])
						$chkscan41 = $checked;
						
					if($rowgetvalues["SCANNED_F2"])
						$chkscanF2 = $checked;
						
					if($rowgetvalues["SCANNED_F1"])
						$chkscanF1 = $checked;
						
					if($rowgetvalues["SCANNED_32"])
						$chkscan32 = $checked;
						
					if($rowgetvalues["SCANNED_P2"])
						$chkscanP2 = $checked;
						
					if($rowgetvalues["SCANNED_P1"])
						$chkscanP1 = $checked;
						
					if($rowgetvalues["SCANNED_42"])
						$chkscan42 = $checked;
						
					if($rowgetvalues["SCANNED_A4"])
						$chkscanA4 = $checked;
						
					if($rowgetvalues["SCANNED_18"])
						$chkscan18 = $checked;
						
					if($rowgetvalues["SCANNED_C0"])
						$chkscanC0 = $checked;
						
					$scanned_remarks = stripslashes($rowgetvalues["SCANNEDREMARKS"]);
				}
			}
		
			$content = "
			<!--
			<span class=\"sectiontitle\">DOCUMENTS SURRENDERED</span>
			<br />
			-->
			<center>
			<table style=\"width:80%;$fontsize\">
				<tr>
					<th><u>DOCUMENTS</u></th>
					<th><u>SURRENDERED</u></th>
					<th><u>SCANNED</u></th>
				</tr>
				<tr $mouseovereffect>
					<td>PASSPORT</td>
					<td align=\"center\"><input type=\"checkbox\" name=\"surr_41\" onclick=\"surrender_remarks.value += 'PASSPORT/ ';\" $chksur41 /></td>
					<td align=\"center\"><input type=\"checkbox\" name=\"scan_41\" onclick=\"scanned_remarks.value += 'PASSPORT/ ';\" $chkscan41 /></td>
				</tr>
				<tr $mouseovereffect>
					<td>PHIL SEAMAN'S BOOK</td>
					<td align=\"center\"><input type=\"checkbox\" name=\"surr_F2\" onclick=\"surrender_remarks.value += 'PHIL SEAMAN BOOK/ ';\" $chksurF2 /></td>
					<td align=\"center\"><input type=\"checkbox\" name=\"scan_F2\" onclick=\"scanned_remarks.value += 'PHIL SEAMAN BOOK/ ';\" $chkscanF2 /></td>
				</tr>
<!--
				<tr $mouseovereffect>
					<td>PHIL LICENSE</td>
					<td align=\"center\"><input type=\"checkbox\" name=\"surr_F1\" onclick=\"surrender_remarks.value += 'PHIL LIC/ ';\" $chksurF1 /></td>
				</tr>
-->
				<tr $mouseovereffect>
					<td>SRC</td>
					<td align=\"center\"><input type=\"checkbox\" name=\"surr_32\" onclick=\"surrender_remarks.value += 'SRC/ ';\" $chksur32 /></td>
					<td align=\"center\"><input type=\"checkbox\" name=\"scan_32\" onclick=\"scanned_remarks.value += 'SRC/ ';\" $chkscan32 /></td>
				</tr>
				<tr $mouseovereffect>
					<td>PANAMA SEAMAN'S BOOK</td>
					<td align=\"center\"><input type=\"checkbox\" name=\"surr_P2\" onclick=\"surrender_remarks.value += 'PANAMA SEAMAN BOOK/ ';\" $chksurP2 /></td>
					<td align=\"center\"><input type=\"checkbox\" name=\"scan_P2\" onclick=\"scanned_remarks.value += 'PANAMA SEAMAN BOOK/ ';\" $chkscanP2 /></td>
				</tr>
<!--
				<tr $mouseovereffect>
					<td>PANAMA LICENSE</td>
					<td align=\"center\"><input type=\"checkbox\" name=\"surr_P1\" onclick=\"surrender_remarks.value += 'PANAMA LIC/ ';\" $chksurP1 /></td>
				</tr>
-->
				<tr $mouseovereffect>
					<td>U.S. VISA</td>
					<td align=\"center\"><input type=\"checkbox\" name=\"surr_42\" onclick=\"surrender_remarks.value += 'U.S. VISA/ ';\" $chksur42 /></td>
					<td align=\"center\"><input type=\"checkbox\" name=\"scan_42\" onclick=\"scanned_remarks.value += 'U.S. VISA/ ';\" $chkscan42 /></td>
				</tr>
<!--
				<tr $mouseovereffect>
					<td>AU MCV</td>
					<td align=\"center\"><input type=\"checkbox\" name=\"surr_A4\" onclick=\"surrender_remarks.value += 'AU MCV/ ';\" $chksurA4 /></td>
				</tr>
-->
				<tr $mouseovereffect>
					<td>COC (OFFICERS)</td>
					<td align=\"center\"><input type=\"checkbox\" name=\"surr_18\" onclick=\"surrender_remarks.value += 'COC(OFFICER)/ ';\" $chksur18 /></td>
					<td align=\"center\"><input type=\"checkbox\" name=\"scan_18\" onclick=\"scanned_remarks.value += 'COC(OFFICER)/ ';\" $chkscan18 /></td>
				</tr>
				<tr $mouseovereffect>
					<td>COC (RATINGS)</td>
					<td align=\"center\"><input type=\"checkbox\" name=\"surr_C0\" onclick=\"surrender_remarks.value += 'COC(RATINGS)/ ';\" $chksurC0 /></td>
					<td align=\"center\"><input type=\"checkbox\" name=\"scan_C0\" onclick=\"scanned_remarks.value += 'COC(RATINGS)/ ';\" $chkscanC0 /></td>
				</tr>
				<tr><td colspan=\"3\">&nbsp;</td></tr>
				<tr $mouseovereffect>
					<td colspan=\"3\">
						<u><b>Surrender Remarks</b></u> <br />
						<textarea rows=3 cols=55 name=\"surrender_remarks\">$surrender_remarks</textarea>
					</td>
				</tr>
				<tr><td colspan=\"3\">&nbsp;</td></tr>
				<tr $mouseovereffect>
					<td colspan=\"3\">
						<u><b>Scanning Remarks</b></u> <br />
						<textarea rows=3 cols=55 name=\"scanned_remarks\">$scanned_remarks</textarea>
					</td>
				</tr>
			</table>
			</center>
			";
			
		break;
	case "4"	:
			
			$content = "
			<span class=\"sectiontitle\">U.S. VISA ENDORSEMENT</span>
			<br />
			
			<center>
<!--
			<table style=\"width:90%;$fontsize\">
				<tr>
					<td width=\"30%\">Vessel Assigned</td>
					<td width=\"4%\">:</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>ETD</td>
					<td>:</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td colspan=\"3\"><hr /></td>
				</tr>
			</table>
-->
			<table style=\"width:90%;$fontsize\">
				<tr>
					<td width=\"20%\">Endorse?</td>
					<td width=\"4%\">:</td>
					<td width=\"38%\" align=\"center\" style=\"font-size:0.8em;font-weight:Bold;\">
						<input type=\"checkbox\" name=\"visaendorse\" $chkendorse />
					</td>
				</tr>
				<tr>
					<td colspan=\"3\">&nbsp;</td>
				</tr>
				<tr>
					<td colspan=\"3\">
						<u><b>Remarks</b></u> <br />
						<textarea rows=3 cols=40 name=\"visa_remarks\">$visa_remarks</textarea>
					</td>
				</tr>
			</table>
				
			
			</center>
			
			";
		
		break;
	case "5"	:

			if ($actiontxt != "save")
			{
				$qrygetvalues = mysql_query("SELECT d.CCID,SCANNED_F1,SCANNED_41,SCANNED_F2,SCANNED_P1,
												SCANNED_P2,SCANNED_42,SCANNED_A4,SCANNED_32,SCANNED_18,SCANNED_C0,
												dh.SCANNEDREMARKS
												FROM docsurrenderhdr d
												LEFT JOIN debriefinghdr dh ON dh.CCID=d.CCID
												WHERE d.CCID=$ccid") or die(mysql_error());
				
				if (mysql_num_rows($qrygetvalues) > 0)
				{
					$rowgetvalues = mysql_fetch_array($qrygetvalues);
					
					if($rowgetvalues["SCANNED_41"])
						$chkscan41 = $checked;
						
					if($rowgetvalues["SCANNED_F2"])
						$chkscanF2 = $checked;
						
					if($rowgetvalues["SCANNED_F1"])
						$chkscanF1 = $checked;
						
					if($rowgetvalues["SCANNED_32"])
						$chkscan32 = $checked;
						
					if($rowgetvalues["SCANNED_P2"])
						$chkscanP2 = $checked;
						
					if($rowgetvalues["SCANNED_P1"])
						$chkscanP1 = $checked;
						
					if($rowgetvalues["SCANNED_42"])
						$chkscan42 = $checked;
						
					if($rowgetvalues["SCANNED_A4"])
						$chkscanA4 = $checked;
						
					if($rowgetvalues["SCANNED_18"])
						$chkscan18 = $checked;
						
					if($rowgetvalues["SCANNED_C0"])
						$chkscanC0 = $checked;
						
					$scanned_remarks = stripslashes($rowgetvalues["SCANNEDREMARKS"]);
				}
			}
		
			$content = "
			<span class=\"sectiontitle\">SCANNING OF DOCUMENTS</span>
			<br />
			
			<center>
			<table style=\"width:80%;$fontsize\">
				<tr>
					<th><u>DOCUMENTS</u></th>
					<th><u>SCANNED</u></th>
				</tr>
				<tr $mouseovereffect>
					<td>PASSPORT</td>
					<td align=\"center\"><input type=\"checkbox\" name=\"scan_41\" onclick=\"scanned_remarks.value += 'PASSPORT/ ';\" $chkscan41 /></td>
				</tr>
				<tr $mouseovereffect>
					<td>PHIL SEAMAN'S BOOK</td>
					<td align=\"center\"><input type=\"checkbox\" name=\"scan_F2\" onclick=\"scanned_remarks.value += 'PHIL SEAMAN BOOK/ ';\" $chkscanF2 /></td>
				</tr>
<!--
				<tr $mouseovereffect>
					<td>PHIL LICENSE</td>
					<td align=\"center\"><input type=\"checkbox\" name=\"scan_F1\" onclick=\"scanned_remarks.value += 'PHIL LIC/ ';\" $chkscanF1 /></td>
				</tr>
-->
				<tr $mouseovereffect>
					<td>SRC</td>
					<td align=\"center\"><input type=\"checkbox\" name=\"scan_32\" onclick=\"scanned_remarks.value += 'SRC/ ';\" $chkscan32 /></td>
				</tr>
				<tr $mouseovereffect>
					<td>PANAMA SEAMAN'S BOOK</td>
					<td align=\"center\"><input type=\"checkbox\" name=\"scan_P2\" onclick=\"scanned_remarks.value += 'PANAMA SEAMAN BOOK/ ';\" $chkscanP2 /></td>
				</tr>
<!--
				<tr $mouseovereffect>
					<td>PANAMA LICENSE</td>
					<td align=\"center\"><input type=\"checkbox\" name=\"scan_P1\" onclick=\"scanned_remarks.value += 'PANAMA LIC/ ';\" $chkscanP1 /></td>
				</tr>
-->
				<tr $mouseovereffect>
					<td>U.S. VISA</td>
					<td align=\"center\"><input type=\"checkbox\" name=\"scan_42\" onclick=\"scanned_remarks.value += 'U.S. VISA/ ';\" $chkscan42 /></td>
				</tr>
<!--
				<tr $mouseovereffect>
					<td>AU MCV</td>
					<td align=\"center\"><input type=\"checkbox\" name=\"scan_A4\" onclick=\"scanned_remarks.value += 'AU MCV/ ';\" $chkscanA4 /></td>
				</tr>
-->
				<tr $mouseovereffect>
					<td>COC (OFFICERS)</td>
					<td align=\"center\"><input type=\"checkbox\" name=\"scan_18\" onclick=\"scanned_remarks.value += 'COC(OFFICER)/ ';\" $chkscan18 /></td>
				</tr>
				<tr $mouseovereffect>
					<td>COC (RATINGS)</td>
					<td align=\"center\"><input type=\"checkbox\" name=\"scan_C0\" onclick=\"scanned_remarks.value += 'COC(RATINGS)/ ';\" $chkscanC0 /></td>
				</tr>
				<tr><td colspan=\"2\">&nbsp;</td></tr>
				<tr $mouseovereffect>
					<td colspan=\"2\">
						<u><b>Remarks</b></u> <br />
						<textarea rows=3 cols=45 name=\"scanned_remarks\">$scanned_remarks</textarea>
					</td>
				</tr>
			</table>
			</center>
			";
		
		break;
	case "6"	:

			if ($actiontxt != "save")
			{
				$qrygetvalues = mysql_query("SELECT d.CCID,STORED_F1,STORED_41,STORED_F2,STORED_P1,
												STORED_P2,STORED_42,STORED_A4,STORED_32,STORED_18,STORED_C0,
												dh.STOREDREMARKS
												FROM docsurrenderhdr d
												LEFT JOIN debriefinghdr dh ON dh.CCID=d.CCID
												WHERE d.CCID=$ccid") or die(mysql_error());
				
				if (mysql_num_rows($qrygetvalues) > 0)
				{
					$rowgetvalues = mysql_fetch_array($qrygetvalues);
					
					if($rowgetvalues["STORED_41"])
						$chkstore41 = $checked;
						
					if($rowgetvalues["STORED_F2"])
						$chkstoreF2 = $checked;
						
					if($rowgetvalues["STORED_F1"])
						$chkstoreF1 = $checked;
						
					if($rowgetvalues["STORED_32"])
						$chkstore32 = $checked;
						
					if($rowgetvalues["STORED_P2"])
						$chkstoreP2 = $checked;
						
					if($rowgetvalues["STORED_P1"])
						$chkstoreP1 = $checked;
						
					if($rowgetvalues["STORED_42"])
						$chkstore42 = $checked;
						
					if($rowgetvalues["STORED_A4"])
						$chkstoreA4 = $checked;
						
					if($rowgetvalues["STORED_18"])
						$chkstore18 = $checked;
						
					if($rowgetvalues["STORED_C0"])
						$chkstoreC0 = $checked;
						
					$stored_remarks = stripslashes($rowgetvalues["STOREDREMARKS"]);
				}
			}
		
			$content = "
			<span class=\"sectiontitle\">STORING OF DOCUMENTS</span>
			<br />
			
			<center>
			<table style=\"width:80%;$fontsize\">
				<tr>
					<th><u>DOCUMENTS</u></th>
					<th><u>STORED</u></th>
				</tr>
				<tr $mouseovereffect>
					<td>PASSPORT</td>
					<td align=\"center\"><input type=\"checkbox\" name=\"store_41\" onclick=\"stored_remarks.value += 'PASSPORT/ ';\" $chkstore41 /></td>
				</tr>
				<tr $mouseovereffect>
					<td>PHIL SEAMAN'S BOOK</td>
					<td align=\"center\"><input type=\"checkbox\" name=\"store_F2\" onclick=\"stored_remarks.value += 'PHIL SEAMAN BOOK/ ';\" $chkstoreF2 /></td>
				</tr>
<!--
				<tr $mouseovereffect>
					<td>PHIL LICENSE</td>
					<td align=\"center\"><input type=\"checkbox\" name=\"store_F1\" onclick=\"stored_remarks.value += 'PHIL LIC/ ';\" $chkstoreF1 /></td>
				</tr>
-->
				<tr $mouseovereffect>
					<td>SRC</td>
					<td align=\"center\"><input type=\"checkbox\" name=\"store_32\" onclick=\"stored_remarks.value += 'SRC/ ';\" $chkstore32 /></td>
				</tr>
				<tr $mouseovereffect>
					<td>PANAMA SEAMAN'S BOOK</td>
					<td align=\"center\"><input type=\"checkbox\" name=\"store_P2\" onclick=\"stored_remarks.value += 'PANAMA SEAMAN BOOK/ ';\" $chkstoreP2 /></td>
				</tr>
<!--
				<tr $mouseovereffect>
					<td>PANAMA LICENSE</td>
					<td align=\"center\"><input type=\"checkbox\" name=\"store_P1\" onclick=\"stored_remarks.value += 'PANAMA LIC/ ';\" $chkstoreP1 /></td>
				</tr>
-->
				<tr $mouseovereffect>
					<td>U.S. VISA</td>
					<td align=\"center\"><input type=\"checkbox\" name=\"store_42\" onclick=\"stored_remarks.value += 'U.S. VISA/ ';\" $chkstore42 /></td>
				</tr>
<!--
				<tr $mouseovereffect>
					<td>AU MCV</td>
					<td align=\"center\"><input type=\"checkbox\" name=\"store_A4\" onclick=\"stored_remarks.value += 'AU MCV/ ';\" $chkstoreA4 /></td>
				</tr>
-->
				<tr $mouseovereffect>
					<td>COC (OFFICERS)</td>
					<td align=\"center\"><input type=\"checkbox\" name=\"store_18\" onclick=\"stored_remarks.value += 'COC(OFFICER)/ ';\" $chkstore18 /></td>
				</tr>
				<tr $mouseovereffect>
					<td>COC (RATINGS)</td>
					<td align=\"center\"><input type=\"checkbox\" name=\"store_C0\" onclick=\"stored_remarks.value += 'COC(RATINGS)/ ';\" $chkstoreC0 /></td>
				</tr>
				<tr><td colspan=\"2\">&nbsp;</td></tr>
				<tr $mouseovereffect>
					<td colspan=\"2\">
						<u><b>Remarks</b></u> <br />
						<textarea rows=3 cols=45 name=\"stored_remarks\">$stored_remarks</textarea>
					</td>
				</tr>
			</table>
			</center>
			
			";
			
		break;
	case "7"	:

			$content = "
			<span class=\"sectiontitle\">PRINTING OF FORMS</span>
			<br />
			
			<center>
			<table style=\"width:80%;$fontsize\">
				<tr $mouseovereffect>
					<td style=\"font-size:0.9em;font-weight:Bold;\">Debriefing Form (FM-227)</td>
					<td align=\"center\"><input type=\"button\" value=\"Print\" onclick=\"openWindow('debriefingformshow.php?applicantno=$applicantno&load=1&ccid=$ccid&print=1', 'debriefingfleet' ,0, 0);\" /></td>
				</tr>
				<tr $mouseovereffect>
					<td style=\"font-size:0.9em;font-weight:Bold;\">Clearance Checklist (FM-229)</td>
					<td align=\"center\"><input type=\"button\" value=\"Print\" onclick=\"openWindow('debriefing_clearanceformshow.php?applicantno=$applicantno&load=1&ccid=$ccid&print=1', 'debriefingfleet' ,0, 0);\" /></td>
				</tr>
			<!--
				<tr $mouseovereffect>
					<td style=\"font-size:0.9em;font-weight:Bold;\">U.S. VISA Endorsement</td>
					<td align=\"center\"><input type=\"button\" value=\"Print\" onclick=\"openWindow('rependorseusvisa.php?applicantno=$applicantno&ccid=$ccid', 'debriefingfleet' ,0, 0);\" /></td>
				</tr>
			-->
			</table>
			</center>
			";
			
		break;
	case "3"	:  //CHECKING OF JIS FOR OFFICERS
			
// Japanese 3rd Grade - 1
// Japanese GOC - 2
// Japanese ROC - 3
// Japanese License - 4
// Japanese MECA - 5
// Japanese SC/AF - 6
// Japanese SSO - 7


				
			$qryjapandocs = mysql_query("SELECT DOCCODE,DOCUMENT FROM crewdocuments where DOCCODE LIKE 'J%'") or die(mysql_error());

			$selected = "";
			$jis_select = "";
			while ($rowjapandocs = mysql_fetch_array($qryjapandocs))
			{
				$jdoccode = $rowjapandocs["DOCCODE"];
				$jdocument = $rowjapandocs["DOCUMENT"];
				
				if ($jis_doccode == $jdoccode)
					$selected = "SELECTED";
					
				$jis_select .= "<option value=\"$jdoccode\">$jdocument</option>";
			}
		
			$content = "
			<span class=\"sectiontitle\">CHECKING OF JIS BY FLEET OFFICERS</span>
			<br />
			
			<center>
				
				<table style=\"width:80%;$fontsize\">
					<tr>
						<td width=\"30%\">Type</td>
						<td width=\"5%\">:</td>
						<td width=\"65%\">
							<select name=\"jis_doccode\">
								<option value=\"\">--Select One--</option>
								$jis_select
							</select>
						</td>
					</tr>
					<tr>
						<td>Exam Date</td>
						<td>:</td>
						<td>
							<input type=\"text\" name=\"jis_examdate\" value=\"$jis_examdate\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
							<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(jis_examdate, jis_examdate, 'mm/dd/yyyy', 0, 0);return false;\">
							&nbsp;&nbsp;&nbsp;(mm/dd/yyy)
						</td>
					</tr>
					<tr>
						<td valign=\"top\">Remarks</td>
						<td>:</td>
						<td>
							<textarea name=\"jis_remarks_ofcr\" rows=\"4\" cols=\"25\">$jis_remarks_ofcr</textarea>
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>
							<input type=\"button\" value=\"Save\" onclick=\"actiontxt.value='save_ofcr';submit();\" />
							<input type=\"button\" value=\"Cancel\" onclick=\"actiontxt.value='cancel';submit();\" />
						</td>
					</tr>
				</table>
				<br />
			</center>
			";
			
			$content .= "
			<center>
			<div style=\"width:99%;height:175px;overflow:auto;\">
				<table class=\"listcol\" style=\"width:100%;height:100%;\">
					<tr>
						<th width=\"30%\">License Type</th>
						<th width=\"15%\">Exam Date</th>
						<th width=\"25%\">Ofcr Remarks</th>
						<th width=\"25%\">Staff Remarks</th>
						<th width=\"5%\">&nbsp;</th>
					</tr>";
					
					$qrygetjisdtls = mysql_query("SELECT dj.IDNO,dj.DOCCODE,cd.DOCUMENT,dj.EXAMDATE,dj.REMARKS_OFFICER,dj.REMARKS_STAFF,dj.DELETED 
										FROM debriefjislicense dj
										LEFT JOIN crewdocuments cd ON cd.DOCCODE=dj.DOCCODE
										WHERE CCID=$ccid
										") or die(mysql_error());
										
					while ($rowgetjisdtls = mysql_fetch_array($qrygetjisdtls))
					{
						$detail1 = $rowgetjisdtls["IDNO"];
						$detail2 = $rowgetjisdtls["DOCCODE"];
						$detail3 = $rowgetjisdtls["DOCUMENT"];
						
						if (!empty($rowgetjisdtls["EXAMDATE"]))
							$detail4 = date("m/d/Y",strtotime($rowgetjisdtls["EXAMDATE"]));
						else
							$detail4 = "---";
							
						$detail5 = $rowgetjisdtls["REMARKS_OFFICER"];
						$detail6 = $rowgetjisdtls["REMARKS_STAFF"];
						$detail7 = $rowgetjisdtls["DELETED"];
						
						$content .= "
						<tr $mouseovereffect style=\"cursor:pointer;\" 
							ondblclick=\"jis_doccode.value='$detail2';jis_examdate.value='$detail4';jis_remarks_ofcr.value='$detail5';\" >
							<td>$detail3</td>
							<td align=\"center\">$detail4</td>
							<td >&nbsp;$detail5</td>
							<td >&nbsp;$detail6</td>
							<td><a href=\"#\" onclick=\"actiontxt.value='delete';delidno.value='$detail1';submit();\" style=\"font-weight:Bold;color:Blue;cursor:pointer;\" >DEL</a></td>
						</tr>
						";
					}
					
				$content .= "
				</table>
			</div>
			</center>
			";
			
		break;
	case "8"	:  //CHECKING OF JIS FOR STAFF
				
			$qryjapandocs = mysql_query("SELECT DOCCODE,DOCUMENT FROM crewdocuments where DOCCODE LIKE 'J%'") or die(mysql_error());

			$selected2= "";
			$jis_select2 = "";
			while ($rowjapandocs = mysql_fetch_array($qryjapandocs))
			{
				$jdoccode = $rowjapandocs["DOCCODE"];
				$jdocument = $rowjapandocs["DOCUMENT"];
				
				if ($jis_doccode2 == $jdoccode)
					$selected2 = "SELECTED";
					
				$jis_select2 .= "<option value=\"$jdoccode\">$jdocument</option>";
			}
		
			$content = "
			<span class=\"sectiontitle\">CHECKING OF JIS BY STAFF</span>
			<br />
			
			<center>
				
				<table style=\"width:80%;$fontsize\">
					<tr>
						<td width=\"30%\">Type</td>
						<td width=\"5%\">:</td>
						<td width=\"65%\">
							<select name=\"jis_doccode2\">
								<option value=\"\">--Select One--</option>
								$jis_select2
							</select>
						</td>
					</tr>
					<tr>
						<td>Exam Date</td>
						<td>:</td>
						<td>
							<input type=\"text\" name=\"jis_examdate2\" value=\"$jis_examdate2\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
							<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(jis_examdate2, jis_examdate2, 'mm/dd/yyyy', 0, 0);return false;\">
							&nbsp;&nbsp;&nbsp;(mm/dd/yyy)
						</td>
					</tr>
					<tr>
						<td valign=\"top\">Remarks by Officer</td>
						<td>:</td>
						<td>
							<textarea name=\"jis_remarks_ofcr\" rows=\"2\" cols=\"25\" readonly=\"readonly\">$jis_remarks_ofcr</textarea>
						</td>
					</tr>
					<tr>
						<td valign=\"top\">Remarks by Staff</td>
						<td>:</td>
						<td>
							<textarea name=\"jis_remarks_staff\" rows=\"3\" cols=\"25\">$jis_remarks_staff</textarea>
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>
							<input type=\"button\" value=\"Save\" onclick=\"actiontxt.value='save_staff';submit();\" />
							<input type=\"button\" value=\"Cancel\" onclick=\"actiontxt.value='cancel';submit();\" />
						</td>
					</tr>
				</table>
				<br />
			</center>
			";
			
			$content .= "
			<center>
			<div style=\"width:99%;height:150px;overflow:auto;\">
				<table class=\"listcol\" style=\"width:100%;height:100%;\">
					<tr>
						<th width=\"20%\">License Type</th>
						<th width=\"15%\">Exam Date</th>
						<th width=\"30%\">Ofcr Remarks</th>
						<th width=\"30%\">Staff Remarks</th>
						<th width=\"5%\">&nbsp;</th>
					</tr>";
					
					$qrygetjisdtls = mysql_query("SELECT dj.IDNO,dj.DOCCODE,cd.DOCUMENT,dj.EXAMDATE,dj.REMARKS_OFFICER,dj.REMARKS_STAFF,dj.DELETED 
										FROM debriefjislicense dj
										LEFT JOIN crewdocuments cd ON cd.DOCCODE=dj.DOCCODE
										WHERE CCID=$ccid
										") or die(mysql_error());
										
					while ($rowgetjisdtls = mysql_fetch_array($qrygetjisdtls))
					{
						$detail1 = $rowgetjisdtls["IDNO"];
						$detail2 = $rowgetjisdtls["DOCCODE"];
						$detail3 = $rowgetjisdtls["DOCUMENT"];
						
						if (!empty($rowgetjisdtls["EXAMDATE"]))
							$detail4 = date("m/d/Y",strtotime($rowgetjisdtls["EXAMDATE"]));
						else
							$detail4 = "---";
							
						$detail5 = $rowgetjisdtls["REMARKS_OFFICER"];
						$detail6 = $rowgetjisdtls["REMARKS_STAFF"];
						$detail7 = $rowgetjisdtls["DELETED"];
						
						$styledeleted = "";
						if ($detail7 == 1)
						{
							$delshow = "UNDO";
							$styledeleted = "background-color:Red;";
							$ondblclick = "ondblclick=\"alert('You must click UNDO first, to edit entry.');\"";
						}
						else
						{
							$delshow = "DEL";
							$ondblclick = "ondblclick=\"jis_doccode2.value='$detail2';jis_examdate2.value='$detail4';jis_remarks_ofcr.value='$detail5';jis_remarks_staff.value='$detail6';\"";
						}
						
						$content .= "
						<tr $mouseovereffect style=\"cursor:pointer;\" $ondblclick >
							<td style=\"$styledeleted\">$detail3</td>
							<td style=\"$styledeleted\" align=\"center\">$detail4</td>
							<td style=\"$styledeleted\">&nbsp;$detail5</td>
							<td style=\"$styledeleted\">&nbsp;$detail6</td>
							<td style=\"$styledeleted\"><a href=\"#\" onclick=\"actiontxt.value='delete';delidno.value='$detail1';submit();\" style=\"font-weight:Bold;color:Blue;cursor:pointer;\" >$delshow</a></td>
						</tr>
						";
					}
					
				$content .= "
				</table>
			</div>
			</center>
			";
			
		break;
}






echo "
<html>
<head>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>
<script language=\"javascript\" src=\"popcalendar.js\"></script>	
</head>
<body style=\"overflow:hidden;\">

<form name=\"debriefedit\" method=\"POST\">

	$content
	<br />
	
	<center>
	";

	if (empty($errormsg) && $action != "3" && $action != "8")
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
	<input type=\"hidden\" name=\"delidno\" />
	
</form>
</body>

</html>
";