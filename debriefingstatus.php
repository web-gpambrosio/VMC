<?php

include('veritas/connectdb.php');
include("veritas/include/stylephp.inc");

$currentdate = date('Y-m-d H:i:s');
$currentdate = date('Y-m-d H:i:s');

$display1 = "display:none;";
$dateformat = "dMY";

if (isset($_POST['transdatefrom']))
	$transdatefrom = $_POST['transdatefrom'];
	
if (isset($_POST['transdateto']))
	$transdateto = $_POST['transdateto'];

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];

if (isset($_POST['vesselselect']))
	$vesselselect = $_POST['vesselselect'];

if (isset($_POST['xstatus']))
	$xstatus = $_POST['xstatus'];
else 
	$xstatus = 2;

switch ($actiontxt)
{
	case "viewlist"	:
			$display1 = "display:block;";
			
			$transdatefromraw = date('Y-m-d',strtotime($transdatefrom));
			$transdatetoraw = date('Y-m-d',strtotime($transdateto));
			
			if (!empty($vesselselect))
				$wherevessel = "AND cc.VESSELCODE='$vesselselect'";
			else
				$wherevessel = "";

			$qrydebrieflist = mysql_query("SELECT dh.CCID,v.VESSEL,dh.REPORTEDDATE,dh.FILLUPDATE,dh.SURRENDERTO,dh.SURRENDERDATE,dh.SURRENDERREMARKS,
												dh.SCANNEDBY,dh.SCANNEDDATE,dh.SCANNEDREMARKS,dh.STOREDBY,dh.STOREDDATE,dh.STOREDREMARKS,
												dh.UPDATEDOCBY,dh.UPDATEDOCDATE,dh.UPDATEDOCREMARKS,dh.SCHOLARBY,dh.SCHOLARDATE,dh.SCHOLARREMARKS,
												dh.FLEETBY,dh.FLEETDATE,dh.FLEETREMARKS,dh.TRAININGBY,dh.TRAININGDATE,dh.TRAININGREMARKS,
												dh.DIVISIONBY,dh.DIVISIONDATE,dh.DIVISIONREMARKS,dh.MANAGEMENTBY,dh.MANAGEMENTDATE,dh.MANAGEMENTREMARKS,
												dh.ACCOUNTINGBY,dh.ACCOUNTINGDATE,dh.ACCOUNTINGREMARKS,dh.PRINTBY,dh.PRINTDATE,
												c.APPLICANTNO,c.CREWCODE,c.FNAME,c.GNAME,c.MNAME,r.ALIAS1 AS RANKALIAS,
												IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,cc.ARRMNLDATE,
												IF(
												dh.FILLUPDATE IS NOT NULL AND
												dh.SURRENDERDATE IS NOT NULL AND
												dh.SCANNEDDATE IS NOT NULL AND
												dh.STOREDDATE IS NOT NULL AND
												dh.FLEETDATE IS NOT NULL AND
												dh.TRAININGDATE IS NOT NULL AND
												(dh.DIVISIONDATE IS NOT NULL OR dh.MANAGEMENTDATE IS NOT NULL) AND
												dh.ACCOUNTINGDATE IS NOT NULL,1,0) AS DEBRIEFSTATUS,
												IF(cs.APPLICANTNO IS NULL,0,1) AS SCHOLAR,s.DESCRIPTION AS SCHOLARTYPE,
												IF(cf.APPLICANTNO IS NULL,0,1) AS FASTTRACK
												FROM debriefinghdr dh
												LEFT JOIN crewchange cc ON cc.CCID=dh.CCID
												LEFT JOIN rank r ON r.RANKCODE=cc.RANKCODE
												LEFT JOIN vessel v ON v.VESSELCODE=cc.VESSELCODE
												LEFT JOIN crew c ON c.APPLICANTNO=cc.APPLICANTNO
												LEFT JOIN crewscholar cs ON cs.APPLICANTNO=cc.APPLICANTNO
												LEFT JOIN scholar s ON s.SCHOLASTICCODE = cs.SCHOLASTICCODE
												LEFT JOIN crewfasttrack cf ON cf.APPLICANTNO=cc.APPLICANTNO
												WHERE REPORTEDDATE BETWEEN '$transdatefromraw 00:00:00' AND '$transdatetoraw 23:59:59'
												$wherevessel
												ORDER BY dh.REPORTEDDATE,c.FNAME
											") or die(mysql_error());
			
		break;
}

$qryvessel = mysql_query("SELECT VESSELCODE,VESSEL FROM vessel WHERE STATUS=1 ORDER BY VESSEL") or die(mysql_error());

$vesselselectname = "";
while ($rowvessel = mysql_fetch_array($qryvessel))
{
	$zvslcode = $rowvessel["VESSELCODE"];
	$zvslname = $rowvessel["VESSEL"];
	$selected = "";
	
	if ($vesselselect == $zvslcode)
		$selected = "SELECTED";
	
	$vesselselectname .= "<option $selected value=\"$zvslcode\">$zvslname</option>";
}

echo "
<html>

<!-- HEAD START -->
<head>
	<title>Debriefing Status Report</title>
	<script language=\"javascript\" src=\"popcalendar.js\"></script>	
	<script language=\"javascript\" src=\"veripro.js\"></script>	
	<link rel=\"stylesheet\" href=\"veripro.css\">

	<script>

				
	</script>
	
</head>

<body>

<form name=\"debriefingstatus\" method=\"POST\">

	<center>
	<div style=\"width:100%;height:650px;padding:2px;border:1px solid black;\">
	
		<span class=\"wintitle\">DEBRIEFING STATUS REPORT</span>
		<br />
		<table width=\"90%\" style=\"font-size:0.8em;\">
			<tr>
				<th valign=\"center\" align=\"center\">Report Date</th>
				<th valign=\"center\" align=\"center\">From<br />
					<input type=\"text\" name=\"transdatefrom\" value=\"$transdatefrom\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(transdatefrom, transdatefrom, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
				<th valign=\"center\" align=\"center\">To<br />
					<input type=\"text\" name=\"transdateto\" value=\"$transdateto\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(transdateto, transdateto, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
				<th valign=\"center\" align=\"center\">Vessel<br />
					<select name=\"vesselselect\" onchange=\"actiontxt.value='viewlist';debriefingstatus.submit();\">
						<option value=\"\">-- ALL --</option>
						$vesselselectname
					</select>
				</th>
				<td align=\"center\"><b>Status</b> <br />
					<select name=\"xstatus\" onchange=\"actiontxt.value='viewlist';debriefingstatus.submit();\">";

						$select0 = "";
						$select1 = "";
						$select2 = "";
						
						// echo "xstatus = $xstatus";
						
						switch ($xstatus)
						{
							case 0	:	$select0 = "SELECTED"; break;
							case 1	:	$select1 = "SELECTED"; break;
							case 2	:	$select2 = "SELECTED"; break;
						}
						
						echo "<option $select2 value=\"2\">ALL</option>";
						echo "<option $select1 value=\"1\">COMPLETED</option>";
						echo "<option $select0 value=\"0\">INCOMPLETE</option>";
						
						echo "
					</select>
				</td>
				<th valign=\"center\" colspan=\"2\" align=\"left\">
					<input type=\"button\" value=\"View\" onclick=\"actiontxt.value='viewlist';debriefingstatus.submit();\" />
					<input type=\"button\" name=\"btnreport\" 
						onclick=\"
						repdebriefingstatus.transdatefrom.value=transdatefrom.value;
						repdebriefingstatus.transdateto.value=transdateto.value;
						repdebriefingstatus.vesselselect.value=vesselselect.value;
						repdebriefingstatus.status2.value=xstatus.value;
						window.open(repdebriefingstatus.action,repdebriefingstatus.target,'scrollbars=yes,resizable=yes,channelmode=yes');
						repdebriefingstatus.submit();\" value=\"Print Report\">				
				</th>
			</tr>
		</table>
		
		<hr />
		
		<div style=\"width:100%;height:400px;padding:2px;$display1\">
			<table width=\"100%\" style=\"font-size:0.75em;\">
				<tr>
					<th width=\"40%\" align=\"center\"><u>NAME</u></th>
					<th width=\"60%\" align=\"center\"><u>REMARKS</u></th>
				</tr>
		";

			$tmpreportdate = "";
			
			while ($rowdebrieflist = mysql_fetch_array($qrydebrieflist))
			{
			
				$ccid = $rowdebrieflist["CCID"];
				$applicantno = $rowdebrieflist["APPLICANTNO"];
				$fname = $rowdebrieflist["FNAME"];
				$gname = $rowdebrieflist["GNAME"];
				$mname = $rowdebrieflist["MNAME"];
				$vesselname = $rowdebrieflist["VESSEL"];
				$rankalias = $rowdebrieflist["RANKALIAS"];
				$scholar = $rowdebrieflist["SCHOLAR"];
				$fasttrack = $rowdebrieflist["FASTTRACK"];
				if (!empty($rowdebrieflist["SCHOLARTYPE"]))
					$scholartype = $rowdebrieflist["SCHOLARTYPE"];
				else
					$scholartype = "---";
				
				if (!empty($rowdebrieflist["DATEDISEMB"]))
					$datedisemb = date($dateformat,strtotime($rowdebrieflist["DATEDISEMB"]));
				else 
					$datedisemb = "---";
				
				if (!empty($rowdebrieflist["ARRMNLDATE"]))
					$arrmnldate = date($dateformat,strtotime($rowdebrieflist["ARRMNLDATE"]));
				else 
					$arrmnldate = "---";
				
				if (!empty($rowdebrieflist["PRINTDATE"]))
					$printdate = date($dateformat,strtotime($rowdebrieflist["PRINTDATE"]));
				else 
					$printdate = "---";
					
				$printby = $rowdebrieflist["PRINTBY"];
				
				$nameshow = $fname . ", " . $gname . " " . $mname;
				
				if (!empty($rowdebrieflist["REPORTEDDATE"]))
					$reporteddate = date($dateformat,strtotime($rowdebrieflist["REPORTEDDATE"]));
				else 
					$reporteddate = "---";
					
				$scholarby = $rowdebrieflist["SCHOLARBY"];
				
				if (!empty($rowdebrieflist["SCHOLARDATE"]))
					$scholardate = date($dateformat,strtotime($rowdebrieflist["SCHOLARDATE"]));
				else 
					$scholardate = "---";
					
				if (!empty($rowdebrieflist["SCHOLARREMARKS"]))
					$scholarremarks = $rowdebrieflist["SCHOLARREMARKS"];
				else 
					$scholarremarks = "&nbsp;";
				
				switch ($rowdebrieflist["DEBRIEFSTATUS"])
				{
					case 0	:	$status = 0;
								$statusshow = "<span style=\"font-size:1.5em;font-weight:Bold;color:Red;\">INCOMPLETE</span> 
								<span title=\"Click here to complete Debriefing...\" style=\"font-size:1.3em;font-weight:Bold;color:Gray;cursor:pointer;\" onclick=\"openWindow('debriefing.php?applicantno=$applicantno&print=1', 'debriefingfleet' ,0, 0);\">&nbsp;&nbsp;&nbsp;[ Goto Debriefing >> ]</span>"; 
					break;
					case 1	:	if($scholar == 1 || $fasttrack == 1)
								{
									if ($scholardate != "---")
									{
										$status = 1;
										$statusshow = "<span style=\"font-size:1.5em;font-weight:Bold;color:Green;\">COMPLETED</span>"; 
									}
									else
									{
										$status = 0;
										$statusshow = "<span style=\"font-size:1.5em;font-weight:Bold;color:Red;\">INCOMPLETE</span> 
												<span title=\"Click here to complete Debriefing...\" style=\"font-size:1.3em;font-weight:Bold;color:Gray;cursor:pointer;\" onclick=\"openWindow('debriefing.php?applicantno=$applicantno&print=1', 'debriefingfleet' ,0, 0);\">&nbsp;&nbsp;&nbsp;[ Goto Debriefing >> ]</span>"; 
									}
								}
								else
								{
									$status = 1;
									$statusshow = "<span style=\"font-size:1.5em;font-weight:Bold;color:Green;\">COMPLETED</span>"; 
								}
					break;
				}
				
				if (!empty($rowdebrieflist["FILLUPDATE"]))
					$fillupdate = date($dateformat,strtotime($rowdebrieflist["FILLUPDATE"]));
				else 
					$fillupdate = "---";
					
				$surrenderto = $rowdebrieflist["SURRENDERTO"];
				
				if (!empty($rowdebrieflist["SURRENDERDATE"]))
					$surrenderdate = date($dateformat,strtotime($rowdebrieflist["SURRENDERDATE"]));
				else 
					$surrenderdate = "---";
					
				if (!empty($rowdebrieflist["SURRENDERREMARKS"]))	
					$surrenderremarks = $rowdebrieflist["SURRENDERREMARKS"];
				else 
					$surrenderremarks = "&nbsp;";
					
				$scannedby = $rowdebrieflist["SCANNEDBY"];
				
				if (!empty($rowdebrieflist["SCANNEDDATE"]))
					$scanneddate = date($dateformat,strtotime($rowdebrieflist["SCANNEDDATE"]));
				else 
					$scanneddate = "---";
					
				if (!empty($rowdebrieflist["SCANNEDREMARKS"]))
					$scannedremarks = $rowdebrieflist["SCANNEDREMARKS"];
				else 
					$scannedremarks = "&nbsp;";
				
				$storedby = $rowdebrieflist["STOREDBY"];
				
				if (!empty($rowdebrieflist["STOREDDATE"]))
					$storeddate = date($dateformat,strtotime($rowdebrieflist["STOREDDATE"]));
				else 
					$storeddate = "---";
					
				if (!empty($rowdebrieflist["STOREDREMARKS"]))
					$storedremarks = $rowdebrieflist["STOREDREMARKS"];
				else 
					$storedremarks = "&nbsp;";
				
				$rem_ofcr = "";
				$rem_staff = "";	
			
				$qryjis = mysql_query("SELECT CCID,REMARKS_OFFICER,MADEBY_OFCR,MADEDATE_OFCR
									FROM debriefjislicense WHERE CCID=$ccid AND MADEDATE_OFCR IS NOT NULL") or die(mysql_error());
				
				while($rowjis = mysql_fetch_array($qryjis))
				{
					$ofcr_rem = $rowjis["REMARKS_OFFICER"];
					$ofcr_by = $rowjis["MADEBY_OFCR"];
					
					if (!empty($rowjis["MADEDATE_OFCR"]))
						$ofcr_date = date("dMY",strtotime($rowjis["MADEDATE_OFCR"]));
						
					if (!empty($ofcr_rem))
					{
						if (!empty($rem_ofcr))
							$rem_ofcr .= "<br />$ofcr_rem ($ofcr_by/$ofcr_date)";
						else
							$rem_ofcr = $ofcr_rem . "($ofcr_by/$ofcr_date)";
					}
				}
				
				$qryjisstaff = mysql_query("SELECT REMARKS_STAFF,MADEBY_STAFF,MADEDATE_STAFF
										FROM debriefjislicense WHERE CCID=$ccid and MADEDATE_STAFF IS NOT NULL") or die(mysql_error());
										
				while($rowjisstaff = mysql_fetch_array($qryjisstaff))
				{
					$staff_rem = $rowjisstaff["REMARKS_STAFF"];
					$staff_by = $rowjisstaff["MADEBY_STAFF"];
					
					if (!empty($rowjisstaff["MADEDATE_STAFF"]))
						$staff_date = date("dMY",strtotime($rowjisstaff["MADEDATE_STAFF"]));
					
					if (!empty($staff_rem))
					{
						if (!empty($rem_staff))
							$rem_staff .= "<br />" . $staff_rem . "($staff_by/$staff_date)";
						else
							$rem_staff = $staff_rem. "($staff_by/$staff_date)";
					}
				}
				
				// $updatedocby = $rowdebrieflist["UPDATEDOCBY"];
				
				// if (!empty($rowdebrieflist["UPDATEDOCDATE"]))
					// $updatedocdate = date($dateformat,strtotime($rowdebrieflist["UPDATEDOCDATE"]));
				// else 
					// $updatedocdate = "---";
					
				// if (!empty($rowdebrieflist["UPDATEDOCREMARKS"]))
					// $updatedocremarks = $rowdebrieflist["UPDATEDOCREMARKS"];
				// else 
					// $updatedocremarks = "&nbsp;";
					
				$fleetby = $rowdebrieflist["FLEETBY"];
				
				if (!empty($rowdebrieflist["FLEETDATE"]))
					$fleetdate = date($dateformat,strtotime($rowdebrieflist["FLEETDATE"]));
				else 
					$fleetdate = "---";
					
				if (!empty($rowdebrieflist["FLEETREMARKS"]))
					$fleetremarks = $rowdebrieflist["FLEETREMARKS"];
				else 
					$fleetremarks = "&nbsp;";
					
				$trainingby = $rowdebrieflist["TRAININGBY"];
				
				if (!empty($rowdebrieflist["TRAININGDATE"]))
					$trainingdate = date($dateformat,strtotime($rowdebrieflist["TRAININGDATE"]));
				else 
					$trainingdate = "---";
					
				if (!empty($rowdebrieflist["TRAININGREMARKS"]))
					$trainingremarks = $rowdebrieflist["TRAININGREMARKS"];
				else 
					$trainingremarks = "&nbsp;";
					
				$divisionby = $rowdebrieflist["DIVISIONBY"];
				
				if (!empty($rowdebrieflist["DIVISIONDATE"]))
					$divisiondate = date($dateformat,strtotime($rowdebrieflist["DIVISIONDATE"]));
				else 
					$divisiondate = "---";
					
				if (!empty($rowdebrieflist["DIVISIONREMARKS"]))
					$divisionremarks = $rowdebrieflist["DIVISIONREMARKS"];
				else 
					$divisionremarks = "&nbsp;";
					
				$accountingby = $rowdebrieflist["ACCOUNTINGBY"];
				
				if (!empty($rowdebrieflist["ACCOUNTINGDATE"]))
					$accountingdate = date($dateformat,strtotime($rowdebrieflist["ACCOUNTINGDATE"]));
				else 
					$accountingdate = "---";
					
				if (!empty($rowdebrieflist["ACCOUNTINGREMARKS"]))
					$accountingremarks = $rowdebrieflist["ACCOUNTINGREMARKS"];
				else 
					$accountingremarks = "&nbsp;";
					
				$managementby = $rowdebrieflist["MANAGEMENTBY"];
				
				if (!empty($rowdebrieflist["MANAGEMENTDATE"]))
					$managementdate = date($dateformat,strtotime($rowdebrieflist["MANAGEMENTDATE"]));
				else 
					$managementdate = "---";
					
				if (!empty($rowdebrieflist["MANAGEMENTREMARKS"]))
					$managementremarks = $rowdebrieflist["MANAGEMENTREMARKS"];
				else 
					$managementremarks = "&nbsp;";

				if ($status == $xstatus || $xstatus == 2)
				{
					if ($tmpreportdate != $reporteddate)
					{
						echo "
						<tr><td colspan=\"3\">&nbsp;</td></tr>
						<tr>
							<td style=\"font-size:1.4em;font-weight:Bold;color:Yellow;background-color:Gray;text-align:center;\" colspan=\"3\"><u>Date Reported - $reporteddate</u></td>
						</tr>
						<tr><td colspan=\"3\">&nbsp;</td></tr>
						";
					}
		
					$styleborder = "style=\"border-bottom:1px solid silver;\"";
					$stylename = "style=\"font-size:1.5em;font-weight:Bold;color:Blue;\"";
				

			echo "	<tr $mouseovereffect style=\"border:2px solid Black;\">
						<td $styleborder valign=\"top\">
							<span $stylename>$nameshow</span> <br /><hr /><br />
							<span style=\"font-size:1em;font-weight:Bold;\">
							Vessel: <span style=\"font-size:1.3em;color:Blue;\">$vesselname</span><br />
							Rank: <span style=\"font-size:1.1em;color:Blue;\">$rankalias</span><br /><br />
							Disembark: $datedisemb <br />
							Arrive Manila: $arrmnldate <br />
							Print By/Date: $printby/$printdate <br />
							Scholar: <span style=\"font-size:1.2em;color:Magenta;\">$scholartype</span> <br />
							</span>
							$statusshow
						</td>
						<td $styleborder valign=\"top\">
							<table width=\"100%\" style=\"font-size:1em;\">
								<tr>
									<td width=\"30%\" $styleborder><b>DOCS SURRENDERING</b> <br /> 
													$surrenderto / $surrenderdate</td>
									<td width=\"70%\" $styleborder  align=\"center\">$surrenderremarks</td>
								</tr>
								<tr>
									<td $styleborder><b>DOCS SCANNING</b> <br /> 
										$scannedby / $scanneddate
									</td>
									<td $styleborder align=\"center\">$scannedremarks</td>
								</tr>
								<tr>
									<td $styleborder><b>DOCS STORING</b><br /> 
										$storedby / $storeddate
									</td>
									<td $styleborder align=\"center\">$storedremarks</td>
								</tr>
								<tr>
									<td $styleborder><b>SCHOLAR/FASTTRACK</b><br /> 
										$scholarby / $scholardate
									</td>
									<td $styleborder align=\"center\">$scholarremarks</td>
								</tr>
								<tr>
									<td $styleborder><b>JIS CHECKING</b><br /> 
										$updatedocby / $updatedocdate
									</td>
									<td $styleborder align=\"left\">
										<b>OFFICER REMARKS:</b> $rem_ofcr <br /><br />
										<b>STAFF REMARKS:</b> $rem_staff
									</td>
								</tr>
								<tr>
									<td $styleborder><b>FLEET</b><br /> 
										$fleetby / $fleetdate
									</td>
									<td $styleborder align=\"center\">$fleetremarks</td>
								</tr>
								<tr>
									<td $styleborder><b>TRAINING</b> <br /> 
										$trainingby / $trainingdate
									</td>
									<td $styleborder align=\"center\">$trainingremarks</td>
								</tr>
								<tr>
									<td $styleborder><b>DIVISION</b> <br /> 
										$divisionby / $divisiondate
									</td>
									<td $styleborder align=\"center\">$divisionremarks</td>
								</tr>
								<tr>
									<td $styleborder><b>ACCOUNTING</b> <br /> 
										$accountingby / $accountingdate
									</td>
									<td $styleborder align=\"center\">$accountingremarks</td>
								</tr>
								<tr>
									<td $styleborder><b>MANAGEMENT</b> <br /> 
										$managementby / $managementdate
									</td>
									<td $styleborder align=\"center\">$managementremarks</td>
								</tr>
							</table>
						</td>
					</tr>
			";
					$tmpreportdate = $reporteddate;
				}
				
					
			}  //END OF WHILE
		echo "
			</table>
		</div>
		
		
		
	</div>

	<input type=\"hidden\" name=\"actiontxt\">
</form>
	
<form name=\"repdebriefingstatus\" action=\"repdebriefingstatus.php\" target=\"repdebriefingstatus\" method=\"POST\">
	<input type=\"hidden\" name=\"transdatefrom\">
	<input type=\"hidden\" name=\"transdateto\">
	<input type=\"hidden\" name=\"vesselselect\">
	<input type=\"hidden\" name=\"status2\">
</form>

</body>
</html>
";

?>