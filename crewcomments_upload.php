<?php

include("veritas/connectdb.php");
include("veritas/include/stylephp.inc");

if (isset($_GET["idno"]))
	$idno = $_GET["idno"];
	
if (isset($_GET["applicantno"]))
	$applicantno = $_GET["applicantno"];

if (isset($_POST["actiontxt"]))
	$actiontxt = $_POST["actiontxt"];
	
$commentsdir = "comments/";	

	
function checkpath($path,$type)
{
	switch ($type)
	{
		case "1" :
				if (is_file($path))
					return true;
				else 
					return false;
			break;
		case "2" :
				if (is_dir($path))
					return true;
				else 
					return false;
			break;
	}
}

	
switch($actiontxt)
{
	case  "uploadpdf":
		
		//PDF FILE UPLOAD
		
		$doneupload2 = 0;
		$uploadmsg2 = "";
		
		$filename = $_FILES["uploadedfile_pdf"]["name"];
		
		$getfilename = explode(".",$filename);
		$ext = $getfilename[1];
		
		$dir=$commentsdir. $applicantno;  
		
		if (!checkpath($dir,2))  //CHECK IF docimages/$applicantno directory exists
			mkdir($dir,0777);
		// $dir .= '/' . 'CER';
		
		// if (!checkpath($dir,2))  //CHECK IF docimages/$applicantno/CER directory exists
			// mkdir($dir,0777);
			
		if ($ext == "pdf")  //CHECK IF extension is PDF...
		{
			$file = $dir . "/" . $idno . "." . $ext;
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
	<title>Crew Comments</title>
	<script language=\"javascript\" src=\"veripro.js\"></script>	
	<link rel=\"stylesheet\" href=\"veripro.css\">
	
</head>

<body>
<form name=\"commentsupload\" method=\"POST\" id=\"commentsupload\" enctype=\"multipart/form-data\">

	<center>
	<br />
	<b>Upload PDF file:</b> <br /><br />
	<input name=\"uploadedfile_pdf\" type=\"file\" size=\"20\" style=\"visibility:show\" /> <br /><br />
	<input name=\"uploaded\" type=\"button\" 
		onclick=\"if (uploadedfile_pdf.value != ''){actiontxt.value='uploadpdf';commentsupload.submit();}
		else {alert('No File Selected!');}\"
		value=\"Upload PDF File!\" />
	<input type=\"button\" value=\"Close\" onclick=\"window.close();\" />
	<br /><br />
	<span style=\"color:Red;font-size:0.9em;font-weight:Bold;\"><i>$uploadmsg2</i></span>
	</center>
	
	<input type=\"hidden\" name=\"actiontxt\" />
	
</form>

</body>

</html>
";

?>