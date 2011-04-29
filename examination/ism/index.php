<?php
include('ism/connection/auth.php');
session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ISM Online Examination</title>
<link rel="stylesheet" type="text/css" href="ism/style/body.css" />
<script type="text/javascript" language="javascript">
var d_names = new Array("Sunday", "Monday", "Tuesday",
"Wednesday", "Thursday", "Friday", "Saturday");

var m_names = new Array("January", "February", "March", 
"April", "May", "June", "July", "August", "September", 
"October", "November", "December");

var d = new Date();
var curr_day = d.getDay();
var curr_date = d.getDate();
var sup = "";
if (curr_date == 1 || curr_date == 21 || curr_date ==31)
   {
   sup = "st";
   }
else if (curr_date == 2 || curr_date == 22)
   {
   sup = "nd";
   }
else if (curr_date == 3 || curr_date == 23)
   {
   sup = "rd";
   }
else
   {
   sup = "th";
   }
var curr_month = d.getMonth();
var curr_year = d.getFullYear();
</script>
</head>
<body topmargin="10" background="ism/images/background.gif">

<table width=100% height=100% border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
    	<td align="center" valign="middle">

        <table width="708" height="573" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" background="ism/images/sideline.gif">
  <tr>
    <td height="105" colspan="2" align="left" background="ism/images/top.gif"></td>
    </tr>
  <tr>
    <td height="67" colspan="2" align="left" valign="top"><table width="337" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="80">&nbsp;</td>
        <td width="257" align="left"><img src="ism/images/title.gif" width="257" height="49" /></td>
      </tr>
    </table>
      </td>
    </tr>
  <tr>
    <td height="66" colspan="2" align="center" valign="">
    
    <table width="629" border="0" cellspacing="0" cellpadding="0" background="ism/images/menu.gif">
  <tr>
                      <td width="1" height="36"></td>
                      <td width="627" height="36" align="left" valign="top">
                      
                      
                      <table width="0" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td colspan="3" height="11"></td>
                        </tr>
  <tr>
    <td width="11">&nbsp;</td>
    <td width="459"><div style="font-size:14px; color:#000000">
                      <span style="color:#333333; font:Arial; font-size:11px"><strong>TODAY: </strong></span>
                      <span style="color:#666666; font:Arial; font-size:11px"><strong>
                    <script type="text/javascript">
					document.write(d_names[curr_day] + " " + curr_date + "<SUP>"
					+ sup + "</SUP> " + m_names[curr_month] + " " + curr_year);
        			</script>
                  	  </strong></span>
                      </div></td>
    <td width="152"><div><a href="login.php" target="_blank" style="color:#FFFFFF; text-decoration:none"><strong>Administrator Account</strong></a></div></td>
  </tr>
</table>

                      
                      
                      
                      
                      
                </td>
                <td width="1" height="36"></td>
              </tr>
            </table>
    <img src="ism/images/sideline.gif" width="800"/></td>
    </tr>
  <tr>
    <td width="447" background="ism/images/sideline.gif" align="left" valign="top">
<table width="431" height="199" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="92" height="184">&nbsp;</td>
    <td width="339" valign="top" align="left">
        <form action="" method="post" name="form" id="form" enctype="multipart/form-data">
<table width="0" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="248">
    <div style="color:#006600"><u>Please Enter Your Birth Date and Passcode</u></div>
    <div style="color:#FF0000"><?php echo $msg; ?></div>    </td>
  </tr>
</table>

    <table width="328" height="95" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="72" height="8"></td>
    <td width="13" rowspan="5"></td>
    <td width="234"></td>
    <td width="9" rowspan="5"></td>
  </tr>
  <tr>
    <td height="18" valign="top" align="left">Birth Date: </td>
    <td valign="top">
    <select name="mm" id="mm" onchange='if (this.value.length == 2) {form.dd.focus();form.dd.select()}' tabindex="1">
    <option value="" style="color:#999999">-- Month --</option>
                            <option value="01">January</option>
                            <option value="02">February</option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="05">May</option>
                            <option value="06">June</option>
                            <option value="07">July</option>
                            <option value="08">August</option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
					  </select>
                      &nbsp;-&nbsp;
                      <input onkeypress="if (event.keyCode &lt; 45 || event.keyCode &gt; 57) event.returnValue = false;" onkeyup='if (this.value.length == 2) {form.yyyy.focus();form.yyyy.select()}' name="dd" type="text" id="dd" style="font-size:12px; width:25px" maxlength="2"/>
                      &nbsp;-&nbsp;
                      <input onkeypress="if (event.keyCode &lt; 45 || event.keyCode &gt; 57) event.returnValue = false;" onkeyup='if (this.value.length == 4) {form.passcode.focus();form.passcode.select()}' name="yyyy" type="text" id="yyyy" style="font-size:12px; width:50px" maxlength="4"/>
  
                          <div><?php echo $msg4; ?></div></td>
    </tr>
  <tr>
    <td height="10"></td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <td height="18" valign="top" align="left">Passcode : </td>
    <td valign="top">
    <div><input type="text" name="passcode" class="textbox" maxlength="19"/></div>
    <div style="color:#FF0000; font-size:10px"><?php echo $msg2; ?></div>    </td>
    </tr>
  <tr>
    <td height="29">&nbsp;</td>
    <td valign="bottom">
    <!--<input name="name" type="image" id="submit" value="Search" src="ism/images/login.jpg" />-->
    <input type="submit" name="submit" value="  Log In  "/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="button" name="buttonx" value="  Cancel  " onclick="javascript:location.href='../index.php'"/>
    </td>
    </tr>
</table>
</form>    </td>
  </tr>
</table>    </td>
    <td width="353" valign="top" align="left">
	<img src="ism/images/loginsign.gif" width="257" height="204" />    </td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
    </tr>
  <tr>
    <td colspan="2" background="ism/images/bottom.gif" height="112"></td>
    </tr>
</table>


      </td>
    </tr>
</table>

</body>
</html>
