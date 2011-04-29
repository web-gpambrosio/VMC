<?php
session_start();
if(!session_is_registered(adminno))
{
header("location:login.php");
}
if ((!isset($_GET['adminno']) || trim($_GET['adminno']) == '')&&(!isset($_GET['id']) || trim($_GET['id']) == ''))
{
header("location:login.php");
}
include('../connection/conn.php');
$adminno=$_GET['adminno'];
$id=$_GET['id'];
$paint=$_GET['paint'];
	mysql_query("update category set stat='".$paint."' where id='".$id."'",$conn);
header("location:exam_cat_admin.php?adminno=$adminno");
?>