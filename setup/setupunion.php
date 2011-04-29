<?php

include("veritas/connectdb.php");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
		
$formname = "formunion";
$formtitle = "UNION SETUP";

$checkstatus = "";

//POSTS

if(isset($_POST['unioncode']))
	$unioncode=$_POST['unioncode'];

if(isset($_POST['union']))
	$union=$_POST['union'];

if(isset($_POST['address']))
	$address=$_POST['address'];

if(isset($_POST['telno']))
	$telno=$_POST['telno'];

if(isset($_POST['faxno']))
	$faxno=$_POST['faxno'];

if(isset($_POST['email']))
	$email=$_POST['email'];
	
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
		
			$qryverify = mysql_query("SELECT UNIONCODE FROM union WHERE UNIONCODE='$unioncode'") or die(mysql_error());
		
			if(mysql_num_rows($qryverify) == 0)
			{
				$qryunionsave = mysql_query("INSERT INTO union(UNIONCODE,UNION,ADDRESS,TELNO,FAXNO,EMAIL,STATUS,MADEBY,MADEDATE) 
												VALUES('$unioncode','$union','$address','$telno','$faxno','$email',$status,'$employeeid','$currentdate')
											") or die(mysql_error());
			}
			else 
			{
				$qryunionupdate = mysql_query("UPDATE union SET UNION='$union',
															ADDRESS='$address',
															TELNO='$telno',
															FAXNO='$faxno',
															EMAIL='$email',
															STATUS=$status,
															MADEBY='$employeeid',
															MADEDATE='$currentdate'
												WHERE UNIONCODE='$unioncode'
											") or die(mysql_error());
			}
			
			$unioncode = "";
			$union = "";
			$address = "";
			$telno = "";
			$faxno = "";
			$email = "";
			$status = 0;
			
		break;
		
	case "cancel"	:
		
			$unioncode = "";
			$union = "";
			$address = "";
			$telno = "";
			$faxno = "";
			$email = "";
			$status = 0;

		break;
		
	case "delete"	:

		break;
}
	

/* LISTINGS  */

$qryunion = mysql_query("SELECT * FROM `union`") or die(mysql_error());


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
			
		<div style=\"width:100%;height:285px;float:right;padding:5px 20px 0 20px;overflow:auto;\">
			<br />
			<span class=\"sectiontitle\">$formtitle</span>
			<br />
			
			<table class=\"setup\" width=\"100%\" >	
				<tr>
					<th>Union Code</th>
					<th>:</th>
					<th><input type=\"text\" name=\"unioncode\" value=\"$unioncode\" size=\"10\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Union</th>
					<th>:</th>
					<th><input type=\"text\" name=\"union\" value=\"$union\" size=\"10\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Address</th>
					<th>:</th>
					<th><input type=\"text\" name=\"address\" value=\"$address\" size=\"60\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Tel. No.</th>
					<th>:</th>
					<th><input type=\"text\" name=\"telno\" value=\"$telno\" size=\"10\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Fax No.</th>
					<th>:</th>
					<th><input type=\"text\" name=\"faxno\" value=\"$faxno\" size=\"10\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Email</th>
					<th>:</th>
					<th><input type=\"text\" name=\"email\" value=\"$email\" size=\"10\" onKeyPress=\"return alphanumericonly(this);\"
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
		
		<div style=\"width:100%;height:240px;padding:5px 20px 0 20px;overflow:hidden;\">
			<br />
			<span class=\"sectiontitle\">CURRENT LISTING</span>
			<br />
			
			<div style=\"width:100%;height:200px;padding:0;overflow:auto;\">
				<table width=\"100%\" class=\"listcol\">
					<tr>
						<th>CODE</th>
						<th>UNION</th>
						<th>ADDRESS</th>
						<th>TELNO</th>
						<th>EMAIL</th>
						<th>STATUS</th>
						<th>MADEBY</th>
						<th>MADEDATE</th>
					</tr>
	";
				while ($rowunion=mysql_fetch_array($qryunion))
				{
					$list1 = $rowunion["UNIONCODE"];
					$list2 = $rowunion["UNION"];
					$list3 = $rowunion["ADDRESS"];
					$list4 = $rowunion["TELNO"];
					$list5 = $rowunion["FAXNO"];
					$list6 = $rowunion["EMAIL"];
						
					if ($rowunion["STATUS"] == 0)
						$list7 = "INACTIVE";
					else 
						$list7 = "ACTIVE";
						
					$list8 = $rowunion["MADEBY"];
					$list9 = date('m-d-Y',strtotime($rowunion["MADEDATE"]));
					
					echo "
					<tr ondblclick=\"
						document.$formname.unioncode.value='$list1';
						document.$formname.union.value='$list2';
						document.$formname.address.value='$list3';
						document.$formname.telno.value='$list4';
						document.$formname.faxno.value='$list5';
						document.$formname.email.value='$list6';
						if ('$list7' == 'INACTIVE') {document.$formname.status.checked='';} else {document.$formname.status.checked='checked';}
						\">
						
						<td align=\"center\">$list1</td>
						<td>$list2</td>
						<td>$list3</td>
						<td>$list4</td>
						<td>$list6</td>
						<td align=\"center\">$list7</td>
						<td align=\"center\">$list8</td>
						<td align=\"center\">$list9</td>
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

