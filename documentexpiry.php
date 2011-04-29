<?php

//include('connectdb.php');
include('veritas/connectdb.php');
include("veritas/include/stylephp.inc");
include("veritas/include/functions.inc");

session_start();

$basedir = "idpics/";
$basedirdocs = "docimg/";
// $basedirdocs = "docimages/";

$currentdate = date('Y-m-d H:i:s');
$showmultiple = "display:none;";
$multiple = 0;

$double = 0;


if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
else 
	$employeeid = ""; 

if (isset($_POST['applicantno']))
	$applicantno = $_POST['applicantno'];
	
if (empty($applicantno))
	$disablebtnnext = "disabled=\"disabled\"";
	
if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_POST['currentpage']))
	$currentpage = $_POST['currentpage'];
	
if (empty($currentpage))
	$currentpage = 0;

$currentpagediv = "div"	. $currentpage;
$$currentpagediv = "display:block;";
	
	
if (isset($_POST["searchkey"]))
	$searchkey = $_POST["searchkey"];
	
if (isset($_POST["searchby"]))
	$searchby = $_POST["searchby"];
	
if ($searchkey == "")
	$disablesearch = "disabled=\"disabled\"";
else 
	$disablesearch = "";


	
//PERSONAL

if (isset($_POST['crewcode']))
	$crewcode = $_POST['crewcode'];
	
if (isset($_POST['fname']))
	$fname = $_POST['fname'];
	
if (isset($_POST['gname']))
	$gname = $_POST['gname'];

if (isset($_POST['mname']))
	$mname = $_POST['mname'];
	
if (isset($_POST['crewaddress']))
	$crewaddress = $_POST['crewaddress'];

if (isset($_POST['crewbarangay']))
	$crewbarangay = $_POST['crewbarangay'];
	
if (isset($_POST['crewcity']))
	$crewcity = $_POST['crewcity'];

if (isset($_POST['crewprovince']))
	$crewprovince = $_POST['crewprovince'];
	
if (isset($_POST['crewzipcode']))
	$crewzipcode = $_POST['crewzipcode'];
	
if (isset($_POST['crewtelno']))
	$crewtelno = $_POST['crewtelno'];

	
if (isset($_POST['crewprovaddress']))
	$crewprovaddress = $_POST['crewprovaddress'];

if (isset($_POST['crewprovbarangay']))
	$crewprovbarangay = $_POST['crewprovbarangay'];
	
if (isset($_POST['crewprovcity']))
	$crewprovcity = $_POST['crewprovcity'];

if (isset($_POST['crewprovprovince']))
	$crewprovprovince = $_POST['crewprovprovince'];

if (isset($_POST['crewprovzipcode']))
	$crewprovzipcode = $_POST['crewprovzipcode'];
	
if (isset($_POST['crewprovtelno']))
	$crewprovtelno = $_POST['crewprovtelno'];
	
if (isset($_POST['crewbdate']))
	$crewbdate = $_POST['crewbdate'];
	
if (isset($_POST['crewbplace']))
	$crewbplace = $_POST['crewbplace'];	
	
if (isset($_POST['crewcelphone1']))
	$crewcelphone1 = $_POST['crewcelphone1'];
	
if (isset($_POST['crewcelphone2']))
	$crewcelphone2 = $_POST['crewcelphone2'];
	
if (isset($_POST['crewcelphone3']))
	$crewcelphone3 = $_POST['crewcelphone3'];

if (isset($_POST['crewgender']))
	$crewgender = $_POST['crewgender'];
else 
	$crewgender = "M";
	
if (isset($_POST['crewcivilstatus']))
	$crewcivilstatus = $_POST['crewcivilstatus'];

if (isset($_POST['crewnationality']))
	$crewnationality = $_POST['crewnationality'];

if (isset($_POST['crewreligion']))
	$crewreligion = $_POST['crewreligion'];

if (isset($_POST['crewsss']))
	$crewsss = $_POST['crewsss'];

if (isset($_POST['crewtin']))
	$crewtin = $_POST['crewtin'];

if (isset($_POST['crewweight']))
	$crewweight = $_POST['crewweight'];

if (isset($_POST['crewheight']))
	$crewheight = $_POST['crewheight'];

if (isset($_POST['crewshoesize']))
	$crewshoesize = $_POST['crewshoesize'];

if (isset($_POST['crewemail']))
	$crewemail = $_POST['crewemail'];

if (isset($_POST['crewrecommendedby']))
	$crewrecommendedby = $_POST['crewrecommendedby'];

//FAMILY

	
if (isset($_POST['familyfname']))
	$familyfname = $_POST['familyfname'];
	
if (isset($_POST['familygname']))
	$familygname = $_POST['familygname'];
	
if (isset($_POST['familymname']))
	$familymname = $_POST['familymname'];
	
if (isset($_POST['familyrelcode']))
	$familyrelcode = $_POST['familyrelcode'];
	
if (isset($_POST['familybdate']))
	$familybdate = $_POST['familybdate'];
	
if (isset($_POST['familygender']))
	$familygender = $_POST['familygender'];
else 
	$familygender = "M";
	
if (isset($_POST['familyaddress']))
	$familyaddress = $_POST['familyaddress'];
	
if (isset($_POST['familyprovince']))
	$familyprovince = $_POST['familyprovince'];
	
if (isset($_POST['familycity']))
	$familycity = $_POST['familycity'];

if (isset($_POST['familybarangay']))
	$familybarangay = $_POST['familybarangay'];
	
if (isset($_POST['familyzipcode']))
	$familyzipcode = $_POST['familyzipcode'];
	
if (isset($_POST['familycountrycode']))
	$familycountrycode = $_POST['familycountrycode'];
else 
	$familycountrycode = "PH";
	
if (isset($_POST['familytelno']))
	$familytelno = $_POST['familytelno'];
	
if (isset($_POST['familyemail']))
	$familyemail = $_POST['familyemail'];
	
if (isset($_POST['familyoccupation']))
	$familyoccupation = $_POST['familyoccupation'];

if (isset($_POST['familyjobstatus']))
	$familyjobstatus = $_POST['familyjobstatus'];

if (isset($_POST['familydependent']))
	$familydependent = $_POST['familydependent'];

if (isset($_POST['familyabroad']))
	$familyabroad = $_POST['familyabroad'];

if (isset($_POST['familyrelothers']))
	$familyrelothers = $_POST['familyrelothers'];

//EDUCATION

if (isset($_POST['educschoolid']))
	$educschoolid = $_POST['educschoolid'];
	
if (isset($_POST['educschoolothers']))
	$educschoolothers = $_POST['educschoolothers'];
	
if (isset($_POST['educcourseid']))
	$educcourseid = $_POST['educcourseid'];
	
if (isset($_POST['educcourseothers']))
	$educcourseothers = $_POST['educcourseothers'];
	
if (isset($_POST['educaddress']))
	$educaddress = $_POST['educaddress'];
	
if (isset($_POST['educcontactno']))
	$educcontactno = $_POST['educcontactno'];
	
if (isset($_POST['educcontactperson']))
	$educcontactperson = $_POST['educcontactperson'];
	
if (isset($_POST['educdategrad']))
	$educdategrad = $_POST['educdategrad'];
	
if (isset($_POST['educlevel']))
	$educlevel = $_POST['educlevel'];
	
if (isset($_POST['educhonors']))
	$educhonors = $_POST['educhonors'];
	
//DOCUMENTS

if (isset($_POST['docno']))
	$docno = $_POST['docno'];
	
if (isset($_POST['doccode']))
	$doccode = $_POST['doccode'];
	
if (isset($_POST['docrankcode']))
	$docrankcode = $_POST['docrankcode'];
	
if (isset($_POST['docissued']))
	$docissued = $_POST['docissued'];
	
if (isset($_POST['docexpired']))
	$docexpired = $_POST['docexpired'];
	
//LICENSE

if (isset($_POST['liccode']))
	$liccode = $_POST['liccode'];

if (isset($_POST['licno']))
	$licno = $_POST['licno'];

if (isset($_POST['licrankcode']))
	$licrankcode = $_POST['licrankcode'];

if (isset($_POST['licissued']))
	$licissued = $_POST['licissued'];

if (isset($_POST['licexpired']))
	$licexpired = $_POST['licexpired'];


if (isset($_POST['certno']))
	$certno = $_POST['certno'];
	
if (isset($_POST['certcode']))
	$certcode = $_POST['certcode'];
	
if (isset($_POST['certrankcode']))
	$certrankcode = $_POST['certrankcode'];

if (isset($_POST['certissued']))
	$certissued = $_POST['certissued'];
	
	
//EXPERIENCE

if (isset($_POST['expvessel']))
	$expvessel = $_POST['expvessel'];

if (isset($_POST['expenginetype']))
	$expenginetype = $_POST['expenginetype'];

if (isset($_POST['expflagcode']))
	$expflagcode = $_POST['expflagcode'];

if (isset($_POST['expflagothers']))
	$expflagothers = $_POST['expflagothers'];

if (isset($_POST['expvesseltypecode']))
	$expvesseltypecode = $_POST['expvesseltypecode'];

if (isset($_POST['expvsltypeothers']))
	$expvsltypeothers = $_POST['expvsltypeothers'];

if (isset($_POST['exprankcode']))
	$exprankcode = $_POST['exprankcode'];

if (isset($_POST['exptraderoutecode']))
	$exptraderoutecode = $_POST['exptraderoutecode'];

if (isset($_POST['exptradeothers']))
	$exptradeothers = $_POST['exptradeothers'];

if (isset($_POST['expgrosston']))
	$expgrosston = $_POST['expgrosston'];

if (isset($_POST['expdeadwt']))
	$expdeadwt = $_POST['expdeadwt'];

if (isset($_POST['expcargo']))
	$expcargo = $_POST['expcargo'];

if (isset($_POST['expmanningcode']))
	$expmanningcode = $_POST['expmanningcode'];

if (isset($_POST['expmanningothers']))
	$expmanningothers = $_POST['expmanningothers'];

if (isset($_POST['expembdate']))
	$expembdate = $_POST['expembdate'];

if (isset($_POST['expdisembdate']))
	$expdisembdate = $_POST['expdisembdate'];

if (isset($_POST['expdisembreasoncode']))
	$expdisembreasoncode = $_POST['expdisembreasoncode'];

if (isset($_POST['expreasonothers']))
	$expreasonothers = $_POST['expreasonothers'];

//EMPLOYMENT

if (isset($_POST['empemployer']))
	$empemployer = $_POST['empemployer'];

if (isset($_POST['empposition']))
	$empposition = $_POST['empposition'];

if (isset($_POST['emptelno']))
	$emptelno = $_POST['emptelno'];

if (isset($_POST['empdatefrom']))
	$empdatefrom = $_POST['empdatefrom'];

if (isset($_POST['empdateto']))
	$empdateto = $_POST['empdateto'];

if (isset($_POST['empsalary']))
	$empsalary = $_POST['empsalary'];

if (isset($_POST['empovertime']))
	$empovertime = $_POST['empovertime'];

if (isset($_POST['empreason']))
	$empreason = $_POST['empreason'];

//MEDICAL

if (isset($_POST['medclinicid']))
	$medclinicid = $_POST['medclinicid'];

if (isset($_POST['medcheckupdate']))
	$medcheckupdate = $_POST['medcheckupdate'];

if (isset($_POST['meddiagnosis']))
	$meddiagnosis = $_POST['meddiagnosis'];

if (isset($_POST['medhbatest']))
{
	$medhbatest = 1;
	$chkhbatest = "checked=\"checked\"";
}
else 
{
	$medhbatest = 0;
	$chkhbatest = "";
}
	

if (isset($_POST['medrecommendcode']))
	$medrecommendcode = $_POST['medrecommendcode'];

if (isset($_POST['medremarks']))
	$medremarks = $_POST['medremarks'];

	
//POSTS FOR DELETION/EDIT

if (isset($_POST['deleteid']))
	$deleteid = $_POST['deleteid'];

if (isset($_POST['editid']))
	$editid = $_POST['editid'];

//END OF POSTS FOR DELETION/EDIT


//********************************************************  SAVING SECTION  ***************************************************//
	
