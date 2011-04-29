<?php
session_start();
include('includes/myfunction.php');
include('../../includes/conn.php');

if ((!isset($_GET['empno']) || trim($_GET['empno']) == ''))
{ header("location:../../index.php"); }

$empno=$_GET['empno'];
include('includes/myname.php');
include('includes/inc.php');

if ($_POST['submit'])
{
$error=array();
$examname = $_POST['examname'];
$notime = $_POST['notime'];
$noq = $_POST['noq'];

if (trim($examname) != '')
{
	$examname_examtype=mysql_query("Select examname from examtype where examname='$examname'",$conn);
	$examname_examtype02=mysql_num_rows($examname_examtype);
		if ($examname_examtype02 != '0')
		{
			$error[0] = "<div class='warning_message'>Duplicate Entry</div>";
		}
}
elseif (trim($examname) == '') { $error[0] = "<div class='warning_message'>Select Examination Name</div>"; }

if (trim($noq) == '') { $error[1] = "<div class='warning_message'>Select Number of Questionares</div>"; }
if (trim($notime) == '') { $error[2] = "<div class='warning_message'>Select Time Limit</div>"; }


if (sizeof($error) == 0)
{
$queryv = "insert into examtype (examname, whocreated, totaltime, totalno) values ('$examname', '$empno', '$notime', '$noq')";
$resultv = mysql_query($queryv) or die ("Error in query: $query. " . mysql_error());
echo "<script type='text/javascript'>alert('Record Saved!')</script>" ;
echo "<script language=\"javascript\">window.location.href='type.php?empno=" . $empno . "'</script>";


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
}
}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../includes/body.css" />
<title>Administrator - ISPS Online Examination</title>
<script> 
function showhide(id){ 
if (document.getElementById){ 
obj = document.getElementById(id); 
if (obj.style.display == "none"){ 
obj.style.display = ""; 
} else { 
obj.style.display = "none"; 
} 
} 
} 
</script>
</head>

<body>
<form action="" method="post" name="form" id="form" enctype="multipart/form-data">
<table width=100% height=100% border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="621" align="center" valign="top">
    
<table width="0" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" class="a">
<?php echo $header; ?>
  <tr>
    <td width="3" background="../../images/side.gif" valign="top">&nbsp;</td>
    <td width="794" height="466" colspan="4" align="center" valign="top">
<?php
include('includes/mytitle.php');
?>    
      <table width="724" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="724" height="10"></td>
        </tr>
        <tr>
          <td height="297" valign="top" align="center"><table width="629" border="0" cellspacing="0" cellpadding="0">

            <tr>
              <td height="158" align="center"><table width="457" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td align="right">Examination Name: </td>
                    <td>&nbsp;</td>
                    <td align="left"><select name="examname" id="examname" style="font-size:12px; width:200px">
                                              <option value="" style="color:#CCCCCC">[SELECT EXAMINATION NAME]</option>
