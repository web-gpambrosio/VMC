<?php
session_start();
if(!session_is_registered(adminno))
{
header("location:login.php");
}
if ((!isset($_GET['admin']) || trim($_GET['admin']) == '')&&(!isset($_GET['empno']) || trim($_GET['empno']) == ''))
{
header("location:index.php");
}
include('../connection/conn.php');
$adminno=$_GET['admin'];
$crewcode=$_GET['empno'];


$crew_add="Select * from crew where crewcode='$crewcode'";
$crew_add_query=mysql_query("$crew_add",$conn);	
$crew_add_row=mysql_num_rows($crew_add_query);

if ($crew_add_row != '0')
	{
		$bdate=mysql_result($crew_add_query,0,"bdate");
		$lname=mysql_result($crew_add_query,0,"fname");
		$fname=mysql_result($crew_add_query,0,"gname");
		$mname=mysql_result($crew_add_query,0,"mname");
		$crewcode=mysql_result($crew_add_query,0,"crewcode");
		$contact=mysql_result($crew_add_query,0,"contact");
		
		$mm = date("m",strtotime($bdate));
		$dd = date("d",strtotime($bdate));
		$yyyy = date("Y",strtotime($bdate));

	}

if ($_POST['submit'])
{

$error=array();
$txtcrew_id = $_POST['txtcrew_id'];
$lname = $_POST['lname'];
$fname = $_POST['fname'];
$mname = $_POST['mname'];
$contact = $_POST['contact'];
$mm = $_POST['mm'];
$dd = $_POST['dd'];
$yyyy = $_POST['yyyy'];

//Date Function --------------------------------------------------------------------------------------------->

$date_formatx = $yyyy.'-'.$mm.'-'.$dd;
$xdate_formatx = date("m/d/Y",strtotime($date_formatx));

  
  $currentYear = date('Y'); // the current year
  $currentMonth = date('m'); // the current month
  $currentDay = date('d'); // the current day
  
  $yearDiff = $currentYear - $yyyy; // difference between current year and your birth year
  
  if (($currentMonth < $mm) || ($currentMonth = $mm && $currentDay < $dd)) :
    $age = $yearDiff - 1;
  elseif (($currentMonth = $mm && $currentDay = dd)) :
    $age = $yearDiff;
  else:
    $age = $yearDiff;
  endif;



if (trim($lname) == '') { $error[0] = "<div class='warning_message'>Enter Last Name</div>"; }
if (trim($fname) == '') { $error[1] = "<div class='warning_message'>Enter First Name</div>"; }
if (trim($mname) == '') { $error[2] = "<div class='warning_message'>Enter Middle Name</div>"; }
if (trim($xdate_formatx) == '') { $error[3] = "<div class='warning_message'>Enter Birth Date</div>"; }
elseif (trim($xdate_formatx) == '01/01/1970') { $error[3] = "<div class='warning_message'>Invalid Input</div>"; }
elseif (trim($age) < '17') { $error[3] = "<div class='warning_message'>You must be 17 years old and above.</div>"; }
if (trim($contact) == '') { $error[4] = "<div class='warning_message'>Enter Contact Number</div>"; }


if (sizeof($error) == 0)
{

$queryv = "update crew set fname='$lname', gname='$fname', mname='$mname', contact='$contact', bdate='$date_formatx' where crewcode='$crewcode'";
$resultv = mysql_query($queryv) or die ("Error in query: $query. " . mysql_error());

mysql_query("update users set fname='$lname', gname='$fname', mname='$mname', bdate='$date_formatx' where crewcode='$crewcode'") or die ("Error in query: $query. " . mysql_error());

echo "<script type='text/javascript'>alert('Record Saved!')</script>" ;
echo "<script language=\"javascript\">window.location.href='admin_crew_page.php?adminno=" . $adminno . "'</script>";


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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Edit Administrator Account</title>
<link rel="stylesheet" type="text/css" href="../style/body.css" />
<link rel="stylesheet" type="text/css" href="../style/text_style.css" />

</head>
<body topmargin="10" leftmargin="15" background="../images/background.gif">

<table width=101% height=100% border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
    	<td align="center" valign="top"><table width="800" height="388" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" background="../images/sideline.gif">
          <tr>
            <td width="800" height="105" align="left" background="../images/top.gif"></td>
          </tr>
          <tr>
            <td height="52" align="left"><table width="755" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="79">&nbsp;</td>
                  <td width="268" align="left"><img src="../images/title.gif" width="257" height="49" /></td>
                  <td width="42">&nbsp;</td>
                  <td width="366"><img src="../images/supper.gif" width="378" height="33" /></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td height="12"><img src="../images/sideline.gif" width="800"/></td>
          </tr>
          <tr>
            <td height="106" align="center" valign="top">
<form action="" method="post" enctype="multipart/form-data" name="form" id="form">
            <table width="629" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="17" align="right"><a href="admin_crew_page.php?adminno=<?php echo $adminno; ?>" style="text-decoration:none; color:#0033FF">Cancel</a></td>
              </tr>
              <tr>
                <td height="5"></td>
              </tr>
              <tr>
                <td width="629" height="47" align="left" valign="top"><table width="629" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="1" height="32"><img src="../images/gray_side.gif" width="1" height="32"/></td>
                      <td width="639" height="32" background="../images/gray_mid.gif">
                      <div style="font-size:14px">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $fname.' '.$lname; ?>
                      </div>
                      </td>
                      <td width="1" height="32"><img src="../images/gray_side.gif" width="1" height="32"/></td>
                    </tr>
                  </table>
                  <br />
                    </td>
              </tr>
              <tr>
                <td height="17" align="center"><table width="382" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td colspan="3" height="14"></td>
                  </tr>
                  <tr>
                    <td width="131" align="right">Crew Number :</td>
                    <td width="22">&nbsp;</td>
                    <td width="246" align="left"><strong><?php echo $crewcode; ?></strong> </td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td align="right">Last Name :</td>
                    <td>&nbsp;</td>
                    <td align="left"><input name="lname" type="text" id="lname" style="font-size:12px" size="40" height="12" value="<?php echo $lname; ?>"/>
                        <div><?php echo $msg1; ?></div></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td align="right">First Name :</td>
                    <td>&nbsp;</td>
                    <td align="left"><input name="fname" type="text" id="fname" style="font-size:12px" size="40" height="12" value="<?php echo $fname; ?>"/>
                        <div><?php echo $msg2; ?></div></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td align="right">Middle Name :</td>
                    <td>&nbsp;</td>
                    <td align="left"><input name="mname" type="text" id="mname" style="font-size:12px" size="40" height="12" value="<?php echo $mname; ?>"/>
                        <div><?php echo $msg3; ?></div></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="1" bgcolor="#CCCCCC"></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td align="right">Date of Birth : </td>
                    <td>&nbsp;</td>
                    <td align="left"><select name="mm" id="mm" onchange='if (this.value.length == 2) {form.dd.focus();form.dd.select()}'>
                        <?php
switch ($mm)
{
case "01": $mmm = "January"; break;
case "02": $mmm = "February"; break;
case "03": $mmm = "March"; break;
case "04": $mmm = "April"; break;
case "05": $mmm = "May"; break;
case "06": $mmm = "June"; break;
case "07": $mmm = "July"; break;
case "08": $mmm = "August"; break;
case "09": $mmm = "September"; break;
case "10": $mmm = "October"; break;
case "11": $mmm = "November"; break;
case "12": $mmm = "December"; break;
}
?>
                        <option value="<?php echo $mm; ?>"><?php echo $mmm; ?></option>
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
                      <input onkeypress="if (event.keyCode &lt; 45 || event.keyCode &gt; 57) event.returnValue = false;" onkeyup='if (this.value.length == 2) {form.yyyy.focus();form.yyyy.select()}' name="dd" type="text" id="dd" style="font-size:12px; width:25px" maxlength="2" value="<?php echo $dd; ?>"/>
                      &nbsp;-&nbsp;
                      <input onkeypress="if (event.keyCode &lt; 45 || event.keyCode &gt; 57) event.returnValue = false;" onkeyup='if (this.value.length == 4) {form.contact.focus();form.contact.select()}' name="yyyy" type="text" id="yyyy" style="font-size:12px; width:50px" maxlength="4" value="<?php echo $yyyy; ?>"/>
                      <div style="font-size:9px; color:#999999">ex.(mm/dd/yyyy)</div>
                      <div><?php echo $msg4; ?></div></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="1" bgcolor="#CCCCCC"></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td align="right">Contact Number : </td>
                    <td>&nbsp;</td>
                    <td align="left"><input onkeypress="if (event.keyCode &lt; 45 || event.keyCode &gt; 57) event.returnValue = false;" name="contact" type="text" id="contact" style="font-size:12px" size="40" height="12" value="<?php echo $contact; ?>"/>
                        <div><?php echo $msg5; ?></div></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="1" bgcolor="#CCCCCC"></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td colspan="3" align="left"><table width="332" height="20" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td width="139">&nbsp;</td>
                          <td width="193"><input type="submit" name="submit" value="  Save  "/></td>
                        </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                </table></td>
              </tr>
            </table>
            </form>
            </td>
          </tr>
          <tr>
            <td background="../images/bottom.gif" height="112"></td>
          </tr>
        </table></td>
</tr>
</table>

</body>
</html>
