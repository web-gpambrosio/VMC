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
				
					$qrysearch = mysql_query("SELECT * FROM (
								SELECT cc.APPLICANTNO,
								IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,
								cc.ARRMNLDATE,dh.STATUS AS DEBRIEFSTATUS,
								CONCAT(c.FNAME,', ',c.GNAME,' ',c.MNAME) AS NAME,c.CREWCODE
								FROM crewchange cc
								LEFT JOIN crew c ON c.APPLICANTNO=cc.APPLICANTNO
								LEFT JOIN debriefinghdr dh ON dh.CCID=cc.CCID
								WHERE dh.STATUS IS NULL OR dh.STATUS = 0
								ORDER BY DATEDISEMB DESC
							) x
							WHERE DATEDISEMB BETWEEN '2007-01-01' AND CURRENT_DATE
							AND APPLICANTNO LIKE '$searchkey%'
							GROUP BY APPLICANTNO
						") or die(mysql_error());
				
				break;
			case "2"	: //CREW CODE
				
					$qrysearch = mysql_query("SELECT * FROM (
								SELECT cc.APPLICANTNO,c.FNAME,c.GNAME,
								IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,
								cc.ARRMNLDATE,dh.STATUS AS DEBRIEFSTATUS,
								CONCAT(c.FNAME,', ',c.GNAME,' ',c.MNAME) AS NAME,c.CREWCODE
								FROM crewchange cc
								LEFT JOIN crew c ON c.APPLICANTNO=cc.APPLICANTNO
								LEFT JOIN debriefinghdr dh ON dh.CCID=cc.CCID
								WHERE dh.STATUS IS NULL OR dh.STATUS = 0
								ORDER BY DATEDISEMB DESC
							) x
							WHERE DATEDISEMB BETWEEN '2007-01-01' AND CURRENT_DATE
							AND CREWCODE LIKE '$searchkey%'
							GROUP BY APPLICANTNO
						") or die(mysql_error());
				
				break;
			case "3"	: //FAMILY NAME
				
					$qrysearch = mysql_query("SELECT * FROM (
								SELECT cc.APPLICANTNO,c.FNAME,c.GNAME,
								IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,
								cc.ARRMNLDATE,dh.STATUS AS DEBRIEFSTATUS,
								CONCAT(c.FNAME,', ',c.GNAME,' ',c.MNAME) AS NAME,c.CREWCODE
								FROM crewchange cc
								LEFT JOIN crew c ON c.APPLICANTNO=cc.APPLICANTNO
								LEFT JOIN debriefinghdr dh ON dh.CCID=cc.CCID
								WHERE dh.STATUS IS NULL OR dh.STATUS = 0
								ORDER BY DATEDISEMB DESC
							) x
							WHERE DATEDISEMB BETWEEN '2007-01-01' AND CURRENT_DATE
							AND FNAME LIKE '$searchkey%'
							GROUP BY APPLICANTNO
						") or die(mysql_error());
				
				break;
			case "4"	: //GIVEN NAME
				
					$qrysearch = mysql_query("SELECT * FROM (
								SELECT cc.APPLICANTNO,c.FNAME,c.GNAME,
								IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,
								cc.ARRMNLDATE,dh.STATUS AS DEBRIEFSTATUS,
								CONCAT(c.FNAME,', ',c.GNAME,' ',c.MNAME) AS NAME,c.CREWCODE
								FROM crewchange cc
								LEFT JOIN crew c ON c.APPLICANTNO=cc.APPLICANTNO
								LEFT JOIN debriefinghdr dh ON dh.CCID=cc.CCID
								WHERE dh.STATUS IS NULL OR dh.STATUS = 0
								ORDER BY DATEDISEMB DESC
							) x
							WHERE DATEDISEMB BETWEEN '2007-01-01' AND CURRENT_DATE
							AND GNAME LIKE '$searchkey%'
							GROUP BY APPLICANTNO
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
		
	break;
}
	

