<?php


include("veritas/connectdb.php");
session_start();

$currentdate = date('Y-m-d H:i:s');
$datenow = date('d F Y');
$basedirid = "idpics/";
$basedirdocs = "docimages/";

if(isset($_SESSION["employeeid"]))
	$employeeid=$_SESSION["employeeid"];

//if (isset($_POST["actiontxt"]))
//	$actiontxt = $_POST["actiontxt"];
	
if (isset($_GET["applicantno"]))
	$applicantno = $_GET["applicantno"];
else 
	$applicantno = $_POST["applicantno"];

if (isset($_GET["classcardno"]))
	$classcardno = $_GET["classcardno"];

if (isset($_GET["remarks"]))
	$remarks = $_GET["remarks"];


$assignedrankcode = "---";
$assignedrankalias = "---";
$assignedvesselcode = "---";
$assignedvessel = "---";
$assignedetd = "---";

$lastrankcode = "---";
$lastrankalias = "---";
$lastvesselcode = "---";
$lastvessel = "---";
$lastdisembdate = "---";


if (!empty($applicantno))
{
	
	$qryallexperience = mysql_query("SELECT IF (cpr.CCIDPROMOTE IS NULL,0,1) AS PROMOTED,x.* FROM
									(
										SELECT '1' AS POS,cc.CCID,CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,CREWCODE,
										LEFT(v.ALIAS2,10) AS VESSEL,v.VESSELCODE,r.ALIAS1 AS RANKALIAS,r.RANKCODE,
										cc.DATEEMB,IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,cc.DISEMBREASONCODE,dr.REASON,
										cc.ARRMNLDATE,cc.DEPMNLDATE
										FROM crewchange cc
										LEFT JOIN crew c ON c.APPLICANTNO=cc.APPLICANTNO
										LEFT JOIN vessel v ON v.VESSELCODE=cc.VESSELCODE
										LEFT JOIN rank r ON r.RANKCODE=cc.RANKCODE
										LEFT JOIN disembarkreason dr ON dr.DISEMBREASONCODE=cc.DISEMBREASONCODE
										WHERE cc.APPLICANTNO=$applicantno
										
										UNION
										
										SELECT '2' AS POS,ce.IDNO,CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,CREWCODE,
										LEFT(ce.VESSEL,10) AS VESSEL,NULL,r.ALIAS1 AS RANKALIAS,r.RANKCODE,
										DATEEMB,DATEDISEMB,ce.DISEMBREASONCODE,dr.REASON,NULL,NULL
										FROM crewexperience ce
										LEFT JOIN crew c ON c.APPLICANTNO=ce.APPLICANTNO
										LEFT JOIN rank r ON r.RANKCODE=ce.RANKCODE
										LEFT JOIN disembarkreason dr ON dr.DISEMBREASONCODE=ce.DISEMBREASONCODE
										WHERE ce.APPLICANTNO=$applicantno
									) x
									LEFT JOIN crewpromotionrelation cpr ON cpr.CCIDPROMOTE=x.CCID
									ORDER BY x.DATEDISEMB DESC
									LIMIT 4
								") or die(mysql_error());

	$cnt = 0;
//	$content = "";
	$appstatus = "";
	
	while ($rowallexperience = mysql_fetch_array($qryallexperience))
	{
		if ($rowallexperience["POS"] == "1")
			$zexptype = "Veritas";
		else 
			$zexptype = "Outside VMC";
			
		$crewname = $rowallexperience["NAME"];
		$crewcode = $rowallexperience["CREWCODE"];
			
		$zccid = $rowallexperience["CCID"];
		$zcrewcode = $rowallexperience["CREWCODE"];
		$zvessel = $rowallexperience["VESSEL"];
		$zvesselcode = $rowallexperience["VESSELCODE"];
		$zrankalias = $rowallexperience["RANKALIAS"];
		$zrankcode = $rowallexperience["RANKCODE"];
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
						
					$lastrankcode = $zrankcode;
					$lastrankalias = $zrankalias;
					$lastvesselcode = $zvesselcode;
					$lastvessel = $zvessel;
					$lastdisembdate = $zdatedisemb;
				}
				else 
				{
					if (strtotime($zdateemb) <= strtotime($datenow))
					{
						if ($zpromoted != 1)
						{
							$appstatus = "ONBOARD";
							
							$lastrankcode = $zrankcode;
							$lastrankalias = $zrankalias;
							$lastvesselcode = $zvesselcode;
							$lastvessel = $zvessel;
							$lastdisembdate = $zdatedisemb;
						}
						else 
						{
							$appstatus = "PROMOTED ONBOARD";
							
							$assignedrankcode = $zrankcode;
							$assignedrankalias = $zrankalias;
							$assignedvesselcode = $zvesselcode;
							$assignedvessel = $zvessel;
							$assignedetd = $zdateemb;
						}
	

						
						$disablechecklist = 1;
					}
					else 
					{
						if ($zpromoted != 1)
						{
							$appstatus = "EMBARKING";
						}
						else 
						{
							$appstatus = "PROMOTED ONBOARD";
						}
						
						$assignedrankcode = $zrankcode;
						$assignedrankalias = $zrankalias;
						$assignedvesselcode = $zvesselcode;
						$assignedvessel = $zvessel;
						$assignedetd = $zdateemb;
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
					
					$assignedrankcode = $rowapplicantstatus["VMCRANKCODE"];
					$assignedrankalias = $rowapplicantstatus["RANKALIAS"];
					$assignedvesselcode = $rowapplicantstatus["VMCVESSELCODE"];
					$assignedvessel = $rowapplicantstatus["VESSEL"];
					
					if (!empty($rowapplicantstatus["VMCETD"]))
						$assignedetd = date("dMY",strtotime($rowapplicantstatus["VMCETD"]));
					else 
						$assignedetd = "---";
				}
				else 
				{
					$appstatus = "NO VMC EXPERIENCE / NO LINE-UP YET";
				}
				
			}
			
			
			//SAVE TO $currentrankcode for checklist use...
			
			if (!empty($assignedrankcode) && $assignedrankcode != "---")
			{
				$currentrankcode = $assignedrankcode;
				$currentvesselcode = $assignedvesselcode;
				$currentetd = $assignedetd;
			}
			elseif (!empty($lastrankcode) && $lastrankcode != "---")
			{
				$currentrankcode = $lastrankcode;
				$currentvesselcode = "";
				$currentetd = "";
			}
			else 
			{
				$currentrankcode = "";
				$currentvesselcode = "";
				$currentetd = "";
			}
			
		}
		elseif ($cnt == 1)
		{
			if ($appstatus == "EMBARKING" || $appstatus == "PROMOTED ONBOARD")
			{
				$lastrankcode = $zrankcode;
				$lastrankalias = $zrankalias;
				$lastvesselcode = $zvesselcode;
				$lastvessel = $zvessel;
				$lastdisembdate = $zdatedisemb;
			}
		}
		
		$cnt++;
	}

}
	

