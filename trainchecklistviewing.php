<?php
include('veritas/connectdb.php');
include("veritas/include/stylephp.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');
$basedirid = "idpics/";
$basedirdocs = "docimg/";
// $basedirdocs = "docimages/";

$datenow = date("Y-m-d");

if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
else 
	$employeeid = "";

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];

if (isset($_POST['applicantno']))
	$applicantno = $_POST['applicantno'];
//else 
//	$applicantno = $_GET['applicantno'];
	
if (isset($_POST['currentrankcode']))
	$currentrankcode = $_POST['currentrankcode'];
	
if (isset($_POST["searchkey"]))
	$searchkey = $_POST["searchkey"];
	
if (isset($_POST["searchby"]))
	$searchby = $_POST["searchby"];
//else 
//	$searchby = 2;
	
if ($searchkey == "")
	$disablesearch = "disabled=\"disabled\"";
else 
	$disablesearch = "";

	
//POSTS FOR "UPDATE TRAINING"

if (isset($_POST['traincodehidden']))
	$traincodehidden = $_POST['traincodehidden'];

if (isset($_POST['rankcodehidden']))
	$rankcodehidden = $_POST['rankcodehidden'];

if (isset($_POST['traindatehidden']))
	$traindatehidden = $_POST['traindatehidden'];

if (isset($_POST['gradehidden']))
	$gradehidden = $_POST['gradehidden'];


//POSTS FOR "ENDORSE"

if (isset($_POST['endorsetraincode']))
	$endorsetraincode = $_POST['endorsetraincode'];
	
	
//POSTS	FOR "CANCEL TRAINING"
	
//if (isset($_POST['canceltraincode']))
//	$canceltraincode = $_POST['canceltraincode'];

//if (isset($_POST['cancelschedid']))
//	$cancelschedid = $_POST['cancelschedid'];

	
	
$disablechecklist = 0;
$showmultiple = "display:none;";
$multiple = 0;

	
function checkpath($path)
{
	if (is_file($path))
		return true;
	else 
		return false;
}

function imageScale($image, $newWidth, $newHeight)
{
    if(!$size = @getimagesize($image))
        die("Unable to get info on image $image");
    $ratio = ($size[0] / $size[1]);
    //scale by height
    if($newWidth == -1)
    {
        $ret[1] = $newHeight;
        $ret[0] = round(($newHeight * $ratio));
    }
    else if($newHeight == -1)
    {
        $ret[0] = $newWidth;
        $ret[1] = round(($newWidth / $ratio));
    }
    else
        die("Scale Error");
    return $ret;
} 



