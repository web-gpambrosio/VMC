<?php

include("veritas/connectdb.php");
include("veritas/include/stylephp.inc");
include("veritas/include/functions.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');
$dateformat = "dMY";

if(isset($_SESSION['employeeid']))
	$employeeid=$_SESSION['employeeid'];

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];

if (isset($_POST['listed_tmp']))
	$listed_tmp = $_POST['listed_tmp'];
	
if (isset($_POST['applicantno']))
	$applicantno = $_POST['applicantno'];
	
if (isset($_POST['vmcrankcode']))
	$vmcrankcode = $_POST['vmcrankcode'];
	
if (isset($_POST['vmcidno']))
	$vmcidno = $_POST['vmcidno'];
	
if (isset($_POST['poeaidno']))
	$poeaidno = $_POST['poeaidno'];
	
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
	$transdateto = date('m/d/Y',strtotime($currentdate));
	
$transdatefromraw = date('Y-m-d',strtotime($transdatefrom));
$transdatetoraw = date('Y-m-d',strtotime($transdateto));

if (isset($_POST["searchkey"]))
	$searchkey = $_POST["searchkey"];
	
if (isset($_POST["searchby"]))
	$searchby = $_POST["searchby"];

switch ($actiontxt)
{
	case "endorse"	:
			$scholar = 0;
			$fasttrack = 0;
			$rankallow = 0;
			
			$qryverify = mysql_query("SELECT APPLICANTNO FROM applicantstatus WHERE APPLICANTNO=$applicantno") or die(mysql_error());
		
			$qryscholar = mysql_query("SELECT APPLICANTNO FROM crewscholar cs WHERE APPLICANTNO=$applicantno") or die(mysql_error());
			$qryfasttrack = mysql_query("SELECT APPLICANTNO FROM crewfasttrack cf WHERE APPLICANTNO=$applicantno") or die(mysql_error());
			
			if ($rowscholar=mysql_num_rows($qryscholar) > 0)
				$scholar = 1;	
				
			if ($rowfasttrack=mysql_num_rows($qryfasttrack) > 0)
				$fasttrack = 1;
				
			if ($vmcrankcode == 'D41' || $vmcrankcode == 'D49' || $vmcrankcode == 'E41' || $vmcrankcode == 'E49')
				$rankallow = 1;
			
			if (mysql_num_rows($qryverify) > 0)
			{
				if ($scholar == 0 && $fasttrack == 0 && $rankallow == 0)
				{
					$qryendorseupdate = mysql_query("UPDATE applicantstatus SET ENDORSEDBY='$employeeid',
																		ENDORSEDDATE='$currentdate',
																		VMCRANKCODE='$vmcrankcode'
													WHERE APPLICANTNO=$applicantno
												") or die(mysql_error());
				}
				else 
				{
					$qryendorseupdate = mysql_query("UPDATE applicantstatus SET ENDORSEDBY='$employeeid',
																		ENDORSEDDATE='$currentdate',
																		VMCRANKCODE='$vmcrankcode',
																		CHARCHECKBY='$employeeid',
																		CHARCHECKDATE='$currentdate'
													WHERE APPLICANTNO=$applicantno
												") or die(mysql_error());
				}
			}
			else 
			{
				if ($scholar == 0 && $fasttrack == 0 && $rankallow == 0)
				{
					$qryendorseinsert = mysql_query("INSERT INTO applicantstatus(APPLICANTNO,ENDORSEDBY,ENDORSEDDATE,VMCRANKCODE) 
										VALUES($applicantno,'$employeeid','$currentdate','$vmcrankcode')") or die(mysql_error());
				}
				else 
				{
					$qryendorseinsert = mysql_query("INSERT INTO applicantstatus(APPLICANTNO,ENDORSEDBY,ENDORSEDDATE,CHARCHECKBY,CHARCHECKDATE,VMCRANKCODE) 
										VALUES($applicantno,'$employeeid','$currentdate','$employeeid','$currentdate','$vmcrankcode')") or die(mysql_error());
				}
			}
		
		break;
		
	case "unendorse":
		
			$qrywatchhdr = mysql_query("SELECT IDNO FROM applicantwatchhdr WHERE APPLICANTNO=$applicantno") or die(mysql_error());
			
			if (mysql_num_rows($qrywatchhdr) > 0)
			{
				while ($rowwatchhdr = mysql_fetch_array($qrywatchhdr))
				{
					$idno = $rowwatchhdr["IDNO"];
					$qrydelwatchdtl = mysql_query("DELETE FROM applicantwatchdtl WHERE IDNO=$idno") or die(mysql_error());
				}
			}
		
			$qryunendorse = mysql_query("DELETE FROM applicantstatus WHERE APPLICANTNO=$applicantno") or die(mysql_error());
		
		break;
		
	case "secondchance":
		
		if (!empty($vmcidno))
		{
			$qryupdate1 = mysql_query("UPDATE watchlist_veritas SET SECONDCHANCE=1 WHERE IDNO=$vmcidno") or die(mysql_error());
		}
		
		if (!empty($poeaidno))
		{
			$qryupdate2 = mysql_query("UPDATE watchlist_poea SET SECONDCHANCE=1 WHERE IDNO=$poeaidno") or die(mysql_error());
		}
		
		break;
		
	case "delete"	:
		
			$qryverify = mysql_query("SELECT APPLICANTNO FROM applicantstatus WHERE APPLICANTNO=$applicantno") or die(mysql_error());
			
			if (mysql_num_rows($qryverify) > 0)
			{
				$qrydelete1 = mysql_query("UPDATE applicantstatus SET STATUS=0 WHERE APPLICANTNO=$applicantno") or die(mysql_error());				
			}
		
			$qrydelete2 = mysql_query("UPDATE crew SET STATUS=0,MADEBY='$employeeid',MADEDATE='$currentdate' WHERE APPLICANTNO=$applicantno") or die(mysql_error());
		
		break;
}


switch ($searchby)
{
	case "1"	: //APPLICANT NO
	
			$wherefind = "AND APPLICANTNO LIKE '$searchkey%'";
		
		break;
	case "2"	: //CREW CODE
	
			$wherefind = "AND CREWCODE LIKE '$searchkey%'";
		
		break;
	case "3"	: //FAMILY NAME
	
			$wherefind = "AND FNAME LIKE '$searchkey%'";
		
		break;
	case "4"	: //GIVEN NAME
	
			$wherefind = "AND GNAME LIKE '$searchkey%'";
		
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

//if (!empty($appno_single))
//	$whereappno = "AND a.APPLICANTNO=$appno_single";
//else 
//	$whereappno = "";


$qrypersonallist = mysql_query("SELECT a.ONLINE,a.APPLICANTNO,CONCAT(FNAME,', ',GNAME,' ' ,MNAME) AS NAME, FNAME,GNAME,c.UTILITY,
								c.ADDRESS,c.MUNICIPALITY,c.CITY,c.ZIPCODE,ab.BARANGAY,at.TOWN,ap.PROVINCE,
								TELNO,EMAIL,rc1.RANK AS CHOICE1,rc2.RANK AS CHOICE2,rc1.RANKCODE AS RANKCODE1,rc2.RANKCODE AS RANKCODE2,
								DATEAPPLIED,IF(c.RECOMMENDEDBY IS NULL,a.RECOMMENDEDBY,c.RECOMMENDEDBY) AS RECOMMENDEDBY,a.DATEAPPLIED,aps.ENDORSEDBY,aps.ENDORSEDDATE,
								aps.ENCODEDDATE,aps.CHARCHECKDATE,aps.ACCEPTDATE,aps.ACCEPTBY,
								IF(cs.APPLICANTNO IS NULL,0,1) AS SCHOLAR,s.DESCRIPTION AS SCHOLARTYPE,cs.SCHOLASTICCODE,
								IF(cf.APPLICANTNO IS NULL,0,1) AS FASTTRACK,f.FASTTRACK AS FASTRK,cf.FASTTRACKCODE
								FROM crew c
								LEFT JOIN applicant a ON a.APPLICANTNO=c.APPLICANTNO
								LEFT JOIN rank rc1 ON rc1.RANKCODE=a.CHOICE1
								LEFT JOIN rank rc2 ON rc2.RANKCODE=a.CHOICE2
								LEFT JOIN addrprovince ap ON ap.PROVCODE=c.PROVINCECODE
								LEFT JOIN addrtown at ON at.TOWNCODE=c.TOWNCODE AND at.PROVCODE=c.PROVINCECODE
								LEFT JOIN addrbarangay ab ON ab.BRGYCODE=c.BARANGAYCODE AND ab.TOWNCODE=c.TOWNCODE AND ab.PROVCODE=c.PROVINCECODE
								LEFT JOIN rank r ON r.RANKCODE=a.CHOICE1
								LEFT JOIN ranklevel rl ON rl.RANKLEVELCODE=r.RANKLEVELCODE
								LEFT JOIN ranktype rt ON rt.RANKTYPECODE=r.RANKTYPECODE
								LEFT JOIN applicantstatus aps ON aps.APPLICANTNO=a.APPLICANTNO
								LEFT JOIN crewscholar cs ON cs.APPLICANTNO=c.APPLICANTNO
								LEFT JOIN scholar s ON s.SCHOLASTICCODE=cs.SCHOLASTICCODE
								LEFT JOIN crewfasttrack cf ON cf.APPLICANTNO=c.APPLICANTNO
								LEFT JOIN fasttrack f ON f.FASTTRACKCODE=cf.FASTTRACKCODE
								WHERE a.DATEAPPLIED BETWEEN '$transdatefromraw 00:00:00' AND '$transdatetoraw 23:59:59'
								AND a.CHOICE1 IS NOT NULL AND a.AGREED = 1 AND c.STATUS=1
								AND aps.APPROVEDDATE IS NULL AND (aps.STATUS IS NULL OR aps.STATUS=1)
								$whererank
								$wherefind
								ORDER BY r.RANKING,a.DATEAPPLIED,NAME
								") or die(mysql_error());

//$qryscholarlist = mysql_query("SELECT a.APPLICANTNO,CONCAT(FNAME,', ',GNAME,' ' ,MNAME) AS NAME, FNAME,GNAME,
//								c.ADDRESS,c.MUNICIPALITY,c.CITY,c.ZIPCODE,ab.BARANGAY,at.TOWN,ap.PROVINCE,
//								TELNO,EMAIL,rc1.RANK AS CHOICE1,rc2.RANK AS CHOICE2,rc1.RANKCODE AS RANKCODE1,rc2.RANKCODE AS RANKCODE2,
//								DATEAPPLIED,IF(c.RECOMMENDEDBY IS NULL,a.RECOMMENDEDBY,c.RECOMMENDEDBY) AS RECOMMENDEDBY,a.DATEAPPLIED,aps.ENDORSEDBY,aps.ENDORSEDDATE,
//								aps.ENCODEDDATE,aps.CHARCHECKDATE,aps.ACCEPTDATE,aps.ACCEPTBY
//								FROM crew c
//								LEFT JOIN applicant a ON a.APPLICANTNO=c.APPLICANTNO
//								LEFT JOIN rank rc1 ON rc1.RANKCODE=a.CHOICE1
//								LEFT JOIN rank rc2 ON rc2.RANKCODE=a.CHOICE2
//								LEFT JOIN addrprovince ap ON ap.PROVCODE=c.PROVINCECODE
//								LEFT JOIN addrtown at ON at.TOWNCODE=c.TOWNCODE AND at.PROVCODE=c.PROVINCECODE
//								LEFT JOIN addrbarangay ab ON ab.BRGYCODE=c.BARANGAYCODE AND ab.TOWNCODE=c.TOWNCODE AND ab.PROVCODE=c.PROVINCECODE
//								LEFT JOIN rank r ON r.RANKCODE=a.CHOICE1
//								LEFT JOIN ranklevel rl ON rl.RANKLEVELCODE=r.RANKLEVELCODE
//								LEFT JOIN ranktype rt ON rt.RANKTYPECODE=r.RANKTYPECODE
//								LEFT JOIN applicantstatus aps ON aps.APPLICANTNO=a.APPLICANTNO
//								LEFT JOIN crewscholar cs ON cs.APPLICANTNO=c.APPLICANTNO
//								WHERE a.DATEAPPLIED BETWEEN '$transdatefromraw 00:00:00' AND '$transdatetoraw 23:59:59'
//								AND a.CHOICE1 IS NOT NULL AND a.AGREED = 1 AND c.STATUS=1
//								AND cs.APPLICANTNO IS NOT NULL AND aps.APPROVEDDATE IS NULL AND (aps.STATUS IS NULL OR aps.STATUS=1)
//								$whererank
//								ORDER BY r.RANKING,a.DATEAPPLIED,NAME
//								") or die(mysql_error());
			
$poea_id = 0;
$vmc_id = 0;

echo "
<html>\n
<head>\n
<title>
Applicant Screening
</title>

<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>
<script language=\"javascript\" src=\"popcalendar.js\"></script>	
</head>
<body style=\"overflow:hidden;\">\n

<form name=\"screening\" method=\"POST\">\n

<span class=\"wintitle\">APPLICANT SCREENING</span>

	<div style=\"width:100%;\">
		<table class=\"listrow\" width=\"100%\" border=1 style=\"padding:5px;\">
			<tr>
				<td align=\"center\" width=\"10%\"><b>Transaction Date</b></td>
				<td align=\"center\" width=\"10%\"><b>From</b> <br />
					<input type=\"text\" name=\"transdatefrom\" value=\"$transdatefrom\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(transdatefrom, transdatefrom, 'mm/dd/yyyy', 0, 0);return false;\">
				</td>
				<td align=\"center\" width=\"10%\"><b>To</b> <br />
					<input type=\"text\" name=\"transdateto\" value=\"$transdateto\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(transdateto, transdateto, 'mm/dd/yyyy', 0, 0);return false;\">
				</td>
				<td align=\"center\" width=\"20%\"><b>Choose Rank</b> <br />
					<select name=\"rankcode\" onchange=\"screening.submit();\">
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
					
					<input type=\"button\" value=\"GO\" onclick=\"submit();\" />
					
				</td>
			</tr>
			
			<tr>
				<td colspan=\"4\">
					<table width=\"95%\" style=\"font-weight:Bold;\">
						<tr>
							<th>Search Key &nbsp;&nbsp;
								<select name=\"searchby\" onchange=\"searchkey.focus();\">
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
									<option $selected3 value=\"3\">FAMILY NAME</option>
									<option $selected2 value=\"2\">CREW CODE</option>
									<option $selected1 value=\"1\">APPLICANT NO</option>
									<option $selected4 value=\"4\">GIVEN NAME</option>
								</select>
							</th>
							<td><input type=\"text\" name=\"searchkey\" size=\"40\" value=\"$searchkey\" onkeyup=\"this.value=this.value.toUpperCase()\" 
									style=\"color:Red;font-weight:Bold;\" onkeydown=\"if(event.keyCode==13) {btnfind.focus();}\"/>
									
								<input type=\"button\" name=\"btnfind\" value=\"Find\" onclick=\"if(searchby.value != '' && searchkey.value != '')
											{submit();}
											else
											{alert('Invalid Search Key. Please try again.');}
											\" />
								&nbsp;&nbsp;
								<span style=\"background-color:Yellow;font-weight:Bold;color:Red;\">&nbsp;&nbsp;Note: Applied ONLINE!&nbsp;&nbsp;</span>
							</td>
						</tr>
					</table>
				
				</td>
			</tr>
		</table>
	</div>

	
	<span class=\"sectiontitle\">NEW APPLICANTS</span>
	<div style=\"padding:10px;width:100%;height:550px;background-color:#DCDCDC;overflow:auto;\">
		
		<table style=\"width:100%;\" class=\"listcol\">
			<tr>
			<!--	<th width=\"5%\" align=\"center\"><u>APPNO</u></th>		-->
				<th width=\"5%\" align=\"center\"><u>DATE</u></th>
				<th width=\"25%\" align=\"center\"><u>NAME</u></th>
				<th width=\"25%\" align=\"center\"><u>ADDRESS</u></th>
				<th width=\"10%\" align=\"center\"><u>TELNO</u></th>
				<th width=\"5%\" align=\"center\"><u>EMAIL</u></th>
				<th width=\"30%\" align=\"center\"><u>ACTION</U></th>
			</tr>
	";
		$tmprankcode = "";
		
		$styledtl = "style=\"font-weight:0.7em;\"";
		
		while ($rowpersonallist = mysql_fetch_array($qrypersonallist))
		{
			$showlisted = "";
			$poea_id = 0;
			$vmc_id = 0;
			
			$online = $rowpersonallist["ONLINE"];
			
			switch($online)
			{
			    case "0" : $styleonline = ""; break;
			    case "1" : $styleonline = "style=\"background-color:Yellow;\""; break;
			    case "2" : $styleonline = "style=\"background-color:Lime;\""; break;
			}

		//	if ($online == 1)
			//	$styleonline = "style=\"background-color:Yellow;\"";
		//	else
			//	$styleonline = "";
			
			$appno1 = $rowpersonallist["APPLICANTNO"];
			$choice1 = $rowpersonallist["CHOICE1"];
			$rankcode1 = $rowpersonallist["RANKCODE1"];
			$rankcode2 = $rowpersonallist["RANKCODE2"];
			$name = $rowpersonallist["NAME"];
			$fname = $rowpersonallist["FNAME"];
			$gname = $rowpersonallist["GNAME"];
			
			$listed = checkwatchlist($appno1,$vmc_id,$poea_id);
			
			switch ($listed)
			{
				case 0	: $showlisted = ""; break;
				case 1	: $showlisted = "VMC Watchlist!";  break;
				case 2	: $showlisted = "POEA Watchlist!"; break;
				case 3	: $showlisted = "VMC & POEA Watchlist!"; break;
			}
			
//			$address = $rowpersonallist["ADDRESS"];
//			$address1 = $rowpersonallist["ADDRESS1"];
			$telno = $rowpersonallist["TELNO"];
			$email = $rowpersonallist["EMAIL"];
			$dateapplied = date($dateformat,strtotime($rowpersonallist["DATEAPPLIED"]));
			
			$address = $rowpersonallist["ADDRESS"];
			$municipality = $rowpersonallist["MUNICIPALITY"];
			$city = $rowpersonallist["CITY"];
			$zipcode = $rowpersonallist["ZIPCODE"];
			$barangay = $rowpersonallist["BARANGAY"];
			$town = $rowpersonallist["TOWN"];
			$province = $rowpersonallist["PROVINCE"];
			
			$scholarstat = $rowpersonallist["SCHOLAR"]; //Determine if Crew is Scholar (0 - Not Scholar;1 - Scholar)
			$scholartype = $rowpersonallist["SCHOLARTYPE"]; 
			$scholarcode = $rowpersonallist["SCHOLASTICCODE"];
			
			$fastrackstat = $rowpersonallist["FASTTRACK"];
			$fastracktype = $rowpersonallist["FASTRK"];
			$fastrackcode = $rowpersonallist["FASTTRACKCODE"];
			
			$utilitystat = $rowpersonallist["UTILITY"];
			
			$endorsedby = $rowpersonallist["ENDORSEDBY"];
			
			if (!empty($rowpersonallist["ENDORSEDDATE"]))
				$endorseddate = date($dateformat,strtotime($rowpersonallist["ENDORSEDDATE"]));
			else 
				$endorseddate = "";
			
			if (!empty($rowpersonallist["ENCODEDDATE"]))
				$encodeddate = date($dateformat,strtotime($rowpersonallist["ENCODEDDATE"]));
			else 
				$encodeddate = "";
			
			if (!empty($rowpersonallist["CHARCHECKDATE"]))
				$charcheckdate = date($dateformat,strtotime($rowpersonallist["CHARCHECKDATE"]));
			else 
				$charcheckdate = "";
			
			$acceptby = $rowpersonallist["ACCEPTBY"];
				
			if (!empty($rowpersonallist["ACCEPTDATE"]))
				$acceptdate = date($dateformat,strtotime($rowpersonallist["ACCEPTDATE"]));
			else 
				$acceptdate = "";
			
			if ($tmprankcode != $rankcode1)
			{
				echo "
				<tr>
					<td style=\"font-size:1.2em;font-weight:Bold;color:Yellow;background-color:Gray;\" colspan=\"7\"><u>$choice1</u></td>
				</tr>
				";
			}
			
			$scholarshow = "";
			
			if ($scholarstat == 1)
				$scholarshow = "<span style=\"background-color:Red;color:Yellow;font-size:0.9em;font-weight:Bold;\" title=\"$scholartype\">&nbsp;$scholarcode&nbsp;</span>";
			else 
				$scholarshow = "";
			
			$ftshow = "";
			
			if ($fastrackstat == 1)
				$ftshow = "<span style=\"background-color:Black;color:Lime;font-size:0.9em;font-weight:Bold;\" title=\"$fastracktype\">&nbsp;$fastrackcode&nbsp;</span>";
			else 
				$ftshow = "";
			
			$utilityshow = "";
			
			if ($utilitystat == 1)
				$utilityshow = "<span style=\"background-color:Magenta;color:Black;font-size:0.9em;font-weight:Bold;\" title=\"UTILITY\">&nbsp;UT&nbsp;</span>";
			else 
				$utilityshow = "";
			
	echo "	<tr $mouseovereffect>
			<!--	<td valign=\"top\" $styledtl $styleonline>$appno1</td>	-->
				<td valign=\"top\" $styledtl $styleonline>$dateapplied</td>
				<td valign=\"top\" $styledtl $styleonline title=\"$appno1\">
					<a href=\"javascript:openWindow('repcrewdatasheet.php?applicantno=$appno1&print=1', 'crewsheet', 1020, 700);\" title=\"Click to view Data Sheet.\">
					$scholarshow $ftshow $utilityshow <b>$name</b></a>
					
					<br />
				";
					if ($listed != 0)
					{
						echo "
						<span style=\"font-weight:Bold;color:red;\">$showlisted</span>
						<input type=\"button\" value=\"Ignore\" onclick=\"if(confirm('Re-hire person even if LISTED, confirm?'))
									{actiontxt.value='secondchance';
									vmcidno.value='$vmc_id';poeaidno.value='$poea_id';
									submit();}\" />
						";
					}
				echo "
				</td>
				";
	
				$showaddress1 = $address . " " . $municipality . " " . $city . " " . $zipcode;
//				$showaddress2 = $barangay . " " . $town . " " . $province;
	
				echo "
				<td valign=\"top\" $styledtl $styleonline>$showaddress1</td>
				<td valign=\"top\" $styledtl $styleonline>&nbsp;$telno</td>
				<td valign=\"top\" $styledtl $styleonline>&nbsp;<a href=\"mailto:$email\" title=\"Send Email Now!\">$email</a></td>
				<td valign=\"top\" align=\"center\" $styledtl $styleonline>
				";
				if ($listed == 0)
				{
					if (empty($endorseddate))
					{
						echo "<input type=\"button\" value=\"Endorse\" style=\"font-size:0.7em;font-weight:Bold;color:Green;\" onclick=\"
								applicantno.value='$appno1';actiontxt.value='endorse';
								vmcrankcode.value='$rankcode1';submit();\" />
							";
						echo "<input type=\"button\" value=\"Delete\" style=\"font-size:0.7em;font-weight:Bold;color:Red;\" onclick=\"if(confirm('Delete Applicant - $name ?'))
								{applicantno.value='$appno1';actiontxt.value='delete';vmcrankcode.value='$rankcode1';submit();}
								\" />
							";
					}
					else 
					{
						if (!empty($acceptdate))
						{
							echo "
								<span title=\"Accepted By: $acceptby \nDate:$acceptdate\" $styledtl>
									Endorsed By:$endorsedby<br />
									Date:$endorseddate
								</span>
							";
						}
						else 
						{
							echo "
								<a href=\"#\" title=\"Click to Undo Endorsement...\" 
									onclick=\"if('$endorsedby' == '$employeeid'){applicantno.value='$appno1';actiontxt.value='unendorse';submit();}
											else {alert('Cannot Undo! Endorsed by $endorsedby.');}\">
								<span $styledtl>
									<b>Endorsed By:$endorsedby<br />
									Date:$endorseddate
									</b>
								</span>
								</a>
							";
						}
					}
				}
				else 
				{
					echo "<span style=\"font-weight:Bold;color:red;\">WATCHLISTED!</span>";
				}
				echo "
				</td>
			</tr>
	";
			$tmprankcode = $rankcode1;
		}
	echo "
		</table>
	</div>

	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"applicantno\" />
	
	<input type=\"hidden\" name=\"poeaidno\" />
	<input type=\"hidden\" name=\"vmcidno\" />
	
	<input type=\"hidden\" name=\"vmcrankcode\" />

</form>
</body>

</html>

";
