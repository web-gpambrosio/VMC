<?php
include('includes/session.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="includes/body.css" />
<title>ISPS Online Examination</title>
</head>

<body>
<form action="" method="post" name="form" id="form" enctype="multipart/form-data">
<table width=100% height=100% border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="621" align="center" valign="top">
    
    <table width="0" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" class="a">
  <tr>
    <td width="3" rowspan="2" background="images/side.gif" valign="top"><img src="images/topt.gif" width="3" /></td>
    <td width="29" height="49" background="images/top.gif">&nbsp;</td>
    <td width="296" background="images/top.gif" valign="bottom"><img src="images/topm1.gif" width="296" height="49" /></td>
    <td width="431" background="images/top.gif" valign="top" align="right"><table width="396" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="2" height="12"></td>
        </tr>
      <tr>
        <td width="369" height="26" align="right"><span style="color:#FFFFFF; font-size:16px"><strong>International Ship and Port Facility Security</strong></span></td>
      </tr>
    </table></td>
    <td width="38" background="images/top.gif" align="right"><img src="images/top.gif" /><img src="images/topline.gif" width="22" height="49" /></td>
    <td width="3" rowspan="2" align="right" valign="top" background="images/side.gif"><img src="images/topt.gif" width="3" /></td>
  </tr>
  <tr>
    <td height="39">&nbsp;</td>
    <td valign="top"><img src="images/topm2.gif" width="296" height="39" /></td>
    <td>&nbsp;</td>
    <td><img src="images/tnkc.gif" width="25" height="40" /></td>
    </tr>
  <tr>
    <td width="3" background="images/side.gif" valign="top">&nbsp;</td>
    <td height="466" colspan="4" align="center" valign="middle"><table width="323" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="21"><img src="images/tl.gif" width="21" height="21" /></td>
          <td width="281" background="images/t.gif"><img src="images/t.gif" height="21" /></td>
          <td width="21"><img src="images/tr.gif" width="21" height="21" /></td>
        </tr>
      <tr>
        <td height="189" background="images/l.gif">&nbsp;</td>
          <td valign="top" align="center"><table width="241" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="37">&nbsp;</td>
              <td width="204">&nbsp;</td>
            </tr>
            <tr>
              <td align="left"><img src="images/lock.gif" width="24" height="31" /></td>
              <td align="left"><span style="color:#333333; font-size:16px">Log In</span></td>
            </tr>
            <tr>
              <td colspan="2" height="8"></td>
              </tr>
            <tr>
              <td height="26" colspan="2" align="center"><span style="color:#333333; font-size:14px">Enter Your Crew Code and Passcode</span>
                <?php echo $msg; ?>            </td>
              </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="77" colspan="2" align="center">
                
                <table width="192" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align="left"><strong>Crew Code</strong></td>
                </tr>
                  <tr>
                    <td align="left" valign="top"><input type="text" name="crewcode" class="logtext"/></td>
                </tr>
                  <tr>
                    <td height="10" align="left"></td>
                </tr>
                  <tr>
                    <td height="15" align="left"><strong>Pass Code</strong></td>
                </tr>
                  <tr>
                    <td align="left"><input type="password" name="passcode" class="logtext"/></td>
                </tr>
                  <tr>
                    <td height="36" align="right" valign="bottom">
                    <div style="float:left"><input type="submit" name="submit" value="  Log In  "/></div>
                    <div style="float:right"><input type="button" name="buttonx" value="  Cancel  " onclick="javascript:location.href='../index.php'"/></div>
                    </td>
                </tr>
                  </table>            </td>
              </tr>
            <tr>
              <td width="37">&nbsp;</td>
              <td width="204">&nbsp;</td>
            </tr>
            </table></td>
          <td background="images/r.gif">&nbsp;</td>
        </tr>
      <tr>
        <td><img src="images/bl.gif" width="21" height="21" /></td>
          <td background="images/b.gif">&nbsp;</td>
          <td><img src="images/br.gif" width="21" height="21" /></td>
        </tr>
    </table>
      <br />
      <img src="images/isps.gif" width="377" height="54" /></td>
    <td width="3" align="right" valign="top" background="images/side.gif">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="6"><img src="images/bot.gif" width="800" height="15" /></td>
    </tr>
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
