<?php
session_start();
include('includes/myfunction.php');
include('../includes/conn.php');
$crewcode=$_GET['empno'];
$takeno=$_GET['takeno'];

$bxxb = time ();
$examdate = date("Y-m-d g:i:s",$bxxb);
mysql_query("update users set activated='1', testdate='$examdate' where crewcode='$crewcode' and takeno='$takeno'",$conn);

if ((!isset($_GET['empno']) || trim($_GET['empno']) == '')&&(!isset($_GET['takeno']) || trim($_GET['takeno']) == ''))
{ header("location:../index.php"); }


$result_user_show=mysql_query("select users.crewcode As a, users.fname As b, users.gname As c, users.mname As d, users.bdate As e, users.takeno As i,
							   users.activated As j, type.type As f,
							   examtype.totalno As g, examtype.totaltime As h
							   from users, examtype, type
							   where examtype.examname=type.type and type.id = users.exam and users.takeno='$takeno' and users.crewcode='$crewcode'",$conn);
$row_user_show=mysql_num_rows($result_user_show);
if ($row_user_show != '0')
{
	$zcrewcode=mysql_result($result_user_show,0,"a");
	$zfname=mysql_result($result_user_show,0,"b");
	$zgname=mysql_result($result_user_show,0,"c");
	$zmname=mysql_result($result_user_show,0,"d");
	$zbdate=mysql_result($result_user_show,0,"e");
	$zexam=mysql_result($result_user_show,0,"f");
	$zno=mysql_result($result_user_show,0,"g");
	$ztime=mysql_result($result_user_show,0,"h");
	$ztake=mysql_result($result_user_show,0,"i");
	$x=mysql_result($result_user_show,0,"j");
	
	$name = strtoupper($zfname . ', ' . $zgname . ' ' . $zmname);
	$staff = date("F d, Y",strtotime($zbdate));
}
else
{
	echo "<script type='text/javascript'>alert(\"System Error! Please Contact VMC Staff\")</script>" ;
	/*echo "<script language=\"javascript\">window.location.href='http://www.veritas.com.ph'</script>";*/
}

switch ($ztime)
{
case "15 Minutes": $ytime = "15"; break;
case "30 Minutes": $ytime = "30"; break;
case "45 Minutes": $ytime = "45"; break;
case "60 Minutes": $ytime = "60"; break;
case "90 Minutes": $ytime = "90"; break;
case "120 Minutes": $ytime = "120"; break;
default: $ytime = "60"; break;
}

$examgame=mysql_query("select
						users_exam.id As id,
						users_exam.ans As ans,
						users_exam.qid As qid,
						questions.question As question,
						questions.ans1 As ans1,
						questions.ans2 As ans2,
						questions.ans3 As ans3,
						questions.ans4 As ans4,
						questions.anscorrect As anscorrect,
						questions.examtype As examtype
					  from users_exam, questions where users_exam.qid = questions.id and users_exam.crewcode='$crewcode' and 
					  	users_exam.take='$takeno'",$conn);
$count=mysql_num_rows($examgame);
$z=0;

include('includes/myuser.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../includes/body.css" />
<title>Scheduler - ISPS Online Examination</title>
<SCRIPT LANGUAGE="JavaScript"> 

var down;var min1,sec1;var cmin1,csec1,cmin2,csec2;
function Minutes(data) {
for(var i=0;i<data.length;i++) 
if(data.substring(i,i+1)==":") 
break;  
return(data.substring(0,i)); 
}
function Seconds(data) {        
for(var i=0;i<data.length;i++) 
if(data.substring(i,i+1)==":") 
break;  
return(data.substring(i+1,data.length)); 
}
function Display(min,sec) {     
var disp;       
if(min<=9) disp=" 0";   
else disp=" ";  
disp+=min+":";  
if(sec<=9) disp+="0"+sec;       
else disp+=sec; 
return(disp); 
}
function Down() {       
cmin2=1*Minutes(document.frmform.beg2.value);        
csec2=0+Seconds(document.frmform.beg2.value);        
DownRepeat(); 
}
function DownRepeat() { 
csec2--;        
if(csec2==-1) { 
csec2=59; cmin2--; 
}       
document.frmform.disp2.value=Display(cmin2,csec2);   
if((cmin2==0)&&(csec2==0)) 
{
document.frmform.submit();
}
else
{
down=setTimeout("DownRepeat()",1000);
}
}
</SCRIPT> 
<script>
 var isNS = (navigator.appName == "Netscape") ? 1 : 0;
  if(navigator.appName == "Netscape") document.captureEvents(Event.MOUSEDOWN||Event.MOUSEUP);
  function mischandler(){
   return false;
 }
  function mousehandler(e){
 	var myevent = (isNS) ? e : event;
 	var eventbutton = (isNS) ? myevent.which : myevent.button;
    if((eventbutton==2)||(eventbutton==3)) return false;
 }
 document.oncontextmenu = mischandler;
 document.onmousedown = mousehandler;
 document.onmouseup = mousehandler;
</script>

<!-- no refresh -->

<!--<script> 
window.history.forward(1); 
document.attachEvent("onkeydown", my_onkeydown_handler); 
function my_onkeydown_handler() 
{ 
switch (event.keyCode) 
{ 
case 116 :
event.returnValue = false; 
event.keyCode = 0; 
break; 
} 
} 
</script>-->
</head>

<body onload="Down()">
<form action="query.php?<?php echo 'crewcode='.$crewcode.'&takeno='.$takeno; ?>" method="post" name="frmform">
<table width=100% height=100% border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="621" align="center" valign="top">
    
<table width="0" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" class="a">
<?php echo $xheader; ?>
  <tr>
    <td width="3" background="../images/side.gif" valign="top">&nbsp;</td>
    <td width="794" height="466" colspan="4" align="center" valign="top">
      <table width="724" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="724" height="10"></td>
        </tr>
        <tr>
          <td height="312" valign="top" align="center"><table width="659" height="221" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="93" height="221">&nbsp;</td>
              <td width="566" align="left" valign="top">
                  <table width="566" border="0" cellspacing="0" cellpadding="0" style="color:#999999; font-size:14px">
                    <tr>
                      <td height="24" align="center"><table width="471" height="81" border="0" cellpadding="0" cellspacing="0" bgcolor="#f4f4f4" style="border:#000000 1px solid">
                        <tr>
                          <td width="204"></td>
                          <td width="14" height="5"></td>
                          <td width="251" height="5"></td>
                          </tr>
                        <tr>
                          <td align="right">NAME:</td>
                          <td height="19"></td>
                          <td height="19" align="left" valign="top"><strong><span class="blackxx"><?php echo $name; ?></span></strong> </td>
                          </tr>
                        <tr>
                          <td align="right">Birthdate:</td>
                          <td height="19"></td>
                          <td height="19" align="left" valign="top"><strong><span class="blackxx"><?php echo $staff; ?></span></strong></td>
                          </tr>
                        <tr>
                          <td align="right">Examination:</td>
                          <td height="19"></td>
                          <td height="19" align="left" valign="top"><strong><span class="blackxx"><?php echo $zexam; ?></span></strong></td>
                          </tr>
                        <tr>
                          <td align="right">Take Attempt:</td>
                          <td height="19"></td>
                          <td height="19" align="left" valign="top"><strong><span class="blackxx"><?php echo $ztake; ?></span></strong></td>
                          </tr>

                      </table></td>
                    </tr>
                    <tr>
                      <td height="8"><hr /></td>
                    </tr>
                    <tr>
                      <td height="15" align="center"><table width="373" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td height="15" colspan="3" align="center"><span style="color:#000000; text-align:center"><?php echo $totalno; ?> Questions</span></td>
                          </tr>
                          <tr>
                            <td colspan="3" height="5"></td>
                          </tr>
                          <tr>
                            <td width="186" height="15" align="right"><span  style="color:#000000; text-align:center"><strong>Time Limit : </strong></span></td>
                            <td width="4">&nbsp;</td>
                            <td width="183" align="left"><span style="color:#000000; text-align:left">&nbsp;<?php echo $ztime; ?></span></td>
                          </tr>
                          <tr>
                            <td colspan="3" height="5"></td>
                          </tr>
                          <tr>
                            <td align="right"><span  style="color:#000000; text-align:center"><strong>Time Remaining : </strong></span></td>
                            <td>&nbsp;</td>
                            <td align="left"><span style="color:#000000; text-align:left">
                              <input type="text" name="disp2" readonly="readonly" style="border-width:0px; text-align:left; font-size:14px; width:45px" />
                              Minutes
                              <input type="hidden" name="beg2" size="7" value="<?php echo $ytime; ?>" />
                            </span></td>
                          </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td height="8"></td>
                    </tr>
                  </table>
                <hr />
                  <table width="567" height="54" border="0" cellpadding="0" cellspacing="0" align="center">
                    <?php 
while($rows=mysql_fetch_array($examgame))
{
	$zz = $z+1;
?>
                    <tr <?php echo $rad; ?>>
                      <td width="23" height="15" valign="top">&nbsp;</td>
                      <td width="544" height="15" valign="top"><input name="xqid[<?php echo $rows['qid']; ?>]" type="hidden" id="xqid" value="<? echo $rows['qid']; ?>" />
                          <input name="xid[<?php echo $rows['id']; ?>]" type="hidden" id="xid" value="<? echo $rows['id']; ?>" />
                          <?php echo '<strong>' . $zz . '</strong>. ' . $rows['question']; ?></td>
                      <td height="15" valign="top">&nbsp;</td>
                    </tr>
                    <tr>
                      <td height="10" valign="top" colspan="2"></td>
                    </tr>
                    <tr>
                      <td height="15" valign="top">&nbsp;</td>
                      <td height="15" valign="top"><input type="radio" name="OptionID[<?php echo $rows['id']; ?>]" value="A"/>
                          <?php echo $rows['ans1']; ?></td>
                      <td height="15" valign="top">&nbsp;</td>
                    </tr>
                    <tr>
                      <td height="15" valign="top">&nbsp;</td>
                      <td height="15" valign="top"><input type="radio" name="OptionID[<?php echo $rows['id']; ?>]" value="B"/>
                          <?php echo $rows['ans2']; ?></td>
                      <td height="15" valign="top">&nbsp;</td>
                    </tr>
                    <?php
	if ($rows['ans3'] != "")
	{
	?>
                    <tr>
                      <td height="15" valign="top">&nbsp;</td>
                      <td height="15" valign="top"><input type="radio" name="OptionID[<?php echo $rows['id']; ?>]" value="C"/>
                          <?php echo $rows['ans3']; ?></td>
                      <td height="15" valign="top">&nbsp;</td>
                    </tr>
                    <?php
	}
	if ($rows['ans4'] != "")
	{
	?>
                    <tr>
                      <td height="15" valign="top">&nbsp;</td>
                      <td height="15" valign="top"><input type="radio" name="OptionID[<?php echo $rows['id']; ?>]" value="D"/>
                          <?php echo $rows['ans4']; ?></td>
                    </tr>
                    <?php 
	}
	?>
                    <tr>
                      <td height="10" colspan="3"></td>
                    </tr>
                    <tr>
                      <td height="1" colspan="3" bgcolor="#CCCCCC"></td>
                    </tr>
                    <tr>
                      <td height="10" colspan="3"></td>
                    </tr>
                    <?php
$z++;
}
?>
                    <tr>
                      <td height="10" colspan="3" align="center"><input type="submit" value=" Finish "/>
                      </td>
                    </tr>
                </table></td>
            </tr>
          </table></td>
        </tr>
      </table>
      <!-- ------------------------------------------------------------------------------------ -->    </td>
    <td width="3" align="right" valign="top" background="../images/side.gif">&nbsp;</td>
  </tr>
<?php echo $footer; ?>  
  <tr>
    <td height="1"></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
</table></td>
  </tr>
</table>
</form>
</body>
</html>
