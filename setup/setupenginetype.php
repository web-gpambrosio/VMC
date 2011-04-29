<?php

include("veritas/connectdb.php");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_POST['delcode']))
	$delcode = $_POST['delcode'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
		
$formname = "formenginetype";
$formtitle = "ENGINE TYPE SETUP";

$checkstatus = "";

//POSTS

if(isset($_POST['enginetypecode']))
	$enginetypecode=$_POST['enginetypecode'];
	
if(isset($_POST['enginetype']))
	$enginetype=$_POST['enginetype'];
	
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
		
			$qryverify = mysql_query("SELECT ENGINETYPECODE FROM enginetype WHERE ENGINETYPECODE='$enginetypecode'") or die(mysql_error());
		
			if(mysql_num_rows($qryverify) == 0)
			{
				$qryenginetypesave = mysql_query("INSERT INTO enginetype(ENGINETYPECODE,ENGINETYPE,STATUS,MADEBY,MADEDATE) 
													VALUES('$enginetypecode','$enginetype',$status,'$employeeid','$currentdate')
												") or die(mysql_error());
			}
			else 
			{
				$qryenginetypeupdate = mysql_query("UPDATE enginetype SET ENGINETYPE='$enginetype',
																		STATUS=$status,
																		MADEBY='$employeeid',
																		MADEDATE='$currentdate'
													WHERE ENGINETYPECODE='$enginetypecode'
													") or die(mysql_error());
			}

			$enginetypecode = "";
			$enginetype = "";
			$status = "";
			
			$checkstatus = "";
			
		break;
		
	case "cancel"	:

			$enginetypecode = "";
			$enginetype = "";
			$status = "";
			
			$checkstatus = "";
		
		break;
		
	case "delete"	:

		break;
}
	

/* LISTINGS  */

$qryenginetype = mysql_query("SELECT * FROM enginetype") or die(mysql_error());


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
			
		<div style=\"width:100%;height:175px;float:right;padding:5px 20px 0 20px;overflow:auto;\">
			<br />
			<span class=\"sectiontitle\">$formtitle</span>
			<br />
			
			<table class=\"setup\" width=\"100%\" >	
				<tr>
					<th>Engine Type Code</th>
					<th>:</th>
					<th><input type=\"text\" name=\"enginetypecode\" value=\"$enginetypecode\" size=\"15\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Engine Type</th>
					<th>:</th>
					<th><input type=\"text\" name=\"enginetype\" value=\"$enginetype\" size=\"50\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Active?</th>
					<th>:</th>
					<th>
						<input type=\"checkbox\" name=\"status\" $checkstatus />
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
		
		<div style=\"width:100%;height:300px;padding:5px 20px 0 20px;overflow:hidden;\">
			<br />
			<span class=\"sectiontitle\">CURRENT LISTING</span>
			<br />
			
			<div style=\"width:100%;height:250px;padding:0;overflow:auto;\">
				<table width=\"100%\" class=\"listcol\">
					<tr>
						<th>CODE</th>
						<th>ENGINETYPE</th>
						<th>STATUS</th>
						<th>MADE BY</th>
						<th>MADE DATE</th>
					</tr>
	";
				while ($rowenginetype=mysql_fetch_array($qryenginetype))
				{
					$list1 = $rowenginetype["ENGINETYPECODE"];
					$list2 = $rowenginetype["ENGINETYPE"];
						
					if ($rowenginetype["STATUS"] == 0)
						$list3 = "INACTIVE";
					else 
						$list3 = "ACTIVE";
						
					$list4 = $rowenginetype["MADEBY"];
					$list5 = date('m-d-Y',strtotime($rowenginetype["MADEDATE"]));
					
					echo "
					<tr ondblclick=\"
						document.$formname.enginetypecode.value='$list1';
						document.$formname.enginetype.value='$list2';
						if ('$list3' == 'INACTIVE') {document.$formname.status.checked='';} else {document.$formname.status.checked='checked';}
						\">
						
						<td align=\"center\">$list1</td>
						<td>$list2</td>
						<td align=\"center\">$list3</td>
						<td align=\"center\">$list4</td>
						<td align=\"center\">$list5</td>
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
	<input type=\"hidden\" name=\"delcode\" />
</form>

</body>
</html>
";

?>

