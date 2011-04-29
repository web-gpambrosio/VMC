<?php
include('veritas/connectdb.php');
include("veritas/include/stylephp.inc");
include("veritas/include/functions.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');
$basedirid = "idpics/";
$basedirdocs = "docimages/";

if (isset($_SESSION['employeeid']))
{
	$employeeid = $_SESSION['employeeid'];
	
	$qrygetdivision = mysql_query("SELECT DIVCODE FROM employee WHERE EMPLOYEEID='$employeeid'") or die(mysql_error());
	$rowgetdivision = mysql_fetch_array($qrygetdivision);
	$divcode = $rowgetdivision["DIVCODE"];
}
else 
	$employeeid = "";

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];

if (isset($_POST['applicantno']))
	$applicantno = $_POST['applicantno'];
else 
	$applicantno = $_GET["applicantno"];
	
//if (isset($_POST['groupby']))
//	$groupby = $_POST['groupby'];
//else 
//	$groupby = "1";
//	
//if (isset($_POST['byrank']))
//	$byrank = $_POST['byrank'];
//else 
//	$byrank = "All";
//	
//if (isset($_POST['byvessel']))
//	$byvessel = $_POST['byvessel'];
//else 
//	$byvessel = "";

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
}
	
	
	
//FOR RANK LISTING

//	$rankselectM = "";
//	$rankselectO = "";
//	$rankselectS = "";
//	$rankselectD = "";
//	$rankselectE = "";
//	$rankselectT = "";
//	$rankselectALL = "";
//	
//	$whererank = "";
//	
//	
//	
//	switch ($byrank)
//	{
//		case "M"	:  //MANAGEMENT
//			
//				$whererank = "AND rl.RANKLEVELCODE='M'";
//				$rankselectM = "SELECTED";
//				
//			break;
//		case "O"	:  //OPERATIONAL
//			
//				$whererank = "AND rl.RANKLEVELCODE='O'";
//				$rankselectO = "SELECTED";
//				
//			break;
//		case "S"	:  //SUPPORT
//			
//				$whererank = "AND rl.RANKLEVELCODE='S'";
//				$rankselectS = "SELECTED";
//				
//			break;
//		case "D"	:  //DECK
//			
//				$whererank = "AND rt.RANKTYPECODE='D'";
//				$rankselectD = "SELECTED";
//		
//			break;
//		case "E"	:  //ENGINE
//			
//				$whererank = "AND rt.RANKTYPECODE='E'";
//				$rankselectE = "SELECTED";
//				
//			break;
//		case "T"	:  //STEWARD
//			
//				$whererank = "AND rt.RANKTYPECODE='S'";
//				$rankselectT = "SELECTED";
//				
//			break;
//		
//		case "All"	:  //ALL RANKS
//			
//				$whererank = "";
//				$rankselectALL = "SELECTED";
//				
//			break;
//		
//		default		: //ANY RANKCODE
//		
//				$whererank = "AND r.RANKCODE='$byrank'";
//				
//			break;
//	}

//END OF RANK LISTING

//$qryranklist = mysql_query("SELECT RANK,RANKCODE FROM rank WHERE STATUS=1 ORDER BY RANKING") or die(mysql_error());
//$qryvessellist = mysql_query("SELECT VESSELCODE,VESSEL FROM vessel WHERE DIVCODE='$divcode' AND STATUS=1") or die(mysql_error());


//switch ($groupby)
//{
//	case "1"	:  //PER RANK
//		
//			$orderpart = "ORDER BY r.RANKLEVELCODE,r.RANKTYPECODE,r.RANKING,NAME";
//			$select1 = "SELECTED";
//		
//		break;
//	case "2"	:  //PER VESSEL
//		
//			$orderpart = "ORDER BY v.VESSEL,r.RANKING";
//			$select2 = "SELECTED";
//		
//		break;
//}
//
//
//$wherevessel = "";
//
//if (!empty($byvessel))
//{
//	$wherevessel = "AND cc.VESSELCODE='$byvessel'";
//}


// NOTE : After recon, change WHERE part to DATEEMB > CURRENT_DATE

//$qryembarklist = mysql_query("SELECT cc.APPLICANTNO,cc.DATEEMB AS ETD,
//							CONCAT(c.FNAME,', ',c.GNAME,' ',c.MNAME) AS NAME,c.CREWCODE,
//							LEFT(v.ALIAS2,12) AS VESSEL,v.VESSELCODE,v.VESSEL AS VESSELNAME,r.ALIAS1 AS RANKALIAS,r.RANKCODE,
//							r.RANKLEVELCODE,r.RANKTYPECODE,r.RANK,
//							s.SCHOLASTICCODE,s.DESCRIPTION AS SCHOLARTYPE,f.FASTTRACKCODE,f.FASTTRACK
//							FROM crewchange cc
//							LEFT JOIN crewpromotionrelation cpr ON cpr.CCIDPROMOTE=cc.CCID
//							LEFT JOIN crew c ON c.APPLICANTNO=cc.APPLICANTNO
//							LEFT JOIN rank r ON r.RANKCODE=cc.RANKCODE
//							LEFT JOIN ranklevel rl ON rl.RANKLEVELCODE=r.RANKLEVELCODE
//							LEFT JOIN ranktype rt ON rt.RANKTYPECODE=r.RANKTYPECODE
//							LEFT JOIN vessel v ON v.VESSELCODE=cc.VESSELCODE
//							LEFT JOIN crewscholar cs ON cs.APPLICANTNO=cc.APPLICANTNO
//							LEFT JOIN scholar s ON s.SCHOLASTICCODE=cs.SCHOLASTICCODE
//							LEFT JOIN crewfasttrack cf ON cf.APPLICANTNO=cc.APPLICANTNO
//							LEFT JOIN fasttrack f ON f.FASTTRACKCODE=cf.FASTTRACKCODE
//							WHERE DATEEMB > CURRENT_DATE
//							AND DEPMNLDATE IS NULL
//							AND cpr.CCID IS NULL
//							$wherevessel
//							$whererank
//							$orderpart
//							") or die(mysql_error());



