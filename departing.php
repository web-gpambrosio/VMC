<?php
include('veritas/connectdb.php');
include("veritas/include/stylephp.inc");
include("veritas/include/functions.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');
$basedirid = "idpics/";
$basedirdocs = "docimages/";

$dateformat = "dMY";

$wherediv = "";

if (isset($_SESSION['employeeid']))
{
	$employeeid = $_SESSION['employeeid'];
	
	$qrygetdivision = mysql_query("SELECT DIVCODE FROM employee WHERE EMPLOYEEID='$employeeid'") or die(mysql_error());
	$rowgetdivision = mysql_fetch_array($qrygetdivision);
	$divcode = $rowgetdivision["DIVCODE"];
	
	if (!empty($divcode))
		$wherediv = "AND DIVCODE=$divcode";
	else 
		$wherediv = "";
}
else 
	$employeeid = "";

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];

if (isset($_POST['applicantno']))
	$applicantno = $_POST['applicantno'];
else 
	$applicantno = $_GET["applicantno"];
	
if (isset($_POST['groupby']))
	$groupby = $_POST['groupby'];
else 
	$groupby = "1";
	
if (isset($_POST['byrank']))
	$byrank = $_POST['byrank'];
else 
	$byrank = "All";
	
if (isset($_POST['byvessel']))
	$byvessel = $_POST['byvessel'];
else 
	$byvessel = "";

if (isset($_POST["searchkey"]))
	$searchkey = $_POST["searchkey"];
	
if (isset($_POST["searchby"]))
	$searchby = $_POST["searchby"];
	
