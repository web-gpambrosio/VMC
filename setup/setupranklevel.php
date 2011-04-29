<?php

include("veritas/connectdb.php");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
		
$formname = "formranklevel";
$formtitle = "RANK LEVEL SETUP";

$checkstatus = "";

//POSTS

if(isset($_POST['ranklevelcode']))
	$ranklevelcode=$_POST['ranklevelcode'];

if(isset($_POST['ranklevel']))
	$ranklevel=$_POST['ranklevel'];
	
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
		
			$qryverify = mysql_query("SELECT RANKLEVELCODE FROM ranklevel WHERE RANKLEVELCODE='$ranktypecode'") or die(mysql_error());
		
			if(mysql_num_rows($qryverify) == 0)
			{
				$qryranklevelsave = mysql_query("INSERT INTO ranklevel(RANKLEVELCODE,RANKLEVEL,STATUS,MADEBY,MADEDATE) 
												VALUES('$ranklevelcode','$ranklevel',$status,'$employeeid','$currentdate')
											") or die(mysql_error());
				
			}
			else 
			{
				$qryranklevelupdate = mysql_query("UPDATE ranklevel SET RANKLEVEL='$ranklevel',
																	STATUS=$status
													WHERE RANKLEVELCODE='$ranklevelcode'
												") or die(mysql_error());
			}

			$ranklevelcode = "";
			$ranklevel = "";
			$status = 0;
			
			$checkstatus = "";
			
		break;
		
	case "cancel"	:

			$ranklevelcode = "";
			$ranklevel = "";
			$status = 0;
			
			$checkstatus = "";
		
		break;

}
	

/* LISTINGS  */


$qryranklevel = mysql_query("SELECT * FROM ranklevel") or die(mysql_error());


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
					<th>Rank Type Code</th>
					<th>:</th>
					<th><input type=\"text\" name=\"ranklevelcode\" value=\"$ranklevelcode\" size=\"15\" maxlength=\"10\" onKeyPress=\"return alphaonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Rank Type</th>
					<th>:</th>
					<th><input type=\"text\" name=\"ranklevel\" value=\"$ranklevel\" size=\"50\" maxlength=\"45\" onKeyPress=\"return alphaonly(this);\"
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
		
		<div style=\"width:100%;height:350px;padding:5px 20px 0 20px;overflow:hidden;\">
			<br />
			<span class=\"sectiontitle\">CURRENT LISTING</span>
			<br />
			
			<div style=\"width:100%;height:300px;padding:0;overflow:auto;\">
				<table width=\"100%\" class=\"listcol\">
					<tr>
						<th>CODE</th>
						<th>RANK TYPE</th>
						<th>STATUS</th>
						<th>MADE BY</th>
						<th>MADE DATE</th>
					</tr>
	";
				while ($rowranklevel=mysql_fetch_array($qryranklevel))
				{
					$list1 = $rowranklevel["RANKLEVELCODE"];
					$list2 = $rowranklevel["RANKLEVEL"];
						
					if ($rowranklevel["STATUS"] == 0)
						$list3 = "INACTIVE";
					else 
						$list3 = "ACTIVE";
						
					$list4 = $rowranklevel["MADEBY"];
					$list5 = date('m-d-Y',strtotime($rowranklevel["MADEDATE"]));
					
					echo "
					<tr ondblclick=\"
						document.$formname.ranklevelcode.value='$list1';
						document.$formname.ranklevel.value='$list2';
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
</form>

</body>
</html>
";

?>

