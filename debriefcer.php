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

if(isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if(isset($_GET["type"]))
	$type=$_GET['type'];

$content = "";
	
switch ($type)
{
	case "1"	: //FLEET

			$content = "
			<tr>
				<td>
					<input type=\"button\" value=\"CER\" 
						style=\"border:1px solid Black;background-color:Red;font-size:0.6em;color:Yellow;font-weight:Bold;cursor:pointer;\"
						onclick=\"document.getElementById('debriefcreweval').src='crewevaluationshow.php?ccid=$ccid'\" />
				</td>
				<td>
					<input type=\"button\" value=\"DEBRIEFING (FM-227)\" 
						style=\"border:1px solid Black;background-color:Red;font-size:0.6em;color:Yellow;font-weight:Bold;cursor:pointer;\"
						onclick=\"document.getElementById('debriefcreweval').src='debriefingformshow.php?applicantno=$applicantno&ccid=$ccid&load=1'\" />
				</td>
				<td>
					<input type=\"button\" value=\"CLEARANCE (FM-229)\" 
						style=\"border:1px solid Black;background-color:Red;font-size:0.6em;color:Yellow;font-weight:Bold;cursor:pointer;\"
						onclick=\"document.getElementById('debriefcreweval').src='debriefing_clearanceformshow.php?applicantno=$applicantno&ccid=$ccid&load=1'\" />
				</td>
				<td>
					<input type=\"button\" value=\"DATA SHEET\" 
						style=\"border:1px solid Black;background-color:Red;font-size:0.6em;color:Yellow;font-weight:Bold;cursor:pointer;\"
						onclick=\"document.getElementById('debriefcreweval').src='repcrewdatasheet.php?applicantno=$applicantno&print=0'\" />
				</td>
				<td>
					<input type=\"button\" value=\"TRAINING ENDORSEMENT\" 
						style=\"border:1px solid Black;background-color:Red;font-size:0.6em;color:Yellow;font-weight:Bold;cursor:pointer;\"
						onclick=\"openWindow('trainingendorsement.php?applicantno=$applicantno', 'trainendorse' ,0, 0);\" />
				</td>
<!-- document.getElementById('debriefcreweval').src='crewtrainingendorse.php?applicantno=$applicantno'  -->
			</tr>
			";

		break;
	case "2"	: //TRAINING

			$content = "
			<tr>
				<td>
					<input type=\"button\" value=\"CER\" 
						style=\"border:1px solid Black;background-color:Red;font-size:0.6em;color:Yellow;font-weight:Bold;cursor:pointer;\"
						onclick=\"document.getElementById('debriefcreweval').src='crewevaluationshow.php?ccid=$ccid'\" />
				</td>
				<td>
					<input type=\"button\" value=\"DEBRIEFING (FM-227)\" 
						style=\"border:1px solid Black;background-color:Red;font-size:0.6em;color:Yellow;font-weight:Bold;cursor:pointer;\"
						onclick=\"document.getElementById('debriefcreweval').src='debriefingformshow.php?applicantno=$applicantno&ccid=$ccid&load=1'\" />
				</td>
				<td>
					<input type=\"button\" value=\"DATA SHEET\" 
						style=\"border:1px solid Black;background-color:Red;font-size:0.6em;color:Yellow;font-weight:Bold;cursor:pointer;\"
						onclick=\"document.getElementById('debriefcreweval').src='repcrewdatasheet.php?applicantno=$applicantno&print=0'\" />
				</td>
				<td>
					<input type=\"button\" value=\"TRAINING ENROLLMENT\" 
						style=\"border:1px solid Black;background-color:Red;font-size:0.6em;color:Yellow;font-weight:Bold;cursor:pointer;\"
						onclick=\"openWindow('crewtraining.php?applicantno=$applicantno', 'trainendorse' ,0, 0);\" />
				</td>
			</tr>
			";
	
	
		break;
	case "3"	: //DIVISION

			$content = "
			<tr>
				<td>
					<input type=\"button\" value=\"CER\" 
						style=\"border:1px solid Black;background-color:Red;font-size:0.6em;color:Yellow;font-weight:Bold;cursor:pointer;\"
						onclick=\"document.getElementById('debriefcreweval').src='crewevaluationshow.php?ccid=$ccid'\" />
				</td>
				<td>
					<input type=\"button\" value=\"DEBRIEFING (FM-227)\" 
						style=\"border:1px solid Black;background-color:Red;font-size:0.6em;color:Yellow;font-weight:Bold;cursor:pointer;\"
						onclick=\"document.getElementById('debriefcreweval').src='debriefingformshow.php?applicantno=$applicantno&ccid=$ccid&load=1'\" />
				</td>
				<td>
					<input type=\"button\" value=\"CLEARANCE FORM\" 
						style=\"border:1px solid Black;background-color:Red;font-size:0.6em;color:Yellow;font-weight:Bold;cursor:pointer;\"
						onclick=\"document.getElementById('debriefcreweval').src='debriefing_clearanceformshow.php?applicantno=$applicantno&ccid=$ccid&load=1'\" />
				</td>
				<td>
					<input type=\"button\" value=\"DATA SHEET\" 
						style=\"border:1px solid Black;background-color:Red;font-size:0.6em;color:Yellow;font-weight:Bold;cursor:pointer;\"
						onclick=\"document.getElementById('debriefcreweval').src='repcrewdatasheet.php?applicantno=$applicantno&print=0'\" />
				</td>
			</tr>
			";
	
	
		break;
	case "4"	: //TOP MANAGEMENT

			$content = "
			<tr>
				<td>
					<input type=\"button\" value=\"CREW EVALUATION REPORT (CER)\" 
						style=\"border:1px solid Black;background-color:Red;font-size:0.6em;color:Yellow;font-weight:Bold;cursor:pointer;\"
						onclick=\"document.getElementById('debriefcreweval').src='crewevaluationshow.php?ccid=$ccid'\" />
				</td>
				<td>
					<input type=\"button\" value=\"DEBRIEFING (FM-227)\" 
						style=\"border:1px solid Black;background-color:Red;font-size:0.6em;color:Yellow;font-weight:Bold;cursor:pointer;\"
						onclick=\"document.getElementById('debriefcreweval').src='debriefingformshow.php?applicantno=$applicantno&ccid=$ccid&load=1'\" />
				</td>
				<td>
					<input type=\"button\" value=\"DATA SHEET\" 
						style=\"border:1px solid Black;background-color:Red;font-size:0.6em;color:Yellow;font-weight:Bold;cursor:pointer;\"
						onclick=\"document.getElementById('debriefcreweval').src='repcrewdatasheet.php?applicantno=$applicantno&print=0'\" />
				</td>
			</tr>
			";
	
	
		break;
	case "5"	:	//ACCOUNTING

			$content = "
			<tr>
<!--
				<td>
					<input type=\"button\" value=\"CER\" 
						style=\"border:1px solid Black;background-color:Red;font-size:0.6em;color:Yellow;font-weight:Bold;cursor:pointer;\"
						onclick=\"document.getElementById('debriefcreweval').src='crewevaluationshow.php?ccid=$ccid'\" />
				</td>
-->
				<td>
					<input type=\"button\" value=\"DEBRIEFING (FM-227)\" 
						style=\"border:1px solid Black;background-color:Red;font-size:0.6em;color:Yellow;font-weight:Bold;cursor:pointer;\"
						onclick=\"document.getElementById('debriefcreweval').src='debriefingformshow.php?applicantno=$applicantno&ccid=$ccid&load=1'\" />
				</td>
				<td>
					<input type=\"button\" value=\"CLEARANCE FORM\" 
						style=\"border:1px solid Black;background-color:Red;font-size:0.6em;color:Yellow;font-weight:Bold;cursor:pointer;\"
						onclick=\"document.getElementById('debriefcreweval').src='debriefing_clearanceformshow.php?applicantno=$applicantno&ccid=$ccid&load=1'\" />
				</td>
				<td>
					<input type=\"button\" value=\"DATA SHEET\" 
						style=\"border:1px solid Black;background-color:Red;font-size:0.6em;color:Yellow;font-weight:Bold;cursor:pointer;\"
						onclick=\"document.getElementById('debriefcreweval').src='repcrewdatasheet.php?applicantno=$applicantno&print=0'\" />
				</td>
			</tr>
			";
	
	
		break;
	
}

	
	
echo "
<html>\n
<head>\n
<title></title>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>

<body onload=\"\" style=\"overflow:hidden;background-color:#DCDCDC;\">\n

<form name=\"debriefingcer\" method=\"POST\">\n

	<center>
		<table width=\"60%\">
			$content
		
		</table>
	</center>
	
	<br />
	
	<div style=\"width:100%;height:400px;overflow:auto;\">

		<iframe marginwidth=0 marginheight=0 id=\"debriefcreweval\" frameborder=\"0\" name=\"content\" 
			src=\"crewevaluationshow.php?ccid=$ccid\" scrolling=\"auto\" style=\"width:100%;height:100%;\">
		</iframe>
	</div>

</form>

</body>

</html>


";