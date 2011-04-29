<?php

include("veritas/connectdb.php");	
include('veritas/include/stylephp.inc');
$getservername=$_SERVER["SERVER_NAME"];
$getserveraddr=$_SERVER["SERVER_ADDR"];
$formname = "scholar";
$focusname = "gname";

session_start();

$currentdate = date("Y-m-d H:i:s");

if (isset($_POST["actiontxt"]))
	$actiontxt = $_POST["actiontxt"];
	
if (isset($_SESSION["employeeid"]))
	$employeeid = $_SESSION["employeeid"];

//POSTS

if(isset($_POST["applicantno"]))
	$applicantno=$_POST["applicantno"];

if(isset($_POST["crewcode"]))
	$crewcode=$_POST["crewcode"];

if(isset($_POST["gname"]))
	$gname=$_POST["gname"];

if(isset($_POST["fname"]))
	$fname=$_POST["fname"];

if(isset($_POST["ranktypecode"]))
	$ranktypecode=$_POST["ranktypecode"];

if(isset($_POST["address"]))
	$address=$_POST["address"];	

if(isset($_POST["provincecode"]))
	$provincecode=$_POST["provincecode"];

if(isset($_POST["towncode"]))
	$towncode=$_POST["towncode"];

if(isset($_POST["barangaycode"]))
	$barangaycode=$_POST["barangaycode"];

if(isset($_POST["birthdate"]))
	$birthdate=$_POST["birthdate"];
	
if(isset($_POST["telno"]))
	$telno=$_POST["telno"];
	
if(isset($_POST["cel1"]))
	$cel1=$_POST["cel1"];
	
if(isset($_POST["email"]))
	$email=$_POST["email"];
	
if(isset($_POST["datehsgrad"]))
	$datehsgrad=$_POST["datehsgrad"];
	
if(isset($_POST["scholasticcode"]))
	$scholasticcode=$_POST["scholasticcode"];
	
if(isset($_POST["batchno"]))
	$batchno=$_POST["batchno"];
	
if(isset($_POST["schoolid"]))
	$schoolid=$_POST["schoolid"];
	
if(isset($_POST["yeargraduate"]))
	$yeargraduate=$_POST["yeargraduate"];
	
if(isset($_POST["expelleddate"]))
	$expelleddate=$_POST["expelleddate"];

