<?php

include("veritas/connectdb.php");
include("veritas/include/stylephp.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
		
$formname = "formtrainingschedules";
$formtitle = "TRAINING SCHEDULES SETUP";

//POSTS

if(isset($_POST['scheduleid']))
	$scheduleid=$_POST['scheduleid'];

if(isset($_POST['moduleid']))
	$moduleid=$_POST['moduleid'];

if(isset($_POST['schedtlidno']))
	$schedtlidno=$_POST['schedtlidno'];
	
if(isset($_POST['traincode']))
	$traincode=$_POST['traincode'];
else 
	$traincode="";

if(isset($_POST['coursetypecode']))
	$coursetypecode=$_POST['coursetypecode'];

if(isset($_POST['addtype']))
	$addtype=$_POST['addtype'];

if(isset($_POST['datefrom']))
	$datefrom=$_POST['datefrom'];

if(isset($_POST['datefrom2']))
	$datefrom2=$_POST['datefrom2'];

if(isset($_POST['dateto']))
	$dateto=$_POST['dateto'];

if(isset($_POST['dateto2']))
	$dateto2=$_POST['dateto2'];

if(isset($_POST['day']))
	$day=$_POST['day'];

if(isset($_POST['venuecode']))
	$venuecode=$_POST['venuecode'];

if(isset($_POST['centercode']))
	$centercode=$_POST['centercode'];

if(isset($_POST['maxslots']))
	$maxslots=$_POST['maxslots'];

if ($addtype == 1)
	$checked1 = "checked=\"checked\"";
elseif ($addtype == 2)
	$checked2 = "checked=\"checked\"";
else 
{
	$checked1 = "";
	$checked2 = "";
	$addtype = "";
}

//POSTS for trainingschedhdr

if(isset($_POST['hvenuecode']))
	$hvenuecode=$_POST['hvenuecode'];

if(isset($_POST['hcentercode']))
	$hcentercode=$_POST['hcentercode'];

if(isset($_POST['hmaxslots']))
	$hmaxslots=$_POST['hmaxslots'];

if(isset($_POST['hinstructor']))
	$hinstructor=$_POST['hinstructor'];

if(isset($_POST['htimestart']))
	$htimestart=$_POST['htimestart'];

if(isset($_POST['htimeend']))
	$htimeend=$_POST['htimeend'];

if(isset($_POST['hremarks']))
	$hremarks=$_POST['hremarks'];

//POSTS for trainingscheddtl

if(isset($_POST['dvenuecode']))
	$dvenuecode=$_POST['dvenuecode'];

if(isset($_POST['dcentercode']))
	$dcentercode=$_POST['dcentercode'];

if(isset($_POST['dinstructor']))
	$dinstructor=$_POST['dinstructor'];

if(isset($_POST['dtimestart']))
	$dtimestart=$_POST['dtimestart'];

if(isset($_POST['dtimeend']))
	$dtimeend=$_POST['dtimeend'];



if (empty($traincode))
	$disabledradio = "disabled=\"disabled\"";
else 
	$disabledradio = "";

	
$showscheddtls = "visibility:hidden;";
$showschedmodules = "visibility:hidden;";

switch ($actiontxt)
{
	case "schedadd"		:
		
			switch ($addtype)
			{
				case "1"	: //By Date Range
					
						if ($datefrom != "" && $dateto != "")
						{
							if ($datefrom != "")
								$datefromraw = "'" . date("Y-m-d",strtotime($datefrom)) . "'";
							else 
								$datefromraw = "NULL";
						
							if ($dateto != "")
								$datetoraw = "'" . date("Y-m-d",strtotime($dateto)) . "'";
							else 
								$datetoraw = "NULL";
								
							if (empty($maxslots))
								$maxslots = 0;
						
							$qryinserthdr = mysql_query("INSERT INTO trainingschedhdr(TRAINCODE,DATEFROM,DATETO,MADEBY,MADEDATE,MAXSLOTS,STATUS) 
														VALUES('$traincode',$datefromraw,$datetoraw,'$employeeid','$currentdate',$maxslots,1)") or die(mysql_error());
							
							$qrygetschedid = mysql_query("SELECT MAX(SCHEDID) AS SCHEDID
														FROM trainingschedhdr
														") or die(mysql_error());
							
							if (mysql_num_rows($qrygetschedid) == 1)
							{
								$rowgetschedid = mysql_fetch_array($qrygetschedid);
								
								$sid = $rowgetschedid["SCHEDID"];
								
								$qrygetdiff = mysql_query("SELECT DATEFROM,DATETO,DATEDIFF(DATETO,DATEFROM) AS DIFF FROM trainingschedhdr WHERE SCHEDID=$sid") or die(mysql_error());
								$rowgetdiff = mysql_fetch_array($qrygetdiff);
								$diff = $rowgetdiff["DIFF"];
								$date1 = $rowgetdiff["DATEFROM"];
								$date2 = $rowgetdiff["DATETO"];
								
								for ($i=0;($i<=$diff);$i++)
								{
									$tstart = date("Y-m-d 08:00:00",strtotime($date1));
									$tend = date("Y-m-d 17:00:00",strtotime($date1));
									
									if ($venuecode != "")
										$venuecode2 = "'" . $venuecode . "'";
									else 
										$venuecode2 = "NULL";
										
									if ($centercode != "")
										$centercode2 = "'" . $centercode . "'";
									else 
										$centercode2 = "NULL";
										
									$qryinsertdtl = mysql_query("INSERT INTO trainingscheddtl(SCHEDID,SCHEDDATE,TRAINVENUECODE,TRAINCENTERCODE,TIMESTART,TIMEEND) 
																VALUES($sid,'$date1',$venuecode2,$centercode2,'$tstart','$tend')") or die(mysql_error());
									
									$datetmp = strtotime("+1 day",strtotime($date1));
									$date1 = date("Y-m-d",$datetmp);
								}
							}
	
							$datefrom = "";
							$dateto = "";
							$maxslots = "";
							$venuecode = "";
							$centercode = "";
						}
						else 
							$error = "Dates are invalid.";
							
					break;
				case "2"	: //By Specific Day
				
						if ($datefrom2 != "" && $dateto2 != "")
						{
							$datediff = (strtotime($dateto2) - strtotime($datefrom2)) / 86400;
							
							for ($i=0;($i<=$datediff);$i++)
							{
								$daytmp = date("N",strtotime($datefrom2));
								
								if ($daytmp == $day)
								{
									$datefrom2raw = "'" . date("Y-m-d",strtotime($datefrom2)) . "'";
									
									if ($venuecode != "")
										$venuecode2 = "'" . $venuecode . "'";
									else 
										$venuecode2 = "NULL";
									
									if ($centercode != "")
										$centercode2 = "'" . $centercode . "'";
									else 
										$centercode2 = "NULL";
										
									if (empty($maxslots))
										$maxslots = 0;
										
									$qryinserthdr = mysql_query("INSERT INTO trainingschedhdr(TRAINCODE,DATEFROM,DATETO,MADEBY,MADEDATE,MAXSLOTS,STATUS) 
																VALUES('$traincode',$datefrom2raw,$datefrom2raw,'$employeeid','$currentdate',$maxslots,1)
																") or die(mysql_error());
									
									$qrygetschedid = mysql_query("SELECT MAX(SCHEDID) AS SCHEDID
																FROM trainingschedhdr
																") or die(mysql_error());
									
									if (mysql_num_rows($qrygetschedid) == 1)
									{
										$rowgetschedid = mysql_fetch_array($qrygetschedid);
										
										$sid = $rowgetschedid["SCHEDID"];
										
										$tstart = date("Y-m-d 08:00:00",strtotime($datefrom2));
										$tend = date("Y-m-d 17:00:00",strtotime($datefrom2));
											
										$qryinsertdtl = mysql_query("INSERT INTO trainingscheddtl(SCHEDID,SCHEDDATE,TRAINVENUECODE,TRAINCENTERCODE,TIMESTART,TIMEEND) 
																	VALUES($sid,$datefrom2raw,$venuecode2,$centercode2,'$tstart','$tend')") or die(mysql_error());
									}
								}
									
								$datetmp = strtotime("+1 day",strtotime($datefrom2));
								$datefrom2 = date("Y-m-d",$datetmp);
							}
							
							$datefrom2 = "";
							$dateto2 = "";
							$maxslots = "";
							$venuecode = "";
							$centercode = "";
						}
						else 
							$error = "Dates are invalid.";
					break;
			}

		break;
	case "updateall"	:
		
			if ($scheduleid != "")
			{
				if (empty($hmaxslots))
					$hmaxslots = 1;
				
				$qryudpatehdr = mysql_query("UPDATE trainingschedhdr SET MAXSLOTS=$hmaxslots,REMARKS='$hremarks' WHERE SCHEDID=$scheduleid") or die(mysql_error());
				
				$qrygetdiff = mysql_query("SELECT DATEFROM,DATETO,DATEDIFF(DATETO,DATEFROM) AS DIFF 
										FROM trainingschedhdr WHERE SCHEDID=$scheduleid") or die(mysql_error());
				$rowgetdiff = mysql_fetch_array($qrygetdiff);
				$diff = $rowgetdiff["DIFF"];
				$date1 = $rowgetdiff["DATEFROM"];
				$date2 = $rowgetdiff["DATETO"];
				
				$datetmp = "";
				for ($i=0;($i<=$diff);$i++)
				{
					$date1tmp = date("Y-m-d",strtotime($date1));
					
					$tstart = date("Y-m-d H:i:s",strtotime((date("Y-m-d",strtotime($date1)) . " " . date("H:i:s",strtotime($htimestart)))));
					$tend = date("Y-m-d H:i:s",strtotime((date("Y-m-d",strtotime($date1)) . " " . date("H:i:s",strtotime($htimeend)))));
	
//					echo "UPDATE trainingscheddtl SET TRAINVENUECODE='$hvenuecode',
//																			TRAINCENTERCODE='$hcentercode',
//																			INSTRUCTOR='$hinstructor',
//																			TIMESTART='$tstart',
//																			TIMEEND ='$tend'
//												WHERE SCHEDID=$scheduleid AND SCHEDDATE='$date1tmp'";
					
					$qryupdatedtl = mysql_query("UPDATE trainingscheddtl SET TRAINVENUECODE='$hvenuecode',
																			TRAINCENTERCODE='$hcentercode',
																			INSTRUCTOR='$hinstructor',
																			TIMESTART='$tstart',
																			TIMEEND ='$tend'
												WHERE SCHEDID=$scheduleid AND SCHEDDATE='$date1tmp'") or die(mysql_error());
					
					$datetmp = strtotime("+1 day",strtotime($date1));
					$date1 = date("Y-m-d",$datetmp);
				}
			}
			
		break;
	case "updateentry"	:
		
			if ($schedtlidno != "")
			{
				$qrygetscheddate = mysql_query("SELECT SCHEDDATE FROM trainingscheddtl WHERE IDNO=$schedtlidno") or die(mysql_error());
				$rowgetscheddate = mysql_fetch_array($qrygetscheddate);
				$date1 = $rowgetscheddate["SCHEDDATE"];
				
				$tstart = date("Y-m-d H:i:s",strtotime((date("Y-m-d",strtotime($date1)) . " " . date("H:i:s",strtotime($dtimestart)))));
				$tend = date("Y-m-d H:i:s",strtotime((date("Y-m-d",strtotime($date1)) . " " . date("H:i:s",strtotime($dtimeend)))));

				$qryupdatedtl = mysql_query("UPDATE trainingscheddtl SET TRAINVENUECODE='$dvenuecode',
																		TRAINCENTERCODE='$dcentercode',
																		INSTRUCTOR='$dinstructor',
																		TIMESTART='$tstart',
																		TIMEEND ='$tend'
											WHERE IDNO=$schedtlidno") or die(mysql_error());
				
			}
			
		
		break;
	case "addmodule"	:
		
			$showschedmodules = "visibility:show;";
			
			$qryaddmodule = mysql_query("INSERT INTO trainingschedmodules(SCHEDID,MODULEID) VALUES ($scheduleid,$moduleid)") or die(mysql_error());
			
		break;
	case "deletemodule"	:
		
			$showschedmodules = "visibility:show;";
			
			$qrydeletemodule = mysql_query("DELETE FROM trainingschedmodules WHERE SCHEDID=$scheduleid AND MODULEID=$moduleid") or die(mysql_error());
			
		break;
	case "showscheddtl"	:
		
			$showscheddtls = "visibility:show;";
			
		break;
	case "showschedmodules"	:
		
			$showschedmodules = "visibility:show;";
			
		break;
	case "cancelscheddtl"	:
		
			$qrycanceltraining = mysql_query("UPDATE trainingschedhdr SET STATUS=0 WHERE SCHEDID='$scheduleid'") or die(mysql_error());
			
			$scheduleid = "";
			
		break;
	case "deletesched"		:
		
			$qrydeletescheddtl = mysql_query("DELETE FROM trainingscheddtl WHERE SCHEDID='$scheduleid'") or die(mysql_error());
			$qrydeleteschedhdr = mysql_query("DELETE FROM trainingschedhdr WHERE SCHEDID='$scheduleid'") or die(mysql_error());
			
			$qrydeleteschedmodules = mysql_query("DELETE FROM trainingschedmodules WHERE SCHEDID='$scheduleid'") or die(mysql_error());
		
		break;
	case "showcurrent"		:
			
			
		
		break;
}
	

/* LISTINGS  */

$qrycoursetype = mysql_query("SELECT COURSETYPECODE,COURSETYPE
									FROM coursetype
					") or die(mysql_error());

	$coursetypesel = "<option selected value=\"\">--Select One--</option>";
	while($rowcoursetype = mysql_fetch_array($qrycoursetype))
	{
		$ctcode = $rowcoursetype["COURSETYPECODE"];
		$ctype = $rowcoursetype["COURSETYPE"];
		
		$selected = "";
		
		if ($ctcode == $coursetypecode)
			$selected = "SELECTED";
			
		$coursetypesel .= "<option $selected value=\"$ctcode\">$ctype</option>";
	}

$qrytrainingcourses = mysql_query("SELECT TRAINCODE,TRAINING
									FROM trainingcourses
									WHERE COURSETYPECODE='$coursetypecode'
									AND STATUS=1
									ORDER BY TRAINING
					") or die(mysql_error());

	$coursesel = "<option selected value=\"\">--Select One--</option>";
	while($rowtrainingcourses = mysql_fetch_array($qrytrainingcourses))
	{
		$tcode = $rowtrainingcourses["TRAINCODE"];
		$train = $rowtrainingcourses["TRAINING"];
		
		$selected = "";
		
		if ($tcode == $traincode)
			$selected = "SELECTED";
			
		$coursesel .= "<option $selected value=\"$tcode\">$train</option>";
	}

$qrytrainingvenue = mysql_query("SELECT TRAINVENUECODE,LEFT(TRAINVENUE,15) AS TRAINVENUE
									FROM trainingvenue
									WHERE STATUS=1
									ORDER BY TRAINVENUE
					") or die(mysql_error());

	$venuesel = "<option selected value=\"\">--Select One--</option>";
	while($rowtrainingvenue = mysql_fetch_array($qrytrainingvenue))
	{
		$vcode = $rowtrainingvenue["TRAINVENUECODE"];
		$venue = $rowtrainingvenue["TRAINVENUE"];
		
		$selected2 = "";
		
		if ($vcode == $venuecode)
			$selected2 = "SELECTED";
			
		$venuesel .= "<option $selected2 value=\"$vcode\">$venue</option>";
	}

$qrytrainingcenter = mysql_query("SELECT TRAINCENTERCODE,LEFT(TRAINCENTER,15) AS TRAINCENTER
									FROM trainingcenter
									WHERE STATUS=1
									ORDER BY TRAINCENTER
					") or die(mysql_error());

	$centersel = "<option selected value=\"\">--Select One--</option>";
	while($rowtrainingvenue = mysql_fetch_array($qrytrainingcenter))
	{
		$ccode = $rowtrainingvenue["TRAINCENTERCODE"];
		$center = $rowtrainingvenue["TRAINCENTER"];
		
		$selected2 = "";
		
		if ($ccode == $venuecode)
			$selected2 = "SELECTED";
			
		$centersel .= "<option $selected2 value=\"$ccode\">$center</option>";
	}
	
/*END OF LISTINGS*/

$wherepart = " AND th.DATEFROM > CURRENT_DATE";

$qryscheddtls = mysql_query("SELECT th.SCHEDID,th.TRAINCODE,th.DATEFROM,th.DATETO,tv.TRAINVENUE,th.STATUS,
								IF(tcm.MODULEID IS NOT NULL,1,0) AS HASMODULES
								FROM trainingschedhdr th
								LEFT JOIN trainingscheddtl td ON td.SCHEDID=th.SCHEDID
								LEFT JOIN trainingvenue tv ON tv.TRAINVENUECODE=td.TRAINVENUECODE
								LEFT JOIN traincoursemodules tcm ON tcm.TRAINCODE=th.TRAINCODE
								WHERE th.TRAINCODE='$traincode' $wherepart
							GROUP BY th.SCHEDID
							ORDER BY th.DATEFROM
							") or die(mysql_error());

echo "
<html>
<head>
<link rel=\"stylesheet\" type=\"text/css\" href=\"../veripro.css\">
<script language=\"javascript\" src=\"../veripro.js\"></script>
<script language=\"javascript\" src=\"../popcalendar.js\"></script>
<script>

	
</script>
</head>
<body>
<form name=\"$formname\" method=\"POST\">
	<br />
	<span class=\"sectiontitle\">$formtitle</span>
	<br />
	
<div style=\"position:absolute;left:300px;top:175px;width:380px;height:400px;background-color:Orange;
				border:2px solid black;overflow:auto;$showscheddtls \">
";
	$style1 =  "style=\"font-size:0.8em;font-weight:Bold;\"";	

	if ($schedtlidno == "" && $scheduleid != "")
	{
		$qryschedhdr = mysql_query("SELECT th.SCHEDID,th.TRAINCODE,th.DATEFROM,th.DATETO,th.MAXSLOTS,
										tcs.COURSETYPECODE
										FROM trainingschedhdr th
										LEFT JOIN trainingcourses tcs ON tcs.TRAINCODE=th.TRAINCODE
										WHERE th.SCHEDID=$scheduleid
									") or die(mysql_error());
		
		$rowschedhdr = mysql_fetch_array($qryschedhdr);
		
		$datefrom1 = date("m-d-Y",strtotime($rowschedhdr["DATEFROM"]));
		$dateto1 = date("m-d-Y",strtotime($rowschedhdr["DATETO"]));
		$maxslots1 = $rowschedhdr["MAXSLOTS"];
		$coursetypecode1 = $rowschedhdr["COURSETYPECODE"];
		
		$qryvenuecode = mysql_query("SELECT TRAINVENUECODE,LEFT(TRAINVENUE,15) AS TRAINVENUE
									FROM trainingvenue
									WHERE STATUS=1") or die(mysql_error());
		
		$qrycentercode = mysql_query("SELECT TRAINCENTERCODE,LEFT(TRAINCENTER,15) AS TRAINCENTER
									FROM trainingcenter
									WHERE STATUS=1") or die(mysql_error());
		
	echo "
			<span class=\"sectiontitle\">MODIFY MULTIPLE ENTRY - APPLY TO ALL DATES</span>
			<br />
			
			<center>
			<table width=\"90%\">
				<tr>
					<td $style1>Schedule ID</td>			
					<td>:</td>			
					<td $style1>$scheduleid</td>			
				</tr>
				<tr>
					<td $style1>Date From</td>			
					<td>:</td>			
					<td $style1>$datefrom1</td>			
				</tr>
				<tr>
					<td $style1>Date To</td>			
					<td>:</td>			
					<td $style1>$dateto1</td>			
				</tr>
				";
	
				if ($coursetypecode1 == "INHSE")
				{
					echo "
					<tr>
						<td style=\"font-size:0.8em;font-weight:Bold;\">Venue</td>			
						<td>:</td>			
						<td><select name=\"hvenuecode\">
								<option value=\"\">--Select One--</option>
							";
								while ($rowvenuecode = mysql_fetch_array($qryvenuecode))
								{
									$code = $rowvenuecode["TRAINVENUECODE"];
									$name = $rowvenuecode["TRAINVENUE"];
									
									echo "<option value=\"$code\">$name</option>";
								}
							echo "
							</select>
						</td>			
					</tr>
					";
				}
				else 
				{
					echo "
					<tr>
						<td style=\"font-size:0.8em;font-weight:Bold;\">Center</td>			
						<td>:</td>			
						<td><select name=\"hcentercode\">
								<option value=\"\">--Select One--</option>
							";
								while ($rowcentercode = mysql_fetch_array($qrycentercode))
								{
									$code = $rowcentercode["TRAINCENTERCODE"];
									$name = $rowcentercode["TRAINCENTER"];
									
									echo "<option value=\"$code\">$name</option>";
								}
							echo "
							</select>
						</td>			
					</tr>
					";
				}	
					
				echo "
				<tr>
					<td $style1>Max. Slots</td>			
					<td>:</td>			
					<td $style1><input type=\"text\" size=\"5\" name=\"hmaxslots\" value=\"$maxslots1\" onKeyPress=\"return numbersonly(this);\"
									style=\"font-size:0.9em;font-weight:Bold;color:Green;\" /></td>			
				</tr>
				<tr>
					<td $style1>Instructor</td>			
					<td>:</td>			
					<td><input type=\"text\" size=\"25\" name=\"hinstructor\"
									style=\"font-size:0.9em;font-weight:Bold;color:Green;\" />
					</td>			
				</tr>
				<tr>
					<td $style1>Time Start</td>			
					<td>:</td>			
					<td><input type=\"text\" size=\"7\" name=\"htimestart\" onKeyPress=\"return timeonly(this);\"
									onKeyDown=\"if(event.keyCode==13){chktime(this);}\"
									style=\"font-size:0.9em;font-weight:Bold;color:Green;\" />
					</td>			
				</tr>
				<tr>
					<td $style1>Time End</td>			
					<td>:</td>			
					<td><input type=\"text\" size=\"7\" name=\"htimeend\" onKeyPress=\"return timeonly(this);\" 
									onKeyDown=\"if(event.keyCode==13){chktime(this);}\"
									style=\"font-size:0.9em;font-weight:Bold;color:Green;\" />
					</td>			
				</tr>
				<tr>
					<td $style1>Remarks</td>			
					<td>:</td>			
					<td><textarea rows=\"4\" cols=\"20\" name=\"hremarks\"></textarea></td>			
				</tr>
				<tr>
					<td colspan=\"3\">&nbsp;</td>			
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td colspan=\"2\" >
						<input type=\"button\" value=\"Apply To All\" onclick=\"actiontxt.value='updateall';
									scheduleid.value='$scheduleid';submit();\" />
						<input type=\"button\" value=\"Close Window\" onclick=\"submit();\" />
					</td>
				</tr>
			</table>
			</center>
	
	";	
	}
	elseif ($scheduleid != "" && $schedtlidno != "")
	{
		$qrygetschedtime = mysql_query("SELECT td.IDNO,SCHEDDATE,TIMESTART,TIMEEND,INSTRUCTOR,
										td.TRAINVENUECODE,TRAINVENUE,td.TRAINCENTERCODE,tc.TRAINCENTER,
										th.TRAINCODE,tcs.TRAINING,tcs.COURSETYPECODE
										FROM trainingscheddtl td
										LEFT JOIN trainingschedhdr th ON th.SCHEDID=td.SCHEDID
										LEFT JOIN trainingcourses tcs ON tcs.TRAINCODE=th.TRAINCODE
										LEFT JOIN trainingvenue tv ON tv.TRAINVENUECODE=td.TRAINVENUECODE
										LEFT JOIN trainingcenter tc ON tc.TRAINCENTERCODE=td.TRAINCENTERCODE
										WHERE td.SCHEDID=$scheduleid AND IDNO=$schedtlidno
									") or die(mysql_error());
		
		$rowgetschedtime = mysql_fetch_array($qrygetschedtime);
		
		$dtl_idno = $rowgetschedtime["IDNO"];
		$dtl_date = date("m-d-Y",strtotime($rowgetschedtime["SCHEDDATE"]));
		
		if ($rowgetschedtime["TIMESTART"] != "")
			$dtl_start1 = date("H:i",strtotime($rowgetschedtime["TIMESTART"]));
			
		if ($rowgetschedtime["TIMEEND"] != "")
			$dtl_end1 = date("H:i",strtotime($rowgetschedtime["TIMEEND"]));
			
		if ($rowgetschedtime["TRAINVENUECODE"] != "")
			$dtl_venue1 = $rowgetschedtime["TRAINVENUECODE"];

		if ($rowgetschedtime["TRAINCENTERCODE"] != "")
			$dtl_center1 = $rowgetschedtime["TRAINCENTERCODE"];

		$qryvenue = mysql_query("SELECT TRAINVENUECODE,LEFT(TRAINVENUE,15) AS TRAINVENUE
								FROM trainingvenue 
								WHERE STATUS=1
								") or die(mysql_error());	

		$qrycenter = mysql_query("SELECT TRAINCENTERCODE,LEFT(TRAINCENTER,15) AS TRAINCENTER
								FROM trainingcenter 
								WHERE STATUS=1
								") or die(mysql_error());	

		$dtl_coursetypecode = $rowgetschedtime["COURSETYPECODE"];	
		$dtl_training = $rowgetschedtime["TRAINING"];	
		$dtl_instructor = $rowgetschedtime["INSTRUCTOR"];	
		
	echo "
			<span class=\"sectiontitle\">MODIFY SINGLE ENTRY - SCHEDULE ID = $scheduleid</span>
			<br />
	
			<center>
			<table width=\"90%\">
				<tr>
					<td $style1>ID No</td>			
					<td>:</td>			
					<td $style1>$schedtlidno</td>			
				</tr>
				<tr>
					<td $style1>Training</td>			
					<td>:</td>			
					<td $style1>$dtl_training</td>			
				</tr>
				<tr>
					<td $style1>Schedule Date</td>			
					<td>:</td>			
					<td $style1>$dtl_date</td>			
				</tr>
				";
				
				if ($dtl_coursetypecode == "INHSE")
				{
					echo "
					<tr>
						<td $style1>Venue</td>			
						<td>:</td>			
						<td><select name=\"dvenuecode\">
								<option value=\"\">--Select One--</option>
							";
								$vsel = "";
								while ($rowvenue = mysql_fetch_array($qryvenue))
								{
									$code = $rowvenue["TRAINVENUECODE"];
									$name = $rowvenue["TRAINVENUE"];
									
									if ($code == $dtl_venue1)
										$vsel = "SELECTED";
									else 
										$vsel = "";
									
									echo "<option $vsel value=\"$code\">$name</option>";
								}
							echo "
							</select>
						</td>			
					</tr>
					";
				}
				else 
				{
					echo "
					<tr>
						<td $style1>Center</td>			
						<td>:</td>			
						<td><select name=\"dcentercode\">
								<option value=\"\">--Select One--</option>
							";
								$vsel = "";
								while ($rowcenter = mysql_fetch_array($qrycenter))
								{
									$code = $rowcenter["TRAINCENTERCODE"];
									$name = $rowcenter["TRAINCENTER"];
									
									if ($code == $dtl_center1)
										$csel = "SELECTED";
									else 
										$csel = "";
									
									echo "<option $csel value=\"$code\">$name</option>";
								}
							echo "
							</select>
						</td>			
					</tr>
					";
				}
				
				echo "
				<tr>
					<td $style1>Instructor</td>			
					<td>:</td>			
					<td><input type=\"text\" size=\"25\" name=\"dinstructor\" value=\"$dtl_instructor\"
									style=\"font-size:0.9em;font-weight:Bold;color:Green;\" />
					</td>			
				</tr>
				<tr>
					<td $style1>Time Start</td>			
					<td>:</td>			
					<td><input type=\"text\" size=\"7\" value=\"$dtl_start1\" name=\"dtimestart\" onKeyPress=\"return timeonly(this);\"
									onKeyDown=\"if(event.keyCode==13){chktime(this);}\"
									style=\"font-size:0.9em;font-weight:Bold;color:Green;\" />
					</td>			
				</tr>
				<tr>
					<td $style1>Time End</td>			
					<td>:</td>			
					<td><input type=\"text\" size=\"7\" value=\"$dtl_end1\" name=\"dtimeend\" onKeyPress=\"return timeonly(this);\" 
									onKeyDown=\"if(event.keyCode==13){chktime(this);}\"
									style=\"font-size:0.9em;font-weight:Bold;color:Green;\" />
					</td>				
				</tr>
				<tr>
					<td colspan=\"3\">&nbsp;</td>			
				</tr>
				<tr>
					<td colspan=\"3\">
						<input type=\"button\" value=\"Update Entry\" onclick=\"actiontxt.value='updateentry';
									schedtlidno.value='$schedtlidno';scheduleid.value='$scheduleid';submit();\" />
						<input type=\"button\" value=\"Close Window\" onclick=\"submit();\" />
					</td>
				</tr>
			</table>
			
			<br />
			
			<span style=\"font-size:0.8em;font-weight:Bold;color:Red;\">NOTE: Update Entry -- will only take effect on the SELECTED DATE.</span>
			</center>
	
	";	
	}
echo "	

	<br />
</div>
	

<div style=\"position:absolute;left:300px;top:175px;width:460px;height:300px;background-color:#9DE37F;
				border:2px solid black;overflow:auto;$showschedmodules \">
				
	<span class=\"sectiontitle\">SETUP MODULES</span>
	<br />
";
	if (!empty($traincode) && !empty($scheduleid))
	{
		$qrycurrent = mysql_query("SELECT tcm.MODULEID,tcm.MODULE
									FROM trainingschedmodules tsm
									LEFT JOIN traincoursemodules tcm ON tcm.MODULEID=tsm.MODULEID
									WHERE tcm.TRAINCODE='$traincode' AND tsm.SCHEDID=$scheduleid") or die(mysql_error());
		
		$qryavailable = mysql_query("SELECT MODULEID,MODULE
									FROM traincoursemodules tcm
									WHERE MODULEID NOT IN (SELECT tcm.MODULEID
									FROM trainingschedmodules tsm
									LEFT JOIN traincoursemodules tcm ON tcm.MODULEID=tsm.MODULEID
									WHERE tcm.TRAINCODE='$traincode' AND tsm.SCHEDID=$scheduleid)
									AND TRAINCODE='$traincode'") or die(mysql_error());
	
		
		echo "
		<div style=\"width:100%;\">
			<div style=\"width:48%;float:left;\">
				<center><span style=\"font-size:0.9em;font-weight:Bold;\"> AVAILABLE </span></center>
				<table class=\"listcol\" width=\"100%\">
					<tr>
						<th>NO.</th>
						<th>MODULE</th>
					</tr>
					";
				
				while ($rowavailable = mysql_fetch_array($qryavailable))
				{
					$moduledid1 = $rowavailable["MODULEID"];
					$moduled1 = $rowavailable["MODULE"];
					
					echo "
					<tr $mouseovereffect style=\"cursor:pointer;\" ondblclick=\"actiontxt.value='addmodule';
											moduleid.value='$moduledid1';scheduleid.value='$scheduleid';submit();\">
						<td>$moduledid1</td>
						<td>$moduled1</td>
					</tr>
					";
				}
			
				echo "
				</table>
			</div>
			
			<div style=\"width:48%;float:right;\">
				<center><span style=\"font-size:0.9em;font-weight:Bold;\"> CURRENT </span></center>
				<table class=\"listcol\" width=\"100%\">
					<tr>
						<th>NO.</th>
						<th>MODULE</th>
					";
				
				while ($rowcurrent = mysql_fetch_array($qrycurrent))
				{
					$moduledid2 = $rowcurrent["MODULEID"];
					$moduled2 = $rowcurrent["MODULE"];
					
					echo "
					<tr $mouseovereffect style=\"cursor:pointer;\" ondblclick=\"actiontxt.value='deletemodule';
											moduleid.value='$moduledid2';scheduleid.value='$scheduleid';submit();\">
						<td>$moduledid2</td>
						<td>$moduled2</td>
					</tr>
					";
				}
					echo "
					</tr>
				</table>
			</div>
			
		</div>
		";
	}
	echo "
	<br />
	<center>
		<input type=\"button\" value=\"Close Window\" onclick=\"submit();\" />
	</center>
	
</div>

	
	<center>
	<div style=\"width:100%;height:510px;padding:5px 20px 0 20px;overflow:hidden;\">
	

		<table class=\"setup\" width=\"100%\">	
			<tr>
				<th align=\"left\">
					Course Type&nbsp <br />
					<select name=\"coursetypecode\" onchange=\"traincode.value='';submit();\">
						$coursetypesel
					</select>
				</th>
				<th align=\"left\">
					Training Course <br />
					<select name=\"traincode\" onchange=\"submit();\">
						$coursesel
					</select>
				</th>
				<th align=\"right\" style=\"font-weight:Bold;color:Red;\">
					$error
				</th>
			</tr>
		</table>
		<br />

		<div style=\"width:100%;height:460px;\">
			<div style=\"width:39%;height:460px;float:left;\">
				<span class=\"sectiontitle\">ADD SCHEDULE</span>
				<br />
				
				<table class=\"setup\" width=\"100%\" >
					<tr>
						<th colspan=\"3\" align=\"left\"><input type=\"radio\" name=\"addtype\" $checked1 $disabledradio value=\"1\"
									onclick=\"datefrom2.value='';dateto2.value='';day.value='';
											datefrom.disabled=false;dateto.disabled=false;datefromgif.disabled=false;datetogif.disabled=false;
											datefrom2.disabled=true;dateto2.disabled=true;datefrom2gif.disabled=true;dateto2gif.disabled=true;
											day.disabled=true;datefrom.focus();btnadd.disabled=false;\" />&nbsp;&nbsp;Date Range</th>
					</tr>
					<tr>
						<th>From</th>
						<th>:</th>
						<th>
							<input type=\"text\" name=\"datefrom\" value=\"$datefrom\" $disabledradio onKeyPress=\"return dateonly(this);\"
									size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
							<img src=\"calendaricon.gif\" name=\"datefromgif\" $disabledradio width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(datefrom, datefrom, 'mm/dd/yyyy', 0, 0);return false;\">
						</th>
					</tr>
					<tr>
						<th>To</th>
						<th>:</th>
						<th>
							<input type=\"text\" name=\"dateto\" value=\"$dateto\" $disabledradio onKeyPress=\"return dateonly(this);\"
									size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
							<img src=\"calendaricon.gif\" name=\"datetogif\" $disabledradio width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(dateto, dateto, 'mm/dd/yyyy', 0, 0);return false;\">
						</th>
					</tr>
					<tr>
						<th colspan=\"3\">&nbsp;</th>
					</tr>
					<tr>
						<th colspan=\"3\" align=\"left\"><input type=\"radio\" name=\"addtype\" $checked2 $disabledradio value=\"2\"
							onclick=\"datefrom.value='';dateto.value='';
									datefrom.disabled=true;dateto.disabled=true;datefromgif.disabled=true;datetogif.disabled=true;
									datefrom2.disabled=false;dateto2.disabled=false;datefrom2gif.disabled=false;dateto2gif.disabled=false;
									day.disabled=false;day.focus();btnadd.disabled=false;\" />&nbsp;&nbsp;Specific Day of the Week</th>
					</tr>
					<tr>
						<th>Every</th>
						<th>:</th>
						<th>
							<select name=\"day\" $disabledradio>
								<option value=\"\">--Select--</option>
								<option value=\"1\">Monday</option>
								<option value=\"2\">Tuesday</option>
								<option value=\"3\">Wednesday</option>
								<option value=\"4\">Thursday</option>
								<option value=\"5\">Friday</option>
								<option value=\"6\">Saturday</option>
								<option value=\"7\">Sunday</option>
							</select>
						</th>
					</tr>
					<tr>
						<th>From</th>
						<th>:</th>
						<th>
							<input type=\"text\" name=\"datefrom2\" value=\"$datefrom2\" $disabledradio onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
							<img src=\"calendaricon.gif\" name=\"datefrom2gif\" $disabledradio width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(datefrom2, datefrom2, 'mm/dd/yyyy', 0, 0);return false;\">
						</th>
					</tr>
					<tr>
						<th>To</th>
						<th>:</th>
						<th>
							<input type=\"text\" name=\"dateto2\" value=\"$dateto2\" $disabledradio onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
							<img src=\"calendaricon.gif\" name=\"dateto2gif\" $disabledradio width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(dateto2, dateto2, 'mm/dd/yyyy', 0, 0);return false;\">
						</th>
					</tr>
					
					<tr>
						<th colspan=\"3\">&nbsp;</th>
					</tr>
					";

					if ($coursetypecode == "INHSE")
					{
						echo "
						<tr>
							<th>Venue</th>
							<th>:</th>
							<th>
								<select name=\"venuecode\" $disabledradio>
									$venuesel
								</select>
							</th>
						</tr>
						";
					}
					else 
					{
						echo "
						<tr>
							<th>Center</th>
							<th>:</th>
							<th>
								<select name=\"centercode\" $disabledradio>
									$centersel
								</select>
							</th>
						</tr>
						";
					}

					echo "
					<tr>
						<th>Max. Slots</th>
						<th>:</th>
						<th><input type=\"text\" name=\"maxslots\" $disabledradio value=\"$maxslots\" size=\"5\" onKeyPress=\"return numbersonly(this);\" /></th>
					</tr>
					<tr>
						<th colspan=\"3\">&nbsp;</th>
					</tr>
					<tr>
						<th colspan=\"2\">&nbsp;</th>
						<th><input type=\"button\" name=\"btnadd\" value=\"Add Schedule >>\" $disabledradio
								onclick=\"if ((datefrom.value != '' && dateto.value != '') || (datefrom2.value != '' && dateto2.value != '')) 
								{actiontxt.value='schedadd';submit();}
								else
								{alert('Invalid Dates. Please check.');}\" /></th>
					</tr>
				</table>
				
			</div>
			
			<div style=\"width:59%;height:460px;float:right;overflow:hidden;background-color:White;\">
				
				<span class=\"sectiontitle\">SCHEDULE DETAILS</span>
				<br />
				
				<div style=\"width:100%;height:410px;padding:0;overflow:auto;\">
					<table width=\"100%\" class=\"listcol\">
						<tr>
							<th width=\"10%\">SCHEDID</th>
							<th width=\"25%\">FROM</th>
							<th width=\"25%\">TO</th>
							<th width=\"40%\">ACTION</th>
						</tr>
		";
					while ($rowscheddtls = mysql_fetch_array($qryscheddtls))
					{
						$schedid = $rowscheddtls["SCHEDID"];
						$hasmodules = $rowscheddtls["HASMODULES"];
						
						if ($rowscheddtls["DATEFROM"] != "")
							$dfrom = date("Y-m-d",strtotime($rowscheddtls["DATEFROM"]));
						else 
							$dfrom = "";
							
						if ($rowscheddtls["DATETO"] != "")
							$dto = date("Y-m-d",strtotime($rowscheddtls["DATETO"]));
						else 
							$dto = "";
							
						$schedstatus = $rowscheddtls["STATUS"];
						
						$styledtl = "style=\"font-size:0.6em;\"";
						
						if ($schedstatus == 0)
						{
							$stylecancel = "style=\"background-color:Red;\""; //if SCHEDULE is CANCELLED!!
							$statusdisabled = "disabled=\"disabled\"";
						}
						else 
						{
							$stylecancel = "";
							$statusdisabled = "";
						}
						
						//CHECK IF SCHEDID CAN BE DELETED... (IF IT HAS NO PARTICIPANTS)
						
						$qrynumparticipants = mysql_query("SELECT ct.APPLICANTNO
										FROM crewtraining ct
										WHERE ct.SCHEDID=$schedid
										") or die(mysql_error());
						
						
						//END OF CHECKING PARTICIPANTS
						
						echo "
							<tr $mouseovereffect>
								<td align=\"center\" $stylecancel><b>$schedid</b></td>
								<td align=\"center\" $stylecancel><b>$dfrom</b></td>
								<td align=\"center\" $stylecancel><b>$dto</b></td>
								<td align=\"center\" $statusdisabled $stylecancel>
									<a href=\"#\"  onclick=\"scheduleid.value='$schedid';actiontxt.value='showscheddtl';$formname.submit();\" 
										style=\"font-weight:Bold;color:Green;text-decoration:underline;\">Modify
									</a>|
									<a href=\"#\" onclick=\"scheduleid.value='$schedid';actiontxt.value='cancelscheddtl';$formname.submit();\" 
										style=\"font-weight:Bold;color:Red;text-decoration:underline;\">Cancel
									</a>|
								";
						
								if ($hasmodules)
								{
									echo "
									<a href=\"#\" onclick=\"scheduleid.value='$schedid';actiontxt.value='showschedmodules';$formname.submit();\" 
										style=\"font-weight:Bold;color:Blue;text-decoration:underline;\">Modules
									</a>|
									";
								}
								
						
								if (mysql_num_rows($qrynumparticipants) == 0)
								{
									echo "
									<a href=\"#\" onclick=\"if(confirm('Delete Training Schedule ID: $schedid. Please confirm?'))
											{scheduleid.value='$schedid';actiontxt.value='deletesched';$formname.submit();}\" 
										style=\"font-weight:Bold;color:Blue;text-decoration:underline;\">Delete
									</a>
									";
								}
								else //CANNOT DELETE SCHEDID...IT HAS PARTICIPANTS.
								{
									echo "<span style=\"font-weight:Bold;color:Gray;\">----</span>";
								}
								
								echo "
								</td>
							</tr>
							<tr $stylecancel>
								<td colspan=\"4\">
								
									<table width=\"100%\" style=\"background-color:White;\">
									
									";

									$qryschedtime = mysql_query("SELECT IDNO,SCHEDDATE,TIMESTART,TIMEEND,TRAINVENUE,INSTRUCTOR
																	FROM trainingscheddtl td
																	LEFT JOIN trainingvenue tv ON tv.TRAINVENUECODE=td.TRAINVENUECODE
																	WHERE SCHEDID='$schedid'
																") or die(mysql_error());
						
									while ($rowshedtime = mysql_fetch_array($qryschedtime))
									{
										$idno = $rowshedtime["IDNO"];
										
										if ($rowshedtime["SCHEDDATE"] != "")
											$scheddate = date("m-d-Y",strtotime($rowshedtime["SCHEDDATE"]));
										else 
											$scheddate = "-";
											
										if ($rowshedtime["TIMESTART"] != "")
											$timestart = date("H:i",strtotime($rowshedtime["TIMESTART"]));
										else 
											$timestart = "-";
											
										if ($rowshedtime["TIMEEND"] != "")
											$timeend = date("H:i",strtotime($rowshedtime["TIMEEND"]));
										else 
											$timeend = "-";
											
										$trainvenue = $rowshedtime["TRAINVENUE"];
										$instructor = $rowshedtime["INSTRUCTOR"];
										
										echo "
										<tr $mouseovereffect style=\"cursor:pointer;\"
											onclick=\"scheduleid.value='$schedid';schedtlidno.value='$idno';actiontxt.value='showscheddtl';submit();\">
											
											<td width=\"15%\" $stylecancel $styledtl>&nbsp;$scheddate</td>
											<td width=\"15%\" align=\"center\" $stylecancel $styledtl>&nbsp;$timestart</td>
											<td width=\"15%\" align=\"center\" $stylecancel $styledtl>&nbsp;$timeend</td>
											<td width=\"30%\" $stylecancel $styledtl>&nbsp;$trainvenue</td>
											<td width=\"35%\" $stylecancel $styledtl>&nbsp;$instructor</td>
										</tr>
										";
									}
									echo "
									</table>
								
								</td>
							</tr>
						";
					}
		echo "
					</table>
				</div>
			</div>
		</div>
		<br />
		
	</div>
	</center>
	
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"scheduleid\" />
	<input type=\"hidden\" name=\"schedtlidno\" />
	
	<input type=\"hidden\" name=\"moduleid\" />
</form>

</body>
</html>
";

?>

