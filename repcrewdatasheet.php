<?php

$kups = "gino";

include("veritas/connectdb.php");
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

if (isset($_GET["print"]))
	$print = $_GET["print"];
else 
	$print = 0;

	
$explimit = "LIMIT 10";
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

</head>
<body style=\"overflow:auto;\">\n
<br><br>
<div style=\"width:755px;background-color:white;\">

	<div style=\"width:75%;height:60px;float:left;background-color:White;overflow:hidden;\">
		<center>
			<span style=\"font-size:1em;font-weight:Bold;\">VERITAS MARITIME CORPORATION</span><br />
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
					<td colspan=\"3\"><span style=\"color:Orange;font-size:1.2em;\" ><b></b></span>
						<span style=\"color:Blue;font-size:1.2em;\" ><b>$crewutilityshow</b></span>
					</td>
				</tr>
				";
////$crewregular				
			
			echo "
				<tr>
					
				</tr>
			";
		}
		
		echo "
		</table>
	</div>

	<div style=\"width:755px;height:100px;overflow:hidden;\">

		<div style=\"width:350px;height:100%;float:left;overflow:hidden;\">
			<table class=\"listrow\" width=\"100%\">
				<tr>
					<th width=\"35%\">Name of Seaman</th>
					<th>:</th>
					<td valign=\"top\" $styleborder>$crewname</td>
				</tr>
				<tr>
					<th>Veritas Experience</th>
					<th>:</th>
					<td valign=\"top\" $styleborder>$vmcyears&nbsp;Years&nbsp;&nbsp; $vmcmonths&nbsp; Months</td>
				</tr>
				<tr>
					<th>Experience on<br /> 
						Highest Rank</th>
					<th>:</th>
					<td valign=\"top\" $styleborder>$exphighrankyears&nbsp; Year&nbsp;&nbsp; $exphighrankmonths Months</td>
				</tr>
			</table>
		</div>
		
		<div style=\"width:250px;height:100%;float:left;overflow:hidden;\">
			<table class=\"listrow\" width=\"100%\">
				<tr>
					<th width=\"45%\">Rank</th>
					<th>:</th>
					<td $styleborder>$currentrank</td>
				</tr>
				<tr>
					<th>Recommended By</th>
					<th>:</th>
					<td $styleborder>$crewrecommendedby</td>
				</tr>
				<tr>
					<th>Experience as Rank</th>
					<th>:</th>
					<td $styleborder>$exprankyears&nbsp; Years&nbsp;&nbsp; $exprankmonths&nbsp; Months</td>
				</tr>
				<tr>
					<th>Rank License</th>
					<th>:</th>
					<td $styleborder>$exphighestrank</td>
				</tr>
			</table>
		</div>
		
		<div style=\"width:130px;height:100%;float:right;background-color:White;overflow:hidden;\">
	";
			$dirfilename = "$basedir/$applicantno.JPG";
			if (checkpath($dirfilename))
			{
				$scale = imageScale($dirfilename,-1,98);
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
	
		<div style=\"width:550px;height:150px;float:left;overflow:hidden;\">
			<table class=\"listrow\" width=\"100%\">
				<tr>
					<th colspan=\"3\" align=\"left\"><u>PERSONAL DATA</u></th>
				</tr>
				<tr>
					<th width=\"20%\">Place of Birth</th>
					<th>:</th>
					<td $styleborder>$crewbplace</td>
				</tr>
				<tr>
					<th>Religion</th>
					<th>:</th>
					<td $styleborder>$crewreligion</td>
				</tr>
				<tr>
					<th>Next of Kin</th>
					<th>:</th>
					<td $styleborder>$crewkinname&nbsp;($crewkinrelation)</td>
				</tr>
				<tr>
					<th>Address</th>
					<th>:</th>
					<td $styleborder>$crewkinaddr1</td>
				</tr>
				<tr>
					<th>Telephone No.</th>
					<th>:</th>
					<td $styleborder>$crewkintelno</td>
				</tr>
				<tr>
					<th>Address</th>
					<th>:</th>
					<td $styleborder>$crewaddress</td>
				</tr>
				<tr>
					<th>Telephone No.</th>
					<th>:</th>
					<td $styleborder>$crewtelno</td>
				</tr>
			</table>
		</div>
		<div style=\"width:200px;height:150px;float:left;background-color:White;overflow:hidden;\">
			<table class=\"listrow\" width=\"95%\">
				<tr>
					<th colspan=\"3\">&nbsp;</th>
				</tr>
				<tr>
					<th>Date of Birth</th>
					<th>:</th>
					<td $styleborder>$crewbdate</td>
				</tr>
				<tr>
					<th>Age</th>
					<th>:</th>
					<td $styleborder>$crewage</td>
				</tr>
				<tr>
					<th>Height(cm)</th>
					<th>:</th>
					<td $styleborder>$crewheight</td>
				</tr>
				<tr>
					<th>Weight(kg)</th>
					<th>:</th>
					<td $styleborder>$crewweight</td>
				</tr>
				<tr>
					<th>Civil Status</th>
					<th>:</th>
					<td $styleborder>$crewcivilstatus</td>
				</tr>
				<tr>
					<th>Driver's License</th>
					<th>:</th>
					<td $styleborder>$crewdriverlic</td>
				</tr>
			</table>
		</div>
		<div style=\"width:755px;height:40px;float:left;background-color:White;overflow:hidden;\">
			<table class=\"listrow\" width=\"100%\">
				<tr>
					<th colspan=\"8\" align=\"left\"><u>MEDICAL</u></th>
				</tr>
				<tr>
					<th align=\"left\">PEME on Process:</th>
					<td $styleborder valign=\"top\" align=\"left\"><b>[$crewmeddate]</b></td>
					<th>HBA1c Test:</th>
					<td $styleborder align=\"left\">$crewmedhbatest</td>
					<th align=\"left\">Conducted By:</th>
					<td $styleborder align=\"left\">$crewmedclinic2</td>
					<th align=\"left\">Result:</th>
					<td $styleborder align=\"left\">$crewmedrecommend</td>
				</tr>
			</table>
		</div>
		<div style=\"width:755px;height:40px;float:left;background-color:White;overflow:hidden;\">
			<table class=\"listrow\" width=\"100%\">
				<tr>
					<th colspan=\"6\" align=\"left\"><u>EDUCATIONAL ATTAINMENT</u></th>
				</tr>
				<tr>
					<th align=\"left\">School:</th>
					<td $styleborder align=\"left\">$creweducschool</td>
					<th align=\"left\">Course:</th>
					<td $styleborder align=\"left\">$creweduccourse</td>
					<th align=\"left\">Year:</th>
					<td $styleborder align=\"left\">$creweducdategrad</td>
				</tr>
			</table>
		</div>
	</div>
	<br />
	<div style=\"width:755px;\">
		<div style=\"width:460px;height:310px;float:left;background-color:White;overflow:hidden;\">
		";
		$style = "style=\"\"";
		
		echo "
			<table width=\"100%\" cellspacing=\"0\" cellpadding=\"1\" border=1 style=\"font-size:0.7em;font-weight:Bold;\">
				<tr style=\"border:1px solid black;\">
					<th colspan=\"5\" align=\"center\">DOCUMENTS AND LICENSES</th>
				</tr>
				<tr>
					<td width=\"23%\" align=\"center\" $style>Document</td>
					<td width=\"10%\" align=\"center\" $style>Rank</td>
					<td width=\"30%\" align=\"center\" $style>Doc. No.</td>
					<td width=\"17%\" align=\"center\" $style>Issued</td>
					<td width=\"17%\" align=\"center\" $style>Expiry</td>
				</tr>
		";
				$style = "style=\"color:Black;\"";
	
				while ($rowdocuments = mysql_fetch_array($qrydocuments))
				{
					$doccode = $rowdocuments["DOCCODE"];
					$doccode1 = $rowdocuments["DOCCODE1"];
					$document = $rowdocuments["DOCUMENT"];
					$documentno = $rowdocuments["DOCNO"];
					$docissued = $rowdocuments["DATEISSUED"];
					$docexpired = $rowdocuments["DATEEXPIRED"];
					$rankcode = $rowdocuments["RANKCODE"];
					$rankalias = $rowdocuments["ALIAS1"];
					$doctype = $rowdocuments["TYPE"];
					$dochasexpiry = $rowdocuments["HASEXPIRY"];
					
						$doctmp1 = "documentno" . $doccode1;
						$doctmp2 = "docissued" . $doccode1;
						$doctmp3 = "docexpired" . $doccode1;
						$doctmp4 = "rankalias" . $doccode1;
						
							
						$$doctmp1 = "N/A";
						$$doctmp2 = "N/A";
						$$doctmp3 = "N/A";
						$$doctmp4 = "N/A";
						
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
									$$doctmp1 = "<a href=\"#\" onclick=\"openWindow('$dirfilename', '$doccode1' ,700, 500);\" 
													style=\"font-weight:Bold;$color\" title=\"Click to View Document.\" >$documentno</a>";
								else 
									$$doctmp1 = "&nbsp; <span style=\"font-weight:Bold;$color\" title=\"Not yet scanned.\">$documentno</span>";
							}
							else 
							{
								$$doctmp1 = "&nbsp; <span style=\"font-weight:Bold;$color\">NO DOCNO</span>";
							}
								
							if (!empty($docissued))
								$$doctmp2 = date("dMY",strtotime($docissued));
							else 
								$$doctmp2 = "---";
								
							if (!empty($docexpired))
								$$doctmp3 = date("dMY",strtotime($docexpired));
							else 
								$$doctmp3 = "---";
								
							if (!empty($rankalias))
								$$doctmp4 = "&nbsp;" . $rankalias;
						}
							

				}
	
				
	echo "			
				<tr>
					<td $style>Phil. Seabook</td>
					<td align=\"center\" $style>$rankaliasF2</td>
					<td align=\"center\" $style>$documentnoF2</td>
					<td align=\"center\" $style>$docissuedF2</td>
					<td align=\"center\" $style>$docexpiredF2</td>
				</tr>
				<tr>
					<td $style>Phil. License</td>
					<td align=\"center\" $style>$rankaliasF1</td>
					<td align=\"center\" $style>$documentnoF1</td>
					<td align=\"center\" $style>$docissuedF1</td>
					<td align=\"center\" $style>$docexpiredF1</td>
				</tr>
	<!--
				<tr>
					<td $style>STCW</td>
					<td align=\"center\" $style>$rankalias40</td>
					<td align=\"center\" $style>$documentno40</td>
					<td align=\"center\" $style>$docissued40</td>
					<td align=\"center\" $style>$docexpired40</td>
				</tr>
	-->
				<tr>
					<td $style>STCW</td>
					<td align=\"center\" $style>$rankalias16</td>
					<td align=\"center\" $style>$documentno16</td>
					<td align=\"center\" $style>$docissued16</td>
					<td align=\"center\" $style>$docexpired16</td>
				</tr>
	";
			if ($documentno18 == "N/A")
			{
				echo "
					<tr>
						<td $style>COC</td>
						<td align=\"center\" $style>$rankaliasC0</td>
						<td align=\"center\" $style>$documentnoC0</td>
						<td align=\"center\" $style>$docissuedC0</td>
						<td align=\"center\" $style>$docexpiredC0</td>
					</tr>
					";
			}
			else 
			{
				echo "
					<tr>
						<td $style>COC</td>
						<td align=\"center\" $style>$rankalias18</td>
						<td align=\"center\" $style>$documentno18</td>
						<td align=\"center\" $style>$docissued18</td>
						<td align=\"center\" $style>$docexpired18</td>
					</tr>
					";
			}
				
	echo "
				<tr>
					<td $style>Panama Seabook</td>
					<td align=\"center\" $style>$rankaliasP2</td>
					<td align=\"center\" $style>$documentnoP2</td>
					<td align=\"center\" $style>$docissuedP2</td>
					<td align=\"center\" $style>$docexpiredP2</td>
				</tr>
				<tr>
					<td $style>Panama License</td>
					<td align=\"center\" $style>$rankaliasP1</td>
					<td align=\"center\" $style>$documentnoP1</td>
					<td align=\"center\" $style>$docissuedP1</td>
					<td align=\"center\" $style>$docexpiredP1</td>
				</tr>
				<tr>
					<td $style>GMDSS Seabook</td>
					<td align=\"center\" $style>$rankalias44</td>
					<td align=\"center\" $style>$documentno44</td>
					<td align=\"center\" $style>$docissued44</td>
					<td align=\"center\" $style>$docexpired44</td>
				</tr>
				<tr>
					<td $style>GMDSS License</td>
					<td align=\"center\" $style>$rankalias28</td>
					<td align=\"center\" $style>$documentno28</td>
					<td align=\"center\" $style>$docissued28</td>
					<td align=\"center\" $style>$docexpired28</td>
				</tr>
				<tr>
					<td $style>Passport</td>
					<td align=\"center\" $style>$rankalias41</td>
					<td align=\"center\" $style>$documentno41</td>
					<td align=\"center\" $style>$docissued41</td>
					<td align=\"center\" $style>$docexpired41</td>
				</tr>
				<tr>
					<td $style>U.S. Visa</td>
					<td align=\"center\" $style>$rankalias42</td>
					<td align=\"center\" $style>$documentno42</td>
					<td align=\"center\" $style>$docissued42</td>
					<td align=\"center\" $style>$docexpired42</td>
				</tr>
				<tr>
					<td $style>Yellow Fever</td>
					<td align=\"center\" $style>$rankalias51</td>
					<td align=\"center\" $style>$documentno51</td>
					<td align=\"center\" $style>$docissued51</td>
					<td align=\"center\" $style>$docexpired51</td>
				</tr>
				<tr>
					<td $style>JIS License</td>
					<td align=\"center\" $style>$rankaliasJ1</td>
					<td align=\"center\" $style>$documentnoJ1</td>
					<td align=\"center\" $style>$docissuedJ1</td>
					<td align=\"center\" $style>$docexpiredJ1</td>
				</tr>
				<tr>
					<td $style>HK License</td>
					<td align=\"center\" $style>$rankaliasH1</td>
					<td align=\"center\" $style>$documentnoH1</td>
					<td align=\"center\" $style>$docissuedH1</td>
					<td align=\"center\" $style>$docexpiredH1</td>
				</tr>
				<tr>
					<td $style>Australian MCV</td>
					<td align=\"center\" $style>$rankaliasA4</td>
					<td align=\"center\" $style>$documentnoA4</td>
					<td align=\"center\" $style>$docissuedA4</td>
					<td align=\"center\" $style>$docexpiredA4</td>
				</tr>
	";
	
	echo "
			</table>
		</div>
	
		<div style=\"width:290px;height:310px;background-color:White;overflow:hidden;\">
	";

	$rowmanagement = mysql_fetch_array($qrymanagement);
	
	$managementcode = $rowmanagement["MANAGEMENTCODE"];
	$xcompany = $rowmanagement["COMPANY"];
	
	if ($managementcode == 101 || $managementcode == 102)
	{
		$traintitle1 = "TNKC MANDATORY TRAINING(IN-HOUSE)";
		$traintitle2 = "TNKC MANDATORY TRAINING(OUTSIDE)";
		$ismtitle = "TNKC ";
	}
	else 
	{
		$traintitle1 = "TRAINING(IN-HOUSE)";
		$traintitle2 = "TRAINING(OUTSIDE)";
		$ismtitle = "";
	}

	$style = "style=\"color:Black;\"";
	
		echo "
			<table width=\"100%\" cellspacing=\"0\" cellpadding=\"1\" border=1 style=\"font-size:0.7em;font-weight:Bold;\">
				<tr>
					<th colspan=\"3\" align=\"center\" $style>$traintitle1</th>
				</tr>
				<tr>
					<td width=\"33%\" align=\"center\" $style >Training</td>
					<td width=\"33%\" align=\"center\" $style >Issued</td>
					<td width=\"33%\" align=\"center\" $style >Grade</td>
				</tr>
		";
				
	
				while ($rowtrainings = mysql_fetch_array($qrytrainings))
				{
					$certcode = $rowtrainings["DOCCODE"];
					$certcode1 = $rowtrainings["DOCCODE1"];
					$certificate = $rowtrainings["DOCUMENT"];
					$certno = $rowtrainings["DOCNO"];
					$certissued = $rowtrainings["DATEISSUED"];
					$certgrade = $rowtrainings["GRADE"];
					$certrankcode = $rowtrainings["RANKCODE"];
					$certrankalias = $rowtrainings["ALIAS1"];
					$certtype = $rowtrainings["TYPE"];
					$certhasexpiry = $rowdocuments["HASEXPIRY"];
					
						$certtmp1 = "certno" . $certcode1;
						$certtmp2 = "certissued" . $certcode1;
						$certtmp3 = "certgrade" . $certcode1;
						$certtmp4 = "certrankalias" . $certcode1;
						
						if ($certno != "")
							$$certtmp1 = "&nbsp;" . $certno;
						else 
							$$certtmp1 = "N/A";
							
						if ($certissued != "")
						{
							$dirfilename = $basedirdocs . $applicantno . "/" . $certtype . "/" . $certcode1 . ".pdf";
							
							if (checkpath($dirfilename))
								$$certtmp2 = "<a href=\"#\" onclick=\"openWindow('$dirfilename', '$certcode1' ,700, 500);\" 
												style=\"font-weight:Bold;color:Black;\" title=\"Click to View Document.\" >" . date("dMY",strtotime($certissued)) . "</a>";
							else 
								$$certtmp2 = "&nbsp; <span style=\"font-weight:Bold;color:Black;\" title=\"Not yet scanned.\">" . date("dMY",strtotime($certissued)) . "</span>";
						}
						else 
							$$certtmp2 = "N/A";
							
						if ($certgrade != "")
							$$certtmp3 = "&nbsp;" . $certgrade;
						else 
							$$certtmp3 = "N/A";
							
						if ($certrankalias != "")
							$$certtmp4 = "&nbsp;" . $certrankalias;
						else 
							$$certtmp4 = "N/A";
				}
	
	echo "			
	<!--
				<tr>
					<td $style>$ismtitle ISM/SMS</td>
					<td align=\"center\" $style>$certissuedI1</td>
					<td align=\"center\" $style>$certgradeI1</td>
				</tr>
	-->
				<tr>
					<td $style>$ismtitle ISM/SMS</td>
					<td align=\"center\" $style>$certissuedT0</td>
					<td align=\"center\" $style>$certgradeT0</td>
				</tr>
	<!--
				<tr>
					<td $style>ISPS</td>
					<td align=\"center\" $style>$certissued57</td>
					<td align=\"center\" $style>$certgrade57</td>
				</tr>
	-->
				<tr>
					<td $style>ISPS</td>
					<td align=\"center\" $style>$certissuedI4</td>
					<td align=\"center\" $style>$certgradeI4</td>
				</tr>
				<tr>
					<td $style>BASS</td>
					<td align=\"center\" $style>$certissuedI0</td>
					<td align=\"center\" $style>$certgradeI0</td>
				</tr>
				<tr>
					<td $style>TTOS(PCC)</td>
					<td align=\"center\" $style>$certissuedT8</td>
					<td align=\"center\" $style>$certgradeT8</td>
				</tr>
				<tr>
					<td $style>TTOS(Corona)</td>
					<td align=\"center\" $style>$certissuedT1</td>
					<td align=\"center\" $style>$certgradeT1</td>
				</tr>
				<tr>
					<th colspan=\"3\" align=\"left\">&nbsp;</u></th>
				</tr>
				<tr>
					<th colspan=\"3\" align=\"center\"><u>$traintitle2</u></th>
				</tr>
				<tr>
					<td $style>S.S.B.T</td>
					<td align=\"center\" $style>$certissued79</td>
					<td align=\"center\" $style>$certgrade79</td>
				</tr>
				<tr>
					<td $style>S.S.O</td>
					<td align=\"center\" $style>$certissued49</td>
					<td align=\"center\" $style>$certgrade49</td>
				</tr>
				<tr>
					<td $style>E.R.S</td>
					<td align=\"center\" $style>$certissuedE1</td>
					<td align=\"center\" $style>$certgradeE1</td>
				</tr>
				<tr>
					<td $style>A.I.S</td>
					<td align=\"center\" $style>$certissuedA0</td>
					<td align=\"center\" $style>$certgradeA0</td>
				</tr>
				<tr>
					<td $style>E.C.D.I.S</td>
					<td $style align=\"center\">$certissued54</td>
					<td $style align=\"center\">$certgrade54</td>
				</tr>
				<tr>
					<td $style>&nbsp;</td>
					<td $style align=\"center\">&nbsp;</td>
					<td $style align=\"center\">&nbsp;</td>
				</tr>
				<tr>
					<td $style>&nbsp;</td>
					<td $style align=\"center\">&nbsp;</td>
					<td $style align=\"center\">&nbsp;</td>
				</tr>
			</table>
	";
		
	echo "
		</div>
	
	</div>
	<div style=\"width:755px;background-color:White;overflow:hidden;\">
		
		<table width=\"100%\" cellspacing=\"0\" cellpadding=\"1\" style=\"font-size:0.7em;\" border=1>
			<tr>
				<th colspan=\"15\" align=\"center\">EXPERIENCE SEABASED</th>
			</tr>
			<tr>
				<th align=\"center\">Company</th>
				<th align=\"center\">Vessel</th>
				<th align=\"center\">Rank</th>
				<th align=\"center\">GRT</th>
				<th align=\"center\">Engine/KW</th>
				<th align=\"center\">Type</th>
				<th align=\"center\">Trade<br />Route</th>
				<th align=\"center\">Embkd</th>
				<th align=\"center\">Disembkd</th>
				<th align=\"center\">Mo</th>
				<th align=\"center\">Reason</th>
