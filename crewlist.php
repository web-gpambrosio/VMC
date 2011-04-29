<?php
//include('veritas/connectdb.php');
include('connectdb2.php');

session_start();

if (isset($_GET["vessel_code"]))
	$vessel_code = $_GET["vessel_code"];	
else 
	$vessel_code = "P44";
	
if (isset($_GET["crewid"]))
	$xcrewid = $_GET["crewid"];	
	
//$qrycrewlist = mysql_query("SELECT CRW1,VSL2 FROM mcms_05 a
//							LEFT JOIN mcms_04 b ON b.VSCODE=a.CRW23A
//							WHERE CRW5 IS NOT NULL AND a.CRW23A = '$vessel_code'
//							ORDER BY CRW24, CRW2, CRW3, CRW4") or die(mysql_error());

$qrycrewlist = mysql_query("SELECT CRW1,CL1
							FROM mcms_18 a
							WHERE a.VSCODE = '$vessel_code' AND CRW1 <> ''
							ORDER BY CL1") or die(mysql_error());

$qryvesselname = mysql_query("SELECT VSL2,VSL17,VSL18,VSL19 FROM mcms_04 WHERE VSCODE='$vessel_code'") or die(mysql_error());
$rowvesselname = mysql_fetch_array($qryvesselname);

$vesselname = $rowvesselname["VSL2"];
$numcrew = $rowvesselname["VSL17"];
$numcrewforeign = $rowvesselname["VSL18"];
$numcrewfil = $rowvesselname["VSL19"];

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


$availpic = 0;
$imgpath = "vesselpics/$vessel_code.jpg";

if (is_file($imgpath))
{
	$scaling = 120;
	$scale = imageScale($imgpath,$scaling,-1);
	$xwidth = $scale[0];
	$xheight = $scale[1];
	$availpic = 1;
}


function checkpath($path)
{
	if (is_file($path))
		return true;
	else 
		return false;
}

include("header.php");

echo "

<div style=\"margin-top:10px;margin-left:20px;margin-right:20px;padding:5px;\">
	<span class=\"title\">Crew List</span>
	<hr>
	<div style=\"width:100%;padding:2px;\">
		<div style=\"width:40%;float:left;\">
			<table width=\"100%\">
				<tr>
					<td class=\"item\" width=\"40%\">Vessel Name</td>
					<td class=\"item\" width=\"5%\" valign=\"top\">:</td>
					<td class=\"field\" width=\"65%\">$vesselname</td>
				</tr>
				<tr>
					<td class=\"item\" width=\"40%\">Total Crew</td>
					<td class=\"item\" width=\"5%\" valign=\"top\">:</td>
					<td class=\"field\" width=\"65%\">$numcrew</td>
				</tr>
				<tr>
					<td class=\"item\" width=\"40%\">Filipino Crew</td>
					<td class=\"item\" width=\"5%\" valign=\"top\">:</td>
					<td class=\"field\" width=\"65%\">$numcrewfil</td>
				</tr>
				<tr>
					<td class=\"item\" width=\"40%\">Foreign Crew</td>
					<td class=\"item\" width=\"5%\" valign=\"top\">:</td>
					<td class=\"field\" width=\"65%\">$numcrewforeign</td>
				</tr>
			</table>
		</div>
		<div style=\"width:30%;height:100px;float:right;border:1px solid black;\">
			<center>
	";
			if ($availpic == 1)
	echo "		<img src=\"$imgpath\" border=\"1\" width=\"$xwidth\" height=\"$xheight\" />";
			else 
	echo "		[NO IMAGE]";
	
	echo "	</center>
		</div>
	</div>
	
	<br />
	
	<table class=\"borders\" width=\"1200\" style=\"padding-left:3px;\">
		<tr>
			<th class=\"colheadersmall\" width=\"10px\">NO.</th>
			<th class=\"colheadersmall\" width=\"35px\" align=\"center\">RANK</th>
			<th class=\"colheadersmall\" width=\"100px\">NAME</th>
			<th class=\"colheadersmall\" width=\"15px\">NAT.</th>
			<th class=\"colheadersmall\" width=\"35px\">BIRTH DATE</th>
			<th class=\"colheadersmall\" width=\"10px\">AGE</th>
			<th class=\"colheadersmall\" width=\"20px\">SIGN ON</th>
			<th class=\"colheadersmall\" width=\"10px\">DAYS</th>
			<th class=\"colheadersmall\" width=\"20px\">LAST VESSEL</th>
			<th class=\"colheadersmall\" width=\"20px\">LAST SIGNOFF</th>
			<th class=\"colheadersmall\" width=\"10px\">EXP. AS LANK</th>
			<th class=\"colheadersmall\" width=\"20px\" align=\"center\">COC GRADE</th>
			<th class=\"colheadersmall\" width=\"20px\">COC EXPIRY</th>
			<th class=\"colheadersmall\" width=\"20px\" align=\"center\">GOC EXPIRY</th>			
			<th class=\"colheadersmall\" width=\"20px\">STCW ISSUED</th>			
			<th class=\"colheadersmall\" width=\"20px\">SEAMAN BOOK EXPIRY</th>		
			<th class=\"colheadersmall\" width=\"20px\">PASSPORT EXPIRY</th>			
			<th class=\"colheadersmall\" width=\"20px\">MEDICAL EXAM DATE</th>			
			<th class=\"colheadersmall\" width=\"20px\">SSO ISSUED</th>		
			<th class=\"colheadersmall\" width=\"20px\">SEAMAN BOOK NO.</th>				
		</tr>
";

		$cnt = 1;
		
		while ($rowcrewlist = mysql_fetch_array($qrycrewlist))
		{
			$hlite = "";
			$css = "odd";
			if($cnt % 2)
				$css = "even";
				
			$crewid = $rowcrewlist["CRW1"];
			$crewtype = $rowcrewlist["CL1"];
			
			if ($crewtype != "00")
			{
				$qrygetlist = mysql_query("SELECT CRW2,CRW3,CRW4,date_format(CRW5,'%Y-%m-%d') as CRW5,CRW7A,R2,CRW24,CRW26
									FROM mcms_05 c
									LEFT JOIN mcms_01 r ON r.R1=c.CRW24
									WHERE CRW1='$crewid'") or die(mysql_error());
			
				$rowgetlist = mysql_fetch_array($qrygetlist);
				
				$crewname = $rowgetlist["CRW2"] . ", " . $rowgetlist["CRW3"] . " " . $rowgetlist["CRW4"];
				$birthdate = $rowgetlist["CRW5"];
				$civilstatus = $rowgetlist["CRW7A"];
				$rank = $rowgetlist["R2"];				
				$rankcode = $rowgetlist["CRW24"];				
				$nationality = $rowgetlist["CRW26"];				
				
				$age = round(((strtotime("NOW") - strtotime($birthdate)) / 31536000));
				
				$qrygetlist2 = mysql_query("SELECT CL2 FROM mcms_18 WHERE CRW1='$crewid'") or die(mysql_error());
				$rowgetlist2 = mysql_fetch_array($qrygetlist2);
				
				$signon = $rowgetlist2["CL2"];
				
				$days = round((strtotime("NOW") - strtotime($signon)) / 86400);
				
				$qrygetlist3 = mysql_query("SELECT a.XPV3,a.XPV2,b.VSL4
									FROM m_08xpv a
									LEFT JOIN mcms_04 b ON b.VSCODE = a.XPV5
									WHERE a.XPV12 != 'PN' AND b.VSL82 = '2' AND a.XPV3 IS NOT NULL AND CRW1='$crewid'
									ORDER BY a.XPV3 DESC") or die(mysql_error());		
				
				$rowgetlist3 = mysql_fetch_array($qrygetlist3);
				$lastvessel = $rowgetlist3["VSL4"];
				$signoff = $rowgetlist3["XPV2"];
				
				//experience at lank
				
				$qryexplank1 = mysql_query("SELECT COUNT(*) AS counter1
											FROM m_08xpv
											WHERE XPV6 = '$rankcode'
											AND CRW1 = '$crewid'") or die(mysql_error());
				$rowexplank1 = mysql_fetch_array($qryexplank1);
				$counter1 = $rowexplank1["counter1"];
				
				$qryexplank2 = mysql_query("SELECT COUNT(*) AS counter2
											FROM mcms_09
											WHERE XPO3 = '$rankcode'
											AND CRW1 = '$crewid'") or die(mysql_error());
				$rowexplank2 = mysql_fetch_array($qryexplank2);
				$counter2 = $rowexplank2["counter2"];	
				
				$experiencelank = $counter1 + $counter2;
				
				$qrygetlist4 = mysql_query("SELECT R2,DOC1,LC2,LC3,LC4
											FROM mcms_07 
											LEFT JOIN mcms_01 ON LC1=R1
											where CRW1='$crewid' AND DOC1 IN ('18','27','F2','41','40','49')") or die(mysql_error());
	
				
				$qrygetlist5 = mysql_query("SELECT MED3 FROM mcms_08 WHERE CRW1='$crewid'
											ORDER BY MED3 DESC") or die(mysql_error());
				$rowgetlist5 = mysql_fetch_array($qrygetlist5);
				$medical = $rowgetlist5["MED3"];			
				
				while($rowgetlist4= mysql_fetch_array($qrygetlist4))
				{
					$doc1 = $rowgetlist4["DOC1"];
					
					switch ($doc1)
					{
						case "18": // SP COC
								$coc_grade = $rowgetlist4["R2"];
								$coc_expiry = $rowgetlist4["LC4"]; 
							break;
						case "27"://GOC
								$goc_expiry = $rowgetlist4["LC4"];
							break;
						case "F2"://SEAMAN BOOK
								$sbook_expiry = $rowgetlist4["LC4"];
								$sbookno = $rowgetlist4["LC2"];
							break;
						case "41"://PASSPORT
								$passport_expiry = $rowgetlist4["LC4"];
								$passportno = $rowgetlist4["LC2"];
							break;
						case "40"://STCW
								$stcw_issued = $rowgetlist4["LC3"];
								$stcwno = $rowgetlist4["LC2"];
							break;
						case "49"://SSO
								$sso_issued = $rowgetlist4["LC3"];
								$stcwno = $rowgetlist4["LC2"];
							break;
					}								
				}
			}
			else 
			{
				$hlite = "#E2BCE7";
				
				$qrygetlist = mysql_query("SELECT JAP2,JAP3,JAP6,JAP7,JAP12,JAP13,R2
										FROM mcms_25 a
										LEFT JOIN mcms_01 b ON b.R1=a.JAP4
										WHERE JAP5='$vessel_code' AND JAP1='$crewid'") or die(mysql_error());
				
				$rowgetlist = mysql_fetch_array($qrygetlist);
				
				$crewname = $rowgetlist["JAP2"] . ", " . $rowgetlist["JAP3"];
				$birthdate = $rowgetlist["JAP6"];
				$civilstatus = $rowgetlist["JAP7"];
				$rank = $rowgetlist["R2"];	
				
				if ($rowgetlist["JAP12"] == "")
					$sbookno = $rowgetlist["JAP12"];
				else 
					$sbookno = "no data";
				
				if ($rowgetlist["JAP13"] == "")
					$passport_expiry = $rowgetlist["JAP13"];
				else 
					$passport_expiry = "no data";
				
			}
			
			if ($xcrewid == $crewid)
				$hlite = "Yellow";
				
			
			
echo "		
			<tr class=\"$css\" title=\"$crewid\" style=\"background-color:$hlite;\">
				<td class=\"list\">$cnt.</td>
				<td class=\"list\" align=\"center\">$rank</td>
				<td class=\"list\"><a href=\"javascript:openWindow('201.php?crewid=$crewid', '201$cnt', 900, 600);\">$crewname</a></td>
				<td class=\"list\" align=\"center\">$nationality</td>
				<td class=\"list\" align=\"center\">$birthdate</td>
				<td class=\"list\" align=\"center\">$age</td>
				<td class=\"list\" align=\"center\">$signon</td>
				<td class=\"list\" align=\"center\">$days</td>
				<td class=\"list\" align=\"center\">$lastvessel</td>
				<td class=\"list\" align=\"center\">$signoff</td>
				<td class=\"list\" align=\"center\">$experiencelank</td>
				<td class=\"list\" align=\"center\">$coc_grade</td>
";				
				if (checkpath("scanned/$crewid/18.pdf"))
					echo "<td class=\"list\" align=\"center\"><a href=\"javascript:openWindow('scanned/$crewid/18.pdf', 'showdoc18$cnt', 900, 600);\">$coc_expiry</a></td>";
				else if (checkpath("scanned/$crewid/C0.pdf"))
						echo "<td class=\"list\" align=\"center\"><a href=\"javascript:openWindow('scanned/$crewid/C0.pdf', 'showdoc18$cnt', 900, 600);\">$coc_expiry</a></td>";
					else 
						echo "<td class=\"list\" align=\"center\">$coc_expiry</td>";
				
				if (checkpath("scanned/$crewid/27.pdf"))					
					echo "<td class=\"list\" align=\"center\"><a href=\"javascript:openWindow('scanned/$crewid/27.pdf', 'showdoc27$cnt', 900, 600);\">$goc_expiry</a></td>";
				else 
					echo "<td class=\"list\" align=\"center\">$goc_expiry</td>";
				
				if (checkpath("scanned/$crewid/40.pdf"))
					echo "<td class=\"list\" align=\"center\"><a href=\"javascript:openWindow('scanned/$crewid/40.pdf', 'showdoc40$cnt', 900, 600);\">$stcw_issued</a></td>";
				else 
					echo "<td class=\"list\" align=\"center\">$stcw_issued</td>";
				
				if (checkpath("scanned/$crewid/F2.pdf"))
					echo "<td class=\"list\" align=\"center\"><a href=\"javascript:openWindow('scanned/$crewid/F2.pdf', 'showdocF2$cnt', 900, 600);\">$sbook_expiry</a></td>";
				else 
					echo "<td class=\"list\" align=\"center\">$sbook_expiry</td>";
					
				if (checkpath("scanned/$crewid/41.pdf"))
					echo "<td class=\"list\" align=\"center\"><a href=\"javascript:openWindow('scanned/$crewid/41.pdf', 'showdoc41$cnt', 900, 600);\">$passport_expiry</a></td>";
				else 
					echo "<td class=\"list\" align=\"center\">$passport_expiry</td>";
					
				if (checkpath("scanned/$crewid/P7.pdf"))
					echo "<td class=\"list\" align=\"center\"><a href=\"javascript:openWindow('scanned/$crewid/P7.pdf', 'showdocP7$cnt', 900, 600);\">$medical</a></td>";
				else 
					echo "<td class=\"list\" align=\"center\">$medical</td>";
					
				if (checkpath("scanned/$crewid/49.pdf"))
					echo "<td class=\"list\" align=\"center\"><a href=\"javascript:openWindow('scanned/$crewid/49.pdf', 'showdoc49$cnt', 900, 600);\">$sso_issued</a></td>";
				else 
					echo "<td class=\"list\" align=\"center\">$sso_issued</td>";
					
				if (checkpath("scanned/$crewid/F2.pdf"))
					echo "<td class=\"list\" align=\"center\"><a href=\"javascript:openWindow('scanned/$crewid/F2.pdf', 'showdocF2$cnt', 900, 600);\">$sbookno</a></td>";
				else
					echo "<td class=\"list\" align=\"center\">$sbookno</td>";
echo "
			</tr>


";
			$cnt++;
		}



echo "
	</table>

	<br /><br /><br /><br /><br />

";
include("footer.php");
?>