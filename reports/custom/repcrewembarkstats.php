<?php

include('veritas/connectdb.php');
//include('connectdb.php');

session_start();

include('../include/stylephp.inc');

$datetimenow=date("Y-m-d H:i:s");
$datenow=date("Y-m-d");
$datenowshow=date("M Y");

$getyear=2007;
$getmonth=9;
//$getyearprev=$getyear-1;

if($getmonth>1)
	$getmonthprev=$getmonth-1;
else 
	$getmonthprev=12;
	
if(strlen($getmonthprev)==1)
	$getmonthprevraw="0".$getmonthprev;
else 
	$getmonthprevraw=$getmonthprev;
	
if(strlen($getmonth)==1)
	$getmonthraw="0".$getmonth;
else 
	$getmonthraw=$getmonth;
	
$getcurrent=$getyear.$getmonthraw;
$getprevmonth=$getyear.$getmonthprevraw;
//$getprevyear=$getyearprev.$getmonthraw;
$getarray=explode(",",$getprevmonth.",".$getcurrent.",".$getyear);

$qryvessel=mysql_query("SELECT GROUP_CONCAT(VESSEL) AS VESSEL FROM vessel WHERE DATE_FORMAT(DATEDELIVERED,'%Y%m')='$getcurrent'") or die(mysql_error());
$rowvessel=mysql_fetch_array($qryvessel);
$vessel=$rowvessel["VESSEL"];

