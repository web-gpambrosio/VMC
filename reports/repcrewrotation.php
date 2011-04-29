<?php

include('veritas/connectdb.php');
//include('connectdb.php');

session_start();

$styleheader="font-size:10px;font-weight:bold;border-left:1px solid black;border-bottom:1px solid black;text-align:center;";
$styledetails="font-size:10px;border-left:1px solid black;border-bottom:1px solid black;";

$datetimenow=date("Y-m-d H:i:s");
$datenow=date("Y-m-d");
$datenowshow=date("m/d/Y");
$totalrows=25;
$monthcolcnt=30;

if(isset($_GET['divcode']))
	$divcode=$_GET['divcode'];

if(empty($divcode))
{
	if(isset($_SESSION['divcode']))
		$divcode=$_SESSION['divcode'];
}
	
//if(empty($divcode))
//	$divcode=2; //temp data
//$datetimenow="2007-01-01";//TEMP DATA

$montharray=explode(",","JAN,FEB,MAR,APR,MAY,JUN,JUL,AUG,SEP,OCT,NOV,DEC");
$bgcolor=explode(",","#999AFF,#FFFF99,#CC99FF,#9BFF99,#FF99CC");
$startyear=date("Y",strtotime($datetimenow));
$startmonth=date("n",strtotime($datetimenow))-1;

//GET HEADER
for($mo=1;$mo<=$monthcolcnt;$mo++)
{
	$getcolumnmonth .="<td style=\"width:25px;$styledetails\">&nbsp;</td>\n";
}

$setcolumns="
		<td style=\"width:25px;$styledetails\">&nbsp;</td>\n
		<td style=\"width:140px;$styledetails\">&nbsp;</td>\n".$getcolumnmonth;
$getheader = "
<table style=\"border-top:1px solid black;border-right:1px solid black;table-layout:fixed;\" cellspacing=\"0\" cellpadding=\"0\">
	<tr height=\"15px\" style=\"display:none;\">\n
	$setcolumns
	</tr>\n
	<tr height=\"15px\" style=\"\">\n
		<td style=\"$styleheader\" colspan=\"32\">VERITAS MARITIME CORPORATION</td>\n
	</tr>\n
	<tr height=\"15px\" style=\"\">\n
		<td style=\"$styleheader\" colspan=\"32\">ROTATION PROGRAM DIV-$divcode</td>\n
	</tr>\n
	<tr height=\"15px\" style=\"\">\n
		<td style=\"$styleheader\" colspan=\"32\">AS OF $datetimenow</td>\n
	</tr>\n";
	//GET YEAR HEADER
	$getyearheader = "
	<tr height=\"15px\" style=\"\">\n
		<td style=\"$styledetails\" colspan=\"2\">&nbsp;</td>";
	$yearspan1=12-$startmonth;
	$placeyear1=$startyear;
	$getyearheader .= "
	<td style=\"$styleheader\" colspan=\"$yearspan1\">$placeyear1</td>\n"; //1st year
	$placeyear2=$placeyear1+1;
	$getyearheader .= "
	<td style=\"$styleheader\" colspan=\"12\">$placeyear2</td>\n"; //2nd year whole
	$yearspan2tmp=$monthcolcnt-12-$yearspan1;
	if($yearspan2tmp>12)
		$yearspan2=12;
	else 
		$yearspan2=$yearspan2tmp;
	$placeyear3=$placeyear2+1;
	$getyearheader .= "
	<td style=\"$styleheader\" colspan=\"$yearspan2\">$placeyear3</td>\n"; //3rd year
//	echo 12 ."+".$yearspan1."+".$yearspan2;
	if(12+$yearspan1+$yearspan2<$monthcolcnt)
	{
		$yearspan3=$monthcolcnt-(12+$yearspan1+$yearspan2);
		$placeyear4=$placeyear3+1;
		$getyearheader .= "
		<td style=\"$styleheader\" colspan=\"$yearspan3\">$placeyear4</td>\n"; //4th year if any
//		echo "YYY";
	}
	$getyearheader .= "</tr>\n";
	
	//GET MONTH HEADER
	$getmonthheader = "
	<tr height=\"15px\" style=\"\">\n
		<td style=\"$styledetails\" colspan=\"2\">&nbsp;</td>";
	$monthno=$startmonth;
	$ccc=0;
	for($m=$startmonth;$m<$startmonth+$monthcolcnt;$m++)
	{
		if($m==12 || $m==24 || $m==36)
			$monthno=0;
		$placemonth=$montharray[$monthno];
		$getmonthheader .= "
		<td style=\"$styleheader\">$placemonth</td>\n";
		$monthno++;
	}
	$getmonthheader .= "</tr>\n";


