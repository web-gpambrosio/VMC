<?php
session_start();
include('includes/myfunction.php');
include('../includes/conn.php');

if ((!isset($_GET['empno']) || trim($_GET['empno']) == ''))
{ header("location:../index.php"); }

$empno=$_GET['empno'];
include('includes/inc.php');
include('includes/myname.php');

$xxx=@$_GET['xxx'];
$txtsearch=@$_GET['txtsearch'];

$Limit = 20;
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
$srz = "SELECT distinct fname, gname, mname, crewcode, bdate FROM crew WHERE gname != '' and fname != '' and crewcode != '' and $zzz LIKE '$txtsearch%'";
$SearchResult=mysql_query($srz) or die(mysql_error()); 
$NumberOfResults=mysql_num_rows($SearchResult); 
$NumberOfPages=ceil($NumberOfResults/$Limit); 
$SearchResult=mysql_query($srz . " ORDER BY fname LIMIT " . ($page-1)*$Limit . ",$Limit") or die(mysql_error());
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../includes/body.css" />
<title>List of All Crew - ISPS Online Examination</title>
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
<body>
<form action="" method="get" enctype="multipart/form-data" name="form" id="form">
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
              <td width="629" height="167" align="center" valign="top"><table width="614" height="80" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="8" colspan="5" align="left"><br />
                          <span style="font-size:13px">Search by: </span>
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
                          </select>
                        &nbsp;&nbsp;
                          <input type="text" name="txtsearch" id="txtsearch"/>
                        &nbsp;&nbsp;
                          <input type="submit" name="submit" value=" Search " style="font-size:11px" />
                          <input type="button" name="submit2" value=" Refresh " style="font-size:11px" onClick="javascript:location.href='list.php?empno=<?php echo $empno; ?>'"/>
                          
                          &nbsp;&nbsp;&nbsp;&nbsp;
                          <input type="button" name="submit3" value=" Add Crew " style="font-size:11px" onClick="javascript:location.href='crew_page_add.php?empno=<?php echo $empno; ?>'"/></td>
                    </tr>
                    <tr>
                      <td height="10" colspan="5"></td>
                    </tr>
                    <tr>
                      <td height="1" bgcolor="#666666" colspan="5"></td>
                    </tr>
                    <tr>
                      <td align="center" colspan="5" height="20"><span style="color:#333333; font-size:12px">
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
                      </span> </td>
                    </tr>
                    <tr>
                      <td height="1" bgcolor="#666666" colspan="5"></td>
                    </tr>
                    <tr>
                      <td height="10" colspan="5"></td>
                    </tr>
                   <tr style="color:#0000FF; font-size:14px">
                      <td height="26" width="99" align="left" valign="top">&nbsp;&nbsp;&nbsp;Crew Code</td>
                      <td height="26" width="76" align="left" valign="top">Birth Date</td>
                      <td width="279" height="26" align="left" valign="top">Name</td>
                      <td width="108" height="26" align="left">&nbsp;</td>
                      <td width="52" height="26" align="left">&nbsp;</td>
                    </tr>
                    <tr>
                      <td height="1" bgcolor="#666666" colspan="5"></td>
                    </tr>
                    <tr>
                      <td height="1" colspan="5"></td>
                    </tr>
                    <tr>
                      <td height="1" bgcolor="#666666" colspan="5"></td>
                    </tr>
                    <tr>
                      <td height="1" colspan="5"></td>
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
<td align="left" valign="middle">';

if (!empty($xxxtakeno))
{
echo '<span style="color:#FF0000; font-size:10px"><strong>'. $xxxtakeno.$y .' Take</strong></span>
&nbsp;&nbsp;&nbsp;&nbsp;
<span style="color:#006600; font-size:10px; cursor:pointer" onclick="showhide(\'script'. $row->crewcode .'\'); return(false);">
<strong><u>View all '. $xxxtakeno .'</u></strong></span>';
}


