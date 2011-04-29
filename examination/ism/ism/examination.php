<?php
session_start();

if(!session_is_registered(crewcode))
{
	header("location:../index.php");
}

if ((!isset($_GET['crewcode']) || trim($_GET['crewcode']) == '') && (!isset($_GET['takeno']) || trim($_GET['takeno']) == ''))
{
	header("location:../index.php");
}
include('connection/conn.php');
$crewcode=$_GET['crewcode'];
$takeno=$_GET['takeno'];

$examdate = date("Y-m-d");

mysql_query("update users set activated='1', testdate='$examdate' where crewcode='$crewcode' and takeno='$takeno'",$conn);	

/*$xcode="Select users.crewcode, users.passcode, users.exam, users.takeno, users.fname, users.gname, users.mname,
				examtype.totalno,
				examtype.totaltime
				
		from users, examtype where examtype.examname = users.exam and users.crewcode='$crewcode'";

$query_result=mysql_query("$xcode",$conn);	
$crewcode=mysql_result($query_result,0,"users.crewcode");
$fname=mysql_result($query_result,0,"users.fname");
$gname=mysql_result($query_result,0,"users.gname");
$mname=mysql_result($query_result,0,"users.mname");
$takeno=mysql_result($query_result,0,"users.takeno");
$exam=mysql_result($query_result,0,"users.exam");
$totalno=mysql_result($query_result,0,"examtype.totalno");
$totaltime=mysql_result($query_result,0,"examtype.totaltime");*/

$xcode="Select users.crewcode, users.takeno, users.fname, users.gname, users.mname,

				vessel.vessel,
				type.type,

				examtype.totalno, examtype.totaltime
				
		from users, examtype, type, vessel where vessel.id = users.vessel and type.id = users.exam and examtype.examname = type.type and users.crewcode='$crewcode' and takeno = '$takeno'";
			
$query_result=mysql_query("$xcode",$conn);	
$crewcode=mysql_result($query_result,0,"users.crewcode");
$fname=mysql_result($query_result,0,"users.fname");
$gname=mysql_result($query_result,0,"users.gname");
$mname=mysql_result($query_result,0,"users.mname");
$takenox=mysql_result($query_result,0,"users.takeno");
$vessel=mysql_result($query_result,0,"vessel.vessel");

$exam=mysql_result($query_result,0,"type.type");
$totalno=mysql_result($query_result,0,"examtype.totalno");
$totaltime=mysql_result($query_result,0,"examtype.totaltime");

switch ($totaltime)
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
						questions.category As category,
						questions.examtype As examtype
					  from users_exam, questions where users_exam.qid = questions.id and users_exam.crewcode='$crewcode' and users_exam.take='$takeno' order by rand()",$conn);

$count=mysql_num_rows($examgame);
$z=0;

/*if (isset($_POST['finish']))
{
	$OptionID=$_POST['OptionID']; 
	$xid=$_POST['xid'];
	
	foreach ( $xid as $usersid )
	{ 
		$optionvalue=$OptionID[$usersid];
		
		$xquesry = mysql_query("select questions.anscorrect from users_exam, questions where users_exam.qid = questions.id and crewcode='$crewcode' and users_exam.id = '$usersid'");
		$v=mysql_num_rows($xquesry);
		if ($v != '0')
		{
			$anscorrect=mysql_result($xquesry,0,"questions.anscorrect");
			if ($anscorrect == $optionvalue)
			{
				$f = '1';
			}
			else
			{
				$f = '0';
			}
		}
		else
		{
			$f = '0';
		}
			
		mysql_query("update users_exam set ans='$optionvalue', correct='$f' where crewcode='$crewcode' and id='$usersid'");
	} 


$xcoded="select examtype.totalno from users, examtype, type where type.id = users.exam and type.type=examtype.examname and users.crewcode='$crewcode'";
$query_resultd=mysql_query("$xcoded",$conn);	
$totalnof=mysql_result($query_resultd,0,"examtype.totalno");

$query_resultf=mysql_query("select count(correct) As correct_answer from users_exam where crewcode='$crewcode' and take='$takeno' and correct ='1'",$conn);	
$scoref=mysql_result($query_resultf,0,"correct_answer");
$percentscf = (($scoref / $totalnof)*100);
$percentscoref = round($percentscf, 2);

		$to2 = "rynelle.coronacion@veritas.com.ph";
		$subject = "International Safety Management Online Examination";
		$message = "ISM Online Examination\n
					Name: ".$gname." ".$mname.". ".$fname."\n
					Test Name: ".$exam."\n\n
					To view and print the result, visit: \n
					http://www.veritas.com.ph/exam/ism/print.php?crewcode=". $crewcode ."\n\n
					Thank you.";
		$from = "ISM Online Examination <onlineexam@veritas.com.ph>";
		$headers = "From: $from";
		mail($to2,$subject,$message,$headers);

echo "<script type='text/javascript'>alert('Your Score for ". $exam ." exam is ". $percentscoref ."%. Thank you!')</script>" ;
echo "<script language=\"javascript\">window.open('print.php?crewcode=" . $crewcode . "&takeno=" . $takeno . "');</script>";
echo "<script language=\"javascript\">window.close();</script>";
mysql_close($conn);
}*/

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ISM - International Safety Management</title>
<link rel="stylesheet" type="text/css" href="style/body.css" />
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

<script> 
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
</script>


