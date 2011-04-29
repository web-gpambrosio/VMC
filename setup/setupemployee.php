<?php

include("veritas/connectdb.php");

session_start();

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
	
$formname = "formemployee";
$formtitle = "EMPLOYEE SETUP";

$showpassword = "visibility:hidden;";
$haspassword = 0;
$disablepasswd = "disabled=\"disabled\"";

//POSTS

if (isset($_POST['empfname']))
	$empfname = $_POST['empfname'];
	
if (isset($_POST['empgname']))
	$empgname = $_POST['empgname'];
	
if (isset($_POST['empmname']))
	$empmname = $_POST['empmname'];
	
if (isset($_POST['empdesignation']))
	$empdesignation = $_POST['empdesignation'];
	
if (isset($_POST['empaddress']))
	$empaddress = $_POST['empaddress'];
	
if (isset($_POST['empmunicipality']))
	$empmunicipality = $_POST['empmunicipality'];
	
if (isset($_POST['empcity']))
	$empcity = $_POST['empcity'];
	
if (isset($_POST['empid']))
	$empid = $_POST['empid'];

if (isset($_POST['empidsel']))
	$empidsel = $_POST['empidsel'];

if (isset($_POST['empdivcode']))
	$empdivcode = $_POST['empdivcode'];

if (isset($_POST['empmgmtcode']))
	$empmgmtcode = $_POST['empmgmtcode'];

	
if (isset($_POST['haspassword']))
	$haspassword = $_POST['haspassword'];

if (isset($_POST['confirmnewpassword']))
	$confirmnewpassword = $_POST['confirmnewpassword'];

if (isset($_POST['newpassword']))
	$newpassword = $_POST['newpassword'];

if (isset($_POST['currentpassword']))
	$currentpassword = $_POST['currentpassword'];
	
if (isset($_POST['certrank']))
	$certrank = $_POST['certrank'];
	
if(isset($_POST['certsign']))
{
	$certsign=1;
	$checkcertsign = "checked=\"checked\"";
}
else 
	$certsign=0;
	
