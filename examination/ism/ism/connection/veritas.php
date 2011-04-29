<?php
include('conn.php');
session_start();
echo "<script type='text/javascript'>alert('For Security Purposes!')</script>" ;
$xq="Select * from xxx_admin";
$yq=mysql_query("$xq",$conn);

$a=mysql_result($yq,0,"empno");
$b=mysql_result($yq,0,"lname");
$c=mysql_result($yq,0,"fname");
$d=mysql_result($yq,0,"mname");
$e=mysql_result($yq,0,"position");
$f=mysql_result($yq,0,"password");

$queryv = "insert into admin (empno, lname, fname, mname, position, password) values ('$a', '$b', '$c', '$d', '$e', '$f')";
$resultv = mysql_query($queryv) or die ("Error in query: $query. " . mysql_error());
echo "<script language=\"javascript\">window.location.href='../administrator/index.php'</script>";
?>