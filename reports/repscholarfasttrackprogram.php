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
	<td style=\"width:54px;\">&nbsp;</td>\n		<!--  1  -->
	<td style=\"width:180px;\">&nbsp;</td>\n	<!--  2  -->
	<td style=\"width:180px;\">&nbsp;</td>\n	<!--  3  -->
	<td style=\"width:180px;\">&nbsp;</td>\n	<!--  4  -->
	<td style=\"width:180px;\">&nbsp;</td>\n	<!--  5  -->
	<td style=\"width:54px;\">&nbsp;</td>\n		<!--  6  -->
	<td style=\"width:54px;\">&nbsp;</td>\n		<!--  7  -->
	<td style=\"width:54px;\">&nbsp;</td>\n		<!--  8  -->
</tr>
";
$header="
<table cellspacing=\"0\" cellpadding=\"0\" style=\"table-layout:fixed;border-left:2px solid black;border-bottom:2px solid black;\">
$fixedcolumn
	<tr height=\"36px\" style=\"text-align:center;valign:middle;\">\n
		<td style=\"border-right:2px solid black;border-top:2px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;\">&nbsp;</td>\n
		<td colspan=\"2\" style=\"font-size:9pt;font-family:Arial Narrow;font-weight:bold;\">
			<table cellspacing=\"0\" cellpadding=\"0\">
				<tr>
					<td colspan=\"2\" style=\"border-right:2px solid black;border-top:2px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;text-align:center;\">SCHOLARS</td>
				</tr>
				<tr>
					<td style=\"border-right:2px solid black;border-top:2px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;width:180px;text-align:center;\">DECK</td>
					<td style=\"border-right:2px solid black;border-top:2px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;width:180px;text-align:center;\">ENGINE</td>
				</tr>
			</table>
		</td>\n
		<td colspan=\"2\" style=\"font-size:9pt;font-family:Arial Narrow;font-weight:bold;\">
			<table cellspacing=\"0\" cellpadding=\"0\">
				<tr>
					<td colspan=\"2\" style=\"border-right:2px solid black;border-top:2px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;text-align:center;\">FAST TRACKS</td>
				</tr>
				<tr>
					<td style=\"border-right:2px solid black;border-top:2px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;width:180px;text-align:center;\">DECK</td>
					<td style=\"border-right:2px solid black;border-top:2px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;width:180px;text-align:center;\">ENGINE</td>
				</tr>
			</table>
		</td>\n
		<td colspan=\"2\" style=\"font-size:9pt;font-family:Arial Narrow;font-weight:bold;\">
			<table cellspacing=\"0\" cellpadding=\"0\">
				<tr>
					<td colspan=\"2\" style=\"border-right:2px solid black;border-top:2px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;text-align:center;\">TOTAL</td>
				</tr>
				<tr>
					<td style=\"border-right:2px solid black;border-top:2px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;width:54px;text-align:center;\">DECK</td>
					<td style=\"border-right:2px solid black;border-top:2px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;width:54px;text-align:center;\">ENGINE</td>
				</tr>
			</table>
		</td>\n
		<td style=\"border-right:2px solid black;border-top:2px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;\">TOTAL</td>\n
	</tr>
</table>";
$mainheader="
<table cellspacing=\"0\" cellpadding=\"0\">
	<tr height=\"20px\">\n
		<td style=\"width:896px;text-align:center;font-size:16pt;font-weight:bold;\">VERITAS MARITIME CORPORATION</td>\n
	</tr>
	<tr height=\"25px\">\n
		<td style=\"width:896px;text-align:center;font-size:16pt;font-weight:bold;\">Training Department</td>\n
	</tr>
	<tr height=\"25px\">\n
		<td style=\"width:896px;text-align:center;font-size:16pt;font-weight:bold;\">SUMMARY OF SCHOLAR / FAST TRACK CADETSHIP PROGRAM</td>\n
	</tr>
</table>";
	#*******************POST VALUES*************
