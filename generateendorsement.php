<?php
include('veritas/connectdb.php');
include("veritas/include/stylephp.inc");
include("veritas/include/functions.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');
$basedirid = "idpics/";
$basedirdocs = "docimages/";

$datenow = date("Y-m-d");

if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
else 
	$employeeid = "";

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];

if (isset($_POST['endorsementidno2']))
	$endorsementidno2 = $_POST['endorsementidno2'];
else 
	$endorsementidno2 = "";

if (isset($_POST['applicantno']))
	$applicantno = $_POST['applicantno'];
	
if (isset($_POST['selidno']))
	$selidno = $_POST['selidno'];
else 
	$selidno = "";
	
if (isset($_POST['selschedid']))
	$selschedid = $_POST['selschedid'];
else 
	$selschedid = "";
	
if (isset($_POST['dateissued']))
	$dateissued = $_POST['dateissued'];
else 
	$dateissued = date("m/d/Y");
	
if (isset($_POST['signature1']))
	$signature1 = $_POST['signature1'];
	
if (isset($_POST['signature2']))
	$signature2 = $_POST['signature2'];
	
if (isset($_POST['notedby']))
	$notedby = $_POST['notedby'];
	
if (isset($_POST['note']))
	$note = $_POST['note'];
	
if (isset($_POST['traincode']))
	$traincode = $_POST['traincode'];
	
if (isset($_POST['traincentercode']))
	$traincentercode = $_POST['traincentercode'];
	
if (isset($_POST['docsubmit']))
	$docsubmit = $_POST['docsubmit'];
	
if (isset($_POST['participantno']))
	$participantno = $_POST['participantno'];
else 
	$participantno = "";
	
if (isset($_POST['mode']))
	$mode = $_POST['mode'];
else 
	$mode = "0";
	
if (isset($_POST["searchkey"]))
	$searchkey = $_POST["searchkey"];
	
if (isset($_POST["searchby"]))
	$searchby = $_POST["searchby"];
	
if ($searchkey == "")
	$disablesearch = "disabled=\"disabled\"";
else 
	$disablesearch = "";

	
$showmultiple = "display:none;";
$multiple = 0;

$saved = 0;

if (empty($selidno) && empty($selschedid))
	$disabled = "disabled=\"disabled\"";
else 
	$disabled = "";
	
$disableprint = "disabled=\"disabled\"";
	
