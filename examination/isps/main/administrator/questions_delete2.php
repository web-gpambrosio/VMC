<?php
session_start();
include('includes/myfunction.php');
include('../../includes/conn.php');
if ((!isset($_GET['admin']) || trim($_GET['admin']) == '')&&(!isset($_GET['id']) || trim($_GET['id']) == ''))
{
header("location:../../index.php");
}
$admin=$_GET['admin'];
$id=$_GET['id'];

$query = "delete from questions WHERE id='$id'";
mysql_query("$query",$conn);
header("location:questions_setting.php?empno=$admin");
?>