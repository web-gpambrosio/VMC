<?php
$kups = "gino";
include('veritas/connectdb.php');
//include('connectdb.php');

session_start();

if (isset($_POST['employeeid']))
	$employeeid = $_POST['employeeid'];
	
if (isset($_POST['password']))
	$password = $_POST['password'];
	
if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$validated = 1;
else 
	$validated=0;	
	
if (isset($_GET['logout']))
{
	$logout = $_GET['logout'];
	session_unset();		
	$validated=0;
}
else 
	$logout = 0;
	

$remarks = "";

switch ($actiontxt)
{
	case "login"	:
			
			$passwordhash = sha1($password);
		
			$qrylogin = mysql_query("SELECT * FROM employee WHERE EMPLOYEEID='$employeeid' AND PASSWORD='$passwordhash'") or die(mysql_error());
			
			if (mysql_num_rows($qrylogin) > 0)
			{
				$validated = 1;
				$rowlogin = mysql_fetch_array($qrylogin);
				$departmentid = $rowlogin["DEPARTMENTID"];
				$divcode = $rowlogin["DIVCODE"];
				
				$_SESSION['employeeid'] = $employeeid;
				$_SESSION['departmentid'] = $departmentid;
				$_SESSION['divcode'] = $divcode;
			}
			else 
			{
				$validated = 0;
				$logout = 0;
				$remarks = "Invalid Username or Password. Please try again.";
			}
		
		break;	
		
	case "logout"	:
		
			session_unset();		
			$validated=0;
		
		break;
}	
	

echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
    \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">

<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">

<!-- HEAD START -->
<head>
	<title>VERITAS MARITIME CORPORATION :: Online</title>
	<meta http-equiv=\"content-type\" content=\"text/html;charset=utf-8\" />
	<meta name=\"generator\" content=\"Geany 0.9\" />
	<META HTTP-EQUIV=\"imagetoolbar\" CONTENT=\"no\">
	<link rel=\"stylesheet\" href=\"veripro.css\">

	<style type=\"text/css\" media=\"screen\">
		
	#main {
		width:500px;
		margin:0px auto; /* Right and left margin widths set to \"auto\" */
		text-align:left; /* Counteract to IE5/Win Hack */
		}
	</style>
	
	<script src='veripro.js' type='text/javascript'></script>	
	
	<script>
	if ('$logout' != '1')
		window.opener=1;
	function autoclose()
	{
		window.opener = 1;
		window.open('close.html', '_self');
	}
	</script>
</head>
<!-- HEAD END -->

";

if ($validated == 0)
{
echo "

<body style=\"overflow:hidden;background-color:White;margin:50px 0px; padding:0px;text-align:center;\" 
		onload=\"if('$logout' == '1') {opener.autoclose2();} document.getElementById('userid').focus();\">

	<form name=\"formlogin\" method=\"POST\">

	<div id=\"main\">
		
		<center>
		
		<img src=\"images/main1.gif\" />
		<br />
		
		<img src=\"images/main3.gif\" />
		<br /><br />

<!--		<img src=\"images/main2.gif\" border=\"1\" />	-->

		<table>
			<tr>
				<td>
					<span style=\"color:Blue;font-weight:Bold;font-size:1em;\">USER</span> <br />
					<input type=\"text\" id=\"userid\" name=\"employeeid\" size=\"8\" maxlength=\"6\" 
							style=\"background-color:#DCDCDC;color:Black;font-size:1em;font-weight:Bold;text-align:center;border:1px solid Gray;\"
							 onkeydown=\"checkCR();\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" onKeyPress=\"return alphanumericonly(this,event);\" />
				</td>
			</tr>
			<tr>
				<td>
					<span style=\"color:Blue;font-weight:Bold;font-size:1em;\">PASSWORD</span> <br />
					<input type=\"password\" name=\"password\" size=\"8\" maxlength=\"8\" 
							style=\"background-color:#DCDCDC;color:Black;font-size:1em;font-weight:Bold;text-align:center;border:1px solid Gray;\"
							 onKeyPress=\"return alphanumericonly(this,event);\"
							 onkeydown=\"checkCR();\" />
				</td>
			</tr>
			<tr>
				<td><input type=\"button\" name=\"btnlogin\" value=\"Login >>\" 
									style=\"border:thin solid Gray;background-color:Black;color:White;font-size:1em;font-weight:bold;\"
									onfocus=\"if(employeeid.value != '' && password.value != ''){actiontxt.value='login'; formlogin.submit();}
											else {alert('Invalid EMPLOYEE ID or PASSWORD. Please try again.');}\" />
				</td>
			</tr>
		</table>
		<br /><br /><br />
		<span style=\"font-size:1em;font-weight:Bold;color:Red;\"><i>$remarks</i></span>
		<br /><br />
		
		
		</center>
	</div>	
	
	<input type=\"hidden\" name=\"actiontxt\" />
	</form>
";
}
else 
{
echo "
<body style=\"overflow:hidden;background-color:Black;\" onload=\"window.open('veripro.php?firstload=1','target',
								'scrollbars=yes,resizable=yes,status=yes,channelmode=yes');\">
";
	
}
	
echo "
</body>
</html>
";

?>