<?php
include('veritas/connectdb.php');
include("veritas/include/stylephp.inc");
include("veritas/include/functions.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');
$basedirid = "idpics/";
$basedirdocs = "docimg/";
// $basedirdocs = "docimages/";

$datenow = date("Y-m-d");

if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
else 
	$employeeid = "";

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];

if (isset($_POST['applicantno']))
	$applicantno = $_POST['applicantno'];
	
if (isset($_POST['xtrainidno']))
	$xtrainidno = $_POST['xtrainidno'];
	
if(isset($_POST['finalized']))
{
	$finalized=1;
	$chkfinalized = "checked=\"checked\"";
}
else 
	$finalized=0;
	
if (isset($_POST["searchkey"]))
	$searchkey = $_POST["searchkey"];
	
if (isset($_POST["searchby"]))
	$searchby = $_POST["searchby"];
	
if ($searchkey == "")
	$disablesearch = "disabled=\"disabled\"";
else 
	$disablesearch = "";

	
$disablechecklist = 0;
$showmultiple = "display:none;";
$multiple = 0;

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
	
		if (mysql_num_rows($qrysearch) == 1)
		{
			$rowsearch = mysql_fetch_array($qrysearch);
			$applicantno = $rowsearch["APPLICANTNO"];
		}
		elseif (mysql_num_rows($qrysearch) > 1)
		{
			$showmultiple = "display:block;";
			$multiple = 1;
		}
		
	break;
		
	case "finalize":
		
			$qryfinalize = mysql_query("UPDATE crewtraining SET FINALIZEDBY='$employeeid', FINALIZEDDATE='$currentdate'
											WHERE IDNO=$xtrainidno") or die(mysql_error());
		
		break;
	
	case "unfinalize":
		
			$qryfinalize = mysql_query("UPDATE crewtraining SET FINALIZEDBY=NULL, FINALIZEDDATE=NULL
											WHERE IDNO=$xtrainidno") or die(mysql_error());
		
		break;
	
}

	
if (!empty($applicantno))
{
	// FOR MONITOR LISTING
	if ($finalized == 0)
		$wherefinal = "AND ct.FINALIZEDDATE IS NULL";
	else 
		$wherefinal = "";
	
	
	$qrytrainings = mysql_query("SELECT ct.IDNO,ct.SCHEDID,ct.CLASSCARDNO,ct.ENDORSEID,ct.RANKCODE,ct.VESSELCODE,ct.GRADE,ct.REMARKS,
									IF (ct.STATUS IS NULL,'NOT YET POSTED', IF (ct.STATUS=1,'COMPLETED','INCOMPLETE')) AS STATUS,
									ct.POSTBY,ct.POSTDATE,ct.CERTGENBY,ct.CERTGENDATE,ct.CERTIDNO,ct.FINALIZEDBY,ct.FINALIZEDDATE,
									ct.MADEBY AS ENROLLEDBY,ct.MADEDATE AS ENROLLEDDATE,
									th.SCHEDID,th.TRAINCODE,th.DATEFROM,th.DATETO,IF (th.STATUS=0,'CANCELLED','') AS TRAINSTATUS,
									IF (th.DATEFROM > CURRENT_DATE,'ENROLLED',
									    IF (th.DATETO < CURRENT_DATE,
									        'FINISHED',
									        'ON-GOING'
									        )
									) AS STATUS2,
									tc.TRAINING,tc.COURSETYPECODE,tc.DOCCODE,
									c.COURSETYPE,
									ccs.DOCNO,ccs.DATEISSUED,ccs.MADEBY AS DOCMADEBY,ccs.MADEDATE AS DOCMADEDATE,
									cch.ACCEPTDATE,cch.ACCEPTBY,cch.CANCELDATE,cch.CANCELBY,cch.MADEDATE,cch.MADEBY,
									eh.PRINTDATE,eh.PRINTBY,eh.MADEBY AS CREATEDBY,eh.MADEDATE AS CREATEDDATE,
									IF (th.DATEFROM > CURRENT_DATE,'ENROLLED',
									    IF (th.DATETO < CURRENT_DATE,
									        'FINISHED',
									        'ON-GOING'
									        )
									) AS STATUS2
								FROM crewtraining ct
								LEFT JOIN trainingschedhdr th ON th.SCHEDID=ct.SCHEDID
								LEFT JOIN trainingcourses tc ON tc.TRAINCODE=th.TRAINCODE
								LEFT JOIN coursetype c ON c.COURSETYPECODE=tc.COURSETYPECODE
								LEFT JOIN crewcertstatus ccs ON ccs.IDNO=ct.CERTIDNO
								LEFT JOIN classcardhdr cch ON cch.CLASSCARDNO=ct.CLASSCARDNO
								LEFT JOIN endorsementhdr eh ON eh.ENDORSEID=ct.ENDORSEID
								WHERE ct.APPLICANTNO=$applicantno
								$wherefinal
								ORDER BY tc.COURSETYPECODE,tc.TRAINING
					") or die(mysql_error());
	
}

include("include/crewsummary.inc");

echo "
<html>\n
<head>\n
<title>Training Monitor</title>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>

<body onload=\"\" style=\"overflow:hidden;background-color:White;\">\n

<form name=\"formtrainmonitor\" method=\"POST\">\n


<div id=\"multiple\" style=\"position:absolute;top:200px;left:200px;height:184px;width:600px;height:400px;background-color:#6699FF;
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




<span class=\"wintitle\">TRAINING MONITOR</span>

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
		
		<div style=\"width:100%;height:170px;margin:3 5 3 5;background-color:#003559;\">
		
			<span class=\"sectiontitle\">CREW INFORMATION</span>
			
			<div style=\"width:79%;height:100px;float:left;border:1px solid Black;\">
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
			
				<div style=\"width:100%;\">
					<div style=\"width:79%;float:left;overflow:auto;\">
						<table class=\"listcol\" >
							<tr>
								<th>VESSEL</th>
								<th>RANK</th>
								<th>EMBARK</th>
								<th>DISEMBARK</th>
								<th>REASON</th>
							</tr>
							
							 $content
		 
						</table>
					</div>
					
					<div style=\"width:18%;float:right;\">
						<table width=\"100%\">
							<tr>
								<td style=\"font-size:1em;font-weight:Bold;color:White;background-color:Orange\" align=\"center\"><u>$appstatus</u></td>
							</tr>
							<tr>
								<td style=\"font-size:0.8em;font-weight:Bold;color:Lime;\">
									<input type=\"checkbox\" name=\"finalized\" $chkfinalized onclick=\"submit();\" />Show Finalized
								</td>
							</tr>
						</table>
					</div>
	
				</div>
			
			</div>
				
			
			<div style=\"width:20%;float:right;color:Orange;\">
	";
				$dirfilename = $basedirid . $applicantno . ".JPG";
				if (checkpath($dirfilename))
				{
					$scale = imageScale($dirfilename,-1,130);
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
			
			
		<div style=\"width:100%;height:380px;background-color:#DCDCDC;margin:3 5 3 5;border:1px solid Black;overflow:auto;\">
			<span class=\"sectiontitle\">TRAINING STATUS MONITOR</span>
			
			<table width=\"100%\" cellpadding=\"5\" cellspacing=\"1\" style=\"font-size:0.8em;background-color:#F0F1E9;border:1px solid black;\">
				<tr style=\"font-weight:bold;background-color:Black;color:White;\">
					<td width=\"15%\" align=\"center\">COURSE</td>
					<td width=\"14%\" align=\"center\">TRAIN DATE</td>
					<td width=\"14%\" align=\"center\">ENROLL</td>
					<td width=\"14%\" align=\"center\">CLASSCARD</td>
					<td width=\"12%\" align=\"center\">ENDORSE</td>
					<td width=\"14%\" align=\"center\">GRADING</td>
					<td width=\"14%\" align=\"center\">CERTIFICATE</td>
					<td width=\"14%\" align=\"center\">ISSUED</td>
					<td width=\"10%\" align=\"center\">ACTION</td>
				</tr>
			
";
			$coursetypecodetmp = "";
			$trainingcodetmp = "";
			
			$format1 = "dMY";
			$format2 = "Md";
			
			
	
			while ($rowtrainings = mysql_fetch_array($qrytrainings))
			{
				$trainidno = $rowtrainings["IDNO"];
				$schedid = $rowtrainings["SCHEDID"];
				$rankcode = $rowtrainings["RANKCODE"];
				$vesselcode = $rowtrainings["VESSELCODE"];
				
				$grade = $rowtrainings["GRADE"];
				$postby = $rowtrainings["POSTBY"];
				
				if (!empty($rowtrainings["POSTDATE"]))
					$postdate = date($format1,strtotime($rowtrainings["POSTDATE"]));
				else 
					$postdate = "";
					
				$gradeshow = "<b>By : </b>" . $postby . "<br />";
				$gradeshow .= "<b>Date : </b>" . $postdate . "<br />";
				$gradeshow .= "<b>Grade : </b>" . $grade . "<br />";
					
				//CERTIFICATE GENERATION INFO
				
				$certgenby = $rowtrainings["CERTGENBY"];
				$certidno = $rowtrainings["CERTIDNO"];
				
				$docno = $rowtrainings["DOCNO"];
				$docmadeby = $rowtrainings["DOCMADEBY"];
				
				if (!empty($rowtrainings["DOCMADEDATE"]))
					$docmadedate = date($format1,strtotime($rowtrainings["DOCMADEDATE"]));
				else 
					$docmadedate = "";
				
				if (!empty($rowtrainings["DATEISSUED"]))
					$dateissued = date($format1,strtotime($rowtrainings["DATEISSUED"]));
				else 
					$dateissued = "";
				
				if (!empty($rowtrainings["CERTGENDATE"]))
					$certgendate = date($format1,strtotime($rowtrainings["CERTGENDATE"]));
				else 
					$certgendate = "";

				$certshow = "<b>MadeBy : </b>" . $docmadeby . "<br />";
				$certshow .= "<b>Date : </b>" . $docmadedate . "<br />";
				$certshow .= "<b>PrintBy : </b>" . $certgenby . "<br />";
				$certshow .= "<b>Date : </b>" . $certgendate . "<br />";
				$certshow .= "<b>Cert. No. : </b>" . $docno . "<br />";
				$certshow .= "<b>Issued : </b>" . $dateissued . "<br />";
				
				//END OF CERTIFICATE GENERATION INFO
				
				$enrolledby = $rowtrainings["ENROLLEDBY"];
				if (!empty($rowtrainings["ENROLLEDDATE"]))
					$enrolleddate = date($format1,strtotime($rowtrainings["ENROLLEDDATE"]));
				else 
					$enrolleddate = "";
					
				$enrolledshow = "<b>By : </b>" . $enrolledby . "<br />";
				$enrolledshow .= "<b>Date : </b>" . $enrolleddate . "<br />";

				//TRAINING INFO	
				
					$traincode = $rowtrainings["TRAINCODE"];
					
					$datefrom = date("dMY",strtotime($rowtrainings["DATEFROM"]));
					$dateto = date("dMY",strtotime($rowtrainings["DATETO"]));
					
					if (strtotime($datefrom) == strtotime($dateto))
						$traindateshow = $datefrom;
					else 
						$traindateshow = $datefrom . " to " . $dateto;
					
					$training = $rowtrainings["TRAINING"];
					$coursetypecode = $rowtrainings["COURSETYPECODE"];
					$doccode = $rowtrainings["DOCCODE"];
					$coursetype = $rowtrainings["COURSETYPE"];
					$status = $rowtrainings["STATUS"];  //COMPLETED OR INCOMPLETE
					$trainstatus = $rowtrainings["TRAINSTATUS"];  //IF TRAINING IS CANCELLED!
					$status2 = $rowtrainings["STATUS2"];  //FINISHED; ON-GOING; ENROLLED
					
					$trainshow = "<b>Date : </b><br />" . $traindateshow . "<br />";
					
					if (empty($trainstatus))
						$trainshow .= "<b>Status : </b>" . $status2 . " / " . $status . "<br />";
					else 
						$trainshow .= "<b>Status : </b>" . $trainstatus . "<br />";
					
				
				//END OF TRAINING INFO
				
				//CLASSCARD INFO
				
					$classcardno = $rowtrainings["CLASSCARDNO"];
					
					$classmadeby = $rowtrainings["MADEBY"];
					
					if (!empty($rowtrainings["MADEDATE"]))
						$classmadedate = date($format1,strtotime($rowtrainings["MADEDATE"]));
					else 
						$classmadedate = "";
						
					if ($classmadedate != "")
						$classmadeshow = $classmadedate . " / " . $classmadeby;
					else 
						$classmadeshow = $classmadedate;
						
					$cancelby = $rowtrainings["CANCELBY"];
					
					if (!empty($rowtrainings["CANCELDATE"]))
						$canceldate = date($format1,strtotime($rowtrainings["CANCELDATE"]));
					else 
						$canceldate = "";
						
					if ($canceldate != "")
						$cancelshow = $canceldate . " / " . $cancelby;
					else 
						$cancelshow = $canceldate;
						
					
					$acceptby = $rowtrainings["ACCEPTBY"];
					
					if (!empty($rowtrainings["ACCEPTDATE"]))
						$acceptdate = date($format1,strtotime($rowtrainings["ACCEPTDATE"]));
					else 
						$acceptdate = "";
						
					if ($acceptdate != "")
						$acceptshow = $acceptdate . " / " . $acceptby;
					else 
						$acceptshow = $acceptdate;
						
						
					$classcardshow = "<b>CCNo : </b>" . $classcardno . "<br />";
					$classcardshow .= "<b>Made : </b><br />" . $classmadeshow . "<br />";
					$classcardshow .= "<b>Accepted : </b><br />" . $acceptshow . "<br />";
					$classcardshow .= "<b>Cancelled : </b><br />" . $cancelshow . "<br />";
				
				//END OF CLASSCARD INFO
					
				//ENDORSEMENT INFO			
					
					$endorseid = $rowtrainings["ENDORSEID"];
				
					$printby = $rowtrainings["PRINTBY"];
					$createdby = $rowtrainings["CREATEDBY"];
					
//					if (!empty($rowtrainings["ENDORSEDDATE"]))
//						$endorseddate = date($format1,strtotime($rowtrainings["ENDORSEDDATE"]));
//					else 
//						$endorseddate = "";
					
					if (!empty($rowtrainings["CREATEDDATE"]))
						$createddate = date($format1,strtotime($rowtrainings["CREATEDDATE"]));
					else 
						$createddate = "";
					
					if (!empty($rowtrainings["PRINTDATE"]))
						$printdate = date($format1,strtotime($rowtrainings["PRINTDATE"]));
					else 
					{
						if ($coursetypecode == "INHSE")
							$printdate = "N/A";
						else 
							$printdate = "";
					}
					
					$endorsedshow = "<b>Endorsed by : </b>" . $createdby . "<br />";
					$endorsedshow .= "<b>Date : </b>" . $createddate . "<br /><br />";
					$endorsedshow .= "<b>ID : </b>" . $endorsedid . "<br />";
					$endorsedshow .= "<b>Print By : </b>" . $printby . "<br />";
					$endorsedshow .= "<b>Print Date : </b>" . $printdate . "<br />";
					
				//END OF ENDORSEMENT INFO
				
				$status2 = $rowtrainings["STATUS2"];
				
				//CHECK IF DOCUMENT IS READY FOR VIEWING...
				
				$dirfilename1 = $basedirdocs . $applicantno . "/C/" . $doccode . ".pdf";
				
				if (checkpath($dirfilename1))
				{
					$docshow = "<a href=\"#\" style=\"font-weight:Bold;color:Navy;\"
								onclick=\"openWindow('$dirfilename1', '$doccode' ,700, 500);\" title=\"Click to View.\">
								[$dateissued]</a>";
				}
				else 
					$docshow = "&nbsp;";
				
					
				$finalizedby = $rowtrainings["FINALIZEDBY"];
				
				if (!empty($rowtrainings["FINALIZEDDATE"]))
					$finalizeddate = date($format1,strtotime($rowtrainings["FINALIZEDDATE"]));
				else 
					$finalizeddate = "";
					
				if (!empty($finalizeddate))
				{
					$finalizedshow = "<b>FINALIZED</b> <br />";
					$finalizedshow .= "<b>By : </b>" . $finalizedby . "<br />";
					$finalizedshow .= "<b>Date : </b>" . $finalizeddate . "<br />";
					$finalizedshow .= "<input type=\"button\" value=\"Undo\"
									onclick=\"if (confirm('Undo Finalization of this Training. Continue?')) 
									{xtrainidno.value='$trainidno';actiontxt.value='unfinalize';
									submit();}\"  />" . "<br />";
				}
				else 
					$finalizedshow = "<input type=\"button\" value=\"Finalize!\" 
							onclick=\"if (confirm('Finalize Training. Continue?')) {xtrainidno.value='$trainidno';actiontxt.value='finalize';submit();}\" />";
					
				
				if ($coursetypecode != $coursetypecodetmp)
				{
					$stylegroup = "style=\"background-color:Navy;color:Orange;font-weight:Bold;font-size:1.2em;text-align:center;\"";
					
					echo "<tr><td colspan=\"9\" $stylegroup>$coursetype</td></tr>";
					
				}
				
				$styledtl = " style=\"font-size:1em;color:Black;border-bottom:1px dashed gray;border-left:1px solid Gray;\"";
				
				echo "
				<tr $mouseovereffect>
					<td $styledtl align=\"left\" valign=\"top\"><b>$training</b></td>
					<td $styledtl align=\"left\" valign=\"top\">$trainshow</td>
					<td $styledtl align=\"left\" valign=\"top\">$enrolledshow</td>
					<td $styledtl align=\"left\" valign=\"top\">$classcardshow</td>
					<td $styledtl align=\"left\" valign=\"top\">$endorsedshow</td>
					<td $styledtl align=\"left\" valign=\"top\">$gradeshow</td>
					<td $styledtl align=\"left\" valign=\"top\">$certshow</td>
					<td $styledtl align=\"left\" valign=\"top\">$docshow</td>
					<td $styledtl align=\"left\" valign=\"top\">$finalizedshow</td>
				</tr>
				
				";
				
				
				
				
//				$qrytrainingdtls = mysql_query("SELECT td.SCHEDDATE,td.INSTRUCTOR,td.TRAINCENTERCODE,td.TRAINVENUECODE,td.TIMESTART,td.TIMEEND,td.REMARKS,
//												trc.TRAINCENTER,trv.TRAINVENUE
//												FROM trainingscheddtl td
//												LEFT JOIN trainingcenter trc ON trc.TRAINCENTERCODE=td.TRAINCENTERCODE
//												LEFT JOIN trainingvenue trv ON trv.TRAINVENUECODE=td.TRAINVENUECODE
//												WHERE SCHEDID=$schedid
//												ORDER BY SCHEDDATE
//								") or die(mysql_error());
//				
//				while ($rowtrainingdtls = mysql_fetch_array($qrytrainingdtls))
//				{
//					$scheddate = $rowtrainingdtls["SCHEDDATE"];
//					$instructor = $rowtrainingdtls["INSTRUCTOR"];
//					$traincentercode = $rowtrainingdtls["TRAINCENTERCODE"];
//					$trainvenuecode = $rowtrainingdtls["TRAINVENUECODE"];
//					$timestart = $rowtrainingdtls["TIMESTART"];
//					$timeend = $rowtrainingdtls["TIMEEND"];
//					$dtlremarks = $rowtrainingdtls["REMARKS"];
//					$traincenter = $rowtrainingdtls["TRAINCENTER"];
//					$trainvenue = $rowtrainingdtls["TRAINVENUE"];
//					
//					
//					
//				}
				
				
				
				
				$coursetypecodetmp = $coursetypecode;
				$trainingcodetmp = $trainingcode;
				
			}
	
			
echo "
			</table>
		</div>
		

	</div>
	
	
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"applicantno\" value=\"$applicantno\" />
	<input type=\"hidden\" name=\"xtrainidno\" />
	
</form>

</body>
";
?>