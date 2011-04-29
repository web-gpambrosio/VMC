<?php
include('../../includes/conn.php');
$txt1=$_GET['txt1'];
$txt2=$_GET['txt2'];
$txt3=$_GET['txt3'];

if ($txt3=="")
	$ex4se = "exam LIKE '%$txt3%'";
else
	$ex4se = "exam='$txt3'";
$sr = "SELECT * FROM users WHERE ".$ex4se." and testdate between '$txt1' and '$txt2' ORDER BY exam, fname desc";
$SearchResult=mysql_query($sr) or die(mysql_error());

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ISPS Online Examination - Report</title>
<style type="text/css">
body { 
font-family: Arial;
color:#333333;
font-size:12px;
text-decoration:none;
}
.xtitle { color:#000000; font-family:Verdana; font-size:16px }
</style>
</head>
<body>
<table width="800" height="247" border="0" cellpadding="0" cellspacing="0" align="center" style="margin:5 5 5 5">
  <tr>
    <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="62" valign="top" align="right">
        <table width="597" height="48" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td colspan="3" height="10"></td>
            </tr><tr>
            <td width="41" align="left" valign="top"><img src="../../images/tnkc.gif" width="27" height="42" /></td>
            <td width="250" align="center" valign="top"><span class="xtitle"><strong>ISPS Online Examination</strong><br />
              <img src="../../images/book.gif" width="12" height="12"/> Report</span></td>
            <td width="267" valign="bottom" align="right"><span style="color:#666666; font-size:10px">PRINT DATE: <strong><?php echo date("F d, Y"); ?></strong></span>&nbsp;&nbsp;&nbsp;</td>
          </tr>
        </table></td>
      </tr>
    </table>
 <?php
$num_row=mysql_num_rows($SearchResult);
if ($num_row!='0')
{
?>
<table width="779" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td colspan="6" height="10"></td>
    </tr>
  <tr align="left" valign="top" style="font-size:13px; font-weight:bold; text-decoration:underline">
    <td width="33"></td>
    <td width="97">CREWCODE</td>
    <td width="281">NAME</td>
    <td width="148">TYPE OF EXAM</td>
    <td width="135">TEST DATE</td>
    <td width="85">SCORE (%)</td>
  </tr><tr>
    <td colspan="6" height="5"></td>
    </tr>
  <tr>
    <td colspan="6" height="3" style="border-bottom:double 3px #333333"></td>
    </tr>
     <tr>
    <td colspan="6" height="5"></td>
    </tr>
  <tr>
    <td colspan="6" height="2"></td>
    </tr>
<?php
	while($row = mysql_fetch_object($SearchResult))
	{
	$ii = $i+1;

		$stypeofexam = mysql_query("select type from type where id='".$row->exam."'") or die(mysql_error()); 
		$rwtypeofexam = mysql_num_rows($stypeofexam);
		if ($rwtypeofexam != "0")
		{
			$rtype = mysql_result($stypeofexam,0,'type');
		}
		
		$dtestdate = date("d-M-Y / <b>H:i</b>", strtotime($row->testdate));

//-----------
$qexamtype_ue=mysql_query("select examtype.totalno As totalnoexam from users, examtype, type 
						   where type.id = users.exam and type.type=examtype.examname and users.crewcode='".$row->crewcode."'");	
$totalno_que=mysql_result($qexamtype_ue,0,"totalnoexam");
//-----------
$qcorrect_uexam=mysql_query("select count(correct) As correct_answer from users_exam
							 where crewcode='".$row->crewcode."' and take='".$row->takeno."' and correct ='1'",$conn);
$scorefg=mysql_result($qcorrect_uexam,0,"correct_answer");
//-----------
$percentscfg = (($scorefg / $totalno_que)*100);
$pfg = round($percentscfg,0);
//-----------
$query_result_percentage=mysql_query("select sum(grade) As grade from passing order by id desc limit 1",$conn);
$crewgrade=mysql_result($query_result_percentage,0,"grade");
//-----------
if (($row->testdate != "0000-00-00 00:00:00")&&($pfg == "0"))
{
	$pstatus = '<span style="color:#FF0000; font-size:11px"><strong>(0%) FAILED</strong></span>';
}
else
{			
	if ($pfg >= $crewgrade)	
		{
			$pstatus = '<span style="color:#009900; font-size:11px"><strong>('.$pfg.'%) PASSED</strong></span>';
		}
		else
		{
			$pstatus = '<span style="color:#FF0000; font-size:11px"><strong>('.$pfg.'%) FAILED</strong></span>';
		}
	}		
		
		echo '<tr align="left" valign="top" style="font-size:12px; cursor:pointer">
			<td>&nbsp;&nbsp;&nbsp;'.$ii.'. </td>
			<td>'.$row->crewcode.'</td>
			<td>'.$row->fname.', '.$row->gname.' '.substr($row->mname,0,1).'.</td>
			<td>'.$rtype.'</td>
			<td>'.$dtestdate.'</td>
			<td>'.$pstatus.'</td>
		  	</tr>
		  	<tr>
			<td colspan="6" height="2"></td>
			</tr>';
	$i++;
	} 
?>
</table>
<?php
}
?>
    </td>
  </tr>
</table>

</body>
</html>
