<?php

include('veritas/connectdb.php');
//include('connectdb.php');

session_start();

include('../include/stylephp.inc');

$datetimenow=date("Y-m-d H:i:s");
$datenow=date("Y-m-d");
$datenowshow=date("d M Y");

//CREATE COLUMNS
$fixedcolumn="
<tr height=\"18px\" style=\"display:none;\">\n
	<td style=\"width:33px;\">&nbsp;</td>\n		<!--  1  -->
	<td style=\"width:54px;\">&nbsp;</td>\n		<!--  2  -->
	<td style=\"width:201px;\">&nbsp;</td>\n	<!--  3  -->
	<td style=\"width:89px;\">&nbsp;</td>\n		<!--  4  -->
	<td style=\"width:89px;\">&nbsp;</td>\n		<!--  5  -->
	<td style=\"width:131px;\">&nbsp;</td>\n	<!--  6  -->
	<td style=\"width:89px;\">&nbsp;</td>\n		<!--  7  -->
	<td style=\"width:89px;\">&nbsp;</td>\n		<!--  8  -->
	<td style=\"width:89px;\">&nbsp;</td>\n		<!--  9  -->
</tr>
";
$fixedcolumnsumm="
<tr height=\"18px\" style=\"display:none;\">\n
	<td style=\"width:124px;\">&nbsp;</td>\n	<!--  1  -->
	<td style=\"width:47px;\">&nbsp;</td>\n		<!--  2  -->
	<td style=\"width:131px;\">&nbsp;</td>\n	<!--  3  -->
	<td style=\"width:47px;\">&nbsp;</td>\n		<!--  4  -->
</tr>
";

	#*******************POST VALUES*************
