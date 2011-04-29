<?php
include('veritas/connectdb.php');
include("veritas/include/stylephp.inc");
include("veritas/include/functions.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');
$basedirid = "idpics/";
$basedirdocs = "docimg/";
// $basedirdocs = "docimages/";

$datenow = date("Y-m-d");

if (isset($_SESSION["employeeid"]))
	$employeeid = $_SESSION["employeeid"];
else 
	$employeeid = "";

if (isset($_POST["actiontxt"]))
	$actiontxt = $_POST["actiontxt"];
	
if (isset($_POST["ccid"]))
	$ccid = $_POST["ccid"];

if (isset($_POST["getapplicantno"]))
	$getapplicantno = $_POST["getapplicantno"];
	
if (isset($_POST["applicantno"]))
	$applicantno = $_POST["applicantno"];
else 
	$applicantno = $_GET["applicantno"];
	
if(empty($getapplicantno))
	$getapplicantno=$applicantno;
	
if (isset($_POST["currentrankcode"]))
	$currentrankcode = $_POST["currentrankcode"];
	
if (isset($_POST["selrankcode"]))
	$selrankcode = $_POST["selrankcode"];
	
if (isset($_POST["selvesselcode"]))
	$selvesselcode = $_POST["selvesselcode"];
	
if (isset($_POST["seletd"]))
	$seletd = $_POST["seletd"];
	
if (isset($_POST["assignedccidhidden"]))
	$assignedccidhidden = $_POST["assignedccidhidden"];
	
if (isset($_POST["searchkey"]))
	$searchkey = $_POST["searchkey"];
	
if (isset($_POST["searchby"]))
	$searchby = $_POST["searchby"];
	
if ($searchkey == "")
	$disablesearch = "disabled=\"disabled\"";
else 
	$disablesearch = "";

	
//POSTS FOR "UPDATE TRAINING"

if (isset($_POST["traincodehidden"]))
	$traincodehidden = $_POST["traincodehidden"];

if (isset($_POST["rankcodehidden"]))
	$rankcodehidden = $_POST["rankcodehidden"];

if (isset($_POST["traindatehidden"]))
	$traindatehidden = $_POST["traindatehidden"];

if (isset($_POST["gradehidden"]))
	$gradehidden = $_POST["gradehidden"];


//POSTS FOR "ENDORSE"

if (isset($_POST["endorsetraincode"]))
	$endorsetraincode = $_POST["endorsetraincode"];
	
//FOR CREW CHANGE UPDATE
if (isset($_POST["getccid"]))
	$getccid = $_POST["getccid"];

if (isset($_POST["getdateemb"]))
	$getdateemb = $_POST["getdateemb"];

if (isset($_POST["getdatedisemb"]))
	$getdatedisemb = $_POST["getdatedisemb"];

//POSTS	FOR "CANCEL TRAINING"
	
//if (isset($_POST['canceltraincode']))
//	$canceltraincode = $_POST['canceltraincode'];

//if (isset($_POST['cancelschedid']))
//	$cancelschedid = $_POST['cancelschedid'];

			
$chkrecommend = "";
$chkupgrade = "";
$chkpromotion = "";
	
$disablechecklist = 0;
$showmultiple = "display:none;";
$multiple = 0;


switch ($actiontxt)
{
	case "find"	:
		
		$whereappno = "";
		$whereappno2 = "";
		$errormsg = "";
		
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
	
	case "updatetraining":
		
			if ($traindatehidden != "")
				$traindatehidden = "'" . $traindatehidden . "'";
			else 
				$traindatehidden = "NULL";
				
			if ($gradehidden == "")
				$gradehidden = 0; 
					

					
			// crewtrainingnosched -- BOTH INHOUSE AND OUTSIDE, NO SCHED ID!!!
			
			$qrycrewtrainingnoschedinsert = mysql_query("INSERT INTO crewtrainingnosched(APPLICANTNO,TRAINCODE,TRAINDATE,RANKCODE,
													GRADE,MADEBY,MADEDATE)
													VALUES($applicantno,'$traincodehidden',$traindatehidden,'$rankcodehidden',
															$gradehidden,'$employeeid','$currentdate')
											") or die(mysql_error());

			
		break;
		
	case "endorse":
			
			if (!empty($_POST["chkbxtrain"]))
			{
				$inpart = "";
				foreach ($_POST["chkbxtrain"] as $boxname=>$traincodeendorse)
				{
					if (empty($inpart))
						$inpart .= "(" . $applicantno . ",'" . $traincodeendorse ."','" . $employeeid . "','" . $currentdate . "')";
					else 
						$inpart .= ",(" . $applicantno . ",'" . $traincodeendorse ."','" . $employeeid . "','" . $currentdate . "')";
				}
				
	//			echo "INSERT INTO trainingendorsement(APPLICANTNO,TRAINCODE,MADEBY,MADEDATE) 
	//										VALUES $inpart
	//								";
				
				$qryendorse = mysql_query("INSERT INTO trainingendorsement(APPLICANTNO,TRAINCODE,MADEBY,MADEDATE) 
											VALUES $inpart
									") or die(mysql_error());
				
	//			
	//			$qryendorse = mysql_query("INSERT INTO trainingendorsement(APPLICANTNO,TRAINCODE,MADEBY,MADEDATE) 
	//										VALUES($applicantno,'$endorsetraincode','$employeeid','$currentdate')
	//								") or die(mysql_error());
			}
		
		break;
		
	case "unendorse":
		
//			$qryunendorse = mysql_query("DELETE FROM trainingendorsement 
//										WHERE APPLICANTNO=$applicantno AND TRAINCODE='$endorsetraincode'
//										AND STATUS=0 AND SCHEDID IS NULL") or die(mysql_error());
			if (!empty($_POST["chkbxcancel"]))
			{
				$inpart2 = "";
				foreach ($_POST["chkbxcancel"] as $boxname2=>$traincodecancel)
				{
					if(empty($inpart2))
						$inpart2 .= "('" . $traincodecancel . "'";
					else 
						$inpart2 .= ",'" . $traincodecancel . "'";
				}
				
				if(!empty($inpart2))
					$inpart2 .= ")";
			
				$qryunendorse = mysql_query("DELETE FROM trainingendorsement 
											WHERE APPLICANTNO=$applicantno AND TRAINCODE IN $inpart2
											AND STATUS=0 AND SCHEDID IS NULL") or die(mysql_error());
			}
		
		break;
		
	case "updatestatus":
		
			$zrecommend = 0;
			$zupgrading = 0;
			$zpromotion = 0;

				
			if (isset($_POST['recommendation']))
			{
				$chkrecommend = "checked=\"checked\"";
				$zrecommend = 1;
			}
			if (isset($_POST['upgrading']))
			{
				$chkupgrade = "checked=\"checked\"";
				$zupgrading = 1;
			}
			else 
			{
				$chkupgrade = "";
				$zupgrading = 0;
			}
			if (isset($_POST['promotion']))
			{
				$chkpromotion = "checked=\"checked\"";
				$zpromotion = 1;
			}
			else 
			{
				$chkpromotion = "";
				$zpromotion = 0;
			}

			$qryverify = mysql_query("SELECT RECOMMENDED,UPGRADING,PROMOTION 
									FROM trainingendorsestatus WHERE APPLICANTNO=$applicantno
									") or die(mysql_error());
			
			if (mysql_num_rows($qryverify) > 0)
			{
				$qryupdate = mysql_query("UPDATE trainingendorsestatus SET RECOMMENDED = $zrecommend,
																UPGRADING = $zupgrading,
																PROMOTION = $zpromotion,
																MADEBY = '$employeeid',
																MADEDATE = '$currentdate'
										WHERE APPLICANTNO=$applicantno
										") or die(mysql_error());
			}
			else 
			{
				$qryinsert = mysql_query("INSERT INTO trainingendorsestatus(APPLICANTNO,RECOMMENDED,UPGRADING,PROMOTION,MADEBY,MADEDATE) VALUES($applicantno,$zrecommend,$zupgrading,$zpromotion,'$employeeid','$currentdate')") or die(mysql_error());
			}
		
		break;
		
		case "updateendorse":
			
			$qryverify = mysql_query("SELECT VESSELCODE,ETD,RANKCODE
									FROM trainingendorsestatus WHERE APPLICANTNO=$applicantno
									") or die(mysql_error());
			
			if(!empty($seletd))
				$seletd1 = "'" . date("Y-m-d",strtotime($seletd)) . "'";
			else 
				$seletd1 = "NULL";
			
			if (mysql_num_rows($qryverify) > 0)
			{
				$qryupdate = mysql_query("UPDATE trainingendorsestatus SET VESSELCODE='$selvesselcode',
																RANKCODE='$selrankcode',
																ETD=$seletd1,
																ESTIMATEBY='$employeeid',
																ESTIMATEDATE='$currentdate'
										WHERE APPLICANTNO=$applicantno
										") or die(mysql_error());
			}
			else 
			{
				$qryinsert = mysql_query("INSERT INTO trainingendorsestatus VALUES($applicantno,0,0,0,'$selvesselcode',
											$seletd1,'$selrankcode','$employeeid','$currentdate','$employeeid','$currentdate')") or die(mysql_error());
			}
			
		break;
	
		case "updatecrewchange":
			$getdateembraw=date("Y-m-d",strtotime($getdateemb));
			$getdatedisembraw=date("Y-m-d",strtotime($getdatedisemb));
			if(empty($assignedccidhidden))
			{
				$qrysave=mysql_query("INSERT INTO crewchange (APPLICANTNO,BATCHNO,VESSELCODE,RANKCODE,DATEEMB,DATEDISEMB,
					MADEBY,MADEDATE) VALUES($getapplicantno,999,'$selvesselcode','$selrankcode','$getdateembraw','$getdatedisembraw',
					'$employeeid','$currentdate')") or die(mysql_error());
				$qryfind=mysql_query("SELECT CCID FROM crewchange 
					WHERE MADEBY='$employeeid' AND MADEDATE='$currentdate'") or die(mysql_error());
				$rowfind=mysql_fetch_array($qryfind);
				$newccid=$rowfind["CCID"];
				$qryadd=mysql_query("INSERT INTO crewchangerelation (CCID,CCIDEMB) VALUES($getccid,$newccid)") or die(mysql_error());
			}
			else 
			{
				$qrysave=mysql_query("UPDATE crewchange SET VESSELCODE='$selvesselcode',RANKCODE='$selrankcode',
					DATEEMB='$getdateembraw',DATEDISEMB='$getdatedisembraw',
					MADEBY='$employeeid',MADEDATE='$currentdate'
					WHERE CCID=$assignedccidhidden") or die(mysql_error());
				$qrydelrelation=mysql_query("DELETE FROM crewchangerelation WHERE CCIDEMB=$assignedccidhidden")or die(mysql_error());
				$qryadd=mysql_query("INSERT INTO crewchangerelation (CCID,CCIDEMB) VALUES($getccid,$assignedccidhidden)") or die(mysql_error());
			}
		break;
}


	$qrystatus = mysql_query("SELECT * FROM trainingendorsestatus WHERE APPLICANTNO=$applicantno") or die(mysql_error());
	
	if (mysql_num_rows($qrystatus) > 0)
	{
		$rowstatus = mysql_fetch_array($qrystatus);
		
		if ($rowstatus["RECOMMENDED"] == 1)
			$chkrecommend = "checked=\"checked\"";
		if ($rowstatus["UPGRADING"] == 1)
			$chkupgrade = "checked=\"checked\"";
		if ($rowstatus["PROMOTION"] == 1)
			$chkpromotion = "checked=\"checked\"";
			
		$endorsedvslcode = $rowstatus["VESSELCODE"];
		$endorsedrnkcode = $rowstatus["RANKCODE"];
		if(!empty($rowstatus["ETD"]))
			$endorsedetd = date("m/d/Y",strtotime($rowstatus["ETD"]));
		else 
			$endorsedetd = "";
		
	}

$appstatus = "";
$currentrankcode2 = "";
include("include/crewsummary.inc");

$qryvessel = mysql_query("SELECT VESSELCODE,LEFT(VESSEL,15) AS VESSEL FROM vessel WHERE STATUS=1 ORDER BY VESSEL") or die(mysql_error());
$qryrank = mysql_query("SELECT RANKCODE,LEFT(RANK,15) AS RANK FROM rank WHERE STATUS=1 ORDER BY RANKING") or die(mysql_error());

if($appstatus=="PROMOTED ONBOARD" || $appstatus=="ONBOARD")
{
	echo "<script>alert('Crew is ONBOARD.');</script>";
	$disableonboard="disabled=\"disabled\"";
}
echo "
<html>\n
<head>\n
<title>Training Checklist and Enrollment</title>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script language=\"javascript\" src=\"popcalendar.js\"></script>
<script type='text/javascript' src='veripro.js'></script>

<script language=\"JavaScript\"> 
function updatecrew()
{
	var rem='';
	var getvalue='';
	with(document.formtrainendorse)
	{
		var getvesselcode=selvesselcode.value;
		var getrankcode=selrankcode.value;
		var getetd=seletd.value.replace('/','_');
		if(getvesselcode=='')
		{
			rem='Vessel';
		}
		if(getrankcode=='')
		{
			if(rem=='')
				rem='Rank';
			else
				rem=rem + ', Rank';
		}
		if(rem=='')
		{
			getvalue=window.showModalDialog('crewtrainingendorsemodal.php?selvesselcode='+getvesselcode+'&selrankcode='+getrankcode+'&seletd='+getetd+'&crewname=$crewname&applicantno=$applicantno','',
				'dialogHeight:350px; dialogWidth:700px;status=no;');
		}
		else
			alert(rem + ' should not be NULL!');
		
		if(getvalue!='')
		{
			var getarray=getvalue.split('|');
			getccid.value=getarray[0];
			getdateemb.value=getarray[1];
			getdatedisemb.value=getarray[2];
			actiontxt.value='updatecrewchange';
			submit();
		}
	}
}
</script>
</head>
<body onload=\"\" style=\"overflow:hidden;background-color:White;\">\n

<form name=\"formtrainendorse\" method=\"POST\">\n

	<div style=\"width:100%;height:530px;\">
	
		<div style=\"width:100%;height:170px;margin:3 5 3 5;background-color:#004000;\">
			
			<div style=\"width:79%;height:100px;float:left;border:1px solid Black;\">
			";
				$stylehdr = "style=\"font-size:0.75em;font-weight:Bold;color:White;\"";
				$styledtl2 = "style=\"font-size:0.9em;font-weight:Bold;color:Yellow;\"";
				
			echo "
				<div style=\"width:100%;height:70px;\">
					<table style=\"width:100%;background-color:Black;\">
						<tr>
							<td $stylehdr>NAME:&nbsp; <span style=\"font-size:1em;color:Blue;\">$crewftdesc $crewscholardesc $crewutilityshow</span><br />
								<span style=\"font-size:1.2em;color:Yellow;\">$crewname</span>
							</td>
							<td $stylehdr>APPLICANT NO: <br /><span style=\"font-size:1.2em;color:Yellow;\">$applicantno</span></td>
							<td $stylehdr>CREW CODE: <br /><span style=\"font-size:1.2em;color:Yellow;\">$crewcode</span></td>
						</tr>
					</table>
					
					<table style=\"width:45%;float:left;\">
						<tr>
							<td colspan=\"4\">&nbsp;</td>
						</tr>
						<tr>
							<td $stylehdr>LAST VESSEL</td>
							<td $stylehdr>:</td>
							<td $styledtl2>$lastvessel</td>
						</tr>
						<tr>
							<td $stylehdr>LAST RANK</td>
							<td $stylehdr>:</td>
							<td $styledtl2>$lastrankalias</td>
						</tr>
						<tr>
							<td $stylehdr>DISEMBARK</td>
							<td $stylehdr>:</td>
							<td $styledtl2>$lastdisembdate</td>
						</tr>
					</table>
					
					<table style=\"width:54%;float:right;\">
						<tr>
							<td colspan=\"4\" style=\"font-size:0.8em;font-weight:Bold;color:Magenta;text-align:right;\">
								<i>Note: Any changes made will directly affect CREW CHANGE PLAN</i>
							</td>
						</tr>
				
						<tr>
							<td $stylehdr>ASSIGNED VESSEL</td>
							<td $stylehdr>:</td>
							<td $styledtl2>$assignedvessel</td>
							<td $styledtl2>
								<select name=\"selvesselcode\" style=\"font-size:0.8em;\" $disableonboard>
									<option value=\"\">--Select Vessel--</option>
								";
								
								if(empty($endorsedvslcode))
								{
									if ($appstatus == "ONBOARD")
									{
										$assignedvesselcode = $lastvesselcode;
									}
								}
			
									while($rowvessel=mysql_fetch_array($qryvessel))
									{
										$vslcode=$rowvessel['VESSELCODE'];
										$vsl=$rowvessel['VESSEL'];
										
										$selectvessel = "";
										if ($vslcode == $assignedvesselcode)
											$selectvessel = "SELECTED";
											
										echo "<option $selectvessel value=\"$vslcode\">$vsl</option>";
									}
								
						echo "
								</select>
							
							</td>
						</tr>
						<tr>
							<td $stylehdr>ASSIGNED RANK</td>
							<td $stylehdr>:</td>
							<td $styledtl2>$assignedrankalias</td>
							<td $styledtl2>
								<select name=\"selrankcode\" style=\"font-size:0.8em;\" $disableonboard>
									<option value=\"\">--Select Rank--</option>
								";
						
								if(empty($endorsedrnkcode))
								{
									if ($appstatus == "ONBOARD")
									{
										$assignedrankcode = $lastrankcode;
									}
								}

									
									while($rowrank=mysql_fetch_array($qryrank))
									{
										$rnk=$rowrank['RANK'];
										$rnkcode=$rowrank['RANKCODE'];
										
										$selectrank = "";
										if ($rnkcode == $assignedrankcode)
											$selectrank = "SELECTED";
											
										echo "<option $selectrank value=\"$rnkcode\">$rnk</option>";
									}
									echo "
								</select>
							
							</td>
						</tr>
						<tr>
							<td $stylehdr>ETD</td>
							<td $stylehdr>:</td>
							<td $styledtl2>$assignedetd</td>
							<td $styledtl2>
							";
//								if(!empty($endorsedetd))
//									$assignedetd2 = $endorsedetd;
								
							echo "
								<input $disableonboard type=\"text\" name=\"seletd\" value=\"$assignedetd2\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
								<img $disableonboard src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(seletd, seletd, 'mm/dd/yyyy', 0, 0);return false;\">
								&nbsp;&nbsp;&nbsp;
								<input type=\"button\" value=\"Update\" onclick=\"updatecrew();\" $disableonboard
									style=\"font-size:0.8em;font-weight:Bold;color:Lime;background-color:Black;border:thin solid white;cursor:pointer;\" />
							</td>
						</tr>
					</table>
				</div>
				<hr />
				
				<div style=\"width:100%;height:70px;overflow:auto;\">
					<table class=\"listcol\" >
						<tr>
							<th>VESSEL</th>
							<th>RANK</th>
							<th>EMBARK</th>
							<th>DISEMBARK</th>
							<th>REASON</th>
						</tr>
						
						 $content
	 
					</table>
				</div>

			</div>
			
			<div style=\"width:18%;float:right;color:Orange;\">
	";
				$dirfilename = $basedirid . $applicantno . ".JPG";
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
			
				
		
				<span style=\"font-size:0.8em;color:Yellow;font-weight:Bold;\">
					<input type=\"checkbox\" name=\"recommendation\" $chkrecommend /> With Recommendation <br />
					<input type=\"checkbox\" name=\"upgrading\" $chkupgrade /> Upgrading <br />
					<input type=\"checkbox\" name=\"promotion\" $chkpromotion /> Promotion <br />
				</span>
				
				<input type=\"button\" value=\"Update\" $disableonboard onclick=\"if(confirm('Update Training status?')) 
								{applicantno.value='$applicantno';actiontxt.value='updatestatus';submit();}\"
					style=\"font-size:0.7em;font-weight:Bold;color:Orange;background-color:Black;border:thin solid white;cursor:pointer;\" />
			</div>
			
		</div>
		
		
		
		<div style=\"width:100%;height:360px;background-color:#DCDCDC;margin:3 5 0 5;border:1px solid Black;overflow:auto;\">
";

if ($assignedrankcode != "---")
{
	$currentrankcode = $assignedrankcode;
}

$qrygetrank = mysql_query("SELECT RANK,ALIAS1 FROM rank WHERE RANKCODE='$currentrankcode'") or die(mysql_error());
$rowgetrank = mysql_fetch_array($qrygetrank);

$rankdisplay = $rowgetrank["RANK"];
$rankdisplay2 = $rowgetrank["ALIAS1"];

include("veritas/include/checklist.inc");

			$btnendorse = "
							<tr>
								<td colspan=\"5\" align=\"center\">
									<input type=\"button\" value=\"Endorse ALL selected Trainings!\" 
										style=\"border:thin solid Black;background-color:Green;color:White;font-weight:Bold;cursor:pointer;\"
										onclick=\"if(confirm('ALL SELECTED Trainings will be ENDORSED. Please Confirm?'))
										{applicantno.value='$applicantno';actiontxt.value='endorse';submit();}\"/>
									<input type=\"button\" value=\"Cancel all SELECTED Endorsements\" 
										style=\"border:thin solid Black;background-color:Red;color:White;font-weight:Bold;cursor:pointer;\"
										onclick=\"if(confirm('Endorsements of ALL SELECTED Trainings will be CANCELLED. Please Confirm?'))
										{applicantno.value='$applicantno';actiontxt.value='unendorse';submit();}\"/>
								
								</td>
							</tr>
			";
							
			if (mysql_num_rows($qrychecklist) > 0)
			{
				//#FDE3B0
				echo "
				<div style=\"width:100%;height:100%;\">
					<span class=\"sectiontitle\">TRAINING CHECKLIST -- [ $rankdisplay ]</span>
					<table width=\"100%\" style=\"font-size:0.8em;background-color:White;border:1px solid black;\">
						<tr>
							<td colspan=\"5\" align=\"center\">
								<span style=\"font-size:1.2em;font-weight:Bold;color:Blue;\">NOTE: Please CHECK ALL Trainings you want to ENDORSE or CANCEL, then click below the appropriate action to be taken.<br />
									Trainings that are currently endorsed are shaded in <span style=\"background-color:yellow;\"> YELLOW </span>. 
								</span>
							</td>
						</tr>
						<tr style=\"font-weight:bold;background-color:Black;color:White;\">
							<td width=\"40%\" align=\"center\">COURSE</td>
							<td width=\"10%\" align=\"center\">TRAINING DATE</td>
							<td width=\"30%\" align=\"center\">STATUS</td>
							<td width=\"5%\" align=\"center\">&nbsp;</td>
							<td width=\"15%\" align=\"center\">ACTION</td>
						</tr>
						";
				
//						if ($applicantno != "")
//						{
							$tmpgroup = "";
							$trainneeds = 1;

							while ($rowchecklist = mysql_fetch_array($qrychecklist))
							{
								$chkidno = $rowchecklist["IDNO"];
								$chkpos = $rowchecklist["POS"];
								$chktraincode = $rowchecklist["TRAINCODE"];
								$chktraining = $rowchecklist["TRAINING"];
								$chkalias = $rowchecklist["ALIAS"];
								$chkrankcode = $rowchecklist["RANKCODE"];
								$chkrequired = $rowchecklist["REQUIRED"];
								
								if ($chkrequired == 0)
									$chkrequiredshow = "N";
								else 
									$chkrequiredshow = "Y";
									
								$chkcoursetypecode = $rowchecklist["COURSETYPECODE"];
								$chkcoursetype = $rowchecklist["COURSETYPE"];
								$chkdoccode = $rowchecklist["DOCCODE"];
								$chkstatus = $rowchecklist["STATUS"];
								
								if ($chkcoursetypecode != $tmpgroup)
								{
									$style = "style=\"background-color:Navy;color:Orange;font-weight:Bold;font-size:1.2em;text-align:center;\"";
									
//									if ($chkpos == "2" && $trainneeds == 1)
//									{
//										echo "
//										$btnendorse
//										<tr><td colspan=\"6\"><br /><hr /><br />
//										<span class=\"sectiontitle\" style=\"font-size:1em;\">ADDITIONAL TRAINING NEEDS</span></td></tr>";
//										
//										$trainneeds ++;
//									}
//									
//									if ($chkpos == "2")
//										$style = "style=\"background-color:Gray;color:White;font-weight:Bold;font-size:1.2em;text-align:center;\"";
										
									echo "<tr><td colspan=\"6\" $style>$chkcoursetype</td></tr>";
								}
								
								$status = "";
								$statusshow = "";
								$datefrom = "";
								$dateto = "";
								$certshow = "";
								$docshow = "";
								
								
								$enrolled = 0;
								$endorsed = 0;
								
								$qrycheckendorsement = mysql_query("SELECT SCHEDID,STATUS FROM trainingendorsement
																	WHERE APPLICANTNO=$applicantno AND TRAINCODE='$chktraincode'
															") or die(mysql_error());
								
								if (mysql_num_rows($qrycheckendorsement) > 0) //ENDORSED ALREADY
								{
									$endorsed = 1;
									$rowcheckendorsement = mysql_fetch_array($qrycheckendorsement);
									
									if (!empty($rowcheckendorsement["SCHEDID"])) //ENROLLED ALREADY
										$enrolled = 1;
								}

								$qrychecklistinhouse = mysql_query("
												SELECT *,															
												IF (DATEFROM > CURRENT_DATE,'ENROLLED',
												    IF (DATETO < CURRENT_DATE,
												        'FINISHED',
												        'ON-GOING'
												        )
												) AS STATUS2
												FROM (
													SELECT 'IN' AS TYPE,ct.SCHEDID,th.DATEFROM,th.DATETO,th.STATUS AS TRAINSTATUS,ct.STATUS
													FROM crewtraining ct
													LEFT JOIN trainingschedhdr th ON th.SCHEDID=ct.SCHEDID
													WHERE ct.APPLICANTNO=$applicantno AND th.TRAINCODE='$chktraincode'
												
													UNION
												
													SELECT 'OLD' AS TYPE,NULL,TRAINDATE,TRAINDATE,NULL,1
													FROM crewtrainingold ctold
													WHERE ctold.APPLICANTNO=$applicantno AND ctold.TRAINCODE='$chktraincode'
													
													UNION
													
													SELECT 'UPD' AS TYPE,NULL,TRAINDATE,TRAINDATE,NULL,1
													FROM crewtrainingnosched ctnosched
													WHERE ctnosched.APPLICANTNO=$applicantno AND ctnosched.TRAINCODE='$chktraincode'
												) x
												ORDER BY DATEFROM DESC
											") or die(mysql_error());
								
								$historycnt = mysql_num_rows($qrychecklistinhouse);
								
								if (mysql_num_rows($qrychecklistinhouse) > 0)
								{
									$rowchecklistinhouse = mysql_fetch_array($qrychecklistinhouse);
									
									$schedid = $rowchecklistinhouse["SCHEDID"];
									$datefrom = date("dMY",strtotime($rowchecklistinhouse["DATEFROM"]));
									$dateto = date("dMY",strtotime($rowchecklistinhouse["DATETO"]));
									
									if ($datefrom != $dateto)
										$dateshow = $datefrom . " to " . $dateto;
									else 
										$dateshow = $datefrom;
									
									$trainstatus = $rowchecklistinhouse["TRAINSTATUS"]; //IF TRAINING IS CANCELLED
									$status = $rowchecklistinhouse["STATUS"]; //COMPLETED;INCOMPLETE
									$status2 = $rowchecklistinhouse["STATUS2"]; //ENROLLED;FINISHED;ON-GOING
									$type = $rowchecklistinhouse["TYPE"];
									
									if ($status == 1)
										$completed = "COMPLETED";
									elseif ($status == 2)
										$completed = "INCOMPLETE";
									else 
										$completed = "NOT POSTED YET";
									
									$stylestatus = "";
									$disabledendorse = 0;	
									
									switch ($status2)
									{
										case "ENROLLED"	:
											
												$statusshow = "<span style=\"color:Yellow;background-color:Black;font-weight:Bold;\">" . "&nbsp;ENROLLED " . "</span><i>&nbsp;&nbsp;&nbsp;" .$dateshow . "</i>";
												$disabledendorse = 1;
												
											break;
										case "FINISHED"	:
											
//												$statusshow = "<i>Last&nbsp;"  .$dateto . "</i>&nbsp;<span style=\"color:White;background-color:Red;font-weight:Bold;\"></span>";
												$statusshow = "";
												
											
											break;
										case "ON-GOING"	:
											
												$statusshow = "<span style=\"color:Lime;background-color:Black;font-weight:Bold;\">" . "&nbsp;ON-GOING " . "</span><i>&nbsp;&nbsp; Until&nbsp;" . $dateto . "</i>";
												$disabledendorse = 2;
												
											break;
									}
								}

								
								//CHECK IF DOCUMENT IS READY FOR VIEWING...
								
								$dirfilename1 = $basedirdocs . $applicantno . "/C/" . $chkdoccode . ".pdf";
								
								if (checkpath($dirfilename1))
								{
									$docshow = "<a href=\"#\" style=\"font-weight:Bold;color:Navy;\"
												onclick=\"openWindow('$dirfilename1', '$chkdoccode' ,700, 500);\" title=\"Click to View.\">
												[VIEW]</a>";
								}
								else 
									$docshow = "------";

								$styledtl = " style=\"font-size:1em;color:Black;border-bottom:1px dashed gray;border-right:1px dashed Gray;\"";	
								
								//CHECK IF THERE'S AN ENTRY IN CREWCERTSTATUS

								$qrycheckcertstatus = mysql_query("SELECT if(x.APPLICANTNO IS NULL,'UPDATE','EXISTS') AS STATUS,cc.*
													FROM crewcertstatus cc
													LEFT JOIN (
														SELECT 'IN' AS TYPE,ct.APPLICANTNO,ct.SCHEDID,th.DATEFROM,th.DATETO,th.STATUS AS TRAINSTATUS,ct.STATUS
														FROM crewtraining ct
														LEFT JOIN trainingschedhdr th ON th.SCHEDID=ct.SCHEDID
														WHERE ct.APPLICANTNO=$applicantno AND th.TRAINCODE='$chktraincode'
														
														UNION
														
														SELECT 'OLD' AS TYPE,ctold.APPLICANTNO,NULL,TRAINDATE,TRAINDATE,NULL,NULL
														FROM crewtrainingold ctold
														WHERE ctold.APPLICANTNO=$applicantno AND ctold.TRAINCODE='$chktraincode'
														
														UNION
														
														SELECT 'UPD' AS TYPE,ctnosched.APPLICANTNO,NULL,TRAINDATE,TRAINDATE,NULL,NULL
														FROM crewtrainingnosched ctnosched
														WHERE ctnosched.APPLICANTNO=$applicantno AND ctnosched.TRAINCODE='$chktraincode'
													) x ON x.APPLICANTNO = cc.APPLICANTNO
													
													WHERE cc.APPLICANTNO=$applicantno AND cc.DOCCODE='$chkdoccode'
													ORDER BY DATEISSUED DESC
													LIMIT 1
													") or die(mysql_error());
								
								if (mysql_num_rows($qrycheckcertstatus) > 0)
								{
									$rowcheckcertstatus = mysql_fetch_array($qrycheckcertstatus);
									$certrankcode = $rowcheckcertstatus["RANKCODE"];
									
									if ($rowcheckcertstatus["DATEISSUED"] != "")
										$certdateissued = date("Y-m-d",strtotime($rowcheckcertstatus["DATEISSUED"]));
										
									$certgrade = $rowcheckcertstatus["GRADE"];
									$certstatus = $rowcheckcertstatus["STATUS"];
									
									if ($certstatus == "UPDATE" )
									{
										$certshow = "<a href=\"#\" style=\"font-weight:Bold;color:Green;\"
														onclick=\"
														applicantno.value = '$applicantno';
														traincodehidden.value = '$chktraincode';
														rankcodehidden.value = '$certrankcode';
														traindatehidden.value = '$certdateissued';
														gradehidden.value = '$certgrade';
														
														actiontxt.value='updatetraining';submit();\" 
														title=\"Certificate Found! Click to update training history.\">
														[UPDATE TRAINING]</a>";
									}
									else 
									{
										$certshow = "";
									}
								}
								
								//LISTING - START
								
								$endorseshow = "";
//								$onclick = "-------";
										
								if ($endorsed == 0)
								{
									
									$onclick = "<input type=\"checkbox\" name=\"chkbxtrain[]\" value=\"$chktraincode\" />";
									
//									$onclick = "<a href=\"#\" onclick=\"applicantno.value='$applicantno';endorsetraincode.value='$chktraincode';actiontxt.value='endorse';submit();\" 
//									style=\"font-size:1em;font-weight:bold;color:#004080;\" title=\"Click to Endorse!\">[ENDORSE]</a>";
								}
								else 
								{
									$onclick = "<input type=\"checkbox\" name=\"chkbxcancel[]\" value=\"$chktraincode\" />";
									
//									$onclick = "<a href=\"#\" onclick=\"if(confirm('Training Endorsement of $crewname on Training -- [$chktraining], will be cancelled. Please Confirm?')) 
//											{applicantno.value='$applicantno';endorsetraincode.value='$chktraincode';actiontxt.value='unendorse';submit();}\" 
//											style=\"font-size:1em;font-weight:bold;color:Red;\" title=\"Undo Endorsement\">[CANCEL]</a>";
									
									if ($enrolled != 1)
										$endorseshow = "<i>Not enrolled yet.</i>";
									else
										$endorseshow = "";
										
								}
								
								
								if ($endorsed == 1)
									$styleendorse = "style=\"border:thin solid black;background-color:Yellow;\"";
								else
									$styleendorse = "style=\"border:thin solid black;\"";
								
								echo "
								<tr $mouseovereffect $styleendorse >
									<td $styledtl>$chktraining</td>
									<td $styledtl align=\"center\">
								";
									if (!empty($datefrom))
									{
										echo "<span style=\"font-weight:Bold;color:Black;\">$datefrom</span>&nbsp;&nbsp;";
										if ($historycnt > 1)
											echo "<a href=\"#\" onclick=\"openWindow('traininghistory.php?traincode=$chktraincode&applicantno=$applicantno','traininghistory' ,800, 565);\" style=\"font-weight:Bold;color:Green;\" title=\"Click to view history...\">[$historycnt]</a>";
									}
									else 
									{
										echo "-----";
									}
									echo "	
									</td>
									<td $styledtl align=\"left\">&nbsp;$statusshow&nbsp;$certshow&nbsp;$endorseshow</td>
									<td $styledtl align=\"center\" title=\"$chkdoccode\">$docshow</td>
									<td $styledtl align=\"center\">$onclick</td>
								</tr>
									";
							
								$tmpgroup = $chkcoursetypecode;
							}
//						}
				echo " $btnendorse
					</table>
				</div>
				";
			}
			else 
			{
				echo "
				<div style=\"width:100%;height:100%;overflow:auto;\">
					<br /><br /><br /><br /><br /><br /><br />
					<center>
						<span style=\"font:bold 1.3em;color:gray;\"><i>[ CHECKLIST Preview ]</i></span>
					</center>
				</div>
				
				";
			}

echo "
		</div>
		

	</div>
	
	
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"applicantno\" value=\"$applicantno\" />
	<input type=\"hidden\" name=\"ccid\" />
	<input type=\"hidden\" name=\"currentrankcode\" />
	
	<input type=\"hidden\" name=\"traincodehidden\" />
	<input type=\"hidden\" name=\"rankcodehidden\" />
	<input type=\"hidden\" name=\"traindatehidden\" />
	<input type=\"hidden\" name=\"gradehidden\" />
	
	<input type=\"hidden\" name=\"endorsetraincode\" />
	
	<input type=\"hidden\" name=\"getccid\" />
	<input type=\"hidden\" name=\"getdateemb\" />
	<input type=\"hidden\" name=\"getdatedisemb\" />
	<input type=\"hidden\" name=\"getapplicantno\" value=\"$getapplicantno\" />
	
	<input type=\"hidden\" name=\"assignedccidhidden\" value=\"$assignedccid\"/>
	
	
</form>

</body>
";
?>