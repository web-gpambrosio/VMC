<?php

include("veritas/connectdb.php");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
	
$formname = "formdocuments";
$formtitle = "CREW DOCUMENTS";

$checkneedrank = "";
$checkstatus = "";

//POSTS

if(isset($_POST["doccode"]))
	$doccode=$_POST["doccode"];
else 
{
	$doccode=$_GET["doccode"];
	$doctype = "C";
}

if(isset($_POST['document']))
	$document=$_POST['document'];
	
if(isset($_POST['doctype']))
	$doctype=$_POST['doctype'];
elseif ($_GET["doctype"] == "")
	$doctype = "D";
else 
	$doctype = $_GET["doctype"];

if(isset($_POST['needrank']))
{
	$needrank=1;
	$checkneedrank = "checked=\"checked\"";
}
else 
	$needrank=0;
	
if(isset($_POST['status']))
{
	$status=1;
	$checkstatus = "checked=\"checked\"";
}
else 
	$status=0;
	
if(isset($_POST['hasexpiry']))
{
	$hasexpiry=1;
	$checkexpiry = "checked=\"checked\"";
}
else 
	$hasexpiry=0;


switch ($actiontxt)
{
	case "save"		:
		
			$qryverify = mysql_query("SELECT DOCCODE FROM crewdocuments WHERE DOCCODE='$doccode'") or die(mysql_error());
		
			if(mysql_num_rows($qryverify) == 0)
			{
				$qrycrewdocumentsave = mysql_query("INSERT INTO crewdocuments(DOCCODE,DOCUMENT,TYPE,HASEXPIRY,NEEDRANK,STATUS,MADEBY,MADEDATE) 
										VALUES ('$doccode','$document','$doctype',$hasexpiry,$needrank,$status,'$employeeid','$currentdate')
										") or die(mysql_error());
			}
			else 
			{
				$qrycrewdocumentupdate = mysql_query("UPDATE crewdocuments SET DOCUMENT='$document',
																	TYPE='$doctype',
																	HASEXPIRY=$hasexpiry,
																	NEEDRANK=$needrank,
																	STATUS=$status,
																	MADEBY='$employeeid',
																	MADEDATE='$currentdate'
													WHERE DOCCODE='$doccode'
													") or die(mysql_error());
			}
			
			$doccode = "";
			$document = "";
//			$doctype = "D";
			$needrank = "";
			$status = "";
			
			$checkneedrank = "";
			$checkstatus = "";
			$checkexpiry = "";
			
		break;
		
	case "cancel"	:
		
			$doccode = "";
			$document = "";
//			$doctype = "D";
			$needrank = "";
			$status = "";
			
			$checkneedrank = "";
			$checkstatus = "";
			$checkexpiry = "";
		break;
		
	case "delete"	:
		
		
		break;
}
	
$qrycoursetypesel = mysql_query("SELECT COURSETYPECODE,COURSETYPE FROM coursetype ORDER BY COURSETYPE") or die(mysql_error());

	$typesel = "<option selected value=\"\">--Select One--</option>";
	while($rowcoursetypesel = mysql_fetch_array($qrycoursetypesel))
	{
		$typecode = $rowcoursetypesel["COURSETYPECODE"];
		$typename = $rowcoursetypesel["COURSETYPE"];
		
		$selected3 = "";
		
		if ($typecode == $coursetypecode)
			$selected3 = "SELECTED";
			
		$typesel .= "<option $selected3 value=\"$typecode\">$typename</option>";
	}



/* LISTINGS  */

$qrycrewdocs = mysql_query("SELECT * FROM crewdocuments WHERE TYPE='$doctype' ORDER BY DOCUMENT") or die(mysql_error());


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
			
		<div style=\"width:100%;height:250px;float:right;padding:5px 20px 0 20px;overflow:auto;\">
			<br />
			<span class=\"sectiontitle\">$formtitle</span>
			<br />
			
			<table class=\"setup\" width=\"100%\" >	
				<tr>
					<th>Document Code</th>
					<th>:</th>
					<th><input type=\"text\" name=\"doccode\" value=\"$doccode\" size=\"15\" maxlength=\"10\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Document</th>
					<th>:</th>
					<th><input type=\"text\" name=\"document\" value=\"$document\" size=\"60\" maxlength=\"80\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Document Type</th>
					<th>:</th>
					<th><select name=\"doctype\" onchange=\"submit();\">
							<option value=\"\">--Select One--</option>
					";
						switch($doctype)
						{
							case "D"	:
									$select_D = "SELECTED";
								break;
							case "L"	:
									$select_L = "SELECTED";
								break;
							case "C"	:
									$select_C = "SELECTED";
								break;
						}
				echo "
							<option $select_D value=\"D\">DOCUMENT</option>
							<option $select_L value=\"L\">LICENSE</option>
							<option $select_C value=\"C\">CERTIFICATE</option>
						</select>
					</th>
				</tr>	
				<tr>
					<th>Need Rank?</th>
					<th>:</th>
					<th>
						<input type=\"checkbox\" name=\"needrank\" $checkneedrank />
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
					<th>Has Expiry?</th>
					<th>:</th>
					<th>
						<input type=\"checkbox\" name=\"hasexpiry\" $checkexpiry />
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
			<div style=\"width:100%;height:200px;overflow:auto;\">
				<table width=\"100%\" class=\"listcol\">
					<tr>
						<th>TYPE</th>
						<th>CODE</th>
						<th>DOCUMENT</th>
						<th>NEED RANK</th>
						<th>STATUS</th>
						<th>HAS EXPIRY</th>
						<th>MADE BY</th>
						<th>MADE DATE</th>
					</tr>
	";
				while ($rowcrewdocs=mysql_fetch_array($qrycrewdocs))
				{
					$list1 = $rowcrewdocs["TYPE"];
					$list2 = $rowcrewdocs["DOCCODE"];
					$list3 = addslashes($rowcrewdocs["DOCUMENT"]);
					
					if ($rowcrewdocs["NEEDRANK"] == 0)
						$list4 = "NO";
					else 
						$list4 = "YES";
						
					if ($rowcrewdocs["STATUS"] == 0)
						$list5 = "INACTIVE";
					else 
						$list5 = "ACTIVE";
						
					$list6 = $rowcrewdocs["MADEBY"];
					$list7 = date('m-d-Y',strtotime($rowcrewdocs["MADEDATE"]));
					
					if ($rowcrewdocs["HASEXPIRY"] == 0)
						$list8 = "NO";
					else 
						$list8 = "YES";
					
					echo "
					<tr ondblclick=\"
						document.$formname.doccode.value='$list2';
						document.$formname.document.value='$list3';
						document.$formname.doctype.value='$list1';
						if ('$list4' == 'NO') {document.$formname.needrank.checked='';} else {document.$formname.needrank.checked='checked';}
						if ('$list5' == 'INACTIVE') {document.$formname.status.checked='';} else {document.$formname.status.checked='checked';}
						if ('$list8' == 'NO') {document.$formname.hasexpiry.checked='';} else {document.$formname.hasexpiry.checked='checked';}
						\">
						<td align=\"center\">$list1</td>
						<td align=\"center\">$list2</td>
						<td>$list3</td>
						<td align=\"center\">$list4</td>
						<td align=\"center\">$list5</td>
						<td align=\"center\">$list8</td>
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
</form>

</body>
</html>
";

?>

