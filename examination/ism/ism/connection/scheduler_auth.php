<?php
include('ism/connection/conn.php');
session_start();

if ($_POST['submit'])
{
	$crewcode=$_POST['crewcode']; 
	$passcode=$_POST['passcode'];
	
	if ($crewcode == "" && $passcode == "")
	{
		$msg1 = "Enter your Employee Number";
		$msg2 = "Enter your Password";
	}
	else
	{
		$crewcode = stripslashes($crewcode);
		$passcode = stripslashes($passcode);
		$crewcode = mysql_real_escape_string($crewcode);
		$passcode = mysql_real_escape_string($passcode);
			
		$a1="SELECT * FROM scheduler WHERE empno='$crewcode' and password='$passcode'";
		$b1=mysql_query($a1);
		$c1=mysql_num_rows($b1);
		
		if ($c1 == 1)
		{
				$empno=mysql_result($b1,0,"empno");
				session_register("empno"); 

				header("location:ism/scheduler_page.php?empno=$empno");
		}
		else
		{
				$msg = "Invalid Employee Number and/or Password";	
		}
		
	}
}
?>