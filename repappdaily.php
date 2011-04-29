<?php

include("veritas/connectdb.php");

if (isset($_POST['transdatefrom']))
	$transdatefrom = $_POST['transdatefrom'];
	
if (isset($_POST['transdateto']))
	$transdateto = $_POST['transdateto'];

if (isset($_POST['rankcode']))
	$rankcode = $_POST['rankcode'];
else 
	$rankcode = "All";
	
$whererank = "";

switch ($rankcode)
{
	case "M"	:  //MANAGEMENT
		
			$whererank = "AND rl.RANKLEVELCODE='M'";
			$rankselectM = "SELECTED";
			
		break;
	case "O"	:  //OPERATIONAL
		
			$whererank = "AND rl.RANKLEVELCODE='O'";
			$rankselectO = "SELECTED";
			
		break;
	case "S"	:  //SUPPORT
		
			$whererank = "AND rl.RANKLEVELCODE='S'";
			$rankselectS = "SELECTED";
			
		break;
	case "D"	:  //DECK
		
			$whererank = "AND rt.RANKTYPECODE='D'";
			$rankselectD = "SELECTED";
	
		break;
	case "E"	:  //ENGINE
		
			$whererank = "AND rt.RANKTYPECODE='E'";
			$rankselectE = "SELECTED";
			
		break;
	case "T"	:  //STEWARD
		
			$whererank = "AND rt.RANKTYPECODE='S'";
			$rankselectT = "SELECTED";
			
		break;
	
	case "All"	:  //ALL RANKS
		
			$whererank = "";
			$rankselectALL = "SELECTED";
			
		break;
	
	default		: //ANY RANKCODE
	
			$whererank = "AND r.RANKCODE='$rankcode'";
			
		break;
}	

	$transdatefromraw = date('Y-m-d',strtotime($transdatefrom));
	$transdatetoraw = date('Y-m-d',strtotime($transdateto));
