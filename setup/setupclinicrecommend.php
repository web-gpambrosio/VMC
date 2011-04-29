<?php

include("veritas/connectdb.php");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
		
$formname = "formclinicrecommend";
$formtitle = "CLINIC RECOMMENDATION SETUP";

$checkstatus = "";

//POSTS

if(isset($_POST['recommendcode']))
	$recommendcode=$_POST['recommendcode'];
	
if(isset($_POST['recommendation']))
	$recommendation=$_POST['recommendation'];
	
	
switch ($actiontxt)
{
	case "save"		:
		
			$qryverify = mysql_query("SELECT RECOMMENDCODE FROM clinicrecommend WHERE RECOMMENDCODE='$recommendcode'") or die(mysql_error());
		
			if(mysql_num_rows($qryverify) == 0)
			{
				$qryclinicrecommendsave = mysql_query("INSERT INTO clinicrecommend(RECOMMENDCODE,RECOMMENDATION,MADEBY,MADEDATE) 
													VALUES('$recommendcode','$recommendation','$employeeid','$currentdate')
												") or die(mysql_error());
			}
			else 
			{
				$qryclinicrecommendupdate = mysql_query("UPDATE clinicrecommend SET RECOMMENDATION='$recommendation',
																MADEBY='$employeeid',
																MADEDATE='$currentdate'
												WHERE RECOMMENDCODE='$recommendcode'
													") or die(mysql_error());
			}

			$recommendcode = "";
			$recommendation = "";
			
		break;
		
	case "cancel"	:

			$recommendcode = "";
			$recommendation = "";
		
		break;
		
	case "delete"	:

		break;
}
	

/* LISTINGS  */

$qryclinicrecommend = mysql_query("SELECT * FROM clinicrecommend ORDER BY RECOMMENDCODE") or die(mysql_error());


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
					<th>Recommendation Code</th>
					<th>:</th>
					<th><input type=\"text\" name=\"recommendcode\" value=\"$recommendcode\" size=\"10\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Recommendation</th>
					<th>:</th>
					<th><input type=\"text\" name=\"recommendation\" value=\"$recommendation\" size=\"50\" onKeyPress=\"return alphanumericonly(this);\"
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
						<th>CODE</th>
						<th>RECOMMENDATION</th>
						<th>MADE BY</th>
						<th>MADE DATE</th>
					</tr>
	";
				while ($rowclinicrecommend=mysql_fetch_array($qryclinicrecommend))
				{
					$list1 = $rowclinicrecommend["RECOMMENDCODE"];
					$list2 = $rowclinicrecommend["RECOMMENDATION"];
						
					$list3 = $rowclinicrecommend["MADEBY"];
					$list4 = date('m-d-Y',strtotime($rowclinicrecommend["MADEDATE"]));
					
					echo "
					<tr ondblclick=\"
						document.$formname.recommendcode.value='$list1';
						document.$formname.recommendation.value='$list2';
						\">
						
						<td align=\"center\">$list1</td>
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

