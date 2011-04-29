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
		
$formname = "formprovince";
$formtitle = "PROVINCE SETUP";

$checkflag = "";

//POSTS

if(isset($_POST['provcode']))
	$provcode=$_POST['provcode'];

if(isset($_POST['province']))
	$province=$_POST['province'];

if(isset($_POST['lvm']))
	$lvm=$_POST['lvm'];

if(isset($_POST['region']))
	$region=$_POST['region'];


switch ($actiontxt)
{
	case "save"		:
		
			$qryverify = mysql_query("SELECT PROVCODE FROM addrprovince WHERE PROVCODE='$provcode'") or die(mysql_error());
		
			if(mysql_num_rows($qryverify) == 0)
			{
				$qrycountrysave = mysql_query("INSERT INTO addrprovince(PROVCODE,PROVINCE,LVM,REGION,MADEBY,MADEDATE) 
												VALUES('$provcode','$province','$lvm','$region','$employeeid','$currentdate')
											") or die(mysql_error());
			}
			else 
			{
				$qrycountryupdate = mysql_query("UPDATE addrprovince SET PROVINCE='$province',
															LVM='$lvm',
															REGION='$region',
															MADEBY='$employeeid',
															MADEDATE='$currentdate'
												WHERE PROVCODE='$provcode'
											") or die(mysql_error());
			}
			
			$provcode = "";
			$province = "";
			$lvm = "";
			$region = "";
			
		break;
		
	case "cancel"	:

			$provcode = "";
			$province = "";
			$lvm = "";
			$region = "";
		
		break;
		
	case "delete"	:

		break;
}
	

/* LISTINGS  */

	
$qrycountry = mysql_query("SELECT * FROM addrprovince") or die(mysql_error());

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
			
		<div style=\"width:100%;height:200px;float:right;padding:5px 20px 0 20px;overflow:auto;\">
			<br />
			<span class=\"sectiontitle\">$formtitle</span>
			<br />
			
			<table class=\"setup\" width=\"100%\" >	
				<tr>
					<th>Province Code</th>
					<th>:</th>
					<th><input type=\"text\" name=\"provcode\" value=\"$provcode\" size=\"10\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Province Name</th>
					<th>:</th>
					<th><input type=\"text\" name=\"province\" value=\"$province\" size=\"50\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Area</th>
					<th>:</th>
					<th><select name=\"lvm\">
							<option value=\"\">--Select One--</option>
					";
						switch($lvm)
						{
							case "L"	:
									$select_L = "SELECTED";
								break;
							case "V"	:
									$select_V = "SELECTED";
								break;
							case "M"	:
									$select_M = "SELECTED";
								break;
						}
				echo "
							<option $select_L value=\"L\">LUZON</option>
							<option $select_V value=\"V\">VISAYAS</option>
							<option $select_M value=\"M\">MINDANAO</option>
						</select>
					</th>
				</tr>
				<tr>
					<th>Region</th>
					<th>:</th>
					<th><input type=\"text\" name=\"region\" value=\"$region\" size=\"5\" onKeyPress=\"return alphanumericonly(this);\"
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
			
			<div style=\"width:100%;height:225px;padding:0;overflow:auto;\">
				<table width=\"100%\" class=\"listcol\">
					<tr>
						<th>CODE</th>
						<th>PROVINCE</th>
						<th>AREA</th>
						<th>REGION</th>
						<th>MADEBY</th>
						<th>MADEDATE</th>
					</tr>
	";
				while ($rowcountry=mysql_fetch_array($qrycountry))
				{
					$list1 = $rowcountry["PROVCODE"];
					$list2 = addslashes($rowcountry["PROVINCE"]);
					$list3 = $rowcountry["LVM"];
					$list4 = $rowcountry["REGION"];
						
					$list5 = $rowcountry["MADEBY"];
					$list6 = date('m-d-Y',strtotime($rowcountry["MADEDATE"]));
					
					echo "
					<tr ondblclick=\"
						document.$formname.provcode.value='$list1';
						document.$formname.province.value='$list2';
						document.$formname.lvm.value='$list3';
						document.$formname.region.value='$list4';
						\">
						
						<td align=\"center\">$list1</td>
						<td>$list2</td>
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
	<input type=\"hidden\" name=\"delcode\" />
</form>

</body>
</html>
";

?>