/*
	$qrypersonallist = mysql_query("SELECT CONCAT(FNAME,', ',GNAME,' ' ,MNAME) AS NAME, 
									CONCAT(ADDRESS,', ',BARANGAY,', ',TOWN,' ',PROVINCE) AS ADDRESS,
									CONCAT(ADDRESS,', ',MUNICIPALITY,', ',CITY,' ',ZIPCODE) AS ADDRESS1,
									TELNO,EMAIL,r1.RANK AS CHOICE1,r2.RANK AS CHOICE2,r1.RANKCODE AS RANKCODE1,r2.RANKCODE AS RANKCODE2,
									DATEAPPLIED,RECOMMENDEDBY,a.DATEAPPLIED
									FROM crew c
									LEFT JOIN applicant a ON a.APPLICANTNO=c.APPLICANTNO
									LEFT JOIN rank r1 ON r1.RANKCODE=a.CHOICE1
									LEFT JOIN rank r2 ON r2.RANKCODE=a.CHOICE2
									LEFT JOIN addrprovince ap ON ap.PROVCODE=c.PROVINCECODE
									LEFT JOIN addrtown at ON at.TOWNCODE=c.TOWNCODE AND at.PROVCODE=c.PROVINCECODE
									LEFT JOIN addrbarangay ab ON ab.BRGYCODE=c.BARANGAYCODE AND ab.TOWNCODE=c.TOWNCODE AND ab.PROVCODE=c.PROVINCECODE
									LEFT JOIN rank r ON r.RANKCODE=a.CHOICE1
									LEFT JOIN ranklevel rl ON rl.RANKLEVELCODE=r.RANKLEVELCODE
									LEFT JOIN ranktype rt ON rt.RANKTYPECODE=r.RANKTYPECODE
									WHERE a.DATEAPPLIED BETWEEN '$transdatefromraw 00:00:00' AND '$transdatetoraw 23:59:59'
									AND a.CHOICE1 IS NOT NULL
									ORDER BY r.RANKLEVELCODE,r.RANKTYPECODE,r.RANK,a.DATEAPPLIED,NAME
									") or die(mysql_error());
*/	
	$qrypersonallist = mysql_query("SELECT a.APPLICANTNO,CONCAT(FNAME,', ',GNAME,' ' ,MNAME) AS NAME, 
									CONCAT(ADDRESS,', ',BARANGAY,', ',TOWN,' ',PROVINCE) AS ADDRESS,
									CONCAT(ADDRESS,', ',MUNICIPALITY,', ',CITY,' ',ZIPCODE) AS ADDRESS1,
									TELNO,EMAIL,rc1.RANK AS CHOICE1,rc2.RANK AS CHOICE2,rc1.RANKCODE AS RANKCODE1,rc2.RANKCODE AS RANKCODE2,
									DATEAPPLIED,RECOMMENDEDBY,a.DATEAPPLIED
									FROM crew c
									LEFT JOIN applicant a ON a.APPLICANTNO=c.APPLICANTNO
									LEFT JOIN rank rc1 ON rc1.RANKCODE=a.CHOICE1
									LEFT JOIN rank rc2 ON rc2.RANKCODE=a.CHOICE2
									LEFT JOIN addrprovince ap ON ap.PROVCODE=c.PROVINCECODE
									LEFT JOIN addrtown at ON at.TOWNCODE=c.TOWNCODE AND at.PROVCODE=c.PROVINCECODE
									LEFT JOIN addrbarangay ab ON ab.BRGYCODE=c.BARANGAYCODE AND ab.TOWNCODE=c.TOWNCODE AND ab.PROVCODE=c.PROVINCECODE
									LEFT JOIN rank r ON r.RANKCODE=a.CHOICE1
									LEFT JOIN ranklevel rl ON rl.RANKLEVELCODE=r.RANKLEVELCODE
									LEFT JOIN ranktype rt ON rt.RANKTYPECODE=r.RANKTYPECODE
									WHERE a.DATEAPPLIED BETWEEN '$transdatefromraw 00:00:00' AND '$transdatetoraw 23:59:59'
									AND a.CHOICE1 IS NOT NULL AND a.AGREED = 1
									$whererank
									ORDER BY r.RANKLEVELCODE,r.RANKTYPECODE,r.RANK,a.DATEAPPLIED,NAME
									") or die(mysql_error());
	
//
echo "

<html>
<head>
<title>Veritas - Online Application Print</title>
<link rel=\"stylesheet\" type=\"text/css\" href=\"veripro_print.css\">
</head>
<body onload=\"\">

<div style=\"margin-left:10px;margin-top:10px;width:750px;\">

	<h1>List of Applicants</h1>
	<b>From $transdatefrom to $transdateto</b>
	
	<br /><br />

	<table width=\"100%\" class=\"details\">
		<tr>
			<th width=\"10%\" align=\"center\"><u>DATE</u></th>
			<th width=\"20%\" align=\"center\"><u>NAME</u></th>
			<th width=\"20%\" align=\"center\"><u>ADDRESS</u></th>
			<th width=\"10%\" align=\"center\"><u>TELNO</u></th>
			<th width=\"20%\" align=\"center\"><u>EMAIL</u></th>
		</tr>
";
	$tmprankcode = "";
	
	while ($rowpersonallist = mysql_fetch_array($qrypersonallist))
	{
	
		$choice1 = $rowpersonallist["CHOICE1"];
		$rankcode1 = $rowpersonallist["RANKCODE1"];
//		$choice2 = $rowpersonallist["CHOICE2"];
		$rankcode2 = $rowpersonallist["RANKCODE2"];
		$name = $rowpersonallist["NAME"];
		$address = $rowpersonallist["ADDRESS"];
		$address1 = $rowpersonallist["ADDRESS1"];
		$telno = $rowpersonallist["TELNO"];
		$email = $rowpersonallist["EMAIL"];
		$dateapplied = date('m/d/Y',strtotime($rowpersonallist["DATEAPPLIED"]));
//		$recommendedby = $rowpersonallist["RECOMMENDEDBY"];
		
		if ($tmprankcode != $rankcode1)
		{
			echo "
			<tr>
				<td style=\"font-size:1em;font-weight:Bold;color:Blue;background-color:#DCDCDC;\" colspan=\"5\"><u>$choice1</u></td>
			</tr>
			";
		}

echo "	<tr class=\"print\">
			<td valign=\"top\">$dateapplied</td>
			<td valign=\"top\">$name</td>
			<td valign=\"top\">$address</td>
			<td valign=\"top\">$telno</td>
			<td valign=\"top\">$email</td>
		</tr>
";
		$tmprankcode = $rankcode1;
	}
echo "
	</table>
</div>



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
<div class=\"menuitems\" onClick=\"javscript:window.close()\">Close</div>


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


</body>
</html>

";


?>