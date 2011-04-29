<?php

include("veritas/connectdb.php");
include("veritas/include/functions.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');
$datenow = date('d F Y');
$basedir = "idpics/";
$basedirdocs = "docimages/";

if(isset($_SESSION["employeeid"]))
	$employeeid=$_SESSION["employeeid"];

//if (isset($_POST["actiontxt"]))
//	$actiontxt = $_POST["actiontxt"];
	
if (isset($_GET["applicantno"]))
	$applicantno = $_GET["applicantno"];
else 
	$applicantno = $_POST["applicantno"];

if (isset($_GET["print"]))
	$print = $_GET["print"];
else 
	$print = 0;

include("include/datasheet.inc");

$title = "Experiences";

echo "
<html>\n
<head>\n
<title>
Printing - Crew Experiences
</title>

<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>
<script language=\"javascript\" src=\"popcalendar.js\"></script>	

</head>
<body style=\"overflow:auto;\">

<div style=\"width:755px;background-color:white;margin:10 10 10 10;\">

";

include("repheader.php");
	
echo "
</div>
";
include('include/printclose.inc');
echo "
</body>

";

?>