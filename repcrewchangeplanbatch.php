<?php

include('veritas/connectdb.php');
//include('connectdb.php');

session_start();

include('include/stylephp.inc');

$datetimenow=date("Y-m-d H:i:s");
$datenow=date("Y-m-d");
$datenowshow=date("d M Y");
$tablewidth="915px";

if (isset($_GET["vslcode"]))
	$vslcode = $_GET["vslcode"];

if (isset($_GET["bno"]))
	$batchno = $_GET["bno"];

$qrygetvessel = mysql_query("SELECT VESSELCODE,VESSEL FROM vessel WHERE VESSELCODE='$vslcode'") or die(mysql_error());
$rowgetvessel = mysql_fetch_array($qrygetvessel);
$vesselname = $rowgetvessel["VESSEL"];

$mainheader="
<table cellspacing=\"0\" cellpadding=\"0\" width=\"$tablewidth\">
	<tr height=\"20px\">\n
		<td style=\"text-align:center;font-size:16pt;font-weight:bold;\">VERITAS MARITIME CORPORATION</td>\n
	</tr>
	<tr height=\"20px\">\n
		<td style=\"text-align:center;font-size:13pt;font-weight:bold;color:Blue;\">CREW BATCHING</td>\n
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr height=\"20px\">\n
		<td style=\"text-align:center;font-size:12pt;font-weight:Bold;\">Vessel: $vesselname / Batch No.: $batchno</td>\n 
	</tr>
</table>";


$styleheader = "style=\"font-size:0.7em;font-weight:Bold;text-align:center;border-bottom:1px solid Gray;\"";
$styledetail = "style=\"font-size:0.7em;border-bottom:1px dashed gray;border-right:1px solid silver;\"";

echo	"
<html>\n
<head>\n
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />

</head>\n

