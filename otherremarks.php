<?php
include('veritas/connectdb.php');
include("veritas/include/stylephp.inc");
include("veritas/include/functions.inc");

session_start();

if(isset($_SESSION['employeeid']))
	$employeeid=$_SESSION['employeeid'];
	
$currentdate = date("Y-m-d H:i:s");
$datenow = date("m/d/Y");

if(isset($_POST['ccid']))
	$ccid=$_POST['ccid'];
else 
	$ccid=$_GET['ccid'];
	
if(isset($_POST['applicantno']))
	$applicantno=$_POST['applicantno'];
else 
	$applicantno=$_GET['applicantno'];

	
$qrygetremarks = mysql_query("SELECT FLEETBY,FLEETDATE,FLEETREMARKS,TRAININGBY,TRAININGDATE,TRAININGREMARKS,DIVISIONBY,DIVISIONDATE,DIVISIONREMARKS,
								MANAGEMENTBY,MANAGEMENTDATE,MANAGEMENTREMARKS,UPDATEDOCBY,UPDATEDOCDATE,UPDATEDOCREMARKS,
								ACCOUNTINGBY,ACCOUNTINGDATE,ACCOUNTINGREMARKS,SURRENDERTO,SURRENDERDATE,SURRENDERREMARKS,
								SCANNEDBY,SCANNEDDATE,SCANNEDREMARKS,STOREDBY,STOREDDATE,STOREDREMARKS,
								SCHOLARBY,SCHOLARDATE,SCHOLARREMARKS,
								dsh.SURRENDERED_41,dsh.SURRENDERED_F2,dsh.SURRENDERED_P2,dsh.SURRENDERED_42,dsh.SURRENDERED_32,dsh.SURRENDERED_18,dsh.SURRENDERED_C0,
								dsh.SCANNED_41,dsh.SCANNED_F2,dsh.SCANNED_P2,dsh.SCANNED_42,dsh.SCANNED_32,dsh.SCANNED_18,dsh.SCANNED_C0,
								dsh.STORED_41,dsh.STORED_F2,dsh.STORED_P2,dsh.STORED_42,dsh.STORED_32,dsh.STORED_18,dsh.STORED_C0
							FROM debriefinghdr d
							LEFT JOIN docsurrenderhdr dsh ON dsh.CCID=d.CCID
							WHERE d.CCID=$ccid
							") or die(mysql_error());

$rowgetremarks = mysql_fetch_array($qrygetremarks);

$surrenderto = $rowgetremarks["SURRENDERTO"];
$surrenderdate = $rowgetremarks["SURRENDERDATE"];
$surrenderremarks = $rowgetremarks["SURRENDERREMARKS"];

$SURRENDERED_41 = $rowgetremarks["SURRENDERED_41"];
$SURRENDERED_F2 = $rowgetremarks["SURRENDERED_F2"];
$SURRENDERED_P2 = $rowgetremarks["SURRENDERED_P2"];
$SURRENDERED_42 = $rowgetremarks["SURRENDERED_42"];
$SURRENDERED_32 = $rowgetremarks["SURRENDERED_32"];
$SURRENDERED_18 = $rowgetremarks["SURRENDERED_18"];
$SURRENDERED_C0 = $rowgetremarks["SURRENDERED_C0"];

$scannedby = $rowgetremarks["SCANNEDBY"];
$scanneddate = $rowgetremarks["SCANNEDDATE"];
$scannedremarks = $rowgetremarks["SCANNEDREMARKS"];

$storedby = $rowgetremarks["STOREDBY"];
$storeddate = $rowgetremarks["STOREDDATE"];
$storedremarks = $rowgetremarks["STOREDREMARKS"];

$scholarby = $rowgetremarks["SCHOLARBY"];
$scholardate = $rowgetremarks["SCHOLARDATE"];
$scholarremarks = $rowgetremarks["SCHOLARREMARKS"];

$fleetby = $rowgetremarks["FLEETBY"];
$fleetdate = $rowgetremarks["FLEETDATE"];
$fleetremarks = $rowgetremarks["FLEETREMARKS"];
$trainingby = $rowgetremarks["TRAININGBY"];
$trainingdate = $rowgetremarks["TRAININGDATE"];
$trainingremarks = $rowgetremarks["TRAININGREMARKS"];
$divisionby = $rowgetremarks["DIVISIONBY"];
$divisiondate = $rowgetremarks["DIVISIONDATE"];
$divisionremarks = $rowgetremarks["DIVISIONREMARKS"];
$managementby = $rowgetremarks["MANAGEMENTBY"];
$managementdate = $rowgetremarks["MANAGEMENTDATE"];
$managementremarks = $rowgetremarks["MANAGEMENTREMARKS"];
$updatedocby = $rowgetremarks["UPDATEDOCBY"];
$updatedocdate = $rowgetremarks["UPDATEDOCDATE"];
$updatedocremarks = $rowgetremarks["UPDATEDOCREMARKS"];
$accountingby = $rowgetremarks["ACCOUNTINGBY"];
$accountingdate = $rowgetremarks["ACCOUNTINGDATE"];
$accountingremarks = $rowgetremarks["ACCOUNTINGREMARKS"];


$qrygetremarks2 = mysql_query("SELECT ENDORSEDBY,ENDORSEDDATE,REMARKS,STATUS
							FROM usvisaendorsement
							WHERE APPLICANTNO=$applicantno AND STATUS IS NULL
							") or die(mysql_error());

$rowgetremarks2 = mysql_fetch_array($qrygetremarks2);

$visaendorsedby = $rowgetremarks2["ENDORSEDBY"];
$visaendorseddate = $rowgetremarks2["ENDORSEDDATE"];
$visaremarks = $rowgetremarks2["REMARKS"];
$visastatus = $rowgetremarks2["STATUS"];

echo "
<html>\n
<head>\n
<title></title>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>

<body onload=\"\" style=\"overflow:hidden;background-color:#FFFFBB;overflow:auto;\">\n

<form name=\"debriefremarks\" method=\"POST\">\n
	<br />
	<center>
		<table style=\"width:95%;font-size:0.9em;\" cellspacing=\"0\" cellpadding=\"3\" border=1>
			<tr>
				<th width=\"30%\" style=\"background-color:Navy;color:White\">ACTIVITY</th>
				<th width=\"10%\" style=\"background-color:Navy;color:White\">BY</th>
				<th width=\"60%\" style=\"background-color:Navy;color:White\">REMARKS</th>
			</tr>
<!--
			<tr>
				<td colspan=\"3\">&nbsp;</td>
			</tr>
-->
			<tr $mouseovereffect>
				<td  valign=\"top\">DOCUMENT SURRENDERING</td>
				<td  valign=\"top\" align=\"center\">$surrenderto&nbsp;</td>
				<td  valign=\"top\">$surrenderremarks&nbsp;</td>
			</tr>
			<tr $mouseovereffect>
				<td  valign=\"top\">DOCUMENT SCANNING</td>
				<td  valign=\"top\" align=\"center\">$scannedby&nbsp;</td>
				<td  valign=\"top\">$scannedremarks&nbsp;</td>
			</tr>
			<tr $mouseovereffect>
				<td  valign=\"top\">DOCUMENT STORING</td>
				<td  valign=\"top\" align=\"center\">$storedby&nbsp;</td>
				<td  valign=\"top\">$storedremarks&nbsp;</td>
			</tr>
			<tr $mouseovereffect>
				<td  valign=\"top\">SCHOLAR/FASTTRACK</td>
				<td  valign=\"top\" align=\"center\">$scholarby&nbsp;</td>
				<td  valign=\"top\">$scholarremarks&nbsp;</td>
			</tr>
			<tr $mouseovereffect>
				<td  valign=\"top\">CHECKING JIS LICENSE</td>
				<td  valign=\"top\" align=\"center\">$updatedocby&nbsp;</td>
				<td  valign=\"top\">$updatedocremarks&nbsp;</td>
			</tr>
			<tr $mouseovereffect>
				<td  valign=\"top\">U.S. VISA ENDORSEMENT</td>
				<td  valign=\"top\" align=\"center\">$visaendorsedby&nbsp;</td>
				<td  valign=\"top\">$visaremarks&nbsp;</td>
			</tr>
			<tr $mouseovereffect>
				<td  valign=\"top\">FLEET</td>
				<td  valign=\"top\" align=\"center\">$fleetby&nbsp;</td>
				<td  valign=\"top\">$fleetremarks&nbsp;</td>
			</tr>
			<tr $mouseovereffect>
				<td  valign=\"top\">TRAINING</td>
				<td  valign=\"top\" align=\"center\">$trainingby&nbsp;</td>
				<td  valign=\"top\">$trainingremarks&nbsp;</td>
			</tr>
			<tr $mouseovereffect>
				<td  valign=\"top\">DIVISION</td>
				<td  valign=\"top\" align=\"center\">$divisionby&nbsp;</td>
				<td  valign=\"top\">$divisionremarks&nbsp;</td>
			</tr>
			<tr $mouseovereffect>
				<td  valign=\"top\">ACCOUNTING</td>
				<td  valign=\"top\" align=\"center\">$accountingby&nbsp;</td>
				<td  valign=\"top\">$accountingremarks&nbsp;</td>
			</tr>
			<tr $mouseovereffect>
				<td  valign=\"top\">MANAGEMENT</td>
				<td  valign=\"top\" align=\"center\">$managementby&nbsp;</td>
				<td  valign=\"top\">$managementremarks&nbsp;</td>
			</tr>
		</table>
	</center>

</form>

</body>

</html>


";