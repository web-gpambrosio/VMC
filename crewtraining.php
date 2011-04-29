<?php
include("veritas/connectdb.php");
include("veritas/include/stylephp.inc");
include("veritas/include/functions.inc");

session_start();

$currentdate = date("Y-m-d H:i:s");
$basedirid = "idpics/";
$basedirdocs = "docimg/";
// $basedirdocs = "docimages/";
$disableprint = "disabled=\"disabled\"";

$datenow = date("Y-m-d");

if (isset($_SESSION["employeeid"]))
	$employeeid = $_SESSION["employeeid"];
else 
	$employeeid = "";

if (isset($_POST["actiontxt"]))
	$actiontxt = $_POST["actiontxt"];

if (isset($_POST["applicantno"]))
	$applicantno = $_POST["applicantno"];
else 
{
	if (isset($_GET["applicantno"]))
		$applicantno = $_GET["applicantno"];
	else 
		$applicantno = 0;
}
	
if (isset($_POST["currentrankcode"]))
	$currentrankcode = $_POST["currentrankcode"];
	
if (isset($_POST["searchkey"]))
	$searchkey = $_POST["searchkey"];
	
if (isset($_POST["searchby"]))
	$searchby = $_POST["searchby"];
	
//if ($searchkey == "")
//	$disablesearch = "disabled=\"disabled\"";
//else 
//	$disablesearch = "";

	
//POSTS FOR "UPDATE TRAINING"

if (isset($_POST["traincodehidden"]))
	$traincodehidden = $_POST["traincodehidden"];

if (isset($_POST["rankcodehidden"]))
	$rankcodehidden = $_POST["rankcodehidden"];

if (isset($_POST["traindatehidden"]))
	$traindatehidden = $_POST["traindatehidden"];

if (isset($_POST["gradehidden"]))
	$gradehidden = $_POST["gradehidden"];

	
//POSTS	FOR "CANCEL TRAINING"
	
//if (isset($_POST["canceltraincode"]))
//	$canceltraincode = $_POST["canceltraincode"];

if (isset($_POST["cancelschedid"]))
	$cancelschedid = $_POST["cancelschedid"];

	
	
$disablechecklist = 0;
$showmultiple = "display:none;";
$multiple = 0;


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
		
	case "canceltraining":
		
			$qrycancelenroll = mysql_query("DELETE FROM crewtraining WHERE APPLICANTNO=$applicantno AND SCHEDID=$cancelschedid") or die(mysql_error());
		
		break;
	
}


include("include/crewsummary.inc");

//$qrycrewlist = mysql_query("SELECT DISTINCT te.APPLICANTNO
//							FROM trainingendorsement te
//							LEFT JOIN crew c ON c.APPLICANTNO=te.APPLICANTNO
//							WHERE SCHEDID IS NULL
//							ORDER BY c.FNAME
//							") or die(mysql_error());


$qryranklist=mysql_query("SELECT RANK,RANKCODE FROM rank WHERE STATUS=1 ORDER BY RANKING") or die(mysql_error());

$qrygetrank = mysql_query("SELECT RANK,ALIAS1 FROM rank WHERE RANKCODE='$currentrankcode'") or die(mysql_error());
$rowgetrank = mysql_fetch_array($qrygetrank);
$rankshow = $rowgetrank["RANK"];
$rankaliasshow = $rowgetrank["ALIAS1"];

echo "
<html>\n
<head>\n
<title>Training Checklist and Enrollment</title>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>

<body onload=\"\" style=\"overflow:hidden;background-color:White;\">\n

<form name=\"formchecklist\" method=\"POST\">\n


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




<span class=\"wintitle\">TRAINING CHECKLIST</span>

	<div style=\"width:100%;height:650px;\">

		<center>
		<table style=\"width:70%;margin-top:5px;\" border=1>
			<tr>
				<td align=\"left\" style=\"font-size:0.8em;font-weight:Bold;\">Search</td>
				<td>
					<select name=\"searchby\" onchange=\"if(this.value != ''){searchkey.focus();}\">
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
				</td>
				<td align=\"center\"><input type=\"text\" name=\"searchkey\" size=\"20\" value=\"$searchkey\" 
						onkeyup=\"this.value=this.value.toUpperCase();if (this.value != '') {btnfind.disabled=false;}\"
						style=\"color:Red;font-weight:Bold;\" onkeydown=\"if(event.keyCode==13) {btnfind.focus();}\"/>
						
					<input type=\"button\" name=\"btnfind\" value=\"Find\" onclick=\"currentrankcode.value='';actiontxt.value='find';submit();\" />
				</td>
			</tr>
		</table>
		</center>
		<br />
		
