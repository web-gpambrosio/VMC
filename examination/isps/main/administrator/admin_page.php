<?php
session_start();
include('includes/myfunction.php');
include('../../includes/conn.php');
if ((!isset($_GET['empno']) || trim($_GET['empno']) == ''))
{ header("location:../../index.php"); }
$empno=$_GET['empno'];
include('includes/myname.php');
include('includes/inc.php');
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
          <td height="378" valign="top" align="center">
          <table width="629" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="629" height="361" align="center" style="border:1px solid #666666" valign="top"><table width="581" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td></td>
                    <td align="left">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                  </tr>
                  <tr>
                  	<td width="26"></td>
                    <td width="423" align="left"><span style="color:#006600; font-size:14px">Name / Employee Number - AMDINISTRATOR</span></td>
                    <td width="132" align="right">
                    <a href='admin_add.php?empno=<?php echo $empno; ?>' style="color:#006600; font-size:14px; text-decoration:none">New Account</a>                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
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


<?php
$yqq = $admin . " where level=".md5."('level1') and empno != '".$empno."'";
$yq=mysql_query($yqq);
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
			$aid=mysql_result($yq,$i,"id");
			$aq=mysql_result($yq,$i,"empno");
			$bq=mysql_result($yq,$i,"lname");
			$cq=mysql_result($yq,$i,"fname");	
			
echo '
<tr>
<td align="left" height="20" valign="top"><span style="color:#aaaaaa">'.$ii.'.</span></td>
<td align="left" valign="top">'.$cq.' '.$bq.'&nbsp;&nbsp;-&nbsp;&nbsp; '.$aq.'</td>
<td align="left" valign="top">|&nbsp;&nbsp;&nbsp;&nbsp; 
<a href="admin_edit.php?empno='.$empno.'&edit='.$aid.'" style="text-decoration:none; color:#0000FF">Edit</a>  &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
<a href="admin_delete.php?empno='.$aq.'&admin='.$empno.'" style="text-decoration:none; color:#0000FF">Delete</a> &nbsp;&nbsp;&nbsp;|</td>
</tr>';
echo '<tr><td align="left" height="1" colspan="3" bgcolor="#666666"></td></tr>';
echo '<tr><td align="left" height="5" colspan="3"></td></tr>';
$i++;
}
}
?>
                 <tr>
                    <td height="15"></td>
                    <td align="left">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
            </table>
          </td>
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
