<?php
session_start();
include('includes/myfunction.php');
include('../../includes/conn.php');

if ((!isset($_GET['empno']) || trim($_GET['empno']) == '')&&(!isset($_GET['id']) || trim($_GET['id']) == ''))
{ header("location:../../index.php"); }

$empno=$_GET['empno'];
$idx=$_GET['idx'];

include('includes/myname.php');
include('includes/inc.php');

$xcode="Select questions.id, questions.empno, questions.question, questions.ans1, questions.ans2, questions.ans3, questions.ans4, questions.anscorrect, questions.examtype, questions.whencreated from questions where questions.id='$idx'";
$query_result=mysql_query("$xcode",$conn);	

$qid=mysql_result($query_result,0,"questions.id");
$empno=mysql_result($query_result,0,"questions.empno");
$question=mysql_result($query_result,0,"questions.question");
$ans1=mysql_result($query_result,0,"questions.ans1");
$ans2=mysql_result($query_result,0,"questions.ans2");
$ans3=mysql_result($query_result,0,"questions.ans3");
$ans4=mysql_result($query_result,0,"questions.ans4");
$anscorrect=mysql_result($query_result,0,"questions.anscorrect");
$examtypex=mysql_result($query_result,0,"questions.examtype");
$xwhencreated=mysql_result($query_result,0,"questions.whencreated");
$whencreated = date("F d, Y / g:i:s", strtotime($xwhencreated));

$qr=mysql_query("select type from type where id='$examtypex'",$conn);
$xexamtype=mysql_result($qr,0,"type");

switch ($anscorrect)
{
case "A": $xmark1 = " ***"; break;
case "B": $xmark2 = " ***"; break;
case "C": $xmark3 = " ***"; break;
case "D": $xmark4 = " ***"; break;
}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../includes/body.css" />
<title>Administrator - ISPS Online Examination</title>
<script> 
function showhide(id){ 
if (document.getElementById){ 
obj = document.getElementById(id); 
if (obj.style.display == "none"){ 
obj.style.display = ""; 
} else { 
obj.style.display = "none"; 
} 
} 
} 
</script>
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
          <td height="312" valign="top" align="left"><table width="629" border="0" cellspacing="0" cellpadding="0" align="center">
            <tr>
              <td height="5"></td>
            </tr>
            <tr>
              <td height="233" align="center"><table width="507" height="164" border="0" cellpadding="0" cellspacing="0">

                  <tr>
                    <td width="507" height="15" align="left" valign="top"><span style="font-size:13px; color:#999999">Question # : </span><span style="font-size:13px; color:#0000FF"><?php echo $qid; ?></span></td>
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
                          <td width="93" height="16" align="left" valign="top"><span style="font-size:13px; color:#999999">Type of Exam  : </span></td>
                          <td width="433" rowspan="2" align="left" valign="top"><span style="font-size:13px; color:#0000FF"><strong><?php echo $xexamtype; ?></strong></span></td>
                        </tr>

                    </table></td>
                  </tr>
                  <tr>
                    <td height="8"></td>
                  </tr>
                  <tr>
                    <td height="15" align="left" valign="top"><table width="526" height="20" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td width="65" height="16" align="left" valign="top"><span style="font-size:13px; color:#999999">Question  : </span></td>
                          <td width="547" rowspan="2" align="left" valign="top"><span style="font-size:13px; color:#000000"><strong><?php echo $question; ?></strong></span></td>
                        </tr>

                    </table></td>
                  </tr>
                  <tr>
                    <td height="8"></td>
                  </tr>
                  <tr>
                    <td height="15" align="left" valign="top"><table width="526" height="20" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td width="83" height="16" align="left" valign="top"><span style="font-size:13px; color:#999999">Date / Time  : </span></td>
                          <td width="443" rowspan="2" align="left" valign="top"><span style="font-size:13px; color:#006600"><?php echo $whencreated; ?></span></td>
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
?>
                <tr>
                    <td height="8"></td>
                </tr>
                  <tr>
                    <td height="1" bgcolor="#CCCCCC"></td>
                  </tr>
                  <tr>
                    <td height="25" valign="bottom" align="center"><input type="button" name="button" value=" Back " style="font-size:11px" onclick="javascript:location.href='questions_setting.php?empno=<?php echo $empno; ?>'"/></td>
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
    <td height="2"></td>
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
