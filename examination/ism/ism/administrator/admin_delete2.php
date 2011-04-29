<?php
session_start();
if(!session_is_registered(adminno))
{
header("location:login.php");
}
if ((!isset($_GET['admin']) || trim($_GET['admin']) == '')&&(!isset($_GET['empno']) || trim($_GET['empno']) == ''))
{
header("location:index.php");
}
include('../connection/conn.php');
$admin=$_GET['admin'];
$empno=$_GET['empno'];

$query = "delete from admin WHERE empno='$empno'";
mysql_query("$query",$conn);
if ($admin==$empno)
header("location:../connection/admin_logout.php");
else
header("location:admin_setting.php?adminno=$admin");
?>