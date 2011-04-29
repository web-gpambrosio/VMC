<?php

include("veritas/connectdb.php");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
		
$formname = "formmanning";
$formtitle = "MANNING AGENCY SETUP";

$checkmarquez = "";

//POSTS

if(isset($_POST['manningcode']))
	$manningcode=$_POST['manningcode'];

if(isset($_POST['manning']))
	$manning=$_POST['manning'];

if(isset($_POST['address']))
	$address=$_POST['address'];

if(isset($_POST['municipality']))
	$municipality=$_POST['municipality'];

if(isset($_POST['city']))
	$city=$_POST['city'];

if(isset($_POST['telno']))
	$telno=$_POST['telno'];

if(isset($_POST['faxno']))
	$faxno=$_POST['faxno'];
	
if(isset($_POST['president']))
	$president=$_POST['president'];

if(isset($_POST['manager']))
	$manager=$_POST['manager'];

if(isset($_POST['email']))
	$email=$_POST['email'];

if(isset($_POST['marquez']))
{
	$marquez=1;
	$checkmarquez = "checked=\"checked\"";
}
else 
	$marquez=0;


switch ($actiontxt)
{
	case "save"		:
		
			$qryverify = mysql_query("SELECT MANNINGCODE FROM manning WHERE MANNINGCODE='$manningcode'") or die(mysql_error());
		
			if(mysql_num_rows($qryverify) == 0)
			{
				$qrymanningsave = mysql_query("INSERT INTO manning(MANNINGCODE,MANNING,ALIAS,ADDRESS,MUNICIPALITY,CITY,
												TELNO,FAXNO,EMAIL,PRESIDENT,MANAGER,MARQUEZ,MADEBY,MADEDATE) 
												VALUES('$manningcode','$manning','$address','$municipality','$city','$telno','$faxno','$email',
												'$president','$manager',$marquez,'$employeeid','$currentdate')
											") or die(mysql_error());
			}
			else 
			{
				$qrymanningupdate = mysql_query("UPDATE manning SET MANNING='$manning',
															ALIAS='$alias',
															ADDRESS='$address',
															MUNICIPALITY='$municipality',
															CITY='$city',
															TELNO='$telno',
															FAXNO='$faxno',
															EMAIL='$email',
															PRESIDENT='$president',
															MANAGER='$manager',
															MARQUEZ=$marquez,
															MADEBY='$employeeid',
															MADEDATE='$currentdate'
												 WHERE MANNINGCODE='$manningcode'
											") or die(mysql_error());
			}
			
			$manningcode = "";
			$manning = "";
			$alias = "";
			$address = "";
			$municipality = "";
			$city = "";
			$telno = "";
			$faxno = "";
			$email = "";
			$president = "";
			$manager = "";
			$marquez = 0;

			$checkmarquez = "";		
		break;
		
	case "cancel"	:
		
			$manningcode = "";
			$manning = "";
			$alias = "";
			$address = "";
			$municipality = "";
			$city = "";
			$telno = "";
			$faxno = "";
			$email = "";
			$president = "";
			$manager = "";
			$marquez = 0;
			
			$checkmarquez = "";

		break;
		
	case "delete"	:

		break;
}
	

/* LISTINGS  */

$qrymanning = mysql_query("SELECT * FROM manning") or die(mysql_error());


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
			
		<div style=\"width:100%;height:400px;float:right;padding:5px 20px 0 20px;\">
			<br />
			<span class=\"sectiontitle\">$formtitle</span>
			<br />
			
			<table class=\"setup\" width=\"100%\" >	
				<tr>
					<th>Manning Code</th>
					<th>:</th>
					<th><input type=\"text\" name=\"manningcode\" value=\"$manningcode\" size=\"10\"  onKeyPress=\"return alphanumeric(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Manning</th>
					<th>:</th>
					<th><input type=\"text\" name=\"manning\" value=\"$manning\" size=\"60\" onKeyPress=\"return alphanumeric(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Alias</th>
					<th>:</th>
					<th><input type=\"text\" name=\"alias\" value=\"$alias\" size=\"10\" onKeyPress=\"return alphanumeric(this);\"
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
					<th>Municipality</th>
					<th>:</th>
					<th><input type=\"text\" name=\"municipality\" value=\"$municipality\" size=\"60\" onKeyPress=\"return alphanumeric(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>City</th>
					<th>:</th>
					<th><input type=\"text\" name=\"city\" value=\"$city\" size=\"60\" onKeyPress=\"return alphanumeric(this);\"
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
					<th>President</th>
					<th>:</th>
					<th><input type=\"text\" name=\"president\" value=\"$president\" size=\"20\" onKeyPress=\"return alphanumeric(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Manager</th>
					<th>:</th>
					<th><input type=\"text\" name=\"manager\" value=\"$manager\" size=\"20\" onKeyPress=\"return alphanumeric(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Marquez Owned?</th>
					<th>:</th>
					<th>
						<input type=\"checkbox\" name=\"marquez\" $checkmarquez />
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
			
			<div style=\"width:100%;height:180px;padding:0;overflow:auto;\">
				<table width=\"100%\" class=\"listcol\">
					<tr>
						<th>CODE</th>
						<th>MANNING</th>
						<th>ADDRESS</th>
						<th>TELNO</th>
						<th>FAXNO</th>
						<th>EMAIL</th>
						<th>MADEBY</th>
						<th>MADEDATE</th>
					</tr>
	";
				while ($rowmanning=mysql_fetch_array($qrymanning))
				{
					$list1 = $rowmanning["MANNINGCODE"];
					$list2 = $rowmanning["MANNING"];
					$list2a = addslashes($rowmanning["MANNING"]);
					$list3 = $rowmanning["ALIAS"];
					$list4 = $rowmanning["ADDRESS"];
					$list4a = addslashes($rowmanning["ADDRESS"]);
					$list5 = $rowmanning["MUNICIPALITY"];
					$list6 = $rowmanning["CITY"];
					$list7 = $rowmanning["TELNO"];
					$list8 = $rowmanning["FAXNO"];
					$list9 = $rowmanning["EMAIL"];
					$list10 = $rowmanning["PRESIDENT"];
					$list11 = $rowmanning["MANAGER"];
						
					if ($rowmanning["MARQUEZ"] == 0)
						$list12 = "NO";
					else 
						$list12 = "YES";
						
					$list13 = $rowmanning["MADEBY"];
					$list14 = date('m-d-Y',strtotime($rowmanning["MADEDATE"]));
					
					echo "
					<tr ondblclick=\"
						document.$formname.manningcode.value='$list1';

						document.$formname.municipality.value='$list5';
						document.$formname.city.value='$list6';
						document.$formname.telno.value='$list7';
						document.$formname.faxno.value='$list8';
						document.$formname.email.value='$list9';
						document.$formname.president.value='$list10';
						document.$formname.manager.value='$list11';
						if ('$list12' == 'NO') {document.$formname.marquez.checked='';} else {document.$formname.marquez.checked='checked';}
						\">
						
						<td align=\"center\">$list1</td>
						<td>$list2</td>
						<td>$list4</td>
						<td>$list7</td>
						<td>$list8</td>
						<td>$list9</td>
						<td align=\"center\">$list13</td>
						<td align=\"center\">$list14</td>
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

