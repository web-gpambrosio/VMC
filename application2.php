<?php

//include('connectdb.php');
include('veritas/connectdb.php');

$currentdate = date('Y-m-d H:i:s');


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
	

if (isset($_POST["searchby"]))
	$searchby = $_POST["searchby"];
else 
	$searchby = "1";
	
if (isset($_POST["searchkey"]))
	$searchkey = $_POST["searchkey"];
	
	
	
//PERSONAL

if (isset($_POST['choice1']))
	$choice1 = $_POST['choice1'];
	
if (isset($_POST['choice2']))
	$choice2 = $_POST['choice2'];

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
	
if (isset($_POST['educdategrad']))
	$educdategrad = $_POST['educdategrad'];
	
if (isset($_POST['educlevel']))
	$educlevel = $_POST['educlevel'];
	
if (isset($_POST['educhonors']))
	$educhonors = $_POST['educhonors'];
	
//DOCUMENTS

if (isset($_POST['passportno']))
	$passportno = $_POST['passportno'];
	
if (isset($_POST['passportissued']))
	$passportissued = $_POST['passportissued'];
	
if (isset($_POST['passportexpired']))
	$passportexpired = $_POST['passportexpired'];

if (isset($_POST['sbookno']))
	$sbookno = $_POST['sbookno'];
	
if (isset($_POST['sbookissued']))
	$sbookissued = $_POST['sbookissued'];
	
if (isset($_POST['sbookexpired']))
	$sbookexpired = $_POST['sbookexpired'];

if (isset($_POST['srcno']))
	$srcno = $_POST['srcno'];
	
if (isset($_POST['srcissued']))
	$srcissued = $_POST['srcissued'];
	
if (isset($_POST['srcexpired']))
	$srcexpired = $_POST['srcexpired'];

if (isset($_POST['usvisano']))
	$usvisano = $_POST['usvisano'];
	
if (isset($_POST['usvisaissued']))
	$usvisaissued = $_POST['usvisaissued'];

if (isset($_POST['usvisaexpired']))
	$usvisaexpired = $_POST['usvisaexpired'];

	
if (isset($_POST['docno']))
	$docno = $_POST['docno'];
	
if (isset($_POST['doccode']))
	$doccode = $_POST['doccode'];
	
if (isset($_POST['docissued']))
	$docissued = $_POST['docissued'];
	
if (isset($_POST['docexpired']))
	$docexpired = $_POST['docexpired'];
	
//LICENSE

if (isset($_POST['liccode']))
	$liccode = $_POST['liccode'];

if (isset($_POST['licno']))
	$licno = $_POST['licno'];

if (isset($_POST['licissued']))
	$licissued = $_POST['licissued'];

if (isset($_POST['licexpired']))
	$licexpired = $_POST['licexpired'];

if (isset($_POST['licno1']))
	$licno1 = $_POST['licno1'];

if (isset($_POST['licissued1']))
	$licissued1 = $_POST['licissued1'];

if (isset($_POST['licexpired1']))
	$licexpired1 = $_POST['licexpired1'];

if (isset($_POST['licno2']))
	$licno2 = $_POST['licno2'];

if (isset($_POST['licissued2']))
	$licissued2 = $_POST['licissued2'];

if (isset($_POST['licexpired2']))
	$licexpired2 = $_POST['licexpired2'];

if (isset($_POST['licno3']))
	$licno3 = $_POST['licno3'];

if (isset($_POST['licissued3']))
	$licissued3 = $_POST['licissued3'];

if (isset($_POST['licexpired3']))
	$licexpired3 = $_POST['licexpired3'];

if (isset($_POST['licno4']))
	$licno4 = $_POST['licno4'];

if (isset($_POST['licissued4']))
	$licissued4 = $_POST['licissued4'];

if (isset($_POST['licexpired4']))
	$licexpired4 = $_POST['licexpired4'];

if (isset($_POST['licno5']))
	$licno5 = $_POST['licno5'];

if (isset($_POST['licissued5']))
	$licissued5 = $_POST['licissued5'];

if (isset($_POST['licexpired5']))
	$licexpired5 = $_POST['licexpired5'];

if (isset($_POST['licno6']))
	$licno6 = $_POST['licno6'];

if (isset($_POST['licissued6']))
	$licissued6 = $_POST['licissued6'];

if (isset($_POST['licexpired6']))
	$licexpired6 = $_POST['licexpired6'];

if (isset($_POST['licno7']))
	$licno7 = $_POST['licno7'];

if (isset($_POST['licissued7']))
	$licissued7 = $_POST['licissued7'];

if (isset($_POST['licexpired7']))
	$licexpired7 = $_POST['licexpired7'];

if (isset($_POST['licno8']))
	$licno8 = $_POST['licno8'];

if (isset($_POST['licissued8']))
	$licissued8 = $_POST['licissued8'];

if (isset($_POST['licexpired8']))
	$licexpired8 = $_POST['licexpired8'];

if (isset($_POST['licno9']))
	$licno9 = $_POST['licno9'];

if (isset($_POST['licissued9']))
	$licissued9 = $_POST['licissued9'];

if (isset($_POST['licexpired9']))
	$licexpired9 = $_POST['licexpired9'];

if (isset($_POST['licno10']))
	$licno10 = $_POST['licno10'];

if (isset($_POST['licissued10']))
	$licissued10 = $_POST['licissued10'];

if (isset($_POST['licexpired10']))
	$licexpired10 = $_POST['licexpired10'];

if (isset($_POST['licno11']))
	$licno11 = $_POST['licno11'];

if (isset($_POST['licissued11']))
	$licissued11 = $_POST['licissued11'];

if (isset($_POST['licexpired11']))
	$licexpired11 = $_POST['licexpired11'];

if (isset($_POST['licno12']))
	$licno12 = $_POST['licno12'];

if (isset($_POST['licissued12']))
	$licissued12 = $_POST['licissued12'];

if (isset($_POST['licexpired12']))
	$licexpired12 = $_POST['licexpired12'];

if (isset($_POST['licno13']))
	$licno13 = $_POST['licno13'];

if (isset($_POST['licissued13']))
	$licissued13 = $_POST['licissued13'];

if (isset($_POST['licexpired13']))
	$licexpired13 = $_POST['licexpired13'];

if (isset($_POST['licno14']))
	$licno14 = $_POST['licno14'];

if (isset($_POST['licissued14']))
	$licissued14 = $_POST['licissued14'];

if (isset($_POST['licexpired14']))
	$licexpired14 = $_POST['licexpired14'];


//CERTIFICATE
//if (isset($_POST['cocno']))
//	$cocno = $_POST['cocno'];
//
//if (isset($_POST['cocgrade']))
//	$cocgrade = $_POST['cocgrade'];
//
//if (isset($_POST['cocissued']))
//	$cocissued = $_POST['cocissued'];
//
//if (isset($_POST['cocexpired']))
//	$cocexpired = $_POST['cocexpired'];
//
//if (isset($_POST['stcwno']))
//	$stcwno = $_POST['stcwno'];
//
//if (isset($_POST['stcwgrade']))
//	$stcwgrade = $_POST['stcwgrade'];
//
//if (isset($_POST['stcwissued']))
//	$stcwissued = $_POST['stcwissued'];
//
//if (isset($_POST['stcwexpired']))
//	$stcwexpired = $_POST['stcwexpired'];
//
//if (isset($_POST['gocno']))
//	$gocno = $_POST['gocno'];
//
//if (isset($_POST['gocgrade']))
//	$gocgrade = $_POST['gocgrade'];
//
//if (isset($_POST['gocissued']))
//	$gocissued = $_POST['gocissued'];
//
//if (isset($_POST['gocexpired']))
//	$gocexpired = $_POST['gocexpired'];


if (isset($_POST['certno']))
	$certno = $_POST['certno'];
	
//if (isset($_POST['certgrade']))
//	$certgrade = $_POST['certgrade'];
	
if (isset($_POST['certcode']))
	$certcode = $_POST['certcode'];

if (isset($_POST['certissued']))
	$certissued = $_POST['certissued'];

if (isset($_POST['certno1']))
	$certno1 = $_POST['certno1'];

if (isset($_POST['certissued1']))
	$certissued1 = $_POST['certissued1'];
	
if (isset($_POST['certno2']))
	$certno2 = $_POST['certno2'];

if (isset($_POST['certissued2']))
	$certissued2 = $_POST['certissued2'];
	
if (isset($_POST['certno3']))
	$certno3 = $_POST['certno3'];

if (isset($_POST['certissued3']))
	$certissued3 = $_POST['certissued3'];
	
if (isset($_POST['certno4']))
	$certno4 = $_POST['certno4'];

if (isset($_POST['certissued4']))
	$certissued4 = $_POST['certissued4'];
	
if (isset($_POST['certno5']))
	$certno5 = $_POST['certno5'];

if (isset($_POST['certissued5']))
	$certissued5 = $_POST['certissued5'];
	
	
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

	
//QUESTIONS

if (isset($_POST['qanswer1a']))
	$qanswer1a = $_POST['qanswer1a'];

if (isset($_POST['qanswer1b']))
	$qanswer1b = $_POST['qanswer1b'];

if (isset($_POST['qanswer2a']))
	$qanswer2a = $_POST['qanswer2a'];

if (isset($_POST['qanswer2b']))
	$qanswer2b = $_POST['qanswer2b'];

if (isset($_POST['qanswer3a']))
	$qanswer3a = $_POST['qanswer3a'];

if (isset($_POST['qanswer3b']))
	$qanswer3b = $_POST['qanswer3b'];

if (isset($_POST['qanswer4a']))
	$qanswer4a = $_POST['qanswer4a'];

if (isset($_POST['qanswer4b']))
	$qanswer4b = $_POST['qanswer4b'];

if (isset($_POST['qanswer5a']))
	$qanswer5a = $_POST['qanswer5a'];

if (isset($_POST['qanswer5b']))
	$qanswer5b = $_POST['qanswer5b'];

if (isset($_POST['qdesiredsalary']))
	$qdesiredsalary = $_POST['qdesiredsalary'];

//if (isset($_POST['qdesiredot']))
//	$qdesiredot = $_POST['qdesiredot'];


//REFERENCE

if (isset($_POST['refname']))
	$refname = $_POST['refname'];

if (isset($_POST['reftelno']))
	$reftelno = $_POST['reftelno'];

if (isset($_POST['refoccupation']))
	$refoccupation = $_POST['refoccupation'];

if (isset($_POST['refremarks']))
	$refremarks = $_POST['refremarks'];

if (isset($_POST['recommendedby']))
	$recommendedby = $_POST['recommendedby'];

	
//POSTS FOR DELETION/EDIT

if (isset($_POST['deleteid']))
	$deleteid = $_POST['deleteid'];

if (isset($_POST['editid']))
	$editid = $_POST['editid'];

//END OF POSTS FOR DELETION/EDIT


//********************************************************  SAVING SECTION  ***************************************************//
	
