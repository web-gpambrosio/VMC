<?php

include('veritas/connectdb.php');
//include('connectdb.php');

session_start();
$getservername=$_SERVER["SERVER_NAME"];
$getserveraddr=$_SERVER["SERVER_ADDR"];

$currentdate = date("Y-m-d H:i:s");
$currentdate1 = date("m/d/Y");
$datenow=date("Y-m-d");

include('veritas/include/stylephp.inc');

if(isset($_SESSION["employeeid"]))
	$employeeid=$_SESSION["employeeid"];

if(isset($_GET["divcode"]))
	$divcode=$_GET["divcode"];
//else 
//	$divcode=2;//TEMP

if(isset($_POST["ccidhidden"]))
	$ccidhidden=$_POST["ccidhidden"];

if(isset($_POST["applicantnohidden"]))
	$applicantnohidden=$_POST["applicantnohidden"];

if(isset($_POST["actiontxt"]))
	$actiontxt=$_POST["actiontxt"];

if(isset($_POST["tagpromotehidden"]))
	$tagpromotehidden=$_POST["tagpromotehidden"];
	
if(isset($_POST["xcrewname"]))
	$xcrewname=$_POST["xcrewname"];

if(isset($_POST["xembdate"]))
	$xembdate=$_POST["xembdate"];

if(isset($_POST["xdisembdate"]))
	$xdisembdate=$_POST["xdisembdate"];

if(isset($_POST["embdate"]))
	$embdate=$_POST["embdate"];

if(isset($_POST["disembdate"]))
	$disembdate=$_POST["disembdate"];

if(isset($_POST["xembport"]))
	$xembport=$_POST["xembport"];

if(isset($_POST["batch"]))
	$batch=$_POST["batch"];

if(isset($_POST["embport"]))
	$embport=$_POST["embport"];

if(isset($_POST["embporthidden"]))
	$embporthidden=$_POST["embporthidden"];

if(isset($_POST["batchfrom"]))
	$batchfrom=$_POST["batchfrom"];

if(isset($_POST["batchto"]))
	$batchto=$_POST["batchto"];

if(isset($_POST["embcountry"]))
	$embcountry=$_POST["embcountry"];

if(isset($_POST["mandisembdate"]))
	$mandisembdate=$_POST["mandisembdate"];

if(isset($_POST["mandisembreasoncode"]))
	$mandisembreasoncode=$_POST["mandisembreasoncode"];

if(isset($_POST["mandepartmnl"]))
	$mandepartmnl=$_POST["mandepartmnl"];

if(isset($_POST["manarrivemnl"]))
	$manarrivemnl=$_POST["manarrivemnl"];

if(isset($_POST["maninjurydate"]))
	$maninjurydate=$_POST["maninjurydate"];

if(isset($_POST["maninjuryreason"]))
	$maninjuryreason=$_POST["maninjuryreason"];

if(isset($_POST["changebatchdisplay"]))
	$changebatchdisplay=$_POST["changebatchdisplay"];
//if(empty($changebatchdisplay))
	$batchdisplay="none";
//else 
//	$batchdisplay="block";
	


//FILL EMBARK PORT (COUNTRY AND PORT)
$qryembcountry=mysql_query("SELECT DISTINCT PORTCOUNTRY FROM port ORDER BY PORTCOUNTRY") or die(mysql_error());

