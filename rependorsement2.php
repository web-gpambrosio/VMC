<?php

include("veritas/connectdb.php");
session_start();

// PRINTING OF ENDORSEMENT LETTER - OUTSIDE TRAINING

$currentdate = date('Y-m-d H:i:s');
$datenow = date('d F Y');
$basedir = "idpics/";
$basedirdocs = "docimages/";

if(isset($_SESSION["employeeid"]))
	$employeeid=$_SESSION["employeeid"];

if (isset($_GET["endorsementidno"]))
	$endorsementidno = $_GET["endorsementidno"];

if (isset($_GET["idno"]))
	$idno = $_GET["idno"];


function checkpath($path)
{
	if (is_file($path))
		return true;
	else 
		return false;
}
	
function imageScale($image, $newWidth, $newHeight)
{
    if(!$size = @getimagesize($image))
        die("Unable to get info on image $image");
    $ratio = ($size[0] / $size[1]);
    //scale by height
    if($newWidth == -1)
    {
        $ret[1] = $newHeight;
        $ret[0] = round(($newHeight * $ratio));
    }
    else if($newHeight == -1)
    {
        $ret[0] = $newWidth;
        $ret[1] = round(($newWidth / $ratio));
    }
    else
        die("Scale Error");
    return $ret;
} 



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


