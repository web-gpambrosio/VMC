<?php

include("veritas/connectdb.php");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
		
$formname = "formmanagement";
$formtitle = "MANAGEMENT SETUP";


//POSTS

if(isset($_POST['managementcode']))
	$managementcode=$_POST['managementcode'];

if(isset($_POST['management']))
	$management=$_POST['management'];

if(isset($_POST['alias']))
	$alias=$_POST['alias'];

if(isset($_POST['address']))
	$address=$_POST['address'];

if(isset($_POST['telno']))
	$telno=$_POST['telno'];

if(isset($_POST['faxno']))
	$faxno=$_POST['faxno'];

if(isset($_POST['email']))
	$email=$_POST['email'];
	
if(isset($_POST['manningcode']))
	$manningcode=$_POST['manningcode'];
	
if(isset($_POST['principalcode']))
	$principalcode=$_POST['principalcode'];
	
if(isset($_POST['unioncode']))
	$unioncode=$_POST['unioncode'];


switch ($actiontxt)
{
	case "save"		:
		
			$qryverify = mysql_query("SELECT MANAGEMENTCODE FROM management WHERE MANAGEMENTCODE='$managementcode'") or die(mysql_error());
		
			if(mysql_num_rows($qryverify) == 0)
			{
				$qrymanagementsave = mysql_query("INSERT INTO management(MANAGEMENTCODE,MANAGEMENT,ALIAS,ADDRESS,
																TELNO,FAXNO,EMAIL,MANNINGCODE,PRINCIPALCODE,UNIONCODE,MADEBY,MADEDATE) 
												VALUES('$managementcode','$management','$alias','$address','$telno',
														'$faxno','$email','$manningcode','$principalcode','$unioncode','$employeeid','$currentdate')
											") or die(mysql_error());
			}
			else 
			{
				$qrymanagementupdate = mysql_query("UPDATE management SET MANAGEMENT='$management',
															ALIAS='$alias',
															ADDRESS='$address',
															TELNO='$telno',
															FAXNO='$faxno',
															EMAIL='$email',
															MANNINGCODE='$manningcode',
															PRINCIPALCODE='$principalcode',
															UNIONCODE='$unioncode',
															MADEBY='$employeeid',
															MADEDATE='$currentdate'
												WHERE MANAGEMENTCODE='$managementcode'
											") or die(mysql_error());
			}
			
			$managementcode = "";
			$management = "";
			$alias = "";
			$address = "";
			$telno = "";
			$faxno = "";
			$email = "";
			$manningcode = "";
			$principalcode = "";
			$unioncode = "";
			
			
		break;
		
	case "cancel"	:
		
			$managementcode = "";
			$management = "";
			$alias = "";
			$address = "";
			$telno = "";
			$faxno = "";
			$email = "";
			$manningcode = "";
			$principalcode = "";
			$unioncode = "";

		break;
		
	case "delete"	:

		break;
}
	

/* LISTINGS  */

$qrymanningsel = mysql_query("SELECT MANNINGCODE,MANNING FROM manning ORDER BY MANNING") or die(mysql_error());

	$select1 = "<option selected value=\"\">--Select One--</option>";
	while($rowmanningsel = mysql_fetch_array($qrymanningsel))
	{
		$mancode = $rowmanningsel["MANNINGCODE"];
		$man = $rowmanningsel["MANNING"];
		
		$selected1 = "";
		
		if ($manningcode == $mancode)
			$selected1 = "SELECTED";
			
		$select1 .= "<option $selected1 value=\"$mancode\">$man</option>";
	}
	
$qryprincipalsel = mysql_query("SELECT PRINCIPALCODE,PRINCIPAL FROM principal ORDER BY PRINCIPAL") or die(mysql_error());

	$select2 = "<option selected value=\"\">--Select One--</option>";
	while($rowprincipalsel = mysql_fetch_array($qryprincipalsel))
	{
		$prncplcode = $rowprincipalsel["PRINCIPALCODE"];
		$prncpl = $rowprincipalsel["PRINCIPAL"];
		
		$selected1 = "";
		
		if ($principalcode == $prncplcode)
			$selected1 = "SELECTED";
			
		$select2 .= "<option $selected1 value=\"$prncplcode\">$prncpl</option>";
	}

$qryunionsel = mysql_query("SELECT UNIONCODE,`UNION` FROM `union` ORDER BY `UNION`") or die(mysql_error());

	$select3 = "<option selected value=\"\">--Select One--</option>";
	while($rowunionsel = mysql_fetch_array($qryunionsel))
	{
		$uncode = $rowunionsel["UNIONCODE"];
		$un = $rowunionsel["UNION"];
		
		$selected1 = "";
		
		if ($unioncode == $uncode)
			$selected1 = "SELECTED";
			
		$select3 .= "<option $selected1 value=\"$uncode\">$un</option>";
	}


$qrymanagement = mysql_query("SELECT m.MANAGEMENTCODE,m.MANAGEMENT,m.ALIAS,m.ADDRESS,m.TELNO,m.FAXNO,m.EMAIL,
							m.MANNINGCODE,mn.MANNING,m.PRINCIPALCODE,p.PRINCIPAL,m.UNIONCODE,u.UNION,m.MADEBY,m.MADEDATE
							FROM management m
							LEFT JOIN manning mn ON mn.MANNINGCODE=m.MANNINGCODE
							LEFT JOIN principal p ON p.PRINCIPALCODE=m.PRINCIPALCODE
							LEFT JOIN `union` u ON u.UNIONCODE=m.UNIONCODE
							") or die(mysql_error());


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
	<div style=\"width:100%;height:500px;padding:5px 20px 0 20px;\">
			
		<div style=\"width:100%;height:350px;float:right;padding:5px 20px 0 20px;\">
			<br />
			<span class=\"sectiontitle\">$formtitle</span>
			<br />
			
			<table class=\"setup\" width=\"100%\" >	
				<tr>
					<th>Management Code</th>
					<th>:</th>
					<th><input type=\"text\" name=\"managementcode\" value=\"$managementcode\" size=\"10\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Management</th>
					<th>:</th>
					<th><input type=\"text\" name=\"management\" value=\"$management\" size=\"60\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Alias</th>
					<th>:</th>
					<th><input type=\"text\" name=\"alias\" value=\"$alias\" size=\"10\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Address</th>
					<th>:</th>
					<th><input type=\"text\" name=\"address\" value=\"$address\" size=\"60\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Tel. No.</th>
					<th>:</th>
					<th><input type=\"text\" name=\"telno\" value=\"$telno\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Fax No.</th>
					<th>:</th>
					<th><input type=\"text\" name=\"faxno\" value=\"$faxno\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Email</th>
					<th>:</th>
					<th><input type=\"text\" name=\"email\" value=\"$email\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Manning Agency</th>
					<th>:</th>
					<th><select name=\"manningcode\">
							$select1
						</select>
					</th>
				</tr>
				<tr>
					<th>Principal</th>
					<th>:</th>
					<th><select name=\"principalcode\">
							$select2
						</select>
					</th>
				</tr>
				<tr>
					<th>Union</th>
					<th>:</th>
					<th><select name=\"unioncode\">
							$select3
						</select>
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
		
		<div style=\"width:100%;height:200px;padding:5px 20px 0 20px;overflow:hidden;\">
			<br />
			<span class=\"sectiontitle\">CURRENT LISTING</span>
			<br />
			
			<div style=\"width:100%;height:130px;padding:0;overflow:auto;\">
				<table width=\"100%\" class=\"listcol\">
					<tr>
						<th>CODE</th>
						<th>MANAGEMENT</th>
						<th>ALIAS</th>
						<th>ADDRESS</th>
						<th>MADEBY</th>
						<th>MADEDATE</th>
					</tr>
	";
				while ($rowmanagement=mysql_fetch_array($qrymanagement))
				{
					$list1 = $rowmanagement["MANAGEMENTCODE"];
					$list2 = $rowmanagement["MANAGEMENT"];
					$list3 = $rowmanagement["ALIAS"];
					$list4 = $rowmanagement["ADDRESS"];
					$list6 = $rowmanagement["FAXNO"];
					$list8 = $rowmanagement["MANNINGCODE"];
					$list8a = $rowmanagement["MANNING"];
					$list9 = $rowmanagement["PRINCIPALCODE"];
					$list9a = $rowmanagement["PRINCIPAL"];
					$list10 = $rowmanagement["UNIONCODE"];
					$list10a = $rowmanagement["UNION"];
						
					$list11 = $rowmanagement["MADEBY"];
					$list12 = date('m-d-Y',strtotime($rowmanagement["MADEDATE"]));
					
					echo "
					<tr ondblclick=\"
						document.$formname.managementcode.value='$list1';
						document.$formname.management.value='$list2';
						document.$formname.alias.value='$list3';
						document.$formname.address.value='$list4';
						document.$formname.telno.value='$list5';
						document.$formname.faxno.value='$list6';
						document.$formname.email.value='$list7';
						document.$formname.manningcode.value='$list8';
						document.$formname.principalcode.value='$list9';
						document.$formname.unioncode.value='$list10';
						\">
						
						<td>$list1</td>
						<td>$list2</td>
						<td>$list3</td>
						<td>$list4</td>
						<td>$list11</td>
						<td>$list12</td>
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

