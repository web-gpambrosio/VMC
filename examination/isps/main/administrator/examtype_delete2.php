<?php
session_start();
include('includes/myfunction.php');
include('../../includes/conn.php');
if ((!isset($_GET['admin']) || trim($_GET['admin']) == '')&&(!isset($_GET['id']) || trim($_GET['id']) == ''))
{
header("location:../../index.php");
}
$admin=$_GET['admin'];
$xid=$_GET['id'];

$c=mysql_query("select examname from examtype where id='$xid'",$conn);	
$cc=mysql_result($c,0,"examname");

mysql_query("delete from examtype WHERE examname='$cc'",$conn);
mysql_query("delete from questions WHERE examtype='$xid'",$conn);
header("location:type.php?empno=$admin");
?>