//switch ($groupby)
//{
//	case "1"	:  //PER RANK
//		
//			//FOR RANK LISTING
//			
//				$rankselectM = "";
//				$rankselectO = "";
//				$rankselectS = "";
//				$rankselectD = "";
//				$rankselectE = "";
//				$rankselectT = "";
//				$rankselectALL = "";
//				
//				$whererank = "";
//				
//				
//				
//				switch ($byrank)
//				{
//					case "M"	:  //MANAGEMENT
//						
//							$whererank = "AND x.RANKLEVELCODE='M'";
//							$rankselectM = "SELECTED";
//							
//						break;
//					case "O"	:  //OPERATIONAL
//						
//							$whererank = "AND x.RANKLEVELCODE='O'";
//							$rankselectO = "SELECTED";
//							
//						break;
//					case "S"	:  //SUPPORT
//						
//							$whererank = "AND x.RANKLEVELCODE='S'";
//							$rankselectS = "SELECTED";
//							
//						break;
//					case "D"	:  //DECK
//						
//							$whererank = "AND x.RANKTYPECODE='D'";
//							$rankselectD = "SELECTED";
//					
//						break;
//					case "E"	:  //ENGINE
//						
//							$whererank = "AND x.RANKTYPECODE='E'";
//							$rankselectE = "SELECTED";
//							
//						break;
//					case "T"	:  //STEWARD
//						
//							$whererank = "AND x.RANKTYPECODE='S'";
//							$rankselectT = "SELECTED";
//							
//						break;
//					
//					case "All"	:  //ALL RANKS
//						
//							$whererank = "";
//							$rankselectALL = "SELECTED";
//							
//						break;
//					
//					default		: //ANY RANKCODE
//					
//							$whererank = "AND x.RANKCODE='$byrank'";
//							
//						break;
//				}
//			
//			//END OF RANK LISTING
//			
//			$qryranklist = mysql_query("SELECT RANK,RANKCODE FROM rank WHERE STATUS=1 ORDER BY RANKING") or die(mysql_error());	
//
//	
//			$orderpart = "ORDER BY RANKLEVELCODE,RANKTYPECODE,RANKING,DATEDISEMB DESC";
//			$select1 = "SELECTED";
//		
//		break;
//	case "2"	:  //PER VESSEL
//	
//	
//			$wherevessel = "";
//			
//			if (!empty($byvessel))
//			{
//				$wherevessel = "AND VESSELCODE='$byvessel'";
//			}
//			
//			$qryvessellist = mysql_query("SELECT VESSELCODE,VESSEL FROM vessel WHERE STATUS=1 $wherediv ORDER BY VESSEL") or die(mysql_error());
//			
//			$orderpart = "ORDER BY VESSELNAME,RANKING,DATEDISEMB DESC";
//			$select2 = "SELECTED";
//		
//		break;
//}


// NOTE : After recon, change WHERE part to DATEEMB > CURRENT_DATE   (LEFT JOIN crewpromotionrelation cpr ON cpr.CCIDPROMOTE=cc.CCID)

//$qryarrivelist = mysql_query("SELECT * FROM (
//									SELECT cc.APPLICANTNO,
//									IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,
//									cc.ARRMNLDATE,dh.STATUS AS DEBRIEFSTATUS,
//									CONCAT(c.FNAME,', ',c.GNAME,' ',c.MNAME) AS NAME,c.CREWCODE,
//									LEFT(v.ALIAS2,12) AS VESSEL,v.VESSELCODE,v.VESSEL AS VESSELNAME,r.ALIAS1 AS RANKALIAS,r.RANKCODE,
//									r.RANKLEVELCODE,r.RANKTYPECODE,r.RANK,r.RANKING,
//									s.SCHOLASTICCODE,s.DESCRIPTION AS SCHOLARTYPE,f.FASTTRACKCODE,f.FASTTRACK
//									FROM crewchange cc
//									LEFT JOIN crew c ON c.APPLICANTNO=cc.APPLICANTNO
//									LEFT JOIN rank r ON r.RANKCODE=cc.RANKCODE
//									LEFT JOIN ranklevel rl ON rl.RANKLEVELCODE=r.RANKLEVELCODE
//									LEFT JOIN ranktype rt ON rt.RANKTYPECODE=r.RANKTYPECODE
//									LEFT JOIN vessel v ON v.VESSELCODE=cc.VESSELCODE
//									LEFT JOIN crewscholar cs ON cs.APPLICANTNO=cc.APPLICANTNO
//									LEFT JOIN scholar s ON s.SCHOLASTICCODE=cs.SCHOLASTICCODE
//									LEFT JOIN crewfasttrack cf ON cf.APPLICANTNO=cc.APPLICANTNO
//									LEFT JOIN fasttrack f ON f.FASTTRACKCODE=cf.FASTTRACKCODE
//									LEFT JOIN debriefinghdr dh ON dh.CCID=cc.CCID
//									WHERE dh.STATUS IS NULL OR dh.STATUS = 0
//								) x
//							WHERE DATEDISEMB BETWEEN '2007-01-01' AND CURRENT_DATE
//							$wherevessel
//							$whererank
//							$orderpart
//							") or die(mysql_error());


	$crewname = "";
	$crewcode = "";
	$crewvessel = "";
	$crewrank = "";
	$crewscholar = "";
	$crewfastrack =  "";
	$errormsg = "";

