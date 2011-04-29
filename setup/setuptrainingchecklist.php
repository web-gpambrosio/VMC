<?php

include("veritas/connectdb.php");
include("veritas/include/stylephp.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
		
$formname = "formtrainingchecklist";
$formtitle = "TRAINING CHECKLIST SETUP";

//POSTS

if(isset($_POST['rankcode']))
	$rankcode=$_POST['rankcode'];

//if(isset($_POST['rankcodesel']))
//	$rankcodesel=$_POST['rankcodesel'];

if(isset($_POST['traincode']))
	$traincode=$_POST['traincode'];

if(isset($_POST['required']))
	$required=$_POST['required'];

if(isset($_POST['traincodesel']))
	$traincodesel=$_POST['traincodesel'];

//if(isset($_POST['coursetypecode']))
//	$coursetypecode=$_POST['coursetypecode'];
//else 
//	$coursetypecode = "INHSE";

if(isset($_POST['required']))
	$required=$_POST['required'];

switch ($actiontxt)
{
	case "copytochecklist"	:
		
			$qryverify = mysql_query("SELECT IDNO FROM trainingchecklist WHERE TRAINCODE='$traincodesel' AND RANKCODE='$rankcode'") or die(mysql_error());
			
			if (mysql_num_rows($qryverify) == 0)
			{
				$qryinsert = mysql_query("INSERT INTO trainingchecklist(RANKCODE,TRAINCODE,REQUIRED,STATUS,MADEBY,MADEDATE) 
										VALUES('$rankcode','$traincodesel',1,1,'$employeeid','$currentdate')") or die(mysql_error());
				
			}
		

		break;
		
	case "deletetraining"	:

			$qryverify = mysql_query("SELECT IDNO FROM trainingchecklist WHERE TRAINCODE='$traincodesel' AND RANKCODE='$rankcode'") or die(mysql_error());

			if (mysql_num_rows($qryverify) > 0)
			{
				$qrydelete = mysql_query("DELETE FROM trainingchecklist WHERE TRAINCODE='$traincodesel' AND RANKCODE='$rankcode'") or die(mysql_error());
			}

		break;
		
	case "updaterequired"	:
		
//			echo "Traincode=" . $traincodesel . " / Rank=" . $rankcode . " / Required=" . $_POST['required'];
			$qryverify = mysql_query("SELECT IDNO FROM trainingchecklist WHERE TRAINCODE='$traincodesel' AND RANKCODE='$rankcode'") or die(mysql_error());

			if (mysql_num_rows($qryverify) > 0)
			{
				$qryupdate = mysql_query("UPDATE trainingchecklist SET REQUIRED=$required 
										WHERE TRAINCODE='$traincodesel' 
										AND RANKCODE='$rankcode'") or die(mysql_error());
			}
		
		break;
}
	

/* LISTINGS  */


$qryranklist = mysql_query("SELECT RANKCODE,RANK from rank WHERE STATUS=1 ORDER BY RANKING") or die(mysql_error());

	$selectrank = "<option selected value=\"\">--Select One--</option>";
	while($rowranklist = mysql_fetch_array($qryranklist))
	{
		$rcode = $rowranklist["RANKCODE"];
		$rank = $rowranklist["RANK"];
		
		$selected = "";
		
		if ($rcode == $rankcode)
			$selected = "SELECTED";
			
		$selectrank .= "<option $selected value=\"$rcode\">$rank</option>";
	}

$qryavailablecourses = mysql_query("SELECT t.TRAINCODE,t.TRAINING,t.COURSETYPECODE,ct.COURSETYPE
									FROM trainingcourses t
									LEFT JOIN coursetype ct ON ct.COURSETYPECODE=t.COURSETYPECODE
									LEFT JOIN trainingchecklist tch ON tch.TRAINCODE=t.TRAINCODE AND tch.RANKCODE='$rankcode'
									WHERE t.STATUS=1 AND tch.IDNO IS NULL
									ORDER BY t.COURSETYPECODE,t.TRAINING
					") or die(mysql_error());

$qryrankchecklist = mysql_query("SELECT t.RANKCODE,t.TRAINCODE,tc.TRAINING,t.REQUIRED,tc.COURSETYPECODE,ct.COURSETYPE
								FROM trainingchecklist t
								LEFT JOIN trainingcourses tc ON tc.TRAINCODE=t.TRAINCODE
								LEFT JOIN coursetype ct ON ct.COURSETYPECODE=tc.COURSETYPECODE
								WHERE t.RANKCODE='$rankcode' AND t.STATUS=1
								ORDER BY tc.COURSETYPECODE,tc.TRAINING
								") or die(mysql_error());


/*END OF LISTINGS*/

echo "
<html>
<head>
<link rel=\"stylesheet\" type=\"text/css\" href=\"../veripro.css\">
<script language=\"javascript\" src=\"../veripro.js\"></script>

<script>

//function calliframe(tcode,rnkcode)
//{
//	with(document.$formname)
//	{
//		if(rankcodesel.value!='')
//		{
//			alert('checklist.php?actiontxt.value=copytochecklist&traincodesel=' + tcode + '&rankcodesel=' +rnkcode);
//			document.getElementById('content').src='checklist.php?actiontxt.value=copytochecklist&traincodesel=' + tcode + '&rankcodesel=' +rnkcode;
//		}
//		else
//		{
//			document.getElementById('content').src='checklist.php';
//		}
//	}
//}
	
</script>
</head>
<body style=\"overflow:auto;\">

<form name=\"$formname\" method=\"POST\">
	
	<div style=\"width:100%;height:620px;padding:5px 20px 0 20px;\">
			
		<br />
		<span class=\"sectiontitle\">$formtitle</span>
		<br />
		
		<div style=\"width:48%;height:550px;float:left;overflow:hidden;\">
		
			<span class=\"sectiontitle\">AVAILABLE COURSES</span>
			<br />
			
			<div style=\"width:100%;height:510px;overflow:auto;\">
			<table width=\"100%\" class=\"listcol\">
				<tr>
					<th>TRAIN CODE</th>
					<th>TRAINING</th>
				</tr>
			";
			
			if ($rankcode != "")
			{
			
				$coursetypecodetmp = "";
				
				while ($rowavailablecourses = mysql_fetch_array($qryavailablecourses))
				{
					$atraincode = $rowavailablecourses["TRAINCODE"];
					$atraining = $rowavailablecourses["TRAINING"];
					$acoursetypecode = $rowavailablecourses["COURSETYPECODE"];
					$acoursetype = $rowavailablecourses["COURSETYPE"];
					
					if ($coursetypecodetmp != $acoursetypecode)
					{
						echo "
						<tr>
							<td colspan=\"2\" style=\"font-size:1.2em;font-weight:Bold;background-color:Navy;color:Yellow;text-align:center;\">$acoursetype</td>
						</tr>
						";
					}
					
					echo "
					<tr onclick=\"actiontxt.value='copytochecklist';traincodesel.value='$atraincode';submit();\" $mouseovereffect
								style=\"cursor:pointer;\">
						<td style=\"font-weight:Bold;\">$atraincode</td>
						<td style=\"font-size:0.9em;\">$atraining</td>
					</tr>
					";
					
					$coursetypecodetmp = $acoursetypecode;
				}
			}
			else 
			{
				echo "
				<tr>
					<td colspan=\"2\">&nbsp;</td>
				</tr>
				<tr>
					<td colspan=\"2\" style=\"font-size:1.5em;font-weight:Bold;color:Green\" align=\"center\"><i>[ SELECT RANK FIRST... ]</i></td>
				</tr>
				";
			}

		echo "
			</table>
			</div>
		
		</div>
		
		<div style=\"width:50%;height:550px;float:right;overflow:hidden;\">
		
			<span class=\"sectiontitle\">CHECKLIST</span>
			<br />
			
			<center>
				<select name=\"rankcode\" onchange=\"submit();\">
					$selectrank
				</select>
			</center>
			<br />
			
			<div style=\"width:100%;height:430px;overflow:auto;border:1px solid gray;background-color:White;\">
			";
		
			$stylehdr = " style=\"font-size:0.7em;font-weight:Bold;background-color:Black;color:Lime;text-align:center;\"";
			$styledtl = " style=\"font-size:0.7em;color:Black;border-bottom:1px dashed gray;\"";
			
			echo "
				<table width=\"100%\">
					<tr>
						<th width=\"70%\" $stylehdr>TRAINING</th>
						<th width=\"20%\" $stylehdr>REQUIRED</th>
						<th width=\"10%\" $stylehdr>&nbsp;</th>
					</tr>
				";
				
				$coursetypecodetmp = "";
		
				while ($rowrankchecklist = mysql_fetch_array($qryrankchecklist))
				{
					$ctraincode = $rowrankchecklist["TRAINCODE"];
					$ctraining = $rowrankchecklist["TRAINING"];
					$ccoursetypecode = $rowrankchecklist["COURSETYPECODE"];
					$ccoursetype = $rowrankchecklist["COURSETYPE"];
					$crequired = $rowrankchecklist["REQUIRED"];
					
					if ($coursetypecodetmp != $ccoursetypecode)
					{
						echo "
						<tr>
							<td colspan=\"3\" style=\"font-size:0.8em;font-weight:Bold;background-color:Navy;color:Yellow;\">$ccoursetype</td>
						</tr>
						";
					}
					
					echo "
					<tr $mouseovereffect>
						<td $styledtl>$ctraining</td>
						<td align=\"center\" $styledtl>
					";
					
					if ($crequired == 0)
						echo "<a href=\"#\" onclick=\"actiontxt.value='updaterequired';traincodesel.value='$ctraincode';
									required.value=1;submit();\" style=\"color:Red;\"><b>NO</b></a>";
					else 
						echo "<a href=\"#\" onclick=\"actiontxt.value='updaterequired';traincodesel.value='$ctraincode';
									required.value=0;submit();\" style=\"color:Blue;\"><b>YES</b></a>";
					
					echo "	
						</td>
						<td $styledtl>
							<input type=\"button\" value=\"Delete\" onclick=\"actiontxt.value='deletetraining';traincodesel.value='$ctraincode';submit();\" 
									style=\"cursor:pointer;\" />
						</td>
					</tr>
					";
					$coursetypecodetmp = $ccoursetypecode;
				}
		
				echo "
				</table>
			</div>
					
			<br />
		
		
<!--
			<iframe marginwidth=0 marginheight=0 id=\"content\" frameborder=\"0\" name=\"content\" scrolling=\"yes\" 
				style=\"width:100%;height:550px;\">
			</iframe>	
-->
		</div>
		
	</div>
	
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"traincodesel\" />
	<input type=\"hidden\" name=\"rankcodesel\" />
	<input type=\"hidden\" name=\"required\" />
</form>

</body>
</html>
";

?>

