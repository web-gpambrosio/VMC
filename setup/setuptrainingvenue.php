<?php

include("veritas/connectdb.php");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
		
$formname = "formtrainvenue";
$formtitle = "TRAINING VENUE SETUP";

$checkmarquez = "";

//POSTS

if(isset($_POST['trainvenuecode']))
	$trainvenuecode=$_POST['trainvenuecode'];

if(isset($_POST['trainvenue']))
	$trainvenue=$_POST['trainvenue'];

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
		
			$qryverify = mysql_query("SELECT TRAINVENUECODE FROM trainingvenue WHERE TRAINVENUECODE='$trainvenuecode'") or die(mysql_error());
		
			if(mysql_num_rows($qryverify) == 0)
			{
				$qrytrainingvenuesave = mysql_query("INSERT INTO trainingvenue(TRAINVENUECODE,TRAINVENUE,
												STATUS,MADEBY,MADEDATE) 
												VALUES('$trainvenuecode','$trainvenue',$status,'$employeeid','$currentdate')
											") or die(mysql_error());
			}
			else 
			{
				$qrytrainingvenueupdate = mysql_query("UPDATE trainingvenue SET TRAINVENUE='$trainvenue',
															STATUS=$status,
															MADEBY='$employeeid',
															MADEDATE='$currentdate'
												 WHERE TRAINVENUECODE='$trainvenuecode'
											") or die(mysql_error());
			}
			
			$trainvenuecode = "";
			$trainvenue = "";
			$status = 0;

			$checkstatus = "";		
		break;
		
	case "cancel"	:
		
			$trainvenuecode = "";
			$trainvenue = "";
			$status = 0;

			$checkstatus = "";	

		break;
		
	case "delete"	:

		break;
}
	

/* LISTINGS  */

$qrytrainingvenue = mysql_query("SELECT * FROM trainingvenue") or die(mysql_error());


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
			
		<div style=\"width:100%;height:220px;float:right;padding:5px 20px 0 20px;\">
			<br />
			<span class=\"sectiontitle\">$formtitle</span>
			<br />
			
			<table class=\"setup\" width=\"100%\" >	
				<tr>
					<th>Training Venue Code</th>
					<th>:</th>
					<th><input type=\"text\" name=\"trainvenuecode\" value=\"$trainvenuecode\" size=\"10\"  onKeyPress=\"return alphanumeric(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Training Venue</th>
					<th>:</th>
					<th><input type=\"text\" name=\"trainvenue\" value=\"$trainvenue\" size=\"60\" onKeyPress=\"return alphanumeric(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
<!--
				<tr>
					<th>Max. Slots</th>
					<th>:</th>
					<th><input type=\"text\" name=\"maxslots\" value=\"$maxslots\" size=\"10\" onKeyPress=\"return alphanumeric(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
-->
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
		
		<div style=\"width:100%;height:230px;padding:5px 20px 0 20px;overflow:hidden;\">
			<br />
			<span class=\"sectiontitle\">CURRENT LISTING</span>
			<br />
			
			<div style=\"width:100%;height:200px;padding:0;overflow:auto;\">
				<table width=\"100%\" class=\"listcol\">
					<tr>
						<th>CODE</th>
						<th>VENUE</th>
						<th>STATUS</th>
						<th>MADEBY</th>
						<th>MADEDATE</th>
					</tr>
	";
				while ($rowtrainingvenue=mysql_fetch_array($qrytrainingvenue))
				{
					$list1 = $rowtrainingvenue["TRAINVENUECODE"];
					$list2 = $rowtrainingvenue["TRAINVENUE"];
						
					if ($rowtrainingvenue["STATUS"] == 0)
						$list4 = "INACTIVE";
					else 
						$list4 = "ACTIVE";
						
					$list5 = $rowtrainingvenue["MADEBY"];
					$list6 = date('m-d-Y',strtotime($rowtrainingvenue["MADEDATE"]));
					
					echo "
					<tr ondblclick=\"
						document.$formname.trainingvenuecode.value='$list1';
						document.$formname.trainingvenue.value='$list2';
						if ('$list4' == 'INACTIVE') {document.$formname.status.checked='';} else {document.$formname.status.checked='checked';}
						\">
						
						<td align=\"center\">$list1</td>
						<td>$list2</td>
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
</form>

</body>
</html>
";

?>

