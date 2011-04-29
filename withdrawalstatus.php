<?php
include('veritas/connectdb.php');
include("veritas/include/stylephp.inc");
include("veritas/include/functions.inc");

session_start();

$basedirdocs = "docimg/";

if(isset($_SESSION['employeeid']))
	$employeeid=$_SESSION['employeeid'];
	
//if (isset($_SESSION['departmentid']))
//	$departmentid = $_SESSION['departmentid'];

$qrydepartment = mysql_query("SELECT DEPARTMENTID FROM employee WHERE EMPLOYEEID='$employeeid'") or die(mysql_error());
$rowdepartment = mysql_fetch_array($qrydepartment);
$departmentid = $rowdepartment["DEPARTMENTID"];

	
$currentdate = date("Y-m-d H:i:s");
$datenow = date("m/d/Y");
$dateformat = "dMY";
	
if(isset($_POST["applicantno"]))
	$applicantno=$_POST["applicantno"];
else 
	$applicantno=$_GET["applicantno"];
	
if(isset($_POST["idno"]))
	$idno=$_POST["idno"];
else 
	$idno=$_GET["idno"];
	
if(isset($_POST["actiontxt"]))
	$actiontxt=$_POST["actiontxt"];
	
if(isset($_POST["selection"]))
	$selection=$_POST["selection"];
	
if(isset($_POST["clearanceremarks"]))
	$clearanceremarks=$_POST["clearanceremarks"];
	
if(isset($_POST["formdate"]))
	$formdate=$_POST["formdate"];
else
	$formdate = "";
	


	
	
