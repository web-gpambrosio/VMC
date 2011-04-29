<?php
// Note that !== did not exist until 4.0.0-RC2
///www/veritas/scanned/1998D165
session_start();
$getservername=$_SERVER["SERVER_NAME"];
$getserveraddr=$_SERVER["SERVER_ADDR"];

include('veritas/connectdb.php');
include('veritas/include/stylephp.inc');
$localdir="dumping"; // local dumping
$serverdir="scanned"; // server dumping with addl subdirectory (APPLICANTNO)



if(isset($_POST["applicantno"]))
	$applicantno=$_POST["applicantno"];
	
if(isset($_POST["crewcode"]))
	$crewcode=$_POST["crewcode"];
	
if(isset($_POST["gname"]))
	$gname=$_POST["gname"];
	
if(isset($_POST["fname"]))
	$fname=$_POST["fname"];
	
if(isset($_POST["actiontxt"]))
	$actiontxt=$_POST["actiontxt"];
	
if(isset($_POST["actionajax"]))
	$actionajax=$_POST["actionajax"];
	
if(isset($_POST["imgfile"]))
	$imgfile=$_POST["imgfile"];
	


switch ($actiontxt)
{
	case "gname":
		$multidata=1;
		$qrygetname=mysql_query("SELECT APPLICANTNO,CREWCODE,FNAME,GNAME,CONCAT(FNAME,', ',GNAME) AS CREWNAME 
			FROM crew WHERE GNAME='$gname'") or die(mysql_error());
		if(mysql_num_rows($qrygetname)==0)
		{
			echo "<script>alert('$gname not found!')</script>";
			$gname="";
			$multidata=0;
		}
		if(mysql_num_rows($qrygetname)==1)
		{
			$rowgetname=mysql_fetch_array($qrygetname);
			$applicantno=$rowgetname["APPLICANTNO"];
			$crewcode=$rowgetname["CREWCODE"];
			$fname=$rowgetname["FNAME"];
			$gname=$rowgetname["GNAME"];
			$multidata=0;
		}
	break;
	case "fname":
		$multidata=1;
		$qrygetname=mysql_query("SELECT APPLICANTNO,CREWCODE,FNAME,GNAME,CONCAT(FNAME,', ',GNAME) AS CREWNAME 
			FROM crew WHERE FNAME='$fname'") or die(mysql_error());
		if(mysql_num_rows($qrygetname)==0)
		{
			echo "<script>alert('$fname not found!')</script>";
			$fname="";
			$multidata=0;
		}
		if(mysql_num_rows($qrygetname)==1)
		{
			$rowgetname=mysql_fetch_array($qrygetname);
			$applicantno=$rowgetname["APPLICANTNO"];
			$crewcode=$rowgetname["CREWCODE"];
			$fname=$rowgetname["FNAME"];
			$gname=$rowgetname["GNAME"];
			$multidata=0;
		}
	break;
	case "crewcode":
		$qrygetname=mysql_query("SELECT APPLICANTNO,CREWCODE,FNAME,GNAME
			FROM crew WHERE CREWCODE='$crewcode'") or die(mysql_error());
		if(mysql_num_rows($qrygetname)==0)
		{
			echo "<script>alert('$crewcode not found!')</script>";
			$crewcode="";
		}
		if(mysql_num_rows($qrygetname)==1)
		{
			$rowgetname=mysql_fetch_array($qrygetname);
			$applicantno=$rowgetname["APPLICANTNO"];
//			$crewcode=$rowgetname["CREWCODE"];
			$fname=$rowgetname["FNAME"];
			$gname=$rowgetname["GNAME"];
		}
	break;
	case "applicantno":
		$qrygetname=mysql_query("SELECT APPLICANTNO,CREWCODE,FNAME,GNAME
			FROM crew WHERE APPLICANTNO='$applicantno'") or die(mysql_error());
		if(mysql_num_rows($qrygetname)==0)
		{
			echo "<script>alert('$applicantno not found!')</script>";
			$applicantno="";
		}
		if(mysql_num_rows($qrygetname)==1)
		{
			$rowgetname=mysql_fetch_array($qrygetname);
//			$applicantno=$rowgetname["APPLICANTNO"];
			$crewcode=$rowgetname["CREWCODE"];
			$fname=$rowgetname["FNAME"];
			$gname=$rowgetname["GNAME"];
		}
	break;
	case "upload":
		if ($handle = opendir($localdir)) 
		{
			$cntfiles=0;
			$getlist="";
		    while (false !== ($file = readdir($handle))) 
		    {
		    	if ($file != "." && $file != "..") 
		    	{
			        $file_parts=pathinfo($localdir.'/'.$file);
			        if($file_parts['extension']=='pdf')
			        {
			        	copy($localdir.'/'.$file,$serverdir.'/'.$applicantno.'_'.$file);
						unlink($localdir.'/'.$file);
			        }
		    		$cntfiles++;
		    	}
		    }
		}
		if($cntfiles>0)
			echo "<script>alert('Files transferred to SCANNED folder');</script>";
		else 
			echo "<script>alert('No files found!');</script>";
		closedir($handle);
	break;
	case "deleteimg":
		unlink($localdir.'/'.$imgfile);
	break;
}
//SEARCH FOR FILES IN 'DUMPING' FOLDER
if ($handle = opendir($localdir)) 
{
	$cnt=0;
	$getlist="";
    while (false !== ($file = readdir($handle))) 
    {
    	if ($file != "." && $file != "..") 
    	{
	        $file_parts=pathinfo($localdir.'/'.$file);
	        if($file_parts['extension']=='pdf')
	        {
//	        	$files[] = $file_parts['filename'];
	        	$files[] = $file;
	        	$getlist.="<tr $mouseovereffect><td onclick=\"viewpdf('$file')\" style=\"cursor:pointer;\">&nbsp;$file</td>";
	        	$getlist.="<td><input type=\"button\" value=\"X\" style=\"cursor:pointer;font-weight:bold;color:red;font-size:8pt;border:0;background:inherit;\" onclick=\"if(confirm('Are you sure you want to delete $file?')){actiontxt.value='deleteimg';imgfile.value='$file';submit();}\"></td></tr>";
	        }
    		$cnt++;
    	}
    }
    $getlist.="<tr><td colspan=\"2\" style=\"font-weight:bold;\">Count: $cnt</td></tr>";
}
closedir($handle);

if(!empty($applicantno) && $cnt>0)
{
	$display="block";
}
else 
{
	$display="none";
}

echo "<html>
<title>File Dumping</title>
<head>
<script type='text/javascript' src='../veritas/veripro.js'></script>
<script type='text/javascript' src='../veritas/ajax.js'></script>
<script>
function datachange(x,y)
{
	with(document.filedumping)
	{
		gname.value='';
		fname.value='';
		crewcode.value='';
		applicantno.value='';
	}
	if(x)
	{
		document.filedumping(x).value=y;
		document.filedumping.actiontxt.value=x;
		document.filedumping.btnsearch.click();
	}
	else
	{
		document.filedumping.actiontxt.value='';
		document.filedumping.gname.focus();
	}
}
function viewpdf(x) //loc - 1 for local, 2 for server, 3 for idpics
{
	var location='$localdir/';
	var myObject = new Object();
    myObject.getimage = location + x + '#toolbar=0&navpanes=0';
    window.showModalDialog('viewpdf.php?ttlimage='+x, myObject, 'dialogHeight:750px; dialogWidth:700px;status=no'); 
}
var url;
var fillurl;
function createurl() // 
{
//alert('k');
	url = \"http://$getserveraddr/veritas/crewchangeplanrequest.php?actionajax=\"; // The server-side script 
	
	var actionajax = document.filedumping.actionajax.value; 
		
	//fill URL
	var applicantno = '&embapplicantnohidden=' + document.filedumping.applicantno.value;
	fillurl = actionajax + applicantno;
//	alert(fillurl);
	getvalues();
}
function switchajax(x)
{
	if(x==1) // 1 if loading box is visible
	{
		var strdisplay='block';
		var strdisable='disabled';
	}
	else
	{
		var strdisplay='none';
		var strdisable='';
	}
	
	document.getElementById('ajaxprogress').style.display=strdisplay;
	
}
function fillview201()
{
	if(document.filedumping.actionajax.value=='viewonboard201')
	{
		onboard201result=results[2];
		onboard201();
	}
}
function onboard201()
{
	var myObject = new Object();
    myObject.getonboard201result = onboard201result;
    myObject.getcrewname = document.filedumping.fname.value + ', ' + document.filedumping.gname.value;
    window.showModalDialog('crewonboard201.php', myObject, 'dialogHeight:650px; dialogWidth:500px;status=no'); 
}
</script>
</head>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />

<body style=\"overflow:hidden;background:black;\" onload=\"if('$multidata'=='1'){document.getElementById('multidata').style.display='block';};document.filedumping.applicantno.focus();\">
<form name=\"filedumping\" method=\"POST\">\n

<span style=\"width:350px;\" class=\"wintitle\">FILE DUMPING</span>

<div style=\"width:350px;height:500px;float:left;overflow:hidden;\">
	<div style=\"height:150px;overflow:hidden;background:silver;\">
		<table style=\"color:Red;font-size:10pt;\">
			<tr>
				<td><b>Given name.</b></td>
				<td>
					<input type=\"text\" name=\"gname\" value=\"$gname\" 
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"if('$gname'!=this.value){datachange(this.name,this.value);}\"/>
				</td>
				<td rowspan=\"3\">
					<img src=\"idpics/$applicantno.JPG\"
						width=\"85px\" height=\"85px\">
				</td>
			</tr>
			<tr>
				<td><b>Last name.</b></td>
				<td>
					<input type=\"text\" name=\"fname\" value=\"$fname\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"if('$fname'!=this.value){datachange(this.name,this.value);}\"/>
				</td>
			</tr>
			<tr>
				<td><b>Crew Code.</b></td>
				<td>
					<input type=\"text\" name=\"crewcode\" value=\"$crewcode\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"if('$crewcode'!=this.value){datachange(this.name,this.value);}\"/>
				</td>
			</tr>
			<tr>
				<td><b>Applicant No.</b></td>
				<td>
					<nobr><input type=\"text\" name=\"applicantno\" value=\"$applicantno\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"if('$applicantno'!=this.value){datachange(this.name,this.value);}\"/></td>
				<td>
					<input type=\"button\" name=\"btnupload\" value=\"Upload\" style=\"display:$display;font-weight:bold;color:blue;\"
						onclick=\"if(confirm('Are you sure you want to upload?')){actiontxt.value='upload';submit();}\" /></nobr>
				</td>
			</tr>
			<tr>
				<td><input type=\"button\" name=\"btnview\" value=\"View\"
					onclick=\"if('$applicantno'!=''){actionajax.value='viewonboard201';createurl();}\" /></td>
				<td>
					<input type=\"button\" name=\"btnsearch\" value=\"Search\" onclick=\"submit();\" />
					<input type=\"button\" name=\"btnreset\" value=\"Reset\" onclick=\"datachange();submit();\" />
				</td>
			</tr>
		</table>
	</div>
	<div id=\"dumpfilelist\" style=\"width:350px;height:550px;background:black;float:left;text-align:center;\">
		<span style=\"font-weight:bold;color:yellow;width:300px;\" class=\"listcol\">DUMPED FILES</span>
		<div style=\"width:350px;height:330px;background:silver;\">
			<table id=\"certimage\" class=\"listcol\" cellspacing=\"1\" cellpadding=\"0\">
				$getlist
			</table>
		</div>
		
	</div>
	<!--
	<div style=\"width:320px;height:550px;background:gray;float:right;text-align:center;\">
		<span id=\"zoomfile\" style=\"font-weight:bold;color:yellow;cursor:pointer;\" title=\"click to enlarge image...\"
			onclick=\"if(document.getElementById('dumpfile').src!=''){
				viewpdf(doctypefolder.value+'/'+docfile.value,2)}else{alert('No image to enlarge!');}\">
			Filename
		</span>
		<iframe id=\"dumpfile\" src=\"\" height=\"400\" width=\"300\"></iframe>
		<input type=\"button\" value=\"Back to dump\" onclick=\"senddump();\" style=\"float:left;\">
		<input type=\"button\" value=\"Delete\" style=\"float:right;\" onclick=\"chkdeleteimage();\">
	</div>
	-->
</div>
<div id=\"multidata\"
	style=\"background:white;z-index:200;position:absolute;left:95px;top:150px;width:200px;height:300px;
		border:3px solid black;display:none;\">
	<span style=\"width:200px;\" class=\"wintitle\" style=\"font-size:12pt;\">MULTIPLE DATA</span>
	<br>
	<center>
	<table style=\"width:100%;overflow:hidden;\" cellpadding=\"0\" cellspacing=\"0\">
		<tr><th style=\"font-size:10pt;background:black;color:yellow;font-weight:bold;\">CREW NAME</th></tr>";
		$cntdata=0;
		while($rowgetname=mysql_fetch_array($qrygetname))
		{
			$applicantno=$rowgetname["APPLICANTNO"];
			$crewname=$rowgetname["CREWNAME"];
			echo "
			<tr title=\"click to select...\" $mouseovereffect
				onclick=\"applicantno.value='$applicantno';actiontxt.value='applicantno';submit();\">
				<td style=\"$styledetails;cursor:pointer;\">$crewname</td>
			</tr>
			";
			$cntdata++;
		}
	echo "
		<tr><td onclick=\"datachange();document.getElementById('multidata').style.display='none';\" 
			style=\"$styledetails;text-align:center;cursor:pointer;color:blue;\">-close-</td></tr>
		<tr><th style=\"font-size:10pt;background:black;color:yellow;font-weight:bold;\">COUNT: $cntdata</th></tr>
	</table>
	</center>
</div>";
include("veritas/include/ajaxprogress.inc");
echo "
<input type=\"hidden\" name=\"actiontxt\">
<input type=\"hidden\" name=\"actionajax\">
<input type=\"hidden\" name=\"imgfile\">

</form>
</body>
</html>";

?>