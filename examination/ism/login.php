<?php
include('ism/connection/myauth.php');
session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Adminitrator / Scheduler - Log In Page   ---   ISM Online Examination</title>
<link rel="stylesheet" type="text/css" href="ism/style/body.css" />
<script type="text/javascript" language="JavaScript">
function HideContent(d) {
if(d.length < 1) { return; }
document.getElementById(d).style.display = "none";
}
function ShowContent(d) {
if(d.length < 1) { return; }
var dd = document.getElementById(d);
dd.style.display = "block";
}

function logmex(form)
{ 
  var TestVar = form.passwordsecurity.value;
  if(TestVar=="veritas")
   window.open("ism/administrator/login.php"); 
  else
   alert("Invalid Input!");
 }

 </script>
</head>
<body topmargin="10" background="ism/images/background.gif">

<table width=100% height=100% border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
    	<td align="center" valign="middle">
        <form action="" method="post" name="form" id="form" enctype="multipart/form-data">
        <table width="708" height="565" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" background="ism/images/sideline.gif">
  <tr>
    <td height="105" colspan="2" align="left" background="ism/images/top.gif"></td>
    </tr>
  <tr>
    <td height="68" colspan="2" align="left" valign="top"><table width="734" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="78">&nbsp;</td>
        <td width="292" align="left"><img src="ism/images/title.gif" width="257" height="49" /></td>
        <td width="364">&nbsp;</td>
      </tr>
    </table>
      </td>
    </tr>
  <tr>
    <td height="57" colspan="2" valign="top" align="center"><table width="629" border="0" cellspacing="0" cellpadding="0" background="ism/images/menu.gif">
      <tr>
        <td width="1" height="36"></td>
        <td width="627" height="36" align="left" valign="top"><table width="0" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="2" height="11"></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td width="181">
                <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a onClick="ShowContent('login4admin'); return true;" href="javascript:ShowContent('login4admin')" style="color:#FFFFFF; text-decoration:none"> <strong>VMC Administrator</strong></a></div>
   <span 
   id="login4admin" 
   style="display:none; position:absolute; border:1px #000000 solid; background-color: white; padding: 5px; top: 216px; width: 169px;">
                  <table width="0" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td height="12" valign="top" align="right">
                      <a onClick="HideContent('login4admin'); return true;" href="javascript:HideContent('login4admin')">
                      <img src="ism/images/closex.gif" width="12" height="12" border="0" style="cursor:pointer"/></a></td>
                    </tr>
                    <tr>
                      <td width="171" height="41" align="center">
                      
                      <table width="156" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="102" align="left">Security:</td>
  </tr>
  <tr>
    <td align="left"><input type="password" name="passwordsecurity"/>
                     <input type="button" name="btnlogin" value="Login"  style="border:1px solid #333333; color:#000000; font-size:12px; cursor:pointer; " onclick="logmex(this.form); HideContent('login4admin'); return true;" /></td>
  </tr>
</table>
</td>
                    </tr>
                  </table>
                </span></td>
          </tr>
          <tr>
            <td width="438"></td>
            <td height="1"></td>
          </tr>
        </table></td>
        <td width="1" height="36"></td>
      </tr>
    </table>
      <img src="ism/images/sideline.gif" width="800"/></td>
    </tr>
  <tr>
    <td width="464" background="ism/images/sideline.gif" align="left" valign="top">
<table width="445" height="205" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="92">&nbsp;</td>
    <td width="353" valign="top" align="left">
        
<table width="0" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="335">
    <div style="color:#006600"><u>Please Enter Your Employee Number and Password</u></div>
    <div style="color:#FF0000"><?php echo $msg; ?></div>    </td>
  </tr>
</table>

    <table width="336" height="95" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="123" height="8"></td>
    <td width="10" rowspan="5"></td>
    <td width="189"></td>
    <td width="14" rowspan="5"></td>
  </tr>
  <tr>
    <td height="18" valign="top" align="left">Log In Number: </td>
    <td valign="top">
    <div><input type="text" name="crewcode" style="width:150px" maxlength="19"/></div>
    <div style="color:#FF0000; font-size:10px"><?php echo $msg1; ?></div>    </td>
    </tr>
  <tr>
    <td height="10"></td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <td height="18" valign="top" align="left">Password : </td>
    <td valign="top">
    <div><input type="password" name="passcode" style="width:150px" maxlength="19" sty/></div>
    <div style="color:#FF0000; font-size:10px"><?php echo $msg2; ?></div>    </td>
    </tr>
  <tr>
    <td height="29">&nbsp;</td>
    <td valign="bottom">
    <input type="submit" name="submit" value="  Log In  "/>    </td>
    </tr>
</table>
    </td>
  </tr>
</table>    </td>
    <td width="336" valign="top" align="left">
	<img src="ism/images/loginsign.gif" width="257" height="204" />    </td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
    </tr>
  <tr>
    <td colspan="2" background="ism/images/bottom.gif" height="112"></td>
    </tr>
</table>
</form>

      </td>
    </tr>
</table>

</body>
</html>
