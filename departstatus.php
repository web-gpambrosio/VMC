<?php
include('veritas/connectdb.php');
include("veritas/include/stylephp.inc");
include("veritas/include/functions.inc");

session_start();

if(isset($_SESSION['employeeid']))
	$employeeid=$_SESSION['employeeid'];
	
$currentdate = date("Y-m-d H:i:s");
$datenow = date("m/d/Y");
$dateformat = "dMY";

if(isset($_POST["actiontxt"]))
	$actiontxt=$_POST["actiontxt"];
	
if(isset($_POST["remhidden"]))
	$remhidden=$_POST["remhidden"];
	
if(isset($_POST["rowhidden"]))
	$rowhidden=$_POST["rowhidden"];
	
if(isset($_POST["rdselect"]))
	$rdselect=$_POST["rdselect"];

if(empty($rdselect))
	$rdselect="NAIA1";

$$rdselect="checked=\"checked\"";
	
if(isset($_POST["ccid"]))
	$ccid=$_POST["ccid"];
else 
	$ccid=$_GET["ccid"];
	
if(isset($_POST["flagcode"]))
	$flagcode=$_POST["flagcode"];
else 
	$flagcode=$_GET["flagcode"];

if(isset($_POST["rankcode"]))
	$rankcode=$_POST["rankcode"];
else 
	$rankcode=$_GET["rankcode"];

if(isset($_POST["idpicture"]))
	$getidpicture=$_POST["idpicture"];
	
if(isset($_POST["allcertificates"]))
	$getallcertificates=$_POST["allcertificates"];
	
