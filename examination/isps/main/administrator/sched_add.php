<?php
session_start();
include('includes/myfunction.php');
include('../../includes/conn.php');

if ((!isset($_GET['empno']) || trim($_GET['empno']) == ''))
{ header("location:../../index.php"); }

$empno=$_GET['empno'];

include('includes/myname.php');
include('includes/inc.php');
if ($_POST['submit'])
{
	$error=array();
	$txtempno = $_POST['txtempno'];
	
	$mycheck = $_POST['mycheck'];
	
	$txtlname = $_POST['txtlname'];
	$txtfname = $_POST['txtfname'];
	$txtmname = $_POST['txtmname'];
	$txtposition = $_POST['txtposition'];
	$txtcontactno = $_POST['txtcontactno'];
	$txtemail = $_POST['txtemail'];
	$txtnewpassword = $_POST['txtnewpassword'];
	$txtconfirmpassword = $_POST['txtconfirmpassword'];
	
if ($mycheck != true)
{
	if (trim($txtempno) == '')
	{
		$error[0] = "<div class='warning_message'>Enter Employee Number</div>";
	}
	else
	{
		$empno_admin_duplicate=mysql_query("select empno from admin where empno='".$txtempno."'",$conn);
		$empno_admin_duplicate_row=mysql_num_rows($empno_admin_duplicate);
		if ($empno_admin_duplicate_row != '0')
		{
			$error[0] = "<div class='warning_message'>Duplicate Employee Number</div>";
		}
	}
}
else
{
	$txtempno = 'VMC-'.date('di');
}
if (trim($txtlname) == '') { $error[1] = "<div class='warning_message'>Enter Last Name</div>"; }
if (trim($txtfname) == '') { $error[2] = "<div class='warning_message'>Enter First Name</div>"; }
if (trim($txtmname) == '') { $error[3] = "<div class='warning_message'>Enter Middle Name</div>"; }
if (trim($txtposition) == '') { $error[4] = "<div class='warning_message'>Enter Position</div>"; }
if (trim($txtcontactno) == '') { $error[5] = "<div class='warning_message'>Enter Contact Number</div>"; }
if (trim($txtemail) == '')
{ $error[6] = "<div class='warning_message'>Enter Email Address</div>"; }
elseif (!eregi("^[a-z0-9]+([\.%!][_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$",$txtemail))
{ $error[6] = "<div class='warning_message'>Invalid E-mail Address</div>"; }
if (trim($txtnewpassword) == '') { $error[7] = "<div class='warning_message'>Enter Password</div>"; }
elseif (trim($txtnewpassword) != trim($txtconfirmpassword)) { $error[7] = "<div class='warning_message'>Passwords do not match</div>"; }

if (sizeof($error) == 0)
{

	$queryv = "insert into admin (empno, lname, fname, mname, position, contactno, email, password, level) values ('$txtempno', '$txtlname', '$txtfname', '$txtmname', '$txtposition', '$txtcontactno', '$txtemail', md5('$txtnewpassword'), '$lvls')";
	$resultv = mysql_query($queryv) or die ("Error in query: $query. " . mysql_error());
	if ($mycheck != true)
	{ echo "<script type='text/javascript'>alert('New Scheduler Account has been Added!')</script>"; }
	else
	{ echo "<script type='text/javascript'>alert('Your Employee Number is ".$txtempno.". Record Saved!')</script>"; }
	  echo "<script language=\"javascript\">window.location.href='sched_page.php?empno=" . $empno . "'</script>";
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
}
}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../includes/body.css" />
<title>Administrator - ISPS Online Examination</title>
</head>

<body>
<form action="" method="post" name="form" id="form" enctype="multipart/form-data">
<table width=100% height=100% border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="621" align="center" valign="top">
    
<table width="0" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" class="a">
<?php echo $header; ?>
  <tr>
    <td width="3" background="../../images/side.gif" valign="top">&nbsp;</td>
    <td width="794" height="466" colspan="4" align="center" valign="top">
    <?php
