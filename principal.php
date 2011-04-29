<?php

include('veritas/connectdb.php');
include("veritas/include/stylephp.inc");

session_start();

if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
else 
	$employeeid = "";

if (isset($_GET["showsearch"]))
	$showsearch = $_GET["showsearch"];
else 
	$showsearch=0;
	
if (isset($_GET["mcode"]))
	$mcode = $_GET["mcode"];

	
if (isset($_POST["actiontxt"]))
	$actiontxt = $_POST["actiontxt"];
	
if (isset($_POST["vslcode"]))
	$vslcode = $_POST["vslcode"];
	
if (isset($_POST["searchkey"]))
	$searchkey = $_POST["searchkey"];
	
if (isset($_POST["searchby"]))
	$searchby = $_POST["searchby"];
	
$showdatasheet = 0;

switch ($actiontxt)
{
	case "find"		:
		
//			$showsearch = "visibility:show;";
			$showsearch = 1;
		
			switch ($searchby)
			{
				case "1"	: //APPLICANT NO
					
						$qrysearch = mysql_query("SELECT APPLICANTNO FROM crew WHERE APPLICANTNO LIKE '$searchkey%' ORDER BY APPLICANTNO") or die(mysql_error());
					
					break;
				case "2"	: //CREW CODE
					
						$qrysearch = mysql_query("SELECT APPLICANTNO FROM crew WHERE CREWCODE LIKE '$searchkey%' ORDER BY CREWCODE") or die(mysql_error());
					
					break;
				case "3"	: //FAMILY NAME
					
						$qrysearch = mysql_query("SELECT APPLICANTNO FROM crew WHERE FNAME LIKE '$searchkey%' ORDER BY FNAME") or die(mysql_error());
					
					break;
				case "4"	: //GIVEN NAME
					
						$qrysearch = mysql_query("SELECT APPLICANTNO FROM crew WHERE GNAME LIKE '$searchkey%' ORDER BY GNAME") or die(mysql_error());
					
					break;
			}
			
			if (mysql_num_rows($qrysearch) == 1)
			{
				$showdatasheet = 1;
				$rowgetsearch = mysql_fetch_array($qrysearch);
				
				$appno_single = $rowgetsearch["APPLICANTNO"];
			}
			else 
				$showdatasheet = 0;
			
		break;
}
	
	
if ($employeeid != "")
{
//	$qrywelcome = mysql_query("SELECT EMPLOYEEID,FNAME,GNAME,MNAME,DESIGNATION
//					FROM employee e
//					LEFT JOIN designation d ON d.DESIGNATIONCODE=e.DESIGNATIONCODE
//					WHERE e.EMPLOYEEID = '$employeeid'") or die(mysql_error());
//	
//	$rowwelcome = mysql_fetch_array($qrywelcome);
//	
//	$name = $rowwelcome["FNAME"] . ", " . $rowwelcome["GNAME"] . " " . $rowwelcome["MNAME"];
//	$designation = $rowwelcome["DESIGNATION"];

	$qrywelcome = mysql_query("SELECT MANAGEMENTCODE,MANAGEMENT,ADDRESS,TELNO,FAXNO,EMAIL
								FROM management
								WHERE MANAGEMENTCODE='$mcode' AND STATUS=1
						") or die(mysql_error());
	
	$rowwelcome = mysql_fetch_array($qrywelcome);
	$princode = $rowwelcome["MANAGEMENTCODE"];
	$principal = $rowwelcome["MANAGEMENT"];
	$address = $rowwelcome["ADDRESS"];
	$telno = $rowwelcome["TELNO"];
	$faxno = $rowwelcome["FAXNO"];
	$email = $rowwelcome["EMAIL"];

	
	$qryvessel = mysql_query("SELECT VESSELCODE,VESSEL FROM vessel WHERE STATUS=1 AND MANAGEMENTCODE='$mcode' ORDER BY VESSEL") or die(mysql_error());
	
	$vesselsel = "<option value=\"\">-- Select One --</option>";
	
	while ($rowvessel = mysql_fetch_array($qryvessel))
	{
		$vcode = $rowvessel["VESSELCODE"];
		$vsl = $rowvessel["VESSEL"];
		
		$selected = "";
		if ($vcode==$vslcode)
			$selected = "SELECTED";
		
		$vesselsel .= "<option $selected value=\"$vcode\">$vsl</option>";
	}
	
}
if($showsearch==0)
{
	$displaysearch="none";
	$displaybackground="display";
}
else
{
	$displaysearch="display";
	$displaybackground="none";
}

echo "
<html>
<head>
<link rel=\"stylesheet\" type=\"text/css\" href=\"veripro.css\">
<script type='text/javascript' src='../veritas/veripro.js'></script>
</head>
<body onload=\"if ('$showdatasheet' == '1') {openWindow('crewdatasheet.php?applicantno=$appno_single','crewdatasheet',0,0);}\">
<form name=\"formmain\" method=\"POST\">
	<div style=\"width:100%;height:100%;\">
	
		<div id=\"welcome\">
			<table class=\"listrow\" style=\"font-size:0.9em;\" >
				<tr>
					<th width=\"100px\">Logged in </th>
					<th width=\"20px\"> : </th>
					<td style=\"font-size:1em;font-weight:Bold;color:Green;\">$principal</td>
				</tr>

			</table>
			
			<hr />
			
			<div id=\"advsearch\" style=\"background-color:#EBF7EC;
					overflow:hidden;padding:10px;border:1px solid Navy;display:'$displaysearch'\">
				<br>
				<span class=\"sectiontitle\">ADVANCE SEARCH</span>
				<br />
			
				<table width=\"90%\" style=\"font-size:0.8em;font-weight:Bold;\">
					<tr>
						<th>Search Key &nbsp;&nbsp;
							<select name=\"searchby\" onchange=\"searchkey.value='';searchkey.focus();\">
							";
			
							$selected1 = "";
							$selected2 = "";
							$selected3 = "";
							$selected4 = "";
			
							switch ($searchby)
							{
								case "1"	: //BY APPLICANT NO
										$selected1 = "SELECTED";
									break;
								case "2"	: //BY CREW CODE
										$selected2 = "SELECTED";
									break;
								case "3"	: //BY FAMILY NAME
										$selected3 = "SELECTED";
									break;
								case "4"	: //BY GIVEN NAME
										$selected4 = "SELECTED";
									break;
							}
			
						echo "
								<option $selected2 value=\"2\">CREW CODE</option>
								<option $selected1 value=\"1\">APPLICANT NO</option>
								<option $selected3 value=\"3\">FAMILY NAME</option>
								<option $selected4 value=\"4\">GIVEN NAME</option>
							</select>
						</th>
						<td><input type=\"text\" name=\"searchkey\" size=\"40\" value=\"$searchkey\" onkeyup=\"this.value=this.value.toUpperCase()\" 
								style=\"color:Red;font-weight:Bold;\" onkeydown=\"if(event.keyCode==13) {btnfind.focus();}\"/>
								
							<input type=\"button\" name=\"btnfind\" value=\"Find\" onclick=\"if(searchby.value != '' && searchkey.value != '')
										{actiontxt.value='find';submit();}
										else
										{alert('Invalid Search Key. Please try again.');}
										\" />
						</td>
					</tr>
				</table>
				
				<div style=\"width:90%;height:220px;background-color:#EBF7EC;overflow:auto;padding:10px;\">
					<center>
						<span style=\"font-size:0.7em;font-weight:Bold;color:Blue;cursor:pointer;\" 
							onclick=\"document.getElementById('advsearch').style.display='none';document.getElementById('showbackground').style.display='block';\">
							[ Close Window ]
							</span>
					</center>
					<br />
					
					<table width=\"100%\" class=\"listcol\">
						<tr>
							<th width=\"15%\">APPLICANT NO</th>
							<th width=\"15%\">CREW CODE</th>
							<th width=\"20%\">FNAME</th>
							<th width=\"20%\">GNAME</th>
							<th width=\"20%\">MNAME</th>
							<th width=\"10%\">STATUS</th>
						</tr>
					";
						if (mysql_num_rows($qrysearch) > 1)
						{
							while ($rowmultisearch = mysql_fetch_array($qrysearch))
							{
								$appno = $rowmultisearch["APPLICANTNO"];
								
								$qrygetinfo = mysql_query("SELECT APPLICANTNO,CREWCODE,FNAME,GNAME,MNAME,STATUS
															FROM crew 
															WHERE APPLICANTNO=$appno
														") or die(mysql_error());
								
								$rowgetinfo = mysql_fetch_array($qrygetinfo);
				
								$info1 = $rowgetinfo["APPLICANTNO"];
								$info2 = $rowgetinfo["CREWCODE"];
								$info3 = $rowgetinfo["FNAME"];
								$info4 = $rowgetinfo["GNAME"];
								$info5 = $rowgetinfo["MNAME"];
								if ($rowgetinfo["STATUS"] == 1)
									$info6 = "ACTIVE";
								else 
									$info6 = "INACTIVE";
								
								echo "
								<tr $mouseovereffect style=\"cursor:pointer;\"
									ondblclick=\"openWindow('crewdatasheet.php?applicantno=$info1','crewdatasheet',0,0);\">
									<td align=\"center\">$info1</td>
									<td align=\"center\">$info2</td>
									<td>$info3&nbsp;</td>
									<td>$info4&nbsp;</td>
									<td>$info5&nbsp;</td>
									<td align=\"center\">$info6</td>
								</tr>
								";
							}
						}
						elseif (mysql_num_rows($qrysearch) == 1)
						{
							
						}
						else 
						{
							$errormsg = "[ NO RESULTS ]";
							
							echo "
								<tr>
									<td colspan=\"6\">&nbsp;</td>
								</tr>
								<tr>
									<td colspan=\"6\" align=\"center\" style=\"font-size:1.1em;font-weight:Bold;color:Green;\"><i>$errormsg</i></td>
								</tr>
							";
						}
							
					echo "
					</table>
					<br>
					<center>
						<span style=\"font-size:0.7em;font-weight:Bold;color:Blue;cursor:pointer;\" 
							onclick=\"document.getElementById('advsearch').style.display='none';document.getElementById('showbackground').style.display='block';\">
							[ Close Window ]
							</span>
					</center>
				</div>
			</div>
			<div id=\"showbackground\" style=\"display:'$displaybackground';width:750px;height:320px;border-bottom:0;overflow:auto;padding:0;\">
			
				<div style=\"width:50%;height:100%;float:left;padding:5 5 5 5;\">
					<span class=\"sectiontitle\">ONBOARD LISTING</span>
					<br />
					<span style=\"font-size:0.8em;font-weight:Bold;\">Select Vessel</span> <br />
					<select name=\"vslcode\" onchange=\"submit();\">
						$vesselsel
					</select>

					<input type=\"button\" value=\"View\" onclick=\"openWindow('crewlisting.php?vesselcode=$vslcode', 'weekly', 900, 600);\" />
					
				</div>
				
				<div style=\"width:45%;height:100%;float:right;padding:5 5 5 5;background-color:#DCDCDC;overflow:auto;\">
					<span class=\"sectiontitle\">REPORTS</span>
					<br />
					<table class=\"listrow\" width=\"100%\">
						<tr>
							<td style=\"font-weight:Bold;\"><a href=\"#\" onclick=\"openWindow('reports/principal/WeeklyReport.pdf', 'weeklyreport', 900, 600);\">Weekly Report - 15 October 2007</a></td>
						</tr>
						<tr>
							<td style=\"font-weight:Bold;\"><a href=\"#\" onclick=\"openWindow('reports/principal/WeeklyTrainingReport.pdf', 'trainingreport', 900, 600);\">Weekly Training Report</a></td>
						</tr>
						<tr>
							<td style=\"font-weight:Bold;\"><a href=\"#\" onclick=\"openWindow('reports/principal/ManagementMin.pdf', 'managementminutes', 900, 600);\">Management Meeting Minutes</a></td>
						</tr>
						<tr>
							<td style=\"font-weight:Bold;\"><a href=\"#\" onclick=\"openWindow('reports/principal/TrainingMin.pdf', 'trainingminutes', 900, 600);\">Training Meeting Minutes</a></td>
						</tr>
						<tr>
							<td style=\"font-weight:Bold;\"><a href=\"#\" onclick=\"openWindow('reports/principal/US_VISA.pdf', 'usvisa', 900, 600);\">US VISA</a></td>
						</tr>
						<tr>
							<td style=\"font-weight:Bold;\"><a href=\"#\" onclick=\"openWindow('reports/principal/AUS_VISA.pdf', 'ausvisa', 900, 600);\">Australian VISA</a></td>
						</tr>
						<tr>
							<td style=\"font-weight:Bold;\"><a href=\"#\" onclick=\"openWindow('reports/principal/Crew_Withdrawal.pdf', 'withdrawal', 900, 600);\">Crew Withdrawal</a></td>
						</tr>
						<tr>
							<td style=\"font-weight:Bold;\"><a href=\"#\" onclick=\"openWindow('reports/principal/CadetshipProgramSummary.pdf', 'cadteship', 900, 600);\">VMC Cadetship Program Summary</a></td>
						</tr>
						<tr>
							<td style=\"font-weight:Bold;\"><a href=\"#\" onclick=\"openWindow('reports/principal/PromotedScholars2007.pdf', 'promoted', 900, 600);\">VMC Promoted Scholars 2007</a></td>
						</tr>
						<tr>
							<td style=\"font-weight:Bold;\"><a href=\"#\" onclick=\"openWindow('reports/principal/ScholarsFasttracks_October2007.pdf', 'scholars2007', 900, 600);\">VMC Scholars and Fasttracks as of October 2007</a></td>
						</tr>
						<tr>
							<td style=\"font-weight:Bold;\"><a href=\"#\" onclick=\"openWindow('reports/principal/CookCadets.pdf', 'cookcadets', 900, 600);\">VMC Cook Cadetship Program Summary</a></td>
						</tr>
						<tr>
							<td style=\"font-weight:Bold;\"><a href=\"#\" onclick=\"openWindow('reports/principal/VMC_ServiceDirectory.pdf', 'directory', 900, 600);\">VMC Service Directory</a></td>
						</tr>
						<tr>
							<td style=\"font-weight:Bold;\"><a href=\"#\" onclick=\"openWindow('reports/principal/EmployeeList.pdf', 'employee', 900, 600);\">VMC Employee Listing</a></td>
						</tr>
						<tr>
							<td style=\"font-weight:Bold;\"><a href=\"#\" onclick=\"openWindow('reports/principal/VMC_CrewDevelopment.xls', 'employee', 0, 0);\">VMC Crew Development Plan for the next 5 years</a></td>
						</tr>
					</table>
				</div>
					
			</div>
		</div>
	</div>
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"applicantno\" />
</form>
</body>
</html>
";




?>

