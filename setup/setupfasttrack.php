<?php

include("veritas/connectdb.php");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
	
$formname = "formfasttrack";
$formtitle = "FAST TRACK SETUP";

	

//POSTS

if (isset($_POST['fasttrackcode']))
	$fasttrackcode = $_POST['fasttrackcode'];
	
if (isset($_POST['fasttrack']))
	$fasttrack = $_POST['fasttrack'];


switch ($actiontxt)
{
	case "save"		:
			
			$qryverify = mysql_query("SELECT FASTTRACKCODE FROM fasttrack WHERE FASTTRACKCODE='$fasttrackcode'") or die(mysql_error());
		
			if(mysql_num_rows($qryverify) == 0)
			{		
				$qryfasttracksave = mysql_query("INSERT INTO fasttrack(FASTTRACKCODE,FASTTRACK,MADEBY,MADEDATE)
												VALUES('$fasttrackcode','$fasttrack','$employeeid','$currentdate')
												") or die(mysql_error());
			}
			else 
			{
				$qryfasttrackupdate = mysql_query("UPDATE designation SET FASTTRACK='$fasttrack',
																		MADEBY='$employeeid',
																		MADEDATE='$currentdate'
													WHERE FASTTRACKCODE='$fasttrackcode'
													") or die(mysql_error());
			}
			
			$fasttrackcode = "";
			$fasttrack = "";
		break;
		
	case "cancel"	:
		
			$fasttrackcode = "";
			$fasttrack = "";

		break;
}
	

/* LISTINGS  */

$qryfasttrack = mysql_query("SELECT * FROM fasttrack") or die(mysql_error());

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
		if(fasttrackcode.value=='' || fasttrackcode.value==null)
			if(rem=='')
				rem='Fast Track Code';
			else
				rem=rem + ',Fast Track Code';	
		if(fasttrack.value=='' || fasttrack.value==null)
			if(rem=='')
				rem='Fast Track';
			else
				rem=rem + ',Fast Track';
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
			
		<div style=\"width:100%;height:175px;padding:5px 20px 0 20px;overflow:hidden;\">
			<br />
			<span class=\"sectiontitle\">$formtitle</span>
			<br />
			
			<table class=\"setup\" width=\"100%\" >	
				<tr>
					<th>Fast Track Code</th>
					<th>:</th>
					<th><input type=\"text\" name=\"fasttrackcode\" value=\"$fasttrackcode\" size=\"15\" maxlength=\"10\" onKeyPress=\"return alphaonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Fast Track</th>
					<th>:</th>
					<th><input type=\"text\" name=\"fasttrack\" value=\"$fasttrack\" size=\"50\" maxlength=\"45\" onKeyPress=\"return alphaonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
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
		
		<div style=\"width:100%;height:325px;padding:5px 20px 0 20px;overflow:hidden;\">
			<br />
			<span class=\"sectiontitle\">CURRENT LISTING</span>
			<br />
			
			<div style=\"width:100%;height:250px;padding:0;overflow:auto;\">
				<table width=\"100%\" class=\"listcol\" style=\"\">
					<tr>
						<th>CODE</th>
						<th>FAST TRACK</th>
						<th>MADE BY</th>
						<th>MADE DATE</th>
					</tr>
	";
				while ($rowfasttrack=mysql_fetch_array($qryfasttrack))
				{
					$list1 = $rowfasttrack["FASTTRACKCODE"];
					$list2 = $rowfasttrack["FASTTRACK"];
					$list3 = $rowfasttrack["MADEBY"];
					$list4 = date('m-d-Y',strtotime($rowfasttrack["MADEDATE"]));
					
					echo "
					<tr ondblclick=\"
						document.$formname.fasttrackcode.value='$list1';
						document.$formname.fasttrack.value='$list2';
						\">
						<td align=\"center\">$list1</td>
						<td>$list2</td>
						<td align=\"center\">$list3</td>
						<td align=\"center\">$list4</td>
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

