<?php

include("veritas/connectdb.php");

session_start();

$currentdate = date('Y-m-d H:i:s');

if(isset($_SESSION["employeeid"]))
	$employeeid=$_SESSION["employeeid"];
	
if (isset($_POST["actiontxt"]))
	$actiontxt = $_POST["actiontxt"];
		
$formname = "formcrewforeign";
$formtitle = "CREW FOREIGN SETUP";


//POSTS

if(isset($_POST["crewcode"]))
	$crewcode=$_POST["crewcode"];

if(isset($_POST["fname"]))
	$fname=$_POST["fname"];

if(isset($_POST["gname"]))
	$gname=$_POST["gname"];

if(isset($_POST["rankcode"]))
	$rankcode=$_POST["rankcode"];

if(isset($_POST["birthdate"]))
	$birthdate=$_POST["birthdate"];

if(isset($_POST["civilstatus"]))
	$civilstatus=$_POST["civilstatus"];

if(isset($_POST["nationality"]))
	$nationality=$_POST["nationality"];

if(isset($_POST["telno"]))
	$telno=$_POST["telno"];

if(isset($_POST["address"]))
	$address=$_POST["address"];

if(isset($_POST["passportno"]))
	$passportno=$_POST["passportno"];

if(isset($_POST["seamanbookno"]))
	$seamanbookno=$_POST["seamanbookno"];

if(isset($_POST["applicantno"]))
	$applicantno=$_POST["applicantno"];

$qryranklist=mysql_query("SELECT RANK,RANKCODE FROM rank WHERE STATUS=1 ORDER BY RANKING") or die(mysql_error());

