<?php

include("veritas/connectdb.php");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
		
$formname = "formtrainingcourses";
$formtitle = "TRAINING COURSES SETUP";

//POSTS

if(isset($_POST['traincode']))
	$traincode=$_POST['traincode'];

if(isset($_POST['traincode1']))
	$traincode1=$_POST['traincode1'];

if(isset($_POST['training']))
	$training=$_POST['training'];

if(isset($_POST['description']))
	$description=$_POST['description'];

if(isset($_POST['coursetypecode']))
	$coursetypecode=$_POST['coursetypecode'];
else 
	$coursetypecode = "INHSE";

if(isset($_POST['trainingcentercode']))
	$trainingcentercode=$_POST['trainingcentercode'];
else 
	$trainingcentercode = "";

if(isset($_POST['trainingvenuecode']))
	$trainingvenuecode=$_POST['trainingvenuecode'];
else 
	$trainingvenuecode = "";

if(isset($_POST['documentcode']))
	$documentcode=$_POST['documentcode'];

if(isset($_POST['managementcode']))
	$managementcode=$_POST['managementcode'];
else 
	$managementcode = "";

if(isset($_POST['alias']))
	$alias=$_POST['alias'];

if(isset($_POST['status']))
{
	$status=1;
	$checkstatus = "checked=\"checked\"";
}
else 
	$status=0;


