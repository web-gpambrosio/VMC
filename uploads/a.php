<?
if($_POST["action"] == "Upload Image")
{
$picbig = $_POST['picbig'];
$tempName = $_FILES['picbig']['tmp_name'];
$fileName = $_FILES['picbig']['name'];
if (@move_uploaded_file($tempName, "uploads/".$fileName)) 
echo "<script type='text/javascript'>alert('Picture Saved')</script>" ;
else
echo "<script type='text/javascript'>alert('Else')</script>" ;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<meta name="keywords" content="Veritas Maritime Corporation, Seaman, Marc 2000" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../../style/body.css" />
<link rel="stylesheet" type="text/css" href="../../style/hover.css" />
<script type="text/javascript" src="../../style/java.js"></script>
<link rel="shortcut icon" href="../../images/logo.ico" type="image/x-icon" />

<title>A</title>
<script src="Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
</head>

<body topmargin="0" bottommargin="0" leftmargin="0">
<table width="501" height="229" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="493" height="221" valign="top" align="center">
    <form action='' method='post' enctype='multipart/form-data' name='image_upload_form' id='image_upload_form'>
      <table width="461" height="219" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td height="15" align="center"></td>
        </tr>
        <tr>
          <td height="1" ><hr /></td>
        </tr>
        <tr>
          <td height="5"></td>
        </tr>
        <tr>
          <td width="461" height="19" align="left" valign="top"><span style="color:#000000"><strong>PRODUCT NAME: </strong></span><span style="color:#0033FF"><?php echo $product; ?></span></td>
        </tr>
        <tr>
          <td height="5"></td>
        </tr>
        <tr>
          <td height="33" align="left" valign="top"><span style="color:#000000"><strong>Description : </strong> </span><span style="color:#0033FF"> <br />
                  <?php echo substr($txtdesc, 0, 200) . " ..."; ?> </span>
                </div></td>
        </tr>
        <tr>
          <td height="5"></td>
        </tr>
        <tr>
          <td height="24" align="left" valign="top"><span style="color:#000000"><strong>Categories:</strong></span> <span style="color:#0033FF"><?php echo $categories; ?></span> &nbsp;&nbsp;&nbsp;&nbsp; <span style="color:#000000"><strong>Quantity : </strong></span> <span style="color:#0033FF"><?php echo $quantity; ?></span></td>
        </tr>
        <tr>
          <td height="5"></td>
        </tr>
        <tr>
          <td height="1" ><hr /></td>
        </tr>
        <tr>
          <td height="5"></td>
        </tr>
        <tr>
          <td height="10" align="left"><table width="431" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td height="8" colspan="2" align="left"><div style="color:#666666; font-family:Verdana; font-size:12px"><strong>Browse Picture</strong></div></td>
            </tr>
            <tr>
              <td height="8" colspan="2"></td>
            </tr>
            <tr>
              <td height="22" align="left" valign="top"><input type="file" name="picbig" size="20" style="font-size:12px" />
<?
if(is_array($error))
{
while(list($key, $val) = each($error))
{
echo "<br>";
echo $val;
}
}
?>
              </td>
              <td valign="top"></td>
            </tr>
            <tr>
              <td height="5" colspan="2"></td>
            </tr>
            <tr>
              <td width="404" height="24" align="left" valign="top"><input type="submit" value="Upload Image" name="action" /></td>
              <td width="27"></td>
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
          <td height="24" valign="top" align="left">
          </td>
        </tr>
      </table>
    </form></td>
    <td width="8"></td>
  </tr>
  <tr>
    <td height="6" colspan="2"></td>
  </tr>
</table>
</body>
</html>