<?php
session_start();

if(!session_is_registered(adminno))
{
header("location:login.php");
}

if ((!isset($_GET['adminno']) || trim($_GET['adminno']) == ''))
{
header("location:login.php");
}
include('../connection/conn.php');
$adminno=$_GET['adminno'];

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

$search_result2 = "SELECT distinct crewcode, fname, gname, mname, bdate FROM crew WHERE gname != '' and fname != '' and crewcode != '' and $zzz LIKE '$txtsearch%'";

$SearchResult=mysql_query($search_result2) or die(mysql_error()); 
$NumberOfResults=mysql_num_rows($SearchResult); 
$NumberOfPages=ceil($NumberOfResults/$Limit); 
$SearchResult=mysql_query( $search_result2 . " ORDER BY id desc LIMIT " . ($page-1)*$Limit . ",$Limit") or die(mysql_error());

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Veritas Crew</title>
<link rel="stylesheet" type="text/css" href="../style/body.css" />
<link rel="stylesheet" type="text/css" href="../style/text_style.css" />
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
<body topmargin="10" leftmargin="15" background="../images/background.gif">

<table width=101% height=100% border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
    	<td height="544" align="center" valign="top"><table width="800" height="388" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" background="../images/sideline.gif">
          <tr>
            <td width="800" height="105" align="left" background="../images/top.gif"></td>
          </tr>
          <tr>
            <td height="52" align="left"><table width="755" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="79">&nbsp;</td>
                  <td width="268" align="left"><img src="../images/title.gif" width="257" height="49" /></td>
                  <td width="42">&nbsp;</td>
                  <td width="366">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td height="12"><img src="../images/sideline.gif" width="800"/></td>
          </tr>
          <tr>
            <td height="106" align="center" valign="top">
<form action="" method="get" enctype="multipart/form-data" name="form" id="form">
            <table width="629" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="17" align="right">
                <a href="admin_withdraw.php?adminno=<?php echo $adminno; ?>" style="text-decoration:none; color:#0033FF"><strong>WITHDRAW</strong></a>
                &nbsp;&nbsp;&nbsp;
                <a href="admin_setting.php?adminno=<?php echo $adminno; ?>" style="text-decoration:none; color:#0033FF">Home</a>
                </td>
              </tr>
              <tr>
                <td height="5"></td>
              </tr>
              <tr>
                <td width="629" height="47" align="left" valign="top"><table width="629" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="1" height="32"><img src="../images/gray_side.gif" width="1" height="32"/></td>
                      <td width="639" height="32" background="../images/gray_mid.gif">
                      <div style="font-size:14px">&nbsp;&nbsp;&nbsp;&nbsp;Veritas Crew</div>
                      </td>
                      <td width="1" height="32"><img src="../images/gray_side.gif" width="1" height="32"/></td>
                    </tr>
                  </table>
                  <br />
                    </td>
              </tr>
              <tr>
                <td height="110" align="center">
                  <table width="571" height="80" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="8" colspan="6" align="left"><br />
                          <img src="../images/search.gif" /><span style="font-size:13px"> Search by: </span>.
                          <input type="hidden" name="adminno" id="adminno" value="<?php echo $adminno; ?>"/>
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
                          </select>
                        &nbsp;&nbsp;
                          <input type="text" name="txtsearch" id="txtsearch"/>
                        &nbsp;&nbsp;
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
                      <td align="center" colspan="6" height="20"><span style="color:#333333; font-size:12px">
                        <?php

	echo 'Page ' . $page . ' of ' . $NumberOfPages . '&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;';
	
	if($page > 1) { 
		echo "<a href=\"$PHP_SELF?page=1" . "&adminno=$adminno&txtsearch=$txtsearch&xxx=$xxx\"><<</A>"; 
		echo "&nbsp;&nbsp;";
		echo "<a href=\"$PHP_SELF?page=" . ($page-1) . "&adminno=$adminno&txtsearch=$txtsearch&xxx=$xxx\"><</A>"; 
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
			echo "<a href=\"$PHP_SELF?page=$i&adminno=$adminno&txtsearch=$txtsearch&xxx=$xxx\">$i</A>";
			echo "&nbsp;&nbsp;";
			} 
		} 

	if($page < $NumberOfPages) { 
		echo "&nbsp;&nbsp;";
		echo "<a href=\"$PHP_SELF?page=" . ($page+1) . "&adminno=$adminno&txtsearch=$txtsearch&xxx=$xxx\">></A>"; 
		echo "&nbsp;&nbsp;";
		echo "<a href=\"$PHP_SELF?page=$NumberOfPages" . "&adminno=$adminno&txtsearch=$txtsearch&xxx=$xxx\">>></A>"; 
		} 
		