switch ($actiontxt)
{
	case "save"		:
		
			$qryverify = mysql_query("SELECT TRAINCODE FROM trainingcourses WHERE TRAINCODE='$traincode'") or die(mysql_error());
		
			if ($trainingcentercode == "")
				$trainingcentercode = "NULL";
			else 
				$trainingcentercode = "'" . $trainingcentercode . "'";
				
			if ($trainingvenuecode == "")
				$trainingvenuecode = "NULL";
			else 
				$trainingvenuecode = "'" . $trainingvenuecode . "'";
				
			if ($managementcode == "")
				$managementcode = "NULL";
			else 
				$managementcode = "'" . $managementcode . "'";
			
//			echo "INSERT INTO trainingcourses(TRAINCODE,TRAINING,DESCRIPTION,COURSETYPECODE,
//												TRAINCENTERCODE,TRAINVENUECODE,DOCCODE,STATUS,MADEBY,MADEDATE,ALIAS,MANAGEMENTCODE) 
//												VALUES('$traincode','$training','$description','$coursetypecode',$trainingcentercode,$trainingvenuecode,
//														'$documentcode',$status,'$employeeid','$currentdate','$alias',$managementcode)
//											";
				
			if(mysql_num_rows($qryverify) == 0)
			{
				$qrytrainingcoursesave = mysql_query("INSERT INTO trainingcourses(TRAINCODE,TRAINING,DESCRIPTION,COURSETYPECODE,
												TRAINCENTERCODE,TRAINVENUECODE,DOCCODE,STATUS,MADEBY,MADEDATE,ALIAS,MANAGEMENTCODE) 
												VALUES('$traincode','$training','$description','$coursetypecode',$trainingcentercode,$trainingvenuecode,
														'$documentcode',$status,'$employeeid','$currentdate','$alias',$managementcode)
											") or die(mysql_error());
			}
			else 
			{
				$qrytrainingcourseupdate = mysql_query("UPDATE trainingcourses SET TRAINING='$training',
															DESCRIPTION='$description',
															COURSETYPECODE='$coursetypecode',
															TRAINCENTERCODE=$trainingcentercode,
															TRAINVENUECODE=$trainingvenuecode,
															DOCCODE='$documentcode',
															STATUS=$status,
															MADEBY='$employeeid',
															MADEDATE='$currentdate',
															ALIAS='$alias',
															MANAGEMENTCODE=$managementcode
												 WHERE TRAINCODE='$traincode'
											") or die(mysql_error());
			}
			
			$traincode = "";
			$training = "";
			$description = "";
//			$coursetypecode = "";
			$trainingcentercode = "";
			$trainingvenuecode = "";
			$traindoccode = "";
			$documentcode = "";
			$alias = "";
			$managementcode = "";
			$status = 0;

			$checkstatus = "";		
		break;
		
	case "cancel"	:
		
			$traincode = "";
			$training = "";
			$description = "";
			$coursetypecode = "";
			$trainingcentercode = "";
			$trainingvenuecode = "";
			$traindoccode = "";
			$documentcode = "";
			$alias = "";
			$managementcode = "";
			$status = 0;

			$checkstatus = "";	

		break;
		
	case "update"	:
		
			$qrytrainingdisplay = mysql_query("SELECT t.*,tc.TRAINCENTER,tv.TRAINVENUE,cd.DOCUMENT
												FROM trainingcourses t
												LEFT JOIN trainingcenter tc ON tc.TRAINCENTERCODE=t.TRAINCENTERCODE
												LEFT JOIN trainingvenue tv ON tv.TRAINVENUECODE=t.TRAINVENUECODE
												LEFT JOIN crewdocuments cd ON cd.DOCCODE=t.DOCCODE
												WHERE t.TRAINCODE='$traincode1'
								") or die(mysql_error());
			
			$rowtrainingdisplay = mysql_fetch_array($qrytrainingdisplay);
			
			$traincode = $rowtrainingdisplay["TRAINCODE"];
			$training = $rowtrainingdisplay["TRAINING"];
			$description = $rowtrainingdisplay["DESCRIPTION"];
			$coursetypecode = $rowtrainingdisplay["COURSETYPECODE"];
			$trainingcentercode = $rowtrainingdisplay["TRAINCENTERCODE"];
			$trainingvenuecode = $rowtrainingdisplay["TRAINVENUECODE"];
			
			if ($rowtrainingdisplay["DOCCODE"] != "")
				$traindoccode = $rowtrainingdisplay["DOCCODE"] . " - " . $rowtrainingdisplay["DOCUMENT"];
			else 
				$traindoccode = "-- NO CODE YET --";

			if ($rowtrainingdisplay["STATUS"])
				$checkstatus = "checked=\"checked\"";
			else 
				$checkstatus = "";
				
			$managementcode = $rowtrainingdisplay["MANAGEMENTCODE"];
			$alias = $rowtrainingdisplay["ALIAS"];
		
		break;
		
	case "delete"	:

		break;
}
	

/* LISTINGS  */

$qrytraincentersel = mysql_query("SELECT TRAINCENTERCODE,TRAINCENTER FROM trainingcenter WHERE STATUS=1 ORDER BY TRAINCENTER") or die(mysql_error());

	$select1 = "<option selected value=\"\">--Select One--</option>";
	while($rowtraincentersel = mysql_fetch_array($qrytraincentersel))
	{
		$tcentercode = $rowtraincentersel["TRAINCENTERCODE"];
		$tcenter = $rowtraincentersel["TRAINCENTER"];
		
		$selected1 = "";
		
		if ($tcentercode == $trainingcentercode)
			$selected1 = "SELECTED";
			
		$select1 .= "<option $selected1 value=\"$tcentercode\">$tcenter</option>";
	}
	
$qrymanagementsel = mysql_query("SELECT MANAGEMENTCODE,MANAGEMENT FROM management WHERE STATUS=1 ORDER BY MANAGEMENT") or die(mysql_error());

	$select2 = "<option selected value=\"\">--Select One--</option>";
	while($rowmanagementsel = mysql_fetch_array($qrymanagementsel))
	{
		$mgmtcode = $rowmanagementsel["MANAGEMENTCODE"];
		$mgmtname = $rowmanagementsel["MANAGEMENT"];
		
		$selected2 = "";
		
		if ($mgmtcode == $managementcode)
			$selected2 = "SELECTED";
			
		$select2 .= "<option $selected2 value=\"$mgmtcode\">$mgmtname</option>";
	}

$qrycoursetypesel = mysql_query("SELECT COURSETYPECODE,COURSETYPE FROM coursetype ORDER BY COURSETYPE") or die(mysql_error());

	$typesel = "<option selected value=\"\">--Select One--</option>";
	while($rowcoursetypesel = mysql_fetch_array($qrycoursetypesel))
	{
		$typecode = $rowcoursetypesel["COURSETYPECODE"];
		$typename = $rowcoursetypesel["COURSETYPE"];
		
		$selected3 = "";
		
		if ($typecode == $coursetypecode)
		{
			$selected3 = "SELECTED";
			$title_listing = $typename;
		}
			
		$typesel .= "<option $selected3 value=\"$typecode\">$typename</option>";
	}

$qrydocumentsel = mysql_query("SELECT tc.DOCCODE AS TRAINDOCCODE,cd.DOCCODE,cd.DOCUMENT
					FROM crewdocuments cd
					LEFT JOIN trainingcourses tc ON tc.DOCCODE=cd.DOCCODE
					WHERE cd.TYPE='C' AND tc.DOCCODE IS NULL
					ORDER BY cd.DOCUMENT
					") or die(mysql_error());

	$documentsel = "<option selected value=\"\">--Select One--</option>";
	while($rowdocumentsel = mysql_fetch_array($qrydocumentsel))
	{
		$dcode = $rowdocumentsel["DOCCODE"];
		$dname = $rowdocumentsel["DOCUMENT"];
		
		$selected4 = "";
		
		if ($dcode == $documentcode)
			$selected4 = "SELECTED";
			
		$documentsel .= "<option $selected4 value=\"$dcode\"><b>[$dcode]</b>&nbsp;-&nbsp;$dname</option>";
	}

$qrytrainvenuesel = mysql_query("SELECT TRAINVENUECODE,TRAINVENUE FROM trainingvenue WHERE STATUS=1 ORDER BY TRAINVENUE") or die(mysql_error());

	$select4 = "<option selected value=\"\">--Select One--</option>";
	while($rowtrainvenuesel = mysql_fetch_array($qrytrainvenuesel))
	{
		$tvenuecode = $rowtrainvenuesel["TRAINVENUECODE"];
		$tvenue = $rowtrainvenuesel["TRAINVENUE"];
		
		$selected4 = "";
		
		if ($tvenuecode == $trainingvenuecode)
			$selected4 = "SELECTED";
			
		$select4 .= "<option $selected1 value=\"$tvenuecode\">$tvenue</option>";
	}

	

$qrytrainingcourses = mysql_query("SELECT t.*,tc.TRAINCENTER,tv.TRAINVENUE
									FROM trainingcourses t
									LEFT JOIN trainingcenter tc ON tc.TRAINCENTERCODE=t.TRAINCENTERCODE
									LEFT JOIN trainingvenue tv ON tv.TRAINVENUECODE=t.TRAINVENUECODE
									WHERE COURSETYPECODE='$coursetypecode'
					") or die(mysql_error());


/*END OF LISTINGS*/

echo "
<html>
<head>
<link rel=\"stylesheet\" type=\"text/css\" href=\"../veripro.css\">
<script language=\"javascript\" src=\"../veripro.js\"></script>

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
	<div style=\"width:100%;height:480px;padding:5px 20px 0 20px;\">
			
		<div style=\"width:100%;height:240px;\">
			<br />
			<span class=\"sectiontitle\">$formtitle</span>
			<br />
			
			<table class=\"setup\" width=\"100%\" >	
				<tr>
					<th>Type</th>
					<th>:</th>
					<th><select name=\"coursetypecode\" onchange=\"submit();\">
							$typesel;
						</select>
					</th>
				</tr>
				<tr>
					<th>Training Code</th>
					<th>:</th>
					<th><input type=\"text\" name=\"traincode\" value=\"$traincode\" size=\"20\" onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Training</th>
					<th>:</th>
					<th><input type=\"text\" name=\"training\" value=\"$training\" size=\"60\" onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Alias</th>
					<th>:</th>
					<th><input type=\"text\" name=\"alias\" value=\"$alias\" size=\"20\" onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th valign=\"top\">Description</th>
					<th valign=\"top\">:</th>
					<th><textarea rows=\"5\" cols=\"50\" name=\"description\">$description</textarea>
					</th>
				</tr>
				<tr>
					<th colspan=\"3\">&nbsp;</th>
				</tr>
				<tr>
					<th>Existing Certificate Code</th>
					<th>:</th>
					<th><input type=\"text\" name=\"traindoccode\" readonly=\"readonly\" value=\"$traindoccode\" size=\"60\" 
							style=\"border:0;font-weight:Bold;color:Red;\" onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				
				<tr>
					<th>Certificate Code</th>
					<th>:</th>
					<th><select name=\"documentcode\">
							$documentsel;
						</select>
						<input type=\"button\" value=\"Create\" onclick=\"openWindow('setupcrewdocuments.php?doctype=C', 'setupcrewdocuments' ,900, 650);\"
					</th>
				</tr>
				";

				if ($coursetypecode != "INHSE")
				{
					echo "
					<tr>
						<th>Training Center</th>
						<th>:</th>
						<th><select name=\"trainingcentercode\">
								$select1
							</select>
						</th>
					</tr>
					";
				}
				else 
				{
					echo "
					<tr>
						<th>Training Venue</th>
						<th>:</th>
						<th><select name=\"trainingvenuecode\">
								$select4
							</select>
						</th>
					</tr>
					";
				}
				
				
				if ($coursetypecode == "PRINC")
				{
					echo "
						<tr>
							<th>Management</th>
							<th>:</th>
							<th><select name=\"managementcode\">
									$select2
								</select>
							</th>
						</tr>
					";
				}
				else 
				{
					echo "
						<tr>
							<th colspan=\"3\">&nbsp;</th>
						</tr>
					";
				}

				
				echo "
				<tr>
					<th>Active?</th>
					<th>:</th>
					<th>
						<input type=\"checkbox\" name=\"status\" $checkstatus />
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
		
		<div style=\"width:100%;height:180px;overflow:hidden;\">
			<br />
			<span class=\"sectiontitle\">CURRENT LISTING - $title_listing</span>
			<br />
			<div style=\"width:100%;height:120px;padding:0;overflow:auto;\">
				<table width=\"100%\" class=\"listcol\">
					<tr>
						<th>CODE</th>
						<th>TRAINING</th>
						<th>DOC CODE</th>
						<th>TRAINCENTER</th>
						<th>STATUS</th>
						<th>MADEBY</th>
						<th>MADEDATE</th>
					</tr>
		";
				while ($rowtrainingcourses=mysql_fetch_array($qrytrainingcourses))
				{
					$list1 = $rowtrainingcourses["TRAINCODE"];
					$list2 = $rowtrainingcourses["TRAINING"];
					$list3 = $rowtrainingcourses["DESCRIPTION"];
					$list4 = $rowtrainingcourses["COURSETYPECODE"];
					$list5 = $rowtrainingcourses["TRAINCENTERCODE"];
					if ($rowtrainingcourses["TRAINCENTER"])
						$list6 = $rowtrainingcourses["TRAINCENTER"];
					else 
						$list6 = "";
						
					$list7 = $rowtrainingcourses["DOCCODE"];
						
					if ($rowtrainingcourses["STATUS"] == 0)
						$list8 = "INACTIVE";
					else 
						$list8 = "ACTIVE";
						
					$list9 = $rowtrainingcourses["MADEBY"];
					$list10 = date('m-d-Y',strtotime($rowtrainingcourses["MADEDATE"]));
					
					echo "
					<tr ondblclick=\"traincode1.value='$list1';actiontxt.value='update';submit();\">
						
						<td align=\"center\">$list1</td>
						<td>$list2</td>
						<td align=\"center\">&nbsp;$list7</td>
						<td align=\"center\">&nbsp;$list6</td>
						<td align=\"center\">&nbsp;$list8</td>
						<td align=\"center\">&nbsp;$list9</td>
						<td align=\"center\">&nbsp;$list10</td>
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
	<input type=\"hidden\" name=\"traincode1\" />
</form>

</body>
</html>
";

?>

