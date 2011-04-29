<?php

include("veritas/connectdb.php");
include("veritas/include/stylephp.inc");
include("veritas/include/functions.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');
$datenow = date('d F Y');
$basedir = "idpics/";
$basedirdocs = "docimages/";

$dateformat = "dMY";
//$new = 0;
$disabled = "disabled=\"disabled\"";

$marked = "<span style=\"font-size:1em;font-weight:Bold;\">X</span>";

if(isset($_SESSION["employeeid"]))
	$employeeid=$_SESSION["employeeid"];

if (isset($_POST["actiontxt"]))
	$actiontxt = $_POST["actiontxt"];
	
if (isset($_POST["applicantno"]))
	$applicantno = $_POST["applicantno"];


if (empty($applicantno))
{
	if (isset($_GET["applicantno"]))
		$applicantno = $_GET["applicantno"];
}
	
if (isset($_GET["print"]))
{
	$print = $_GET["print"];
	$disabled = "";
}
else 
	$print = 0;
	
$checked= "checked=\"checked\"";
	
// END OF DOCUMENT TURN-OVER



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
$exprank = $rowheader["EXPRANK"];
$vmcrank = $rowheader["VMCRANK"];

if (!empty($vmcrank))
	$rankshow = $vmcrank;
else 
	$rankshow = $exprank;
	
$lastemployed = $rowheader["MANNING"];
$datedisemb = $rowheader["DATEDISEMB"];


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

if ($vmclisted == 1)
{
	$vmcmarked1 = "";
	$vmcmarked2 = "X";
}
else 
{
	$vmcmarked1 = "X";
	$vmcmarked2 = "";
}
	
if ($poealisted == 1)
{
	$poeamarked1 = "";
	$poeamarked2 = "X";
}
else 
{
	$poeamarked1 = "X";
	$poeamarked2 = "";
}


$qrybackgroundchk = mysql_query("SELECT awd.IDNO,awd.MANNINGOTHERS,awd.REMARKS,awd.INTERVIEWED,awd.POSITION,
								awd.INTERVIEWBY,awd.INTERVIEWDATE,e.GNAME,left(e.MNAME,1) as MNAME,e.FNAME
								FROM applicantwatchhdr awh
								LEFT JOIN applicantwatchdtl awd ON awd.IDNO=awh.IDNO
								LEFT JOIN employee e ON e.EMPLOYEEID=awd.INTERVIEWBY
								WHERE awh.APPLICANTNO=$applicantno") or die(mysql_error());
	

echo "
<html>\n
<head>\n
<title>
Applicant Watchlist/Background Check
</title>

<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>


</head>
<body style=\"\">\n

<form name=\"charcheck\" method=\"POST\">\n

	";
	
	$styleborder = "style=\"border-bottom:1px solid Black;\"";
	$styledata = "style=\"font-weight:Bold;color:Blue;text-align:center;\"";
	$styleencode = "style=\"color:Red;font-weight:Bold;\"";
	
	echo "
	<div style=\"margin:5 5 5 10;width:755px;height:600px;\">
	
		<div style=\"width:100%;height:60px;float:left;background-color:White;\">
			<div style=\"width:85%;float:left;\">
				<center>
					<span style=\"font-size:1.1em;font-weight:Bold;\">VERITAS MARITIME CORPORATION</span><br />
					<span style=\"font-size:0.9em;font-weight:Bold;\">WATCHLIST / BACKGROUND CHECK</span><br />
					<span style=\"font-size:0.8em;font-weight:Bold;\">Date: $datenow</span><br />
				</center>
			</div>
			<div style=\"width:13%;float:right;\">
				<span style=\"font-size:0.7em;font-weight:Bold;\">FM-203</span><br />
				<span style=\"font-size:0.7em;font-weight:Bold;\">REV. MAY 2002</span>
			</div>
		</div>
		
		<br /><br />
		<div style=\"width:100%;background-color:White;\">
			<table style=\"width:100%;\" border=1 cellpadding=\"5\" cellspacing=\"0\">
				<tr>
					<td width=\"50%\"><span style=\"font-size:0.70em;\">NAME:</span>&nbsp;&nbsp; <span style=\"font-size:0.9em;font-weight:Bold;color:Blue;\">$applicant</span></td>
					<td width=\"50%\"><span style=\"font-size:0.70em;\">RANK:</span>&nbsp;&nbsp; <span style=\"font-size:0.9em;font-weight:Bold;color:Blue;\">$rankshow</span></td>
				</tr>
				<tr>
					<td colspan=\"2\" align=\"left\"><span style=\"font-size:0.70em;\">LAST COMPANY EMPLOYED:</span> &nbsp;&nbsp;
						<span style=\"font-size:0.9em;font-weight:Bold;color:Blue;\">$lastemployed</span>
					</td>
				</tr>
			</table>
		</div>
		
		<div style=\"width:100%;background-color:White;\">
			<table style=\"width:100%;font-size:0.9em;\" cellspacing=\"0\" cellpadding=\"2\">
				<tr><td colspan=\"3\">&nbsp;</td></tr>
				<tr>
					<td colspan=\"3\" align=\"left\"><b>VERITAS Watchlist</b></td>
				</tr>
				<tr>
					<td width=\"20%\">[&nbsp; <b>$vmcmarked1</b> &nbsp;]&nbsp;&nbsp; NOT LISTED</td>
					<td width=\"20%\">[&nbsp; <b>$vmcmarked2</b> &nbsp;]&nbsp;&nbsp; LISTED</td>
					<td width=\"60%\" $styleborder>Findings:&nbsp;&nbsp; $vmcfindings</td>
				</tr>
				<tr><td colspan=\"3\">&nbsp;</td></tr>
				<tr>
					<td colspan=\"3\" align=\"left\"><b>OTHERS Watchlist</b></td>
				</tr>
				<tr>
					<td>[&nbsp; <b>$poeamarked1</b> &nbsp;]&nbsp;&nbsp; NOT LISTED</td>
					<td>[&nbsp; <b>$poeamarked2</b> &nbsp;]&nbsp;&nbsp; LISTED</td>
					<td $styleborder>Findings:&nbsp;&nbsp; $poeafindings</td>
				</tr>
			</table>
			<br /><br />
			<span style=\"font-size:1em;\"><b>CHARACTER BACKGROUND CHECK FROM PREVIOUS EMPLOYERS:</b></span>
			<br /><br />
			
			<table style=\"width:100%;font-size:0.9em;\" cellspacing=\"0\" cellpadding=\"2\">
							
			";
			
			$numrows = mysql_num_rows($qrybackgroundchk);
			
			$rowstogo = 4 - $numrows;
			$cnt = 1;
	
			while ($rowbackgroundchk = mysql_fetch_array($qrybackgroundchk))
			{
				$xidno = $rowbackgroundchk["IDNO"];
				$xmanningothers = $rowbackgroundchk["MANNINGOTHERS"];
				$xremarks= $rowbackgroundchk["REMARKS"];
				$xinterviewed = $rowbackgroundchk["INTERVIEWED"];
				$xposition = $rowbackgroundchk["POSITION"];
				$xinterviewby = $rowbackgroundchk["INTERVIEWBY"];
				$xinterviewdate = $rowbackgroundchk["INTERVIEWDATE"];
				$xgname = $rowbackgroundchk["GNAME"];
				$xmname = $rowbackgroundchk["MNAME"];
				$xfname = $rowbackgroundchk["FNAME"];
				
				if (!empty($xinterviewdate))
					$showinterviewedby = $xgname . " " . $xmname . ". " . $xfname;
				else 
					$showinterviewedby = "";
				
				if (!empty($xinterviewed))
					$showinterview = $xinterviewed . " / " . $xposition;
				else 
					$showinterview = "----";
				
				echo "
				<tr>
					<td width=\"10%\">$cnt.</td>
					<td width=\"15%\">Employer:</td>
					<td width=\"75%\"><b>$xmanningothers</b></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>Remarks:</td>
					<td><b>$xremarks</b></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td colspan=\"2\" $styleborder>Person Interviewed / Position: &nbsp;&nbsp;
						<b>$showinterview</b>
					</td>
				</tr>
				<tr>
					<td colspan=\"3\">&nbsp;</td>
				</tr>
				";
				
				$cnt++;
			}
			
			
			for ($i=$cnt;$i<=4;$i++)
			{
				echo "
				<tr>
					<td width=\"10%\">$i.</td>
					<td width=\"15%\">Employer:</td>
					<td width=\"75%\"><b>----</b></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>Remarks:</td>
					<td><b>----</b></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td colspan=\"2\" $styleborder>Person Interviewed / Position: &nbsp;&nbsp;
						<b>----</b>
					</td>
				</tr>
				<tr>
					<td colspan=\"3\">&nbsp;</td>
				</tr>
				";
			}
			
		echo "
			</table>
			
			<br /><br />
			
			<table style=\"width:100%;font-size:0.9em;\" cellspacing=\"0\" cellpadding=\"2\">
				<tr>
					<td colspan=\"2\">&nbsp;</td>
					<td align=\"center\" style=\"border-top:1px solid black;\"><p>$employeeid</p>
				    <p>Interviewer  </p></td>
				</tr>	
				<tr>
					<td>&nbsp;</td>
				</tr>	
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td width=\"50%\" $styleborder>Noted by:</td>
					<td width=\"10%\">&nbsp;</td>
					<td width=\"40%\">&nbsp;</td>
				</tr>
				<tr>
					<td width=\"50%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
								Fleet Manager / Port Captain / Port Engr.
					</td>
					<td width=\"10%\">&nbsp;</td>
					<td width=\"40%\">&nbsp;</td>
				</tr>	
			</table>
		</div>
		
	</div>
	
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"applicantno\" value=\"$applicantno\"/>
</form>";

if ($print == 1)
	include('include/printclose.inc');

echo "
</body>

</html>

";