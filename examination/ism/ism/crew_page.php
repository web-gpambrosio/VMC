<?php
session_start();

if(!session_is_registered(empno))
{
header("location:../scheduler.php");
}
include('connection/conn.php');
$empno=$_GET['empno'];

$xxx=@$_GET['xxx'];
$txtsearch=@$_GET['txtsearch'];

$Limit = 40;
$page=$_GET["page"];
if($page == "") $page=1;

if ($xxx != "")
{
switch ($xxx)
{
case "1": $zzz = "fname"; break;
case "2": $zzz = "gname"; break;
case "3": $zzz = "crewcode"; break;
	}
}
else
{
$zzz = "fname";
}
	
$SearchResult=mysql_query("SELECT distinct crewcode, fname, gname, mname, bdate FROM crew WHERE gname != '' and fname != '' and crewcode != '' and $zzz LIKE '$txtsearch%'") or die(mysql_error()); 
$NumberOfResults=mysql_num_rows($SearchResult); 
$NumberOfPages=ceil($NumberOfResults/$Limit); 
$SearchResult=mysql_query("SELECT distinct crewcode, fname, gname, mname, bdate FROM crew WHERE gname != '' and fname != '' and crewcode != '' and $zzz LIKE '$txtsearch%' ORDER BY id desc LIMIT " . ($page-1)*$Limit . ",$Limit") or die(mysql_error());
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VERIPRO Database</title>
<link rel="stylesheet" type="text/css" href="style/body.css" />
<link rel="stylesheet" type="text/css" href="style/text_style.css" />
<style type="text/css">
a { text-decoration:none; color:#aaaaaa }
</style>

<script> 
function showhide(id)
{ 
	if (document.getElementById)
	{ 
		obj = document.getElementById(id); 
		if (obj.style.display == "none")
		{ 
			obj.style.display = ""; 
		}
		else
		{ 
		obj.style.display = "none"; 
		} 
	} 
} 
</script>

</head>
<body topmargin="10" leftmargin="15">
<form action="" method="get" enctype="multipart/form-data" name="form" id="form">
<table width=101% height=100% border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
    	<td height="427" align="center" valign="top"><table width="800" height="388" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" background="images/sideline.gif">
          <tr>
            <td width="800" height="105" align="left" background="images/top.gif"></td>
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
                <td height="17" align="right"><a href="crew_page_add.php?empno=<?php echo $empno; ?>" style="text-decoration:none; color:#0033FF">ADD CREW</a> &nbsp;&nbsp;&nbsp;<a href="scheduler_page.php?empno=<?php echo $empno; ?>" style="text-decoration:none; color:#0033FF">Back</a>                </td>
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
                      <td width="639" height="32" background="images/gray_mid.gif" align="left"><div style="font-size:14px; color:#000000"></div></td>
                      <td width="1" height="32"><img src="images/gray_side.gif" width="1" height="32"/></td>
                    </tr>
                  </table>
<table width="616" height="80" border="0" cellpadding="0" cellspacing="0">
<tr><td height="8" colspan="6" align="left">
<br />
	<img src="images/search.gif" /><span style="font-size:13px"> Search by: </span>
	
    <input type="hidden" name="empno" id="empno" value="<?php echo $empno; ?>"/>
	<select name="xxx" id="xxx">
    <?php
	if ($xxx != "")
	{
		switch ($xxx)
		{
			case "1": { $xxxx = "Family Name"; $zzzz = "1"; } break;
			case "2": { $xxxx = "Given Name"; $zzzz = "2"; } break;
			case "3": { $xxxx = "Crew Code"; $zzzz = "3"; } break;
		}
	echo '<option value='.$zzzz.'>'. $xxxx .'</option>';
	}
	?>
		<option value="1" style="color:#666666">Family Name</option>
		<option value="2" style="color:#666666">Given Name</option>
		<option value="3" style="color:#666666">Crew Code</option>
	</select>&nbsp;&nbsp;
    
	<input type="text" name="txtsearch" id="txtsearch"/>&nbsp;&nbsp;
    
	<input type="submit" name="submit" value=" Search " style="font-size:11px" />
	<input type="submit" name="submit2" value=" Refresh " style="font-size:11px" />
    
</td>
                    </tr>
                    <tr>
                      <td height="10" colspan="6"></td>
                    </tr>
                    <tr>
                      <td height="1" bgcolor="#666666" colspan="6"></td>
                    </tr>

                    <tr>
                      <td align="center" colspan="6" height="20">

<span style="color:#333333; font-size:12px">
<?php

	echo 'Page ' . $page . ' of ' . $NumberOfPages . '&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;';
	
	if($page > 1) { 
		echo "<a href=\"$PHP_SELF?page=1" . "&empno=$empno&txtsearch=$txtsearch&xxx=$xxx\"><<</A>"; 
		echo "&nbsp;&nbsp;";
		echo "<a href=\"$PHP_SELF?page=" . ($page-1) . "&empno=$empno&txtsearch=$txtsearch&xxx=$xxx\"><</A>"; 
		echo "&nbsp;&nbsp;";
		} 

	if ($page - 3 < 1) {
		$pagemin = 1;
		}
		else {
		$pagemin = $page - 3;
		}

	if ($page + 3 > $NumberOfPages) {
		$pagemax = $NumberOfPages;
		}
		else {
		$pagemax = $page + 3;
		}

	for($i = $pagemin ; $i <= $pagemax ; $i++) { 
		if($i == $page) { 
			echo "&nbsp;&nbsp;<span style='color:#FF0000; font-size:11px'><b>$i</b></span>&nbsp;&nbsp;"; 
			}
			else { 
			echo "&nbsp;&nbsp;";
			echo "<a href=\"$PHP_SELF?page=$i&empno=$empno&txtsearch=$txtsearch&xxx=$xxx\">$i</A>";
			echo "&nbsp;&nbsp;";
			} 
		} 

	if($page < $NumberOfPages) { 
		echo "&nbsp;&nbsp;";
		echo "<a href=\"$PHP_SELF?page=" . ($page+1) . "&empno=$empno&txtsearch=$txtsearch&xxx=$xxx\">></A>"; 
		echo "&nbsp;&nbsp;";
		echo "<a href=\"$PHP_SELF?page=$NumberOfPages" . "&empno=$empno&txtsearch=$txtsearch&xxx=$xxx\">>></A>"; 
		} 
		
?>
                      </span>                      </td>
                    </tr>
                    <tr>
                      <td height="1" bgcolor="#666666" colspan="6"></td>
                    </tr>
                    <tr>
                      <td height="10" colspan="6"></td>
                    </tr>
                    <tr style="color:#0000FF; font-size:14px">
                      <td height="26" width="83" align="left" valign="top">Crew Code</td>
                      <td height="26" width="92" align="left" valign="top">Birth Date</td>
                      <td width="294" height="26" align="left" valign="top">Name</td>
                      <td width="125" height="26" align="left">&nbsp;</td>
                      <td width="22" height="26" align="left">&nbsp;</td>
                    </tr>
                    <tr>
                      <td height="1" bgcolor="#666666" colspan="6"></td>
                    </tr>
                    <tr>
                      <td height="1" colspan="6"></td>
                    </tr>
                    <tr>
                      <td height="1" bgcolor="#666666" colspan="6"></td>
                    </tr>
                    <tr>
                      <td height="1" colspan="6"></td>
                    </tr>
<?php
while($row = mysql_fetch_object($SearchResult))
{ 

$xxxviewing_take = mysql_query("select take from users_exam where crewcode='".$row->crewcode."' order by id desc limit 1");
$xxxviewing_take_row=mysql_num_rows($xxxviewing_take);
if ($xxxviewing_take_row!='0')
{
$xxxtakeno = mysql_result($xxxviewing_take,0,"take");
}

switch ($xxxtakeno)
{
case "1": $y = "<sup>st</sup>"; break;
case "2": $y = "<sup>nd</sup>"; break;
case "3": $y = "<sup>rd</sup>"; break;
default: $y = "<sup>th</sup>"; break;
}

echo '
<tr>
<td align="left" height="20" valign="middle"><span style="color:#006600">&nbsp;&nbsp;&nbsp;<strong>'.$row->crewcode.'</strong></span></td>
<td align="left" height="20" valign="middle"><span style="color:#555555"><strong>'.
date("d M Y",strtotime($row->bdate))
.'</strong></span></td>
<td align="left" valign="middle"><b>'. $row->fname . '</b>, ' . $row->gname . ' ' . substr($row->mname,0,1) .'.</td>
<td align="left" valign="middle">';

	if ($xxxtakeno != "")
	{
		echo '
		<span style="color:#FF0000; font-size:10px"><strong>'. $xxxtakeno.$y .' Take</strong></span>
		&nbsp;&nbsp;&nbsp;&nbsp;
		<span style="color:#006600; font-size:10px; cursor:pointer" onclick="showhide(\'script'. $row->crewcode .'\'); return(false);">
		<strong><u>View all '. $xxxtakeno .'</u></strong></span>
		';
	}
	
echo '
</td>
<td align="left" height="20" valign="middle">


<a href="crew_page_edit.php?empno='.$empno.
'&crewcode='.$row->crewcode.
'" style="color:#006600; font-size:10px; cursor:pointer">
<img src="images/edit.jpg" border="0" alt="Edit Account"/></a>
</td>
</tr>';
?>
<tr id="script<?php echo $row->crewcode; ?>" style="display:none">
<td align="left" height="10" colspan="6" background="images/background.gif">
<hr />
<table width="583" border="0" cellspacing="0" cellpadding="0" align="center" style="border:1px #FFFFFF solid">
  <tr bgcolor="#FFFFFF">
    <td height="5" colspan="6"></td>
  </tr>
  <tr bgcolor="#990000">
    <td height="1" colspan="6"></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td height="5" colspan="6"></td>
  </tr>
  <tr>
    <td width="3" align="center">&nbsp;</td>
    <td height="15" align="center"><strong>Pass Code</strong></td>
    <td align="center">&nbsp;</td>
    <td height="15" align="left">&nbsp;</td>
    <td align="center"><strong>Date</strong></td>
    <td width="106" align="center"><strong>Result</strong></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td height="5" colspan="6"></td>
  </tr>
  <tr bgcolor="#990000">
    <td height="1" colspan="6"></td>
  </tr>
<?php
$viewing_per_takeno = mysql_query("select distinct takeno, testdate, activated, xtake from users where crewcode='".$row->crewcode."' order by exam, xtake desc");
while($rowtakeno = mysql_fetch_object($viewing_per_takeno))
{

	$serquery=mysql_query("select distinct users.passcode, type.type
							from users, type where type.id = users.exam and
							users.takeno='".$rowtakeno->takeno."' and users.crewcode='".$row->crewcode."'",$conn);
	$serquery_row=mysql_num_rows($serquery);
	if ($serquery_row!='0')
	{
		$testpasscodex = mysql_result($serquery,0,"users.passcode");
		$examsssxx = mysql_result($serquery,0,"type.type");
	}
	switch ($rowtakeno->xtake)
	{
		case "1": $yy = "<sup>st</sup>"; break;
		case "2": $yy = "<sup>nd</sup>"; break;
		case "3": $yy = "<sup>rd</sup>"; break;
		default: $yy = "<sup>th</sup>"; break;
	}
	if ($rowtakeno->activated == "0")
    { $xxxdate = "<span style='color:#ff0000'>[PENDING]</span>"; }
    else
    { $xxxdate = date("M. d, Y",strtotime($rowtakeno->testdate)); }
	
	$xcodedg="select examtype.totalno from users, examtype, type
				where type.id = users.exam and type.type=examtype.examname and users.crewcode='".$row->crewcode."'";
	$query_resultdg=mysql_query("$xcodedg",$conn);	
	$totalnofg=mysql_result($query_resultdg,0,"examtype.totalno");
	
	$query_resultfg=mysql_query("select count(correct) As correct_answer from users_exam
									where crewcode='".$row->crewcode."' and take='". $rowtakeno->takeno ."' and correct ='1'",$conn);	
	$scorefg=mysql_result($query_resultfg,0,"correct_answer");
	$percentscfg = (($scorefg / $totalnofg)*100);
	//percentscorefg - 2 is equal to number of decimal. if 2 makes 0 it is equal to a whole number
	$percentscorefg = round($percentscfg, 2);
	
	$query_result_percentage=mysql_query("select grade from passing order by id desc limit 1",$conn);
	$rowwwv=mysql_num_rows($query_result_percentage);
	if (($rowwwv != '') || ($rowwwv != 0))
	{	
		$crewgrade=mysql_result($query_result_percentage,0,"grade");
	}
?>
  <tr bgcolor="#eeeeee">
  <td align="center">&nbsp;</td>

    <td width="81" height="18" align="center"><strong><?php	echo $testpasscodex; ?></strong></td>
    
    <td width="77" align="center"><?php echo "<span style='color:#006600'>".$rowtakeno->xtake.$yy." Take</span>"; ?></td>
    
    <td width="210" align="left"><?php echo "<strong>".$examsssxx."</strong>"; ?></td>
    
    <td width="100" align="center"><?php  echo $xxxdate; ?></td>
    
    <td align="center">
	<?php
	if ($rowtakeno->activated == "0")
	{
		echo "---------";
	}
	elseif (($rowtakeno->activated != "0")&&(($percentscorefg == "0")||($percentscorefg == "")))
	{
		echo '<span style="color:#FF0000; font-size:11px"><strong>(0%) FAILED</strong></span>';
	}
	else
	{			
		if ($percentscorefg >= $crewgrade)	
		{
		echo "<a href='print.php?crewcode=". $row->crewcode ."&takeno=" . $rowtakeno->takeno . "' target='_blank' style='text-decoration:none'>";
		echo '<span style="color:#009900; font-size:11px"><strong>('.$percentscorefg.'%) PASSED</strong></span>';
		echo '</a>';
		}
		else
		{
		echo "<a href='print.php?crewcode=". $row->crewcode ."&takeno=" . $rowtakeno->takeno . "' target='_blank' style='text-decoration:none'>";
		echo '<span style="color:#FF0000; font-size:11px"><strong>('.$percentscorefg.'%) FAILED</strong></span>';
		echo '</a>';
		}
	}

?>	</td>
  </tr>
  <?php
}
?>
  <tr bgcolor="#990000">
    <td height="1" colspan="6"></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td height="5" colspan="6"></td>
  </tr>
</table></td>
</tr>
<?php
echo '<tr><td align="left" height="1" colspan="6" bgcolor="#666666"></td></tr>';
echo '<tr><td align="left" height="5" colspan="6"></td></tr>';
} 
?>
                  </table>
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
</form>
</body>
</html>
