<?php
include('veritas/connectdb.php');
include("veritas/include/stylephp.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');
$basedirid = "idpics/";
// $basedirdocs = "docimages/";
$basedirdocs = "/home/veritas/docimages/";
$showhistory = "visibility:hidden;";

if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
else 
	$employeeid = "";
	
	
if (isset($_POST['schedidhidden']))
	$schedidhidden = $_POST['schedidhidden'];

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];

if (isset($_POST['applicantno']))
	$applicantno = $_POST['applicantno'];
	
//if (isset($_POST['currentrank']))
//	$currentrank = $_POST['currentrank'];
	
if (isset($_POST['rankcode']))
	$rankcode = $_POST['rankcode'];
else 
	$rankcode = "All";

if (isset($_POST['currentrankcode']))
	$currentrankcode = $_POST['currentrankcode'];
	
if (isset($_POST['currentrankcodehidden']))
	$currentrankcodehidden = $_POST['currentrankcodehidden'];
	
//if ($rankcode != $currentrankcode && $currentrankcode != "")
//	$rankcode = $currentrankcode;

if (isset($_POST["searchkey"]))
	$searchkey = $_POST["searchkey"];
	
if (isset($_POST["searchby"]))
	$searchby = $_POST["searchby"];
	
if ($searchkey == "")
	$disablesearch = "disabled=\"disabled\"";
else 
	$disablesearch = "";
	
if (isset($_POST['traincodedtl']))
	$traincodedtl = $_POST['traincodedtl'];
	
	
if (isset($_POST['traincodehidden']))
	$traincodehidden = $_POST['traincodehidden'];

if (isset($_POST['rankcodehidden']))
	$rankcodehidden = $_POST['rankcodehidden'];

if (isset($_POST['traindatehidden']))
	$traindatehidden = $_POST['traindatehidden'];

if (isset($_POST['gradehidden']))
	$gradehidden = $_POST['gradehidden'];

if (isset($_POST['coursetypecodehidden']))
	$coursetypecodehidden = $_POST['coursetypecodehidden'];
	
	
$disableranklist = "disabled=\"disabled\"";
	
	
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


	
switch ($actiontxt)
{
	case "find"	:
		
		$whereappno = "";
		$whereappno2 = "";
		$errormsg = "";
		
		switch ($searchby)
		{
			case "1"	: //APPLICANT NO
				
					$qrysearch = mysql_query("SELECT APPLICANTNO,CREWCODE FROM crew WHERE APPLICANTNO LIKE '$searchkey%' ORDER BY APPLICANTNO") or die(mysql_error());
				
				break;
			case "2"	: //CREW CODE
				
					$qrysearch = mysql_query("SELECT APPLICANTNO,CREWCODE FROM crew WHERE CREWCODE LIKE '$searchkey%' ORDER BY CREWCODE") or die(mysql_error());
				
				break;
			case "3"	: //FAMILY NAME
				
					$qrysearch = mysql_query("SELECT APPLICANTNO,CREWCODE FROM crew WHERE FNAME LIKE '$searchkey%' ORDER BY FNAME") or die(mysql_error());
				
				break;
			case "4"	: //GIVEN NAME
				
					$qrysearch = mysql_query("SELECT APPLICANTNO,CREWCODE FROM crew WHERE GNAME LIKE '$searchkey%' ORDER BY GNAME") or die(mysql_error());
				
				break;
		}
	
		if ($searchby != "")
		{
			if (mysql_num_rows($qrysearch) > 0) 
			{
				$cnt = mysql_num_rows($qrysearch);
				$applist = "IN (";
				
				for ($i=1;$i<$cnt;$i++)
				{
					$rowsearch = mysql_fetch_array($qrysearch);
					$applist .= $rowsearch["APPLICANTNO"] . ", ";
				}
				
				$rowsearch = mysql_fetch_array($qrysearch);
				$applist .= $rowsearch["APPLICANTNO"] . ")";
	
				$whereappno2 = "AND APPLICANTNO " . $applist;
				$whereappno = "AND c.APPLICANTNO " . $applist;
			}
			else 
			{
				$applicantno="";
				$errormsg = "Search Key -- '$searchkey' Not Found. ";
			}
		}
		else 
		{
			if ($applicantno != "")
				$whereappno2 = "AND APPLICANTNO =" . $applicantno;
			else 
				$whereappno2 = "";
		}
		
	break;
	
	case "viewhistory" :
		
			$showhistory = "visibility:show;";
		
	break;
	
	case "changerank" :
		
		if ($currentrankcode != $currentrankcodehidden)
		{
			$currentrankcode = $currentrankcodehidden;
//			$rankcode = "";
		}
		
	break;
	
	case "updatetraining":
		
			if ($traindatehidden != "")
				$traindatehidden = "'" . $traindatehidden . "'";
			else 
				$traindatehidden = "NULL";
				
			if ($gradehidden == "")
				$gradehidden = 0; 
					

					
			// crewtrainingnosched -- BOTH INHOUSE AND OUTSIDE, NO SCHED ID!!!
			
			$qrycrewtrainingnoschedinsert = mysql_query("INSERT INTO crewtrainingnosched(APPLICANTNO,TRAINCODE,TRAINDATE,RANKCODE,
													GRADE,MADEBY,MADEDATE)
													VALUES($applicantno,'$traincodehidden',$traindatehidden,'$rankcodehidden',
															$gradehidden,'$employeeid','$currentdate')
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
	
	case  ""	:
		
			$whererank = "";
		
		break;
	
	default		: //ANY RANKCODE
	
			$whererank = "AND r.RANKCODE='$rankcode'";
			
		break;
}

