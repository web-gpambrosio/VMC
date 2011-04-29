<?php
include('veritas/connectdb.php');
include("veritas/include/stylephp.inc");
include("veritas/include/functions.inc");
header('Content-Type: text/html; charset=UTF-8');

session_start();

if(isset($_SESSION['employeeid']))
	$employeeid=$_SESSION['employeeid'];
	
$currentdate = date("Y-m-d H:i:s");
$currentdate2 = date("Ymd");
$datenow = date("m/d/Y");
$errormsg = "";
$checked = "checked=\"checked\"";

if(isset($_POST["ccid"]))
	$ccid=$_POST["ccid"];
else 
	$ccid=$_GET["ccid"];

if(isset($_POST["idno"]))
	$idno=$_POST["idno"];
else 
	$idno=$_GET["idno"];
	
if(isset($_POST["applicantno"]))
	$applicantno=$_POST["applicantno"];
else 
	$applicantno=$_GET["applicantno"];

if(isset($_POST["actiontxt"]))
	$actiontxt = $_POST["actiontxt"];

if(isset($_POST["effectdate"]))
	$effectdate = $_POST["effectdate"];

if(isset($_POST["manningco"]))
	$manningco = $_POST["manningco"];

if(isset($_POST["salaryoffered"]))
	$salaryoffered = $_POST["salaryoffered"];

if(isset($_POST["vmcsalary"]))
	$vmcsalary = $_POST["vmcsalary"];

if(isset($_POST["transfer"]))
	$transfer = $_POST["transfer"];
else
	$transfer = 0;

$serverdirtmp="docimg"; 
// $serverdirtmp="docimages"; 
	
switch ($transfer)
{
	case "0"	:	$chktransfer0 = "checked=\"checked\""; break;
	case "1"	:	$chktransfer1 = "checked=\"checked\""; break;
	case "2"	:	$chktransfer2 = "checked=\"checked\""; break;
}
	
	
switch ($actiontxt)
{
	case "upload"	:
			
			//PDF FILE UPLOAD
			
			$doneupload2 = 0;
			$uploadmsg2 = "";
			
			$filename = $_FILES["uploadedfile_pdf"]["name"];
			
			$getfilename = explode(".",$filename);
			$ext = $getfilename[1];
			
			$dir=$serverdirtmp.'/'. $applicantno;  
			
			if (!checkpath($dir,2))  //CHECK IF docimages/$applicantno directory exists
				mkdir($dir,0777);
				
			if ($ext == "pdf")  //CHECK IF extension is PDF...
			{
				$file = $dir . '/' . 'CrewWithdrawalForm' . '_' . $idno . '.' . $ext;
				
				if (!checkpath($file,1))
				{
					$tmp_name = $_FILES['uploadedfile_pdf']['tmp_name'];
					$error = $_FILES['uploadedfile_pdf']['error'];
		
					if ($error==UPLOAD_ERR_OK) 
					{
						if ($_FILES['uploadedfile_pdf']['size'] > 0)
						{
							if(move_uploaded_file($tmp_name, $file))
							{
					      		$doneupload2 = 1;
					      		$uploadmsg2 = "File: $filename uploaded successfully.";
								
								$effectdateraw = date("Y-m-d H:i:s",strtotime($effectdate));
								
								$qryupdateeffect = mysql_query("UPDATE crewwithdrawal SET EFFECTDATE='$effectdateraw',
																			TRANSFER=$transfer,
																			MANNINGCOMPANY='$manningco',
																			SALARYOFFERED='$salaryoffered',
																			VMCSALARY='$vmcsalary'
																WHERE IDNO=$idno") or die(mysql_error());
							}
					      	else 
					      	{
					      		$doneupload2 = 0;
					      		$uploadmsg2 = "File upload failed.";
					      	}
						}
						else
						{
							$uploadmsg2 = "File: $filename is empty.";
						}
					}
					else if ($error==UPLOAD_ERR_NO_FILE) 
						{
							$uploadmsg2 = "No files specified.";
						} 
						else
						{
							$uploadmsg2 = "File Upload failed2.";
						}
				}
			}
				
			//END OF PDF FILE UPLOAD	
		break;
}

echo "
<html>
<head>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>
<script language=\"javascript\" src=\"popcalendar.js\"></script>	
</head>
<body style=\"overflow:hidden;\">

<form name=\"withdrawalsafekeep\" method=\"POST\" enctype=\"multipart/form-data\">
	<span class=\"sectiontitle\">UPLOAD FILE</span>
	<br />
	<center>
		<span style=\"font-size:0.8em;font-weight:Bold;\">
		<span style=\"color:Blue;font-size:0.9em;\"><i>The default status is WITHDRAW. Choose here if otherwise.</i></span>
	<!--	<input type=\"radio\" name=\"transfer\" $chktransfer0 value=\"0\" />No Transfer &nbsp;&nbsp;&nbsp;  -->
		<input type=\"radio\" name=\"transfer\" $chktransfer1 value=\"1\" />Loaned &nbsp;&nbsp;&nbsp;
		<input type=\"radio\" name=\"transfer\" $chktransfer2 value=\"2\" />Transferred
		<br />
		<br />
		
		Effect Date <br />
		<input type=\"text\" name=\"effectdate\" value=\"$effectdate\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
		<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" onclick=\"popUpCalendar(effectdate, effectdate, 'mm/dd/yyyy', 0, 0);return false;\">
		<br /><br />
		
		<table style=\"width:90%;font-size:0.9em;font-weight:Bold;\">
			<tr>
				<td>Manning Company</td>
				<td>:</td>
				<td><input type=\"text\" name=\"manningco\" size=\"30\" /></td>
			</tr>
			<tr>
				<td>Salary Offered</td>
				<td>:</td>
				<td><input type=\"text\" name=\"salaryoffered\" /></td>
			</tr>
			<tr>
				<td>VMC Salary</td>
				<td>:</td>
				<td><input type=\"text\" name=\"vmcsalary\" /></td>
			</tr>
		</table>
		<br />
		
		Upload Scanned Withdrawal Form <br /><br />
		
		<input name=\"uploadedfile_pdf\" type=\"file\" size=\"20\" style=\"visibility:show\" />  <br /><br />
		<input name=\"upload\" type=\"button\" onclick=\"if (uploadedfile_pdf.value != '' && effectdate.value != ''){actiontxt.value='upload';idno.value=$idno;withdrawalsafekeep.submit();} else {alert('No File Selected or Invalid Effect Date.');}\" value=\"Upload\" />
			
		<input type=\"button\" value=\"Close\" onclick=\"window.close();\" />
	
	<br /><br />
	<span style=\"color:Red;font-size:1em;font-weight:Bold;\">$uploadmsg2</span>
	</center>

	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"idno\" />
	<input type=\"hidden\" name=\"applicantno\" value=\"$applicantno\" />
	
</form>
</body>

</html>
";