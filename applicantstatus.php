<?php

include("veritas/connectdb.php");
session_start();

$currentdate = date('Y-m-d H:i:s');
$datenow = date('d F Y');
$basedir = "idpics/";
$basedirdocs = "docimages/";

$docdisplay = "display:none;";

if(isset($_SESSION['employeeid']))
	$employeeid=$_SESSION['employeeid'];

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];

if (isset($_POST['remarks']))
	$remarks = $_POST['remarks'];

if (isset($_POST['applicantno']))
	$applicantno = $_POST['applicantno'];
	
if (isset($_POST['rankcode']))
	$rankcode = $_POST['rankcode'];
else 
	$rankcode = "All";
	
if (isset($_POST['statusrankcode']))
	$statusrankcode = $_POST['statusrankcode'];
else 
	$statusrankcode = "All";
	
if (isset($_POST['vmcrankcode']))
	$vmcrankcode = $_POST['vmcrankcode'];
	
if (isset($_POST['vmcvslcode']))
	$vmcvslcode = $_POST['vmcvslcode'];
	
if (isset($_POST['vmcetd']))
	$vmcetd = $_POST['vmcetd'];
	
if (isset($_POST['rankcodesel']))
	$rankcodesel = $_POST['rankcodesel'];	

if (isset($_POST['doctype']))
{
	$doctype = $_POST['doctype'];
	
	if ($doctype != "")
		$docdisplay = "display:block;";
}

if (isset($_POST['switchto']))
	$switchto = $_POST['switchto'];
else 
	$switchto = "screening";
	
if (isset($_POST['groupby']))
	$groupby = $_POST['groupby'];
else 
	$groupby = "1";
		
$select1 = "";
$select2 = "";
$select3 = "";

