<?php

include("veritas/connectdb.php");
include("veritas/include/stylephp.inc");

$currentdate = date("Y-m-d H:i:s");

if (isset($_SESSION["employeeid"]))
	$user = $_SESSION["employeeid"];

$display1 = "display:none;";
$dateformat = "dMY";

$basedir = "/comments";

if (isset($_GET["applicantno"]))
	$applicantno = $_GET["applicantno"];

if (isset($_POST["actiontxt"]))
	$actiontxt = $_POST["actiontxt"];

if (isset($_POST["comment"]))
	$comment = $_POST["comment"];
	
	
switch($actiontxt)
{
	case "addcomment":
	
		$qrysave = mysql_query("INSERT INTO crewcomments(APPLICANTNO,COMMENT,MADEBY,MADEDATE)
								VALUES ($applicantno,'$comment','$user','$currentdate')") or die(mysql_error());
	
	break;
}
	
	
$qrycomments = mysql_query("SELECT * FROM crewcomments WHERE APPLICANTNO=$applicantno ORDER BY MADEDATE DESC") or die(mysql_error());
	
echo "
<html>

<head>
	<title>Crew Comments</title>
	<script language=\"javascript\" src=\"veripro.js\"></script>	
	<link rel=\"stylesheet\" href=\"veripro.css\">
	
</head>

<body style=\"overflow:hidden;\">
<form name=\"crewcomments\" method=\"POST\">

	<span class=\"wintitle\">CREW COMMENTS</span>
	<br />
	
	<div style=\"width:100%;height:150px;padding: 5 5 5 5;font-size:0.9em;font-weight:Bold;\">
		Comment: <br />
		<textarea cols=\"65\" rows=\"5\" name=\"comment\"></textarea> <br />
		<input type=\"button\" value=\"Add Comment...\" 
			onclick=\"if (comment.value != ''){actiontxt.value='addcomment';submit();} else {alert('Invalid Comment.');}\" />
		<input type=\"button\" value=\"Refresh\" onclick=\"submit();\" />
	</div>

	<hr />
	
	<div style=\"margin-left:20px;margin-right:20px;font-size:0.9em;overflow:auto;height:275px;\">
	";
	
		while ($rowcomments = mysql_fetch_array($qrycomments))
		{
			$xidno = $rowcomments["IDNO"];
			$xcomment = $rowcomments["COMMENT"];
			$xmadeby = $rowcomments["MADEBY"];
			$xmadedate = $rowcomments["MADEDATE"];
			
			echo "
			<span style=\"font-size:0.8em;color:Gray;font-weight:Bold;\">User: $xmadeby / Date: $xmadedate</span> <br /><br />
			<span style=\"font-size:0.9em;color:Green;\">$xcomment</span> <br /><br />
			";
			
			$viewlist = "";
			$dir="comments/$applicantno";
			if ($handle = opendir($dir)) 
			{
				$cnt=0;
				
				while (false !== ($file = readdir($handle))) 
				{
					if ($file != "." && $file != "..") 
					{
						$file_parts=pathinfo($dir.'/'.$file);
						if($file_parts['extension']=='pdf')
						{
							// $getdocfile=explode("_",$file_parts['filename']);
							if($xidno==$file_parts['filename'])
							{
								$cnt++;
								$targetdoc = "pdf$cnt";
								$viewlist = "<span onclick=\"javascript:openWindow('comments/$applicantno/$file', '$targetdoc', 900, 600);\" ";
								$viewlist .= "style=\"cursor:pointer;color:Red;font-size:0.8em;\"><b><i>Click here to view PDF File</i></b></span> <br />";
							}
						}
					}
				}
				closedir($handle);
				// if($files)
				// {
					// rsort($files);
					// $getlatestdoc1=$files[0];
					// $targetdoc = "old$personalcrewcode";
					// $getlatestdoc="<span onclick=\"javascript:openWindow('$basedir/$embapplicantnohidden/D/OLD/$getlatestdoc1', '$targetdoc', 900, 600);\" ";
					// $getlatestdoc.="style=\"cursor:pointer;color:blue;font-size:0.8em;\"><b><i>[OLD]</i></b></span>";
				// }
			}
			
			if (empty($viewlist))
			{
				$viewlist = "<center>
					<a href=\"#\" onclick=\"openWindow('crewcomments_upload.php?applicantno=$applicantno&idno=$xidno', 'pdfupload', 350, 200);\" style=\"color:Blue;font-size:0.8em;font-weight:Bold;\"><i>[Upload PDF file]</i></a>
					</center>
					";
			}
			
			echo "
				$viewlist
			
			<hr />
			";
		
		}
	
	
	echo "
	</div>
	
	<input type=\"hidden\" name=\"actiontxt\" />

</form>
</body>

</html>
";
	
	
?>