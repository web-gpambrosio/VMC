<?php
session_start();
include('includes/myfunction.php');
include('../includes/conn.php');

if ((!isset($_GET['empno']) || trim($_GET['empno']) == ''))
{ header("location:../index.php"); }
$empno=$_GET['empno'];
include('includes/inc.php');
include('includes/myname.php');

$dformat = "m/d/Y";

$txt1=$_GET['txt1'];
$txt2=$_GET['txt2'];
$txt3=$_GET['txt3'];

if ((!empty($txt1))&&(!empty($txt2)))
{
	$txt1x = date("Y-m-d", strtotime('-1 day'.date($txt1)));
	$txt2x = date("Y-m-d", strtotime('+1 day'.date($txt2)));
}

$qtype = mysql_query("select type from type where id='$txt3'");
$rw_qtype = mysql_num_rows($qtype);
if ($rw_qtype != "0")
{
	$txt3x = mysql_result($qtype,0,"type");
}

$Limit = 20;
$page=$_GET["page"];
if($page == "") $page=1;

if ($txt3=="")
	$ex4se = "exam LIKE '%$txt3%'";
else
	$ex4se = "exam='$txt3'";
$sr = "SELECT * FROM users WHERE ".$ex4se." and testdate between '$txt1x' and '$txt2x'";

$SearchResult=mysql_query($sr) or die(mysql_error()); 
$NumberOfResults=mysql_num_rows($SearchResult); 
$NumberOfPages=ceil($NumberOfResults/$Limit); 
$SearchResult=mysql_query($sr." ORDER BY exam, fname desc LIMIT " . ($page-1)*$Limit . ",$Limit") or die(mysql_error()); 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../includes/body.css" />
<link rel="stylesheet" type="text/css" href="../includes/javac.css" />
<script src="../includes/java.js" type="text/javascript"></script>
<title>Scheduler - ISPS Online Examination</title>
<script type="text/javascript">
var bas_cal,dp_cal,ms_cal;      
window.onload = function () {
  dp_cal  = new Epoch('epoch_popup','popup',document.getElementById('pop1'));
  dp_cal  = new Epoch('epoch_popup','popup',document.getElementById('pop2'));
};
</script>
<body>
<form action="" method="get" name="form" id="form" enctype="multipart/form-data">
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
    <td height="312" valign="top" align="center"><table width="680" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="116" align="left"><strong>From</strong></td>
        <td width="123" align="left"><strong>To</strong></td>
        <td width="223" align="left"><strong>Position</strong></td>
        <td width="218">&nbsp;</td>
      </tr>
      <tr>
        <td height="50" align="left" valign="top"><input id="pop1" type="text" class="tableforb" name="txt1" style="color:#0000FF"
              		 value="<?php if ($txt1=="") { echo date($dformat, strtotime('-2 weeks'.date($dformat))); } else { echo $txt1; } ?>"/>
              <div style="color:#666666; font-size:9px">mm/dd/yyyy</div></td>
        <td align="left" valign="top"><input id="pop2" type="text" class="tableforb" name="txt2" style="color:#0000FF"
              		value="<?php if ($txt2=="") { echo date($dformat); } else { echo $txt2; } ?>"/>
              <div style="color:#666666; font-size:9px">mm/dd/yyyy</div></td>
        <td align="left" valign="top"><select name="txt3" id="examname" style="font-size:12px; width:200px;color:#000000">
          <?php 
$examname_combo="select id, type from type where sync='0' order by id asc";
$examname_combo2=mysql_query($examname_combo);
if ($txt3!="")
{
	echo '<option value="'.$txt3.'">'.$txt3x.'</option>';
	echo '<option disabled>-----------------</option>';
	echo '<option value="">All Position</option>';
}
else
{
	echo '<option value="">All Position</option>';
}

if (mysql_num_rows($examname_combo2) > 0)
{
	while($examname_combo_row = mysql_fetch_row($examname_combo2))
	{
		echo '<option value="'. $examname_combo_row[0] .'">' . $examname_combo_row[1] . '</option>'; 
	} 
}	
?>
        </select></td>
        <td valign="top" align="left"><input id="submit" value="search" src="../images/searchb.gif" type="image" />
          &nbsp;&nbsp;&nbsp;&nbsp;
          <?php
if ((!empty($txt1))&&(!empty($txt2)))
{
echo '<a href="administrator/print.php?txt1='.$txt1x.'&txt2='.$txt2x.'&txt3='.$txt3.'" target="_blank"><img src="../images/print.gif" width="86" height="19" border="0" /></a>';
}
?>
        </td>
      </tr>
    </table>
        <input type="hidden" name="empno" id="empno" value="<?php echo $empno; ?>"/>
        <?php
$num_row=mysql_num_rows($SearchResult);
if ($num_row!='0')
{
?>
        <table width="721" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #333333">
          <tr>
            <td colspan="6" height="10"></td>
          </tr>
          <tr align="left" valign="top" style="font-size:13px; font-weight:bold; text-decoration:underline">
            <td width="14"></td>
            <td width="90">CREWCODE</td>
            <td width="269">NAME</td>
            <td width="163">TYPE OF EXAM</td>
            <td width="95">TEST DATE</td>
            <td width="88">SCORE (%)</td>
          </tr>
          <tr>
            <td colspan="6" height="5"></td>
          </tr>
          <tr>
            <td colspan="6" height="1" bgcolor="#333333"></td>
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
		$row->id;
		$remarks;

		$stypeofexam = mysql_query("select type from type where id='".$row->exam."'") or die(mysql_error()); 
		$rwtypeofexam = mysql_num_rows($stypeofexam);
		if ($rwtypeofexam != "0")
		{
			$rtype = mysql_result($stypeofexam,0,'type');
		}
		
		$dtestdate = date("d-M-Y", strtotime($row->testdate));

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
	if ($pfg == "" || $pfg == "0")
	{
		echo "---------";
	}
	elseif ($pfg >= $crewgrade)	
		{
			$pstatus = '<span style="color:#009900; font-size:11px"><strong>('.$pfg.'%) PASSED</strong></span>';
		}
		else
		{
			$pstatus = '<span style="color:#FF0000; font-size:11px"><strong>('.$pfg.'%) FAILED</strong></span>';
		}
	}		
		
		echo '<tr align="left" valign="top" style="font-size:12px; cursor:pointer"
			  onmouseover="this.style.backgroundColor=\'#639aff\'" onmouseout="this.style.backgroundColor=\'\'">
			<td>&nbsp;</td>
			<td>'.$row->crewcode.'</td>
			<td>'.$row->fname.', '.$row->gname.' '.substr($row->mname,0,1).'.</td>
			<td>'.$rtype.'</td>
			<td>'.$dtestdate.'</td>
			<td>'.$pstatus.'</td>
		  	</tr>
		  	<tr>
			<td colspan="6" height="2"></td>
			</tr>';
	} 
?>
        </table>
      <?php
}
?>
    </td>
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