//if ($vmcrankcode == "")
//	$vmcrankcode = $rankcode;

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

	
switch ($switchto)
{
	case "screening":
		
			$wherepart = "";
		
			if($vmcrankcode != "")
			{
				$qrygetranktype = mysql_query("SELECT RANKTYPECODE FROM rank WHERE RANKCODE='$vmcrankcode'") or die(mysql_error());
				
				$rowranktype = mysql_fetch_array($qrygetranktype);
				$ranktypecode = $rowranktype["RANKTYPECODE"];
			}
			
			switch ($actiontxt)
			{
				case "ok"	:
					
						$qryverify = mysql_query("SELECT APPLICANTNO FROM applicantstatus WHERE APPLICANTNO=$applicantno") or die(mysql_error());
						
						if(!empty($vmcetd))
							$vmcetdraw = "'" . date("Y-m-d",strtotime($vmcetd)) . "'";
						else 
							$vmcetdraw = "NULL";
						
						if (mysql_num_rows($qryverify) > 0)
						{
							$qryappstatusupdate = mysql_query("UPDATE applicantstatus SET ACCEPTBY='$employeeid', 
																						ACCEPTDATE='$currentdate',
																						REMARKS='$remarks',
																						VMCRANKCODE='$vmcrankcode',
																						VMCVESSELCODE='$vmcvslcode',
																						VMCETD=$vmcetdraw
															WHERE APPLICANTNO=$applicantno") or die(mysql_error());
						}
//						else 
//						{
//							$qryappstatusinsert = mysql_query("INSERT INTO applicantstatus(APPLICANTNO,ACCEPTBY,ACCEPTDATE,REMARKS,VMCRANKCODE,VMCVESSELCODE) 
//															VALUES($applicantno,'$employeeid','$currentdate','$remarks','$vmcrankcode','$vmcvslcode')") or die(mysql_error());
//						}
						
						$qrycrewranktype = mysql_query("UPDATE crew SET RANKTYPECODE='$ranktypecode' WHERE APPLICANTNO=$applicantno") or die(mysql_error());
						
						$applicantno = "";
						$remarks = "";
						$vmcvslcode = "";
					
					break;
				case "activefile"	:
					
						$qryverify = mysql_query("SELECT APPLICANTNO FROM applicantstatus WHERE APPLICANTNO=$applicantno") or die(mysql_error());
						
						if (mysql_num_rows($qryverify) > 0)
						{
							$qryappstatusupdate = mysql_query("UPDATE applicantstatus SET ACTIVEFILEBY='$employeeid',
																						ACTIVEFILEDATE='$currentdate', 
																						REMARKS='$remarks',
															WHERE APPLICANTNO=$applicantno") or die(mysql_error());
						}
//						else 
//						{
//							$qryappstatusinsert = mysql_query("INSERT INTO applicantstatus(APPLICANTNO,ACTIVEFILEBY,ACTIVEFILEDATE,REMARKS,VMCRANKCODE,VMCVESSELCODE) 
//															VALUES($applicantno,'$employeeid','$currentdate','$remarks','$vmcrankcode','$vmcvslcode')") or die(mysql_error());
//						}
						
						$qrycrewranktype = mysql_query("UPDATE crew SET RANKTYPECODE='$ranktypecode' WHERE APPLICANTNO=$applicantno") or die(mysql_error());
						
						$applicantno = "";
						$remarks = "";
						$vmcvslcode = "";
					
					break;
				case "reserve"	:
					
						$qryverify = mysql_query("SELECT APPLICANTNO FROM applicantstatus WHERE APPLICANTNO=$applicantno") or die(mysql_error());
						
						if (mysql_num_rows($qryverify) > 0)
						{
							$qryappstatusupdate = mysql_query("UPDATE applicantstatus SET RESERVEDBY='$employeeid',
																						RESERVEDDATE='$currentdate', 
																						REMARKS='$remarks'
															WHERE APPLICANTNO=$applicantno") or die(mysql_error());
						}
//						else 
//						{
//							$qryappstatusinsert = mysql_query("INSERT INTO applicantstatus(APPLICANTNO,RESERVEDBY,RESERVEDDATE,REMARKS) 
//															VALUES($applicantno,'$employeeid','$currentdate','$remarks')") or die(mysql_error());
//						}
						
						$qrycrewranktype = mysql_query("UPDATE crew SET RANKTYPECODE='$ranktypecode' WHERE APPLICANTNO=$applicantno") or die(mysql_error());
						
						$applicantno = "";
						$remarks = "";
						$vmcvslcode = "";
					
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
			
			
			switch ($groupby)
			{
				case "1"	:  //NEW APPLICANTS
					
						$wherepart = "AND ACCEPTDATE IS NULL AND RESERVEDDATE IS NULL AND ACTIVEFILEDATE IS NULL";
						$select1 = "SELECTED";
					
					break;
				case "2"	:  //ACTIVE FILE
					
						$wherepart = "AND ACCEPTDATE IS NULL AND RESERVEDDATE IS NULL AND ACTIVEFILEDATE IS NOT NULL";
						$select2 = "SELECTED";
					
					break;
				case "3"	:  //RESERVED
					
						$wherepart = "AND ACCEPTDATE IS NULL AND RESERVEDDATE IS NOT NULL AND ACTIVEFILEDATE IS NULL";
						$select3 = "SELECTED";
					
					break;
			}
			
			$qryranklist=mysql_query("SELECT RANK,RANKCODE FROM rank WHERE STATUS=1 ORDER BY RANKING") or die(mysql_error());
			$qryasranklist=mysql_query("SELECT RANK,RANKCODE FROM rank WHERE STATUS=1 ORDER BY RANKING") or die(mysql_error());
			
			$qryvessellist = mysql_query("SELECT VESSELCODE,VESSEL FROM vessel WHERE STATUS=1 ORDER BY VESSEL") or die(mysql_error());

			$qryapplicants = mysql_query("SELECT ap.APPLICANTNO,CONCAT(c.FNAME,', ',GNAME,' ',MNAME) AS NAME,r.RANKCODE,
									ap.VMCRANKCODE,r.RANK,r.ALIAS1,r.RANKLEVELCODE,r.RANKTYPECODE
									FROM applicantstatus ap
									LEFT JOIN crew c ON c.APPLICANTNO=ap.APPLICANTNO
									LEFT JOIN rank r ON r.RANKCODE=ap.VMCRANKCODE
									LEFT JOIN ranklevel rl ON rl.RANKLEVELCODE=r.RANKLEVELCODE
									LEFT JOIN ranktype rt ON rt.RANKTYPECODE=r.RANKTYPECODE
									LEFT JOIN applicantwatchhdr awh ON awh.APPLICANTNO=ap.APPLICANTNO
									WHERE ap.CHARCHECKDATE IS NOT NULL AND ap.ENCODEDDATE IS NOT NULL
									AND awh.VMCLISTED=0 AND awh.POEALISTED=0
									$wherepart
									$whererank
									ORDER BY r.RANKLEVELCODE,r.RANKTYPECODE,r.RANK,NAME") or die(mysql_error());
			
		include("include/datasheet.inc");
			
		break;
	case "status"	:
		
			switch ($actiontxt)
			{
				case "checklist"	:
					
						$qryapplicantinfo = mysql_query("SELECT CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,r.RANK,r.RANKCODE
														FROM applicant a
														LEFT JOIN applicantstatus ap ON ap.APPLICANTNO=a.APPLICANTNO
														LEFT JOIN crew c ON c.APPLICANTNO=a.APPLICANTNO
														LEFT JOIN rank r ON r.RANKCODE=ap.VMCRANKCODE
														WHERE a.APPLICANTNO=$applicantno
														") or die(mysql_error());
						
						$rowapplicantinfo = mysql_fetch_array($qryapplicantinfo);
						
						$applicantname = $rowapplicantinfo["NAME"];
						$applicantrank = $rowapplicantinfo["RANK"];
						$applicantrankcode = $rowapplicantinfo["RANKCODE"];
						
						$qrychecklist = mysql_query("SELECT t.IDNO,t.RANKCODE,t.TRAINGROUPNO,t.TRAINCODE,tc.TRAINING,t.REQUIRED,tc.TYPE,tc.ALIAS
													FROM trainingchecklist t
													LEFT JOIN trainingcourses tc ON tc.TRAINCODE=t.TRAINCODE
													WHERE RANKCODE='$applicantrankcode' AND t.STATUS=1
													AND tc.STATUS=1
													ORDER BY TRAINGROUPNO,TRAINCODE
													") or die(mysql_error());
					
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
			
			switch ($statusrankcode)
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
				
						$whererank = "AND r.RANKCODE='$statusrankcode'";
						
					break;
			}
		
			$qryranklist=mysql_query("SELECT RANK,RANKCODE FROM rank WHERE STATUS=1 ORDER BY RANKING") or die(mysql_error());
				
			$qryfortraining = mysql_query("SELECT at.APPLICANTNO,CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,
										a.CHOICE1,r.RANK,r.ALIAS1,r.RANKLEVELCODE,r.RANKTYPECODE,at.ACCEPTBY,at.ACCEPTDATE
										FROM applicantstatus at
										LEFT JOIN applicant a ON a.APPLICANTNO=at.APPLICANTNO
										LEFT JOIN applicantexam ax ON ax.APPLICANTNO=at.APPLICANTNO
										LEFT JOIN crew c ON c.APPLICANTNO=at.APPLICANTNO
										LEFT JOIN rank r ON r.RANKCODE=at.VMCRANKCODE
										LEFT JOIN ranklevel rl ON rl.RANKLEVELCODE=r.RANKLEVELCODE
										LEFT JOIN ranktype rt ON rt.RANKTYPECODE=r.RANKTYPECODE
										WHERE ax.APPLICANTNO IS NOT NULL
										AND at.ACCEPTDATE IS NOT NULL
										AND ax.PASSED=1
										$whererank
										ORDER BY r.RANKLEVELCODE,r.RANKTYPECODE,r.RANK,NAME
										") or die(mysql_error());
		
		break;
}
	
	



echo "
<html>\n
<head>\n
<title>
Applicant Status
</title>

<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<link rel=\"stylesheet\" type=\"text/css\" href=\"veripro_print.css\">
<script type='text/javascript' src='veripro.js'></script>
<script language=\"javascript\" src=\"popcalendar.js\"></script>

<body onload=\"\" style=\"overflow:hidden;\">\n

<form name=\"applicantstatus\" method=\"POST\">\n

<span class=\"wintitle\">APPLICANT STATUS</span>
<div style=\"width:1019px;height:25px;background-color:Black;padding-left:10px;overflow:auto;\">
	<a href=\"#\" style=\"color:Yellow;font-size:0.8em;font-weight:bold;\" onclick=\"switchto.value='screening';applicantno.value='';submit();\">SCREENING</a>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<a href=\"#\" style=\"color:Yellow;font-size:0.8em;font-weight:bold;\" onclick=\"switchto.value='status';applicantno.value='';submit();\">STATUS</a>
</div>
";

switch ($switchto)
{
	case "screening"	:
			echo "
			<div style=\"width:1019px;border-bottom:1px solid black;\">
			
				<div style=\"width:185px;height:650px;float:left;background-color:#999AFF\">
					
					<div style=\"width:100%;height:350px;float:left;\">
					
						<span class=\"sectiontitle\">LIST OF APPLICANTS</span>
						<br />
						
						<span style=\"font-size:0.8em;font-weight:Bold;\">Group By</span>&nbsp;&nbsp;&nbsp;
						<select name=\"groupby\" onchange=\"switchto.value='screening';applicantno.value='';submit();\">
							<option $select1 value=\"1\">NEW APPLICANTS</option>
							<option $select2 value=\"2\">ACTIVE FILE</option>
							<option $select3 value=\"3\">RESERVED</option>
						</select>
						
						<br /><br />
						
						<table width=\"100%\" class=\"listcol\" style=\"font-size:0.7em;overflow:auto;\">
							<tr>
								<th>APPNO</th>
								<th>NAME</th>
							</tr>
						";
						
						$tmprank = "";
			
						while ($rowapplicants=mysql_fetch_array($qryapplicants))
						{
							$applicantno1 = $rowapplicants["APPLICANTNO"];
							$name = $rowapplicants["NAME"];
							$rank = $rowapplicants["RANK"];
							$rankcode1 = $rowapplicants["RANKCODE"];
							$alias1 = $rowapplicants["ALIAS1"];
							
							if ($tmprank != $rank)
							{
								echo "
								<tr>
									<td colspan=\"2\" align=\"left\" style=\"font-size:1em;font-weight:bold;color:Blue;\"><i>$rank</i></td>
								</tr>
								";
							}
							
							echo "
							<tr ondblclick=\"rankcodesel.value='$rankcode1';applicantno.value='$applicantno1';submit();\">
								<td style=\"font-size:0.9em;\">$applicantno1</td>
								<td style=\"font-size:0.9em;\">$name</td>
							
							</tr>
							";
							
							$tmprank = $rank;
						}
			
			echo "
						</table>
						
						
					</div>
				
					<div style=\"width:100%;height:150px;float:left;overflow:auto;background-color:#CCCCFF;\">
					
						<span class=\"sectiontitle\">SEARCH CRITERIA</span>
						
						<center>
						<table width=\"100%\" style=\"margin-top:5px;\">
						<!--
							<tr>
								<td colspan=\"2\" align=\"center\" style=\"font-size:0.8em;font-weight:Bold;\">Rank List</td>
							</tr>
						-->
							<tr>
								<td>
									<span style=\"font-size:0.8em;font-weight:Bold;\">Rank List</span>
									
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
								</td>
							</tr>

							<tr>
								<td>
									<center>
									<input type=\"button\" value=\"Refresh List\" style=\"border:0;height:30px;background-color:Green;
													font-size:1em;font-weight:Bold;color:Yellow;border:thin solid white;cursor:pointer;\"
												 onclick=\"applicantno.value='';submit();\" />
									</center>
								</td>
							</tr>
						</table>
						</center>

					</div>
					
					<div style=\"width:100%;height:150px;float:left;overflow:auto;background-color:#CCFF99;\">
					
						<span class=\"sectiontitle\">REMARKS</span>
						
						<table width=\"100%\" class=\"listrow\">
							<tr>
								<th width=\"30%\">Scholar</th>
								<th width=\"5%\">:</th>
								<td width=\"65%\">&nbsp;</td>
							</tr>
							<tr>
								<th>Watchlist</th>
								<th>:</th>
								<td>&nbsp;</td>
							</tr>
						</table>
						
					</div>
				
				</div>
				
				<div style=\"width:825px;height:650px;float:left;background-color:White;\">
				
					<span class=\"sectiontitle\">APPLICATION FORM (PREVIEW)</span>

					<div style=\"margin-left:5px;margin-top:5px;width:823px;height:430px;border:1px solid black;overflow:auto;\">
				";
							//#E5FFE6
				if ($applicantno != "")
				{
				
					echo "
						<div style=\"width:80%;height:60px;float:left;background-color:White;overflow:hidden;\">
							<center>
								<span style=\"font-size:1em;font-weight:Bold;\">VERITAS MARITIME CORPORATION</span><br />
								<span style=\"font-size:0.8em;font-weight:Bold;\">CREW PERSONAL DATA SHEET</span><br />
								<span style=\"font-size:0.7em;font-weight:Bold;\">Date: $datenow</span><br />
							</center>
						</div>
						
						<div style=\"width:20%;height:60px;float:right;background-color:White;overflow:hidden;\">
					
							<table width=\"100%\" class=\"listrow\" >
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
					
						<div style=\"width:620px;height:80px;float:left;overflow:hidden;\">
							<div style=\"width:360px;height:80px;float:left;background-color:White;overflow:hidden;\">
								<table class=\"listrow\" width=\"100%\">
									<tr>
										<th width=\"30%\">Name of Seaman</th>
										<th>:</th>
										<td>$crewname</td>
									</tr>
									<tr>
										<th>Experience in VMC</th>
										<th>:</th>
										<td>$vmcyears&nbsp;Years&nbsp;&nbsp; $vmcmonths&nbsp; Months</td>
									</tr>
									<tr>
										<th>Experience on<br /> 
											Highest Rank</th>
										<th>:</th>
										<td valign=\"top\">$exphighrankyears&nbsp; Year&nbsp;&nbsp; $exphighrankmonths Months</td>
									</tr>
								</table>
							</div>
							<div style=\"width:260px;height:80px;float:right;background-color:White;overflow:hidden;\">
								<table class=\"listrow\" width=\"100%\">
									<tr>
										<th width=\"45%\">Rank</th>
										<th>:</th>
										<td>$currentrank</td>
									</tr>
									<tr>
										<th>Recommended By</th>
										<th>:</th>
										<td>$crewrecommendedby</td>
									</tr>
									<tr>
										<th>Experience as Rank</th>
										<th>:</th>
										<td>$exprankyears&nbsp; Years&nbsp;&nbsp; $exprankmonths&nbsp; Months</td>
									</tr>
									<tr>
										<th>Rank License</th>
										<th>:</th>
										<td>$exphighestrank</td>
									</tr>
								</table>
							</div>
						</div>
						<div style=\"width:170px;height:140px;margin:5px;float:right;background-color:White;overflow:hidden;\">
					";
								$dirfilename = "$basedir/$applicantno.JPG";
								if (checkpath($dirfilename))
								{
									$scale = imageScale($dirfilename,-1,135);
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
						
						<div style=\"width:450px;height:240px;float:left;background-color:White;overflow:hidden;\">
							<table class=\"listrow\" width=\"100%\">
								<tr>
									<th colspan=\"3\" align=\"left\"><u>PERSONAL DATA</u></th>
								</tr>
								<tr>
									<th width=\"20%\">Place of Birth</th>
									<th>:</th>
									<td>$crewbplace</td>
								</tr>
								<tr>
									<th>Religion</th>
									<th>:</th>
									<td>$crewreligion</td>
								</tr>
								<tr>
									<th>Next of Kin</th>
									<th>:</th>
									<td>$crewkinname&nbsp;($crewkinrelation)</td>
								</tr>
								<tr>
									<th>Address</th>
									<th>:</th>
									<td>$crewkinaddr1</td>
								</tr>
								<tr>
									<th>Telephone No.</th>
									<th>:</th>
									<td>$crewkintelno</td>
								</tr>
								<tr>
									<th>Address</th>
									<th>:</th>
									<td>$crewaddress</td>
								</tr>
								<tr>
									<th>Telephone No.</th>
									<th>:</th>
									<td>$crewtelno</td>
								</tr>
								<tr>
									<th colspan=\"3\" align=\"left\"><u>EDUCATIONAL ATTAINMENT</u></th>
								</tr>
								<tr>
									<th>School</th>
									<th>:</th>
									<td>$creweducschool</td>
								</tr>
								<tr>
									<th>Course</th>
									<th>:</th>
									<td>$creweduccourse</td>
								</tr>
								<tr>
									<th>Year</th>
									<th>:</th>
									<td>$creweducdategrad</td>
								</tr>
							</table>
						</div>
						<div style=\"width:350px;height:190px;float:right;background-color:White;overflow:hidden;\">
							<table class=\"listrow\" width=\"95%\">
								<tr>
									<th>Date of Birth</th>
									<th>:</th>
									<td>$crewbdate</td>
								</tr>
								<tr>
									<th>Age</th>
									<th>:</th>
									<td>$crewage</td>
								</tr>
								<tr>
									<th>Height(cm)</th>
									<th>:</th>
									<td>$crewheight</td>
								</tr>
								<tr>
									<th>Weight(kg)</th>
									<th>:</th>
									<td>$crewweight</td>
								</tr>
								<tr>
									<th>Civil Status</th>
									<th>:</th>
									<td>$crewcivilstatus</td>
								</tr>
								<tr>
									<th>Driver's License</th>
									<th>:</th>
									<td>$crewdriverlic</td>
								</tr>
								<tr>
									<th>PEME on Process</th>
									<th>:</th>
									<td><b>[$crewmeddate]</b></td>
								</tr>
								<tr>
									<th>HBA1c Test</th>
									<th>:</th>
									<td>$crewmedhbatest</td>
								</tr>
								<tr>
									<th>Conducted By</th>
									<th>:</th>
									<td>$crewmedclinic</td>
								</tr>
								<tr>
									<th>Result</th>
									<th>:</th>
									<td>$crewmedrecommend</td>
								</tr>
							</table>
						</div>
						
						<div style=\"width:500px;height:310px;float:left;background-color:White;overflow:hidden;\">
							<table width=\"100%\" style=\"font-size:0.7em;font-weight:Bold;\" border=1>
								<tr>
									<th colspan=\"5\" align=\"left\"><u>DOCUMENTS AND LICENSES</u></th>
								</tr>
								<tr>
									<td width=\"23%\" align=\"center\">Document</td>
									<td width=\"10%\" align=\"center\">Rank</td>
									<td width=\"30%\" align=\"center\">Doc. No.</td>
									<td width=\"17%\" align=\"center\">Issued</td>
									<td width=\"17%\" align=\"center\">Expiry</td>
								</tr>
						";
								$style = "style=\"color:Blue;\"";
					
								while ($rowdocuments = mysql_fetch_array($qrydocuments))
								{
									$doccode = $rowdocuments["DOCCODE"];
									$doccode1 = $rowdocuments["DOCCODE1"];
									$document = $rowdocuments["DOCUMENT"];
									$documentno = $rowdocuments["DOCNO"];
									$docissued = $rowdocuments["DATEISSUED"];
									$docexpired = $rowdocuments["DATEEXPIRED"];
									$rankcode = $rowdocuments["RANKCODE"];
									$rankalias = $rowdocuments["ALIAS1"];
									
										$doctmp1 = "documentno" . $doccode1;
										$doctmp2 = "docissued" . $doccode1;
										$doctmp3 = "docexpired" . $doccode1;
										$doctmp4 = "rankalias" . $doccode1;
										
										if ($documentno != "")
										{
											$dirfilename = $basedirdocs . $applicantno . "/" . $doccode1 . ".pdf";
											
											if (checkpath($dirfilename))
												$$doctmp1 = "<a href=\"#\" onclick=\"openWindow('$dirfilename', '$doccode1' ,700, 500);\" 
																style=\"font-weight:Bold;color:Green;\" >$documentno</a>";
											else 
												$$doctmp1 = "&nbsp;" . $documentno;
										}
										else 
											$$doctmp1 = "N/A";
											
										if ($docissued != "")
											$$doctmp2 = date("dMY",strtotime($docissued));
										else 
											$$doctmp2 = "N/A";
											
										if ($docexpired != "")
											$$doctmp3 = date("dMY",strtotime($docexpired));
										else 
											$$doctmp3 = "N/A";
											
										if ($rankalias != "")
											$$doctmp4 = "&nbsp;" . $rankalias;
										else 
											$$doctmp4 = "N/A";
								}
					
								
					echo "			
								<tr>
									<td>Phil. Seabook</td>
									<td align=\"center\" $style>$rankaliasF2</td>
									<td align=\"center\" $style>$documentnoF2</td>
									<td align=\"center\" $style>$docissuedF2</td>
									<td align=\"center\" $style>$docexpiredF2</td>
								</tr>
								<tr>
									<td>Phil. License</td>
									<td align=\"center\" $style>$rankaliasF1</td>
									<td align=\"center\" $style>$documentnoF1</td>
									<td align=\"center\" $style>$docissuedF1</td>
									<td align=\"center\" $style>$docexpiredF1</td>
								</tr>
								<tr>
									<td>STCW</td>
									<td align=\"center\" $style>$rankalias40</td>
									<td align=\"center\" $style>$documentno40</td>
									<td align=\"center\" $style>$docissued40</td>
									<td align=\"center\" $style>$docexpired40</td>
								</tr>
					";
							if ($documentno18 == "N/A")
							{
								echo "
									<tr>
										<td>COC</td>
										<td align=\"center\" $style>$rankaliasC0</td>
										<td align=\"center\" $style>$documentnoC0</td>
										<td align=\"center\" $style>$docissuedC0</td>
										<td align=\"center\" $style>$docexpiredC0</td>
									</tr>
									";
							}
							else 
							{
								echo "
									<tr>
										<td>COC</td>
										<td align=\"center\" $style>$rankalias18</td>
										<td align=\"center\" $style>$documentno18</td>
										<td align=\"center\" $style>$docissued18</td>
										<td align=\"center\" $style>$docexpired18</td>
									</tr>
									";
							}
								
					echo "
								<tr>
									<td>Panama Seabook</td>
									<td align=\"center\" $style>$rankaliasP2</td>
									<td align=\"center\" $style>$documentnoP2</td>
									<td align=\"center\" $style>$docissuedP2</td>
									<td align=\"center\" $style>$docexpiredP2</td>
								</tr>
								<tr>
									<td>Panama License</td>
									<td align=\"center\" $style>$rankaliasP1</td>
									<td align=\"center\" $style>$documentnoP1</td>
									<td align=\"center\" $style>$docissuedP1</td>
									<td align=\"center\" $style>$docexpiredP1</td>
								</tr>
								<tr>
									<td>GMDSS Seabook</td>
									<td align=\"center\" $style>$rankalias44</td>
									<td align=\"center\" $style>$documentno44</td>
									<td align=\"center\" $style>$docissued44</td>
									<td align=\"center\" $style>$docexpired44</td>
								</tr>
								<tr>
									<td>GMDSS License</td>
									<td align=\"center\" $style>$rankalias28</td>
									<td align=\"center\" $style>$documentno28</td>
									<td align=\"center\" $style>$docissued28</td>
									<td align=\"center\" $style>$docexpired28</td>
								</tr>
								<tr>
									<td>Passport</td>
									<td align=\"center\" $style>$rankalias41</td>
									<td align=\"center\" $style>$documentno41</td>
									<td align=\"center\" $style>$docissued41</td>
									<td align=\"center\" $style>$docexpired41</td>
								</tr>
								<tr>
									<td>U.S. Visa</td>
									<td align=\"center\" $style>$rankalias42</td>
									<td align=\"center\" $style>$documentno42</td>
									<td align=\"center\" $style>$docissued42</td>
									<td align=\"center\" $style>$docexpired42</td>
								</tr>
								<tr>
									<td>Yellow Fever</td>
									<td align=\"center\" $style>$rankalias51</td>
									<td align=\"center\" $style>$documentno51</td>
									<td align=\"center\" $style>$docissued51</td>
									<td align=\"center\" $style>$docexpired51</td>
								</tr>
								<tr>
									<td>JIS License</td>
									<td align=\"center\" $style>$rankaliasJ1</td>
									<td align=\"center\" $style>$documentnoJ1</td>
									<td align=\"center\" $style>$docissuedJ1</td>
									<td align=\"center\" $style>$docexpiredJ1</td>
								</tr>
								<tr>
									<td>HK License</td>
									<td align=\"center\" $style>$rankaliasH1</td>
									<td align=\"center\" $style>$documentnoH1</td>
									<td align=\"center\" $style>$docissuedH1</td>
									<td align=\"center\" $style>$docexpiredH1</td>
								</tr>
					";
					
					echo "
							</table>
						</div>
						<div style=\"width:300px;height:310px;background-color:White;overflow:hidden;\">
							<table width=\"100%\" style=\"font-size:0.7em;font-weight:Bold;\" border=1>
								<tr>
									<th colspan=\"3\" align=\"center\"><u>TNKC MANDATORY TRAINING(IN-HOUSE)</u></th>
								</tr>
								<tr>
									<td width=\"33%\" align=\"center\">Training</td>
									<td width=\"33%\" align=\"center\">Issued</td>
									<td width=\"33%\" align=\"center\">Grade</td>
								</tr>
						";
								$style = "style=\"color:Blue;\"";
					
								while ($rowtrainings = mysql_fetch_array($qrytrainings))
								{
									$certcode = $rowtrainings["DOCCODE"];
									$certcode1 = $rowtrainings["DOCCODE1"];
									$certificate = $rowtrainings["DOCUMENT"];
									$certno = $rowtrainings["DOCNO"];
									$certissued = $rowtrainings["DATEISSUED"];
									$certgrade = $rowtrainings["GRADE"];
									$certrankcode = $rowtrainings["RANKCODE"];
									$certrankalias = $rowtrainings["ALIAS1"];
									
										$certtmp1 = "certno" . $certcode1;
										$certtmp2 = "certissued" . $certcode1;
										$certtmp3 = "certgrade" . $certcode1;
										$certtmp4 = "certrankalias" . $certcode1;
										
										if ($certno != "")
											$$certtmp1 = "&nbsp;" . $certno;
										else 
											$$certtmp1 = "N/A";
											
										if ($certissued != "")
										{
											$dirfilename = $basedirdocs . $applicantno . "/" . $certcode1 . ".pdf";
											
											if (checkpath($dirfilename))
												$$certtmp2 = "<a href=\"#\" onclick=\"openWindow('$dirfilename', '$certcode1' ,700, 500);\" 
																style=\"font-weight:Bold;color:Green;\" >" . date("dMY",strtotime($certissued)) . "</a>";
											else 
												$$certtmp2 = "&nbsp;" . date("dMY",strtotime($certissued));
										}
										else 
											$$certtmp2 = "N/A";
											
										if ($certgrade != "")
											$$certtmp3 = "&nbsp;" . $certgrade;
										else 
											$$certtmp3 = "N/A";
											
										if ($certrankalias != "")
											$$certtmp4 = "&nbsp;" . $certrankalias;
										else 
											$$certtmp4 = "N/A";
								}
					
					echo "			
								<tr>
									<td>TNKC ISM/SMS</td>
									<td align=\"center\" $style>$certissuedI1</td>
									<td align=\"center\" $style>$certgradeI1</td>
								</tr>
								<tr>
									<td>ISPS</td>
									<td align=\"center\" $style>$certissued57</td>
									<td align=\"center\" $style>$certgrade57</td>
								</tr>
								<tr>
									<td>BASS</td>
									<td align=\"center\" $style>$certissuedI0</td>
									<td align=\"center\" $style>$certgradeI0</td>
								</tr>
								<tr>
									<td>TTOS(PCC)</td>
									<td align=\"center\" $style>$certissuedI0</td>
									<td align=\"center\" $style>$certgradeI0</td>
								</tr>
								<tr>
									<td>TTOS(Corona)</td>
									<td align=\"center\" $style>$certissuedI0</td>
									<td align=\"center\" $style>$certgradeI0</td>
								</tr>
								<tr>
									<th colspan=\"3\" align=\"left\">&nbsp;</u></th>
								</tr>
								<tr>
									<th colspan=\"3\" align=\"center\"><u>TNKC MANDATORY TRAINING(OUTSIDE)</u></th>
								</tr>
								<tr>
									<td>S.S.B.T</td>
									<td align=\"center\" $style>$certissued79</td>
									<td align=\"center\" $style>$certgrade79</td>
								</tr>
								<tr>
									<td>S.S.O</td>
									<td align=\"center\" $style>$certissued49</td>
									<td align=\"center\" $style>$certgrade49</td>
								</tr>
								<tr>
									<td>E.R.S</td>
									<td align=\"center\" $style>$certissuedE1</td>
									<td align=\"center\" $style>$certgradeE1</td>
								</tr>
								<tr>
									<td>A.I.S</td>
									<td align=\"center\" $style>$certissuedA0</td>
									<td align=\"center\" $style>$certgradeA0</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td align=\"center\">&nbsp;</td>
									<td align=\"center\">&nbsp;</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td align=\"center\">&nbsp;</td>
									<td align=\"center\">&nbsp;</td>
								</tr>
							</table>
						</div>
						
						<div style=\"width:100%;background-color:White;overflow:hidden;\">
							
							<table width=\"100%\" style=\"font-size:0.7em;\" border=1>
								<tr>
									<th colspan=\"15\" align=\"left\"><u>EXPERIENCE SEABASED</u></th>
								</tr>
								<tr>
									<th align=\"center\">Company</th>
									<th align=\"center\">Vessel</th>
									<th align=\"center\">Rank</th>
									<th align=\"center\">GRT</th>
									<th align=\"center\">Engine</th>
									<th align=\"center\">Type</th>
									<th align=\"center\">Trade<br />Route</th>
									<th align=\"center\">BHP</th>
									<th align=\"center\">Embkd</th>
									<th align=\"center\">Disembkd</th>
									<th align=\"center\">Mo</th>
									<th align=\"center\">Reason</th>
									<th align=\"center\">Pro</th>
									<th align=\"center\">Rank</th>
									<th align=\"center\">Grd</th>
								</tr>
						";
							
							
							while($rowexperience = mysql_fetch_array($qryexperience))
							{
								$company = $rowexperience["COMPANY"];
								$vessel = $rowexperience["VESSEL"];
								$rankalias = $rowexperience["RANKALIAS"];
								$grosston = $rowexperience["GROSSTON"];
								$engine = $rowexperience["ENGINE"];
								$vesseltype = $rowexperience["VESSELTYPECODE"];
								$traderoute = $rowexperience["TRADEROUTECODE"];
								$enginepower = $rowexperience["ENGINEPOWER"];
								$dateemb = $rowexperience["DATEEMB"];
								$datedisemb = $rowexperience["DATEDISEMB"];
								if ($rowexperience["DISEMBREASONCODE"] != "")
									$disembreasoncode = $rowexperience["DISEMBREASONCODE"];
								else 
									$disembreasoncode = "--";
								
								$months = round(((strtotime($datedisemb) - strtotime($dateemb)) / 86400) / 30);
								
								$dateembview = date("dMY",strtotime($dateemb));
								$datedisembview = date("dMY",strtotime($datedisemb));
								
								echo "
								<tr>
									<td align=\"left\">$company</td>
									<td align=\"left\">$vessel</td>
									<td align=\"center\">$rankalias</td>
									<td align=\"right\">&nbsp;$grosston</td>
									<td align=\"center\">&nbsp;$engine</td>
									<td align=\"center\">$vesseltype</td>
									<td align=\"center\">$traderoute</td>
									<td align=\"center\">&nbsp;$enginepower</td>
									<td align=\"center\">$dateembview</td>
									<td align=\"center\">$datedisembview</td>
									<td align=\"center\">$months</td>
									<td align=\"center\">&nbsp;$disembreasoncode</td>
									<td align=\"center\">---</td>
									<td align=\"center\">---</td>
									<td align=\"right\">---</td>
								</tr>
								";
							}
					
						echo "
							</table>
							
							<br /><br />
						
						</div>
					
					";
				}
				else 
				{
					echo "<br /><br /><br /><br /><br /><br /><br /><br /><br />
						<center>
							
							<span style=\"font-size:1.2em;font-weight:Bold;color:Blue;\"><i>[ NO PREVIEW ]</i></span>
						
						</center>
					
					";
				}
				
				echo "			
					</div>

					<div style=\"width:825px;height:220px;background-color:Black;overflow:auto;\" >
						<br /><br />
						<table style=\"color:White;font-size:0.7em;font-weight:Bold;width:25%;float:left;\">
							<tr>
								<td>Remarks<br />
									<textarea rows=\"5\" cols=\"25\" name=\"remarks\" $disablebuttons >$remarks</textarea>
								</td>
							</tr>
						</table>
						
						<table style=\"width:70%;height:60px;float:right;\" cellpadding=\"0\" cellspacing=\"0\">
							<tr>
								<td align=\"center\"><span style=\"color:White;font-size:0.9em;font-weight:Bold;\">Recommended Vessel</span>
									<br />
									<select name=\"vmcvslcode\" $disablebuttons style=\"font-size:0.8em;\">
										<option value=\"\">--Select One--</option>
						";
									
									while($rowvessellist=mysql_fetch_array($qryvessellist))
									{
										$vessel=$rowvessellist['VESSEL'];
										$vesselcode=$rowvessellist['VESSELCODE'];
										
										$selectvessel = "";
										if ($vmcvslcode == $vesselcode)
											$selectvessel = "SELECTED";
											
										echo "<option $selectvessel value=\"$vesselcode\">$vessel</option>";
									}
				
				echo "		
									</select>
								</td>
								
								<td align=\"center\"><span style=\"color:White;font-size:0.9em;font-weight:Bold;\">Estimated Departure</span>
									<br />
									<input type=\"text\" name=\"vmcetd\" value=\"$vmcetd\" style=\"font-size:0.8em;\" $disablebuttons onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
									<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" $disablebuttons border=\"0\" onclick=\"popUpCalendar(vmcetd, vmcetd, 'mm/dd/yyyy', 0, 0);return false;\">
								</td>
								
								<td align=\"center\"><span style=\"color:White;font-size:0.9em;font-weight:Bold;\">As Rank</span>
									<br />
									<select name=\"vmcrankcode\" $disablebuttons style=\"font-size:0.8em;\">
										<option value=\"\">--Select One--</option>
						";
									
									while($rowasranklist=mysql_fetch_array($qryasranklist))
									{
										$asrank1=$rowasranklist['RANK'];
										$asrankcode1=$rowasranklist['RANKCODE'];
										
										$selectasrank = "";
										if ($rankcodesel == $asrankcode1)
											$selectasrank = "SELECTED";
											
										echo "<option $selectasrank value=\"$asrankcode1\">$asrank1</option>";
									}
				
				echo "		
									</select>
								</td>
							</tr>
							<tr>
								<td colspan=\"3\">&nbsp;</td>
							</tr>
							<tr height=\"40px;\">
								<td style=\"width:33%;background-color:Green;\" >
									<input type=\"button\" value=\"OK for Sets\" $disablebuttons style=\"border:0;width:100%;height:100%;cursor:pointer;font-size:1.3em;font-weight:Bold;color:White;background:inherit;\" 
													onclick=\"if(confirm('Applicant No. $applicantno: $name will be endorsed to the Training Department for examination. Continue?'))
															{if(vmcrankcode.value != ''){actiontxt.value='ok';submit();}
															else {alert('Please select designated rank.');}}\" />
								</td>
								<td style=\"width:33%;background-color:Blue;\" >
									<input type=\"button\" value=\"ACTIVE FILE\" $disablebuttons style=\"border:0;width:100%;height:100%;cursor:pointer;font-size:1.3em;font-weight:Bold;color:White;background:inherit;\" 
													onclick=\"actiontxt.value='activefile';submit();\" />
								</td>
								<td style=\"width:33%;background-color:Red;\" >
									<input type=\"button\" value=\"RESERVE\" $disablebuttons style=\"border:0;width:100%;height:100%;cursor:pointer;font-size:1.3em;font-weight:Bold;color:White;background:inherit;\" 
													onclick=\"actiontxt.value='reserve';submit();\" />
								</td>
							</tr>
						
						</table>
					
					</div>
				
				</div>
			</div>
			";
				

		break;
		
	case "status"	:
		echo "
			
			<div style=\"width:100%;\">
			
				<div style=\"width:100%;height:150px;background-color:White;\">
		
					<div style=\"width:70%;height:150px;float:left;background-color:White;overflow:auto;\">
						<table width=\"100%\" class=\"listcol\" style=\"font-size:0.7em;\">
							<tr>
								<th>APPNO</th>
								<th>NAME</th>
								<th>BY</th>
								<th>DATE</th>
							</tr>
						";
						
						$tmprank = "";
				
						while ($rowfortraining=mysql_fetch_array($qryfortraining))
						{
							$applicantno1 = $rowfortraining["APPLICANTNO"];
							$name2 = $rowfortraining["NAME"];
							$rank = $rowfortraining["RANK"];
							$acceptby = $rowfortraining["ACCEPTBY"];
							$acceptdate = date('m/d/Y',strtotime($rowfortraining["ACCEPTDATE"]));
							
							if ($tmprank != $rank)
							{
								echo "
								<tr>
									<td colspan=\"4\" align=\"left\" style=\"font-size:1em;font-weight:bold;color:Blue;\"><i>$rank</i></td>
								</tr>
								";
							}
							
							echo "
							<tr ondblclick=\"actiontxt.value='checklist';applicantno.value='$applicantno1';submit();\">
								<td style=\"font-size:0.9em;\">$applicantno1</td>
								<td style=\"font-size:0.9em;\">$name2</td>
								<td style=\"font-size:0.9em;\" align=\"center\">$acceptby</td>
								<td style=\"font-size:0.9em;\" align=\"center\">$acceptdate</td>
							</tr>
							";
							
							$tmprank = $rank;
						}
				
				echo "
						</table>
					</div>
					
					<div style=\"width:30%;height:150px;float:right;background-color:#CCCC66;\">
					
						<center>
						<table style=\"margin-top:5px;\">
							<tr>
								<td colspan=\"2\" align=\"center\" style=\"font-size:0.8em;font-weight:Bold;\">Rank List</td>
							</tr>
							<tr>
								<td>
									<select name=\"statusrankcode\" onchange=\"applicantno.value='';submit();\">
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
											if ($statusrankcode == $rankcode1)
												$selectrank = "SELECTED";
												
											echo "<option $selectrank value=\"$rankcode1\">$rank1</option>";
										}
							echo "
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>
									<input type=\"button\" value=\"Refresh List\" style=\"border:0;height:40px;background-color:Green;
													font-size:1em;font-weight:Bold;color:Yellow;border:thin solid white;cursor:pointer;\"
												 onclick=\"submit();\" />
								</td>
							</tr>
						</table>
						</center>
					</div>
					
				</div>
				
				<div style=\"width:100%;height:425px;\">
					
					<table style=\"width:100%;background-color:#333333;\" class=\"listrow\">
						<tr>
							<th style=\"color:White;\">Applicant No:&nbsp;&nbsp;<span style=\"font-size:1.2em;font-weight:Bold;color:Lime;\">$applicantno</span></th>
							<th style=\"color:White;\">Name:&nbsp;&nbsp;<span style=\"font-size:1.2em;font-weight:Bold;color:Lime;\">$applicantname</span></th>
							<th style=\"color:White;\">Rank:&nbsp;&nbsp;<span style=\"font-size:1.2em;font-weight:Bold;color:Lime;\">$applicantrank</span></th>
						</tr>
					</table>
		";
							
					if (mysql_num_rows($qrychecklist) > 0)
					{
						
						echo "
						<div style=\"width:100%;height:400px;overflow:auto;\">
							
							<table width=\"100%\" style=\"font-size:0.7em;background-color:#D0D2FF;border:1px solid black;\">
								<tr style=\"font-weight:bold;background-color:Black;color:White;\">
									<td width=\"40%\" align=\"center\">COURSE</td>
									<td width=\"20%\" align=\"center\">STATUS</td>
									<td width=\"20%\" align=\"center\">SCHEDULE</td>
									<td width=\"20%\" align=\"center\">REMARKS</td>
								</tr>
								";
									$tmpgroup = "";
									
									while ($rowchecklist = mysql_fetch_array($qrychecklist))
									{
										$chkidno = $rowchecklist["IDNO"];
										$chkrankcode = $rowchecklist["RANKCODE"];
										$chktraingroupno = $rowchecklist["TRAINGROUPNO"];
										$chktraincode = $rowchecklist["TRAINCODE"];
										$chktraining = $rowchecklist["TRAINING"];
										$chkrequired = $rowchecklist["REQUIRED"];
										$chktype = $rowchecklist["TYPE"];
										$chkalias = $rowchecklist["ALIAS"];
										
										if ($chktraingroupno != $tmpgroup)
										{
											$style = "style=\"background-color:Navy;color:Yellow;font-weight:Bold;font-size:1.2em;text-align:center;\"";
											
											switch ($chktraingroupno)
											{
												case "A"	:  //STCW BASIC SAFETY TRAINING
														echo "<tr><td colspan=\"4\" $style>STCW BASIC SAFETY TRAINING</td></tr>";		
													break;
												case "B"	:  //IN-HOUSE TRAINING REQUIREMENTS
														echo "<tr><td colspan=\"4\" $style>IN-HOUSE TRAINING REQUIREMENTS</td></tr>";		
													break;
												case "C"	:  //PRINCIPAL IN-HOUSE TRAINING
														echo "<tr><td colspan=\"4\" $style>PRINCIPAL IN-HOUSE TRAINING</td></tr>";			
													break;
												case "D"	:  //OUTSIDE TRAINING REQUIREMENTS
														echo "<tr><td colspan=\"4\" $style>OUTSIDE TRAINING REQUIREMENTS</td></tr>";		
													break;
											}
										}
										
										echo "
										<tr style=\"border:thin solid black;\">
											<td style=\"border:1px solid gray;font-weight:Bold;\">$chktraining</td>
											<td style=\"border:1px solid gray;\" align=\"center\">---</td>
											<td style=\"border:1px solid gray;\" align=\"center\">---</td>
											<td style=\"border:1px solid gray;\" align=\"center\">---</td>
										</tr>
										
										";
										
										$tmpgroup = $chktraingroupno;
									}
						echo "
							</table>
						</div>
						";
					}
					else 
					{
						echo "
						<div style=\"width:100%;height:400px;overflow:auto;\">
							<br /><br /><br /><br /><br /><br /><br />
							<center>
								<span style=\"font:bold 1.3em;color:gray;\"><i>[ CHECKLIST Preview ]</i></span>
							</center>
						</div>
						
						";
					}
		echo "
				</div>
			
			</div>
		";
		
		break;
}

	if ($applicantno != "")
	{
		echo "
			<div id=\"docwindow\" style=\"z-index:100;position:absolute;left:260px;top:100px;width:600px;height:400px;overflow:auto;
				background-color:#FFCC33;border:thin solid black;$docdisplay\">
			";
				
			$closestyle = "style=\"font-size:0.8em;font-weight:Bold;color:Blue;\"";
			
			switch ($doctype)
			{
				case "D"	:
					
					$qrydoc = mysql_query("SELECT cd.DOCUMENT,cds.DOCNO,cds.DATEISSUED,cds.DATEEXPIRED
												FROM crewdocstatus cds
												LEFT JOIN crewdocuments cd ON cd.DOCCODE=cds.DOCCODE
												WHERE cd.TYPE='D' and cds.APPLICANTNO=$applicantno") or die(mysql_error());
					
					
					echo "
						<span class=\"sectiontitle\">LIST OF DOCUMENTS</span>
						<br />
						<table class=\"listcol\" width=\"100%\">
							<tr>
								<th>DOCUMENT</th>
								<th>DOCNO</th>
								<th>ISSUED</th>
								<th>EXPIRY</th>
							</tr>
						";
							while ($rowdoc = mysql_fetch_array($qrydoc))
							{
								$list1 = $rowdoc["DOCUMENT"];
								$list2 = $rowdoc["DOCNO"];
								$list3 = date('m/d/Y',strtotime($rowdoc["DATEISSUED"]));
								$list4 = date('m/d/Y',strtotime($rowdoc["DATEEXPIRED"]));
								
								echo "
									<tr>
										<td>&nbsp;$list1</td>
										<td>&nbsp;$list2</td>
										<td>&nbsp;$list3</td>
										<td>&nbsp;$list4</td>
									</tr>
								";
								
							}
					echo "
						</table>
						
						<br />
						<center>
						<a href=\"#\" onclick=\"doctype.value='';getElementById('docwindow').style.display='none';\" $closestyle >Close Window</a>
						</center>
					";
					
					break;
					
				case "L"	:
					
					$qrylic = mysql_query("SELECT cd.DOCUMENT,cds.DOCNO,cds.DATEISSUED,cds.DATEEXPIRED
												FROM crewdocstatus cds
												LEFT JOIN crewdocuments cd ON cd.DOCCODE=cds.DOCCODE
												WHERE cd.TYPE='L'and cds.APPLICANTNO=$applicantno") or die(mysql_error());
					
					echo "
						<span class=\"sectiontitle\">LIST OF LICENSES</span>
						<br />
						<table class=\"listcol\" width=\"100%\">
							<tr>
								<th>LICENSE</th>
								<th>LICNO</th>
								<th>ISSUED</th>
								<th>EXPIRY</th>
							</tr>
						";
							while ($rowlic = mysql_fetch_array($qrylic))
							{
								$list1 = $rowlic["DOCUMENT"];
								$list2 = $rowlic["DOCNO"];
								$list3 = date('m/d/Y',strtotime($rowlic["DATEISSUED"]));
								$list4 = date('m/d/Y',strtotime($rowlic["DATEEXPIRED"]));
								
								echo "
									<tr>
										<td>&nbsp;$list1</td>
										<td>&nbsp;$list2</td>
										<td>&nbsp;$list3</td>
										<td>&nbsp;$list4</td>
									</tr>
								";
								
							}
					echo "
						</table>
						
						<br />
						
						<center>
						<a href=\"#\" onclick=\"doctype.value='';getElementById('docwindow').style.display='none';\" $closestyle >Close Window</a>
						</center>
					";
					
					break;
					
				case "C"	:
					
					$qrycert = mysql_query("SELECT cd.DOCUMENT,ccs.DOCNO,ccs.DATEISSUED,ccs.GRADE
												FROM crewcertstatus ccs
												LEFT JOIN crewdocuments cd ON cd.DOCCODE=ccs.DOCCODE
												WHERE cd.TYPE='C'and ccs.APPLICANTNO=$applicantno") or die(mysql_error());
					
					echo "
						<span class=\"sectiontitle\">LIST OF CERTIFICATES</span>
						<br />
						<table class=\"listcol\" width=\"100%\">
							<tr>
								<th>CERTIFICATE</th>
								<th>CERT NO</th>
								<th>ISSUED</th>
							</tr>
						";
							while ($rowcert = mysql_fetch_array($qrycert))
							{
								$list1 = $rowcert["DOCUMENT"];
								$list2 = $rowcert["DOCNO"];
								$list3 = date('m/d/Y',strtotime($rowcert["DATEISSUED"]));
								
								echo "
									<tr>
										<td>&nbsp;$list1</td>
										<td>&nbsp;$list2</td>
										<td>&nbsp;$list3</td>
									</tr>
								";
								
							}
					echo "
						</table>
						
						<br />
						
						<center>
						<a href=\"#\" onclick=\"doctype.value='';getElementById('docwindow').style.display='none';\" $closestyle >Close Window</a>
						</center>
					";	
				
					break;
					
				case "E"	:
					
					$qryexperience = mysql_query("SELECT ce.VESSEL,ce.ENGINETYPE,r.ALIAS1 AS RANKALIAS,ce.VESSELTYPECODE,ce.TRADEROUTECODE,FLAGCODE,DATEEMB,DATEDISEMB,
												IF (ce.MANNINGCODE = '',ce.MANNINGOTHERS,m.MANNING) AS MANNING,
												IF((ce.DISEMBREASONCODE <> 'OTH'),dr.REASON,ce.REASONOTHERS) AS REASON
												FROM crewexperience ce
												LEFT JOIN rank r ON r.RANKCODE=ce.RANKCODE
												LEFT JOIN manning m ON m.MANNINGCODE=ce.MANNINGCODE
												LEFT JOIN disembarkreason dr ON dr.DISEMBREASONCODE=ce.DISEMBREASONCODE
												WHERE ce.APPLICANTNO=$applicantno
												ORDER BY DATEDISEMB DESC
												") or die(mysql_error());
					
					echo "
						<span class=\"sectiontitle\">LIST OF EXPERIENCES</span>
						<br />
						<table class=\"listcol\" width=\"100%\">
							<tr>
								<th>VESSEL</th>
								<th>TYPE</th>
								<th>RANK</th>
								<th>ENGINE TYPE</th>
								<th>MANNING AGENCY</th>
								<th>REASON</th>
							</tr>
						";
							while ($rowexperience = mysql_fetch_array($qryexperience))
							{
								$experience1 = $rowexperience["VESSEL"];
								$experience2 = $rowexperience["VESSELTYPECODE"];
								$experience3 = $rowexperience["RANKALIAS"];
								$experience4 = $rowexperience["ENGINETYPE"];
								$experience5 = $rowexperience["MANNING"];
								$experience6 = $rowexperience["REASON"];
									
			echo "
								<tr>
									<td align=\"left\">&nbsp;$experience1</td>
									<td align=\"left\">&nbsp;$experience2</td>
									<td align=\"left\">&nbsp;$experience3</td>
									<td align=\"left\">&nbsp;$experience4</td>
									<td align=\"left\">&nbsp;$experience5</td>
									<td align=\"left\">&nbsp;$experience6</td>
								</tr>
			";
								
							}
					echo "
						</table>
						<br />
						
						<center>
						<a href=\"#\" onclick=\"doctype.value='';getElementById('docwindow').style.display='none';\" $closestyle >Close Window</a>
						</center>
					";	
				
					break;
			}
				
echo "				
			</div>
		";
	}
echo "
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"rankcodesel\" />
	<input type=\"hidden\" name=\"doctype\" />
	<input type=\"hidden\" name=\"switchto\" value=\"$switchto\" />
	<input type=\"hidden\" name=\"applicantno\" value=\"$applicantno\" />

</form>

";

?>