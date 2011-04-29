<?php


include("veritas/connectdb.php");
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
	
if (isset($_GET["traindidno"]))
	$traindidno = $_GET["traindidno"];
else 
	$traindidno = $_POST["traindidno"];

function checkpath($path)
{
	if (is_file($path))
		return true;
	else 
		return false;
}
	
	
function imageScale($image, $newWidth, $newHeight)
{
    if(!$size = @getimagesize($image))
        die("Unable to get info on image $image");
    $ratio = ($size[0] / $size[1]);
    //scale by height
    if($newWidth == -1)
    {
        $ret[1] = $newHeight;
        $ret[0] = round(($newHeight * $ratio));
    }
    else if($newHeight == -1)
    {
        $ret[0] = $newWidth;
        $ret[1] = round(($newWidth / $ratio));
    }
    else
        die("Scale Error");
    return $ret;
} 


if (!empty($applicantno) && !empty($traindidno))
{
	$qrycertificate = mysql_query("SELECT CONCAT(GNAME,' ',LEFT(MNAME,1),'. ',FNAME) AS NAME,c.CREWCODE,
									cs.SIGNATURE1,cs.SIGNATURE2,cs.SIGNATURE3,cs.DOCNO,cs.DATEISSUED,
									ct.IDNO,ct.SCHEDID,th.TRAINCODE,tc.TRAINING,th.DATEFROM,th.DATETO,r.ALIAS2 AS RANKALIAS
									FROM crewtraining ct
									LEFT JOIN trainingschedhdr th ON th.SCHEDID=ct.SCHEDID
									LEFT JOIN trainingcourses tc ON tc.TRAINCODE=th.TRAINCODE
									LEFT JOIN rank r ON r.RANKCODE=ct.RANKCODE
									LEFT JOIN crew c ON c.APPLICANTNO=ct.APPLICANTNO
									LEFT JOIN crewcertstatus cs ON cs.IDNO=ct.CERTIDNO
									WHERE ct.IDNO=$traindidno AND ct.APPLICANTNO=$applicantno
						") or die(mysql_error());
	
	$rowcertificate = mysql_fetch_array($qrycertificate);

	$crewname = $rowcertificate["NAME"];
	$crewcode = $rowcertificate["CREWCODE"];
	$sign1 = $rowcertificate["SIGNATURE1"];
	$sign2 = $rowcertificate["SIGNATURE2"];
	$sign3 = $rowcertificate["SIGNATURE3"];
	$certno = $rowcertificate["DOCNO"];
	
	if (!empty($rowcertificate["DATEISSUED"]))
		$dateissued = date("Y-m-d",strtotime($rowcertificate["DATEISSUED"]));
	else 
		$dateissued = "NULL";
	
	$certidno = $rowcertificate["IDNO"];
	$schedid = $rowcertificate["SCHEDID"];
	$traincode = $rowcertificate["TRAINCODE"];
	$training = $rowcertificate["TRAINING"];
	
	if (!empty($rowcertificate["DATEFROM"]))
		$datefrom = date("F j, Y",strtotime($rowcertificate["DATEFROM"]));
	else 
		$datefrom = "";
		
	if (!empty($rowcertificate["DATETO"]))
		$dateto = date("F j, Y",strtotime($rowcertificate["DATETO"]));
	else 
		$dateto = "";
	
	$rankalias = $rowcertificate["RANKALIAS"];
	
	
	if (!empty($sign1))
	{
		$qryfullname1 = mysql_query("
				SELECT CONCAT(CERTRANK,' ',GNAME,' ',LEFT(MNAME,1),'. ',FNAME) AS NAMESIGN,d.DESIGNATION
				FROM employee e
				LEFT JOIN designation d ON d.DESIGNATIONCODE=e.DESIGNATIONCODE
				WHERE e.EMPLOYEEID = '$sign1'
		") or die(mysql_error());
		
		if (mysql_num_rows($qryfullname1) > 0)
		{
			$rowfullname1 = mysql_fetch_array($qryfullname1);
			$showsign1 = $rowfullname1["NAMESIGN"];
			$showpos1 = $rowfullname1["DESIGNATION"];
		}
		else 
			$showsign1 = "";
	}
	
	if (!empty($sign2))
	{
		$qryfullname2 = mysql_query("
				SELECT CONCAT(CERTRANK,' ',GNAME,' ',LEFT(MNAME,1),'. ',FNAME) AS NAMESIGN,d.DESIGNATION
				FROM employee e
				LEFT JOIN designation d ON d.DESIGNATIONCODE=e.DESIGNATIONCODE
				WHERE e.EMPLOYEEID = '$sign2'
		") or die(mysql_error());
		
		if (mysql_num_rows($qryfullname2) > 0)
		{
			$rowfullname2 = mysql_fetch_array($qryfullname2);
			$showsign2 = $rowfullname2["NAMESIGN"];
			$showpos2 = $rowfullname2["DESIGNATION"];
		}
		else 
			$showsign2 = "";
	}
	
	if (!empty($sign3))
	{
		$qryfullname3 = mysql_query("
				SELECT CONCAT(CERTRANK,' ',GNAME,' ',LEFT(MNAME,1),'. ',FNAME) AS NAMESIGN,d.DESIGNATION
				FROM employee e
				LEFT JOIN designation d ON d.DESIGNATIONCODE=e.DESIGNATIONCODE
				WHERE e.EMPLOYEEID = '$sign3'
		") or die(mysql_error());
		
		if (mysql_num_rows($qryfullname3) > 0)
		{
			$rowfullname3 = mysql_fetch_array($qryfullname3);
			$showsign3 = $rowfullname3["NAMESIGN"];
			$showpos3 = $rowfullname3["DESIGNATION"];
		}
		else 
			$showsign3 = "";
		
	}
	
}

echo "
<html>\n
<head>\n
<title>
Print Certificate
</title>
";
include('include/printclose.inc');
echo "
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>
<script language=\"javascript\" src=\"popcalendar.js\"></script>	

</head>
<body style=\"overflow:auto;\">\n

<div style=\"width:700px;border:10px double Black;\">

		<table style=\"width:100%;height:20px;\">
			<tr>
				<td width=\"20%\" style=\"font-size:0.6em;font-weight:Bold;color:Black;text-align:center;\">VMC Form 234-A<br />September 2000</td>
				<td width=\"60%\" style=\"font-size:1.2em;font-weight:Bold;text-align:center;\">&nbsp;</td>
				<td width=\"20%\" style=\"font-size:0.6em;font-weight:Bold;color:Black;text-align:center;\">Cert. No. $certno</td>
			</tr>
			<tr>
				<td width=\"20%\">&nbsp;</td>
				<td width=\"60%\" style=\"font-size:1.2em;font-weight:Bold;text-align:center;\">VERITAS MARITIME CORPORATION</td>
				<td width=\"20%\">&nbsp;</td>
			</tr>
		</table>
		
		
		";
			
		$normalstyle = "style=\"font-size:0.8em;font-family:Times New Roman;\"";
			
		echo "
		<center>

			<span $normalstyle><i>Presents this</i></span>
			<br />
			<span style=\"font-family:Times New Roman;font-size:1.4em;font-weight:Bold;\"><i>Certificate of Attendance</i></span>
			<br />
			<span $normalstyle><i>to</i></span>
			<br />
			
			<span style=\"font-family:Times New Roman;font-size:1.7em;font-weight:Bold;color:Blue;\">$rankalias  $crewname</span>
			<br />
			
			<span $normalstyle><i>for having succesfully completed the in-house seminar</i></span>
			<br />
			<span $normalstyle><i>for <b>$training</b> in accordance with the</i></span>
			<br />
			<span $normalstyle><i>Seafarers Training, Certification and Watchkeeping</i></span>
			<br />
			<span $normalstyle><i>(STCW '95) Code Section A-11/2</i></span>
			<br />
			";
			
			if (strtotime($datefrom) == strtotime($dateto))
				$traindate =  "<i>Date: </i><b><u>$datefrom</u></b>";
			else 
				$traindate =  "<i>From</i> <b>$datefrom</b> <i>to</i> <b>$dateto</b>";
			
			echo "
			<span $normalstyle> $traindate</span>
			<br />
		
		</center>
		
		<br />
		";
			
		$modresult = 0;
		$qrymodules = mysql_query("SELECT MODULEID,MODULE FROM traincoursemodules WHERE TRAINCODE='$traincode'
									ORDER BY MODULEID
								") or die(mysql_error());
		
		if (mysql_num_rows($qrymodules) > 0)
		{
			$modresult = 1;
			echo "
			<div style=\"width:100%;height:155px;background-color:White;overflow:hidden;\">
				<center>
				<table style=\"width:50%;\">
				";
				
				$stylemodules = "style=\"font-family:Times New Roman;font-size:0.7em;font-weight:Bold;\"";
				
				while ($rowmodules = mysql_fetch_array($qrymodules))
				{
					$moduleid = $rowmodules["MODULEID"];
					
					switch ($rowmodules["MODULEID"])
					{
						case "1"	:	$moduleid = "I. "; break;
						case "2"	:	$moduleid = "II. "; break;
						case "3"	:	$moduleid = "III. "; break;
						case "4"	:	$moduleid = "IV. "; break;
						case "5"	:	$moduleid = "V. "; break;
						case "6"	:	$moduleid = "VI. "; break;
						case "7"	:	$moduleid = "VII. "; break;
						case "8"	:	$moduleid = "VIII. "; break;
						case "9"	:	$moduleid = "IX. "; break;
						case "10"	:	$moduleid = "X. "; break;
					}
					
					$module = $rowmodules["MODULE"];
					
					echo "
					<tr>
						<td width=\"20%\" $stylemodules align=\"right\">$moduleid</td>
						<td width=\"3%\">&nbsp;</td>
						<td width=\"77%\" $stylemodules><i>$module</i></td>
					</tr>
					";
					
				}
				
				echo "
				</table>
				</center>
			</div>
			<br />
			";
		}
		else 
		{
			echo "
			<div style=\"width:100%;height:50px;background-color:White;\">
			
			</div>
			";
		}
		
		if (!empty($dateissued))
		{
			$showdate1 = date("jS",strtotime($dateissued));
			$showdate2 = date("F Y",strtotime($dateissued));
			
			$showdate = $showdate1 . " day of " . $showdate2;
		}
		else 
			$showdate = "";
			
		echo "
		<center>
			<span $normalstyle><i>ISSUED at Manila, Philippines this <b>$showdate</b></i>. </span>
		</center>

		";
		
		$stylesign = "style=\"font-family:Times New Roman;font-size:0.8em;font-weight:Bold;text-align:center;\"";
		
		if ($modresult == 1)
			echo "
			<br />
			";
		else 
			echo "
			<br /><br /><br /><br /><br /><br />
			";
		
		echo "
		<table width=\"100%\">
			<tr>
				<td width=\"33%\" $stylesign>
					<i>$showsign1</i> <br />
					<span style=\"font-size:0.7em;\">$showpos1</span>
					
				</td>
				<td width=\"33%\" $stylesign>
					<i>$showsign2</i> <br />
					<span style=\"font-size:0.7em;\">$showpos2</span>
				</td>
				<td width=\"33%\" $stylesign>
					<i>$showsign3</i> <br />
					<span style=\"font-size:0.7em;\">$showpos3</span>
				</td>
			</tr>
		</table>

		<br />

</div>";


echo "

</body>

</html>

";

?>