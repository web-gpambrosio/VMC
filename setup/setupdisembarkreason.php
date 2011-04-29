<?php

include("veritas/connectdb.php");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
		
$formname = "formdisembarkreason";
$formtitle = "DIESEMBARK REASON SETUP";

$checkstatus = "";

//POSTS

if(isset($_POST['disembarkreasoncode']))
	$disembarkreasoncode=$_POST['disembarkreasoncode'];
	
if(isset($_POST['disembarkreason']))
	$disembarkreason=$_POST['disembarkreason'];
	
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
		
			$qryverify = mysql_query("SELECT DISEMBREASONCODE FROM disembarkreason WHERE DISEMBREASONCODE='$disembarkreasoncode'") or die(mysql_error());
		
			if(mysql_num_rows($qryverify) == 0)
			{
				$qryrecommendsave = mysql_query("INSERT INTO disembarkreason(DISEMBREASONCODE,REASON,STATUS,MADEBY,MADEDATE) 
													VALUES('$disembarkreasoncode','$disembarkreason',$status,'$employeeid','$currentdate')
												") or die(mysql_error());
			}
			else 
			{
				$qryrecommendupdate = mysql_query("UPDATE disembarkreason SET REASON='$disembarkreason',
																STATUS=$status,
																MADEBY='$employeeid',
																MADEDATE='$currentdate'
												WHERE DISEMBREASONCODE='$disembarkreasoncode'
													") or die(mysql_error());
			}

			$disembarkreasoncode = "";
			$disembarkreason = "";
			$status = 0;
			
			$checkstatus = "";
			
		break;
		
	case "cancel"	:

			$disembarkreasoncode = "";
			$disembarkreason = "";
			$status = 0;
			
			$checkstatus = "";
		
		break;
		
	case "delete"	:

		break;
}
	

/* LISTINGS  */

$qrydisembarkreason = mysql_query("SELECT * FROM disembarkreason ORDER BY REASON") or die(mysql_error());


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
					<th>Disembark Reason Code</th>
					<th>:</th>
					<th><input type=\"text\" name=\"disembarkreasoncode\" value=\"$disembarkreasoncode\" size=\"10\" onKeyPress=\"return alphanumericonly(this);\" />
					</th>
				</tr>
				<tr>
					<th>Disembark Reason</th>
					<th>:</th>
					<th><input type=\"text\" name=\"disembarkreason\" value=\"$disembarkreason\" size=\"50\" onKeyPress=\"return alphanumericonly(this);\" />
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
					<th><input type=\"button\" name=\"\" value=\"Save\" onclick=\"actiontxt.value='save';submit();\" />
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
						<th>REASON</th>
						<th>STATUS</th>
						<th>MADE BY</th>
						<th>MADE DATE</th>
					</tr>
	";
				while ($rowdisembarkreason=mysql_fetch_array($qrydisembarkreason))
				{
					$list1 = $rowdisembarkreason["DISEMBREASONCODE"];
					$list2 = $rowdisembarkreason["REASON"];
						
					if ($rowdisembarkreason["STATUS"] == 0)
						$list3 = "INACTIVE";
					else 
						$list3 = "ACTIVE";
					
					$list4 = $rowdisembarkreason["MADEBY"];
					$list5 = date('m-d-Y',strtotime($rowdisembarkreason["MADEDATE"]));
					
					echo "
					<tr ondblclick=\"
						document.$formname.disembarkreasoncode.value='$list1';
						document.$formname.disembarkreason.value='$list2';
						if ('$list5' == 'INACTIVE') {document.$formname.status.checked='';} else {document.$formname.status.checked='checked';}
						\">
						
						<td align=\"center\">$list1</td>
						<td>$list2</td>
						<td align=\"center\">$list3</td>
						<td align=\"center\">$list4</td>
						<td align=\"center\">$list5</td>
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