echo '</td>
<td align="right" height="20" valign="middle">

<a href="crew_page_edit.php?empno='.$empno.
'&crewcode='.$row->crewcode.
'" style="color:#006600; font-size:10px; cursor:pointer">
<img src="../images/edit.jpg" border="0" alt="Edit Account"/>EDIT</a>
</td>
</tr>';
?>
                    <tr id="script<?php echo $row->crewcode; ?>" style="display:none">
                      <td align="left" height="10" colspan="6" background="../images/background.gif"><hr />      
                          <table width="535" border="0" cellspacing="0" cellpadding="0" align="center" style="border:1px #FFFFFF solid">
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
                              <td align="center">&nbsp;</td>
                              <td align="center"><strong>Date</strong></td>
                              <td width="113" align="center"><strong>Result</strong></td>
                            </tr>
                            <tr bgcolor="#FFFFFF">
                              <td height="5" colspan="6"></td>
                            </tr>
                            <tr bgcolor="#990000">
                              <td height="1" colspan="6"></td>
                            </tr>
                            <?php
$viewing_per_takeno = mysql_query("select distinct take, examdate from users_exam where crewcode='".$row->crewcode."' order by id desc");
while($rowtakeno = mysql_fetch_object($viewing_per_takeno))
{
?>
                            <tr bgcolor="#eeeeee">
                              <td align="center">&nbsp;</td>
                              <td width="88" height="18" align="center"><strong>
                                <?php
			$serquery=mysql_query("select distinct users.passcode, users.testdate, type.type from users, type where type.id = users.exam and users.takeno='".$rowtakeno->take."' and users.crewcode='".$row->crewcode."'",$conn);
			$serquery_row=mysql_num_rows($serquery);
			if ($serquery_row!='0')
			{
			  $testpasscodex = mysql_result($serquery,0,"users.passcode");
			  $testdatex = mysql_result($serquery,0,"users.testdate");
			  $examsssxx = mysql_result($serquery,0,"type.type");
			  
			  
			  }
			echo $testpasscodex;
			?>
                              </strong> </td>
                              <td width="68" align="center"><?php
            switch ($rowtakeno->take)
            {
            case "1": $yy = "<sup>st</sup>"; break;
            case "2": $yy = "<sup>nd</sup>"; break;
            case "3": $yy = "<sup>rd</sup>"; break;
            default: $yy = "<sup>th</sup>"; break;
            }
            echo $rowtakeno->take.$yy." Take";
            ?>                              </td>
                              
                              
            <td width="160" align="left"><?php
            echo "-&nbsp;&nbsp;&nbsp;".$examsssxx;
            ?>                              </td>
                              
                              <td width="101" align="center"><?php 
            if ($testdatex == "0000-00-00 00:00:00")
            { $xxxdate = "<span style='color:#ff0000'>[PENDING]</span>"; }
            else
            { $xxxdate = date("F d, Y",strtotime($testdatex)); }
            echo $xxxdate;
            ?>                              </td>
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

if (($testdatex != "0000-00-00 00:00:00")&&($percentscorefg == "0"))
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
	echo "<a href='print.php?crewcode=". $row->crewcode ."&takeno=" . $rowtakeno->take . "' target='_blank' style='text-decoration:none'>";
	echo '<span style="color:#009900; font-size:11px"><strong>('.$percentscorefg.'%) PASSED</strong></span>';
	echo '</a>';
	}
	else
	{
	echo '<span style="color:#FF0000; font-size:11px"><strong>('.$percentscorefg.'%) FAILED</strong></span>';
	}
}

?>
                              </td>
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
                        </table>
                        </td>
                    </tr>
                    <?php
echo '<tr><td align="left" height="1" colspan="5" bgcolor="#666666"></td></tr>';
echo '<tr><td align="left" height="5" colspan="5"></td></tr>';
} 
?>
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