<!--
				<th align=\"center\">Pro</th>
				<th align=\"center\">Rank</th>
-->
				<th align=\"center\">Grd</th>

			</tr>
	";
		
		
		while($rowexperience = mysql_fetch_array($qryexperience))
		{
			$pos = $rowexperience["POS"];
			$ccid = $rowexperience["CCID"];
			$company = $rowexperience["COMPANY"];
			$vessel = $rowexperience["VESSEL"];
			$vesselname = $rowexperience["VESSELNAME"];
			$rankalias = $rowexperience["RANKALIAS"];
			$grosston = $rowexperience["GROSSTON"];
			$engine = $rowexperience["ENGINE"];
			$vesseltype = $rowexperience["VESSELTYPECODE"];
			$traderoute = $rowexperience["TRADEROUTECODE"];
			$enginepower = $rowexperience["ENGINEPOWER"];
			$dateemb = $rowexperience["DATEEMB"];
			$datedisemb = $rowexperience["DATEDISEMB"];
			if ($rowexperience["DISEMBREASONCODE"] != "")
				$disembreasoncode = $rowexperience["DISEMBREASONCODE"];
			else 
				$disembreasoncode = "--";
				
			$checkpromote = $rowexperience["CHKPROMOTE"];
			$checkpromote2 = $rowexperience["CCID2"];
			
			if (!empty($rowexperience["DEPMNLDATE"]))
				$depmnldate = $rowexperience["DEPMNLDATE"];
			else 
				$depmnldate = "";
			
			if (!empty($rowexperience["ARRMNLDATE"]))
				$arrmnldate = $rowexperience["ARRMNLDATE"];
			else 
				$arrmnldate = "";
			
			$months = round(((strtotime($datedisemb) - strtotime($dateemb)) / 86400) / 30);
			
			$dateembview = date("dMY",strtotime($dateemb));
			
			if (!empty($arrmnldate) || $pos == 2)  //kapag wala pang arrive manila date --> "Present" ang display
				$datedisembview = date("dMY",strtotime($datedisemb));
			else 
			{
				if (empty($checkpromote2))
					$datedisembview = "Present";
				else
					$datedisembview = "PROMOTED";
			}
			
			if ($pos == 1)
			{
				$gradeshow = computegrade($ccid);
				
				if ($gradeshow == 0)
					$gradeshow = "--";
				
				if ($gradeshow > 100)
					$gradeshow = "ERR";
			}
			else 
				$gradeshow = "--";
				
//			$gradeshow = "--";
			
			if (!empty($depmnldate) || !empty($checkpromote) || $pos == 2)
			{
				echo "
				<tr>
					<td align=\"left\">$company</td>
					<td align=\"left\" title=\"$vesselname\">$vessel</td>
					<td align=\"center\">&nbsp;$rankalias</td>
					<td align=\"right\">&nbsp;$grosston</td>
					<td align=\"center\">&nbsp;".$engine."/".$enginepower."</td>
					<td align=\"center\">&nbsp;$vesseltype</td>
					<td align=\"center\">&nbsp;$traderoute</td>
					<td align=\"center\">$dateembview</td>
					<td align=\"center\">$datedisembview</td>
					<td align=\"center\">&nbsp;$months</td>
					<td align=\"center\">$disembreasoncode</td>
					<td align=\"right\">$gradeshow</td>
	
				</tr>
				";
			}
		}

	echo "
		</table>
		
		
	
	</div>
";
	
	
echo "
	<div style=\"width:755px;background-color:White;overflow:hidden;padding:5 5 5 5;\">
		<br />
		<span style=\"font-size:0.7em;\">
		<b>Legend:</b><br />
		$legend		
		</span>
	</div>
	
</div>";

if ($print == 1)
	include('include/printclose.inc');

echo "

</body>

</html>

";

?>