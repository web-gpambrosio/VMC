<?php
include('veritas/connectdb.php');
include("veritas/include/stylephp.inc");
include("veritas/include/functions.inc");

session_start();

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

if(isset($_POST['ccid']))
	$ccid=$_POST['ccid'];
else 
	$ccid=$_GET['ccid'];
	
if(isset($_POST['applicantno']))
	$applicantno=$_POST['applicantno'];
else 
	$applicantno=$_GET['applicantno'];
	
	
echo "
<html>\n
<head>\n
<title>
Welcome to Veritas Maritime Corporation
</title>

<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>

</head>
<body style=\"overflow:hidden;\">\n

<form name=\"datasheet\" method=\"POST\">\n
";
	$stylehdr = "style=\"font-size:1em;font-weight:Bold;color:Blue;\"";
	$stylebutton = "style=\"border:1px solid Black;background-color:Red;color:Yellow;font-weight:Bold;cursor:pointer;\"";
	$styleborder = "style=\"border-bottom:1px solid Black;font-size:1em;font-weight:Bold;color:Blue;\"";

echo "
	<center>
	<br /><br />
	<h1>Welcome to Veritas Maritime Corporation</h1>
	<br />
	
	<img src=\"images/veritas_logo_anim.gif\" />
	<br /><br /><br />
	
	<table style=\"width:50%;font-size:1.2em;\">
		<tr $mouseovereffect>
			<td $stylehdr><i>Online Application Form</i></td>		
			<td $stylehdr align=\"center\">
				<input type=\"button\" value=\"Apply Now!\" $stylebutton onclick=\"openWindow('application.php', 'applicationform' ,0, 0);\" />
			</td>
		</tr>
		<tr><td colspan=\"2\">&nbsp;</td></tr>
		<tr $mouseovereffect>
			<td $stylehdr><i>Arriving Seaman - Debriefing Form</i></td>		
			<td $stylehdr align=\"center\">
				<input type=\"button\" value=\"Fill-up Form\" $stylebutton onclick=\"openWindow('debriefingform.php', 'debriefingformkiosk' ,0, 0);\" />
			</td>		
		</tr>
	</table>

	</center>

</form>

</body>

</html>
";

?>