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

if ($_POST['submit'])
{
$error=array();
$txtcat = $_POST['txtcat'];

if ($txtcat != '')
{
	if (preg_match("/[!@#$%^&*,\"'.:;<>?]/", $txtcat))
	{
		$error[0] = "<div class='warning_message'>Please do not use Symbol</div>";
	}
	else
	{
		$cat01=mysql_query("select type from type where type like '$txtcat%'",$conn);
		$cat02=mysql_num_rows($cat01);
		if ($cat02 > 0)
		{
			$error[0] = "<div class='warning_message'>Duplicate Entry</div>";
		}
	}
}
elseif (trim($txtcat) == '') { $error[0] = "<div class='warning_message'>Enter Type of Examination</div>"; }

if (sizeof($error) == 0)
{
$queryv = "insert into type (type) values ('$txtcat')";
$resultv = mysql_query($queryv) or die ("Error in query: $query. " . mysql_error());
echo "<script type='text/javascript'>alert('Record Saved!')</script>" ;
echo "<script language=\"javascript\">window.location.href='exam_type_create.php?empno=" . $empno . "&tnkc=" . $tnkc . "'</script>";

mysql_close($conn);
}
else
{
for ($x=0; $x<sizeof($error); $x++)
{
$msg0 = $error[0];
}
}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Type of Examination</title>
<link rel="stylesheet" type="text/css" href="../style/body.css" />
<link rel="stylesheet" type="text/css" href="../style/text_style.css" />
</head>
<body topmargin="10" leftmargin="15" background="../images/background.gif">

<table width=101% height=100% border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
    	<td height="544" align="center" valign="top"><table width="800" height="388" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" background="../images/sideline.gif">
          <tr>
            <td width="800" height="105" align="left" background="../images/top.gif"></td>
          </tr>
          <tr>
            <td height="52" align="left"><table width="755" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="79">&nbsp;</td>
                  <td width="268" align="left"><img src="../images/title.gif" width="257" height="49" /></td>
                  <td width="42">&nbsp;</td>
                  <td width="366"><img src="../images/acc.gif" width="302" height="33" /></td>
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
                <td height="17" align="right">
                
                <a href="admin_page.php?empno=<?php echo $empno; ?>&tnkc=<?php echo $tnkc; ?>" style="text-decoration:none; color:#0033FF">Home</a>
                &nbsp;&nbsp;&nbsp;<a href="../connection/admin_logout.php" style="text-decoration:none; color:#0033FF">Log Out</a>                </td>
              </tr>
              <tr>
                <td height="5"></td>
              </tr>
              <tr>
                <td width="629" height="47" align="left" valign="top"><table width="629" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="1" height="32"><img src="../images/gray_side.gif" width="1" height="32"/></td>
                      <td width="639" height="32" background="../images/gray_mid.gif">
                      <div style="font-size:14px">&nbsp;&nbsp;&nbsp;&nbsp;Type of Examination</div>
                      </td>
                      <td width="1" height="32"><img src="../images/gray_side.gif" width="1" height="32"/></td>
                    </tr>
                  </table>
                  <br />
                    </td>
              </tr>
              <tr>
                <td height="110" align="center"><table width="408" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td width="147" align="right">Examination : </td>
                    <td width="17">&nbsp;</td>
                    <td width="244" align="left">
<input name="txtcat" type="text" id="txtcat" style="font-size:12px; width:228px" size="40" height="12"/>
                        <div><?php echo $msg0; ?></div></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td colspan="3" align="left"><table width="332" height="20" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td width="139">&nbsp;</td>
                          <td width="193"><input type="submit" name="submit" value="  Add  "/></td>
                        </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                </table>
                  <table width="581" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="26"></td>
                      <td width="465" align="left"></td>
                      <td width="90">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="3" height="8"></td>
                    </tr>
                    <tr>
                      <td colspan="3" height="1" bgcolor="#666666"></td>
                    </tr>
                    <tr>
                      <td colspan="3" height="5"></td>
                    </tr>
<?php
$xq="Select * from type where stat='0' order by id asc";
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
			$xid=mysql_result($yq,$i,"id");
			$xcat=mysql_result($yq,$i,"type");
echo '
<tr>
<td align="left" height="20" valign="top"><span style="color:#aaaaaa">'.$ii.'.</span></td>
<td align="left" valign="top"><strong>
<span style="color:#000000; text-decoration:none">'.$xcat.'</span></strong>
</td>
<td align="left" valign="top">';
/*
echo '
|&nbsp;&nbsp;&nbsp;&nbsp;
<a href="exam_type_create_delete.php?id='.$xid.'&admin='.$empno.'&tnkc='.$tnkc.'" style="text-decoration:none; color:#0000FF">Delete</a> &nbsp;&nbsp;&nbsp;|';
*/
echo '
</td>
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
