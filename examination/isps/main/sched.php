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
$SearchResult=mysql_query("SELECT distinct fname, gname, mname, crewcode, bdate FROM crew WHERE gname != '' and fname != '' and crewcode != '' and $zzz LIKE '$txtsearch%'") or die(mysql_error()); 
$NumberOfResults=mysql_num_rows($SearchResult); 
$NumberOfPages=ceil($NumberOfResults/$Limit); 
$SearchResult=mysql_query("SELECT distinct fname, gname, mname, crewcode, bdate FROM crew WHERE gname != '' and fname != '' and crewcode != '' and $zzz LIKE '$txtsearch%' ORDER BY fname LIMIT " . ($page-1)*$Limit . ",$Limit") or die(mysql_error());
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../includes/body.css" />
<title>Scheduler - ISPS Online Examination</title>
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
              <td width="629" height="167" align="center" valign="top"><table width="581" height="80" border="0" cellpadding="0" cellspacing="0">
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
                          <input type="button" name="submit2" value=" Refresh " style="font-size:11px" onClick="javascript:location.href='sched.php?empno=<?php echo $empno; ?>'"/>
                          
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
                    <tr style="color:#0000FF; font-size:13px">
                      <td height="26" width="91" align="left" valign="top">Applicant No.</td>
                      <td height="26" width="101" align="left" valign="top">Date of Birth</td>
                      <td width="279" height="26" align="left" valign="top">Name</td>
                      <td width="110" height="26">&nbsp;</td>
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

$datedelivered = date("M-d-Y",strtotime($row->bdate));



echo '
<tr>
<td align="left" height="22" valign="top"><span style="color:#006600">&nbsp;&nbsp;&nbsp;<strong>
'.$row->crewcode.'</strong></span></td>
<td align="left" valign="top">'. $datedelivered .'</td>
<td align="left" valign="top"><b>'. $row->fname . '</b>, ' . $row->gname . ' ' . substr($row->mname,0,1) .'.</td>
<td align="left" valign="top"><span style="color:#006600; font-size:12px">

<a href="index.php?empno='.$empno.
'&crewcode='.$row->crewcode.
'" style="color:#006600"><u><strong>Take Exam</strong> <img src="../images/arrow_animated.gif" border="0" height="5"/></u></a>


</span></td>
</tr>';
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
