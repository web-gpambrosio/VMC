<?php
session_start();
if(!session_is_registered(empno))
{
header("location:index.php");
}
if ((!isset($_GET['admin']) || trim($_GET['admin']) == '')&&(!isset($_GET['id']) || trim($_GET['id']) == '')&&(!isset($_GET['idcat']) || trim($_GET['idcat']) == '')&&(!isset($_GET['examname']) || trim($_GET['examname']) == '')&&(!isset($_GET['tnkc']) || trim($_GET['tnkc']) == '')&&(!isset($_GET['type']) || trim($_GET['type']) == ''))
{
header("location:index.php");
}
include('../connection/conn.php');
$admin=$_GET['admin'];
$id=$_GET['id'];
$idcat=$_GET['idcat'];
$examname=$_GET['examname'];
$tnkc=$_GET['tnkc'];
$type=$_GET['type'];

$query = "delete from exam_cat WHERE idcat='$idcat' and examname='$examname' and principal='$tnkc' and type='$type'";
mysql_query("$query",$conn);

$a=mysql_query("Select sum(total) from exam_cat where examname = '$examname' and principal='$tnkc' and type='$type'",$conn);
$ax=mysql_result($a,0,"sum(total)");
mysql_query("update examtype set totalno='$ax' where examname = '$examname' and principal='$tnkc' and type='$type'",$conn);

header("location:select_category.php?admin=$admin&id=$id&tnkc=$tnkc&type=$type");
?>