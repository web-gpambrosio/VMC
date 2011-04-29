<?php
include("veritas/connectdb.php");
include("veritas/include/stylephp.inc");
include("veritas/include/functions.inc");

session_start();

//if(isset($_SESSION["user"]))
//	$user=$_SESSION["user"];

if(isset($_SESSION["employeeid"]))
	$employeeid=$_SESSION["employeeid"];
	
$currentdate = date("Y-m-d H:i:s");
$errormsg = "";

if(isset($_POST["ccid"]))
	$ccid=$_POST["ccid"];
else 
	$ccid=$_GET["ccid"];
	
if(isset($_POST["applicantno"]))
	$applicantno=$_POST["applicantno"];
else 
	$applicantno=$_GET["applicantno"];
	
if(isset($_POST["action"]))
	$action=$_POST["action"];
else 
	$action=$_GET["action"];

if(isset($_POST["actiontxt"]))
	$actiontxt = $_POST["actiontxt"];
	
//1 - Current Address
//2 - Contact No.
//3 - Cellphone No.
//4 - Weight
//5 - Height
//6 - Civil Status
//7 - Spouse Name
//8 - Allottee Contact No.
//9 - Availability	

	
// GET ALL DATA TO BE EDITED

$qryeditfields = mysql_query("SELECT CREWCODE,CONCAT(FNAME,', ',GNAME,' ' ,MNAME) AS NAME,c.STATUS,
								c.ADDRESS,c.MUNICIPALITY,c.CITY,c.ZIPCODE,ab.BARANGAY,at.TOWN,ap.PROVINCE,
								ab.BRGYCODE,at.TOWNCODE,ap.PROVCODE,
								CONCAT(ADDRESS,', ',MUNICIPALITY,', ',CITY,' ',ZIPCODE) AS ADDRESS1,
								CONCAT(ADDRESS,', ',ab.BARANGAY,', ',at.TOWN,', ',ap.PROVINCE,' ',ZIPCODE) AS ADDRESS2,
								TELNO,CEL1,CEL2,CEL3,BIRTHDATE,BIRTHPLACE,CIVILSTATUS,WEIGHT,HEIGHT,
								IF(c.RECOMMENDEDBY IS NULL,a.RECOMMENDEDBY,c.RECOMMENDEDBY) AS RECOMMENDEDBY,DRIVERLICENSE
								FROM crew c
								LEFT JOIN applicant a ON a.APPLICANTNO=c.APPLICANTNO
								LEFT JOIN rank r1 ON r1.RANKCODE=a.CHOICE1
								LEFT JOIN addrprovince ap ON ap.PROVCODE=c.PROVINCECODE
								LEFT JOIN addrtown at ON at.TOWNCODE=c.TOWNCODE AND at.PROVCODE=c.PROVINCECODE
								LEFT JOIN addrbarangay ab ON ab.BRGYCODE=c.BARANGAYCODE AND ab.TOWNCODE=c.TOWNCODE AND ab.PROVCODE=c.PROVINCECODE
								WHERE c.APPLICANTNO=$applicantno") or die(mysql_error());

	$roweditfields = mysql_fetch_array($qryeditfields);
	
	$crewcode = $roweditfields["CREWCODE"];
	$crewname = $roweditfields["NAME"];
	
	$xaddress = $roweditfields["ADDRESS"];
	$xmunicipality = $roweditfields["MUNICIPALITY"];
	$xcity = $roweditfields["CITY"];
	$xzipcode = $roweditfields["ZIPCODE"];
	$xbarangaycode = $roweditfields["BRGYCODE"];
	$xtowncode = $roweditfields["TOWNCODE"];
	$xprovincecode = $roweditfields["PROVCODE"];
	
	if (!empty($roweditfields["ADDRESS2"]))
		$crewaddress = $roweditfields["ADDRESS2"];
	else 
		$crewaddress = $roweditfields["ADDRESS1"];
		
	if (empty($crewaddress))
		$crewaddress = $xaddress . " " . $xmunicipality . " " . $xcity . " " . $xzipcode;
		
	$crewdriverlic = $roweditfields["DRIVERLICENSE"];
//	$crewtelno1 = $roweditfields["TELNO"];
	$crewtelno = $roweditfields["TELNO"];
	$crewcel1 = $roweditfields["CEL1"];
	$crewcel2 = $roweditfields["CEL2"];
	$crewcel3 = $roweditfields["CEL3"];
	
	$crewmobile = $crewcel1;
	
	if (!empty($crewcel2))
		$crewmobile .= " / $crewcel2";
	
	if (!empty($crewcel3))
		$crewmobile .= " / $crewcel3";
	
//	$crewbdate = date("dMY",strtotime($roweditfields["BIRTHDATE"]));
//	$crewage = floor((strtotime($currentdate) - strtotime($crewbdate)) / (86400*365.25));
//	$crewbplace = $roweditfields["BIRTHPLACE"];
	
	$crewcivilstatus = $roweditfields["CIVILSTATUS"];

	$crewweight = $roweditfields["WEIGHT"];
	$crewheight = $roweditfields["HEIGHT"];

	$qryavailability = mysql_query("SELECT AVAILABILITY FROM debriefinghdr WHERE CCID=$ccid") or die(mysql_error());
	
	if (mysql_num_rows($qryavailability) > 0)
	{
		$rowavailability = mysql_fetch_array($qryavailability);
		$crewavailability = $rowavailability["AVAILABILITY"];
	}
		
	
// END OF EDITABLE DATA


//1 - Current Address

if (isset($_POST["zcrewaddress"]))
	$zcrewaddress = $_POST["zcrewaddress"];
else 
	$zcrewaddress = $xaddress;

if (isset($_POST["zcrewbarangaycode"]))
	$zcrewbarangaycode = $_POST["zcrewbarangaycode"];
else 
	$zcrewbarangaycode = $xbarangaycode;
	
if (isset($_POST["zcrewtowncode"]))
	$zcrewtowncode = $_POST["zcrewtowncode"];
else 
	$zcrewtowncode = $xtowncode;

if (isset($_POST["zcrewprovincecode"]))
	$zcrewprovincecode = $_POST["zcrewprovincecode"];
else 
	$zcrewprovincecode = $xprovincecode;
	
if (isset($_POST["zcrewzipcode"]))
	$zcrewzipcode = $_POST["zcrewzipcode"];
else 
	$zcrewzipcode = $xzipcode;

//2 - Contact No.
	
if (isset($_POST["zcrewtelno"]))
	$zcrewtelno = $_POST["zcrewtelno"];
else 
	$zcrewtelno = $crewtelno;

//3 - Cellphone No.

if (isset($_POST["zcrewcel1"]))
	$zcrewcel1 = $_POST["zcrewcel1"];
else 
	$zcrewcel1 = $crewcel1;

if (isset($_POST["zcrewcel2"]))
	$zcrewcel2 = $_POST["zcrewcel2"];
else 
	$zcrewcel2 = $crewcel1;

if (isset($_POST["zcrewcel3"]))
	$zcrewcel3 = $_POST["zcrewcel3"];
else 
	$zcrewcel3 = $crewcel3;
	
//4 - Weight

if (isset($_POST["zcrewweight"]))
	$zcrewweight = $_POST["zcrewweight"];
else 
	$zcrewweight = $crewweight;

//5 - Height
	
if (isset($_POST["zcrewheight"]))
	$zcrewheight = $_POST["zcrewheight"];
else 
	$zcrewheight = $crewheight;
	
//6 - Civil Status
	
if (isset($_POST["zcrewcivilstatus"]))
	$zcrewcivilstatus = $_POST["zcrewcivilstatus"];
else 
	$zcrewcivilstatus = $crewcivilstatus;

	switch ($zcrewcivilstatus)
	{
		case "S" : $civilshow = "Single"; break;
		case "M" : $civilshow = "Married"; break;
		case "W" : $civilshow = "Widower"; break;
		case "P" : $civilshow = "Separated"; break;
	}

//7 - Allottee

	$qryfamilyallottee = mysql_query("SELECT c.CREWRELID,FNAME,GNAME,MNAME,TELNO,f.RELATION
							FROM crewfamily c
							LEFT JOIN familyrelation f ON f.RELCODE=c.RELCODE
							WHERE APPLICANTNO=$applicantno AND c.ALLOTTEE=1
							ORDER BY f.POSITION DESC LIMIT 1") or die(mysql_error());
	
	if (mysql_num_rows($qryfamilyallottee) > 0)
	{
		$rowfamilyallottee = mysql_fetch_array($qryfamilyallottee);
		$acrewrelid = $rowfamilyallottee["CREWRELID"];
		$afname = $rowfamilyallottee["FNAME"];
		$agname = $rowfamilyallottee["GNAME"];
		$amname = $rowfamilyallottee["MNAME"];
		$atelno = $rowfamilyallottee["TELNO"];
		$arelation = $rowfamilyallottee["RELATION"];
	
	}

	$anamefull = $afname . ", " . $agname . " " . $amname;
	
if (isset($_POST["allotteeid"]))
	$allotteeid = $_POST["allotteeid"];
	
if (isset($_POST["crewrelidprev"]))
	$crewrelidprev = $_POST["crewrelidprev"];
	
if (isset($_POST["allotteetelno"]))
	$allotteetelno = $_POST["allotteetelno"];
	
// if (isset($_POST["zcrewspousefname"]))
	// $zcrewspousefname = $_POST["zcrewspousefname"];
// else 
	// $zcrewspousefname = $afname;

// if (isset($_POST["zcrewspousegname"]))
	// $zcrewspousegname = $_POST["zcrewspousegname"];
// else 
	// $zcrewspousegname = $agname;

// if (isset($_POST["zcrewspousemname"]))
	// $zcrewspousemname = $_POST["zcrewspousemname"];
// else 
	// $zcrewspousemname = $amname;

//8 - Allottee Contact No.

// if (isset($_POST["zcrewspousetelno"]))
	// $zcrewspousetelno = $_POST["zcrewspousetelno"];
// else 
	// $zcrewspousetelno = $atelno;

	$qryfamily = mysql_query("SELECT CREWRELID,FNAME,GNAME,MNAME,TELNO,f.RELATION
							FROM crewfamily c
							LEFT JOIN familyrelation f ON f.RELCODE=c.RELCODE
							WHERE APPLICANTNO=$applicantno
							ORDER BY f.POSITION DESC") or die(mysql_error());
	
	
//9 - Availability	
	
if (isset($_POST["zcrewavailability"]))
	$zcrewavailability = $_POST["zcrewavailability"];
else 
	$zcrewavailability = $crewavailability;
	
	
	
$content = "";
	
switch ($action)
{
	case "1"	:
		
			//PERMANENT ADDRESS
			
			$qryprovince = mysql_query("SELECT PROVCODE,PROVINCE FROM addrprovince
										ORDER BY PROVINCE") or die(mysql_error());
			
			$provincesel = "<option selected value=\"\">--Select One--</option>";
				while($rowprovince=mysql_fetch_array($qryprovince))
				{
					$provincecode = $rowprovince["PROVCODE"];
					$province = $rowprovince["PROVINCE"];
					
					$selected = "";
					
					if ($zcrewprovincecode == $provincecode)
						$selected = "SELECTED";
					
					$provincesel .= "<option $selected value=\"$provincecode\">$province</option>";
				}
			
			$qrycity = mysql_query("SELECT TOWNCODE,TOWN FROM addrtown WHERE PROVCODE='$zcrewprovincecode'
									ORDER BY TOWN") or die(mysql_error());
			
			$townsel = "<option selected value=\"\">--Select One--</option>";
				while($rowcity=mysql_fetch_array($qrycity))
				{
					$towncode = $rowcity["TOWNCODE"];
					$town = $rowcity["TOWN"];
					
					$selected = "";
					
					if ($zcrewtowncode == $towncode)
						$selected = "SELECTED";
					
					$townsel .= "<option $selected value=\"$towncode\">$town</option>";
				}
			
			$qrybarangay = mysql_query("SELECT BRGYCODE,BARANGAY FROM addrbarangay WHERE PROVCODE='$zcrewprovincecode' AND TOWNCODE='$zcrewtowncode'
										ORDER BY BARANGAY") or die(mysql_error());
			
			$barangaysel = "<option selected value=\"\">--Select One--</option>";
				while($rowbarangay=mysql_fetch_array($qrybarangay))
				{
					$brgycode = $rowbarangay["BRGYCODE"];
					$barangay = $rowbarangay["BARANGAY"];
					
					$selected = "";
					
					if ($zcrewbarangaycode == $brgycode)
						$selected = "SELECTED";
					
					$barangaysel .= "<option $selected value=\"$brgycode\">$barangay</option>";
				}
		
			$content = "
			<table style=\"width:100%;font-size:0.8em;\">
				<tr>
					<td colspan=\"3\" width=\"20%\" align=\"left\" valign=\"top\"><b>Current Address:</b> <br />
						$crewaddress
					</td>
				</tr>
				<tr>
					<td colspan=\"3\">&nbsp;</td>
				</tr>
				<tr>
					<td colspan=\"3\"><b><u>Change To</u></b></td>
				</tr>
				<tr>
					<td>Address</td>
					<td>:</td>
					<td><input type=\"text\" name=\"zcrewaddress\" size=\"60\" value=\"$zcrewaddress\" /></td>			
				</tr>
				<tr>
					<td>Province</td>
					<td>:</td>
					<td><select name=\"zcrewprovincecode\">
							$provincesel
						</select>
					</td>			
				</tr>
				<tr>
					<td>Town/City</td>
					<td>:</td>
					<td><select name=\"zcrewtowncode\">
							$townsel
						</select>
					</td>		
				</tr>
				<tr>
					<td>Barangay</d>
					<td>:</d>
					<td><select name=\"zcrewbarangaycode\">
							$barangaysel
						</select>
					</td>			
				</tr>
				<tr>
					<td>ZIP Code</d>
					<td>:</d>
					<td><input type=\"text\" name=\"zcrewzipcode\" size=\"15\" value=\"$zcrewzipcode\" />
					</td>			
				</tr>
			</table>
			
			";
		
		
		break;
	case "2"	:
		
			$content = "
			<table style=\"width:100%;font-size:0.8em;\">
				<tr>
					<td colspan=\"3\" valign=\"top\"><b>Current Land Line :</b><br />
						$crewtelno
					</td>
				</tr>
				<tr>
					<td colspan=\"3\">&nbsp;</td>
				</tr>
				<tr>
					<td colspan=\"3\"><b><u>Change To</u></b></td>
				</tr>
				<tr>
					<td>Land Line</td>
					<td>:</td>
					<td><input type=\"text\" name=\"zcrewtelno\" size=\"30\" value=\"$zcrewtelno\" /></td>			
				</tr>
			</table>
			
			";

		break;
	case "3"	:
		
			$content = "
			<table style=\"width:100%;font-size:0.8em;\">
				<tr>
					<td colspan=\"3\" valign=\"top\"><b>Current Cellphone No :</b><br />
						$crewmobile
					</td>
				</tr>
				<tr>
					<td colspan=\"3\">&nbsp;</td>
				</tr>
				<tr>
					<td colspan=\"3\"><b><u>Change To</u></b></td>
				</tr>
				<tr>
					<td>Cellphone 1</td>
					<td>:</td>
					<td><input type=\"text\" name=\"zcrewcel1\" size=\"30\" value=\"$zcrewcel1\" /></td>			
				</tr>
				<tr>
					<td>Cellphone 2</td>
					<td>:</td>
					<td><input type=\"text\" name=\"zcrewcel2\" size=\"30\" value=\"$zcrewcel2\" /></td>			
				</tr>
				<tr>
					<td>Cellphone 3</td>
					<td>:</td>
					<td><input type=\"text\" name=\"zcrewcel2\" size=\"30\" value=\"$zcrewcel2\" /></td>			
				</tr>
			</table>
			
			";
		
		break;
	case "4"	:
		
			$content = "
			<table style=\"width:100%;font-size:0.8em;\">
				<tr>
					<td colspan=\"3\" valign=\"top\"><b>Current Weight (kls) :</b><br />
						$crewweight
					</td>
				</tr>
				<tr>
					<td colspan=\"3\">&nbsp;</td>
				</tr>
				<tr>
					<td colspan=\"3\"><b><u>Change To</u></b></td>
				</tr>
				<tr>
					<td>Weight</td>
					<td>:</td>
					<td><input type=\"text\" name=\"zcrewweight\" size=\"15\" value=\"$zcrewweight\" /></td>			
				</tr>
			</table>
			
			";
		
		break;
	case "5"	:
		
			$content = "
			<table style=\"width:100%;font-size:0.8em;\">
				<tr>
					<td colspan=\"3\" valign=\"top\"><b>Current Height (cm) :</b><br />
						$crewheight
					</td>
				</tr>
				<tr>
					<td colspan=\"3\">&nbsp;</td>
				</tr>
				<tr>
					<td colspan=\"3\"><b><u>Change To</u></b></td>
				</tr>
				<tr>
					<td>Height</td>
					<td>:</td>
					<td><input type=\"text\" name=\"zcrewheight\" size=\"15\" value=\"$zcrewheight\" /></td>			
				</tr>
			</table>
			
			";
		
		break;
	case "6"	:
		
			//CIVIL STATUS
			
			$civilstatussel = "<option selected value=\"\">--Select One--</option>";
			
			$crewcivilstatus1 = "crew2" . $zcrewcivilstatus;
			$$crewcivilstatus1 = "SELECTED";	
			
			$civilstatussel .= "<option $crew2S value=\"S\">SINGLE</option>";
			$civilstatussel .= "<option $crew2M value=\"M\">MARRIED</option>";
			$civilstatussel .= "<option $crew2W value=\"W\">WIDOWER</option>";
			$civilstatussel .= "<option $crew2P value=\"P\">SEPARATED</option>";
			
			$content = "
			<table style=\"width:100%;font-size:0.8em;\">
				<tr>
					<td colspan=\"3\" valign=\"top\"><b>Current Civil Status :</b><br />
						$civilshow
					</td>
				</tr>
				<tr>
					<td colspan=\"3\">&nbsp;</td>
				</tr>
				<tr>
					<td colspan=\"3\"><b><u>Change To</u></b></td>
				</tr>
				<tr>
					<td>Civil Status</td>
					<td>:</td>
					<td><select name=\"zcrewcivilstatus\">
							$civilstatussel
						</select>
					</td>
				</tr>
			</table>
			
			";	
			
		break;
	case "7"	:
		
			$content = "
			<table style=\"width:100%;font-size:0.8em;\">
				<tr>
					<td colspan=\"3\" valign=\"top\"><b>Current Allottee Name/Contact No. :</b><br />
						$anamefull / $atelno
					</td>
				</tr>
				<tr>
					<td colspan=\"3\">&nbsp;</td>
				</tr>
				<tr>
					<td colspan=\"3\"><b><u>Change To</u></b></td>
				</tr>
				
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td><select name=\"allotteeid\">
					";
					
						if (mysql_num_rows($qryfamily) > 0)
						{
							while ($rowfamily = mysql_fetch_array($qryfamily))
							{
								$crewrelid = $rowfamily["CREWRELID"];
								$crewallotteefname = $rowfamily["FNAME"];
								$crewallotteegname = $rowfamily["GNAME"];
								$crewallotteemname = $rowfamily["MNAME"];
								
								$crewallotteeshow = $crewallotteefname . ", " . $crewallotteegname . " " . $crewallotteemname;
								
								$content .= "
									<option value=\"$crewrelid\" >$crewallotteeshow</option>
								";
							
							}
						}
					
					$content .= "
						</select>
					</td>
				</tr>
				<tr>
					<td>New Cellphone No</td>
					<td>:</td>
					<td><input type=\"text\" name=\"allotteetelno\" size=\"30\" value=\"\" /></td>			
				</tr>
			</table>
			
			";
		
		break;
	case "8"	:
		
			$content = "
			<table style=\"width:100%;font-size:0.8em;\">
				<tr>
					<td colspan=\"3\" valign=\"top\"><b>Allottee Cellphone No :</b><br />
						$crewspousetelno
					</td>
				</tr>
				<tr>
					<td colspan=\"3\">&nbsp;</td>
				</tr>
				<tr>
					<td colspan=\"3\"><b><u>Change To</u></b></td>
				</tr>
				<tr>
					<td>Allottee Cellphone No</td>
					<td>:</td>
					<td><input type=\"text\" name=\"zcrewspousetelno\" size=\"30\" value=\"$zcrewspousetelno\" /></td>			
				</tr>
			</table>
			
			";
		
		break;
	case "9"	:
		
			$content = "
			<table style=\"width:100%;font-size:0.8em;\">
				<tr>
					<td colspan=\"3\" valign=\"top\"><b>Availability</b><br />
						$crewavailability
					</td>
				</tr>
				<tr>
					<td colspan=\"3\">&nbsp;</td>
				</tr>
				<tr>
					<td colspan=\"3\"><b><u>Change To</u></b></td>
				</tr>
				<tr>
					<td>Availability</td>
					<td>:</td>
					<td><input type=\"text\" name=\"zcrewavailability\" value=\"$zcrewavailability\" 
							onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\"
							onkeydown=\"if(event.keyCode==13){if(this.value!=''){chkdate(this);}}\" />
						&nbsp;&nbsp;(mm/dd/yyyy)
					</td>			
				</tr>
			</table>
			
			";
		
		break;
}
	
switch ($actiontxt)
{
	case "save"	:
		
			switch ($action)
			{
				case "1"	:  //1 - Current Address
				
						if (empty($zcrewprovincecode))
							$zcrewprovincecode = "NULL";
						else 
							$zcrewprovincecode = "'" . $zcrewprovincecode . "'";
							
						if (empty($zcrewtowncode))
							$zcrewtowncode = "NULL";
						else 
							$zcrewtowncode =  "'" . $zcrewtowncode . "'";
							
						if (empty($zcrewbarangaycode))
							$zcrewbarangaycode = "NULL";
						else 
							$zcrewbarangaycode = "'" . $zcrewbarangaycode . "'";
							
						if (empty($zcrewzipcode))
							$zcrewzipcode = "NULL";
						else 
							$zcrewzipcode = "'" . $zcrewzipcode . "'";
				
						if (!empty($zcrewprovincecode) && !empty($zcrewtowncode))
							$deleteold = ",MUNICIPALITY=NULL,CITY=NULL";
						else 
							$deleteold = "";
				
						$qryupdate = mysql_query("UPDATE crew SET ADDRESS='$zcrewaddress',
																PROVINCECODE=$zcrewprovincecode,
																TOWNCODE=$zcrewtowncode,
																BARANGAYCODE=$zcrewbarangaycode,
																ZIPCODE=$zcrewzipcode
																$deleteold
												WHERE APPLICANTNO=$applicantno
												") or die(mysql_error());
						
						$errormsg = "Address updated successfully! <br />";
					
					break;
				case "2"	:  //2 - Contact No.
				
						$qryupdate = mysql_query("UPDATE crew SET TELNO='$zcrewtelno'
												WHERE APPLICANTNO=$applicantno
												") or die(mysql_error());
						
						$errormsg = "Telephone No. updated successfully! <br />";
						
					break;
				case "3"	:  //3 - Cellphone No.
				
						$qryupdate = mysql_query("UPDATE crew SET CEL1='$zcrewcel1',
																 CEL2='$zcrewcel2',
																 CEL3='$zcrewcel3'
												WHERE APPLICANTNO=$applicantno
												") or die(mysql_error());
						
						$errormsg = "Cellphone No. updated successfully! <br />";
					
					break;
				case "4"	:  //4 - Weight
				
						$qryupdate = mysql_query("UPDATE crew SET WEIGHT='$zcrewweight'
												WHERE APPLICANTNO=$applicantno
												") or die(mysql_error());
						
						$errormsg = "Weight updated successfully! <br />";
					
					break;
				case "5"	:  //5 - Height
				
						$qryupdate = mysql_query("UPDATE crew SET HEIGHT='$zcrewheight'
												WHERE APPLICANTNO=$applicantno
												") or die(mysql_error());
						
						$errormsg = "Height updated successfully! <br />";
					
					break;
				case "6"	:  //6 - Civil Status
				
						$qryupdate = mysql_query("UPDATE crew SET CIVILSTATUS='$zcrewcivilstatus'
												WHERE APPLICANTNO=$applicantno
												") or die(mysql_error());
						
						$errormsg = "Civil Status updated successfully! <br />";
					
					break;
				case "7"	:  //7 - Allottee
						// echo "( $allotteeid != $crewrelidprev )UPDATE crewfamily SET ALLOTTEE = 1, TELNO = '$allotteetelno'
												// WHERE APPLICANTNO=$applicantno AND CREWRELID=$allotteeid
												// ";
						$qryupdate = mysql_query("UPDATE crewfamily SET ALLOTTEE = 1, TELNO = '$allotteetelno'
												WHERE APPLICANTNO=$applicantno AND CREWRELID=$allotteeid
												") or die(mysql_error());
						
						if ($allotteeid != $crewrelidprev)
						{
							// echo "UPDATE crewfamily SET ALLOTTEE = 0
													// WHERE APPLICANTNO=$applicantno AND CREWRELID=$crewrelidprev
													// ";
							$qryupdate = mysql_query("UPDATE crewfamily SET ALLOTTEE = 0
													WHERE APPLICANTNO=$applicantno AND CREWRELID=$crewrelidprev
													") or die(mysql_error());
						}
						
						// $qryverify = mysql_query("SELECT * FROM crewfamily WHERE APPLICANTNO=$applicantno AND RELCODE='WFE'") or die(mysql_error());
						
						// if (mysql_num_rows($qryverify) > 0)
						// {
							// $qryupdate = mysql_query("UPDATE crewfamily SET FNAME='$zcrewspousefname',
																	 // GNAME='$zcrewspousegname',
																	 // MNAME='$zcrewspousemname'
													// WHERE APPLICANTNO=$applicantno AND RELCODE='WFE'
													// ") or die(mysql_error());
						// }
						// else 
						// {
							// $qryinsert = mysql_query("INSERT INTO crewfamily(APPLICANTNO,RELCODE,FNAME,GNAME,MNAME,MADEBY,MADEDATE)
														// VALUES($applicantno,'WFE','$zcrewspousefname',$zcrewspousegname','$zcrewspousemname',
														// '$employeeid','$currentdate')
													// ") or die(mysql_error());
							
						// }
						
						$errormsg = "Allottee updated successfully! <br />";
					
					break;
				case "8"	:  //8 - Allottee Contact No.
				
						$qryupdate = mysql_query("UPDATE crewfamily SET TELNO='$zcrewspousetelno'
												WHERE APPLICANTNO=$applicantno AND RELCODE='WFE'
												") or die(mysql_error());
						
						$errormsg = "Allottee Contact No. updated successfully! <br />";
					
					break;
				case "9"	:  //9 - Availability   
				
						if (!empty($zcrewavailability))
							$zcrewavailabilityraw = "'" . date("Y-m-d",strtotime($zcrewavailability)) . "'";
						else 
							$zcrewavailabilityraw = "NULL";
						
						$qryupdate = mysql_query("UPDATE debriefinghdr SET AVAILABILITY=$zcrewavailabilityraw
												WHERE CCID=$ccid") or die(mysql_error());
						
						$errormsg = "Availability Date updated successfully! <br />";
					
					break;
			}
		
		break;
}


echo "
<html>
<head>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>
</head>
<body style=\"overflow:hidden;width:100%;\">

<form name=\"debriefedit\" method=\"POST\">

	$content

	<br />
	
	<center>
	";
	if (empty($errormsg))
		echo "<input type=\"button\" value=\"Save Changes\" onclick=\"actiontxt.value='save';submit();\"/> <br />";
	else 
		echo "<span style=\"font-size:0.8em;font-weight:Bold;color:Green;\">$errormsg</span>";
		
	echo "
	</center>
	
	<br /><br />

	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"action\" value=\"$action\"/>
	<input type=\"hidden\" name=\"ccid\" value=\"$ccid\" />
	<input type=\"hidden\" name=\"applicantno\" value=\"$applicantno\" />
	<input type=\"hidden\" name=\"crewrelidprev\" value=\"$acrewrelid\" />
	
</form>
</body>

</html>
";