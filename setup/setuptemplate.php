<?php

include("veritas/connectdb.php");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
		
$formname = "";
$formtitle = "";

$checkstatus = "";

//POSTS

if(isset($_POST['ranklevelcode']))
	$ranklevelcode=$_POST['ranklevelcode'];


if(isset($_POST['ojtposition']))
{
	$ojtposition=1;
	$checkojtposition = "checked=\"checked\"";
}
else 
	$ojtposition=0;
	
if(isset($_POST['status']))
{
	$status=1;
	$checkstatus = "checked=\"checked\"";
}
else 
	$status=0;


switch ($actiontxt)
{
	case "save"		:

			
		break;
		
	case "cancel"	:

		break;
		
	case "delete"	:

		break;
}
	

/* LISTINGS  */

$qrydesignationsel = mysql_query("SELECT DESIGNATIONCODE,DESIGNATION FROM designation") or die(mysql_error());

	$empselect1 = "<option selected value=\"\">--Select One--</option>";
	while($rowdesignationsel = mysql_fetch_array($qrydesignationsel))
	{
		$descode = $rowdesignationsel["DESIGNATIONCODE"];
		$designation = $rowdesignationsel["DESIGNATION"];
		
		$selected1 = "";
		
		if ($empdesignation == $descode)
			$selected1 = "SELECTED";
			
		$empselect1 .= "<option value=\"$descode\">$designation</option>";
	}


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
			
		<div style=\"width:100%;height:150px;float:right;padding:5px 20px 0 20px;overflow:auto;\">
			<br />
			<span class=\"sectiontitle\">$formtitle</span>
			<br />
			
			<table class=\"setup\" width=\"100%\" >	
				<tr>
					<th>Family Name</th>
					<th>:</th>
					<th><input type=\"text\" name=\"empfname\" value=\"$empfname\" size=\"30\" onKeyPress=\"return alphaonly(this);\"
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
		
		<div style=\"width:100%;height:350px;padding:5px 20px 0 20px;overflow:hidden;\">
			<br />
			<span class=\"sectiontitle\">CURRENT LISTING</span>
			<br />
			
			<div style=\"width:100%;height:250px;padding:0;overflow:auto;\">
				<table width=\"100%\" class=\"listcol\">
					<tr>
						<th>CODE</th>
						<th>DESIGNATION</th>
						<th>MADE BY</th>
						<th>MADE DATE</th>
					</tr>
	";
				while ($rowcrewdocs=mysql_fetch_array($qrycrewdocs))
				{
					$list1 = $rowcrewdocs["TYPE"];
					$list2 = $rowcrewdocs["DOCCODE"];
					$list3 = $rowcrewdocs["DOCUMENT"];
					
					if ($rowcrewdocs["NEEDRANK"] == 0)
						$list4 = "NO";
					else 
						$list4 = "YES";
						
					if ($rowcrewdocs["STATUS"] == 0)
						$list5 = "INACTIVE";
					else 
						$list5 = "ACTIVE";
						
					$list6 = $rowcrewdocs["MADEBY"];
					$list7 = date('m-d-Y',strtotime($rowcrewdocs["MADEDATE"]));
					
					echo "
					<tr ondblclick=\"
						document.$formname.doccode.value='$list2';
						document.$formname.document.value='$list3';
						document.$formname.doctype.value='$list1';
						if ('$list4' == 'NO') {document.$formname.needrank.checked='';} else {document.$formname.needrank.checked='checked';}
						if ('$list5' == 'INACTIVE') {document.$formname.status.checked='';} else {document.$formname.status.checked='checked';}
						\">
						
						<td>$list1</td>
						<td>$list2</td>
						<td>$list3</td>
						<td>$list4</td>
						<td>$list5</td>
						<td>$list6</td>
						<td>$list7</td>
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