$showmulti=0; //for searching multiple names
switch($actiontxt)
{
	case "search":
		$searched=0;
		if(!empty($gname))
		{
			if(!empty($fname))
			{
				$addwhere="AND FNAME LIKE '$fname%'";
				$addalert= "and Last Name";
			}
			$qrysearch=mysql_query("SELECT APPLICANTNO,CONCAT(FNAME,', ',GNAME,' ',IF(MNAME IS NULL OR MNAME='','',CONCAT(LEFT(MNAME,1),'.'))) AS CREWNAME
				FROM crew 
				WHERE GNAME LIKE '$gname%' $addwhere
				ORDER BY FNAME") or die(mysql_error());
			$cntsearch=mysql_num_rows($qrysearch);
			if($cntsearch>1)
			{
				$showmulti=1;
			}
			elseif ($cntsearch==0)
			{
				echo "<script>alert('First Name $addalert not found! Create new Scholar.');</script>";
				$fname="";
				$gname="";
			}
			else 
			{
				$rowsearch=mysql_fetch_array($qrysearch);
				$applicantno=$rowsearch["APPLICANTNO"];
			}
			$searched=1;
		}
		if(!empty($fname) && $searched!=1)
		{
			$qrysearch=mysql_query("SELECT APPLICANTNO,CONCAT(FNAME,', ',GNAME,' ',IF(MNAME IS NULL OR MNAME='','',CONCAT(LEFT(MNAME,1),'.'))) AS CREWNAME
				FROM crew 
				WHERE FNAME LIKE '$fname%'
				ORDER BY FNAME") or die(mysql_error());
			$cntsearch=mysql_num_rows($qrysearch);
			if($cntsearch>1)
			{
				$showmulti=1;
			}
			elseif ($cntsearch==0)
			{
				echo "<script>alert('Last Name not found! Create new Scholar.');</script>";
				$fname="";
				$gname="";
			}
			else 
			{
				$rowsearch=mysql_fetch_array($qrysearch);
				$applicantno=$rowsearch["APPLICANTNO"];
			}
			$searched=1;
		}
		if(!empty($applicantno))
		{
			$qrysearch=mysql_query("SELECT APPLICANTNO
				FROM crew 
				WHERE APPLICANTNO=$applicantno") or die(mysql_error());
			if(mysql_num_rows($qrysearch)==0)
			{
				echo "<script>alert('Applicant No. not found!');</script>";
				$applicantno="";
			}
			else 
			{
				$rowsearch=mysql_fetch_array($qrysearch);
				$applicantno=$rowsearch["APPLICANTNO"];
			}
			$searched=1;
		}
		if(!empty($crewcode))
		{
			$qrysearch=mysql_query("SELECT APPLICANTNO
				FROM crew 
				WHERE CREWCODE='$crewcode'") or die(mysql_error());
			if(mysql_num_rows($qrysearch)==0)
			{
				echo "<script>alert('Crew Code not found!');</script>";
				$crewcode="";
			}
			else 
			{
				$rowsearch=mysql_fetch_array($qrysearch);
				$applicantno=$rowsearch["APPLICANTNO"];
			}
			$searched=1;
		}
	break;
	case "save":
		if(empty($birthdate))
			$birthdatesave="NULL";
		else
			$birthdatesave="'".date("Y-m-d",strtotime($birthdate))."'";
		if(empty($datehsgrad))
			$datehsgradsave="NULL";
		else
			$datehsgradsave="'".date("Y-m-d",strtotime($datehsgrad))."'";
		if(empty($yeargraduate))
			$yeargraduatesave="NULL";
		else
			$yeargraduatesave="'".date("Y-m-d",strtotime($yeargraduate))."'";
		if(empty($expelleddate))
			$expelleddatesave="NULL";
		else
			$expelleddatesave="'".date("Y-m-d",strtotime($expelleddate))."'";
		if(empty($applicantno))
		{
			$qrysave=mysql_query("INSERT INTO crew (GNAME,FNAME,RANKTYPECODE,ADDRESS,PROVINCECODE,
				TOWNCODE,BARANGAYCODE,BIRTHDATE,TELNO,CEL1,EMAIL,MADEBY,MADEDATE) 
				VALUES ('$gname','$fname','$ranktypecode','$address','$provincecode','$towncode','$barangaycode',
				$birthdatesave,'$telno','$cel1','$email','$employeeid','$currentdate')") or die(mysql_error());
			$qrygetappno=mysql_query("SELECT APPLICANTNO FROM crew
				WHERE GNAME='$gname' AND FNAME='$fname' AND CREWCODE IS NULL") or die(mysql_error());
			$rowgetappno=mysql_fetch_array($qrygetappno);
			$applicantno=$rowgetappno["APPLICANTNO"];
		}
		else 
		{
			$qrysave=mysql_query("UPDATE crew SET GNAME='$gname',FNAME='$fname',RANKTYPECODE='$ranktypecode',
				ADDRESS='$address',PROVINCECODE='$provincecode',TOWNCODE='$towncode',BARANGAYCODE='$barangaycode',
				BIRTHDATE=$birthdatesave,TELNO='$telno',CEL1='$cel1',EMAIL='$email',MADEBY='$employeeid',MADEDATE='$currentdate'
				WHERE APPLICANTNO=$applicantno") or die(mysql_error());
		}
		$qrygetscholar=mysql_query("SELECT APPLICANTNO FROM crewscholar WHERE APPLICANTNO=$applicantno") or die(mysql_error());
		if(mysql_num_rows($qrygetscholar)!=0)
			$qrysavescholar=mysql_query("UPDATE crewscholar SET DATEHSGRAD=$datehsgradsave,SCHOLASTICCODE='$scholasticcode',
				BATCHNO='$batchno',SCHOOLID='$schoolid',YEARGRADUATE=$yeargraduatesave,EXPELLEDDATE=$expelleddatesave,RANKTYPECODE='$ranktypecode',
				MADEBY='$employeeid',MADEDATE='$currentdate'
				WHERE APPLICANTNO=$applicantno") or die(mysql_error());
		else 
			$qrysavescholar=mysql_query("INSERT INTO crewscholar (APPLICANTNO,DATEHSGRAD,SCHOLASTICCODE,
				BATCHNO,RANKTYPECODE,SCHOOLID,YEARGRADUATE,EXPELLEDDATE,MADEBY,MADEDATE) VALUES ($applicantno,$datehsgradsave,
				'$scholasticcode','$batchno','$ranktypecode','$schoolid',$yeargraduatesave,$expelleddatesave,'$employeeid',
				'$currentdate')") or die(mysql_error());
	break;
}

if(!empty($applicantno))
{
	$qrycrewdetails=mysql_query("SELECT CREWCODE,GNAME,FNAME,ADDRESS,BARANGAYCODE,PROVINCECODE,TOWNCODE,
		RANKTYPECODE,BIRTHDATE,TELNO,CEL1,EMAIL
		FROM crew c
		WHERE APPLICANTNO=$applicantno") or die(mysql_error());
	$cntcrewdetails=mysql_num_rows($qrycrewdetails);
	if($cntcrewdetails!=0)
	{
		$rowcrewdetails=mysql_fetch_array($qrycrewdetails);
		$crewcode=$rowcrewdetails["CREWCODE"];
		$gname=$rowcrewdetails["GNAME"];
		$fname=$rowcrewdetails["FNAME"];
		$address=$rowcrewdetails["ADDRESS"];
		if($actiontxt!="address")
		{
			$barangaycode=$rowcrewdetails["BARANGAYCODE"];
			$provincecode=$rowcrewdetails["PROVINCECODE"];
			$towncode=$rowcrewdetails["TOWNCODE"];
		}
		$ranktypecode=$rowcrewdetails["RANKTYPECODE"];
		$birthdateraw=$rowcrewdetails["BIRTHDATE"];
		if(empty($birthdateraw))
			$birthdate="";
		else
			$birthdate=date("m/d/Y",strtotime($birthdateraw));
		$telno=$rowcrewdetails["TELNO"];
		$cel1=$rowcrewdetails["CEL1"];
		$email=$rowcrewdetails["EMAIL"];
	}
	$qryscholardetails=mysql_query("SELECT SCHOLASTICCODE,DATEHSGRAD,SCHOOLID,YEARGRADUATE,EXPELLEDDATE,BATCHNO
		FROM crewscholar
		WHERE APPLICANTNO=$applicantno") or die(mysql_error());
	$cntscholardetails=mysql_num_rows($qryscholardetails);
	if($cntscholardetails!=0)
	{
		$rowscholardetails=mysql_fetch_array($qryscholardetails);
		$scholasticcode=$rowscholardetails["SCHOLASTICCODE"];
		$datehsgradraw=$rowscholardetails["DATEHSGRAD"];
		if(empty($datehsgradraw))
			$datehsgrad="";
		else
			$datehsgrad=date("m/d/Y",strtotime($datehsgradraw));
		$schoolid=$rowscholardetails["SCHOOLID"];
		$yeargraduateraw=$rowscholardetails["YEARGRADUATE"];
		if(empty($yeargraduateraw))
			$yeargraduate="";
		else
			$yeargraduate=date("m/d/Y",strtotime($yeargraduateraw));
		$expelleddateraw=$rowscholardetails["EXPELLEDDATE"];
		if(empty($expelleddateraw))
			$expelleddate="";
		else
			$expelleddate=date("m/d/Y",strtotime($expelleddateraw));
		$batchno=$rowscholardetails["BATCHNO"];
	}
	else 
	{
		echo "<script>alert('$gname, $fname is not a scholar yet!')</script>";
	}
	$focusname="datehsgrad";
}
else 
{
	$cntscholardetails=0;
	$disableappno="disabled=\"disabled\"";
}
//get province
$qryprovince=mysql_query("SELECT PROVCODE,PROVINCE FROM addrprovince ORDER BY PROVINCE") or die(mysql_error());
while($rowprovince=mysql_fetch_array($qryprovince))
{
	$provincecodelist=$rowprovince["PROVCODE"];
	$province=addslashes($rowprovince["PROVINCE"]);
	$selected="";
	if($provincecodelist==$provincecode)
		$selected="selected";
	$provinceselect.="<option $selected value=\"$provincecodelist\">$province</option>";
}
//get town
$qrytown=mysql_query("SELECT TOWNCODE,TOWN 
	FROM addrtown 
	WHERE PROVCODE='$provincecode'
	ORDER BY TOWN") or die(mysql_error());
while($rowtown=mysql_fetch_array($qrytown))
{
	$towncodelist=$rowtown["TOWNCODE"];
	$town=addslashes($rowtown["TOWN"]);
	$selected="";
	if($towncodelist==$towncode)
		$selected="selected";
	$townselect.="<option $selected value=\"$towncodelist\">$town</option>";
}
//get barangay
$qrybarangay=mysql_query("SELECT BRGYCODE,BARANGAY 
	FROM addrbarangay 
	WHERE PROVCODE='$provincecode' AND TOWNCODE='$towncode'
	ORDER BY BARANGAY") or die(mysql_error());
while($rowbarangay=mysql_fetch_array($qrybarangay))
{
	$barangaycodelist=$rowbarangay["BRGYCODE"];
	$barangay=addslashes($rowbarangay["BARANGAY"]);
	$selected="";
	if($barangaycodelist==$barangaycode)
		$selected="selected";
	$barangayselect.="<option $selected value=\"$barangaycodelist\">$barangay</option>";
}
//get ranktype
$qryranktype=mysql_query("SELECT RANKTYPECODE,RANKTYPE
	FROM ranktype 
	ORDER BY RANKTYPE") or die(mysql_error());
while($rowranktype=mysql_fetch_array($qryranktype))
{
	$ranktypecodelist=$rowranktype["RANKTYPECODE"];
	$ranktype=addslashes($rowranktype["RANKTYPE"]);
	$selected="";
	if($ranktypecodelist==$ranktypecode)
		$selected="selected";
	$ranktypeselect.="<option $selected value=\"$ranktypecodelist\">$ranktype</option>";
}
//get scholar type
$qryscholartype=mysql_query("SELECT SCHOLASTICCODE,DESCRIPTION
	FROM scholar 
	ORDER BY DESCRIPTION") or die(mysql_error());
while($rowscholartype=mysql_fetch_array($qryscholartype))
{
	$scholasticcodelist=$rowscholartype["SCHOLASTICCODE"];
	$scholasticdesc=addslashes($rowscholartype["DESCRIPTION"]);
	$selected="";
	if($scholasticcodelist==$scholasticcode)
		$selected="selected";
	$scholartypeselect.="<option $selected value=\"$scholasticcodelist\">$scholasticdesc</option>";
}
//get school
$qryschool=mysql_query("SELECT SCHOOLID,SCHOOL
	FROM maritimeschool 
	ORDER BY SCHOOL") or die(mysql_error());
while($rowschool=mysql_fetch_array($qryschool))
{
	$schoolidlist=$rowschool["SCHOOLID"];
	$school=addslashes($rowschool["SCHOOL"]);
	$selected="";
	if($schoolidlist==$schoolid)
		$selected="selected";
	$schoolselect.="<option $selected value=\"$schoolidlist\">$school</option>";
}
echo "
<html>
<head>
<link rel=\"stylesheet\" type=\"text/css\" href=\"veripro.css\">
<script type='text/javascript' src='../veritas/veripro.js'></script>
<script type='text/javascript' src='../veritas/ajax.js'></script>

<script>
function createurl() // 
{
	url = \"http://$getserveraddr/veritas/crewchangeplanrequest.php?actionajax=\"; // The server-side script 
	
	var actionajax = document.$formname.actionajax.value; 
		
	//fill URL
	var applicantno = '&embapplicantnohidden=$applicantno';
	fillurl = actionajax + applicantno;
	getvalues();
}
function switchajax(x)
{
	if(x==1) // 1 if loading box is visible
	{
		var strdisplay='block';
		var strdisable='disabled';
	}
	else
	{
		var strdisplay='none';
		var strdisable='';
	}
	
	document.getElementById('ajaxprogress').style.display=strdisplay;
	
}
function fillview201()
{
	if(document.$formname.actionajax.value=='viewonboard201')
	{
		onboard201result=results[2];
		onboard201();
	}
}
function onboard201()
{
	var myObject = new Object();
    myObject.getonboard201result = onboard201result;
    myObject.getcrewname = document.$formname.fname.value + ', ' + document.$formname.gname.value;
    window.showModalDialog('crewonboard201.php', myObject, 'dialogHeight:650px; dialogWidth:500px;status=no'); 
}
function resetdata(x)
{
	with (document.$formname)
	{
		if(x==0)
		{
			gname.value='';
			fname.value='';
			crewcode.value='';
			applicantno.value='';
		}
		ranktypecode.value='';
		address.value='';
		provincecode.value='';
		towncode.value='';
		barangaycode.value='';
		birthdate.value='';
		telno.value='';
		cel1.value='';
		email.value='';
		datehsgrad.value='';
		scholasticcode.value='';
		batchno.value='';
		schoolid.value='';
		yeargraduate.value='';
		expelleddate.value='';
	}
}
function chkmulti()
{
	if('$showmulti'=='1')
		document.getElementById('multidata').style.display='block';
}
function chksave()
{
	if(document.getElementById('multidata').style.display=='none') //no saving if this is visible
	{
		var dosave=1;
		
		//check if crew exists
		if('$applicantno'=='')
		{
			if(!confirm('Crew does not exist. System will create this crew. Continue?'))
			{
				dosave=0;
			}
		}
		//check if crew is currently a scholar
//		if($cntscholardetails==0)
//		{
//			if(dosave==0)
//			{
//				if(confirm('Scholar does not exist. Continue anyway?'))
//				{
//					dosave=1;
//				}
//			}
//		}
		if(dosave==1) //check data before saving
		{
			var rem='';
			with(document.$formname)
			{
				if(gname.value=='')
					rem='Given Name';
				if(fname.value=='')
				{
					if(rem=='')
						rem='Last Name';
					else
						rem+=', Last Name';
				}
				if(ranktypecode.value=='')
				{
					if(rem=='')
						rem='Rank Type';
					else
						rem+=', Rank Type';
				}
				if(scholasticcode.value=='')
				{
					if(rem=='')
						rem='Scholar Type';
					else
						rem+=', Scholar Type';
				}
				if(batchno.value=='')
				{
					if(rem=='')
						rem='Batch No';
					else
						rem+=', Batch No';
				}
				if(schoolid.value=='')
				{
					if(rem=='')
						rem='College';
					else
						rem+=', College';
				}
				if(yeargraduate.value=='')
				{
					if(rem=='')
						rem='Grad Date';
					else
						rem+=', Grad Date';
				}
				if(rem=='')
				{
					actiontxt.value='save';
					submit();
				}
				else
				{
					alert('Input ' + rem + ' first!');
				}
			}
		}
	}
}
function datachange(x,y)
{
	var gochk=1;
	if(x=='fname' || x=='gname')
	{
		if(!confirm('Click OK to search. CANCEL to create New Scholar.'))
		{
			gochk=0;
			document.$formname.crewcode.ReadOnly='readonly';
			document.$formname.applicantno.ReadOnly='readonly';
		}
	}
	if(gochk==1)
	{
		with(document.$formname)
		{
			if(!(x=='fname' || x=='gname'))
			{
				gname.value='';
				fname.value='';
			}
			crewcode.value='';
			applicantno.value='';
			resetdata(1);
		}
		if(x)
		{
			document.$formname(x).value=y;
			chksearch();
//			document.$formname.actiontxt.value='search';
//			document.$formname.submit();
		}
		else
		{
			document.$formname.actiontxt.value='';
			document.$formname.gname.focus();
		}
	}
}
function chksearch()
{
	with(document.$formname)
	{
		if(gname.value!='' || fname.value!='' || crewcode.value!='' || applicantno.value!='')
		{
			actiontxt.value='search';
			submit();
		}
		else
			alert('Nothing to search!');
	}
}
</script>
</head>
<body onload=\"chkmulti();document.$formname.$focusname.focus();\">

<form name=\"$formname\" method=\"POST\">
	
	<div style=\"width:100%;height:440px;padding:0 20px 0 20px;overflow:hidden;\">
		<span class=\"wintitle\" style=\"font-size:12pt;\">SCHOLAR</span>
		<div style=\"width:50%;height:440px;float:left;padding:5px 0 0 20px;overflow:auto;\">
			<span class=\"sectiontitle\" style=\"width:90%\">SEARCH</span>
			<br />
			<br />
			<table class=\"setup\" width=\"100%\" style=\"border-bottom:1px solid Navy;\">	
				<tr>
					<th>Given name</th>
					<th>:</th>
					<th><input type=\"text\" name=\"gname\" value=\"$gname\" size=\"30\" 
							onKeyPress=\"return alphaonly(this);\"
							onkeyup=\"this.value=this.value.toUpperCase()\"
							onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
							onblur=\"if('$gname'!=this.value && fname.value==''){datachange(this.name,this.value);}\" />
					</th>
				</tr>
				<tr>
					<th>Last name</th>
					<th>:</th>
					<th><input type=\"text\" name=\"fname\" value=\"$fname\" size=\"30\" 
							onKeyPress=\"return alphaonly(this);\"
							onkeyup=\"this.value=this.value.toUpperCase()\"
							onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
							onblur=\"if('$fname'!=this.value && gname.value==''){datachange(this.name,this.value);}\" />
					</th>
				</tr>
				<tr>
					<th>Crew Code</th>
					<th>:</th>
					<th><input type=\"text\" name=\"crewcode\" value=\"$crewcode\" size=\"30\" 
							onKeyPress=\"return alphanumericonly(this);\"
							onkeyup=\"this.value=this.value.toUpperCase()\"
							onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
							onblur=\"if('$crewcode'!=this.value){datachange(this.name,this.value);}\" />
					</th>
				</tr>
				<tr>
					<th>Applicant No</th>
					<th>:</th>
					<th><input type=\"text\" name=\"applicantno\" value=\"$applicantno\" size=\"25\" 
							onKeyPress=\"return numbersonly(this);\"
							onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
							onblur=\"if('$applicantno'!=this.value){datachange(this.name,this.value);}\" />
						&nbsp;
						<img src=\"images/buttons/btn_v_def.gif\"
										onmousedown=\"this.src='images/buttons/btn_v_click.gif';\"
										onmouseup=\"this.src='images/buttons/btn_v_def.gif';\"
										onmouseout=\"this.src='images/buttons/btn_v_def.gif';\"
										onclick=\"if('$applicantno'!=''){actionajax.value='viewonboard201';createurl();}\"
										width=\"20px\">
					</th>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>
						<img src=\"images/buttons/btn_search_def.gif\" id=\"btnsearch\"
								onmousedown=\"this.src='images/buttons/btn_search_click.gif';\"
								onmouseup=\"this.src='images/buttons/btn_search_def.gif';\"
								onmouseout=\"this.src='images/buttons/btn_search_def.gif';\"
								onclick=\"chksearch();\">
						<img src=\"images/buttons/btn_reset_def.gif\" 
								onmousedown=\"this.src='images/buttons/btn_reset_click.gif';\"
								onmouseup=\"this.src='images/buttons/btn_reset_def.gif';\"
								onmouseout=\"this.src='images/buttons/btn_reset_def.gif';\"
								onclick=\"resetdata(0);submit();\">
					</td>
				</tr>		
			</table>
			<br />
			<span class=\"sectiontitle\" style=\"width:90%\">SCHOLAR DETAILS</span>
			<br />
			<br />
			<table class=\"setup\" width=\"100%\" >	
				<tr>
					<th>Grad. (HS)</th>
					<th>:</th>
					<th>
						<input type=\"text\" name=\"datehsgrad\" value=\"$datehsgrad\" size=\"10\" 
								onKeyPress=\"return dateonly(this);\"
								onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
								onblur=\"chkdate(this);\" />&nbsp;mm/dd/yy
					</th>
				</tr>  
				<tr>
					<th>Scholar Type</th>
					<th>:</th>
					<th>
						<select name=\"scholasticcode\" style=\"\" onchange=\"\" style=\"width:200;\">
							<option value=\"\">-Select-</option>
							$scholartypeselect
						</select>
					</th>
				</tr>  
				<tr>
					<th>Batch No</th>
					<th>:</th>
					<th>
						<input type=\"text\" name=\"batchno\" value=\"$batchno\" size=\"3\" 
								onKeyPress=\"return numbersonly(this);\" />
					</th>
				</tr>
				<tr>
					<th>College</th>
					<th>:</th>
					<th>
						<select name=\"schoolid\" style=\"\" onchange=\"\" style=\"width:250;\">
							<option value=\"\">-Select-</option>
							$schoolselect
						</select>
					</th>
				</tr>
				<tr>
					<th>Grad. Date</th>
					<th>:</th>
					<th>
						<input type=\"text\" name=\"yeargraduate\" value=\"$yeargraduate\" size=\"12\" 
								onKeyPress=\"return dateonly(this);\"
								onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
								onblur=\"chkdate(this);\" />&nbsp;mm/dd/yy
					</th>
				</tr>
				<tr>
					<th>Expelled</th>
					<th>:</th>
					<th>
						<input type=\"text\" name=\"expelleddate\" value=\"$expelleddate\" size=\"12\" 
								onKeyPress=\"return dateonly(this);\"
								onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
								onblur=\"chkdate(this);\" />&nbsp;mm/dd/yy
					</th>
				</tr>
			</table>
		</div>
		
		<div style=\"width:50%;height:440px;float:right;padding:5px 0 0 20px;;overflow:auto;border-left:1px solid Navy;\">
			<span class=\"sectiontitle\" style=\"width:90%\">CREW DETAILS</span>
			<br />
			<br />
			<table class=\"setup\" width=\"100%\" >	
				<tr>
					<th>No. & Street</th>
					<th>:</th>
					<th>
						<textarea name=\"address\" cols=\"30\" rows=\"3\" onKeyPress=\"return remarksonly(this);\">$address</textarea>
					</th>
				</tr>
				<tr>
					<th>Province</th>
					<th>:</th>
					<th>
						<select name=\"provincecode\" style=\"width:250;\"
							onchange=\"actiontxt.value='address';towncode.value='';barangaycode.value='';submit();\">
							<option value=\"\">-Select-</option>
							$provinceselect
						</select>
					</th>
				</tr>  
				<tr>
					<th>City/Town</th>
					<th>:</th>
					<th>
						<select name=\"towncode\" style=\"width:250;\" onchange=\"actiontxt.value='address';barangaycode.value='';submit();\">
							<option value=\"\">-Select-</option>
							$townselect
						</select>
					</th>
				</tr>
				<tr>
					<th>Barangay</th>
					<th>:</th>
					<th>
						<select name=\"barangaycode\" style=\"width:250;\"\">
							<option value=\"\">-Select-</option>
							$barangayselect
						</select>
					</th>
				</tr>
				<tr>
					<th>Rank Type</th>
					<th>:</th>
					<th>
						<select name=\"ranktypecode\" style=\"\" onchange=\"\" style=\"width:100;\">
							<option value=\"\">-Select-</option>
							$ranktypeselect
						</select>
					</th>
				</tr>
				<tr>
					<th>Birthdate</th>
					<th>:</th>
					<th>
						<input type=\"text\" name=\"birthdate\" value=\"$birthdate\" size=\"10\" 
								onKeyPress=\"return dateonly(this);\"
								onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
								onblur=\"chkdate(this);\" />&nbsp;mm/dd/yy
					</th>
				</tr>
				<tr>
					<th>TelNo</th>
					<th>:</th>
					<th>
						<input type=\"text\" name=\"telno\" value=\"$telno\" size=\"30\" 
							onKeyPress=\"return phonenoonly(this);\" />
					</th>
				</tr>
				<tr>
					<th>CellNo</th>
					<th>:</th>
					<th>
						<input type=\"text\" name=\"cel1\" value=\"$cel1\" size=\"30\" 
							onKeyPress=\"return phonenoonly(this);\" />
					</th>
				</tr>
				<tr>
					<th>Email</th>
					<th>:</th>
					<th>
						<input type=\"text\" name=\"email\" value=\"$email\" size=\"25\" 
								onKeyPress=\"return emailonly(this);\"
							 	 />
					</th>
				</tr>
				<tr>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
					<th>
						<img src=\"images/buttons/btn_save_def.gif\" 
							onmousedown=\"this.src='images/buttons/btn_save_click.gif';\"
							onmouseup=\"this.src='images/buttons/btn_save_def.gif';\"
							onmouseout=\"this.src='images/buttons/btn_save_def.gif';\"
							onclick=\"chksave();\">
					</th>
				</tr>			
			</table>
		</div> 
	</div> 
	<div id=\"multidata\" 
		style=\"background:white;z-index:200;position:absolute;left:30px;top:40px;width:400px;height:250px;
			border:3px solid black;background:silver;display:none;overflow:auto;\">
		<span class=\"wintitle\" style=\"width:100%\">Multiple Data</span>
		<br>
		<center>
		<table style=\"width:100%;overflow:hidden;\" cellpadding=\"0\" cellspacing=\"0\">
			<tr><th style=\"font-size:10pt;background:black;color:yellow;font-weight:bold;\">CREW NAME</th></tr>";
			$cntdata=0;
			while($rowsearch=mysql_fetch_array($qrysearch))
			{
				$applicantno1=$rowsearch["APPLICANTNO"];
				$crewname=$rowsearch["CREWNAME"];
				echo "
				<tr title=\"click to select...\" $mouseovereffect
					onclick=\"applicantno.value='$applicantno1';submit();\">
					<td style=\"$styledetails;font-size:12pt;cursor:pointer;\">$crewname</td>
				</tr>
				";
				$cntdata++;
			}
		echo "
			<tr><td onclick=\"document.getElementById('multidata').style.display='none';\" 
				style=\"$styledetails;font-size:12pt;text-align:center;cursor:pointer;color:blue;\">-close-</td></tr>
			<tr><th style=\"font-size:10pt;background:black;color:yellow;font-weight:bold;\">COUNT: $cntdata</th></tr>
		</table>
		</center>
	</div>";
$leftmarg="80px";
$topmarg="80px";
include("veritas/include/ajaxprogress.inc");
echo "
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"actionajax\">
</form>

</body>
</html>
";

?>

