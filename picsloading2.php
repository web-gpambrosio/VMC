<?php

include('veritas/connectdb.php');

$currentdate = date('Y-m-d H:i:s');

$basedir = "idpics/";
$bgcolor = "white";

if (isset($_POST['applicantno']))
	$applicantno = $_POST['applicantno'];

if (isset($_POST['appno']))
	$appno = $_POST['appno'];

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];

function checkpath($path)
{
	if (is_file($path))
		return true;
	else 
		return false;
}
	
	
function imageScale($image, $newWidth, $newHeight)
{
    if(!$size = @getimagesize($image))
        die("Unable to get info on image $image");
    $ratio = ($size[0] / $size[1]);
    //scale by height
    if($newWidth == -1)
    {
        $ret[1] = $newHeight;
        $ret[0] = round(($newHeight * $ratio));
    }
    else if($newHeight == -1)
    {
        $ret[0] = $newWidth;
        $ret[1] = round(($newWidth / $ratio));
    }
    else
        die("Scale Error");
    return $ret;
} 
	

//$divhide = "display:none;";
$disableprint = "disabled=\"disabled\"";
$disableupload = "";

switch ($actiontxt)
{
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
	
				$qryfamilylist = mysql_query("SELECT CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,CONCAT(ADDRESS,', ',MUNICIPALITY,' ',CITY) AS ADDRESS, 
											fr.RELATION,TELNO
											FROM crewfamily cf
											LEFT JOIN familyrelation fr ON fr.RELCODE=cf.RELCODE
											WHERE APPLICANTNO=$applicantno LIMIT 2") or die(mysql_error());
					
				$qryeduclist = mysql_query("SELECT IF(ce.SCHOOLID IS NULL,ce.SCHOOLOTHERS,ms.SCHOOL) AS SCHOOL,
											IF(ce.COURSEID IS NULL,ce.COURSEOTHERS,mc.COURSE) AS COURSE,
											IF(ce.DATEGRADUATED IS NULL,'',ce.DATEGRADUATED) AS DATEGRADUATED,
											ce.REMARKS
											FROM creweducation ce
											LEFT JOIN maritimeschool ms ON ce.SCHOOLID=ms.SCHOOLID
											LEFT JOIN maritimecourses mc ON ce.COURSEID=mc.COURSEID
											WHERE ce.APPLICANTNO=$applicantno LIMIT 2") or die(mysql_error());
			
				$qrydoclist = mysql_query("SELECT cd.DOCUMENT,cds.DOCNO,cds.DATEISSUED,cds.DATEEXPIRED
											FROM crewdocstatus cds
											LEFT JOIN crewdocuments cd ON cd.DOCCODE=cds.DOCCODE
											WHERE cd.TYPE='D' and cds.APPLICANTNO=$applicantno LIMIT 3") or die(mysql_error());
				
				$qryliclist = mysql_query("SELECT cd.DOCUMENT,cds.DOCNO,cds.DATEISSUED,cds.DATEEXPIRED
											FROM crewdocstatus cds
											LEFT JOIN crewdocuments cd ON cd.DOCCODE=cds.DOCCODE
											WHERE cd.TYPE='L'and cds.APPLICANTNO=$applicantno LIMIT 3") or die(mysql_error());
				
				$qrycertlist = mysql_query("SELECT cd.DOCUMENT,ccs.DOCNO,ccs.DATEISSUED,ccs.GRADE
											FROM crewcertstatus ccs
											LEFT JOIN crewdocuments cd ON cd.DOCCODE=ccs.DOCCODE
											WHERE cd.TYPE='C'and ccs.APPLICANTNO=$applicantno LIMIT 3") or die(mysql_error());
			
				$qryexperiencelist = mysql_query("SELECT ce.VESSEL,ce.ENGINETYPE,r.ALIAS1 AS RANKALIAS,ce.VESSELTYPECODE,ce.TRADEROUTECODE,FLAGCODE,DATEEMB,DATEDISEMB,
											IF (ce.MANNINGCODE = '',ce.MANNINGOTHERS,m.MANNING) AS MANNING,
											IF((ce.DISEMBREASONCODE <> 'OTH'),dr.REASON,ce.REASONOTHERS) AS REASON
											FROM crewexperience ce
											LEFT JOIN rank r ON r.RANKCODE=ce.RANKCODE
											LEFT JOIN manning m ON m.MANNINGCODE=ce.MANNINGCODE
											LEFT JOIN disembarkreason dr ON dr.DISEMBREASONCODE=ce.DISEMBREASONCODE
											WHERE ce.APPLICANTNO=$applicantno
											ORDER BY DATEDISEMB DESC
											LIMIT 3") or die(mysql_error());
			
				$qryemploymentlist = mysql_query("SELECT EMPLOYER,POSITION,DATEFROM,DATETO,REASON FROM employhistory WHERE APPLICANTNO=$applicantno LIMIT 2") or die(mysql_error());
				
				$qryreferencelist = mysql_query("SELECT * FROM applicantreference WHERE APPLICANTNO=$applicantno LIMIT 2") or die(mysql_error());
			
			
				$qrypersonallist = mysql_query("SELECT CONCAT(FNAME,', ',GNAME,' ' ,MNAME) AS NAME, 
												CONCAT(ADDRESS,', ',MUNICIPALITY,', ',CITY,' ',ZIPCODE) AS ADDRESS1,
												CONCAT(ADDRESS,', ',ab.BARANGAY,', ',at.TOWN,', ',ap.PROVINCE,' ',ZIPCODE) AS ADDRESS,
												TELNO,BIRTHDATE,BIRTHPLACE,CIVILSTATUS,RELIGION,TIN,SSS,WEIGHT,HEIGHT,SIZESHOES,GENDER,EMAIL,
												r1.RANK AS CHOICE1,r2.RANK AS CHOICE2,DATEAPPLIED,RECOMMENDEDBY,QUESTION1A,QUESTION1B,QUESTION2A,QUESTION2B,QUESTION3A,QUESTION3B,QUESTION4A,QUESTION4B,
												QUESTION5A,QUESTION5B
												FROM crew c
												LEFT JOIN applicant a ON a.APPLICANTNO=c.APPLICANTNO
												LEFT JOIN rank r1 ON r1.RANKCODE=a.CHOICE1
												LEFT JOIN rank r2 ON r2.RANKCODE=a.CHOICE2
												LEFT JOIN addrprovince ap ON ap.PROVCODE=c.PROVINCECODE
												LEFT JOIN addrtown at ON at.TOWNCODE=c.TOWNCODE AND at.PROVCODE=c.PROVINCECODE
												LEFT JOIN addrbarangay ab ON ab.BRGYCODE=c.BARANGAYCODE AND ab.TOWNCODE=c.TOWNCODE AND ab.PROVCODE=c.PROVINCECODE
												WHERE c.APPLICANTNO=$applicantno LIMIT 2") or die(mysql_error());
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
	<div style=\"width:780px;height:50px;background-color:#DCDCDC;margin-left:10px;margin-top:10px;border:1px solid black;\" >
		<table width=\"100%\" class=\"listrow\">
			<tr>
				<th>Application No.<br />
					<input type=\"text\" name=\"applicantno\" />
					<input type=\"button\" value=\"Search\" onclick=\"if (applicantno.value != '') {actiontxt.value='search';form.submit();}\" />
					
					<input type=\"button\" name=\"btnreport\" $disableprint
						onclick=\"actiontxt.value = 'print';appsearch.submit();
						repappfront.applicantno.value='$applicantno';
						window.open(repappfront.action,repappfront.target,'scrollbars=yes,resizable=yes,channelmode=yes');
						repappfront.submit();\" value=\"Print (with Validity Date)\">	
						
					<input type=\"button\" name=\"btnreport\" $disableprint
						onclick=\"actiontxt.value = 'print';appsearch.submit();
						repappfront2.applicantno.value='$applicantno';
						window.open(repappfront2.action,repappfront2.target,'scrollbars=yes,resizable=yes,channelmode=yes');
						repappfront2.submit();\" value=\"Print (Complete)\">	
				</th>
				<td>
					<b>$searcherror</b>
				</td>
			</tr>
		</table>

</div>

<div style=\"width:780px;height:350px;background-color:$bgcolor;overflow:auto;margin-left:10px;margin-top:10px;border:1px solid black;\" >

	<div style=\"width:750px;\">
	
		<div style=\"width:74%;height:130px;float:left;\">
			<center>
				<table class=\"heading\">
					<tr>
						<th>VERITAS MARITIME CORPORATION</th>
					</tr>
					<tr>
						<td>MANILA, PHILIPPINES</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td align=\"center\">APPLICANT NO.</td>
					</tr>
					<tr>
						<th><u>$applicantno</u></th>
					</tr>
				</table>
				
				
			</center>
		</div>
	
		<div style=\"width:17%;height:130px;float:right;border:1px solid black;background-color:white;\">
";
			$dirfilename = "$basedir/$applicantno.JPG";
			if (checkpath($dirfilename))
			{
				$scale = imageScale($dirfilename,-1,120);
				$width = $scale[0];
				$height = $scale[1];
				
echo "			<center>
					<img src=\"$dirfilename\" width=\"$width\" height=\"$height\" />
					<a href=\"#\" onclick=\"if (confirm('Are you sure you want to delete the PICTURE loaded?')) {actiontxt.value='deletepic';appsearch.submit();}\">
						<span style=\"font-size:0.7em;color:red;font-weight:bold;\">[Delete]</span>
					</a>
				</center> ";

//

				$disableupload = "disabled=\"disabled\"";
			}
			else 
			{
echo "			<center><b>[NO PICTURE]</b></center>";
			}
echo "
		</div>
	</div>
	<br />
	<div style=\"width:750px;\">
	
		<div style=\"width:375px;float:left;\">
";

			$rowpersonallist = mysql_fetch_array($qrypersonallist);
			
			$choice1 = $rowpersonallist["CHOICE1"];
			$choice2 = $rowpersonallist["CHOICE2"];
			$name = $rowpersonallist["NAME"];
			$address = $rowpersonallist["ADDRESS"];
			$telno = $rowpersonallist["TELNO"];
			if ($rowpersonallist["BIRTHDATE"] != "")
				$birthdate = date('m/d/Y',strtotime($rowpersonallist["BIRTHDATE"]));
			else 
				$birthdate = "";
				
			$birthplace = $rowpersonallist["BIRTHPLACE"];
			$civilstatus = $rowpersonallist["CIVILSTATUS"];
			
			switch ($civilstatus)
			{
				case "S"	:  
					$civilstatus = "SINGLE";
					break;
				case "M"	:  
					$civilstatus = "MARRIED";
					break;
				case "W"	:  
					$civilstatus = "WIDOW";
					break;
			}
			
			$religion = $rowpersonallist["RELIGION"];
			$tin = $rowpersonallist["TIN"];
			$sss = $rowpersonallist["SSS"];
			$weight = $rowpersonallist["WEIGHT"];
			$height = $rowpersonallist["HEIGHT"];
			$sizeshoes = $rowpersonallist["SIZESHOES"];
			$gender = $rowpersonallist["GENDER"];
			
			if ($gender == "M")
				$gender = "MALE";
			else 
				$gender = "FEMALE";
			
			$email = $rowpersonallist["EMAIL"];
			if ($rowpersonallist["DATEAPPLIED"] != "")
				$dateapplied = date('m/d/Y',strtotime($rowpersonallist["DATEAPPLIED"]));
			else 
				$dateapplied = "";
				
			$recommendedby = $rowpersonallist["RECOMMENDEDBY"];
			
			$question1a = $rowpersonallist["QUESTION1A"];
			$question1b = $rowpersonallist["QUESTION1B"];
			$question2a = $rowpersonallist["QUESTION2A"];
			$question2b = $rowpersonallist["QUESTION2B"];
			$question3a = $rowpersonallist["QUESTION3A"];
			$question3b = $rowpersonallist["QUESTION3B"];
			$question4a = $rowpersonallist["QUESTION4A"];
			$question4b = $rowpersonallist["QUESTION4B"];
			$question5a = $rowpersonallist["QUESTION5A"];
			$question5b = $rowpersonallist["QUESTION5B"];
			
			
			if ($question1a == 1)
				$question1a = "YES";
			else 
				$question1a = "NO";

			if ($question2a == 1)
				$question2a = "YES";
			else 
				$question2a = "NO";
			
			if ($question3a == 1)
				$question3a = "YES";
			else 
				$question3a = "NO";
			
			if ($question4a == 1)
				$question4a = "YES";
			else 
				$question4a = "NO";
			
			if ($question5a == 1)
				$question5a = "YES";
			else 
				$question5a = "NO";
			
echo "
	
			<table class=\"details\" width=\"375px\">
				<tr>
					<th colspan=\"2\" style=\"border-bottom:1px solid black;\">POSITION APPLIED</th>
				</tr>
				<tr>
					<th width=\"50%\">First Choice</td>
					<td width=\"50%\">$choice1</td>
				</tr>
				<tr>
					<th>Second Choice</td>
					<td>$choice2</td>
				</tr>
			</table>
			<table class=\"details\" width=\"375px\">			
				<tr>
					<th colspan=\"2\" style=\"border-bottom:1px solid black;\">PERSONAL INFORMATION</th>
				</tr>				
				<tr>
					<th width=\"40%\">Name</td>
					<td width=\"60%\">$name</td>
				</tr>
				<tr>
					<th>Address</td>
					<td>$address</td>
				</tr>
				<tr>
					<th>Birth Date</td>
					<td>$birthdate</td>
				</tr>
				</tr>
				<tr>
					<th>Birth Place</td>
					<td>$birthplace</td>
				</tr>
				<tr>
					<th>Gender</td>
					<td>$gender</td>
				</tr>				
				<tr>
					<th>Civil Status</td>
					<td>$civilstatus</td>
				</tr>
				<tr>
					<th>Religion</td>
					<td>$religion</td>
				</tr>				
				<tr>
					<th>Tel. No.</td>
					<td>$telno</td>
				</tr>			
				<tr>
					<th>Weight</td>
					<td>$weight kls.</td>
				</tr>
				<tr>
					<th>Height</td>
					<td>$height cm.</td>
				</tr>		
				<tr>
					<th>Shoe Size</td>
					<td>$sizeshoes</td>
				</tr>
				<tr>
					<th>SSS No.</td>
					<td>$sss</td>
				</tr>		
				<tr>
					<th>Tax ID No.</td>
					<td>$tin</td>
				</tr>
				<tr>
					<th>Email</td>
					<td>$email</td>
				</tr>				
			</table>
		</div>
		
		<div style=\"width:375px;float:right;\">
			<div style=\"width:100%;height:120px;\">
				<table class=\"details\" width=\"100%\">
					<tr>
						<th colspan=\"4\" style=\"border-bottom:1px solid black;\">DOCUMENTS</th>
					</tr>
					<tr class=\"colhdr\">
						<td width=\"35%\">TYPE</td>
						<td width=\"25%\">DOC. NO.</td>
						<td width=\"20%\">ISSUED</td>
						<td width=\"20%\">EXPIRED</td>
					</tr>
";			
					while ($rowdoclist=mysql_fetch_array($qrydoclist))
					{
						$document1 = $rowdoclist["DOCUMENT"];
						$document2 = $rowdoclist["DOCNO"];
						if ($rowdoclist["DATEISSUED"] != "")
							$document3 = date('dMY',strtotime($rowdoclist["DATEISSUED"]));
						else 
							$document3 = "";
							
						if ($rowdoclist["DATEEXPIRED"] != "")
							$document4 = date('dMY',strtotime($rowdoclist["DATEEXPIRED"]));
						else 
							$document4 = "";
echo "					
						<tr>
							<td valign=\"top\">$document1</td>
							<td valign=\"top\">$document2</td>
							<td valign=\"top\">$document3</td>
							<td valign=\"top\">$document4</td>
						</tr>
";
					}

echo "
				</table>
			</div>
			<div style=\"width:100%;height:120px;\">
				<table class=\"details\" width=\"100%\">
					<tr>
						<th colspan=\"4\" style=\"border-bottom:1px solid black;\">LICENSES</th>
					</tr>
					<tr class=\"colhdr\">
						<td width=\"35%\">TYPE</td>
						<td width=\"25%\">DOC. NO.</td>
						<td width=\"20%\">ISSUED</td>
						<td width=\"20%\">EXPIRED</td>
					</tr>
";			
					while ($rowliclist=mysql_fetch_array($qryliclist))
					{
						$license1 = $rowliclist["DOCUMENT"];
						$license2 = $rowliclist["DOCNO"];
						if ($rowliclist["DATEISSUED"] != "")
							$license3 = date('dMY',strtotime($rowliclist["DATEISSUED"]));
						else 
							$license3 = "";
							
						if ($rowliclist["DATEEXPIRED"] != "")
							$license4 = date('dMY',strtotime($rowliclist["DATEEXPIRED"]));
						else 
							$license4 = "";
echo "						
					<tr>
						<td valign=\"top\">$license1</td>
						<td valign=\"top\">$license2</td>
						<td valign=\"top\">$license3</td>
						<td valign=\"top\">$license4</td>
					</tr>
";

					}
echo "
				</table>
			</div>
			<div style=\"width:100%;height:120px;\">
				<table class=\"details\" width=\"100%\">
					<tr>
						<th colspan=\"4\" style=\"border-bottom:1px solid black;\">CERTIFICATES</th>
					</tr>
					<tr class=\"colhdr\">
						<td width=\"40%\">TYPE</td>
						<td width=\"40%\">DOC. NO.</td>
						<td width=\"20%\">ISSUED</td>
					</tr>
";			
					while ($rowcertlist=mysql_fetch_array($qrycertlist))
					{
						$cert1 = $rowcertlist["DOCUMENT"];
						$cert2 = $rowcertlist["DOCNO"];
						if ($rowcertlist["DATEISSUED"] != "")
							$cert3 = date('dMY',strtotime($rowcertlist["DATEISSUED"]));
						else 
							$cert3 = "";
							
echo "					
					<tr>
						<td valign=\"top\">$cert1</td>
						<td valign=\"top\">$cert2</td>
						<td valign=\"top\">$cert3</td>
					</tr>
";
					}

echo "
				</table>
			</div>
		</div>
	</div>
	
	<div style=\"width:750px;height:100px;\">
		<table class=\"details\" width=\"100%\">
			<tr style=\"border-bottom:1px solid black;\">
				<th colspan=\"4\" style=\"border-bottom:1px solid black;\" valign=\"top\">EDUCATIONAL ATTAINMENT</th>
			</tr>
			<tr class=\"colhdr\">
				<td width=\"35%\">SCHOOL</td>
				<td width=\"35%\">COURSE</td>
				<td width=\"15%\">GRADUATION</td>
				<td width=\"15%\">REMARKS</td>
			</tr>
";			
					while ($roweduclist=mysql_fetch_array($qryeduclist))
					{
						$educ1 = $roweduclist["SCHOOL"];
						$educ2 = $roweduclist["COURSE"];
						if ($roweduclist["DATEGRADUATED"] != "")
							$educ3 = date('dMY',strtotime($roweduclist["DATEGRADUATED"]));
						else 
							$educ3 = "";
						$educ4 = $roweduclist["REMARKS"];
echo "								
			<tr>
				<td valign=\"top\">$educ1</td>
				<td valign=\"top\">$educ2</td>
				<td valign=\"top\">$educ3</td>
				<td valign=\"top\">$educ4</td>
			</tr>
";
					}
echo "
		</table>
	</div>
	
	<div style=\"width:100%;height:100px;\">
		<table class=\"details\" width=\"100%\">
			<tr style=\"border-bottom:1px solid black;\">
				<th colspan=\"5\" style=\"border-bottom:1px solid black;\" valign=\"top\">EMPLOYMENT HISTORY</th>
			</tr>
			<tr class=\"colhdr\">
				<td width=\"20%\">EMPLOYER</td>
				<td width=\"15%\">POSITION</td>
				<td width=\"15%\">FROM</td>
				<td width=\"15%\">TO</td>
				<td width=\"15%\">REASON</td>
			</tr>
";			
					while ($rowemploymentlist=mysql_fetch_array($qryemploymentlist))
					{
						$employment1 = $rowemploymentlist["EMPLOYER"];
						$employment2 = $rowemploymentlist["POSITION"];
						if ($rowemploymentlist["DATEFROM"] != "")
							$employment3 = date('dMY',strtotime($rowemploymentlist["DATEFROM"]));
						else 
							$employment3 = "";
							
						if ($rowemploymentlist["DATETO"] != "")
							$employment4 = date('dMY',strtotime($rowemploymentlist["DATETO"]));
						else 
							$employment4 = "";
							
						$employment5 = $rowemploymentlist["REMARKS"];
echo "		
			<tr>
				<td valign=\"top\">$employment1</td>
				<td valign=\"top\">$employment2</td>
				<td valign=\"top\">$employment3</td>
				<td valign=\"top\">$employment4</td>
				<td valign=\"top\">$employment5</td>
			</tr>
";
					}
echo "
		</table>
	</div>
	
	<div style=\"width:750px;height:130px;\">
		<table class=\"details\" width=\"100%\">
			<tr>
				<th colspan=\"10\" style=\"border-bottom:1px solid black;\">PREVIOUS SEA EXPERIENCES</th>
			</tr>
			<tr class=\"colhdr\">
				<td width=\"15%\">VESSEL</td>
				<td width=\"5%\">RANK</td>
				<td width=\"5%\">FLAG</td>
				<td width=\"5%\">TYPE</td>
				<td width=\"10%\">EMBARK</td>
				<td width=\"10%\">DISEMBARK</td>				
				<td width=\"5%\">ROUTE</td>
				<td width=\"10%\">ENGINE</td>
				<td width=\"20%\">MANNING</td>
				<td width=\"15%\">REASON</td>
			</tr>
";									
					while ($rowexperiencelist=mysql_fetch_array($qryexperiencelist))
					{
						$experience1 = $rowexperiencelist["VESSEL"];
						$experience2 = $rowexperiencelist["RANKALIAS"];
						$experience3 = $rowexperiencelist["FLAGCODE"];
						$experience4 = $rowexperiencelist["VESSELTYPECODE"];
						if ($rowexperiencelist["DATEEMB"] != "")
							$experience5 = date('dMY',strtotime($rowexperiencelist["DATEEMB"]));
						else 
							$experience5 = "";
							
						if ($rowexperiencelist["DATEDISEMB"] != "")
							$experience6 = date('dMY',strtotime($rowexperiencelist["DATEDISEMB"]));
						else 
							$experience6 = "";
						$experience7 = $rowexperiencelist["TRADEROUTECODE"];
						$experience8 = $rowexperiencelist["ENGINETYPE"];
						$experience9 = $rowexperiencelist["MANNING"];
						$experience10 = $rowexperiencelist["REASON"];
echo "		
			<tr>
				<td valign=\"top\">$experience1</td>
				<td valign=\"top\">$experience2</td>
				<td valign=\"top\">$experience3</td>
				<td valign=\"top\">$experience4</td>
				<td valign=\"top\">$experience5</td>
				<td valign=\"top\">$experience6</td>
				<td valign=\"top\">$experience7</td>
				<td valign=\"top\">$experience8</td>
				<td valign=\"top\">$experience9</td>
				<td valign=\"top\">$experience10</td>
			</tr>
";
					}
echo "		
		</table>
	</div>
	
	<div style=\"width:750px;height:100px;\">
	
		<div style=\"width:350px;float:right;height:100px;\">
			<table class=\"details\" width=\"100%\">
				<tr>
					<th colspan=\"4\" style=\"border-bottom:1px solid black;\">REFERENCES</th>
				</tr>
				<tr class=\"colhdr\">
					<td width=\"30%\">NAME</td>
					<td width=\"20%\">TEL. NO.</td>
					<td width=\"30%\">OCCUPATION</td>
					<td width=\"20%\">REMARKS</td>
				</tr>
";			
					while ($rowreferencelist=mysql_fetch_array($qryreferencelist))
					{
						$reference1 = $rowreferencelist["NAME"];
						$reference2 = $rowreferencelist["TELNO"];
						$reference3 = $rowreferencelist["OCCUPATION"];
						$reference4 = $rowreferencelist["REMARKS"];
echo "		
				<tr>
					<td valign=\"top\">$reference1</td>
					<td valign=\"top\">$reference2</td>
					<td valign=\"top\">$reference3</td>
					<td valign=\"top\">$reference4</td>
				</tr>
";
					}
echo "
			</table>
		</div>	
		
		<div style=\"width:390px;float:left;height:100px;\">
			<table class=\"details\" width=\"100%\">
				<tr>
					<th colspan=\"4\" style=\"border-bottom:1px solid black;\">FAMILY BACKGROUND</th>
				</tr>
				<tr class=\"colhdr\">
					<td width=\"35%\">NAME</td>
					<td width=\"20%\">RELATION</td>
					<td width=\"30%\">ADDRESS</td>
					<td width=\"15%\">TEL. NO.</td>
				</tr>	
";			
					while ($rowfamilylist=mysql_fetch_array($qryfamilylist))
					{
						$family1 = $rowfamilylist["NAME"];
						$family2 = $rowfamilylist["RELATION"];
						$family3 = $rowfamilylist["ADDRESS"];
						$family4 = $rowfamilylist["TELNO"];
echo "					
				<tr>
					<td valign=\"top\">$family1</td>
					<td valign=\"top\">$family2</td>
					<td valign=\"top\">$family3</td>
					<td valign=\"top\">$family4</td>				
				</tr>
";
					}
echo "
			</table>
		</div>
	</div>
	<div style=\"width:750px;height:150px;\">
		<table class=\"details\" width=\"100%\">
			<tr>
				<th colspan=\"3\" style=\"border-bottom:1px solid black;\">OTHER QUESTIONS</th>
			</tr>	
			<tr class=\"colhdr\">
				<td width=\"60%\">QUESTION</td>
				<td width=\"10%\">ANSWER</td>
				<td width=\"30%\">REMARKS</td>
			</tr>
			<tr>
				<td valign=\"top\">1. Are you a member of any local or foreign seaman's union? if yes, state union name</td>
				<td align=\"center\">$question1a</td>
				<td>$question1b</td>
			</tr>
			<tr>
				<td valign=\"top\">2. Have you ever been involved in any foreign union-related case?</td>
				<td align=\"center\">$question2a</td>
				<td>$question2b</td>
			</tr>
			<tr>
				<td valign=\"top\">3. Have you ever been dismissed for a cause, If yes, what offense?</td>
				<td align=\"center\">$question3a</td>
				<td>$question3b</td>
			</tr>
			<tr>
				<td valign=\"top\">4. Do you have pending case with POEA, what offense?</td>
				<td align=\"center\">$question4a</td>
				<td>$question4b</td>
			</tr>
			<tr>
				<td valign=\"top\">5. Have you ever been sued in any court? what offense?</td>
				<td align=\"center\">$question5a</td>
				<td>$question5b</td>
			</tr>
			<tr>
				<td><b>Recommended By</b></td>
				<td colspan=\"2\">$recommendedby</td>
			</tr>
		</table>
	</div>	
	
	<div style=\"width:750px;height:100px;\">
		<table class=\"details\" width=\"100%\">	
			<tr>
				<td colspan=\"4\">
					<center>
					<b>I HEREBY CERTIFY THAT ALL INFORMATION PROVIDED BY THE UNDERSIGNED ARE TRUE AND CORRECT AND ANY MISREPRESENTATION OR INCORRECT DATA SUPPLIED SHALL  BE JUST CAUSE FOR TERMINATION OF MY EMPLOYMENT.</b>
					</center>
				</td>
			</tr>
			<tr>
				<td colspan=\"4\">&nbsp;</td>
			</tr>
			<tr>
				<td width=\"25%\">&nbsp;</td>
				<td width=\"25%\">
					<center>$dateapplied</center></td>
				<td width=\"25%\">&nbsp;</td>
				<td width=\"25%\">&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td style=\"border-top:thin solid black;\">
					<center>DATE</center>
				</td>
				<td>&nbsp;</td>
				<td style=\"border-top:thin solid black;\">
					<center>APPLICANT SIGNATURE</center>
				</td>
			</tr>
			<tr>
				<td colspan=\"4\" align=\"left\">Print Date: $printdate</td>
			</tr>
		</table>
	</div>


</div>

	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"appno\" value=\"$applicantno\" />

</form>

<form enctype=\"multipart/form-data\" method=\"POST\" name=\"fileupload\">

	<div style=\"width:780px;height:75px;background-color:#DCDCDC;margin-left:10px;margin-top:10px;border:1px solid black;\" >
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

<form name=\"repappfront\" action=\"repappfront.php\" target=\"repappfront\" method=\"GET\">
	<input type=\"hidden\" name=\"applicantno\">
</form>

<form name=\"repappfront2\" action=\"repappfront2.php\" target=\"repappfront2\" method=\"GET\">
	<input type=\"hidden\" name=\"applicantno\">
</form>

</body>

</html>

"
	
	
	
	
	
	
	
?>