<?php
include('veritas/connectdb.php');
include("veritas/include/stylephp.inc");
include("veritas/include/functions.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');
$basedirid = "idpics/";
$basedirdocs = "docimages/";

$datenow = date("Y-m-d");

if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
else 
	$employeeid = "";

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];

if (isset($_POST['applicantno']))
	$applicantno = $_POST['applicantno'];
	
if (isset($_POST['schedid']))
	$schedid = $_POST['schedid'];
	
if (isset($_POST['classcardno']))
	$classcardno = $_POST['classcardno'];
	
if (isset($_POST['postidno']))
	$postidno = $_POST['postidno'];
	
if (isset($_POST['grade']))
	$grade = $_POST['grade'];
	
if (isset($_POST['remarks']))
	$remarks = $_POST['remarks'];
	
if (isset($_POST['status']))
	$status = $_POST['status'];
else 
	$status = "1";

if (isset($_POST["searchkey"]))
	$searchkey = $_POST["searchkey"];
	
if (isset($_POST["searchby"]))
	$searchby = $_POST["searchby"];
	
if(empty($searchby))
	$searchby="SCHEDID";
$$searchby="selected";
	
if ($searchkey == "")
	$disablesearch = "disabled=\"disabled\"";
else 
	$disablesearch = "";

	
$showmultiple = "display:none;";
$multiple = 0;

$disabledinput = "disabled=\"disabled\"";

switch ($status)
{
	case "1" : $chkcompleted = "checked=\"checked\""; break;
	case "2" : $chkincomplete = "checked=\"checked\""; break;
}