$qryranklist=mysql_query("SELECT RANK,RANKCODE FROM rank WHERE STATUS=1 ORDER BY RANKING") or die(mysql_error());
	
$qryrankcodelist=mysql_query("SELECT RANK,RANKCODE FROM rank WHERE STATUS=1 ORDER BY RANKING") or die(mysql_error());


//SELECT *,
//IF (DATEEMB > CURRENT_DATE,'EMBARKING',IF (ARRMNLDATE <= CURRENT_DATE,'STANDBY',IF (CURRENT_DATE BETWEEN DEPMNLDATE AND ARRMNLDATE,'ONBOARD',IF (DATEDISEMB < CURRENT_DATE,'NO ARRMNLDATE',NULL)))) AS STATUS
//FROM (
//SELECT cc.CCID,cc.RANKCODE,cc.VESSELCODE,cc.DATEEMB,IF (cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,
//cc.DEPMNLDATE,cc.ARRMNLDATE,IF (cpr.CCID IS NOT NULL,'1','0') AS PROMOTED
//FROM crewchange cc
//LEFT JOIN crewpromotionrelation cpr ON cpr.CCIDPROMOTE=cc.CCID
//WHERE cc.APPLICANTNO=101720
//) x
//ORDER BY DATEDISEMB DESC


	$qrycrew = mysql_query("SELECT GROUP_CONCAT(CCID,',',IF(RANKCODE IS NULL,'',RANKCODE),',',IF(VESSELCODE IS NULL,'',VESSELCODE),',',
							IF(DATEEMB IS NULL,'',DATEEMB),',',IF (DATEDISEMB IS NULL,'',DATEDISEMB),',',IF (DEPMNLDATE IS NULL,'',DEPMNLDATE),',',
							IF (ARRMNLDATE IS NULL,'',ARRMNLDATE)
							ORDER BY DATEDISEMB DESC
							SEPARATOR '|')
							FROM crewchange cc
							WHERE APPLICANTNO=$applicantno
							GROUP BY APPLICANTNO
						");



