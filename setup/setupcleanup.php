<?php

include("veritas/connectdb.php");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
		
$formname = "formcleanup";
$formtitle = "MCMS_37 CLEANUP";


//POSTS

if(isset($_POST['trainingcode']))
	$trainingcode=$_POST['trainingcode'];

if(isset($_POST['convtrain']))
	$convtrain=stripslashes($_POST['convtrain']);

if(isset($_POST['sortby']))
	$sortby=$_POST['sortby'];
else 
	$sortby = "1";

if(isset($_POST['coursetypecode']))
	$coursetypecode=$_POST['coursetypecode'];
else 
	$coursetypecode = "INHSE";



switch ($actiontxt)
{
	case "convert"	:
		
			$convtraintmp = str_replace("*","\"",$convtrain);
			$convtraintmp = str_replace("!","\'",$convtraintmp);
			
			$qrymcmstraining = mysql_query("SELECT IDNO,APPLICANTNO,CRW1,VSCODE,R1,TM3,TM4 
											FROM training_mcms t
											LEFT JOIN crew c ON c.CREWCODE=t.CRW1
											WHERE TM2='$convtraintmp' AND APPLICANTNO IS NOT NULL
											ORDER BY CRW1") or die(mysql_error());
			
			while ($rowmcmstraining = mysql_fetch_array($qrymcmstraining))
			{
				$applicantno = $rowmcmstraining["APPLICANTNO"];
				$vslcode = $rowmcmstraining["VSCODE"];
				$rankcode = $rowmcmstraining["R1"];
				$idno = $rowmcmstraining["IDNO"];
				
				if ($rowmcmstraining["TM3"] != "")
					$traindate = "'" . date("Y-m-d",strtotime($rowmcmstraining["TM3"])) . "'";
				else 
					$traindate = "NULL";
					
				if ($rowmcmstraining["TM4"] != "")
					$grade = $rowmcmstraining["TM4"];
				else 
					$grade = 0;
					
				if ($trainingcode != "")
				{
					$trainingcode1 = "'" . $trainingcode . "'";
					$training = "NULL";
				}
				else 
				{
					$trainingcode1 = "NULL";
					$training = "'" . $convtraintmp . "'";
				}
					
				
				$qryinsert = mysql_query("INSERT INTO crewtrainingold(APPLICANTNO,TRAINCODE,TRAINING,TRAINDATE,
											RANKCODE,VESSELCODE,GRADE,MADEBY,MADEDATE)
											VALUES($applicantno,$trainingcode1,$training,$traindate,'$rankcode',
											'$vslcode',$grade,'$employeeid','$currentdate')
										") or die(mysql_error());
				
				$qrymcmsupdate = mysql_query("UPDATE training_mcms SET STATUS=1 WHERE IDNO=$idno") or die(mysql_error());
			}
			
			$trainingcode = "";
			$convtrain = "";

		break;
		
	case "cancel"	:
		
			$trainingcode = "";
			$coursetypecode = "INHSE";
			$convtrain = "";
			$sortby = "1";

		break;
}
	

/* LISTINGS  */

if ($sortby == "1")
{
	$orderby = "ORDER BY TM2";
	$check1 = "checked=\"checked\"";
}
else 
{
	$orderby = "ORDER BY COUNT(*) DESC";
	$check2 = "checked=\"checked\"";
}

$qrytrainraw = mysql_query("SELECT DISTINCT upper(TM2) as TM2, COUNT(*) AS CNT 
							FROM training_mcms t
							LEFT JOIN crew c ON c.CREWCODE=t.CRW1
							WHERE t.STATUS=0 AND APPLICANTNO IS NOT NULL
							GROUP BY TM2
							$orderby") or die(mysql_error());

	$select1 = "<option selected value=\"\">--Select One--</option>";
	$cnt = "";
	while($rowtrainraw = mysql_fetch_array($qrytrainraw))
	{
		$train1 = $rowtrainraw["TM2"];
		$train1a = $rowtrainraw["TM2"];
		$train1b = str_replace("\"","*",$rowtrainraw["TM2"]);
		$train1b = str_replace("'","!",$train1b);
		$cnt = number_format($rowtrainraw["CNT"],0);
		
		$selected1 = "";
		
//		echo $train1a . "==" . addslashes($convtrain) . "&&&\r";
//		echo $train1b . "==" . $convtrain . "****\n";
		
		if ($train1b == $convtrain)
			$selected1 = "SELECTED";
		else 
			$selected1 = "";
			
		$select1 .= "<option $selected1 value=\"$train1b\">$train1 - ($cnt)</option>";
	}

$qrycoursetypesel = mysql_query("SELECT COURSETYPECODE,COURSETYPE FROM coursetype ORDER BY COURSETYPE") or die(mysql_error());

	$select2 = "<option selected value=\"\">--Select One--</option>";
	while($rowcoursetypesel = mysql_fetch_array($qrycoursetypesel))
	{
		$typecode = $rowcoursetypesel["COURSETYPECODE"];
		$typename = $rowcoursetypesel["COURSETYPE"];
		
		$selected2 = "";
		
		if ($typecode == $coursetypecode)
			$selected2 = "SELECTED";
			
		$select2 .= "<option $selected2 value=\"$typecode\">$typename</option>";
	}

$qrytrainingsel = mysql_query("SELECT TRAINCODE,TRAINING,DOCCODE
							FROM trainingcourses 
							WHERE COURSETYPECODE='$coursetypecode'
							ORDER BY TRAINING
					") or die(mysql_error());

	$select3 = "<option selected value=\"\">-- NO TRAINING CODE (INSERT AS-IS) --</option>";
	while($rowtrainingsel = mysql_fetch_array($qrytrainingsel))
	{
		$tcode = $rowtrainingsel["TRAINCODE"];
		$train = $rowtrainingsel["TRAINING"];
		if ($rowtrainingsel["DOCCODE"] != "")
			$dcode = "[" . $rowtrainingsel["DOCCODE"] . "]&nbsp;-&nbsp;";
		else 
			$dcode = "";
		
		$selected3 = "";
		
		if ($tcode == $trainingcode)
			$selected3 = "SELECTED";
			
		$select3 .= "<option $selected3 value=\"$tcode\">$dcode $train</option>";
	}

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
	<div style=\"width:100%;height:550px;padding:5px 20px 0 20px;\">
			
		<div style=\"width:100%;height:250px;float:right;padding:5px 20px 0 20px;\">
			<br />
			<span class=\"sectiontitle\">$formtitle</span>
			<br />
			
			<table class=\"setup\" width=\"100%\" >	
				<tr>
					<th colspan=\"3\">MCMS_37 Training: <br />
					 	<select name=\"convtrain\" onchange=\"submit();\">
							$select1;
						</select>
					</th>
				</tr>
				<tr>
					<th colspan=\"3\">
						<input type=\"radio\" $check1 name=\"sortby\" value=\"1\" onclick=\"submit();\">Alphabetical&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" $check2 name=\"sortby\" value=\"2\" onclick=\"submit();\">Number of Usage
					</th>
				</tr>
			</table>
			
			<br />
			<span class=\"sectiontitle\">CONVERT TO...</span>
			<br /><br />
			
			<table class=\"setup\" width=\"100%\" >	
				<tr>
					<th>Course Type</th>
					<th>:</th>
					<th><select name=\"coursetypecode\" onchange=\"submit();\">
							$select2
						</select>
					</th>
				</tr>
				<tr>
					<th colspan=\"3\">&nbsp;</th>
				</tr>
				<tr>
					<th>Existing Training Courses</th>
					<th>:</th>
					<th><select name=\"trainingcode\">
							$select3
						</select>
					</th>
				</tr>
				<tr>
					<th colspan=\"3\">&nbsp;</th>
				</tr>
				<tr>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
					<th><input type=\"button\" name=\"\" value=\"Convert and Transfer\" onclick=\"actiontxt.value='convert';submit();\" />
						<input type=\"button\" name=\"\" value=\"Cancel\" onclick=\"actiontxt.value='cancel';$formname.submit();\" />
					</th>
				</tr>			
			</table>
		</div>

<!--
		<div style=\"width:100%;height:230px;padding:5px 20px 0 20px;overflow:hidden;\">
			<br />
			<span class=\"sectiontitle\">CURRENT LISTING - $title_listing</span>
			<br />

		</div>
-->	
	</div>
	</center>
	
	<input type=\"hidden\" name=\"actiontxt\" />
</form>

</body>
</html>
";

?>

