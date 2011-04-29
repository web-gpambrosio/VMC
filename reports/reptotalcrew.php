<?php

include('veritas/connectdb.php');
//include('connectdb.php');

session_start();

include('../include/stylephp.inc');

$datetimenow=date("Y-m-d H:i:s");
$datenow=date("Y-m-d");
$datenowshow=date("d M Y");
$tablewidth="700px";


if (isset($_GET['divcode']))
	$divcode = $_GET['divcode'];

if (isset($_GET['fleetno']))
	$fleetno = $_GET['fleetno'];

if (isset($_GET['principalcode']))
	$principalcode = $_GET['principalcode'];

if (isset($_GET['vesseltypecode']))
	$vesseltypecode = $_GET['vesseltypecode'];

if(empty($divcode))
{
	$addhdr="All Division";
}
else 
{
	$addhdr="Division $divcode";
	if(!empty($fleetno))
		$addhdr.=" / Fleet $fleetno";
}

if(!empty($principalcode))
{
	$qryprincipal=mysql_query("SELECT PRINCIPAL FROM principal WHERE PRINCIPALCODE='$principalcode'") or die(mysql_error());
	$rowprincipal=mysql_fetch_array($qryprincipal);
	$principal=$rowprincipal["PRINCIPAL"];
	$addhdr1="Principal: $principal";
	
}

if(!empty($vesseltypecode))
{
	$qryvesseltype=mysql_query("SELECT VESSELTYPE FROM vesseltype WHERE VESSELTYPECODE='$vesseltypecode'") or die(mysql_error());
	$rowvesseltype=mysql_fetch_array($qryvesseltype);
	$vesseltype=$rowvesseltype["VESSELTYPE"];
	if(!empty($addhdr1))
		$addhdr1.=" / ";
	$addhdr1.="Vessel Type: $vesseltype";
}
if(!empty($addhdr1))
{
	$addrowhdr="
		<tr height=\"20px\">\n
			<td style=\"text-align:center;font-size:10pt;font-weight:bold;\">$addhdr1</td>\n
		</tr>";
}

$mainheader="
<table cellspacing=\"0\" cellpadding=\"0\" width=\"$tablewidth\">
	<tr height=\"20px\">\n
		<td style=\"text-align:center;font-size:16pt;font-weight:bold;\">VERITAS MARITIME CORPORATION</td>\n
	</tr>
	$addrowhdr
	<tr height=\"20px\">\n
		<td style=\"text-align:center;font-size:10pt;font-weight:bold;\">$addhdr</td>\n
	</tr>
	<tr height=\"20px\">\n
		<td style=\"text-align:center;font-size:10pt;\">As of $datenowshow</td>\n 
	</tr>
</table>";

	#*******************POST VALUES*************





echo	"<html>\n
<head>\n
<link rel=\"StyleSheet\" type=\"text/css\" href=\"../veripro.css\" />
<style>
#noprint
{
	display: none;
}
</style>
<script>

</script>\n

</head>\n

<body onload=\"\" style=\"\">\n

