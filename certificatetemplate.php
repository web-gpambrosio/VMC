<?php
$kups="gino";
include("veritas/connectdb.php");
include("veritas/include/functions.inc");

$currentdate = date("m/d/Y");
$basedirid = "idpics/";
$basedirdocs = "docimages/";

$crewname=$_GET["name"];
$rankcode=$_GET["rankcode"];
$certtype=$_GET["certtype"];
$applicantno=$_GET["applicantno"];

//get different rank names
$qryranknames=mysql_query("SELECT RANK,RANKFULL,ALIAS1,ALIAS2 FROM rank WHERE RANKCODE='$rankcode'") or die(mysql_error());
$rowranknames=mysql_fetch_array($qryranknames);
$rank=$rowranknames["RANK"];
$alias1=$rowranknames["ALIAS1"];
$alias2=$rowranknames["ALIAS2"];
$rankfull=$rowranknames["RANKFULL"];

//$crewname="AJEIRKDLWOJW KCMZAPQOELSK OWE";
//$crewname="AJEIRKDLWOJW9JWO0";

$cntname=strlen($crewname);
$cntname1=$cntname;

//initialize margins and borders
$px=96; //pixel conversion

$qrytrainingcourse=mysql_query("SELECT DISTINCT TRAINING FROM trainingcourses") or die(mysql_error());

