<?php

include('veritas/connectdb.php');
include("veritas/include/stylephp.inc");

$currentdate = date('Y-m-d H:i:s');

$display1 = "display:none;";

if (isset($_POST['transdatefrom']))
	$transdatefrom = $_POST['transdatefrom'];
	
if (isset($_POST['transdateto']))
	$transdateto = $_POST['transdateto'];

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];

if (isset($_POST['rankcode']))
	$rankcode = $_POST['rankcode'];
else 
	$rankcode = "All";

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
	
switch ($actiontxt)
{
	case "viewlist"	:
			$display1 = "display:block;";
			
			$transdatefromraw = date('Y-m-d',strtotime($transdatefrom));
			$transdatetoraw = date('Y-m-d',strtotime($transdateto));
/*		
			$qrypersonallist = mysql_query("SELECT CONCAT(FNAME,', ',GNAME,' ' ,MNAME) AS NAME, 
											CONCAT(ADDRESS,', ',BARANGAY,', ',TOWN,' ',PROVINCE) AS ADDRESS,
											CONCAT(ADDRESS,', ',MUNICIPALITY,', ',CITY,' ',ZIPCODE) AS ADDRESS1,
											TELNO,EMAIL,r1.RANK AS CHOICE1,r2.RANK AS CHOICE2,r1.RANKCODE AS RANKCODE1,r2.RANKCODE AS RANKCODE2,
											DATEAPPLIED,RECOMMENDEDBY,a.DATEAPPLIED
											FROM crew c
											LEFT JOIN applicant a ON a.APPLICANTNO=c.APPLICANTNO
											LEFT JOIN rank r1 ON r1.RANKCODE=a.CHOICE1
											LEFT JOIN rank r2 ON r2.RANKCODE=a.CHOICE2
											LEFT JOIN addrprovince ap ON ap.PROVCODE=c.PROVINCECODE
											LEFT JOIN addrtown at ON at.TOWNCODE=c.TOWNCODE AND at.PROVCODE=c.PROVINCECODE
											LEFT JOIN addrbarangay ab ON ab.BRGYCODE=c.BARANGAYCODE AND ab.TOWNCODE=c.TOWNCODE AND ab.PROVCODE=c.PROVINCECODE
											LEFT JOIN rank r ON r.RANKCODE=a.CHOICE1
											LEFT JOIN ranklevel rl ON rl.RANKLEVELCODE=r.RANKLEVELCODE
											LEFT JOIN ranktype rt ON rt.RANKTYPECODE=r.RANKTYPECODE
											WHERE a.DATEAPPLIED BETWEEN '$transdatefromraw 00:00:00' AND '$transdatetoraw 23:59:59'
											AND a.CHOICE1 IS NOT NULL
											ORDER BY r.RANKLEVELCODE,r.RANKTYPECODE,r.RANK,a.DATEAPPLIED,NAME
											") or die(mysql_error());
*/		
			$qrypersonallist = mysql_query("SELECT a.APPLICANTNO,CONCAT(FNAME,', ',GNAME,' ' ,MNAME) AS NAME,a.ONLINE, 
											CONCAT(ADDRESS,', ',BARANGAY,', ',TOWN,' ',PROVINCE) AS ADDRESS,
											CONCAT(ADDRESS,', ',MUNICIPALITY,', ',CITY,' ',ZIPCODE) AS ADDRESS1,
											TELNO,EMAIL,rc1.RANK AS CHOICE1,rc2.RANK AS CHOICE2,rc1.RANKCODE AS RANKCODE1,rc2.RANKCODE AS RANKCODE2,
											DATEAPPLIED,IF(c.RECOMMENDEDBY IS NULL,a.RECOMMENDEDBY,c.RECOMMENDEDBY) AS RECOMMENDEDBY,a.DATEAPPLIED
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
											WHERE a.DATEAPPLIED BETWEEN '$transdatefromraw 00:00:00' AND '$transdatetoraw 23:59:59'
											AND a.CHOICE1 IS NOT NULL AND a.AGREED = 1
											$whererank
											ORDER BY r.RANKLEVELCODE,r.RANKTYPECODE,r.RANK,a.DATEAPPLIED,NAME
											") or die(mysql_error());
			
		break;
}


echo "
<html>

<!-- HEAD START -->
<head>
	<title>Applicant Listing</title>
	<meta http-equiv=\"content-type\" content=\"text/html;charset=utf-8\" />
	<meta name=\"generator\" content=\"Geany 0.9\" />
	
	<script language=\"javascript\" src=\"popcalendar.js\"></script>	
	<script language=\"javascript\" src=\"veripro.js\"></script>	
	<link rel=\"stylesheet\" href=\"veripro.css\">
	<link rel=\"stylesheet\" type=\"text/css\" href=\"veripro_print.css\">
	<script>

				
	</script>
	
</head>

<body>

<form name=\"dailyform\" method=\"POST\">

	<center>
	<div style=\"width:85%;height:600px;padding:2px;border:1px solid black;\">
	
		<span class=\"wintitle\">ONLINE APPLICATION - REPORTS</span>
		<br />
		<table class=\"list\" width=\"100%\">
			<tr>
				<th valign=\"bottom\" align=\"left\">Transaction Date</th>
				<th align=\"center\">From<br />
					<input type=\"text\" name=\"transdatefrom\" value=\"$transdatefrom\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(transdatefrom, transdatefrom, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
				<th align=\"center\">To<br />
					<input type=\"text\" name=\"transdateto\" value=\"$transdateto\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(transdateto, transdateto, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
				<td align=\"center\" width=\"20%\"><b>Choose Rank</b> <br />
					<select name=\"rankcode\" onchange=\"actiontxt.value='viewlist';dailyform.submit();\">
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
				<th valign=\"bottom\" colspan=\"2\" align=\"left\">
					<input type=\"button\" value=\"View\" onclick=\"actiontxt.value='viewlist';dailyform.submit();\" />
					<input type=\"button\" name=\"btnreport\" 
						onclick=\"
						repappdaily.transdatefrom.value=transdatefrom.value;
						repappdaily.transdateto.value=transdateto.value;
						repappdaily.rankcode.value=rankcode.value;
						window.open(repappdaily.action,repappdaily.target,'scrollbars=yes,resizable=yes,channelmode=yes');
						repappdaily.submit();\" value=\"Print Report\">				
				</th>
			</tr>
		</table>
		
		<hr />
		
		<div style=\"width:100%;height:400px;padding:2px;$display1\">
			<table width=\"100%\" class=\"details\">
				<tr>
					<th width=\"10%\" align=\"center\"><u>DATE</u></th>
					<th width=\"40%\" align=\"center\"><u>NAME</u></th>
					<th width=\"30%\" align=\"center\"><u>ADDRESS</u></th>
					<th width=\"10%\" align=\"center\"><u>TELNO</u></th>
					<th width=\"10%\" align=\"center\"><u>EMAIL</u></th>
				</tr>
		";

			$tmprankcode = "";
			
			while ($rowpersonallist = mysql_fetch_array($qrypersonallist))
			{
			
				$applicantno = $rowpersonallist["APPLICANTNO"];
				$choice1 = $rowpersonallist["CHOICE1"];
				$rankcode1 = $rowpersonallist["RANKCODE1"];
//				$choice2 = $rowpersonallist["CHOICE2"];
				$rankcode2 = $rowpersonallist["RANKCODE2"];
				$name = $rowpersonallist["NAME"];
				$address = $rowpersonallist["ADDRESS"];
				$address1 = $rowpersonallist["ADDRESS1"];
				$telno = $rowpersonallist["TELNO"];
				$email = $rowpersonallist["EMAIL"];
				$dateapplied = date('m/d/Y',strtotime($rowpersonallist["DATEAPPLIED"]));
		//		$recommendedby = $rowpersonallist["RECOMMENDEDBY"];
				
				$online = $rowpersonallist["ONLINE"];

				switch($online)
				{
				   case "1" : $styleonline = "style=\"background-color:Yellow;\""; break;
				   case "2" : $styleonline = "style=\"background-color:Lime;\""; break;
				   default : $styleonline = ""; break;
				}
		
				if ($tmprankcode != $rankcode1)
				{
					echo "
					<tr>
						<td style=\"font-size:1.2em;font-weight:Bold;color:Yellow;background-color:Gray;text-align:center;\" colspan=\"5\"><u>$choice1</u></td>
					</tr>
					";
				}
		
		echo "	<tr $mouseovereffect class=\"print\">
					<td valign=\"top\" $styleonline >$dateapplied</td>
					<td valign=\"top\" $styleonline ><a href=\"javascript:openWindow('crewdatasheet.php?applicantno=$applicantno', 'crewsheet$applicantno', 1000, 650);\" title=\"Click to View Data Sheet...\">$name</a></td>
					<td valign=\"top\" $styleonline >$address</td>
					<td valign=\"top\" $styleonline >$telno</td>
					<td valign=\"top\" $styleonline ><a href=\"mailto:$email\" title=\"Send Email Now!\">$email</a></td>
				</tr>
		";
				$tmprankcode = $rankcode1;
			}
		echo "
			</table>
		</div>
		
		
		
	</div>

	<input type=\"hidden\" name=\"actiontxt\">
</form>
	
<form name=\"repappdaily\" action=\"repappdaily.php\" target=\"repappdaily\" method=\"POST\">
	<input type=\"hidden\" name=\"transdatefrom\">
	<input type=\"hidden\" name=\"transdateto\">
	<input type=\"hidden\" name=\"rankcode\">
</form>

</body>
</html>
";

?>
