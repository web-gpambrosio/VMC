<?php

include("veritas/connectdb.php");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
	
$formname = "formcrewutility";
$formtitle = "CREW UTILITY SETUP";

$checkstatus = "";	

//POSTS

if (isset($_POST['applicantno']))
	$applicantno = $_POST['applicantno'];
	
if (isset($_POST['datestart']))
	$datestart = $_POST['datestart'];
	
if (isset($_POST['dateend']))
	$dateend = $_POST['dateend'];
	
if (isset($_POST['recommendby']))
	$recommendby = $_POST['recommendby'];
	
if (isset($_POST['recommenddate']))
	$recommenddate = $_POST['recommenddate'];
	
if (isset($_POST['approvedby']))
	$approvedby = $_POST['approvedby'];
	
if (isset($_POST['approveddate']))
	$approveddate = $_POST['approveddate'];
	
switch ($actiontxt)
{
	case "save"		:
		
			$datestartraw = date('Y-m-d',strtotime($datestart));
			$dateendraw = date('Y-m-d',strtotime($dateend));	
			
			$recommenddateraw = date('Y-m-d',strtotime($recommenddate));
			$approveddateraw = date('Y-m-d',strtotime($approveddate));
			
		
			$qryverify = mysql_query("SELECT APPLICANTNO FROM crewutility WHERE APPLICANTNO='$applicantno'") or die(mysql_error());
		
			if(mysql_num_rows($qryverify) == 0)
			{
				$qrycrewutilitysave = mysql_query("INSERT INTO crewutility(APPLICANTNO,DATESTARTED,DATEENDED,RECOMMENDBY,RECOMMENDDATE,
													APPROVEDBY,APPROVEDDATE)
													VALUES('$applicantno','$datestartraw','$dateendraw','$recommendby','$recommenddateraw',
													'$approvedby','$approveddateraw')
												") or die(mysql_error());
			}
			else 
			{
				$qrycrewutilityupdate = mysql_query("UPDATE crewutility SET 
																	DATESTARTED='$datestartraw',
																	DATEENDED='$dateendraw',
																	RECOMMENDBY='$recommendby',
																	RECOMMENDDATE='$recommenddateraw',
																	APPROVEDBY='$approvedby',
																	APPROVEDDATE='$approveddateraw'
													WHERE APPLICANTNO='$applicantno'
												") or die(mysql_error());
			}
			
			$applicantno = "";
			$datestart = "";
			$dateend = "";
			$recommenddate = "";
			$recommendby = "";
			$approvedby = "";
			$approveddate = "";
			
		break;
		
	case "cancel"	:
		
			$applicantno = "";
			$datestart = "";
			$dateend = "";
			$recommenddate = "";
			$recommendby = "";
			$approvedby = "";
			$approveddate = "";

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

	
$qrycrewutility = mysql_query("SELECT cu.APPLICANTNO,CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,
								cu.DATESTARTED,cu.DATEENDED,cu.RECOMMENDBY,cu.RECOMMENDDATE,
								cu.APPROVEDBY,cu.APPROVEDDATE
								FROM crewutility cu
								LEFT JOIN crew c ON c.APPLICANTNO=cu.APPLICANTNO
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
			
		<div style=\"width:100%;height:300px;float:right;padding:5px 20px 0 20px;overflow:auto;\">
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
					<th>Recommended By</th>
					<th>:</th>
					<th><input type=\"text\" name=\"recommendby\" value=\"$recommendby\" size=\"50\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Recommended Date</th>
					<th>:</th>
					<th>
						<input type=\"text\" name=\"recommenddate\" value=\"$recommenddate\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
						<img src=\"../calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(recommenddate, recommenddate, 'mm/dd/yyyy', 0, 0);return false;\">
					</th>
				</tr>
				<tr>
					<th>Approved By</th>
					<th>:</th>
					<th><input type=\"text\" name=\"approvedby\" value=\"$approvedby\" size=\"50\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Approved Date</th>
					<th>:</th>
					<th>
						<input type=\"text\" name=\"approveddate\" value=\"$approveddate\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
						<img src=\"../calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(approveddate, approveddate, 'mm/dd/yyyy', 0, 0);return false;\">
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
		
		<div style=\"width:100%;height:240px;padding:5px 20px 0 20px;overflow:hidden;\">
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
						<th>RECOMMENDED BY</th>
						<th>APPROVED BY</th>
					</tr>
	";
				while ($rowcrewutility=mysql_fetch_array($qrycrewutility))
				{
					$list1 = $rowcrewutility["APPLICANTNO"];
					$list2 = $rowcrewutility["NAME"];
					
					if ($rowcrewutility["DATESTARTED"] != "")
						$list3 = date('m/d/Y',strtotime($rowcrewutility["DATESTARTED"]));
					else 
						$list3 = "";
						
					if ($rowcrewutility["DATEENDED"] != "")
						$list4 = date('m/d/Y',strtotime($rowcrewutility["DATEENDED"]));
					else 
						$list4 = "";
						
					$list5 = $rowcrewutility["RECOMMENDBY"];
					
					if ($rowcrewutility["RECOMMENDDATE"] != "")
						$list6 = date('m/d/Y',strtotime($rowcrewutility["RECOMMENDDATE"]));
					else 
						$list6 = "";
						
					$list7 = $rowcrewutility["APPROVEDBY"];
					
					if ($rowcrewutility["APPROVEDDATE"] != "")
						$list8 = date('m/d/Y',strtotime($rowcrewutility["APPROVEDDATE"]));
					else 
						$list8 = "";
						
					echo "
					<tr ondblclick=\"
						document.$formname.applicantno.value='$list1';
						document.$formname.datestart.value='$list3';
						document.$formname.dateend.value='$list4';
						document.$formname.recommendby.value='$list5';
						document.$formname.recommenddate.value='$list6';
						document.$formname.approvedby.value='$list7';
						document.$formname.approveddate.value='$list8';
						\">
						<td align=\"center\">$list1</td>
						<td>$list2</td>
						<td align=\"center\">$list3</td>
						<td align=\"center\">$list4</td>
						<td align=\"center\">$list5</td>
						<td align=\"center\">$list7</td>
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

