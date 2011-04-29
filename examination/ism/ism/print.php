<?php 
if ((!isset($_GET['crewcode']) || trim($_GET['crewcode']) == '')&&(!isset($_GET['takeno']) || trim($_GET['takeno']) == ''))
{
	header("location:../index.php");
}
include('connection/conn.php');
$crewcode=$_GET['crewcode'];
$takeno=$_GET['takeno'];

$xcode="Select users.xtake, users.testdate, users.activated, users.crewcode, users.takeno, users.fname, users.gname, users.mname,
		vessel.vessel, vessel.type,
		type.id, type.type,
		principal.id, principal.principal,
		pbw.id, pbw.type,
		examtype.totalno, examtype.totaltime
		from users, examtype, type, vessel, principal, pbw where pbw.id = vessel.type and principal.id = vessel.principal and vessel.id = users.vessel and type.id = users.exam and examtype.examname = type.type and users.crewcode='$crewcode' and takeno = '$takeno'";
			
$query_result=mysql_query("$xcode",$conn);	

if ($rowww != '0')
{	
$crewcode=mysql_result($query_result,0,"users.crewcode");
$fname=mysql_result($query_result,0,"users.fname");
$gname=mysql_result($query_result,0,"users.gname");
$mname=mysql_result($query_result,0,"users.mname");
$xtake=mysql_result($query_result,0,"users.xtake");

$type_queryf=mysql_result($query_result,0,"vessel.type");
$vessel=mysql_result($query_result,0,"vessel.vessel");

$principalid=mysql_result($query_result,0,"principal.id");
$principal=mysql_result($query_result,0,"principal.principal");

$vtypeid=mysql_result($query_result,0,"pbw.id");
$vtype=mysql_result($query_result,0,"pbw.type");

$examid=mysql_result($query_result,0,"type.id");
$exam=mysql_result($query_result,0,"type.type");

$query_result_percentage=mysql_query("select grade from passing order by id desc limit 1",$conn);
	$rowwwv=mysql_num_rows($query_result_percentage);
	if (($rowwwv != '') || ($rowwwv != 0))
	{	
		$crewgrade=mysql_result($query_result_percentage,0,"grade");
	}	

$totalno=mysql_result($query_result,0,"examtype.totalno");
$totaltime=mysql_result($query_result,0,"examtype.totaltime");
$xxxtestdate=mysql_result($query_result,0,"users.testdate");
$takeno=mysql_result($query_result,0,"users.takeno");

	$testdate = date("F d, Y", strtotime($xxxtestdate));
	$wholename= $fname . ', ' . $gname . ' ' . $mname . '.';
	
	$users_examresult=mysql_query("select count(correct) from users_exam where crewcode='$crewcode' and correct ='1' and users_exam.take='$takeno'",$conn);	
	$scorer=mysql_result($users_examresult,0,"count(correct)");
	$percentsc = (($scorer / $totalno)*100);
	$percentscore = round($percentsc, 2);
	
	$qr=mysql_query("Select sum(exam_cat.total) As totalf, sum(exam_cat.max) As maxf from exam_cat, category where exam_cat.examname = '$exam' and 	category.id = exam_cat.idcat and exam_cat.principal='$principalid' and exam_cat.type ='$vtypeid'",$conn);
	$xptotal=mysql_result($qr,0,"totalf");
	$xpmax=mysql_result($qr,0,"maxf");
	
	
	if ($percentscore >= $crewgrade) { $completed = '<span style="color:#006600">PASSED</span>'; } else { $completed = '<span style="color:#FF0000">FAILED</span>'; }

	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ISM Online Examination</title>
<style type="text/css">
body { font-size:12px }
#arial { font-family:Arial }
#arial2 { font-family:Arial; font-size:11px }
#transbox
  {
  /* for IE */
  filter:alpha(opacity=60);
  /* CSS3 standard */
  opacity:0.6;
  }
</style>
<SCRIPT LANGUAGE="JavaScript1.2">
var menuskin = "skin1";
var display_url = 0;
function showmenuie5() {
var rightedge = document.body.clientWidth-event.clientX;
var bottomedge = document.body.clientHeight-event.clientY;
if (rightedge < ie5menu.offsetWidth)
ie5menu.style.left = document.body.scrollLeft + event.clientX - ie5menu.offsetWidth;
else
ie5menu.style.left = document.body.scrollLeft + event.clientX;
if (bottomedge < ie5menu.offsetHeight)
ie5menu.style.top = document.body.scrollTop + event.clientY - ie5menu.offsetHeight;
else
ie5menu.style.top = document.body.scrollTop + event.clientY;
ie5menu.style.visibility = "visible";
return false;
}
function hidemenuie5() {
ie5menu.style.visibility = "hidden";
}
function highlightie5() {
if (event.srcElement.className == "menuitems") {
event.srcElement.style.backgroundColor = "highlight";
event.srcElement.style.color = "white";
if (display_url)
window.status = event.srcElement.url;
   }
}
function lowlightie5() {
if (event.srcElement.className == "menuitems") {
event.srcElement.style.backgroundColor = "";
event.srcElement.style.color = "black";
window.status = "";
   }
}
function jumptoie5() {
if (event.srcElement.className == "menuitems") {
if (event.srcElement.getAttribute("target") != null)
window.open(event.srcElement.url, event.srcElement.getAttribute("target"));
else
window.location = event.srcElement.url;
   }
}
</script>

<script> 
function my_onkeydown_handler() 
{ 
switch (event.keyCode) 
{ 
case 80 :
window.print(); 
break; 
} 
} 
</script>

<script> 
var howLong = 20000;
 t = null;
function closeMe22(){
t = setTimeout("self.close()",howLong);
}
</script> 

</head>
<body onload="closeMe22();self.focus()" style="background-image:url(images/ism.gif); background-repeat:repeat">

<table width=100% height="319" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="63" colspan="7" valign="top" style="border-top:1px solid #000000; border-bottom:1px solid #000000">
    <table width="743" height=100% border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="256" height="61">&nbsp;</td>
        <td width="487" align="center"><label style="color:#000000; font-size:17px; font-family:Verdana"><strong>Taiyo Nippon Kisen Co. Ltd.<br />ISM Online Examination</strong></label></td>
      </tr>
    </table></td>
    <td width="278" valign="bottom" style="border-top:1px solid #000000; border-bottom:1px solid #000000"><div style="text-align:left">Print Date: <strong id="arial"><?php echo date("F j, Y"); ?></strong></div>
    <br /></td>
  </tr>
  <tr>
    <td height="125" colspan="8">
    <table width="868" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="8" height="20">&nbsp;</td>
        <td width="80"><em>Name:</em></td>
        <td width="272"><strong id="arial"><?php echo $wholename; ?></strong></td>
        <td width="72"><em>Principal:</em></td>
        <td width="436"><strong id="arial"><?php echo $principal; ?></strong></td>
        </tr>
      <tr>
        <td height="20">&nbsp;</td>
        <td><em>Test Name:</em></td>
        <td><strong id="arial"><?php echo $exam; ?></strong></td>
        <td width="72"><em>Vessel:</em></td>
        <td width="436"><strong id="arial3"><?php echo $vessel; ?></strong></td>
        </tr>
      <tr>
        <td height="20">&nbsp;</td>
        <td><em>Test Date:</em></td>
        <td><strong id="arial"><?php echo $testdate; ?></strong></td>
        <td width="72"><em>Vessel Type:</em></td>
        <td width="436"><strong id="arial4"><?php echo $vtype; ?></strong></td>
        </tr>
      <tr>
        <td height="20">&nbsp;</td>
        <td><em>Score:</em></td>
        <td><strong id="arial"><?php echo $percentscore. '% (' . $scorer . '/' . $totalno . ')'; ?></strong></td>
        <td width="72">&nbsp;</td>
        <td width="436">&nbsp;</td>
        </tr>
      <tr>
        <td height="20">&nbsp;</td>
        <td><em>Take Attempt:</em></td>
        <td><strong id="arial"><?php echo $xtake; ?></strong></td>
        <td width="72">&nbsp;</td>
        <td width="436">&nbsp;</td>
        </tr>
      <tr>
        <td height="20">&nbsp;</td>
        <td><em>Result Status:</em></td>
        <td><strong id="arial"><?php echo $completed; ?></strong></td>
        <td width="72">&nbsp;</td>
        <td width="436">&nbsp;</td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td width="323" rowspan="2" style="border:1px solid #000000" align="center">Subject Name</td>
    <td height="21" colspan="5" style="border-top:1px solid #000000; border-bottom:1px solid #000000" valign="middle" align="left"><img src="images/questiondes.jpg" height="21" width=100%/></td>
    <td width="106" rowspan="2" align="center" style="border:1px solid #000000">Score Per Subject</td>
    <td valign="bottom" align="center" width="278" rowspan="2" style="border-top:1px solid #000000; border-bottom:1px solid #000000; border-right:1px solid #000000"><table width=91% height="48" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="278" height="38" valign="bottom" align="left"><img src="images/percent.gif" width=100% height="27" /></td>
      </tr>
      <tr>
        <td height="8"></td>
      </tr>
    </table></td>
  </tr>
  <tr style="text-align:center; color:#000000; font-size:12px">
    <td width="69" height="46" style="border-bottom:1px solid #000000; border-right:1px solid #000000">Management</td>
    <td width="66" style="border-bottom:1px solid #000000; border-right:1px solid #000000">Operational</td>
    <td width="60" style="border-bottom:1px solid #000000; border-right:1px solid #000000">Support</td>
    <td width="73" style="border-bottom:1px solid #000000; border-right:1px solid #000000">Total Questions in Subject</td>
    <td width="56" style="border-bottom:1px solid #000000;">Max Possible Score</td>
  </tr>
  
<?php

$xq="Select category.id, category.cat,
	 exam_cat.management, exam_cat.operational, exam_cat.support, exam_cat.total, exam_cat.max
	 from exam_cat, category where exam_cat.examname = '$exam' and exam_cat.type='$type_queryf' and category.id = exam_cat.idcat and exam_cat.principal='$principalid' and exam_cat.type ='$vtypeid' order by category.cat asc";

$yq=mysql_query("$xq",$conn);
$num_rowxx=mysql_num_rows($yq);
$i=0;
while($i < $num_rowxx)
{
$ii = $i+1;
	$cat=mysql_result($yq,$i,"category.id");
	$cat_details=mysql_result($yq,$i,"category.cat");
	$catmanagement=mysql_result($yq,$i,"exam_cat.management");
	$catoperational=mysql_result($yq,$i,"exam_cat.operational");
	$catsupport=mysql_result($yq,$i,"exam_cat.support");
	$cattotal=mysql_result($yq,$i,"exam_cat.total");
	$catmax=mysql_result($yq,$i,"exam_cat.max");
	
	$exam_questions=mysql_query("select count(users_exam.correct) As countcorrect from users_exam, questions where users_exam.crewcode='$crewcode' and users_exam.qid = questions.id and questions.category = '$cat' and users_exam.correct='1' and users_exam.take='$takeno'",$conn);
	
		$www=mysql_result($exam_questions,0,"countcorrect");
		$wwwcatmax = (($www / $catmax)*100);
		$percentwww = round($wwwcatmax, 2);
		

?>
  
  <tr>
    <td style="border-left:1px solid #000000;" height="20" align="left" id="arial2">&nbsp;<?php echo '<span style="color:#777777">' . $ii . '.</span> ' . $cat_details; ?></td>
    <td align="center" id="arial2"><?php echo $catmanagement; ?></td>
    <td align="center" id="arial2"><?php echo $catoperational; ?></td>
    <td align="center" id="arial2"><?php echo $catsupport; ?></td>
    <td align="center" id="arial2"><?php echo $cattotal; ?></td>
    <td align="center" id="arial2"><?php echo $catmax; ?></td>
    <td align="center" id="arial2"><strong><?php echo $percentwww. '% (' . $www . '/' . $catmax . ')'; ?></strong></td>
<td align="center" style="border-right:1px solid #000000;"><table width="90%" height="19" border="0" cellpadding="0" cellspacing="0">
	  					<tr>
        					<td width="278" height="19" valign="bottom" align="left"><img src="images/bar.gif" width=<?php echo $percentwww.'%'; ?> height="18" /></td>
   		  				</tr>
    					</table></td>
  </tr>
  <tr>
    <td colspan="8" height="1"><img src="images/point.jpg" height="1" width=100%/></td>
  </tr>
<?php
$i++;
}
?>  
  
  
  
  
  <tr>
    <td height="25" style="border-left:1px solid #000000; border-bottom:1px solid #000000;">&nbsp;</td>
    <td colspan="3" style="border-bottom:1px solid #000000;"><div style="text-align:center; font-size:14px"><strong>Overall Result &gt;&gt;</strong></div></td>
    <td style="border-bottom:1px solid #000000;"><div style="text-align:center; font-size:12px"><strong id="arial"><?php echo $xptotal; ?></strong></div></td>
    <td style="border-bottom:1px solid #000000;"><div style="text-align:center; font-size:12px"><strong id="arial"><?php echo $xpmax; ?></strong></div></td>
    <td style="border-bottom:1px solid #000000;"><div style="text-align:center; font-size:12px"><strong id="arial"><?php echo $percentscore. '% (' . $scorer . '/' . $totalno . ')'; ?></strong></div></td>
    <td align="center"  style="border-right:1px solid #000000; border-bottom:1px solid #000000;"><table width="90%" height="19" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="278" height="19" valign="bottom" align="left"><img src="images/bar.gif" width=<?php echo $percentscore.'%'; ?> height="18" /></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="8"><div style="text-align:center"><strong>*** NOTHING FOLLOWS ***</strong></div></td>
  </tr>
</table>

</body>
</html>
