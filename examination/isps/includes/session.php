<?php
session_start();
include('conn.php');
include('msg.php');
if ($_POST['submit'])
{
	$crewcode = $_POST['crewcode']; 
	$passcode = $_POST['passcode'];
	$crewcode = stripslashes($crewcode);
	$passcode = stripslashes($passcode);
	$crewcode = mysql_real_escape_string($crewcode);
	$passcode = mysql_real_escape_string($passcode);
	
	if ($crewcode == "" && $passcode == "")
	{
		$msg = $w1login. $msg1 .$w2login;
	}
	elseif ($crewcode == "")
	{
		$msg = $w1login. $msg2 .$w2login;
	}
	elseif ($passcode == "")
	{
		$msg = $w1login. $msg3 .$w2login;
	}
	else
	{
		$md5passcode = md5($passcode);
		$a1="SELECT empno, level FROM admin WHERE empno='$crewcode' and password='$md5passcode'";
		$b1=mysql_query($a1);
		$c1=mysql_num_rows($b1);
		if ($c1 == 1)
		{
			$empno=mysql_result($b1,0,"empno");
			$level=mysql_result($b1,0,"level");
			$mmd5 = md5('level1');
			/*mysql_query("insert into account (account) values ('$empno')");*/
			session_register("myemployee_number"); 
			if ($level == $mmd5)
			{
				header("location:main/administrator/index.php?empno=$empno");
			}
			else
			{
				header("location:main/index.php?empno=$empno");
			}
		}
		else
		{	
			$sqsl="SELECT * FROM users WHERE crewcode='$crewcode' and passcode='$passcode'";
			$resuxxlt=mysql_query($sqsl);
			$counxxt=mysql_num_rows($resuxxlt);
			if($counxxt==1)
			{
				$activated=mysql_result($resuxxlt,0,"activated");
				if ($activated != '1')
				{
					$crewcodxxe=mysql_result($resuxxlt,0,"crewcode");
					$ctakenoe=mysql_result($resuxxlt,0,"takeno");
					session_register("myemployee_number");
					header("location:main/home.php?empno=$crewcodxxe&takeno=$ctakenoe");
				}
				else
				{
					$msg = $w1login. $msg4 .$w2login;
				}
			}
			else
			{
				$msg = $w1login. $msg1 .$w2login;
			}	
			
		}
		
	}
}
?>