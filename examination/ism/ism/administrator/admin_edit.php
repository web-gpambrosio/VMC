<?php
session_start();
if(!session_is_registered(adminno))
{
header("location:login.php");
}
if ((!isset($_GET['admin']) || trim($_GET['admin']) == '')&&(!isset($_GET['empno']) || trim($_GET['empno']) == ''))
{
header("location:index.php");
}
include('../connection/conn.php');
$admin=$_GET['admin'];
$empno=$_GET['empno'];
$xcode="Select * from admin where empno='$empno'";
$query_result=mysql_query("$xcode",$conn);	
$id=mysql_result($query_result,0,"id");
$txtempno=mysql_result($query_result,0,"empno");
$lname=mysql_result($query_result,0,"lname");
$fname=mysql_result($query_result,0,"fname");
$mname=mysql_result($query_result,0,"mname");
$position=mysql_result($query_result,0,"position");
$contactno=mysql_result($query_result,0,"contactno");
$email=mysql_result($query_result,0,"email");
$password=mysql_result($query_result,0,"password");

if ($_POST['submit'])
{
$error=array();
$txtempno = $_POST['txtempno'];
$lname = $_POST['lname'];
$fname = $_POST['fname'];
$mname = $_POST['mname'];
$position = $_POST['position'];
$contactno = $_POST['contactno'];
$email = $_POST['email'];
$oldpassword = $_POST['oldpassword'];
$newpassword = $_POST['newpassword'];
$confirmpassword = $_POST['confirmpassword'];


if (trim($txtempno) == '') { $error[0] = "<div class='warning_message'>Enter Employee Number</div>"; }
if (trim($lname) == '') { $error[1] = "<div class='warning_message'>Enter Last Name</div>"; }
if (trim($fname) == '') { $error[2] = "<div class='warning_message'>Enter  First Name</div>"; }
if (trim($mname) == '') { $error[3] = "<div class='warning_message'>Enter Middle Name</div>"; }
if (trim($position) == '') { $error[4] = "<div class='warning_message'>Enter Position</div>"; }
if (trim($contactno) == '') { $error[5] = "<div class='warning_message'>Enter Contact Number</div>"; }
if (trim($email) != '')
{

	if (!eregi("^[a-z0-9]+([\.%!][_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$",$email))
	{
		$error[6] = "<div class='warning_message'>Invalid E-mail Address</div>";
	}
	elseif (trim($email) == '')
	{
		$error[6] = "<div class='warning_message'>Enter your E-mail Address</div>";
	}

}
elseif (trim($email) == '') { $error[6] = "<div class='warning_message'>Enter Email Address</div>"; }

	if (trim($oldpassword) != '')
	{
		if (trim($oldpassword) == $password)
		{
			if (trim($newpassword) == '' || trim($confirmpassword) == '')
			{
				if (trim($newpassword) == '')
				{
					$error[8] = "<div class='warning_message'>Required field cannot be left blank</div>";
				}
				if (trim($confirmpassword) == '')
				{
					$error[9] = "<div class='warning_message'>Required field cannot be left blank</div>";
				}
			}
			else
			{
				if (trim($newpassword) != $confirmpassword)
				{
					$error[8] = "<div class='warning_message'>Passwords do not match</div>";
				}
			}
		}
		else
		{
			$error[7] = "<div class='warning_message'>Invalid Password</div>";
		}
	}
	else
	{
	$newpassword=$password;
	}


if (sizeof($error) == 0)
{
$queryv = "update admin set lname='$lname', fname='$fname', mname='$mname', position='$position', contactno='$contactno', email='$email', password='$newpassword' where id='$id'";
$resultv = mysql_query($queryv) or die ("Error in query: $query. " . mysql_error());

echo "<script type='text/javascript'>alert('".$fname." ". $lname ." has been updated')</script>" ;
echo "<script language=\"javascript\">window.location.href='admin_setting.php?adminno=" . $admin . "'</script>";

mysql_close($conn);
}
else
{
for ($x=0; $x<sizeof($error); $x++)
{
$msg0 = $error[0];
$msg1 = $error[1];
$msg2 = $error[2];
$msg3 = $error[3];
$msg4 = $error[4];
$msg5 = $error[5];
$msg6 = $error[6];
$msg7 = $error[7];
$msg8 = $error[8];
$msg9 = $error[9];
}
}
}


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Edit Administrator Account</title>
<link rel="stylesheet" type="text/css" href="../style/body.css" />
<link rel="stylesheet" type="text/css" href="../style/text_style.css" />

</head>
<body topmargin="10" leftmargin="15" background="../images/background.gif">

