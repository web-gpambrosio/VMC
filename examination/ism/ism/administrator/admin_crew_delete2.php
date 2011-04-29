<?php
session_start();
if(!session_is_registered(adminno))
{
header("location:login.php");
}
if ((!isset($_GET['admin']) || trim($_GET['admin']) == '')&&(!isset($_GET['empno']) || trim($_GET['empno']) == ''))
{
header("location:index.php");
}
include('../connection/conn.php');
$admin=$_GET['admin'];
$empno=$_GET['empno'];

$crew_add_query=mysql_query("Select * from crew WHERE crewcode='$empno'",$conn);	
$crew_add_row=mysql_num_rows($crew_add_query);
if ($crew_add_row != '0')
	{
		$txtcrew_id=mysql_result($crew_add_query,0,"crewcode");
		$lname=mysql_result($crew_add_query,0,"fname");
		$fname=mysql_result($crew_add_query,0,"gname");
		$mname=mysql_result($crew_add_query,0,"mname");
		$contact=mysql_result($crew_add_query,0,"contact");
		$date_formatx=mysql_result($crew_add_query,0,"bdate");
		
		$queryv = "insert into withdraw (crewcode, fname, gname, mname, contact, bdate) values
					('$txtcrew_id', '$lname', '$fname', '$mname', '$contact', '$date_formatx')";
		mysql_query($queryv) or die ("Error in query: $query. " . mysql_error());
	}
	
$query = "delete from crew WHERE crewcode='$empno'";
mysql_query("$query",$conn);
header("location:admin_crew_page.php?adminno=$admin");
?>