//	$qrycrew = mysql_query("SELECT * FROM (
//										SELECT c.APPLICANTNO,CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,CREWCODE,
//										r.RANKCODE,r.RANK,r.ALIAS1,r.RANKLEVELCODE,r.RANKTYPECODE,DATEDISEMB
//										FROM crew c
//										LEFT JOIN (SELECT APPLICANTNO,RANKCODE,IF (DATECHANGEDISEMB IS NULL, DATEDISEMB,DATECHANGEDISEMB) AS DATEDISEMB,
//													cc.VESSELCODE,v.VESSEL
//													FROM crewchange cc
//													LEFT JOIN vessel v ON v.VESSELCODE=cc.VESSELCODE
//													WHERE DATEDISEMB <= CURRENT_DATE
//													$whereappno2
//
//							                        UNION
//							                        
//							                       SELECT APPLICANTNO,RANKCODE,DATEDISEMB,NULL,VESSEL
//							                        FROM crewexperience 
//							                        WHERE DATEDISEMB <= CURRENT_DATE
//							                        $whereappno2
//													) x ON x.APPLICANTNO=c.APPLICANTNO
//										LEFT JOIN rank r ON r.RANKCODE=x.RANKCODE
//										LEFT JOIN ranklevel rl ON rl.RANKLEVELCODE=r.RANKLEVELCODE
//										LEFT JOIN ranktype rt ON rt.RANKTYPECODE=r.RANKTYPECODE
//										WHERE r.RANK IS NOT NULL 
//										$whererank
//
//										ORDER BY NAME,DATEDISEMB DESC
//									) z
//									GROUP BY APPLICANTNO
//									ORDER BY RANKCODE,NAME
//								") or die(mysql_error());

