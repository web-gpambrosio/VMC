<?php
echo "<!-- START OF SCRIPT -->

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
@media print 
{
	#ie5menu { display: none; }
	#PrintOut { display: block; }
}
</style>
<!-- Step 2: Finally, insert the below anywhere inside the <body> section of your page: -->
<div id=\"ie5menu\" class=\"skin0\" onMouseover=\"highlightie5(event)\" onMouseout=\"lowlightie5(event)\" onClick=\"jumptoie5(event)\" display:none>

<div class=\"menuitems\"  url=\"javascript:window.print();\">Print</div>
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
<!-- END OF SCRIPT --> ";
?>