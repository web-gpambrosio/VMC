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

$xcode="Select id, lname, fname, mname, position, tnkc from admin where empno='$empno' and tnkc='$tnkc'";
$query_result=mysql_query("$xcode",$conn);

$id=mysql_result($query_result,0,"id");
$lname=mysql_result($query_result,0,"lname");
$fname=mysql_result($query_result,0,"fname");
$mname=mysql_result($query_result,0,"mname");
$position=mysql_result($query_result,0,"position");
$tnkc=mysql_result($query_result,0,"tnkc");

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $fname.' '.$lname.' : '.$position; ?></title>
<link rel="stylesheet" type="text/css" href="../style/body.css" />
<link rel="stylesheet" type="text/css" href="../style/text_style.css" />
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
<body topmargin="10" leftmargin="15" background="../images/background.gif">

<table width=101% height=100% border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
    	<td align="center" valign="top"><table width="800" height="388" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" background="../images/sideline.gif">
          <tr>
            <td width="800" height="105" align="left" background="../images/top.gif"></td>
          </tr>
          <tr>
            <td height="52" align="left"><table width="755" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="79">&nbsp;</td>
                  <td width="268" align="left"><img src="../images/title.gif" width="257" height="49" /></td>
                  <td width="42">&nbsp;</td>
                  <td width="366">
                  
                  <?php
				  	switch ($tnkc)
					{
						case "1": $tnkc_picture = "kobe"; break;
						case "2": $tnkc_picture = "manila"; break;
						case "3": $tnkc_picture = "star"; break;
						default: $tnkc_picture = "account"; break;
					}
				  ?>
                  <img src="../images/<?php echo $tnkc_picture; ?>.gif" width="302" height="33" />
                  
                  </td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td height="12"><img src="../images/sideline.gif" width="800"/></td>
          </tr>
          <tr>
            <td height="106" align="center" valign="top"><table width="629" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="17" align="right">
                <a href="account_setting.php?empno=<?php echo $empno; ?>&tnkc=<?php echo $tnkc; ?>" style="text-decoration:none; color:#0033FF">Account Setting</a>
                &nbsp;&nbsp;&nbsp;
                <a href="../connection/admin_logout.php" style="text-decoration:none; color:#0033FF">Log Out</a>                </td>
              </tr>
              <tr>
                <td height="5"></td>
              </tr>
              <tr>
                <td height="5"></td>
              </tr>
              <tr>
                <td height="132" align="left" valign="top"><table width="629" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="1" height="32"><img src="../images/gray_side.gif" width="1" height="32"/></td>
                      <td width="639" height="32" background="../images/gray_mid.gif"><div style="font-size:14px; color:#000000">&nbsp;&nbsp;&nbsp;&nbsp; <?php echo '<b>'.$fname.' '.substr($mname,0,1).'. '.$lname .'</b> : '. $position; ?> </div></td>
                      <td width="1" height="32"><img src="../images/gray_side.gif" width="1" height="32"/></td>
                    </tr>
                  </table>
                    <table width="274" height="100" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td height="10" colspan="2"></td>
                      </tr>
                      <tr>
                        <td height="15">&nbsp;</td>
                        <td align="left" valign="top"> <a href='exam_type.php?empno=<?php echo $empno; ?>&tnkc=<?php echo $tnkc; ?>' style="color:#333333; text-decoration:none"><span class="main_body">Type of Examination</span></a></td>
                      </tr>
                      <tr>
                        <td height="15">&nbsp;</td>
                        <td align="left" valign="top"></td>
                      </tr>
                      <tr>
                        <td width="36" height="15">&nbsp;</td>
                        <td width="238"><span class="main_body" style="cursor:pointer" onclick="showhide('script3'); return(false);">Questionare</span></td>
                      </tr>
                      <tr>
                        <td height="15">&nbsp;</td>
                        <td align="left" valign="top">
<table width="228" height="49" border="0" cellpadding="0" cellspacing="0" id="script3" style="display:none">
                          <tr>
                            <td height="5" colspan="2"></td>
                          </tr>
                          <tr>
                            <td height="15">&nbsp;</td>
                            <td>&bull; <a href='questions_add.php?empno=<?php echo $empno; ?>&tnkc=<?php echo $tnkc; ?>' style="color:#333333; text-decoration:none">Add</a></td>
                          </tr>
                          <tr>
                            <td height="5" colspan="2"></td>
                          </tr>
                          <tr>
                            <td width="20" height="15">&nbsp;</td>
                            <td width="208">&bull; <a href='questions_setting.php?empno=<?php echo $empno; ?>&tnkc=<?php echo $tnkc; ?>' style="color:#333333; text-decoration:none">Search :: Edit :: Delete</a></td>
                          </tr>
                          <tr>
                            <td height="9" colspan="2"></td>
                          </tr>
</table></td>
                      </tr>
                      <tr>
                        <td height="15">&nbsp;</td>
                        <td align="left" valign="top"> <a href='crew_page.php?empno=<?php echo $empno; ?>&tnkc=<?php echo $tnkc; ?>' style="color:#333333; text-decoration:none"><span class="main_body">Veritas Crew</span></a></td>
                      </tr>
                      <tr>
                        <td height="15">&nbsp;</td>
                        <td align="left" valign="top"></td>
                      </tr>
                  </table>
                  
                </td>
              </tr>
              
              
             
              <tr>
                <td width="629" height="71" align="left" valign="top">
                <table width="629" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="1" height="1"></td>
                      <td width="639" height="1" background="../images/gray_mid.gif"></td>
                      <td width="1" height="1"></td>
                    </tr>
                  </table>
                  <table width="274" height="70" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="10" colspan="2"></td>
                    </tr>
                    <tr>
                      <td width="36" height="15">&nbsp;</td>
                      <td><span><a href='exam_principal.php?empno=<?php echo $empno; ?>&tnkc=<?php echo $tnkc; ?>' class="main_body2">Vessels and Principal</a></span></td>
                    </tr>

                    <tr>
                      <td height="15">&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td height="15">&nbsp;</td>
                      <td><span><a href='exam_cat.php?empno=<?php echo $empno; ?>&tnkc=<?php echo $tnkc; ?>' class="main_body2">Categories of Exam</a></span></td>
                    </tr>

                    <tr>
                      <td height="15">&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td height="15">&nbsp;</td>
                      <td><span><a href='exam_type_create.php?empno=<?php echo $empno; ?>&tnkc=<?php echo $tnkc; ?>' class="main_body2">List of Type of Exam</a></span></td>
                    </tr>

                    <tr>
                      <td height="15">&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                   </table>
                  <table width="629" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="1" height="1"></td>
                      <td width="639" height="1" background="../images/gray_mid.gif"></td>
                      <td width="1" height="1"></td>
                    </tr>
                  </table>
                  </td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td background="../images/bottom.gif" height="112"></td>
          </tr>
        </table></td>
</tr>
</table>

</body>
</html>
