<?php

include("veritas/connectdb.php");	
include('veritas/include/stylephp.inc');

session_start();

$currentdate = date("Y-m-d H:i:s");

if (isset($_POST["actiontxt"]))
	$actiontxt = $_POST["actiontxt"];
	
if (isset($_SESSION["employeeid"]))
	$employeeid = $_SESSION["employeeid"];

$showmultiple = "display:none;";
	
//POSTS

if(isset($_POST["applicantno"]))
	$applicantno=$_POST["applicantno"];	
	
if (isset($_POST["searchkey"]))
	$searchkey = $_POST["searchkey"];
	
if (isset($_POST["searchby"]))
	$searchby = $_POST["searchby"];
	
if ($searchkey == "")
	$disablesearch = "disabled=\"disabled\"";
else 
	$disablesearch = "";

if (isset($_POST["doccode"]))
	$doccode = $_POST["doccode"];

if (isset($_POST["xdocrankcode"]))
	$xdocrankcode = $_POST["xdocrankcode"];

if (isset($_POST["xdocno"]))
	$xdocno = $_POST["xdocno"];

if (isset($_POST["xdocissued"]))
	$xdocissued = $_POST["xdocissued"];

if (isset($_POST["xdocexpiry"]))
	$xdocexpiry = $_POST["xdocexpiry"];

if (isset($_POST["xremarks"]))
	$xremarks = $_POST["xremarks"];

if (isset($_POST["crewdocid"]))
	$crewdocid = $_POST["crewdocid"];

if (isset($_POST["newentry"]))
	$newentry = $_POST["newentry"];
else
	$newentry = 0;

