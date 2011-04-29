<?php

include("veritas/connectdb.php");
include("veritas/include/stylephp.inc");
include("veritas/include/functions.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');

$basedir = "docimg/";
// $basedir = "docimages/";

if(isset($_SESSION['employeeid']))
	$employeeid=$_SESSION['employeeid'];

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
else 
	$actiontxt = "";

if (isset($_POST['applicantno']))
	$applicantno = $_POST['applicantno'];

if (isset($_POST['rankcode']))
	$rankcode = $_POST['rankcode'];
else 
	$rankcode = "All";
	
if (isset($_POST['doctype']))
	$doctype = $_POST['doctype'];
else 
	$doctype = "D";
	
if (isset($_POST['docno']))
	$docno = $_POST['docno'];
	
if (isset($_POST['documentcode']))
	$documentcode = $_POST['documentcode'];
	
if (isset($_POST['issued']))
	$issued = $_POST['issued'];
	
if (isset($_POST['expiry']))
	$expiry = $_POST['expiry'];
	
if (isset($_POST['editdel']))
	$editdel = $_POST['editdel'];
	
if (isset($_POST['doctype2']))
	$doctype2 = $_POST['doctype2'];
	
if (isset($_POST['editdoccode']))
	$editdoccode = $_POST['editdoccode'];
	
if (isset($_POST['licrankcode']))
	$licrankcode = $_POST['licrankcode'];
	
//if (isset($_POST['editrankcode']))
//	$editrankcode = $_POST['editrankcode'];
	
	
$disabled = "disabled=\"disabled\"";
$disabledoctype = "";
$expirydisable = "";

$chkdoc = "";
$chklic = "";
$chkcert = "";

switch ($doctype)
{
	case "D"	:	$chkdoc = "checked=\"checked\""; break;
	case "L"	:	$chklic = "checked=\"checked\""; break;
	case "C"	:	$chkcert = "checked=\"checked\""; $expirydisable = "disabled=\"disabled\""; break;
}
	


switch ($actiontxt)
{
	case "addlist"	:
		
			$issuedraw = "'" . date("Y-m-d",strtotime($issued)) . "'";

			if (!empty($expiry))
				$expiryraw = "'" . date("Y-m-d",strtotime($expiry)) . "'";
			else
				$expiryraw = "NULL";
				
			if (!empty($licrankcode))
				$licrankcode = "'" . $licrankcode . "'";
			else 
				$licrankcode = "NULL";
			
			switch ($doctype)
			{
				case ($doctype == "D" || $doctype == "L")	:
					
						$qryverify = mysql_query("SELECT APPLICANTNO FROM crewdocstatus 
												WHERE APPLICANTNO=$applicantno AND DOCCODE='$documentcode'") or die(mysql_error());
						
						if (mysql_num_rows($qryverify) == 0)
						{
					
							$qrycrewdocsinsert = mysql_query("INSERT INTO crewdocstatus(APPLICANTNO,RANKCODE,DOCCODE,DATEISSUED,DATEEXPIRED,
														DOCNO,MADEBY,MADEDATE) VALUES($applicantno,$licrankcode,'$documentcode',$issuedraw,
														$expiryraw,'$docno','$employeeid','$currentdate')") or die(mysql_error());
						}
						else 
						{
							$qrycrewdocsupdate = mysql_query("UPDATE crewdocstatus SET DOCNO='$docno',
																					RANKCODE=$licrankcode,
																					DATEISSUED=$issuedraw,
																					DATEEXPIRED=$expiryraw,
																					MADEBY='$employeeid',
																					MADEDATE='$currentdate'
																WHERE APPLICANTNO=$applicantno AND DOCCODE='$documentcode'
															") or die(mysql_error());
						}
					break;
				case "C"	:
					
						$qryverify = mysql_query("SELECT APPLICANTNO FROM crewcertstatus 
												WHERE APPLICANTNO=$applicantno AND DOCCODE='$documentcode'") or die(mysql_error());
						
						if (mysql_num_rows($qryverify) == 0)
						{
					
							$qrycrewcertinsert = mysql_query("INSERT INTO crewcertstatus(APPLICANTNO,DOCCODE,DATEISSUED,
														DOCNO,MADEBY,MADEDATE) VALUES($applicantno,'$documentcode',$issuedraw,
														'$docno','$employeeid','$currentdate')") or die(mysql_error());
						}
						else 
						{
							$qrycrewcertupdate = mysql_query("UPDATE crewcertstatus SET DOCNO='$docno',
																					DATEISSUED=$issuedraw,
																					MADEBY='$employeeid',
																					MADEDATE='$currentdate'
																WHERE APPLICANTNO=$applicantno AND DOCCODE='$documentcode'
															") or die(mysql_error());
						}
					break;
				
			}
			
			$documentcode = "";
			$licrankcode = "";
			$docno = "";
			$issued = "";
			$expiry = "";
			
			$disabledoctype = "";
			$expirydisable = "";
			
		break;
		
	case "delete"	:
		
			switch ($doctype2)
			{
				case ($doctype2 == "L" || $doctype2 == "D"):
					
						$qryeditdocdelete = mysql_query("DELETE FROM crewdocstatus
														WHERE DOCCODE='$editdel' 
														AND APPLICANTNO=$applicantno
												") or die(mysql_error());
						
					break;
				case "C"	:
					
						$qryeditcertdelete = mysql_query("DELETE FROM crewcertstatus 
														WHERE DOCCODE='$editdel' 
														AND APPLICANTNO=$applicantno
												") or die(mysql_error());
					break;
			}
		
		break;
		
	case "editsave"	:
		
			$issuedraw = "'" . date("Y-m-d",strtotime($issued)) . "'";
			
			if (!empty($expiry))
				$expiryraw = "'" . date("Y-m-d",strtotime($expiry)) . "'";
			else 
				$expiryraw = "NULL";
			
			if (!empty($licrankcode))
				$licrankcode = "'" . $licrankcode . "'";
			else 
				$licrankcode = "NULL";

			switch ($doctype2)
			{
				case ($doctype2 == "L" || $doctype2 == "D"):
					
						$qryeditdocsave = mysql_query("UPDATE crewdocstatus 
															SET DOCNO='$docno',
															RANKCODE=$licrankcode,
															DATEISSUED=$issuedraw,
															DATEEXPIRED=$expiryraw,
															MADEBY='$employeeid',
															MADEDATE='$currentdate'
														WHERE DOCCODE='$editdoccode' 
														AND APPLICANTNO=$applicantno
												") or die(mysql_error());
						
					break;
				case "C"	:
					
						$qryeditcertsave = mysql_query("UPDATE crewcertstatus 
															SET DOCNO='$docno',
															DATEISSUED=$issuedraw,
															MADEBY='$employeeid',
															MADEDATE='$currentdate'
														WHERE DOCCODE='$editdoccode' 
														AND APPLICANTNO=$applicantno
												") or die(mysql_error());
					break;
			}
			
			$documentcode = "";
			$docno = "";
			$issued = "";
			$expiry = "";
			
			$disabledoctype = "";
			$expirydisable = "";
		
		break;
		
	case "edit"		:
		
			switch ($doctype2)
			{
				case ($doctype2 == "D" || $doctype2 == "L")	:
					
						$qrygetdocdata = mysql_query("SELECT cds.DOCCODE,cds.DOCNO,cds.DATEISSUED,cds.DATEEXPIRED,cd.HASEXPIRY
										FROM crewdocstatus cds
										LEFT JOIN crewdocuments cd ON cd.DOCCODE=cds.DOCCODE
										WHERE APPLICANTNO=$applicantno AND cds.DOCCODE='$editdel'") or die(mysql_error());
					
						$rowgetdocdata = mysql_fetch_array($qrygetdocdata);
						
						$documentcode = $rowgetdocdata["DOCCODE"];
						$docno = $rowgetdocdata["DOCNO"];
						$issued = date("m/d/Y",strtotime($rowgetdocdata["DATEISSUED"]));
						if (!empty($rowgetdocdata["DATEEXPIRED"]))
							$expiry = date("m/d/Y",strtotime($rowgetdocdata["DATEEXPIRED"]));
						else 
							$expiry = "";
						$dochasexpiry = $rowgetdocdata["HASEXPIRY"];
					break;
					
				case "C"	:
					
						$expirydisable = "disabled=\"disabled\"";
						
						$qrygetdocdata = mysql_query("SELECT * FROM crewcertstatus 
										WHERE APPLICANTNO=$applicantno AND DOCCODE='$editdel'") or die(mysql_error());
					
						$rowgetdocdata = mysql_fetch_array($qrygetdocdata);
						
						$documentcode = $rowgetdocdata["DOCCODE"];
						$docno = $rowgetdocdata["DOCNO"];
						$issued = date("m/d/Y",strtotime($rowgetdocdata["DATEISSUED"]));
						
					break;
			}
			
			$disabledoctype = "disabled=\"disabled\"";
			
		break;
		
	case "cancel"	:
		
			$documentcode = "";
			$docno = "";
			$issued = "";
			$expiry = "";
		
		break;
	case "done"		:
		
			$qryapplicantupdate = mysql_query("UPDATE applicantstatus SET ENCODEDBY='$employeeid',
																		ENCODEDDATE='$currentdate'
													WHERE APPLICANTNO=$applicantno") or die(mysql_error());
			
			$doctype = "";
			$documentcode = "";
			$docno = "";
			$issued = "";
			$expiry = "";
			
			$applicantno = "";
		
		break;
	case "reset"		:
		
			$doctype = "";
			$documentcode = "";
			$docno = "";
			$issued = "";
			$expiry = "";
			
			$applicantno = "";
		
		break;
		
	case "create"		:
			
			$disabledoctype = "disabled=\"disabled\"";
		
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
$qryranklist2=mysql_query("SELECT RANK,RANKCODE FROM rank WHERE STATUS=1 ORDER BY RANKING") or die(mysql_error());

$qryapplicants = mysql_query("SELECT ap.APPLICANTNO,CONCAT(c.FNAME,', ',GNAME,' ',MNAME) AS NAME,
						r.RANKCODE,r.RANK,r.ALIAS1,r.RANKLEVELCODE,r.RANKTYPECODE
						FROM applicantstatus ap
						LEFT JOIN crew c ON c.APPLICANTNO=ap.APPLICANTNO
						LEFT JOIN rank r ON r.RANKCODE=ap.VMCRANKCODE
						LEFT JOIN ranklevel rl ON rl.RANKLEVELCODE=r.RANKLEVELCODE
						LEFT JOIN ranktype rt ON rt.RANKTYPECODE=r.RANKTYPECODE
						LEFT JOIN crewscholar cs ON cs.APPLICANTNO=ap.APPLICANTNO
						WHERE ap.ENDORSEDDATE IS NOT NULL AND (ap.ENCODEDDATE IS NULL AND cs.APPLICANTNO IS NULL) AND ap.STATUS=1
						$whererank
						ORDER BY r.RANKLEVELCODE,r.RANKTYPECODE,r.RANK,NAME") or die(mysql_error());
	

if ($applicantno != "")
{
	
	$qrygetcrewinfo = mysql_query("SELECT CONCAT(FNAME,', ',GNAME,' ' ,MNAME) AS NAME,r.RANK
								FROM applicantstatus ap
								LEFT JOIN crew c ON c.APPLICANTNO=ap.APPLICANTNO
								LEFT JOIN rank r ON r.RANKCODE=ap.VMCRANKCODE
								WHERE ap.APPLICANTNO=$applicantno AND ap.STATUS=1
								") or die(mysql_error());
	
	if (mysql_num_rows($qrygetcrewinfo) > 0)
	{
		$disabled = "";
		
		$rowgetcrewinfo = mysql_fetch_array($qrygetcrewinfo);
		
		$applicant = $rowgetcrewinfo["NAME"];
		$watchlistdate = date('m/d/Y',strtotime($currentdate));
		$apprank = $rowgetcrewinfo["RANK"];
		
		if ($actiontxt != "edit")
		{
			$qrydocuments = mysql_query("SELECT * FROM
				(
				SELECT cd1.DOCCODE,cd1.DOCUMENT,cd1.TYPE,cd1.NEEDRANK,cds.DOCCODE AS DOCUMENTCODE,cd1.HASEXPIRY
											FROM crewdocuments cd1
											LEFT JOIN crewdocstatus cds ON cds.DOCCODE=cd1.DOCCODE AND cds.APPLICANTNO=$applicantno
				              				WHERE cd1.TYPE IN ('D','L') AND cd1.STATUS=1
				              				AND cds.DOCCODE IS NULL
				UNION
				SELECT cd2.DOCCODE,cd2.DOCUMENT,cd2.TYPE,cd2.NEEDRANK,ccs.DOCCODE AS DOCUMENTCODE,cd2.HASEXPIRY
											FROM crewdocuments cd2
											LEFT JOIN crewcertstatus ccs ON ccs.DOCCODE=cd2.DOCCODE AND ccs.APPLICANTNO=$applicantno
											WHERE cd2.TYPE IN ('C') AND cd2.STATUS=1
											AND ccs.DOCCODE IS NULL
				) x
				WHERE x.TYPE = '$doctype'
				ORDER BY x.DOCUMENT") or die(mysql_error());
			
				$docselect = "<option selected value=\"\">--Select One--</option>";
				
					while($rowdocuments=mysql_fetch_array($qrydocuments))
					{
						$doccode = $rowdocuments["DOCCODE"];
						$docname = $rowdocuments["DOCUMENT"];
						$needrank1 = $rowdocuments["NEEDRANK"];
						$dochasexpiry1 = $rowdocuments["HASEXPIRY"];
						
						$selected1 = "";
						
						if ($doccode == $documentcode)
						{
								$selected1 = "SELECTED";
								$needrank = $needrank1;
								$dochasexpiry = $dochasexpiry1;
						}
							
						$docselect .= "<option $selected1 value=\"$doccode\">$docname</option>";
					}
			
		}
		else  //EDIT MODE
		{
			$qrygetdoccode = mysql_query("SELECT cd.DOCCODE,cd.DOCUMENT,cd.TYPE,cd.HASEXPIRY,
											IF(cds.RANKCODE IS NULL,a.LICRANKCODE,cds.RANKCODE) AS LICRANKCODE
											FROM crewdocuments cd
											LEFT JOIN crewdocstatus cds ON cds.DOCCODE=cd.DOCCODE AND cds.APPLICANTNO=$applicantno
											LEFT JOIN applicantdocs a ON a.DOCCODE=cd.DOCCODE AND a.APPLICANTNO=cds.APPLICANTNO
											WHERE cd.DOCCODE='$editdel'
										") or die(mysql_error());
			
			$rowgetdoccode = mysql_fetch_array($qrygetdoccode);
			
			$editdocument = $rowgetdoccode["DOCUMENT"];
			$editdocumentcode = $rowgetdoccode["DOCCODE"];
			
			if ($rowgetdoccode["LICRANKCODE"])
			{
				$licrankcode = $rowgetdoccode["LICRANKCODE"];
				$needrank = 1;
			}
			else 
			{
				$licrankcode = "";
				$needrank = 0;
			}
			
//			$edithasexpiry = $rowgetdoccode["HASEXPIRY"];
			
		}
		
		$qryapplicantdocs = mysql_query("SELECT ad.*,cd.DOCUMENT,cd.TYPE
										FROM applicantdocs ad
										LEFT JOIN crewdocuments cd ON cd.DOCCODE=ad.DOCCODE
										LEFT JOIN crewdocstatus cds ON cds.DOCCODE=ad.DOCCODE AND cds.APPLICANTNO=ad.APPLICANTNO
										WHERE ad.APPLICANTNO=$applicantno
										AND cds.APPLICANTNO IS NULL
										ORDER BY cd.TYPE,cd.DOCUMENT
										") or die(mysql_error());
		
		$qrycrewdocs = mysql_query("SELECT * FROM 
									(
										SELECT cds.APPLICANTNO,cds.DOCCODE,cds.RANKCODE,r.RANK,r.ALIAS1,cd.DOCUMENT,cd.TYPE,cds.DATEISSUED,cds.DATEEXPIRED,cds.DOCNO,cd.HASEXPIRY
										FROM crewdocstatus cds
										LEFT JOIN crewdocuments cd ON cd.DOCCODE=cds.DOCCODE
										LEFT JOIN rank r ON r.RANKCODE=cds.RANKCODE
										WHERE APPLICANTNO=$applicantno
			
										UNION
										
										SELECT ccs.APPLICANTNO,ccs.DOCCODE,ccs.RANKCODE,r.RANK,r.ALIAS1,cd.DOCUMENT,cd.TYPE,ccs.DATEISSUED,NULL,ccs.DOCNO,cd.HASEXPIRY
										FROM crewcertstatus ccs
										LEFT JOIN crewdocuments cd ON cd.DOCCODE=ccs.DOCCODE
										LEFT JOIN rank r ON r.RANKCODE=ccs.RANKCODE
										WHERE APPLICANTNO=$applicantno
									) x
									ORDER BY x.TYPE DESC,x.DOCUMENT") or die(mysql_error());
	}
	else 
	{
		$errormsg = "Applicant No. " . $applicantno . " does not exists/not endorsed yet/not a NEW Applicant. Please try again.";
	}

}

echo "
<html>\n
<head>\n
<title>
Document Encoding
</title>

<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<link rel=\"stylesheet\" type=\"text/css\" href=\"veripro_print.css\">
<script type='text/javascript' src='veripro.js'></script>
<script language=\"javascript\" src=\"popcalendar.js\"></script>	
</head>
<body onload=\"\" style=\"overflow:hidden;\">\n

<form name=\"docsencode\" method=\"POST\">\n

<span class=\"wintitle\">ENCODE DOCUMENTS</span>

	<div style=\"width:1019px;border-bottom:1px solid black;\">
	
		<div style=\"width:239px;height:650px;float:left;background-color:#999AFF\">
			
			<div style=\"width:239px;height:500px;float:left;overflow:auto;\">
			
				<span class=\"sectiontitle\">LIST OF APPLICANTS</span>
				<br />

				<table width=\"100%\" class=\"listcol\" style=\"font-size:0.8em;overflow:auto;\">
					<tr>
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
							<td align=\"left\" style=\"font-size:1em;font-weight:bold;color:Blue;\"><i>$rank</i></td>
						</tr>
						";
					}
					
					echo "
					<tr $mouseovereffect ondblclick=\"applicantno.value='$applicantno1';submit();\" style=\"cursor:pointer;\">
						<td style=\"font-size:0.9em;\">$name</td>
					
					</tr>
					";
					
					$tmprank = $rank;
				}
	
	echo "
				</table>
				
				
			</div>
			
			<div style=\"width:239px;height:150px;float:left;overflow:auto;background-color:#CCCCFF;\">
			
				<span class=\"sectiontitle\">SEARCH CRITERIA</span>
				
				<center>
				<table width=\"100%\" style=\"margin-top:5px;\">
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
		
		</div>
		
		<div style=\"width:780px;height:650px;float:left;background-color:Blue;\">

			<div style=\"width:780px;height:100px;background-color:Black;overflow:hidden;padding:5px;\" > 
		";
			$styledata = " style=\"font-size:1em;font-weight:bold;color:White;\"";	
		echo "
				<table width=\"100%\" style=\"font-size:0.7em;font-weight:bold;color:Orange;padding:2px;\" border=1>
					<tr>
						<td width=\"10%\">NAME</td>
						<td width=\"40%\" $styledata>&nbsp;$applicant</td>
						<td width=\"20%\">DATE</td>
						<td width=\"30%\" $styledata>&nbsp;$watchlistdate</td>
					</tr>
					<tr>
						<td>RANK</td>
						<td $styledata>&nbsp;$apprank</td>
						<td>Error Message:</td>
						<td rowspan=\"2\" align=\"center\" style=\"font-size:1em;font-weight:bold;color:White;\">&nbsp;$errormsg</td>
					</tr>
					<tr>
						<td colspan=\"2\" valign=\"middle\">Search Applicant No.&nbsp;&nbsp;&nbsp;
							<input type=\"text\" name=\"searchkey\" />
							<input type=\"button\" value=\"Search\" onclick=\"if(searchkey.value != '')
											{applicantno.value=searchkey.value;submit();}
											else {alert('Invalid Applicant No. Please try again.');}\" />
						</td>
						<td align=\"center\" style=\"font-size:1.8em;font-weight:bold;color:Red;\">&nbsp;$applicantno</td>
					</tr>
				</table>
			
			</div>
			
			<div style=\"width:780px;height:300px;background-color:White;overflow:auto;\" > 

				<div style=\"width:550px;height:300px;float:left;background-color:White;overflow:auto;padding:10px;\">
					
					<table width=\"100%\" border=1 style=\"font-weight:Bold;\">
						<tr>
							<td>Document Type</td>
							<td align=\"center\"><input type=\"radio\" name=\"doctype\" $chkdoc value=\"D\" $disabled $disabledoctype onclick=\"submit();\" />Document</td>
							<td align=\"center\"><input type=\"radio\" name=\"doctype\" $chklic value=\"L\" $disabled $disabledoctype onclick=\"submit();\" />License</td>
							<td align=\"center\"><input type=\"radio\" name=\"doctype\" $chkcert value=\"C\" $disabled $disabledoctype onclick=\"submit();\" />Certificate</td>
						</tr>
					</table>
					<br />
					
					<div style=\"border:1px solid black;width:100%;\">
						<br />
						<table class=\"listrow\" width=\"100%\">

							<tr>
								<th width=\"20%\">Document</th>
								<td width=\"65%\" align=\"left\">
						";
							if ($actiontxt != "edit")
							{
								echo "
									<select name=\"documentcode\" onchange=\"actiontxt.value='create';submit();\" $disabled>
										$docselect
									</select>
								";
							}
							else 
							{
								echo "
									<span style=\"font-size:1.1em;font-weight:Bold;color:Red;\">$editdocument</span>
								";
							}
							
							echo "
								</td>
							</tr>
							<tr>
								<th>Document No.</th>
								<td><input type=\"text\" name=\"docno\" $disabled value=\"$docno\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" onkeydown=\"checkCR()\" /></td>
							</tr>
							<tr>
								<th>Date Issued</th>
								<td>
									<input type=\"text\" name=\"issued\" $disabled value=\"$issued\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
									<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" $disabled onclick=\"popUpCalendar(issued, issued, 'mm/dd/yyyy', 0, 0);return false;\">
								</td>
							</tr>
							<tr>
								<th $expirydisable>Date Expiry</th>
								<td>
									<input type=\"text\" name=\"expiry\" $disabled $expirydisable value=\"$expiry\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
									<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" $disabled $expirydisable onclick=\"popUpCalendar(expiry, expiry, 'mm/dd/yyyy', 0, 0);return false;\">
								</td>
							</tr>
					";
							
					if ($needrank == 1)
					{
						echo "
							<tr>
								<th>Rank</th>
								<td>
									<select name=\"licrankcode\">
										<option value=\"\">--Select One--</option>
									";
										while($rowranklist2=mysql_fetch_array($qryranklist2))
										{
											$rank2=$rowranklist2['RANK'];
											$rankcode2=$rowranklist2['RANKCODE'];
											
											$selectrank2 = "";
											if ($licrankcode == $rankcode2)
												$selectrank2 = "SELECTED";
												
											echo "<option $selectrank2 value=\"$rankcode2\">$rank2</option>";
										}
							echo "
									</select>
								</td>
							</tr>
						";
					}
					
					if ($actiontxt != "edit")
					{
						echo "
							<tr>
								<td colspan=\"2\" align=\"center\">
									<input type=\"button\" value=\"Add to List...\" $disabled 
														onclick=\"if(docno.value != '' && documentcode.value != '' && (expiry.value != '' || '$dochasexpiry'=='0')) 
																	{actiontxt.value='addlist';submit();} 
																else {alert('Invalid Input Field. Please check.');}\" />
									<input type=\"button\" value=\"Cancel\" $disabled 
														onclick=\"actiontxt.value='cancel';submit();\" />
								</td>
							</tr>
						";
					}
					else //EDIT MODE
					{
						echo "
							<tr>
								<td colspan=\"2\" align=\"center\">
									<input type=\"button\" value=\"Save Changes\" $disabled 
														onclick=\"if(docno.value != ''  && (expiry.value != '' || '$dochasexpiry'=='0')) 
																	{editdoccode.value='$editdocumentcode';actiontxt.value='editsave';submit();} 
																	else {alert('Invalid Input Field. Please check.');}\" />
									<input type=\"button\" value=\"Cancel\" $disabled 
														onclick=\"actiontxt.value='cancel';submit();\" />
								</td>
							</tr>
						";
					}
					
					echo "
						</table>
					</div>
					<br />
					
					<input type=\"button\" value=\"Done\" $disabled $disabledoctype style=\"border:1px solid Black;background-color:Red;color:Yellow;font-weight:Bold;\"
								onclick=\"if(confirm('This is to confirm that ALL DOCUMENTS available are already encoded. Continue?')) {actiontxt.value='done';submit();}\" />
					<input type=\"button\" value=\"Reset\" $disabled $disabledoctype style=\"border:1px solid Black;background-color:Red;color:Yellow;font-weight:Bold;\"
								onclick=\"actiontxt.value='reset';submit();\" />
				
				</div>
				
				<div style=\"width:230px;height:300px;float:right;background-color:Gray;overflow:auto;padding:2px;\">
				
					<table class=\"listcol\" width=\"100%\">
						<tr>
							<th>DOCUMENT</th>
							<th>VALIDITY</th>
						</tr>
				";
					$tmptype = "";
					
					while ($rowapplicantdocs = mysql_fetch_array($qryapplicantdocs))
					{
						$list1 = $rowapplicantdocs["DOCUMENT"];
						$list2 = date('m/d/Y',strtotime($rowapplicantdocs["VALIDITY"]));
						$type = $rowapplicantdocs["TYPE"];
						
						if ($tmptype != $type)
						{
							switch ($type)
							{
								case "D": $typedesc = "Documents"; break;
								case "L": $typedesc = "License"; break;
								case "C": $typedesc = "Certificate"; break;
								
							}
							echo "<tr><td colspan=\"2\" style=\"font-weight:Bold;color:Blue;\"><i>$typedesc</i></td></tr>";
						}
						
						echo "
						<tr>
							<td>$list1</td>
							<td>$list2</td>
						</tr>
						";
						
						$tmptype = $type;
					}
					
				echo "	
					</table>
				</div>
			
			</div>
			
			<div style=\"width:780px;height:250px;background-color:#DCDCDC;overflow:auto;padding:10px;\" > 
			
				<span class=\"sectiontitle\">LIST OF DOCUMENTS</span>
			
					<table class=\"listcol\" width=\"100%\">
						<tr>
							<th>DOCUMENT</th>
							<th>DOCUMENT NO</th>
							<th>RANK</th>
							<th>ISSUED</th>
							<th>EXPIRY</th>
							<th>SCANNED DOCUMENT</th>
							<th>ACTION</th>
						</tr>
				";
					$tmptype = "";
					
					while ($rowcrewdocs = mysql_fetch_array($qrycrewdocs))
					{
						$list1 = $rowcrewdocs["DOCUMENT"];
						$list2 = date('m/d/Y',strtotime($rowcrewdocs["DATEISSUED"]));
						if (!empty($rowcrewdocs["DATEEXPIRED"]))
							$list3 = date('m/d/Y',strtotime($rowcrewdocs["DATEEXPIRED"]));
						else 
							$list3 = "---";
						$list4 = $rowcrewdocs["DOCNO"];
						$list5 = $rowcrewdocs["DOCCODE"];
						$list6 = $rowcrewdocs["RANK"];
						$list7 = $rowcrewdocs["RANKCODE"];
						$list8 = $rowcrewdocs["ALIAS1"];
						$type = $rowcrewdocs["TYPE"];
						$hasexpiry = $rowcrewdocs["HASEXPIRY"];
						
						if ($hasexpiry == 1)
						{
							if ($list3 != "---")
							{
								$expdate = date('Y-m-d',strtotime($rowcrewdocs["DATEEXPIRED"]));
								$color = checkexpiry($expdate);
							}
							else 
								$color = "color:Red";  //Expiration Date is REQUIRED (pero NULL)
						}
						else 
							$color = "color:Black";  //Expiration Date is NOT REQUIRED					
	
						
						if ($tmptype != $type)
						{
							switch ($type)
							{
								case "D": $typedesc = "Documents"; break;
								case "L": $typedesc = "License"; break;
								case "C": $typedesc = "Certificate"; break;
								
							}
							echo "<tr><td colspan=\"6\" style=\"font-weight:Bold;color:Blue;\"><i>$typedesc</i></td></tr>";
						}
						
						echo "
						<tr>
							<td>&nbsp;$list1</td>
							<td align=\"center\">&nbsp;$list4</td>
							<td align=\"center\">&nbsp;$list8</td>
							<td align=\"center\">&nbsp;$list2</td>
							<td align=\"center\" style=\"font-weight:Bold;$color\">&nbsp;$list3</td>
							";
						
						$dirfilename = $basedir . $applicantno . "/" . $type . "/"  . $list5 . ".pdf";
						
						if (checkpath($dirfilename))
						{
							echo "
								<td align=\"center\" style=\"font-weight:Bold;color:Blue;\">
									<a href=\"#\" onclick=\"openWindow('$dirfilename', '$list5' ,700, 500);\" style=\"font-weight:Bold;color:Green;\" >[VIEW]</a>
								</td>";
						}
						else 
						{
							echo "<td align=\"center\" style=\"font-weight:Bold;color:Gray;\">--NOT YET SCANNED--</td>";
						}
						
						echo "
							<td>
								<input type=\"button\" value=\"Edit\" $disabledoctype style=\"border:1px solid Black;font-size:0.8em;font-weight:Bold;color:White;background-color:Blue;\"
									onclick=\"editdel.value='$list5';doctype2.value='$type';actiontxt.value='edit';submit();\" />
								<input type=\"button\" value=\"Delete\" $disabledoctype style=\"border:1px solid Black;font-size:0.8em;font-weight:Bold;color:White;background-color:Blue;\"
									onclick=\"if(confirm('Delete: $list1 [$list4]. Confirm?'))
												{editdel.value='$list5';doctype2.value='$type';actiontxt.value='delete';submit();}
											\" />
							</td>	
						
						
						</tr>
						";
						
						$tmptype = $type;
					}
					
				echo "	
					</table>
				
			</div>
		</div>
		
		
	</div>

	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"doctype2\" />
	<input type=\"hidden\" name=\"editdel\" />
	<input type=\"hidden\" name=\"editdoccode\" />
	<input type=\"hidden\" name=\"applicantno\" value=\"$applicantno\" />


</form>
</body>

</html>

";