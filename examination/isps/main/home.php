<?php
session_start();
include('includes/myfunction.php');
include('../includes/conn.php');

if ((!isset($_GET['empno']) || trim($_GET['empno']) == '')&&(!isset($_GET['takeno']) || trim($_GET['takeno']) == ''))
{ header("location:../index.php"); }

$empno=$_GET['empno'];
$takeno=$_GET['takeno'];

$result_user_show=mysql_query("select users.crewcode As a, users.fname As b, users.gname As c, users.mname As d, users.bdate As e, users.takeno As i,
							   users.activated As j, type.type As f,
							   examtype.totalno As g, examtype.totaltime As h
							   from users, examtype, type
							   where examtype.examname=type.type and type.id = users.exam and users.takeno='$takeno' and users.crewcode='$empno'",$conn);
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
	
	if ($x == 0)
	{
	$xbutton = '<input type="button" name="button" value="Start Exam" onclick="openclose()"/>';
	}
}
else
{
	echo "<script type='text/javascript'>alert(\"System Error! Please Contact VMC Staff\")</script>" ;
	echo "<script language=\"javascript\">window.location.href='http://www.veritas.com.ph'</script>";
}

include('includes/myuser.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../includes/body.css" />
<title>ISPS Online Examination</title>
<script type="text/javascript">
function openclose()
{
window.open('examination.php?empno=<?php echo $empno; ?>&takeno=<?php echo $takeno; ?>', 'Welcome','location=no,directories=no,menubar=no,toolbar=no,status=no,scrollbars=yes,dependent=no,fullscreen=yes,history=no');
window.open('close.php', '_self');
}
</script>
</head>

<body>
<form action="" method="post" name="form" id="form" enctype="multipart/form-data">
<table width=100% height=100% border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="621" align="center" valign="top">
    
<table width="0" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" class="a">
<?php echo $header; ?>
  <tr>
    <td width="3" background="../images/side.gif" valign="top">&nbsp;</td>
    <td width="794" height="466" colspan="4" align="center" valign="top">
<?php
include('includes/mytag.php');
?>    
      <table width="724" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="724" height="10"></td>
        </tr>
        <tr>
          <td height="312" valign="top" align="center"><table width="566" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="566" height="132" align="left">&nbsp; <br />
                  <span style="font-size:18px"><u>Instructions</u></span> <br />
                  <br />
                Please&nbsp;make a note of the following&nbsp;before taking the Exam.<br />
                <br />
                1. Questions are basically of Multiple choice type<br />
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( <strong>i.e.,</strong> only one answer is correct, these answers are represented by Buttons &quot;
                <input  type="radio" name="radio" />
                &quot; )<br />
                2. For&nbsp;the entire exam&nbsp;you are given some limited time. <br />
                3. The Passmark for this test is  
                <?php
				$grade_passing_grade=mysql_query("select grade * 1 As mygrade from passing order by id desc limit 1",$conn);
				echo mysql_result($grade_passing_grade,0,"mygrade");
				?>%.<br /><br /></td>
            </tr>
          </table>
            <table width="566" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #000000">
            <tr>
                <td height="13" align="left"> 
                </td>
              </tr>
            <tr>
                <td height="13" align="left" class="blue">
                &nbsp;&nbsp;&nbsp;<span class="red">Warning:</span> 
                <span class="blue">Your time starts as soon as you press the "Start Exam" button. <strong style="font-size:14px; color:#000033; text-decoration:underline">Good Luck!</strong></span><br /><br /></td>
              </tr>
              <tr>
                <td width="450" align="left">&nbsp;&nbsp;&nbsp;<?php echo $xbutton; ?><br /><br /></td>
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
