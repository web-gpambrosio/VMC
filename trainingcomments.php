<?php

include("veritas/connectdb.php");
include("veritas/include/stylephp.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];

if(isset($_SESSION['employeeid']))
	$employeeid=$_SESSION['employeeid'];

//$_GET 
	
if(isset($_GET['applicantno']))
	$applicantno=$_GET['applicantno'];
else 
	$applicantno=$_POST['applicantno'];
	
if(isset($_GET['traincode']))
	$traincode=$_GET['traincode'];

if(isset($_POST['remarks']))
	$remarks=$_POST['remarks'];
	
if (empty($remarks))
{
	$qrygetremarks = mysql_query("SELECT REMARKS FROM trainingendorsement WHERE APPLICANTNO=$applicantno AND TRAINCODE='$traincode'") or die(mysql_error());
	
	if(!empty($qrygetremarks))
	{
		$rowgetremarks = mysql_fetch_array($qrygetremarks);
		$remarks = $rowgetremarks["REMARKS"];
	}
	else
		$remarks = "";
}

$disableenroll = "";

switch ($actiontxt)
{
	case "save"	:
		
				$qrysaverem = mysql_query("UPDATE trainingendorsement SET REMARKS='$remarks' 
										WHERE APPLICANTNO=$applicantno AND TRAINCODE='$traincode'") or die(mysql_error());
										
				$message = "<span style=\"color:Red;font-size:1em;font-weight:Bold;\">Saved Successfully!</span>";
		
		break;

}
	
$qrytraininfo = mysql_query("SELECT TRAINCODE,TRAINING,DESCRIPTION,STATUS,COURSETYPECODE
							FROM trainingcourses
							WHERE TRAINCODE='$traincode'
						") or die(mysql_error());
						
$rowtraininfo = mysql_fetch_array($qrytraininfo);
$trainingname = $rowtraininfo["TRAINING"];
	
$qrygetcrew = mysql_query("SELECT CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,CREWCODE
							FROM crew c
							WHERE APPLICANTNO=$applicantno
							") or die(mysql_error());

$rowgetcrew = mysql_fetch_array($qrygetcrew);
$crewname = $rowgetcrew["NAME"];

echo "
<html>
<head>
<title>
Training Endorsement Remarks
</title>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>

<body style=\"overflow:hidden;\">

<form name=\"trainingremarks\" method=\"POST\">

<span class=\"sectiontitle\">Training Endorsement Remarks</span>

<center>
	<br />
	<span style=\"font-size:1em;font-weight:Bold;\">Training Course</span> <br />
	<span style=\"font-size:1.2em;font-weight:Bold;\">$trainingname</span>
	
	<br /><br />
	
	<span style=\"font-size:1em;font-weight:Bold;\">Comments/Remarks - $message</span>
	<br />
	<textarea name=\"remarks\" rows=\"5\" cols=\"50\">$remarks</textarea>
	<br />
	
	<input type=\"button\" value=\"Save\" onclick=\"actiontxt.value='save';submit();\" />
	<input type=\"button\" value=\"Close Window\" 
		onclick=\"
		
			opener.document.formchecklist.applicantno.value='$applicantno';
			opener.document.formchecklist.submit();
	
		window.close();\" />


	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"applicantno\" value=\"$applicantno\"/>
	<input type=\"hidden\" name=\"traincode\" value=\"$traincode\" />
</center>

</form>

<form name=\"crewtraining\" method=\"POST\" target=\"crewtraining\" action=\"crewtraining.php\">
	<input type=\"hidden\" name=\"applicantno\" value=\"$applicantno\" />
</form>

</body>

</html>

";


?>