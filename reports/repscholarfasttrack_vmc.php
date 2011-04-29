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
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  1  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  2  -->
	<td style=\"width:128px;\">&nbsp;</td>\n	<!--  3  -->
	<td style=\"width:56px;\">&nbsp;</td>\n		<!--  4  -->
	<td style=\"width:26px;\">&nbsp;</td>\n		<!--  5  -->
	<td style=\"width:48px;\">&nbsp;</td>\n		<!--  6  -->
	<td style=\"width:36px;\">&nbsp;</td>\n		<!--  7  -->
	<td style=\"width:48px;\">&nbsp;</td>\n		<!--  8  -->
	<td style=\"width:110px;\">&nbsp;</td>\n	<!--  9  -->
	<td style=\"width:75px;\">&nbsp;</td>\n		<!--  10  -->
	<td style=\"width:75px;\">&nbsp;</td>\n		<!--  11  -->
	<td style=\"width:74px;\">&nbsp;</td>\n		<!--  12  -->
	<td style=\"width:67px;\">&nbsp;</td>\n		<!--  13  -->
	<td style=\"width:89px;\">&nbsp;</td>\n		<!--  14  -->
	<td style=\"width:51px;\">&nbsp;</td>\n		<!--  15  -->
	<td style=\"width:59px;\">&nbsp;</td>\n		<!--  16  -->
</tr>
";

	#*******************POST VALUES*************
$qryscft=mysql_query("SELECT * FROM (
	SELECT cs.APPLICANTNO,BATCHNO*1 AS BATCHNO,CONCAT(FNAME,', ',GNAME) AS NAME,BIRTHDATE,
	LEFT(DATEDIFF(NOW(),BIRTHDATE)/365,2) AS AGE,m.SCHOOL,LEFT(YEARGRADUATE,4) AS GRADUATE,
	'SC' AS STATUS,c.RANKTYPECODE,SCHOLASTICCODE AS SCFTCODE,EXPELLEDDATE
	FROM crewscholar cs
	LEFT JOIN crew c ON cs.APPLICANTNO=c.APPLICANTNO
	LEFT JOIN maritimeschool m ON cs.SCHOOLID=m.SCHOOLID
	WHERE SCHOLASTICCODE IN ('VF','VS')
	UNION
	SELECT cs.APPLICANTNO,BATCHNO*1 AS BATCHNO,CONCAT(FNAME,', ',GNAME) AS NAME,BIRTHDATE,
	LEFT(DATEDIFF(NOW(),BIRTHDATE)/365,2) AS AGE,m.SCHOOL,LEFT(YEARGRADUATE,4) AS GRADUATE,
	'FT' AS STATUS,c.RANKTYPECODE,FASTTRACKCODE AS SCFTCODE,EXPELLEDDATE
	FROM crewfasttrack cs
	LEFT JOIN crew c ON cs.APPLICANTNO=c.APPLICANTNO
	LEFT JOIN maritimeschool m ON cs.SCHOOLID=m.SCHOOLID
	WHERE FASTTRACKCODE IN ('VF','VS')) x
	ORDER BY EXPELLEDDATE,RANKTYPECODE,BATCHNO,BIRTHDATE") or die(mysql_error());

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
		<td style=\"width:896px;text-align:center;font-size:16pt;font-weight:bold;\">LIST OF VMC SCHOLARS & FAST TRACK CADETS as of $datenowshow</td>\n
	</tr>
</table>
<table style=\"border:2px solid black;table-layout:fixed;\" cellspacing=\"0\" cellpadding=\"0\">
	$fixedcolumn
	<tr height=\"55px\" style=\"text-align:center;valign:middle;\">\n
		<td style=\"border-right:1px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;\">&nbsp;</td>\n
		<td style=\"border-right:1px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;\">BATCH</td>\n
		<td style=\"border-right:1px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;\"\">NAME</td>\n
		<td style=\"border-right:1px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;\"\">BIRTHDAY</td>\n
		<td style=\"border-right:1px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;\">AGE</td>\n
		<td style=\"border-right:1px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;\">SCHOOL</td>\n
		<td style=\"border-right:1px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;\">YEAR GRAD</td>\n
		<td style=\"border-right:1px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;\">STATUS</td>\n
		<td style=\"border-right:1px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;\">PRESENT SHIP'S NAME</td>\n
		<td style=\"border-right:1px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;\">EMBARKATION DATE</td>\n
		<td style=\"border-right:1px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;\">DIS-EMBARKATION DATE</td>\n
		<td style=\"border-right:1px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;\">PRESENT POSITION</td>\n
		<td style=\"border-right:1px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;\">NO. OF CONTRACTS IN POSITION</td>\n
		<td style=\"border-right:1px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;\">LICENSE</td>\n
		<td style=\"border-right:1px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;\">W/COC</td>\n
		<td style=\"font-size:9pt;font-family:Arial Narrow;font-weight:bold;\">REMARKS</td>\n
	</tr>
