<?php
include('e-store_conn.php');
$SearchResult=mysql_query("SELECT * FROM merch WHERE verif='0' order by id desc") or die(mysql_error()); 
$NumberOfResults=mysql_num_rows($SearchResult); 
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
<form action="" method="post" name="form" id="form" enctype="multipart/form-data">
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
    <td height="143" valign="top" align="center" bgcolor="#FFFFFF" ><table width="667" height="232" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td height="232" valign="top" align="center"><table width="623" height="290" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td height="15" colspan="5"></td>
            </tr>
            <tr>
              <td height="8" colspan="5" align="left" bgcolor="#FFFFFF"><span style="color:#000000; font-size:16px"><strong>Administrative requests for posting an item: <em>e-store</em></strong></span> </td>
            </tr>
            <tr>
              <td height="8" colspan="5"></td>
            </tr>
            <tr>
              <td height="12" colspan="5" align="center" bgcolor="#FFFFFF"><!--<input type="submit" name="submit" value="  Submit All Data   "/>--></td>
            </tr>
            <tr>
              <td height="5" colspan="5"></td>
            </tr>
            <tr>
              <td height="1" colspan="5" bgcolor="#e9e9e9"></td>
            </tr>
            <tr>
              <td height="5" colspan="5"></td>
            </tr>
            <tr>
              <td height="30" colspan="5" align="left" bgcolor="#e9e9e9">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span style="color:#000000; font-size:10px"><strong>VERIFICATION PAGE</strong></span></td>
            </tr>
            <tr>
              <td height="1" colspan="5" bgcolor="#e9e9e9"></td>
            </tr>
            <!--///////////////////////////////////////////START//////////////////////////////////////////////////-->
            <?php
While($row = mysql_fetch_object($SearchResult))
{ 
?>
            <tr>
              <td height="8" colspan="5"></td>
            </tr>
          <tr>
            <td width="108" height="50" align="left" valign="top"><img src="<?php echo $row->productpic; ?>" width="100" height="100" style="border:solid #CCCCCC 2px"/> </td>
            <td width="321" align="left" valign="top"><table width="97%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                    <td width="303" height="22" valign="top"><span style="color:#0033CC; font-size:16px; font-family:Verdana"><strong><?php echo $row->product; ?></strong></span></td>
              </tr>
                  <tr>
                    <td height="16" valign="top"><span style="color:#666666; font-size:10px">
                      <?php 
	echo substr($row->txtdesc, 0, 100) . " ...";
	?>
                    </span></td>
                  </tr>
                  <tr>
                    <td height="24" valign="bottom"><span style="color:#666666; font-size:11px"><strong>Price</strong>: </span><span style="color:#FF0000; font-size:11px">
                      <?php
	if ($row->price1 == "" && $row->price2 == "")
	{
	echo $row->priceremarks;
	}
	elseif ($row->price2 == "")
	{
	echo "Php " . $row->price1 . " - (" . $row->priceremarks . ")";
	}
	elseif ($row->price1 != "" && $row->price2 != "")
	{
	echo "Php " . $row->price1 . " - Php " . $row->price2 . " - (" . $row->priceremarks . ")";
	}
	?>
                    </span></td>
                  </tr>
                  <tr>
                    <td height="16" valign="top"><span style="color:#666666; font-size:11px"><strong>Contact Person</strong>: </span><span style="color:#FF0000; font-size:11px">
                      <?php
	echo $row->contact;
	?>
                    </span></td>
                  </tr>
            </table></td>
            <td width="1" valign="top" bgcolor="#e9e9e9"></td>
            <td height="50" colspan="2" align="right" valign="top"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="303" height="21" valign="top" align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="21" valign="top" align="left"><a href="update.php?id=<?php echo $row->id; ?>"> <img src="e-store_images/check1.gif" width="89" height="25" border="0"/></a> </td>
                  </tr>
                  <tr>
                    <td height="10"></td>
                  </tr>
                  <tr>
                    <td height="21" valign="top" align="left"><a href="delete.php?id=<?php echo $row->id; ?>"> <img src="e-store_images/discard1.gif" width="89" height="25" border="0"/> </a></td>
                  </tr>
                  <tr>
                    <td width="303" height="10"></td>
                  </tr>
                  <tr>
                    <td width="303" height="15" valign="top" align="left"></td>
                  </tr>
              </table></td>
          </tr>
          <tr>
            <td height="8" colspan="5"></td>
          </tr>
          <tr>
            <td height="1" colspan="5" bgcolor="#e9e9e9"></td>
          </tr>
          <tr>
            <td height="8" colspan="5"></td>
          </tr>
            <?php
} 
?>
            <!--///////////////////////////////////////////END//////////////////////////////////////////////////-->
            <tr>
              <td height="12" colspan="5" align="center" bgcolor="#FFFFFF"><!--                              <input type="submit" name="submit" value="  Submit All Data  "/><br /><br />--></td>
            </tr>
        </table></td>
      </tr>
    </table></td>
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
</form>
</body>
</html>
