<?php

session_start();

include('veritas/connectdb.php');
//include('connectdb.php');
include("veritas/include/functions.inc");
include('veritas/include/stylephp.inc');

$datetimenow=date("Y-m-d H:i:s");
$datenow=date("Y-m-d");
$datenowshow=date("m/d/Y");

$dateformat = "dMY";

// $basedir = "docimages"; //change if different directory
$basedir = "docimg"; //change if different directory

//if(isset($_SESSION['employeeid']))
//	$employeeid=$_SESSION['employeeid'];
//$employeeid="123"; //temporary for testing purpose only

$employeeid = $_GET['employeeid'];

if (empty($employeeid))
{
	if(isset($_SESSION['employeeid']))
		$employeeid=$_SESSION['employeeid'];
}

$divcode = $_GET['divcode'];
$actionajax = $_GET['actionajax'];
$poolchoice = $_GET['poolchoice'];
$rankalias = $_GET['rankalias'];
$rankcode = $_GET['rankcode'];
$rankcodehidden = $_GET['rankcodehidden'];

	if (!empty($rankcodehidden))
	{
		$qrygetrank = mysql_query("SELECT RANKCODE,RANK,ALIAS1 FROM rank WHERE RANKCODE='$rankcodehidden' AND STATUS=1") or die(mysql_error());
		
		$rowgetrank = mysql_fetch_array($qrygetrank);
		$rankaliashidden = $rowgetrank["ALIAS1"];
		$rankhidden = $rowgetrank["RANK"];
	}

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
$estimatedate = $_GET['estimatedate'];
$disembreasoncode = $_GET['disembreasoncode'];
$promoteembdate = $_GET['promoteembdate'];
$promoteccid = $_GET['promoteccid'];
$promoteappno = $_GET['promoteappno'];
$batchnohidden = $_GET['batchnohidden'];
$shownobatch = $_GET['shownobatch'];

$dateembraw=date("Y-m-d",strtotime($dateemb));
$datedisembraw=date("Y-m-d",strtotime($datedisemb));
$changedatedisembraw=date("Y-m-d",strtotime($changedatedisemb));
$estimatedateraw=date("Y-m-d",strtotime($estimatedate));

