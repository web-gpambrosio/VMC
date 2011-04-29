<?php
include('veritas/connectdb.php');
include("veritas/include/stylephp.inc");
include("veritas/include/functions.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');
$basedirid = "idpics/";
$basedirdocs = "docimages/";

$datenow = date("Y-m-d");
$chkmanagement = "";
$showmanagement=0;

if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
else 
	$employeeid = "";

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];

if (isset($_POST['applicantno']))
	$applicantno = $_POST['applicantno'];
	
if(isset($_POST['chkmanagement']))
{
	$showmanagement=1;
	$chkmanagement = "checked=\"checked\"";
}
	
if (isset($_POST["searchkey"]))
	$searchkey = $_POST["searchkey"];
	
if (isset($_POST["searchby"]))
	$searchby = $_POST["searchby"];
	
if ($searchkey == "")
	$disablesearch = "disabled=\"disabled\"";
else 
	$disablesearch = "";

	
if (isset($_POST['rankcode']))
	$rankcode = $_POST['rankcode'];
else 
	$rankcode = "All";
	
if (isset($_POST['transdatefrom']))
	$transdatefrom = $_POST['transdatefrom'];
else 
	$transdatefrom = "10/1/2008";
	
if (isset($_POST['transdateto']))
	$transdateto = $_POST['transdateto'];
else 
	$transdateto = date("m/d/Y",strtotime($currentdate));

if (isset($_POST['show']))
	$show = $_POST['show'];	
else 
	$show = 1;
	
$disablechecklist = 0;
$showmultiple = "display:none;";
$multiple = 0;

$show1 = "";
$show2 = "";