switch ($actiontxt)
{
	case "editdetail":
		$getrem=$rowhidden."REM";
		$getby=$rowhidden."BY";
		$getdate=$rowhidden."DATE";
		
		//check exist
		$qryexist=mysql_query("SELECT CCID FROM departhdr WHERE CCID=$ccid") or die(mysql_error());
		if(mysql_num_rows($qryexist)==0)
			$qrysave=mysql_query("INSERT INTO departhdr (CCID) VALUES($ccid)") or die(mysql_error());
		$qryupdate=mysql_query("UPDATE departhdr SET $getrem='$remhidden',$getby='$employeeid',$getdate='$currentdate'
			WHERE CCID=$ccid") or die(mysql_error());
	break;
	case "idpicture":
		$getrem=$rowhidden."REM";
		$getby=$rowhidden."BY";
		$getdate=$rowhidden."DATE";
		if(empty($getidpicture))
			mysql_query("UPDATE departhdr SET $getrem=NULL,$getby='$employeeid',$getdate='$currentdate'
				WHERE CCID=$ccid") or die(mysql_error());
		else
			mysql_query("UPDATE departhdr SET $getrem='SUBMITTED',$getby='$employeeid',$getdate='$currentdate'
				WHERE CCID=$ccid") or die(mysql_error());
	break;
	case "allcertificates":
		$getrem=$rowhidden."REM";
		$getby=$rowhidden."BY";
		$getdate=$rowhidden."DATE";
		if(empty($getallcertificates))
			mysql_query("UPDATE departhdr SET $getrem=NULL,$getby='$employeeid',$getdate='$currentdate'
				WHERE CCID=$ccid") or die(mysql_error());
		else
			mysql_query("UPDATE departhdr SET $getrem='SUBMITTED',$getby='$employeeid',$getdate='$currentdate'
				WHERE CCID=$ccid") or die(mysql_error());
	break;
}

$qryappno=mysql_query("SELECT APPLICANTNO FROM crewchange WHERE CCID=$ccid") or die(mysql_error());
$rowappno=mysql_fetch_array($qryappno);
$applicantno=$rowappno["APPLICANTNO"];

$formatdate="dMY";

//initialize variables
$predeprem="&nbsp;";
$predepdate="&nbsp;";
$predepby="&nbsp;";
$preembrem="&nbsp;";
$preembdate="&nbsp;";
$preembby="&nbsp;";
$contractrem="&nbsp;";
$contractdate="&nbsp;";
$contractby="&nbsp;";
$amosuprem="&nbsp;";
$amosupdate="&nbsp;";
$amosupby="&nbsp;";
$infoshtrem="&nbsp;";
$infoshtdate="&nbsp;";
$infoshtby="&nbsp;";
$rpsrem="&nbsp;";
$rpsdate="&nbsp;";
$rpsby="&nbsp;";
$consignrem="&nbsp;";
$consigndate="&nbsp;";
$consignby="&nbsp;";
$tentlinerem="&nbsp;";
$tentlinedate="&nbsp;";
$tentlineby="&nbsp;";
$valdocsrem="&nbsp;";
$valdocsdate="&nbsp;";
$valdocsby="&nbsp;";
$flightdtlrem="&nbsp;";
$flightdtldate="&nbsp;";
$flightdtlby="&nbsp;";
$panamaapprem="&nbsp;";
$panamaappdate="&nbsp;";
$panamaappby="&nbsp;";
$form218rem="&nbsp;";
$form218date="&nbsp;";
$form218by="&nbsp;";
$issueshoesrem="&nbsp;";
$issueshoesdate="&nbsp;";
$issueshoesby="&nbsp;";
$form217rem="&nbsp;";
$form217date="&nbsp;";
$form217by="&nbsp;";
$onsignersrem="&nbsp;";
$onsignersdate="&nbsp;";
$onsignersby="&nbsp;";
$report201rem="&nbsp;";
$report201date="&nbsp;";
$report201by="&nbsp;";
$report272rem="&nbsp;";
$report272date="&nbsp;";
$report272by="&nbsp;";
$medicalreferralrem="&nbsp;";
$medicalreferraldate="&nbsp;";
$medicalreferralby="&nbsp;";
$idpicturerem="&nbsp;";
$idpicturedate="&nbsp;";
$idpictureby="&nbsp;";
$allcertificatesrem="&nbsp;";
$allcertificatesdate="&nbsp;";
$allcertificatesby="&nbsp;";
$pdosschedulerem="&nbsp;";
$pdosscheduledate="&nbsp;";
$pdosscheduleby="&nbsp;";
$updatesshoesrem="&nbsp;";
$updatesshoesdate="&nbsp;";
$updatesshoesby="&nbsp;";
$addendumrem="&nbsp;";
$addendumdate="&nbsp;";
$addendumby="&nbsp;";
$affidavitrem="&nbsp;";
$affidavitdate="&nbsp;";
$affidavitby="&nbsp;";
$antidrugrem="&nbsp;";
$antidrugdate="&nbsp;";
$antidrugby="&nbsp;";
$trainingchecklistrem="&nbsp;";
$trainingchecklistdate="&nbsp;";
$trainingchecklistby="&nbsp;";

$qrydeparthdr=mysql_query("SELECT * FROM departhdr WHERE CCID=$ccid") or die(mysql_error());
$cntdeparthdr=mysql_num_rows($qrydeparthdr);
if($cntdeparthdr!=0)
{
	$rowdeparthdr=mysql_fetch_array($qrydeparthdr);
	
	if(!empty($rowdeparthdr["PREDEPREM"]))
		$predeprem=$rowdeparthdr["PREDEPREM"];
	if(!empty($rowdeparthdr["PREDEPDATE"]))
	{
		$predepdate=date($formatdate,strtotime($rowdeparthdr["PREDEPDATE"]));
		$predepby=$rowdeparthdr["PREDEPBY"];
	}
	if(!empty($rowdeparthdr["PREEMBREM"]))
		$preembrem=$rowdeparthdr["PREEMBREM"];
	if(!empty($rowdeparthdr["PREEMBDATE"]))
	{
		$preembdate=date($formatdate,strtotime($rowdeparthdr["PREEMBDATE"]));
		$preembby=$rowdeparthdr["PREEMBBY"];
	}
	if(!empty($rowdeparthdr["CONTRACTREM"]))
		$contractrem=$rowdeparthdr["CONTRACTREM"];
	if(!empty($rowdeparthdr["CONTRACTDATE"]))
	{
		$contractdate=date($formatdate,strtotime($rowdeparthdr["CONTRACTDATE"]));
		$contractby=$rowdeparthdr["CONTRACTBY"];
	}
	if(!empty($rowdeparthdr["AMOSUPREM"]))
		$amosuprem=$rowdeparthdr["AMOSUPREM"];
	if(!empty($rowdeparthdr["AMOSUPDATE"]))
	{
		$amosupdate=date($formatdate,strtotime($rowdeparthdr["AMOSUPDATE"]));
		$amosupby=$rowdeparthdr["AMOSUPBY"];
	}
	if(!empty($rowdeparthdr["INFOSHTREM"]))
		$infoshtrem=$rowdeparthdr["INFOSHTREM"];
	if(!empty($rowdeparthdr["INFOSHTDATE"]))
	{
		$infoshtdate=date($formatdate,strtotime($rowdeparthdr["INFOSHTDATE"]));
		$infoshtby=$rowdeparthdr["INFOSHTBY"];
	}
	if(!empty($rowdeparthdr["RPSREM"]))
		$rpsrem=$rowdeparthdr["RPSREM"];
	if(!empty($rowdeparthdr["RPSDATE"]))
	{
		$rpsdate=date($formatdate,strtotime($rowdeparthdr["RPSDATE"]));
		$rpsby=$rowdeparthdr["RPSBY"];
	}
	if(!empty($rowdeparthdr["CONSIGNREM"]))
		$consignrem=$rowdeparthdr["CONSIGNREM"];
	if(!empty($rowdeparthdr["CONSIGNDATE"]))
	{
		$consigndate=date($formatdate,strtotime($rowdeparthdr["CONSIGNDATE"]));
		$consignby=$rowdeparthdr["CONSIGNBY"];
	}
	if(!empty($rowdeparthdr["TENTLINEREM"]))
		$tentlinerem=$rowdeparthdr["TENTLINEREM"];
	if(!empty($rowdeparthdr["TENTLINEDATE"]))
	{
		$tentlinedate=date($formatdate,strtotime($rowdeparthdr["TENTLINEDATE"]));
		$tentlineby=$rowdeparthdr["TENTLINEBY"];
	}
	if(!empty($rowdeparthdr["VALDOCSREM"]))
		$valdocsrem=$rowdeparthdr["VALDOCSREM"];
	if(!empty($rowdeparthdr["VALDOCSDATE"]))
	{
		$valdocsdate=date($formatdate,strtotime($rowdeparthdr["VALDOCSDATE"]));
		$valdocsby=$rowdeparthdr["VALDOCSBY"];
	}
	if(!empty($rowdeparthdr["FLIGHTDETAILSREM"]))
		$flightdtlrem=$rowdeparthdr["FLIGHTDETAILSREM"];
	if(!empty($rowdeparthdr["FLIGHTDETAILSDATE"]))
	{
		$flightdtldate=date($formatdate,strtotime($rowdeparthdr["FLIGHTDETAILSDATE"]));
		$flightdtlby=$rowdeparthdr["FLIGHTDETAILSBY"];
	}
	if(!empty($rowdeparthdr["PANAMAAPPREM"]))
		$panamaapprem=$rowdeparthdr["PANAMAAPPREM"];
	if(!empty($rowdeparthdr["PANAMAAPPDATE"]))
	{
		$panamaappdate=date($formatdate,strtotime($rowdeparthdr["PANAMAAPPDATE"]));
		$panamaappby=$rowdeparthdr["PANAMAAPPBY"];
	}
	if(!empty($rowdeparthdr["FORM218REM"]))
		$form218rem=$rowdeparthdr["FORM218REM"];
	if(!empty($rowdeparthdr["FORM218DATE"]))
	{
		$form218date=date($formatdate,strtotime($rowdeparthdr["FORM218DATE"]));
		$form218by=$rowdeparthdr["FORM218BY"];
	}
	if(!empty($rowdeparthdr["ISSUESHOESREM"]))
		$issueshoesrem=$rowdeparthdr["ISSUESHOESREM"];
	if(!empty($rowdeparthdr["ISSUESHOESDATE"]))
	{
		$issueshoesdate=date($formatdate,strtotime($rowdeparthdr["ISSUESHOESDATE"]));
		$issueshoesby=$rowdeparthdr["ISSUESHOESBY"];
	}
	if(!empty($rowdeparthdr["FORM217REM"]))
		$form217rem=$rowdeparthdr["FORM217REM"];
	if(!empty($rowdeparthdr["FORM217DATE"]))
	{
		$form217date=date($formatdate,strtotime($rowdeparthdr["FORM217DATE"]));
		$form217by=$rowdeparthdr["FORM217BY"];
	}
	if(!empty($rowdeparthdr["ONSIGNERSREM"]))
		$onsignersrem=$rowdeparthdr["ONSIGNERSREM"];
	if(!empty($rowdeparthdr["ONSIGNERSDATE"]))
	{
		$onsignersdate=date($formatdate,strtotime($rowdeparthdr["ONSIGNERSDATE"]));
		$onsignersby=$rowdeparthdr["ONSIGNERSBY"];
	}
	if(!empty($rowdeparthdr["REPORT201REM"]))
		$report201rem=$rowdeparthdr["REPORT201REM"];
	if(!empty($rowdeparthdr["REPORT201DATE"]))
	{
		$report201date=date($formatdate,strtotime($rowdeparthdr["REPORT201DATE"]));
		$report201by=$rowdeparthdr["REPORT201BY"];
	}
	if(!empty($rowdeparthdr["REPORT272REM"]))
		$report272rem=$rowdeparthdr["REPORT272REM"];
	if(!empty($rowdeparthdr["REPORT272DATE"]))
	{
		$report272date=date($formatdate,strtotime($rowdeparthdr["REPORT272DATE"]));
		$report272by=$rowdeparthdr["REPORT272BY"];
	}
	
	if(!empty($rowdeparthdr["MEDICALREFERRALREM"]))
		$medicalreferralrem=$rowdeparthdr["MEDICALREFERRALREM"];
	if(!empty($rowdeparthdr["MEDICALREFERRALDATE"]))
	{
		$medicalreferraldate=date($formatdate,strtotime($rowdeparthdr["MEDICALREFERRALDATE"]));
		$medicalreferralby=$rowdeparthdr["MEDICALREFERRALBY"];
	}
	if(!empty($rowdeparthdr["IDPICTUREREM"]))
	{
		$idpicturerem=$rowdeparthdr["IDPICTUREREM"];
		$chkidpicture="checked=\"checked\"";
	}
	if(!empty($rowdeparthdr["IDPICTUREDATE"]))
	{
		$idpicturedate=date($formatdate,strtotime($rowdeparthdr["IDPICTUREDATE"]));
		$idpictureby=$rowdeparthdr["IDPICTUREBY"];
	}
	if(!empty($rowdeparthdr["ALLCERTIFICATESREM"]))
	{
		$allcertificatesrem=$rowdeparthdr["ALLCERTIFICATESREM"];
		$chkallcertificates="checked=\"checked\"";
	}
	if(!empty($rowdeparthdr["ALLCERTIFICATESDATE"]))
	{
		$allcertificatesdate=date($formatdate,strtotime($rowdeparthdr["ALLCERTIFICATESDATE"]));
		$allcertificatesby=$rowdeparthdr["ALLCERTIFICATESBY"];
	}
	if(!empty($rowdeparthdr["PDOSSCHEDULEREM"]))
		$pdosschedulerem=$rowdeparthdr["PDOSSCHEDULEREM"];
	if(!empty($rowdeparthdr["PDOSSCHEDULEDATE"]))
	{
		$pdosscheduledate=date($formatdate,strtotime($rowdeparthdr["PDOSSCHEDULEDATE"]));
		$pdosscheduleby=$rowdeparthdr["PDOSSCHEDULEBY"];
	}
	if(!empty($rowdeparthdr["UPDATESSHOESREM"]))
		$updatesshoesrem=$rowdeparthdr["UPDATESSHOESREM"];
	if(!empty($rowdeparthdr["UPDATESSHOESDATE"]))
	{
		$updatesshoesdate=date($formatdate,strtotime($rowdeparthdr["UPDATESSHOESDATE"]));
		$updatesshoesby=$rowdeparthdr["UPDATESSHOESBY"];
	}
	if(!empty($rowdeparthdr["ADDENDUMREM"]))
		$addendumrem=$rowdeparthdr["ADDENDUMREM"];
	if(!empty($rowdeparthdr["ADDENDUMDATE"]))
	{
		$addendumdate=date($formatdate,strtotime($rowdeparthdr["ADDENDUMDATE"]));
		$addendumby=$rowdeparthdr["ADDENDUMBY"];
	}
	if(!empty($rowdeparthdr["AFFIDAVITREM"]))
		$affidavitrem=$rowdeparthdr["AFFIDAVITREM"];
	if(!empty($rowdeparthdr["AFFIDAVITDATE"]))
	{
		$affidavitdate=date($formatdate,strtotime($rowdeparthdr["AFFIDAVITDATE"]));
		$affidavitby=$rowdeparthdr["AFFIDAVITBY"];
	}
	if(!empty($rowdeparthdr["ANTIDRUGREM"]))
		$antidrugrem=$rowdeparthdr["ANTIDRUGREM"];
	if(!empty($rowdeparthdr["ANTIDRUGDATE"]))
	{
		$antidrugdate=date($formatdate,strtotime($rowdeparthdr["ANTIDRUGDATE"]));
		$antidrugby=$rowdeparthdr["ANTIDRUGBY"];
	}
	if(!empty($rowdeparthdr["TRAININGCHECKLISTREM"]))
		$trainingchecklistrem=$rowdeparthdr["TRAININGCHECKLISTREM"];
	if(!empty($rowdeparthdr["TRAININGCHECKLISTDATE"]))
	{
		$trainingchecklistdate=date($formatdate,strtotime($rowdeparthdr["TRAININGCHECKLISTDATE"]));
		$trainingchecklistby=$rowdeparthdr["TRAININGCHECKLISTBY"];
	}
}


$row1="Pre-Departure Checklist";
$row2="Pre-Embarkation Medical Exam";
$row3="POEA Contract ";
$row4="AMOSUP Contract";
$row5="Information Sheet ";
$row6="RPS ";
$row7="VMC Contract Signing";
$row8="Tentative Vessel Line-up";
$row9="Validity of Documents";
$row10="Flight Details";
$row11="Panama Application";
$row12="Form 218";
$row13="Issuance of Shoes, Uniforms & Gears";
$row14="Form 217";
$row15="On-Signers";
$row16="Report 201";
$row17="Report 272";
$row18="Medical Referral";
$row19="Submit ID Picture";
$row20="Submit All Certificates";
$row21="PDOS Schedule";
$row22="Update Shoes and Uniform Size";
$row23="Addendum to Employment Contract";
$row24="Affidavit";
$row25="Anti Drug Abuse Affidavit ";
$row26="Training Checklist";

$doc1="Z2";
$doc2="P7";
$doc3="VC";
$doc4="Z4";
$doc5="Z5";
$doc6="Z6";
$doc7="Z7";
$doc8="Z8";
$doc9="Z9";
$docdir="docimages/$applicantno/D/CCID$ccid/";

$stylespangreen="style=\"font-weight:bold;cursor:pointer;color:green;\"";
$stylespanred="style=\"font-weight:bold;cursor:pointer;color:red;\"";

//check Pre-Departure Checklist
if(is_file($docdir.$doc1.".pdf"))
	$predep="<span $stylespangreen onclick=\"openWindow('$docdir$doc1','$doc1','500px','600px');\">VIEW</span>";
else 
	$predep="<span $stylespanred onclick=\"alert('No PDF file found!');\">PENDING</span>";

//check Pre-Embarkation Medical Exam
if(is_file("docimages/$applicantno/D/$doc2.pdf"))
	$preemb="<span $stylespangreen onclick=\"openWindow('docimages/$applicantno/D/$doc2','$doc2','500px','600px');\">VIEW</span>";
else 
	$preemb="<span $stylespanred onclick=\"alert('No PDF file found!');\">PENDING</span>";

//check Contract
if(is_file($docdir.$doc3.".pdf"))
	$contract="<span $stylespangreen onclick=\"openWindow('$docdir$doc3','$doc3','500px','600px');\">VIEW</span>";
else 
	$contract="<span $stylespanred onclick=\"openWindow('repdepartingpoea.php?title=POEA Contract&type=1&ccid=$ccid&applicantno=$applicantno&print=1','contract',0,0);\">PRINT</span>";

//check AMOSUP Contract
if(is_file($docdir.$doc4.".pdf"))
	$amosup="<span $stylespangreen onclick=\"openWindow('$docdir$doc4','$doc4','500px','600px');\">VIEW</span>";
else 
	$amosup="<span $stylespanred onclick=\"openWindow('repdepartingpoea.php?title=AMOSUP Contract&type=2&ccid=$ccid&applicantno=$applicantno&print=1','amosup',0,0);\">PRINT</span>";

//check Information Sheet
if(is_file($docdir.$doc5.".pdf"))
	$infosht="<span $stylespangreen onclick=\"openWindow('$docdir$doc5','$doc5','500px','600px');\">VIEW</span>";
else 
	$infosht="<span $stylespanred onclick=\"openWindow('repdepartingpoea.php?title=Information Sheet&type=3&ccid=$ccid&applicantno=$applicantno&print=1','infosheet',0,0);\">PRINT</span>";

//check RPS
if(is_file($docdir.$doc6.".pdf"))
	$rps="<span $stylespangreen onclick=\"openWindow('$docdir$doc6','$doc6','500px','600px');\">VIEW</span>";
else 
	$rps="<span $stylespanred onclick=\"openWindow('repdepartingpoea.php?title=RPS&type=4&ccid=$ccid&applicantno=$applicantno&print=1','infosheet',0,0);\">PRINT</span>";

//check Contract Signing
if(is_file($docdir.$doc7.".pdf"))
	$consign="<span $stylespangreen onclick=\"openWindow('$docdir$doc7','$doc7','500px','600px');\">VIEW</span>";
else 
	$consign="<span $stylespanred onclick=\"alert('Wala pa!');\">PRINT</span>";

//check Tentative Vessel Line-up
if(is_file($docdir.$doc8.".pdf"))
	$tentline="<span $stylespangreen onclick=\"openWindow('$docdir$doc8','$doc8','500px','600px');\">VIEW</span>";
else 
	$tentline="<span $stylespanred onclick=\"openWindow('repdepartingpoea.php?title=Tentative Vessel Line-up&type=5&ccid=$ccid&applicantno=$applicantno&print=1','tentline',0,0);\">PRINT</span>";

//for Flight Details
$flightdtl="<span $stylespanred onclick=\"openWindow('repdepartingpoea.php?title=Flight Details&type=10&ccid=$ccid&applicantno=$applicantno&naia='+document.departstatus.naiahidden.value,'flightdetails',0,0);\">PRINT</span>";

//for Panama Application
$panamaapp="<span $stylespanred onclick=\"openWindow('repdepartingpoea.php?title=PANAMA&type=11&ccid=$ccid&applicantno=$applicantno','panamaapp',0,0);\">PRINT</span>";

//for Form218
$form218="<span $stylespanred onclick=\"openWindow('repdepartingpoea.php?title=Form 218&type=12&ccid=$ccid&applicantno=$applicantno','form218',0,0);\">PRINT</span>";

//for Issue Shoes
$issueshoes="<span $stylespanred onclick=\"openWindow('repdepartingpoea.php?title=VMC Gears&type=13&ccid=$ccid&applicantno=$applicantno','issueshoes',500,400);\">VIEW</span>";

//for Form217
$form217="<span $stylespanred onclick=\"openWindow('repdepartingpoea.php?title=Form 217&type=14&ccid=$ccid&applicantno=$applicantno','form217',0,0);\">PRINT</span>";

//for On-Signers
$onsigners="<span $stylespanred onclick=\"openWindow('repdepartingpoea.php?title=On-Signers&type=15&ccid=$ccid&applicantno=$applicantno','onsigners',0,0);\">PRINT</span>";

//for Report 201
$report201="<span $stylespanred onclick=\"openWindow('repdepartingpoea.php?title=Report 201&type=16&ccid=$ccid&applicantno=$applicantno','report201',200,350);\">PRINT</span>";

//for Report 272
$report272="<span $stylespanred onclick=\"openWindow('repdepartingpoea.php?title=Report 272&type=17&ccid=$ccid&applicantno=$applicantno','report272',0,0);\">PRINT</span>";

//for Medical Referral
$medicalreferral="<span $stylespanred onclick=\"openWindow('repdepartingpoea.php?title=Medical Referral&type=18&ccid=$ccid&applicantno=$applicantno','medicalreferral',0,0);\">PRINT</span>";

//for Submit ID Picture
//$idpicture="<span $stylespanred onclick=\"openWindow('repdepartingpoea.php?title=Submit ID Picture&type=19&ccid=$ccid&applicantno=$applicantno','idpicture',0,0);\">PRINT</span>";
$idpicture="<input type=\"checkbox\" $chkidpicture name=\"idpicture\" onclick=\"rowhidden.value='IDPICTURE';actiontxt.value='idpicture';submit();\">";

//for Submit All Certificates
//$allcertificates="<span $stylespanred onclick=\"openWindow('repdepartingpoea.php?title=Submit All Certificates&type=20&ccid=$ccid&applicantno=$applicantno','allcertificates',0,0);\">PRINT</span>";
$allcertificates="<input type=\"checkbox\" $chkallcertificates name=\"allcertificates\" onclick=\"rowhidden.value='ALLCERTIFICATES';actiontxt.value='allcertificates';submit();\">";

//for PDOS Schedule
$pdosschedule="<span $stylespanred onclick=\"openWindow('repdepartingpoea.php?title=PDOS Schedule&type=21&ccid=$ccid&applicantno=$applicantno','pdosschedule',0,0);\">PRINT</span>";

//for Update Shoes and Uniform Size
//$updateshoes="<span $stylespanred onclick=\"openWindow('repdepartingpoea.php?title=Update Shoes and Uniform Size&type=22&ccid=$ccid&applicantno=$applicantno','updateshoes',0,0);\">PRINT</span>";
$updateshoes="<span $stylespanred onclick=\"openWindow('repdepartingpoea.php?title=Update Shoes and Uniform Size&type=22&ccid=$ccid&applicantno=$applicantno','updateshoes',500,500);\">INPUT</span>";
//for Addendum to Employment Contract
$addendum="<span $stylespanred onclick=\"openWindow('repdepartingpoea.php?title=Addendum to Employment Contract&type=23&ccid=$ccid&applicantno=$applicantno','addendum',0,0);\">PRINT</span>";

//for Affidavit
$affidavit="<span $stylespanred onclick=\"openWindow('repdepartingpoea.php?title=Affidavit&type=24&ccid=$ccid&applicantno=$applicantno','affidavit',0,0);\">PRINT</span>";

//for Anti Drug Abuse Affidavit
$antidrug="<span $stylespanred onclick=\"openWindow('repdepartingpoea.php?title=Anti Drug Abuse Affidavit&type=25&ccid=$ccid&applicantno=$applicantno','antidrug',0,0);\">PRINT</span>";

//for Training Checklist
$trainingchecklist="<span $stylespanred onclick=\"openWindow('reptrainingchecklist.php?applicantno=$applicantno&rankcode=$rankcode&showprint=1','trainingchecklist',0,0);\">VIEW</span>";


echo "
<html>\n
<head>\n
<title></title>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>
<script language=\"JavaScript\"> 
function departstatusmodal(x)
{
	var docarray = new Array;
	docarray['predep']='$row1';
	docarray['preemb']='$row2';
	docarray['contract']='$row3';
	docarray['amosup']='$row4';
	docarray['infosht']='$row5';
	docarray['rps']='$row6';
	docarray['consign']='$row7';
	docarray['tentline']='$row8';
	docarray['valdocs']='Document Viewer';
	docarray['flightdetails']='$row10';
	docarray['panamaapp']='$row11';
	docarray['form218']='$row12';
	docarray['issueshoes']='$row13';
	docarray['form217']='$row14';
	docarray['onsigners']='$row15';
	docarray['report201']='$row16';
	docarray['report272']='$row17';
	docarray['medicalreferral']='$row18';
	docarray['idpicture']='$row19';
	docarray['allcertificates']='$row20';
	docarray['pdosschedule']='$row21';
	docarray['updateshoes']='$row22';
	docarray['addendum']='$row23';
	docarray['affidavit']='$row24';
	docarray['antidrug']='$row25';
	docarray['trainingchecklist']='$row26';
	
	var getexistrem=document.getElementById(x+'rem').innerHTML;
	if(getexistrem=='&nbsp;')
		getexistrem='';
		
	var getheight='250px';
	var getwidth='500px';
	var getphp='departstatusmodal';
	if(x=='valdocs')
	{
		var getheight='600px';
		var getwidth='900px';
		var getphp='documentviewer';
	}
		
	var getvalue=window.showModalDialog(getphp+'.php?remval='+getexistrem+'&putttl='+docarray[x]+'&ccid=$ccid&applicantno=$applicantno','', 
		'dialogHeight:'+getheight+'; dialogWidth:'+getwidth+';status=no');

	if(getvalue!=getexistrem)
	{
		var getarray=getvalue;
		with(document.departstatus)
		{
			remhidden.value=getvalue;
			rowhidden.value=x.toUpperCase();
			actiontxt.value='editdetail';
			submit();
		}
	}
}
</script>
<body onload=\"\" style=\"overflow:hidden;background-color:White;\">\n

<form name=\"departstatus\" method=\"POST\">\n

<span class=\"sectiontitle\" title=\"$ccid\">DEPARTING STATUS</span>
<center>
<br />
<div style=\"width:90%;height:420px;background-color:White;overflow:auto;\">
";
	$styleborder = "border-bottom:1px dashed Gray;";
	$styleby = "style=\"font-size:0.8em;font-weight:Bold;color:Blue;\"";
	
echo "
	<table style=\"width:100%;font-size:1em;\" border=1 cellspacing=\"0\" cellpadding=\"2\">
		<tr>
			<th width=\"5%\">&nbsp;</th>
			<th width=\"45%\"><u>ACTIVITY</u></th>
			<th width=\"20%\"><u>STATUS</u></th>
			<th width=\"20%\"><u>REMARKS</u></th>
			<th width=\"20%\"><u>DATE</u></th>
			<th width=\"10%\"><u>BY</u></th>
		</tr>
		<tr>
			<td colspan=\"6\"><i><b>DOCUMENTS</b></i></td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>$row1
				&nbsp;&nbsp;&nbsp;<span style=\"font-size:0.8em;font-weight:bold;cursor:pointer;color:blue;\" onclick=\"departstatusmodal('predep');\">[edit]</span>
			</td>
			<td align=\"center\">$predep</td>
			<td align=\"center\" id=\"predeprem\">$predeprem</td>
			<td align=\"center\">$predepdate</td>
			<td align=\"center\">$predepby</td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>$row2
				&nbsp;&nbsp;&nbsp;<span style=\"font-size:0.8em;font-weight:bold;cursor:pointer;color:blue;\" onclick=\"departstatusmodal('preemb');\">[edit]</span>
			</td>
			<td align=\"center\">$preemb</td>
			<td align=\"center\" id=\"preembrem\">$preembrem</td>
			<td align=\"center\">$preembdate</td>
			<td align=\"center\">$preembby</td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>POEA DOCUMENTS</td>
			<td align=\"center\">&nbsp;</td>
			<td align=\"center\">&nbsp;</td>
			<td align=\"center\">&nbsp;</td>
			<td align=\"center\">&nbsp;</td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$row3
				&nbsp;&nbsp;&nbsp;<span style=\"font-size:0.8em;font-weight:bold;cursor:pointer;color:blue;\" onclick=\"departstatusmodal('contract');\">[edit]</span>
			</td>
			<td align=\"center\">$contract</td>
			<td align=\"center\" id=\"contractrem\">$contractrem</td>
			<td align=\"center\">$contractdate</td>
			<td align=\"center\">$contractby</td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$row4
				&nbsp;&nbsp;&nbsp;<span style=\"font-size:0.8em;font-weight:bold;cursor:pointer;color:blue;\" onclick=\"departstatusmodal('amosup');\">[edit]</span>
			</td>
			<td align=\"center\">$amosup</td>
			<td align=\"center\" id=\"amosuprem\">$amosuprem</td>
			<td align=\"center\">$amosupdate</td>
			<td align=\"center\">$amosupby</td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$row5
				&nbsp;&nbsp;&nbsp;<span style=\"font-size:0.8em;font-weight:bold;cursor:pointer;color:blue;\" onclick=\"departstatusmodal('infosht');\">[edit]</span>
			</td>
			<td align=\"center\">$infosht</td>
			<td align=\"center\" id=\"infoshtrem\">$infoshtrem</td>
			<td align=\"center\">$infoshtdate</td>
			<td align=\"center\">$infoshtby</td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$row6
				&nbsp;&nbsp;&nbsp;<span style=\"font-size:0.8em;font-weight:bold;cursor:pointer;color:blue;\" onclick=\"departstatusmodal('rps');\">[edit]</span>
			</td>
			<td align=\"center\">$rps</td>
			<td align=\"center\" id=\"rpsrem\">$rpsrem</td>
			<td align=\"center\">$rpsdate</td>
			<td align=\"center\">$rpsby</td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$row23
				&nbsp;&nbsp;&nbsp;<span style=\"font-size:0.8em;font-weight:bold;cursor:pointer;color:blue;\" onclick=\"departstatusmodal('addendum');\">[edit]</span>
			</td>
			<td align=\"center\">$addendum</td>
			<td align=\"center\" id=\"addendumrem\">$addendumrem</td>
			<td align=\"center\">$addendumdate</td>
			<td align=\"center\">$addendumby</td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$row24
				&nbsp;&nbsp;&nbsp;<span style=\"font-size:0.8em;font-weight:bold;cursor:pointer;color:blue;\" onclick=\"departstatusmodal('affidavit');\">[edit]</span>
			</td>
			<td align=\"center\">$affidavit</td>
			<td align=\"center\" id=\"affidavitrem\">$affidavitrem</td>
			<td align=\"center\">$affidavitdate</td>
			<td align=\"center\">$affidavitby</td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$row25
				&nbsp;&nbsp;&nbsp;<span style=\"font-size:0.8em;font-weight:bold;cursor:pointer;color:blue;\" onclick=\"departstatusmodal('antidrug');\">[edit]</span>
			</td>
			<td align=\"center\">$antidrug</td>
			<td align=\"center\" id=\"antidrugrem\">$antidrugrem</td>
			<td align=\"center\">$antidrugdate</td>
			<td align=\"center\">$antidrugby</td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>$row7
				&nbsp;&nbsp;&nbsp;<span style=\"font-size:0.8em;font-weight:bold;cursor:pointer;color:blue;\" onclick=\"departstatusmodal('consign');\">[edit]</span>
			</td>
			<td align=\"center\">$consign</td>
			<td align=\"center\" id=\"consignrem\">$consignrem</td>
			<td align=\"center\">$consigndate</td>
			<td align=\"center\">$consignby</td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>$row8
				&nbsp;&nbsp;&nbsp;<span style=\"font-size:0.8em;font-weight:bold;cursor:pointer;color:blue;\" onclick=\"departstatusmodal('tentline');\">[edit]</span>
			</td>
			<td align=\"center\">$tentline</td>
			<td align=\"center\" id=\"tentlinerem\">$tentlinerem</td>
			<td align=\"center\">$tentlinedate</td>
			<td align=\"center\">$tentlineby</td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>$row9
				&nbsp;&nbsp;&nbsp;<span style=\"font-size:0.8em;font-weight:bold;cursor:pointer;color:blue;\" onclick=\"departstatusmodal('valdocs');\">[edit]</span>
			</td>
			<td align=\"center\">
				<span $stylespanred onclick=\"departstatusmodal('valdocs');\">CHECK</span>
			</td>
			<td align=\"center\" id=\"valdocsrem\">$valdocsrem</td>
			<td align=\"center\">$valdocsdate</td>
			<td align=\"center\">$valdocsby</td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>$row10
				&nbsp;&nbsp;&nbsp;<span style=\"font-size:0.8em;font-weight:bold;cursor:pointer;color:blue;\" 
				onclick=\"departstatusmodal('flightdetails');\">[edit]</span>&nbsp;&nbsp;&nbsp;
				<input type=\"radio\" name=\"rdselect\" $NAIA1 onclick=\"naiahidden.value=this.value\" value=\"NAIA1\">&nbsp;NAIA 1
				<input type=\"radio\" name=\"rdselect\" $NAIA2 onclick=\"naiahidden.value=this.value\" value=\"NAIA2\">&nbsp;NAIA 2
			</td>
			<td align=\"center\">$flightdtl</td>
			<td align=\"center\" id=\"flightdetailsrem\">$flightdtlrem</td>
			<td align=\"center\">$flightdtldate</td>
			<td align=\"center\">$flightdtlby</td>
		</tr>
		<!--
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>$row14
				&nbsp;&nbsp;&nbsp;<span style=\"font-size:0.8em;font-weight:bold;cursor:pointer;color:blue;\" 
				onclick=\"departstatusmodal('form217');\">[edit]</span>
			</td>
			<td align=\"center\">$form217</td>
			<td align=\"center\" id=\"form217rem\">$form217rem</td>
			<td align=\"center\">$form217date</td>
			<td align=\"center\">$form217by</td>
		</tr>
		-->
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>$row15
				&nbsp;&nbsp;&nbsp;<span style=\"font-size:0.8em;font-weight:bold;cursor:pointer;color:blue;\" 
				onclick=\"departstatusmodal('onsigners');\">[edit]</span>
			</td>
			<td align=\"center\">$onsigners</td>
			<td align=\"center\" id=\"onsignersrem\">$onsignersrem</td>
			<td align=\"center\">$onsignersdate</td>
			<td align=\"center\">$onsignersby</td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>$row18
				&nbsp;&nbsp;&nbsp;<span style=\"font-size:0.8em;font-weight:bold;cursor:pointer;color:blue;\" 
				onclick=\"departstatusmodal('medicalreferral');\">[edit]</span>
			</td>
			<td align=\"center\">$medicalreferral</td>
			<td align=\"center\" id=\"medicalreferralrem\">$medicalreferralrem</td>
			<td align=\"center\">$medicalreferraldate</td>
			<td align=\"center\">$medicalreferralby</td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>$row19
				&nbsp;&nbsp;&nbsp;
				<!--
				<span style=\"font-size:0.8em;font-weight:bold;cursor:pointer;color:blue;\" 
				onclick=\"departstatusmodal('idpicture');\">[edit]</span>
				-->
			</td>
			<td align=\"center\">$idpicture</td>
			<td align=\"center\" id=\"idpicturerem\">$idpicturerem</td>
			<td align=\"center\">$idpicturedate</td>
			<td align=\"center\">$idpictureby</td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>$row20
				&nbsp;&nbsp;&nbsp;
				<!--
				<span style=\"font-size:0.8em;font-weight:bold;cursor:pointer;color:blue;\" 
				onclick=\"departstatusmodal('allcertificates');\">[edit]</span>
				-->
			</td>
			<td align=\"center\">$allcertificates</td>
			<td align=\"center\" id=\"allcertificatesrem\">$allcertificatesrem</td>
			<td align=\"center\">$allcertificatesdate</td>
			<td align=\"center\">$allcertificatesby</td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>$row21
				&nbsp;&nbsp;&nbsp;<span style=\"font-size:0.8em;font-weight:bold;cursor:pointer;color:blue;\" 
				onclick=\"departstatusmodal('pdosschedule');\">[edit]</span>
			</td>
			<td align=\"center\">$pdosschedule</td>
			<td align=\"center\" id=\"pdosschedulerem\">$pdosschedulerem</td>
			<td align=\"center\">$pdosscheduledate</td>
			<td align=\"center\">$pdosscheduleby</td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>$row22
				&nbsp;&nbsp;&nbsp;<span style=\"font-size:0.8em;font-weight:bold;cursor:pointer;color:blue;\" 
				onclick=\"departstatusmodal('updatesshoes');\">[edit]</span>
			</td>
			<td align=\"center\">$updateshoes</td>
			<td align=\"center\" id=\"updatesshoesrem\">$updatesshoesrem</td>
			<td align=\"center\">$updatesshoesdate</td>
			<td align=\"center\">$updatesshoesby</td>
		</tr>
		";
		if($flagcode=="PA")
		{
			echo "
			<tr $mouseovereffect>
				<td>&nbsp;</td>
				<td>$row11
					&nbsp;&nbsp;&nbsp;<span style=\"font-size:0.8em;font-weight:bold;cursor:pointer;color:blue;\" 
					onclick=\"departstatusmodal('panamaapp');\">[edit]</span>
				</td>
				<td align=\"center\">&nbsp;</td>
				<td align=\"center\" id=\"panamaapprem\">$panamaapprem</td>
				<td align=\"center\">$panamaappdate</td>
				<td align=\"center\">$panamaappby</td>
			</tr>";
		}
		echo "
		<tr>
			<td colspan=\"6\"><i><b>ACCOUNTING</b></i></td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>$row12
				&nbsp;&nbsp;&nbsp;<span style=\"font-size:0.8em;font-weight:bold;cursor:pointer;color:blue;\" 
				onclick=\"departstatusmodal('form218');\">[edit]</span>
			</td>
			<td align=\"center\">&nbsp;</td>
			<td align=\"center\" id=\"form218rem\">$form218rem</td>
			<td align=\"center\">$form218date</td>
			<td align=\"center\">$form218by</td>
		</tr>
		<tr>
			<td colspan=\"6\"><i><b>ADMIN</b></i></td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>$row13
				&nbsp;&nbsp;&nbsp;<span style=\"font-size:0.8em;font-weight:bold;cursor:pointer;color:blue;\" 
				onclick=\"departstatusmodal('issueshoes');\">[edit]</span>
			</td>
			<td align=\"center\">$issueshoes</td>
			<td align=\"center\" id=\"issueshoesrem\">$issueshoesrem</td>
			<td align=\"center\">$issueshoesdate</td>
			<td align=\"center\">$issueshoesby</td>
		</tr>
		<tr>
			<td colspan=\"6\"><i><b>TRAINING</b></i></td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>$row26
				&nbsp;&nbsp;&nbsp;<span style=\"font-size:0.8em;font-weight:bold;cursor:pointer;color:blue;\" 
				onclick=\"departstatusmodal('trainingchecklist');\">[edit]</span>
			</td>
			<td align=\"center\">$trainingchecklist</td>
			<td align=\"center\" id=\"trainingchecklistrem\">$trainingchecklistrem</td>
			<td align=\"center\">$trainingchecklistdate</td>
			<td align=\"center\">$trainingchecklistby</td>
		</tr>
		<tr>
			<td colspan=\"6\"><i><b>DOCS STAFF</b></i></td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>$row16
				&nbsp;&nbsp;&nbsp;<span style=\"font-size:0.8em;font-weight:bold;cursor:pointer;color:blue;\" 
				onclick=\"departstatusmodal('report201');\">[edit]</span>
			</td>
			<td align=\"center\">$report201</td>
			<td align=\"center\" id=\"report201rem\">$report201rem</td>
			<td align=\"center\">$report201date</td>
			<td align=\"center\">$report201by</td>
		</tr>
		<tr $mouseovereffect>
			<td>&nbsp;</td>
			<td>$row17
				&nbsp;&nbsp;&nbsp;<span style=\"font-size:0.8em;font-weight:bold;cursor:pointer;color:blue;\" 
				onclick=\"departstatusmodal('report272');\">[edit]</span>
			</td>
			<td align=\"center\">$report272</td>
			<td align=\"center\" id=\"report272rem\">$report272rem</td>
			<td align=\"center\">$report272date</td>
			<td align=\"center\">$report272by</td>
		</tr>
	</table>
	<br />
	
";
	
echo "
</div>
<div style=\"width:90%;background-color:White;\">
	<center>
		<input type=\"button\" value=\"Refresh\" style=\"border:1px solid Orange;background-color:Blue;color:Yellow;font-weight:Bold;font-size:1em;\"
			onclick=\"applicantno.value='$applicantno';submit();\" / >
	</center>
</div>
</center>

<div id=\"editwindow\" 
	style=\"background-color:Black;border:2px solid Red;z-index:200;position:absolute;left:250;top:25;
		border:3px solid black;display:none;\">
	
	<iframe marginwidth=0 marginheight=0 id=\"editfile\" frameborder=\"0\" name=\"content\" src=\"\" scrolling=\"auto\" 
		style=\"width:100%;height:100%;\">
	</iframe>
	
	<input type=\"button\" value=\"Close\" onclick=\"document.getElementById('editwindow').style.display='none';applicantno.value='$applicantno';submit();\" />
		
</div>

	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"applicantno\" />
	<input type=\"hidden\" name=\"remhidden\" />
	<input type=\"hidden\" name=\"rowhidden\" />
	<input type=\"hidden\" name=\"naiahidden\" value=\"$rdselect\"/>

</form>

</body>
</html>

";
?>