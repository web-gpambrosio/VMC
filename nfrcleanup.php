<?php

include("connectdb.php");

session_start();

$currentdate = date("Y-m-d");
$currentmonth = date("m");
$currentday = date("d");
$currentyear = date("y");

$sendemailtype = 1;

$subject = "HAPPY BIRTHDAY!!!";

//$qrybday = mysql_query("
//	SELECT FNAME,GNAME,CONCAT(LEFT(MNAME,1),'.') AS MNAME,MONTH(BIRTHDATE) AS MONTH,DAYOFMONTH(BIRTHDATE) AS DAY,YEAR(BIRTHDATE) AS YEAR,
//	MONTHNAME(BIRTHDATE) AS MONTHNAME,BIRTHDATE,VMCEMAIL
//	FROM staffhdr
//	WHERE MONTH(BIRTHDATE)=$currentmonth AND DAYOFMONTH(BIRTHDATE)=$currentday
//") or die(mysql_error());

$qrybday = mysql_query("
	SELECT FNAME,GNAME,CONCAT(LEFT(MNAME,1),'.') AS MNAME,MONTH(BIRTHDATE) AS MONTH,DAYOFMONTH(BIRTHDATE) AS DAY,YEAR(BIRTHDATE) AS YEAR,
	MONTHNAME(BIRTHDATE) AS MONTHNAME,BIRTHDATE,VMCEMAIL
	FROM staffhdr
	WHERE MONTH(BIRTHDATE)=$currentmonth AND DAYOFMONTH(BIRTHDATE)=$currentday
") or die(mysql_error());



$celebrants = "";  //Name,email

while ($rowbday = mysql_fetch_array($qrybday))
{
	$fname = $rowbday["FNAME"];
	$gname = $rowbday["GNAME"];
	$mname = $rowbday["MNAME"];
	$vmcemail = $rowbday["VMCEMAIL"];

//	$vmcemail = "gino.ambrosio@yahoo.com.ph";
	
	$fullname = $gname . " " . $mname . " " . $fname;
	$emailto = $gname . " " . $fname;
	
	if (!empty($emailto))
	{
		if (empty($celebrants))
			$celebrants = $emailto . ", " . $vmcemail;
		else 
			$celebrants .= "|" . $emailto . ", " . $vmcemail;
	}
}

$body = "
<html>
<head>

</head>

<body>

	<div style=\"width:800px;height:400px;background-color:White;\">

		<div style=\"width:800px;height:700px;background-color:White;float:left;\">
			
			<center>
			<img src=\"http://61.28.171.13/veritas/images/happybday.jpg\" border=0> <br /><br />
			
			<span style=\"font-size:2.2em;font-weight:Bold;color:Green;\"><i>$fullname</i></span> <br /><br /><br />
			
			<img src=\"http://61.28.171.13/veritas/images/bday.jpg\" border=0>
			</center>
			
		</div>

	</div>
</body>

</html>
";


//echo	
//"<html>\n
//<head>\n
//
//<link rel=\"stylesheet\" type=\"text/css\" href=\"focc.css\">
//</head>\n
//
//<body>
//
//<h1>Email Testing Started...</h1>
//";

include("/usr/local/www/data/veritas/include/email.inc");

//if(!$mailer->Send())
//{
//  echo "<script>alert('There was a problem sending this mail!');</script>";
//}
//else
//{
//  echo "<script>alert('Mail Sent Successfully!');</script>";
//}		

if (!empty($celebrants))
{
	$mailer->Send();
	
	$mailer->ClearAddresses();
	$mailer->ClearAttachments();
}


//echo "
//<br /><br />
//<h1>Finished!</h1>
//
//</body>
//
//</html>
//
//";


?>