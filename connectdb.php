<?php
session_start();

if(isset($_SESSION["employeeid"]))
$employeeid=$_SESSION["employeeid"];



$dbservertype='localhost';
$servername='localhost';
// username and password to log onto db server
$dbusername='gino';
$dbpassword='astigino';
// name of database
$dbname='veritas';

if (!empty($employeeid) || $kups == "gino" || $directprint == 1)
	connecttodb($servername,$dbname,$dbusername,$dbpassword);
else
	echo "<script>window.close();</script>";
	
function connecttodb($servername,$dbname,$dbuser,$dbpassword)
{
global $link;
$link=mysql_connect ("$servername","$dbuser","$dbpassword");
if(!$link){die("Could not connect to MySQL");}
mysql_select_db("$dbname",$link) or die ("could not open db".mysql_error());
}

?>