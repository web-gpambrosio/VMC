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
		
$formname = "formtown";
$formtitle = "TOWN SETUP";

	

//POSTS

if(isset($_POST['provcode']))
	$provcode=$_POST['provcode'];

if(isset($_POST['towncode']))
	$towncode=$_POST['towncode'];

if(isset($_POST['town']))
	$town=$_POST['town'];


switch ($actiontxt)
{
	case "save"		:
		
			$qryverify = mysql_query("SELECT PROVCODE FROM addrtown WHERE PROVCODE='$provcode' AND TOWNCODE='$towncode'") or die(mysql_error());
		
			if(mysql_num_rows($qryverify) == 0)
			{
				$qrytownsave = mysql_query("INSERT INTO addrtown(PROVCODE,TOWNCODE,TOWN,MADEBY,MADEDATE) 
											VALUES('$provcode','$towncode','$town','$employeeid','$currentdate')
										") or die(mysql_error());
			}
			else 
			{
				$qrytownupdate = mysql_query("UPDATE addrtown SET TOWN='$town',
															MADEBY='$employeeid',
															MADEDATE='$currentdate'
											WHERE PROVCODE='$provcode' AND TOWNCODE='$towncode'
											") or die(mysql_error());
			}

			$provcode = "";
			$towncode = "";
			$town = "";
			
		break;
		
	case "cancel"	:
		
			$provcode = "";
			$towncode = "";
			$town = "";

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

$qrytown = mysql_query("SELECT ap.PROVCODE,ap.PROVINCE,TOWNCODE,TOWN,at.MADEBY,at.MADEDATE 
						FROM addrtown at
						LEFT JOIN addrprovince ap ON ap.PROVCODE=at.PROVCODE
						ORDER BY PROVINCE
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
					<th><select name=\"provcode\">
							$select1
						</select>
					</th>
				</tr>
				<tr>
					<th>Town Code</th>
					<th>:</th>
					<th><input type=\"text\" name=\"towncode\" value=\"$towncode\" size=\"30\" onKeyPress=\"return alphaonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Town</th>
					<th>:</th>
					<th><input type=\"text\" name=\"town\" value=\"$town\" size=\"30\" onKeyPress=\"return alphaonly(this);\"
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
						<th>TOWNCODE</th>
						<th>TOWN</th>
						<th>MADEBY</th>
						<th>MADEDATE</th>
					</tr>
	";
				while ($rowtown=mysql_fetch_array($qrytown))
				{
					$list1 = $rowtown["PROVCODE"];
					$list2 = addslashes($rowtown["PROVINCE"]);
					$list3 = $rowtown["TOWNCODE"];
					$list4 = addslashes($rowtown["TOWN"]);
						
					$list5 = $rowtown["MADEBY"];
					$list6 = date('m-d-Y',strtotime($rowtown["MADEDATE"]));
					
					echo "
					<tr ondblclick=\"
						document.$formname.provcode.value='$list1';
						document.$formname.towncode.value='$list3';
						document.$formname.town.value='$list4';
						\">
						
						<td>$list2</td>
						<td align=\"center\">$list3</td>
						<td>$list4</td>
						<td align=\"center\">$list5</td>
						<td align=\"center\">$list6</td>
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

