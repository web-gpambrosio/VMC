<?php
session_start();
include('includes/myfunction.php');
include('../../includes/conn.php');

if ((!isset($_GET['empno']) || trim($_GET['empno']) == ''))
{ header("location:../../index.php"); }

$empno=$_GET['empno'];
include('includes/myname.php');
include('includes/inc.php');

$srcquestions=$_GET['srcquestions'];
$Limit = 20;
$page=$_GET["page"];
if($page == "") $page=1;

$SearchResult=mysql_query("SELECT * FROM questions WHERE question LIKE '%$srcquestions%'") or die(mysql_error()); 

$NumberOfResults=mysql_num_rows($SearchResult); 
$NumberOfPages=ceil($NumberOfResults/$Limit); 
$SearchResult=mysql_query("SELECT * FROM questions WHERE question LIKE '%$srcquestions%' ORDER BY id desc LIMIT " . ($page-1)*$Limit . ",$Limit") or die(mysql_error()); 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../includes/body.css" />
<title>Administrator - ISPS Online Examination</title>
<script> 
function showhide(id){ 
if (document.getElementById){ 
obj = document.getElementById(id); 
if (obj.style.display == "none"){ 
obj.style.display = ""; 
} else { 
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
    <td width="3" background="../../images/side.gif" valign="top">&nbsp;</td>
    <td width="794" height="466" colspan="4" align="center" valign="top">
<?php
include('includes/mytitle.php');
?>    
      <table width="724" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="724" height="10"></td>
        </tr>
        <tr>
          <td height="312" valign="top" align="center"><table width="581" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td height="8" colspan="2" align="left"><br /><table width="577" border="0" cellspacing="0" cellpadding="0">

                  <tr valign="top">
                    <td width="116" align="left">Search Questions :</td>
                    <td width="30">&nbsp;</td>
                    <td width="199" align="left"><input type="text" name="srcquestions" value="<?php echo $srcquestions; ?>" style="font-size:11px; color:#0000FF; width:180px"/></td>
                    <td width="232" align="right"><a href='questions_add.php?empno=<?php echo $empno; ?>' style="color:#0000FF; text-decoration:underline">ADD QUESTION</a></td>
                    </tr>
                  <tr valign="top">
                    <td height="25" align="right"></td>
                    <td><input type="hidden" name="empno" id="empno" value="<?php echo $empno; ?>"/>                        </td>
                    <td align="left" valign="bottom"><input type="submit" name="submit" value=" Search " style="font-size:11px" />
                      &nbsp;&nbsp;&nbsp;
                      <input type="button" name="button" value=" Refresh " style="font-size:11px" onclick="javascript:location.href='questions_setting.php?empno=<?php echo $empno; ?>'"/>                    </td>
                      <td>&nbsp;</td>
                      </tr>
                </table>
                  <span style="color:#333333; font-size:12px">
                  <?php
echo 'Page ' . $page . ' of ' . $NumberOfPages . '&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;';
if($page > 1) { 

echo "<a href=\"$PHP_SELF?page=1" . "&srcquestions=$srcquestions&empno=$empno\"><<</A>"; 
echo "&nbsp;&nbsp;";
echo "<a href=\"$PHP_SELF?page=" . ($page-1) . "&srcquestions=$srcquestions&empno=$empno\"><</A>"; 
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
echo "<a href=\"$PHP_SELF?page=$i&srcquestions=$srcquestions&empno=$empno\">$i</A>";
echo "&nbsp;&nbsp;";
} 
} 

if($page < $NumberOfPages) { 
echo "&nbsp;&nbsp;";
echo "<a href=\"$PHP_SELF?page=" . ($page+1) . "&srcquestions=$srcquestions&empno=$empno\">></A>"; 
echo "&nbsp;&nbsp;";
echo "<a href=\"$PHP_SELF?page=$NumberOfPages" . "&srcquestions=$srcquestions&empno=$empno\">>></A>"; 
} 
?>
                </span> </td>
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
            </tr>
            <tr>
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
<a href="questions_delete.php?id='.$row->id.'&admin='.$empno.'" style="color:#006600">Delete</a>
&nbsp;&nbsp;|&nbsp;&nbsp; 


<a href="questions_view.php?idx='.$row->id.'&empno='.$empno.'" style="color:#006600">View Details</a>

</span>
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
      <!-- ------------------------------------------------------------------------------------ -->    </td>
    <td width="3" align="right" valign="top" background="../../images/side.gif">&nbsp;</td>
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
