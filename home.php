<?php

include("veritas/connectdb.php");
include("veritas/include/stylephp.inc");
include("veritas/include/functions.inc");

//$mouseovereffect="onMouseOver=\"prevfontcolor=this.style.color;prevbgcolor=this.style.backgroundColor; this.style.backgroundColor=overbgcolor;this.style.color=overfontcolor\" onMouseOut=\"this.style.backgroundColor=prevbgcolor;this.style.color=prevfontcolor;\"";

$getservername=$_SERVER["SERVER_NAME"];
$gethttphost=$_SERVER["HTTP_HOST"];
$getserveraddr=$_SERVER["SERVER_ADDR"];

session_start();

if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
else 
	$employeeid = "";

if (isset($_SESSION['departmentid']))
	$departmentid = $_SESSION['departmentid'];
else 
	$departmentid = "";

if (isset($_GET["showsearch"]))
	$showsearch = $_GET["showsearch"];
else 
	$showsearch=0;
	
if (isset($_POST["actiontxt"]))
	$actiontxt = $_POST["actiontxt"];
	
if (isset($_POST["applicantno"]))
	$applicantno = $_POST["applicantno"];
	
if (isset($_POST["searchkey"]))
	$searchkey = $_POST["searchkey"];
	
if (isset($_POST["searchby"]))
	$searchby = $_POST["searchby"];
	
$showdatasheet = 0;

$basedirid = "idpics/";

switch ($actiontxt)
{
	case "find"		:
		
//			$showsearch = "visibility:show;";
			$showsearch = 1;
		
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
				$showdatasheet = 1;
				$rowgetsearch = mysql_fetch_array($qrysearch);
				
				$appno_single = $rowgetsearch["APPLICANTNO"];
			}
			else 
				$showdatasheet = 0;
			
		break;
		
	case "selected":
		
			$showdatasheet = 1;
			$appno_single = $applicantno;
			
		break;

}
	
