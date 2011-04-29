<?php

include("veritas/connectdb.php");
include("veritas/include/stylephp.inc");
include("veritas/include/functions.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');
$datenow = date('d F Y');
$basedir = "idpics/";
$basedirdocs = "docimages/";

$dateformat = "dMY";
//$new = 0;
$disabled = "disabled=\"disabled\"";

$marked = "<span style=\"font-size:1em;font-weight:Bold;\">X</span>";

if(isset($_SESSION["employeeid"]))
	$employeeid=$_SESSION["employeeid"];

if (isset($_POST["actiontxt"]))
	$actiontxt = $_POST["actiontxt"];
	
if (isset($_POST["applicantno"]))
	$applicantno = $_POST["applicantno"];

if (empty($applicantno))
{
	if (isset($_GET["applicantno"]))
		$applicantno = $_GET["applicantno"];
}

if (isset($_POST["load"]))
	$load = $_POST["load"];
else 
	$load = $_GET["load"];

if (isset($_POST["ccid"]))
	$ccid = $_POST["ccid"];
	
if (isset($_GET["print"]))
{
	$print = $_GET["print"];
	$disabled = "";
}
else 
	$print = 0;
	
$checked= "checked=\"checked\"";


	$qryusvisaupdate = mysql_query("UPDATE usvisaendorsement SET 
									PRINTBY = '$employeeid',
									PRINTDATE = '$currentdate'
								WHERE APPLICANTNO=$applicantno AND STATUS IS NULL
							") or die(mysql_error());

include("include/datasheet.inc");


echo "
<html>\n
<head>\n
<title>
U.S. VISA Endorsement
</title>

<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>


</head>
<body style=\"\">\n

<form name=\"usvisaform\" method=\"POST\">\n

	";
	
	$styleborder = "style=\"border-bottom:1px solid Black;\"";
	$styledata = "style=\"font-weight:Bold;color:Blue;text-align:center;\"";
	$styleencode = "style=\"color:Red;font-weight:Bold;\"";
	
	echo "
	<div style=\"margin:5 5 5 10;width:755px;height:600px;\">
	
		<div style=\"width:100%;height:60px;\">
			<center>
				<span style=\"font-size:1.1em;font-weight:Bold;\">VERITAS MARITIME CORPORATION</span><br />
				<span style=\"font-size:0.9em;font-weight:Bold;\">U.S. VISA ENDORSEMENT</span><br />
				<span style=\"font-size:0.8em;font-weight:Bold;\">Date: $datenow</span><br />
			</center>
		</div>

		<br />
		<div style=\"width:100%;background-color:White;\">
			<table style=\"width:100%;font-size:0.9em;font-weight:Bold;\">
				<tr>
					<td width=\"20%\">DATE</td>
					<td width=\"5%\">:</td>
					<td style=\"border-bottom:1px solid Gray;\">$datenow</td>
				</tr>
				<tr>
					<td>NAME</td>
					<td>:</td>
					<td style=\"border-bottom:1px solid Gray;\">$crewname</td>
				</tr>
				<tr>
					<td>RANK</td>
					<td>:</td>
					<td style=\"border-bottom:1px solid Gray;\">$currentrank</td>
				</tr>
				<tr>
					<td>VESSEL ASSIGN</td>
					<td>:</td>
					<td style=\"border-bottom:1px solid Gray;\">&nbsp;</td>
				</tr>
				<tr>
					<td>ETD</td>
					<td>:</td>
					<td style=\"border-bottom:1px solid Gray;\">&nbsp;</td>
				</tr>
				<tr>
					<td>REMARKS</td>
					<td>:</td>
					<td style=\"border-bottom:1px solid Gray;\">&nbsp;</td>
				</tr>
				<tr>
					<td>PEME DATE</td>
					<td>:</td>
					<td style=\"border-bottom:1px solid Gray;\">&nbsp;</td>
				</tr>
			</table>
			
			<br /><br />
			";
			
			switch ($divcode)
			{
				case 1	:
					
					$endorsedby = "PILAR REYES / DAPHNE DEPUSOY";
					$divisionby = "DANILO JOSE C. CARDOZO";
					
					break;
				case 2	:
					
					$endorsedby = "JOCELYN PALMEJAR";
					$divisionby = "FELIX M. ROMO";
					
					break;
			}
	
			echo "
			<table style=\"width:40%;float:left;font-size:0.9em;font-weight:Bold;\">
				<tr>
					<td>Endorsed By</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr><td>&nbsp;</td></tr>
				<tr><td style=\"border-bottom:1px solid black;\">$endorsedby</td></tr>
				<tr><td>Fleet Manager / Engineer</td></tr>
				<tr><td>&nbsp;</td></tr>
			</table>
			
			<table style=\"width:40%;float:right;font-size:0.9em;font-weight:Bold;\">
				<tr>
					<td>Recommended Approval</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr><td>&nbsp;</td></tr>
				<tr><td style=\"border-bottom:1px solid black;\">$divisionby</td></tr>
				<tr><td>Division Manager</td></tr>
			</table>
			<br /><br />
			<table style=\"width:100%;font-size:0.9em;font-weight:Bold;\">
				<tr>
					<td width=\"33%\">&nbsp;</td>
					<td width=\"33%\" align=\"center\">Approved By</td>
					<td width=\"33%\">&nbsp;</td>
				</tr>
				<tr><td colspan=\"3\">&nbsp;</td></tr>
				<tr><td colspan=\"3\">&nbsp;</td></tr>
				<tr>
					<td>&nbsp;</td>
					<td style=\"border-bottom:1px solid black;\">&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td align=\"center\">JIRO M. MARQUEZ</td>
					<td>&nbsp;</td>
				</tr>
			</table>
		</div>

	</div>
	
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"applicantno\" value=\"$applicantno\"/>
	<input type=\"hidden\" name=\"ccid\" value=\"$ccid\"/>
	<input type=\"hidden\" name=\"load\" value=\"$load\"/>
</form>";

if ($print == 1)
	include('include/printclose.inc');

echo "
</body>

</html>

";