<?php
include('veritas/connectdb.php');

session_start();

$currentdate = date('Y-m-d H:i:s');
$basedirid = "idpics/";
$basedirdocs = "docimg/";
// $basedirdocs = "docimages/";


if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
else 
	$employeeid = "";
	
	
if (isset($_POST['schedidhidden']))
	$schedidhidden = $_POST['schedidhidden'];

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];

if (isset($_POST['applicantno']))
	$applicantno = $_POST['applicantno'];
	
if (isset($_POST['rankcode']))
	$rankcode = $_POST['rankcode'];
else 
	$rankcode = "All";

if (isset($_POST['traincodehidden']))
	$traincodehidden = $_POST['traincodehidden'];

if (isset($_POST['rankcodehidden']))
	$rankcodehidden = $_POST['rankcodehidden'];

if (isset($_POST['traindatehidden']))
	$traindatehidden = $_POST['traindatehidden'];

if (isset($_POST['gradehidden']))
	$gradehidden = $_POST['gradehidden'];

if (isset($_POST['coursetypecodehidden']))
	$coursetypecodehidden = $_POST['coursetypecodehidden'];

if (isset($_POST['currentrankcodehidden']))
	$currentrankcodehidden = $_POST['currentrankcodehidden'];
	
if ($currentrankcodehidden != "")
	$currentrankcode = $currentrankcodehidden;

	
function checkpath($path)
{
	if (is_file($path))
		return true;
	else 
		return false;
}