switch ($actiontxt)
{
	case "saveremarks"	:
	
			switch ($selection)
			{
				case "0":  //FORM ISSUANCE
					// echo "INSERT INTO crewwithdrawal(APPLICANTNO,DATE) VALUES($applicantno,'$currentdate')";
					$qryinsert = mysql_query("INSERT INTO crewwithdrawal(APPLICANTNO,FORMDATE) VALUES($applicantno,'$currentdate')") or die(mysql_errno());
					
				break;
				case "1":  //OPERATIONS
					$qryupdateremarks = mysql_query("UPDATE crewwithdrawal SET OPERATIONSBY = '$employeeid',
																			OPERATIONSDATE = '$currentdate',
																			OPERATIONSREMARKS = '$clearanceremarks'
													WHERE APPLICANTNO = '$applicantno'
													") or die(mysql_errno());
				break;
				case "2":  //TRAINING
					$qryupdateremarks = mysql_query("UPDATE crewwithdrawal SET TRAININGBY = '$employeeid',
																			TRAININGDATE = '$currentdate',
																			TRAININGREMARKS = '$clearanceremarks'
													WHERE APPLICANTNO = '$applicantno'
													") or die(mysql_errno());
				break;
				case "3":  //ACCOUNTING/FINANCE
					$qryupdateremarks = mysql_query("UPDATE crewwithdrawal SET ACCOUNTINGBY = '$employeeid',
																			ACCOUNTINGDATE = '$currentdate',
																			ACCOUNTINGREMARKS = '$clearanceremarks'
													WHERE APPLICANTNO = '$applicantno'
													") or die(mysql_errno());
				break;
				case "4":  //FLEET
					$qryupdateremarks = mysql_query("UPDATE crewwithdrawal SET FLEETBY = '$employeeid',
																			FLEETDATE = '$currentdate',
																			FLEETREMARKS = '$clearanceremarks'
													WHERE APPLICANTNO = '$applicantno'
													") or die(mysql_errno());
				break;
				case "5":  //DIVISION
					$qryupdateremarks = mysql_query("UPDATE crewwithdrawal SET DIVISIONBY = '$employeeid',
																			DIVISIONDATE = '$currentdate',
																			DIVISIONREMARKS = '$clearanceremarks'
													WHERE APPLICANTNO = '$applicantno'
													") or die(mysql_errno());
				break;
				case "6":  //MANAGEMENT
					$qryupdateremarks = mysql_query("UPDATE crewwithdrawal SET MGMTBY = '$employeeid',
																			MGMTDATE = '$currentdate',
																			MGMTREMARKS = '$clearanceremarks'
													WHERE APPLICANTNO = '$applicantno'
													") or die(mysql_errno());
				break;
				case "7":  //WITHDRAWAL FORM SURRENDER
					$qryupdateremarks = mysql_query("UPDATE crewwithdrawal SET SURRENDERTO = '$employeeid',
																			SURRENDERDATE = '$currentdate',
																			SURRENDERREMARKS = '$clearanceremarks'
													WHERE APPLICANTNO = '$applicantno'
													") or die(mysql_errno());
				break;
				case "8":  //MIS SAFEKEEP
					$qryupdateremarks = mysql_query("UPDATE crewwithdrawal SET SAFEKEEPBY = '$employeeid',
																			SAFEKEEPDATE = '$currentdate',
																			SAFEKEEPREMARKS = '$clearanceremarks'
													WHERE APPLICANTNO = '$applicantno'
													") or die(mysql_errno());
				break;
				case "9":  //ADMINISTRATION
					$qryupdateremarks = mysql_query("UPDATE crewwithdrawal SET ADMINBY = '$employeeid',
																			ADMINDATE = '$currentdate',
																			ADMINREMARKS = '$clearanceremarks'
													WHERE APPLICANTNO = '$applicantno'
													") or die(mysql_errno());
				break;
			}
			
			$clearanceremarks = "";
	break;

}


//retrieve details of withdrawal

$qrywithdrawal = mysql_query("SELECT * FROM crewwithdrawal WHERE APPLICANTNO=$applicantno AND IDNO=$idno") or die(mysql_errno());

// if (mysql_num_rows($qrywithdrawal) > 0)
// {
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

	$adminshow = "---";
	$adminremarks = "&nbsp;";
	

	$rowwithdrawal = mysql_fetch_array($qrywithdrawal);
	
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
// }
// else
// {
		// $formdate = strtotime($dateformat,$datenow);
		

// }

// if (empty($formdate))
// {
	// $disableclearance = "disabled=\"disabled\"";
	
// }

$openwindow = "";
$viewupload = "";

$dir = $basedirdocs . $applicantno . "/CrewWithdrawalForm" . "_$idno" . ".pdf";
if (checkpath($dir,1))
{
	$viewupload = "";
	$viewform = "<a href=\"#\" onclick=\"openWindow('$dir', 'viewdoc' ,900, 600);\" style=\"font-size:0.9em;font-weight:Bold;color:Red;\" >
		<i>View Withdrawal Form </i> </a>";
}
else
{
	$viewupload = "<a href=\"#\" onclick=\"openWindow('withdrawal_safekeep.php?applicantno=$applicantno&idno=$idno', 'safekeep' ,400, 350);\" style=\"font-size:0.9em;color:Green;font-weight:Bold;\">
					<u><i>Upload Withdrawal Form</i></u></a>";
	$viewform = "";
}

echo "
<html>\n
<head>\n
<title></title>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>

<body onload=\"\" style=\"overflow:hidden;background-color:White;\">\n

<form name=\"withdrawalstatus\" method=\"POST\">\n

<span class=\"sectiontitle\">WITHDRAWAL STATUS</span>
<br />
<div style=\"width:60%;height:auto;background-color:White;float:left;overflow:auto;\">
	<span class=\"sectiontitle\">STATUS</span>
";
	$styleborder = "border-bottom:1px dashed Gray;";
	
	$styleby = "style=\"font-size:1em;font-weight:Bold;color:Blue;\"";
	
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
			<td>Issue Withdrawal Form</td>
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
			<td><a href=\"#\" $disableclearance onclick=\"selection.value='1';remhdr.value='OPERATIONS';clearanceremarks.value='$operationsremarks';clearanceremarks.focus();\">Operations <b>>></b></a></td>
			<td align=\"center\" $styleby>$operationshow</td>
			<td align=\"center\">$operationsremarks</td>
		</tr>
		<tr $mouseovereffect>
			<td><a href=\"#\" $disableclearance onclick=\"selection.value='2';remhdr.value='TRAINING';clearanceremarks.value='$trainingremarks';clearanceremarks.focus();\">Training <b>>></b></a></td>
			<td align=\"center\" $styleby>$trainingshow</td>
			<td align=\"center\">$trainingremarks</td>
		</tr>
		<tr $mouseovereffect>
			<td><a href=\"#\" $disableclearance onclick=\"selection.value='9';remhdr.value='ADMINISTRATION';clearanceremarks.value='$adminremarks';clearanceremarks.focus();\">Administration <b>>></b></a></td>
			<td align=\"center\" $styleby>$adminshow</td>
			<td align=\"center\">$adminremarks</td>
		</tr>
		<tr $mouseovereffect>
			<td><a href=\"#\" $disableclearance onclick=\"selection.value='3';remhdr.value='ACCOUNTING/FINANCE';clearanceremarks.value='$accountingremarks';clearanceremarks.focus();\">Accounting/Finance <b>>></b></a></td>
			<td align=\"center\" $styleby>$accountingshow</td>
			<td align=\"center\">$accountingremarks</td>
		</tr>
		<tr $mouseovereffect>
			<td><a href=\"#\" $disableclearance onclick=\"selection.value='4';remhdr.value='FLEET';clearanceremarks.value='$fleetremarks';clearanceremarks.focus();\">Fleet <b>>></b></a></td>
			<td align=\"center\" $styleby>$fleetshow</td>
			<td align=\"center\">$fleetremarks</td>
		</tr>
		<tr $mouseovereffect>
			<td><a href=\"#\" $disableclearance onclick=\"selection.value='5';remhdr.value='DIVISION';clearanceremarks.value='$divisionremarks';clearanceremarks.focus();\">Division <b>>></b></a></td>
			<td align=\"center\" $styleby>$divisionshow</td>
			<td align=\"center\">$divisionremarks</td>
		</tr>
		<tr $mouseovereffect>
			<td><a href=\"#\" $disableclearance onclick=\"selection.value='6';remhdr.value='MANAGEMENT';clearanceremarks.value='$mgmtremarks';clearanceremarks.focus();\">Management <b>>></b></a></td>
			<td align=\"center\" $styleby>$mgmtshow</td>
			<td align=\"center\">$mgmtremarks</td>
		</tr>
		<tr $mouseovereffect>
			<td><a href=\"#\" $disableclearance onclick=\"selection.value='7';remhdr.value='WITHDRAWAL FORM SURRENDER';clearanceremarks.value='$surrenderremarks';clearanceremarks.focus();\">Withdrawal Form Surrender <b>>></b></a></td>
			<td align=\"center\" $styleby>$surrendershow</td>
			<td align=\"center\">$surrenderremarks</td>
		</tr>
		<tr $mouseovereffect>
			<td v-align=\"top\"><a href=\"#\" $disableclearance onclick=\"selection.value='8';remhdr.value='MIS SAFEKEEP';clearanceremarks.value='$safekeepremarks';clearanceremarks.focus();\">MIS Safekeep <b>>></b></a>
				<br /><br />$viewform $viewupload
			</td>
			<td align=\"center\" $styleby>$safekeepshow</td>
			<td align=\"center\">
			";
				if ($safekeepremarks != "")
				{
					echo "$safekeepremarks <br />
					
					";
				}
			echo "
			</td>
		</tr>
	</table>
	<br />
	
	<center>
		<input type=\"button\" value=\"Refresh\" style=\"border:1px solid Orange;background-color:Blue;color:Yellow;font-weight:Bold;font-size:1em;\"
			onclick=\"idno.value=$idno;submit();\" / >
	</center>
";
	
echo "
</div>

<div style=\"width:40%;height:auto;background-color:Silver;float:right;\">
	<span class=\"sectiontitle\">CLEARANCE REMARKS</span>
	<center>
	<br />
	<input type=\"text\" readonly=\"readonly\" name=\"remhdr\" style=\"background-color:Silver;color:Blue;font-size:0.8em;font-weight:Bold;text-align:center;\" size=\"40\" border=\"0\"/>
	<br /><br />
	<textarea cols=\"40\" rows=\"11\" name=\"clearanceremarks\" $disableclearance></textarea>
	
	<br /><br />

	<input type=\"button\" value=\"Save Remarks\" $disableclearance name=\"btnsave\" onclick=\"actiontxt.value='saveremarks';idno.value=$idno;submit();\"/>
	<br /><br />
	</center>
</div>


	<input type=\"hidden\" name=\"applicantno\" value=\"$applicantno\" />
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"selection\" />
	<input type=\"hidden\" name=\"idno\" />

</form>

</body>
</html>

";
	
	
	
?>