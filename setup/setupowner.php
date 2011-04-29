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
		
$formname = "formowner";
$formtitle = "VESSEL OWNER SETUP";

$checkstatus = "";

//POSTS

if(isset($_POST['ownercode']))
	$ownercode=$_POST['ownercode'];
	
if(isset($_POST['owner']))
	$owner=$_POST['owner'];
	
if(isset($_POST['address']))
	$address=$_POST['address'];
	
if(isset($_POST['telno']))
	$telno=$_POST['telno'];
	
if(isset($_POST['incharge']))
	$incharge=$_POST['incharge'];


switch ($actiontxt)
{
	case "save"		:
		
			$qryverify = mysql_query("SELECT OWNERCODE FROM owner WHERE OWNERCODE='$ownercode'") or die(mysql_error());
		
			if(mysql_num_rows($qryverify) == 0)
			{
				$qryownersave = mysql_query("INSERT INTO owner(OWNERCODE,OWNER,ADDRESS,TELNO,INCHARGE,MADEBY,MADEDATE) 
													VALUES('$ownercode','$owner','$address','$telno','$incharge','$employeeid','$currentdate')
												") or die(mysql_error());
			}
			else 
			{
				$qryownerupdate = mysql_query("UPDATE owner SET OWNER='$owner',
																ADDRESS='$address',
																TELNO='$telno',
																INCHARGE='$incharge',
																MADEBY='$employeeid',
																MADEDATE='$currentdate'
													WHERE OWNERCODE='$ownercode'
													") or die(mysql_error());
			}

			$ownercode = "";
			$owner = "";
			$address = "";
			$telno = "";
			$incharge = "";
			
		break;
		
	case "cancel"	:

			$ownercode = "";
			$owner = "";
			$address = "";
			$telno = "";
			$incharge = "";
		
		break;
		
	case "delete"	:

		break;
}
	

/* LISTINGS  */

$qryowner = mysql_query("SELECT * FROM owner ORDER BY OWNER") or die(mysql_error());


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
			
		<div style=\"width:100%;height:275px;float:right;padding:5px 20px 0 20px;overflow:auto;\">
			<br />
			<span class=\"sectiontitle\">$formtitle</span>
			<br />
			
			<table class=\"setup\" width=\"100%\" >	
				<tr>
					<th>Owner Code</th>
					<th>:</th>
					<th><input type=\"text\" name=\"ownercode\" value=\"$ownercode\" size=\"15\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Owner</th>
					<th>:</th>
					<th><input type=\"text\" name=\"owner\" value=\"$owner\" size=\"50\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Address</th>
					<th>:</th>
					<th><input type=\"text\" name=\"address\" value=\"$address\" size=\"15\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Tel. No.</th>
					<th>:</th>
					<th><input type=\"text\" name=\"telno\" value=\"$telno\" size=\"50\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>In-charge</th>
					<th>:</th>
					<th><input type=\"text\" name=\"incharge\" value=\"$incharge\" size=\"50\" onKeyPress=\"return alphanumericonly(this);\"
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
		
		<div style=\"width:100%;height:225px;padding:5px 20px 0 20px;overflow:hidden;\">
			<br />
			<span class=\"sectiontitle\">CURRENT LISTING</span>
			<br />
			
			<div style=\"width:100%;height:250px;padding:0;overflow:auto;\">
				<table width=\"100%\" class=\"listcol\">
					<tr>
						<th>CODE</th>
						<th>OWNER</th>
						<th>ADDRESS</th>
						<th>TELNO</th>
						<th>INCHARGE</th>
						<th>MADE BY</th>
						<th>MADE DATE</th>
					</tr>
	";
				while ($rowowner=mysql_fetch_array($qryowner))
				{
					$list1 = $rowowner["OWNERCODE"];
					$list2 = $rowowner["OWNER"];
					$list3 = $rowowner["ADDRESS"];
					$list4 = $rowowner["TELNO"];
					$list5 = $rowowner["INCHARGE"];
						
					$list6 = $rowowner["MADEBY"];
					$list7 = date('m-d-Y',strtotime($rowowner["MADEDATE"]));
					
					echo "
					<tr ondblclick=\"
						document.$formname.ownercode.value='$list1';
						document.$formname.owner.value='$list2';
						document.$formname.address.value='$list3';
						document.$formname.telno.value='$list4';
						document.$formname.incharge.value='$list5';
						\">
						
						<td align=\"center\">$list1</td>
						<td>$list2</td>
						<td>$list3</td>
						<td>$list4</td>
						<td>$list5</td>
						<td align=\"center\">$list6</td>
						<td align=\"center\">$list7</td>
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

