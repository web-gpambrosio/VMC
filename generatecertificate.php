<?php
include('veritas/connectdb.php');
include("veritas/include/stylephp.inc");
include("veritas/include/functions.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');
$basedirid = "idpics/";
$basedirdocs = "docimages/";

$datenow = date("Y-m-d");

if (isset($_SESSION["employeeid"]))
	$employeeid = $_SESSION["employeeid"];
else 
	$employeeid = "";

if (isset($_POST["actiontxt"]))
	$actiontxt = $_POST["actiontxt"];

if (isset($_POST["applicantno"]))
	$applicantno = $_POST["applicantno"];
	
if (isset($_POST["schedid"]))
	$schedid = $_POST["schedid"];
	
if (isset($_POST["classcardno"]))
	$classcardno = $_POST["classcardno"];
	
if (isset($_POST["postidno"]))
	$postidno = $_POST["postidno"];
	
if (isset($_POST["grade"]))
	$grade = $_POST["grade"];
	
if (isset($_POST["remarks"]))
	$remarks = $_POST["remarks"];
	
if (isset($_POST["status"]))
	$status = $_POST["status"];
else 
	$status = "1";

if (isset($_POST["searchkey"]))
	$searchkey = $_POST["searchkey"];
	
if (isset($_POST["searchby"]))
	$searchby = $_POST["searchby"];
	
if(empty($searchby))
	$searchby="APPLICANTNO";
$$searchby="selected";
	
if ($searchkey == "")
	$disablesearch = "disabled=\"disabled\"";
else 
	$disablesearch = "";

	
$showmultiple = "display:none;";
$multiple = 0;

$disabledinput = "disabled=\"disabled\"";

switch ($status)
{
	case "1" : $chkcompleted = "checked=\"checked\""; break;
	case "2" : $chkincomplete = "checked=\"checked\""; break;
}


switch ($actiontxt)
{
	case "find"	:
		$applicantno="";
		
		$addwhere="$searchby LIKE '$searchkey%' ";
		$errormsg = "";
		$qrysearch = mysql_query("SELECT c.APPLICANTNO,c.CREWCODE 
			FROM crew c
			WHERE $addwhere AND STATUS=1") or die(mysql_error());
		
		if(mysql_num_rows($qrysearch) == 0)
		{
			echo "<script>alert('Search produced 0 record!');</script>";
			$searchkey="";
			$disablesearch = "disabled=\"disabled\"";
		}
		else if (mysql_num_rows($qrysearch) == 1)
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
	
	case "postgrade":

			$qrypostgrade = mysql_query("UPDATE crewtraining SET GRADE=$grade,
																STATUS=$status,
																REMARKS = '$remarks',
																POSTBY = '$employeeid',
																POSTDATE = '$currentdate'
										WHERE IDNO=$postidno AND APPLICANTNO=$applicantno
										") or die(mysql_error());
			
			$postidno = "";
			
		break;
	
}


if (!empty($applicantno))
{
	$qrycrew = mysql_query("
							SELECT * FROM (
							SELECT CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,CONCAT(GNAME,' ',LEFT(MNAME,1),'.',' ',FNAME) AS NAME2,
							c.APPLICANTNO,CREWCODE,r.ALIAS2 AS RANK,cc.RANKCODE
							FROM crew c
							LEFT JOIN crewchange cc ON c.APPLICANTNO=cc.APPLICANTNO
							LEFT JOIN rank r ON cc.RANKCODE=r.RANKCODE
							WHERE c.APPLICANTNO=$applicantno
							ORDER BY cc.DATEDISEMB DESC) x
							GROUP BY APPLICANTNO
							") or die(mysql_error());
	
	$rowcrew = mysql_fetch_array($qrycrew);
	$crewcode = $rowcrew["CREWCODE"];
	$crewname = $rowcrew["NAME"];
	$crewname2 = $rowcrew["NAME2"];
	$rank = $rowcrew["RANK"];
	$rankcode = $rowcrew["RANKCODE"];
}



echo "
<html>\n
<head>\n
<title>Training - Grade Posting</title>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>

<body onload=\"document.gradeposting.searchkey.select();\" style=\"overflow:hidden;background-color:White;\">\n

<form name=\"gradeposting\" method=\"POST\">\n


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
														else {submit();}\">
						<option value=\"\">--Select Search Key--</option>
					";
	
				echo "
						<option $APPLICANTNO value=\"APPLICANTNO\">APPLICANT NO</option>
						<option $CREWCODE value=\"CREWCODE\">CREW CODE</option>
						<option $FNAME value=\"FNAME\">FAMILY NAME</option>
						<option $GNAME value=\"GNAME\">GIVEN NAME</option>
					</select>
				</td>
				<td align=\"left\" style=\"font-size:0.8em;font-weight:Bold;\">Search Key</td>
				<td align=\"center\">
						<input type=\"text\" name=\"searchkey\" size=\"20\" value=\"$searchkey\"
						onkeyup=\"this.value=this.value.toUpperCase();if (this.value != '') {btnfind.disabled=false;}else{btnfind.disabled=true;}\"
						style=\"color:Red;font-weight:Bold;\" onkeydown=\"if(event.keyCode==13) {btnfind.focus();}\"/>
						
					<input type=\"button\" name=\"btnfind\" value=\"Find\" $disablesearch onclick=\"actiontxt.value='find';submit();\" />
				</td>
			</tr>
		</table>
		</center>
		
		<div style=\"width:100%;height:130px;margin:5 5 10 5;background:inherit;\">
			<div style=\"width:40%;height:130px;background-color:Black;float:left;\">
			
				<span class=\"sectiontitle\">CREW INFORMATION</span>
				
				<div style=\"width:100%;height:130px;float:left;\">
				";
					$stylehdr = "style=\"font-size:0.7em;font-weight:Bold;color:White;\"";
					$styledtl = "style=\"font-size:0.9em;font-weight:Bold;color:Yellow;\"";
					
				echo "
					<div style=\"width:100%;padding:5 0 5 5;\" cellspacing=\"0\" cellpadding=\"0\">
						<table style=\"float:left;\">
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
								<td $stylehdr>RANK</td>
								<td $stylehdr>:</td>
								<td $styledtl>$rank</td>
							</tr>
							<tr>
								<td $stylehdr>NAME:</td>
							<tr>
							</tr>
								<td colspan=\"3\" $styledtl>$crewname</td>
							</tr>
						</table>
					</div>

				</div>
			<!--	
				<div style=\"width:20%;float:left;color:Orange;\">
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
			-->
			</div>
			<div style=\"width:59%;height:130px;background:gray;float:right;\">
			";
			$stylebutton = "style=\"border:2px solid Yellow;background-color:Red;color:White;font-size:0.8em;font-weight:Bold;\"";
			echo "
				<span class=\"sectiontitle\">TEMPLATE</span>
				<br />
				<center>
				<div style=\"width:90%;height:110px;background:inherit;\">
					<input type=\"button\" value=\"Attendance 1\" $disablesearch $stylebutton
						onclick=\"document.getElementById('certttl').innerHTML='CERTIFICATE - '+this.value;
							document.getElementById('certid').src='certificatetemplate.php?applicantno=$applicantno&certtype=attendance1&rankcode=$rankcode&name=$crewname2'\" />
					<input type=\"button\" value=\"Attendance 2\" $disablesearch $stylebutton
						onclick=\"document.getElementById('certttl').innerHTML='CERTIFICATE - '+this.value;
							document.getElementById('certid').src='certificatetemplate.php?applicantno=$applicantno&certtype=attendance2&rankcode=$rankcode&name=$crewname2'\" />
					<input type=\"button\" value=\"Attendance 4\" $disablesearch $stylebutton
						onclick=\"document.getElementById('certttl').innerHTML='CERTIFICATE - '+this.value;
							document.getElementById('certid').src='certificatetemplate.php?applicantno=$applicantno&certtype=attendance4&rankcode=$rankcode&name=$crewname2'\" />
					<br /><br />
					<input type=\"button\" value=\"Promotion 1\" $disablesearch $stylebutton
						onclick=\"document.getElementById('certttl').innerHTML='CERTIFICATE - '+this.value;
							document.getElementById('certid').src='certificatetemplate.php?applicantno=$applicantno&certtype=promotion1&rankcode=$rankcode&name=$crewname2'\" />
					<input type=\"button\" value=\"Completion 1\" $disablesearch $stylebutton
						onclick=\"document.getElementById('certttl').innerHTML='CERTIFICATE - '+this.value;
							document.getElementById('certid').src='certificatetemplate.php?applicantno=$applicantno&certtype=completion1&rankcode=$rankcode&name=$crewname2'\" />
				</div>
				</center>
			</div>
		</div>
		<div style=\"width:100%;height:420px;background-color:Gray;margin:3 5 3 5;border:1px solid Black;overflow:auto;\">
			<span class=\"sectiontitle\" id=\"certttl\">CERTIFICATE</span>
			<iframe id=\"certid\" src=\"\" width=\"100%\" height=\"400px\"></iframe>
		</div>
		

	</div>
	
	
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"applicantno\" value=\"$applicantno\" />
	<input type=\"hidden\" name=\"schedid\" value=\"$schedid\" />
	<input type=\"hidden\" name=\"postidno\" />

	
</form>

</body>
";
?>