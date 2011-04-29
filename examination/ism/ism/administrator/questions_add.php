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

$dbcategory = $_GET['category'];
$idx = $_GET['id'];
$idtypex = $_GET['idtype'];
$id_vtype = $_GET['vtype'];


$viewing_principal=mysql_query("Select id, principal from principal where id ='$tnkc'");
$viewing_principal_rows=mysql_num_rows($viewing_principal);
if ($viewing_principal_rows != '0')
{
$viewid=mysql_result($viewing_principal,0,"id");
$viewprincipal=mysql_result($viewing_principal,0,"principal");
}

$vav=mysql_query("Select id, type from type where id='$idx'");
if (mysql_num_rows($vav) > 0)
{
$examid=mysql_result($vav,0,"id");
$examnamem=mysql_result($vav,0,"type");
}

$vavpbw=mysql_query("Select id, type from pbw where id='$idtypex'");
if (mysql_num_rows($vavpbw) > 0)
{
$exampbwid=mysql_result($vavpbw,0,"id");
$exampbwtype=mysql_result($vavpbw,0,"type");
}

$vavvtype=mysql_query("Select id, type from pbw where id='$id_vtype'");
if (mysql_num_rows($vavvtype) > 0)
{
$idvtype=mysql_result($vavvtype,0,"id");
$typevtype=mysql_result($vavvtype,0,"type");
}

$newcategory=mysql_query("Select id, cat from category where id='$dbcategory'");
if (mysql_num_rows($newcategory) > 0)
{
$dbcategory=mysql_result($newcategory,0,"id");
$dbcategorys=mysql_result($newcategory,0,"cat");
}

