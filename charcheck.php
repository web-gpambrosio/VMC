<?php

include("veritas/connectdb.php");
include("veritas/include/stylephp.inc");
include("veritas/include/functions.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');

$poealisted = 0;
$vmclisted = 0;
$vmcfindings = "";
$poeafindings = "";

if(isset($_SESSION['employeeid']))
	$employeeid=$_SESSION['employeeid'];

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_POST['hasexperience']))
	$hasexperience = $_POST['hasexperience'];
else 
	$hasexperience = "";
	
if (isset($_POST['applicantno']))
{
	$applicantno = $_POST['applicantno'];
	
	if (isset($_POST['vmcfindings']))
		$vmcfindings = $_POST['vmcfindings'];
		
	if (isset($_POST['poeafindings']))
		$poeafindings = $_POST['poeafindings'];
		
	if (isset($_POST['vmclisted']))
		$vmclisted = $_POST['vmclisted'];
		
	if (isset($_POST['poealisted']))
		$poealisted = $_POST['poealisted'];
	
//	echo "poealisted = " . $poealisted . " / ";
//	echo "vmclisted = " . $vmclisted;
}

if (isset($_POST['rankcode']))
	$rankcode = $_POST['rankcode'];
else 
	$rankcode = "All";
	

	
if (isset($_POST['crewexpidno']))
	$crewexpidno = $_POST['crewexpidno'];
	
if (isset($_POST['remarks']))
	$remarks = $_POST['remarks'];
	
if (isset($_POST['interviewed']))
	$interviewed = $_POST['interviewed'];
	
if (isset($_POST['position']))
	$position = $_POST['position'];
	
if (isset($_POST['interviewdate']))
	$interviewdate = $_POST['interviewdate'];
	
$disabledone = "";
$encodedisabled = "disabled=\"disabled\"";
$encodedisabled1 = "";
	

