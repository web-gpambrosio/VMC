<?php
session_start();
include('includes/myfunction.php');
include('../../includes/conn.php');

if ((!isset($_GET['empno']) || trim($_GET['empno']) == ''))
{ header("location:../../index.php"); }
$empno=$_GET['empno'];
include('includes/myname.php');
include('includes/inc.php');

$xttt = $_GET['Exam_Name'];
$vav=mysql_query("select id, type from type where id='$xttt'");
if (mysql_num_rows($vav) != "0")
{
$examida=mysql_result($vav,0,"id");
$examnamea=mysql_result($vav,0,"type");
}



if ($_POST['submit'])
{
$error=array();
$xexamname = $_POST['xexamname'];
$txtquestion = $_POST['txtquestion'];
$txta = $_POST['txta'];
$txtb = $_POST['txtb'];
$txtc = $_POST['txtc'];
$txtd = $_POST['txtd'];
$option_a = $_POST['option_a'];
$b = time ();
$whencreated = date("Y-m-d g:i:s",$b);
if (trim($xexamname) == '') { $error[5] = "<div class='warning_message'>Enter Type of Exam</div>"; }
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
$dbquery = "insert into questions values ('','$empno', '$txtquestion', '$txta', '$txtb', '$txtc', '$txtd', '$xoption', '$xexamname', '$whencreated')";
$dbresult = mysql_query($dbquery) or die ("Error in query: $query. " . mysql_error());
echo "<script type='text/javascript'>alert('Record Saved!')</script>" ;
echo "
<script type='text/javascript'>
window.location.href='questions_add.php?empno=" . $empno . "&Exam_Name=" . $xexamname . "';
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
          <td height="312" valign="top" align="center"><table width="467" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td colspan="3" height="22" align="left"><div><span style="float:RIGHT"><a href='questions_setting.php?empno=<?php echo $empno; ?>' style="color:#0000FF; text-decoration:underline">SEARCH QUESTION</a></span> </div></td>
            </tr>
            <tr>
              <td colspan="3" height="8"></td>
            </tr>
            <tr>
              <td align="left"><strong>Type of Exam</strong> : </td>
              <td>&nbsp;</td>
              <td align="left"><div>
<?php
$dbexamtype="Select type.id, type.type from type, examtype where examtype.examname = type.type and type.sync='0' order by id asc";
$dbrexamtype=mysql_query($dbexamtype);
echo "<select name=\"xexamname\" style=\"font-size:12px; width:220px; font-family:Verdana;color:#999999\"> ";
if ($examnamea=="")
{
echo "<option value='' >-- Select Type of Examination --</option>";
}
else
{
echo "<option value=".$examida." style='color:#333333'>".$examnamea."</option>";
}

while($row = mysql_fetch_assoc($dbrexamtype))
{
echo "<option value=\"{$row['id']}\" style='color:#333333'>{$row['type']}</option> ";
}
echo "</select> ";
?>
                </div>
                  <div><?php echo $msg5; ?></div></td>
            </tr>
            <tr>
              <td colspan="3" height="8"></td>
            </tr>

            <tr>
              <td colspan="3" height="22" align="left">
              <div>
              <span style="float:left"><strong style="font-size:13px">Question</strong></span>
              </div>              </td>
            </tr>
            <tr>
              <td colspan="3" align="left" valign="top"><textarea name="txtquestion" id="txtquestion" style="font-size:12px; width:460px; height:120px"><?php echo $txtquestion; ?></textarea>
                  <div><?php echo $msg0; ?></div></td>
            </tr>
            <tr>
              <td colspan="3" height="8"></td>
            </tr>
            <tr>
              <td colspan="3" align="center"><span style="color:#0000FF; font-size:10px">Note: Use the RADIO BUTTON to choose the correct answer.</span></td>
            </tr>
            <tr>
              <td colspan="3" height="8"></td>
            </tr>
            <tr>
              <td width="88" align="right"><strong>Option A</strong> : </td>
              <td width="16">&nbsp;</td>
              <td width="363" align="left"><div>
                  <input name="txta" type="text" id="txta" style="font-size:12px; width:207px" size="40" height="12" value="<?php echo $txta; ?>"/>
                  <input type="radio" name="option_a" value="A" checked="checked"/>
              </div>
                  <div><?php echo $msg1; ?></div></td>
            </tr>
            <tr>
              <td colspan="3" height="8"></td>
            </tr>
            <tr>
              <td align="right"><strong>Option B</strong> : </td>
              <td>&nbsp;</td>
              <td align="left"><div>
                  <input name="txtb" type="text" id="txtb" style="font-size:12px; width:207px" size="40" height="12" value="<?php echo $txtb; ?>"/>
                  <input type="radio" name="option_a" value="B" />
                </div>
                  <div><?php echo $msg2; ?></div></td>
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
                </div>
                  <div><?php echo $msg3; ?></div>
                <div>(Optional)</div></td>
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
                </div>
                  <div><?php echo $msg4; ?></div>
                <div>(Optional)</div></td>
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
