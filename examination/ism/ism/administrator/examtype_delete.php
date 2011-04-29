<?php
session_start();
if(!session_is_registered(empno))
{
header("location:index.php");
}
if ((!isset($_GET['admin']) || trim($_GET['admin']) == '')&&(!isset($_GET['id']) || trim($_GET['id']) == '')&&(!isset($_GET['tnkc']) || trim($_GET['tnkc']) == '')&&(!isset($_GET['type']) || trim($_GET['type']) == ''))
{
header("location:index.php");
}
include('../connection/conn.php');
$admin=$_GET['admin'];
$id=$_GET['id'];
$tnkc=$_GET['tnkc'];
$type=$_GET['type'];
$c=mysql_query("select examname from examtype where id='$id' and principal='$tnkc' and type='$type'",$conn);	
$cc=mysql_result($c,0,"examname");
mysql_query("delete from exam_cat WHERE examname='$cc' and principal='$tnkc' and type='$type'",$conn);
mysql_query("delete from examtype WHERE examname='$cc' and principal='$tnkc' and type='$type'",$conn);
header("location:exam_type.php?empno=$admin&tnkc=$tnkc");
?>