if(empty($sortby))
	$sortby="oth3";
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
		
		$qryupdatepr = mysql_query("UPDATE crewchange SET DISEMBREASONCODE='PR' WHERE CCID=$getccid") or die(mysql_error());
		
		$actionajax="vesselselect";
	break;
	case "savechangedate":
		//get original datedisemb
		$qrydisemb=mysql_query("SELECT DATEDISEMB,ESTIMATEDATE FROM crewchange WHERE CCID=$embccidhidden") or die(mysql_error());
		$rowdisemb=mysql_fetch_array($qrydisemb);
		$getdatedisemborig=date("Y-m-d",strtotime($rowdisemb["DATEDISEMB"]));
		$getestimatedateorig=date("Y-m-d",strtotime($rowdisemb["ESTIMATEDATE"]));
		//$estimatedateraw
		if($getdatedisemborig==$changedatedisembraw)
		{
			$qrysavechangedate=mysql_query("UPDATE crewchange SET 
				DATECHANGEDISEMB=NULL,DISEMBREASONCODE=NULL,DATECHANGEBY='$employeeid',DATECHANGEDATE='$datetimenow',ESTIMATEDATE='$estimatedateraw'
				WHERE CCID=$embccidhidden") or die(mysql_error());
		}
		else 
		{
//			$qrysavechangedate=mysql_query("UPDATE crewchange SET 
//				DATECHANGEDISEMB='$changedatedisembraw',DISEMBREASONCODE='$disembreasoncode',DATECHANGEBY='$employeeid',
//				DATECHANGEDATE='$datetimenow'
//				WHERE CCID=$embccidhidden") or die(mysql_error());
			$qrysavechangedate=mysql_query("UPDATE crewchange SET 
				DATECHANGEDISEMB='$changedatedisembraw',DATECHANGEBY='$employeeid',
				DATECHANGEDATE='$datetimenow',ESTIMATEDATE='$estimatedateraw'
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
	case "deletecrew":
		
		$qrydeletecrewleft = mysql_query("DELETE FROM crewchange WHERE CCID=$ccidhidden") or die(mysql_error());
		$qrydeletecrewrelation = mysql_query("DELETE FROM crewchangerelation WHERE CCIDEMB=$ccidhidden") or die(mysql_error());
//		$qrydelpromotionrelation = mysql_query("DELETE FROM crewpromotionrelation WHERE CCIDPROMOTE=$embccidhidden") or die(mysql_error());

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
		$qrygetcrew = mysql_query("SELECT CREWCODE,CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,FNAME,GNAME,MNAME,
									c.ADDRESS,c.MUNICIPALITY,c.CITY,c.ZIPCODE,ab.BARANGAY,at.TOWN,ap.PROVINCE,
									CONCAT(c.ADDRESS,', ',MUNICIPALITY,', ',CITY,' ',ZIPCODE) AS ADDRESS1,
									CONCAT(c.ADDRESS,', ',ab.BARANGAY,', ',at.TOWN,', ',ap.PROVINCE,' ',ZIPCODE) AS ADDRESS2,
									ab.BRGYCODE,at.TOWNCODE,ap.PROVCODE,HIRINGRESTRICTION,
									TELNO,CEL1,CEL2,CEL3,BIRTHDATE,BIRTHPLACE,GENDER,CIVILSTATUS,NATIONALITY,RELIGION,
									SSS,TIN,WEIGHT,HEIGHT,EMAIL,OFWNO,t.TRADEROUTE AS PREFROUTE,
									cf.FASTTRACKCODE AS FASTTRACK,
									cs.SCHOLASTICCODE AS SCHOLAR,
									s.DESCRIPTION AS SCHOLARTYPE,f.FASTTRACK AS FASTTRACKTYPE,
									IF(c.RECOMMENDEDBY IS NULL,a.RECOMMENDEDBY,c.RECOMMENDEDBY) AS RECOMMENDEDBY,c.REMARKS,
									IF(ce.SCHOOLID IS NULL,ce.SCHOOLOTHERS,ms.SCHOOL) AS SCHOOL,
									IF(ce.COURSEID IS NULL,ce.COURSEOTHERS,mc.COURSE) AS COURSE,
									c.STATUS,c.UTILITY
									FROM crew c
									LEFT JOIN traderoute t ON t.TRADEROUTECODE=c.PREFROUTE
									LEFT JOIN applicant a ON a.APPLICANTNO=c.APPLICANTNO
									LEFT JOIN addrprovince ap ON ap.PROVCODE=c.PROVINCECODE
									LEFT JOIN addrtown at ON at.TOWNCODE=c.TOWNCODE AND at.PROVCODE=c.PROVINCECODE
									LEFT JOIN addrbarangay ab ON ab.BRGYCODE=c.BARANGAYCODE AND ab.TOWNCODE=c.TOWNCODE AND ab.PROVCODE=c.PROVINCECODE
									LEFT JOIN crewscholar cs ON cs.APPLICANTNO=c.APPLICANTNO
									LEFT JOIN scholar s ON s.SCHOLASTICCODE=cs.SCHOLASTICCODE
									LEFT JOIN crewfasttrack cf ON cf.APPLICANTNO=c.APPLICANTNO
									LEFT JOIN fasttrack f ON f.FASTTRACKCODE=cf.FASTTRACKCODE
									LEFT JOIN creweducation ce ON ce.APPLICANTNO=c.APPLICANTNO
									LEFT JOIN maritimeschool ms ON ms.SCHOOLID=ce.SCHOOLID
									LEFT JOIN maritimecourses mc ON mc.COURSEID=ce.COURSEID
									WHERE c.APPLICANTNO=$embapplicantnohidden") or die(mysql_error());
		
		$rowgetcrew = mysql_fetch_array($qrygetcrew);
		
		$personalcrewcode = $rowgetcrew["CREWCODE"];
		
		if (empty($personalcrewcode))
			$nocrewcode = "--NO CREWCODE--";
		else
			$nocrewcode = "";
		
		$personalname = $rowgetcrew["NAME"];
		
		$personalfname = $rowgetcrew["FNAME"];
		$personalgname = $rowgetcrew["GNAME"];
		$personalmname = $rowgetcrew["MNAME"];
		
		if (empty($personalname))
			$personalname = $personalfname . ", " . $personalgname . " " . $personalmname;
		
		if (!empty($rowgetcrew["HIRINGRESTRICTION"]))
			$personalhiringrestriction = $rowgetcrew["HIRINGRESTRICTION"];
		else
			$personalhiringrestriction = "<b>NONE</b>";
	
		
				$xaddress = $rowgetcrew["ADDRESS"];
				$xmunicipality = $rowgetcrew["MUNICIPALITY"];
				$xcity = $rowgetcrew["CITY"];
				$xzipcode = $rowgetcrew["ZIPCODE"];
				
				$xbarangay = $rowgetcrew["BARANGAY"];
				$xtown = $rowgetcrew["TOWN"];
				$xprovince = $rowgetcrew["PROVINCE"];
				
				$xprovcode = $rowgetcrew["PROVCODE"];
				$xtowncode = $rowgetcrew["TOWNCODE"];
				$xbrgycode = $rowgetcrew["BRGYCODE"];
				
//				if (!empty($rowpersonal["ADDRESS2"]))
//					$crewaddress = $rowpersonal["ADDRESS2"];
//				else 
//				{
					if (!empty($xprovcode) && !empty($xtowncode))
					{
						$personaladdress = $xaddress . ", ";
						
						if (!empty($xbrgycode))
							$personaladdress .= $xbarangay . ", ";
							
						if (!empty($xtowncode))
							$personaladdress .= $xtown . ", ";
							
						if (!empty($xprovcode))
							$personaladdress .= $xprovince;
							
						if (!empty($xzipcode))
							$personaladdress .= " " . $xzipcode;
					}
					else 
						$personaladdress = $rowgetcrew["ADDRESS1"];
//				}
					
				if (empty($personaladdress))
					$personaladdress = $xaddress . " " . $xmunicipality . " " . $xcity . " " . $xzipcode;
		
			$crewcel1 = $rowgetcrew["CEL1"];
			$crewcel2 = $rowgetcrew["CEL2"];
			$crewcel3 = $rowgetcrew["CEL3"];
			$crewtelno = $rowgetcrew["TELNO"];
			$crewmobile = $crewcel1;
			
			if (!empty($crewcel1))
				$crewtelno .= " / $crewcel1";
			
			if (!empty($crewcel2))
			{
				$crewtelno .= " / $crewcel2";
				$crewmobile .= " / $crewcel2";
			}
			
			if (!empty($crewcel3))
			{
				$crewtelno .= " / $crewcel3";
				$crewmobile .= " / $crewcel3";
			}
		
		
//		$personaladdress = $rowgetcrew["ADDRESS"];
		$personaltelno = $rowgetcrew["TELNO"];
		$personalbdate = date($dateformat,strtotime($rowgetcrew["BIRTHDATE"]));
		
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
			case "P":
					$personalcivilstatus = "SEPARATED";
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
		$personalrecommendedby = $rowgetcrew["RECOMMENDEDBY"];
		$personalremarks = "HIRING RESTRICTION:&nbsp;&nbsp;" . $personalhiringrestriction . "&nbsp;&nbsp;"; 
		
		if (!empty($rowgetcrew["REMARKS"]))
			$personalremarks .= " ( " . $rowgetcrew["REMARKS"] . " )";
		
		$personalschool = $rowgetcrew["SCHOOL"];
		$personalcourse = $rowgetcrew["COURSE"];
		$personalfasttrack = $rowgetcrew["FASTTRACK"];
		$personalfasttracktype = $rowgetcrew["FASTTRACKTYPE"];
		$personalscholar = $rowgetcrew["SCHOLAR"];
		$personalscholartype = $rowgetcrew["SCHOLARTYPE"];
		
		if ($rowgetcrew["UTILITY"] == 1)
			$personalutility = "( UTILITY )";
		else 
			$personalutility = "";
		
		// if (!empty($rowgetcrew["UTILITY"]))
			// $personalutility = "( " . $rowgetcrew["UTILITY"] . " )";
		// else 
			// $personalutility = "";
			
		if (empty($personalfasttracktype) && empty($personalscholartype) && empty($personalutility))
			$personalregular = "REGULAR";
		else 
			$personalregular = "";
		
		
		$personalstatus = $rowgetcrew["STATUS"];
		
//		$scftshow="";
//		
//		if(!empty($personalfasttrack))
//			$scftshow="FAST TRACK ($personalfasttrack)";
//		if(!empty($personalscholar))
//			$scftshow="SCHOLAR ($personalscholar)";
	
		
		$qrycurrentrank = mysql_query("SELECT z.DATEDISEMB,v.MANAGEMENTCODE,z.RANKCODE,r.RANK,r.ALIAS1 FROM 
										(
											SELECT '1' AS VMC,RANKCODE,DATEDISEMB,VESSELCODE
											FROM
												(
												SELECT RANKCODE,VESSELCODE,
												IF(IF(DATECHANGEDISEMB IS NULL,DATEDISEMB,DATECHANGEDISEMB) > CURRENT_DATE,
													CURRENT_DATE,IF(DATECHANGEDISEMB IS NULL,DATEDISEMB,DATECHANGEDISEMB)) AS DATEDISEMB
												FROM crewchange where APPLICANTNO=$embapplicantnohidden AND DATEEMB < CURRENT_DATE
												ORDER BY DATEDISEMB DESC
												) x
											
											UNION					
																
											SELECT '2' AS VMC,RANKCODE,DATEDISEMB,NULL
											FROM
												(
												SELECT RANKCODE,DATEDISEMB
												FROM crewexperience where APPLICANTNO=$embapplicantnohidden
												ORDER BY DATEDISEMB DESC
												) y
										) z
										LEFT JOIN rank r ON r.RANKCODE=z.RANKCODE
										LEFT JOIN vessel v ON v.VESSELCODE=z.VESSELCODE
										ORDER BY DATEDISEMB DESC
									") or die(mysql_error());
		
		$rowcurrentrank = mysql_fetch_array($qrycurrentrank);
		$currentrank = $rowcurrentrank["RANK"];
		$currentrankalias = $rowcurrentrank["ALIAS1"];
		$currentrankcode = $rowcurrentrank["RANKCODE"];
		
//		include("veritas/include/crewsummary.inc");  //THIS IS FOR STATUS OF CREW
		
		$qryfamilylist = mysql_query("SELECT CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,CONCAT(ADDRESS,', ',MUNICIPALITY,' ',CITY) AS ADDRESS, 
									fr.RELATION,TELNO
									FROM crewfamily cf
									LEFT JOIN familyrelation fr ON fr.RELCODE=cf.RELCODE
									WHERE fr.RELCODE <> 'HIM' AND
									APPLICANTNO=$embapplicantnohidden") or die(mysql_error());	
	
		$qrydoclist = mysql_query("SELECT * FROM (
									SELECT cd.DOCCODE,cd.DOCUMENT,cds.DOCNO,cds.DATEISSUED,cds.DATEEXPIRED,r.ALIAS2,cd.HASEXPIRY
									FROM crewdocstatus cds
									LEFT JOIN crewdocuments cd ON cd.DOCCODE=cds.DOCCODE
									LEFT JOIN rank r ON r.RANKCODE=cds.RANKCODE
									WHERE cd.TYPE='D' and cds.APPLICANTNO=$embapplicantnohidden
									ORDER BY cd.DOCUMENT,cds.DATEISSUED DESC) x
									GROUP BY DOCUMENT
									") or die(mysql_error());
		
		$qryliclist = mysql_query("SELECT * FROM (
									SELECT cd.DOCCODE,cd.DOCUMENT,cds.DOCNO,cds.DATEISSUED,cds.DATEEXPIRED,r.ALIAS2,cd.HASEXPIRY
									FROM crewdocstatus cds
									LEFT JOIN crewdocuments cd ON cd.DOCCODE=cds.DOCCODE
									LEFT JOIN rank r ON r.RANKCODE=cds.RANKCODE
									WHERE cd.TYPE='L'and cds.APPLICANTNO=$embapplicantnohidden
									ORDER BY cd.DOCUMENT,cds.DATEISSUED DESC) x
									GROUP BY DOCUMENT
									") or die(mysql_error());	
		
		$qryexperiencelist = mysql_query("SELECT ce.VESSEL,r.ALIAS1 AS RANKALIAS,ce.VESSELTYPECODE,ce.TRADEROUTECODE,
									IF (ce.MANNINGCODE = '',LEFT(ce.MANNINGOTHERS,15),LEFT(m.MANNING,15)) AS MANNING,
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
									cc.DATEEMB,IF (cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,cc.DISEMBREASONCODE,
									dr.REASON,dr2.REASON AS REASONPROMOTE,
									rp.ALIAS2 AS ALIAS2PROMOTE,ccp.DATEEMB AS DATEEMBPROMOTE,
									IF (ccp.DATECHANGEDISEMB IS NULL,ccp.DATEDISEMB,ccp.DATECHANGEDISEMB) AS DATEDISEMBPROMOTE,
									cp1.CCIDPROMOTE AS CHKPROMOTE,
									cc.DEPMNLDATE,cc.ARRMNLDATE,ccp.DEPMNLDATE AS DEPMNLPROMOTE,ccp.ARRMNLDATE AS ARRMNLPROMOTE,cc.CCID
									FROM crewchange cc
	                				LEFT JOIN rank r ON r.RANKCODE=cc.RANKCODE
									LEFT JOIN vessel v ON v.VESSELCODE=cc.VESSELCODE
									LEFT JOIN disembarkreason dr ON cc.DISEMBREASONCODE=dr.DISEMBREASONCODE
									LEFT JOIN crewpromotionrelation cp ON cc.CCID=cp.CCID
									LEFT JOIN crewchange ccp ON cp.CCIDPROMOTE=ccp.CCID
	                				LEFT JOIN rank rp ON rp.RANKCODE=ccp.RANKCODE
	                				LEFT JOIN disembarkreason dr2 ON ccp.DISEMBREASONCODE=dr2.DISEMBREASONCODE
	                				LEFT JOIN crewpromotionrelation cp1 ON cc.CCID=cp1.CCIDPROMOTE
									WHERE cc.DATEEMB <= CURRENT_DATE AND cc.APPLICANTNO=$embapplicantnohidden
									ORDER BY cc.DATEEMB DESC
									") or die(mysql_error());
		
		$qrycertlist = mysql_query("SELECT cd.DOCCODE,cd.DOCUMENT,ccs.DOCNO,ccs.DATEISSUED,ccs.GRADE,r.ALIAS2,cd.HASEXPIRY
									FROM crewcertstatus ccs
									LEFT JOIN crewdocuments cd ON cd.DOCCODE=ccs.DOCCODE
									LEFT JOIN rank r ON r.RANKCODE=ccs.RANKCODE
									WHERE cd.TYPE='C' AND ccs.APPLICANTNO=$embapplicantnohidden
									ORDER BY ccs.DATEISSUED DESC
									") or die(mysql_error());
		
		$qrymedicallist = mysql_query("SELECT cl.CLINIC,cm.DATECHECKUP,cm.DIAGNOSIS,cr.RECOMMENDATION,cm.REMARKS,cm.DOCCODE
									FROM crewmedical cm
									LEFT JOIN clinic cl ON cl.CLINICID=cm.CLINICID
									LEFT JOIN clinicrecommend cr ON cr.RECOMMENDCODE=cm.RECOMMENDCODE
									WHERE cm.APPLICANTNO=$embapplicantnohidden
									ORDER BY cm.DATECHECKUP DESC
									") or die(mysql_error());
		
		$qryperformancelist = mysql_query("SELECT v.VESSEL,cc.CCID,cc.DATEEMB,
									IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,
									cc.ARRMNLDATE,ccp.DATEEMB AS DATEEMBPROMOTE,dr.DISEMBREASONCODE,dr.REASON AS DISEMBREASON,
									IF (ccp.DATECHANGEDISEMB IS NULL,ccp.DATEDISEMB,ccp.DATECHANGEDISEMB) AS DATEDISEMBPROMOTE,
									cp1.CCIDPROMOTE AS CHKPROMOTE,cc.DEPMNLDATE,cc.ARRMNLDATE,ccp.DEPMNLDATE AS DEPMNLPROMOTE,ccp.ARRMNLDATE AS ARRMNLPROMOTE
									FROM crewchange cc
									LEFT JOIN vessel v ON v.VESSELCODE=cc.VESSELCODE
									LEFT JOIN crewpromotionrelation cp ON cp.CCID=cc.CCID
									LEFT JOIN crewpromotionrelation cp1 ON cc.CCID=cp1.CCIDPROMOTE
									LEFT JOIN crewchange ccp ON cp.CCIDPROMOTE=ccp.CCID
									LEFT JOIN disembarkreason dr ON cc.DISEMBREASONCODE=dr.DISEMBREASONCODE
									WHERE cc.DATEEMB <= CURRENT_DATE AND cc.APPLICANTNO=$embapplicantnohidden
									AND (cc.DEPMNLDATE IS NOT NULL OR cp1.CCIDPROMOTE IS NOT NULL)
									ORDER BY cc.DATEEMB DESC,cc.CCID
									") or die(mysql_error());
		
		// $qryperformancelist = mysql_query("SELECT v.VESSEL,cc.CCID,cc.DATEEMB,IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,cc.ARRMNLDATE
									// FROM crewchange cc
									// LEFT JOIN vessel v ON v.VESSELCODE=cc.VESSELCODE
									// WHERE cc.DATEEMB <= CURRENT_DATE AND cc.APPLICANTNO=$embapplicantnohidden
									// AND cc.DEPMNLDATE IS NOT NULL
									// ORDER BY cc.DATEEMB DESC,cc.CCID
									// ") or die(mysql_error());
		
//SELECT v.VESSEL,cc.CCID,ce.EVALNO,cc.DATEEMB,cc.DATEDISEMB
//FROM crewchange cc
//LEFT JOIN vessel v ON v.VESSELCODE=cc.VESSELCODE
//LEFT JOIN crewevalhdr ce ON ce.CCID=cc.CCID
//WHERE cc.APPLICANTNO=100899 
//GROUP BY cc.DATEEMB,ce.EVALNO
//ORDER BY cc.DATEEMB DESC,ce.EVALNO
		
		
$poea_id = 0;
$listed = 0;
$watchlistremarks = "";
$listed = checkwatchlist($embapplicantnohidden,$watchlistremarks);
	
if (!empty($watchlistremarks))
{
	$watchlistremarks_show = '<br /> "' . $watchlistremarks . '"';
}
	
	
switch ($listed)
{
	case 0	: $showlisted = "";break;
	case 1	: $showlisted = "[ VMC Watchlist! ]";  break;
	case 2	: $showlisted = "[ POEA Watchlist! ]"; break;
	case 3	: $showlisted = "[ VMC & POEA Watchlist! ]"; break;
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
							<td><span class=\"important\">$personalcrewcode $nocrewcode ($embapplicantnohidden)</span>&nbsp;&nbsp;
							";
							
							if (!empty($personalregular))
							{
$view201 .= "					<span class=\"important\" style=\"color:Blue;\">$personalregular</span><br />";
							}
							else 
							{
$view201 .= "
								<span class=\"important\" style=\"color:Blue;\">$personalutility</span><br />
								<span class=\"important\" style=\"color:Magenta;\">$personalfasttracktype</span>&nbsp;&nbsp;
								<span class=\"important\" style=\"color:Magenta;\">$personalscholartype</span>&nbsp;&nbsp;
								";
							}
$view201 .= "
							</td>
						</tr>
						<tr>
							<th>NAME</th>
							<th>:</th>
							<td><span style=\"font-size:1.2em;color:Black;\"><b>$personalname</b></span> <br />
								<span class=\"important\" >$showlisted $watchlistremarks_show</span></td>
						</tr>
						<tr>
							<th>PRESENT RANK</th>
							<th>:</th>
							<td valign=\"top\" style=\"color:Orange;font-size:1.5em;font-weight:Bold;\">$currentrankalias ($currentrank)</td>
						</tr>
							";


$view201 .= "
							</td>
						</tr>
						<tr>
							<th>ADDRESS</th>
							<th>:</th>
							<td>$personaladdress</td>
						</tr>
						<tr>
							<th>TEL. NO.</th>
							<th>:</th>
							<td>$crewtelno</td>
						</tr>
						<tr>
							<th>EMAIL</th>
							<th>:</th>
							<td><a href=\"mailto:$personalemail\" title=\"Send Email Now!\">$personalemail</a></td>
						</tr>
						<tr>
							<th>SCHOOL/COURSE</th>
							<th>:</th>
							<td>$personalschool / $personalcourse</td>
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
			
			<div style=\"width:100%;height:120px;\">		
			
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
			";

//			$presentstatus = "---";
			$standby = 0;
			$appstatus = "";
			
			
			//CHECK IN CREW WITHDRAWAL


			$qrywithdraw = mysql_query("SELECT IDNO,NFR,TRANSFER,SAFEKEEPREMARKS
									FROM crewwithdrawal
									WHERE APPLICANTNO=$embapplicantnohidden
									AND LIFTWITHDRAWAL = 0
									ORDER BY NFR DESC,FORMDATE DESC
								") or die(mysql_error());
								
			if (mysql_num_rows($qrywithdraw) > 0)
			{
				
				while($rowwithdraw = mysql_fetch_array($qrywithdraw))
				{
					$widno = $rowwithdraw["IDNO"];
					$wnfr = $rowwithdraw["NFR"];
					$wtransfer = $rowwithdraw["TRANSFER"];  //0 - NO TRANSFER; 1 - TRANSFER IN; 2 - TRANSFER OUTSIDE
					
					if ($wnfr == 1)
					{
						$wsafekeepremarks = $rowwithdraw["SAFEKEEPREMARKS"];
						$appstatus = "<a href=\"#\" title=\"$wsafekeepremarks\" style=\"color:Red;\">NOT FOR RE-HIRE</a>";
						// exit;
					}
					else
					{
						switch($wtransfer)
						{
							case "0" : $withdrawtype = "WITHDRAW"; break;
							case "1" : $withdrawtype = "LOANED"; break;
							case "2" : $withdrawtype = "TRANSFERRED"; break;
						}
						
						$appstatus = "<a href=\"#\" onclick=\"openWindow('withdrawcomments.php?applicantno=$embapplicantnohidden&idno=$widno', 'withdrawal', 600, 400);\" style=\"font-size:1.2em;font-weight:Bold;color:Red;\" title=\"View Withdrawal Remarks...\">$withdrawtype</a>";
						// exit;
					}
						
					
				}
			}
			//END OF CHECKING CREW WITHDRAWAL
			

			
			if ($personalstatus == 0)  //IF CREW IS INACTIVE
				$appstatus = "<a href=\"#\" title=\"If otherwise, please inform MIS to lift INACTIVE status.\" style=\"color:Red;\">INACTIVE(TAGGED)</a>";
				
			//CHECK FOR INJURIES
			
			// $qryinjury = mysql_query("
							// SELECT c.CCID,c.DATEINJURED,c.REASON,cic.IDNO,cic.RECOMMENDCODE
							// FROM crewinjury c
							// LEFT JOIN crewinjurychkup cic ON cic.CCID=c.CCID
							// LEFT JOIN crewchange cc ON cc.CCID=c.CCID
							// WHERE cc.APPLICANTNO=$embapplicantnohidden 
							// ORDER BY c.DATEINJURED DESC
							// LIMIT 1
					// ") or die(mysql_error());
			
			// if (mysql_num_rows($qryinjury) > 0)
			// {
				// $rowinjury = mysql_fetch_array($qryinjury);
				// if (!empty($rowinjury["DATEINJURED"]))
					// $dateinjured = date('m/d/Y',strtotime($rowinjury["DATEINJURED"]));
				
				// $injuryreason = $rowinjury["REASON"];
				
				// if ($rowinjury["RECOMMENDCODE"] != "FIT")
					// $appstatus = "<a href=\"#\" title=\"If otherwise, please inform MIS to change status.\" style=\"color:Red;\">INJURED</a>";
				
			// }
			
			//CREW DECEASED
			
			$qrydeceased = mysql_query("SELECT REASON FROM crewdeceased WHERE APPLICANTNO=$embapplicantnohidden") or die(mysql_error());
			
			if (mysql_num_rows($qrydeceased) > 0)
			{
				$rowdeceased = mysql_fetch_array($qrydeceased);
				
				$deceasedreason = $rowdeceased["REASON"];
				
				$appstatus = "<a href=\"#\" title=\"$deceasedreason\" style=\"color:Red;\">DECEASED</a>";
				
			}
			
			if (empty($appstatus))
			{
				include("veritas/include/crewsummary.inc");  //THIS IS FOR STATUS OF CREW
				
				if ($appstatus != "STANDBY" && $appstatus != "STANDBY (Lined-up; Not Departed yet)")
				{
					if ($appstatus == "EMBARKING")
					{
						$presentvessel = $lastvesselname;
						$embarkdate = $lastembdate;
						$eoc = $lastdisembdate;
												
						$rankfull = $assignedrankalias;

						$nextlineup = $vesselfull;
						$etd = $assignedetd;

					}
					
					if ($appstatus == "PROMOTED ONBOARD")
					{
						$presentvessel = $lastvesselname;
						$embarkdate = $lastembdate;
						$eoc = $lastdisembdate;
						
						$nextlineup = $vesselfull;
						$etd = $assignedetd;
					}
						
					if ($appstatus == "ONBOARD" || $appstatus == "ONBOARD (Extended)")
					{
						$presentvessel = $vesselfull;
						$embarkdate = $lastembdate;
						$eoc = $lastdisembdate;
						
						$nextlineup = "---";
						$etd = "---";
					}
	
				}
				elseif ($appstatus != "STANDBY")
					{
						$standby = 0;
						
						$presentvessel = $lastvesselname;
						$embarkdate = $lastembdate;
						$eoc = $lastdisembdate;
	
						$rankfull = $assignedrankalias;
						$nextlineup = $vesselfull;
						$etd = $assignedetd;
					}
					else 
					{
						$standby = 1;
						
						$qryavailability = mysql_query("SELECT AVAILABILITY
												FROM debriefinghdr d
												LEFT JOIN crewchange cc ON cc.CCID=d.CCID
												WHERE AVAILABILITY IS NOT NULL AND cc.APPLICANTNO=$embapplicantnohidden
												ORDER BY FILLUPDATE DESC
												LIMIT 1
											") or die(mysql_error());
											
						if (mysql_num_rows($qryavailability) > 0)
						{
							$rowavailability = mysql_fetch_array($qryavailability);
							$availability = date("dMY",strtotime($rowavailability["AVAILABILITY"]));
						}
						else
						{
							$availability = "---";
						}
						
						$presentvessel = $lastvesselname;
						$embarkdate = $lastembdate;
						$eoc = $lastdisembdate;
						
						$nextlineup = "---";
						$etd = "---";
						
						if ($lastdisembreasoncode == "PS" || $lastdisembreasoncode == "PI")
						{
							if ($lastdisembreasoncode == "PS")
							{
								$appstatus = "P&I(Illness)";
							}
							
							if ($lastdisembreasoncode == "PI")
							{
								$appstatus = "P&I(Injury)";
							}
						}
						
						//CHECK INJURY STATUS
						
						$qrychkstatus = mysql_query("SELECT ci.CCID,ci.DATEINJURED,ci.REASON,cic.CLINICNAME,cic.DATECHECKUP,
													cic.DIAGNOSIS,cic.RECOMMENDCODE,cr.RECOMMENDATION
													FROM crewinjury ci
													LEFT JOIN crewinjurychkup cic ON cic.CCID=ci.CCID
													LEFT JOIN clinicrecommend cr ON cr.RECOMMENDCODE=cic.RECOMMENDCODE
													WHERE ci.CCID=$lastccid
												") or die(mysql_error());
						
						if (mysql_num_rows($qrychkstatus) > 0)
						{
							$rowchkstatus = mysql_fetch_array($qrychkstatus);
							$xdateinjured = date("dMY",strtotime($rowchkstatus["DATEINJURED"]));
							$xreason = $rowchkstatus["REASON"];
							$xclinicname = $rowchkstatus["CLINICNAME"];
							$xdatecheckup = date("dMY",strtotime($rowchkstatus["DATECHECKUP"]));
							$xdiagnosis = $rowchkstatus["DIAGNOSIS"];
							$xrecommendcode = $rowchkstatus["RECOMMENDCODE"];
							$xrecommendation = $rowchkstatus["RECOMMENDATION"];
							
							if ($xrecommendcode == "FIT")
							{
								$appstatus = "STANDBY<br />($xrecommendation)";
							}
							else
							{
								$appstatus = "P&I Case";
							}
							
							$apptitle = "Date: $xdateinjured \nReason: $xreason\nCheckup Date: $xdatecheckup\nDiagnosis: $xdiagnosis\nClinic: $xclinicname\nRecommendation: $xrecommendation";
							
						}
						else
						{
							if ($lastdisembreasoncode == "PS" || $lastdisembreasoncode == "PI")
							{
								$apptitle = "No Details yet.";
							}
						}

					}
			}
				



$view201 .= "
			<div style=\"width:100%;height:80px;background-color:Silver;\">
			
				<div style=\"float:left;width:49%;padding-top:3px;padding-left:3px;\">
					<table class=\"listrow\">
						";
						
						if ($standby == 0)
							$view201 .= "
								<tr>
									<th>PRESENT VESSEL</th>
									<th>:</th>
									<td valign=\"top\">$presentvessel</td>
								</tr>
								";
						else 
							$view201 .= "
								<tr>
									<th>LAST VESSEL</th>
									<th>:</th>
									<td valign=\"top\">$presentvessel</td>
								</tr>
								";
				$view201 .= "
						<tr>
							<th>EMBARKED</th>
							<th>:</th>
							<td valign=\"top\">$embarkdate</td>
						</tr>
						<tr>
							<th>END OF CONTRACT</th>
							<th>:</th>
							<td valign=\"top\">$eoc</td>
						</tr>
						<tr>
							<th>AVAILABILITY</th>
							<th>:</th>
							<td valign=\"top\">$availability</td>
						</tr>
					";
				
	$view201 .= "
					</table>
				</div>
				
				<div style=\"float:right;width:49%;\">
					<table class=\"listrow\">
						<tr>
							<th>NEXT LINEUP</th>
							<th>:</th>
							<td valign=\"top\">$nextlineup</td>
						</tr>
						<tr>
							<th>RANK</th>
							<th>:</th>
							<td valign=\"top\">$rankfull</td>
						</tr>
						<tr>
							<th>ETD</th>
							<th>:</th>
							<td valign=\"top\">$etd</td>
						</tr>
						<tr>
							<th>CREW STATUS</th>
							<th>:</th>
							<td valign=\"top\" style=\"font-size:1.4em;font-weight:Bold;color:Red;\" title=\"$apptitle\">&nbsp;<u>$appstatus</u></td>
						</tr>
					</table>
				</div>
			</div>	
			
			<div style=\"width:100%;height:40px;overflow:auto;\">
			
				<span class=\"sectiontitle\">REMARKS</span>
				
				<table class=\"listrow\">
					<tr>
						<td>$personalremarks</td>
					</tr>
				</table>
			</div>
			
			<div style=\"width:100%;\">
				
				<!--
			
				<span class=\"sectiontitle\">FAMILY BACKGROUND</span>
				
				<div style=\"width:100%;overflow:auto;height:110px;\">
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
				
				-->
				";
	
				$stylebuttons = "style=\"font-size:0.6em;font-weight:Bold;color:Blue;height:20px;\"";
				
				$stylehead = "style=\"font-size:0.75em;font-weight:Bold;color:White;\"";
	
	$view201 .= "
				<div style=\"width:100%;overflow:auto;background-color:Black;\">

					<center>
						<input type=\"button\" value=\"201 Format\" $stylebuttons title=\"View Crew Data Sheet\" onclick=\"openWindow('rep201sheet.php?applicantno=$embapplicantnohidden&print=1', 'repdatasheet' ,900, 650);\" />
						<input type=\"button\" value=\"TNKC Format\" $stylebuttons title=\"View Compressed Data Sheet\" onclick=\"openWindow('repcrewdatasheet.php?applicantno=$embapplicantnohidden&print=1', 'repdatasheet' ,900, 650);\" />
						<input type=\"button\" value=\"DOC/LIC\" $stylebuttons title=\"View Documents/Licenses\" onclick=\"openWindow('repdoclic.php?applicantno=$embapplicantnohidden', 'repdoclic' ,900, 650);\" />
						<input type=\"button\" value=\"CERT\" $stylebuttons title=\"View Certificates\" onclick=\"openWindow('repcert.php?applicantno=$embapplicantnohidden', 'repcert' ,900, 650);\" />
						<input type=\"button\" value=\"EXP-VMC\" $stylebuttons title=\"View Experiences with Veritas\" onclick=\"openWindow('repexperience_vmc.php?applicantno=$embapplicantnohidden', 'repexperiences' ,900, 650);\" />
						<input type=\"button\" value=\"EXP-OUT\" $stylebuttons title=\"View Experiences with Other Companies\" onclick=\"openWindow('repexperience_out.php?applicantno=$embapplicantnohidden', 'repexperiences' ,900, 650);\" />
						<input type=\"button\" value=\"TRAININGS\" $stylebuttons title=\"View Trainings\" onclick=\"openWindow('reptrainings.php?applicantno=$embapplicantnohidden', 'reptrainings' ,900, 650);\" />	
					</center>

				</div>
			</div>	
	
		</div>
		<div id=\"documents201list\" style=\"width:100%;display:none;\">
			<div style=\"width:100%; overflow:auto;height:40px;\">
				<table style=\"width:100%;background-color:Black;font-size:0.9em;font-weight:Bold;color:Yellow;\">
					<tr>
						<td><span $stylehead>NAME:</span><br />$personalname</td>
						<td><span $stylehead>RANK:</span><br />$currentrankalias</td>
						<td><span $stylehead>REFERENCE:</span><br />$personalcrewcode&nbsp;/&nbsp;$embapplicantnohidden</td>
					</tr>
				</table>
			</div>
			
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
					$color = "";
						
					$doc1 = $rowdoclist["DOCUMENT"];
					$doc2 = $rowdoclist["DOCNO"];
					$doc3 = $rowdoclist["ALIAS2"];
					if ($rowdoclist["DATEISSUED"] != "")
						$doc4 = date($dateformat,strtotime($rowdoclist["DATEISSUED"]));
					else 
						$doc4 = "NO DATE";
					
					if ($rowdoclist["DATEEXPIRED"] != "")
						$doc5 = date($dateformat,strtotime($rowdoclist["DATEEXPIRED"]));
					else 
						$doc5 = "---";
						
					$doccode = $rowdoclist["DOCCODE"];
					$docexpiry = $rowdoclist["HASEXPIRY"];
					
					//FOR COLOR CODING - DOC EXPIRATION
					
					if ($docexpiry == 1)
					{
						if ($doc5 != "---")
						{
							$expdate = date('Y-m-d',strtotime($rowdoclist["DATEEXPIRED"]));
							$color = checkexpiry($expdate);
						}
						else 
							$color = "color:Red";  //Expiration Date is REQUIRED (pero NULL)
					}
					else 
						$color = "color:Black";  //Expiration Date is NOT REQUIRED
					
					//END OF COLOR CODING
					
					if (checkpath("$basedir/$embapplicantnohidden/D/$doccode.pdf"))
						$viewdisabled = 0;
					else 
						$viewdisabled = 1;
					
					//CHECK FOR OLD SCANNED DOCS
					$getlatestdoc="";
					$getlatestdoc1="";
					$files="";
					// $olddir="docimages/$embapplicantnohidden/D/OLD"; // local dumping
					$olddir="$basedir/$embapplicantnohidden/D/OLD"; // local dumping
					if ($handle = opendir($olddir)) 
					{
					    /* This is the correct way to loop over the directory. */
						$cnt=0;
						
					    while (false !== ($file = readdir($handle))) 
					    {
					    	if ($file != "." && $file != "..") 
					    	{
						        $file_parts=pathinfo($olddir.'/'.$file);
						        if($file_parts['extension']=='pdf')
						        {
						        	$getdocfile=explode("_",$file_parts['filename']);
						        	if($doccode==$getdocfile[0])
						        	{
						        		$files[] = $file;
						        		$cnt++;
						        	}
						        }
					    	}
					    }
					    closedir($handle);
						if($files)
						{
						    rsort($files);
						    $getlatestdoc1=$files[0];
						    $targetdoc = "old$personalcrewcode";
						   	$getlatestdoc="<span onclick=\"javascript:openWindow('$basedir/$embapplicantnohidden/D/OLD/$getlatestdoc1', '$targetdoc', 900, 600);\" ";
						   	$getlatestdoc.="style=\"cursor:pointer;color:blue;font-size:0.8em;\"><b><i>[OLD]</i></b></span>";
						}
					}
					
					
						
					$target = "doc$personalcrewcode" . "_$ctr";
					
$view201 .= "
					<tr class=\"$classtype\">
						<td>$doc1 $getlatestdoc</td>
						<td align=\"center\">$doc2</td>
						<td align=\"center\">$doc3</td>
						<td align=\"center\">$doc4</td>
						<td align=\"center\"><span style=\"font-weight:Bold;$color\">$doc5</span></td>
						<td align=\"center\">";
						if($viewdisabled==0)
						{	
$view201 .= "
							<img src=\"images/buttons/btn_v_def.gif\" 
										onmousedown=\"this.src='images/buttons/btn_v_click.gif';\"
										onmouseup=\"this.src='images/buttons/btn_v_def.gif';\"
										onmouseout=\"this.src='images/buttons/btn_v_def.gif';\"
										onclick=\"javascript:openWindow('$basedir/$embapplicantnohidden/D/$doccode.pdf', '$target', 900, 600);\"
										width=\"20px\">";  //#toolbar=0&scrollbar=0&navpanes=0
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
			
			<div style=\"width:100%; overflow:auto;height:200px;\">
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
					$color = "";
						
					$lic1 = $rowliclist["DOCUMENT"];
					$lic2 = $rowliclist["DOCNO"];
					$lic3 = $rowliclist["ALIAS2"];
					if ($rowliclist["DATEISSUED"] != "")
						$lic4 = date($dateformat,strtotime($rowliclist["DATEISSUED"]));
					else 
						$lic4 = "NO DATE";
					
					if ($rowliclist["DATEEXPIRED"] != "")
						$lic5 = date($dateformat,strtotime($rowliclist["DATEEXPIRED"]));
					else 
						$lic5 = "---";
					
					$liccode = $rowliclist["DOCCODE"];
					$licexpiry = $rowliclist["HASEXPIRY"];
					
					//FOR COLOR CODING - DOC EXPIRATION
					
					if ($docexpiry == 1)
					{
						if ($lic5 != "---")
						{
							$expdate = date('Y-m-d',strtotime($rowliclist["DATEEXPIRED"]));
							$color = checkexpiry($expdate);
						}
						else 
							$color = "color:Red";  //Expiration Date is REQUIRED (pero NULL)
					}
					else 
						$color = "color:Black";  //Expiration Date is NOT REQUIRED
					
					//END OF COLOR CODING
					
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
						<td align=\"center\"><span style=\"font-weight:Bold;$color\">$lic5</span></td>
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
			
			<div style=\"width:100%;overflow:auto;background-color:Black;\">
				<center>
				<input type=\"button\" value=\"PRINT DOC/LIC\" $stylebuttons onclick=\"openWindow('repdoclic.php?applicantno=$embapplicantnohidden', 'repdoclic' ,900, 650);\" />
				</center>
			</div>
			
		</div>
		
		<div id=\"experience201list\" style=\"width:100%;display:none;\">
			<div style=\"width:100%; overflow:auto;height:40px;\">
				<table style=\"width:100%;background-color:Black;font-size:0.9em;font-weight:Bold;color:Yellow;\">
					<tr>
						<td><span $stylehead>NAME:</span><br />$personalname</td>
						<td><span $stylehead>RANK:</span><br />$currentrankalias</td>
						<td><span $stylehead>REFERENCE:</span><br />$personalcrewcode&nbsp;/&nbsp;$embapplicantnohidden</td>
					</tr>
				</table>
			</div>
			<span class=\"sectiontitle\">EXPERIENCES - OUTSIDE</span>
			
			<div style=\"width:100%; overflow:auto;height:200px;\">
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
						$experience4 = date($dateformat,strtotime($rowexperiencelist["DATEEMB"]));
					else 
						$experience4 = "";
					
					if ($rowexperiencelist["DATEDISEMB"] != "")
						$experience5 = date($dateformat,strtotime($rowexperiencelist["DATEDISEMB"]));
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
			
			<div style=\"width:100%; overflow:auto;height:200px;\">
				<table class=\"listcol\" cellspacing=\"1\" cellpadding=\"0\">
					<tr>
						<th width=\"25%\">VESSEL</th>
						<th width=\"10%\">RANK</th>
						<th width=\"10%\">TYPE</th>
						<th width=\"10%\">EMBARK</th>
						<th width=\"10%\">DISEMBARK</th>
						<th width=\"10%\">ROUTE</th>
						<th width=\"15%\">REASON</th>
						<th width=\"5%\">MO</th>
						<th width=\"5%\">DB</th>
					</tr>
";
				$ctr = 0;
				$classtype = "odd";
				while ($rowexperiencevmclist = mysql_fetch_array($qryexperiencevmclist))
				{
					$experiencevmc1 = $rowexperiencevmclist["VESSEL"];
					$expccid = $rowexperiencevmclist["CCID"];
					$experiencevmc2 = $rowexperiencevmclist["ALIAS2"];
					$experiencevmc3 = $rowexperiencevmclist["VESSELTYPECODE"];
					
					$dateembx = $rowexperiencevmclist["DATEEMB"];
					
					if (!empty($rowexperiencevmclist["DATEEMB"]))
						$experiencevmc4 = date($dateformat,strtotime($rowexperiencevmclist["DATEEMB"]));
					else 
						$experiencevmc4 = "";
					
					$datedisembx = $rowexperiencevmclist["DATEDISEMB"];	
					
					if (!empty($rowexperiencevmclist["DATEDISEMB"]))
						$experiencevmc5 = date($dateformat,strtotime($rowexperiencevmclist["DATEDISEMB"]));
					else 
						$experiencevmc5 = "";
						
//					if ($rowexperiencevmclist["DATECHANGEDISEMB"] != "")
//						$experiencevmc5 = date($dateformat,strtotime($rowexperiencevmclist["DATECHANGEDISEMB"]));
						
					$disembcode = $rowexperiencevmclist["DISEMBREASONCODE"];
					
//					$titledepmnldate = ; 
//					$titlearrmnldate = "";
					
					$depmnlpromote = $rowexperiencevmclist["DEPMNLPROMOTE"];
					$arrmnlpromote = $rowexperiencevmclist["ARRMNLPROMOTE"];
					$checkpromote = $rowexperiencevmclist["CHKPROMOTE"];
					
					$experiencevmc6 = $rowexperiencevmclist["TRADEROUTECODE"];
					$experiencevmc7 = $rowexperiencevmclist["REASON"];
					
					if (!empty($rowexperiencevmclist["DEPMNLDATE"]))
						$titledepmnldate = "Depart Manila:" . date($dateformat,strtotime($rowexperiencevmclist["DEPMNLDATE"]));
					else 
						$titledepmnldate = "Depart Manila:(No Depart Manila!)";
						
					if (!empty($rowexperiencevmclist["ARRMNLDATE"]))	
						$titlearrmnldate = "Arrive Manila: " . date($dateformat,strtotime($rowexperiencevmclist["ARRMNLDATE"]));
					else 
						$titlearrmnldate = "Arrive Manila: ";
					
					if (strtotime($experiencevmc5) >= strtotime($datenow) || (empty($rowexperiencevmclist["ARRMNLDATE"])))
					{
						$experiencevmc5 = "Present";
					}
						
					if ($disembcode == "PR")
					{
						$experiencevmc5 = "---";
						
						if (!empty($arrmnlpromote))
							$titlearrmnldate .= date($dateformat,strtotime($arrmnlpromote));
						else 
							$titlearrmnldate .= "No Arrive Manila (Promoted)";
					}
					
//					//IF PROMOTED
//					$alias2promote = $rowexperiencevmclist["ALIAS2PROMOTE"];
//					$dateembpromote = date($dateformat,strtotime($rowexperiencevmclist["DATEEMBPROMOTE"]));
//					$datedisembpromote = date($dateformat,strtotime($rowexperiencevmclist["DATEDISEMBPROMOTE"]));
//					if(!empty($alias2promote))
//					{
//						$titlerem="($experiencevmc2 until $experiencevmc5 only)";
//						$experiencevmc7="Promoted ".$titlerem;
//						$experiencevmc2=$alias2promote;
//						$experiencevmc5=$datedisembpromote;
//					}
//					else 
//						$titlerem="";
//					//CHECK IF CCID IS PROMOTED
//					if(empty($rowexperiencevmclist["CHKPROMOTE"]))
//					{
					if (!empty($rowexperiencevmclist["DEPMNLDATE"]) || !empty($checkpromote))
					{
						
					if ($experiencevmc5 != "Present")
						$months = round(((strtotime($datedisembx) - strtotime($dateembx)) / 86400) / 30);
					else 
						$months = "--";
						
					$qrycheckdebriefing = mysql_query("SELECT CCID,MADEBY,MADEDATE FROM debriefinghdr WHERE CCID=$expccid") or die(mysql_error());
						
$view201 .= "
					<tr class=\"$classtype\">
			";

					if (mysql_num_rows($qrycheckdebriefing) > 0)
					{
						$view201 .= "
								<td>
									<a href=\"#\" title=\"Click to see Debriefing comments.\" style=\"color:Magenta;font-weight:Bold;\"
									onclick=\"openWindow('debriefingcomments.php?ccid=$expccid', 'Debriefing_Comments', 700, 500);\">
									$experiencevmc1
									</a>
								</td>";
					}
					else 
					{
						$view201 .= "<td>$experiencevmc1</td>";
					}
					
$view201 .= "
						<td align=\"center\">$experiencevmc2</td>
						<td align=\"center\">$experiencevmc3</td>
						<td align=\"center\" title=\"$titledepmnldate\">$experiencevmc4</td>
						<td align=\"center\" title=\"$titlearrmnldate\">$experiencevmc5</td>
						<td align=\"center\">$experiencevmc6</td>
						<td title=\"$titlerem\" align=\"center\">$disembcode</td>
						<td align=\"center\">$months</td>
						";
					
					

					if (mysql_num_rows($qrycheckdebriefing) > 0)
					{
$view201 .= "
						<td title=\"Click to see Debriefing\" align=\"center\">
							<img src=\"images/buttons/btn_v_def.gif\" 
										onmousedown=\"this.src='images/buttons/btn_v_click.gif';\"
										onmouseup=\"this.src='images/buttons/btn_v_def.gif';\"
										onmouseout=\"this.src='images/buttons/btn_v_def.gif';\"
										onclick=\"openWindow('debriefingcomments.php?ccid=$expccid', 'Debriefing_Comments', 700, 500);\"
										width=\"20px\">
						</td>
";
					}
					else   //javascript:openWindow('debriefingformshow.php?applicantno=$embapplicantnohidden&load=1', 'debriefing', 900, 600);
					{
$view201 .= "			<td align=\"center\">---</td>";
						
					}
$view201 .= "
					</tr>";			
					$ctr++;
					if($classtype=="even") { $classtype="odd";} else { $classtype="even"; }		
					}
				}

$view201 .= "				
				</table>
			</div>
			
			<div style=\"width:100%;overflow:auto;background-color:Black;\">
				<center>
				<input type=\"button\" value=\"PRINT EXP-VERITAS\" $stylebuttons onclick=\"openWindow('repexperience_vmc.php?applicantno=$embapplicantnohidden', 'repexperience_vmc' ,900, 650);\" />
				<input type=\"button\" value=\"PRINT EXP-OUTSIDE\" $stylebuttons onclick=\"openWindow('repexperience_out.php?applicantno=$embapplicantnohidden', 'repexperiences_out' ,900, 650);\" />
				</center>
			</div>			
		</div>
		
		<div id=\"performance201list\" style=\"width:100%;display:none;\">
			<div style=\"width:100%; overflow:auto;height:40px;\">
				<table style=\"width:100%;background-color:Black;font-size:0.9em;font-weight:Bold;color:Yellow;\">
					<tr>
						<td><span $stylehead>NAME:</span><br />$personalname</td>
						<td><span $stylehead>RANK:</span><br />$currentrankalias</td>
						<td><span $stylehead>REFERENCE:</span><br />$personalcrewcode&nbsp;/&nbsp;$embapplicantnohidden</td>
					</tr>
				</table>
			</div>
		
			<span class=\"sectiontitle\">PERFORMANCE (CREW EVALUATION REPORT)</span>
			
			<div style=\"width:100%; overflow:auto;height:400px;\">
";

			$view201 .= "				
			<table class=\"listcol\" cellspacing=\"1\" cellpadding=\"0\">
				<tr>
					<th width=\"30%\">VESSEL</th>
					<th width=\"20%\">EMBARK</th>
					<th width=\"20%\">DISEMBARK</th>
					<th width=\"10%\">VIEW</th>
					<th width=\"20%\">PDF</th>
				</tr>";
			
			$getcutoff="2008-01-01";//for OLD DATEDISEMB W/O ARRIVE MANILA
			while ($rowperformancelist=mysql_fetch_array($qryperformancelist))
			{

				$ccid = $rowperformancelist["CCID"];		
				$performance1 = $rowperformancelist["VESSEL"];
				$datedisemb1 = $rowperformancelist["DATEDISEMB"];
				$arrmnldate1 = $rowperformancelist["ARRMNLDATE"];
				
				$depmnlpromote = $rowperformancelist["DEPMNLPROMOTE"];
				$arrmnlpromote = $rowperformancelist["ARRMNLPROMOTE"];
				$checkpromote = $rowperformancelist["CHKPROMOTE"];
				$disembreasoncode = $rowperformancelist["DISEMBREASONCODE"];
				$performance3 = "";
				
				if (!empty($rowperformancelist["DATEEMB"]))
					$performance2 = date($dateformat,strtotime($rowperformancelist["DATEEMB"]));
				else 
					$performance2 = "";
				
				if(strtotime($datedisemb1)<strtotime($getcutoff))
				{
					if (!empty($datedisemb1))
						$performance3 = date($dateformat,strtotime($datedisemb1))."*";
					else 
						$performance3 = "";
				}

				if (strtotime($datedisemb1) >= strtotime($datenow) || (empty($rowperformancelist["ARRMNLDATE"])) && $disembreasoncode != "PR")
				{
					$performance3 = "Present";
				}
				elseif ($disembreasoncode == "PR")
					{
						$performance3 = date($dateformat,strtotime($datedisemb1)) . "<br /> <span style=\"font-size:0.8em;color:Red;\">(Promoted)</span>";
						
						if (!empty($arrmnlpromote))
							$titlearrmnldate .= date($dateformat,strtotime($arrmnlpromote));
						else 
							$titlearrmnldate .= "No Arrive Manila (Promoted)";
					}
					else
					{
						$performance3 = date($dateformat,strtotime($datedisemb1));
					}

				// if ($disembreasoncode == "PR")
				// {
					// $performance3 = "Promoted";
					// $performance3 = date($dateformat,strtotime($datedisemb1)) . "<br /> (Promoted)";
					
					// if (!empty($arrmnlpromote))
						// $titlearrmnldate .= date($dateformat,strtotime($arrmnlpromote));
					// else 
						// $titlearrmnldate .= "No Arrive Manila (Promoted)";
				// }
				// else
				// {
					// $performance3 = date($dateformat,strtotime($datedisemb1));
				// }
				
				
				
				
				$qrycheckeval = mysql_query("SELECT EVALNO FROM crewevalhdr WHERE CCID=$ccid") or die(mysql_error());
				
				if (mysql_num_rows($qrycheckeval) > 0)
					$viewdisabled = 0;
				else 
					$viewdisabled = 1;
					
				$view201 .= "
				<tr class=\"$classtype\">
					<td>$performance1</td>
					<td align=\"center\">$performance2</td>
					<td align=\"center\">$performance3</td>
					<td align=\"center\">&nbsp;";
					if($viewdisabled==0)
					{	
				$view201 .= "
						<input type=\"button\" value=\"CER\" style=\"font-size:0.8em;font-weight:Bold;\" title=\"Goto CER screen...\"
							onclick=\"javascript:openWindow('crewevaluationshow.php?ccid=$ccid', 'CER$ccid', 0, 0);\" />
			";
					}
				$view201 .= "
					</td>
					<td align=\"center\">&nbsp;
			";
				
				$qrygetevalno = mysql_query("SELECT ceh.EVALNO
												FROM crewevalhdr ceh
												WHERE ceh.CCID=$ccid
												GROUP BY EVALNO
												ORDER BY ceh.EVALNO") or die(mysql_error());
				
				if (mysql_num_rows($qrygetevalno) > 0)
				{
					while ($rowgetevalno = mysql_fetch_array($qrygetevalno))
					{
						$evno = $rowgetevalno["EVALNO"];
						
						$path = "$basedir/$embapplicantnohidden/CER/$ccid" . "_" . $evno . ".pdf";
						
						if (checkpath($path))
						{
							$view201 .= "<input type=\"button\" value=\"$evno\" onclick=\"javascript:openWindow('$path', '$target', 900, 600);\" />&nbsp;&nbsp;";
							
						}
						else 
							$view201 .= "&nbsp;&nbsp;";
					}
				}
				else //if there's no crewevalhdr entry BUT there's PDF file uploaded.
				{
					$path = "$basedir/$embapplicantnohidden/CER";
					
					//SEARCH FOR FILES IN 'docimages/<applicantno>/CER' FOLDER
					if ($handle = opendir($path)) 
					{
						$cnt=1;
						$getlist="";
					    while (false !== ($file = readdir($handle))) 
					    {
					    	if ($file != "." && $file != "..") 
					    	{
						        $file_parts=pathinfo($path.'/'.$file);
						        if($file_parts['extension']=='pdf')
						        {
						        	$filename = $file_parts['filename'];
						        	$basename = $file_parts['basename'];
						        	$dirname = $file_parts['dirname'];
						        	
						        	$evnotmp = explode("_",$filename);
						        	$ccid2 = $evnotmp[0];
						        	$evno2 = $evnotmp[1];
						        	
						        	if ($ccid2 == $ccid)
						        	{
						        		$pathpdf = $dirname . "/" . $basename;
						        		$view201 .= "<input type=\"button\" value=\"$evno2\" onclick=\"javascript:openWindow('$pathpdf', '$target', 900, 600);\" />&nbsp;&nbsp;";
						        	}
						        }
					    		
					    	}
					    }
					    $getlist.="<tr><td colspan=\"2\" style=\"font-weight:bold;\">Count: $cnt</td></tr>";
					}
					closedir($handle);
					
				}
				
				$view201 .= "
					</td>
				</tr>
				";
					if($classtype=="even") { $classtype="odd";} else { $classtype="even"; }	
				}
$view201 .= "		</table>";
$view201 .= "
			</div>
		</div>
		
		<div id=\"training201list\" style=\"width:100%;display:none;\">
			<div style=\"width:100%; overflow:auto;height:40px;\">
				<table style=\"width:100%;background-color:Black;font-size:0.9em;font-weight:Bold;color:Yellow;\">
					<tr>
						<td><span $stylehead>NAME:</span><br />$personalname</td>
						<td><span $stylehead>RANK:</span><br />$currentrankalias</td>
						<td><span $stylehead>REFERENCE:</span><br />$personalcrewcode&nbsp;/&nbsp;$embapplicantnohidden</td>
					</tr>
				</table>
			</div>
			<span class=\"sectiontitle\">TRAINING CERTIFICATES</span>
			
			<div style=\"width:100%; overflow:auto;height:400px;\">
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
						$cert4 = date($dateformat,strtotime($rowcertlist["DATEISSUED"]));
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
			
			<div style=\"width:100%;overflow:auto;background-color:Black;\">
				<center>
				<input type=\"button\" value=\"PRINT CERTIFICATES\" $stylebuttons onclick=\"openWindow('repcert.php?applicantno=$embapplicantnohidden', 'repcert' ,900, 650);\" />
				<input type=\"button\" value=\"PRINT TRAININGS\" $stylebuttons onclick=\"openWindow('reptrainings.php?applicantno=$embapplicantnohidden', 'reptrainings' ,900, 650);\" />	
				</center>
			</div>
		</div>
		
		<div id=\"medical201list\" style=\"width:100%;display:none;\">
			<div style=\"width:100%; overflow:auto;height:40px;\">
				<table style=\"width:100%;background-color:Black;font-size:0.9em;font-weight:Bold;color:Yellow;\">
					<tr>
						<td><span $stylehead>NAME:</span><br />$personalname</td>
						<td><span $stylehead>RANK:</span><br />$currentrankalias</td>
						<td><span $stylehead>REFERENCE:</span><br />$personalcrewcode&nbsp;/&nbsp;$embapplicantnohidden</td>
					</tr>
				</table>
			</div>
			<span class=\"sectiontitle\">MEDICAL BACKGROUND</span>
			
			<div style=\"width:100%; overflow:auto;height:400px;\">
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
				$medicaltotal = mysql_num_rows($qrymedicallist) - 1; //for the filename
				$medicalcnt = $medicaltotal;
				while ($rowmedicallist = mysql_fetch_array($qrymedicallist))
				{

						
					$medical1 = $rowmedicallist["CLINIC"];
					if ($rowmedicallist["DATECHECKUP"] != "")
						$medical2 = date($dateformat,strtotime($rowmedicallist["DATECHECKUP"]));
					else 
						$medical2 = "";
					
					$medical3 = $rowmedicallist["DIAGNOSIS"];
					$medical4 = $rowmedicallist["RECOMMENDATION"];
					$medical5 = $rowmedicallist["REMARKS"];
					$medcode = $rowmedicallist["DOCCODE"];
					
					if($medicalcnt<10)
						$add0="0";
					else 
						$add0="";
					//SEARCH FILE FOR PEME (P7)
					if ($medicalcnt != $medicaltotal)
						// $dirfilename = $basedir . $applicantno . "/D/OLD/P7_0" . $medicalcnt . ".pdf";
						$dirfilename = "$basedir/$embapplicantnohidden/D/OLD/$medcode" . "_" . $add0 . $medicalcnt . ".pdf";
					else
						// $dirfilename = $basedir . $applicantno . "/D/P7.pdf";
						$dirfilename = "$basedir/$embapplicantnohidden/D/$medcode.pdf";
					
					if (checkpath($dirfilename))
						$medfile = "javascript:openWindow('$dirfilename', 'med$medicalcnt' ,900, 600);";
					else 
						$medfile = "";	
					
					//SEARCH FILE FOR PEME (TR)
					if ($medicalcnt != $medicaltotal)
						// $dirfilename = $basedir . $applicantno . "/D/OLD/P7_0" . $medicalcnt . ".pdf";
						$dirfilenameTR = "$basedir/$embapplicantnohidden/D/OLD/TR" . "_" . $add0 . $medicalcnt . ".pdf";
					else
						// $dirfilename = $basedir . $applicantno . "/D/P7.pdf";
						$dirfilenameTR = "$basedir/$embapplicantnohidden/D/TR.pdf";
					
					if (checkpath($dirfilenameTR))
						$medfileTR = "javascript:openWindow('$dirfilenameTR', 'medTR$medicalcnt' ,900, 600);";
					else 
						$medfileTR = "";	
						
					// if (checkpath("$basedir/$embapplicantnohidden/D/$medcode.pdf"))
						// $viewdisabled = 0;
					// else 
						// $viewdisabled = 1;					
					
$view201 .= "
					<tr class=\"$classtype\">
						<td>$medical1</td>
						<td align=\"center\">$medical2</td>
						<td align=\"center\">$medical3</td>
						<td align=\"center\">$medical4</td>
						<td align=\"center\">$medical5</td>
						<td align=\"center\">";
						if(!empty($medfile))
						{	
	$view201 .= "
							<img src=\"images/buttons/btn_v_def.gif\" title=\"PEME\"
										onmousedown=\"this.src='images/buttons/btn_v_click.gif';\"
										onmouseup=\"this.src='images/buttons/btn_v_def.gif';\"
										onmouseout=\"this.src='images/buttons/btn_v_def.gif';\"
										onclick=\"$medfile\"
										width=\"20px\">";
						}
						if(!empty($medfileTR))
						{	
	$view201 .= "
							<img src=\"images/buttons/btn_v_def.gif\" title=\"Medical Record\"
										onmousedown=\"this.src='images/buttons/btn_v_click.gif';\"
										onmouseup=\"this.src='images/buttons/btn_v_def.gif';\"
										onmouseout=\"this.src='images/buttons/btn_v_def.gif';\"
										onclick=\"$medfileTR\"
										width=\"20px\">";
						}
	$view201 .= "
						</td>
					</tr>
";					
					$ctr++;
					if($classtype=="even") { $classtype="odd";} else { $classtype="even"; }
					
					$medicalcnt--;
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
		
		if ($shownobatch == 1)
			$wherebatch = "WHERE x.BATCHNO <> 999";
		else
			$wherebatch = "";
		
		include("veritas/include/qrylistplan.inc");
		$vesselselect = "
		<table style=\"font-size:0.8em;font-weight:Bold;border-top:1px solid black;border-right:1px solid black;table-layout:fixed;\" 
				cellspacing=\"0\" cellpadding=\"2\">
			<tr style=\"\">\n
				<td style=\"width:25px;\">&nbsp;</td>\n
				<td style=\"width:50px;\">&nbsp;</td>\n
				<td style=\"width:180px;\">&nbsp;</td>\n
				<td style=\"width:80px;\">&nbsp;</td>\n
				<td style=\"width:80px;\">&nbsp;</td>\n
				<td style=\"width:80px;\">&nbsp;</td>\n
				<td style=\"width:80px;\">&nbsp;</td>\n
				<td style=\"width:50px;\">&nbsp;</td>\n
				<td style=\"width:180px;\">&nbsp;</td>\n
				<td style=\"width:80px;\">&nbsp;</td>\n
				<td style=\"width:80px;\">&nbsp;</td>\n
				<td style=\"width:80px;\">&nbsp;</td>\n 
			</tr>";
				$getdiscrepancy="
				<div style=\"width:100%;height:57px;overflow:hidden;background-color:White;\">
					<table width=\"100%\" style=\"font-size:0.8em;font-weight:Bold;border:1px solid black;\">
						<tr>
							<td style=\"width:10%;background-color:Lime;\"></td>
							<td><i>6-7 Months</i></td>
							<td style=\"width:10%;background-color:Yellow;\"></td>
							<td><i>7-8 Months</i></td>
							<td style=\"width:10%;background-color:Red;\"></td>
							<td><i>Above 8 Months</i></td>
						</tr>
					</table>
					<span style=\"background-color:#DCDCDC;border-left:1px solid black;border-right:1px solid black;font-weight:bold;font-size:0.9em;width:100%;text-align:center;\">
						DISCREPANCY
					</span>\n
					<table id=\"discrepancyheader\" style=\"table-layout:fixed;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
						<tr height=\"17px\">\n
							<td style=\"width:200px;$styleheader font-size:0.7em;background-color:Black;color:White;\">Crew</td>\n
							<td style=\"width:40px;$styleheader font-size:0.7em;background-color:Black;color:White;\">Rank</td>\n
							<td style=\"width:60px;$styleheader font-size:0.7em;background-color:Black;color:White;\">Batch</td>\n
							<td style=\"$styleheader font-size:0.7em;background-color:Black;color:White;\">Remarks</td>\n
						</tr>
					</table>
				</div>
				<div style=\"width:100%;height:118px;overflow:auto;\">
					<table id=\"discrepancydetails\" style=\"border-top:1px solid black;border-right:1px solid black;table-layout:fixed;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"2\">
						<tr style=\"display:none;\">\n
							<td style=\"width:200px;\">&nbsp;</td>\n
							<td style=\"width:40px;\">&nbsp;</td>\n
							<td style=\"width:60px;\">&nbsp;</td>\n
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
			//check if crew is foreign
			$crewforeign=$rowlistplan["CREWFOREIGN"];
			//ADDED BY GPA - 2008-01-15
			if (!empty($rowlistplan["EMBDEPMNLDATE"]))
				$embdepmnldate=date("m/d/Y",strtotime($rowlistplan["EMBDEPMNLDATE"]));
			else 
				$embdepmnldate="";
				
//			$dateembpromote1 = $rowlistplan["DATEEMBPROMOTE"];
			//END GPA
			
			$datedisemb=$rowlistplan["DATEDISEMB"];
			
			if (!empty($rowlistplan["DEPMNLDATE"]))
				$depmnldate = date("Y-m-d",strtotime($rowlistplan["DEPMNLDATE"]));
			else 
				$depmnldate = "";
			
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
						if($crewforeign==1)
							$batchnoshow="FOREIGN";
//						if (empty($embdepmnldate))	
//						{
							$stylehdr = "font-size:0.85em;color:Black;text-align:center;border-right:1px solid Black;border-bottom:1px solid Black;";
							
							$vesselselect .= "
							<tr>\n
								<td colspan=\"12\" style=\"background:black;color:Red;font-size:1.2em;text-align:center;\">$batchnoshow BATCH</td>\n
							</tr>\n
							<tr>\n
								<td colspan=\"7\" style=\"font-size:0.9em;background-color:#DCDCDC;text-align:center;border:1px solid Black;\">DISEMBARKING CREW</td>\n
								<td colspan=\"5\" style=\"font-size:0.9em;background-color:#DCDCDC;text-align:center;border:1px solid Black;\">EMBARKING CREW</td>\n
							</tr>\n
							<tr>\n
								<td style=\"$stylehdr\">NO</td>\n
								<td style=\"$stylehdr\">RANK</td>\n
								<td style=\"$stylehdr\">NAME</td>\n
								<td style=\"$stylehdr\">EMBARK</td>\n
								<td style=\"$stylehdr\">DISEMBARK</td>\n
								<td style=\"$stylehdr\">E.O.C.</td>\n
								<td style=\"$stylehdr\">EST. DATE</td>\n
								<td style=\"$stylehdr\">RANK</td>\n
								<td style=\"$stylehdr\">NAME</td>\n
								<td style=\"$stylehdr\">EX-VSL</td>\n	
								<td style=\"$stylehdr\">DATE</td>\n
								<td style=\"$stylehdr\">ACTION</td>\n
							</tr>";
//						}
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
				$rankaliasx=$rowlistplan["ALIAS1"];
				$embrankcode=$rowlistplan["EMBRANKCODE"];
				$alias2=$rowlistplan["ALIAS2"];
				$embalias2=$rowlistplan["EMBALIAS2"];
				$name=$rowlistplan["NAME"];
				$dateemb=$rowlistplan["DATEEMB"];
				$datedisemborig=$rowlistplan["DATEDISEMBORIG"];
				$datechangedisemb=$rowlistplan["DATECHANGEDISEMB"];
				
				if (!empty($rowlistplan["ESTIMATEDATE"]))
					$estimatedateshow=date("m/d/Y",strtotime($rowlistplan["ESTIMATEDATE"]));
				else
					$estimatedateshow="";
				
				$datechangedisembshow=date("m/d/Y",strtotime($datechangedisemb));
				$dateembshow=date("d-M-y",strtotime($dateemb));
				$dateembshow1=date("m/d/Y",strtotime($dateemb));
				$datedisembshow=date("d-M-y",strtotime($datedisemb));
				$datedisembshow2=date("d M Y",strtotime($datedisemb));
				$datedisemborigshow=date("m/d/Y",strtotime($datedisemborig));
				$datedisemborigshow2=date("d-M-y",strtotime($datedisemborig));
				$datedisemborigshow3=date("dMY",strtotime($datedisemborig));
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
				$disembreason=$rowlistplan["REASON"];
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
						if(!empty($chkdepart)) //HAS ALREADY DEPARTED MANILA
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
//				else //FOR id="chkdiscrepancy" area in crewchangplan.php
//				{
					$styledef = "font-size:0.8em;font-weight:Bold;border-bottom:1px dashed Gray;";
					
						$chkdiscrepancy="";
						
						$strdatedisemb=strtotime($datedisemb);
						
						$strdatedisembshow=date("dMY",strtotime($datedisemb));
						$strdatechangedisembshow=date("dMY",strtotime($datechangedisemb));
						$strdisemborig=strtotime($datedisemborig);
						$strdisemborigshow=date("dMY",strtotime($datedisemborig));
						
						$strdatenow=strtotime($datenow);
						$strdateemb=strtotime($dateemb);
						$strdateembshow=date("dMY",strtotime($dateemb));
	
						$months_onboard = round(($strdatenow-$strdateemb)/2592000);
						
						$styledef2 = "background-color:Black;color:Orange;font-size:0.9em;";
							
						switch ($datedisembchanged)
						{
							case "Extended"	:
									$exremarks = "<br /><span style=\"$styledef2\">
									<i>EXTENDED:&nbsp;$strdatechangedisembshow</i>&nbsp;&nbsp;</span>";
								break;
							case "Shortened":
									$exremarks = "<br /><span style=\"$styledef2\">
									<i>SHORTENED:&nbsp;$strdatechangedisembshow</i>&nbsp;&nbsp;</span>";
								break;
							case "0":
									$exremarks = "";
								break;
						}

						//Embark Date: $strdateembshow; 
						if ($months_onboard >= 6 && $months_onboard < 7)
							$styledef .= "background-color:Lime;color:Black;";
						if ($months_onboard >=7 && $months_onboard < 8)
							$styledef .= "background-color:Yellow;color:Black;";
						if ($months_onboard >=8)
							$styledef .= "background-color:Red;color:White;";

						$chkdiscrepancy="Original Disembark : $datedisemborigshow3 <br /><span style=\"$styledef2\">
									<i>&nbsp;&nbsp;Onboard = $months_onboard&nbsp;month(s)</i>&nbsp;&nbsp;</span>";
									
						if ($months_onboard >= 6)
						{
							$getdiscrepancy.="
							<tr>
								<td title=\"$months_onboard\" style=\"$styledef border-right:1px solid Black;\">&nbsp;$name</td>\n
								<td style=\"$styledef border-right:1px solid Black;\" align=\"center\">&nbsp;$alias2</td>\n
								<td style=\"$styledef border-right:1px solid Black;\" align=\"center\">&nbsp;$batchnoshow</td>\n
								<td style=\"$styledef\">&nbsp;$chkdiscrepancy $exremarks</td>\n
							</tr>
							";
						}
						
//				}

				//ADDED BY GPA -- TO CHECK IF CURRENT CCID IS PREVIOUSLY PROMOTED.
				$qrycheckpromote = mysql_query("SELECT CCID FROM crewpromotionrelation WHERE CCIDPROMOTE=$ccid") or die(mysql_error());
				
				if (mysql_num_rows($qrycheckpromote) > 0)
					$ccidpromoted = 1;
				else 
					$ccidpromoted = 0;
				//END GPA
				
				// POSITION -- CREW ONBOARD BASED ON EMBARK DATE/HAS ALREADY DEPARTED MANILA/PREVIOUSLY PROMOTED ONBOARD
				if(date("Y-m-d",strtotime($dateemb))<=$datenow && date("Y-m-d",strtotime($datedisemb))>=$datenow && (!empty($depmnldate) || $ccidpromoted==1))
				{
					$position++;
					$getposition=$position;
				}
				else 
					$getposition="";
					
				//SHADING -- CREW ONBOARD BASED ON EMBARK DATE ONLY
				if (date("Y-m-d",strtotime($dateemb))<=$datenow && date("Y-m-d",strtotime($datedisemb))>=$datenow)
					$styleshade = "background-color:#D2D0FB;";
				else 
					$styleshade = "";
					
					
				// GET LAST VESSEL & DATEDISEMB
				if(!empty($embapplicantno))
				{
					$qrylastvessel=mysql_query("
						SELECT VESSEL,DATEDISEMB,ALIAS1 FROM
						(SELECT VESSEL,IF(c.DATECHANGEDISEMB IS NULL,c.DATEDISEMB,c.DATECHANGEDISEMB) AS DATEDISEMB,ALIAS1
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
				
				
//							if('$disembreasoncode1'!=' ')
//								{disembreasoncode.value='$disembreasoncode1';}
				
				if(!empty($getposition) || $batchno==0)
				{
					$putonclickdisemb="onclick=\"if(chkbtnloading()==1)
						{
							document.getElementById('crewchangedate').style.display='block';
							switchajax(1);
							document.getElementById('existdate').value='$datedisemborigshow';
							document.getElementById('cname').value='$name';
							document.getElementById('existdateemb').value='$dateembshow1';
							document.getElementById('changeremarks').value='$datedisembchanged';
							document.getElementById('changeto').value='$datedisembshow2';

								document.getElementById('changereason').value='$disembreason';
								document.getElementById('changeby').value='$datechangeby';
								document.getElementById('changedate').value='$datechangedate';
								document.getElementById('estdate').value='$estimatedateshow';
								
							embccidhidden.value='$ccid';

							newdate.focus();
						}\"";
				}
				else 
				{
					$putonclickdisemb="onclick=\"if(chkbtnloading()==1){alert('Not On-board yet!');}\"";
					
				}
				// if(!empty($getposition))
				// {
					$putonclickalias2="onclick=\"if(chkbtnloading()==1){document.getElementById('changebatch').style.display='block';switchajax(1);crewselect.value='$name';currentbatch.value='$batchnoshow';batchselect.value='$batchno';ccidhidden.value='$ccid';}\"";
				// }
				// else 
				// {
					// $putonclickalias2="onclick=\"alert('Crew is NOT On-board!');\"";
					
				// }
				
//				if (empty($embdepmnldate))
//				{
					$styledtls = "border-bottom:1px dashed Gray;border-right:1px solid Gray;";
					
					if (empty($depmnldate) && $ccidpromoted==0)
					{
						$styledepart = "font-weight:Bold;color:Red;";
						$allowdelete = 1;
					}
					else 
					{
						$styledepart = "";
						$allowdelete = 0;
					}
					
					if ($allowdelete == 1)
						$showdelete = "<a href=\"#\" style=\"font-weight:Bold;color:Magenta;\" title=\"Delete Crew and Crew Relationship...\"
										onclick=\"if (confirm('This will DELETE this entry, together with ALL its Crew Change Relation. Confirm?')) {ccidhidden.value='$ccid';chkloading('deletecrew',0);}\">
										X</a>";
					else 
						$showdelete = "";
					
						
					$vesselselect .= "
					<tr $mouseovereffect>\n
						<td style=\"text-align:center;$styledtls $styleshade\">&nbsp;$getposition $showdelete</td>\n
						<td style=\"text-align:center;$styledtls $styleshade\" title=\"$rankcode\">
							<input type=\"button\" value=\"$alias2\" title=\"Click to change batch no...\"
								$putonclickalias2
								style=\"border:0;background:inherit;cursor:pointer;font-weight:Bold;color:Navy;\">\n
						</td>\n
						<td style=\"$styledtls $styleshade\">
							<span style=\"cursor:pointer;$styledepart\" title=\"Applicant No: $applicantno \nClick to view 201 file...\" 
								onclick=\"crewonboard.value='$name';embapplicantnohidden.value='$applicantno';chkloading('viewonboard201',10);\">
								$name
							</span>
						</td>\n
						<td style=\"text-align:center;$styledtls $styleshade\">&nbsp;$dateembshow</td>\n
						<td style=\"text-align:center;$styledtls $styleshade\">&nbsp;$datedisemborigshow2</td>\n
						<td style=\"text-align:center;$styledtls $styleshade\" title=\"$datedisembtitle1\">&nbsp;
							<span style=\"cursor:pointer;\"
								$putonclickdisemb>$datedisembshow$datedisembtag</span>
						</td>\n
						<td style=\"text-align:center;$styledtls $styleshade\" title=\"$estimatedateshow\">&nbsp;$estimatedateshow</td>\n
						";
						if(empty($embrankcode))
						{
							if(empty($ccpno)) //check if approved ccpno does not exist
							{
								$vesselselect .= "
								<td colspan=\"5\" style=\"text-align:center;$styledtls\">\n
									<input id=\"noprint\" type=\"button\" value=\"-- ADD CREW --\" title=\"Click to add crew...\"
										onclick=\"if(chkbtnloading()==1){addeditcrew('$ccid','$rankcode','$rankaliasx','$name','$datedisemb','','','','$dateembfuture','$datedisembfuture','$alias2','','','')}\"
										style=\"border:0;background:inherit;cursor:pointer;color:Blue;font-style:italic;font-weight:Bold;\">\n
								</td>\n";
							}
							else 
							{
								$vesselselect .= "
								<td colspan=\"5\" style=\"text-align:center;$styledtls\">\n
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
							
//							$qrycheckendorse = mysql_query("
//										SELECT te.VESSELCODE,v.VESSEL,te.RANKCODE,r.RANK,te.ETD,ESTIMATEDATE,CONCAT(em.FNAME,', ',em.GNAME) AS ESTIMATEBY
//										FROM trainingendorsestatus te
//										LEFT JOIN vessel v ON v.VESSELCODE=te.VESSELCODE
//										LEFT JOIN rank r ON r.RANKCODE=te.RANKCODE
//										LEFT JOIN employee em ON em.EMPLOYEEID=te.ESTIMATEBY
//										WHERE APPLICANTNO=$embapplicantno AND te.ETD > '$datenow'			
//							") or die(mysql_error());
//					
//							if (mysql_num_rows($qrycheckendorse) > 0)
//							{
//								$rowcheckendorse = mysql_fetch_array($qrycheckendorse);
//								
//								$xvessel = $rowcheckendorse["VESSEL"];
//								$xrank = $rowcheckendorse["RANK"];
//								if(!empty($rowcheckendorse["ETD"]))
//									$xetd = date("dMY",strtotime($rowcheckendorse["ETD"]));
//								else 
//									$xetd = "";
//									
//								$xestby = $rowcheckendorse["ESTIMATEBY"];
//								$xestdate = date("dMY",strtotime($rowcheckendorse["ESTIMATEDATE"]));
//									
//								$recommend = "Recommended Assignment\n Vessel:&nbsp;&nbsp;$xvessel\n ETD:&nbsp;&nbsp;$xetd\n Rank:&nbsp;&nbsp;$xrank\n\n By:&nbsp;&nbsp;$xestby\n Date:&nbsp;&nbsp;$xestdate";
//								$stylerecommend = "background-color:Orange;";
//							}
//							else 
//							{
//								$recommend = "";
//								$stylerecommend = "";
//							}
							
							$vesselselect .= "
							<td style=\"text-align:center;$styledtls $styleshade\" title=\"$embrankcode\">&nbsp;$embalias2</td>\n
							<td style=\"$styledtls $styleshade \" title=\"Applicant No: $embapplicantno $promotion\n \">
								<span style=\"cursor:pointer;$disembcolor;\" $onclickpromote>$embname$promotion1</span>
							</td>\n
							<td style=\"text-align:center;$styledtls $styleshade\" title=\"$lastvessel1\">&nbsp;$vesselalias1</td>\n
							<td style=\"text-align:center;$styledtls $styleshade\" title=\"$embdateemb\">&nbsp;$embdateembshow</td>\n";
							
							// <td style=\"$styledtls $styleshade\">&nbsp;$embport</td>\n
							
							if($displaydelete==1)
							{
								if(empty($ccpno)) //check if approved ccpno does not exist
								{
									$vesselselect .= "
									<td style=\"text-align:center;$styledtls $styleshade\">\n
										<input id=\"noprint\" type=\"button\" value=\"Edit\" title=\"Edit crew...\"
											onclick=\"if(chkbtnloading()==1){addeditcrew('$ccid','$rankcode','$rankaliasx','$name','$datedisemb','$ccidemb','$embembport','$embembcountry','$embdateembjs','$embdatedisembjs','$embalias2','$embapplicantno','$applicantno','$embname');}\"
											style=\"border:0;background:inherit;cursor:pointer;color:Blue;font-weight:bold;\">\n
										<input id=\"noprint\" type=\"button\" value=\"Del\" title=\"Delete crew...\"
											onclick=\"if(chkbtnloading()==1){if(confirm('$onclickpromotedel')){
												ccidhidden.value='$ccid';embccidhidden.value='$ccidemb';chkloading('deleteembarkcrew',0)}}\"\"
											style=\"border:0;background:inherit;cursor:pointer;color:Red;font-weight:bold;\">\n
									</td>\n";
								}
								else 
								{
									$vesselselect .= "
									<td style=\"text-align:center;$styledtls $styleshade\">\n
										&nbsp;
									</td>\n";
								}
							}
							else 
							{
								$vesselselect .= "
								<td style=\"text-align:center;$styledtls $styleshade\">\n
									<input id=\"noprint\" type=\"button\" value=\"-\" title=\"$displaydeletetitle\"
										style=\"border:0;background:inherit;cursor:pointer;color:red;font-weight:bold;\">\n
								</td>\n";
							}
						}
						$vesselselect .= "
					</tr>\n";
						
//				}  //end of $embdepmnldate
						
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
				
				$getfname = "";
				$getgname = "";
				
				if(!empty($vesseltypecode))
				{
					$wherevsltype = "AND xv.VESSELTYPECODE='$vesseltypecode'";
				}
				else 
				{
					$wherevsltype = "AND xv.VESSELTYPECODE IN ('BULK','PCC','CONT','GC','ORE','WCC')";
				}
				
				if(!empty($rankcode))
				{
					$whererank = "AND c.RANKCODE='$rankcode'";
				}
				else 
				{
					$whererank = "AND c.RANKCODE IN ('D41','D49','E41','E49')";
				}
				
				include("veritas/include/qrygetexcrewlist.inc");
				
				$excrew="<table id=\"excrewdetails\" class=\"listcol\" cellspacing=\"1\" cellpadding=\"0\">";
					$ctndata=0;
					if($qrygetexcrewlist)
					{
						$classtype="odd";
						$grouplabel = "2";
						while($rowgetexcrewlist=mysql_fetch_array($qrygetexcrewlist))
						{
							$applicantno1=$rowgetexcrewlist['APPLICANTNO'];
							
							$crewcode1=$rowgetexcrewlist['CREWCODE'];
							$fname1=$rowgetexcrewlist['FNAME'];
							$gname1=$rowgetexcrewlist['GNAME'];
							$poolname1=$fname1.", ".$gname1;
							
							$rankcode1=$rowgetexcrewlist['RANKCODE'];
							$crewinactive=$rowgetexcrewlist['INACTIVE'];
							
							$datedisembtitle="";
							$datedisemb1=$rowgetexcrewlist['DATEDISEMB'];
							$datedisembshow=date("M 'y",strtotime($datedisemb1));
							$datedisembtitle=date("m/d/Y",strtotime($datedisemb1));
							
							$lastvessel1=$rowgetexcrewlist["VESSEL"];
							$vesselalias1=$rowgetexcrewlist["VALIAS"];
							
							$aliasx=$rowgetexcrewlist['ALIAS1'];
							$showcrewname = $fname1.", ".$gname1 . "  (" . $aliasx . ")";
							
							$crewonboard = $rowgetexcrewlist["CREWONBOARD"];
								
							// GET SCHOLAR OR FAST TRACK (1)
							$qryscholar=mysql_query("
								SELECT SCHOLASTICCODE,IF(cf.APPLICANTNO IS NULL,0,1) AS FASTTRACK,cs.MADEBY 
								FROM crew c 
								LEFT JOIN crewscholar cs ON c.APPLICANTNO=cs.APPLICANTNO
								LEFT JOIN crewfasttrack cf ON c.APPLICANTNO=cf.APPLICANTNO
								WHERE c.APPLICANTNO=$applicantno1") or die(mysql_error());
							
							$schstyle = "";
							
							$rowscholar=mysql_fetch_array($qryscholar);
							$scholasticcode1=$rowscholar["SCHOLASTICCODE"];
							$madeby1=$rowscholar["MADEBY"];
							$fasttrack1=$rowscholar["FASTTRACK"];
							
							$scholar_fttitle="";
							$scholar_ft="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							
							if(!empty($madeby1) || $fasttrack1==1)
							{
								if(!empty($madeby1))
								{
									$scholar_fttitle="Scholar";
									$scholar_ft = "SCH";
								}
								else 
								{
									$scholar_fttitle="Fast Track";
									$scholar_ft = "FST";
								}
								
								$schstyle = "background-color:Yellow;color:Blue;";
							}
							// END OF GET SCHOLAR OR FAST TRACK (1)

								
							$placeondblclick="selectcrew('$poolname1 ($rankcode)','$showcrewname','$applicantno1','$rankcode1');";
									
							//$excrew .= "<tr><td style=\"font-size:1.4em;background-color:Black;color:Orange;\" align=\"center\"><b>STANDBY</b></td></tr>";
								
							$obstyle="";
								
							if ($crewonboard != $grouplabel)	
							{
								switch ($crewonboard)
								{
									case "0"	:	$stat = "SB"; $stattitle = "Standby"; $statcolor = "background-color:Gray;color:White;"; 
										break;
									case "1"	:	$stat = "OB"; $stattitle = "Onboard"; $statcolor = "background-color:Black;color:Lime;"; 
										break;
								}
								
								$grouplabel = $crewonboard;
							}	
							
							if ($crewinactive == 1)
								$inacstyle = "font-style:italic;color:Red;";
							else 
								$inacstyle = "font-style:normal;color:Black;";
								
							if ($crewonboard == "1")
								$obstyle="font-weight:Bold;";
							else 
								$obstyle="";
									
							$excrew.="
								<tr style=\"border-top: 1px solid gray; $inacstyle\" $mouseovereffect class=\"$classtype\"
									ondblclick=\"$placeondblclick\">
									<td style=\"text-align:left;vertical-align:middle;$obstyle\" title=\"$poolname1\">&nbsp;$poolname1</td>
									<td style=\"text-align:center;vertical-align:middle;\" title=\"$lastvessel1\">$vesselalias1</td>
									<td style=\"text-align:center;vertical-align:middle;\" title=\"$datedisembtitle\">&nbsp;$datedisembshow&nbsp;</td>
									<td style=\"vertical-align:middle;$statcolor\" title=\"$stattitle\" align=\"center\"><b>$stat</b></td>
									<td style=\"text-align:center;vertical-align:middle;$schstyle\" title=\"$scholar_fttitle\">&nbsp;<b><i>$scholar_ft</i></b>&nbsp;</td>
									<td style=\"text-align:center;vertical-align:middle;cursor:pointer;\">
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
			
			case "searchcrew":
				
				$getfname = "";
				$getgname = "";
				$whererank = "";
				$whererank2 = "";
				
				if(!empty($searchfname))
					$getfname="AND cr.FNAME LIKE '$searchfname%'";
					
				if(!empty($searchgname))
				{
					if(empty($getfname))
						$getgname="AND cr.GNAME LIKE '$searchgname%'";
					else 
						$getgname="AND cr.GNAME LIKE '$searchgname%'";
				}
				
//				if(!empty($rankcode))
//				{
//					$whererank = "AND c.RANKCODE='$rankcode'";
//					$whererank2 = "AND aps.VMCRANKCODE='$rankcode'";
//				}
//				else 
//				{
//					$whererank = "AND c.RANKCODE IN ('D41','D49','E41','E49')";
//					$whererank2 = "";
//				}
//
//				if(!empty($vesseltypecode))
//				{
//					$wherevsltype = "AND xv.VESSELTYPECODE='$vesseltypecode'";
//				}
//				else 
//				{
//					$wherevsltype = "AND xv.VESSELTYPECODE IN ('BULK','PCC','CONT','GC','ORE','WCC')";
//				}
				$usecrewlist = 2;
				if(!empty($searchfname) || !empty($searchgname))
					include("veritas/include/qrygetexcrewlist.inc");
				
				$searchcrew="<table id=\"searchcrewdetails\" class=\"listcol\" cellspacing=\"1\" cellpadding=\"0\">";
					$ctndata=0;					
					if($qrygetexcrewlist2)
					{
						$classtype="odd";
						$grouplabel = "2";
						while($rowgetexcrewlist2=mysql_fetch_array($qrygetexcrewlist2))
						{
							$applicantno1=$rowgetexcrewlist2['APPLICANTNO'];
							
							$crewcode1=$rowgetexcrewlist2['CREWCODE'];
							$fname1=$rowgetexcrewlist2['FNAME'];
							$gname1=$rowgetexcrewlist2['GNAME'];
							$poolname1=$fname1.", ".$gname1;
							
							$rankcode1=$rowgetexcrewlist2['RANKCODE'];
							$crwtype1=$rowgetexcrewlist2['TYPE'];
//							$rankcodealias1=$rowgetexcrewlist2['ALIAS1'];
//							$poolname1 .= "( " . $rankcodealias1 . " )";
							
							$crewinactive=$rowgetexcrewlist2['INACTIVE'];
							
							$datedisembtitle="";
							
							if($crwtype1 == '1')
							{
								$datedisemb1=$rowgetexcrewlist2['DATEDISEMB'];
								$datedisembshow=date("M 'y",strtotime($datedisemb1));
								$datedisembtitle=date("m/d/Y",strtotime($datedisemb1));
							}
							else 
							{
								$datedisembshow = "---";
							}
							
							$lastvessel1=$rowgetexcrewlist2["VESSEL"];
							$vesselalias1=$rowgetexcrewlist2["VALIAS"];
							
							$aliasx=$rowgetexcrewlist2['ALIAS1'];
							$showcrewname = $fname1.", ".$gname1 . "  (" . $aliasx . ")";
							
							$crewonboard = $rowgetexcrewlist2["CREWONBOARD"];
								
							// GET SCHOLAR OR FAST TRACK (1)
							$qryscholar=mysql_query("
								SELECT SCHOLASTICCODE,IF(cf.APPLICANTNO IS NULL,0,1) AS FASTTRACK,cs.MADEBY 
								FROM crew c 
								LEFT JOIN crewscholar cs ON c.APPLICANTNO=cs.APPLICANTNO
								LEFT JOIN crewfasttrack cf ON c.APPLICANTNO=cf.APPLICANTNO
								WHERE c.APPLICANTNO=$applicantno1") or die(mysql_error());
							
							$schstyle = "";
							
							$rowscholar=mysql_fetch_array($qryscholar);
							$scholasticcode1=$rowscholar["SCHOLASTICCODE"];
							$madeby1=$rowscholar["MADEBY"];
							$fasttrack1=$rowscholar["FASTTRACK"];
							
							$scholar_fttitle="";
							$scholar_ft="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							
							if(!empty($madeby1) || $fasttrack1==1)
							{
								if(!empty($madeby1))
								{
									$scholar_fttitle="Scholar";
									$scholar_ft = "SCH";
								}
								else 
								{
									$scholar_fttitle="Fast Track";
									$scholar_ft = "FST";
								}
								
								$schstyle = "background-color:Yellow;color:Blue;";
							}
							// END OF GET SCHOLAR OR FAST TRACK (1)

								
							$placeondblclick="selectcrew('$poolname1 ($rankcode)','$showcrewname','$applicantno1','$rankcode1');";
									
							//$excrew .= "<tr><td style=\"font-size:1.4em;background-color:Black;color:Orange;\" align=\"center\"><b>STANDBY</b></td></tr>";
								
							$obstyle="";
								
							if ($crewonboard != $grouplabel)	
							{
								switch ($crewonboard)
								{
									case "0"	:	$stat = "SB"; $stattitle = "Standby"; $statcolor = "background-color:Gray;color:White;"; 
										break;
									case "1"	:	$stat = "OB"; $stattitle = "Onboard"; $statcolor = "background-color:Black;color:Lime;"; 
										break;
								}
								
								$grouplabel = $crewonboard;
							}	
							
							if ($crewinactive == 1)
								$inacstyle = "font-style:italic;color:Red;";
							else 
								$inacstyle = "font-style:normal;color:Black;";
								
							if ($crewonboard == "1")
								$obstyle="font-weight:Bold;";
							else 
								$obstyle="";
									
							$searchcrew.="
								<tr style=\"border-top: 1px solid gray; $inacstyle\" $mouseovereffect class=\"$classtype\"
									ondblclick=\"$placeondblclick\">
									<td style=\"text-align:left;vertical-align:middle;$obstyle\" title=\"$poolname1\">&nbsp;$showcrewname</td>
									<td style=\"text-align:center;vertical-align:middle;\" title=\"$lastvessel1\">$vesselalias1</td>
									<td style=\"text-align:center;vertical-align:middle;\" title=\"$datedisembtitle\">&nbsp;$datedisembshow&nbsp;</td>
									<td style=\"vertical-align:middle;$statcolor\" title=\"$stattitle\" align=\"center\"><b>$stat</b></td>
									<td style=\"text-align:center;vertical-align:middle;$schstyle\" title=\"$scholar_fttitle\">&nbsp;<b><i>$scholar_ft</i></b>&nbsp;</td>
									<td style=\"text-align:center;vertical-align:middle;cursor:pointer;\">
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
			
			case "foreign":
				if(!empty($rankcodehidden))
				{
					$whererank = "AND c.RANKCODE='$rankcodehidden'";
				}
				else 
				{
					$whererank = "AND c.RANKCODE IN ('D41','D49','E41','E49')";
				}
				$qrygetforeignlist=mysql_query("SELECT c.APPLICANTNO,c.CREWCODE,c.FNAME,c.GNAME,c.RANKCODE,r.ALIAS1
					FROM crewforeign c 
					LEFT JOIN rank r ON c.RANKCODE=r.RANKCODE
					$whererank
					ORDER BY c.FNAME,c.GNAME") or die(mysql_error());
				$foreign="<table id=\"foreigndetails\" class=\"listcol\" cellspacing=\"1\" cellpadding=\"0\">";
					$ctndata=0;
					if($qrygetforeignlist)
					{
						// $classtype="listgray";
						$classtype="odd";
						while($rowgetforeignlist=mysql_fetch_array($qrygetforeignlist))
						{
							$applicantno1=$rowgetforeignlist['APPLICANTNO'];
							$crewcode1=$rowgetforeignlist['CREWCODE'];
							$fname1=$rowgetforeignlist['FNAME'];
							$gname1=$rowgetforeignlist['GNAME'];
							$rankcode1=$rowgetforeignlist['RANKCODE'];
							$poolname1=$fname1.", ".$gname1;
							$aliasx=$rowgetforeignlist['ALIAS1'];
							$showcrewname = $fname1.", ".$gname1 . "  (" . $aliasx . ")";
							
							// CREATE ARRAY FOR SORTING
							// $col1[$ctndata]=$poolname1;
							// $col2[$ctndata]=$vesselalias1;
							// $col2ttl[$ctndata]=$lastvessel1;
							// $oth1[$ctndata]=$rankcode1;
							// $oth2[$ctndata]=$applicantno1;
							// $dblclick[$ctndata]="";
							// $dblclickalert[$ctndata]=$getalert;
							// END OF CREATE ARRAY FOR SORTING
							
							// $ctndata++;
						// }
						// if($ctndata!=0)
						// {
							// $stuff = array(	"col1" => $col1,
											// "col2" => $col2,
											// "col2ttl" => $col2ttl,
											// "oth1" => $oth1,
											// "oth2" => $oth2,
											// "dblclick" => $dblclick,
											// "dblclickalert" => $dblclickalert
										// );
							// $sortby=$stuff[$sortby];
							// if($orderby=="SORT_DESC")
								// array_multisort($sortby,SORT_DESC,$stuff["col1"],
																// $stuff["col2"],
																// $stuff["col2ttl"],
																// $stuff["oth1"],
																// $stuff["oth2"],
																// $stuff["dblclick"],
																// $stuff["dblclickalert"]
																// );
							// else 
								// array_multisort($sortby,SORT_ASC,$stuff["col1"],
																// $stuff["col2"],
																// $stuff["col2ttl"],
																// $stuff["oth1"],
																// $stuff["oth2"],
																// $stuff["dblclick"],
																// $stuff["dblclickalert"]
																// );
							
							
							// for($i=0;$i<$ctndata;$i++)
							// {
								// $poolname1=$stuff["col1"][$i];
								// $vesselalias1=$stuff["col2"][$i];
								// $lastvessel1=$stuff["col2ttl"][$i];
								// $rankcode1=$stuff["oth1"][$i];
								// $applicantno1=$stuff["oth2"][$i];
								// $dblclick1=$stuff["dblclick"][$i];
								// $dblclickalert1=$stuff["dblclickalert"][$i];
								// if(!empty($dblclickalert1))
									//$placeondblclick="selectcrew('$poolname1 ($rankcode)','$showcrewname','$applicantno1','$rankcode1');";
								
									// $placeondblclick="if(confirm('$dblclickalert1. Are you sure?')){selectcrew('$poolname1 ($rankcode1)','$applicantno1');}";
								// else 
									$placeondblclick="selectcrew('$poolname1 ($rankcode1)','$showcrewname','$applicantno1','$rankcode1');";
								$foreign.= "
								<tr style=\"border-top: 1px solid gray;\" $mouseovereffect class=\"$classtype\"
									ondblclick=\"$placeondblclick\">
									<td style=\"text-align:left;vertical-align:middle;cursor:pointer;\" title=\"$applicantno1-$poolname1\">&nbsp;$poolname1</td>
								</tr>";
								if($classtype=="even") { $classtype="odd";} else { $classtype="even"; }
							// }
						}
					}
				$foreign.= "
					</table>";
				$resulttemp=$foreign;
			break;
			case "newhire":
				// $qrygetnewhirelist=mysql_query("
						// SELECT c.APPLICANTNO,c.CREWCODE,c.FNAME,c.GNAME,r.ALIAS1,r.RANKCODE
						// FROM crew c
						// LEFT JOIN crewchange cc ON cc.APPLICANTNO=c.APPLICANTNO
						// LEFT JOIN applicantstatus aps ON aps.APPLICANTNO=c.APPLICANTNO
						// LEFT JOIN rank r ON r.RANKCODE=aps.VMCRANKCODE
						// where c.STATUS=1
						// AND aps.STATUS=1 AND aps.ACCEPTDATE IS NOT NULL
					// ") or die(mysql_error());
				$qrygetnewhirelist=mysql_query("
						SELECT c.APPLICANTNO,c.CREWCODE,c.FNAME,c.GNAME,r.ALIAS1,r.RANKCODE
						FROM applicant a
						LEFT JOIN crew c ON c.APPLICANTNO=a.APPLICANTNO
						LEFT JOIN applicantstatus aps ON aps.APPLICANTNO=a.APPLICANTNO
						LEFT JOIN rank r ON r.RANKCODE=aps.VMCRANKCODE
						WHERE NOT EXISTS (
						SELECT * FROM crewchange cc WHERE cc.APPLICANTNO=a.APPLICANTNO AND DEPMNLDATE IS NOT NULL
						) AND c.STATUS = 1 AND aps.STATUS=1 AND ACCEPTDATE IS NOT NULL
					") or die(mysql_error());
		
				$newhire="<table id=\"newhiredetails\" class=\"listcol\" cellspacing=\"1\" cellpadding=\"0\">";
					$ctndata=0;
					$grouplabel = "2";
					if($qrygetnewhirelist)
					{
						while($rowgetnewhirelist=mysql_fetch_array($qrygetnewhirelist))
						{
							$applicantno1=$rowgetnewhirelist["APPLICANTNO"];
							$crewcode1=$rowgetnewhirelist["CREWCODE"];
							$fname1=$rowgetnewhirelist["FNAME"];
							$gname1=$rowgetnewhirelist["GNAME"];
							$rankcode1=$rowgetnewhirelist["RANKCODE"];
							$poolname1=$fname1.", ".$gname1;
							
							$aliasx=$rowgetnewhirelist["ALIAS1"];
							$showcrewname = $fname1.", ".$gname1 . "  (" . $aliasx . ")";
							
							// GET LAST VESSEL & DATEDISEMB
							$qrylastvessel=mysql_query("
								SELECT VESSEL,DATEDISEMB,ALIAS1 FROM
								(SELECT VESSEL,IF(DATEDISEMB IS NULL,'---',DATEDISEMB) AS DATEDISEMB,VESSEL AS ALIAS1
								FROM crewexperience
								WHERE APPLICANTNO=$applicantno1) x
								ORDER BY DATEDISEMB DESC 
								LIMIT 1") or die(mysql_error());
							if(mysql_num_rows($qrylastvessel)>0)
							{
								$rowlastvessel=mysql_fetch_array($qrylastvessel);
								$lastvessel1=$rowlastvessel["VESSEL"];
								$vesselalias1=$rowlastvessel["ALIAS1"];
								
								if($rowlastvessel["DATEDISEMB"] != "---")
								{
									$datedisemb1=$rowlastvessel["DATEDISEMB"];
									$datedisembshow=date("M 'y",strtotime($datedisemb1));
									$datedisembtitle=date("m/d/Y",strtotime($datedisemb1));
								}
								else 
								{
									$datedisemb1=$rowlastvessel["DATEDISEMB"];
								}
								
//								$datedisembtitle="";
//								if($datedisemb1 != "---")
//								{
//									$datedisembshow=date("M 'y",strtotime($datedisemb1));
//									$datedisembtitle=date("m/d/Y",strtotime($datedisemb1));
//								}
//								else 
//									$datedisembshow="---";
							}
							else 
							{
								$lastvessel1="---";
								$datedisemb1="---";
								$vesselalias1="";
							}

							// END OF GET LAST VESSEL & DATEDISEMB
							
							// GET SCHOLAR OR FAST TRACK (1)
							$qryscholar=mysql_query("
								SELECT SCHOLASTICCODE,IF(cf.APPLICANTNO IS NULL,0,1) AS FASTTRACK,cs.MADEBY 
								FROM crew c 
								LEFT JOIN crewscholar cs ON c.APPLICANTNO=cs.APPLICANTNO
								LEFT JOIN crewfasttrack cf ON c.APPLICANTNO=cf.APPLICANTNO
								WHERE c.APPLICANTNO=$applicantno1") or die(mysql_error());
							
							$schstyle = "";
							
							$rowscholar=mysql_fetch_array($qryscholar);
							$scholasticcode1=$rowscholar["SCHOLASTICCODE"];
							$madeby1=$rowscholar["MADEBY"];
							$fasttrack1=$rowscholar["FASTTRACK"];
							
							$scholar_fttitle="";
							$scholar_ft="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							
							if(!empty($madeby1) || $fasttrack1==1)
							{
								if(!empty($madeby1))
								{
									$scholar_fttitle="Scholar";
									$scholar_ft = "SCH";
								}
								else 
								{
									$scholar_fttitle="Fast Track";
									$scholar_ft = "FST";
								}
								
								$schstyle = "background-color:Yellow;color:Blue;";
							}
							// END OF GET SCHOLAR OR FAST TRACK (1)

						$placeondblclick="selectcrew('$poolname1 ($rankcode)','$showcrewname','$applicantno1','$rankcode1');";
						
						$obstyle="";
							
//						if ($crewonboard != $grouplabel)	
//						{
//							switch ($crewonboard)
//							{
//								case "0"	:	$stat = "SB"; $stattitle = "Standby"; $statcolor = "background-color:Gray;color:White;"; 
//									break;
//								case "1"	:	$stat = "OB"; $stattitle = "Onboard"; $statcolor = "background-color:Black;color:Lime;"; 
//									break;
//							}
//							
//							$grouplabel = $crewonboard;
//						}	
						
//						if ($crewinactive == 1)
//							$inacstyle = "font-style:italic;color:Red;";
//						else 
//							$inacstyle = "font-style:normal;color:Black;";
							
						if ($crewonboard == "1")
							$obstyle="font-weight:Bold;";
						else 
							$obstyle="";

						$newhire.= "
							<tr class=\"$classtype\" $mouseovereffect ondblclick=\"$placeondblclick\">
								<td style=\"text-align:left;vertical-align:middle;$obstyle\" title=\"$poolname1\">&nbsp;$showcrewname</td>
								<td style=\"text-align:center;vertical-align:middle;\" title=\"$lastvessel1\">$vesselalias1</td>
								<td style=\"text-align:center;vertical-align:middle;\" title=\"$datedisembtitle\">&nbsp;$datedisembshow&nbsp;</td>
								<td style=\"text-align:center;vertical-align:middle;$schstyle\" title=\"$scholar_fttitle\">&nbsp;<b><i>$scholar_ft</i></b>&nbsp;</td>
								<td style=\"text-align:center;vertical-align:middle;cursor:pointer;\">
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