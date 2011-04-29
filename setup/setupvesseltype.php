<?php

include("veritas/connectdb.php");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
		
$formname = "formvesseltype";
$formtitle = "VESSEL TYPE SETUP";

$checkstatus = "";

//POSTS

if(isset($_POST['vesseltypecode']))
	$vesseltypecode=$_POST['vesseltypecode'];
	
if(isset($_POST['vesseltype']))
	$vesseltype=$_POST['vesseltype'];
	
if(isset($_POST['alias']))
	$alias=$_POST['alias'];
//else 
//	$alias = "O";
	
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
		
			$qryverify = mysql_query("SELECT VESSELTYPECODE FROM vesselsize WHERE VESSELTYPECODE='$vesseltypecode'") or die(mysql_error());
		
			if(mysql_num_rows($qryverify) == 0)
			{
				$qryvesseltypesave = mysql_query("INSERT INTO vesselsize(VESSELTYPECODE,VESSELTYPE,STATUS,ALIAS,MADEBY,MADEDATE) 
													VALUES('$vesseltypecode','$vesseltype',$status,'$alias','$employeeid','$currentdate')
												") or die(mysql_error());
			}
			else 
			{
				$qryvesseltypeupdate = mysql_query("UPDATE vesselsize SET VESSELTYPE='$vesseltype',
																		STATUS=$status,
																		ALIAS='$alias',
																		MADEBY='$employeeid',
																		MADEDATE='$currentdate'
													WHERE VESSELTYPECODE='$vesseltypecode'
													") or die(mysql_error());
			}

			$vesseltypecode = "";
			$vesseltype = "";
			$status = "";
			$alias = "";
			
			$checkstatus = "";
			
		break;
		
	case "cancel"	:

			$vesseltypecode = "";
			$vesseltype = "";
			$status = "";
			$alias = "";
			
			$checkstatus = "";
		
		break;
		
	case "delete"	:

		break;
}
	

/* LISTINGS  */

$qryvesseltype = mysql_query("SELECT * FROM vesseltype") or die(mysql_error());


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
					<th>Vessel Type Code</th>
					<th>:</th>
					<th><input type=\"text\" name=\"vesseltypecode\" value=\"$vesseltypecode\" size=\"15\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Vessel Type</th>
					<th>:</th>
					<th><input type=\"text\" name=\"vesseltype\" value=\"$vesseltype\" size=\"50\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Alias</th>
					<th>:</th>
					<th><input type=\"text\" name=\"alias\" value=\"$alias\" size=\"1\" maxlength=\"1\" onKeyPress=\"return alphanumericonly(this);\"
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
		
		<div style=\"width:100%;height:300px;padding:5px 20px 0 20px;overflow:hidden;\">
			<br />
			<span class=\"sectiontitle\">CURRENT LISTING</span>
			<br />
			
			<div style=\"width:100%;height:250px;padding:0;overflow:auto;\">
				<table width=\"100%\" class=\"listcol\">
					<tr>
						<th>CODE</th>
						<th>VESSELTYPE</th>
						<th>STATUS</th>
						<th>ALIAS</th>
						<th>MADE BY</th>
						<th>MADE DATE</th>
					</tr>
	";
				while ($rowvesseltype=mysql_fetch_array($qryvesseltype))
				{
					$list1 = $rowvesseltype["VESSELTYPECODE"];
					$list2 = $rowvesseltype["VESSELTYPE"];
						
					if ($rowvesseltype["STATUS"] == 0)
						$list3 = "INACTIVE";
					else 
						$list3 = "ACTIVE";
						
					$list4 = $rowvesseltype["MADEBY"];
					$list5 = date('m-d-Y',strtotime($rowvesseltype["MADEDATE"]));
					
					$list6 = $rowvesseltype["ALIAS"];
					
					echo "
					<tr ondblclick=\"
						document.$formname.vesseltypecode.value='$list1';
						document.$formname.vesseltype.value='$list2';
						document.$formname.alias.value='$list6';
						if ('$list3' == 'INACTIVE') {document.$formname.status.checked='';} else {document.$formname.status.checked='checked';}
						\">
						
						<td align=\"center\">$list1</td>
						<td>$list2</td>
						<td align=\"center\">$list3</td>
						<td align=\"center\">$list6</td>
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

