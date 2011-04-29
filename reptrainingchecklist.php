<?php
include('veritas/connectdb.php');
include("veritas/include/stylephp.inc");
include("veritas/include/functions.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');
$currentdateshow = date('dMY H:i:s');
$basedirid = "idpics/";
$basedirdocs = "docimg/";
// $basedirdocs = "docimages/";

$datenow = date("Y-m-d");

if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
else 
	$employeeid = "";

if (isset($_GET['applicantno']))
	$applicantno = $_GET['applicantno'];

if (isset($_GET['rankcode']))
	$currentrankcode = $_GET['rankcode'];

//$currentrankcode = "E23";
//$applicantno = 100899;

include("include/crewsummary.inc");


echo "
<html>\n
<head>\n
<title>Training Checklist - Printing</title>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>
<style type='text/css'>
@media print 
{
	#PAScreenOut { display: none; }
}
</style>
";
include('include/printclose.inc');
echo "
<body onload=\"\" style=\"background-color:White;overflow:auto;\">\n

	<div style=\"width:1000px;height:680px;background-color:White;margin:5 5 5 5;\">
	
		<div style=\"width:100%;height:100px;background-color:White;\">
		";
			$stylehdr = "style=\"font-size:0.6em;font-weight:Bold;\"";
			$styledtl2 = "style=\"font-size:0.7em;font-weight:Bold;text-decoration:underline;\"";
		echo "
			<div style=\"width:80%;height:100%;float:left;\">
				<span style=\"font-size:0.7em;font-weight:Bold;color:Black;float:right;\">FM-249M <br /> REV. JUNE 2008</span>
				<span style=\"font-size:1.3em;font-weight:Bold;text-align:center;\">Training Checklist</span>&nbsp;&nbsp;
				<span style=\"font-size:0.8em;font-weight:Bold;color:Black;\">(PRINT DATE: &nbsp;&nbsp;$currentdateshow)&nbsp;&nbsp;
					<input type=\"button\" value=\"Print\" id=\"PAScreenOut\" onclick=\"window.print();\">
				</span>
				<hr />
<!--
				<table style=\"width:100%;\">
					<tr>
						<td $stylehdr>NAME: &nbsp;&nbsp;<span style=\"font-size:1.2em;color:Black;text-decoration:underline;\">$crewname</span></td>
						<td $stylehdr>APPLICANT NO: &nbsp;&nbsp;<span style=\"font-size:1.2em;color:Black;text-decoration:underline;\">$applicantno</span></td>
						<td $stylehdr>CREW CODE: &nbsp;&nbsp;<span style=\"font-size:1.2em;color:Black;text-decoration:underline;\">$crewcode</span></td>
					</tr>
				</table>
-->
				<table style=\"width:40%;float:left;border-right:1px solid Gray;\">
					<tr>
						<td $stylehdr>NAME</td>
						<td $stylehdr>:</td>
						<td $styledtl2>$crewname</td>
					</tr>
					<tr>
						<td $stylehdr>APPLICANT NO</td>
						<td $stylehdr>:</td>
						<td $styledtl2>$applicantno</td>
					</tr>
					<tr>
						<td $stylehdr>CREW CODE</td>
						<td $stylehdr>:</td>
						<td $styledtl2>$crewcode</td>
					</tr>
				</table>
				<table style=\"width:30%;float:left;border-right:1px solid Gray;\">
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
				
				<table style=\"width:30%;float:right;\">
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
			
			<div style=\"width:20%;height:100%;float:right;\">
	";
				$dirfilename = $basedirid . $applicantno . ".JPG";
				if (checkpath($dirfilename))
				{
					$scale = imageScale($dirfilename,-1,120);
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
		
		<div style=\"width:100%;height:540px;\">
";
			include("veritas/include/checklist.inc");
										
				if (mysql_num_rows($qrychecklist) > 0)
				{
					echo "
						
						<table width=\"90%\" style=\"font-size:0.7em;background-color:White;border:1px solid black;\">
							<tr style=\"font-weight:bold;color:Black;\">
								<td width=\"30%\" align=\"center\"><u>COURSE</u></td>
								<td width=\"10%\" align=\"center\"><u>TRAIN DATE</u></td>
								<td width=\"15%\" align=\"center\"><u>STATUS</u></td>
								<td width=\"20%\" align=\"center\"><u>REMARKS</u></td>
							</tr>
							";
					
						if ($currentrankcode != "")
						{
								$tmpgroup = "";
								$trainneeds = 1;
		
								while ($rowchecklist = mysql_fetch_array($qrychecklist))
								{
									$chkidno = $rowchecklist["IDNO"];
									$chkpos = $rowchecklist["POS"];
									$chktraincode = $rowchecklist["TRAINCODE"];
									$chktraining = $rowchecklist["TRAINING"];
									$chkalias = $rowchecklist["ALIAS"];
									$chkrankcode = $rowchecklist["RANKCODE"];
//									$chkrequired = $rowchecklist["REQUIRED"];
									
//									if ($chkrequired == 0)
//										$chkrequiredshow = "N";
//									else 
//										$chkrequiredshow = "Y";
										
									$chkcoursetypecode = $rowchecklist["COURSETYPECODE"];
									$chkcoursetype = $rowchecklist["COURSETYPE"];
									$chkdoccode = $rowchecklist["DOCCODE"];
									$chkstatus = $rowchecklist["STATUS"];
									
									
									$endorsed = 0;
									$enrolled = 0;
									$qrytrainendorse = mysql_query("SELECT SCHEDID,TRAINCODE,REMARKS FROM trainingendorsement 
														WHERE APPLICANTNO=$applicantno AND TRAINCODE='$chktraincode' ") or die(mysql_error());
									$remarks = "";
									if (mysql_num_rows($qrytrainendorse) > 0)
									{
										$endorsed = 1;
										$rowtrainendorse = mysql_fetch_array($qrytrainendorse);
										$remarks = $rowtrainendorse["REMARKS"];
										
										if (!empty($rowtrainendorse["SCHEDID"])) //ENROLLED ALREADY
											$enrolled = 1;
									}
									
									if ($chkcoursetypecode != $tmpgroup)
									{
										$style = "style=\"border:1px solid Gray;color:Black;font-weight:Bold;font-size:1em;\"";
										
										if ($chkcoursetypecode == "OTH")
											echo "<tr><td colspan=\"6\" $style><hr />$chkcoursetype</td></tr>";
										else 
											echo "<tr><td colspan=\"6\" $style><br /><i>$chkcoursetype</i></td></tr>";
									}
									
//									if ($chkcoursetypecode != $tmpgroup)
//									{
//										$style = "style=\"border:1px solid Gray;color:Black;font-weight:Bold;font-size:1em;\"";
//										
//										if ($chkpos == "2" && $trainneeds == 1)
//										{
//											echo "<tr><td colspan=\"6\"><br /><hr /><br />
//											<span class=\"sectiontitle\" style=\"font-size:1em;\">ADDITIONAL TRAINING NEEDS</span></td></tr>";
//											
//											$trainneeds ++;
//										}
//										
//										echo "<tr><td colspan=\"6\" $style>$chkcoursetype</td></tr>";
//									}
									
									
									$disabledenroll = 0;
									
									$status = "";
									$statusshow = "";
									$datefrom = "";
									$dateto = "";
									$certshow = "";
									$docshow = "";
									$status2 = "";
									
		
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
										
										switch ($status2)
										{
											case "ENROLLED"	:
												
													$qrygettraindtls = mysql_query("SELECT tsh.DATEFROM,tsh.DATETO,tsd.TIMESTART,tsd.TIMEEND,
																					IF(tv.TRAINVENUE IS NULL,tc.TRAINCENTER,tv.TRAINVENUE) AS VENUE
																					FROM trainingschedhdr tsh
																					LEFT JOIN trainingscheddtl tsd ON tsd.SCHEDID=tsh.SCHEDID
																					LEFT JOIN trainingvenue tv ON tv.TRAINVENUECODE=tsd.TRAINVENUECODE
																					LEFT JOIN trainingcenter tc ON tc.TRAINCENTERCODE=tsd.TRAINCENTERCODE
																					WHERE tsh.SCHEDID=$schedid
																					ORDER BY IDNO
																					LIMIT 1
																				") or die(mysql_error());
													
													if (mysql_num_rows($qrygettraindtls) > 0)
													{
														$rowgettraindtls = mysql_fetch_array($qrygettraindtls);
														
														if (!empty($rowgettraindtls["DATEFROM"]))
															$dtldatefrom = date("dMY",strtotime($rowgettraindtls["DATEFROM"]));
														else 
															$dtldatefrom = "";
															
														if (!empty($rowgettraindtls["DATETO"]))
															$dtldateto = date("dMY",strtotime($rowgettraindtls["DATETO"]));
														else 
															$dtldateto = "";
															
														if (!empty($rowgettraindtls["TIMESTART"]))
															$dtltimestart = date("H:i",strtotime($rowgettraindtls["TIMESTART"]));
														else 
															$dtltimestart = "";
															
														if (!empty($rowgettraindtls["TIMEEND"]))
															$dtltimeend = date("H:i",strtotime($rowgettraindtls["TIMEEND"]));
														else 
															$dtltimeend = "";
														
														$dtlvenue = $rowgettraindtls["VENUE"];
														
														if ($dtldatefrom != $dtldateto)
															$details = "( " . $dtldatefrom . " to " . $dtldateto . " | " . $dtltimestart . " - " . $dtltimeend . " | " . "Venue: $dtlvenue" . " )";
														else 
															$details = "( " . $dtldatefrom . " | " . $dtltimestart . " - " . $dtltimeend . " | " . "Venue: $dtlvenue" . " )";
														
													}
												
													$statusshow = "<span style=\"font-weight:Bold;\">" . "&nbsp;ENROLLED " . "</span><i>&nbsp;&nbsp;&nbsp;</i> $details";
													$disabledenroll = 1;
													
												break;
											case "FINISHED"	:
												
													$statusshow = "<i>"  .$dateto . "</i>&nbsp;--->&nbsp;&nbsp;<span style=\"font-weight:Bold;\">&nbsp;$completed&nbsp;</span>";
													
												
												break;
											case "ON-GOING"	:
												
													$statusshow = "<span style=\"font-weight:Bold;\">" . "&nbsp;ON-GOING " . "</span><i>&nbsp;&nbsp; Until&nbsp;" . $dateto . "</i>";
													$disabledenroll = 2;
													
												break;
										}
									}
		
									
									//CHECK IF DOCUMENT IS READY FOR VIEWING...
									
									$dirfilename1 = $basedirdocs . $applicantno . "/C/" . $chkdoccode . ".pdf";
									
									if (checkpath($dirfilename1))
									{
										$docshow = "YES";
									}
									else 
										$docshow = "---";
		
										
									$styledtl = " style=\"font-size:0.9em;color:Black;border-bottom:1px dashed gray;border-right:1px dashed Gray;v-align:middle;\"";
									
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
//											$certshow = "<a href=\"#\" style=\"font-weight:Bold;color:Green;\"
//															onclick=\"
//															applicantno.value = '$applicantno';
//															traincodehidden.value = '$chktraincode';
//															rankcodehidden.value = '$certrankcode';
//															traindatehidden.value = '$certdateissued';
//															gradehidden.value = '$certgrade';
//															
//															actiontxt.value='updatetraining';submit();\" 
//															title=\"Certificate Found! Click to update training history.\">
//															[UPDATE TRAINING]</a>";

											$certshow = "Certificate found. Please UPDATE!";
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
													onclick=\"if(confirm('Enrollment reservation of $crewname on Training -- [$chktraining], with Schedule ID:$schedid, scheduled on $datefrom to $dateto will be cancelled. Please Confirm?')) 
																{applicantno.value='$applicantno';cancelschedid.value='$schedid';actiontxt.value='canceltraining';submit();}
															\" 
													style=\"font-size:1em;font-weight:bold;color:Blue;\">[CANCEL]</a>";
											
											break;
											
										case "2"	:
											
												$onclick = "-------";
											
											break;
									}
									
//									if ($endorsed == 1)
//										$endorsestyle = "background-color:Yellow;";
//									else 
//										$endorsestyle = "";

//									if (($status2 == "ENROLLED" || $status2 == "ON-GOING"))
//									{
//										
//										if ($chkcoursetypecode != $tmpgroup)
//										{
//											$style = "style=\"border:1px solid Gray;color:Black;font-weight:Bold;font-size:1em;\"";
////											if ($chkpos == "2" && $trainneeds == 1)
////											{
////												echo "<tr><td colspan=\"6\"><hr />
////												<span class=\"sectiontitle\" style=\"font-size:1em;\">ADDITIONAL TRAINING NEEDS</span></td></tr>";
////												
////												$trainneeds ++;
////											}
//											
////											if ($chkpos == "1")
////												echo "<tr><td colspan=\"6\" $style>$chkcoursetype</td></tr>";
////											elseif ($chkpos == "2" && $status2 == "ENROLLED")
//												echo "<tr><td colspan=\"6\" $style>$chkcoursetype</td></tr>";
//										}
										
										
										
										echo "
										<tr $mouseovereffect style=\"border:thin solid black;$endorsestyle\">
											<td $styledtl>$chktraining</td>
			<!--										<td $styledtl align=\"center\">$chkrequiredshow</td>	-->
											<td $styledtl align=\"center\">
											";
												if (!empty($datefrom))
												{
													echo "<span style=\"font-weight:Bold;color:Black;\">$datefrom</span>&nbsp;&nbsp;";
													
	//												if ($historycnt > 1)
	//												{
	//													echo "<a href=\"#\" onclick=\"openWindow('traininghistory.php?traincode=$chktraincode&applicantno=$applicantno','traininghistory' ,800, 565);\" 
	//														style=\"font-weight:Bold;color:Green;\" title=\"Click to view history...\">[$historycnt]</a>";
	//												}
												}
												else 
												{
													echo "N/A";
												}
											echo "	
											</td>
											<td $styledtl align=\"left\">&nbsp;$statusshow&nbsp;$certshow</td>
											<td $styledtl align=\"center\">&nbsp;$remarks</td>
										</tr>
										";
//									}
								
									$tmpgroup = $chkcoursetypecode;
								}
						}
					echo "
						</table>
						
						";
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
					
					echo "
						<span style=\"font-size:1em;font-weight:Bold;\">$statuscontent</span>
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
	<br />
	<div style=\"width:900px;height:40px;background-color:White;margin:10 10 10 10;\">
	
		<table style=\"width:25%;float:left;font-size:0.75em;\">
			<tr><td style=\"border-top:1px solid black;\"><b>BERNADETTE C. BARIAS</b></td></tr>
			<tr><td>Training Assistant - Division 1</td></tr>
			<tr><td>310-0317</td></tr>
		</table>
	
		<table style=\"width:25%;float:left;font-size:0.75em;\">
			<tr><td style=\"border-top:1px solid black;\"><b>SHAMAINE D. VALENCE</b></td></tr>
			<tr><td>Training Assistant - Division 2</td></tr>
			<tr><td>310-0317</td></tr>
		</table>
		
		<table style=\"width:25%;float:right;font-size:0.75em;\">
			<tr><td style=\"border-top:1px solid black;\"><b>CAPT. LEXINGTON CALUMPANG</b></td></tr>
			<tr><td>Assistant General Manager <br /> Training Division</td></tr>
		</table>
	</div>

</body>

</html>

";
	
	
?>