if (!empty($applicantno) && !empty($classcardno))
{
	//CLASSCARD DETAILS - TRAININGS INVOLVED
	$qryclasscard = mysql_query("SELECT th.SCHEDID,td.IDNO,th.DATEFROM,th.DATETO,th.STATUS,
								trc.TRAINCODE,trc.TRAINING,
								tv.TRAINVENUECODE,tv.TRAINVENUE,
								tc.TRAINCENTERCODE,tc.TRAINCENTER,
								td.SCHEDDATE,td.INSTRUCTOR,td.TIMESTART,td.TIMEEND,
								ct.COURSETYPECODE,ct.COURSETYPE,te.REMARKS
								FROM crewtraining c
								LEFT JOIN trainingschedhdr th ON th.SCHEDID=c.SCHEDID
								LEFT JOIN trainingscheddtl td ON td.SCHEDID=th.SCHEDID
								LEFT JOIN trainingvenue tv ON tv.TRAINVENUECODE=td.TRAINVENUECODE
								LEFT JOIN trainingcenter tc ON tc.TRAINCENTERCODE=td.TRAINCENTERCODE
								LEFT JOIN trainingcourses trc ON trc.TRAINCODE=th.TRAINCODE
								LEFT JOIN coursetype ct ON ct.COURSETYPECODE=trc.COURSETYPECODE
								LEFT JOIN trainingendorsement te ON te.APPLICANTNO=c.APPLICANTNO AND te.SCHEDID=c.SCHEDID
								WHERE c.CLASSCARDNO=$classcardno AND c.APPLICANTNO=$applicantno
								ORDER BY ct.COURSETYPECODE,th.SCHEDID,td.SCHEDDATE,trc.TRAINCODE
							") or die(mysql_error());
	if (mysql_num_rows($qryclasscard) > 0)
	{
		$statclassempty = 0;
		
		//CLASSCARD STATUS
		$qrygetclasscarddtls = mysql_query("SELECT ch.PRINTDATE,ch.PRINTBY,ch.ACCEPTDATE,ch.ACCEPTBY,ch.CANCELDATE,ch.CANCELBY,ch.REMARKS
									FROM classcardhdr ch
									WHERE ch.CLASSCARDNO=$classcardno
								") or die(mysql_error());
		
		$rowgetclasscarddtls = mysql_fetch_array($qrygetclasscarddtls);
		
		if (!empty($rowgetclasscarddtls["PRINTDATE"]))
			$cardprintdate = date("dMY",strtotime($rowgetclasscarddtls["PRINTDATE"]));
		else 
			$cardprintdate = "";
			
		$cardprintby = $rowgetclasscarddtls["PRINTBY"];
		
		if (!empty($rowgetclasscarddtls["ACCEPTDATE"]))
			$cardacceptdate = date("dMY",strtotime($rowgetclasscarddtls["ACCEPTDATE"]));
		else 
			$cardacceptdate = "";
		
		$cardacceptby = $rowgetclasscarddtls["ACCEPTBY"];
		
		if (!empty($rowgetclasscarddtls["CANCELDATE"]))
			$cardcanceldate = date("dMY",strtotime($rowgetclasscarddtls["CANCELDATE"]));
		else 
			$cardcanceldate = "";
		
		$cardcancelby = $rowgetclasscarddtls["CANCELBY"];
		$cardremarks = $rowgetclasscarddtls["REMARKS"];
		
	
//		if (!empty($cardprintdate))
//			$statprinted = 1;
//		else 
//			$statprinted = 0;
//			
//		if (!empty($cardacceptdate))
//			$stataccepted = 1;
//		else 
//			$stataccepted = 0;
//		
//		if (!empty($cardcanceldate))
//			$statcancelled = 1;
//		else 
//			$statcancelled = 0;

	}
}
	
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




echo "
<html>\n
<head>\n
<title>
Class Card Generation -- Printing
</title>

<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>
<script language=\"javascript\" src=\"popcalendar.js\"></script>	


</head>
<body style=\"overflow:auto;\">\n

<div style=\"margin:10 10 0 10;width:770px;\">
";
	$stylehdr = "style=\"font-size:0.7em;font-weight:Bold;color:Blue;\"";
	$styledtl = "style=\"font-size:0.7em;font-weight:Bold;color:Black;\"";
echo "
	<div style=\"width:100%;\" >
		<div style=\"width:20%;float:left;\" >
		";
			$dirfilename = "images/veritas_logo2.JPG";
			if (checkpath($dirfilename))
			{
				$scale = imageScale($dirfilename,-1,100);
				$width = $scale[0];
				$height = $scale[1];
				
echo "			<center><img src=\"$dirfilename\" width=\"$width\" height=\"$height\" /></center> ";
			}
			
echo "

		</div>
		
		<div style=\"width:75%;float:right;margin-top:25px;\">
			<span style=\"width:100%;font-size:1.8em;font-weight:Bold;color:Navy;text-align:center;\" >TRAINING CLASS CARD</span>
			<hr>
		</div>
	
	</div>
	
	<br />

	<div style=\"width:100%;height:40px;border:1px solid black;\" >
		<table style=\"width:100%;\">
			<tr>
				<td valign=\"top\" width=\"40%\" align=\"left\" $stylehdr>
					<span style=\"font-size:1.5em;font-weight:Bold;color:Black;\">$crewname</span>
				</td>
				<td valign=\"top\" width=\"20%\" align=\"center\" $stylehdr>CLASSCARD NO:&nbsp;<br />
					<span style=\"font-size:1.5em;font-weight:Bold;color:Black;\">$classcardno</span>
				</td>
				<td valign=\"top\" width=\"20%\" align=\"center\" $stylehdr>CREW CODE:&nbsp;<br />
					<span style=\"font-size:1.5em;font-weight:Bold;color:Black;\">$crewcode</span>
				</td>
				<td valign=\"top\" width=\"20%\" align=\"center\" $stylehdr>APPLICANT NO:&nbsp;$applicantno<br />
					<span style=\"font-family:Code 39;font-size:2.5em;font-weight:Bold;color:Black;\">$applicantno</span>
				</td>
			</tr>
		</table>
	</div>
	
	<div style=\"width:100%;height:100px;border:1px solid black;\" >
	
		<div style=\"width:85%;height:100px;float:left;background-color:White;\" >
			<table style=\"width:50%;float:left;\">
				<tr>
					<td $stylehdr>LAST VESSEL</td>
					<td $stylehdr>:</td>
					<td $styledtl>$lastvessel</td>
				</tr>
				<tr>
					<td $stylehdr>LAST RANK</td>
					<td $stylehdr>:</td>
					<td $styledtl>$lastrankalias</td>
				</tr>
				<tr>
					<td $stylehdr>DISEMBARK</td>
					<td $stylehdr>:</td>
					<td $styledtl>$lastdisembdate</td>
				</tr>
			</table>
			
			<table style=\"width:49%;float:right;\">
				<tr>
					<td $stylehdr>ASSIGNED VESSEL</td>
					<td $stylehdr>:</td>
					<td $styledtl>$assignedvessel</td>
				</tr>
				<tr>
					<td $stylehdr>ASSIGNED RANK</td>
					<td $stylehdr>:</td>
					<td $styledtl>$assignedrankalias</td>
				</tr>
				<tr>
					<td $stylehdr>ETD</td>
					<td $stylehdr>:</td>
					<td $styledtl>$assignedetd</td>
				</tr>
				<tr>
					<td $stylehdr>STATUS</td>
					<td $stylehdr>:</td>
					<td style=\"font-size:1em;font-weight:Bold;color:Green;\" align=\"left\"><u>$appstatus</u></td>
				</tr>
			</table>
			
		</div>
		
		<div style=\"width:15%;height:100px;float:left;background-color:White;\" >
		
		";
			$dirfilename = $basedirid . $applicantno . ".JPG";
			if (checkpath($dirfilename))
			{
				$scale = imageScale($dirfilename,-1,90);
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

	<div style=\"width:100%;height:590px;border:1px solid black;\" >
	";
		$stylehdr1 = "style=\"color:Black;font-size:0.8em;text-decoration:underline;\"";
	echo "
		<table width=\"100%\" style=\"\">
			<tr>
				<th $stylehdr1 width=\"15%\">DATE</th>
				<th $stylehdr1 width=\"15%\">START</th>
				<th $stylehdr1 width=\"15%\">END</th>
				<th $stylehdr1 width=\"35%\">VENUE/CENTER</th>
				<th $stylehdr1 width=\"30%\">REMARKS</th>
			</tr>
		";

		$styledtl2 = "style=\"font-size:0.7em;color:Black;border-bottom:1px dashed gray;\"";

		$coursetypecodetmp = "";
		$schedidtmp = "";
		$traincnt = 1;
		
		while ($rowclasscard = mysql_fetch_array($qryclasscard))
		{
			$classschedid = $rowclasscard["SCHEDID"];
			
			if (!empty($rowclasscard["DATEFROM"]))
				$classdatefrom = date("dMY",strtotime($rowclasscard["DATEFROM"]));
			else 
				$classdatefrom = "";
				
			if (!empty($rowclasscard["DATETO"]))
				$classdateto = date("dMY",strtotime($rowclasscard["DATETO"]));
			else 
				$classdateto = "";
				
			$classremarks = $rowclasscard["REMARKS"];
			$classstatus = $rowclasscard["STATUS"];
			$classtraincode = $rowclasscard["TRAINCODE"];
			$classtraining = $rowclasscard["TRAINING"];
			$classtrainvenuecode = $rowclasscard["TRAINVENUECODE"];
			$classtrainvenue = $rowclasscard["TRAINVENUE"];
			$classtraincentercode = $rowclasscard["TRAINCENTERCODE"];
			$classtraincenter = $rowclasscard["TRAINCENTER"];
			
			if (!empty($rowclasscard["SCHEDDATE"]))
				$classscheddate = date("dMY",strtotime($rowclasscard["SCHEDDATE"]));
			else 
				$classscheddate = "";
			
			$classinstructor = $rowclasscard["INSTRUCTOR"];
			
			if (!empty($rowclasscard["TIMESTART"]))
				$classtimestart =  date('H:i',strtotime($rowclasscard["TIMESTART"]));
			else 
				$classtimestart =  "";
				
			if (!empty($rowclasscard["TIMEEND"]))
				$classtimeend =  date('H:i',strtotime($rowclasscard["TIMEEND"]));
			else 
				$classtimeend =  "";
				
			$classcoursetypecode = $rowclasscard["COURSETYPECODE"];
			$classcoursetype = $rowclasscard["COURSETYPE"];
			
			if (strtotime($classdatefrom) != strtotime($classdateto))
				$classdatefromto = $classdatefrom . " to " . $classdateto;
			else 
				$classdatefromto = $classdatefrom;
			
			
			if ($coursetypecodetmp != $classcoursetypecode)  //GROUPING FOR INHOUSE | PRINCIPAL | OUTSIDE
			{
				echo "
				<tr><td colspan=\"5\">&nbsp;</td></tr>
				<tr>
					<td colspan=\"5\" style=\"font-size:0.8em;font-weight:Bold;border:1px solid Gray;background-color:Yellow;color:Red;text-align:center;\">$classcoursetype</td>
				</tr>
				";
			}
			
			if ($schedidtmp != $classschedid)
			{
				echo "
				<tr>
					<td colspan=\"6\" style=\"font-size:0.7em;font-weight:Bold;background-color:Silver;color:Black;\">
						$traincnt.&nbsp; <span style=\"color:Navy;\">$classtraining</span> &nbsp;&nbsp;
						Sched ID: $classschedid &nbsp;(&nbsp;$classdatefromto&nbsp;)
					</td>
				</tr>
				";
				
				$traincnt++;
			}
			
			echo "
				<tr>
					<td $styledtl2 align=\"center\">$classscheddate</td>
					<td $styledtl2 align=\"center\">$classtimestart</td>
					<td $styledtl2 align=\"center\">$classtimeend</td>
					<td $styledtl2 align=\"center\">&nbsp;$classtrainvenue &nbsp;$classtraincenter</td>
					<td $styledtl2 align=\"center\">&nbsp;$classremarks</td>
				</tr>
			
			";
			
			
			$schedidtmp = $classschedid;
			$coursetypecodetmp = $classcoursetypecode;
			
		}
	
	echo "
			<tr><td colspan=\"5\">&nbsp;</td></tr>
			<tr>
				<td colspan=\"5\" style=\"font-size:1em;font-weight:Bold;color:Red;text-align:center;\"><i>************** NOTHING FOLLOWS **************</i></td>
			</tr>
		</table>
	</div>

	<div style=\"width:100%;height:90px;border:1px solid Gray;\" >
	
		<div style=\"width:45%;float:left;margin:5 5 5 5;\">
		
			<table width=\"100%\">
				<tr>
					<td $stylehdr>REMARKS</td>		
				</tr>
				<tr>
					<td $styledtl>$remarks <br />$cardremarks</td>		
				</tr>
			</table>
		</div>
		
		<div style=\"width:50%;float:right;margin:5 5 5 5;\">
		
			<table width=\"80%\">
				<tr>
					<td $stylehdr width=\"20%\" align=\"right\">PRINT BY:</td>
					<td $styledtl style=\"width:25%;border-bottom:1px solid black;text-align:center;\">$cardprintby</td>
					<td $stylehdr width=\"25%\" align=\"right\">PRINT DATE:</td>
					<td $styledtl style=\"width:30%;border-bottom:1px solid black;text-align:center;\">$cardprintdate</td>
				</tr>
			</table>
			
			<br /><br />
			
			<table width=\"100%\">
				<tr>
					<td style=\"width:45%;font-size:0.7em;font-weight:Bold;border-top:thin solid black;text-align:center;\">NOTED BY / DATE</td>
					<td style=\"width:10%;\">&nbsp;</td>
					<td style=\"width:45%;font-size:0.7em;font-weight:Bold;border-top:thin solid black;text-align:center;\">RECEIVED BY / DATE</td>
				</tr>
			
			</table>
		</div>
	
	</div>
	
</div>";


include('include/printclose.inc');

echo "

</body>

</html>

";

?>