include('includes/mytitle.php');
?>

      <table width="724" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="724" height="10"></td>
        </tr>
        <tr>
          <td height="312" valign="top" align="center"><table width="629" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td height="17" align="right">&nbsp;</td>
            </tr>
            <tr>
              <td height="5"></td>
            </tr>
            <tr>
              <td height="30" bgcolor="#eeeeee" style="border:1px solid #999999" align="center"><div class="black"><strong>CREATE NEW SCHEDULER ACCOUNT</strong></div></td>
            </tr>
            <tr>
              <td height="11"></td>
            </tr>
            <tr>
              <td height="17" align="center"><table width="382" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="131" align="right" valign="top">Employee Number <span class="requiredx">*</span> :</td>
                    <td width="22">&nbsp;</td>
                    <td width="246" align="left">
                    <input name="txtempno" type="txt" <?php echo $txtstyle . 'value="'.$txtempno.'"'; ?>/>
                    <div><?php echo $msg0; ?></div>
                    <div><input type="checkbox" name="mycheck"/> Don't have Employee Number.</div>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                 <tr>
                    <td align="right" valign="top">Last Name <span class="requiredx">*</span> :</td>
                    <td>&nbsp;</td>
                    <td align="left">
                    <input name="txtlname" type="text" <?php echo $txtstyle . 'value="'.$txtlname.'"'; ?>/>
                        <div><?php echo $msg1; ?></div></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td align="right" valign="top">First Name <span class="requiredx">*</span> :</td>
                    <td>&nbsp;</td>
                    <td align="left">
                    <input name="txtfname" type="text" <?php echo $txtstyle . 'value="'.$txtfname.'"'; ?>/>
                        <div><?php echo $msg2; ?></div></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td align="right" valign="top">Middle Name <span class="requiredx">*</span> :</td>
                    <td>&nbsp;</td>
                    <td align="left">
                    <input name="txtmname" type="text" <?php echo $txtstyle . 'value="'.$txtmname.'"'; ?>/>
                        <div><?php echo $msg3; ?></div></td>
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
                    <td align="right" valign="top">Position <span class="requiredx">*</span> : </td>
                    <td>&nbsp;</td>
                    <td align="left">
                    <input name="txtposition" type="text" <?php echo $txtstyle . 'value="'.$txtposition.'"'; ?>/>
                        <div><?php echo $msg4; ?></div></td>
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
                    <td align="right" valign="top">Contact Number <span class="requiredx">*</span> : </td>
                    <td>&nbsp;</td>
                    <td align="left">
                    <input name="txtcontactno" type="text" <?php echo $txtstyle . 'value="'.$txtcontactno.'"'; ?>/>
                        <div><?php echo $msg5; ?></div></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td align="right" valign="top">E-mail Address <span class="requiredx">*</span> : </td>
                    <td>&nbsp;</td>
                    <td align="left">
                    <input name="txtemail" type="text" <?php echo $txttext . 'value="'.$txtemail.'"'; ?>/>
                        <div><?php echo $msg6; ?></div></td>
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
                    <td align="right" valign="top">Password <span class="requiredx">*</span> : </td>
                    <td>&nbsp;</td>
                    <td align="left">
                    <input name="txtnewpassword" type="password" <?php echo $txttext; ?>/>
                        <div><?php echo $msg7; ?></div></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td align="right" valign="top">Confirm Password : </td>
                    <td>&nbsp;</td>
                    <td align="left">
                    <input name="txtconfirmpassword" type="password" <?php echo $txttext; ?>/></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td colspan="3" align="left"><table width="332" height="20" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td width="139">&nbsp;</td>
                          <td width="193">
<input type="submit" name="submit" value="  Save  "/>
<input type="button" name="back" value="  Cancel  " onClick="javascript:location.href='sched_page.php?empno=<?php echo $empno; ?>'"/></td>
                        </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
              </table></td>
            </tr>
          </table></td>
        </tr>
      </table>
      <!-- ------------------------------------------------------------------------------------ -->    </td>
    <td width="3" align="right" valign="top" background="../../images/side.gif">&nbsp;</td>
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