switch ($actiontxt)
{
	case "find"	:
		
		$whereappno = "";
		
		switch ($searchby)
		{
			case "1"	: //APPLICANT NO
				
					$qrysearch = mysql_query("SELECT APPLICANTNO,CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,CREWCODE FROM crew WHERE APPLICANTNO LIKE '$searchkey%' ORDER BY APPLICANTNO") or die(mysql_error());
				
				break;
			case "2"	: //CREW CODE
				
					$qrysearch = mysql_query("SELECT APPLICANTNO,CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,CREWCODE FROM crew WHERE CREWCODE LIKE '$searchkey%' ORDER BY CREWCODE") or die(mysql_error());
				
				break;
			case "3"	: //FAMILY NAME
				
					$qrysearch = mysql_query("SELECT APPLICANTNO,CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,CREWCODE FROM crew WHERE FNAME LIKE '$searchkey%' ORDER BY FNAME") or die(mysql_error());
				
				break;
			case "4"	: //GIVEN NAME
				
					$qrysearch = mysql_query("SELECT APPLICANTNO,CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,CREWCODE FROM crew WHERE GNAME LIKE '$searchkey%' ORDER BY GNAME") or die(mysql_error());
				
				break;
		}
	
		if (mysql_num_rows($qrysearch) == 1)
		{
			$rowsearch = mysql_fetch_array($qrysearch);
			$applicno = $rowsearch["APPLICANTNO"];
			$name1 = $rowsearch["NAME"];
			$crewcode1 = $rowsearch["CREWCODE"];
			
			$qrychkapplicant = mysql_query("SELECT APPLICANTNO,APPROVEDBY,APPROVEDDATE 
											FROM applicantstatus 
											WHERE APPLICANTNO=$applicno") or die(mysql_error());
			
			$rowchkapplicant = mysql_fetch_array($qrychkapplicant);
			
			if (mysql_num_rows($qrychkapplicant) > 0)
			{
//				$showmanagement
				$applicantno = $rowchkapplicant["APPLICANTNO"];
				$approveddate1 = $rowchkapplicant["APPROVEDDATE"];
				
				if (!empty($approveddate1))
				{
					$showmanagement = 1;
					$chkmanagement = "checked=\"checked\"";
				}
				
			}
			else 
			{
				$applicantno = "";
				echo "<script>alert('Applicant No. $applicno : $name1 is not in the Applicant Table.');</script>";
			}
		}
		elseif (mysql_num_rows($qrysearch) > 1)
		{
			$showmultiple = "display:block;";
			$multiple = 1;
		}
		
	break;
	
	case "drop"	:
		
//			echo "SELECT APPROVEDBY,APPROVEDDATE FROM applicantstatus WHERE APPLICANTNO=$applicantno AND STATUS=1";
		
			$qryverify = mysql_query("SELECT APPROVEDBY,APPROVEDDATE FROM applicantstatus WHERE APPLICANTNO=$applicantno AND STATUS=1") or die(mysql_error());
			
			if (mysql_num_rows($qryverify) > 0)
			{
				$rowverify = mysql_fetch_array($qryverify);
				$apprvby = $rowverify["APPROVEDBY"];
				$apprvdate = $rowverify["APPROVEDDATE"];
				
				$qrydrop = mysql_query("UPDATE applicantstatus SET STATUS=0 WHERE APPLICANTNO=$applicantno AND STATUS=1") or die(mysql_error());
				$qrydrop2 = mysql_query("UPDATE crew SET STATUS=0 WHERE APPLICANTNO=$applicantno AND STATUS=1") or die(mysql_error());
				
				$applicantno = "";
			}
		
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

$whereoption = "";

switch ($show)
{
	case "1"	:	$whereoption = ""; $show1 = "checked=\"checked\""; break;
	case "2"	:	$whereoption = "AND cs.APPLICANTNO IS NOT NULL"; $show2 = "checked=\"checked\""; break;
}

$fromraw = date("Y-m-d 00:00:00",strtotime($transdatefrom));
$toraw = date("Y-m-d 23:59:59",strtotime($transdateto));
$wheredate = " AND app.DATEAPPLIED BETWEEN '$fromraw' AND '$toraw'";

if ($showmanagement == 0)
	$wheremgmt = "AND aps.APPROVEDDATE IS NULL";
else 
	$wheremgmt = "AND aps.APPROVEDDATE IS NOT NULL";
	
if (!empty($applicantno))
	$whereappno = "AND c.APPLICANTNO=$applicantno";
else 
	$whereappno = "";
	

$qrycrewlisting = mysql_query("SELECT aps.*,app.DATEAPPLIED,
								CONCAT(c.FNAME,', ',c.GNAME,' ',c.MNAME) AS NAME,c.CREWCODE,
								awh.POEALISTED,awh.VMCLISTED,
								r.ALIAS1 AS RANKALIAS,r.RANK,v.VESSEL,
								ax.GRADE,ax.PASSED,ax.EVALUATION,
								cs.SCHOLASTICCODE,s.DESCRIPTION AS SCHOLARTYPE,
								e1.FNAME AS FNAME1,e1.GNAME AS GNAME1,e1.MNAME AS MNAME1,
								e2.FNAME AS FNAME2,e2.GNAME AS GNAME2,e2.MNAME AS MNAME2,
								e3.FNAME AS FNAME3,e3.GNAME AS GNAME3,e3.MNAME AS MNAME3,
								e4.FNAME AS FNAME4,e4.GNAME AS GNAME4,e4.MNAME AS MNAME4,
								e5.FNAME AS FNAME5,e5.GNAME AS GNAME5,e5.MNAME AS MNAME5,
								e6.FNAME AS FNAME6,e6.GNAME AS GNAME6,e6.MNAME AS MNAME6,
								e7.FNAME AS FNAME7,e7.GNAME AS GNAME7,e7.MNAME AS MNAME7,
								e8.FNAME AS FNAME8,e8.GNAME AS GNAME8,e8.MNAME AS MNAME8,
								e9.FNAME AS FNAME9,e9.GNAME AS GNAME9,e9.MNAME AS MNAME9,
								e10.FNAME AS FNAME10,e10.GNAME AS GNAME10,e10.MNAME AS MNAME10,
								e11.FNAME AS FNAME11,e11.GNAME AS GNAME11,e11.MNAME AS MNAME11
								FROM applicantstatus aps
								LEFT JOIN crew c ON c.APPLICANTNO=aps.APPLICANTNO
								LEFT JOIN applicantwatchhdr awh ON awh.APPLICANTNO=aps.APPLICANTNO
								LEFT JOIN applicantexam ax ON ax.APPLICANTNO=aps.APPLICANTNO
								LEFT JOIN applicant app ON app.APPLICANTNO=aps.APPLICANTNO
								LEFT JOIN crewscholar cs ON cs.APPLICANTNO=aps.APPLICANTNO
								LEFT JOIN scholar s ON s.SCHOLASTICCODE=cs.SCHOLASTICCODE
								LEFT JOIN rank r ON r.RANKCODE=aps.VMCRANKCODE
								LEFT JOIN ranklevel rl ON rl.RANKLEVELCODE=r.RANKLEVELCODE
								LEFT JOIN ranktype rt ON rt.RANKTYPECODE=r.RANKTYPECODE
								LEFT JOIN vessel v ON v.VESSELCODE=aps.VMCVESSELCODE
								LEFT JOIN employee e1 ON e1.EMPLOYEEID=aps.ENDORSEDBY
								LEFT JOIN employee e2 ON e2.EMPLOYEEID=aps.ENCODEDBY
								LEFT JOIN employee e3 ON e3.EMPLOYEEID=aps.CHARCHECKBY
								LEFT JOIN employee e4 ON e4.EMPLOYEEID=aps.ACCEPTBY
								LEFT JOIN employee e5 ON e5.EMPLOYEEID=aps.ACTIVEFILEBY
								LEFT JOIN employee e6 ON e6.EMPLOYEEID=aps.RESERVEDBY
								LEFT JOIN employee e7 ON e7.EMPLOYEEID=aps.EXAMEVALBY
								LEFT JOIN employee e8 ON e8.EMPLOYEEID=aps.HOLDBY
								LEFT JOIN employee e9 ON e9.EMPLOYEEID=aps.APPROVEDBY
								LEFT JOIN employee e10 ON e10.EMPLOYEEID=aps.PRINCIPALBY
								LEFT JOIN employee e11 ON e11.EMPLOYEEID=aps.DIVBY
								WHERE c.STATUS=1 AND aps.STATUS=1
								$whereappno
								$whererank
								$whereoption
								$wheredate
								$wheremgmt
								ORDER BY r.RANKING
						") or die(mysql_error());
	
echo "
<html>\n
<head>\n
<title>Applicant Monitor</title>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>
<script language=\"javascript\" src=\"popcalendar.js\"></script>	

<body onload=\"\" style=\"overflow:hidden;background-color:White;\">\n

<form name=\"formapplicantmonitor\" method=\"POST\">\n


								<div id=\"multiple\" style=\"position:absolute;top:200px;left:200px;width:600px;height:400px;background-color:#6699FF;
												border:2px solid black;overflow:auto;$showmultiple \">
									<span class=\"sectiontitle\">MULTIPLE ENTRY FOUND</span>
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
										if ($multiple == 1)
										{
											while ($rowmultisearch = mysql_fetch_array($qrysearch))
											{
												$appno = $rowmultisearch["APPLICANTNO"];
												
												$qrygetinfo = mysql_query("SELECT APPLICANTNO,CREWCODE,FNAME,GNAME,MNAME,STATUS
																			FROM crew 
																			WHERE APPLICANTNO=$appno AND STATUS=1
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
												<tr $mouseovereffect style=\"cursor:pointer;\" ondblclick=\"
																applicantno.value='$info1';submit();
																document.getElementById('multiple').style.display='none';
																\">
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
											
									echo "
									</table>
									<br />
									<center>
										<a href=\"#\" style=\"font-size:0.8em;font-weight:Bold;\" onclick=\"document.getElementById('multiple').style.display='none';\">Close Window</a>
									</center>
									<br />
								</div>



<span class=\"wintitle\">APPLICANT MONITOR</span>

	<div style=\"width:100%;height:650px;\">

		<center>
		<table style=\"width:70%;margin-top:5px;\" border=1>
			<tr>
				<td align=\"left\" style=\"font-size:0.8em;font-weight:Bold;\">Search</td>
				<td>
					<select name=\"searchby\" onchange=\"if(this.value != ''){searchkey.disabled=false;searchkey.value='';searchkey.focus();}
														else {searchkey.disabled=true;searchkey.value='';}\">
						<option value=\"\">--Select Search Key--</option>
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
						<option $selected1 value=\"1\">APPLICANT NO</option>
						<option $selected2 value=\"2\">CREW CODE</option>
						<option $selected3 value=\"3\">FAMILY NAME</option>
						<option $selected4 value=\"4\">GIVEN NAME</option>
					</select>
				</td>
				<td align=\"center\"><input type=\"text\" name=\"searchkey\" size=\"20\" value=\"$searchkey\" $disablesearch
						onkeyup=\"this.value=this.value.toUpperCase();if (this.value != '') {btnfind.disabled=false;}\"
						style=\"color:Red;font-weight:Bold;\" onkeydown=\"if(event.keyCode==13) {btnfind.focus();}\"/>
						
					<input type=\"button\" name=\"btnfind\" value=\"Find\" $disablesearch onclick=\"actiontxt.value='find';submit();\" />
				</td>
			</tr>
		</table>
		</center>
		<br />
		
		<div style=\"width:100%;height:140px;margin:3 5 3 5;background-color:White;\">
		";
				
			$style1 = "style=\"font-size:0.8em;font-weight:Bold;color:White;\"";
			$style2 = "style=\"font-size:0.7em;font-weight:Bold;color:Yellow;\"";
				
			echo "
			<div style=\"width:70%;height:100%;float:left;background-color:#292929;\">
			
				<span class=\"sectiontitle\">ADDITIONAL SORTING</span>
			
				<table style=\"width:70%;float:left;\">
					<tr>
						<td $style1>Rank</td>
						<td $style1>:</td>
						<td $style1>
							<select name=\"rankcode\" onchange=\"submit();\">
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
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td $style1 valign=\"top\">Show the following</td>
						<td $style1 valign=\"top\">:</td>
						<td $style2>
							<input type=\"radio\" name=\"show\" value=\"1\" $show1 style=\"cursor:pointer;\" onclick=\"submit();\" />Show All 
							<input type=\"radio\" name=\"show\" value=\"2\" $show2 style=\"cursor:pointer;\" onclick=\"submit();\" />Scholars Only
						</td>
					</tr>
					<tr>
						<td $style1 colspan=\"3\">
							Date Range&nbsp;:&nbsp;&nbsp;
							From &nbsp;
								<input type=\"text\" name=\"transdatefrom\" value=\"$transdatefrom\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
								<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(transdatefrom, transdatefrom, 'mm/dd/yyyy', 0, 0);return false;\">
							&nbsp;&nbsp;
							To &nbsp;
								<input type=\"text\" name=\"transdateto\" value=\"$transdateto\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
								<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(transdateto, transdateto, 'mm/dd/yyyy', 0, 0);return false;\">
							<br />
							
	
						</td>
					</tr>
				</table>
				
				<table style=\"width:30%;float:left;border-left:thin dashed white;\">
					<tr>
						<td align=\"center\">
							<input type=\"button\" value=\"Refresh List\" onclick=\"searchkey.value='';submit();\"
								style=\"font-size:1.2em;font-weight:Bold;border:thin solid White;background-color:#FF0101;color:Yellow;\" />
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td $style1 align=\"center\"><input type=\"checkbox\" $chkmanagement name=\"chkmanagement\" 
													value=\"1\" onclick=\"submit();\" />Show Management Approved Only!</td>
					</tr>
				</table>
			</div>
			
			<div style=\"width:30%;height:100%;float:right;background-color:White;\">
				
				<span class=\"sectiontitle\">ID PICTURE</span>
	";
				$dirfilename = $basedirid . $applicantno . ".JPG";
				if (checkpath($dirfilename))
				{
					$scale = imageScale($dirfilename,-1,150);
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
		
		<div style=\"width:100%;height:450px;background-color:#DCDCDC;margin:3 5 3 5;border:1px solid Black;overflow:auto;\">
			<span class=\"sectiontitle\">CURRENT STATUS OF APPLICATION</span>
			
			<table width=\"100%\" cellpadding=\"5\" cellspacing=\"1\" style=\"font-size:0.7em;background-color:#F0F1E9;border:1px solid black;\">
				<tr style=\"font-weight:bold;background-color:Black;color:White;\">
					<td width=\"8%\" align=\"center\">TYPE</td>
					<td width=\"20%\" align=\"center\">NAME</td>
					<td width=\"8%\" align=\"center\">VESSEL</td>
					<td width=\"8%\" align=\"center\">ENDORSEMENT</td>
					<td width=\"8%\" align=\"center\">DOCUMENT ENCODING</td>
					<td width=\"8%\" align=\"center\">CHARACTER CHECK</td>
					<td width=\"8%\" align=\"center\">FLEET APPROVAL</td>
					<td width=\"8%\" align=\"center\">EXAM EVALUATION</td>
					<td width=\"8%\" align=\"center\">DIVISION APPROVAL</td>
					<td width=\"8%\" align=\"center\">MGMT APPROVAL</td>
					<td width=\"8%\" align=\"center\">PRINCIPAL APPROVAL</td>
				</tr>
			
		";
		$tmpvmcrankcode = "";
		
		$styledtl = "style=\"font-weight:0.7em;border-bottom:1px solid Gray;border-left:1px dashed Blue;\"";
		$format1 = "dMY H:i";
		
		while ($rowcrewlisting = mysql_fetch_array($qrycrewlisting))
		{
			$appno = $rowcrewlisting["APPLICANTNO"];
			if (!empty($rowcrewlisting["NAME"]))
				$crewname = $rowcrewlisting["NAME"];
			else 
				$crewname = "---";
			$crewcode = $rowcrewlisting["CREWCODE"];
			$crewrankalias = $rowcrewlisting["RANKALIAS"];
			$crewrank = $rowcrewlisting["RANK"];
			$crewvessel = $rowcrewlisting["VESSEL"];
			
			$vmcrankcode = $rowcrewlisting["VMCRANKCODE"];
			$vmcvesselcode = $rowcrewlisting["VMCVESSELCODE"];
			$vmcremarks = $rowcrewlisting["VMCREMARKS"];
			
			if (!empty($rowcrewlisting["VMCETD"]))
				$vmcetd = date($format1,strtotime($rowcrewlisting["VMCETD"]));
			else 
				$vmcetd = "";
			
			if (!empty($rowcrewlisting["DATEAPPLIED"]))
				$dateapplied = date($format1,strtotime($rowcrewlisting["DATEAPPLIED"]));
			else 
				$dateapplied = "";
			
			$remarks = $rowcrewlisting["REMARKS"];
			
			$poealisted = $rowcrewlisting["POEALISTED"];
			$vmclisted = $rowcrewlisting["VMCLISTED"];
			$grade = $rowcrewlisting["GRADE"];
			$passed = $rowcrewlisting["PASSED"];
			$evaluation = $rowcrewlisting["EVALUATION"];
			
			$scholarshow = $rowcrewlisting["SCHOLASTICCODE"];
			$scholartitle = $rowcrewlisting["SCHOLARTYPE"];
			
			$scholasticcode = $rowcrewlisting["SCHOLASTICCODE"];
			$scholartype = $rowcrewlisting["SCHOLARTYPE"];
			
			
			
			$endorsedby = $rowcrewlisting["ENDORSEDBY"];
			$endorsedshow = "<b>--------</b><br />";
			
			if (!empty($rowcrewlisting["ENDORSEDDATE"]))
				$endorseddate = date($format1,strtotime($rowcrewlisting["ENDORSEDDATE"]));
			else 
				$endorseddate = "";
				
			$endorsettl = "";
				
			if (!empty($endorseddate))
			{
				$fname1 = $rowcrewlisting["FNAME1"];
				$gname1 = $rowcrewlisting["GNAME1"];
				$mname1 = $rowcrewlisting["MNAME1"];
				
				$endorsettl = $fname1 . ", " . $gname1 . " " . $mname1;
				
				$endorsedshow = "By: " . $endorsedby . "<br />";
				$endorsedshow .= "Date: " . $endorseddate;
			}
				
			$encodedby = $rowcrewlisting["ENCODEDBY"];
			$encodedshow = "<b>--------</b><br />";
			
			if (!empty($rowcrewlisting["ENCODEDDATE"]))
				$encodeddate = date($format1,strtotime($rowcrewlisting["ENCODEDDATE"]));
			else 
				$encodeddate = "";
				
			$encodettl = "";
				
			if(!empty($encodeddate))
			{
				$fname2 = $rowcrewlisting["FNAME2"];
				$gname2 = $rowcrewlisting["GNAME2"];
				$mname2 = $rowcrewlisting["MNAME2"];
				
				$encodettl = $fname2 . ", " . $gname2 . " " . $mname2;
				
				$encodedshow = "By: " . $encodedby . "<br />";
				$encodedshow .= "Date: " . $encodeddate;
			}
				
			$charcheckby = $rowcrewlisting["CHARCHECKBY"];
			$charcheckshow = "<b>--------</b><br />";
			
			if (!empty($rowcrewlisting["CHARCHECKDATE"]))
				$charcheckdate = date($format1,strtotime($rowcrewlisting["CHARCHECKDATE"]));
			else 
				$charcheckdate = "";
				
			$charcheckttl = "";
				
			if(!empty($charcheckdate))
			{
				$fname3 = $rowcrewlisting["FNAME3"];
				$gname3 = $rowcrewlisting["GNAME3"];
				$mname3 = $rowcrewlisting["MNAME3"];
				
				$charcheckttl = $fname3 . ", " . $gname3 . " " . $mname3;
				
				$charcheckshow = "By: " . $charcheckby . "<br />";
				$charcheckshow .= "Date: " . $charcheckdate;
			}
			
			$acceptshow = "<b>--------</b><br />";
			
			$acceptby = $rowcrewlisting["ACCEPTBY"];
			
			if (!empty($rowcrewlisting["ACCEPTDATE"]))
				$acceptdate = date($format1,strtotime($rowcrewlisting["ACCEPTDATE"]));
			else 
				$acceptdate = "";
				
			$acceptttl = "";
				
			if (!empty($acceptdate))
			{
				$fname4 = $rowcrewlisting["FNAME4"];
				$gname4 = $rowcrewlisting["GNAME4"];
				$mname4 = $rowcrewlisting["MNAME4"];
				
				$acceptttl = $fname4 . ", " . $gname4 . " " . $mname4;
				
				$acceptshow = "<b>ACCEPTED</b><br />";
				$acceptshow .= "By: " . $acceptby . "<br />";
				$acceptshow .= "Date: " . $acceptdate;
			}
			else 
			{
				$activefileby = $rowcrewlisting["ACTIVEFILEBY"];
				
				if (!empty($rowcrewlisting["ACTIVEFILEDATE"]))
					$activefiledate = date($format1,strtotime($rowcrewlisting["ACTIVEFILEDATE"]));
				else 
					$activefiledate = "";
					
				if (!empty($activefiledate))
				{
					$fname5 = $rowcrewlisting["FNAME5"];
					$gname5 = $rowcrewlisting["GNAME5"];
					$mname5 = $rowcrewlisting["MNAME5"];
					
					$acceptttl = $fname5 . ", " . $gname5 . " " . $mname5;
					
					$acceptshow = "ACTIVE FILE<br />";
					$acceptshow .= "By: " . $activefileby . "<br />";
					$acceptshow .= "Date: " . $activefiledate;
				}
				else 
				{
					$reservedby = $rowcrewlisting["RESERVEDBY"];
					
					if (!empty($rowcrewlisting["RESERVEDDATE"]))
						$reserveddate = date($format1,strtotime($rowcrewlisting["RESERVEDDATE"]));
					else 
						$reserveddate = "";
						
					if(!empty($reserveddate))
					{
						$fname6 = $rowcrewlisting["FNAME6"];
						$gname6 = $rowcrewlisting["GNAME6"];
						$mname6 = $rowcrewlisting["MNAME6"];
						
						$acceptttl = $fname6 . ", " . $gname6 . " " . $mname6;
						
						$acceptshow = "RESERVED<br />";
						$acceptshow .= "By: " . $reservedby . "<br />";
						$acceptshow .= "Date: " . $reserveddate;
					}
				}
			}
			
			
			
			$examevalby = $rowcrewlisting["EXAMEVALBY"];
			$examevalshow = "<b>--------</b><br />";
			
			if (!empty($rowcrewlisting["EXAMEVALDATE"]))
				$examevaldate = date($format1,strtotime($rowcrewlisting["EXAMEVALDATE"]));
			else 
				$examevaldate = "";
				
			$examevalttl = "";
				
			if(!empty($examevaldate))
			{
				$fname7 = $rowcrewlisting["FNAME7"];
				$gname7 = $rowcrewlisting["GNAME7"];
				$mname7 = $rowcrewlisting["MNAME7"];
				
				$examevalttl = $fname7 . ", " . $gname7 . " " . $mname7;
				
				$examevalshow = "By: " . $examevalby . "<br />";
				$examevalshow .= "Date: " . $examevaldate . "<br />";
				$examevalshow .= "Grade: " . $grade;
			}
			
			$approvedshow = "<b>--------</b><br />";
			
			$approvedby = $rowcrewlisting["APPROVEDBY"];
			
			if (!empty($rowcrewlisting["APPROVEDDATE"]))
				$approveddate = date($format1,strtotime($rowcrewlisting["APPROVEDDATE"]));
			else 
				$approveddate = "";
				
			$approvettl = "";
				
			if(!empty($approveddate))
			{
				$fname9 = $rowcrewlisting["FNAME9"];
				$gname9 = $rowcrewlisting["GNAME9"];
				$mname9 = $rowcrewlisting["MNAME9"];
				
				$approvettl = $fname9 . ", " . $gname9 . " " . $mname9;
				
				$approvedshow = "<b>APPROVED</b><br />";
				$approvedshow .= "By: " . $approvedby . "<br />";
				$approvedshow .= "Date: " . $approveddate;
			}
			
			if (empty($approveddate))
			{
				$holdby = $rowcrewlisting["HOLDBY"];
				
				if (!empty($rowcrewlisting["HOLDDATE"]))
					$holddate = date($format1,strtotime($rowcrewlisting["HOLDDATE"]));
				else 
					$holddate = "";
					
				if(!empty($holddate))
				{
					$fname8 = $rowcrewlisting["FNAME8"];
					$gname8 = $rowcrewlisting["GNAME8"];
					$mname8 = $rowcrewlisting["MNAME8"];
					
					$approvettl = $fname8 . ", " . $gname8 . " " . $mname8;
					
					$approvedshow = "<b>ON HOLD</b><br />";
					$approvedshow .= "By: " . $holdby . "<br />";
					$approvedshow .= "Date: " . $holddate;
				}
			}
			
			$principalby = $rowcrewlisting["PRINCIPALBY"];
			$principalshow = "<b>--------</b><br />";
			
			if (!empty($rowcrewlisting["PRINCIPALDATE"]))
				$principaldate = date($format1,strtotime($rowcrewlisting["PRINCIPALDATE"]));
			else 
				$principaldate = "";
				
			$principalttl = "";
				
			if(!empty($principaldate))
			{
				$fname10 = $rowcrewlisting["FNAME10"];
				$gname10 = $rowcrewlisting["GNAME10"];
				$mname10 = $rowcrewlisting["MNAME10"];
				
				$principalttl = $fname10 . ", " . $gname10 . " " . $mname10;
				
				$principalshow = "By: " . $principalby . "<br />";
				$principalshow .= "Date: " . $principaldate;
			}
			
			$divby = $rowcrewlisting["DIVBY"];
			$divshow = "<b>--------</b><br />";
			
			if (!empty($rowcrewlisting["DIVDATE"]))
				$divdate = date($format1,strtotime($rowcrewlisting["DIVDATE"]));
			else 
				$divdate = "";
				
			$divttl = "";
				
			if(!empty($divdate))
			{
				$fname11 = $rowcrewlisting["FNAME11"];
				$gname11 = $rowcrewlisting["GNAME11"];
				$mname11 = $rowcrewlisting["MNAME11"];
				
				$divttl = $fname11 . ", " . $gname11 . " " . $mname11;
				
				$divshow = "By: " . $divby . "<br />";
				$divshow .= "Date: " . $divdate;
			}
			
			if ($tmpvmcrankcode != $vmcrankcode)
			{
				echo "
				<tr>
					<td style=\"font-size:1.2em;font-weight:Bold;color:Yellow;background-color:Green;\" colspan=\"11\"><u>$crewrank</u></td>
				</tr>
				";
			}
			
	echo "	<tr $mouseovereffect style=\"cursor:pointer;\">
				<td style=\"font-weight:0.6em;font-weight:Bold;color:Red;border-bottom:1px solid Gray;border-left:1px dashed Blue;text-align:center;\" title=\"$scholartitle\" >&nbsp;$scholarshow</td>
				<td $styledtl valign=\"top\">
					<b>
						<u>$crewname</u><br />
						Crew Code:&nbsp;$crewcode<br />
						Applicant No.:&nbsp;$appno
					";
						if (empty($approveddate))
						{
							echo "<input type=\"button\" value=\"Drop\" style=\"cursor:pointer;font-size:0.8em;background-color:Red;border:1px solid White;color:Yellow;\"
								onclick=\"if(confirm('This will CANCEL Applicant $crewname. Continue?')){actiontxt.value='drop';applicantno.value='$appno';submit();}\" />";
						}
						
					echo "
						<br />
						Date Applied:&nbsp;$dateapplied<br />
					</b>
						
				</td>
				<td $styledtl valign=\"top\" align=\"center\">&nbsp;$crewvessel</td>
				<td $styledtl valign=\"top\" title=\"$endorsettl\">$endorsedshow</td>
				<td $styledtl valign=\"top\" title=\"$encodettl\">$encodedshow</td>
				<td $styledtl valign=\"top\" title=\"$charcheckttl\">$charcheckshow</td>
				<td $styledtl valign=\"top\" title=\"$acceptttl\">$acceptshow</td>
				<td $styledtl valign=\"top\" title=\"$examevalttl\">$examevalshow</td>
				<td $styledtl valign=\"top\" title=\"$divttl\">$divshow</td>
				<td $styledtl valign=\"top\" title=\"$approvettl\">$approvedshow</td>
				<td $styledtl valign=\"top\" title=\"$principalttl\">$principalshow</td>
			</tr>
	";
			$tmpvmcrankcode = $vmcrankcode;
		}
	echo "
		</table>
		</div>

	</div>
	
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"applicantno\" value=\"$applicantno\" />
	
</form>

</body>
";
?>