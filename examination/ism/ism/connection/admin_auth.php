<?php
include('../connection/conn.php');
session_start();

if ($_POST['submit'])
{
	$empno=$_POST['empno']; 
	$password=$_POST['password'];
	
	if ($empno == "" && $password == "")
	{
		$msg1 = "Enter your Log In Number";
		$msg2 = "Enter your Password";
	}
	else
	{
		$empno = stripslashes($empno);
		$password = stripslashes($password);
		$empno = mysql_real_escape_string($empno);
		$password = mysql_real_escape_string($password);

		$sql="SELECT * FROM admin WHERE empno='$empno' and password='$password'";
		$result=mysql_query($sql);
		$count=mysql_num_rows($result);
		$password=md5($password);

		if($count==1)
		{
			$empno=mysql_result($result,0,"empno");
			$tnkc=mysql_result($result,0,"tnkc");
			session_register("empno");
			session_register("password"); 
			header("location:admin_page.php?empno=$empno&tnkc=$tnkc");
		}
		else
		{
			$msg1 = "Invalid Log In Number and/or ";
			$msg2 = "Invalid Password";
		}
	}
}

?>