$qrydata1 = mysql_query("SELECT ct.APPLICANTNO,CONCAT(c.FNAME,', ',c.GNAME,' ',c.MNAME) AS NAME,CIVILSTATUS,
						CONCAT(c.ADDRESS,', ',c.MUNICIPALITY,', ',c.CITY,' ',c.ZIPCODE) AS ADDRESS1,
						CONCAT(c.ADDRESS,', ',ab.BARANGAY,', ',at.TOWN,', ',ap.PROVINCE,' ',c.ZIPCODE) AS ADDRESS2,
						CONCAT(c.PROVADDRESS,', ',ab2.BARANGAY,', ',at2.TOWN,', ',ap2.PROVINCE,' ',c.PROVZIPCODE) AS PROVADDR,
						tc.TRAINING,th.DATEFROM,th.DATETO,
						IF(td.TRAINCENTERCODE IS NOT NULL,trc2.TRAINCENTER,trc.TRAINCENTER) AS TRAINCENTER,
						IF(td.TRAINCENTERCODE IS NOT NULL,trc2.TRAINCENTERCODE,trc.TRAINCENTERCODE) AS TRAINCENTERCODE,
						CONCAT(e1.CERTRANK,' ',e1.GNAME,' ',LEFT(e1.MNAME,1),'. ',e1.FNAME) AS SIGNNAME1,
						CONCAT(e2.CERTRANK,' ',e2.GNAME,' ',LEFT(e2.MNAME,1),'. ',e2.FNAME) AS SIGNNAME2,
						d1.DESIGNATION AS DESIG1,d2.DESIGNATION AS DESIG2,
						eh.DOCSUBMITTED,eh.PARTICIPANTNO,eh.DATEISSUED
						FROM crewtraining ct
						LEFT JOIN crew c ON c.APPLICANTNO=ct.APPLICANTNO
						LEFT JOIN addrprovince ap ON ap.PROVCODE=c.PROVINCECODE
						LEFT JOIN addrtown at ON at.TOWNCODE=c.TOWNCODE AND at.PROVCODE=c.PROVINCECODE
						LEFT JOIN addrbarangay ab ON ab.BRGYCODE=c.BARANGAYCODE AND ab.TOWNCODE=c.TOWNCODE AND ab.PROVCODE=c.PROVINCECODE
						LEFT JOIN addrprovince ap2 ON ap2.PROVCODE=c.PROVPROVCODE
						LEFT JOIN addrtown at2 ON at2.TOWNCODE=c.PROVTOWNCODE AND at.PROVCODE=c.PROVPROVCODE
						LEFT JOIN addrbarangay ab2 ON ab2.BRGYCODE=c.PROVBRGYCODE AND ab2.TOWNCODE=c.PROVTOWNCODE AND ab2.PROVCODE=c.PROVPROVCODE
						LEFT JOIN trainingschedhdr th ON th.SCHEDID=ct.SCHEDID
						LEFT JOIN trainingscheddtl td ON td.SCHEDID=th.SCHEDID
						LEFT JOIN trainingcourses tc ON tc.TRAINCODE=th.TRAINCODE
						LEFT JOIN trainingcenter trc ON trc.TRAINCENTERCODE=tc.TRAINCENTERCODE
						LEFT JOIN trainingcenter trc2 ON trc2.TRAINCENTERCODE=td.TRAINCENTERCODE
						LEFT JOIN endorsementhdr eh ON eh.ENDORSEID=ct.ENDORSEID
						LEFT JOIN employee e1 ON e1.EMPLOYEEID=eh.SIGN1
						LEFT JOIN employee e2 ON e2.EMPLOYEEID=eh.SIGN2
						LEFT JOIN designation d1 ON d1.DESIGNATIONCODE=e1.DESIGNATIONCODE
						LEFT JOIN designation d2 ON d2.DESIGNATIONCODE=e2.DESIGNATIONCODE
						WHERE ct.IDNO=$idno AND ct.ENDORSEID=$endorsementidno
						LIMIT 1
						") or die(mysql_error());

$rowdata1 = mysql_fetch_array($qrydata1);
$applicantno = $rowdata1["APPLICANTNO"];
$crewname = $rowdata1["NAME"];

switch ($rowpersonal["CIVILSTATUS"])
{
	case "S" : $civilstatus = "SINGLE"; break;
	case "M" : $civilstatus = "MARRIED"; break;
	case "W" : $civilstatus = "WIDOWER"; break;
	case "P" : $civilstatus = "SEPARATED"; break;
}

$address1 = $rowdata1["ADDRESS1"];
$address2 = $rowdata1["ADDRESS2"];

if (!empty($address1))
	$showaddress = $address1;
else 
	$showaddress = $address2;

$provaddr = $rowdata1["PROVADDR"];
$training = $rowdata1["TRAINING"];
$traincenter = $rowdata1["TRAINCENTER"];
$participantno = $rowdata1["PARTICIPANTNO"];

if (!empty($rowdata1["DATEFROM"]))
	$datefrom = date("dMY",strtotime($rowdata1["DATEFROM"]));
else 
	$datefrom = "";

if (!empty($rowdata1["DATETO"]))
	$dateto = date("dMY",strtotime($rowdata1["DATETO"]));
else 
	$dateto = "";

if (strtotime($rowdata1["DATEFROM"]) == strtotime($rowdata1["DATETO"]))
	$dateshow = $datefrom;
else 
	$dateshow = $datefrom . " TO " . $dateto;
	
$signname1 = $rowdata1["SIGNNAME1"];
$signname2 = $rowdata1["SIGNNAME2"];
$desig1 = $rowdata1["DESIG1"];
$desig2 = $rowdata1["DESIG2"];
$docsubmitted = $rowdata1["DOCSUBMITTED"];
	
if (!empty($rowdata1["DATEISSUED"]))
	$dateissued = date("dMY",strtotime($rowdata1["DATEISSUED"]));
else 
	$dateissued = "";
	
$vessel = "";
$rank = "";
$etd = "";
$lastvessel = "";

extractlist($applicantno,$vessel,$rank,$etd,$lastvessel);

if (strtotime($etd) <= strtotime($currentdate))
{
	$vessel = "";
	$rank = "";
	$etd = "";
	$lastvessel = "";
}
	
echo "
<html>\n
<head>\n
<title>
Endorsement Letter - Outside Training
</title>

<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>

</head>
<body style=\"overflow:auto;\">\n

<div style=\"width:700px;margin-left:20px;margin-right:20px;\">
";
	$stylehdr = "style=\"font-size:0.7em;font-weight:Bold;\"";
	$styledtl = "style=\"font-size:0.7em;\"";
echo "
	<table width=\"100%\">
		<tr>
			<td width=\"25%\" align=\"left\"><span style=\"font-size:0.7em;font-weight:Bold;\">Form 244-A/August 15, 1997</span></td>
			<td width=\"75%\" align=\"right\"><span style=\"font-size:0.7em;font-weight:Bold;\">&nbsp;</span></td>
		</tr>
		<tr>
			<td>
				<img src=\"images/veritas_small.jpg\" border=0 />
			</td>
			<td>
				<center>
					<span style=\"font-size:1.2em;font-weight:Bold;\">VERITAS MARITIME CORPORATION</span><br />
					<span style=\"font-size:0.75em;\">15th Floor, MARC 2000 Tower, 1973 Taft Avenue, Malate, Manila, Philippines</span><br />
					<span style=\"font-size:0.75em;\">Telefax (632) 526-1029&nbsp;&nbsp;(632) 338-0318&nbsp;&nbsp;Tlx 051-94078312-VMCCG</span><br />
					<span style=\"font-size:0.75em;\">Tels. (632) 5268041&nbsp;&nbsp;(632) 5362775&nbsp;&nbsp;(632) 5362757</span><br />
					<span style=\"font-size:0.75em;\">E-mail Address: <i><u>vmcgroup@veritas.com.ph</u>&nbsp;&nbsp;<u>veritas_mc@pacific.net.ph</u></i></span><br />
				</center>
			</td>
		</tr>
	</table>
	
	<center><span style=\"font-size:0.8em;font-weight:Bold;\">ENDORSEMENT FOR TRAINING/SEMINAR ATTENDANCE</span></center>
	<br />
		
	<div style=\"width:100%;\">
		<table style=\"width:59%;float:left;\">
			<tr>
				<td $stylehdr>Participant No.</td>
				<td $stylehdr>:</td>
				<td $styledtl>$participantno</td>
			</tr>
			<tr>
				<td $stylehdr>NAME</td>
				<td $stylehdr>:</td>
				<td $styledtl>$crewname</td>
			</tr>
			<tr>
				<td $stylehdr>Manila Address</td>
				<td $stylehdr>:</td>
				<td $styledtl>$showaddress</td>
			</tr>
			<tr>
				<td $stylehdr>Provincial Address</td>
				<td $stylehdr>:</td>
				<td $styledtl>$provaddr</td>
			</tr>
			<tr>
				<td $stylehdr>Civil Status</td>
				<td $stylehdr>:</td>
				<td $styledtl>$civilstatus</td>
			</tr>
		</table>
		
		<table style=\"width:40%;float:right;\">
			<tr>
				<td $stylehdr>Name of Course</td>
				<td $stylehdr>:</td>
				<td $styledtl>$training</td>
			</tr>
			<tr>
				<td $stylehdr>School Endorsed</td>
				<td $stylehdr>:</td>
				<td $styledtl>$traincenter</td>
			</tr>
			<tr>
				<td $stylehdr>Attendance</td>
				<td $stylehdr>:</td>
				<td $styledtl>$dateshow</td>
			</tr>
			<tr>
				<td $stylehdr>Embark Date</td>
				<td $stylehdr>:</td>
				<td $styledtl>$etd</td>
			</tr>
			<tr>
				<td $stylehdr>Vessel</td>
				<td $stylehdr>:</td>
				<td $styledtl>$vessel</td>
			</tr>
			<tr>
				<td $stylehdr>Rank</td>
				<td $stylehdr>:</td>
				<td $styledtl>$rank</td>
			</tr>
		</table>
	</div>

	<center>
	<div style=\"width:80%;text-align:center;\">
		<span style=\"font-size:0.8em;font-weight:Bold;text-align:center;\">CERTIFICATION</span> 
		<br /><br />
		
		<span style=\"font-size:0.7em;\">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			This is to certify that above-named seaman is a lined-up crew of VERITAS MARITIME CORPORATION and
			has been endorsed by the Company to attend the seminar(s)/training(s) as specified above.<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			This is to certify that his training shall be for the account of VERITAS.  Please send your bill to us, together with
			the trainees certificate / evaluation at:
		</span>
			
		<br /><br />
			
		<span style=\"font-size:0.7em;font-weight:Bold;\">
			VERITAS MARITIME CORPORATION <br />
			15th Floor MARC 2000 Tower, 1973 Taft Avenue<br />
			Malate, Manila<br />
		</span>
	</div>
	</center>
	<br />
	
	<table style=\"width:100%;font-size:0.75em;\">
		<tr>
			<td width=\"33%\" align=\"center\"><b>$signname1</b></td>
			<td width=\"33%\" align=\"center\">&nbsp;</td>
			<td width=\"33%\" align=\"center\"><b>$signname2</b></td>
		</tr>
		<tr>
			<td width=\"33%\" align=\"center\">$desig1</td>
			<td width=\"33%\" align=\"center\">&nbsp;</td>
			<td width=\"33%\" align=\"center\">$desig2</td>
		</tr>
	</table>
	
	<br />
	<hr />
	";	
	
	

echo "
	<table width=\"100%\">
		<tr>
			<td width=\"25%\" align=\"left\"><span style=\"font-size:0.7em;font-weight:Bold;\">Form 244-A/August 15, 1997</span></td>
			<td width=\"75%\" align=\"right\"><span style=\"font-size:0.7em;font-weight:Bold;\">For VMC Accounting / Finance Departments</span></td>
		</tr>
		<tr>
			<td>
				<img src=\"images/veritas_small.jpg\" border=0 />
			</td>
			<td>
				<center>
					<span style=\"font-size:1.2em;font-weight:Bold;\">VERITAS MARITIME CORPORATION</span><br />
					<span style=\"font-size:0.75em;\">15th Floor, MARC 2000 Tower, 1973 Taft Avenue, Malate, Manila, Philippines</span><br />
					<span style=\"font-size:0.75em;\">Telefax (632) 526-1029&nbsp;&nbsp;(632) 338-0318&nbsp;&nbsp;Tlx 051-94078312-VMCCG</span><br />
					<span style=\"font-size:0.75em;\">Tels. (632) 5268041&nbsp;&nbsp;(632) 5362775&nbsp;&nbsp;(632) 5362757</span><br />
					<span style=\"font-size:0.75em;\">E-mail Address: <i><u>vmcgroup@veritas.com.ph</u>&nbsp;&nbsp;<u>veritas_mc@pacific.net.ph</u></i></span><br />
				</center>
			</td>
		</tr>
	</table>
	<br />
	
		
	<div style=\"width:100%;\">
		<table style=\"width:59%;float:left;\">
			<tr>
				<td $stylehdr>Participant No.</td>
				<td $stylehdr>:</td>
				<td $styledtl>$participantno</td>
			</tr>
			<tr>
				<td $stylehdr>NAME</td>
				<td $stylehdr>:</td>
				<td $styledtl>$crewname</td>
			</tr>
			<tr>
				<td $stylehdr>Manila Address</td>
				<td $stylehdr>:</td>
				<td $styledtl>$showaddress</td>
			</tr>
			<tr>
				<td $stylehdr>Provincial Address</td>
				<td $stylehdr>:</td>
				<td $styledtl>$provaddr</td>
			</tr>
			<tr>
				<td $stylehdr>Civil Status</td>
				<td $stylehdr>:</td>
				<td $styledtl>$civilstatus</td>
			</tr>
		</table>
		
		<table style=\"width:40%;float:right;\">
			<tr>
				<td $stylehdr>Name of Course</td>
				<td $stylehdr>:</td>
				<td $styledtl>$training</td>
			</tr>
			<tr>
				<td $stylehdr>School Endorsed</td>
				<td $stylehdr>:</td>
				<td $styledtl>$traincenter</td>
			</tr>
			<tr>
				<td $stylehdr>Attendance</td>
				<td $stylehdr>:</td>
				<td $styledtl>$dateshow</td>
			</tr>
			<tr>
				<td $stylehdr>Embark Date</td>
				<td $stylehdr>:</td>
				<td $styledtl>$etd</td>
			</tr>
			<tr>
				<td $stylehdr>Vessel</td>
				<td $stylehdr>:</td>
				<td $styledtl>$vessel</td>
			</tr>
			<tr>
				<td $stylehdr>Rank</td>
				<td $stylehdr>:</td>
				<td $styledtl>$rank</td>
			</tr>
		</table>
	</div>

	<center>
	<div style=\"width:80%;text-align:center;\">
		<span style=\"font-size:0.8em;font-weight:Bold;text-align:center;\">ACKNOWLEDGEMENT</span> 
		<br /><br />
		
		<span style=\"font-size:0.7em;\">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			This is to acknowledge that I, the undersigned seaman, is a lined-up crew of VERITAS MARITIME and was
			endorsed by VERITAS to attend the seminar(s) / training(s) as specified above.  Cost of the training will be advanced by VERITAS to the school.<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			This is to certify further of my permission for the cost of the training to be deducted from my alotment as per Company Schedule.
		</span>
		<br /><br />
		
		<center>
		<table style=\"font-size:0.7em;\">
			<tr>
				<td align=\"center\"><b>$crewname</b></td>
			</tr>
			<tr>
				<td align=\"center\" style=\"border-top:1px solid black;\">Seafarer's Full Name & Signature</td>
			</tr>
		</table>
		</center>
	</div>
	</center>
	
	<br />
	
	<table style=\"width:100%;font-size:0.75em;\">
		<tr>
			<td width=\"33%\" align=\"center\"><b>$signname1</b></td>
			<td width=\"33%\" align=\"center\">&nbsp;</td>
			<td width=\"33%\" align=\"center\"><b>$signname2</b></td>
		</tr>
		<tr>
			<td width=\"33%\" align=\"center\">$desig1</td>
			<td width=\"33%\" align=\"center\">&nbsp;</td>
			<td width=\"33%\" align=\"center\">$desig2</td>
		</tr>
	</table>
	<br />
	
	<span style=\"font-size:0.8em;\">
		Documents submitted to VMC as of this endorsement: <u>$docsubmitted</u> <br />
		Date of issuance: <u>$dateissued</u>
	
	</span>
	
	";	

	
	echo "
</div>";


include('include/printclose.inc');

echo "

</body>

</html>

";

?>