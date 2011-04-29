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

$xcode="Select * from s_admin where adminno='$adminno'";
$query_result=mysql_query("$xcode",$conn);
			
$query_result_row=mysql_num_rows($query_result);

if ($query_result_row != '0')
	{
		$adminno=mysql_result($query_result,0,"adminno");
		$lname=mysql_result($query_result,0,"lname");
		$fname=mysql_result($query_result,0,"fname");
		$mname=mysql_result($query_result,0,"mname");
		$position=mysql_result($query_result,0,"position");
	}
else
	{
		echo "<script type='text/javascript'>alert('Program Error! Please Contact Veritas I.T. Staff to fix the problem')</script>" ;
		echo "<script language=\"javascript\">window.location.href='http://www.veritas.com.ph'</script>";
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
                  <td width="366"><img src="../images/supper.gif" width="378" height="33" /></td>
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
                <a href="exam_type_create_admin.php?adminno=<?php echo $adminno; ?>" style="text-decoration:none; color:#0033FF"><strong>Type of Exam</strong></a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a href="exam_cat_admin.php?adminno=<?php echo $adminno; ?>" style="text-decoration:none; color:#0033FF"><strong>Category</strong></a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a href="admin_add.php?adminno=<?php echo $adminno; ?>" style="text-decoration:none; color:#0033FF"><strong>Add Principal</strong></a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a href="scheduler_setting.php?adminno=<?php echo $adminno; ?>" style="text-decoration:none; color:#0033FF"><strong>Scheduler</strong></a> 	
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a href="admin_crew_page.php?adminno=<?php echo $adminno; ?>" style="text-decoration:none; color:#0033FF"><strong>Crew</strong></a> 	
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a href="../connection/s_logout.php" style="text-decoration:none; color:#FF0000">Log Out</a>
                &nbsp;&nbsp;|</td>
              </tr>
              <tr>
                <td height="5"></td>
              </tr>
              <tr>
                <td width="629" height="47" align="left" valign="top"><table width="629" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="1" height="32"><img src="../images/gray_side.gif" width="1" height="32"/></td>
                      <td width="639" height="32" background="../images/gray_mid.gif">
                      <div style="font-size:14px"><span style="font-size:14px; color:#000000">&nbsp;&nbsp;&nbsp;&nbsp; <?php echo '<b>'.$fname.' '.substr($mname,0,1).'. '.$lname; ?> </span></div>
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
                    <td width="365" align="left"><span style="color:#006600; font-size:14px"><u>Name</u> / <u>Log In Number</u> / <u>Principal</u></span></td>
                    <td width="190">&nbsp;</td>
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

$xq="Select * from admin";
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
			$dq=mysql_result($yq,$i,"tnkc");
			
			$tnkcqq=mysql_query("select principal from principal where id='$dq'",$conn);
			$tnkcq=mysql_result($tnkcqq,0,"principal");
echo '
<tr>
<td align="left" height="20" valign="top"><span style="color:#aaaaaa">'.$ii.'.</span></td>
<td align="left" valign="top">'.$cq.' '.$bq.'&nbsp;&nbsp;-&nbsp;&nbsp; '.$aq.'&nbsp;&nbsp;-&nbsp;&nbsp; '.$tnkcq.'</td>
<td align="left" valign="top">|&nbsp;&nbsp;&nbsp;&nbsp; 
<a href="admin_edit.php?empno='.$aq.'&admin='.$adminno.'" style="text-decoration:none; color:#0000FF">Edit</a>  &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
<a href="admin_delete.php?empno='.$aq.'&admin='.$adminno.'" style="text-decoration:none; color:#0000FF">Delete</a> &nbsp;&nbsp;&nbsp;|</td>
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