<!--
		<div style=\"width:20%;height:600px;float:left;padding:3 5 3 5;background-color:#DCDCDC;overflow:auto;\">
			<span class=\"sectiontitle\">CREW ENDORSED</span>
			
			<table class=\"listcol\">
				<tr>
					<th>NAME</th>
				</tr>
			";
			
//			while ($rowcrewlist = mysql_fetch_array($qrycrewlist))
//			{
//				$appno1 = $rowcrewlist["APPLICANTNO"];
//				
//				$qrygetcrew = mysql_query("SELECT CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,CREWCODE
//										FROM crew c
//										WHERE APPLICANTNO=$appno1
//										") or die(mysql_error());
//				
//				$rowgetcrew = mysql_fetch_array($qrygetcrew);
//				$endorsedname = $rowgetcrew["NAME"];
//				$endorsedcode = $rowgetcrew["CREWCODE"];
//				
//				echo "
//				<tr $mouseovereffect style=\"cursor:pointer;\" ondblclick=\"applicantno.value='$appno1';submit();\">
//					<td title=\"Crew Code:$endorsedcode \nApplicant No. : $appno1\">$endorsedname</td>
//				</tr>
//				";
//			}
				
				
			echo "
			</table>
		
		</div>
!-->	
		<div style=\"width:100%;float:right;\">
		
			<div style=\"width:100%;height:140px;margin:3 5 3 5;background-color:#004000;\">
			
				<span class=\"sectiontitle\">CREW INFORMATION</span>
				
				<div style=\"width:80%;height:100px;float:left;border:1px solid Black;\">
				";
					$stylehdr = "style=\"font-size:0.75em;font-weight:Bold;color:White;\"";
					$styledtl2 = "style=\"font-size:0.9em;font-weight:Bold;color:Yellow;\"";
				echo "
					<div style=\"width:100%;\">
					
						<table style=\"width:100%;background-color:Black;\">
							<tr>
								<td $stylehdr>NAME: <br /><span style=\"font-size:1.2em;color:Yellow;\">$crewname</span></td>
								<td $stylehdr>APPLICANT NO: <br /><span style=\"font-size:1.2em;color:Yellow;\">$applicantno</span></td>
								<td $stylehdr>CREW CODE: <br /><span style=\"font-size:1.2em;color:Yellow;\">$crewcode</span></td>
							</tr>
						</table>
						<br />
						
						<table style=\"width:48%;float:left;\">
							<tr>
								<td $stylehdr>LAST VESSEL</td>
								<td $stylehdr>:</td>
								<td $styledtl2>$lastvessel</td>
							</tr>
							<tr>
								<td $stylehdr>LAST RANK</td>
								<td $stylehdr>:</td>
								<td $styledtl2>$lastrankalias</td>
							</tr>
							<tr>
								<td $stylehdr>DISEMBARK</td>
								<td $stylehdr>:</td>
								<td $styledtl2>$lastdisembdate</td>
							</tr>
						</table>
						
						<table style=\"width:48%;float:right;\">
							<tr>
								<td $stylehdr>ASSIGNED VESSEL</td>
								<td $stylehdr>:</td>
								<td $styledtl2>$assignedvessel</td>
							</tr>
							<tr>
								<td $stylehdr>ASSIGNED RANK</td>
								<td $stylehdr>:</td>
								<td $styledtl2>$assignedrankalias</td>
							</tr>
							<tr>
								<td $stylehdr>ETD</td>
								<td $stylehdr>:</td>
								<td $styledtl2>$assignedetd</td>
							</tr>
						</table>
					</div>
					<hr />
					
					<div style=\"width:100%;height:70px;overflow:auto;\">
					
						<div style=\"width:80%;height:100%;float:left;overflow:auto;\">
							<table class=\"listcol\" >
								<tr>
<!--									<th>TYPE</th>	-->
									<th>VESSEL</th>
									<th>RANK</th>
									<th>EMBARK</th>
<!--									<th>DEPART MNL</th>	-->
									<th>DISEMBARK</th>
