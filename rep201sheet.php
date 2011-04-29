<?php

//$kups = "gino";

	
//include("veritas/connectdb.php");
include("veritas/include/functions.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');
$datenow = date('d F Y');
$basedir = "idpics/";
$basedirdocs = "docimages/";

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

$styleborder="style=\"border-bottom:1px solid black;\"";

echo "
<html>\n
<head>\n
<title>
Crew Data Sheet
</title>

<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>
<script language=\"javascript\" src=\"popcalendar.js\"></script>	
<style type='text/css'>
@media print 
{
	#PAScreenOut { display: none; }
}
</style>

</head>
<body style=\"overflow:auto;\">\n
<br><br>
<div style=\"width:755px;background-color:white;\">

	<div style=\"width:75%;height:60px;float:left;background-color:White;overflow:hidden;\">
		<center>
			<span style=\"font-size:1em;font-weight:Bold;\">VERITAS MARITIME CORPORATION
			";
			if (empty($directprint))
				echo "<input type=\"button\" value=\"Print\" id=\"PAScreenOut\" onclick=\"window.print();\">";
			echo "
			</span><br />
			<span style=\"font-size:0.8em;font-weight:Bold;\">CREW PERSONAL DATA SHEET</span><br />
			<span style=\"font-size:0.7em;font-weight:Bold;\">Date: $datenow</span><br />
		</center>
	</div>
	";

	echo "
	<div style=\"width:25%;height:60px;float:right;background-color:White;\">

		<table width=\"90%\" class=\"listrow\" >
			<tr>
				<th>Applicant No.</th>
				<th>:</th>
				<td><b>$applicantno</b></td>
			</tr>
			<tr>
				<th>Crew Code</th>
				<th>:</th>
				<td><b>$crewcode</b></td>
			</tr>
		";
	
		if (empty($crewregular))
		{
			echo "
				<tr>
					<td colspan=\"3\" style=\"color:Red;font-size:1.2em;\"><b>$crewscholartype</b></td>
				</tr>
				<tr>
					<td colspan=\"3\" style=\"color:Orange;font-size:1.2em;\"><b>$crewfasttracktype</b></td>
				</tr>
			";
		}
		else 
		{
			if ($crewutility == 1)
				$crewutilityshow = "(UTILITY)";
			else
				$crewutilityshow = "";
			echo "
				<tr>
					<td colspan=\"3\"><span style=\"color:Orange;font-size:1.2em;\" ><b>$crewregular</b></span>
						<span style=\"color:Blue;font-size:1.2em;\" ><b>$crewutilityshow</b></span>
					</td>
				</tr>
				";
				
			
			echo "
				<tr>
					
				</tr>
			";
		}
		
		echo "
		</table>
	</div>

	<div style=\"width:755px;height:125px;overflow:hidden;\">

		<div style=\"width:450px;height:100%;float:left;overflow:hidden;\">
			<table class=\"listrow\" width=\"100%\">
				<tr>
					<td colspan=\"3\">&nbsp;</td>
				</tr>
				<tr>
					<th width=\"40%\">Name of Seaman</th>
					<th width=\"5%\">:</th>
					<td width=\"55%\" valign=\"top\" >$crewname</td>
				</tr>
				<tr>
					<th>Rank</th>
					<th>:</th>
					<td>$currentrank</td>
				</tr>
				<tr>
					<th>Experience in VMC</th>
					<th>:</th>
					<td valign=\"top\">$vmcyears&nbsp;Years&nbsp;&nbsp; $vmcmonths&nbsp; Months</td>
				</tr>
				<tr>
					<th>Experience as Rank</th>
					<th>:</th>
					<td>$exprankyears&nbsp; Years&nbsp;&nbsp; $exprankmonths&nbsp; Months</td>
				</tr>
				<tr>
					<th>Experience on Highest Rank</th>
					<th>:</th>
					<td valign=\"top\">$exphighrankyears&nbsp; Year&nbsp;&nbsp; $exphighrankmonths Months</td>
				</tr>
			</table>
		</div>

		<div style=\"width:200px;height:100%;float:right;background-color:White;overflow:hidden;\">
	";
			$dirfilename = "$basedir/$applicantno.JPG";
			if (checkpath($dirfilename))
			{
				$scale = imageScale($dirfilename,-1,120);
				$width = $scale[0];
				$height = $scale[1];
				
	echo "			<center><img src=\"$dirfilename\" width=\"$width\" height=\"$height\" border=\"1\" /></center> ";
			}
			else 
			{
	echo "			<center><b>[NO PICTURE]</b></center>";
			}
	echo "
		</div>
	</div>
	
		<div style=\"width:400px;height:80px;float:left;overflow:hidden;\">
			<table class=\"listrow\" width=\"100%\">
				<tr>
					<th colspan=\"3\" align=\"left\"><u>Personal Data</u></th>
				</tr>
				<tr>
					<th width=\"30%\">Place of Birth</th>
					<th>:</th>
					<td>$crewbplace</td>
				</tr>
				<tr>
					<th>Date of Birth</th>
					<th>:</th>
					<td>$crewbdate</td>
				</tr>
				<tr>
					<th>Religion</th>
					<th>:</th>
					<td>$crewreligion</td>
				</tr>
			</table>
		</div>
		<div style=\"width:150px;height:80px;float:left;background-color:White;overflow:hidden;\">
			<table class=\"listrow\" width=\"95%\">
				<tr>
					<th colspan=\"3\">&nbsp;</th>
				</tr>
				<tr>
					<th width=\"50%\">Age</th>
					<th>:</th>
					<td>$crewage</td>
				</tr>
				<tr>
					<th>Height(cm)</th>
					<th>:</th>
					<td>$crewheight</td>
				</tr>
				<tr>
					<th>Weight(kg)</th>
					<th>:</th>
					<td>$crewweight</td>
				</tr>
			<!--
				<tr>
					<th>Driver's License</th>
					<th>:</th>
					<td>$crewdriverlic</td>
				</tr>
			-->
			</table>
		</div>
		<div style=\"width:600px;height:80px;float:left;background-color:White;overflow:hidden;\">
			<table class=\"listrow\" width=\"100%\">
				<tr>
					<th colspan=\"3\" align=\"left\"><u>Pre-Employment Medical Exam</u></th>
				</tr>
				<tr>
					<th width=\"20%\">Conducted By</th>
					<th>:</th>
					<td>$crewmedclinic2</td>
				</tr>
				<tr>
					<th>Result</th>
					<th>:</th>
					<td>$crewmedrecommend</td>
				</tr>
				<tr>
					<th>Date</th>
					<th>:</th>
					<td>$crewmeddate</td>
				</tr>
			</table>
		</div>
		<div style=\"width:600px;height:90px;float:left;background-color:White;overflow:hidden;\">
			<table class=\"listrow\" width=\"100%\">
				<tr>
					<th colspan=\"3\" align=\"left\"><u>Educational Attainment</u></th>
				</tr>
				<tr>
					<th width=\"20%\">Name of School</th>
					<th>:</th>
					<td>$creweducschool</td>
				</tr>
				<tr>
					<th>Course Taken</th>
					<th>:</th>
					<td>$creweduccourse</td>
				</tr>
				<tr>
					<th>Graduate(Y/N)</th>
					<th>:</th>
					<td>$creweducgrad</td>
				</tr>
				<tr>
					<th>Year Graduate</th>
					<th>:</th>
					<td>$creweducdategrad</td>
				</tr>
			</table>
		</div>
	</div>
	<br />
	";
	
	$qrygetlicenses = mysql_query("
							SELECT cd.DOCCODE,cd.DOCUMENT,c.DOCNO,c.DATEISSUED,c.DATEEXPIRED,c.RANKCODE,r.ALIAS1 AS RANKALIAS
							FROM crewdocuments cd
							LEFT JOIN crewdocstatus c ON cd.DOCCODE=c.DOCCODE AND c.APPLICANTNO=$applicantno
							LEFT JOIN rank r ON r.RANKCODE=c.RANKCODE
							WHERE cd.DOCCODE IN ('F1','F2','P1','P2','J1','J2','41','42','A4','C0','18','S1') AND cd.STATUS=1
					") or die(mysql_error());
				
	$dateformat = "dMY";
	while ($rowgetlicenses = mysql_fetch_array($qrygetlicenses))
	{
		$xdoccode = $rowgetlicenses["DOCCODE"];
		$xdocument = $rowgetlicenses["DOCUMENT"];
		$xdocno = $rowgetlicenses["DOCNO"];
		$xdateissued = $rowgetlicenses["DATEISSUED"];
		$xdateexpired = $rowgetlicenses["DATEEXPIRED"];
		$xrankcode = $rowgetlicenses["RANKCODE"];
		$xrankalias = $rowgetlicenses["RANKALIAS"];
		
		if (!empty($xdateissued))
			$xdateissued = date($dateformat,strtotime($xdateissued));
		else
			$xdateissued = "";
		
		if (!empty($xdateexpired))
			$xdateexpired = date($dateformat,strtotime($xdateexpired));
		else
			$xdateexpired = "";
			
		$doctmp1 = "docno" . $xdoccode;
		$doctmp2 = "docissued" . $xdoccode;
		$doctmp3 = "docexpired" . $xdoccode;
		$doctmp4 = "rankalias" . $xdoccode;
							
		if (!empty($xdocno))
		{
			$$doctmp1 = $xdocno;
			$$doctmp2 = $xdateissued;
			$$doctmp3 = $xdateexpired;
			$$doctmp4 = $xrankalias;
		}
		else
		{
			// echo "<script>alert('$xdoccode');</script>";
			$$doctmp1 = "NONE";
			$$doctmp2 = "NONE";
			$$doctmp3 = "NONE";
			$$doctmp4 = "NONE";
		}
			
	}
	
	$styledata = "style=\"font-weight:Bold;\"";
	
	echo "
	<div style=\"width:755px;font-size:0.75em;\">
		<u><b>LICENSE & CERTIFICATES</b></u>
		<br /><br />

		<table style=\"width:100%;font-size:1em;\">
			<tr>
				<td>
					Seaman Book <br />
					&nbsp;&nbsp;&nbsp;No. <br />
					&nbsp;&nbsp;&nbsp;Date Issue <br />
					&nbsp;&nbsp;&nbsp;Expiry <br />
				</td>
				<td>
					Philippine <br />
					<span $styledata>
					$docnoF2 <br />
					$docissuedF2 <br />
					$docexpiredF2 <br />
					</span>
				</td>
				<td>
					Panama <br />
					<span $styledata>
					$docnoP2 <br />
					$docissuedP2 <br />
					$docexpiredP2 <br />
					</span>
				</td>
				<td>
					Japanese <br />
					<span $styledata>
					$docnoJ2 <br />
					$docissuedJ2 <br />
					$docexpiredJ2 <br />
					</span>
				</td>
				<td>
					&nbsp;
				</td>
			</tr>
			<tr>
				<td colspan=\"5\">&nbsp;</td>
			</tr>
			<tr>
				<td>
					License <br />
					&nbsp;&nbsp;&nbsp;Rank/Apply <br />
					&nbsp;&nbsp;&nbsp;No. <br />
					&nbsp;&nbsp;&nbsp;Date Issue <br />
					&nbsp;&nbsp;&nbsp;Expiry <br />
				</td>
				<td>
					Philippine <br />
					<span $styledata>
					$rankaliasF1 <br />
					$docnoF1 <br />
					$docissuedF1 <br />
					$docexpiredF1 <br />
					</span>
				</td>
				<td>
					Panama <br />
					<span $styledata>
					$rankaliasP1 <br />
					$docnoP1 <br />
					$docissuedP1 <br />
					$docexpiredP1 <br />
					</span>
				</td>
				<td>
					Singapore <br />
					<span $styledata>
					$rankaliasS1 <br />
					$docnoS1 <br />
					$docissuedS1 <br />
					$docexpiredS1 <br />
					</span>
				</td>
				<td>
					JIS License <br />
					<span $styledata>
					$rankaliasJ1 <br />
					$docnoJ1 <br />
					$docissuedJ1 <br />
					$docexpiredJ1 <br />
					</span>
				</td>
			</tr>
			<tr>
				<td colspan=\"5\">&nbsp;</td>
			</tr>
			<tr>
				<td>
					Certificate of Competency <br />
					&nbsp;&nbsp;&nbsp;Rank <br />
					&nbsp;&nbsp;&nbsp;No. <br />
					&nbsp;&nbsp;&nbsp;Date Issue <br />
					&nbsp;&nbsp;&nbsp;Expiry <br />
				</td>
				<td>
					Officer <br />
					<span $styledata>
					$rankalias18 <br />
					$docno18 <br />
					$docissued18 <br />
					$docexpired18 <br />
					</span>
				</td>
				<td>
					Rating <br />
					<span $styledata>
					$rankaliasC0 <br />
					$docnoC0 <br />
					$docissuedC0 <br />
					$docexpiredC0 <br />
					</span>
				</td>
				<td>
					&nbsp;
				</td>
				<td>
					&nbsp;
				</td>
			</tr>
			<tr>
				<td colspan=\"5\">&nbsp;</td>
			</tr>
			<tr>
				<td>
					Passport <br />
					&nbsp;&nbsp;&nbsp;No. <br />
					&nbsp;&nbsp;&nbsp;Date Issue <br />
					&nbsp;&nbsp;&nbsp;Expiry <br />
				</td>
				<td>
					Philippine <br />
					<span $styledata>
					$docno41 <br />
					$docissued41 <br />
					$docexpired41 <br />
					</span>
				</td>
				<td>
					U.S. Visa <br />
					<span $styledata>
					$docno42 <br />
					$docissued42 <br />
					$docexpired42 <br />
					</span>
				</td>
				<td>
					MCV <br />
					<span $styledata>
					$docnoA4 <br />
					$docissuedA4 <br />
					$docexpiredA4 <br />
					</span>
				</td>
				<td>
					&nbsp;
				</td>
			</tr>
		</table>

	</div>
	<br />
	<div style=\"width:755px;font-size:0.75em;\">
		<u><b>GEARS,SIZE</b></u>
		<br /><br />
		
		<table style=\"width:50%;font-size:1em;\">
			<tr>
				<td>Coverall &nbsp;&nbsp;&nbsp;&nbsp;<span $styledata>$crewsizecoverall</span></td>
				<td>Rain Coat &nbsp;&nbsp;&nbsp;&nbsp;<span $styledata>$crewsizeraincoat</span></td>
				<td>Shoe Size &nbsp;&nbsp;&nbsp;&nbsp;<span $styledata>$crewsizeshoes&nbsp;&nbsp;inch</span></td>
			</tr>
		</table>
		<br /><br />
		<u><b>DRIVER'S LICENSE</b></u>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span $styledata>$crewdriverlic</span>
		<br /><br />
		<u><b>HIRING RESTRICTION</b></u>
		<br /><br />
		$crewhiringrestriction
	
	</div>
";
	
	
echo "
<!--
	<div style=\"width:755px;background-color:White;overflow:hidden;padding:5 5 5 5;\">
		<br />
		<span style=\"font-size:0.7em;\">
		<b>Legend:</b><br />
		$legend		
		</span>
	</div>
-->
</div>";

// if ($print == 1)
	// include('include/printclose.inc');

echo "

</body>

</html>

";

?>