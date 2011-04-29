<?php
if ((!isset($_GET['id']) || trim($_GET['id']) == ''))
{ die ('Missing Record!!'); }
include('e-store_conn.php');
$w=$_GET['id'];

$x="Select id, product, txtdesc, categories, quantity, productpic from merch where id='" . $w . "'";
$y=mysql_query("$x",$conn);
$id=mysql_result($y,0,"id");
$product=mysql_result($y,0,"product");
$txtdesc=mysql_result($y,0,"txtdesc");
$categories=mysql_result($y,0,"categories");
$quantity=mysql_result($y,0,"quantity");
$productpic=mysql_result($y,0,"productpic");
?>

<?php
if ($_POST['submit'])
{
$error=array();
$a = $_POST['txtcontactname'];
$b = $_POST['txtcontactno'];
$c = $_POST['txtfax'];
$d = $_POST['txtemail'];
$e = $_POST['txtprice1'];
$f = $_POST['txtprice2'];
$g = $_POST['txtnegociable'];
$h = $_POST['txtpayment'];


if (trim($a) == '')
{
$error[0] = "<div style='color:#FF0000; font-size:10px'>Please Enter Your Contact Name</div>";
}
if (trim($b) == '')
{
$error[1] = "<div style='color:#FF0000; font-size:10px'>Please Enter Your Contact Number</div>";
}

if (trim($d) != '')
{
if (!eregi("^[_a-z0-9]+([\.%!][_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$",$d))
{
$error[2] = "<div style='color:#FF0000; font-size:10px'>Invalid Email Address</div>";
}
}

if (trim($g) == 'Fix Price')
{
	if (trim($e) == '')
	{
	$error[3] = "<div style='color:#FF0000; font-size:10px'>Please Enter Product Price</div>";
	}
}


if (sizeof($error) == 0)
{
$que = "update merch set contact='$a', contactnum='$b', fax='$c', email='$d', priceremarks='$g', transaction='$h', price1='$e', price2='$f' where id='$id'";
$res = mysql_query($que) or die ("Error in query: $query. " . mysql_error());

echo "<script type='text/javascript'>alert('Note: We need to verify your Post Item to view in a public')</script>" ;

$abb="Select contact, contactnum, email from merch where id='$id'";
$accc=mysql_query("$abb",$conn);
$aa=mysql_result($accc,0,"contact");
$bb=mysql_result($accc,0,"contactnum");
$cc=mysql_result($accc,0,"email");

echo '<script language=javascript>javascript:self.close()</script>';

mysql_close($conn);
}
else
{
for ($x=0; $x<sizeof($error); $x++)
{
$msg1 = $error[0];
$msg2 = $error[1];
$msg3 = $error[2];
$msg4 = $error[3];
$msg5 = $error[4];
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
<title>E-Store for Allottees - Verification </title>
</head>

<body topmargin="0" bottommargin="0" leftmargin="0">
<table width="0" border="0" cellspacing="0" cellpadding="0" align='center'>
<tr>
  <td colspan="3" bgcolor="#333333" height="1"></td>
  </tr>
  <tr>
    <td width="1" bgcolor="#333333"></td>
    <td width="274"><table width="788" height="662" border="0" cellpadding="0" cellspacing="0" align="center">
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
        <td width="788" height="432" valign="top" align="center" id="body_BG">
		
		<table width="761" height="418" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="403" valign="top" align="center" bgcolor="#FFFFFF" >
    
<table width="501" height="392" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="493" height="384" valign="top" align="center">

            <form action="" method="post" name="form" id="form" enctype="multipart/form-data">
              <table width="461" height="287" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="461" height="15" align="center"></td>
                </tr>
                <tr>
                  <td height="1" ><hr /></td>
                </tr>
                <tr>
                  <td height="10"></td>
                </tr>
                <tr>
                  <td height="19" align="left" valign="top"><table width="457" height="104" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="112" height="104" align="left" valign="top"><img src="<?php echo $productpic; ?>" width="100" height="100" style="border:solid #CCCCCC 2px"/></td>
                      <td width="345" valign="top"><span style="color:#000000"><strong>PRODUCT NAME: </strong></span><span style="color:#0033FF"><?php echo $product; ?></span>
                      <br />
                      <br style="height:2px"/>
                      <span style="color:#000000"><strong>Categories:</strong></span> <span style="color:#0033FF"><?php echo $categories; ?></span> &nbsp;&nbsp;&nbsp;&nbsp; <span style="color:#000000"><strong>Quantity : </strong></span> <span style="color:#0033FF"><?php echo $quantity; ?></span>
                      <br />
                      <br style="height:2px"/>
                      <span style="color:#000000"><strong>Description : </strong> </span><span style="color:#0033FF"> <br />
                      <?php echo substr($txtdesc, 0, 200) . " ..."; ?> </span>
                      </div>                      </td>
                    </tr>
                    
                    
                  </table></td>
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
                  <td height="19" align="left" valign="top"><span style="color:#000000"><strong>Contact Person <span style="color:#FF0000">*</span> : </strong></span>
                      <input name="txtcontactname" type="text" style="width:250px; font-size:10px" size="40" height="10" maxlength="50" value="<?php echo $a; ?>"/>
                      <?php echo $msg1; ?></td>
                </tr>
                <tr>
                  <td height="10"></td>
                </tr>
                <tr>
                  <td height="19" align="left" valign="top"><span style="color:#000000"><strong>Contact N<u>o</u>. <span style="color:#FF0000">*</span> : </strong></span>
                      <input name="txtcontactno" type="text" style="width:120px; font-size:10px" size="40" height="12" maxlength="15" value="<?php echo $b; ?>"/>
                      <?php echo $msg2; ?></td>
                </tr>
                <tr>
                  <td height="10"></td>
                </tr>
                <tr>
                  <td height="19" align="left" valign="top"><span style="color:#000000"><strong>Fax N<u>o</u>. : </strong></span>
                      <input name="txtfax" type="text" style="width:250px; font-size:10px" size="40" height="12" maxlength="35" value="<?php echo $c; ?>"/>                </td>
                </tr>
                <tr>
                  <td height="10"></td>
                </tr>
                <tr>
                  <td height="19" align="left" valign="top"><span style="color:#000000"><strong>Email Address : </strong></span>
                      <input name="txtemail" type="text" style="width:200px; font-size:10px" size="40" height="12" maxlength="35" value="<?php echo $d; ?>"/>
                      <?php echo $msg3; ?></td>
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
                  <td height="20" align="left" valign="top"><span style="color:#000000"><strong>Price Range <span style="color:#FF0000">*</span> : </strong></span>
                      <span style="color:#999999">Php </span>
                      <input name="txtprice1" type="text" style="width:50px; font-size:10px" size="40" height="12" maxlength="8" value="<?php echo $e; ?>"/>
                      <span style="color:#999999">- Php </span>
                      <input name="txtprice2" type="text" style="width:50px; font-size:10px" size="40" height="12" maxlength="8" value="<?php echo $f; ?>"/>
                      <span style="color:#999999">&nbsp;&nbsp;/&nbsp;&nbsp;</span>
                      <select name="txtnegociable" style="font-size:9px; font-family:Verdana;" id="select2width">
                      <option value="Negotiable">Negotiable</option>
                      <option value="Fix Price">Fix Price</option>
                      </select>                      <?php echo $msg4; ?></td>
                </tr>
                <tr>
                  <td height="10"></td>
                </tr>
                <tr>
                  <td height="19" align="left" valign="top"><span style="color:#000000"><strong> Payment Methods <span style="color:#FF0000">*</span>  : </strong></span>
                    <select name="txtpayment" style="font-size:9px; font-family:Verdana;" id="dept">
                      <option value="Contact via Phone">Contact via Phone</option>
                      <option value="Personal cheque">Personal cheque</option>
                      <option value="Cash on Delivery">Cash on Delivery</option>
                      <option value="Other / See item descriptio">Other / See item description</option>
                    </select></td>
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
