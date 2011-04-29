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
		
$formname = "formcountry";
$formtitle = "COUNTRY SETUP";

$checkflag = "";

//POSTS

if(isset($_POST['countrycode']))
	$countrycode=$_POST['countrycode'];

if(isset($_POST['country']))
	$country=$_POST['country'];

if(isset($_POST['flag']))
{
	$flag=1;
	$checkflag = "checked=\"checked\"";
}
else 
	$flag=0;


switch ($actiontxt)
{
	case "save"		:
		
			$qryverify = mysql_query("SELECT COUNTRYCODE FROM country WHERE COUNTRYCODE='$countrycode'") or die(mysql_error());
		
			if(mysql_num_rows($qryverify) == 0)
			{
				$qrycountrysave = mysql_query("INSERT INTO country(COUNTRYCODE,COUNTRY,FLAG,MADEBY,MADEDATE) 
												VALUES('$countrycode','$country',$flag,'$employeeid','$currentdate')
											") or die(mysql_error());
			}
			else 
			{
				$qrycountryupdate = mysql_query("UPDATE country SET COUNTRY='$country',
															FLAG=$flag,
															MADEBY='$employeeid',
															MADEDATE='$currentdate'
												WHERE COUNTRYCODE='$countrycode'
											") or die(mysql_error());
			}
			
			$countrycode = "";
			$country = "";
			$flag = 0;
			
			$checkflag = "";
			
		break;
		
	case "cancel"	:

			$countrycode = "";
			$country = "";
			$flag = 0;
			
			$checkflag = "";
		
		break;
		
	case "delete"	:

		break;
}
	

/* LISTINGS  */

	
$qrycountry = mysql_query("SELECT * FROM country") or die(mysql_error());

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
					<th>Country Code</th>
					<th>:</th>
					<th><input type=\"text\" name=\"countrycode\" value=\"$countrycode\" size=\"10\" onKeyPress=\"return alphaonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Country Name</th>
					<th>:</th>
					<th><input type=\"text\" name=\"country\" value=\"$country\" size=\"50\" onKeyPress=\"return alphaonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Flag?</th>
					<th>:</th>
					<th>
						<input type=\"checkbox\" name=\"flag\" $checkflag />
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
						<th>COUNTRY</th>
						<th>FLAG</th>
						<th>MADEBY</th>
						<th>MADEDATE</th>
					</tr>
	";
				while ($rowcountry=mysql_fetch_array($qrycountry))
				{
					$list1 = $rowcountry["COUNTRYCODE"];
					$list2 = addslashes($rowcountry["COUNTRY"]);
					
					if ($rowcountry["FLAG"] == 0)
						$list3 = "NO";
					else 
						$list3 = "YES";
						
					$list4 = $rowcountry["MADEBY"];
					$list5 = date('m-d-Y',strtotime($rowcountry["MADEDATE"]));
					
					echo "
					<tr ondblclick=\"
						document.$formname.countrycode.value='$list1';
						document.$formname.country.value='$list2';
						if ('$list3' == 'NO') {document.$formname.flag.checked='';} else {document.$formname.flag.checked='checked';}
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

