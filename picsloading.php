<?php

include('veritas/connectdb.php');
include("veritas/include/stylephp.inc");
include("veritas/include/functions.inc");

$currentdate = date('Y-m-d H:i:s');
$datenow = date('d F Y');
$basedir = "idpics/";
$basedirdocs = "docimages/";

$bgcolor = "white";

if (isset($_POST['applicantno']))
	$applicantno = $_POST['applicantno'];

if (isset($_POST['appno']))
	$appno = $_POST['appno'];

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_POST["searchkey"]))
	$searchkey = $_POST["searchkey"];
	
if (isset($_POST["searchby"]))
	$searchby = $_POST["searchby"];

//$divhide = "display:none;";
$disableprint = "disabled=\"disabled\"";
$disableupload = "";

$showmultiple = "visibility:hidden;";
$multiple = 0;

switch ($actiontxt)
{
	
	case "find"		:
		
			switch ($searchby)
			{
				case "1"	: //APPLICANT NO
					
						$qrysearch = mysql_query("SELECT APPLICANTNO FROM crew WHERE APPLICANTNO=$searchkey ORDER BY APPLICANTNO") or die(mysql_error());
					
					break;
				case "2"	: //CREW CODE
					
						$qrysearch = mysql_query("SELECT APPLICANTNO FROM crew WHERE CREWCODE=$searchkey ORDER BY CREWCODE") or die(mysql_error());
					
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
	
	case "search"	:
		
			$qryappsearch = mysql_query("SELECT APPLICANTNO,PRINTED FROM applicant 
								WHERE APPLICANTNO='$applicantno' AND AGREED=1 
								") or die(mysql_error());
			
			if (mysql_num_rows($qryappsearch) > 0)
			{
				$rowappsearch = mysql_fetch_array($qryappsearch);
				$printdate = date('m/d/Y H:i:s',strtotime($rowappsearch["PRINTED"]));
				
				$divhide = "display:block;";
			}
			else 
			{
				$searcherror = "Applicant No. $applicantno does not exists.";
				$applicantno = "";
			}
		
		
		break;
		
	case "upload"	:

		//FILE UPLOAD TO SERVER....

//		$basedir = $_SERVER['DOCUMENT_ROOT'];
//		$basedir = "/usr/local/www/data";
//		$basedir = "idpics/";
		
		$uploaded = 0;
		$uploadmsg = "";

		$fname = $_FILES['uploadedfile']['name'];  //ACTUAL UPLOAD FILENAME
		$parts = pathinfo($fname);
		$ext = $parts['extension'];
		$ext = strtoupper($ext);
		
		$newfile = $basedir . $applicantno . "." .$ext;
		
		if ($applicantno != "")
		{
			if (preg_match('/^(JPE?G|jpe?g)$/',$ext)) 
			{
	
				$error = $_FILES['uploadedfile']['error'];
				$tmp_name = $_FILES['uploadedfile']['tmp_name'];
				
				if ($error==UPLOAD_ERR_OK) 
				{
					if ($_FILES['uploadedfile']['size'] > 0)
					{
						if(move_uploaded_file($tmp_name, $newfile))
						{
				      		$uploadmsg = "File: $fname uploaded successfully.";
						}
				      	else 
				      	{
				      		$uploadmsg = "File upload failed.";
				      	}
					}
					else
					{
						$uploadmsg = "File: $fname is empty.";
					}
				}
				else if ($error==UPLOAD_ERR_NO_FILE) 
					{
						$uploadmsg = "No files specified.";
					} 
					else
					{
						$uploadmsg = "File Upload failed.";
					}
					
				//END OF FILE UPLOAD TO SERVER...			
				
			}
			else 
				$uploadmsg = "[$ext] - File is not JPG or GIF.";
		}

		break;	
	
	case "deletepic":
		
			$file = $basedir . $appno . ".JPG";
			unlink($file);
			
			$applicantno = $appno;
	
		break;
		
	case "print"	:
			
			$qryprintsave = mysql_query("UPDATE applicant SET PRINTED='$currentdate' WHERE APPLICANTNO=$appno") or die(mysql_error());
			
			$applicantno = $appno;
			
		break;
}

if ($applicantno == "")
{
	$disableupload = "disabled=\"disabled\"";
}
else 
{
	$disableprint = "";
	$bgcolor = "#CCFF99";
	
	include("include/datasheet.inc");
}

echo "
<html>\n
<head>\n

<title>Applicant Picture Loading</title>

<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<link rel=\"stylesheet\" type=\"text/css\" href=\"veripro_print.css\">

<script type='text/javascript' src='veripro.js'></script>

<script language=\"javascript\" src=\"popcalendar.js\"></script>

</head>
<body style=\"\">\n


<span class=\"wintitle\">APPLICATION FORM - PICTURE UPLOADING</span>

<form name=\"appsearch\" method=\"POST\">
	<div style=\"width:823px;height:50px;background-color:#DCDCDC;margin-left:10px;margin-top:10px;border:1px solid black;\" >
	
	<table width=\"100%\" style=\"font-size:0.8em;font-weight:Bold;\">
		<tr>
			<th>Search Key &nbsp;&nbsp;
				<select name=\"searchby\" onchange=\"searchkey.value='';searchkey.focus();\">
					<option value=\"\">--Select Search Key--</option>
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
					
				<input type=\"button\" value=\"Find\" name=\"btnfind\" onclick=\"actiontxt.value='find';submit();\" />
			</td>
			<td>
				<input type=\"button\" name=\"btnprint\" value=\"Print Data Sheet\" 
					onclick=\"openWindow('repcrewdatasheet.php?applicantno=$applicantno', 'repdatasheet' ,900, 650);\" />
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
										$appno2 = $rowmultisearch["APPLICANTNO"];
										
										$qrygetinfo = mysql_query("SELECT APPLICANTNO,CREWCODE,FNAME,GNAME,MNAME,STATUS
																	FROM crew 
																	WHERE APPLICANTNO=$appno2
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
										<tr $mouseovereffect ondblclick=\"actiontxt.value='closewindow';applicantno.value='$info1';appsearch.submit();
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


<div style=\"margin-left:10px;margin-top:10px;width:823px;height:450px;border:1px solid black;overflow:auto;\">

	<iframe marginwidth=0 marginheight=0 id=\"previewdatasheet\" frameborder=\"0\" name=\"showbody\" 
		src=\"repcrewdatasheet.php?applicantno=$applicantno&print=0\" scrolling=\"yes\" style=\"width:100%;height:100%;\">
	</iframe> 

</div>

	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"appno\" value=\"$applicantno\" />
	<input type=\"hidden\" name=\"applicantno\" value=\"$applicantno\" />

</form>

<form enctype=\"multipart/form-data\" method=\"POST\" name=\"fileupload\">

	<div style=\"width:823px;height:75px;background-color:#DCDCDC;margin-left:10px;margin-top:10px;border:1px solid black;\" >
		<span class=\"sectiontitle\">PICTURE UPLOAD</span>
		
		<table width=\"100%\" class=\"listrow\">
			<tr>
				<th>Filename:</th>
			</tr>
			<tr>
				<td>
					<input name=\"uploadedfile\" type=\"file\" size=\"30\" $disableupload />
					<input type=\"button\" value=\"Upload\" onCLick=\"actiontxt.value='upload';fileupload.submit();\" $disableupload />
				</td>
			</tr>
			<tr>
				<td>$uploadmsg</td>
			</tr>
		</table>
	</div>

	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"applicantno\" value=\"$applicantno\" />
			
</form>

<!--
<form name=\"repappfront\" action=\"repappfront.php\" target=\"repappfront\" method=\"GET\">
	<input type=\"hidden\" name=\"applicantno\">
</form>

<form name=\"repappfront2\" action=\"repappfront2.php\" target=\"repappfront2\" method=\"GET\">
	<input type=\"hidden\" name=\"applicantno\">
</form>

-->

</body>

</html>

"
	
	
	
	
	
	
	
?>