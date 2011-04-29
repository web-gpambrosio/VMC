<?php
session_start();

if(!session_is_registered(empno))
{
header("location:index.php");
}

if ((!isset($_GET['empno']) || trim($_GET['empno']) == '')&&(!isset($_GET['tnkc']) || trim($_GET['tnkc']) == '')&&(!isset($_GET['id']) || trim($_GET['id']) == ''))
{
header("location:index.php");
}
include('../connection/conn.php');
$empno=$_GET['empno'];
$tnkc=$_GET['tnkc'];
$id=$_GET['id'];


$xcode="Select questions.id, questions.question, questions.ans1, questions.ans2, questions.ans3, questions.ans4, questions.anscorrect, questions.category, questions.examtype, questions.principal, questions.type, admin.fname, admin.lname, admin.position from questions, admin where questions.id='$id' and admin.empno=questions.empno";
$query_result=mysql_query("$xcode",$conn);	

$xid=mysql_result($query_result,0,"questions.id");
$question=mysql_result($query_result,0,"questions.question");
$ans1=mysql_result($query_result,0,"questions.ans1");
$ans2=mysql_result($query_result,0,"questions.ans2");
$ans3=mysql_result($query_result,0,"questions.ans3");
$ans4=mysql_result($query_result,0,"questions.ans4");
$anscorrect=mysql_result($query_result,0,"questions.anscorrect");
$category=mysql_result($query_result,0,"questions.category");
$examtype=mysql_result($query_result,0,"questions.examtype");
$fname=mysql_result($query_result,0,"admin.fname");
$lname=mysql_result($query_result,0,"admin.lname");
$position=mysql_result($query_result,0,"admin.position");
$principalno=mysql_result($query_result,0,"questions.principal");
$type=mysql_result($query_result,0,"questions.type");

	$viewing=mysql_query("Select principal.principal As p, pbw.type As t, type.type As y, category.cat As c from principal, pbw, type, category where 
							principal.id = '$principalno' and
							pbw.id = '$type' and 
							category.id = '$category' and
							type.id = '$examtype'							
							");
	$viewing_rows=mysql_num_rows($viewing);
	if ($viewing_rows != '0') {
		$p=mysql_result($viewing,0,"p");
		$v=mysql_result($viewing,0,"t");
		$e=mysql_result($viewing,0,"y");
		$c=mysql_result($viewing,0,"c");
	}
switch ($anscorrect)
{
case "A": $xmark1 = "checked='checked'"; break;
case "B": $xmark2 = "checked='checked'"; break;
case "C": $xmark3 = "checked='checked'"; break;
case "D": $xmark4 = "checked='checked'"; break;
}
	

if ($_POST['submit'])
{
$error=array();
$xid = $_POST['xid'];
$txtquestion = $_POST['txtquestion'];
$txta = $_POST['txta'];
$txtb = $_POST['txtb'];
$txtc = $_POST['txtc'];
$txtd = $_POST['txtd'];

$option_a = $_POST['option_a'];

if (trim($txtquestion) != '')
{
	if (preg_match("/['\"]/", $txtquestion))
	{
		$error[0] = "<div class='warning_message'>Please do not use Quotation Mark</div>";
	}
}
elseif (trim($txtquestion) == '') { $error[0] = "<div class='warning_message'>Enter Question</div>"; }


if (trim($txta) != '')
{
	if (preg_match("/['\"]/", $txta))
	{
		$error[1] = "<div class='warning_message'>Please do not use Quotation Mark</div>";
	}
}
elseif (trim($txta) == '') { $error[1] = "<div class='warning_message'>This is required field</div>"; }

if (trim($txtb) != '')
{
	if (preg_match("/['\"]/", $txtb))
	{
		$error[2] = "<div class='warning_message'>Please do not use Quotation Mark</div>";
	}
}
elseif (trim($txtb) == '') { $error[2] = "<div class='warning_message'>This is required field</div>"; }

if (preg_match("/['\"]/", $txtc))
{
	$error[3] = "<div class='warning_message'>Please do not use Quotation Mark</div>";
}

if (preg_match("/['\"]/", $txtd))
{
	$error[4] = "<div class='warning_message'>Please do not use Quotation Mark</div>";
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

$dbquery = "update questions set empno='$empno', question='$txtquestion', ans1='$txta', ans2='$txtb', ans3='$txtc', ans4='$txtd', anscorrect='$xoption' where id='$xid'";
$dbresult = mysql_query($dbquery) or die ("Error in query: $query. " . mysql_error());
echo "<script type='text/javascript'>alert('Record Saved!')</script>" ;
echo "
<script type='text/javascript'>
window.location.href='questions_setting.php?empno=" . $empno . "&tnkc=". $tnkc ."';
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
                <a href='questions_setting.php?empno=<?php echo $empno; ?>&tnkc=<?php echo $tnkc; ?>' style="color:#0033FF; text-decoration:none">
                	Cancel</a>

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
                      <div style="font-size:14px">
                      </div>
                      </td>
                      <td width="1" height="32"><img src="../images/gray_side.gif" width="1" height="32"/></td>
                    </tr>
                  </table>
                  <br />
                    </td>
              </tr>
              <tr>
                <td height="147" align="center">
<table width="467" border="0" cellspacing="0" cellpadding="0">
<tr><td height="15" align="left" colspan="3">
<input name="xid" type="hidden" value="<?php echo $xid; ?>"/>
    <span style="font-size:13px; color:#999999">By : </span><span style="font-size:13px; color:#000000"><?php echo $fname.' '.$lname; ?></span>
</td></tr>

<tr><td height="5" colspan="3"></td></tr>

<tr><td height="15" align="left" colspan="3">
    <span style="font-size:13px; color:#999999">Ship Management : </span><span style="font-size:13px; color:#006600"><?php echo $p; ?></span>
</td></tr>

<tr><td height="5" colspan="3"></td></tr>

<tr><td width="507" height="15" align="left" colspan="3">
	<span style="font-size:13px; color:#999999">Vessel Type : </span><span style="font-size:13px; color:#006600"><?php echo $v; ?></span>
</td></tr>

<tr><td height="5" colspan="3"></td></tr>

<tr><td height="15" align="left" colspan="3">
	<span style="font-size:13px; color:#999999">Type of Examination : </span><span style="font-size:13px; color:#006600"><?php echo $e; ?></span>
</td></tr>

<tr><td height="5" colspan="3"></td></tr>

<tr><td height="15" align="left" colspan="3">
	<span style="font-size:13px; color:#999999">Category of Examination : </span><span style="font-size:13px; color:#006600"><?php echo $c; ?></span>
</td></tr>

<tr><td height="5" colspan="3"></td></tr>
                  
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
                    <td colspan="3" align="center" valign="top"><textarea name="txtquestion" id="txtquestion" style="font-size:12px; width:460px; height:120px"><?php echo $question; ?></textarea>
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
                    <td width="122" align="right"><strong>Option A</strong> : </td>
                    <td width="20">&nbsp;</td>
                    <td width="325" align="left"><div>
                        <input name="txta" type="text" id="txta" style="font-size:12px; width:207px" size="40" height="12" value="<?php echo $ans1; ?>"/>
                    <input type="radio" name="option_a" value="A" <?php echo $xmark1; ?>/></div><div><?php echo $msg4; ?></div></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td align="right"><strong>Option B</strong> : </td>
                    <td>&nbsp;</td>
                    <td align="left"><div>
                        <input name="txtb" type="text" id="txtb" style="font-size:12px; width:207px" size="40" height="12" value="<?php echo $ans2; ?>"/>
                        <input type="radio" name="option_a" value="B" <?php echo $xmark2; ?>/>
                    </div><div><?php echo $msg5; ?></div></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td align="right"><strong>Option C</strong> : </td>
                    <td>&nbsp;</td>
                    <td align="left"><div>
                        <input name="txtc" type="text" id="txtc" style="font-size:12px; width:207px" size="40" height="12" value="<?php echo $ans3; ?>"/>
                        <input type="radio" name="option_a" value="C" <?php echo $xmark3; ?>/>
                    </div><div><?php echo $msg6; ?></div><div>(Optional)</div></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td align="right"><strong>Option D</strong> : </td>
                    <td>&nbsp;</td>
                    <td align="left"><div>
                      <input name="txtd" type="text" id="txtd" style="font-size:12px; width:207px" size="40" height="12" value="<?php echo $ans4; ?>"/>
                      <input type="radio" name="option_a" value="D" <?php echo $xmark4; ?>/>
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
