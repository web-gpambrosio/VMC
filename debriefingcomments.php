<?php

include('veritas/connectdb.php');
include("veritas/include/stylephp.inc");

$currentdate = date('Y-m-d H:i:s');
$currentdate = date('Y-m-d H:i:s');

$display1 = "display:none;";
$dateformat = "dMY";

if (isset($_GET['ccid']))
	$ccid = $_GET['ccid'];
	

$qrydebrieflist = mysql_query("SELECT dh.CCID,v.VESSEL,dh.REPORTEDDATE,dh.STATUS,dh.FILLUPDATE,dh.SURRENDERTO,dh.SURRENDERDATE,dh.SURRENDERREMARKS,
									dh.SCANNEDBY,dh.SCANNEDDATE,dh.SCANNEDREMARKS,dh.STOREDBY,dh.STOREDDATE,dh.STOREDREMARKS,
									dh.SCHOLARBY,dh.SCHOLARDATE,dh.SCHOLARREMARKS,dh.rehire_assesment,
									dh.FLEETBY,dh.FLEETDATE,dh.FLEETREMARKS,dh.TRAININGBY,dh.TRAININGDATE,dh.TRAININGREMARKS,
									dh.DIVISIONBY,dh.DIVISIONDATE,dh.DIVISIONREMARKS,dh.MANAGEMENTBY,dh.MANAGEMENTDATE,dh.MANAGEMENTREMARKS,
									dh.ACCOUNTINGBY,dh.ACCOUNTINGDATE,dh.ACCOUNTINGREMARKS,dh.PRINTBY,dh.PRINTDATE,r.ALIAS1 AS RANKALIAS,
									c.APPLICANTNO,c.CREWCODE,c.FNAME,c.GNAME,c.MNAME,dh.UPDATEDOCBY,dh.UPDATEDOCDATE,dh.UPDATEDOCREMARKS,
									IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,cc.ARRMNLDATE,
									IF(cs.APPLICANTNO IS NULL,0,1) AS SCHOLAR,s.DESCRIPTION AS SCHOLARTYPE
									FROM debriefinghdr dh
									LEFT JOIN crewchange cc ON cc.CCID=dh.CCID
									LEFT JOIN vessel v ON v.VESSELCODE=cc.VESSELCODE
									LEFT JOIN rank r ON r.RANKCODE=cc.RANKCODE
									LEFT JOIN crew c ON c.APPLICANTNO=cc.APPLICANTNO
									LEFT JOIN crewscholar cs ON cs.APPLICANTNO=cc.APPLICANTNO
									LEFT JOIN scholar s ON s.SCHOLASTICCODE = cs.SCHOLASTICCODE
									WHERE dh.CCID=$ccid
									ORDER BY dh.REPORTEDDATE,c.FNAME
								") or die(mysql_error());
	

echo "
<html>

<!-- HEAD START -->
<head>
	<title>Debriefing Comments</title>
	<script language=\"javascript\" src=\"popcalendar.js\"></script>	
	<script language=\"javascript\" src=\"veripro.js\"></script>	
	<link rel=\"stylesheet\" href=\"veripro.css\">

	<script>

				
	</script>
	
</head>

<body>

<form name=\"debriefingcomments\" method=\"POST\">

	<center>
	<div style=\"width:100%;padding:2px;border:1px solid black;\">
	
		<span class=\"wintitle\">DEBRIEFING COMMENTS</span>

		
		<div style=\"width:100%;padding:2px;\">
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
				if (!empty($rowdebrieflist["SCHOLARTYPE"]))
					$scholartype = $rowdebrieflist["SCHOLARTYPE"];
				else
					$scholartype = "---";
				
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
				
				switch ($rowdebrieflist["STATUS"])
				{
					case 0	:	$status = "INCOMPLETE"; break;
					case 1	:	$status = "COMPLETED"; break;
					default	:	$status = "NOT STARTED YET"; break;
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
		
				
				$rehire_assesment = $rowdebrieflist["rehire_assesment"];
				
				switch ($rehire_assesment)
				{
					case "0": $r_assesment = ""; $r_color = ""; break;
					case "1": $r_assesment = "FOR <br> REHIRE"; $r_color = "color:#0000FF;"; break;
					case "2": $r_assesment = "FOR <br> MANAGEMENT ASSESSMENT"; $r_color = "color:#FF0000;"; break;
				}
			
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

				$scholarby = $rowdebrieflist["SCHOLARBY"];
				
				if (!empty($rowdebrieflist["SCHOLARDATE"]))
					$scholardate = date($dateformat,strtotime($rowdebrieflist["SCHOLARDATE"]));
				else 
					$scholardate = "---";
					
				if (!empty($rowdebrieflist["SCHOLARREMARKS"]))
					$scholarremarks = $rowdebrieflist["SCHOLARREMARKS"];
				else 
					$scholarremarks = "&nbsp;";
		
				if ($tmpreportdate != $reporteddate)
				{
					echo "
					<tr>
						<td style=\"font-size:1.4em;font-weight:Bold;color:Yellow;background-color:Gray;text-align:center;\" colspan=\"2\"><u>Date Reported - $reporteddate</u></td>
					</tr>
					<tr><td colspan=\"2\">&nbsp;</td></tr>
					";
				}
		
				$styleborder = "style=\"border-bottom:1px solid silver;\"";
				$stylename = "style=\"font-size:1.3em;font-weight:Bold;color:Blue;\"";
				
		echo "	<tr $mouseovereffect>
					<td $styleborder valign=\"top\">
						<span $stylename>$nameshow</span> <br /><hr /><br />
						<span style=\"font-size:1em;font-weight:Bold;\">
						Vessel: <span style=\"font-size:1.3em;color:Blue;\">$vesselname</span><br />
						Rank: <span style=\"font-size:1.1em;color:Blue;\">$rankalias</span><br /><br />
						Disembark: $datedisemb <br />
						Arrive Manila: $arrmnldate<br />
						Print By/Date: $printby/$printdate<br />
						Scholar: <span style=\"font-size:1.2em;color:Magenta;\">$scholartype</span> <br /><br />
						<div style=\"font-size:24px; ".$r_color."\"><strong>".$r_assesment."</strong></div>
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
								<td $styleborder><b>SCHOLAR/FASTTRACK </b><br /> 
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
				<tr>
					<table>
						<tr>
							<td>
								<input type=\"button\" value=\"CER\" 
									style=\"border:1px solid Black;background-color:Red;font-size:0.6em;color:Yellow;font-weight:Bold;cursor:pointer;\"
									onclick=\"javascript:openWindow('crewevaluationshow.php?ccid=$ccid', 'showform', 900, 600);\" />
							</td>  
							<td>
								<input type=\"button\" value=\"DEBRIEFING (FM-227)\" 
									style=\"border:1px solid Black;background-color:Red;font-size:0.6em;color:Yellow;font-weight:Bold;cursor:pointer;\"
									onclick=\"javascript:openWindow('debriefingformshow.php?applicantno=$applicantno&ccid=$ccid&load=1', 'showform', 900, 600);\" />
							</td>
							<td>
								<input type=\"button\" value=\"CLEARANCE (FM-229)\" 
									style=\"border:1px solid Black;background-color:Red;font-size:0.6em;color:Yellow;font-weight:Bold;cursor:pointer;\"
									onclick=\"javascript:openWindow('debriefing_clearanceformshow.php?applicantno=$applicantno&ccid=$ccid&load=1', 'showform', 900, 600);\" />
							</td>
							<td>
								<input type=\"button\" value=\"DATA SHEET\" 
									style=\"border:1px solid Black;background-color:Red;font-size:0.6em;color:Yellow;font-weight:Bold;cursor:pointer;\"
									onclick=\"javascript:openWindow('repcrewdatasheet.php?applicantno=$applicantno&print=0', 'showform', 900, 600);\" />
							</td>
						</tr>
					</table>
				</tr>
		";
				$tmpreportdate = $reporteddate;
			}
		echo "
			</table>
		</div>
		
	</div>

	<input type=\"hidden\" name=\"actiontxt\">
</form>
	
</body>
</html>
";

?>