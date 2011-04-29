<?php

// include("veritas/connectdb.php");
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


$dateformat = "dMY";
$title = "Experiences with Veritas";

//$qryexperience_vmc = mysql_query("SELECT * FROM
//								(
//									SELECT '1' AS POS,cc.CCID,'Veritas' AS COMPANY,v.MANAGEMENTCODE,LEFT(v.ALIAS2,10) AS VESSEL,v.VESSEL AS VESSELNAME,v.VESSELCODE,r.ALIAS1 AS RANKALIAS,v.GROSSTON,vs.ENGINEMAIN AS ENGINE,
//									vt.VESSELTYPECODE,v.TRADEROUTECODE,vs.ENGINEPOWER,
//									cc.DATEEMB,IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,cc.DISEMBREASONCODE
//									FROM crewchange cc
//									LEFT JOIN vessel v ON v.VESSELCODE=cc.VESSELCODE
//									LEFT JOIN rank r ON r.RANKCODE=cc.RANKCODE
//									LEFT JOIN vesselspecs vs ON vs.VESSELCODE=v.VESSELCODE
//									LEFT JOIN vesseltype vt ON vt.VESSELTYPECODE=v.VESSELTYPECODE
//									LEFT JOIN vesselsize vz ON vz.VESSELSIZECODE=v.VESSELSIZECODE
//									WHERE cc.APPLICANTNO=$applicantno
//								) x
//								ORDER BY x.DATEDISEMB DESC
//							") or die(mysql_error());

echo "
<html>\n
<head>\n
<title>
Printing - Crew Experiences with Veritas
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
<body style=\"overflow:auto;\">

<div style=\"width:755px;background-color:white;margin:10 10 10 10;\">

";

include("repheader.php");
	
if (empty($directprint))
	echo "<input type=\"button\" value=\"Print\" id=\"PAScreenOut\" onclick=\"window.print();\">";
	
