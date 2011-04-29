<?php
session_start();

if(!session_is_registered(adminno))
{
header("location:login.php");
}

if ((!isset($_GET['adminno']) || trim($_GET['adminno']) == ''))
{
header("location:login.php");
}
include('../connection/conn.php');

$adminno=$_GET['adminno'];
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Exam Scheduler</title>
<link rel="stylesheet" type="text/css" href="../style/body.css" />
<link rel="stylesheet" type="text/css" href="../style/text_style.css" />
</head>
<body topmargin="10" leftmargin="15" background="../images/background.gif">

<table width=101% height=100% border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
    	<td align="center" valign="top"><table width="800" height="465" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" background="../images/sideline.gif">
          <tr>
            <td width="800" height="105" align="left" background="../images/top.gif"></td>
          </tr>
          <tr>
            <td height="52" align="left"><table width="755" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="79">&nbsp;</td>
                  <td width="268" align="left"><img src="../images/title.gif" width="257" height="49" /></td>
                  <td width="42">&nbsp;</td>
                  <td width="366"><img src="../images/accoun2.gif" width="302" height="33" /></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td height="12"><img src="../images/sideline.gif" width="800"/></td>
          </tr>
          <tr>
            <td height="183" align="center" valign="top">
<form action="" method="post" enctype="multipart/form-data" name="form" id="form">
            <table width="629" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="17" align="right">
                |&nbsp;&nbsp;
                <a href="scheduler_add.php?adminno=<?php echo $adminno; ?>" style="text-decoration:none; color:#0033FF">Add Scheduler Account</a> 
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a href="admin_setting.php?adminno=<?php echo $adminno; ?>" style="text-decoration:none; color:#0033FF">Home</a>
                &nbsp;&nbsp;|
                </td>
              </tr>
              <tr>
                <td height="5"></td>
              </tr>
              <tr>
                <td width="629" height="47" align="left" valign="top"><table width="629" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="1" height="32"><img src="../images/gray_side.gif" width="1" height="32"/></td>
                      <td width="639" height="32" background="../images/gray_mid.gif">
                      <div style="font-size:14px"><span style="font-size:14px; color:#000000"></span></div>
                      </td>
                      <td width="1" height="32"><img src="../images/gray_side.gif" width="1" height="32"/></td>
                    </tr>
                  </table>
                  <br />
                    </td>
              </tr>
              <tr>
                <td height="34" align="center"><table width="581" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                  	<td width="26"></td>
                    <td width="309" align="left"><span style="color:#006600; font-size:14px">Name and Employee Number</span></td>
                    <td width="246">&nbsp;</td>
                  </tr>
                  	<tr>
                    <td colspan="3" height="8"></td>
                    </tr>
                     <tr>
                    <td colspan="3" height="1" bgcolor="#666666"></td>
                    </tr>
                    <tr>
                    <td colspan="3" height="1"></td>
                    </tr><tr>
                    <td colspan="3" height="1" bgcolor="#666666"></td>
                    </tr>
                     <tr>
                    <td colspan="3" height="5"></td>
                    </tr>


<?
$xq="Select * from scheduler";
$yq=mysql_query("$xq",$conn);
$num_row=mysql_num_rows($yq);
if ($num_row=='0')
{
echo '
<tr>
<td height="20" valign="top" colspan="3">Empty Record</td>
</tr>';
}
else
{
$i=0;
while($i < $num_row)
{
$ii = $i+1;

$aq=mysql_result($yq,$i,"empno");
$bq=mysql_result($yq,$i,"lname");
$cq=mysql_result($yq,$i,"fname");

echo '
<tr>
<td align="left" height="20" valign="top"><span style="color:#aaaaaa">'.$ii.'.</span></td>
<td align="left" valign="top">'.$cq.' '.$bq.'&nbsp;&nbsp;-&nbsp;&nbsp; '.$aq.'</td>
<td align="left" valign="top">|&nbsp;&nbsp;&nbsp;&nbsp; 
<a href="scheduler_edit.php?empno='.$aq.'&adminno='.$adminno.'" style="text-decoration:none; color:#0000FF">Edit</a>  &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
<a href="scheduler_delete.php?empno='.$aq.'&adminno='.$adminno.'" style="text-decoration:none; color:#0000FF">Delete</a> &nbsp;&nbsp;&nbsp;|</td>
</tr>';
echo '<tr><td align="left" height="1" colspan="3" bgcolor="#666666"></td></tr>';
echo '<tr><td align="left" height="5" colspan="3"></td></tr>';
$i++;
}
}
?>
                </table></td>
              </tr>
            </table>
            </form>
            </td>
          </tr>
          <tr>
            <td background="../images/bottom.gif" height="112"></td>
          </tr>
        </table></td>
</tr>
</table>

</body>
</html>
