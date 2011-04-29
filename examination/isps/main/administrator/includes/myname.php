<?php
$admin = "select * from admin";
$result_admin_show=mysql_query($admin . " where empno='$empno'",$conn);
$row_admin_show=mysql_num_rows($result_admin_show);
if ($row_admin_show != '0')
{
	$id=mysql_result($result_admin_show,0,"id");
	$empno=mysql_result($result_admin_show,0,"empno");
	$lname=mysql_result($result_admin_show,0,"lname");
	$fname=mysql_result($result_admin_show,0,"fname");
	$mname=mysql_result($result_admin_show,0,"mname");
	$position=mysql_result($result_admin_show,0,"position");
	$sync2=mysql_result($result_admin_show,0,"sync");
	$name = strtoupper($lname . ', ' . $fname . ' ' . $mname);
	$staff = strtoupper($position);
	
	if ($sync2 == '1')
	{
	$xsync = '<a href="admin_type.php?empno='.$empno.'" class="ahref2" style="text-decoration:underline">TYPE</a> <img src="../../images/report.gif" width="10" height="10" border="0"/> ';
	
	$xsyncx = '<tr><td height="5" colspan="2"></td></tr>
    <tr><td height="15">&nbsp;</td><td>&bull;
		<a href=\'admin_page.php?empno='.$empno.'\' style="color:#333333; text-decoration:none">Administrator Account</a>
	</td</tr>';
	}
}
else
{
	echo "<script type='text/javascript'>alert(\"System Error! Please Contact VMC Staff\")</script>" ;
	echo "<script language=\"javascript\">window.location.href='http://www.veritas.com.ph'</script>";
}
?>