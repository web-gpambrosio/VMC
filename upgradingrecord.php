<?php

include("veritas/connectdb.php");
include("veritas/include/stylephp.inc");
include("veritas/include/functions.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');
$datenow = date('d F Y');
$basedir = "idpics/";
$basedirdocs = "docimages/";

if(isset($_SESSION["employeeid"]))
	$employeeid=$_SESSION["employeeid"];

if (isset($_POST["actiontxt"]))
	$actiontxt = $_POST["actiontxt"];
	
if (isset($_GET["applicantno"]))
	$applicantno = $_GET["applicantno"];
else 
	$applicantno = $_POST["applicantno"];

if (isset($_POST["idno"]))
	$idno = $_POST["idno"];
else
	$idno = "";

if (isset($_POST["rankcodefrom"]))
	$rankcodefrom = $_POST["rankcodefrom"];

if (isset($_POST["rankcodeto"]))
	$rankcodeto = $_POST["rankcodeto"];

if (isset($_POST["module"]))
	$module = $_POST["module"];

if (isset($_POST["schedfrom"]))
	$schedfrom = $_POST["schedfrom"];

if (isset($_POST["schedto"]))
	$schedto = $_POST["schedto"];

if (isset($_POST["attendance"]))
	$attendance = $_POST["attendance"];

if (isset($_POST["attitude"]))
	$attitude = $_POST["attitude"];

if (isset($_POST["participation"]))
	$participation = $_POST["participation"];

if (isset($_POST["communication"]))
	$communication = $_POST["communication"];

if (isset($_POST["dailyquizzes"]))
	$dailyquizzes = $_POST["dailyquizzes"];

if (isset($_POST["handson"]))
	$handson = $_POST["handson"];

if (isset($_POST["finaltest"]))
	$finaltest = $_POST["finaltest"];

if (isset($_POST["totaverage"]))
	$totaverage = $_POST["totaverage"];

if (isset($_POST["ga_ability"]))
	$ga_ability = $_POST["ga_ability"];

if (isset($_POST["ga_abilityrem"]))
	$ga_abilityrem = $_POST["ga_abilityrem"];

if (isset($_POST["ga_attitude"]))
	$ga_attitude = $_POST["ga_attitude"];

if (isset($_POST["ga_attituderem"]))
	$ga_attituderem = $_POST["ga_attituderem"];

if (isset($_POST["ga_interest"]))
	$ga_interest = $_POST["ga_interest"];

if (isset($_POST["ga_interestrem"]))
	$ga_interestrem = $_POST["ga_interestrem"];

if (isset($_POST["ga_progress"]))
	$ga_progress = $_POST["ga_progress"];

if (isset($_POST["ga_progressrem"]))
	$ga_progressrem = $_POST["ga_progressrem"];

if (isset($_POST["recommendation"]))
	$recommendation = $_POST["recommendation"];

if (isset($_POST["trainingmngr"]))
	$trainingmngr = $_POST["trainingmngr"];
else
	$trainingmngr = "CAPT. LEXINGTON S. CALUMPANG";

if (isset($_POST["trainingofficer"]))
	$trainingofficer = $_POST["trainingofficer"];
else
	$trainingofficer = "3/O RONALD R. DE LUNA";
	

if (isset($_POST["selidno"]))
	$selidno = $_POST["selidno"];
	
if (isset($_POST["delsubj"]))
	$delsubj = $_POST["delsubj"];

if (isset($_POST["subject"]))
	$subject = $_POST["subject"];

$wheredate = "WHERE x.DATEEMB <= CURRENT_DATE";

if (empty($idno))
{
	$qrygetupgrades = mysql_query("SELECT IDNO,MADEBY,MADEDATE FROM upgradingrecordhdr WHERE APPLICANTNO=$applicantno") or die(mysql_error());

	$upgradeselect = "";
	while ($rowgetupgrades = mysql_fetch_array($qrygetupgrades))
	{
		$xidno = $rowgetupgrades["IDNO"];
		$xmadeby = $rowgetupgrades["MADEBY"];
		if (!empty($rowgetupgrades["MADEDATE"]))
			$xmadedate = date("dMY",strtotime($rowgetupgrades["MADEDATE"]));
		else
			$xmadedate = "";
		
		$selected = "";
		if ($xidno == $idno)
			$selected = "SELECTED";
			
		$upgradeselect .= "<option $selected value=\"$xidno\">$xmadedate/$xmadeby</option>";
	}
}
	
switch ($actiontxt)
{
	case "create":
		
		$qryinsert = mysql_query("INSERT INTO upgradingrecordhdr(APPLICANTNO,MADEBY,MADEDATE) VALUES($applicantno,'$employeeid','$currentdate')") or die(mysql_error());
		
		$qrygetidno = mysql_query("SELECT MAX(IDNO) AS IDNO FROM upgradingrecordhdr WHERE APPLICANTNO = $applicantno") or die(mysql_error());
		$rowgetidno = mysql_fetch_array($qrygetidno);
		
		$idno = $rowgetidno["IDNO"];
	break;
	case "addsubject":
	
		$qrysubjinsert = mysql_query("INSERT INTO upgradingrecorddtl VALUES($idno,'$subject','$employeeid','$currentdate')") or die(mysql_error());
	
	break;
	case "deletesubject":
	
		$qrysubjdelete = mysql_query("DELETE FROM upgradingrecorddtl WHERE IDNO=$idno AND SUBJECTS='$delsubj'") or die(mysql_error());
	
	break;
	case "save":
			
		if (!empty($schedfrom))
			$schedfromraw = "'" . date("Y-m-d",strtotime($schedfrom)) . "'";
		else
			$schedfromraw = "NULL";
			
		if (!empty($schedto))
			$schedtoraw = "'" . date("Y-m-d",strtotime($schedto)) . "'";
		else
			$schedtoraw = "NULL";
			
		if (!empty($attendance))
			$attendanceraw = $attendance;
		else
			$attendanceraw = "NULL";
			
		if (!empty($attitude))
			$attituderaw = $attitude;
		else
			$attituderaw = "NULL";
			
		if (!empty($participation))
			$participationraw = $participation;
		else
			$participationraw = "NULL";
			
		if (!empty($communication))
			$communicationraw = $communication;
		else
			$communicationraw = "NULL";
			
		if (!empty($dailyquizzes))
			$dailyquizzesraw = $dailyquizzes;
		else
			$dailyquizzesraw = "NULL";
			
		if (!empty($handson))
			$handsonraw = $handson;
		else
			$handsonraw = "NULL";
			
		if (!empty($finaltest))
			$finaltestraw = $finaltest;
		else
			$finaltestraw = "NULL";
			
		if (!empty($totaverage))
			$totaverageraw = $totaverage;
		else
			$totaverageraw = "NULL";
			
		if (!empty($ga_ability))
			$ga_abilityraw = $ga_ability;
		else
			$ga_abilityraw = "NULL";
			
		if (!empty($ga_attitude))
			$ga_attituderaw = $ga_attitude;
		else
			$ga_attituderaw = "NULL";
			
		if (!empty($ga_interest))
			$ga_interestraw = $ga_interest;
		else
			$ga_interestraw = "NULL";
			
		if (!empty($ga_progress))
			$ga_progressraw = $ga_progress;
		else
			$ga_progressraw = "NULL";
	
		$qryupdate = mysql_query("UPDATE upgradingrecordhdr SET 
										RANKCODEFROM = '$rankcodefrom',
										RANKCODETO = '$rankcodeto',
										DATEFROM = $schedfromraw,
										DATETO = $schedtoraw,
										MODULE = '$module',
										ATTENDANCE = $attendanceraw,
										ATTITUDE = $attituderaw,
										PARTICIPATION = $participationraw,
										COMMUNICATION = $communicationraw,
										DAILYQUIZZES = $dailyquizzesraw,
										HANDSON = $handsonraw,
										FINALTEST = $finaltestraw,
										TOTAVERAGE = $totaverageraw,
										GA_ABILITY = $ga_abilityraw,
										GA_ABILITYREM = '$ga_abilityrem',
										GA_ATTITUDE = $ga_attituderaw,
										GA_ATTITUDEREM = '$ga_attituderem',
										GA_INTEREST = $ga_interestraw,
										GA_INTERESTREM = '$ga_interestrem',
										GA_PROGRESS = $ga_progressraw,
										GA_PROGRESSREM = '$ga_progressrem',
										RECOMMENDATION = '$recommendation',
										TRAININGOFFICER = '$trainingofficer',
										TRAININGMNGR = '$trainingmngr'
							WHERE IDNO=$idno
						") or die(mysql_error());
	
	break;
}

if (!empty($selidno))
{
		$qrygetupgrading = mysql_query("SELECT * FROM upgradingrecordhdr WHERE IDNO=$selidno") or die(mysql_error());
		
		$rowgetupgrading = mysql_fetch_array($qrygetupgrading);
		
		$rankcodefrom = $rowgetupgrading["RANKCODEFROM"];
		$rankcodeto = $rowgetupgrading["RANKCODETO"];
		if (!empty($rowgetupgrading["DATEFROM"]))
			$schedfrom = date("m/d/Y",strtotime($rowgetupgrading["DATEFROM"]));
		else
			$schedfrom = "";
		if (!empty($rowgetupgrading["DATETO"]))
			$schedto = date("m/d/Y",strtotime($rowgetupgrading["DATETO"]));
		else
			$schedto = "";
		$module = $rowgetupgrading["MODULE"];
		$attendance = $rowgetupgrading["ATTENDANCE"];
		$attitude = $rowgetupgrading["ATTITUDE"];
		$participation = $rowgetupgrading["PARTICIPATION"];
		$communication = $rowgetupgrading["COMMUNICATION"];
		$dailyquizzes = $rowgetupgrading["DAILYQUIZZES"];
		$handson = $rowgetupgrading["HANDSON"];
		$finaltest = $rowgetupgrading["FINALTEST"];
		$totaverage = $rowgetupgrading["TOTAVERAGE"];
		$ga_ability = $rowgetupgrading["GA_ABILITY"];
		$ga_abilityrem = $rowgetupgrading["GA_ABILITYREM"];
		$ga_attitude = $rowgetupgrading["GA_ATTITUDE"];
		$ga_attituderem = $rowgetupgrading["GA_ATTITUDEREM"];
		$ga_interest = $rowgetupgrading["GA_INTEREST"];
		$ga_interestrem = $rowgetupgrading["GA_INTERESTREM"];
		$ga_progress = $rowgetupgrading["GA_PROGRESS"];
		$ga_progressrem = $rowgetupgrading["GA_PROGRESSREM"];
		$recommendation = $rowgetupgrading["RECOMMENDATION"];
		$trainingofficer = $rowgetupgrading["TRAININGOFFICER"];
		$trainingmngr = $rowgetupgrading["TRAININGMNGR"];
		if (!empty($rowgetupgrading["MADEDATE"]))
		{
			$madedate = "'" .date("Y-m-d H:i:s",strtotime($rowgetupgrading["MADEDATE"])) . "'";
			$madedate2 = date("d F Y",strtotime($rowgetupgrading["MADEDATE"]));
			
			$wheredate = "WHERE x.DATEEMB <= " . $madedate;
		}
		else
		{
			$madedate = "";
		}
		$madeby = $rowgetupgrading["MADEBY"];
		
		$idno = $selidno;
}
	
	
$qrygetcrew = mysql_query("SELECT FNAME,GNAME,LEFT(MNAME,1) AS MNAME FROM crew WHERE APPLICANTNO=$applicantno") or die(mysql_error());
	
if (mysql_num_rows($qrygetcrew) > 0)
{
	$rowgetcrew = mysql_fetch_array($qrygetcrew);
	$fname = $rowgetcrew["FNAME"];
	$gname = $rowgetcrew["GNAME"];
	$mname = $rowgetcrew["MNAME"];
	
	$crewname = $fname . ", " . $gname . " " . $mname . ".";
}

if (!empty($idno))
{
	
	$qryexperience = mysql_query("SELECT * FROM
									(
										SELECT '1' AS POS,cc.CCID,'Veritas' AS COMPANY,v.MANAGEMENTCODE,LEFT(v.ALIAS2,10) AS VESSEL,v.VESSEL AS VESSELNAME,v.VESSELCODE,r.ALIAS1 AS RANKALIAS,v.GROSSTON,vs.ENGINEMAIN AS ENGINE,
										vt.VESSELTYPECODE,v.TRADEROUTECODE,vs.ENGINEPOWER,rl.RANKLEVEL,
										cc.DATEEMB,
										IF(IF(DATECHANGEDISEMB IS NULL,DATEDISEMB,DATECHANGEDISEMB) > CURRENT_DATE,CURRENT_DATE,IF(DATECHANGEDISEMB IS NULL,DATEDISEMB,DATECHANGEDISEMB)) AS DATEDISEMB,
										cc.DISEMBREASONCODE,dr.REASON AS DISEMBREASON,
										vt.VESSELTYPE,tr.TRADEROUTE,cc.DEPMNLDATE,cc.ARRMNLDATE,cp1.CCID AS CHKPROMOTE,NULL AS MANNING,cp.CCID AS CCID2
										FROM crewchange cc
										LEFT JOIN vessel v ON v.VESSELCODE=cc.VESSELCODE
										LEFT JOIN rank r ON r.RANKCODE=cc.RANKCODE
										LEFT JOIN ranklevel rl ON rl.RANKLEVELCODE=r.RANKLEVELCODE
										LEFT JOIN vesselspecs vs ON vs.VESSELCODE=v.VESSELCODE
										LEFT JOIN vesseltype vt ON vt.VESSELTYPECODE=v.VESSELTYPECODE
										LEFT JOIN vesselsize vz ON vz.VESSELSIZECODE=v.VESSELSIZECODE
										LEFT JOIN disembarkreason dr ON dr.DISEMBREASONCODE=cc.DISEMBREASONCODE
										LEFT JOIN traderoute tr ON tr.TRADEROUTECODE=v.TRADEROUTECODE
										LEFT JOIN crewpromotionrelation cp1 ON cc.CCID=cp1.CCIDPROMOTE
										LEFT JOIN crewpromotionrelation cp ON cc.CCID=cp.CCID
										WHERE cc.DATEEMB <= CURRENT_DATE AND cc.APPLICANTNO=$applicantno
										
										UNION
										
										SELECT '2' AS POS,NULL AS CCID,IF (ce.MANNINGCODE = '',LEFT(ce.MANNINGOTHERS,10),LEFT(m.MANNING,10)) AS COMPANY,
										NULL,LEFT(ce.VESSEL,10) AS VESSEL,ce.VESSEL AS VESSELNAME,NULL AS VESSELCODE,r.ALIAS1 AS RANKALIAS,ce.GROSSTON,ce.ENGINETYPE AS ENGINE,
										ce.VESSELTYPECODE,ce.TRADEROUTECODE,NULL AS ENGINEPOWER,NULL,DATEEMB,DATEDISEMB,ce.DISEMBREASONCODE,
										dr.REASON AS DISEMBREASON,NULL AS VESSELTYPE,tr.TRADEROUTE,NULL AS DEPMNLDATE,NULL AS ARRMNLDATE,NULL AS CHKPROMOTE,
										m.MANNING,NULL AS CCID2
										FROM crewexperience ce
										LEFT JOIN rank r ON r.RANKCODE=ce.RANKCODE
										LEFT JOIN manning m ON m.MANNINGCODE=ce.MANNINGCODE
										LEFT JOIN disembarkreason dr ON dr.DISEMBREASONCODE=ce.DISEMBREASONCODE
										LEFT JOIN traderoute tr ON tr.TRADEROUTECODE=ce.TRADEROUTECODE
										WHERE ce.APPLICANTNO=$applicantno
									) x
									$wheredate
									ORDER BY x.DATEDISEMB DESC
									LIMIT 10
								") or die(mysql_error());
								
		$experience = "";
		$cnt = 0;
		while ($rowexperience = mysql_fetch_array($qryexperience))
		{
			if ($cnt == 0)
				$ranklevel = $rowexperience["RANKLEVEL"];
				
			$vesselname = $rowexperience["VESSELNAME"];
			
			if (!empty($rowexperience["DATEEMB"]))
				$dateemb = date("dMY",strtotime($rowexperience["DATEEMB"]));
			else
				$dateemb = "---";
			
			if (!empty($rowexperience["DATEDISEMB"]))
				$datedisemb = date("dMY",strtotime($rowexperience["DATEDISEMB"]));
			else
				$datedisemb = "---";
				
			$rankalias = $rowexperience["RANKALIAS"];
			
			$experience .= "<tr>
						<td>$vesselname</td>
						<td>$dateemb</td>
						<td>$datedisemb</td>
						<td>$rankalias</td>
					</tr>";
					
			$cnt++;
		}

	$qryrankfrom = mysql_query("SELECT RANKCODE,RANK,ALIAS1 FROM rank WHERE STATUS=1") or die(mysql_error());

	$rankfromselect = "";
	while ($rowrankfrom = mysql_fetch_array($qryrankfrom))
	{
		$rankcode1 = $rowrankfrom["RANKCODE"];
		$rank1 = $rowrankfrom["RANK"];
		$alias1 = $rowrankfrom["ALIAS1"];
		
		$selected1 = "";
		if ($rankcode1 == $rankcodefrom)
			$selected1 = "SELECTED";
			
		$rankfromselect .= "<option $selected1 value=\"$rankcode1\">$alias1</option>";
	}
	$qryrankto = mysql_query("SELECT RANKCODE,RANK,ALIAS1 FROM rank WHERE STATUS=1") or die(mysql_error());

	$ranktoselect = "";
	while ($rowrankto = mysql_fetch_array($qryrankto))
	{
		$rankcode2 = $rowrankto["RANKCODE"];
		$rank2 = $rowrankto["RANK"];
		$alias2 = $rowrankto["ALIAS1"];
		
		$selected2 = "";
		if ($rankcode2 == $rankcodeto)
			$selected2 = "SELECTED";
			
		$ranktoselect .= "<option $selected2 value=\"$rankcode2\">$alias2</option>";
	}

	$qrygetsubjects = mysql_query("SELECT SUBJECTS FROM upgradingrecorddtl WHERE IDNO=$idno") or die(mysql_error());
	
	$subjectstaken = "";
	while ($rowgetsubjects = mysql_fetch_array($qrygetsubjects))
	{
		$subject = $rowgetsubjects["SUBJECTS"];
		
		$subjectstaken .= "
			<tr $mouseovereffect>
				<td>$subject</td>
				<td><a href=\"#\" onclick=\"actiontxt.value='deletesubject';delsubj.value='$subject';submit();\" style=\"color:Red;font-weight:Bold;\" >X</a></td>
			</tr>
		";
	}
}


$styleborder="style=\"border-bottom:1px solid black;\"";
$fontsize = "font-size:0.8em;";

$fontdata = "style=\"font-weight:Bold;color:Green;\"";

echo "
<html>\n
<head>\n
<title>
Upgrading Record - Printing
</title>

<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>
<script language=\"javascript\" src=\"popcalendar.js\"></script>	

<script>

function compute()
{
	var cnt = 0;
	var total = 0;
	with (document.upgradingrecord)
	{
		if (attendance.value != '')
		{
			cnt++;
			total = total + (attendance.value * 1);
		}
		if (attitude.value != '')
		{
			cnt++;
			total = total + (attitude.value * 1);
		}
			
		if (participation.value != '')
		{
			cnt++;
			total = total + (participation.value * 1);
		}
			
		if (communication.value != '')
		{
			cnt++;
			total = total + (communication.value * 1);
		}
			
		if (dailyquizzes.value != '')
		{
			cnt++;
			total = total + (dailyquizzes.value * 1);
		}
			
		if (handson.value != '')
		{
			cnt++;
			total = total + (handson.value * 1);
		}
			
		if (finaltest.value != '')
		{
			cnt++;
			total = total + (finaltest.value * 1);
		}

		if (cnt > 0)
		{
			var average = (total / cnt);
			totaverage.value = Math.round(average*100)/100;
		}
		else
			totaverage.value = 0;
	}
}

</script>

</head>
<body style=\"\">\n
<form name=\"upgradingrecord\" method=\"POST\">
<span class=\"wintitle\">CREW UPGRADING RECORD</span>
	<div style=\"width:95%;overflow:auto;\">
	";
		if (empty($idno))
		{
			if (!empty($upgradeselect))
			{
				echo "
				<br />
				<span style=\"font-weight:Bold;color:Blue;\">Previous Upgrading</span> &nbsp;
				<select name=\"selidno\" onchange=\"submit();\">
					<option value=\"\">--Select One--</option>
					$upgradeselect
				</select>
				<input type=\"button\" value=\"Create\" onclick=\"actiontxt.value='create';submit();\" />
				";
			}
			else
			{
				echo "
				<br />
				<span style=\"font-weight:Bold;color:Blue;\">No Previous Upgrading</span> &nbsp;
				<input type=\"button\" value=\"Create New Upgrading\" onclick=\"actiontxt.value='create';submit();\" />
				<br />
				";
			}
		}

		echo "
		<br />
		<span class=\"sectiontitle\">CREW INFO</span>
		<br />
		<div style=\"width:100%;height:50px;$fontsize\">
			<table style=\"width:100%;font-size:0.9em;\">
				<tr>
					<td width=\"10%\">NAME</td>
					<td width=\"3%\">:</td>
					<td width=\"30%\">$crewname</td>
					<td width=\"10%\">POSITION</td>
					<td width=\"3%\">:</td>
					<td width=\"45%\">
						<select name=\"rankcodefrom\">
							<option value=\"\">--Select One--</option>
							$rankfromselect
						</select>
						
						to

						<select name=\"rankcodeto\">
							<option value=\"\">--Select One--</option>
							$ranktoselect
						</select>
					</td>
				</tr>
				<tr>
					<td>LEVEL</td>
					<td>:</td>
					<td>$ranklevel</td>
					<td>DATE</td>
					<td>:</td>
					<td>$madedate2</td>
				</tr>
			</table>
		</div>
		<br />
		<span class=\"sectiontitle\">VESSEL EXPERIENCE</span>
		<br />
		<div style=\"width:100%;height:100px;$fontsize\">
			<table style=\"width:80%;font-size:0.9em;text-align:left;\">
				<tr>
					<th width=\"40%\">Vessel Record</th>
					<th width=\"20%\">From</th>
					<th width=\"20%\">To</th>
					<th width=\"20%\">Position</th>
				</tr>
				$experience
			</table>
		</div>
		<br />
		<span class=\"sectiontitle\">SUBJECTS TAKEN AND SCHEDULE</span>
		<br />
		<div style=\"width:100%;height:100px;$fontsize\">
		
			Module : <input type=\"text\" name=\"module\" size=\"30\" value=\"$module\" $fontdata />&nbsp;&nbsp;
			Date : 
			<input type=\"text\" name=\"schedfrom\" value=\"$schedfrom\" $fontdata onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
			<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(schedfrom, schedfrom, 'mm/dd/yyyy', 0, 0);return false;\">
			&nbsp;&nbsp; to &nbsp;&nbsp;
			<input type=\"text\" name=\"schedto\" value=\"$schedto\" $fontdata onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
			<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(schedto, schedto, 'mm/dd/yyyy', 0, 0);return false;\">
			<br /><br />
			
			<table style=\"width:49%;font-size:0.9em;text-align:left;float:left;\">
				<tr>
					<td>Subject</td>
					<td>:</td>
					<td><input type=\"text\" name=\"subject\" size=\"30\" $fontdata /></td>
				</tr>
				<tr>
					<td colspan=\"2\">&nbsp;</td>
					<td><input type=\"button\" value=\"Add to list >>\" onclick=\"actiontxt.value='addsubject';submit();\" /></td>
				</tr>
			</table>
			
			<table style=\"width:50%;height:20px;font-size:0.9em;text-align:left;float:left;overflow:auto;border:1px solid black;\" >
				<tr>
					<th width=\"95%\"><u>SUBJECTS TAKEN</u></th>
					<th width=\"5%\">&nbsp;</th>
				</tr>
				$subjectstaken
			</table>
		</div>
		<br />
		<span class=\"sectiontitle\">ASSESSMENT</span>
		<br />
		<div style=\"width:100%;height:150px;$fontsize\">
			<table style=\"width:49%;font-size:0.9em;text-align:left;float:left;\">
				<tr>
					<td colspan=\"2\"><b>APTITUDE</b></td>
				</tr>
				<tr>
					<td>Attendance</td>
					<td><input type=\"text\" name=\"attendance\" value=\"$attendance\" size=\"5\" $fontdata onKeyPress=\"return numbersonly(this);\" onblur=\"compute();\" style=\"text-align:center\" /></td>
				</tr>
				<tr>
					<td>Attitude</td>
					<td><input type=\"text\" name=\"attitude\" value=\"$attitude\" size=\"5\" $fontdata onKeyPress=\"return numbersonly(this);\" onblur=\"compute();\" style=\"text-align:center\" /></td>
				</tr>
				<tr>
					<td>Participation</td>
					<td><input type=\"text\" name=\"participation\" value=\"$participation\" size=\"5\" $fontdata onKeyPress=\"return numbersonly(this);\" onblur=\"compute();\" style=\"text-align:center\" /></td>
				</tr>
				<tr>
					<td>Communication</td>
					<td><input type=\"text\" name=\"communication\" value=\"$communication\" size=\"5\" $fontdata onKeyPress=\"return numbersonly(this);\" onblur=\"compute();\" style=\"text-align:center\" /></td>
				</tr>
			</table>
			<table style=\"width:49%;font-size:0.9em;text-align:left;float:left;\">
				<tr>
					<td colspan=\"2\"><b>GENERAL KNOWLEDGE/ABILITY</b></td>
				</tr>
				<tr>
					<td width=\"35%\">Daily Quizzes</td>
					<td width=\"65%\"><input type=\"text\" name=\"dailyquizzes\" value=\"$dailyquizzes\" size=\"5\" $fontdata onKeyPress=\"return numbersonly(this);\" onblur=\"compute();\" style=\"text-align:center\" /></td>
				</tr>
				<tr>
					<td colspan=\"2\"><b>PRACTICAL KNOWLEDGE/PROGRESS</b></td>
				</tr>
				<tr>
					<td>Hands-on Exercises</td>
					<td><input type=\"text\" name=\"handson\" value=\"$handson\" size=\"5\" $fontdata onKeyPress=\"return numbersonly(this);\" onblur=\"compute();\" style=\"text-align:center\" /></td>
				</tr>
				<tr>
					<td>Final Test</td>
					<td><input type=\"text\" name=\"finaltest\" value=\"$finaltest\" size=\"5\" $fontdata onKeyPress=\"return numbersonly(this);\" onblur=\"compute();\" style=\"text-align:center\" /></td>
				</tr>
				<tr>
					<td><b>TOTAL AVERAGE</b></td>
					<td>
						<input type=\"text\" name=\"totaverage\" value=\"$totaverage\" size=\"5\" $fontdata  style=\"text-align:center\"/>
					</td>
				</tr>
			</table>
		</div>
		<br />
		<span class=\"sectiontitle\">GENERAL ASSESSMENT AND COMMENTS</span>
		<br />
		<div style=\"width:100%;height:150px;$fontsize\">
			<table style=\"width:100%;font-size:0.9em;text-align:left;float:left;\">
				<tr>
					<td valign=\"top\">1. ABILITY</td>
					<td valign=\"top\"><input type=\"text\" name=\"ga_ability\" value=\"$ga_ability\" size=\"5\" $fontdata onKeyPress=\"return numbersonly(this);\" style=\"text-align:center\"/></td>
					<td><textarea rows=\"2\" cols=\"30\" $fontdata name=\"ga_abilityrem\">$ga_abilityrem</textarea></td>
				</tr>
				<tr>
					<td valign=\"top\">2. ATTITUDE/CONDUCT</td>
					<td valign=\"top\"><input type=\"text\" name=\"ga_attitude\" value=\"$ga_attitude\" size=\"5\" $fontdata onKeyPress=\"return numbersonly(this);\" style=\"text-align:center\" /></td>
					<td><textarea rows=\"2\" cols=\"30\" $fontdata name=\"ga_attituderem\">$ga_attituderem</textarea></td>
				</tr>
				<tr>
					<td valign=\"top\">3. INTEREST</td>
					<td valign=\"top\"><input type=\"text\" name=\"ga_interest\" value=\"$ga_interest\" size=\"5\" $fontdata onKeyPress=\"return numbersonly(this);\" style=\"text-align:center\" /></td>
					<td><textarea rows=\"2\" cols=\"30\" $fontdata name=\"ga_interestrem\">$ga_interestrem</textarea></td>
				</tr>
				<tr>
					<td valign=\"top\">4. PROGRESS</td>
					<td valign=\"top\"><input type=\"text\" name=\"ga_progress\" value=\"$ga_progress\" size=\"5\" $fontdata onKeyPress=\"return numbersonly(this);\" style=\"text-align:center\" /></td>
					<td><textarea rows=\"2\" cols=\"30\" $fontdata name=\"ga_progressrem\">$ga_progressrem</textarea></td>
				</tr>
				<tr>
					<td colspan=\"3\">&nbsp;</td>
				</tr>
				<tr>
					<td colspan=\"3\"><b>RECOMMENDATIONS/REMARKS:</b></td>
				</tr>
				<tr>
					<td colspan=\"3\">
						<textarea rows=\"2\" cols=\"70\" $fontdata name=\"recommendation\">$recommendation</textarea>
					</td>
				</tr>
			</table>
		</div>
		<br />
		<span class=\"sectiontitle\">SIGNATORY</span>
		<br />
		<div style=\"width:100%;height:100px;$fontsize\">
			<table style=\"width:100%;font-size:0.9em;text-align:left;\">
				<tr>
					<td valign=\"top\"><b>Training Officer</b></td>
					<td valign=\"top\">:</td>
					<td valign=\"top\"><input type=\"text\" name=\"trainingofficer\" $fontdata value=\"$trainingofficer\" size=\"50\" /></td>
				</tr>
				<tr>
					<td valign=\"top\"><b>Training Manager</b></td>
					<td valign=\"top\">:</td>
					<td valign=\"top\"><input type=\"text\" name=\"trainingmngr\" $fontdata value=\"$trainingmngr\" size=\"50\" /></td>
				</tr>
				<tr>
					<td colspan=\"3\">&nbsp;</td>
				</tr>
				<tr>
					<td colspan=\"3\"><input type=\"button\" value=\"Save\" onclick=\"actiontxt.value='save';submit();\" />
					<input type=\"button\" value=\"Print\" onclick=\"openWindow('repupgradingrecord.php?applicantno=$applicantno&idno=$idno', 'repdatasheet' ,900, 650);\" /></td>
				</tr>
		</div>

	</div>
	
	<input type=\"hidden\" name=\"applicantno\" value=\"$applicantno\" />
	<input type=\"hidden\" name=\"delsubj\" />
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"idno\" value=\"$idno\"/>
</form>
</body>

</html>

";

?>