<form name=\"scholarfasttrack\" id=\"scholarfasttrack\" method=\"POST\">\n";
	echo $mainheader;
	//GET ONBOARD//GET ONBOARD//GET ONBOARD//GET ONBOARD//GET ONBOARD
	echo "
	<table>
		<tr height=\"20px\">\n
			<td style=\"text-align:center;font-size:12pt;font-weight:bold;\">ON BOARD</td>\n
		</tr>";
	
		if(!empty($divcode))
		{
			$addwheredivcode = "AND v.DIVCODE='$divcode'";
			if(!empty($fleetno))
				$addwheredivcode .= " AND v.FLEETNO='$fleetno'";
		}
		else 
			$addwheredivcode = "";
			
		if(!empty($principalcode))
		{
			$addwhereprincipalcode = "AND m.PRINCIPALCODE='$principalcode'";
		}
		else 
			$addwhereprincipalcode = "";
			
		if(!empty($vesseltypecode))
		{
			$addwherevesseltypecode = "AND v.VESSELTYPECODE='$vesseltypecode'";
		}
		else 
			$addwherevesseltypecode = "";
			
			
		$qrygetonboard=mysql_query("SELECT RANKCODE,RANKFULL 
			FROM rank 
			WHERE RANKLEVELCODE IN ('M','O') 
			ORDER BY RANKLEVELCODE,RANKING") or die(mysql_error());
		$totonboardofficer=0;
		while($rowgetonboard=mysql_fetch_array($qrygetonboard))
		{
			$rankcode=$rowgetonboard["RANKCODE"];
			$rank=$rowgetonboard["RANKFULL"];
			$qrygetcount=mysql_query("SELECT COUNT(*) AS COUNT 
				FROM crewchange cc
				LEFT JOIN vessel v ON cc.VESSELCODE=v.VESSELCODE
				LEFT JOIN management m ON v.MANAGEMENTCODE=m.MANAGEMENTCODE
				LEFT JOIN watchlist_veritas w ON cc.APPLICANTNO=w.APPLICANTNO
				LEFT JOIN crew c ON cc.APPLICANTNO=c.APPLICANTNO
				LEFT JOIN watchlist_poea wp ON c.GNAME=wp.GNAME AND c.FNAME=wp.FNAME AND c.MNAME=wp.MNAME
				WHERE cc.RANKCODE='$rankcode' AND LEFT(NOW(),10) BETWEEN DATEEMB AND DATEDISEMB 
				AND DEPMNLDATE IS NOT NULL AND w.APPLICANTNO IS NULL AND wp.IDNO IS NULL
				$addwheredivcode $addwhereprincipalcode $addwherevesseltypecode
				") or die(mysql_error());
			$rowgetcount=mysql_fetch_array($qrygetcount);
			$getcount=$rowgetcount["COUNT"];
			$getcountshow=number_format($getcount,0);
			echo "
			<tr height=\"15px\">\n
				<td style=\"text-align:left;font-size:10pt;font-weight:bold;\">$rank</td>\n
				<td style=\"text-align:right;font-size:10pt;font-weight:bold;\">&nbsp;</td>\n
				<td style=\"text-align:right;font-size:10pt;font-weight:bold;\">$getcountshow</td>\n
			</tr>";
			$totonboardofficer+=$getcount;
		}
		$totonboardofficershow=number_format($totonboardofficer,0);
		echo "
		<tr height=\"15px\">\n
			<td style=\"text-align:right;font-size:10pt;font-weight:bold;\">&nbsp;</td>\n
			<td style=\"text-align:right;font-size:10pt;font-weight:bold;\">Total Officer</td>\n
			<td style=\"text-align:right;font-size:10pt;font-weight:bold;\">&nbsp;</td>\n
			<td style=\"text-align:right;font-size:10pt;font-weight:bold;\">&nbsp;</td>\n
			<td style=\"text-align:right;font-size:10pt;font-weight:bold;\">$totonboardofficershow</td>\n
		</tr>";
		//get total Ratings on board
		$qrygetcount=mysql_query("SELECT COUNT(*) AS COUNT 
			FROM crewchange cc
			LEFT JOIN vessel v ON cc.VESSELCODE=v.VESSELCODE
			LEFT JOIN management m ON v.MANAGEMENTCODE=m.MANAGEMENTCODE
			LEFT JOIN rank r ON cc.RANKCODE=r.RANKCODE
			LEFT JOIN watchlist_veritas w ON cc.APPLICANTNO=w.APPLICANTNO
			LEFT JOIN crew c ON cc.APPLICANTNO=c.APPLICANTNO
			LEFT JOIN watchlist_poea wp ON c.GNAME=wp.GNAME AND c.FNAME=wp.FNAME AND c.MNAME=wp.MNAME
			WHERE LEFT(NOW(),10) BETWEEN DATEEMB AND DATEDISEMB 
			AND DEPMNLDATE IS NOT NULL AND RANKLEVELCODE='S' AND w.APPLICANTNO IS NULL AND wp.IDNO IS NULL
			$addwheredivcode $addwhereprincipalcode $addwherevesseltypecode") or die(mysql_error());
		$rowgetcount=mysql_fetch_array($qrygetcount);
		$totonboardratings=$rowgetcount["COUNT"];
		echo "
		<tr height=\"15px\">\n
			<td style=\"text-align:right;font-size:10pt;font-weight:bold;\">&nbsp;</td>\n
			<td style=\"text-align:right;font-size:10pt;font-weight:bold;\">Total Ratings</td>\n
			<td style=\"text-align:right;font-size:10pt;font-weight:bold;\">&nbsp;</td>\n
			<td style=\"text-align:right;font-size:10pt;font-weight:bold;\">&nbsp;</td>\n
			<td style=\"text-align:right;font-size:10pt;font-weight:bold;\">$totonboardratings</td>\n
		</tr>";
		$totonboard=$totonboardofficer+$totonboardratings;
		$totonboardshow=number_format($totonboard,0);
		echo "
		<tr height=\"15px\">\n
			<td style=\"text-align:right;font-size:12pt;font-weight:bold;\">TOTAL</td>\n
			<td style=\"text-align:right;font-size:10pt;font-weight:bold;\">&nbsp;</td>\n
			<td style=\"text-align:right;font-size:10pt;font-weight:bold;\">&nbsp;</td>\n
			<td style=\"text-align:right;font-size:12pt;font-weight:bold;\">&nbsp;</td>\n
			<td style=\"text-align:right;font-size:12pt;font-weight:bold;\">&nbsp;</td>\n
			<td style=\"text-align:right;font-size:12pt;font-weight:bold;\">&nbsp;</td>\n
			<td style=\"text-align:right;font-size:12pt;font-weight:bold;\">$totonboardshow</td>\n
		</tr>";
	echo "
	</table>";
	//GET STANDBY//GET STANDBY//GET STANDBY//GET STANDBY//GET STANDBY
	echo "
	<table>
		<tr height=\"20px\">\n
			<td style=\"text-align:center;font-size:12pt;font-weight:bold;\">ON STAND-BY</td>\n
		</tr>";
		$qrygetonstandby=mysql_query("SELECT RANKCODE,RANKFULL 
			FROM rank 
			WHERE RANKLEVELCODE IN ('M','O') 
			ORDER BY RANKLEVELCODE,RANKING") or die(mysql_error());
		$totonstandbyofficer=0;
		while($rowgetonstandby=mysql_fetch_array($qrygetonstandby))
		{
			$rankcode=$rowgetonstandby["RANKCODE"];
			$rank=$rowgetonstandby["RANKFULL"];
			$qrygetcount=mysql_query("SELECT COUNT(*) AS COUNT FROM
				(SELECT cc.APPLICANTNO
				FROM crewchange cc
				LEFT JOIN vessel v ON cc.VESSELCODE=v.VESSELCODE
				LEFT JOIN management m ON v.MANAGEMENTCODE=m.MANAGEMENTCODE
				LEFT JOIN 
					(SELECT APPLICANTNO
					FROM crewchange 
					WHERE LEFT(NOW(),10) BETWEEN DATEEMB AND DATEDISEMB AND DEPMNLDATE IS NOT NULL AND ARRMNLDATE IS NULL 
					AND RANKCODE='$rankcode') x ON cc.APPLICANTNO=x.APPLICANTNO
				LEFT JOIN watchlist_veritas w ON cc.APPLICANTNO=w.APPLICANTNO
				LEFT JOIN crew c ON cc.APPLICANTNO=c.APPLICANTNO
				LEFT JOIN watchlist_poea wp ON c.GNAME=wp.GNAME AND c.FNAME=wp.FNAME AND c.MNAME=wp.MNAME
				WHERE cc.RANKCODE='$rankcode' AND LEFT(NOW(),10) NOT BETWEEN DATEEMB AND DATEDISEMB 
				AND ARRMNLDATE<LEFT(NOW(),10) AND DATEDIFF(NOW(),ARRMNLDATE)<365 
				AND w.APPLICANTNO IS NULL AND wp.IDNO IS NULL AND x.APPLICANTNO IS NULL
				$addwheredivcode $addwhereprincipalcode $addwherevesseltypecode
				GROUP BY cc.APPLICANTNO) z") or die(mysql_error());
			$rowgetcount=mysql_fetch_array($qrygetcount);
			$getcount=$rowgetcount["COUNT"];
			$getcountshow=number_format($getcount,0);
			echo "
			<tr height=\"15px\">\n
				<td style=\"text-align:left;font-size:10pt;font-weight:bold;\">$rank</td>\n
				<td style=\"text-align:right;font-size:10pt;font-weight:bold;\">&nbsp;</td>\n
				<td style=\"text-align:right;font-size:10pt;font-weight:bold;\">$getcountshow</td>\n
			</tr>";
			$totonstandbyofficer+=$getcount;
		}
		$totonstandbyofficershow=number_format($totonstandbyofficer,0);
		echo "
		<tr height=\"15px\">\n
			<td style=\"text-align:right;font-size:10pt;font-weight:bold;\">&nbsp;</td>\n
			<td style=\"text-align:right;font-size:10pt;font-weight:bold;\">Total Officer</td>\n
			<td style=\"text-align:right;font-size:10pt;font-weight:bold;\">&nbsp;</td>\n
			<td style=\"text-align:right;font-size:10pt;font-weight:bold;\">&nbsp;</td>\n
			<td style=\"text-align:right;font-size:10pt;font-weight:bold;\">$totonstandbyofficershow</td>\n
		</tr>";
		//get total Ratings on standby
		$qrygetcount=mysql_query("SELECT COUNT(*) AS COUNT FROM
			(SELECT cc.APPLICANTNO
			FROM crewchange cc
			LEFT JOIN vessel v ON cc.VESSELCODE=v.VESSELCODE
			LEFT JOIN management m ON v.MANAGEMENTCODE=m.MANAGEMENTCODE
			LEFT JOIN 
				(SELECT APPLICANTNO
				FROM crewchange c1
				LEFT JOIN rank r ON c1.RANKCODE=r.RANKCODE
				WHERE LEFT(NOW(),10) BETWEEN DATEEMB AND DATEDISEMB AND DEPMNLDATE IS NOT NULL AND ARRMNLDATE IS NULL 
				AND RANKLEVELCODE='S') x ON cc.APPLICANTNO=x.APPLICANTNO
			LEFT JOIN rank r ON cc.RANKCODE=r.RANKCODE
			LEFT JOIN watchlist_veritas w ON cc.APPLICANTNO=w.APPLICANTNO
			LEFT JOIN crew c ON cc.APPLICANTNO=c.APPLICANTNO
			LEFT JOIN watchlist_poea wp ON c.GNAME=wp.GNAME AND c.FNAME=wp.FNAME AND c.MNAME=wp.MNAME
			WHERE LEFT(NOW(),10) NOT BETWEEN DATEEMB AND DATEDISEMB 
			AND ARRMNLDATE<LEFT(NOW(),10) AND DATEDIFF(NOW(),ARRMNLDATE)<365 
			AND RANKLEVELCODE='S' AND w.APPLICANTNO IS NULL AND wp.IDNO IS NULL AND x.APPLICANTNO IS NULL
			$addwheredivcode $addwhereprincipalcode $addwherevesseltypecode
			GROUP BY cc.APPLICANTNO) z") or die(mysql_error());
		$rowgetcount=mysql_fetch_array($qrygetcount);
		$totonstandbyratings=$rowgetcount["COUNT"];
		$totonstandbyratingsshow=number_format($totonstandbyratings,0);
		echo "
		<tr height=\"15px\">\n
			<td style=\"text-align:right;font-size:10pt;font-weight:bold;\">&nbsp;</td>\n
			<td style=\"text-align:right;font-size:10pt;font-weight:bold;\">Total Ratings</td>\n
			<td style=\"text-align:right;font-size:10pt;font-weight:bold;\">&nbsp;</td>\n
			<td style=\"text-align:right;font-size:10pt;font-weight:bold;\">&nbsp;</td>\n
			<td style=\"text-align:right;font-size:10pt;font-weight:bold;\">$totonstandbyratingsshow</td>\n
		</tr>";
		$totonstandby=$totonstandbyofficer+$totonstandbyratings;
		$totonstandbyshow=number_format($totonstandby,0);
		echo "
		<tr height=\"15px\">\n
			<td style=\"text-align:right;font-size:12pt;font-weight:bold;\">TOTAL</td>\n
			<td style=\"text-align:right;font-size:10pt;font-weight:bold;\">&nbsp;</td>\n
			<td style=\"text-align:right;font-size:10pt;font-weight:bold;\">&nbsp;</td>\n
			<td style=\"text-align:right;font-size:12pt;font-weight:bold;\">&nbsp;</td>\n
			<td style=\"text-align:right;font-size:12pt;font-weight:bold;\">&nbsp;</td>\n
			<td style=\"text-align:right;font-size:12pt;font-weight:bold;\">&nbsp;</td>\n
			<td style=\"text-align:right;font-size:12pt;font-weight:bold;\">$totonstandbyshow</td>\n
		</tr>";
		
		$grandtotal=number_format(($totonboard+$totonstandby),0);
		//get GRAND TOTAL
	echo "
		<tr height=\"15px\">\n
			<td colspan=\"2\" style=\"text-align:right;font-size:14pt;font-weight:bold;\">TOTAL ACTIVE CREW</td>\n
			<td style=\"text-align:right;font-size:10pt;font-weight:bold;\">&nbsp;</td>\n
			<td style=\"text-align:right;font-size:12pt;font-weight:bold;\">&nbsp;</td>\n
			<td style=\"text-align:right;font-size:12pt;font-weight:bold;\">&nbsp;</td>\n
			<td style=\"text-align:right;font-size:12pt;font-weight:bold;\">&nbsp;</td>\n
			<td style=\"text-align:right;font-size:12pt;font-weight:bold;\">&nbsp;</td>\n
			<td style=\"text-align:right;font-size:14pt;font-weight:bold;\">$grandtotal</td>\n
		</tr>
	</table>
</form>";
include('veritas/include/printclose.inc');
echo "
</body>\n
</html>\n";

?>
