<?php

include('veritas/connectdb.php');

session_start();

$showsearch = "visibility:hidden;";

if (isset($_GET['firstload']))
	$firstload = $_GET['firstload'];



if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
else 
	$employeeid = "";

$managementcode = "";
	
$qrymanagement = mysql_query("SELECT MANAGEMENTCODE FROM employee WHERE EMPLOYEEID='$employeeid' AND MANAGEMENTCODE IS NOT NULL") or die(mysql_error());

if (mysql_num_rows($qrymanagement) > 0)
{
	$rowmanagement = mysql_fetch_array($qrymanagement);
	$managementcode = $rowmanagement["MANAGEMENTCODE"];
}


include("veritas/header.php");


echo "
	
		<div style=\"width:1019px;height:440px;border-bottom:0;overflow:auto;padding:0;
					background-image:url(images/bg3.jpg);background-repeat:no-repeat;background-position: right bottom;\">
			
			<div style=\"width:199px;height:440px;float:left;\">
			
			</div>
			<div style=\"width:810px;height:440px;float:left;\">
			";
			// if (!empty($managementcode))
			// {
				// echo "
				// <iframe marginwidth=0 marginheight=0 id=\"showbody\" frameborder=\"0\" name=\"showbody\" 
					// src=\"principal.php?mcode=$managementcode\" scrolling=\"yes\" style=\"width:100%;height:100%;\">
				// </iframe> 
				// ";
			// }
			// else 
			// {
				echo "
				<iframe marginwidth=0 marginheight=0 id=\"showbody\" frameborder=\"0\" name=\"showbody\" 
					src=\"home.php\" scrolling=\"yes\" style=\"width:100%;height:100%;\">
				</iframe> 
				";
			// }
			echo "
			</div>
		</div>
";



include("veritas/footer.php");

?>

