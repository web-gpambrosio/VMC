<?php
session_start();
include('includes/myfunction.php');
include('../../includes/conn.php');

if ((!isset($_GET['empno']) || trim($_GET['empno']) == ''))
{ header("location:../../index.php"); }

$empno=$_GET['empno'];
include('includes/myname.php');
include('includes/inc.php');

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
          <td height="312" valign="top" align="left">
          <table width="274" height="70" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td height="10" colspan="2"></td>
            </tr>


            <tr>
              <td width="28" height="15">&nbsp;</td>
              <td width="246"><span style="font-size:10px">&darr; </span><span class="main_body" style="cursor:pointer" onclick="showhide('script1'); return(false);"><strong>Account </strong></span></td>
            </tr>
            <tr>
              <td height="15">&nbsp;</td>
              <td align="left" valign="top">
              <table width="228" border="0" cellpadding="0" cellspacing="0" id="script1" style="display:none">
                  <?php echo $xsyncx; ?>
                  <tr>
                    <td height="5" colspan="2"></td>
                  </tr>
                  <tr>
                    <td width="20" height="15">&nbsp;</td>
                    <td width="208">&bull; <a href='sched_page.php?empno=<?php echo $empno; ?>' style="color:#333333; text-decoration:none">Scheduler Account</a></td>
                  </tr>
                  <tr>
                    <td height="9" colspan="2"></td>
                  </tr>
              </table></td>
            </tr>
            
            <tr>
              <td width="28" height="15">&nbsp;</td>
              <td width="246"><span style="font-size:10px">&darr; </span><span class="main_body" style="cursor:pointer" onclick="showhide('script3'); return(false);"><strong>Questionare</strong></span></td>
            </tr>
            <tr>
              <td height="15">&nbsp;</td>
              <td align="left" valign="top"><table width="228" height="49" border="0" cellpadding="0" cellspacing="0" id="script3" style="display:none">
                  <tr>
                    <td height="5" colspan="2"></td>
                  </tr>
                  <tr>
                    <td height="15">&nbsp;</td>
                    <td>&bull; <a href='questions_add.php?empno=<?php echo $empno; ?>' style="color:#333333; text-decoration:none">Add</a></td>
                  </tr>
                  <tr>
                    <td height="5" colspan="2"></td>
                  </tr>
                  <tr>
                    <td width="20" height="15">&nbsp;</td>
                    <td width="208">&bull; <a href='questions_setting.php?empno=<?php echo $empno; ?>' style="color:#333333; text-decoration:none">Search :: Delete</a></td>
                  </tr>
                  <tr>
                    <td height="9" colspan="2"></td>
                  </tr>
              </table></td>
            </tr>
            
            
            
            
            
            <tr>
              <td height="15">&nbsp;</td>
              <td align="left" valign="top"><a href='isps_crew.php?empno=<?php echo $empno; ?>' style="color:#333333; text-decoration:none">• <span class="main_body"><strong>List of Crew</strong></span></a></td>
            </tr>
            <tr>
              <td height="15">&nbsp;</td>
              <td align="left" valign="top"></td>
            </tr>
            <tr>
              <td height="15">&nbsp;</td>
              <td align="left" valign="top"><a href='type.php?empno=<?php echo $empno; ?>' style="color:#333333; text-decoration:none">• <span class="main_body"><strong>Type of Examination</strong></span></a></td>
            </tr>
            <tr>
              <td height="15">&nbsp;</td>
              <td align="left" valign="top"></td>
            </tr>
          </table>
          </td>
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