if (!empty($appno_single))
{
	$qrygetname = mysql_query("SELECT FNAME,GNAME,MNAME 
							FROM crew 
							WHERE APPLICANTNO=$appno_single") or die(mysql_error());
	
	if (!empty($qrygetname))
	{
		$rowgetname = mysql_fetch_array($qrygetname);
		$xfname = $rowgetname["FNAME"];
		$xgname = $rowgetname["GNAME"];
		$xmname = $rowgetname["MNAME"];
		
		$fullname = $xfname . ", " . $xgname . " " . $xmname;
	}
	
	$qryexperience = mysql_query("SELECT x.CCID,d.CCID AS DEBRIEFCCID,d.STATUS FROM
									(
										SELECT cc.CCID,v.MANAGEMENTCODE,LEFT(v.ALIAS2,10) AS VESSEL,v.VESSEL AS VESSELNAME,v.VESSELCODE,r.ALIAS1 AS RANKALIAS,v.GROSSTON,vs.ENGINEMAIN AS ENGINE,
										vt.VESSELTYPECODE,v.TRADEROUTECODE,vs.ENGINEPOWER,
										cc.DATEEMB,
										IF(IF(DATECHANGEDISEMB IS NULL,DATEDISEMB,DATECHANGEDISEMB) > CURRENT_DATE,CURRENT_DATE,IF(DATECHANGEDISEMB IS NULL,DATEDISEMB,DATECHANGEDISEMB)) AS DATEDISEMB,
										cc.DISEMBREASONCODE,dr.REASON AS DISEMBREASON,
										vt.VESSELTYPE,tr.TRADEROUTE,cc.DEPMNLDATE,cc.ARRMNLDATE,cp1.CCIDPROMOTE AS CHKPROMOTE,NULL AS MANNING
										FROM crewchange cc
										LEFT JOIN vessel v ON v.VESSELCODE=cc.VESSELCODE
										LEFT JOIN rank r ON r.RANKCODE=cc.RANKCODE
										LEFT JOIN vesselspecs vs ON vs.VESSELCODE=v.VESSELCODE
										LEFT JOIN vesseltype vt ON vt.VESSELTYPECODE=v.VESSELTYPECODE
										LEFT JOIN vesselsize vz ON vz.VESSELSIZECODE=v.VESSELSIZECODE
										LEFT JOIN disembarkreason dr ON dr.DISEMBREASONCODE=cc.DISEMBREASONCODE
										LEFT JOIN traderoute tr ON tr.TRADEROUTECODE=v.TRADEROUTECODE
										LEFT JOIN crewpromotionrelation cp1 ON cc.CCID=cp1.CCIDPROMOTE
										WHERE cc.DATEEMB <= CURRENT_DATE AND cc.APPLICANTNO=$appno_single
									) x
									LEFT JOIN debriefinghdr d ON d.CCID=x.CCID
									WHERE x.DATEEMB <= CURRENT_DATE AND ARRMNLDATE IS NOT NULL
									ORDER BY x.DATEDISEMB DESC
									LIMIT 1
								") or die(mysql_error());
	
	$rowexperience = mysql_fetch_array($qryexperience);
	
	$ccid = $rowexperience["CCID"];
	$debriefccid = $rowexperience["DEBRIEFCCID"];
	$debriefstatus = $rowexperience["STATUS"];
	
	if (!empty($debriefccid))
	{
		$btndebrief = "";
	}
	else 
	{
		$btndebrief = "disabled=\"disabled\"";
	}
	
	if ($debriefstatus < 1)
	{
		$debriefclick = "openWindow('debriefing.php?applicantno=$appno_single', 'Debriefing', 0, 0);";
	}
	else 
	{
		$debriefclick = "openWindow('debriefingcomments.php?ccid=$debriefccid', 'Debriefing_Comments', 700, 500);";
	}
	
	$btnwithdraw = "disabled=\"disabled\"";
	
	
	// $qrywithdraw = mysql_query("SELECT APPLICANTNO FROM crewwithdrawal WHERE APPLICANTNO=$appno_single") or die(mysql_error());
	
	$qrywithdraw = mysql_query("SELECT IDNO,APPLICANTNO FROM crewwithdrawal 
						WHERE APPLICANTNO=$appno_single
						AND EFFECTDATE IS NOT NULL ORDER BY FORMDATE DESC
						LIMIT 1
						") or die(mysql_error());

	$rowwithdraw = mysql_fetch_array($qrywithdraw);

	$idno = $rowwithdraw["IDNO"];
	
	if (mysql_num_rows($qrywithdraw) > 0)
	{
		$btnwithdraw = "";
	}
	
}

	
if ($employeeid != "")
{
//	echo "<script>alert('$employeeid')</script>";
	$qrywelcome = mysql_query("SELECT EMPLOYEEID,FNAME,GNAME,MNAME,DESIGNATION,DEPARTMENT
					FROM employee e
					LEFT JOIN designation d ON d.DESIGNATIONCODE=e.DESIGNATIONCODE
					LEFT JOIN department dp ON dp.DEPARTMENTID=e.DEPARTMENTID
					WHERE e.EMPLOYEEID = '$employeeid'") or die(mysql_error());
	
	$rowwelcome = mysql_fetch_array($qrywelcome);
	
	$name = $rowwelcome["FNAME"] . ", " . $rowwelcome["GNAME"] . " " . $rowwelcome["MNAME"];
	$designation = $rowwelcome["DESIGNATION"];
	$department= $rowwelcome["DEPARTMENT"];

}
if($showsearch==0)
{
	$displaysearch="none";
	$displaybackground="display";
}
else
{
	$displaysearch="display";
	$displaybackground="none";
}

echo "
<html>
<head>
<link rel=\"stylesheet\" type=\"text/css\" href=\"veripro.css\">

<script type='text/javascript' src='veripro.js'></script>
<script type='text/javascript' src='crewchangeplanajax.js'></script>
<script type='text/javascript' src='ajax.js'></script>

<script>
function createurl(z) // 
{
//	url = \"http://$getservername/veritas/crewchangeplanrequest.php?actionajax=\"; // The server-side script 
	url = \"http://$gethttphost/veritas/crewchangeplanrequest.php?actionajax=\"; // The server-side script 
	
	var actionajax = document.formmain.actionajax.value; 
		
	//fill URL
	var applicantno = '&embapplicantnohidden=' + z;
	fillurl = actionajax + applicantno;
	
	getvalues();
}
function switchajax(x)
{
	if(x==1) // 1 if loading box is visible
	{
		var strdisplay='block';
		var strdisable='disabled';
	}
	else
	{
		var strdisplay='none';
		var strdisable='';
	}
	
	document.getElementById('ajaxprogress').style.display=strdisplay;
	
}
function fillview201()
{
	if(document.formmain.actionajax.value=='viewonboard201')
	{
		onboard201result=results[2];
		onboard201();
	}
}
function onboard201()
{
	var myObject = new Object();
    myObject.getonboard201result = onboard201result;
    myObject.getcrewname = '';
    window.showModalDialog('crewonboard201.php', myObject, 'dialogHeight:650px; dialogWidth:500px;status=no'); 
}



</script>
</head>
<body onload=\"if ('$showdatasheet' == '1') {document.getElementById('advsearch').style.display='block';document.getElementById('whattodo').style.display='block';}\">
<form name=\"formmain\" method=\"POST\">
	<div style=\"width:100%;height:100%;\">
	
		<div id=\"welcome\">
			<table class=\"listrow\" style=\"width:90%;font-size:0.9em;\" >
				<tr>
					<th>Logged in user</th>
					<th> : </th>
					<td style=\"font-size:1em;font-weight:Bold;color:Green;\">$name</td>
				</tr>
				<tr>
					<th>Designation</th>
					<th> : </th>
					<td style=\"font-size:0.8em;font-weight:Bold;color:Blue;\">$designation</td>
				</tr>
				<tr>
					<th>Department</th>
					<th> : </th>
					<td style=\"font-size:0.8em;font-weight:Bold;color:Blue;\">&nbsp;</td>
				</tr>
			</table>
			
			<hr />
			
			<div id=\"whattodo\" style=\"position:absolute;z-index:200;left:275;top:80;width:300px;height:325px;background-color:#486471;
					border:3px solid Red;overflow:hidden;border:1px solid Navy;display:'none';padding:5px;\">
				";
				$stylebutton = "style=\"font-size:0.75em;font-weight:Bold;width:200px;border:1px solid Black;background-color:#DCDCDC;cursor:pointer;\"";
				
				echo "
				<center>
				<span class=\"sectiontitle\">$fullname</span>
				<br />
		";
					$dirfilename = $basedirid . $appno_single . ".JPG";
					if (checkpath($dirfilename))
					{
						$scale = imageScale($dirfilename,-1,120);
						$width = $scale[0];
						$height = $scale[1];
						
		echo "			<center><img src=\"$dirfilename\" width=\"$width\" height=\"$height\" border=\"1\" /></center> ";
					}
					else 
					{
		echo "			<center><b>[NO PICTURE]</b></center>";
					}
					
					//openWindow('debriefingformshow.php?applicantno=$applicantno&load=1', 'showform', 900, 600);
					
					
					
				if (!empty($appno_single))
				{
					$qrycurrentrank2 = mysql_query("SELECT z.DATEDISEMB,v.MANAGEMENTCODE,v.VESSEL,z.RANKCODE,r.RANK,r.ALIAS1 FROM 
													(
														SELECT '1' AS VMC,RANKCODE,DATEDISEMB,VESSELCODE
														FROM
															(
															SELECT RANKCODE,VESSELCODE,
															IF(IF(DATECHANGEDISEMB IS NULL,DATEDISEMB,DATECHANGEDISEMB) > CURRENT_DATE,
																CURRENT_DATE,IF(DATECHANGEDISEMB IS NULL,DATEDISEMB,DATECHANGEDISEMB)) AS DATEDISEMB
															FROM crewchange where APPLICANTNO=$appno_single AND DATEEMB < CURRENT_DATE
															ORDER BY DATEDISEMB DESC
															) x
														
														UNION					
																			
														SELECT '2' AS VMC,RANKCODE,DATEDISEMB,NULL
														FROM
															(
															SELECT RANKCODE,DATEDISEMB
															FROM crewexperience where APPLICANTNO=$appno_single
															ORDER BY DATEDISEMB DESC
															) y
													) z
													LEFT JOIN rank r ON r.RANKCODE=z.RANKCODE
													LEFT JOIN vessel v ON v.VESSELCODE=z.VESSELCODE
													ORDER BY DATEDISEMB DESC
												") or die(mysql_error());
					
					$rowcurrentrank2 = mysql_fetch_array($qrycurrentrank2);
					$currentrankcode2 = $rowcurrentrank2["RANKCODE"];
				}
					
					
		echo "
				<br />

				<input type=\"button\" $stylebutton value=\"201 Profile\" onclick=\"document.formmain.actionajax.value='viewonboard201';createurl($appno_single);\" /> <br />
				<input type=\"button\" $stylebutton value=\"Debriefing\" $btndebrief onclick=\"$debriefclick\" /> <br />
				<input type=\"button\" $stylebutton value=\"Withdrawal\" $btnwithdraw onclick=\"openWindow('withdrawcomments.php?applicantno=$appno_single&idno=$idno', 'withdrawal2', 600, 400);\" /> <br />
				<input type=\"button\" $stylebutton value=\"Training\" onclick=\"openWindow('reptrainingchecklist.php?applicantno=$appno_single&rankcode=$currentrankcode2', 'repchecklist' ,0,0);\" /> <br />
				<input type=\"button\" $stylebutton value=\"Crew Comments\" onclick=\"openWindow('crewcomments.php?applicantno=$appno_single', 'crewcomments' ,600,500);\" /> <br />
				
				<br />
				<input type=\"button\" $stylebutton value=\"Close Window\" onclick=\"document.getElementById('whattodo').style.display='none';\" />
				<center>
				
			</div>
			
			<div id=\"advsearch\" style=\"background-color:#EBF7EC;
					overflow:hidden;padding:10px;border:1px solid Navy;display:'$displaysearch'\">
				<br>
				<span class=\"sectiontitle\">ADVANCE SEARCH</span>
				<br />
			
				<table width=\"95%\" style=\"font-size:0.8em;font-weight:Bold;\">
					<tr>
						<th>Search Key &nbsp;&nbsp;
							<select name=\"searchby\" onchange=\"searchkey.focus();\">
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
								<option $selected2 value=\"2\">CREW CODE</option>
								<option $selected1 value=\"1\">APPLICANT NO</option>
								<option $selected4 value=\"4\">GIVEN NAME</option>
							</select>
						</th>
						<td><input type=\"text\" name=\"searchkey\" size=\"40\" value=\"$searchkey\" onkeyup=\"this.value=this.value.toUpperCase()\" 
								style=\"color:Red;font-weight:Bold;\" onkeydown=\"if(event.keyCode==13) {btnfind.focus();}\"/>
								
							<input type=\"button\" name=\"btnfind\" value=\"Find\" onclick=\"if(searchby.value != '' && searchkey.value != '')
										{actiontxt.value='find';submit();}
										else
										{alert('Invalid Search Key. Please try again.');}
										\" />
						</td>
					</tr>
				</table>
				
				<div style=\"width:100%;height:220px;background-color:#EBF7EC;overflow:auto;padding:10px;\">
					<center>
						<span style=\"font-size:0.7em;font-weight:Bold;color:Blue;cursor:pointer;\" 
							onclick=\"document.getElementById('advsearch').style.display='none';document.getElementById('showbackground').style.display='block';\">
							[ Close Window ]
							</span>
					</center>
					<br />
					
					<table width=\"100%\" class=\"listcol\">
						<tr>
							<th width=\"10%\">APP. NO</th>
							<th width=\"10%\">CREW CODE</th>
							<th width=\"20%\">FNAME</th>
							<th width=\"20%\">GNAME</th>
							<th width=\"20%\">MNAME</th>
							<th width=\"10%\">RANK</th>
							<th width=\"10%\">STATUS</th>
						</tr>
					";
						if (mysql_num_rows($qrysearch) > 0)
						{
							while ($rowmultisearch = mysql_fetch_array($qrysearch))
							{
								$appno = $rowmultisearch["APPLICANTNO"];
								
								$qrygetinfo = mysql_query("SELECT c.APPLICANTNO,c.CREWCODE,FNAME,GNAME,MNAME,c.STATUS,c.UTILITY,
															s.DESCRIPTION AS SCHOLARTYPE,f.FASTTRACK AS FASTTRACKTYPE
															FROM crew c
															LEFT JOIN crewfasttrack cf ON cf.APPLICANTNO=c.APPLICANTNO
															LEFT JOIN fasttrack f ON f.FASTTRACKCODE=cf.FASTTRACKCODE
															LEFT JOIN crewscholar cs ON cs.APPLICANTNO=c.APPLICANTNO
															LEFT JOIN scholar s ON s.SCHOLASTICCODE=cs.SCHOLASTICCODE
															WHERE c.APPLICANTNO=$appno
														") or die(mysql_error());
								
								$rowgetinfo = mysql_fetch_array($qrygetinfo);
				
								$info1 = $rowgetinfo["APPLICANTNO"];
								$info2 = $rowgetinfo["CREWCODE"];
								$info3 = $rowgetinfo["FNAME"];
								$info4 = $rowgetinfo["GNAME"];
								$info5 = $rowgetinfo["MNAME"];
								
								$dblclick = "actiontxt.value='selected';applicantno.value='$info1';submit();";
								if ($rowgetinfo["STATUS"] == 1)
								{
									$info6 = "ACTIVE";
									$styleinactive = "";
								}
								else 
								{
									$info6 = "INACTIVE";
									$styleinactive = "style=\"background-color:Red;color:White;\"";
								}
								
								$infoscholar = $rowgetinfo["SCHOLARTYPE"];
								$infofasttrack = $rowgetinfo["FASTTRACKTYPE"];
								$infoutility = $rowgetinfo["UTILITY"];
								
								if (empty($infoscholar) && empty($infofasttrack) && empty($infoutility))
									$inforegular = "REGULAR";
								else 
									$inforegular = "";
								
								$qrycurrentrank = mysql_query("SELECT z.DATEDISEMB,v.MANAGEMENTCODE,v.VESSEL,z.RANKCODE,r.RANK,r.ALIAS1 FROM 
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
									
								$currentrankcode = $rowcurrentrank["RANKCODE"];
								$vessel = $rowcurrentrank["VESSEL"];
								
								
								echo "
								<tr $mouseovereffect style=\"cursor:pointer;\"
									ondblclick=\"$dblclick\">
									<td $styleinactive align=\"center\">$info1</td>
									<td $styleinactive align=\"center\">$info2</td>
									<td $styleinactive>$info3&nbsp;</td>
									<td $styleinactive>$info4&nbsp;</td>
									<td $styleinactive>$info5&nbsp;</td>
<!--									<td $styleinactive align=\"center\">$info6</td>		-->
									<td $styleinactive align=\"center\">$currentrankalias</td>
									<td $styleinactive style=\"font-size:0.9em;\" align=\"center\">
									";
									if (empty($inforegular))
									{
										echo "$infoscholar $infofasttrack $infoutility";
									}
									else 
									{
										echo "$inforegular";
									}
										
									echo "
									</td>
								</tr>
								";
							}
						}
						elseif (mysql_num_rows($qrysearch) == 1)
						{
							
						}
						else 
						{
							$errormsg = "[ NO RESULTS ]";
							
							echo "
								<tr>
									<td colspan=\"6\">&nbsp;</td>
								</tr>
								<tr>
									<td colspan=\"6\" align=\"center\" style=\"font-size:1.1em;font-weight:Bold;color:Green;\"><i>$errormsg</i></td>
								</tr>
							";
						}
							
					echo "
					</table>
					<br>
					<center>
						<span style=\"font-size:0.7em;font-weight:Bold;color:Blue;cursor:pointer;\" 
							onclick=\"document.getElementById('advsearch').style.display='none';document.getElementById('showbackground').style.display='block';\">
							[ Close Window ]
							</span>
					</center>
				</div>
			</div>
			<div id=\"showbackground\" style=\"display:'$displaybackground';width:750px;height:320px;border-bottom:0;overflow:auto;padding:0;
					background-image:url(images/bg3.jpg);background-repeat:no-repeat;background-position: center;\">
			</div>
		</div>
	</div>
";	
include("veritas/include/ajaxprogress.inc");

echo "
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"applicantno\" />
	
	<input type=\"hidden\" name=\"actionajax\">
</form>
</body>
</html>
";




?>

