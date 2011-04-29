<?php
session_start();
include('includes/myfunction.php');
include('../includes/conn.php');

if ((!isset($_GET['empno']) || trim($_GET['empno']) == ''))
{ header("location:../index.php"); }

$empno=$_GET['empno'];
include('includes/inc.php');
include('includes/myname.php');

$xcrewcode = $_GET['crewcode'];
$idx = $_GET['id'];

$xexamdatex = date("Y-m-d");

	$vav=mysql_query("Select id, type from type where id='$idx'");
	if (mysql_num_rows($vav) > 0)
	{
	$examid=mysql_result($vav,0,"id");
	$examnamem=mysql_result($vav,0,"type");
	}

$search_crew_table="Select fname, gname, mname, bdate, crewcode from crew where crewcode='".$xcrewcode."'";
$search_crew_query=mysql_query("$search_crew_table",$conn);
$search_crew_row=mysql_num_rows($search_crew_query);
if ($search_crew_row!='0')
{
$xfname = mysql_result($search_crew_query,0,"fname");
$xgname = mysql_result($search_crew_query,0,"gname");
$xmname = mysql_result($search_crew_query,0,"mname");
$pbdate = mysql_result($search_crew_query,0,"bdate");
$pcrewcode = mysql_result($search_crew_query,0,"crewcode");
$xbdate = date("F d, Y",strtotime($pbdate));
}

$n="Select count(takeno) As takeno from users where crewcode='".$xcrewcode."' order by takeno desc limit 1";
$m=mysql_query("$n",$conn);
$o=mysql_num_rows($m);
if ($o!='0')
{
$p = mysql_result($m,0,"takeno");
$xtakeno = $p + 1;
}
else
{
$xtakeno = "1";
}

switch ($xtakeno)
{
case "1": $y = "<sup>st</sup>"; break;
case "2": $y = "<sup>nd</sup>"; break;
case "3": $y = "<sup>rd</sup>"; break;
default: $y = "<sup>th</sup>"; break;
	}
	
	
$error_1 = '<script type="text/javascript">alert("System Error: #E192-0. Please Contact Veritas IT Staff. Thank You.")</script>';
$error_2 = '<script language="javascript">window.location.href="index.php?empno='.$empno.'"</script>';
	