switch ($actiontxt)
{
	case "find"	:
		$applicantno="";
		$schedid="";
		
		if($searchby=="SCHEDID")
		{
			$addorder="ct.$searchby";
			$addwhere="AND $addorder = '$searchkey'";
		}
		else
		{
			$addorder="c.$searchby";
			$addwhere="AND $addorder LIKE '$searchkey%' ";
		}
		$errormsg = "";
		$qrysearch = mysql_query("SELECT c.APPLICANTNO,c.CREWCODE 
			FROM crew c
			LEFT JOIN crewtraining ct ON c.APPLICANTNO=ct.APPLICANTNO
			LEFT JOIN trainingschedhdr th ON th.SCHEDID=ct.SCHEDID
			LEFT JOIN trainingcourses tc ON tc.TRAINCODE=th.TRAINCODE
			WHERE ct.POSTDATE IS NULL AND ct.CLASSCARDNO IS NOT NULL AND tc.STATUS = 1 $addwhere
			GROUP BY c.APPLICANTNO
			ORDER BY $addorder") or die(mysql_error());
		
//		switch ($searchby)
//		{
//			case "1"	: //APPLICANT NO
//				
//					$qrysearch = mysql_query("SELECT APPLICANTNO,CREWCODE FROM crew WHERE APPLICANTNO LIKE '$searchkey%' ORDER BY APPLICANTNO") or die(mysql_error());
//				
//				break;
//			case "2"	: //CREW CODE
//				
//					$qrysearch = mysql_query("SELECT APPLICANTNO,CREWCODE FROM crew WHERE CREWCODE LIKE '$searchkey%' ORDER BY CREWCODE") or die(mysql_error());
//				
//				break;
//			case "3"	: //FAMILY NAME
//				
//					$qrysearch = mysql_query("SELECT APPLICANTNO,CREWCODE FROM crew WHERE FNAME LIKE '$searchkey%' ORDER BY FNAME") or die(mysql_error());
//				
//				break;
//			case "4"	: //GIVEN NAME
//				
//					$qrysearch = mysql_query("SELECT APPLICANTNO,CREWCODE FROM crew WHERE GNAME LIKE '$searchkey%' ORDER BY GNAME") or die(mysql_error());
//				
//				break;
//		}
		
		if(mysql_num_rows($qrysearch) == 0)
		{
			echo "<script>alert('Search produced 0 record!');</script>";
			$searchkey="";
			$disablesearch = "disabled=\"disabled\"";
		}
		
		if($searchby!="SCHEDID")
		{
			if (mysql_num_rows($qrysearch) == 1)
			{
				$rowsearch = mysql_fetch_array($qrysearch);
				$applicantno = $rowsearch["APPLICANTNO"];
				$classcardno = "";
			}
			elseif (mysql_num_rows($qrysearch) > 1)
			{
				$showmultiple = "display:block;";
				$multiple = 1;
			}
		}
		else 
		{
			if(mysql_num_rows($qrysearch) != 0)
				$schedid=$searchkey;
		}
	break;
	
	case "postgrade":

			$qrypostgrade = mysql_query("UPDATE crewtraining SET GRADE=$grade,
																STATUS=$status,
																REMARKS = '$remarks',
																POSTBY = '$employeeid',
																POSTDATE = '$currentdate'
										WHERE IDNO=$postidno AND APPLICANTNO=$applicantno
										") or die(mysql_error());
			
			$postidno = "";
			
		break;
	
}

//initialize second column of CURRENTLY ENROLLED TRAININGS
$colhdr2="TRAINING";

if (!empty($applicantno) && $searchby!="SCHEDID")
{
	$qryenrolled = mysql_query("SELECT ct.IDNO,ct.SCHEDID,th.TRAINCODE,tc.TRAINING,th.DATEFROM,th.DATETO,th.STATUS,c.COURSETYPECODE,c.COURSETYPE,ct.CLASSCARDNO
								FROM crewtraining ct
								LEFT JOIN trainingschedhdr th ON th.SCHEDID=ct.SCHEDID
								LEFT JOIN trainingcourses tc ON tc.TRAINCODE=th.TRAINCODE
								LEFT JOIN coursetype c ON c.COURSETYPECODE=tc.COURSETYPECODE
								WHERE ct.APPLICANTNO=$applicantno
								AND ct.POSTDATE IS NULL AND ct.CLASSCARDNO IS NOT NULL
								AND tc.STATUS = 1 AND ct.FINALIZEDDATE IS NULL
								") or die(mysql_error());
	
	$qrycrew = mysql_query("SELECT CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,CREWCODE
							FROM crew
							WHERE APPLICANTNO=$applicantno
							") or die(mysql_error());
	
	$rowcrew = mysql_fetch_array($qrycrew);
	$crewcode = $rowcrew["CREWCODE"];
	$crewname = $rowcrew["NAME"];
}
if($searchby=="SCHEDID" && !empty($schedid))
{
	$colhdr2="CREW NAME";
	$qryenrolled = mysql_query("SELECT ct.IDNO,th.TRAINCODE,tc.TRAINING,th.DATEFROM,th.DATETO,th.STATUS,c.COURSETYPECODE,c.COURSETYPE,ct.CLASSCARDNO,
								CONCAT(FNAME,', ',GNAME,' ',LEFT(MNAME,1),'.') AS NAME,ct.APPLICANTNO
								FROM crewtraining ct
								LEFT JOIN crew cr ON ct.APPLICANTNO=cr.APPLICANTNO
								LEFT JOIN trainingschedhdr th ON th.SCHEDID=ct.SCHEDID
								LEFT JOIN trainingcourses tc ON tc.TRAINCODE=th.TRAINCODE
								LEFT JOIN coursetype c ON c.COURSETYPECODE=tc.COURSETYPECODE
								WHERE ct.SCHEDID=$schedid
								AND ct.POSTDATE IS NULL AND ct.CLASSCARDNO IS NOT NULL
								AND tc.STATUS = 1 AND ct.FINALIZEDDATE IS NULL
								ORDER BY CONCAT(FNAME,', ',GNAME,' ',MNAME)
								") or die(mysql_error());
	if(!empty($applicantno))
	{
		$qrycrew = mysql_query("SELECT CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,CREWCODE
							FROM crew
							WHERE APPLICANTNO=$applicantno
							") or die(mysql_error());
	
		$rowcrew = mysql_fetch_array($qrycrew);
		$crewcode = $rowcrew["CREWCODE"];
		$crewname = $rowcrew["NAME"];
	}
}

if (!empty($postidno))
{
	$qrytraindtls = mysql_query("SELECT ct.IDNO,ct.SCHEDID,th.TRAINCODE,tc.TRAINING,th.DATEFROM,th.DATETO,th.STATUS,ct.GRADE,ct.REMARKS
								FROM crewtraining ct
								LEFT JOIN trainingschedhdr th ON th.SCHEDID=ct.SCHEDID
								LEFT JOIN trainingcourses tc ON tc.TRAINCODE=th.TRAINCODE
								WHERE ct.IDNO=$postidno AND APPLICANTNO=$applicantno
					") or die(mysql_error());
	
	$disabledinput = "";
}


echo "
<html>\n
<head>\n
<title>Training - Grade Posting</title>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>

<body onload=\"document.gradeposting.searchkey.select();\" style=\"overflow:hidden;background-color:White;\">\n

<form name=\"gradeposting\" method=\"POST\">\n


<div id=\"multiple\" style=\"position:absolute;top:230px;left:200px;width:600px;height:400px;background-color:#6699FF;
				border:2px solid black;overflow:auto;$showmultiple \">
	<span class=\"sectiontitle\">MULTIPLE ENTRY FOUND</span>
	<br />
	
	<table width=\"100%\" class=\"listcol\">
		<tr>
			<th width=\"15%\">APPLICANT NO</th>
			<th width=\"15%\">CREW CODE</th>
			<th width=\"20%\">FNAME</th>
			<th width=\"20%\">GNAME</th>
			<th width=\"20%\">MNAME</th>
			<th width=\"10%\">STATUS</th>
		</tr>
	";
		if ($multiple == 1)
		{
			while ($rowmultisearch = mysql_fetch_array($qrysearch))
			{
				$appno = $rowmultisearch["APPLICANTNO"];
				
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
				if ($rowgetinfo["STATUS"] == 1)
					$info6 = "ACTIVE";
				else 
					$info6 = "INACTIVE";
				
				echo "
				<tr $mouseovereffect style=\"cursor:pointer;\" ondblclick=\"
								applicantno.value='$info1';submit();
								document.getElementById('multiple').style.display='none';
								\">
					<td align=\"center\">$info1</td>
					<td align=\"center\">$info2</td>
					<td>$info3&nbsp;</td>
					<td>$info4&nbsp;</td>
					<td>$info5&nbsp;</td>
					<td align=\"center\">$info6</td>
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


<span class=\"wintitle\">GRADE POSTING</span>

	<div style=\"width:100%;height:650px;background-color:Silver;\">

		<center>
		<table style=\"width:70%;margin-top:5px;\" border=1>
			<tr>
				<td align=\"left\" style=\"font-size:0.8em;font-weight:Bold;\">Search By</td>
				<td>
					<select name=\"searchby\" onchange=\"if(this.value != ''){searchkey.disabled=false;searchkey.value='';searchkey.focus();}
														else {submit();}\">
						<option value=\"\">--Select Search Key--</option>
					";
	
//					$selected1 = "";
//					$selected2 = "";
//					$selected3 = "";
//					$selected4 = "";
//	
//					switch ($searchby)
//					{
//						case "1"	: //BY APPLICANT NO
//								$selected1 = "SELECTED";
//							break;
//						case "2"	: //BY CREW CODE
//								$selected2 = "SELECTED";
//							break;
//						case "3"	: //BY FAMILY NAME
//								$selected3 = "SELECTED";
//							break;
//						case "4"	: //BY GIVEN NAME
//								$selected4 = "SELECTED";
//							break;
//					}
	
				echo "
						<option $SCHEDID value=\"SCHEDID\">SCHED ID</option>
						<option $APPLICANTNO value=\"APPLICANTNO\">APPLICANT NO</option>
						<option $CREWCODE value=\"CREWCODE\">CREW CODE</option>
						<option $FNAME value=\"FNAME\">FAMILY NAME</option>
						<option $GNAME value=\"GNAME\">GIVEN NAME</option>
					</select>
				</td>
				<td align=\"left\" style=\"font-size:0.8em;font-weight:Bold;\">Search Key</td>
				<td align=\"center\">
						<input type=\"text\" name=\"searchkey\" size=\"20\" value=\"$searchkey\"
						onkeyup=\"this.value=this.value.toUpperCase();if (this.value != '') {btnfind.disabled=false;}else{btnfind.disabled=true;}\"
						style=\"color:Red;font-weight:Bold;\" onkeydown=\"if(event.keyCode==13) {btnfind.focus();}\"/>
						
					<input type=\"button\" name=\"btnfind\" value=\"Find\" $disablesearch onclick=\"actiontxt.value='find';submit();\" />
				</td>
			</tr>
		</table>
		</center>
		<br />
		
		<div style=\"width:100%;height:130px;margin:3 5 3 5;background-color:Black;\">
		
			<span class=\"sectiontitle\">CREW INFORMATION</span>
			
			<div style=\"width:80%;height:100px;float:left;\">
			";
				$stylehdr = "style=\"font-size:0.7em;font-weight:Bold;color:White;\"";
				$styledtl = "style=\"font-size:0.9em;font-weight:Bold;color:Yellow;\"";
				
			echo "
				<div style=\"width:100%;padding:5 5 5 10;\">
					<table style=\"width:50%;float:left;\">
						<tr>
							<td $stylehdr>APPLICANT NO.</td>
							<td $stylehdr>:</td>
							<td $styledtl>$applicantno</td>
						</tr>
						<tr>
							<td $stylehdr>CREW CODE</td>
							<td $stylehdr>:</td>
							<td $styledtl>$crewcode</td>
						</tr>
						<tr>
							<td $stylehdr>NAME</td>
							<td $stylehdr>:</td>
							<td $styledtl>$crewname</td>
						</tr>
						";
						
						if (!empty($applicantno))
						{
						echo "
						<tr>
							<td colspan=\"3\">
								<input type=\"button\" value=\"Upgrading Record Encoding/Printing\"
									style=\"cursor:pointer;border:1px solid White;background-color:Green;color:Yellow;\"
									 onclick=\"openWindow('upgradingrecord.php?applicantno=$applicantno', 'upgradingrecord' ,700, 550);\"
									/>
							</td>
						</tr>
						";
						}
					echo "
					</table>
				</div>
				
			</div>
			
			<div style=\"width:20%;float:right;color:Orange;\">
	";
				$dirfilename = $basedirid . $applicantno . ".jpg";
				if (checkpath($dirfilename))
				{
					$scale = imageScale($dirfilename,-1,100);
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
			
		</div>
		
		<div style=\"width:100%;height:410px;background-color:Gray;margin:3 5 3 5;border:1px solid Black;overflow:auto;\">
		
			<div style=\"width:100%;height:390px;overflow:auto;\">
			
				<div style=\"width:58%;height:95%;margin:15 5 0 5;background-color:Silver;float:left;border:1px solid black;overflow:auto;\">
					<span class=\"sectiontitle\">CURRENTLY ENROLLED TRAININGS</span>
					
					<table width=\"100%\" class=\"listcol\">
						<tr>
							<th width=\"15%\">SCHEDID</th>
							<th width=\"40%\">$colhdr2</th>
							<th width=\"15%\">CARD NO</th>
							<th width=\"15%\">FROM</th>
							<th width=\"15%\">TO</th>
						</tr>
					";
	
					$styledtl2 = "style=\"color:Black;border-bottom:1px dashed gray;cursor:pointer;\"";
	
					$coursetypecodetmp = "";

					while ($rowenrolled = mysql_fetch_array($qryenrolled))
					{
						$enrollidno = $rowenrolled["IDNO"];
						
						if($searchby=="SCHEDID")
						{
							$enrollappno = "applicantno.value='".$rowenrolled["APPLICANTNO"]."'";
							$enrolltraining = $rowenrolled["NAME"];
							$enrollschedid = $schedid;
						}
						else 
						{
							$enrollappno="";
							$enrolltraining = $rowenrolled["TRAINING"];
							$enrollschedid = $rowenrolled["SCHEDID"];
						}
						
						if (!empty($rowenrolled["DATEFROM"]))
							$enrollfrom = date("dMY",strtotime($rowenrolled["DATEFROM"]));
						else 
							$enrollfrom = "";
							
						if (!empty($rowenrolled["DATETO"]))
							$enrollto = date("dMY",strtotime($rowenrolled["DATETO"]));
						else 
							$enrollto = "";
							
						$enrollcoursetypecode = $rowenrolled["COURSETYPECODE"];
						$enrollcoursetype = $rowenrolled["COURSETYPE"];
						$enrollclasscardno = $rowenrolled["CLASSCARDNO"];
						
						if ($coursetypecodetmp != $enrollcoursetypecode)
						{
							echo "
							<tr>
								<td colspan=\"5\" style=\"font-size:1.2em;font-weight:Bold;background-color:Navy;color:Yellow;\">$enrollcoursetype</td>
							</tr>
							";
						}
						
						echo "
						<tr onclick=\"$enrollappno;postidno.value='$enrollidno';submit();\" $mouseovereffect>
							<td align=\"center\" $styledtl2>$enrollschedid</td>
							<td $styledtl2>$enrolltraining</td>
							<td align=\"center\" $styledtl2>$enrollclasscardno</td>
							<td align=\"center\" $styledtl2>$enrollfrom</td>
							<td align=\"center\" $styledtl2>$enrollto</td>
						</tr>
						";
						
						$coursetypecodetmp = $enrollcoursetypecode;
					}
					
					echo "
					</table>
					
				</div>
				
				<div style=\"width:38%;height:85%;margin:15 5 0 5;background-color:Silver;float:right;border:1px solid black;\">
					<span class=\"sectiontitle\">TRAINING DETAILS</span>
					";
					
					$rowtraindtls = mysql_fetch_array($qrytraindtls);
					
					$trainschedid = $rowtraindtls["SCHEDID"];
					$trainidno = $rowtraindtls["IDNO"];
					$training = $rowtraindtls["TRAINING"];
					
					if (!empty($rowtraindtls["DATEFROM"]))
						$trainfrom = date("dMY",strtotime($rowtraindtls["DATEFROM"]));
					else 
						$trainfrom = "";
						
					if (!empty($rowtraindtls["DATETO"]))
						$trainto = date("dMY",strtotime($rowtraindtls["DATETO"]));
					else 
						$trainto = "";
					
					$traingrade = $rowtraindtls["GRADE"];
					$trainremarks = $rowtraindtls["REMARKS"];
					
					$trainstyle1 = "style=\"font-size:0.7em;font-weight:Bold;color:Black;\"";
					$trainstyle2 = "style=\"font-size:0.8em;font-weight:Bold;color:Blue;\"";
					
					echo "
					<br /><br />
					<center>
					<table style=\"width:90%;\">
						<tr>
							<td $trainstyle1 width=\"30%\">SCHED ID</td>
							<td $trainstyle1 width=\"10%\">:</td>
							<td $trainstyle2 width=\"60%\">$trainschedid</td>
						</tr>
						<tr>
							<td $trainstyle1>TRAINING</td>
							<td $trainstyle1>:</td>
							<td $trainstyle2>$training</td>
						</tr>
						<tr>
							<td $trainstyle1>FROM</td>
							<td $trainstyle1>:</td>
							<td $trainstyle2>$trainfrom</td>
						</tr>
						<tr>
							<td $trainstyle1>TO</td>
							<td $trainstyle1>:</td>
							<td $trainstyle2>$trainto</td>
						</tr>
						<tr>
							<td $trainstyle1>GRADE</td>
							<td $trainstyle1>:</td>
							<td><input type=\"text\" name=\"grade\" $disabledinput size=\"5\" maxlengtd=\"5\" style=\"font-size:1.3em;font-weight:Bold;color:Red;\" 
								onKeyPress=\"return amountonly(this);\" value=\"$traingrade\" />
							</td>
						</tr>
						<tr>
							<td $trainstyle1>REMARKS</td>
							<td $trainstyle1>:</td>
							<td><textarea rows=\"6\" cols=\"30\" name=\"remarks\" $disabledinput >$trainremarks</textarea></td>
						</tr>
						<tr>
							<td $trainstyle1>STATUS</td>
							<td $trainstyle1>:</td>
							<td $trainstyle2>
								<input type=\"radio\" name=\"status\" $chkcompleted value=\"1\" $disabledinput />Completed
								<input type=\"radio\" name=\"status\" $chkincomplete value=\"2\" $disabledinput />Incomplete
							</td>
						</tr>
					</table>
					</center>

				</div>
				
				<div style=\"width:38%;height:9%;margin:2 5 0 5;border:1px solid black;float:right;background-color:White;\">
				
					<center>

						<input type=\"button\" name=\"btnpost\" value=\"POST GRADE\" $disabledinput style=\"font-size:0.9em;font-weight:Bold;\"
						onclick=\"if (grade.value != '') {postidno.value='$trainidno';actiontxt.value='postgrade';submit();}
									else {alert('Invalid Grade. Please try again.');}\" />
						
						<input type=\"button\" name=\"btncancel\" $disabledinput value=\"Cancel\" 
						onclick=\"submit();\" />
					</center>
					
				</div>
				
				
			</div>

		</div>
		

	</div>
	
	
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"applicantno\" value=\"$applicantno\" />
	<input type=\"hidden\" name=\"schedid\" value=\"$schedid\" />
	<input type=\"hidden\" name=\"postidno\" />

	
</form>

</body>
";
?>