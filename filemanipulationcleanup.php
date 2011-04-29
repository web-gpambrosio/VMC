<?php
// Note that !== did not exist until 4.0.0-RC2
///www/veritas/scanned/1998D165
session_start();
$getservername=$_SERVER["SERVER_NAME"];
$getserveraddr=$_SERVER["SERVER_ADDR"];

include('veritas/connectdb.php');
include('veritas/include/stylephp.inc');
// $localdir="docimages"; // local dumping
// $serverdir="docimages"; // server dumping with addl subdirectory (APPLICANTNO)
$localdir="docimg"; // local dumping
$serverdir="docimg"; // server dumping with addl subdirectory (APPLICANTNO)


// REMARKS:  Upon scanning of DOCUMENT, file MUST be saved in "SCANNED" directory as "<APPLICANTNO>_TIMESTAMP.pdf" (LOCAL Directory in workstation).
//			-- File Manipulation Module will read the "SCANNED" directory for pending files to be renamed. 
//			Encoder will then VIEW the "raw" file, and choose from the list of available documents from that APPLICANT NO.
//


if(isset($_POST["applicantno"]))
	$applicantno=$_POST["applicantno"];
	
if(isset($_POST["doccodelist"]))
	$doccodelist=$_POST["doccodelist"];

$doccode=explode("*",$doccodelist);

if(isset($_POST["doctype"]))
	$doctype=$_POST["doctype"];
	
if(isset($_POST["doctypefolder"]))
	$doctypefolder=$_POST["doctypefolder"];
	
if(isset($_POST["docfile"]))
	$docfile=$_POST["docfile"];
	
if(isset($_POST["filestoragedump"]))
	$filestoragedump=$_POST["filestoragedump"];

if(isset($_POST["dumpfileselect"]))
	$dumpfileselect=$_POST["dumpfileselect"];

if(isset($_POST["placefilename"]))
	$getplacefilename=$_POST["placefilename"];

if(isset($_POST["actiontxt"]))
	$actiontxt=$_POST["actiontxt"];

if(isset($_POST["searchkey"]))
	$searchkey=$_POST["searchkey"];

switch ($actiontxt)
{
	case "renamefile":
		$getdoctype=$doccode[2];
		if(!is_dir($serverdir.'/'.$applicantno))
		{
//			echo "YES ".$serverdir.'/'.$applicantno;
			mkdir($serverdir.'/'.$applicantno);
		}
		if(!is_dir($serverdir.'/'.$applicantno.'/'.$getdoctype))
		{
			mkdir($serverdir.'/'.$applicantno.'/'.$getdoctype);
//			echo "<script>alert('$serverdir/$applicantno/$getdoctype')</script>";
		}
		$getnewfile=$doccode[1].".pdf";
		if(is_file($serverdir.'/'.$applicantno.'/'.$getdoctype.'/'.$getnewfile))
		{
			if(!is_dir($serverdir.'/'.$applicantno.'/'.$getdoctype.'/OLD'))
				mkdir($serverdir.'/'.$applicantno.'/'.$getdoctype.'/OLD');
			$chkout=0;
			$tst=0;
			$incr=1;
			while($chkout==0)
			{
				$tst++;
				if($incr<10)
					$addincr="0";
				else 
					$addincr="";
				if(is_file($serverdir.'/'.$applicantno.'/'.$getdoctype.'/OLD/'.$doccode[1]."_".$addincr.$incr.".pdf"))
				{
					$incr++;
//					echo "<script>alert('$incr')</script>";
				}
				else 
				{
					copy($serverdir.'/'.$applicantno.'/'.$getdoctype.'/'.$getnewfile,$serverdir.'/'.$applicantno.'/'.$getdoctype.'/OLD/'.$doccode[1]."_".$addincr.$incr.".pdf");
					break;
				}
//				echo "<script>alert('$incr')</script>";
				if($tst==50)
					break;
			}
//			echo "<script>alert('$tst')</script>";
		}
		copy($localdir.'/'.$applicantno.'/'.$getplacefilename,$serverdir.'/'.$applicantno.'/'.$getdoctype.'/'.$getnewfile);
		unlink($localdir.'/'.$applicantno.'/'.$getplacefilename);
	break;
	case "dumpfile":
		$getnewfile=$applicantno."_".$docfile;
//		echo $serverdir.'/'.$applicantno.'/'.$doctypefolder.'/'.$docfile;
		copy($serverdir.'/'.$applicantno.'/'.$doctypefolder.'/'.$docfile,$localdir.'/'.$applicantno.'/'.$getnewfile);
		unlink($serverdir.'/'.$applicantno.'/'.$doctypefolder.'/'.$docfile);
	break;
	case "deletefile":
		unlink($localdir.'/'.$applicantno.'/'.$getplacefilename);
	break;
	case "deleteimage":
		unlink($serverdir.'/'.$applicantno.'/'.$doctypefolder.'/'.$docfile);
	break;
	
	case "search":
		$qrygetappno = mysql_query("SELECT APPLICANTNO FROM crew WHERE CREWCODE='$searchkey'") or die(mysql_error());
		
		if (mysql_num_rows($qrygetappno) > 0)
		{
			$rowgetappno = mysql_fetch_array($qrygetappno);
			$searchappno = $rowgetappno["APPLICANTNO"];
		}
		else 
			$searchappno = "Not Found.";
	break;
}

