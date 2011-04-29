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

$txtvessel = $_POST['txtvessel'];
$type = $_POST['type'];

if (trim($txtvessel) == '') { $error[0] = "<div class='warning_message'>Enter Vessel Name</div>"; }
if (trim($type) == '') { $error[1] = "<div class='warning_message'>Select Type of Vessel</div>"; }

if (sizeof($error) == 0)
{
$query_vessel = "insert into vessel (principal, vessel, type) values ('$tnkc', '$txtvessel', '$type')";
$result_vessel = mysql_query($query_vessel) or die ("Error in query: $query. " . mysql_error());
echo "<script type='text/javascript'>alert('Record Saved!')</script>" ;
echo "<script language=\"javascript\">window.location.href='exam_principal.php?empno=" . $empno . "&tnkc=" . $tnkc . "'</script>";

mysql_close($conn);
}
else
{
for ($x=0; $x<sizeof($error); $x++)
{
$msg0 = $error[0];
$msg1 = $error[1];
}
}
}

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Vessel Type</title>
<link rel="stylesheet" type="text/css" href="../style/body.css" />
<link rel="stylesheet" type="text/css" href="../style/text_style.css" /></head>
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
                  <td width="366">&nbsp;</td>
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
                <td height="17" align="right"><a href="admin_page.php?empno=<?php echo $empno; ?>&tnkc=<?php echo $tnkc; ?>" style="text-decoration:none; color:#0033FF">Home</a> &nbsp;&nbsp;&nbsp; <a href="../connection/admin_logout.php" style="text-decoration:none; color:#0033FF">Log Out</a> <a href="admin_page.php?empno=<?php echo $empno; ?>" style="text-decoration:none; color:#0033FF"></a></td>
              </tr>
              <tr>
                <td height="5"></td>
              </tr>
              <tr>
                <td width="629" height="47" align="left" valign="top"><table width="629" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="1" height="32"><img src="../images/gray_side.gif" width="1" height="32"/></td>
                      <td width="639" height="32" background="../images/gray_mid.gif">
                      <div style="font-size:14px">&nbsp;&nbsp;&nbsp;&nbsp;Principal and Vessel</div>
                      </td>
                      <td width="1" height="32"><img src="../images/gray_side.gif" width="1" height="32"/></td>
                    </tr>
                  </table>
                  <br />
                    </td>
              </tr>
              <tr>
                <td height="158" align="center"><table width="382" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td width="131" align="right">Vessel : </td>
                    <td width="22">&nbsp;</td>
                    <td width="246" align="left">
<input value="<?php echo $txtvessel; ?>" name="txtvessel" type="text" id="txtvessel" style="font-size:12px; width:228px" size="49" height="12"/>
                        <div><?php echo $msg0; ?></div></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td width="131" align="right">Type : </td>
                    <td width="22">&nbsp;</td>
                    <td width="246" align="left">
<select name="type" id="type" style="font-size:12px; width:95px">
<option value=""></option>
<?php 
$vtype_combo2=mysql_query("select id, type from pbw order by id asc");
if (mysql_num_rows($vtype_combo2) > 0)
{
while($vtype_combo_row = mysql_fetch_row($vtype_combo2))
{
echo '<option value="'. $vtype_combo_row[0] .'">' . $vtype_combo_row[1] . '</option>'; 
} 
}
?>
</select>
<div><?php echo $msg1; ?></div></td>
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
                    <td colspan="3" height="22"></td>
                  </tr>
                </table>
                  <table width="581" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="36"></td>
                      <td width="157" align="left"><strong>SHIP MANAGEMENT</strong></td>
                      <td width="309" align="left"><strong>VESSELS</strong></td>
                      <td width="79"></td>
                    </tr>
                    <tr>
                      <td colspan="4" height="8"></td>
                    </tr>
                    <tr>
                      <td colspan="4" height="1" bgcolor="#666666"></td>
                    </tr>
                    <tr>
                      <td colspan="4" height="5"></td>
                    </tr>
<?php
$vessel_query=mysql_query("Select * from vessel where principal='$tnkc' order by principal, vessel asc",$conn);
$vessel_query_row=mysql_num_rows($vessel_query);
if ($vessel_query_row=='0')
{
echo '
<tr>
<td height="20" valign="top" colspan="4" align="center">Empty Record</td>
</tr>';
}
else
{
$vesseli=0;
while($vesseli < $vessel_query_row)
{
$vesselii = $vesseli+1;

			$xidrow=mysql_result($vessel_query,$vesseli,"id");
			$vesselrow=mysql_result($vessel_query,$vesseli,"vessel");
			$principalrow=mysql_result($vessel_query,$vesseli,"principal");
			$typerow=mysql_result($vessel_query,$vesseli,"type");
			
			switch($typerow)
			{
				case "1": $mycolorrow = "000000"; break;
				case "2": $mycolorrow = "0000FF"; break;
				case "3": $mycolorrow = "FF0000"; break;
				default:  $mycolorrow = "000000"; break;
			}
			
			
			$viewing_principal=mysql_query("Select principal from principal where id ='$principalrow'");
			$viewing_result=mysql_result($viewing_principal,0,"principal");
echo '
<tr>
<td align="left" height="20" valign="top"><span style="color:#aaaaaa">'.$vesselii.'.</span></td>
<td align="left" valign="top">
<span style="color:#006600; text-decoration:none">'.$viewing_result.'</span>

</td>
<td align="left" valign="top">
<span style="color:#'.$mycolorrow.'; text-decoration:none">'.$vesselrow.'</span>
</td>
<td><a href="exam_principal_delete.php?id='.$xidrow.'&admin='.$empno.'&tnkc='.$tnkc.'" style="text-decoration:none; color:#0000FF">Delete</a></td>
</tr>';
echo '<tr><td align="left" height="1" colspan="4" bgcolor="#666666"></td></tr>';
echo '<tr><td align="left" height="5" colspan="4"></td></tr>';
$vesseli++;
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
