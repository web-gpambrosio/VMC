<?php
session_start();
if(!session_is_registered(empno))
{
header("location:index.php");
}
if ((!isset($_GET['admin']) || trim($_GET['admin']) == '')&&
	(!isset($_GET['id']) || trim($_GET['id']) == '')&&
	(!isset($_GET['tnkc']) || trim($_GET['tnkc']) == ''))
{
header("location:index.php");
}
include('../connection/conn.php');
$admin=$_GET['admin'];
$id=$_GET['id'];
$tnkc=$_GET['tnkc'];

mysql_query("delete from vessel WHERE id='$id' and principal='$tnkc'",$conn);

header("location:exam_principal.php?empno=$admin&tnkc=$tnkc");
?>