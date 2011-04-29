<?php
$conn = mysql_connect("localhost", "veritas", "dbveritas") or die (mysql_error());
$sql = mysql_select_db("isps", $conn) or die (mysql_error());
$lvla = md5('level1');
$lvls = md5('level0');
?>