switch ($actiontxt)
{
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
								
								CEL1 = '$crewcelphone1',
								CEL2 = '$crewcelphone2',
								CEL3 = '$crewcelphone3'
								
								WHERE APPLICANTNO=$applicantno					
						") or die(mysql_error());
			
			
			if ($qanswer1a == "")
				$qanswer1a = "NULL";
			
			if ($qanswer2a == "")
				$qanswer2a = "NULL";
			
			if ($qanswer3a == "")
				$qanswer3a = "NULL";
			
			if ($qanswer4a == "")
				$qanswer4a = "NULL";
			
			if ($qanswer5a == "")
				$qanswer5a = "NULL";
				
			if ($qdesiredsalary == "")
				$qdesiredsalary = 0;
				
//			if ($qdesiredot == "")
//				$qdesiredot = 0;
			
				$qryapplicantsaveall = mysql_query("UPDATE applicant SET
									CHOICE1 = '$choice1',
									CHOICE2 = '$choice2',
									RECOMMENDEDBY = '$recommendedby',
									DESIREDSALARY = $qdesiredsalary,
									QUESTION1A = $qanswer1a,
									QUESTION1B = '$qanswer1b',
									QUESTION2A = $qanswer2a,
									QUESTION2B = '$qanswer2b',
									QUESTION3A = $qanswer3a,
									QUESTION3B = '$qanswer3b',
									QUESTION4A = $qanswer4a,
									QUESTION4B = '$qanswer4b',
									QUESTION5A = $qanswer5a,
									QUESTION5B = '$qanswer5b'
									
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
						$educdategrad = "";
						$educlevel = "";
						$educhonors = "";
						
					break;
				case "3": //DOCUMENTS
						
						$doccode = "";
						$docno = "";
						$docissued = "";
						$docexpired = "";
				
					break;
				case "4": //LICENSE
						
						$liccode = "";
						$licno = "";
						$licissued = "";
						$licexpired = "";
				
					break;
				case "5": //CERTIFICATE
						
						$certcode = "";
						$certno = "";
						$certgrade = "";
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
				case "8": //QUESTIONS
				
					break;
				case "9": //REFERENCE
						
						$refname = "";
						$reftelno = "";
						$refoccupation = "";
						$refremarks = "";
				
					break;
				case "10": //AGREEMENT
					
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
															LEVEL='$educlevel',
															HONORS='$educhonors'
														WHERE APPLICANTNO=$applicantno AND IDNO=$editid
											") or die(mysql_error());
				
						$educschoolid = "";
						$educschoolothers = "";
						$educcourseid = "";
						$educcourseothers = "";
						$educdategrad = "";
						$educlevel = "";
						$educhonors = "";
						
					break;
				case "3": //DOCUMENTS
				
						$docissuedraw = date('Y-m-d',strtotime($docissued));
						
						$docexpiredraw = date('Y-m-d',strtotime($docexpired));
			
						$qrydoceditsave = mysql_query("UPDATE crewdocstatus SET
																DOCCODE='$doccode',
																DATEISSUED='$docissuedraw',
																DATEEXPIRED='$docexpiredraw',
																DOCNO='$docno'
														WHERE APPLICANTNO=$applicantno AND IDNO=$editid
											") or die(mysql_error());
						
						$doccode = "";
						$docno = "";
						$docissued = "";
						$docexpired = "";
				
					break;
				case "4": //LICENSE
					
						$licissuedraw = date('Y-m-d',strtotime($licissued));
						
						$licexpiredraw = date('Y-m-d',strtotime($licexpired));
			
						$qryliceditsave = mysql_query("UPDATE crewdocstatus SET
																DOCCODE='$liccode',
																DATEISSUED='$licissuedraw',
																DATEEXPIRED='$licexpiredraw',
																DOCNO='$licno'
														WHERE APPLICANTNO=$applicantno AND IDNO=$editid
											") or die(mysql_error());
						
						$liccode = "";
						$licno = "";
						$licissued = "";
						$licexpired = "";
				
					break;
				case "5": //CERTIFICATE
				
						$certissuedraw = date('Y-m-d',strtotime($certissued));
						
						if ($certgrade == "")
							$certgrade = 0;
			
						$qrycerteditsave = mysql_query("UPDATE crewcertstatus SET
																DOCCODE='$certcode',
																DOCNO='$certno',
																DATEISSUED='$certissuedraw',
																GRADE=$certgrade
														WHERE APPLICANTNO=$applicantno AND IDNO=$editid
											") or die(mysql_error());
						
						$certcode = "";
						$certno = "";
						$certgrade = "";
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
															BASICSALARY=$empsalary,
															OVERTIME=$empovertime,
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
				case "8": //QUESTIONS
				
					break;
				case "9": //REFERENCE
				
						$qryrefeditsave = mysql_query("UPDATE applicantreference SET
															NAME='$refname',
															TELNO='$reftelno',
															OCCUPATION='$refoccupation',
															REMARKS='$refremarks'
															WHERE APPLICANTNO=$applicantno AND IDNO=$editid
											") or die(mysql_error());
						
						$refname = "";
						$reftelno = "";
						$refoccupation = "";
						$refremarks = "";
				
					break;
				case "10": //AGREEMENT
					
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
				
						$qryeducdata = mysql_query("SELECT SCHOOLID,SCHOOLOTHERS,
											COURSEID,COURSEOTHERS,date_format(DATEGRADUATED,'%m/%d/%Y') AS DATEGRADUATED,LEVEL,HONORS 
											FROM creweducation 
											WHERE APPLICANTNO=$applicantno AND IDNO=$editid") or die(mysql_error());
						
						$roweducdata = mysql_fetch_array($qryeducdata);
						
						$educschoolid = $roweducdata["SCHOOLID"];
						$educschoolothers = $roweducdata["SCHOOLOTHERS"];
						$educcourseid = $roweducdata["COURSEID"];
						$educcourseothers = $roweducdata["COURSEOTHERS"];
						$educdategrad = $roweducdata["DATEGRADUATED"];
						$educlevel = $roweducdata["LEVEL"];
						$educhonors = $roweducdata["HONORS"];
					
					break;
				case "3": //DOCUMENTS
				
						$qrydocdata = mysql_query("SELECT DOCCODE,DOCNO,date_format(DATEISSUED,'%m/%d/%Y') AS DATEISSUED,
											date_format(DATEEXPIRED,'%m/%d/%Y') AS DATEEXPIRED
											FROM crewdocstatus
											WHERE APPLICANTNO=$applicantno AND IDNO=$editid") or die(mysql_error());
						
						$rowdocdata = mysql_fetch_array($qrydocdata);
						
						$doccode = $rowdocdata["DOCCODE"];
						$docno = $rowdocdata["DOCNO"];
						$docissued = $rowdocdata["DATEISSUED"];
						$docexpired = $rowdocdata["DATEEXPIRED"];
				
					break;
				case "4": //LICENSE
				
						$qrylicdata = mysql_query("SELECT DOCCODE,DOCNO,date_format(DATEISSUED,'%m/%d/%Y') AS DATEISSUED,
											date_format(DATEEXPIRED,'%m/%d/%Y') AS DATEEXPIRED
											FROM crewdocstatus
											WHERE APPLICANTNO=$applicantno AND IDNO=$editid") or die(mysql_error());
						
						$rowlicdata = mysql_fetch_array($qrylicdata);
						
						$liccode = $rowlicdata["DOCCODE"];
						$licno = $rowlicdata["DOCNO"];
						$licissued = $rowlicdata["DATEISSUED"];
						$licexpired = $rowlicdata["DATEEXPIRED"];
					
					break;
				case "5": //CERTIFICATE
				
						$qrycertdata = mysql_query("SELECT DOCCODE,DOCNO,date_format(DATEISSUED,'%m/%d/%Y') AS DATEISSUED,GRADE
											FROM crewcertstatus
											WHERE APPLICANTNO=$applicantno AND IDNO=$editid") or die(mysql_error());
						
						$rowcertdata = mysql_fetch_array($qrycertdata);
						
						$certcode = $rowcertdata["DOCCODE"];
						$certno = $rowcertdata["DOCNO"];
						$certgrade = $rowcertdata["GRADE"];
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
												date_format(DATEFROM,'%m/%d/%Y') AS DATEFROM,date_format(DATETO,'%m/%d/%Y') AS DATETO,
												BASICSALARY,OVERTIME,REASON
												FROM employhistory
												WHERE APPLICANTNO=$applicantno AND IDNO=$editid") or die(mysql_error());
						
						$rowempdata = mysql_fetch_array($qryempdata);
				
						$empemployer = $rowempdata["EMPLOYER"];
						$empposition = $rowempdata["POSITION"];
						$emptelno = $rowempdata["TELNO"];
						$empdatefrom = $rowempdata["DATEFROM"];
						$empdateto = $rowempdata["DATETO"];
						$empsalary = $rowempdata["BASICSALARY"];
						$empovertime = $rowempdata["OVERTIME"];
						$empreason = $rowempdata["REASON"];
						
					break;
				case "8": //QUESTIONS
				
					break;
				case "9": //REFERENCE
				
						$qryrefdata = mysql_query("SELECT NAME,TELNO,OCCUPATION,REMARKS
												FROM applicantreference
												WHERE APPLICANTNO=$applicantno AND IDNO=$editid") or die(mysql_error());
						
						$rowrefdata = mysql_fetch_array($qryrefdata);
						
						$refname = $rowrefdata["NAME"];
						$reftelno = $rowrefdata["TELNO"];
						$refoccupation = $rowrefdata["OCCUPATION"];
						$refremarks = $rowrefdata["REMARKS"];
				
					break;
				case "10": //AGREEMENT
					
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
				case "8": //QUESTIONS
				
					break;
				case "9": //REFERENCE
				
						$qryrefdelete = mysql_query("DELETE FROM applicantreference WHERE APPLICANTNO=$applicantno AND IDNO=$deleteid") or die(mysql_error());
						
					break;
				case "10": //AGREEMENT
					
					break;
			}
		break;
		
	case "agreed"	: 
			
			//SAVE QUESTIONS,RECOMMENDED BY TO APPLICANT TABLE
			
			if ($qdesiredsalary == "")
				$qdesiredsalary = 0;
				
//			if ($qdesiredot == "")
//				$qdesiredot = 0;
			
			if ($qanswer1a == "")
				$qanswer1a = "NULL";
			
			if ($qanswer2a == "")
				$qanswer2a = "NULL";
			
			if ($qanswer3a == "")
				$qanswer3a = "NULL";
			
			if ($qanswer4a == "")
				$qanswer4a = "NULL";
			
			if ($qanswer5a == "")
				$qanswer5a = "NULL";				
				
			$qryapplicantsave = mysql_query("UPDATE applicant SET DESIREDSALARY = $qdesiredsalary,
																RECOMMENDEDBY = '$recommendedby',
																QUESTION1A = $qanswer1a,
																QUESTION1B = '$qanswer1b',
																QUESTION2A = $qanswer2a,
																QUESTION2B = '$qanswer2b',
																QUESTION3A = $qanswer3a,
																QUESTION3B = '$qanswer3b',
																QUESTION4A = $qanswer4a,
																QUESTION4B = '$qanswer4b',
																QUESTION5A = $qanswer5a,
																QUESTION5B = '$qanswer5b',
																AGREED = 1
											WHERE APPLICANTNO = $applicantno
											") or die(mysql_error());
			
		break;	
		
	case "print"	:
			$qryprintsave = mysql_query("UPDATE applicant SET PRINTED='$currentdate' WHERE APPLICANTNO=$applicantno") or die(mysql_error());
		
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
			$educdategrad = "";
			$educlevel = "";
			$educhonors = "";
			
			$liccode = "";
			$licno = "";
			$licissued = "";
			$licexpired = "";
			
			$licno1 = "";
			$licissued1 = "";
			$licexpired1 = "";
			
			$licno2 = "";
			$licissued2 = "";
			$licexpired2 = "";
			
			$licno3 = "";
			$licissued3 = "";
			$licexpired3 = "";
			
			$licno4 = "";
			$licissued4 = "";
			$licexpired4 = "";
			
			$licno5 = "";
			$licissued5 = "";
			$licexpired5 = "";
			
			$licno6 = "";
			$licissued6 = "";
			$licexpired6 = "";
			
			$licno7 = "";
			$licissued7 = "";
			$licexpired7 = "";
			
			$licno8 = "";
			$licissued8 = "";
			$licexpired8 = "";
			
			$licno9 = "";
			$licissued9 = "";
			$licexpired9 = "";
			
			$licno10 = "";
			$licissued10 = "";
			$licexpired10 = "";
			
			$licno11 = "";
			$licissued11 = "";
			$licexpired11 = "";
			
			$licno12 = "";
			$licissued12 = "";
			$licexpired12 = "";
			
			$licno13 = "";
			$licissued13 = "";
			$licexpired13 = "";
			
			$licno14 = "";
			$licissued14 = "";
			$licexpired14 = "";
			
			$doccode = "";
			$docno = "";
			$docissued = "";
			$docexpired = "";
			$usvisano = "";
			$usvisaissued = "";
			$usvisaexpired = "";

			$sbookno = "";
			$sbookissued = "";
			$sbookexpired = "";
			$passportno = "";
			$passportissued = "";
			$passportexpired = "";				

			$certcode = "";
			$certno = "";
			$certissued = "";
			$certno1 = "";
			$certissued1 = "";
			$certno2 = "";
			$certissued2 = "";
			$certno3 = "";
			$certissued3 = "";
			$certno4 = "";
			$certissued4 = "";
			$certno5 = "";
			$certissued5 = "";
			
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
			
			$qanswer1a = "";
			$qanswer1b = "";
			$qanswer2a = "";
			$qanswer2b = "";
			$qanswer3a = "";
			$qanswer3b = "";
			$qanswer4a = "";
			$qanswer4b = "";
			$qanswer5a = "";
			$qanswer5b = "";
			$qdesiredsalary = "";
			$recommendedby = "";
			
			$refname = "";
			$reftelno = "";
			$refoccupation = "";
			$refremarks = "";							
							
										
		break;
	
	case "search"	: //SEARCH KEY
			
			$appno = "";
			switch ($searchby) // TO GET THE APPLICANT NO BASED ON THE SEARCHBY FIELD
			{
				case "1": //APPLICANT NO
						
						$appno = $searchkey;
				
					break;
					
				case "2": //SSS NO
					
					$qrysearchapplicant = mysql_query("SELECT APPLICANTNO FROM crew WHERE SSS='$searchkey'") or die(mysql_error());
					$rowsearchapplicant = mysql_fetch_array($qrysearchapplicant);
					
					$cntapp = mysql_num_rows($qrysearchapplicant);
					
					if ($cntapp == 1)
					{
						$appno = $rowsearchapplicant["APPLICANTNO"];

					}
					elseif ($cntapp = 0)
						$searcherror = "SSS No. $searchkey not found.";
					else 
						$searcherror = "Multiple entries found.";
				
					break;
			}
			
			//EXTRACT ALL DATA FOR ALL PAGES
			
			//APPLICANT
			
			if ($appno != "")
			{
				$qrygetapplicant = mysql_query("SELECT CHOICE1,CHOICE2,DATEAPPLIED,RECOMMENDEDBY,DESIREDSALARY,DESIREDOT,
												QUESTION1A,QUESTION1B,QUESTION2A,QUESTION2B,QUESTION3A,QUESTION3B,QUESTION4A,QUESTION4B,QUESTION5A,QUESTION5B
												FROM applicant 
												WHERE APPLICANTNO=$appno") or die(mysql_error());
				
				if (mysql_num_rows($qrygetapplicant) == 1)
				{	
					$rowgetapplicant = mysql_fetch_array($qrygetapplicant);
					
					$choice1 = $rowgetapplicant["CHOICE1"];
					$choice2 = $rowgetapplicant["CHOICE2"];
					
					$recommendedby = $rowgetapplicant["RECOMMENDEDBY"];
					$qdesiredsalary = $rowgetapplicant["DESIREDSALARY"];
//					$qdesiredot = $rowgetapplicant["DESIREDOT"];
					
					$qanswer1a = $rowgetapplicant["QUESTION1A"];
					$qanswer1b = $rowgetapplicant["QUESTION1B"];
					$qanswer2a = $rowgetapplicant["QUESTION2A"];
					$qanswer2b = $rowgetapplicant["QUESTION2B"];
					$qanswer3a = $rowgetapplicant["QUESTION3A"];
					$qanswer3b = $rowgetapplicant["QUESTION3B"];
					$qanswer4a = $rowgetapplicant["QUESTION4A"];
					$qanswer4b = $rowgetapplicant["QUESTION4B"];
					$qanswer5a = $rowgetapplicant["QUESTION5A"];
					$qanswer5b = $rowgetapplicant["QUESTION5B"];
					
					//CREW
					
					$qrygetcrew = mysql_query("SELECT FNAME,GNAME,MNAME,ADDRESS,PROVINCECODE,TOWNCODE,BARANGAYCODE,ZIPCODE,TELNO,
												PROVADDRESS,PROVPROVCODE,PROVTOWNCODE,PROVBRGYCODE,PROVTELNO,PROVZIPCODE,
												BIRTHDATE,BIRTHPLACE,GENDER,CIVILSTATUS,RELIGION,WEIGHT,HEIGHT,SIZESHOES,EMAIL,
												CEL1,CEL2,CEL3
												FROM crew
												WHERE APPLICANTNO=$appno") or die(mysql_error());
					
					$rowgetcrew = mysql_fetch_array($qrygetcrew);
					
					$fname = $rowgetcrew["FNAME"];
					$gname = $rowgetcrew["GNAME"];
					$mname = $rowgetcrew["MNAME"];
					
					$crewaddress = $rowgetcrew["ADDRESS"];
					$crewprovince = $rowgetcrew["PROVINCECODE"];
					$crewcity = $rowgetcrew["TOWNCODE"];
					$crewbarangay = $rowgetcrew["BARANGAYCODE"];
					$crewzipcode = $rowgetcrew["ZIPCODE"];
					$crewtelno = $rowgetcrew["TELNO"];
					
					$crewprovaddress = $rowgetcrew["PROVADDRESS"];
					$crewprovprovince = $rowgetcrew["PROVPROVCODE"];
					$crewprovcity = $rowgetcrew["PROVTOWNCODE"];
					$crewprovbarangay = $rowgetcrew["PROVBRGYCODE"];
					$crewprovzipcode = $rowgetcrew["PROVZIPCODE"];
					$crewprovtelno = $rowgetcrew["PROVTELNO"];
					
					if ($rowgetcrew["BIRTHDATE"] == "")
						$crewbdate = "";
					else 
						$crewbdate = date('m/d/Y',strtotime($rowgetcrew["BIRTHDATE"]));
						
					$crewbplace = $rowgetcrew["BIRTHPLACE"];
					$crewgender = $rowgetcrew["GENDER"];
					$crewcivilstatus = $rowgetcrew["CIVILSTATUS"];
//					$crewnationality = $rowgetcrew["NATIONALITY"];
					$crewreligion = $rowgetcrew["RELIGION"];
//					$crewsss = $rowgetcrew["SSS"];
//					$crewtin = $rowgetcrew["TIN"];
					$crewweight = $rowgetcrew["WEIGHT"];
					$crewheight = $rowgetcrew["HEIGHT"];
					$crewshoesize = $rowgetcrew["SIZESHOES"];
					$crewemail = $rowgetcrew["EMAIL"];
					
					$crewcelphone1 = $rowgetcrew["CEL1"];
					$crewcelphone2 = $rowgetcrew["CEL2"];
					$crewcelphone3 = $rowgetcrew["CEL3"];
					
					$applicantno = $appno; //update $applicantno					
				}
				else 
				{
					$searcherror = "Applicant No. not found!";
					$applicantno = "";
				}
			}
			
		break;
	
	case "getappno"	: //INITIAL SAVING OF APPLICANT NO (PERSONAL INFORMATION)
	
			$crewbdateraw = date('Y-m-d',strtotime($crewbdate));
			
//FNAME,GNAME,MNAME,ADDRESS,PROVINCECODE,TOWNCODE,BARANGAYCODE,ZIPCODE,TELNO,
//												PROVADDRESS,PROVPROVCODE,PROVTOWNCODE,PROVBRGYCODE,PROVTELNO,PROVZIPCODE,
//												BIRTHDATE,BIRTHPLACE,GENDER,CIVILSTATUS,RELIGION,WEIGHT,HEIGHT,SIZESHOES,EMAIL
			
			$qrysavecrew = mysql_query("INSERT INTO crew(FNAME,GNAME,MNAME,
						ADDRESS,PROVINCECODE,TOWNCODE,BARANGAYCODE,ZIPCODE,TELNO,
						PROVADDRESS,PROVPROVCODE,PROVTOWNCODE,PROVBRGYCODE,PROVZIPCODE,PROVTELNO,
						BIRTHDATE,BIRTHPLACE,CIVILSTATUS,NATIONALITY,RELIGION,WEIGHT,HEIGHT,
						GENDER,SIZESHOES,MADEBY,MADEDATE,EMAIL,CEL1,CEL2,CEL3) 
						VALUES('$fname','$gname','$mname','$crewaddress','$crewprovince','$crewcity','$crewbarangay','$crewzipcode','$crewtelno',
						'$crewprovaddress','$crewprovprovince','$crewprovcity','$crewprovbarangay','$crewprovzipcode','$crewprovtelno',
						'$crewbdateraw','$crewbplace','$crewcivilstatus','FILIPINO','$crewreligion','$crewweight','$crewheight',
						'$crewgender','$crewshoesize','APP','$currentdate','$crewemail','$crewcelphone1','$crewcelphone2','$crewcelphone3')") or die(mysql_error());
			
			$qrygetapplicantno = mysql_query("SELECT MAX(APPLICANTNO) AS APPLICANTNO FROM crew WHERE FNAME='$fname' AND GNAME='$gname'") or die(mysql_error());
			$rowgetapplicantno = mysql_fetch_array($qrygetapplicantno);
			
			$applicantno = $rowgetapplicantno["APPLICANTNO"];
			
			$qrysaveapplicant = mysql_query("INSERT INTO applicant(APPLICANTNO,CHOICE1,CHOICE2,DATEAPPLIED) 
											VALUES($applicantno,'$choice1','$choice2','$currentdate')") or die(mysql_error());		
	
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
											'$familyjobstatus','$familyoccupation',$familydependent,'$familyrelothers',NULL,'APP','$currentdate',
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
											COURSEID,COURSEOTHERS,DATEGRADUATED,LEVEL,HONORS,MADEBY,MADEDATE) 
											VALUES($applicantno,$educschoolid,'$educschoolothers',$educcourseid,'$educcourseothers',
											'$educdategradraw','$educlevel','$educhonors','APP','$currentdate')
											") or die(mysql_error());
							
							$educschoolid = "";
							$educschoolothers = "";
							$educcourseid = "";
							$educcourseothers = "";
							$educdategrad = "";
							$educlevel = "";
							$educhonors = "";
						}
						else 
							$remarks2 = "Duplicate Entry. Please try again.";
							
					break;
				case "3": //DOCUMENTS
				
						if($doccode != "")
						{
							$qrydoccheck = mysql_query("SELECT APPLICANTNO FROM crewdocstatus WHERE DOCCODE='$doccode' AND DOCNO='$docno'
												 AND APPLICANTNO=$applicantno") or die(mysql_error());
							
							if (mysql_num_rows($qrydoccheck) < 1)
							{
								$docissuedraw = date('Y-m-d',strtotime($docissued));
								
								$docexpiredraw = date('Y-m-d',strtotime($docexpired));
					
								$qrydocumentssave = mysql_query("INSERT INTO crewdocstatus(APPLICANTNO,DOCCODE,DATEISSUED,DATEEXPIRED,DOCNO,MADEBY,MADEDATE)
												VALUES($applicantno,'$doccode','$docissuedraw','$docexpiredraw','$docno','APP','$currentdate')
												") or die(mysql_error());
								
								$doccode = "";
								$docno = "";
								$docissued = "";
								$docexpired = "";
							}
							else 
								$remarks3 = "Duplicate Entry. Please try again.";
						}
						else 
						{
							if ($passportno != "")
							{
								$qrydoccheck2 = mysql_query("SELECT APPLICANTNO FROM crewdocstatus WHERE DOCCODE='41' AND DOCNO='$passportno'
												 AND APPLICANTNO=$applicantno") or die(mysql_error());
								
								if (mysql_num_rows($qrydoccheck2) < 1)
								{
									$passportissuedraw = date('Y-m-d',strtotime($passportissued));
									
									$passportexpiredraw = date('Y-m-d',strtotime($passportexpired));
									
									$qrydocumentssave = mysql_query("INSERT INTO crewdocstatus(APPLICANTNO,DOCCODE,DATEISSUED,DATEEXPIRED,DOCNO,MADEBY,MADEDATE)
													VALUES($applicantno,'41','$passportissuedraw','$passportexpiredraw','$passportno','APP','$currentdate')
													") or die(mysql_error());
									
									$passportno = "";
									$passportissued = "";
									$passportexpired = "";
								}
								else 
									if ($remarks3 == "")
										$remarks3 = "Passport Number already exists.";
									else 
										$remarks3 .= "; Passport Number already exists.";
							}
							
							if ($sbookno != "")
							{
								
								$qrydoccheck3 = mysql_query("SELECT APPLICANTNO FROM crewdocstatus WHERE DOCCODE='F2' AND DOCNO='$sbookno'
												 AND APPLICANTNO=$applicantno") or die(mysql_error());
								
								if (mysql_num_rows($qrydoccheck3) < 1)
								{
									$sbookissuedraw = date('Y-m-d',strtotime($sbookissued));
									
									$sbookexpiredraw = date('Y-m-d',strtotime($sbookexpired));
						
									$qrydocumentssave = mysql_query("INSERT INTO crewdocstatus(APPLICANTNO,DOCCODE,DATEISSUED,DATEEXPIRED,DOCNO,MADEBY,MADEDATE)
													VALUES($applicantno,'F2','$sbookissuedraw','$sbookexpiredraw','$sbookno','APP','$currentdate')
													") or die(mysql_error());
									
									$sbookno = "";
									$sbookissued = "";
									$sbookexpired = "";
								}
								else 
									if ($remarks3 == "")
										$remarks3 = "Seaman Book Number already exists.";
									else 
										$remarks3 .= "; Seaman Book Number already exists.";
							}
							
//							if ($srcno != "")
//							{
//								$qrydoccheck4 = mysql_query("SELECT APPLICANTNO FROM crewdocstatus WHERE DOCCODE='32' AND DOCNO='$srcno'
//												 AND APPLICANTNO=$applicantno") or die(mysql_error());
//								
//								if (mysql_num_rows($qrydoccheck4) < 1)
//								{
//									$srcissuedraw = date('Y-m-d',strtotime($srcissued));
//									
//									$srcexpiredraw = date('Y-m-d',strtotime($srcexpired));
//						
//									$qrydocumentssave = mysql_query("INSERT INTO crewdocstatus(APPLICANTNO,DOCCODE,DATEISSUED,DATEEXPIRED,DOCNO,MADEBY,MADEDATE)
//													VALUES($applicantno,'32','$srcissuedraw','$srcexpiredraw','$srcno','APP','$currentdate')
//													") or die(mysql_error());
//									
//									$srcno = "";
//									$srcissued = "";
//									$srcexpired = "";
//								}
//								else 
//									if ($remarks3 == "")
//										$remarks3 = "SRC Number already exists.";
//									else 
//										$remarks3 .= "; SRC Number already exists.";
//							}
							
							if ($usvisano != "")
							{
								$qrydoccheck5 = mysql_query("SELECT APPLICANTNO FROM crewdocstatus WHERE DOCCODE='42' AND DOCNO='$usvisano'
												 AND APPLICANTNO=$applicantno") or die(mysql_error());
								
								if (mysql_num_rows($qrydoccheck5) < 1)
								{
									$usvisaissuedraw = date('Y-m-d',strtotime($usvisaissued));
									
									$usvisaexpiredraw = date('Y-m-d',strtotime($usvisaexpired));
						
									$qrydocumentssave = mysql_query("INSERT INTO crewdocstatus(APPLICANTNO,DOCCODE,DATEISSUED,DATEEXPIRED,DOCNO,MADEBY,MADEDATE)
													VALUES($applicantno,'42','$usvisaissuedraw','$usvisaexpiredraw','$usvisano','APP','$currentdate')
													") or die(mysql_error());
									
									$usvisano = "";
									$usvisanoissued = "";
									$usvisanoexpired = "";
								}
								else 
									if ($remarks3 == "")
										$remarks3 = "U.S. VISA Number already exists.";
									else 
										$remarks3 .= "; U.S. VISA Number already exists.";	
							}
						}
					break;
				case "4": //LICENSE
						if ($licno != "")
						{
							$qryliccheck = mysql_query("SELECT APPLICANTNO FROM crewdocstatus WHERE DOCCODE='$liccode' AND DOCNO='$licno'
												 AND APPLICANTNO=$applicantno") or die(mysql_error());
							
							if (mysql_num_rows($qryliccheck) < 1)
							{
								$licissuedraw = date('Y-m-d',strtotime($licissued));
								$licexpiredraw = date('Y-m-d',strtotime($licexpired));
					
								$qrylicensesave = mysql_query("INSERT INTO crewdocstatus(APPLICANTNO,DOCCODE,DATEISSUED,DATEEXPIRED,DOCNO,MADEBY,MADEDATE)
												VALUES($applicantno,'$liccode','$licissuedraw','$licexpiredraw','$licno','APP','$currentdate')
												") or die(mysql_error());
								
								$liccode = "";
								$licno = "";
								$licissued = "";
								$licexpired = "";
							}
							else 
								$remarks4 = "Duplicate Entry. Please try again.";
						}
						else 
						{
							if ($licno1 != "")  //PHILIPPINE LICENSE
							{
								$qryliccheck1 = mysql_query("SELECT APPLICANTNO FROM crewdocstatus WHERE DOCCODE='F1' AND DOCNO='$licno1'
												 AND APPLICANTNO=$applicantno") or die(mysql_error());
								
								if (mysql_num_rows($qryliccheck1) < 1)
								{
									$licissued1raw = date('Y-m-d',strtotime($licissued1));
									$licexpired1raw = date('Y-m-d',strtotime($licexpired1));
									
									$qrydocumentssave = mysql_query("INSERT INTO crewdocstatus(APPLICANTNO,DOCCODE,DATEISSUED,DATEEXPIRED,DOCNO,MADEBY,MADEDATE)
													VALUES($applicantno,'F1','$licissued1raw','$licexpired1raw','$licno1','APP','$currentdate')
													") or die(mysql_error());
									
									$licno1 = "";
									$licissued1 = "";
									$licexpired1 = "";
								}
								else 
									if ($remarks3 == "")
										$remarks3 = "Philippine License Number already exists.";
									else 
										$remarks3 .= "; Philippine License Number already exists.";
							}
							
							if ($licno2 != "")  //COC for OFFICEERS
							{
								$qryliccheck2 = mysql_query("SELECT APPLICANTNO FROM crewdocstatus WHERE DOCCODE='18' AND DOCNO='$licno2'
												 AND APPLICANTNO=$applicantno") or die(mysql_error());
								
								if (mysql_num_rows($qryliccheck2) < 1)
								{
									$licissued2raw = date('Y-m-d',strtotime($licissued2));
									$licexpired2raw = date('Y-m-d',strtotime($licexpired2));
									
									$qrydocumentssave = mysql_query("INSERT INTO crewdocstatus(APPLICANTNO,DOCCODE,DATEISSUED,DATEEXPIRED,DOCNO,MADEBY,MADEDATE)
													VALUES($applicantno,'18','$licissued2raw','$licexpired2raw','$licno2','APP','$currentdate')
													") or die(mysql_error());
									
									$licno2 = "";
									$licissued2 = "";
									$licexpired2 = "";
								}
								else 
									if ($remarks3 == "")
										$remarks3 = "COC for Officers Number already exists.";
									else 
										$remarks3 .= "; COC for Officers Number already exists.";
							}
							
							if ($licno3 != "")  //EOC for OFFICERS
							{
								$qryliccheck3 = mysql_query("SELECT APPLICANTNO FROM crewdocstatus WHERE DOCCODE='16' AND DOCNO='$licno3'
												 AND APPLICANTNO=$applicantno") or die(mysql_error());
								
								if (mysql_num_rows($qryliccheck3) < 1)
								{
									$licissued3raw = date('Y-m-d',strtotime($licissued3));
									$licexpired3raw = date('Y-m-d',strtotime($licexpired3));
									
									$qrydocumentssave = mysql_query("INSERT INTO crewdocstatus(APPLICANTNO,DOCCODE,DATEISSUED,DATEEXPIRED,DOCNO,MADEBY,MADEDATE)
													VALUES($applicantno,'16','$licissued3raw','$licexpired3raw','$licno3','APP','$currentdate')
													") or die(mysql_error());
									
									$licno3 = "";
									$licissued3 = "";
									$licexpired3 = "";
								}
								else 
									if ($remarks3 == "")
										$remarks3 = "EOC for Officers Number already exists.";
									else 
										$remarks3 .= "; EOC for Officers Number already exists.";
							}
							
							if ($licno4 != "")  //COC for RATINGS
							{
								$qryliccheck4 = mysql_query("SELECT APPLICANTNO FROM crewdocstatus WHERE DOCCODE='C0' AND DOCNO='$licno4'
												 AND APPLICANTNO=$applicantno") or die(mysql_error());
								
								if (mysql_num_rows($qryliccheck4) < 1)
								{
									$licissued4raw = date('Y-m-d',strtotime($licissued4));
									$licexpired4raw = date('Y-m-d',strtotime($licexpired4));
									
									$qrydocumentssave = mysql_query("INSERT INTO crewdocstatus(APPLICANTNO,DOCCODE,DATEISSUED,DATEEXPIRED,DOCNO,MADEBY,MADEDATE)
													VALUES($applicantno,'C0','$licissued4raw','$licexpired4raw','$licno4','APP','$currentdate')
													") or die(mysql_error());
									
									$licno4 = "";
									$licissued4 = "";
									$licexpired4 = "";
								}
								else 
									if ($remarks3 == "")
										$remarks3 = "COC for Ratings Number already exists.";
									else 
										$remarks3 .= "; COC for Ratings Number already exists.";
							}
							
							if ($licno5 != "")  //PANAMA LICENSE
							{
								$qryliccheck5 = mysql_query("SELECT APPLICANTNO FROM crewdocstatus WHERE DOCCODE='P1' AND DOCNO='$licno5'
												 AND APPLICANTNO=$applicantno") or die(mysql_error());
								
								if (mysql_num_rows($qryliccheck5) < 1)
								{
									$licissued5raw = date('Y-m-d',strtotime($licissued5));
									$licexpired5raw = date('Y-m-d',strtotime($licexpired5));
									
									$qrydocumentssave = mysql_query("INSERT INTO crewdocstatus(APPLICANTNO,DOCCODE,DATEISSUED,DATEEXPIRED,DOCNO,MADEBY,MADEDATE)
													VALUES($applicantno,'P1','$licissued5raw','$licexpired5raw','$licno5','APP','$currentdate')
													") or die(mysql_error());
									
									$licno5 = "";
									$licissued5 = "";
									$licexpired5 = "";
								}
								else 
									if ($remarks3 == "")
										$remarks3 = "Panama License Number already exists.";
									else 
										$remarks3 .= "; Panama License Number already exists.";
							}
							
							if ($licno6 != "")  //PANAMA SEAMAN BOOK
							{
								$qryliccheck6 = mysql_query("SELECT APPLICANTNO FROM crewdocstatus WHERE DOCCODE='P2' AND DOCNO='$licno6'
												 AND APPLICANTNO=$applicantno") or die(mysql_error());
								
								if (mysql_num_rows($qryliccheck6) < 1)
								{
									$licissued6raw = date('Y-m-d',strtotime($licissued6));
									$licexpired6raw = date('Y-m-d',strtotime($licexpired6));
									
									$qrydocumentssave = mysql_query("INSERT INTO crewdocstatus(APPLICANTNO,DOCCODE,DATEISSUED,DATEEXPIRED,DOCNO,MADEBY,MADEDATE)
													VALUES($applicantno,'P2','$licissued6raw','$licexpired6raw','$licno6','APP','$currentdate')
													") or die(mysql_error());
									
									$licno6 = "";
									$licissued6 = "";
									$licexpired6 = "";
								}
								else 
									if ($remarks3 == "")
										$remarks3 = "Panama Seaman Book Number already exists.";
									else 
										$remarks3 .= "; Panama Seaman Book Number already exists.";
							}
							
							if ($licno7 != "")  //PANAMA GMDSS LICENSE
							{
								$qryliccheck7 = mysql_query("SELECT APPLICANTNO FROM crewdocstatus WHERE DOCCODE='28' AND DOCNO='$licno7'
												 AND APPLICANTNO=$applicantno") or die(mysql_error());
								
								if (mysql_num_rows($qryliccheck7) < 1)
								{
									$licissued7raw = date('Y-m-d',strtotime($licissued7));
									$licexpired7raw = date('Y-m-d',strtotime($licexpired7));
									
									$qrydocumentssave = mysql_query("INSERT INTO crewdocstatus(APPLICANTNO,DOCCODE,DATEISSUED,DATEEXPIRED,DOCNO,MADEBY,MADEDATE)
													VALUES($applicantno,'28','$licissued7raw','$licexpired7raw','$licno7','APP','$currentdate')
													") or die(mysql_error());
									
									$licno7 = "";
									$licissued7 = "";
									$licexpired7 = "";
								}
								else 
									if ($remarks3 == "")
										$remarks3 = "Panama GMDSS License Number already exists.";
									else 
										$remarks3 .= "; Panama GMDSS License Number already exists.";
							}
							
							if ($licno8 != "")  //PANAMA GMDSS SEAMAN BOOK
							{
								$qryliccheck8 = mysql_query("SELECT APPLICANTNO FROM crewdocstatus WHERE DOCCODE='44' AND DOCNO='$licno8'
												 AND APPLICANTNO=$applicantno") or die(mysql_error());
								
								if (mysql_num_rows($qryliccheck8) < 1)
								{
									$licissued8raw = date('Y-m-d',strtotime($licissued8));
									$licexpired8raw = date('Y-m-d',strtotime($licexpired8));
									
									$qrydocumentssave = mysql_query("INSERT INTO crewdocstatus(APPLICANTNO,DOCCODE,DATEISSUED,DATEEXPIRED,DOCNO,MADEBY,MADEDATE)
													VALUES($applicantno,'44','$licissued8raw','$licexpired8raw','$licno8','APP','$currentdate')
													") or die(mysql_error());
									
									$licno8 = "";
									$licissued8 = "";
									$licexpired8 = "";
								}
								else 
									if ($remarks3 == "")
										$remarks3 = "Panama GMDSS Seaman Book Number already exists.";
									else 
										$remarks3 .= "; Panama GMDSS Seaman Book Number already exists.";
							}
							
							if ($licno9 != "")  //GOC
							{
								$qryliccheck9 = mysql_query("SELECT APPLICANTNO FROM crewdocstatus WHERE DOCCODE='27' AND DOCNO='$licno9'
												 AND APPLICANTNO=$applicantno") or die(mysql_error());
								
								if (mysql_num_rows($qryliccheck9) < 1)
								{
									$licissued9raw = date('Y-m-d',strtotime($licissued9));
									$licexpired9raw = date('Y-m-d',strtotime($licexpired9));
									
									$qrydocumentssave = mysql_query("INSERT INTO crewdocstatus(APPLICANTNO,DOCCODE,DATEISSUED,DATEEXPIRED,DOCNO,MADEBY,MADEDATE)
													VALUES($applicantno,'27','$licissued9raw','$licexpired9raw','$licno9','APP','$currentdate')
													") or die(mysql_error());
									
									$licno9 = "";
									$licissued9 = "";
									$licexpired9 = "";
								}
								else 
									if ($remarks3 == "")
										$remarks3 = "GOC Number already exists.";
									else 
										$remarks3 .= "; GOC Number already exists.";
							}
							
							if ($licno10 != "")  //HONGKONG LICENSE
							{
								$qryliccheck10 = mysql_query("SELECT APPLICANTNO FROM crewdocstatus WHERE DOCCODE='H1' AND DOCNO='$licno10'
												 AND APPLICANTNO=$applicantno") or die(mysql_error());
								
								if (mysql_num_rows($qryliccheck10) < 1)
								{
									$licissued10raw = date('Y-m-d',strtotime($licissued10));
									$licexpired10raw = date('Y-m-d',strtotime($licexpired10));
									
									$qrydocumentssave = mysql_query("INSERT INTO crewdocstatus(APPLICANTNO,DOCCODE,DATEISSUED,DATEEXPIRED,DOCNO,MADEBY,MADEDATE)
													VALUES($applicantno,'H1','$licissued10raw','$licexpired10raw','$licno10','APP','$currentdate')
													") or die(mysql_error());
									
									$licno10 = "";
									$licissued10 = "";
									$licexpired10 = "";
								}
								else 
									if ($remarks3 == "")
										$remarks3 = "Hongkong License already exists.";
									else 
										$remarks3 .= "; Hongkong License already exists.";
							}
							
							if ($licno11 != "")  //JAPANESE LICENSE
							{
								$qryliccheck11 = mysql_query("SELECT APPLICANTNO FROM crewdocstatus WHERE DOCCODE='J1' AND DOCNO='$licno11'
												 AND APPLICANTNO=$applicantno") or die(mysql_error());
								
								if (mysql_num_rows($qryliccheck11) < 1)
								{
									$licissued11raw = date('Y-m-d',strtotime($licissued11));
									$licexpired11raw = date('Y-m-d',strtotime($licexpired11));
									
									$qrydocumentssave = mysql_query("INSERT INTO crewdocstatus(APPLICANTNO,DOCCODE,DATEISSUED,DATEEXPIRED,DOCNO,MADEBY,MADEDATE)
													VALUES($applicantno,'J1','$licissued11raw','$licexpired11raw','$licno11','APP','$currentdate')
													") or die(mysql_error());
									
									$licno11 = "";
									$licissued11 = "";
									$licexpired11 = "";
								}
								else 
									if ($remarks3 == "")
										$remarks3 = "Japanese License already exists.";
									else 
										$remarks3 .= "; Japanese License already exists.";
							}
							
							if ($licno12 != "")  //JAPANESE GOC
							{
								$qryliccheck12 = mysql_query("SELECT APPLICANTNO FROM crewdocstatus WHERE DOCCODE='J3' AND DOCNO='$licno12'
												 AND APPLICANTNO=$applicantno") or die(mysql_error());
								
								if (mysql_num_rows($qryliccheck12) < 1)
								{
									$licissued12raw = date('Y-m-d',strtotime($licissued12));
									$licexpired12raw = date('Y-m-d',strtotime($licexpired12));
									
									$qrydocumentssave = mysql_query("INSERT INTO crewdocstatus(APPLICANTNO,DOCCODE,DATEISSUED,DATEEXPIRED,DOCNO,MADEBY,MADEDATE)
													VALUES($applicantno,'J3','$licissued12raw','$licexpired12raw','$licno12','APP','$currentdate')
													") or die(mysql_error());
									
									$licno12 = "";
									$licissued12 = "";
									$licexpired12 = "";
								}
								else 
									if ($remarks3 == "")
										$remarks3 = "Japanese GOC already exists.";
									else 
										$remarks3 .= "; Japanese GOC already exists.";
							}
							
							if ($licno13 != "")  //SINGAPORE GOC
							{
								$qryliccheck13 = mysql_query("SELECT APPLICANTNO FROM crewdocstatus WHERE DOCCODE='S1' AND DOCNO='$licno13'
												 AND APPLICANTNO=$applicantno") or die(mysql_error());
								
								if (mysql_num_rows($qryliccheck13) < 1)
								{
									$licissued13raw = date('Y-m-d',strtotime($licissued13));
									$licexpired13raw = date('Y-m-d',strtotime($licexpired13));
									
									$qrydocumentssave = mysql_query("INSERT INTO crewdocstatus(APPLICANTNO,DOCCODE,DATEISSUED,DATEEXPIRED,DOCNO,MADEBY,MADEDATE)
													VALUES($applicantno,'S1','$licissued13raw','$licexpired13raw','$licno13','APP','$currentdate')
													") or die(mysql_error());
									
									$licno13 = "";
									$licissued13 = "";
									$licexpired13 = "";
								}
								else 
									if ($remarks3 == "")
										$remarks3 = "Singapore GOC already exists.";
									else 
										$remarks3 .= "; Singapore GOC already exists.";
							}
							
							if ($licno14 != "")  //SINGAPORE COE
							{
								$qryliccheck14 = mysql_query("SELECT APPLICANTNO FROM crewdocstatus WHERE DOCCODE='S2' AND DOCNO='$licno14'
												 AND APPLICANTNO=$applicantno") or die(mysql_error());
								
								if (mysql_num_rows($qryliccheck14) < 1)
								{
									$licissued14raw = date('Y-m-d',strtotime($licissued14));
									$licexpired14raw = date('Y-m-d',strtotime($licexpired14));
									
									$qrydocumentssave = mysql_query("INSERT INTO crewdocstatus(APPLICANTNO,DOCCODE,DATEISSUED,DATEEXPIRED,DOCNO,MADEBY,MADEDATE)
													VALUES($applicantno,'S2','$licissued14raw','$licexpired14raw','$licno14','APP','$currentdate')
													") or die(mysql_error());
									
									$licno14 = "";
									$licissued14 = "";
									$licexpired14 = "";
								}
								else 
									if ($remarks3 == "")
										$remarks3 = "Singapore COE already exists.";
									else 
										$remarks3 .= "; Singapore COE already exists.";
							}
						}
					break;
				case "5": //CERTIFICATE
					
						if ($certno != "")
						{
							$qrycertcheck = mysql_query("SELECT APPLICANTNO FROM crewcertstatus WHERE DOCCODE='$certcode' AND DOCNO='$certno'
												 AND APPLICANTNO=$applicantno") or die(mysql_error());
							
							if (mysql_num_rows($qrycertcheck) < 1)
							{
								$certissuedraw = date('Y-m-d',strtotime($certissued));
								
								$qrycertificatesave = mysql_query("INSERT INTO crewcertstatus(APPLICANTNO,DOCCODE,DOCNO,DATEISSUED,GRADE,MADEBY,MADEDATE)
												VALUES($applicantno,'$certcode','$certno','$certissuedraw','$certgrade','APP','$currentdate')
												") or die(mysql_error());
								
								$certcode = "";
								$certno = "";
								$certissued = "";
							}
							else 
								$remarks5 = "Duplicate Entry. Please try again.";
						}
						else 
						{
							if ($certno1 != "")  //SSBT
							{
								$qrycertcheck1 = mysql_query("SELECT APPLICANTNO FROM crewcertstatus WHERE DOCCODE='79' AND DOCNO='$certno1'
													 AND APPLICANTNO=$applicantno") or die(mysql_error());
								
								if (mysql_num_rows($qrycertcheck1) < 1)
								{
									$certissued1raw = date('Y-m-d',strtotime($certissued1));	
									
									$qrycertificatesave = mysql_query("INSERT INTO crewcertstatus(APPLICANTNO,DOCCODE,DOCNO,DATEISSUED,MADEBY,MADEDATE)
													VALUES($applicantno,'79','$certno1','$certissued1raw','APP','$currentdate')
													") or die(mysql_error());	
									
									$certno1 = "";
									$certissued1 = "";
								}
								else 
									if ($remarks5 == "")
										$remarks5 = "SSBT Number already exists.";
									else 
										$remarks5 .= "; SSBT Number already exists.";	
							}

							if ($certno2 != "")  //ADVANCE SSBT
							{
								$qrycertcheck2 = mysql_query("SELECT APPLICANTNO FROM crewcertstatus WHERE DOCCODE='A1' AND DOCNO='$certno2'
													 AND APPLICANTNO=$applicantno") or die(mysql_error());
								
								if (mysql_num_rows($qrycertcheck2) < 1)
								{
									$certissued2raw = date('Y-m-d',strtotime($certissued2));	
									
									$qrycertificatesave = mysql_query("INSERT INTO crewcertstatus(APPLICANTNO,DOCCODE,DOCNO,DATEISSUED,MADEBY,MADEDATE)
													VALUES($applicantno,'A1','$certno2','$certissued2raw','APP','$currentdate')
													") or die(mysql_error());	
									
									$certno2 = "";
									$certissued2 = "";
								}
								else 
									if ($remarks5 == "")
										$remarks5 = "Advance SSBT Number already exists.";
									else 
										$remarks5 .= "; Advance SSBT Number already exists.";	
							}

							if ($certno3 != "")  //SSO
							{
								$qrycertcheck3 = mysql_query("SELECT APPLICANTNO FROM crewcertstatus WHERE DOCCODE='49' AND DOCNO='$certno3'
													 AND APPLICANTNO=$applicantno") or die(mysql_error());
								
								if (mysql_num_rows($qrycertcheck3) < 1)
								{
									$certissued3raw = date('Y-m-d',strtotime($certissued3));	
									
									$qrycertificatesave = mysql_query("INSERT INTO crewcertstatus(APPLICANTNO,DOCCODE,DOCNO,DATEISSUED,MADEBY,MADEDATE)
													VALUES($applicantno,'49','$certno3','$certissued3raw','APP','$currentdate')
													") or die(mysql_error());	
									
									$certno3 = "";
									$certissued3 = "";
								}
								else 
									if ($remarks5 == "")
										$remarks5 = "SSO Number already exists.";
									else 
										$remarks5 .= "; SSO Number already exists.";	
							}

							if ($certno4 != "")  //ERS
							{
								$qrycertcheck4 = mysql_query("SELECT APPLICANTNO FROM crewcertstatus WHERE DOCCODE='E1' AND DOCNO='$certno4'
													 AND APPLICANTNO=$applicantno") or die(mysql_error());
								
								if (mysql_num_rows($qrycertcheck4) < 1)
								{
									$certissued4raw = date('Y-m-d',strtotime($certissued4));	
									
									$qrycertificatesave = mysql_query("INSERT INTO crewcertstatus(APPLICANTNO,DOCCODE,DOCNO,DATEISSUED,MADEBY,MADEDATE)
													VALUES($applicantno,'E1','$certno4','$certissued4raw','APP','$currentdate')
													") or die(mysql_error());	
									
									$certno4 = "";
									$certissued4 = "";
								}
								else 
									if ($remarks5 == "")
										$remarks5 = "ERS Number already exists.";
									else 
										$remarks5 .= "; ERS Number already exists.";	
							}

							if ($certno5 != "")  //AIS
							{
								$qrycertcheck5 = mysql_query("SELECT APPLICANTNO FROM crewcertstatus WHERE DOCCODE='A0' AND DOCNO='$certno5'
													 AND APPLICANTNO=$applicantno") or die(mysql_error());
								
								if (mysql_num_rows($qrycertcheck5) < 1)
								{
									$certissued5raw = date('Y-m-d',strtotime($certissued5));	
									
									$qrycertificatesave = mysql_query("INSERT INTO crewcertstatus(APPLICANTNO,DOCCODE,DOCNO,DATEISSUED,MADEBY,MADEDATE)
													VALUES($applicantno,'A0','$certno5','$certissued5raw','APP','$currentdate')
													") or die(mysql_error());	
									
									$certno5 = "";
									$certissued5 = "";
								}
								else 
									if ($remarks5 == "")
										$remarks5 = "AIS Number already exists.";
									else 
										$remarks5 .= "; AIS Number already exists.";	
							}
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
							
							$qryemploymentsave = mysql_query("INSERT INTO employhistory(APPLICANTNO,EMPLOYER,POSITION,TELNO,DATEFROM,DATETO,BASICSALARY,OVERTIME,REASON)
												VALUES($applicantno,'$empemployer','$empposition','$emptelno','$empdatefromraw','$empdatetoraw',$empsalary,$empovertime,
												'$empreason')
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
				case "8": //QUESTIONS
					
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

$qrydocuments = mysql_query("SELECT DOCCODE,DOCUMENT FROM crewdocuments WHERE TYPE='D' ORDER BY DOCUMENT") or die(mysql_error());
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

$qrylicenses = mysql_query("SELECT DOCCODE,DOCUMENT FROM crewdocuments WHERE TYPE='L' ORDER BY DOCUMENT") or die(mysql_error());
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

$qrycertificates = mysql_query("SELECT DOCCODE,DOCUMENT FROM crewdocuments WHERE TYPE='C' ORDER BY DOCUMENT") or die(mysql_error());
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
	
//$qryflag = mysql_query("SELECT FLAGCODE,FLAG FROM flag") or die(mysql_error());
//$expselect6 = "<option selected value=\"\">--Select One--</option>";
//	while($rowflag=mysql_fetch_array($qryflag))
//	{
//		$flagcode = $rowflag["FLAGCODE"];
//		$flag = $rowflag["FLAG"];
//		
//		$selected6 = "";
//		
//		if ($expflagcode == $flagcode)
//			$selected6 = "SELECTED";
//			
//		$expselect6 .= "<option $selected6 value=\"$flagcode\">$flag</option>";
//	}
	
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

//+++++++++++++++  QUESTIONS  ++++++++++++++++++//

$qselect1 = "<option selected value=\"\">--Select--</option>";

$qanswer1ax = "A" . $qanswer1a;
$$qanswer1ax = "SELECTED";	

$qselect1 .= "<option $A1 value=\"1\">YES</option>";
$qselect1 .= "<option $A0 value=\"0\">NO</option>";


$qselect2 = "<option selected value=\"\">--Select--</option>";

$qanswer2ax = "B" . $qanswer2a;
$$qanswer2ax = "SELECTED";	

$qselect2 .= "<option $B1 value=\"1\">YES</option>";
$qselect2 .= "<option $B0 value=\"0\">NO</option>";


$qselect3 = "<option selected value=\"\">--Select--</option>";

$qanswer3ax = "C" . $qanswer3a;
$$qanswer3ax = "SELECTED";	

$qselect3 .= "<option $C1 value=\"1\">YES</option>";
$qselect3 .= "<option $C0 value=\"0\">NO</option>";


$qselect4 = "<option selected value=\"\">--Select--</option>";

$qanswer4ax = "D" . $qanswer4a;
$$qanswer4ax = "SELECTED";	

$qselect4 .= "<option $D1 value=\"1\">YES</option>";
$qselect4 .= "<option $D0 value=\"0\">NO</option>";


$qselect5 = "<option selected value=\"\">--Select--</option>";

$qanswer5ax = "E" . $qanswer5a;
$$qanswer5ax = "SELECTED";	

$qselect5 .= "<option $E1 value=\"1\">YES</option>";
$qselect5 .= "<option $E0 value=\"0\">NO</option>";

//+++++++++++++++  END OF QUESTIONS  ++++++++++++++++++//


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
								ce.LEVEL,ce.REMARKS
								FROM creweducation ce
								LEFT JOIN maritimeschool ms ON ce.SCHOOLID=ms.SCHOOLID
								LEFT JOIN maritimecourses mc ON ce.COURSEID=mc.COURSEID
								WHERE ce.APPLICANTNO=$applicantno") or die(mysql_error());
	
	$qrydoclist = mysql_query("SELECT IDNO,cd.DOCUMENT,cds.DOCNO,cds.DATEISSUED,cds.DATEEXPIRED
								FROM crewdocstatus cds
								LEFT JOIN crewdocuments cd ON cd.DOCCODE=cds.DOCCODE
								WHERE cd.TYPE='D' and cds.APPLICANTNO=$applicantno
								ORDER BY cd.DOCUMENT") or die(mysql_error());
	
	$qryliclist = mysql_query("SELECT IDNO,cd.DOCUMENT,cds.DOCNO,cds.DATEISSUED,cds.DATEEXPIRED
								FROM crewdocstatus cds
								LEFT JOIN crewdocuments cd ON cd.DOCCODE=cds.DOCCODE
								WHERE cd.TYPE='L'and cds.APPLICANTNO=$applicantno
								ORDER BY cd.DOCUMENT") or die(mysql_error());
	
	$qrycertlist = mysql_query("SELECT IDNO,cd.DOCUMENT,ccs.DOCNO,ccs.DATEISSUED,ccs.GRADE
								FROM crewcertstatus ccs
								LEFT JOIN crewdocuments cd ON cd.DOCCODE=ccs.DOCCODE
								WHERE cd.TYPE='C'and ccs.APPLICANTNO=$applicantno
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
	
	$qryreferencelist = mysql_query("SELECT * FROM applicantreference WHERE APPLICANTNO=$applicantno") or die(mysql_error());
	

	//GET CURRENT STATUS OF APPLICATION
	$qrycheckapplicant = mysql_query("SELECT AGREED,PRINTED,APPROVED FROM applicant WHERE APPLICANTNO=$applicantno") or die(mysql_error());
	
	$rowcheckapplicant = mysql_fetch_array($qrycheckapplicant);
	
	$agreedx = $rowcheckapplicant["AGREED"];
	$printedx = $rowcheckapplicant["PRINTED"];
	$approvedx = $rowcheckapplicant["APPROVED"];	
	
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
	
if ($approvedx != "")
	$disableapproved = "disabled=\"disabled\"";
else 
	$disableapproved = "";

	
echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
    \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">

<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">

<!-- HEAD START -->
<head>
	<title>Online Application</title>
	<meta http-equiv=\"content-type\" content=\"text/html;charset=utf-8\" />
	<meta name=\"generator\" content=\"Geany 0.9\" />
	
	<script language=\"javascript\" src=\"popcalendar.js\"></script>	
	<script language=\"javascript\" src=\"veripro.js\"></script>	
	
<!--	<link rel=\"stylesheet\" href=\"veritas.css\">	-->
	<link rel=\"stylesheet\" href=\"veripro.css\">

	<script>
		var displaypage=new Array('personal','family','education','documents','license',
									'certificate','experience','employment','questions','reference','agreement');
									
		
		function changepage(x,y)
		{
			hidepage= displaypage[y];
			document.getElementById(hidepage).style.display='none';
			
			if (x==0)
				getpage=y*1-1;
			else
				getpage=y*1+1;
				
			var openpage= displaypage[getpage];
			document.getElementById(openpage).style.display='block';
			document.appform.currentpage.value = getpage;
			
			if (getpage != 0)
				document.appform.btnback.disabled = false;
			else
				document.appform.btnback.disabled = true;		
					
			if (getpage==10)
				document.appform.btnnext.disabled = true;
			else
				document.appform.btnnext.disabled = false;
				
		}
		
		function previewfront(form) 
		{
		    var result = window.showModalDialog(\"repappfront3.php?applicantno=$applicantno\", form, 
		        \"dialogWidth:900px; dialogHeight:700px; center:yes; resizable:yes\");
		}
	
		
		function checkadd(x)
		{
			var rem = '';
			
			with (document.appform)
			{
				switch (x)
				{
					case '0':
						if(choice1.value=='' || choice1.value==null)
							if(rem=='')
								rem='First Choice';
							else
								rem=rem + ',First Choice';
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
						if(docexpired.value=='' || docexpired.value==null)
							if(rem=='')
								rem='Date Expired';
							else
								rem=rem + ',Date Expired';
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
						if(licexpired.value=='' || licexpired.value==null)
							if(rem=='')
								rem='Date Expired';
							else
								rem=rem + ',Date Expired';
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
						if(certgrade.value=='' || certgrade.value==null)
							if(rem=='')
								rem='Grade';
							else
								rem=rem + ',Grade';
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
					
					case '9':
						if(refname.value=='' || refname.value==null)
							if(rem=='')
								rem='Name';
							else
								rem=rem + ',Name';
						if(reftelno.value=='' || reftelno.value==null)
							if(rem=='')
								rem='Tel. No.';
							else
								rem=rem + ',Tel. No.';
//						if(refoccupation.value=='' || refoccupation.value==null)
//							if(rem=='')
//								rem='Occupation';
//							else
//								rem=rem + ',Occupation';
//						if(refremarks.value=='' || refremarks.value==null)
//							if(rem=='')
//								rem='Remarks';
//							else
//								rem=rem + ',Remarks';
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
<!-- HEAD END -->

<body style=\"\">
	<div style=\"margin-left:100px;margin-top:30px;width:80%;\">
	<br /><br />
	<img src=\"images/hdr_onlineapp.jpg\" />
	
	<form name=\"appform\" method=\"POST\">
	
	<div style=\"float:right;\">
		<table style=\"width:100%;\" class=\"listrow\" border=\"1\">
			<th>$applicantno</th>
			<th valign=\"center\">Search
				<select name=\"searchby\">
					$searchselect
				</select>
				<input type=\"text\" name=\"searchkey\" value=\"$searchkey\" size=\"5\"  onKeyPress=\"return numbersonly(this);\"/>
				<input type=\"button\" name=\"btnsearch\" value=\"Search\" onclick=\"actiontxt.value='search';submit();\" />
				<input type=\"button\" name=\"btnreset\" value=\"Reset All\" onclick=\"actiontxt.value='reset';currentpage.value=0;submit();\" />
				<input type=\"button\" value=\"Save\" $showsave $disableapproved onclick=\"actiontxt.value='save';checkadd(currentpage.value);\" />
";

				if ($agreedx == 1)
				echo "
				<!--
				<input type=\"button\" value=\"Print Application\" onclick=\"previewfront(this.form);
									actiontxt.value='reset';currentpage.value=0;submit();\" />
				-->		
				
				<input type=\"button\" name=\"btnreport\" 
					onclick=\"actiontxt.value = 'print';appform.submit();
					repappfront3.applicantno.value='$applicantno';
					window.open(repappfront3.action,repappfront3.target,'scrollbars=yes,resizable=yes,channelmode=yes');
					repappfront3.submit();\" value=\"Print Application\">									
				";
									
echo "
				<br />&nbsp;&nbsp;&nbsp;$searcherror
			</th>
<!--
			<th align=\"right\">
				<input type=\"button\" name=\"btnback\" value=\"<< Back\" $disablebtnback style=\"border:2px solid gray;background-color:Red;color:Yellow;font-weight:Bold;\" onclick=\"changepage(0,currentpage.value)\" />
				<input type=\"button\" name=\"btnnext\" value=\"Next >>\" $disablebtnnext style=\"border:2px solid gray;background-color:Red;color:Yellow;font-weight:Bold;\" onclick=\"changepage(1,currentpage.value)\" />
			</th>
-->
		</table>
	</div>
		
	<br />
	<br />

";

echo "
	<div id=\"personal\" style=\"border:1px solid Navy;display:none;$div0\">
		<center>
		<span class=\"sectiontitle\">PERSONAL INFORMATION</span>
		</center>
		<br />
		<table class=\"listrow\" width=\"100%\">
			<tr>
				<th valign=\"top\">Position Applied For</th>
				<td><b>1st Choice *</b>  <br />
					<select name=\"choice1\">
						$personalselect1
					</select>
				</td>
				<th>2nd Choice <br />
					<select name=\"choice2\">
						$personalselect2
					</select>
				</th>
			</tr>
		</table>
		<br />
		<center>
			<table class=\"listrow\" width=\"80%\">
				<tr>
					<td><b>Family Name *</b><br />
						<input type=\"text\" name=\"fname\" value=\"$fname\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"30\" />
					</td>
					<td><b>Given Name *</b><br />
						<input type=\"text\" name=\"gname\" value=\"$gname\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"30\" />
					</td>
					<td><b>Middle Name *</b><br />
						<input type=\"text\" name=\"mname\" value=\"$mname\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"30\" />
					</td>
				</tr>
			</table>
		</center>
		<br />
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
						<td align=\"left\"><select name=\"crewprovince\" onchange=\"appform.submit();\">
								$personalselect5
							</select>
						</td>
					</tr>
					<tr>
						<th align=\"left\">Town/City</th>
						<td align=\"left\"><select name=\"crewcity\" onchange=\"appform.submit();\">
								$personalselect6
							</select>
						</td>
					</tr>
					<tr>
						<th align=\"left\">Barangay</th>
						<td align=\"left\"><select name=\"crewbarangay\" onchange=\"appform.submit();\">
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
						<td align=\"left\"><select name=\"crewprovprovince\" onchange=\"appform.submit();\">
								$personalselect8
							</select>
						</td>
					</tr>
					<tr>
						<th align=\"left\">Town/City</th>
						<td align=\"left\"><select name=\"crewprovcity\" onchange=\"appform.submit();\">
								$personalselect9
							</select>
						</td>
					</tr>
					<tr>
						<th align=\"left\">Barangay</th>
						<td align=\"left\"><select name=\"crewprovbarangay\" onchange=\"appform.submit();\">
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
<!--					<tr>
						<th align=\"left\">SSS No.</th>
						<th align=\"left\"><input type=\"text\" name=\"crewsss\" value=\"$crewsss\" size=\"20\" /></th>
					</tr>
					<tr>
						<th align=\"left\">TIN No.</th>
						<th align=\"left\"><input type=\"text\" name=\"crewtin\" value=\"$crewtin\" size=\"20\" /></th>
					</tr>
-->
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
";
		if ($remarks1 != "")
echo "		<center><span class=\"error\">ERROR:<br />$remarks1</span></center>	";

echo "		
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
					<td align=\"left\"><select name=\"familyprovince\" onchange=\"appform.submit();\">
							$familyselect7
						</select>
					</td>
				</tr>
				<tr>
					<th align=\"left\">Town/City</th>
					<td align=\"left\"><select name=\"familycity\" onchange=\"appform.submit();\">
							$familyselect8
						</select>
					</td>
				</tr>
				<tr>
					<th align=\"left\">Barangay</th>
					<td align=\"left\"><select name=\"familybarangay\" onchange=\"appform.submit();\">
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
					<tr>
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
		
		<div style=\"width:100%;overflow:auto;height:100px;background-color:#DCDCDC;\">
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
	
	<div id=\"documents\" style=\"border:1px solid Navy;display:none;$div3\">
		
		<span class=\"sectiontitle\">DOCUMENTS</span>
";
		if ($remarks3 != "")
echo "		<center><span class=\"error\">ERROR:<br />$remarks3</span></center>	";

echo "
		<br /><br />
		
		<table width=\"90%\" class=\"listrow\">
			<tr>
				<th align=\"left\"><u>Document Type</u></th>
				<th align=\"left\"><u>Document No.</u></th>
				<th align=\"left\"><u>Date Issued</u><br />(mm/dd/yyyy)</th>
				<th align=\"left\"><u>Date Expired</u><br />(mm/dd/yyyy)</th>
			</tr>
			<tr>
				<td align=\"left\"><b>Philippine Passport *</b></td>
				<th align=\"left\"><input type=\"text\" name=\"passportno\" value=\"$passportno\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"20\" /></th>
				<th align=\"left\">
					<input type=\"text\" name=\"passportissued\" value=\"$passportissued\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(passportissued, passportissued, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
				<th align=\"left\">
					<input type=\"text\" name=\"passportexpired\" value=\"$passportexpired\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(passportexpired, passportexpired, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
			</tr>
			<tr>
				<td align=\"left\"><b>Philippine Seaman Book *</b></td>
				<th align=\"left\"><input type=\"text\" name=\"sbookno\" value=\"$sbookno\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"20\" /></th>
				<th align=\"left\">
					<input type=\"text\" name=\"sbookissued\" value=\"$sbookissued\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(sbookissued, sbookissued, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
				<th align=\"left\">
					<input type=\"text\" name=\"sbookexpired\" value=\"$sbookexpired\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(sbookexpired, sbookexpired, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
			</tr>
<!--
			<tr>
				<th align=\"left\">SRC</th>
				<th align=\"left\"><input type=\"text\" name=\"srcno\" value=\"$srcno\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"20\" /></th>
				<th align=\"left\">
					<input type=\"text\" name=\"srcissued\" value=\"$srcissued\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(srcissued, srcissued, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
				<th align=\"left\">
					<input type=\"text\" name=\"srcexpired\" value=\"$srcexpired\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(srcexpired, srcexpired, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
			</tr>
-->
			<tr>
				<td align=\"left\"><b>U.S. VISA *</b></td>
				<th align=\"left\"><input type=\"text\" name=\"usvisano\" value=\"$usvisano\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"20\" /></th>
				<th align=\"left\">
					<input type=\"text\" name=\"usvisaissued\" value=\"$usvisaissued\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(usvisaissued, usvisaissued, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
				<th align=\"left\">
					<input type=\"text\" name=\"usvisaexpired\" value=\"$usvisaexpired\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(usvisaexpired, usvisaexpired, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
			</tr>
			<tr>
				<th colspan=\"3\">&nbsp;</th>
				<th align=\"left\"><input type=\"button\" value=\"Add to List...\" $disableapproved onclick=\"actiontxt.value='addlist';submit();\" /></th>
			</tr>
		</table>
		<br />
";

	if ($actiontxt == "edit")
	{
	echo "
		<hr>
		<br />
		<center>
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
<!--
			<tr>
				<td align=\"left\">&nbsp;</td>
				<td align=\"left\"><input type=\"button\" value=\"Add to list...\" $disableapproved onclick=\"actiontxt.value='addlist';checkadd(currentpage.value);\" /></td>
			</tr>
-->
			<tr>
				<td align=\"left\">&nbsp;</td>
				<td align=\"left\">
					<input type=\"button\" value=\"Save Entry\" onclick=\"actiontxt.value='editsave';editid.value='$editid';checkadd(currentpage.value);\" />
					<input type=\"button\" value=\"Cancel\" onclick=\"actiontxt.value='cancel';submit();\" />
				</td>
			</tr>
		</table>
		</center>
		<br />
";
	}
echo "	
		<div style=\"width:100%;overflow:auto;height:120px;background-color:#DCDCDC;\">
			<table width=\"100%\" class=\"listcol\">
				<tr>
					<th>DOCUMENT</th>
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
					if ($rowdoclist["DATEISSUED"] != "")
						$document3 = date('m/d/Y',strtotime($rowdoclist["DATEISSUED"]));
					else 
						$document3 = "";
						
					if ($rowdoclist["DATEEXPIRED"] != "")
						$document4 = date('m/d/Y',strtotime($rowdoclist["DATEEXPIRED"]));
					else 
						$document4 = "";
						
					$document5 = $rowdoclist["IDNO"];
						
echo "
					<tr>
						<td align=\"left\">$document1</td>
						<td align=\"left\">$document2</td>
						<td align=\"left\">$document3</td>
						<td align=\"left\">$document4</td>
						<td align=\"left\">
							<input type=\"button\" value=\"EDIT\" $editmode $disableapproved style=\"color:Red;font-weight:Bold;font-size:0.9em;\" title=\"Edit\"
									onclick=\"actiontxt.value='edit';editid.value='$document5';submit();\" />
							<input type=\"button\" value=\"DEL\" $editmode $disableapproved style=\"color:Red;font-weight:Bold;font-size:0.9em;\" 
									onclick=\"actiontxt.value='delete';deleteid.value='$document5';submit();\" />
						</td>						
					</tr>
";
				}

				
				
echo "			
			</table>
		</div>
	</div>
	
	<div id=\"license\" style=\"border:1px solid Navy;display:none;$div4\">
	
		<span class=\"sectiontitle\">LICENSES</span>
";
		if ($remarks4 != "")
echo "		<center><span class=\"error\">ERROR:<br />$remarks4</span></center>	 ";

echo "
		<br /><br />
		
		<table width=\"90%\" class=\"listrow\">
			<tr>
				<th align=\"left\"><u>Document Type</u></th>
				<th align=\"left\"><u>Document No.</u></th>
				<th align=\"left\"><u>Date Issued</u><br />(mm/dd/yyyy)</th>
				<th align=\"left\"><u>Date Expired</u><br />(mm/dd/yyyy)</th>
			</tr>
			<tr>
				<td align=\"left\"><b>Philippine License *</b></td>
				<th align=\"left\"><input type=\"text\" name=\"licno1\" value=\"\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"20\" /></th>
				<th align=\"left\">
					<input type=\"text\" name=\"licissued1\" value=\"\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(licissued1, licissued1, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
				<th align=\"left\">
					<input type=\"text\" name=\"licexpired1\" value=\"\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(licexpired1, licexpired1, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
			</tr>
			<tr>
				<td align=\"left\"><b>Certificate of Competency (COC) for Officers *</b></td>
				<th align=\"left\"><input type=\"text\" name=\"licno2\" value=\"\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"20\" /></th>
				<th align=\"left\">
					<input type=\"text\" name=\"licissued2\" value=\"\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(licissued2, licissued2, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
				<th align=\"left\">
					<input type=\"text\" name=\"licexpired2\" value=\"\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(licexpired2, licexpired2, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
			</tr>
			<tr>
				<td align=\"left\"><b>Endorsement of Certificate (EOC) for Officers *</b></td>
				<th align=\"left\"><input type=\"text\" name=\"licno3\" value=\"\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"20\" /></th>
				<th align=\"left\">
					<input type=\"text\" name=\"licissued3\" value=\"\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(licissued3, licissued3, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
				<th align=\"left\">
					<input type=\"text\" name=\"licexpired3\" value=\"\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(licexpired3, licexpired3, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
			</tr>
			<tr>
				<td align=\"left\"><b>Certificate of Competency (COC) for Ratings *</b></td>
				<th align=\"left\"><input type=\"text\" name=\"licno4\" value=\"\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"20\" /></th>
				<th align=\"left\">
					<input type=\"text\" name=\"licissued4\" value=\"\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(licissued4, licissued4, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
				<th align=\"left\">
					<input type=\"text\" name=\"licexpired4\" value=\"\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(licexpired4, licexpired4, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
			</tr>
			<tr>
				<td align=\"left\"><b>Panama License *</b></td>
				<th align=\"left\"><input type=\"text\" name=\"licno5\" value=\"\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"20\" /></th>
				<th align=\"left\">
					<input type=\"text\" name=\"licissued5\" value=\"\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(licissued5, licissued5, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
				<th align=\"left\">
					<input type=\"text\" name=\"licexpired5\" value=\"\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(licexpired5, licexpired5, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
			</tr>
			<tr>
				<td align=\"left\"><b>Panama Seaman Book *</b></td>
				<th align=\"left\"><input type=\"text\" name=\"licno6\" value=\"\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"20\" /></th>
				<th align=\"left\">
					<input type=\"text\" name=\"licissued6\" value=\"\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(licissued6, licissued6, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
				<th align=\"left\">
					<input type=\"text\" name=\"licexpired6\" value=\"\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(licexpired6, licexpired6, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
			</tr>
			<tr>
				<td align=\"left\"><b>Panama GMDSS License *</b></td>
				<th align=\"left\"><input type=\"text\" name=\"licno7\" value=\"\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"20\" /></th>
				<th align=\"left\">
					<input type=\"text\" name=\"licissued7\" value=\"\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(licissued7, licissued7, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
				<th align=\"left\">
					<input type=\"text\" name=\"licexpired7\" value=\"\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(licexpired7, licexpired7, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
			</tr>
			<tr>
				<td align=\"left\"><b>Panama GMDSS Seaman Book *</b></td>
				<th align=\"left\"><input type=\"text\" name=\"licno8\" value=\"\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"20\" /></th>
				<th align=\"left\">
					<input type=\"text\" name=\"licissued8\" value=\"\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(licissued8, licissued8, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
				<th align=\"left\">
					<input type=\"text\" name=\"licexpired8\" value=\"\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(licexpired8, licexpired8, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
			</tr>
			<tr>
				<td align=\"left\"><b>General Operator Certificate(GOC) *</b></td>
				<th align=\"left\"><input type=\"text\" name=\"licno9\" value=\"\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"20\" /></th>
				<th align=\"left\">
					<input type=\"text\" name=\"licissued9\" value=\"\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(licissued9, licissued9, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
				<th align=\"left\">
					<input type=\"text\" name=\"licexpired9\" value=\"\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(licexpired9, licexpired9, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
			</tr>
			<tr>
				<td align=\"left\"><b>Hongkong License *</b></td>
				<th align=\"left\"><input type=\"text\" name=\"licno10\" value=\"\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"20\" /></th>
				<th align=\"left\">
					<input type=\"text\" name=\"licissued10\" value=\"\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(licissued10, licissued10, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
				<th align=\"left\">
					<input type=\"text\" name=\"licexpired10\" value=\"\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(licexpired10, licexpired10, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
			</tr>
			<tr>
				<td align=\"left\"><b>Japanese License *</b></td>
				<th align=\"left\"><input type=\"text\" name=\"licno11\" value=\"\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"20\" /></th>
				<th align=\"left\">
					<input type=\"text\" name=\"licissued11\" value=\"\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(licissued11, licissued11, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
				<th align=\"left\">
					<input type=\"text\" name=\"licexpired11\" value=\"\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(licexpired11, licexpired11, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
			</tr>
			<tr>
				<td align=\"left\"><b>Japanese GOC *</b></td>
				<th align=\"left\"><input type=\"text\" name=\"licno12\" value=\"\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"20\" /></th>
				<th align=\"left\">
					<input type=\"text\" name=\"licissued12\" value=\"\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(licissued12, licissued12, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
				<th align=\"left\">
					<input type=\"text\" name=\"licexpired12\" value=\"\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(licexpired12, licexpired12, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
			</tr>
			<tr>
				<td align=\"left\"><b>Singapore GOC *</b></td>
				<th align=\"left\"><input type=\"text\" name=\"licno13\" value=\"\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"20\" /></th>
				<th align=\"left\">
					<input type=\"text\" name=\"licissued13\" value=\"\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(licissued13, licissued13, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
				<th align=\"left\">
					<input type=\"text\" name=\"licexpired13\" value=\"\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(licexpired13, licexpired13, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
			</tr>
			<tr>
				<td align=\"left\"><b>Singapore COE *</b></td>
				<th align=\"left\"><input type=\"text\" name=\"licno14\" value=\"\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"20\" /></th>
				<th align=\"left\">
					<input type=\"text\" name=\"licissued14\" value=\"\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(licissued14, licissued14, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
				<th align=\"left\">
					<input type=\"text\" name=\"licexpired14\" value=\"\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(licexpired14, licexpired14, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
			</tr>
			<tr>
				<th colspan=\"3\">&nbsp;</th>
				<th align=\"left\"><input type=\"button\" value=\"Add to List...\" $disableapproved onclick=\"actiontxt.value='addlist';submit();\" /></th>
			</tr>			
		</table>
		
		<br />
";
	if ($actiontxt == "edit")
	{
	echo "	
		<hr />
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
<!--
			<tr>
				<td align=\"left\">&nbsp;</td>
				<td align=\"left\"><input type=\"button\" value=\"Add to list...\" $disableapproved onclick=\"actiontxt.value='addlist';checkadd(currentpage.value);\" /></td>
			</tr>
-->
			<tr>
				<td align=\"left\">&nbsp;</td>
				<td align=\"left\">
					<input type=\"button\" value=\"Save Entry\" onclick=\"actiontxt.value='editsave';editid.value='$editid';checkadd(currentpage.value);\" />
					<input type=\"button\" value=\"Cancel\" onclick=\"actiontxt.value='cancel';submit();\" />
				</td>
			</tr>
		</table>
		<br />
";
	}
echo "	
		<div style=\"width:100%;overflow:auto;height:120px;background-color:#DCDCDC;\">
			<table width=\"100%\" class=\"listcol\">
				<tr>
					<th>LICENSE</th>
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
						$license3 = "";
						
					if ($rowliclist["DATEEXPIRED"] != "")
						$license4 = date('m/d/Y',strtotime($rowliclist["DATEEXPIRED"]));
					else 
						$license4 = "";
						
					$license5 = $rowliclist["IDNO"];
						
echo "
					<tr>
						<td align=\"left\">$license1</td>
						<td align=\"left\">$license2</td>
						<td align=\"left\">$license3</td>
						<td align=\"left\">$license4</td>
						<td align=\"left\">
							<input type=\"button\" value=\"EDIT\" $editmode $disableapproved style=\"color:Red;font-weight:Bold;font-size:0.9em;\" title=\"Edit\"
									onclick=\"actiontxt.value='edit';editid.value='$license5';submit();\" />
							<input type=\"button\" value=\"DEL\" $editmode $disableapproved style=\"color:Red;font-weight:Bold;font-size:0.9em;\" 
									onclick=\"actiontxt.value='delete';deleteid.value='$license5';submit();\" />
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
";
		if ($remarks5 != "")
echo "		<center><span class=\"error\">ERROR:<br />$remarks5</span></center>	";
				
echo "
		<br /><br />
		<table width=\"70%\" class=\"listrow\">
			<tr>
				<th align=\"left\"><u>Certificate</u></th>
				<th align=\"left\"><u>Certificate No.</u></th>
				<th align=\"left\"><u>Date Issued</u><br />(mm/dd/yyyy)</th>
			</tr>
			<tr>
				<td align=\"left\"><b>Ship Simulator and Bridge Teamwork(SSBT) *</b></td>
				<th align=\"left\"><input type=\"text\" name=\"certno1\" value=\"$certno1\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"20\" /></th>
				<th align=\"left\">
					<input type=\"text\" name=\"certissued1\" value=\"$certissued1\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(certissued1, certissued1, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
			</tr>
			<tr>
				<td align=\"left\"><b>Advanced Ship Simulator and Bridge Teamwork *</b></td>
				<th align=\"left\"><input type=\"text\" name=\"certno2\" value=\"$certno2\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"20\" /></th>
				<th align=\"left\">
					<input type=\"text\" name=\"certissued2\" value=\"$certissued2\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(certissued2, certissued2, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
			</tr>
			<tr>
				<td align=\"left\"><b>Ship Security Officer(SSO) *</b></td>
				<th align=\"left\"><input type=\"text\" name=\"certno3\" value=\"$certno3\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"20\" /></th>
				<th align=\"left\">
					<input type=\"text\" name=\"certissued3\" value=\"$certissued3\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(certissued3, certissued3, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
			</tr>
			<tr>
				<td align=\"left\"><b>Engine Room Simulator Course(ERS) *</b></td>
				<th align=\"left\"><input type=\"text\" name=\"certno4\" value=\"$certno4\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"20\" /></th>
				<th align=\"left\">
					<input type=\"text\" name=\"certissued4\" value=\"$certissued4\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(certissued4, certissued4, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
			</tr>
			<tr>
				<td align=\"left\"><b>Automatic Identification System(AIS) *</b></td>
				<th align=\"left\"><input type=\"text\" name=\"certno5\" value=\"$certno5\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"20\" /></th>
				<th align=\"left\">
					<input type=\"text\" name=\"certissued5\" value=\"$certissued5\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(certissued5, certissued5, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
			</tr>
			<tr>
				<th colspan=\"2\">&nbsp;</th>
				<th align=\"left\"><input type=\"button\" value=\"Add to List...\" $disableapproved onclick=\"actiontxt.value='addlist';submit();\" /></th>
			</tr>			
		</table>
		<br />		
";
	if ($actiontxt == "edit")
	{
	echo "
		<hr>
		<br />
		
		<table width=\"60%\" class=\"listrow\">
			<tr>
				<th align=\"left\"><b>Certificate Type</b></th>
				<th align=\"left\"><select name=\"certcode\">
						$certselect1
					</select>
				</th>
			</tr>
			<tr>
				<th align=\"left\"><b>Certificate No.</b></th>
				<th align=\"left\"><input type=\"text\" name=\"certno\" value=\"$certno\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"20\" /></th>
			</tr>	
<!--		
			<tr>
				<th align=\"left\">Grade</th>
				<th align=\"left\"><input type=\"text\" name=\"certgrade\" value=\"$certgrade\" size=\"10\" onKeyPress=\"return numbersonly(this);\" /></th>
			</tr>			
-->
			<tr>
				<th align=\"left\"><b>Date Issued</b></th>
				<th align=\"left\">
					<input type=\"text\" name=\"certissued\" value=\"$certissued\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(certissued, certissued, 'mm/dd/yyyy', 0, 0);return false;\">
					&nbsp;&nbsp;&nbsp;(mm/dd/yyyy)
				</th>
			</tr>
<!--
			<tr>
				<td align=\"left\">&nbsp;</td>
				<td align=\"left\"><input type=\"button\" value=\"Add to list...\" $disableapproved onclick=\"actiontxt.value='addlist';checkadd(currentpage.value);\" /></td>
			</tr>
-->
			<tr>
				<td align=\"left\">&nbsp;</td>
				<td align=\"left\">
					<input type=\"button\" value=\"Save Entry\" onclick=\"actiontxt.value='editsave';editid.value='$editid';checkadd(currentpage.value);\" />
					<input type=\"button\" value=\"Cancel\" onclick=\"actiontxt.value='cancel';submit();\" />
				</td>
			</tr>
	
		</table>
		<br />
";
	}
echo "
		<div style=\"width:100%;overflow:auto;height:120px;background-color:#DCDCDC;\">
			<table width=\"100%\" class=\"listcol\">
				<tr>
					<th>CERTIFICATE</th>
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
						
echo "
					<tr>
						<td align=\"left\">$certificate1</td>
						<td align=\"left\">$certificate2</td>
						<td align=\"left\">$certificate4</td>
						<td align=\"left\">
							<input type=\"button\" value=\"EDIT\" $editmode $disableapproved style=\"color:Red;font-weight:Bold;font-size:0.9em;\" title=\"Edit\"
									onclick=\"actiontxt.value='edit';editid.value='$certificate5';submit();\" />
							<input type=\"button\" value=\"DEL\" $editmode $disableapproved style=\"color:Red;font-weight:Bold;font-size:0.9em;\" 
									onclick=\"actiontxt.value='delete';deleteid.value='$certificate5';submit();\" />
						</td>
					</tr>
";
				}

echo "
			</table>
		</div>	
	</div>
	
	<div id=\"experience\" style=\"border:1px solid Navy;display:none;$div6\">
	
		<span class=\"sectiontitle\">EXPERIENCES</span>
";
		if ($remarks6 != "")
echo "		<center><span class=\"error\">ERROR:<br />$remarks6</span></center>	";
		
echo "
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
					<tr>
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
			<tr>
				<th align=\"left\">Basic Salary</th>
				<th align=\"left\"><input type=\"text\" name=\"empsalary\" value=\"$empsalary\"  size=\"10\" onKeyPress=\"return numbersonly(this);\" /></th>
			</tr>
			<tr>
				<th align=\"left\">Overtime Pay</th>
				<th align=\"left\"><input type=\"text\" name=\"empovertime\" value=\"$empovertime\"  size=\"10\" onKeyPress=\"return numbersonly(this);\" /></th>
			</tr>			
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
					<tr>
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
	
	<div id=\"questions\" style=\"border:1px solid Navy;display:none;$div8\">		
		
		<span class=\"sectiontitle\">OTHER QUESTIONS</span>
		<br /><br />
		<table width=\"100%\" class=\"listrow\">
			<tr>
				<th align=\"left\">Are you a member of any local or foreign seaman's union? if yes, state union name</th>
				<th align=\"left\"><select name=\"qanswer1a\" >
						$qselect1
					</select>
					&nbsp;&nbsp;
					<input type=\"text\" name=\"qanswer1b\" value=\"$qanswer1b\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\"  size=\"30\" maxlength=\"30\" />
				</th>
			</tr>
			<tr>
				<th align=\"left\">Have you ever been involved in any foreign union-related case?</th>
				<th align=\"left\"><select name=\"qanswer2a\" >
						$qselect2
					</select>
					&nbsp;&nbsp;
					<input type=\"text\" name=\"qanswer2b\" value=\"$qanswer2b\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\"  size=\"30\" maxlength=\"30\" />
				</th>
			</tr>
			<tr>
				<th align=\"left\">Have you ever been dismissed for a cause, If yes, what offense?</th>
				<th align=\"left\"><select name=\"qanswer3a\" >
						$qselect3
					</select>
					&nbsp;&nbsp;
					<input type=\"text\" name=\"qanswer3b\" value=\"$qanswer3b\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\"  size=\"30\" maxlength=\"30\" />
				</th>
			</tr>										
			<tr>
				<th align=\"left\">Do you have pending case with POEA, what offense?</th>
				<th align=\"left\"><select name=\"qanswer4a\" >
						$qselect4
					</select>
					&nbsp;&nbsp;
					<input type=\"text\" name=\"qanswer4b\" value=\"$qanswer4b\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\"  size=\"30\" maxlength=\"30\" />
				</th>
			</tr>
			<tr>
				<th align=\"left\">Have you ever been sued in any court? what offense?</th>
				<th align=\"left\"><select name=\"qanswer5a\" >
						$qselect5
					</select>
					&nbsp;&nbsp;
					<input type=\"text\" name=\"qanswer5b\" value=\"$qanswer5b\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"30\" maxlength=\"30\" />
				</th>
			</tr>
			<tr>
				<th align=\"left\">Desired Salary</th>
				<th align=\"left\"><input type=\"text\" name=\"qdesiredsalary\" value=\"$qdesiredsalary\" onKeyPress=\"return numbersonly(this);\" /></th>
			</tr>
<!--
			<tr>
				<th align=\"left\">Desired Overtime Rate</th>
				<th align=\"left\"><input type=\"text\" name=\"qdesiredot\" value=\"$qdesiredot\" onKeyPress=\"return numbersonly(this);\" /></th>
			</tr>	
-->
			<tr>
				<th align=\"left\">&nbsp;</th>
				<th align=\"left\">&nbsp;</th>
			</tr>				
		</table>	
	</div>
	
	<div id=\"reference\" style=\"border:1px solid Navy;display:none;$div9\">

		<span class=\"sectiontitle\">REFERENCES</span>
";
		if ($remarks9 != "")
echo "		<center><span class=\"error\">ERROR:<br />$remarks9</span></center>	";
		
echo "	
		<br /><br />
		<table width=\"60%\" class=\"listrow\">
			<tr>
				<td align=\"left\"><b>Name *</b></td>
				<td align=\"left\"><input type=\"text\" name=\"refname\" value=\"$refname\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"30\" /></td>
			</tr>
			<tr>
				<td align=\"left\"><b>Tel. No. *</b></td>
				<td align=\"left\"><input type=\"text\" name=\"reftelno\" value=\"$reftelno\" size=\"30\" /></td>
			</tr>					
			<tr>
				<th align=\"left\">Occupation</th>
				<th align=\"left\"><input type=\"text\" name=\"refoccupation\" value=\"$refoccupation\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"30\" /></th>
			</tr>
			<tr>
				<th align=\"left\" valign=\"top\">Remarks</th>
				<th align=\"left\"><textarea name=\"refremarks\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" rows=\"3\" cols=\"20\">$refremarks</textarea></th>
			</tr>
";
			if ($actiontxt != "edit")
echo "
			<tr>
				<th align=\"left\">&nbsp;</th>
				<th align=\"left\"><input type=\"button\" value=\"Add to list...\" $disableapproved onclick=\"actiontxt.value='addlist';checkadd(currentpage.value);\" /></th>
			</tr>
";
			else 
echo "
			<tr>
				<th align=\"left\">&nbsp;</th>
				<th align=\"left\">
					<input type=\"button\" value=\"Save Entry\" onclick=\"actiontxt.value='editsave';editid.value='$editid';checkadd(currentpage.value);\" />
					<input type=\"button\" value=\"Cancel\" onclick=\"actiontxt.value='cancel';submit();\" />
				</th>
			</tr>
";
			
echo "			
		</table>
		<br />
		
		<div style=\"width:100%;overflow:auto;height:120px;background-color:#DCDCDC;\">
			<table width=\"100%\" class=\"listcol\">
				<tr>
					<th>NAME</th>
					<th>OCCUPATION</th>
					<th>TELNO</th>
					<th>REMARKS</th>
					<th>&nbsp;</th>
				</tr>
";				
				while ($rowreferencelist = mysql_fetch_array($qryreferencelist))
				{
					$reference1 = $rowreferencelist["NAME"];
					$reference2 = $rowreferencelist["OCCUPATION"];
					$reference3 = $rowreferencelist["TELNO"];
					$reference4 = $rowreferencelist["REMARKS"];
					$reference5 = $rowreferencelist["IDNO"];
						
echo "
					<tr>
						<td align=\"left\">$reference1</td>
						<td align=\"left\">$reference2</td>
						<td align=\"left\">$reference3</td>
						<td align=\"left\">$reference4</td>
						<td align=\"left\">
							<input type=\"button\" value=\"EDIT\" $editmode $disableapproved style=\"color:Red;font-weight:Bold;font-size:0.9em;\" title=\"Edit\"
									onclick=\"actiontxt.value='edit';editid.value='$reference5';submit();\" />
							<input type=\"button\" value=\"DEL\" $editmode $disableapproved style=\"color:Red;font-weight:Bold;font-size:0.9em;\" 
									onclick=\"actiontxt.value='delete';deleteid.value='$reference5';submit();\" />
						</td>
					</tr>
";
				}
				
echo "			
			</table>
		</div>
		
		<br />
		<table width=\"100%\" class=\"listrow\">
			<tr>
				<th align=\"left\">WHO RECOMMENDED YOU TO THIS COMPANY?</th>
				<th align=\"left\"><input type=\"text\" name=\"recommendedby\" value=\"$recommendedby\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"30\" /></th>
			</tr>
		</table>		
	</div>
	
	<div id=\"agreement\" style=\"border:1px solid Navy;display:none;$div10\">	

		<span class=\"sectiontitle\">AGREEMENT</span>	
		
		<br /><br /><br />
		<center>
			<span style=\"font-size:0.75em;font-weight:Bold;\">
			I HEREBY CERTIFY THAT ALL INFORMATION PROVIDED BY THE UNDERSIGNED ARE TRUE AND CORRECT AND ANY MISREPRESENTATION OR INCORRECT DATA SUPPLIED SHALL  BE JUST CAUSE FOR TERMINATION OF MY EMPLOYMENT.
			</span>
			<br /><br /><br />
";
	if ($agreedx == 0)
		echo "
			<input type=\"button\" value=\"Cancel\" onclick=\"actiontxt.value='reset';submit();\" />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;		
			<input type=\"button\" value=\"I Agree\" onclick=\"actiontxt.value='agreed';submit();\" />
			";
	else 
		echo "
		
			<input type=\"button\" name=\"btnreport\" 
				onclick=\"actiontxt.value = 'print';appform.submit();
				repappfront3.applicantno.value='$applicantno';
				window.open(repappfront3.action,repappfront3.target,'scrollbars=yes,resizable=yes,channelmode=yes');
				repappfront3.submit();\" value=\"Print Application\">		

			<br /><br />
		";
		
echo "
		</center>	
	</div>
	<br /><br />
";



echo "
	<hr>
	
	<table width=\"100%\">
		<th align=\"right\">
			<input type=\"button\" name=\"btnback\" value=\"<< Back\" $disablebtnback style=\"border:2px solid gray;background-color:Red;color:Yellow;font-weight:Bold;\" onclick=\"changepage(0,currentpage.value)\" />
			<input type=\"button\" name=\"btnnext\" value=\"Next >>\" $disablebtnnext style=\"border:2px solid gray;background-color:Red;color:Yellow;font-weight:Bold;\" onclick=\"changepage(1,currentpage.value)\" />
		</th>
	</table>
	
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"deleteid\" />
	<input type=\"hidden\" name=\"editid\" />
	<input type=\"hidden\" name=\"currentpage\" value=\"$currentpage\" />
	<input type=\"hidden\" name=\"applicantno\" value=\"$applicantno\" />
	</form>
	
	<form name=\"repappfront3\" action=\"repappfront3.php\" target=\"repappfront3\" method=\"GET\">
		<input type=\"hidden\" name=\"applicantno\">
	</form>

  </div>
</body>
</html>
";