<?php

include("veritas/connectdb.php");
include("veritas/include/stylephp.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
	
$formname = "formreporthdr";
$formtitle = "REPORT HEADER SETUP";

	

//POSTS

if (isset($_POST['reportid']))
	$reportid = $_POST['reportid'];
	
if (isset($_POST['report']))
	$report = $_POST['report'];
	
if (isset($_POST['description']))
	$description = $_POST['description'];

if (isset($_POST['position']))
	$position = $_POST['position'];


switch ($actiontxt)
{
	case "save"		:
			
			$qryverify = mysql_query("SELECT REPORTID FROM reportshdr WHERE REPORTID=$reportid") or die(mysql_error());
		
			if(mysql_num_rows($qryverify) == 0)
			{		
				$qrysetuphdrsave = mysql_query("INSERT INTO reportshdr(REPORT,DESCRIPTION,POSITION,MADEBY,MADEDATE)
												VALUES('$report','$description',$position,'$employeeid','$currentdate')
												") or die(mysql_error());
			}
			else 
			{
				$qrysetuphdrupdate = mysql_query("UPDATE setuphdr SET REPORT='$report',
																		DESCRIPTION='$description',
																		POSITION=$position,
																		MADEBY='$employeeid',
																		MADEDATE='$currentdate'
													WHERE REPORTID=$reportid
													") or die(mysql_error());
			}
			
			$report = "";
			$description = "";
			$position = "";
		break;
		
	case "cancel"	:
		
			$report = "";
			$description = "";
			$position = "";

		break;
}
	

/* LISTINGS  */

$qryreportshdrlist = mysql_query("SELECT * FROM reportshdr ORDER BY POSITION") or die(mysql_error());

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
					<th>Report Title</th>
					<th>:</th>
					<th><input type=\"text\" name=\"report\" value=\"$report\" size=\"35\" onKeyPress=\"return alphaonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Description</th>
					<th>:</th>
					<th><input type=\"text\" name=\"description\" value=\"$description\" size=\"50\" maxlength=\"45\" onKeyPress=\"return alphaonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Position</th>
					<th>:</th>
					<th><input type=\"text\" name=\"position\" value=\"$description\" size=\"5\" onKeyPress=\"return numbersonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
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
						<th>REPORTID</th>
						<th>REPORT</th>
						<th>DESCRIPTION</th>
						<th>POSITION</th>
						<th>MADE BY</th>
						<th>MADE DATE</th>
					</tr>
	";
				while ($rowreportshdrlist=mysql_fetch_array($qryreportshdrlist))
				{
					$list1 = $rowreportshdrlist["REPORTID"];
					$list2 = $rowreportshdrlist["REPORT"];
					$list3 = $rowreportshdrlist["DESCRIPTION"];
					$list4 = $rowreportshdrlist["POSITION"];
					$list5 = $rowreportshdrlist["MADEBY"];
					$list6 = date('m-d-Y',strtotime($rowreportshdrlist["MADEDATE"]));
					
					echo "
					<tr $mouseovereffect ondblclick=\"
						document.$formname.reportid.value='$list1';
						document.$formname.report.value='$list2';
						document.$formname.description.value='$list3';
						document.$formname.position.value='$list4';
						\">
						<td align=\"center\">$list1</td>
						<td align=\"center\">$list2</td>
						<td align=\"center\">&nbsp;$list3</td>
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
	<input type=\"hidden\" name=\"reportid\" />
</form>

</body>
</html>
";

?>

