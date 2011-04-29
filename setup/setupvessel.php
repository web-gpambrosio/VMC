<?php

include("veritas/connectdb.php");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
		
$formname = "formvessel";
$formtitle = "VESSEL SETUP";


$checkstatus = "";
$checkcallingus = "";

//POSTS

if(isset($_POST['vesselcode']))
	$vesselcode=$_POST['vesselcode'];

if(isset($_POST['managementcode']))
	$managementcode=$_POST['managementcode'];

if(isset($_POST['vessel']))
	$vessel=$_POST['vessel'];
	
if(isset($_POST['alias1']))
	$alias1=$_POST['alias1'];

if(isset($_POST['vesseltypecode']))
	$vesseltypecode=$_POST['vesseltypecode'];

if(isset($_POST['divcode']))
	$divcode=$_POST['divcode'];

if(isset($_POST['fleetno']))
	$fleetno=$_POST['fleetno'];

if(isset($_POST['flagcode']))
	$flagcode=$_POST['flagcode'];
	
if(isset($_POST['flagothers']))
	$flagothers=$_POST['flagothers'];
	
if(isset($_POST['portregistry']))
	$portregistry=$_POST['portregistry'];
	
if(isset($_POST['callsign']))
	$callsign=$_POST['callsign'];
	
if(isset($_POST['classification']))
	$classification=$_POST['classification'];

if(isset($_POST['classno']))
	$classno=$_POST['classno'];

if(isset($_POST['officialno']))
	$officialno=$_POST['officialno'];

if(isset($_POST['builtplace']))
	$builtplace=$_POST['builtplace'];
	
if(isset($_POST['builtyear']))
	$builtyear=$_POST['builtyear'];
	
if(isset($_POST['datelaunched']))
	$datelaunched=$_POST['datelaunched'];
	
if(isset($_POST['datedelivered']))
	$datedelivered=$_POST['datedelivered'];

if(isset($_POST['email']))
	$email=$_POST['email'];

if(isset($_POST['traderoutecode']))
	$traderoutecode=$_POST['traderoutecode'];

if(isset($_POST['cargokind']))
	$cargokind=$_POST['cargokind'];

if($_POST['callingus'])
{
	$callingus=1;
	$checkcallingus = "checked=\"checked\"";
}
else 
{
	$callingus=0;
	$checkcallingus = "";
}
	
if(isset($_POST['grosston']))
	$grosston=$_POST['grosston'];
else 
	$grosston=0;
	
if(isset($_POST['grosston_sbook']))
	$grosston_sbook=$_POST['grosston_sbook'];
else 
	$grosston_sbook=0;
	
if(isset($_POST['netton']))
	$netton=$_POST['netton'];
else 
	$netton=0;
	
if(isset($_POST['deadwt']))
	$deadwt=$_POST['deadwt'];
else 
	$deadwt=0;

if(isset($_POST['status']))
{
	$status=1;
	$checkstatus = "checked=\"checked\"";
}
else 
{
	$status=0;
}

if(isset($_POST['prevname']))
	$prevname=$_POST['prevname'];
	
if(isset($_POST['alias2']))
	$alias2=$_POST['alias2'];
	
if(isset($_POST['ownercode']))
	$ownercode=$_POST['ownercode'];
	
if(isset($_POST['pnicode']))
	$pnicode=$_POST['pnicode'];

if(isset($_POST['vesselsizecode']))
	$vesselsizecode=$_POST['vesselsizecode'];
	
if(isset($_POST['dateredelivered']))
	$dateredelivered=$_POST['dateredelivered'];

if(isset($_POST['contractterm']))
{
	if (!empty($_POST['contractterm']))
		$contractterm=$_POST['contractterm'];
	else 
		$contractterm=0;
}
else 
	$contractterm=0;


