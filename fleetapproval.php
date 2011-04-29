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

if (isset($_POST['remarks']))
	$remarks = $_POST['remarks'];

if (isset($_POST['applicantno']))
	$applicantno = $_POST['applicantno'];

if (isset($_POST['crewname']))
	$crewname = $_POST['crewname'];
	
if (isset($_POST['rankcode']))
	$rankcode = $_POST['rankcode'];
else 
	$rankcode = "All";
	
if (isset($_POST['statusrankcode']))
	$statusrankcode = $_POST['statusrankcode'];
else 
	$statusrankcode = "All";
	
// if (isset($_POST['rankcodesel']))
	// $rankcodesel = $_POST['rankcodesel'];	
	
if (isset($_POST['vmcrankcode']))
	$vmcrankcode = $_POST['vmcrankcode'];
	
if (isset($_POST['vmcvslcode']))
	$vmcvslcode = $_POST['vmcvslcode'];
	
if (isset($_POST['vmcetd']))
	$vmcetd = $_POST['vmcetd'];

//FOR RECOMMENDED VESSEL SECTION -- 2009-03-17

if (isset($_POST["selrankcode"]))
	$selrankcode = $_POST["selrankcode"];
	
if (isset($_POST["selvesselcode"]))
	$selvesselcode = $_POST["selvesselcode"];
	
if (isset($_POST["seletd"]))
	$seletd = $_POST["seletd"];
	
//FOR CREW CHANGE UPDATE
if (isset($_POST['getccid']))
	$getccid = $_POST['getccid'];

if (isset($_POST['getdateemb']))
	$getdateemb = $_POST['getdateemb'];

if (isset($_POST['getdatedisemb']))
	$getdatedisemb = $_POST['getdatedisemb'];

if (isset($_POST['getapplicantno']))
	$getapplicantno = $_POST['getapplicantno'];
	
if(empty($getapplicantno))
	$getapplicantno=$applicantno;
	
