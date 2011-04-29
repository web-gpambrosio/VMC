<?php
session_start();
include('includes/myfunction.php');

if ((!isset($_GET['empno']) || trim($_GET['empno']) == '')&&(!isset($_GET['admin']) || trim($_GET['admin']) == ''))
{ header("location:../../index.php"); }
include('../../includes/conn.php');

$admin=$_GET['admin'];
$empno=$_GET['empno'];

	$a = mysql_query("select id from admin where level=".md5."('level1')");
	$b = mysql_num_rows($a);
	
	if ($b > '1')
	{ mysql_query("delete from admin where empno='$empno'",$conn); header("location:admin_page.php?empno=$admin"); }
	else
	{ 
	echo "<script type='text/javascript'>alert(\"You are not allowed to delete this account.\")</script>";
	echo "<script language=\"javascript\">window.location.href='admin_page.php?empno=".$admin."'</script>";
	}
	
	
?>