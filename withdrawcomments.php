<?php

include('veritas/connectdb.php');
include("veritas/include/stylephp.inc");
include("veritas/include/functions.inc");

$currentdate = date('Y-m-d H:i:s');

$display1 = "display:none;";
$dateformat = "dMY";

if (isset($_GET['applicantno']))
	$applicantno = $_GET['applicantno'];
	
if(isset($_POST['idno']))
	$idno=$_POST['idno'];
else 
	$idno=$_GET['idno'];
	

$qrywithdrawal = mysql_query("SELECT cw.*,c.FNAME,c.GNAME,c.MNAME
								FROM crewwithdrawal cw
								LEFT JOIN crew c ON c.APPLICANTNO=cw.APPLICANTNO
								WHERE cw.APPLICANTNO=$applicantno AND cw.IDNO=$idno") or die(mysql_error());
	
$rowwithdrawal = mysql_fetch_array($qrywithdrawal);

	$fname = $rowwithdrawal["FNAME"];
	$gname = $rowwithdrawal["GNAME"];
	$mname = $rowwithdrawal["MNAME"];
	
	$operationshow = "---";
	$operationsremarks = "&nbsp;";
	
	$fleetshow = "---";
	$fleetremarks = "&nbsp;";
	
	$trainingshow = "---";
	$trainingremarks = "&nbsp;";
	
	$divisionshow = "---";
	$divisionremarks = "&nbsp;";
	
	$mgmtshow = "---";
	$mgmtremarks = "&nbsp;";
	
	$accountingshow = "---";
	$accountingremarks = "&nbsp;";
	
	$surrendershow = "---";
	$surrenderremarks = "&nbsp;";

	$safekeepshow = "---";
	$safekeepremarks = "&nbsp;";
	
	if (!empty($rowwithdrawal["FORMDATE"]))
	{
		$formdate = date("F d, Y",strtotime($rowwithdrawal["FORMDATE"]));
	}
	else
		$formdate = "";
	
	if (!empty($rowwithdrawal["OPERATIONSBY"]))
		$operationsby = $rowwithdrawal["OPERATIONSBY"];
		
	if (!empty($rowwithdrawal["OPERATIONSDATE"]))
		$operationsdate = date($dateformat,strtotime($rowwithdrawal["OPERATIONSDATE"]));
	else
		$operationsdate = "&nbsp;";
		
	$operationshow = $operationsby . " / " . $operationsdate;
	if (!empty($rowwithdrawal["OPERATIONSREMARKS"]))
		$operationsremarks = $rowwithdrawal["OPERATIONSREMARKS"];
	else
		$operationsremarks = "&nbsp;";
	
	if (!empty($rowwithdrawal["FLEETBY"]))
		$fleetby = $rowwithdrawal["FLEETBY"];
		
	if (!empty($rowwithdrawal["FLEETDATE"]))
		$fleetdate = date($dateformat,strtotime($rowwithdrawal["FLEETDATE"]));
	else
		$fleetdate = "&nbsp;";
		
	$fleetshow = $fleetby . " / " . $fleetdate;
	if (!empty($rowwithdrawal["FLEETREMARKS"]))
		$fleetremarks = $rowwithdrawal["FLEETREMARKS"];
	else
		$fleetremarks = "&nbsp;";
	
	if (!empty($rowwithdrawal["TRAININGBY"]))
		$trainingby = $rowwithdrawal["TRAININGBY"];
		
	if (!empty($rowwithdrawal["TRAININGDATE"]))
		$trainingdate = date($dateformat,strtotime($rowwithdrawal["TRAININGDATE"]));
	else
		$trainingdate = "&nbsp;";
		
	$trainingshow = $trainingby . " / " . $trainingdate;
	if (!empty($rowwithdrawal["TRAININGREMARKS"]))
		$trainingremarks = $rowwithdrawal["TRAININGREMARKS"];
	else
		$trainingremarks = "&nbsp;";
	
	if (!empty($rowwithdrawal["ADMINBY"]))
		$adminby = $rowwithdrawal["ADMINBY"];
		
	if (!empty($rowwithdrawal["ADMINDATE"]))
		$admindate = date($dateformat,strtotime($rowwithdrawal["ADMINDATE"]));
	else
		$admindate = "&nbsp;";
		
	$adminshow = $adminby . " / " . $admindate;
	if (!empty($rowwithdrawal["ADMINREMARKS"]))
		$adminremarks = $rowwithdrawal["ADMINREMARKS"];
	else
		$adminremarks = "&nbsp;";
	
	if (!empty($rowwithdrawal["DIVISIONBY"]))
		$divisionby = $rowwithdrawal["DIVISIONBY"];
		
	if (!empty($rowwithdrawal["DIVISIONDATE"]))
		$divisiondate = date($dateformat,strtotime($rowwithdrawal["DIVISIONDATE"]));
	else
		$divisiondate = "&nbsp;";

	$divisionshow = $divisionby . " / " . $divisiondate;
	if (!empty($rowwithdrawal["DIVISIONREMARKS"]))
		$divisionremarks = $rowwithdrawal["DIVISIONREMARKS"];
	else
		$divisionremarks = "&nbsp;";
	
	if (!empty($rowwithdrawal["MGMTBY"]))
		$mgmtby = $rowwithdrawal["MGMTBY"];
		
	if (!empty($rowwithdrawal["MGMTDATE"]))
		$mgmtdate = date($dateformat,strtotime($rowwithdrawal["MGMTDATE"]));
	else
		$mgmtdate = "&nbsp;";

	$mgmtshow = $mgmtby . " / " . $mgmtdate;
	if (!empty($rowwithdrawal["MGMTREMARKS"]))
		$mgmtremarks = $rowwithdrawal["MGMTREMARKS"];
	else
		$mgmtremarks = "&nbsp;";
	
	if (!empty( $rowwithdrawal["ACCOUNTINGBY"]))
		$accountingby = $rowwithdrawal["ACCOUNTINGBY"];
		
	if (!empty($rowwithdrawal["ACCOUNTINGDATE"]))
		$accountingdate = date($dateformat,strtotime($rowwithdrawal["ACCOUNTINGDATE"]));
	else
		$accountingdate = "&nbsp;";

	$accountingshow = $accountingby . " / " . $accountingdate;
	if (!empty($rowwithdrawal["ACCOUNTINGREMARKS"]))
		$accountingremarks = $rowwithdrawal["ACCOUNTINGREMARKS"];
	else
		$accountingremarks = "&nbsp;";
	
	if (!empty($rowwithdrawal["SURRENDERTO"]))
		$surrenderto = $rowwithdrawal["SURRENDERTO"];
		
	if (!empty($rowwithdrawal["SURRENDERDATE"]))
		$surrenderdate = date($dateformat,strtotime($rowwithdrawal["SURRENDERDATE"]));
	else
		$surrenderdate = "&nbsp;";

	$surrendershow = $surrenderto . " / " . $surrenderdate;
	if (!empty($rowwithdrawal["SURRENDERREMARKS"]))
		$surrenderremarks = $rowwithdrawal["SURRENDERREMARKS"];
	else
		$surrenderremarks = "&nbsp;";
	
	if (!empty($rowwithdrawal["SAFEKEEPBY"]))
		$safekeepby = $rowwithdrawal["SAFEKEEPBY"];
		
	if (!empty($rowwithdrawal["SAFEKEEPDATE"]))
		$safekeepdate = date($dateformat,strtotime($rowwithdrawal["SAFEKEEPDATE"]));
	else
		$safekeepdate = "&nbsp;";

	$safekeepshow = $safekeepby . " / " . $safekeepdate;
	if (!empty($rowwithdrawal["SAFEKEEPREMARKS"]))
		$safekeepremarks = $rowwithdrawal["SAFEKEEPREMARKS"];
	else
		$safekeepremarks = "&nbsp;";

	$widno = $rowwithdrawal["IDNO"];
		
$disableclearance = "disabled=\"disabled\"";

$openwindow = "";
$serverdirtmp="docimg/"; 


$dir = $serverdirtmp . $applicantno . "/CrewWithdrawalForm" . "_$widno" . ".pdf";
if (checkpath($dir,1))
{
	$viewform = "<a href=\"#\" onclick=\"openWindow('$dir', 'viewdoc' ,900, 600);\" style=\"font-size:0.9em;font-weight:Bold;color:Green;\" >
		[ View Withdrawal Form ] </a>";
}
else
{

	$viewform = "<span style=\"font-size:0.9em;font-weight:Bold;color:Red;\">[NOT UPLOADED YET]</span>";
}


echo "
<html>

<!-- HEAD START -->
<head>
	<title>Withdrawal Comments</title>
	<script language=\"javascript\" src=\"popcalendar.js\"></script>	
	<script language=\"javascript\" src=\"veripro.js\"></script>	
	<link rel=\"stylesheet\" href=\"veripro.css\">

	<script>

				
	</script>
	
</head>

<body>

<form name=\"withdrawalcomments\" method=\"POST\">

	<center>
	<div style=\"width:100%;padding:2px;border:1px solid black;\">
	
		<span class=\"wintitle\">CREW WITHDRAWAL COMMENTS</span>

		
		<div style=\"width:100%;padding:2px;\">
		";
echo "
		<table style=\"width:100%;font-size:0.8em;\" border=1 cellspacing=\"0\">
			<tr>
				<th width=\"35%\"><u>ACTIVITY</u></th>
				<th width=\"20%\"><u>DATE/BY</u></th>
				<th width=\"45%\"><u>REMARKS</u></th>
			</tr>
			<tr>
				<td colspan=\"3\">&nbsp;</td>
			</tr>
			<tr $mouseovereffect>
				<td><b>Issue Withdrawal Form</b></td>
				<td>&nbsp;</td>
				<td align=\"center\">
				";
				
				// if (empty($formdate))
				// {
					// echo "<input type=\"button\" value=\"Create / Issue Form\" style=\"color:Blue;font-weight:Bold;font-size:1em;\" 
								// onclick=\"selection.value='0';actiontxt.value='saveremarks';submit();\" />";		
				// }
				// else
				// {
					echo "<span style=\"color:Red;font-size:1.3em;font-weight:Bold;\">$formdate</span>";
				// }
				
				echo "
				</td>
			</tr>
			<tr $mouseovereffect>
				<td><b>Operations</b></td>
				<td align=\"center\" $styleby>$operationshow</td>
				<td align=\"center\">$operationsremarks</td>
			</tr>
			<tr $mouseovereffect>
				<td><b>Training</b></td>
				<td align=\"center\" $styleby>$trainingshow</td>
				<td align=\"center\">$trainingremarks</td>
			</tr>
			<tr $mouseovereffect>
				<td><b>Administration</b></td>
				<td align=\"center\" $styleby>$adminshow</td>
				<td align=\"center\">$adminremarks</td>
			</tr>
			<tr $mouseovereffect>
				<td><b>Accounting/Finance</b></td>
				<td align=\"center\" $styleby>$accountingshow</td>
				<td align=\"center\">$accountingremarks</td>
			</tr>
			<tr $mouseovereffect>
				<td><b>Fleet</b></td>
				<td align=\"center\" $styleby>$fleetshow</td>
				<td align=\"center\">$fleetremarks</td>
			</tr>
			<tr $mouseovereffect>
				<td><b>Division</b></td>
				<td align=\"center\" $styleby>$divisionshow</td>
				<td align=\"center\">$divisionremarks</td>
			</tr>
			<tr $mouseovereffect>
				<td><b>Management</b></td>
				<td align=\"center\" $styleby>$mgmtshow</td>
				<td align=\"center\">$mgmtremarks</td>
			</tr>
			<tr $mouseovereffect>
				<td><b>Withdrawal Form Surrender</b></td>
				<td align=\"center\" $styleby>$surrendershow</td>
				<td align=\"center\">$surrenderremarks</td>
			</tr>
			<tr $mouseovereffect>
				<td><b>MIS Safekeep</b></td>
				<td align=\"center\" $styleby>$safekeepshow</td>
				<td align=\"center\">$safekeepremarks</td>
			</tr>
		</table>
		
		<br /><br />
		
		<center>$viewform</center>
		
		<br /><br />
		
	</div>

	<input type=\"hidden\" name=\"actiontxt\">
</form>
	
</body>
</html>
";

?>