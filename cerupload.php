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
	
	case "viewcer"	:
		
			
		
		break;

}


if (!empty($applicantno))
{
	$qryallexperience = mysql_query("SELECT IF (cpr.CCIDPROMOTE IS NULL,0,1) AS PROMOTED,x.* FROM
									(
										SELECT '1' AS POS,cc.CCID,CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,CREWCODE,
										LEFT(v.ALIAS2,10) AS VESSEL,v.VESSELCODE,r.ALIAS1 AS RANKALIAS,r.RANKCODE,
										cc.DATEEMB,IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,cc.DISEMBREASONCODE,dr.REASON,
										cc.ARRMNLDATE,cc.DEPMNLDATE
										FROM crewchange cc
										LEFT JOIN crew c ON c.APPLICANTNO=cc.APPLICANTNO
										LEFT JOIN vessel v ON v.VESSELCODE=cc.VESSELCODE
										LEFT JOIN rank r ON r.RANKCODE=cc.RANKCODE
										LEFT JOIN disembarkreason dr ON dr.DISEMBREASONCODE=cc.DISEMBREASONCODE
										WHERE cc.APPLICANTNO=$applicantno
									) x
									LEFT JOIN crewpromotionrelation cpr ON cpr.CCIDPROMOTE=x.CCID
									LEFT JOIN crewevalhdr ce ON ce.CCID=x.CCID
									GROUP BY x.CCID
									ORDER BY x.DATEDISEMB DESC
								") or die(mysql_error());

	$cnt = 0;
	$content = "";
	$appstatus = "";
	
	while ($rowallexperience = mysql_fetch_array($qryallexperience))
	{
		if ($rowallexperience["POS"] == "1")
			$zexptype = "Veritas";
		else 
			$zexptype = "Outside VMC";
			
		$crewname = $rowallexperience["NAME"];
		$crewcode = $rowallexperience["CREWCODE"];
			
		$zccid = $rowallexperience["CCID"];
		$zcrewcode = $rowallexperience["CREWCODE"];
		$zvessel = $rowallexperience["VESSEL"];
		$zvesselcode = $rowallexperience["VESSELCODE"];
		$zrankalias = $rowallexperience["RANKALIAS"];
		$zrankcode = $rowallexperience["RANKCODE"];
		$zpromoted = $rowallexperience["PROMOTED"];
		
		if (!empty($rowallexperience["DATEEMB"]))
			$zdateemb = date("dMY",strtotime($rowallexperience["DATEEMB"]));
		else 
			$zdateemb = "";
			
		if (!empty($rowallexperience["DATEDISEMB"]))
			$zdatedisemb = date("dMY",strtotime($rowallexperience["DATEDISEMB"]));
		else 
			$zdatedisemb = "";
			
		if (!empty($rowallexperience["ARRMNLDATE"]))
			$zarrmnldate = date("dMY",strtotime($rowallexperience["ARRMNLDATE"]));
		else 
			$zarrmnldate = "";
			
		if (!empty($rowallexperience["DEPMNLDATE"]))
			$zdepmnldate = date("dMY",strtotime($rowallexperience["DEPMNLDATE"]));
		else 
			$zdepmnldate = "";
			
		$zdisembreasoncode = $rowallexperience["DISEMBREASONCODE"];
		$zreason = $rowallexperience["REASON"];
		
		$content .= "
		<tr title=\"CCID: $zccid\">
			<td>$zexptype</td>
			<td>$zvessel</td>
			<td align=\"center\">$zrankalias</td>
			<td align=\"center\">&nbsp;$zdateemb</td>
			<td align=\"center\">&nbsp;$zdepmnldate</td>
			<td align=\"center\">&nbsp;$zdatedisemb</td>
			<td align=\"center\">&nbsp;$zarrmnldate</td>
			<td align=\"center\">&nbsp;$zreason</td>
			<td><a href=\"#\" onclick=\"ccidhidden.value='$zccid';document.getElementById('content').src='crewevaluation.php?ccid=$zccid';\"
					style=\"font-size:0.9em;font-weight:Bold;color:Blue;\" >
					[View CER]
				</a>
				
			
			</td>
		</tr>
		";
		
		$cnt++;
	}

}




echo "
<html>\n
<head>\n
<title>Crew Evaluation Report Uploading</title>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>
</head>

<body onload=\"\" style=\"overflow:hidden;background-color:White;\">\n

<form name=\"cerupload\" method=\"POST\">\n


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
							
							
<span class=\"wintitle\">CREW EVALUATION REPORT UPLOAD</span>

	<div style=\"width:100%;height:660px;background-color:Silver;\">

		<center>
		<table style=\"width:70%;margin-top:5px;\" border=1>
			<tr>
				<td align=\"left\" style=\"font-size:0.8em;font-weight:Bold;\">Search</td>
				<td>
					<select name=\"searchby\" onchange=\"if(this.value != ''){searchkey.focus();}\">
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
						<option $selected1 value=\"1\">APPLICANT NO</option>
						<option $selected2 value=\"2\">CREW CODE</option>
						<option $selected4 value=\"4\">GIVEN NAME</option>
					</select>
				</td>
				<td align=\"center\"><input type=\"text\" name=\"searchkey\" size=\"20\" value=\"$searchkey\" 
						onkeyup=\"this.value=this.value.toUpperCase();if (this.value != '') {btnfind.disabled=false;}\"
						style=\"color:Red;font-weight:Bold;\" onkeydown=\"if(event.keyCode==13) {btnfind.focus();}\"/>
						
					<input type=\"button\" name=\"btnfind\" value=\"Find\" $disablesearch onclick=\"actiontxt.value='find';submit();\" />
				</td>
			</tr>
		</table>
		</center>
		<br />
		
		<div style=\"width:100%;height:150px;margin:3 5 3 5;background-color:#1A507E;\">
			<span class=\"sectiontitle\">CREW EXPERIENCE</span>
			
			<div style=\"width:100%;height:40px;background-color:Black;\">
			";
				$stylehdr = "style=\"font-size:0.7em;font-weight:Bold;color:White;\"";
				$styledtl = "style=\"font-size:0.9em;font-weight:Bold;color:Orange;\"";
				
			echo "
				<table style=\"width:100%;\">
					<tr>
						<td $stylehdr>CREW NAME</td>
						<td $stylehdr>CREW CODE</td>
						<td $stylehdr>APPLICANT NO</td>
					</tr>
					<tr>
						<td $styledtl>$crewname</td>
						<td $styledtl>$crewcode</td>
						<td $styledtl>$applicantno</td>
					</tr>
				</table>
			
			</div>
			
			<div style=\"width:100%;height:110px;overflow:auto;\">
				<table class=\"listcol\" >
					<tr>
						<th>TYPE</th>
						<th>VESSEL</th>
						<th>RANK</th>
						<th>EMBARK</th>
						<th>DEPART MNL</th>
						<th>DISEMBARK</th>
						<th>ARRIVE MNL</th>
						<th>REASON</th>
						<th>STATUS</th>
					</tr>
					
					 $content
 
				</table>
			</div>
		</div>
		
		<div style=\"width:100%;height:440px;margin:3 5 3 5;background-color:White;overflow:auto;\">
		
			<iframe marginwidth=20 marginheight=20 id=\"content\" frameborder=\"0\" name=\"content\" src=\"$cerfile\" scrolling=\"yes\" 
				style=\"width:100%;height:100%;float:left;\">
			</iframe>
		
		</div>
		
	</div>

	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"applicantno\" />
	<input type=\"hidden\" name=\"ccidhidden\" />
	
</form>

</body>

</html>
";


?>