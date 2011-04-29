<?php
session_start();

if(!session_is_registered(empno))
{
header("location:index.php");
}

if ((!isset($_GET['empno']) || trim($_GET['empno']) == '')&&(!isset($_GET['tnkc']) || trim($_GET['tnkc']) == ''))
{
header("location:index.php");
}
include('../connection/conn.php');

$empno=$_GET['empno'];
$tnkc=$_GET['tnkc'];

$xcode="Select * from admin where empno='$empno' and tnkc='$tnkc'";
$query_result=mysql_query("$xcode",$conn);
$id=mysql_result($query_result,0,"id");
$empno=mysql_result($query_result,0,"empno");
$lname=mysql_result($query_result,0,"lname");
$fname=mysql_result($query_result,0,"fname");
$position=mysql_result($query_result,0,"position");
$password=mysql_result($query_result,0,"password");
$tnkc=mysql_result($query_result,0,"tnkc");




if ($_POST['submit'])
{
$error=array();
$aaa = $_POST['oldpassword'];
$bbb = $_POST['newpassword'];
$ccc = $_POST['confirmpassword'];

	if (trim($aaa) != '')
	{
		if (trim($aaa) == $password)
		{
			if (trim($bbb) == '' || trim($ccc) == '')
			{
				if (trim($bbb) == '')
				{
					$error[1] = "<div class='warning_message'>Required field cannot be left blank</div>";
				}
				if (trim($ccc) == '')
				{
					$error[2] = "<div class='warning_message'>Required field cannot be left blank</div>";
				}
			}
			else
			{
				if (trim($bbb) != $ccc)
				{
					$error[1] = "<div class='warning_message'>Passwords do not match</div>";
				}
			}
		}
		else
		{
			$error[0] = "<div class='warning_message'>The password you gave is incorrect.</div>";
		}
	}
	else
	{
		$error[0] = "<div class='warning_message'>Required field cannot be left blank</div>";
	}


if (sizeof($error) == 0)
{
	$queryv = "update admin set password='$bbb' where empno='$empno'";
	$resultv = mysql_query($queryv) or die ("Error in query: $query. " . mysql_error());

	echo "<script type='text/javascript'>alert('Your new password has been saved')</script>" ;
	echo "<script language=\"javascript\">window.location.href='change_password.php?empno=" . $empno . "&tnkc=" . $tnkc . "'</script>";
	mysql_close($conn);
}
else
{
for ($x=0; $x<sizeof($error); $x++)
{
$msg0 = $error[0];
$msg1 = $error[1];
$msg2 = $error[2];
}
}
}


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $fname.' '.$lname.' : '.$position; ?></title>
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
                  <td width="366"><?php
				  	switch ($tnkc)
					{
						case "1": $tnkc_picture = "kobe"; break;
						case "2": $tnkc_picture = "manila"; break;
						case "3": $tnkc_picture = "star"; break;
						default: $tnkc_picture = "account"; break;
					}
				  ?>
                  <img src="../images/<?php echo $tnkc_picture; ?>.gif" width="302" height="33" /></td>
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
                <td height="17" align="right"><a href="admin_page.php?empno=<?php echo $empno; ?>&amp;tnkc=<?php echo $tnkc; ?>" style="text-decoration:none; color:#0033FF">Home</a> &nbsp;&nbsp;&nbsp; <a href="../connection/admin_logout.php" style="text-decoration:none; color:#0033FF">Log Out</a> </td>
              </tr>
              <tr>
                <td height="5"></td>
              </tr>
              <tr>
                <td width="629" height="47" align="left" valign="top"><table width="629" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="1" height="32"><img src="../images/gray_side.gif" width="1" height="32"/></td>
                      <td width="639" height="32" background="../images/gray_mid.gif">
                      <div style="font-size:14px">&nbsp;&nbsp;&nbsp;&nbsp;
<a href="account_setting.php?empno=<?php echo $empno; ?>&tnkc=<?php echo $tnkc; ?>" style="color:#0000FF; text-decoration:none">Profile Account</a>
                      &nbsp;&nbsp;::&nbsp;&nbsp;
<a href="change_password.php?empno=<?php echo $empno; ?>&tnkc=<?php echo $tnkc; ?>" style="color:#0000FF; text-decoration:none">Change Password</a>
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
                    <?php echo $empno; ?></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td align="right">Name :</td>
                    <td>&nbsp;</td>
                    <td align="left"><?php echo $fname.' '.$lname; ?></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td align="right">Position :</td>
                    <td>&nbsp;</td>
                    <td align="left"><?php echo $position; ?></td>
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
                    <td align="right">Old Password <span class='warning_message'>*</span> : </td>
                    <td>&nbsp;</td>
                    <td align="left"><input name="oldpassword" type="password" id="contactno" style="font-size:12px" size="40" height="12"/><div><?php echo $msg0; ?></div></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td align="right">New Password <span class='warning_message'>*</span> : </td>
                    <td>&nbsp;</td>
                    <td align="left"><input name="newpassword" type="password" id="bdate" style="font-size:12px" size="40" height="12"/><div><?php echo $msg1; ?></div></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td align="right">Confirm Password <span class='warning_message'>*</span> : </td>
                    <td>&nbsp;</td>
                    <td align="left"><input name="confirmpassword" type="password" id="bplace" style="font-size:12px" size="40" height="12"/><div><?php echo $msg2; ?></div></td>
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
