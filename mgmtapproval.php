<?php

include("veritas/connectdb.php");
include("veritas/include/stylephp.inc");
include("veritas/include/functions.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');
$datenow = date('d F Y');
$basedir = "idpics/";
$basedirdocs = "docimages/";

$docdisplay = "display:none;";

if(isset($_SESSION['employeeid']))
	$employeeid=$_SESSION['employeeid'];

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];

if (isset($_POST['ranktypecodehidden']))
	$ranktypecodehidden = $_POST['ranktypecodehidden'];

if (isset($_POST['vmcremarks']))
	$vmcremarks = $_POST['vmcremarks'];

if (isset($_POST['applicantno']))
	$applicantno = $_POST['applicantno'];
	
if (isset($_POST['rankcode']))
	$rankcode = $_POST['rankcode'];
else 
	$rankcode = "All";
	
if (isset($_POST['statusrankcode']))
	$statusrankcode = $_POST['statusrankcode'];
else 
	$statusrankcode = "All";
	
if (isset($_POST['vmcrankcode']))
	$vmcrankcode = $_POST['vmcrankcode'];
	
if (isset($_POST['rankcodesel']))
	$rankcodesel = $_POST['rankcodesel'];	

if (isset($_POST['doctype']))
{
	$doctype = $_POST['doctype'];
	
	if ($doctype != "")
		$docdisplay = "display:block;";
}
	
if (isset($_POST['groupby']))
	$groupby = $_POST['groupby'];
else 
	$groupby = "1";
		
$select1 = "";
$select2 = "";
$select3 = "";

//if ($vmcrankcode == "")
//	$vmcrankcode = $rankcode;

		
$wherepart = "";

