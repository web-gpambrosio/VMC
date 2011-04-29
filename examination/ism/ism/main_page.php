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

$xcode="Select users.activated, users.crewcode, users.takeno, users.xtake, users.fname, users.gname, users.mname, vessel.vessel, type.type, vessel.vessel, principal.principal, pbw.type, examtype.totalno, examtype.totaltime from users, examtype, type, vessel, principal, pbw where pbw.id = vessel.type and principal.id = vessel.principal and vessel.id = users.vessel and type.id = users.exam and examtype.examname = type.type and users.crewcode='$crewcode' and takeno = '$takeno'";
			
$query_result=mysql_query("$xcode",$conn);	
$crewcode=mysql_result($query_result,0,"users.crewcode");
$fname=mysql_result($query_result,0,"users.fname");
$gname=mysql_result($query_result,0,"users.gname");
$mname=mysql_result($query_result,0,"users.mname");
$takeno=mysql_result($query_result,0,"users.takeno");
$xtake=mysql_result($query_result,0,"users.xtake");
$vessel=mysql_result($query_result,0,"vessel.vessel");
$principal=mysql_result($query_result,0,"principal.principal");
$vtype=mysql_result($query_result,0,"pbw.type");
$exam=mysql_result($query_result,0,"type.type");
$totalno=mysql_result($query_result,0,"examtype.totalno");
$totaltime=mysql_result($query_result,0,"examtype.totaltime");

$activated=mysql_result($query_result,0,"users.activated");

if ($activated == 0)
{
$xbutton = '<input type="button" name="button" value="Start Exam" onclick="openclose()"/>';
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ISM - International Safety Management</title>
<link rel="stylesheet" type="text/css" href="style/body.css" />
<script type="text/javascript">
function openclose()
{
window.open('examination.php?crewcode=<?php echo $crewcode; ?>&takeno=<?php echo $takeno; ?>', 'welcome','location=no,directories=no,menubar=no,toolbar=no,status=no,scrollbars=yes,dependent=no,fullscreen=yes,history=no');
window.open('close.php', '_self');
}
</script>
</head>
<body topmargin="10" leftmargin="15" background="images/background.gif">

<table width=101% height=100% border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
    	<td align="center" valign="top"><table width="800" height="521" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" background="images/sideline.gif">
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
            <td height="247" align="left" valign="top"><table width="659" height="290" border="0" cellpadding="0" cellspacing="0">
            <tr>
                  <td width="93" height="290">&nbsp;</td>
                  <td width="566" align="left" valign="top"><table width="566" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="566" height="132" align="left">&nbsp;
                        <br />
                            <span style="font-size:18px"><u>Instructions</u></span>
                          <br /> 
                          <br />
                          Please&nbsp;make a note of the following&nbsp;before taking the Exam.<br />
                          <br />
1. Questions are basically of Multiple choice type<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( <strong>i.e.,</strong>
			only one answer is correct, these answers are represented by four Radio Buttons &quot;<input  type="radio" name="radio" />&quot; )<br />
2. For&nbsp;the entire exam&nbsp;you are given some limited time. <br />
3. The Passmark for this test is <?php
				$grade_passing_grade=mysql_query("select grade * 1 As mygrade from passing order by id desc limit 1",$conn);
				echo mysql_result($grade_passing_grade,0,"mygrade");
				?>%.
</td>
                    </tr>
                  </table><br /><hr />
                    <table width="566" border="0" cellspacing="0" cellpadding="0" style="color:#999999; font-size:14px">
                      <tr>
                        <td height="18" >
                        
                        	Name: <strong style="color:#0000FF"><?php echo $gname.' '.$mname.'. '.$fname; ?></strong></td>
                      </tr>
                      <tr><td height="8"></td></tr>
                      <tr>
                        <td height="15">Examination: <strong style="color:#000000"><?php echo $exam; ?></strong></td>
                      </tr>
                      <tr>
                        <td height="8"></td>
                      </tr>
                      <tr>
                        <td height="15">Principal: <strong style="color:#000000"><?php echo $principal; ?></strong></td>
                      </tr>
                      <tr>
                        <td height="8"></td>
                      </tr>
                      <tr>
                        <td height="15">Vessel: <strong style="color:#000000"><?php echo $vessel; ?></strong></td>
                      </tr>
                      <tr>
                        <td height="8"></td>
                      </tr>
                      <tr>
                        <td height="15">Vessel Type: <strong style="color:#000000"><?php echo $vtype; ?></strong></td>
                      </tr>
                      <tr>
                        <td height="8"></td>
                      </tr>
                      <tr>
                        <td height="15">Take Attempt: <strong style="color:#000000"><?php echo $xtake; ?></strong></td>
                      </tr>
                      <tr><td height="8"></td></tr>
                      <tr>
                      <tr><td height="8"><hr /></td></tr>
                      <tr>
                      <tr><td height="8"></td></tr>
                      <tr>
                        <td height="15">Questionare: <strong style="color:#000000"><?php echo $totalno; ?> Questions</strong></td>
                      </tr>
                      <tr><td height="8"></td></tr>
                      <tr>
                        <td height="15">Time Limit: <strong style="color:#000000"><?php echo $totaltime; ?></strong></td>
                      </tr>
                      <tr><td height="8"></td></tr>
                    </table>
                    <hr />
                    <table width="566" border="0" cellspacing="0" cellpadding="0">

                      <tr>
                        <td height="24">
                        Your time starts as soon as you press the "Start Exam" button. <strong style="font-size:14px">Good Luck!</strong><br />
                        <table width="566" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="450" align="left">
	<?php echo $xbutton; ?>
    
    </td>
    <td width="60" align="left">
    <input type="button" name="button2" value="Log Out" onClick="javascript:location.href='connection/logout.php'"/></td>
  </tr>
</table>

                        
                        </td>
                      </tr>
                      <tr>
                        <td height="25">&nbsp;</td>
                      </tr>
                    </table></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td background="images/bottom.gif" height="112"></td>
          </tr>
        </table></td>
</tr>
</table>

</body>
</html>
