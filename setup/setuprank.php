<?php

include("veritas/connectdb.php");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
		
$formname = "formrank";
$formtitle = "RANK SETUP";

$checkojtposition = "";
$checkstatus = "";

//POSTS

if(isset($_POST['rankcode']))
	$rankcode=$_POST['rankcode'];

if(isset($_POST['rank']))
	$rank=$_POST['rank'];

if(isset($_POST['rankfull']))
	$rankfull=$_POST['rankfull'];

if(isset($_POST['alias1']))
	$alias1=$_POST['alias1'];

if(isset($_POST['alias2']))
	$alias2=$_POST['alias2'];

if(isset($_POST['ranking']))
	$ranking=$_POST['ranking'];
else 
{
	$qrygetmaxrank = mysql_query("SELECT MAX(RANKING) AS MAXRANK FROM rank") or die(mysql_error());
	
	$rowgetmaxrank = mysql_fetch_array($qrygetmaxrank);
	$ranking = $rowgetmaxrank["MAXRANK"] + 1;
}

if(isset($_POST['ranktypecode']))
	$ranktypecode=$_POST['ranktypecode'];

if(isset($_POST['ranklevelcode']))
	$ranklevelcode=$_POST['ranklevelcode'];


if(isset($_POST['ojtposition']))
{
	$ojtposition=1;
	$checkojtposition = "checked=\"checked\"";
}
else 
	$ojtposition=0;
	
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
		
			$qryverify = mysql_query("SELECT RANKCODE FROM rank WHERE RANKCODE='$rankcode'") or die(mysql_error());
		
			if(mysql_num_rows($qryverify) == 0)
			{
				$qryranksave = mysql_query("INSERT INTO rank(RANKCODE,RANK,RANKFULL,ALIAS1,ALIAS2,STATUS,RANKING,
							RANKTYPECODE,RANKLEVELCODE,OJT,MADEBY,MADEDATE) 
							VALUES('$rankcode','$rank','$rankfull','$alias1','$alias2',$status,$ranking,'$ranktypecode',
							'$ranklevelcode',$ojtposition,'$employeeid','$currentdate')
							") or die(mysql_error());
			}
			else 
			{
				$aryrankupdate = mysql_query("UPDATE rank SET RANK='$rank',
														RANKFULL='$rankfull',
														ALIAS1='$alias1',
														ALIAS2='$alias2',
														RANKING='$ranking',
														RANKTYPECODE='$ranktypecode',
														RANKLEVELCODE='$ranklevelcode',
														OJT=$ojtposition,
														STATUS=$status,
														MADEBY='$employeeid',
														MADEDATE='$currentdate'
											WHERE RANKCODE='$rankcode'
											") or die(mysql_error());
			}
			
			$rankcode = "";
			$rank = "";
			$rankfull = "";
			$alias1 = "";
			$alias2 = "";
			$status = 0;
			$ranking = "";
			$ranktypecode = "";
			$ranklevelcode = "";
			$ojtposition = 0;
			
			$checkojtposition = "";
			$checkstatus = "";
			
		break;
		
	case "cancel"	:
		
			$rankcode = "";
			$rank = "";
			$rankfull = "";
			$alias1 = "";
			$alias2 = "";
			$status = 0;
			$ranking = "";
			$ranktypecode = "";
			$ranklevelcode = "";
			$ojtposition = 0;
			
			$checkojtposition = "";
			$checkstatus = "";
		break;
}
	

/* LISTINGS  */

$qryranktypesel = mysql_query("SELECT RANKTYPECODE,RANKTYPE FROM ranktype") or die(mysql_error());

	$rankselect1 = "<option selected value=\"\">--Select One--</option>";
	while($rowranktypesel = mysql_fetch_array($qryranktypesel))
	{
		$rtypecode = $rowranktypesel["RANKTYPECODE"];
		$rtype = $rowranktypesel["RANKTYPE"];
		
		$selected1 = "";
		
		if ($ranktypecode == $rtypecode)
			$selected1 = "SELECTED";
			
		$rankselect1 .= "<option $selected1 value=\"$rtypecode\">$rtype</option>";
	}

$qryranklevelsel = mysql_query("SELECT RANKLEVELCODE,RANKLEVEL FROM ranklevel") or die(mysql_error());

	$rankselect2 = "<option selected value=\"\">--Select One--</option>";
	while($rowranklevelsel = mysql_fetch_array($qryranklevelsel))
	{
		$rlevelcode = $rowranklevelsel["RANKLEVELCODE"];
		$rlevel = $rowranklevelsel["RANKLEVEL"];
		
		$selected1 = "";
		
		if ($ranklevelcode == $rlevelcode)
			$selected1 = "SELECTED";
			
		$rankselect2 .= "<option $selected1 value=\"$rlevelcode\">$rlevel</option>";
	}


