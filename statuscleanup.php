<?php

include("veritas/connectdb.php");

session_start();

echo "START!!! <br />";

$qrydata = mysql_query("SELECT CREWCODE,APPLICANTNO FROM crew WHERE CREWCODE IS NOT NULL AND CREWCODE <> ''") or die(mysql_error());

while ($rowdata = mysql_fetch_array($qrydata))
{
	$xcrewcode = $rowdata["CREWCODE"];
	$xappno = $rowdata["APPLICANTNO"];
	
	$qrygetstat = mysql_query("SELECT STATUSCODE FROM crewstat WHERE CREWCODE='$xcrewcode'") or die(mysql_error());
	
	if (mysql_num_rows($qrygetstat) > 0)
	{
		$rowgetstat = mysql_fetch_array($qrygetstat);
		$statcode = $rowgetstat["STATUSCODE"];
	}
	
	$qryupdate = mysql_query("UPDATE crew SET CREWSTATCODE='$statcode' WHERE CREWCODE='$xcrewcode'");
	
	echo "Updated Crewcode $xcrewcode --> Status = $statcode \n <br />";
	
}

echo "FINISHED!!!";

?>