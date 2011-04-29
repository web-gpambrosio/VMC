<?php

include("veritas/connectdb.php");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
		
$formname = "formtrainingmodules";
$formtitle = "TRAINING COURSE MODULES SETUP";

//POSTS

if(isset($_POST['traincode']))
	$traincode=$_POST['traincode'];

if(isset($_POST['moduleid']))
	$moduleid=$_POST['moduleid'];

if(isset($_POST['module']))
	$module=$_POST['module'];

if(isset($_POST['description']))
	$description=$_POST['description'];

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
		
			$qryverify = mysql_query("SELECT TRAINCODE FROM traincoursemodules WHERE TRAINCODE='$traincode' AND MODULEID=$moduleid") or die(mysql_error());
			
			if(mysql_num_rows($qryverify) == 0)
			{
				$qrytrainmodulessave = mysql_query("INSERT INTO traincoursemodules(TRAINCODE,MODULEID,MODULE,DESCRIPTION,STATUS,MADEBY,MADEDATE) 
												VALUES('$traincode',$moduleid,'$module','$description',$status,'$employeeid','$currentdate')
											") or die(mysql_error());
			}
			else 
			{
				$qrytrainmodulesupdate = mysql_query("UPDATE traincoursemodules SET 
															MODULE='$module',
															DESCRIPTION='$description',
															STATUS=$status,
															MADEBY='$employeeid',
															MADEDATE='$currentdate'
												 WHERE TRAINCODE='$traincode' AND MODULEID=$moduleid
											") or die(mysql_error());
			}
			
			$traincode = "";
			$moduleid = "";
			$module = "";
			$description = "";
			$status = 0;

			$checkstatus = "";		
		break;
		
	case "cancel"	:
		
			$traincode = "";
			$moduleid = "";
			$module = "";
			$description = "";
			$status = 0;

			$checkstatus = "";	

		break;
}
	

/* LISTINGS  */

$qrytrainingcourses = mysql_query("SELECT TRAINCODE,TRAINING
									FROM trainingcourses
									WHERE COURSETYPECODE='INHSE'
									AND STATUS=1
									ORDER BY TRAINING
					") or die(mysql_error());

	$coursesel = "<option selected value=\"\">--Select One--</option>";
	while($rowtrainingcourses = mysql_fetch_array($qrytrainingcourses))
	{
		$tcode = $rowtrainingcourses["TRAINCODE"];
		$train = $rowtrainingcourses["TRAINING"];
		
		$selected = "";
		
		if ($tcode == $traincode)
			$selected = "SELECTED";
			
		$coursesel .= "<option $selected value=\"$tcode\">$train</option>";
	}

$qrymodules = mysql_query("SELECT * FROM traincoursemodules WHERE TRAINCODE='$traincode'") or die(mysql_error());

/*END OF LISTINGS*/

echo "
<html>
<head>
<link rel=\"stylesheet\" type=\"text/css\" href=\"../veripro.css\">
<script language=\"javascript\" src=\"../veripro.js\"></script>

<script>


	
</script>
</head>
<body>

<form name=\"$formname\" method=\"POST\">
	
	<center>
	<div style=\"width:100%;height:480px;padding:5px 20px 0 20px;\">
			
		<div style=\"width:100%;height:240px;\">
			<br />
			<span class=\"sectiontitle\">$formtitle</span>
			<br />
			
			<table class=\"setup\" width=\"100%\" >	
				<tr>
					<th>Training (IN-HOUSE)</th>
					<th>:</th>
					<th><select name=\"traincode\" onchange=\"submit();\">
							$coursesel;
						</select>
					</th>
				</tr>
				<tr><td colspan=\"3\">&nbsp;</td></tr>
				<tr>
					<th>Module ID</th>
					<th>:</th>
					<th valign=\"top\"><input type=\"text\" name=\"moduleid\" value=\"$moduleid\" maxlength=\"2\" size=\"3\" onKeyPress=\"return numbersonly(this);\" />
						&nbsp;&nbsp;(Numbers Only)
					</th>
				</tr>
				<tr>
					<th>Module</th>
					<th>:</th>
					<th><input type=\"text\" name=\"module\" value=\"$module\" size=\"50\" onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th valign=\"top\">Description</th>
					<th valign=\"top\">:</th>
					<th><textarea rows=\"5\" cols=\"50\" name=\"description\">$description</textarea>
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
		
		<div style=\"width:100%;height:300px;overflow:hidden;\">
			<br />
			<span class=\"sectiontitle\">CURRENT LISTING</span>
			<br />
			<div style=\"width:100%;height:240px;padding:0;overflow:auto;\">
				<table width=\"100%\" class=\"listcol\">
					<tr>
						<th>MODULE ID</th>
						<th>MODULE</th>
						<th>DESCRIPTION</th>
						<th>STATUS</th>
						<th>MADEBY</th>
						<th>MADEDATE</th>
					</tr>
		";
				while ($rowmodules=mysql_fetch_array($qrymodules))
				{
					$list1 = $rowmodules["MODULEID"];
					$list2 = $rowmodules["MODULE"];
					$list3 = $rowmodules["DESCRIPTION"];
						
					if ($rowmodules["STATUS"] == 0)
						$list4 = "INACTIVE";
					else 
						$list4 = "ACTIVE";
						
					$list5 = $rowmodules["MADEBY"];
					$list6 = date('m-d-Y',strtotime($rowmodules["MADEDATE"]));
					
					echo "
					<tr ondblclick=\"
						document.$formname.traincode.value='$traincode';
						document.$formname.moduleid.value='$list1';
						document.$formname.module.value='$list2';
						document.$formname.description.value='$list3';
						if ('$list4' == 'INACTIVE') {document.$formname.status.checked='';} else {document.$formname.status.checked='checked';}
					\">
						
						<td align=\"center\">$list1</td>
						<td>$list2</td>
						<td align=\"left\">&nbsp;$list3</td>
						<td align=\"center\">&nbsp;$list4</td>
						<td align=\"center\">&nbsp;$list5</td>
						<td align=\"center\">&nbsp;$list6</td>
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

