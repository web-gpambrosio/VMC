<?php

include("veritas/connectdb.php");
include("veritas/include/stylephp.inc");

session_start();

$currentdate = date("Y-m-d H:i:s");

if (isset($_POST["actiontxt"]))
	$actiontxt = $_POST["actiontxt"];

if(isset($_SESSION["employeeid"]))
	$employeeid=$_SESSION["employeeid"];

//$_GET 
	
if(isset($_GET["applicantno"]))
	$applicantno=$_GET["applicantno"];
else 
	$applicantno=$_POST["applicantno"];
	
if(isset($_GET["traincode"]))
	$traincode=$_GET["traincode"];

if(isset($_GET["getname"]))
	$getname=$_GET["getname"];

if(isset($_GET["getvesselcode"]))
	$getvesselcode=$_GET["getvesselcode"];

if(isset($_GET["getrankcode"]))
	$getrankcode=$_GET["getrankcode"];
	
if(isset($_GET["getetd"]))
	$getetd=$_GET["getetd"];

	
	
	
if(isset($_POST["rankcode"]))
	$rankcode=$_POST["rankcode"];

if(isset($_POST["vesselcode"]))
	$vesselcode=$_POST["vesselcode"];

if (isset($_POST["schedule"]))
	$schedule = $_POST["schedule"];
else 
	$schedule = "";

if (isset($_POST["schedid"]))
	$schedid = $_POST["schedid"];
else 
	$schedid = "";

$disableenroll = "";

// disabled to remove date restriction for the enrollment. - Jan. 8, 2010  -GPA

// if (!empty($getetd))
	// $wheredate = "AND th.DATETO BETWEEN '$currentdate' AND '" . date("Y-m-d H:i:s",strtotime($getetd)) . "'";
// else 
	// $wheredate = "AND th.DATETO >= '$currentdate'";

