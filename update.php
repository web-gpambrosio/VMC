<?php
if ((!isset($_GET['id']) || trim($_GET['id']) == ''))
{ die ('Missing record ID!'); }
include('e-store_conn.php');
$id=$_GET['id'];
$query = "update merch set verif='1' WHERE id='$id'";
mysql_query("$query",$conn);
header("location:verification.php");
?>
