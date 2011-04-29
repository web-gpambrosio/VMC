<?php
session_start();
if(!session_is_registered(empno))
{
header("location:index.php");
}
if ((!isset($_GET['admin']) || trim($_GET['admin']) == '')&&(!isset($_GET['id']) || trim($_GET['id']) == '')&&(!isset($_GET['tnkc']) || trim($_GET['tnkc']) == ''))
{
header("location:index.php");
}
include('../connection/conn.php');
$empno=$_GET['admin'];
$xyzid=$_GET['id'];
$tnkc=$_GET['tnkc'];

$xcode="Select * from examtype where id='$xyzid' and principal='$tnkc'";
$query_result=mysql_query("$xcode",$conn);	
$id=mysql_result($query_result,0,"id");
$examname=mysql_result($query_result,0,"examname");
$totalno=mysql_result($query_result,0,"totalno");
$vtype=mysql_result($query_result,0,"type");

$principal_query=mysql_query("select principal from principal where id='$tnkc'",$conn);
$principal_query_row=mysql_num_rows($principal_query);
if ($principal_query_row !="0")
{
	$for_principal=mysql_result($principal_query,0,"principal");
}

$vtype_result_inside=mysql_query("Select type from pbw where id = '$vtype'");
$xtypex=mysql_result($vtype_result_inside,0,"type");


$sum_total_questions=mysql_query("Select sum(total) from exam_cat where examname = '$examname' and principal='$tnkc' and type='$vtype'",$conn);
$sum_total_questions_result=mysql_result($sum_total_questions,0,"sum(total)");

if ($sum_total_questions_result == "")
{
	$sum_total_questions_result = '0';
}


