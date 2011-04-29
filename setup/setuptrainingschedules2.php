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

if(isset($_POST['schedtlidno']))
	$schedtlidno=$_POST['schedtlidno'];
	
if(isset($_POST['traincode']))
	$traincode=$_POST['traincode'];

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
										
									$qryinsertdtl = mysql_query("INSERT INTO trainingscheddtl(SCHEDID,SCHEDDATE,TRAINVENUECODE,TIMESTART,TIMEEND) 
																VALUES($sid,'$date1','$venuecode','$tstart','$tend')") or die(mysql_error());
									
									$datetmp = strtotime("+1 day",strtotime($date1));
									$date1 = date("Y-m-d",$datetmp);
								}
							}
	
							$datefrom = "";
							$dateto = "";
							$maxslots = "";
							$venuecode = "";
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
	//								$datetoraw = "'" . date("Y-m-d",strtotime($datefrom)) . "'";
									
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
											
										$qryinsertdtl = mysql_query("INSERT INTO trainingscheddtl(SCHEDID,SCHEDDATE,TRAINVENUECODE,TIMESTART,TIMEEND) 
																	VALUES($sid,$datefrom2raw,'$venuecode','$tstart','$tend')") or die(mysql_error());
									}
								}
									
								$datetmp = strtotime("+1 day",strtotime($datefrom2));
								$datefrom2 = date("Y-m-d",$datetmp);
							}
							
							$datefrom2 = "";
							$dateto2 = "";
							$maxslots = "";
							$venuecode = "";
						}
						else 
							$error = "Dates are invalid.";
						
					break;
				
			}

		break;
	case "updateall"	:
		
			if ($scheduleid != "")
			{
				$qryudpatehdr = mysql_query("UPDATE trainingschedhdr SET MAXSLOTS='$hmaxslots',REMARKS='$hremarks' WHERE SCHEDID=$scheduleid") or die(mysql_error());
				
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
	
					$qryupdatedtl = mysql_query("UPDATE trainingscheddtl SET TRAINVENUECODE='$hvenuecode',
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
																		INSTRUCTOR='$dinstructor',
																		TIMESTART='$tstart',
																		TIMEEND ='$tend'
											WHERE IDNO=$schedtlidno") or die(mysql_error());
				
//				$datetmp = strtotime("+1 day",strtotime($date1));
//				$date1 = date("Y-m-d",$datetmp);
			}
			
		
		break;
	case "showscheddtl"	:
		
			$showscheddtls = "visibility:show;";
			
		break;
	case "cancelscheddtl"	:
		
			$qrycanceltraining = mysql_query("UPDATE trainingschedhdr SET STATUS=0 WHERE SCHEDID='$scheduleid'") or die(mysql_error());
			
			$scheduleid = "";
		
		
		break;
}
	

/* LISTINGS  */

