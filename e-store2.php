<?php
if ((!isset($_GET['id']) || trim($_GET['id']) == ''))
{ die ('Missing Record!'); }
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

if($_POST["action"] == "Upload Image")
{
	unset($imagename);
	
	if(!isset($_FILES) && isset($HTTP_POST_FILES))
		$_FILES = $HTTP_POST_FILES;
	
	if(!isset($_FILES['image_file']))
		$error["image_file"] = "<div style='color:#FF0000; font-size:10px'>An image was not found.</div>";
	
	$imagename = basename($_FILES['image_file']['name']);
	
	if(empty($imagename))
		$error["imagename"] = "<div style='color:#FF0000; font-size:10px'>The image was not found.</div>";
	
	if(empty($error)){
		$picture_upload = $_FILES['image_file']['name'];
		$array_picture = pathinfo($picture_upload);
		$extension_picture = $array_picture['extension'];
		$newimage = $id.".".$extension_picture;
		$result = @move_uploaded_file($_FILES['image_file']['tmp_name'], $newimage);
		$que = "update merch set productpic='$newimage' where id='$id'";
		$res = mysql_query($que) or die ("Error in query: $query. " . mysql_error());
		
		if(!empty($result))
		{
			$ttt = "<span style='color:#00CC66; font-size:10px'>The file has been successfully uploaded</span><span><a href=\"view.php?id=$id\" target=\"_blank\" style='color:#993300; font-size:10px; text-decoration:none'><b>View Image</b></a></span>";
			$rrr = "<input type=\"button\" value=\" Next \" onClick=\"window.location.href='e-store3.php?id=". $w ."'\">";
		}
		
		if(empty($result))
			$error["result"] = "<span style='color:#FF0000; font-size:10px'>There was an error moving the uploaded file</span><span style='color:#FFFFFF; font-size:10px'>Name Error!</span>";
	
	}
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
<style type="text/css">
body { 
font-family: Arial;
color:#ffffff;
font-size:12px;
text-decoration:none;
}
</style>
<title>E-Store - Upload Picture</title>
<script src="Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
</head>

<body topmargin="0" bottommargin="0" leftmargin="0">
<table width="0" border="0" cellspacing="0" cellpadding="0" align='center'>
<tr>
  <td colspan="3" bgcolor="#333333" height="1"></td>
  </tr>
  <tr>
    <td width="1" bgcolor="#333333"></td>
    <td width="274"><table width="788" height="502" border="0" cellpadding="0" cellspacing="0" align="center">
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
        <td width="788" height="272" valign="top" align="center" id="body_BG">
		
		<table width="761" height="256" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="241" valign="top" align="center" bgcolor="#FFFFFF" >
    
<table width="501" height="229" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="493" height="221" valign="top" align="center">
            <form method="POST" enctype="multipart/form-data" name="image_upload_form" action="">
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
                  <td width="461" height="19" align="left" valign="top">
                  <span style="color:#000000"><strong>PRODUCT NAME: </strong></span><span style="color:#0033FF"><?php echo $product; ?></span></td>
                  </tr>
                <tr>
                  <td height="5"></td>
                </tr>
                <tr>
                  <td height="33" align="left" valign="top"><span style="color:#000000"><strong>Description : </strong>
                  </span><span style="color:#0033FF">
                  <br />
                  <?php echo substr($txtdesc, 0, 200) . " ..."; ?>
                  </span>
                   </div></td>
                </tr>
                <tr>
                  <td height="5"></td>
                </tr>
                <tr>
                  <td height="24" align="left" valign="top">
                  <span style="color:#000000"><strong>Categories:</strong></span>
                  <span style="color:#0033FF"><?php echo $categories; ?></span>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                  <span style="color:#000000"><strong>Quantity : </strong></span>
                  <span style="color:#0033FF"><?php echo $quantity; ?></span></td>
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
                  <td height="10" align="left">
                  
                  <table width="457" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td height="8" colspan="2" align="left">
                  <div style="color:#666666; font-family:Verdana; font-size:12px"><strong>Browse Picture</strong></div></td>
                </tr>
                <tr>
                  <td height="8" colspan="2"></td>
                </tr>
                <tr>
                  <td height="22" align="left" valign="top"><input type="file" name="image_file" size="20" style="font-size:12px" />
                  <input type="hidden" name="MAX_FILE_SIZE2" value="25000" />
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
<?php echo "<br>"; echo $ttt; ?>
              </td>
                  <td valign="top"></td>
                </tr>
                <tr>
                  <td height="5" colspan="2"></td>
                  </tr>
                <tr>
                  <td width="452" height="24" align="left" valign="top"><input type="submit" value="Upload Image" name="action" /></td>
                  <td width="5"></td>
                </tr> 
                </table>
                
                </td>
                </tr>
				<tr>
                  <td height="10"></td>
                </tr>
                <tr>
                  <td height="1" > <hr /></td>
                </tr>
                <tr>
                  <td height="10"></td>
                </tr>
                <tr>
                  <td height="24" valign="top" align="left">
                  
                  <?php
				  echo $rrr; 
				  ?>
                  </td>
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
