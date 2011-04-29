<?php

include("veritas/connectdb.php");
include("veritas/include/stylephp.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
	
$formname = "formsetupdtl";
$formtitle = "SETUP DETAIL SETUP";

	

//POSTS

if (isset($_POST['setupid']))
	$setupid = $_POST['setupid'];
else 
	$setupid = 1;
	
if (isset($_POST['subsetup']))
	$subsetup = $_POST['subsetup'];
	
if (isset($_POST['subsetupid']))
	$subsetupid = $_POST['subsetupid'];
	
if (isset($_POST['phpfile']))
	$phpfile = $_POST['phpfile'];

if (isset($_POST['subposition']))
	$subposition = $_POST['subposition'];


switch ($actiontxt)
{
	case "save"		:
			
			$qryverify = mysql_query("SELECT SUBSETUPID FROM setupdtl WHERE SETUPID=$setupid AND SUBSETUP='$subsetup'") or die(mysql_error());
		
			if(mysql_num_rows($qryverify) == 0)
			{		
				
				$qrysetupdtlsave = mysql_query("INSERT INTO setupdtl(SETUPID,SUBSETUP,PHPFILE,SUBPOSITION,MADEBY,MADEDATE)
												VALUES('$setupid','$subsetup','$phpfile',$subposition,'$employeeid','$currentdate')
												") or die(mysql_error());
			}
			else 
			{
				$qrysetupdtlupdate = mysql_query("UPDATE setupdtl SET SUBSETUP='$subsetup',
																		PHPFILE='$phpfile',
																		SUBPOSITION=$subposition,
																		MADEBY='$employeeid',
																		MADEDATE='$currentdate'
													WHERE SETUPID=$setupid AND SUBSETUPID=$subsetupid
													") or die(mysql_error());
			}
			
			$subsetup = "";
			$phpfile = "";
			$subposition = "";
		break;
		
	case "cancel"	:
		
			$subsetup = "";
			$phpfile = "";
			$subposition = "";

		break;
}
	

/* LISTINGS  */

$qrysetuphdrlist = mysql_query("SELECT SETUPID,SETUP FROM setuphdr") or die(mysql_error());
$hdrselect = "<option value=\"\">--Select One--</option>";
$selected = "";
while ($rowsetuphdrlist = mysql_fetch_array($qrysetuphdrlist))
{
	$sid = $rowsetuphdrlist["SETUPID"];
	$set = $rowsetuphdrlist["SETUP"];
	
	if ($sid == $setupid)
		$selected = "SELECTED";
	else 
		$selected = "";
	
	$hdrselect .= "<option $selected value=\"$sid\">$set&nbsp;($sid)</option>";
}


if (!empty($setupid))
	$qrysetupdtllist = mysql_query("SELECT * FROM setupdtl WHERE SETUPID=$setupid ORDER BY SUBPOSITION") or die(mysql_error());

/*END OF LISTINGS*/


echo "
<html>
<head>
<link rel=\"stylesheet\" type=\"text/css\" href=\"../veripro.css\">
<script language=\"javascript\" src=\"../veripro.js\"></script>

</head>
<body>

<form name=\"$formname\" method=\"POST\">
	
	<center>
	<div style=\"width:100%;height:500px;padding:5px 20px 0 20px;overflow:hidden;\">
			
		<div style=\"width:100%;height:200px;padding:5px 20px 0 20px;overflow:hidden;\">
			<br />
			<span class=\"sectiontitle\">$formtitle</span>
			<br />
			
			<table class=\"setup\" width=\"100%\" >	
				<tr>
					<th>Setup Header</th>
					<th>:</th>
					<th><select name=\"setupid\" onchange=\"submit();\">
							$hdrselect
						</select>
					
					</th>
				</tr>
				<tr>
					<th>Sub Setup Title</th>
					<th>:</th>
					<th><input type=\"text\" name=\"subsetup\" value=\"$subsetup\" size=\"30\" onKeyPress=\"return remarksonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Position</th>
					<th>:</th>
					<th><input type=\"text\" name=\"subposition\" value=\"$subposition\" size=\"5\" onKeyPress=\"return numbersonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>PHP File</th>
					<th>:</th>
					<th><input type=\"text\" name=\"phpfile\" value=\"$phpfile\" size=\"25\" onKeyPress=\"return remarksonly(this);\" />
					</th>
				</tr>
				<tr>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
					<th><input type=\"button\" name=\"\" value=\"Save\" onclick=\"actiontxt.value='save';$formname.submit();\" />
						<input type=\"button\" name=\"\" value=\"Cancel\" onclick=\"actiontxt.value='cancel';$formname.submit();\" />
					</th>
				</tr>
			</table>
		</div>
		
		<div style=\"width:100%;height:325px;padding:5px 20px 0 20px;overflow:hidden;\">
			<br />
			<span class=\"sectiontitle\">CURRENT LISTING</span>
			<br />
			
			<div style=\"width:100%;height:250px;padding:0;overflow:auto;\">
				<table width=\"100%\" class=\"listcol\" style=\"\">
					<tr>
						<th>SUBSETUPID</th>
						<th>SUBSETUP</th>
						<th>POSITION</th>
						<th>PHPFILE</th>
						<th>MADE BY</th>
						<th>MADE DATE</th>
					</tr>
	";
				while ($rowsetupdtllist=mysql_fetch_array($qrysetupdtllist))
				{
					$list1 = $rowsetupdtllist["SUBSETUPID"];
					$list2 = $rowsetupdtllist["SUBSETUP"];
					$list3 = $rowsetupdtllist["SUBPOSITION"];
					$list4 = $rowsetupdtllist["PHPFILE"];
					$list5 = $rowsetupdtllist["MADEBY"];
					$list6 = date('m-d-Y',strtotime($rowsetupdtllist["MADEDATE"]));
					
					echo "
					<tr $mouseovereffect ondblclick=\"
						document.$formname.setupid.value='$setupid';
						document.$formname.subsetupid.value='$list1';
						document.$formname.subsetup.value='$list2';
						document.$formname.subposition.value='$list3';
						document.$formname.phpfile.value='$list4';
						\">
						<td align=\"center\">$list1</td>
						<td align=\"center\">$list2</td>
						<td align=\"center\">$list3</td>
						<td align=\"center\">$list4</td>
						<td align=\"center\">$list5</td>
						<td align=\"center\">$list6</td>
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
	<input type=\"hidden\" name=\"subsetupid\" />
</form>

</body>
</html>
";

?>