switch ($actiontxt)
{
	case "find"	:
		
		$whereappno = "";
		$whereappno2 = "";
		$errormsg = "";
		
		switch ($searchby)
		{
			case "1"	: //APPLICANT NO
				
					$qrysearch = mysql_query("SELECT APPLICANTNO,CREWCODE FROM crew WHERE APPLICANTNO LIKE '$searchkey%' ORDER BY APPLICANTNO") or die(mysql_error());
				
				break;
			case "2"	: //CREW CODE
				
					$qrysearch = mysql_query("SELECT APPLICANTNO,CREWCODE FROM crew WHERE CREWCODE LIKE '$searchkey%' ORDER BY CREWCODE") or die(mysql_error());
				
				break;
			case "3"	: //FAMILY NAME
				
					$qrysearch = mysql_query("SELECT APPLICANTNO,CREWCODE FROM crew WHERE FNAME LIKE '$searchkey%' ORDER BY FNAME") or die(mysql_error());
				
				break;
			case "4"	: //GIVEN NAME
				
					$qrysearch = mysql_query("SELECT APPLICANTNO,CREWCODE FROM crew WHERE GNAME LIKE '$searchkey%' ORDER BY GNAME") or die(mysql_error());
				
				break;
		}
	
		if (mysql_num_rows($qrysearch) == 1)
		{
			$rowsearch = mysql_fetch_array($qrysearch);
			$applicantno = $rowsearch["APPLICANTNO"];
		}
		elseif (mysql_num_rows($qrysearch) > 1)
		{
			$showmultiple = "display:block;";
			$multiple = 1;
		}
		
	break;
	
	case "updatetraining":
		
			if ($traindatehidden != "")
				$traindatehidden = "'" . $traindatehidden . "'";
			else 
				$traindatehidden = "NULL";
				
			if ($gradehidden == "")
				$gradehidden = 0; 
					

					
			// crewtrainingnosched -- BOTH INHOUSE AND OUTSIDE, NO SCHED ID!!!
			
			$qrycrewtrainingnoschedinsert = mysql_query("INSERT INTO crewtrainingnosched(APPLICANTNO,TRAINCODE,TRAINDATE,RANKCODE,
													GRADE,MADEBY,MADEDATE)
													VALUES($applicantno,'$traincodehidden',$traindatehidden,'$rankcodehidden',
															$gradehidden,'$employeeid','$currentdate')
											") or die(mysql_error());

			
		break;
		
	case "endorse":
			
			$qryendorse = mysql_query("INSERT INTO trainingendorsement(APPLICANTNO,TRAINCODE,MADEBY,MADEDATE) 
										VALUES($applicantno,'$endorsetraincode','$employeeid','$currentdate')
								") or die(mysql_error());
		
		break;
		
	case "unendorse":
		
			$qryunendorse = mysql_query("DELETE FROM trainingendorsement 
										WHERE APPLICANTNO=$applicantno AND TRAINCODE='$endorsetraincode'
										AND STATUS=0 AND SCHEDID IS NULL") or die(mysql_error());
		
		break;
	
}


$assignedrankcode = "---";
$assignedrankalias = "---";
$assignedvesselcode = "---";
$assignedvessel = "---";
$assignedetd = "---";

$lastrankcode = "---";
$lastrankalias = "---";
$lastvesselcode = "---";
$lastvessel = "---";
$lastdisembdate = "---";

if (!empty($applicantno))
{
	
	$qryallexperience = mysql_query("SELECT IF (cpr.CCIDPROMOTE IS NULL,0,1) AS PROMOTED,x.* FROM
									(
										SELECT '1' AS POS,cc.CCID,CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,CREWCODE,
										LEFT(v.ALIAS2,10) AS VESSEL,v.VESSELCODE,r.ALIAS1 AS RANKALIAS,r.RANKCODE,
										cc.DATEEMB,IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,cc.DISEMBREASONCODE,dr.REASON,
										cc.ARRMNLDATE,cc.DEPMNLDATE
										FROM crewchange cc
										LEFT JOIN crew c ON c.APPLICANTNO=cc.APPLICANTNO
										LEFT JOIN vessel v ON v.VESSELCODE=cc.VESSELCODE
										LEFT JOIN rank r ON r.RANKCODE=cc.RANKCODE
										LEFT JOIN disembarkreason dr ON dr.DISEMBREASONCODE=cc.DISEMBREASONCODE
										WHERE cc.APPLICANTNO=$applicantno
										
										UNION
										
										SELECT '2' AS POS,ce.IDNO,CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,CREWCODE,
										LEFT(ce.VESSEL,10) AS VESSEL,NULL,r.ALIAS1 AS RANKALIAS,r.RANKCODE,
										DATEEMB,DATEDISEMB,ce.DISEMBREASONCODE,dr.REASON,NULL,NULL
										FROM crewexperience ce
										LEFT JOIN crew c ON c.APPLICANTNO=ce.APPLICANTNO
										LEFT JOIN rank r ON r.RANKCODE=ce.RANKCODE
										LEFT JOIN disembarkreason dr ON dr.DISEMBREASONCODE=ce.DISEMBREASONCODE
										WHERE ce.APPLICANTNO=$applicantno
									) x
									LEFT JOIN crewpromotionrelation cpr ON cpr.CCIDPROMOTE=x.CCID
									ORDER BY x.DATEDISEMB DESC
									LIMIT 2
								") or die(mysql_error());

	$cnt = 0;
	$content = "";
	$appstatus = "";
	
	while ($rowallexperience = mysql_fetch_array($qryallexperience))
	{
		if ($rowallexperience["POS"] == "1")
			$zexptype = "Veritas";
		else 
			$zexptype = "Outside VMC";
			
		$crewname = $rowallexperience["NAME"];
		$crewcode = $rowallexperience["CREWCODE"];
			
		$zccid = $rowallexperience["CCID"];
		$zcrewcode = $rowallexperience["CREWCODE"];
		$zvessel = $rowallexperience["VESSEL"];
		$zvesselcode = $rowallexperience["VESSELCODE"];
		$zrankalias = $rowallexperience["RANKALIAS"];
		$zrankcode = $rowallexperience["RANKCODE"];
		$zpromoted = $rowallexperience["PROMOTED"];
		
		if (!empty($rowallexperience["DATEEMB"]))
			$zdateemb = date("dMY",strtotime($rowallexperience["DATEEMB"]));
		else 
			$zdateemb = "";
			
		if (!empty($rowallexperience["DATEDISEMB"]))
			$zdatedisemb = date("dMY",strtotime($rowallexperience["DATEDISEMB"]));
		else 
			$zdatedisemb = "";
			
		if (!empty($rowallexperience["ARRMNLDATE"]))
			$zarrmnldate = date("dMY",strtotime($rowallexperience["ARRMNLDATE"]));
		else 
			$zarrmnldate = "";
			
		if (!empty($rowallexperience["DEPMNLDATE"]))
			$zdepmnldate = date("dMY",strtotime($rowallexperience["DEPMNLDATE"]));
		else 
			$zdepmnldate = "";
			
		$zdisembreasoncode = $rowallexperience["DISEMBREASONCODE"];
		$zreason = $rowallexperience["REASON"];
		
		
		if ($cnt == 0)
		{
			if ($zexptype == "Veritas")
			{
				if (strtotime($zdatedisemb) <= strtotime($datenow))
				{
					if (!empty($zarrmnldate))
						$appstatus = "STANDBY";
					else 
						$appstatus = "STANDBY (No Arrive Manila)";
						
					$lastrankcode = $zrankcode;
					$lastrankalias = $zrankalias;
					$lastvesselcode = $zvesselcode;
					$lastvessel = $zvessel;
					$lastdisembdate = $zdatedisemb;
				}
				else 
				{
					if (strtotime($zdateemb) <= strtotime($datenow) && !empty($zdepmnldate))
					{
						if ($zpromoted != 1)
						{
							$appstatus = "ONBOARD";
							
							$lastrankcode = $zrankcode;
							$lastrankalias = $zrankalias;
							$lastvesselcode = $zvesselcode;
							$lastvessel = $zvessel;
							$lastdisembdate = $zdatedisemb;
						}
						else 
						{
							$appstatus = "PROMOTED ONBOARD";
							
							$assignedrankcode = $zrankcode;
							$assignedrankalias = $zrankalias;
							$assignedvesselcode = $zvesselcode;
							$assignedvessel = $zvessel;
							$assignedetd = $zdateemb;
						}
	

						
						$disablechecklist = 1;
					}
					else 
					{
						if ($zpromoted != 1)
						{
							$appstatus = "EMBARKING";
						}
						else 
						{
							$appstatus = "PROMOTED ONBOARD";
						}
						
						$assignedrankcode = $zrankcode;
						$assignedrankalias = $zrankalias;
						$assignedvesselcode = $zvesselcode;
						$assignedvessel = $zvessel;
						$assignedetd = $zdateemb;
					}
				}
			}
			else //OUTSIDE (NO VMC EXPERIENCE)
			{
				$qryapplicantstatus = mysql_query("SELECT ap.VMCRANKCODE,ap.VMCVESSELCODE,ap.VMCETD,r.ALIAS1 AS RANKALIAS,v.VESSEL
													FROM applicantstatus ap
													LEFT JOIN rank r ON r.RANKCODE=ap.VMCRANKCODE
													LEFT JOIN vessel v ON v.VESSELCODE=ap.VMCVESSELCODE
													WHERE ap.APPLICANTNO=$applicantno
												") or die(mysql_error());
				
				if (mysql_num_rows($qryapplicantstatus) == 1)
				{
					$rowapplicantstatus = mysql_fetch_array($qryapplicantstatus);
					
					$appstatus = "NEW HIRE";
					
					$assignedrankcode = $rowapplicantstatus["VMCRANKCODE"];
					$assignedrankalias = $rowapplicantstatus["RANKALIAS"];
					$assignedvesselcode = $rowapplicantstatus["VMCVESSELCODE"];
					$assignedvessel = $rowapplicantstatus["VESSEL"];
					
					if (!empty($rowapplicantstatus["VMCETD"]))
						$assignedetd = date("dMY",strtotime($rowapplicantstatus["VMCETD"]));
					else 
						$assignedetd = "---";
				}
				else 
				{
					$appstatus = "NO VMC EXPERIENCE / NO LINE-UP YET";
				}
				
			}
			
			
			//SAVE TO $currentrankcode for checklist use...
			
			if (!empty($assignedrankcode) && $assignedrankcode != "---")
			{
				$currentrankcode = $assignedrankcode;
				$currentvesselcode = $assignedvesselcode;
				$currentetd = $assignedetd;
			}
			elseif (!empty($lastrankcode) && $lastrankcode != "---")
			{
				$currentrankcode = $lastrankcode;
				$currentvesselcode = "";
				$currentetd = "";
			}
			else 
			{
				$currentrankcode = "";
				$currentvesselcode = "";
				$currentetd = "";
			}
			
		}
		elseif ($cnt == 1)
		{
			if ($appstatus == "EMBARKING" || $appstatus == "PROMOTED ONBOARD")
			{
				$lastrankcode = $zrankcode;
				$lastrankalias = $zrankalias;
				$lastvesselcode = $zvesselcode;
				$lastvessel = $zvessel;
				$lastdisembdate = $zdatedisemb;
			}
		}
		
		$content .= "
		<tr>
			<td>$zexptype</td>
			<td>$zvessel</td>
			<td>$zrankalias</td>
			<td>&nbsp;$zdateemb</td>
			<td>&nbsp;$zdepmnldate</td>
			<td>&nbsp;$zdatedisemb</td>
			<td>&nbsp;$zarrmnldate</td>
			<td>&nbsp;$zreason</td>
		</tr>
		";
		
		$cnt++;
	}

}


echo "
<html>\n
<head>\n
<title>Crew Training Checklist</title>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>

<body onload=\"\" style=\"background-color:White;overflow:hidden;\">\n

<form name=\"formtrainendorse\" method=\"POST\">\n

					<div id=\"multiple\" style=\"position:absolute;top:200px;left:200px;height:184px;width:600px;height:400px;background-color:#6699FF;
									border:2px solid black;overflow:auto;$showmultiple \">
						<span class=\"sectiontitle\">MULTIPLE ENTRY FOUND</span>
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
									
									$qrygetinfo = mysql_query("SELECT APPLICANTNO,CREWCODE,FNAME,GNAME,MNAME,STATUS
																FROM crew 
																WHERE APPLICANTNO=$appno AND STATUS=1
															") or die(mysql_error());
									
									$rowgetinfo = mysql_fetch_array($qrygetinfo);
					
									$info1 = $rowgetinfo["APPLICANTNO"];
									$info2 = $rowgetinfo["CREWCODE"];
									$info3 = $rowgetinfo["FNAME"];
									$info4 = $rowgetinfo["GNAME"];
									$info5 = $rowgetinfo["MNAME"];
									if ($rowgetinfo["STATUS"] == 1)
										$info6 = "ACTIVE";
									else 
										$info6 = "INACTIVE";
									
									echo "
									<tr $mouseovereffect style=\"cursor:pointer;\" ondblclick=\"
													applicantno.value='$info1';submit();
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

	<div style=\"width:100%;height:650px;\">
	
		<center>
		<table style=\"width:70%;margin-top:5px;\" border=1>
			<tr>
				<td align=\"left\" style=\"font-size:0.8em;font-weight:Bold;\">Search</td>
				<td>
					<select name=\"searchby\" onchange=\"if(this.value != ''){searchkey.disabled=false;searchkey.value='';searchkey.focus();}
														else {searchkey.disabled=true;searchkey.value='';}\">
						<option value=\"\">--Select Search Key--</option>
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
						<option $selected1 value=\"1\">APPLICANT NO</option>
						<option $selected2 value=\"2\">CREW CODE</option>
						<option $selected3 value=\"3\">FAMILY NAME</option>
						<option $selected4 value=\"4\">GIVEN NAME</option>
					</select>
				</td>
				<td align=\"center\"><input type=\"text\" name=\"searchkey\" size=\"20\" value=\"$searchkey\" $disablesearch
						onkeyup=\"this.value=this.value.toUpperCase();if (this.value != '') {btnfind.disabled=false;}\"
						style=\"color:Red;font-weight:Bold;\" onkeydown=\"if(event.keyCode==13) {btnfind.focus();}\"/>
						
					<input type=\"button\" name=\"btnfind\" value=\"Find\" $disablesearch onclick=\"currentrankcode.value='';actiontxt.value='find';submit();\" />
				</td>
			</tr>
		</table>
		</center>
		<br />
		
		<div style=\"width:100%;height:170px;margin:3 5 3 5;background-color:#408080;\">
			
			<div style=\"width:79%;height:100px;float:left;border:1px solid Black;\">
			";
				$stylehdr = "style=\"font-size:0.7em;font-weight:Bold;color:White;\"";
				$styledtl = "style=\"font-size:0.7em;font-weight:Bold;color:Yellow;\"";
				$styledtl2 = "style=\"font-size:1.2em;font-weight:Bold;color:Yellow;\"";
				
			echo "
				<div style=\"width:100%;height:70px;\">
					<table style=\"width:100%;background-color:Black;\">
						<tr>
							<td $stylehdr>NAME:&nbsp;&nbsp;<span $styledtl2>$crewname</span></td>
							<td $stylehdr>APPLICANT NO:&nbsp;&nbsp;<span $styledtl2>$applicantno</span></td>
							<td $stylehdr>CREW CODE:&nbsp;&nbsp;<span $styledtl2>$crewcode</span></td>
						</tr>
					</table>
					<br />
					
					<table style=\"width:48%;float:left;\">
						<tr>
							<td $stylehdr>LAST VESSEL</td>
							<td $stylehdr>:</td>
							<td $styledtl>$lastvessel</td>
						</tr>
						<tr>
							<td $stylehdr>LAST RANK</td>
							<td $stylehdr>:</td>
							<td $styledtl>$lastrankalias</td>
						</tr>
						<tr>
							<td $stylehdr>DISEMBARK</td>
							<td $stylehdr>:</td>
							<td $styledtl>$lastdisembdate</td>
						</tr>
					</table>
					
					<table style=\"width:48%;float:right;\">
						<tr>
							<td $stylehdr>ASSIGNED VESSEL</td>
							<td $stylehdr>:</td>
							<td $styledtl>$assignedvessel</td>
						</tr>
						<tr>
							<td $stylehdr>ASSIGNED RANK</td>
							<td $stylehdr>:</td>
							<td $styledtl>$assignedrankalias</td>
						</tr>
						<tr>
							<td $stylehdr>ETD</td>
							<td $stylehdr>:</td>
							<td $styledtl>$assignedetd</td>
						</tr>
					</table>
				</div>
				<hr />
				
				<div style=\"width:100%;overflow:auto;\">
					<table class=\"listcol\" >
						<tr>
							<th>TYPE</th>
							<th>VESSEL</th>
							<th>RANK</th>
							<th>EMBARK</th>
							<th>DEPART MNL</th>
							<th>DISEMBARK</th>
							<th>ARRIVE MNL</th>
							<th>REASON</th>
						</tr>
						
						 $content
	 
					</table>
				</div>

			</div>
			
			<div style=\"width:18%;float:right;color:Orange;\">
	";
				$dirfilename = $basedirid . $applicantno . ".JPG";
				if (checkpath($dirfilename))
				{
					$scale = imageScale($dirfilename,-1,130);
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
			
		</div>
		
		<div style=\"width:100%;height:460px;background-color:#DCDCDC;margin:3 5 3 5;border:1px solid Black;\">
			<span class=\"sectiontitle\">TRAINING CHECKLIST</span>
";


include("veritas/include/checklist.inc");
							
			if (mysql_num_rows($qrychecklist) > 0)
			{
				
				echo "
				<div style=\"width:100%;height:430px;overflow:auto;\">
					
					<table width=\"100%\" style=\"font-size:0.8em;background-color:#FDE3B0;border:1px solid black;\">
						<tr style=\"font-weight:bold;background-color:Black;color:White;\">
							<td width=\"45%\" align=\"center\">COURSE</td>
							<td width=\"15%\" align=\"center\">TRAINING DATE</td>
							<td width=\"30%\" align=\"center\">STATUS</td>
							<td width=\"10%\" align=\"center\">CERTIFICATE</td>
						</tr>
						";
				
//						if ($applicantno != "")
//						{
							$tmpgroup = "";

							while ($rowchecklist = mysql_fetch_array($qrychecklist))
							{
								$chkidno = $rowchecklist["IDNO"];
								$chktraincode = $rowchecklist["TRAINCODE"];
								$chktraining = $rowchecklist["TRAINING"];
								$chkalias = $rowchecklist["ALIAS"];
								$chkrankcode = $rowchecklist["RANKCODE"];
								$chkrequired = $rowchecklist["REQUIRED"];
								
								if ($chkrequired == 0)
									$chkrequiredshow = "N";
								else 
									$chkrequiredshow = "Y";
									
								$chkcoursetypecode = $rowchecklist["COURSETYPECODE"];
								$chkcoursetype = $rowchecklist["COURSETYPE"];
								$chkdoccode = $rowchecklist["DOCCODE"];
								$chkstatus = $rowchecklist["STATUS"];
								
								if ($chkcoursetypecode != $tmpgroup)
								{
									$style = "style=\"background-color:Navy;color:Orange;font-weight:Bold;font-size:1.2em;text-align:center;\"";
									
									echo "<tr><td colspan=\"4\" $style>$chkcoursetype</td></tr>";
									
								}
								
								$status = "";
								$statusshow = "";
								$datefrom = "";
								$dateto = "";
								$certshow = "";
								$docshow = "";
								
								
//								$enrolled = 0;
								$endorsed = 0;
								
								$qrycheckendorsement = mysql_query("SELECT SCHEDID,STATUS FROM trainingendorsement
																	WHERE APPLICANTNO=$applicantno AND TRAINCODE='$chktraincode'
															") or die(mysql_error());
								
								if (mysql_num_rows($qrycheckendorsement) > 0) //ENDORSED ALREADY
								{
									$endorsed = 1;
//									$rowcheckendorsement = mysql_fetch_array($qrycheckendorsement);
//									
//									if (!empty($rowcheckendorsement["SCHEDID"])) //ENROLLED ALREADY
//										$enrolled = 1;
								}

								$qrychecklistinhouse = mysql_query("
												SELECT *,															
												IF (DATEFROM > CURRENT_DATE,'ENROLLED',
												    IF (DATETO < CURRENT_DATE,
												        'FINISHED',
												        'ON-GOING'
												        )
												) AS STATUS2
												FROM (
													SELECT 'IN' AS TYPE,ct.SCHEDID,th.DATEFROM,th.DATETO,th.STATUS AS TRAINSTATUS,ct.STATUS
													FROM crewtraining ct
													LEFT JOIN trainingschedhdr th ON th.SCHEDID=ct.SCHEDID
													WHERE ct.APPLICANTNO=$applicantno AND th.TRAINCODE='$chktraincode'
												
													UNION
												
													SELECT 'OLD' AS TYPE,NULL,TRAINDATE,TRAINDATE,NULL,1
													FROM crewtrainingold ctold
													WHERE ctold.APPLICANTNO=$applicantno AND ctold.TRAINCODE='$chktraincode'
													
													UNION
													
													SELECT 'UPD' AS TYPE,NULL,TRAINDATE,TRAINDATE,NULL,1
													FROM crewtrainingnosched ctnosched
													WHERE ctnosched.APPLICANTNO=$applicantno AND ctnosched.TRAINCODE='$chktraincode'
												) x
												ORDER BY DATEFROM DESC
											") or die(mysql_error());
								
								$historycnt = mysql_num_rows($qrychecklistinhouse);
								
								if (mysql_num_rows($qrychecklistinhouse) > 0)
								{
									$rowchecklistinhouse = mysql_fetch_array($qrychecklistinhouse);
									
									$schedid = $rowchecklistinhouse["SCHEDID"];
									$datefrom = date("dMY",strtotime($rowchecklistinhouse["DATEFROM"]));
									$dateto = date("dMY",strtotime($rowchecklistinhouse["DATETO"]));
									$trainstatus = $rowchecklistinhouse["TRAINSTATUS"]; //IF TRAINING IS CANCELLED
									$status = $rowchecklistinhouse["STATUS"]; //COMPLETED;INCOMPLETE
									$status2 = $rowchecklistinhouse["STATUS2"]; //ENROLLED;FINISHED;ON-GOING
									$type = $rowchecklistinhouse["TYPE"];
									
									if ($status == 1)
										$completed = "COMPLETED";
									elseif ($status == 2)
										$completed = "INCOMPLETE";
									else 
										$completed = "NOT POSTED YET";
									
									$stylestatus = "";
									$disabledendorse = 0;	
									
									switch ($status2)
									{
										case "ENROLLED"	:
											
												$statusshow = "<span style=\"color:Yellow;background-color:Black;font-weight:Bold;\">" . "&nbsp;ENROLLED " . "</span><i>&nbsp;&nbsp;&nbsp;" . $datefrom . " to " . $dateto . "</i>";
												$disabledendorse = 1;
												
											break;
										case "FINISHED"	:
											
//												$statusshow = "<i>Last&nbsp;"  .$dateto . "</i>&nbsp;<span style=\"color:White;background-color:Red;font-weight:Bold;\"></span>";
												$statusshow = "";
												
											
											break;
										case "ON-GOING"	:
											
												$statusshow = "<span style=\"color:Lime;background-color:Black;font-weight:Bold;\">" . "&nbsp;ON-GOING " . "</span><i>&nbsp;&nbsp; Until&nbsp;" . $dateto . "</i>";
												$disabledendorse = 2;
												
											break;
									}
								}

								
								//CHECK IF DOCUMENT IS READY FOR VIEWING...
								
								$dirfilename1 = $basedirdocs . $applicantno . "/C/" . $chkdoccode . ".pdf";
								
								if (checkpath($dirfilename1))
								{
									$docshow = "<a href=\"#\" style=\"font-weight:Bold;color:Navy;\"
												onclick=\"openWindow('$dirfilename1', '$chkdoccode' ,700, 500);\" title=\"Click to View.\">
												[VIEW]</a>";
								}
								else 
									$docshow = "------";

								$styledtl = " style=\"font-size:1em;color:Black;border-bottom:1px dashed gray;\"";	
								
								//CHECK IF THERE'S AN ENTRY IN CREWCERTSTATUS

								$qrycheckcertstatus = mysql_query("SELECT if(x.APPLICANTNO IS NULL,'UPDATE','EXISTS') AS STATUS,cc.*
													FROM crewcertstatus cc
													LEFT JOIN (
														SELECT 'IN' AS TYPE,ct.APPLICANTNO,ct.SCHEDID,th.DATEFROM,th.DATETO,th.STATUS AS TRAINSTATUS,ct.STATUS
														FROM crewtraining ct
														LEFT JOIN trainingschedhdr th ON th.SCHEDID=ct.SCHEDID
														WHERE ct.APPLICANTNO=$applicantno AND th.TRAINCODE='$chktraincode'
														
														UNION
														
														SELECT 'OLD' AS TYPE,ctold.APPLICANTNO,NULL,TRAINDATE,TRAINDATE,NULL,NULL
														FROM crewtrainingold ctold
														WHERE ctold.APPLICANTNO=$applicantno AND ctold.TRAINCODE='$chktraincode'
														
														UNION
														
														SELECT 'UPD' AS TYPE,ctnosched.APPLICANTNO,NULL,TRAINDATE,TRAINDATE,NULL,NULL
														FROM crewtrainingnosched ctnosched
														WHERE ctnosched.APPLICANTNO=$applicantno AND ctnosched.TRAINCODE='$chktraincode'
													) x ON x.APPLICANTNO = cc.APPLICANTNO
													
													WHERE cc.APPLICANTNO=$applicantno AND cc.DOCCODE='$chkdoccode'
													ORDER BY DATEISSUED DESC
													LIMIT 1
													") or die(mysql_error());
								
								if (mysql_num_rows($qrycheckcertstatus) > 0)
								{
									$rowcheckcertstatus = mysql_fetch_array($qrycheckcertstatus);
									$certrankcode = $rowcheckcertstatus["RANKCODE"];
									
									if ($rowcheckcertstatus["DATEISSUED"] != "")
										$certdateissued = date("Y-m-d",strtotime($rowcheckcertstatus["DATEISSUED"]));
										
									$certgrade = $rowcheckcertstatus["GRADE"];
									$certstatus = $rowcheckcertstatus["STATUS"];
									
									if ($certstatus == "UPDATE" )
									{
										$certshow = "<a href=\"#\" style=\"font-weight:Bold;color:Green;\"
														onclick=\"
														applicantno.value = '$applicantno';
														traincodehidden.value = '$chktraincode';
														rankcodehidden.value = '$certrankcode';
														traindatehidden.value = '$certdateissued';
														gradehidden.value = '$certgrade';
														
														actiontxt.value='updatetraining';submit();\" 
														title=\"Certificate Found! Click to update training history.\">
														[UPDATE TRAINING]</a>";
									}
									else 
									{
										$certshow = "";
									}
								}
								
								//LISTING - START
								
//								$endorseshow = "";
////								$onclick = "-------";
//										
//								if ($endorsed == 0)
//								{
//									$onclick = "<a href=\"#\" onclick=\"applicantno.value='$applicantno';endorsetraincode.value='$chktraincode';actiontxt.value='endorse';submit();\" 
//									style=\"font-size:1em;font-weight:bold;color:#004080;\" title=\"Click to Endorse!\">[ENDORSE]</a>";
//								}
//								else 
//								{
//									$onclick = "<a href=\"#\" onclick=\"if(confirm('Training Endorsement of $crewname on Training -- [$chktraining], will be cancelled. Please Confirm?')) 
//											{applicantno.value='$applicantno';endorsetraincode.value='$chktraincode';actiontxt.value='unendorse';submit();}\" 
//											style=\"font-size:1em;font-weight:bold;color:Red;\" title=\"Undo Endorsement\">[CANCEL]</a>";
//									
//									$endorseshow = "<i>Not enrolled yet.</i>";
//								}
//								
//								
//								if ($endorsed == 1)
//									$styleendorse = "style=\"border:thin solid black;background-color:Yellow;\"";
//								else
//									$styleendorse = "style=\"border:thin solid black;\"";
								
								echo "
								<tr $mouseovereffect $styleendorse >
									<td $styledtl><b>$chktraining</b></td>
									<td $styledtl align=\"left\">
								";
									if (!empty($datefrom))
									{
										echo "<span style=\"font-weight:Bold;color:Black;\">$datefrom</span>&nbsp;&nbsp;";
										if ($historycnt > 1)
											echo "<a href=\"#\" onclick=\"openWindow('traininghistory.php?traincode=$chktraincode&applicantno=$applicantno','traininghistory' ,800, 565);\" style=\"font-weight:Bold;color:Green;\" title=\"Click to view history...\">[$historycnt]</a>";
									}
									else 
									{
										echo "-----";
									}
									echo "	
									</td>
									<td $styledtl align=\"left\">&nbsp;$statusshow&nbsp;$certshow&nbsp;$endorseshow</td>
									<td $styledtl align=\"center\" title=\"$chkdoccode\">&nbsp;$docshow</td>
<!--									<td $styledtl align=\"center\">$onclick</td>	-->
								</tr>
									";
							
								$tmpgroup = $chkcoursetypecode;
							}
//						}
				echo "
					</table>
				</div>
				";
			}
			else 
			{
				echo "
				<div style=\"width:100%;height:370px;overflow:auto;\">
					<br /><br /><br /><br /><br /><br /><br />
					<center>
						<span style=\"font:bold 1.3em;color:gray;\"><i>[ CHECKLIST Preview ]</i></span>
					</center>
				</div>
				
				";
			}

echo "
		</div>
		

	</div>
	
	
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"applicantno\" />
	<input type=\"hidden\" name=\"currentrankcode\" />
	
	<input type=\"hidden\" name=\"traincodehidden\" />
	<input type=\"hidden\" name=\"rankcodehidden\" />
	<input type=\"hidden\" name=\"traindatehidden\" />
	<input type=\"hidden\" name=\"gradehidden\" />
	
	<input type=\"hidden\" name=\"endorsetraincode\" />
	
</form>

</body>
";
?>