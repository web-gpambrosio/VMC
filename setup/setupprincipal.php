<?php

include("veritas/connectdb.php");

session_start();

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
	
$formname = "formprincipal";
$formtitle = "PRINCIPAL SETUP";


$checkvmc = "";
$checkstatus = "";	

//POSTS

if (isset($_POST['principalcode']))
	$principalcode = $_POST['principalcode'];
	
if (isset($_POST['principal']))
	$principal = $_POST['principal'];
	
if (isset($_POST['alias']))
	$alias = $_POST['alias'];
	
if (isset($_POST['principalid']))
	$principalid = $_POST['principalid'];
	
if (isset($_POST['$accreditno']))
	$$accreditno = $_POST['$accreditno'];
	
if (isset($_POST['$accreditdate']))
	$$accreditdate = $_POST['$accreditdate'];
	
if (isset($_POST['accreditexpiry']))
	$accreditexpiry = $_POST['accreditexpiry'];
	
if (isset($_POST['address1']))
	$address1 = $_POST['address1'];
	
if (isset($_POST['address2']))
	$address2 = $_POST['address2'];
	
if (isset($_POST['address3']))
	$address3 = $_POST['address3'];
	
if (isset($_POST['countrycode']))
	$countrycode = $_POST['countrycode'];
	
if (isset($_POST['telno']))
	$telno = $_POST['telno'];
	
if (isset($_POST['faxno']))
	$faxno = $_POST['faxno'];
	
if (isset($_POST['telexno']))
	$telexno = $_POST['telexno'];
	
if (isset($_POST['incharge']))
	$incharge = $_POST['incharge'];
	
if(isset($_POST['vmc']))
{
	$vmc=1;
	$checkvmc = "checked=\"checked\"";
}
else 
	$vmc=0;
	
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
		
			$qryverify = mysql_query("SELECT PRINCIPALCODE FROM principal WHERE PRINCIPALCODE='$principalcode'") or die(mysql_error());
		
			if(mysql_num_rows($qryverify) == 0)
			{
				$qryprincipalsave = mysql_query("INSERT INTO principal(PRINCIPALCODE,PRINCIPAL,PRINCIPALID,ALIAS,ACCREDITNO,
													ACCREDIT_DATE,ACCREDIT_EXPIRY,ADDR1,ADDR2,ADDR3,COUNTRYCODE,TELNO,FAXNO,TELEXNO,
													INCHARGE,STATUS,VMC,MADEBY,MADEDATE) 
													VALUES('$principalcode','$principal','$principalid','$alias','$accreditno',
													'$accreditdate','$accreditexpiry','$address1','$address2','$address3',
													'$countrycode','$telno','$faxno','$telexno','$incharge',$status,$vmc,
													'$employeeid','$currentdate')
												") or die(mysql_error());
			}
			else 
			{
				$qryprincipalupdate = mysql_query("UPDATE principal SET PRINCIPAL='$principal',
																	PRINCIPALID='$principalid',
																	ALIAS='$alias',
																	ACCREDITNO='$accreditno',
																	ACCREDIT_DATE='$accreditdate',
																	ACCREDIT_EXPIRY='$accreditexpiry',
																	ADDR1='$address1',
																	ADDR2='$address2',
																	ADDR3='$address3',
																	COUNTRYCODE='$countrycode',
																	TELNO='$telno',
																	FAXNO='$faxno',
																	TELEXNO='$telexno',
																	INCHARGE='$incharge',
																	STATUS=$status,
																	VMC=$vmc,
																	MADEBY='$employeeid',
																	MADEDATE='$currentdate'
													WHERE PRINCIPALCODE='$principalcode'
												") or die(mysql_error());
			}
			
			$principalcode = "";
			$principal = "";
			$principalid = "";
			$alias = "";
			$accreditno = "";
			$accreditdate = "";
			$accreditexpiry = "";
			$address1 = "";
			$address2 = "";
			$address3 = "";
			$countrycode = "";
			$telno = "";
			$faxno = "";
			$telexno = "";
			$incharge = "";
			
			$checkvmc = "";
			$checkstatus = "";	
			
		break;
		
	case "cancel"	:
		
			$principalcode = "";
			$principal = "";
			$principalid = "";
			$alias = "";
			$accreditno = "";
			$accreditdate = "";
			$accreditexpiry = "";
			$address1 = "";
			$address2 = "";
			$address3 = "";
			$countrycode = "";
			$telno = "";
			$faxno = "";
			$telexno = "";
			$incharge = "";
			
			$checkvmc = "";
			$checkstatus = "";	

		break;
}
	

