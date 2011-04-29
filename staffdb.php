<?php

include('veritas/connectdb.php');
include("veritas/include/functions.inc");

$currentdate = date('Y-m-d H:i:s');

$basedir = "staffpics/";

if (isset($_GET['employeeid']))
	$employeeid = $_GET['employeeid'];
	
	
if (isset($_POST['staffid']))
	$staffid = $_POST['staffid'];
	
if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];	

if (isset($_POST['section']))
	$section = $_POST['section'];	

if (isset($_POST['positioncode']))
	$positioncode = $_POST['positioncode'];	

if (isset($_POST['fname']))
	$fname = $_POST['fname'];	

if (isset($_POST['gname']))
	$gname = $_POST['gname'];	

if (isset($_POST['mname']))
	$mname = $_POST['mname'];	

if (isset($_POST['address']))
	$address = $_POST['address'];	

if (isset($_POST['birthdate']))
	$birthdate = $_POST['birthdate'];	

if (isset($_POST['birthplace']))
	$birthplace = $_POST['birthplace'];	

if (isset($_POST['civilstatus']))
	$civilstatus = $_POST['civilstatus'];
	
$chkcivilS = "";
$chkcivilM = "";
$chkcivilW = "";
$chkcivilP = "";

switch ($civilstatus)
{
	case "S" : $chkcivilS = "checked=\"checked\""; break;
	case "M" : $chkcivilM = "checked=\"checked\""; break;
	case "W" : $chkcivilW = "checked=\"checked\""; break;
	case "P" : $chkcivilP = "checked=\"checked\""; break;
}


if (isset($_POST['religion']))
	$religion = $_POST['religion'];	

if (isset($_POST['gender']))
	$gender = $_POST['gender'];	
	
$chkgenderF = "";
$chkgenderM = "";

switch ($gender)
{
	case "M" : $chkgenderM = "checked=\"checked\"";
	case "F" : $chkgenderF = "checked=\"checked\"";
}
	
//if ($gender == "M")
//{
//	$chkgenderM = "checked=\"checked\"";
//	$chkgenderF = "";
//}
//else 
//{
//	$chkgenderM = "";
//	$chkgenderF = "checked=\"checked\"";
//}

if (isset($_POST['height']))
	$height = $_POST['height'];	

if (isset($_POST['weight']))
	$weight = $_POST['weight'];	

if (isset($_POST['telno']))
	$telno = $_POST['telno'];	

if (isset($_POST['celphone']))
	$celphone = $_POST['celphone'];	

if (isset($_POST['email']))
	$email = $_POST['email'];	

//EMPLOYMENT
	
if (isset($_POST['company']))
	$company = $_POST['company'];	

if (isset($_POST['companyposition']))
	$companyposition = $_POST['companyposition'];	

if (isset($_POST['compdatefrom']))
	$compdatefrom = $_POST['compdatefrom'];	

if (isset($_POST['compdateto']))
	$compdateto = $_POST['compdateto'];	

if (isset($_POST['compreason']))
	$compreason = $_POST['compreason'];	

//EDUCATION

if (isset($_POST['college']))
	$college = $_POST['college'];	

if (isset($_POST['collegedegree']))
	$collegedegree = $_POST['collegedegree'];	

if (isset($_POST['colyeargrad']))
	$colyeargrad = $_POST['colyeargrad'];	

if (isset($_POST['highschool']))
	$highschool = $_POST['highschool'];	

if (isset($_POST['hsyeargrad']))
	$hsyeargrad = $_POST['hsyeargrad'];	

if (isset($_POST['elementary']))
	$elementary = $_POST['elementary'];	

if (isset($_POST['elemyeargrad']))
	$elemyeargrad = $_POST['elemyeargrad'];	

if (isset($_POST['postgradschool']))
	$postgradschool = $_POST['postgradschool'];	

if (isset($_POST['postgradstudies']))
	$postgradstudies = $_POST['postgradstudies'];	

//COURSES

if (isset($_POST['course']))
	$course = $_POST['course'];	

if (isset($_POST['coursedate']))
	$coursedate = $_POST['coursedate'];	

//EXAMINATIONS

if (isset($_POST['examination']))
	$examination = $_POST['examination'];	

if (isset($_POST['examdate']))
	$examdate = $_POST['examdate'];	

//HONORS

if (isset($_POST['honor']))
	$honor = $_POST['honor'];	

if (isset($_POST['honordate']))
	$honordate = $_POST['honordate'];	

	
if (isset($_POST["searchkey"]))
	$searchkey = $_POST["searchkey"];
	
if (isset($_POST["searchby"]))
	$searchby = $_POST["searchby"];
	