if (isset($_POST["assignedccidhidden"]))
	$assignedccidhidden = $_POST["assignedccidhidden"];
	
	
	
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

	if($vmcrankcode != "")
	{
		$qrygetranktype = mysql_query("SELECT RANKTYPECODE FROM rank WHERE RANKCODE='$vmcrankcode'") or die(mysql_error());
		
		$rowranktype = mysql_fetch_array($qrygetranktype);
		$ranktypecode = $rowranktype["RANKTYPECODE"];
	}
	
	switch ($actiontxt)
	{
		case "ok"	:
			
				$qryverify = mysql_query("SELECT APPLICANTNO FROM applicantstatus WHERE APPLICANTNO=$applicantno") or die(mysql_error());
				
				if(!empty($vmcetd))
					$vmcetdraw = "'" . date("Y-m-d",strtotime($vmcetd)) . "'";
				else 
					$vmcetdraw = "NULL";
				
				if (mysql_num_rows($qryverify) > 0)
				{
					$qryappstatusupdate = mysql_query("UPDATE applicantstatus SET ACCEPTBY='$employeeid', 
																				ACCEPTDATE='$currentdate',
																				REMARKS='$remarks',
																				VMCRANKCODE='$vmcrankcode',
																				VMCVESSELCODE='$vmcvslcode',
																				VMCETD=$vmcetdraw
													WHERE APPLICANTNO=$applicantno") or die(mysql_error());
				}
//						else 
//						{
//							$qryappstatusinsert = mysql_query("INSERT INTO applicantstatus(APPLICANTNO,ACCEPTBY,ACCEPTDATE,REMARKS,VMCRANKCODE,VMCVESSELCODE) 
//															VALUES($applicantno,'$employeeid','$currentdate','$remarks','$vmcrankcode','$vmcvslcode')") or die(mysql_error());
//						}
				
				$qrycrewranktype = mysql_query("UPDATE crew SET RANKTYPECODE='$ranktypecode' WHERE APPLICANTNO=$applicantno") or die(mysql_error());
				
				$applicantno = "";
				$remarks = "";
				$vmcvslcode = "";
			
			break;
		case "activefile"	:
			
				$qryverify = mysql_query("SELECT APPLICANTNO FROM applicantstatus WHERE APPLICANTNO=$applicantno") or die(mysql_error());
				
				if (mysql_num_rows($qryverify) > 0)
				{
					$qryappstatusupdate = mysql_query("UPDATE applicantstatus SET ACTIVEFILEBY='$employeeid',
																				ACTIVEFILEDATE='$currentdate', 
																				REMARKS='$remarks',
													WHERE APPLICANTNO=$applicantno") or die(mysql_error());
				}
//						else 
//						{
//							$qryappstatusinsert = mysql_query("INSERT INTO applicantstatus(APPLICANTNO,ACTIVEFILEBY,ACTIVEFILEDATE,REMARKS,VMCRANKCODE,VMCVESSELCODE) 
//															VALUES($applicantno,'$employeeid','$currentdate','$remarks','$vmcrankcode','$vmcvslcode')") or die(mysql_error());
//						}
				
				$qrycrewranktype = mysql_query("UPDATE crew SET RANKTYPECODE='$ranktypecode' WHERE APPLICANTNO=$applicantno") or die(mysql_error());
				
				$applicantno = "";
				$remarks = "";
				$vmcvslcode = "";
			
			break;
		case "reserve"	:
			
				$qryverify = mysql_query("SELECT APPLICANTNO FROM applicantstatus WHERE APPLICANTNO=$applicantno") or die(mysql_error());
				
				if (mysql_num_rows($qryverify) > 0)
				{
					$qryappstatusupdate = mysql_query("UPDATE applicantstatus SET RESERVEDBY='$employeeid',
																				RESERVEDDATE='$currentdate', 
																				REMARKS='$remarks'
													WHERE APPLICANTNO=$applicantno") or die(mysql_error());
				}
//						else 
//						{
//							$qryappstatusinsert = mysql_query("INSERT INTO applicantstatus(APPLICANTNO,RESERVEDBY,RESERVEDDATE,REMARKS) 
//															VALUES($applicantno,'$employeeid','$currentdate','$remarks')") or die(mysql_error());
//						}
				
				$qrycrewranktype = mysql_query("UPDATE crew SET RANKTYPECODE='$ranktypecode' WHERE APPLICANTNO=$applicantno") or die(mysql_error());
				
				$applicantno = "";
				$remarks = "";
				$vmcvslcode = "";
			
			break;
			
		case "updatecrewchange":
			$getdateembraw=date("Y-m-d",strtotime($getdateemb));
			$getdatedisembraw=date("Y-m-d",strtotime($getdatedisemb));
			if(empty($assignedccidhidden))
			{
				$qrysave=mysql_query("INSERT INTO crewchange (APPLICANTNO,BATCHNO,VESSELCODE,RANKCODE,DATEEMB,DATEDISEMB,
					MADEBY,MADEDATE) VALUES($getapplicantno,999,'$vmcvslcode','$vmcrankcode','$getdateembraw','$getdatedisembraw',
					'$employeeid','$currentdate')") or die(mysql_error());
				$qryfind=mysql_query("SELECT CCID FROM crewchange 
					WHERE MADEBY='$employeeid' AND MADEDATE='$currentdate'") or die(mysql_error());
				$rowfind=mysql_fetch_array($qryfind);
				$newccid=$rowfind["CCID"];
				$qryadd=mysql_query("INSERT INTO crewchangerelation (CCID,CCIDEMB) VALUES($getccid,$newccid)") or die(mysql_error());
			}
			else 
			{
				$qrysave=mysql_query("UPDATE crewchange SET VESSELCODE='$selvesselcode',RANKCODE='$selrankcode',
					DATEEMB='$getdateembraw',DATEDISEMB='$getdatedisembraw',
					MADEBY='$employeeid',MADEDATE='$currentdate'
					WHERE CCID=$assignedccidhidden") or die(mysql_error());
				$qrydelrelation=mysql_query("DELETE FROM crewchangerelation WHERE CCIDEMB=$assignedccidhidden")or die(mysql_error());
				$qryadd=mysql_query("INSERT INTO crewchangerelation (CCID,CCIDEMB) VALUES($getccid,$assignedccidhidden)") or die(mysql_error());
			}
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
	
	
	switch ($groupby)
	{
		case "1"	:  //NEW APPLICANTS
			
				$wherepart = "AND RESERVEDDATE IS NULL AND ACTIVEFILEDATE IS NULL";
				$select1 = "SELECTED";
			
			break;
		case "2"	:  //ACTIVE FILE
			
				$wherepart = "AND RESERVEDDATE IS NULL AND ACTIVEFILEDATE IS NOT NULL";
				$select2 = "SELECTED";
			
			break;
		case "3"	:  //RESERVED
			
				$wherepart = "AND RESERVEDDATE IS NOT NULL AND ACTIVEFILEDATE IS NULL";
				$select3 = "SELECTED";
			
			break;
	}
	
	$qryranklist=mysql_query("SELECT LEFT(RANK,18) AS RANK,RANKCODE FROM rank WHERE STATUS=1 ORDER BY RANKING") or die(mysql_error());
	$qryasranklist=mysql_query("SELECT RANK,RANKCODE FROM rank WHERE STATUS=1 ORDER BY RANKING") or die(mysql_error());
	
	//FOR ASSIGNED VESSEL (RECOMMENDATION)
	$qryvessel = mysql_query("SELECT VESSELCODE,LEFT(VESSEL,15) AS VESSEL FROM vessel WHERE STATUS=1 ORDER BY VESSEL") or die(mysql_error());
	$qryrank = mysql_query("SELECT RANKCODE,LEFT(RANK,15) AS RANK FROM rank WHERE STATUS=1 ORDER BY RANKING") or die(mysql_error());
	
	$qryvessellist = mysql_query("SELECT VESSELCODE,VESSEL FROM vessel WHERE STATUS=1 ORDER BY VESSEL") or die(mysql_error());

	$qryapplicants = mysql_query("SELECT ap.APPLICANTNO,CONCAT(c.FNAME,', ',GNAME,' ',MNAME) AS NAME,r.RANKCODE,
							ap.VMCRANKCODE,r.RANK,r.ALIAS1,r.RANKLEVELCODE,r.RANKTYPECODE,
							s.SCHOLASTICCODE,s.DESCRIPTION AS SCHOLARTYPE,f.FASTTRACKCODE,f.FASTTRACK
							FROM applicantstatus ap
							LEFT JOIN crew c ON c.APPLICANTNO=ap.APPLICANTNO
							LEFT JOIN rank r ON r.RANKCODE=ap.VMCRANKCODE
							LEFT JOIN ranklevel rl ON rl.RANKLEVELCODE=r.RANKLEVELCODE
							LEFT JOIN ranktype rt ON rt.RANKTYPECODE=r.RANKTYPECODE
							LEFT JOIN crewscholar cs ON cs.APPLICANTNO=ap.APPLICANTNO
							LEFT JOIN scholar s ON s.SCHOLASTICCODE=cs.SCHOLASTICCODE
							LEFT JOIN crewfasttrack cf ON cf.APPLICANTNO=ap.APPLICANTNO
							LEFT JOIN fasttrack f ON f.FASTTRACKCODE=cf.FASTTRACKCODE
							WHERE ap.CHARCHECKDATE IS NOT NULL AND (ap.ENCODEDDATE IS NOT NULL OR cs.APPLICANTNO IS NOT NULL)
							AND ACCEPTDATE IS NULL AND ap.STATUS=1
							$wherepart
							$whererank
							ORDER BY r.RANKLEVELCODE,r.RANKTYPECODE,r.RANK,NAME") or die(mysql_error());
	
	
	
include("include/datasheet.inc");


echo "
<html>\n
<head>\n
<title>
Fleet Approval
</title>

<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<link rel=\"stylesheet\" type=\"text/css\" href=\"veripro_print.css\">
<script type='text/javascript' src='veripro.js'></script>
<script language=\"javascript\" src=\"popcalendar.js\"></script>
<script language=\"JavaScript\"> 
function updatecrew()
{
	var rem='';
	var getvalue='';
	with(document.fleetapproval)
	{
		var getvesselcode=vmcvslcode.value;
		var getrankcode=vmcrankcode.value;
		var getetd=vmcetd.value.replace('/','_');
		if(getvesselcode=='')
		{
			rem='Vessel';
		}
		if(getrankcode=='')
		{
			if(rem=='')
				rem='Rank';
			else
				rem=rem + ', Rank';
		}
		if(rem=='')
		{
			getvalue=window.showModalDialog('crewtrainingendorsemodal.php?selvesselcode='+getvesselcode+'&selrankcode='+getrankcode+'&seletd='+getetd+'&crewname=$crewname&applicantno=$applicantno','',
				'dialogHeight:350px; dialogWidth:700px;status=no;');
		}
		else
			alert(rem + ' should not be NULL!');
		
		if(getvalue!='')
		{
			var getarray=getvalue.split('|');
			getccid.value=getarray[0];
			getdateemb.value=getarray[1];
			getdatedisemb.value=getarray[2];
			actiontxt.value='updatecrewchange';
			submit();
		}
	}
}
</script>
<body onload=\"\" style=\"overflow:hidden;\">\n

<form name=\"fleetapproval\" method=\"POST\">\n

<span class=\"wintitle\">FLEET APPROVAL</span>

<div style=\"width:1019px;border-bottom:1px solid black;\">

	<div style=\"width:185px;height:650px;float:left;background-color:#999AFF\">
		
		<div style=\"width:100%;height:520px;float:left;overflow:auto;\">
		
			<span class=\"sectiontitle\">LIST OF APPLICANTS</span>
			<br />
			
			<span style=\"font-size:0.8em;font-weight:Bold;\">Group By</span>&nbsp;&nbsp;&nbsp;
			<select name=\"groupby\" onchange=\"applicantno.value='';submit();\">
				<option $select1 value=\"1\">NEW APPLICANTS</option>
				<option $select2 value=\"2\">ACTIVE FILE</option>
				<option $select3 value=\"3\">RESERVED</option>
			</select>
			
			<br /><br />
			
			<table class=\"listcol\" style=\"width:100%;overflow:auto;\">
				<tr>
<!--					<th>APPNO</th>	-->
					<th>NAME</th>
				</tr>
			";
			
			$tmprank = "";

			while ($rowapplicants=mysql_fetch_array($qryapplicants))
			{
				$applicantno1 = $rowapplicants["APPLICANTNO"];
				$name = $rowapplicants["NAME"];
				$rank = $rowapplicants["RANK"];
				$rankcode1 = $rowapplicants["RANKCODE"];
				$alias1 = $rowapplicants["ALIAS1"];
				$schcode = $rowapplicants["SCHOLASTICCODE"];
				$schtype = $rowapplicants["SCHOLARTYPE"];
				$ftcode = $rowapplicants["FASTTRACKCODE"];
				$fttype = $rowapplicants["FASTTRACKTYPE"];
				
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
				<tr $mouseovereffect ondblclick=\"vmcrankcode.value='$rankcode1';crewname.value='$name';applicantno.value='$applicantno1';submit();\" style=\"cursor:pointer;\">
				";
				if (!empty($scftshow))
					echo "
<!--						<td style=\"cursor:pointer;\" title=\"$schtype\"><b>$applicantno1</b></td>	-->
						<td style=\"cursor:pointer;\" title=\"$schtype\"><b>$name $scftshow</b></td>
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

		<div style=\"width:820px;height:430px;margin:5 5 5 5;border:1px solid black;overflow:auto;\">
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

		<div style=\"width:820px;height:220px;background-color:Black;margin:5 5 5 5;overflow:auto;\" >
			<table style=\"width:100%;color:White;font-weight:Bold;\" border=1>
				<tr>
					<td>Remarks<br />&nbsp;&nbsp;
						<textarea rows=\"7\" cols=\"40\" name=\"remarks\" $disablebuttons >$remarks</textarea>
					</td>
					
					<td align=\"left\">";
					
					$stylehdr = "style=\"font-size:0.9em;font-weight:Bold;color:White;\"";
					$styledtl2 = "style=\"font-size:0.9em;font-weight:Bold;color:Yellow;\"";
					
					echo "
						<table style=\"width:90%;\">
							<tr>
								<td colspan=\"3\" style=\"font-size:0.8em;font-weight:Bold;color:Magenta;text-align:center;\">
									<i>Note: Any changes made will directly affect CREW CHANGE PLAN</i>
								</td>
							</tr>
					
							<tr>
								<td $stylehdr>ASSIGNED VESSEL</td>
								<td $stylehdr>:</td>
								<td $styledtl2>
									<select name=\"vmcvslcode\" style=\"font-size:0.8em;\" $disableonboard>
										<option value=\"\">--Select Vessel--</option>
									";
				
										while($rowvessel=mysql_fetch_array($qryvessel))
										{
											$vslcode=$rowvessel['VESSELCODE'];
											$vsl=$rowvessel['VESSEL'];
											
											$selectvessel = "";
											if ($vslcode == $vmcvslcode)
												$selectvessel = "SELECTED";
												
											echo "<option $selectvessel value=\"$vslcode\">$vsl</option>";
										}
									
							echo "
									</select>
								
								</td>
							</tr>
							<tr>
								<td $stylehdr>ASSIGNED RANK</td>
								<td $stylehdr>:</td>
								<td $styledtl2>
									<select name=\"vmcrankcode\" style=\"font-size:0.8em;\" $disableonboard>
										<option value=\"\">--Select Rank--</option>
									";
										
										while($rowrank=mysql_fetch_array($qryrank))
										{
											$rnk=$rowrank['RANK'];
											$rnkcode=$rowrank['RANKCODE'];
											
											$selectrank = "";
											if ($rnkcode == $vmcrankcode)
												$selectrank = "SELECTED";
												
											echo "<option $selectrank value=\"$rnkcode\">$rnk</option>";
										}
										echo "
									</select>
								
								</td>
							</tr>
							<tr>
								<td $stylehdr valign=\"top\">ETD</td>
								<td $stylehdr valign=\"top\">:</td>
								<td $styledtl2>
								";
									
								echo "
									<input type=\"text\" name=\"vmcetd\" value=\"$vmcetd\" $disablebuttons onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
									&nbsp;&nbsp;&nbsp;<i>(mm/dd/yyyy)</i>
									<br />
									<!--
									<input $disableonboard type=\"text\" name=\"seletd\" value=\"$assignedetd2\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
									<img $disableonboard src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(seletd, seletd, 'mm/dd/yyyy', 0, 0);return false;\">
									&nbsp;&nbsp;&nbsp;
									
									-->
									
									<input type=\"button\" value=\"Update\" onclick=\"updatecrew();\" $disableonboard
										style=\"font-size:0.8em;font-weight:Bold;color:Lime;background-color:Black;border:thin solid white;cursor:pointer;\" />
									
								</td>
							</tr>
						</table>
					";

				// echo "
						// <table width=\"100%\">
							// <tr>
								// <td style=\"color:White;font-weight:Bold;\">Recommended Vessel</td>							
								// <td>
									// <select name=\"vmcvslcode\" $disablebuttons>
										// <option value=\"\">--Select One--</option>
						// ";
									
									// while($rowvessellist=mysql_fetch_array($qryvessellist))
									// {
										// $vessel=$rowvessellist['VESSEL'];
										// $vesselcode=$rowvessellist['VESSELCODE'];
										
										// $selectvessel = "";
										// if ($vmcvslcode == $vesselcode)
											// $selectvessel = "SELECTED";
											
										// echo "<option $selectvessel value=\"$vesselcode\">$vessel</option>";
									// }
				// echo "		
									// </select>
								// </td>							
							// </tr>
							// <tr>
								// <td style=\"color:White;font-weight:Bold;\">Estimated Departure</td>
								// <td style=\"color:White;font-weight:Bold;\">
									// <input type=\"text\" name=\"vmcetd\" value=\"$vmcetd\" $disablebuttons onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
									// &nbsp;&nbsp;&nbsp;<i>(mm/dd/yyyy)</i>
								// </td>
							// </tr>
							// <tr>
								// <td style=\"color:White;font-weight:Bold;\">As Rank</td>
								// <td>
									// <select name=\"vmcrankcode\" $disablebuttons >
										// <option value=\"\">--Select One--</option>
						// ";
									
									// while($rowasranklist=mysql_fetch_array($qryasranklist))
									// {
										// $asrank1=$rowasranklist['RANK'];
										// $asrankcode1=$rowasranklist['RANKCODE'];
										
										// $selectasrank = "";
										// if ($rankcodesel == $asrankcode1)
											// $selectasrank = "SELECTED";
											
										// echo "<option $selectasrank value=\"$asrankcode1\">$asrank1</option>";
									// }
				
				// echo "		
									// </select>
								// </td>
							// </tr>
						// </table>";
							
				
				echo "
					</td>
				</tr>
			</table>
			
			<table width=\"100%\">
				<tr height=\"40px;\" align=\"center\">
					<td style=\"width:33%;background-color:Green;\" >
						<input type=\"button\" value=\"OK for Sets\" $disablebuttons style=\"border:1px solid White;width:100%;height:100%;cursor:pointer;font-size:1.3em;font-weight:Bold;color:White;background:inherit;\" 
										onclick=\"if(confirm('Applicant No. $applicantno: $crewname will be endorsed to the Training Department for examination. Continue?'))
												{if(vmcrankcode.value != '' && vmcetd.value != '' && vmcvslcode != ''){actiontxt.value='ok';submit();}
												else {alert('Please make sure to fill up the following: Recommended Vessel; ETD; As Rank.');}}\" />
					</td>
					<td style=\"width:33%;background-color:Blue;\" >
						<input type=\"button\" value=\"ACTIVE FILE\" $disablebuttons style=\"border:1px solid White;width:100%;height:100%;cursor:pointer;font-size:1.3em;font-weight:Bold;color:White;background:inherit;\" 
										onclick=\"actiontxt.value='activefile';submit();\" />
					</td>
					<td style=\"width:33%;background-color:Red;\" >
						<input type=\"button\" value=\"RESERVE\" $disablebuttons style=\"border:1px solid White;width:100%;height:100%;cursor:pointer;font-size:1.3em;font-weight:Bold;color:White;background:inherit;\" 
										onclick=\"actiontxt.value='reserve';submit();\" />
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
	<input type=\"hidden\" name=\"crewname\" />
	<input type=\"hidden\" name=\"applicantno\" value=\"$applicantno\" />
	
	<input type=\"hidden\" name=\"getccid\" />
	<input type=\"hidden\" name=\"getdateemb\" />
	<input type=\"hidden\" name=\"getdatedisemb\" />
	<input type=\"hidden\" name=\"getapplicantno\" value=\"$getapplicantno\" />
	
	<input type=\"hidden\" name=\"assignedccidhidden\" value=\"$assignedccid\"/>

</form>

";

?>