</table>
<br>
<table style=\"border-top:1px solid black;border-right:1px solid black;table-layout:fixed;\" cellspacing=\"0\" cellpadding=\"0\">
	$fixedcolumn";

	$typetemp="";
	$cntrowtype=1;
	$morethan3contracts="";
	while($rowscft=mysql_fetch_array($qryscft))
	{
		$applicantno=$rowscft["APPLICANTNO"];
		$batchno=$rowscft["BATCHNO"];
		$name=$rowscft["NAME"];
		$birthdate=$rowscft["BIRTHDATE"];
		$birthdateshow=date("d-M-y",strtotime($birthdate));
		$age=$rowscft["AGE"];
		$school=$rowscft["SCHOOL"];
		$graduate=$rowscft["GRADUATE"];
		$status=$rowscft["STATUS"];
		$ranktypecode=$rowscft["RANKTYPECODE"];
		$expelleddate=$rowscft["EXPELLEDDATE"];
		if(!empty($batchno))
		{
			if(!empty($expelleddate))
				$type="DELETED FROM CADETSHIP PROGRAM";
			else 
			{
				if($ranktypecode=="D")
					$type="DECK SCHOLARS AND FAST TRACK CADETS";
				elseif($ranktypecode=="E")
					$type="ENGINE SCHOLARS AND FAST TRACK CADETS";
				elseif($ranktypecode=="S")
					$type="STEWARD CADETS";
			}
				
			if($typetemp=="" || $typetemp!=$type)
			{
				if(!empty($typetemp))
					echo "
					<tr height=\"18px\">\n
						<td colspan=\"16\" style=\"$styledetails\">&nbsp;</td>\n
					</tr>
					";
				echo "
				<tr height=\"24px\" style=\"text-align:center;valign:middle;\">\n
					<td colspan=\"16\" style=\"$styledetails font-size:14pt;font-weight:bold;\">$type</td>\n
				</tr>
				";
				$cntrowtype=1;
			}
			//get latest embarkation
			$qrylastembark=mysql_query("SELECT c.VESSELCODE,v.VESSEL,c.DATEEMB,c.DATEDISEMB,c.RANKCODE,r.ALIAS2
				FROM crewchange c
				LEFT JOIN vessel v ON c.VESSELCODE=v.VESSELCODE
				LEFT JOIN rank r ON c.RANKCODE=r.RANKCODE
				WHERE APPLICANTNO=$applicantno
				ORDER BY DATEDISEMB DESC
				LIMIT 1") or die(mysql_error());
			if(mysql_num_rows($qrylastembark)==0)
			{
				$vesselname="";
				$vesselcode="";
				$rankdateshow="";
				$dateembshow="";
				$datedisembshow="";
				$rankcode="";
				$alias2="";
			}
			else 
			{
				$rowlastembark=mysql_fetch_array($qrylastembark);
				$vesselname=$rowlastembark["VESSEL"];
				$vesselcode=$rowlastembark["VESSELCODE"];
				$dateemb=$rowlastembark["DATEEMB"];
				$dateembshow=date("d-M-y",strtotime($dateemb));
				$datedisemb=$rowlastembark["DATEDISEMB"];
				$datedisembshow=date("d-M-y",strtotime($datedisemb));
				$rankcode=$rowlastembark["RANKCODE"];
				$alias2=$rowlastembark["ALIAS2"];
			}
			//get date started for rankcode
			$qryrankdate=mysql_query("SELECT DATEEMB 
				FROM crewchange c
				WHERE APPLICANTNO=$applicantno AND RANKCODE='$rankcode'
				ORDER BY DATEEMB
				LIMIT 1") or die(mysql_error());
			if(mysql_num_rows($qryrankdate)==0)
			{
				$rankdateshow="";
			}
			else 
			{
				$rowrankdate=mysql_fetch_array($qryrankdate);
				$rankdate=$rowrankdate["DATEEMB"];
				$rankdateshow=date("M y",strtotime($rankdate));
			}
			//get no. of contracts
			$qrycontracts=mysql_query("SELECT COUNT(*) AS CONTRACTS 
				FROM crewchange c
				WHERE APPLICANTNO=$applicantno AND RANKCODE='$rankcode'") or die(mysql_error());
			$rowcontracts=mysql_fetch_array($qrycontracts);
			$contracts=$rowcontracts["CONTRACTS"];
			//get philippine license with date of issue
			$qrylicense=mysql_query("SELECT c.RANKCODE,r.ALIAS2,DATEISSUED,c.DOCNO 
				FROM crewdocstatus c
				LEFT JOIN rank r ON c.RANKCODE=r.RANKCODE
				WHERE APPLICANTNO=$applicantno AND DOCCODE='F1'
				ORDER BY DATEISSUED DESC
				LIMIT 1") or die(mysql_error());
			if(mysql_num_rows($qrylicense)==0)
			{
				$dateissuedshow="";
				$licenserank="";
				$docno="";
			}
			else 
			{
				$rowlicense=mysql_fetch_array($qrylicense);
				$dateissued=$rowlicense["DATEISSUED"];
				$dateissuedshow=date("M y",strtotime($dateissued));
				$licenserank=$rowlicense["ALIAS2"];
				$docno=$rowlicense["DOCNO"];
			}
			//check COC if exist and indicate if YES or NO
			$qrycoc=mysql_query("SELECT DOCNO 
				FROM crewdocstatus c 
				WHERE APPLICANTNO=$applicantno AND DOCCODE='18'") or die(mysql_error());
			if(mysql_num_rows($qrycoc)==0)
				$coc="None";
			else 
			{
				$rowcoc=mysql_fetch_array($qrycoc);
				$doccoc=substr($rowcoc["DOCNO"],0,2);
				$coc=$doccoc."-Yes";
			}
			//for REMARKS
			if(!empty($expelleddate))
				$rem="DEL";
			else 
			{
				if($datedisemb>=$datenow)
					$rem="OB";
				else 
					$rem="STBY";
			}
			
			$placerow = "
			<tr height=\"18px\" style=\"text-align:center;valign:middle;\">\n
				<td style=\"$styledetails font-family:Arial Narrow;\">$cntrowtype</td>\n
				<td style=\"$styledetails font-family:Arial Narrow;\">$batchno</td>\n
				<td style=\"$styledetails font-family:Arial Narrow;text-align:left;\" title=\"$applicantno\">&nbsp;&nbsp;$name</td>\n
				<td style=\"$styledetails font-family:Arial Narrow;\">&nbsp;$birthdateshow</td>\n
				<td style=\"$styledetails font-family:Arial Narrow;\">&nbsp;$age</td>\n
				<td style=\"$styledetails font-family:Arial Narrow;\">&nbsp;$school</td>\n
				<td style=\"$styledetails font-family:Arial Narrow;\">&nbsp;$graduate</td>\n
				<td style=\"$styledetails font-family:Arial Narrow;\">&nbsp;$status</td>\n
				<td style=\"$styledetails font-family:Arial Narrow;\">&nbsp;$vesselname</td>\n
				<td style=\"$styledetails font-family:Arial Narrow;\">$dateembshow</td>\n
				<td style=\"$styledetails font-family:Arial Narrow;\">$datedisembshow</td>\n
				<td style=\"$styledetails font-family:Arial Narrow;\">$alias2-$rankdateshow</td>\n
				<td style=\"$styledetails font-family:Arial Narrow;\">$contracts</td>\n
				<td style=\"$styledetails font-family:Arial Narrow;\" title=\"$docno\">$licenserank-$dateissuedshow</td>\n
				<td style=\"$styledetails font-family:Arial Narrow;\">$coc</td>\n
				<td style=\"$styledetails font-family:Arial Narrow;\">$rem</td>\n
			</tr>
			";
			echo $placerow;
			if($contracts>=3)//if contracts is greater than or equal to 3, save in variable
				$morethan3contracts.=$placerow;
			$typetemp=$type;
			$cntrowtype++;
		}
	}
	if($morethan3contracts!="")
	{
		$type="SCHOLARS / FAST TRACKS W/ MORE THAN 3 CONTRACTS IN PRESENT POSITION";
		echo "
		<tr height=\"18px\">\n
			<td colspan=\"16\" style=\"$styledetails\">&nbsp;</td>\n
		</tr>
		<tr height=\"24px\" style=\"text-align:center;valign:middle;\">\n
			<td colspan=\"16\" style=\"$styledetails font-size:14pt;font-weight:bold;\">$type</td>\n
		</tr>
		";
		echo $morethan3contracts;
	}
echo "
</table>
</form>


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
}
-->
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

</body>\n
</html>\n";

?>
