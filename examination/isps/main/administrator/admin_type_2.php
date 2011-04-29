<?php
session_start();
include('includes/myfunction.php');
include('../../includes/conn.php');

if ((!isset($_GET['empno']) || trim($_GET['empno']) == ''))
{ header("location:../../index.php"); }

$empno=$_GET['empno'];
include('includes/myname.php');
include('includes/inc.php');

$xid=$_GET['id'];

	$etype=mysql_query("select type from type where id='$xid'",$conn);
	$etype02=mysql_num_rows($etype);
		if ($etype02 != '0')
		{
			$e_type=mysql_result($etype,0,"type");
		}

if ($_POST['submit'])
{
$error=array();
$examname = $_POST['txttype'];

if (trim($examname) != '')
{
	$examname_examtype=mysql_query("Select type from type where type='$examname'",$conn);
	$examname_examtype02=mysql_num_rows($examname_examtype);
		if ($examname_examtype02 != '0')
		{
			$error[0] = "<div class='warning_message'>Duplicate Entry</div>";
		}
}
elseif (trim($examname) == '') { $error[0] = "<div style='color:#FF0000; font-size:10px'>Enter Examination Name</div>"; }

if (sizeof($error) == 0)
{
$queryv = "update type set type='$examname' where id='$xid'";
$resultv = mysql_query($queryv) or die ("Error in query: $queryv. " . mysql_error());
echo "<script type='text/javascript'>alert('Record has been Updated!')</script>" ;
echo "<script language=\"javascript\">window.location.href='admin_type.php?empno=".$empno."'</script>";
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
              <td height="158" align="center"><table width="457" border="0" cellspacing="0" cellpadding="0">
                  <tr>
  <td height="32" bgcolor="#eeeeee" style="border:1px solid #999999" align="center" colspan="3"><div class="black"><strong>EDIT - <strong style="color:#0000FF; text-decoration:underline"><?php echo $e_type; ?></strong> - TYPE OF EXAMINATION</strong></div></td>
   </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td width="184" align="right">Examination Name: </td>
                    <td width="25">&nbsp;</td>
                    <td width="248" align="left"><input name="txttype" type="text" value="<?php echo $e_type; ?>" style="width:200px"/>
                    <div><?php echo $msg0; ?></div></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td colspan="3" align="left"><table width="390" height="20" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td width="211">&nbsp;</td>
                          <td width="179">
                          <input type="submit" name="submit" value="  Save  "/>
                          &nbsp;&nbsp;
                          <input type="button" name="submit2" value="  Cancel  " onClick="javascript:location.href='<?php echo "admin_type.php?empno=".$empno ?>'"/></td>
                        </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="30"></td>
                  </tr>
                </table>
                  <table width="617" border="0" cellspacing="0" cellpadding="0">

                    <tr>
                      <td colspan="5" height="5"></td>
                    </tr>
                    <tr>
                      <td width="18"></td>
                      <td width="223"></td>
                      <td width="133" align="left"></td>
                      <td width="63" align="left"></td>
                      <td width="180">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="5" height="5"></td>
                    </tr>
                    <tr>
                      <td colspan="5" height="1" bgcolor="#666666"></td>
                    </tr>
                    <tr>
                      <td colspan="5" height="1"></td>
                    </tr>
                    <tr>
                      <td colspan="5" height="1" bgcolor="#666666"></td>
                    </tr>
                    <tr>
                      <td colspan="5" height="5"></td>
                    </tr>
<?php
$yq=mysql_query("select id, type, sync from type order by id",$conn);
$num_row=mysql_num_rows($yq);
if ($num_row=='0')
{
echo '
<tr>
<td height="20" valign="top" colspan="5">Empty Record</td>
</tr>';
}
else
{
$i=0;
while($i < $num_row)
{
$ii = $i+1;
			$xid=mysql_result($yq,$i,"id");
			$xav=mysql_result($yq,$i,"type");
			$xaz=mysql_result($yq,$i,"sync");
			if ($xaz == '0') $xazz = "000000"; else $xazz = "999999";
			
echo '
<tr>
<td align="left" height="24" valign="top"><span style="color:#aaaaaa">'.$ii.'.</span></td>
<td align="left" valign="top">
	<strong><div style="color:#'.$xazz.';">'.$xav.'</div></strong>
</td>
<td align="left" valign="top"></td>
<td align="left" valign="top"></td>
<td align="right" valign="top">';
	
	if ($xaz == '0')
	{
	echo '
	<a href="admin_type_2.php?id='.$xid.'&empno='.$empno.'" onclick="NewWindow(this.href,\'name\',\'500\',\'150\',\'yes\');return false" style="text-decoration:none; color:#0000FF">
		<img src="../../images/edit.jpg" width="16" height="16" border="0"/>
	</a> &nbsp;&nbsp;
	
	<a href="admin_type_1.php?id='.$xid.'&admin='.$empno.'" style="text-decoration:none; color:#0000FF">
		<img src="../../images/failed.gif" width="16" height="16" border="0"/>
	</a> &nbsp;&nbsp';
	}
	else
	{
	echo '<a href="admin_type_1.php?id='.$xid.'&admin='.$empno.'" style="text-decoration:none; color:#0000FF">
		<img src="../../images/passed.gif" width="16" height="16" border="0"/>
	</a> &nbsp;&nbsp;';
	}
echo '</td>
</tr>';
echo '<tr><td align="left" height="1" colspan="5" bgcolor="#666666"></td></tr>';
echo '<tr><td align="left" height="5" colspan="5"></td></tr>';
$i++;
}
}
?>
                    <tr>
                      <td colspan="5" height="27"></td>
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