if ($_POST['submit'])
{
$error=array();
$dbcategory = $_POST['dbcategory'];
$examname = $_POST['examname'];
$vtype = $_POST['vtype'];
$txtquestion = $_POST['txtquestion'];
$txta = $_POST['txta'];
$txtb = $_POST['txtb'];
$txtc = $_POST['txtc'];
$txtd = $_POST['txtd'];
$option_a = $_POST['option_a'];


if (trim($examname) == '') { $error[0] = "<div class='warning_message'>Select Type of Exam</div>"; }
if (trim(($dbcategory) == '')||($dbcategory == 'Select Category')) { $error[1] = "<div class='warning_message'>Select Category</div>"; }
if (trim($vtype) == '') { $error[2] = "<div class='warning_message'>Select Vessel Type</div>"; }

if (trim($txtquestion) != '')
{
	if (preg_match("/['\"]/", $txtquestion))
	{
		$error[3] = "<div class='warning_message'>Please do not use Quotation Mark</div>";
	}
}
elseif (trim($txtquestion) == '') { $error[3] = "<div class='warning_message'>Enter Question</div>"; }


if (trim($txta) != '')
{
	if (preg_match("/['\"]/", $txta))
	{
		$error[4] = "<div class='warning_message'>Please do not use Quotation Mark</div>";
	}
}
elseif (trim($txta) == '') { $error[4] = "<div class='warning_message'>This is required field</div>"; }

if (trim($txtb) != '')
{
	if (preg_match("/['\"]/", $txtb))
	{
		$error[5] = "<div class='warning_message'>Please do not use Quotation Mark</div>";
	}
}
elseif (trim($txtb) == '') { $error[5] = "<div class='warning_message'>This is required field</div>"; }

if (preg_match("/['\"]/", $txtc))
{
	$error[6] = "<div class='warning_message'>Please do not use Quotation Mark</div>";
}

if (preg_match("/['\"]/", $txtd))
{
	$error[7] = "<div class='warning_message'>Please do not use Quotation Mark</div>";
}


if (trim($option_a) == 'A') {
$xoption = "A";
}
elseif (trim($option_a) == 'B') {
$xoption = "B";
}
elseif (trim($option_a) == 'C') {
$xoption = "C";
}
elseif (trim($option_a) == 'D') {
$xoption = "D";
}

if (sizeof($error) == 0)
{

$dbquery = "insert into questions values ('','$empno', '$txtquestion', '$txta', '$txtb', '$txtc', '$txtd', '$xoption', '$dbcategory', '$examname', '$tnkc','$vtype')";
$dbresult = mysql_query($dbquery) or die ("Error in query: $query. " . mysql_error());
echo "<script type='text/javascript'>alert('Record Saved!')</script>" ;
echo "
<script type='text/javascript'>
window.location.href='questions_add.php?empno=" . $empno . "&category=". $dbcategory ."&id=". $examname ."&vtype=". $vtype ."&tnkc=". $tnkc ."&idtype=". $idtypex ."';
</script>
";
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
<title>Questionaires</title>
<link rel="stylesheet" type="text/css" href="../style/body.css" />
<link rel="stylesheet" type="text/css" href="../style/text_style.css" />
<SCRIPT language=JavaScript>
function reload(form)
{

var val=form.examname.options[form.examname.options.selectedIndex].value;
var val2=form.vtype.options[form.vtype.options.selectedIndex].value;
self.location='questions_add.php?empno=<?php echo $empno; ?>&tnkc=<?php echo $tnkc; ?>&id=' + val + '&idtype=' + val2;

}
</script>
</head>
<body topmargin="10" leftmargin="15" background="../images/background.gif">

<table width=101% height=100% border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
    	<td height="497" align="center" valign="top"><table width="800" height="388" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" background="../images/sideline.gif">
          <tr>
            <td width="800" height="105" align="left" background="../images/top.gif"></td>
          </tr>
          <tr>
            <td height="52" align="left"><table width="755" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="79">&nbsp;</td>
                  <td width="268" align="left"><img src="../images/title.gif" width="257" height="49" /></td>
                  <td width="42">&nbsp;</td>
                  <td width="366"> <?php
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
                <td height="17" align="right">
                <a href="admin_page.php?empno=<?php echo $empno; ?>&tnkc=<?php echo $tnkc; ?>" style="text-decoration:none; color:#0033FF">Home</a>
                &nbsp;&nbsp;&nbsp;
                <a href='questions_setting.php?empno=<?php echo $empno; ?>&tnkc=<?php echo $tnkc; ?>' style="color:#0033FF; text-decoration:none">Search</a>
                &nbsp;&nbsp;&nbsp;
                <a href="../connection/admin_logout.php" style="text-decoration:none; color:#0033FF">Log Out</a>
                </td>
              </tr>
              <tr>
                <td height="5"></td>
              </tr>
              <tr>
                <td width="629" height="47" align="left" valign="top"><table width="629" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="1" height="32"><img src="../images/gray_side.gif" width="1" height="32"/></td>
                      <td width="639" height="32" background="../images/gray_mid.gif">
                      <div style="font-size:14px">&nbsp;&nbsp;&nbsp;&nbsp;Questionaires
                      </div>
                      </td>
                      <td width="1" height="32"><img src="../images/gray_side.gif" width="1" height="32"/></td>
                    </tr>
                  </table>
                  <br />
                    </td>
              </tr>
              <tr>
                <td height="147" align="center"><table width="467" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td width="122" align="right">Type of Exam : </td>
                    <td width="20">&nbsp;</td>
                    <td width="325" align="left">
<div>

<?php
$dbexamtype="Select id, type from type order by type asc";
$dbrexamtype=mysql_query($dbexamtype);
echo "<select name=examname style=\"font-size:12px; width:250px; font-family:Verdana;\" onchange=\"reload(this.form)\"> ";
if ($examnamem=="")
{
echo "<option value=''></option>";
}
else
{
echo "<option value=".$examid.">".$examnamem."</option>";
}
while($row = mysql_fetch_assoc($dbrexamtype))
{
echo "<option value=\"{$row['id']}\" style='color:#666666'>{$row['type']}</option> ";
}
echo "</select> ";
?>
                      </div>
                        <div><?php echo $msg0; ?></div></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td align="right">Vessel Type: </td>
                    <td>&nbsp;</td>
                    <td align="left">
                    
                    
<?php
$vtype_combo="Select distinct pbw.id, pbw.type from pbw, exam_cat where exam_cat.examname='$examnamem' and  principal = '$viewid' and exam_cat.type = pbw.id order by pbw.type asc";
$vtype_combo2=mysql_query($vtype_combo);
echo "<select name=vtype style=\"font-size:12px; width:105px; font-family:Verdana;\" onchange=\"reload(this.form)\"> ";
if ($vtype_combo2=="")
{
echo "<option value=''></option>";
}
else
{
echo "<option value=".$exampbwid.">".$exampbwtype."</option>";
}
while($rowvtype = mysql_fetch_assoc($vtype_combo2))
{
echo "<option value=\"{$rowvtype['id']}\" style='color:#666666'>{$rowvtype['type']}</option> ";
}
echo "</select> ";
?>
                    
 <div><?php echo $msg2; ?></div></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td align="right">Category of Exam : </td>
                    <td>&nbsp;</td>
                    <td align="left">
<div>
<input type="hidden" name="tnkc" value="<?php $tnkc; ?>" />
<select name="dbcategory" style="font-size:11px; width:325px; font-family:Verdana;" id="dbcategory">
<option value="<?php echo $dbcategory; ?>"><?php echo $dbcategorys; ?></option>
<?php 
$dbcategoryb="Select category.id, category.cat from category, exam_cat where exam_cat.examname='$examnamem' and  principal = '$viewid' and exam_cat.type = '$idtypex' and exam_cat.idcat=category.id and category.stat='0' order by category.cat asc";
$dbrcategoryb=mysql_query($dbcategoryb);
if (mysql_num_rows($dbrcategoryb) > 0)
{
while($rowc = mysql_fetch_row($dbrcategoryb))
{
echo '<option value="'. $rowc[0] .'">' . $rowc[1] . '</option>'; 
} 
}
else
{
echo '<option style="color:#666666" value="Select Category">Select Category</option>'; 
}
?>
                      </select>
                   </div>
                   <div><?php echo $msg1; ?></div></td>
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
                    <td colspan="3" height="22" align="left"><strong style="font-size:13px">Question</strong></td>
                  </tr>
                  <tr>
                    <td colspan="3" align="center" valign="top"><textarea name="txtquestion" id="txtquestion" style="font-size:12px; width:460px; height:120px"></textarea>
                      <div><?php echo $msg3; ?></div></td>
                    </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td colspan="3" align="center"><span style="color:#0000FF; font-size:10px">Note: Use the RADIO BUTTON to select the correct answer.</span></td>
                    </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td align="right"><strong>Option A</strong> : </td>
                    <td>&nbsp;</td>
                    <td align="left"><div>
                        <input name="txta" type="text" id="txta" style="font-size:12px; width:207px" size="40" height="12"/>
                    <input type="radio" name="option_a" value="A" checked="checked"/></div><div><?php echo $msg4; ?></div></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td align="right"><strong>Option B</strong> : </td>
                    <td>&nbsp;</td>
                    <td align="left"><div>
                        <input name="txtb" type="text" id="txtb" style="font-size:12px; width:207px" size="40" height="12"/>
                        <input type="radio" name="option_a" value="B" />
                    </div><div><?php echo $msg5; ?></div></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td align="right"><strong>Option C</strong> : </td>
                    <td>&nbsp;</td>
                    <td align="left"><div>
                        <input name="txtc" type="text" id="txtc" style="font-size:12px; width:207px" size="40" height="12"/>
                        <input type="radio" name="option_a" value="C" />
                    </div><div><?php echo $msg6; ?></div><div>(Optional)</div></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td align="right"><strong>Option D</strong> : </td>
                    <td>&nbsp;</td>
                    <td align="left"><div>
                      <input name="txtd" type="text" id="txtd" style="font-size:12px; width:207px" size="40" height="12"/>
                      <input type="radio" name="option_a" value="D" />
                    </div><div><?php echo $msg7; ?></div><div>(Optional)</div></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
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
