<?php

include("veritas/connectdb.php");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
		
$formname = "setupwatchlist";
$formtitle = "WATCHLIST SETUP";

$checkoverride = "";
$showmultiple = "display:none;";
$multiple = 0;

//POSTS FOR VERITAS
	
if (isset($_POST["applicantno"]))
	$applicantno = $_POST["applicantno"];
	
if (isset($_POST["watchtype"]))
	$watchtype = $_POST["watchtype"];
else 
	$watchtype = 0;
	
if (isset($_POST["remarks"]))
	$remarks = $_POST["remarks"];
	
if (isset($_POST["rankcode"]))
	$rankcode = $_POST["rankcode"];
	
if (isset($_POST["vesselcode"]))
	$vesselcode = $_POST["vesselcode"];
	
if(isset($_POST['override']))
{
	$override=1;
	$checkoverride = "checked=\"checked\"";
}
else 
	$override=0;

//POSTS FOR SEARCH	

if (isset($_POST["searchkey"]))
	$searchkey = $_POST["searchkey"];
	
if (isset($_POST["searchby"]))
	$searchby = $_POST["searchby"];
	
if ($searchkey == "")
	$disablesearch = "disabled=\"disabled\"";
else 
	$disablesearch = "";

	
//POSTS FOR POEA
	
if (isset($_POST["idno"]))
	$idno = $_POST["idno"];
	
if (isset($_POST["pfname"]))
	$pfname = $_POST["pfname"];
	
if (isset($_POST["pgname"]))
	$pgname = $_POST["pgname"];
	
if (isset($_POST["pmname"]))
	$pmname = $_POST["pmname"];
	
if (isset($_POST["ptin"]))
	$ptin = $_POST["ptin"];
	
if (isset($_POST["psss"]))
	$psss = $_POST["psss"];
	
if (isset($_POST["pvessel"]))
	$pvessel = $_POST["pvessel"];
	
if (isset($_POST["ppassportno"]))
	$ppassportno = $_POST["ppassportno"];
	
if (isset($_POST["pseabookno"]))
	$pseabookno = $_POST["pseabookno"];
	
if (isset($_POST["premarks"]))
	$premarks = $_POST["premarks"];

if(isset($_POST['poverride']))
{
	$poverride=1;
	$checkpoverride = "checked=\"checked\"";
}
else 
	$poverride=0;
	


