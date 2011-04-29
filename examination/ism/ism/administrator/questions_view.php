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
$xempno=$_GET['admin'];
$id=$_GET['id'];
$tnkc=$_GET['tnkc'];

$xcode="Select questions.id, questions.empno, questions.question, questions.ans1, questions.ans2, questions.ans3, questions.ans4, questions.anscorrect, questions.category, questions.examtype, questions.principal, questions.type, admin.fname, admin.lname, admin.position from questions, admin where questions.id='$id' and admin.empno=questions.empno";
$query_result=mysql_query("$xcode",$conn);	

$id=mysql_result($query_result,0,"questions.id");
$empno=mysql_result($query_result,0,"questions.empno");
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
case "A": $xmark1 = " <span style='color:#FF0000'>***</span>"; break;
case "B": $xmark2 = " <span style='color:#FF0000'>***</span>"; break;
case "C": $xmark3 = " <span style='color:#FF0000'>***</span>"; break;
case "D": $xmark4 = " <span style='color:#FF0000'>***</span>"; break;
}
	

	

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Questionares</title>
<link rel="stylesheet" type="text/css" href="../style/body.css" />
<link rel="stylesheet" type="text/css" href="../style/text_style.css" />
<script> 
var howLong = 10000;
 t = null;
function closeMe22(){
t = setTimeout("self.close()",howLong);
}
</script> 
<style type="text/css">
a { text-decoration:none; color:#aaaaaa }
</style>
</head>
<body onload="closeMe22();self.focus()" topmargin="10" leftmargin="15" background="../images/background.gif">

<table width="629" border="0" cellspacing="0" cellpadding="0" align="center">

  <tr>
    <td height="5"></td>
  </tr>
  <tr>
    <td width="629" height="47" align="left" valign="top"><table width="629" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="1" height="32"><img src="../images/gray_side.gif" width="1" height="32"/></td>
        <td width="639" height="32" background="../images/gray_mid.gif"><div style="font-size:14px"><span style="font-size:14px; color:#000000"></span></div></td>
        <td width="1" height="32"><img src="../images/gray_side.gif" width="1" height="32"/></td>
      </tr>
    </table>
        <br />
    </td>
  </tr>
  <tr>
    <td height="233" align="center"><table width="507" height="300" border="0" cellpadding="0" cellspacing="0">
    <tr><td height="15" align="left" colspan="3">
    <span style="font-size:13px; color:#999999">By : </span><span style="font-size:13px; color:#00000"><?php echo $fname.' '.$lname; ?></span>
</td></tr>

<tr><td height="5" colspan="3"></td></tr>

      <tr>
        <td height="15" align="left">
        <span style="font-size:13px; color:#999999">Ship Management : </span><span style="font-size:13px; color:#006600"><?php echo $p; ?></span>
        </td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td width="507" height="15" align="left"><span style="font-size:13px; color:#999999">Vessel Type : </span><span style="font-size:13px; color:#006600"><?php echo $v; ?></span></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td height="15" align="left"><span style="font-size:13px; color:#999999">Type of Examination : </span><span style="font-size:13px; color:#006600"><?php echo strtoupper($e); ?></span></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td height="15" align="left"><span style="font-size:13px; color:#999999">Category of Examination : </span><span style="font-size:13px; color:#006600"><?php echo $c; ?></span></td>
      </tr>
      <tr>
        <td height="8"></td>
      </tr>
      <tr>
        <td height="1" bgcolor="#CCCCCC"></td>
      </tr>
      <tr>
        <td height="8"></td>
      </tr>
      <tr>
        <td height="15" align="left" valign="top"><span style="font-size:13px; color:#999999">Question # : </span><span style="font-size:13px; color:#0000FF"><?php echo $id; ?></span></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td height="1" bgcolor="#CCCCCC"></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td height="15" align="left" valign="top"><table width="526" height="20" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="65" height="16" align="left" valign="top"><span style="font-size:13px; color:#999999">Question  : </span></td>
            <td width="547" rowspan="2" align="left" valign="top"><span style="font-size:13px; color:#000000"><strong><?php echo $question; ?></strong></span></td>
          </tr>
          <tr>
            <td height="2"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="8"></td>
      </tr>
      <tr>
        <td height="1" bgcolor="#CCCCCC"></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td height="15" align="left" valign="top"><span style="font-size:13px; color:#999999">Option A : </span><span style="font-size:13px; color:#000000"><?php echo $ans1 . $xmark1; ?></span></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td height="15" align="left" valign="top"><span style="font-size:13px; color:#999999">Option B : </span><span style="font-size:13px; color:#000000"><?php echo $ans2 . $xmark2; ?></span></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <?php if ($ans3 != "")
{
?>
      <tr>
        <td height="15" align="left" valign="top"><span style="font-size:13px; color:#999999">Option C : </span><span style="font-size:13px; color:#000000"><?php echo $ans3 . $xmark3; ?></span></td>
      </tr>
      <?php
}
if ($ans4 != "")
{
?>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td height="15" align="left" valign="top"><span style="font-size:13px; color:#999999">Option D : </span><span style="font-size:13px; color:#000000"><?php echo $ans4 . $xmark4; ?></span></td>
      </tr>
      
      <?php
}
?><tr>
        <td height="8"></td>
      </tr>
      <tr>
        <td height="1" bgcolor="#CCCCCC"></td>
      </tr>
      <tr>
        <td height="37" valign="bottom" align="center"><input type="button" name="button" value=" CLOSE " style="font-size:11px" onclick="javascript:self.close()"/>
        </td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
