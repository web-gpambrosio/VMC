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
		
$formname = "formfamilyrelation";
$formtitle = "FAMILY RELATIONSHIP SETUP";

$checkmember = "";

//POSTS

if(isset($_POST['relcode']))
	$relcode=$_POST['relcode'];

if(isset($_POST['relation']))
	$relation=$_POST['relation'];

if(isset($_POST['remarks']))
	$remarks=$_POST['remarks'];

if(isset($_POST['member']))
{
	$member=1;
	$checkmember = "checked=\"checked\"";
}
else 
	$member=0;


switch ($actiontxt)
{
	case "save"		:
			$qryverify = mysql_query("SELECT RELCODE FROM familyrelation WHERE RELCODE='$relcode'") or die(mysql_error());
		
			if(mysql_num_rows($qryverify) == 0)
			{
				$qryfamilyrelationsave = mysql_query("INSERT INTO familyrelation(RELCODE,RELATION,REMARKS,FAMILYMEMBER,MADEBY,MADEDATE)
													VALUES('$relcode','$relation','$remarks',$member,'$employeeid','$currentdate')
													") or die(mysql_error());
			}
			else 
			{
				$qryfamilyrelationupdate = mysql_query("UPDATE familyrelation SET RELATION='$relation',
																			REMARKS='$remarks',
																			FAMILYMEMBER=$member,
																			MADEDATE='$currentdate',
																			MADEBY='$employeeid'
														WHERE RELCODE='$relcode'
														") or die(mysql_error());
			}
			
			$relcode = "";
			$relation = "";
			$remarks = "";
			$member = 0;
			
			$checkmember = "";
			
		break;
		
	case "cancel"	:

			$relcode = "";
			$relation = "";
			$remarks = "";
			$member = 0;
			
			$checkmember = "";
		
		break;
		
	case "delete"	:

		break;
}
	

/* LISTINGS  */

$qryfamilyrelation = mysql_query("SELECT * FROM familyrelation") or die(mysql_error());


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
			
		<div style=\"width:100%;height:250px;float:right;padding:5px 20px 0 20px;overflow:auto;\">
			<br />
			<span class=\"sectiontitle\">$formtitle</span>
			<br />
			
			<table class=\"setup\" width=\"100%\" >	
				<tr>
					<th>Relation Code</th>
					<th>:</th>
					<th><input type=\"text\" name=\"relcode\" value=\"$relcode\" size=\"15\" maxlength=\"10\" onKeyPress=\"return alphaonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Relation</th>
					<th>:</th>
					<th><input type=\"text\" name=\"relation\" value=\"$relation\" size=\"50\" maxlength=\"45\" onKeyPress=\"return alphaonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Family Member?</th>
					<th>:</th>
					<th>
						<input type=\"checkbox\" name=\"member\" $checkmember />
					</th>
				</tr>
				<tr>
					<th>Remarks</th>
					<th>:</th>
					<th>
						<textarea rows=\"3\" cols=\"30\" name=\"remarks\">$remarks</textarea>
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
			
			<div style=\"width:100%;height:175px;padding:0;overflow:auto;\">
				<table width=\"100%\" class=\"listcol\">
					<tr>
						<th>CODE</th>
						<th>RELATION</th>
						<th>REMARKS</th>
						<th>FAMILY MEMBER</th>
						<th>MADE BY</th>
						<th>MADE DATE</th>
					</tr>
	";
				while ($rowfamilyrelation=mysql_fetch_array($qryfamilyrelation))
				{
					$list1 = $rowfamilyrelation["RELCODE"];
					$list2 = $rowfamilyrelation["RELATION"];
					$list3 = $rowfamilyrelation["REMARKS"];
					
					if ($rowfamilyrelation["FAMILYMEMBER"] == 0)
						$list4 = "NO";
					else 
						$list4 = "YES";
						
					$list5 = $rowfamilyrelation["MADEBY"];
					$list6 = date('m-d-Y',strtotime($rowfamilyrelation["MADEDATE"]));
					
					echo "
					<tr ondblclick=\"
						document.$formname.relcode.value='$list1';
						document.$formname.relation.value='$list2';
						document.$formname.remarks.value='$list3';
						if ('$list4' == 'NO') {document.$formname.member.checked='';} else {document.$formname.member.checked='checked';}
						\">
						
						<td align=\"center\">$list1</td>
						<td>$list2</td>
						<td align=\"center\">$list3</td>
						<td align=\"center\">$list4</td>
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