echo "
<html>\n
<head>\n
<title>Training Endorsement</title>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>

<body onload=\"\" style=\"overflow:hidden;background-color:White;\">\n

<form name=\"trainendorsement\" method=\"POST\">\n

<span class=\"wintitle\">TRAINING ENDORSEMENT</span>

<div style=\"width:100%;background-color:White;\">
<!--
	<div style=\"width:30%;height:650px;float:left;padding:10 5 0 5;background-color:White;\">
	
		<span class=\"sectiontitle\">CREW POOLING</span>
		
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

		$stylehdr = "style=\"font-weight:Bold;background-color:Blue;color:White;text-align:center;\"";
		$styleborder = "style=\"border-right:1px dashed Black;\"";
		
		switch ($groupby)
		{
			case "1"	:  //PER RANK
				
					echo "
						<table width=\"100%\" class=\"listcol\">
							<tr>
								<th>NAME</th>
								<th>ETD</th>
								<th>VESSEL</th>
							</tr>
						";
						
						$tmprankcode = "";
						
						while ($rowembarklist = mysql_fetch_array($qryembarklist))
						{
							$applicantno1 = $rowembarklist["APPLICANTNO"];
							$crewname = $rowembarklist["NAME"];
							if (!empty($rowembarklist["ETD"]))
								$etd = date("dMY",strtotime($rowembarklist["ETD"]));
							else 
								$etd = "";
							$rankcode = $rowembarklist["RANKCODE"];
							$rankalias = $rowembarklist["RANKALIAS"];
							$rank = $rowembarklist["RANK"];
							$vessel = $rowembarklist["VESSEL"];
							$scholartype = $rowembarklist["SCHOLARTYPE"];
							$fasttrack = $rowembarklist["FASTTRACK"];
							$vesselname= $rowembarklist["VESSELNAME"];
							
							if ($applicantno1 == $applicantno)
								$styleselect = "background-color:Yellow;";
							else 
								$styleselect = "";
							
							if ($tmprankcode != $rankcode)
							{
								echo "
								<tr>
									<td colspan=\"3\" $stylehdr>$rankalias</td>
								</tr>
								";
							}
							
							echo "
							<tr $mouseovereffect style=\"cursor:pointer;$styleselect\"  ondblclick=\"applicantno.value='$applicantno1';submit();\">
								<td align=\"left\" $styleborder>$crewname</td>
								<td align=\"center\" $styleborder>$etd</td>
								<td align=\"center\" $styleborder title=\"$vesselname\">$vessel</td>
							</tr>
							";
							
							$tmprankcode = $rankcode;
						}
					
						echo "
						</table>
					";
				
				break;
				
			case "2"	:  //PER VESSEL
				
					echo "
						<table width=\"100%\" class=\"listcol\">
							<tr>
								<th>NAME</th>
								<th>ETD</th>
								<th>RANK</th>
							</tr>
						";
						
						$tmpvesselcode = "";
					
						while ($rowembarklist = mysql_fetch_array($qryembarklist))
						{
							$applicantno1 = $rowembarklist["APPLICANTNO"];
							$crewname = $rowembarklist["NAME"];
							if (!empty($rowembarklist["ETD"]))
								$etd = date("dMY",strtotime($rowembarklist["ETD"]));
							else 
								$etd = "";
							$rankalias = $rowembarklist["RANKALIAS"];
							$vesselcode = $rowembarklist["VESSELCODE"];
							$vessel = $rowembarklist["VESSEL"];
							$scholartype = $rowembarklist["SCHOLARTYPE"];
							$fasttrack = $rowembarklist["FASTTRACK"];
							
							if ($applicantno1 == $applicantno)
								$styleselect = "background-color:Yellow;";
							else 
								$styleselect = "";
							
							if ($tmpvesselcode != $vesselcode)
							{
								echo "
								<tr>
									<td colspan=\"3\" $stylehdr>$vessel</td>
								</tr>
								";
							}
							
							echo "
							<tr $mouseovereffect style=\"cursor:pointer;$styleselect\" ondblclick=\"applicantno.value='$applicantno1';submit();\">
								<td align=\"left\" $styleborder title=\"Scholar: $scholartype \n FastTrack: $fasttrack\">$crewname</td>
								<td align=\"center\" $styleborder>$etd</td>
								<td align=\"center\" $styleborder>$rankalias</td>
							</tr>
							";
							
							$tmpvesselcode = $vesselcode;
						}
					
						echo "
						</table>
					";
				
				break;
		}
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
		<br />
	
	
		<span class=\"sectiontitle\">TRAINING CHECKLIST</span>
		";
		if (!empty($applicantno))
		{
			echo "
			<iframe marginwidth=0 marginheight=0 id=\"showbody\" frameborder=\"0\" name=\"showbody\" 
				src=\"crewtrainingendorse.php?applicantno=$applicantno\" scrolling=\"yes\" style=\"width:100%;height:100%;\">
			</iframe> 
			";
		}
		else 
		{
			echo "
			<center>
				<br /><br /><br /><br /><br /><br />
				<span style=\"font-size:1.5em;font-weight:Bold;color:Gray;\"><i>[CHECKLIST PREVIEW]</i></span>

			</center>
			";
		}
	echo "
	</div>

</div>

	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"applicantno\" />

</form>
</body>
";

?>