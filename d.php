<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>B</title>
</head>

<body>
<table width="399" height="186" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF">
  <tr>
    <td width="390" height="186" align="center" valign="top">
    <table width="390" height="202" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="385" height="178"valign="top">
<?php
if(!$_POST['save'])
{
?>
            <form action="" method="post" name="form" id="form" enctype="multipart/form-data">
              <table width="382" height="102" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td colspan="3" height="12"><span style="font-family:Verdana; font-size:12px">Title:</span> <span style="color:#0033CC; font-family:Verdana; font-size:12px"><u><strong></strong></u></span></td>
                </tr>
                <tr>
                  <td height="8" colspan="3"></td>
                </tr>
                <tr>
                  <td height="8" colspan="3" align="center"><div style="color:#666666; font-family:Verdana; font-size:12px"><strong>Browse Picture</strong></div></td>
                </tr>
                <tr>
                  <td height="8" colspan="3"></td>
                </tr>
                <tr>
                  <td width="100" height="31" align="left" valign="top"><div style="color:#666666; font-family:Verdana; font-size:12px">Paragraph 1</div></td>
                  <td width="278" align="left" valign="top"><input name="picbig" type="file" style="font-size:12px" size="40"/></td>
                  <td width="4"></td>
                </tr>
                <tr>
                  <td height="24" colspan="3" valign="top" align="center"><input type="submit" name="save" value="  Save  "/></td>
                </tr>
              </table>
            </form>
<?php
}
else
{
$error=array();
$picbig = $_POST['picbig'];
$tempName = $_FILES['picbig']['tmp_name'];
$fileName = $_FILES['picbig']['name'];

if (@move_uploaded_file($tempName, "uploads\\".$fileName)) 
echo "<script type='text/javascript'>alert('Picture Saved')</script>" ;
else
echo "<script type='text/javascript'>alert('Else')</script>";
}
//<---End--->
?>
</td>
        <td width="5"></td>
      </tr>

    </table></td>
  </tr>
</table>
</body>
</html>