if(empty($embcountry))
{
	$getembport="<select name=\"embport\" style=\"width:100px;\" disabled=\"disabled\">
			<option value=\"\">-Port-</option>
		</select>";
}
else 
{
	if(empty($embporthidden))
		$embporttmp=$embport;
	else 
		$embporttmp=$embporthidden;
	$qryembport=mysql_query("SELECT PORT FROM port WHERE PORTCOUNTRY='$embcountry' ORDER BY PORT") or die(mysql_error());
	$getembport="<select name=\"embport\" style=\"width:100px;font-size:10pt;\"
					onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\">
					<option value=\"\">-Port-</option>";
					while($rowembport=mysql_fetch_array($qryembport))
					{
						$embport1=$rowembport['PORT'];
						if($embporttmp==$embport1)
							$selected="selected";
						else 
							$selected="";
						$getembport .= "<option $selected value=\"$embport1\">$embport1</option>\n";
					}
			$getembport .= "
				</select>";
}
switch ($actiontxt)
{
	case "disembsave"	:
//			echo "CCID=$ccidhidden";
			$qryfindccid = mysql_query("SELECT CCID FROM crewchange WHERE CCID='$ccidhidden'") or die(mysql_error());
			
			if (mysql_num_rows($qryfindccid) == 1)
			{
				if($mandisembdate != "")
					$mandisembdateraw = date('Y-m-d',strtotime($mandisembdate));
				else 
					$mandisembdateraw = "";
				
				$qrydisembsave = mysql_query("UPDATE crewchange SET DATECHANGEDISEMB='$mandisembdateraw',
													DISEMBREASONCODE='$mandisembreasoncode',
													DATECHANGEBY='$employeeid',
													DATECHANGEDATE='$currentdate'
											WHERE CCID=$ccidhidden
								") or die(mysql_error());		
			}
			
		break;
		
	case "arrivesave"	:
			
//			echo "CCID=$ccidhidden";
			if(empty($batch))
			{
				$qrycheckcrewchange = mysql_query("SELECT CCID,ARRMNLDATE,DEPMNLDATE,DATEEMB,DATEDISEMB,
												DATECHANGEDISEMB,DATECHANGEDATE,DATECHANGEBY,DISEMBREASONCODE
												FROM crewchange WHERE CCID=$ccidhidden") or die(mysql_error());
				
				if (mysql_num_rows($qrycheckcrewchange) > 0)
				{
					$placearrive = "";
					$placedepart = "";
					$placedateemb = "";
					$placedatedisemb = "";
					
					$error = 0;
					
					$rowcheckcrewchange=mysql_fetch_array($qrycheckcrewchange);
					$getarrmnldate=$rowcheckcrewchange["ARRMNLDATE"];
					$getdepmnldate=$rowcheckcrewchange["DEPMNLDATE"];
					$getdateemb=$rowcheckcrewchange["DATEEMB"];
					$getdisembreasoncode=$rowcheckcrewchange["DISEMBREASONCODE"];
					$getdatedisemb=$rowcheckcrewchange["DATEDISEMB"];
					$embdateraw=date("Y-m-d",strtotime($embdate));
					$disembdateraw=date("Y-m-d",strtotime($disembdate));
					
					if (empty($manarrivemnl))
						$manarrivemnlraw="NULL";
					else 
						$manarrivemnlraw = "'".date("Y-m-d",strtotime($manarrivemnl))."'";
					if (empty($mandepartmnl))
						$mandepartmnlraw="NULL";
					else 
						$mandepartmnlraw = "'".date("Y-m-d",strtotime($mandepartmnl))."'";
						
						
					//GET PORT ID
					$qryembportid=mysql_query("SELECT PORTID FROM port WHERE PORTCOUNTRY='$embcountry' AND PORT='$embport'") or die(mysql_error());
					$rowembportid=mysql_fetch_array($qryembportid);
					$embportid=$rowembportid["PORTID"];
					if(empty($embportid))
						$embportid="NULL";
						
					$update = "";
					
					if (strtotime($getarrmnldate)!=strtotime($manarrivemnl))
					{
						if (!empty($update))
							$update .=",ARRMNLDATE=$manarrivemnlraw,CONFIRMARRMNLBY='$employeeid',CONFIRMARRMNLDATE='$currentdate'";
						else 
							$update .="ARRMNLDATE=$manarrivemnlraw,CONFIRMARRMNLBY='$employeeid',CONFIRMARRMNLDATE='$currentdate'";
					}
						
					if (!empty($mandisembreasoncode) && ($getdisembreasoncode != $mandisembreasoncode))
					{
						if (!empty($update))
							$update .= ",DISEMBREASONCODE='$mandisembreasoncode'";
						else 
							$update .= "DISEMBREASONCODE='$mandisembreasoncode'";
					}
						
					
					if (strtotime($getdepmnldate)!=strtotime($mandepartmnl))
					{
						if (!empty($update))
							$update .= ",DEPMNLDATE=$mandepartmnlraw,CONFIRMDEPBY='$employeeid',CONFIRMDEPDATE='$currentdate'";
						else 
							$update .= "DEPMNLDATE=$mandepartmnlraw,CONFIRMDEPBY='$employeeid',CONFIRMDEPDATE='$currentdate'";
					}
											
					if (strtotime($getdateemb)!=strtotime($embdate))
					{
						if (!empty($update))
							$update .= ",DATEEMB='$embdateraw'";
						else 
							$update .= "DATEEMB='$embdateraw'";
					}
						
					if(strtotime($getdatedisemb)!=strtotime($disembdate))
					{
						if (!empty($update))
							$update .= ",DATECHANGEDISEMB='$disembdateraw',DATECHANGEBY='$employeeid',DATECHANGEDATE='$currentdate'";
						else 
							$update .= "DATECHANGEDISEMB='$disembdateraw',DATECHANGEBY='$employeeid',DATECHANGEDATE='$currentdate'";
					}
						
					if (!empty($update))
						$update .= ",EMBPORTID=$embportid,MADEBY='$employeeid',MADEDATE='$currentdate'";
					else 
						$update .= "EMBPORTID=$embportid,MADEBY='$employeeid',MADEDATE='$currentdate'";
//					
//					echo "UPDATE crewchange SET 
//							$update
//							WHERE CCID=$ccidhidden";
					
						$qryarriveupdate = mysql_query("UPDATE crewchange SET 
							$update
							WHERE CCID=$ccidhidden") or die(mysql_error());
				}
			}
			else 
			{
				$wherebatchno="WHERE BATCHNO=$batch AND OVERDUE1=1";
//				echo "YYY";
//				include("include/qrylistplan.inc");
				if(isset($_POST["vesselcode"]))
					$vesselcode=$_POST["vesselcode"];
				
				$qrylist=mysql_query("SELECT * FROM (
					SELECT c.CCID,c.APPLICANTNO,c.DATEEMB,c.DATEDISEMB AS DATEDISEMBORIG,c.ARRMNLDATE,c.DEPMNLDATE,
					IF(cw.FNAME IS NULL,CONCAT(cf.FNAME,', ',cf.GNAME),CONCAT(cw.FNAME,', ',cw.GNAME)) AS NAME,
					IF(c.DATECHANGEDISEMB IS NULL,c.DATEDISEMB,c.DATECHANGEDISEMB) AS DATEDISEMB,
					IF(c.DATECHANGEDISEMB IS NULL,0,IF(c.DATECHANGEDISEMB<c.DATEDISEMB,'Shortened','Extended')) AS DATEDISEMBCHANGED,
					c1.CCID AS CCIDEMB,c1.APPLICANTNO AS EMBAPPLICANTNO,
					IF(cw1.FNAME IS NULL,CONCAT(cf1.FNAME,', ',cf1.GNAME),CONCAT(cw1.FNAME,', ',cw1.GNAME)) AS EMBNAME,
					c1.DATEEMB AS EMBDATEEMB,c1.DATEDISEMB AS EMBDATEDISEMB,
					e1.PORT AS EMBPORT,e1.PORTCOUNTRY AS EMBPORTCOUNTRY,
					c.DATECHANGEDATE,ccp.DATECHANGEDATE AS DATECHANGEDATEPROMOTE,CONCAT(em.FNAME,', ',em.GNAME) AS DATECHANGEBY,CONCAT(emp.FNAME,', ',emp.GNAME) AS DATECHANGEBYPROMOTE,
					c.RANKCODE,c1.RANKCODE AS EMBRANKCODE,r.ALIAS2,r1.ALIAS2 AS EMBALIAS2,v.VESSELTYPECODE,v.VESSEL,d.REASON,c.DISEMBREASONCODE,
					ccp.CCID AS CCIDPROMOTE,rp.ALIAS2 AS ALIAS2PROMOTE,ccp.DATEEMB AS DATEEMBPROMOTE,
					IF(ccp.DATECHANGEDISEMB IS NULL,ccp.DATEDISEMB,ccp.DATECHANGEDISEMB) AS DATEDISEMBPROMOTE,
					IF(ccp.CCID IS NULL,c.BATCHNO,ccp.BATCHNO) AS BATCHNO,
					IF(ccp.DATECHANGEDISEMB IS NULL,0,IF(ccp.DATECHANGEDISEMB<ccp.DATEDISEMB,'Shortened','Extended')) AS DATEDISEMBCHANGEDPROMOTE,
					cp1.CCID AS CHKPROMOTE,ccp.ARRMNLDATE AS ARRMNLDATEPROMOTE,ccp.DEPMNLDATE AS DEPMNLDATEPROMOTE,
					IF(ccp.CCID IS NULL,c.CCID,ccp.CCID) AS CCIDFINAL,
					IF(ccp.CCID IS NULL,0,1) AS TAGPROMOTE,
					IF(ccp.CCID IS NULL,e.PORTCOUNTRY,ecp.PORTCOUNTRY) AS PORTCOUNTRY,
					IF(ccp.CCID IS NULL,e.PORT,ecp.PORT) AS PORT,
					IF(ccp.CCID IS NULL,
				    IF(c.DATECHANGEDISEMB IS NULL,c.DATEDISEMB,c.DATECHANGEDISEMB), IF(ccp.DATECHANGEDISEMB IS NULL,
				    ccp.DATEDISEMB,ccp.DATECHANGEDISEMB)) AS DISEMBFINAL,
				    IF(CURRENT_DATE <= IF(ccp.CCID IS NULL,
				    IF(c.DATECHANGEDISEMB IS NULL,c.DATEDISEMB,c.DATECHANGEDISEMB), IF(ccp.DATECHANGEDISEMB IS NULL,
				    ccp.DATEDISEMB,ccp.DATECHANGEDISEMB)),1,0) AS OVERDUE1
					FROM crewchange c
					LEFT JOIN crewchangerelation cr ON c.CCID=cr.CCID
					LEFT JOIN crewchange c1 ON cr.CCIDEMB=c1.CCID
					LEFT JOIN crew cw ON c.APPLICANTNO=cw.APPLICANTNO
					LEFT JOIN crew cw1 ON c1.APPLICANTNO=cw1.APPLICANTNO
					LEFT JOIN crewforeign cf ON c.APPLICANTNO=cf.APPLICANTNO
					LEFT JOIN crewforeign cf1 ON c1.APPLICANTNO=cf1.APPLICANTNO
					LEFT JOIN port e ON c.EMBPORTID=e.PORTID
					LEFT JOIN port e1 ON c1.EMBPORTID=e1.PORTID
					LEFT JOIN employee em ON c.DATECHANGEBY=em.EMPLOYEEID
					LEFT JOIN rank r ON c.RANKCODE=r.RANKCODE
					LEFT JOIN rank r1 ON c1.RANKCODE=r1.RANKCODE
					LEFT JOIN rank rf ON cf.RANKCODE=rf.RANKCODE
					LEFT JOIN rank rf1 ON cf1.RANKCODE=rf1.RANKCODE
					LEFT JOIN vessel v ON c.VESSELCODE=v.VESSELCODE
					LEFT JOIN disembarkreason d ON c.DISEMBREASONCODE=d.DISEMBREASONCODE
					LEFT JOIN crewpromotionrelation cp ON c.CCID=cp.CCID
					LEFT JOIN crewchange ccp ON cp.CCIDPROMOTE=ccp.CCID
					LEFT JOIN port ecp ON ccp.EMBPORTID=ecp.PORTID
					LEFT JOIN employee emp ON ccp.DATECHANGEBY=emp.EMPLOYEEID
					LEFT JOIN rank rp ON rp.RANKCODE=ccp.RANKCODE
					LEFT JOIN crewpromotionrelation cp1 ON c.CCID=cp1.CCIDPROMOTE
					WHERE c.VESSELCODE='$vesselcode') x
					$wherebatchno") or die(mysql_error());
				while($rowlist=mysql_fetch_array($qrylist))
				{
//					echo $rowlist["NAME"]." / ";
					$getccid=$rowlist["CCIDFINAL"];
					
					$qrycheckcrewchange = mysql_query("SELECT CCID,ARRMNLDATE,DEPMNLDATE,DATEEMB,DATEDISEMB 
						FROM crewchange WHERE CCID=$getccid") or die(mysql_error());
				
					if (mysql_num_rows($qrycheckcrewchange) > 0)
					{
						$rowcheckcrewchange=mysql_fetch_array($qrycheckcrewchange);
						$getarrmnldate=$rowcheckcrewchange["ARRMNLDATE"];
						$getdepmnldate=$rowcheckcrewchange["DEPMNLDATE"];
						$getdateemb=$rowcheckcrewchange["DATEEMB"];
						$getdatedisemb=$rowcheckcrewchange["DATEDISEMB"];
						$embdateraw=date("Y-m-d",strtotime($embdate));
						$disembdateraw=date("Y-m-d",strtotime($disembdate));
						
						if (empty($manarrivemnl))
							$manarrivemnlraw="NULL";
						else 
							$manarrivemnlraw = "'".date("Y-m-d",strtotime($manarrivemnl))."'";
						if (empty($mandepartmnl))
							$mandepartmnlraw="NULL";
						else 
							$mandepartmnlraw = "'".date("Y-m-d",strtotime($mandepartmnl))."'";
						
						if(strtotime($getarrmnldate)!=strtotime($manarrivemnl))
							$placearrive="ARRMNLDATE=$manarrivemnlraw,CONFIRMARRMNLBY='$employeeid',CONFIRMARRMNLDATE='$currentdate',";
						else 
							$placearrive="";
						if(strtotime($getdepmnldate)!=strtotime($mandepartmnl))
							$placedepart="DEPMNLDATE=$mandepartmnlraw,CONFIRMDEPBY='$employeeid',CONFIRMDEPDATE='$currentdate',";
						else 
							$placedepart="";
						if(strtotime($getdateemb)!=strtotime($embdate) && !empty($embdate))
							$placedateemb="DATEEMB='$embdateraw',";
						else 
							$placedateemb="";
						if(strtotime($getdatedisemb)!=strtotime($disembdate) && !empty($disembdate))
							$placedatedisemb="DATEDISEMB='$disembdateraw',";
						else 
							$placedatedisemb="";
						
						//GET PORT ID
						$qryembportid=mysql_query("SELECT PORTID FROM port WHERE PORTCOUNTRY='$embcountry' AND PORT='$embport'") or die(mysql_error());
						$rowembportid=mysql_fetch_array($qryembportid);
						$embportid=$rowembportid["PORTID"];
						if(empty($embportid))
							$embportid="NULL";
						$qryarriveupdate = mysql_query("UPDATE crewchange SET 
							$placearrive
							$placedepart
							$placedateemb
							$placedatedisemb
							EMBPORTID=$embportid,MADEBY='$employeeid',MADEDATE='$currentdate'
							WHERE CCID=$getccid") or die(mysql_error());
					}
				}
			}
			
		
		break;
	
	case "injurysave"	:
//			echo "CCID=$ccidhidden";
			$qrycheckinjury = mysql_query("SELECT CCID FROM crewinjury WHERE CCID=$ccidhidden") or die(mysql_error());
			
			if ($maninjurydate != "")
				$maninjurydateraw = date('Y-m-d',strtotime($maninjurydate));
			else 
				$maninjurydateraw = "";
			
			if (mysql_num_rows($qrycheckinjury) == 0)
			{
				$qryinjuryinsert = mysql_query("INSERT INTO crewinjury(CCID,DATEINJURED,REASON,MADEBY,MADEDATE)
										VALUES($ccidhidden,'$maninjurydateraw','$maninjuryreason','$employeeid','$currentdate')
										") or die(mysql_error());
			}
			else 
			{
				$qryinjuryupdate = mysql_query("UPDATE crewinjury SET DATEINJURED='$maninjurydateraw',
															REASON='$maninjuryreason',
															MADEBY='$employeeid',
															MADEDATE='$currentdate'
												WHERE CCID=$ccidhidden
										") or die(mysql_error());
			}
		
		break;
		
	case "reset"	:
			
			$xcrewname = "";
			$xembdate = "";
			$xdisembdate = "";
			$xembport = "";
			
			$mandisembdate = "";
			$mandepartmnl = "";
			$manarrivemnl = "";
		
			$maninjurydate = "";
			$maninjuryreason = "";
			
			$ccidhidden = "";
			
		break;
	case "batchchange":
		$wherebatchno="WHERE BATCHNO=$batchfrom AND OVERDUE1=1";
//				echo "YYY";
//				include("include/qrylistplan.inc");
		if(isset($_POST["vesselcode"]))
			$vesselcode=$_POST["vesselcode"];
		$qrylist=mysql_query("SELECT * FROM (
			SELECT c.CCID,c.APPLICANTNO,c.DATEEMB,c.DATEDISEMB AS DATEDISEMBORIG,c.ARRMNLDATE,c.DEPMNLDATE,
			IF(cw.FNAME IS NULL,CONCAT(cf.FNAME,', ',cf.GNAME),CONCAT(cw.FNAME,', ',cw.GNAME)) AS NAME,
			IF(c.DATECHANGEDISEMB IS NULL,c.DATEDISEMB,c.DATECHANGEDISEMB) AS DATEDISEMB,
			IF(c.DATECHANGEDISEMB IS NULL,0,IF(c.DATECHANGEDISEMB<c.DATEDISEMB,'Shortened','Extended')) AS DATEDISEMBCHANGED,
			c1.CCID AS CCIDEMB,c1.APPLICANTNO AS EMBAPPLICANTNO,
			IF(cw1.FNAME IS NULL,CONCAT(cf1.FNAME,', ',cf1.GNAME),CONCAT(cw1.FNAME,', ',cw1.GNAME)) AS EMBNAME,
			c1.DATEEMB AS EMBDATEEMB,c1.DATEDISEMB AS EMBDATEDISEMB,
			e1.PORT AS EMBPORT,e1.PORTCOUNTRY AS EMBPORTCOUNTRY,
			c.DATECHANGEDATE,ccp.DATECHANGEDATE AS DATECHANGEDATEPROMOTE,CONCAT(em.FNAME,', ',em.GNAME) AS DATECHANGEBY,CONCAT(emp.FNAME,', ',emp.GNAME) AS DATECHANGEBYPROMOTE,
			c.RANKCODE,c1.RANKCODE AS EMBRANKCODE,r.ALIAS2,r1.ALIAS2 AS EMBALIAS2,v.VESSELTYPECODE,v.VESSEL,d.REASON,c.DISEMBREASONCODE,
			ccp.CCID AS CCIDPROMOTE,rp.ALIAS2 AS ALIAS2PROMOTE,ccp.DATEEMB AS DATEEMBPROMOTE,
			IF(ccp.DATECHANGEDISEMB IS NULL,ccp.DATEDISEMB,ccp.DATECHANGEDISEMB) AS DATEDISEMBPROMOTE,
			IF(ccp.CCID IS NULL,c.BATCHNO,ccp.BATCHNO) AS BATCHNO,
			IF(ccp.DATECHANGEDISEMB IS NULL,0,IF(ccp.DATECHANGEDISEMB<ccp.DATEDISEMB,'Shortened','Extended')) AS DATEDISEMBCHANGEDPROMOTE,
			cp1.CCID AS CHKPROMOTE,ccp.ARRMNLDATE AS ARRMNLDATEPROMOTE,ccp.DEPMNLDATE AS DEPMNLDATEPROMOTE,
			IF(ccp.CCID IS NULL,c.CCID,ccp.CCID) AS CCIDFINAL,
			IF(ccp.CCID IS NULL,0,1) AS TAGPROMOTE,
			IF(ccp.CCID IS NULL,e.PORTCOUNTRY,ecp.PORTCOUNTRY) AS PORTCOUNTRY,
			IF(ccp.CCID IS NULL,e.PORT,ecp.PORT) AS PORT,
			IF(ccp.CCID IS NULL,
		    IF(c.DATECHANGEDISEMB IS NULL,c.DATEDISEMB,c.DATECHANGEDISEMB), IF(ccp.DATECHANGEDISEMB IS NULL,
		    ccp.DATEDISEMB,ccp.DATECHANGEDISEMB)) AS DISEMBFINAL,
		    IF(CURRENT_DATE <= IF(ccp.CCID IS NULL,
		    IF(c.DATECHANGEDISEMB IS NULL,c.DATEDISEMB,c.DATECHANGEDISEMB), IF(ccp.DATECHANGEDISEMB IS NULL,
		    ccp.DATEDISEMB,ccp.DATECHANGEDISEMB)),1,0) AS OVERDUE1
			FROM crewchange c
			LEFT JOIN crewchangerelation cr ON c.CCID=cr.CCID
			LEFT JOIN crewchange c1 ON cr.CCIDEMB=c1.CCID
			LEFT JOIN crew cw ON c.APPLICANTNO=cw.APPLICANTNO
			LEFT JOIN crew cw1 ON c1.APPLICANTNO=cw1.APPLICANTNO
			LEFT JOIN crewforeign cf ON c.APPLICANTNO=cf.APPLICANTNO
			LEFT JOIN crewforeign cf1 ON c1.APPLICANTNO=cf1.APPLICANTNO
			LEFT JOIN port e ON c.EMBPORTID=e.PORTID
			LEFT JOIN port e1 ON c1.EMBPORTID=e1.PORTID
			LEFT JOIN employee em ON c.DATECHANGEBY=em.EMPLOYEEID
			LEFT JOIN rank r ON c.RANKCODE=r.RANKCODE
			LEFT JOIN rank r1 ON c1.RANKCODE=r1.RANKCODE
			LEFT JOIN rank rf ON cf.RANKCODE=rf.RANKCODE
			LEFT JOIN rank rf1 ON cf1.RANKCODE=rf1.RANKCODE
			LEFT JOIN vessel v ON c.VESSELCODE=v.VESSELCODE
			LEFT JOIN disembarkreason d ON c.DISEMBREASONCODE=d.DISEMBREASONCODE
			LEFT JOIN crewpromotionrelation cp ON c.CCID=cp.CCID
			LEFT JOIN crewchange ccp ON cp.CCIDPROMOTE=ccp.CCID
			LEFT JOIN port ecp ON ccp.EMBPORTID=ecp.PORTID
			LEFT JOIN employee emp ON ccp.DATECHANGEBY=emp.EMPLOYEEID
			LEFT JOIN rank rp ON rp.RANKCODE=ccp.RANKCODE
			LEFT JOIN crewpromotionrelation cp1 ON c.CCID=cp1.CCIDPROMOTE
			WHERE c.VESSELCODE='$vesselcode') x
			$wherebatchno") or die(mysql_error());
		while($rowlist=mysql_fetch_array($qrylist))
		{
			$getccid=$rowlist["CCIDFINAL"];
			$qryarriveupdate = mysql_query("UPDATE crewchange SET 
				BATCHNO=$batchto,MADEBY='$employeeid',MADEDATE='$currentdate'
				WHERE CCID=$getccid") or die(mysql_error());
		}
	break;
}


$qrydisembreason = mysql_query("SELECT DISEMBREASONCODE,REASON FROM disembarkreason WHERE STATUS=1") or die(mysql_error());
$selectreason = "<option selected value=\"\">--Select One--</option>";

while($rowdisembreason = mysql_fetch_array($qrydisembreason))
{
	$reasoncode = $rowdisembreason["DISEMBREASONCODE"];
	$reason = $rowdisembreason["REASON"];
	
	$selected1 = "";
	
	if ($reasoncode == $mandisembreasoncode)
		$selected1 = "SELECTED";
		
	$selectreason .= "<option $selected1 value=\"$reasoncode\">$reason</option>";
}
	

if ($ccidhidden == "" && empty($batch))
	$disableupdate = "disabled=\"disabled\"";
else 
	$disableupdate = "";

if($tagpromotehidden==1)
	$embreadonly="readonly=\"readonly\"";
include('include/qryvessellist.inc');
//<META HTTP-EQUIV=\"imagetoolbar\" CONTENT=\"no\">

echo	"
<html>\n
<head>\n

<title>Crew Manipulation</title>

<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='crewonboardmanipulationajax.js'></script>
<script type='text/javascript' src='veripro.js'></script>
<script type='text/javascript' src='ajax.js'></script>

<script language=\"javascript\" src=\"popcalendar.js\"></script>

<script>

var url;
var fillurl;
var represults;


function createurl(x)
{
	var actionajax = document.crewonboard.actionajax.value; 
	
	if (x==1)
	{
		url = \"http://$getserveraddr/veritas/crewchangeplanrequest.php?actionajax=\"; // The server-side script 
		
		var embapplicantnohidden = '&embapplicantnohidden=' + document.crewonboard.applicantnohidden.value;
		var ccidhidden = '&ccidhidden=' + document.crewonboard.ccidhidden.value;
		var batchnohidden = '&batchnohidden=' + document.crewonboard.batchnohidden.value;
		fillurl = actionajax + embapplicantnohidden + ccidhidden + batchnohidden;
	}
	
	if (x==2)
	{
		url = \"http://$getserveraddr/veritas/crewonboardmanipulationrequest.php?actionajax=\"; // The server-side script 
		
		var embapplicantnohidden = '&embapplicantnohidden=' + document.crewonboard.applicantnohidden.value;
		var ccidhidden = '&ccidhidden=' + document.crewonboard.ccidhidden.value;
		fillurl = actionajax + embapplicantnohidden + ccidhidden;
	}	
	
	if (x==3)
	{
		url = \"http://$getserveraddr/veritas/crewevaluation.php?ccid=\"; // The server-side script 
		
//		var embapplicantnohidden = '&embapplicantnohidden=' + document.crewonboard.applicantnohidden.value;
//		var ccidhidden = '&ccidhidden=' + document.crewonboard.ccidhidden.value;
		fillurl = ccidhidden;
	}
	
	getvalues();
}

function switchajax(x)
{
	if(x==1) // 1 if loading box is visible
	{
	var strdisplay='none';
	var strdisplayprogress='block';
	var strdisable='disabled';
	}
	else
	{
	var strdisplay='block';
	var strdisplayprogress='none';
	var strdisable='';
	}

//	document.crewonboard.manreasoncode.style.display=strdisplay;
	document.getElementById('ajaxprogress').style.display=strdisplayprogress;
}

function selecttab(x,y)
{
	if(document.getElementById('ajaxprogress').style.display=='none')
	{
		document.crewonboard.prevpool.value=document.getElementById('currentlipool').name;
		document.getElementById('currentlipool').id=document.crewonboard.prevpool.value;
		document.getElementById('a'+x).id='currentlipool';
		
		//get previous select
		var getprevlen=document.crewonboard.prevpool.value.length;
		var getprev=document.crewonboard.prevpool.value.substring(1,getprevlen);
		
		with(document.crewonboard)
		{
			eval(getprev+'.id=\'\'');
			eval(x+'.id=\'currentpool\'');
		}
		document.getElementById('x'+getprev).style.display='none';
		document.getElementById('x'+x).style.display='block';
	}
	with(document.crewonboard)
	{
		if(x=='arrival')
		{
			if(mandepartmnl.disabled==false)
				mandepartmnl.focus();
			if(manarrivemnl.disabled==false)
				manarrivemnl.focus();
		}
		if(x=='pni')
		{
			if(maninjurydate.disabled==false)
				maninjurydate.focus();
		}
	}
}


function checkupdate(x)
{
//	var rem = '';
//	
//	with (document.crewonboard)
//	{
//		if(mandisembdate.value=='' || mandisembdate.value==null)
//			if(rem=='')
//				rem='Date Disembark';
//			else
//				rem=rem + ',Date Disembark';
//		if(maninjuryreason.value=='' || maninjuryreason.value==null)
//		{
//			if(rem=='')
//				rem='Injury Reason';
//			else
//				rem=rem + ',Injury Reason';
//		}
//		
//		if(rem=='')
//		{
//			submit();
//		}
//		else
//			alert('Invalid Input: ' + rem);		
//	}
}
function chkdates(x) // 1-depart; 2-arrive; 3-pni
{
	with(document.crewonboard)
	{
		if(x==1)
		{
//			alert(mandepartmnl.value+'>'+xembdate.value);
			if(getstringdatetime(mandepartmnl.value)>getstringdatetime(embdate.value))
			{
				alert('Depart Manila Date should be EARLIER THAN Embark Date!');
				mandepartmnl.value='';
				mandepartmnl.focus();
			}
//			if(getstringdatetime(mandepartmnl.value)>getstringdatetime('$currentdate1'))
//			{
//				alert('Date is in the future!');
//				mandepartmnl.value='';
//				mandepartmnl.focus();
//			}
		}
		if(x==2)
		{
			if(getstringdatetime(manarrivemnl.value)<getstringdatetime(disembdate.value))
			{
				alert('Arrive Manila Date should be LATER THAN Disembark date!');
				manarrivemnl.value='';
				manarrivemnl.focus();
			}
//			else
//			{
//				if(getstringdatetime(manarrivemnl.value)<=getstringdatetime(mandepartmnl.value))
//				{
//					alert('Date is before Departure Manila!');
//					manarrivemnl.value='';
//					manarrivemnl.focus();
//				}
//				if(getstringdatetime(manarrivemnl.value)<=getstringdatetime(xdisembdate.value))
//				{
//					alert('Date is before Disembark date!');
//					manarrivemnl.value='';
//					manarrivemnl.focus();
//				}
//			}
		}
		if(x==3)
		{
			if(getstringdatetime(maninjurydate.value)>getstringdatetime('$currentdate1'))
			{
				alert('Date is in the future!');
				maninjurydate.value='';
				maninjurydate.focus();
			}
			else
			{
				if(getstringdatetime(maninjurydate.value)<getstringdatetime(xembdate.value))
				{
					alert('Date is before Embark date!');
					maninjurydate.value='';
					maninjurydate.focus();
				}
				if(getstringdatetime(maninjurydate.value)>getstringdatetime(xdisembdate.value))
				{
					alert('Date is after Disembark date!');
					maninjurydate.value='';
					maninjurydate.focus();
				}
			}
		}
		if(x==4)
		{
			if(disembdate.value!='')
			{
				if(getstringdatetime(embdate.value)>=getstringdatetime(disembdate.value))
				{
					alert('Embark Date should be EARLIER THAN Disembark Date!');
					embdate.value='';
					embdate.focus();
				}
			}
		}
		if(x==5)
		{
			if(embdate.value!='')
			{
				if(getstringdatetime(disembdate.value)<=getstringdatetime(embdate.value))
				{
					alert('Disembark Date should be LATER THAN Embark Date!');
					disembdate.value='';
					disembdate.focus();
				}
			}
		}
	}
}
function setClipBoardData(){

     setInterval(\"window.clipboardData.setData('text','')\",20);

} 
function chkbtnloading()
{
	if(document.getElementById('ajaxprogress').style.display=='none' && 
		document.getElementById('changebatch').style.display=='none')
		return 1;
	else
		return 0;
}
function chkchangebatch()
{
	with(document.crewonboard)
	{
		if(batchselect.value!='')
		{
			actionajax.value='savechangebatch';
			batchnohidden.value=batchselect.value;
			document.getElementById('ajaxprogress').style.display='block';
			createurl(1);
//			resetdata(0);
			document.getElementById('ajaxprogress').style.display='none';
			batchnohidden.value='';
//			crewselect.focus();
		}
		else
			alert('You have to select a batch!');
	}
	
}
function fillvesselselect(x)
{
	document.crewonboard.submit();
}
function selectbatch()
{
	with(document.crewonboard)
	{
//		ccidhidden.value='';
		btnupdate2.disabled=false;
		embcountry.value='';
		embport.value='';
		embdate.value='';
		disembdate.value='';
		mandepartmnl.value='';
		manarrivemnl.value='';
	}
}
</script>

</head>\n

<body style=\"overflow:hidden;\" onload=\"\">\n

<form name=\"crewonboard\" method=\"POST\">\n

<span class=\"wintitle\">CREW ON-BOARD MANIPULATION</span>

	<div style=\"width:390px;height:630px;background-color:#DCDCDC;float:left;border-right:1px solid black;\">
	
		<span class=\"sectiontitle\">CREW INFORMATION</span>
		<br />
		<div style=\"width:390px;height:150px;background-color:#DCDCDC;\">
		
			<table class=\"listrow\">
				<tr>
					<th>Crew Name</th>
					<td><input type=\"text\" name=\"xcrewname\" readonly=\"readonly\" value=\"$xcrewname\" size=\"40\"></td>
				</tr>
				<tr>
					<th>Date Embark</th>
					<td><input type=\"text\" name=\"xembdate\" readonly=\"readonly\" value=\"$xembdate\" size=\"10\"></td>
				</tr>
				<tr>
					<th>Date Disembark</th>
					<td><input type=\"text\" name=\"xdisembdate\" readonly=\"readonly\" value=\"$xdisembdate\" size=\"10\"></td>
				</tr>
				<tr>
					<th>Embarked/Port</th>
					<td><input type=\"text\" name=\"xembport\" readonly=\"readonly\" value=\"$xembport\" size=\"30\"></td>
				</tr>
			</table>
		</div>
		
		<div style=\"width:390px;height:450px;background-color:White;\">

			<div id=\"tabsite\" style=\"width:100%;\">
				<ul>
					<!--<li id=\"currentlipoolaa\" name=\"adisembark\"><a name=\"disembark\" onclick=\"selecttab(this.name,1);\"><span style=\"width:80px;text-align:center;\">Disembark</span></a></li>-->
					<li id=\"currentlipool\" name=\"aarrival\"><a name=\"arrival\" onclick=\"selecttab(this.name,1);\" id=\"currentpool\"><span style=\"width:80px;text-align:center;\">Arrival/Depart</span></a></li>
					<li id=\"apni\" name=\"apni\"><a name=\"pni\" onclick=\"selecttab(this.name,1);\"><span style=\"width:80px;text-align:center;\">PNI</span></a></li>
				</ul>
			</div>
			
			<div id=\"xarrival\" style=\"width:100%;display:block;height:370px;\">
				<br />
				<span class=\"sectiontitle\">Arrival Date / Departure Date</span>
				<br />
				
				<center>
				<table class=\"listrow\" width=\"80%\">
					<tr>
						<th>Batch.</th>
						<td>
							<select name=\"batch\" style=\"font-size:10pt;\" 
								onchange=\"selectbatch();\">
								<option value=\"\">-Select-</option>";
								for($i=1;$i<=20;$i++)
								{
									if($i==1)
										$batchlistshow="1st";
									elseif($i==2)
										$batchlistshow="2nd";
									elseif($i==3)
										$batchlistshow="3rd";
									else 
										$batchlistshow=$i."th";
									if($batch==$i)
										$selected="selected";
									else 
										$selected="";
									echo "<option $selected value=\"$i\">$batchlistshow</option>\n";
								}
						echo "
							</select>
						</td>
					</tr>
					<tr>
						<th>Port.</th>
						<td>
							<select name=\"embcountry\" style=\"width:170px;font-size:10pt;\" 
								onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
								onchange=\"submit();\">\n
								<option value=\"\">-Country-</option>";
								while($rowembcountry=mysql_fetch_array($qryembcountry))
								{
									$embcountry1=$rowembcountry['PORTCOUNTRY'];
									if($embcountry==$embcountry1)
										$selected="selected";
									else 
										$selected="";
									echo "<option $selected value=\"$embcountry1\">$embcountry1</option>\n";
								}
						echo "
							</select>&nbsp;&nbsp;
							$getembport
						</td>
					</tr>
				</table>
				<br>
				<table class=\"listrow\" width=\"80%\">
					<tr>
						<th>Date Departure Manila</th>
						<td>
							<input type=\"text\" name=\"mandepartmnl\" value=\"$mandepartmnl\" size=\"10\" 
								onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" 
								onkeydown=\"chkdatedown(this);\"
								onblur=\"if(this.value!=''){chkdates(1);}\">
							<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" 
								onclick=\"popUpCalendar(mandepartmnl, mandepartmnl, 'mm/dd/yyyy', 0, 0);return false;\">
						</td>
						
					</tr>
					<tr>
						<th>Date Embark</th>
						<td>
							<input type=\"text\" name=\"embdate\" value=\"$embdate\" size=\"10\" $embreadonly 
								onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" 
								onkeydown=\"chkdatedown(this);\"
								onblur=\"if(this.value!=''){chkdates(4);}\">
							<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" 
								onclick=\"popUpCalendar(embdate, embdate, 'mm/dd/yyyy', 0, 0);return false;\">
						</td>
					</tr>
					<tr>
						<th>Date Disembark</th>
						<td>
							<input type=\"text\" name=\"disembdate\" value=\"$disembdate\" size=\"10\" 
								onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" 
								onkeydown=\"chkdatedown(this);\"
								onblur=\"if(this.value!=''){chkdates(5);}\">
							<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" 
								onclick=\"popUpCalendar(disembdate, disembdate, 'mm/dd/yyyy', 0, 0);return false;\">
						</td>
					</tr>
					<tr>
						<th>Date Arrival Manila</th>
						<td>
							<input type=\"text\" name=\"manarrivemnl\" value=\"$manarrivemnl\" size=\"10\" 
							onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" 
							onkeydown=\"chkdatedown(this);\"
							onblur=\"if(this.value!=''){chkdates(2);}\">
							<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(manarrivemnl, manarrivemnl, 'mm/dd/yyyy', 0, 0);return false;\">
						</td>
					</tr>
					<tr>
						<th>Disembark Reason</th>
						<td><select name=\"mandisembreasoncode\">
								$selectreason
							</select>
						</td>
					</tr>
					<tr>
						<th>&nbsp;</th>
						<td align=\"left\"><input type=\"button\" value=\"Update\" $disableupdate name=\"btnupdate2\"
											onclick=\"if((embdate.value=='' || disembdate.value=='') && batch.value==''){alert('Embark and Disembark date should not be empty!');}else{actiontxt.value='arrivesave';crewonboard.submit();}\"/></td>
					</tr>
				</table>
				</center>
			</div>
			<div id=\"xpni\" style=\"width:100%;display:none;height:370px;\">
				<br />
				<span class=\"sectiontitle\">PNI / Injury</span>
				<br />
				
				<center>
				<table class=\"listrow\" width=\"80%\">
					<tr>
						<th>Date of Injury</th>
						<td>
							<input type=\"text\" name=\"maninjurydate\" value=\"$maninjurydate\" size=\"10\" 
								onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" 
								onkeydown=\"chkdatedown(this);\"
								onblur=\"if(this.value!=''){chkdates(3)}\">
							<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(maninjurydate, maninjurydate, 'mm/dd/yyyy', 0, 0);return false;\">
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<th>Reason</th>
						<td>
							<textarea rows=\"3\" cols=\"20\" name=\"maninjuryreason\">$maninjuryreason</textarea>
						</td>
						
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td align=\"left\"><input type=\"button\" value=\"Update\" $disableupdate name=\"btnupdate3\"
									onclick=\"actiontxt.value='injurysave';crewonboard.submit();\" /></td>
					</tr>
				</table>
				</center>
				
			</div>
		
		</div>
	</div>
	";
$wherebatchno="";
include("veritas/include/crewonboard.inc");
include("veritas/include/ajaxprogress.inc");

echo "
	<input type=\"hidden\" name=\"actionajax\">
	<input type=\"hidden\" name=\"prevpool\">
	<input type=\"hidden\" name=\"actiontxt\">
	<input type=\"hidden\" name=\"batchnohidden\">
	<input type=\"hidden\" name=\"embporthidden\">
	<input type=\"hidden\" name=\"tagpromotehidden\">
	<input type=\"hidden\" name=\"batchfrom\">
	<input type=\"hidden\" name=\"batchto\">
</form>
</body>
</html>\n";
?>
