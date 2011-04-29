<?php

include("veritas/connectdb.php");
include("veritas/include/stylephp.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
	
$formname = "formsetupdtl";
$formtitle = "SETUP LEVELS SETUP";

	

//POSTS

if (isset($_POST['setupid']))
	$setupid = $_POST['setupid'];
	
if (isset($_POST['selsetupid']))
	$selsetupid = $_POST['selsetupid'];
	
if (isset($_POST['selsubsetupid']))
	$selsubsetupid = $_POST['selsubsetupid'];
	
if (isset($_POST['empid']))
	$empid = $_POST['empid'];
	
switch ($actiontxt)
{
	case "tocurrent":
	
			$qryaddcurrent = mysql_query("INSERT INTO setupaccess(EMPLOYEEID,SETUPID,SUBSETUPID,MADEBY,MADEDATE)
										VALUES('$empid',$selsetupid,$selsubsetupid,'$employeeid','$currentdate')
									") or die(mysql_error());
		
		break;
		
	case "toavailable":
		
			$qrytoavailable = mysql_query("DELETE FROM setupaccess 
										WHERE EMPLOYEEID='$empid' AND SETUPID=$selsetupid 
										AND SUBSETUPID=$selsubsetupid
										") or die(mysql_error());
		
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

$qrysetuphdrlist = mysql_query("SELECT SETUPID,SETUP FROM setuphdr") or die(mysql_error());
$hdrselect = "<option value=\"\">--Select One--</option>";
$selected = "";
while ($rowsetuphdrlist = mysql_fetch_array($qrysetuphdrlist))
{
	$sid = $rowsetuphdrlist["SETUPID"];
	$set = $rowsetuphdrlist["SETUP"];
	
	if ($sid == $setupid)
		$selected = "SELECTED";
	else 
		$selected = "";
	
	$hdrselect .= "<option $selected value=\"$sid\">$set&nbsp;($sid)</option>";
}

//$qrysetupdtllist = mysql_query("SELECT SUBSETUPID,SUBSETUP FROM setupdtl WHERE SETUPID=$setupid ORDER BY SUBPOSITION") or die(mysql_error());
//$dtlselect = "<option value=\"\">--Select One--</option>";
//$selected = "";
//while ($rowsetuphdrlist = mysql_fetch_array($qrysetuphdrlist))
//{
//	$subid = $rowsetuphdrlist["SUBSETUPID"];
//	$subset = $rowsetuphdrlist["SUBSETUP"];
//	
//	if ($subid == $subsetupid)
//		$selected = "SELECTED";
//	else 
//		$selected = "";
//	
//	$dtlselect .= "<option $selected value=\"$subid\">$subset&nbsp;($subid)</option>";
//}


if (!empty($setupid))
{
	$qrysetupavailable = mysql_query("SELECT sd.SETUPID,sd.SUBSETUPID,sd.SUBSETUP
							FROM setupdtl sd 
							LEFT JOIN setupaccess sa ON sa.SETUPID=sd.SETUPID AND sa.SUBSETUPID=sd.SUBSETUPID AND sa.EMPLOYEEID='$empid'
			 				WHERE sd.SETUPID=$setupid AND sa.SETUPID IS NULL
							") or die(mysql_error());
	
	$qrysetupcurrent = mysql_query("SELECT sd.SETUPID,sd.SUBSETUPID,sd.SUBSETUP
							FROM setupaccess sa
							LEFT JOIN setupdtl sd ON sd.SETUPID=sa.SETUPID AND sd.SUBSETUPID=sa.SUBSETUPID
							WHERE sa.SETUPID=$setupid AND sa.EMPLOYEEID='$empid'
							") or die(mysql_error());
}

/*END OF LISTINGS*/


echo "
<html>
<head>
<link rel=\"stylesheet\" type=\"text/css\" href=\"../veripro.css\">
<script language=\"javascript\" src=\"../veripro.js\"></script>

</head>
<body>

<form name=\"$formname\" method=\"POST\">
	
	<center>
	<div style=\"width:100%;height:550px;padding:5px 20px 0 20px;overflow:hidden;\">
			
		<div style=\"width:100%;padding:5px 20px 0 20px;overflow:hidden;\">
			<br />
			<span class=\"sectiontitle\">$formtitle</span>
			<br />
			
			<table class=\"setup\" width=\"100%\" >	
				<tr>
					<th>Employee</th>
					<th>:</th>
					<th><select name=\"empid\" onchange=\"submit();\">
							$empselect
						</select>
					
					</th>
				</tr>	
				<tr>
					<th>Setup Header</th>
					<th>:</th>
					<th><select name=\"setupid\" onchange=\"submit();\">
							$hdrselect
						</select>
					
					</th>
				</tr>
			</table>
			<br /><br />
			<div style=\"width:49%;height:350px;float:left;overflow:auto;border:1px solid Gray;\">
				<span class=\"sectiontitle\">AVAILABLE ACCESS</span>
				<br />
				
				<table class=\"listcol\">
					<tr>
						<th width=\"20px\">SUBSETUPID</th>
						<th>SUBSETUP</th>
					</tr>
				";
				while ($rowsetupavailable = mysql_fetch_array($qrysetupavailable))
				{
					$xsetupid = $rowsetupavailable["SETUPID"];
					$xsubsetupid = $rowsetupavailable["SUBSETUPID"];
					$xsubsetup = $rowsetupavailable["SUBSETUP"];
					
					echo "
					<tr $mouseovereffect ondblclick=\"selsetupid.value='$xsetupid';selsubsetupid.value='$xsubsetupid';
													actiontxt.value='tocurrent';submit();\">
						<td align=\"center\" style=\"cursor:pointer;\">$xsubsetupid</td>
						<td style=\"cursor:pointer;\">$xsubsetup</td>
					</tr>
					";
				}


				echo "
				</table>
			</div>

			<div style=\"width:49%;height:350px;float:right;overflow:auto;border:1px solid Gray;\">
				<span class=\"sectiontitle\">CURRENT ACCESS</span>
				<br />
				
				<table class=\"listcol\">
					<tr>
						<th width=\"20px\">SUBSETUPID</th>
						<th>SUBSETUP</th>
					</tr>
				";
				while ($rowsetupcurrent = mysql_fetch_array($qrysetupcurrent))
				{
					$zsetupid = $rowsetupcurrent["SETUPID"];
					$zsubsetupid = $rowsetupcurrent["SUBSETUPID"];
					$zsubsetup = $rowsetupcurrent["SUBSETUP"];
					
					echo "
					<tr $mouseovereffect ondblclick=\"selsetupid.value='$zsetupid';selsubsetupid.value='$zsubsetupid';
													actiontxt.value='toavailable';submit();\">
						<td align=\"center\" style=\"cursor:pointer;\">$zsubsetupid</td>
						<td style=\"cursor:pointer;\">$zsubsetup</td>
					</tr>
					";
				}


				echo "
				</table>
			</div>
		</div>
	</div>
	</center>
	
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"selsetupid\" />
	<input type=\"hidden\" name=\"selsubsetupid\" />
</form>

</body>
</html>
";

?>