switch ($actiontxt)
{
	case "checklist"	:
		
			$qryapplicantinfo = mysql_query("SELECT CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,r.RANK,r.RANKCODE
											FROM applicant a
											LEFT JOIN applicantstatus ap ON ap.APPLICANTNO=a.APPLICANTNO
											LEFT JOIN crew c ON c.APPLICANTNO=a.APPLICANTNO
											LEFT JOIN rank r ON r.RANKCODE=ap.VMCRANKCODE
											WHERE a.APPLICANTNO=$applicantno
											") or die(mysql_error());
			
			$rowapplicantinfo = mysql_fetch_array($qryapplicantinfo);
			
			$applicantname = $rowapplicantinfo["NAME"];
			$applicantrank = $rowapplicantinfo["RANK"];
			$applicantrankcode = $rowapplicantinfo["RANKCODE"];
			
			$currentrankcode = $applicantrankcode;

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
}
	
	
$rankselectM = "";
$rankselectO = "";
$rankselectS = "";
$rankselectD = "";
$rankselectE = "";
$rankselectT = "";
$rankselectALL = "";

$whererank = "";

switch ($rankcode)
{
	case "M"	:  //MANAGEMENT
		
			$whererank = "AND rl.RANKLEVELCODE='M'";
			$rankselectM = "SELECTED";
			
		break;
	case "O"	:  //OPERATIONAL
		
			$whererank = "AND rl.RANKLEVELCODE='O'";
			$rankselectO = "SELECTED";
			
		break;
	case "S"	:  //SUPPORT
		
			$whererank = "AND rl.RANKLEVELCODE='S'";
			$rankselectS = "SELECTED";
			
		break;
	case "D"	:  //DECK
		
			$whererank = "AND rt.RANKTYPECODE='D'";
			$rankselectD = "SELECTED";
	
		break;
	case "E"	:  //ENGINE
		
			$whererank = "AND rt.RANKTYPECODE='E'";
			$rankselectE = "SELECTED";
			
		break;
	case "T"	:  //STEWARD
		
			$whererank = "AND rt.RANKTYPECODE='S'";
			$rankselectT = "SELECTED";
			
		break;
	
	case "All"	:  //ALL RANKS
		
			$whererank = "";
			$rankselectALL = "SELECTED";
			
		break;
	
	default		: //ANY RANKCODE
	
			$whererank = "AND r.RANKCODE='$rankcode'";
			
		break;
}

$qryranklist=mysql_query("SELECT RANK,RANKCODE FROM rank WHERE STATUS=1 ORDER BY RANKING") or die(mysql_error());
	
$qryfortraining = mysql_query("SELECT at.APPLICANTNO,CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,
							r.RANK,r.ALIAS1,r.RANKLEVELCODE,r.RANKTYPECODE,at.ACCEPTBY,at.ACCEPTDATE
							FROM applicantstatus at
							LEFT JOIN applicantexam ax ON ax.APPLICANTNO=at.APPLICANTNO
							LEFT JOIN crew c ON c.APPLICANTNO=at.APPLICANTNO
							LEFT JOIN rank r ON r.RANKCODE=at.VMCRANKCODE
							LEFT JOIN ranklevel rl ON rl.RANKLEVELCODE=r.RANKLEVELCODE
							LEFT JOIN ranktype rt ON rt.RANKTYPECODE=r.RANKTYPECODE
							WHERE ax.APPLICANTNO IS NOT NULL
							AND at.ACCEPTDATE IS NOT NULL AND APPROVEDDATE IS NULL
							AND ax.PASSED=1
							$whererank
							ORDER BY r.RANKLEVELCODE,r.RANKTYPECODE,r.RANK,NAME
							") or die(mysql_error());



echo "
<html>\n
<head>\n
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>

<body onload=\"\" style=\"overflow:hidden;background-color:White;\">\n

<form name=\"formchecklist\" method=\"POST\">\n

<span class=\"sectiontitle\">TRAINING CHECKLIST FOR NEW HIRES</span>

	<div style=\"width:100%;\">
	
		<div style=\"width:100%;height:150px;background-color:White;\">

			<div style=\"width:60%;height:150px;float:left;background-color:White;overflow:auto;\">
				<table width=\"100%\" class=\"listcol\" style=\"font-size:0.7em;\">
					<tr>
						<th>APPNO</th>
						<th>NAME</th>
						<th>BY</th>
						<th>DATE</th>
					</tr>
				";
				
				$tmprank = "";
		
				while ($rowfortraining=mysql_fetch_array($qryfortraining))
				{
					$applicantno1 = $rowfortraining["APPLICANTNO"];
					$name = $rowfortraining["NAME"];
					$rank = $rowfortraining["RANK"];
					$acceptby = $rowfortraining["ACCEPTBY"];
					$acceptdate = date('m/d/Y',strtotime($rowfortraining["ACCEPTDATE"]));
					
					if ($tmprank != $rank)
					{
						echo "
						<tr>
							<td colspan=\"4\" align=\"left\" style=\"font-size:1em;font-weight:bold;color:Blue;\"><i>$rank</i></td>
						</tr>
						";
					}
					
					echo "
					<tr ondblclick=\"actiontxt.value='checklist';applicantno.value='$applicantno1';formchecklist.submit();\">
						<td style=\"font-size:0.9em;\">$applicantno1</td>
						<td style=\"font-size:0.9em;\">$name</td>
						<td style=\"font-size:0.9em;\" align=\"center\">$acceptby</td>
						<td style=\"font-size:0.9em;\" align=\"center\">$acceptdate</td>
					</tr>
					";
					
					$tmprank = $rank;
				}
		
		echo "
				</table>
			</div>
			
			<div style=\"width:40%;height:150px;float:right;background-color:#CCCC66;\">
			
				<center>
				<table style=\"margin-top:5px;\">
					<tr>
						<td colspan=\"2\" align=\"center\" style=\"font-size:0.8em;font-weight:Bold;\">Rank List</td>
					</tr>
					<tr>
						<td>
							<select name=\"rankcode\" onchange=\"applicantno.value='';submit();\">
								<option $rankselectALL value=\"All\">-All-</option>
								<option $rankselectM value=\"M\">-Management-</option>
								<option $rankselectO value=\"O\">-Operation-</option>
								<option $rankselectS value=\"S\">-Support-</option>
								<option $rankselectD value=\"D\">-Deck-</option>
								<option $rankselectE value=\"E\">-Engine-</option>
								<option $rankselectT value=\"T\">-Steward-</option>";
		
								
								while($rowranklist=mysql_fetch_array($qryranklist))
								{
									$rank1=$rowranklist['RANK'];
									$rankcode1=$rowranklist['RANKCODE'];
									
									$selectrank = "";
									if ($rankcode == $rankcode1)
										$selectrank = "SELECTED";
										
									echo "<option $selectrank value=\"$rankcode1\">$rank1</option>";
								}
					echo "
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>
							<input type=\"button\" value=\"Refresh List\" style=\"border:0;height:40px;background-color:Green;
											font-size:1em;font-weight:Bold;color:Yellow;border:thin solid white;cursor:pointer;\"
										 onclick=\"submit();\" />
						</td>
					</tr>
				</table>
				</center>
			</div>
			
		</div>

		<div style=\"width:100%;height:425px;\">
";
					
include("veritas/include/checklist.inc");

echo "			
			<table style=\"width:100%;background-color:#333333;\" class=\"listrow\">
				<tr>
					<th style=\"color:White;\">Applicant No:&nbsp;&nbsp;<span style=\"font-size:1.2em;font-weight:Bold;color:Lime;\">$applicantno</span></th>
					<th style=\"color:White;\">Name:&nbsp;&nbsp;<span style=\"font-size:1.2em;font-weight:Bold;color:Lime;\">$applicantname</span></th>
					<th style=\"color:White;\">Rank:&nbsp;&nbsp;<span style=\"font-size:1.2em;font-weight:Bold;color:Lime;\">$applicantrank</span></th>
				</tr>
			</table>
";
					
			if (mysql_num_rows($qrychecklist) > 0)
			{
				
				echo "
				<div style=\"width:100%;height:100%;overflow:auto;\">
					
					<table width=\"100%\" style=\"font-size:0.7em;background-color:#D0D2FF;border:1px solid black;overflow:auto;\">
						<tr style=\"font-weight:bold;background-color:Black;color:White;\">
							<td width=\"35%\" align=\"center\">COURSE</td>
							<td width=\"50%\" align=\"center\">STATUS</td>
							<td width=\"15%\" align=\"center\">ACTION</td>
						</tr>
						";
							$tmpgroup = "";
							while ($rowchecklist = mysql_fetch_array($qrychecklist))
							{
								$chkidno = $rowchecklist["IDNO"];
								$chktraincode = $rowchecklist["TRAINCODE"];
								$chktraining = $rowchecklist["TRAINING"];
								$chktrainalias = $rowchecklist["ALIAS"];
								$chkcoursetypecode = $rowchecklist["COURSETYPECODE"];
								$chkcoursetype = $rowchecklist["COURSETYPE"];
								$chkdoccode = $rowchecklist["DOCCODE"];
								
								if ($chkcoursetypecode != $tmpgroup)
								{
									$style = "style=\"background-color:Navy;color:Yellow;font-weight:Bold;font-size:1.2em;text-align:center;\"";
									
									echo "<tr><td colspan=\"3\" $style>$chkcoursetype</td></tr>";
									
								}
								
								$disabledenroll = 0;
								
								$statusshow = "";
								$onclick = "&nbsp;";
								$completed = "";
								$datefrom = "";
								$dateto = "";
								$certshow = "";
								$docshow = "";
								
								$wherepart = "";
								
								if ($chkcoursetypecode == "INHSE")
									$wherepart = "WHERE TYPE NOT IN ('OUT')";
								else 
									$wherepart = "WHERE TYPE NOT IN ('IN')";
								
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
												
													SELECT 'OUT' AS TYPE,cto.SCHEDID,th.DATEFROM,th.DATETO,th.STATUS AS TRAINSTATUS,cto.STATUS
													FROM crewtrainingothers cto
													LEFT JOIN trainingschedhdr th ON th.SCHEDID=cto.SCHEDID
													WHERE cto.APPLICANTNO=$applicantno AND th.TRAINCODE='$chktraincode'
												
													UNION
												
													SELECT 'OLD' AS TYPE,NULL,TRAINDATE,TRAINDATE,NULL,NULL
													FROM crewtrainingold ctold
													WHERE ctold.APPLICANTNO=$applicantno AND ctold.TRAINCODE='$chktraincode'
													
													UNION
													
													SELECT 'UPD' AS TYPE,NULL,TRAINDATE,TRAINDATE,NULL,1
													FROM crewtrainingnosched ctnosched
													WHERE ctnosched.APPLICANTNO=$applicantno AND ctnosched.TRAINCODE='$chktraincode'
												) x
												$wherepart
												ORDER BY DATEFROM DESC
											") or die(mysql_error());

								
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
									
									switch ($status2)
									{
										case "ENROLLED"	:
											
												$statusshow = "<span style=\"color:Yellow;background-color:Black;font-weight:Bold;\">" . "&nbsp;ENROLLED " . "</span><i>&nbsp;&nbsp;&nbsp;" . $datefrom . " to " . $dateto . "</i>";
												$disabledenroll = 2;
												
											break;
										case "FINISHED"	:
											
												$statusshow = "<span style=\"color:Red;background-color:Black;font-weight:Bold;\">" . "&nbsp;FINISHED " . "</span><i>&nbsp;&nbsp; Last&nbsp;"  .$dateto . "</i>&nbsp;<span style=\"color:White;background-color:Red;font-weight:Bold;\">&nbsp;$completed&nbsp;</span>";
												
												
											break;
										case "ON-GOING"	:
											
												$statusshow = "<span style=\"color:Lime;background-color:Black;font-weight:Bold;\">" . "&nbsp;ON-GOING " . "</span><i>&nbsp;&nbsp; Until&nbsp;" . $dateto . "</i>";
												$disabledenroll = 2;
											break;
									}
								}

								
								//CHECK IF DOCUMENT IS READY FOR VIEWING...
								
								$dirfilename1 = $basedirdocs . $applicantno . "/C/" . $chkdoccode . ".pdf";
								
								if (checkpath($dirfilename1))
								{
									$docshow = "<a href=\"#\" style=\"font-weight:Bold;color:Navy;\"
												onclick=\"openWindow('$dirfilename1', '$chkdoccode' ,700, 500);\" title=\"Click to View Document...\">
												[VIEW]</a>";
								}
								
								//CHECK IF THERE'S AN ENTRY IN CREWCERTSTATUS
//								$qrycheckcertstatus = mysql_query("SELECT * FROM crewcertstatus 
//														WHERE APPLICANTNO=$applicantno AND DOCCODE='$chkdoccode'
//														ORDER BY DATEISSUED DESC
//														LIMIT 1
//													") or die(mysql_error());
								$qrycheckcertstatus = mysql_query("SELECT if(x.APPLICANTNO IS NULL,'UPDATE','EXISTS') AS STATUS,cc.*
													FROM crewcertstatus cc
													LEFT JOIN (
														SELECT 'IN' AS TYPE,ct.APPLICANTNO,ct.SCHEDID,th.DATEFROM,th.DATETO,th.STATUS AS TRAINSTATUS,ct.STATUS
														FROM crewtraining ct
														LEFT JOIN trainingschedhdr th ON th.SCHEDID=ct.SCHEDID
														WHERE ct.APPLICANTNO=$applicantno AND th.TRAINCODE='$chktraincode'
														
														UNION
														
														SELECT 'OUT' AS TYPE,cto.APPLICANTNO,cto.SCHEDID,th.DATEFROM,th.DATETO,th.STATUS AS TRAINSTATUS,cto.STATUS
														FROM crewtrainingothers cto
														LEFT JOIN trainingschedhdr th ON th.SCHEDID=cto.SCHEDID
														WHERE cto.APPLICANTNO=$applicantno AND th.TRAINCODE='$chktraincode'
														
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
									
//										$statusshow = "ENTRY FOUND: ";

									if ($certstatus == "UPDATE" )
									{
										$certshow = "<a href=\"#\" style=\"font-weight:Bold;color:Green;\"
														onclick=\"
														applicantno.value = '$applicantno';
														traincodehidden.value = '$chktraincode';
														rankcodehidden.value = '$certrankcode';
														traindatehidden.value = '$certdateissued';
														gradehidden.value = '$certgrade';
														coursetypecodehidden.value = '$chkcoursetypecode';
														
														currentrankcodehidden.value = '$currentrankcode';
														actiontxt.value='updatetraining';submit();\" 
														title=\"Certificate Found! Click to update training history.\">
														[UPDATE TRAINING]</a>";
									}
									else 
									{
										$certshow = "";
									}
								}
								
								
								switch ($disabledenroll)
								{
									case "0"	:
										
											$onclick = "<a href=\"#\" onclick=\"openWindow('enrollment.php?traincode=$chktraincode&applicantno=$applicantno&type=1','enrollment' ,800, 565);\" 
													style=\"font-size:1em;font-weight:bold;color:#FF6600;\">[ENROLL]</a>";
										
										break;
										
									case "1"	:
										
//											$onclick = "<a href=\"#\" onclick=\"openWindow('enrollment.php?traincode=$chktraincode&applicantno=$applicantno&type=1','enrollment' ,800, 565);\" 
//													style=\"font-size:1em;font-weight:bold;color:#FF6600;\">[ENROLL]</a>";
										
										break;
										
									case "2"	:
										
											$onclick = "-------";
										
										break;
								}
								
								echo "
								<tr style=\"border:thin solid black;\">
									<td style=\"border:1px solid gray;font-weight:Bold;\">$chktraining</td>
									<td style=\"border:1px solid gray;font-weight:Bold;color:Black;\" align=\"left\">&nbsp;$statusshow&nbsp;$certshow&nbsp;$docshow</td>
									<td style=\"border:1px solid gray;\" align=\"center\">$onclick</td>
								</tr>
								";

								$tmpgroup = $chkcoursetypecode;
							}
				echo "
					</table>
				</div>
				";
			}
			else 
			{
				echo "
				<div style=\"width:100%;height:400px;overflow:auto;\">
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
	<input type=\"hidden\" name=\"currentrankcodehidden\" />
	
	<input type=\"hidden\" name=\"traincodehidden\" />
	<input type=\"hidden\" name=\"rankcodehidden\" />
	<input type=\"hidden\" name=\"traindatehidden\" />
	<input type=\"hidden\" name=\"gradehidden\" />
	<input type=\"hidden\" name=\"coursetypecodehidden\" />
</form>

</body>

</html>

";

?>