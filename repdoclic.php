<?php
$kups="gino";
//include("veritas/connectdb.php");
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
	
//if (empty($directprint))
include("veritas/connectdb.php");
	
// if (isset($_GET["print"]))
	// $print = $_GET["print"];
// else 
	// $print = 0;

include("include/datasheet.inc");

$title = "Documents/Licenses";

$qrydoclic = mysql_query("SELECT * FROM (
						SELECT cds.DOCCODE,cd.DOCUMENT,cds.DOCNO,cd.TYPE,cd.HASEXPIRY,cds.DATEISSUED,cds.DATEEXPIRED
						FROM crewdocstatus cds
						LEFT JOIN crewdocuments cd ON cd.DOCCODE=cds.DOCCODE
						WHERE cds.APPLICANTNO=$applicantno AND cd.STATUS=1
						ORDER BY cd.TYPE,cd.DOCUMENT,cds.DATEISSUED DESC
						) x
						GROUP BY TYPE,DOCUMENT
					") or die(mysql_error());


echo "
<html>\n
<head>\n
<title>
Printing - Crew Documents and Licenses
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

echo "

	<table style=\"width:100%;font-size:0.8em;\">
	";
	$styleborder = "style=\"border-bottom:1px solid Gray;\"";
	$doctypetmp = "";
	
	while ($rowdoclic=mysql_fetch_array($qrydoclic))
	{
		$documentshow = "&nbsp; <span style=\"font-weight:Bold;\">&nbsp;</span>";
		$docissued = "";
		$docexpired = "";
		
		$doccode = $rowdoclic["DOCCODE"];
		$document = $rowdoclic["DOCUMENT"];
		$documentno = $rowdoclic["DOCNO"];
		$doctype = $rowdoclic["TYPE"];
		$dochasexpiry = $rowdoclic["HASEXPIRY"];
		
		if (!empty($rowdoclic["DATEISSUED"]))
			$docissued = date("dMY",strtotime($rowdoclic["DATEISSUED"]));
		else 
			$docissued = "---";
			
		if (!empty($rowdoclic["DATEEXPIRED"]))
			$docexpired = date("dMY",strtotime($rowdoclic["DATEEXPIRED"]));
		else 
			$docexpired = "---";
		
		if ($doctypetmp != $doctype)
		{
			if ($doctype == "D")
			{
				echo "
					<tr><td colspan=\"4\">&nbsp;</td></tr>
					<tr><td colspan=\"4\"><span style=\"font-size:1.5em;font-weight:Bold;text-decoration:underline;\">DOCUMENTS</span></td></tr>
					<tr>
						<th width=\"45%\">&nbsp;</th>
						<th width=\"25%\"><u>DOCUMENT NO</u></th>
						<th width=\"15%\"><u>ISSUED</u></th>
						<th width=\"15%\"><u>EXPIRY</u></th>
					</tr>
					<tr><td colspan=\"4\">&nbsp;</td></tr>
				";
			}
			elseif ($doctype == "L")
			{
				echo "
					<tr><td colspan=\"4\">&nbsp;</td></tr>
					<tr><td colspan=\"4\"><span style=\"font-size:1.5em;font-weight:Bold;text-decoration:underline;\">LICENSES</span></td></tr>

					<tr>
						<th width=\"45%\">&nbsp;</th>
						<th width=\"25%\"><u>LICENSE NO</u></th>
						<th width=\"15%\"><u>ISSUED</u></th>
						<th width=\"15%\"><u>EXPIRY</u></th>
					</tr>
					<tr><td colspan=\"4\">&nbsp;</td></tr>
				";
			}
			
		}
			
			
		if (!empty($documentno))
		{
			if ($dochasexpiry == 1 && $documentno != " ")
			{
				$expdate = date('Y-m-d',strtotime($docexpired));
				$color = checkexpiry($expdate);
			}
			else 
				$color = "color:Black";
			
			$dirfilename = $basedirdocs . $applicantno . "/" . $doctype . "/" . $doccode1 . ".pdf";
			
			if ($documentno != " ")
			{
				if (checkpath($dirfilename))
					$documentshow = "<a href=\"#\" onclick=\"openWindow('$dirfilename', '$doccode1' ,700, 500);\" 
									style=\"font-weight:Bold;$color\" title=\"Click to View Document.\" >$documentno</a>";
				else 
					$documentshow = "&nbsp; <span style=\"font-weight:Bold;$color\" title=\"Not yet scanned.\">$documentno</span>";
			}
			else 
			{
				$documentshow = "&nbsp; <span style=\"font-weight:Bold;$color\">---</span>";
			}
				
		}
		
		echo "
			<tr>
				<td $styleborder>$document</td>
				<td $styleborder align=\"center\">$documentshow</td>
				<td $styleborder align=\"center\">$docissued</td>
				<td $styleborder align=\"center\">$docexpired</td>
			</tr>
		";
		
		$doctypetmp = $doctype;
	}
		
echo "

</div>
";
// include('include/printclose.inc');
echo "
</body>

";

?>