<!--									<th>ARRIVE MNL</th>	-->
									<th>REASON</th>
								</tr>
								
								 $content
			 
							</table>
						</div>
						
						<div style=\"width:20%;height:100%;float:right;overflow:auto;\">
							<table width=\"100%\">
								<tr>
									<td style=\"font-size:1em;font-weight:Bold;color:White;background-color:Orange\" align=\"center\"><u>$appstatus</u></td>
								</tr>
							</table>
						</div>
					</div>
					
				</div>
				
				<div style=\"width:20%;float:right;color:Orange;\">
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
					<br />
					<center>
						<input type=\"button\" $disableprint style=\"font-size:0.9em;font-weight:Bold;border:1px solid White;color:Red;background-color:Yellow;cursor:pointer;\" 
								value=\"PRINT \n CHECKLIST\" onclick=\"openWindow('reptrainingchecklist.php?applicantno=$applicantno&rankcode=$currentrankcode', 'repchecklist' ,0, 0);\" />
					</center>
				</div>
				
			</div>
		
			
			<span class=\"sectiontitle\">TRAINING CHECKLIST - &nbsp;$rankshow&nbsp;($rankaliasshow)</span>
			<br />
			";
		
			if (!empty($applicantno))
			{
				$qrystatus = mysql_query("SELECT RECOMMENDED,UPGRADING,PROMOTION 
											FROM trainingendorsestatus WHERE APPLICANTNO=$applicantno
										") or die(mysql_error());
				
				$statuscontent = "[&nbsp;&nbsp;] - With Recommendation";
				$statuscontent .= "&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;&nbsp;] - Upgrading";
				$statuscontent .= "&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;&nbsp;] - Promotion";
				
				if (mysql_num_rows($qrystatus) > 0)
				{
					$statuscontent = "";
					
					$rowstatus = mysql_fetch_array($qrystatus);
					
					if ($rowstatus["RECOMMENDED"] == 1)
						$xrecommend = "X";
					
					if ($rowstatus["UPGRADING"] == 1)
						$xupgrading = "X";
						
					if ($rowstatus["PROMOTION"] == 1)
						$xpromotion = "X";
					
					
					$statuscontent = "[ " . $xrecommend . " ] - With Recommendation";
					$statuscontent .= "&nbsp;&nbsp;&nbsp;&nbsp;[ " . $xupgrading . " ] - Upgrading";
					$statuscontent .= "&nbsp;&nbsp;&nbsp;&nbsp;[ " . $xpromotion . " ] - Promotion";
				}
			}
			else 
			{
				$statuscontent = "";
			}
		
	echo "		
			<span style=\"font-size:1em;font-weight:Bold;\">$statuscontent</span>
			<div style=\"width:100%;height:325px;background-color:#DCDCDC;margin:3 5 3 5;border:1px solid Black;overflow:auto;\">
				
	";
	
	
	include("veritas/include/checklist.inc");
								
			if (mysql_num_rows($qrychecklist) > 0)
			{
				echo "
				<div style=\"width:100%;height:100%;\">
					
					<table width=\"100%\" style=\"font-size:0.8em;background-color:#F0F1E9;border:1px solid black;\">
						<tr style=\"font-weight:bold;background-color:Black;color:White;\">
							<td width=\"30%\" align=\"center\">COURSE</td>
<!--								<td width=\"5%\" align=\"center\">REQ</td>	-->
							<td width=\"15%\" align=\"center\">TRAINING DATE</td>
							<td width=\"25%\" align=\"center\">STATUS</td>
							<td width=\"20%\" align=\"center\">REMARKS</td>
							<td width=\"10%\" align=\"center\">ACTION</td>
						</tr>
						";
				
						if ($currentrankcode != "")
						{
							$tmpgroup = "";
//							$trainneeds = 1;

							while ($rowchecklist = mysql_fetch_array($qrychecklist))
							{
								$chkidno = $rowchecklist["IDNO"];
								$chkpos = $rowchecklist["POS"];
								$chktraincode = $rowchecklist["TRAINCODE"];
								$chktraining = $rowchecklist["TRAINING"];
								$chkalias = $rowchecklist["ALIAS"];
								$chkrankcode = $rowchecklist["RANKCODE"];
//								$chkrequired = $rowchecklist["REQUIRED"];
								
//								if ($chkrequired == 0)
//									$chkrequiredshow = "N";
//								else 
//									$chkrequiredshow = "Y";
									
								$chkcoursetypecode = $rowchecklist["COURSETYPECODE"];
								$chkcoursetype = $rowchecklist["COURSETYPE"];
								$chkdoccode = $rowchecklist["DOCCODE"];
								$chkstatus = $rowchecklist["STATUS"];
								
								
								$endorsed = 0;
								$qrytrainendorse = mysql_query("SELECT TRAINCODE,REMARKS FROM trainingendorsement 
													WHERE APPLICANTNO=$applicantno AND TRAINCODE='$chktraincode' 
													") or die(mysql_error());
								
								if (mysql_num_rows($qrytrainendorse) > 0)
									$endorsed = 1;
								
								if ($chkcoursetypecode != $tmpgroup)
								{
//									$style = "style=\"background-color:Navy;color:Orange;font-weight:Bold;font-size:1.2em;text-align:center;\"";
//									
//									if ($chkpos == "2" && $trainneeds == 1)
//									{
//										echo "<tr><td colspan=\"6\"><br /><hr /><br />
//										<span class=\"sectiontitle\" style=\"font-size:1em;\">ADDITIONAL TRAINING NEEDS</span></td></tr>";
//										
//										$trainneeds ++;
//									}
//									
//									if ($chkpos == "2")
//										$style = "style=\"background-color:Green;color:White;font-weight:Bold;font-size:1.2em;text-align:center;\"";
//										
//									echo "<tr><td colspan=\"6\" $style>$chkcoursetype</td></tr>";
								}
									
									
								if ($chkcoursetypecode != $tmpgroup)
								{
									$style = "style=\"background-color:Navy;color:Orange;font-weight:Bold;font-size:1.2em;text-align:center;\"";
									
									if ($chkcoursetypecode == "OTH")
										echo "<tr><td colspan=\"6\"><br /><hr />
										<span class=\"sectiontitle\" style=\"font-size:1em;\">$chkcoursetype</span></td></tr>";
									else 
										echo "<tr><td colspan=\"6\" $style>$chkcoursetype</td></tr>";
								}
								
								$disabledenroll = 0;
								
								$status = "";
								$statusshow = "";
								$datefrom = "";
								$dateto = "";
								$certshow = "";
								$docshow = "";
								

								$qrychecklistinhouse = mysql_query("
												SELECT *,															
												IF (DATEFROM > CURRENT_DATE,'ENROLLED',
												    IF (DATETO < CURRENT_DATE,
												        'FINISHED',
												        'ON-GOING'
												        )
												) AS STATUS2
												FROM (
													SELECT 'IN' AS TYPE,ct.SCHEDID,th.DATEFROM,th.DATETO,th.STATUS AS TRAINSTATUS,IF(ct.STATUS=1,IF(th.DATETO < '2007-01-01',9,1),ct.STATUS) AS STATUS
													FROM crewtraining ct
													LEFT JOIN trainingschedhdr th ON th.SCHEDID=ct.SCHEDID
													WHERE ct.APPLICANTNO=$applicantno AND th.TRAINCODE='$chktraincode'
												
													UNION
												
													SELECT 'OLD' AS TYPE,NULL,TRAINDATE,TRAINDATE,NULL,IF(TRAINDATE < '2007-01-01',9,1) AS STATUS
													FROM crewtrainingold ctold
													WHERE ctold.APPLICANTNO=$applicantno AND ctold.TRAINCODE='$chktraincode'
													
													UNION
													
													SELECT 'UPD' AS TYPE,NULL,TRAINDATE,TRAINDATE,NULL,IF(TRAINDATE < '2007-01-01',9,1) AS STATUS
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
									
									if ($datefrom != $dateto)
										$dateshow = $datefrom . " to " . $dateto;
									else 
										$dateshow = $datefrom;									
									
									$trainstatus = $rowchecklistinhouse["TRAINSTATUS"]; //IF TRAINING IS CANCELLED
									$status = $rowchecklistinhouse["STATUS"]; //COMPLETED;INCOMPLETE
									$status2 = $rowchecklistinhouse["STATUS2"]; //ENROLLED;FINISHED;ON-GOING
									$type = $rowchecklistinhouse["TYPE"];
									
									if ($status == 1)
										$completed = "COMPLETED";
									if ($status == 2)
										$completed = "INCOMPLETE";
									if ($status == 9)
										$completed = "";
									if (empty($status))
										$completed = "NOT POSTED YET";
									
									$stylestatus = "";
										
									switch ($status2)
									{
										case "ENROLLED"	:
											
												$statusshow = "<span style=\"font-size:0.9em;color:Yellow;background-color:Black;font-weight:Bold;\">" . "&nbsp;ENROLLED " . "</span><i>&nbsp;&nbsp;&nbsp;" . $dateshow . "</i>";
												$disabledenroll = 1;
												
											break;
										case "FINISHED"	:
											
												$statusshow = "<i>&nbsp;&nbsp; Ended:&nbsp;&nbsp;"  .$dateto . "</i>&nbsp;<span style=\"font-size:0.9em;color:White;background-color:Red;font-weight:Bold;\">$completed</span>";
												
											
											break;
										case "ON-GOING"	:
											
												$statusshow = "<span style=\"font-size:0.9em;color:Lime;background-color:Black;font-weight:Bold;\">" . "&nbsp;ON-GOING " . "</span><i>&nbsp;&nbsp; Until&nbsp;" . $dateto . "</i>";
												// $disabledenroll = 2;
												$disabledenroll = 1;
												
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

									
								$styledtl = " style=\"font-size:1em;color:Black;border-bottom:1px dashed gray;border-right:1px dashed Gray;v-align:middle;\"";
								
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
								
								switch ($disabledenroll)
								{
									case "0"	:
										
											if ($disablechecklist == 0)
												$onclick = "<a href=\"#\" 
												onclick=\"openWindow('enrollment.php?traincode=$chktraincode&applicantno=$applicantno&getname=$crewname&getrankcode=$currentrankcode&getvesselcode=$currentvesselcode&getetd=$currentetd','enrollment' ,800, 565);\" 
												style=\"font-size:1em;font-weight:bold;color:#FF6600;\">[ENROLL]</a>";
											else 
												$onclick = "-------";
										
										break;
										
									case "1"	:
										
											$onclick = "<a href=\"#\" 
												onclick=\"if(confirm('Enrollment reservation/On-Going Course of $crewname on Training -- [$chktraining], with Schedule ID:$schedid, scheduled on $datefrom to $dateto will be cancelled. Please Confirm?')) 
															{applicantno.value='$applicantno';cancelschedid.value='$schedid';actiontxt.value='canceltraining';submit();}
														\" 
												style=\"font-size:1em;font-weight:bold;color:Blue;\">[CANCEL]</a>";
										
										break;
										
									case "2"	:
										
											$onclick = "-------";
										
										break;
								}
								
								$remarksshow = "------";
								$remarks = "";
								if ($endorsed == 1)
								{
									$endorsestyle = "background-color:Yellow;";
									$remarksshow = "<a href=\"#\" onclick=\"openWindow('trainingcomments.php?traincode=$chktraincode&applicantno=$applicantno','trainingcomments' ,500, 300);\" 
											style=\"font-weight:Bold;color:Green;\" title=\"Click to encode Remarks...\">[EDIT]</a>";
											
									$rowtrainendorse = mysql_fetch_array($qrytrainendorse);
									$remarks = $rowtrainendorse["REMARKS"];
								}
								else 
									$endorsestyle = "";
									
								echo "
								<tr $mouseovereffect style=\"border:thin solid black;$endorsestyle\">
									<td $styledtl>$chktraining</td>
<!--										<td $styledtl align=\"center\">$chkrequiredshow</td>	-->
									<td $styledtl align=\"center\">
									";
										if (!empty($datefrom))
										{
											echo "<span style=\"font-weight:Bold;color:Black;\">$datefrom</span>&nbsp;&nbsp;";
											
											if ($historycnt > 1)
											{
												echo "<a href=\"#\" onclick=\"openWindow('traininghistory.php?traincode=$chktraincode&applicantno=$applicantno','traininghistory' ,800, 565);\" 
													style=\"font-weight:Bold;color:Green;\" title=\"Click to view history...\">[$historycnt]</a>";
											}
										}
										else 
										{
											echo "N/A";
										}
									echo "	
									</td>
									<td $styledtl align=\"left\">&nbsp;$statusshow&nbsp;$certshow</td>
									<td $styledtl align=\"center\" title=\"$chkdoccode\">$remarks $remarksshow</td>
									<td $styledtl align=\"center\">$onclick</td>
								</tr>
								";
							
								$tmpgroup = $chkcoursetypecode;
							}
						}
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
		
	</div>
	
	
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"applicantno\" />
	<input type=\"hidden\" name=\"currentrankcode\" />
	
	<input type=\"hidden\" name=\"traincodehidden\" />
	<input type=\"hidden\" name=\"rankcodehidden\" />
	<input type=\"hidden\" name=\"traindatehidden\" />
	<input type=\"hidden\" name=\"gradehidden\" />
	
	<input type=\"hidden\" name=\"cancelschedid\" />
	
</form>

</body>
";
?>