if ($applicantno != "")
{
	//CHECK IF APPLICANT HAS EXPERIENCE (crewexperience entry)
	
	$qrycheckexperience = mysql_query("SELECT IDNO FROM crewexperience WHERE APPLICANTNO=$applicantno") or die(mysql_error());
	
	if (mysql_num_rows($qrycheckexperience) > 0)
		$hasexperience = 1;
	else 
		$hasexperience = 0;
		
	//END OF CHECKING
}

	switch ($actiontxt)
	{
		case "done"		:
			
				$qryverify = mysql_query("SELECT IDNO FROM applicantwatchhdr WHERE APPLICANTNO=$applicantno") or die(mysql_error());
				
				if (mysql_num_rows($qryverify) > 0)
				{
					//UPDATE

//				echo "UPDATE applicantwatchhdr SET VMCLISTED=$vmclisted,
//																				VMCFINDINGS='$vmcfindings',
//																				POEALISTED=$poealisted,
//																				POEAFINDINGS='$poeafindings',
//																				MADEBY='$employeeid',
//																				MADEDATE='$currentdate'
//														WHERE APPLICANTNO=$applicantno
//													";
					
					
					$qryappwatchupdate = mysql_query("UPDATE applicantwatchhdr SET VMCLISTED=$vmclisted,
																				VMCFINDINGS='$vmcfindings',
																				POEALISTED=$poealisted,
																				POEAFINDINGS='$poeafindings',
																				MADEBY='$employeeid',
																				MADEDATE='$currentdate'
														WHERE APPLICANTNO=$applicantno
													") or die(mysql_error());
					
				}
				else 
				{
					//INSERT
					
//				echo "INSERT INTO applicantwatchhdr(APPLICANTNO,VMCLISTED,VMCFINDINGS,
//														POEALISTED,POEAFINDINGS,MADEBY,MADEDATE) 
//														VALUES($applicantno,$vmclisted,'$vmcfindings',$poealisted,
//														'$poeafindings','$employeeid','$currentdate')
//										";
					
					$qryappwatchinsert = mysql_query("INSERT INTO applicantwatchhdr(APPLICANTNO,VMCLISTED,VMCFINDINGS,
														POEALISTED,POEAFINDINGS,MADEBY,MADEDATE) 
														VALUES($applicantno,$vmclisted,'$vmcfindings',$poealisted,
														'$poeafindings','$employeeid','$currentdate')
										") or die(mysql_error());
				}
				
				$qryapplicantupdate = mysql_query("UPDATE applicantstatus SET CHARCHECKBY='$employeeid',
																			CHARCHECKDATE='$currentdate'
														WHERE APPLICANTNO=$applicantno") or die(mysql_error());
				
				$vmcfindings = "";
				$poeafindings = "";
				$vmclisted = 0;
				$poealisted = 0;
				
				$crewexpidno = "";
				$remarks = "";
				$interviewed = "";
				$position = "";
				$interviewdate = "";
				
				$applicantno = "";
				$hasexperience = "";
				
			break;
		
		case "cancel"	:
			
				$crewexpidno = "";
				$remarks = "";
				$interviewed = "";
				$position = "";
				$interviewdate = "";
			
			break;
		
		case "reset"	:
			
				$vmcfindings = "";
				$poeafindings = "";
				$vmclisted = 0;
				$poealisted = 0;
				
				$crewexpidno = "";
				$remarks = "";
				$interviewed = "";
				$position = "";
				$interviewdate = "";
				
				$applicantno = "";
				$hasexperience = "";
			
			break;
		
		case "save"		:
			
				//SAVE HEADER
				
				$qryverify = mysql_query("SELECT IDNO FROM applicantwatchhdr WHERE APPLICANTNO=$applicantno") or die(mysql_error());
				
				if (mysql_num_rows($qryverify) > 0)
				{
					//UPDATE
					
					$qryappwatchupdate = mysql_query("UPDATE applicantwatchhdr SET VMCLISTED=$vmclisted,
																				VMCFINDINGS='$vmcfindings',
																				POEALISTED=$poealisted,
																				POEAFINDINGS='$poeafindings',
																				MADEBY='$employeeid',
																				MADEDATE='$currentdate'
														WHERE APPLICANTNO=$applicantno
													") or die(mysql_error());
					
				}
				else 
				{
					//INSERT
					
					$qryappwatchinsert = mysql_query("INSERT INTO applicantwatchhdr(APPLICANTNO,VMCLISTED,VMCFINDINGS,
														POEALISTED,POEAFINDINGS,MADEBY,MADEDATE) 
														VALUES($applicantno,$vmclisted,'$vmcfindings',$poealisted,
														'$poeafindings','$employeeid','$currentdate')
										") or die(mysql_error());
				}
				
				if ($hasexperience == 1 && $crewexpidno != "") //ONLY SAVE DETAIL IF APPLICANT HAS AN EXPERIENCE and $crewexpidno != ""
				{
					$qrygetidno = mysql_query("SELECT IDNO FROM applicantwatchhdr WHERE APPLICANTNO=$applicantno") or die(mysql_error());
					
					//SAVE DETAIL
					
					$rowgetidno = mysql_fetch_array($qrygetidno);
					$watchidno = $rowgetidno["IDNO"];
					
					$qrygetmanning = mysql_query("SELECT cx.MANNINGCODE,IF(cx.MANNINGCODE IS NULL OR cx.MANNINGCODE='',cx.MANNINGOTHERS,m.MANNING) AS MANNING
													FROM crewexperience cx
													LEFT JOIN manning m ON m.MANNINGCODE=cx.MANNINGCODE
													WHERE cx.IDNO=$crewexpidno
												") or die(mysql_error());
					
					$rowgetmanning = mysql_fetch_array($qrygetmanning);
					
					if ($rowgetmanning["MANNINGCODE"] != "")
						$manningcode1 = "'" . $rowgetmanning["MANNINGCODE"] . "'";
					else 
						$manningcode1 = 'NULL';
					
					if ($rowgetmanning["MANNING"] != "")
						$manning1 = "'" . $rowgetmanning["MANNING"] . "'";
					else 
						$manning1 ='NULL';
						
					$interviewdateraw = date("Y-m-d",strtotime($interviewdate));
					
					$qryappwatchdtlinsert = mysql_query("INSERT INTO applicantwatchdtl(IDNO,MANNINGCODE,MANNINGOTHERS,REMARKS,
														INTERVIEWED,POSITION,INTERVIEWBY,INTERVIEWDATE,MADEBY,MADEDATE) 
														VALUES($watchidno,$manningcode1,$manning1,
														'$remarks','$interviewed','$position','$employeeid','$interviewdateraw','$employeeid','$currentdate')
														") or die(mysql_error());
					
					$remarks = "";
					$interviewed = "";
					$position = "";
					$interviewdate = "";
				}
				
			break;
	}


if (!empty($applicantno))
{
	$qrycheckappstatus = mysql_query("SELECT CHARCHECKBY,CHARCHECKDATE FROM applicantstatus WHERE APPLICANTNO=$applicantno AND STATUS=1") or die(mysql_error());
	
	$rowcheckappstatus = mysql_fetch_array($qrycheckappstatus);
	$charcheckby = $rowcheckappstatus["CHARCHECKBY"];
	$charcheckdate = $rowcheckappstatus["CHARCHECKDATE"];
	
	if (!empty($charcheckdate))
	{
		$disabledone = "disabled=\"disabled\"";
		$errormsg = "Character Checked Already. You cannot save.";
	}
	else 
	{
		$disabledone = "";
		$errormsg = "";
	}
	
	if (mysql_num_rows($qrycheckappstatus) > 0)
	{
		if ($hasexperience == 1 && $charcheckdate == "")
		{
			$encodedisabled = "";
			
			$qrymanning = mysql_query("SELECT cx.IDNO,IF(cx.MANNINGCODE<>'',left(m.MANNING,20),left(cx.MANNINGOTHERS,20)) AS MANNING,cx.RANKCODE,r.ALIAS1
									FROM crewexperience cx
									LEFT JOIN manning m ON m.MANNINGCODE=cx.MANNINGCODE
									LEFT JOIN rank r ON r.RANKCODE=cx.RANKCODE
									where APPLICANTNO = $applicantno
									GROUP BY cx.MANNINGCODE,cx.MANNINGOTHERS
									") or die(mysql_error());
			
			$empselect = "<option selected value=\"\">--Select One--</option>";
			
			while($rowmanning=mysql_fetch_array($qrymanning))
			{
				$expidno = $rowmanning["IDNO"];
//				$manningcode = $rowmanning["MANNINGCODE"];
				$manning = $rowmanning["MANNING"];
//				$expmanningothers = $rowmanning["MANNINGOTHERS"];
				$exprankcode = $rowmanning["RANKCODE"];
				$exprankalias = $rowmanning["ALIAS1"];
				
				$selected = "";
				
				if ($crewexpidno == $expidno)
					$selected = "SELECTED";
					
				$empselect .= "<option $selected value=\"$expidno\">$manning - [$exprankalias]</option>";
			}
			
			
			//FOR DISPLAY
						
			$qryheader = mysql_query("SELECT cx.IDNO,cx.APPLICANTNO,cx.MANNINGCODE,cx.RANKCODE,r1.RANK AS EXPRANK,r.RANK AS VMCRANK,
											IF(cx.MANNINGCODE IS NOT NULL,m.MANNING,cx.MANNINGOTHERS) AS MANNING,cx.DATEDISEMB,
											CONCAT(FNAME,', ',GNAME,' ' ,MNAME) AS NAME
											FROM crewexperience cx
											LEFT JOIN crew c ON c.APPLICANTNO=cx.APPLICANTNO
											LEFT JOIN manning m ON m.MANNINGCODE=cx.MANNINGCODE
											LEFT JOIN applicantstatus ap ON ap.APPLICANTNO=cx.APPLICANTNO
											LEFT JOIN rank r ON r.RANKCODE=ap.VMCRANKCODE
											LEFT JOIN rank r1 ON r1.RANKCODE=cx.RANKCODE
											WHERE cx.APPLICANTNO=$applicantno AND ap.ENDORSEDDATE IS NOT NULL AND ap.CHARCHECKDATE IS NULL
											AND ap.STATUS=1
											AND cx.DATEDISEMB IN (
												SELECT MAX(DATEDISEMB) FROM crewexperience WHERE APPLICANTNO=$applicantno
											)") or die(mysql_error());	
			
			$rowheader = mysql_fetch_array($qryheader);
			
			$applicant = $rowheader["NAME"];
			$watchlistdate = date('m/d/Y',strtotime($currentdate));
			$apprank = $rowheader["EXPRANK"];
			$apprank1 = $rowheader["VMCRANK"];
			$lastemployed = $rowheader["MANNING"];
			$datedisemb = $rowheader["DATEDISEMB"];
		
		}
		elseif ($hasexperience == 0 && $charcheckdate == "") //$hasexperience == 0 (HAS NO EXPERIENCE)
		{
			$encodedisabled1 = "disabled=\"disabled\"";
			$encodedisabled = "";
			
			$qrynoexperience = mysql_query("SELECT ap.APPLICANTNO,CONCAT(c.FNAME,', ',GNAME,' ',MNAME) AS NAME,
											ap.VMCRANKCODE,r.RANK
											FROM applicantstatus ap
											LEFT JOIN crew c ON c.APPLICANTNO=ap.APPLICANTNO
											LEFT JOIN rank r ON r.RANKCODE=ap.VMCRANKCODE
											WHERE ap.ENDORSEDDATE IS NOT NULL AND ap.STATUS=1
											AND ap.APPLICANTNO=$applicantno
										") or die(mysql_error());

			$rownoexperience = mysql_fetch_array($qrynoexperience);
				
			$applicant = $rownoexperience["NAME"];
			$watchlistdate = date('m/d/Y',strtotime($currentdate));
			$apprank = $rownoexperience["RANK"];
			$lastemployed = "[NO EXPERIENCE]";
			$datedisemb = "";
		}
	}
	else 
	{
		$errormsg = "Applicant No. $applicantno, NOT found in Applicant Table or NOT endorsed yet.";
		$encodedisabled = "disabled=\"disabled\"";
	}
	
	
	$vmc_id = 0;
	$poea_id = 0;
	
	$listed = checkwatchlist($applicantno,$vmc_id,$poea_id);
	
//	echo "Listed = " . $listed;
	
	if ($listed == 1 || $listed == 3)
	{
		$qryvmcwatch = mysql_query("SELECT REMARKS,SECONDCHANCE FROM watchlist_veritas WHERE IDNO=$vmc_id ") or die(mysql_error());	
		$rowvmcwatch = mysql_fetch_array($qryvmcwatch);
		
		$vmcfindings = $rowvmcwatch["REMARKS"];
		$vmcsecondchance = $rowvmcwatch["SECONDCHANCE"];
		
		if ($vmcsecondchance == 0)
			$vmclisted = 1;
		else 
			$vmclisted = 0;
	}
	
	if ($listed == 2 || $listed == 3)
	{
		$qrypoeawatch = mysql_query("SELECT REMARKS,SECONDCHANCE FROM watchlist_poea WHERE IDNO=$poea_id ") or die(mysql_error());	
		$rowpoeawatch = mysql_fetch_array($qrypoeawatch);
		
		$poeafindings = $rowpoeawatch["REMARKS"];
		$poeasecondchance = $rowpoeawatch["SECONDCHANCE"];
		
		if ($poeasecondchance == 0)
			$poealisted = 1;
		else 
			$poealisted = 0;
	}
	
	if ($listed == 0)
	{
		$vmclisted = 0;
		$poealisted = 0;
	}
		
	$qryappsearchhdr = mysql_query("SELECT *
								FROM applicantwatchhdr awh
								WHERE awh.APPLICANTNO=$applicantno 
								AND NOTEDDATE IS NULL
							") or die(mysql_error());
	
	if (mysql_num_rows($qryappsearchhdr) == 1) 
	{
		$rowappsearchhdr = mysql_fetch_array($qryappsearchhdr);
		
		$idno = $rowappsearchhdr["IDNO"];
		$vmcwatchlist1 = $rowappsearchhdr["VMCLISTED"];
		$poeawatchlist1 = $rowappsearchhdr["POEALISTED"];
		
		if ($listed == 0)
		{
			$vmclisted = $vmcwatchlist1;
			$poealisted = $poeawatchlist1;
		}
			
		if (!empty($rowappsearchhdr["VMCFINDINGS"]))
			$vmcfindings = $rowappsearchhdr["VMCFINDINGS"];
			
		if (!empty($rowappsearchhdr["POEAFINDINGS"]))
			$poeafindings = $rowappsearchhdr["POEAFINDINGS"];
		
		$watchlistdate = date('m/d/Y',strtotime($rowappsearchhdr["MADEDATE"]));
		
		
		// GET DETAIL
		
		$qryappsearchdtl = mysql_query("SELECT awd.REMARKS,IF(awd.MANNINGCODE IS NULL,awd.MANNINGOTHERS,m.MANNING) AS MANNING
										FROM applicantwatchdtl awd
										LEFT JOIN manning m ON m.MANNINGCODE=awd.MANNINGCODE
										WHERE IDNO=$idno
									") or die(mysql_error());
		
	}
	
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

$qryapplicants = mysql_query("SELECT ap.APPLICANTNO,CONCAT(c.FNAME,', ',GNAME,' ',MNAME) AS NAME,
						r.RANKCODE,r.RANK,r.ALIAS1,r.RANKLEVELCODE,r.RANKTYPECODE
						FROM applicantstatus ap
						LEFT JOIN crew c ON c.APPLICANTNO=ap.APPLICANTNO
						LEFT JOIN rank r ON r.RANKCODE=ap.VMCRANKCODE
						LEFT JOIN ranklevel rl ON rl.RANKLEVELCODE=r.RANKLEVELCODE
						LEFT JOIN ranktype rt ON rt.RANKTYPECODE=r.RANKTYPECODE
						LEFT JOIN applicantwatchhdr awh ON awh.APPLICANTNO=ap.APPLICANTNO
						WHERE ap.ENDORSEDDATE IS NOT NULL AND ap.CHARCHECKDATE IS NULL AND ap.STATUS=1
						AND awh.NOTEDDATE IS NULL
						$whererank
						ORDER BY r.RANKLEVELCODE,r.RANKTYPECODE,r.RANK,NAME") or die(mysql_error());

echo "
<html>\n
<head>\n
<title>
Watchlist/Background Check
</title>

<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<link rel=\"stylesheet\" type=\"text/css\" href=\"veripro_print.css\">
<script language=\"javascript\" src=\"popcalendar.js\"></script>	
<script type='text/javascript' src='veripro.js'></script>
</head>
<body onload=\"\" style=\"overflow:hidden;\">\n

<form name=\"backgroundcheck\" method=\"POST\">\n

<span class=\"wintitle\">WATCHLIST / BACKGROUND CHECK</span>

	<div style=\"width:100%;border-bottom:1px solid black;\">
	
		<div style=\"width:25%;height:650px;float:left;background-color:#999AFF;overflow:auto;\">
			
			<div style=\"width:100%;height:500px;float:left;overflow:auto;\">
			
				<span class=\"sectiontitle\">LIST OF APPLICANTS</span>
				<br />
				<table width=\"100%\" class=\"listcol\">
					<tr>
						<th>NAME</th>
					</tr>
				";
				
				$tmprank = "";
	
				while ($rowapplicants=mysql_fetch_array($qryapplicants))
				{
					$applicantno1 = $rowapplicants["APPLICANTNO"];
					$name = $rowapplicants["NAME"];
					$rank = $rowapplicants["RANK"];
					$rankcode1 = $rowapplicants["RANKCODE"];
					$alias1 = $rowapplicants["ALIAS1"];
					
					if ($tmprank != $rank)
					{
						echo "
						<tr>
							<td align=\"left\" style=\"font-size:1em;font-weight:bold;color:Blue;\"><i>$rank</i></td>
						</tr>
						";
					}
					
					echo "
					<tr $mouseovereffect ondblclick=\"vmcfindings.value='';poeafindings.value='';applicantno.value='$applicantno1';submit();\" style=\"cursor:pointer;\">
						<td>$name</td>
					
					</tr>
					";
					
					$tmprank = $rank;
				}
	
	echo "
				</table>
				
				
			</div>
		
			<div style=\"width:100%;height:150px;float:left;overflow:auto;background-color:#CCCCFF;\">
			
				<span class=\"sectiontitle\">SEARCH CRITERIA</span>
				
				<center>
				<table width=\"100%\" style=\"margin-top:5px;\">
					<tr>
						<td>
							<span style=\"font-size:0.8em;font-weight:Bold;\">Rank List</span>
							
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
							</select>
						</td>
					</tr>

					<tr>
						<td>
							<center>
							<input type=\"button\" value=\"Refresh List\" style=\"border:0;height:30px;background-color:Green;
											font-size:1em;font-weight:Bold;color:Yellow;border:thin solid white;cursor:pointer;\"
										 onclick=\"vmcfindings.value='';poeafindings.value='';applicantno.value='';submit();\" />
							</center>
						</td>
					</tr>
				</table>
				</center>

			</div>
		
		</div>
		
		<div style=\"width:75%;height:650px;float:left;background-color:Silver;\">

			<div style=\"width:100%;height:120px;background-color:Black;overflow:hidden;padding:5px;\" > 
		";
			$styledata = " style=\"font-size:1em;font-weight:bold;color:White;\"";	
		echo "
				<table width=\"100%\" style=\"font-size:0.8em;font-weight:bold;color:Orange;padding:2px;\" border=1>
					<tr>
						<td width=\"10%\">NAME</td>
						<td width=\"40%\" $styledata>&nbsp;$applicant</td>
						<td width=\"20%\">DATE</td>
						<td width=\"30%\" $styledata>&nbsp;$watchlistdate</td>
					</tr>
					<tr>
						<td>RANK</td>
						<td $styledata>&nbsp;$apprank</td>
						<td>LAST EMPLOYMENT</td>
						<td $styledata>&nbsp;$lastemployed</td>
					</tr>
					<tr>
						<td colspan=\"2\" valign=\"middle\">
					<!--		Search Applicant No.&nbsp;&nbsp;&nbsp;
							<input type=\"text\" name=\"searchkey\" />
							<input type=\"button\" value=\"Search\" onclick=\"if(searchkey.value != '')
											{applicantno.value=searchkey.value;submit();}
											else {alert('Invalid Applicant No. Please try again.');}\" />
					-->
						</td>
						<td align=\"center\" style=\"font-size:1.8em;font-weight:bold;color:Red;\">&nbsp;$applicantno</td>
						<td align=\"center\" style=\"font-size:1em;font-weight:bold;color:White;\">&nbsp;$errormsg</td>
					</tr>
				</table>
			
			</div>
			
			<div style=\"width:100%;height:530px;background-color:White;overflow:auto;padding:10px;\" > 
			
				<span class=\"sectiontitle\">Watchlist</span>
				<br />
				
				<center>
				<span style=\"color:Blue;font-size:0.8em;font-weight:Bold;\"><i>NOTE: You cannot edit Watchlist Findings. Please advise MIS if findings are proven otherwise.</i></span>
				<table width=\"80%\" border=1>
					<tr>
						<td>&nbsp;</td>
						<td align=\"center\" style=\"font-size:0.8em;font-weight:Bold;\">Listed</td>
						<td align=\"center\" style=\"font-size:0.8em;font-weight:Bold;\">Findings</td>
					</tr>
					<tr>
						<td widtd=\"10%\" valign=\"middle\" align=\"center\" style=\"font-size:0.8em;font-weight:Bold;\">VERITAS</td>
						<td widtd=\"20%\" valign=\"middle\" style=\"font-size:0.9em;font-weight:Bold;color:Red;text-align:center;\">
						";
							if ($vmclisted == 1)
								echo "YES";
							else 
								echo "NO";
						echo "
						</td>
						<td widtd=\"70%\" style=\"font-size:0.8em;font-weight:Bold;\">
							<center>
							<textarea rows=\"2\" cols=\"40\" name=\"vmcfindings\" readonly=\"readonly\">$vmcfindings</textarea>
							</center>
						</td>
					</tr>
					<tr>
						<td widtd=\"10%\" valign=\"middle\" align=\"center\" style=\"font-size:0.8em;font-weight:Bold;\">POEA</td>
						<td widtd=\"20%\" valign=\"middle\" style=\"font-size:0.9em;font-weight:Bold;color:Red;text-align:center;\">
						";
							if ($poealisted == 1)
								echo "YES";
							else 
								echo "NO";
						echo "
						</td>
						<td widtd=\"70%\" style=\"font-size:0.8em;font-weight:Bold;\">
							<center>
							<textarea rows=\"2\" cols=\"40\" name=\"poeafindings\" readonly=\"readonly\">$poeafindings</textarea>
							</center>
						</td>
					</tr>
				</table>
				</center>
				
				<br />
				
				<span class=\"sectiontitle\">Character Check on Previous Employers</span>
				<br />
				
				<span style=\"color:Blue;font-size:0.8em;font-weight:Bold;\"><i>NOTE: If Applicant has no previous experience. Please click DONE button.</i></span>
				<div style=\"width:400px;float:left;background-color:White;\">
				
					<div style=\"border:1px solid black;\">
						<table width=\"90%\" class=\"listrow\">
							<tr>
								<th width=\"20%\">Employer</th>
								<th width=\"5%\">:</th>
								<td width=\"75%\">
									<select name=\"crewexpidno\" $encodedisabled $encodedisabled1 $disabledone>
										$empselect
									</select>
								</td>
							</tr>
							<tr>
								<th>Remarks</th>
								<th>:</th>
								<td><textarea rows=\"4\" cols=\"30\" name=\"remarks\" $encodedisabled $encodedisabled1 $disabledone >$remarks</textarea></td>
							</tr>
							<tr>
								<th>Person Interviewed</th>
								<th>:</th>
								<td><input type=\"text\" name=\"interviewed\" $encodedisabled $encodedisabled1 $disabledone value=\"$interviewed\" /></td>
							</tr>
							<tr>
								<th>Position</th>
								<th>:</th>
								<td><input type=\"text\" name=\"position\" $encodedisabled $encodedisabled1 $disabledone value=\"$position\" /></td>
							</tr>
							<tr>
								<th>Date</th>
								<th>:</th>
								<td>
									<input type=\"text\" name=\"interviewdate\" $encodedisabled $encodedisabled1 $disabledone value=\"$interviewdate\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
									<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(interviewdate, interviewdate, 'mm/dd/yyyy', 0, 0);return false;\">
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>
									<input type=\"button\" value=\"Save\" $encodedisabled $disabledone 
										onclick=\"if (crewexpidno.value != '' && remarks.value != ''){poealisted.value='$poealisted';vmclisted.value='$vmclisted';actiontxt.value='save';submit();}
													else {alert('Invalid fields. Please check.');}\" />
									<input type=\"button\" value=\"Cancel\" $encodedisabled $disabledone onclick=\"actiontxt.value='cancel';submit();\" />
								</td>
							</tr>
						</table>
					</div>

				</div>
				
				<div style=\"width:350px;height:200px;float:left;background-color:#DCDCDC;\">
				
					<table class=\"listcol\" width=\"100%\">
						<tr>
							<th>EMPLOYER</th>
							<th>REMARKS</th>
						</tr>
				";
					while ($rowappsearchdtl = mysql_fetch_array($qryappsearchdtl))
					{
						$list1 = $rowappsearchdtl["REMARKS"];
						$list2 = $rowappsearchdtl["MANNING"];
						
						echo "
						<tr>
							<td>$list2</td>
							<td>$list1</td>
						</tr>
						";
					}
					
				echo "	
					</table>
				
				</div>
				
				<br />
				<input type=\"button\" value=\"Done\" $encodedisabled $disabledone style=\"border:1px solid Black;background-color:Red;color:Yellow;font-weight:Bold;\"
						onclick=\"if(confirm('This is to confirm that Background/Character Check has been made for Applicant No. {$applicantno} -- {$applicant}. Continue?')) 
											 {poealisted.value='$poealisted';vmclisted.value='$vmclisted';actiontxt.value='done';submit();}\" />
				<input type=\"button\" value=\"Reset\" $encodedisabled $disabledone style=\"border:1px solid Black;background-color:Red;color:Yellow;font-weight:Bold;\"
						onclick=\"actiontxt.value='reset';submit();\" />
				<input type=\"button\" value=\"Print\" $encodedisabled $disabledone style=\"border:1px solid Black;background-color:Red;color:Yellow;font-weight:Bold;\"
						onclick=\"openWindow('repcharcheck.php?applicantno=$applicantno&print=1', 'debriefingfleet' ,0, 0);\" />
			</div>
			
		</div>
		
		
	</div>

	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"applicantno\" value=\"$applicantno\" />
	<input type=\"hidden\" name=\"hasexperience\" />
	<input type=\"hidden\" name=\"poealisted\" />
	<input type=\"hidden\" name=\"vmclisted\" />

</form>
</body>

</html>

";