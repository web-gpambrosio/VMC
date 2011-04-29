<?php

session_start();

include('veritas/connectdb.php');
//include('connectdb.php');

include('veritas/include/stylephp.inc');

$datetimenow=date("Y-m-d H:i:s");
$datenow=date("Y-m-d");
$datenowshow=date("m/d/Y");

$basedir = "scanned"; //change if different directory

if(isset($_SESSION['employeeid']))
	$employeeid=$_SESSION['employeeid'];
$employeeid="123"; //temporary for testing purpose only

$actionajax = $_GET['actionajax'];
$embapplicantnohidden = $_GET['embapplicantnohidden'];
$ccidhidden = $_GET['ccidhidden'];


$dateembraw=date("Y-m-d",strtotime($dateemb));
$datedisembraw=date("Y-m-d",strtotime($datedisemb));

$result="";

	
switch ($actionajax)
{
	case "crewinfo":
			
			$qrygetinfo = mysql_query("SELECT CONCAT(cr.FNAME,', ',cr.GNAME,' ',cr.MNAME) AS NAME,cc.DATEEMB,
									IF(cc.DATECHANGEDISEMB is null,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,
									cc.DEPMNLDATE,cc.ARRMNLDATE,p.PORT,p.PORTCOUNTRY,cc.DISEMBREASONCODE,
									cc.CONFIRMARRMNLBY,cc.CONFIRMARRMNLDATE,cc.CONFIRMDEPBY,cc.CONFIRMDEPDATE,
									cc1.CCID AS CCIDPROMOTE,cc1.CONFIRMARRMNLBY AS CONFIRMARRMNLBYPROMOTE,
									cc1.CONFIRMARRMNLDATE AS CONFIRMARRMNLDATEPROMOTE,cc1.CONFIRMDEPBY AS CONFIRMDEPBYPROMOTE,
									cc1.CONFIRMDEPDATE AS CONFIRMDEPDATEPROMOTE,
									cc1.DATEEMB AS DATEEMBPROMOTE,
									IF(cc1.DATECHANGEDISEMB is null,cc1.DATEDISEMB,cc1.DATECHANGEDISEMB) AS DATEDISEMBPROMOTE
									FROM crewchange cc
									LEFT JOIN crew cr ON cr.APPLICANTNO=cc.APPLICANTNO
									LEFT JOIN port p ON p.PORTID=cc.EMBPORTID
									LEFT JOIN crewpromotionrelation cp ON cc.CCID=cp.CCID
									LEFT JOIN crewchange cc1 ON cp.CCIDPROMOTE=cc1.CCID
									WHERE CCID=$ccidhidden") or die(mysql_error());
			
			if (mysql_num_rows($qrygetinfo) > 0)
			{
				$rowgetinfo = mysql_fetch_array($qrygetinfo);
				
				$crewname = $rowgetinfo["NAME"];
				if (!empty($rowgetinfo["DATEEMB"]))
					$crewembdate = date('m/d/Y',strtotime($rowgetinfo["DATEEMB"]));
				else 
					$crewembdate = "";
					
				if (!empty($rowgetinfo["DATEDISEMB"]))
					$crewdisembdate = date('m/d/Y',strtotime($rowgetinfo["DATEDISEMB"]));
				else 
					$crewdisembdate = "";
				
				if (!empty($rowgetinfo["DEPMNLDATE"]))
					$crewdepdate = date('m/d/Y',strtotime($rowgetinfo["DEPMNLDATE"]));
				else 
					$crewdepdate = "";
					
				$crewconfirmdepby = $rowgetinfo["CONFIRMDEPBY"];
					
				if (!empty($rowgetinfo["CONFIRMDEPDATE"]))
					$crewconfirmdepdate = date('m/d/Y',strtotime($rowgetinfo["CONFIRMDEPDATE"]));
				else 
					$crewconfirmdepdate = "";
					
				if (!empty($rowgetinfo["ARRMNLDATE"]))
					$crewarrdate = date('m/d/Y',strtotime($rowgetinfo["ARRMNLDATE"]));
				else 
					$crewarrdate = "";
					
				$crewconfirmarrmnlby = $rowgetinfo["CONFIRMARRMNLBY"];
					
				if (!empty($rowgetinfo["CONFIRMARRMNLDATE"]))
					$crewconfirmarrmnldate = date('m/d/Y',strtotime($rowgetinfo["CONFIRMARRMNLDATE"]));
				else 
					$crewconfirmarrmnldate = "";
					
				$crewdisembreason = $rowgetinfo["DISEMBREASONCODE"];
				
				$crewembport = $rowgetinfo["PORT"] . ", " . $rowgetinfo["PORTCOUNTRY"];
//				cc1.CCID AS CCIDPROMOTE,cc1.CONFIRMARRMNLBY AS CONFIRMARRMNLBYPROMOTE,
//				cc1.CONFIRMARRMNLDATE AS CONFIRMARRMNLDATEPROMOTE,cc1.CONFIRMDEPBY AS CONFIRMDEPBYPROMOTE,
//				cc1.CONFIRMDEPDATE AS CONFIRMDEPDATEPROMOTE,
//				cc1.DATEEMB AS DATEEMBPROMOTE,
//				IF(cc1.DATECHANGEDISEMB is null,cc1.DATEDISEMB,cc1.DATECHANGEDISEMB) AS DATEDISEMBPROMOTE
				
			}
			
			$qrycrewpni = mysql_query("SELECT DATEINJURED,REASON FROM crewinjury WHERE CCID=$ccidhidden") or die(mysql_error());
			
			if (mysql_num_rows($qrycrewpni) > 0)
			{
				$rowcrewpni = mysql_fetch_array($qrycrewpni);
				
				if ($rowcrewpni["DATEINJURED"])
					$pnidate = date('m/d/Y',strtotime($rowcrewpni["DATEINJURED"]));
				else 
					$pnidate = "";
					
				$pnireason = $rowcrewpni["REASON"];
			}

			$resulttemp1 = $crewname."^".$crewembdate."^".$crewdisembdate."^".$crewembport."^".$crewdisembreason;
			$resulttemp2 = $crewdepdate."^".$crewarrdate."^".$crewconfirmdepby."^".$crewconfirmdepdate."^".$crewconfirmarrmnlby."^".$crewconfirmarrmnldate;
			$resulttemp3 = $pnidate."^".$pnireason;
			
		break;
}



$result=$actionajax."|".$resulttemp1."|".$resulttemp2."|".$resulttemp3;

echo $result; 


?>