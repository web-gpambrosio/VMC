<?php
session_start();
if(!session_is_registered(empno))
{
header("location:index.php");
}
if ((!isset($_GET['admin']) || trim($_GET['admin']) == '')&&(!isset($_GET['id']) || trim($_GET['id']) == '')&&(!isset($_GET['tnkc']) || trim($_GET['tnkc']) == ''))
{
header("location:index.php");
}
include('../connection/conn.php');
$admin=$_GET['admin'];
$id=$_GET['id'];
$tnkc=$_GET['tnkc'];

$query = "delete from questions WHERE id='$id'";
mysql_query("$query",$conn);
header("location:questions_setting.php?empno=$admin&tnkc=$tnkc");
?>