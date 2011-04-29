<?php

include("veritas/connectdb.php");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
	
$formname = "formdesignation";
$formtitle = "DESIGNATION SETUP";

	

//POSTS

if (isset($_POST['designationcode']))
	$designationcode = $_POST['designationcode'];
	
if (isset($_POST['designation']))
	$designation = $_POST['designation'];


switch ($actiontxt)
{
	case "save"		:
			
			$qryverify = mysql_query("SELECT DESIGNATIONCODE FROM designation WHERE DESIGNATIONCODE='$designationcode'") or die(mysql_error());
		
			if(mysql_num_rows($qryverify) == 0)
			{		
				$qrydesignationsave = mysql_query("INSERT INTO designation(DESIGNATIONCODE,DESIGNATION,MADEBY,MADEDATE)
												VALUES('$designationcode','$designation','$employeeid','$currentdate')
												") or die(mysql_error());
			}
			else 
			{
				$qrydesignationupdate = mysql_query("UPDATE designation SET DESIGNATION='$designation',
																		MADEBY='$employeeid',
																		MADEDATE='$currentdate'
													WHERE DESIGNATIONCODE='$designationcode'
													") or die(mysql_error());
			}
			
			$designation = "";
			$designationcode = "";
		break;
		
	case "cancel"	:
		
			$designation = "";
			$designationcode = "";

		break;
}
	

/* LISTINGS  */

$qrydesignationlist = mysql_query("SELECT * FROM designation") or die(mysql_error());

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
		if(designationcode.value=='' || designationcode.value==null)
			if(rem=='')
				rem='Designation Code';
			else
				rem=rem + ',Designation Code';	
		if(designation.value=='' || designation.value==null)
			if(rem=='')
				rem='Designation';
			else
				rem=rem + ',Designation';
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
			
		<div style=\"width:100%;height:175px;padding:5px 20px 0 20px;overflow:hidden;\">
			<br />
			<span class=\"sectiontitle\">$formtitle</span>
			<br />
			
			<table class=\"setup\" width=\"100%\" >	
				<tr>
					<th>Designation Code</th>
					<th>:</th>
					<th><input type=\"text\" name=\"designationcode\" value=\"$designationcode\" size=\"15\" maxlength=\"10\" onKeyPress=\"return alphaonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Designation</th>
					<th>:</th>
					<th><input type=\"text\" name=\"designation\" value=\"$designation\" size=\"50\" maxlength=\"45\" onKeyPress=\"return alphaonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
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
						<th>DESIGNATION</th>
						<th>MADE BY</th>
						<th>MADE DATE</th>
					</tr>
	";
				while ($rowdesignationlist=mysql_fetch_array($qrydesignationlist))
				{
					$list1 = $rowdesignationlist["DESIGNATIONCODE"];
					$list2 = $rowdesignationlist["DESIGNATION"];
					$list3 = $rowdesignationlist["MADEBY"];
					$list4 = date('m-d-Y',strtotime($rowdesignationlist["MADEDATE"]));
					
					echo "
					<tr ondblclick=\"
						document.$formname.designationcode.value='$list1';
						document.$formname.designation.value='$list2';
						\">
						<td>$list1</td>
						<td>$list2</td>
						<td align=\"center\">$list3</td>
						<td align=\"center\">$list4</td>
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

