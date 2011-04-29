<?php
include('veritas/connectdb.php');
include("veritas/include/functions.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');
$basedirid = "idpics/";
$basedirdocs = "docimages/";

$dateformat = "dMY";


$qrycategory = mysql_query("
SELECT APPLICANTNO,CRW1,CRW25A
FROM category ca
LEFT JOIN crew c ON c.CREWCODE=ca.CRW1
WHERE CRW25A <> ''
ORDER BY CRW25A
") or die(mysql_error());

$cnt1 = 1;
$cnt2 = 1;
$cnt3 = 1;
$cnt4 = 1;

while ($rowcategory = mysql_fetch_array($qrycategory))
{
	$applicantno = $rowcategory["APPLICANTNO"];
	$crewcode = $rowcategory["CRW1"];
	$code = $rowcategory["CRW25A"];
	
//	$qryupdate = mysql_query("UPDATE crew SET RECOMMENDEDBY='$recommendation' WHERE APPLICANTNO=$applicantno") or die(mysql_error());
//	
//	$content .= $cnt . ") Updated --> ApplicantNo = $applicantno / Recommended By = $recommendation <br />";
	

	if (!empty($applicantno))
	{
		if ($code == "IS" || $code == "VS" || $code == "KS" || $code == "FS" || $code == "TS") //SCHOLAR
		{
			$qryverify = mysql_query("SELECT APPLICANTNO,SCHOLASTICCODE FROM crewscholar WHERE APPLICANTNO=$applicantno") or die(mysql_error());
			
			if (mysql_num_rows($qryverify) == 0)
			{
				$qryupdatescholar = mysql_query("INSERT INTO crewscholar(APPLICANTNO,SCHOLASTICCODE,MADEBY,MADEDATE) VALUES($applicantno,'$code','SYS','2008-06-25')") or die(mysql_error());
			}
			elseif (mysql_num_rows($qryverify) == 1)
			{
				$qryupdatescholar = mysql_query("UPDATE crewscholar SET SCHOLASTICCODE='$code' WHERE APPLICANTNO=$applicantno") or die(mysql_error());
			}
			
			$cnt1++;
		}
		
		if ($code == "IF" || $code == "VF" || $code == "FF" || $code == "TF")  //FASTTRACK
		{
			$qryverify = mysql_query("SELECT APPLICANTNO FROM crewfasttrack WHERE APPLICANTNO=$applicantno") or die(mysql_error());
			
			if (mysql_num_rows($qryverify) == 0)
			{
				$qryupdatefastrack = mysql_query("INSERT INTO crewfasttrack(APPLICANTNO,FASTTRACKCODE,MADEBY,MADEDATE) VALUES($applicantno,'$code','SYS','2008-06-25')") or die(mysql_error());
			}
			elseif (mysql_num_rows($qryverify) == 1)
			{
				$qryupdatescholar = mysql_query("UPDATE crewfasttrack SET FASTTRACKCODE='$code' WHERE APPLICANTNO=$applicantno") or die(mysql_error());
			}
			
			$cnt2++;
		}
		
		if ($code == "UT") //UTILITY
		{
			$qryverify = mysql_query("SELECT APPLICANTNO FROM crewutility WHERE APPLICANTNO=$applicantno") or die(mysql_error());
			
			if (mysql_num_rows($qryverify) == 0)
			{
				$qryupdateutility = mysql_query("INSERT INTO crewutility(APPLICANTNO) VALUES($applicantno)") or die(mysql_error());
			}
			
			$cnt3++;
		}
	}
	else 
	{
		$qrynoentry = mysql_query("INSERT INTO noentry VALUES(NULL,'$crewcode','$code')") or die(mysql_error());
		$cnt4++;
	}
	
}

$total = $cnt1 + $cnt2 + $cnt3 + $cnt4;


echo "
<html>\n
<head>\n
<title>Update Category</title>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>

<body onload=\"\" style=\"overflow:auto;background-color:White;\">\n

Crew Scholar = $cnt1 <br />
Crew Fasttrack = $cnt2 <br />
Crew Utility = $cnt3 <br />
No Entry = $cnt4 <br /><br />
Total = $total

</body>

</html>

";




?>