<?php

include("veritas/connectdb.php");
include("veritas/include/stylephp.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
	
$formname = "formreportdtl";
$formtitle = "REPORT DETAIL SETUP";

$maximized = 0;
$checkmax = "";

//POSTS

if (isset($_POST['reportid']))
	$reportid = $_POST['reportid'];
else 
	$reportid = 1;
	
if (isset($_POST['subreport']))
	$subreport = $_POST['subreport'];
	
if (isset($_POST['subreportid']))
	$subreportid = $_POST['subreportid'];
	
if (isset($_POST['phpfile']))
	$phpfile = $_POST['phpfile'];

if (isset($_POST['subposition']))
	$subposition = $_POST['subposition'];

if (isset($_POST['chkmaximized']))
{
	$maximized = 1;
	$checkmax = "checked=\"checked\"";
}


switch ($actiontxt)
{
	case "save"		:
			
			$qryverify = mysql_query("SELECT SUBREPORTID FROM setupdtl WHERE REPORTID=$reportid AND SUBREPORT='$subreport'") or die(mysql_error());
		
			if(mysql_num_rows($qryverify) == 0)
			{		
				$qrysetupdtlsave = mysql_query("INSERT INTO reportsdtl(REPORTID,SUBREPORT,PHPFILE,MAXIMIZE,SUBPOSITION,MADEBY,MADEDATE)
												VALUES('$reportid','$subreport','$phpfile',$maximized,$subposition,'$employeeid','$currentdate')
												") or die(mysql_error());
			}
			else 
			{
				$qrysetupdtlupdate = mysql_query("UPDATE setupdtl SET SUBREPORT='$subsetup',
																		PHPFILE='$phpfile',
																		MAXIMIZE=$maximized,
																		SUBPOSITION=$subposition,
																		MADEBY='$employeeid',
																		MADEDATE='$currentdate'
													WHERE REPORTID=$reportid AND SUBREPORTID=$subreportid
													") or die(mysql_error());
			}
			
			$subreport = "";
			$phpfile = "";
			$subposition = "";
			$maximized = "";
		break;
		
	case "cancel"	:
		
			$subreport = "";
			$phpfile = "";
			$subposition = "";
			$maximized = "";

		break;
}
	

/* LISTINGS  */

$qryreportshdrlist = mysql_query("SELECT REPORTID,REPORT FROM reportshdr") or die(mysql_error());
$hdrselect = "<option value=\"\">--Select One--</option>";
$selected = "";
while ($rowreportshdrlist = mysql_fetch_array($qryreportshdrlist))
{
	$rid = $rowreportshdrlist["REPORTID"];
	$rep = $rowreportshdrlist["REPORT"];
	
	if ($rid == $reportid)
		$selected = "SELECTED";
	else 
		$selected = "";
	
	$hdrselect .= "<option $selected value=\"$rid\">$rep&nbsp;($rid)</option>";
}


if (!empty($reportid))
	$qryreportsdtllist = mysql_query("SELECT * FROM reportsdtl WHERE REPORTID=$reportid ORDER BY SUBPOSITION") or die(mysql_error());

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
	<div style=\"width:100%;height:600px;padding:5px 20px 0 20px;overflow:hidden;\">
			
		<div style=\"width:100%;height:250px;padding:5px 20px 0 20px;overflow:hidden;\">
			<br />
			<span class=\"sectiontitle\">$formtitle</span>
			<br />
			
			<table class=\"setup\" width=\"100%\" >	
				<tr>
					<th>Setup Header</th>
					<th>:</th>
					<th><select name=\"reportid\" onchange=\"submit();\">
							$hdrselect
						</select>
					
					</th>
				</tr>
				<tr>
					<th>Sub Report Title</th>
					<th>:</th>
					<th><input type=\"text\" name=\"subreport\" value=\"$subreport\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
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
					<th>Maximized?</th>
					<th>:</th>
					<th><input type=\"checkbox\" $checkmax name=\"chkmaximized\" />
					</th>
				</tr>
				<tr>
					<th>PHP File</th>
					<th>:</th>
					<th><input type=\"text\" name=\"phpfile\" value=\"$phpfile\" size=\"45\" onKeyPress=\"return alphanumericonly(this);\"
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
						<th>SUBREPORTID</th>
						<th>SUBREPORT</th>
						<th>POSITION</th>
						<th>PHPFILE</th>
						<th>MAXIMIZED</th>
						<th>MADE BY</th>
						<th>MADE DATE</th>
					</tr>
	";
				while ($rowreportsdtllist=mysql_fetch_array($qryreportsdtllist))
				{
					$list1 = $rowreportsdtllist["SUBREPORTID"];
					$list2 = $rowreportsdtllist["SUBREPORT"];
					$list3 = $rowreportsdtllist["SUBPOSITION"];
					$list4 = $rowreportsdtllist["PHPFILE"];
					$list5 = $rowreportsdtllist["MADEBY"];
					$list6 = date('m-d-Y',strtotime($rowreportsdtllist["MADEDATE"]));
					
					if ($rowreportsdtllist["MAXIMIZE"] == 0)
						$list7 = "NO";
					else 
						$list7 = "YES";
					
					echo "
					<tr $mouseovereffect ondblclick=\"
						document.$formname.reportid.value='$setupid';
						document.$formname.subreportid.value='$list1';
						document.$formname.subreport.value='$list2';
						document.$formname.subposition.value='$list3';
						document.$formname.phpfile.value='$list4';
						if ('$list7' == 'NO') {document.$formname.chkmaximized.checked='';} else {document.$formname.chkmaximized.checked='checked';}
						\">
						<td align=\"center\">$list1</td>
						<td align=\"center\">$list2</td>
						<td align=\"center\">$list3</td>
						<td align=\"center\">$list4</td>
						<td align=\"center\">$list7</td>
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
	<input type=\"hidden\" name=\"subreportid\" />
</form>

</body>
</html>
";

?>

