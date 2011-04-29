<?php

include("veritas/connectdb.php");


if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_POST['currentpage']))
	$currentpage = $_POST['currentpage'];
	
if (empty($currentpage))
	$currentpage = 0;

$currentpagediv = "div"	. $currentpage;
$$currentpagediv = "display:block;";


//POSTS

if (isset($_POST['empfname']))
	$empfname = $_POST['empfname'];
	
if (isset($_POST['empgname']))
	$empgname = $_POST['empgname'];
	
if (isset($_POST['empmname']))
	$empmname = $_POST['empmname'];
	
if (isset($_POST['empdesignation']))
	$empdesignation = $_POST['empdesignation'];
	
if (isset($_POST['empaddress']))
	$empaddress = $_POST['empaddress'];
	
if (isset($_POST['empmunicipality']))
	$empmunicipality = $_POST['empmunicipality'];
	
if (isset($_POST['empcity']))
	$empcity = $_POST['empcity'];
	
if (isset($_POST['empid']))
	$empid = $_POST['empid'];
	
//if (isset($_POST['emppass1']))
//	$emppass1 = $_POST['emppass1'];
//	
//if (isset($_POST['emppass2']))
//	$emppass2 = $_POST['emppass2'];
	


switch ($actiontxt)
{
	case "genid"	:
			
			// GET INITIALS
			$empid = substr($empgname,0,1) . substr($empmname,0,1) . substr($empfname,0,1);
			
			$qrycheckid = mysql_query("SELECT EMPLOYEEID FROM employee WHERE EMPLOYEEID = '$empid'") or die(mysql_error());
			
			if (mysql_num_rows($qrycheckid) > 0)
			{
				$qrycheckid2 = mysql_query("SELECT MAX(EMPLOYEEID) AS EMPLOYEEID FROM employee WHERE EMPLOYEEID LIKE '$empid%'") or die(mysql_error());
				$rowcheckid2 = mysql_fetch_array($qrycheckid2);
				
				$idtmp1 = substr($rowcheckid2["EMPLOYEEID"],3,4);
				$idtmp1++;
				
				$empid .= $idtmp1;
			}		
		
		break;
		
	case "save"		:
		
			switch ($currentpage)
			{
				case "0"	:
					
						$qryemployeesave = mysql_query("INSERT INTO employee(EMPLOYEEID,DESIGNATIONCODE,FNAME,GNAME,MNAME,ADDRESS,MUNICIPALITY,CITY,PASSWORD)
									 VALUES('$empid','$empdesignation','$empfname','$empgname','$empmname','$empaddress',
									 		'$empmunicipality','$empcity','1234')") or die(mysql_error());
						
						$empid = "";
						$empfname = "";
						$empgname = "";
						$empmname = "";
						$empaddress = "";
						$empmunicipality = "";
						$empcity = "";
						$empdesignation = "";
//						$emppass1 = "";
//						$emppass2 = "";
					
					break;
				
				case "1"	:
					
					
					break;
				
				case "2"	:
					
					
					break;
				
				case "3"	:
					
					
					break;
				
				case "4"	:
					
					
					break;
			}
			
		break;
		
	case "cancel"	:
		
			switch ($currentpage)
			{
				case "0"	:
					
						$empid = "";
						$empfname = "";
						$empgname = "";
						$empmname = "";
						$empaddress = "";
						$empmunicipality = "";
						$empcity = "";
						$empdesignation = "";
//						$emppass1 = "";
//						$emppass2 = "";
					
					break;
				
				case "1"	:
					
					
					break;
				
				case "2"	:
					
					
					break;
				
				case "3"	:
					
					
					break;
				
				case "4"	:
					
					
					break;
					
				case "5"	:
					
					
					break;
			}
		
		break;
}
	

/* LISTINGS  */

$qrydesignationsel = mysql_query("SELECT DESIGNATIONCODE,DESIGNATION FROM designation") or die(mysql_error());

	$empselect1 = "<option selected value=\"\">--Select One--</option>";
	while($rowdesignationsel = mysql_fetch_array($qrydesignationsel))
	{
		$descode = $rowdesignationsel["DESIGNATIONCODE"];
		$designation = $rowdesignationsel["DESIGNATION"];
		
		$selected1 = "";
		
		if ($empdesignation == $descode)
			$selected1 = "SELECTED";
			
		$empselect1 .= "<option value=\"$descode\">$designation</option>";
	}


/*END OF LISTINGS*/



//include("header.php");

echo "
<html>
<head>
<link rel=\"stylesheet\" type=\"text/css\" href=\"veripro.css\">
<script language=\"javascript\" src=\"veripro.js\"></script>

	<script>
		var displaypage=new Array('employee','designation','setup3','setup4','setup5');
		
		function changepage(x,y)
		{
			hidepage= displaypage[y];
			document.getElementById(hidepage).style.display='none';
			getpage=x;
				
			var openpage= displaypage[getpage];
			document.getElementById(openpage).style.display='block';
			document.setupform.currentpage.value = getpage;
		}
	
		function checksave(x)
		{
			var rem = '';
			
			with (setupform)
			{
				switch(x)
				{
					case 0:
						if(empid.value=='' || empid.value==null)
							if(rem=='')
								rem='Employee ID';
							else
								rem=rem + ',Employee ID';	
						if(empdesignation.value=='' || empdesignation.value==null)
							if(rem=='')
								rem='Designation';
							else
								rem=rem + ',Designation';
									
//						if(emppass1.value=='' || emppass1.value==null)
//							if(rem=='')
//								rem='Password';
//							else
//								rem=rem + ',Password';	
//								
//						if(emppass2.value=='' || emppass2.value==null)
//							if(rem=='')
//								rem='Verify Password';
//							else
//								rem=rem + ',Verify Password';	
//								
//						if(emppass1.value != '' && emppass2.value != '')
//						{
//							if(emppass1.value != emppass2.value)
//								if(rem=='')
//									rem='[Password not verified. Please enter again.]';
//								else
//									rem=rem + ' -- [Password not verified. Please enter again.]';
//						}
					break
				}
			}
					
			if(rem=='')
			{
				setupform.submit();
			}
			else
				alert('Please CHECK the following: ' + rem + ' before saving!');		
		
		}
		
	</script>
</head>
<body>

<form name=\"setupform\" method=\"POST\">
	
	<center>
	<div style=\"width:85%;height:100%;padding:2px;border:1px solid black;\">
	
		<span class=\"wintitle\">SETUP</span>
			
			<div style=\"width:20%;height:380px;;float:left;overflow:auto;background-color:Silver;
						border-left:1px solid Black;padding-left:3px;\">
				
				<table class=\"listcol\" style=\"width:100%;\" >	
					<tr>
						<td><a href=\"#\" onclick=\"changepage(0,currentpage.value)\">EMPLOYEE</a></td>
					</tr>	
					<tr>
						<td><a href=\"#\" onclick=\"changepage(1,currentpage.value)\">DESIGNATION</a></td>
					</tr>
					<tr>
						<td><a href=\"#\" onclick=\"changepage(2,currentpage.value)\">3RD SETUP</a></td>
					</tr>
					<tr>
						<td><a href=\"#\" onclick=\"changepage(3,currentpage.value)\">4TH SETUP</a></td>
					</tr>
					<tr>
						<td><a href=\"#\" onclick=\"changepage(4,currentpage.value)\">5TH SETUP</a></td>
					</tr>
				</table>
				
			</div>
			
			<div id=\"employee\" style=\"width:80%;height:380px;float:right;padding:5px 0 0 20px;
					overflow:auto;display:none;border-bottom:1px solid Gray;$div0\">
					
				<span class=\"sectiontitle\">EMPLOYEE SETUP</span>
				<br />
				
				<table class=\"setup\" width=\"100%\" >	
					<tr>
						<th>Family Name</th>
						<th>:</th>
						<th><input type=\"text\" name=\"empfname\" value=\"$empfname\" size=\"30\" onKeyPress=\"return alphaonly(this);\"
								 onkeyup=\"this.value=this.value.toUpperCase()\" />
						</th>
					</tr>
					<tr>
						<th>Given Name</th>
						<th>:</th>
						<th><input type=\"text\" name=\"empgname\" value=\"$empgname\" size=\"30\" onKeyPress=\"return alphaonly(this);\"
								 onkeyup=\"this.value=this.value.toUpperCase()\" />
						</th>
					</tr>
					<tr>
						<th>Middle Name</th>
						<th>:</th>
						<th><input type=\"text\" name=\"empmname\" value=\"$empmname\" size=\"30\" onKeyPress=\"return alphaonly(this);\"
								 onkeyup=\"this.value=this.value.toUpperCase()\" />
						</th>
					</tr>
					<tr>
						<th>Designation</th>
						<th>:</th>
						<th><select name=\"empdesignation\">
								$empselect1
							</select>
						</th>
					</tr>
					<tr>
						<th>Address</th>
						<th>:</th>
						<th><input type=\"text\" name=\"empaddress\" value=\"$empaddress\" size=\"30\" 
								 onkeyup=\"this.value=this.value.toUpperCase()\" />
						</th>
					</tr>
					<tr>
						<th>Municipality</th>
						<th>:</th>
						<th><input type=\"text\" name=\"empmunicipality\" value=\"$empmunicipality\" size=\"30\"
								 onkeyup=\"this.value=this.value.toUpperCase()\" />
						</th>
					</tr>
					<tr>
						<th>City</th>
						<th>:</th>
						<th><input type=\"text\" name=\"empcity\" value=\"$empcity\" size=\"20\"
								 onkeyup=\"this.value=this.value.toUpperCase()\" 
						</th>
					</tr>
					<tr>
						<th>Employee ID</th>
						<th>:</th>
						<th><input type=\"text\" name=\"empid\" value=\"$empid\" size=\"10\" readonly=\"readonly\" />&nbsp;&nbsp;
							<input type=\"button\" name=\"\" value=\"Generate ID\" onclick=\"actiontxt.value='genid';setupform.submit();\" />
						</th>
					</tr>
<!--
					<tr>
						<th>Password</th>
						<th>:</th>
						<th><input type=\"password\" name=\"emppass1\" value=\"$emppass1\" size=\"10\" onKeyPress=\"return alphaonly(this);\"
								 onkeyup=\"this.value=this.value.toUpperCase()\" />
						</th>
					</tr>
					<tr>
						<th>Verify Password</th>
						<th>:</th>
						<th><input type=\"password\" name=\"emppass2\" value=\"$emppass2\" size=\"10\" onKeyPress=\"return alphaonly(this);\"
								 onkeyup=\"this.value=this.value.toUpperCase()\" />
						</th>
					</tr>
-->
					<tr>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
					</tr>
					<tr>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th><input type=\"button\" name=\"\" value=\"Save\" onclick=\"actiontxt.value='save';checksave($currentpage);\" />
							<input type=\"button\" name=\"\" value=\"Cancel\" onclick=\"actiontxt.value='cancel';setupform.submit();\" />
						</th>
					</tr>
				</table>
					
				
			</div>
			
			<div id=\"designation\" style=\"width:80%;height:380px;float:right;padding:5px 0 0 20px;
					overflow:auto;display:none;border-bottom:1px solid Gray;$div1\">
				<span class=\"sectiontitle\">DESIGNATION SETUP</span>
				<br>
			</div>
			
			<div id=\"setup3\" style=\"width:80%;height:380px;float:right;padding:5px 0 0 20px;
					overflow:auto;display:none;border-bottom:1px solid Gray;$div2\">
				<span class=\"sectiontitle\">3RD SETUP</span>
				<br>
			</div>
			
			<div id=\"setup4\" style=\"width:80%;height:380px;float:right;padding:5px 0 0 20px;
					overflow:auto;display:none;border-bottom:1px solid Gray;$div3\">
				<span class=\"sectiontitle\">4TH SETUP</span>
				<br>
			</div>
			
			<div id=\"setup5\" style=\"width:80%;height:380px;float:right;padding:5px 0 0 20px;
					overflow:auto;display:none;border-bottom:1px solid Gray;$div4\">
				<span class=\"sectiontitle\">5TH SETUP</span>
				<br>
			</div>
			
		
			<input type=\"hidden\" name=\"actiontxt\" />
			<input type=\"hidden\" name=\"currentpage\" value=\"$currentpage\" />

	</div>
	</center>
</form>

</body>
</html>
";



//include("footer.php");

?>