//if ($searchkey == "")
//	$disablesearch = "disabled=\"disabled\"";
//else 
//	$disablesearch = "";
	
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
				
					$qrysearch = mysql_query("
								SELECT cc.APPLICANTNO
								FROM crewchange cc
								LEFT JOIN crew c ON c.APPLICANTNO=cc.APPLICANTNO
								WHERE DATEEMB IS NOT NULL AND DEPMNLDATE IS NULL AND cc.APPLICANTNO LIKE '$searchkey%'
								GROUP BY cc.APPLICANTNO
						") or die(mysql_error());
				
				break;
			case "2"	: //CREW CODE
				
					$qrysearch = mysql_query("
								SELECT cc.APPLICANTNO
								FROM crewchange cc
								LEFT JOIN crew c ON c.APPLICANTNO=cc.APPLICANTNO
								WHERE DATEEMB IS NOT NULL AND DEPMNLDATE IS NULL AND CREWCODE LIKE '$searchkey%'
								GROUP BY cc.APPLICANTNO
						") or die(mysql_error());
				
				break;
			case "3"	: //FAMILY NAME
				
					$qrysearch = mysql_query("
								SELECT cc.APPLICANTNO
								FROM crewchange cc
								LEFT JOIN crew c ON c.APPLICANTNO=cc.APPLICANTNO
								WHERE DATEEMB IS NOT NULL AND DEPMNLDATE IS NULL AND FNAME LIKE '$searchkey%'
								GROUP BY cc.APPLICANTNO
						") or die(mysql_error());
				
				break;
			case "4"	: //GIVEN NAME
				
					$qrysearch = mysql_query("
								SELECT cc.APPLICANTNO
								FROM crewchange cc
								LEFT JOIN crew c ON c.APPLICANTNO=cc.APPLICANTNO
								WHERE DATEEMB IS NOT NULL AND DEPMNLDATE IS NULL AND GNAME LIKE '$searchkey%'
								GROUP BY cc.APPLICANTNO
						") or die(mysql_error());
				
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
		else 
		{
			echo "<script>alert('No Departing Crew found!');</script>";
			$searchkey="";
		}
		
	break;
}
	


	$ccid = "";
	$crewname = "";
	$crewcode = "";
	$crewvessel = "";
	$crewrank = "";
	$crewscholar = "";
	$crewfastrack =  "";
	$errormsg = "";

if (!empty($applicantno))
{
	$qrycrewdtls = mysql_query("
			SELECT * FROM (
				SELECT cc.APPLICANTNO,cc.CCID,
				IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,
				cc.ARRMNLDATE,LEFT(v.FLAGCODE,2) AS FLAGCODE,
				CONCAT(c.FNAME,', ',c.GNAME,' ',c.MNAME) AS NAME,c.CREWCODE,
				LEFT(v.ALIAS2,12) AS VESSEL,v.VESSELCODE,v.VESSEL AS VESSELNAME,r.ALIAS1 AS RANKALIAS,r.RANKCODE,
				r.RANKLEVELCODE,r.RANKTYPECODE,r.RANK,r.RANKING,
				s.SCHOLASTICCODE,s.DESCRIPTION AS SCHOLARTYPE,f.FASTTRACKCODE,f.FASTTRACK,cc.DATEEMB,cc.DEPMNLDATE
				FROM crewchange cc
				LEFT JOIN crew c ON c.APPLICANTNO=cc.APPLICANTNO
				LEFT JOIN rank r ON r.RANKCODE=cc.RANKCODE
				LEFT JOIN ranklevel rl ON rl.RANKLEVELCODE=r.RANKLEVELCODE
				LEFT JOIN ranktype rt ON rt.RANKTYPECODE=r.RANKTYPECODE
				LEFT JOIN vessel v ON v.VESSELCODE=cc.VESSELCODE
				LEFT JOIN crewscholar cs ON cs.APPLICANTNO=cc.APPLICANTNO
				LEFT JOIN scholar s ON s.SCHOLASTICCODE=cs.SCHOLASTICCODE
				LEFT JOIN crewfasttrack cf ON cf.APPLICANTNO=cc.APPLICANTNO
				LEFT JOIN fasttrack f ON f.FASTTRACKCODE=cf.FASTTRACKCODE
			) x
			WHERE DATEEMB IS NOT NULL AND DEPMNLDATE IS NULL
			AND APPLICANTNO=$applicantno
		") or die(mysql_error());
	
	if (mysql_num_rows($qrycrewdtls) == 1)
	{
		$rowcrewdtls = mysql_fetch_array($qrycrewdtls);
		
		$ccid = $rowcrewdtls["CCID"];
		$crewname = $rowcrewdtls["NAME"];
		$crewcode = $rowcrewdtls["CREWCODE"];
		$crewvesselcode = $rowcrewdtls["VESSELCODE"];
		$crewvessel = $rowcrewdtls["VESSELNAME"];
		$flagcode = $rowcrewdtls["FLAGCODE"];
		$crewrank = $rowcrewdtls["RANKALIAS"];
		$rankcode = $rowcrewdtls["RANKCODE"];
		
		$crewemb = date($dateformat,strtotime($rowcrewdtls["DATEEMB"]));
		$crewemb2 = $rowcrewdtls["DATEEMB"];
		
			
		$crewscholar = $rowcrewdtls["SCHOLARTYPE"];
		$crewfastrack = $rowcrewdtls["FASTTRACK"];
		$debriefstatus = $rowcrewdtls["DEBRIEFSTATUS"];
		$iframesrc="departstatus.php?ccid=$ccid&flagcode=$flagcode&rankcode=$rankcode";
//		$arrmnldate = $rowcrewdtls["ARRMNLDATE"];
		
	}
	elseif (mysql_num_rows($qrycrewdtls) > 1)
	{
		$crewvessel="<select onchange=\"vesselsel(this.value);\" style=\"border:0;font-size:0.9em;font-weight:Bold;color:Yellow;background:black;\">\n";
		$crewvessel.="<option selected value=\"\">-Select-</option>\n";
		while($rowcrewdtls = mysql_fetch_array($qrycrewdtls))
		{
			$ccid1 = $rowcrewdtls["CCID"];
			$crewname = $rowcrewdtls["NAME"];
			$crewcode = $rowcrewdtls["CREWCODE"];
			$crewvessel1 = $rowcrewdtls["VESSELNAME"];
			$crewrank1 = $rowcrewdtls["RANKALIAS"];
			$crewemb1 = date($dateformat,strtotime($rowcrewdtls["DATEEMB"]));
			$crewvessel.="<option value=\"$crewrank1^$crewemb1^$ccid1\">$crewvessel1</option>\n";
		}
		$crewvessel.="</select>\n";
		$iframesrc="";
		echo "<script>alert('Multiple vessels found for departing crew!');</script>";
	}
	else 
	{
		$applicantno = "";
		$errormsg = "Entry not found.";
	}
	
	
}


echo "
<html>\n
<head>\n
<title>Departing Seaman</title>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>
<script language=\"JavaScript\">
function vesselsel(x)
{
	if(x=='')
	{
		document.getElementById('crewrank').innerHTML='';
		document.getElementById('crewemb').innerHTML='';
		document.getElementById('crewid').innerHTML='';
		document.getElementById('departstat').src='';
	}
	else
	{
		var dataarr=x.split('^');
		document.getElementById('crewrank').innerHTML=dataarr[0];
		document.getElementById('crewemb').innerHTML=dataarr[1];
		document.getElementById('crewid').innerHTML=dataarr[2];
		document.getElementById('departstat').src='departstatus.php?ccid='+dataarr[2];
	}
	document.getElementById('departstat').focus();
}
</script>
<body onload=\"\" style=\"overflow:hidden;background-color:White;\">\n

<form name=\"departing\" method=\"POST\">\n

<span class=\"wintitle\">DEPARTING SEAMAN</span>

<div style=\"width:100%;background-color:White;\">


	<div style=\"width:100%;height:600px;float:right;padding:10 5 0 5;background-color:White;\">
			<div id=\"multiple\" style=\"position:absolute;top:200px;left:200px;width:600px;height:400px;background-color:#6699FF;
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
						
					<input type=\"button\" name=\"btnfind\" value=\"Find\" onclick=\"actiontxt.value='find';submit();\" />
				</td>
			</tr>
		</table>
		</center>

		<div style=\"width:100%;height:100px;margin:3 5 3 5;background-color:#004000;\">
			
			<span class=\"sectiontitle\">CREW INFORMATION</span>
		
			<div style=\"width:80%;height:100px;float:left;border:1px solid Black;background-color:Black;\">
			";
				$stylehdr = "style=\"font-size:0.75em;font-weight:Bold;color:White;\"";
				$styledtl2 = "style=\"font-size:0.9em;font-weight:Bold;color:Yellow;\"";
				
			echo "
				<div style=\"width:100%;height:70px;\">
					<table style=\"width:100%;\">
						<tr>
							<td colspan=\"2\" $stylehdr>NAME: <br /><span style=\"font-size:1.3em;color:Yellow;\">$crewname $errormsg</span></td>
							<td $stylehdr>APPLICANT NO: <br /><span style=\"font-size:1.3em;color:Yellow;\">$applicantno</span></td>
							<td $stylehdr>CREW CODE: <br /><span style=\"font-size:1.3em;color:Yellow;\">$crewcode</span></td>
						</tr>
						<tr>
							<td $stylehdr>VESSEL: <br /><span style=\"font-size:1.3em;color:Yellow;\">$crewvessel</span> </td>
							<td $stylehdr>CREW CHANGE ID(CCID): <br /><span style=\"font-size:1.3em;color:Yellow;\" id=\"crewid\">$ccid</span> </td>
							<td $stylehdr>RANK: <br /><span style=\"font-size:1.3em;color:Yellow;\" id=\"crewrank\">$crewrank </span> </td>
							<td $stylehdr>ETD: <br /><span style=\"font-size:1.3em;color:Yellow;\" id=\"crewemb\">$crewemb </span> </td>
						</tr>
					</table>
					<br />
				</div>
			</div>
			
			<div style=\"width:19%;float:right;color:Orange;\">
	";
				$dirfilename = $basedirid . $applicantno . ".JPG";
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
		
		<div style=\"width:100%;height:490px;margin:3 5 3 5;background-color:White;\">

			<center>
			";
	
			if (!empty($applicantno))
			{
				echo "
				<iframe marginwidth=0 marginheight=0 id=\"departstat\" frameborder=\"0\" name=\"content\" 
					src=\"$iframesrc\" scrolling=\"auto\" 
					style=\"width:100%;height:100%;\">
				</iframe>
				";
			
			}
			else 
			{
				
				
			}
			
			echo "
			</center>
		
		</div>
		
	</div>

</div>

	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"applicantno\" />

</form>
</body>
";

?>