switch ($actiontxt)
{
	case "saveid"	:
				
			if (!empty($dateissued))
				$dateissuedraw = "'" . date("Y-m-d",strtotime($dateissued)) . "'";
			else 
				$dateissuedraw = "NULL";
				
			if (empty($participantno))
				$participantno = 0;
			
			$qryinsertid = mysql_query("INSERT INTO endorsementhdr(TRAINCENTERCODE,TRAINCODE,NOTE,SIGN1,SIGN2,NOTEDBY,
									TYPE,DATEISSUED,DOCSUBMITTED,PARTICIPANTNO,MADEBY,MADEDATE) 
									VALUES('$traincentercode','$traincode','$note','$signature1','$signature2','$notedby',$mode,$dateissuedraw,
									'$docsubmit',$participantno,'$employeeid','$currentdate')") or die(mysql_error());
				
			//GET MAX ENDORSE ID
			$qrymaxendorsement = mysql_query("SELECT MAX(ENDORSEID) AS ID FROM endorsementhdr e") or die(mysql_error());
			
			$rowmaxendorsement = mysql_fetch_array($qrymaxendorsement);
			$endorsementidno = $rowmaxendorsement["ID"];

			$wherepart = "";
			if (!empty($selidno))
				$wherepart = "WHERE IDNO=$selidno";
			elseif (!empty($selschedid))
				$wherepart = "WHERE SCHEDID=$selschedid";
					
			$qryendorsetraining = mysql_query("UPDATE crewtraining SET ENDORSEID=$endorsementidno $wherepart") or die(mysql_error());
			
			$saved = 1;
		break;
		
	case "reset"	:
		
			$saved = 0;
			$disabled = "disabled=\"disabled\"";
		break;

	case "print"	:
		
			$qryprinted = mysql_query("UPDATE endorsementhdr SET PRINTBY='$employeeid',PRINTDATE='$currentdate' 
									WHERE ENDORSEID=$endorsementidno2") or die(mysql_error());
			
			$endorsementidno = "";
			$selschedid = "";
			$selidno = "";
		
		break;
		
}


switch ($mode)
{
	case "0"	:  
		
			if (empty($selschedid))
			{
				$note = "";
				$signature1 = "";
				$signature2 = "";
				$notedby = "";
				$dateissued = "";
			}
		break;
	case "1"	:  
	
			if (empty($selidno))
			{
				$signature1 = "";
				$signature2 = "";
				$dateissued = "";
				$docsubmit = "";
				$participantno = "";
			}
		break;
	
}


$qrysign1 = mysql_query("SELECT EMPLOYEEID,CONCAT(GNAME,' ',LEFT(MNAME,1),'. ',FNAME) AS NAMESIGN FROM employee WHERE CERTSIGN=1") or die(mysql_error());

	$selsign1 = "<option selected value=\"\">--Select One--</option>";
	while($rowsign1 = mysql_fetch_array($qrysign1))
	{
		$empid1 = $rowsign1["EMPLOYEEID"];
		$empname1 = $rowsign1["NAMESIGN"];
		
		$selected1 = "";
		
		if ($empid1 == $signature1)
			$selected1 = "SELECTED";
			
		$selsign1 .= "<option $selected1 value=\"$empid1\">$empname1</option>";
	}
	
$qrysign2 = mysql_query("SELECT EMPLOYEEID,CONCAT(GNAME,' ',LEFT(MNAME,1),'. ',FNAME) AS NAMESIGN FROM employee WHERE CERTSIGN=1") or die(mysql_error());

	$selsign2 = "<option selected value=\"\">--Select One--</option>";
	while($rowsign2 = mysql_fetch_array($qrysign2))
	{
		$empid2 = $rowsign2["EMPLOYEEID"];
		$empname2 = $rowsign2["NAMESIGN"];
		
		$selected2 = "";
		
		if ($empid2 == $signature2)
			$selected2 = "SELECTED";
			
		$selsign2 .= "<option $selected2 value=\"$empid2\">$empname2</option>";
	}
	
$qrynotedby = mysql_query("SELECT EMPLOYEEID,CONCAT(GNAME,' ',LEFT(MNAME,1),'. ',FNAME) AS NAMESIGN FROM employee WHERE CERTSIGN=1") or die(mysql_error());

	$selnotedby = "<option selected value=\"\">--Select One--</option>";
	while($rownotedby = mysql_fetch_array($qrynotedby))
	{
		$empid3 = $rownotedby["EMPLOYEEID"];
		$empname3 = $rownotedby["NAMESIGN"];
		
		$selected3 = "";
		
		if ($empid3 == $notedby)
			$selected3 = "SELECTED";
			
		$selnotedby .= "<option $selected3 value=\"$empid3\">$empname3</option>";
	}


echo "
<html>\n
<head>\n
<title>Training - Generate Endorsement</title>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>
<script language=\"javascript\" src=\"popcalendar.js\"></script>

<script>

var flag;

function PrintValidation()
{
	flag=1; 
	return;
	submit();
}

function ConfirmFinal()
{	
	frm=document.certificategen;
	
	if( confirm('Is the print final?') )
	{
//		alert('');
		frm.selidno.value='$selidno';
		frm.actiontxt.value='finalprint';
		frm.submit();
	}
}

</script>


<body onload=\"\" style=\"overflow:hidden;background-color:White;\"
onFocus=\"if(flag==1) {ConfirmFinal(); flag=0;} else {flag=0;}\">\n

<form name=\"endorsementgen\" method=\"POST\">\n

<!--
					<div id=\"multiple\" style=\"position:absolute;top:230px;left:200px;width:600px;height:400px;background-color:#6699FF;
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
-->

<span class=\"wintitle\">GENERATE ENDORSEMENT</span>

	<div style=\"width:100%;height:650px;background-color:Silver;\">
	";
	
	$stylebtn = "style=\"font-size:0.8em;font-weight:Bold;border:thin solid White;background-color:Red;color:White;\"";
	
	echo "
		<div style=\"width:100%;height:50px;background-color:Black;\">
			<br />
			<table>
				<tr>
					<td><input type=\"button\" $stylebtn value=\"Principal (In-house) Training\" onclick=\"mode.value='0';actiontxt.value='showlist';submit();\" /></td>
					<td><input type=\"button\" $stylebtn value=\"Outside Training\" onclick=\"mode.value='1';actiontxt.value='showlist';submit();\" /></td>
				</tr>
			</table>
		</div>

		<div style=\"width:100%;height:600px;\">
	";
	
	$stylebtn = "style=\"border:0;background-color:Navy;color:Orange;font-size:0.9em;font-weight:Bold;cursor:pointer;\"";
	
	$styledtl = "style=\"font-weight:Bold;color:Blue;\"";
				
	switch ($mode)
	{
		case "0"	:  //PRINCIPAL
			
				if(!empty($selschedid))
				{
					$qryaddinfo = mysql_query("SELECT th.TRAINCODE,tc.TRAINING,th.SCHEDID,th.DATEFROM,th.DATETO,
										IF(td.TRAINCENTERCODE IS NOT NULL,trc2.TRAINCENTERCODE,trc.TRAINCENTERCODE) AS TRAINCENTERCODE,
										IF(td.TRAINCENTERCODE IS NOT NULL,trc2.TRAINCENTER,trc.TRAINCENTER) AS TRAINCENTER
										FROM crewtraining ct
										LEFT JOIN trainingschedhdr th ON th.SCHEDID=ct.SCHEDID
										LEFT JOIN trainingscheddtl td ON td.SCHEDID=th.SCHEDID
										LEFT JOIN trainingcourses tc ON tc.TRAINCODE=th.TRAINCODE
										LEFT JOIN trainingcenter trc ON trc.TRAINCENTERCODE=tc.TRAINCENTERCODE
										LEFT JOIN trainingcenter trc2 ON trc2.TRAINCENTERCODE=td.TRAINCENTERCODE
										WHERE th.SCHEDID=$selschedid
										LIMIT 1
										") or die(mysql_errno());
					
					$rowaddinfo = mysql_fetch_array($qryaddinfo);
					
					$showtraincode = $rowaddinfo["TRAINCODE"];
					$showtraining = $rowaddinfo["TRAINING"];
					$showschedid = $rowaddinfo["SCHEDID"];
					
					if (!empty($rowaddinfo["DATEFROM"]))
						$showdatefrom = date("dMY",strtotime($rowaddinfo["DATEFROM"]));
					else 
						$showdatefrom = "";
					
					if (!empty($rowaddinfo["DATETO"]))
						$showdateto = date("dMY",strtotime($rowaddinfo["DATETO"]));
					else 
						$showdateto = "";
						
					if (strtotime($rowaddinfo["DATEFROM"]) == strtotime($rowaddinfo["DATEFROM"]))
						$showdate = $showdatefrom;
					else 
						$showdate = $showdatefrom . " to " . $showdateto;
					
					$showtraincentercode = $rowaddinfo["TRAINCENTERCODE"];
					$showtraincenter = $rowaddinfo["TRAINCENTER"];
					
				}
		
				$qrylisting = mysql_query("SELECT th.DATEFROM,th.DATETO,
								th.TRAINCODE,tc.TRAINING,ct.SCHEDID,COUNT(*) AS CNT
								FROM crewtraining ct
								LEFT JOIN trainingschedhdr th ON th.SCHEDID=ct.SCHEDID
								LEFT JOIN trainingcourses tc ON tc.TRAINCODE=th.TRAINCODE
								WHERE COURSETYPECODE='PRINC' AND ct.ENDORSEID IS NULL
								AND ct.POSTDATE IS NULL
								GROUP BY ct.SCHEDID
								ORDER BY tc.TRAINING,th.SCHEDID
								") or die(mysql_errno());
		
				echo "
					<br />
					<span class=\"sectiontitle\">IN-HOUSE PRINCIPAL TRAINING ENDORSEMENT</span>
					
					<div style=\"width:29%;height:550px;float:left;background-color:White;padding:10 5 0 5;border-right:1px solid Gray;overflow:auto;\">
					
						<span class=\"sectiontitle\">PENDING LIST</span>
						
						<table class=\"listcol\">
							<tr>
								<th width=\"30%\">SCHED ID</th>
								<th width=\"50%\">DATE</th>
								<th width=\"20%\">COUNT</th>
							</tr>
						";
						
						$tmptraincode = "";
				
						while ($rowlisting = mysql_fetch_array($qrylisting))
						{
							if (!empty($rowlisting["DATEFROM"]))
								$lstdatefrom = date("dMY",strtotime($rowlisting["DATEFROM"]));
							else 
								$lstdatefrom = "";
								
							if (!empty($rowlisting["DATETO"]))
								$lstdateto = date("dMY",strtotime($rowlisting["DATETO"]));
							else 
								$lstdateto = "";
								
							if (strtotime($rowaddinfo["DATEFROM"]) == strtotime($rowaddinfo["DATEFROM"]))
								$lstdate = $lstdatefrom;
							else 
								$lstdate = $lstdatefrom . " to " . $lstdateto;
								
							$lsttraincode = $rowlisting["TRAINCODE"];
							$lsttraining = $rowlisting["TRAINING"];
							$lstschedid = $rowlisting["SCHEDID"];
							$lstcount = $rowlisting["CNT"];
							
							if ($tmptraincode != $lsttraincode)
							{
								echo "
								<tr>
									<td colspan=\"3\" style=\"background-color:Blue;color:White;font-size:1.2em;font-weight:Bold;\">$lsttraining</td>
								</tr>
								";
							}
							
							echo "
							<tr $mouseovereffect style=\"cursor:pointer;\" ondblclick=\"selschedid.value='$lstschedid';submit();\">
								<td align=\"center\">$lstschedid</td>
								<td align=\"left\">$lstdate</td>
								<td align=\"center\">$lstcount</td>
							</tr>
							
							";
							
							$tmptraincode = $lsttraincode;
						}
				
				
				
						echo "
						</table>
					
					</div>
					";
						
					echo "
					<div style=\"width:70%;height:575px;float:left;background-color:White;padding:10 5 0 5;\">
						<div style=\"width:100%;height:175px;background-color:White;padding:10 5 0 5;\">
							<span class=\"sectiontitle\">MANIPULATION</span>
							
							<table class=\"setup\" style=\"width:50%;float:left;\">
								<tr>
									<th>Endorsement ID No.</th>
									<th>:</th>
									<td $styledtl>$endorsementidno</td>
								</tr>
								<tr>
									<th>Training</th>
									<th>:</th>
									<td $styledtl>$showtraining</td>
								</tr>
								<tr>
									<th>Schedule ID</th>
									<th>:</th>
									<td $styledtl>$showschedid</td>
								</tr>
								<tr>
									<th>Schedule</th>
									<th>:</th>
									<td $styledtl>$showdate</td>
								</tr>
								<tr>
									<th>Note</th>
									<th>:</th>
									<td><textarea rows=\"3\" cols=\"15\" name=\"note\" $disabled>$note</textarea>
									</td>
								</tr>
							</table>
							
							<table class=\"setup\" style=\"width:50%;float:right;\">
								<tr>
									<th>Signature 1</th>
									<th>:</th>
									<td><select name=\"signature1\" $disabled>
											$selsign1
										</select>
									</td>
								</tr>
								<tr>
									<th>Signature 2</th>
									<th>:</th>
									<td><select name=\"signature2\" $disabled>
											$selsign2
										</select>
									</td>
								</tr>
								<tr>
									<th>Noted By</th>
									<th>:</th>
									<td><select name=\"notedby\" $disabled>
											$selnotedby
										</select>
									</td>
								</tr>
								<tr>
									<th>Date Issued</th>
									<th>:</th>
									<td><input type=\"text\" name=\"dateissued\" $disabled onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\"
											value=\"$dateissued\" /></td>
								</tr>
							</table>
							
						<br />
						<center>
						";
					
						if ($saved == 0)
						{
							echo "							
							<input type=\"button\" $stylebtn value=\"Save Endorsement\" $disabled
									onclick=\"actiontxt.value='saveid';
											traincode.value='$showtraincode';
											traincentercode.value='$showtraincentercode';
											selschedid.value='$selschedid';
											submit();\" />
							";
						}
						else 
						{
							echo "							
							<input type=\"button\" $stylebtn value=\"PRINT Endorsement\" $disabled
									onclick=\"openWindow('rependorsement1.php?endorsementidno=$endorsementidno&schedid=$selschedid', 'rependorse' ,900, 650);
									actiontxt.value='print';submit();\" />
							";
						}
											
						echo "
						</center>
							
						</div>
						
						<div style=\"width:100%;height:375px;float:right;background-color:White;padding:10 5 0 5;\">
							<span class=\"sectiontitle\">PREVIEW</span>
						";	
						
						if (!empty($endorsementidno) && !empty($selschedid))
						{
							echo "
							<iframe marginwidth=0 marginheight=0 id=\"showbody\" frameborder=\"0\" name=\"showbody\" 
								src=\"rependorsement1.php?endorsementidno=$endorsementidno&schedid=$selschedid\" scrolling=\"yes\" style=\"width:100%;height:100%;\">
							</iframe> 
							";
						}
						else 
						{
							echo "
							<br /><br /><br />
							<center>
								<span style=\"font-size:1.3em;color:Silver;\"><i>[Save first before viewing]</i></span>
							</center>
							";
						}
						
						echo "
						</div>
	
					</div>
				
				";
			
			break;
		
		case "1"	:  //OUTSIDE TRAINING
			
				if(!empty($selidno))
				{
					$qryaddinfo = mysql_query("SELECT ct.APPLICANTNO,CONCAT(c.FNAME,', ',c.GNAME,' ',c.MNAME) AS CREWNAME,
										th.TRAINCODE,tc.TRAINING,th.SCHEDID,th.DATEFROM,th.DATETO,
										IF(td.TRAINCENTERCODE IS NOT NULL,trc2.TRAINCENTERCODE,trc.TRAINCENTERCODE) AS TRAINCENTERCODE,
										IF(td.TRAINCENTERCODE IS NOT NULL,trc2.TRAINCENTER,trc.TRAINCENTER) AS TRAINCENTER
										FROM crewtraining ct
										LEFT JOIN crew c ON c.APPLICANTNO=ct.APPLICANTNO
										LEFT JOIN trainingschedhdr th ON th.SCHEDID=ct.SCHEDID
										LEFT JOIN trainingscheddtl td ON td.SCHEDID=th.SCHEDID
										LEFT JOIN trainingcourses tc ON tc.TRAINCODE=th.TRAINCODE
										LEFT JOIN trainingcenter trc ON trc.TRAINCENTERCODE=tc.TRAINCENTERCODE
										LEFT JOIN trainingcenter trc2 ON trc2.TRAINCENTERCODE=td.TRAINCENTERCODE
										WHERE ct.IDNO=$selidno
										LIMIT 1
										") or die(mysql_errno());
					
					$rowaddinfo = mysql_fetch_array($qryaddinfo);
					
					$showname = $rowaddinfo["CREWNAME"];
					$showtraincode = $rowaddinfo["TRAINCODE"];
					$showtraining = $rowaddinfo["TRAINING"];
					$showschedid = $rowaddinfo["SCHEDID"];
					
					if (!empty($rowaddinfo["DATEFROM"]))
						$showdatefrom = date("dMY",strtotime($rowaddinfo["DATEFROM"]));
					else 
						$showdatefrom = "";
					
					if (!empty($rowaddinfo["DATETO"]))
						$showdateto = date("dMY",strtotime($rowaddinfo["DATETO"]));
					else 
						$showdateto = "";
						
					if (strtotime($rowaddinfo["DATEFROM"]) == strtotime($rowaddinfo["DATEFROM"]))
						$showdate = $showdatefrom;
					else 
						$showdate = $showdatefrom . " to " . $showdateto;
					
					$showtraincentercode = $rowaddinfo["TRAINCENTERCODE"];
					$showtraincenter = $rowaddinfo["TRAINCENTER"];
					
				}
		
				$qrylisting = mysql_query("SELECT ct.APPLICANTNO,CONCAT(c.FNAME,', ',c.GNAME,' ',c.MNAME) AS CREWNAME,th.DATEFROM AS TRAINDATE,
								th.TRAINCODE,tc.TRAINING,ct.SCHEDID,ct.IDNO,c.CREWCODE
								FROM crewtraining ct
								LEFT JOIN crew c ON c.APPLICANTNO=ct.APPLICANTNO
								LEFT JOIN trainingschedhdr th ON th.SCHEDID=ct.SCHEDID
								LEFT JOIN trainingcourses tc ON tc.TRAINCODE=th.TRAINCODE
								WHERE COURSETYPECODE='OUTSIDE' AND ct.ENDORSEID IS NULL
								AND ct.POSTDATE IS NULL
								ORDER BY tc.TRAINING,th.SCHEDID,c.FNAME
								") or die(mysql_errno());
		
				echo "
					<br />
					<span class=\"sectiontitle\">OUTSIDE TRAINING ENDORSEMENT</span>
					
					<div style=\"width:29%;height:550px;float:left;background-color:White;padding:10 5 0 5;border-right:1px solid Gray;overflow:auto;\">
					
						<span class=\"sectiontitle\">PENDING LIST</span>
						
						<table class=\"listcol\" style=\"overflow:auto;\">
							<tr>
								<th colspan=\"2\" width=\"10%\">&nbsp;</th>
								<th width=\"20%\">APP NO</th>
								<th width=\"60%\">NAME</th>
							</tr>
						";
						
						$tmptraincode = "";
						$tmpschedid = "";
				
						while ($rowlisting = mysql_fetch_array($qrylisting))
						{
							$lstappno = $rowlisting["APPLICANTNO"];
							$lstcrewname = $rowlisting["CREWNAME"];
							
							if (!empty($rowlisting["TRAINDATE"]))
								$lsttraindate = date("dMY",strtotime($rowlisting["TRAINDATE"]));
							else 
								$lsttraindate = "";
								
							$lsttraincode = $rowlisting["TRAINCODE"];
							$lsttraining = $rowlisting["TRAINING"];
							$lstschedid = $rowlisting["SCHEDID"];
							$lstidno = $rowlisting["IDNO"];
							$lstcrewcode = $rowlisting["CREWCODE"];
							
							if ($tmptraincode != $lsttraincode)
							{
								echo "
								<tr>
									<td colspan=\"4\" style=\"background-color:Blue;color:White;font-size:1.2em;font-weight:Bold;\">$lsttraining</td>
								</tr>
								";
							}
							
							if ($tmpschedid != $lstschedid)
							{
								echo "
								<tr>
									<td colspan=\"4\" style=\"background-color:#FFEFB1;color:Black;font-weight:Bold;text-align:center;\">
										<i>Sched ID: $lstschedid ($lsttraindate)</i>
									</td>
								</tr>
								";
							}
							
							echo "
							<tr $mouseovereffect style=\"cursor:pointer;\" ondblclick=\"selidno.value='$lstidno';submit();\">
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td title=\"Crew Code: $lstcrewcode\">$lstappno</td>
								<td title=\"Crew Code: $lstcrewcode\">$lstcrewname</td>
							</tr>
							
							";
							
							$tmpschedid = $lstschedid;
							$tmptraincode = $lsttraincode;
						}
				
				
				
						echo "
						</table>
					
					</div>
					
					<div style=\"width:70%;height:575px;float:left;background-color:White;padding:10 5 0 5;\">
						<div style=\"width:100%;height:175px;background-color:White;padding:10 5 0 5;\">
							<span class=\"sectiontitle\">MANIPULATION</span>
							
							<table class=\"setup\" style=\"width:50%;float:left;\">
								<tr>
									<th>Endorsement IDNo.</th>
									<th>:</th>
									<td $styledtl>$endorsementidno</td>
								</tr>
								<tr>
									<th>Crew Name</th>
									<th>:</th>
									<td $styledtl>$showname</td>
								</tr>
								<tr>
									<th>Training</th>
									<th>:</th>
									<td $styledtl>$showtraining</td>
								</tr>
								<tr>
									<th>Schedule ID</th>
									<th>:</th>
									<td $styledtl>$showschedid</td>
								</tr>
								<tr>
									<th>Schedule</th>
									<th>:</th>
									<td $styledtl>$showdate</td>
								</tr>
								<tr>
									<th>Training Center</th>
									<th>:</th>
									<td $styledtl>$showtraincenter</td>
								</tr>
							</table>
							
							<table class=\"setup\" style=\"width:50%;float:right;\">
								<tr>
									<th>Signature 1</th>
									<th>:</th>
									<td><select name=\"signature1\" $disabled>
											$selsign1
										</select>
									</td>
								</tr>
								<tr>
									<th>Signature 2</th>
									<th>:</th>
									<td><select name=\"signature2\" $disabled>
											$selsign2
										</select>
									</td>
								</tr>
								<tr>
									<th>Date Issued</th>
									<th>:</th>
									<td><input type=\"text\" name=\"dateissued\" $disabled onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\"
											value=\"$dateissued\" /></td>
								</tr>
								<tr>
									<th>Submitted Documents</th>
									<th>:</th>
									<td><input type=\"text\" name=\"docsubmit\" value=\"$docsubmit\" $disabled /></td>
								</tr>
								<tr>
									<th>Participant No.</th>
									<th>:</th>
									<td><input type=\"text\" name=\"participantno\" value=\"$participantno\" $disabled onKeyPress=\"return numbersonly(this);\"/></td>
								</tr>
							</table>
						
						<br />
						<center>
						";
					
						if ($saved == 0)
						{
							echo "							
							<input type=\"button\" $stylebtn value=\"Save Endorsement\" $disabled
									onclick=\"actiontxt.value='saveid';
											traincode.value='$showtraincode';
											traincentercode.value='$showtraincentercode';
											selidno.value='$selidno';
											submit();\" />
							";
						}
						else 
						{
							echo "							
							<input type=\"button\" $stylebtn value=\"PRINT Endorsement\" $disabled
									onclick=\"openWindow('rependorsement2.php?endorsementidno=$endorsementidno&idno=$selidno', 'rependorse2' ,900, 650);actiontxt.value='print';submit();\" />
							";
							
						}
											
						echo "
						</center>
							
						</div>
						
						<div style=\"width:100%;height:375px;float:right;background-color:White;padding:10 5 0 5;\">
							<span class=\"sectiontitle\">PREVIEW</span>
						";	
						
						if (!empty($endorsementidno) && !empty($selidno))
						{
							echo "
							<iframe marginwidth=0 marginheight=0 id=\"showbody\" frameborder=\"0\" name=\"showbody\" 
								src=\"rependorsement2.php?endorsementidno=$endorsementidno&idno=$selidno\" scrolling=\"yes\" style=\"width:100%;height:100%;\">
							</iframe> 
							";
						}
						else 
						{
							echo "
							<br /><br /><br />
							<center>
								<span style=\"font-size:1.3em;color:Silver;\"><i>[Save first before viewing]</i></span>
							</center>
							";
						}
						
						echo "
						</div>
					</div>
				
				";
			
			break;
	}
	

echo "
		</div>
	</div>
	
	
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"applicantno\" value=\"$applicantno\" />
	<input type=\"hidden\" name=\"selidno\" />
	<input type=\"hidden\" name=\"selschedid\" />
	<input type=\"hidden\" name=\"mode\" value=\"$mode\" />
	
	<input type=\"hidden\" name=\"traincode\" />
	<input type=\"hidden\" name=\"traincentercode\" />
	
	<input type=\"hidden\" name=\"endorsementidno2\" value=\"$endorsementidno\" />
</form>

</body>
";
?>