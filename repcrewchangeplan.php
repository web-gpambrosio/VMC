<?php

session_start();
		
include('veritas/connectdb.php');
//include('connectdb.php');

include('veritas/include/stylephp.inc');
		
if(isset($_POST['reportresult']))
	$reportresult=stripslashes($_POST['reportresult']);
	
if(isset($_POST['ccpno']))
	$ccpno=$_POST['ccpno'];
	
if(isset($_POST['vesselname']))
	$vesselname=$_POST['vesselname'];
	
if(isset($_POST['vslcode']))
	$vslcode=$_POST['vslcode'];


$qryprincipal = mysql_query("
							SELECT v.VESSEL,m.MANAGEMENT,p.PRINCIPAL,p.INCHARGE
							FROM vessel v
							LEFT JOIN management m ON m.MANAGEMENTCODE=v.MANAGEMENTCODE
							LEFT JOIN principal p ON p.PRINCIPALCODE=m.PRINCIPALCODE
							where VESSEL='$vesselname'
						") or die(mysql_error());

$rowprincipal = mysql_fetch_array($qryprincipal);
$principal = $rowprincipal["PRINCIPAL"];
$incharge = $rowprincipal["INCHARGE"];

echo "
<html>
<head>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='../veritas/veripro.js'></script>
<script type='text/javascript' src='../veritas/crewchangeplanajax.js'></script>

<style>
#noprint
{
	display: none;
}
</style>

</head>

<body>
<form name=\"crewchangeplan\" method=\"POST\">\n

<table style=\"float:left;\" width=\"500px\" cellspacing=\"0\" cellpadding=\"0\">\n
	<tr>\n
		<td style=\"width:25px;\">&nbsp;</td>\n
		<td style=\"width:60px;font-size:12px;font-weight:bold;\">TO</td>\n
		<td style=\"font-size:12px;\">:</td>\n
		<td style=\"font-size:12px;\"><span style=\"font-size:1.2em;font-weight:Bold;color:Blue;\">$principal</span></td>\n
	</tr>\n
	<tr>\n
		<td style=\"width:25px;\">&nbsp;</td>\n
		<td style=\"width:60px;font-size:12px;font-weight:bold;\">ATTN</td>\n
		<td style=\"font-size:12px;\">:</td>\n
		<td style=\"font-size:12px;\"><span style=\"font-size:1.2em;font-weight:Bold;color:Blue;\">$incharge</span></td>\n
	</tr>\n
	<tr>\n
		<td style=\"width:25px;\">&nbsp;</td>\n
		<td style=\"width:60px;font-size:12px;font-weight:bold;\">VESSEL</td>\n
		<td style=\"font-size:12px;\">:</td>\n
		<td style=\"font-size:12px;\"><span style=\"font-size:1.2em;font-weight:Bold;color:Blue;\">$vesselname</span></td>\n
	</tr>\n
</table>\n
<table style=\"float:left;\" width=\"490\" cellspacing=\"0\" cellpadding=\"0\">\n
	<tr>
		<td style=\"text-align:right;\">
			<span style=\"font-size:14px;font-weight:bold;\">FM-266</span>
		</td>\n
	</tr>
	<tr>\n
		<td style=\"text-align:right;\">
			<span style=\"font-size:12px;font-weight:bold;\">CCP No.</span>
			<span style=\"font-size:20px;font-weight:bold;\">$ccpno</span>
		</td>\n
	</tr>\n
</table>\n
<span style=\"width:1000px;text-align:center;font-size:1.2em;font-weight:bold;\">CREW CHANGE PLAN</span>\n
$reportresult
<table style=\"border-right:1px solid black;table-layout:fixed;\" cellspacing=\"0\" cellpadding=\"0\">\n
	<tr>\n
		<td style=\"width:25px;\">&nbsp;</td>\n
		<td style=\"width:965px;$styledetails\">&nbsp;This plan is subject to revision based on vessel's itinerary, crew promotion or extension and upon approval from principals and management.</td>\n
	</tr>\n
</table>\n

<!--
<br>
<br>

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
</table>

-->

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

</body>
</html>";

