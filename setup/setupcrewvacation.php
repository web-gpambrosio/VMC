<?php

include("veritas/connectdb.php");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
	
$formname = "formcrewvacation";
$formtitle = "CREW VACATION SETUP";

$checkstatus = "";	

//POSTS

if (isset($_POST['idno']))
	$idno = $_POST['idno'];
else 
	$idno = "";
	
if (isset($_POST['applicantno']))
	$applicantno = $_POST['applicantno'];
	
if (isset($_POST['datestart']))
	$datestart = $_POST['datestart'];
	
if (isset($_POST['dateend']))
	$dateend = $_POST['dateend'];
	
if (isset($_POST['reason']))
	$reason = $_POST['reason'];


switch ($actiontxt)
{
	case "save"		:
		
			$datestartraw = date('Y-m-d',strtotime($datestart));
			$dateendraw = date('Y-m-d',strtotime($dateend));
		
			$qryverify = mysql_query("SELECT APPLICANTNO FROM crewvacation WHERE IDNO='$idno'") or die(mysql_error());
		
			if(mysql_num_rows($qryverify) == 0)
			{
				$qrycrewvacationsave = mysql_query("INSERT INTO crewvacation(APPLICANTNO,DATESTART,DATEEND,REASON,MADEBY,MADEDATE)
													VALUES('$applicantno','$datestartraw','$dateendraw','$reason','$employeeid','$currentdate')
												") or die(mysql_error());
			}
			else 
			{
				$qrycrewvacationupdate = mysql_query("UPDATE crewvacation SET REASON='$reason',
																	DATESTART='$datestartraw',
																	DATEEND='$dateendraw',
																	MADEBY='$employeeid',
																	MADEDATE='$currentdate'
													WHERE IDNO='$idno'
												") or die(mysql_error());
			}
			
			$idno = "";
			$applicantno = "";
			$datestart = "";
			$dateend = "";
			$reason = "";
			
		break;
		
	case "cancel"	:
		
			$idno = "";
			$applicantno = "";
			$datestart = "";
			$dateend = "";
			$reason = "";

		break;
		
	case "display"	:
			
			$qrydisplay = mysql_query("SELECT * FROM crewvacation WHERE IDNO='$idno'") or die(mysql_error());
			
			$rowdisplay = mysql_fetch_array($qrydisplay);
			
				$idno = $rowdisplay["IDNO"];
				$applicantno = $rowdisplay["APPLICANTNO"];
				$datestart = date('m/d/Y',strtotime($rowdisplay["DATESTART"]));
				$dateend = date('m/d/Y',strtotime($rowdisplay["DATEEND"]));
				$reason = $rowdisplay["REASON"];
		
		break;
}
	

/* LISTINGS  */

$qrycrewsel = mysql_query("SELECT APPLICANTNO,CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME FROM crew ORDER BY NAME") or die(mysql_error());

	$select1 = "<option selected value=\"\">--Select One--</option>";
	while($rowcrewsel = mysql_fetch_array($qrycrewsel))
	{
		$appno = $rowcrewsel["APPLICANTNO"];
		$name = $rowcrewsel["NAME"];
		
		$selected1 = "";
		
		if ($applicantno == $appno)
			$selected1 = "SELECTED";
			
		$select1 .= "<option $selected1 value=\"$appno\">$name</option>";
	}

	
$qrycrewvacation = mysql_query("SELECT cv.IDNO,cv.APPLICANTNO,CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,
								cv.DATESTART,cv.DATEEND,cv.REASON,cv.MADEBY,cv.MADEDATE
								FROM crewvacation cv
								LEFT JOIN crew c ON c.APPLICANTNO=cv.APPLICANTNO
								ORDER BY NAME") or die(mysql_error());

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

	}
			
	if(rem=='')
	{
		$formname.submit();
	}
	else
		alert('Please CHECK the following: ' + rem + ' before saving!');		

}
	
</script>
</head>
<body>

<form name=\"$formname\" method=\"POST\">
	
	<center>
	<div style=\"width:100%;height:500px;padding:5px 20px 0 20px;\">
			
		<div style=\"width:100%;height:250px;float:right;padding:5px 20px 0 20px;overflow:auto;\">
			<br />
			<span class=\"sectiontitle\">$formtitle</span>
			<br />
			
			<table class=\"setup\" width=\"100%\" >	
				<tr>
					<th>Crew</th>
					<th>:</th>
					<th><select name=\"applicantno\">
							$select1
						</select>
					</th>
				</tr>
				<tr>
					<th>Date Start</th>
					<th>:</th>
					<th>
						<input type=\"text\" name=\"datestart\" value=\"$datestart\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
						<img src=\"../calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(datestart, datestart, 'mm/dd/yyyy', 0, 0);return false;\">
					</th>
				</tr>
				<tr>
					<th>Date End</th>
					<th>:</th>
					<th>
						<input type=\"text\" name=\"dateend\" value=\"$dateend\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
						<img src=\"../calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(dateend, dateend, 'mm/dd/yyyy', 0, 0);return false;\">
					</th>
				</tr>
				<tr>
					<th>Reason</th>
					<th>:</th>
					<th>
						<textarea rows=\"4\" cols=\"20\" name=\"reason\">$reason</textarea>
					</th>
				</tr>
				<tr>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
					<th><input type=\"button\" name=\"\" value=\"Save\" onclick=\"idno.value='$idno';actiontxt.value='save';checksave();\" />
						<input type=\"button\" name=\"\" value=\"Cancel\" onclick=\"actiontxt.value='cancel';$formname.submit();\" />
					</th>
				</tr>			
			</table>
		</div>
		
		<div style=\"width:100%;height:290px;padding:5px 20px 0 20px;overflow:hidden;\">
			<br />
			<span class=\"sectiontitle\">CURRENT LISTING</span>
			<br />
			
			<div style=\"width:100%;height:240px;padding:0;overflow:auto;\">
				<table width=\"100%\" class=\"listcol\">
					<tr>
						<th>APPLICANTNO</th>
						<th>NAME</th>
						<th>START</th>
						<th>END</th>
						<th>REASON</th>
						<th>MADEBY</th>
						<th>MADEDATE</th>
					</tr>
	";
				while ($rowcrewvacation=mysql_fetch_array($qrycrewvacation))
				{
					$list1 = $rowcrewvacation["IDNO"];
					$list2 = $rowcrewvacation["APPLICANTNO"];
					$list3 = $rowcrewvacation["NAME"];
					$list4 = date('m/d/Y',strtotime($rowcrewvacation["DATESTART"]));
					$list5 = date('m/d/Y',strtotime($rowcrewvacation["DATEEND"]));
					$list6 = $rowcrewvacation["REASON"];
						
					$list7 = $rowcrewvacation["MADEBY"];
					$list8 = date('m-d-Y',strtotime($rowcrewvacation["MADEDATE"]));
					
					echo "
					<tr ondblclick=\"idno.value='$list1';actiontxt.value='display';submit();\">
						<td align=\"center\">$list2</td>
						<td>$list3</td>
						<td align=\"center\">$list4</td>
						<td align=\"center\">$list5</td>
						<td align=\"center\">$list6</td>
						<td align=\"center\">$list7</td>
						<td align=\"center\">$list8</td>
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
	<input type=\"hidden\" name=\"idno\" />
</form>

</body>
</html>
";

?>