$qryscholar=mysql_query("SELECT CONCAT(FNAME,', ',GNAME) AS NAME,cs.APPLICANTNO,m.ALIAS AS SCHOOL,c.RANKTYPECODE,
	LEFT(YEARGRADUATE,4) AS GRADUATE
	FROM crewscholar cs
	LEFT JOIN crew c ON cs.APPLICANTNO=c.APPLICANTNO
	LEFT JOIN maritimeschool m ON cs.SCHOOLID=m.SCHOOLID
	WHERE EXPELLEDDATE IS NULL AND SCHOLASTICCODE IN ('VS','IS')") or die(mysql_error());
$cntdata=0;
while($rowscholar=mysql_fetch_array($qryscholar))
{
	$applicantno=$rowscholar["APPLICANTNO"];
	$name=$rowscholar["NAME"];
	$school=$rowscholar["SCHOOL"];
	$ranktypecode=$rowscholar["RANKTYPECODE"];
	$ranktype="";
	if($ranktypecode=="D")
		$ranktypeprefix="Deck";
	elseif($ranktypecode=="E")
		$ranktypeprefix="Engine";
	else 
		$ranktypeprefix="Unknown";
	$ranktype=$ranktypeprefix." Scholars";
	$yeargraduate=$rowscholar["GRADUATE"];
	//get latest embarkation
	$qrylastembark=mysql_query("SELECT c.VESSELCODE,v.VESSEL,c.DATEEMB,c.DATEDISEMB,c.RANKCODE,r.ALIAS2,r.RANKING,r.RANK
		FROM crewchange c
		LEFT JOIN vessel v ON c.VESSELCODE=v.VESSELCODE
		LEFT JOIN rank r ON c.RANKCODE=r.RANKCODE
		WHERE APPLICANTNO=$applicantno
		ORDER BY DATEDISEMB DESC
		LIMIT 1") or die(mysql_error());
	if(mysql_num_rows($qrylastembark)==0)
	{
		$vessel="";
		$dateemb="";
		$datedisemb="";
		$lastrank="";
		$lastranking="";
	}
	else 
	{
		$rowlastembark=mysql_fetch_array($qrylastembark);
		$vessel=$rowlastembark["VESSEL"];
		$dateemb=$rowlastembark["DATEEMB"];
		$datedisemb=$rowlastembark["DATEDISEMB"];
		$lastrank=$rowlastembark["ALIAS2"];
		$lastrank1=$rowlastembark["RANK"];
		$lastranking=$rowlastembark["RANKING"];
	}
	// 
	if($lastrank=="DCA" || $lastrank=="DCB")
	{
		$licenserank="Deck Cadets";
		$licenserank1=$licenserank;
		$licenseranking=999;
	}
	elseif($lastrank=="ECA" || $lastrank=="ECB")
	{
		$licenserank="Engine Cadets";
		$licenserank1=$licenserank;
		$licenseranking=999;
	}
	else 
	{
		//get philippine license with date of issue
		$qrylicense=mysql_query("SELECT r.RANKFULL,r.RANKING
			FROM crewdocstatus c
			LEFT JOIN rank r ON c.RANKCODE=r.RANKCODE
			WHERE APPLICANTNO=$applicantno AND DOCCODE='F1'
			ORDER BY DATEISSUED DESC
			LIMIT 1") or die(mysql_error());
		if(mysql_num_rows($qrylicense)==0)
		{
			$licenserank=$ranktypeprefix." Ratings";
			$licenserank1=$licenserank;
			$licenseranking=998;
		}
		else 
		{
			$rowlicense=mysql_fetch_array($qrylicense);
			$licenserank="Licensed ".$rowlicense["RANKFULL"];
			$licenserank1=$rowlicense["RANKFULL"];
			$licenseranking=$rowlicense["RANKING"];
		}
	}
	
	if($datedisemb>=$datenow)
		$rem="On board";
	else 
		$rem="On vacation";
	
	$col1[$cntdata]=$lastrank;
	$col2[$cntdata]=$name;
	$col3[$cntdata]=$school;
	$col4[$cntdata]=$yeargraduate;
	$col5[$cntdata]=$vessel;
	$col6[$cntdata]=$dateemb;
	$col7[$cntdata]=$datedisemb;
	$col8[$cntdata]=$rem;
	$oth1[$cntdata]=$lastranking;
	$oth2[$cntdata]=$licenseranking;
	$oth3[$cntdata]=$licenserank;
	$oth4[$cntdata]=$ranktype;
	$oth5[$cntdata]=$applicantno;
	$oth6[$cntdata]=$ranktypecode;
	$cntdata++;
	
	//$ranktype,$licenseranking,$lastranking
	
	//CREATE SUMMARY
	if($rem=="On vacation")//for ORDER BY in summary
		$lastrankingsumm=$lastranking.".2";
	elseif($rem=="On board")
		$lastrankingsumm=$lastranking.".0";
	else 
		$lastrankingsumm=$lastranking.".4";
	$colname=$ranktype."_".$licenseranking."_".$lastrankingsumm."_".$licenserank1."_".$lastrank."_".$rem;
	$summ1[$colname]+=1;
	$licenserank1tot[$licenserank1]+=1;
}
//CREATE SUMMARY ARRAY
$getsummkeys=array_keys($summ1);
$combinesumm=array("desc" => $getsummkeys,"value" => $summ1);
$summlength=count($summ1);
array_multisort($combinesumm["desc"],$combinesumm["value"]);
//print_r($combinesumm);

$stuff = array(	"col1" => $col1,
				"col2" => $col2,
				"col3" => $col3,
				"col4" => $col4,
				"col5" => $col5,
				"col6" => $col6,
				"col7" => $col7,
				"col8" => $col8,
				"oth1" => $oth1,
				"oth2" => $oth2,
				"oth3" => $oth3,
				"oth4" => $oth4,
				"oth5" => $oth5,
				"oth6" => $oth6
			);

array_multisort($stuff["oth4"],$stuff["oth2"],$stuff["oth1"],
			$stuff["col1"],
			$stuff["col2"],
			$stuff["col3"],
			$stuff["col4"],
			$stuff["col5"],
			$stuff["col6"],
			$stuff["col7"],
			$stuff["col8"],
			$stuff["oth1"],
			$stuff["oth2"],
			$stuff["oth3"],
			$stuff["oth4"],
			$stuff["oth5"],
			$stuff["oth6"]
			);

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

<form name=\"scholarfasttrack\" id=\"scholarfasttrack\" method=\"POST\">\n
<table>
	<tr height=\"27px\">\n
		<td style=\"width:896px;text-align:center;font-size:16pt;font-weight:bold;\">VERITAS MARITIME CORPORATION</td>\n
	</tr>
	<tr height=\"27px\">\n
		<td style=\"width:896px;text-align:center;font-size:16pt;font-weight:bold;\">List of Deck Scholars (According to License) as of $datenowshow</td>\n
	</tr>
</table>
<table style=\"border:2px solid black;table-layout:fixed;\" cellspacing=\"0\" cellpadding=\"0\">
	$fixedcolumn
	<tr height=\"35px\" style=\"text-align:center;valign:middle;\">\n
		<td style=\"border-right:1px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;\">&nbsp;</td>\n
		<td style=\"border-right:1px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;\">RANK</td>\n
		<td style=\"border-right:1px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;\"\">NAME</td>\n
		<td style=\"border-right:1px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;\">SCHOOL</td>\n
		<td style=\"border-right:1px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;\">YEAR GRAD</td>\n
		<td style=\"border-right:1px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;\">VESSEL</td>\n
		<td style=\"border-right:1px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;\">EMBARK</td>\n
		<td style=\"border-right:1px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;\">END OF CONTRACT</td>\n
		<td style=\"font-size:9pt;font-family:Arial Narrow;font-weight:bold;\">REMARKS</td>\n
	</tr>
</table>
<br>
<table style=\"border-top:1px solid black;border-right:1px solid black;table-layout:fixed;\" cellspacing=\"0\" cellpadding=\"0\">
	$fixedcolumn";
	$licenseranktemp="";
	$typetemp="";
	$cntrowtype=1;
	for($i=0;$i<$cntdata;$i++)
	{
		$lastrank=$stuff["col1"][$i];
		$name=$stuff["col2"][$i];
		$school=$stuff["col3"][$i];
		$yeargraduate=$stuff["col4"][$i];
		$vessel=$stuff["col5"][$i];
		$dateemb=$stuff["col6"][$i];
		$dateembshow=date("d-M-y",strtotime($dateemb));
		$datedisemb=$stuff["col7"][$i];
		$datedisembshow=date("d-M-y",strtotime($datedisemb));
		$rem=$stuff["col8"][$i];
		$lastranking=$stuff["oth1"][$i];
		$licenseranking=$stuff["oth2"][$i];
		$licenserank=$stuff["oth3"][$i];
		$ranktype=$stuff["oth4"][$i];
		$applicantno=$stuff["oth5"][$i];
		$ranktypecode=$stuff["oth6"][$i];
		if($typetemp=="" || $typetemp!=$ranktype)
		{
			if(!empty($typetemp))
				echo "
				<tr height=\"18px\">\n
					<td colspan=\"9\" style=\"$styledetails\">&nbsp;</td>\n
				</tr>
				";
			echo "
			<tr height=\"24px\" style=\"text-align:center;valign:middle;\">\n
				<td colspan=\"9\" style=\"$styledetails font-size:14pt;font-weight:bold;\">$ranktype</td>\n
			</tr>
			";
			$cntrowtype=1;
		}
		if($licenseranktemp=="" || $licenseranktemp!=$licenserank)
		{
			if(!empty($licenseranktemp))
				echo "
				<tr height=\"18px\">\n
					<td colspan=\"9\" style=\"$styledetails\">&nbsp;</td>\n
				</tr>
				";
			echo "
			<tr height=\"24px\" style=\"\">\n
				<td colspan=\"9\" style=\"$styledetails font-size:12pt;font-weight:bold;\"><u>$licenserank</u></td>\n
			</tr>
			";
			$cntrowtype=1;
		}
		echo "
		<tr height=\"18px\" style=\"text-align:center;valign:middle;\">\n
			<td style=\"$styledetails font-family:Arial Narrow;\">$cntrowtype</td>\n
			<td style=\"$styledetails font-family:Arial Narrow;\">$lastrank</td>\n
			<td style=\"$styledetails font-family:Arial Narrow;text-align:left;\" title=\"$applicantno\">&nbsp;&nbsp;$name</td>\n
			<td style=\"$styledetails font-family:Arial Narrow;\">&nbsp;$school</td>\n
			<td style=\"$styledetails font-family:Arial Narrow;\">&nbsp;$yeargraduate</td>\n
			<td style=\"$styledetails font-family:Arial Narrow;\">&nbsp;$vessel</td>\n
			<td style=\"$styledetails font-family:Arial Narrow;\">$dateembshow</td>\n
			<td style=\"$styledetails font-family:Arial Narrow;\">$datedisembshow</td>\n
			<td style=\"$styledetails font-family:Arial Narrow;\">$rem</td>\n
		</tr>";
		$licenseranktemp=$licenserank;
		$typetemp=$ranktype;
		$cntrowtype++;
		
	}
	
echo "
</table>
<br><br>
<table>
	<tr height=\"27px\">\n
		<td style=\"width:896px;text-align:center;font-size:16pt;font-weight:bold;\">VERITAS MARITIME CORPORATION</td>\n
	</tr>
	<tr height=\"27px\">\n
		<td style=\"width:896px;text-align:center;font-size:16pt;font-weight:bold;\">Statistical Summary of Scholars as of $datenowshow</td>\n
	</tr>
</table>
<table style=\"border-top:1px solid black;border-right:1px solid black;table-layout:fixed;\" cellspacing=\"0\" cellpadding=\"0\">
	$fixedcolumnsumm";
	$getlicenseranktemp="";
	$getheadingtemp="";
	$cntrowtype=1;
	$sumheading=0;
	//$colname=$ranktype."_".$licenseranking."_".$lastrankingsumm."_".$licenserank1."_".$lastrank."_".$rem;
	for($i=0;$i<$summlength;$i++)
	{
		$desc=$combinesumm["desc"][$i];
		$descarray=explode("_",$desc);
		$getcount=$combinesumm["value"][$desc];
		$getheading=$descarray[0];
		$getlicenserank=$descarray[3];
		$getdetail=$descarray[5]." as ".$descarray[4];
		$totperrank=$licenserank1tot[$getlicenserank];
		
		if($getheadingtemp!=$getheading)
		{
			if($getheadingtemp!="")
				echo "
				<tr height=\"18px\" style=\"text-align:center;valign:middle;\">\n
					<td style=\"$styledetails font-family:Arial Narrow;\">TOTAL</td>\n
					<td style=\"$styledetails font-family:Arial Narrow;\">$sumheading</td>\n
					<td style=\"$styledetails font-family:Arial Narrow;\">&nbsp;</td>\n
					<td style=\"$styledetails font-family:Arial Narrow;\">&nbsp;</td>\n
				</tr>";
			echo "
			<tr height=\"24px\" style=\"\">\n
				<td colspan=\"4\" style=\"$styledetails text-align:center;font-size:12pt;font-weight:bold;\">$getheading</td>\n
			</tr>";
			$sumheading=0;
		}
		if($getlicenseranktemp==$getlicenserank)
		{
			$getlicenserankshow="&nbsp;";
			$totperrankshow="&nbsp;";
		}
		else 
		{
			$getlicenserankshow=$getlicenserank;
			$totperrankshow=$totperrank;
			$sumheading+=$totperrank;
		}
		echo "
		<tr height=\"18px\" style=\"text-align:center;valign:middle;\">\n
			<td style=\"$styledetails font-family:Arial Narrow;\">$getlicenserankshow</td>\n
			<td style=\"$styledetails font-family:Arial Narrow;\">$totperrankshow</td>\n
			<td style=\"$styledetails font-family:Arial Narrow;\">$getdetail</td>\n
			<td style=\"$styledetails font-family:Arial Narrow;\">$getcount</td>\n
		</tr>";
		$getlicenseranktemp=$getlicenserank;
		$getheadingtemp=$getheading;
	};
	//get last total
echo "
	<tr height=\"18px\" style=\"text-align:center;valign:middle;\">\n
		<td style=\"$styledetails font-family:Arial Narrow;\">TOTAL</td>\n
		<td style=\"$styledetails font-family:Arial Narrow;\">$sumheading</td>\n
		<td style=\"$styledetails font-family:Arial Narrow;\">&nbsp;</td>\n
		<td style=\"$styledetails font-family:Arial Narrow;\">&nbsp;</td>\n
	</tr>";
echo "
</table>
</form>";


include('../include/printclose.inc');
echo "
</body>\n
</html>\n";

?>