$qrytrainingcourses = mysql_query("SELECT TRAINCODE,TRAINING
									FROM trainingcourses
									WHERE COURSETYPECODE='INHSE'
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

$qrytrainingvenue = mysql_query("SELECT TRAINVENUECODE,TRAINVENUE
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

/*END OF LISTINGS*/

$qryscheddtls = mysql_query("SELECT th.SCHEDID,TRAINCODE,DATEFROM,DATETO,TRAINVENUE,th.STATUS
								FROM trainingschedhdr th
								LEFT JOIN trainingscheddtl td ON td.SCHEDID=th.SCHEDID
								LEFT JOIN trainingvenue tv ON tv.TRAINVENUECODE=td.TRAINVENUECODE
								WHERE TRAINCODE='$traincode'
							GROUP BY th.SCHEDID
							ORDER BY DATEFROM
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
	
<div style=\"position:absolute;left:250px;top:175px;width:380px;height:400px;background-color:Orange;
				border:2px solid black;overflow:auto;$showscheddtls \">
";
	$style1 =  "style=\"font-size:0.8em;font-weight:Bold;\"";	

	if ($schedtlidno == "" && $scheduleid != "")
	{
		$qryschedhdr = mysql_query("SELECT th.SCHEDID,TRAINCODE,DATEFROM,DATETO,MAXSLOTS
										FROM trainingschedhdr th
										WHERE th.SCHEDID=$scheduleid
									") or die(mysql_error());
		
		$rowschedhdr = mysql_fetch_array($qryschedhdr);
		
		$datefrom1 = date("m-d-Y",strtotime($rowschedhdr["DATEFROM"]));
		$dateto1 = date("m-d-Y",strtotime($rowschedhdr["DATETO"]));
		$maxslots1 = $rowschedhdr["MAXSLOTS"];
		
		$qryvenuecode = mysql_query("SELECT TRAINVENUECODE,TRAINVENUE
									FROM trainingvenue
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
		$qrygetschedtime = mysql_query("SELECT td.IDNO,SCHEDDATE,TIMESTART,TIMEEND,tv.TRAINVENUECODE,TRAINVENUE,INSTRUCTOR
										FROM trainingscheddtl td
										LEFT JOIN trainingvenue tv ON tv.TRAINVENUECODE=td.TRAINVENUECODE
										WHERE SCHEDID=$scheduleid AND IDNO=$schedtlidno
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

		$qryvenue = mysql_query("SELECT TRAINVENUECODE,LEFT(TRAINVENUE,15) AS TRAINVENUE
								FROM trainingvenue 
								") or die(mysql_error());	

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
					<td $style1>Schedule Date</td>			
					<td>:</td>			
					<td $style1>$dtl_date</td>			
				</tr>
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
	
	
	
	<center>
	<div style=\"width:100%;height:550px;padding:5px 20px 0 20px;overflow:hidden;\">
	

		<table class=\"setup\" width=\"100%\">	
			<tr>
				<th>
					Training Course&nbsp;&nbsp;&nbsp;
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

		<div style=\"width:100%;height:500px;\">
			<div style=\"width:39%;height:500px;float:left;\">
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
									day.disabled=false;day.focus();btnadd.disabled=false;\" />&nbsp;&nbsp;Specific Day</th>
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
					<tr>
						<th>Venue</th>
						<th>:</th>
						<th>
							<select name=\"venuecode\" $disabledradio>
								$venuesel
							</select>
						</th>
					</tr>
					<tr>
						<th>Max. Slots</th>
						<th>:</th>
						<th><input type=\"text\" name=\"maxslots\" $disabledradio value=\"$maxslots\" size=\"5\" onKeyPress=\"return numbersonly(this);\" /></th>
					</tr>
					<tr>
						<th colspan=\"2\">&nbsp;</th>
						<th><input type=\"button\" name=\"btnadd\" value=\"Add Schedule >>\" $disabledradio
								onclick=\"if (venuecode.value!='' && maxslots.value != '') 
												{actiontxt.value='schedadd';submit();}
												else {alert('Please enter VENUE and MAX SLOTS.');}
											\" /></th>
					</tr>
				</table>
				
			</div>
			
			<div style=\"width:59%;height:500px;float:right;overflow:hidden;background-color:White;\">
				
				<span class=\"sectiontitle\">SCHEDULE DETAILS</span>
				<br />
				
				<div style=\"width:100%;height:460px;padding:0;overflow:auto;\">
					<table width=\"100%\" class=\"listcol\">
						<tr>
							<th width=\"10%\">SCHEDID</th>
							<th width=\"30%\">FROM</th>
							<th width=\"30%\">TO</th>
							<th width=\"30%\">&nbsp;</th>
						</tr>
		";
					while ($rowscheddtls = mysql_fetch_array($qryscheddtls))
					{
						$schedid = $rowscheddtls["SCHEDID"];
						
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
									</a>
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
		
<!--		
		<div style=\"height:140px;width:100%;background-color:Orange;overflow:hidden; \">
			
			<span class=\"sectiontitle\">CHANGE SCHEDULE DETAILS</span>

			<div style=\"width:100%;height:120px;padding:0;overflow:auto;\">
				<table width=\"100%\" class=\"listcol\" style=\"overflow:auto;\">
					<tr>
						<th width=\"5%\">DATE</th>
						<th width=\"10%\">TIME START</th>
						<th width=\"10%\">TIME END</th>
						<th width=\"40%\">VENUE</th>
						<th width=\"30%\">INSTRUCTOR</th>
						<th width=\"5%\">ACTION</th>
					</tr>
				";
				
				$qrygetschedtime = mysql_query("SELECT td.IDNO,SCHEDDATE,TIMESTART,TIMEEND,tv.TRAINVENUECODE,TRAINVENUE,INSTRUCTOR
												FROM trainingscheddtl td
												LEFT JOIN trainingvenue tv ON tv.TRAINVENUECODE=td.TRAINVENUECODE
												WHERE SCHEDID='$scheduleid' AND IDNO='$schedtlidno'
											") or die(mysql_error());
				
				while ($rowgetschedtime = mysql_fetch_array($qrygetschedtime))
				{
					$dtl_idno = $rowgetschedtime["IDNO"];
					$dtl_date = date("m/d",strtotime($rowgetschedtime["SCHEDDATE"]));
					
					if ($rowgetschedtime["TIMESTART"] != "")
						$dtl_start1 = date("H:i",strtotime($rowgetschedtime["TIMESTART"]));

					$dtl_start = "<input type=\"text\" value=\"$dtl_start1\" size=\"7\" name=\"ztimestart\" onKeyPress=\"return timeonly(this);\"
									onKeyDown=\"if(event.keyCode==13){chktime(this);}\"
									style=\"font-size:0.8em;font-weight:Bold;color:Green;height:18px;\" />";
						
					if ($rowgetschedtime["TIMEEND"] != "")
						$dtl_end1 = date("H:i",strtotime($rowgetschedtime["TIMEEND"]));

					$dtl_end = "<input type=\"text\" value=\"$dtl_end1\" size=\"7\" name=\"ztimeend\" onKeyPress=\"return timeonly(this);\" 
									onKeyDown=\"if(event.keyCode==13){chktime(this);}\"
									style=\"font-size:0.8em;font-weight:Bold;color:Green;height:18px;\" />";
						
						if ($rowgetschedtime["TRAINVENUECODE"] != "")
							$dtl_venue1 = $rowgetschedtime["TRAINVENUECODE"];

						$qryvenue = mysql_query("SELECT TRAINVENUECODE,LEFT(TRAINVENUE,15) AS TRAINVENUE
												FROM trainingvenue 
												") or die(mysql_error());
						
						$sel1 = "";
						$venuedtl = "<option value=\"\">--Select One--</option>";
						while ($rowvenue = mysql_fetch_array($qryvenue))
						{
							$v1 = $rowvenue["TRAINVENUECODE"];
							$v2 = $rowvenue["TRAINVENUE"];
							
							if ($dtl_venue1 == $v1)
								$sel1 = "SELECTED";
							else 
								$sel1 = "";
								
							$venuedtl .= "<option $sel1 value=\"$v1\">$v2</option>";
						}
						
						$vensel = "venue" . $dtl_idno;
						$dtl_venue = "<select name=\"$vensel\">
											$venuedtl
									</select>
						";
						

					$dtl_instructor = "<input type=\"text\" size=\"25\" name=\"zinstructor\" value=\"{$rowgetschedtime["INSTRUCTOR"]}\"
									style=\"font-size:0.8em;font-weight:Bold;color:Green;height:18px;\" />";
						
					echo "
					<tr $mouseovereffect>
						<td align=\"center\">$dtl_date</td>
						<td>$dtl_start</td>
						<td>$dtl_end</td>
						<td>$dtl_venue</td>
						<td>$dtl_instructor</td>
						<td>
							<input type=\"button\" value=\"POST\" style=\"font-size:0.8em;font-weight:Bold;color:Blue;height:18px;\"
								onclick=\"actionxt.value='savescheddtl';scheduleid.value='$scheduleid';schedtlidno.value='$dtl_idno';submit();\" />
						</td>
					</tr>
					";
					
				}
				
		
				echo "
				</table>
			</div>
		</div>

-->
		
	</div>
	</center>
	
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"scheduleid\" />
	<input type=\"hidden\" name=\"schedtlidno\" />
</form>

</body>
</html>
";

?>

