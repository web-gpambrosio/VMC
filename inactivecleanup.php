<?php
$kups="gino";
include('veritas/connectdb.php');

// session_start();

$qryinactive = mysql_query("
					SELECT c.APPLICANTNO
					FROM veritas2.dbinactive d
					LEFT JOIN veritas.crew c ON c.CREWCODE=d.CRW1
					WHERE c.APPLICANTNO IS NOT NULL
				") or die(mysql_error());
				
echo "Start Cleanup... <br /><br />";
$cnt = 1;
while ($rowinactive = mysql_fetch_array($qryinactive))
{
	$applicantno = $rowinactive["APPLICANTNO"];
	
	$qryupdate = mysql_query("UPDATE crew SET STATUS=0 WHERE APPLICANTNO=$applicantno") or die(mysql_error());
	
	echo "$cnt. UPDATE crew SET STATUS=0 WHERE APPLICANTNO=$applicantno <br />";
	
	$cnt++;
}
echo "Finish Cleanup! <br /><br />";
?>