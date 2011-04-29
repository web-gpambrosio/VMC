<?php

include("veritas/connectdb.php");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
	
$formname = "formdivision";
$formtitle = "DIVISION SETUP";

	

//POSTS

if (isset($_POST['divcode']))
	$divcode = $_POST['divcode'];
	
if (isset($_POST['division']))
	$division = $_POST['division'];

if (isset($_POST['manager']))
	$manager = $_POST['manager'];
	
if (isset($_POST['remarks']))
	$remarks = $_POST['remarks'];


switch ($actiontxt)
{
	case "save"		:
			
			$qryverify = mysql_query("SELECT DIVCODE FROM division WHERE DIVCODE='$divcode'") or die(mysql_error());
		
			if(mysql_num_rows($qryverify) == 0)
			{		
				$qrydivisionsave = mysql_query("INSERT INTO division(DIVCODE,DIVISION,MANAGER,REMARKS,MADEBY,MADEDATE)
												VALUES('$divcode','$division','$manager','$remarks','$employeeid','$currentdate')
												") or die(mysql_error());
			}
			else 
			{
				$qrydivisionupdate = mysql_query("UPDATE division SET DIVISION='$designationcode',
																		MANAGER='$manager',
																		REMARKS='$remarks',
																		MADEBY='$employeeid',
																		MADEDATE='$currentdate'
												WHERE DIVCODE='$divcode'
												") or die(mysql_error());
			}
			
			$divcode = "";
			$division = "";
			$manager = "";
			$remarks = "";
			
		break;
		
	case "cancel"	:
		
			$divcode = "";
			$division = "";
			$manager = "";
			$remarks = "";

		break;
}
	

/* LISTINGS  */

$qryemployeesel = mysql_query("SELECT EMPLOYEEID,CONCAT(FNAME,', ',GNAME) AS NAME FROM employee") or die(mysql_error());

	$select1 = "<option selected value=\"\">--Select One--</option>";
	while($rowemployeesel = mysql_fetch_array($qryemployeesel))
	{
		$employeeid1 = $rowemployeesel["EMPLOYEEID"];
		$name = $rowemployeesel["NAME"];
		
		$selected1 = "";
		
		if ($manager == $employeeid1)
			$selected1 = "SELECTED";
			
		$select1 .= "<option $selected1 value=\"$employeeid1\">$name</option>";
	}



$qrydivision = mysql_query("SELECT * FROM division") or die(mysql_error());

/*END OF LISTINGS*/


echo "
<html>
<head>
<link rel=\"stylesheet\" type=\"text/css\" href=\"../veripro.css\">
<script language=\"javascript\" src=\"../veripro.js\"></script>

<script>

function checksave(x)
{
	var rem = '';
	
	with ($formname)
	{
		if(divcode.value=='' || divcode.value==null)
			if(rem=='')
				rem='Division Code';
			else
				rem=rem + ',Division Code';	
		if(division.value=='' || division.value==null)
			if(rem=='')
				rem='Division';
			else
				rem=rem + ',Division';
	}
			
	if(rem=='')
	{
		$formname.submit();
	}
	else
		alert('Please CHECK the following: ' + rem + ' before saving!');		

}
	
</script>
</head>
<body>

<form name=\"$formname\" method=\"POST\">
	
	<center>
	<div style=\"width:100%;height:500px;padding:5px 20px 0 20px;overflow:hidden;\">
			
		<div style=\"width:100%;height:250px;padding:5px 20px 0 20px;overflow:hidden;\">
			<br />
			<span class=\"sectiontitle\">$formtitle</span>
			<br />
			
			<table class=\"setup\" width=\"100%\" >	
				<tr>
					<th>Division Code</th>
					<th>:</th>
					<th><input type=\"text\" name=\"divcode\" value=\"$divcode\" size=\"15\" maxlength=\"10\" onKeyPress=\"return alphaonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Division Name</th>
					<th>:</th>
					<th><input type=\"text\" name=\"division\" value=\"$division\" size=\"50\" maxlength=\"45\" onKeyPress=\"return alphaonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Manager</th>
					<th>:</th>
					<th><select name=\"manager\">
							$select1
						</select>
					</th>
				</tr>
				<tr>
					<th>Remarks</th>
					<th>:</th>
					<th>
						<textarea rows=\"3\" cols=\"30\" name=\"remarks\">$remarks</textarea>
					</th>
				</tr>
				<tr>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
					<th><input type=\"button\" name=\"\" value=\"Save\" onclick=\"actiontxt.value='save';checksave();\" />
						<input type=\"button\" name=\"\" value=\"Cancel\" onclick=\"actiontxt.value='cancel';$formname.submit();\" />
					</th>
				</tr>
			</table>
		</div>
		
		<div style=\"width:100%;height:325px;padding:5px 20px 0 20px;overflow:hidden;\">
			<br />
			<span class=\"sectiontitle\">CURRENT LISTING</span>
			<br />
			
			<div style=\"width:100%;height:250px;padding:0;overflow:auto;\">
				<table width=\"100%\" class=\"listcol\" style=\"\">
					<tr>
						<th>CODE</th>
						<th>DIVISION</th>
						<th>MANAGER</th>
						<th>REMARKS</th>
						<th>MADEBY</th>
						<th>MADEDATE</th>
					</tr>
	";
				while ($rowdivision=mysql_fetch_array($qrydivision))
				{
					$list1 = $rowdivision["DIVCODE"];
					$list2 = $rowdivision["DIVISION"];
					$list3 = $rowdivision["MANAGER"];
					$list4 = $rowdivision["REMARKS"];
					$list5 = $rowdivision["MADEBY"];
					$list6 = date('m-d-Y',strtotime($rowdivision["MADEDATE"]));
					
					echo "
					<tr ondblclick=\"
						document.$formname.divcode.value='$list1';
						document.$formname.division.value='$list2';
						document.$formname.manager.value='$list3';
						document.$formname.remarks.value='$list4';
						\">
						<td align=\"center\">$list1</td>
						<td align=\"center\">$list2</td>
						<td align=\"center\">$list3</td>
						<td align=\"center\">$list4</td>
						<td align=\"center\">$list5</td>
						<td align=\"center\">$list6</td>
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
</form>

</body>
</html>
";

?>