<table width=101% height=100% border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
    	<td align="center" valign="top"><table width="800" height="388" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" background="../images/sideline.gif">
          <tr>
            <td width="800" height="105" align="left" background="../images/top.gif"></td>
          </tr>
          <tr>
            <td height="52" align="left"><table width="755" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="79">&nbsp;</td>
                  <td width="268" align="left"><img src="../images/title.gif" width="257" height="49" /></td>
                  <td width="42">&nbsp;</td>
                  <td width="366"><img src="../images/supper.gif" width="378" height="33" /></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td height="12"><img src="../images/sideline.gif" width="800"/></td>
          </tr>
          <tr>
            <td height="106" align="center" valign="top">
<form action="" method="post" enctype="multipart/form-data" name="form" id="form">
            <table width="629" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="17" align="right"><a href="admin_setting.php?adminno=<?php echo $admin; ?>" style="text-decoration:none; color:#0033FF">Cancel</a></td>
              </tr>
              <tr>
                <td height="5"></td>
              </tr>
              <tr>
                <td width="629" height="47" align="left" valign="top"><table width="629" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="1" height="32"><img src="../images/gray_side.gif" width="1" height="32"/></td>
                      <td width="639" height="32" background="../images/gray_mid.gif">
                      <div style="font-size:14px">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $fname.' '.$lname.' : '.$position; ?>
                      </div>
                      </td>
                      <td width="1" height="32"><img src="../images/gray_side.gif" width="1" height="32"/></td>
                    </tr>
                  </table>
                  <br />
                    </td>
              </tr>
              <tr>
                <td height="17" align="center"><table width="382" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="131" align="right">Employee Number :</td>
                    <td width="22">&nbsp;</td>
                    <td width="246" align="left">
                    <input name="txtempno" type="hidden" id="txtempno" value="<?php echo $txtempno; ?>"/><span><?php echo $txtempno; ?></span></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td align="right">Last Name :</td>
                    <td>&nbsp;</td>
                    <td align="left"><input name="lname" type="text" id="lname" style="font-size:12px" size="40" height="12" value="<?php echo $lname; ?>"/><div><?php echo $msg1; ?></div></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td align="right">First Name :</td>
                    <td>&nbsp;</td>
                    <td align="left"><input name="fname" type="text" id="fname" style="font-size:12px" size="40" height="12" value="<?php echo $fname; ?>"/><div><?php echo $msg2; ?></div></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td align="right">Middle Name :</td>
                    <td>&nbsp;</td>
                    <td align="left"><input name="mname" type="text" id="mname" style="font-size:12px" size="40" height="12" value="<?php echo $mname; ?>"/><div><?php echo $msg3; ?></div></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="1" bgcolor="#CCCCCC"></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>

                  <tr>
                    <td align="right">Position : </td>
                    <td>&nbsp;</td>
                    <td align="left"><input name="position" type="text" id="position" style="font-size:12px" size="40" height="12" value="<?php echo $position; ?>"/><div><?php echo $msg4; ?></div></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="1" bgcolor="#CCCCCC"></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td align="right">Contact Number : </td>
                    <td>&nbsp;</td>
                    <td align="left"><input name="contactno" type="text" id="contactno" style="font-size:12px" size="40" height="12" value="<?php echo $contactno; ?>"/><div><?php echo $msg5; ?></div></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td align="right">E-mail Address : </td>
                    <td>&nbsp;</td>
                    <td align="left"><input name="email" type="text" id="email" style="font-size:12px" size="40" height="12" value="<?php echo $email; ?>"/><div><?php echo $msg6; ?></div></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="1" bgcolor="#CCCCCC"></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td align="right">Old Password : </td>
                    <td>&nbsp;</td>
                    <td align="left"><input value="<?php echo $password; ?>" name="oldpassword" type="text" id="oldpassword" style="font-size:12px; width:228px" size="40" height="12"/>
                        <div><?php echo $msg7; ?></div></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td align="right">New Password : </td>
                    <td>&nbsp;</td>
                    <td align="left"><input name="newpassword" type="password" id="newpassword" style="font-size:12px; width:228px" size="40" height="12"/>
                        <div><?php echo $msg8; ?></div></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td align="right">Confirm Password : </td>
                    <td>&nbsp;</td>
                    <td align="left"><input name="confirmpassword" type="password" id="confirmpassword" style="font-size:12px; width:228px" size="40" height="12"/><div><?php echo $msg9; ?></div></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td colspan="3" align="left"><table width="332" height="20" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="139">&nbsp;</td>
                        <td width="193"><input type="submit" name="submit" value="  Save  "/></td>
                      </tr>
                    </table></td>
                    </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                    </tr>

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
