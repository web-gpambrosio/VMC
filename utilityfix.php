<?php
$kups="gino";
include('veritas/connectdb.php');

$qryutility = mysql_query("
SELECT APPLICANTNO FROM crewutility
") or die(mysql_error());

$cnt1 = 0;

while ($rowutility = mysql_fetch_array($qryutility))
{
	$applicantno = $rowutility["APPLICANTNO"];
	$cnt1++;
	if (!empty($applicantno))
	{
		$qryupdate = mysql_query("UPDATE crew SET UTILITY=1 WHERE APPLICANTNO=$applicantno");
		echo "$cnt1. UPDATE crew SET UTILITY=1 WHERE APPLICANTNO=$applicantno <br />";
	}
}


echo "
<html>\n
<head>\n
<title>Update Category</title>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>

<body onload=\"\" style=\"overflow:auto;background-color:White;\">\n


Total = $cnt1

</body>

</html>

";




?>