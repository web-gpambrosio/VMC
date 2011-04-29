<?php

include('veritas/connectdb.php');
include("veritas/include/stylephp.inc");
session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
else 
	$employeeid = "";
	
	
if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];

if (isset($_POST['applicantno']))
	$applicantno = $_POST['applicantno'];

if (isset($_POST['examdate']))
	$examdate = $_POST['examdate'];
else 
	$examdate = date('m/d/Y',strtotime($currentdate));

if (isset($_POST['grade']))
	$grade = $_POST['grade'];

if (isset($_POST['evaluation']))
	$evaluation = $_POST['evaluation'];

//if (isset($_POST['remarks']))
//	$remarks = $_POST['remarks'];
	
if (isset($_POST['rankcode']))
	$rankcode = $_POST['rankcode'];
else 
	$rankcode = "All";

	
switch ($actiontxt)
{
	case "evaluate"		:
		
			$qryapplicantinfo = mysql_query("SELECT CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,r.RANK
											FROM applicant a
											LEFT JOIN applicantstatus ap ON ap.APPLICANTNO=a.APPLICANTNO
											LEFT JOIN crew c ON c.APPLICANTNO=a.APPLICANTNO
											LEFT JOIN rank r ON r.RANKCODE=ap.VMCRANKCODE
											WHERE a.APPLICANTNO=$applicantno
											") or die(mysql_error());
			
			$rowapplicantinfo = mysql_fetch_array($qryapplicantinfo);
			
			$applicantname = $rowapplicantinfo["NAME"];
			$applicantrank = $rowapplicantinfo["RANK"];
		
		break;
	
	case "postgrade"	:
		
			$qryverify = mysql_query("SELECT APPLICANTNO FROM applicantexam WHERE APPLICANTNO=$applicantno") or die(mysql_error());
			
			$examdateraw = date('Y-m-d',strtotime($examdate));
			
			if ($grade == "")
				$grade = 0;
			
			//put computation for passing grade, 1 - Passed / 0 - Failed
			
			if ($grade > 59)
				$passed = 1;
			else 
				$passed = 0;
			
			if (mysql_num_rows($qryverify) == 0)
			{
				$qryexaminsert = mysql_query("INSERT INTO applicantexam(APPLICANTNO,EXAMDATE,GRADE,EVALUATION,PASSED,MADEBY,MADEDATE) 
											VALUES($applicantno,'$examdateraw',$grade,'$evaluation',$passed,'$employeeid','$currentdate')
											") or die(mysql_error());
			}
			else 
			{
				$qryexamupdate = mysql_query("UPDATE applicantexam SET EXAMDATE = '$examdateraw',
																	GRADE = $grade,
																	EVALUATION = '$evaluation',
																	PASSED = $passed,
																	MADEBY = '$employeeid',
																	MADEDATE = '$currentdate'
													WHERE APPLICANTNO=$applicantno
											") or die(mysql_error());
			}
			
			//UPDATE APPLICANTSTATUS TABLE
			
			$qrystatusupdate = mysql_query("UPDATE applicantstatus SET EXAMEVALBY='$employeeid', EXAMEVALDATE='$currentdate'
											WHERE APPLICANTNO=$applicantno") or die(mysql_error());
			
			
			$examdate = date('m/d/Y',strtotime($currentdate));
			$evaluation = "";
			$remarks = "";
			$grade = "";
			
			$applicantno = "";
			
		break;
		
	case "cancel"	:
		
			$examdate = date('m/d/Y',strtotime($currentdate));
			$evaluation = "";
			$remarks = "";
			$grade = "";
			
			$applicantno = "";
		
		break;
	
}
	

			
$rankselectM = "";
$rankselectO = "";
$rankselectS = "";
$rankselectD = "";
$rankselectE = "";
$rankselectT = "";
$rankselectALL = "";

$whererank = "";

switch ($rankcode)
{
	case "M"	:  //MANAGEMENT
		
			$whererank = "AND rl.RANKLEVELCODE='M'";
			$rankselectM = "SELECTED";
			
		break;
	case "O"	:  //OPERATIONAL
		
			$whererank = "AND rl.RANKLEVELCODE='O'";
			$rankselectO = "SELECTED";
			
		break;
	case "S"	:  //SUPPORT
		
			$whererank = "AND rl.RANKLEVELCODE='S'";
			$rankselectS = "SELECTED";
			
		break;
	case "D"	:  //DECK
		
			$whererank = "AND rt.RANKTYPECODE='D'";
			$rankselectD = "SELECTED";
	
		break;
	case "E"	:  //ENGINE
		
			$whererank = "AND rt.RANKTYPECODE='E'";
			$rankselectE = "SELECTED";
			
		break;
	case "T"	:  //STEWARD
		
			$whererank = "AND rt.RANKTYPECODE='S'";
			$rankselectT = "SELECTED";
			
		break;
	
	case "All"	:  //ALL RANKS
		
			$whererank = "";
			$rankselectALL = "SELECTED";
			
		break;
	
	default		: //ANY RANKCODE
	
			$whererank = "AND r.RANKCODE='$rankcode'";
			
		break;
}

$qryranklist=mysql_query("SELECT RANK,RANKCODE FROM rank WHERE STATUS=1 ORDER BY RANKING") or die(mysql_error());
	
$qryexamapplicants = mysql_query("SELECT at.APPLICANTNO,CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,
							r.RANK,r.ALIAS1,r.RANKLEVELCODE,r.RANKTYPECODE,at.ACCEPTBY,at.ACCEPTDATE
							FROM applicantstatus at
							LEFT JOIN applicantexam ax ON ax.APPLICANTNO=at.APPLICANTNO
							LEFT JOIN crew c ON c.APPLICANTNO=at.APPLICANTNO
							LEFT JOIN rank r ON r.RANKCODE=at.VMCRANKCODE
							LEFT JOIN ranklevel rl ON rl.RANKLEVELCODE=r.RANKLEVELCODE
							LEFT JOIN ranktype rt ON rt.RANKTYPECODE=r.RANKTYPECODE
							WHERE ax.APPLICANTNO IS NULL
							AND at.ACCEPTDATE IS NOT NULL AND EXAMEVALDATE IS NULL AND at.STATUS=1
							$whererank
							ORDER BY r.RANKLEVELCODE,r.RANKTYPECODE,r.RANK,NAME
							") or die(mysql_error());

if ($applicantno == "")
{
	$disablebuttons = "disabled=\"disabled\"";
}
else 
{
	$disablebuttons = "";
}


echo "
<html>
<head>
<title>
VERIPRO - Veritas Resource Integration Program
</title>

<link rel=\"stylesheet\" type=\"text/css\" href=\"veripro.css\">
<script language=\"javascript\" src=\"veripro.js\"></script>
<script language=\"javascript\" src=\"popcalendar.js\"></script>

<script>

</script>
</head>
<body style=\"border-right:1px solid black;overflow:hidden;\">

<form name=\"evaluation\" method=\"POST\">

	<div style=\"width:95%;height:270px;background-color:White;padding-left:10px;overflow:hidden;\">
		<span class=\"sectiontitle\">ENTRANCE EXAMINATION EVALUATION</span>
		<table style=\"width:90%;font-size:0.8em;\" class=\"setup\">
			<tr>
				<th width=\"30%\">Applicant No.</th>
				<th width=\"3%\">:</th>
				<th width=\"67%\"><span style=\"font-size:1.4em;font-weight:Bold;color:Red;\">$applicantno</span></th>						
			</tr>
			<tr>
				<th>Applicant Name</th>
				<th>:</th>
				<th><span style=\"font-size:1.3em;font-weight:Bold;color:Green;\">$applicantname</span></th>						
			</tr>
			<tr>
				<th>Rank</th>
				<th>:</th>
				<th><span style=\"font-size:1.2em;font-weight:Bold;color:Green;\">$applicantrank</span></th>						
			</tr>
			<tr>
				<th colspan=\"3\"><hr></th>						
			</tr>
			<tr>
				<th>Examination Date</th>
				<th>:</th>
				<th>
					<input type=\"text\" name=\"examdate\" value=\"$examdate\" $disablebuttons onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" $disablebuttons onclick=\"popUpCalendar(examdate, examdate, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
			</tr>
			<tr>
				<th valign=\"top\">Evaluation</th>
				<th valign=\"top\">:</th>
				<th>
					<textarea rows=\"4\" cols=\"30\" name=\"evaluation\" $disablebuttons>$evaluation</textarea>
				</th>
			</tr>
			<tr>
				<th>Grade</th>
				<th>:</th>
				<th>
					<input type=\"text\" name=\"grade\" $disablebuttons value=\"$grade\" size=\"10\" 
								style=\"font-size:1.5em;font-weight:Bold;color:red;text-align:center;\"
								onKeyPress=\"return amountonly(this);\" />
								
					<input type=\"button\" $disablebuttons value=\"Post Grade\" 
							onclick=\"if(grade.value != '') {if(confirm('This is to confirm that a GRADE of ' + grade.value + ' will be POSTED for Applicant No. $applicantno - $applicantname. Continue?')) 
										{actiontxt.value='postgrade';submit();}}
										else {alert('Invalid GRADE. Please check.');}\" />
					<input type=\"button\" $disablebuttons value=\"Cancel\" onclick=\"actiontxt.value='cancel';submit();\" />
				</th>
			</tr>
			
		</table>
	</div>
	
	<div style=\"width:95%;height:140px;background-color:silver;\">
	
		<div style=\"width:70%;height:140px;float:left;background-color:silver;overflow:auto;\">
			<span class=\"sectiontitle\">LIST OF APPLICANTS</span>
			<table width=\"100%\" class=\"listcol\" style=\"font-size:0.8em;\">
				<tr>
					<th width=\"25%\">APPNO</th>
					<th width=\"45%\">NAME</th>
					<th width=\"15%\">BY</th>
					<th width=\"15%\">DATE</th>
				</tr>
			";
			
			$tmprank = "";
	
			while ($rowexamapplicants=mysql_fetch_array($qryexamapplicants))
			{
				$applicantno1 = $rowexamapplicants["APPLICANTNO"];
				$name = $rowexamapplicants["NAME"];
				$rank = $rowexamapplicants["RANK"];
				$acceptby = $rowexamapplicants["ACCEPTBY"];
				$acceptdate = date('m/d/Y',strtotime($rowexamapplicants["ACCEPTDATE"]));
				
				if ($tmprank != $rank)
				{
					echo "
					<tr>
						<td colspan=\"4\" align=\"left\" style=\"font-size:1em;font-weight:bold;color:Blue;\"><i>$rank</i></td>
					</tr>
					";
				}
				
				echo "
				<tr $mouseovereffect ondblclick=\"applicantno.value='$applicantno1';actiontxt.value='evaluate';submit();\">
					<td style=\"font-size:0.9em;\">$applicantno1</td>
					<td style=\"font-size:0.9em;\">$name</td>
					<td style=\"font-size:0.9em;\" align=\"center\">$acceptby</td>
					<td style=\"font-size:0.9em;\" align=\"center\">$acceptdate</td>
				</tr>
				";
				
				$tmprank = $rank;
			}
	
	echo "
			</table>
		</div>
			
		<div style=\"width:29%;height:140px;float:right;background-color:#CCCC66;\">
			<span class=\"sectiontitle\">SORT BY</span>
			<table width=\"100%\">
				<tr>
					<td>
						<center>
							<span style=\"font-size:0.8em;font-weight:Bold;\">Rank List</span> <br />
							
							<select name=\"rankcode\" onchange=\"applicantno.value='';submit();\">
								<option $rankselectALL value=\"All\">-All-</option>
								<option $rankselectM value=\"M\">-Management-</option>
								<option $rankselectO value=\"O\">-Operation-</option>
								<option $rankselectS value=\"S\">-Support-</option>
								<option $rankselectD value=\"D\">-Deck-</option>
								<option $rankselectE value=\"E\">-Engine-</option>
								<option $rankselectT value=\"T\">-Steward-</option>";
		
								
								while($rowranklist=mysql_fetch_array($qryranklist))
								{
									$rank1=$rowranklist['RANK'];
									$rankcode1=$rowranklist['RANKCODE'];
									
									$selectrank = "";
									if ($rankcode == $rankcode1)
										$selectrank = "SELECTED";
										
									echo "<option $selectrank value=\"$rankcode1\">$rank1</option>";
								}
					echo "
							</select>
							<br /><br />
							<input type=\"button\" value=\"Refresh List\" style=\"border:0;height:30px;background-color:Green;
											font-size:0.8em;font-weight:Bold;color:Yellow;border:thin solid white;cursor:pointer;\"
										 onclick=\"applicantno.value='';submit();\" />
						</center>
					</td>
				</tr>
			</table>
			
		</div>
		
	</div>
		
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"applicantno\" value=\"$applicantno\" />
	
</form>
</body>

</html>

";


?>