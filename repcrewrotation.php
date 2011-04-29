<?php

//include('veritas/connectdb.php');
include('connectdb.php');

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
	$divcode=2; //temp data
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
	$yearspan2=$monthcolcnt-12-$yearspan1;
	$placeyear3=$placeyear2+1;
	$getyearheader .= "
	<td style=\"$styleheader\" colspan=\"$yearspan2\">$placeyear3</td>\n"; //3rd year
	if(12+$yearspan1+$yearspan2<$monthcolcnt)
	{
		$yearspan3=$monthcolcnt-(12+$yearspan1+$yearspan2);
		$placeyear4=$placeyear4+1;
		$getyearheader .= "
		<td style=\"$styleheader\" colspan=\"$yearspan3\">$placeyear4</td>\n"; //4th year if any
	}
	$getyearheader .= "</tr>\n";
	
	//GET MONTH HEADER
	$getmonthheader = "
	<tr height=\"15px\" style=\"\">\n
		<td style=\"$styledetails\" colspan=\"2\">&nbsp;</td>";
	$monthno=$startmonth;
	for($m=$startmonth;$m<$startmonth+$monthcolcnt;$m++)
	{
		if($m==12 || $m==24)
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
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
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
		$qrygetdetails=mysql_query("SELECT c.FNAME,c.GNAME,cc.DATEDISEMB
			FROM crewchange cc
			LEFT JOIN crew c ON cc.APPLICANTNO=c.APPLICANTNO
			WHERE cc.DATEDISEMB>'$datetimenow' AND cc.VESSELCODE='$vesselcode' AND RANKCODE='D11'
			ORDER BY cc.DATEDISEMB") or die(mysql_error());
		$cntcolspan=0;
		$cntcrew=0;
		while($rowgetdetails=mysql_fetch_array($qrygetdetails))
		{
			$fname=$rowgetdetails["FNAME"];
			$gname=$rowgetdetails["GNAME"];
			$datedisemb=$rowgetdetails["DATEDISEMB"];
			//compute fo shaded area
			$getyear=date("Y",strtotime($datedisemb));
			$getmonth=date("m",strtotime($datedisemb))*1;
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
			echo "<\tr>\n";
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
		$qrygetdetails=mysql_query("SELECT c.FNAME,c.GNAME,cc.DATEDISEMB
			FROM crewchange cc
			LEFT JOIN crew c ON cc.APPLICANTNO=c.APPLICANTNO
			WHERE cc.DATEDISEMB>'$datetimenow' AND cc.VESSELCODE='$vesselcode' AND RANKCODE='D21'
			ORDER BY cc.DATEDISEMB") or die(mysql_error());
		$cntcolspan=0;
		$cntcrew=0;
		while($rowgetdetails=mysql_fetch_array($qrygetdetails))
		{
			$fname=$rowgetdetails["FNAME"];
			$gname=$rowgetdetails["GNAME"];
			$datedisemb=$rowgetdetails["DATEDISEMB"];
			//compute fo shaded area
			$getyear=date("Y",strtotime($datedisemb));
			$getmonth=date("m",strtotime($datedisemb))*1;
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
			echo "<\tr>\n";
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
		$qrygetdetails=mysql_query("SELECT c.FNAME,c.GNAME,cc.DATEDISEMB
			FROM crewchange cc
			LEFT JOIN crew c ON cc.APPLICANTNO=c.APPLICANTNO
			WHERE cc.DATEDISEMB>'$datetimenow' AND cc.VESSELCODE='$vesselcode' AND RANKCODE='E11'
			ORDER BY cc.DATEDISEMB") or die(mysql_error());
		$cntcolspan=0;
		$cntcrew=0;
		while($rowgetdetails=mysql_fetch_array($qrygetdetails))
		{
			$fname=$rowgetdetails["FNAME"];
			$gname=$rowgetdetails["GNAME"];
			$datedisemb=$rowgetdetails["DATEDISEMB"];
			//compute fo shaded area
			$getyear=date("Y",strtotime($datedisemb));
			$getmonth=date("m",strtotime($datedisemb))*1;
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
			echo "<\tr>\n";
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
		$qrygetdetails=mysql_query("SELECT c.FNAME,c.GNAME,cc.DATEDISEMB
			FROM crewchange cc
			LEFT JOIN crew c ON cc.APPLICANTNO=c.APPLICANTNO
			WHERE cc.DATEDISEMB>'$datetimenow' AND cc.VESSELCODE='$vesselcode' AND RANKCODE='E21'
			ORDER BY cc.DATEDISEMB") or die(mysql_error());
		$cntcolspan=0;
		$cntcrew=0;
		while($rowgetdetails=mysql_fetch_array($qrygetdetails))
		{
			$fname=$rowgetdetails["FNAME"];
			$gname=$rowgetdetails["GNAME"];
			$datedisemb=$rowgetdetails["DATEDISEMB"];
			//compute fo shaded area
			$getyear=date("Y",strtotime($datedisemb));
			$getmonth=date("m",strtotime($datedisemb))*1;
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

<table cellspacing=\"0\" cellpadding=\"0\">
	<tr>\n
		<td style=\"width:375px;text-align:center;font-size:12px;font-weight:bold;\"><u>Capt Andy Alvaro</u></td>\n
		<td style=\"width:100px;\">&nbsp;</td>
		<td style=\"width:375px;text-align:center;font-size:12px;font-weight:bold;\"><u>Capt Dan Cardozo</u></td>\n
	</tr>
	<tr>\n
		<td style=\"width:375px;text-align:center;font-size:10px;vertical-align:top;\">Asst. General Manager Div-1</td>\n
		<td style=\"width:100px;\">&nbsp;</td>
		<td style=\"width:375px;text-align:center;font-size:10px;vertical-align:top;\">Fleet Manager Div-1</td>\n
	</tr>
</table>
<!-- START OF SCRIPT -->

<!-- Step 1: Insert the below into the <head> section of your page: -->
<style>
<!--

/* Context menu Script- © Dynamic Drive (www.dynamicdrive.com) Last updated: 01/08/22
For full source code and Terms Of Use, visit http://www.dynamicdrive.com */

.skin0{
position:absolute;
width:165px;
border:2px solid black;
background-color:menu;
font-family:Verdana;
line-height:20px;
cursor:default;
font-size:14px;
z-index:100;
visibility:hidden;
}

.menuitems{
padding-left:10px;
padding-right:10px;
size: landscape;
}



-->
</style>
<style type='text/css' media='print'> 
	@page { size: landscape; }
</style>
<!-- Step 2: Finally, insert the below anywhere inside the <body> section of your page: -->
<div id=\"ie5menu\" class=\"skin0\" onMouseover=\"highlightie5(event)\" onMouseout=\"lowlightie5(event)\" onClick=\"jumptoie5(event)\" display:none>

<div class=\"menuitems\" url=\"javascript:window.print();\">Print</div>
<div class=\"menuitems\" onClick=\"javscript:window.close()\" target=\"_self\">Close</div>


</div>

<script language=\"JavaScript1.2\">

//set this variable to 1 if you wish the URLs of the highlighted menu to be displayed in the status bar
var display_url=0

var ie5=document.all&&document.getElementById
var ns6=document.getElementById&&!document.all
if (ie5||ns6)
var menuobj=document.getElementById(\"ie5menu\")

function showmenuie5(e){
//Find out how close the mouse is to the corner of the window
var rightedge=ie5? document.body.clientWidth-event.clientX : window.innerWidth-e.clientX
var bottomedge=ie5? document.body.clientHeight-event.clientY : window.innerHeight-e.clientY

//if the horizontal distance isn't enough to accomodate the width of the context menu
if (rightedge<menuobj.offsetWidth)
//move the horizontal position of the menu to the left by it's width
menuobj.style.left=ie5? document.body.scrollLeft+event.clientX-menuobj.offsetWidth : window.pageXOffset+e.clientX-menuobj.offsetWidth
else
//position the horizontal position of the menu where the mouse was clicked
menuobj.style.left=ie5? document.body.scrollLeft+event.clientX : window.pageXOffset+e.clientX

//same concept with the vertical position
if (bottomedge<menuobj.offsetHeight)
menuobj.style.top=ie5? document.body.scrollTop+event.clientY-menuobj.offsetHeight : window.pageYOffset+e.clientY-menuobj.offsetHeight
else
menuobj.style.top=ie5? document.body.scrollTop+event.clientY : window.pageYOffset+e.clientY

menuobj.style.visibility=\"visible\"
return false
}

function hidemenuie5(e){
menuobj.style.visibility=\"hidden\"
}

function highlightie5(e){
var firingobj=ie5? event.srcElement : e.target
if (firingobj.className==\"menuitems\"||ns6&&firingobj.parentNode.className==\"menuitems\"){
if (ns6&&firingobj.parentNode.className==\"menuitems\") firingobj=firingobj.parentNode //up one node
firingobj.style.backgroundColor=\"highlight\"
firingobj.style.color=\"white\"
if (display_url==1)
window.status=event.srcElement.url
}
}

function lowlightie5(e){
var firingobj=ie5? event.srcElement : e.target
if (firingobj.className==\"menuitems\"||ns6&&firingobj.parentNode.className==\"menuitems\"){
if (ns6&&firingobj.parentNode.className==\"menuitems\") firingobj=firingobj.parentNode //up one node
firingobj.style.backgroundColor=\"\"
firingobj.style.color=\"black\"
window.status=''
}
}

function jumptoie5(e){
var firingobj=ie5? event.srcElement : e.target
if (firingobj.className==\"menuitems\"||ns6&&firingobj.parentNode.className==\"menuitems\"){
if (ns6&&firingobj.parentNode.className==\"menuitems\") firingobj=firingobj.parentNode
if (firingobj.getAttribute(\"target\"))
window.open(firingobj.getAttribute(\"url\"),firingobj.getAttribute(\"target\"))
else
window.location=firingobj.getAttribute(\"url\")
}
}

if (ie5||ns6){
menuobj.style.display=''
document.oncontextmenu=showmenuie5
document.onclick=hidemenuie5
}

</script>
<!-- END OF SCRIPT -->
</form>

</body>
</html>";
?>