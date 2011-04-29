<?php

$kups = "gino";

include("veritas/connectdb.php");
include("veritas/include/stylephp.inc");
include("veritas/include/functions.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');
$datenow = date('d F Y');
$basedir = "idpics/";
$basedirdocs = "docimages/";

if(isset($_SESSION["employeeid"]))
	$employeeid=$_SESSION["employeeid"];

if (isset($_POST["actiontxt"]))
	$actiontxt = $_POST["actiontxt"];
	
if (isset($_GET["applicantno"]))
{
	$applicantno = $_GET["applicantno"];
	$searchkey=$applicantno;
//	unset($_GET["applicantno"]);
}
else 
	$applicantno = $_POST["applicantno"];
	
if (isset($_POST["searchkey"]))
	$searchkey = $_POST["searchkey"];
	
if (isset($_POST["searchby"]))
	$searchby = $_POST["searchby"];
	
$showmultiple = "visibility:hidden;";
$multiple = 0;

switch ($actiontxt)
{
	case "find"		:
		
			switch ($searchby)
			{
				case "1"	: //APPLICANT NO
					
						$qrysearch = mysql_query("SELECT APPLICANTNO FROM crew WHERE APPLICANTNO LIKE '$searchkey%' ORDER BY APPLICANTNO") or die(mysql_error());
					
					break;
				case "2"	: //CREW CODE
					
						$qrysearch = mysql_query("SELECT APPLICANTNO FROM crew WHERE CREWCODE LIKE '$searchkey%' ORDER BY CREWCODE") or die(mysql_error());
					
					break;
				case "3"	: //FAMILY NAME
					
						$qrysearch = mysql_query("SELECT APPLICANTNO FROM crew WHERE FNAME LIKE '$searchkey%' ORDER BY FNAME") or die(mysql_error());
					
					break;
				case "4"	: //GIVEN NAME
					
						$qrysearch = mysql_query("SELECT APPLICANTNO FROM crew WHERE GNAME LIKE '$searchkey%' ORDER BY GNAME") or die(mysql_error());
					
					break;
			}
		
			if (mysql_num_rows($qrysearch) == 1)  //SINGLE ENTRY FOUND
			{
				$rowsearch = mysql_fetch_array($qrysearch);
				$applicantno = $rowsearch["APPLICANTNO"];
			}
			elseif (mysql_num_rows($qrysearch) > 1)  //MULTIPLE ENTRY FOUND
			{
				$showmultiple = "visibility:show;";
				$multiple = 1;
			}
			else 
			{
				$applicantno="";
				$errormsg = "Search Key -- '$searchkey' Not Found.";
			}
			
		
		break;
		
	case "closewindow"	:
		
			$showmultiple = "visibility:hidden;";
			$multiple = 0;
		
		break;
}


include("include/datasheet.inc");


echo "
<html>\n
<head>\n
<title>
Crew Data Sheet
</title>

<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>
<script language=\"javascript\" src=\"popcalendar.js\"></script>	
</head>
<body style=\"overflow:hidden;\">\n

<form name=\"datasheet\" method=\"POST\">\n

<span class=\"wintitle\">CREW DATA SHEET</span>

<div style=\"width:100%;height:100px;background-color:#DCDCDC;overflow:hidden;padding:10px;\">

	<table width=\"80%\" style=\"font-size:0.8em;font-weight:Bold;\">
		<tr>
			<th>Search Key &nbsp;&nbsp;
				<select name=\"searchby\" onchange=\"searchkey.value='';searchkey.focus();\">

				";

				$selected1 = "";
				$selected2 = "";
				$selected3 = "";
				$selected4 = "";

				switch ($searchby)
				{
					case "1"	: //BY APPLICANT NO
							$selected1 = "SELECTED";
						break;
					case "2"	: //BY CREW CODE
							$selected2 = "SELECTED";
						break;
					case "3"	: //BY FAMILY NAME
							$selected3 = "SELECTED";
						break;
					case "4"	: //BY GIVEN NAME
							$selected4 = "SELECTED";
						break;
				}

			echo "
					<option $selected1 value=\"1\">APPLICANT NO</option>
					<option $selected2 value=\"2\">CREW CODE</option>
					<option $selected3 value=\"3\">FAMILY NAME</option>
					<option $selected4 value=\"4\">GIVEN NAME</option>
				</select>
			</th>
			<td><input type=\"text\" name=\"searchkey\" size=\"40\" value=\"$searchkey\" onkeyup=\"this.value=this.value.toUpperCase()\" 
					style=\"color:Red;font-weight:Bold;\" onkeydown=\"if(event.keyCode==13) {btnfind.focus();}\"/>
					
				<input type=\"button\" name=\"btnfind\" value=\"Find\" onclick=\"actiontxt.value='find';submit();\" />
			</td>
			<td>
				<input type=\"button\" name=\"btnprint\" value=\"Print Data Sheet\" 
					onclick=\"openWindow('repcrewdatasheet.php?applicantno=$applicantno', 'repdatasheet' ,900, 650);\" />
			</td>
		</tr>
		<tr>
			<td colspan=\"3\" valign=\"top\"><i>Documents Expiry Legend:</i><br />
				&nbsp;&nbsp;<span style=\"font-size:0.9em;background-color:black;color:Yellow;\">DOCUMENT NO</span>
				&nbsp;&nbsp;Three (3) months before document expiration. <br />
				&nbsp;&nbsp;<span style=\"font-size:0.9em;background-color:black;color:Red;\">DOCUMENT NO</span>
				&nbsp;&nbsp;Document already EXPIRED. <br />
			</td>
		</tr>
	</table>
</div>

							<div style=\"position:absolute;left:112px;height:184px;width:600px;height:400px;background-color:#6699FF;
											border:2px solid black;overflow:auto;$showmultiple \">
								<span class=\"sectiontitle\">MULTIPLE ENTRY FOUND&nbsp;-&nbsp;
									<a href=\"#\" onclick=\"actiontxt.value='closewindow';submit();\">[Close Window]</a>
								</span>
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
											<tr $mouseovereffect style=\"cursor:pointer;\" ondblclick=\"actiontxt.value='closewindow';
															applicantno.value='$info1';submit();
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
									<a href=\"#\" style=\"font-size:0.8em;font-weight:Bold;\" onclick=\"actiontxt.value='closewindow';submit();\">Close Window</a>
								</center>
								<br />
							</div>

	<center>

	<div style=\"margin:5 5 5 5;width:823px;height:550px;border:1px solid black;overflow:auto;\">
	
		<iframe marginwidth=0 marginheight=0 id=\"previewdatasheet\" frameborder=\"0\" name=\"crewdatasheet\" 
			src=\"repcrewdatasheet.php?applicantno=$applicantno&print=0\" scrolling=\"yes\" style=\"width:100%;height:100%;\">
		</iframe> 
		
	</div>
	
	</center>	

	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"applicantno\" value=\"$applicantno\" />

</form>
</body>

</html>

";