<style type="text/css">
td { color:#000000 }
</style>

</head>
<body topmargin="10" leftmargin="15" background="images/background.gif" onload="Down()">
<form action="query.php?<?php echo 'crewcode='.$crewcode.'&takeno='.$takeno; ?>" method="post" name="frmform">
<table width=100% height=100% border="0" cellspacing="0" cellpadding="0" align="center">
<tr><td align="center" valign="top"><table width="800" height="503" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" background="images/sideline.gif">
          <tr>
            <td width="800" height="105" align="left" background="images/top.gif"></td>
          </tr>
          <tr>
            <td height="52" align="left"><table width="337" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="80">&nbsp;</td>
                  <td width="257" align="left"><img src="images/title.gif" width="257" height="49" /></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td height="5"><img src="images/sideline.gif" width="800"/></td>
          </tr>
          <tr>
            <td height="229" align="left" valign="top"><table width="659" height="221" border="0" cellpadding="0" cellspacing="0">
            <tr>
                  <td width="93" height="221">&nbsp;</td>
                  <td width="566" align="left" valign="top"><hr />
                    <table width="566" border="0" cellspacing="0" cellpadding="0" style="color:#999999; font-size:14px">
                    <tr>
                        <td height="24" bgcolor="#f4f4f4" style="border:#000000 1px solid">
                        <div style="text-align:center; font-size:14px; color:#0000FF"><strong>
						<span style="color:#006600"><?php echo $gname.' '.$mname.'. '.$fname; ?></span>&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;<?php echo $exam; ?></strong><strong>&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;<?php echo $vessel; ?></strong></div>                        </td>
                    </tr>
                      <tr><td height="8"><hr /></td></tr>
                      <tr>
                        <td height="15" align="center"><table width="373" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td height="15" colspan="3" align="center">
                            <span style="color:#000000; text-align:center"><?php echo $totalno; ?> Questions</span></td>
                            </tr>
                          <tr>
                            <td colspan="3" height="5"></td>
                          </tr>
                          <tr>
                            <td width="186" height="15" align="right">
                            <span  style="color:#000000; text-align:center"><strong>Time Limit : </strong></span></td>
                            <td width="4">&nbsp;</td>
                            <td width="183" align="left"><span style="color:#000000; text-align:left">&nbsp;<?php echo $totaltime; ?></span></td>
                          </tr>
                          <tr>
                            <td colspan="3" height="5"></td>
                            </tr>
                          <tr>
                            <td align="right"><span  style="color:#000000; text-align:center"><strong>Time Remaining : </strong></span></td>
                            <td>&nbsp;</td>
                            <td align="left">
                            <span style="color:#000000; text-align:left"><input type="text" name="disp2" readonly="readonly" style="border-width:0px; text-align:left; font-size:14px; width:45px">Minutes
                              <input type="hidden" name="beg2" size="7" value=<?php echo $ytime; ?> /></span></td>
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
                        <td width="544" height="15" valign="top">
                        <input name="xqid[<?php echo $rows['qid']; ?>]" type="hidden" id="xqid" value="<? echo $rows['qid']; ?>" />
						<input name="xid[<?php echo $rows['id']; ?>]" type="hidden" id="xid" value="<? echo $rows['id']; ?>" />
						<?php echo '<strong>' . $zz . '</strong>. ' . $rows['question']; ?></td>
                        <td height="15" valign="top">&nbsp;</td>
                      </tr>
                      
                      <tr><td height="10" valign="top" colspan="2"></td></tr>
                      
                      <tr>
                        <td height="15" valign="top">&nbsp;</td>
                        <td height="15" valign="top">
                        <label style="cursor:pointer"><input type="radio" name="OptionID[<?php echo $rows['id']; ?>]" value="A"/> <?php echo $rows['ans1']; ?></label></td>
                        <td height="15" valign="top">&nbsp;</td>
                      </tr>
                      
                      <tr>
                        <td height="15" valign="top">&nbsp;</td>
                        <td height="15" valign="top">
                        <label style="cursor:pointer"><input type="radio" name="OptionID[<?php echo $rows['id']; ?>]" value="B"/> <?php echo $rows['ans2']; ?></label></td>
                        <td height="15" valign="top">&nbsp;</td>
                      </tr>
	<?php
	if ($rows['ans3'] != "")
	{
	?>                      
                      <tr>
                        <td height="15" valign="top">&nbsp;</td>
                        <td height="15" valign="top">
                        <label style="cursor:pointer"><input type="radio" name="OptionID[<?php echo $rows['id']; ?>]" value="C"/> <?php echo $rows['ans3']; ?></label></td>
                        <td height="15" valign="top">&nbsp;</td>
                      </tr>
	<?php
	}
	if ($rows['ans4'] != "")
	{
	?> 
                      <tr>
                        <td height="15" valign="top">&nbsp;</td>
                        <td height="15" valign="top">
                        <label style="cursor:pointer"><input type="radio" name="OptionID[<?php echo $rows['id']; ?>]" value="D"/> <?php echo $rows['ans4']; ?></label></td>
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
                        <td height="10" colspan="3" align="center">
                        <input type="submit" value=" Finish "/> </td>
                    </tr> 
                    
                    
                                    
</table>

			</td>
            </tr>
            </table></td>
          </tr>
          <tr>
            <td background="images/bottom.gif" height="112"></td>
          </tr>
        </table> </td>
</tr>
</table>
</FORM> 
</body>
</html>