if ($applicantno != "")
{
	$disableranklist = "";
	
	$qrygetname = mysql_query("SELECT CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME
								FROM crew c
								WHERE APPLICANTNO=$applicantno
								") or die(mysql_error());
	
	$rowgetname = mysql_fetch_array($qrygetname);
	$applicantname = $rowgetname["NAME"];
	
}

echo "
<html>\n
<head>\n
<title>Training Checklist and Enrollment</title>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>

<body onload=\"\" style=\"overflow:hidden;background-color:White;\">\n

<form name=\"formchecklist\" method=\"POST\">\n

<span class=\"wintitle\">TRAINING CHECKLIST</span>

	<div style=\"width:100%;\">
	
		<div style=\"width:100%;height:150px;background-color:White;\">

			<div style=\"width:50%;height:150px;float:left;background-color:White;overflow:auto;\">
				<table class=\"listcol\" style=\"width:100%;font-size:0.7em;float:left;\">
					<tr>
						<th width=\"10%\">APPNO</th>
						<th width=\"10%\">CREWCODE</th>
						<th width=\"20%\">NAME</th>
					</tr>
				";
				
				$tmprank = "";
		
				while ($rowcrew=mysql_fetch_array($qrycrew))
				{
					$applicantno1 = $rowcrew["APPLICANTNO"];
					$name = $rowcrew["NAME"];
					$crewcode = $rowcrew["CREWCODE"];
					$rankcode1 = $rowcrew["RANKCODE"];
					$rank1 = $rowcrew["RANK"];
					
					if ($tmprank != $rank1)
					{
						echo "
						<tr>
							<td colspan=\"3\" align=\"left\" style=\"font-size:1em;font-weight:bold;color:Yellow;background-color:Black;\"><i>$rank1</i></td>
						</tr>
						";
					}
					
					echo "
					<tr $mouseovereffect ondblclick=\"actiontxt.value='find';applicantno.value='$applicantno1';currentrankcode.value='$rankcode1';
									formchecklist.submit();\">
						<td style=\"font-size:0.9em;cursor:pointer;\">$applicantno1</td>
						<td style=\"font-size:0.9em;cursor:pointer;\">$crewcode</td>
						<td style=\"font-size:0.9em;cursor:pointer;\">$name</td>
					</tr>
					";
					
					$tmprank = $rank1;
				}
		
		echo "
				</table>
			</div>
			
			<div style=\"width:50%;height:150px;float:right;background-color:#CCCC66;\">
			
				<center>
				<table style=\"width:100%;margin-top:5px;float:left;\" border=1>
					<tr>
						<td align=\"left\" style=\"font-size:0.8em;font-weight:Bold;\">Search</td>
						<td>
							<select name=\"searchby\" onchange=\"if(this.value != ''){searchkey.disabled=false;searchkey.value='';rankcode.value='All';searchkey.focus();}
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
								
							<input type=\"button\" name=\"btnfind\" value=\"Find\" $disablesearch onclick=\"currentrankcode.value='';actiontxt.value='find';submit();\" />
						</td>
					</tr>
					<tr>
						<td align=\"left\" style=\"font-size:0.8em;font-weight:Bold;\">Sort By</td>
						<td>
							<select name=\"rankcode\" onchange=\"applicantno.value='';currentrankcode.value='';searchkey.value='';searchby.value='';
											actiontxt.value='find';submit();\">
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
						</td>
						<td align=\"center\">
							<input type=\"button\" value=\"Refresh List\" style=\"border:0;background-color:Green;
											font-size:1em;font-weight:Bold;color:Yellow;border:thin solid white;cursor:pointer;\"
										 onclick=\"currentrankcode.value='';actiontxt.value='find';submit();\" />
						</td>
					</tr>
					<tr>
						<td align=\"left\" style=\"font-size:0.8em;font-weight:Bold;\">Error</td>
						<td colspan=\"2\" style=\"background-color:Black;color:Yellow;font-size:0.8em;font-weight:Bold;\">$errormsg</td>
					</tr>
				</table>
				</center>
			</div>
			
		</div>

		<div style=\"width:100%;height:425px;\">
";

echo "			
			<table style=\"width:100%;background-color:#333333;\" class=\"listrow\">
				<tr>
					<th style=\"color:White;\">Applicant No:&nbsp;&nbsp;<span style=\"font-size:1.2em;font-weight:Bold;color:Lime;\">$applicantno</span></th>
					<th style=\"color:White;\">Name:&nbsp;&nbsp;<span style=\"font-size:1.2em;font-weight:Bold;color:Lime;\">$applicantname</span></th>
					<th style=\"color:White;\">Rank:&nbsp;&nbsp;
					<select name=\"currentrankcodehidden\" $disableranklist onchange=\"applicantno.value='$applicantno';
						actiontxt.value='changerank';submit();\">
						<option value=\"\">--Select Rank--</option>
					";

					//CHECK applicantstatus
					


					while($rowrankcodelist=mysql_fetch_array($qryrankcodelist))
					{
						$rank2=$rowrankcodelist['RANK'];
						$rankcode2=$rowrankcodelist['RANKCODE'];
						
						$selectrank = "";
						if ($rankcode2 == $currentrankcode)
							$selectrank = "SELECTED";
							
						echo "<option $selectrank value=\"$rankcode2\">$rank2</option>";
					}
					
					echo "
					</th>
				</tr>
			</table>
";
			
include("veritas/include/checklist.inc");
							
			if (mysql_num_rows($qrychecklist) > 0)
			{
				
				echo "
				<div style=\"width:100%;height:100%;overflow:auto;\">
					
					<table width=\"100%\" style=\"font-size:0.7em;background-color:#F0F1E9;border:1px solid black;\">
						<tr style=\"font-weight:bold;background-color:Black;color:White;\">
							<td width=\"20%\" align=\"center\">COURSE</td>
							<td width=\"5%\" align=\"center\">REQ</td>
							<td width=\"10%\" align=\"center\">TRAINING DATE</td>
							<td width=\"30%\" align=\"center\">STATUS</td>
							<td width=\"15%\" align=\"center\">LOCATION</td>
							<td width=\"10%\" align=\"center\">CERTIFICATE</td>
							<td width=\"10%\" align=\"center\">ACTION</td>
						</tr>
						";
				
//						if ($applicantno != "")
//						{
							$tmpgroup = "";

							while ($rowchecklist = mysql_fetch_array($qrychecklist))
							{
								$chkidno = $rowchecklist["IDNO"];
								$chktraincode = $rowchecklist["TRAINCODE"];
								$chktraining = $rowchecklist["TRAINING"];
								$chkalias = $rowchecklist["ALIAS"];
								$chkrankcode = $rowchecklist["RANKCODE"];
								$chkrequired = $rowchecklist["REQUIRED"];
								
								if ($chkrequired == 0)
									$chkrequiredshow = "N";
								else 
									$chkrequiredshow = "Y";
									
								$chkcoursetypecode = $rowchecklist["COURSETYPECODE"];
								$chkcoursetype = $rowchecklist["COURSETYPE"];
								$chkdoccode = $rowchecklist["DOCCODE"];
								$chkstatus = $rowchecklist["STATUS"];
								
								if ($chkcoursetypecode != $tmpgroup)
								{
									$style = "style=\"background-color:Navy;color:Orange;font-weight:Bold;font-size:1.2em;text-align:center;\"";
									
									echo "<tr><td colspan=\"7\" $style>$chkcoursetype</td></tr>";
									
								}
								
								$disabledenroll = 0;
								
								$status = "";
								$statusshow = "";
								$datefrom = "";
								$dateto = "";
								$certshow = "";
								$docshow = "";

								$wherepart = "";
								
								if ($chkcoursetypecode == "INHSE")
									$wherepart = "WHERE TYPE NOT IN ('OUT')";
								else 
									$wherepart = "WHERE TYPE NOT IN ('IN')";
									
								
								$qrychecklistinhouse = mysql_query("
												SELECT *,															
												IF (DATEFROM > CURRENT_DATE,'ENROLLED',
												    IF (DATETO < CURRENT_DATE,
												        'FINISHED',
												        'ON-GOING'
												        )
												) AS STATUS2
												FROM (
													SELECT 'IN' AS TYPE,ct.SCHEDID,th.DATEFROM,th.DATETO,th.STATUS AS TRAINSTATUS,ct.STATUS
													FROM crewtraining ct
													LEFT JOIN trainingschedhdr th ON th.SCHEDID=ct.SCHEDID
													WHERE ct.APPLICANTNO=$applicantno AND th.TRAINCODE='$chktraincode'
												
													UNION
												
													SELECT 'OUT' AS TYPE,cto.SCHEDID,th.DATEFROM,th.DATETO,th.STATUS AS TRAINSTATUS,cto.STATUS
													FROM crewtrainingothers cto
													LEFT JOIN trainingschedhdr th ON th.SCHEDID=cto.SCHEDID
													WHERE cto.APPLICANTNO=$applicantno AND th.TRAINCODE='$chktraincode'
												
													UNION
												
													SELECT 'OLD' AS TYPE,NULL,TRAINDATE,TRAINDATE,NULL,1
													FROM crewtrainingold ctold
													WHERE ctold.APPLICANTNO=$applicantno AND ctold.TRAINCODE='$chktraincode'
													
													UNION
													
													SELECT 'UPD' AS TYPE,NULL,TRAINDATE,TRAINDATE,NULL,1
													FROM crewtrainingnosched ctnosched
													WHERE ctnosched.APPLICANTNO=$applicantno AND ctnosched.TRAINCODE='$chktraincode'
												) x
												$wherepart
												ORDER BY DATEFROM DESC
											") or die(mysql_error());
							
								$historycnt = mysql_num_rows($qrychecklistinhouse);
								
								if (mysql_num_rows($qrychecklistinhouse) > 0)
								{
									$rowchecklistinhouse = mysql_fetch_array($qrychecklistinhouse);
									
									$schedid = $rowchecklistinhouse["SCHEDID"];
									$datefrom = date("dMY",strtotime($rowchecklistinhouse["DATEFROM"]));
									$dateto = date("dMY",strtotime($rowchecklistinhouse["DATETO"]));
									$trainstatus = $rowchecklistinhouse["TRAINSTATUS"]; //IF TRAINING IS CANCELLED
									$status = $rowchecklistinhouse["STATUS"]; //COMPLETED;INCOMPLETE
									$status2 = $rowchecklistinhouse["STATUS2"]; //ENROLLED;FINISHED;ON-GOING
									$type = $rowchecklistinhouse["TYPE"];
									
									if ($status == 1)
										$completed = "COMPLETED";
									elseif ($status == 2)
										$completed = "INCOMPLETE";
									else 
										$completed = "NOT POSTED YET";
									
									$stylestatus = "";
										
									switch ($status2)
									{
										case "ENROLLED"	:
											
												$statusshow = "<span style=\"color:Yellow;background-color:Black;font-weight:Bold;\">" . "&nbsp;ENROLLED " . "</span><i>&nbsp;&nbsp;&nbsp;" . $datefrom . " to " . $dateto . "</i>";
												$disabledenroll = 2;
												
											break;
										case "FINISHED"	:
											
												$statusshow = "<span style=\"color:Red;background-color:Black;font-weight:Bold;\">" . "&nbsp;FINISHED " . "</span><i>&nbsp;&nbsp; Last&nbsp;"  .$dateto . "</i>&nbsp;<span style=\"color:White;background-color:Red;font-weight:Bold;\">&nbsp;$completed&nbsp;</span>";
												
											
											break;
										case "ON-GOING"	:
											
												$statusshow = "<span style=\"color:Lime;background-color:Black;font-weight:Bold;\">" . "&nbsp;ON-GOING " . "</span><i>&nbsp;&nbsp; Until&nbsp;" . $dateto . "</i>";
												$disabledenroll = 2;
												
											break;
									}
								}

								
								//CHECK IF DOCUMENT IS READY FOR VIEWING...
								
								$dirfilename1 = $basedirdocs . $applicantno . "/C/" . $chkdoccode . ".pdf";
								
								if (checkpath($dirfilename1))
								{
									$docshow = "<a href=\"#\" style=\"font-weight:Bold;color:Navy;\"
												onclick=\"openWindow('$dirfilename1', '$chkdoccode' ,700, 500);\" title=\"Click to View.\">
												[VIEW]</a>";
								}
								else 
									$docshow = "------";

									
								$styledtl = " style=\"font-size:1em;color:Black;border-bottom:1px dashed gray;\"";
//								$styledtl = " style=\"border:1px solid gray;font-weight:Bold;\"";
								
								//CHECK IF THERE'S AN ENTRY IN CREWCERTSTATUS
								
//								$qrycheckcertstatus = mysql_query("SELECT * FROM crewcertstatus 
//														WHERE APPLICANTNO=$applicantno AND DOCCODE='$chkdoccode'
//														ORDER BY DATEISSUED DESC
//													") or die(mysql_error());

								$qrycheckcertstatus = mysql_query("SELECT if(x.APPLICANTNO IS NULL,'UPDATE','EXISTS') AS STATUS,cc.*
													FROM crewcertstatus cc
													LEFT JOIN (
														SELECT 'IN' AS TYPE,ct.APPLICANTNO,ct.SCHEDID,th.DATEFROM,th.DATETO,th.STATUS AS TRAINSTATUS,ct.STATUS
														FROM crewtraining ct
														LEFT JOIN trainingschedhdr th ON th.SCHEDID=ct.SCHEDID
														WHERE ct.APPLICANTNO=$applicantno AND th.TRAINCODE='$chktraincode'
														
														UNION
														
														SELECT 'OUT' AS TYPE,cto.APPLICANTNO,cto.SCHEDID,th.DATEFROM,th.DATETO,th.STATUS AS TRAINSTATUS,cto.STATUS
														FROM crewtrainingothers cto
														LEFT JOIN trainingschedhdr th ON th.SCHEDID=cto.SCHEDID
														WHERE cto.APPLICANTNO=$applicantno AND th.TRAINCODE='$chktraincode'
														
														UNION
														
														SELECT 'OLD' AS TYPE,ctold.APPLICANTNO,NULL,TRAINDATE,TRAINDATE,NULL,NULL
														FROM crewtrainingold ctold
														WHERE ctold.APPLICANTNO=$applicantno AND ctold.TRAINCODE='$chktraincode'
														
														UNION
														
														SELECT 'UPD' AS TYPE,ctnosched.APPLICANTNO,NULL,TRAINDATE,TRAINDATE,NULL,NULL
														FROM crewtrainingnosched ctnosched
														WHERE ctnosched.APPLICANTNO=$applicantno AND ctnosched.TRAINCODE='$chktraincode'
													) x ON x.APPLICANTNO = cc.APPLICANTNO
													
													WHERE cc.APPLICANTNO=$applicantno AND cc.DOCCODE='$chkdoccode'
													ORDER BY DATEISSUED DESC
													LIMIT 1
													") or die(mysql_error());
								
								if (mysql_num_rows($qrycheckcertstatus) > 0)
								{
									$rowcheckcertstatus = mysql_fetch_array($qrycheckcertstatus);
									$certrankcode = $rowcheckcertstatus["RANKCODE"];
									
									if ($rowcheckcertstatus["DATEISSUED"] != "")
										$certdateissued = date("Y-m-d",strtotime($rowcheckcertstatus["DATEISSUED"]));
										
									$certgrade = $rowcheckcertstatus["GRADE"];
									$certstatus = $rowcheckcertstatus["STATUS"];
									
//										$statusshow = "ENTRY FOUND: ";

									if ($certstatus == "UPDATE" )
									{
										$certshow = "<a href=\"#\" style=\"font-weight:Bold;color:Green;\"
														onclick=\"
														applicantno.value = '$applicantno';
														traincodehidden.value = '$chktraincode';
														rankcodehidden.value = '$certrankcode';
														traindatehidden.value = '$certdateissued';
														gradehidden.value = '$certgrade';
														coursetypecodehidden.value = '$chkcoursetypecode';
														
														actiontxt.value='updatetraining';submit();\" 
														title=\"Certificate Found! Click to update training history.\">
														[UPDATE TRAINING]</a>";
									}
									else 
									{
										$certshow = "";
									}
								}
								
								//LISTING - START
								
								switch ($disabledenroll)
								{
									case "0"	:
										
											$onclick = "<a href=\"#\" onclick=\"openWindow('enrollment.php?traincode=$chktraincode&applicantno=$applicantno&type=0&getrankcode=$currentrankcode','enrollment' ,800, 565);\" 
												style=\"font-size:1em;font-weight:bold;color:#FF6600;\">[ENROLL]</a>";
										
										break;
										
									case "1"	:
										
//											$onclick = "<a href=\"#\" onclick=\"openWindow('enrollment.php?traincode=$chktraincode&applicantno=$applicantno&type=0','enrollment' ,800, 565);\" 
//												style=\"font-size:1em;font-weight:bold;color:#FF6600;\">[ENROLL]</a>";
										
										break;
										
									case "2"	:
										
											$onclick = "-------";
										
										break;
								}
								
								echo "
								<tr style=\"border:thin solid black;\">
									<td $styledtl>$chktraining</td>
									<td $styledtl align=\"left\">$chkrequiredshow</td>
										<td $styledtl align=\"center\">
										";
											if (!empty($datefrom))
											{
												echo "<span style=\"font-weight:Bold;color:Black;\">$datefrom</span>&nbsp;&nbsp;";
												if ($historycnt > 1)
												{
//													echo "<a href=\"#\" onclick=\"applicantno.value='$applicantno';
//														traincodedtl.value='$chktraincode';actiontxt.value='viewhistory';submit();\" 
//														style=\"font-weight:Bold;color:Green;\" title=\"Click to view history...\">[$historycnt]</a>";
													echo "<a href=\"#\" onclick=\"openWindow('traininghistory.php?traincode=$chktraincode&applicantno=$applicantno','traininghistory' ,800, 565);\" 
														style=\"font-weight:Bold;color:Green;\" title=\"Click to view history...\">[$historycnt]</a>";
												}
											}
											else 
											{
												echo "N/A";
											}
										echo "	
										</td>
										<td $styledtl align=\"left\">&nbsp;$statusshow&nbsp;$certshow</td>
										<td $styledtl align=\"center\">&nbsp;$location</td>
										<td $styledtl align=\"center\" title=\"$chkdoccode\">&nbsp;$docshow</td>
										<td $styledtl align=\"center\">$onclick</td>
									</tr>
									";
							
								$tmpgroup = $chkcoursetypecode;
							}
//						}
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

	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"applicantno\" />
	<input type=\"hidden\" name=\"currentrankcode\" value=\"$currentrankcode\" />
	<input type=\"hidden\" name=\"traincodedtl\" />
	
	<input type=\"hidden\" name=\"traincodehidden\" />
	<input type=\"hidden\" name=\"rankcodehidden\" />
	<input type=\"hidden\" name=\"traindatehidden\" />
	<input type=\"hidden\" name=\"gradehidden\" />
	<input type=\"hidden\" name=\"coursetypecodehidden\" />
</form>

</body>

</html>

";

?>