switch ($actiontxt)
{
	case "genid"	:
			
			// GET INITIALS
			$empid = substr($empgname,0,1) . substr($empmname,0,1) . substr($empfname,0,1);
			
			$qrycheckid = mysql_query("SELECT EMPLOYEEID FROM employee WHERE EMPLOYEEID = '$empid'") or die(mysql_error());
			
			if (mysql_num_rows($qrycheckid) > 0)
			{
				$qrycheckid2 = mysql_query("SELECT MAX(EMPLOYEEID) AS EMPLOYEEID FROM employee WHERE EMPLOYEEID LIKE '$empid%'") or die(mysql_error());
				$rowcheckid2 = mysql_fetch_array($qrycheckid2);
				
				$idtmp1 = substr($rowcheckid2["EMPLOYEEID"],3,4);
				$idtmp1++;
				
				$empid .= $idtmp1;
			}		
		
		break;
		
	case "save"		:
					
			$qryverify = mysql_query("SELECT EMPLOYEEID FROM employee WHERE EMPLOYEEID = '$empid'") or die(mysql_error());
		
			if(mysql_num_rows($qryverify) == 0)
			{	
				$qryemployeesave = mysql_query("INSERT INTO employee(EMPLOYEEID,DESIGNATIONCODE,FNAME,GNAME,MNAME,ADDRESS,MUNICIPALITY,CITY,DIVCODE,CERTSIGN,CERTRANK,MANAGEMENTCODE)
												VALUES('$empid','$empdesignation','$empfname','$empgname','$empmname','$empaddress',
							 							'$empmunicipality','$empcity','$empdivcode',$certsign,'$certrank','$empmgmtcode')
							 					") or die(mysql_error());
			}
			else 
			{
				$qryemployeeupdate = mysql_query("UPDATE employee SET DESIGNATIONCODE='$empdesignation',
																	FNAME='$empfname',
																	GNAME='$empgname',
																	MNAME='$empmname',
																	ADDRESS='$empaddress',
																	MUNICIPALITY='$empmunicipality',
																	CITY='$empcity',
																	DIVCODE='$empdivcode',
																	CERTSIGN=$certsign,
																	CERTRANK='$certrank',
																	MANAGEMENTCODE='$empmgmtcode'
													WHERE EMPLOYEEID = '$empid'
												") or die(mysql_error());
			}
			
			$empid = "";
			$empfname = "";
			$empgname = "";
			$empmname = "";
			$empaddress = "";
			$empmunicipality = "";
			$empcity = "";
			$empdesignation = "";
			$empdivcode = "";
			
			$checkcertsign = "";
			$certsign=0;
			$empcertrank = "";
			
		break;
		
	case "update"	:
		
			$qryemployeeinfo = mysql_query("SELECT EMPLOYEEID,FNAME,GNAME,MNAME,PASSWORD,
							e.DESIGNATIONCODE,d.DESIGNATIONCODE,dv.DIVCODE,dv.DIVISION,
							d.DESIGNATION,ADDRESS,MUNICIPALITY,CITY,CERTSIGN,CERTRANK
							FROM employee e
							LEFT JOIN designation d ON d.DESIGNATIONCODE=e.DESIGNATIONCODE
							LEFT JOIN division dv ON dv.DIVCODE=e.DIVCODE
							WHERE e.EMPLOYEEID='$empidsel'
							") or die(mysql_error());
			
			if (mysql_num_rows($qryemployeeinfo) == 1)
			{
				$rowemployeeinfo = mysql_fetch_array($qryemployeeinfo);
				
				$empfname = $rowemployeeinfo["FNAME"];
				$empgname = $rowemployeeinfo["GNAME"];
				$empmname = $rowemployeeinfo["MNAME"];
				$empdivcode = $rowemployeeinfo["DIVCODE"];
				$empdesignation = $rowemployeeinfo["DESIGNATIONCODE"];
				$empaddress = $rowemployeeinfo["ADDRESS"];
				$empmunicipality = $rowemployeeinfo["MUNICIPALITY"];
				$empcity = $rowemployeeinfo["CITY"];
				$empcertrank = $rowemployeeinfo["CERTRANK"];
				
				if ($rowemployeeinfo["CERTSIGN"] == 1)
				{
					$certsign=1;
					$checkcertsign = "checked=\"checked\"";
				}
				else 
				{
					$certsign=0;
					$checkcertsign = "";
				}
				
				$empid = $empidsel;
				
				if ($rowemployeeinfo["PASSWORD"] != "")
					$haspassword = 1;
				else 
					$haspassword = 0;
					
				$disablepasswd = "";
				
			}
		
		break;
		
	case "cancel"	:
					
			$empid = "";
			$empidsel = "";
			$empfname = "";
			$empgname = "";
			$empmname = "";
			$empaddress = "";
			$empmunicipality = "";
			$empcity = "";
			$empdesignation = "";
			$empdivcode = "";
			$haspassword = 0;
			
			$checkcertsign = "";
			$certsign=0;
			$empcertrank = "";
		
		break;
		
	case "confirmpassword":
		
			if ($haspassword)  //MODIFY PASSWORD
			{
				$qrygetpassword = mysql_query("SELECT PASSWORD FROM employee WHERE EMPLOYEEID='$empid'") or die(mysql_error());
				
				if (mysql_num_rows($qrygetpassword) > 0)
				{
					$rowgetpassword = mysql_fetch_array($qrygetpassword);
					$passwd = $rowgetpassword["PASSWORD"];
					
					//check CURRENT PASSWORD
					if (sha1($currentpassword) === $passwd)
					{
						if ($newpassword == $confirmnewpassword)
						{
							$finalpasswd = sha1($newpassword);
							$qrypasswordupdate = mysql_query("UPDATE employee SET PASSWORD='$finalpasswd' WHERE EMPLOYEEID='$empid'") or die(mysql_error());
							
							$passworderror = "New Password Saved!";
						}
						else 
							$passworderror = "New Password confirmation failed. Please try again.";
					}
					else 
						$passworderror = "Current password does not match password stored in database. Please try again.";
				}
				
			}
			else  //CREATE PASSWORD
			{
				if ($newpassword == $confirmnewpassword)
				{
					$finalpasswd = sha1($newpassword);
					$qrypasswordupdate = mysql_query("UPDATE employee SET PASSWORD='$finalpasswd' WHERE EMPLOYEEID='$empid'") or die(mysql_error());
					
					$passworderror = "New Password Saved!";
				}
				else 
					$passworderror = "New Password confirmation failed. Please try again.";
			}
		
			$showpassword = "visibility:show;";
		
		break;
		
	case "showpassword":
		
			$showpassword = "visibility:show;";
			$disablepasswd = "disabled=\"disabled\"";
		
		break;
		
	case "closepassword":
		
			$showpassword = "visibility:hidden;";
			$disablepasswd = "";
		
		break;
}
	

/* LISTINGS  */

$qrydesignationsel = mysql_query("SELECT DESIGNATIONCODE,DESIGNATION FROM designation") or die(mysql_error());

	$empselect1 = "<option selected value=\"\">--Select One--</option>";
	while($rowdesignationsel = mysql_fetch_array($qrydesignationsel))
	{
		$descode = $rowdesignationsel["DESIGNATIONCODE"];
		$designation = $rowdesignationsel["DESIGNATION"];
		
		$selected1 = "";
		
		if ($empdesignation == $descode)
			$selected1 = "SELECTED";
			
		$empselect1 .= "<option $selected1 value=\"$descode\">$designation</option>";
	}
	
$qrydivisionsel = mysql_query("SELECT DIVCODE,DIVISION FROM division") or die(mysql_error());

	$empselect2 = "<option selected value=\"\">--Select One--</option>";
	while($rowdivisionsel = mysql_fetch_array($qrydivisionsel))
	{
		$divcode = $rowdivisionsel["DIVCODE"];
		$division = $rowdivisionsel["DIVISION"];
		
		$selected2 = "";
		
		if ($empdivcode == $divcode)
			$selected2 = "SELECTED";
			
		$empselect2 .= "<option $selected2 value=\"$divcode\">$division</option>";
	}

$qrymanagementsel = mysql_query("SELECT MANAGEMENTCODE,MANAGEMENT FROM management") or die(mysql_error());

	$empselect3 = "<option selected value=\"\">--Select One--</option>";
	while($rowmanagementsel = mysql_fetch_array($qrymanagementsel))
	{
		$mgmtcode = $rowmanagementsel["MANAGEMENTCODE"];
		$management = $rowmanagementsel["MANAGEMENT"];
		
		$selected3 = "";
		
		if ($empmgmtcode == $mgmtcode)
			$selected3 = "SELECTED";
			
		$empselect3 .= "<option $selected3 value=\"$mgmtcode\">$management</option>";
	}
	
$qryemployee = mysql_query("SELECT EMPLOYEEID,FNAME,GNAME,MNAME,e.CERTSIGN,e.CERTRANK,
							e.DESIGNATIONCODE,d.DESIGNATIONCODE,dv.DIVCODE,dv.DIVISION,
							d.DESIGNATION,ADDRESS,MUNICIPALITY,CITY
							FROM employee e
							LEFT JOIN designation d ON d.DESIGNATIONCODE=e.DESIGNATIONCODE
							LEFT JOIN division dv ON dv.DIVCODE=e.DIVCODE
							ORDER BY FNAME
							") or die(mysql_error());

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
		if(empid.value=='' || empid.value==null)
			if(rem=='')
				rem='Employee ID';
			else
				rem=rem + ',Employee ID';	
		if(empdesignation.value=='' || empdesignation.value==null)
			if(rem=='')
				rem='Designation';
			else
				rem=rem + ',Designation';
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
	
<div style=\"position:absolute;top:300px;left:100px;height:250px;width:450px;background-color:#6699FF;
				border:2px solid black;overflow:auto;$showpassword \">
	<span class=\"sectiontitle\">PASSWORD MANIPULATION</span>
	
	<center>
	<br />
	<table width=\"75%\" style=\"font-size:0.8em;font-weight:Bold;\">
";
		if ($haspassword == 1)
		{
			echo "
			<tr>
				<td>Current Password</td>
				<td>:</td>
				<td><input type=\"password\" name=\"currentpassword\" size=20 maxlength=20 /></td>
			</tr>
			";
		}

echo "
		<tr>
			<td>New Password</td>
			<td>:</td>
			<td><input type=\"password\" name=\"newpassword\" size=20 maxlength=20 /></td>
		</tr>
		<tr>
			<td>Confirm Password</td>
			<td>:</td>
			<td><input type=\"password\" name=\"confirmnewpassword\" size=20 maxlength=20 /></td>
		</tr>
		<tr>
			<td colspan=\"3\">&nbsp;</td>
		</tr>
		<tr>
			<td colspan=\"3\" align=\"center\">
				<input type=\"button\" value=\"Create/Modify Password\" onclick=\"actiontxt.value='confirmpassword';submit();\" />&nbsp;
				<input type=\"button\" value=\"Close Window\" onclick=\"actiontxt.value='closepassword';submit();\" />
			</td>
		</tr>
		<tr>
			<td colspan=\"3\">&nbsp;</td>
		</tr>
		<tr>
			<td colspan=\"3\" align=\"center\">
				<span style=\"color:Red;\">$passworderror</span>
			</td>
		</tr>
	</table>
	</center>
	
</div>

	<center>
	<div style=\"width:100%;height:600px;float:right;padding:1px 20px 0 20px;overflow:hidden;\">
			
		<div style=\"width:100%;height:460px;float:right;padding:5px 20px 0 20px;overflow:hidden;\">
			<span class=\"sectiontitle\">$formtitle</span>
			<table class=\"setup\" width=\"100%\" >	
				<tr>
					<th>Family Name</th>
					<th>:</th>
					<th><input type=\"text\" name=\"empfname\" value=\"$empfname\" size=\"30\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Given Name</th>
					<th>:</th>
					<th><input type=\"text\" name=\"empgname\" value=\"$empgname\" size=\"30\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Middle Name</th>
					<th>:</th>
					<th><input type=\"text\" name=\"empmname\" value=\"$empmname\" size=\"30\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Designation</th>
					<th>:</th>
					<th><select name=\"empdesignation\">
							$empselect1
						</select>
					</th>
				</tr>
				<tr>
					<th>Address</th>
					<th>:</th>
					<th><input type=\"text\" name=\"empaddress\" value=\"$empaddress\" size=\"30\" 
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Municipality</th>
					<th>:</th>
					<th><input type=\"text\" name=\"empmunicipality\" value=\"$empmunicipality\" size=\"30\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>City</th>
					<th>:</th>
					<th><input type=\"text\" name=\"empcity\" value=\"$empcity\" size=\"20\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" 
					</th>
				</tr>
				<tr>
					<th>Division</th>
					<th>:</th>
					<th><select name=\"empdivcode\">
							$empselect2
						</select>
					</th>
				</tr>
				<tr>
					<th>Management Code</th>
					<th>:</th>
					<th><select name=\"empmgmtcode\">
							$empselect3
						</select>
					</th>
				</tr>
				<tr>
					<th>Certificate Sign?</th>
					<th>:</th>
					<th>
						<input type=\"checkbox\" name=\"certsign\" $checkcertsign />
					</th>
				</tr>
				<tr>
					<th>Certificate Prefix</th>
					<th>:</th>
					<th><input type=\"text\" name=\"certrank\" value=\"$empcertrank\" size=\"20\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" 
					</th>
				</tr>
				<tr>
					<th>Employee ID</th>
					<th>:</th>
					<th><input type=\"text\" name=\"empid\" value=\"$empid\" size=\"10\" readonly=\"readonly\" />&nbsp;&nbsp;
						<input type=\"button\" name=\"\" value=\"Generate ID\" onclick=\"actiontxt.value='genid';$formname.submit();\" />
					</th>
				</tr>

				<tr>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
				</tr>
				<tr>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
					<th><input type=\"button\" value=\"Save\" onclick=\"actiontxt.value='save';checksave();\" />
						<input type=\"button\" value=\"Cancel\" onclick=\"actiontxt.value='cancel';$formname.submit();\" />
						<input type=\"button\" value=\"Create/Modify Password\" $disablepasswd onclick=\"empidsel.value='$empid';actiontxt.value='showpassword';submit();\" />
					</th>
				</tr>
			</table>
		</div>
		
		<div style=\"width:100%;height:180px;padding:5px 20px 0 20px;overflow:hidden;\">
			<span class=\"sectiontitle\">CURRENT LISTING</span>
			<br />
			
			<div style=\"width:100%;height:120px;padding:0;overflow:auto;\">
				<table width=\"100%\" class=\"listcol\">
					<tr>
						<th>ID</th>
						<th>FNAME</th>
						<th>GNAME</th>
						<th>MNAME</th>
						<th>DIVISION</th>
						<th>DESIGNATION</th>
						<th>CERT SIGN</th>
						<th>CERT RANK</th>
					</tr>
	";
				while ($rowemployee=mysql_fetch_array($qryemployee))
				{
					$list1 = $rowemployee["EMPLOYEEID"];
					$list2 = $rowemployee["FNAME"];
					$list3 = $rowemployee["GNAME"];
					$list4 = $rowemployee["MNAME"];
					$list5 = $rowemployee["DIVCODE"];
					$list6 = $rowemployee["DESIGNATION"];
					$list7 = $rowemployee["DESIGNATIONCODE"];
					$list8 = $rowemployee["ADDRESS"];
					$list9 = $rowemployee["MUNICIPALITY"];
					$list10 = $rowemployee["CITY"];
					if ($rowemployee["CERTSIGN"] == 0)
						$list11 = "NO";
					else 
						$list11 = "YES";
					$list12 = $rowemployee["CERTRANK"];
					
					echo "
					<tr ondblclick=\"empidsel.value='$list1';actiontxt.value='update';submit();
						\">
						
						<td>&nbsp;$list1</td>
						<td>&nbsp;$list2</td>
						<td>&nbsp;$list3</td>
						<td>&nbsp;$list4</td>
						<td>&nbsp;$list5</td>
						<td>&nbsp;$list6</td>
						<td>&nbsp;$list11</td>
						<td>&nbsp;$list12</td>
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
	<input type=\"hidden\" name=\"empidsel\" />
	<input type=\"hidden\" name=\"haspassword\" value=\"$haspassword\"/>
</form>

</body>
</html>
";

?>

