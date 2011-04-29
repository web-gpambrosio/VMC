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
	$name = strtoupper($lname . ', ' . $fname . ' ' . $mname);
	$staff = strtoupper($position);
}
else
{
	echo "<script type='text/javascript'>alert(\"System Error: #P17-0. Please Contact VMC IT Staff\")</script>";
	echo "<script language=\"javascript\">window.location.href='http://www.veritas.com.ph'</script>";
}
?>