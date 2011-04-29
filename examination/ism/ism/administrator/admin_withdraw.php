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

$search_result2 = "SELECT distinct crewcode, fname, gname, mname, bdate FROM withdraw WHERE gname != '' and fname != '' and crewcode != '' and $zzz LIKE '$txtsearch%'";

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
                <td height="17" align="right">&nbsp;
                <a href="admin_crew_page.php?adminno=<?php echo $adminno; ?>" style="text-decoration:none; color:#0033FF">Back</a>                </td>
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
                  <table width="571" height="126" border="0" cellpadding="0" cellspacing="0">
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
<td align="left" height="18" valign="middle"><span style="color:#006600">&nbsp;&nbsp;&nbsp;<strong>'.$row->crewcode.'</strong></span></td>
<td align="left" valign="middle"><span style="color:#555555"><strong>'.
date("d M Y",strtotime($row->bdate))
.'</strong></span></td>
<td align="left" valign="middle"><b>'. $row->fname . '</b>, ' . $row->gname . ' ' . substr($row->mname,0,1) .'.</td>
<td align="left" valign="middle"></td>
</tr>';

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
