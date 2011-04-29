<?php
session_start();

if(!session_is_registered(empno))
{
header("location:../scheduler.php");
}

if ((!isset($_GET['empno']) || trim($_GET['empno']) == ''))
{
header("location:../scheduler.php");
}
include('connection/conn.php');
$empno=$_GET['empno'];
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
$xbdate = date("m/d/Y",strtotime($pbdate));
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
	
$xcode="Select id, lname, fname, mname, position from scheduler where empno='$empno'";
$query_result=mysql_query("$xcode",$conn);
$id=mysql_result($query_result,0,"id");
$lname=mysql_result($query_result,0,"lname");
$fname=mysql_result($query_result,0,"fname");
$mname=mysql_result($query_result,0,"mname");
$position=mysql_result($query_result,0,"position");



if (isset($_POST['exam_activated']))
{

	$error=array();
	$xexamname = $_POST['xexamname'];
	$txtvessel = $_POST['txtprincipal'];
	$xrand = $_POST['xrand'];	
	
	if (trim($xexamname) == '') { $error[0] = "<div class='warning_message'>Select Type of Exam</div>"; }
	if (trim($txtvessel) == '') { $error[1] = "<div class='warning_message'>Select Vessel</div>"; }
	
	if (sizeof($error) == '0')
	{
		$vessel_principal_manipulate = mysql_query("select pbw.type As xpbw, vessel.principal As principal, vessel.type As type,
													type.type As type2x from vessel, type, pbw where vessel.id='$txtvessel' and
													type.id='$xexamname' and vessel.type = pbw.id");
		$vessel_principal_manipulate_row=mysql_num_rows($vessel_principal_manipulate);
		
		if ($vessel_principal_manipulate_row != "0")
		{
			$txtprincipal=mysql_result($vessel_principal_manipulate,0,"principal");
			$txtprincipal_type=mysql_result($vessel_principal_manipulate,0,"type");
			$examname_exam=mysql_result($vessel_principal_manipulate,0,"type2x");
			$xxpbw=mysql_result($vessel_principal_manipulate,0,"xpbw");
		}
		else
		{
			echo '<script type="text/javascript">alert("Program Error!\n" + "Please report this error to Veritas IT Department.")</script>' ;
			echo "<script language=\"javascript\">window.location.href='connection/mylogout.php'</script>";
			mysql_close($conn);
		}

		$qwe = mysql_query("select distinct idcat from exam_cat where examname='$examname_exam' and principal='$txtprincipal'
							and type='$txtprincipal_type'");
		$qwe1=mysql_num_rows($qwe);
		
		if (($qwe1 != "") || ($qwe1 != 0))
		{
			for ($iidcat=0; $iidcat<$qwe1; $iidcat++)
			{
				
				$idcat_examcat=mysql_result($qwe,$iidcat,"idcat");
				
				$ax1 = mysql_query("select sum(total) As sum_total from exam_cat where idcat='$idcat_examcat' and
									examname='$examname_exam' and principal='$txtprincipal' and type='$txtprincipal_type'");
				$sum_total1=mysql_result($ax1,0,"sum_total");
				
				$select2 = mysql_query("select id from questions where category='$idcat_examcat' and examtype='$xexamname'
										and principal='$txtprincipal' and type='$txtprincipal_type' order by rand()");
				$select2ssss=mysql_num_rows($select2);
				
				if ($select2ssss != "0")
				{
					for ($inumx=0; $inumx<$sum_total1; $inumx++)
					{	
						$catxcv=mysql_result($select2,$inumx,"id");
						mysql_query("insert into users_exam (crewcode, qid, take, examdate) values ('$xcrewcode','$catxcv','$xtakeno','$xexamdatex')")
						or die(mysql_error());
					}
				}
				else
				{
					echo '<script type="text/javascript">alert("Questionares Error!\n" + "Type of Vessel: '. $xxpbw .'\n" + "Please report
																this error to the ISM Adminitrator")</script>';
					echo "<script language=\"javascript\">window.location.href='scheduler_page.php?empno=$empno'</script>";
					mysql_close($conn);
				}
			}
			$vessel_search_principaltoid=mysql_query("Select principal from vessel where id='".$txtvessel."'");
			$vessel_search_principaltoid_row=mysql_num_rows($vessel_search_principaltoid);
			if ($vessel_search_principaltoid_row!='0')
			{
				$principalj = mysql_result($vessel_search_principaltoid,0,"principal");
			}	
			
			$nr="select xtake from users where crewcode='".$xcrewcode."' and exam='".$xexamname."' order by xtake desc limit 1";
			$mr=mysql_query("$nr",$conn);
			$or=mysql_num_rows($mr);
			if ($or!='0')
			{
			$pr = mysql_result($mr,0,"xtake");
			$xtakex = $pr + 1;
			}
			else
			{
			$xtakex = "1";
			}
		
			$queryv = "insert into users (crewcode, passcode, exam, takeno, fname, gname, mname, vessel, bdate, principal, xtake) values
						('$xcrewcode', '$xrand', '$xexamname', '$xtakeno', '$xfname',
						'$xgname', '$xmname', '$txtvessel', '$pbdate', '$principalj', '$xtakex')";			
			$resultv = mysql_query($queryv) or die ("Error in query: $query. " . mysql_error());
			mysql_close($conn);
		}
		else
		{
			echo '<script type="text/javascript">alert("Questionares Error!\n" + "Type of Exam: '. $examname_exam .'\n" + "
					Please report this error to the ISM Adminitrator")</script>' ;
			echo "<script language=\"javascript\">window.location.href='scheduler_page.php?empno=$empno'</script>";
			mysql_close($conn);
		}
		
		echo "<script type='text/javascript'>alert('Exam Activated!')</script>" ;
		echo "<script language=\"javascript\">window.location.href='crew_page.php?empno=" . $empno . "'</script>";	
	}
	else
	{
		for ($x=0; $x<sizeof($error); $x++)
		{
			$msg1 = $error[0];
			$msg2 = $error[1];
		}
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $fname.' '.$lname.' : '.$position; ?></title>
<link rel="stylesheet" type="text/css" href="style/body.css" />
<link rel="stylesheet" type="text/css" href="style/text_style.css" />
<SCRIPT language=JavaScript>
function reload(form)
{
var val=form.xexamname.options[form.xexamname.options.selectedIndex].value;
self.location='scheduler_page.php?empno=<?php echo $empno; ?>&crewcode=<?php echo $xcrewcode; ?>&id=' + val;
}
</script>
<style type="text/css">
<!--
.style1 {font-weight: bold}
-->
</style></head>
<body topmargin="10" leftmargin="15" background="images/background.gif">

<table width=101% height=100% border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
    	<td height="427" align="center" valign="top"><table width="800" height="388" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" background="images/sideline.gif">
          <tr>
            <td width="800" height="105" align="left" background="images/top.gif">
            </td>
          </tr>
          <tr>
            <td height="52" align="left"><table width="755" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="79">&nbsp;</td>
                  <td width="268" align="left"><img src="images/title.gif" width="257" height="49" /></td>
                  <td width="42">&nbsp;</td>
                  <td width="366">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td height="12"><img src="images/sideline.gif" width="800"/></td>
          </tr>
          <tr>
            <td height="106" align="center" valign="top"><table width="629" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="17" align="right">
                <a href="crew_page_add.php?empno=<?php echo $empno; ?>" style="text-decoration:none; color:#0033FF">ADD CREW</a> &nbsp;&nbsp;&nbsp; 
                <a href="crew_page.php?empno=<?php echo $empno; ?>" style="color:#0033FF; text-decoration:none">Crew</a> &nbsp;&nbsp;&nbsp; <a href="connection/mylogout.php" style="text-decoration:none; color:#0033FF">Log Out</a>
                </td>
              </tr>
              <tr>
                <td height="5"></td>
              </tr>
              <tr>
                <td height="5"></td>
              </tr>
              <tr>
                <td height="119" align="center" valign="top"><table width="629" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="1" height="32"><img src="images/gray_side.gif" width="1" height="32"/></td>
                      <td width="639" height="32" background="images/gray_mid.gif" align="left"><div style="font-size:14px; color:#000000">&nbsp;&nbsp;&nbsp;&nbsp; <?php echo '<b>'.$fname.' '.substr($mname,0,1).'. '.$lname .'</b> : '. $position; ?> </div></td>
                      <td width="1" height="32"><img src="images/gray_side.gif" width="1" height="32"/></td>
                    </tr>
                  </table>
<form action="" method="post" enctype="multipart/form-data" name="form" id="form">
<table width="620" height="20" border="0" cellpadding="0" cellspacing="0">
  <tr>
<td width="572" align="center"><br />
<span style="font-size:16px; color:#0000FF">Search Crew from<strong> International Safety Management </strong>Database</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>
<input type="button" name="button" value="  Search  " accesskey="S" onclick="location.href='veripro.php?empno=<?php echo $empno; ?>'"/></span></td>
</tr></table>
<hr />
<?php 
if ($xcrewcode != "")
{
?> 

<table width="382" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td colspan="3" height="19">                      </td>
                    </tr>
                    <tr>
                      <td width="131" align="right"><span style="font-size:13px; color:#666666">Applicant No.:</span></td>
                      <td width="22">&nbsp;</td>
                      <td width="246" align="left"><span style="font-size:13px; color:#000000"><?php echo $xcrewcode; ?></span></td></tr>
                    <tr>
                      <td colspan="3" height="8"></td>
                    </tr>
                    <tr>
                      <td align="right"><span style="font-size:13px; color:#666666">Name :</span></td>
                      <td>&nbsp;</td>
                      <td align="left"><span style="font-size:13px; color:#666666"> </span><span style="font-size:13px; color:#000000"><?php echo '<strong>' .$xfname . '</strong> ' . $xgname . ', ' . $xmname; ?>.</span>
                        <div></div></td></tr>
                    <tr>
                      <td colspan="3" height="8"></td>
                    </tr>
                    <tr>
                      <td align="right"><span style="font-size:15px; color:#666666">Date of Birth:</span></td>
                      <td>&nbsp;</td>
                      <td align="left">
                      <span style="font-size:15px; color:#0000FF"><strong><u><?php echo $xbdate; ?></u></strong></span>
                      &nbsp;&nbsp;&nbsp;
                      <span style="font-size:11px; color:#999999">(mm/dd/yyyy)</span>                      </td>
                    </tr>
                    <tr>
                      <td colspan="3" height="8"></td>
                    </tr>
                    <tr>
                      <td align="right"><span style="font-size:13px; color:#666666">Take:</span></td>
                      <td>&nbsp;</td>
                      <td align="left"><span style="font-size:13px; color:#666666"> </span><span style="font-size:13px; color:#006600"><?php echo $xtakeno.$y.' Take'; ?></span>
                      <div></div></td></tr>
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
                      <td align="right">Type of Exam : </td>
                      <td>&nbsp;</td>
                      <td align="left"><div>
<?php
$dbexamtype="Select id, type from type order by id asc";
$dbrexamtype=mysql_query($dbexamtype);
echo "<select name=xexamname style=\"font-size:12px; width:250px; font-family:Verdana;\" onchange=\"reload(this.form)\"> ";
if ($examnamem=="")
{
echo "<option value='' >--- Select Type of Examination ---</option>";
}
else
{
echo "<option value=".$examid.">".$examnamem."</option>";
}
while($row = mysql_fetch_assoc($dbrexamtype))
{
echo "<option value=\"{$row['id']}\" style='color:#666666'>{$row['type']}</option> ";
}
echo "</select> ";
?>
                        </div>
                          <div><?php echo $msg1; ?></div></td>
                    </tr>
                    <tr>
                      <td colspan="3" height="8"></td>
                    </tr>
                    
<?php

$principal_tmp = "";

$viewing_principal=mysql_query("Select distinct principal from examtype where examtype.examname = '$examnamem'");
$viewing_principal_rows=mysql_num_rows($viewing_principal);
if ($viewing_principal_rows != '0')
{

	echo '<tr><td align="right" valign="top"> Vessel : </td><td>&nbsp;</td><td align="left">';
	echo "<select name=txtprincipal style=\"font-size:12px; font-family:Verdana;\">";
	
	while($rowprincipal = mysql_fetch_row($viewing_principal))
	{
	
		$vtype_combo="Select distinct vessel.id, vessel.vessel, vessel.type from vessel, examtype
						where examtype.principal = '$rowprincipal[0]' and examtype.examname='$examnamem' and
						examtype.type = vessel.type and examtype.principal = vessel.principal";
		$vtype_combo2=mysql_query($vtype_combo);
	
		if ($vtype_combo2=="")
		{
			echo "<option value=''></option>";
		}
		elseif (($exampbwid!="")&&($exampbwtype!=""))
		{
			echo "<option value=".$exampbwid.">".$exampbwtype."</option>";
		}
		else
		{
			switch ($rowprincipal[0])
			{
			case "1" : $principal_show = "TNKC KOBE"; break;
			case "2" : $principal_show = "TNKC MANILA"; break;	
			case "3" : $principal_show = "STARGATE BREMEN"; break;				
						
			}
			echo "<option value='' style=\"color:#333333\">---------".$principal_show."---------</option>";
		}
	
		while($rowvtype = mysql_fetch_row($vtype_combo2))
		{	
			if ($rowprincipal[0] != $principal_tmp)
			{
					
				switch ($rowvtype[2])
				{
				case "1": $bluex = "000000"; break;
				case "2": $bluex = "0000FF"; break;
				case "3": $bluex = "006600"; break;
				default: $bluex = "FF0000"; break;
				}
				echo "<option value=\"$rowvtype[0]\" style=\"color:#". $bluex ."\">$rowvtype[1]</option> ";
			}
		}
	}
	echo "</select>";	
	echo '<div style="font-size:5px">&nbsp;</div>
      <div style="color:#300000; font-size:9px">| BULK - Black</div>
      <div style="color:#3000FF; font-size:9px">| PCC - Blue</div>
      <div style="color:#306600; font-size:9px">| Woodship - Green</div>';
	
	
echo '<div>'. $msg2 .'</div></td></tr>';
}
?>
                    <tr>
                      <td colspan="3" height="9"></td>
                    </tr>
                    <tr>
                      <td align="right"><span style="font-size:13px; color:#666666">Passcode :</span></td>
                      <td>&nbsp;</td>
                      <td align="left"><span style="font-size:15px; color:#0000FF"><strong><u>
					  <input type="hidden" name="xrand" id="xrand" value="<?php echo $xrand = rand(1, 9).$xtakeno.rand(1, 9). '-' .rand(100, 999); ?>"/>
					  <?php echo $xrand; ?></u></strong></span><span style="font-size:10px; color:#990000"></span> </td></tr>
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
                            <td width="193"><input type="submit" name="exam_activated" value="&nbsp;&nbsp;&nbsp;SUBMIT&nbsp;&nbsp;&nbsp;"/></td>
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
                  
</form>
                </td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td background="images/bottom.gif" height="112"></td>
          </tr>
        </table></td>
</tr>
</table>

</body>
</html>
