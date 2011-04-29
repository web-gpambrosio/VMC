<?php

include("veritas/connectdb.php");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
		
$formname = "formtraincenter";
$formtitle = "TRAINING CENTERS SETUP";

$checkstatus = "";

//POSTS

if(isset($_POST['trainingcentercode']))
	$trainingcentercode=$_POST['trainingcentercode'];

if(isset($_POST['trainingcenter']))
	$trainingcenter=$_POST['trainingcenter'];

if(isset($_POST['address']))
	$address=$_POST['address'];

if(isset($_POST['telno']))
	$telno=$_POST['telno'];

if(isset($_POST['faxno']))
	$faxno=$_POST['faxno'];
	
if(isset($_POST['contactperson']))
	$contactperson=$_POST['contactperson'];

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
		
			$qryverify = mysql_query("SELECT TRAINCENTERCODE FROM trainingcenter WHERE TRAINCENTERCODE='$trainingcentercode'") or die(mysql_error());
		
			if(mysql_num_rows($qryverify) == 0)
			{
				$qrytrainingcentersave = mysql_query("INSERT INTO trainingcenter(TRAINCENTERCODE,TRAINCENTER,ADDRESS,
												TELNO,FAXNO,EMAIL,CONTACTPERSON,STATUS,MADEBY,MADEDATE) 
												VALUES('$trainingcentercode','$trainingcenter','$address','$telno','$faxno','$email',
												'$contactperson',$status,'$employeeid','$currentdate')
											") or die(mysql_error());
			}
			else 
			{
				$qrytrainingcenterupdate = mysql_query("UPDATE trainingcenter SET TRAINCENTER='$trainingcenter',
															ADDRESS='$address',
															TELNO='$telno',
															FAXNO='$faxno',
															EMAIL='$email',
															CONTACTPERSON='$contactperson',
															STATUS=$status,
															MADEBY='$employeeid',
															MADEDATE='$currentdate'
												 WHERE TRAINCENTERCODE='$trainingcentercode'
											") or die(mysql_error());
			}
			
			$trainingcentercode = "";
			$trainingcenter = "";
			$address = "";
			$telno = "";
			$faxno = "";
			$email = "";
			$contactperson = "";
			$status = 0;

			$checkstatus = "";		
		break;
		
	case "cancel"	:
		
			$trainingcentercode = "";
			$trainingcenter = "";
			$address = "";
			$telno = "";
			$faxno = "";
			$email = "";
			$contactperson = "";
			$status = 0;

			$checkstatus = "";	

		break;
		
	case "delete"	:

		break;
}
	

/* LISTINGS  */

$qrytrainingcenter = mysql_query("SELECT * FROM trainingcenter") or die(mysql_error());


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
			
		<div style=\"width:100%;height:300px;float:right;padding:5px 20px 0 20px;\">
			<br />
			<span class=\"sectiontitle\">$formtitle</span>
			<br />
			
			<table class=\"setup\" width=\"100%\" >	
				<tr>
					<th>Training Center Code</th>
					<th>:</th>
					<th><input type=\"text\" name=\"trainingcentercode\" value=\"$trainingcentercode\" size=\"10\"  onKeyPress=\"return alphanumeric(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Training Center</th>
					<th>:</th>
					<th><input type=\"text\" name=\"trainingcenter\" value=\"$trainingcenter\" size=\"60\" onKeyPress=\"return alphanumeric(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Address</th>
					<th>:</th>
					<th><input type=\"text\" name=\"address\" value=\"$address\" size=\"60\" onKeyPress=\"return alphanumeric(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Tel. No.</th>
					<th>:</th>
					<th><input type=\"text\" name=\"telno\" value=\"$telno\" size=\"20\" onKeyPress=\"return alphanumeric(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Fax No.</th>
					<th>:</th>
					<th><input type=\"text\" name=\"faxno\" value=\"$faxno\" size=\"20\" onKeyPress=\"return alphanumeric(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Email</th>
					<th>:</th>
					<th><input type=\"text\" name=\"email\" value=\"$email\" size=\"20\" onKeyPress=\"return alphanumeric(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Contact Person</th>
					<th>:</th>
					<th><input type=\"text\" name=\"contactperson\" value=\"$contactperson\" size=\"20\" onKeyPress=\"return alphanumeric(this);\"
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
		
		<div style=\"width:100%;height:230px;padding:5px 20px 0 20px;overflow:hidden;\">

			<span class=\"sectiontitle\">CURRENT LISTING</span>
			<br />
			
			<div style=\"width:100%;height:200px;padding:0;overflow:auto;\">
				<table width=\"100%\" class=\"listcol\">
					<tr>
						<th>CODE</th>
						<th>TRAININGCENTER</th>
						<th>ADDRESS</th>
						<th>TELNO</th>
						<th>FAXNO</th>
						<th>EMAIL</th>
						<th>CONTACTPERSON</th>
						<th>MADEBY</th>
						<th>MADEDATE</th>
					</tr>
	";
				while ($rowtrainingcenter=mysql_fetch_array($qrytrainingcenter))
				{
					$list1 = $rowtrainingcenter["TRAINCENTERCODE"];
					if ($rowtrainingcenter["TRAINCENTER"])
						$list2 = $rowtrainingcenter["TRAINCENTER"];
					else 
						$list2 = "";
					$list3 = $rowtrainingcenter["ADDRESS"];
					$list4 = $rowtrainingcenter["TELNO"];
					$list5 = $rowtrainingcenter["FAXNO"];
					$list6 = $rowtrainingcenter["EMAIL"];
					$list7 = $rowtrainingcenter["CONTACTPERSON"];
						
					if ($rowtrainingcenter["STATUS"] == 0)
						$list8 = "INACTIVE";
					else 
						$list8 = "ACTIVE";
						
					$list9 = $rowtrainingcenter["MADEBY"];
					$list10 = date('m-d-Y',strtotime($rowtrainingcenter["MADEDATE"]));
					
					echo "
					<tr ondblclick=\"
						document.$formname.trainingcentercode.value='$list1';
						document.$formname.trainingcenter.value='$list2';
						document.$formname.address.value='$list3';
						document.$formname.telno.value='$list4';
						document.$formname.faxno.value='$list5';
						document.$formname.email.value='$list6';
						document.$formname.contactperson.value='$list7';
						if ('$list8' == 'INACTIVE') {document.$formname.status.checked='';} else {document.$formname.status.checked='checked';}
						\">
						
						<td align=\"center\">$list1</td>
						<td>$list2</td>
						<td>$list3</td>
						<td>$list4</td>
						<td>$list5</td>
						<td>$list6</td>
						<td>$list7</td>
						<td>$list8</td>
						<td align=\"center\">$list9</td>
						<td align=\"center\">$list10</td>
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