$qryrank = mysql_query("SELECT RANKCODE,RANK,RANKFULL,ALIAS1,ALIAS2,r.STATUS,RANKING,
						r.RANKTYPECODE,rt.RANKTYPE,r.RANKLEVELCODE,rl.RANKLEVEL,OJT,r.MADEBY,r.MADEDATE
						FROM rank r
						LEFT JOIN ranktype rt ON rt.RANKTYPECODE=r.RANKTYPECODE
						LEFT JOIN ranklevel rl ON rl.RANKLEVELCODE=r.RANKLEVELCODE
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
			
		<div style=\"width:100%;height:360px;float:right;padding:5px 20px 0 20px;overflow:auto;\">
			<br />
			<span class=\"sectiontitle\">$formtitle</span>
			<br />
			
			<table class=\"setup\" width=\"100%\" >	
				<tr>
					<th>Rank Code</th>
					<th>:</th>
					<th><input type=\"text\" name=\"rankcode\" value=\"$rankcode\" size=\"15\" maxlength=\"10\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Rank</th>
					<th>:</th>
					<th><input type=\"text\" name=\"rank\" value=\"$rank\" size=\"60\" maxlength=\"50\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Rank (Full)</th>
					<th>:</th>
					<th><input type=\"text\" name=\"rankfull\" value=\"$rankfull\" size=\"60\" maxlength=\"80\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Alias 1</th>
					<th>:</th>
					<th><input type=\"text\" name=\"alias1\" value=\"$alias1\" size=\"25\" maxlength=\"20\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Alias 2</th>
					<th>:</th>
					<th><input type=\"text\" name=\"alias2\" value=\"$alias2\" size=\"25\" maxlength=\"20\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Ranking</th>
					<th>:</th>
					<th><input type=\"text\" name=\"ranking\" value=\"$ranking\" disabled=\"disabled\" size=\"5\" maxlength=\"2\" onKeyPress=\"return numbersonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Rank Type</th>
					<th>:</th>
					<th><select name=\"ranktypecode\">
							$rankselect1
						</select>
					</th>
				</tr>
				<tr>
					<th>Rank Level</th>
					<th>:</th>
					<th><select name=\"ranklevelcode\">
							$rankselect2
						</select>
					</th>
				</tr>
				<tr>
					<th>OJT Position ?</th>
					<th>:</th>
					<th>
						<input type=\"checkbox\" name=\"ojtposition\" $checkojtposition />
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
		
		<div style=\"width:100%;height:210px;padding:5px 20px 0 20px;overflow:hidden;\">
			<br />
			<span class=\"sectiontitle\">CURRENT LISTING</span>
			<br />
			
			<div style=\"width:100%;height:145px;padding:0;overflow:auto;\">
				<table width=\"100%\" class=\"listcol\">
					<tr>
						<th>CODE</th>
						<th>RANK</th>
						<th>RANKFULL</th>
						<th>ALIAS1</th>
						<th>ALIAS2</th>
						<th>TYPE</th>
						<th>LEVEL</th>
						<th>OJT</th>
						<th>STATUS</th>
					</tr>
	";
				while ($rowrank=mysql_fetch_array($qryrank))
				{
					$list1 = $rowrank["RANKCODE"];
					$list2 = $rowrank["RANK"];
					$list3 = $rowrank["RANKFULL"];
					$list4 = $rowrank["ALIAS1"];
					$list5 = $rowrank["ALIAS2"];
					$list6 = $rowrank["RANKTYPE"];
					$list7 = $rowrank["RANKLEVEL"];
					$list8 = $rowrank["RANKTYPECODE"];
					$list9 = $rowrank["RANKLEVELCODE"];
					
					if ($rowrank["OJT"] == 0)
						$list10 = "NO";
					else 
						$list10 = "YES";
						
					if ($rowrank["STATUS"] == 0)
						$list11 = "INACTIVE";
					else 
						$list11 = "ACTIVE";
					
					$list12 = $rowrank["RANKING"];
					echo "
					<tr ondblclick=\"
						document.$formname.rankcode.value='$list1';
						document.$formname.rank.value='$list2';
						document.$formname.rankfull.value='$list3';
						document.$formname.alias1.value='$list4';
						document.$formname.alias2.value='$list5';
						document.$formname.ranktypecode.value='$list8';
						document.$formname.ranklevelcode.value='$list9';
						document.$formname.ranking.value='$list12';
						if ('$list10' == 'NO') {document.$formname.ojtposition.checked='';} else {document.$formname.ojtposition.checked='checked';}
						if ('$list11' == 'INACTIVE') {document.$formname.status.checked='';} else {document.$formname.status.checked='checked';}
						\">
						
						<td align=\"center\">$list1</td>
						<td>$list2</td>
						<td>$list3</td>
						<td align=\"center\">$list4</td>
						<td align=\"center\">$list5</td>
						<td align=\"center\">$list6</td>
						<td align=\"center\">$list7</td>
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

