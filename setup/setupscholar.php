<?php

include("veritas/connectdb.php");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
		
$formname = "formscholar";
$formtitle = "SCHOLAR SETUP";
	

//POSTS

if(isset($_POST['scholasticcode']))
	$scholasticcode=$_POST['scholasticcode'];

if(isset($_POST['description']))
	$description=$_POST['description'];


switch ($actiontxt)
{
	case "save"		:
		
			$qryverify = mysql_query("SELECT SCHOLASTICCODE FROM scholar WHERE SCHOLASTICCODE='$scholasticcode'") or die(mysql_error());
		
			if(mysql_num_rows($qryverify) == 0)
			{
				$qryscholasticsave = mysql_query("INSERT INTO scholar(SCHOLASTICCODE,DESCRIPTION,MADEBY,MADEDATE) 
												VALUES('$scholasticcode','$description','$employeeid','$currentdate')") or die(mysql_error());
			}
			else 
			{
				$qryscholasticupdate = mysql_query("UPDATE scholar SET DESCRIPTION='$description',
																	MADEBY='$employeeid',
																	MADEDATE='$currentdate'
													WHERE SCHOLASTICCODE='$scholasticcode'") or die(mysql_error());
			}
			
			$scholasticcode = "";
			$description = "";
			
		break;
		
	case "cancel"	:

			$scholasticcode = "";
			$description = "";
			
		break;
		
	case "delete"	:

		break;
}
	

/* LISTINGS  */

$qryscholastic = mysql_query("SELECT * FROM scholar") or die(mysql_error());

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
					<th>Scholastic Code</th>
					<th>:</th>
					<th><input type=\"text\" name=\"scholasticcode\" value=\"$scholasticcode\" size=\"30\" onKeyPress=\"return alphaonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Description</th>
					<th>:</th>
					<th><input type=\"text\" name=\"description\" value=\"$description\" size=\"30\" onKeyPress=\"return alphaonly(this);\"
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
						<th>DESCRIPTION</th>
						<th>MADE BY</th>
						<th>MADE DATE</th>
					</tr>
	";
				while ($rowscholastic=mysql_fetch_array($qryscholastic))
				{
					$list1 = $rowscholastic["SCHOLASTICCODE"];
					$list2 = $rowscholastic["DESCRIPTION"];
					$list3 = $rowscholastic["MADEBY"];
					$list4 = date('m-d-Y',strtotime($rowscholastic["MADEDATE"]));
					
					echo "
					<tr ondblclick=\"
						document.$formname.scholasticcode.value='$list1';
						document.$formname.description.value='$list2';
						\">
						
						<td>$list1</td>
						<td>$list2</td>
						<td>$list3</td>
						<td>$list4</td>
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

