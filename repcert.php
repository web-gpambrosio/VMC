<?php

// include("veritas/connectdb.php");
include("veritas/include/functions.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');
$datenow = date('d F Y');
$basedir = "idpics/";
$basedirdocs = "docimg/";
// $basedirdocs = "docimages/";

if(isset($_SESSION["employeeid"]))
	$employeeid=$_SESSION["employeeid"];

//if (isset($_POST["actiontxt"]))
//	$actiontxt = $_POST["actiontxt"];
	
if (isset($_GET["applicantno"]))
	$applicantno = $_GET["applicantno"];
else 
	$applicantno = $_POST["applicantno"];
	
if (isset($_GET["directprint"]))
	$directprint = $_GET["directprint"];

include("veritas/connectdb.php");

include("include/datasheet.inc");

$title = "Training Certificates";

$qrycertificate = mysql_query("SELECT ccs.DOCCODE,cd.DOCUMENT,ccs.DOCNO,'C' AS DOCTYPE,ccs.DATEISSUED,ccs.GRADE
						FROM crewcertstatus ccs
						LEFT JOIN crewdocuments cd ON cd.DOCCODE=ccs.DOCCODE
						WHERE ccs.APPLICANTNO=$applicantno
						ORDER BY ccs.DATEISSUED DESC
					") or die(mysql_error());


echo "
<html>\n
<head>\n
<title>
Printing - Training Certificates
</title>

<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>
<style type='text/css'>
@media print 
{
	#PAScreenOut { display: none; }
}
</style>

</head>
<body style=\"overflow:auto;\">\n

<div style=\"width:755px;background-color:white;margin:10 10 10 10;\">
";

include("repheader.php");
	
if (empty($directprint))
	echo "<input type=\"button\" value=\"Print\" id=\"PAScreenOut\" onclick=\"window.print();\">";

$styleborder = "style=\"border-bottom:1px solid Gray;\"";

echo "
	
	<br />

	<span style=\"font-size:1.1em;font-weight:Bold;text-decoration:underline;\">CERTIFICATES</span>
	
	<table style=\"width:100%;font-size:0.8em;\">
		<tr>
			<th width=\"45%\">&nbsp;</th>
			<th width=\"25%\"><u>CERTIFICATE NO</u></th>
			<th width=\"15%\"><u>ISSUED</u></th>
			<th width=\"15%\"><u>REMARKS</u></th>
		</tr>
		<tr><td colspan=\"4\">&nbsp;</td></tr>
	";
	while ($rowcertificate=mysql_fetch_array($qrycertificate))
	{
		$document = $rowcertificate["DOCUMENT"];
		$certno = $rowcertificate["DOCNO"];
		
		if (!empty($rowcertificate["GRADE"]))
			$grade = $rowcertificate["GRADE"];
		else 
			$grade = "---";
		$certcode = $rowcertificate["DOCCODE"];
		
		if (!empty($rowcertificate["DATEISSUED"]))
			$docissued = date("dMY",strtotime($rowcertificate["DATEISSUED"]));
		else 
			$docissued = "---";
		
		if (!empty($certno))
		{
			
			$dirfilename = $basedirdocs . $applicantno . "/" . $doctype . "/" . $certcode . ".pdf";
			
			if ($certno != " ")
			{
				if (checkpath($dirfilename))
					$certshow = "<a href=\"#\" onclick=\"openWindow('$dirfilename', '$doccode1' ,700, 500);\" 
									style=\"font-weight:Bold;$color\" title=\"Click to View Document.\" >$certno</a>";
				else 
					$certshow = "&nbsp; <span style=\"font-weight:Bold;$color\" title=\"Not yet scanned.\">$certno</span>";
			}
			else 
			{
				$certshow = "&nbsp; <span style=\"font-weight:Bold;$color\">---</span>";
			}
				
		}
			
			
		echo "
			<tr>
				<td $styleborder>$document</td>
				<td $styleborder align=\"center\">$certshow</td>
				<td $styleborder align=\"center\">$docissued</td>
				<td $styleborder align=\"center\"></td>
			</tr>
		";
		
	}

	echo "
	</table>

</div>
";
// include('include/printclose.inc');
echo "
</body>

";

?>