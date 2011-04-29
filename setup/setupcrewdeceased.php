<?php

include("veritas/connectdb.php");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
	
$formname = "formdeceased";
$formtitle = "CREW DECEASED SETUP";

$checkstatus = "";	

//POSTS

if (isset($_POST['applicantno']))
	$applicantno = $_POST['applicantno'];
	
if (isset($_POST['datedeceased']))
	$datedeceased = $_POST['datedeceased'];
	
if (isset($_POST['reason']))
	$reason = $_POST['reason'];


switch ($actiontxt)
{
	case "save"		:
		
			$datedeceasedraw = date('Y-m-d',strtotime($datedeceased));	
		
			$qryverify = mysql_query("SELECT APPLICANTNO FROM crewdeceased WHERE APPLICANTNO='$applicantno'") or die(mysql_error());
		
			if(mysql_num_rows($qryverify) == 0)
			{
				$qrydeceasedsave = mysql_query("INSERT INTO crewdeceased(APPLICANTNO,DATEDECEASED,REASON,MADEBY,MADEDATE)
													VALUES('$applicantno','$datedeceasedraw','$reason','$employeeid','$currentdate')
												") or die(mysql_error());
			}
			else 
			{
				$qrydeceasedupdate = mysql_query("UPDATE crewdeceased SET REASON='$reason',
																	DATEDECEASED='$datedeceasedraw',
																	MADEBY='$employeeid',
																	MADEDATE='$currentdate'
													WHERE APPLICANTNO='$applicantno'
												") or die(mysql_error());
			}
			
			$applicantno = "";
			$datedeceased = "";
			$reason = "";
			
		break;
		
	case "cancel"	:
		
			$applicantno = "";
			$datedeceased = "";
			$reason = "";

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

	
$qrycrewdeceased = mysql_query("SELECT cd.APPLICANTNO,CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,
								cd.DATEDECEASED,cd.REASON,cd.MADEBY,cd.MADEDATE
								FROM crewdeceased cd
								LEFT JOIN crew c ON c.APPLICANTNO=cd.APPLICANTNO
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
					<th>Date Deceased</th>
					<th>:</th>
					<th>
						<input type=\"text\" name=\"datedeceased\" value=\"$datedeceased\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
						<img src=\"../calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(datedeceased, datedeceased, 'mm/dd/yyyy', 0, 0);return false;\">
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
					<th><input type=\"button\" name=\"\" value=\"Save\" onclick=\"actiontxt.value='save';checksave();\" />
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
						<th>DATE</th>
						<th>REASON</th>
						<th>MADEBY</th>
						<th>MADEDATE</th>
					</tr>
	";
				while ($rowcrewdeceased=mysql_fetch_array($qrycrewdeceased))
				{
					$list1 = $rowcrewdeceased["APPLICANTNO"];
					$list2 = $rowcrewdeceased["NAME"];
					$list3 = $rowcrewdeceased["DATEDECEASED"];
					$list4 = $rowcrewdeceased["REASON"];
						
					$list5 = $rowcrewdeceased["MADEBY"];
					$list6 = date('m-d-Y',strtotime($rowcrewdeceased["MADEDATE"]));
					
					echo "
					<tr ondblclick=\"
						document.$formname.applicantno.value='$list1';
						document.$formname.datedeceased.value='$list3';
						document.$formname.reason.value='$list4';
						\">
						<td align=\"center\">$list1</td>
						<td>$list2</td>
						<td align=\"center\">$list3</td>
						<td align=\"center\">$list4</td>
						<td align=\"center\">$list5</td>
						<td align=\"center\">$list6</td>
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
</form>

</body>
</html>
";

?>

