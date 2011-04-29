<?php
include('veritas/connectdb.php');
include("veritas/include/stylephp.inc");
include("veritas/include/functions.inc");

session_start();

if(isset($_SESSION['employeeid']))
	$employeeid=$_SESSION['employeeid'];
	
//if (isset($_SESSION['departmentid']))
//	$departmentid = $_SESSION['departmentid'];

$qrydepartment = mysql_query("SELECT DEPARTMENTID FROM employee WHERE EMPLOYEEID='$employeeid'") or die(mysql_error());
$rowdepartment = mysql_fetch_array($qrydepartment);
$departmentid = $rowdepartment["DEPARTMENTID"];

	
$currentdate = date("Y-m-d H:i:s");
$datenow = date("m/d/Y");
$dateformat = "dMY";

if(isset($_POST["ccid"]))
	$ccid=$_POST["ccid"];
else 
	$ccid=$_GET["ccid"];
	
if(isset($_POST["applicantno"]))
	$applicantno=$_POST["applicantno"];
else 
	$applicantno=$_GET["applicantno"];
	


//retrieve details of debriefing

$qrydebrief = mysql_query("SELECT REPORTEDDATE,ARRMNLDATE,CONFIRMARRMNLBY,CONFIRMARRMNLDATE,FILLUPDATE,FLEETBY,FLEETDATE,TRAININGBY,TRAININGDATE,DIVISIONBY,DIVISIONDATE,MANAGEMENTBY,MANAGEMENTDATE,
					UPDATEDOCBY,UPDATEDOCDATE,ACCOUNTINGBY,ACCOUNTINGDATE,PRINTBY,PRINTDATE,SURRENDERTO,SURRENDERDATE,SCANNEDBY,SCANNEDDATE,STOREDBY,STOREDDATE,
					SCHOLARBY,SCHOLARDATE,SCHOLARREMARKS,dh.MADEBY,dh.MADEDATE,
					IF (cs.APPLICANTNO IS NULL,0,1) AS SCHOLAR,
					IF (ct.APPLICANTNO IS NULL,0,1) AS FASTTRACK
					FROM debriefinghdr dh
					LEFT JOIN crewchange cc ON cc.CCID=dh.CCID
					LEFT JOIN crewscholar cs ON cs.APPLICANTNO=cc.APPLICANTNO
					LEFT JOIN crewfasttrack ct ON ct.APPLICANTNO=cc.APPLICANTNO
					WHERE dh.CCID=$ccid") or die(mysql_error());

		$confirmarrmnlby = "---";
		$fleetby = "---";
		$trainingby = "---";
		$divisionby = "---";
		$managementby = "---";
		$updatedocby = "---";
		$accountingby = "---";
		$printby = "---";
		
		$surrenderto = "---";
		$scannedby = "---";
		$storedby = "---";
		$scholarby = "---";
		
		$endorsedby = "---";
		$madeby = "---";
		
if (mysql_num_rows($qrydebrief) > 0)
{
	$rowdebrief = mysql_fetch_array($qrydebrief);
	
	$scholar = $rowdebrief["SCHOLAR"];
	$fasttrack = $rowdebrief["FASTTRACK"];
	$madeby = $rowdebrief["MADEBY"];
	$madedate = $rowdebrief["MADEDATE"];
	
	if (!empty($rowdebrief["MADEDATE"]))
		$madedate = $rowdebrief["MADEDATE"];
	
	$arrmnldate = $rowdebrief["ARRMNLDATE"];
	
	if (!empty($rowdebrief["CONFIRMARRMNLBY"]))
		$confirmarrmnlby = $rowdebrief["CONFIRMARRMNLBY"];
		
	$confirmarrmnldate = date($dateformat,strtotime($rowdebrief["CONFIRMARRMNLDATE"]));
	
	$fillupdate = $rowdebrief["FILLUPDATE"];
	if (!empty($rowdebrief["FLEETBY"]))
		$fleetby = $rowdebrief["FLEETBY"];

	$fleetdate = $rowdebrief["FLEETDATE"];
	if (!empty($rowdebrief["TRAININGBY"]))
		$trainingby = $rowdebrief["TRAININGBY"];

	$trainingdate = $rowdebrief["TRAININGDATE"];
	if (!empty($rowdebrief["DIVISIONBY"]))
		$divisionby = $rowdebrief["DIVISIONBY"];

	$divisiondate = $rowdebrief["DIVISIONDATE"];
	if (!empty($rowdebrief["MANAGEMENTBY"]))
		$managementby = $rowdebrief["MANAGEMENTBY"];

	$managementdate = $rowdebrief["MANAGEMENTDATE"];
	
	if (!empty($rowdebrief["UPDATEDOCBY"]))  //USED BY JIS BY OFFICERS
		$updatedocby = $rowdebrief["UPDATEDOCBY"];

	$updatedocdate = $rowdebrief["UPDATEDOCDATE"];
	
	// if (!empty($rowdebrief["UPDATEDOCBY"]))  //USED BY JIS BY STAFF
		// $updatejisstaffby = $rowdebrief["UPDATEDOCBY"];

	// $updatejisstaffdate = $rowdebrief["UPDATEDOCDATE"];
	
	if (!empty( $rowdebrief["ACCOUNTINGBY"]))
		$accountingby = $rowdebrief["ACCOUNTINGBY"];

	$accountingdate = $rowdebrief["ACCOUNTINGDATE"];
	if (!empty($rowdebrief["PRINTBY"]))
		$printby = $rowdebrief["PRINTBY"];

	$printdate = $rowdebrief["PRINTDATE"];
	
	if (!empty($rowdebrief["SURRENDERTO"]))
		$surrenderto = $rowdebrief["SURRENDERTO"];

	$surrenderdate = $rowdebrief["SURRENDERDATE"];
	if (!empty($rowdebrief["SCANNEDBY"]))
		$scannedby = $rowdebrief["SCANNEDBY"];

	$scanneddate = $rowdebrief["SCANNEDDATE"];
	if (!empty($rowdebrief["STOREDBY"]))
		$storedby = $rowdebrief["STOREDBY"];

	$storeddate = $rowdebrief["STOREDDATE"];
	if (!empty($rowdebrief["SCHOLARBY"]))
		$scholarby = $rowdebrief["SCHOLARBY"];

	$scholardate = $rowdebrief["SCHOLARDATE"];
	
	$reporteddate = $rowdebrief["REPORTEDDATE"];
}

$qryusvisa = mysql_query("SELECT ENDORSEDBY,ENDORSEDDATE,RECOMMENDEDBY,RECOMMENDEDDATE,APPROVEDBY,APPROVEDDATE,STATUS 
			FROM usvisaendorsement WHERE APPLICANTNO=$applicantno AND STATUS IS NULL") or die(mysql_error());

if (mysql_num_rows($qryusvisa) > 0)
{
	$rowusvisa = mysql_fetch_array($qryusvisa);
	
	if (!empty($rowusvisa["ENDORSEDBY"]))
		$endorsedby = $rowusvisa["ENDORSEDBY"];
	else 
		$endorsedby = "---";

	$endorseddate = $rowusvisa["ENDORSEDDATE"];
	$recommendedby = $rowusvisa["RECOMMENDEDBY"];
	$recommendeddate = $rowusvisa["RECOMMENDEDDATE"];
	$approvedby = $rowusvisa["APPROVEDBY"];
	$approveddate = $rowusvisa["APPROVEDDATE"];
	$usvisastatus = $rowusvisa["STATUS"];
}
	

echo "
<html>\n
<head>\n
<title></title>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>

<body onload=\"\" style=\"overflow:hidden;background-color:White;\">\n

<form name=\"debriefingstatus\" method=\"POST\">\n

<span class=\"sectiontitle\">DEBRIEFING STATUS</span>
<center>
<br />
<div style=\"width:80%;height:450px;background-color:White;\">
";
	$styleborder = "border-bottom:1px dashed Gray;";
	
	//INITIALIZE STATUS
	
	$arrivestatus = "PENDING";
	$formstatus = "PENDING";
	$surrenderstatus = "PENDING";
	$fleetstatus = "PENDING";
	$trainingstatus = "PENDING";
	$divisionstatus = "PENDING";
	$managementstatus = "PENDING";
	$checkdocstatus = "PENDING";   //USED BY JIS BY OFFICERS
	$checkjisstaffstatus = "PENDING";   //USED BY JIS BY staff
	$accountingstatus = "PENDING";
	$visastatus = "NO";
	$printstatus = "PRINT";
	
	$scanstatus = "PENDING";
	$storedstatus = "PENDING";
	if($scholar == 1 || $fasttrack == 1)
		$scholarstatus = "PENDING";
	else
		$scholarstatus = "N/A";

	//UPDATE STATUS
	
	$onclick1 = "";
	$onclick2 = "";
	$onclick3 = "";
	$onclick4 = "";
	$onclick5 = "";
	$onclick6 = "";
	$onclick7 = "";
	$onclick8 = "";
	$onclick9 = "";
	$onclick10 = "";
	$onclick11 = "";
	$onclick12 = "";
	$onclick15 = "";
	
	$arrtitle1 = "";
	$arrtitle2 = "";
	$arrtitle3 = "";
	$arrtitle4 = "";
	$arrtitle5 = "";
	$arrtitle6 = "";
	$arrtitle7 = "";
	$arrtitle8 = "";
	$arrtitle9 = "";
	$arrtitle10 = "";
	$arrtitle11 = "";
	$arrtitle12 = "";
	
	if (!empty($arrmnldate))
	{
		$arrivestatus = "DONE";
		$stylestatus1 = "color:Green;";
		$arrmnldate = date($dateformat,strtotime($arrmnldate));
		$confirmarrmnldate = date($dateformat,strtotime($confirmarrmnldate));
		$reporteddate = date($dateformat,strtotime($reporteddate));
		
		$onclick2 = "onclick=\"openWindow('debriefingformshow.php?applicantno=$applicantno&ccid=$ccid&load=1', 'debriefingform' ,0, 0);\"";
		
	}
	else 
	{
		$arrmnldate = "---";
		$reporteddate = "---";
		$stylestatus1 = "color:Red;";

		
//		if ($departmentid == 3)
//		{
			$onclick1 = "onclick=\"document.getElementById('editfile').src='editwindow.php?applicantno=$applicantno&ccid=$ccid&action=1';
					document.getElementById('editwindow').style.width=400;
					document.getElementById('editfile').style.width=400;
					document.getElementById('editfile').style.height=350;
					document.getElementById('editwindow').style.height=350;
					document.getElementById('editwindow').style.display='block';\"";
//		}
				
		$onclick2 = "";
		$arrtitle2 = "title=\"You must encode Arrive Manila Date first.\"";
	}
	
	if (!empty($fillupdate))
	{
		$formstatus = "DONE";
		$stylestatus2 = "color:Green;";
		$fillupdate = date($dateformat,strtotime($fillupdate));
		
//		if (empty($surrenderdate) && ($departmentid == 3))
//		if (empty($surrenderdate))
//		{
			$onclick3 = "onclick=\"document.getElementById('editfile').src='editwindow.php?applicantno=$applicantno&ccid=$ccid&action=2';
					document.getElementById('editwindow').style.width=700;
					document.getElementById('editfile').style.width=700;
					document.getElementById('editfile').style.height=420;
					document.getElementById('editwindow').style.height=420;
					document.getElementById('editwindow').style.display='block';\"";
//		}
		
//		if (empty($updatedocdate))
//		{
			$onclick4 = "onclick=\"document.getElementById('editfile').src='editwindow.php?applicantno=$applicantno&ccid=$ccid&action=3';
					document.getElementById('editwindow').style.width=600;
					document.getElementById('editfile').style.width=600;
					document.getElementById('editfile').style.height=400;
					document.getElementById('editwindow').style.height=400;
					document.getElementById('editwindow').style.display='block';\"";
//		}
		
//		if (empty($endorseddate) && ($departmentid == 3))
//		if (empty($endorseddate))
//		{
			$onclick5 = "onclick=\"document.getElementById('editfile').src='editwindow.php?applicantno=$applicantno&ccid=$ccid&action=4';
					document.getElementById('editwindow').style.width=400;
					document.getElementById('editfile').style.width=400;
					document.getElementById('editfile').style.height=250;
					document.getElementById('editwindow').style.height=250;
					document.getElementById('editwindow').style.display='block';\"";
//		}
		
//		if (empty($scanneddate) && ($departmentid == 5))
//		if (empty($scanneddate))
//		{
			$onclick6 = "onclick=\"document.getElementById('editfile').src='editwindow.php?applicantno=$applicantno&ccid=$ccid&action=5';
					document.getElementById('editwindow').style.width=450;
					document.getElementById('editfile').style.width=450;
					document.getElementById('editfile').style.height=420;
					document.getElementById('editwindow').style.height=420;
					document.getElementById('editwindow').style.display='block';\"";
//		}
		
//		if (empty($storeddate) && ($departmentid == 3))
//		if (empty($storeddate))
//		{
			$onclick12 = "onclick=\"document.getElementById('editfile').src='editwindow.php?applicantno=$applicantno&ccid=$ccid&action=6';
					document.getElementById('editwindow').style.width=450;
					document.getElementById('editfile').style.width=450;
					document.getElementById('editfile').style.height=420;
					document.getElementById('editwindow').style.height=420;
					document.getElementById('editwindow').style.display='block';\"";
//		}

			$onclick13 = "onclick=\"document.getElementById('editfile').src='editwindow.php?applicantno=$applicantno&ccid=$ccid&action=7';
					document.getElementById('editwindow').style.width=400;
					document.getElementById('editfile').style.width=400;
					document.getElementById('editfile').style.height=200;
					document.getElementById('editwindow').style.height=200;
					document.getElementById('editwindow').style.display='block';\"";

			$onclick14 = "onclick=\"document.getElementById('editfile').src='editwindow.php?applicantno=$applicantno&ccid=$ccid&action=8';
					document.getElementById('editwindow').style.width=700;
					document.getElementById('editfile').style.width=700;
					document.getElementById('editfile').style.height=400;
					document.getElementById('editwindow').style.height=400;
					document.getElementById('editwindow').style.display='block';\"";
	}
	else 
	{
		$fillupdate = "---";
		$stylestatus2 = "color:Red;";

	}
	
	if (!empty($surrenderdate))
	{
		$surrenderstatus = "DONE";
		$stylestatus3 = "color:Green;";
		$surrenderdate = date($dateformat,strtotime($surrenderdate));
		
//		if (empty($fleetdate) && ($departmentid == 2))
//		if (empty($fleetdate))
			$onclick7 = "onclick=\"openWindow('debriefingfleet.php?applicantno=$applicantno&ccid=$ccid', 'debriefingfleet' ,0, 0);\"";
		
//		if (empty($trainingdate) && ($departmentid == 7))
//		if (empty($trainingdate))
			$onclick8 = "onclick=\"openWindow('debriefingtraining.php?applicantno=$applicantno&ccid=$ccid', 'debriefingtraining' ,0, 0);\"";
			
//		if (empty($divisiondate) && ($departmentid == 2))
//		if (empty($divisiondate))
			$onclick9 = "onclick=\"openWindow('debriefingdivision.php?applicantno=$applicantno&ccid=$ccid', 'debriefingdivision' ,0, 0);\"";
			
//		if (empty($managementdate) && ($departmentid == 1))
//		if (empty($managementdate))
			$onclick10 = "onclick=\"openWindow('debriefingmanagement.php?applicantno=$applicantno&ccid=$ccid', 'debriefingmanagement' ,0, 0);\"";
			
//		if (empty($accountingdate) && ($departmentid == 6))
//		if (empty($accountingdate))
			$onclick11 = "onclick=\"openWindow('debriefingaccounting.php?applicantno=$applicantno&ccid=$ccid', 'debriefingaccounting' ,0, 0);\"";
			
			if ($scholar == 1 || $fasttrack == 1)
				$onclick15 = "onclick=\"openWindow('debriefingscholar.php?applicantno=$applicantno&ccid=$ccid', 'debriefingscholar' ,0, 0);\"";
			else
			{
				$onclick15 = "";
				// $scholarstatus = "N/A";
			}

	}
	else 
	{
		$surrenderdate = "---";
		$stylestatus3 = "color:Red;";
	}
	
	// if (!empty($updatedocdate))  //USED BY JIS BY OFFICERS
	// {
		// $checkdocstatus = "DONE";
		// $stylestatus4 = "color:Green;";
		// $updatedocdate = date($dateformat,strtotime($updatedocdate));
	// }
	// else 
	// {
		// $updatedocdate = "---";
		// $stylestatus4 = "color:Red;";
	// }
	
	if (!empty($endorseddate))
	{
//		$visastatus = "YES";
		$visastatus = "<input type=\"button\" value=\"Print\" onclick=\"openWindow('rependorseusvisa.php?applicantno=$applicantno&print=1', 'debriefingfleet' ,0, 0);\" />";
		$stylestatus5 = "color:Green;";
		$endorseddate = date($dateformat,strtotime($endorseddate));
	}
	else 
	{
		$endorseddate = "---";
		$stylestatus5 = "color:Red;";
	}
	
	if (!empty($scanneddate))
	{
		$scanstatus = "DONE";
		$stylestatus6 = "color:Green;";
		$scanneddate = date($dateformat,strtotime($scanneddate));
	}
	else 
	{
		$scanneddate = "---";
		$stylestatus6 = "color:Red;";
	}
		
	if (!empty($fleetdate))
	{
		$fleetstatus = "DONE";
		$stylestatus7 = "color:Green;";
		$fleetdate = date($dateformat,strtotime($fleetdate));
	}
	else 
	{
		$fleetdate = "---";
		$stylestatus7 = "color:Red;";
	}
	
	if (!empty($trainingdate))
	{
		$trainingstatus = "DONE";
		$stylestatus8 = "color:Green;";
		$trainingdate = date($dateformat,strtotime($trainingdate));
	}
	else 
	{
		$trainingdate = "---";
		$stylestatus8 = "color:Red;";
	}
	
	if (!empty($divisiondate))
	{
		$divisionstatus = "DONE";
		$stylestatus9 = "color:Green;";
		$divisiondate = date($dateformat,strtotime($divisiondate));
	}
	else 
	{
		$divisiondate = "---";
		$stylestatus9 = "color:Red;";
	}
	
	if (!empty($managementdate))
	{
		$managementstatus = "DONE";
		$stylestatus10 = "color:Green;";
		$managementdate = date($dateformat,strtotime($managementdate));
	}
	else 
	{
		$managementdate = "---";
		$stylestatus10 = "color:Red;";
	}
	
//	if (!empty($accountingdate) && ($departmentid == 6))
	if (!empty($accountingdate))
	{
		$accountingstatus = "DONE";
		$stylestatus11 = "color:Green;";
		$accountingdate = date($dateformat,strtotime($accountingdate));
		
	}
	else 
	{
		$accountingdate = "---";
		$stylestatus11 = "color:Red;";
	}
	
	if (!empty($storeddate))
	{
		$storedstatus = "DONE";
		$stylestatus12 = "color:Green;";
		$storeddate = date($dateformat,strtotime($storeddate));
	}
	else 
	{
		$storeddate = "---";
		$stylestatus12 = "color:Red;";
	}
	
	if (!empty($scholardate))
	{
		if ($scholar == 1)
		{
			$scholarstatus = "DONE";
			$stylestatus15 = "color:Green;";
			$scholardate = date($dateformat,strtotime($scholardate));
		}
		// else
		// {
			// $scholarstatus = "DONE";
			// $stylestatus15 = "color:Green;";
			// $scholardate = date($dateformat,strtotime($scholardate));
		// }
	}
	else 
	{
		$scholardate = "---";
		$stylestatus15 = "color:Red;";
	}
	
	if (!empty($printdate))
	{
		$printstatus = "DONE";
		$stylestatus13 = "color:Green;";
		$printdate = date($dateformat,strtotime($printdate));
	}
	else 
	{
		$printdate = "---";
		$stylestatus13 = "color:Red;";
	}
	
	
	//CHECKING OF JIS REMARKS - OFFICERS
	
	$qryjischeck = mysql_query("SELECT IDNO,MADEBY_OFCR,MADEDATE_OFCR FROM debriefjislicense WHERE CCID=$ccid") or die(mysql_error());
	
	if (mysql_num_rows($qryjischeck) > 0)
	{
		$jis_ofcr_show = "DONE";
		$stylestatus4 = "color:Green;";
		$rowjischeck = mysql_fetch_array($qryjischeck);
		$madebyofcr = $rowjischeck["MADEBY_OFCR"];
		
		if (!empty($rowjischeck["MADEDATE_OFCR"]))
			$madedateofcr = date("dMY",strtotime($rowjischeck["MADEDATE_OFCR"]));
		else
			$madedateofcr = "";
	}
	else
	{
		$jis_ofcr_show = "PENDING";
		$stylestatus4 = "color:Red;";
	}
	
	//CHECKING OF JIS REMARKS - STAFF
	
	$qryjischeck2 = mysql_query("SELECT IDNO,MADEBY_STAFF,MADEDATE_STAFF FROM debriefjislicense WHERE CCID=$ccid AND REMARKS_STAFF IS NOT NULL") or die(mysql_error());
	
	if (mysql_num_rows($qryjischeck2) > 0)
	{
		$jis_staff_show = "DONE";
		$stylestatus14 = "color:Green;";
		$rowjischeck2 = mysql_fetch_array($qryjischeck2);
		$madebystaff = $rowjischeck2["MADEBY_STAFF"];
		
		if (!empty($rowjischeck2["MADEDATE_STAFF"]))
			$madedatestaff = date("dMY",strtotime($rowjischeck2["MADEDATE_STAFF"]));
		else
			$madedatestaff = "";
	}
	else
	{
		$jis_staff_show = "PENDING";
		$stylestatus14 = "color:Red;";
	}
	
	
	
	$styleby = "style=\"font-size:0.8em;font-weight:Bold;color:Blue;\"";
	
echo "
	<table style=\"width:100%;font-size:1em;\" border=1 cellspacing=\"0\">
		<tr>
			<th width=\"5%\">&nbsp;</th>
			<th width=\"45%\"><u>ACTIVITY</u></th>
			<th width=\"20%\"><u>STATUS</u></th>
			<th width=\"20%\"><u>DATE</u></th>
			<th width=\"10%\"><u>BY</u></th>
		</tr>
		<tr>
			<td colspan=\"5\">&nbsp;</td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>Updating of Arrive Manila Date</td>
			<td align=\"center\"><a href=\"#\" style=\"font-weight:Bold;$stylestatus1\" $onclick1 >$arrivestatus</a></td>
			<td align=\"center\">$reporteddate</td>
			<td align=\"center\" $styleby>$madeby</td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>Filled-up Arriving Seaman Debriefing Form</td>
			<td align=\"center\">
				<a href=\"#\" $arrtitle2 style=\"font-weight:Bold;$stylestatus2\" $onclick2 >$formstatus</a></td>
			<td align=\"center\">$fillupdate</td>
			<td align=\"center\" $styleby>CREW</td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>Surrendering of Documents/Licenses</td>
			<td align=\"center\"><a href=\"#\" $arrtitle3 style=\"font-weight:Bold;$stylestatus3\" $onclick3 >$surrenderstatus</a></td>
			<td align=\"center\">$surrenderdate</td>
			<td align=\"center\" $styleby>$surrenderto</td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>Storing of Documents/Licenses</td>
			<td align=\"center\"><a href=\"#\" $arrtitle12 style=\"font-weight:Bold;$stylestatus12\" $onclick12 >$storedstatus</a></td>
			<td align=\"center\">$storeddate</td>
			<td align=\"center\" $styleby>$storedby</td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>Scholar/FastTrack Comments</td>
			<td align=\"center\"><a href=\"#\" style=\"font-weight:Bold;$stylestatus15\" $onclick15 >$scholarstatus</a></td>
			<td align=\"center\">$scholardate</td>
			<td align=\"center\" $styleby>$scholarby</td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>Checking of JIS License by Fleet Officers</td>
			<td align=\"center\"><a href=\"#\" style=\"font-weight:Bold;$stylestatus4\" $onclick4 >$jis_ofcr_show</a></td>
			<td align=\"center\">&nbsp;$madedateofcr</td>
			<td align=\"center\" $styleby>&nbsp;$madebyofcr</td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; JIS License Process by Staff</td>
			<td align=\"center\"><a href=\"#\" style=\"font-weight:Bold;$stylestatus14\" $onclick14 >$jis_staff_show</a></td>
			<td align=\"center\">&nbsp;$madedatestaff</td>
			<td align=\"center\" $styleby>&nbsp;$madebystaff</td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>U.S. VISA Endorsement</td>
			<td align=\"center\"><a href=\"#\" $arrtitle5 style=\"font-weight:Bold;$stylestatus5\" $onclick5 >$visastatus</a></td>
			<td align=\"center\">$endorseddate</td>
			<td align=\"center\" $styleby>$endorsedby</td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>Scanning of Documents/Licenses</td>
			<td align=\"center\"><a href=\"#\" $arrtitle6 style=\"font-weight:Bold;$stylestatus6\" $onclick6 >$scanstatus</a></td>
			<td align=\"center\">$scanneddate</td>
			<td align=\"center\" $styleby>$scannedby</td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>Fleet Debriefing and Training Endorsement</td>
			<td align=\"center\"><a href=\"#\" $arrtitle7 style=\"font-weight:Bold;$stylestatus7\" $onclick7 >$fleetstatus</a></td>
			<td align=\"center\">$fleetdate</td>
			<td align=\"center\" $styleby>$fleetby</td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>Division Debriefing</td>
			<td align=\"center\"><a href=\"#\" $arrtitle9 style=\"font-weight:Bold;$stylestatus9\" $onclick9 >$divisionstatus</a></td>
			<td align=\"center\">$divisiondate</td>
			<td align=\"center\" $styleby>$divisionby</td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>Top Management Debriefing</td>
			<td align=\"center\"><a href=\"#\" $arrtitle10 style=\"font-weight:Bold;$stylestatus10\" $onclick10 >$managementstatus</a></td>
			<td align=\"center\">$managementdate</td>
			<td align=\"center\" $styleby>$managementby</td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>Rehire-Training/For Managment Assessment-Fleet</td>
			<td align=\"center\"><a href=\"#\" $arrtitle8 style=\"font-weight:Bold;$stylestatus8\" $onclick8 >$trainingstatus</a></td>
			<td align=\"center\">$trainingdate</td>
			<td align=\"center\" $styleby>$trainingby</td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>Accounting Debriefing/Clearance</td>
			<td align=\"center\"><a href=\"#\" $arrtitle11 style=\"font-weight:Bold;$stylestatus11\" $onclick11 >$accountingstatus</a></td>
			<td align=\"center\">$accountingdate</td>
			<td align=\"center\" $styleby>$accountingby</td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>Final Printing of necessary Forms</td>
			<td align=\"center\"><a href=\"#\" $arrtitle13 style=\"font-weight:Bold;$stylestatus13\" $onclick13 >$printstatus</a></td>
			<td align=\"center\">$printdate</td>
			<td align=\"center\" $styleby>$printby</td>
		</tr>
	</table>
	<br />
	
	<center>
		<input type=\"button\" value=\"Refresh\" style=\"border:1px solid Orange;background-color:Blue;color:Yellow;font-weight:Bold;font-size:1em;\"
			onclick=\"applicantno.value='$applicantno';submit();\" / >
	</center>
";
	
echo "
</div>

</center>

<div id=\"editwindow\" 
	style=\"background-color:Black;border:2px solid Red;z-index:200;position:absolute;left:250;top:25;
		border:3px solid black;display:none;\">
	
	<iframe marginwidth=0 marginheight=0 id=\"editfile\" frameborder=\"0\" name=\"content\" src=\"\" scrolling=\"auto\" 
		style=\"width:100%;height:100%;\">
	</iframe>
	
	<input type=\"button\" value=\"Close\" onclick=\"document.getElementById('editwindow').style.display='none';applicantno.value='$applicantno';submit();\" />
		
</div>

	<input type=\"hidden\" name=\"applicantno\" />

</form>

</body>
</html>

";
	
	
	
?>