/* LISTINGS  */

$qrycountrysel = mysql_query("SELECT COUNTRYCODE,COUNTRY FROM country") or die(mysql_error());

	$select1 = "<option selected value=\"\">--Select One--</option>";
	while($rowcountrysel = mysql_fetch_array($qrycountrysel))
	{
		$countrycode1 = $rowcountrysel["COUNTRYCODE"];
		$country = $rowcountrysel["COUNTRY"];
		
		$selected1 = "";
		
		if ($countrycode == $countrycode1)
			$selected1 = "SELECTED";
			
		$select1 .= "<option $selected1 value=\"$countrycode1\">$country</option>";
	}

	
$qryprincipal = mysql_query("SELECT * FROM principal") or die(mysql_error());

/*END OF LISTINGS*/


echo "
<html>
<head>
<link rel=\"stylesheet\" type=\"text/css\" href=\"../veripro.css\">
<script language=\"javascript\" src=\"../veripro.js\"></script>
<script language=\"javascript\" src=\"popcalendar.js\"></script>	

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
			
		<div style=\"width:100%;height:560px;float:right;padding:5px 20px 0 20px;overflow:auto;\">
			<br />
			<span class=\"sectiontitle\">$formtitle</span>
			<br />
			
			<table class=\"setup\" width=\"100%\" >	
				<tr>
					<th>Principal Code</th>
					<th>:</th>
					<th><input type=\"text\" name=\"principalcode\" value=\"$principalcode\" size=\"30\" onKeyPress=\"return alphaonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Principal Name</th>
					<th>:</th>
					<th><input type=\"text\" name=\"principal\" value=\"$principal\" size=\"30\" onKeyPress=\"return alphaonly(this);\"
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
					<th>Principal ID</th>
					<th>:</th>
					<th><input type=\"text\" name=\"principalid\" value=\"$principalid\" size=\"30\" onKeyPress=\"return alphaonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Accreditation No.</th>
					<th>:</th>
					<th><input type=\"text\" name=\"accreditno\" value=\"$accreditno\" size=\"30\" onKeyPress=\"return alphaonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Date Accredited</th>
					<th>:</th>
					<th>
						<input type=\"text\" name=\"accreditdate\" value=\"$accreditdate\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
						<img src=\"../calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(accreditdate, accreditdate, 'mm/dd/yyyy', 0, 0);return false;\">
					</th>
				</tr>
				<tr>
					<th>Date Expiry</th>
					<th>:</th>
					<th>
						<input type=\"text\" name=\"accreditexpiry\" value=\"$accreditexpiry\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
						<img src=\"../calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(accreditexpiry, accreditexpiry, 'mm/dd/yyyy', 0, 0);return false;\">
					</th>
				</tr>
				<tr>
					<th>Address 1</th>
					<th>:</th>
					<th><input type=\"text\" name=\"address1\" value=\"$address1\" size=\"50\" maxlength=\"45\" onKeyPress=\"return remarksonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Address 2</th>
					<th>:</th>
					<th><input type=\"text\" name=\"address2\" value=\"$address2\" size=\"50\" maxlength=\"45\" onKeyPress=\"return remarksonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Address 3</th>
					<th>:</th>
					<th><input type=\"text\" name=\"address3\" value=\"$address3\" size=\"50\" maxlength=\"45\" onKeyPress=\"return remarksonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Country</th>
					<th>:</th>
					<th><select name=\"countrycode\">
							$select1
						</select>
					</th>
				</tr>
				<tr>
					<th>Tel. No.</th>
					<th>:</th>
					<th><input type=\"text\" name=\"telno\" value=\"$telno\" size=\"30\" maxlength=\"30\" onKeyPress=\"return remarksonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Fax No.</th>
					<th>:</th>
					<th><input type=\"text\" name=\"faxno\" value=\"$faxno\" size=\"30\" maxlength=\"30\" onKeyPress=\"return remarksonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Telex No.</th>
					<th>:</th>
					<th><input type=\"text\" name=\"telexno\" value=\"$telexno\" size=\"30\" maxlength=\"30\" onKeyPress=\"return remarksonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>In-Charge</th>
					<th>:</th>
					<th><input type=\"text\" name=\"incharge\" value=\"$incharge\" size=\"50\" maxlength=\"45\" onKeyPress=\"return remarksonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>VMC Controlled ?</th>
					<th>:</th>
					<th>
						<input type=\"checkbox\" name=\"vmc\" $checkvmc />
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
			
			<div style=\"width:100%;height:250px;padding:0;overflow:auto;\">
				<table width=\"100%\" class=\"listcol\">
					<tr>
						<th>CODE</th>
						<th>PRINCIPAL</th>
						<th>ID</th>
						<th>ALIAS</th>
						<th>ACCREDITNO</th>
						<th>TELNO</th>
						<th>INCHARGE</th>
						<th>VMC</th>
						<th>STATUS</th>
					</tr>
	";
				while ($rowprincipal=mysql_fetch_array($qryprincipal))
				{
					$list1 = $rowprincipal["PRINCIPALCODE"];
					$list2 = $rowprincipal["PRINCIPAL"];
					$list3 = $rowprincipal["PRINCIPALID"];
					$list4 = $rowprincipal["ALIAS"];
					$list5 = $rowprincipal["ACCREDITNO"];
					$list6 = $rowprincipal["ACCREDIT_DATE"];
					$list7 = $rowprincipal["ACCREDIT_EXPIRY"];
					$list8 = $rowprincipal["TELNO"];
					$list9 = $rowprincipal["INCHARGE"];
					
					if ($rowprincipal["VMC"] == 0)
						$list10 = "NO";
					else 
						$list10 = "YES";
						
					if ($rowprincipal["STATUS"] == 0)
						$list11 = "INACTIVE";
					else 
						$list11 = "ACTIVE";
						
					$list12 = $rowprincipal["MADEBY"];
					$list13 = date('m-d-Y',strtotime($rowprincipal["MADEDATE"]));
					
					$list14 = $rowprincipal["ADDRESS1"];
					$list15 = $rowprincipal["ADDRESS2"];
					$list16 = $rowprincipal["ADDRESS3"];
					$list17 = $rowprincipal["TELEXNO"];
					$list18 = $rowprincipal["FAXNO"];
					$list19 = $rowprincipal["COUNTRYCODE"];
					
					echo "
					<tr ondblclick=\"
						document.$formname.principalcode.value='$list1';
						document.$formname.principal.value='$list2';
						document.$formname.principalid.value='$list3';
						document.$formname.alias.value='$list4';
						document.$formname.accreditno.value='$list5';
						document.$formname.accreditdate.value='$list6';
						document.$formname.accreditexpiry.value='$list7';
						document.$formname.address1.value='$list14';
						document.$formname.address2.value='$list15';
						document.$formname.address3.value='$list16';
						document.$formname.telno.value='$list8';
						document.$formname.faxno.value='$list18';
						document.$formname.telexno.value='$list17';
						if ('$list10' == 'NO') {document.$formname.vmc.checked='';} else {document.$formname.vmc.checked='checked';}
						if ('$list11' == 'INACTIVE') {document.$formname.status.checked='';} else {document.$formname.status.checked='checked';}
						\">
						<td align=\"center\">$list1</td>
						<td>$list2</td>
						<td align=\"center\">$list3</td>
						<td align=\"center\">$list4</td>
						<td align=\"center\">$list5</td>
						<td>$list8</td>
						<td>$list9</td>
						<td align=\"center\">$list10</td>
						<td align=\"center\">$list11</td>
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

