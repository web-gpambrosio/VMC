<?php

include("veritas/connectdb.php");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
		
$formname = "formclinic";
$formtitle = "CLINIC SETUP";

$checkstatus = "";

//POSTS

if(isset($_POST['clinic']))
	$clinic=$_POST['clinic'];
	
if(isset($_POST['address']))
	$address=$_POST['address'];
	
if(isset($_POST['contactperson']))
	$contactperson=$_POST['contactperson'];
	
if(isset($_POST['telno']))
	$telno=$_POST['telno'];
	
	
switch ($actiontxt)
{
	case "save"		:
		
			$qryverify = mysql_query("SELECT CLINICID FROM clinic WHERE CLINIC='$clinic'") or die(mysql_error());
		
			if(mysql_num_rows($qryverify) == 0)
			{
				$qryclinicsave = mysql_query("INSERT INTO clinic(CLINIC,ADDRESS,TELNO,CONTACTPERSON,MADEBY,MADEDATE) 
													VALUES('$clinic','$address','$telno','$contactperson','$employeeid','$currentdate')
												") or die(mysql_error());
			}
			else 
			{
				$qryclinicupdate = mysql_query("UPDATE clinic SET CLINIC='$clinic',
																ADDRESS='$address',
																TELNO='$telno',
																CONTACTPERSON='$contactperson',
																MADEBY='$employeeid',
																MADEDATE='$currentdate'
													WHERE CLINIC='$clinic'
													") or die(mysql_error());
			}

			$clinic = "";
			$address = "";
			$telno = "";
			$contactperson = "";
			
		break;
		
	case "cancel"	:

			$clinic = "";
			$address = "";
			$telno = "";
			$contactperson = "";
		
		break;
		
	case "delete"	:

		break;
}
	

/* LISTINGS  */

$qryclinic = mysql_query("SELECT * FROM clinic ORDER BY CLINIC") or die(mysql_error());


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
			
		<div style=\"width:100%;height:210px;float:right;padding:5px 20px 0 20px;overflow:auto;\">
			<br />
			<span class=\"sectiontitle\">$formtitle</span>
			<br />
			
			<table class=\"setup\" width=\"100%\" >	
				<tr>
					<th>Clinic Name</th>
					<th>:</th>
					<th><input type=\"text\" name=\"clinic\" value=\"$clinic\" size=\"50\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Address</th>
					<th>:</th>
					<th><input type=\"text\" name=\"address\" value=\"$address\" size=\"50\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Tel. No.</th>
					<th>:</th>
					<th><input type=\"text\" name=\"telno\" value=\"$telno\" size=\"50\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Contact Person</th>
					<th>:</th>
					<th><input type=\"text\" name=\"contactperson\" value=\"$contactperson\" size=\"50\" onKeyPress=\"return alphanumericonly(this);\"
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
		
		<div style=\"width:100%;height:290px;padding:5px 20px 0 20px;overflow:hidden;\">
			<br />
			<span class=\"sectiontitle\">CURRENT LISTING</span>
			<br />
			
			<div style=\"width:100%;height:220px;padding:0;overflow:auto;\">
				<table width=\"100%\" class=\"listcol\">
					<tr>
						<th>CLINIC</th>
						<th>ADDRESS</th>
						<th>TELNO</th>
						<th>CONTACT</th>
						<th>MADE BY</th>
						<th>MADE DATE</th>
					</tr>
	";
				while ($rowclinic=mysql_fetch_array($qryclinic))
				{
					$list1 = $rowclinic["CLINIC"];
					$list2 = $rowclinic["ADDRESS"];
					$list3 = $rowclinic["TELNO"];
					$list4 = $rowclinic["CONTACTPERSON"];
						
					$list5 = $rowclinic["MADEBY"];
					$list6 = date('m-d-Y',strtotime($rowclinic["MADEDATE"]));
					
					echo "
					<tr ondblclick=\"
						document.$formname.clinic.value='$list1';
						document.$formname.address.value='$list2';
						document.$formname.telno.value='$list3';
						document.$formname.contactperson.value='$list4';
						\">
						
						<td>$list1</td>
						<td>$list2</td>
						<td>$list3</td>
						<td>$list4</td>
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
	<input type=\"hidden\" name=\"delcode\" />
</form>

</body>
</html>
";

?>