switch ($actiontxt)
{
	case "datasave":
		$birthdateraw=date("Y-m-d",strtotime($birthdate));
		if(empty($applicantno))
		{
			mysql_query("INSERT INTO crewforeign (CREWCODE,FNAME,GNAME,RANKCODE,BIRTHDATE,CIVILSTATUS,
				NATIONALITY,TELNO,ADDRESS,PASSPORTNO,SEAMANBOOKNO,STATUS,MADEBY,MADEDATE)
				VALUES ('$crewcode','$fname','$gname','$rankcode','$birthdateraw','$civilstatus',
				'$nationality','$telno','$address','$passportno','$seamanbookno',1,'$employeeid','$currentdate')") or die(mysql_error());
		}
		else 
		{
			mysql_query("UPDATE crewforeign SET CREWCODE='$crewcode',FNAME='$fname',GNAME='$gname',RANKCODE='$rankcode',BIRTHDATE='$birthdateraw',
				CIVILSTATUS='$civilstatus',NATIONALITY='$nationality',TELNO='$telno',ADDRESS='$address',PASSPORTNO='$passportno',
				SEAMANBOOKNO='$seamanbookno',MADEBY='$employeeid',MADEDATE='$currentdate' WHERE APPLICANTNO=$applicantno") or die(mysql_error());
		}
	break;
	case "datadelete":
		mysql_query("DELETE FROM crewforeign WHERE APPLICANTNO=$applicantno") or die(mysql_error());
		$crewcode="";
		$fname="";
		$gname="";
		$rankcode="";
		$birthdate="";
		$civilstatus="";
		$nationality="";
		$telno="";
		$address="";
		$passportno="";
		$seamanbookno="";
		$applicantno="";
	break;
}


$qrylist=mysql_query("SELECT CREWCODE,FNAME,GNAME,cf.RANKCODE,ALIAS1,r.RANK,BIRTHDATE,CIVILSTATUS,NATIONALITY,
	TELNO,ADDRESS,PASSPORTNO,SEAMANBOOKNO,cf.MADEBY,cf.MADEDATE,APPLICANTNO
	FROM crewforeign cf
	LEFT JOIN rank r ON cf.RANKCODE=r.RANKCODE
	WHERE cf.STATUS=1 OR cf.STATUS IS NULL
	ORDER BY FNAME,GNAME") or die(mysql_error());

/*END OF LISTINGS*/


echo "
<html>
<head>
<link rel=\"stylesheet\" type=\"text/css\" href=\"../veripro.css\">
<script language=\"javascript\" src=\"../veripro.js\"></script>
<script language=\"javascript\" src=\"popcalendar.js\"></script>

<script>

function checksave(x)
{
	var rem = '';
	
	with ($formname)
	{
		if(crewcode.value=='')
			rem = 'Crew Code';
		if(rankcode.value=='')
		{
			if(rem=='')
				rem = 'Rank';
			else
				rem += ', Rank';
		}
		if(birthdate.value=='')
		{
			if(rem=='')
				rem = 'Birth Date';
			else
				rem += ', Birth Date';
		}
		if(nationality.value=='')
		{
			if(rem=='')
				rem = 'Nationality';
			else
				rem += ', Nationality';
		}
		if(passportno.value=='')
		{
			if(rem=='')
				rem = 'Passport No';
			else
				rem += ', Passport No';
		}
		if(seamanbookno.value=='')
		{
			if(rem=='')
				rem = 'Seaman Book No';
			else
				rem += ', Seaman Book No';
		}
	}
			
	if(rem=='')
	{
		$formname.actiontxt.value='datasave';
		$formname.submit();
	}
	else
		alert('Please CHECK the following: ' + rem + ' before saving!');		

}
function resetdata()
{
	with ($formname)
	{
		crewcode.value='';
		fname.value='';
		gname.value='';
		rankcode.value='';
		birthdate.value='';
		civilstatus.value='';
		nationality.value='';
		telno.value='';
		address.value='';
		passportno.value='';
		seamanbookno.value='';
		applicantno.value='';
	}
}
</script>
</head>
<body>

<form name=\"$formname\" method=\"POST\">
	
	<center>
	<div style=\"width:100%;height:550px;padding:5px 20px 0 20px;overflow:hidden;\">
			
		<div style=\"width:100%;height:250px;float:right;padding:5px 20px 0 20px;overflow:auto;\">
			<br />
			<span class=\"sectiontitle\">$formtitle</span>
			<br />
			
			
			<table class=\"setup\" width=\"50%\" style=\"float:left;\">	
				<tr>
					<th>Crew Code</th>
					<th>:</th>
					<th><input type=\"text\" name=\"crewcode\" value=\"$crewcode\" size=\"15\" maxlength=\"10\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Family Name</th>
					<th>:</th>
					<th><input type=\"text\" name=\"fname\" value=\"$fname\" size=\"50\" maxlength=\"45\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>	
				<tr>
					<th>Given Name</th>
					<th>:</th>
					<th><input type=\"text\" name=\"gname\" value=\"$gname\" size=\"50\" maxlength=\"45\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>	
				<tr>
					<th>Rank</th>
					<th>:</th>
					<th><select name=\"rankcode\">
							<option value=\"\">-Select-</option>";
							while($rowranklist=mysql_fetch_array($qryranklist))
							{
								$rank=$rowranklist['RANK'];
								$rankcode1=$rowranklist['RANKCODE'];
								if($rankcode1==$rankcode)
									$selected="selected";
								else
									$selected="";
								echo "<option $selected value=\"$rankcode1\">$rank</option>\n";
							}
						echo "
						</select>
					</th>
				</tr>		
				<tr>
					<th>Birth Date</th>
					<th>:</th>
					<th><input type=\"text\" name=\"birthdate\" value=\"$birthdate\" onKeyPress=\"return dateonly(this);\" size=\"10\" 
							maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
						<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" 
							onclick=\"popUpCalendar(birthdate, birthdate, 'mm/dd/yyyy', 0, 0);return false;\">
			&nbsp;&nbsp;&nbsp;(mm/dd/yy)
					</th>
				</tr>
				<tr>
					<th>Civil Status</th>
					<th>:</th>
					<th><input type=\"text\" name=\"civilstatus\" value=\"$civilstatus\" size=\"50\" maxlength=\"45\" onKeyPress=\"return alphaonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
			</table>
			<table class=\"setup\" width=\"49%\" style=\"float:right;\">	
				<tr>
					<th>Nationality</th>
					<th>:</th>
					<th><input type=\"text\" name=\"nationality\" value=\"$nationality\" size=\"50\" maxlength=\"45\" onKeyPress=\"return alphaonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>TelNo</th>
					<th>:</th>
					<th><input type=\"text\" name=\"telno\" value=\"$telno\" size=\"50\" maxlength=\"45\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Address</th>
					<th>:</th>
					<th><input type=\"text\" name=\"address\" value=\"$address\" size=\"50\" maxlength=\"45\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Passport No.</th>
					<th>:</th>
					<th><input type=\"text\" name=\"passportno\" value=\"$passportno\" size=\"50\" maxlength=\"45\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Seaman Book No.</th>
					<th>:</th>
					<th><input type=\"text\" name=\"seamanbookno\" value=\"$seamanbookno\" size=\"50\" maxlength=\"45\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
					<th><input type=\"button\" name=\"\" value=\"Save\" onclick=\"checksave();\" />
						<input type=\"button\" name=\"\" value=\"Cancel\" onclick=\"resetdata();$formname.submit();\" />
						<input type=\"button\" name=\"\" value=\"Delete\" onclick=\"if(applicantno.value!=''){actiontxt.value='datadelete';$formname.submit();}else{alert('Nothing to delete!')}\" />
					</th>
				</tr>
			</table>
		</div>
		
		<div style=\"width:100%;height:350px;padding:5px 20px 0 20px;overflow:hidden;\">
			<br />
			<span class=\"sectiontitle\">CURRENT LISTING</span>
			<br />
			
			<div style=\"width:100%;height:225px;padding:0;overflow:auto;\">
				<table width=\"100%\" class=\"listcol\">
					<tr>
						<th>CODE</th>
						<th>FAMILY NAME</th>
						<th>GIVEN NAME</th>
						<th>RANK</th>
						<th>NATIONALITY</th>
						<th>PASSPORT NO</th>
						<th>SEAMAN BOOK</th>
					</tr>
	";
				while ($rowlist=mysql_fetch_array($qrylist))
				{//CREWCODE,FNAME,GNAME,cf.RANKCODE,RANK,BIRTHDATE,CIVILSTATUS,NATIONALITY,
//	TELNO,ADDRESS,PASSPORTNO,SEAMANBOOKNO,cf.MADEBY,cf.MADEDATE,APPLICANTNO
					$crewcode1 = $rowlist["CREWCODE"];
					$fname1 = $rowlist["FNAME"];
					$gname1 = $rowlist["GNAME"];
					$rankcode2 = $rowlist["RANKCODE"];
					$rank1 = $rowlist["RANK"];
					$alias1 = $rowlist["ALIAS1"];
					if(empty($rowlist["BIRTHDATE"]))
						$birthdate1="";
					else 
						$birthdate1 = date("m/d/Y",strtotime($rowlist["BIRTHDATE"]));
					$civilstatus1 = $rowlist["CIVILSTATUS"];
					$nationality1 = $rowlist["NATIONALITY"];
					$telno1 = $rowlist["TELNO"];
					$address1 = $rowlist["ADDRESS"];
					$passportno1 = $rowlist["PASSPORTNO"];
					$seamanbookno1 = $rowlist["SEAMANBOOKNO"];
					$applicantno1 = $rowlist["APPLICANTNO"];
					
					$madeby = $rowlist["MADEBY"];
					$madedate = date("m-d-Y",strtotime($rowlist["MADEDATE"]));
					
					echo "
					<tr ondblclick=\"
						crewcode.value='$crewcode1';
						fname.value='$fname1';
						gname.value='$gname1';
						rankcode.value='$rankcode2';
						birthdate.value='$birthdate1';
						civilstatus.value='$civilstatus1';
						nationality.value='$nationality1';
						telno.value='$telno1';
						address.value='$address1';
						passportno.value='$passportno1';
						seamanbookno.value='$seamanbookno1';
						applicantno.value='$applicantno1';
						\">
						
						<td align=\"center\">$crewcode1</td>
						<td align=\"left\">&nbsp;$fname1</td>
						<td align=\"left\">&nbsp;$gname1</td>
						<td align=\"center\">&nbsp;$rank1</td>
						<td align=\"center\">&nbsp;$nationality1</td>
						<td align=\"center\">&nbsp;$passportno1</td>
						<td align=\"center\">&nbsp;$seamanbookno1</td>
					</tr>
	";
				}
	echo "
				</table>
			</div>
		</div>
		
	</div>
	</center>
	
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"applicantno\" value=\"$applicantno\"/>
</form>

</body>
</html>
";

?>

