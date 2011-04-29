<?php

session_start();

include('veritas/connectdb.php');
//include('connectdb.php');

include('veritas/include/stylephp.inc');

$datetimenow=date("Y-m-d H:i:s");
$datenow=date("Y-m-d");
$datenowshow=date("m/d/Y");

$basedir = "docimages"; //change if different directory

//if(isset($_SESSION['employeeid']))
//	$employeeid=$_SESSION['employeeid'];
//$employeeid="123"; //temporary for testing purpose only

$employeeid = $_GET['employeeid'];
$divcode = $_GET['divcode'];
$actionajax = $_GET['actionajax'];
$poolchoice = $_GET['poolchoice'];
$rankalias = $_GET['rankalias'];
$rankcode = $_GET['rankcode'];
$rankcodehidden = $_GET['rankcodehidden'];
$vesselcode = $_GET['vesselcode'];
$vesseltypecode = $_GET['vesseltypecode'];
$batchlimit = $_GET['batchlimit'];
$orderby = $_GET['orderby'];
$sortby = $_GET['sortby'];
$embcountry = $_GET['embcountry'];
$embport = $_GET['embport'];
$ccpno = $_GET['ccpno'];
$ccidhidden = $_GET['ccidhidden'];
$searchfname = $_GET['searchfname'];
$searchgname = $_GET['searchgname'];
$embccidhidden = $_GET['embccidhidden'];
$dateemb = $_GET['dateemb'];
$datedisemb = $_GET['datedisemb'];
$embapplicantnohidden = $_GET['embapplicantnohidden'];
$addnewcrew = $_GET['addnewcrew'];
$changedatedisemb = $_GET['changedatedisemb'];
$disembreasoncode = $_GET['disembreasoncode'];
$promoteembdate = $_GET['promoteembdate'];
$promoteccid = $_GET['promoteccid'];
$promoteappno = $_GET['promoteappno'];
$batchnohidden = $_GET['batchnohidden'];

$dateembraw=date("Y-m-d",strtotime($dateemb));
$datedisembraw=date("Y-m-d",strtotime($datedisemb));
$changedatedisembraw=date("Y-m-d",strtotime($changedatedisemb));

if(empty($sortby))
	$sortby="col1";
$flagccpno=0; // flag for adding ccpno

//GET APPLICANTNO OF ONBOARD CREW
if(!empty($ccidhidden))
{
	$qrygetapplicantno=mysql_query("SELECT APPLICANTNO FROM crewchange WHERE CCID=$ccidhidden") or die(mysql_error());
	$rowgetapplicantno=mysql_fetch_array($qrygetapplicantno);
	$getapplicantno=$rowgetapplicantno["APPLICANTNO"];
}

if(!empty($embapplicantnohidden))
{
	$qrygetcrewcode=mysql_query("SELECT CREWCODE FROM crew WHERE APPLICANTNO=$embapplicantnohidden") or die(mysql_error());
	$rowgetcrewcode=mysql_fetch_array($qrygetcrewcode);
	$searchcrewcode=$rowgetcrewcode["CREWCODE"];
}

