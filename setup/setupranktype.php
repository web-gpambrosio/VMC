<?php

include("veritas/connectdb.php");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
		
$formname = "formranktype";
$formtitle = "RANK TYPE SETUP";

$checkstatus = "";

//POSTS

if(isset($_POST['ranktypecode']))
	$ranktypecode=$_POST['ranktypecode'];

if(isset($_POST['ranktype']))
	$ranktype=$_POST['ranktype'];
	
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
		
			$qryverify = mysql_query("SELECT RANKTYPECODE FROM ranktype WHERE RANKTYPECODE='$ranktypecode'") or die(mysql_error());
		
			if(mysql_num_rows($qryverify) == 0)
			{
				$qryranktypesave = mysql_query("INSERT INTO ranktype(RANKTYPECODE,RANKTYPE,STATUS,MADEBY,MADEDATE) 
												VALUES('$ranktypecode','$ranktype',$status,'$employeeid','$currentdate')
											") or die(mysql_error());
				
			}
			else 
			{
				$qryranktypeupdate = mysql_query("UPDATE ranktype SET RANKTYPE='$ranktype',
																	STATUS=$status
													WHERE RANKTYPECODE='$ranktypecode'
												") or die(mysql_error());
			}

			$ranktypecode = "";
			$ranktype = "";
			$status = 0;
			
			$checkstatus = "";
			
		break;
		
	case "cancel"	:

			$ranktypecode = "";
			$ranktype = "";
			$status = 0;
			
			$checkstatus = "";
		
		break;
		
	case "delete"	:

		break;
}
	

/* LISTINGS  */


$qryranktype = mysql_query("SELECT * FROM ranktype") or die(mysql_error());


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
					<th><input type=\"text\" name=\"ranktypecode\" value=\"$ranktypecode\" size=\"15\" maxlength=\"10\" onKeyPress=\"return alphaonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Rank Type</th>
					<th>:</th>
					<th><input type=\"text\" name=\"ranktype\" value=\"$ranktype\" size=\"50\" maxlength=\"45\" onKeyPress=\"return alphaonly(this);\"
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
				while ($rowranktype=mysql_fetch_array($qryranktype))
				{
					$list1 = $rowranktype["RANKTYPECODE"];
					$list2 = $rowranktype["RANKTYPE"];
						
					if ($rowranktype["STATUS"] == 0)
						$list3 = "INACTIVE";
					else 
						$list3 = "ACTIVE";
						
					$list4 = $rowranktype["MADEBY"];
					$list5 = date('m-d-Y',strtotime($rowranktype["MADEDATE"]));
					
					echo "
					<tr ondblclick=\"
						document.$formname.ranktypecode.value='$list1';
						document.$formname.ranktype.value='$list2';
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

