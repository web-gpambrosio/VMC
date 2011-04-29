<?php

include('veritas/connectdb.php');
//include('connectdb.php');

session_start();
$getservername=$_SERVER["SERVER_NAME"];

include('veritas/include/stylephp.inc');

$basedir = "scanned"; //change if different directory

$datetimenow=date("Y-m-d H:i:s");
$datenow=date("Y-m-d");
$datenowshow=date("m/d/Y");

	#*******************POST VALUES*************
if(isset($_SESSION['employeeid']))
	$employeeid=$_SESSION['employeeid'];

echo	"<html>\n
<head>\n
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />




<script>

</script>\n

</head>\n

<body onload=\"\" style=\"overflow:hidden;\">\n

<form name=\"scholarfasttrack\" id=\"scholarfasttrack\" method=\"POST\">\n

<span class=\"wintitle\">SCHOLAR - FASTRACK REPORT</span>

<div id=\"\" style=\"width:100%;overflow:hidden;\">


</div>";

echo "
<input type=\"hidden\" name=\"actiontxt\">


</form>
<form name=\"repcrewchangeplan\" action=\"repcrewchangeplan.php\" target=\"repcrewchangeplan\" method=\"POST\">
	<input type=\"hidden\" name=\"reportresult\">
	<input type=\"hidden\" name=\"ccpno\">
	<input type=\"hidden\" name=\"vesselname\">
</form>


</body>\n
</html>\n";

?>