<body onload=\"\" style=\"\">\n

	$mainheader
	
	<br /><br />
	<div style=\"width:$tablewidth\">
		
		<table style=\"width:100%;empty-cells:show;\">
			<tr>
				<td colspan=\"4\" style=\"text-align:center;font-size:0.8em;font-weight:Bold;border:1px solid black;background-color:#DCDCDC;\">DISEMBARKING</td>
				<td colspan=\"4\" style=\"text-align:center;font-size:0.8em;font-weight:Bold;border:1px solid black;background-color:#DCDCDC;\">EMBARKING</td>
			</tr>
			<tr>
				<td $styleheader>RANK</td>
				<td $styleheader>NAME</td>
			<!--	<td $styleheader>EMBARK</td>  -->
				<td $styleheader>DISEMBARK</td>  
			<!--	<td $styleheader>E.O.C.</td>  -->
				<td $styleheader style=\"border-right:1px solid Black;\">EST. DATE</td>
				<td $styleheader>RANK</td>
				<td $styleheader>NAME</td>
				<td $styleheader>EX-VSL</td>
				<td $styleheader>DATE</td>
			</tr>
			";
			
			$qrygetbatch = mysql_query("SELECT cc.CCID AS CCID1,cc.APPLICANTNO AS APPNO1,cc.BATCHNO AS BATCHNO1,
							c.FNAME AS FNAME1,c.GNAME AS GNAME1,c.MNAME AS MNAME1,
							cc.DATEEMB AS DATEEMB1,IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB1,
							cc.DATEDISEMB AS EOC1,cc.RANKCODE AS RANKCODE1,r.ALIAS1 AS RANKALIAS1,
							cc2.CCID AS CCID2,cc2.APPLICANTNO AS APPNO2,cc2.BATCHNO AS BATCHNO2,
							c2.FNAME AS FNAME2,c2.GNAME AS GNAME2,c2.MNAME AS MNAME2,
							cc2.DATEEMB AS DATEEMB2,IF(cc2.DATECHANGEDISEMB IS NULL,cc2.DATEDISEMB,cc2.DATECHANGEDISEMB) AS DATEDISEMB2,
							cc2.DATEDISEMB AS EOC2,cc2.RANKCODE AS RANKCODE2,r2.ALIAS1 AS RANKALIAS2,cc.ESTIMATEDATE
							FROM crewchange cc
							LEFT JOIN crew c ON c.APPLICANTNO=cc.APPLICANTNO
							LEFT JOIN rank r ON r.RANKCODE=cc.RANKCODE
							LEFT JOIN crewchangerelation ccr ON ccr.CCID=cc.CCID
							LEFT JOIN crewchange cc2 ON cc2.CCID=ccr.CCIDEMB
							LEFT JOIN crew c2 ON c2.APPLICANTNO=cc2.APPLICANTNO
							LEFT JOIN rank r2 ON r2.RANKCODE=cc2.RANKCODE
							WHERE cc.VESSELCODE='$vslcode' AND cc.BATCHNO=$batchno
							AND (IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) > CURRENT_DATE) AND cc.ARRMNLDATE IS NULL
							ORDER BY cc.DATEDISEMB DESC
			
						") or die(mysql_error());
			
			while ($rowgetbatch = mysql_fetch_array($qrygetbatch))
			{
				//Disembarking Crew
				$rankalias1 = $rowgetbatch["RANKALIAS1"];
				$fname1 = $rowgetbatch["FNAME1"];
				$gname1 = $rowgetbatch["GNAME1"];
				$mname1 = $rowgetbatch["MNAME1"];
				
				$crewname1 = $fname1 . ", " . $gname1 . " " . $mname1;
				
				if (!empty($rowgetbatch["DATEEMB1"]))
					$dateemb1 = date("d-M-y",strtotime($rowgetbatch["DATEEMB1"]));
				else
					$dateemb1 = "---";
				
				if (!empty($rowgetbatch["ESTIMATEDATE"]))
					$estimatedate1 = date("d-M-y",strtotime($rowgetbatch["ESTIMATEDATE"]));
				else
					$estimatedate1 = "---";
					
				if (!empty($rowgetbatch["DATEDISEMB1"]))
					$datedisemb1 = date("d-M-y",strtotime($rowgetbatch["DATEDISEMB1"]));
				else
					$datedisemb1 = "---";
				if (!empty($rowgetbatch["EOC1"]))
					$eoc1 = date("d-M-y",strtotime($rowgetbatch["EOC1"]));
				else
					$eoc1 = "---";
			
				//Embarking Crew
				$appno2 = $rowgetbatch["APPNO2"];
				$rankalias2 = $rowgetbatch["RANKALIAS2"];
				$fname2 = $rowgetbatch["FNAME2"];
				$gname2 = $rowgetbatch["GNAME2"];
				$mname2 = $rowgetbatch["MNAME2"];
				
				if (!empty($fname2))
					$crewname2 = $fname2 . ", " . $gname2 . " " . $mname2;
				else
					$crewname2 = "&nbsp;";
				
				if (!empty($rowgetbatch["DATEEMB2"]))
					$dateemb2 = date("M 'y",strtotime($rowgetbatch["DATEEMB2"]));
				else
					$dateemb2 = "&nbsp;";
				
				if (!empty($rowgetbatch["DATEDISEMB2"]))
					$datedisemb2 = date("d-M-y",strtotime($rowgetbatch["DATEDISEMB2"]));
				else
					$datedisemb2 = "&nbsp;";
				
				if (!empty($rowgetbatch["EOC2"]))
					$eoc2 = date("M 'y",strtotime($rowgetbatch["EOC2"]));
				else
					$eoc2 = "&nbsp;";
					
				if(!empty($appno2))
				{
					$qrylastvessel=mysql_query("
						SELECT VESSEL,DATEDISEMB,ALIAS1 FROM
						(SELECT VESSEL,IF(c.DATECHANGEDISEMB IS NULL,c.DATEDISEMB,c.DATECHANGEDISEMB) AS DATEDISEMB,ALIAS1
						FROM crewchange c
						LEFT JOIN vessel v ON c.VESSELCODE=v.VESSELCODE
						WHERE ARRMNLDATE IS NOT NULL AND c.APPLICANTNO=$appno2
						UNION
						SELECT VESSEL,DATEDISEMB,VESSEL AS ALIAS1
						FROM crewexperience
						WHERE APPLICANTNO=$appno2) x
						ORDER BY DATEDISEMB DESC 
						LIMIT 1") or die(mysql_error());
					if(mysql_num_rows($qrylastvessel)>0)
					{
						$rowlastvessel=mysql_fetch_array($qrylastvessel);
						$lastvessel1=$rowlastvessel["VESSEL"];
						$vesselalias1=$rowlastvessel["ALIAS1"];
					}
					else 
					{
						$lastvessel1="";
						$vesselalias1="";
					}
				}
				else 
				{
					$lastvessel1="";
					$vesselalias1="";
				}
					
					
				echo "
				<tr>
					<td align=\"center\" $styledetail>$rankalias1</td>
					<td $styledetail style=\"font-weight:Bold;\">$crewname1</td>
				<!--	<td align=\"center\" $styledetail>$dateemb1</td>   -->
					<td align=\"center\" $styledetail>$datedisemb1</td>
				<!--	<td align=\"center\" $styledetail>$eoc1</td>   -->
					<td align=\"center\" style=\"background-color:Yellow;font-size:0.7em;font-weight:Bold;color:Blue;border-bottom:1px dashed gray;border-right:1px solid silver;\">$estimatedate1</td>
					<td align=\"center\" $styledetail>&nbsp;$rankalias2</td>
					<td align=\"left\" $styledetail>&nbsp;$crewname2</td>
					<td align=\"center\" $styledetail>&nbsp;$vesselalias1</td>
					<td align=\"center\" $styledetail>&nbsp;$dateemb2</td>
				</tr>
				";
			}
			
	echo "
		</table>
	</div>
";
include("veritas/include/printclose.inc");

echo "
</body>\n
</html>\n";

?>
