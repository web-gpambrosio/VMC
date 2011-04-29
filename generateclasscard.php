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
	
$qrygetpassword = mysql_query("SELECT PASSWORD FROM employee WHERE EMPLOYEEID='$employeeid'") or die(mysql_error());
$rowgetpassword = mysql_fetch_array($qrygetpassword);
$passwd = $rowgetpassword["PASSWORD"];


if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];

if (isset($_POST['applicantno']))
	$applicantno = $_POST['applicantno'];
	
if (isset($_POST['classcardno']))
	$classcardno = $_POST['classcardno'];
	
if (isset($_POST['viewclasscardno']))
	$viewclasscardno = $_POST['viewclasscardno'];
	
if (isset($_POST['selschedid']))
	$selschedid = $_POST['selschedid'];
	
if (isset($_POST['delschedid']))
	$delschedid = $_POST['delschedid'];
	
if (isset($_POST['remarks']))
	$remarks = $_POST['remarks'];
	
if (isset($_POST['cancelcardno']))
	$cancelcardno = $_POST['cancelcardno'];
	
if (isset($_POST['acceptby']))
	$acceptby = $_POST['acceptby']; //APPLICANT NO USED (NOT CREW CODE)
	
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

$showissued = "display:none;";
$issued = 0;


$statprinted = 0;
$statcancelled = 0;
$stataccepted = 0;
$statcreated = 0;

$statenrollempty = 0;
$statclassempty = 0;