if (isset($_POST['exam_activated']))
{
	$error=array();
	$xexamname = $_POST['xexamname'];
	// $txtvessel = $_POST['txtprincipal'];
	$xrand = $_POST['xrand'];	
	
	
	
	if (trim($xexamname) == '') { $error[0] = "<div class='warning_message'>Select Type of Exam</div>"; }
	
	if (sizeof($error) == '0')
	{
// ---------------------------- >
		$type4loop=mysql_query("select type from type where id='$xexamname'",$conn);
		$type4loop_row=mysql_num_rows($type4loop);
		if ($type4loop_row!='0')
		{
			$examname2 = mysql_result($type4loop,0,"type");
		}
		else
		{
			echo $error_1 . $error_2 . mysql_close($conn);
		}
// ---------------------------- >
		$total_exam = mysql_query("select sum(totalno) As totalno from examtype where examname='$examname2' limit 1");
		$totalnoexam=mysql_num_rows($total_exam);
		if ($totalnoexam!='0')
		{
			$xtotal_exam2 = mysql_result($total_exam,0,"totalno");
		}
		else
		{
			echo $error_1 . $error_2 . mysql_close($conn);
		}
		
		
// ---------------------------- >
		$totalq = mysql_query("select count(id) As id from questions where examtype='$xexamname'");
		$xtotalq = mysql_result($totalq,0,"id");
		
		if (($xtotalq > $xtotal_exam2)||($xtotalq == $xtotal_exam2))
		{
// ---------------------------- >
			$select2 = mysql_query("select id from questions where examtype='$xexamname' order by rand()");
			$select2ssss=mysql_num_rows($select2);
			
			if ($select2ssss != '0')
			{
				for ($inumx=0; $inumx<$xtotal_exam2; $inumx++)
				{	
					$catxcv=mysql_result($select2,$inumx,"id");
					mysql_query("insert into users_exam (crewcode, qid, take, examdate) values ('$xcrewcode','$catxcv','$xtakeno','$xexamdatex')")
					or die(mysql_error());
				}
			}
			else
			{
				echo '<script type="text/javascript">alert("System Error: #E173-'.$xexamname.'. Please report this error to the ISPS Adminitrator")</script>';
				echo $error_2 . mysql_close($conn);
			}
		}
		else
		{
				echo '<script type="text/javascript">alert("System Error: #E154-'.$xexamname.'. Please report this error to the ISPS Adminitrator")</script>';
				echo $error_2 . mysql_close($conn);
		}
// ---------------------------- >
	$queryv = "insert into users (crewcode, passcode, exam, takeno, fname, gname, mname, bdate) values
						('$xcrewcode', '$xrand', '$xexamname', '$xtakeno', '$xfname', '$xgname', '$xmname', '$pbdate')";
	$resultv = mysql_query($queryv) or die ("Error in query: $query. " . mysql_error());
	mysql_close($conn);

// ---------------------------- >
	echo "<script type='text/javascript'>alert('Exam Activated!')</script>";
	echo "<script language=\"javascript\">window.location.href='isps_crew.php?empno=" . $empno . "'</script>";	
	}
	else
	{
		for ($x=0; $x<sizeof($error); $x++)
		{
			$msg1 = $error[0];
		}
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../includes/body.css" />
<title>Scheduler - ISPS Online Examination</title>
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
              <td height="119" align="center" valign="top"><table width="620" height="39" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="572" height="39" align="center"><br />
                    
<div style="font-size:16px; color:#0000FF">Search Crew from<strong> International Ship and Port Facility Security </strong>Database</div>
<div style="font-size:10px">&nbsp;</div>
<div><input type="button" name="button" value="  Search  " accesskey="S" onclick="location.href='sched.php?empno=<?php echo $empno; ?>'"/></div>

</td>
                  </tr>
                </table>
                  <hr />
                  <?php 
if ($xcrewcode != "")
{
?>
                  <table width="421" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td colspan="3" height="19"></td>
                    </tr>
                    <tr>
                      <td width="138" align="right"><span style="font-size:15px; color:#666666">Crew Code:</span></td>
                      <td width="17">&nbsp;</td>
                      <td width="266" align="left"><span style="font-size:15px; color:#0000FF"><strong><?php echo $xcrewcode; ?></strong></span></td>
                    </tr>
                    <tr>
                      <td colspan="3" height="8"></td>
                    </tr>
                    <tr>
                      <td align="right"><span style="font-size:13px; color:#666666">Name :</span></td>
                      <td>&nbsp;</td>
                      <td align="left"><span style="font-size:13px; color:#666666"> </span><span style="font-size:13px; color:#000000"><?php echo '<strong>' .$xfname . '</strong> ' . $xgname . ', ' . $xmname; ?>.</span>
                          <div></div></td>
                    </tr>
                    <tr>
                      <td colspan="3" height="8"></td>
                    </tr>
                    <tr>
                      <td align="right"><span style="font-size:13px; color:#666666">Date of Birth:</span></td>
                      <td>&nbsp;</td>
                      <td align="left"><span style="font-size:13px; color:#000000"><strong><u><?php echo $xbdate; ?></u></strong></span></td>
                    </tr>
                    <tr>
                      <td colspan="3" height="8"></td>
                    </tr>
                    <tr>
                      <td align="right"><span style="font-size:13px; color:#666666">Take:</span></td>
                      <td>&nbsp;</td>
                      <td align="left"><span style="font-size:13px; color:#666666"> </span><span style="font-size:13px; color:#006600"><?php echo $xtakeno.$y.' Take'; ?></span>
                          <div></div></td>
                    </tr>
                    <tr>
                      <td colspan="3" height="8"></td>
                    </tr>
                    <tr>
                      <td colspan="3" height="1" bgcolor="#CCCCCC"></td>
                    </tr>
                    <tr>
                      <td colspan="3" height="10"></td>
                    </tr>
                    <tr>
                      <td align="right"><span style="font-size:13px; color:#666666">Type of Examination:</span></td>
                      <td>&nbsp;</td>
                      <td align="left">
<?php
$dbexamtype="Select distinct type.id As id, type.type As type from type, questions where type.id = questions.examtype order by type.id asc";
$dbrexamtype=mysql_query($dbexamtype);
echo "<select name=\"xexamname\" style=\"font-size:12px; width:250px; font-family:Verdana;\"> ";
if ($examnamem=="")
{
echo "<option value=''>--- Select Type of Examination ---</option>";
}
else
{
echo "<option value=".$examid.">".$examnamem."</option>";
}
while($row = mysql_fetch_assoc($dbrexamtype))
{
echo "<option value=\"{$row['id']}\" style='color:#333333'>{$row['type']}</option> ";
}
echo "</select> ";
?>

<div><?php echo $msg1; ?></div></td>
                    </tr>
                    <tr>
                      <td colspan="3" height="8"></td>
                    </tr>
                    <tr>
                      <td colspan="3" height="9"></td>
                    </tr>
                    <tr>
                      <td align="right"><span style="font-size:13px; color:#666666">Passcode :</span></td>
                      <td>&nbsp;</td>
                      <td align="left"><span style="font-size:15px; color:#0000FF"><strong><u>
                        <input type="hidden" name="xrand" id="xrand" value="<?php echo $xrand = rand(1, 9).$xtakeno.rand(1, 9). '-' .rand(100, 999); ?>"/>
                        <?php echo $xrand; ?></u></strong></span><span style="font-size:10px; color:#990000"></span> </td>
                    </tr>
                    <tr>
                      <td colspan="3" height="25"></td>
                    </tr>
                    <tr>
                      <td colspan="3" height="1" bgcolor="#CCCCCC"></td>
                    </tr>
                    <tr>
                      <td colspan="3" height="8"></td>
                    </tr>
                    <tr>
                      <td colspan="3" height="8"></td>
                    </tr>
                    <tr>
                      <td colspan="3" align="left"><table width="332" height="20" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="139">&nbsp;</td>
                            <td width="193"><input type="submit" name="exam_activated" value="Save"/></td>
                          </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td colspan="3" height="8"></td>
                    </tr>
                  </table>
                <?php
}
?>
              </td>
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
