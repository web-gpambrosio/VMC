<?php

//include('connectdb.php');
$kups = "gino";
include('veritas/connectdb.php');

$currentdate = date('Y-m-d H:i:s');
$duplicate = 0;

$getservername=$_SERVER["SERVER_NAME"];
$getserveraddr=$_SERVER['SERVER_ADDR'];
$getremoteaddr=$_SERVER['REMOTE_ADDR'];
//$local = $_SERVER["HTTP_X_FORWARDED_FOR"];

$local=$_GET["application"];


function documentadd($appno,$docucode,$validity,$higherlic,$licrankcode)
{
	if($appno != "" && $validity != "")
	{
		$qryverifydocs = mysql_query("SELECT APPLICANTNO FROM applicantdocs WHERE APPLICANTNO=$appno AND DOCCODE='$docucode'")	or die(mysql_error());
		
		if (!empty($validity))
			$validityraw = "'" . date('Y-m-d',strtotime($validity)) ."'";
		else 
			$validityraw = "NULL";
			
		if (!empty($licrankcode))
			$licrankcode = "'" . $licrankcode . "'";
		else 
			$licrankcode = "NULL";
		
		if (mysql_num_rows($qryverifydocs) == 0)
		{
			
			$qrydocinsert = mysql_query("INSERT INTO applicantdocs(APPLICANTNO,DOCCODE,VALIDITY,HIGHERLIC,LICRANKCODE) 
										VALUES($appno,'$docucode',$validityraw,$higherlic,$licrankcode)") or die(mysql_error());
		}
		else 
		{
			
			$qrydocupdate = mysql_query("UPDATE applicantdocs SET VALIDITY=$validityraw,
																HIGHERLIC=$higherlic,
																LICRANKCODE=$licrankcode
										WHERE APPLICANTNO=$appno AND DOCCODE='$docucode'
										") or die(mysql_error());
		}
	}
}

function documentdelete($appno,$docucode)
{
	if($appno != "")
	{
		$qryverifydocs = mysql_query("SELECT APPLICANTNO FROM applicantdocs WHERE APPLICANTNO=$appno AND DOCCODE='$docucode'")	or die(mysql_error());
		
		if (mysql_num_rows($qryverifydocs) > 0)
		{
			$qrydocinsert = mysql_query("DELETE FROM applicantdocs WHERE APPLICANTNO=$appno AND DOCCODE='$docucode'") or die(mysql_error());
		}
	}

}


if (isset($_POST["applicantno"]))
	$applicantno = $_POST["applicantno"];
	
if (empty($applicantno))
	$disablebtnnext = "disabled=\"disabled\"";
	
if (isset($_POST["actiontxt"]))
	$actiontxt = $_POST["actiontxt"];
	
if (isset($_POST["currentpage"]))
	$currentpage = $_POST["currentpage"];
	
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

if (isset($_POST["choice1"]))
	$choice1 = $_POST["choice1"];
	
if (isset($_POST["choice2"]))
	$choice2 = $_POST["choice2"];

if (isset($_POST["fname"]))
	$fname = $_POST["fname"];
	
if (isset($_POST["gname"]))
	$gname = $_POST["gname"];

if (isset($_POST["mname"]))
	$mname = $_POST["mname"];
	
if (isset($_POST["crewaddress"]))
	$crewaddress = $_POST["crewaddress"];

if (isset($_POST["crewbarangay"]))
	$crewbarangay = $_POST["crewbarangay"];
	
if (isset($_POST["crewcity"]))
	$crewcity = $_POST["crewcity"];

if (isset($_POST["crewprovince"]))
	$crewprovince = $_POST["crewprovince"];

if (isset($_POST["crewzipcode"]))
	$crewzipcode = $_POST["crewzipcode"];
	
if (isset($_POST["crewtelno"]))
	$crewtelno = $_POST["crewtelno"];

	
if (isset($_POST["crewprovaddress"]))
	$crewprovaddress = $_POST["crewprovaddress"];

if (isset($_POST["crewprovbarangay"]))
	$crewprovbarangay = $_POST["crewprovbarangay"];
	
if (isset($_POST["crewprovcity"]))
	$crewprovcity = $_POST["crewprovcity"];

if (isset($_POST["crewprovprovince"]))
	$crewprovprovince = $_POST["crewprovprovince"];

if (isset($_POST["crewprovzipcode"]))
	$crewprovzipcode = $_POST["crewprovzipcode"];
	
if (isset($_POST["crewprovtelno"]))
	$crewprovtelno = $_POST["crewprovtelno"];
	
if (isset($_POST["crewbdate"]))
	$crewbdate = $_POST["crewbdate"];
	
if (isset($_POST["crewbplace"]))
	$crewbplace = $_POST["crewbplace"];	
	
if (isset($_POST["crewcelphone1"]))
	$crewcelphone1 = $_POST["crewcelphone1"];
	
if (isset($_POST["crewcelphone2"]))
	$crewcelphone2 = $_POST["crewcelphone2"];
	
if (isset($_POST["crewcelphone3"]))
	$crewcelphone3 = $_POST["crewcelphone3"];

if (isset($_POST["crewgender"]))
	$crewgender = $_POST["crewgender"];
else 
	$crewgender = "M";
	
if (isset($_POST["crewcivilstatus"]))
	$crewcivilstatus = $_POST["crewcivilstatus"];

if (isset($_POST["crewnationality"]))
	$crewnationality = $_POST["crewnationality"];

if (isset($_POST["crewreligion"]))
	$crewreligion = $_POST["crewreligion"];

if (isset($_POST["crewsss"]))
	$crewsss = $_POST["crewsss"];

if (isset($_POST["crewtin"]))
	$crewtin = $_POST["crewtin"];

if (isset($_POST["crewweight"]))
	$crewweight = $_POST["crewweight"];

if (isset($_POST["crewheight"]))
	$crewheight = $_POST["crewheight"];

if (isset($_POST["crewshoesize"]))
	$crewshoesize = $_POST["crewshoesize"];

if (isset($_POST["crewemail"]))
	$crewemail = $_POST["crewemail"];

//EDUCATION

if (isset($_POST["educschoolid"]))
	$educschoolid = $_POST["educschoolid"];
	
if (isset($_POST["educschoolothers"]))
	$educschoolothers = $_POST["educschoolothers"];
	
if (isset($_POST["educcourseid"]))
	$educcourseid = $_POST["educcourseid"];
	
if (isset($_POST["educcourseothers"]))
	$educcourseothers = $_POST["educcourseothers"];
	
if (isset($_POST["educaddress"]))
	$educaddress = $_POST["educaddress"];
	
if (isset($_POST["educcontactno"]))
	$educcontactno = $_POST["educcontactno"];
	
if (isset($_POST["educcontactperson"]))
	$educcontactperson = $_POST["educcontactperson"];
	
if (isset($_POST["educdategrad"]))
	$educdategrad = $_POST["educdategrad"];
	
if (isset($_POST["educlevel"]))
	$educlevel = $_POST["educlevel"];
	
if (isset($_POST["educhonors"]))
	$educhonors = $_POST["educhonors"];
	
//DOCUMENTS

if ($actiontxt == "save" || $actiontxt == "agreed")
{
	if (isset($_POST["validdoc1"]) && !empty($_POST["validdoc1"]))
	{
		$validdoc1 = $_POST["validdoc1"];
		documentadd($applicantno,"41",$validdoc1,0,"");
	}
	else 
		documentdelete($applicantno,"41");
		
	if (isset($_POST["validdoc2"]) && !empty($_POST["validdoc2"]))
	{
		$validdoc2 = $_POST["validdoc2"];
		documentadd($applicantno,"F2",$validdoc2,0,"");
	}
	else 
		documentdelete($applicantno,"F2");
		
	if (isset($_POST["validdoc3"]) && !empty($_POST["validdoc3"]))
	{
		$validdoc3 = $_POST["validdoc3"];
		documentadd($applicantno,"42",$validdoc3,0,"");
	}
	else 
		documentdelete($applicantno,"42");
		
	//LICENSE
	
	if (isset($_POST["chkhigherlic"]))
	{
		$chkhigherlic = 1;
		$chkhigherlicstatus = "checked=\"checked\"";
		
		if (isset($_POST["licrankcode"]))
			$licrankcode = $_POST["licrankcode"];
		else 
			$licrankcode = "";
	}
	else 
	{
		$chkhigherlic = 0;
		$chkhigherlicstatus = "";
	}
	
	if (isset($_POST["validlic1"]) && !empty($_POST["validlic1"]))
	{
		$validlic1 = $_POST["validlic1"];
		documentadd($applicantno,"F1",$validlic1,$chkhigherlic,$licrankcode);
	}
	else 
		documentdelete($applicantno,"F1");
	
	if (isset($_POST["validlic2"]) && !empty($_POST["validlic2"]))
	{
		$validlic2 = $_POST["validlic2"];
		documentadd($applicantno,"18",$validlic2,0,"");
	}
	else 
		documentdelete($applicantno,"18");
	
	if (isset($_POST["validlic3"]) && !empty($_POST["validlic3"]))
	{
		$validlic3 = $_POST["validlic3"];
		documentadd($applicantno,"16",$validlic3,0,"");
	}
	else 
		documentdelete($applicantno,"16");
	
	if (isset($_POST["validlic4"]) && !empty($_POST["validlic4"]))
	{
		$validlic4 = $_POST["validlic4"];
		documentadd($applicantno,"C0",$validlic4,0,"");
	}
	else 
		documentdelete($applicantno,"C0");
	
	if (isset($_POST["validlic5"]) && !empty($_POST["validlic5"]))
	{
		$validlic5 = $_POST["validlic5"];
		documentadd($applicantno,"27",$validlic5,0,"");
	}
	else 
		documentdelete($applicantno,"27");
	
	if (isset($_POST["validlic6"]) && !empty($_POST["validlic6"]))
	{
		$validlic6 = $_POST["validlic6"];
		documentadd($applicantno,"P1",$validlic6,0,"");
	}
	else 
		documentdelete($applicantno,"P1");
	
	if (isset($_POST["validlic7"]) && !empty($_POST["validlic7"]))
	{
		$validlic7 = $_POST["validlic7"];
		documentadd($applicantno,"P2",$validlic7,0,"");
	}
	else 
		documentdelete($applicantno,"P2");
	
	if (isset($_POST["validlic8"]) && !empty($_POST["validlic8"]))
	{
		$validlic8 = $_POST["validlic8"];
		documentadd($applicantno,"28",$validlic8,0,"");
	}
	else 
		documentdelete($applicantno,"28");
	
	if (isset($_POST["validlic9"]) && !empty($_POST["validlic9"]))
	{
		$validlic9 = $_POST["validlic9"];
		documentadd($applicantno,"44",$validlic9,0,"");
	}
	else 
		documentdelete($applicantno,"44");
	
	if (isset($_POST["validlic10"]) && !empty($_POST["validlic10"]))
	{
		$validlic10 = $_POST["validlic10"];
		documentadd($applicantno,"H1",$validlic10,0,"");
	}
	else 
		documentdelete($applicantno,"H1");
	
	if (isset($_POST["validlic11"]) && !empty($_POST["validlic11"]))
	{
		$validlic11 = $_POST["validlic11"];
		documentadd($applicantno,"J1",$validlic11,0,"");
	}
	else 
		documentdelete($applicantno,"J1");
	
	if (isset($_POST["validlic12"]) && !empty($_POST["validlic12"]))
	{
		$validlic12 = $_POST["validlic12"];
		documentadd($applicantno,"J3",$validlic12,0,"");
	}
	else 
		documentdelete($applicantno,"J3");
	
	if (isset($_POST["validlic13"]) && !empty($_POST["validlic13"]))
	{
		$validlic13 = $_POST["validlic13"];
		documentadd($applicantno,"S1",$validlic13,0,"");
	}
	else 
		documentdelete($applicantno,"S1");
	
	if (isset($_POST["validlic14"]) && !empty($_POST["validlic14"]))
	{
		$validlic14 = $_POST["validlic14"];
		documentadd($applicantno,"S2",$validlic14,0,"");
	}
	else 
		documentdelete($applicantno,"S2");
}


//EXPERIENCE


if (isset($_POST["expvessel"]))
	$expvessel = $_POST["expvessel"];

if (isset($_POST["expenginetype"]))
	$expenginetype = $_POST["expenginetype"];

if (isset($_POST["expflagcode"]))
	$expflagcode = $_POST["expflagcode"];

if (isset($_POST["expflagothers"]))
	$expflagothers = $_POST["expflagothers"];

if (isset($_POST["expvesseltypecode"]))
	$expvesseltypecode = $_POST["expvesseltypecode"];

if (isset($_POST["expvsltypeothers"]))
	$expvsltypeothers = $_POST["expvsltypeothers"];

if (isset($_POST["exprankcode"]))
	$exprankcode = $_POST["exprankcode"];

if (isset($_POST["exptraderoutecode"]))
	$exptraderoutecode = $_POST["exptraderoutecode"];

if (isset($_POST["exptradeothers"]))
	$exptradeothers = $_POST["exptradeothers"];

if (isset($_POST["expgrosston"]))
	$expgrosston = $_POST["expgrosston"];

if (isset($_POST["expdeadwt"]))
	$expdeadwt = $_POST["expdeadwt"];

if (isset($_POST["expcargo"]))
	$expcargo = $_POST["expcargo"];

if (isset($_POST["expmanningcode"]))
	$expmanningcode = $_POST["expmanningcode"];

if (isset($_POST["expmanningothers"]))
	$expmanningothers = $_POST["expmanningothers"];

if (isset($_POST["expembdate"]))
	$expembdate = $_POST["expembdate"];

if (isset($_POST["expdisembdate"]))
	$expdisembdate = $_POST["expdisembdate"];

if (isset($_POST["expdisembreasoncode"]))
	$expdisembreasoncode = $_POST["expdisembreasoncode"];

if (isset($_POST["expreasonothers"]))
	$expreasonothers = $_POST["expreasonothers"];

//EMPLOYMENT

if (isset($_POST["empemployer"]))
	$empemployer = $_POST["empemployer"];

if (isset($_POST["empposition"]))
	$empposition = $_POST["empposition"];

if (isset($_POST["emptelno"]))
	$emptelno = $_POST["emptelno"];

if (isset($_POST["empdatefrom"]))
	$empdatefrom = $_POST["empdatefrom"];

if (isset($_POST["empdateto"]))
	$empdateto = $_POST["empdateto"];

if (isset($_POST["empreason"]))
	$empreason = $_POST["empreason"];

	
//QUESTIONS

if (isset($_POST["qanswer1a"]))
	$qanswer1a = $_POST["qanswer1a"];

if (isset($_POST["qanswer1b"]))
	$qanswer1b = $_POST["qanswer1b"];

if (isset($_POST["qanswer2a"]))
	$qanswer2a = $_POST["qanswer2a"];

if (isset($_POST["qanswer2b"]))
	$qanswer2b = $_POST["qanswer2b"];

if (isset($_POST["qanswer3a"]))
	$qanswer3a = $_POST["qanswer3a"];

if (isset($_POST["qanswer3b"]))
	$qanswer3b = $_POST["qanswer3b"];

if (isset($_POST["qanswer4a"]))
	$qanswer4a = $_POST["qanswer4a"];

if (isset($_POST["qanswer4b"]))
	$qanswer4b = $_POST["qanswer4b"];

if (isset($_POST["qanswer5a"]))
	$qanswer5a = $_POST["qanswer5a"];

if (isset($_POST["qanswer5b"]))
	$qanswer5b = $_POST["qanswer5b"];

if (isset($_POST["qdesiredsalary"]))
	$qdesiredsalary = $_POST["qdesiredsalary"];

if (isset($_POST["recommendedby"]))
	$recommendedby = $_POST["recommendedby"];

	
//POSTS FOR DELETION/EDIT

if (isset($_POST['deleteid']))
	$deleteid = $_POST['deleteid'];

if (isset($_POST['editid']))
	$editid = $_POST['editid'];

//END OF POSTS FOR DELETION/EDIT


//********************************************************  SAVING SECTION  ***************************************************//
	

switch ($actiontxt)
{
	case "cancel"	:
			
			$editmode = "";
		
			switch ($currentpage)
			{
				case "0": //PERSONAL
					
					break;
				case "1": //EDUCATION
				
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
				case "4": //EXPERIENCE
						
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
				case "5": //EMPLOYMENT
						
						$empemployer = "";
						$empposition = "";
						$emptelno = "";
						$empdatefrom = "";
						$empdateto = "";
						$empreason = "";
				
					break;		
			}
		
		break;
		
	case "editsave"	:
			$editmode = "";
			
			switch ($currentpage)
			{
				case "0": //PERSONAL
					
					break;
				case "1": //EDUCATION
						
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
					
				case "4": //EXPERIENCE
				
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
				case "5": //EMPLOYMENT
				
						$empdatefromraw = date('Y-m-d',strtotime($empdatefrom));
						$empdatetoraw = date('Y-m-d',strtotime($empdateto));
						
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
						$empreason = "";
				
					break;		
			}
		
		break;	
	
	case "edit"		:
		
			$editmode = "disabled=\"disabled\"";
		
			switch ($currentpage)
			{
				case "0": //PERSONAL
					
					break;
				case "1": //EDUCATION
				
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
				case "4": //EXPERIENCE
				
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
				case "5": //EMPLOYMENT
				
						$qryempdata = mysql_query("SELECT EMPLOYER,POSITION,TELNO,
												date_format(DATEFROM,'%m/%d/%Y') AS DATEFROM,date_format(DATETO,'%m/%d/%Y') AS DATETO, REASON
												FROM employhistory
												WHERE APPLICANTNO=$applicantno AND IDNO=$editid") or die(mysql_error());
						
						$rowempdata = mysql_fetch_array($qryempdata);
				
						$empemployer = $rowempdata["EMPLOYER"];
						$empposition = $rowempdata["POSITION"];
						$emptelno = $rowempdata["TELNO"];
						$empdatefrom = $rowempdata["DATEFROM"];
						$empdateto = $rowempdata["DATETO"];
						$empreason = $rowempdata["REASON"];
						
					break;		
			}
		
		
		break;
		
	case "delete"	:
			
			switch ($currentpage)
			{
				case "0": //PERSONAL
					
					break;
				case "1": //EDUCATION
					
						$qryeducdelete = mysql_query("DELETE FROM creweducation WHERE APPLICANTNO=$applicantno AND IDNO=$deleteid") or die(mysql_error());
				
					break;
				case "4": //EXPERIENCE
				
						$qryexpdelete = mysql_query("DELETE FROM crewexperience WHERE APPLICANTNO=$applicantno AND IDNO=$deleteid") or die(mysql_error());
					
					break;
				case "5": //EMPLOYMENT
				
						$qryemploydelete = mysql_query("DELETE FROM employhistory WHERE APPLICANTNO=$applicantno AND IDNO=$deleteid") or die(mysql_error());
				
					break;
			}
		break;
		
	case ($actiontxt == "agreed" || $actiontxt == "save")	: 

		
	
	
		if (!empty($applicantno))
		{
//			$qryexperiencechk = mysql_query("SELECT APPLICANTNO FROM crewexperience WHERE APPLICANTNO=$applicantno") or die(mysql_error());
			$qryschoolchk = mysql_query("SELECT APPLICANTNO,SCHOOLID,SCHOOLOTHERS,COURSEID,COURSEOTHERS FROM creweducation WHERE APPLICANTNO=$applicantno ORDER BY DATEGRADUATED DESC LIMIT 1") or die(mysql_error());
			$qrycontactno = mysql_query("SELECT TELNO,CEL1,CEL2,CEL3 FROM crew WHERE APPLICANTNO=$applicantno
										AND (TELNO IS NOT NULL OR CEL1 IS NOT NULL OR CEL2 IS NOT NULL OR CEL3 IS NOT NULL)
										") or die(mysql_error());
		
			$valid = "";
			
//			if ((mysql_num_rows($qryexperiencechk) == 0) && ($choice1 != "D41") && ($choice1 != "D49") && ($choice1 != "E41") && ($choice1 != "E49"))
//				$valid = "No Experience";
			
			if (mysql_num_rows($qryschoolchk) > 0)
			{
				$rowschoolchk = mysql_fetch_array($qryschoolchk);

				$xschoolid = $rowschoolchk["SCHOOLID"];
				$xschoolothers = $rowschoolchk["SCHOOLOTHERS"];
				$xcourseid = $rowschoolchk["COURSEID"];
				$xcourseothers = $rowschoolchk["COURSEOTHERS"];
					
				if ($xschoolid == "" && $xschoolothers == "")
				{
					if (empty($valid))
						$valid = "Invalid School";
					else
						$valid = ", Invalid School";
				}
				
				if ($xcourseid == "" && $xcourseothers == "")
				{
					if (empty($valid))
						$valid = "Invalid Course";
					else
						$valid = ", Invalid Course";
				}
			}
			else
			{
				if (empty($valid))
					$valid = "No Educational Background";
				else
					$valid = ", No Educational Background";
			}
			
			if (mysql_num_rows($qrycontactno) == 0)
				if (empty($valid))
					$valid = "Invalid Contact Nos.";
				else
					$valid = ", Invalid Contact Nos.";
		
			if (empty($valid))
			{
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
					
					
				if ($actiontxt == "agreed")
				{
					$qryapplicantsave = mysql_query("UPDATE applicant SET AGREED = 1 WHERE APPLICANTNO = $applicantno") or die(mysql_error());
				
					$qryemailbody = mysql_query("SELECT a.APPLICANTNO,a.ONLINE,a.CHOICE1,a.DATEAPPLIED,a.RECOMMENDEDBY,
										c.FNAME,c.GNAME,CONCAT(LEFT(c.MNAME,1),'.') AS MNAME,
										r.RANKCODE,r.RANK,r.ALIAS1,r.RANKFULL
										FROM applicant a
										LEFT JOIN crew c ON c.APPLICANTNO=a.APPLICANTNO
										LEFT JOIN rank r ON r.RANKCODE=a.CHOICE1
										where a.APPLICANTNO=$applicantno
									") or die(mysql_error());
				
					$rowemailbody = mysql_fetch_array($qryemailbody);
					
					$zonline = $rowemailbody["ONLINE"];
					$zchoice1 = $rowemailbody["CHOICE1"];
					
					$zdateapplied = strtotime("M d, Y H:i:s",$rowemailbody["DATEAPPLIED"]);
					$zrecommendedby = $rowemailbody["RECOMMENDEDBY"];
					$zfname = $rowemailbody["FNAME"];
					$zgname = $rowemailbody["GNAME"];
					$zmname = $rowemailbody["MNAME"];
					$zrankcode = $rowemailbody["RANKCODE"];
					$zrank = $rowemailbody["RANK"];
					$zalias1 = $rowemailbody["ALIAS1"];
					$zrankfull = $rowemailbody["RANKFULL"];
					
					if ($zonline == 1)
						$onlineshow = "ONLINE";
					else
						$onlineshow = "WALK-IN";
					
					$body = "
						<html>
						<head>

						</head>

						<body>
							<br /><br />
							<b>Good Day! Someone applied for the position of $zrank.</b>
							<br /><br />
							<a href=\"http://61.28.171.13/veritas/crewdatasheet.php?applicantno=$applicantno\">Click here to view Crew Data Sheet (for Internet Explorer 6.0 Browser only)</a>
							<br /><br />
							or Copy-Paste this link to the Address(URL) of Internet Explorer 6.0 Browser: <br /><br />
							<b>http://61.28.171.13/veritas/repcrewdatasheet.php?applicantno=$applicantno</b>

						</body>

						</html>
					";
					
					$sendemailtype = 3;
					$subject = "New Applicant - $onlineshow : $zgname $zfname ($zrank)";
					
					include("/usr/local/www/data/veritas/include/email.inc");
					
					$mailer->Send();
					$mailer->ClearAddresses();
					$mailer->ClearAttachments();
					
					$qryapplicantemailed = mysql_query("UPDATE applicant SET EMAILED=1 WHERE APPLICANTNO=$applicantno") or die(mysql_error());
					
					$actiontxt = "";
				}
			}
			else
			{
				echo "<script>alert('$valid');</script>";
			}
		}
			
		break;	
	
	case ($actiontxt == "reset" || $actiontxt == "print")	:
			
			if ($applicantno != "")
				$qryprintsave = mysql_query("UPDATE applicant SET PRINTED='$currentdate' WHERE APPLICANTNO=$applicantno") or die(mysql_error());
		
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
			
			$educschoolid = "";
			$educschoolothers = "";
			$educcourseid = "";
			$educcourseothers = "";
			$educdategrad = "";
			$educlevel = "";
			$educhonors = "";	
			
			$validdoc1 = "";
			$validdoc2 = "";
			$validdoc3 = "";
			
			$chkhigherlic = 0;
			$chkhigherlicstatus = "";

			$validlic1 = "";
			$validlic2 = "";
			$validlic3 = "";
			$validlic4 = "";
			$validlic5 = "";
			$validlic6 = "";
			$validlic7 = "";
			$validlic8 = "";
			$validlic9 = "";
			$validlic10 = "";
			$validlic11 = "";
			$validlic12 = "";
			$validlic13 = "";
			$validlic14 = "";
			
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
												CEL1,CEL2,CEL3,IF(cs.APPLICANTNO IS NOT NULL,s.DESCRIPTION,NULL) AS SCHOLARTYPE
												FROM crew c
												LEFT JOIN crewscholar cs ON cs.APPLICANTNO=c.APPLICANTNO
												LEFT JOIN scholar s ON s.SCHOLASTICCODE=cs.SCHOLASTICCODE
												WHERE c.APPLICANTNO=$appno") or die(mysql_error());
					
					$rowgetcrew = mysql_fetch_array($qrygetcrew);
					
					$fname = $rowgetcrew["FNAME"];
					$gname = $rowgetcrew["GNAME"];
					$mname = $rowgetcrew["MNAME"];
					$scholartype = "&nbsp;( " . $rowgetcrew["SCHOLARTYPE"] . " ) ";
					
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
					
					
					//GET DOCUMENTS AND LICENSES
					
					$qrygetdocs = mysql_query("SELECT * FROM applicantdocs WHERE APPLICANTNO=$applicantno") or die(mysql_error());
					
					while ($rowgetdocs = mysql_fetch_array($qrygetdocs))
					{
						$doccode1 = $rowgetdocs["DOCCODE"];
						$docvalidity = date('m/d/Y',strtotime($rowgetdocs["VALIDITY"]));
						$higherlic = $rowgetdocs["HIGHERLIC"];
						
						switch ($doccode1)
						{
							case "41"	: //PHILIPPINE PASSPORT
									$validdoc1 = $docvalidity;
								break;
							case "F2"	: //PHILIPPINE SEAMANS BOOK
									$validdoc2 = $docvalidity;
								break;
							case "42"	: //U.S. VISA
									$validdoc3 = $docvalidity;
								break;
							case "F1"	: //PHILIPPINE LICENSE
									$validlic1 = $docvalidity;
								break;
							case "18"	: //COC for OFFICEERS
									$validlic2 = $docvalidity;
								break;
							case "16"	: //EOC for OFFICERS
									$validlic3 = $docvalidity;
								break;
							case "C0"	: //COC for RATINGS
									$validlic4 = $docvalidity;
								break;
							case "27"	: //GOC
									$validlic5 = $docvalidity;
								break;
							case "P1"	: //PANAMA LICENSE
									$validlic6 = $docvalidity;
								break;
							case "P2"	: //PANAMA SEAMAN BOOK
									$validlic7 = $docvalidity;
								break;
							case "28"	: //PANAMA GMDSS LICENSE
									$validlic8 = $docvalidity;
								break;
							case "44"	: //PANAMA GMDSS SEAMAN BOOK
									$validlic9 = $docvalidity;
								break;
							case "H1"	: //HONGKONG LICENSE
									$validlic10 = $docvalidity;
								break;
							case "J1"	: //JAPANESE LICENSE
									$validlic11 = $docvalidity;
								break;
							case "J3"	: //JAPANESE GOC
									$validlic12 = $docvalidity;
								break;
							case "S1"	: //SINGAPORE GOC
									$validlic13 = $docvalidity;
								break;
							case "S2"	: //SINGAPORE COE
									$validlic14 = $docvalidity;
								break;
						}
						
					}
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
			
			$qryverify = mysql_query("SELECT c.APPLICANTNO,IF(cs.APPLICANTNO IS NOT NULL,s.DESCRIPTION,NULL) AS SCHOLARTYPE FROM crew c
										LEFT JOIN crewscholar cs ON cs.APPLICANTNO=c.APPLICANTNO
										LEFT JOIN scholar s ON s.SCHOLASTICCODE=cs.SCHOLASTICCODE
										WHERE FNAME='$fname' AND GNAME='$gname'") or die(mysql_error());	
			
			
			if (mysql_num_rows($qryverify) == 0)
			{
//				$local2 = substr($local,0,8);
//				$local3 = substr($local,0,9);
				

				switch($local)
				{
				    case "1": $online = 1; break;
				    case "2": $online = 2; break;
				    default: $online = 0;
				}

				//if (empty($local))
					//$online = 0;
				//else
					//$online = 1;
				
				

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
				
				$qrysaveapplicant = mysql_query("INSERT INTO applicant(APPLICANTNO,CHOICE1,CHOICE2,DATEAPPLIED,ONLINE) 
												VALUES($applicantno,'$choice1','$choice2','$currentdate',$online)") or die(mysql_error());
			}
			else //CREW EXISTS
			{
				$rowverify = mysql_fetch_array($qryverify);
				
				
				//CHECK IF SCHOLAR
				
				if (!empty($rowverify["SCHOLARTYPE"])) //SCHOLAR
				{
					$scholartype = "&nbsp;( " . $rowverify["SCHOLARTYPE"] . " ) ";
					
					$applicantno = $rowverify["APPLICANTNO"];
					
					$qryupdatecrew = mysql_query("UPDATE crew SET MNAME='$mname',
																PROVADDRESS='$crewprovaddress',
																PROVPROVCODE='$crewprovprovince',
																PROVTOWNCODE='$crewprovcity',
																PROVBRGYCODE='$crewprovbarangay',
																PROVZIPCODE='$crewprovzipcode',
																PROVTELNO='$crewprovtelno',
																BIRTHDATE='$crewbdateraw',
																BIRTHPLACE='$crewbplace',
																CIVILSTATUS='$crewcivilstatus',
																NATIONALITY='FILIPINO',
																RELIGION='$crewreligion',
																WEIGHT='$crewweight',
																HEIGHT='$crewheight',
																GENDER='$crewgender',
																SIZESHOES='$crewshoesize',
																MADEBY='APP',
																MADEDATE='$currentdate',
																EMAIL='$crewemail',
																CEL1='$crewcelphone1',
																CEL2='$crewcelphone2',
																CEL3='$crewcelphone3'
													WHERE APPLICANTNO=$applicantno") or die(mysql_error());
					
					$qrychkapplicant = mysql_query("SELECT APPLICANTNO FROM applicant WHERE APPLICANTNO=$applicantno") or die(mysql_error());
					
					if (mysql_num_rows($qrychkapplicant) == 0)
					{
						$qrysaveapplicant = mysql_query("INSERT INTO applicant(APPLICANTNO,CHOICE1,CHOICE2,DATEAPPLIED) 
													VALUES($applicantno,'$choice1','$choice2','$currentdate')") or die(mysql_error());
					}
					else 
					{
						
					}

						
				}
				else //NOT SCHOLAR
				{
					$scholartype = "";
					$applicantno2 = $rowverify["APPLICANTNO"];
					$duplicate = 1;
					echo "<script>alert('Crew $fname, $gname already exists! Try searching Applicant No. $applicantno2');</script>";
				}
				

			}
	
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
				case "1": //EDUCATION
				
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
											'APP','$currentdate')
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
						}
						else 
							$remarks2 = "Duplicate Entry. Please try again.";
							
					break;

				case "4": //EXPERIENCE

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
				case "5": //EMPLOYMENT
					
						$qryempcheck = mysql_query("SELECT APPLICANTNO FROM employhistory 
											WHERE EMPLOYER='$empemployer' AND POSITION='$empposition'
											AND APPLICANTNO=$applicantno") or die(mysql_error());
						
						if (mysql_num_rows($qryempcheck) < 1)
						{
							$empdatefromraw = date('Y-m-d',strtotime($empdatefrom));
							$empdatetoraw = date('Y-m-d',strtotime($empdateto));
							
							$qryemploymentsave = mysql_query("INSERT INTO employhistory(APPLICANTNO,EMPLOYER,POSITION,TELNO,DATEFROM,DATETO,REASON)
												VALUES($applicantno,'$empemployer','$empposition','$emptelno','$empdatefromraw','$empdatetoraw',
												'$empreason')
												") or die(mysql_error());
							
							$empemployer = "";
							$empposition = "";
							$emptelno = "";
							$empdatefrom = "";
							$empdateto = "";
							$empreason = "";
						}
						else 
							$remarks7 = "Duplicate Entry. Please try again.";
				
					break;
				case "6": //QUESTIONS
					
					break;
				case "7": //AGREEMENT
					
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
	
//RANK CODE
$qrylicrank = mysql_query("SELECT RANKCODE,RANK FROM rank WHERE STATUS=1") or die(mysql_error());
$licselect = "<option selected value=\"\">--Select One--</option>";
	while($rowlicrank=mysql_fetch_array($qrylicrank))
	{
		$licrankcode1 = $rowlicrank["RANKCODE"];
		$licrank1 = $rowlicrank["RANK"];
		
		$selected2 = "";
		
		if ($licrankcode1 == $licrankcode)
			$selected2 = "SELECTED";
			
		$licselect .= "<option $selected2 value=\"$licrankcode1\">$licrank1</option>";
	}
	
//+++++++++++++++  END OF LICENSE  ++++++++++++++++++//

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
	
	$qryeduclist = mysql_query("SELECT IDNO,IF(ce.SCHOOLID IS NULL,ce.SCHOOLOTHERS,ms.SCHOOL) AS SCHOOL,
								IF(ce.COURSEID IS NULL,ce.COURSEOTHERS,mc.COURSE) AS COURSE,
								IF(ce.DATEGRADUATED IS NULL,'',ce.DATEGRADUATED) AS DATEGRADUATED,
								ce.LEVEL,ce.REMARKS
								FROM creweducation ce
								LEFT JOIN maritimeschool ms ON ce.SCHOOLID=ms.SCHOOLID
								LEFT JOIN maritimecourses mc ON ce.COURSEID=mc.COURSEID
								WHERE ce.APPLICANTNO=$applicantno") or die(mysql_error());
	
//	$qrydoclist = mysql_query("SELECT IDNO,cd.DOCUMENT,cds.DOCNO,cds.DATEISSUED,cds.DATEEXPIRED
//								FROM crewdocstatus cds
//								LEFT JOIN crewdocuments cd ON cd.DOCCODE=cds.DOCCODE
//								WHERE cd.TYPE='D' and cds.APPLICANTNO=$applicantno
//								ORDER BY cd.DOCUMENT") or die(mysql_error());
//	
//	$qryliclist = mysql_query("SELECT IDNO,cd.DOCUMENT,cds.DOCNO,cds.DATEISSUED,cds.DATEEXPIRED
//								FROM crewdocstatus cds
//								LEFT JOIN crewdocuments cd ON cd.DOCCODE=cds.DOCCODE
//								WHERE cd.TYPE='L'and cds.APPLICANTNO=$applicantno
//								ORDER BY cd.DOCUMENT") or die(mysql_error());


	$qrygetdocs = mysql_query("SELECT * FROM applicantdocs WHERE APPLICANTNO=$applicantno") or die(mysql_error());
	
	while ($rowgetdocs = mysql_fetch_array($qrygetdocs))
	{
		$doccode1 = $rowgetdocs["DOCCODE"];
		$docvalidity = date('m/d/Y',strtotime($rowgetdocs["VALIDITY"]));
		$higherlic = $rowgetdocs["HIGHERLIC"];
		
		switch ($doccode1)
		{
			case "41"	: //PHILIPPINE PASSPORT
					$validdoc1 = $docvalidity;
				break;
			case "F2"	: //PHILIPPINE SEAMANS BOOK
					$validdoc2 = $docvalidity;
				break;
			case "42"	: //U.S. VISA
					$validdoc3 = $docvalidity;
				break;
			case "F1"	: //PHILIPPINE LICENSE
					$validlic1 = $docvalidity;
				break;
			case "18"	: //COC for OFFICEERS
					$validlic2 = $docvalidity;
				break;
			case "16"	: //EOC for OFFICERS
					$validlic3 = $docvalidity;
				break;
			case "C0"	: //COC for RATINGS
					$validlic4 = $docvalidity;
				break;
			case "27"	: //GOC
					$validlic5 = $docvalidity;
				break;
			case "P1"	: //PANAMA LICENSE
					$validlic6 = $docvalidity;
				break;
			case "P2"	: //PANAMA SEAMAN BOOK
					$validlic7 = $docvalidity;
				break;
			case "28"	: //PANAMA GMDSS LICENSE
					$validlic8 = $docvalidity;
				break;
			case "44"	: //PANAMA GMDSS SEAMAN BOOK
					$validlic9 = $docvalidity;
				break;
			case "H1"	: //HONGKONG LICENSE
					$validlic10 = $docvalidity;
				break;
			case "J1"	: //JAPANESE LICENSE
					$validlic11 = $docvalidity;
				break;
			case "J3"	: //JAPANESE GOC
					$validlic12 = $docvalidity;
				break;
			case "S1"	: //SINGAPORE GOC
					$validlic13 = $docvalidity;
				break;
			case "S2"	: //SINGAPORE COE
					$validlic14 = $docvalidity;
				break;
		}
		
	}

	
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

<html>


<head>
	<title>Online Application</title>
	<meta http-equiv=\"content-type\" content=\"text/html;charset=utf-8\" />
	<meta name=\"generator\" content=\"Geany 0.9\" />
	
	<script language=\"javascript\" src=\"popcalendar.js\"></script>	
	<script language=\"javascript\" src=\"veripro.js\"></script>	
	
	<link rel=\"stylesheet\" href=\"veripro.css\">

	<script>
		var displaypage=new Array('personal','education','documents','license','experience','employment','questions','agreement');
									
		
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
					
			if (getpage==7)
				document.appform.btnnext.disabled = true;
			else
				document.appform.btnnext.disabled = false;
				
		}
		
		function previewfront(form) 
		{
		    var result = window.showModalDialog(\"repappfront.php?applicantno=$applicantno\", form, 
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
//						if(crewbdate.value=='' || crewbdate.value==null)
//							if(rem=='')
//								rem='Birthdate';
//							else
//								rem=rem + ',Birthdate';

						if((crewtelno.value=='' || crewtelno.value==null) &&
							(crewcelphone1.value=='' || crewcelphone1.value==null) &&
							(crewemail.value=='' || crewemail.value==null))
						{
							if(rem=='')
								rem='Contact Details';
							else
								rem=rem + ',Contact Details';
						}
					break;
					
					case '1':
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
					break;
					
					case '5':
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
								
					break;
					
					case '6':
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
					break;
					
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

<body onload=\"if ('$duplicate' == '1') 
		{
			document.appform.fname.value='';
			document.appform.gname.value='';
			document.appform.mname.value='';
			document.appform.choice1.value='';
			document.appform.fname.focus();
		}\">
	<div style=\"width:100%;margin:10 20 0 20;\">
	<br /><br />
	<img src=\"images/hdr_onlineapp.jpg\" />
	
	<form name=\"appform\" method=\"POST\">
	
	<div style=\"float:right;\">
		<table style=\"width:100%;\" class=\"listrow\" border=\"1\">
			<tr>
				<th align=\"center\" width=\"20%\">Application No.<br />
					<span style=\"font-size:1.5em;font-weight:Bold;color:Red;\">$applicantno</span>
					<br / ><span style=\"font-size:1em;font-weight:Bold;color:Lime;background-color:Black;\">$scholartype</span>
				</th>
				<th width=\"40%\" align=\"center\">&nbsp;

					&nbsp;&nbsp;Searching Options<br />
					<select name=\"searchby\">
						$searchselect
					</select>
					<input type=\"text\" name=\"searchkey\" value=\"$searchkey\" size=\"5\"  onKeyPress=\"return numbersonly(this);\"/>
					<input type=\"button\" name=\"btnsearch\" value=\"Search\" onclick=\"actiontxt.value='search';submit();\" />

				</th>
				<th align=\"center\" width=\"48%\">
					<br />
					<input type=\"button\" name=\"btnreset\" value=\"Reset All\" onclick=\"actiontxt.value='reset';currentpage.value=0;submit();\" />
					<input type=\"button\" value=\"Save\" $showsave $disableapproved onclick=\"actiontxt.value='save';checkadd(currentpage.value);\" />
					
	";
	
					if ($agreedx == 1)
					echo "	

					<input type=\"button\" name=\"btnreport\" 
						onclick=\"actiontxt.value = 'print';currentpage.value=0;appform.submit();
						repappfront.applicantno.value='$applicantno';
						window.open(repappfront.action,repappfront.target,'scrollbars=yes,resizable=yes,channelmode=yes');
						repappfront.submit();\" value=\"Print Application\">			

				
					";
										
	echo "
					&nbsp;&nbsp;<span style=\"color:Red;\">$searcherror</span>
				</th>
			</tr>
		";
//		if ($currentpage != 0)
//		{
//			echo "
//			<tr>
//				<th colspan=\"2\" style=\"font-size:1em;font-weight:Bold;\">
//					Name:&nbsp;$fname,&nbsp;$gname&nbsp;$mname&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Choice 1:&nbsp;$choice1
//				</th>
//			</tr>
//			";
//		}
		
		echo "
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
						<th align=\"left\">Address</th>
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
						<th align=\"left\">Tel. No. *</th>
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
						<th align=\"left\">Birth Date</th>
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
						<th align=\"left\">Cel. Phone 1 *</th>
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
						<th align=\"left\"><input type=\"text\" name=\"crewweight\" value=\"$crewweight\" size=\"3\" maxlength=\"3\" onKeyPress=\"return numbersonly(this);\" /></th>
					</tr>
					<tr>
						<th align=\"left\">Height (cm)</th>
						<th align=\"left\"><input type=\"text\" name=\"crewheight\" value=\"$crewheight\" size=\"3\" maxlength=\"3\" onKeyPress=\"return numbersonly(this);\" /></th>
					</tr>
					<tr>
						<th align=\"left\">Shoe Size</th>
						<th align=\"left\"><input type=\"text\" name=\"crewshoesize\" value=\"$crewshoesize\" size=\"5\" maxlength=\"5\" onKeyPress=\"return numbersonly(this);\" /></th>
					</tr>
					<tr>
						<th align=\"left\">Email *</th>
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
	

	<div id=\"education\" style=\"border:1px solid Navy;display:none;$div1\">
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
		
		<div style=\"width:100%;overflow:auto;height:150px;background-color:#DCDCDC;\">
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
						<td align=\"left\">$education1&nbsp;</td>
						<td align=\"left\">$education2&nbsp;</td>
						<td align=\"left\">$education3&nbsp;</td>
						<td align=\"left\">$education4&nbsp;</td>
						<td align=\"left\">$education5&nbsp;</td>
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
	
	<div id=\"documents\" style=\"border:1px solid Navy;display:none;$div2\">
		
		<span class=\"sectiontitle\">DOCUMENTS</span>
";
		if ($remarks3 != "")
echo "		<center><span class=\"error\">ERROR:<br />$remarks3</span></center>	";

echo "
		<center>
		<table width=\"70%\" class=\"listrow\">
			<tr>
				<th align=\"center\" width=\"70%\">&nbsp;</th>
				<th align=\"center\" width=\"30%\">Validity <i>(mm/dd/yyyy)</i></th>
			</tr>
			<tr>
				<td><b>Philippine Passport</b></td>
				<th align=\"left\">
					<input type=\"text\" name=\"validdoc1\" value=\"$validdoc1\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(validdoc1, validdoc1, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
			</tr>
			<tr>
				<td><b>Philippine Seaman Book</b></td>
				<th align=\"left\">
					<input type=\"text\" name=\"validdoc2\" value=\"$validdoc2\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(validdoc2, validdoc2, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
			</tr>
			<tr>
				<td><b>U.S. VISA</b></td>
				<th align=\"left\">
					<input type=\"text\" name=\"validdoc3\" value=\"$validdoc3\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(validdoc3, validdoc3, 'mm/dd/yyyy', 0, 0);return false;\">
				</th>
			</tr>
			<tr>
				<td colspan=\"2\">&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type=\"button\" value=\"Save\" onclick=\"actiontxt.value='save';submit();\" /></td>
			</tr>
		</table>
		</center>
		
		<br /><br />

";

echo "
	</div>
	
	<div id=\"license\" style=\"border:1px solid Navy;display:none;$div3\">
	
		<span class=\"sectiontitle\">LICENSES</span>
";
		if ($remarks4 != "")
echo "		<center><span class=\"error\">ERROR:<br />$remarks4</span></center>	 ";

echo "
		<div style=\"width:100%;\">
		
			<div style=\"width:50%;float:left;\">
				<br /><br />
				<center>
				<table width=\"90%\" class=\"listrow\">
					<tr>
						<th align=\"center\" width=\"65%\">&nbsp;</th>
						<th align=\"center\">Validity <i>(mm/dd/yyyy)</i></th>
					</tr>
					<tr>
						<th colspan=\"2\" align=\"left\"><i><u>For Officers</u></i></th>
					</tr>
					<tr>
						<td><b>Philippine License</b></td>
						<th align=\"left\">
							<input type=\"text\" name=\"validlic1\" value=\"$validlic1\" onKeyPress=\"return dateonly(this);\" 
										onblur=\"if(this.value != ''){licrankcode.disabled=false} else {licrankcode.disabled=true;}\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
							<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(validlic1, validlic1, 'mm/dd/yyyy', 0, 0);return false;\">
						</th>
					</tr>
					<tr>
						<td><b>COC (Certificate of Competency)</b></td>
						<th align=\"left\">
							<input type=\"text\" name=\"validlic2\" value=\"$validlic2\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
							<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(validlic2, validlic2, 'mm/dd/yyyy', 0, 0);return false;\">
						</th>
					</tr>
					<tr>
						<td><b>EOC (Endorsement of Certificate)</b></td>
						<th align=\"left\">
							<input type=\"text\" name=\"validlic3\" value=\"$validlic3\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
							<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(validlic3, validlic3, 'mm/dd/yyyy', 0, 0);return false;\">
						</th>
					</tr>
					<tr>
						<td><input type=\"checkbox\" $chkhigherlicstatus name=\"chkhigherlic\" 
								onclick=\"if(this.checked){licrankcode.disabled=false} else {licrankcode.disabled=true;}\" /><b>with Higher License</b></td>
						<td align=\"left\">Rank (for Philippine License)
						<select name=\"licrankcode\" >
							$licselect
						</select>
						</td>
					</tr>
				</table>
				<br />
				<table width=\"90%\" class=\"listrow\">
					<tr>
						<th colspan=\"2\" align=\"left\"><i><u>For Ratings</u></i></th>
					</tr>
					<tr>
						<td width=\"65%\"><b>COC (Certificate of Competency)</b></td>
						<th align=\"left\">
							<input type=\"text\" name=\"validlic4\" value=\"$validlic4\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
							<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(validlic4, validlic4, 'mm/dd/yyyy', 0, 0);return false;\">
						</th>
					</tr>
				</table>
				</center>
			</div>
		
			<div style=\"width:49%;float:right;border-left:1px solid black;\">
				<center>
				<table width=\"90%\" class=\"listrow\">
					<tr>
						<th align=\"center\" width=\"65%\">&nbsp;</th>
						<th align=\"center\">Validity <i>(mm/dd/yyyy)</i></th>
					</tr>
					<tr>
						<th><b>General Operator Certificate(GOC)</b></th>
						<th align=\"left\">
							<input type=\"text\" name=\"validlic5\" value=\"$validlic5\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
							<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(validlic5, validlic5, 'mm/dd/yyyy', 0, 0);return false;\">
						</th>
					</tr>
					<tr>
						<th><b>Panama License</b></th>
						<th align=\"left\">
							<input type=\"text\" name=\"validlic6\" value=\"$validlic6\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
							<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(validlic6, validlic6, 'mm/dd/yyyy', 0, 0);return false;\">
						</th>
					</tr>
					<tr>
						<th><b>Panama Seaman Book</b></th>
						<th align=\"left\">
							<input type=\"text\" name=\"validlic7\" value=\"$validlic7\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
							<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(validlic7, validlic7, 'mm/dd/yyyy', 0, 0);return false;\">
						</th>
					</tr>
					<tr>
						<th><b>Panama GMDSS License</b></th>
						<th align=\"left\">
							<input type=\"text\" name=\"validlic8\" value=\"$validlic8\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
							<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(validlic8, validlic8, 'mm/dd/yyyy', 0, 0);return false;\">
						</th>
					</tr>
					<tr>
						<th><b>Panama GMDSS Seaman Book</b></th>
						<th align=\"left\">
							<input type=\"text\" name=\"validlic9\" value=\"$validlic9\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
							<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(validlic9, validlic9, 'mm/dd/yyyy', 0, 0);return false;\">
						</th>
					</tr>
					<tr>
						<th><b>Hongkong License</b></th>
						<th align=\"left\">
							<input type=\"text\" name=\"validlic10\" value=\"$validlic10\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
							<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(validlic10, validlic10, 'mm/dd/yyyy', 0, 0);return false;\">
						</th>
					</tr>
					<tr>
						<th><b>Japanese License</b></th>
						<th align=\"left\">
							<input type=\"text\" name=\"validlic11\" value=\"$validlic11\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
							<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(validlic11, validlic11, 'mm/dd/yyyy', 0, 0);return false;\">
						</th>
					</tr>
					<tr>
						<th><b>Japanese GOC</b></th>
						<th align=\"left\">
							<input type=\"text\" name=\"validlic12\" value=\"$validlic12\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
							<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(validlic12, validlic12, 'mm/dd/yyyy', 0, 0);return false;\">
						</th>
					</tr>
					<tr>
						<th><b>Singapore GOC</b></th>
						<th align=\"left\">
							<input type=\"text\" name=\"validlic13\" value=\"$validlic13\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
							<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(validlic13, validlic13, 'mm/dd/yyyy', 0, 0);return false;\">
						</th>
					</tr>
					<tr>
						<th><b>Singapore COE</b></th>
						<th align=\"left\">
							<input type=\"text\" name=\"validlic14\" value=\"$validlic14\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
							<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(validlic14, validlic14, 'mm/dd/yyyy', 0, 0);return false;\">
						</th>
					</tr>
					<tr>
						<td colspan=\"2\">&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><input type=\"button\" value=\"Save\" onclick=\"actiontxt.value='save';submit();\" /></td>
					</tr>
				</table>
				</center>
				<br />
			</div>
			
		</div>

";

echo "
	</div>
	
	<div id=\"experience\" style=\"border:1px solid Navy;display:none;$div4\">
	
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
						<td align=\"left\">$experience1&nbsp;</td>
						<td align=\"left\">$experience2&nbsp;</td>
						<td align=\"left\">$experience3&nbsp;</td>
						<td align=\"left\">$experience4&nbsp;</td>
						<td align=\"left\">$experience5&nbsp;</td>
						<td align=\"left\">$experience6&nbsp;</td>
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
	
	<div id=\"employment\" style=\"border:1px solid Navy;display:none;$div5\">
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
						<td align=\"left\">$employment1&nbsp;</td>
						<td align=\"left\">$employment2&nbsp;</td>
						<td align=\"left\">$employment3&nbsp;</td>
						<td align=\"left\">$employment4&nbsp;</td>
						<td align=\"left\">$employment5&nbsp;</td>
						<td align=\"left\">$employment6&nbsp;</td>
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
	
	<div id=\"questions\" style=\"border:1px solid Navy;display:none;$div6\">		
		
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
			<tr>
				<th align=\"left\">&nbsp;</th>
				<th align=\"left\">&nbsp;</th>
			</tr>				
		</table>
		<table width=\"100%\" class=\"listrow\">
			<tr>
				<th align=\"left\">WHO RECOMMENDED YOU TO THIS COMPANY?</th>
				<th align=\"left\"><input type=\"text\" name=\"recommendedby\" value=\"$recommendedby\" onkeyup=\"javascript:this.value=this.value.toUpperCase()\" size=\"30\" /></th>
			</tr>
		</table>		
	</div>
	
	<div id=\"agreement\" style=\"border:1px solid Navy;display:none;$div7\">	

		<span class=\"sectiontitle\">AGREEMENT</span>	
		
		<br /><br />
		<br />
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
		<span style=\"font-size:1em;font-weight:Bold;color:Red;\">Your Application has been forwarded to Veritas Maritime Corporation. Thank you!</span>
		<!--
			<input type=\"button\" name=\"btnreport\" 
				onclick=\"actiontxt.value = 'print';currentpage.value=0;appform.submit();
				repappfront.applicantno.value='$applicantno';
				window.open(repappfront.action,repappfront.target,'scrollbars=yes,resizable=yes,channelmode=yes');
				repappfront.submit();\" value=\"Print Application\">		
		-->
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
	
	<form name=\"repappfront\" action=\"repappfront.php\" target=\"repappfront\" method=\"GET\">
		<input type=\"hidden\" name=\"applicantno\">
	</form>

  </div>
</body>
</html>
";