?>
                      </span> </td>
                    </tr>
                    <tr>
                      <td height="1" bgcolor="#666666" colspan="6"></td>
                    </tr>
                    <tr>
                      <td height="10" colspan="6"></td>
                    </tr>
                    <tr style="color:#0000FF; font-size:14px">
                      <td height="26" width="87" align="left" valign="top">Crew Code</td>
                      <td height="26" width="98" align="left" valign="top">Birth Date</td>
                      <td width="223" height="26" align="left" valign="top">Name</td>
                      <td width="91" height="26" align="left">&nbsp;</td>
                      <td width="72" height="26" align="left">&nbsp;</td>
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

switch ($row->takeno)
{
case "1": $y = "<sup>st</sup>"; break;
case "2": $y = "<sup>nd</sup>"; break;
case "3": $y = "<sup>rd</sup>"; break;
default: $y = "<sup>th</sup>"; break;
}

$xxxviewing_take = mysql_query("select take from users_exam where crewcode='".$row->crewcode."' order by id desc limit 1");
$xxxviewing_take_row=mysql_num_rows($xxxviewing_take);
if ($xxxviewing_take_row!='0')
{
$xxxtakeno = mysql_result($xxxviewing_take,0,"take");
}

echo '
<tr>
<td align="left" height="20" valign="middle"><span style="color:#006600">&nbsp;&nbsp;&nbsp;<strong>'.$row->crewcode.'</strong></span></td>
<td align="left" height="20" valign="middle"><span style="color:#555555"><strong>'.
date("d M Y",strtotime($row->bdate))
.'</strong></span></td>
<td align="left" valign="middle"><b>'. $row->fname . '</b>, ' . $row->gname . ' ' . substr($row->mname,0,1) .'.</td>
<td align="left" valign="middle"><span style="color:#FF0000; font-size:10px"><strong>

<a href="admin_crew_delete.php?empno='.$row->crewcode.'&admin='.$adminno.'" style="color:#FF0000;"><u>Delete</u></a>&nbsp;&nbsp;&nbsp;&nbsp;
<a href="admin_crew_edit.php?empno='.$row->crewcode.'&admin='.$adminno.'" style="color:#FF0000;"><u>Edit</u></a>

</strong></span></td>
<td align="left" height="20" valign="middle">';

if ($xxxtakeno != '')
{
echo '<span style="color:#006600; font-size:10px; cursor:pointer" onclick="showhide(\'script'. $row->crewcode .'\'); return(false);">
&nbsp;&nbsp;&nbsp;<strong><u>View all '. $xxxtakeno .'</u></strong></span>';
}

echo '</td></tr>';
?>
                    <tr id="script<?php echo $row->crewcode; ?>" style="display:none">
                      <td align="left" height="10" colspan="6" background="images/background.gif"><hr />
                          
                          
                          
                          <table width="379" border="0" cellspacing="0" cellpadding="0" align="center" style="border:1px #FFFFFF solid">
                            <tr bgcolor="#FFFFFF">
                              <td height="5" colspan="5"></td>
                            </tr>
                            <tr bgcolor="#990000">
                              <td height="1" colspan="5"></td>
                            </tr>
                            <tr bgcolor="#FFFFFF">
                              <td height="5" colspan="5"></td>
                            </tr>
                            <tr>
                              <td width="3" align="center">&nbsp;</td>
                              <td height="15" align="center"><strong>Pass Code</strong></td>
                              <td align="center">&nbsp;</td>
                              <td align="center"><strong>Date</strong></td>
                              <td width="88" align="center"><strong>Result</strong></td>
                            </tr>
                            <tr bgcolor="#FFFFFF">
                              <td height="5" colspan="5"></td>
                            </tr>
                            <tr bgcolor="#990000">
                              <td height="1" colspan="5"></td>
                            </tr>
