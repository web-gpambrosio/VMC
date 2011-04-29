<?php
include('veritas/connectdb.php');
include("veritas/include/stylephp.inc");
include("veritas/include/functions.inc");

session_start();

if(isset($_SESSION['employeeid']))
	$employeeid=$_SESSION['employeeid'];
	
$currentdate = date("Y-m-d H:i:s");
$datenow = date("m/d/Y");

$qrydepartment = mysql_query("SELECT DEPARTMENTID FROM employee WHERE EMPLOYEEID='$employeeid'") or die(mysql_error());
$rowdepartment = mysql_fetch_array($qrydepartment);
$departmentid = $rowdepartment["DEPARTMENTID"];

if(isset($_POST['ccid']))
	$ccid=$_POST['ccid'];
else 
	$ccid=$_GET['ccid'];
	
if(isset($_POST['applicantno']))
	$applicantno=$_POST['applicantno'];
else 
	$applicantno=$_GET['applicantno'];

if(isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];

if(isset($_POST['division_remarks']))
	$division_remarks = $_POST['division_remarks'];
	
	
switch ($actiontxt)
{
	case "save"	:
		
			$division_remarks = mysql_real_escape_string($division_remarks);
		
			$qryupdate = mysql_query("UPDATE debriefinghdr SET DIVISIONBY='$employeeid', 
														DIVISIONDATE='$currentdate', 
														DIVISIONREMARKS='$division_remarks'
									WHERE CCID=$ccid") or die(mysql_error());
		
		break;
}

$qrygetremarks = mysql_query("SELECT DIVISIONREMARKS, rehire_assesment FROM debriefinghdr WHERE CCID=$ccid") or die(mysql_error());

$rowgetremarks = mysql_fetch_array($qrygetremarks);
$division_remarks = stripslashes($rowgetremarks["DIVISIONREMARKS"]);

if (!empty($division_remarks) && $departmentid != 5)  //DEPARTMENT ID = 5 (MIS GROUP) -- THEY CAN EDIT!!
{
	$readonly = "readonly=\"readonly\"";
	$disabled = "disabled=\"disabled\"";
}
else
{
	$readonly = "";
	$disabled = "";
}
$rehire_assesment = $rowgetremarks["rehire_assesment"];
				
				switch ($rehire_assesment)
				{
					case "0": $r_assesment = ""; break;
					case "1": $r_assesment = "( FOR REHIRE )"; break;
					case "2": $r_assesment = "( FOR MANAGEMENT ASSESSMENT )"; break;
				}


echo "
<html>\n
<head>\n
<title>Division Debriefing Remarks</title>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>

<body onload=\"\" style=\"overflow:hidden;background-color:White;\">\n

<form name=\"divisiondebriefing\" method=\"POST\">\n

<span class=\"wintitle\">DIVISION DEBRIEFING - ARRIVING SEAMAN</span>

	<div style=\"width:100%;height:450px;background-color:Silver;\">

		<center>
		";
	
		if (!empty($applicantno) && !empty($ccid))
		{
			echo "
			<iframe marginwidth=0 marginheight=0 id=\"debriefcer\" frameborder=\"0\" name=\"content\" 
				src=\"debriefcer.php?applicantno=$applicantno&ccid=$ccid&type=1\" scrolling=\"auto\" 
				style=\"width:100%;height:450px;\">
			</iframe>
			";
		
		}
		
		echo "
		</center>
	
	</div>

	<div style=\"width:100%;height:220px;background-color:White;\">

		<div style=\"width:50%;float:left;height:220px;background-color:Black;\">
			<span class=\"sectiontitle\">DIVISION REMARKS</span>
			
			<center>
			<table style=\"width:90%;font-size:0.8em;\">
				<tr>
					<td style=\"font-weight:Bold;color:Yellow;\"><u>Remarks</u>&nbsp;&nbsp;&nbsp;<span style=\"color:#FFFFFF; font-size:15px\">".$r_assesment."</span></td>
				</tr>
				<tr>
					<td><textarea rows=\"8\" cols=\"55\" $readonly name=\"division_remarks\">$division_remarks</textarea></td>
				</tr>
				<tr>
					<td align=\"center\">
						<input type=\"button\" value=\"Done\" $disabled
								style=\"border:1px solid Yellow;background-color:Green;color:White;font-weight:Bold;\"
								onclick=\"if (division_remarks.value != '') {if (confirm('Save changes?')) {
											actiontxt.value='save';submit();}
										}
										else {alert('Please fill up Remarks, then click DONE.');}
										
										\" />
						<input type=\"button\" value=\"Close Window\" 
								style=\"border:1px solid Yellow;background-color:Green;color:White;font-weight:Bold;\"
								onclick=\"opener.document.debriefingstatus.applicantno.value='$applicantno';
										opener.document.debriefingstatus.submit();
										window.close();\" />
						<input type=\"button\" value=\"Edit Clearance Form\" 
								style=\"border:1px solid Yellow;background-color:Green;color:White;font-weight:Bold;\"
								onclick=\"openWindow('debriefing_clearanceform.php?applicantno=$applicantno&load=1', 'debriefingclearance' ,900, 650);\" />
					</td>
				</tr>
			</table>
			</center>
		</div>
	
		<div style=\"width:50%;float:right;height:200px;background-color:White;\">
			<span class=\"sectiontitle\">OTHER REMARKS</span>
			
			<iframe marginwidth=0 marginheight=0 id=\"debriefcer\" frameborder=\"0\" name=\"content\" 
				src=\"otherremarks.php?applicantno=$applicantno&ccid=$ccid\" scrolling=\"auto\" 
				style=\"width:100%;height:180px;\">
			</iframe>
			
		</div>
	</div>
	
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"applicantno\" value=\"$applicantno\" />
	<input type=\"hidden\" name=\"ccid\" value=\"$ccid\" />

</form>

</body>

</html>
";
	
	
	
	
	
?>