if (!empty($applicantno))
{
	$qrycrewdtls = mysql_query("SELECT x.* FROM (
									SELECT cc.APPLICANTNO,cc.CCID,
									IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,
									cc.ARRMNLDATE,cc.DEPMNLDATE,
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
								) x
								LEFT JOIN crewpromotionrelation cpr ON cpr.CCIDPROMOTE=x.CCID
								WHERE DATEDISEMB BETWEEN '2007-01-01' AND CURRENT_DATE
								AND (DEPMNLDATE IS NOT NULL OR cpr.CCID IS NOT NULL)
								ORDER BY DATEDISEMB DESC
								LIMIT 1
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
		// $debriefstatus = $rowcrewdtls["DEBRIEFSTATUS"];
		
//		$arrmnldate = $rowcrewdtls["ARRMNLDATE"];
		
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
<title>Debriefing - Arriving Seaman</title>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>

<body onload=\"\" style=\"overflow:hidden;background-color:White;\">\n

<form name=\"debriefing\" method=\"POST\">\n

<span class=\"wintitle\">DEBRIEFING - ARRIVING SEAMAN</span>

<div style=\"width:100%;background-color:White;\">

<!--
	<div style=\"width:30%;height:650px;float:left;padding:10 5 0 5;background-color:White;\">
	
		<span class=\"sectiontitle\">ARRIVING CREW</span>
		
		<div style=\"width:100%;height:40px;background-color:White;border:1px solid Black;\">
			<br />
			<table class=\"setup\" width=\"100%\">
				<tr>
					<th>Group By</th>
					<th>:</th>
					<td><select name=\"groupby\" onchange=\"submit();\">
							<option $select1 value=\"1\">PER RANK</option>
							<option $select2 value=\"2\">PER VESSEL</option>
						</select>
					
					</td>
				</tr>
			
			</table>
		</div>
		
		<div style=\"width:100%;height:550px;overflow:auto;background-color:#DCDCDC;border:1px solid Black;\">
		";

//		$stylehdr = "style=\"font-weight:Bold;background-color:Blue;color:White;text-align:center;\"";
//		$styleborder = "style=\"border-right:1px dashed Black;\"";
//		
//		switch ($groupby)
//		{
//			case "1"	:  //PER RANK
//				
//					echo "
//						<table width=\"100%\" class=\"listcol\">
//							<tr>
//								<th width=\"40%\">NAME</th>
//								<th width=\"25%\">DISEMBARK</th>
//								<th width=\"35%\">VESSEL</th>
//							</tr>
//						";
//						
//						$tmprankcode = "";
//						
//						while ($rowembarklist = mysql_fetch_array($qryarrivelist))
//						{
//							$applicantno1 = $rowembarklist["APPLICANTNO"];
//							$xcrewname = $rowembarklist["NAME"];
//							if (!empty($rowembarklist["DATEDISEMB"]))
//								$datedisemb = date("dMY",strtotime($rowembarklist["DATEDISEMB"]));
//							else 
//								$datedisemb = "";
//							$rankcode = $rowembarklist["RANKCODE"];
//							$rankalias = $rowembarklist["RANKALIAS"];
//							$rank = $rowembarklist["RANK"];
//							$vessel = $rowembarklist["VESSEL"];
//							$scholartype = $rowembarklist["SCHOLARTYPE"];
//							$fasttrack = $rowembarklist["FASTTRACK"];
//							$vesselname= $rowembarklist["VESSELNAME"];
//							
//							if ($applicantno1 == $applicantno)
//								$styleselect = "background-color:Yellow;";
//							else 
//								$styleselect = "";
//							
//							if ($tmprankcode != $rankcode)
//							{
//								echo "
//								<tr>
//									<td colspan=\"3\" $stylehdr>$rankalias</td>
//								</tr>
//								";
//							}
//							
//							echo "
//							<tr $mouseovereffect style=\"cursor:pointer;$styleselect\"  ondblclick=\"applicantno.value='$applicantno1';submit();\">
//								<td align=\"left\" $styleborder>$xcrewname</td>
//								<td align=\"center\" $styleborder>$datedisemb</td>
//								<td align=\"center\" $styleborder title=\"$vesselname\">$vessel</td>
//							</tr>
//							";
//							
//							$tmprankcode = $rankcode;
//						}
//					
//						echo "
//						</table>
//					";
//				
//				break;
//				
//			case "2"	:  //PER VESSEL
//				
//					echo "
//						<table width=\"100%\" class=\"listcol\">
//							<tr>
//								<th width=\"40%\">NAME</th>
//								<th width=\"35%\">DISEMBARK</th>
//								<th width=\"25%\">RANK</th>
//							</tr>
//						";
//						
//						$tmpvesselcode = "";
//					
//						while ($rowembarklist = mysql_fetch_array($qryarrivelist))
//						{
//							$applicantno1 = $rowembarklist["APPLICANTNO"];
//							$xcrewname = $rowembarklist["NAME"];
//							if (!empty($rowembarklist["DATEDISEMB"]))
//								$datedisemb = date("dMY",strtotime($rowembarklist["DATEDISEMB"]));
//							else 
//								$datedisemb = "";
//							$rankalias = $rowembarklist["RANKALIAS"];
//							$vesselcode = $rowembarklist["VESSELCODE"];
//							$vessel = $rowembarklist["VESSEL"];
//							$scholartype = $rowembarklist["SCHOLARTYPE"];
//							$fasttrack = $rowembarklist["FASTTRACK"];
//							
//							$vesselname= $rowembarklist["VESSELNAME"];
//							
//							if ($applicantno1 == $applicantno)
//								$styleselect = "background-color:Yellow;";
//							else 
//								$styleselect = "";
//							
//							if ($tmpvesselcode != $vesselcode)
//							{
//								echo "
//								<tr>
//									<td colspan=\"3\" $stylehdr>$vesselname</td>
//								</tr>
//								";
//							}
//							
//							echo "
//							<tr $mouseovereffect style=\"cursor:pointer;$styleselect\" ondblclick=\"applicantno.value='$applicantno1';submit();\">
//								<td align=\"left\" $styleborder title=\"Scholar: $scholartype \n FastTrack: $fasttrack\">$xcrewname</td>
//								<td align=\"center\" $styleborder>$datedisemb</td>
//								<td align=\"center\" $styleborder>$rankalias</td>
//							</tr>
//							";
//							
//							$tmpvesselcode = $vesselcode;
//						}
//					
//						echo "
//						</table>
//					";
//				
//				break;
//		}
		echo "
		</div>
		
		<div style=\"width:100%;height:40px;background-color:Gray;border:1px solid Black;\">
			<table class=\"setup\" width=\"100%\">
		";
		
		switch ($groupby)
		{
			case "1"	:  //PER RANK
			
					echo "
						<tr>
							<th>Rank</th>
							<th>:</th>
							<td><select name=\"byrank\" onchange=\"submit();\">
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
									if ($byrank == $rankcode1)
										$selectrank = "SELECTED";
										
									echo "<option $selectrank value=\"$rankcode1\">$rank1</option>";
								}
					echo "
								</select>
							
							</td>
						</tr>
					";
			
				break;
			case "2"	:  //PER VESSEL
				
					echo "
						<tr>
							<th>Vessel</th>
							<th>:</th>
							<td><select name=\"byvessel\" onchange=\"submit();\">
									<option value=\"0\">--Choose Vessel--</option>
						";
		
								while($rowvessellist=mysql_fetch_array($qryvessellist))
								{
									$vessel1=$rowvessellist['VESSEL'];
									$vesselcode1=$rowvessellist['VESSELCODE'];
									
									$selectvsl = "";
									if ($byvessel == $vesselcode1)
										$selectvsl = "SELECTED";
										
									echo "<option $selectvsl value=\"$vesselcode1\">$vessel1</option>";
								}
					echo "
		
								</select>
							
							</td>
						</tr>
						";
				break;
		}
			
			echo "
			</table>
		</div>
		
		
	
	</div>
-->	
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
			
		</div>
		
		<div style=\"width:100%;height:490px;margin:3 5 3 5;background-color:White;\">

			<center>
			";
	
			if (!empty($applicantno))
			{
				echo "
				<iframe marginwidth=0 marginheight=0 id=\"debriefstat\" frameborder=\"0\" name=\"content\" 
					src=\"debriefstatus.php?applicantno=$applicantno&ccid=$ccid\" scrolling=\"auto\" 
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