<?php 
$examname_combo="Select type from type where sync='0' order by id asc";
$examname_combo2=mysql_query($examname_combo);
if (mysql_num_rows($examname_combo2) > 0)
{
while($examname_combo_row = mysql_fetch_row($examname_combo2))
{
echo '<option value="'. $examname_combo_row[0] .'">' . $examname_combo_row[0] . '</option>'; 
} 
}
?>
                      </select>
                        <div><?php echo $msg0; ?></div></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td align="right">Total Number of Questions: </td>
                    <td>&nbsp;</td>
                    <td align="left"><select name="noq" id="noq" style="font-size:12px; width:105px">
                      <option value="60">60 Questions</option>
                       <option value="" disabled>------------------------</option>
                      <option value="10">10 Questions</option>
                      <option value="20">20 Questions</option>
                      <option value="30">30 Questions</option>
                      <option value="40">40 Questions</option>
                      <option value="50">50 Questions</option>
                      <option value="60">60 Questions</option>
                      <option value="70">70 Questions</option>
                      <option value="80">80 Questions</option>
                      <option value="90">90 Questions</option>
                      <option value="100">100 Questions</option>
                    </select>
                      <div><?php echo $msg1; ?></div></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td width="184" align="right">Time Limit : </td>
                    <td width="25">&nbsp;</td>
                    <td width="248" align="left"><select name="notime" id="notime" style="font-size:12px; width:105px">
                        <option value="60 Minutes">60 Minutes</option>
                        <option value="" disabled>------------------------</option>
                        <option value="30 Minutes">30 Minutes</option>
                        <option value="45 Minute">45 Minutes</option>
                        <option value="60 Minutes">60 Minutes</option>
                        <option value="90 Minutes">90 Minutes</option>
                        <option value="120 Minutes">120 Minutes</option>
                      </select>
                        <div><?php echo $msg2; ?></div></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="8"></td>
                  </tr>
                  <tr>
                    <td colspan="3" align="left"><table width="332" height="20" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td width="139">&nbsp;</td>
                          <td width="193"><input type="submit" name="submit" value="  Add  "/></td>
                        </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td colspan="3" height="30"></td>
                  </tr>
                </table>
                  <table width="617" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td colspan="5" height="1" bgcolor="#666666"></td>
                    </tr>
                    <tr>
                      <td colspan="5" height="5"></td>
                    </tr>
                    <tr>
                      <td width="18"></td>
                      <td width="223"></td>
                      <td width="133" align="left"><span style="font-size:12px"><strong><em>No. of Questionaires</em></strong></span></td>
                      <td width="95" align="left"><span style="font-size:12px"><strong><em>Time Limit</em></strong></span></td>
                      <td width="148">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="5" height="5"></td>
                    </tr>
                    <tr>
                      <td colspan="5" height="1" bgcolor="#666666"></td>
                    </tr>
                    <tr>
                      <td colspan="5" height="1"></td>
                    </tr>
                    <tr>
                      <td colspan="5" height="1" bgcolor="#666666"></td>
                    </tr>
                    <tr>
                      <td colspan="5" height="5"></td>
                    </tr>
<?php
$xq="Select * from examtype order by examname";
$yq=mysql_query("$xq",$conn);
$num_row=mysql_num_rows($yq);
if ($num_row=='0')
{
echo '
<tr>
<td height="20" valign="top" colspan="5">Empty Record</td>
</tr>';
}
else
{
$i=0;
while($i < $num_row)
{
$ii = $i+1;
			$xid=mysql_result($yq,$i,"id");
			$xav=mysql_result($yq,$i,"examname");
			$xbv=mysql_result($yq,$i,"totalno");
			$xcv=mysql_result($yq,$i,"totaltime");
			
echo '
<tr>
<td align="left" height="24" valign="top"><span style="color:#aaaaaa">'.$ii.'.</span></td>
<td align="left" valign="top">
	<strong><div style="color:#000000;">'.$xav.'</div></strong>
</td>
<td align="left" valign="top"><span style="font-size:12px; color:#000000"><strong>'. $xbv .' Item(s)</strong></span></td>
<td align="left" valign="top"><span style="font-size:12px; color:#000000"><strong>'. $xcv .'</strong></span></td>
<td align="right" valign="top">
	|&nbsp;&nbsp;&nbsp;
	<a href="examtype_delete.php?id='.$xid.'&admin='.$empno.'" style="text-decoration:none; color:#0000FF">Delete</a> &nbsp;&nbsp;|
</td>
</tr>';
echo '<tr><td align="left" height="1" colspan="5" bgcolor="#666666"></td></tr>';
echo '<tr><td align="left" height="5" colspan="5"></td></tr>';
$i++;
}
}
?>
                    <tr>
                      <td colspan="5" height="27"></td>
                    </tr>
                </table></td>
            </tr>
          </table></td>
        </tr>
      </table>
      <!-- ------------------------------------------------------------------------------------ -->    </td>
    <td width="3" align="right" valign="top" background="../../images/side.gif">&nbsp;</td>
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