$qrygetvesselcode=mysql_query("SELECT VESSELCODE,VESSEL 
	FROM vessel v WHERE DIVCODE=$divcode AND STATUS=1
	ORDER BY VESSEL") or die(mysql_error());
$cntgetvesselcode=mysql_num_rows($qrygetvesselcode);

echo	"<html>\n
<head>\n
<link rel=\"StyleSheet\" type=\"text/css\" href=\"../veritas/veripro.css\" />
<script type='text/javascript' src='../veritas/veripro.js'></script>
<script type='text/javascript' src='../veritas/crewchangeplanajax.js'></script>
</head>
<body>
<form>";

	//PLACE HEADER
	echo $getheader;
	echo $getyearheader;
	echo $getmonthheader;
	echo "
	<tr height=\"20px\" style=\"\">\n
		<td style=\"$styleheader font-size:12pt;\" colspan=\"32\"><i>Master Mariner</i></td>\n
	</tr>\n";
	$cntdata=0;
	$cntrow=1;
	while ($rowgetvesselcode=mysql_fetch_array($qrygetvesselcode)) 
	{
		$cntitem=$cntdata+1;
		$vesselcode=$rowgetvesselcode["VESSELCODE"];
		$vessel=$rowgetvesselcode["VESSEL"];
		echo "
		<tr height=\"15px\">\n
			<td style=\"$styleheader\">$cntitem</td>\n
			<td style=\"$styleheader\">$vessel</td>\n";
		$qrygetdetails=mysql_query("SELECT IF(cf.FNAME IS NULL,c.FNAME,cf.FNAME) AS FNAME,
			IF(cf.GNAME IS NULL,c.GNAME,cf.GNAME) AS GNAME,cc.DATEDISEMB,cc.DATEEMB,
			IF(cf.FNAME IS NULL,'',cf.NATIONALITY) AS NATIONALITY
			FROM crewchange cc
			LEFT JOIN crew c ON cc.APPLICANTNO=c.APPLICANTNO
			LEFT JOIN crewforeign cf ON cc.APPLICANTNO=cf.APPLICANTNO
			WHERE cc.DATEDISEMB>'$datetimenow' AND cc.VESSELCODE='$vesselcode' AND cc.RANKCODE='D11'
			ORDER BY cc.DATEDISEMB") or die(mysql_error());
		$cntcolspan=0;
		$cntcrew=0;
		while($rowgetdetails=mysql_fetch_array($qrygetdetails))
		{
			$fname=$rowgetdetails["FNAME"];
			$gname=$rowgetdetails["GNAME"];
			$datedisemb=$rowgetdetails["DATEDISEMB"];
			$dateemb=$rowgetdetails["DATEEMB"];
			$nationality=$rowgetdetails["NATIONALITY"];
			if(empty($nationality))
				$fnameshow=$fname;
			else 
				$fnameshow=$nationality;
			$getmonthemb=date("m",strtotime($dateemb))*1;
			$getyearemb=date("Y",strtotime($dateemb));
			//compute fo shaded area
			$getyear=date("Y",strtotime($datedisemb));
			$getmonth=date("m",strtotime($datedisemb))*1;
			if($startmonth<$getmonthemb && $getyearemb==$startyear && $cntcolspan==0)
			{
				$getdiff=$getmonthemb-$startmonth;
				for($i=1;$i<$getdiff;$i++)
				{
					echo "
					<td style=\"$styledetails\">&nbsp;</td>\n";
				}
				$cntcolspan+=$getdiff-1;
			}
			$getcolspan=(($getyear-$startyear)*12+$getmonth)-$startmonth-$cntcolspan;
			if($getcolspan==0)
				$getcolspan=1;
			
			echo "<td style=\"$styleheader background:{$bgcolor[$cntcrew]}\" title=\"$fname, $gname \" colspan=\"$getcolspan\">$fnameshow ($getcolspan)</td>";
			$cntcolspan+=$getcolspan;
			$cntcrew++;
		}
		for($i=1;$i<=$monthcolcnt-$cntcolspan;$i++)
		{
			echo "
			<td style=\"$styledetails\">&nbsp;</td>\n";
		}
		echo "</tr>";
		if($cntrow==$totalrows) //LOAD NEXT PAGE IF ROW IS $totalrows
		{
			$cntrow=0;
			echo $getmonthheader;
			echo $getyearheader;
			echo "</table><br><br>";
			echo $getheader;
			echo $getyearheader;
			echo $getmonthheader;
			echo "
			<tr height=\"20px\" style=\"\">\n
				<td style=\"$styleheader font-size:12pt;\" colspan=\"32\"><i>Master Mariner cont...</i></td>\n
			</tr>\n";
		}
		$cntdata++;
		$cntrow++;
	}
	if(7>$totalrows-$cntrow) //close page if rank will not fit
	{
		$remainingrows=$totalrows-$cntrow;
		for($i=1;$i<=$remainingrows;$i++)
		{
			echo "<tr height=\"15px\">\n";
			echo $setcolumns;
			echo "</tr>\n";
		}
		echo $getmonthheader;
		echo $getyearheader;
		echo "</table><br><br>";
		//PLACE HEADER
		echo $getheader;
		echo $getyearheader;
		echo $getmonthheader;
		$cntrow=0;
	}
	else 
	{
		echo "<tr height=\"15px\">\n";
		echo $setcolumns;
		echo "</tr>\n";
		$cntrow++;
	}
	//FOR Chief Mate
	$qrygetvesselcode=mysql_query("SELECT VESSELCODE,VESSEL 
			FROM vessel v WHERE DIVCODE=$divcode AND STATUS=1
			ORDER BY VESSEL") or die(mysql_error());
	echo "
	<tr height=\"20px\" style=\"\">\n
		<td style=\"$styleheader font-size:12pt;\" colspan=\"32\"><i>Chief Mate</i></td>\n
	</tr>\n";
	$cntrow++;
	$cntdata=0;
//	$cntrow=0;
	while ($rowgetvesselcode=mysql_fetch_array($qrygetvesselcode)) 
	{
		$cntitem=$cntdata+1;
		$vesselcode=$rowgetvesselcode["VESSELCODE"];
		$vessel=$rowgetvesselcode["VESSEL"];
		echo "
		<tr height=\"15px\">\n
			<td style=\"$styleheader\">$cntitem</td>\n
			<td style=\"$styleheader\">$vessel</td>\n";
		$qrygetdetails=mysql_query("SELECT IF(cf.FNAME IS NULL,c.FNAME,cf.FNAME) AS FNAME,
			IF(cf.GNAME IS NULL,c.GNAME,cf.GNAME) AS GNAME,cc.DATEDISEMB,cc.DATEEMB,
			IF(cf.FNAME IS NULL,'',cf.NATIONALITY) AS NATIONALITY
			FROM crewchange cc
			LEFT JOIN crew c ON cc.APPLICANTNO=c.APPLICANTNO
			LEFT JOIN crewforeign cf ON cc.APPLICANTNO=cf.APPLICANTNO
			WHERE cc.DATEDISEMB>'$datetimenow' AND cc.VESSELCODE='$vesselcode' AND cc.RANKCODE='D21'
			ORDER BY cc.DATEDISEMB") or die(mysql_error());
		$cntcolspan=0;
		$cntcrew=0;
		while($rowgetdetails=mysql_fetch_array($qrygetdetails))
		{
			$fname=$rowgetdetails["FNAME"];
			$gname=$rowgetdetails["GNAME"];
			$datedisemb=$rowgetdetails["DATEDISEMB"];
			$dateemb=$rowgetdetails["DATEEMB"];
			$nationality=$rowgetdetails["NATIONALITY"];
			if(empty($nationality))
				$fnameshow=$fname;
			else 
				$fnameshow=$nationality;
			$getmonthemb=date("m",strtotime($dateemb))*1;
			$getyearemb=date("Y",strtotime($dateemb));
			//compute fo shaded area
			$getyear=date("Y",strtotime($datedisemb));
			$getmonth=date("m",strtotime($datedisemb))*1;
			if($startmonth<$getmonthemb && $getyearemb==$startyear && $cntcolspan==0)
			{
				$getdiff=$getmonthemb-$startmonth;
				for($i=1;$i<$getdiff;$i++)
				{
					echo "
					<td style=\"$styledetails\">&nbsp;</td>\n";
				}
				$cntcolspan+=$getdiff-1;
			}
			$getcolspan=(($getyear-$startyear)*12+$getmonth)-$startmonth-$cntcolspan;
			if($getcolspan==0)
				$getcolspan=1;
			
			echo "<td style=\"$styleheader background:{$bgcolor[$cntcrew]}\" title=\"$fname, $gname\" colspan=\"$getcolspan\">$fname ($getcolspan)</td>";
			$cntcolspan+=$getcolspan;
			$cntcrew++;
		}
		for($i=1;$i<=$monthcolcnt-$cntcolspan;$i++)
		{
			echo "
			<td style=\"$styledetails\">&nbsp;</td>\n";
		}
		echo "</tr>";
		if($cntrow==$totalrows) //LOAD NEXT PAGE IF ROW IS $totalrows
		{
			$cntrow=0;
			echo $getmonthheader;
			echo $getyearheader;
			echo "</table><br><br>";
			echo $getheader;
			echo $getyearheader;
			echo $getmonthheader;
			echo "
			<tr height=\"20px\" style=\"\">\n
				<td style=\"$styleheader font-size:12pt;\" colspan=\"32\"><i>Chief Mate cont...</i></td>\n
			</tr>\n";
		}
		$cntdata++;
		$cntrow++;
	}
	if(7>$totalrows-$cntrow) //close page if rank will not fit
	{
		$remainingrows=$totalrows-$cntrow;
		for($i=1;$i<=$remainingrows;$i++)
		{
			echo "<tr height=\"15px\">\n";
			echo $setcolumns;
			echo "</tr>\n";
		}
		echo $getmonthheader;
		echo $getyearheader;
		echo "</table><br><br>";
		//PLACE HEADER
		echo $getheader;
		echo $getyearheader;
		echo $getmonthheader;
		$cntrow=0;
	}
	else 
	{
		echo "<tr height=\"15px\">\n";
		echo $setcolumns;
		echo "</tr>\n";
		$cntrow++;
	}
	//FOR Chief Engineer
	$qrygetvesselcode=mysql_query("SELECT VESSELCODE,VESSEL 
			FROM vessel v WHERE DIVCODE=$divcode AND STATUS=1
			ORDER BY VESSEL") or die(mysql_error());
	echo "
	<tr height=\"20px\" style=\"\">\n
		<td style=\"$styleheader font-size:12pt;\" colspan=\"32\"><i>Chief Engineer</i></td>\n
	</tr>\n";
	$cntrow++;
	$cntdata=0;
//	$cntrow=0;
	while ($rowgetvesselcode=mysql_fetch_array($qrygetvesselcode)) 
	{
		$cntitem=$cntdata+1;
		$vesselcode=$rowgetvesselcode["VESSELCODE"];
		$vessel=$rowgetvesselcode["VESSEL"];
		echo "
		<tr height=\"15px\">\n
			<td style=\"$styleheader\">$cntitem</td>\n
			<td style=\"$styleheader\">$vessel</td>\n";
		$qrygetdetails=mysql_query("SELECT IF(cf.FNAME IS NULL,c.FNAME,cf.FNAME) AS FNAME,
			IF(cf.GNAME IS NULL,c.GNAME,cf.GNAME) AS GNAME,cc.DATEDISEMB,cc.DATEEMB,
			IF(cf.FNAME IS NULL,'',cf.NATIONALITY) AS NATIONALITY
			FROM crewchange cc
			LEFT JOIN crew c ON cc.APPLICANTNO=c.APPLICANTNO
			LEFT JOIN crewforeign cf ON cc.APPLICANTNO=cf.APPLICANTNO
			WHERE cc.DATEDISEMB>'$datetimenow' AND cc.VESSELCODE='$vesselcode' AND cc.RANKCODE='E11'
			ORDER BY cc.DATEDISEMB") or die(mysql_error());
		$cntcolspan=0;
		$cntcrew=0;
		while($rowgetdetails=mysql_fetch_array($qrygetdetails))
		{
			$fname=$rowgetdetails["FNAME"];
			$gname=$rowgetdetails["GNAME"];
			$datedisemb=$rowgetdetails["DATEDISEMB"];
			$dateemb=$rowgetdetails["DATEEMB"];
			$nationality=$rowgetdetails["NATIONALITY"];
			if(empty($nationality))
				$fnameshow=$fname;
			else 
				$fnameshow=$nationality;
			$getmonthemb=date("m",strtotime($dateemb))*1;
			$getyearemb=date("Y",strtotime($dateemb));
			//compute fo shaded area
			$getyear=date("Y",strtotime($datedisemb));
			$getmonth=date("m",strtotime($datedisemb))*1;
			if($startmonth<$getmonthemb && $getyearemb==$startyear && $cntcolspan==0)
			{
				$getdiff=$getmonthemb-$startmonth;
				for($i=1;$i<$getdiff;$i++)
				{
					echo "
					<td style=\"$styledetails\">&nbsp;</td>\n";
				}
				$cntcolspan+=$getdiff-1;
			}
			$getcolspan=(($getyear-$startyear)*12+$getmonth)-$startmonth-$cntcolspan;
			if($getcolspan==0)
				$getcolspan=1;
			
			echo "<td style=\"$styleheader background:{$bgcolor[$cntcrew]}\" title=\"$fname, $gname\" colspan=\"$getcolspan\">$fname ($getcolspan)</td>";
			$cntcolspan+=$getcolspan;
			$cntcrew++;
		}
		for($i=1;$i<=$monthcolcnt-$cntcolspan;$i++)
		{
			echo "
			<td style=\"$styledetails\">&nbsp;</td>\n";
		}
		echo "</tr>";
		if($cntrow==$totalrows) //LOAD NEXT PAGE IF ROW IS $totalrows
		{
			$cntrow=1;
			echo $getmonthheader;
			echo $getyearheader;
			echo "</table><br><br>";
			echo $getheader;
			echo $getyearheader;
			echo $getmonthheader;
			echo "
			<tr height=\"20px\" style=\"\">\n
				<td style=\"$styleheader font-size:12pt;\" colspan=\"32\"><i>Chief Engineer cont...</i></td>\n
			</tr>\n";
		}
		$cntdata++;
		$cntrow++;
	}
	if(7>$totalrows-$cntrow) //close page if rank will not fit
	{
		$remainingrows=$totalrows-$cntrow;
		for($i=1;$i<=$remainingrows;$i++)
		{
			echo "<tr height=\"15px\">\n";
			echo $setcolumns;
			echo "</tr>\n";
		}
		echo $getmonthheader;
		echo $getyearheader;
		echo "</table><br><br>";
		//PLACE HEADER
		echo $getheader;
		echo $getyearheader;
		echo $getmonthheader;
		$cntrow=0;
	}
	else 
	{
		echo "<tr height=\"15px\">\n";
		echo $setcolumns;
		echo "</tr>\n";
		$cntrow++;
	}
	
	//FOR First Assistant Engineer
	//FOR First Assistant Engineer
	//FOR First Assistant Engineer
	$qrygetvesselcode=mysql_query("SELECT VESSELCODE,VESSEL 
			FROM vessel v WHERE DIVCODE=$divcode AND STATUS=1
			ORDER BY VESSEL") or die(mysql_error());
	echo "
	<tr height=\"20px\" style=\"\">\n
		<td style=\"$styleheader font-size:12pt;\" colspan=\"32\"><i>First Assistant Engineer</i></td>\n
	</tr>\n";
	$cntrow++;
	$cntdata=0;
//	$cntrow=0;
	while ($rowgetvesselcode=mysql_fetch_array($qrygetvesselcode)) 
	{
		$cntitem=$cntdata+1;
		$vesselcode=$rowgetvesselcode["VESSELCODE"];
		$vessel=$rowgetvesselcode["VESSEL"];
		echo "
		<tr height=\"15px\">\n
			<td style=\"$styleheader\">$cntitem</td>\n
			<td style=\"$styleheader\">$vessel</td>\n";
		$qrygetdetails=mysql_query("SELECT IF(cf.FNAME IS NULL,c.FNAME,cf.FNAME) AS FNAME,
			IF(cf.GNAME IS NULL,c.GNAME,cf.GNAME) AS GNAME,cc.DATEDISEMB,cc.DATEEMB,
			IF(cf.FNAME IS NULL,'',cf.NATIONALITY) AS NATIONALITY
			FROM crewchange cc
			LEFT JOIN crew c ON cc.APPLICANTNO=c.APPLICANTNO
			LEFT JOIN crewforeign cf ON cc.APPLICANTNO=cf.APPLICANTNO
			WHERE cc.DATEDISEMB>'$datetimenow' AND cc.VESSELCODE='$vesselcode' AND cc.RANKCODE='E21'
			ORDER BY cc.DATEDISEMB") or die(mysql_error());
		$cntcolspan=0;
		$cntcrew=0;
		while($rowgetdetails=mysql_fetch_array($qrygetdetails))
		{
			$fname=$rowgetdetails["FNAME"];
			$gname=$rowgetdetails["GNAME"];
			$datedisemb=$rowgetdetails["DATEDISEMB"];
			$dateemb=$rowgetdetails["DATEEMB"];
			$nationality=$rowgetdetails["NATIONALITY"];
			if(empty($nationality))
				$fnameshow=$fname;
			else 
				$fnameshow=$nationality;
			$getmonthemb=date("m",strtotime($dateemb))*1;
			$getyearemb=date("Y",strtotime($dateemb));
			//compute fo shaded area
			$getyear=date("Y",strtotime($datedisemb));
			$getmonth=date("m",strtotime($datedisemb))*1;
			if($startmonth<$getmonthemb && $getyearemb==$startyear && $cntcolspan==0)
			{
				$getdiff=$getmonthemb-$startmonth;
				for($i=1;$i<$getdiff;$i++)
				{
					echo "
					<td style=\"$styledetails\">&nbsp;</td>\n";
				}
				$cntcolspan+=$getdiff-1;
			}
			$getcolspan=(($getyear-$startyear)*12+$getmonth)-$startmonth-$cntcolspan;
			if($getcolspan==0)
				$getcolspan=1;
			echo "<td style=\"$styleheader background:{$bgcolor[$cntcrew]}\" title=\"$fname, $gname\" colspan=\"$getcolspan\">$fname ($getcolspan)</td>";
			$cntcolspan+=$getcolspan;
			$cntcrew++;
		}
		for($i=1;$i<=$monthcolcnt-$cntcolspan;$i++)
		{
			echo "
			<td style=\"$styledetails\">&nbsp;</td>\n";
		}
		echo "</tr>";
		if($cntrow==$totalrows) //LOAD NEXT PAGE IF ROW IS $totalrows
		{
			$cntrow=1;
			echo $getmonthheader;
			echo $getyearheader;
			echo "</table><br><br>";
			echo $getheader;
			echo $getyearheader;
			echo $getmonthheader;
			echo "
			<tr height=\"20px\" style=\"\">\n
				<td style=\"$styleheader font-size:12pt;\" colspan=\"32\"><i>First Assistant Engineer cont...</i></td>\n
			</tr>\n";
		}
		$cntdata++;
		$cntrow++;
	}
	$remainingrows=$totalrows-$cntrow;
	
	for($i=1;$i<=$remainingrows;$i++)
	{
		echo "<tr height=\"15px\">\n";
		echo $setcolumns;
		echo "</tr>\n";
	}
	echo $getmonthheader;
	echo $getyearheader;
echo "
</table>

<br>
<br>
<!--
<table cellspacing=\"0\" cellpadding=\"0\">
	<tr>\n
		<td style=\"width:375px;text-align:center;font-size:12px;font-weight:bold;\"><u>Capt. Felix Mendoza Romo</u></td>\n
		<td style=\"width:100px;\">&nbsp;</td>
		<td style=\"width:375px;text-align:center;font-size:12px;font-weight:bold;\"><u>CE. Joemer B. Gnilo</u></td>\n
	</tr>
	<tr>\n
		<td style=\"width:375px;text-align:center;font-size:10px;vertical-align:top;\">Asst. General Manager Div-2</td>\n
		<td style=\"width:100px;\">&nbsp;</td>
		<td style=\"width:375px;text-align:center;font-size:10px;vertical-align:top;\">Port Engineer Div-2</td>\n
	</tr>
</table>-->
</form>";
include('veritas/include/printclose.inc');
echo "

</body>
</html>";
?>