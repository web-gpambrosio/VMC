<?php

include("veritas/connectdb.php");
include("veritas/include/stylephp.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');
$basedirdocs = "docimages/";


function checkpath($path)
{
	if (is_file($path))
		return true;
	else 
		return false;
}


if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];

if(isset($_SESSION['employeeid']))
	$employeeid=$_SESSION['employeeid'];

	
if(isset($_GET['traincode']))
	$traincode=$_GET['traincode'];
	
if(isset($_GET['applicantno']))
	$applicantno=$_GET['applicantno'];
else 
	$applicantno=$_POST['applicantno'];


$qrytraininghistory = mysql_query("
							SELECT x.*,r.RANK,v.VESSEL,															
								IF (DATEFROM > CURRENT_DATE,'ENROLLED',
								    IF (DATETO < CURRENT_DATE,
								        'FINISHED',
								        'ON-GOING'
								        )
								) AS STATUS2,
								IF (x.STATUS=1,'COMPLETED',IF(x.STATUS=2,'INCOMPLETE','NOT YET POSTED')) AS STATUS
								FROM (
									SELECT 'IN' AS TYPE,th.DATEFROM,th.DATETO,ct.RANKCODE,ct.VESSELCODE,ct.GRADE,ct.STATUS,ct.REMARKS,th.STATUS AS TRAINSTATUS
									FROM crewtraining ct
									LEFT JOIN trainingschedhdr th ON th.SCHEDID=ct.SCHEDID
									WHERE ct.APPLICANTNO=$applicantno AND th.TRAINCODE='$traincode'
								
									UNION
								
									SELECT 'OUT' AS TYPE,th.DATEFROM,th.DATETO,cto.RANKCODE,cto.VESSELCODE,cto.GRADE,cto.STATUS,cto.REMARKS,th.STATUS AS TRAINSTATUS
									FROM crewtrainingothers cto
									LEFT JOIN trainingschedhdr th ON th.SCHEDID=cto.SCHEDID
									WHERE cto.APPLICANTNO=$applicantno AND th.TRAINCODE='$traincode'
								
									UNION
								
									SELECT 'OLD' AS TYPE,TRAINDATE AS DATEFROM,TRAINDATE AS DATETO,RANKCODE,NULL AS VESSELCODE,GRADE,1 AS STATUS,NULL AS REMARKS,1 AS TRAINSTATUS
									FROM crewtrainingold ctold
									WHERE ctold.APPLICANTNO=$applicantno AND ctold.TRAINCODE='$traincode'
									
									UNION
									
									SELECT 'UPD' AS TYPE,TRAINDATE AS DATEFROM,TRAINDATE AS DATETO,RANKCODE,NULL AS VESSELCODE,GRADE,1 AS STATUS,NULL AS REMARKS,1 AS TRAINSTATUS
									FROM crewtrainingnosched ctnosched
									WHERE ctnosched.APPLICANTNO=$applicantno AND ctnosched.TRAINCODE='$traincode'
								) x
							LEFT JOIN rank r ON r.RANKCODE=x.RANKCODE
							LEFT JOIN vessel v ON v.VESSELCODE=x.VESSELCODE
							ORDER BY DATEFROM DESC
			") or die(mysql_error());


$qrytraininginfo = mysql_query("SELECT TRAINING,DOCCODE
								FROM trainingcourses t
								WHERE TRAINCODE='$traincode'
							") or die(mysql_error());

$rowtraininginfo = mysql_fetch_array($qrytraininginfo);

$training = $rowtraininginfo["TRAINING"];
$doccode = $rowtraininginfo["DOCCODE"];


echo "
<html>
<head>
<title>
Training History
</title>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>

<body style=\"overflow:hidden;\">

<form name=\"traininghistory\" method=\"POST\">

<div style=\"width:100%;height:300px;overflow:auto;background-color:Black;\">
<br />
	<table style=\"width:75%;font-size:1em;font-weight:Bold;\">
		<tr>
			<td style=\"color:Silver;\">Training</td>
			<td style=\"color:Silver;\">:</td>
			<td style=\"color:Yellow;\">$training</t>
		</tr>
		<tr>
			<td style=\"color:Silver;\">Document Code</td>
			<td style=\"color:Silver;\">:</td>
			<td style=\"color:Yellow;\">$doccode</t>
		</tr>
	</table>
	<br />

	<table width=\"100%\" class=\"listcol\">
		<tr>
			<th>FROM</th>
			<th>TO</th>
			<th>RANK</th>
			<th>VESSEL</th>
			<th>GRADE</th>
			<th>REMARKS</th>
			<th>STATUS</th>
		</tr>
		";
						
		while($rowtraininghistory = mysql_fetch_array($qrytraininghistory))
		{
			$datefrom = date('dMY',strtotime($rowtraininghistory["DATEFROM"]));
			$dateto = date('dMY',strtotime($rowtraininghistory["DATETO"]));
			$rankcode = $rowtraininghistory["RANKCODE"];
			$rank = $rowtraininghistory["RANK"];
			$vesselcode = $rowtraininghistory["VESSELCODE"];
			$vessel = $rowtraininghistory["VESSEL"];
			$grade = $rowtraininghistory["GRADE"];
			$trainstatus = $rowtraininghistory["TRAINSTATUS"];
//			$trainstatus = $rowtraininghistory["TRAINSTATUS"];
			$status = $rowtraininghistory["STATUS"];
			
//			$dirfilename1 = $basedirdocs . $applicantno . "/C/OLD/" . $chkdoccode . ".pdf";
//			
//			if (checkpath($dirfilename1))
//			{
//				$view = "<a href=\"#\" onclick=\"document.getElementById('document').src='$dirfilename1' + '#toolbar=0&navpanes=0';\" >[VIEW]</a>";
//			}
//			else 
//			{
//				$view = "[NO VIEW]";
//			}
//			D:\www\veritas\docimages\102190\C\OLD
			
//			$view = "------";

			$view = $rowtraininghistory["REMARKS"];


			echo "
				<tr $mouseovereffect style=\"cursor:pointer;\">
					<td>$datefrom</td>
					<td>$dateto</td>
					<td align=\"center\">&nbsp;$rank</td>
					<td align=\"center\">&nbsp;$vessel</td>
					<td align=\"center\">&nbsp;$grade</td>
					<td align=\"center\" style=\"font-weight:Bold;color:Navy;\">$view</td>
					<td align=\"center\">$status</td>
				</tr>
			";
		}
echo "
	</table>
</div>

<div style=\"width:100%;overflow:auto;\">

<iframe id=\"document\" src=\"\" height=\"100%\" width=\"100%\"></iframe>


</div>
			
</form>

</body>

</html>

";


?>