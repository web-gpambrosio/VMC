<?php

include("veritas/connectdb.php");
include("veritas/include/stylephp.inc");


session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
	
$formname = "formaccess";
$formtitle = "ACCESS SETUP";

	

//POSTS

if (isset($_POST['empid']))
	$empid = $_POST['empid'];
	
if (isset($_POST['accessid']))
	$accessid = $_POST['accessid'];


switch ($actiontxt)
{
	case "saveaccess":

			$qrysaveaccess = mysql_query("INSERT INTO access(EMPLOYEEID,ACCESSID) VALUES('$empid',$accessid)") or die(mysql_error());
		
		break;
	case "deleteaccess":

			$qrydeleteaccess = mysql_query("DELETE FROM access WHERE EMPLOYEEID='$empid' AND ACCESSID=$accessid") or die(mysql_error());
		
		break;
}
	

/* LISTINGS  */

$qryemployeelist = mysql_query("SELECT EMPLOYEEID,CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME FROM employee ORDER BY FNAME") or die(mysql_error());
$empselect = "<option value=\"\">--Select One--</option>";
$selected = "";
while ($rowemployeelist = mysql_fetch_array($qryemployeelist))
{
	$eid = $rowemployeelist["EMPLOYEEID"];
	$ename = $rowemployeelist["NAME"];
	
	if ($eid == $empid)
		$selected = "SELECTED";
	else 
		$selected = "";
	
	$empselect .= "<option $selected value=\"$eid\">$ename&nbsp;($eid)</option>";
}

/*END OF LISTINGS*/

if ($empid != "")
{
	$qrycurrent = mysql_query("SELECT al.ACCESSID,al.MENU,al.SUBMENU1,al.SUBMENU2,al.SUBMENU3
								FROM access a
								LEFT JOIN accesslevels al ON al.ACCESSID=a.ACCESSID
								WHERE EMPLOYEEID='$empid'
								ORDER BY al.MENU,al.POSITION") or die(mysql_error());
	
	$qryavailable = mysql_query("SELECT al.ACCESSID,al.MENU,al.SUBMENU1,al.SUBMENU2,al.SUBMENU3
								FROM accesslevels al
								WHERE al.ACCESSID NOT IN (
										SELECT ACCESSID FROM access
										WHERE EMPLOYEEID='$empid'
										)
								ORDER BY al.MENU,al.POSITION") or die(mysql_error());
}

echo "
<html>
<head>
<link rel=\"stylesheet\" type=\"text/css\" href=\"../veripro.css\">
<script language=\"javascript\" src=\"../veripro.js\"></script>

<script>


	
</script>
</head>
<body>

<form name=\"$formname\" method=\"POST\">
	
	<div style=\"width:100%;height:550px;padding:5px 20px 0 20px;\">
			
		<div style=\"width:100%;padding:5px 20px 0 20px;overflow:hidden;\">
			<br />
			<span class=\"sectiontitle\">$formtitle</span>
			<br />
			
			<table class=\"setup\" width=\"100%\" >	
				<tr>
					<th>Employee ID</th>
					<th>:</th>
					<th><select name=\"empid\" onchange=\"submit();\">
							$empselect
						</select>
					</th>
				</tr>
			</table>
		</div>
		<br />
		
		<div style=\"width:100%;height:450px;\">
		
			<div style=\"width:49%;height:450px;float:left;overflow:auto;\">
				<br />
				<span class=\"sectiontitle\">CURRENT ACCESS</span>
				<br />
				
				<table width=\"100%\" class=\"listcol\">
					<tr>
						<th>MENU</th>
						<th>SUB1</th>
						<th>SUB2</th>
						<th>SUB3</th>
					</tr>
				";
				while ($rowcurrent = mysql_fetch_array($qrycurrent))
				{
					$accessid1 = $rowcurrent["ACCESSID"];
					$menu = $rowcurrent["MENU"];
					$sub1 = $rowcurrent["SUBMENU1"];
					$sub2 = $rowcurrent["SUBMENU2"];
					$sub3 = $rowcurrent["SUBMENU3"];
					
					echo "
					<tr title=\"$accessid1\" style=\"cursor:pointer;\" $mouseovereffect
						ondblclick=\"accessid.value='$accessid1';actiontxt.value='deleteaccess';submit();\">
						<td>$menu&nbsp;</td>
						<td>$sub1&nbsp;</td>
						<td>$sub2&nbsp;</td>
						<td>$sub3&nbsp;</td>
					</tr>
					";
				}

				echo "
				</table>
			</div>
			
			<div style=\"width:49%;height:450px;float:right;overflow:auto;\">
				<br />
				<span class=\"sectiontitle\">AVAILABLE ACCESS</span>
				<br />
				
				<table width=\"100%\" class=\"listcol\">
					<tr>
						<th>MENU</th>
						<th>SUB1</th>
						<th>SUB2</th>
						<th>SUB3</th>
					</tr>	
				";
				while ($rowavailable = mysql_fetch_array($qryavailable))
				{
					$accessid2 = $rowavailable["ACCESSID"];
					$menu = $rowavailable["MENU"];
					$sub1 = $rowavailable["SUBMENU1"];
					$sub2 = $rowavailable["SUBMENU2"];
					$sub3 = $rowavailable["SUBMENU3"];
					
					echo "
					<tr title=\"$accessid2\" style=\"cursor:pointer;\" $mouseovereffect
						ondblclick=\"accessid.value='$accessid2';actiontxt.value='saveaccess';submit();\">
						<td>$menu&nbsp;</td>
						<td>$sub1&nbsp;</td>
						<td>$sub2&nbsp;</td>
						<td>$sub3&nbsp;</td>
					</tr>
					";
				}

				echo "			
				</table>
			</div>
			
		
		</div>
		
	</div>
	
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"accessid\" />
</form>

</body>
</html>
";

?>