$qrysignatory=mysql_query("SELECT CONCAT(e.CERTRANK,' ',GNAME,' ',LEFT(MNAME,1),'.',' ',FNAME) AS NAME,d.DESIGNATION
	FROM employee e
	LEFT JOIN designation d ON e.DESIGNATIONCODE=d.DESIGNATIONCODE
	WHERE e.CERTSIGN=1
	ORDER BY GNAME,FNAME,MNAME") or die(mysql_error());
$signlist="";
$cntdata=-1;

//$crewname=ucwords(strtolower($crewname));

while($rowsignatory=mysql_fetch_array($qrysignatory))
{
	$signlist.=ucwords(strtolower($rowsignatory["NAME"])).",".$rowsignatory["DESIGNATION"]."^";
	$signlist1.=ucwords(strtolower($rowsignatory["NAME"])).",";
	$cntdata++;
}
$signlistarr1=explode(",",$signlist1);


$qrytrainmodules = mysql_query("SELECT tm.TRAINCODE,t.TRAINING,tm.MODULEID,tm.MODULE
						FROM traincoursemodules tm
						LEFT JOIN trainingcourses t ON t.TRAINCODE=tm.TRAINCODE
						ORDER BY tm.TRAINCODE,tm.MODULEID
					") or die(mysql_error());
			
$modlist = "";
$modcnt = 0;
while ($rowtrainmodules = mysql_fetch_array($qrytrainmodules))
{
	$modlist .= $rowtrainmodules["TRAINING"] . "," . $rowtrainmodules["MODULEID"] . "," . $rowtrainmodules["MODULE"] . "^";
	$modcnt++;
}



echo "
<html>\n
<head>\n
<title>$title</title>

<link rel=\"stylesheet\" type=\"text/css\" href=\"veripro.css\">
<script language=\"javascript\" src=\"veripro.js\"></script>
<script language=\"javascript\" src=\"popcalendar.js\"></script>
<style type='text/css'>
@media print 
{
	#ScreenOut { display: none; }
	#PrintOut { display: block; }
}
@media screen 
{
	#PrintOut { display: none; }
	#ScreenOut { display: block; }
}
</style>

<script>
getarray='$signlist'.split('^');
gettrainarray='$modlist'.split('^');
traincnt = $modcnt;

function placetopics(training)
{
	var topic_content='';
	var topic_content_print='<ul>';
	
	// topic_content = '';
	// topic_content1 = '';
	
	for(y=0;y<gettrainarray.length;y++)
	{
		gettrainrow = gettrainarray[y].split(',');
		if (gettrainrow[0] == training)
		{
			// alert(gettrainrow[2]);
			topic_content += gettrainrow[2] + '\\n';
			topic_content_print += '<li>' + gettrainrow[2] + '</li>\\n';
		}
	}
	
	topic_content_print += '</ul>';
	document.getElementById('topics').value=topic_content;
	document.getElementById('topics1').innerHTML=topic_content_print;
}


function placetopics_edit(val)
{
	var topic_content_print='<ul>';
	
	valarray = val.split('\\n');
	
	for(y=0;y<valarray.length;y++)
	{
		if (valarray[y] != '' && valarray[y] != null)
			topic_content_print += '<li>' + valarray[y] + '</li>';
	}
	
	topic_content_print += '</ul>';
	document.getElementById('topics1').innerHTML=topic_content_print;
}

getMonth=',January,February,March,April,May,June,July,August,September,October,November,December'.split(',');
var dateshow;
function placedesignation(sign,designation,datacount)
{
	if(datacount!='')
	{
		getdesigarray=getarray[datacount].split(',');
		document.getElementById(sign).value=getdesigarray[0];
		document.getElementById(designation).value=getdesigarray[1];
	}
	else
	{
		document.getElementById(sign).value='';
		document.getElementById(designation).value='';
	}
}
function placedate(datename,val,datetype)
{
	getMo=val.split('/');
	getNo=getMo[0]*1;
	getDay='';
	if(datetype==1)
	{
		if(getMo[1]*1==1)
			getDay='st';
		else if(getMo[1]*1==2)
			getDay='nd';
		else if(getMo[1]*1==3)
			getDay='rd';
		else
			getDay='th';
		getDay += ' day of';
	}
	dateshow = getMo[1] + getDay + ' ' + getMonth[getNo] + ' ' + getMo[2];
	document.getElementById(datename).innerHTML=dateshow;
}
</script>

</head>
<body style=\"overflow:auto;\">\n

<form name=\"certificatetemplate\" method=\"POST\">\n";


switch($certtype)
{
	case "attendance2":
		$borderwidth=7.625*$px;
		$borderheight=5.25*$px;
		$margin=12;
		
		$borderwidthpx=$borderwidth."px";
		$borderheightpx=$borderheight."px";
		
		$borderwidthpx2=$borderwidth-($margin*2)."px";
		$borderheightpx2=$borderheight-($margin*2)."px";
		
		//adjust font size for name if long
		$fontsize="24pt";
		if($cntname<20)
			$cntname = $cntname*1.4;
		else if($cntname<30)
		{
			$cntname = $cntname*1.57;
			if($cntname1>25)
				$fontsize="22pt";
		}
		else if($cntname<40)
		{
			$cntname = $cntname*1.65;
			$fontsize="18pt";
		}
		else
		{
			$cntname = $cntname*1.7;
			$fontsize="18pt";
			if($cntname1>45)
			{
				$cntname=$cntname1*1.6;
				$fontsize="14pt";
			}
		}
		
		
			echo "
		<div style=\"width:$borderwidthpx;height:$borderheightpx;border:1px Solid #003366;position:absolute;\">
			<div style=\"margin:$margin $margin $margin $margin;width:$borderwidthpx2;height:$borderheightpx2;border:5px Solid #003366;position:absolute;\">
		
				<div style=\"width:100%;height:15px;margin:10 10 0 10;\">
					<span style=\"font-size:7pt;font-family:OCR A Extended;float:left;\">VMC Form  <i>PROVISIONAL</i></span>
					<span id=\"ScreenOut\" style=\"font-size:10pt;float:left;\">&nbsp;&nbsp;
						<input type=\"button\" name=\"btnPrint\" value=\"Print\" onclick=\"window.print();\" />
					</span>
					<span style=\"font-size:8pt;font-family:Gill Sans MT Condensed;float:right;\">Cert. No.  BMSA/ISPS09-219</span>
				</div>
				
				<center>
					<span style=\"font-size:16pt;font-weight:Bold;font-family:Arial;\">CERTIFICATE OF ATTENDANCE</span> <br /><br />
					<span style=\"font-size:12pt;font-weight:Bold;font-family:Arial;\">This certificate is issued to</span> <br /><br />
					<!--
					<span style=\"font-size:$fontsize;font-weight:Bold;font-family:Monotype Corsiva;\">
						<input type=\"text\" style=\"font-size:$fontsize;font-weight:Bold;font-family:Monotype Corsiva;text-align:right;text-decoration:underline;border:0;\" 
								value=\"$alias2\" size=\"6\">
						<input type=\"text\" readonly=\"readonly\" style=\"font-size:$fontsize;font-weight:Bold;font-family:Monotype Corsiva;text-align:left;text-decoration:underline;border:0;\" 
							value=\"$crewname\" size=\"$cntname\">
					</span> 
					-->
					<span id=\"ScreenOut\" style=\"font-size:$fontsize;font-weight:Bold;font-family:Monotype Corsiva;\">
						<input type=\"text\" name=\"crewrank\" style=\"font-size:$fontsize;font-weight:Bold;font-family:Lucida Sans Unicode;text-align:right;text-decoration:underline;border:0;\" 
								value=\"$alias2\" size=\"6\" onblur=\"document.getElementById('idcrew').value=this.value+' $crewname';\">
						<input type=\"text\" name=\"crewname\" readonly=\"readonly\" style=\"font-size:$fontsize;font-weight:Bold;font-family:Lucida Sans Unicode;text-align:left;text-decoration:underline;border:0;\" 
							value=\"$crewname\" size=\"$cntname1\">
					</span> 
					<span id=\"PrintOut\">
						<input type=\"text\" name=\"idcrew\" value=\"$alias2 $crewname\" size=\"100%\" 
							style=\"font-size:$fontsize;font-weight:Bold;font-family:Lucida Sans Unicode;width:100%;border:0;text-decoration:underline;text-align:center;\">
					</span> 
					
					<br />
					<span style=\"font-size:12pt;font-family:Times New Roman;\"><i>For having successfully completed the seminar on</i></span> <br /><br />
					<span style=\"font-size:20pt;font-weight:Bold;font-family:Monotype Corsiva;\" id=\"ScreenOut\">
						<select name=\"courseselect\" onchange=\"course.value=this.value;courseprint.value=this.value;\">
							<option value=\"\">--Select--</option>";
							while($rowtrainingcourse=mysql_fetch_array($qrytrainingcourse))
							{
								$course=$rowtrainingcourse["TRAINING"];
								echo "<option value=\"$course\">$course</option>";
							}
					echo "
						</select>
						<input type=\"text\" name=\"course\" style=\"font-size:12pt;font-weight:Bold;font-family:Monotype Corsiva;text-align:center;\" 
							onkeyup=\"courseprint.value=this.value;\" size=\"80\">
					</span> 
					<span id=\"PrintOut\">
						<input type=\"text\" name=\"courseprint\" style=\"width:100%;font-size:20pt;font-weight:Bold;font-family:Monotype Corsiva;text-align:center;border:0;\">
						<br /><br />
					</span> 
					<span id=\"ScreenOut\" style=\"font-size:12pt;font-family:Times New Roman;\">
						<i>Conducted </i>
						<input type=\"text\" size=\"50\" onblur=\"document.getElementById('idbdate').innerHTML=this.value;\">
						<i> in accordance with</i>
					</span> 
					<span id=\"PrintOut\" style=\"font-size:12pt;font-family:Times New Roman;\">
						<i>Conducted </i>
						<u><span style=\"font-size:12pt;font-family:Times New Roman;\" id=\"idbdate\"></span></u>
						<i>&nbsp;in accordance with</i>
					</span>
					<span style=\"font-size:12pt;font-family:Times New Roman;\"><i>Safety of Life at Sea 1974 Convention, as amended and the</i></span> <br />
					<span style=\"font-size:12pt;font-family:Times New Roman;\"><i>International Ship and Port Facility Security (ISPS) Code.</i></span> <br /><br />
					<span id=\"ScreenOut\" style=\"font-size:12pt;font-family:Times New Roman;\">
						<i>Issued this</i>
						<input type=\"text\" size=\"20\" onblur=\"document.getElementById('idissueddate').innerHTML=this.value;\">
						<i>&nbsp;in the City of Manila, Philippines.</i>
					</span> 
					<span id=\"PrintOut\" style=\"font-size:12pt;font-family:Times New Roman;\">
						<i>Issued this</i>
						<u><span id=\"idissueddate\"></span></u>
						<i>&nbsp;in the City of Manila, Philippines.</i>
					</span> 
					<br />
				</center><br><br>
				<div style=\"width:100%;height:15px;\" id=\"ScreenOut\">
					<select name=\"signatory1\" style=\"font-size:13pt;font-family:Times New Roman;float:left;width:300px;\" 
						onchange=\"placedesignation('sign1','designation1',this.value);\">
						<option value=\"\">--Select--</option>";
						for($i=0;$i<$cntdata;$i++)
						{
							$name1=$signlistarr1[$i];
							echo "<option value=\"$i\">$name1</option>";
						}
				echo "
					</select>
					<select name=\"signatory2\" style=\"font-size:13pt;font-family:Times New Roman;float:right;width:300px;\" 
						onchange=\"placedesignation('sign2','designation2',this.value);\">
						<option value=\"\">--Select--</option>";
						for($i=0;$i<$cntdata;$i++)
						{
							$name2=$signlistarr1[$i];
							echo "<option value=\"$i\">$name2</option>";
						}
				echo "
					</select>
				</div>
				<span style=\"width:100%;\" id=\"PrintOut\">
					<br><br>
					<input type=\"text\" id=\"sign1\" style=\"font-size:10pt;font-family:Times New Roman;float:left;width:300px;text-align:center;border:0;\">
					<input type=\"text\" id=\"sign2\" style=\"font-size:10pt;font-family:Times New Roman;float:right;width:300px;text-align:center;border:0;\">
				</span>
				<div style=\"width:100%;>
					<input type=\"text\" id=\"designation0\" style=\"font-size:10pt;font-family:Agency FB;float:left;width:300px;text-align:center;border:0;\">
					<input type=\"text\" id=\"designation1\" style=\"font-size:10pt;font-family:Agency FB;float:left;width:300px;text-align:center;border:0;\">
					<input type=\"text\" id=\"designation2\" style=\"font-size:10pt;font-family:Agency FB;float:right;width:300px;text-align:center;border:0;\">
				</div>
			</div>
		</div>";
	break;
	case "promotion1":
		$borderwidth=7.25*$px;
		$borderheight=5.125*$px;
		$margin=12;
		
		$borderwidthpx=$borderwidth."px";
		$borderheightpx=$borderheight."px";
		
		$borderwidthpx2=$borderwidth-($margin*2)."px";
		$borderheightpx2=$borderheight-($margin*2)."px";
		
		//adjust font size for name if long
		$fontsize="22pt";
		if($cntname1>25)
			$fontsize="20pt";
		else if($cntname1>45)
			$fontsize="16pt";
		
		//adjust name to first letter uppercase
		$crewname=ucwords(strtolower($crewname));
		
		echo "
		<div style=\"width:$borderwidthpx;height:$borderheightpx;position:absolute;border:1pt Solid Black;\">
			<div style=\"margin:$margin $margin $margin $margin;width:$borderwidthpx2;height:$borderheightpx2;position:absolute;border:4pt Solid Black;\">
				<div style=\"margin:5 5 0 5;width:100%;height:15px;\">
					<span style=\"font-size:6pt;font-family:OCR A Extended;font-weight:normal;float:left;\">VMC Form 233 - Revised 1 Sep 2000</span>
					<span id=\"ScreenOut\" style=\"font-size:8pt;float:left;\">&nbsp;&nbsp;
						<input type=\"button\"style=\"font-size:8pt;\" name=\"btnPrint\" value=\"Print\" onclick=\"window.print();\" />
					</span>
					<span style=\"font-size:6pt;font-family:Gill Sans MT Condensed;font-weight:normal;float:right;\">CP No.  2009-043</span>
				</div>
				<center>
					<span style=\"font-size:16pt;font-family:Copperplate Gothic Bold;font-weight:normal;\">VERITAS MARITIME CORPORATION $cntname1</span><br>
					<span style=\"font-size:26pt;font-family:Old English Text MT;font-weight:bold;\">Certificate of Promotion</span><br><br>
					<span>
						<font style=\"font-size:13pt;font-family:Old English Text MT;font-weight:normal;\">
							BE IT KNOWN TO ALL that 
						</font>
						<font style=\"font-size:$fontsize;font-family:Old English Text MT;font-weight:normal;\">
							$crewname
						</font>
						<font style=\"font-size:13pt;font-family:Old English Text MT;font-weight:normal;\">
							has
						</font>
					</span><br>
					<span style=\"font-size:13pt;font-family:Old English Text MT;font-weight:normal;\">
						satisfactorily met the standard requirements under the policies and regulations of the Company
					</span><br>
					<span style=\"font-size:13pt;font-family:Old English Text MT;font-weight:normal;\">
						and is hereby awarded this certificate as a qualified seafarer with the rank of
					</span><br>
					<div style=\"margin:5 5 5 5;\">
						<span style=\"font-size:18pt;font-family:Old English Text MT;font-weight:bold;\">
							<input type=\"text\" value=\"$rankfull\"
								style=\"font-size:18pt;font-weight:Bold;font-family:Old English Text MT;text-align:center;width:100%;border:0;\">
						</span>
					</div>
					
					<!--
					<span id=\"ScreenOut\">
						<font style=\"font-size:13pt;font-family:Old English Text MT;font-weight:normal;\">
							ISSUED at Manila, Philippines this 
						</font>
						<font style=\"font-size:13pt;font-family:Old English Text MT;font-weight:bold;\">
							<input type=\"text\" title=\"mm/dd/yyyy\" name=\"issueddate\" onKeyPress=\"return dateonly(this);\" size=\"8\" 
								maxlength=\"10\" value=\"$currentdate\" onkeydown=\"chkCR();\" onblur=\"chkdate(this);placedate('idissueddate',this.value,1)\">.
						</font>
					</span>
					<span id=\"PrintOut\">
						<font style=\"font-size:13pt;font-family:Old English Text MT;font-weight:normal;\">
							ISSUED at Manila, Philippines this 
						</font>
						<span style=\"font-size:13pt;font-family:Old English Text MT;font-weight:bold;\" id=\"idissueddate\"></span>
					</span>
					-->
					
					<span id=\"ScreenOut\" style=\"font-size:13pt;font-family:Old English Text MT;font-weight:normal;\">
						<font style=\"font-size:13pt;font-family:Old English Text MT;font-weight:normal;\">
							ISSUED at Manila, Philippines this 
						</font>
						<font style=\"font-size:13pt;font-family:Old English Text MT;font-weight:bold;\">
							<input type=\"text\" size=\"20\" onblur=\"document.getElementById('idissueddate').innerHTML=this.value;\">
						</font>
					</span> 
					<span id=\"PrintOut\" style=\"font-size:12pt;font-family:Times New Roman;\">
						<font style=\"font-size:13pt;font-family:Old English Text MT;font-weight:normal;\">
							ISSUED at Manila, Philippines this 
						</font>
						<font style=\"font-size:13pt;font-family:Old English Text MT;font-weight:bold;\">
							<u><span id=\"idissueddate\"></span></u>
						</font>
					</span> 
					
					
					<span style=\"font-size:13pt;font-family:Old English Text MT;font-weight:normal;float:left;\">
						&nbsp;Attested.
					</span><br><br>
					<span id=\"ScreenOut\">
						<select name=\"signatory1\" style=\"font-size:12pt;font-family:Arial;border:0;\" 
							onchange=\"placedesignation('sign1','designation1',this.value);\">
							<option value=\"\">--Select--</option>";
							for($i=0;$i<$cntdata;$i++)
							{
								$name1=$signlistarr1[$i];
								echo "<option value=\"$i\">$name1</option>";
							}
						echo "
						</select>
					</span>
					<span style=\"width:100%;\" id=\"PrintOut\">
						<input type=\"text\" id=\"sign1\" style=\"font-size:14pt;font-family:Old English Text MT;width:300px;text-align:center;border:0;\">
					</span>
					<span style=\"font-size:10pt;font-family:Gill Sans MT Condensed;font-weight:bold;\">
						<input type=\"text\" readonly=\"readonly\" id=\"designation1\" style=\"font-size:10pt;font-family:Agency FB;float:left;width:100%;text-align:center;border:0;\">
					</span><br>
					<span id=\"ScreenOut\">
						<select name=\"signatory2\" style=\"font-size:12pt;font-family:Arial;border:0;\" 
							onchange=\"placedesignation('sign2','designation2',this.value);\">
							<option value=\"\">--Select--</option>";
							for($i=0;$i<$cntdata;$i++)
							{
								$name1=$signlistarr1[$i];
								echo "<option value=\"$i\">$name1</option>";
							}
						echo "
						</select>
					</span>
					<span style=\"width:100%;\" id=\"PrintOut\">
						<input type=\"text\" id=\"sign2\" style=\"font-size:14pt;font-family:Old English Text MT;width:300px;text-align:center;border:0;\">
					</span>
					<span style=\"font-size:10pt;font-family:Gill Sans MT Condensed;font-weight:bold;\">
						<input type=\"text\" readonly=\"readonly\" id=\"designation2\" style=\"font-size:10pt;font-family:Agency FB;float:left;width:100%;text-align:center;border:0;\">
					</span><br>
				</center>
				<div style=\"width:100%;\" id=\"ScreenOut\">
					<span>
						<select name=\"signatory3\" style=\"font-size:12pt;font-family:Arial;width:300px;float:left;border:0;\" 
							onchange=\"placedesignation('sign3','designation3',this.value);\">
							<option value=\"\">--Select--</option>";
							for($i=0;$i<$cntdata;$i++)
							{
								$name1=$signlistarr1[$i];
								echo "<option value=\"$i\">$name1</option>";
							}
						echo "
						</select>
					</span>
					<span>
						<select name=\"signatory4\" style=\"font-size:12pt;font-family:Arial;width:300px;float:right;border:0;\" 
							onchange=\"placedesignation('sign4','designation4',this.value);\">
							<option value=\"\">--Select--</option>";
							for($i=0;$i<$cntdata;$i++)
							{
								$name1=$signlistarr1[$i];
								echo "<option value=\"$i\">$name1</option>";
							}
						echo "
						</select>
					</span>
				</div>
				<div style=\"width:100%;\" id=\"PrintOut\">
					<input type=\"text\" id=\"sign3\" style=\"font-size:14pt;font-family:Old English Text MT;width:320px;text-align:center;float:left;border:0;\">
					<input type=\"text\" id=\"sign4\" style=\"font-size:14pt;font-family:Old English Text MT;width:320px;text-align:center;float:right;border:0;\">
				</div>
				<div style=\"width:100%;\">
					<input type=\"text\" readonly=\"readonly\" id=\"designation3\" style=\"font-size:10pt;font-family:Agency FB;float:left;width:300px;text-align:center;border:0;\">
					<input type=\"text\" readonly=\"readonly\" id=\"designation4\" style=\"font-size:10pt;font-family:Agency FB;float:right;width:300px;text-align:center;border:0;\">
				</div>
			</div>
		</div>";
	break;
	case "attendance4":
		$borderwidth=7.375*$px;
		$borderheight=4.875*$px;
		$margin=12;
		
		$borderwidthpx=$borderwidth."px";
		$borderheightpx=$borderheight."px";
		
		$borderwidthpx2=$borderwidth-($margin*2)."px";
		$borderheightpx2=$borderheight-($margin*2)."px";
		
		//adjust font size for name if long
		$fontsize="18pt";
		
		
		
			echo "
		<div style=\"width:$borderwidthpx;height:$borderheightpx;border:1px Solid #003366;position:absolute;\">
			<div style=\"margin:$margin $margin $margin $margin;width:$borderwidthpx2;height:$borderheightpx2;border:5px Solid #003366;position:absolute;\">
		
				<div style=\"width:100%;height:15px;margin:5 10 0 10;\">
					<img src=\"images/veritas_small.jpg\" height=\"79\" width=\"99\" style=\"float:left;\">
					<img src=\"images/dnv_logo_certificate.jpg\" height=\"78\" width=\"99\" style=\"float:right;\">
					<span style=\"font-size:24pt;font-weight:Bold;font-family:Century Schoolbook;width:100%;height:79px;text-align:center;valign:center;\">
						<span id=\"ScreenOut\" style=\"font-size:8pt;float:left;\">&nbsp;&nbsp;
							<input type=\"button\"style=\"font-size:8pt;\" name=\"btnPrint\" value=\"Print\" onclick=\"window.print();\" />
						</span>
						<br>
						Certificate of  Attendance
					</span>
				</div>
				<span style=\"font-size:8pt;font-family:Times New Roman;float:right;\">Cert. No. MCA-09-063&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br />
				
				<center>
					<span style=\"font-size:12pt;font-weight:Normal;font-family:Lucida Blackletter;\">This certificate is issued to</span> <br /><br />
					<span id=\"ScreenOut\" style=\"font-size:$fontsize;font-weight:Bold;font-family:Monotype Corsiva;\">
						<input type=\"text\" name=\"crewrank\" style=\"font-size:$fontsize;font-weight:Bold;font-family:Lucida Sans Unicode;text-align:right;text-decoration:underline;border:0;\" 
								value=\"$alias2\" size=\"6\" onblur=\"document.getElementById('idcrew').value=this.value+' $crewname';\">
						<input type=\"text\" name=\"crewname\" readonly=\"readonly\" style=\"font-size:$fontsize;font-weight:Bold;font-family:Lucida Sans Unicode;text-align:left;text-decoration:underline;border:0;\" 
							value=\"$crewname\" size=\"$cntname1\">
					</span> 
					<span id=\"PrintOut\">
						<input type=\"text\" name=\"idcrew\" value=\"$alias2 $crewname\" size=\"100%\" 
							style=\"font-size:$fontsize;font-weight:Bold;font-family:Lucida Sans Unicode;width:100%;border:0;text-decoration:underline;text-align:center;\">
					</span> 
					<br />
					<span style=\"font-size:12pt;font-family:Times New Roman;\"><i>For having successfully  attended  the  IN-House training familiarization for</i></span> <br />
					<span style=\"font-size:20pt;font-weight:Bold;font-family:Monotype Corsiva;\" id=\"ScreenOut\">
						<select name=\"courseselect\" onchange=\"course.value=this.value;courseprint.value=this.value;\">
							<option value=\"\">--Select--</option>";
							while($rowtrainingcourse=mysql_fetch_array($qrytrainingcourse))
							{
								$course=$rowtrainingcourse["TRAINING"];
								echo "<option value=\"$course\">$course</option>\n";
							}
					echo "
						</select>
						<input type=\"text\" name=\"course\" style=\"font-size:12pt;font-weight:Bold;font-family:Lucida Sans Unicode;text-align:center;\" 
							onkeyup=\"courseprint.value=this.value;\" size=\"60\">
					</span> 
					<span id=\"PrintOut\">
						<br />
						<input type=\"text\" name=\"courseprint\" style=\"width:100%;font-size:17pt;font-weight:Bold;font-family:Lucida Sans Unicode;text-align:center;border:0;\">
						<br />
					</span> 
					
					<!--
					<span style=\"font-size:12pt;font-family:Times New Roman;\" id=\"ScreenOut\">
						<i>Conducted on</i>
						<input type=\"text\" title=\"mm/dd/yyyy\" name=\"bdate\" onKeyPress=\"return dateonly(this);\" size=\"8\" 
							maxlength=\"10\" onkeydown=\"chkCR();\" onblur=\"chkdate(this);placedate('idbdate',this.value,1)\">
						<u> to </u>
						<input type=\"text\" title=\"mm/dd/yyyy\" name=\"edate\" onKeyPress=\"return dateonly(this);\" size=\"8\" 
							maxlength=\"10\" onkeydown=\"chkCR();\" onblur=\"chkdate(this);placedate('idedate',this.value,1)\">
						<i> at Veritas Maritime Corporation</i>
					</span> 
					<span id=\"PrintOut\" style=\"font-size:12pt;font-family:Times New Roman;\">
						<i>Conducted</i>
						<b><u><span style=\"font-size:12pt;font-family:Times New Roman;\" id=\"idbdate\"></span></u>
						<u>&nbsp;to </u>
						<u><span style=\"font-size:12pt;font-family:Times New Roman;\" id=\"idedate\"></span></u></b>
						<i>&nbsp;at Veritas Maritime Corporation</i>
					</span>
					-->
					
					
					<span id=\"ScreenOut\" style=\"font-size:12pt;font-family:Times New Roman;\">
						<i>Conducted </i>
						<input type=\"text\" size=\"50\" onblur=\"document.getElementById('idbdate').innerHTML=this.value;\">
						<i>&nbsp;at Veritas Maritime Corporation</i>
					</span> 
					<span id=\"PrintOut\" style=\"font-size:12pt;font-family:Times New Roman;\">
						<i>Conducted </i>
						<u><span id=\"idbdate\"></span></u>
						<i>&nbsp;at Veritas Maritime Corporation</i>
					</span> 
					
					
					
					<span style=\"font-size:12pt;font-family:Times New Roman;\"><i>and practicum at Maritime Academy of Asia and the Pacific (MAAP).</i></span> <br /><br />
					
					
					<!--
					<span style=\"font-size:12pt;font-family:Times New Roman;\" id=\"ScreenOut\">
						<i>Given this</i>
						<input type=\"text\" title=\"mm/dd/yyyy\" name=\"issueddate\" onKeyPress=\"return dateonly(this);\" size=\"8\" 
							maxlength=\"10\" onkeydown=\"chkCR();\" onblur=\"chkdate(this);placedate('idissueddate',this.value,1);\" value=\"$currentdate\"> 
						<i>&nbsp;in the City of Manila, Philippines.</i>
					</span> 
					<span id=\"PrintOut\">
						<i>Issued this</i>
						<b><u><i><span style=\"font-size:12pt;font-family:Times New Roman;\" id=\"idissueddate\"></span></i></u></b>
						<i>&nbsp;in the City of Manila, Philippines.</i>
					</span> 
					-->
					
					<span id=\"ScreenOut\" style=\"font-size:12pt;font-family:Times New Roman;\">
						<i>Given this </i>
						<input type=\"text\" size=\"30\" onblur=\"document.getElementById('idissueddate').innerHTML=this.value;\">
						<i>&nbsp;in the City of Manila, Philippines.</i>
					</span> 
					<span id=\"PrintOut\" style=\"font-size:12pt;font-family:Times New Roman;\">
						<i>Given this </i>
						<u><span id=\"idissueddate\"></span></u>
						<i>&nbsp;in the City of Manila, Philippines.</i>
					</span> 
					
					<br />
				</center><br>
				<div style=\"width:100%;height:15px;\" id=\"ScreenOut\">
					<select name=\"signatory1\" style=\"font-size:13pt;font-family:Times New Roman;float:left;width:300px;\" 
						onchange=\"placedesignation('sign1','designation1',this.value);\">
						<option value=\"\">--Select--</option>";
						for($i=0;$i<$cntdata;$i++)
						{
							$name1=$signlistarr1[$i];
							echo "<option value=\"$i\">$name1</option>\n";
						}
				echo "
					</select>
					<select name=\"signatory2\" style=\"font-size:13pt;font-family:Times New Roman;float:right;width:300px;\" 
						onchange=\"placedesignation('sign2','designation2',this.value);\">
						<option value=\"\">--Select--</option>";
						for($i=0;$i<$cntdata;$i++)
						{
							$name2=$signlistarr1[$i];
							echo "<option value=\"$i\">$name2</option>\n";
						}
				echo "
					</select>
				</div>
				<span style=\"width:100%;\" id=\"PrintOut\">
					<input type=\"text\" id=\"sign1\" style=\"font-size:12pt;font-family:Times New Roman;float:left;width:300px;text-align:center;border:0;\">
					<input type=\"text\" id=\"sign2\" style=\"font-size:12pt;font-family:Times New Roman;float:right;width:300px;text-align:center;border:0;\">
				</span>
				<div style=\"width:300px;float:left;\">
					<input type=\"text\" id=\"designation1\" style=\"font-size:10pt;font-family:Agency FB;float:left;width:300px;text-align:center;border:0;\">
					<input type=\"text\" id=\"designation2\" style=\"font-size:10pt;font-family:Agency FB;float:left;width:300px;text-align:center;border:0;display:none;\">
				</div>
				<div style=\"width:300px;float:right;\">
					<span style=\"font-size:10pt;font-weight:Normal;font-family:Agency FB;text-align:center;width:100%;\">Principal’s Representative</span>
					<span style=\"font-size:10pt;font-weight:Normal;font-family:Agency FB;text-align:center;width:100%;\">Veritas Maritime Corporation</span>
				</div>
			</div>
		</div>";
	break;
	case "completion1":
		
		$dirfilename = $basedirid . $applicantno . ".jpg";
		
		$borderwidth=7.125*$px;
		$borderheight=10.72*$px;
		$margin=12;
		
		//adjust font size for name if long
		$fontsize="25pt";
		if($cntname1>25)
			$fontsize="20pt";
		else if($cntname1>45)
			$fontsize="16pt";
			
		$borderwidthpx=$borderwidth."px";
		$borderheightpx=$borderheight."px";
		
		$borderwidthpx2=$borderwidth-($margin*2)."px";
		$borderheightpx2=$borderheight-($margin*2)."px";
		
		//adjust font size for name if long
		$fontsize="18pt";
		
		$qrydob=mysql_query("SELECT BIRTHDATE FROM crew WHERE APPLICANTNO=$applicantno") or die(mysql_error());
		$rowdob=mysql_fetch_array($qrydob);
		$birth=date("F d, Y",strtotime($rowdob["BIRTHDATE"]));
		
		
			echo "
		<div style=\"width:$borderwidthpx;height:$borderheightpx;border:1px Solid #003366;position:absolute;\">
			<div style=\"margin:$margin $margin $margin $margin;width:$borderwidthpx2;height:$borderheightpx2;border:5px Solid #003366;position:absolute;\">
		
				<div style=\"width:100%;height:15px;margin:5 10 0 10;\">
					<img src=\"images/veritas_small.jpg\" height=\"66\" width=\"84\" style=\"float:left;\">
					<img src=\"images/dnv_logo_certificate.jpg\" height=\"66\" width=\"84\" style=\"float:right;\">
					<span style=\"font-size:18pt;font-weight:Bold;font-family:Times New Roman;width:100%;height:66px;text-align:center;valign:center;color:#000080;\">
						VERITAS MARITIME CORPORATION<br>
						<font style=\"font-size:8pt;font-weight:Normal;font-family:Arial;\">
							15th Floor, MARC 2000 Tower, 1973 Taft Avenue, Malate, Manila, Philippines
						</font>
						<font style=\"font-size:8pt;font-weight:Normal;font-family:Arial;\">
							Telefax   (632) 526-1029 . (632) 338-0318 . Tlx 051-94078312 - VMCCG
						</font>
						<font style=\"font-size:8pt;font-weight:Normal;font-family:Arial;\">
							Tels. (632) 3380319 . (632) 5261034 . (632) 5243661 . (632) 5242116 . (632) 5241691
						</font>
						<font style=\"font-size:8pt;font-weight:Normal;font-family:Arial;\">
							(632) 5268041 . (632) 5362775 . (632) 5362757
						</font>
						<font style=\"font-size:8pt;font-weight:Normal;font-family:Arial;\">
							E-mail Address: vmcgroup@veritas.com.ph . veritas_mc@pacific.net.ph
						</font>
					</span>
				</div>
				<span style=\"font-size:9pt;font-family:Times New Roman;float:right;font-weight:bold;\">Certificate Number :  SSOC/0812-003&nbsp;&nbsp;&nbsp;&nbsp;</span>
				<br />
				<br />
				<br />
				<br />
				
				<center>
					<span style=\"font-size:12pt;font-weight:Normal;font-family:Times New Roman;\"><i>This</i></span> <br /><br />
					<span style=\"font-size:28pt;font-weight:Bold;font-family:Times New Roman;\">CERTIFICATE OF COMPLETION</span> <br /><br />
					<span style=\"font-size:12pt;font-weight:Normal;font-family:Times New Roman;\"><i>is issued to</i></span> <br /><br />
					<span style=\"font-size:25pt;font-weight:Bold;font-family:Monotype Corsiva;\">
						<input type=\"text\" readonly=\"readonly\" style=\"font-size:25pt;font-weight:Bold;font-family:Monotype Corsiva;
							text-align:center;width:100%;border:0;\" 
							value=\"$crewname\" size=\"$cntname1\">
					</span> 
					<span style=\"font-size:12pt;font-weight:Normal;font-family:Times New Roman;\"><i>(D.O.B. $birth)</i></span> <br /><br />
					<span style=\"font-size:12pt;font-family:Times New Roman;\"><i>for having successfully completed the training course for</i></span> <br />
					<span style=\"font-size:20pt;font-weight:Bold;font-family:Monotype Corsiva;\" id=\"ScreenOut\">
						<select name=\"courseselect\" onchange=\"course.value=this.value;courseprint.value=this.value;\">
							<option value=\"\">--Select--</option>";
							while($rowtrainingcourse=mysql_fetch_array($qrytrainingcourse))
							{
								$course=$rowtrainingcourse["TRAINING"];
								echo "<option value=\"$course\">$course</option>\n";
							}
					echo "
						</select>
						<input type=\"text\" name=\"course\" style=\"width:100%;font-size:24pt;font-weight:Bold;font-family:Times New Roman;text-align:center;\" 
							onkeyup=\"courseprint.value=this.value;\">
					</span> 
					<span id=\"PrintOut\">
						<br />
						<input type=\"text\" name=\"courseprint\" style=\"width:100%;font-size:28pt;font-weight:Bold;font-family:Times New Roman;
							text-align:center;border:0;\">
						<br />
					</span> 
					
					<!--
					<span style=\"font-size:12pt;font-family:Times New Roman;\" id=\"ScreenOut\">
						<i>Conducted on</i>
						<input type=\"text\" title=\"mm/dd/yyyy\" name=\"bdate\" onKeyPress=\"return dateonly(this);\" size=\"8\" 
							maxlength=\"10\" onkeydown=\"chkCR();\" onblur=\"chkdate(this);placedate('idbdate',this.value,1)\">
						<u> to </u>
						<input type=\"text\" title=\"mm/dd/yyyy\" name=\"edate\" onKeyPress=\"return dateonly(this);\" size=\"8\" 
							maxlength=\"10\" onkeydown=\"chkCR();\" onblur=\"chkdate(this);placedate('idedate',this.value,1)\">
						<i> as authorized by the</i>
					</span> 
					<span id=\"PrintOut\" style=\"font-size:12pt;font-family:Times New Roman;\">
						<i>Conducted on</i>
						<b><u><span style=\"font-size:12pt;font-family:Times New Roman;\" id=\"idbdate\"></span></u>
						<u>&nbsp;to </u>
						<u><span style=\"font-size:12pt;font-family:Times New Roman;\" id=\"idedate\"></span></u></b>
						<i>&nbsp;as authorized by the</i>
					</span>
					-->
					
					<span id=\"ScreenOut\" style=\"font-size:12pt;font-family:Times New Roman;\">
						<i>Conducted </i>
						<input type=\"text\" size=\"50\" onblur=\"document.getElementById('idbdate').innerHTML=this.value;\">
						<i>&nbsp;as authorized by the</i>
					</span> 
					<span id=\"PrintOut\" style=\"font-size:12pt;font-family:Times New Roman;\">
						<i>Conducted </i>
						<u><span id=\"idbdate\"></span></u>
						<i>&nbsp;as authorized by the</i>
					</span> 
					
					
					<span style=\"font-size:12pt;font-family:Times New Roman;\">
						<i>Maritime Industry Authority in conformity with Chapter XI-2 of the International</i>
					</span> <br />
					<span style=\"font-size:12pt;font-family:Times New Roman;\">
						<i>Convention for the Safety of Life at Sea (SOLAS) 1974, as amended, and the</i>
					</span> <br />
					<span style=\"font-size:12pt;font-family:Times New Roman;\">
						<i>International Ship and Port Facility Security (ISPS) Code.</i>
					</span> <br />
					
					<!--
					<span style=\"font-size:12pt;font-family:Times New Roman;\" id=\"ScreenOut\">
						<i>Issued this</i>
						<input type=\"text\" title=\"mm/dd/yyyy\" name=\"issueddate\" onKeyPress=\"return dateonly(this);\" size=\"8\" 
							maxlength=\"10\" onkeydown=\"chkCR();\" onblur=\"chkdate(this);placedate('idissueddate',this.value,1);\" value=\"$currentdate\"> 
						<i>&nbsp;in the City of Manila, Philippines.</i>
					</span> 
					<span id=\"PrintOut\">
						<i>Issued this</i>
						<b><u><i><span style=\"font-size:12pt;font-family:Times New Roman;\" id=\"idissueddate\"></span></i></u></b>
						<i>&nbsp;in the City of Manila, Philippines.</i>
					</span> 
					-->
					
					<span id=\"ScreenOut\" style=\"font-size:12pt;font-family:Times New Roman;\">
						<i>Issued this </i>
						<input type=\"text\" size=\"30\" onblur=\"document.getElementById('idissueddate').innerHTML=this.value;\">
						<i>&nbsp;in the City of Manila, Philippines.</i>
					</span> 
					<span id=\"PrintOut\" style=\"font-size:12pt;font-family:Times New Roman;\">
						<i>Issued this </i>
						<u><span id=\"idissueddate\"></span></u>
						<i>&nbsp;in the City of Manila, Philippines.</i>
					</span> 
					
					
					<br />
				</center><br><br><br><br><br><br><br><br><br><br><br>
				<!--
				<div style=\"width:100%;height:15px;\" id=\"ScreenOut\">
					<select name=\"signatory1\" style=\"font-size:10pt;font-family:Times New Roman;float:left;width:200px;\" 
						onchange=\"placedesignation('sign1','designation1',this.value);\">
						<option value=\"\">--Select--</option>";
						for($i=0;$i<$cntdata;$i++)
						{
							$name1=$signlistarr1[$i];
							echo "<option value=\"$i\">$name1</option>\n";
						}
				echo "
					</select>
					<select name=\"signatory2\" style=\"font-size:10pt;font-family:Times New Roman;float:right;width:200px;\" 
						onchange=\"placedesignation('sign2','designation2',this.value);\">
						<option value=\"\">--Select--</option>";
						for($i=0;$i<$cntdata;$i++)
						{
							$name2=$signlistarr1[$i];
							echo "<option value=\"$i\">$name2</option>\n";
						}
				echo "
					</select>
				</div>
				<span style=\"width:100%;\" id=\"PrintOut\">
					<input type=\"text\" id=\"sign1\" style=\"font-size:12pt;font-family:Times New Roman;float:left;width:300px;text-align:center;border:0;\">
					<input type=\"text\" id=\"sign2\" style=\"font-size:12pt;font-family:Times New Roman;float:right;width:300px;text-align:center;border:0;\">
				</span>
				<div style=\"width:300px;float:left;\">
					<input type=\"text\" id=\"designation1\" style=\"font-size:10pt;font-family:Agency FB;float:left;width:300px;text-align:center;border:0;\">
					<input type=\"text\" id=\"designation2\" style=\"font-size:10pt;font-family:Agency FB;float:left;width:300px;text-align:center;border:0;display:none;\">
				</div>
				<div style=\"width:300px;float:right;\">
					<span style=\"font-size:10pt;font-weight:Normal;font-family:Agency FB;text-align:center;width:100%;\">Principal’s Representative</span>
					<span style=\"font-size:10pt;font-weight:Normal;font-family:Agency FB;text-align:center;width:100%;\">Veritas Maritime Corporation</span>
				</div>
				-->
				<div style=\"width:100%;height:140px;vertical-align:center;\">
					<div style=\"width:250px;float:left;\">
						<br>
						<select id=\"ScreenOut\" name=\"signatory1\" style=\"font-size:10pt;font-family:Times New Roman;float:left;width:100%;\" 
							onchange=\"placedesignation('sign1','designation1',this.value);\">
							<option value=\"\">--Select--</option>";
							for($i=0;$i<$cntdata;$i++)
							{
								$name1=$signlistarr1[$i];
								echo "<option value=\"$i\">$name1</option>\n";
							}
					echo "
						</select><br>
						<span id=\"PrintOut\">
							<input type=\"text\" id=\"sign1\" style=\"font-size:12pt;font-family:Times New Roman;float:left;width:250px;text-align:center;border:0;\">
						</span>
						<input type=\"text\" id=\"designation1\" style=\"font-size:10pt;font-family:Agency FB;float:left;width:250px;text-align:center;border:0;\">
						<input type=\"text\" id=\"designation2\" style=\"font-size:10pt;font-family:Agency FB;float:left;width:250px;text-align:center;border:0;display:none;\">
					</div>
					<img src=\"$dirfilename\" width=\"135px\" height=\"135px\" border=\"1\" style=\"float:left;\"/>
					<div style=\"width:250px;float:right;\">
						<br>
						<select id=\"ScreenOut\" name=\"signatory2\" style=\"font-size:10pt;font-family:Times New Roman;float:right;width:100%;\" 
							onchange=\"placedesignation('sign2','designation2',this.value);\">
							<option value=\"\">--Select--</option>";
							for($i=0;$i<$cntdata;$i++)
							{
								$name2=$signlistarr1[$i];
								echo "<option value=\"$i\">$name2</option>\n";
							}
					echo "
						</select><br>
						<span id=\"PrintOut\">
							<input type=\"text\" id=\"sign2\" style=\"font-size:12pt;font-family:Times New Roman;float:right;width:250px;text-align:center;border:0;\">
						</span>
						<span style=\"font-size:10pt;font-weight:Normal;font-family:Agency FB;text-align:center;width:100%;\">Principal’s Representative</span>
						<span style=\"font-size:10pt;font-weight:Normal;font-family:Agency FB;text-align:center;width:100%;\">Veritas Maritime Corporation</span>
					</div>
				</div>
			</div>
		</div>";
	break;
	
	case "attendance1":
		$borderwidth=6.5*$px;
		$borderheight=10.25*$px;
		$margin=12;
		
		$borderwidthpx=$borderwidth."px";
		$borderheightpx=$borderheight."px";
		
		//adjust font size for name if long
		$fontsize="24pt";
		if($cntname<20)
			$cntname = $cntname*1.4;
		else if($cntname<30)
		{
			$cntname = $cntname*1.57;
			if($cntname1>25)
				$fontsize="22pt";
		}
		else if($cntname<40)
		{
			$cntname = $cntname*1.65;
			$fontsize="18pt";
		}
		else
		{
			$cntname = $cntname*1.7;
			$fontsize="18pt";
			if($cntname1>45)
			{
				$cntname=$cntname1*1.6;
				$fontsize="14pt";
			}
		}

		echo "
		<div style=\"width:$borderwidthpx;height:$borderheightpx;border:1px Solid #003366;position:absolute;\">
			<div style=\"margin:$margin $margin $margin $margin;width:$borderwidthpx;height:100%;border:5px Solid #003366;position:absolute;\">
				<center>
					<table style=\"width:90%;padding:5 5 5 5;\">
						<tr>
							<td width=\"20%\" align=\"center\" valign=\"top\">
						";
									$dirfilename = "images/veritas_logo.JPG";
									if (checkpath($dirfilename))
									{
										$scale = imageScale($dirfilename,-1,100);
										$width = $scale[0];
										$height = $scale[1];
									}
						echo "
								<img src=\"$dirfilename\" width=\"$width\" height=\"$height\" border=\"0\" />
							</td>
							<td width=\"80%\" align=\"center\">
								<br />
								<span style=\"font-size:12pt;font-weight:Bold;font-family:EngraversD;\">Veritas Maritime Corporation </span><br /><br />
								<span style=\"font-size:12pt;font-family:Times New Roman;\"><i>in behalf of</i></span> <br /><br />
								<span style=\"font-size:11pt;font-weight:Bold;font-family:EngraversD;\">Kagoshima Senpaku Co.,Ltd.</span>
							</td>
						</tr>
					</table>
					<br /><br />
				
					<span style=\"font-size:38pt;font-family:Impact;\">Certificate of Attendance</span> <br /><br />
					<span style=\"font-size:12pt;font-family:Times New Roman;\"><i>This certifies that</i></span> <br /><br />
					
					<span style=\"font-size:24pt;font-weight:Bold;font-family:Monotype Corsiva;\">
						<input type=\text\" style=\"font-size:24pt;font-weight:Bold;font-family:Monotype Corsiva;text-align:right;text-decoration:underline;border:0;\" 
								value=\"$alias2\" size=\"6\">
						<input type=\text\" readonly=\"readonly\" style=\"font-size:24pt;font-weight:Bold;font-family:Monotype Corsiva;text-align:left;text-decoration:underline;border:0;\" 
							value=\"$crewname\" size=\"$cntname\">
					</span> <br />
					
					
			<!--		<span style=\"font-size:24pt;font-weight:Bold;font-family:Monotype Corsiva;\"><u>$rank $crewname</u></span> <br />  -->
					<span style=\"font-size:12pt;font-family:Times New Roman;\"><i>has successfully completed the</i></span> <br /><br />
					
					<span style=\"font-size:20pt;font-weight:Bold;font-family:Monotype Corsiva;\" id=\"ScreenOut\">
						<select name=\"courseselect\" onchange=\"course.value=this.value;courseprint.value=this.value;placetopics(this.value);\">
							<option value=\"\">--Select--</option>";
							while($rowtrainingcourse=mysql_fetch_array($qrytrainingcourse))
							{
								$course=$rowtrainingcourse["TRAINING"];
								echo "<option value=\"$course\">$course</option>";
							}	
					echo "
						</select>
						
						<input type=\"text\" name=\"course\" style=\"font-size:12pt;font-weight:Bold;font-family:Times New Roman;text-align:center;\" 
							onkeyup=\"courseprint.value=this.value;\" size=\"70\">
					
					</span> 
					<span id=\"PrintOut\">
						<input type=\"text\" name=\"courseprint\" style=\"width:100%;font-size:20pt;font-weight:Bold;font-family:Times New Roman;text-align:center;border:0;\"> 
					</span> 
					
					<span style=\"font-size:12pt;font-family:Times New Roman;\"><i>(refresher course)</i></span> <br /><br /><br />
					
					<span id=\"PrintOut\">
						<input type=\"text\" name=\"incdateprint\" style=\"width:100%;font-size:12pt;font-weight:Bold;font-family:Times New Roman;text-align:center;border:0;\"> 
					</span>
					<span style=\"font-size:12pt;font-family:Times New Roman;\" id=\"ScreenOut\">
						<input type=\"text\" name=\"incdate\" onkeyup=\"incdateprint.value=this.value;\" style=\"text-align:center;\" size=\"50\" />
					</span>
					<br /><br />
					
					<span style=\"font-size:14pt;font-weight:Bold;font-family:Times New Roman;\">TOPICS DISCUSSED ON THE SEMINAR ARE:</span> 
					<br /><br />
					";
					
					echo "<textarea id=\"ScreenOut\" rows=\"10\" cols=\"60\" name=\"topics\" onblur=\"placetopics_edit(this.value);\"></textarea> <br /><br />";

					echo "
					<span id=\"PrintOut\"><span id=\"topics1\" style=\"text-align:left;\"></span></span>
					<br><br>
					
					
					<span id=\"ScreenOut\" style=\"font-size:12pt;font-family:Times New Roman;\">
						<i>given in Manila, Philippines on</i>
						<input type=\"text\" name=\"givendate\" onkeyup=\"givendateprint.value=this.value;\" style=\"text-align:center;\" size=\"30\" />
					</span>
					<span id=\"PrintOut\" style=\"font-size:12pt;font-family:Times New Roman;\">
						<i>given in Manila, Philippines on</i>
						<input type=\"text\" name=\"givendateprint\" style=\"font-size:12pt;font-weight:Bold;font-family:Times New Roman;border:0;\"> 
					</span>
					<br><br>
					
					<div style=\"width:90%;height:15px;\" id=\"ScreenOut\">
						<select name=\"signatory1\" style=\"font-size:13pt;font-family:Times New Roman;float:left;width:300px;\" 
							onchange=\"placedesignation('sign1','designation2',this.value);\">
							<option value=\"\">--Select--</option>";
							for($i=0;$i<$cntdata;$i++)
							{
								$name1=$signlistarr1[$i];
								echo "<option value=\"$i\">$name1</option>";
							}
					echo "
						</select>
					</div>
					
					<span style=\"width:100%;\" id=\"PrintOut\">
						<br><br>
						<input type=\"text\" id=\"sign1\" style=\"font-size:12pt;font-family:Times New Roman;float:left;width:900px;text-align:center;border:0;\">
					</span>
					<div style=\"width:100%;>
						<input type=\"text\" id=\"designation0\" style=\"font-size:9pt;font-family:Agency FB;float:left;width:300px;text-align:center;border:0;\">
						<input type=\"text\" id=\"designation1\" style=\"font-size:9pt;font-family:Agency FB;float:left;width:300px;text-align:center;border:0;\">
						<input type=\"text\" id=\"designation2\" style=\"font-size:9pt;font-family:Agency FB;float:right;width:300px;text-align:center;border:0;\">
					</div>
		
<!--		
					<div style=\"width:90%\">
						<table width=\"100%\">
							<tr>
								<td width=\"50%\">&nbsp;</td>
								<td align=\"center\"><span style=\"font-size:12pt;font-weight:Bold;font-family:Times New Roman;\"><i>Capt. Lexington S. Calumpang</i></span></td>
							</tr>
							<tr>
								<td width=\"\">&nbsp;</td>
								<td align=\"center\"><span style=\"font-size:12pt;font-family:Times New Roman;\">Training Manager</span></td>
							</tr>
						</table>
					</div>
-->
				</center>
				<input type=\"button\" id=\"ScreenOut\" value=\"Print\" onclick=\"window.print();\" />
			</div>
		</div>
		";
	break;
}
echo "
</form>
</body>

</html>

";

?>