$showmultiple = "visibility:hidden;";
$multiple = 0;
	
	
if ($staffid == "")
{
	if ($fname != "")
	{
		if (!empty($birthdate))
			$birthdateraw = "'" .date("Y-m-d",strtotime($birthdate)) . "'";
		else 
			$birthdateraw = "NULL";
		
		//INSERT ENTRY INTO staffhdr table
		$qrystaffinsert = mysql_query("INSERT INTO staffhdr(STAFFID,POSITION,FNAME,GNAME,MNAME,ADDRESS,BIRTHDATE,
							BIRTHPLACE,CIVILSTATUS,RELIGION,GENDER,HEIGHT,WEIGHT,TELNO,CELNO,EMAIL,STATUS) 
							VALUES(NULL,'$positioncode','$fname','$gname','$mname','$address',$birthdateraw,'$birthplace',
							'$civilstatus','$religion','$gender','$height','$weight','$telno','$celphone','$email',1)") or die(mysql_error());
		
		$qrygetstaffid = mysql_query("SELECT staffid FROM staffhdr WHERE FNAME='$fname' AND GNAME='$gname' AND MNAME='$mname'") or die(mysql_error());
		
		$rowgetstaffid = mysql_fetch_array($qrygetstaffid);
		$staffid = $rowgetstaffid["STAFFID"];
	}
}	

	
switch ($actiontxt)
{
	case "createnew":
		
			$fname = "";
			$gname = "";
			$mname = "";
			$positioncode = "";
			$address = "";
			$birthdate = "";
			$birthplace = "";
			$civilstatus = "";
			$religion = "";
			$gender = "";
			$height = "";
			$weight = "";
			$telno = "";
			$celphone = "";
			$email = "";
				
			$college = "";
			$collegedegree = "";
			$colyeargrad = "";
			$highschool = "";
			$hsyeargrad = "";
			$elementary = "";
			$elemyeargrad = "";
			$postgradschool = "";
			$postgradstudies = "";
			
			$staffid = "";

		break;
	
	case "save"		:
		
			if (!empty($birthdate))
				$birthdateraw = "'" . date("Y-m-d",strtotime($birthdate)) . "'";
			else 
				$birthdateraw = "NULL";
		
			$qrystaffupdate = mysql_query("UPDATE staffhdr SET POSITION = '$positioncode',
															ADDRESS = '$address',
															BIRTHDATE = $birthdateraw,
															BIRTHPLACE = '$birthplace',
															CIVILSTATUS = '$civilstatus',
															RELIGION = '$religion',
															GENDER = '$gender',
															HEIGHT = '$height',
															WEIGHT = '$weight',
															TELNO = '$telno',
															CELNO = '$celphone',
															EMAIL = '$email'
											WHERE STAFFID=$staffid;
										") or die(mysql_error());
			
			$qryeducverify = mysql_query("SELECT STAFFID FROM staffeducation WHERE STAFFID=$staffid ") or die(mysql_error());
			
			if (!empty($colyeargrad))
				$colyeargradraw = "'" . date("Y-m-d",strtotime($colyeargrad)) . "'";
			else 
				$colyeargradraw = "NULL";
			
			if (!empty($hsyeargrad))
				$hsyeargradraw = "'" . date("Y-m-d",strtotime($hsyeargrad)) . "'";
			else 
				$hsyeargradraw = "NULL";
			
			if (!empty($elemyeargrad))
				$elemyeargradraw = "'" . date("Y-m-d",strtotime($elemyeargrad)) . "'";
			else 
				$elemyeargradraw = "NULL";
			
			if (mysql_num_rows($qryeducverify) == 0)
			{
			
				$qryeducationinsert = mysql_query("INSERT INTO staffeducation(STAFFID,COLLEGE,DEGREE,COLYEARGRAD,HIGHSCHOOL,HSYEARGRAD,
													ELEMENTARY,ELEMYEARGRAD,POSTGRADSCHOOL,POSTGRADCOURSE) 
													VALUES($staffid,'$college','$collegedegree',$colyeargradraw,'$highschool',$hsyeargradraw,
													'$elementary',$elemyeargradraw,'$postgradschool','$postgradstudies')
													") or die(mysql_error());
			}
			else 
			{
				$qryeducationupdate = mysql_query("UPDATE staffeducation SET COLLEGE = '$college',
																			DEGREE= '$collegedegree',
																			COLYEARGRAD = $colyeargradraw,
																			HIGHSCHOOL = '$highschool',
																			HSYEARGRAD = $hsyeargradraw,
																			ELEMENTARY = '$elementary',
																			ELEMYEARGRAD = $elemyeargradraw,
																			POSTGRADSCHOOL = '$postgradschool',
																			POSTGRADCOURSE = '$postgradstudies'
													WHERE STAFFID=$staffid
													") or die(mysql_error());
				
			}
			
			
			

			
		break;
	
	case "addlist"	:
			
			switch ($section)
			{
				case "employment"	:
					
						if (!empty($compdatefrom))
							$compdatefromraw = "'" . date('Y-m-d',strtotime($compdatefrom)) . "'";
						else 
							$compdatefromraw = "NULL";
					
						if (!empty($compdateto))
							$compdatetoraw = "'" . date('Y-m-d',strtotime($compdateto)) . "'";
						else 
							$compdatetoraw = "NULL";
					
						$qryemploymentinsert = mysql_query("INSERT INTO staffemployment(STAFFID,COMPANY,POSITION,DATEFROM,DATETO,REASON) 
													VALUES($staffid,'$company','$companyposition',$compdatefromraw,$compdatetoraw,'$compreason')") or die(mysql_error());
					
						$company = "";
						$companyposition = "";
						$compdatefrom = "";
						$compdateto = "";
						$compreason = "";
						
					break;
					
				case "courses"	:
					
						if (!empty($coursedate))
							$coursedateraw = "'" . date('Y-m-d',strtotime($coursedate)) . "'";
						else 
							$coursedateraw = "NULL";
					
						$qrycourseinsert = mysql_query("INSERT INTO staffcourses(STAFFID,COURSENAME,DATETAKEN)
													VALUES($staffid,'$course',$coursedateraw)") or die(mysql_error());
						
						$course = "";
						$coursedate = "";
					
					break;
				case "examination"	:
					
						if (!empty($examdate))
							$examdateraw = "'" . date('Y-m-d',strtotime($examdate)) . "'";
						else 
							$examdateraw = "NULL";
					
						$qryexaminationinsert = mysql_query("INSERT INTO staffexamination (STAFFID,EXAMINATION,DATETAKEN)
													VALUES($staffid,'$examination',$examdateraw)") or die(mysql_error());
						
						$examination = "";
						$examdate = "";
					
					break;
				case "honors"	:
					
						if (!empty($honordate))
							$honordateraw = "'" . date('Y-m-d',strtotime($honordate)) . "'";
						else 
							$honordateraw = "NULL";
					
						$qryhonorinsert = mysql_query("INSERT INTO staffhonors(STAFFID,HONOR,DATEGIVEN) 
													VALUES($staffid,'$honor',$honordateraw)") or die(mysql_error());
						
						$honor = "";
						$honordate = "";
					
					break;
				
			}
			
		
		break;
		
	case "find"		:
		
			
			switch ($searchby)
			{
				case "1"	: //FAMILY NAME
					
						$qrysearch = mysql_query("SELECT STAFFID FROM staffhdr WHERE FNAME LIKE '$searchkey%' ORDER BY FNAME") or die(mysql_error());
					
					break;
				case "2"	: //GIVEN NAME
					
						$qrysearch = mysql_query("SELECT STAFFID FROM staffhdr WHERE GNAME LIKE '$searchkey%' ORDER BY GNAME") or die(mysql_error());
					
					break;
				case "3"	: //STAFF ID
					
						$qrysearch = mysql_query("SELECT STAFFID FROM staffhdr WHERE STAFFID LIKE '$searchkey%' ORDER BY STAFFID") or die(mysql_error());
					
					break;
			}
		
			if (mysql_num_rows($qrysearch) == 1)  //SINGLE ENTRY FOUND
			{
				$rowsearch = mysql_fetch_array($qrysearch);
				$staffid = $rowsearch["STAFFID"];
			}
			elseif (mysql_num_rows($qrysearch) > 1)  //MULTIPLE ENTRY FOUND
			{
				$showmultiple = "visibility:show;";
				$multiple = 1;
			}
			else 
			{
				$staffid="";
				$errormsg = "Search Key -- '$searchkey' Not Found.";
			}
			
		
		break;
		
	case "closewindow"	:
		
			$showmultiple = "visibility:hidden;";
			$multiple = 0;
		
		break;
	
}
	
if (!empty($staffid))
{
	if ($actiontxt == "closewindow" || $actiontxt == "find")
	{
		$qrystaff = mysql_query("SELECT * FROM staffhdr WHERE STAFFID=$staffid") or die(mysql_error());
		$rowstaff = mysql_fetch_array($qrystaff);
		
			$fname = $rowstaff["FNAME"];
			$gname = $rowstaff["GNAME"];
			$mname = $rowstaff["MNAME"];
			
			$initial = substr($gname,0,1) . substr($mname,0,1) . substr($fname,0,1);
			
			$positioncode = $rowstaff["POSITION"];
			$address = $rowstaff["ADDRESS"];
			if ($rowstaff["BIRTHDATE"] != "")
				$birthdate = date("m/d/Y",strtotime($rowstaff["BIRTHDATE"]));
			else 
				$birthdate = "";
			$birthplace = $rowstaff["BIRTHPLACE"];
			$civilstatus = $rowstaff["CIVILSTATUS"];
			
			switch ($civilstatus)
			{
				case "S" : $chkcivilS = "checked=\"checked\""; break;
				case "M" : $chkcivilM = "checked=\"checked\""; break;
				case "W" : $chkcivilW = "checked=\"checked\""; break;
				case "P" : $chkcivilP = "checked=\"checked\""; break;
			}
			
			$religion = $rowstaff["RELIGION"];
			$gender = $rowstaff["GENDER"];
			
			if ($gender == "M")
			{
				$chkgenderM = "checked=\"checked\"";
				$chkgenderF = "";
			}
			else 
			{
				$chkgenderM = "";
				$chkgenderF = "checked=\"checked\"";
			}
			
			$height = $rowstaff["HEIGHT"];
			$weight = $rowstaff["WEIGHT"];
			$telno = $rowstaff["TELNO"];
			$celphone = $rowstaff["CELNO"];
			$email = $rowstaff["EMAIL"];
		
		$qrystaffeduc = mysql_query("SELECT * FROM staffeducation WHERE STAFFID=$staffid") or die(mysql_error());
		$rowstaffeduc = mysql_fetch_array($qrystaffeduc);
				
			$college = $rowstaffeduc["COLLEGE"];
			$collegedegree = $rowstaffeduc["DEGREE"];
			
			if ($rowstaffeduc["COLYEARGRAD"] != "")
				$colyeargrad = date("m/d/Y",$rowstaffeduc["COLYEARGRAD"]);
			else 
				$colyeargrad = "";
				
			$highschool = $rowstaffeduc["HIGHSCHOOL"];
			
			if ($rowstaffeduc["HSYEARGRAD"] != "")
				$hsyeargrad = date("m/d/Y",$rowstaffeduc["HSYEARGRAD"]);
			else 
				$hsyeargrad = "";
				
			$elementary = $rowstaffeduc["ELEMENTARY"];
			
			if ($rowstaffeduc["ELEMYEARGRAD"] != "")
				$elemyeargrad = date("m/d/Y",$rowstaffeduc["ELEMYEARGRAD"]);
			else 
				$elemyeargrad = "";
				
			$postgradschool = $rowstaffeduc["POSTGRADSCHOOL"];
			$postgradstudies = $rowstaffeduc["POSTGRADCOURSE"];
	}
	
	$qryemployment = mysql_query("SELECT * FROM staffemployment WHERE STAFFID=$staffid") or die(mysql_error());
	$qrycourses = mysql_query("SELECT COURSENAME,DATETAKEN FROM staffcourses WHERE STAFFID=$staffid") or die(mysql_error());
	$qryexamination = mysql_query("SELECT EXAMINATION,DATETAKEN FROM staffexamination WHERE STAFFID=$staffid") or die(mysql_error());
	$qryhonors = mysql_query("SELECT HONOR,DATEGIVEN FROM staffhonors WHERE STAFFID=$staffid") or die(mysql_error());
}

	

$qrydesignation = mysql_query("SELECT DESIGNATIONCODE,DESIGNATION FROM designation") or die(mysql_error());

echo "
<html>
<head>
<title>Staff Database</title>

<script language=\"javascript\" src=\"popcalendar.js\"></script>	
<script language=\"javascript\" src=\"veripro.js\"></script>	

<link rel=\"stylesheet\" href=\"veripro.css\">

</head>
<body style=\"overflow:auto;\">

<form name=\"staffdb\" method=\"POST\">\n

<span class=\"wintitle\">STAFF DATABASE</span>

<center>
<div style=\"width:90%;background-color:White;overflow:auto;padding:0 10px 0 10px;border:1px solid black;\">
	<br />
	<div style=\"width:100%;\">
		<table width=\"100%\" style=\"font-size:0.8em;font-weight:Bold;\">
			<tr>
				<th align=\"left\">Search Key <br />
					<select name=\"searchby\" onchange=\"searchkey.value='';searchkey.focus();\">
						<option value=\"\">--Select Search Key--</option>
					";
	
					$selected1 = "";
					$selected2 = "";
					$selected3 = "";
	
					switch ($searchby)
					{
						case "1"	: //BY FAMILY NAME
								$selected1 = "SELECTED";
							break;
						case "2"	: //BY GIVEN NAME
								$selected2 = "SELECTED";
							break;
						case "3"	: //BY STAFF ID
								$selected3 = "SELECTED";
							break;
					}
	
				echo "
						<option $selected1 value=\"1\">FAMILY NAME</option>
						<option $selected2 value=\"2\">GIVEN NAME</option>
						<option $selected3 value=\"3\">STAFF ID</option>
					</select>
				</th>
				<td valign=\"bottom\"><input type=\"text\" name=\"searchkey\" size=\"40\" value=\"$searchkey\" onkeyup=\"this.value=this.value.toUpperCase()\" 
						style=\"color:Red;font-weight:Bold;\" onkeydown=\"if(event.keyCode==13) {btnfind.focus();}\"/>
						
					<input type=\"button\" value=\"Find\" name=\"btnfind\" onclick=\"actiontxt.value='find';submit();\" />
				</td>
				<td valign=\"bottom\">
					<input type=\"button\" onclick=\"actiontxt.value='save';submit();\" value=\"Save All Changes\" 
						style=\"border:2px solid Red;background-color:Black;color:Yellow;font-weight:Bold;\" />
				</td>
				<td valign=\"bottom\">
					<input type=\"button\" onclick=\"actiontxt.value='createnew';submit();\" value=\"Create New\" 
						style=\"border:2px solid Black;background-color:Green;color:White;font-weight:Bold;\" />
				</td>
				<td valign=\"bottom\">
					<input type=\"button\" onclick=\"openWindow('repstaffdb.php?staffid=$staffid&print=1', 'repstaffid' ,900, 650);\" value=\"Print\" 
						style=\"border:2px solid Black;background-color:Red;color:Yellow;font-weight:Bold;\" />
				</td>

			</tr>
		</table>
	

	</div>

	
	<div style=\"position:absolute;left:100px;height:184px;width:500px;height:150px;background-color:#6699FF;
					border:2px solid black;overflow:auto;$showmultiple \">
		<span class=\"sectiontitle\">MULTIPLE ENTRY FOUND&nbsp;-&nbsp;
			<a href=\"#\" onclick=\"actiontxt.value='closewindow';submit();\">[Close Window]</a>
		</span>
		<br />
		
		<table width=\"100%\" class=\"listcol\">
			<tr>
				<th width=\"15%\">STAFFID</th>
				<th width=\"20%\">FNAME</th>
				<th width=\"20%\">GNAME</th>
				<th width=\"20%\">MNAME</th>
				<th width=\"25%\">DESIGNATION</th>
			</tr>
		";
			if ($multiple == 1)
			{
				while ($rowmultisearch = mysql_fetch_array($qrysearch))
				{
					$tmpstaffid = $rowmultisearch["STAFFID"];
					
					$qrygetinfo = mysql_query("SELECT STAFFID,FNAME,GNAME,MNAME,d.DESIGNATION
												FROM staffhdr s
												LEFT JOIN designation d ON d.DESIGNATIONCODE=s.POSITION
												WHERE STAFFID=$tmpstaffid
											") or die(mysql_error());
					
					$rowgetinfo = mysql_fetch_array($qrygetinfo);
	
					$info1 = $rowgetinfo["STAFFID"];
					$info2 = $rowgetinfo["FNAME"];
					$info3 = $rowgetinfo["GNAME"];
					$info4 = $rowgetinfo["MNAME"];
					$info5 = $rowgetinfo["DESIGNATION"];
					
					echo "
					<tr ondblclick=\"actiontxt.value='closewindow';
									staffid.value='$info1';submit();
									\">
						<td align=\"center\">$info1</td>
						<td align=\"center\">$info2</td>
						<td>$info3&nbsp;</td>
						<td>$info4&nbsp;</td>
						<td>$info5&nbsp;</td>
					</tr>
					
					";
				}
			}
				
		echo "
		</table>
		<br />
		<center>
			<a href=\"#\" style=\"font-size:0.8em;font-weight:Bold;\" onclick=\"actiontxt.value='closewindow';submit();\">Close Window</a>
		</center>
		<br />
	</div>
	
	
	
	
	
	<br />
	<span class=\"sectiontitle\">PERSONAL DATA</span>
	<br />
	
";
			$dirfilename = "$basedir/$initial.JPG";
			if (checkpath($dirfilename))
			{
				$scale = imageScale($dirfilename,-1,120);
				$width = $scale[0];
				$height = $scale[1];
				
	echo "			<center><img src=\"$dirfilename\" width=\"$width\" height=\"$height\" border=\"1\" /></center> ";
			}
			else 
			{
	echo "			<center><b>[NO PICTURE]</b></center>";
			}

	echo "<br />";
			
	$tdstyle =  "style=\"font-size:0.7em;font-weight:Bold;\"";
echo "
	<table style=\"width:100%;\">
		<tr>
			<td width=\"100px\" valign=\"middle\" $tdstyle>Name</td>
			<td width=\"700px\">
				<table class=\"listrow\" width=\"80%\" cellpadding=\"0\" cellspacing=\"0\">
					<tr>
						<td>Family Name<br />
							<input type=\"text\" name=\"fname\" value=\"$fname\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"30\" />
						</td>
						<td>Given Name<br />
							<input type=\"text\" name=\"gname\" value=\"$gname\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"30\" />
						</td>
						<td>Middle Name<br />
							<input type=\"text\" name=\"mname\" value=\"$mname\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"30\" />
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td $tdstyle>Address</td>
			<td>
				<textarea name=\"address\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" rows=\"3\" cols=\"40\">$address</textarea>
			</td>
		</tr>
		<tr>
			<td $tdstyle>Position</td>
			<td>
				<select name=\"positioncode\">
					<option>--Select One--</option>
				";
					$selected = "";
					while ($rowdesignation = mysql_fetch_array($qrydesignation))
					{
						$designationcode = $rowdesignation["DESIGNATIONCODE"];
						$designation = $rowdesignation["DESIGNATION"];
						
						if ($designationcode == $positioncode)
							$selected = "SELECTED";
						else 
							$selected = "";
							
						echo "
							<option $selected value=\"$designationcode\">$designation</option>
						";
					}

			echo "
				</select>
			</td>
		</tr>
		<tr>
			<td $tdstyle>Birth Date</td>
			<td align=\"left\">
				<input type=\"text\" name=\"birthdate\" value=\"$birthdate\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
				<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(birthdate, birthdate, 'mm/dd/yyyy', 0, 0);return false;\">
			</td>
		</tr>
		<tr>
			<td $tdstyle>Birth Place</td>
			<td align=\"left\"><input type=\"text\" name=\"birthplace\" value=\"$birthplace\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"30\" /></td>
		</tr>
		<tr>
			<td $tdstyle>Gender</td>
			<td align=\"left\">
				<input type=\"radio\" name=\"gender\" value=\"M\" $chkgenderM /><span style=\"font-size:0.8em;\">Male</span>
				<input type=\"radio\" name=\"gender\" value=\"F\" $chkgenderF /><span style=\"font-size:0.8em;\">Female</span>
			</td>
		</tr>
		<tr>
			<td $tdstyle>Weight (kls)</td>
			<td align=\"left\"><input type=\"text\" name=\"weight\" value=\"$weight\" size=\"10\" /></td>
		</tr>
		<tr>
			<td $tdstyle>Height (cm)</td>
			<td align=\"left\"><input type=\"text\" name=\"height\" value=\"$height\" size=\"10\" /></td>
		</tr>
		<tr>
			<td $tdstyle>Religion</td>
			<td align=\"left\"><input type=\"text\" name=\"religion\" value=\"$religion\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"20\" /></td>
		</tr>
		<tr>
			<td $tdstyle>Civil Status</td>
			<td align=\"left\">
				<input type=\"radio\" name=\"civilstatus\" value=\"S\" $chkcivilS /><span style=\"font-size:0.8em;\">Single</span> &nbsp;&nbsp;
				<input type=\"radio\" name=\"civilstatus\" value=\"M\" $chkcivilM /><span style=\"font-size:0.8em;\">Married</span> &nbsp;&nbsp;
				<input type=\"radio\" name=\"civilstatus\" value=\"W\" $chkcivilW /><span style=\"font-size:0.8em;\">Widower</span> &nbsp;&nbsp;
				<input type=\"radio\" name=\"civilstatus\" value=\"P\" $chkcivilP /><span style=\"font-size:0.8em;\">Separated</span> &nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td $tdstyle>Tel. No.</td>
			<td align=\"left\"><input type=\"text\" name=\"telno\" value=\"$telno\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"40\" /></td>
		</tr>
		<tr>
			<td $tdstyle>Cel. Phone</td>
			<td align=\"left\"><input type=\"text\" name=\"celphone\" value=\"$celphone\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"40\" /></td>
		</tr>
		<tr>
			<td $tdstyle>Email</td>
			<td align=\"left\"><input type=\"text\" name=\"email\" value=\"$email\" size=\"40\" /></td>
		</tr>
	</table>
	<br />
	<span class=\"sectiontitle\">EMPLOYMENT RECORD</span>
	<br />
	
	<table width=\"100%\">
		<tr>
			<td width=\"150px\" valign=\"middle\" $tdstyle>Name of Company</td>
			<td width=\"650px\"><input type=\"text\" name=\"company\" value=\"$company\" size=\"80\" /></td>
		</tr>
		<tr>
			<td $tdstyle>Position</td>
			<td align=\"left\"><input type=\"text\" name=\"companyposition\" value=\"$companyposition\" size=\"45\" /></td>
		</tr>
		<tr>
			<td $tdstyle>Period of Service</td>
			<td align=\"left\"><span style=\"font-size:0.8em;\">Date From</span>&nbsp;&nbsp;
				<input type=\"text\" name=\"compdatefrom\" value=\"$compdatefrom\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
				<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(compdatefrom, compdatefrom, 'mm/dd/yyyy', 0, 0);return false;\">
				&nbsp;&nbsp;
				<span style=\"font-size:0.8em;\">Date To</span>&nbsp;&nbsp;
				<input type=\"text\" name=\"compdateto\" value=\"$compdateto\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
				<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(compdateto, compdateto, 'mm/dd/yyyy', 0, 0);return false;\">
			</td>
		</tr>
		<tr>
			<td $tdstyle>Reason for Leaving</td>
			<td>
				<textarea name=\"compreason\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" rows=\"3\" cols=\"30\">$compreason</textarea>
			</td>
		</tr>
		<tr>
			<td align=\"left\">&nbsp;</td>
			<td align=\"left\"><input type=\"button\" value=\"Add to list...\" onclick=\"actiontxt.value='addlist';section.value='employment';submit();\" /></td>
		</tr>
	</table>
	<br />
	
	<table width=\"100%\" class=\"listcol\">
		<tr>
			<th>COMPANY</th>
			<th>POSITION</th>
			<th>FROM</th>
			<th>TO</th>
			<th>REASON</th>
		</tr>
";
		while ($rowemployment = mysql_fetch_array($qryemployment))
		{
			$employment1 = $rowemployment["COMPANY"];
			$employment2 = $rowemployment["POSITION"];
			if ($rowemployment["DATEFROM"] != "")
				$employment3 = date('m/d/Y',strtotime($rowemployment["DATEFROM"]));
			else 
				$employment3 = "";
			
			if ($rowemployment["DATETO"] != "")
				$employment4 = date('m/d/Y',strtotime($rowemployment["DATETO"]));
			else 
				$employment4 = "";
				
			$employment5 = $rowemployment["REASON"];
			$employment6 = $rowemployment["IDNO"];
				
echo "
			<tr>
				<td align=\"left\">$employment1&nbsp;</td>
				<td align=\"left\">$employment2&nbsp;</td>
				<td align=\"left\">$employment3&nbsp;</td>
				<td align=\"left\">$employment4&nbsp;</td>
				<td align=\"left\">$employment5&nbsp;</td>
			</tr>
";
		}

echo "
		
	</table>
	<br />
	
	<br />
	<span class=\"sectiontitle\">EDUCATION</span>
	<br />
	
	<table width=\"100%\">
		<tr>
			<td width=\"150px\" $tdstyle>College</td>
			<td width=\"650px\"><input type=\"text\" name=\"college\" value=\"$college\" size=\"80\" /></td>
		</tr>
		<tr>
			<td $tdstyle>Degree Finished</td>
			<td align=\"left\"><input type=\"text\" name=\"collegedegree\" value=\"$collegedegree\" size=\"45\" /></td>
		</tr>
		<tr>
			<td $tdstyle>Date Graduated</td>
			<td align=\"left\">
				<input type=\"text\" name=\"colyeargrad\" value=\"$colyeargrad\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
				<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(colyeargrad, colyeargrad, 'mm/dd/yyyy', 0, 0);return false;\">
			</td>
		</tr>
		<tr>
			<td $tdstyle>High School</td>
			<td><input type=\"text\" name=\"highschool\" value=\"$highschool\" size=\"80\" /></td>
		</tr>
		<tr>
			<td $tdstyle>Date Graduated</td>
			<td align=\"left\">
				<input type=\"text\" name=\"hsyeargrad\" value=\"$hsyeargrad\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
				<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(hsyeargrad, hsyeargrad, 'mm/dd/yyyy', 0, 0);return false;\">
			</td>
		</tr>
		<tr>
			<td $tdstyle>Elementary</td>
			<td><input type=\"text\" name=\"elementary\" value=\"$elementary\" size=\"80\" /></td>
		</tr>
		<tr>
			<td $tdstyle>Date Graduated</td>
			<td align=\"left\">
				<input type=\"text\" name=\"elemyeargrad\" value=\"$elemyeargrad\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
				<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(elemyeargrad, elemyeargrad, 'mm/dd/yyyy', 0, 0);return false;\">
			</td>
		</tr>
		<tr>
			<td $tdstyle>Post-Graduate School</td>
			<td><input type=\"text\" name=\"postgradschool\" value=\"$postgradschool\" size=\"80\" /></td>
		</tr>
		<tr>
			<td $tdstyle>Post-Graduate Studies</td>
			<td><input type=\"text\" name=\"postgradstudies\" value=\"$postgradstudies\" size=\"80\" /></td>
		</tr>
	</table>
	<br />
	
	<br />
	<span class=\"sectiontitle\">OTHER SPECIAL COURSES</span>
	<br />
	
	<table width=\"100%\">
		<tr>
			<td width=\"150px\" $tdstyle>Course</td>
			<td width=\"650px\"><input type=\"text\" name=\"course\" value=\"$course\" size=\"80\" /></td>
		</tr>
		<tr>
			<td $tdstyle>Date Taken</td>
			<td align=\"left\">
				<input type=\"text\" name=\"coursedate\" value=\"$coursedate\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
				<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(coursedate, coursedate, 'mm/dd/yyyy', 0, 0);return false;\">
			</td>
		</tr>
		<tr>
			<td align=\"left\">&nbsp;</td>
			<td align=\"left\"><input type=\"button\" value=\"Add to list...\" onclick=\"actiontxt.value='addlist';section.value='courses';submit();\" /></td>
		</tr>
	</table>
	<br />

	<table width=\"100%\" class=\"listcol\">
		<tr>
			<th>COURSE</th>
			<th>DATE</th>
		</tr>
";
		while ($rowcourses = mysql_fetch_array($qrycourses))
		{
			$course1 = $rowcourses["COURSENAME"];
			if ($rowcourses["DATETAKEN"] != "")
				$course2 = date('m/d/Y',strtotime($rowcourses["DATETAKEN"]));
			else 
				$course2 = "";
				
echo "
			<tr>
				<td align=\"left\">$course1&nbsp;</td>
				<td align=\"left\">$course2&nbsp;</td>
			</tr>
";
		}

echo "
		
	</table>
	<br />
	
	<br />
	<span class=\"sectiontitle\">EXAMINATIONS TAKEN</span>
	<br />
	
	<table width=\"100%\">
		<tr>
			<td width=\"150px\" $tdstyle>Examination</td>
			<td width=\"650px\"><input type=\"text\" name=\"examination\" value=\"$examination\" size=\"80\" /></td>
		</tr>
		<tr>
			<td $tdstyle>Date Taken</td>
			<td align=\"left\">
				<input type=\"text\" name=\"examdate\" value=\"$examdate\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
				<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(examdate, examdate, 'mm/dd/yyyy', 0, 0);return false;\">
			</td>
		</tr>
		<tr>
			<td align=\"left\">&nbsp;</td>
			<td align=\"left\"><input type=\"button\" value=\"Add to list...\" onclick=\"actiontxt.value='addlist';section.value='examination';submit();\" /></td>
		</tr>
	</table>
	<br />

	<table width=\"100%\" class=\"listcol\">
		<tr>
			<th>EXAMINATION</th>
			<th>DATE</th>
		</tr>
";
		while ($rowexamination = mysql_fetch_array($qryexamination))
		{
			$examination1 = $rowexamination["EXAMINATION"];
			if ($rowexamination["DATETAKEN"] != "")
				$examination2 = date('m/d/Y',strtotime($rowexamination["DATETAKEN"]));
			else 
				$examination2 = "";
				
echo "
			<tr>
				<td align=\"left\">$examination1&nbsp;</td>
				<td align=\"left\">$examination2&nbsp;</td>
			</tr>
";
		}

echo "
		
	</table>
	<br />
	
	<br />
	<span class=\"sectiontitle\">SPECIAL HONORS (if any)</span>
	<br />
	
	<table width=\"100%\">
		<tr>
			<td width=\"150px\" $tdstyle>Honor</td>
			<td width=\"650px\"><input type=\"text\" name=\"honor\" value=\"$honor\" size=\"80\" /></td>
		</tr>
		<tr>
			<td $tdstyle>Date Given</td>
			<td align=\"left\">
				<input type=\"text\" name=\"honordate\" value=\"$honordate\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
				<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(honordate, honordate, 'mm/dd/yyyy', 0, 0);return false;\">
			</td>
		</tr>
		<tr>
			<td align=\"left\">&nbsp;</td>
			<td align=\"left\"><input type=\"button\" value=\"Add to list...\" onclick=\"actiontxt.value='addlist';section.value='honors';submit();\" /></td>
		</tr>
	</table>
	<br />

	<table width=\"100%\" class=\"listcol\">
		<tr>
			<th>HONORS</th>
			<th>DATE GIVEN</th>
		</tr>
";
		while ($rowhonors = mysql_fetch_array($qryhonors))
		{
			$honors1 = $rowhonors["HONOR"];
			if ($rowhonors["DATEGIVEN"] != "")
				$honors2 = date('m/d/Y',strtotime($rowhonors["DATEGIVEN"]));
			else 
				$honors2 = "";
				
echo "
			<tr>
				<td align=\"left\">$honors1&nbsp;</td>
				<td align=\"left\">$honors2&nbsp;</td>
			</tr>
";
		}

echo "
		
	</table>
	<br /><br />
	
	<center>
		<input type=\"button\" onclick=\"actiontxt.value='save';submit();\"
			value=\"Save All Changes\" style=\"border:2px solid Red;background-color:Black;color:Yellow;font-weight:Bold;\" />
	</center>
	
	<br /><br /><br /><br />
</div>
</center>
	
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"section\" />
	<input type=\"hidden\" name=\"staffid\" value=\"$staffid\" />
	
</form>

</body>

</html>

"
?>