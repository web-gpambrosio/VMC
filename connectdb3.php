<?php

$dbservertype='localhost';
$servername='localhost';
//$servername='61.28.171.12';
// username and password to log onto db server
//$dbusername='gino';
//$dbpassword='astigino';
$dbusername='veritas';
$dbpassword='';
// name of database
$dbname='veritas';
//$dbname='veritas1';

connecttodb($servername,$dbname,$dbusername,$dbpassword);
function connecttodb($servername,$dbname,$dbuser,$dbpassword)
{
global $link;
$link=mysql_connect ("$servername","$dbuser","$dbpassword");
if(!$link){die("Could not connect to MySQL");}
mysql_select_db("$dbname",$link) or die ("could not open db".mysql_error());
}

?>