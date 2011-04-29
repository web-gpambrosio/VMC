<?php
$kups = "gino";
include("veritas/connectdb.php");
include("veritas/include/functions.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');
$datenow = date('d F Y');
$basedir = "idpics/";
$basedirdocs = "docimages/";

if(isset($_SESSION["employeeid"]))
	$employeeid=$_SESSION["employeeid"];

//if (isset($_POST["actiontxt"]))
//	$actiontxt = $_POST["actiontxt"];
	
if (isset($_GET["applicantno"]))
	$applicantno = $_GET["applicantno"];
else 
	$applicantno = $_POST["applicantno"];
	
if (isset($_GET["idno"]))
	$idno = $_GET["idno"];
	
$wheredate = "WHERE x.DATEEMB <= CURRENT_DATE";
	
$qrygetupgrading = mysql_query("
					SELECT c.FNAME,c.GNAME,LEFT(c.MNAME,1) AS MNAME,r1.ALIAS1 AS RANKFROM,r2.ALIAS1 AS RANKTO,u.DATEFROM,u.DATETO,u.MODULE,
					u.ATTENDANCE,u.ATTITUDE,u.PARTICIPATION,u.COMMUNICATION,u.DAILYQUIZZES,u.HANDSON,u.FINALTEST,u.TOTAVERAGE,
					u.GA_ABILITY,u.GA_ABILITYREM,u.GA_ATTITUDE,u.GA_INTEREST,u.GA_INTERESTREM,u.GA_PROGRESS,u.GA_PROGRESSREM,
					u.RECOMMENDATION,u.TRAININGOFFICER,u.TRAININGMNGR,u.MADEBY,u.MADEDATE
					FROM upgradingrecordhdr u
					LEFT JOIN crew c ON c.APPLICANTNO=u.APPLICANTNO
					LEFT JOIN rank r1 ON r1.RANKCODE=u.RANKCODEFROM
					LEFT JOIN rank r2 ON r2.RANKCODE=u.RANKCODETO
					WHERE u.IDNO=$idno
") or die(mysql_error());

$rowgetupgrading = mysql_fetch_array($qrygetupgrading);

	$fname = $rowgetupgrading["FNAME"];
	$gname = $rowgetupgrading["GNAME"];
	$mname = $rowgetupgrading["MNAME"];
	
	$crewname = $fname . ", " . $gname . " " . $mname . ".";

$rankfrom = $rowgetupgrading["RANKFROM"];
$rankto = $rowgetupgrading["RANKTO"];
if (!empty($rowgetupgrading["DATEFROM"]))
	$schedfrom = date("dMY",strtotime($rowgetupgrading["DATEFROM"]));
else
	$schedfrom = "";
if (!empty($rowgetupgrading["DATETO"]))
	$schedto = date("dMY",strtotime($rowgetupgrading["DATETO"]));
else
	$schedto = "";
	
$schedshow = "";
if (!empty($schedfrom) && !empty($schedto))
	$schedshow = $schedfrom . " to " . $schedto;
else
	$schedshow = $schedfrom;
	
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

// $qryrankfrom = mysql_query("SELECT RANKCODE,RANK,ALIAS1 FROM rank WHERE STATUS=1") or die(mysql_error());

// $rankfromselect = "";
// while ($rowrankfrom = mysql_fetch_array($qryrankfrom))
// {
	// $rankcode1 = $rowrankfrom["RANKCODE"];
	// $rank1 = $rowrankfrom["RANK"];
	// $alias1 = $rowrankfrom["ALIAS1"];
	
	// $selected1 = "";
	// if ($rankcode1 == $rankcodefrom)
		// $selected1 = "SELECTED";
		
	// $rankfromselect .= "<option $selected1 value=\"$rankcode1\">$alias1</option>";
// }
// $qryrankto = mysql_query("SELECT RANKCODE,RANK,ALIAS1 FROM rank WHERE STATUS=1") or die(mysql_error());

// $ranktoselect = "";
// while ($rowrankto = mysql_fetch_array($qryrankto))
// {
	// $rankcode2 = $rowrankto["RANKCODE"];
	// $rank2 = $rowrankto["RANK"];
	// $alias2 = $rowrankto["ALIAS1"];
	
	// $selected2 = "";
	// if ($rankcode2 == $rankcodeto)
		// $selected2 = "SELECTED";
		
	// $ranktoselect .= "<option $selected2 value=\"$rankcode2\">$alias2</option>";
// }

$qrygetsubjects = mysql_query("SELECT SUBJECTS FROM upgradingrecorddtl WHERE IDNO=$idno") or die(mysql_error());

$subjcnt = 0;
$subjectstaken = "";
while ($rowgetsubjects = mysql_fetch_array($qrygetsubjects))
{
	$subject = $rowgetsubjects["SUBJECTS"];
	$subjcnt++;
	$subjectstaken .= "
	$subjcnt.)&nbsp;&nbsp; $subject <br />
	";
}
	
	
	
$styleborder="style=\"border-bottom:1px solid black;\"";

echo "
<html>\n
<head>\n
<title>
Crew Upgrading Record
</title>

<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>
<script language=\"javascript\" src=\"popcalendar.js\"></script>	
<style type='text/css'>
@media print 
{
	#PAScreenOut { display: none; }
}
</style>

</head>
<body style=\"overflow:auto;\">\n

<div style=\"width:755px;background-color:white;\">

	<div style=\"width:100%;height:60px;float:left;background-color:White;overflow:hidden;\">
		<center>
			<span style=\"font-size:1em;font-weight:Bold;\">VERITAS MARITIME CORPORATION
			<input type=\"button\" value=\"Print\" id=\"PAScreenOut\" onclick=\"window.print();\">
			</span><br />
			<span style=\"font-size:0.8em;font-weight:Bold;\">CREW UPGRADING RECORD</span><br />
			<span style=\"font-size:0.7em;font-weight:Bold;\">Date: $datenow</span><br />
		</center>
	</div>
	";

	echo "
		<br />
		<div style=\"width:100%;height:50px;$fontsize\">
			<table style=\"width:100%;font-size:0.9em;\">
				<tr>
					<td width=\"10%\"><b>NAME</b></td>
					<td width=\"3%\">:</td>
					<td width=\"30%\">$crewname</td>
					<td width=\"10%\"><b>POSITION</b></td>
					<td width=\"3%\">:</td>
					<td width=\"45%\">$rankfrom to $rankto</td>
				</tr>
				<tr>
					<td><b>LEVEL</b></td>
					<td>:</td>
					<td>$ranklevel</td>
					<td><b>DATE</b></td>
					<td>:</td>
					<td>$madedate2</td>
				</tr>
			</table>
		</div>
		<br />
		<div style=\"width:100%;height:100px;$fontsize\">
			<center>
			<table style=\"width:80%;font-size:0.9em;text-align:left;\">
				<tr>
					<th width=\"40%\">Vessel Record</th>
					<th width=\"20%\">From</th>
					<th width=\"20%\">To</th>
					<th width=\"20%\">Position</th>
				</tr>
				$experience
			</table>
			</center>
		</div>
		<br />
		<div style=\"width:100%;$fontsize\">
		
			<table style=\"width:100%;font-size:0.9em;\">
				<tr>
					<td width=\"8%\"><b>MODULE</b></td>
					<td width=\"2%\">:</td>
					<td width=\"40%\">$module</td>
					<td width=\"8%\"><b>DATE</b></td>
					<td width=\"2%\">:</td>
					<td width=\"40%\">$schedshow</td>
				</tr>
				<tr>
					<td valign=\"top\"><b>SUBJECTS</b></td>
					<td valign=\"top\">:</td>
					<td colspan=\"4\">
						$subjectstaken
					</td>
				</tr>
			</table>
		</div>
		<br />
		<div style=\"width:100%;$fontsize\">
			<span style=\"font-size:0.9em;font-weight:Bold;\">ASSESSMENT OF THE SUBJECT TAKEN</span> <br /><br />
			<center>
			<div style=\"width:90%;\">
				<table style=\"width:50%;font-size:0.9em;text-align:left;float:left;\">
					<tr>
						<td colspan=\"2\"><b>APTITUDE</b></td>
					</tr>
					<tr>
						<td width=\"50%\">Attendance</td>
						<td width=\"50%\"><b>$attendance</b></td>
					</tr>
					<tr>
						<td>Attitude</td>
						<td><b>$attitude</b></td>
					</tr>
					<tr>
						<td>Participation</td>
						<td><b>$participation</b></td>
					</tr>
					<tr>
						<td>Communication</td>
						<td><b>$communication</b></td>
					</tr>
				</table>
				<table style=\"width:50%;font-size:0.9em;text-align:left;float:left;\">
					<tr>
						<td colspan=\"2\"><b>GENERAL KNOWLEDGE/ABILITY</b></td>
					</tr>
					<tr>
						<td width=\"40%\">Daily Quizzes</td>
						<td width=\"60%\"><b>$dailyquizzes</b></td>
					</tr>
					<tr>
						<td colspan=\"2\"><b>PRACTICAL KNOWLEDGE/PROGRESS</b></td>
					</tr>
					<tr>
						<td>Hands-on Exercises</td>
						<td><b>$handson</b></td>
					</tr>
					<tr>
						<td>Final Test</td>
						<td><b>$finaltest</b></td>
					</tr>
					<tr>
						<td><b>TOTAL AVERAGE</b></td>
						<td><b>$totaverage</b></td>
					</tr>
				</table>
			</div>
			</center>
		</div>
		<br />
		<div style=\"width:100%;$fontsize\">
		<span style=\"font-size:0.9em;font-weight:Bold;\">GENERAL ASSESSMENT AND COMMENTS</span> <br /><br />
			<table style=\"width:100%;font-size:0.9em;text-align:left;\">
				<tr>
					<td valign=\"top\" width=\"25%\">1. ABILITY</td>
					<td valign=\"top\" width=\"5%\"><b>($ga_ability)</b></td>
					<td width=\"70%\">$ga_abilityrem</td>
				</tr>
				<tr>
					<td valign=\"top\">2. ATTITUDE/CONDUCT <br />&nbsp;&nbsp;&nbsp;&nbsp;(during upgrading)</td>
					<td valign=\"top\"><b>($ga_attitude)</b></td>
					<td>$ga_attituderem</td>
				</tr>
				<tr>
					<td valign=\"top\">3. INTEREST</td>
					<td valign=\"top\"><b>($ga_interest)</b></td>
					<td>$ga_interestrem</td>
				</tr>
				<tr>
					<td valign=\"top\">4. PROGRESS</td>
					<td valign=\"top\"><b>($ga_progress)</b></td>
					<td>$ga_progressrem</td>
				</tr>
			</table>
			
		</div>
		<br />
		<div style=\"width:100%;$fontsize\">
		<span style=\"font-size:0.9em;font-weight:Bold;\">RECOMMENDATIONS/REMARKS:</span> <br /><br />
		$recommendation
		<br /><br /><br />
			<table style=\"width:95%;font-size:0.9em;text-align:left;\">
				<tr>
					<td align=\"center\" width=\"40%\" style=\"border-bottom:1px solid black;\"><b>$trainingofficer</b></td>
					<td align=\"center\" width=\"20%\">&nbsp;</td>
					<td align=\"center\" width=\"40%\" style=\"border-bottom:1px solid black;\"><b>$trainingmngr</b></td>
				</tr>
				<tr>
					<td align=\"center\">Training Officer</td>
					<td align=\"center\">&nbsp;</td>
					<td align=\"center\">Training Manager</td>
				</tr>
			</table>
		<br /><br />
		<center>
		<span style=\"font-size:0.9em;font-weight:Bold;\">
			Classification: &nbsp;&nbsp;
			Excellent = 5&nbsp;&nbsp;
			Good = 4&nbsp;&nbsp;
			Satisfactory = 3&nbsp;&nbsp;
			Poor = 2&nbsp;&nbsp;
			Bad = 1
		</span>
		</center>
				
		</div>

</div>

</body>

</html>

";

?>