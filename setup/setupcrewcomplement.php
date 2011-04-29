<?php

include("veritas/connectdb.php");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
		
$formname = "formcrewcomplement";
$formtitle = "CREW COMPLEMENT";

	

//POSTS

if(isset($_POST['vesselcode']))
	$vesselcode=$_POST['vesselcode'];

if(isset($_POST['rankcode']))
	$rankcode=$_POST['rankcode'];

if(isset($_POST['quantity']))
	$quantity=$_POST['quantity'];

if(isset($_POST['nationality']))
	$nationality=$_POST['nationality'];


switch ($actiontxt)
{
	case "save"		:
		
			$qryverify = mysql_query("SELECT VESSELCODE FROM crewcomplement WHERE VESSELCODE='$vesselcode' AND RANKCODE='$rankcode'") or die(mysql_error());
		
			if(mysql_num_rows($qryverify) == 0)
			{
				$qrycomplementsave = mysql_query("INSERT INTO crewcomplement(VESSELCODE,RANKCODE,QUANTITY,NATIONALITY,MADEBY,MADEDATE) 
													VALUES('$vesselcode','$rankcode',$quantity,'$nationality','$employeeid','$currentdate')
												") or die(mysql_error());
			}
			else 
			{
				$qrycomplementupdate = mysql_query("UPDATE crewcomplement SET QUANTITY=$quantity,
																			NATIONALITY='$nationality',
																			MADEBY='$employeeid',
																			MADEDATE='$currentdate'
													WHERE VESSELCODE='$vesselcode' AND RANKCODE='$rankcode'
													") or die(mysql_error());
			}

			$rankcode = "";
			$quantity = "";
			$nationality = "";
			
		break;
		
	case "cancel"	:
		
			$vesselcode = "";
			$rankcode = "";
			$quantity = "";
			$nationality = "";

		break;
		
	case "delete"	:

		break;
}
	

/* LISTINGS  */

$qryvesselsel = mysql_query("SELECT VESSELCODE,VESSEL FROM vessel WHERE STATUS=1 ORDER BY VESSEL") or die(mysql_error());

	$select1 = "<option selected value=\"\">--Select One--</option>";
	while($rowvesselsel = mysql_fetch_array($qryvesselsel))
	{
		$vslcode = $rowvesselsel["VESSELCODE"];
		$vsl = $rowvesselsel["VESSEL"];
		
		$selected1 = "";
		
		if ($vesselcode == $vslcode)
			$selected1 = "SELECTED";
			
		$select1 .= "<option $selected1 value=\"$vslcode\">$vsl</option>";
	}
	
$qryranksel = mysql_query("SELECT RANKCODE,RANK FROM rank WHERE STATUS=1") or die(mysql_error());

	$select2 = "<option selected value=\"\">--Select One--</option>";
	while($rowranksel = mysql_fetch_array($qryranksel))
	{
		$rankcode1 = $rowranksel["RANKCODE"];
		$rank1 = $rowranksel["RANK"];
		
		$selected1 = "";
		
		if ($rankcode == $rankcode1)
			$selected1 = "SELECTED";
			
		$select2 .= "<option $selected1 value=\"$rankcode1\">$rank1</option>";
	}

$qrycountrysel = mysql_query("SELECT COUNTRYCODE,COUNTRY FROM country") or die(mysql_error());

	$select3 = "<option selected value=\"\">--Select One--</option>";
	while($rowcountrysel = mysql_fetch_array($qrycountrysel))
	{
		$countrycode = $rowcountrysel["COUNTRYCODE"];
		$country = $rowcountrysel["COUNTRY"];
		
		$selected1 = "";
		
		if ($nationality == $countrycode)
			$selected1 = "SELECTED";
			
		$select3 .= "<option $selected1 value=\"$countrycode\">$country</option>";
	}


$qrycomplement = mysql_query("SELECT cc.IDNO,cc.VESSELCODE,v.VESSEL,cc.RANKCODE,r.ALIAS1,cc.QUANTITY,cc.NATIONALITY,cc.MADEBY,cc.MADEDATE
								FROM crewcomplement cc 
								LEFT JOIN vessel v ON v.VESSELCODE=cc.VESSELCODE
								LEFT JOIN rank r ON r.RANKCODE=cc.RANKCODE
								WHERE cc.VESSELCODE='$vesselcode'
								ORDER BY r.RANKING") or die(mysql_error());
	
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
					<th>Vessel</th>
					<th>:</th>
					<th><select name=\"vesselcode\" onchange=\"$formname.submit();\">
							$select1
						</select>
					</th>
				</tr>
				<tr>
					<th>Rank</th>
					<th>:</th>
					<th><select name=\"rankcode\">
							$select2
						</select>
					</th>
				</tr>
				<tr>
					<th>Quantity</th>
					<th>:</th>
					<th><input type=\"text\" name=\"quantity\" value=\"$quantity\" size=\"5\" onKeyPress=\"return numbersonly(this);\" /></th>
				</tr>
				<tr>
					<th>Nationality</th>
					<th>:</th>
					<th><select name=\"nationality\">
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
		
		<div style=\"width:100%;height:300px;padding:5px 20px 0 20px;overflow:hidden;\">
			<br />
			<span class=\"sectiontitle\">CURRENT LISTING</span>
			<br />
			
			<div style=\"width:100%;height:230px;padding:0;overflow:auto;\">
				<table width=\"100%\" class=\"listcol\">
					<tr>
						<th>VESSEL</th>
						<th>RANK</th>
						<th>QUANTITY</th>
						<th>NATIONALITY</th>
						<th>MADE BY</th>
						<th>MADE DATE</th>
					</tr>
	";
				while ($rowcomplement=mysql_fetch_array($qrycomplement))
				{
					$list1 = $rowcomplement["VESSELCODE"];
					$list2 = $rowcomplement["VESSEL"];
					$list3 = $rowcomplement["RANKCODE"];
					$list4 = $rowcomplement["ALIAS1"];
					$list5 = $rowcomplement["QUANTITY"];
					$list6 = $rowcomplement["NATIONALITY"];
						
					$list7 = $rowcomplement["MADEBY"];
					$list8 = date('m-d-Y',strtotime($rowcomplement["MADEDATE"]));
					
					echo "
					<tr ondblclick=\"
						document.$formname.vesselcode.value='$list1';
						document.$formname.rankcode.value='$list3';
						document.$formname.quantity.value='$list5';
						document.$formname.nationality.value='$list6';
						\">
						
						<td>$list2</td>
						<td align=\"center\">$list4</td>
						<td align=\"center\">$list5</td>
						<td align=\"center\">$list6</td>
						<td align=\"center\">$list7</td>
						<td align=\"center\">$list8</td>
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