switch($actiontxt)
{
	case "find"	: //SEARCH KEY
			
			$appno = "";
			
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
		
		case "update":
			
			if (!empty($xdocissued))
				$xdocissuedraw = "'" . date("Y-m-d",strtotime($xdocissued)) . "'";
			else
				$xdocissuedraw = "NULL";
				
			if (!empty($xdocexpiry))
				$xdocexpiryraw = "'" . date("Y-m-d",strtotime($xdocexpiry)) . "'";
			else
				$xdocexpiryraw = "NULL";
			
			
			$qryupdate = mysql_query("UPDATE crewdocstatus SET DOCNO='$xdocno',
													DATEISSUED = $xdocissuedraw,
													DATEEXPIRED = $xdocexpiryraw,
													REMARKS = '$xremarks',
													MADEBY = '$employeeid',
													MADEDATE = '$currentdate'
										WHERE IDNO = $crewdocid;
			
							");
			$zdocno = "";
			$zdocexpiry = "";
			$zdocissued = "";
			$zremarks = "";
			$zdocument = "";
			$doccode = "";
			$xdocrankcode = "";
			$new = 0;
		break;
		
		case "cancel":
			$zdocno = "";
			$zdocexpiry = "";
			$zdocissued = "";
			$zremarks = "";
			$zdocument = "";
			$doccode = "";
			$xdocrankcode = "";
			$new = 0;
		break;
		
		case "insert":
			
			if (!empty($xdocissued))
				$xdocissuedraw = "'" . date("Y-m-d",strtotime($xdocissued)) . "'";
			else
				$xdocissuedraw = "NULL";
				
			if (!empty($xdocexpiry))
				$xdocexpiryraw = "'" . date("Y-m-d",strtotime($xdocexpiry)) . "'";
			else
				$xdocexpiryraw = "NULL";
			
			$qryinsert = mysql_query("INSERT INTO crewdocstatus VALUES(NULL,$applicantno,'$xdocrankcode','$doccode',
								$xdocissuedraw,$xdocexpiryraw,'$xdocno','$employeeid','$currentdate','$xremarks')
							") or die(mysql_error());
							
			$zdocno = "";
			$zdocexpiry = "";
			$zdocissued = "";
			$zremarks = "";
			$zdocument = "";
			$doccode = "";
			$xdocrankcode = "";
			$new = 0;
			
			echo "<script>alert('Crew Document inserted successfully.');</script>";
		
		break;
		
		case "createnew":
			$zdocno = "";
			$zdocexpiry = "";
			$zdocissued = "";
			$zremarks = "";
			$zdocument = "";
			$doccode = "";
			$xdocrankcode = "";
			$new = 0;
			
		break;
}

if (!empty($applicantno))
{
	$qrygetcrew = mysql_query("SELECT FNAME,GNAME,MNAME FROM crew WHERE APPLICANTNO=$applicantno") or die(mysql_error());
	
	$rowgetcrew = mysql_fetch_array($qrygetcrew);
	
	$fname = $rowgetcrew["FNAME"];
	$gname = $rowgetcrew["GNAME"];
	$mname = $rowgetcrew["MNAME"];
	
	$namefull = $fname . ", " . $gname . " " . $mname;
	
	if (!empty($doccode))
	{
		$qrygetdocs2 = mysql_query("
						SELECT cd.IDNO AS CREWDOCID,cd.DOCCODE,c.DOCUMENT,cd.DOCNO,cd.DATEISSUED,cd.DATEEXPIRED,cd.REMARKS,cd.RANKCODE
						FROM crewdocuments c
						LEFT JOIN crewdocstatus cd ON cd.DOCCODE=c.DOCCODE
						where cd.APPLICANTNO=$applicantno AND cd.DOCCODE='$doccode'
						ORDER BY cd.DATEISSUED DESC
						LIMIT 1
					") or die(mysql_error());
					
		$rowgetdocs2 = mysql_fetch_array($qrygetdocs2);
		
		$zdocument = $rowgetdocs2["DOCUMENT"];
		
		if ($newentry == 0)
		{
			$zcrewdocid = $rowgetdocs2["CREWDOCID"];
			$zdocrankcode = $rowgetdocs2["RANKCODE"];
			$zdocno = $rowgetdocs2["DOCNO"];
			if (!empty($rowgetdocs2["DATEISSUED"]))
				$zdateissued = date("m/d/Y",strtotime($rowgetdocs2["DATEISSUED"]));
			else
				$zdateissued = "";
				
			if (!empty($rowgetdocs2["DATEEXPIRED"]))
				$zdateexpired = date("m/d/Y",strtotime($rowgetdocs2["DATEEXPIRED"]));
			else
				$zdateexpired = "";
				
			$zremarks = $rowgetdocs2["REMARKS"];
		}
	}

	
	
	$qrygetdocs = mysql_query("SELECT DOCCODE,DOCUMENT FROM crewdocuments where DOCCODE IN ('41','F2','51','P1','C0','18')") or die(mysql_error());
	$docselect = "";
	while ($rowgetdocs = mysql_fetch_array($qrygetdocs))
	{
		$dcode = $rowgetdocs["DOCCODE"];
		$doc = $rowgetdocs["DOCUMENT"];
		
		$selectdoc = "";
		if ($dcode == $doccode)
			$selectdoc = "SELECTED";
			
		$docselect .= "<option $selectdoc value=\"$dcode\">$doc</option>";
	}
	
	
	$qrygetrank = mysql_query("SELECT RANKCODE,ALIAS1 FROM rank WHERE STATUS=1") or die(mysql_error());
	$rankselect = "";
	while ($rowgetrank = mysql_fetch_array($qrygetrank))
	{
		$rcode = $rowgetrank["RANKCODE"];
		$ralias = $rowgetrank["ALIAS1"];
		
		$selectrank = "";
		
		if (empty($zdocrankcode))
			$zdocrankcode = $xdocrankcode;
		
		if ($rcode == $zdocrankcode)
			$selectrank = "SELECTED";
			
		$rankselect .= "<option $selectrank value=\"$rcode\">$ralias</option>";
	}
	
}
	
	
echo "
<html>
<head>
<link rel=\"stylesheet\" type=\"text/css\" href=\"veripro.css\">
<script type='text/javascript' src='../veritas/veripro.js'></script>
<script type='text/javascript' src='../veritas/ajax.js'></script>
<script language=\"javascript\" src=\"popcalendar.js\"></script>	
</head>
<body onload=\"\">

<form name=\"docsinfo\" method=\"POST\">
	<div style=\"width:100%;height:440px;padding:0 20px 0 20px;overflow:hidden;\">
		<span class=\"wintitle\" style=\"font-size:12pt;\">MANUAL DOCUMENTS OVERRIDE</span>


					<!-- FOR MULTIPLE RESULTS  -->
						
					<div id=\"multiple\" style=\"position:absolute;top:80px;left:50px;width:600px;height:200px;background-color:#6699FF;z-index:200;
									border:2px solid black;overflow:auto;$showmultiple \">
						<span class=\"sectiontitle\">MULTIPLE ENTRY FOUND</span>
						
						<center>
							<a href=\"#\" style=\"font-size:0.8em;font-weight:Bold;\" onclick=\"document.getElementById('multiple').style.display='none';\">Close Window</a>
						</center>
						<br />
						
						<table width=\"100%\" class=\"listcol\">
							<tr>
								<th width=\"10%\">APP NO</th>
								<th width=\"15%\">CREW CODE</th>
								<th width=\"10%\">RANK</th>
								<th width=\"20%\">FNAME</th>
								<th width=\"20%\">GNAME</th>
								<th width=\"20%\">MNAME</th>
							</tr>
						";
							// if ($multiple == 1)
							// {
								while ($rowmultisearch = mysql_fetch_array($qrysearch))
								{
									$appno = $rowmultisearch["APPLICANTNO"];
									
									//GPA - REMOVED " AND STATUS=1" IN WHERE PART
									
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
									$stat = $rowgetinfo["STATUS"];
									if ($rowgetinfo["STATUS"] == 1)
										$info6 = "ACTIVE";
									else 
										$info6 = "INACTIVE";
										
									$qrycurrentrank = mysql_query("SELECT z.DATEDISEMB,v.MANAGEMENTCODE,v.DIVCODE,z.RANKCODE,r.RANK,r.ALIAS1 FROM 
																	(
																		SELECT '1' AS VMC,RANKCODE,DATEDISEMB,VESSELCODE
																		FROM
																			(
																			SELECT RANKCODE,VESSELCODE,
																			IF(IF(DATECHANGEDISEMB IS NULL,DATEDISEMB,DATECHANGEDISEMB) > CURRENT_DATE,
																				CURRENT_DATE,IF(DATECHANGEDISEMB IS NULL,DATEDISEMB,DATECHANGEDISEMB)) AS DATEDISEMB
																			FROM crewchange where APPLICANTNO=$info1 AND DATEEMB < CURRENT_DATE
																			ORDER BY DATEDISEMB DESC
																			) x
																		
																		UNION					
																							
																		SELECT '2' AS VMC,RANKCODE,DATEDISEMB,NULL
																		FROM
																			(
																			SELECT RANKCODE,DATEDISEMB
																			FROM crewexperience where APPLICANTNO=$info1
																			ORDER BY DATEDISEMB DESC
																			) y
																	) z
																	LEFT JOIN rank r ON r.RANKCODE=z.RANKCODE
																	LEFT JOIN vessel v ON v.VESSELCODE=z.VESSELCODE
																	ORDER BY DATEDISEMB DESC
																") or die(mysql_error());
									
									$rowcurrentrank = mysql_fetch_array($qrycurrentrank);
									$currentrank = $rowcurrentrank["RANK"];

									if (!empty($rowcurrentrank["ALIAS1"]))
										$currentrankalias = $rowcurrentrank["ALIAS1"];
									else 
										$currentrankalias = "---";
									
									$styleinactive = "";
										
									if ($stat == 0)
									{
										$styleinactive = "style=\"background-color:Red;color:White;text-decoration:line-through;\"";
									}
										
									echo "
									<tr $mouseovereffect style=\"cursor:pointer;\" ondblclick=\"
													applicantno.value='$info1';submit();
													document.getElementById('multiple').style.display='none';
													\">
										<td $styleinactive align=\"center\">$info1</td>
										<td $styleinactive align=\"center\">&nbsp;$info2</td>
										<td $styleinactive align=\"center\" title=\"$currentrank\">$currentrankalias</td>
										<td $styleinactive>$info3&nbsp;</td>
										<td $styleinactive>$info4&nbsp;</td>
										<td $styleinactive>$info5&nbsp;</td>
									</tr>
									
									";
								}
							// }
								
						echo "
						</table>
						<br />
						<center>
							<a href=\"#\" style=\"font-size:0.8em;font-weight:Bold;\" onclick=\"document.getElementById('multiple').style.display='none';\">Close Window</a>
						</center>
						<br />
					</div>
						
					<!-- END OF MULTIPLE RESULTS  -->
					
		<center>
		<table style=\"width:80%;margin-top:5px;\" border=1>
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
						onkeyup=\"this.value=this.value.toUpperCase();\"
						style=\"color:Red;font-weight:Bold;\" onkeydown=\"if(event.keyCode==13) {btnfind.focus();}\"/>
						
					<input type=\"button\" name=\"btnfind\" value=\"Find\" onclick=\"actiontxt.value='find';submit();\" />
					
					";
					
				echo "
				</td>
			</tr>
		</table>
		</center>
		";	
		
		if (!empty($applicantno))
		{
				

			
			echo "
			<div style=\"width:90%;height:400px;overflow:auto;\">
				<br />
				<center>
					<span style=\"color:Navy;font-size:1em;font-weight:Bold;\">Crew Name : $namefull</span>
					<br /><br />
					<span style=\"font-size:0.8em;font-weight:Bold;\">
					Document Type &nbsp;&nbsp;
					<select name=\"doccode\" onchange=\"newentry.value='$newentry';submit();\">
						<option value=\"\">--Select One--</option>
						$docselect
					</select>
					</span>
					<input type=\"button\" value=\"Create New\" onclick=\"actiontxt.value='createnew';newentry.value='1';submit();\" />
					<br /><br />
					<center>
						<table style=\"width:80%;font-size:0.8em;\"> 
							<tr>
								<td>Document</td>
								<td>:</td>
								<td style=\"font-size:1.1em;color:Red;font-weight:Bold;\">$zdocument</td>
							</tr>
							<tr>
								<td width=\"25%\">Document No.</td>
								<td width=\"5%\">:</td>
								<td width=\"55%\"><input type=\"text\" value=\"$zdocno\" name=\"xdocno\" /></td>
							</tr>
							<tr>
								<td>Rank</td>
								<td>:</td>
								<td>
									<select name=\"xdocrankcode\" onchange=\"\">
										<option value=\"\">--Select One--</option>
										$rankselect
									</select>
								</td>
							</tr>
							<tr>
								<td>Date Issued</td>
								<td>:</td>
								<td>
									<input type=\"text\" name=\"xdocissued\" value=\"$zdateissued\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
									<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(xdocissued, xdocissued, 'mm/dd/yyyy', 0, 0);return false;\">
									&nbsp;&nbsp;&nbsp;(mm/dd/yyy)
								</td>
							</tr>
							<tr>
								<td>Date Expiry</td>
								<td>:</td>
								<td>
									<input type=\"text\" name=\"xdocexpiry\" value=\"$zdateexpired\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
									<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(xdocexpiry, xdocexpiry, 'mm/dd/yyyy', 0, 0);return false;\">
									&nbsp;&nbsp;&nbsp;(mm/dd/yyy)
								</td>
							</tr>
							<tr>
								<td>Remarks</td>
								<td>:</td>
								<td><textarea rows=\"3\" cols=\"20\" name=\"xremarks\">$zremarks</textarea></td>
							</tr>
							<tr>
								<td colspan=\"2\">&nbsp;</td>
								<td>
									";
									
									if ($newentry == 0)
									{
										echo "
										<input type=\"button\" value=\"Update\" onclick=\"actiontxt.value='update';crewdocid.value='$zcrewdocid';submit();\" />
										<input type=\"button\" value=\"Cancel\" onclick=\"actiontxt.value='cancel';submit();\" />
										";
									}
									else
									{
										echo "
										<input type=\"button\" value=\"Save\" onclick=\"actiontxt.value='insert';submit();\" />
										<input type=\"button\" value=\"Cancel\" onclick=\"actiontxt.value='cancel';submit();\" />
										";
									
									}
								echo "
								</td>
							</tr>
							<!--
							<tr>
								<td colspan=\"3\">&nbsp;</td>
							</tr>
							<tr>
								<td colspan=\"3\" style=\"font-size:1.1em;color:Green;font-weight:Bold;\" align=\"center\">
									REMINDER: Changes made here will affect ALL modules that refer to the documents. <br />
									Please be careful.
								</td>
							</tr>
							-->
						</table>
					</center>
				</center>
				
			</div>
			";
		}
	echo "	
	</div>
	
	
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"crewdocid\" />
	<input type=\"hidden\" name=\"newentry\" />
	<input type=\"hidden\" name=\"applicantno\" value=\"$applicantno\" />
</form>

</body>
";

?>