switch ($actiontxt)
{
	case "find"	:
		
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
	
	case "save"		:
		
		if ($watchtype == 1)
		{
			$qryverify = mysql_query("SELECT * FROM watchlist_veritas WHERE APPLICANTNO=$applicantno") or die(mysql_error());
			
			if (mysql_num_rows($qryverify) > 0)
			{
//				echo "UPDATE watchlist_veritas SET VESSELCODE='$vesselcode',
//																	RANKCODE='$rankcode',
//																	REMARKS='$remarks',
//																	SECONDCHANCE=$override,
//																	MADEBY='$employeeid',
//																	MADEDATE='$currentdate'
//										WHERE APPLICANTNO=$applicantno";
				
				$qryupdate = mysql_query("UPDATE watchlist_veritas SET VESSELCODE='$vesselcode',
													RANKCODE='$rankcode',
													REMARKS='$remarks',
													SECONDCHANCE=$override,
													MADEBY='$employeeid',
													MADEDATE='$currentdate'
						WHERE APPLICANTNO=$applicantno") or die(mysql_error());

			}
			else
			{
				$qrycrewdetails = mysql_query("SELECT c.APPLICANTNO,c.FNAME,c.GNAME,c.MNAME,c.TIN,c.SSS
											FROM crew c
											WHERE c.APPLICANTNO=$applicantno") or die(mysql_error());
				
				$rowcrewdetails = mysql_fetch_array($qrycrewdetails);
				
				$zfname = $rowcrewdetails["FNAME"];
				$zgname = $rowcrewdetails["GNAME"];
				$zmname = $rowcrewdetails["MNAME"];
				$ztin = $rowcrewdetails["TIN"];
				$zsss = $rowcrewdetails["SSS"];
				
				$qrygetdocinfo = mysql_query("SELECT DOCCODE,DOCNO FROM crewdocstatus 
								WHERE APPLICANTNO=$applicantno AND DOCCODE IN ('31','32','33','F2','41')") or die(mysql_error());
				
				while($rowgetdocinfo = mysql_fetch_array($qrygetdocinfo))
				{
					$vdoccode = "v" . $rowgetdocinfo["DOCCODE"];
					$vdocno = $rowgetdocinfo["DOCNO"];
					
					$$vdoccode=$vdocno;
				}
				
				$qryinsert = mysql_query("INSERT INTO watchlist_veritas(FNAME,GNAME,MNAME,APPLICANTNO,RANKCODE,VESSELCODE,TIN,SSS,OCW,SRC,PRC,
											PASSPORTNO,SEABOOKNO,DATEFILED,REMARKS,SECONDCHANCE,MADEBY,MADEDATE)
											VALUES('$zfname','$zgname','$zmname',$applicantno,'$rankcode','$vesselcode','$ztin','$zsss',
											'$v33','$v32','$v31','$v41','$vF2','$currentdate','$remarks',$override,'$employeeid','$currentdate')	
										") or die(mysql_error());
			}
			
			$vesselcode = "";
			$rankcode = "";
			$remarks = "";
			$override = 0;
			$checkoverride = "";
			
		}
		elseif ($watchtype == 2)
		{
			if (empty($idno)) //NEW ENTRY
			{
				$qryinsert = mysql_query("INSERT INTO watchlist_poea(FNAME,GNAME,MNAME,VESSEL,TIN,SSS,
											PASSPORTNO,SEABOOKNO,DATEFILED,REMARKS,SECONDCHANCE,MADEBY,MADEDATE)
											VALUES('$pfname','$pgname','$pmname','$pvessel','$ptin','$psss','$ppassportno','$pseabookno',
											'$currentdate','$premarks',$poverride,'$employeeid','$currentdate')	
										") or die(mysql_error());
			}
			else 
			{
				$qryupdate = mysql_query("UPDATE watchlist_poea SET FNAME='$pfname',
																	GNAME='$pgname',
																	MNAME='$pmname',
																	VESSEL='$pvessel',
																	TIN='$ptin',
																	SSS='$psss',
																	PASSPORTNO='$ppassportno',
																	SEABOOKNO='$pseabookno',
																	REMARKS='$premarks',
																	SECONDCHANCE=$poverride,
																	MADEBY='$employeeid',
																	MADEDATE='$currentdate'
										WHERE IDNO=$idno") or die(mysql_error());
			}
			
			$idno = "";
			$pfname = "";
			$pgname = "";
			$pmname = "";
			$pvessel = "";
			$ptin = "";
			$psss = "";
			$ppassportno = "";
			$pseabookno = "";
			$premarks = "";
			$poverride = 0;
			$checkpoverride = "";

		}

			
		break;
		
	case "cancel"	:
		
			$idno = "";
			$pfname = "";
			$pgname = "";
			$pmname = "";
			$pvessel = "";
			$ptin = "";
			$psss = "";
			$ppassportno = "";
			$pseabookno = "";
			$premarks = "";
			$poverride = 0;
			$checkpoverride = "";
		
			$vesselcode = "";
			$rankcode = "";
			$remarks = "";
			$override = 0;
			$checkoverride = "";
			
		break;
}


echo "
<html>
<head>
<link rel=\"stylesheet\" type=\"text/css\" href=\"../veripro.css\">
<script language=\"javascript\" src=\"../veripro.js\"></script>

<script>

function checksave(x)
{
	var rem = '';
	
	with ($formname)
	{

	}
			
	if(rem=='')
	{
		$formname.submit();
	}
	else
		alert('Please CHECK the following: ' + rem + ' before saving!');		

}
	
</script>
</head>
<body>

<form name=\"$formname\" method=\"POST\">
	

	<div style=\"width:100%;height:620px;padding:5px 20px 0 20px;overflow:hidden;\">
	<span class=\"sectiontitle\">$formtitle</span>
		<div style=\"width:100%;height:400px;padding:5px 20px 0 20px;overflow:auto;\">

			
			<table class=\"setup\" width=\"50%\" >	
				<tr>
					<th>Watchlist Type</th>
					<th>:</th>
					<th><select name=\"watchtype\" onchange=\"submit();\">
					";
						switch ($watchtype)
						{
							case 1	:	$vmcselected = "SELECTED"; break;
							case 2	:	$poeaselected = "SELECTED"; break;
							default	:	$selectone = "SELECTED"; break;
						}

					echo "
							<option $selectone value=\"\">--Select--</option>
							<option $vmcselected value=\"1\">VERITAS</option>
							<option $poeaselected value=\"2\">POEA</option>
						</select>
					</th>
				</tr>
			</table>
			<hr />
			";
			if ($watchtype == 1) //VERITAS
			{
				
				//FOR MULTIPLE ENTRY
				echo "
							<div id=\"multiple\" style=\"position:absolute;top:150px;left:120px;height:184px;width:550px;background-color:#6699FF;
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
				";
				
				//END OF MULTIPLE ENRY
				
				//SEARCH AREA
				echo "
				<center>
				<table style=\"width:70%;margin-top:5px;\" border=1>
					<tr>
						<td align=\"left\" style=\"font-size:0.8em;font-weight:Bold;\">Search</td>
						<td>
							<select name=\"searchby\" onchange=\"if(this.value != ''){searchkey.disabled=false;searchkey.value='';searchkey.focus();}
																else {searchkey.disabled=true;searchkey.value='';}\">
								<option value=\"\">--Select Search Key--</option>
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
								<option $selected1 value=\"1\">APPLICANT NO</option>
								<option $selected2 value=\"2\">CREW CODE</option>
								<option $selected3 value=\"3\">FAMILY NAME</option>
								<option $selected4 value=\"4\">GIVEN NAME</option>
							</select>
						</td>
						<td align=\"center\"><input type=\"text\" name=\"searchkey\" size=\"20\" value=\"$searchkey\" $disablesearch
								onkeyup=\"this.value=this.value.toUpperCase();if (this.value != '') {btnfind.disabled=false;}\"
								style=\"color:Red;font-weight:Bold;\" onkeydown=\"if(event.keyCode==13) {btnfind.focus();}\"/>
								
							<input type=\"button\" name=\"btnfind\" value=\"Find\" $disablesearch onclick=\"actiontxt.value='find';submit();\" />
						</td>
					</tr>
				</table>
				</center>
				<br />
				";
				//END OF SEARCH AREA
						
				if (!empty($applicantno))
				{
					$qrygetinfo = mysql_query("SELECT c.APPLICANTNO,c.FNAME,c.GNAME,c.MNAME,c.TIN,c.SSS,
											wv.VESSELCODE,wv.RANKCODE,wv.REMARKS,wv.SECONDCHANCE
											FROM crew c
											LEFT JOIN watchlist_veritas wv ON wv.APPLICANTNO=c.APPLICANTNO
											WHERE c.APPLICANTNO=$applicantno") or die(mysql_error());
					
					$rowgetinfo = mysql_fetch_array($qrygetinfo);
					$vfname = $rowgetinfo["FNAME"];
					$vgname = $rowgetinfo["GNAME"];
					$vmname = $rowgetinfo["MNAME"];
					$vtin = $rowgetinfo["TIN"];
					$vsss = $rowgetinfo["SSS"];
					$vesselcode = $rowgetinfo["VESSELCODE"];
					$rankcode = $rowgetinfo["RANKCODE"];
					$remarks = $rowgetinfo["REMARKS"];
					$secondchance = $rowgetinfo["SECONDCHANCE"];
				
					if ($secondchance == 1)
					{
						$override=1;
						$checkoverride = "checked=\"checked\"";
					}
					else 
					{
						$override=0;
						$checkoverride = "";
					}
					
					$qrygetdocinfo = mysql_query("SELECT DOCCODE,DOCNO FROM crewdocstatus 
									WHERE APPLICANTNO=$applicantno AND DOCCODE IN ('31','32','33','F2','41')") or die(mysql_error());
					
					while($rowgetdocinfo = mysql_fetch_array($qrygetdocinfo))
					{
						$vdoccode = "v" . $rowgetdocinfo["DOCCODE"];
						$vdocno = $rowgetdocinfo["DOCNO"];
						
						$$vdoccode=$vdocno;
					}
					
					$qryvesselsel = mysql_query("SELECT VESSELCODE,VESSEL FROM vessel WHERE STATUS=1 ORDER BY VESSEL") or die(mysql_error());
					
						$vesselsel = "<option selected value=\"\">--Select One--</option>";
						while($rowvesselsel = mysql_fetch_array($qryvesselsel))
						{
							$svesselcode = $rowvesselsel["VESSELCODE"];
							$svessel = $rowvesselsel["VESSEL"];
							
							$selected1 = "";
							
							if ($svesselcode == $vesselcode)
								$selected1 = "SELECTED";
								
							$vesselsel .= "<option $selected1 value=\"$svesselcode\">$svessel</option>";
						}
					
					$qryranksel = mysql_query("SELECT RANKCODE,RANK FROM rank WHERE STATUS=1 ORDER BY RANK") or die(mysql_error());
					
						$ranksel = "<option selected value=\"\">--Select One--</option>";
						while($rowranksel = mysql_fetch_array($qryranksel))
						{
							$srankcode = $rowranksel["RANKCODE"];
							$srank = $rowranksel["RANK"];
							
							$selected1 = "";
							
							if ($srankcode == $rankcode)
								$selected1 = "SELECTED";
								
							$ranksel .= "<option $selected1 value=\"$srankcode\">$srank</option>";
						}
					
					
					echo "
					<table class=\"setup\" style=\"width:50%;float:left;\" >	
						<tr>
							<th colspan=\"3\"><u>Personal Information</u></th>
						</tr>
						<tr><td colspan=\"3\">&nbsp;</td></tr>
						<tr>
							<th>Family Name</th>
							<th>:</th>
							<td>$vfname</td>
						</tr>
						<tr>
							<th>Given Name</th>
							<th>:</th>
							<td>$vgname</td>
						</tr>
						<tr>
							<th>Middle Name</th>
							<th>:</th>
							<td>$vmname</td>
						</tr>
						<tr>
							<th>TIN</th>
							<th>:</th>
							<td>$vtin</td>
						</tr>
						<tr>
							<th>SSS</th>
							<th>:</th>
							<td>$vsss</td>
						</tr>
						<tr>
							<th>Vessel</th>
							<th>:</th>
							<th><select name=\"vesselcode\">
									$vesselsel
								</select>
							</th>
						</tr>
						<tr>
							<th>Rank</th>
							<th>:</th>
							<th><select name=\"rankcode\">
									$ranksel
								</select>
							</th>
						</tr>
						<tr>
							<th>Override?</th>
							<th>:</th>
							<td>
								<input type=\"checkbox\" name=\"override\" $checkoverride />
							</td>
						</tr>
					</table>
					<table class=\"setup\" style=\"width:49%;float:right;\" >
						<tr>
							<th colspan=\"3\"><u>Documents Information</u></th>
						</tr>
						<tr><td colspan=\"3\">&nbsp;</td></tr>
						<tr>
							<th>Philippine Passport</th>
							<th>:</th>
							<td>$v41</td>
						</tr>
						<tr>
							<th>Philippine Seamanbook</th>
							<th>:</th>
							<td>$vF2</td>
						</tr>
						<tr>
							<th>PRC</th>
							<th>:</th>
							<td>$v31</td>
						</tr>
						<tr>
							<th>SRC</th>
							<th>:</th>
							<td>$v32</td>
						</tr>
						<tr>
							<th>OCW</th>
							<th>:</th>
							<td>$v33</td>
						</tr>
						<tr>
							<th colspan=\"3\" valign=\"top\">Remarks:
								<textarea rows=\"2\" cols=\"30\" name=\"remarks\">$remarks</textarea>
							</th>
						</tr>
						<tr>
							<th colspan=\"3\"><input type=\"button\" name=\"\" value=\"Save\" onclick=\"applicantno.value='$applicantno';actiontxt.value='save';$formname.submit();\" />
								<input type=\"button\" name=\"\" value=\"Cancel\" onclick=\"actiontxt.value='cancel';$formname.submit();\" />
							</th>
						</tr>
					</table>
					
					";
				}
			}
			elseif ($watchtype == 2) //POEA 
			{
				if (!empty($idno))
				{
					$qrygetinfo = mysql_query("SELECT * FROM watchlist_poea WHERE IDNO=$idno") or die(mysql_error());
					
					$rowgetinfo = mysql_fetch_array($qrygetinfo);
					$pfname = $rowgetinfo["FNAME"];
					$pgname = $rowgetinfo["GNAME"];
					$pmname = $rowgetinfo["MNAME"];
					$ptin = $rowgetinfo["TIN"];
					$psss = $rowgetinfo["SSS"];
					$pvessel = $rowgetinfo["VESSEL"];
					$ppassportno = $rowgetinfo["PASSPORTNO"];
					$pseabookno = $rowgetinfo["SEABOOKNO"];
					$premarks = $rowgetinfo["REMARKS"];
					$secondchance = $rowgetinfo["SECONDCHANCE"];
				
					if ($secondchance == 1)
					{
						$poverride=1;
						$checkpoverride = "checked=\"checked\"";
					}
					else 
					{
						$poverride=0;
						$checkpoverride = "";
					}
				}
					
					echo "
					<table class=\"setup\" style=\"width:100%;\" >	
						<tr>
							<th colspan=\"3\"><u>Crew Information</u></th>
						</tr>
						<tr>
							<th>Family Name</th>
							<th>:</th>
							<td><input type=\"text\" name=\"pfname\" value=\"$pfname\" /></td>
						</tr>
						<tr>
							<th>Given Name</th>
							<th>:</th>
							<td><input type=\"text\" name=\"pgname\" value=\"$pgname\" /></td>
						</tr>
						<tr>
							<th>Middle Name</th>
							<th>:</th>
							<td><input type=\"text\" name=\"pmname\" value=\"$pmname\" /></td>
						</tr>
						<tr>
							<th>TIN</th>
							<th>:</th>
							<td><input type=\"text\" name=\"ptin\" value=\"$ptin\" /></td>
						</tr>
						<tr>
							<th>SSS</th>
							<th>:</th>
							<td><input type=\"text\" name=\"psss\" value=\"$psss\" /></td>
						</tr>
						<tr>
							<th>Philippine Passport</th>
							<th>:</th>
							<td><input type=\"text\" name=\"ppassportno\" value=\"$ppassportno\" /></td>
						</tr>
						<tr>
							<th>Philippine Seamanbook</th>
							<th>:</th>
							<td><input type=\"text\" name=\"pseabookno\" value=\"$pseabookno\" /></td>
						</tr>
						<tr>
							<th>Vessel</th>
							<th>:</th>
							<th><input type=\"text\" name=\"pvessel\" value=\"$pvessel\" /></th>
						</tr>
						<tr>
							<th>Override?</th>
							<th>:</th>
							<td>
								<input type=\"checkbox\" name=\"poverride\" $checkpoverride />
							</td>
						</tr>
						<tr>
							<th>Remarks:</th>
							<th>:</th>
							<th valign=\"top\">
								<textarea rows=\"2\" cols=\"50\" name=\"premarks\">$premarks</textarea>
							</th>
						</tr>
						<tr>
							<th>&nbsp;</th>
							<th colspan=\"2\"><input type=\"button\" name=\"\" value=\"Save\" onclick=\"actiontxt.value='save';idno.value='$idno';$formname.submit();\" />
								<input type=\"button\" name=\"\" value=\"Cancel\" onclick=\"actiontxt.value='cancel';$formname.submit();\" />
							</th>
						</tr>
					</table>
					";
//				}
			}
					
					
			echo "
		</div>
		
		<span class=\"sectiontitle\">CURRENT LISTING</span>
		<div style=\"width:100%;height:160px;overflow:auto;background-color:Silver;\">
		";
			
		if ($watchtype == 1)
		{
			$qrycrewlist = mysql_query("SELECT IDNO,APPLICANTNO,FNAME,GNAME,MNAME,SSS,TIN FROM watchlist_veritas ORDER BY FNAME") or die(mysql_error());
			
			echo "
				<table class=\"listcol\" style=\"width:100%;height:100%;\">
					<tr>
						<th>FNAME</th>
						<th>GNAME</th>
						<th>MNAME</th>
						<th>SSS</th>
						<th>TIN</th>
					</tr>

			";
			
			while ($rowcrewlist = mysql_fetch_array($qrycrewlist))
			{
				$xidno = $rowcrewlist["IDNO"];
				$xappno = $rowcrewlist["APPLICANTNO"];
				$xfname = $rowcrewlist["FNAME"];
				$xgname = $rowcrewlist["GNAME"];
				$xmname = $rowcrewlist["MNAME"];
				$xsss = $rowcrewlist["SSS"];
				$xtin = $rowcrewlist["TIN"];
				
				echo"
					<tr style=\"cursor:pointer;\" ondblclick=\"applicantno.value='$xappno';searchby.value='';searchkey.value='';submit();\">
						<td>&nbsp;$xfname</td>
						<td>&nbsp;$xgname</td>
						<td>&nbsp;$xmname</td>
						<td>&nbsp;$xsss</td>
						<td>&nbsp;$xtin</td>
					</tr>
				";
			}
			echo "
				</table>
			";
		}
		elseif ($watchtype == 2)
		{
			$qrycrewlist = mysql_query("SELECT IDNO,FNAME,GNAME,MNAME,PASSPORTNO,SEABOOKNO FROM watchlist_poea ORDER BY FNAME") or die(mysql_error());
			
			echo "
				<table class=\"listcol\" style=\"width:100%;height:100%;\">
					<tr>
						<th>FNAME</th>
						<th>GNAME</th>
						<th>MNAME</th>
						<th>PASSPORTNO</th>
						<th>SEABOOKNO</th>
					</tr>

			";
			
			while ($rowcrewlist = mysql_fetch_array($qrycrewlist))
			{
				$xidno = $rowcrewlist["IDNO"];
				$xappno = $rowcrewlist["APPLICANTNO"];
				$xfname = $rowcrewlist["FNAME"];
				$xgname = $rowcrewlist["GNAME"];
				$xmname = $rowcrewlist["MNAME"];
				$xpassportno = $rowcrewlist["PASSPORTNO"];
				$xseabookno = $rowcrewlist["SEABOOKNO"];
				
				echo"
					<tr style=\"cursor:pointer;\" ondblclick=\"idno.value='$xidno';submit();\">
						<td>&nbsp;$xfname</td>
						<td>&nbsp;$xgname</td>
						<td>&nbsp;$xmname</td>
						<td>&nbsp;$xpassportno</td>
						<td>&nbsp;$xseabookno</td>
					</tr>
				";
			}
			echo "
				</table>
			";
		}
			
		echo "	
		</div>
		
	</div>
	
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"applicantno\" />
	<input type=\"hidden\" name=\"idno\" />
</form>

</body>
</html>
";

?>