$wheredate = "AND th.DATETO > '2009-01-01'";
	
	
switch ($actiontxt)
{
	case "showparticipants"	:
		
			if ($schedid != "")
			{
				$qryparticipants = mysql_query("SELECT c.APPLICANTNO,CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,r.RANK,r.RANKCODE
								FROM crewtraining ct
								LEFT JOIN crew c ON c.APPLICANTNO=ct.APPLICANTNO
								LEFT JOIN rank r ON r.RANKCODE=ct.RANKCODE
								WHERE ct.SCHEDID=$schedid
								") or die(mysql_error());
			}
		
		break;
		
	case "enroll"	:
		
			$qryenroll = mysql_query("INSERT INTO crewtraining(APPLICANTNO,SCHEDID,CLASSCARDNO,RANKCODE,VESSELCODE,MADEBY,MADEDATE) 
									VALUES($applicantno,$schedid,0,'$rankcode','$vesselcode','$employeeid','$currentdate')") or die(mysql_error());
			
			$qryverify = mysql_query("SELECT SCHEDID FROM trainingendorsement
									WHERE APPLICANTNO=$applicantno AND TRAINCODE='$traincode' 
									AND SCHEDID IS NULL AND STATUS=0 ") or die(mysql_error());
			
			if (mysql_num_rows($qryverify) > 0)
			{
				$qryupdateendorsement = mysql_query("UPDATE trainingendorsement SET SCHEDID=$schedid,STATUS=1 
										WHERE APPLICANTNO=$applicantno AND TRAINCODE='$traincode' 
										AND SCHEDID IS NULL AND STATUS=0") or die(mysql_error());
			}
			
			$disableenroll = "disabled=\"disabled\"";
			
		break;
	
}


$qryrank = mysql_query("SELECT RANKCODE,RANK,ALIAS1 AS RANKALIAS
						FROM rank
						WHERE RANKCODE='$getrankcode'
						") or die(mysql_error());

$qryvessel = mysql_query("SELECT VESSELCODE,VESSEL
							FROM vessel
							WHERE vesselcode='$getvesselcode'
						") or die(mysql_error());
	
$qrytraininfo = mysql_query("SELECT TRAINCODE,TRAINING,DESCRIPTION,STATUS,COURSETYPECODE
							FROM trainingcourses
							WHERE TRAINCODE='$traincode'
						") or die(mysql_error());

$qryschedules = mysql_query("SELECT th.SCHEDID,th.DATEFROM,th.DATETO,th.MAXSLOTS,th.REMARKS,tv.TRAINVENUECODE,tv.TRAINVENUE,
							td.SCHEDDATE,td.INSTRUCTOR,td.TIMESTART,td.TIMEEND
							FROM trainingschedhdr th
							LEFT JOIN trainingscheddtl td ON td.SCHEDID=th.SCHEDID
							LEFT JOIN trainingvenue tv ON tv.TRAINVENUECODE=td.TRAINVENUECODE
							WHERE th.TRAINCODE='$traincode' AND th.STATUS=1
							$wheredate
							ORDER BY td.SCHEDDATE
						") or die(mysql_error());



echo "
<html>
<head>
<title>
Training Enrollment Schedules
</title>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>

<script language='JavaScript'>
function passBack2()
{
	close();window.opener.submit();
}
</script>

<body style=\"overflow:hidden;\">

<form name=\"enrollment\" method=\"POST\">

<span class=\"wintitle\">ENROLLMENT SCHEDULES</span>

<div style=\"width:100%;\">

	<div style=\"width:100%;height:150px;\">
		<div style=\"width:60%;float:left;height:150px;background-color:#DCDCDC;\">
			<span class=\"sectiontitle\">TRAINING INFORMATION</span>

			<div style=\"width:100%;overflow:auto;padding:5px;\">
";
			$rowtraininfo = mysql_fetch_array($qrytraininfo);

			$tcode = $rowtraininfo["TRAINCODE"];
			$train = $rowtraininfo["TRAINING"];
			$desc = $rowtraininfo["DESCRIPTION"];
				
echo "
				<table width=\"100%\">
					<tr>
						<th width=\"20%\" style=\"font-size:0.8em;font-weight:Bold;color:Black;text-align:left;\">Code</th>
						<th width=\"5%\"><b>:</b></th>
						<td style=\"font-size:0.9em;font-weight:Bold;color:Blue;\">$tcode</td>
					</tr>
					<tr>
						<th style=\"font-size:0.8em;font-weight:Bold;color:Black;text-align:left;\">Training</th>
						<th width=\"5%\"><b>:</b></th>
						<td style=\"font-size:0.9em;font-weight:Bold;color:Blue;\">$train</td>
					</tr>
					<tr>
						<th valign=\"top\" style=\"font-size:0.8em;font-weight:Bold;color:Black;text-align:left;\">Description</th>
						<th width=\"5%\" valign=\"top\"><b>:</b></th>
						<td valign=\"top\" style=\"font-size:0.9em;color:Black;\">$desc</td>
					</tr>
				</table>
				
			</div>
			
		</div>
		<div style=\"width:40%;float:right;height:150px;background-color:#006600;border-left:1px solid black;\">
			<span class=\"sectiontitle\">BACKGROUND</span>
			
			<div style=\"width:100%;overflow:auto;padding:5px;\">
";
			$rowrank = mysql_fetch_array($qryrank);
			$rankcode1 = $rowrank["RANKCODE"];
			$rank = $rowrank["RANKALIAS"];
			
			$rowvessel = mysql_fetch_array($qryvessel);
			$vessel = $rowvessel["VESSEL"];
			$vesselcode1 = $rowvessel["VESSELCODE"];
			
			if (!empty($getetd))
				$etd = date("dMY",strtotime($getetd));
			else 
				$etd = "---";
			
			$name = $getname;
				
echo "
				<table style=\"width:100%;font-size:0.7em;font-weight:Bold;\">
					<tr>
						<td width=\"25%\" style=\"color:White;text-align:left;\">Name</td>
						<td width=\"5%\" style=\"color:White;\"><b>:</b></td>
						<td style=\"color:Yellow;text-align:left;\">$name</td>
					</tr>
					<tr>
						<td width=\"25%\" style=\"color:White;text-align:left;\">Rank</td>
						<td width=\"5%\" style=\"color:White;\"><b>:</b></td>
						<td style=\"color:Yellow;text-align:left;\">$rank</td>
					</tr>
					<tr>
						<td width=\"25%\" style=\"color:White;text-align:left;\">ETD</td>
						<td width=\"5%\" style=\"color:White;\">:</td>
						<td style=\"color:Yellow;text-align:left;\">$etd</td>
					</tr>
					<tr>
						<td width=\"25%\" style=\"color:White;text-align:left;\">Vessel Assigned</td>
						<td width=\"5%\" style=\"color:White;\">:</td>
						<td style=\"color:Yellow;text-align:left;\">$vessel</td>
					</tr>
				</table>

			</div>
			
		</div>
	</div>
	<div style=\"width:100%;height:350px;\">
		<div style=\"width:60%;float:left;height:350px;background-color:White;\">
			<span class=\"sectiontitle\">SCHEDULES</span>
			
			<div style=\"width:100%;height:300px;overflow:auto;\">
			
				<table class=\"listcol\">
					<tr>
						<th>&nbsp;</th>
						<th>DATE</th>
						<th>START</th>
						<th>END</th>
						<th>VENUE</th>
						<th>SLOTS</th>
					</tr>
";
					$tmpschedid = "";
					$checkschedid = "";
									
					while($rowschedules = mysql_fetch_array($qryschedules))
					{
						$schedid1 = $rowschedules["SCHEDID"];
						$datefrom = date('dMY',strtotime($rowschedules["DATEFROM"]));
						$dateto = date('dMY',strtotime($rowschedules["DATETO"]));
						$scheddate = date('dMY',strtotime($rowschedules["SCHEDDATE"]));
						$timestart = date('H:i',strtotime($rowschedules["TIMESTART"]));
						$timeend = date('H:i',strtotime($rowschedules["TIMEEND"]));
						$venue = $rowschedules["TRAINVENUE"];
						$slots = $rowschedules["MAXSLOTS"];
						
						if ($datefrom != $dateto)
							$datefromto = $datefrom . " to " . $dateto;
						else 
							$datefromto = $datefrom;

						if ($schedid1 == $schedid)
							$checkschedid = "checked=\"checked\"";
						else 
							$checkschedid = "";
							
						$qryparticipants1 = mysql_query("SELECT ct.APPLICANTNO
										FROM crewtraining ct
										WHERE ct.SCHEDID=$schedid1
										") or die(mysql_error());
							
							
						$slotstaken = mysql_num_rows($qryparticipants1);
						
						$slotsavailable = $slots - $slotstaken;
						
						if ($tmpschedid != $schedid1)
						{
							echo "
							<tr $mouseovereffect style=\"background-color:Yellow;\">
								<td align=\"center\">
								";
								if ($slotsavailable > 0)
								{
									echo "
									<input type=\"radio\" name=\"schedule\" $checkschedid value=\"$schedid1\"
									style=\"cursor:pointer;\" onclick=\"schedid.value='$schedid1';actiontxt.value='showparticipants';submit();\">
									";
								}
								else 
								{
									echo "<span style=\"font-size:1.1em;font-weight:Bold;color:red;cursor:pointer;\" title=\"Click to view participants.\"
										onclick=\"schedid.value='$schedid1';actiontxt.value='showparticipants';submit();\">FULL</span>";
								}
								echo "
								</td>
								<td colspan=\"4\" style=\"font-size:1.1em;font-weight:bold;color:Blue;text-align:center;\">
									<i>$datefromto</i>&nbsp;&nbsp;(Sched ID:$schedid1)
								</td>
								<td align=\"center\"><b>$slotsavailable</b></td>
							</tr>
							";
						}
						
						echo "
							<tr>
								<td>&nbsp;</td>
								<td align=\"center\">$scheddate</td>
								<td align=\"center\">$timestart</td>
								<td align=\"center\">$timeend</td>
								<td colspan=\"2\">$venue</td>
							</tr>
						
						";
						
						$tmpschedid = $schedid1;
						
					}

echo "
				</table>
			</div>
			
			<div style=\"width:100%;height:60px;background-color:Black;padding-top:10px;\">
";
				if ($schedule == "")
					$disableenroll = "disabled=\"disabled\"";

echo "
				<center>
					<input type=\"button\" value=\"Enroll Now!\" 
							onclick=\"if(confirm('Please confirm enrollment of $name ($rank) / $vessel')) 
									{actiontxt.value='enroll';schedid.value='$schedid';applicantno.value='$applicantno';
									rankcode.value='$rankcode1';vesselcode.value='$vesselcode1';enrollment.submit();
									
									opener.document.formchecklist.applicantno.value='$applicantno';
									opener.document.formchecklist.submit();}\" 
							style=\"font-size:1.2em;font-weight:Bold;
							background-color:Red;color:Yellow;cursor:pointer;\" $disableenroll>
							
					<input type=\"button\" value=\"Close\" style=\"font-size:1.2em;font-weight:Bold;
							background-color:Red;color:Yellow;cursor:pointer;\" onclick=\"window.close();\"
				</center>
			</div>
			
		</div>
		<div style=\"width:40%;float:right;height:378px;background-color:#98CC66;border-left:1px solid black;\">
			
			<div style=\"width:100%;height:100px;background-color:Black;overflow:auto;\">
				<span class=\"sectiontitle\">REMARKS</span>
				
				
			</div>
			
			<div style=\"width:100%;height:250px;overflow:auto;\">
				<span class=\"sectiontitle\">PARTICIPANTS</span>
				
				<table class=\"listcol\">
					<tr>
						<th>APPNO</th>
						<th>NAME</th>
						<th>RANK</th>
					</tr>
";
									
					while($rowparticipants = mysql_fetch_array($qryparticipants))
					{
						$part_appno = $rowparticipants["APPLICANTNO"];
						$participant = $rowparticipants["NAME"];
						$part_rank = $rowparticipants["RANK"];
						
						echo "
							<tr>
								<td align=\"center\">$part_appno</td>
								<td>$participant</td>
								<td>$part_rank</td>
							</tr>
						
						";
					}
				
echo "	
				</table>
			</div>
			
		</div>
	</div>
</div>

	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"applicantno\" />
	<input type=\"hidden\" name=\"schedid\" />
	<input type=\"hidden\" name=\"rankcode\" />
	<input type=\"hidden\" name=\"vesselcode\" />
	

</form>

<form name=\"crewtraining\" method=\"POST\" target=\"crewtraining\" action=\"crewtraining.php\">
	<input type=\"hidden\" name=\"applicantno\" value=\"$applicantno\" />
</form>

</body>

</html>

";


?>