switch ($actiontxt)
{
	case "approve"	:
		
			$qryverify = mysql_query("SELECT APPLICANTNO FROM applicantstatus WHERE APPLICANTNO=$applicantno") or die(mysql_error());
			
			if (mysql_num_rows($qryverify) > 0)
			{
				$qryappstatusupdate = mysql_query("UPDATE applicantstatus SET APPROVEDBY='$employeeid', APPROVEDDATE='$currentdate',
												VMCREMARKS='$vmcremarks'
												WHERE APPLICANTNO=$applicantno") or die(mysql_error());
			}
//						else 
//						{
//							$qryappstatusinsert = mysql_query("INSERT INTO applicantstatus(APPLICANTNO,APPROVEDBY,APPROVEDDATE,VMCREMARKS) 
//															VALUES($applicantno,'$employeeid','$currentdate','$vmcremarks')") or die(mysql_error());
//						}
			//CREATE CREW CODE
				//check if crewcode exists
//			if(mysql_num_rows(mysql_query("SELECT * FROM crew WHERE APPLICANTNO=$applicantno"))!=0)
//			{
//					//get last crew code based on YEAR,RANKTYPECODE AND SEQUENTIAL NUMBER
//				$getyear = date("Y");
//				$yearrank=$getyear.$ranktypecodehidden;
//				$qrymaxcrewcode=mysql_query("SELECT RIGHT(CREWCODE,3) AS CREWSEQ
//					FROM crew c WHERE LEFT(CREWCODE,5)='$yearrank'
//					ORDER BY CREWCODE DESC
//					LIMIT 1") or die(mysql_error());
//				if(mysql_num_rows($qrymaxcrewcode)==0)
//					$lastno=1;
//				else 
//				{
//					$rowmaxcrewcode=mysql_fetch_array($qrymaxcrewcode);
//					$lastno=$rowmaxcrewcode["CREWSEQ"]*1+1;
//				}
//				$lastnolen=strlen($lastno);
//				
//				if($lastnolen==1)
//					$maxno=$yearrank."00".$lastno;
//				if($lastnolen==2)
//					$maxno=$yearrank."0".$lastno;
//				if($lastnolen==3)
//					$maxno=$yearrank.$lastno;
//				$qrycreatecode=mysql_query("UPDATE crew SET CREWCODE='$maxno' WHERE APPLICANTNO=$applicantno") or die(mysql_error());
//			}
			$applicantno = "";
			$vmcremarks = "";
				
		
		break;
	case "hold"	:
		
			$qryverify = mysql_query("SELECT APPLICANTNO FROM applicantstatus WHERE APPLICANTNO=$applicantno") or die(mysql_error());
			
			if (mysql_num_rows($qryverify) > 0)
			{
				$qryappstatusupdate = mysql_query("UPDATE applicantstatus SET HOLDBY='$employeeid', HOLDDATE='$currentdate', 
												VMCREMARKS='$vmcremarks'
												WHERE APPLICANTNO=$applicantno") or die(mysql_error());
			}
			else 
			{
				$qryappstatusinsert = mysql_query("INSERT INTO applicantstatus(APPLICANTNO,HOLDBY,HOLDDATE,VMCREMARKS) 
												VALUES($applicantno,'$employeeid','$currentdate','$vmcremarks')") or die(mysql_error());
			}
			
			$applicantno = "";
			$vmcremarks = "";
		
		break;
}

switch ($groupby)
{
	case "1"	:  //FOR APPROVAL
		
			$wherepart = "AND at.APPROVEDDATE IS NULL AND at.HOLDDATE IS NULL";
			$select1 = "SELECTED";
		
		break;
	case "2"	:  //ON HOLD
		
			$wherepart = "AND at.HOLDDATE IS NOT NULL AND at.APPROVEDDATE IS NULL";
			$select2 = "SELECTED";
		
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

$qryranklist=mysql_query("SELECT LEFT(RANK,18) AS RANK,RANKCODE FROM rank WHERE STATUS=1 ORDER BY RANKING") or die(mysql_error());

$qryforapproval = mysql_query("SELECT at.APPLICANTNO,CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,ax.PASSED,
							r.RANK,r.ALIAS1,r.RANKLEVELCODE,r.RANKTYPECODE,at.ACCEPTBY,at.ACCEPTDATE,
							s.SCHOLASTICCODE,s.DESCRIPTION AS SCHOLARTYPE,f.FASTTRACKCODE,f.FASTTRACK
							FROM applicantstatus at
							LEFT JOIN applicantexam ax ON ax.APPLICANTNO=at.APPLICANTNO
							LEFT JOIN crew c ON c.APPLICANTNO=at.APPLICANTNO
							LEFT JOIN rank r ON r.RANKCODE=at.VMCRANKCODE
							LEFT JOIN ranklevel rl ON rl.RANKLEVELCODE=r.RANKLEVELCODE
							LEFT JOIN ranktype rt ON rt.RANKTYPECODE=r.RANKTYPECODE
							LEFT JOIN crewscholar cs ON cs.APPLICANTNO=at.APPLICANTNO
							LEFT JOIN scholar s ON s.SCHOLASTICCODE=cs.SCHOLASTICCODE
							LEFT JOIN crewfasttrack cf ON cf.APPLICANTNO=at.APPLICANTNO
							LEFT JOIN fasttrack f ON f.FASTTRACKCODE=cf.FASTTRACKCODE
							WHERE at.ACCEPTDATE IS NOT NULL
        					AND ax.PASSED=1 AND at.STATUS=1
        					$wherepart
        					$whererank
							ORDER BY r.RANKLEVELCODE,r.RANKTYPECODE,r.RANK,NAME
							") or die(mysql_error());

include("include/datasheet.inc");
			

echo "
<html>\n
<head>\n
<title>
Management Approval
</title>

<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<link rel=\"stylesheet\" type=\"text/css\" href=\"veripro_print.css\">
<script type='text/javascript' src='veripro.js'></script>

<body onload=\"\" style=\"overflow:hidden;\">\n

<form name=\"managementapproval\" method=\"POST\">\n

<span class=\"wintitle\">MANAGEMENT APPROVAL</span>

<div style=\"width:1019px;border-bottom:1px solid black;\">

	<div style=\"width:185px;height:650px;float:left;background-color:#999AFF\">
		
		<div style=\"width:100%;height:520px;float:left;overflow:auto;\">
		
			<span class=\"sectiontitle\">LIST OF APPLICANTS</span>
			<br />
			
			<span style=\"font-size:0.8em;font-weight:Bold;\">Group By</span>&nbsp;&nbsp;&nbsp;
			<select name=\"groupby\" onchange=\"ranktypecodehidden.value='';applicantno.value='';submit();\">
				<option $select1 value=\"1\">FOR APPROVAL</option>
				<option $select2 value=\"2\">ON HOLD</option>
<!--							<option $select3 value=\"3\">RESERVED</option>  -->
			</select>
			
			<br /><br />
			
			<table width=\"100%\" class=\"listcol\" style=\"overflow:auto;\">
				<tr>
<!--					<th>APPNO</th>	-->
					<th>NAME</th>
				</tr>
			";
			
			$tmprank = "";

			while ($rowforapproval=mysql_fetch_array($qryforapproval))
			{
				$applicantno1 = $rowforapproval["APPLICANTNO"];
				$name = $rowforapproval["NAME"];
				$rank = $rowforapproval["RANK"];
				$rankcode1 = $rowforapproval["RANKCODE"];
				$ranktypecode1 = $rowforapproval["RANKTYPECODE"];
				$schcode = $rowforapproval["SCHOLASTICCODE"];
				$schtype = $rowforapproval["SCHOLARTYPE"];
				$ftcode = $rowforapproval["FASTTRACKCODE"];
				$fttype = $rowforapproval["FASTTRACKTYPE"];
				
				$scftshow = "";
				
				if (!empty($schcode))
					$scftshow = "(" . $schcode;
					
				if (!empty($ftcode))
					if (!empty($scftshow))
						$scftshow .= "/" . $ftcode;
					else 
						$scftshow = "(" . $ftcode;
					
				if (!empty($schcode) || !empty($ftcode))
					$scftshow .= ")";
				
				if ($tmprank != $rank)
				{
					echo "
					<tr>
						<td align=\"left\" style=\"font-size:1em;font-weight:bold;color:Blue;\"><i>$rank</i></td>
					</tr>
					";
				}
				
				echo "
				<tr $mouseovereffect ondblclick=\"ranktypecodehidden.value='$ranktypecode1';rankcodesel.value='$rankcode1';applicantno.value='$applicantno1';submit();\" style=\"cursor:pointer;\">
				";
				if (!empty($scftshow))
					echo "
<!--						<td style=\"cursor:pointer;\" title=\"$schtype\"><i>$applicantno1</i></td>	-->
						<td style=\"cursor:pointer;\" title=\"$schtype\"><i>$name $scftshow</i></td>
					";
				else 
					echo "
<!--						<td style=\"cursor:pointer;\">$applicantno1</td>	-->
						<td style=\"cursor:pointer;\">$name</td>
					";
				
				echo "
				</tr>
				";
				
				$tmprank = $rank;
			}

echo "
			</table>
			
			
		</div>
	
		<div style=\"width:100%;height:150px;float:left;overflow:auto;background-color:#CCCCFF;\">
		
			<span class=\"sectiontitle\">SEARCH CRITERIA</span>
			
			<center>
			<table width=\"100%\" style=\"margin-top:5px;\">
			<!--
				<tr>
					<td colspan=\"2\" align=\"center\" style=\"font-size:0.8em;font-weight:Bold;\">Rank List</td>
				</tr>
			-->
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
	
	<div style=\"width:825px;height:600px;float:left;background-color:White;\">
	
		<span class=\"sectiontitle\">APPLICATION FORM (PREVIEW)</span>

		<div style=\"width:650px;height:500px;margin:5 5 5 5;border:1px solid black;overflow:auto;float:left;\">
	";
				//#E5FFE6
		if ($applicantno != "")
		{
			echo "
			<iframe marginwidth=0 marginheight=0 id=\"previewdatasheet\" frameborder=\"0\" name=\"showbody\" 
				src=\"repcrewdatasheet.php?applicantno=$applicantno&print=0\" scrolling=\"yes\" style=\"width:100%;height:100%;\">
			</iframe> 
			";
		}
		else 
		{
			echo "<br /><br /><br /><br /><br /><br /><br /><br /><br />
				<center>
					
					<span style=\"font-size:1.2em;font-weight:Bold;color:Blue;\"><i>[ NO PREVIEW ]</i></span>
				
				</center>
			
			";
		}
		
		echo "			
		
		</div>
		
		
		<div style=\"width:160px;background-color:Black;height:470px;margin-top:5px;float:right;\">
		";
			
			$styletitle = "style=\"color:Yellow;font-size:0.85em;font-weight:Bold;\"";
			
			if ($applicantno != "")
			{
				$qrygetremarks = mysql_query("SELECT APPLICANTNO,ACCEPTBY,ACCEPTDATE,REMARKS AS FLEETREM, 
											DIVBY,DIVDATE,DIVREMARKS
											FROM applicantstatus WHERE APPLICANTNO=$applicantno") or die(mysql_error());
				
				$rowgetremarks = mysql_fetch_array($qrygetremarks);
				
				$fleetacceptby = $rowgetremarks["ACCEPTBY"];
				
				if ($rowgetremarks["ACCEPTDATE"] != "")
					$fleetacceptdate = "[" . date("dMY",strtotime($rowgetremarks["ACCEPTDATE"])) . "]";
				else 
					$fleetacceptdate = "";
					
				$fleetremarks = $rowgetremarks["FLEETREM"];
				
				$divby = $rowgetremarks["DIVBY"];
				
				if ($rowgetremarks["DIVDATE"] != "")
					$divdate = "[" . date("dMY",strtotime($rowgetremarks["DIVDATE"])) . "]";
				else 
					$divdate = "";
					
				$divremarks = $rowgetremarks["DIVREMARKS"];
			}
			
		echo "
			<span class=\"sectiontitle\">FLEET</span>
			<br />
			
			<div style=\"width:100%;height:45%;\">
				<table style=\"width:100%;font-size:0.8em;font-weight:Bold;color:White;\">
					<tr>
						<td $styletitle>Approved By:</td>
					</tr>
					<tr>
						<td>$fleetacceptby $fleetacceptdate </td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td $styletitle>Remarks:</td>
					</tr>
					<tr>
						<td>$fleetremarks</td>
					</tr>
				</table>
			</div>
			
			<span class=\"sectiontitle\">DIVISION</span>
			<br />
			
			<div style=\"width:100%;height:45%;\">
				<table style=\"width:100%;font-size:0.8em;font-weight:Bold;color:White;\">
					<tr>
						<td $styletitle>Approved By:</td>
					</tr>
					<tr>
						<td>$divby $divdate</td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td $styletitle>Remarks:</td>
					</tr>
					<tr>
						<td>$divremarks</td>
					</tr>
				</table>
			</div>
			
		</div>

		<div style=\"width:815px;height:140px;background-color:Black;overflow:hidden;margin: 0 0 0 10;\" >
			<table style=\"color:White;font-size:0.7em;font-weight:Bold;width:30%;float:left;\">
				<tr>
					<td>Remarks<br />
						<textarea rows=\"6\" cols=\"40\" name=\"vmcremarks\">$vmcremarks</textarea>
					</td>
				</tr>
			</table>
			
			<table style=\"width:50%;height:60px;margin:15px;float:right;\" cellpadding=\"0\" cellspacing=\"0\">
				<tr>
					<td colspan=\"3\">&nbsp;</td>
				</tr>
				<tr height=\"40px;\">
					<td style=\"width:50%;background-color:Green;\" >
						<input type=\"button\" value=\"APPROVE\" $disablebuttons style=\"border:1px solid White;width:100%;height:100%;cursor:pointer;font-size:1.3em;font-weight:Bold;color:White;background:inherit;\" 
										onclick=\"if(confirm('Approve Applicant?')){actiontxt.value='approve';submit();}\" />
					</td>
					<td style=\"width:50%;background-color:Red;\" >
						<input type=\"button\" value=\"HOLD\" $disablebuttons style=\"border:1px solid White;width:100%;height:100%;cursor:pointer;font-size:1.3em;font-weight:Bold;color:White;background:inherit;\" 
										onclick=\"if(confirm('Put Applicant ON HOLD?')){actiontxt.value='hold';submit();}\" />
					</td>
				</tr>
			
			</table>
		
		</div>
	
	</div>
</div>
";
				

echo "
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"rankcodesel\" />
	<input type=\"hidden\" name=\"doctype\" />
	<input type=\"hidden\" name=\"ranktypecodehidden\" value=\"$ranktypecodehidden\" />
	<input type=\"hidden\" name=\"applicantno\" value=\"$applicantno\" />

</form>

";

?>