switch ($actiontxt)
{
	case "save"		:
		
			$qryverify = mysql_query("SELECT VESSELCODE FROM vessel WHERE VESSELCODE='$vesselcode'") or die(mysql_error());
		
			if (!empty($builtyear))
				$builtyearraw = "'" . date('Y-m-d',strtotime($builtyear)) . "'";
			else
				$builtyearraw = "NULL";
				
			if (!empty($datelaunched))
				$datelaunchedraw = "'" . date('Y-m-d',strtotime($datelaunched)) . "'";
			else
				$datelaunchedraw = "NULL";
				
			if (!empty($datedelivered))
				$datedeliveredraw = "'" . date('Y-m-d',strtotime($datedelivered)) . "'";
			else
				$datedeliveredraw = "NULL";
				
			if (!empty($dateredelivered))
				$dateredeliveredraw = date('Y-m-d',strtotime($dateredelivered));
			else
				$dateredeliveredraw = "NULL";
			
			
			if(mysql_num_rows($qryverify) == 0)
			{
				
				$qryvesselsave = mysql_query("INSERT INTO vessel(VESSELCODE,VESSEL,MANAGEMENTCODE,ALIAS1,ALIAS2,VESSELTYPECODE,DIVCODE,FLAGCODE,
													PORTREGISTRY,CALLSIGN,CLASSIFICATION,CLASSNO,OFFICIALNO,BUILTPLACE,BUILTYEAR,DATELAUNCHED,DATEDELIVERED,
													TRADEROUTECODE,CARGOKIND,CALLINGUS,GROSSTON,GROSSTON_SBOOK,NETTON,DEADWT,STATUS,PREVNAME,OWNERCODE,PNICODE,
													VESSELSIZECODE,DATEREDELIVERED,CONTRACTTERM,FLAGOTHERS,MADEBY,MADEDATE,EMAILADDRESS,FLEETNO)
													VALUES('$vesselcode','$vessel','$managementcode','$alias1','$alias2','$vesseltypecode','$divcode',
													'$flagcode','$portregistry','$callsign','$classification','$classno','$officialno','$builtplace',
													$builtyearraw,$datelaunchedraw,$datedeliveredraw,'$traderoutecode','$cargokind',$callingus,$grosston,
													$grosston_sbook,$netton,$deadwt,$status,'$prevname','$ownercode','$pnicode','$vesselsizecode',$dateredeliveredraw,
													$contractterm,'$flagothers','$employeeid','$currentdate','$email','$fleetno')
											") or die(mysql_error());
			}
			else 
			{
				
				$qryvesselupdate = mysql_query("UPDATE vessel SET VESSEL='$vessel',
																	MANAGEMENTCODE='$managementcode',
																	ALIAS1='$alias1',
																	ALIAS2='$alias2',
																	VESSELTYPECODE='$vesseltypecode',
																	DIVCODE='$divcode',
																	FLAGCODE='$flagcode',
																	PORTREGISTRY='$portregistry',
																	CALLSIGN='$callsign',
																	CLASSIFICATION='$classification',
																	CLASSNO='$classno',
																	OFFICIALNO='$officialno',
																	BUILTPLACE='$builtplace',
																	BUILTYEAR=$builtyearraw,
																	DATELAUNCHED=$datelaunchedraw,
																	DATEDELIVERED=$datedeliveredraw,
																	TRADEROUTECODE='$traderoutecode',
																	CARGOKIND='$cargokind',
																	CALLINGUS=$callingus,
																	GROSSTON=$grosston,
																	GROSSTON_SBOOK=$grosston_sbook,
																	NETTON=$netton,
																	DEADWT=$deadwt,
																	STATUS=$status,
																	PREVNAME='$prevname',
																	OWNERCODE='$ownercode',
																	PNICODE='$pnicode',
																	VESSELSIZECODE='$vesselsizecode',
																	DATEREDELIVERED=$dateredeliveredraw,
																	CONTRACTTERM=$contractterm,
																	FLAGOTHERS='$flagothers',
																	MADEBY='$employeeid',
																	MADEDATE='$currentdate',
																	EMAILADDRESS='$email',
																	FLEETNO='$fleetno'
												WHERE VESSELCODE='$vesselcode'
											") or die(mysql_error());
			}
			
			$vesselcode = "";
			$vessel = "";
			$alias1 = "";
			$alias2 = "";
			$vesseltypecode = "";
			$divcode = "";
			$flagcode = "";
			$portregistry = "";
			$callsign = "";
			$classification = "";
			$classno = "";
			$officialno = "";
			$builtplace = "";
			$builtyear = "";
			$builtplace = "";
			$datelaunched = "";
			$datedelivered = "";
			$traderoutecode = "";
			$cargokind = "";
			$callingus = "";
			$grosston = "";
			$grosston_sbook = "";
			$netton = "";
			$deadwt = "";
			$status = "";
			$prevname = "";
			$ownercode = "";
			$pnicode = "";
			$vesselsizecode = "";
			$dateredelivered = "";
			$contractterm = "";
			$flagothers = "";
			$email = "";
			$fleetno = "";
			
		break;
		
	case "cancel"	:
		
			$vesselcode = "";
			$vessel = "";
			$alias1 = "";
			$alias2 = "";
			$vesseltypecode = "";
			$divcode = "";
			$flagcode = "";
			$portregistry = "";
			$callsign = "";
			$classification = "";
			$classno = "";
			$officialno = "";
			$builtplace = "";
			$builtyear = "";
			$builtplace = "";
			$datelaunched = "";
			$datedelivered = "";
			$traderoutecode = "";
			$cargokind = "";
			$callingus = "";
			$grosston = "";
			$grosston_sbook = "";
			$netton = "";
			$deadwt = "";
			$status = "";
			$prevname = "";
			$ownercode = "";
			$pnicode = "";
			$vesselsizecode = "";
			$dateredelivered = "";
			$contractterm = "";
			$flagothers = "";
			$email = "";
			$fleetno = "";
		break;
		
	case "delete"	:

		break;
}
	

/* LISTINGS  */

$qrymanagementsel = mysql_query("SELECT MANAGEMENTCODE,MANAGEMENT FROM management ORDER BY MANAGEMENT") or die(mysql_error());

	$select1 = "<option selected value=\"\">--Select One--</option>";
	while($rowmanagementsel = mysql_fetch_array($qrymanagementsel))
	{
		$mgmtcode = $rowmanagementsel["MANAGEMENTCODE"];
		$mgmt = $rowmanagementsel["MANAGEMENT"];
		
		$selected1 = "";
		
		if ($managementcode == $mgmtcode)
			$selected1 = "SELECTED";
			
		$select1 .= "<option $selected1 value=\"$mgmtcode\">$mgmt</option>";
	}
	
$qryvesseltypesel = mysql_query("SELECT VESSELTYPECODE,VESSELTYPE FROM vesseltype ORDER BY VESSELTYPE") or die(mysql_error());

	$select2 = "<option selected value=\"\">--Select One--</option>";
	while($rowvesseltypesel = mysql_fetch_array($qryvesseltypesel))
	{
		$vsltypecode = $rowvesseltypesel["VESSELTYPECODE"];
		$vsltype = $rowvesseltypesel["VESSELTYPE"];
		
		$selected1 = "";
		
		if ($vesseltypecode == $vsltypecode)
			$selected1 = "SELECTED";
			
		$select2 .= "<option $selected1 value=\"$vsltypecode\">$vsltype</option>";
	}

$qrydivisionsel = mysql_query("SELECT DIVCODE,DIVISION FROM division ORDER BY DIVISION") or die(mysql_error());

	$select3 = "<option selected value=\"\">--Select One--</option>";
	while($rowdivisionsel = mysql_fetch_array($qrydivisionsel))
	{
		$dvcode = $rowdivisionsel["DIVCODE"];
		$dv = $rowdivisionsel["DIVISION"];
		
		$selected1 = "";
		
		if ($divcode == $dvcode)
			$selected1 = "SELECTED";
			
		$select3 .= "<option $selected1 value=\"$dvcode\">$dv</option>";
	}

$qrycountrysel = mysql_query("SELECT COUNTRYCODE,COUNTRY FROM country ORDER BY COUNTRY") or die(mysql_error());

	$select4 = "<option selected value=\"\">--Select One--</option>";
	while($rowcountrysel = mysql_fetch_array($qrycountrysel))
	{
		$cntrycode = $rowcountrysel["COUNTRYCODE"];
		$cntry = $rowcountrysel["COUNTRY"];
		
		$selected1 = "";
		
		if ($flagcode == $cntrycode)
			$selected1 = "SELECTED";
			
		$select4 .= "<option $selected1 value=\"$cntrycode\">$cntry</option>";
	}

$qrytraderoutesel = mysql_query("SELECT TRADEROUTECODE,TRADEROUTE FROM traderoute ORDER BY TRADEROUTE") or die(mysql_error());

	$select5 = "<option selected value=\"\">--Select One--</option>";
	while($rowtraderoutesel = mysql_fetch_array($qrytraderoutesel))
	{
		$tradecode = $rowtraderoutesel["TRADEROUTECODE"];
		$trade = $rowtraderoutesel["TRADEROUTE"];
		
		$selected1 = "";
		
		if ($traderoutecode == $tradecode)
			$selected1 = "SELECTED";
			
		$select5 .= "<option $selected1 value=\"$tradecode\">$trade</option>";
	}

$qryownersel = mysql_query("SELECT OWNERCODE,OWNER FROM owner ORDER BY OWNER") or die(mysql_error());

	$select6 = "<option selected value=\"\">--Select One--</option>";
	while($rowownersel = mysql_fetch_array($qryownersel))
	{
		$owncode = $rowownersel["OWNERCODE"];
		$own = $rowownersel["OWNER"];
		
		$selected1 = "";
		
		if ($ownercode == $owncode)
			$selected1 = "SELECTED";
			
		$select6 .= "<option $selected1 value=\"$owncode\">$own</option>";
	}

$qrypnisel = mysql_query("SELECT PNICODE,PNI FROM pni ORDER BY PNI") or die(mysql_error());

	$select7 = "<option selected value=\"\">--Select One--</option>";
	while($rowpnisel = mysql_fetch_array($qrypnisel))
	{
		$pncode = $rowpnisel["PNICODE"];
		$pni = $rowpnisel["PNI"];
		
		$selected1 = "";
		
		if ($pnicode == $pncode)
			$selected1 = "SELECTED";
			
		$select7 .= "<option $selected1 value=\"$pncode\">$pni</option>";
	}

$qryvesselsizesel = mysql_query("SELECT VESSELSIZECODE,VESSELSIZE FROM vesselsize ORDER BY VESSELSIZE") or die(mysql_error());

	$select8 = "<option selected value=\"\">--Select One--</option>";
	while($rowvesselsizesel = mysql_fetch_array($qryvesselsizesel))
	{
		$vslsizecode = $rowvesselsizesel["VESSELSIZECODE"];
		$vslsize = $rowvesselsizesel["VESSELSIZE"];
		
		$selected1 = "";
		
		if ($vesselsizecode == $vslsizecode)
			$selected1 = "SELECTED";
			
		$select8 .= "<option $selected1 value=\"$vslsizecode\">$vslsize</option>";
	}


$qryvessel = mysql_query("SELECT v.*,vt.VESSELTYPE,vs.VESSELSIZE,tr.TRADEROUTE,m.MANAGEMENT,d.DIVISION,
							p.PNI,o.OWNER,c.COUNTRY AS FLAG
							FROM vessel v
							LEFT JOIN vesseltype vt ON vt.VESSELTYPECODE=v.VESSELTYPECODE
							LEFT JOIN vesselsize vs ON vs.VESSELSIZECODE=v.VESSELSIZECODE
							LEFT JOIN traderoute tr ON tr.TRADEROUTECODE=v.TRADEROUTECODE
							LEFT JOIN management m ON m.MANAGEMENTCODE=v.MANAGEMENTCODE
							LEFT JOIN division d ON d.DIVCODE=v.DIVCODE
							LEFT JOIN pni p ON p.PNICODE=v.PNICODE
							LEFT JOIN owner o ON o.OWNERCODE=v.OWNERCODE
							LEFT JOIN country c ON c.COUNTRYCODE=v.FLAGCODE
							ORDER BY STATUS DESC,VESSEL
							") or die(mysql_error());




/*END OF LISTINGS*/


echo "
<html>
<head>
<link rel=\"stylesheet\" type=\"text/css\" href=\"../veripro.css\">
<script language=\"javascript\" src=\"../veripro.js\"></script>
<script language=\"javascript\" src=\"popcalendar.js\"></script>	

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
	
	<center>
	<div style=\"width:100%;height:500px;padding:5px 20px 0 20px;\">
			
		<div style=\"width:100%;height:350px;float:right;padding:5px 20px 0 20px;\">
			<br />
			<span class=\"sectiontitle\">$formtitle</span>
			<br />
			
			<table class=\"setup\" width=\"100%\" >	
				<tr>
					<th>Vessel Code</th>
					<th>:</th>
					<th><input type=\"text\" name=\"vesselcode\" value=\"$vesselcode\" size=\"10\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Vessel Name</th>
					<th>:</th>
					<th><input type=\"text\" name=\"vessel\" value=\"$vessel\" size=\"60\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Management</th>
					<th>:</th>
					<th><select name=\"managementcode\">
							$select1
						</select>
					</th>
				</tr>
				<tr>
					<th>Alias 1</th>
					<th>:</th>
					<th><input type=\"text\" name=\"alias1\" value=\"$alias1\" size=\"60\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Alias 2</th>
					<th>:</th>
					<th><input type=\"text\" name=\"alias2\" value=\"$alias2\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Vessel Type</th>
					<th>:</th>
					<th><select name=\"vesseltypecode\">
							$select2
						</select>
					</th>
				</tr>
				<tr>
					<th>Vessel Size</th>
					<th>:</th>
					<th><select name=\"vesselsizecode\">
							$select8
						</select>
					</th>
				</tr>
				<tr>
					<th>Contract Term (Months)</th>
					<th>:</th>
					<th><input type=\"text\" name=\"contractterm\" value=\"$contractterm\" size=\"30\" onKeyPress=\"return numbersonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Division</th>
					<th>:</th>
					<th><select name=\"divcode\">
							$select3
						</select>
					</th>
				</tr>
				<tr>
					<th>Fleet No.</th>
					<th>:</th>
					<th><input type=\"text\" name=\"fleetno\" value=\"$fleetno\" size=\"15\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Flag</th>
					<th>:</th>
					<th><select name=\"flagcode\">
							$select4
						</select>
					</th>
				</tr>
				<tr>
					<th>Flag (Others)</th>
					<th>:</th>
					<th><input type=\"text\" name=\"flagothers\" value=\"$flagothers\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Port Registry</th>
					<th>:</th>
					<th><input type=\"text\" name=\"portregistry\" value=\"$portregistry\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Call Sign</th>
					<th>:</th>
					<th><input type=\"text\" name=\"callsign\" value=\"$callsign\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Classification</th>
					<th>:</th>
					<th><input type=\"text\" name=\"classification\" value=\"$classification\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Class No.</th>
					<th>:</th>
					<th><input type=\"text\" name=\"classno\" value=\"$classno\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Official No.</th>
					<th>:</th>
					<th><input type=\"text\" name=\"officialno\" value=\"$officialno\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Place Built</th>
					<th>:</th>
					<th><input type=\"text\" name=\"builtplace\" value=\"$builtplace\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Built Year</th>
					<th>:</th>
					<th>
						<input type=\"text\" name=\"builtyear\" value=\"$builtyear\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
						<img src=\"../calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(builtyear, builtyear, 'mm/dd/yyyy', 0, 0);return false;\">
					</th>
				</tr>
				<tr>
					<th>Date Launched</th>
					<th>:</th>
					<th>
						<input type=\"text\" name=\"datelaunched\" value=\"$datelaunched\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
						<img src=\"../calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(datelaunched, datelaunched, 'mm/dd/yyyy', 0, 0);return false;\">
					</th>
				</tr>
				<tr>
					<th>Date Delivered</th>
					<th>:</th>
					<th>
						<input type=\"text\" name=\"datedelivered\" value=\"$datedelivered\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
						<img src=\"../calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(datedelivered, datedelivered, 'mm/dd/yyyy', 0, 0);return false;\">
					</th>
				</tr>
				<tr>
					<th>Date Re-delivered</th>
					<th>:</th>
					<th>
						<input type=\"text\" name=\"dateredelivered\" value=\"$dateredelivered\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
						<img src=\"../calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(datedelivered, datedelivered, 'mm/dd/yyyy', 0, 0);return false;\">
					</th>
				</tr>
				<tr>
					<th>Trade Route</th>
					<th>:</th>
					<th><select name=\"traderoutecode\">
							$select5
						</select>
					</th>
				</tr>
				<tr>
					<th>Cargo Kind</th>
					<th>:</th>
					<th><input type=\"text\" name=\"cargokind\" value=\"$cargokind\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Calling US?</th>
					<th>:</th>
					<th>
						<input type=\"checkbox\" name=\"callingus\" $checkcallingus />
					</th>
				</tr>
				<tr>
					<th>Net Tonnage</th>
					<th>:</th>
					<th><input type=\"text\" name=\"netton\" value=\"$netton\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Gross Tonnage</th>
					<th>:</th>
					<th><input type=\"text\" name=\"grosston\" value=\"$grosston\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Gross Tonnage (Seaman's Book)</th>
					<th>:</th>
					<th><input type=\"text\" name=\"grosston_sbook\" value=\"$grosston_sbook\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Dead Weight</th>
					<th>:</th>
					<th><input type=\"text\" name=\"deadwt\" value=\"$deadwt\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Previous Name</th>
					<th>:</th>
					<th><input type=\"text\" name=\"prevname\" value=\"$prevname\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>

				<tr>
					<th>Owner</th>
					<th>:</th>
					<th><select name=\"ownercode\">
							$select6
						</select>
					</th>
				</tr>
				<tr>
					<th>PNI</th>
					<th>:</th>
					<th><select name=\"pnicode\">
							$select7
						</select>
					</th>
				</tr>
				<tr>
					<th>Email Address</th>
					<th>:</th>
					<th><input type=\"text\" name=\"email\" value=\"$email\" size=\"50\" onKeyPress=\"\" />
					</th>
				</tr>
				<tr>
					<th>Active?</th>
					<th>:</th>
					<th>
						<input type=\"checkbox\" name=\"status\" $checkstatus />
					</th>
				</tr>
				<tr>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
					<th><input type=\"button\" name=\"\" value=\"Save\" onclick=\"actiontxt.value='save';checksave();\" />
						<input type=\"button\" name=\"\" value=\"Cancel\" onclick=\"actiontxt.value='cancel';$formname.submit();\" />
					</th>
				</tr>			
			</table>
		</div>
		
		<div style=\"width:100%;height:200px;padding:5px 20px 0 20px;overflow:hidden;\">
			<br />
			<span class=\"sectiontitle\">CURRENT LISTING</span>
			<br />
			
			<div style=\"width:100%;height:130px;padding:0;overflow:auto;\">
				<table width=\"100%\" class=\"listcol\">
					<tr>
						<th>CODE</th>
						<th>VESSEL</th>
						<th>TYPE</th>
						<th>SIZE</th>
						<th>TRADEROUTE</th>
						<th>STATUS</th>
						<th>MADEBY</th>
						<th>MADEDATE</th>
					</tr>
	";
				while ($rowvessel=mysql_fetch_array($qryvessel))
				{
					$list1 = $rowvessel["VESSELCODE"];
					$list2 = $rowvessel["VESSEL"];
					$list3 = $rowvessel["ALIAS1"];
					$list4 = $rowvessel["ALIAS2"];
					$list6 = $rowvessel["MANAGEMENTCODE"];
					$list8 = $rowvessel["MANAGEMENT"];
					$list9 = $rowvessel["VESSELTYPECODE"];
					$list10 = $rowvessel["VESSELTYPE"];
					$list11 = $rowvessel["DIVCODE"];
					$list12 = $rowvessel["DIVISION"];
					$list13 = $rowvessel["FLAGCODE"];
					$list14 = $rowvessel["FLAG"];
					$list15 = $rowvessel["PORTREGISTRY"];
					$list16 = $rowvessel["CALLSIGN"];
					$list17 = $rowvessel["CLASSIFICATION"];
					$list18 = $rowvessel["CLASSNO"];
					$list19 = $rowvessel["OFFICIALNO"];
					$list20 = $rowvessel["BUILTPLACE"];
					
					if ($rowvessel["BUILTYEAR"])
						$list21 = date('m/d/Y',strtotime($rowvessel["BUILTYEAR"]));
					else 
						$list21 = "";
						
					$list21a = $rowvessel["BUILTYEAR"];
					
					if (strtotime($rowvessel["DATELAUNCHED"]) != strtotime('1970-01-01'))
						$list22 = date('m/d/Y',strtotime($rowvessel["DATELAUNCHED"]));
					else 
						$list22 = "";
						
					$list22a = $rowvessel["DATELAUNCHED"];
					
					if (strtotime($rowvessel["DATEDELIVERED"]) != strtotime('1970-01-01'))
						$list23 = date('m/d/Y',strtotime($rowvessel["DATEDELIVERED"]));
					else 
						$list23 = "";
						
					$list23a = $rowvessel["DATEDELIVERED"];
					$list24 = $rowvessel["TRADEROUTECODE"];
					$list25 = $rowvessel["TRADEROUTE"];
					$list26 = $rowvessel["CARGOKIND"];
					$list27 = $rowvessel["CALLINGUS"];
					
//					if ($rowvessel["CALLINGUS"])
//						$list27 = "YES";
//					else 
//						$list27 = "NO";
						
					$list28 = $rowvessel["GROSSTON"];
					$list29 = $rowvessel["NETTON"];
					$list30 = $rowvessel["DEADWT"];
					$list31 = $rowvessel["PREVNAME"];
					$list32 = $rowvessel["OWNERCODE"];
					$list33 = $rowvessel["OWNER"];
					$list34 = $rowvessel["PNICODE"];
					$list35 = $rowvessel["PNI"];
					$list36 = $rowvessel["VESSELSIZECODE"];
					$list37 = $rowvessel["VESSELSIZE"];
					
					if (strtotime($rowvessel["DATEREDELIVERED"]) != strtotime('1970-01-01'))
						$list38 = date('m/d/Y',strtotime($rowvessel["DATEREDELIVERED"]));
					else 
						$list38 = "";
						
					$list38a = $rowvessel["DATEREDELIVERED"];
					
					if ($rowvessel["STATUS"]==1)
						$list39 = "ACTIVE";
					else 
						$list39 = "INACTIVE";
						
					$list40 = $rowvessel["MADEBY"];
					$list41 = date('m-d-Y',strtotime($rowvessel["MADEDATE"]));
					
					$list42 = $rowvessel["CONTRACTTERM"];
					$list43 = $rowvessel["FLAGOTHERS"];
					$list44 = $rowvessel["EMAILADDRESS"];
					$list45 = $rowvessel["FLEETNO"];
					$list46 = $rowvessel["GROSSTON_SBOOK"];
					
					echo "
					<tr ondblclick=\"
						document.$formname.vesselcode.value='$list1';
						document.$formname.vessel.value='$list2';
						document.$formname.alias1.value='$list3';
						document.$formname.alias2.value='$list4';
						document.$formname.managementcode.value='$list6';
						document.$formname.vesseltypecode.value='$list9';
						document.$formname.divcode.value='$list11';
						document.$formname.flagcode.value='$list13';
						document.$formname.portregistry.value='$list15';
						document.$formname.callsign.value='$list16';
						document.$formname.classification.value='$list17';
						document.$formname.classno.value='$list18';
						document.$formname.officialno.value='$list19';
						document.$formname.builtplace.value='$list20';
						document.$formname.builtyear.value='$list21';
						document.$formname.datelaunched.value='$list22';
						document.$formname.datedelivered.value='$list23';
						document.$formname.traderoutecode.value='$list24';
						document.$formname.cargokind.value='$list26';
						document.$formname.callingus.value='$list27';
						document.$formname.grosston.value='$list28';
						document.$formname.netton.value='$list29';
						document.$formname.deadwt.value='$list30';
						document.$formname.prevname.value='$list31';
						document.$formname.ownercode.value='$list32';
						document.$formname.pnicode.value='$list34';
						document.$formname.vesselsizecode.value='$list36';
						document.$formname.dateredelivered.value='$list38';
						document.$formname.contractterm.value='$list42';
						document.$formname.flagothers.value='$list43';
						document.$formname.email.value='$list44';
						document.$formname.fleetno.value='$list45';
						document.$formname.grosston_sbook.value='$list46';
						if ('$list39' == 'INACTIVE') {document.$formname.status.checked='';} else {document.$formname.status.checked='checked';}
						\">
						
						<td>$list1</td>
						<td>$list2</td>
						<td>$list10</td>
						<td align=\"center\">$list37</td>
						<td align=\"center\">$list25</td>
						<td align=\"center\">$list39</td>
						<td align=\"center\">$list40</td>
						<td align=\"center\">$list41</td>
					</tr>
	";
				}
	echo "
				</table>
			</div>
		</div>
		
	</div>
	</center>
	
	<input type=\"hidden\" name=\"actiontxt\" />
</form>

</body>
</html>
";

?>

