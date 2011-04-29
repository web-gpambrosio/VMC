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

if (isset($_POST['status2']))
	$xstatus = $_POST['status2'];

if (isset($_POST['vesselselect']))
	$vesselselect = $_POST['vesselselect'];

			
	$transdatefromraw = date('Y-m-d',strtotime($transdatefrom));
	$transdatetoraw = date('Y-m-d',strtotime($transdateto));
	
	if (!empty($vesselselect))
		$wherevessel = "AND cc.VESSELCODE='$vesselselect'";
	else
		$wherevessel = "";
	
	// $qrydebrieflist = mysql_query("SELECT dh.CCID,dh.REPORTEDDATE,dh.STATUS,dh.FILLUPDATE,dh.SURRENDERTO,dh.SURRENDERDATE,dh.SURRENDERREMARKS,
										// dh.SCANNEDBY,dh.SCANNEDDATE,dh.SCANNEDREMARKS,dh.STOREDBY,dh.STOREDDATE,dh.STOREDREMARKS,
										// dh.FLEETBY,dh.FLEETDATE,dh.FLEETREMARKS,dh.TRAININGBY,dh.TRAININGDATE,dh.TRAININGREMARKS,
										// dh.DIVISIONBY,dh.DIVISIONDATE,dh.DIVISIONREMARKS,dh.MANAGEMENTBY,dh.MANAGEMENTDATE,dh.MANAGEMENTREMARKS,
										// dh.ACCOUNTINGBY,dh.ACCOUNTINGDATE,dh.ACCOUNTINGREMARKS,dh.PRINTBY,dh.PRINTDATE,
										// c.APPLICANTNO,c.CREWCODE,c.FNAME,c.GNAME,c.MNAME,
										// IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,cc.ARRMNLDATE
										// FROM debriefinghdr dh
										// LEFT JOIN crewchange cc ON cc.CCID=dh.CCID
										// LEFT JOIN crew c ON c.APPLICANTNO=cc.APPLICANTNO
										// WHERE REPORTEDDATE BETWEEN '$transdatefromraw 00:00:00' AND '$transdatetoraw 23:59:59'
										// $wherestatus
										// ORDER BY dh.REPORTEDDATE,c.FNAME
									// ") or die(mysql_error());

			$qrydebrieflist = mysql_query("SELECT dh.CCID,v.VESSEL,dh.REPORTEDDATE,dh.FILLUPDATE,dh.SURRENDERTO,dh.SURRENDERDATE,dh.SURRENDERREMARKS,
												dh.SCANNEDBY,dh.SCANNEDDATE,dh.SCANNEDREMARKS,dh.STOREDBY,dh.STOREDDATE,dh.STOREDREMARKS,
												dh.UPDATEDOCBY,dh.UPDATEDOCDATE,dh.UPDATEDOCREMARKS,
												dh.FLEETBY,dh.FLEETDATE,dh.FLEETREMARKS,dh.TRAININGBY,dh.TRAININGDATE,dh.TRAININGREMARKS,
												dh.DIVISIONBY,dh.DIVISIONDATE,dh.DIVISIONREMARKS,dh.MANAGEMENTBY,dh.MANAGEMENTDATE,dh.MANAGEMENTREMARKS,
												dh.ACCOUNTINGBY,dh.ACCOUNTINGDATE,dh.ACCOUNTINGREMARKS,dh.PRINTBY,dh.PRINTDATE,
												c.APPLICANTNO,c.CREWCODE,c.FNAME,c.GNAME,c.MNAME,
												IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,cc.ARRMNLDATE,
												IF(
												dh.FILLUPDATE IS NOT NULL AND
												dh.SURRENDERDATE IS NOT NULL AND
												dh.SCANNEDDATE IS NOT NULL AND
												dh.STOREDDATE IS NOT NULL AND
												dh.FLEETDATE IS NOT NULL AND
												dh.TRAININGDATE IS NOT NULL AND
												(dh.DIVISIONDATE IS NOT NULL OR dh.MANAGEMENTDATE IS NOT NULL) AND
												dh.ACCOUNTINGDATE IS NOT NULL,1,0) AS DEBRIEFSTATUS
												FROM debriefinghdr dh
												LEFT JOIN crewchange cc ON cc.CCID=dh.CCID
												LEFT JOIN vessel v ON v.VESSELCODE=cc.VESSELCODE
												LEFT JOIN crew c ON c.APPLICANTNO=cc.APPLICANTNO
												WHERE REPORTEDDATE BETWEEN '$transdatefromraw 00:00:00' AND '$transdatetoraw 23:59:59'
												$wherevessel
												ORDER BY dh.REPORTEDDATE,c.FNAME
											") or die(mysql_error());



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
	<div style=\"width:750px;height:600px;padding:2px;border:1px solid black;\">
	
		<span style=\"font-size:1.5em;font-weight:Bold;color:Green;\">Debriefing Status Report</span><br /><br />
		<b>From $transdatefrom to $transdateto</b>
		<br />
		<hr />
		
		<div style=\"width:100%;height:400px;padding:2px;\">
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
				
				if (!empty($rowdebrieflist["DATEDISEMB"]))
					$datedisemb = date($dateformat,strtotime($rowdebrieflist["DATEDISEMB"]));
				else 
					$datedisemb = "---";
				
				if (!empty($rowdebrieflist["ARRMNLDATE"]))
					$arrmnldate = date($dateformat,strtotime($rowdebrieflist["DATEDISEMB"]));
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
				
				switch ($rowdebrieflist["DEBRIEFSTATUS"])
				{
					case 0	:	$status = 0;$statusshow = "<span style=\"font-size:1.5em;font-weight:Bold;color:Red;\">INCOMPLETE</span>"; 
					break;
					case 1	:	$status = 1;$statusshow = "<span style=\"font-size:1.5em;font-weight:Bold;color:Green;\">COMPLETED</span>"; break;
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
							Vessel: <span style=\"font-size:1.3em;color:Blue;\">$vesselname</span><br /><br />
							Disembark: $datedisemb <br />
							Arrive Manila: $arrmnldate <br />
							Print By/Date: $printby/$printdate <br />
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
									<td $styleborder><b>JIS CHECKING</b><br /> 
										$updatedocby / $updatedocdate
									</td>
									<td $styleborder align=\"center\">$updatedocremarks</td>
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
				
			}
		echo "
			</table>
		</div>
		
		
		
	</div>

	<input type=\"hidden\" name=\"actiontxt\">
</form>
	
";
		
	include('include/printclose.inc');
	
echo "
</body>
</html>
";

?>