$result="";
switch ($actionajax)
{
	case "savechangebatch":
			if($batchnohidden==0)
				$changebatchno=999;
			else 
				$changebatchno=$batchnohidden;
			$qrybatch = mysql_query("UPDATE crewchange SET BATCHNO='$changebatchno',MADEBY='$employeeid',
				MADEDATE='$datetimenow' WHERE CCID='$ccidhidden'") or die(mysql_error());
			
			$actionajax = "vesselselect";
		
	break;
	case "savebatchno":

			$qrybatch = mysql_query("UPDATE crewchange SET BATCHNO='$batchnohidden',MADEBY='$employeeid',MADEDATE='$datetimenow' WHERE CCID='$ccidhidden'") or die(mysql_error());
			
			$actionajax = "vesselselect";
		
	break;
	case "getpromotionrelation":
		$qrygetccid=mysql_query("SELECT CCID
			FROM crewchange 
			WHERE (ABS(DATEDIFF(DATEDISEMB,'$promoteembdate'))<=5 OR ABS(DATEDIFF(DATECHANGEDISEMB,'$promoteembdate'))<=5) 
			AND VESSELCODE='$vesselcode' 
			AND APPLICANTNO=$promoteappno AND CCID<>$promoteccid") or die(mysql_error());
		$rowgetccid=mysql_fetch_array($qrygetccid);
		$getccid=$rowgetccid["CCID"];
		$qrysave=mysql_query("INSERT INTO crewpromotionrelation (CCID,CCIDPROMOTE,MADEBY,MADEDATE) 
			VALUES($getccid,$promoteccid,'$employeeid','$datetimenow')") or die(mysql_error());
		$actionajax="vesselselect";
	break;
	case "savechangedate":
		//get original datedisemb
		$qrydisemb=mysql_query("SELECT DATEDISEMB FROM crewchange WHERE CCID=$embccidhidden") or die(mysql_error());
		$rowdisemb=mysql_fetch_array($qrydisemb);
		$getdatedisemborig=date("Y-m-d",strtotime($rowdisemb["DATEDISEMB"]));
		if($getdatedisemborig==$changedatedisembraw)
		{
			$qrysavechangedate=mysql_query("UPDATE crewchange SET 
				DATECHANGEDISEMB=NULL,DISEMBREASONCODE=NULL,DATECHANGEBY='$employeeid',DATECHANGEDATE='$datetimenow'
				WHERE CCID=$embccidhidden") or die(mysql_error());
		}
		else 
		{
			$qrysavechangedate=mysql_query("UPDATE crewchange SET 
				DATECHANGEDISEMB='$changedatedisembraw',DISEMBREASONCODE='$disembreasoncode',DATECHANGEBY='$employeeid',
				DATECHANGEDATE='$datetimenow'
				WHERE CCID=$embccidhidden") or die(mysql_error());
		}
		$actionajax="vesselselect";
	break;
	case "saveembarkcrew":
		$allowinsert=1;
		if(!empty($embcountry))
		{
			$qryembportid=mysql_query("SELECT PORTID FROM port WHERE PORTCOUNTRY='$embcountry' AND PORT='$embport'") or die(mysql_error());
			$rowembportid=mysql_fetch_array($qryembportid);
			$embportid=$rowembportid["PORTID"];
			if(empty($embportid))
				$embportid="null";
		}
		else 
			$embportid="null";
		if(empty($addnewcrew))
		{
			//check if partner already exists
			$qrychkpartner=mysql_query("SELECT CCIDEMB FROM crewchangerelation WHERE CCID=$ccidhidden") or die(mysql_error());
			$rowchkpartner=mysql_fetch_array($qrychkpartner);
			$chkccidemb=$rowchkpartner["CCIDEMB"];
			if(!empty($chkccidemb)) //if not empty then delete relation
			{
				if($chkccidemb==$embccidhidden)
				{
					$qrysave=mysql_query("UPDATE crewchange SET DATEEMB='$dateembraw',DATEDISEMB='$datedisembraw',
						EMBPORTID=$embportid,MADEBY='$employeeid',MADEDATE='$datetimenow' 
						WHERE CCID=$embccidhidden") or die(mysql_error());
					$allowinsert=0;
				}
				else 
				{
					//DELETE CREWCHANGE
					$qrydeletecrew=mysql_query("DELETE FROM crewchange WHERE CCID=$embccidhidden") or die(mysql_error());
					//DELETE RELATION
					$qrydeleterelation=mysql_query("DELETE FROM crewchangerelation WHERE CCID=$ccidhidden AND CCIDEMB=$embccidhidden") or die(mysql_error());
				}
			}
		}
		if($allowinsert==1)
		{
			$qrysave=mysql_query("INSERT INTO crewchange (APPLICANTNO,VESSELCODE,DATEEMB,DATEDISEMB,EMBPORTID,RANKCODE,MADEBY,MADEDATE)
				VALUES ('$embapplicantnohidden','$vesselcode','$dateembraw','$datedisembraw',$embportid,'$rankcodehidden','$employeeid','$datetimenow')") or die(mysql_error());
			if(empty($addnewcrew))
			{
				//GET CCID OF EMBARKING CREW
				$qryembccid=mysql_query("SELECT MAX(CCID) AS CCIDEMB FROM crewchange WHERE MADEBY='$employeeid'") or die(mysql_error());
				$rowembccid=mysql_fetch_array($qryembccid);
				$maxembccid=$rowembccid["CCIDEMB"];
				//CREATE RELATIONSHIP
				$qryrelate=mysql_query("INSERT INTO crewchangerelation (CCID,CCIDEMB) VALUES ($ccidhidden,$maxembccid)") or die(mysql_error());
			}
		}
		$actionajax="vesselselect";
	break;
	case "deleteembarkcrew":
		$qrydeletecrew=mysql_query("DELETE FROM crewchange WHERE CCID=$embccidhidden") or die(mysql_error());
		//DELETE RELATION
		$qrydeleterelation=mysql_query("DELETE FROM crewchangerelation WHERE CCID=$ccidhidden AND CCIDEMB=$embccidhidden") or die(mysql_error());
		//DELETE PROMOTION RELATION
		$qrydeletepromotionrelation=mysql_query("DELETE FROM crewpromotionrelation WHERE CCIDPROMOTE=$embccidhidden") or die(mysql_error());
		$actionajax="vesselselect";
	break;
	case "ccpno":
		if(!empty($ccpno))
		{
			$qrychkccpno=mysql_query("SELECT c.VESSELCODE,v.DIVCODE,
				ch.PREPAREDBY,ch.PREPAREDDATE,ch.APPROVEDBY1,ch.APPROVEDDATE1,ch.APPROVEDBY2,ch.APPROVEDDATE2,ch.APPROVEDBY3,ch.APPROVEDDATE3
				FROM crewchangeplandtl cd
				LEFT JOIN crewchange c ON cd.CCID=c.CCID
				LEFT JOIN crewchangeplanhdr ch ON cd.CCPNO=ch.CCPNO
				LEFT JOIN vessel v ON c.VESSELCODE=v.VESSELCODE
				WHERE cd.CCPNO=$ccpno LIMIT 1") or die(mysql_error());
			if(mysql_num_rows($qrychkccpno)>0)
			{
				$getccpno=$ccpno;
				$rowchkccpno=mysql_fetch_array($qrychkccpno);
				$getdivcode=$rowchkccpno["DIVCODE"];
				$vesselcode=$rowchkccpno["VESSELCODE"];
				$preparedby=$rowchkccpno["PREPAREDBY"];
				$prepareddate=$rowchkccpno["PREPAREDDATE"];
				$approvedby1=$rowchkccpno["APPROVEDBY1"];
				$approveddate1=$rowchkccpno["APPROVEDDATE1"];
				$approvedby2=$rowchkccpno["APPROVEDBY2"];
				$approveddate2=$rowchkccpno["APPROVEDDATE2"];
				$approvedby3=$rowchkccpno["APPROVEDBY3"];
				$approveddate3=$rowchkccpno["APPROVEDDATE3"];
			}
			else 
			{
				$getccpno=0;
			}
			
			$actionajax="vesselselect";
		}
		else 
		{
			if(!empty($vesselcode))
				$actionajax="vesselselect";
		}
		if($divcode!=$getdivcode)
			$actionajax="wrongdivcode";
		if(empty($getdivcode))
			$actionajax="nodivcode";
	break;
	case "getccpno":
		$qrysaveccpno=mysql_query("INSERT INTO crewchangeplanhdr (PREPAREDBY,PREPAREDDATE) VALUES('$employeeid','$datetimenow')") or die(mysql_error());
		$qrygetccpno=mysql_query("SELECT CCPNO FROM crewchangeplanhdr WHERE PREPAREDBY='$employeeid' AND PREPAREDDATE='$datetimenow'") or die(mysql_error());
		$rowgetccpno=mysql_fetch_array($qrygetccpno);
		$getccpno=$rowgetccpno["CCPNO"];
		$flagccpno=1;
		$actionajax="vesselselect";
	break;
}


if($actionajax=="viewonboard201")
	$actionajax="view201";
	
switch ($actionajax)
{
	case "view201":
		$qrygetcrew = mysql_query("SELECT CREWCODE,CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,
									CONCAT(ADDRESS,', ',MUNICIPALITY,' ',CITY,' ',ZIPCODE) AS ADDRESS,
									TELNO,BIRTHDATE,BIRTHPLACE,GENDER,CIVILSTATUS,NATIONALITY,RELIGION,
									SSS,TIN,WEIGHT,HEIGHT,EMAIL,OFWNO,t.TRADEROUTE AS PREFROUTE,
									cf.FASTTRACKCODE AS FASTTRACK,
	                				cs.SCHOLASTICCODE AS SCHOLAR,
									a.RECOMMENDEDBY,REMARKS
									FROM crew c
									LEFT JOIN traderoute t ON t.TRADEROUTECODE=c.PREFROUTE
									LEFT JOIN applicant a ON a.APPLICANTNO=c.APPLICANTNO
	                				LEFT JOIN crewscholar cs ON cs.APPLICANTNO=c.APPLICANTNO
	                				LEFT JOIN crewfasttrack cf ON cf.APPLICANTNO=c.APPLICANTNO
									WHERE c.APPLICANTNO=$embapplicantnohidden") or die(mysql_error());
		
		$rowgetcrew = mysql_fetch_array($qrygetcrew);
		
		$personalcrewcode = $rowgetcrew["CREWCODE"];
		$personalname = $rowgetcrew["NAME"];
		$personaladdress = $rowgetcrew["ADDRESS"];
		$personaltelno = $rowgetcrew["TELNO"];
		$personalbdate = date('m/d/Y',strtotime($rowgetcrew["BIRTHDATE"]));
		
	//	$age = number_format((strtotime($datetimenow) - strtotime($bdate)) / (86400*365.25),0);
		
		$personalage = floor((strtotime($datetimenow) - strtotime($personalbdate)) / (86400*365.25));
		
		$personalbplace = $rowgetcrew["BIRTHPLACE"];
		$personalgender = $rowgetcrew["GENDER"];
		
		if ($personalgender == "M")
			$personalgender = "MALE";
		else 
			$personalgender = "FEMALE";
		
		$personalcivilstatus = $rowgetcrew["CIVILSTATUS"];
		
		switch ($personalcivilstatus)
		{
			case "S":
					$personalcivilstatus = "SINGLE";
				break;
			case "M":
					$personalcivilstatus = "MARRIED";
				break;
			case "W":
					$personalcivilstatus = "WIDOW";
				break;
		}
		
		$personalnationality = $rowgetcrew["NATIONALITY"];
		$personalreligion = $rowgetcrew["RELIGION"];
		$personalsss = $rowgetcrew["SSS"];
		$personaltin = $rowgetcrew["TIN"];
		$personalweight = $rowgetcrew["WEIGHT"];
		$personalheight = $rowgetcrew["HEIGHT"];
		$personalemail = $rowgetcrew["EMAIL"];
		$personalofwno = $rowgetcrew["OFWNO"];
		$personalprefroute = $rowgetcrew["PREFROUTE"];
		$personalfasttrack = $rowgetcrew["FASTTRACK"];
		$personalscholar = $rowgetcrew["SCHOLAR"];
		$personalrecommendedby = $rowgetcrew["RECOMMENDEDBY"];
		$personalremarks = $rowgetcrew["REMARKS"];
		
		$scftshow="";
		if(!empty($personalfasttrack))
			$scftshow="FAST TRACK ($personalfasttrack)";
		if(!empty($personalscholar))
			$scftshow="SCHOLAR ($personalscholar)";
	
		
		$qryfamilylist = mysql_query("SELECT CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,CONCAT(ADDRESS,', ',MUNICIPALITY,' ',CITY) AS ADDRESS, 
									fr.RELATION,TELNO
									FROM crewfamily cf
									LEFT JOIN familyrelation fr ON fr.RELCODE=cf.RELCODE
									WHERE fr.RELCODE <> 'HIM' AND
									APPLICANTNO=$embapplicantnohidden") or die(mysql_error());	
	
		$qrydoclist = mysql_query("SELECT cd.DOCCODE,cd.DOCUMENT,cds.DOCNO,cds.DATEISSUED,cds.DATEEXPIRED,r.ALIAS2
									FROM crewdocstatus cds
									LEFT JOIN crewdocuments cd ON cd.DOCCODE=cds.DOCCODE
									LEFT JOIN rank r ON r.RANKCODE=cds.RANKCODE
									WHERE cd.TYPE='D' and cds.APPLICANTNO=$embapplicantnohidden
									ORDER BY cds.DATEISSUED DESC
									") or die(mysql_error());
		
		$qryliclist = mysql_query("SELECT cd.DOCCODE,cd.DOCUMENT,cds.DOCNO,cds.DATEISSUED,cds.DATEEXPIRED,r.ALIAS2
									FROM crewdocstatus cds
									LEFT JOIN crewdocuments cd ON cd.DOCCODE=cds.DOCCODE
									LEFT JOIN rank r ON r.RANKCODE=cds.RANKCODE
									WHERE cd.TYPE='L'and cds.APPLICANTNO=$embapplicantnohidden
									ORDER BY cds.DATEISSUED DESC
									") or die(mysql_error());	
		
		$qryexperiencelist = mysql_query("SELECT ce.VESSEL,r.ALIAS1 AS RANKALIAS,ce.VESSELTYPECODE,ce.TRADEROUTECODE,
									IF (ce.MANNINGCODE = '',ce.MANNINGOTHERS,m.MANNING) AS MANNING,
									IF((ce.DISEMBREASONCODE <> 'OTH'),dr.REASON,ce.REASONOTHERS) AS REASON,
	                				ce.DISEMBREASONCODE,DATEEMB,DATEDISEMB
									FROM crewexperience ce
									LEFT JOIN rank r ON r.RANKCODE=ce.RANKCODE
									LEFT JOIN traderoute t ON t.TRADEROUTECODE=ce.TRADEROUTECODE
									LEFT JOIN manning m ON m.MANNINGCODE=ce.MANNINGCODE
									LEFT JOIN disembarkreason dr ON dr.DISEMBREASONCODE=ce.DISEMBREASONCODE
									WHERE ce.APPLICANTNO=$embapplicantnohidden
									ORDER BY DATEEMB DESC") or die(mysql_error());
		
		$qryexperiencevmclist = mysql_query("SELECT v.VESSEL,v.VESSELTYPECODE,v.TRADEROUTECODE,r.ALIAS2,
									cc.DATEEMB,cc.DATEDISEMB,cc.DATECHANGEDISEMB,dr.REASON,
									rp.ALIAS2 AS ALIAS2PROMOTE,ccp.DATEEMB AS DATEEMBPROMOTE,ccp.DATEDISEMB AS DATEDISEMBPROMOTE,
									cp1.CCIDPROMOTE AS CHKPROMOTE
									FROM crewchange cc
	                				LEFT JOIN rank r ON r.RANKCODE=cc.RANKCODE
									LEFT JOIN vessel v ON v.VESSELCODE=cc.VESSELCODE
									LEFT JOIN disembarkreason dr ON cc.DISEMBREASONCODE=dr.DISEMBREASONCODE
									LEFT JOIN crewpromotionrelation cp ON cc.CCID=cp.CCID
									LEFT JOIN crewchange ccp ON cp.CCIDPROMOTE=ccp.CCID
	                				LEFT JOIN rank rp ON rp.RANKCODE=ccp.RANKCODE
	                				LEFT JOIN crewpromotionrelation cp1 ON cc.CCID=cp1.CCIDPROMOTE
									WHERE cc.APPLICANTNO=$embapplicantnohidden
									ORDER BY cc.DATEEMB DESC
									") or die(mysql_error());
		
		$qrycertlist = mysql_query("SELECT cd.DOCCODE,cd.DOCUMENT,ccs.DOCNO,ccs.DATEISSUED,ccs.GRADE,r.ALIAS2
									FROM crewcertstatus ccs
									LEFT JOIN crewdocuments cd ON cd.DOCCODE=ccs.DOCCODE
									LEFT JOIN rank r ON r.RANKCODE=ccs.RANKCODE
									WHERE cd.TYPE='C'and ccs.APPLICANTNO=$embapplicantnohidden
									ORDER BY ccs.DATEISSUED DESC
									") or die(mysql_error());
		
		$qrymedicallist = mysql_query("SELECT cl.CLINIC,cm.DATECHECKUP,cm.DIAGNOSIS,cr.RECOMMENDATION,cm.REMARKS,cm.DOCCODE
									FROM crewmedical cm
									LEFT JOIN clinic cl ON cl.CLINICID=cm.CLINICID
									LEFT JOIN clinicrecommend cr ON cr.RECOMMENDCODE=cm.RECOMMENDCODE
									WHERE cm.APPLICANTNO=$embapplicantnohidden
									ORDER BY cm.DATECHECKUP DESC
									") or die(mysql_error());
		
		$qryperformancelist = mysql_query("SELECT v.VESSEL,cc.CCID,cc.DATEEMB,cc.DATEDISEMB
									FROM crewchange cc
									LEFT JOIN vessel v ON v.VESSELCODE=cc.VESSELCODE
									WHERE cc.APPLICANTNO=$embapplicantnohidden
									ORDER BY cc.DATEEMB DESC,cc.CCID
									") or die(mysql_error());
		
		
		function checkpath($path)
		{
			if (is_file($path))
				return true;
			else 
				return false;
		}	
		
$view201 = "
		<div id=\"personal201list\" style=\"width:100%;display:block;\">

			<div style=\"width:100%;height:150px;\">		
				<div style=\"float:left;width:332px;height:150px;padding-top:3px;padding-left:3px;\">
					<span class=\"sectiontitle\">PERSONAL INFORMATION</span>
					<table class=\"listrow\">
						<tr>
							<th>CREW CODE</th>
							<th>:</th>
							<td><span class=\"important\">$personalcrewcode ($embapplicantnohidden)</span>&nbsp;<span class=\"important\" style=\"color:green;\">$scftshow</span></td>
						</tr>
						<tr>
							<th>NAME</th>
							<th>:</th>
							<td>$personalname</td>
						</tr>
						<tr>
							<th>ADDRESS</th>
							<th>:</th>
							<td>$personaladdress</td>
						</tr>
						<tr>
							<th>TEL. NO.</th>
							<th>:</th>
							<td>$personaltelno</td>
						</tr>
						<tr>
							<th>EMAIL</th>
							<th>:</th>
							<td>$personalemail</td>
						</tr>
						<tr>
							<th>RECOMMENDED</th>
							<th>:</th>
							<td>$personalrecommendedby</td>
						</tr>
					</table>
				</div>
				
				<div style=\"float:right;width:155px;height:150px;\">
					<img src=\"idpics/$embapplicantnohidden.JPG\" width=\"150px\" height=\"150px\">
				</div>
			</div>
			
			<div style=\"width:100%;height:140px;\">		
			
				<div style=\"float:left;width:50%;padding-top:3px;padding-left:3px;\">
					<table class=\"listrow\">
						<tr>
							<th width=\"35%\">BIRTHDATE</th>
							<th width=\"5%\">:</th>
							<td width=\"60%\">$personalbdate</td>
						</tr>
						<tr>
							<th>AGE</th>
							<th>:</th>
							<td>$personalage</td>
						</tr>						
						<tr>
							<th>BIRTHPLACE</th>
							<th>:</th>
							<td>$personalbplace</td>
						</tr>
						<tr>
							<th>GENDER</th>
							<th>:</th>
							<td>$personalgender</td>
						</tr>
						<tr>
							<th>STATUS</th>
							<th>:</th>
							<td>$personalcivilstatus</td>
						</tr>
						<tr>
							<th>RELIGION</th>
							<th>:</th>
							<td>$personalreligion</td>
						</tr>
					</table>
				</div>
				<div style=\"float:right;width:49%;\">
					<table class=\"listrow\">
						<tr>
							<th>WEIGHT</th>
							<th>:</th>
							<td>$personalweight kls.</td>
						</tr>
						<tr>
							<th>HEIGHT</th>
							<th>:</th>
							<td>$personalheight cm.</td>
						</tr>
						
						<tr>
							<th>SSS NO.</th>
							<th>:</th>
							<td>$personalsss</td>
						</tr>
						<tr>
							<th>TAX ID NO.</th>
							<th>:</th>
							<td>$personaltin</td>
						</tr>
						<tr>
							<th>OFW NO.</th>
							<th>:</th>
							<td>$personalofwno</td>
						</tr>
						<tr>
							<th>PREF. ROUTE</th>
							<th>:</th>
							<td>$personalprefroute</td>
						</tr>
					</table>
				</div>
				
			</div>	
			
			
			<div style=\"width:100%;height:80px;overflow:auto;\">
			
				<span class=\"sectiontitle\">REMARKS</span>
				
				<table class=\"listrow\">
					<tr>
						<td>$personalremarks</td>
					</tr>
				</table>
			</div>
			
			
			<div style=\"width:100%;\">
			
				<span class=\"sectiontitle\">FAMILY BACKGROUND</span>
				
				<div style=\"width:100%;overflow:auto;height:130px;\">
					<table class=\"listcol\" cellspacing=\"1\" cellpadding=\"0\">
						<tr>
							<th width=\"30%\">NAME</th>
							<th width=\"30%\">ADDRESS</th>
							<th width=\"20%\">RELATION</th>
							<th width=\"20%\">TELNO</th>
						</tr>
	";
					$ctr = 0;
					$classtype = "odd";
					while ($rowfamilylist = mysql_fetch_array($qryfamilylist))
					{
	
										
						$family1 = $rowfamilylist["NAME"];
						$family2 = $rowfamilylist["ADDRESS"];
						$family3 = $rowfamilylist["RELATION"];
						$family4 = $rowfamilylist["TELNO"];
						
	$view201 .= "
						<tr class=\"$classtype\">
							<td>$family1</td>
							<td>$family2</td>
							<td align=\"center\">$family3</td>
							<td align=\"center\">$family4</td>
						</tr>
	";					
						$ctr++;
						if($classtype=="even") { $classtype="odd";} else { $classtype="even"; }
					}
	
	$view201 .= "				
					</table>
				</div>
			</div>	
	
		</div>
		<div id=\"documents201list\" style=\"width:100%;display:none;\">
			<br />
			<span class=\"sectiontitle\">LIST OF DOCUMENTS</span>
			
			<div style=\"width:100%; overflow:auto;height:220px;\">
				<table class=\"listcol\" cellspacing=\"1\" cellpadding=\"0\">
					<tr>
						<th width=\"30%\">DOCUMENT</th>
						<th width=\"20%\">NO.</th>
						<th width=\"5%\">RANK</th>
						<th width=\"20%\">DATE ISSUED</th>
						<th width=\"20%\">DATE EXPIRED</th>
						<th width=\"5%\">&nbsp;</th>
					</tr>
";
				$ctr = 0;
				$classtype = "odd";
				while ($rowdoclist = mysql_fetch_array($qrydoclist))
				{

						
					$doc1 = $rowdoclist["DOCUMENT"];
					$doc2 = $rowdoclist["DOCNO"];
					$doc3 = $rowdoclist["ALIAS2"];
					if ($rowdoclist["DATEISSUED"] != "")
						$doc4 = date('m/d/Y',strtotime($rowdoclist["DATEISSUED"]));
					else 
						$doc4 = "";
					
					if ($rowdoclist["DATEEXPIRED"] != "")
						$doc5 = date('m/d/Y',strtotime($rowdoclist["DATEEXPIRED"]));
					else 
						$doc5 = "";
						
					$doccode = $rowdoclist["DOCCODE"];
					
					if (checkpath("$basedir/$embapplicantnohidden/D/$doccode.pdf"))
						$viewdisabled = 0;
					else 
						$viewdisabled = 1;
						
						
					$target = "doc$personalcrewcode" . "_$ctr";
					
$view201 .= "
					<tr class=\"$classtype\">
						<td>$doc1</td>
						<td align=\"center\">$doc2</td>
						<td align=\"center\">$doc3</td>
						<td align=\"center\">$doc4</td>
						<td align=\"center\">$doc5</td>
						<td align=\"center\">";
						if($viewdisabled==0)
						{	
$view201 .= "
							<img src=\"images/buttons/btn_v_def.gif\" 
										onmousedown=\"this.src='images/buttons/btn_v_click.gif';\"
										onmouseup=\"this.src='images/buttons/btn_v_def.gif';\"
										onmouseout=\"this.src='images/buttons/btn_v_def.gif';\"
										onclick=\"javascript:openWindow('$basedir/$embapplicantnohidden/D/$doccode.pdf#toolbar=0&scrollbar=0&navpanes=0', '$target', 900, 600);\"
										width=\"20px\">";
						}
$view201 .= "
						</td>
					</tr>
";					
					$ctr++;
					if($classtype=="even") { $classtype="odd";} else { $classtype="even"; }
				}

$view201 .= "				
				</table>
			</div>

			<br />
			<span class=\"sectiontitle\">LIST OF LICENSES</span>
			
			<div style=\"width:100%; overflow:auto;height:220px;\">
				<table class=\"listcol\" cellspacing=\"1\" cellpadding=\"0\">
					<tr>
						<th width=\"30%\">LICENSE</th>
						<th width=\"20%\">NO.</th>
						<th width=\"5%\">RANK</th>
						<th width=\"20%\">DATE ISSUED</th>
						<th width=\"20%\">DATE EXPIRED</th>
						<th width=\"5%\">&nbsp;</th>
					</tr>
";
				$ctr = 0;
				$classtype = "odd";
				while ($rowliclist = mysql_fetch_array($qryliclist))
				{

						
					$lic1 = $rowliclist["DOCUMENT"];
					$lic2 = $rowliclist["DOCNO"];
					$lic3 = $rowliclist["ALIAS2"];
					if ($rowliclist["DATEISSUED"] != "")
						$lic4 = date('m/d/Y',strtotime($rowliclist["DATEISSUED"]));
					else 
						$lic4 = "";
					
					if ($rowliclist["DATEEXPIRED"] != "")
						$lic5 = date('m/d/Y',strtotime($rowliclist["DATEEXPIRED"]));
					else 
						$lic5 = "";
					
					$liccode = $rowliclist["DOCCODE"];
					
					if (checkpath("$basedir/$embapplicantnohidden/L/$liccode.pdf"))
						$viewdisabled = 0;
					else 
						$viewdisabled = 1;
					
					$target = "lic$personalcrewcode" . "_$ctr";
						
$view201 .= "
					<tr class=\"$classtype\">
						<td>$lic1</td>
						<td align=\"center\">$lic2</td>
						<td align=\"center\">$lic3</td>
						<td align=\"center\">$lic4</td>
						<td align=\"center\">$lic5</td>
						<td align=\"center\">";
						if($viewdisabled==0)
						{	
$view201 .= "
							<img src=\"images/buttons/btn_v_def.gif\" 
										onmousedown=\"this.src='images/buttons/btn_v_click.gif';\"
										onmouseup=\"this.src='images/buttons/btn_v_def.gif';\"
										onmouseout=\"this.src='images/buttons/btn_v_def.gif';\"
										onclick=\"javascript:openWindow('$basedir/$embapplicantnohidden/L/$liccode.pdf', '$target', 900, 600);\"
										width=\"20px\">";
						}
$view201 .= "
						</td>
					</tr>
";					
					$ctr++;
					if($classtype=="even") { $classtype="odd";} else { $classtype="even"; }
				}

$view201 .= "				
				</table>
			</div>
			
		</div>
		
		<div id=\"experience201list\" style=\"width:100%;display:none;\">
		
			<br />
			<span class=\"sectiontitle\">EXPERIENCES - OUTSIDE</span>
			
			<div style=\"width:100%; overflow:auto;height:220px;\">
				<table class=\"listcol\" cellspacing=\"1\" cellpadding=\"0\">
					<tr>
						<th width=\"20%\">VESSEL</th>
						<th width=\"5%\">RANK</th>
						<th width=\"5%\">TYPE</th>
						<th width=\"10%\">EMBARK</th>
						<th width=\"10%\">DISEMBARK</th>
						<th width=\"5%\">ROUTE</th>
						<th width=\"25%\">MANNING</th>
						<th width=\"20%\">REASON</th>
					</tr>
";
				$ctr = 0;
				$classtype = "odd";
				while ($rowexperiencelist = mysql_fetch_array($qryexperiencelist))
				{

						
					$experience1 = $rowexperiencelist["VESSEL"];
					$experience2 = $rowexperiencelist["RANKALIAS"];
					$experience3 = $rowexperiencelist["VESSELTYPECODE"];
					
					if ($rowexperiencelist["DATEEMB"] != "")
						$experience4 = date('m/d/Y',strtotime($rowexperiencelist["DATEEMB"]));
					else 
						$experience4 = "";
					
					if ($rowexperiencelist["DATEDISEMB"] != "")
						$experience5 = date('m/d/Y',strtotime($rowexperiencelist["DATEDISEMB"]));
					else 
						$experience5 = "";
						
					$experience6 = $rowexperiencelist["TRADEROUTECODE"];
					$experience7 = $rowexperiencelist["MANNING"];
					$experience8 = $rowexperiencelist["REASON"];
					
$view201 .= "
					<tr class=\"$classtype\">
						<td>$experience1</td>
						<td align=\"center\">$experience2</td>
						<td align=\"center\">$experience3</td>
						<td align=\"center\">$experience4</td>
						<td align=\"center\">$experience5</td>
						<td align=\"center\">$experience6</td>
						<td>$experience7</td>
						<td>$experience8</td>
					</tr>
";					
					$ctr++;
					if($classtype=="even") { $classtype="odd";} else { $classtype="even"; }
				}

$view201 .= "				
				</table>
			</div>
			
			<br />
			<span class=\"sectiontitle\">EXPERIENCES - VERITAS</span>
			
			<div style=\"width:100%; overflow:auto;height:220px;\">
				<table class=\"listcol\" cellspacing=\"1\" cellpadding=\"0\">
					<tr>
						<th width=\"25%\">VESSEL</th>
						<th width=\"10%\">RANK</th>
						<th width=\"10%\">TYPE</th>
						<th width=\"10%\">EMBARK</th>
						<th width=\"10%\">DISEMBARK</th>
						<th width=\"10%\">ROUTE</th>
						<th width=\"25%\">REASON</th>
					</tr>
";
				$ctr = 0;
				$classtype = "odd";
				while ($rowexperiencevmclist = mysql_fetch_array($qryexperiencevmclist))
				{

						
					$experiencevmc1 = $rowexperiencevmclist["VESSEL"];
					$experiencevmc2 = $rowexperiencevmclist["ALIAS2"];
					$experiencevmc3 = $rowexperiencevmclist["VESSELTYPECODE"];
					
					if ($rowexperiencevmclist["DATEEMB"] != "")
						$experiencevmc4 = date('m/d/Y',strtotime($rowexperiencevmclist["DATEEMB"]));
					else 
						$experiencevmc4 = "";
					
					if ($rowexperiencevmclist["DATEDISEMB"] != "")
						$experiencevmc5 = date('m/d/Y',strtotime($rowexperiencevmclist["DATEDISEMB"]));
					else 
						$experiencevmc5 = "";
					if ($rowexperiencevmclist["DATECHANGEDISEMB"] != "")
						$experiencevmc5 = date('m/d/Y',strtotime($rowexperiencevmclist["DATECHANGEDISEMB"]));
						
					$experiencevmc6 = $rowexperiencevmclist["TRADEROUTECODE"];
					$experiencevmc7 = $rowexperiencevmclist["REASON"];
					//IF PROMOTED
					$alias2promote = $rowexperiencevmclist["ALIAS2PROMOTE"];
					$dateembpromote = date('m/d/Y',strtotime($rowexperiencevmclist["DATEEMBPROMOTE"]));
					$datedisembpromote = date('m/d/Y',strtotime($rowexperiencevmclist["DATEDISEMBPROMOTE"]));
					//rp.ALIAS2 AS ALIAS2PROMOTE,ccp.DATEEMB AS DATEEMBPROMOTE,ccp.DATEDISEMB AS DATEDISEMBPROMOTE
					if(!empty($alias2promote))
					{
						$titlerem="($experiencevmc2 until $experiencevmc5 only)";
						$experiencevmc7="Promoted ".$titlerem;
						$experiencevmc2=$alias2promote;
						$experiencevmc5=$datedisembpromote;
					}
					else 
						$titlerem="";
					//CHECK IF CCID IS PROMOTED
					if(empty($rowexperiencevmclist["CHKPROMOTE"]))
					{
$view201 .= "
					<tr class=\"$classtype\">
						<td>$experiencevmc1</td>
						<td align=\"center\">$experiencevmc2</td>
						<td align=\"center\">$experiencevmc3</td>
						<td align=\"center\">$experiencevmc4</td>
						<td align=\"center\">$experiencevmc5</td>
						<td align=\"center\">$experiencevmc6</td>
						<td title=\"$titlerem\">$experiencevmc7</td>
					</tr>";			
					$ctr++;
					if($classtype=="even") { $classtype="odd";} else { $classtype="even"; }		
					}
				}

$view201 .= "				
				</table>
			</div>			
		</div>
		
		<div id=\"performance201list\" style=\"width:100%;display:none;\">
			<br />
			<span class=\"sectiontitle\">PERFORMANCE</span>
			
			<div style=\"width:100%; overflow:auto;height:440px;\">
";
			$ccidtemp = "";
$view201 .= "				
			<table class=\"listcol\" cellspacing=\"1\" cellpadding=\"0\">
				<tr>
					<th style=\"font-weight:normal;\"><b>EVALNO</b></th>
					<th style=\"font-weight:normal;\"><b>EVALDATE</b></th>
					<th style=\"font-weight:normal;\"><b>GRADE</b></th>
					<th style=\"font-weight:normal;\"><b>VIEW</b></th>
				</tr>";
			while ($rowperformancelist=mysql_fetch_array($qryperformancelist))
			{

				$ccid = $rowperformancelist["CCID"];		
				$performance1 = $rowperformancelist["VESSEL"];
				$performance2 = date('m/d/Y',strtotime($rowperformancelist["DATEEMB"]));
				$performance3 = date('m/d/Y',strtotime($rowperformancelist["DATEDISEMB"]));
				
				//PUT IF FILE EXISTS...
				
				if ($ccid != $ccidtemp)
				{
					$view201 .= "
					<tr>
						<td colspan=\"4\" style=\"font-size:.9;background:White;color:blue;\">
							<span>
								Vessel:&nbsp;$performance1&nbsp;&nbsp;&nbsp;Embark:&nbsp;$performance2&nbsp;&nbsp;&nbsp;Disembark:&nbsp;$performance3
							</span>
							<!--
							<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
								<tr style=\"background-color:Silver;font-size:.8em;\">
									<th width=\"15%\">Vessel:</th>
									<th width=\"30%\">$performance1</th>
									<th width=\"10%\">Embark:</th>
									<th width=\"15%\">$performance2</th>
									<th width=\"15%\">Disembark:</th>
									<th width=\"15%\">$performance3</th>
								</tr>
							</table>
							-->
						</td>
					</tr>";
				}	
				
					$qryperformancelist2 = mysql_query("SELECT ceh.EVALNO,ceh.EVALDATE,ceh.VMCCOMMENT,ceh.PRINCIPALCOMMENT,ceh.REMARKS
												FROM crewevalhdr ceh
												WHERE ceh.CCID=$ccid
												ORDER BY ceh.EVALNO
												") or die(mysql_error());
					
					if (mysql_num_rows($qryperformancelist2) > 0)
					{
						$ctr = 1;
						$classtype = "even";
						while($rowperformancelist2 = mysql_fetch_array($qryperformancelist2))
						{
					
							$evalno = $rowperformancelist2["EVALNO"];
							$performance5 = $rowperformancelist2["EVALDATE"];
							$performance6 = $rowperformancelist2["GRADE"];
							
							
							if ($evalno < 4)
								switch ($evalno)					
								{
									case "1"	:	$evalno1 = "1st"; break;
									case "2"	:	$evalno1 = "2nd"; break;
									case "3"	:	$evalno1 = "3rd"; break;
								}
							else 
								$evalno = $evalno . "th";
	
							//PUT IF FILE EXISTS...
							$cerfile = "cer_$ccid" . "_$evalno";
							if (checkpath("$basedir/$embapplicantnohidden/C/$cerfile.pdf"))
								$viewdisabled = 0;
							else 
								$viewdisabled = 1;
							
								
							$target = "cer$personalcrewcode" . "_$ccid" . "_$evalno";
					
							
							
							$view201 .= "
								<tr class=\"$classtype\">
									<td align=\"center\">$evalno1</td>
									<td align=\"center\">$performance5</td>
									<td align=\"center\">$performance6</td>
									<td align=\"center\">";
									if($viewdisabled==0)
									{	
				$view201 .= "
										<img src=\"images/buttons/btn_v_def.gif\" 
													onmousedown=\"this.src='images/buttons/btn_v_click.gif';\"
													onmouseup=\"this.src='images/buttons/btn_v_def.gif';\"
													onmouseout=\"this.src='images/buttons/btn_v_def.gif';\"
													onclick=\"javascript:openWindow('$basedir/$embapplicantnohidden/C/$cerfile.pdf', '$target', 900, 600);\"
													width=\"20px\">";
									}
				$view201 .= "
									</td>
								</tr>
							";
							
							$ctr++;
//							if($ctr!=1)
//							{
//								if($classtype=="even") { $classtype="odd";} else { $classtype="even"; }
//							}
							
						}
					}
					$ccidtemp = $ccid;
				}
$view201 .= "		</table>";
$view201 .= "
			</div>
		</div>
		
		<div id=\"training201list\" style=\"width:100%;display:none;\">
			<br />
			<span class=\"sectiontitle\">TRAINING CERTIFICATES</span>
			
			<div style=\"width:100%; overflow:auto;height:440px;\">
				<table class=\"listcol\" cellspacing=\"1\" cellpadding=\"0\">
					<tr>
						<th width=\"30%\">CERTIFICATE</th>
						<th width=\"20%\">NO.</th>
						<th width=\"5%\">RANK</th>
						<th width=\"20%\">GRADE</th>
						<th width=\"20%\">DATE ISSUED</th>
						<th width=\"5%\">&nbsp;</th>
					</tr>
";
				$ctr = 0;
				$classtype = "odd";
				while ($rowcertlist = mysql_fetch_array($qrycertlist))
				{

						
					$cert1 = $rowcertlist["DOCUMENT"];
					$cert2 = $rowcertlist["DOCNO"];
					$cert3 = $rowcertlist["ALIAS2"];
					if ($rowcertlist["DATEISSUED"] != "")
						$cert4 = date('m/d/Y',strtotime($rowcertlist["DATEISSUED"]));
					else 
						$cert4 = "";
					
					$cert5 = $rowcertlist["GRADE"];
					$certcode = $rowcertlist["DOCCODE"];
					
					if (checkpath("$basedir/$embapplicantnohidden/C/$certcode.pdf"))
						$viewdisabled = 0;
					else 
						$viewdisabled = 1;
						
					$target = "cert$personalcrewcode" . "_$certcode" . "_$ctr";
					
$view201 .= "
					<tr class=\"$classtype\">
						<td>$cert1</td>
						<td align=\"center\">$cert2</td>
						<td align=\"center\">$cert3</td>
						<td align=\"center\">$cert5</td>
						<td align=\"center\">$cert4</td>
						<td align=\"center\">";
						if($viewdisabled==0)
						{	
	$view201 .= "
							<img src=\"images/buttons/btn_v_def.gif\" 
										onmousedown=\"this.src='images/buttons/btn_v_click.gif';\"
										onmouseup=\"this.src='images/buttons/btn_v_def.gif';\"
										onmouseout=\"this.src='images/buttons/btn_v_def.gif';\"
										onclick=\"javascript:openWindow('$basedir/$embapplicantnohidden/C/$certcode.pdf', '$target', 900, 600);\"
										width=\"20px\">";
						}
	$view201 .= "
						</td>
					</tr>
";					
					$ctr++;
					if($classtype=="even") { $classtype="odd";} else { $classtype="even"; }
				}

$view201 .= "
					</tr>
				</table>
			</div>
		</div>
		
		<div id=\"medical201list\" style=\"width:100%;display:none;\">
			<br />
			<span class=\"sectiontitle\">MEDICAL BACKGROUND</span>
			
			<div style=\"width:100%; overflow:auto;height:220px;\">
				<table class=\"listcol\" cellspacing=\"1\" cellpadding=\"0\">
					<tr>
						<th width=\"25%\">CLINIC</th>
						<th width=\"10%\">DATE</th>
						<th width=\"20%\">DIAGNOSIS</th>
						<th width=\"20%\">RECOMMENDATION</th>
						<th width=\"20%\">REMARKS</th>
						<th width=\"5%\">&nbsp;</th>
					</tr>
";
				$ctr = 0;
				$classtype = "odd";
				while ($rowmedicallist = mysql_fetch_array($qrymedicallist))
				{

						
					$medical1 = $rowmedicallist["CLINIC"];
					if ($rowmedicallist["DATECHECKUP"] != "")
						$medical2 = date('m/d/Y',strtotime($rowmedicallist["DATECHECKUP"]));
					else 
						$medical2 = "";
					
					$medical3 = $rowmedicallist["DIAGNOSIS"];
					$medical4 = $rowmedicallist["RECOMMENDATION"];
					$medical5 = $rowmedicallist["REMARKS"];
					$medcode = $rowmedicallist["DOCCODE"];
					
					if (checkpath("$basedir/$embapplicantnohidden/D/$medcode.pdf"))
						$viewdisabled = 0;
					else 
						$viewdisabled = 1;					
					
$view201 .= "
					<tr class=\"$classtype\">
						<td>$medical1</td>
						<td align=\"center\">$medical2</td>
						<td align=\"center\">$medical3</td>
						<td align=\"center\">$medical4</td>
						<td align=\"center\">$medical5</td>
						<td align=\"center\">";
						if($viewdisabled==0)
						{	
	$view201 .= "
							<img src=\"images/buttons/btn_v_def.gif\" 
										onmousedown=\"this.src='images/buttons/btn_v_click.gif';\"
										onmouseup=\"this.src='images/buttons/btn_v_def.gif';\"
										onmouseout=\"this.src='images/buttons/btn_v_def.gif';\"
										onclick=\"javascript:openWindow('$basedir/$embapplicantnohidden/D/$medcode.pdf', 'med$personalcrewcode', 900, 600);\"
										width=\"20px\">";
						}
	$view201 .= "
						</td>
					</tr>
";					
					$ctr++;
					if($classtype=="even") { $classtype="odd";} else { $classtype="even"; }
				}	

$view201 .= "
				</table>
			</div>
		</div>
";

	$resulttemp = $view201;

	break;
	
	
	case "embcountry":
		if(empty($embcountry))
		{
			$getembport="<select name=\"embport\" style=\"width:100px;\" disabled=\"disabled\">
					<option value=\"\">-Port-</option>
				</select>";
		}
		else 
		{
			$qryembport=mysql_query("SELECT PORT FROM port WHERE PORTCOUNTRY='$embcountry' ORDER BY PORT") or die(mysql_error());
			$getembport="<select name=\"embport\" style=\"width:100px;\"
							onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\">\n";
							if(mysql_num_rows($qryembport)==1)
								$selected="selected";
							else 
								$getembport .= "<option value=\"\">-Port-</option>";
							while($rowembport=mysql_fetch_array($qryembport))
							{
								$embport1=$rowembport['PORT'];
//								if($embport1==$embport)
//									$selected="selected";
//								else 
//									$selected="";
								$getembport .= "<option $selected value=\"$embport1\">$embport1</option>\n";
							}
					$getembport .= "
						</select>";
		}
		$resulttemp=$getembport;
	break;
	
	case "vesselselect":
		if($ccpno!=0)
		{
			$leftjoinvessel="LEFT JOIN crewchangeplandtl cd ON c.CCID=cd.CCID";
			$wherevessel="AND cd.CCPNO=$ccpno";
		}
		include("veritas/include/qrylistplan.inc");
		$vesselselect = "
		<table style=\"border-top:1px solid black;border-right:1px solid black;table-layout:fixed;\" cellspacing=\"0\" cellpadding=\"0\">
			<tr height=\"30px\" style=\"display:none;\">\n
				<td style=\"width:25px;\">&nbsp;</td>\n
				<td style=\"width:39px;\">&nbsp;</td>\n
				<td style=\"width:165px;\">&nbsp;</td>\n
				<td style=\"width:70px;\">&nbsp;</td>\n
				<td style=\"width:70px;\">&nbsp;</td>\n
				<td style=\"width:125px;\">&nbsp;</td>\n
				<td style=\"width:39px;\">&nbsp;</td>\n
				<td style=\"width:165px;\">&nbsp;</td>\n
				<td style=\"width:47px;\">&nbsp;</td>\n
				<td style=\"width:55px;\">&nbsp;</td>\n
				<td style=\"width:175px;\">&nbsp;</td>\n
				<td style=\"width:25px;\">&nbsp;</td>\n
			</tr>";
		$getdiscrepancy="
		<div style=\"width:100%;height:37px;overflow:hidden;\">
			<span style=\"background:White;border-left:1px solid black;border-right:1px solid black;font-weight:bold;font-size:12pt;width:100%;height:20px;text-align:center;\">Discrepancy</span>\n
			<table id=\"discrepancyheader\" style=\"border-top:1px solid black;border-right:1px solid black;table-layout:fixed;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
				<tr height=\"17px\">\n
					<td style=\"width:165px;$styleheader font-size:10pt;background:White;\">Crew</td>\n
					<td style=\"width:39px;$styleheader font-size:10pt;background:White;\">Rank</td>\n
					<td style=\"width:45px;$styleheader font-size:10pt;background:White;\">Batch</td>\n
					<td style=\"$styleheader font-size:10pt;background:White;\">Discrepancy</td>\n
				</tr>
			</table>
		</div>
		<div style=\"width:100%;height:118px;overflow:auto;\">
			<table id=\"discrepancydetails\" style=\"border-top:1px solid black;border-right:1px solid black;table-layout:fixed;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
				<tr style=\"display:none;\">\n
					<td style=\"width:165px;\">&nbsp;</td>\n
					<td style=\"width:39px;\">&nbsp;</td>\n
					<td style=\"width:45px;\">&nbsp;</td>\n
					<td style=\"\">&nbsp;</td>\n
				</tr>";
		$datebatchtemp="";
		$batchnotemp="";
		$batchno=0;
		$position=0;
		$classtype="even";
		$overduetemp=0;
		while($rowlistplan=mysql_fetch_array($qrylistplan))
		{
			$datedisemb=$rowlistplan["DATEDISEMB"];
			$getbatchno=$rowlistplan["BATCHNO"];
			$datedisembchanged=$rowlistplan["DATEDISEMBCHANGED"];
			$reason=$rowlistplan["REASON"];
			if($datedisembchanged!="0")
			{
				$datechangeby=$rowlistplan["DATECHANGEBY"];
				$datechangedate=date("d M Y",strtotime($rowlistplan["DATECHANGEDATE"]));
				$datedisembtag="*";
				$datedisembtitle1="Date ".$datedisembchanged."\nReason: ".$reason."\nBy: ".$datechangeby."\nOn: ".$datechangedate;
			}
			else 
			{
				$datedisembtag="";
				$datedisembtitle1="Click to change disembark date...";
			}
			
			$datebatch=date("Ym",strtotime($datedisemb)); //FOR BATCHING
			if($datedisemb<$datenow)
			{
				$batchno=0;
				$yesterday=1;
			}
			else 
			{
				$yesterday=0;
				$dateembraw1=date("Y-m-d",strtotime($rowlistplan["DATEEMB"]));
//				if($dateembraw1>$datenow)
//					$getbatchno =999;
//				if($getbatchno == 999)
//				{
//					if($datebatchtemp!=$datebatch || $getbatchno!=$getbatchnotemp || $yesterday!=$yesterdaytemp)
//						$batchno++;
//				}
//				else 
				$batchno = $getbatchno;
				$overduetemp=1;
			}	
			
			if($datebatchtemp!=$datebatch && $getbatchno != $batchno || $batchno!=$batchnotemp)
			{
				if(($overduetemp==0 && $batchno==0)/*first loop*/ || ($overduetemp==1 && $batchno>0))
				{
					if($batchno<=$batchlimit || empty($batchlimit))
					{
						if($batchno==0)
							$batchnoshow="Overdue";
						elseif($batchno==1)
							$batchnoshow=$batchno."st";
						elseif ($batchno==2)
							$batchnoshow=$batchno."nd";
						elseif ($batchno==3)
							$batchnoshow=$batchno."rd";
						elseif ($batchno==999)
							$batchnoshow="NO";
						else
							$batchnoshow=$batchno."th";
						$vesselselect .= "
						<tr height=\"15px\">\n
							<td colspan=\"12\" style=\"$styleheader;background:black;color:yellow;font-size:12px;\">$batchnoshow BATCH</td>\n
						</tr>\n
						<tr height=\"15px\">\n
							<td colspan=\"6\" style=\"$styleheader;font-weight:bold;\">DISEMBARKING CREW</td>\n
							<td colspan=\"6\" style=\"$styleheader;font-weight:bold;\">EMBARKING CREW</td>\n
						</tr>\n
						<tr height=\"30px\">\n
							<td style=\"$styleheader;font-weight:bold;\">NO</td>\n
							<td style=\"$styleheader;font-weight:bold;\">RANK</td>\n
							<td style=\"$styleheader;font-weight:bold;\">NAME</td>\n
							<td style=\"$styleheader;font-weight:bold;\">EMBARKED DATE</td>\n
							<td style=\"$styleheader;font-weight:bold;\">E.O.C.</td>\n
							<td style=\"$styleheader;font-weight:bold;\">EMBARKED/PORT</td>\n
							<td style=\"$styleheader;font-weight:bold;\">RANK</td>\n
							<td style=\"$styleheader;font-weight:bold;\">NAME</td>\n
							<td style=\"$styleheader;font-weight:bold;\">EX-VSL</td>\n
							<td style=\"$styleheader;font-weight:bold;\">DATE</td>\n
							<td style=\"$styleheader;font-weight:bold;\" colspan=\"2\">PORT</td>\n
						</tr>";
					}
				}
				$overduetemp=1;
			}
			if($batchno<=$batchlimit || empty($batchlimit))
			{
				$ccid=$rowlistplan["CCID"];
				$ccidemb=$rowlistplan["CCIDEMB"];
				$applicantno=$rowlistplan["APPLICANTNO"];
				$embapplicantno=$rowlistplan["EMBAPPLICANTNO"];
				$rankcode=$rowlistplan["RANKCODE"];
				$embrankcode=$rowlistplan["EMBRANKCODE"];
				$alias2=$rowlistplan["ALIAS2"];
				$embalias2=$rowlistplan["EMBALIAS2"];
				$name=$rowlistplan["NAME"];
				$dateemb=$rowlistplan["DATEEMB"];
				$datedisemborig=$rowlistplan["DATEDISEMBORIG"];
				$dateembshow=date("d-M-y",strtotime($dateemb));
				$dateembshow1=date("m/d/Y",strtotime($dateemb));
				$datedisembshow=date("d-M-y",strtotime($datedisemb));
				$datedisemborigshow=date("m/d/Y",strtotime($datedisemborig));
				$dateembfuture=date("m/d/Y",strtotime($datedisemb));
				$datedisembfuture=date("m/d/Y", strtotime("$datedisemb + 9 month"));
				$embcountry=$rowlistplan["PORTCOUNTRY"];
				$embport=$rowlistplan["PORT"];
				$port=$embport.", ".$embcountry;
				$embname=$rowlistplan["EMBNAME"];
				$embdateemb=$rowlistplan["EMBDATEEMB"];
				$vesseltypecode=$rowlistplan["VESSELTYPECODE"];
				$vesselname=$rowlistplan["VESSEL"];
				$disembreasoncode1=$rowlistplan["DISEMBREASONCODE"];
				if(!empty($embdateemb))
						$embdateembshow=date("M 'y",strtotime($embdateemb));
				else 
					$embdateembshow="";
				$embdatedisemb=$rowlistplan["EMBDATEDISEMB"];
				$embdateembjs=date("m/d/Y",strtotime($embdateemb));
				$embdatedisembjs=date("m/d/Y",strtotime($embdatedisemb));
				$embembcountry=$rowlistplan["EMBPORTCOUNTRY"];
				$embembport=$rowlistplan["EMBPORT"];
				$embport=$embembport.", ".$embembcountry;
				
				//CHECK IF $ccidemb has relationship with replacement crew
				$displaydelete="show";
				if(!empty($ccidemb))
				{
					//CHECK IF CREW HAS REPLACEMENT ALREADY
					$qrycheckreplacement=mysql_query("SELECT CCIDEMB FROM crewchangerelation WHERE CCID=$ccidemb") or die(mysql_error());
					$cntcheckreplacement=mysql_num_rows($qrycheckreplacement);
					if($cntcheckreplacement>0)
					{
						$displaydelete=0;
						$rowcheckreplacement=mysql_fetch_array($qrycheckreplacement);
						$getccidemb=$rowcheckreplacement["CCIDEMB"];
						$qrygetembreplace=mysql_query("SELECT CONCAT(FNAME,', ',GNAME) AS NAME,DATEEMB FROM crewchange c
								LEFT JOIN crew cr ON c.APPLICANTNO=cr.APPLICANTNO
								WHERE CCID=$getccidemb") or die(mysql_error());
						$rowgetembreplace=mysql_fetch_array($qrygetembreplace);
						$getname=$rowgetembreplace["NAME"];
						$getdateemb=$rowgetembreplace["DATEEMB"];
						$displaydeletetitle="Can't edit!\nReplaced by:$getname\nOn:$getdateemb\n";
					}
					else 
					{
						$displaydelete=1;
						$displaydeletetitle="";
					}
					if($displaydelete==1)
					{
						//CHECK IF CREW HAS DEPARTED MANILA ALREADY
						$qrychkdepart=mysql_query("SELECT DEPMNLDATE FROM crewchange WHERE CCID=$ccidemb") or die(mysql_error());
						$rowchkdepart=mysql_fetch_array($qrychkdepart);
						$chkdepart=$rowchkdepart["DEPMNLDATE"];
						if(!empty($chkdepart))
						{
							$displaydelete=0;
							$getdeparteddate=date("d-M-y",strtotime($chkdepart));
							$displaydeletetitle="$embname has departed\non $getdeparteddate";
						}
						else 
						{
							$displaydelete=1;
							$displaydeletetitle="";
						}
					}
				}
				else //FOR id="chkdiscrepancy" area in crewchangplan.php
				{
					$chkdiscrepancy="";
					$strdatedisemb=strtotime($datedisemb);
					$strdatenow=strtotime($datenow);
					if($strdatenow>$strdatedisemb)
						$chkdiscrepancy="Disembark overdue";
					else
					{
						$daysremain=($strdatedisemb-$strdatenow)/86400;
						if($daysremain>1)
							$days="s";
						else 
							$days="";
						if(($daysremain<30))
							$chkdiscrepancy="Disembark in ". $daysremain . "day" . $days;
					}
					if(!empty($chkdiscrepancy))
					{
						if($classtype=="even") { $classtype="odd";} else { $classtype="even"; }
						$getdiscrepancy.="
						<tr class=\"$classtype\">
							<td style=\"$styledetails\">&nbsp;$name</td>\n
							<td style=\"$styleheader\">&nbsp;$alias2</td>\n
							<td style=\"$styleheader\">&nbsp;$batchnoshow</td>\n
							<td style=\"$styledetails\">&nbsp;$chkdiscrepancy</td>\n
						</tr>
						";
					}
				}
				if(date("Y-m-d",strtotime($dateemb))<=$datenow && date("Y-m-d",strtotime($datedisemb))>=$datenow)
				{
					$position++;
					$getposition=$position;
				}
				else 
					$getposition="";
					
				// GET LAST VESSEL & DATEDISEMB
				if(!empty($embapplicantno))
				{
					$qrylastvessel=mysql_query("
						SELECT VESSEL,DATEDISEMB,ALIAS1 FROM
						(SELECT VESSEL,c.DATEDISEMB,ALIAS1
						FROM crewchange c
						LEFT JOIN vessel v ON c.VESSELCODE=v.VESSELCODE
						WHERE ARRMNLDATE IS NOT NULL AND c.APPLICANTNO=$embapplicantno
						UNION
						SELECT VESSEL,DATEDISEMB,VESSEL AS ALIAS1
						FROM crewexperience
						WHERE APPLICANTNO=$embapplicantno) x
						ORDER BY DATEDISEMB DESC 
						LIMIT 1") or die(mysql_error());
					if(mysql_num_rows($qrylastvessel)>0)
					{
						$rowlastvessel=mysql_fetch_array($qrylastvessel);
						$lastvessel1=$rowlastvessel["VESSEL"];
						$vesselalias1=$rowlastvessel["ALIAS1"];
					}
					else 
					{
						$lastvessel1="";
						$vesselalias1="";
					}
				}
				else 
				{
					$lastvessel1="";
					$vesselalias1="";
				}
				// END OF GET LAST VESSEL & DATEDISEMB
				
				if($flagccpno==1) //INSERT CCID FOR NEW CCPNO IF FLAG IS 1
				{
					$qrysaveccid=mysql_query("INSERT INTO crewchangeplandtl (CCPNO,CCID) VALUES($getccpno,$ccid)") or die(mysql_error());
				}
				
				
				if(!empty($getposition) || $batchno==0)
				{
					$putonclickdisemb="onclick=\"if(chkbtnloading()==1){document.getElementById('crewchangedate').style.display='block';switchajax(1);document.getElementById('existdate').value='$datedisemborigshow';document.getElementById('existdateemb').value='$dateembshow1';embccidhidden.value='$ccid';if('$disembreasoncode1'!=' '){disembreasoncode.value='$disembreasoncode1';};newdate.focus();}\"";
				}
				else 
				{
					$putonclickdisemb="onclick=\"if(chkbtnloading()==1){alert('Not On-board yet!');}\"";
					
				}
				if(!empty($getposition))
				{
					$putonclickalias2="onclick=\"if(chkbtnloading()==1){document.getElementById('changebatch').style.display='block';switchajax(1);crewselect.value='$name';currentbatch.value='$batchnoshow';batchselect.value='$batchno';ccidhidden.value='$ccid';}\"";
				}
				else 
				{
					$putonclickalias2="onclick=\"alert('Crew is NOT On-board!');\"";
					
				}
				$vesselselect .= "
				<tr height=\"15px\" $mouseovereffect>\n
					<td style=\"text-align:center;$styledetails\">&nbsp;$getposition</td>\n
					<td style=\"text-align:center;$styledetails\" title=\"$rankcode\">
						<input id=\"noprint\" type=\"button\" value=\"$alias2\" title=\"Click to change batch no...\"
							$putonclickalias2
							style=\"border:0;background:inherit;cursor:pointer;font-size:10px;\">\n
					</td>\n
					<td style=\"$styledetails\">
						<span style=\"cursor:pointer;\" title=\"Applicant No: $applicantno \nClick to view 201 file...\" 
							onclick=\"crewonboard.value='$name';embapplicantnohidden.value='$applicantno';chkloading('viewonboard201',10);\">$name</span>
					</td>\n
					<td style=\"text-align:center;$styledetails\">&nbsp;$dateembshow</td>\n
					<td style=\"text-align:center;$styledetails\" title=\"$datedisembtitle1\">&nbsp;
						<span style=\"cursor:pointer;\"
							$putonclickdisemb>$datedisembshow$datedisembtag</span>
					</td>\n
					<td style=\"$styledetails\">&nbsp;$port</td>\n";
					if(empty($embrankcode))
					{
						if(empty($ccpno)) //check if approved ccpno does not exist
						{
							$vesselselect .= "
							<td colspan=\"6\" style=\"text-align:center;$styledetails\">\n
								<input id=\"noprint\" type=\"button\" value=\"-Add Crew-\" title=\"Click to add crew...\"
									onclick=\"if(chkbtnloading()==1){addeditcrew('$ccid','$rankcode','$name','$datedisemb','','','','$dateembfuture','$datedisembfuture','$alias2','','','')}\"
									style=\"border:0;background:inherit;cursor:pointer;color:gray;font-size:10px;\">\n
							</td>\n";
						}
						else 
						{
							$vesselselect .= "
							<td colspan=\"6\" style=\"text-align:center;$styledetails\">\n
								&nbsp;
							</td>\n";
						}
					}
					else 
					{
						//CHECK IF CREW PROMOTION RELATION EXISTS
						$qrypromotion=mysql_query("SELECT cp.CCID,cp.MADEBY,cp.MADEDATE
							FROM crewchange c
							LEFT JOIN crewpromotionrelation cp ON c.CCID=cp.CCID AND cp.CCIDPROMOTE=$ccidemb
							WHERE (ABS(DATEDIFF(DATEDISEMB,'$embdateemb'))<=5 OR ABS(DATEDIFF(DATECHANGEDISEMB,'$embdateemb'))<=5) 
							AND VESSELCODE='$vesselcode' 
							AND APPLICANTNO=$embapplicantno AND c.CCID<>$ccidemb") or die(mysql_error());
						$cntpromotion=mysql_num_rows($qrypromotion);
						$disembcolor="";
						$promotion="";
						$promotion1="";
						$onclickpromote="";
						$onclickpromotedel="Are you sure you want to delete $embname?";
						if($cntpromotion!=0)
						{
							$rowpromotion=mysql_fetch_array($qrypromotion);
							$getoldccid=$rowpromotion["CCID"];
							$getmadeby=$rowpromotion["MADEBY"];
							$getmadedate=$rowpromotion["MADEDATE"];
							if(empty($getoldccid))
							{
								$disembcolor="color:red";
								$promotion="(click to create Promotion Relation...)";
								$onclickpromote="onclick=\"if(confirm('Are you sure you want to relate this?')){promoteembdate.value='$embdateemb';promoteccid.value='$ccidemb';promoteappno.value='$embapplicantno';chkloading('getpromotionrelation',0);}\"";
							}
							else 
							{
								$promotion="\nPromoted (ccid:$getoldccid)\nBy: $getmadeby\nDate: $getmadedate";
								$promotion1="*";
							}
							$onclickpromotedel="$embname is promoted. Are you sure you want to delete him?";
						}
						$vesselselect .= "
						<td style=\"text-align:center;$styledetails\" title=\"$embrankcode\">&nbsp;$embalias2</td>\n
						<td style=\"$styledetails\" title=\"Applicant No: $embapplicantno $promotion\">
							<span style=\"cursor:pointer;$disembcolor;\" $onclickpromote>$embname$promotion1</span>
						</td>\n
						<td style=\"text-align:center;$styledetails\" title=\"$lastvessel1\">&nbsp;$vesselalias1</td>\n
						<td style=\"text-align:center;$styledetails\" title=\"$embdateemb\">&nbsp;$embdateembshow</td>\n
						<td style=\"$styledetails\">&nbsp;$embport</td>\n";
						if($displaydelete==1)
						{
							if(empty($ccpno)) //check if approved ccpno does not exist
							{
								$vesselselect .= "
								<td style=\"text-align:center;$styledetails\">\n
									<input id=\"noprint\" type=\"button\" value=\"E\" title=\"Edit crew...\"
										onclick=\"if(chkbtnloading()==1){addeditcrew('$ccid','$rankcode','$name','$datedisemb','$ccidemb','$embembport','$embembcountry','$embdateembjs','$embdatedisembjs','$embalias2','$embapplicantno','$applicantno','$embname');}\"
										style=\"border:0;background:inherit;cursor:pointer;color:red;font-weight:bold;font-size:10px;\">\n
									<input id=\"noprint\" type=\"button\" value=\"X\" title=\"Delete crew...\"
										onclick=\"if(chkbtnloading()==1){if(confirm('$onclickpromotedel')){
											ccidhidden.value='$ccid';embccidhidden.value='$ccidemb';chkloading('deleteembarkcrew',0)}}\"\"
										style=\"border:0;background:inherit;cursor:pointer;color:red;font-weight:bold;font-size:10px;\">\n
								</td>\n";
							}
							else 
							{
								$vesselselect .= "
								<td style=\"text-align:center;$styledetails\">\n
									&nbsp;
								</td>\n";
							}
						}
						else 
						{
							$vesselselect .= "
							<td style=\"text-align:center;$styledetails\">\n
								<input id=\"noprint\" type=\"button\" value=\"-\" title=\"$displaydeletetitle\"
									style=\"border:0;background:inherit;cursor:pointer;color:red;font-weight:bold;\">\n
							</td>\n";
						}
					}
					$vesselselect .= "
				</tr>\n";
			} //end of checking for batch limit
			$datebatchtemp=$datebatch;
			$batchnotemp=$batchno;
			$getbatchnotemp=$getbatchno;
			$yesterdaytemp=$yesterday;
		}
	
	$vesselselect .= "
	</table>";
	$getdiscrepancy.="</table></div>";
		$resulttemp=$vesselselect;
	break;
	case "selectcrew":
		switch ($poolchoice)
		{
			case "excrew":
				include("veritas/include/qrygetexcrewlist.inc");
				
				$excrew="<table id=\"excrewdetails\" class=\"listcol\" cellspacing=\"1\" cellpadding=\"0\">";
					$ctndata=0;
					if($qrygetexcrewlist)
					{
						
						while($rowgetexcrewlist=mysql_fetch_array($qrygetexcrewlist))
						{
							$dontshow=0;
							$applicantno1=$rowgetexcrewlist['APPLICANTNO'];
							if($applicantno1==$getapplicantno)
								$dontshow=1;
							
							//CHECK IF VESSELTYPECODE EXISTS AND CHECK IF CREW HAS IT
							if(!empty($vesseltypecode))
							{
								$qrychkvtc=mysql_query("SELECT x.APPLICANTNO,x.VESSELTYPECODE
									FROM
									(SELECT c.APPLICANTNO,v.VESSELTYPECODE
									FROM crewchange c
									LEFT JOIN vessel v ON c.VESSELCODE=v.VESSELCODE
									WHERE c.APPLICANTNO=$applicantno1 
									AND v.VESSELTYPECODE='$vesseltypecode'
									UNION
									SELECT APPLICANTNO,VESSELTYPECODE
									FROM crewexperience
									WHERE APPLICANTNO=$applicantno1 
									AND VESSELTYPECODE='$vesseltypecode') x") or die(mysql_error());
								if(mysql_num_rows($qrychkvtc)==0)
									$dontshow=1;
							}

							// GET ONBOARD (2)
							$qryonboard=mysql_query("
								SELECT VESSEL,IF(DATECHANGEDISEMB = NULL,DATEDISEMB,DATECHANGEDISEMB) AS DATEDISEMB,DATEEMB,c.VESSELCODE
								FROM crewchange c 
								LEFT JOIN vessel v ON c.VESSELCODE=v.VESSELCODE
								WHERE DEPMNLDATE IS NOT NULL AND ARRMNLDATE IS NULL AND APPLICANTNO=$applicantno1") 
								or die(mysql_error());
							if(mysql_num_rows($qryonboard)>0)
							{
								$rowonboard=mysql_fetch_array($qryonboard);
								$vesselonboard1=$rowonboard["VESSEL"];
								$vesselcodeonboard1=$rowonboard["VESSELCODE"];
								$scheddateemb=date("M d 'y",strtotime($rowonboard["DATEEMB"]));
								$scheddatedisembraw=$rowonboard["DATEDISEMB"];
								$scheddatedisemb=date("M d 'y",strtotime($scheddatedisembraw));
								$onboardtitle1="Vessel:".$vesselonboard1."\nEmb:".$scheddateemb."\nDisEmb:".$scheddatedisemb."(est)";
								$onboard1="*";
//								if($vesselcodeonboard1==$vesselcode)
//									$dontshow=1;
							}
							else 
							{
								$vesselonboard1="";
								$scheddatedisemb="";
								$scheddatedisembraw="";
								$onboardtitle1="";
								$onboard1="&nbsp;";
							}
							// END OF GET ONBOARD (2)
							
							if($dontshow==0) //FILTER FOR NOT SHOWING SELECTED CREW
							{
								$crewcode1=$rowgetexcrewlist['CREWCODE'];
								$fname1=$rowgetexcrewlist['FNAME'];
								$gname1=$rowgetexcrewlist['GNAME'];
								$rankcode1=$rowgetexcrewlist['RANKCODE'];
								$poolname1=$fname1.", ".$gname1;
								
								// GET LAST VESSEL & DATEDISEMB
								$qrylastvessel=mysql_query("
									SELECT VESSEL,DATEDISEMB,ALIAS1 FROM
									(SELECT VESSEL,c.DATEDISEMB,ALIAS1
									FROM crewchange c
									LEFT JOIN vessel v ON c.VESSELCODE=v.VESSELCODE
									WHERE ARRMNLDATE IS NOT NULL AND c.APPLICANTNO=$applicantno1
									UNION
									SELECT VESSEL,DATEDISEMB,VESSEL AS ALIAS1
									FROM crewexperience
									WHERE APPLICANTNO=$applicantno1) x
									ORDER BY DATEDISEMB DESC 
									LIMIT 1") or die(mysql_error());
								
								if(mysql_num_rows($qrylastvessel)>0)
								{
									$rowlastvessel=mysql_fetch_array($qrylastvessel);
									$lastvessel1=$rowlastvessel["VESSEL"];
									$vesselalias1=$rowlastvessel["ALIAS1"];
									$datedisemb1=$rowlastvessel["DATEDISEMB"];
								}
								else 
								{
									$lastvessel1="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
									$datedisemb1="";
									$vesselalias1="";
								}
								$datedisembtitle="";
								if(!empty($datedisemb1))
								{
									$datedisembshow=date("M 'y",strtotime($datedisemb1));
									$datedisembtitle=date("m/d/Y",strtotime($datedisemb1));
								}
								else 
									$datedisembshow="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
								// END OF GET LAST VESSEL & DATEDISEMB
								
								// GET SCHOLAR OR FAST TRACK (1)
								$qryscholar=mysql_query("
									SELECT SCHOLASTICCODE,IF(cf.APPLICANTNO IS NULL,0,1) AS FASTTRACK,cs.MADEBY 
									FROM crew c 
									LEFT JOIN crewscholar cs ON c.APPLICANTNO=cs.APPLICANTNO
									LEFT JOIN crewfasttrack cf ON c.APPLICANTNO=cf.APPLICANTNO
									WHERE c.APPLICANTNO=$applicantno1") or die(mysql_error());
								$rowscholar=mysql_fetch_array($qryscholar);
								$scholasticcode1=$rowscholar["SCHOLASTICCODE"];
								$madeby1=$rowscholar["MADEBY"];
								$fasttrack1=$rowscholar["FASTTRACK"];
								$scholar_fttitle="";
									$scholar_ft="&nbsp;";
								if(!empty($madeby1) || $fasttrack1==1)
								{
									$scholar_ft="*";
									if(!empty($madeby1))
										$scholar_fttitle="Scholar";
									else 
										$scholar_fttitle="Fast Track";
								}
								// END OF GET SCHOLAR OR FAST TRACK (1)
								
								// GET WALK-IN (3)
								$qrywalkin=mysql_query("
									SELECT TYPE,DATEEMB FROM
									(SELECT 0 AS TYPE,DATEEMB
									FROM crewchange c
									WHERE APPLICANTNO=$applicantno1
									UNION
									SELECT 1 AS TYPE,DATEEMB
									FROM crewexperience c
									LEFT JOIN rank r ON c.RANKCODE=r.RANKCODE
									WHERE APPLICANTNO=$applicantno1 AND OJT=0) x
									ORDER BY DATEEMB LIMIT 1") or die(mysql_error());
								$walkin="&nbsp;";
								if(mysql_num_rows($qrywalkin)>0)
								{
									$rowwalkin=mysql_fetch_array($qrywalkin);
									$type1=$rowwalkin["TYPE"];
									$dateemb1=$rowwalkin["DATEEMB"];
									if($type1==1)
										$walkin="*";
								}
								// END OF GET WALK-IN (3)
								
								// GET PNI(FIT2WRK) OR INACTIVE (4)
								$inactivepnititle="";
								$inactive="";
								if($onboard1!="*") //if crew is on-board, no need to check inactive state
								{
									if(!empty($datedisemb1))
									{
										$datediff=(strtotime($datenow)-strtotime($datedisemb1))/86400;
										if($datediff>365)
											$inactive="*";
									}
								}
								$qrypni=mysql_query("
									SELECT DATEINJURED,REASON,RECOMMENDCODE
									FROM crewinjury c
									LEFT JOIN crewchange cc ON c.CCID=cc.CCID
									LEFT JOIN crewinjurychkup ci ON c.CCID=ci.CCID
									WHERE cc.APPLICANTNO=$applicantno1
					                ORDER BY DATECHECKUP DESC
					                LIMIT 1") or die(mysql_error());
								if(mysql_num_rows($qrypni)>0)
								{
									$pni="*";
								}
								else 
									$pni="";
								if(!empty($inactive) || !empty($pni))
								{
									$inactivepni="*";
									if(!empty($inactive))
										$inactivepnititle="Inactive";
									if(!empty($pni))
										$inactivepnititle="PNI";
									if(!empty($inactive) && !empty($pni))
										$inactivepnititle="PNI & Inactive";
								}
								else 
								{
									$inactivepni="&nbsp;";
									$inactive="&nbsp;";
								}
								// END OF GET PNI(FIT2WRK) OR INACTIVE (4)
								
								// GET VACATION (5)
								$qryvacation=mysql_query("
									SELECT DATESTART,DATEEND,REASON
									FROM crewvacation
									WHERE DATESTART<='$datenow' AND DATEEND>='$datenow' AND APPLICANTNO=$applicantno1") 
									or die(mysql_error());
								if(mysql_num_rows($qryvacation)>0)
								{
									$rowvacation=mysql_fetch_array($qryvacation);
									$datestart1=$rowvacation["DATESTART"];
									$dateend1=$rowvacation["DATEEND"];
									$reason1=$rowvacation["REASON"];
									$datestartshow=date("m/d/Y",strtotime($datestart1));
									$dateendshow=date("m/d/Y",strtotime($dateend1));
									$vacationtitle="Start: ".$datestartshow."\nEnd: ".$dateendshow."\n".$reason1;
									$vacation1="*";
								}
								else 
									$vacation1="&nbsp;";
								
								// END OF GET VACATION (5)
								
								$grade=0;
								
								// CHECK FOR ALERTS IF CHOSEN CREW HAS DISCREPANCY
									//check for disembarkdate if onboard
									$getalert="";
									if(!empty($scheddatedisembraw))
									{
										$scheddatedisembalert=date("M d Y",strtotime($scheddatedisembraw));
										if(strtotime($scheddatedisembraw)>=strtotime($dateemb))
											$getalert="$fname1, $gname1 is onboard the $vesselonboard1 until $scheddatedisembalert";
									}
//									if($vesselcodeonboard1==$vesselcode)
//									{
//										if(empty($getalert))
//											$getalert=""$fname1, $gname1 is onboard the $vesselonboard1 until $scheddatedisembalert";
//									}
								// END OF CHECK FOR ALERTS IF CHOSEN CREW HAS DISCREPANCY
								
								// CREATE ARRAY FOR SORTING
								$col1[$ctndata]=$poolname1;
								$col2[$ctndata]=$vesselalias1;
								$col2ttl[$ctndata]=$lastvessel1;
								$col3[$ctndata]=$datedisemb1;
								$col3show[$ctndata]=$datedisembshow;
								$col3ttl[$ctndata]=$datedisembtitle;
								$col4[$ctndata]=$grade;
								$col5[$ctndata]=$scholar_ft;
								$col5ttl[$ctndata]=$scholar_fttitle;
								$col6[$ctndata]=$onboard1;
								$col6ttl[$ctndata]=$onboardtitle1;
								$col7[$ctndata]=$walkin;
								$col8[$ctndata]=$inactivepni;
								$col8ttl[$ctndata]=$inactivepnititle;
								$col9[$ctndata]=$vacation1;
								$col9ttl[$ctndata]=$vacationtitle;
								$oth1[$ctndata]=$rankcode;
								$oth2[$ctndata]=$applicantno1;
								$dblclick[$ctndata]="";
								$dblclickalert[$ctndata]=$getalert;
								// END OF CREATE ARRAY FOR SORTING
								
								$ctndata++;
							}
						}
					}
					if($ctndata!=0)
					{
						$stuff = array(	"col1" => $col1,
										"col2" => $col2,
										"col2ttl" => $col2ttl,
										"col3" => $col3,
										"col3show" => $col3show,
										"col3ttl" => $col3ttl,
										"col4" => $col4,
										"col5" => $col5,
										"col5ttl" => $col5ttl,
										"col6" => $col6,
										"col6ttl" => $col6ttl,
										"col7" => $col7,
										"col8" => $col8,
										"col8ttl" => $col8ttl,
										"col9" => $col9,
										"col9ttl" => $col9ttl,
										"oth1" => $oth1,
										"oth2" => $oth2,
										"dblclick" => $dblclick,
										"dblclickalert" => $dblclickalert
									);
						$sortby=$stuff[$sortby];
						if($orderby=="SORT_DESC")
							array_multisort($sortby,SORT_DESC,$stuff["col1"],
															$stuff["col2"],
															$stuff["col3"],
															$stuff["col4"],
															$stuff["col5"],
															$stuff["col6"],
															$stuff["col7"],
															$stuff["col8"],
															$stuff["col9"],
															$stuff["col2ttl"],
															$stuff["col3show"],
															$stuff["col3ttl"],
															$stuff["col5ttl"],
															$stuff["col6ttl"],
															$stuff["col8ttl"],
															$stuff["col9ttl"],
															$stuff["oth1"],
															$stuff["oth2"],
															$stuff["dblclick"],
															$stuff["dblclickalert"]
															);
						else 
							array_multisort($sortby,SORT_ASC,$stuff["col1"],
															$stuff["col2"],
															$stuff["col3"],
															$stuff["col4"],
															$stuff["col5"],
															$stuff["col6"],
															$stuff["col7"],
															$stuff["col8"],
															$stuff["col9"],
															$stuff["col2ttl"],
															$stuff["col3show"],
															$stuff["col3ttl"],
															$stuff["col5ttl"],
															$stuff["col6ttl"],
															$stuff["col8ttl"],
															$stuff["col9ttl"],
															$stuff["oth1"],
															$stuff["oth2"],
															$stuff["dblclick"],
															$stuff["dblclickalert"]
															);
						
						$classtype="odd";
						for($i=0;$i<$ctndata;$i++)
						{
							$poolname1=$stuff["col1"][$i];
							$vesselalias1=$stuff["col2"][$i];
							$lastvessel1=$stuff["col2ttl"][$i];
							$datedisembshow=$stuff["col3show"][$i];
							$datedisembtitle=$stuff["col3ttl"][$i];
							$grade=$stuff["col4"][$i];
							$scholar_ft=$stuff["col5"][$i];
							$scholar_fttitle=$stuff["col5ttl"][$i];
							$onboard1=$stuff["col6"][$i];
							$onboardtitle1=$stuff["col6ttl"][$i];
							$walkin=$stuff["col7"][$i];
							$inactivepni=$stuff["col8"][$i];
							$inactivepnititle=$stuff["col8ttl"][$i];
							$vacation1=$stuff["col9"][$i];
							$vacationtitle=$stuff["col9ttl"][$i];
							$rankcode=$stuff["oth1"][$i];
							$applicantno1=$stuff["oth2"][$i];
							$dblclick1=$stuff["dblclick"][$i];
							$dblclickalert1=$stuff["dblclickalert"][$i];
							if(!empty($dblclickalert1))
								$placeondblclick="if(confirm('$dblclickalert1. Are you sure?')){selectcrew('$poolname1 ($rankcode)','$applicantno1','$rankcode1');}";
							else 
								$placeondblclick="selectcrew('$poolname1 ($rankcode)','$applicantno1','$rankcode1');";
						$excrew.="
							<tr style=\"border-top: 1px solid gray;\" $mouseovereffect class=\"$classtype\"
								ondblclick=\"$placeondblclick\">
								<td style=\"text-align:left;vertical-align:middle;\" title=\"$poolname1\">&nbsp;$poolname1</td>
								<td style=\"text-align:center;vertical-align:middle;\" title=\"$lastvessel1\">$vesselalias1</td>
								<td style=\"text-align:center;vertical-align:middle;\" title=\"$datedisembtitle\">&nbsp;$datedisembshow&nbsp;</td>
								<td style=\"text-align:center;vertical-align:middle;\">&nbsp;&nbsp;$grade&nbsp;&nbsp;</td>
								<td style=\"text-align:center;vertical-align:middle;\" title=\"$scholar_fttitle\">&nbsp;&nbsp;$scholar_ft&nbsp;&nbsp;</td>
								<td style=\"text-align:center;vertical-align:middle;\" title=\"$onboardtitle1\">&nbsp;&nbsp;$onboard1&nbsp;&nbsp;</td>
								<td style=\"text-align:center;vertical-align:middle;\" title=\"Walk-in\">&nbsp;&nbsp;$walkin&nbsp;&nbsp;</td>
								<td style=\"text-align:center;vertical-align:middle;\" title=\"$inactivepnititle\">&nbsp;&nbsp;$inactivepni&nbsp;&nbsp;</td>
								<td style=\"text-align:center;vertical-align:middle;\" title=\"$vacationtitle\">&nbsp;&nbsp;$vacation1&nbsp;&nbsp;</td>
								<td style=\"text-align:center;vertical-align:middle;\">
									<!--
									<input type=\"button\" value=\"V\" id=\"buttoncol\" onclick=\"selecttab201('personal201');embapplicantnohidden.value='$applicantno1';chkloading('view201',10);\" />
									-->
									<img src=\"images/buttons/btn_v_def.gif\" 
										onmousedown=\"this.src='images/buttons/btn_v_click.gif';\"
										onmouseup=\"this.src='images/buttons/btn_v_def.gif';\"
										onmouseout=\"this.src='images/buttons/btn_v_def.gif';\"
										onclick=\"selecttab201('personal201');embapplicantnohidden.value='$applicantno1';
											name201='$poolname1';chkloading('view201',10);\"
										width=\"20px\">
								</td>
							</tr>";
							if($classtype=="even") { $classtype="odd";} else { $classtype="even"; }
						}
					}
			$excrew.= "
				</table>";
				$resulttemp=$excrew;
			break;
			case "newhire":
				$qrygetnewhirelist=mysql_query("SELECT c.APPLICANTNO,c.CREWCODE,c.FNAME,c.GNAME,x.RANKCODE
					FROM crew c 
					LEFT JOIN (
						SELECT cs.APPLICANTNO,r.RANKCODE
						FROM
						(SELECT c.APPLICANTNO,MIN(r.RANKING) AS MINRANKING FROM crewchange c
						LEFT JOIN rank r ON c.RANKCODE=r.RANKCODE
						GROUP BY c.APPLICANTNO) cs
						LEFT JOIN rank r ON cs.MINRANKING=r.RANKING) x ON c.APPLICANTNO=x.APPLICANTNO
					LEFT JOIN crewexperience ce ON c.APPLICANTNO=ce.APPLICANTNO
					LEFT JOIN (SELECT APPLICANTNO,1 AS TYPE FROM crewchange 
								WHERE DEPMNLDATE IS NOT NULL AND ARRMNLDATE IS NOT NULL
								GROUP BY APPLICANTNO) cc ON c.APPLICANTNO=cc.APPLICANTNO
					LEFT JOIN (SELECT cc.APPLICANTNO,cic.RECOMMENDCODE
							      FROM crewinjury c
					      		  LEFT JOIN crewchange cc ON c.CCID=cc.CCID
							      LEFT JOIN
							        (SELECT cr.CCID,MAX(DATECHECKUP) AS MAXDATE
							          FROM crewinjurychkup cr GROUP BY cr.CCID) ci ON c.CCID=ci.CCID
							      LEFT JOIN crewinjurychkup cic ON cic.CCID=ci.CCID AND cic.DATECHECKUP=ci.MAXDATE) ci 
				                ON c.APPLICANTNO=ci.APPLICANTNO
					WHERE x.RANKCODE='$rankcode' AND ce.APPLICANTNO IS NULL AND cc.TYPE IS NULL AND 
					(ci.RECOMMENDCODE='FIT' OR ci.RECOMMENDCODE IS NULL)
					ORDER BY c.FNAME,c.GNAME") or die(mysql_error());
				$newhire="<table id=\"newhiredetails\" class=\"listcol\" cellspacing=\"1\" cellpadding=\"0\">";
					$ctndata=0;
					if($qrygetnewhirelist)
					{
						while($rowgetnewhirelist=mysql_fetch_array($qrygetnewhirelist))
						{
							$applicantno1=$rowgetnewhirelist['APPLICANTNO'];
							$crewcode1=$rowgetnewhirelist['CREWCODE'];
							$fname1=$rowgetnewhirelist['FNAME'];
							$gname1=$rowgetnewhirelist['GNAME'];
							$rankcode1=$rowgetnewhirelist['RANKCODE'];
							$poolname1=$fname1.", ".$gname1;
							
							// GET LAST VESSEL & DATEDISEMB
							$qrylastvessel=mysql_query("
								SELECT VESSEL,DATEDISEMB,ALIAS1 FROM
								(SELECT VESSEL,c.DATEDISEMB,ALIAS1 
								FROM crewchange c
								LEFT JOIN vessel v ON c.VESSELCODE=v.VESSELCODE
								WHERE ARRMNLDATE IS NOT NULL AND c.APPLICANTNO=$applicantno1
								UNION
								SELECT VESSEL,DATEDISEMB,VESSEL AS ALIAS1
								FROM crewexperience
								WHERE APPLICANTNO=$applicantno1) x
								ORDER BY DATEDISEMB DESC 
								LIMIT 1") or die(mysql_error());
							if(mysql_num_rows($qrylastvessel)>0)
							{
								$rowlastvessel=mysql_fetch_array($qrylastvessel);
								$lastvessel1=$rowlastvessel["VESSEL"];
								$vesselalias1=$rowlastvessel["ALIAS1"];
								$datedisemb1=$rowlastvessel["DATEDISEMB"];
							}
							else 
							{
								$lastvessel1="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
								$datedisemb1="";
								$vesselalias1="";
							}
							$datedisembtitle="";
							if(!empty($datedisemb1))
							{
								$datedisembshow=date("M 'y",strtotime($datedisemb1));
								$datedisembtitle=date("m/d/Y",strtotime($datedisemb1));
							}
							else 
								$datedisembshow="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							// END OF GET LAST VESSEL & DATEDISEMB
							
							// GET SCHOLAR OR FAST TRACK (1)
							$qryscholar=mysql_query("
								SELECT SCHOLASTICCODE,IF(cf.APPLICANTNO IS NULL,0,1) AS FASTTRACK,cs.MADEBY 
								FROM crew c 
								LEFT JOIN crewscholar cs ON c.APPLICANTNO=cs.APPLICANTNO
								LEFT JOIN crewfasttrack cf ON c.APPLICANTNO=cf.APPLICANTNO
								WHERE c.APPLICANTNO=$applicantno1") or die(mysql_error());
							$rowscholar=mysql_fetch_array($qryscholar);
							$scholasticcode1=$rowscholar["SCHOLASTICCODE"];
							$fasttrack1=$rowscholar["FASTTRACK"];
							$scholar_fttitle="";
							$scholar_ft="&nbsp;";
							if(!empty($scholasticcode1) || $fasttrack1==1)
							{
								$scholar_ft="*";
								if(!empty($scholasticcode1))
									$scholar_fttitle="Scholar";
								else 
									$scholar_fttitle="Fast Track";
							}
							// END OF GET SCHOLAR OR FAST TRACK (1)
							
							// GET ONBOARD (2)
							$qryonboard=mysql_query("
								SELECT VESSEL,IF(DATECHANGEDISEMB = NULL,DATEDISEMB,DATECHANGEDISEMB) AS DATEDISEMB
								FROM crewchange c 
								LEFT JOIN vessel v ON c.VESSELCODE=v.VESSELCODE
								WHERE DEPMNLDATE IS NOT NULL AND ARRMNLDATE IS NULL AND APPLICANTNO=$applicantno1") 
								or die(mysql_error());
							if(mysql_num_rows($qryonboard)>0)
							{
								$rowonboard=mysql_fetch_array($qryonboard);
								$vesselonboard1=$rowonboard["VESSEL"];
								$scheddatedisemb=date("M 'y",strtotime($rowonboard["DATEDISEMB"]));
								$onboardtitle1="Vessel:".$vesselonboard1."\nDisEmb:".$scheddatedisemb;
								$onboard1="*";
							}
							else 
							{
								$vesselonboard1="";
								$onboardtitle1="";
								$scheddatedisemb="";
								$onboard1="&nbsp;";
							}
							// END OF GET ONBOARD (2)
							
							// GET WALK-IN (3)
							$qrywalkin=mysql_query("
								SELECT TYPE,DATEEMB FROM
								(SELECT 0 AS TYPE,DATEEMB
								FROM crewchange c
								WHERE APPLICANTNO=$applicantno1
								UNION
								SELECT 1 AS TYPE,DATEEMB
								FROM crewexperience c
								LEFT JOIN rank r ON c.RANKCODE=r.RANKCODE
								WHERE APPLICANTNO=$applicantno1 AND OJT=0) x
								ORDER BY DATEEMB LIMIT 1") or die(mysql_error());
							$walkin="&nbsp;";
							if(mysql_num_rows($qrywalkin)>0)
							{
								$rowwalkin=mysql_fetch_array($qrywalkin);
								$type1=$rowwalkin["TYPE"];
								$dateemb1=$rowwalkin["DATEEMB"];
								if($type1==1)
									$walkin="*";
							}
							// END OF GET WALK-IN (3)
							
							// CREATE ARRAY FOR SORTING
							$col1[$ctndata]=$poolname1;
							$col2[$ctndata]=$vesselalias1;
							$col2ttl[$ctndata]=$lastvessel1;
							$col3[$ctndata]=$datedisemb1;
							$col3show[$ctndata]=$datedisembshow;
							$col3ttl[$ctndata]=$datedisembtitle;
							$col4[$ctndata]=$grade;
							$col5[$ctndata]=$scholar_ft;
							$col5ttl[$ctndata]=$scholar_fttitle;
							$col6[$ctndata]=$onboard1;
							$col6ttl[$ctndata]=$onboardtitle1;
							$col7[$ctndata]=$walkin;
							$col8[$ctndata]=$inactivepni;
							$col8ttl[$ctndata]=$inactivepnititle;
							$col9[$ctndata]=$vacation1;
							$col9ttl[$ctndata]=$vacationtitle;
							$oth1[$ctndata]=$rankcode;
							$oth2[$ctndata]=$applicantno1;
							$dblclick[$ctndata]="";
							$dblclickalert[$ctndata]=$getalert;
							// END OF CREATE ARRAY FOR SORTING
							
							$ctndata++;
						}
					}
					if($ctndata!=0)
					{
						$stuff = array(	"col1" => $col1,
										"col2" => $col2,
										"col2ttl" => $col2ttl,
										"col3" => $col3,
										"col3show" => $col3show,
										"col3ttl" => $col3ttl,
										"col4" => $col4,
										"col5" => $col5,
										"col5ttl" => $col5ttl,
										"col6" => $col6,
										"col6ttl" => $col6ttl,
										"col7" => $col7,
										"col8" => $col8,
										"col8ttl" => $col8ttl,
										"col9" => $col9,
										"col9ttl" => $col9ttl,
										"oth1" => $oth1,
										"oth2" => $oth2,
										"dblclick" => $dblclick,
										"dblclickalert" => $dblclickalert
									);
						$sortby=$stuff[$sortby];
						if($orderby=="SORT_DESC")
							array_multisort($sortby,SORT_DESC,$stuff["col1"],
															$stuff["col2"],
															$stuff["col3"],
															$stuff["col4"],
															$stuff["col5"],
															$stuff["col6"],
															$stuff["col7"],
															$stuff["col8"],
															$stuff["col9"],
															$stuff["col2ttl"],
															$stuff["col3show"],
															$stuff["col3ttl"],
															$stuff["col5ttl"],
															$stuff["col6ttl"],
															$stuff["col8ttl"],
															$stuff["col9ttl"],
															$stuff["oth1"],
															$stuff["oth2"],
															$stuff["dblclick"],
															$stuff["dblclickalert"]
															);
						else 
							array_multisort($sortby,SORT_ASC,$stuff["col1"],
															$stuff["col2"],
															$stuff["col3"],
															$stuff["col4"],
															$stuff["col5"],
															$stuff["col6"],
															$stuff["col7"],
															$stuff["col8"],
															$stuff["col9"],
															$stuff["col2ttl"],
															$stuff["col3show"],
															$stuff["col3ttl"],
															$stuff["col5ttl"],
															$stuff["col6ttl"],
															$stuff["col8ttl"],
															$stuff["col9ttl"],
															$stuff["oth1"],
															$stuff["oth2"],
															$stuff["dblclick"],
															$stuff["dblclickalert"]
															);
						
						$classtype="odd";
						for($i=0;$i<$ctndata;$i++)
						{
							$poolname1=$stuff["col1"][$i];
							$vesselalias1=$stuff["col2"][$i];
							$lastvessel1=$stuff["col2ttl"][$i];
							$datedisembshow=$stuff["col3show"][$i];
							$datedisembtitle=$stuff["col3ttl"][$i];
							$grade=$stuff["col4"][$i];
							$scholar_ft=$stuff["col5"][$i];
							$scholar_fttitle=$stuff["col5ttl"][$i];
							$onboard1=$stuff["col6"][$i];
							$onboardtitle1=$stuff["col6ttl"][$i];
							$walkin=$stuff["col7"][$i];
							$inactivepni=$stuff["col8"][$i];
							$inactivepnititle=$stuff["col8ttl"][$i];
							$vacation1=$stuff["col9"][$i];
							$vacationtitle=$stuff["col9ttl"][$i];
							$rankcode=$stuff["oth1"][$i];
							$applicantno1=$stuff["oth2"][$i];
							$dblclick1=$stuff["dblclick"][$i];
							$dblclickalert1=$stuff["dblclickalert"][$i];
							if(!empty($dblclickalert1))
								$placeondblclick="if(confirm('$dblclickalert1. Are you sure?')){selectcrew('$poolname1 ($rankcode)','$applicantno1','$rankcode1');}";
							else 
								$placeondblclick="selectcrew('$poolname1 ($rankcode)','$applicantno1','$rankcode1');";
						$newhire.= "
							<tr style=\"border-top: 1px solid gray;\" $mouseovereffect class=\"$classtype\"
								ondblclick=\"selectcrew('$poolname1 ($getrankcode1)','$applicantno1');\">
								<td style=\"text-align:left;vertical-align:middle;\" title=\"$applicantno1-$poolname1\">&nbsp;$poolname1</td>
								<td style=\"text-align:center;vertical-align:middle;\" title=\"$scholar_fttitle\">&nbsp;&nbsp;$scholar_ft&nbsp;&nbsp;</td>
								<td style=\"text-align:center;vertical-align:middle;\" title=\"$onboardtitle1\">&nbsp;&nbsp;$onboard1&nbsp;&nbsp;</td>
								<td style=\"text-align:center;vertical-align:middle;\">
									<img src=\"images/buttons/btn_v_def.gif\" 
										onmousedown=\"this.src='images/buttons/btn_v_click.gif';\"
										onmouseup=\"this.src='images/buttons/btn_v_def.gif';\"
										onmouseout=\"this.src='images/buttons/btn_v_def.gif';\"
										onclick=\"selecttab201('personal201');embapplicantnohidden.value='$applicantno1';
											name201='$poolname1';chkloading('view201',10);\"
										width=\"20px\">
								</td>
							</tr>";
							if($classtype=="even") { $classtype="odd";} else { $classtype="even"; }
						}
					}
					
				$newhire.= "
					</table>";
				$resulttemp=$newhire;
			break;
			case "scft":
				$qrygetscftlist=mysql_query("SELECT c.APPLICANTNO,c.CREWCODE,c.FNAME,c.GNAME,x.RANKCODE,cw.MADEDATE
					FROM crew c 
					LEFT JOIN crewscholar cw ON c.APPLICANTNO=cw.APPLICANTNO
					LEFT JOIN crewfasttrack cf ON c.APPLICANTNO=cf.APPLICANTNO
					LEFT JOIN (
						SELECT cs.APPLICANTNO,r.RANKCODE
						FROM
						(SELECT c.APPLICANTNO,MIN(r.RANKING) AS MINRANKING FROM crewchange c
						LEFT JOIN rank r ON c.RANKCODE=r.RANKCODE
						GROUP BY c.APPLICANTNO) cs
						LEFT JOIN rank r ON cs.MINRANKING=r.RANKING) x ON c.APPLICANTNO=x.APPLICANTNO
					LEFT JOIN (SELECT APPLICANTNO,1 AS TYPE FROM crewchange 
								WHERE DEPMNLDATE IS NOT NULL AND ARRMNLDATE IS NOT NULL
								GROUP BY APPLICANTNO) cc ON c.APPLICANTNO=cc.APPLICANTNO
					LEFT JOIN (SELECT cc.APPLICANTNO,cic.RECOMMENDCODE
							      FROM crewinjury c
					      		  LEFT JOIN crewchange cc ON c.CCID=cc.CCID
							      LEFT JOIN
							        (SELECT cr.CCID,MAX(DATECHECKUP) AS MAXDATE
							          FROM crewinjurychkup cr GROUP BY cr.CCID) ci ON c.CCID=ci.CCID
							      LEFT JOIN crewinjurychkup cic ON cic.CCID=ci.CCID AND cic.DATECHECKUP=ci.MAXDATE) ci 
				                ON c.APPLICANTNO=ci.APPLICANTNO
					WHERE x.RANKCODE='$rankcode' AND (ci.RECOMMENDCODE='FIT' OR ci.RECOMMENDCODE IS NULL) 
						AND (cf.FASTTRACKCODE IS NOT NULL OR cw.SCHOLASTICCODE IS NOT NULL) AND cw.YEARGRADUATE<CURRENT_DATE
					ORDER BY c.FNAME,c.GNAME") or die(mysql_error());
				$scft="<table id=\"scftdetails\" class=\"listcol\" cellspacing=\"1\" cellpadding=\"0\">";
					$ctndata=0;
					if($qrygetscftlist)
					{
						$classtype="listgray";
						while($rowgetscftlist=mysql_fetch_array($qrygetscftlist))
						{
							$applicantno1=$rowgetscftlist['APPLICANTNO'];
							$dontshow=0;
							
							//CHECK IF VESSELTYPECODE EXISTS AND CHECK IF CREW HAS IT
							if(!empty($vesseltypecode))
							{
								$qrychkvtc=mysql_query("SELECT x.APPLICANTNO,x.VESSELTYPECODE
									FROM
									(SELECT c.APPLICANTNO,v.VESSELTYPECODE
									FROM crewchange c
									LEFT JOIN vessel v ON c.VESSELCODE=v.VESSELCODE
									WHERE c.APPLICANTNO=$applicantno1 
									AND v.VESSELTYPECODE='$vesseltypecode'
									UNION
									SELECT APPLICANTNO,VESSELTYPECODE
									FROM crewexperience
									WHERE APPLICANTNO=$applicantno1 
									AND VESSELTYPECODE='$vesseltypecode') x") or die(mysql_error());
								if(mysql_num_rows($qrychkvtc)==0)
									$dontshow=1;
							}
							if($dontshow==0)
							{
								$crewcode1=$rowgetscftlist['CREWCODE'];
								$fname1=$rowgetscftlist['FNAME'];
								$gname1=$rowgetscftlist['GNAME'];
								$rankcode1=$rowgetscftlist['RANKCODE'];
								$poolname1=$fname1.", ".$gname1;
								
								// GET LAST VESSEL & DATEDISEMB
								$qrylastvessel=mysql_query("
									SELECT VESSEL,DATEDISEMB,ALIAS1 FROM
									(SELECT VESSEL,c.DATEDISEMB,ALIAS1
									FROM crewchange c
									LEFT JOIN vessel v ON c.VESSELCODE=v.VESSELCODE
									WHERE ARRMNLDATE IS NOT NULL AND c.APPLICANTNO=$applicantno1
									UNION
									SELECT VESSEL,DATEDISEMB,VESSEL AS ALIAS1
									FROM crewexperience
									WHERE APPLICANTNO=$applicantno1) x
									ORDER BY DATEDISEMB DESC 
									LIMIT 1") or die(mysql_error());
								if(mysql_num_rows($qrylastvessel)>0)
								{
									$rowlastvessel=mysql_fetch_array($qrylastvessel);
									$lastvessel1=$rowlastvessel["VESSEL"];
									$vesselalias1=$rowlastvessel["ALIAS1"];
									$datedisemb1=$rowlastvessel["DATEDISEMB"];
								}
								else 
								{
									$lastvessel1="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
									$datedisemb1="";
									$vesselalias1="";
								}
								$datedisembtitle="";
								if(!empty($datedisemb1))
								{
									$datedisembshow=date("M 'y",strtotime($datedisemb1));
									$datedisembtitle=date("m/d/Y",strtotime($datedisemb1));
								}
								else 
									$datedisembshow="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
								// END OF GET LAST VESSEL & DATEDISEMB
								
								// GET ONBOARD (2)
								$qryonboard=mysql_query("
									SELECT VESSEL,IF(DATECHANGEDISEMB = NULL,DATEDISEMB,DATECHANGEDISEMB) AS DATEDISEMB,DATEEMB
									FROM crewchange c 
									LEFT JOIN vessel v ON c.VESSELCODE=v.VESSELCODE
									WHERE DEPMNLDATE IS NOT NULL AND ARRMNLDATE IS NULL AND APPLICANTNO=$applicantno1") 
									or die(mysql_error());
								if(mysql_num_rows($qryonboard)>0)
								{
									$rowonboard=mysql_fetch_array($qryonboard);
									$vesselonboard1=$rowonboard["VESSEL"];
									$scheddateemb=date("M 'y",strtotime($rowonboard["DATEEMB"]));
									$scheddatedisemb=date("M 'y",strtotime($rowonboard["DATEDISEMB"]));
									$onboardtitle1="Vessel:".$vesselonboard1."\nEmb:".$scheddateemb."\nDisEmb:".$scheddatedisemb."(est)";
									$onboard1="*";
								}
								else 
								{
									$vesselonboard1="";
									$onboardtitle1="";
									$scheddatedisemb="";
									$onboard1="&nbsp;";
								}
								// END OF GET ONBOARD (2)
								
								// GET PNI(FIT2WRK) OR INACTIVE (4)
								$inactivepnititle="";
								$inactive="";
								if($onboard1!="*") //if crew is on-board, no need to check inactive state
								{
									if(!empty($datedisemb1))
									{
										$datediff=(strtotime($datenow)-strtotime($datedisemb1))/86400;
										if($datediff>365)
											$inactive="*";
									}
								}
								$qrypni=mysql_query("
									SELECT DATEINJURED,REASON,RECOMMENDCODE
									FROM crewinjury c
									LEFT JOIN crewchange cc ON c.CCID=cc.CCID
									LEFT JOIN crewinjurychkup ci ON c.CCID=ci.CCID
									WHERE cc.APPLICANTNO=$applicantno1
					                ORDER BY DATECHECKUP DESC
					                LIMIT 1") or die(mysql_error());
								if(mysql_num_rows($qrypni)>0)
								{
									$pni="*";
								}
								else 
									$pni="";
								if(!empty($inactive) || !empty($pni))
								{
									$inactivepni="*";
									if(!empty($inactive))
										$inactivepnititle="Inactive";
									if(!empty($pni))
										$inactivepnititle="PNI";
									if(!empty($inactive) && !empty($pni))
										$inactivepnititle="PNI & Inactive";
								}
								else 
									$inactivepni="&nbsp;";
								// END OF GET PNI(FIT2WRK) OR INACTIVE (4)
								
								// GET VACATION (5)
								$qryvacation=mysql_query("
									SELECT DATESTART,DATEEND,REASON
									FROM crewvacation
									WHERE DATESTART<='$datenow' AND DATEEND>='$datenow' AND APPLICANTNO=$applicantno1") 
									or die(mysql_error());
								$vacation1="&nbsp;";
								if(mysql_num_rows($qryvacation)>0)
								{
									$rowvacation=mysql_fetch_array($qryvacation);
									$datestart1=$rowvacation["DATESTART"];
									$dateend1=$rowvacation["DATEEND"];
									$reason1=$rowvacation["REASON"];
									$datestartshow=date("m/d/Y",strtotime($datestart1));
									$dateendshow=date("m/d/Y",strtotime($dateend1));
									$vacationtitle="Start: ".$datestartshow."\nEnd: ".$dateendshow."\n".$reason1;
									$vacation1="*";
								}
								// END OF GET VACATION (5)
								
								// CREATE ARRAY FOR SORTING
								$col1[$ctndata]=$poolname1;
								$col2[$ctndata]=$vesselalias1;
								$col2ttl[$ctndata]=$lastvessel1;
								$col3[$ctndata]=$datedisemb1;
								$col3show[$ctndata]=$datedisembshow;
								$col3ttl[$ctndata]=$datedisembtitle;
								$col4[$ctndata]=$grade;
								$col5[$ctndata]=$scholar_ft;
								$col5ttl[$ctndata]=$scholar_fttitle;
								$col6[$ctndata]=$onboard1;
								$col6ttl[$ctndata]=$onboardtitle1;
								$col7[$ctndata]=$walkin;
								$col8[$ctndata]=$inactivepni;
								$col8ttl[$ctndata]=$inactivepnititle;
								$col9[$ctndata]=$vacation1;
								$col9ttl[$ctndata]=$vacationtitle;
								$oth1[$ctndata]=$rankcode;
								$oth2[$ctndata]=$applicantno1;
								$dblclick[$ctndata]="";
								$dblclickalert[$ctndata]=$getalert;
								// END OF CREATE ARRAY FOR SORTING
									
								$ctndata++;
							}
						}
						if($ctndata!=0)
						{
							$stuff = array(	"col1" => $col1,
											"col2" => $col2,
											"col2ttl" => $col2ttl,
											"col3" => $col3,
											"col3show" => $col3show,
											"col3ttl" => $col3ttl,
											"col4" => $col4,
											"col5" => $col5,
											"col5ttl" => $col5ttl,
											"col6" => $col6,
											"col6ttl" => $col6ttl,
											"col7" => $col7,
											"col8" => $col8,
											"col8ttl" => $col8ttl,
											"col9" => $col9,
											"col9ttl" => $col9ttl,
											"oth1" => $oth1,
											"oth2" => $oth2,
											"dblclick" => $dblclick,
											"dblclickalert" => $dblclickalert
										);
							$sortby=$stuff[$sortby];
							if($orderby=="SORT_DESC")
								array_multisort($sortby,SORT_DESC,$stuff["col1"],
																$stuff["col2"],
																$stuff["col3"],
																$stuff["col4"],
																$stuff["col5"],
																$stuff["col6"],
																$stuff["col7"],
																$stuff["col8"],
																$stuff["col9"],
																$stuff["col2ttl"],
																$stuff["col3show"],
																$stuff["col3ttl"],
																$stuff["col5ttl"],
																$stuff["col6ttl"],
																$stuff["col8ttl"],
																$stuff["col9ttl"],
																$stuff["oth1"],
																$stuff["oth2"],
																$stuff["dblclick"],
																$stuff["dblclickalert"]
																);
							else 
								array_multisort($sortby,SORT_ASC,$stuff["col1"],
																$stuff["col2"],
																$stuff["col3"],
																$stuff["col4"],
																$stuff["col5"],
																$stuff["col6"],
																$stuff["col7"],
																$stuff["col8"],
																$stuff["col9"],
																$stuff["col2ttl"],
																$stuff["col3show"],
																$stuff["col3ttl"],
																$stuff["col5ttl"],
																$stuff["col6ttl"],
																$stuff["col8ttl"],
																$stuff["col9ttl"],
																$stuff["oth1"],
																$stuff["oth2"],
																$stuff["dblclick"],
																$stuff["dblclickalert"]
																);
							
							$classtype="odd";
							for($i=0;$i<$ctndata;$i++)
							{
								$poolname1=$stuff["col1"][$i];
								$vesselalias1=$stuff["col2"][$i];
								$lastvessel1=$stuff["col2ttl"][$i];
								$datedisembshow=$stuff["col3show"][$i];
								$datedisembtitle=$stuff["col3ttl"][$i];
								$grade=$stuff["col4"][$i];
								$scholar_ft=$stuff["col5"][$i];
								$scholar_fttitle=$stuff["col5ttl"][$i];
								$onboard1=$stuff["col6"][$i];
								$onboardtitle1=$stuff["col6ttl"][$i];
								$walkin=$stuff["col7"][$i];
								$inactivepni=$stuff["col8"][$i];
								$inactivepnititle=$stuff["col8ttl"][$i];
								$vacation1=$stuff["col9"][$i];
								$vacationtitle=$stuff["col9ttl"][$i];
								$rankcode=$stuff["oth1"][$i];
								$applicantno1=$stuff["oth2"][$i];
								$dblclick1=$stuff["dblclick"][$i];
								$dblclickalert1=$stuff["dblclickalert"][$i];
								if(!empty($dblclickalert1))
									$placeondblclick="if(confirm('$dblclickalert1. Are you sure?')){selectcrew('$poolname1 ($rankcode)','$applicantno1','$rankcode1');}";
								else 
									$placeondblclick="selectcrew('$poolname1 ($rankcode)','$applicantno1','$rankcode1');";
								$scft.="
								<tr style=\"border-top: 1px solid gray;\" $mouseovereffect class=\"$classtype\"
									ondblclick=\"selectcrew('$poolname1 ($getrankcode1)','$applicantno1');\">
									<td style=\"text-align:left;vertical-align:middle;\" title=\"$applicantno1-$poolname1\">&nbsp;$poolname1</td>
									<td style=\"text-align:center;vertical-align:middle;\" title=\"$lastvessel1\">$vesselalias1</td>
									<td style=\"text-align:center;vertical-align:middle;\" title=\"$datedisembtitle\">&nbsp;$datedisembshow&nbsp;</td>
									<td style=\"text-align:center;vertical-align:middle;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
									<td style=\"text-align:center;vertical-align:middle;\" title=\"$onboardtitle1\">&nbsp;&nbsp;$onboard1&nbsp;&nbsp;</td>
									<td style=\"text-align:center;vertical-align:middle;\" title=\"$inactivepnititle\">&nbsp;&nbsp;$inactivepni&nbsp;&nbsp;</td>
									<td style=\"text-align:center;vertical-align:middle;\" title=\"$vacationtitle\">&nbsp;&nbsp;$vacation1&nbsp;&nbsp;</td>
									<td style=\"text-align:center;vertical-align:middle;\">
										<img src=\"images/buttons/btn_v_def.gif\" 
											onmousedown=\"this.src='images/buttons/btn_v_click.gif';\"
											onmouseup=\"this.src='images/buttons/btn_v_def.gif';\"
											onmouseout=\"this.src='images/buttons/btn_v_def.gif';\"
											onclick=\"selecttab201('personal201');embapplicantnohidden.value='$applicantno1';
											name201='$poolname1';chkloading('view201',10);\"
											width=\"20px\">
									</td>
								</tr>";
								if($classtype=="even") { $classtype="odd";} else { $classtype="even"; }
							}
						}
					}
				$scft.= "
					</table>";
				$resulttemp=$scft;
			break;
			case "walkin":
				$qrygetwalkinlist=mysql_query("SELECT c.APPLICANTNO,c.CREWCODE,c.FNAME,c.GNAME,x.RANKCODE,IF(cf.FASTTRACKCODE IS NULL,0,1) AS FASTTRACK
					FROM crew c 
					LEFT JOIN crewscholar cw ON c.APPLICANTNO=cw.APPLICANTNO
					LEFT JOIN crewfasttrack cf ON c.APPLICANTNO=cf.APPLICANTNO
					LEFT JOIN (
						SELECT cs.APPLICANTNO,r.RANKCODE
						FROM
						(SELECT c.APPLICANTNO,MIN(r.RANKING) AS MINRANKING FROM crewchange c
						LEFT JOIN rank r ON c.RANKCODE=r.RANKCODE
						GROUP BY c.APPLICANTNO) cs
						LEFT JOIN rank r ON cs.MINRANKING=r.RANKING) x ON c.APPLICANTNO=x.APPLICANTNO
					LEFT JOIN (SELECT APPLICANTNO,1 AS TYPE FROM crewchange 
								WHERE DEPMNLDATE IS NOT NULL AND ARRMNLDATE IS NOT NULL
								GROUP BY APPLICANTNO) cc ON c.APPLICANTNO=cc.APPLICANTNO
					LEFT JOIN (SELECT cc.APPLICANTNO,cic.RECOMMENDCODE
							      FROM crewinjury c
					      		  LEFT JOIN crewchange cc ON c.CCID=cc.CCID
							      LEFT JOIN
							        (SELECT cr.CCID,MAX(DATECHECKUP) AS MAXDATE
							          FROM crewinjurychkup cr GROUP BY cr.CCID) ci ON c.CCID=ci.CCID
							      LEFT JOIN crewinjurychkup cic ON cic.CCID=ci.CCID AND cic.DATECHECKUP=ci.MAXDATE) ci 
				                ON c.APPLICANTNO=ci.APPLICANTNO
					WHERE x.RANKCODE='$rankcode' AND (ci.RECOMMENDCODE='FIT' OR ci.RECOMMENDCODE IS NULL) 
					ORDER BY c.FNAME,c.GNAME") or die(mysql_error());
				$walkin1="<table id=\"walkindetails\" class=\"listcol\" cellspacing=\"1\" cellpadding=\"0\">";
					$ctndata=0;
					if($qrygetwalkinlist)
					{
						$classtype="listgray";
						while($rowgetwalkinlist=mysql_fetch_array($qrygetwalkinlist))
						{
							$applicantno1=$rowgetwalkinlist['APPLICANTNO'];
							$dontshow=0;
							//CHECK IF VESSELTYPECODE EXISTS AND CHECK IF CREW HAS IT
							if(!empty($vesseltypecode))
							{
								$qrychkvtc=mysql_query("SELECT x.APPLICANTNO,x.VESSELTYPECODE
									FROM
									(SELECT c.APPLICANTNO,v.VESSELTYPECODE
									FROM crewchange c
									LEFT JOIN vessel v ON c.VESSELCODE=v.VESSELCODE
									WHERE c.APPLICANTNO=$applicantno1 
									AND v.VESSELTYPECODE='$vesseltypecode'
									UNION
									SELECT APPLICANTNO,VESSELTYPECODE
									FROM crewexperience
									WHERE APPLICANTNO=$applicantno1 
									AND VESSELTYPECODE='$vesseltypecode') x") or die(mysql_error());
								if(mysql_num_rows($qrychkvtc)==0)
									$dontshow=1;
							}
							if($dontshow==0)
							{
								// GET WALK-IN (3) IF NOT WALK-IN, DON'T SHOW
								$qrywalkin=mysql_query("
									SELECT TYPE,DATEEMB FROM
									(SELECT 0 AS TYPE,DATEEMB
									FROM crewchange c
									WHERE APPLICANTNO=$applicantno1
									UNION
									SELECT 1 AS TYPE,DATEEMB
									FROM crewexperience c
									LEFT JOIN rank r ON c.RANKCODE=r.RANKCODE
									WHERE APPLICANTNO=$applicantno1 AND OJT=0) x
									ORDER BY DATEEMB LIMIT 1") or die(mysql_error());
								
								if(mysql_num_rows($qrywalkin)>0)
								{
									$rowwalkin=mysql_fetch_array($qrywalkin);
									$type1=$rowwalkin["TYPE"];
									$dateemb1=$rowwalkin["DATEEMB"];
	//										$type1=1;
									if($type1==1)
									{
										$crewcode1=$rowgetwalkinlist['CREWCODE'];
										$fname1=$rowgetwalkinlist['FNAME'];
										$gname1=$rowgetwalkinlist['GNAME'];
										$rankcode1=$rowgetwalkinlist['RANKCODE'];
										$poolname1=$fname1.", ".$gname1;
										
										// GET LAST VESSEL & DATEDISEMB
										$qrylastvessel=mysql_query("
											SELECT VESSEL,DATEDISEMB,ALIAS1 FROM
											(SELECT VESSEL,c.DATEDISEMB,ALIAS1
											FROM crewchange c
											LEFT JOIN vessel v ON c.VESSELCODE=v.VESSELCODE
											WHERE ARRMNLDATE IS NOT NULL AND c.APPLICANTNO=$applicantno1
											UNION
											SELECT VESSEL,DATEDISEMB,VESSEL AS ALIAS1
											FROM crewexperience
											WHERE APPLICANTNO=$applicantno1) x
											ORDER BY DATEDISEMB DESC 
											LIMIT 1") or die(mysql_error());
										if(mysql_num_rows($qrylastvessel)>0)
										{
											$rowlastvessel=mysql_fetch_array($qrylastvessel);
											$lastvessel1=$rowlastvessel["VESSEL"];
											$vesselalias1=$rowlastvessel["ALIAS1"];
											$datedisemb1=$rowlastvessel["DATEDISEMB"];
										}
										else 
										{
											$lastvessel1="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
											$datedisemb1="";
											$vesselalias1="";
										}
										$datedisembtitle="";
										if(!empty($datedisemb1))
										{
											$datedisembshow=date("M 'y",strtotime($datedisemb1));
											$datedisembtitle=date("m/d/Y",strtotime($datedisemb1));
										}
										else 
											$datedisembshow="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
										// END OF GET LAST VESSEL & DATEDISEMB
										
										// GET FAST TRACK
										$fasttrack1=$rowgetwalkinlist['FASTTRACK'];
										$fasttrackshow="&nbsp;";
										if($fasttrack1==1)
											$fasttrackshow="*";
										// END OF GET FAST TRACK
										
										// GET ONBOARD (2)
										$qryonboard=mysql_query("
											SELECT VESSEL,IF(DATECHANGEDISEMB = NULL,DATEDISEMB,DATECHANGEDISEMB) AS DATEDISEMB,DATEEMB
											FROM crewchange c 
											LEFT JOIN vessel v ON c.VESSELCODE=v.VESSELCODE
											WHERE DEPMNLDATE IS NOT NULL AND ARRMNLDATE IS NULL AND APPLICANTNO=$applicantno1") 
											or die(mysql_error());
										if(mysql_num_rows($qryonboard)>0)
										{
											$rowonboard=mysql_fetch_array($qryonboard);
											$vesselonboard1=$rowonboard["VESSEL"];
											$scheddateemb=date("M 'y",strtotime($rowonboard["DATEEMB"]));
											$scheddatedisemb=date("M 'y",strtotime($rowonboard["DATEDISEMB"]));
											$onboardtitle1="Vessel:".$vesselonboard1."\nEmb:".$scheddateemb."\nDisEmb:".$scheddatedisemb."(est)";
											$onboard1="*";
										}
										else 
										{
											$vesselonboard1="";
											$scheddatedisemb="";
											$onboardtitle1="";
											$onboard1="&nbsp;";
										}
										// END OF GET ONBOARD (2)
										
										// GET PNI(FIT2WRK) OR INACTIVE (4)
										$inactivepnititle="";
										$inactive="";
										if($onboard1!="*") //if crew is on-board, no need to check inactive state
										{
											if(!empty($datedisemb1))
											{
												$datediff=(strtotime($datenow)-strtotime($datedisemb1))/86400;
												if($datediff>365)
													$inactive="*";
											}
										}
										$qrypni=mysql_query("
											SELECT DATEINJURED,REASON,RECOMMENDCODE
											FROM crewinjury c
											LEFT JOIN crewchange cc ON c.CCID=cc.CCID
											LEFT JOIN crewinjurychkup ci ON c.CCID=ci.CCID
											WHERE cc.APPLICANTNO=$applicantno1
							                ORDER BY DATECHECKUP DESC
							                LIMIT 1") or die(mysql_error());
										if(mysql_num_rows($qrypni)>0)
										{
											$pni="*";
										}
										else 
											$pni="";
										if(!empty($inactive) || !empty($pni))
										{
											$inactivepni="*";
											if(!empty($inactive))
												$inactivepnititle="Inactive";
											if(!empty($pni))
												$inactivepnititle="PNI";
											if(!empty($inactive) && !empty($pni))
												$inactivepnititle="PNI & Inactive";
										}
										else 
											$inactivepni="&nbsp;";
										// END OF GET PNI(FIT2WRK) OR INACTIVE (4)
										
										// GET VACATION (5)
										$qryvacation=mysql_query("
											SELECT DATESTART,DATEEND,REASON
											FROM crewvacation
											WHERE DATESTART<='$datenow' AND DATEEND>='$datenow' AND APPLICANTNO=$applicantno1") 
											or die(mysql_error());
										$vacation1="&nbsp;";
										if(mysql_num_rows($qryvacation)>0)
										{
											$rowvacation=mysql_fetch_array($qryvacation);
											$datestart1=$rowvacation["DATESTART"];
											$dateend1=$rowvacation["DATEEND"];
											$reason1=$rowvacation["REASON"];
											$datestartshow=date("m/d/Y",strtotime($datestart1));
											$dateendshow=date("m/d/Y",strtotime($dateend1));
											$vacationtitle="Start: ".$datestartshow."\nEnd: ".$dateendshow."\n".$reason1;
											$vacation1="*";
										}
										// END OF GET VACATION (5)
										
										// CREATE ARRAY FOR SORTING
										$col1[$ctndata]=$poolname1;
										$col2[$ctndata]=$vesselalias1;
										$col2ttl[$ctndata]=$lastvessel1;
										$col3[$ctndata]=$datedisemb1;
										$col3show[$ctndata]=$datedisembshow;
										$col3ttl[$ctndata]=$datedisembtitle;
										$col4[$ctndata]=$grade;
										$col5[$ctndata]=$scholar_ft;
										$col5ttl[$ctndata]=$scholar_fttitle;
										$col6[$ctndata]=$onboard1;
										$col6ttl[$ctndata]=$onboardtitle1;
										$col7[$ctndata]=$walkin;
										$col8[$ctndata]=$inactivepni;
										$col8ttl[$ctndata]=$inactivepnititle;
										$col9[$ctndata]=$vacation1;
										$col9ttl[$ctndata]=$vacationtitle;
										$oth1[$ctndata]=$rankcode;
										$oth2[$ctndata]=$applicantno1;
										$dblclick[$ctndata]="";
										$dblclickalert[$ctndata]=$getalert;
										// END OF CREATE ARRAY FOR SORTING
										
										$ctndata++;
									}
								}
							}
						}
						if($ctndata!=0)
						{
							$stuff = array(	"col1" => $col1,
											"col2" => $col2,
											"col2ttl" => $col2ttl,
											"col3" => $col3,
											"col3show" => $col3show,
											"col3ttl" => $col3ttl,
											"col4" => $col4,
											"col5" => $col5,
											"col5ttl" => $col5ttl,
											"col6" => $col6,
											"col6ttl" => $col6ttl,
											"col7" => $col7,
											"col8" => $col8,
											"col8ttl" => $col8ttl,
											"col9" => $col9,
											"col9ttl" => $col9ttl,
											"oth1" => $oth1,
											"oth2" => $oth2,
											"dblclick" => $dblclick,
											"dblclickalert" => $dblclickalert
										);
							$sortby=$stuff[$sortby];
							if($orderby=="SORT_DESC")
								array_multisort($sortby,SORT_DESC,$stuff["col1"],
																$stuff["col2"],
																$stuff["col3"],
																$stuff["col4"],
																$stuff["col5"],
																$stuff["col6"],
																$stuff["col7"],
																$stuff["col8"],
																$stuff["col9"],
																$stuff["col2ttl"],
																$stuff["col3show"],
																$stuff["col3ttl"],
																$stuff["col5ttl"],
																$stuff["col6ttl"],
																$stuff["col8ttl"],
																$stuff["col9ttl"],
																$stuff["oth1"],
																$stuff["oth2"],
																$stuff["dblclick"],
																$stuff["dblclickalert"]
																);
							else 
								array_multisort($sortby,SORT_ASC,$stuff["col1"],
																$stuff["col2"],
																$stuff["col3"],
																$stuff["col4"],
																$stuff["col5"],
																$stuff["col6"],
																$stuff["col7"],
																$stuff["col8"],
																$stuff["col9"],
																$stuff["col2ttl"],
																$stuff["col3show"],
																$stuff["col3ttl"],
																$stuff["col5ttl"],
																$stuff["col6ttl"],
																$stuff["col8ttl"],
																$stuff["col9ttl"],
																$stuff["oth1"],
																$stuff["oth2"],
																$stuff["dblclick"],
																$stuff["dblclickalert"]
																);
							
							$classtype="odd";
							for($i=0;$i<$ctndata;$i++)
							{
								$poolname1=$stuff["col1"][$i];
								$vesselalias1=$stuff["col2"][$i];
								$lastvessel1=$stuff["col2ttl"][$i];
								$datedisembshow=$stuff["col3show"][$i];
								$datedisembtitle=$stuff["col3ttl"][$i];
								$grade=$stuff["col4"][$i];
								$scholar_ft=$stuff["col5"][$i];
								$scholar_fttitle=$stuff["col5ttl"][$i];
								$onboard1=$stuff["col6"][$i];
								$onboardtitle1=$stuff["col6ttl"][$i];
								$walkin=$stuff["col7"][$i];
								$inactivepni=$stuff["col8"][$i];
								$inactivepnititle=$stuff["col8ttl"][$i];
								$vacation1=$stuff["col9"][$i];
								$vacationtitle=$stuff["col9ttl"][$i];
								$rankcode=$stuff["oth1"][$i];
								$applicantno1=$stuff["oth2"][$i];
								$dblclick1=$stuff["dblclick"][$i];
								$dblclickalert1=$stuff["dblclickalert"][$i];
								if(!empty($dblclickalert1))
									$placeondblclick="if(confirm('$dblclickalert1. Are you sure?')){selectcrew('$poolname1 ($rankcode)','$applicantno1','$rankcode1');}";
								else 
									$placeondblclick="selectcrew('$poolname1 ($rankcode)','$applicantno1','$rankcode1');";
								$walkin1 .= "
								<tr style=\"border-top: 1px solid gray;\" $mouseovereffect class=\"$classtype\"
									ondblclick=\"selectcrew('$poolname1 ($getrankcode1)','$applicantno1');\">
									<td style=\"text-align:left;vertical-align:middle;\" title=\"$applicantno1-$poolname1\">&nbsp;$poolname1</td>
									<td style=\"text-align:center;vertical-align:middle;\" title=\"$lastvessel1\">$vesselalias1</td>
									<td style=\"text-align:center;vertical-align:middle;\" title=\"$datedisembtitle\">&nbsp;&nbsp;$datedisembshow&nbsp;&nbsp;</td>
									<td style=\"text-align:center;vertical-align:middle;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
									<td style=\"text-align:center;vertical-align:middle;\">&nbsp;&nbsp;$fasttrackshow&nbsp;&nbsp;</td>
									<td style=\"text-align:center;vertical-align:middle;\" title=\"$onboardtitle1\">&nbsp;&nbsp;$onboard1&nbsp;&nbsp;</td>
									<td style=\"text-align:center;vertical-align:middle;\" title=\"$inactivepnititle\">&nbsp;&nbsp;$inactivepni&nbsp;&nbsp;</td>
									<td style=\"text-align:center;vertical-align:middle;\" title=\"$vacationtitle\">&nbsp;&nbsp;$vacation1&nbsp;&nbsp;</td>
									<td style=\"text-align:center;vertical-align:middle;\">
										<img src=\"images/buttons/btn_v_def.gif\" 
											onmousedown=\"this.src='images/buttons/btn_v_click.gif';\"
											onmouseup=\"this.src='images/buttons/btn_v_def.gif';\"
											onmouseout=\"this.src='images/buttons/btn_v_def.gif';\"
											onclick=\"selecttab201('personal201');embapplicantnohidden.value='$applicantno1';
											name201='$poolname1';chkloading('view201',10);\"
											width=\"20px\">
									</td>
								</tr>";
								if($classtype=="even") { $classtype="odd";} else { $classtype="even"; }
							}
						}
					}
				$walkin1 .= "
					</table>";
				$resulttemp=$walkin1;
			break;
			case "inactive":
				$qrygetinactivelist=mysql_query("SELECT c.APPLICANTNO,c.CREWCODE,c.FNAME,c.GNAME,x.RANKCODE,
					IF(cf.FASTTRACKCODE IS NULL,0,1) AS FASTTRACK
					FROM crew c 
					LEFT JOIN crewscholar cw ON c.APPLICANTNO=cw.APPLICANTNO
					LEFT JOIN crewfasttrack cf ON c.APPLICANTNO=cf.APPLICANTNO
					LEFT JOIN (
						SELECT cs.APPLICANTNO,r.RANKCODE
						FROM
						(SELECT c.APPLICANTNO,MIN(r.RANKING) AS MINRANKING FROM crewchange c
						LEFT JOIN rank r ON c.RANKCODE=r.RANKCODE
						GROUP BY c.APPLICANTNO) cs
						LEFT JOIN rank r ON cs.MINRANKING=r.RANKING) x ON c.APPLICANTNO=x.APPLICANTNO
					LEFT JOIN (SELECT APPLICANTNO,1 AS TYPE FROM crewchange 
								WHERE DEPMNLDATE IS NOT NULL AND ARRMNLDATE IS NOT NULL
								GROUP BY APPLICANTNO) cc ON c.APPLICANTNO=cc.APPLICANTNO
					LEFT JOIN (SELECT cc.APPLICANTNO,cic.RECOMMENDCODE
							      FROM crewinjury c
					      		  LEFT JOIN crewchange cc ON c.CCID=cc.CCID
							      LEFT JOIN
							        (SELECT cr.CCID,MAX(DATECHECKUP) AS MAXDATE
							          FROM crewinjurychkup cr GROUP BY cr.CCID) ci ON c.CCID=ci.CCID
							      LEFT JOIN crewinjurychkup cic ON cic.CCID=ci.CCID AND cic.DATECHECKUP=ci.MAXDATE) ci 
				                ON c.APPLICANTNO=ci.APPLICANTNO
					WHERE x.RANKCODE='$rankcode' AND (ci.RECOMMENDCODE='FIT' OR ci.RECOMMENDCODE IS NULL) 
					ORDER BY c.FNAME,c.GNAME") or die(mysql_error());
				$inactive="<table id=\"inactivedetails\" class=\"listcol\" cellspacing=\"1\" cellpadding=\"0\">";
					$ctndata=0;
					if($qrygetinactivelist)
					{
						$classtype="listgray";
						while($rowgetinactivelist=mysql_fetch_array($qrygetinactivelist))
						{
							$applicantno1=$rowgetinactivelist['APPLICANTNO'];
							$dontshow=0;
							//CHECK IF VESSELTYPECODE EXISTS AND CHECK IF CREW HAS IT
							if(!empty($vesseltypecode))
							{
								$qrychkvtc=mysql_query("SELECT x.APPLICANTNO,x.VESSELTYPECODE
									FROM
									(SELECT c.APPLICANTNO,v.VESSELTYPECODE
									FROM crewchange c
									LEFT JOIN vessel v ON c.VESSELCODE=v.VESSELCODE
									WHERE c.APPLICANTNO=$applicantno1 
									AND v.VESSELTYPECODE='$vesseltypecode'
									UNION
									SELECT APPLICANTNO,VESSELTYPECODE
									FROM crewexperience
									WHERE APPLICANTNO=$applicantno1 
									AND VESSELTYPECODE='$vesseltypecode') x") or die(mysql_error());
								if(mysql_num_rows($qrychkvtc)==0)
									$dontshow=1;
							}
							if($dontshow==0)
							{
								// GET LAST VESSEL & DATEDISEMB
								$qrylastvessel=mysql_query("
									SELECT VESSEL,DATEDISEMB,ALIAS1 FROM
									(SELECT VESSEL,c.DATEDISEMB,ALIAS1
									FROM crewchange c
									LEFT JOIN vessel v ON c.VESSELCODE=v.VESSELCODE
									WHERE ARRMNLDATE IS NOT NULL AND c.APPLICANTNO=$applicantno1
									UNION
									SELECT VESSEL,DATEDISEMB,VESSEL AS ALIAS1
									FROM crewexperience
									WHERE APPLICANTNO=$applicantno1) x
									ORDER BY DATEDISEMB DESC 
									LIMIT 1") or die(mysql_error());
								if(mysql_num_rows($qrylastvessel)>0)
								{
									$rowlastvessel=mysql_fetch_array($qrylastvessel);
									$lastvessel1=$rowlastvessel["VESSEL"];
									$vesselalias1=$rowlastvessel["ALIAS1"];
									$datedisemb1=$rowlastvessel["DATEDISEMB"];
								}
								else 
								{
									$lastvessel1="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
									$datedisemb1="";
									$vesselalias1="";
								}
								$datedisembtitle="";
								if(!empty($datedisemb1))
								{
									$datedisembshow=date("M 'y",strtotime($datedisemb1));
									$datedisembtitle=date("m/d/Y",strtotime($datedisemb1));
								}
								else 
									$datedisembshow="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
								// END OF GET LAST VESSEL & DATEDISEMB
								
								// GET ONBOARD (2)
								$qryonboard=mysql_query("
									SELECT VESSEL,IF(DATECHANGEDISEMB = NULL,DATEDISEMB,DATECHANGEDISEMB) AS DATEDISEMB,DATEEMB
									FROM crewchange c 
									LEFT JOIN vessel v ON c.VESSELCODE=v.VESSELCODE
									WHERE DEPMNLDATE IS NOT NULL AND ARRMNLDATE IS NULL AND APPLICANTNO=$applicantno1") 
									or die(mysql_error());
								if(mysql_num_rows($qryonboard)>0)
								{
									$onboard1=1;
								}
								else 
								{
									$onboard1=0;
								}
								// END OF GET ONBOARD (2)
								
								$countdays=0;
								if(!empty($datedisemb1))
									$countdays=(strtotime(date("Y-m-d"))-strtotime($datedisemb1))/86400;
								if($countdays>365 && $onboard1==0)
								{
									$crewcode1=$rowgetinactivelist['CREWCODE'];
									$fname1=$rowgetinactivelist['FNAME'];
									$gname1=$rowgetinactivelist['GNAME'];
									$rankcode1=$rowgetinactivelist['RANKCODE'];
									$poolname1=$fname1.", ".$gname1;
									$fasttrack1=$rowgetinactivelist['FASTTRACK'];
									$fasttrackshow="&nbsp;";
									$fasttracktitle="";
									// GET FAST TRACK
									if($fasttrack1==1)
									{
										$fasttrackshow="*";
										$fasttracktitle="Fast Track";
									}
									// END OF GET FAST TRACK
									
									
									
									// GET VACATION (5)
									$qryvacation=mysql_query("
										SELECT DATESTART,DATEEND,REASON
										FROM crewvacation
										WHERE DATESTART<='$datenow' AND DATEEND>='$datenow' AND APPLICANTNO=$applicantno1") 
										or die(mysql_error());
									$vacation1="&nbsp;";
									if(mysql_num_rows($qryvacation)>0)
									{
										$rowvacation=mysql_fetch_array($qryvacation);
										$datestart1=$rowvacation["DATESTART"];
										$dateend1=$rowvacation["DATEEND"];
										$reason1=$rowvacation["REASON"];
										$datestartshow=date("m/d/Y",strtotime($datestart1));
										$dateendshow=date("m/d/Y",strtotime($dateend1));
										$vacationtitle="Start: ".$datestartshow."\nEnd: ".$dateendshow."\n".$reason1;
										$vacation1="*";
									}
									else 
										$vacation1="&nbsp;&nbsp;&nbsp;&nbsp;";
									// END OF GET VACATION (5)
									
									// CREATE ARRAY FOR SORTING
									$col1[$ctndata]=$poolname1;
									$col2[$ctndata]=$vesselalias1;
									$col2ttl[$ctndata]=$lastvessel1;
									$col3[$ctndata]=$datedisemb1;
									$col3show[$ctndata]=$datedisembshow;
									$col3ttl[$ctndata]=$datedisembtitle;
									$col4[$ctndata]=$grade;
									$col5[$ctndata]=$scholar_ft;
									$col5ttl[$ctndata]=$scholar_fttitle;
									$col6[$ctndata]=$onboard1;
									$col6ttl[$ctndata]=$onboardtitle1;
									$col7[$ctndata]=$walkin;
									$col8[$ctndata]=$inactivepni;
									$col8ttl[$ctndata]=$inactivepnititle;
									$col9[$ctndata]=$vacation1;
									$col9ttl[$ctndata]=$vacationtitle;
									$oth1[$ctndata]=$rankcode;
									$oth2[$ctndata]=$applicantno1;
									$dblclick[$ctndata]="";
									$dblclickalert[$ctndata]=$getalert;
									// END OF CREATE ARRAY FOR SORTING
									
									$ctndata++;
								}
							}
						}
						if($ctndata!=0)
						{
							$stuff = array(	"col1" => $col1,
											"col2" => $col2,
											"col2ttl" => $col2ttl,
											"col3" => $col3,
											"col3show" => $col3show,
											"col3ttl" => $col3ttl,
											"col4" => $col4,
											"col5" => $col5,
											"col5ttl" => $col5ttl,
											"col6" => $col6,
											"col6ttl" => $col6ttl,
											"col7" => $col7,
											"col8" => $col8,
											"col8ttl" => $col8ttl,
											"col9" => $col9,
											"col9ttl" => $col9ttl,
											"oth1" => $oth1,
											"oth2" => $oth2,
											"dblclick" => $dblclick,
											"dblclickalert" => $dblclickalert
										);
							$sortby=$stuff[$sortby];
							if($orderby=="SORT_DESC")
								array_multisort($sortby,SORT_DESC,$stuff["col1"],
																$stuff["col2"],
																$stuff["col3"],
																$stuff["col4"],
																$stuff["col5"],
																$stuff["col6"],
																$stuff["col7"],
																$stuff["col8"],
																$stuff["col9"],
																$stuff["col2ttl"],
																$stuff["col3show"],
																$stuff["col3ttl"],
																$stuff["col5ttl"],
																$stuff["col6ttl"],
																$stuff["col8ttl"],
																$stuff["col9ttl"],
																$stuff["oth1"],
																$stuff["oth2"],
																$stuff["dblclick"],
																$stuff["dblclickalert"]
																);
							else 
								array_multisort($sortby,SORT_ASC,$stuff["col1"],
																$stuff["col2"],
																$stuff["col3"],
																$stuff["col4"],
																$stuff["col5"],
																$stuff["col6"],
																$stuff["col7"],
																$stuff["col8"],
																$stuff["col9"],
																$stuff["col2ttl"],
																$stuff["col3show"],
																$stuff["col3ttl"],
																$stuff["col5ttl"],
																$stuff["col6ttl"],
																$stuff["col8ttl"],
																$stuff["col9ttl"],
																$stuff["oth1"],
																$stuff["oth2"],
																$stuff["dblclick"],
																$stuff["dblclickalert"]
																);
							
							$classtype="odd";
							for($i=0;$i<$ctndata;$i++)
							{
								$poolname1=$stuff["col1"][$i];
								$vesselalias1=$stuff["col2"][$i];
								$lastvessel1=$stuff["col2ttl"][$i];
								$datedisembshow=$stuff["col3show"][$i];
								$datedisembtitle=$stuff["col3ttl"][$i];
								$grade=$stuff["col4"][$i];
								$scholar_ft=$stuff["col5"][$i];
								$scholar_fttitle=$stuff["col5ttl"][$i];
								$onboard1=$stuff["col6"][$i];
								$onboardtitle1=$stuff["col6ttl"][$i];
								$walkin=$stuff["col7"][$i];
								$inactivepni=$stuff["col8"][$i];
								$inactivepnititle=$stuff["col8ttl"][$i];
								$vacation1=$stuff["col9"][$i];
								$vacationtitle=$stuff["col9ttl"][$i];
								$rankcode=$stuff["oth1"][$i];
								$applicantno1=$stuff["oth2"][$i];
								$dblclick1=$stuff["dblclick"][$i];
								$dblclickalert1=$stuff["dblclickalert"][$i];
								if(!empty($dblclickalert1))
									$placeondblclick="if(confirm('$dblclickalert1. Are you sure?')){selectcrew('$poolname1 ($rankcode)','$applicantno1','$rankcode1');}";
								else 
									$placeondblclick="selectcrew('$poolname1 ($rankcode)','$applicantno1','$rankcode1');";
								$inactive.= "
								<tr style=\"border-top: 1px solid gray;\" $mouseovereffect class=\"$classtype\"
									ondblclick=\"selectcrew('$poolname1 ($getrankcode1)','$applicantno1');\">
									<td style=\"text-align:left;vertical-align:middle;\" title=\"$applicantno1-$poolname1\">&nbsp;$poolname1</td>
									<td style=\"text-align:center;vertical-align:middle;\" title=\"$lastvessel1\">$vesselalias1</td>
									<td style=\"text-align:center;vertical-align:middle;\" title=\"$datedisembtitle\">&nbsp;&nbsp;$datedisembshow&nbsp;&nbsp;</td>
									<td style=\"text-align:center;vertical-align:middle;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
									<td style=\"text-align:center;vertical-align:middle;\" title=\"$fasttracktitle\">&nbsp;&nbsp;$fasttrackshow&nbsp;&nbsp;</td>
									<td style=\"text-align:center;vertical-align:middle;\" title=\"$vacationtitle\">&nbsp;&nbsp;$vacation1&nbsp;&nbsp;</td>
									<td style=\"text-align:center;vertical-align:middle;\">
										<img src=\"images/buttons/btn_v_def.gif\" 
											onmousedown=\"this.src='images/buttons/btn_v_click.gif';\"
											onmouseup=\"this.src='images/buttons/btn_v_def.gif';\"
											onmouseout=\"this.src='images/buttons/btn_v_def.gif';\"
											onclick=\"selecttab201('personal201');embapplicantnohidden.value='$applicantno1';
											name201='$poolname1';chkloading('view201',10);\"
											width=\"20px\">
									</td>
								</tr>";
								if($classtype=="even") { $classtype="odd";} else { $classtype="even"; }
							}
						}
					}
				$inactive.= "
					</table>";
				$resulttemp=$inactive;
			break;
			case "searchcrew":
				if(!empty($searchfname))
					$getfname="c.FNAME LIKE '%$searchfname%'";
				if(!empty($searchgname))
				{
					if(empty($getfname))
						$getgname="c.GNAME LIKE '%$searchgname%'";
					else 
						$getgname="AND c.GNAME LIKE '%$searchgname%'";
				}
				$qrygetsearchcrewlist=mysql_query("SELECT c.APPLICANTNO,c.CREWCODE,c.FNAME,c.GNAME,x.RANKCODE
					FROM crew c 
					LEFT JOIN (
						SELECT cs.APPLICANTNO,r.RANKCODE
						FROM
						(SELECT c.APPLICANTNO,MIN(r.RANKING) AS MINRANKING FROM crewchange c
						LEFT JOIN rank r ON c.RANKCODE=r.RANKCODE
						GROUP BY c.APPLICANTNO) cs
						LEFT JOIN rank r ON cs.MINRANKING=r.RANKING) x ON c.APPLICANTNO=x.APPLICANTNO
					WHERE $getfname $getgname
					ORDER BY c.FNAME,c.GNAME") or die(mysql_error());
				$searchcrew="<table id=\"searchcrewdetails\" class=\"listcol\" cellspacing=\"1\" cellpadding=\"0\">";
					$ctndata=0;
					if($qrygetsearchcrewlist)
					{
						
						while($rowgetsearchcrewlist=mysql_fetch_array($qrygetsearchcrewlist))
						{
							$dontshow=0;
							$applicantno1=$rowgetsearchcrewlist['APPLICANTNO'];
							if($applicantno1==$getapplicantno)
								$dontshow=1;
								
							// GET ONBOARD (2)
							$qryonboard=mysql_query("
								SELECT VESSEL,IF(DATECHANGEDISEMB = NULL,DATEDISEMB,DATECHANGEDISEMB) AS DATEDISEMB,DATEEMB,c.VESSELCODE
								FROM crewchange c 
								LEFT JOIN vessel v ON c.VESSELCODE=v.VESSELCODE
								WHERE DEPMNLDATE IS NOT NULL AND ARRMNLDATE IS NULL AND APPLICANTNO=$applicantno1") 
								or die(mysql_error());
							if(mysql_num_rows($qryonboard)>0)
							{
								$rowonboard=mysql_fetch_array($qryonboard);
								$vesselonboard1=$rowonboard["VESSEL"];
								$vesselcodeonboard1=$rowonboard["VESSELCODE"];
								$scheddateemb=date("M d 'y",strtotime($rowonboard["DATEEMB"]));
								$scheddatedisembraw=$rowonboard["DATEDISEMB"];
								$scheddatedisemb=date("M d 'y",strtotime($scheddatedisembraw));
								$onboardtitle1="Vessel:".$vesselonboard1."\nEmb:".$scheddateemb."\nDisEmb:".$scheddatedisemb."(est)";
								$onboard1="*";
//								if($vesselcodeonboard1==$vesselcode)
//									$dontshow=1;
							}
							else 
							{
								$vesselonboard1="";
								$scheddatedisemb="";
								$scheddatedisembraw="";
								$onboardtitle1="";
								$onboard1="&nbsp;";
							}
							// END OF GET ONBOARD (2)
							
							if($dontshow==0) //FILTER FOR NOT SHOWING SELECTED CREW
							{
								$crewcode1=$rowgetsearchcrewlist['CREWCODE'];
								$fname1=$rowgetsearchcrewlist['FNAME'];
								$gname1=$rowgetsearchcrewlist['GNAME'];
								$rankcode1=$rowgetsearchcrewlist['RANKCODE'];
								$poolname1=$fname1.", ".$gname1;
								
								// GET RANK ALIAS1
								$qrygetrankalias=mysql_query("SELECT ALIAS1 FROM rank WHERE RANKCODE='$rankcode1'") or die(mysql_error());
								$rowgetrankalias=mysql_fetch_array($qrygetrankalias);
								$rankalias1=$rowgetrankalias["ALIAS1"];
								
								// GET LAST VESSEL & DATEDISEMB
								$qrylastvessel=mysql_query("
									SELECT VESSEL,DATEDISEMB,ALIAS1 FROM
									(SELECT VESSEL,c.DATEDISEMB,ALIAS1
									FROM crewchange c
									LEFT JOIN vessel v ON c.VESSELCODE=v.VESSELCODE
									WHERE ARRMNLDATE IS NOT NULL AND c.APPLICANTNO=$applicantno1
									UNION
									SELECT VESSEL,DATEDISEMB,VESSEL AS ALIAS1
									FROM crewexperience
									WHERE APPLICANTNO=$applicantno1) x
									ORDER BY DATEDISEMB DESC 
									LIMIT 1") or die(mysql_error());
								if(mysql_num_rows($qrylastvessel)>0)
								{
									$rowlastvessel=mysql_fetch_array($qrylastvessel);
									$lastvessel1=$rowlastvessel["VESSEL"];
									$vesselalias1=$rowlastvessel["ALIAS1"];
									$datedisemb1=$rowlastvessel["DATEDISEMB"];
								}
								else 
								{
									$lastvessel1="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
									$datedisemb1="";
									$vesselalias1="";
								}
								$datedisembtitle="";
								if(!empty($datedisemb1))
								{
									$datedisembshow=date("M 'y",strtotime($datedisemb1));
									$datedisembtitle=date("m/d/Y",strtotime($datedisemb1));
								}
								else 
									$datedisembshow="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
								// END OF GET LAST VESSEL & DATEDISEMB
								
								// GET SCHOLAR OR FAST TRACK (1)
								$qryscholar=mysql_query("
									SELECT SCHOLASTICCODE,IF(cf.APPLICANTNO IS NULL,0,1) AS FASTTRACK,cs.MADEBY 
									FROM crew c 
									LEFT JOIN crewscholar cs ON c.APPLICANTNO=cs.APPLICANTNO
									LEFT JOIN crewfasttrack cf ON c.APPLICANTNO=cf.APPLICANTNO
									WHERE c.APPLICANTNO=$applicantno1") or die(mysql_error());
								$rowscholar=mysql_fetch_array($qryscholar);
								$scholasticcode1=$rowscholar["SCHOLASTICCODE"];
								$madeby1=$rowscholar["MADEBY"];
								$fasttrack1=$rowscholar["FASTTRACK"];
								$scholar_fttitle="";
									$scholar_ft="&nbsp;";
								if(!empty($madeby1) || $fasttrack1==1)
								{
									$scholar_ft="*";
									if(!empty($madeby1))
										$scholar_fttitle="Scholar";
									else 
										$scholar_fttitle="Fast Track";
								}
								// END OF GET SCHOLAR OR FAST TRACK (1)
								
								// GET WALK-IN (3)
								$qrywalkin=mysql_query("
									SELECT TYPE,DATEEMB FROM
									(SELECT 0 AS TYPE,DATEEMB
									FROM crewchange c
									WHERE APPLICANTNO=$applicantno1
									UNION
									SELECT 1 AS TYPE,DATEEMB
									FROM crewexperience c
									LEFT JOIN rank r ON c.RANKCODE=r.RANKCODE
									WHERE APPLICANTNO=$applicantno1 AND OJT=0) x
									ORDER BY DATEEMB LIMIT 1") or die(mysql_error());
								$walkin="&nbsp;";
								if(mysql_num_rows($qrywalkin)>0)
								{
									$rowwalkin=mysql_fetch_array($qrywalkin);
									$type1=$rowwalkin["TYPE"];
									$dateemb1=$rowwalkin["DATEEMB"];
									if($type1==1)
										$walkin="*";
								}
								// END OF GET WALK-IN (3)
								
								// GET PNI(FIT2WRK) OR INACTIVE (4)
								$inactivepnititle="";
								$inactive="";
								if($onboard1!="*") //if crew is on-board, no need to check inactive state
								{
									if(!empty($datedisemb1))
									{
										$datediff=(strtotime($datenow)-strtotime($datedisemb1))/86400;
										if($datediff>365)
											$inactive="*";
									}
								}
								$qrypni=mysql_query("
									SELECT DATEINJURED,REASON,RECOMMENDCODE
									FROM crewinjury c
									LEFT JOIN crewchange cc ON c.CCID=cc.CCID
									LEFT JOIN crewinjurychkup ci ON c.CCID=ci.CCID
									WHERE cc.APPLICANTNO=$applicantno1
					                ORDER BY DATECHECKUP DESC
					                LIMIT 1") or die(mysql_error());
								if(mysql_num_rows($qrypni)>0)
								{
									$pni="*";
								}
								else 
									$pni="";
								if(!empty($inactive) || !empty($pni))
								{
									$inactivepni="*";
									if(!empty($inactive))
										$inactivepnititle="Inactive";
									if(!empty($pni))
										$inactivepnititle="PNI";
									if(!empty($inactive) && !empty($pni))
										$inactivepnititle="PNI & Inactive";
								}
								else 
									$inactivepni="&nbsp;";
								// END OF GET PNI(FIT2WRK) OR INACTIVE (4)
								
								// GET VACATION (5)
								$qryvacation=mysql_query("
									SELECT DATESTART,DATEEND,REASON
									FROM crewvacation
									WHERE DATESTART<='$datenow' AND DATEEND>='$datenow' AND APPLICANTNO=$applicantno1") 
									or die(mysql_error());
								if(mysql_num_rows($qryvacation)>0)
								{
									$rowvacation=mysql_fetch_array($qryvacation);
									$datestart1=$rowvacation["DATESTART"];
									$dateend1=$rowvacation["DATEEND"];
									$reason1=$rowvacation["REASON"];
									$datestartshow=date("m/d/Y",strtotime($datestart1));
									$dateendshow=date("m/d/Y",strtotime($dateend1));
									$vacationtitle="Start: ".$datestartshow."\nEnd: ".$dateendshow."\n".$reason1;
									$vacation1="*";
								}
								else 
									$vacation1="&nbsp;";
								
								// END OF GET VACATION (5)
								
								$grade=0;
								
								// CHECK FOR ALERTS IF CHOSEN CREW HAS DISCREPANCY
									//check for disembarkdate if onboard
									$getalert="";
									if(!empty($scheddatedisembraw))
									{
										$scheddatedisembalert=date("M d Y",strtotime($scheddatedisembraw));
										if(strtotime($scheddatedisembraw)>=strtotime($dateemb))
											$getalert="$fname1, $gname1 is onboard the $vesselonboard1 until $scheddatedisembalert";
									}
//									if($vesselcodeonboard1==$vesselcode)
//									{
//										if(empty($getalert))
//											$getalert=""$fname1, $gname1 is onboard the $vesselonboard1 until $scheddatedisembalert";
//									}
								// END OF CHECK FOR ALERTS IF CHOSEN CREW HAS DISCREPANCY
								
								// CREATE ARRAY FOR SORTING
								$col1[$ctndata]=$poolname1;
								$col2[$ctndata]=$vesselalias1;
								$col2ttl[$ctndata]=$lastvessel1;
								$col3[$ctndata]=$datedisemb1;
								$col3show[$ctndata]=$datedisembshow;
								$col3ttl[$ctndata]=$datedisembtitle;
								$col4[$ctndata]=$grade;
								$col5[$ctndata]=$scholar_ft;
								$col5ttl[$ctndata]=$scholar_fttitle;
								$col6[$ctndata]=$onboard1;
								$col6ttl[$ctndata]=$onboardtitle1;
								$col7[$ctndata]=$walkin;
								$col8[$ctndata]=$inactivepni;
								$col8ttl[$ctndata]=$inactivepnititle;
								$col9[$ctndata]=$vacation1;
								$col9ttl[$ctndata]=$vacationtitle;
								$oth1[$ctndata]=$rankcode1;
								$oth2[$ctndata]=$applicantno1;
								$oth3[$ctndata]=$rankalias1;
								$dblclick[$ctndata]="";
								$dblclickalert[$ctndata]=$getalert;
								// END OF CREATE ARRAY FOR SORTING
								
								$ctndata++;
							}
						}
					}
					if($ctndata!=0)
					{
						$stuff = array(	"col1" => $col1,
										"col2" => $col2,
										"col2ttl" => $col2ttl,
										"col3" => $col3,
										"col3show" => $col3show,
										"col3ttl" => $col3ttl,
										"col4" => $col4,
										"col5" => $col5,
										"col5ttl" => $col5ttl,
										"col6" => $col6,
										"col6ttl" => $col6ttl,
										"col7" => $col7,
										"col8" => $col8,
										"col8ttl" => $col8ttl,
										"col9" => $col9,
										"col9ttl" => $col9ttl,
										"oth1" => $oth1,
										"oth2" => $oth2,
										"oth3" => $oth3,
										"dblclick" => $dblclick,
										"dblclickalert" => $dblclickalert
									);
						$sortby=$stuff[$sortby];
						if($orderby=="SORT_DESC")
							array_multisort($sortby,SORT_DESC,$stuff["col1"],
															$stuff["col2"],
															$stuff["col3"],
															$stuff["col4"],
															$stuff["col5"],
															$stuff["col6"],
															$stuff["col7"],
															$stuff["col8"],
															$stuff["col9"],
															$stuff["col2ttl"],
															$stuff["col3show"],
															$stuff["col3ttl"],
															$stuff["col5ttl"],
															$stuff["col6ttl"],
															$stuff["col8ttl"],
															$stuff["col9ttl"],
															$stuff["oth1"],
															$stuff["oth2"],
															$stuff["oth3"],
															$stuff["dblclick"],
															$stuff["dblclickalert"]
															);
						else 
							array_multisort($sortby,SORT_ASC,$stuff["col1"],
															$stuff["col2"],
															$stuff["col3"],
															$stuff["col4"],
															$stuff["col5"],
															$stuff["col6"],
															$stuff["col7"],
															$stuff["col8"],
															$stuff["col9"],
															$stuff["col2ttl"],
															$stuff["col3show"],
															$stuff["col3ttl"],
															$stuff["col5ttl"],
															$stuff["col6ttl"],
															$stuff["col8ttl"],
															$stuff["col9ttl"],
															$stuff["oth1"],
															$stuff["oth2"],
															$stuff["oth3"],
															$stuff["dblclick"],
															$stuff["dblclickalert"]
															);
						
						$classtype="odd";
						for($i=0;$i<$ctndata;$i++)
						{
							$poolname1=$stuff["col1"][$i];
							$vesselalias1=$stuff["col2"][$i];
							$lastvessel1=$stuff["col2ttl"][$i];
							$datedisembshow=$stuff["col3show"][$i];
							$datedisembtitle=$stuff["col3ttl"][$i];
							$grade=$stuff["col4"][$i];
							$scholar_ft=$stuff["col5"][$i];
							$scholar_fttitle=$stuff["col5ttl"][$i];
							$onboard1=$stuff["col6"][$i];
							$onboardtitle1=$stuff["col6ttl"][$i];
							$walkin=$stuff["col7"][$i];
							$inactivepni=$stuff["col8"][$i];
							$inactivepnititle=$stuff["col8ttl"][$i];
							$vacation1=$stuff["col9"][$i];
							$vacationtitle=$stuff["col9ttl"][$i];
							$rankcode1=$stuff["oth1"][$i];
							$applicantno1=$stuff["oth2"][$i];
							$rankalias1=$stuff["oth3"][$i];
							$dblclick1=$stuff["dblclick"][$i];
							$dblclickalert1=$stuff["dblclickalert"][$i];
							if(!empty($dblclickalert1))
								$placeondblclick="if(confirm('$dblclickalert1. Are you sure?')){selectcrew('$poolname1 ($rankcode)','$applicantno1','$rankcode1');}";
							else 
								$placeondblclick="selectcrew('$poolname1 ($rankcode1)','$applicantno1','$rankcode1');";
						$searchcrew.="
							<tr style=\"border-top: 1px solid gray;\" $mouseovereffect class=\"$classtype\"
								ondblclick=\"$placeondblclick\">
								<td style=\"text-align:left;vertical-align:middle;\" title=\"$poolname1\">&nbsp;$poolname1($rankalias1)</td>
								<td style=\"text-align:center;vertical-align:middle;\" title=\"$lastvessel1\">$vesselalias1</td>
								<td style=\"text-align:center;vertical-align:middle;\" title=\"$datedisembtitle\">&nbsp;$datedisembshow&nbsp;</td>
								<td style=\"text-align:center;vertical-align:middle;\">&nbsp;&nbsp;$grade&nbsp;&nbsp;</td>
								<td style=\"text-align:center;vertical-align:middle;\" title=\"$scholar_fttitle\">&nbsp;&nbsp;$scholar_ft&nbsp;&nbsp;</td>
								<td style=\"text-align:center;vertical-align:middle;\" title=\"$onboardtitle1\">&nbsp;&nbsp;$onboard1&nbsp;&nbsp;</td>
								<td style=\"text-align:center;vertical-align:middle;\" title=\"Walk-in\">&nbsp;&nbsp;$walkin&nbsp;&nbsp;</td>
								<td style=\"text-align:center;vertical-align:middle;\" title=\"$inactivepnititle\">&nbsp;&nbsp;$inactivepni&nbsp;&nbsp;</td>
								<td style=\"text-align:center;vertical-align:middle;\" title=\"$vacationtitle\">&nbsp;&nbsp;$vacation1&nbsp;&nbsp;</td>
								<td style=\"text-align:center;vertical-align:middle;\">
									<!--
									<input type=\"button\" value=\"V\" id=\"buttoncol\" onclick=\"selecttab201('personal201');embapplicantnohidden.value='$applicantno1';chkloading('view201',10);\" />
									-->
									<img src=\"images/buttons/btn_v_def.gif\" 
										onmousedown=\"this.src='images/buttons/btn_v_click.gif';\"
										onmouseup=\"this.src='images/buttons/btn_v_def.gif';\"
										onmouseout=\"this.src='images/buttons/btn_v_def.gif';\"
										onclick=\"selecttab201('personal201');embapplicantnohidden.value='$applicantno1';
											name201='$poolname1';chkloading('view201',10);\"
										width=\"20px\">
								</td>
							</tr>";
							if($classtype=="even") { $classtype="odd";} else { $classtype="even"; }
						}
					}
			$searchcrew.= "
				</table>";
				$resulttemp=$searchcrew;
			break;
			case "applicant":
				$qrygetapplicantlist=mysql_query("SELECT c.APPLICANTNO,c.CREWCODE,c.FNAME,c.GNAME,a.ACCEPTDATE
					FROM applicantstatus a 
					LEFT JOIN crew c ON a.APPLICANTNO=c.APPLICANTNO
					WHERE a.VMCRANKCODE='$rankcode' AND c.CREWCODE IS NULL
					ORDER BY c.FNAME,c.GNAME") or die(mysql_error());
				$applicant="<table id=\"applicantdetails\" class=\"listcol\" cellspacing=\"1\" cellpadding=\"0\">";
					$ctndata=0;
					if($qrygetapplicantlist)
					{
						
						while($rowgetapplicantlist=mysql_fetch_array($qrygetapplicantlist))
						{
							$dontshow=0;
							$applicantno1=$rowgetapplicantlist['APPLICANTNO'];
							$acceptdate1=$rowgetapplicantlist['ACCEPTDATE'];
							if($applicantno1==$getapplicantno)
								$dontshow=1;
								
							//CHECK IF VESSELTYPECODE EXISTS AND CHECK IF CREW HAS IT
							if(!empty($vesseltypecode))
							{
								$qrychkvtc=mysql_query("SELECT x.APPLICANTNO,x.VESSELTYPECODE
									FROM
									(SELECT c.APPLICANTNO,v.VESSELTYPECODE
									FROM crewchange c
									LEFT JOIN vessel v ON c.VESSELCODE=v.VESSELCODE
									WHERE c.APPLICANTNO=$applicantno1 
									AND v.VESSELTYPECODE='$vesseltypecode'
									UNION
									SELECT APPLICANTNO,VESSELTYPECODE
									FROM crewexperience
									WHERE APPLICANTNO=$applicantno1 
									AND VESSELTYPECODE='$vesseltypecode') x") or die(mysql_error());
								if(mysql_num_rows($qrychkvtc)==0)
									$dontshow=1;
							}
							if($dontshow==0) //FILTER FOR NOT SHOWING SELECTED CREW
							{
								$crewcode1=$rowgetapplicantlist['CREWCODE'];
								$fname1=$rowgetapplicantlist['FNAME'];
								$gname1=$rowgetapplicantlist['GNAME'];
								$rankcode1=$rowgetapplicantlist['RANKCODE'];
								$poolname1=$fname1.", ".$gname1;
								
								// GET LAST VESSEL & DATEDISEMB
								$qrylastvessel=mysql_query("
									SELECT VESSEL,DATEDISEMB,ALIAS1 FROM
									(SELECT VESSEL,c.DATEDISEMB,ALIAS1
									FROM crewchange c
									LEFT JOIN vessel v ON c.VESSELCODE=v.VESSELCODE
									WHERE ARRMNLDATE IS NOT NULL AND c.APPLICANTNO=$applicantno1
									UNION
									SELECT VESSEL,DATEDISEMB,VESSEL AS ALIAS1
									FROM crewexperience
									WHERE APPLICANTNO=$applicantno1) x
									ORDER BY DATEDISEMB DESC 
									LIMIT 1") or die(mysql_error());
								if(mysql_num_rows($qrylastvessel)>0)
								{
									$rowlastvessel=mysql_fetch_array($qrylastvessel);
									$lastvessel1=$rowlastvessel["VESSEL"];
									$vesselalias1=$rowlastvessel["ALIAS1"];
									$datedisemb1=$rowlastvessel["DATEDISEMB"];
								}
								else 
								{
									$lastvessel1="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
									$datedisemb1="";
									$vesselalias1="";
								}
								$datedisembtitle="";
								if(!empty($datedisemb1))
								{
									$datedisembshow=date("M 'y",strtotime($datedisemb1));
									$datedisembtitle=date("m/d/Y",strtotime($datedisemb1));
								}
								else 
									$datedisembshow="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
								// END OF GET LAST VESSEL & DATEDISEMB
								
								
								// GET WALK-IN (3)
								$qrywalkin=mysql_query("
									SELECT TYPE,DATEEMB FROM
									(SELECT 0 AS TYPE,DATEEMB
									FROM crewchange c
									WHERE APPLICANTNO=$applicantno1
									UNION
									SELECT 1 AS TYPE,DATEEMB
									FROM crewexperience c
									LEFT JOIN rank r ON c.RANKCODE=r.RANKCODE
									WHERE APPLICANTNO=$applicantno1 AND OJT=0) x
									ORDER BY DATEEMB LIMIT 1") or die(mysql_error());
								$walkin="&nbsp;";
								if(mysql_num_rows($qrywalkin)>0)
								{
									$rowwalkin=mysql_fetch_array($qrywalkin);
									$type1=$rowwalkin["TYPE"];
									$dateemb1=$rowwalkin["DATEEMB"];
									if($type1==1)
										$walkin="*";
								}
								// END OF GET WALK-IN (3)
								
								// CHECK FOR ALERTS IF CHOSEN CREW HAS DISCREPANCY
									//check for disembarkdate if onboard
									$getalert="";
									if(!empty($scheddatedisembraw))
									{
										$scheddatedisembalert=date("M d Y",strtotime($scheddatedisembraw));
										if(strtotime($scheddatedisembraw)>=strtotime($dateemb))
											$getalert="$fname1, $gname1 is onboard the $vesselonboard1 until $scheddatedisembalert";
									}
									if(empty($acceptdate1))
										$getalert=1;
//									if($vesselcodeonboard1==$vesselcode)
//									{
//										if(empty($getalert))
//											$getalert=""$fname1, $gname1 is onboard the $vesselonboard1 until $scheddatedisembalert";
//									}
								// END OF CHECK FOR ALERTS IF CHOSEN CREW HAS DISCREPANCY
								
								// CREATE ARRAY FOR SORTING
								$col1[$ctndata]=$poolname1;
								$col2[$ctndata]=$vesselalias1;
								$col2ttl[$ctndata]=$lastvessel1;
								$col3[$ctndata]=$datedisemb1;
								$col3show[$ctndata]=$datedisembshow;
								$col3ttl[$ctndata]=$datedisembtitle;
								$col4[$ctndata]=$grade;
								$col5[$ctndata]=$scholar_ft;
								$col5ttl[$ctndata]=$scholar_fttitle;
								$col6[$ctndata]=$onboard1;
								$col6ttl[$ctndata]=$onboardtitle1;
								$col7[$ctndata]=$walkin;
								$col8[$ctndata]=$inactivepni;
								$col8ttl[$ctndata]=$inactivepnititle;
								$col9[$ctndata]=$vacation1;
								$col9ttl[$ctndata]=$vacationtitle;
								$oth1[$ctndata]=$rankcode;
								$oth2[$ctndata]=$applicantno1;
								$dblclick[$ctndata]="";
								$dblclickalert[$ctndata]=$getalert;
								// END OF CREATE ARRAY FOR SORTING
								
								$ctndata++;
							}
						}
					}
					if($ctndata!=0)
					{
						$stuff = array(	"col1" => $col1,
										"col2" => $col2,
										"col2ttl" => $col2ttl,
										"col3" => $col3,
										"col3show" => $col3show,
										"col3ttl" => $col3ttl,
										"col4" => $col4,
										"col5" => $col5,
										"col5ttl" => $col5ttl,
										"col6" => $col6,
										"col6ttl" => $col6ttl,
										"col7" => $col7,
										"col8" => $col8,
										"col8ttl" => $col8ttl,
										"col9" => $col9,
										"col9ttl" => $col9ttl,
										"oth1" => $oth1,
										"oth2" => $oth2,
										"dblclick" => $dblclick,
										"dblclickalert" => $dblclickalert
									);
						$sortby=$stuff[$sortby];
						if($orderby=="SORT_DESC")
							array_multisort($sortby,SORT_DESC,$stuff["col1"],
															$stuff["col2"],
															$stuff["col3"],
															$stuff["col4"],
															$stuff["col5"],
															$stuff["col6"],
															$stuff["col7"],
															$stuff["col8"],
															$stuff["col9"],
															$stuff["col2ttl"],
															$stuff["col3show"],
															$stuff["col3ttl"],
															$stuff["col5ttl"],
															$stuff["col6ttl"],
															$stuff["col8ttl"],
															$stuff["col9ttl"],
															$stuff["oth1"],
															$stuff["oth2"],
															$stuff["dblclick"],
															$stuff["dblclickalert"]
															);
						else 
							array_multisort($sortby,SORT_ASC,$stuff["col1"],
															$stuff["col2"],
															$stuff["col3"],
															$stuff["col4"],
															$stuff["col5"],
															$stuff["col6"],
															$stuff["col7"],
															$stuff["col8"],
															$stuff["col9"],
															$stuff["col2ttl"],
															$stuff["col3show"],
															$stuff["col3ttl"],
															$stuff["col5ttl"],
															$stuff["col6ttl"],
															$stuff["col8ttl"],
															$stuff["col9ttl"],
															$stuff["oth1"],
															$stuff["oth2"],
															$stuff["dblclick"],
															$stuff["dblclickalert"]
															);
						
						$classtype="odd";
						for($i=0;$i<$ctndata;$i++)
						{
							$poolname1=$stuff["col1"][$i];
							$vesselalias1=$stuff["col2"][$i];
							$lastvessel1=$stuff["col2ttl"][$i];
							$datedisembshow=$stuff["col3show"][$i];
							$datedisembtitle=$stuff["col3ttl"][$i];
							$grade=$stuff["col4"][$i];
							$scholar_ft=$stuff["col5"][$i];
							$scholar_fttitle=$stuff["col5ttl"][$i];
							$onboard1=$stuff["col6"][$i];
							$onboardtitle1=$stuff["col6ttl"][$i];
							$walkin=$stuff["col7"][$i];
							$inactivepni=$stuff["col8"][$i];
							$inactivepnititle=$stuff["col8ttl"][$i];
							$vacation1=$stuff["col9"][$i];
							$vacationtitle=$stuff["col9ttl"][$i];
							$rankcode=$stuff["oth1"][$i];
							$applicantno1=$stuff["oth2"][$i];
							$dblclick1=$stuff["dblclick"][$i];
							$dblclickalert1=$stuff["dblclickalert"][$i];
							if(!empty($dblclickalert1))
							{
								if($dblclickalert1==1)
									$placeondblclick="alert('Applicant has not yet been approved by fleet!')";
								else
									$placeondblclick="if(confirm('$dblclickalert1. Are you sure?')){selectcrew('$poolname1 ($rankcode)','$applicantno1','$rankcode1');}";
							}
							else 
								$placeondblclick="selectcrew('$poolname1 ($rankcode)','$applicantno1','$rankcode1');";
						$applicant.="
							<tr style=\"border-top: 1px solid gray;\" $mouseovereffect class=\"$classtype\"
								ondblclick=\"$placeondblclick\">
								<td style=\"text-align:left;vertical-align:middle;\" title=\"$poolname1\">&nbsp;$poolname1</td>
								<td style=\"text-align:center;vertical-align:middle;\" title=\"$lastvessel1\">$vesselalias1</td>
								<td style=\"text-align:center;vertical-align:middle;\" title=\"$datedisembtitle\">&nbsp;$datedisembshow&nbsp;</td>
								<td style=\"text-align:center;vertical-align:middle;\" title=\"Walk-in\">&nbsp;&nbsp;$walkin&nbsp;&nbsp;</td>
								<td style=\"text-align:center;vertical-align:middle;\">
									<!--
									<input type=\"button\" value=\"V\" id=\"buttoncol\" onclick=\"selecttab201('personal201');embapplicantnohidden.value='$applicantno1';chkloading('view201',10);\" />
									-->
									<img src=\"images/buttons/btn_v_def.gif\" 
										onmousedown=\"this.src='images/buttons/btn_v_click.gif';\"
										onmouseup=\"this.src='images/buttons/btn_v_def.gif';\"
										onmouseout=\"this.src='images/buttons/btn_v_def.gif';\"
										onclick=\"selecttab201('personal201');embapplicantnohidden.value='$applicantno1';
											name201='$poolname1';chkloading('view201',10);\"
										width=\"20px\">
								</td>
							</tr>";
							if($classtype=="even") { $classtype="odd";} else { $classtype="even"; }
						}
					}
			$applicant.= "
				</table>";
				$resulttemp=$applicant;
			break;
			case "foreign":
				$qrygetforeignlist=mysql_query("SELECT c.APPLICANTNO,c.CREWCODE,c.FNAME,c.GNAME,c.RANKCODE
					FROM crewforeign c 
					ORDER BY c.FNAME,c.GNAME") or die(mysql_error());
				$foreign="<table id=\"foreigndetails\" class=\"listcol\" cellspacing=\"1\" cellpadding=\"0\">";
					$ctndata=0;
					if($qrygetforeignlist)
					{
						$classtype="listgray";
						while($rowgetforeignlist=mysql_fetch_array($qrygetforeignlist))
						{
							$applicantno1=$rowgetforeignlist['APPLICANTNO'];
							$crewcode1=$rowgetforeignlist['CREWCODE'];
							$fname1=$rowgetforeignlist['FNAME'];
							$gname1=$rowgetforeignlist['GNAME'];
							$rankcode1=$rowgetforeignlist['RANKCODE'];
							$poolname1=$fname1.", ".$gname1;
							
							// CREATE ARRAY FOR SORTING
							$col1[$ctndata]=$poolname1;
							$col2[$ctndata]=$vesselalias1;
							$col2ttl[$ctndata]=$lastvessel1;
							$oth1[$ctndata]=$rankcode1;
							$oth2[$ctndata]=$applicantno1;
							$dblclick[$ctndata]="";
							$dblclickalert[$ctndata]=$getalert;
							// END OF CREATE ARRAY FOR SORTING
							
							$ctndata++;
						}
						if($ctndata!=0)
						{
							$stuff = array(	"col1" => $col1,
											"col2" => $col2,
											"col2ttl" => $col2ttl,
											"oth1" => $oth1,
											"oth2" => $oth2,
											"dblclick" => $dblclick,
											"dblclickalert" => $dblclickalert
										);
							$sortby=$stuff[$sortby];
							if($orderby=="SORT_DESC")
								array_multisort($sortby,SORT_DESC,$stuff["col1"],
																$stuff["col2"],
																$stuff["col2ttl"],
																$stuff["oth1"],
																$stuff["oth2"],
																$stuff["dblclick"],
																$stuff["dblclickalert"]
																);
							else 
								array_multisort($sortby,SORT_ASC,$stuff["col1"],
																$stuff["col2"],
																$stuff["col2ttl"],
																$stuff["oth1"],
																$stuff["oth2"],
																$stuff["dblclick"],
																$stuff["dblclickalert"]
																);
							
							$classtype="odd";
							for($i=0;$i<$ctndata;$i++)
							{
								$poolname1=$stuff["col1"][$i];
								$vesselalias1=$stuff["col2"][$i];
								$lastvessel1=$stuff["col2ttl"][$i];
								$rankcode1=$stuff["oth1"][$i];
								$applicantno1=$stuff["oth2"][$i];
								$dblclick1=$stuff["dblclick"][$i];
								$dblclickalert1=$stuff["dblclickalert"][$i];
								if(!empty($dblclickalert1))
									$placeondblclick="if(confirm('$dblclickalert1. Are you sure?')){selectcrew('$poolname1 ($rankcode1)','$applicantno1');}";
								else 
									$placeondblclick="selectcrew('$poolname1 ($rankcode1)','$applicantno1');";
								$foreign.= "
								<tr style=\"border-top: 1px solid gray;\" $mouseovereffect class=\"$classtype\"
									ondblclick=\"selectcrew('$poolname1 ($rankcode1)','$applicantno1');\">
									<td style=\"text-align:left;vertical-align:middle;\" title=\"$applicantno1-$poolname1\">&nbsp;$poolname1</td>
								</tr>";
								if($classtype=="even") { $classtype="odd";} else { $classtype="even"; }
							}
						}
					}
				$foreign.= "
					</table>";
				$resulttemp=$foreign;
			break;
			case "cadet":
				$qrygetapplicantlist=mysql_query("SELECT c.APPLICANTNO,c.CREWCODE,c.FNAME,c.GNAME,
					cs.SCHOOLID,ms.SCHOOL,ms.ALIAS,cs.YEARGRADUATE
					FROM crewscholar cs 
					LEFT JOIN maritimeschool ms ON cs.SCHOOLID=ms.SCHOOLID
					LEFT JOIN crew c ON cs.APPLICANTNO=c.APPLICANTNO
					LEFT JOIN crewchange cc ON cs.APPLICANTNO=cc.APPLICANTNO
					WHERE cs.YEARGRADUATE>CURRENT_DATE OR cc.APPLICANTNO IS NULL
					ORDER BY c.FNAME,c.GNAME") or die(mysql_error());
				$applicant="<table id=\"cadetdetails\" class=\"listcol\" cellspacing=\"1\" cellpadding=\"0\">";
					$ctndata=0;
					if($qrygetapplicantlist)
					{
						
						while($rowgetapplicantlist=mysql_fetch_array($qrygetapplicantlist))
						{
							$dontshow=0;
							$applicantno1=$rowgetapplicantlist["APPLICANTNO"];
							$crewcode1=$rowgetapplicantlist["CREWCODE"];
							$fname1=$rowgetapplicantlist["FNAME"];
							$gname1=$rowgetapplicantlist["GNAME"];
							$alias1=$rowgetapplicantlist["ALIAS"];
							$school1=$rowgetapplicantlist["SCHOOL"];
							$yeargraduate1=$rowgetapplicantlist["YEARGRADUATE"];
							$yeargraduate1show=date("M 'y",strtotime($yeargraduate1));
							$poolname1=$fname1.", ".$gname1;
							
							
							// CREATE ARRAY FOR SORTING
							$col1[$ctndata]=$poolname1;
							$col2[$ctndata]=$alias1;
							$col2ttl[$ctndata]=$school1;
							$col3[$ctndata]=$yeargraduate1;
							$col3show[$ctndata]=$yeargraduate1show;
							$col3ttl[$ctndata]=$datedisembtitle;
							$col4[$ctndata]=$grade;
							$col5[$ctndata]=$scholar_ft;
							$col5ttl[$ctndata]=$scholar_fttitle;
							$col6[$ctndata]=$onboard1;
							$col6ttl[$ctndata]=$onboardtitle1;
							$col7[$ctndata]=$walkin;
							$col8[$ctndata]=$inactivepni;
							$col8ttl[$ctndata]=$inactivepnititle;
							$col9[$ctndata]=$vacation1;
							$col9ttl[$ctndata]=$vacationtitle;
							$oth1[$ctndata]=$rankcode;
							$oth2[$ctndata]=$applicantno1;
							$dblclick[$ctndata]="";
							$dblclickalert[$ctndata]=$getalert;
							// END OF CREATE ARRAY FOR SORTING
							
							$ctndata++;
						}
					}
					if($ctndata!=0)
					{
						$stuff = array(	"col1" => $col1,
										"col2" => $col2,
										"col2ttl" => $col2ttl,
										"col3" => $col3,
										"col3show" => $col3show,
										"col3ttl" => $col3ttl,
										"col4" => $col4,
										"col5" => $col5,
										"col5ttl" => $col5ttl,
										"col6" => $col6,
										"col6ttl" => $col6ttl,
										"col7" => $col7,
										"col8" => $col8,
										"col8ttl" => $col8ttl,
										"col9" => $col9,
										"col9ttl" => $col9ttl,
										"oth1" => $oth1,
										"oth2" => $oth2,
										"dblclick" => $dblclick,
										"dblclickalert" => $dblclickalert
									);
						$sortby=$stuff[$sortby];
						if($orderby=="SORT_DESC")
							array_multisort($sortby,SORT_DESC,$stuff["col1"],
															$stuff["col2"],
															$stuff["col3"],
															$stuff["col4"],
															$stuff["col5"],
															$stuff["col6"],
															$stuff["col7"],
															$stuff["col8"],
															$stuff["col9"],
															$stuff["col2ttl"],
															$stuff["col3show"],
															$stuff["col3ttl"],
															$stuff["col5ttl"],
															$stuff["col6ttl"],
															$stuff["col8ttl"],
															$stuff["col9ttl"],
															$stuff["oth1"],
															$stuff["oth2"],
															$stuff["dblclick"],
															$stuff["dblclickalert"]
															);
						else 
							array_multisort($sortby,SORT_ASC,$stuff["col1"],
															$stuff["col2"],
															$stuff["col3"],
															$stuff["col4"],
															$stuff["col5"],
															$stuff["col6"],
															$stuff["col7"],
															$stuff["col8"],
															$stuff["col9"],
															$stuff["col2ttl"],
															$stuff["col3show"],
															$stuff["col3ttl"],
															$stuff["col5ttl"],
															$stuff["col6ttl"],
															$stuff["col8ttl"],
															$stuff["col9ttl"],
															$stuff["oth1"],
															$stuff["oth2"],
															$stuff["dblclick"],
															$stuff["dblclickalert"]
															);
						
						$classtype="odd";
						for($i=0;$i<$ctndata;$i++)
						{
							$poolname1=$stuff["col1"][$i];
							$vesselalias1=$stuff["col2"][$i];
							$lastvessel1=$stuff["col2ttl"][$i];
							$datedisembshow=$stuff["col3show"][$i];
							$datedisembtitle=$stuff["col3ttl"][$i];
							$grade=$stuff["col4"][$i];
							$scholar_ft=$stuff["col5"][$i];
							$scholar_fttitle=$stuff["col5ttl"][$i];
							$onboard1=$stuff["col6"][$i];
							$onboardtitle1=$stuff["col6ttl"][$i];
							$walkin=$stuff["col7"][$i];
							$inactivepni=$stuff["col8"][$i];
							$inactivepnititle=$stuff["col8ttl"][$i];
							$vacation1=$stuff["col9"][$i];
							$vacationtitle=$stuff["col9ttl"][$i];
							$rankcode=$stuff["oth1"][$i];
							$applicantno1=$stuff["oth2"][$i];
							$dblclick1=$stuff["dblclick"][$i];
							$dblclickalert1=$stuff["dblclickalert"][$i];
//							if(!empty($dblclickalert1))
//							{
//								if($dblclickalert1==1)
//									$placeondblclick="alert('Applicant has not yet been approved by fleet!')";
//								else
//									$placeondblclick="if(confirm('$dblclickalert1. Are you sure?')){selectcrew('$poolname1 ($rankcode)','$applicantno1','$rankcode1');}";
//							}
//							else 
								$placeondblclick="chkcadet('$poolname1 (Cadet)','$applicantno1',rankcodehidden.value);";
						$applicant.="
							<tr style=\"border-top: 1px solid gray;\" $mouseovereffect class=\"$classtype\"
								ondblclick=\"$placeondblclick\">
								<td style=\"text-align:left;vertical-align:middle;\" title=\"$poolname1\">&nbsp;$poolname1</td>
								<td style=\"text-align:center;vertical-align:middle;\" title=\"$lastvessel1\">$vesselalias1</td>
								<td style=\"text-align:center;vertical-align:middle;\" title=\"$datedisembtitle\">&nbsp;$datedisembshow&nbsp;</td>
								<td style=\"text-align:center;vertical-align:middle;\">
									<img src=\"images/buttons/btn_v_def.gif\" 
										onmousedown=\"this.src='images/buttons/btn_v_click.gif';\"
										onmouseup=\"this.src='images/buttons/btn_v_def.gif';\"
										onmouseout=\"this.src='images/buttons/btn_v_def.gif';\"
										onclick=\"selecttab201('personal201');embapplicantnohidden.value='$applicantno1';
											name201='$poolname1';chkloading('view201',10);\"
										width=\"20px\">
								</td>
							</tr>";
							if($classtype=="even") { $classtype="odd";} else { $classtype="even"; }
						}
					}
			$applicant.= "
				</table>";
				$resulttemp=$applicant;
			break;
		}
	break;
}

// create set (^ delimited) for ccpno like ccpno,approvedby,approveddate,etc....
$ccpnoset=$getccpno."^".$preparedby."^".$prepareddate."^".$approvedby1."^".$approveddate1."^".$approvedby2."^".$approveddate2."^".$approvedby3."^".$approveddate3;
// create set (^ delimited) for vesseldetails like $vesselcode,$vesseltypecode,etc....
$vesseldetails=$vesselcode."^".$vesseltypecode."^".$vesselname;

$result=$actionajax."|".$poolchoice."|".$resulttemp."|".$vesseldetails."|".$ccpnoset."|".$getdiscrepancy;
//$result=$actionajax."|".$poolchoice."|".$resulttemp."|".$vesselcode."|".$ccpnoset."|".$getdiscrepancy."|".$vesseltypecode;

echo $result; 


?>