switch ($actiontxt)
{
	case "checkcrewcode":
		
			$qrycheckcrewcode = mysql_query("SELECT APPLICANTNO,FNAME,GNAME,MNAME FROM crew WHERE CREWCODE='$crewcode'") or die(mysql_error());
			
			if (mysql_num_rows($qrycheckcrewcode) > 0)
			{
				$rowcheckcrewcode = mysql_fetch_array($qrycheckcrewcode);
				$xappno = $rowcheckcrewcode["APPLICANTNO"];
				$xfname = $rowcheckcrewcode["FNAME"];
				$xgname = $rowcheckcrewcode["GNAME"];
				$xmname = $rowcheckcrewcode["MNAME"];
				
				echo "
				<script>
					alert('Crew Code $crewcode already exists. ($xfname, $xgname $xmname)');
				</script>";
				
				$double = 1;
			}
		
		break;
	
	case "save"		:
			
			$crewbdateraw = date('Y-m-d',strtotime($crewbdate));
			
			$qrycrewsave = mysql_query("UPDATE crew SET
								FNAME = '$fname',
								GNAME = '$gname',
								MNAME = '$mname',
								ADDRESS = '$crewaddress',
								PROVINCECODE='$crewprovince',
								TOWNCODE = '$crewcity',
								BARANGAYCODE='$crewbarangay',
								ZIPCODE = '$crewzipcode',
								TELNO = '$crewtelno',
								
								PROVADDRESS = '$crewprovaddress',
								PROVPROVCODE='$crewprovprovince',
								PROVTOWNCODE = '$crewprovcity',
								PROVBRGYCODE='$crewprovbarangay',
								PROVZIPCODE = '$crewprovzipcode',
								PROVTELNO = '$crewprovtelno',
								
								BIRTHDATE = '$crewbdateraw',
								BIRTHPLACE = '$crewbplace',
								CIVILSTATUS = '$crewcivilstatus',
								RELIGION = '$crewreligion',
								WEIGHT = '$crewweight',
								HEIGHT = '$crewheight',
								SIZESHOES = '$crewshoesize',
								GENDER = '$crewgender',
								EMAIL = '$crewemail',
								SSS = '$crewsss',
								TIN = '$crewtin',
								RECOMMENDEDBY = '$crewrecommendedby',
								
								CEL1 = '$crewcelphone1',
								CEL2 = '$crewcelphone2',
								CEL3 = '$crewcelphone3',
								
								CREWCODE = '$crewcode'
								
								WHERE APPLICANTNO=$applicantno					
						") or die(mysql_error());
			
		break;
		
	case "cancel"	:
			
			$editmode = "";
		
			switch ($currentpage)
			{
				case "0": //PERSONAL
					
					break;
				case "1": //FAMILY
						
						$familyrelcode = "";
						$familyfname = "";
						$familygname = "";
						$familymname = "";
						$familyaddress = "";
						$familyprovince = "";
						$familybarangay = "";
						$familycity = "";
						$familyzipcode = "";
						$familycountrycode = "PH";
						$familybdate = "";
						$familytelno = "";
						$familyjobstatus = "";
						$familyoccupation = "";
						$familydependent = "";
						$familyrelothers = "";
						$familygender = "";
						$familyabroad = "";				
						$familyemail = "";	
						
					break;
				case "2": //EDUCATION
				
						$educschoolid = "";
						$educschoolothers = "";
						$educcourseid = "";
						$educcourseothers = "";
						$educaddress = "";
						$educcontactno = "";
						$educcontactperson = "";
						$educdategrad = "";
						$educlevel = "";
						$educhonors = "";
						
					break;
				case "3": //DOCUMENTS
						
						$doccode = "";
						$docrankcode = "";
						$docno = "";
						$docissued = "";
						$docexpired = "";
				
					break;
				case "4": //LICENSE
						
						$liccode = "";
						$licrankcode = "";
						$licno = "";
						$licissued = "";
						$licexpired = "";
				
					break;
				case "5": //CERTIFICATE
						
						$certcode = "";
						$certrankcode = "";
						$certno = "";
						$certissued = "";
				
					break;
				case "6": //EXPERIENCE
						
						$expvessel = "";
						$expenginetype = "";
						$expflagcode = "";
						$expvesseltypecode = "";
						$exprankcode = "";
						$exptraderoutecode = "";
						$expgrosston = "";
						$expdeadwt = "";
						$expcargo = "";
						$expmanningcode = "";
						$expmanningothers = "";
						$expembdate = "";
						$expdisembdate = "";
						$expdisembreasoncode = "";
						$expreasonothers = "";
				
					break;
				case "7": //EMPLOYMENT
						
						$empemployer = "";
						$empposition = "";
						$emptelno = "";
						$empdatefrom = "";
						$empdateto = "";
						$empsalary = "";
						$empovertime = "";
						$empreason = "";
				
					break;		
				case "8": //EMPLOYMENT
						
						$medclinicid = "";
						$medcheckupdate = "";
						$meddiagnosis = "";
						$medhbatest = "";
						$medrecommendcode = "";
						$medremarks = "";
						$medhbatest = 0;
						$chkhbatest = "";
				
					break;		
			}
		
		break;
		
	case "editsave"	:
			$editmode = "";
			
			switch ($currentpage)
			{
				case "0": //PERSONAL
					
					break;
				case "1": //FAMILY
						
						$familybdateraw = date('Y-m-d',strtotime($familybdate));
						
						if ($familydependent == "")
							$familydependent = 0;
							
						if ($familyabroad == "")
							$familyabroad = 0;
								
//						echo "UPDATE crewfamily SET 
//															RELCODE='$familyrelcode',
//															FNAME='$familyfname',
//															GNAME='$familygname',
//															MNAME='$familymname',
//															ADDRESS='$familyaddress',
//															PROVINCECODE='$familyprovince',
//															TOWNCODE='$familycity',
//															BARANGAYCODE='$familybarangay',
//															CITY='$familycity',
//															ZIPCODE='$familyzipcode',
//															COUNTRYCODE='$familycountrycode',
//															BIRTHDATE='$familybdateraw',
//															TELNO='$familytelno',
//															JOBSTATUS='$familyjobstatus',
//															OCCUPATION='$familyoccupation',
//															DEPENDENT=$familydependent,
//															OTHERRELATION='$familyrelothers',
//															GENDER='$familygender',
//															ABROAD=$familyabroad,
//															EMAIL='$familyemail'
//														WHERE APPLICANTNO=$applicantno AND CREWRELID=$editid
//											";
						
						$qryfamilyeditsave = mysql_query("UPDATE crewfamily SET 
															RELCODE='$familyrelcode',
															FNAME='$familyfname',
															GNAME='$familygname',
															MNAME='$familymname',
															ADDRESS='$familyaddress',
															PROVINCECODE='$familyprovince',
															TOWNCODE='$familycity',
															BARANGAYCODE='$familybarangay',
															CITY='$familycity',
															ZIPCODE='$familyzipcode',
															COUNTRYCODE='$familycountrycode',
															BIRTHDATE='$familybdateraw',
															TELNO='$familytelno',
															JOBSTATUS='$familyjobstatus',
															OCCUPATION='$familyoccupation',
															DEPENDENT=$familydependent,
															OTHERRELATION='$familyrelothers',
															GENDER='$familygender',
															ABROAD=$familyabroad,
															EMAIL='$familyemail'
														WHERE APPLICANTNO=$applicantno AND CREWRELID=$editid
											") or die(mysql_error());
						
						$familyrelcode = "";
						$familyfname = "";
						$familygname = "";
						$familymname = "";
						$familyaddress = "";
						$familyprovince = "";
						$familycity = "";
						$familybarangay = "";
						$familyzipcode = "";
						$familycountrycode = "PH";
						$familybdate = "";
						$familytelno = "";
						$familyjobstatus = "";
						$familyoccupation = "";
						$familydependent = "";
						$familyrelothers = "";
						$familygender = "";
						$familyabroad = "";				
						$familyemail = "";	
						
					break;
				case "2": //EDUCATION
						
						$educdategradraw = date('Y-m-d',strtotime($educdategrad));
						
						if($educschoolid == "")
							$educschoolid = "NULL";
							
						if($educcourseid == "")
							$educcourseid = "NULL";
						
						$qryeduceditsave = mysql_query("UPDATE creweducation SET
															SCHOOLID=$educschoolid,
															SCHOOLOTHERS='$educschoolothers',
															COURSEID=$educcourseid,
															COURSEOTHERS='$educcourseothers',
															DATEGRADUATED='$educdategradraw',
															ADDRESS='$educaddress',
															CONTACTNO='$educcontactno',
															CONTACTPERSON='$educcontactperson',
															LEVEL='$educlevel',
															HONORS='$educhonors'
														WHERE APPLICANTNO=$applicantno AND IDNO=$editid
											") or die(mysql_error());
				
						$educschoolid = "";
						$educschoolothers = "";
						$educcourseid = "";
						$educcourseothers = "";
						$educaddress = "";
						$educcontactno = "";
						$educcontactperson = "";
						$educdategrad = "";
						$educlevel = "";
						$educhonors = "";
						
					break;
				case "3": //DOCUMENTS
				
						$docissuedraw = "'" . date('Y-m-d',strtotime($docissued)) . "'";
						if (!empty($docexpired))
							$docexpiredraw = "'" . date('Y-m-d',strtotime($docexpired)) . "'";
						else 
							$docexpiredraw = "NULL";
			
						$qrydoceditsave = mysql_query("UPDATE crewdocstatus SET
																DOCCODE='$doccode',
																RANKCODE='$docrankcode',
																DATEISSUED=$docissuedraw,
																DATEEXPIRED=$docexpiredraw,
																DOCNO='$docno'
														WHERE APPLICANTNO=$applicantno AND IDNO=$editid
											") or die(mysql_error());
						
						$doccode = "";
						$docrankcode = "";
						$docno = "";
						$docissued = "";
						$docexpired = "";
				
					break;
				case "4": //LICENSE
					
						$licissuedraw = "'" . date('Y-m-d',strtotime($licissued)) . "'";
						
						if (!empty($licexpired))
							$licexpiredraw = "'" . date('Y-m-d',strtotime($licexpired)) . "'";
						else 
							$licexpiredraw = "NULL";
			
						$qryliceditsave = mysql_query("UPDATE crewdocstatus SET
																DOCCODE='$liccode',
																RANKCODE='$licrankcode',
																DATEISSUED=$licissuedraw,
																DATEEXPIRED=$licexpiredraw,
																DOCNO='$licno'
														WHERE APPLICANTNO=$applicantno AND IDNO=$editid
											") or die(mysql_error());
						
						$liccode = "";
						$licrankcode = "";
						$licno = "";
						$licissued = "";
						$licexpired = "";
				
					break;
				case "5": //CERTIFICATE
				
						$certissuedraw = "'" . date('Y-m-d',strtotime($certissued)) . "'";
			
						$qrycerteditsave = mysql_query("UPDATE crewcertstatus SET
																DOCCODE='$certcode',
																RANKCODE='$certrankcode',
																DOCNO='$certno',
																DATEISSUED=$certissuedraw
														WHERE APPLICANTNO=$applicantno AND IDNO=$editid
											") or die(mysql_error());
						
						$certcode = "";
						$certrankcode = "";
						$certno = "";
						$certissued = "";
				
					break;
				case "6": //EXPERIENCE
				
						$expdisembdateraw = date('Y-m-d',strtotime($expdisembdate));
						$expembdateraw = date('Y-m-d',strtotime($expembdate));
			
						if ($expgrosston == "")
							$expgrosston = 0;
						
						if ($expdeadwt == "")
							$expdeadwt = 0;
						
						$qryexpeditsave = mysql_query("UPDATE crewexperience SET
															VESSEL='$expvessel',
															ENGINETYPE='$expenginetype',
															FLAGCODE='$expflagcode',
															VESSELTYPECODE='$expvesseltypecode',
															RANKCODE='$exprankcode',
															TRADEROUTECODE='$exptraderoutecode',
															GROSSTON=$expgrosston,
															CARGOKIND='$expcargo',
															MANNINGCODE='$expmanningcode',
															MANNINGOTHERS='$expmanningothers',
															DATEEMB='$expembdateraw',
															DATEDISEMB='$expdisembdateraw',
															DISEMBREASONCODE='$expdisembreasoncode',
															REASONOTHERS='$expreasonothers',
															FLAGOTHERS='$expflagothers',
															VESSELTYPEOTHERS='$expvsltypeothers',
															TRADEROUTEOTHERS='$exptradeothers'
													WHERE APPLICANTNO=$applicantno AND IDNO=$editid
											") or die(mysql_error());
						
						$expvessel = "";
						$expenginetype = "";
						$expflagcode = "";
						$expvesseltypecode = "";
						$exprankcode = "";
						$exptraderoutecode = "";
						$expgrosston = "";
						$expcargo = "";
						$expmanningcode = "";
						$expmanningothers = "";
						$expembdate = "";
						$expdisembdate = "";
						$expdisembreasoncode = "";
						$expreasonothers = "";
						
						$expflagothers = "";
						$expvsltypeothers = "";
						$exptradeothers = "";
				
					break;
				case "7": //EMPLOYMENT
				
						$empdatefromraw = date('Y-m-d',strtotime($empdatefrom));
						$empdatetoraw = date('Y-m-d',strtotime($empdateto));
			
						if ($empsalary == "")
							$empsalary = 0;
							
						if ($empovertime == "")
							$empovertime = 0;
						
						$qryempeditsave = mysql_query("UPDATE employhistory SET
															EMPLOYER='$empemployer',
															POSITION='$empposition',
															TELNO='$emptelno',
															DATEFROM='$empdatefromraw',
															DATETO='$empdatetoraw',
															REASON='$empreason'
														WHERE APPLICANTNO=$applicantno AND IDNO=$editid
											") or die(mysql_error());
						
						$empemployer = "";
						$empposition = "";
						$emptelno = "";
						$empdatefrom = "";
						$empdateto = "";
						$empsalary = "";
						$empovertime = "";
						$empreason = "";
				
					break;
				case "8": //MEDICAL
				
						$medcheckupdateraw = date('Y-m-d',strtotime($medcheckupdate));
						
						$qryempeditsave = mysql_query("UPDATE crewmedical SET
															CLINICID = '$medclinicid',
															DATECHECKUP = '$medcheckupdateraw',
															DIAGNOSIS = '$meddiagnosis',
															RECOMMENDCODE = '$medrecommendcode',
															REMARKS = '$medremarks',
															HBATEST = $medhbatest,
															MADEBY = '$employeeid',
															MADEDATE = '$currentdate'
														WHERE APPLICANTNO=$applicantno AND IDNO=$editid
											") or die(mysql_error());
				
						$medclinicid = "";
						$medcheckupdate = "";
						$meddiagnosis = "";
						$medhbatest = 0;
						$chkhbatest = "";
						$medrecommendcode = "";
						$medremarks = "";
				
				break;
			}
		
		break;	
	
	case "edit"		:
		
			$editmode = "disabled=\"disabled\"";
		
			switch ($currentpage)
			{
				case "0": //PERSONAL
					
					break;
				case "1": //FAMILY
				
						$qryfamilydata = mysql_query("SELECT RELCODE,FNAME,GNAME,MNAME,ADDRESS,PROVINCECODE,TOWNCODE,BARANGAYCODE,
												CITY,ZIPCODE,COUNTRYCODE,date_format(BIRTHDATE,'%m/%d/%Y') AS BIRTHDATE,TELNO,JOBSTATUS,
												OCCUPATION,DEPENDENT,OTHERRELATION,
												GENDER,ABROAD,EMAIL 
												FROM crewfamily 
												WHERE APPLICANTNO=$applicantno AND CREWRELID=$editid") or die(mysql_error());
						
						$rowfamilydata = mysql_fetch_array($qryfamilydata);
						
						$familyrelcode = $rowfamilydata["RELCODE"];
						$familyfname = $rowfamilydata["FNAME"];
						$familygname = $rowfamilydata["GNAME"];
						$familymname = $rowfamilydata["MNAME"];
						$familyaddress = $rowfamilydata["ADDRESS"];
						$familyprovince = $rowfamilydata["PROVINCECODE"];
						$familycity = $rowfamilydata["TOWNCODE"];
						$familybarangay = $rowfamilydata["BARANGAYCODE"];
						$familyzipcode = $rowfamilydata["ZIPCODE"];
						$familycountrycode = $rowfamilydata["COUNTRYCODE"];
						$familybdate = $rowfamilydata["BIRTHDATE"];
						$familytelno = $rowfamilydata["TELNO"];
						$familyjobstatus = $rowfamilydata["JOBSTATUS"];
						$familyoccupation = $rowfamilydata["OCCUPATION"];
						$familydependent = $rowfamilydata["DEPENDENT"];
						$familyrelothers = $rowfamilydata["OTHERRELATION"];
						$familygender = $rowfamilydata["GENDER"];
						$familyabroad = $rowfamilydata["ABROAD"];
						$familyemail = $rowfamilydata["EMAIL"];;
				
					break;
				case "2": //EDUCATION
				
						$qryeducdata = mysql_query("SELECT SCHOOLID,SCHOOLOTHERS,ADDRESS,CONTACTNO,CONTACTPERSON,
											COURSEID,COURSEOTHERS,date_format(DATEGRADUATED,'%m/%d/%Y') AS DATEGRADUATED,LEVEL,HONORS 
											FROM creweducation 
											WHERE APPLICANTNO=$applicantno AND IDNO=$editid") or die(mysql_error());
						
						$roweducdata = mysql_fetch_array($qryeducdata);
						
						$educschoolid = $roweducdata["SCHOOLID"];
						$educschoolothers = $roweducdata["SCHOOLOTHERS"];
						$educcourseid = $roweducdata["COURSEID"];
						$educcourseothers = $roweducdata["COURSEOTHERS"];
						$educdategrad = $roweducdata["DATEGRADUATED"];
						$educaddress = $roweducdata["ADDRESS"];
						$educcontactno = $roweducdata["CONTACTNO"];
						$educcontactperson = $roweducdata["CONTACTPERSON"];
						$educlevel = $roweducdata["LEVEL"];
						$educhonors = $roweducdata["HONORS"];
					
					break;
				case "3": //DOCUMENTS
				
						$qrydocdata = mysql_query("SELECT cds.DOCCODE,cds.DOCNO,date_format(cds.DATEISSUED,'%m/%d/%Y') AS DATEISSUED,
											date_format(cds.DATEEXPIRED,'%m/%d/%Y') AS DATEEXPIRED,cd.HASEXPIRY,cds.RANKCODE
											FROM crewdocstatus cds
											LEFT JOIN crewdocuments cd ON cd.DOCCODE=cds.DOCCODE
											WHERE APPLICANTNO=$applicantno AND IDNO=$editid") or die(mysql_error());
						
						$rowdocdata = mysql_fetch_array($qrydocdata);
						
						$doccode = $rowdocdata["DOCCODE"];
						$docrankcode = $rowdocdata["RANKCODE"];
						$docno = $rowdocdata["DOCNO"];
						$docissued = $rowdocdata["DATEISSUED"];
						$docexpired = $rowdocdata["DATEEXPIRED"];
						$dochasexpiry = $rowdocdata["HASEXPIRY"];
				
					break;
				case "4": //LICENSE
				
						$qrylicdata = mysql_query("SELECT cds.DOCCODE,cds.DOCNO,date_format(cds.DATEISSUED,'%m/%d/%Y') AS DATEISSUED,
											date_format(cds.DATEEXPIRED,'%m/%d/%Y') AS DATEEXPIRED,cd.HASEXPIRY,cds.RANKCODE
											FROM crewdocstatus cds
											LEFT JOIN crewdocuments cd ON cd.DOCCODE=cds.DOCCODE
											WHERE APPLICANTNO=$applicantno AND IDNO=$editid") or die(mysql_error());
						
						$rowlicdata = mysql_fetch_array($qrylicdata);
						
						$liccode = $rowlicdata["DOCCODE"];
						$licrankcode = $rowlicdata["RANKCODE"];
						$licno = $rowlicdata["DOCNO"];
						$licissued = $rowlicdata["DATEISSUED"];
						$licexpired = $rowlicdata["DATEEXPIRED"];
						$lichasexpiry = $rowlicdata["HASEXPIRY"];
					
					break;
				case "5": //CERTIFICATE
				
						$qrycertdata = mysql_query("SELECT DOCCODE,DOCNO,date_format(DATEISSUED,'%m/%d/%Y') AS DATEISSUED,GRADE,RANKCODE
											FROM crewcertstatus
											WHERE APPLICANTNO=$applicantno AND IDNO=$editid") or die(mysql_error());
						
						$rowcertdata = mysql_fetch_array($qrycertdata);
						
						$certcode = $rowcertdata["DOCCODE"];
						$certrankcode = $rowcertdata["RANKCODE"];
						$certno = $rowcertdata["DOCNO"];
						$certissued =  $rowcertdata["DATEISSUED"];
				
					break;
				case "6": //EXPERIENCE
				
						$qryexpdata = mysql_query("SELECT VESSEL,ENGINETYPE,FLAGCODE,VESSELTYPECODE,
												RANKCODE,TRADEROUTECODE,GROSSTON,DEADWT,CARGOKIND,MANNINGCODE,MANNINGOTHERS,
												date_format(DATEEMB,'%m/%d/%Y') AS DATEEMB,date_format(DATEDISEMB,'%m/%d/%Y') AS DATEDISEMB,
												DISEMBREASONCODE,REASONOTHERS,FLAGOTHERS,VESSELTYPEOTHERS,TRADEROUTEOTHERS
												FROM crewexperience
												WHERE APPLICANTNO=$applicantno AND IDNO=$editid") or die(mysql_error());
				
						$rowexpdata = mysql_fetch_array($qryexpdata);
				
						$expvessel = $rowexpdata["VESSEL"];
						$expenginetype = $rowexpdata["ENGINETYPE"];
						$expflagcode = $rowexpdata["FLAGCODE"];
						$expvesseltypecode = $rowexpdata["VESSELTYPECODE"];
						$exprankcode = $rowexpdata["RANKCODE"];
						$exptraderoutecode = $rowexpdata["TRADEROUTECODE"];
						$expgrosston = $rowexpdata["GROSSTON"];
						$expdeadwt = $rowexpdata["DEADWT"];
						$expcargo = $rowexpdata["CARGOKIND"];
						$expmanningcode = $rowexpdata["MANNINGCODE"];
						$expmanningothers = $rowexpdata["MANNINGOTHERS"];
						$expembdate = $rowexpdata["DATEEMB"];
						$expdisembdate = $rowexpdata["DATEDISEMB"];
						$expdisembreasoncode = $rowexpdata["DISEMBREASONCODE"];
						$expreasonothers = $rowexpdata["REASONOTHERS"];
						
						$expflagothers = $rowexpdata["FLAGOTHERS"];
						$expvsltypeothers = $rowexpdata["VESSELTYPEOTHERS"];
						$exptradeothers = $rowexpdata["TRADEROUTEOTHERS"];
						
					break;
				case "7": //EMPLOYMENT
				
						$qryempdata = mysql_query("SELECT EMPLOYER,POSITION,TELNO,
												date_format(DATEFROM,'%m/%d/%Y') AS DATEFROM,date_format(DATETO,'%m/%d/%Y') AS DATETO,REASON
												FROM employhistory
												WHERE APPLICANTNO=$applicantno AND IDNO=$editid") or die(mysql_error());
						
						$rowempdata = mysql_fetch_array($qryempdata);
				
						$empemployer = $rowempdata["EMPLOYER"];
						$empposition = $rowempdata["POSITION"];
						$emptelno = $rowempdata["TELNO"];
						$empdatefrom = $rowempdata["DATEFROM"];
						$empdateto = $rowempdata["DATETO"];
//						$empsalary = $rowempdata["BASICSALARY"];
//						$empovertime = $rowempdata["OVERTIME"];
						$empreason = $rowempdata["REASON"];
						
					break;
				case "8": //MEDICAL
				
						$qrymeddata = mysql_query("
												SELECT cm.IDNO,cm.CLINICID,date_format(cm.DATECHECKUP,'%m/%d/%Y') AS DATECHECKUP,cm.DIAGNOSIS,
												cm.RECOMMENDCODE,cm.REMARKS,cm.HBATEST
												FROM crewmedical cm
												WHERE cm.APPLICANTNO=$applicantno AND cm.IDNO=$editid") or die(mysql_error());
						
						$rowmeddata = mysql_fetch_array($qrymeddata);
				
						$medclinicid = $rowmeddata["CLINICID"];
						$medcheckupdate = $rowmeddata["DATECHECKUP"];
						$meddiagnosis = $rowmeddata["DIAGNOSIS"];
						$medhbatest = $rowmeddata["HBATEST"];
						
						if ($medhbatest == 1)
							$chkhbatest = "checked=\"checked\"";
						else
							$chkhbatest = "";
						
						$medrecommendcode = $rowmeddata["RECOMMENDCODE"];
						$medremarks = $rowmeddata["REMARKS"];
						
					break;
			}
		
		
		break;
		
	case "delete"	:
			
			switch ($currentpage)
			{
				case "0": //PERSONAL
					
					break;
				case "1": //FAMILY
				
						$qryfamilydelete = mysql_query("DELETE FROM crewfamily WHERE APPLICANTNO=$applicantno AND CREWRELID=$deleteid") or die(mysql_error());
				
					break;
				case "2": //EDUCATION
					
						$qryeducdelete = mysql_query("DELETE FROM creweducation WHERE APPLICANTNO=$applicantno AND IDNO=$deleteid") or die(mysql_error());
				
					break;
				case "3": //DOCUMENTS
				
						$qrydocdelete = mysql_query("DELETE FROM crewdocstatus WHERE APPLICANTNO=$applicantno AND IDNO=$deleteid") or die(mysql_error());
				
					break;
				case "4": //LICENSE
					
						$qrylicdelete = mysql_query("DELETE FROM crewdocstatus WHERE APPLICANTNO=$applicantno AND IDNO=$deleteid") or die(mysql_error());
				
					break;
				case "5": //CERTIFICATE
				
						$qrycertdelete = mysql_query("DELETE FROM crewcertstatus WHERE APPLICANTNO=$applicantno AND IDNO=$deleteid") or die(mysql_error());
				
					break;
				case "6": //EXPERIENCE
				
						$qryexpdelete = mysql_query("DELETE FROM crewexperience WHERE APPLICANTNO=$applicantno AND IDNO=$deleteid") or die(mysql_error());
					
					break;
				case "7": //EMPLOYMENT
				
						$qryemploydelete = mysql_query("DELETE FROM employhistory WHERE APPLICANTNO=$applicantno AND IDNO=$deleteid") or die(mysql_error());
				
					break;
				case "8": //MEDICAL
				
						$qrymedicaldelete = mysql_query("DELETE FROM crewmedical WHERE APPLICANTNO=$applicantno AND IDNO=$deleteid") or die(mysql_error());
				
					break;
			}
		break;
	
	case "reset"	:
			
			$applicantno = "";
			$searchby = "";
			$searchkey = "";
		
			$choice1 = "";
			$choice2 = "";
			
			$recommendedby = "";
			$qdesiredsalary = "";
		
			$fname = "";
			$gname = "";
			$mname = "";
			
			$crewaddress = "";
			$crewprovince = "";
			$crewcity = "";
			$crewbarangay = "";
			$crewzipcode = "";
			$crewtelno = "";
			
			$crewprovaddress = "";
			$crewprovprovince = "";
			$crewprovcity = "";
			$crewprovbarangay = "";
			$crewprovzipcode = "";
			$crewprovtelno = "";
			
			$crewbdate = "";
			$crewbplace = "";
			$crewgender = "";
			$crewcivilstatus = "";
			$crewreligion = "CATHOLIC";
			$crewweight = "";
			$crewheight = "";
			$crewshoesize = "";
			$crewemail = "";
			$crewrecommendedby = "";
			$crewcelphone1 = "";
			$crewcelphone2 = "";
			$crewcelphone3 = "";
		
			$familyrelcode = "";
			$familyfname = "";
			$familygname = "";
			$familymname = "";
			$familyaddress = "";
			$familyprovince = "";
			$familycity = "";
			$familybarangay = "";
			$familyzipcode = "";
			$familycountrycode = "PH";
			$familybdate = "";
			$familytelno = "";
			$familyjobstatus = "";
			$familyoccupation = "";
			$familydependent = "";
			$familyrelothers = "";
			$familygender = "";
			$familyabroad = "";				
			$familyemail = "";				
			
			$educschoolid = "";
			$educschoolothers = "";
			$educcourseid = "";
			$educcourseothers = "";
			$educaddress = "";
			$educcontactno = "";
			$educcontactperson = "";
			$educdategrad = "";
			$educlevel = "";
			$educhonors = "";
			
			$liccode = "";
			$licrankcode = "";
			$licno = "";
			$licissued = "";
			$licexpired = "";
			
			$doccode = "";
			$docrankcode = "";
			$docno = "";
			$docissued = "";
			$docexpired = "";
			
			$certcode = "";
			$certrankcode = "";
			$certno = "";
			$certissued = "";
			
			$expvessel = "";
			$expenginetype = "";
			$expflagcode = "";
			$expflagothers = "";
			$expvesseltypecode = "";
			$expvsltypeothers = "";
			$exprankcode = "";
			$exptraderoutecode = "";
			$exptradeothers = "";
			$expgrosston = "";
			$expcargo = "";
			$expmanningcode = "";
			$expmanningothers = "";
			$expembdate = "";
			$expdisembdate = "";
			$expdisembreasoncode = "";
			$expreasonothers = "";

			$empemployer = "";
			$empposition = "";
			$emptelno = "";
			$empdatefrom = "";
			$empdateto = "";
			$empsalary = "";
			$empovertime = "";
			$empreason = "";
			
			$medclinicid = "";
			$medcheckupdate = "";
			$meddiagnosis = "";
			$medhbatest = 0;
			$chkhbatest = "";
			$medrecommendcode = "";
			$medremarks = "";
										
		break;
	
	case "find"	: //SEARCH KEY
			
			$appno = "";
			
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
	
	case "getappno"	: //INITIAL SAVING OF APPLICANT NO (PERSONAL INFORMATION)
	
			$crewbdateraw = date('Y-m-d',strtotime($crewbdate));
			
			$qrysavecrew = mysql_query("INSERT INTO crew(CREWCODE,FNAME,GNAME,MNAME,
						ADDRESS,PROVINCECODE,TOWNCODE,BARANGAYCODE,ZIPCODE,TELNO,
						PROVADDRESS,PROVPROVCODE,PROVTOWNCODE,PROVBRGYCODE,PROVZIPCODE,PROVTELNO,
						BIRTHDATE,BIRTHPLACE,CIVILSTATUS,NATIONALITY,RELIGION,WEIGHT,HEIGHT,
						GENDER,SIZESHOES,MADEBY,MADEDATE,EMAIL,CEL1,CEL2,CEL3,RECOMMENDEDBY) 
						VALUES('$crewcode','$fname','$gname','$mname','$crewaddress','$crewprovince','$crewcity','$crewbarangay','$crewzipcode','$crewtelno',
						'$crewprovaddress','$crewprovprovince','$crewprovcity','$crewprovbarangay','$crewprovzipcode','$crewprovtelno',
						'$crewbdateraw','$crewbplace','$crewcivilstatus','FILIPINO','$crewreligion','$crewweight','$crewheight',
						'$crewgender','$crewshoesize','$employeeid','$currentdate','$crewemail','$crewcelphone1','$crewcelphone2','$crewcelphone3',
						'$crewrecommendedby')") or die(mysql_error());
			
			$qrygetapplicantno = mysql_query("SELECT MAX(APPLICANTNO) AS APPLICANTNO FROM crew WHERE FNAME='$fname' AND GNAME='$gname'") or die(mysql_error());
			$rowgetapplicantno = mysql_fetch_array($qrygetapplicantno);
			
			$applicantno = $rowgetapplicantno["APPLICANTNO"];
			
		break;
		
	case "addlist"	:  //IF "ADD TO LIST" BUTTON IS CLICKED -- SAVE DETAILS

			$remarks1 = "";
			$remarks2 = "";
			$remarks3 = "";
			$remarks4 = "";
			$remarks5 = "";
			$remarks6 = "";
			$remarks7 = "";
			$remarks9 = "";
			
			switch ($currentpage)
			{
				case "0": //PERSONAL
					
					break;
				case "1": //FAMILY
				
						$qryfamilycheck = mysql_query("SELECT APPLICANTNO FROM crewfamily WHERE FNAME='$familyfname' 
												AND GNAME='$familygname' AND APPLICANTNO=$applicantno")	or die(mysql_error());
						
						if (mysql_num_rows($qryfamilycheck) < 1)
						{
							
							$familybdateraw = date('Y-m-d',strtotime($familybdate));
							
							if ($familydependent == "")
								$familydependent = 0;
								
							if ($familyabroad == "")
								$familyabroad = 0;
							
							$qryfamilysave = mysql_query("INSERT INTO crewfamily(CREWRELID,APPLICANTNO,RELCODE,FNAME,GNAME,MNAME,
											ADDRESS,PROVINCECODE,TOWNCODE,BARANGAYCODE,
											ZIPCODE,COUNTRYCODE,BIRTHDATE,TELNO,JOBSTATUS,OCCUPATION,DEPENDENT,OTHERRELATION,REMARKS,MADEBY,MADEDATE,
											GENDER,ABROAD,EMAIL) 
											VALUES(NULL,$applicantno,'$familyrelcode','$familyfname','$familygname','$familymname','$familyaddress',
											'$familyprovince','$familycity','$familybarangay','$familyzipcode','$familycountrycode','$familybdateraw','$familytelno',
											'$familyjobstatus','$familyoccupation',$familydependent,'$familyrelothers',NULL,'$employeeid','$currentdate',
											'$familygender',$familyabroad,'$familyemail')
											") or die(mysql_error());
							
							//RESET FIELDS FOR NEXT INPUT
							
							$familyrelcode = "";
							$familyfname = "";
							$familygname = "";
							$familymname = "";
							$familyaddress = "";
							$familyprovince = "";
							$familycity = "";
							$familybarangay = "";
							$familyzipcode = "";
							$familycountrycode = "PH";
							$familybdate = "";
							$familytelno = "";
							$familyjobstatus = "";
							$familyoccupation = "";
							$familydependent = "";
							$familyrelothers = "";
							$familygender = "";
							$familyabroad = "";
							$familyemail = "";
						}
						else 
							$remarks1 = "Duplicate Entry. Please try again.";
								
					break;

				case "2": //EDUCATION
				
						$educdategradraw = date('Y-m-d',strtotime($educdategrad));
						
						if($educschoolid == "")
							$educschoolid = "NULL";
							
						if($educcourseid == "")
							$educcourseid = "NULL";
						
						$qryeduccheck = mysql_query("SELECT APPLICANTNO FROM creweducation WHERE SCHOOLID=$educschoolid AND SCHOOLOTHERS='$educschoolothers' 
											AND	COURSEID=$educcourseid AND COURSEOTHERS='$educcourseothers' AND APPLICANTNO=$applicantno") or die(mysql_error());
			
						if (mysql_num_rows($qryeduccheck) < 1)
						{
							$qryeducationsave = mysql_query("INSERT INTO creweducation(APPLICANTNO,SCHOOLID,SCHOOLOTHERS,
											COURSEID,COURSEOTHERS,DATEGRADUATED,ADDRESS,CONTACTNO,CONTACTPERSON,LEVEL,HONORS,MADEBY,MADEDATE) 
											VALUES($applicantno,$educschoolid,'$educschoolothers',$educcourseid,'$educcourseothers',
											'$educdategradraw','$educaddress','$educcontactno','$educcontactperson','$educlevel','$educhonors',
											'$employeeid','$currentdate')
											") or die(mysql_error());
							
							$educschoolid = "";
							$educschoolothers = "";
							$educcourseid = "";
							$educcourseothers = "";
							$educdategrad = "";
							$educaddress = "";
							$educcontactno = "";
							$educcontactperson = "";
							$educlevel = "";
							$educhonors = "";
						}
						else 
							$remarks2 = "Duplicate Entry. Please try again.";
							
					break;
				case "3": //DOCUMENTS
				
						if(!empty($doccode))
						{
							$qrydoccheck = mysql_query("SELECT APPLICANTNO FROM crewdocstatus WHERE DOCCODE='$doccode' AND DOCNO='$docno'
												 AND APPLICANTNO=$applicantno") or die(mysql_error());
							
							if (mysql_num_rows($qrydoccheck) < 1)
							{
								$docissuedraw = "'" . date('Y-m-d',strtotime($docissued)) . "'";
								
								if (!empty($docexpired))
									$docexpiredraw = "'" . date('Y-m-d',strtotime($docexpired)) . "'";
								else 
									$docexpiredraw = "NULL";
					
								$qrydocumentssave = mysql_query("INSERT INTO crewdocstatus(APPLICANTNO,RANKCODE,DOCCODE,DATEISSUED,DATEEXPIRED,DOCNO,MADEBY,MADEDATE)
												VALUES($applicantno,'$docrankcode','$doccode',$docissuedraw,$docexpiredraw,'$docno','$employeeid','$currentdate')
												") or die(mysql_error());
								
								$doccode = "";
								$docrankcode = "";
								$docno = "";
								$docissued = "";
								$docexpired = "";
							}
							else 
								$remarks3 = "Duplicate Entry. Please try again.";
						}

					break;
				case "4": //LICENSE
						if (!empty($licno))
						{
							$qryliccheck = mysql_query("SELECT APPLICANTNO FROM crewdocstatus WHERE DOCCODE='$liccode' AND DOCNO='$licno'
												 AND APPLICANTNO=$applicantno") or die(mysql_error());
							
							if (mysql_num_rows($qryliccheck) < 1)
							{
								$licissuedraw = "'" . date('Y-m-d',strtotime($licissued)) . "'";
								
								if (!empty($licexpired))
									$licexpiredraw = "'" . date('Y-m-d',strtotime($licexpired)) . "'";
								else 
									$licexpiredraw = "NULL";
					
								$qrylicensesave = mysql_query("INSERT INTO crewdocstatus(APPLICANTNO,RANKCODE,DOCCODE,DATEISSUED,DATEEXPIRED,DOCNO,MADEBY,MADEDATE)
												VALUES($applicantno,'$licrankcode','$liccode',$licissuedraw,$licexpiredraw,'$licno','$employeeid','$currentdate')
												") or die(mysql_error());
								
								$liccode = "";
								$licrankcode = "";
								$licno = "";
								$licissued = "";
								$licexpired = "";
							}
							else 
								$remarks4 = "Duplicate Entry. Please try again.";
						}

					break;
				case "5": //CERTIFICATE
					
						if (!empty($certno))
						{
							$qrycertcheck = mysql_query("SELECT APPLICANTNO FROM crewcertstatus WHERE DOCCODE='$certcode' AND DOCNO='$certno'
												 AND APPLICANTNO=$applicantno") or die(mysql_error());
							
							if (mysql_num_rows($qrycertcheck) < 1)
							{
								$certissuedraw = "'" . date('Y-m-d',strtotime($certissued)) . "'";
								
								$qrycertificatesave = mysql_query("INSERT INTO crewcertstatus(APPLICANTNO,RANKCODE,DOCCODE,DOCNO,DATEISSUED,MADEBY,MADEDATE)
												VALUES($applicantno,'$certrankcode','$certcode','$certno',$certissuedraw,'$employeeid','$currentdate')
												") or die(mysql_error());
								
								$certcode = "";
								$certrankcode = "";
								$certno = "";
								$certissued = "";
							}
							else 
								$remarks5 = "Duplicate Entry. Please try again.";
						}

					break;
				case "6": //EXPERIENCE

						$expdisembdateraw = date('Y-m-d',strtotime($expdisembdate));
						$expembdateraw = date('Y-m-d',strtotime($expembdate));
				
						$qryexpcheck = mysql_query("SELECT APPLICANTNO FROM crewexperience 
											WHERE VESSEL='$expvessel' AND MANNINGCODE='$expmanningcode'
											AND DATEEMB='$expembdateraw' AND DATEDISEMB='$expdisembdateraw'
											AND APPLICANTNO=$applicantno") or die(mysql_error());
						
						if (mysql_num_rows($qryexpcheck) < 1)
						{
				
							if ($expgrosston == "")
								$expgrosston = 0;
							
							if ($expdeadwt == "")
								$expdeadwt = 0;
							
							$qryexperiencesave = mysql_query("INSERT INTO crewexperience(APPLICANTNO,VESSEL,ENGINETYPE,FLAGCODE,VESSELTYPECODE,
												RANKCODE,TRADEROUTECODE,GROSSTON,CARGOKIND,MANNINGCODE,MANNINGOTHERS,DATEEMB,DATEDISEMB,
												DISEMBREASONCODE,REASONOTHERS,FLAGOTHERS,VESSELTYPEOTHERS,TRADEROUTEOTHERS)
												VALUES($applicantno,'$expvessel','$expenginetype','$expflagcode','$expvesseltypecode','$exprankcode',
												'$exptraderoutecode',$expgrosston,'$expcargo','$expmanningcode','$expmanningothers',
												'$expembdateraw','$expdisembdateraw','$expdisembreasoncode','$expreasonothers','$expflagothers',
												'$expvsltypeothers','$exptradeothers')
												") or die(mysql_error());
							
							$expvessel = "";
							$expenginetype = "";
							$expflagcode = "";
							$expflagothers = "";
							$expvesseltypecode = "";
							$expvsltypeothers = "";
							$exprankcode = "";
							$exptraderoutecode = "";
							$exptradeothers = "";
							$expgrosston = "";
							$expcargo = "";
							$expmanningcode = "";
							$expmanningothers = "";
							$expembdate = "";
							$expdisembdate = "";
							$expdisembreasoncode = "";
							$expreasonothers = "";
						}
						else 
							$remarks6 = "Duplicate Entry. Please try again.";
					
					break;
				case "7": //EMPLOYMENT
					
						$qryempcheck = mysql_query("SELECT APPLICANTNO FROM employhistory 
											WHERE EMPLOYER='$empemployer' AND POSITION='$empposition'
											AND APPLICANTNO=$applicantno") or die(mysql_error());
						
						if (mysql_num_rows($qryempcheck) < 1)
						{
							$empdatefromraw = date('Y-m-d',strtotime($empdatefrom));
							$empdatetoraw = date('Y-m-d',strtotime($empdateto));
				
							if ($empsalary == "")
								$empsalary = 0;
								
							if ($empovertime == "")
								$empovertime = 0;
							
							$qryemploymentsave = mysql_query("INSERT INTO employhistory(APPLICANTNO,EMPLOYER,POSITION,TELNO,DATEFROM,DATETO,REASON)
												VALUES($applicantno,'$empemployer','$empposition','$emptelno','$empdatefromraw','$empdatetoraw','$empreason')
												") or die(mysql_error());
							
							$empemployer = "";
							$empposition = "";
							$emptelno = "";
							$empdatefrom = "";
							$empdateto = "";
							$empsalary = "";
							$empovertime = "";
							$empreason = "";
						}
						else 
							$remarks7 = "Duplicate Entry. Please try again.";
				
					break;
				case "8": //MEDICAL
				
						$medcheckupdateraw = date('Y-m-d',strtotime($medcheckupdate));
						
						$qrymedcheck = mysql_query("SELECT APPLICANTNO FROM crewmedical 
											WHERE CLINICID='$medclinicid' AND DATECHECKUP = '$medcheckupdateraw'
											AND APPLICANTNO=$applicantno") or die(mysql_error());
						
						if (mysql_num_rows($qrymedcheck) < 1)
						{
							
							$qrymedicalsave = mysql_query("INSERT INTO crewmedical (IDNO,APPLICANTNO,CLINICID,DATECHECKUP,DIAGNOSIS,RECOMMENDCODE,REMARKS,MADEBY,MADEDATE,DOCCODE,HBATEST,STATUS)
												VALUES(NULL,$applicantno,'$medclinicid','$medcheckupdateraw','$meddiagnosis',
												'$medrecommendcode','$medremarks','$employeeid','$currentdate','P7',$medhbatest,1)") or die(mysql_error());
							
							$medclinicid = "";
							$medcheckupdate = "";
							$meddiagnosis = "";
							$medhbatest = 0;
							$chkhbatest = "";
							$medrecommendcode = "";
							$medremarks = "";
						}
						else 
							$remarks7 = "Duplicate Entry. Please try again.";
					
					break;
				case "9": //REFERENCE
				
						$qryrefcheck = mysql_query("SELECT APPLICANTNO FROM applicantreference 
											WHERE NAME='$refname' AND TELNO='$reftelno'
											AND APPLICANTNO=$applicantno") or die(mysql_error());
						
						if (mysql_num_rows($qryrefcheck) < 1)
						{
							$qryreferencesave = mysql_query("INSERT INTO applicantreference(APPLICANTNO,NAME,TELNO,OCCUPATION,REMARKS)
												VALUES($applicantno,'$refname','$reftelno','$refoccupation','$refremarks')
												") or die(mysql_error());
							
							$refname = "";
							$reftelno = "";
							$refoccupation = "";
							$refremarks = "";
						}
						else 
							$remarks9 = "Duplicate Entry. Please try again.";
							
					break;
				case "10": //AGREEMENT
					
					break;
			}
		
		break;
}

	if ($applicantno != "")
	{
		$qrygetcrew = mysql_query("SELECT FNAME,GNAME,MNAME,ADDRESS,PROVINCECODE,TOWNCODE,BARANGAYCODE,ZIPCODE,TELNO,
									PROVADDRESS,PROVPROVCODE,PROVTOWNCODE,PROVBRGYCODE,PROVTELNO,PROVZIPCODE,SSS,TIN,
									BIRTHDATE,BIRTHPLACE,GENDER,CIVILSTATUS,RELIGION,WEIGHT,HEIGHT,SIZESHOES,EMAIL,
									CEL1,CEL2,CEL3,CREWCODE,
									IF(c.RECOMMENDEDBY IS NULL,a.RECOMMENDEDBY,c.RECOMMENDEDBY) AS RECOMMENDEDBY,
									IF (cs.APPLICANTNO IS NOT NULL,s.DESCRIPTION,'') AS SCHOLAR,
									IF (cf.APPLICANTNO IS NOT NULL,f.FASTTRACK,'') AS FASTTRACK
									FROM crew c
									LEFT JOIN applicant a ON a.APPLICANTNO=c.APPLICANTNO
									LEFT JOIN crewscholar cs ON cs.APPLICANTNO=c.APPLICANTNO
									LEFT JOIN scholar s ON s.SCHOLASTICCODE=cs.SCHOLASTICCODE
									LEFT JOIN crewfasttrack cf ON cf.APPLICANTNO=c.APPLICANTNO
									LEFT JOIN fasttrack f ON f.FASTTRACKCODE=cf.FASTTRACKCODE
									WHERE c.APPLICANTNO=$applicantno") or die(mysql_error());
		
		$rowgetcrew = mysql_fetch_array($qrygetcrew);
		
		$fname = $rowgetcrew["FNAME"];
		$gname = $rowgetcrew["GNAME"];
		$mname = $rowgetcrew["MNAME"];
		$crewcode = $rowgetcrew["CREWCODE"];
		$crewscholartype = $rowgetcrew["SCHOLAR"];
		$crewfasttracktype = $rowgetcrew["FASTTRACK"];
		
		if (empty($crewprovince))
		{
			$crewaddress = $rowgetcrew["ADDRESS"];
			$crewprovince = $rowgetcrew["PROVINCECODE"];
			$crewcity = $rowgetcrew["TOWNCODE"];
			$crewbarangay = $rowgetcrew["BARANGAYCODE"];
		}
		
		$crewzipcode = $rowgetcrew["ZIPCODE"];
		$crewtelno = $rowgetcrew["TELNO"];
		
		if (empty($crewprovprovince))
		{
			$crewprovaddress = $rowgetcrew["PROVADDRESS"];
			$crewprovprovince = $rowgetcrew["PROVPROVCODE"];
			$crewprovcity = $rowgetcrew["PROVTOWNCODE"];
			$crewprovbarangay = $rowgetcrew["PROVBRGYCODE"];
		}
		
		$crewprovzipcode = $rowgetcrew["PROVZIPCODE"];
		$crewprovtelno = $rowgetcrew["PROVTELNO"];
		
		if (empty($rowgetcrew["BIRTHDATE"]))
			$crewbdate = "";
		else 
			$crewbdate = date('m/d/Y',strtotime($rowgetcrew["BIRTHDATE"]));
			
		$crewbplace = $rowgetcrew["BIRTHPLACE"];
		$crewgender = $rowgetcrew["GENDER"];
		$crewcivilstatus = $rowgetcrew["CIVILSTATUS"];
//					$crewnationality = $rowgetcrew["NATIONALITY"];
		$crewreligion = $rowgetcrew["RELIGION"];
		$crewsss = $rowgetcrew["SSS"];
		$crewtin = $rowgetcrew["TIN"];
		$crewweight = $rowgetcrew["WEIGHT"];
		$crewheight = $rowgetcrew["HEIGHT"];
		$crewshoesize = $rowgetcrew["SIZESHOES"];
		$crewemail = $rowgetcrew["EMAIL"];
		$crewrecommendedby = $rowgetcrew["RECOMMENDEDBY"];
		
		$crewcelphone1 = $rowgetcrew["CEL1"];
		$crewcelphone2 = $rowgetcrew["CEL2"];
		$crewcelphone3 = $rowgetcrew["CEL3"];
		
	}
	else 
	{
		$searcherror = "Applicant No. not found!";
		$applicantno = "";
	}

//*****************************************************  END OF SAVING SECTION  ***************************************************//

	
//********************************************************  LISTINGS  ***************************************************//

//+++++++++++++++  PERSONAL  ++++++++++++++++++//

//POSITION APPLIED FOR -- CHOICE1 AND CHOICE2
$qryposition = mysql_query("SELECT RANKCODE,RANK FROM rank WHERE STATUS=1") or die(mysql_error());

$personalselect1 = "<option value=\"\">--Select One--</option>";
$personalselect2 = "<option value=\"\">--Select One--</option>";

	while($rowposition=mysql_fetch_array($qryposition))
	{
		$rankcode = $rowposition['RANKCODE'];
		$position = $rowposition['RANK'];
		
		$selected1 = "";
		$selected2 = "";		
		
		if ($rankcode==$choice1)
			$selected1 = "SELECTED";

		if ($rankcode==$choice2)
			$selected2 = "SELECTED";

		$personalselect1 .= "<option $selected1 value=\"$rankcode\">$position</option>";
		$personalselect2 .= "<option $selected2 value=\"$rankcode\">$position</option>";
	}

//GENDER -- MALE OR FEMALE

$personalselect3 = "<option selected value=\"\">--Select One--</option>";

$crewgender1 = "crew" . $crewgender;
$$crewgender1 = "SELECTED";	

$personalselect3 .= "<option $crewM value=\"M\">MALE</option>";
$personalselect3 .= "<option $crewF value=\"F\">FEMALE</option>";
	
	
//CIVIL STATUS

$personalselect4 = "<option selected value=\"\">--Select One--</option>";

$crewcivilstatus1 = "crew2" . $crewcivilstatus;
$$crewcivilstatus1 = "SELECTED";	

$personalselect4 .= "<option $crew2S value=\"S\">SINGLE</option>";
$personalselect4 .= "<option $crew2M value=\"M\">MARRIED</option>";
$personalselect4 .= "<option $crew2W value=\"W\">WIDOWER</option>";
$personalselect4 .= "<option $crew2P value=\"P\">SEPARATED</option>";


//PERMANENT ADDRESS

$qryprovince = mysql_query("SELECT PROVCODE,PROVINCE FROM addrprovince
							ORDER BY PROVINCE") or die(mysql_error());

$personalselect5 = "<option selected value=\"\">--Select One--</option>";
	while($rowprovince=mysql_fetch_array($qryprovince))
	{
		$provincecode = $rowprovince["PROVCODE"];
		$province = $rowprovince["PROVINCE"];
		
		$selected5 = "";
		
		if ($crewprovince == $provincecode)
			$selected5 = "SELECTED";
		
		$personalselect5 .= "<option $selected5 value=\"$provincecode\">$province</option>";
	}

$qrycity = mysql_query("SELECT TOWNCODE,TOWN FROM addrtown WHERE PROVCODE='$crewprovince'
						ORDER BY TOWN") or die(mysql_error());

$personalselect6 = "<option selected value=\"\">--Select One--</option>";
	while($rowcity=mysql_fetch_array($qrycity))
	{
		$towncode = $rowcity["TOWNCODE"];
		$town = $rowcity["TOWN"];
		
		$selected6 = "";
		
		if ($crewcity == $towncode)
			$selected6 = "SELECTED";
		
		$personalselect6 .= "<option $selected6 value=\"$towncode\">$town</option>";
	}

$qrybarangay = mysql_query("SELECT BRGYCODE,BARANGAY FROM addrbarangay WHERE PROVCODE='$crewprovince' AND TOWNCODE='$crewcity'
							ORDER BY BARANGAY") or die(mysql_error());

$personalselect7 = "<option selected value=\"\">--Select One--</option>";
	while($rowbarangay=mysql_fetch_array($qrybarangay))
	{
		$brgycode = $rowbarangay["BRGYCODE"];
		$barangay = $rowbarangay["BARANGAY"];
		
		$selected7 = "";
		
		if ($crewbarangay == $brgycode)
			$selected7 = "SELECTED";
		
		$personalselect7 .= "<option $selected7 value=\"$brgycode\">$barangay</option>";
	}
	
//PROVINCIAL ADDRESS

$qryprovince = mysql_query("SELECT PROVCODE,PROVINCE FROM addrprovince
							ORDER BY PROVINCE") or die(mysql_error());

$personalselect8 = "<option selected value=\"\">--Select One--</option>";
	while($rowprovince=mysql_fetch_array($qryprovince))
	{
		$provincecode = $rowprovince["PROVCODE"];
		$province = $rowprovince["PROVINCE"];
		
		$selected8 = "";
		
		if ($crewprovprovince == $provincecode)
			$selected8 = "SELECTED";
		
		$personalselect8 .= "<option $selected8 value=\"$provincecode\">$province</option>";
	}

$qrycity = mysql_query("SELECT TOWNCODE,TOWN FROM addrtown WHERE PROVCODE='$crewprovprovince'
						ORDER BY TOWN") or die(mysql_error());

$personalselect9 = "<option selected value=\"\">--Select One--</option>";
	while($rowcity=mysql_fetch_array($qrycity))
	{
		$towncode = $rowcity["TOWNCODE"];
		$town = $rowcity["TOWN"];
		
		$selected9 = "";
		
		if ($crewprovcity == $towncode)
			$selected9 = "SELECTED";
		
		$personalselect9 .= "<option $selected9 value=\"$towncode\">$town</option>";
	}

$qrybarangay = mysql_query("SELECT BRGYCODE,BARANGAY FROM addrbarangay WHERE PROVCODE='$crewprovprovince' AND TOWNCODE='$crewprovcity'
							ORDER BY BARANGAY") or die(mysql_error());

$personalselect10 = "<option selected value=\"\">--Select One--</option>";
	while($rowbarangay=mysql_fetch_array($qrybarangay))
	{
		$brgycode = $rowbarangay["BRGYCODE"];
		$barangay = $rowbarangay["BARANGAY"];
		
		$selected10 = "";
		
		if ($crewprovbarangay == $brgycode)
			$selected10 = "SELECTED";
		
		$personalselect10 .= "<option $selected10 value=\"$brgycode\">$barangay</option>";
	}
	
	
//+++++++++++++++ END OF PERSONAL  ++++++++++++++++++//
	
//+++++++++++++++  FAMILY  ++++++++++++++++++//
				
	//RELATION CODE
	
	$qryrelation = mysql_query("SELECT RELCODE,RELATION FROM familyrelation") or die(mysql_error());
	$familyselect1 = "<option value=\"\">--Select One--</option>";
		while($rowrelation=mysql_fetch_array($qryrelation))
		{
			$relcode = $rowrelation["RELCODE"];
			$relation = $rowrelation["RELATION"];
			
			$selected1="";
			
			if ($familyrelcode ==$relcode)
				$selected1 = "SELECTED";
			
			$familyselect1 .= "<option $selected1 value=\"$relcode\">$relation</option>";
		}
	
	//GENDER -- MALE OR FEMALE
	
	$familyselect2 = "<option selected value=\"\">--Select One--</option>";
	
	$familygender1 = "family" . $familygender;
	$$familygender1 = "SELECTED";	
	
	$familyselect2 .= "<option $familyM value=\"M\">MALE</option>";
	$familyselect2 .= "<option $familyF value=\"F\">FEMALE</option>";
	
	//COUNTRY
	
	$qrycountry = mysql_query("SELECT COUNTRYCODE,COUNTRY FROM country") or die(mysql_error());
	$familyselect3 = "<option selected value=\"\">--Select One--</option>";
		while($rowcountry=mysql_fetch_array($qrycountry))
		{
			$countrycode = $rowcountry["COUNTRYCODE"];
			$country = $rowcountry["COUNTRY"];
			
			$selected3 = "";
			
			if ($familycountrycode == $countrycode)
				$selected3 = "SELECTED";
			
			$familyselect3 .= "<option $selected3 value=\"$countrycode\">$country</option>";
		}
		
	//JOB STATUS
	
	$familyselect4 = "<option selected value=\"\">--Select One--</option>";
	
	$familyjobstatus1 = "family2" . $familyjobstatus;
	$$familyjobstatus1 = "SELECTED";	
	
	$familyselect4 .= "<option $family2S value=\"S\">STUDENT</option>";
	$familyselect4 .= "<option $family2W value=\"W\">WORKING</option>";
	$familyselect4 .= "<option $family2N value=\"N\">NON-WORKING</option>";
	
	
	//DEPENDENT (Y/N)
	
	$familyselect5 = "<option selected value=\"\">--Select--</option>";
	
	$familydependent1 = "family3" . $familydependent;
	$$familydependent1 = "SELECTED";	
	
	$familyselect5 .= "<option $family31 value=\"1\">YES</option>";
	$familyselect5 .= "<option $family30 value=\"0\">NO</option>";
	
	$familyselect6 = "<option selected value=\"\">--Select--</option>";
	
	$familyabroad1 = "family4" . $familyabroad;
	$$familyabroad1 = "SELECTED";	
	
	$familyselect6 .= "<option $family41 value=\"1\">YES</option>";
	$familyselect6 .= "<option $family40 value=\"0\">NO</option>";
	

$qryprovince = mysql_query("SELECT PROVCODE,PROVINCE FROM addrprovince
							ORDER BY PROVINCE") or die(mysql_error());

$familyselect7 = "<option selected value=\"\">--Select One--</option>";
	while($rowprovince=mysql_fetch_array($qryprovince))
	{
		$provincecode = $rowprovince["PROVCODE"];
		$province = $rowprovince["PROVINCE"];
		
		$selected7 = "";
		
		if ($familyprovince == $provincecode)
			$selected7 = "SELECTED";
		
		$familyselect7 .= "<option $selected7 value=\"$provincecode\">$province</option>";
	}

$qrycity = mysql_query("SELECT TOWNCODE,TOWN FROM addrtown WHERE PROVCODE='$familyprovince'
						ORDER BY TOWN") or die(mysql_error());

$familyselect8 = "<option selected value=\"\">--Select One--</option>";
	while($rowcity=mysql_fetch_array($qrycity))
	{
		$towncode = $rowcity["TOWNCODE"];
		$town = $rowcity["TOWN"];
		
		$selected8 = "";
		
		if ($familycity == $towncode)
			$selected8 = "SELECTED";
		
		$familyselect8 .= "<option $selected8 value=\"$towncode\">$town</option>";
	}

$qrybarangay = mysql_query("SELECT BRGYCODE,BARANGAY FROM addrbarangay WHERE PROVCODE='$familyprovince' AND TOWNCODE='$familycity'
							ORDER BY BARANGAY") or die(mysql_error());

$familyselect9 = "<option selected value=\"\">--Select One--</option>";
	while($rowbarangay=mysql_fetch_array($qrybarangay))
	{
		$brgycode = $rowbarangay["BRGYCODE"];
		$barangay = $rowbarangay["BARANGAY"];
		
		$selected9 = "";
		
		if ($familybarangay == $brgycode)
			$selected9 = "SELECTED";
		
		$familyselect9 .= "<option $selected9 value=\"$brgycode\">$barangay</option>";
	}
	
	
//+++++++++++++++ END OF FAMILY  ++++++++++++++++++//	


//+++++++++++++++  EDUCATION  ++++++++++++++++++//

	//COURSES
	
	$qrycourses = mysql_query("SELECT COURSEID,COURSE FROM maritimecourses") or die(mysql_error());
	$educselect1 = "<option selected value=\"\">--Select One--</option>";
		while($rowcourses=mysql_fetch_array($qrycourses))
		{
			$courseid = $rowcourses["COURSEID"];
			$course = $rowcourses["COURSE"];
			
			$selected1 = "";
			
			if ($educcourseid == $courseid)
				$selected1 = "SELECTED";
				
			$educselect1 .= "<option $selected1 value=\"$courseid\">$course</option>";
		}
	
	//SCHOOLS
		
	$qryschools = mysql_query("SELECT SCHOOLID,SCHOOL FROM maritimeschool") or die(mysql_error());
	$educselect2 = "<option selected value=\"\">--Select One--</option>";
		while($rowschools=mysql_fetch_array($qryschools))
		{
			$schoolid = $rowschools["SCHOOLID"];
			$school = $rowschools["SCHOOL"];
			
			$selected2 = "";
			
			if ($educschoolid == $schoolid)
				$selected2 = "SELECTED";
				
			$educselect2 .= "<option $selected2 value=\"$schoolid\">$school</option>";
		}
		
	//LEVEL
	
	$educselect3 = "<option selected value=\"\">--Select One--</option>";
	
	$$educlevel = "SELECTED";	
	
//	$educselect3 .= "<option $ELEMENTARY value=\"ELEMENTARY\">ELEMENTARY</option>";
	$educselect3 .= "<option $HIGHSCHOOL value=\"HIGHSCHOOL\">HIGHSCHOOL</option>";
	$educselect3 .= "<option $COLLEGE value=\"COLLEGE\">COLLEGE</option>";	

//+++++++++++++++  END OF EDUCATION  ++++++++++++++++++//


//+++++++++++++++  DOCUMENTS  ++++++++++++++++++//

$qrydocuments = mysql_query("SELECT DOCCODE,DOCUMENT FROM crewdocuments WHERE TYPE='D' AND STATUS=1 ORDER BY DOCUMENT") or die(mysql_error());
$docselect1 = "<option selected value=\"\">--Select One--</option>";
	while($rowdocuments=mysql_fetch_array($qrydocuments))
	{
		$documentcode = $rowdocuments["DOCCODE"];
		$documentname = $rowdocuments["DOCUMENT"];
		
		$selected1 = "";
		
		if ($doccode == $documentcode)
			$selected1 = "SELECTED";
			
		$docselect1 .= "<option $selected1 value=\"$documentcode\">$documentname</option>";
	}
//+++++++++++++++  END OF DOCUMENTS  ++++++++++++++++++//

//+++++++++++++++  LICENSE  ++++++++++++++++++//

$qrylicenses = mysql_query("SELECT DOCCODE,DOCUMENT FROM crewdocuments WHERE TYPE='L' AND STATUS=1 ORDER BY DOCUMENT") or die(mysql_error());
$licselect1 = "<option selected value=\"\">--Select One--</option>";
	while($rowlicenses=mysql_fetch_array($qrylicenses))
	{
		$licensecode = $rowlicenses["DOCCODE"];
		$licensename = $rowlicenses["DOCUMENT"];
		
		$selected1 = "";
		
		if ($liccode == $licensecode)
			$selected1 = "SELECTED";
			
		$licselect1 .= "<option $selected1 value=\"$licensecode\">$licensename</option>";
	}
	
//+++++++++++++++  END OF LICENSE  ++++++++++++++++++//

//+++++++++++++++  CERTIFICATE  ++++++++++++++++++//

$qrycertificates = mysql_query("SELECT DOCCODE,DOCUMENT FROM crewdocuments WHERE TYPE='C' AND STATUS=1 ORDER BY DOCUMENT") or die(mysql_error());
$certselect1 = "<option selected value=\"\">--Select One--</option>";
	while($rowcertificates=mysql_fetch_array($qrycertificates))
	{
		$certificatecode = $rowcertificates["DOCCODE"];
		$certificatename = $rowcertificates["DOCUMENT"];
		
		$selected1 = "";
		
		if ($certcode == $certificatecode)
			$selected1 = "SELECTED";
			
		$certselect1 .= "<option $selected1 value=\"$certificatecode\">$certificatename</option>";
	}
	
//+++++++++++++++  END OF CERTIFICATE  ++++++++++++++++++//

//+++++++++++++++  LICENSE / DOCUMENT / CERTIFICATE (RANKCODE)  ++++++++++++++++++//

$qrydocranklist = mysql_query("SELECT RANKCODE,RANK FROM rank WHERE STATUS=1 ORDER BY RANKING") or die(mysql_error());
$docselect9 = "<option selected value=\"\">--Select One--</option>";
	while($rowdocranklist=mysql_fetch_array($qrydocranklist))
	{
		$docrcode = $rowdocranklist["RANKCODE"];
		$docrnk = $rowdocranklist["RANK"];
		
		$selected1 = "";
		
		if ($docrcode == $docrankcode)
			$selected1 = "SELECTED";
			
		$docselect9 .= "<option $selected1 value=\"$docrcode\">$docrnk</option>";
	}
	
$qrylicranklist = mysql_query("SELECT RANKCODE,RANK FROM rank WHERE STATUS=1 ORDER BY RANKING") or die(mysql_error());
$licselect9 = "<option selected value=\"\">--Select One--</option>";
	while($rowlicranklist=mysql_fetch_array($qrylicranklist))
	{
		$licrcode = $rowlicranklist["RANKCODE"];
		$licrnk = $rowlicranklist["RANK"];
		
		$selected1 = "";
		
		if ($licrcode == $licrankcode)
			$selected1 = "SELECTED";
			
		$licselect9 .= "<option $selected1 value=\"$licrcode\">$licrnk</option>";
	}
	
$qrycerranklist = mysql_query("SELECT RANKCODE,RANK FROM rank WHERE STATUS=1 ORDER BY RANKING") or die(mysql_error());
$certselect9 = "<option selected value=\"\">--Select One--</option>";
	while($rowcertranklist=mysql_fetch_array($qrycerranklist))
	{
		$certrcode = $rowcertranklist["RANKCODE"];
		$certrnk = $rowcertranklist["RANK"];
		
		$selected1 = "";
		
		if ($certrcode == $certrankcode)
			$selected1 = "SELECTED";
			
		$certselect9 .= "<option $selected1 value=\"$certrcode\">$certrnk</option>";
	}

//+++++++++++++++  LICENSE / DOCUMENT / CERTIFICATE (RANKCODE) ++++++++++++++++++//




//+++++++++++++++  EXPERIENCE  ++++++++++++++++++//

//VESSEL TYPE (BULK/CARRIER)
$qryvesseltype = mysql_query("SELECT VESSELTYPECODE,VESSELTYPE FROM vesseltype") or die(mysql_error());
$expselect1 = "<option selected value=\"\">--Select One--</option>";
	while($rowvesseltype=mysql_fetch_array($qryvesseltype))
	{
		$vesseltypecode = $rowvesseltype["VESSELTYPECODE"];
		$vesseltype = $rowvesseltype["VESSELTYPE"];
		
		$selected1 = "";
		
		if ($expvesseltypecode == $vesseltypecode)
			$selected1 = "SELECTED";
			
		$expselect1 .= "<option $selected1 value=\"$vesseltypecode\">$vesseltype</option>";
	}

//RANK CODE
$qryrank = mysql_query("SELECT RANKCODE,RANK FROM rank WHERE STATUS=1") or die(mysql_error());
$expselect2 = "<option selected value=\"\">--Select One--</option>";
	while($rowrank=mysql_fetch_array($qryrank))
	{
		$rankcode = $rowrank["RANKCODE"];
		$rank = $rowrank["RANK"];
		
		$selected2 = "";
		
		if ($exprankcode == $rankcode)
			$selected2 = "SELECTED";
			
		$expselect2 .= "<option $selected2 value=\"$rankcode\">$rank</option>";
	}
	
//TRADEROUTE
$qrytraderoute = mysql_query("SELECT TRADEROUTECODE,TRADEROUTE FROM traderoute WHERE STATUS=1") or die(mysql_error());
$expselect3 = "<option selected value=\"\">--Select One--</option>";
	while($rowtraderoute=mysql_fetch_array($qrytraderoute))
	{
		$traderoutecode = $rowtraderoute["TRADEROUTECODE"];
		$traderoute = $rowtraderoute["TRADEROUTE"];
		
		$selected3 = "";
		
		if ($exptraderoutecode == $traderoutecode)
			$selected3 = "SELECTED";
			
		$expselect3 .= "<option $selected3 value=\"$traderoutecode\">$traderoute</option>";
	}
	
//MANNING CODE
$qrymanning = mysql_query("SELECT MANNINGCODE,MANNING FROM manning") or die(mysql_error());
$expselect4 = "<option selected value=\"\">--Select One--</option>";
	while($rowmanning=mysql_fetch_array($qrymanning))
	{
		$manningcode = $rowmanning["MANNINGCODE"];
		$manning = $rowmanning["MANNING"];
		
		$selected4 = "";
		
		if ($expmanningcode == $manningcode)
			$selected4 = "SELECTED";
			
		$expselect4 .= "<option $selected4 value=\"$manningcode\">$manning</option>";
	}
	
$qryreason = mysql_query("SELECT DISEMBREASONCODE,REASON FROM disembarkreason") or die(mysql_error());
$expselect5 = "<option selected value=\"\">--Select One--</option>";
	while($rowreason=mysql_fetch_array($qryreason))
	{
		$reasoncode = $rowreason["DISEMBREASONCODE"];
		$reason = $rowreason["REASON"];
		
		$selected5 = "";
		
		if ($expdisembreasoncode == $reasoncode)
			$selected5 = "SELECTED";
			
		$expselect5 .= "<option $selected5 value=\"$reasoncode\">$reason</option>";
	}
	
$qryflag = mysql_query("SELECT COUNTRYCODE,COUNTRY FROM country") or die(mysql_error());
$expselect6 = "<option selected value=\"\">--Select One--</option>";
	while($rowflag=mysql_fetch_array($qryflag))
	{
		$flagcode = $rowflag["COUNTRYCODE"];
		$flag = $rowflag["COUNTRY"];
		
		$selected6 = "";
		
		if ($expflagcode == $flagcode)
			$selected6 = "SELECTED";
			
		$expselect6 .= "<option $selected6 value=\"$flagcode\">$flag</option>";
	}
//+++++++++++++++  END OF EXPERIENCE  ++++++++++++++++++//


//+++++++++++++++  EMPLOYMENT  ++++++++++++++++++//

//RANK CODE
//$qryrank = mysql_query("SELECT RANKCODE,RANK FROM rank WHERE STATUS=1") or die(mysql_error());
//$empselect1 = "<option selected value=\"\">--Select One--</option>";
//	while($rowrank=mysql_fetch_array($qryrank))
//	{
//		$rankcode = $rowrank["RANKCODE"];
//		$rank = $rowrank["RANK"];
//		
//		$selected1 = "";
//		
//		if ($emprank == $rankcode)
//			$selected1 = "SELECTED";
//			
//		$empselect1 .= "<option $selected1 value=\"$rankcode\">$rank</option>";
//	}

//+++++++++++++++  END OF EMPLOYMENT  ++++++++++++++++++//

//+++++++++++++++  MEDICAL  ++++++++++++++++++//

$qryclinic = mysql_query("SELECT CLINICID,CLINIC FROM clinic") or die(mysql_error());
$medselect1 = "<option selected value=\"\">--Select One--</option>";
	while($rowclinic=mysql_fetch_array($qryclinic))
	{
		$clinicid = $rowclinic["CLINICID"];
		$clinic = $rowclinic["CLINIC"];
		
		$selected1 = "";
		
		if ($medclinicid == $clinicid)
			$selected1 = "SELECTED";
			
		$medselect1 .= "<option $selected1 value=\"$clinicid\">$clinic</option>";
	}

$qryrecommend = mysql_query("SELECT RECOMMENDCODE,RECOMMENDATION FROM clinicrecommend") or die(mysql_error());
$medselect2 = "<option selected value=\"\">--Select One--</option>";
	while($rowrecommend=mysql_fetch_array($qryrecommend))
	{
		$recommendcode = $rowrecommend["RECOMMENDCODE"];
		$recommendation= $rowrecommend["RECOMMENDATION"];
		
		$selected2 = "";
		
		if ($medrecommendcode == $recommendcode)
			$selected2 = "SELECTED";
			
		$medselect2 .= "<option $selected2 value=\"$recommendcode\">$recommendation</option>";
	}

//+++++++++++++++  END OF MEDICAL  ++++++++++++++++++//



//$searchselect = "<option selected value=\"\">--Select--</option>";

$search = "S" . $searchby;
$$search = "SELECTED";	

$searchselect .= "<option $S1 value=\"1\">APPLICANT NO.</option>";
//$searchselect .= "<option $S0 value=\"2\">SSS NO.</option>";



//*************************************************  END OF LISTINGS  *************************************************//




if ($applicantno != "")
{
	$hidebtnsave = "visibility:hidden;";
	$showsave = "";
	$disablebtnnext = "";
	
	// FILL-UP LISTS BASED ON "CURRENT PAGE"
	
	$qryfamilylist = mysql_query("SELECT CREWRELID,CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,CONCAT(ADDRESS,', ',TOWN,' ',PROVINCE) AS ADDRESS, 
								fr.RELATION,TELNO,DEPENDENT,ABROAD
								FROM crewfamily cf
								LEFT JOIN familyrelation fr ON fr.RELCODE=cf.RELCODE
								LEFT JOIN addrprovince ap ON ap.PROVCODE=cf.PROVINCECODE
								LEFT JOIN addrtown at ON at.PROVCODE=cf.PROVINCECODE AND at.TOWNCODE=cf.TOWNCODE
								LEFT JOIN addrbarangay ab ON ab.PROVCODE=cf.PROVINCECODE AND ab.TOWNCODE=cf.TOWNCODE AND ab.BRGYCODE=cf.BARANGAYCODE
								WHERE APPLICANTNO=$applicantno") or die(mysql_error());
	
	$qryeduclist = mysql_query("SELECT IDNO,IF(ce.SCHOOLID IS NULL,ce.SCHOOLOTHERS,ms.SCHOOL) AS SCHOOL,
								IF(ce.COURSEID IS NULL,ce.COURSEOTHERS,mc.COURSE) AS COURSE,
								IF(ce.DATEGRADUATED IS NULL,'',ce.DATEGRADUATED) AS DATEGRADUATED,
								ce.ADDRESS,ce.CONTACTNO,ce.CONTACTPERSON,
								ce.LEVEL,ce.REMARKS
								FROM creweducation ce
								LEFT JOIN maritimeschool ms ON ce.SCHOOLID=ms.SCHOOLID
								LEFT JOIN maritimecourses mc ON ce.COURSEID=mc.COURSEID
								WHERE ce.APPLICANTNO=$applicantno") or die(mysql_error());
	
	$qrydoclist = mysql_query("SELECT IDNO,cd.DOCUMENT,cds.DOCNO,cds.DATEISSUED,cds.DATEEXPIRED,cd.DOCCODE,cd.HASEXPIRY,cds.RANKCODE
								FROM crewdocstatus cds
								LEFT JOIN crewdocuments cd ON cd.DOCCODE=cds.DOCCODE
								WHERE cd.TYPE='D' AND STATUS=1 AND cds.APPLICANTNO=$applicantno
								ORDER BY cd.DOCUMENT") or die(mysql_error());
	
	$qryliclist = mysql_query("SELECT IDNO,cd.DOCUMENT,cds.DOCNO,cds.DATEISSUED,cds.DATEEXPIRED,cd.DOCCODE,cd.HASEXPIRY,cds.RANKCODE
								FROM crewdocstatus cds
								LEFT JOIN crewdocuments cd ON cd.DOCCODE=cds.DOCCODE
								WHERE cd.TYPE='L' AND STATUS=1 AND cds.APPLICANTNO=$applicantno
								ORDER BY cd.DOCUMENT") or die(mysql_error());
	
	$qrycertlist = mysql_query("SELECT IDNO,cd.DOCUMENT,ccs.DOCNO,ccs.DATEISSUED,ccs.GRADE,cd.DOCCODE,ccs.RANKCODE
								FROM crewcertstatus ccs
								LEFT JOIN crewdocuments cd ON cd.DOCCODE=ccs.DOCCODE
								WHERE cd.TYPE='C' AND STATUS=1 AND ccs.APPLICANTNO=$applicantno
								ORDER BY cd.DOCUMENT") or die(mysql_error());
	
	$qryexperiencelist = mysql_query("SELECT IDNO,ce.VESSEL,ce.ENGINETYPE,r.ALIAS1 AS RANKALIAS,
								IF (ce.VESSELTYPECODE = '',ce.VESSELTYPEOTHERS,vt.VESSELTYPE) AS VESSELTYPE,
								IF (ce.TRADEROUTECODE = '',ce.TRADEROUTEOTHERS,t.TRADEROUTE) AS TRADEROUTE,
								IF (ce.MANNINGCODE = '',ce.MANNINGOTHERS,m.MANNING) AS MANNING,
								IF((ce.DISEMBREASONCODE <> 'OTH'),dr.REASON,ce.REASONOTHERS) AS REASON
								FROM crewexperience ce
								LEFT JOIN rank r ON r.RANKCODE=ce.RANKCODE
								LEFT JOIN vesseltype vt ON vt.VESSELTYPECODE=ce.VESSELTYPECODE
								LEFT JOIN traderoute t ON t.TRADEROUTECODE=ce.TRADEROUTECODE
								LEFT JOIN manning m ON m.MANNINGCODE=ce.MANNINGCODE
								LEFT JOIN disembarkreason dr ON dr.DISEMBREASONCODE=ce.DISEMBREASONCODE
								WHERE ce.APPLICANTNO=$applicantno") or die(mysql_error());
	
	$qryemploymentlist = mysql_query("SELECT * FROM employhistory WHERE APPLICANTNO=$applicantno") or die(mysql_error());
	
	$qrymedicallist = mysql_query("
				SELECT cm.IDNO,cm.CLINICID,c.CLINIC,cm.DATECHECKUP,cm.DIAGNOSIS,cr.RECOMMENDATION,cm.REMARKS,cm.HBATEST
				FROM crewmedical cm
				LEFT JOIN clinic c ON c.CLINICID = cm.CLINICID
				LEFT JOIN clinicrecommend cr ON cr.RECOMMENDCODE=cm.RECOMMENDCODE
				WHERE cm.APPLICANTNO=$applicantno
				ORDER BY cm.DATECHECKUP DESC
	") or die(mysql_error());
	
	$qryreferencelist = mysql_query("SELECT * FROM applicantreference WHERE APPLICANTNO=$applicantno") or die(mysql_error());
	

	//GET CURRENT STATUS OF APPLICATION
//	$qrycheckapplicant = mysql_query("SELECT AGREED,PRINTED,APPROVED FROM applicant WHERE APPLICANTNO=$applicantno") or die(mysql_error());
//	
//	$rowcheckapplicant = mysql_fetch_array($qrycheckapplicant);
//	
//	$agreedx = $rowcheckapplicant["AGREED"];
//	$printedx = $rowcheckapplicant["PRINTED"];
//	$approvedx = $rowcheckapplicant["APPROVED"];	
	
}	
else 
{
	$hidebtnsave = "";
	$disablebtnnext = "disabled=\"disabled\"";
	$showsave = "disabled=\"disabled\"";
}

if ($currentpage==0)
	$disablebtnback = "disabled=\"disabled\"";
else 
	$disablebtnback = "";	
	
//if ($approvedx != "")
//	$disableapproved = "disabled=\"disabled\"";
//else 
//	$disableapproved = "";

	
echo "
<html>
<head>
<title>Crew Information - Manual Encoding</title>
<script language=\"javascript\" src=\"popcalendar.js\"></script>	
<script language=\"javascript\" src=\"veripro.js\"></script>	
	
<link rel=\"stylesheet\" href=\"veripro.css\">

	<script>
									
		var displaypage=new Array('personal','family','education','documents','license',
									'certificate','experience','employment','medical');
									
		
		function changepage(x,y,z)
		{
			hidepage= displaypage[y];
			document.getElementById(hidepage).style.display='none';
			
			if (z==10) //PREV - NEXT
			{
				if (x==0)
					getpage=y*1-1;
				else
					getpage=y*1+1;
			}
			else
				getpage=z;
				
			var openpage= displaypage[getpage];
			document.getElementById(openpage).style.display='block';
			document.crewinfo.currentpage.value = getpage;
			
			if (getpage != 0)
				document.crewinfo.btnback.disabled = false;
			else
				document.crewinfo.btnback.disabled = true;		
					
			if (getpage==8)
				document.crewinfo.btnnext.disabled = true;
			else
				document.crewinfo.btnnext.disabled = false;
				
		}
		
		function previewfront(form) 
		{
		    var result = window.showModalDialog(\"repappfront3.php?applicantno=$applicantno\", form, 
		        \"dialogWidth:900px; dialogHeight:700px; center:yes; resizable:yes\");
		}
	
		
		function checkadd(x)
		{
			var rem = '';
			
			with (document.crewinfo)
			{
				switch (x)
				{
					case '0':
						if(fname.value=='' || fname.value==null)
							if(rem=='')
								rem='Family Name';
							else
								rem=rem + ',Family Name';
						if(gname.value=='' || gname.value==null)
							if(rem=='')
								rem='Given Name';
							else
								rem=rem + ',Given Name';
//						if(mname.value=='' || mname.value==null)
//							if(rem=='')
//								rem='Middle Name';
//							else
//								rem=rem + ',Middle Name';
//						if(crewaddress.value=='' || crewaddress.value==null)
//							if(rem=='')
//								rem='Address';
//							else
//								rem=rem + ',Address';
//						if(crewmunicipality.value=='' || crewmunicipality.value==null)
//							if(rem=='')
//								rem='Municipality';
//							else
//								rem=rem + ',Municipality';
//						if(crewcity.value=='' || crewcity.value==null)
//							if(rem=='')
//								rem='City';
//							else
//								rem=rem + ',City';
						if(crewbdate.value=='' || crewbdate.value==null)
							if(rem=='')
								rem='Birthdate';
							else
								rem=rem + ',Birthdate';
					break
					case '1':
						if(familyfname.value=='' || familyfname.value==null)
							if(rem=='')
								rem='Family Name';
							else
								rem=rem + ',Family Name';
						if(familygname.value=='' || familygname.value==null)
							if(rem=='')
								rem='Given Name';
							else
								rem=rem + ',Given Name';

						if(familyrelcode.value=='' || familyrelcode.value==null)
						{
							if(familyrelothers.value=='' || familyrelothers.value==null)
							{
								if(rem=='')
									rem='Relationship';
								else
									rem=rem + ',Relationship';		
							}
						}
//						if(familybdate.value=='' || familybdate.value==null)
//							if(rem=='')
//								rem='Birthdate';
//							else
//								rem=rem + ',Birthdate';
//						if(familyabroad.value=='' || familyabroad.value==null)
//							if(rem=='')
//								rem='Living Abroad';
//							else
//								rem=rem + ',Living Abroad';
//						if(familydependent.value=='' || familydependent.value==null)
//							if(rem=='')
//								rem='Dependent';
//							else
//								rem=rem + ',Dependent';
					break
					
					case '2':
						if(educschoolid.value=='' || educschoolid.value==null)
							if(educschoolothers.value=='' || educschoolothers.value==null)
							{
								if(rem=='')
									rem='School Name';
								else
									rem=rem + ',School Name';
							}

						if(educcourseid.value=='' || educcourseid.value==null)
							if(educcourseothers.value=='' || educcourseothers.value==null)
							{
								if(rem=='')
									rem='Course';
								else
									rem=rem + ',Course';
							}
						if(educdategrad.value=='' || educdategrad.value==null)
							if(rem=='')
								rem='Date Graduated';
							else
								rem=rem + ',Date Graduated';
					break
					
					case '3':
						if(doccode.value=='' || doccode.value==null)
							if(rem=='')
								rem='Document Type';
							else
								rem=rem + ',Document Type';
						if(docno.value=='' || docno.value==null)
							if(rem=='')
								rem='Document No.';
							else
								rem=rem + ',Document No.';
						if(docissued.value=='' || docissued.value==null)
							if(rem=='')
								rem='Date Issued';
							else
								rem=rem + ',Date Issued';
//						if(docexpired.value=='' || docexpired.value==null)
//							if(rem=='')
//								rem='Date Expired';
//							else
//								rem=rem + ',Date Expired';
					break
					
					case '4':
						if(liccode.value=='' || liccode.value==null)
							if(rem=='')
								rem='License Type';
							else
								rem=rem + ',License Type';
						if(licno.value=='' || licno.value==null)
							if(rem=='')
								rem='License No.';
							else
								rem=rem + ',License No.';
						if(licissued.value=='' || licissued.value==null)
							if(rem=='')
								rem='Date Issued';
							else
								rem=rem + ',Date Issued';
//						if(licexpired.value=='' || licexpired.value==null)
//							if(rem=='')
//								rem='Date Expired';
//							else
//								rem=rem + ',Date Expired';
					break
					
					case '5':
						if(certcode.value=='' || certcode.value==null)
							if(rem=='')
								rem='Certificate Type';
							else
								rem=rem + ',Certificate Type';
						if(certno.value=='' || licno.value==null)
							if(rem=='')
								rem='Certificate No.';
							else
								rem=rem + ',Certificate No.';
						if(certissued.value=='' || certissued.value==null)
							if(rem=='')
								rem='Date Issued';
							else
								rem=rem + ',Date Issued';
					break
					
					case '6':
						if(expvessel.value=='' || expvessel.value==null)
							if(rem=='')
								rem='Vessel';
							else
								rem=rem + ',Vessel';
						if(expflagcode.value=='' || expflagcode.value==null)
							if(expflagothers.value=='' || expflagothers.value==null)
							{
								if(rem=='')
									rem='Flag';
								else
									rem=rem + ',Flag';
							}
						if(expvesseltypecode.value=='' || expvesseltypecode.value==null)
							if(expvsltypeothers.value=='' || expvsltypeothers.value==null)
							{
								if(rem=='')
									rem='Vessel Type';
								else
									rem=rem + ',Vessel Type';
							}
						if(exprankcode.value=='' || exprankcode.value==null)
							if(rem=='')
								rem='Rank';
							else
								rem=rem + ',Rank';
						if(exptraderoutecode.value=='' || exptraderoutecode.value==null)
							if(exptradeothers.value=='' || exptradeothers.value==null)
							{
								if(rem=='')
									rem='Trade Route';
								else
									rem=rem + ',Trade Route';
							}
						if(expmanningcode.value=='' || expmanningcode.value==null)
							if(expmanningothers.value=='' || expmanningothers.value==null)
							{
								if(rem=='')
									rem='Manning Agency';
								else
									rem=rem + ',Manning Agency';
							}
						if(expembdate.value=='' || expembdate.value==null)
							if(rem=='')
								rem='Date Embark';
							else
								rem=rem + ',Date Embark';
						if(expdisembdate.value=='' || expdisembdate.value==null)
							if(rem=='')
								rem='Date Disembark';
							else
								rem=rem + ',Date Disembark';
								
					break
					
					case '7':
						if(empemployer.value=='' || empemployer.value==null)
							if(rem=='')
								rem='Employer';
							else
								rem=rem + ',Employer';
						if(empposition.value=='' || empposition.value==null)
							if(rem=='')
								rem='Position';
							else
								rem=rem + ',Position';
						if(empdatefrom.value=='' || empdatefrom.value==null)
							if(rem=='')
								rem='Date From';
							else
								rem=rem + ',Date From';
						if(empdateto.value=='' || empdateto.value==null)
							if(rem=='')
								rem='Date To';
							else
								rem=rem + ',Date To';
					break
					
					case '8':
						if(medclinicid.value=='' || medclinicid.value==null)
							if(rem=='')
								rem='Clinic';
							else
								rem=rem + ',Clinic';
						if(medcheckupdate.value=='' || medcheckupdate.value==null)
							if(rem=='')
								rem='Checkup Date';
							else
								rem=rem + ',Checkup Date';
						if(meddiagnosis.value=='' || meddiagnosis.value==null)
							if(rem=='')
								rem='Diagnosis';
							else
								rem=rem + ',Diagnosis';
						if(medrecommendcode.value=='' || medrecommendcode.value==null)
							if(rem=='')
								rem='Recommendation';
							else
								rem=rem + ',Recommendation';
					break
					
				}
			

				if(rem=='')
				{
					submit();
				}
				else
					alert('Invalid Input: ' + rem);				
			}		
		}
	</script>
	
</head>

<body onload=\"if('$double'=='1'){document.crewinfo.crewcode.value='';document.crewinfo.crewcode.focus();}\" style=\"background-color:White;\">

<span class=\"wintitle\">CREW INFORMATION - (FOR ENCODING PURPOSES ONLY)</span>

	<div style=\"width:100%;margin:10 20 0 20;\">
	
	<form name=\"crewinfo\" method=\"POST\">
	
					<!-- FOR MULTIPLE RESULTS  -->
						
					<div id=\"multiple\" style=\"position:absolute;top:100px;left:350px;width:600px;height:200px;background-color:#6699FF;
									border:2px solid black;overflow:auto;$showmultiple \">
						<span class=\"sectiontitle\">MULTIPLE ENTRY FOUND</span>
						
						<center>
							<a href=\"#\" style=\"font-size:0.8em;font-weight:Bold;\" onclick=\"document.getElementById('multiple').style.display='none';\">Close Window</a>
						</center>
						<br />
						
						<table width=\"100%\" class=\"listcol\">
							<tr>
								<th width=\"10%\">APP NO</th>
								<th width=\"15%\">CREW CODE</th>
								<th width=\"10%\">RANK</th>
								<th width=\"20%\">FNAME</th>
								<th width=\"20%\">GNAME</th>
								<th width=\"20%\">MNAME</th>
								<th width=\"5%\">STATUS</th>
							</tr>
						";
							if ($multiple == 1)
							{
								while ($rowmultisearch = mysql_fetch_array($qrysearch))
								{
									$appno = $rowmultisearch["APPLICANTNO"];
									
									//GPA - REMOVED " AND STATUS=1" IN WHERE PART
									
									$qrygetinfo = mysql_query("SELECT APPLICANTNO,CREWCODE,FNAME,GNAME,MNAME,STATUS
																FROM crew 
																WHERE APPLICANTNO=$appno
															") or die(mysql_error());
									
									$rowgetinfo = mysql_fetch_array($qrygetinfo);
					
									$info1 = $rowgetinfo["APPLICANTNO"];
									$info2 = $rowgetinfo["CREWCODE"];
									$info3 = $rowgetinfo["FNAME"];
									$info4 = $rowgetinfo["GNAME"];
									$info5 = $rowgetinfo["MNAME"];
									$stat = $rowgetinfo["STATUS"];
									if ($rowgetinfo["STATUS"] == 1)
										$info6 = "ACTIVE";
									else 
										$info6 = "INACTIVE";
										
									$qrycurrentrank = mysql_query("SELECT z.DATEDISEMB,v.MANAGEMENTCODE,v.DIVCODE,z.RANKCODE,r.RANK,r.ALIAS1 FROM 
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
									
									$styleinactive = "";
										
									if ($stat == 0)
									{
										$styleinactive = "style=\"background-color:Red;color:White;text-decoration:line-through;\"";
									}
										
									echo "
									<tr $mouseovereffect style=\"cursor:pointer;\" ondblclick=\"
													applicantno.value='$info1';submit();
													document.getElementById('multiple').style.display='none';
													\">
										<td $styleinactive align=\"center\">$info1</td>
										<td $styleinactive align=\"center\">&nbsp;$info2</td>
										<td $styleinactive align=\"center\" title=\"$currentrank\">$currentrankalias</td>
										<td $styleinactive>$info3&nbsp;</td>
										<td $styleinactive>$info4&nbsp;</td>
										<td $styleinactive>$info5&nbsp;</td>
										<td $styleinactive align=\"center\">$info6</td>
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
						
					<!-- END OF MULTIPLE RESULTS  -->
	
	
	<div style=\"width:100%;\">
		<center>
		<table style=\"width:80%;margin-top:5px;\" border=1>
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
					
					<input type=\"button\" name=\"btnreset\" value=\"Reset All\" onclick=\"actiontxt.value='reset';currentpage.value=0;submit();\" />
					<input type=\"button\" value=\"Save\" $showsave $disableapproved onclick=\"actiontxt.value='save';checkadd(currentpage.value);\" />
					";
					
					if (!empty($applicantno))
					{
						echo "
						<input type=\"button\" value=\"View Data Sheet\" $showsave $disableapproved 
							onclick=\"openWindow('crewdatasheet.php?applicantno=$applicantno','crewdatasheet',0,0);\" />
						";
					}
				echo "
				</td>
			</tr>
		</table>
		<br />
		
		<div style=\"width:39%;height:40px;float:left;background-color:Black;text-align:left;padding:0 0 0 5;\">
			<span style=\"font-size:0.8em;font-weight:Bold;color:Lime;\" ><i>Crew Name:</i></span><br />
			<span style=\"font-size:1em;font-weight:Bold;color:Yellow;\">
			";
				if (empty($applicantno))
					echo "&nbsp;";
				else 
					echo " $fname, $gname $mname ";
			echo "
			</span>
			<span style=\"font-size:0.9em;color:Orange;\">
			";
			
			if (!empty($crewscholartype))
				echo "&nbsp;&nbsp;( $crewscholartype )";
				
			if (!empty($crewfasttracktype))
				echo "&nbsp;&nbsp;( $crewfasttracktype )";
				
			echo "
			</span>
		</div>
		
		<div style=\"width:60%;height:35px;float:right;background-color:Gray;color:White;text-align:right;\">
		";
			$stylebtn = "style=\"font-size:0.55em;font-weight:Bold;border:0;background-color:Green;color:White;cursor:pointer;\"";
			
		echo "
			<input type=\"button\" value=\"PERSONAL\" $stylebtn onclick=\"changepage(1,currentpage.value,0)\" />
			<input type=\"button\" value=\"FAMILY\" $stylebtn onclick=\"changepage(1,currentpage.value,1)\" />
			<input type=\"button\" value=\"EDUCATION\" $stylebtn onclick=\"changepage(1,currentpage.value,2)\" />
			<input type=\"button\" value=\"DOCUMENTS\" $stylebtn onclick=\"changepage(1,currentpage.value,3)\" />
			<input type=\"button\" value=\"LICENSES\" $stylebtn onclick=\"changepage(1,currentpage.value,4)\" />
			<input type=\"button\" value=\"CERTIFICATES\" $stylebtn onclick=\"changepage(1,currentpage.value,5)\" />
			<input type=\"button\" value=\"EXPERIENCE(OUTSIDE)\" $stylebtn onclick=\"changepage(1,currentpage.value,6)\" />
			<input type=\"button\" value=\"EMPLOYMENT\" $stylebtn onclick=\"changepage(1,currentpage.value,7)\" />
			<input type=\"button\" value=\"MEDICAL\" $stylebtn onclick=\"changepage(1,currentpage.value,8)\" />
		</div>
		
		</center>
	</div>
		
";

echo "
	<div id=\"personal\" style=\"width:100%;border:1px solid Navy;display:none;$div0\">
		<center>
		<span class=\"sectiontitle\">PERSONAL INFORMATION</span>
		</center>
		<br />
		
		<div style=\"width:80%;float:left;\">
		<center>
			<table class=\"listrow\" width=\"100%\">
				<tr>
					<td align=\"center\"><b>Family Name *</b><br />
						<input type=\"text\" name=\"fname\" value=\"$fname\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"30\" />
					</td>
					<td align=\"center\"><b>Given Name *</b><br />
						<input type=\"text\" name=\"gname\" value=\"$gname\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"30\" />
					</td>
					<td align=\"center\"><b>Middle Name *</b><br />
						<input type=\"text\" name=\"mname\" value=\"$mname\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"30\" />
					</td>
				</tr>
				<tr>
					<th>&nbsp;</th>
					<th align=\"center\"><b>Crew Code</b><br />
						<input type=\"text\" name=\"crewcode\" value=\"$crewcode\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" 
							size=\"15\" style=\"font-size:1.5em;font-weight:Bold;color:Red;text-align:center;\"
							onblur=\"actiontxt.value='checkcrewcode';submit();
							\" />
					</th>
					<th>&nbsp;</th>
				</tr>
			</table>
			
		</center>
		</div>
		
		<div style=\"width:20%;height:\">
";
			$dirfilename = "$basedir/$applicantno.JPG";
			if (checkpath($dirfilename))
			{
				$scale = imageScale($dirfilename,-1,110);
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
		<hr>
		
		<div style=\"width:100%;\">
			<div style=\"width:50%;float:left;padding:3px;\">
				<span class=\"title\"><u>PERMANENT ADDRESS</u></span><br />
				<table width=\"100%\" class=\"listrow\">
					<tr>
						<td align=\"left\"><b>Address *</b></td>
						<td align=\"left\"><input type=\"text\" name=\"crewaddress\" value=\"$crewaddress\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"40\" /></td>
					</tr>
					<tr>
						<th align=\"left\">Province</th>
						<td align=\"left\"><select name=\"crewprovince\" onchange=\"submit();\">
								$personalselect5
							</select>
						</td>
					</tr>
					<tr>
						<th align=\"left\">Town/City</th>
						<td align=\"left\"><select name=\"crewcity\" onchange=\"submit();\">
								$personalselect6
							</select>
						</td>
					</tr>
					<tr>
						<th align=\"left\">Barangay</th>
						<td align=\"left\"><select name=\"crewbarangay\" onchange=\"crewinfo.submit();\">
								$personalselect7
							</select>
						</td>
					</tr>
					<tr>
						<th align=\"left\">ZIP Code</th>
						<th align=\"left\"><input type=\"text\" name=\"crewzipcode\" value=\"$crewzipcode\" size=\"20\" /></th>
					</tr>
					<tr>
						<th align=\"left\">Tel. No.</th>
						<th align=\"left\"><input type=\"text\" name=\"crewtelno\" value=\"$crewtelno\" size=\"20\" /></th>
					</tr>
				</table>

			
			</div>
			<div style=\"width:48%;float:right;padding:3px;\">
				<span class=\"title\"><u>PROVINCIAL ADDRESS</u></span>
			
				<table width=\"100%\" class=\"listrow\">
					<tr>
						<th align=\"left\">Address</th>
						<td align=\"left\"><input type=\"text\" name=\"crewprovaddress\" value=\"$crewprovaddress\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"40\" /></td>
					</tr>
					<tr>
						<th align=\"left\">Province</th>
						<td align=\"left\"><select name=\"crewprovprovince\" onchange=\"crewinfo.submit();\">
								$personalselect8
							</select>
						</td>
					</tr>
					<tr>
						<th align=\"left\">Town/City</th>
						<td align=\"left\"><select name=\"crewprovcity\" onchange=\"crewinfo.submit();\">
								$personalselect9
							</select>
						</td>
					</tr>
					<tr>
						<th align=\"left\">Barangay</th>
						<td align=\"left\"><select name=\"crewprovbarangay\" onchange=\"crewinfo.submit();\">
								$personalselect10
							</select>
						</td>
					</tr>
					<tr>
						<th align=\"left\">ZIP Code</th>
						<th align=\"left\"><input type=\"text\" name=\"crewprovzipcode\" value=\"$crewprovzipcode\" size=\"20\" /></th>
					</tr>
					<tr>
						<th align=\"left\">Tel. No.</th>
						<th align=\"left\"><input type=\"text\" name=\"crewprovtelno\" value=\"$crewprovtelno\" size=\"20\" /></th>
					</tr>
				</table>
			</div>
		</div>
		
		<hr />
			
		<div style=\"width:100%;\">
			<div style=\"width:50%;float:left;padding:3px;\">
				<table width=\"100%\" class=\"listrow\">
					<tr>
						<td align=\"left\"><b>Birth Date *</b></td>
						<th align=\"left\">
							<input type=\"text\" name=\"crewbdate\" value=\"$crewbdate\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
							<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(crewbdate, crewbdate, 'mm/dd/yyyy', 0, 0);return false;\">
							&nbsp;&nbsp;&nbsp;(mm/dd/yyy)
						</th>
					</tr>
					<tr>
						<th align=\"left\">Birth Place</th>
						<th align=\"left\"><input type=\"text\" name=\"crewbplace\" value=\"$crewbplace\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"30\" /></th>
					</tr>
					<tr>
						<th align=\"left\">Gender</th>
						<th align=\"left\"><select name=\"crewgender\">
								$personalselect3
							</select>
						</th>
					</tr>
					<tr>
						<th align=\"left\">Cel. Phone 1</th>
						<th align=\"left\"><input type=\"text\" name=\"crewcelphone1\" value=\"$crewcelphone1\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"30\" /></th>
					</tr>
					<tr>
						<th align=\"left\">Cel. Phone 2</th>
						<th align=\"left\"><input type=\"text\" name=\"crewcelphone2\" value=\"$crewcelphone2\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"30\" /></th>
					</tr>
					<tr>
						<th align=\"left\">Cel. Phone 3</th>
						<th align=\"left\"><input type=\"text\" name=\"crewcelphone3\" value=\"$crewcelphone3\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"30\" /></th>
					</tr>
					<tr>
						<th>&nbsp;</th>
					</tr>
					<tr>
						<th align=\"left\">Recommended By</th>
						<th align=\"left\"><input type=\"text\" name=\"crewrecommendedby\" value=\"$crewrecommendedby\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"30\" /></th>
					</tr>
				</table>
			</div>
			<div style=\"width:48%;float:right;padding:3px;\">
				<table width=\"100%\" class=\"listrow\">
					<tr>
						<th align=\"left\">Civil Status</th>
						<th align=\"left\">
							<select name=\"crewcivilstatus\">
								$personalselect4
							</select>			
						</th>
					</tr>

					<tr>
						<th align=\"left\">Religion</th>
						<th align=\"left\"><input type=\"text\" name=\"crewreligion\" value=\"$crewreligion\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"20\" /></th>
					</tr>
					<tr>
						<th align=\"left\">SSS No.</th>
						<th align=\"left\"><input type=\"text\" name=\"crewsss\" value=\"$crewsss\" size=\"20\" /></th>
					</tr>
					<tr>
						<th align=\"left\">TIN No.</th>
						<th align=\"left\"><input type=\"text\" name=\"crewtin\" value=\"$crewtin\" size=\"20\" /></th>
					</tr>
					<tr>
						<th align=\"left\">Weight (kls)</th>
						<th align=\"left\"><input type=\"text\" name=\"crewweight\" value=\"$crewweight\" size=\"3\" maxlength=\"3\" /></th>
					</tr>
					<tr>
						<th align=\"left\">Height (cm)</th>
						<th align=\"left\"><input type=\"text\" name=\"crewheight\" value=\"$crewheight\" size=\"3\" maxlength=\"3\" /></th>
					</tr>
					<tr>
						<th align=\"left\">Shoe Size</th>
						<th align=\"left\"><input type=\"text\" name=\"crewshoesize\" value=\"$crewshoesize\" size=\"5\" maxlength=\"5\" /></th>
					</tr>
					<tr>
						<th align=\"left\">Email</th>
						<th align=\"left\"><input type=\"text\" name=\"crewemail\" value=\"$crewemail\" size=\"40\" /></th>
					</tr>			
				</table>	
			</div>
		</div>
		
		<br />
		<center>
		<input type=\"button\" style=\"$hidebtnsave\" value=\"Save to continue filling up Application Form\" onclick=\"actiontxt.value='getappno';checkadd(currentpage.value);\"
		</center>
		
		<br />
	</div>
	
	<div id=\"family\" style=\"border:1px solid Navy;display:none;$div1\">	

		<span class=\"sectiontitle\">FAMILY BACKGROUND</span>
		
		<br /><br />
	
		<center>
		<table class=\"listrow\" width=\"80%\">
			<tr>
				<td><b>Family Name *</b><br />
					<input type=\"text\" name=\"familyfname\" value=\"$familyfname\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"30\" />
				</td>
				<td><b>Given Name *</b><br />
					<input type=\"text\" name=\"familygname\" value=\"$familygname\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"30\" />
				</td>
				<th>Middle Name<br />
					<input type=\"text\" name=\"familymname\" value=\"$familymname\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"30\" />
				</th>
			</tr>
		</table>
		<br />
		<table width=\"60%\" class=\"listrow\">
			<tr>
				<td align=\"left\"><b>Relationship *</b></td>
				<td align=\"left\">
					<select name=\"familyrelcode\">
						$familyselect1
					</select>		
				</td>
			</tr>	
			<tr>
				<th align=\"left\">Relationship (Others)</th>
				<th align=\"left\"><input type=\"text\" name=\"familyrelothers\" value=\"$familyrelothers\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"40\" /></th>
			</tr>
			<tr>
				<th align=\"left\">Birth Date</th>
				<th align=\"left\">
					<input type=\"text\" name=\"familybdate\" value=\"$familybdate\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(familybdate, familybdate, 'mm/dd/yyyy', 0, 0);return false;\">
					&nbsp;&nbsp;&nbsp;(mm/dd/yyyy)
				</th>
			</tr>
			<tr>
				<th align=\"left\">Gender</th>
				<th align=\"left\"><select name=\"familygender\">
						$familyselect2
					</select>
				</th>
			</tr>
			
				<tr>
					<th align=\"left\">Address</th>
					<td align=\"left\"><input type=\"text\" name=\"familyaddress\" value=\"$familyaddress\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"40\" /></td>
				</tr>
				<tr>
					<th align=\"left\">Province</th>
					<td align=\"left\"><select name=\"familyprovince\" onchange=\"crewinfo.submit();\">
							$familyselect7
						</select>
					</td>
				</tr>
				<tr>
					<th align=\"left\">Town/City</th>
					<td align=\"left\"><select name=\"familycity\" onchange=\"crewinfo.submit();\">
							$familyselect8
						</select>
					</td>
				</tr>
				<tr>
					<th align=\"left\">Barangay</th>
					<td align=\"left\"><select name=\"familybarangay\" onchange=\"crewinfo.submit();\">
							$familyselect9
						</select>
					</td>
				</tr>
<!--		
			<tr>
				<td align=\"left\"><b>Address *</b></td>
				<td align=\"left\"><input type=\"text\" name=\"familyaddress\" value=\"$familyaddress\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"40\" /></td>
			</tr>
			<tr>
				<td align=\"left\"><b>Municipality *</b></td>
				<td align=\"left\"><input type=\"text\" name=\"familymunicipality\" value=\"$familymunicipality\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"40\" /></td>
			</tr>
			<tr>
				<td align=\"left\"><b>City *</b></td>
				<td align=\"left\"><input type=\"text\" name=\"familycity\" value=\"$familycity\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"40\" /></td>
			</tr>
-->
			<tr>
				<th align=\"left\">ZIP Code</th>
				<th align=\"left\"><input type=\"text\" name=\"familyzipcode\" value=\"$familyzipcode\" size=\"20\" /></th>
			</tr>
			<tr>
				<th align=\"left\">Country</th>
				<th align=\"left\"><select name=\"familycountry\">
						$familyselect3
					</select>
				</th>
			</tr>		
			<tr>
				<th align=\"left\">Tel. No.</th>
				<th align=\"left\"><input type=\"text\" name=\"familytelno\" value=\"$familytelno\" size=\"20\" /></th>
			</tr>
			<tr>
				<th align=\"left\">Email</th>
				<th align=\"left\"><input type=\"text\" name=\"familyemail\" value=\"$familyemail\" size=\"40\" /></th>
			</tr>
			<tr>
				<th align=\"left\">Job Status</th>
				<th align=\"left\"><select name=\"familyjobstatus\">
						$familyselect4
					</select>
				</th>
			</tr>			
			<tr>
				<th align=\"left\">Occupation</th>
				<th align=\"left\"><input type=\"text\" name=\"familyoccupation\" value=\"$familyoccupation\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"20\" /></th>
			</tr>
			<tr>
				<td align=\"left\"><b>Dependent? *</b></td>
				<td align=\"left\"><select name=\"familydependent\" >
						$familyselect5
					</select>
				</td>
			</tr>			
			<tr>
				<td align=\"left\"><b>Living Abroad? *</b></td>
				<td align=\"left\"><select name=\"familyabroad\" >
						$familyselect6
					</select>
				</td>
			</tr>	
";

			if ($actiontxt != "edit")
echo "
			<tr>
				<td align=\"left\">&nbsp;</td>
				<td align=\"left\"><input type=\"button\" value=\"Add to list...\" $disableapproved onclick=\"actiontxt.value='addlist';checkadd(currentpage.value);\" /></td>
			</tr>
";
			else 
echo "
			<tr>
				<td align=\"left\">&nbsp;</td>
				<td align=\"left\">
					<input type=\"button\" value=\"Save Entry\" onclick=\"actiontxt.value='editsave';editid.value='$editid';checkadd(currentpage.value);\" />
					<input type=\"button\" value=\"Cancel\" onclick=\"actiontxt.value='cancel';submit();\" />
				</td>
			</tr>
";
			
echo "	
		</table>
		</center>
		<br />
		
		<div style=\"width:100%;overflow:auto;height:120px;background-color:#DCDCDC;\">
			<table width=\"100%\" class=\"listcol\">
				<tr>
					<th>NAME</th>
					<th>RELATION</th>
					<th>ADDRESS</th>
					<th>TELNO</th>
					<th>DEPENDENT</th>
					<th>ABROAD</th>
					<th>&nbsp;</th>
				</tr>
";		
				
				while ($rowfamilylist = mysql_fetch_array($qryfamilylist))
				{
					$family1 = $rowfamilylist["NAME"];
					$family2 = $rowfamilylist["RELATION"];
					$family3 = $rowfamilylist["ADDRESS"];
					$family4 = $rowfamilylist["TELNO"];
					if ($rowfamilylist["DEPENDENT"] == 0)
						$family5 = "NO";
					else 
						$family5 = "YES";
						
					if ($rowfamilylist["ABROAD"] == 0)
						$family6 = "NO";
					else 
						$family6 = "YES";
						
					$family7 = $rowfamilylist["CREWRELID"];
						
echo "
					<tr $mouseovereffect>
						<td align=\"left\">$family1</td>
						<td align=\"left\">$family2</td>
						<td align=\"left\">$family3</td>
						<td align=\"left\">$family4</td>
						<td align=\"left\">$family5</td>
						<td align=\"left\">$family6</td>
						<td align=\"left\">
							<input type=\"button\" value=\"EDIT\" $editmode $disableapproved style=\"color:Red;font-weight:Bold;font-size:0.9em;\" title=\"Edit\"
									onclick=\"actiontxt.value='edit';editid.value='$family7';submit();\" />
							<input type=\"button\" value=\"DEL\" $editmode $disableapproved style=\"color:Red;font-weight:Bold;font-size:0.9em;\" title=\"Delete\"
									onclick=\"actiontxt.value='delete';deleteid.value='$family7';submit();\" />
						</td>
					</tr>
";
				}
					
echo "
			</table>
		</div>	
	</div>
	

	<div id=\"education\" style=\"border:1px solid Navy;display:none;$div2\">
		<span class=\"sectiontitle\">EDUCATIONAL BACKGROUND</span>
		
";
		if ($remarks2 != "")
echo "		<center><span class=\"error\">ERROR:<br />$remarks2</span></center>	";

echo "
		<br /><br />
		<center>
		<table width=\"70%\" class=\"listrow\">
			<tr>
				<td align=\"left\"><b>School Name *</b></td>
				<td align=\"left\"><select name=\"educschoolid\">
						$educselect2
					</select>
				</td>
			</tr>
			<tr>
				<th align=\"left\">School (Others)</th>
				<th align=\"left\"><input type=\"text\" name=\"educschoolothers\" value=\"$educschoolothers\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"40\" />
				</th>
			</tr>		
			<tr>
				<td align=\"left\"><b>Course *</b></td>
				<td align=\"left\"><select name=\"educcourseid\">
						$educselect1
					</select>
				</td>
			</tr>	
			<tr>
				<th align=\"left\">Course (Others)</th>
				<th align=\"left\"><input type=\"text\" name=\"educcourseothers\" value=\"$educcourseothers\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"40\" />
				</th>
			</tr>

			<tr>
				<td align=\"left\"><b>Date Graduated *</b></td>
				<th align=\"left\">
					<input type=\"text\" name=\"educdategrad\" value=\"$educdategrad\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(educdategrad, educdategrad, 'mm/dd/yyyy', 0, 0);return false;\">
					&nbsp;&nbsp;&nbsp;(mm/dd/yyyy)
				</th>
			</tr>	
			<tr>
				<th align=\"left\">Address (Others)</th>
				<th align=\"left\"><input type=\"text\" name=\"educaddress\" value=\"$educaddress\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"80\" />
				</th>
			</tr>	
			<tr>
				<th align=\"left\">Contact No. (Others)</th>
				<th align=\"left\"><input type=\"text\" name=\"educcontactno\" value=\"$educcontactno\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"40\" />
				</th>
			</tr>	
			<tr>
				<th align=\"left\">Contact Person (Others)</th>
				<th align=\"left\"><input type=\"text\" name=\"educcontactperson\" value=\"$educcontactperson\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"40\" />
				</th>
			</tr>	
			<tr>
				<th align=\"left\">Level</th>
				<th align=\"left\"><select name=\"educlevel\">
						$educselect3
					</select>
				</th>
			</tr>			
			<tr>
				<th align=\"left\">Honors/Awards</th>	
				<th align=\"left\"><input type=\"text\" name=\"educhonors\" value=\"$educhonors\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"40\" /></th>	
			</tr>
";

			if ($actiontxt != "edit")
echo "
			<tr>
				<td align=\"left\">&nbsp;</td>
				<td align=\"left\"><input type=\"button\" value=\"Add to list...\" $disableapproved onclick=\"actiontxt.value='addlist';checkadd(currentpage.value);\" /></td>
			</tr>
";
			else 
echo "
			<tr>
				<td align=\"left\">&nbsp;</td>
				<td align=\"left\">
					<input type=\"button\" value=\"Save Entry\" onclick=\"actiontxt.value='editsave';editid.value='$editid';checkadd(currentpage.value);\" />
					<input type=\"button\" value=\"Cancel\" onclick=\"actiontxt.value='cancel';submit();\" />
				</td>
			</tr>
";
			
echo "	
		</table>
		
		<br />
		
		<div style=\"width:100%;overflow:auto;height:200px;background-color:#DCDCDC;\">
			<table width=\"100%\" class=\"listcol\">
				<tr>
					<th>SCHOOL</th>
					<th>COURSE</th>
					<th>GRADUATION DATE</th>
					<th>LEVEL</th>
					<th>REMARKS</th>
					<th>&nbsp;</th>
				</tr>
";
				while ($roweduclist = mysql_fetch_array($qryeduclist))
				{
					$education1 = $roweduclist["SCHOOL"];
					$education2 = $roweduclist["COURSE"];
					if ($roweduclist["DATEGRADUATED"] != "")
						$education3 = date('m/d/Y',strtotime($roweduclist["DATEGRADUATED"]));
					else 
						$education3 = "";
					$education4 = $roweduclist["LEVEL"];
					$education5 = $roweduclist["REMARKS"];
					$education6 = $roweduclist["IDNO"];
						
echo "
					<tr>
						<td align=\"left\">$education1</td>
						<td align=\"left\">$education2</td>
						<td align=\"left\">$education3</td>
						<td align=\"left\">$education4</td>
						<td align=\"left\">$education5</td>
						<td align=\"left\">
							<input type=\"button\" value=\"EDIT\" $editmode $disableapproved style=\"color:Red;font-weight:Bold;font-size:0.9em;\" title=\"Edit\"
									onclick=\"actiontxt.value='edit';editid.value='$education6';submit();\" />
							<input type=\"button\" value=\"DEL\" $editmode $disableapproved style=\"color:Red;font-weight:Bold;font-size:0.9em;\" 
									onclick=\"actiontxt.value='delete';deleteid.value='$education6';submit();\" />
						</td>						
					</tr>
";
				}

echo "
			</table>
		</div>	
	</div>
	
	<div id=\"documents\" style=\"width:100%;border:1px solid Navy;display:none;$div3\">
		
		<span class=\"sectiontitle\">DOCUMENTS</span>

	<br /><br />
	<center>
	
	<table style=\"width:50%;font-size:0.8em;font-weight:Bold;\" border=1>
		<tr>
			<td valign=\"top\"><i>Documents Expiry Legend:</i><br /><br />
				&nbsp;&nbsp;<span style=\"font-size:0.9em;background-color:black;color:Yellow;\">DATE EXPIRY</span>
				&nbsp;&nbsp;Three (3) months before document expiration. <br />
				&nbsp;&nbsp;<span style=\"font-size:0.9em;background-color:black;color:Red;\">DATE EXPIRY</span>
				&nbsp;&nbsp;Document already EXPIRED. <br />
			</td>
		</tr>
	</table>
	<br />
	
	<table width=\"60%\" class=\"listrow\">
		<tr>
			<th align=\"left\"><b>Document Type</b></th>
			<td align=\"left\"><select name=\"doccode\">
					$docselect1
				</select>
			</td>
		</tr>
		<tr>
			<th align=\"left\">Document No.</th>
			<td align=\"left\"><input type=\"text\" name=\"docno\" value=\"$docno\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"20\" /></td>
		</tr>
		<tr>
			<th align=\"left\">Rank</th>
			<th align=\"left\"><select name=\"docrankcode\">
					$docselect9
				</select>
			</th>
		</tr>
		<tr>
			<th align=\"left\">Date Issued</th>
			<th align=\"left\">
				<input type=\"text\" name=\"docissued\" value=\"$docissued\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
				<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(docissued, docissued, 'mm/dd/yyyy', 0, 0);return false;\">
				&nbsp;&nbsp;&nbsp;(mm/dd/yyyy)
			</th>
		</tr>
		<tr>
			<th align=\"left\">Date Expired</th>
			<th align=\"left\">
				<input type=\"text\" name=\"docexpired\" value=\"$docexpired\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
				<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(docexpired, docexpired, 'mm/dd/yyyy', 0, 0);return false;\">
				&nbsp;&nbsp;&nbsp;(mm/dd/yyyy)
			</th>
		</tr>
";
	
	if ($actiontxt != "edit")
		echo "
		<tr>
			<td align=\"left\">&nbsp;</td>
			<td align=\"left\"><input type=\"button\" value=\"Add to list...\" $disableapproved 
					onclick=\"if (docexpired.value != '' || '$dochasexpiry'=='0') {actiontxt.value='addlist';checkadd(currentpage.value);}
							else {alert('Date Expired is required. If not, please make changes in Crew Documents Setup.');}\" /></td>
		</tr>
		";
	else 
		echo "
		<tr>
			<td align=\"left\">&nbsp;</td>
			<td align=\"left\">
				<input type=\"button\" value=\"Save Entry\" 
						onclick=\"if (docexpired.value != '' || '$dochasexpiry'=='0') {actiontxt.value='editsave';editid.value='$editid';checkadd(currentpage.value);}
								else {alert('Date Expired is required. If not, please make changes in Crew Documents Setup.');}\" />
				<input type=\"button\" value=\"Cancel\" onclick=\"actiontxt.value='cancel';submit();\" />
			</td>
		</tr>
		";
	
	echo "
	</table>

	</center>
	<br />
";

echo "	
		<div style=\"width:100%;overflow:auto;height:200px;background-color:#DCDCDC;\">
			<table width=\"100%\" class=\"listcol\">
				<tr>
					<th>DOCUMENT</th>
					<th>RANK</th>
					<th>DOCUMENT NO.</th>
					<th>DATE ISSUED</th>
					<th>DATE EXPIRED</th>
					<th>&nbsp;</th>
				</tr>
";
				
				while ($rowdoclist = mysql_fetch_array($qrydoclist))
				{
					$document1 = $rowdoclist["DOCUMENT"];
					$document2 = $rowdoclist["DOCNO"];
					
					if (!empty($rowdoclist["DATEISSUED"]))
						$document3 = date('m/d/Y',strtotime($rowdoclist["DATEISSUED"]));
					else 
						$document3 = "NO DATE";
						
					if (!empty($rowdoclist["DATEEXPIRED"]))
						$document4 = date('m/d/Y',strtotime($rowdoclist["DATEEXPIRED"]));
					else 
						$document4 = "---";
					
					$document5 = $rowdoclist["IDNO"];
					$document6 = $rowdoclist["DOCCODE"];
					$document7 = $rowdoclist["RANKCODE"];
					$docexpiry = $rowdoclist["HASEXPIRY"];
						
					if ($docexpiry == 1)
					{
						if ($document4 != "---")
						{
							$expdate = date('Y-m-d',strtotime($rowdoclist["DATEEXPIRED"]));
							$color = checkexpiry($expdate);
						}
						else 
							$color = "color:Red";  //Expiration Date is REQUIRED (pero NULL)
					}
					else 
						$color = "color:Black";  //Expiration Date is NOT REQUIRED
						
					
					
				echo "
					<tr $mouseovereffect>
						<td align=\"left\">$document1</td>
						<td align=\"left\">$document7</td>
						<td align=\"left\">$document2</td>
						<td align=\"center\">$document3</td>
					";

					echo "<td align=\"center\"><span style=\"font-weight:Bold;$color\">$document4</span></td>";
						
				echo "
						<td align=\"left\">
							<input type=\"button\" value=\"EDIT\" $editmode $disableapproved style=\"color:Red;font-weight:Bold;font-size:0.9em;\" title=\"Edit\"
									onclick=\"actiontxt.value='edit';editid.value='$document5';submit();\" />
							<input type=\"button\" value=\"DEL\" $editmode $disableapproved style=\"color:Red;font-weight:Bold;font-size:0.9em;\" 
									onclick=\"actiontxt.value='delete';deleteid.value='$document5';submit();\" />
						";
							
							if ($document6 != "")
							{
								$dirfilename = $basedirdocs . $applicantno . "/D/" . $document6 . ".pdf";
								
								if (checkpath($dirfilename))
									echo "
									<input type=\"button\" value=\"VIEW\" $editmode $disableapproved onclick=\"openWindow('$dirfilename', '$document6' ,700, 500);\" 
											style=\"color:Green;font-weight:Bold;font-size:0.9em;\"\" />
									";
								else 
									echo "<span style=\"font-size:1em;font-weight:Bold;color:Blue;\">--NOT SCANNED--</span>";
							}
											
						echo "
						</td>						
					</tr>
				";
				}
	echo "			
			</table>
		</div>
	</div>
	
	<div id=\"license\" style=\"width:100%;border:1px solid Navy;display:none;$div4\">
	
		<span class=\"sectiontitle\">LICENSES</span>

		<br /><br />
		
		<table style=\"width:50%;font-size:0.8em;font-weight:Bold;\" border=1>
			<tr>
				<td valign=\"top\"><i>Documents Expiry Legend:</i><br /><br />
					&nbsp;&nbsp;<span style=\"font-size:0.9em;background-color:black;color:Yellow;\">DATE EXPIRY</span>
					&nbsp;&nbsp;Three (3) months before document expiration. <br />
					&nbsp;&nbsp;<span style=\"font-size:0.9em;background-color:black;color:Red;\">DATE EXPIRY</span>
					&nbsp;&nbsp;Document already EXPIRED. <br />
				</td>
			</tr>
		</table>
		<br />
		
		<table width=\"60%\" class=\"listrow\">
			<tr>
				<th align=\"left\">License Type</th>
				<td align=\"left\"><select name=\"liccode\">
						$licselect1
					</select>
				</td>
			</tr>
			<tr>
				<th align=\"left\">Rank</th>
				<th align=\"left\"><select name=\"licrankcode\">
						$licselect9
					</select>
				</th>
			</tr>
			<tr>
				<th align=\"left\">Number</th>
				<td align=\"left\"><input type=\"text\" name=\"licno\" value=\"$licno\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"20\" maxlength=\"20\" /></td>
			</tr>
	
			<tr>
				<th align=\"left\">Date Issued</th>
				<th align=\"left\">
					<input type=\"text\" name=\"licissued\" value=\"$licissued\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(licissued, licissued, 'mm/dd/yyyy', 0, 0);return false;\">
					&nbsp;&nbsp;&nbsp;(mm/dd/yyyy)
				</th>
			</tr>
			<tr>
				<th align=\"left\">Date Expired</th>
				<th align=\"left\">
					<input type=\"text\" name=\"licexpired\" value=\"$licexpired\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(licexpired, licexpired, 'mm/dd/yyyy', 0, 0);return false;\">
					&nbsp;&nbsp;&nbsp;(mm/dd/yyyy)
				</th>
			</tr>
";
	
		if ($actiontxt != "edit")
			echo "
			<tr>
				<td align=\"left\">&nbsp;</td>
				<td align=\"left\"><input type=\"button\" value=\"Add to list...\" $disableapproved 
						onclick=\"if (licexpired.value != '' || '$lichasexpiry'=='0') {actiontxt.value='addlist';checkadd(currentpage.value);}
								else {alert('Date Expired is required. If not, please make changes in Crew Documents Setup.');}
								\" />
				</td>
			</tr>
			";
		else 
			echo "
			<tr>
				<td align=\"left\">&nbsp;</td>
				<td align=\"left\">
					<input type=\"button\" value=\"Save Entry\" 
						onclick=\"if (licexpired.value != '' || '$lichasexpiry'=='0') {actiontxt.value='editsave';editid.value='$editid';checkadd(currentpage.value);}
								else {alert('Date Expired is required. If not, please make changes in Crew Documents Setup.');}
								\" />
					<input type=\"button\" value=\"Cancel\" onclick=\"actiontxt.value='cancel';submit();\" />
				</td>
			</tr>
			";
			
echo "
		</table>
		<br />

		<div style=\"width:100%;overflow:auto;height:200px;background-color:#DCDCDC;\">
			<table width=\"100%\" class=\"listcol\">
				<tr>
					<th>LICENSE</th>
					<th>RANK</th>
					<th>NUMBER</th>
					<th>DATE ISSUED</th>
					<th>DATE EXPIRED</th>
					<th>&nbsp;</th>
				</tr>
";

				while ($rowliclist = mysql_fetch_array($qryliclist))
				{
					$license1 = $rowliclist["DOCUMENT"];
					$license2 = $rowliclist["DOCNO"];
					if ($rowliclist["DATEISSUED"] != "")
						$license3 = date('m/d/Y',strtotime($rowliclist["DATEISSUED"]));
					else 
						$license3 = "NO DATE";
						
					if ($rowliclist["DATEEXPIRED"] != "")
						$license4 = date('m/d/Y',strtotime($rowliclist["DATEEXPIRED"]));
					else 
						$license4 = "---";
						
					$license5 = $rowliclist["IDNO"];
					$license6 = $rowliclist["DOCCODE"];
					$license7 = $rowliclist["RANKCODE"];
					$licexpiry = $rowliclist["HASEXPIRY"];
						
					if ($licexpiry == 1)
					{
						if ($license4 != "---")
						{
							$expdate = date('Y-m-d',strtotime($rowliclist["DATEEXPIRED"]));
							$color = checkexpiry($expdate);
						}
						else 
							$color = "color:Red";  //Expiration Date is REQUIRED (pero NULL)
					}
					else 
						$color = "color:Black";  //Expiration Date is NOT REQUIRED
						
echo "
					<tr $mouseovereffect>
						<td align=\"left\">$license1</td>
						<td align=\"left\">$license7</td>
						<td align=\"left\">$license2</td>
						<td align=\"center\">$license3</td>
						";

					echo "<td align=\"center\"><span style=\"font-weight:Bold;$color\">$license4</span></td>";
						
					echo "
						<td align=\"left\">
							<input type=\"button\" value=\"EDIT\" $editmode $disableapproved style=\"color:Red;font-weight:Bold;font-size:0.9em;\" title=\"Edit\"
									onclick=\"actiontxt.value='edit';editid.value='$license5';submit();\" />
							<input type=\"button\" value=\"DEL\" $editmode $disableapproved style=\"color:Red;font-weight:Bold;font-size:0.9em;\" 
									onclick=\"actiontxt.value='delete';deleteid.value='$license5';submit();\" />
						";
							
							if ($document6 != "")
							{
								$dirfilename = $basedirdocs . $applicantno . "/L/" . $license6 . ".pdf";
								
								if (checkpath($dirfilename))
									echo "
									<input type=\"button\" value=\"VIEW\" $editmode $disableapproved onclick=\"openWindow('$dirfilename', '$license6' ,700, 500);\" 
											style=\"color:Green;font-weight:Bold;font-size:0.9em;\"\" />
									";
								else 
									echo "<span style=\"font-size:1em;font-weight:Bold;color:Blue;\">--NOT SCANNED--</span>";
							}
											
						echo "
									
						</td>						
					</tr>
";
				}


echo "
			</table>
		</div>
	</div>
	
	<div id=\"certificate\" style=\"border:1px solid Navy;display:none;$div5\">

		<span class=\"sectiontitle\">CERTIFICATES</span>

		<br /><br />
<!--
		<table style=\"width:50%;font-size:0.8em;font-weight:Bold;\" border=1>
			<tr>
				<td valign=\"top\"><i>Documents Expiry Legend:</i><br /><br />
					&nbsp;&nbsp;<span style=\"font-size:0.9em;background-color:black;color:Yellow;\">DATE EXPIRY</span>
					&nbsp;&nbsp;Three (3) months before document expiration. <br />
					&nbsp;&nbsp;<span style=\"font-size:0.9em;background-color:black;color:Red;\">DATE EXPIRY</span>
					&nbsp;&nbsp;Document already EXPIRED. <br />
					&nbsp;&nbsp;<span style=\"font-size:0.9em;color:Red;\">&nbsp;&nbsp;&nbsp;NO DATA&nbsp;&nbsp;</span>
					&nbsp;&nbsp;&nbsp;&nbsp;Expiry date not encoded. <br />
				</td>
			</tr>
		</table>
		<br />
-->
		<table width=\"60%\" class=\"listrow\">
			<tr>
				<th align=\"left\"><b>Certificate Type</b></th>
				<th align=\"left\"><select name=\"certcode\">
						$certselect1
					</select>
				</th>
			</tr>
			<tr>
				<th align=\"left\">Rank</th>
				<th align=\"left\"><select name=\"certrankcode\">
						$certselect9
					</select>
				</th>
			</tr>
			<tr>
				<th align=\"left\"><b>Certificate No.</b></th>
				<th align=\"left\"><input type=\"text\" name=\"certno\" value=\"$certno\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"20\" /></th>
			</tr>	
			<tr>
				<th align=\"left\"><b>Date Issued</b></th>
				<th align=\"left\">
					<input type=\"text\" name=\"certissued\" value=\"$certissued\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(certissued, certissued, 'mm/dd/yyyy', 0, 0);return false;\">
					&nbsp;&nbsp;&nbsp;(mm/dd/yyyy)
				</th>
			</tr>
";
		if ($actiontxt != "edit")
			echo "
			<tr>
				<td align=\"left\">&nbsp;</td>
				<td align=\"left\"><input type=\"button\" value=\"Add to list...\" $disableapproved onclick=\"actiontxt.value='addlist';checkadd(currentpage.value);\" /></td>
			</tr>
			";
		else 
			echo "
			<tr>
				<td align=\"left\">&nbsp;</td>
				<td align=\"left\">
					<input type=\"button\" value=\"Save Entry\" onclick=\"actiontxt.value='editsave';editid.value='$editid';checkadd(currentpage.value);\" />
					<input type=\"button\" value=\"Cancel\" onclick=\"actiontxt.value='cancel';submit();\" />
				</td>
			</tr>
			";
	
echo "
		</table>
		<br />
		
		<div style=\"width:100%;overflow:auto;height:200px;background-color:#DCDCDC;\">
			<table width=\"100%\" class=\"listcol\">
				<tr>
					<th>CERTIFICATE</th>
					<th>RANK</th>
					<th>NUMBER</th>
					<th>DATE ISSUED</th>
					<th>&nbsp;</th>
				</tr>
";
			
				while ($rowcertlist = mysql_fetch_array($qrycertlist))
				{
					$certificate1 = $rowcertlist["DOCUMENT"];
					$certificate2 = $rowcertlist["DOCNO"];
					if ($rowcertlist["DATEISSUED"] != "")
						$certificate4 = date('m/d/Y',strtotime($rowcertlist["DATEISSUED"]));
					else 
						$certificate4 = "";
						
					$certificate5 = $rowcertlist["IDNO"];
					$certificate6 = $rowcertlist["DOCCODE"];
					$certificate7 = $rowcertlist["RANKCODE"];
						
echo "
					<tr $mouseovereffect>
						<td align=\"left\">$certificate1</td>
						<td align=\"left\">$certificate7</td>
						<td align=\"left\">$certificate2</td>
						<td align=\"center\">$certificate4</td>
						<td align=\"left\">
							<input type=\"button\" value=\"EDIT\" $editmode $disableapproved style=\"color:Red;font-weight:Bold;font-size:0.9em;\" title=\"Edit\"
									onclick=\"actiontxt.value='edit';editid.value='$certificate5';submit();\" />
							<input type=\"button\" value=\"DEL\" $editmode $disableapproved style=\"color:Red;font-weight:Bold;font-size:0.9em;\" 
									onclick=\"actiontxt.value='delete';deleteid.value='$certificate5';submit();\" />
						";
							
							if ($certificate6 != "")
							{
								$dirfilename = $basedirdocs . $applicantno . "/C/" . $certificate6 . ".pdf";
								
								if (checkpath($dirfilename))
									echo "
									<input type=\"button\" value=\"VIEW\" $editmode $disableapproved onclick=\"openWindow('$dirfilename', '$certificate6' ,700, 500);\" 
											style=\"color:Green;font-weight:Bold;font-size:0.9em;\"\" />
									";
								else 
									echo "<span style=\"font-size:1em;font-weight:Bold;color:Blue;\">--NOT SCANNED--</span>";
							}
											
						echo "
						</td>
					</tr>
";
				}

echo "
			</table>
		</div>	
	</div>
	
	<div id=\"experience\" style=\"border:1px solid Navy;display:none;$div6\">
	
		<span class=\"sectiontitle\">EXPERIENCE (OUTSIDE)</span>

		<br /><br />
		<table class=\"listrow\" width=\"80%\">
			<tr>
				<td align=\"left\"><b>Vessel Name *</b></td>
				<td align=\"left\"><input type=\"text\" name=\"expvessel\" value=\"$expvessel\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"35\" /></td>
			</tr>
			<tr>
				<td align=\"left\"><b>Flag *</b></td>
				<td align=\"left\"><select name=\"expflagcode\">
						$expselect6
					</select>
				</td>
			</tr>
			<tr>
				<th align=\"left\">Flag (Others)</th>
				<th align=\"left\"><input type=\"text\" name=\"expflagothers\" value=\"$expflagothers\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"35\" /></th>
			</tr>
			<tr>				
				<td align=\"left\"><b>Vessel Type *</b></td>
				<td align=\"left\"><select name=\"expvesseltypecode\">
						$expselect1
					</select>
				</td>
			</tr>
			<tr>
				<th align=\"left\">Vessel Type (Others)</th>
				<th align=\"left\"><input type=\"text\" name=\"expvsltypeothers\" value=\"$expvsltypeothers\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"35\" /></th>
			</tr>
			<tr>
				<td align=\"left\"><b>Rank *</b></td>
				<td align=\"left\"><select name=\"exprankcode\">
						$expselect2
					</select>
				</td>
			</tr>
			<tr>
				<td align=\"left\"><b>Trade Route *</b></td>
				<td align=\"left\"><select name=\"exptraderoutecode\">
						$expselect3
					</select>
				</td>	
			</tr>
			<tr>
				<th align=\"left\">Trade Route (Others)</th>
				<th align=\"left\"><input type=\"text\" name=\"exptradeothers\" value=\"$exptradeothers\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"35\" /></th>
			</tr>
			<tr>
				<th align=\"left\">Engine Type</th>
				<th align=\"left\"><input type=\"text\" name=\"expenginetype\" value=\"$expenginetype\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"35\" /></th>
			</tr>
			<tr>				
				<th align=\"left\">Gross Tonnage</th>
				<th align=\"left\"><input type=\"text\" name=\"expgrosston\" value=\"$expgrosston\" size=\"15\" onKeyPress=\"return numbersonly(this);\" /></th>
			</tr>
<!--
			<tr>				
				<th align=\"left\">Dead Weight</th>
				<th align=\"left\"><input type=\"text\" name=\"expdeadwt\" value=\"$expdeadwt\" size=\"15\" onKeyPress=\"return numbersonly(this);\" /></th>
			</tr>	
-->	
			<tr>				
				<th align=\"left\">Kind of Cargo</th>
				<th align=\"left\"><input type=\"text\" name=\"expcargo\" value=\"$expcargo\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"20\" /></th>
			</tr>		
			<tr>
				<td align=\"left\"><b>Manning Agency *</b></td>
				<td align=\"left\"><select name=\"expmanningcode\">
						$expselect4
					</select>
				</td>
			</tr>
			<tr>				
				<th align=\"left\">Manning Agency (Others)</th>
				<th align=\"left\"><input type=\"text\" name=\"expmanningothers\" value=\"$expmanningothers\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"30\" /></th>
			</tr>			
			<tr>
				<td align=\"left\"><b>Embark Date *</b></td>
				<th align=\"left\"><input type=\"text\" name=\"expembdate\" value=\"$expembdate\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(expembdate, expembdate, 'mm/dd/yyyy', 0, 0);return false;\">
					&nbsp;&nbsp;&nbsp;(mm/dd/yyyy)
				</th>
			</tr>
			<tr>
				<td align=\"left\"><b>Disembark Date *</b></td>
				<th align=\"left\"><input type=\"text\" name=\"expdisembdate\" value=\"$expdisembdate\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(expdisembdate, expdisembdate, 'mm/dd/yyyy', 0, 0);return false;\">
					&nbsp;&nbsp;&nbsp;(mm/dd/yyyy)
				</th>						
			</tr>
			<tr>
				<th align=\"left\">Disembark Reason</th>
				<th align=\"left\"><select name=\"expdisembreasoncode\">
						$expselect5
					</select>
				</th>	
			</tr>
			<tr>
				<th align=\"left\" valign=\"top\">Other Reason</th>
				<th align=\"left\"><textarea name=\"expreasonothers\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" rows=\"3\" cols=\"20\">$expreasonothers</textarea></th>
			</tr>
";
			if ($actiontxt != "edit")
echo "
			<tr>
				<td align=\"left\">&nbsp;</td>
				<td align=\"left\"><input type=\"button\" value=\"Add to list...\" $disableapproved onclick=\"actiontxt.value='addlist';checkadd(currentpage.value);\" /></td>
			</tr>
";
			else 
echo "
			<tr>
				<td align=\"left\">&nbsp;</td>
				<td align=\"left\">
					<input type=\"button\" value=\"Save Entry\" onclick=\"actiontxt.value='editsave';editid.value='$editid';checkadd(currentpage.value);\" />
					<input type=\"button\" value=\"Cancel\" onclick=\"actiontxt.value='cancel';submit();\" />
				</td>
			</tr>
";
			
echo "		
		</table>	
		<br />
		
		<div style=\"width:100%;overflow:auto;height:120px;background-color:#DCDCDC;\">
			<table width=\"100%\" class=\"listcol\">
				<tr>
					<th>VESSEL</th>
					<th>TYPE</th>
					<th>RANK</th>
					<th>ENGINE TYPE</th>
					<th>MANNING AGENCY</th>
					<th>REASON</th>
					<th>&nbsp;</th>
				</tr>
";
				while ($rowexperiencelist = mysql_fetch_array($qryexperiencelist))
				{
					$experience1 = $rowexperiencelist["VESSEL"];
					$experience2 = $rowexperiencelist["VESSELTYPE"];
					$experience3 = $rowexperiencelist["RANKALIAS"];
					$experience4 = $rowexperiencelist["ENGINETYPE"];
					$experience5 = $rowexperiencelist["MANNING"];
					$experience6 = $rowexperiencelist["REASON"];
					$experience7 = $rowexperiencelist["IDNO"];
						
echo "
					<tr $mouseovereffect>
						<td align=\"left\">$experience1</td>
						<td align=\"left\">$experience2</td>
						<td align=\"left\">$experience3</td>
						<td align=\"left\">$experience4</td>
						<td align=\"left\">$experience5</td>
						<td align=\"left\">$experience6</td>
						<td align=\"left\">
							<input type=\"button\" value=\"EDIT\" $editmode $disableapproved style=\"color:Red;font-weight:Bold;font-size:0.9em;\" title=\"Edit\"
									onclick=\"actiontxt.value='edit';editid.value='$experience7';submit();\" />
							<input type=\"button\" value=\"DEL\" $editmode $disableapproved style=\"color:Red;font-weight:Bold;font-size:0.9em;\" 
									onclick=\"actiontxt.value='delete';deleteid.value='$experience7';submit();\" />
						</td>
					</tr>
";
				}

echo "
			</table>
		</div>		
	</div>
	
	<div id=\"employment\" style=\"border:1px solid Navy;display:none;$div7\">
		<span class=\"sectiontitle\">EMPLOYMENT HISTORY (LAND BASE)</span>
";
		if ($remarks7 != "")
echo "		<center><span class=\"error\">ERROR:<br />$remarks7</span></center>	";
			
echo "
		<br /><br />
		<table width=\"60%\" class=\"listrow\">
			<tr>
				<td align=\"left\"><b>Employer *</b></td>
				<td align=\"left\"><input type=\"text\" name=\"empemployer\" value=\"$empemployer\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\"  size=\"20\" /></td>
			</tr>
			<tr>
				<td align=\"left\"><b>Position *</b></td>
				<td align=\"left\"><input type=\"text\" name=\"empposition\" value=\"$empposition\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\"  size=\"20\" /></td>
			</tr>
			<tr>
				<th align=\"left\">Tel. No.</th>
				<th align=\"left\"><input type=\"text\" name=\"emptelno\" value=\"$emptelno\"  size=\"15\" /></th>
			</tr>					
			<tr>
				<td align=\"left\"><b>From *</b></td>
				<th align=\"left\">
					<input type=\"text\" name=\"empdatefrom\" value=\"$empdatefrom\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(empdatefrom, empdatefrom, 'mm/dd/yyyy', 0, 0);return false;\">
					&nbsp;&nbsp;&nbsp;(mm/dd/yyyy)
				</th>
			</tr>
			<tr>
				<td align=\"left\"><b>To *</b></td>
				<th align=\"left\">
					<input type=\"text\" name=\"empdateto\" value=\"$empdateto\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(empdateto, empdateto, 'mm/dd/yyyy', 0, 0);return false;\">
					&nbsp;&nbsp;&nbsp;(mm/dd/yyyy)
				</th>
			</tr>		
<!--
			<tr>
				<th align=\"left\">Basic Salary</th>
				<th align=\"left\"><input type=\"text\" name=\"empsalary\" value=\"$empsalary\"  size=\"10\" onKeyPress=\"return numbersonly(this);\" /></th>
			</tr>
			<tr>
				<th align=\"left\">Overtime Pay</th>
				<th align=\"left\"><input type=\"text\" name=\"empovertime\" value=\"$empovertime\"  size=\"10\" onKeyPress=\"return numbersonly(this);\" /></th>
			</tr>	
-->		
			<tr>
				<th align=\"left\" valign=\"top\">Reason for Separation</th>
				<th align=\"left\"><textarea name=\"empreason\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\"  rows=\"3\" cols=\"20\">$empreason</textarea></th>
			</tr>
";
			if ($actiontxt != "edit")
echo "
			<tr>
				<td align=\"left\">&nbsp;</td>
				<td align=\"left\"><input type=\"button\" value=\"Add to list...\" $disableapproved onclick=\"actiontxt.value='addlist';checkadd(currentpage.value);\" /></td>
			</tr>
";
			else 
echo "
			<tr>
				<td align=\"left\">&nbsp;</td>
				<td align=\"left\">
					<input type=\"button\" value=\"Save Entry\" onclick=\"actiontxt.value='editsave';editid.value='$editid';checkadd(currentpage.value);\" />
					<input type=\"button\" value=\"Cancel\" onclick=\"actiontxt.value='cancel';submit();\" />
				</td>
			</tr>
";
			
echo "			
		</table>
		
		<br />
		<div style=\"width:100%;overflow:auto;height:100px;background-color:#DCDCDC;\">
			<table width=\"100%\" class=\"listcol\">
				<tr>
					<th>EMPLOYER</th>
					<th>POSITION</th>
					<th>FROM</th>
					<th>TO</th>
					<th>TELNO</th>
					<th>REASON</th>
					<th>&nbsp;</th>
				</tr>
";
				while ($rowemploymentlist = mysql_fetch_array($qryemploymentlist))
				{
					$employment1 = $rowemploymentlist["EMPLOYER"];
					$employment2 = $rowemploymentlist["POSITION"];
					if ($rowemploymentlist["DATEFROM"] != "")
						$employment3 = date('m/d/Y',strtotime($rowemploymentlist["DATEFROM"]));
					else 
						$employment3 = "";
					
					if ($rowemploymentlist["DATETO"] != "")
						$employment4 = date('m/d/Y',strtotime($rowemploymentlist["DATETO"]));
					else 
						$employment4 = "";
						
					$employment5 = $rowemploymentlist["TELNO"];
					$employment6 = $rowemploymentlist["REASON"];
					$employment7 = $rowemploymentlist["IDNO"];
						
echo "
					<tr $mouseovereffect>
						<td align=\"left\">$employment1</td>
						<td align=\"left\">$employment2</td>
						<td align=\"left\">$employment3</td>
						<td align=\"left\">$employment4</td>
						<td align=\"left\">$employment5</td>
						<td align=\"left\">$employment6</td>
						<td align=\"left\">
							<input type=\"button\" value=\"EDIT\" $editmode $disableapproved style=\"color:Red;font-weight:Bold;font-size:0.9em;\" title=\"Edit\"
									onclick=\"actiontxt.value='edit';editid.value='$employment7';submit();\" />
							<input type=\"button\" value=\"DEL\" $editmode $disableapproved style=\"color:Red;font-weight:Bold;font-size:0.9em;\" 
									onclick=\"actiontxt.value='delete';deleteid.value='$employment7';submit();\" />
						</td>
					</tr>
";
				}

echo "
			</table>
		</div>	
	</div>

";

echo "
	<div id=\"medical\" style=\"border:1px solid Navy;display:none;$div8\">
		<span class=\"sectiontitle\">MEDICAL HISTORY</span>

		<br /><br />
		<table width=\"60%\" class=\"listrow\">
			<tr>
				<td align=\"left\"><b>Clinic</b></td>
				<td align=\"left\">
					<select name=\"medclinicid\">
						$medselect1
					</select>
				</td>
			</tr>
			<tr>
				<td align=\"left\"><b>Date Checkup</b></td>
				<td align=\"left\">
					<input type=\"text\" name=\"medcheckupdate\" value=\"$medcheckupdate\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(medcheckupdate, medcheckupdate, 'mm/dd/yyyy', 0, 0);return false;\">
					&nbsp;&nbsp;&nbsp;(mm/dd/yyyy)
				</td>
			</tr>
			<tr>
				<th align=\"left\">Diagnosis</th>
				<th align=\"left\"><input type=\"text\" name=\"meddiagnosis\" value=\"$meddiagnosis\"  size=\"30\" /></th>
			</tr>				
			<tr>
				<th align=\"left\">HBA Test</th>
				<th align=\"left\"><input type=\"checkbox\" name=\"medhbatest\" value=\"$medhbatest\" $chkhbatest/></th>
			</tr>					
			<tr>
				<td align=\"left\"><b>Recommendation</b></td>
				<th align=\"left\">
					<select name=\"medrecommendcode\">
						$medselect2
					</select>
				</th>
			</tr>
			<tr>
				<th align=\"left\">Remarks</th>
				<th align=\"left\">
					<input type=\"text\" name=\"medremarks\" value=\"$medremarks\" onkeyup=\"\"  size=\"30\" />
				</th>
			</tr>		
";
			if ($actiontxt != "edit")
echo "
			<tr>
				<td align=\"left\">&nbsp;</td>
				<td align=\"left\"><input type=\"button\" value=\"Add to list...\" $disableapproved onclick=\"actiontxt.value='addlist';checkadd(currentpage.value);\" /></td>
			</tr>
";
			else 
echo "
			<tr>
				<td align=\"left\">&nbsp;</td>
				<td align=\"left\">
					<input type=\"button\" value=\"Save Entry\" onclick=\"actiontxt.value='editsave';editid.value='$editid';checkadd(currentpage.value);\" />
					<input type=\"button\" value=\"Cancel\" onclick=\"actiontxt.value='cancel';submit();\" />
				</td>
			</tr>
";
			
echo "			
		</table>
		
		<br />
		<div style=\"width:100%;overflow:auto;height:100px;background-color:#DCDCDC;\">
			<table width=\"100%\" class=\"listcol\">
				<tr>
					<th>DATE</th>
					<th>CLINIC</th>
					<th>DIAGNOSIS</th>
					<th>RECOMMEND</th>
					<th>REMARKS</th>
					<th>&nbsp;</th>
				</tr>
";
				while ($rowmedicallist = mysql_fetch_array($qrymedicallist))
				{
					//cm.CLINICID,c.CLINIC,cm.DATECHECKUP,cm.DIAGNOSIS,cr.RECOMMENDATION,cm.REMARKS,cm.HBATEST
					
					$medical1 = $rowmedicallist["CLINIC"];
					if ($rowmedicallist["DATECHECKUP"] != "")
						$medical2 = date('m/d/Y',strtotime($rowmedicallist["DATECHECKUP"]));
					else 
						$medical2 = "";
					
					$medical3 = $rowmedicallist["IDNO"];
					$medical4 = $rowmedicallist["DIAGNOSIS"];
					$medical5 = $rowmedicallist["RECOMMENDATION"];
					$medical6 = $rowmedicallist["REMARKS"];
						
echo "
					<tr $mouseovereffect>
						<td align=\"left\">&nbsp;$medical2</td>
						<td align=\"left\">&nbsp;$medical1</td>
						<td align=\"left\">&nbsp;$medical4</td>
						<td align=\"left\">&nbsp;$medical5</td>
						<td align=\"left\">&nbsp;$medical6</td>
						<td align=\"left\">
							<input type=\"button\" value=\"EDIT\" $editmode $disableapproved style=\"color:Red;font-weight:Bold;font-size:0.9em;\" title=\"Edit\"
									onclick=\"actiontxt.value='edit';editid.value='$medical3';submit();\" />
							<input type=\"button\" value=\"DEL\" $editmode $disableapproved style=\"color:Red;font-weight:Bold;font-size:0.9em;\" 
									onclick=\"actiontxt.value='delete';deleteid.value='$medical3';submit();\" />
						</td>
					</tr>
";
				}

echo "
			</table>
		</div>	
	</div>


	<br />
";


echo "
	<hr>
	
	<table width=\"100%\">
		<th align=\"right\">
			<input type=\"button\" name=\"btnback\" value=\"<< Back\" $disablebtnback style=\"border:2px solid gray;background-color:Red;color:Yellow;font-weight:Bold;\" onclick=\"changepage(0,currentpage.value,10)\" />
			<input type=\"button\" name=\"btnnext\" value=\"Next >>\" $disablebtnnext style=\"border:2px solid gray;background-color:Red;color:Yellow;font-weight:Bold;\" onclick=\"changepage(1,currentpage.value,10)\" />
		</th>
	</table>
	
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"deleteid\" />
	<input type=\"hidden\" name=\"editid\" />
	<input type=\"hidden\" name=\"currentpage\" value=\"$currentpage\" />
	<input type=\"hidden\" name=\"applicantno\" value=\"$applicantno\" />
	</form>

  </div>
</body>
</html>
";