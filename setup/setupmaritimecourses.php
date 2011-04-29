<?php

include("veritas/connectdb.php");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
		
$formname = "formcourses";
$formtitle = "MARITIME COURSE SETUP";

$checkstatus = "";

//POSTS

if(isset($_POST['course']))
	$course=$_POST['course'];

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
		
			$qryverify = mysql_query("SELECT COURSEID FROM maritimecourses WHERE COURSE='$course'") or die(mysql_error());
		
			if(mysql_num_rows($qryverify) == 0)
			{
				$qrycoursesave = mysql_query("INSERT INTO maritimecourses(COURSE,ALIAS,STATUS,MADEBY,MADEDATE) 
													VALUES('$course','$alias',$status,'$employeeid','$currentdate')
												") or die(mysql_error());
			}
			else 
			{
				$rowverify = mysql_fetch_array($qryverify);
				$courseid = $rowverify["COURSEID"];
				
				$qrycourseupdate = mysql_query("UPDATE maritimecourses SET COURSE='$course',
																ALIAS='$alias',
																STATUS=$status,
																MADEBY='$employeeid',
																MADEDATE='$currentdate'
													WHERE COURSEID='$courseid'
													") or die(mysql_error());
			}

			$course = "";
			$alias = "";
			$status = 0;
			
			$checkstatus = "";
			
		break;
		
	case "cancel"	:

			$course = "";
			$alias = "";
			$status = 0;
			
			$checkstatus = "";
		
		break;
		
	case "delete"	:

		break;
}
	

/* LISTINGS  */

$qrycourses = mysql_query("SELECT * FROM maritimecourses ORDER BY COURSE") or die(mysql_error());

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
	<div style=\"width:100%;height:500px;padding:5px 20px 0 20px;overflow:hidden;\">
			
		<div style=\"width:100%;height:180px;float:right;padding:5px 20px 0 20px;overflow:auto;\">
			<br />
			<span class=\"sectiontitle\">$formtitle</span>
			<br />
			
			<table class=\"setup\" width=\"100%\" >	
				<tr>
					<th>Course Name</th>
					<th>:</th>
					<th><input type=\"text\" name=\"course\" value=\"$course\" size=\"50\" onKeyPress=\"return alphaonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Alias</th>
					<th>:</th>
					<th><input type=\"text\" name=\"alias\" value=\"$alias\" size=\"30\" onKeyPress=\"return alphaonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
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
		
		<div style=\"width:100%;height:350px;padding:5px 20px 0 20px;overflow:hidden;\">
			<br />
			<span class=\"sectiontitle\">CURRENT LISTING</span>
			<br />
			
			<div style=\"width:100%;height:250px;padding:0;overflow:auto;\">
				<table width=\"100%\" class=\"listcol\">
					<tr>
						<th>COURSE</th>
						<th>ALIAS</th>
						<th>STATUS</th>
						<th>MADE BY</th>
						<th>MADE DATE</th>
					</tr>
	";
				while ($rowcourses=mysql_fetch_array($qrycourses))
				{
					$list1 = $rowcourses["COURSE"];
					$list2 = $rowcourses["ALIAS"];
						
					if ($rowcourses["STATUS"] == 0)
						$list3 = "INACTIVE";
					else 
						$list3 = "ACTIVE";
						
					$list4 = $rowcourses["MADEBY"];
					$list5 = date('m-d-Y',strtotime($rowcourses["MADEDATE"]));
					
					echo "
					<tr ondblclick=\"
						document.$formname.course.value='$list1';
						document.$formname.alias.value='$list2';
						if ('$list3' == 'INACTIVE') {document.$formname.status.checked='';} else {document.$formname.status.checked='checked';}
						\">
						
						<td>$list1</td>
						<td>$list2</td>
						<td>$list3</td>
						<td align=\"center\">$list4</td>
						<td align=\"center\">$list5</td>
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

