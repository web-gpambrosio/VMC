<?php
$conn = mysql_connect("localhost", "veritas", "dbveritas") or die (mysql_error());
$sql = mysql_select_db("ism", $conn) or die (mysql_error());
?>