$disabledprint = "";
$disabledcancel = "";
$disabledcreate = "";
$disabledallenroll = "";
$disabledallclass = "";
$disabledfinal = "";
	

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
		
	break;
	
	case "showissuance":

			$showissued = "display:block;";
			$issued = 0;
			
		break;
		
	case "updateissuance":
		
			$qryupdateaccept = mysql_query("UPDATE classcardhdr SET ACCEPTBY='$employeeid',ACCEPTDATE='$currentdate'
												WHERE CLASSCARDNO=$classcardno") or die(mysql_error());

		break;
		
	case "updateclasscard":
	
			// $qrygetremarks = mysql_query("
							// SELECT te.REMARKS
							// FROM crewtraining ct
							// LEFT JOIN trainingendorsement te ON te.APPLICANTNO=ct.APPLICANTNO AND te.SCHEDID=ct.SCHEDID
							// WHERE ct.APPLICANTNO=$applicantno AND ct.SCHEDID=$selschedid
						// ") or die(mysql_error());
						
			
			// $rowgetremarks = mysql_fetch_array($qrygetremarks);
			// $endorseremarks = $rowgetremarks["REMARKS"];
				
			// if (!empty($endorseremarks))
			// {
				// if (!empty($cardremarks))
					// $cardremarks .= "\\n" . $endorseremarks;
				// else
					// $cardremarks = $endorseremarks;
			// }
				
		
			$qryupdateclasscard = mysql_query("UPDATE crewtraining SET CLASSCARDNO=$classcardno
												WHERE APPLICANTNO=$applicantno AND SCHEDID=$selschedid") or die(mysql_error());

		break;
		
	case "updateclasscardall":
	
			$qryupdateclasscard = mysql_query("UPDATE crewtraining SET CLASSCARDNO=$classcardno
												WHERE CLASSCARDNO=0 AND APPLICANTNO=$applicantno") or die(mysql_error());
												
			// $qrygetremarks = mysql_query("
							// SELECT te.REMARKS
							// FROM crewtraining ct
							// LEFT JOIN trainingendorsement te ON te.APPLICANTNO=ct.APPLICANTNO AND te.SCHEDID=ct.SCHEDID
							// WHERE ct.CLASSCARDNO=$classcardno
						// ") or die(mysql_error());
						
			// while ($rowgetremarks = mysql_fetch_array($qrygetremarks))
			// {
				// $endorseremarks = $rowgetremarks["REMARKS"];
					
				// if (!empty($endorseremarks))
				// {
					// if (!empty($cardremarks))
						// $cardremarks .= "\\n" . $endorseremarks;
					// else
						// $cardremarks = $endorseremarks;
				// }
			// }
		break;
		
	case "removeclasscardentry":
		
			$qryupdateclasscard = mysql_query("UPDATE crewtraining SET CLASSCARDNO=0
												WHERE APPLICANTNO=$applicantno AND SCHEDID=$delschedid") or die(mysql_error());

		break;
		
	case "removeclasscardall":
		
			$qryupdateclasscard = mysql_query("UPDATE crewtraining SET CLASSCARDNO=0
												WHERE APPLICANTNO=$applicantno AND CLASSCARDNO=$classcardno") or die(mysql_error());

		break;
		
	case "viewclasscard":
		
			$classcardno = $viewclasscardno;

		break;
		
	case "printclasscard":
		
			//SAVE REMARKS
				$qrysaveremarks = mysql_query("UPDATE classcardhdr SET REMARKS='$remarks',PRINTBY='$employeeid',PRINTDATE='$currentdate' 
										WHERE CLASSCARDNO=$classcardno") or die(mysql_error());
			
			//FLAG FOR PRINTING (IS THE PRINT FINAL?)
		

		break;
		
	case "cancelclasscard":
		
			$qryupdateclasscard = mysql_query("UPDATE classcardhdr SET CANCELDATE='$currentdate', CANCELBY='$employeeid'
											WHERE CLASSCARDNO=$cancelcardno") or die(mysql_error());

		break;
		
	case "createclasscard":
		
			$qrygetmaxcardno = mysql_query("SELECT MAX(CLASSCARDNO) AS MAXCLASSCARDNO FROM classcardhdr") or die(mysql_error());
			$rowgetmaxcardno = mysql_fetch_array($qrygetmaxcardno);
			
			$maxclasscardno = $rowgetmaxcardno["MAXCLASSCARDNO"];

			$qrycheckcardno = mysql_query("SELECT SCHEDID FROM crewtraining WHERE CLASSCARDNO=$maxclasscardno") or die(mysql_error());
			
			if (mysql_num_rows($qrycheckcardno) > 0)  //USED ALREADY -- CREATE NEW NUMBER
			{
				$qryclasscardinsert = mysql_query("INSERT INTO classcardhdr(MADEBY,MADEDATE) VALUES('$employeeid','$currentdate')") or die(mysql_error());
				
				$qrygetmaxcardno2 = mysql_query("SELECT MAX(CLASSCARDNO) AS MAXCLASSCARDNO FROM classcardhdr") or die(mysql_error());
				$rowgetmaxcardno2 = mysql_fetch_array($qrygetmaxcardno2);
				
				$classcardno = $rowgetmaxcardno2["MAXCLASSCARDNO"];
			}
			else 
			{
				$classcardno = $maxclasscardno;
			}
			
		break;
	
}

include("include/crewsummary.inc");


if (!empty($applicantno) && !empty($classcardno))
{
	//CLASSCARD DETAILS - TRAININGS INVOLVED
	$qryclasscard = mysql_query("SELECT th.SCHEDID,td.IDNO,th.DATEFROM,th.DATETO,th.STATUS,
								trc.TRAINCODE,trc.TRAINING,
								tv.TRAINVENUECODE,tv.TRAINVENUE,
								tc.TRAINCENTERCODE,tc.TRAINCENTER,
								td.SCHEDDATE,td.INSTRUCTOR,td.TIMESTART,td.TIMEEND,
								ct.COURSETYPECODE,ct.COURSETYPE,c.FINALIZEDDATE,
								te.REMARKS
								FROM crewtraining c
								LEFT JOIN trainingschedhdr th ON th.SCHEDID=c.SCHEDID
								LEFT JOIN trainingscheddtl td ON td.SCHEDID=th.SCHEDID
								LEFT JOIN trainingvenue tv ON tv.TRAINVENUECODE=td.TRAINVENUECODE
								LEFT JOIN trainingcenter tc ON tc.TRAINCENTERCODE=td.TRAINCENTERCODE
								LEFT JOIN trainingcourses trc ON trc.TRAINCODE=th.TRAINCODE
								LEFT JOIN coursetype ct ON ct.COURSETYPECODE=trc.COURSETYPECODE
								LEFT JOIN trainingendorsement te ON te.APPLICANTNO=c.APPLICANTNO AND te.SCHEDID=c.SCHEDID
								WHERE c.CLASSCARDNO=$classcardno AND c.APPLICANTNO=$applicantno
								ORDER BY ct.COURSETYPECODE,th.SCHEDID,td.SCHEDDATE,trc.TRAINCODE
							") or die(mysql_error());
	
	
	if (mysql_num_rows($qryclasscard) > 0)
	{
		$statclassempty = 0;
		
		//CLASSCARD STATUS
		$qrygetclasscarddtls = mysql_query("SELECT ch.PRINTDATE,ch.PRINTBY,ch.ACCEPTDATE,ch.ACCEPTBY,ch.CANCELDATE,ch.CANCELBY,ch.REMARKS
									FROM classcardhdr ch
									WHERE ch.CLASSCARDNO=$classcardno
								") or die(mysql_error());
		
		$rowgetclasscarddtls = mysql_fetch_array($qrygetclasscarddtls);
		
		if (!empty($rowgetclasscarddtls["PRINTDATE"]))
			$cardprintdate = date("dMY",strtotime($rowgetclasscarddtls["PRINTDATE"]));
		else 
			$cardprintdate = "";
			
		$cardprintby = $rowgetclasscarddtls["PRINTBY"];
		
		if (!empty($rowgetclasscarddtls["ACCEPTDATE"]))
			$cardacceptdate = date("dMY",strtotime($rowgetclasscarddtls["ACCEPTDATE"]));
		else 
			$cardacceptdate = "";
		
		$cardacceptby = $rowgetclasscarddtls["ACCEPTBY"];
		
		if (!empty($rowgetclasscarddtls["CANCELDATE"]))
			$cardcanceldate = date("dMY",strtotime($rowgetclasscarddtls["CANCELDATE"]));
		else 
			$cardcanceldate = "";
		
		$cardcancelby = $rowgetclasscarddtls["CANCELBY"];
		
		// if(empty($cardremarks))
			$cardremarks = $rowgetclasscarddtls["REMARKS"];
		
	
		if (!empty($cardprintdate))
			$statprinted = 1;
		else 
			$statprinted = 0;
			
		if (!empty($cardacceptdate))
			$stataccepted = 1;
		else 
			$stataccepted = 0;
		
		if (!empty($cardcanceldate))
			$statcancelled = 1;
		else 
			$statcancelled = 0;

	}
	else 
	{
		$statclassempty = 0;
	}

}

// FOR CLASSCARD NO <SELECT>
if (!empty($applicantno))
{
	$qrygetclasscardnos = mysql_query("SELECT DISTINCT CLASSCARDNO 
									FROM crewtraining
									WHERE APPLICANTNO=$applicantno AND CLASSCARDNO > 0
									") or die(mysql_error());
	
	// QUERY FOR TRAININGS ENROLLED
	$qryenrolled = mysql_query("SELECT c.SCHEDID,trc.TRAINING,th.DATEFROM,th.DATETO,
								ct.COURSETYPECODE,ct.COURSETYPE
								FROM crewtraining c
								LEFT JOIN trainingschedhdr th ON th.SCHEDID=c.SCHEDID
								LEFT JOIN trainingcourses trc ON trc.TRAINCODE=th.TRAINCODE
								LEFT JOIN coursetype ct ON ct.COURSETYPECODE=trc.COURSETYPECODE
								WHERE c.CLASSCARDNO=0 AND c.APPLICANTNO=$applicantno AND th.DATEFROM >= '2007-11-01'
								ORDER BY ct.COURSETYPECODE,trc.TRAINING
							") or die(mysql_error());
	
	if (mysql_num_rows($qryenrolled) > 0)
	{
		$statenrollempty = 0;
	}
	else 
	{
		$statenrollempty = 1;
	}

}
else 
{
	$disabledcreate = "disabled=\"disabled\"";
}


if (!empty($classcardno))
{
	if ($statcancelled != 1) //NOT CANCELLED
	{
		if ($stataccepted == 0) //NOT ACCEPTED
			$printable = 1;
		else 
			$printable = 0;
	}
	else //CANCELLED
	{
		$printable = 0;
	}
	
}
else 
{
	$printable = 0;
}


if ($printable == 1)
	$disabledprint = "";
else 
	$disabledprint = "disabled=\"disabled\"";
	
if ($statcancelled == 1 || empty($classcardno))
	$disabledcancel = "disabled=\"disabled\"";
else 
	$disabledcancel = "";
	
if (!empty($classcardno))
{
	if ($statenrollempty == 0)
		$disabledallenroll = "";
	else 
		$disabledallenroll = "disabled=\"disabled\"";
		
	if ($statclassempty == 0)
		$disabledallclass = "";
	else 
		$disabledallclass = "disabled=\"disabled\"";
}
else 
{
	$disabledallenroll = "disabled=\"disabled\"";
	$disabledallclass = "disabled=\"disabled\"";
}

if ($statcancelled == 1)
	$mainstatus = "CANCELLED";
elseif ($stataccepted == 1)
	$mainstatus = "ACCEPTED";
elseif ($statprinted == 1)
	$mainstatus = "PRINTED";
else 
	$mainstatus = "";

	
if ($statprinted == 1 && $stataccepted == 0 )
	$disabledissued = "";
else 
	$disabledissued = "disabled=\"disabled\"";

echo "
<html>\n
<head>\n
<title>Training Class Card Generation</title>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>
<script type='text/javascript' src='sha1.js'></script>

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
	frm=document.classcard;
	
	if( confirm('Is the print final?') )
	{
//		alert('');

		frm.classcardno.value='$classcardno';
		frm.actiontxt.value='printclasscard';
		frm.submit();
	}
}

</script>


<body onload=\"\" style=\"overflow:hidden;background-color:White;\" 
onFocus=\"if(flag==1) {ConfirmFinal(); flag=0;} else {flag=0;}\">\n

<form name=\"classcard\" method=\"POST\">\n


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

<div id=\"issued\" style=\"position:absolute;top:250px;left:220px;height:200px;width:500px;background-color:Red;margin:0 10 0 10;
				border:2px solid black;overflow:auto;$showissued \">
	<span class=\"sectiontitle\">ISSUANCE AND RECEIVING</span>
	<br />
	
	<br />
	<center>
		<span style=\"font-size:0.9em;color:White\">
			You are about to issue <span style=\"font-weight:Bold;color:Yellow;\">Class Card No. $classcardno</span>, 
			for <b>$crewname</b>. Please confirm by entering your password below. 
		</span>
		<br /><br />
		
		<input type=\"password\" style=\"font-weight:Bold;color:Blue;text-align:center;\" name=\"password\" 
			onkeydown=\"if(event.keyCode==13) 
				{
				if(hex_sha1(this.value)=='$passwd') 
					{actiontxt.value='updateissuance';classcardno.value='$classcardno';submit();alert('Class Card Issuance completed successfully!');} 
				else 
					{alert('Password Failed! You are NOT AUTHORIZED to issue Class Card No. $classcardno. Please try again.');this.value='';this.focus();} 
				}\" />
	
		<br /><br />
		<a href=\"#\" style=\"font-size:0.8em;font-weight:Bold;background-color:Gray;\" onclick=\"document.getElementById('issued').style.display='none';\">Close Window</a>
	</center>
	<br />
	
</div>

<span class=\"wintitle\">CLASSCARD GENERATION</span>

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

		
		<div style=\"width:100%;height:170px;margin:3 5 3 5;background-color:#003559;\">
		
			<span class=\"sectiontitle\">CREW INFORMATION</span>
			
			<div style=\"width:80%;height:100px;float:left;border:1px solid Black;\">
			";
//				$stylehdr = "style=\"font-size:0.7em;font-weight:Bold;color:White;\"";
//				$styledtl = "style=\"font-size:0.7em;font-weight:Bold;color:Yellow;\"";
				
				$stylehdr = "style=\"font-size:0.75em;font-weight:Bold;color:White;\"";
				$styledtl2 = "style=\"font-size:0.9em;font-weight:Bold;color:Yellow;\"";
				
			echo "
				<div style=\"width:100%;height:70px;\">
					<table style=\"width:100%;background-color:Black;\">
						<tr>
							<td $stylehdr>NAME: <br /><span style=\"font-size:1.2em;color:Yellow;\">$crewname</span></td>
							<td $stylehdr>APPLICANT NO: <br /><span style=\"font-size:1.2em;color:Yellow;\">$applicantno</span></td>
							<td $stylehdr>CREW CODE: <br /><span style=\"font-size:1.2em;color:Yellow;\">$crewcode</span></td>
						</tr>
					</table>
					<br />
					
					<table style=\"width:48%;float:left;\">
						<tr>
							<td $stylehdr>LAST VESSEL</td>
							<td $stylehdr>:</td>
							<td $styledtl2>$lastvessel</td>
						</tr>
						<tr>
							<td $stylehdr>LAST RANK</td>
							<td $stylehdr>:</td>
							<td $styledtl2>$lastrankalias</td>
						</tr>
						<tr>
							<td $stylehdr>DISEMBARK</td>
							<td $stylehdr>:</td>
							<td $styledtl2>$lastdisembdate</td>
						</tr>
					</table>
					
					<table style=\"width:48%;float:right;\">
						<tr>
							<td $stylehdr>ASSIGNED VESSEL</td>
							<td $stylehdr>:</td>
							<td $styledtl2>$assignedvessel</td>
						</tr>
						<tr>
							<td $stylehdr>ASSIGNED RANK</td>
							<td $stylehdr>:</td>
							<td $styledtl2>$assignedrankalias</td>
						</tr>
						<tr>
							<td $stylehdr>ETD</td>
							<td $stylehdr>:</td>
							<td $styledtl2>$assignedetd</td>
						</tr>
					</table>
				</div>
				<hr />
				
				<div style=\"width:100%;height:70px;\">
				
					<div style=\"width:80%;height:100%;float:left;overflow:auto;\">
						<table width=\"100%\">
							<tr>
								<td width=\"50%\" $stylehdr>
									REMARKS:<br />
									<textarea name=\"remarks\" cols=\"40\" rows=\"3\" $disabledprint>$cardremarks</textarea> <br />
								</td>
								<td width=\"50%\" align=\"center\" valign=\"top\" $stylehdr>
									VIEW CLASSCARD:<br />
									<select name=\"viewclasscardno\" onchange=\"actiontxt.value='viewclasscard';submit();\">
										<option value=\"\">--Select One--</option>
									";
									
									$selected = "";
									while ($rowgetclasscardnos = mysql_fetch_array($qrygetclasscardnos))
									{
										$cardno = $rowgetclasscardnos["CLASSCARDNO"];
										
										if ($cardno == $classcardno)
											$selected = "SELECTED";
										else 
											$selected = "";
											
										echo "<option $selected value=\"$cardno\">$cardno</option>";
										
									}
			
									echo "
									</select>
									
									<input type=\"button\" value=\"Print\" $disabledprint 
										onclick=\"
												openWindow('repclasscard.php?applicantno=$applicantno&classcardno=$classcardno&remarks='+remarks.value, 'repclasscard$applicantno' ,840, 650);
												PrintValidation();\" />
									
									<br /><br />
									
									<input type=\"button\" name=\"btncreate\" value=\"Create\" $disabledcreate
										onclick=\"actiontxt.value='createclasscard';submit();\"/>
									
									
									<input type=\"button\" value=\"Cancel\" $disabledcancel 
										onclick=\"if (confirm('You wil not be able to Print after the cancellation. Cancel Classcard No. $classcardno?')) 
											{actiontxt.value='cancelclasscard';cancelcardno.value='$classcardno';submit();}\" />
										
									<input type=\"button\" value=\"Reset\" 
										onclick=\"applicantno.value='';classcardno.value='';submit();\" />
									
								</td>
							</tr>
						</table>
					</div>
					
					<div style=\"width:20%;height:100%;float:right;overflow:auto;\">
						<table width=\"100%\">
							<tr>
								<td style=\"font-size:1em;font-weight:Bold;color:White;background-color:Orange;\" align=\"center\"><u>$appstatus</u></td>
							</tr>
							<tr>
								<td style=\"font-size:1em;font-weight:Bold;color:Red;background-color:Silver;\" align=\"center\">
								";
									if (!empty($mainstatus))
										echo $mainstatus;
									else 
										echo "------";
								
								echo "
								</td>
							</tr>
							<tr>
								<td style=\"font-size:1.3em;font-weight:Bold;color:Yellow;background-color:Black;\" align=\"center\">
							";
								if (!empty($classcardno))
									echo $classcardno;
								else 
									echo "------";
							
							echo "
								</td>
							</tr>
						</table>
					</div>
				</div>
				
			</div>
			
			<div style=\"width:20%;float:right;color:Orange;\">
	";
				$dirfilename = $basedirid . $applicantno . ".jpg";
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
		
		<div style=\"width:100%;height:370px;background-color:#DCDCDC;margin:3 5 3 5;border:1px solid Black;overflow:auto;\">
		
<!--			<span class=\"sectiontitle\">CLASSCARD DETAILS</span>	-->
		
			<div style=\"width:100%;height:360px;overflow:auto;\">
			
				<div style=\"width:38%;height:95%;margin:15 5 0 5;background-color:White;float:left;border:1px solid black;overflow:auto;\">
					<span class=\"sectiontitle\">CURRENTLY ENROLLED TRAININGS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"button\" value=\"All >>\" style=\"font-size:0.8em;font-weight:Bold;color:Red;\" $disabledprint $disabledallenroll
									onclick=\"if ('$classcardno' != '') {actiontxt.value='updateclasscardall';
												classcardno.value='$classcardno';selschedid.value='$enrollschedid';submit();}
												else {alert ('Create Class Card No. first.');}\" />
					</span>
					
					<table width=\"100%\" class=\"listcol\">
						<tr>
							<th width=\"15%\">SCHEDID</th>
							<th width=\"45%\">TRAINING</th>
							<th width=\"20%\">FROM</th>
							<th width=\"20%\">TO</th>
						</tr>
					";
	
					$styledtl2 = "style=\"color:Black;border-bottom:1px dashed gray;cursor:pointer;\"";
	
					$coursetypecodetmp = "";

					while ($rowenrolled = mysql_fetch_array($qryenrolled))
					{
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
							
						$enrollcoursetypecode = $rowenrolled["COURSETYPECODE"];
						$enrollcoursetype = $rowenrolled["COURSETYPE"];
						
						if ($coursetypecodetmp != $enrollcoursetypecode)
						{
							echo "
							<tr>
								<td colspan=\"4\" style=\"font-size:1.2em;font-weight:Bold;background-color:Navy;color:Yellow;\">$enrollcoursetype</td>
							</tr>
							";
						}
						
						echo "
						<tr onclick=\"if ('$classcardno' != '' && '$stataccepted' != '1') {actiontxt.value='updateclasscard';classcardno.value='$classcardno';selschedid.value='$enrollschedid';submit();}
									else {alert ('No Class Card No. OR Class Card already accepted by Crew. Please CREATE new one.');}\" $mouseovereffect>
							<td align=\"center\" $styledtl2>$enrollschedid</td>
							<td $styledtl2>$enrolltraining</td>
							<td align=\"center\" $styledtl2>$enrollfrom</td>
							<td align=\"center\" $styledtl2>$enrollto</td>
						</tr>
						";
						
						$coursetypecodetmp = $enrollcoursetypecode;
					}
					
					echo "
					</table>
					
				</div>
				
				<div style=\"width:58%;height:85%;margin:15 5 0 5;background-color:White;float:right;border:1px solid black;\">
					<span class=\"sectiontitle\">
						<input type=\"button\" value=\"<< All\" style=\"font-size:0.8em;font-weight:Bold;color:Red;\" $disabledallclass $disabledprint
									onclick=\"if ('$classcardno' != '' && '$stataccepted' != '1') {actiontxt.value='removeclasscardall';
												classcardno.value='$classcardno';selschedid.value='$enrollschedid';submit();}
												else {alert ('Create Class Card No. first.');}\" />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						CLASSCARD DETAILS
					</span>
					
					<table width=\"100%\" class=\"listcol\">
						<tr>
							<th width=\"15%\">DATE</th>
							<th width=\"15%\">START</th>
							<th width=\"15%\">END</th>
							<th width=\"35%\">VENUE/CENTER</th>
							<th width=\"30%\">REMARKS</th>
						</tr>
					";
	
					$styledtl2 = "style=\"color:Black;border-bottom:1px dashed gray;cursor:pointer;\"";
	
					$coursetypecodetmp = "";
					$schedidtmp = "";
					$traincnt = 1;
					
					while ($rowclasscard = mysql_fetch_array($qryclasscard))
					{
						$classschedid = $rowclasscard["SCHEDID"];
						
						if (!empty($rowclasscard["DATEFROM"]))
							$classdatefrom = date("dMY",strtotime($rowclasscard["DATEFROM"]));
						else 
							$classdatefrom = "";
							
						if (!empty($rowclasscard["DATETO"]))
							$classdateto = date("dMY",strtotime($rowclasscard["DATETO"]));
						else 
							$classdateto = "";
							
						$classremarks = $rowclasscard["REMARKS"];
						$classstatus = $rowclasscard["STATUS"];
						$classtraincode = $rowclasscard["TRAINCODE"];
						$classtraining = $rowclasscard["TRAINING"];
						$classtrainvenuecode = $rowclasscard["TRAINVENUECODE"];
						$classtrainvenue = $rowclasscard["TRAINVENUE"];
						$classtraincentercode = $rowclasscard["TRAINCENTERCODE"];
						$classtraincenter = $rowclasscard["TRAINCENTER"];
						$finalizeddate = $rowclasscard["FINALIZEDDATE"];
						
						if (!empty($finalizeddate))
							$disabledfinal = "disabled=\"disabled\"";
						
						if (!empty($rowclasscard["SCHEDDATE"]))
							$classscheddate = date("dMY",strtotime($rowclasscard["SCHEDDATE"]));
						else 
							$classscheddate = "";
						
						$classinstructor = $rowclasscard["INSTRUCTOR"];
						
						if (!empty($rowclasscard["TIMESTART"]))
							$classtimestart =  date('H:i',strtotime($rowclasscard["TIMESTART"]));
						else 
							$classtimestart =  "";
							
						if (!empty($rowclasscard["TIMEEND"]))
							$classtimeend =  date('H:i',strtotime($rowclasscard["TIMEEND"]));
						else 
							$classtimeend =  "";
							
						$classcoursetypecode = $rowclasscard["COURSETYPECODE"];
						$classcoursetype = $rowclasscard["COURSETYPE"];
						
						if (strtotime($classdatefrom) != strtotime($classdateto))
							$classdatefromto = $classdatefrom . " to " . $classdateto;
						else 
							$classdatefromto = $classdatefrom;
						
						
						if ($coursetypecodetmp != $classcoursetypecode)  //GROUPING FOR INHOUSE | PRINCIPAL | OUTSIDE
						{
							echo "
							<tr>
								<td colspan=\"6\" style=\"font-size:1.2em;font-weight:Bold;background-color:Navy;color:Yellow;text-align:center;\">$classcoursetype</td>
							</tr>
							";
						}
						
						if ($schedidtmp != $classschedid)
						{
							echo "
							<tr>
								<td colspan=\"4\" style=\"font-size:1em;font-weight:Bold;background-color:Black;color:White;\">
									$traincnt.&nbsp; <span style=\"font-size:1.2em;font-weight:Bold;color:Lime;\">$classtraining</span> &nbsp;&nbsp;
									Sched ID: $classschedid &nbsp;(&nbsp;$classdatefromto&nbsp;)
								</td>
								<td align=\"right\" valign=\"top\" style=\"background-color:Black;\">
									<input type=\"button\" name=\"btnremove\" value=\"Remove\" style=\"font-size:0.8em;font-weight:Bold;color:Red;\"
										title=\"Remove from the list.\" $disabledprint $disabledallclass $disabledfinal
										onclick=\"if ('$stataccepted' == '1') {alert('Cannot remove. Class Card already accepted by Crew.');}
												else {
												if(confirm('Are you sure you want to remove Schedule ID: $classschedid ?')) 
												{actiontxt.value='removeclasscardentry';delschedid.value='$classschedid';
												classcardno.value='$classcardno';submit();}
												}\" />
								</td>
							</tr>
							";
							
							$traincnt++;
						}
						
						echo "
							<tr>
								<td align=\"center\">$classscheddate</td>
								<td align=\"center\">$classtimestart</td>
								<td align=\"center\">$classtimeend</td>
								<td align=\"left\">&nbsp;$classtrainvenue &nbsp;$classtraincenter</td>
								<td align=\"left\">&nbsp;$classremarks</td>
							</tr>
						
						";
						
						
						$schedidtmp = $classschedid;
						$coursetypecodetmp = $classcoursetypecode;
						
					}
					
					echo "
					</table>

				</div>
				
				<div style=\"width:58%;height:9%;margin:2 5 0 5;border:1px solid black;float:right;background-color:White;\">
				";
					
				echo "<input type=\"button\" name=\"btnaccept\" $disabledissued $disabledcreate $disabledfinal value=\"Issue Class Card No. $classcardno. \" 
					onclick=\"actiontxt.value='showissuance';classcardno.value='$classcardno';submit();\" />
				&nbsp;&nbsp;&nbsp;
				
				";
				
				if (!empty($cardacceptdate))
				{
					echo "<span style=\"font-size:0.7em;font-weight:Bold;color:Blue;\">ISSUED and RECEIVED on <u>$cardacceptdate</u>.</span>";
				}
					
				echo "
				</div>
				
				
			</div>

		</div>
		

	</div>
	
	
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"applicantno\" value=\"$applicantno\" />
	<input type=\"hidden\" name=\"classcardno\" />
	<input type=\"hidden\" name=\"selschedid\" />
	<input type=\"hidden\" name=\"delschedid\" />
	<input type=\"hidden\" name=\"cancelcardno\" />

	
</form>

</body>
";
?>