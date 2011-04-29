<?php

include("veritas/connectdb.php");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
		
$formname = "formschool";
$formtitle = "MARITIME SCHOOL SETUP";

$checkstatus = "";

//POSTS

if(isset($_POST['school']))
	$school=$_POST['school'];

if(isset($_POST['alias']))
	$alias=$_POST['alias'];

if(isset($_POST['address']))
	$address=$_POST['address'];

if(isset($_POST['contactperson']))
	$contactperson=$_POST['contactperson'];

if(isset($_POST['contactno']))
	$contactno=$_POST['contactno'];

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
		
			$qryverify = mysql_query("SELECT SCHOOLID FROM maritimeschool WHERE SCHOOL='$school'") or die(mysql_error());
		
			if(mysql_num_rows($qryverify) == 0)
			{
				$qryschoolsave = mysql_query("INSERT INTO maritimeschool(SCHOOL,ALIAS,ADDRESS,CONTACTNO,CONTACTPERSON,STATUS,MADEBY,MADEDATE) 
													VALUES('$school','$alias','$address','$contactno','$contactperson',$status,'$employeeid','$currentdate')
												") or die(mysql_error());
			}
			else 
			{
				$rowverify = mysql_fetch_array($qryverify);
				$schoolid = $rowverify["SCHOOLID"];
				
				$qryschoolupdate = mysql_query("UPDATE maritimeschool SET SCHOOL='$school',
																ALIAS='$alias',
																ADDRESS='$address',
																CONTACTNO='$contactno',
																CONTACTPERSON='$contactperson',
																STATUS=$status,
																MADEBY='$employeeid',
																MADEDATE='$currentdate'
													WHERE SCHOOLID='$schoolid'
													") or die(mysql_error());
			}

			$school = "";
			$address = "";
			$contactno = "";
			$contactperson = "";
			$alias = "";
			$status = 0;
			
			$checkstatus = "";
			
		break;
		
	case "cancel"	:

			$school = "";
			$address = "";
			$contactno = "";
			$contactperson = "";
			$alias = "";
			$status = 0;
			
			$checkstatus = "";
		
		break;
		
	case "delete"	:

		break;
}
	

/* LISTINGS  */

$qryschool = mysql_query("SELECT * FROM maritimeschool ORDER BY SCHOOL") or die(mysql_error());

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
	<div style=\"width:100%;height:600px;padding:5px 20px 0 20px;overflow:hidden;\">
			
		<div style=\"width:100%;height:270px;float:right;padding:5px 20px 0 20px;overflow:auto;\">
			<br />
			<span class=\"sectiontitle\">$formtitle</span>
			<br />
			
			<table class=\"setup\" width=\"100%\" >	
				<tr>
					<th>School Name</th>
					<th>:</th>
					<th><input type=\"text\" name=\"school\" value=\"$school\" size=\"50\" onKeyPress=\"return alphaonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Alias</th>
					<th>:</th>
					<th><input type=\"text\" name=\"alias\" value=\"$alias\" size=\"30\" onKeyPress=\"return alphaonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Address</th>
					<th>:</th>
					<th><input type=\"text\" name=\"address\" value=\"$address\" size=\"30\" onKeyPress=\"return alphaonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Contact No.</th>
					<th>:</th>
					<th><input type=\"text\" name=\"contactno\" value=\"$contactno\" size=\"30\" onKeyPress=\"\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Contact Person</th>
					<th>:</th>
					<th><input type=\"text\" name=\"contactperson\" value=\"$contactperson\" size=\"30\" onKeyPress=\"return alphaonly(this);\"
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
			
			<div style=\"width:100%;height:220px;padding:0;overflow:auto;\">
				<table width=\"100%\" class=\"listcol\">
					<tr>
						<th>SCHOOL</th>
						<th>ALIAS</th>
						<th>ADDRESS</th>
						<th>STATUS</th>
						<th>CONTACT NO</th>
						<th>CONTACT PERSON</th>
					</tr>
	";
				while ($rowschool=mysql_fetch_array($qryschool))
				{
					$list1 = $rowschool["SCHOOL"];
					$list2 = $rowschool["ALIAS"];
					$list3 = $rowschool["ADDRESS"];
						
					if ($rowschool["STATUS"] == 0)
						$list4 = "INACTIVE";
					else 
						$list4 = "ACTIVE";
						
//					$list5 = $rowschool["MADEBY"];
//					$list6 = date('m-d-Y',strtotime($rowschool["MADEDATE"]));
						
					$list5 = $rowschool["CONTACTNO"];
					$list6 = $rowschool["CONTACTPERSON"];
					
					echo "
					<tr ondblclick=\"
						document.$formname.school.value='$list1';
						document.$formname.alias.value='$list2';
						document.$formname.address.value='$list3';
						if ('$list4' == 'INACTIVE') {document.$formname.status.checked='';} else {document.$formname.status.checked='checked';}
						\">
						
						<td>$list1</td>
						<td>$list2</td>
						<td>&nbsp;$list3</td>
						<td align=\"center\">&nbsp;$list4</td>
						<td align=\"center\">&nbsp;$list5</td>
						<td align=\"center\">&nbsp;$list6</td>
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