for($i=0;$i<3;$i++)
{
	$placedate=$getarray[$i];
	if($i<2)
		$placemo="%m";
	else 
		$placemo="";
	//GET RANK LIST
	$qryranklist=mysql_query("SELECT RANKCODE FROM rank ORDER BY RANKTYPECODE,RANKING") or die(mysql_error());
	while($rowranklist=mysql_fetch_array($qryranklist))
	{
		$rankcode=$rowranklist["RANKCODE"];
		$qrycurrent=mysql_query("SELECT RANKCODE,CCID,APPLICANTNO
			FROM crewchange
			WHERE DATE_FORMAT(DATEEMB,'%Y$placemo')='$placedate' AND RANKCODE='$rankcode'") or die(mysql_error());
		while($rowcurrent=mysql_fetch_array($qrycurrent))
		{
			$applicantno=$rowcurrent["APPLICANTNO"];
			$ccid=$rowcurrent["CCID"];
			
			//check column placement - NH, RT or EC
			$qryexpvmc=mysql_query("
				SELECT * 
				FROM crewchange 
				WHERE APPLICANTNO=$applicantno AND CCID<>$ccid AND ARRMNLDATE<='$datenow'
				ORDER BY ARRMNLDATE DESC
				LIMIT 1") or die(mysql_error());
			$cntexpvmc=mysql_num_rows($qryexpvmc);
			
			$qryexpothers=mysql_query("
				SELECT * 
				FROM crewexperience
				WHERE APPLICANTNO=$applicantno AND DATEDISEMB<='$datenow'
				ORDER BY DATEDISEMB DESC
				LIMIT 1") or die(mysql_error());
			$cntexpothers=mysql_num_rows($qryexpothers);
			if($cntexpothers==0)
			{
				if($cntexpvmc==0)
				{
					$rank[$rankcode][$i*4+1]++;//col 1,5,9
				}
				else 
				{
					$rank[$rankcode][$i*4+3]++;//col 3,7,11
				}
			}
			else 
			{
				if($cntexpvmc==0)
				{
					$rank[$rankcode][$i*4+1]++;//col 1,5,9
				}
				else 
				{
					$rowexpvmc=mysql_fetch_array($qryexpvmc);
					$vmcdisemb=$rowexpvmc["DATEDISEMB"];
					$rowexpothers=mysql_fetch_array($qryexpothers);
					$othersdisemb=$rowexpothers["DATEDISEMB"];
					if(strtotime($othersdisemb)<strtotime($vmcdisemb))
						$rank[$rankcode][$i*4+3]++;//col 3,7,11
					else 
						$rank[$rankcode][$i*4+2]++;//col 2,6,10
				}
			}
			$rank[$rankcode][$i*4+4]++;//col 4,8,12
		}
	}
}
//CREATE COLUMNS
$colwidth="40px";
$border="border-right:2px solid black;border-bottom:1px solid black;font-size:9pt;font-family:Arial ;font-weight:bold;";
$border1="border-right:1px solid black;border-bottom:1px solid black;font-size:9pt;font-family:Arial ;font-weight:bold;";
$fixedcolumn="
<tr height=\"18px\" style=\"display:none;\">\n
	<td style=\"width:120px;\">&nbsp;</td>\n			<!--  Rank  		-->
	<td style=\"width:$colwidth;\">&nbsp;</td>\n		<!--  PREV NH  		-->
	<td style=\"width:$colwidth;\">&nbsp;</td>\n		<!--  PREV RT  		-->
	<td style=\"width:$colwidth;\">&nbsp;</td>\n		<!--  PREV EC  		-->
	<td style=\"width:$colwidth;\">&nbsp;</td>\n		<!--  PREV TOT  	-->
	<td style=\"width:$colwidth;\">&nbsp;</td>\n		<!--  CURR NH  		-->
	<td style=\"width:$colwidth;\">&nbsp;</td>\n		<!--  CURR RT  		-->
	<td style=\"width:$colwidth;\">&nbsp;</td>\n		<!--  CURR EC  		-->
	<td style=\"width:$colwidth;\">&nbsp;</td>\n		<!--  CURR TOT  	-->
	<td style=\"width:$colwidth;\">&nbsp;</td>\n		<!--  YEAR NH  		-->
	<td style=\"width:$colwidth;\">&nbsp;</td>\n		<!--  YEAR RT  		-->
	<td style=\"width:$colwidth;\">&nbsp;</td>\n		<!--  YEAR EC  		-->
	<td style=\"width:$colwidth;\">&nbsp;</td>\n		<!--  YEAR TOT  	-->
	<td style=\"width:65px;\">&nbsp;</td>\n				<!--  RETENTION  	-->
</tr>
";

	#*******************POST VALUES*************
$qryrank=mysql_query("SELECT RANKCODE,ALIAS1 FROM rank ORDER BY RANKTYPECODE,RANKING") or die(mysql_error());

echo	"<html>\n
<title>Reports - Calling US</title>
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
<div id=\"ajaxprogress\" 
	style=\"background:white;z-index:200;position:absolute;left:620px;top:20px;width:50px;height:50px;\">
<span style=\"font-size:8pt;font-family:Arial;\">FM-236 OCT 94</span>
</div>
<table cellspacing=\"0\" cellpadding=\"0\">
	<tr height=\"27px\">\n
		<td style=\"width:620px;text-align:center;font-size:16pt;font-weight:bold;\">VERITAS MARITIME CORPORATION</td>\n
	</tr>
	<tr height=\"22px\">\n
		<td style=\"width:620px;text-align:center;font-size:12pt;font-weight:bold;\">Crew Embarkation Statistics</td>\n
	</tr>
	<tr height=\"22px\">\n
		<td style=\"width:620px;text-align:center;font-size:12pt;font-weight:bold;\">For the month of $datenowshow</td>\n
	</tr>
</table>
<br>
<table style=\"border-left:2px solid black;border-top:2px solid black;table-layout:fixed;\" cellspacing=\"0\" cellpadding=\"0\">
	$fixedcolumn
	<tr height=\"20px\" style=\"text-align:center;valign:middle;\">\n
		<td rowspan=\"2\" style=\"$border\">RANK</td>\n
		<td colspan=\"4\" style=\"$border\">As of previous</td>\n
		<td colspan=\"4\" style=\"$border\">This month</td>\n
		<td colspan=\"4\" style=\"$border\">Year-to-date</td>\n
		<td rowspan=\"2\" style=\"$border\">% Retention</td>\n
	</tr>
	<tr height=\"20px\" style=\"text-align:center;valign:middle;\">\n
		<td style=\"$border1\">NH</td>\n
		<td style=\"$border1\">RT</td>\n
		<td style=\"$border1\">EC</td>\n
		<td style=\"$border\">TOTAL</td>\n
		<td style=\"$border1\">NH</td>\n
		<td style=\"$border1\">RT</td>\n
		<td style=\"$border1\">EC</td>\n
		<td style=\"$border\">TOTAL</td>\n
		<td style=\"$border1\">NH</td>\n
		<td style=\"$border1\">RT</td>\n
		<td style=\"$border1\">EC</td>\n
		<td style=\"$border\">TOTAL</td>\n
	</tr>
</table>
<table style=\"border-left:2px solid black;border-top:1px solid black;table-layout:fixed;\" cellspacing=\"0\" cellpadding=\"0\">
	$fixedcolumn
	<tr height=\"18px\">\n
		<td style=\"$styledetails;$border;text-align:left;\">&nbsp;</td>\n
		<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;</td>\n
		<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;</td>\n
		<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;</td>\n
		<td style=\"$styledetails;$border;text-align:center;\">&nbsp;</td>\n
		<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;</td>\n
		<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;</td>\n
		<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;</td>\n
		<td style=\"$styledetails;$border;text-align:center;\">&nbsp;</td>\n
		<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;</td>\n
		<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;</td>\n
		<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;</td>\n
		<td style=\"$styledetails;$border;text-align:center;\">&nbsp;</td>\n
		<td style=\"$styledetails;$border;text-align:center;\">&nbsp;</td>\n
	</tr>";
	while($rowrank=mysql_fetch_array($qryrank))
	{
		$rankcode1=$rowrank["RANKCODE"];
		$alias1=$rowrank["ALIAS1"];
		if($rankcode1=="D11" || $rankcode1=="D21" || $rankcode1=="D22" 
			|| $rankcode1=="E11" || $rankcode1=="E21" || $rankcode1=="E22")
			$retention=number_format((($rank[$rankcode1][11]/$rank[$rankcode1][12])*100),2)."%";
		else 
			$retention="";
		echo "	
		<tr height=\"18px\">\n
			<td style=\"$styledetails;$border;text-align:left;\">&nbsp;$alias1</td>\n
			<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;{$rank[$rankcode1][1]}</td>\n
			<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;{$rank[$rankcode1][2]}</td>\n
			<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;{$rank[$rankcode1][3]}</td>\n
			<td style=\"$styledetails;$border;text-align:center;\">&nbsp;{$rank[$rankcode1][4]}</td>\n
			<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;{$rank[$rankcode1][5]}</td>\n
			<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;{$rank[$rankcode1][6]}</td>\n
			<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;{$rank[$rankcode1][7]}</td>\n
			<td style=\"$styledetails;$border;text-align:center;\">&nbsp;{$rank[$rankcode1][8]}</td>\n
			<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;{$rank[$rankcode1][9]}</td>\n
			<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;{$rank[$rankcode1][10]}</td>\n
			<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;{$rank[$rankcode1][11]}</td>\n
			<td style=\"$styledetails;$border;text-align:center;\">&nbsp;{$rank[$rankcode1][12]}</td>\n
			<td style=\"$styledetails;$border;text-align:center;\">&nbsp;$retention</td>\n
		</tr>";
		$col1+=$rank[$rankcode1][1];
		$col2+=$rank[$rankcode1][2];
		$col3+=$rank[$rankcode1][3];
		$col4+=$rank[$rankcode1][4];
		$col5+=$rank[$rankcode1][5];
		$col6+=$rank[$rankcode1][6];
		$col7+=$rank[$rankcode1][7];
		$col8+=$rank[$rankcode1][8];
		$col9+=$rank[$rankcode1][9];
		$col10+=$rank[$rankcode1][10];
		$col11+=$rank[$rankcode1][11];
		$col12+=$rank[$rankcode1][12];
		//COUNT CADETS
		if($rankcode1=="D41" || $rankcode1=="D49" || $rankcode1=="E41" || $rankcode1=="E49" || $rankcode1=="S35")
		{
			$cntcadetec+=$rank[$rankcode1][11];
			$cntcadettot+=$rank[$rankcode1][12];
		}
		//COUNT TOP 6 OFFICERS
		if($rankcode1=="D11" || $rankcode1=="D21" || $rankcode1=="D22" 
			|| $rankcode1=="E11" || $rankcode1=="E21" || $rankcode1=="E22")
		{
			$cnttop6ec+=$rank[$rankcode1][11];
			$cnttop6tot+=$rank[$rankcode1][12];
		}
	}
	$overallret=(number_format(($col11/$col12)*100,2))."%";
	$exclcadetec=$col11-$cntcadetec;
	$exclcadettot=$col12-$cntcadettot;
	$cadetret=(number_format(($exclcadetec/$exclcadettot)*100,2))."%";
	$top6ret=(number_format(($cnttop6ec/$cnttop6tot)*100,2))."%";
	echo "	
	<tr height=\"18px\">\n
		<td colspan=\"14\" style=\"$styledetails;$border;border-bottom:2px solid black;border-top:1px solid black;text-align:center;\">&nbsp;</td>\n
	</tr>
	<tr height=\"18px\">\n
		<td style=\"$styledetails;$border;text-align:left;\">&nbsp;TOTAL</td>\n
		<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;{$col1}</td>\n
		<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;{$col2}</td>\n
		<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;{$col3}</td>\n
		<td style=\"$styledetails;$border;text-align:center;\">&nbsp;{$col4}</td>\n
		<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;{$col5}</td>\n
		<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;{$col6}</td>\n
		<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;{$col7}</td>\n
		<td style=\"$styledetails;$border;text-align:center;\">&nbsp;{$col8}</td>\n
		<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;{$col9}</td>\n
		<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;{$col10}</td>\n
		<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;{$col11}</td>\n
		<td style=\"$styledetails;$border;text-align:center;\">&nbsp;{$col12}</td>\n
		<td style=\"$styledetails;$border;text-align:center;\">&nbsp;$overallret</td>\n
	</tr>
	<tr height=\"18px\">\n
		<td colspan=\"14\" style=\"$styledetails;$border;border-bottom:2px solid black;border-top:1px solid black;text-align:center;\">&nbsp;</td>\n
	</tr>
	<tr height=\"18px\">\n
		<td style=\"$styledetails;$border;text-align:left;\">&nbsp;EXCLUDING CADETS</td>\n
		<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;</td>\n
		<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;</td>\n
		<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;</td>\n
		<td style=\"$styledetails;$border;text-align:center;\">&nbsp;</td>\n
		<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;</td>\n
		<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;</td>\n
		<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;</td>\n
		<td style=\"$styledetails;$border;text-align:center;\">&nbsp;</td>\n
		<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;</td>\n
		<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;</td>\n
		<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;$exclcadetec</td>\n
		<td style=\"$styledetails;$border;text-align:center;\">&nbsp;$exclcadettot</td>\n
		<td style=\"$styledetails;$border;text-align:center;\">&nbsp;$cadetret</td>\n
	</tr>
	<tr height=\"18px\">\n
		<td colspan=\"14\" style=\"$styledetails;$border;border-bottom:2px solid black;border-top:1px solid black;text-align:center;\">&nbsp;</td>\n
	</tr>
	<tr height=\"18px\">\n
		<td style=\"$styledetails;$border;text-align:left;\">&nbsp;Top Six Officers</td>\n
		<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;</td>\n
		<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;</td>\n
		<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;</td>\n
		<td style=\"$styledetails;$border;text-align:center;\">&nbsp;</td>\n
		<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;</td>\n
		<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;</td>\n
		<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;</td>\n
		<td style=\"$styledetails;$border;text-align:center;\">&nbsp;</td>\n
		<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;</td>\n
		<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;</td>\n
		<td style=\"$styledetails;$border1;text-align:center;\">&nbsp;$cnttop6ec</td>\n
		<td style=\"$styledetails;$border;text-align:center;\">&nbsp;$cnttop6tot</td>\n
		<td style=\"$styledetails;$border;text-align:center;\">&nbsp;$top6ret</td>\n
	</tr>
	<tr height=\"18px\">\n
		<td colspan=\"14\" style=\"$styledetails;$border;border-bottom:2px solid black;border-top:1px solid black;text-align:center;\">&nbsp;</td>\n
	</tr>
	<tr height=\"18px\">\n
		<td colspan=\"14\" style=\"$styledetails;$border;text-align:left;\">
			&nbsp;Legend :&nbsp;&nbsp;NH - New Hire, RT - Returnee, EC - Ex-crew
		</td>\n
	</tr>
	<tr height=\"18px\">\n
		<td colspan=\"14\" style=\"$styledetails;$border;border-bottom:2px solid black;text-align:left;\">
			&nbsp;Delivery Vessel for the month : <i>$vessel</i>
		</td>\n
	</tr>
</table>
<br>



</form>";
	
include('../../include/printclose.inc');
echo "
</body>\n
</html>\n";

?>
