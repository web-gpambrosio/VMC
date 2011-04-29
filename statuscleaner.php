<?php

include("veritas/connectdb.php");

$trdate = date('Y-m-d');
$currentdate = date('Y-m-d H:i:s');

$qrylist = mysql_query("
SELECT c.APPLICANTNO,cs.CREWCODE,cs.STATUSCODE 
FROM crewstat cs
LEFT JOIN crew c ON c.CREWCODE=cs.CREWCODE
WHERE STATUSCODE IN ('TR','NR') AND c.APPLICANTNO IS NOT NULL
") or die(mysql_error());

echo "Start! <br /><br />";

$cnt = 0;
while ($rowlist = mysql_fetch_array($qrylist))
{
	$crewcode = $rowlist["CREWCODE"];
	$applicantno = $rowlist["APPLICANTNO"];
	$statcode = $rowlist["STATUSCODE"];
	
	switch ($statcode)
	{
		case "TR"	:
			
				$qryinsert = mysql_query("INSERT INTO crewtransfer VALUES(NULL,$applicantno,'$trdate',NULL,'SYS','$currentdate')") or die(mysql_error());
			
			break;
			
		case "NR"	:
			
				$qryinsert = mysql_query("INSERT INTO crewnfr VALUES(NULL,$applicantno,'$trdate',NULL,'SYS','$currentdate')") or die(mysql_error());
			
			break;
	}
	
	echo "Applicant No. $applicantno / $statcode <br />";
	$cnt++;
}

echo "Finished! Total count = $cnt";

?>
