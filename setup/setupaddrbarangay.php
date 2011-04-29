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
		
$formname = "formbarangay";
$formtitle = "BARANGAY SETUP";

	

//POSTS

if(isset($_POST['provcode']))
	$provcode=$_POST['provcode'];
else 
	$provcode = "00";

if(isset($_POST['towncode']))
	$towncode=$_POST['towncode'];

if(isset($_POST['barangaycode']))
	$barangaycode=$_POST['barangaycode'];

if(isset($_POST['barangay']))
	$barangay=$_POST['barangay'];


switch ($actiontxt)
{
	case "save"		:
		
			$qryverify = mysql_query("SELECT PROVCODE FROM addrbarangay WHERE PROVCODE='$provcode' 
										AND TOWNCODE='$towncode'
										AND BRGYCODE='$barangaycode'") or die(mysql_error());
		
			if(mysql_num_rows($qryverify) == 0)
			{
				$qrytownsave = mysql_query("INSERT INTO addrbarangay(PROVCODE,TOWNCODE,BRGYCODE,BARANGAY,MADEBY,MADEDATE) 
											VALUES('$provcode','$towncode','$barangaycode','$barangay','$employeeid','$currentdate')
										") or die(mysql_error());
			}
			else 
			{
				$qrytownupdate = mysql_query("UPDATE addrtown SET BARANGAY='$barangay',
															MADEBY='$employeeid',
															MADEDATE='$currentdate'
											WHERE PROVCODE='$provcode' AND TOWNCODE='$towncode' AND BRGYCODE='$barangaycode'
											") or die(mysql_error());
			}

			$provcode = "";
			$towncode = "";
			$barangaycode = "";
			$barangay = "";
			
		break;
		
	case "cancel"	:
		
			$provcode = "";
			$towncode = "";
			$barangaycode = "";
			$barangay = "";

		break;
		
	case "delete"	:

		break;
}
	

/* LISTINGS  */

$qryprovincesel = mysql_query("SELECT PROVCODE,PROVINCE FROM addrprovince") or die(mysql_error());

	$select1 = "<option selected value=\"\">--Select One--</option>";
	while($rowprovincesel = mysql_fetch_array($qryprovincesel))
	{
		$provincecode = $rowprovincesel["PROVCODE"];
		$province1 = $rowprovincesel["PROVINCE"];
		
		$selected1 = "";
		
		if ($provcode == $provincecode)
			$selected1 = "SELECTED";
			
		$select1 .= "<option $selected1 value=\"$provincecode\">$province1</option>";
	}

$qrytownsel = mysql_query("SELECT TOWNCODE,TOWN FROM addrtown WHERE PROVCODE='$provcode'") or die(mysql_error());
	$select2 = "<option selected value=\"\">--Select One--</option>";
	while($rowtownsel = mysql_fetch_array($qrytownsel))
	{
		$towncode1 = $rowtownsel["TOWNCODE"];
		$town1 = $rowtownsel["TOWN"];
		
		$selected1 = "";
		
		if ($towncode == $towncode1)
			$selected1 = "SELECTED";
			
		$select2 .= "<option $selected1 value=\"$towncode1\">$town1</option>";
	}

	
	
$qrybarangay = mysql_query("SELECT ap.PROVCODE,ap.PROVINCE,at.TOWNCODE,at.TOWN,ab.BRGYCODE,ab.BARANGAY,ab.MADEBY,ab.MADEDATE
						FROM addrbarangay ab
						LEFT JOIN addrtown at ON at.PROVCODE=ab.PROVCODE AND at.TOWNCODE=ab.TOWNCODE
						LEFT JOIN addrprovince ap ON ap.PROVCODE=ab.PROVCODE
						WHERE ab.PROVCODE='$provcode'
						ORDER BY PROVINCE,TOWN,BARANGAY
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
	<div style=\"width:100%;height:500px;padding:5px 20px 0 20px;overflow:hidden;\">
			
		<div style=\"width:100%;height:200px;float:right;padding:5px 20px 0 20px;overflow:auto;\">
			<br />
			<span class=\"sectiontitle\">$formtitle</span>
			<br />
			
			<table class=\"setup\" width=\"100%\" >	
				<tr>
					<th>Province</th>
					<th>:</th>
					<th><select name=\"provcode\" onchange=\"$formname.submit();\">
							$select1
						</select>
					</th>
				</tr>
				<tr>
					<th>Town</th>
					<th>:</th>
					<th><select name=\"towncode\" onchange=\"$formname.submit();\">
							$select2
						</select>
					</th>
				</tr>
				<tr>
					<th>Barangay Code</th>
					<th>:</th>
					<th><input type=\"text\" name=\"barangaycode\" value=\"$barangaycode\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Barangay</th>
					<th>:</th>
					<th><input type=\"text\" name=\"barangay\" value=\"$barangay\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
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
						<th>PROVINCE</th>
						<th>TOWN</th>
						<th>BRGYCODE</th>
						<th>BARANGAY</th>
						<th>MADEBY</th>
						<th>MADEDATE</th>
					</tr>
	";
				while ($rowbarangay=mysql_fetch_array($qrybarangay))
				{
					$list1 = $rowbarangay["PROVCODE"];
					$list2 = addslashes($rowbarangay["PROVINCE"]);
					$list3 = $rowbarangay["TOWNCODE"];
					$list4 = addslashes($rowbarangay["TOWN"]);
					$list5 = $rowbarangay["BRGYCODE"];
					$list6 = addslashes($rowbarangay["BARANGAY"]);
						
					$list7 = $rowbarangay["MADEBY"];
					$list8 = date('m-d-Y',strtotime($rowbarangay["MADEDATE"]));
					
					echo "
					<tr ondblclick=\"
						document.$formname.provcode.value='$list1';
						document.$formname.towncode.value='$list3';
						document.$formname.town.value='$list4';
						document.$formname.brgycode.value='$list5';
						document.$formname.barangay.value='$list6';
						\">
						
						<td>$list2</td>
						<td>$list4</td>
						<td align=\"center\">$list5</td>
						<td>$list6</td>
						<td align=\"center\">$list7</td>
						<td align=\"center\">$list8</td>
<!--						<td align=\"center\">
							<input type=\"button\" style=\"border:0;color:red;font-size:1.1em;font-weight:bold;background-color: #DCDCDC;\" 
							name=\"btndelete\" onclick=\"if(confirm('Delete?')) {delcode.value='$list2'; 
							actiontxt.value='delete';$formname.submit();}\" value=\"X\">
						</td>
-->
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