<?php
$viewing_per_takeno = mysql_query("select distinct users_exam.take, users_exam.examdate from users_exam where users_exam.crewcode='".$row->crewcode."' order by id desc");
while($rowtakeno = mysql_fetch_object($viewing_per_takeno))
{
?>
                            <tr bgcolor="#eeeeee">
                              <td align="center">&nbsp;</td>
                              <td width="91" height="18" align="center"><strong>
<?php
			$serquery=mysql_query("select distinct passcode, testdate from users where takeno='".$rowtakeno->take."' and crewcode='".$row->crewcode."'",$conn);
			$serquery_row=mysql_num_rows($serquery);
			if ($serquery_row!='0')
			{
			  $testpasscodex = mysql_result($serquery,0,"passcode");
			  $testdatex = mysql_result($serquery,0,"testdate"); }
			echo $testpasscodex;
			?>
                              </strong> </td>
                              <td width="73" align="center"><?php
            switch ($rowtakeno->take)
            {
            case "1": $yy = "<sup>st</sup>"; break;
            case "2": $yy = "<sup>nd</sup>"; break;
            case "3": $yy = "<sup>rd</sup>"; break;
            default: $yy = "<sup>th</sup>"; break;
            }
            echo $rowtakeno->take.$yy." Take";
            ?>
                              </td>
                              <td width="122" align="center"><?php 
            if ($testdatex == "0000-00-00")
            { $xxxdate = "<span style='color:#ff0000'>[PENDING]</span>"; }
            else
            { $xxxdate = date("F d, Y",strtotime($testdatex)); }
            echo $xxxdate;
            ?>
                              </td>
                              <td align="center"><?php
$xcodedg="select examtype.totalno from users, examtype, type where type.id = users.exam and type.type=examtype.examname and
				users.crewcode='".$row->crewcode."'";
$query_resultdg=mysql_query("$xcodedg",$conn);	
$totalnofg=mysql_result($query_resultdg,0,"examtype.totalno");

$query_resultfg=mysql_query("select count(correct) As correct_answer from users_exam where crewcode='".$row->crewcode."' and take='". $rowtakeno->take ."' and correct ='1'",$conn);	
$scorefg=mysql_result($query_resultfg,0,"correct_answer");
$percentscfg = (($scorefg / $totalnofg)*100);
$percentscorefg = round($percentscfg, 0);

$query_result_percentage=mysql_query("select grade from passing order by id desc limit 1",$conn);
$rowwwv=mysql_num_rows($query_result_percentage);
if (($rowwwv != '') || ($rowwwv != 0))
{	
	$crewgrade=mysql_result($query_result_percentage,0,"grade");
}
if (($testdatex != "0000-00-00")&&($percentscorefg == "0"))
{
echo '<span style="color:#FF0000; font-size:11px"><strong>(0%) FAILED</strong></span>';
}
else
{
	if ($percentscorefg == "" || $percentscorefg == "0")
	{
	echo "---------";
	}
	elseif ($percentscorefg >= $crewgrade)	
	{
	echo "<a href='../print.php?crewcode=". $row->crewcode ."&takeno=" . $rowtakeno->take . "' target='_blank' style='text-decoration:none'>";
	echo '<span style="color:#009900; font-size:11px"><strong>('.$percentscorefg.'%) PASSED</strong></span>';
	echo '</a>';
	}
	else
	{
	echo "<a href='../print.php?crewcode=". $row->crewcode ."&takeno=" . $rowtakeno->take . "' target='_blank' style='text-decoration:none'>";
	echo '<span style="color:#FF0000; font-size:11px"><strong>('.$percentscorefg.'%) FAILED</strong></span>';
	echo '</a>';
	}
}
?>
                              </td>
                            </tr>
                            <?php
}
?>
                            <tr bgcolor="#990000">
                              <td height="1" colspan="5"></td>
                            </tr>
                            <tr bgcolor="#FFFFFF">
                              <td height="5" colspan="5"></td>
                            </tr>
                        </table></td>
                    </tr>
                    <?php
echo '<tr><td align="left" height="1" colspan="6" bgcolor="#666666"></td></tr>';
echo '<tr><td align="left" height="5" colspan="6"></td></tr>';
} 
?>
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
