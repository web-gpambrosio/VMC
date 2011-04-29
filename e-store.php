<?php
include('e-store_conn.php');
session_start();

if ($_POST['submit'])
{
$error=array();
$a = $_POST['txtproduct'];
$b = $_POST['txtdesc'];
$c = $_POST['txtcategories'];
$d = $_POST['txtquantity'];

if (trim($a) == '')
{
$error[0] = "<div style='color:#FF0000; font-size:10px'>Please Enter Your Product Name</div>";
}
if (trim($b) == '')
{
$error[1] = "<div style='color:#FF0000; font-size:10px'>Please Enter Product Description</div>";
}
if (trim($d) == '')
{
$error[2] = "<div style='color:#FF0000; font-size:10px'>Please Enter Product Quantity</div>";
}

if (sizeof($error) == 0)
{
$queryv = "insert into merch (product, txtdesc, categories, quantity, verif) values ('$a', '$b', '$c', '$d', '0')";
$resultv = mysql_query($queryv) or die ("Error in query: $query. " . mysql_error());

$xr="Select id from merch order by id desc limit 0,1";
$yr=mysql_query("$xr",$conn);
$zr=mysql_result($yr,0,"id");
echo "<script language=\"javascript\">window.location.href='e-store2.php?id=" . $zr . "'</script>";

mysql_close($conn);
}
else
{
for ($x=0; $x<sizeof($error); $x++)
{
$msg1 = $error[0];
$msg2 = $error[1];
$msg3 = $error[2];
}
}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<meta name="keywords" content="Veritas Maritime Corporation, Seaman, Marc 2000" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<style type="text/css">
body { 
font-family: Arial;
color:#ffffff;
font-size:12px;
text-decoration:none;
}
</style>
<title>E-Store for Allottees</title>
</head>

<body topmargin="0" bottommargin="0" leftmargin="0">
<table width="0" border="0" cellspacing="0" cellpadding="0" align='center'>
<tr>
  <td colspan="3" bgcolor="#333333" height="1"></td>
  </tr>
  <tr>
    <td width="1" bgcolor="#333333"></td>
    <td width="274"><table width="788" height="544" border="0" cellpadding="0" cellspacing="0" align="center">
      <tr>
        <td height="160" background="e-store_images/top_banner.gif" valign="top"><table width="0" height="158" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="486" height="29"></td>
              <td width="301">&nbsp;</td>
            </tr>
            <tr>
              <td height="131" valign='top' background="e-store_images/logo.gif">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td width="788" height="314" valign="top" align="center" id="body_BG">
		
		<table width="761" height="158" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="143" valign="top" align="center" bgcolor="#FFFFFF" >
    
<table width="501" height="288" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="493" height="281" valign="top" align="center">

            <form action="" method="post" name="form" id="form" enctype="multipart/form-data">
              <table width="461" height="303" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td height="15" align="center"></td>
                </tr>
                <tr>
                  <td height="1" ><hr /></td>
                </tr>
                <tr>
                  <td height="10"></td>
                </tr>
                <tr>
                  <td width="461" height="19" align="left" valign="top">
                  <span style="color:#000000"><strong>PRODUCT NAME <span style="color:#FF0000">*</span> : </strong></span>
                    <input name="txtproduct" type="text" style="width:250px" size="40" height="12" maxlength="50" value="<?php $a; ?>"/>
                    <?php echo $msg1; ?></td>
                  </tr>
                <tr>
                  <td height="10"></td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top"><span style="color:#000000"><strong>
                  	Categories :
                  	      <select name="txtcategories" style="font-family:Verdana">
                            <?php 
$query1q="Select distinct cat from categories order by cat asc";
$result1q=mysql_query($query1q);
if (mysql_num_rows($result1q) > 0)
{
while($row = mysql_fetch_row($result1q))
{
echo '<option>' . $row[0] . '</option>'; 
} 
}
?>
 <option value="Others">Others</option> </select>
                  </strong></span></td>
                </tr>
                <tr>
                  <td height="10"></td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top"><span style="color:#000000"><strong>Quantity <span style="color:#FF0000">*</span> : </strong></span>
                      <input name="txtquantity" type="text" style="width:50px" size="40" height="12" maxlength="8" value="<?php $d; ?>"/>
                      <?php echo $msg3; ?></td>
                </tr>
                <tr>
                  <td height="10"></td>
                </tr>
                <tr>
                  <td height="133" align="left" valign="top"><span style="color:#000000"><strong>Description <span style="color:#FF0000">*</span> : </strong></span><br />
                      <textarea name="txtdesc" style="width:97%; height:100px;"><?php $b; ?></textarea>
                      <?php echo $msg2; ?>
                    </div></td>
                </tr>
                <tr>
                  <td height="10"></td>
                </tr>
                <tr>
                  <td height="1" ><hr /></td>
                </tr>
                <tr>
                  <td height="10"></td>
                </tr>
                <tr>
                  <td height="24" valign="top" align="left"><input type="submit" name="submit" value="  Save  "/></td>
                </tr>
              </table>
            </form>
</td>
        <td width="8"></td>
      </tr>
      <tr>
        <td height="6" colspan="2"></td>
      </tr>
    </table>
    
    </td>
    </tr>
</table>

		
		</td>
      </tr>
      <tr>
        <td width="788" height="44" id="footer" valign="bottom">
		<table width="0" border="0" cellspacing="0" cellpadding="0" align='center'>
  
  <tr>
    <td align="center"><div style="font-size:10px; font-family:Tahoma; color:#FFFFFF;">Copyright &copy; 2010 VERITAS MARITIME CORPORATION. All Rights Reserved</div></td>
  </tr>
  <tr>
    <td height="7"></td>
  </tr>
</table>

		</td>
      </tr>
    </table></td>
    <td width="1" bgcolor="#333333"></td>
  </tr>
  <tr>
  <td colspan="3" bgcolor="#333333" height="1"></td>
  </tr>
</table>
</body>
</html>
