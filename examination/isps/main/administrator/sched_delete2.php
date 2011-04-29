<?php
session_start();
include('includes/myfunction.php');

if ((!isset($_GET['empno']) || trim($_GET['empno']) == '')&&(!isset($_GET['admin']) || trim($_GET['admin']) == ''))
{ header("location:../../index.php"); }
include('../../includes/conn.php');

$admin=$_GET['admin'];
$empno=$_GET['empno'];

	mysql_query("delete from admin where empno='$empno'",$conn);
	header("location:sched_page.php?empno=$admin");	
	
?>