echo "
	<table style=\"width:100%;font-size:1.2em;\">
		<tr><td>&nbsp;</td></tr>
	";
	
	$cnt = 1;
	$withentry = 0;
	
	
	while ($rowexperience_vmc = mysql_fetch_array($qryexperience))
	{
		
//				cc.CCID,'Veritas' AS COMPANY,v.MANAGEMENTCODE,LEFT(v.ALIAS2,10) AS VESSEL,v.VESSEL AS VESSELNAME,v.VESSELCODE,r.ALIAS1 AS RANKALIAS,
//				v.GROSSTON,vs.ENGINEMAIN AS ENGINE,
//				vt.VESSELTYPECODE,v.TRADEROUTECODE,vs.ENGINEPOWER,
//				cc.DATEEMB,IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,cc.DISEMBREASONCODE,
//				dr.REASON AS DISEMBREASON,vt.VESSELTYPE,tr.TRADEROUTE

		$pos = $rowexperience_vmc["POS"];
		
		if ($pos == 1)
		{
			$withentry = 1;
			
			$ccid = $rowexperience_vmc["CCID"];
			$vesselname = $rowexperience_vmc["VESSELNAME"];
			$rankalias = $rowexperience_vmc["RANKALIAS"];
			
			if (!empty($rowexperience_vmc["DATEEMB"]))
				$dateemb = date($dateformat,strtotime($rowexperience_vmc["DATEEMB"]));
			else 
				$dateemb = "";
			
			if (!empty($rowexperience_vmc["DATEDISEMB"]))
				$datedisemb = date($dateformat,strtotime($rowexperience_vmc["DATEDISEMB"]));
			else 
				$datedisemb = "";
				
			$months = floor((strtotime($rowexperience_vmc["DATEDISEMB"]) - strtotime($rowexperience_vmc["DATEEMB"])) / 2592000);
				
			$grosston = $rowexperience_vmc["GROSSTON"];
			$engine = $rowexperience_vmc["ENGINE"];
			$enginepower = $rowexperience_vmc["ENGINEPOWER"];
			$vesseltypecode = $rowexperience_vmc["VESSELTYPECODE"];
			$vesseltype = $rowexperience_vmc["VESSELTYPE"];
			$traderoutecode = $rowexperience_vmc["TRADEROUTECODE"];
			$traderoute = $rowexperience_vmc["TRADEROUTE"];
			$disembreason = $rowexperience_vmc["DISEMBREASON"];
			
//			$qrycheckpromoted = mysql_query("SELECT CCID,VEP0,NEXTRANK,r.RANKFULL,r.ALIAS1
//											FROM crewevalhdr c
//											LEFT JOIN rank r ON c.NEXTRANK=r.RANKCODE
//											WHERE c.CCID=$ccid") or mysql_error();
//			
//			$promoteshow = "NO";
//			
//			if (mysql_num_rows($qrycheckpromoted) > 0)
//			{
//				$rowcheckpromoted = mysql_fetch_array($qrycheckpromoted);
//				$rankcodepromote = $rowcheckpromoted["NEXTRANK"];
//				$rankfullpromote = $rowcheckpromoted["RANKFULL"];
//				$rankaliaspromote = $rowcheckpromoted["ALIAS1"];
//				$recomendpromote = $rowcheckpromoted["VEP0"];
//				
//				if (!empty($rankaliaspromote))
//					$promoteshow = $rankfullpromote . "( " . $rankaliaspromote . " )";
//			}
//			
			$qrycer=mysql_query("SELECT EVALNO,VEP0,FINALGRADE,ALIAS1 AS NEXTRANK
				FROM crewevalhdr c
				LEFT JOIN rank r ON c.NEXTRANK=r.RANKCODE
				WHERE CCID=$ccid ORDER BY EVALNO") or die(mysql_error());
			$comment1="";
			$comment2="";
			$comment3="";
			$comment4="";
			$recomendpromote="---";
			while($rowcer=mysql_fetch_array($qrycer))
			{
				$evalno=$rowcer["EVALNO"];
				$vep0=$rowcer["VEP0"];
				$finalgrade=$rowcer["FINALGRADE"];
				$nextrank=$rowcer["NEXTRANK"];
				$placecomment="comment".$evalno;
				
				//computegrade($comp_ccid,$comp_evalno)
				
				//$compgrade = computegrade($ccid,$evalno);
				
				if(!empty($finalgrade) && $finalgrade !=0)
				{
					//get performance
					
					if($finalgrade != 0)
					{
						if($finalgrade>0)
						{
							if($finalgrade<=1.5)
								$perf="BAD";
							else 
							{
								if($finalgrade<=2.4)
									$perf="POOR";
								else 
								{
									if($finalgrade<=3.4)
										$perf="SATISFACTORY";
									else 
									{
										if($finalgrade<=4.4)
											$perf="GOOD";
										else 
										{
											$perf="EXCELLENT";
										}
									}
								}
							}
							$finalgrade = number_format($finalgrade,1);
							$$placecomment=$perf." - ".$finalgrade;
						}
					}

				}
				else
				{
					$totgrade = computegrade($ccid,$evalno);
					
						if ($totgrade == 100)
							$finalgrade = 5.0;
						else
						if (($totgrade >= 95) && ($totgrade < 100))
							$finalgrade = 4.5;
						else
						if (($totgrade >= 90) && ($totgrade < 95))
							$finalgrade = 4.0;
						else
						if (($totgrade >= 85) && ($totgrade < 90))
							$finalgrade = 3.5;
						else
						if (($totgrade >= 80) && ($totgrade < 85))
							$finalgrade = 3.0;
						else
						if (($totgrade >= 75) && ($totgrade < 80))
							$finalgrade = 2.5;
						else
						if (($totgrade >= 70) && ($totgrade < 75))
							$finalgrade = 2.0;
						else
						if (($totgrade >= 65) && ($totgrade < 70))
							$finalgrade = 1.5;
						else
							$finalgrade = 1.0;
				}
				
					
				if(!empty($vep0))
				{
					if($vep0==1)
						$recomendpromote = "NEGATIVE";
					else if($vep0==2)
						$recomendpromote = "PREMATURE";
					else if($vep0==3)
						$recomendpromote = "YES (".$nextrank.")";
				}
				else 
				{
					$recomendpromote="---";
				}
				
				if($finalgrade != 0)
				{
					if($finalgrade>0)
					{
						if($finalgrade<=1.5)
							$perf="BAD";
						else 
						{
							if($finalgrade<=2.4)
								$perf="POOR";
							else 
							{
								if($finalgrade<=3.4)
									$perf="SATISFACTORY";
								else 
								{
									if($finalgrade<=4.4)
										$perf="GOOD";
									else 
									{
										$perf="EXCELLENT";
									}
								}
							}
						}
						$finalgrade = number_format($finalgrade,1);
						$$placecomment=$perf." - ".$finalgrade;
					}
				}
				
				
			}
			
			//get port
			$qryport=mysql_query("SELECT p.PORT AS EMBPORT,p1.PORT AS DISEMBPORT
				FROM crewchange cc
				LEFT JOIN port p ON cc.EMBPORTID=p.PORTID
				LEFT JOIN port p1 ON cc.DISEMBPORTID=p1.PORTID
				WHERE cc.CCID=$ccid") or die(mysql_error());
			$rowport=mysql_fetch_array($qryport);
			$embport=$rowport["EMBPORT"];
			$disembport=$rowport["DISEMBPORT"];
			echo "
			<tr>
				<td style=\"border-bottom:1px solid Gray;\">
					<table class=\"listrow\" style=\"width:40%;float:left;\">
						<tr>
							<th width=\"5%\">$cnt.</th>
							<th width=\"40%\">Rank</th>
							<th width=\"5%\">:</th>
							<td width=\"50%\" style=\"font-size:1.2em;font-weight:Bold;\">$rankalias</td>
						</tr>
						<tr>
							<th>&nbsp;</th>
							<th>Embark</th>
							<th>:</th>
							<th>$dateemb / <span style=\"font-size:0.8em;\"><nobr>$embport</nobr></span></th>
						</tr>
						<tr>
							<th>&nbsp;</th>
							<th>Disembark</th>
							<th>:</th>
							<th>$datedisemb / <span style=\"font-size:0.8em;\"><nobr>$disembport</nobr></span></th>
						</tr>
						<tr>
							<th>&nbsp;</th>
							<th>No. of Months</th>
							<th>:</th>
							<th>$months</th>
						</tr>
						<tr>
							<th>&nbsp;</th>
							<th>Remarks</th>
							<th>:</th>
							<th>$disembreason</th>
						</tr>
					
					</table>
					
					<table class=\"listrow\" style=\"width:40%;float:left;\">
						<tr>
							<th width=\"35%\">Vessel</th>
							<th width=\"5%\">:</th>
							<td width=\"60%\" style=\"font-size:1.0em;font-weight:Bold;\"><nobr>$vesselname</nobr></td>
						</tr>
						<tr>
							<th>Type</th>
							<th>:</th>
							<th>$vesseltype</th>
						</tr>
						<tr>
							<th>Trade Route</th>
							<th>:</th>
							<th>$traderoute</th>
						</tr>
						<tr>
							<th>Engine</th>
							<th>:</th>
							<th>$engine</th>
						</tr>
						<tr>
							<th>Engine Power</th>
							<th>:</th>
							<th>$enginepower</th>
						</tr>
						<tr>
							<th colspan=\"3\" style=\"text-align:right;\">Recommended for Promotion:</th>
						</tr>
					</table>
					<table class=\"listrow\" style=\"width:20%;float:right;\">
						<tr>
							<th colspan=\"2\" style=\"font-size:1.0em;\">Comment</th>
						</tr>
						<tr>
							<th>1.</th>
							<th style=\"font-size:0.9em;\">$comment1</th>
						</tr>
						<tr>
							<th>2.</th>
							<th style=\"font-size:0.9em;\">$comment2</th>
						</tr>
						<tr>
							<th>3.</th>
							<th style=\"font-size:0.9em;\">$comment3</th>
						</tr>
						<tr>
							<th>4.</th>
							<th style=\"font-size:0.9em;\">$comment4</th>
						</tr>
						<tr>
							<th colspan=\"3\" style=\"font-size:0.9em;\">$recomendpromote</th>
						</tr>
					</table>
				
				</td>			
			</tr>
			";
			
		}
		
		$cnt++;
	}
	
	if ($withentry == 0)
		echo "
			<br /><br />
			<span style=\"font-size:1.2em;font-weight:Bold;\">[NO VERITAS EXPERIENCE]</span>
		";

echo "	
	</table>

";



echo "
</div>
";
// include('include/printclose.inc');
echo "
</body>

";

?>