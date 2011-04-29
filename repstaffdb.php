<?php

include("veritas/connectdb.php");
include("veritas/include/functions.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');
$datenow = date('d F Y');
$basedir = "staffpics/";
//$basedirdocs = "staffpics/";

if(isset($_SESSION["employeeid"]))
	$employeeid=$_SESSION["employeeid"];

//if (isset($_POST["actiontxt"]))
//	$actiontxt = $_POST["actiontxt"];
	
if (isset($_GET["staffid"]))
	$staffid = $_GET["staffid"];
else 
	$staffid = $_POST["staffid"];

if (isset($_GET["print"]))
	$print = $_GET["print"];
else 
	$print = 0;

//include("include/datasheet.inc");

if(!empty($staffid))
{
	$qrystaff = mysql_query("SELECT s.*,d.DESIGNATION
							FROM staffhdr s
							LEFT JOIN designation d ON d.DESIGNATIONCODE=s.POSITION 
							WHERE STAFFID=$staffid
						") or die(mysql_error());
	
	$rowstaff = mysql_fetch_array($qrystaff);
	
		$fname = $rowstaff["FNAME"];
		$gname = $rowstaff["GNAME"];
		$mname = $rowstaff["MNAME"];
		
		$initial = substr($gname,0,1) . substr($mname,0,1) . substr($fname,0,1);
		
		$fullname = $fname . ", " . $gname . " " . $mname;
		
		$positioncode = $rowstaff["POSITION"];
		$designation = $rowstaff["DESIGNATION"];
		
		$address = $rowstaff["ADDRESS"];
		if ($rowstaff["BIRTHDATE"] != "")
			$birthdate = date("m/d/Y",strtotime($rowstaff["BIRTHDATE"]));
		else 
			$birthdate = "";
		$birthplace = $rowstaff["BIRTHPLACE"];
		$civilstatus = $rowstaff["CIVILSTATUS"];
		
		switch ($civilstatus)
		{
			case "S" : $statusshow = "SINGLE"; break;
			case "M" : $statusshow = "MARRIED"; break;
			case "W" : $statusshow = "WIDOW"; break;
			case "P" : $statusshow = "SEPARATED"; break;
		}
		
		$religion = $rowstaff["RELIGION"];
		$gender = $rowstaff["GENDER"];
		
		if ($gender == "M")
			$gendershow = "MALE";
		else 
			$gendershow = "FEMALE";
		
		$height = $rowstaff["HEIGHT"];
		$weight = $rowstaff["WEIGHT"];
		$telno = $rowstaff["TELNO"];
		$celphone = $rowstaff["CELNO"];
		$email = $rowstaff["EMAIL"];
		$vmcemail = $rowstaff["VMCEMAIL"];
		
		$qrydepartment = mysql_query("SELECT d.DEPARTMENT
									FROM employee e
									LEFT JOIN department d ON d.DEPARTMENTID=e.DEPARTMENTID
									WHERE e.EMPLOYEEID='$initial' 
							") or die(mysql_error());
		
		$rowdepartment = mysql_fetch_array($qrydepartment);
		
		$department = $rowdepartment["DEPARTMENT"];
	
}

$styleborder="style=\"border-bottom:1px solid black;\"";

echo "
<html>\n
<head>\n
<title>
Staff Profile
</title>

<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>
<script language=\"javascript\" src=\"popcalendar.js\"></script>	

</head>
<body style=\"overflow:auto;\">\n

<div style=\"width:755px;background-color:white;\">

	<div style=\"width:75%;height:60px;float:left;background-color:White;overflow:hidden;\">
		<center>
			<span style=\"font-size:1em;font-weight:Bold;\">VERITAS MARITIME CORPORATION</span><br />
			<span style=\"font-size:0.8em;font-weight:Bold;\">STAFF PROFILE</span><br />
			<span style=\"font-size:0.7em;font-weight:Bold;\">Date: $datenow</span><br />
		</center>
	</div>
	";

	echo "
	<!--
	<div style=\"width:25%;height:60px;float:right;background-color:White;\">

		<table width=\"90%\" class=\"listrow\" >
			<tr>
				<th>Applicant No.</th>
				<th>:</th>
				<td><b>$applicantno</b></td>
			</tr>
			<tr>
				<th>Crew Code</th>
				<th>:</th>
				<td><b>$crewcode</b></td>
			</tr>
		</table>
	</div>
	-->

	<div style=\"width:755px;height:100px;overflow:hidden;\">

		<div style=\"width:550px;height:100%;float:left;overflow:hidden;\">
			<table class=\"listrow\" width=\"100%\">
				<tr>
					<th width=\"35%\">Employee Name</th>
					<th>:</th>
					<td valign=\"top\" $styleborder>$fullname</td>
				</tr>
				<tr>
					<th>Position</th>
					<th>:</th>
					<td valign=\"top\" $styleborder>$designation</td>
				</tr>
				<tr>
					<th>Department</th>
					<th>:</th>
					<td valign=\"top\" $styleborder>$department</td>
				</tr>
			</table>
		</div>
		
		<div style=\"width:130px;height:100%;float:right;background-color:White;overflow:hidden;\">
	";
			$dirfilename = "$basedir/$initial.JPG";
			if (checkpath($dirfilename))
			{
				$scale = imageScale($dirfilename,-1,98);
				$width = $scale[0];
				$height = $scale[1];
				
	echo "			<center><img src=\"$dirfilename\" width=\"$width\" height=\"$height\" border=\"1\" /></center> ";
			}
			else 
			{
	echo "			<center><b>[NO PICTURE]</b></center>";
			}
	echo "
		</div>
	</div>
	
	<div style=\"width:550px;height:150px;float:left;overflow:hidden;\">
		<table class=\"listrow\" width=\"100%\">
			<tr>
				<th colspan=\"3\" align=\"left\"><u>PERSONAL DATA</u></th>
			</tr>
			<tr>
				<th>Address</th>
				<th>:</th>
				<td $styleborder>$address</td>
			</tr>
			<tr>
				<th>Telephone No.</th>
				<th>:</th>
				<td $styleborder>$telno</td>
			</tr>
			<tr>
				<th>Mobile No.</th>
				<th>:</th>
				<td $styleborder>$celphone</td>
			</tr>
			<tr>
				<th width=\"20%\">Date of Birth</th>
				<th>:</th>
				<td $styleborder>$birthdate</td>
			</tr>
			<tr>
				<th width=\"20%\">Place of Birth</th>
				<th>:</th>
				<td $styleborder>$birthplace</td>
			</tr>
			<tr>
				<th width=\"20%\">Email</th>
				<th>:</th>
				<td $styleborder>$email</td>
			</tr>
		</table>
	</div>
	<div style=\"width:200px;height:150px;float:left;background-color:White;overflow:hidden;\">
		<table class=\"listrow\" width=\"95%\">
			<tr>
				<th colspan=\"3\">&nbsp;</th>
			</tr>
			<tr>
				<th>Gender</th>
				<th>:</th>
				<td $styleborder>$gendershow</td>
			</tr>
			<tr>
				<th>Civil Status</th>
				<th>:</th>
				<td $styleborder>$statusshow</td>
			</tr>
			<tr>
				<th>Religion</th>
				<th>:</th>
				<td $styleborder>$religion</td>
			</tr>
			<tr>
				<th>Height(cm)</th>
				<th>:</th>
				<td $styleborder>$height</td>
			</tr>
			<tr>
				<th>Weight(kg)</th>
				<th>:</th>
				<td $styleborder>$weight</td>
			</tr>
			<tr>
				<th>VMC Email</th>
				<th>:</th>
				<td $styleborder>$vmcemail</td>
			</tr>
		</table>
	</div>
	";
	
	$qryeducation = mysql_query("SELECT * FROM staffeducation WHERE STAFFID=$staffid") or die(mysql_error());
	
	$roweducation = mysql_fetch_array($qryeducation);
	
	$college = $roweducation["COLLEGE"];
	$degree = $roweducation["DEGREE"];
	
	if (!empty($roweducation["COLYEARGRAD"]))
		$colyeargrad = date("Y",strtotime($roweducation["COLYEARGRAD"]));
	else 
		$colyeargrad = "---";
		
	$highschool = $roweducation["HIGHSCHOOL"];
	
	if (!empty($roweducation["HSYEARGRAD"]))
		$hsyeargrad = date("Y",strtotime($roweducation["HSYEARGRAD"]));
	else 
		$hsyeargrad = "---";
		
	$elementary = $roweducation["ELEMENTARY"];
	
	if (!empty($roweducation["ELEMYEARGRAD"]))
		$elemyeargrad = date("Y",strtotime($roweducation["ELEMYEARGRAD"]));
	else 
		$elemyeargrad = "---";
		
	$postgradschool = $roweducation["POSTGRADSCHOOL"];
	$postgradcourse = $roweducation["POSTGRADCOURSE"];
	
	
	echo "
	<div style=\"width:755px;background-color:White;overflow:hidden;\">
		<table class=\"listrow\" style=\"width:55%;float:left;\">
			<tr>
				<th align=\"left\"><u>EDUCATION</u></th>
			</tr>
			<tr>
				<th>College</th>
				<th>:</th>
				<td $styleborder>$college</td>
			</tr>
			<tr>
				<th>Degree</th>
				<th>:</th>
				<td $styleborder>$degree</td>
			</tr>
			<tr>
				<th>Year Graduated</th>
				<th>:</th>
				<td $styleborder>$colyeargrad</td>
			</tr>
		</table>
		<table class=\"listrow\" style=\"width:44%;float:left;\">
			<tr>
				<th>Highschool</th>
				<th>:</th>
				<td $styleborder>$highschool</td>
			</tr>
			<tr>
				<th>Yr. Grad</th>
				<th>:</th>
				<td $styleborder>$hsyeargrad</td>
			</tr>
			<tr>
				<th>Post Grad School</th>
				<th>:</th>
				<td $styleborder>$postgradschool</td>
			</tr>
			<tr>
				<th>Post Grad Course</th>
				<th>:</th>
				<td $styleborder>$postgradcourse</td>
			</tr>
		</table>
	</div>
	<br />
	<div style=\"width:755px;background-color:White;overflow:hidden;\">
		
		<table width=\"80%\" cellspacing=\"0\" cellpadding=\"1\" style=\"font-size:0.7em;\">
			<tr>
				<th colspan=\"5\" align=\"left\"><u>EMPLOYMENT</u></th>
			</tr>
			<tr>
				<td align=\"left\"><i>Company</i></td>
				<td align=\"left\"><i>Position</i></td>
				<td align=\"left\"><i>From</i></td>
				<td align=\"left\"><i>To</i></td>
				<td align=\"left\"><i>Reason</i></td>
			</tr>
	";
		
		$qryexperience = mysql_query("SELECT * FROM staffemployment WHERE STAFFID=$staffid ORDER BY DATEFROM DESC") or die(mysql_error());
		
		while($rowexperience = mysql_fetch_array($qryexperience))
		{
			$company = $rowexperience["COMPANY"];
			$position = $rowexperience["POSITION"];
			$datefrom = $rowexperience["DATEFROM"];
			
			if (!empty($rowexperience["DATEFROM"]))
				$datefrom = date("dMY",strtotime($rowexperience["DATEFROM"]));
			else 
				$datefrom = "";
			
			$rankalias = $rowexperience["DATETO"];
			
			if (!empty($rowexperience["DATETO"]))
				$dateto = date("dMY",strtotime($rowexperience["DATETO"]));
			else 
				$dateto = "";
				
			$reason = $rowexperience["REASON"];
			
			
			echo "
			<tr>
				<td align=\"left\">$company</td>
				<td align=\"left\" title=\"\">$position</td>
				<td align=\"left\">&nbsp;$datefrom</td>
				<td align=\"left\">&nbsp;$dateto</td>
				<td align=\"left\">&nbsp;$reason</td>
			</tr>
			";
		}

	echo "
		</table>
	
	</div>
	<br />
	<div style=\"width:755px;background-color:White;overflow:hidden;\">
		
		<table cellspacing=\"0\" cellpadding=\"1\" style=\"font-size:0.7em;width:33%;float:left;border-right:1px solid gray;\" >   <!-- COURSES  -->
			<tr>
				<th colspan=\"2\" align=\"left\"><u>COURSES TAKEN</u></th>
			</tr>
			<tr>
				<td><i>Course</i></td>
				<td><i>Date</i></td>
			</tr>
			";
	
			$qrycourses = mysql_query("SELECT * FROM staffcourses WHERE STAFFID=$staffid") or die(mysql_error());
			
			while ($rowcourses = mysql_fetch_array($qrycourses))
			{
				$coursename = $rowcourses["COURSENAME"];
				
				if (!empty($rowcourses["DATETAKEN"]))
					$datetaken = date("dMY",strtotime($rowcourses["DATETAKEN"]));
				else 
					$datetaken = "---";
				
				echo "
				<tr>
					<td>$coursename</td>
					<td>$datetaken</td>
				</tr>
				";
			}
	
		echo "
		</table>
		
		<table cellspacing=\"0\" cellpadding=\"1\" style=\"font-size:0.7em;width:33%;float:left;border-right:1px solid gray;\" >   <!-- EXAMINATION  -->
			<tr>
				<th colspan=\"2\" align=\"left\"><u>EXAMINATIONS</u></th>
			</tr>
			<tr>
				<td><i>Exam</i></td>
				<td><i>Date taken</i></td>
			</tr>
		";
	
			$qryexamination = mysql_query("SELECT * FROM staffexamination WHERE STAFFID=$staffid") or die(mysql_error());
			
			while ($rowexamination = mysql_fetch_array($qryexamination))
			{
				$examination = $rowexamination["EXAMINATION"];
				
				if (!empty($rowexamination["DATETAKEN"]))
					$examdate = date("dMY",strtotime($rowexamination["DATETAKEN"]));
				else 
					$examdate = "---";
				
				echo "
				<tr>
					<td>$examination</td>
					<td>$examdate</td>
				</tr>
				";
			}
		
		echo "
		</table>
		
		<table cellspacing=\"0\" cellpadding=\"1\" style=\"font-size:0.7em;width:33%;float:left;\" >   <!-- HONORS   -->
			<tr>
				<th colspan=\"2\" align=\"left\"><u>HONORS</u></th>
			</tr>
			<tr>
				<td><i>Honor</i></td>
				<td><i>Date given</i></td>
			</tr>
		";
	
			$qryhonors = mysql_query("SELECT * FROM staffhonors WHERE STAFFID=$staffid") or die(mysql_error());
			
			while ($rowhonors = mysql_fetch_array($qryhonors))
			{
				$honor = $rowhonors["HONOR"];
				
				if (!empty($rowhonors["DATEGIVEN"]))
					$honordate = date("dMY",strtotime($rowhonors["DATEGIVEN"]));
				else 
					$honordate = "---";
				
				echo "
				<tr>
					<td>$honor</td>
					<td>$honordate</td>
				</tr>
				";
			}
		
		echo "
		</table>
		
	</div>
";
	
	
echo "

</div>";

if ($print == 1)
	include('include/printclose.inc');

echo "

</body>

</html>

";

?>