$qryscft=mysql_query("SELECT * FROM 
	(SELECT cs.APPLICANTNO,LEFT(YEARGRADUATE,4) AS GRAD,
	CONCAT(LEFT(YEARGRADUATE,4),'S',c.RANKTYPECODE) AS COLTYPE,CONCAT(c.FNAME,', ',c.GNAME) AS NAME,cs.SCHOOL,cs.EXPELLEDDATE
	FROM crewscholar cs
	LEFT JOIN crew c ON cs.APPLICANTNO=c.APPLICANTNO
	WHERE YEARGRADUATE IS NOT NULL AND c.RANKTYPECODE IS NOT NULL
	UNION
	SELECT cs.APPLICANTNO,LEFT(YEARGRADUATE,4) AS GRAD,
	CONCAT(LEFT(YEARGRADUATE,4),'F',c.RANKTYPECODE) AS COLTYPE,CONCAT(c.FNAME,', ',c.GNAME) AS NAME,cs.SCHOOL,cs.EXPELLEDDATE
	FROM crewfasttrack cs
	LEFT JOIN crew c ON cs.APPLICANTNO=c.APPLICANTNO
	WHERE YEARGRADUATE IS NOT NULL AND c.RANKTYPECODE IS NOT NULL) x
	ORDER BY COLTYPE") or die(mysql_error());
$coltypetemp="";
$gradtemp="";
$cntrow=0;
$cntdelrow=0;
while($rowscft=mysql_fetch_array($qryscft))
{
	$grad=$rowscft["GRAD"];
	$coltype=$rowscft["COLTYPE"];
	$name=$rowscft["NAME"];
	$school=$rowscft["SCHOOL"];
	$expelleddate=$rowscft["EXPELLEDDATE"];
	$applicantno=$rowscft["APPLICANTNO"];
	if($grad!=$gradtemp)
	{
		$cntdelrow=0;
	}
	if($coltype!=$coltypetemp)
	{
		$cntrow=0;
	}
	if(empty($expelleddate))
	{
		$summarray[$coltype][$cntrow]=$name." (".$school.")";
		$cntrow++;
	}
	else 
	{
		$summarray[$grad."del"][$cntdelrow]=$name." (".$school.")";
		$cntdelrow++;
	}
	$coltypetemp=$coltype;
	$gradtemp=$grad;
}
//print_r($summarray["1991SD"][1]);
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
	$begyear=1991;
	$endyear=2010;
	echo $mainheader;
	for($j=$begyear;$j<=$endyear;$j++)
	{
		echo "
		$header
		<table style=\"border-left:2px solid black;border-bottom:1px solid black;table-layout:fixed;\" cellspacing=\"0\" cellpadding=\"0\">
			$fixedcolumn
			";
		$highcnt=count($summarray[$j."SD"]);
		if(count($summarray[$j."SE"])>$highcnt)
			$highcnt=count($summarray[$j."SE"]);
		if(count($summarray[$j."FD"])>$highcnt)
			$highcnt=count($summarray[$j."FD"]);
		if(count($summarray[$j."FE"])>$highcnt)
			$highcnt=count($summarray[$j."FE"]);
		$delcnt=count($summarray[$j."del"]);
	//	echo $highcnt;
//		echo "
//		<tr height=\"18px\" style=\"text-align:center;valign:middle;\">\n
//			<td colspan=\"8\" style=\"border-right:2px solid black;border-top:2px solid black;border-bottom:1px solid black;font-family:Arial Narrow;\">&nbsp;</td>\n
//		</tr>";
		for($i=0;$i<$highcnt;$i++)
		{
			if($summarray[$j."SD"])
				sort($summarray[$j."SD"]);
			if($summarray[$j."SE"])
				sort($summarray[$j."SE"]);
			if($summarray[$j."FD"])
				sort($summarray[$j."FD"]);
			if($summarray[$j."FE"])
				sort($summarray[$j."FE"]);
			$col2=$summarray[$j."SD"][$i];
			$col3=$summarray[$j."SE"][$i];
			$col4=$summarray[$j."FD"][$i];
			$col5=$summarray[$j."FE"][$i];
			
			if($i==0)
				$showyear=$j;
			else 
				$showyear="";
			echo "
			<tr height=\"18px\" style=\"text-align:center;valign:middle;\">\n
				<td style=\"border-right:2px solid black;border-bottom:1px solid black;font-size:12px;font-family:Arial;font-weight:bold;\">&nbsp;$showyear</td>\n
				<td style=\"border-right:2px solid black;border-bottom:1px solid black;font-size:10px;font-family:Arial Narrow;\">&nbsp;$col2</td>\n
				<td style=\"border-right:2px solid black;border-bottom:1px solid black;font-size:10px;font-family:Arial Narrow;\">&nbsp;$col3</td>\n
				<td style=\"border-right:2px solid black;border-bottom:1px solid black;font-size:10px;font-family:Arial Narrow;\">&nbsp;$col4</td>\n
				<td style=\"border-right:2px solid black;border-bottom:1px solid black;font-size:10px;font-family:Arial Narrow;\">&nbsp;$col5</td>\n
				<td style=\"border-right:2px solid black;border-bottom:1px solid black;font-size:10px;font-family:Arial Narrow;\">&nbsp;$cntrowtype</td>\n
				<td style=\"border-right:2px solid black;border-bottom:1px solid black;font-size:10px;font-family:Arial Narrow;\">&nbsp;$cntrowtype</td>\n
				<td style=\"border-right:2px solid black;border-bottom:1px solid black;font-size:10px;font-family:Arial Narrow;\">&nbsp;$cntrowtype</td>\n
			</tr>";
		}
		if($delcnt!=0)
		{
			for($k=0;$k<$delcnt;$k++)
			{
				$delcol=$summarray[$j."del"][$k];
				echo "
				<tr height=\"18px\" style=\"text-align:center;valign:middle;\">\n
					<td style=\"border-right:2px solid black;border-bottom:1px solid black;font-family:Arial Narrow;\">&nbsp;</td>\n
					<td colspan=\"4\" style=\"border-right:2px solid black;border-bottom:1px solid black;font-size:10px;font-family:Arial;color:red;text-align:left;\">&nbsp;$delcol is deleted</td>\n
					<td style=\"border-right:2px solid black;border-bottom:1px solid black;font-size:10px;font-family:Arial Narrow;\">&nbsp;</td>\n
					<td style=\"border-right:2px solid black;border-bottom:1px solid black;font-size:10px;font-family:Arial Narrow;\">&nbsp;</td>\n
					<td style=\"border-right:2px solid black;border-bottom:1px solid black;font-size:10px;font-family:Arial Narrow;\">&nbsp;</td>\n
				</tr>";
			}
		}
		$cntcol2=count($summarray[$j."SD"]);
		$cntcol3=count($summarray[$j."SE"]);
		$cntcol4=count($summarray[$j."FD"]);
		$cntcol5=count($summarray[$j."FE"]);
		$totcol6=$cntcol2+$cntcol4;
		$totcol7=$cntcol3+$cntcol5;
		$totcol8=$totcol6+$totcol7;
		echo "
			<tr height=\"18px\" style=\"text-align:center;valign:middle;\">\n
				<td style=\"border-top:1px solid black;border-right:2px solid black;border-bottom:1px solid black;font-size:12px;font-family:Arial;font-weight:bold;\">TOTAL</td>\n
				<td style=\"border-top:1px solid black;border-right:2px solid black;border-bottom:1px solid black;font-size:12px;font-family:Arial;font-weight:bold;\">&nbsp;$cntcol2</td>\n
				<td style=\"border-top:1px solid black;border-right:2px solid black;border-bottom:1px solid black;font-size:12px;font-family:Arial;font-weight:bold;\">&nbsp;$cntcol3</td>\n
				<td style=\"border-top:1px solid black;border-right:2px solid black;border-bottom:1px solid black;font-size:12px;font-family:Arial;font-weight:bold;\">&nbsp;$cntcol4</td>\n
				<td style=\"border-top:1px solid black;border-right:2px solid black;border-bottom:1px solid black;font-size:12px;font-family:Arial;font-weight:bold;\">&nbsp;$cntcol5</td>\n
				<td style=\"border-top:1px solid black;border-right:2px solid black;border-bottom:1px solid black;font-size:12px;font-family:Arial;font-weight:bold;\">&nbsp;$totcol6</td>\n
				<td style=\"border-top:1px solid black;border-right:2px solid black;border-bottom:1px solid black;font-size:12px;font-family:Arial;font-weight:bold;\">&nbsp;$totcol7</td>\n
				<td style=\"border-top:1px solid black;border-right:2px solid black;border-bottom:1px solid black;font-size:12px;font-family:Arial;font-weight:bold;\">&nbsp;$totcol8</td>\n
			</tr>";
		echo "</table><br>";
		$totsd+=$cntcol2;
		$totse+=$cntcol3;
		$totfd+=$cntcol4;
		$totfe+=$cntcol5;
	}
	$gtot=$totsd+$totse+$totfd+$totfe;
//	print_r($summarray["del"][0]);
//	print_r($summarray["del"][1]);
echo "
<table style=\"border-top:1px solid black;border-right:1px solid black;\" cellspacing=\"0\" cellpadding=\"0\">
	<tr height=\"25px\">
		<td width=\"450px\" style=\"border-bottom:1px solid black;border-left:1px solid black;font-size:16px;font-family:Arial;font-weight:bold;\">&nbsp;TOTAL NO. OF DECK SCHOLARS&nbsp;</td>
		<td width=\"70px\" style=\"border-bottom:1px solid black;border-left:1px solid black;font-size:16px;font-family:Arial;font-weight:bold;text-align:center;\">$totsd</td>
	</tr>
	<tr height=\"25px\">
		<td style=\"border-bottom:1px solid black;border-left:1px solid black;font-size:16px;font-family:Arial;font-weight:bold;\">&nbsp;TOTAL NO. OF DECK FAST TRACKS&nbsp;</td>
		<td style=\"border-bottom:1px solid black;border-left:1px solid black;font-size:16px;font-family:Arial;font-weight:bold;text-align:center;\">$totse</td>
	</tr>
	<tr height=\"25px\">
		<td style=\"border-bottom:1px solid black;border-left:1px solid black;font-size:16px;font-family:Arial;font-weight:bold;\">&nbsp;TOTAL NO. OF ENGINE SCHOLARS&nbsp;</td>
		<td style=\"border-bottom:1px solid black;border-left:1px solid black;font-size:16px;font-family:Arial;font-weight:bold;text-align:center;\">$totfd</td>
	</tr>
	<tr height=\"25px\">
		<td style=\"border-bottom:1px solid black;border-left:1px solid black;font-size:16px;font-family:Arial;font-weight:bold;\">&nbsp;TOTAL NO. OF ENGINE FAST TRACKS&nbsp;</td>
		<td style=\"border-bottom:1px solid black;border-left:1px solid black;font-size:16px;font-family:Arial;font-weight:bold;text-align:center;\">$totfe</td>
	</tr>
	<tr height=\"35px\">
		<td style=\"border-bottom:1px solid black;border-left:1px solid black;font-size:18px;font-family:Arial;font-weight:bold;\">&nbsp;TOTAL NO. OF VMC SCHOLARS / FAST TRACKS&nbsp;</td>
		<td style=\"border-bottom:1px solid black;border-left:1px solid black;font-size:18px;font-family:Arial;font-weight:bold;text-align:center;\">$gtot</td>
	</tr>
</table>
</form>




</body>\n
</html>\n";

?>
