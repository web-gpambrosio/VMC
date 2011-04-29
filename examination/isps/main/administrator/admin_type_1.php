<?php
session_start();
include('includes/myfunction.php');
include('../../includes/conn.php');
if ((!isset($_GET['admin']) || trim($_GET['admin']) == '')&&(!isset($_GET['id']) || trim($_GET['id']) == ''))
{ header("location:../../index.php"); }
$admin=$_GET['admin'];
$xid=$_GET['id'];

$c=mysql_query("select sync from type where id='$xid'",$conn);	
$cc=mysql_result($c,0,"sync");
if ($cc=='0')
mysql_query("update type set sync='1' WHERE id='$xid'",$conn);
else
mysql_query("update type set sync='0' WHERE id='$xid'",$conn);

header("location:admin_type.php?empno=$admin");
?>
