<?php

include("veritas/connectdb.php");	
include('veritas/include/stylephp.inc');
$getservername=$_SERVER["SERVER_NAME"];
$getserveraddr=$_SERVER["SERVER_ADDR"];
$formname = "fasttrack";
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
	
if(isset($_POST["fasttrackcode"]))
	$fasttrackcode=$_POST["fasttrackcode"];
	
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
				echo "<script>alert('First Name $addalert not found!');</script>";
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
				echo "<script>alert('Last Name not found!');</script>";
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
		if(empty($yeargraduate))
			$yeargraduatesave="NULL";
		else
			$yeargraduatesave="'".date("Y-m-d",strtotime($yeargraduate))."'";
		if(empty($expelleddate))
			$expelleddatesave="NULL";
		else
			$expelleddatesave="'".date("Y-m-d",strtotime($expelleddate))."'";
		$qrygetfasttrack=mysql_query("SELECT APPLICANTNO FROM crewfasttrack WHERE APPLICANTNO=$applicantno") or die(mysql_error());
		if(mysql_num_rows($qrygetfasttrack)!=0)
			$qrysavefasttrack=mysql_query("UPDATE crewfasttrack SET FASTTRACKCODE='$fasttrackcode',
				BATCHNO='$batchno',SCHOOLID='$schoolid',YEARGRADUATE=$yeargraduatesave,EXPELLEDDATE=$expelleddatesave,
				MADEBY='$employeeid',MADEDATE='$currentdate'
				WHERE APPLICANTNO=$applicantno") or die(mysql_error());
		else 
			$qrysavefasttrack=mysql_query("INSERT INTO crewfasttrack (APPLICANTNO,FASTTRACKCODE,
				BATCHNO,SCHOOLID,YEARGRADUATE,EXPELLEDDATE,MADEBY,MADEDATE) VALUES ($applicantno,
				'$fasttrackcode','$batchno','$schoolid',$yeargraduatesave,$expelleddatesave,'$employeeid',
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
	$qryfasttrackdetails=mysql_query("SELECT FASTTRACKCODE,SCHOOLID,YEARGRADUATE,EXPELLEDDATE,BATCHNO
		FROM crewfasttrack
		WHERE APPLICANTNO=$applicantno") or die(mysql_error());
	$cntfasttrackdetails=mysql_num_rows($qryfasttrackdetails);
	if($cntfasttrackdetails!=0)
	{
		$rowfasttrackdetails=mysql_fetch_array($qryfasttrackdetails);
		$fasttrackcode=$rowfasttrackdetails["FASTTRACKCODE"];
		$schoolid=$rowfasttrackdetails["SCHOOLID"];
		$yeargraduateraw=$rowfasttrackdetails["YEARGRADUATE"];
		if(empty($yeargraduateraw))
			$yeargraduate="";
		else
			$yeargraduate=date("m/d/Y",strtotime($yeargraduateraw));
		$expelleddateraw=$rowfasttrackdetails["EXPELLEDDATE"];
		if(empty($expelleddateraw))
			$expelleddate="";
		else
			$expelleddate=date("m/d/Y",strtotime($expelleddateraw));
		$batchno=$rowfasttrackdetails["BATCHNO"];
	}
	else 
	{
		echo "<script>alert('$gname, $fname is not in Fast Track program yet!')</script>";
	}
	$focusname="fasttrackcode";
}
else 
{
	$cntfasttrackdetails=0;
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
//get fasttrack type
$qryfasttracktype=mysql_query("SELECT FASTTRACKCODE,FASTTRACK
	FROM fasttrack 
	ORDER BY FASTTRACK") or die(mysql_error());
while($rowfasttracktype=mysql_fetch_array($qryfasttracktype))
{
	$fasttrackcodelist=$rowfasttracktype["FASTTRACKCODE"];
	$fasttrackdesc=addslashes($rowfasttracktype["FASTTRACK"]);
	$selected="";
	if($fasttrackcodelist==$fasttrackcode)
		$selected="selected";
	$fasttracktypeselect.="<option $selected value=\"$fasttrackcodelist\">$fasttrackdesc</option>";
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

$disableappno="disabled=\"disabled\"";//disable crew details (only for fast track screen)

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
		fasttrackcode.value='';
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
		//check if crew exists
		if('$applicantno'=='')
		{
			alert('Crew not found!');
		}
		else
		{
			var rem='';
			with(document.$formname)
			{
				if(fasttrackcode.value=='')
				{
					if(rem=='')
						rem='Fast Track Type';
					else
						rem+=', Fast Track Type';
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
		if(document.$formname.gname.value=='' || document.$formname.fname.value=='')
		{
			if(!confirm('Click OK to search. CANCEL to complete Name before searching.'))
				gochk=0;
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
		<span class=\"wintitle\" style=\"font-size:12pt;\">FAST TRACK</span>
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
							onblur=\"if('$gname'!=this.value){datachange(this.name,this.value);}\" />
					</th>
				</tr>
				<tr>
					<th>Last name</th>
					<th>:</th>
					<th><input type=\"text\" name=\"fname\" value=\"$fname\" size=\"30\" 
							onKeyPress=\"return alphaonly(this);\"
							onkeyup=\"this.value=this.value.toUpperCase()\"
							onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
							onblur=\"if('$fname'!=this.value){datachange(this.name,this.value);}\" />
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
			<span class=\"sectiontitle\" style=\"width:90%\">FAST TRACK DETAILS</span>
			<br />
			<br />
			<table class=\"setup\" width=\"100%\" >	
				<tr>
					<th>FastTrack Type</th>
					<th>:</th>
					<th>
						<select name=\"fasttrackcode\" style=\"\" onchange=\"\" style=\"width:200;\">
							<option value=\"\">-Select-</option>
							$fasttracktypeselect
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
								onblur=\"chkdate(this);\" />&nbsp;mm/dd/yy&nbsp;&nbsp;&nbsp;
						<img src=\"images/buttons/btn_save_def.gif\" 
							onmousedown=\"this.src='images/buttons/btn_save_click.gif';\"
							onmouseup=\"this.src='images/buttons/btn_save_def.gif';\"
							onmouseout=\"this.src='images/buttons/btn_save_def.gif';\"
							onclick=\"chksave();\">
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
						<textarea name=\"address\" $disableappno cols=\"30\" rows=\"3\" onKeyPress=\"return remarksonly(this);\">$address</textarea>
					</th>
				</tr>
				<tr>
					<th>Province</th>
					<th>:</th>
					<th>
						<select name=\"provincecode\" $disableappno style=\"width:250;\"
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
						<select name=\"towncode\" $disableappno style=\"width:250;\" onchange=\"actiontxt.value='address';barangaycode.value='';submit();\">
							<option value=\"\">-Select-</option>
							$townselect
						</select>
					</th>
				</tr>
				<tr>
					<th>Barangay</th>
					<th>:</th>
					<th>
						<select name=\"barangaycode\" $disableappno style=\"width:250;\"\">
							<option value=\"\">-Select-</option>
							$barangayselect
						</select>
					</th>
				</tr>
				<tr>
					<th>Rank Type</th>
					<th>:</th>
					<th>
						<select name=\"ranktypecode\" $disableappno style=\"\" onchange=\"\" style=\"width:100;\">
							<option value=\"\">-Select-</option>
							$ranktypeselect
						</select>
					</th>
				</tr>
				<tr>
					<th>Birthdate</th>
					<th>:</th>
					<th>
						<input type=\"text\" name=\"birthdate\" $disableappno value=\"$birthdate\" size=\"10\" 
								onKeyPress=\"return dateonly(this);\"
								onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
								onblur=\"chkdate(this);\" />&nbsp;mm/dd/yy
					</th>
				</tr>
				<tr>
					<th>TelNo</th>
					<th>:</th>
					<th>
						<input type=\"text\" name=\"telno\" $disableappno value=\"$telno\" size=\"30\" 
							onKeyPress=\"return phonenoonly(this);\" />
					</th>
				</tr>
				<tr>
					<th>CellNo</th>
					<th>:</th>
					<th>
						<input type=\"text\" name=\"cel1\" $disableappno value=\"$cel1\" size=\"30\" 
							onKeyPress=\"return phonenoonly(this);\" />
					</th>
				</tr>
				<tr>
					<th>Email</th>
					<th>:</th>
					<th>
						<input type=\"text\" name=\"email\" $disableappno value=\"$email\" size=\"25\" 
								onKeyPress=\"return emailonly(this);\"
							 	onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
				</tr>			
			</table>
		</div> 
	</div> 
	<div id=\"multidata\" 
		style=\"background:white;z-index:200;position:absolute;left:30px;top:20px;width:400px;height:250px;
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

