<?php
include('ism/connection/conn.php');
if ($_POST['submit'])
{
	$mm=$_POST['mm'];
	$dd=$_POST['dd'];
	$yyyy=$_POST['yyyy'];
	$passcode=$_POST['passcode'];
	$date_formatx = $yyyy.'-'.$mm.'-'.$dd;
	if ($date_formatx == "1970-01-01" || $date_formatx == "" && $passcode == "")
	{
		$msg1 = "Invalid Birth Date Format";
		$msg2 = "Enter your Passcode";
	}
	else
	{		
			$sql="SELECT passcode, activated, crewcode, takeno FROM users WHERE bdate='$date_formatx' and passcode='$passcode' order by takeno desc limit 1";
			$result=mysql_query($sql);
			$count=mysql_num_rows($result);

			if($count==1)
			{
				$crewcode=mysql_result($result,0,"crewcode");
				$takeno=mysql_result($result,0,"takeno");

				$activated=mysql_result($result,0,"activated");
				
				if ($activated == 0)
				{
					session_register("crewcode");
					session_register("takeno"); 
					session_register("passcode"); 

					header("location:ism/main_page.php?crewcode=$crewcode&takeno=$takeno");
				}
				else
				{
					$msg = "Exam Deactivated!";
				}

			}
			else
			{
				$msg = "Invalid Birth Date and/or Passcode";
			}
		

	}
}
?>