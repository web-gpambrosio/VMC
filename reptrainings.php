<?php

// include("veritas/connectdb.php");
include("veritas/include/functions.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');
$datenow = date('d F Y');
$basedir = "idpics/";
$basedirdocs = "docimages/";

if(isset($_SESSION["employeeid"]))
	$employeeid=$_SESSION["employeeid"];

//if (isset($_POST["actiontxt"]))
//	$actiontxt = $_POST["actiontxt"];
	
if (isset($_GET["applicantno"]))
	$applicantno = $_GET["applicantno"];
else 
	$applicantno = $_POST["applicantno"];
	
if (isset($_GET["directprint"]))
	$directprint = $_GET["directprint"];
	
include("veritas/connectdb.php");

include("include/datasheet.inc");

$title = "Trainings";

$qrytrainings = mysql_query("
			SELECT * FROM (
			SELECT '1' AS TYPE,tc.COURSETYPECODE,ct.APPLICANTNO,tc.TRAINCODE,tc.TRAINING,th.DATEFROM,th.DATETO,ct.GRADE,
			IF (tc.COURSETYPECODE = 'INHSE',trv.TRAINVENUE,trc.TRAINCENTER) AS LOCATION
			FROM crewtraining ct
			LEFT JOIN trainingschedhdr th ON th.SCHEDID=ct.SCHEDID
			LEFT JOIN trainingcourses tc ON tc.TRAINCODE=th.TRAINCODE
			LEFT JOIN trainingvenue trv ON trv.TRAINVENUECODE=tc.TRAINVENUECODE
			LEFT JOIN trainingcenter trc ON trc.TRAINCENTERCODE=tc.TRAINCENTERCODE
			WHERE ct.APPLICANTNO=$applicantno AND th.DATETO <= CURRENT_DATE
			
			UNION
			
			SELECT '2' AS TYPE,tc.COURSETYPECODE,cto.APPLICANTNO,cto.TRAINCODE,IF(cto.TRAINCODE IS NOT NULL,tc.TRAINING,cto.TRAINING) AS TRAINING,cto.TRAINDATE AS DATEFROM,NULL AS DATETO,cto.GRADE,
			IF (tc.COURSETYPECODE = 'INHSE',trv.TRAINVENUE,trc.TRAINCENTER) AS LOCATION
			FROM crewtrainingold cto
			LEFT JOIN trainingcourses tc ON tc.TRAINCODE=cto.TRAINCODE
			LEFT JOIN trainingvenue trv ON trv.TRAINVENUECODE=tc.TRAINVENUECODE
			LEFT JOIN trainingcenter trc ON trc.TRAINCENTERCODE=tc.TRAINCENTERCODE
			WHERE APPLICANTNO=$applicantno
			
			UNION
			
			SELECT '3' AS TYPE,tc.COURSETYPECODE,ctn.APPLICANTNO,ctn.TRAINCODE,tc.TRAINING,ctn.TRAINDATE AS DATEFROM,NULL AS DATETO,ctn.GRADE,
			IF (tc.COURSETYPECODE = 'INHSE',trv.TRAINVENUE,trc.TRAINCENTER) AS LOCATION
			FROM crewtrainingnosched ctn
			LEFT JOIN trainingcourses tc ON tc.TRAINCODE=ctn.TRAINCODE
			LEFT JOIN trainingvenue trv ON trv.TRAINVENUECODE=tc.TRAINVENUECODE
			LEFT JOIN trainingcenter trc ON trc.TRAINCENTERCODE=tc.TRAINCENTERCODE
			WHERE APPLICANTNO=$applicantno
			) x
			ORDER BY x.COURSETYPECODE,x.DATEFROM DESC
		") or die(mysql_error());


echo "
<html>\n
<head>\n
<title>
Printing - Crew Trainings
</title>

<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>
<script language=\"javascript\" src=\"popcalendar.js\"></script>	
<style type='text/css'>
@media print 
{
	#PAScreenOut { display: none; }
}
</style>

</head>
<body style=\"overflow:auto;\">\n

<div style=\"width:755px;background-color:white;margin:10 10 10 10;\">

";

include("repheader.php");
	
if (empty($directprint))
	echo "<input type=\"button\" value=\"Print\" id=\"PAScreenOut\" onclick=\"window.print();\">";
	
echo "
	<table style=\"width:100%;font-size:0.8em;\">
	";
	$styleborder = "style=\"border-bottom:1px solid Gray;\"";
	$stylebordertop = "style=\"border-top:1px solid Gray;\"";
	$typetmp = "";
	$coursetypetmp = "";
	$traincodetmp = "";
	
	$cnt = 1;
	while ($rowtrainings = mysql_fetch_array($qrytrainings))
	{
		$type = $rowtrainings["TYPE"];
		$grade = $rowtrainings["GRADE"];
		$coursetypecode = $rowtrainings["COURSETYPECODE"];
		$traincode = $rowtrainings["TRAINCODE"];
		$training = $rowtrainings["TRAINING"];
		
		if (!empty($rowtrainings["LOCATION"]))
			$location = $rowtrainings["LOCATION"];
		else 
			$location = "---";
		
		if (!empty($rowtrainings["DATEFROM"]))
			$datefrom = date("dMY",strtotime($rowtrainings["DATEFROM"]));
		else 
			$datefrom = "";
				
		if (!empty($rowtrainings["DATETO"]))
			$dateto = date("dMY",strtotime($rowtrainings["DATETO"]));
		else 
			$dateto = "";
			
		if (!empty($dateto))
		{
			if (strtotime($rowtrainings["DATEFROM"]) == strtotime($rowtrainings["DATETO"]))
				$dateshow = $datefrom;
			else 
				$dateshow = $datefrom . " to " . $dateto;
		}
		else 
			$dateshow = $datefrom;
			
			
		if ($coursetypecode != $coursetypetmp)
		{
			switch ($coursetypecode)
			{
				case "INHSE"	: $headshow = "<span style=\"font-size:1.4em;font-weight:Bold;text-decoration:underline;\">In-house Trainings</span>"; break;
				case "PRINC"	: $headshow = "<span style=\"font-size:1.4em;font-weight:Bold;text-decoration:underline;\">Principal In-house Trainings</span>"; break;
				case "OUTSIDE"	: $headshow = "<span style=\"font-size:1.4em;font-weight:Bold;text-decoration:underline;\">Outside Trainings</span>"; break;
			}
			
			echo "
			<tr><td colspan=\"2\">&nbsp;</td></tr>
			<tr>
				<td colspan=\"2\">$headshow</td>
			</tr>
			";
		}
		
		// if ($traincode != $traincodetmp)
		// {
			// echo "
			// <tr><td colspan=\"4\" $styleborder>&nbsp;</td></tr>
			// <tr>
				// <td>$cnt.</td>
				// <td colspan=\"3\" style=\"font-size:1.2em;font-weight:Bold;\">$training</td>
			// </tr>
			// <tr><td colspan=\"4\" $stylebordertop>&nbsp;</td></tr>
			// <tr>
				// <td>&nbsp;</td>
				// <td colspan=\"3\">Inclusive Dates :</td>
			// </tr>
			// ";
			
			// $cnt++;
		// }
		
		echo "
			<tr>
				<td colspan=\"2\">&nbsp;</td>
			</tr>
			<tr>
				<td valign=\"middle\">$cnt.</td>
				<td>
					<table style=\"width:100%;font-size:1em;\" cellpading=\"0\" cellspacing=\"0\">
						<tr>
							<td width=\"10%\">Nature</td>
							<td width=\"3%\">:</td>
							<td width=\"75%\"><b>$training</b></td>
						</tr>
						<tr>
							<td>Date</td>
							<td>:</td>
							<td><b>$dateshow</b></td>
						</tr>
						<tr>
							<td>Grade</td>
							<td>:</td>
							<td><b>$grade</b></td>
						</tr>
					</table>
				</td>
			</tr>
		
		";
		
		$typetmp = $type;
		$coursetypetmp = $coursetypecode;
		// $traincodetmp = $traincode;
		
		$cnt++;
	}
	
	

echo "
</div>
";
// include('include/printclose.inc');
echo "
</body>

";

?>