if ($_POST['submit'])
{
$error=array();
$xcategory = $_POST['xcategory'];
$xmanagement = $_POST['xmanagement'];
$xoperational = $_POST['xoperational'];
$xsupport = $_POST['xsupport'];

$xtotal = ($_POST['xmanagement'] + $_POST['xoperational'] + $_POST['xsupport']);
$xmax = $xtotal;

$xqm="Select idcat from exam_cat where examname='$examname' and idcat='$xcategory' and principal='$tnkc' and type='$vtype'";
$yqm=mysql_query("$xqm");
$p=mysql_num_rows($yqm);
if ($p!='0') { $error[0] = "<div class='warning_message'>Duplicate Entry</div>"; }

if (sizeof($error) == 0)
{
$queryv = "insert into exam_cat (examname, idcat, management, operational, support, total, max, principal, type) values ('$examname', '$xcategory', '$xmanagement', '$xoperational', '$xsupport', '$xtotal', '$xmax', '$tnkc', '$vtype')";
$resultv = mysql_query($queryv) or die ("Error in query: $query. " . mysql_error());

$a=mysql_query("Select sum(total) from exam_cat where examname = '$examname' and principal='$tnkc' and type='$vtype'",$conn);
$ax=mysql_result($a,0,"sum(total)");

mysql_query("update examtype set totalno='$ax' where examname = '$examname' and principal='$tnkc' and type='$vtype'",$conn);

echo "<script type='text/javascript'>alert('Record Saved!')</script>" ;
echo "<script language=\"javascript\">window.location.href='select_category.php?admin=" . $empno . "&id=" . $xyzid . "&tnkc=" . $tnkc . "'</script>";

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
<title>Select Category for <?php echo $examname; ?></title>
<link rel="stylesheet" type="text/css" href="../style/body.css" />
<link rel="stylesheet" type="text/css" href="../style/text_style.css" />
<SCRIPT LANGUAGE="JavaScript">
function a_pow_b(form) {
a=eval(form.a.value)
b=eval(form.b.value)
c=Math.pow(a, b)
form.ans.value = c
}

</script>
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
                  <td width="366"><img src="../images/cat.gif" width="302" height="33" /></td>
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
                <a href="exam_type.php?empno=<?php echo $empno; ?>&tnkc=<?php echo $tnkc; ?>" style="text-decoration:none; color:#0033FF">Back</a></td>
              </tr>
              <tr>
                <td height="5"></td>
              </tr>
              <tr>
                <td width="629" height="47" align="left" valign="top"><table width="629" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="1" height="32"><img src="../images/gray_side.gif" width="1" height="32"/></td>
                      <td width="639" height="32" background="../images/gray_mid.gif">
                      <div style="font-size:14px">&nbsp;&nbsp;&nbsp;&nbsp;For <span style="color:#0000FF"><strong><?php echo $for_principal; ?> : <?php echo $xtypex. " DIVISION"; ?> : <?php echo $examname; ?></strong></span> Exam</div>
                      </td>
                      <td width="1" height="32"><img src="../images/gray_side.gif" width="1" height="32"/></td>
                    </tr>
                  </table>
                  <br />
                    </td>
              </tr>
              <tr>
                <td height="29" align="center">
                <table width="505" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td width="121" align="right">Category : </td>
                    <td width="8">&nbsp;</td>
                    <td width="376" align="left">
<select name="xcategory" style="font-size:12px; font-family:Verdana;" id="xcategory">
<?php 
$dbcategory="Select id, cat from category where stat='0' order by cat asc";
$dbrcategory=mysql_query($dbcategory);
if (mysql_num_rows($dbrcategory) > 0)
{
while($rowc = mysql_fetch_row($dbrcategory))
{
echo '<option style="color:#333333" value="'. $rowc[0] .'">' . $rowc[1] . '</option>'; 
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
                    <td align="right">Mangement Level : </td>
                    <td>&nbsp;</td>
                    <td align="left"><input value="0" maxlength="3" onkeypress="if (event.keyCode &lt; 45 || event.keyCode &gt; 57) event.returnValue = false;" name="xmanagement" type="text" id="xmanagement" style="font-size:12px; width:25px; text-align:right" size="40" height="12"/>                      </td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td align="right">Operational Level : </td>
                    <td>&nbsp;</td>
                    <td align="left"><input value="0" maxlength="3" onkeypress="if (event.keyCode &lt; 45 || event.keyCode &gt; 57) event.returnValue = false;" name="xoperational" type="text" id="xoperational" style="font-size:12px; width:25px; text-align:right" size="40" height="12"/>                      </td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td align="right">Support Level: </td>
                    <td>&nbsp;</td>
                    <td align="left"><input value="0" maxlength="3" onkeypress="if (event.keyCode &lt; 45 || event.keyCode &gt; 57) event.returnValue = false;" name="xsupport" type="text" id="xsupport" style="font-size:12px; width:25px; text-align:right" size="40" height="12"/>                      </td>
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
                <table width="611" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td colspan="3" height="8"></td>
                    </tr>
                    <tr>
                      <td width="23"></td>
                      <td width="295" align="left" ></td>
                      <td width="293" align="left"><img src="../images/category.gif" width="293" height="86" /></td>
                    </tr>
                    <tr>
                      <td colspan="3" height="1" bgcolor="#666666"></td>
                    </tr>
                    <tr>
                      <td colspan="3" height="5"></td>
                    </tr>
<?php
$xq="Select * from exam_cat where examname='$examname' and principal='$tnkc' and type='$vtype'";
$yq=mysql_query("$xq",$conn);
$num_row=mysql_num_rows($yq);
if ($num_row=='0')
{
echo '<tr><td height="20" valign="top" colspan="3">Empty Record</td></tr>';
echo '<tr><td align="left" height="1" colspan="3" bgcolor="#666666"></td></tr>';
echo '<tr><td align="left" height="5" colspan="3"></td></tr>';
}
else
{
$i=0;
while($i < $num_row)
{
$ii = $i+1;
			$zid=mysql_result($yq,$i,"id");
			$zexam=mysql_result($yq,$i,"examname");
			$zidcat=mysql_result($yq,$i,"idcat");
			$zmanagement=mysql_result($yq,$i,"management");
			$zoperational=mysql_result($yq,$i,"operational");
			$zsupport=mysql_result($yq,$i,"support");
			$ztotal=mysql_result($yq,$i,"total");
			$zmax=mysql_result($yq,$i,"max");
			
			$v="Select cat from category where id='$zidcat'";
			$b=mysql_query("$v");
			$xcatx=mysql_result($b,0,"cat");

echo '
<tr>
<td align="left" height="25" valign="top">'. $ii .'.
</td>
<td align="left" valign="top"><strong>
<span style="color:#666666; text-decoration:none">'.$xcatx.'</span></strong>

</td>
<td align="left" valign="top">
<table width="293" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="12"></td>
<td width="55" align="left"><input value="'. $zmanagement .'" maxlength="3" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" name="management'. zid .'" type="text" id="management'. zid .'" style="font-size:12px; width:25px; text-align:right" size="40" height="12" readonly/></td>

<td width="55" align="left"><input value="'. $zoperational .'" maxlength="3" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" name="operational'. zid .'" type="text" id="operational'. zid .'" style="font-size:12px; width:25px; text-align:right" size="40" height="12" readonly/></td>

<td width="55" align="left"><input value="'. $zsupport .'" maxlength="3" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" name="support'. zid .'" type="text" id="support'. zid .'" style="font-size:12px; width:25px; text-align:right" size="40" height="12" readonly/></td>

<td width="55" align="left"><input value="'. $ztotal .'" maxlength="3" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" name="total'. zid .'" type="text" id="total'. zid .'" style="font-size:12px; width:25px; text-align:right" size="40" height="12" readonly/></td>

<td width="55" align="left"><input value="'. $zmax .'" maxlength="3" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" name="max'. zid .'" type="text" id="max'. zid .'" style="font-size:12px; width:25px; text-align:right" size="40" height="12" readonly/></td>

<td width="10">
<a href="select_category_delete.php?id='.$xyzid.'&type='.$vtype.'&admin='.$empno.'&idcat='.$zidcat.'&examname='.$examname.'&tnkc='.$tnkc.'" style="text-decoration:none; color:#0000FF">
<img src="../images/x.gif" border="0" width="14" height="14"/>
</a>
</td>
<tr>
</table>
</td>
</tr>';
echo '<tr><td align="left" height="1" colspan="3" bgcolor="#666666"></td></tr>';
echo '<tr><td align="left" height="5" colspan="3"></td></tr>';
$i++;
}
}
?>		

                      <tr>
                      <td height="24" colspan="3" align="right"><table width="282" height="23" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="168" align="left"><span style="color:#FF0000; font-size:14px">Total Number of Item: </span></td>
    <td width="53" align="left">&nbsp;<span style="color:#FF0000; font-size:14px"><strong><?php echo $sum_total_questions_result; ?></strong></span></td>
    <td width="61">&nbsp;</td>
  </tr>
</table>
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
