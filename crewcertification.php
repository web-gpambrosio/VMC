<?php
include('veritas/connectdb.php');
include("veritas/include/stylephp.inc");
include("veritas/include/functions.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');
$basedirid = "idpics/";
$basedirdocs = "docimages/";

$dateformat = "dMY";
$getendorsement=$_GET["endorsement"];
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

if (isset($_POST['idno']))
	$idno = $_POST['idno'];

if (isset($_POST['newdate']))
	$newdate = $_POST['newdate'];
	
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
	
switch ($actiontxt)
{
	case "find"	:
		
		$errormsg = "";
		
		switch ($searchby)
		{
			case "1"	: //APPLICANT NO
				
					$qrysearch = mysql_query("SELECT APPLICANTNO FROM crew WHERE APPLICANTNO LIKE '$searchkey%' ORDER BY APPLICANTNO") or die(mysql_error());
				
				break;
			case "2"	: //CREW CODE
				
					$qrysearch = mysql_query("SELECT APPLICANTNO FROM crew WHERE CREWCODE LIKE '$searchkey%' ORDER BY CREWCODE") or die(mysql_error());
				
				break;
			case "3"	: //FAMILY NAME
				
					$qrysearch = mysql_query("SELECT APPLICANTNO FROM crew WHERE FNAME LIKE '$searchkey%' ORDER BY FNAME") or die(mysql_error());
				
				break;
			case "4"	: //GIVEN NAME
				
					$qrysearch = mysql_query("SELECT APPLICANTNO FROM crew WHERE GNAME LIKE '$searchkey%' ORDER BY GNAME") or die(mysql_error());
				
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
		}
		
	break;
	
	case "create":
		
		$newdate = date("Y-m-d",strtotime($newdate));
		$qryinsert = mysql_query("INSERT INTO crewwithdrawal(APPLICANTNO,FORMDATE) VALUES($applicantno,'$newdate')") or die(mysql_errno());
		$newdate = "";
		// echo "<script>alert('TEST');</script>";
	break;
}
	

	$crewname = "";
	$crewcode = "";
	$crewvessel = "";
	$crewrank = "";
	$crewscholar = "";
	$crewfastrack =  "";
	$errormsg = "";

if (!empty($applicantno))
{
	$qrycrewdtls = mysql_query("SELECT cc.APPLICANTNO,cc.CCID,
									IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,
									cc.ARRMNLDATE,
									CONCAT(c.FNAME,', ',c.GNAME,' ',c.MNAME) AS NAME,c.CREWCODE,
									LEFT(v.ALIAS2,12) AS VESSEL,v.VESSELCODE,v.VESSEL AS VESSELNAME,r.ALIAS1 AS RANKALIAS,r.RANKCODE,
									r.RANKLEVELCODE,r.RANKTYPECODE,r.RANK,r.RANKING,
									s.SCHOLASTICCODE,s.DESCRIPTION AS SCHOLARTYPE,f.FASTTRACKCODE,f.FASTTRACK
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
									WHERE cc.APPLICANTNO=$applicantno
									ORDER BY DATEDISEMB DESC
									LIMIT 1;
							") or die(mysql_error());
	
	if (mysql_num_rows($qrycrewdtls) > 0)
	{
		$rowcrewdtls = mysql_fetch_array($qrycrewdtls);
		
		$ccid = $rowcrewdtls["CCID"];
		$crewname = $rowcrewdtls["NAME"];
		$crewcode = $rowcrewdtls["CREWCODE"];
		$crewvessel = $rowcrewdtls["VESSELNAME"];
		$crewrank = $rowcrewdtls["RANKALIAS"];
		
		if (!empty($rowcrewdtls["DATEDISEMB"]))
			$crewdisemb = date($dateformat,strtotime($rowcrewdtls["DATEDISEMB"]));
		else 
			$crewdisemb = "---";
			
		$crewscholar = $rowcrewdtls["SCHOLARTYPE"];
		$crewfastrack = $rowcrewdtls["FASTTRACK"];
		$debriefstatus = $rowcrewdtls["DEBRIEFSTATUS"];
		
//		$arrmnldate = $rowcrewdtls["ARRMNLDATE"];
		
		$qrychecklift = mysql_query("SELECT IDNO FROM crewwithdrawal WHERE APPLICANTNO=$applicantno AND LIFTWITHDRAWAL=0") or die(mysql_error());
		
		if(mysql_num_rows($qrychecklift) != 0)
			$liftfound = 1;
		
		//GET WITHDRAWAL HISTORY
		$qrygethistory = mysql_query("SELECT IDNO,DATE_FORMAT(FORMDATE,'%m/%d/%Y') AS FORMDATE,DATE_FORMAT(EFFECTDATE,'%m/%d/%Y') AS EFFECTDATE,
								OPERATIONSBY,DATE_FORMAT(OPERATIONSDATE,'%m/%d/%Y') AS OPERATIONSDATE,OPERATIONSREMARKS
								FROM crewwithdrawal WHERE APPLICANTNO=$applicantno AND NFR=0 ORDER BY FORMDATE") or die(mysql_error());
		$historycontents = "";	
		while($rowgethistory = mysql_fetch_array($qrygethistory))
		{
			$zidno = $rowgethistory["IDNO"];
			$zformdate = $rowgethistory["FORMDATE"];
			$zeffectdate = $rowgethistory["EFFECTDATE"];
			$zoperationsby = $rowgethistory["OPERATIONSBY"];
			$zoperationsdate = $rowgethistory["OPERATIONSDATE"];
			$zoperationsremarks = $rowgethistory["OPERATIONSREMARKS"];
			
			$stylehistory = "style=\"cursor:pointer;\"";
			$viewform = "";
			$dir = "docimages/" . $applicantno . "/CrewWithdrawalForm" . "_$zidno" . ".pdf";
			if (checkpath($dir,1))
			{
				$viewform = "<a href=\"#\" onclick=\"openWindow('$dir', 'viewdoc' ,900, 600);\" style=\"font-size:0.9em;font-weight:Bold;color:Red;\" >
					[View Form] </a>";
			}
			
			$historycontents .= "<tr ondblclick=\"idno.value=$zidno;applicantno.value=$applicantno;submit();\" $mouseovereffect>";
			
			$historycontents .= "<td $stylehistory>&nbsp;$zformdate</td>";
			$historycontents .= "<td $stylehistory>&nbsp;$zeffectdate</td>";
			$historycontents .= "<td $stylehistory>&nbsp;$zoperationsremarks</td>";
			$historycontents .= "<td $stylehistory>&nbsp;$zoperationsby</td>";
			$historycontents .= "<td $stylehistory>&nbsp;$zoperationsdate</td>";
			$historycontents .= "<td $stylehistory align=\"center\">&nbsp;$viewform</td>";
			$historycontents .= "<td $stylehistory align=\"center\">
								<a href=\"#\" style=\"font-weight:Bold;color:Green;\" onclick=\"openWindow('repwithdrawalform.php?applicantno=$applicantno&idno=$zidno&crewname=$crewname&crewrank=$crewrank&crewvessel=$crewvessel&date=$zformdate', 'viewdoc' ,900, 600);\" >Print Form</a>
							</td>";
			
			$historycontents .= "</tr>";
		}
		
	}
	else 
	{
		$applicantno = "";
		$errormsg = "Entry not found.";
	}
	
	
}

if($getendorsement==1)
	$gettitle="ENDORSEMENT";
else 
	$gettitle="CERTIFICATION";
	

echo "
<html>\n
<head>\n
<title>Crew Withdrawal</title>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>
<script language=\"javascript\" src=\"popcalendar.js\"></script>

<body onload=\"\" style=\"overflow:hidden;background-color:White;\">\n

<form name=\"crewcertification\" method=\"POST\">\n

<span class=\"wintitle\">$gettitle</span>

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
							<td $stylehdr>NAME: <br /><span style=\"font-size:1.3em;color:Yellow;\">$crewname $errormsg</span></td>
							<td $stylehdr>APPLICANT NO: <br /><span style=\"font-size:1.3em;color:Yellow;\">$applicantno</span></td>
							<td $stylehdr>CREW CODE: <br /><span style=\"font-size:1.3em;color:Yellow;\">$crewcode</span></td>
						</tr>
						<tr>
							<td $stylehdr>VESSEL: <br /><span style=\"font-size:1.3em;color:Yellow;\">$crewvessel</span> </td>
							<td $stylehdr>RANK: <br /><span style=\"font-size:1.3em;color:Yellow;\">$crewrank </span> </td>
							<td $stylehdr>DISEMBARK: <br /><span style=\"font-size:1.3em;color:Yellow;\">$crewdisemb </span> </td>
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
			
		";
		
		if (!empty($historycontents))
		{
			
			echo "
			<div style=\"width:100%;height:80px;float:left;border:1px solid Black;background-color:White;\">
				<span class=\"sectiontitle\">WITHDRAWAL HISTORY</span>
				
				<table width=\"100%\" style=\"font-size:0.8em;overflow:auto;\" cellpadding=\"0\" cellspacing=\"0\">
					<tr>
						<th width=\"10%\"><u>ISSUED DATE</u></th>
						<th width=\"10%\"><u>EFFECT DATE</u></th>
						<th width=\"40%\"><u>REMARKS</u></th>
						<th width=\"10%\"><u>BY</u></th>
						<th width=\"10%\"><u>DATE</u></th>
						<th width=\"10%\"><u>UPLOAD</u></th>
						<th width=\"10%\"><u>FORM</u></th>
					</tr>
					$historycontents
				</table>
				
			</div>
			";
		}
		
		echo "
		</div>
		
		<div style=\"width:100%;height:390px;margin:3 5 3 5;background-color:White;\">

			<center>
			";
	
			if (!empty($applicantno))
			{
				echo "
				<iframe marginwidth=0 marginheight=0 id=\"crewcertification\" frameborder=\"0\" name=\"content\" 
					src=\"reports/repcrewcertification.php?applicantno=$applicantno&endorsement=$getendorsement\" scrolling=\"auto\" 
					style=\"width:100%;height:100%;\">
				</iframe>
				";
			
			}
			echo "
			</center>
		
		</div>
		
	</div>

</div>

	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"applicantno\" />
	<input type=\"hidden\" name=\"idno\" />

</form>
</body>
";

?>