<?php
session_start();
if(!session_is_registered(empno))
{
header("location:index.php");
}
if ((!isset($_GET['empno']) || trim($_GET['empno']) == '')&&(!isset($_GET['tnkc']) || trim($_GET['tnkc']) == ''))
{
header("location:index.php");
}
include('../connection/conn.php');

$empno=$_GET['empno'];
$tnkc=$_GET['tnkc'];

$srcexam=$_GET['srcexam'];
$srcvessel=$_GET['srcvessel'];
$srccategory=$_GET['srccategory'];
$srcquestions=$_GET['srcquestions'];

$newcategory=mysql_query("Select id, cat from category where id='$txtsearch'");
if (mysql_num_rows($newcategory) > 0)
{
$dbcategory=mysql_result($newcategory,0,"id");
$dbcategorys=mysql_result($newcategory,0,"cat");
}

$Limit = 20;
$page=$_GET["page"];
if($page == "") $page=1;
$SearchResult=mysql_query("SELECT * FROM questions WHERE category LIKE '%$srccategory%' and examtype LIKE '%$srcexam%' and type LIKE '%$srcvessel%' and question LIKE '%$srcquestions%' and principal='$tnkc'") or die(mysql_error()); 
$NumberOfResults=mysql_num_rows($SearchResult); 
$NumberOfPages=ceil($NumberOfResults/$Limit); 
$SearchResult=mysql_query("SELECT * FROM questions WHERE category LIKE '%$srccategory%' and examtype LIKE '%$srcexam%' and type LIKE '%$srcvessel%' and question LIKE '%$srcquestions%' and principal='$tnkc' ORDER BY id desc LIMIT " . ($page-1)*$Limit . ",$Limit") or die(mysql_error()); 

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Questionares</title>
<link rel="stylesheet" type="text/css" href="../style/body.css" />
<link rel="stylesheet" type="text/css" href="../style/text_style.css" />
<style type="text/css">
a { text-decoration:none; color:#aaaaaa }
</style>

<script language="javascript">
var win = null;
function NewWindow(mypage,myname,w,h,scroll){
LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
settings =
'height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',resizable'
win = window.open(mypage,myname,settings)
}
</script>

</head>
<body topmargin="10" leftmargin="15" background="../images/background.gif">

<table width=101% height=100% border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
    	<td align="center" valign="top"><table width="800" height="465" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" background="../images/sideline.gif">
          <tr>
            <td width="800" height="105" align="left" background="../images/top.gif"></td>
          </tr>
          <tr>
            <td height="52" align="left"><table width="755" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="79">&nbsp;</td>
                  <td width="268" align="left"><img src="../images/title.gif" width="257" height="49" /></td>
                  <td width="42">&nbsp;</td>
                  <td width="366"> <?php
				  	switch ($tnkc)
					{
						case "1": $tnkc_picture = "kobe"; break;
						case "2": $tnkc_picture = "manila"; break;
						case "3": $tnkc_picture = "star"; break;
						default: $tnkc_picture = "account"; break;
					}
				  ?>
                  <img src="../images/<?php echo $tnkc_picture; ?>.gif" width="302" height="33" /></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td height="12"><img src="../images/sideline.gif" width="800"/></td>
          </tr>
          <tr>
            <td height="183" align="center" valign="top">
<form action="" method="get" enctype="multipart/form-data" name="form" id="form">
            <table width="629" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="17" align="right">
                <a href='questions_add.php?empno=<?php echo $empno; ?>&tnkc=<?php echo $tnkc; ?>' style="color:#0033FF; text-decoration:none">Add Questions</a>
&nbsp;&nbsp;&nbsp;        
                <a href="admin_page.php?empno=<?php echo $empno; ?>&tnkc=<?php echo $tnkc; ?>" style="color:#0033FF">Home</a>
&nbsp;&nbsp;&nbsp;
                <a href="../connection/admin_logout.php" style="color:#0033FF">Log Out</a></td>
              </tr>
              <tr>
                <td height="5"></td>
              </tr>
              <tr>
                <td width="629" height="47" align="left" valign="top"><table width="629" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="1" height="32"><img src="../images/gray_side.gif" width="1" height="32"/></td>
                      <td width="639" height="32" background="../images/gray_mid.gif">
                      <div style="font-size:14px"><span style="font-size:14px; color:#000000">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $dbcategorys; ?></span></div>
                      </td>
                      <td width="1" height="32"><img src="../images/gray_side.gif" width="1" height="32"/></td>
                    </tr>
                  </table>
                  <br />
                    </td>
              </tr>
              <tr>
                <td height="34" align="center"><table width="581" border="0" cellspacing="0" cellpadding="0">
                  	<tr>
                    <td height="8" colspan="2" align="center">
                      <table width="433" border="0" cellspacing="0" cellpadding="0">
  <tr valign="top">
    <td width="149" height="25" align="right"> Type of Examination :</td>
    <td width="53">
<?php

if ($srcexam != ""){ $xsrcexam2=mysql_query("select type from type where id='$srcexam'");
$srcexamx=mysql_result($xsrcexam2,0,"type");
$xsrcexam = '<option value="'.$srcexam.'" style="color:#0000FF">'.$srcexamx.'</option>'; }
else {
$xsrcexam = '<option value="" style="color:#aaaaaa">------[Type of Examination]------</option>'; }

if ($srcvessel != ""){ $xsrcvessel2=mysql_query("select type from pbw where id='$srcvessel'");
$srcvesselx=mysql_result($xsrcvessel2,0,"type");
$xsrcvessel = '<option value="'.$srcvessel.'" style="color:#0000FF">'.$srcvesselx.'</option>'; }
else {
$xsrcvessel = '<option value="" style="color:#aaaaaa">------[Type of Vessel]------</option>'; }

if ($srccategory != ""){ $xsrccategory2=mysql_query("select cat from category where id='$srccategory'");
$srccategoryx=mysql_result($xsrccategory2,0,"cat");
$xsrccategory = '<option value="'.$srccategory.'" style="color:#0000FF">'.$srccategoryx.'</option>'; }
else {
$xsrccategory = '<option value="" style="color:#aaaaaa">------[Category]------</option>'; }

?>

</td>
    <td width="231" align="left">
    <select name="srcexam" style="font-size:11px; color:#0000FF; width:200px">
    <?php echo $xsrcexam; ?>
      <?php 
$srcexam2=mysql_query("Select distinct id, type from type order by id asc");
if (mysql_num_rows($srcexam2) > 0)
{
	while($srcexamrow = mysql_fetch_row($srcexam2))
	{
	echo '<option value="'.$srcexamrow[0].'">' . $srcexamrow[1] . '</option>'; 
	} 
}
?>
    </select>
    </td>
  </tr>
  <tr valign="top">
    <td height="26" align="right">Vessel Type: </td>
    <td>&nbsp;</td>
    <td align="left"><select name="srcvessel" style="font-size:11px; color:#0000FF; width:200px">
    <?php echo $xsrcvessel; ?>
<?php 
$srcvessel2=mysql_query("Select distinct id, type from pbw order by id asc");
if (mysql_num_rows($srcvessel2) > 0)
{
	while($srcvessel2row = mysql_fetch_row($srcvessel2))
	{
	echo '<option value="'.$srcvessel2row[0].'">' . $srcvessel2row[1] . '</option>'; 
	} 
}
?>
    </select>
    </td>
  </tr>
  <tr valign="top">
    <td height="24" align="right" valign="top">Category: </td>
    <td>&nbsp;</td>
    <td align="left"><select name="srccategory" style="font-size:11px; color:#0000FF">
    <?php echo $xsrccategory; ?>
        <?php 
$srccategory2=mysql_query("Select distinct id, cat from category where stat='0' order by id asc");
if (mysql_num_rows($srccategory2) > 0)
{
	while($srccategoryrow = mysql_fetch_row($srccategory2))
	{
	echo '<option value="'.$srccategoryrow[0].'">' . $srccategoryrow[1] . '</option>'; 
	} 
}
?>
    </select></td>
  </tr>
  <tr valign="top">
    <td align="right">Questions :</td>
    <td>&nbsp;</td>
    <td align="left"><input type="text" name="srcquestions" value="<?php echo $srcquestions; ?>" style="font-size:11px; color:#0000FF; width:180px"/></td>
  </tr>
  <tr valign="top">
    <td height="33" align="right">
    
    
    
    </td>
    <td><input type="hidden" name="empno" id="empno" value="<?php echo $empno; ?>"/>
      <input type="hidden" name="tnkc" id="tnkc" value="<?php echo $tnkc; ?>"/></td>
    <td align="left" valign="bottom">
    <input type="submit" name="submit" value=" Search " style="font-size:11px" />
    &nbsp;&nbsp;&nbsp;
    <input type="button" name="button" value=" Refresh " style="font-size:11px" onClick="javascript:location.href='questions_setting.php?empno=<?php echo $empno; ?>&tnkc=<?php echo $tnkc; ?>'"/>
    </td>
  </tr>
</table>
<br />
<span style="color:#333333; font-size:12px">
<?php
echo 'Page ' . $page . ' of ' . $NumberOfPages . '&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;';
if($page > 1) { 

echo "<a href=\"$PHP_SELF?page=1" . "&srcexam=$srcexam&srcvessel=$srcvessel&srccategory=$srccategory&srcquestions=$srcquestions&empno=$empno&tnkc=$tnkc\"><<</A>"; 
echo "&nbsp;&nbsp;";
echo "<a href=\"$PHP_SELF?page=" . ($page-1) . "&srcexam=$srcexam&srcvessel=$srcvessel&srccategory=$srccategory&srcquestions=$srcquestions&empno=$empno&tnkc=$tnkc\"><</A>"; 
echo "&nbsp;&nbsp;";

} 

if ($page - 3 < 1) {
$pagemin = 1;
}
else {
$pagemin = $page - 3;
};

if ($page + 3 > $NumberOfPages) {
$pagemax = $NumberOfPages;
}
else {
$pagemax = $page + 3;
};

for($i = $pagemin ; $i <= $pagemax ; $i++) { 
if($i == $page) { 
echo "&nbsp;&nbsp;<span style='color:#FF0000; font-size:10px'><b>$i</b></span>&nbsp;&nbsp;"; 
}
else{ 
echo "&nbsp;&nbsp;";
echo "<a href=\"$PHP_SELF?page=$i&srcexam=$srcexam&srcvessel=$srcvessel&srccategory=$srccategory&srcquestions=$srcquestions&empno=$empno&tnkc=$tnkc\">$i</A>";
echo "&nbsp;&nbsp;";
} 
} 

if($page < $NumberOfPages) { 
echo "&nbsp;&nbsp;";
echo "<a href=\"$PHP_SELF?page=" . ($page+1) . "&srcexam=$srcexam&srcvessel=$srcvessel&srccategory=$srccategory&srcquestions=$srcquestions&empno=$empno&tnkc=$tnkc\">></A>"; 
echo "&nbsp;&nbsp;";
echo "<a href=\"$PHP_SELF?page=$NumberOfPages" . "&srcexam=$srcexam&srcvessel=$srcvessel&srccategory=$srccategory&srcquestions=$srcquestions&empno=$empno&tnkc=$tnkc\">>></A>"; 
} 
?>
</span>
</td>
                    </tr>
                  	<tr>
                      <td width="58"></td>
                  	  <td width="523" align="left"></td>
                  	  </tr>
                    <tr>
                    <td height="8" colspan="2"></td>
                    </tr>
                     <tr>
                    <td height="1" bgcolor="#666666" colspan="2"></td>
                    </tr>
                    <tr>
                    <td height="1"></td>
                    </tr><tr>
                    <td height="1" colspan="2"></td>
                    </tr>
                     <tr>
                    <td height="5"></td>
                    </tr>

<?php
$num_row=mysql_num_rows($SearchResult);
if ($num_row=='0')
{
echo '
<tr>
<td height="20" valign="top" colspan="3">No Record Found</td>
</tr>';
}
else
{
while($row = mysql_fetch_object($SearchResult)) { 
$xqx="Select fname, lname from admin where empno='".$row->empno."'";
$yqx=mysql_query("$xqx",$conn);
$ix=mysql_num_rows($yqx);
if ($ix!='0')
{
$fnamex=mysql_result($yqx,0,"fname");
$lnamex=mysql_result($yqx,0,"lname");
}
echo '
<tr>
<td align="left" width="80" valign="top"><span style="color:#333333">Q# '.$row->id.' :</span></td>
<td align="left" valign="top" colspan="2">'.$row->question.'<br><span style="color:#aaaaaa; font-size:10px">
<a href="questions_delete.php?id='.$row->id.'&admin='.$empno.'&tnkc='.$tnkc.'" style="color:#006600">Delete</a>
&nbsp;&nbsp;|&nbsp;&nbsp; 

<a href="questions_edit.php?id='.$row->id.'&empno='.$empno.'&tnkc='.$tnkc.'" style="color:#006600">Edit</a>
&nbsp;&nbsp;|&nbsp;&nbsp; 

<a href="questions_view.php?id='.$row->id.'&admin='.$empno.'&tnkc='.$tnkc.'&txtsearch='.$txtsearch.'" onclick="NewWindow(this.href,\'Questions\',\'668\',\'380\',\'yes\');return false" style="color:#006600">View Details</a></span>

<br><span style="color:#aaaaaa; font-size:9px; font-family:Verdana">
'.$fnamex. ' ' .$lnamex.'
</span><br><span style="font-size:3px">&nbsp;</span></td>
</tr>';
echo '<tr><td align="left" height="1" colspan="3" bgcolor="#666666"></td></tr>';
echo '<tr><td align="left" height="5" colspan="3"></td></tr>';
	} 
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
