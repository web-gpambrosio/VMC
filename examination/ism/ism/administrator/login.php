<?php
session_start();
include('../connection/conn.php');
if ($_POST['submit'])
{
	$adminno=$_POST['adminno']; 
	$password=$_POST['password'];
	
	if ($adminno == "" && $password == "")
	{
		$msg1 = "Enter your Administrator Number";
		$msg2 = "Enter your Password";
	}
	else
	{
		$adminno = stripslashes($adminno);
		$password = stripslashes($password);
		$adminno = mysql_real_escape_string($adminno);
		$password = mysql_real_escape_string($password);
	
		$sql="SELECT * FROM s_admin WHERE adminno='$adminno' and password='$password'";
		$result=mysql_query($sql);
		$count=mysql_num_rows($result);
		$password=md5($password);

		if($count==1)
		{
		$adminno=mysql_result($result,0,"adminno");
		session_register("adminno");
		session_register("password"); 
		header("location:admin_setting.php?adminno=$adminno");
		}
		else
		{
		$msg1 = "Invalid Administrator Number and/or ";
		$msg2 = "Invalid Password";
		}
}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Administrator Page - ISM Online Examination</title>
<link rel="stylesheet" type="text/css" href="../style/body.css" />
</head>
<body topmargin="10" background="../images/background.gif">

<table width=100% height=100% border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
    	<td align="center" valign="middle">
        <table width="708" height="517" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" background="../images/sideline.gif">
  <tr>
    <td height="105" colspan="2" align="left" background="../images/top.gif"></td>
    </tr>
  <tr>
    <td height="52" colspan="2" align="left"><table width="337" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="80">&nbsp;</td>
        <td width="257" align="left"><img src="../images/title.gif" width="257" height="49" /></td>
      </tr>
    </table>
      </td>
    </tr>
  <tr>
    <td height="25" colspan="2"><img src="../images/sideline.gif" width="800"/></td>
    </tr>
  <tr>
    <td width="463" background="../images/sideline.gif" align="left" valign="top">
<table width="459" height="183" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="80" height="183">&nbsp;</td>
    <td width="379" valign="top" align="left">
        <form action="" method="post" name="form" id="form" enctype="multipart/form-data">
<table width="0" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="248">
    <div><img src="../images/supper.gif" width="378" height="33" /></div>
    <div style="color:#FF0000"><?php echo $msg; ?></div>    </td>
  </tr>
</table>

    <table width="357" height="95" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="111" height="8"></td>
    <td width="8" rowspan="5"></td>
    <td width="224"></td>
    <td width="14" rowspan="5"></td>
  </tr>
  <tr>
    <td height="18" valign="middle" align="left">Administrator No.: </td>
    <td valign="top">
    <div><input type="text" name="adminno" style="width:150px" maxlength="19"/></div>
    <div style="color:#FF0000; font-size:10px"><?php echo $msg1; ?></div>    </td>
    </tr>
  <tr>
    <td height="10"></td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <td height="18" valign="middle" align="left">Password : </td>
    <td valign="top">
    <div><input type="password" name="password" style="width:150px" maxlength="19"/></div>
    <div style="color:#FF0000; font-size:10px"><?php echo $msg2; ?></div>    </td>
    </tr>
  <tr>
    <td height="29">&nbsp;</td>
    <td valign="bottom">
    <!--<input name="name" type="image" id="submit" value="Search" src="../images/login.jpg" />-->
    <input type="submit" name="submit" value="  Log In  "/>    </td>
    </tr>
</table>
        </form>    </td>
  </tr>
</table>    </td>
    <td width="337" valign="top" align="left">
	<img src="../images/loginsign.gif" width="257" height="204" />    </td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
    </tr>
  <tr>
    <td colspan="2" background="../images/bottom.gif" height="112"></td>
    </tr>
</table>


      </td>
    </tr>
</table>

</body>
</html>
