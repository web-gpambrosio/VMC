<?php
/*$conn = mysql_connect("63.28.171.13", "veritas", "dbveritas") or die (mysql_error());*/
$conn = mysql_connect("localhost", "root", "") or die (mysql_error());
$sql = mysql_select_db("db_vmc", $conn) or die (mysql_error());
?>