//GET DOCUMENT TYPE
$qrygetdoctype=mysql_query("SELECT DISTINCT TYPE FROM crewdocuments WHERE TYPE IS NOT NULL AND TYPE<>'' ORDER BY TYPE") or die(mysql_error());

//GET DOCUMENT
if(!empty($doctype))
	$wheredoctype="AND TYPE='$doctype'";
$qrygetdoc=mysql_query("SELECT DOCCODE,DOCUMENT,TYPE FROM crewdocuments WHERE TYPE IS NOT NULL AND TYPE<>'' $wheredoctype ORDER BY DOCUMENT") or die(mysql_error());

//CREATE SELECT BOX FOR OWNER (APPLICANTNO) OF FILES
$getselected="";
if ($handle = opendir($localdir)) 
{
    /* This is the correct way to loop over the directory. */
	$cnt=0;
	
    while (false !== ($dir1 = readdir($handle))) 
    {
    	if ($dir1 != "." && $dir1 != "..") 
    	{
    		$dir2=$localdir.'/'.$dir1;
    		$handle1 = opendir($dir2);
    		while (false !== ($file = readdir($handle1))) 
    		{
		    	if ($file != "." && $file != "..") 
		    	{
			        $file_parts=pathinfo($localdir.'/'.$file);
			        if($file_parts['extension']=='pdf')
			        {
			        	//get fname, gname for sorting
			        	$qryname=mysql_query("SELECT CONCAT(FNAME,', ',GNAME) AS CREWNAME 
	    					FROM crew WHERE APPLICANTNO='$dir1'") or die(mysql_error());
			        	$rowname=mysql_fetch_array($qryname);
		    			$crewname=$rowname["CREWNAME"];
			        	$files[] = $crewname."_".$dir1."_".$file_parts['filename'];
			        }
		    	}
		    	$cnt++;
    		}
    	}
    }
    closedir($handle);
	if($files)
	{
	    sort($files);
	    $filearrtemp="";
	    $getapplicantno="
	    	<div style=\"width:100%;height:90px;overflow:hidden;\">
	    		<div style=\"width:70%;height:90px;font-size:10pt;font-weight:bold;
	    			background:#8FBC8F;color:#704370;float:left;\">
	    		&nbsp;Crew<br>
				&nbsp;<select name=\"applicantno\" style=\"width:250px;color:#704370;\"
					onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
					onchange=\"resetdata();submit();\">\n
					<option value=\"\">-Select-</option>";
	    foreach ($files as $val) 
	    {
	    	$filearr=explode("_",$val);
	    	if($filearrtemp!=$filearr[1])
	    	{
//	    		$qrygetcrewname=mysql_query("SELECT CONCAT(FNAME,', ',GNAME) AS CREWNAME 
//	    				FROM crew WHERE APPLICANTNO='".$filearr[0]."'") or die(mysql_error());
//	    		if(mysql_num_rows($qrygetcrewname)!=0)
//	    		{
//		    		$rowgetcrewname=mysql_fetch_array($qrygetcrewname);
//		    		$crewname=$rowgetcrewname["CREWNAME"];
		    		if($applicantno==$filearr[1])
		    		{
		    			$selected="selected";
		    			$getselected="yes";
		    			$crewname=$filearr[0];
		    		}
		    		else
		    			$selected="";
		    		$getapplicantno .="<option $selected value=\"{$filearr[1]}\">{$filearr[0]}</option>\n";
//	    		}
	    	}
	    	$filearrtemp=$filearr[1];
	    }
	    if($getselected=="")
			$applicantno="";
			
		if($applicantno=="")
			$applicantnoid="blank";
		else
			$applicantnoid="$applicantno";
	   $getapplicantno .="
				</select><br><br>&nbsp;
					<span title=\"click to view 201...\" style=\"cursor:pointer;\"
						onclick=\"if('$applicantno'!=''){actionajax.value='viewonboard201';createurl(1);}\">Applicant No: $applicantno</span>
				</div>
	    		<div style=\"width:30%;height:90px;text-align:center;font-size:10pt;font-weight:bold;
	    			background:#8FBC8F;color:#704370;float:left;\">
					<img src=\"idpics/$applicantnoid.JPG\" style=\"cursor:pointer;\"
						onclick=\"openWindow('filedumping.php', 'filedumping', '350px', '540px')\" 
						onmouseover=\"if('$applicantno'!=''){document.getElementById('idpicture').style.display='block';}\"
						onmouseout=\"if('$applicantno'!=''){document.getElementById('idpicture').style.display='none';}\"
						width=\"85px\" height=\"85px\">
				</div>
			</div>";
	}
	else 
		$getapplicantno="";//WALA
}

//GET LIST OF FILES IF OWNER (APPLICANTNO) IS CHOSEN
if(!empty($applicantno))
{
	$localdir1=$localdir.'/'.$applicantno;
	// GET LIST OF SCANNED FILES
	if ($handle = opendir($localdir1)) 
	{
	    /* This is the correct way to loop over the directory. */
		$cnt=0;
	    while (false !== ($file = readdir($handle))) 
	    {
	    	if ($file != "." && $file != "..") 
	    	{
		        $file_parts=pathinfo($localdir1.'/'.$file);
		        $getappno=explode("_",$file_parts['filename']);
		        if($file_parts["extension"]=="pdf")
		        {
		        	$filesappno[] = $file;
	    			$cnt++;
		        }
	    	}
	    }
	    closedir($handle);
	    if($filesappno)
	    {
	    	sort($filesappno);
	    	if(empty($placefilename))
	    	{
	    		$placefilename=$filesappno[0];
	    		$defaultimg="$localdir1/$placefilename#toolbar=0&navpanes=0";
	    		$fileposition=0;
	    	}
	    	$getimg="";
	    	foreach ($filesappno as $val) 
	    	{
	    		if(empty($getimg))
	    			$getimg=$val;
	    		else
		    		$getimg .= ",".$val;
		    }
		    $posfilename="(1/".$cnt.")";
	    }
	}
	// GET LIST OF DOCIMAGES FILES (APPLICANT NO DIRECTORY WITH EXISTING FILES) -- DOCIMAGES 
	
	//********CERTIFICATES,DOCUMENTS & LICENSES**********//
	
	$type=explode(",","C,D,L");
	for($i=0;$i<=2;$i++)
	{
		$getdirtype=$serverdir.'/'.$applicantno.'/'.$type[$i];
		if(is_dir($getdirtype)) // check if directory exists
		{
			if ($handledump = opendir($getdirtype)) 
			{
			    /* This is the correct way to loop over the directory. */
				unset($filesappnodump);
			    while (false !== ($filedump = readdir($handledump))) 
			    {
			    	if ($filedump != "." && $filedump != "..") 
			    	{
				        	$filesappnodump[] = $filedump;
			    	}
			    }
			    closedir($handledump);
			    if($filesappnodump)
			    {
			    	$getimgdump="";
			    	unset($documentname);
			    	foreach ($filesappnodump as $valdump) 
			    	{
				    	$file_partsdump=pathinfo($getdirtype.'/'.$valdump);
				    	if($file_partsdump["extension"]=="pdf")
				    	{
					    	$dumpbase=$file_partsdump["filename"];
		//			    	echo $dumpbase;
							
					    	$qrygetdocname=mysql_query("SELECT DOCUMENT FROM crewdocuments WHERE DOCCODE='$dumpbase'") or die(mysql_error());
					    	if(mysql_num_rows($qrygetdocname)!=0)
					    	{
						    	$rowgetdocname=mysql_fetch_array($qrygetdocname);
						    	$documentname[]=$rowgetdocname["DOCUMENT"]."^".$valdump;
						    	if(empty($getimgdump))
					    			$getimgdump=$dumpbase;
					    		else
						    		$getimgdump .= ",".$dumpbase;
					    	}
				    	}
				    }
				    sort($documentname); //sort after getting document name
				    if($i==0)
				    {
				    	$getlistcert="";
				    	$cntlistcert=0;
					   	foreach ($documentname as $docname) 
				    	{
				    		if($docname)
				    		{
				    			$getvaluedump=explode("^",$docname);
				    			$getvaluename=addslashes($getvaluedump[0]);
				    			$getlistcert.= "
									<tr style=\"border-top: 1px solid gray;\" $mouseovereffect 
										ondblclick=\"\">
										<td style=\"text-align:left;vertical-align:middle;cursor:pointer;\"
											onclick=\"doctypefolder.value='C';docfile.value='{$getvaluedump[1]}';document.getElementById('zoomfile').innerHTML='{$getvaluename}';
												document.getElementById('dumpfile').src='$serverdir/$applicantno/C/{$getvaluedump[1]}' 
												+ '#toolbar=0&navpanes=0';\">
											&nbsp;$getvaluename
										</td>
										<td style=\"text-align:right;vertical-align:middle;\">&nbsp;X</td>
									</tr>";
				    		}
				    	}
					    $poslistcert="(1/".$cntlistcert.")";
				    }
				    
				    if($i==1)
				    {
				    	$getlistdoc="";
				    	$cntlistdoc=0;
					   	foreach ($documentname as $docname) 
				    	{
				    		if($docname)
				    		{
				    			$getvaluedump=explode("^",$docname);
				    			$getvaluename=addslashes($getvaluedump[0]);
				    			$getlistdoc.= "
									<tr style=\"border-top: 1px solid gray;\" $mouseovereffect 
										ondblclick=\"\">
										<td style=\"text-align:left;vertical-align:middle;cursor:pointer;\"
											onclick=\"doctypefolder.value='D';docfile.value='{$getvaluedump[1]}';document.getElementById('zoomfile').innerHTML='{$getvaluename}';
												document.getElementById('dumpfile').src='$serverdir/$applicantno/D/{$getvaluedump[1]}' 
												+ '#toolbar=0&navpanes=0';\">
											&nbsp;$getvaluename
										</td>
										<td style=\"text-align:right;vertical-align:middle;\">&nbsp;X</td>
									</tr>";
				    		}
				    	}
					    $poslistdoc="(1/".$cntlistdoc.")";
				    }
				    if($i==2)
				    {
				    	$getlistlic="";
				    	$cntlistlic=0;
					   	foreach ($documentname as $docname) 
				    	{
				    		if($docname)
				    		{
				    			$getvaluedump=explode("^",$docname);
				    			$getvaluename=addslashes($getvaluedump[0]);
				    			$getlistlic.= "
									<tr style=\"border-top: 1px solid gray;\" $mouseovereffect 
										ondblclick=\"\">
										<td style=\"text-align:left;vertical-align:middle;cursor:pointer;\"
											onclick=\"doctypefolder.value='L';docfile.value='{$getvaluedump[1]}';document.getElementById('zoomfile').innerHTML='{$getvaluename}';
												document.getElementById('dumpfile').src='$serverdir/$applicantno/L/{$getvaluedump[1]}' 
												+ '#toolbar=0&navpanes=0';\">
											&nbsp;$getvaluename
										</td>
										<td style=\"text-align:right;vertical-align:middle;\">&nbsp;X</td>
									</tr>";
				    		}
				    	}
					    $poslistlic="(1/".$cntlistlic.")";
				    }
			    }
			}
		}
	}
}
else 
{
	$posfilename="";
	$posfilenamedump="";
	$disablenext="disabled=\"disabled\"";
}

if($cnt<=1)
{
	$disablenext="disabled=\"disabled\"";
}
//$placefilename="111";
echo "<html>
<title>File Manipulation Clean-up</title>
<head>
<script type='text/javascript' src='../veritas/veripro.js'></script>
<script type='text/javascript' src='../veritas/filemanipulationajax.js'></script>
<script type='text/javascript' src='../veritas/ajax.js'></script>
<script>
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
function chkloading(getactionajax,resetno)
{
	if(document.getElementById('ajaxprogress').style.display=='none') // DO NOT RUN IF AJAX IS BUSY
	{
//		if(getactionajax=='view201')
//			document.getElementById('crewdetails').style.display='block';
//		else
//			document.getElementById('crewdetails').style.display='none';
			
		with(document.filemanipulationcleanup)
		{
			actionajax.value=getactionajax;
		}
		createurl();
		resetdata(resetno);
	}
}
var fillurl;
var url;
function createurl(x) // 
{
	if(x==1)
		url = \"http://$getserveraddr/veritas/crewchangeplanrequest.php?actionajax=\"; // The server-side script 
	else
		url = \"http://$getserveraddr/veritas/filemanipulationcleanuprequest.php?actionajax=\"; // The server-side script 
	
	var actionajax = document.filemanipulationcleanup.actionajax.value; 
		
	//fill URL
	if(document.filemanipulationcleanup.doctype)
		var doctype = '&doctype=' + document.filemanipulationcleanup.doctype.value;
	else
		var doctype = '&doctype=';
	var applicantno = '&embapplicantnohidden=' + document.filemanipulationcleanup.applicantno.value;
	fillurl = actionajax + doctype + applicantno;
//	alert(fillurl);
	getvalues();
}
function prevnext(x)
{
	var prevnextfile = document.filemanipulationcleanup.filestorage.value.split(',');
	if(x==0)
	{
		var getposition = document.filemanipulationcleanup.fileposition.value*1 - 1;
	}
	else
	{
		var getposition = document.filemanipulationcleanup.fileposition.value*1 + 1;
	}
	document.getElementById('scannedfile').src='$localdir/$applicantno/' + prevnextfile[getposition] + '#toolbar=0&navpanes=0';
	document.filemanipulationcleanup.fileposition.value = getposition;
	var getpage=getposition+1;
	document.filemanipulationcleanup.placefilename.value=prevnextfile[getposition];
	document.filemanipulationcleanup.posfilename.value='(' + getpage + '/' + $cnt + ')';
	
	document.filemanipulationcleanup.btnprev.disabled=false;
	document.filemanipulationcleanup.btnnext.disabled=false;
	if(getpage==1)
		document.filemanipulationcleanup.btnprev.disabled=true;
	if(getpage==$cnt)
		document.filemanipulationcleanup.btnnext.disabled=true;
}
function resetdata(x)
{
	with(document.filemanipulationcleanup)
	{
//		placefilename.value='';
//		doccodelist.value='';
//		doccodeinput.value='';
	}
}
function viewpdf(x,loc) //loc - 1 for local, 2 for server, 3 for idpics
{
	var location;
	if(loc==1)
		location='$localdir/$applicantno/';
	if(loc==2)
		location='$serverdir/$applicantno/';
	if(loc==3)
		location='idpics/';
	var myObject = new Object();
    myObject.getimage = location + x + '#toolbar=0&navpanes=0';
    window.showModalDialog('viewpdf.php', myObject, 'dialogHeight:750px; dialogWidth:700px;status=no'); 
}
function chkrename()
{
	if(document.filemanipulationcleanup.doccodelist.value)
	{
		var chkfilearray = document.filemanipulationcleanup.filestoragedump.value.split(',');
		var getdoccode = document.filemanipulationcleanup.doccodelist.value.split('*');
		var rem;
		var gorename=1;
		for(var i=0;i<chkfilearray.length;i++)
		{
			if(getdoccode[1]==chkfilearray[i])
				rem=getdoccode[0]+' already exists. Overwrite?';
		}
		if(rem)
		{
			if(confirm(rem))
				gorename=1;
			else
				gorename=0;
		}
		if(gorename==1)
		{
			document.filemanipulationcleanup.actiontxt.value='renamefile';
	//		alert('Ayos!');
			document.filemanipulationcleanup.submit();
		}
	}
	else
	{
		alert('Choose a document first!');
	}
}
function senddump()
{
	if(document.getElementById('dumpfile').src!='')
	{
		if(confirm('Are you sure you want to send this file back?'))
		{
			document.filemanipulationcleanup.actiontxt.value='dumpfile';
			document.filemanipulationcleanup.submit();
		}
	}
	else
		alert('Choose document to dump');
}
function chkdelete()
{
	if(document.filemanipulationcleanup.placefilename.value)
	{
		if(confirm('Are you sure you want to delete this file?'))
		{
			document.filemanipulationcleanup.actiontxt.value='deletefile';
			document.filemanipulationcleanup.submit();
		}
	}
}
function chkdeleteimage() //for renamed images
{
	if(document.getElementById('dumpfile').src!='')
	{
		if(confirm('Are you sure you want to delete this file?'))
		{
			document.filemanipulationcleanup.actiontxt.value='deleteimage';
			document.filemanipulationcleanup.submit();
		}
	}
	else
	{
		alert('No image to delete!');
	}
}
</script>
</head>
<script type='text/javascript' src='../veritas/veripro.js'></script>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<body style=\"overflow:hidden;\">
<form name=\"filemanipulationcleanup\" method=\"POST\">\n

<span class=\"wintitle\">FILE MANIPULATION (CLEAN-UP)</span>
<div style=\"width:380px;height:600px;background:gray;float:left;\">";
echo $getapplicantno;

echo "
<center>
<br>
<span style=\"height:25px;width:300px;background:blue;\">
	<input type=\"button\" disabled=\"disabled\" style=\"float:left;\" name=\"btnprev\" value=\"<<\" onclick=\"prevnext(0)\">
	<input type=\"text\" name=\"placefilename\" id=\"placefilename\" readonly=\"readonly\"
		style=\"height:25px;width:210px;background:blue;float:left;border:0;text-align:center;font-size:12pt;font-weight:bold;cursor:pointer;\" 
		title=\"click to enlarge image...\" onclick=\"viewpdf(this.value,1)\" value=\"$placefilename\">
	<input type=\"text\" name=\"posfilename\" readonly=\"readonly\"
		style=\"height:25px;width:40px;background:blue;border:0;float:left;text-align:center;font-size:12pt;font-weight:bold;\" 
		value=\"$posfilename\">
	<input type=\"button\" $disablenext style=\"float:right;\" name=\"btnnext\" value=\">>\" onclick=\"prevnext(1)\">
</span>

<iframe id=\"scannedfile\" src=\"$defaultimg\" height=\"380\" width=\"300\"></iframe>
<div id=\"imgrename\">
	
</div>
</center>
</div>
<div style=\"width:640px;height:600px;background:black;float:left;overflow:hidden;\">
	<div style=\"width:610px;height:50px;background:black;float:right;\">
		<table style=\"color:Red;\" width=\"90%\">
			<tr>
				<td><b>Search Crew Code</b></td>
				<td>
					<input type=\"text\" name=\"crewcode\" />
					<input type=\"button\" value=\"Search\" onclick=\"searchkey.value=crewcode.value;actiontxt.value='search';submit();\" />
				</td>
				<td align=\"center\">
					<b>$searchappno</b>
				</td>
			</tr>
		</table>
	</div>
	<div id=\"dumpfilelist\" style=\"width:320px;height:550px;background:black;float:left;text-align:center;\">
		<!--
		<span style=\"font-weight:bold;color:yellow;width:300px;\">DOCUMENT NAME ($cntdump)</span>
		<select name=\"dumpfileselect\" style=\"font-size:8pt;width:290px;\" size=\"30\"
			onchange=\"alert(this.value);document.getElementById('zoomfile').innerHTML=this.value;document.getElementById('dumpfile').src='$serverdir/$applicantno/' + this.value + '#toolbar=0&navpanes=0';\">
			$getdumplist
		</select>-->
		<span style=\"font-weight:bold;color:yellow;width:300px;\" class=\"listcol\">CERTIFICATES</span>
		<div style=\"width:320px;height:200px;background:silver;overflow:auto;\">
			<table id=\"certimage\" class=\"listcol\" cellspacing=\"1\" cellpadding=\"0\">
				$getlistcert
			</table>
		</div>
		<span style=\"font-weight:bold;color:yellow;width:300px;\" class=\"listcol\">DOCUMENTS</span>
		<div style=\"width:320px;height:150px;background:silver;overflow:auto;\">
			<table id=\"certimage\" class=\"listcol\" cellspacing=\"1\" cellpadding=\"0\">
				$getlistdoc
			</table>
		</div>
		<span style=\"font-weight:bold;color:yellow;width:300px;\" class=\"listcol\">LICENSE</span>
		<div style=\"width:320px;height:150px;background:silver;overflow:auto;\">
			<table id=\"certimage\" class=\"listcol\" cellspacing=\"1\" cellpadding=\"0\">
				$getlistlic
			</table>
		</div>
	</div>
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
</div>
<div id=\"idpicture\" 
	style=\"background:white;z-index:200;position:absolute;left:400px;top:50px;width:180px;height:180px;
		border:3px solid black;display:none;\">
	<center><img src=\"idpics/$applicantno.JPG\"></center>
</div>";
include("veritas/include/ajaxprogress.inc");

echo "
<input type=\"hidden\" size=\"40\" name=\"filestorage\" value=\"$filestorage\">
<input type=\"hidden\" name=\"fileposition\" value=\"$fileposition\">
<input type=\"hidden\" size=\"40\" name=\"filestoragedump\" value=\"$filestoragedump\">
<input type=\"hidden\" name=\"doctypefolder\" value=\"$doctypefolder\">
<input type=\"hidden\" name=\"docfile\" value=\"$docfile\">
<input type=\"hidden\" name=\"crewname\" value=\"$crewname\">
<input type=\"hidden\" name=\"actionajax\">
<input type=\"hidden\" name=\"actiontxt\">
<script>
	document.filemanipulationcleanup.filestorage.value='$getimg';
	document.filemanipulationcleanup.filestoragedump.value='$getimgdump';
	chkloading('doctype',10);
</script>

	<input type=\"hidden\" name=\"searchkey\" />

</form>
</body>
</html>";

?>