<?php

include("veritas/connectdb.php");
include("veritas/include/functions.inc");

session_start();

// PRINTING OF ENDORSEMENT LETTER - PRINCIPAL TRAINING

$currentdate = date('Y-m-d H:i:s');
$datenow = date('d F Y');
$basedir = "idpics/";
$basedirdocs = "docimages/";

if(isset($_SESSION["employeeid"]))
	$employeeid=$_SESSION["employeeid"];

//if (isset($_GET["vesselcode"]))
//	$vesselcode = $_GET["vesselcode"];
//
//if (isset($_GET["traincode"]))
//	$traincode = $_GET["traincode"];

if (isset($_GET["endorsementidno"]))
	$endorsementidno = $_GET["endorsementidno"];

if (isset($_GET["schedid"]))
	$schedid = $_GET["schedid"];

$qrylist = mysql_query("SELECT tc.TRAINING,
					IF(td.TRAINCENTERCODE IS NOT NULL,trc2.TRAINCENTER,trc.TRAINCENTER) AS TRAINCENTER,
					IF(td.TRAINCENTERCODE IS NOT NULL,trc2.ADDRESS,trc.ADDRESS) AS ADDRESS,
					IF(td.TRAINCENTERCODE IS NOT NULL,trc2.ATTENTION,trc.ATTENTION) AS ATTENTION,
					IF(td.TRAINCENTERCODE IS NOT NULL,trc2.ATTDESIG,trc.ATTDESIG) AS ATTDESIG,
					eh.NOTE,eh.PARTICIPANTNO,eh.DATEISSUED,th.DATEFROM,th.DATETO,
					CONCAT(e1.CERTRANK,' ',e1.GNAME,' ',LEFT(e1.MNAME,1),'. ',e1.FNAME) AS SIGNNAME1,
					CONCAT(e2.CERTRANK,' ',e2.GNAME,' ',LEFT(e2.MNAME,1),'. ',e2.FNAME) AS SIGNNAME2,
					CONCAT(e3.CERTRANK,' ',e3.GNAME,' ',LEFT(e3.MNAME,1),'. ',e3.FNAME) AS NOTEDBY,
					d1.DESIGNATION AS DESIG1,d2.DESIGNATION AS DESIG2,d3.DESIGNATION AS NOTEDBYDESIG
					FROM endorsementhdr eh
					LEFT JOIN trainingschedhdr th ON th.SCHEDID=$schedid
					LEFT JOIN trainingscheddtl td ON td.SCHEDID=th.SCHEDID
					LEFT JOIN trainingcourses tc ON tc.TRAINCODE=th.TRAINCODE
					LEFT JOIN trainingcenter trc ON trc.TRAINCENTERCODE=tc.TRAINCENTERCODE
					LEFT JOIN trainingcenter trc2 ON trc2.TRAINCENTERCODE=td.TRAINCENTERCODE
					LEFT JOIN employee e1 ON e1.EMPLOYEEID=eh.SIGN1
					LEFT JOIN employee e2 ON e2.EMPLOYEEID=eh.SIGN2
					LEFT JOIN employee e3 ON e3.EMPLOYEEID=eh.NOTEDBY
					LEFT JOIN designation d1 ON d1.DESIGNATIONCODE=e1.DESIGNATIONCODE
					LEFT JOIN designation d2 ON d2.DESIGNATIONCODE=e2.DESIGNATIONCODE
					LEFT JOIN designation d3 ON d3.DESIGNATIONCODE=e3.DESIGNATIONCODE
					WHERE eh.ENDORSEID=$endorsementidno
					LIMIT 1
					") or die(mysql_errno());

$rowlist = mysql_fetch_array($qrylist);
$xtraincenter = $rowlist["TRAINCENTER"];
$xtraining = $rowlist["TRAINING"];
$xaddress = $rowlist["ADDRESS"];
$xattention = $rowlist["ATTENTION"];
$xattdesig = $rowlist["ATTDESIG"];
$xnote = $rowlist["NOTE"];

if (!empty($rowlist["DATEISSUED"]))
	$xdateissued = date("d F Y",strtotime($rowlist["DATEISSUED"]));
else 
	$xdateissued = date("d F Y"); //date today
	
if (!empty($rowlist["DATEFROM"]))
	$xdatefrom = date("d F Y",strtotime($rowlist["DATEFROM"]));
else 
	$xdatefrom = "";
	
if (!empty($rowlist["DATETO"]))
	$xdateto = date("d F Y",strtotime($rowlist["DATETO"]));
else 
	$xdateto = "";
	
if (strtotime($rowlist["DATEFROM"]) == strtotime($rowlist["DATETO"]))
	$xdateshow = date("d F Y",strtotime($rowlist["DATEFROM"]));
else 
	$xdateshow = $xdatefrom . " to " . $xdateto;
	
$xsignname1 = $rowlist["SIGNNAME1"];
$xsignname2 = $rowlist["SIGNNAME2"];
$xnotedby = $rowlist["NOTEDBY"];
$xdesig1 = $rowlist["DESIG1"];
$xdesig2 = $rowlist["DESIG2"];
$xnotedbydesig = $rowlist["NOTEDBYDESIG"];



function extractlist($appno2,&$vessel,&$rank,&$etd,&$lastvessel)
{
	$qryallexperience = mysql_query("SELECT IF (cpr.CCIDPROMOTE IS NULL,0,1) AS PROMOTED,x.* FROM
									(
										SELECT '1' AS POS,cc.CCID,
										v.ALIAS2 AS VESSEL,v.VESSELCODE,r.ALIAS1 AS RANKALIAS,r.RANKCODE,
										cc.DATEEMB,IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,cc.DISEMBREASONCODE,dr.REASON,
										cc.ARRMNLDATE,cc.DEPMNLDATE
										FROM crewchange cc
										LEFT JOIN crew c ON c.APPLICANTNO=cc.APPLICANTNO
										LEFT JOIN vessel v ON v.VESSELCODE=cc.VESSELCODE
										LEFT JOIN rank r ON r.RANKCODE=cc.RANKCODE
										LEFT JOIN disembarkreason dr ON dr.DISEMBREASONCODE=cc.DISEMBREASONCODE
										WHERE cc.APPLICANTNO=$appno2
										
										UNION
										
										SELECT '2' AS POS,ce.IDNO,
										ce.VESSEL,NULL,r.ALIAS1 AS RANKALIAS,r.RANKCODE,
										DATEEMB,DATEDISEMB,ce.DISEMBREASONCODE,dr.REASON,NULL,NULL
										FROM crewexperience ce
										LEFT JOIN crew c ON c.APPLICANTNO=ce.APPLICANTNO
										LEFT JOIN rank r ON r.RANKCODE=ce.RANKCODE
										LEFT JOIN disembarkreason dr ON dr.DISEMBREASONCODE=ce.DISEMBREASONCODE
										WHERE ce.APPLICANTNO=$appno2
									) x
									LEFT JOIN crewpromotionrelation cpr ON cpr.CCIDPROMOTE=x.CCID
									ORDER BY x.DATEDISEMB DESC
									LIMIT 2
								") or die(mysql_error());
	
	$cnt = 0;
	
	while ($rowallexperience = mysql_fetch_array($qryallexperience))
	{
//		echo "<script>alert('1---Vessel=$vessel; Rank:$rank; ETD:$etd;LastVessel:$lastvessel');</script>";
		
		if ($rowallexperience["POS"] == "1")
			$zexptype = "Veritas";
		else 
			$zexptype = "Outside VMC";
			
		$zvessel = $rowallexperience["VESSEL"];
		$zrankalias = $rowallexperience["RANKALIAS"];
		$zpromoted = $rowallexperience["PROMOTED"];
		
		if (!empty($rowallexperience["DATEEMB"]))
			$zdateemb = date("dMY",strtotime($rowallexperience["DATEEMB"]));
		else 
			$zdateemb = "";
			
		if (!empty($rowallexperience["DATEDISEMB"]))
			$zdatedisemb = date("dMY",strtotime($rowallexperience["DATEDISEMB"]));
		else 
			$zdatedisemb = "";
			
		if (!empty($rowallexperience["ARRMNLDATE"]))
			$zarrmnldate = date("dMY",strtotime($rowallexperience["ARRMNLDATE"]));
		else 
			$zarrmnldate = "";
			
		if (!empty($rowallexperience["DEPMNLDATE"]))
			$zdepmnldate = date("dMY",strtotime($rowallexperience["DEPMNLDATE"]));
		else 
			$zdepmnldate = "";
			
		$zdisembreasoncode = $rowallexperience["DISEMBREASONCODE"];
		$zreason = $rowallexperience["REASON"];
		
		
		if ($cnt == 0)
		{
			if ($zexptype == "Veritas")
			{
				if (strtotime($zdatedisemb) <= strtotime($datenow))
				{
					if (!empty($zarrmnldate))
						$appstatus = "STANDBY";
					else 
						$appstatus = "STANDBY (No Arrive Manila)";
						
					$lastvessel = $zvessel;
				}
				else 
				{
					if (strtotime($zdateemb) <= strtotime($datenow) && !empty($zdepmnldate))
					{
						if ($zpromoted != 1)
						{
							$appstatus = "ONBOARD";
							$lastvessel = $zvessel;
						}
						else 
							$appstatus = "PROMOTED ONBOARD";
					}
					else 
					{
						if ($zpromoted != 1)
						{
							$appstatus = "EMBARKING";
							$vessel = $zvessel;
							$rank = $zrankalias;
							$etd = $zdateemb;
						}
						else 
						{
							$appstatus = "PROMOTED ONBOARD";
						}
					}
				}
			}
			else //OUTSIDE (NO VMC EXPERIENCE)
			{
				$qryapplicantstatus = mysql_query("SELECT ap.VMCRANKCODE,ap.VMCVESSELCODE,ap.VMCETD,r.ALIAS1 AS RANKALIAS,v.VESSEL
													FROM applicantstatus ap
													LEFT JOIN rank r ON r.RANKCODE=ap.VMCRANKCODE
													LEFT JOIN vessel v ON v.VESSELCODE=ap.VMCVESSELCODE
													WHERE ap.APPLICANTNO=$applicantno
												") or die(mysql_error());
				
				if (mysql_num_rows($qryapplicantstatus) == 1)
				{
					$rowapplicantstatus = mysql_fetch_array($qryapplicantstatus);
					
					$appstatus = "NEW HIRE";
					
					$rank = $rowapplicantstatus["RANKALIAS"];
					$vessel = $rowapplicantstatus["VESSEL"];
					
					if (!empty($rowapplicantstatus["VMCETD"]))
						$etd = date("dMY",strtotime($rowapplicantstatus["VMCETD"]));
				}
				else 
				{
					$appstatus = "NO VMC EXPERIENCE / NO LINE-UP YET";
				}
				
			}
			
		}
		else
		{
			if ($appstatus == "EMBARKING")
				$lastvessel = $zvessel;
			
			if ($appstatus == "PROMOTED ONBOARD")
			{
				if ($cnt==2) //IF PROMOTED, 3rd ROW IS THE LAST VESSEL
				{
					$vessel = $zvessel;
					$rank = $zrankalias;
					$etd = $zdateemb;
				}
			}
			
		}
		
//		echo "<script>alert('2---Vessel=$vessel; Rank:$rank; ETD:$etd;LastVessel:$lastvessel');</script>";
		
		$cnt++;
	}
}

echo "
<html>\n
<head>\n
<title>
Endorsement Letter - Principal Training
</title>

<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>

</head>
<body style=\"overflow:auto;\">\n

<div style=\"width:780px;margin-left:20px;margin-right:20px;\">
";
	$stylehdr = "style=\"font-size:0.8em;font-weight:Bold;\"";
	$styledtl = "style=\"font-size:0.8em;\"";
echo "
	<table width=\"90%\">
		<tr>
			<td width=\"10%\">
				<img src=\"images/veritas_small.jpg\" border=0 />
			</td>
			<td width=\"70%\">
				<center>
					<span style=\"font-size:1.3em;font-weight:Bold;\">VERITAS MARITIME CORPORATION</span><br />
					<span style=\"font-size:0.9em;\">15th Floor, MARC 2000 Tower, 1973 Taft Avenue, Malate, Manila, Philippines</span><br />
					<span style=\"font-size:0.9em;\">Telefax (632) 526-1029&nbsp;&nbsp;(632) 338-0318&nbsp;&nbsp;Tlx 051-94078312-VMCCG</span><br />
					<span style=\"font-size:0.9em;\">Tels. (632) 5268041&nbsp;&nbsp;(632) 5362775&nbsp;&nbsp;(632) 5362757</span><br />
					<span style=\"font-size:0.9em;\">E-mail Address: <i><u>vmcgroup@veritas.com.ph</u>&nbsp;&nbsp;<u>veritas_mc@pacific.net.ph</u></i></span><br />
				</center>
			</td>
		</tr>
	</table>
	
	<table style=\"width:70%;float:left;\">
		<tr><td width=\"210px\" $stylehdr>$xdateissued</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td width=\"210px\" $stylehdr>$xtraincenter</td></tr>
		<tr><td width=\"210px\" $styledtl>$xaddress</td></tr>
	</table>
	
	<span style=\"font-size:0.7em;font-weight:Bold;\">(Endorsement IDNo. <u>$endorsementidno</u>)</span>
	
	<br />
	
	<table width=\"400px\">
		<tr>
			<td $stylehdr>ATTN</td>
			<td $stylehdr>:</td>
			<td $stylehdr>$xattention</td>
		</tr>
		<tr>
			<td $stylehdr>&nbsp;</td>
			<td $stylehdr>&nbsp;</td>
			<td $stylehdr>$xattdesig</td>
		</tr>
		<tr>
			<td $stylehdr>RE</td>
			<td $stylehdr>:</td>
			<td $stylehdr><u>$xtraining</u></td>
		</tr>
	</table>
	<br />
	<div style=\"width:80%;font-size:0.8em;\">
		Dear Sirs,<br /><br />
		As per your schedule, appended herewith are our candidates for your $xtraining to be held on<br />
		<b><i>$xdateshow</i></b>.
	</div>
	<br />

	<div style=\"width:100%;\">
	";

		$qrylistcrew = mysql_query("SELECT ct.APPLICANTNO,CONCAT(c.FNAME,', ',c.GNAME,' ',c.MNAME) AS NAME,c.BIRTHDATE
									FROM crewtraining ct
									LEFT JOIN crew c ON c.APPLICANTNO=ct.APPLICANTNO
									WHERE ct.SCHEDID=$schedid AND ct.ENDORSEID=$endorsementidno
							") or die(mysql_errno());

		echo "
		<table width=\"90%\" style=\"font-size:0.8em;\">
			<tr>
				<th align=\"center\" width=\"10%\" style=\"font-size:0.9em;\"><u>RANK</u></th>
				<th align=\"center\" width=\"35%\" style=\"font-size:0.9em;\"><u>CREW NAME</u></th>
				<th align=\"center\" width=\"20%\" style=\"font-size:0.9em;\"><u>LAST VESSEL</u></th>
				<th align=\"center\" width=\"20%\" style=\"font-size:0.9em;\"><u>ASSIGNED VESSEL</u></th>
				<th align=\"center\" width=\"15%\" style=\"font-size:0.9em;\"><u>DATE OF BIRTH</u></th>
			</tr>
		";
		
		while ($rowlistcrew = mysql_fetch_array($qrylistcrew))
		{
			$appno2 = $rowlistcrew["APPLICANTNO"];
			$crewname = $rowlistcrew["NAME"];
			
			if (!empty($rowlistcrew["BIRTHDATE"]))
				$birthdate = date("dMY",strtotime($rowlistcrew["BIRTHDATE"]));
			else 
				$birthdate = "";
		
			$vessel = "TBA";
			$rank = "";
			$etd = "";
			$lastvessel = "";
			
			extractlist($appno2,$vessel,$rank,$etd,$lastvessel);
			
			echo "
			<tr>
				<td valign=\"top\">$rank</td>
				<td valign=\"top\">$crewname</td>
				<td valign=\"top\">$lastvessel</td>
				<td valign=\"top\">$vessel</td>
				<td valign=\"top\">$birthdate</td>
			</tr>
			";
		}
		
		echo "
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td colspan=\"5\">NOTE: $xnote</td>
			</tr>
		</table>
		";
		
	echo "
	</div>
	<br />
	
	<div style=\"width:80%;font-size:0.8em;\">
		Thank you very much for your usual prompt attention with regards to this matter.<br />
		<br />
		<br />
		Very truly yours,<br />
		<br />
		<br />
		
		<table width=\"100%\">
			<tr>
				<td width=\"40%\" style=\"font-size:0.8em;\"><b>$xsignname1</b><br />$xdesig1</td>
				<td width=\"20%\">&nbsp;</td>
				<td width=\"40%\" style=\"font-size:0.8em;\"><b>$xsignname2</b><br />$xdesig2</td>
			</tr>
			<tr><td colspan=\"3\">&nbsp;</td></tr>
			<tr><td colspan=\"3\">&nbsp;</td></tr>
			<tr>
				<td style=\"font-size:0.8em;\">Noted By:</td>
				<td style=\"font-size:0.8em;\">&nbsp;</td>
				<td style=\"font-size:0.8em;\">Received By:  ______________________</td>
			</tr>
			<tr><td colspan=\"3\">&nbsp;</td></tr>
			<tr>
				<td style=\"font-size:0.8em;\"><b>$xnotedby</b><br />$xnotedbydesig</td>
				<td>&nbsp;</td>
				<td style=\"font-size:0.8em;\">Date Received:______________________</td>
			</tr>
		</table>
		
	</div>
	
	
</div>";


include('include/printclose.inc');

echo "

</body>

</html>

";

?>