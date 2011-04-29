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
$examdate = date("F j, Y");
  	switch ($tnkc)
	{
		case "1": $for_principal = "TNKC KOBE"; break;
		case "2": $for_principal = "TNKC MANILA"; break;
		case "3": $for_principal = "STARGATE BREMEN"; break;
		default: $for_principal = "Error: Please Contact VMC I.T Staff to fix this Problem. Thank you!"; break;
	}
if ($_POST['submit'])
{
$error=array();
$examname = $_POST['examname'];
$notime = $_POST['notime'];
$vtype = $_POST['vtype'];
if (trim($examname) != '')
{
	$examname_examtype=mysql_query("Select examname from examtype where examname='$examname' and principal='$tnkc' and type='$vtype'",$conn);
	$examname_examtype02=mysql_num_rows($examname_examtype);
		if ($examname_examtype02 != '0')
		{
			$error[0] = "<div class='warning_message'>Duplicate Entry</div>";
		}
}
elseif (trim($examname) == '') { $error[0] = "<div class='warning_message'>Select Examination Name</div>"; }
if (trim($vtype) == '') { $error[1] = "<div class='warning_message'>Select Vessel Type</div>"; }
if (trim($notime) == '') { $error[2] = "<div class='warning_message'>Select Time Limit</div>"; }
if (sizeof($error) == 0)
{
$queryv = "insert into examtype (examname, whocreated, examdate, totaltime, principal, type) values ('$examname', '$empno', '$examdate', '$notime', '$tnkc', '$vtype')";
$resultv = mysql_query($queryv) or die ("Error in query: $query. " . mysql_error());
echo "<script type='text/javascript'>alert('Record Saved!')</script>" ;

	$werwerqwer=mysql_query("select id from examtype where principal='$tnkc' and type='$vtype' order by id desc limit 1",$conn);
	$werwerqwerrxxx=mysql_num_rows($werwerqwer);
		if ($werwerqwerrxxx > 0)
		{
			$idxxxxx=mysql_result($werwerqwer,0,"id");
			echo "<script language=\"javascript\">window.location.href='select_category.php?id=".$idxxxxx."&admin=".$empno."&tnkc=".$tnkc."'</script>";
		}
		else
		{
			echo "<script language=\"javascript\">window.location.href='exam_type.php?empno=" . $empno . "&tnkc=" . $tnkc . "'</script>";
		}
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
                <a href="admin_page.php?empno=<?php echo $empno; ?>&tnkc=<?php echo $tnkc; ?>" style="text-decoration:none; color:#0033FF">Home</a> &nbsp;&nbsp;&nbsp; <a href="exam_type_create.php?empno=<?php echo $empno; ?>&tnkc=<?php echo $tnkc; ?>" style="text-decoration:none; color:#0033FF">Type of Exam</a> &nbsp;&nbsp;&nbsp; 
                <a href="../connection/admin_logout.php" style="text-decoration:none; color:#0033FF">Log Out</a>                </td>
              </tr>
              <tr>
                <td height="5"></td>
              </tr>
              <tr>
                <td width="629" height="47" align="left" valign="top"><table width="629" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="1" height="32"><img src="../images/gray_side.gif" width="1" height="32"/></td>
                      <td width="639" height="32" background="../images/gray_mid.gif">
                      <div style="font-size:14px">&nbsp;&nbsp;&nbsp;&nbsp;Type of Examination for <span style="color:#0000FF; font-size:14px"><strong><?php echo $for_principal; ?></strong></span></div>
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
                    <td align="right">Type of Exam: </td>
                    <td>&nbsp;</td>
                    <td align="left">
                    
<select name="examname" id="examname" style="font-size:12px;">
<option></option>
<?php 
$examname_combo="Select type from type order by id asc";
$examname_combo2=mysql_query($examname_combo);
if (mysql_num_rows($examname_combo2) > 0)
{
while($examname_combo_row = mysql_fetch_row($examname_combo2))
{
echo '<option value="'. $examname_combo_row[0] .'">' . $examname_combo_row[0] . '</option>'; 
} 
}
?>
</select>
<div><?php echo $msg0; ?></div></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td align="right">Vessel Type : </td>
                    <td>&nbsp;</td>
                    <td align="left">
<select name="vtype" id="vtype" style="font-size:12px; width:95px">
<option></option>
<?php 
$vtype_combo2=mysql_query("Select id, type from pbw order by id asc");
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
                    <td width="131" align="right">Time Limit : </td>
                    <td width="22">&nbsp;</td>
                    <td width="246" align="left">
                    <select name="notime" id="notime" style="font-size:12px; width:95px">
                    <option></option>
                    <option value="30 Minutes">30 Minutes</option>
                    <option value="45 Minute">45 Minutes</option>
                    <option value="60 Minutes">60 Minutes</option>
                    <option value="90 Minutes">90 Minutes</option>
                    <option value="120 Minutes">120 Minutes</option>
                    </select>
                    <div><?php echo $msg2; ?></div></td>
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
                    <td colspan="3" height="10"></td>
                  </tr>
                </table>
                  <table width="617" border="0" cellspacing="0" cellpadding="0">
                  
                  <tr><td colspan="5" height="1" bgcolor="#666666"></td></tr>
                  <tr>
                      <td colspan="5" height="5"></td>
                    </tr>
                    <tr>
                      <td width="18"></td>
                      <td width="223"></td>
                      <td width="133" align="left"><span style="font-size:12px"><strong><em>No. of Questionaires</em></strong></span></td>
                      <td width="95" align="left"><span style="font-size:12px"><strong><em>Time Limit</em></strong></span></td>
                      <td width="148">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="5" height="5"></td>
                    </tr>
                    <tr><td colspan="5" height="1" bgcolor="#666666"></td></tr>
                    <tr><td colspan="5" height="1"></td></tr>
                    <tr><td colspan="5" height="1" bgcolor="#666666"></td></tr>
                    <tr>
                      <td colspan="5" height="5"></td>
                    </tr>
<?php
$xq="Select * from examtype where principal='$tnkc' order by id";
$yq=mysql_query("$xq",$conn);
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
			$xav=mysql_result($yq,$i,"examname");
			$xbv=mysql_result($yq,$i,"totalno");
			$xcv=mysql_result($yq,$i,"totaltime");
			$xdv=mysql_result($yq,$i,"examdate");
			$xtype=mysql_result($yq,$i,"type");
			
			$vtype_result_inside=mysql_query("Select type from pbw where id = '$xtype'");
			$xtypex=mysql_result($vtype_result_inside,0,"type");
			
echo '
<tr>
<td align="left" height="32" valign="top"><span style="color:#aaaaaa">'.$ii.'.</span></td>
<td align="left" valign="top">
	<strong><div style="color:#000000;">'.$xav.'</div></strong>
	<div style="color:#006600; font-size:9px">'.$xtypex.' DIVISION</div>
</td>
<td align="left" valign="top"><span style="font-size:12px; color:#000000"><strong>'. $xbv .' Item(s)</strong></span></td>
<td align="left" valign="top"><span style="font-size:12px; color:#000000"><strong>'. $xcv .'</strong></span></td>
<td align="left" valign="top">
	|&nbsp;&nbsp;&nbsp;
	<a style="text-decoration:none; color:#0000FF" href="select_category.php?id='.$xid.'&admin='.$empno.'&tnkc='.$tnkc.'">
	Category</a>
 &nbsp;&nbsp;| &nbsp;&nbsp;
	<a href="examtype_delete.php?id='.$xid.'&type='.$xtype.'&admin='.$empno.'&tnkc='.$tnkc.'" style="text-decoration:none; color:#0000FF">Delete</a> &nbsp;&nbsp;|
</td>
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
					<tr>
                      <td colspan="5" height="39" align="left" valign="top">
    <div style="color:#FF0000"><strong>&nbsp;NOTE:</strong></div>
    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The <strong>total number of questionaires</strong> per <span style="color:#FF0000">type of exam</span> is base on the <strong>sum of all total questions</strong> per <span style="color:#FF0000">category</span>.</div>
                        </td>
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
