<?php

include("veritas/connectdb.php");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
		
$formname = "formpni";
$formtitle = "PNI SETUP";

$checkstatus = "";

//POSTS

if(isset($_POST['pnicode']))
	$pnicode=$_POST['pnicode'];
	
if(isset($_POST['pni']))
	$pni=$_POST['pni'];
	
if(isset($_POST['address']))
	$address=$_POST['address'];
	
switch ($actiontxt)
{
	case "save"		:
		
			$qryverify = mysql_query("SELECT PNICODE FROM pni WHERE PNICODE='$pnicode'") or die(mysql_error());
		
			if(mysql_num_rows($qryverify) == 0)
			{
				$qrypnisave = mysql_query("INSERT INTO pni(PNICODE,PNI,ADDRESS,MADEBY,MADEDATE) 
													VALUES('$pnicode','$pni','$address','$employeeid','$currentdate')
												") or die(mysql_error());
			}
			else 
			{
				$qrypniupdate = mysql_query("UPDATE pni SET PNI='$pni',
																ADDRESS='$address',
																MADEBY='$employeeid',
																MADEDATE='$currentdate'
													WHERE PNICODE='$pnicode'
													") or die(mysql_error());
			}

			$pnicode = "";
			$pni = "";
			$address = "";
			
		break;
		
	case "cancel"	:

			$pnicode = "";
			$pni = "";
			$address = "";
		
		break;
		
	case "delete"	:

		break;
}
	

/* LISTINGS  */

$qrypni = mysql_query("SELECT * FROM pni ORDER BY PNI") or die(mysql_error());


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
			
		<div style=\"width:100%;height:210px;float:right;padding:5px 20px 0 20px;overflow:auto;\">
			<br />
			<span class=\"sectiontitle\">$formtitle</span>
			<br />
			
			<table class=\"setup\" width=\"100%\" >	
				<tr>
					<th>PNI Code</th>
					<th>:</th>
					<th><input type=\"text\" name=\"pnicode\" value=\"$pnicode\" size=\"10\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>PNI Name</th>
					<th>:</th>
					<th><input type=\"text\" name=\"pni\" value=\"$pni\" size=\"50\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Address</th>
					<th>:</th>
					<th><input type=\"text\" name=\"address\" value=\"$address\" size=\"50\" onKeyPress=\"return alphanumericonly(this);\"
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
		
		<div style=\"width:100%;height:290px;padding:5px 20px 0 20px;overflow:hidden;\">
			<br />
			<span class=\"sectiontitle\">CURRENT LISTING</span>
			<br />
			
			<div style=\"width:100%;height:220px;padding:0;overflow:auto;\">
				<table width=\"100%\" class=\"listcol\">
					<tr>
						<th>CODE</th>
						<th>PNI</th>
						<th>ADDRESS</th>
						<th>MADE BY</th>
						<th>MADE DATE</th>
					</tr>
	";
				while ($rowpni=mysql_fetch_array($qrypni))
				{
					$list1 = $rowpni["PNICODE"];
					$list2 = $rowpni["PNI"];
					$list3 = $rowpni["ADDRESS"];
						
					$list5 = $rowpni["MADEBY"];
					$list6 = date('m-d-Y',strtotime($rowpni["MADEDATE"]));
					
					echo "
					<tr ondblclick=\"
						document.$formname.pnicode.value='$list1';
						document.$formname.pni.value='$list2';
						document.$formname.address.value='$list3';
						\">
						
						<td align=\"center\">$list1</td>
						<td>$list2</td>
						<td>$list3</td>
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
	<input type=\"hidden\" name=\"delcode\" />
</form>

</body>
</html>
";

?>

