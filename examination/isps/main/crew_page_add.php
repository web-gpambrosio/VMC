<?php
session_start();
include('includes/myfunction.php');
include('../includes/conn.php');

if ((!isset($_GET['empno']) || trim($_GET['empno']) == ''))
{ header("location:../index.php"); }

$empno=$_GET['empno'];

include('includes/inc.php');
include('includes/myname.php');
if ($_POST['submit'])
{
	$error=array();
	$txtempno = $_POST['txtempno'];
	$mycheck = $_POST['mycheck'];
	$txtlname = $_POST['txtlname'];
	$txtfname = $_POST['txtfname'];
	$txtmname = $_POST['txtmname'];
	
	$txtcontact = $_POST['txtcontact'];
	
$mm = $_POST['mm'];
$dd = $_POST['dd'];
$yyyy = $_POST['yyyy'];

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



if ($mycheck != true)
{
	if (trim($txtempno) == '')
	{
		$error[0] = "<div class='warning_message'>Enter Crew Code</div>";
	}
	else
	{
		$empno_admin_duplicate=mysql_query("select crewcode from crew where crewcode='".$txtempno."'",$conn);
		$empno_admin_duplicate_row=mysql_num_rows($empno_admin_duplicate);
		if ($empno_admin_duplicate_row != '0')
		{
			$error[0] = "<div class='warning_message'>Duplicate Crew Code</div>";
		}
	}
}
else
{
	$txtempno = date('Y-di');
}
if (trim($txtlname) == '') { $error[1] = "<div class='warning_message'>Enter Last Name</div>"; }
if (trim($txtfname) == '') { $error[2] = "<div class='warning_message'>Enter First Name</div>"; }
if (trim($txtmname) == '') { $error[3] = "<div class='warning_message'>Enter Middle Name</div>"; }

if (trim($date_formatx) == '--') { $error[4] = "<div class='warning_message'>Enter Birth Date</div>"; }
elseif (trim($mm) == '') { $error[4] = "<div class='warning_message'>Enter Birth Date</div>"; }
elseif (trim($dd) == '') { $error[4] = "<div class='warning_message'>Enter Birth Date</div>"; }
elseif (trim($yyyy) == '') { $error[4] = "<div class='warning_message'>Enter Birth Date</div>"; }
elseif (trim($xdate_formatx) == '01/01/1970') { $error[4] = "<div class='warning_message'>Invalid Input</div>"; }
elseif (trim($age) < '17') { $error[4] = "<div class='warning_message'>You must be 17 years old and above.</div>"; }

if (trim($txtcontact) == '') { $error[5] = "<div class='warning_message'>Enter Contact Number</div>"; }

if (sizeof($error) == 0)
{
$queryv = "insert into crew (crewcode, fname, gname, mname, contact, bdate) values ('$txtempno', '$txtlname', '$txtfname', '$txtmname', '$txtcontact', '$date_formatx')";
$resultv = mysql_query($queryv) or die ("Error in query: $query. " . mysql_error());

	if ($mycheck != true)
	{ echo "<script type='text/javascript'>alert('New ISPS Crew Account has been Added!')</script>"; }
	else
	{ echo "<script type='text/javascript'>alert('Your Crew Code is ".$txtempno.". Record Saved!')</script>"; }
	  echo "<script language=\"javascript\">window.location.href='sched.php?empno=" . $empno . "'</script>";
	mysql_close($conn);
}
else
{
for ($x=0; $x<sizeof($error); $x++)
{
	$msg0 = $error[0];
	$msg1 = $error[1];
	$msg2 = $error[2];
	$msg3 = $error[3];
	$msg4 = $error[4];
	$msg5 = $error[5];
}
}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../includes/body.css" />
<title>ISPS Crew - ISPS Online Examination</title>

</head>

<body>
<form action="" method="post" name="form" id="form" enctype="multipart/form-data">
<table width=100% height=100% border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="621" align="center" valign="top">
    
<table width="0" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" class="a">
<?php echo $header; ?>
  <tr>
    <td width="3" background="../images/side.gif" valign="top">&nbsp;</td>
    <td width="794" height="466" colspan="4" align="center" valign="top">
    <?php
include('includes/mytitle.php');
?>

      <table width="724" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="724" height="10"></td>
        </tr>
        <tr>
          <td height="312" valign="top" align="center"><table width="629" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td height="17" align="right">&nbsp;</td>
            </tr>
            <tr>
              <td height="5"></td>
            </tr>
            <tr>
              <td height="30" bgcolor="#eeeeee" style="border:1px solid #999999" align="center"><div class="black"><strong>CREATE NEW CREW ACCOUNT</strong></div></td>
            </tr>
            <tr>
              <td height="11"></td>
            </tr>
            <tr>
              <td height="17" align="center"><table width="382" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="131" align="right" valign="top">Crew Code<span class="requiredx">*</span> :</td>
                    <td width="22">&nbsp;</td>
                    <td width="246" align="left">
                    <input name="txtempno" type="txt" <?php echo $txtstyle . 'value="'.$txtempno.'"'; ?>/>
                    <div><?php echo $msg0; ?></div>
                    <div><input type="checkbox" name="mycheck"/> Don't have Employee Number.</div>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                 <tr>
                      <td align="right">Last Name<span class="requiredx">*</span> :</td>
                      <td>&nbsp;</td>
                      <td align="left"><input onchange="javascript:this.value=this.value.toUpperCase();" name="txtlname" type="text" id="txtlname" style="font-size:12px" size="35" height="12" value="<?php echo $txtlname; ?>"/>
                          <div><?php echo $msg1; ?></div></td>
                    </tr>
                    <tr>
                      <td colspan="3" height="8"></td>
                    </tr>
                    <tr>
                      <td align="right">First Name<span class="requiredx">*</span> :</td>
                      <td>&nbsp;</td>
                      <td align="left"><input onchange="javascript:this.value=this.value.toUpperCase();" name="txtfname" type="text" id="txtfname" style="font-size:12px" size="35" height="12" value="<?php echo $txtfname; ?>"/>
                          <div><?php echo $msg2; ?></div></td>
                    </tr>
                    <tr>
                      <td colspan="3" height="8"></td>
                    </tr>
                    <tr>
                      <td align="right">Middle Name<span class="requiredx">*</span> :</td>
                      <td>&nbsp;</td>
                      <td align="left"><input onchange="javascript:this.value=this.value.toUpperCase();" name="txtmname" type="text" id="txtmname" style="font-size:12px" size="35" height="12" value="<?php echo $txtmname; ?>"/>
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
                      <td align="right">Date of Birth<span class="requiredx">*</span> : </td>
                      <td>&nbsp;</td>
                      <td align="left">
                      
                      <select name="mm" id="mm" onchange='if (this.value.length == 2) {form.dd.focus();form.dd.select()}'>
                      
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
                      <input onkeypress="if (event.keyCode &lt; 45 || event.keyCode &gt; 57) event.returnValue = false;" onkeyup='if (this.value.length == 4) {form.txtcontact.focus();form.txtcontact.select()}' name="yyyy" type="text" id="yyyy" style="font-size:12px; width:50px" maxlength="4" value="<?php echo $yyyy; ?>"/>
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
                      <td align="right">Contact Number<span class="requiredx">*</span> : </td>
                      <td>&nbsp;</td>
                      <td align="left"><input onkeypress="if (event.keyCode &lt; 45 || event.keyCode &gt; 57) event.returnValue = false;" name="txtcontact" type="text" id="txtcontact" style="font-size:12px" size="35" height="12" value="<?php echo $txtcontact; ?>"/>
                          <div><?php echo $msg5; ?></div></td>
                    </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td colspan="3" align="left"><table width="332" height="20" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td width="139">&nbsp;</td>
                          <td width="193">
<input type="submit" name="submit" value="  Save  "/>
<input type="button" name="back" value="  Cancel  " onClick="javascript:location.href='isps_crew.php?empno=<?php echo $empno; ?>'"/></td>
                        </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
              </table></td>
            </tr>
          </table></td>
        </tr>
      </table>
      <!-- ------------------------------------------------------------------------------------ -->    </td>
    <td width="3" align="right" valign="top" background="../images/side.gif">&nbsp;</td>
  </tr>
<?php echo $footer; ?>  
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
