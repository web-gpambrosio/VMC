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

if (isset($_POST['applicantno']))
	$applicantno = $_POST['applicantno'];
	
if (isset($_POST['selidno']))
	$selidno = $_POST['selidno'];
	
if (isset($_POST['certno']))
	$certno = $_POST['certno'];
	
if (isset($_POST['dateissued']))
	$dateissued = $_POST['dateissued'];
else 
	$dateissued = "";
	
if (isset($_POST['signature1']))
	$signature1 = $_POST['signature1'];
	
if (isset($_POST['signature2']))
	$signature2 = $_POST['signature2'];
	
if (isset($_POST['signature3']))
	$signature3 = $_POST['signature3'];
	
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

$printed = 0;
$disabled = "disabled=\"disabled\"";
$disableprint = "disabled=\"disabled\"";
	

switch ($actiontxt)
{
	case "find"	:
		
		
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
	
		if (mysql_num_rows($qrysearch) == 1)
		{
			$rowsearch = mysql_fetch_array($qrysearch);
			$applicantno = $rowsearch["APPLICANTNO"];
			$classcardno = "";
		}
		elseif (mysql_num_rows($qrysearch) > 1)
		{
			$showmultiple = "display:block;";
			$multiple = 1;
		}
		
		$certno = "";
		$dateissued = "";
		
	break;
	
	case "showcertificate":

			$sign1 = "";
			$sign2 = "";
			$sign3 = "";
		
			$qrycheckcert = mysql_query("SELECT ct.CERTIDNO,cs.SIGNATURE1,cs.SIGNATURE2,cs.SIGNATURE3,cs.DOCNO,cs.DATEISSUED,ct.CERTGENDATE
											FROM crewtraining ct
											LEFT JOIN crewcertstatus cs ON cs.IDNO=ct.CERTIDNO
											WHERE ct.IDNO=$selidno
										") or die(mysql_error());
			
			if (mysql_num_rows($qrycheckcert) > 0)
			{
				$rowcheckcert = mysql_fetch_array($qrycheckcert);
				
				$certgendate = $rowcheckcert["CERTGENDATE"];
				
				if (!empty($certgendate))
				{
					$sign1 = $rowcheckcert["SIGNATURE1"];
					$sign2 = $rowcheckcert["SIGNATURE2"];
					$sign3 = $rowcheckcert["SIGNATURE3"];
					
					$certno = $rowcheckcert["DOCNO"];
					
					if (!empty($rowcheckcert["DATEISSUED"]))
						$dateissued = date("m/d/Y",strtotime($rowcheckcert["DATEISSUED"]));
					else 
						$dateissued = "";
						
					$printed = 1;
				}
				else 
				{
					if (empty($signature1))
						$sign1 = $rowcheckcert["SIGNATURE1"];
					else 
						$sign1 = $signature1;
						
					if (empty($signature2))
						$sign2 = $rowcheckcert["SIGNATURE2"];
					else 
						$sign2 = $signature2;
						
					if (empty($signature3))
						$sign3 = $rowcheckcert["SIGNATURE3"];
					else 
						$sign3 = $signature3;
					
					if (empty($certno))
						$certno = $rowcheckcert["DOCNO"];
					
					if (empty($dateissued))
					{
						if (!empty($rowcheckcert["DATEISSUED"]))
							$dateissued = date("m/d/Y",strtotime($rowcheckcert["DATEISSUED"]));
						else 
							$dateissued = "";
					}
					$printed = 0;
				}
			}
			else 
			{
				$sign1 = $signature1;
				$sign2 = $signature2;
				$sign3 = $signature3;
				
				$printed = 0;
			}
			
			
		break;
		
	case "savecertificate"	:
		
			$qryverify = mysql_query("SELECT ct.CERTIDNO,ct.RANKCODE,ct.GRADE,tc.DOCCODE
										FROM crewtraining ct
										LEFT JOIN trainingschedhdr th ON th.SCHEDID=ct.SCHEDID
										LEFT JOIN trainingcourses tc ON tc.TRAINCODE=th.TRAINCODE
										WHERE IDNO=$selidno
									") or die(mysql_error());
			
			$rowverify = mysql_fetch_array($qryverify);
			$certidno = $rowverify["CERTIDNO"];
			
			if (!empty($rowverify["RANKCODE"]))
				$certrankcode = "'" . $rowverify["RANKCODE"] . "'";
			else 
				$certrankcode = "NULL";
				
			if (!empty($rowverify["GRADE"]))
				$certgrade = $rowverify["GRADE"];
			else 
				$certgrade = "NULL";
				
			if (!empty($rowverify["DOCCODE"]))
				$certdoccode = "'" . $rowverify["DOCCODE"] . "'";
			else 
				$certdoccode = "NULL";
				
				
			if (!empty($dateissued))
				$issueddate = "'" . date("Y-m-d",strtotime($dateissued)) . "'";
			else 
				$issueddate = "NULL";
			
			if (!empty($certidno))
			{
				//UPDATE CERTIFICATE
				
				$qrycertupdate = mysql_query("UPDATE crewcertstatus SET SIGNATURE1 = '$signature1',
																		SIGNATURE2 = '$signature2',
																		SIGNATURE3 = '$signature3',
																		DOCNO = '$certno',
																		DATEISSUED = $issueddate
											WHERE IDNO=$certidno
											") or die(mysql_error());
				
			}
			else 
			{
				//INSERT CERTIFICATE
				
				$qrycertinsert = mysql_query("INSERT INTO crewcertstatus(APPLICANTNO,RANKCODE,DOCCODE,DOCNO,DATEISSUED,SIGNATURE1,SIGNATURE2,SIGNATURE3,MADEBY,MADEDATE) 
											VALUES ($applicantno,$certrankcode,$certdoccode,'$certno',$issueddate,'$signature1','$signature2','$signature3','$employeeid','$currentdate')
										") or die(mysql_error());
				
				$qrygetidno = mysql_query("SELECT IDNO FROM crewcertstatus WHERE APPLICANTNO=$applicantno AND DOCNO='$certno' AND DOCCODE=$certdoccode") or die(mysql_error());
				
				$rowgetidno = mysql_fetch_array($qrygetidno);
				$getidno = $rowgetidno["IDNO"];
				
				$qrytrainupdate = mysql_query("UPDATE crewtraining SET CERTIDNO=$getidno 
													WHERE APPLICANTNO=$applicantno AND IDNO=$selidno") or die(mysql_error());
				
			}
			
			
			
//			$qrydocinfo = mysql_query("SELECT tc.DOCCODE,ct.RANKCODE
//								FROM crewtraining ct
//								LEFT JOIN trainingschedhdr th ON th.SCHEDID=ct.SCHEDID
//								LEFT JOIN trainingcourses tc ON tc.TRAINCODE=th.TRAINCODE
//								WHERE ct.IDNO = $selidno
//							") or die(mysql_error());
		

			$sign1 = $signature1;
			$sign2 = $signature2;
			$sign3 = $signature3;
			
		break;
	
	case "finalprint"	:
		
			$qryfinalprint = mysql_query("UPDATE crewtraining SET CERTGENBY='$employeeid',
																CERTGENDATE='$currentdate'
											WHERE APPLICANTNO=$applicantno AND IDNO=$selidno
									") or die(mysql_error());
		
		break;
}


if (!empty($applicantno))
{
	$qryenrolled = mysql_query("SELECT ct.IDNO,ct.SCHEDID,th.TRAINCODE,tc.TRAINING,th.DATEFROM,th.DATETO,ct.CLASSCARDNO
								FROM crewtraining ct
								LEFT JOIN trainingschedhdr th ON th.SCHEDID=ct.SCHEDID
								LEFT JOIN trainingcourses tc ON tc.TRAINCODE=th.TRAINCODE
								LEFT JOIN coursetype c ON c.COURSETYPECODE=tc.COURSETYPECODE
								WHERE ct.APPLICANTNO=$applicantno
								AND POSTDATE IS NOT NULL AND ct.CLASSCARDNO IS NOT NULL 
								AND CERTGENDATE IS NULL
								AND c.COURSETYPECODE = 'INHSE' AND tc.STATUS = 1 AND th.STATUS = 1
								AND FINALIZEDDATE IS NULL
								ORDER BY tc.TRAINING
								") or die(mysql_error());
	
	$qrycrew = mysql_query("SELECT CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,CREWCODE,
							CONCAT(GNAME,' ',MNAME,' ',FNAME) AS CERTNAME1,
							CONCAT(GNAME,' ',LEFT(MNAME,1),'. ',FNAME) AS CERTNAME2
							FROM crew
							WHERE APPLICANTNO=$applicantno
							") or die(mysql_error());
	
	$rowcrew = mysql_fetch_array($qrycrew);
	$crewcode = $rowcrew["CREWCODE"];
	$crewname = $rowcrew["NAME"];
	
	$certname1 = strtoupper($rowcrew["CERTNAME1"]);
	$certname2 = strtoupper($rowcrew["CERTNAME2"]);
	
}

$qrysign1 = mysql_query("SELECT EMPLOYEEID,CONCAT(CERTRANK,' ',GNAME,' ',LEFT(MNAME,1),'. ',FNAME) AS NAMESIGN FROM employee WHERE CERTSIGN=1") or die(mysql_error());

	$selsign1 = "<option selected value=\"\">--Select One--</option>";
	while($rowsign1 = mysql_fetch_array($qrysign1))
	{
		$empid1 = $rowsign1["EMPLOYEEID"];
		$empname1 = $rowsign1["NAMESIGN"];
		
		$selected1 = "";
		
		if ($empid1 == $sign1)
			$selected1 = "SELECTED";
			
		$selsign1 .= "<option $selected1 value=\"$empid1\">$empname1</option>";
	}
	
$qrysign2 = mysql_query("SELECT EMPLOYEEID,CONCAT(CERTRANK,' ',GNAME,' ',LEFT(MNAME,1),'. ',FNAME) AS NAMESIGN FROM employee WHERE CERTSIGN=1") or die(mysql_error());

	$selsign2 = "<option selected value=\"\">--Select One--</option>";
	while($rowsign2 = mysql_fetch_array($qrysign2))
	{
		$empid2 = $rowsign2["EMPLOYEEID"];
		$empname2 = $rowsign2["NAMESIGN"];
		
		$selected2 = "";
		
		if ($empid2 == $sign2)
			$selected2 = "SELECTED";
			
		$selsign2 .= "<option $selected2 value=\"$empid2\">$empname2</option>";
	}

$qrysign3 = mysql_query("SELECT EMPLOYEEID,CONCAT(CERTRANK,' ',GNAME,' ',LEFT(MNAME,1),'. ',FNAME) AS NAMESIGN FROM employee WHERE CERTSIGN=1") or die(mysql_error());

	$selsign3 = "<option selected value=\"\">--Select One--</option>";
	while($rowsign3 = mysql_fetch_array($qrysign3))
	{
		$empid3 = $rowsign3["EMPLOYEEID"];
		$empname3 = $rowsign3["NAMESIGN"];
		
		$selected3 = "";
		
		if ($empid3 == $sign3)
			$selected3 = "SELECTED";
			
		$selsign3 .= "<option $selected3 value=\"$empid3\">$empname3</option>";
	}

	
if (!empty($selidno))  //SHOW CERTIFICATE
{
	$qrycertificate = mysql_query("SELECT ct.IDNO,ct.SCHEDID,th.TRAINCODE,tc.TRAINING,th.DATEFROM,th.DATETO,r.ALIAS2 AS RANKALIAS
									FROM crewtraining ct
									LEFT JOIN trainingschedhdr th ON th.SCHEDID=ct.SCHEDID
									LEFT JOIN trainingcourses tc ON tc.TRAINCODE=th.TRAINCODE
									LEFT JOIN rank r ON r.RANKCODE=ct.RANKCODE
									WHERE ct.IDNO=$selidno AND APPLICANTNO=$applicantno
						") or die(mysql_error());
	
	$disabled = "";


	//CHECK IF GENERATED ALREADY

//	$qrygenerated = mysql_query("SELECT CERTGENBY,CERTGENDATE FROM crewtraining WHERE IDNO=$selidno AND APPLICANTNO=$applicantno") or die(mysql_error());
//	
//	$rowgenerated = mysql_fetch_array($qrygenerated);
//	
//	$genby = $rowgenerated["CERTGENBY"];
//	$gendate = $rowgenerated["CERTGENDATE"];
//	
//	if (empty($gendate))
//	{
//		$disableprint = "";
//	}
//	else 
//	{
//		$disableprint = "disabled=\"disabled\"";
//	}


	//END OF CHECKING
	
	if (!empty($sign1))
	{
		$qryfullname1 = mysql_query("
				SELECT CONCAT(CERTRANK,' ',GNAME,' ',LEFT(MNAME,1),'. ',FNAME) AS NAMESIGN,d.DESIGNATION
				FROM employee e
				LEFT JOIN designation d ON d.DESIGNATIONCODE=e.DESIGNATIONCODE
				WHERE e.EMPLOYEEID = '$sign1'
		") or die(mysql_error());
		
		if (mysql_num_rows($qryfullname1) > 0)
		{
			$rowfullname1 = mysql_fetch_array($qryfullname1);
			$showsign1 = $rowfullname1["NAMESIGN"];
			$showpos1 = $rowfullname1["DESIGNATION"];
		}
		else 
			$showsign1 = "";
	}
	
	if (!empty($sign2))
	{
		$qryfullname2 = mysql_query("
				SELECT CONCAT(CERTRANK,' ',GNAME,' ',LEFT(MNAME,1),'. ',FNAME) AS NAMESIGN,d.DESIGNATION
				FROM employee e
				LEFT JOIN designation d ON d.DESIGNATIONCODE=e.DESIGNATIONCODE
				WHERE e.EMPLOYEEID = '$sign2'
		") or die(mysql_error());
		
		if (mysql_num_rows($qryfullname2) > 0)
		{
			$rowfullname2 = mysql_fetch_array($qryfullname2);
			$showsign2 = $rowfullname2["NAMESIGN"];
			$showpos2 = $rowfullname2["DESIGNATION"];
		}
		else 
			$showsign2 = "";
	}
	
	if (!empty($sign3))
	{
		$qryfullname3 = mysql_query("
				SELECT CONCAT(CERTRANK,' ',GNAME,' ',LEFT(MNAME,1),'. ',FNAME) AS NAMESIGN,d.DESIGNATION
				FROM employee e
				LEFT JOIN designation d ON d.DESIGNATIONCODE=e.DESIGNATIONCODE
				WHERE e.EMPLOYEEID = '$sign3'
		") or die(mysql_error());
		
		if (mysql_num_rows($qryfullname3) > 0)
		{
			$rowfullname3 = mysql_fetch_array($qryfullname3);
			$showsign3 = $rowfullname3["NAMESIGN"];
			$showpos3 = $rowfullname3["DESIGNATION"];
		}
		else 
			$showsign3 = "";
		
	}
	
	
}

	if ($printed == 0)
	{
		$disableprint = "";
	}
	else 
	{
		$disableprint = "disabled=\"disabled\"";
	}

	
echo "
<html>\n
<head>\n
<title>Training - Certificate Generation</title>
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

<form name=\"certificategen\" method=\"POST\">\n


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


<span class=\"wintitle\">CERTIFICATE GENERATION</span>

	<div style=\"width:100%;height:650px;background-color:Silver;\">

		<center>
		<table style=\"width:70%;margin-top:5px;\" border=1>
			<tr>
				<td align=\"left\" style=\"font-size:0.8em;font-weight:Bold;\">Search By</td>
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
				<td align=\"left\" style=\"font-size:0.8em;font-weight:Bold;\">Search Key</td>
				<td align=\"center\">
						<input type=\"text\" name=\"searchkey\" size=\"20\" value=\"$searchkey\" $disablesearch
						onkeyup=\"this.value=this.value.toUpperCase();if (this.value != '') {btnfind.disabled=false;}\"
						style=\"color:Red;font-weight:Bold;\" onkeydown=\"if(event.keyCode==13) {btnfind.focus();}\"/>
						
					<input type=\"button\" name=\"btnfind\" value=\"Find\" $disablesearch onclick=\"actiontxt.value='find';submit();\" />
				</td>
			</tr>
		</table>
		</center>
		<br />
		
		<div style=\"width:30%;height:600px;float:left;background-color:Gray;\">
		
			<div style=\"width:100%;float:left;overflow:auto;\">
				<span class=\"sectiontitle\">VMC ENROLLED TRAININGS</span>
				<br />
				<table width=\"100%\" class=\"listcol\">
					<tr>
						<th width=\"50%\">TRAINING</th>
						<th width=\"25%\">FROM</th>
						<th width=\"25%\">TO</th>
					</tr>
				";

				$styledtl2 = "style=\"color:Black;border-bottom:1px dashed gray;cursor:pointer;\"";

				while ($rowenrolled = mysql_fetch_array($qryenrolled))
				{
					$enrollidno = $rowenrolled["IDNO"];
					$enrollschedid = $rowenrolled["SCHEDID"];
					$enrolltraining = $rowenrolled["TRAINING"];
					
					if (!empty($rowenrolled["DATEFROM"]))
						$enrollfrom = date("dMY",strtotime($rowenrolled["DATEFROM"]));
					else 
						$enrollfrom = "";
						
					if (!empty($rowenrolled["DATETO"]))
						$enrollto = date("dMY",strtotime($rowenrolled["DATETO"]));
					else 
						$enrollto = "";
					
					echo "
					<tr onclick=\"selidno.value='$enrollidno';actiontxt.value='showcertificate';submit();\" $mouseovereffect>
						<td $styledtl2 title=\"ID No. $enrollidno\">$enrolltraining</td>
						<td align=\"center\" $styledtl2>$enrollfrom</td>
						<td align=\"center\" $styledtl2>$enrollto</td>
					</tr>
					";
				}
				
				echo "
				</table>
				
			</div>
		</div>
		
		<div style=\"width:70%;float:right;background-color:Navy;\">
		
			<span class=\"sectiontitle\">CREW INFORMATION</span>
			
			<div style=\"width:100%;height:100px;float:left;\">
			";
				$stylehdr = "style=\"font-size:0.7em;font-weight:Bold;color:White;\"";
				$styledtl = "style=\"font-size:0.9em;font-weight:Bold;color:Yellow;\"";
				
			echo "
				<div style=\"width:80%;float:left;padding:5 5 5 10;\">
					<table style=\"width:64%;float:left;\">
						<tr>
							<td $stylehdr>APPLICANT NO.</td>
							<td $stylehdr>:</td>
							<td $styledtl>$applicantno</td>
						</tr>
						<tr>
							<td $stylehdr>CREW CODE</td>
							<td $stylehdr>:</td>
							<td $styledtl>$crewcode</td>
						</tr>
						<tr>
							<td $stylehdr>NAME</td>
							<td $stylehdr>:</td>
							<td $styledtl>$crewname</td>
						</tr>
						<tr>
							<td $stylehdr>SIGN1</td>
							<td $stylehdr>:</td>
							<td $styledtl>
								<select name=\"signature1\" $disabled $disableprint style=\"font-size:0.7em;\">
									$selsign1
								</select>
							</td>
						</tr>
						<tr>
							<td $stylehdr>SIGN2</td>
							<td $stylehdr>:</td>
							<td $styledtl>
								<select name=\"signature2\" $disabled $disableprint style=\"font-size:0.7em;\">
									$selsign2
								</select>
							</td>
						</tr>
						<tr>
							<td $stylehdr>SIGN3</td>
							<td $stylehdr>:</td>
							<td $styledtl>
								<select name=\"signature3\" $disabled $disableprint style=\"font-size:0.7em;\">
									$selsign3
								</select>
							</td>
						</tr>
					</table>
					
					<table style=\"width:35%;float:right;\">
						<tr>
							<td $stylehdr>CERTIFICATE NO. <br />
								<input type=\"text\" name=\"certno\" $disabled $disableprint value=\"$certno\"/>
							</td>
						</tr>
						<tr>
							<td $stylehdr>DATE ISSUED <br />
								<input type=\"text\" name=\"dateissued\" $disabled $disableprint value=\"$dateissued\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
								<img src=\"calendaricon.gif\" name=\"dateissuedgif\" $disabled $disableprint width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(dateissued, dateissued, 'mm/dd/yyyy', 0, 0);return false;\">
							</td>
						</tr>
						<tr>
							<td><input type=\"button\" $disabled $disableprint value=\"Update Preview\" onclick=\"selidno.value='$selidno';actiontxt.value='showcertificate';submit();\" /></td>
						</tr>
					</table>
					
					
				</div>
				
				<div style=\"width:20%;float:right;color:Orange;\">
	";
					$dirfilename = $basedirid . $applicantno . ".jpg";
					if (checkpath($dirfilename))
					{
						$scale = imageScale($dirfilename,-1,100);
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
			
			<div style=\"width:100%;height:420px;overflow:hidden;background-color:White;border:1px solid Gray;\">
				<span class=\"sectiontitle\">CERTIFICATE PREVIEW</span>
				
				<div style=\"width:100%;height:390px;margin:5 5 5 5;border:10px double black;overflow:auto;\">
				";
				
				if (empty($disabled))
				{
		
				echo "
					<table style=\"width:100%;height:20px;\">
						<tr>
							<td width=\"20%\" style=\"font-size:0.6em;font-weight:Bold;color:Black;text-align:center;\">VMC Form 234-A<br />September 2000</td>
							<td width=\"60%\" style=\"font-size:1.2em;font-weight:Bold;text-align:center;\">&nbsp;</td>
							<td width=\"20%\" style=\"font-size:0.6em;font-weight:Bold;color:Black;text-align:center;\">Cert. No. $certno</td>
						</tr>
						<tr>
							<td width=\"20%\">&nbsp;</td>
							<td width=\"60%\" style=\"font-size:1.2em;font-weight:Bold;text-align:center;\">VERITAS MARITIME CORPORATION</td>
							<td width=\"20%\">&nbsp;</td>
						</tr>
					</table>
					
					
			";
				
					$rowcertificate = mysql_fetch_array($qrycertificate);
					$certschedid = $rowcertificate["SCHEDID"];
					$certtraincode = $rowcertificate["TRAINCODE"];
					$certtraining = $rowcertificate["TRAINING"];
					$certrankalias = $rowcertificate["RANKALIAS"];
					
					if (!empty($rowcertificate["DATEFROM"]))
						$certfrom = date("F j, Y",strtotime($rowcertificate["DATEFROM"]));
					else 
						$certfrom = "";
						
					if (!empty($rowcertificate["DATETO"]))
						$certto = date("F j, Y",strtotime($rowcertificate["DATETO"]));
					else 
						$certto = "";
							
						
					$normalstyle = "style=\"font-size:0.8em;font-family:Times New Roman;\"";
						
					echo "
					<center>
					

					<span $normalstyle><i>Presents this</i></span>
					<br />
					<span style=\"font-family:Times New Roman;font-size:1.4em;font-weight:Bold;\"><i>Certificate of Attendance</i></span>
					<br />
					<span $normalstyle><i>to</i></span>
					<br />
					
					<span style=\"font-family:Times New Roman;font-size:1.7em;font-weight:Bold;color:Blue;\">$certrankalias&nbsp; $certname2</span>
					<br />
					
					<span $normalstyle><i>for having succesfully completed the in-house seminar</i></span>
					<br />
					<span $normalstyle><i>for <b>$certtraining</b> in accordance with the</i></span>
					<br />
					<span $normalstyle><i>Seafarers Training, Certification and Watchkeeping</i></span>
					<br />
					<span $normalstyle><i>(STCW '95) Code Section A-11/2</i></span>
					<br />
					";
					
					if (strtotime($certfrom) == strtotime($certto))
						$traindate =  "<i>Date: </i><b><u>$certfrom</u></b>";
					else 
						$traindate =  "<i>From</i> <b>$certfrom</b> <i>to</i> <b>$certto</b>";
					
					echo "
					<span $normalstyle> $traindate</span>
					<br />
					
					</center>
					
					<br />
					";
						
					$modresult = 0;
					$qrymodules = mysql_query("SELECT MODULEID,MODULE FROM traincoursemodules WHERE TRAINCODE='$certtraincode'
												ORDER BY MODULEID
											") or die(mysql_error());
					
					if (mysql_num_rows($qrymodules) > 0)
					{
						$modresult = 1;
						echo "
						<div style=\"width:100%;height:155px;background-color:White;overflow:hidden;\">
							<center>
							<table style=\"width:50%;\">
							";
							
							$stylemodules = "style=\"font-family:Times New Roman;font-size:0.7em;font-weight:Bold;\"";
							
							while ($rowmodules = mysql_fetch_array($qrymodules))
							{
								$moduleid = $rowmodules["MODULEID"];
								
								switch ($rowmodules["MODULEID"])
								{
									case "1"	:	$moduleid = "I. "; break;
									case "2"	:	$moduleid = "II. "; break;
									case "3"	:	$moduleid = "III. "; break;
									case "4"	:	$moduleid = "IV. "; break;
									case "5"	:	$moduleid = "V. "; break;
									case "6"	:	$moduleid = "VI. "; break;
									case "7"	:	$moduleid = "VII. "; break;
									case "8"	:	$moduleid = "VIII. "; break;
									case "9"	:	$moduleid = "IX. "; break;
									case "10"	:	$moduleid = "X. "; break;
								}
								
								$module = $rowmodules["MODULE"];
								
								echo "
								<tr>
									<td width=\"20%\" $stylemodules align=\"right\" >$moduleid</td>
									<td width=\"3%\">&nbsp;</td>
									<td width=\"77%\" $stylemodules\"><i>$module</i></td>
								</tr>
								";
								
							}
							
							echo "
							</table>
							</center>
						</div>
						
						<br />
						";
					}
					else 
					{
						echo "
						<div style=\"width:100%;height:50px;background-color:White;\">
						
						</div>
						";
					}
					
					if (!empty($dateissued))
					{
						$showdate1 = date("jS",strtotime($dateissued));
						$showdate2 = date("F Y",strtotime($dateissued));
						
						$showdate = $showdate1 . " day of " . $showdate2;
					}
					else 
						$showdate = "";
						
					echo "
					<center>
						<span $normalstyle><i>ISSUED at Manila, Philippines this <b>$showdate</b></i>. </span>
					
					</center>

					";
					
					$stylesign = "style=\"font-family:Times New Roman;font-size:0.8em;font-weight:Bold;text-align:center;\"";
					
					if ($modresult == 1)
						echo "
						<br /><br />
						";
					else 
						echo "
						<br /><br /><br /><br /><br /><br />
						";
					
					echo "
					<table width=\"100%\">
						<tr>
							<td width=\"33%\" $stylesign>
								<i>$showsign1</i> <br />
								<span style=\"font-size:0.7em;\">$showpos1</span>
								
							</td>
							<td width=\"33%\" $stylesign>
								<i>$showsign2</i> <br />
								<span style=\"font-size:0.7em;\">$showpos2</span>
							</td>
							<td width=\"33%\" $stylesign>
								<i>$showsign3</i> <br />
								<span style=\"font-size:0.7em;\">$showpos3</span>
							</td>
						</tr>
					</table>

					<br /><br />
			
				</div>
				";
			
				}
				else 
				{
					echo "
					<br /><br /><br /><br /><br />
					<center>
						<span style=\"font-size:1.4em;font-weight:Bold;color:Red;\"><i>[NO PREVIEW]</i></span>
					</center>
					";
				}
				
			echo "
			</div>
			
			<div style=\"width:100%;height:30px;background-color:Black;\">
				<center>
					<input type=\"button\" name=\"btnsave\" $disabled $disableprint value=\"Save Certificate\" 
						onclick=\"selidno.value='$selidno';actiontxt.value='savecertificate';submit();\" />
						
					<input type=\"button\" name=\"btnprint\" $disabled $disableprint value=\"Print Certificate\" 
						onclick=\"selidno.value='$selidno';openWindow('repcertificate.php?applicantno=$applicantno&traindidno=$selidno', 'repcertificate' ,900, 650);PrintValidation();\" />
				</center>
			
			</div>
		</div>

	</div>
	
	
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"applicantno\" value=\"$applicantno\" />
	<input type=\"hidden\" name=\"selidno\" />
</form>

</body>
";
?>