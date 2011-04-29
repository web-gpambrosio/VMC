<?php

//include('veritas/connectdb.php');
include('connectdb.php');
session_start();

$currentdate = date('Y-m-d H:i:s');

$basedir = "scanned"; //change if different directory

if (isset($_POST['applicantno']))
	$applicantno = $_POST['applicantno'];
else 
	$applicantno = "91";

	
	$qrygetcrew = mysql_query("SELECT CREWCODE,CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,
								CONCAT(ADDRESS,', ',MUNICIPALITY,' ',CITY,' ',ZIPCODE) AS ADDRESS,
								TELNO,BIRTHDATE,BIRTHPLACE,GENDER,CIVILSTATUS,NATIONALITY,RELIGION,
								SSS,TIN,WEIGHT,HEIGHT,EMAIL,OFWNO,t.TRADEROUTE AS PREFROUTE,
								IF(FASTTRACK=1,'FAST TRACK',NULL) AS FASTTRACK,
                				IF(cs.APPLICANTNO IS NOT NULL,'SCHOLAR',NULL) AS SCHOLAR,
								a.RECOMMENDEDBY,REMARKS
								FROM crew c
								LEFT JOIN traderoute t ON t.TRADEROUTECODE=c.PREFROUTE
								LEFT JOIN applicant a ON a.APPLICANTNO=c.APPLICANTNO
                				LEFT JOIN crewscholar cs ON cs.APPLICANTNO=c.APPLICANTNO
								WHERE c.APPLICANTNO=$applicantno") or die(mysql_error());
	
	$rowgetcrew = mysql_fetch_array($qrygetcrew);
	
	$personalcrewcode = $rowgetcrew["CREWCODE"];
	$personalname = $rowgetcrew["NAME"];
	$personaladdress = $rowgetcrew["ADDRESS"];
	$personaltelno = $rowgetcrew["TELNO"];
	$personalbdate = date('m/d/Y',strtotime($rowgetcrew["BIRTHDATE"]));
	
//	$age = number_format((strtotime($currentdate) - strtotime($bdate)) / (86400*365.25),0);
	
	$personalage = floor((strtotime($currentdate) - strtotime($personalbdate)) / (86400*365.25));
	
	$personalbplace = $rowgetcrew["BIRTHPLACE"];
	$personalgender = $rowgetcrew["GENDER"];
	
	if ($personalgender == "M")
		$personalgender = "MALE";
	else 
		$personalgender = "FEMALE";
	
	$personalcivilstatus = $rowgetcrew["CIVILSTATUS"];
	
	switch ($personalcivilstatus)
	{
		case "S":
				$personalcivilstatus = "SINGLE";
			break;
		case "M":
				$personalcivilstatus = "MARRIED";
			break;
		case "W":
				$personalcivilstatus = "WIDOW";
			break;
	}
	
	$personalnationality = $rowgetcrew["NATIONALITY"];
	$personalreligion = $rowgetcrew["RELIGION"];
	$personalsss = $rowgetcrew["SSS"];
	$personaltin = $rowgetcrew["TIN"];
	$personalweight = $rowgetcrew["WEIGHT"];
	$personalheight = $rowgetcrew["HEIGHT"];
	$personalemail = $rowgetcrew["EMAIL"];
	$personalofwno = $rowgetcrew["OFWNO"];
	$personalprefroute = $rowgetcrew["PREFROUTE"];
	$personalfasttrack = $rowgetcrew["FASTTRACK"];
	$personalscholar = $rowgetcrew["SCHOLAR"];
	$personalrecommendedby = $rowgetcrew["RECOMMENDEDBY"];
	$personalremarks = $rowgetcrew["REMARKS"];


	
	$qryfamilylist = mysql_query("SELECT CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,CONCAT(ADDRESS,', ',MUNICIPALITY,' ',CITY) AS ADDRESS, 
								fr.RELATION,TELNO
								FROM crewfamily cf
								LEFT JOIN familyrelation fr ON fr.RELCODE=cf.RELCODE
								WHERE fr.RELCODE <> 'HIM' AND
								APPLICANTNO=$applicantno") or die(mysql_error());	

	$qrydoclist = mysql_query("SELECT cd.DOCCODE,cd.DOCUMENT,cds.DOCNO,cds.DATEISSUED,cds.DATEEXPIRED,r.ALIAS2
								FROM crewdocstatus cds
								LEFT JOIN crewdocuments cd ON cd.DOCCODE=cds.DOCCODE
								LEFT JOIN rank r ON r.RANKCODE=cds.RANKCODE
								WHERE cd.TYPE='D' and cds.APPLICANTNO=$applicantno
								ORDER BY cds.DATEISSUED DESC
								") or die(mysql_error());
	
	$qryliclist = mysql_query("SELECT cd.DOCCODE,cd.DOCUMENT,cds.DOCNO,cds.DATEISSUED,cds.DATEEXPIRED,r.ALIAS2
								FROM crewdocstatus cds
								LEFT JOIN crewdocuments cd ON cd.DOCCODE=cds.DOCCODE
								LEFT JOIN rank r ON r.RANKCODE=cds.RANKCODE
								WHERE cd.TYPE='L'and cds.APPLICANTNO=$applicantno
								ORDER BY cds.DATEISSUED DESC
								") or die(mysql_error());	
	
	$qryexperiencelist = mysql_query("SELECT ce.VESSEL,r.ALIAS1 AS RANKALIAS,ce.VESSELTYPECODE,ce.TRADEROUTECODE,
								IF (ce.MANNINGCODE = '',ce.MANNINGOTHERS,m.MANNING) AS MANNING,
								IF((ce.DISEMBREASONCODE <> 'OTH'),dr.REASON,ce.REASONOTHERS) AS REASON,
                				ce.DISEMBREASONCODE,DATEEMB,DATEDISEMB
								FROM crewexperience ce
								LEFT JOIN rank r ON r.RANKCODE=ce.RANKCODE
								LEFT JOIN traderoute t ON t.TRADEROUTECODE=ce.TRADEROUTECODE
								LEFT JOIN manning m ON m.MANNINGCODE=ce.MANNINGCODE
								LEFT JOIN disembarkreason dr ON dr.DISEMBREASONCODE=ce.DISEMBREASONCODE
								WHERE ce.APPLICANTNO=$applicantno
								ORDER BY DATEEMB DESC") or die(mysql_error());
	
	$qryexperiencevmclist = mysql_query("SELECT v.VESSEL,v.VESSELTYPECODE,v.TRADEROUTECODE,r.ALIAS2,
								cc.DATEEMB,cc.DATEDISEMB,dr.REASON
								FROM crewchange cc
                				LEFT JOIN rank r ON r.RANKCODE=cc.RANKCODE
								LEFT JOIN vessel v ON v.VESSELCODE=cc.VESSELCODE
								LEFT JOIN disembarkreason dr ON cc.DISEMBREASONCODE=dr.DISEMBREASONCODE
								WHERE APPLICANTNO=88
								ORDER BY cc.DATEEMB DESC
								") or die(mysql_error());
	
	$qrycertlist = mysql_query("SELECT cd.DOCCODE,cd.DOCUMENT,ccs.DOCNO,ccs.DATEISSUED,ccs.GRADE,r.ALIAS2
								FROM crewcertstatus ccs
								LEFT JOIN crewdocuments cd ON cd.DOCCODE=ccs.DOCCODE
								LEFT JOIN rank r ON r.RANKCODE=ccs.RANKCODE
								WHERE cd.TYPE='C'and ccs.APPLICANTNO=$applicantno
								ORDER BY ccs.DATEISSUED DESC
								") or die(mysql_error());
	
	$qrymedicallist = mysql_query("SELECT cl.CLINIC,cm.DATECHECKUP,cm.DIAGNOSIS,cr.RECOMMENDATION,cm.REMARKS,cm.DOCCODE
								FROM crewmedical cm
								LEFT JOIN clinic cl ON cl.CLINICID=cm.CLINICID
								LEFT JOIN clinicrecommend cr ON cr.RECOMMENDCODE=cm.RECOMMENDCODE
								WHERE cm.APPLICANTNO=$applicantno
								ORDER BY cm.DATECHECKUP DESC
								") or die(mysql_error());
	
	$qryperformancelist = mysql_query("SELECT v.VESSEL,cc.CCID,cc.DATEEMB,cc.DATEDISEMB
								FROM crewchange cc
								LEFT JOIN vessel v ON v.VESSELCODE=cc.VESSELCODE
								WHERE cc.APPLICANTNO=$applicantno
								ORDER BY cc.DATEEMB DESC,cc.CCID
								") or die(mysql_error());
	
	
function checkpath($path)
{
	if (is_file($path))
		return true;
	else 
		return false;
}	
	

echo "
<html>\n
<head>\n
<link rel=\"StyleSheet\" href=\"veripro.css\" />
<script src='veripro.js' type='text/javascript'></script>


<SCRIPT language=JavaScript>\n



function selecttab201(x)
{
	document.crewchangeplan.prev201.value=document.getElementById('currentli').name;
	document.getElementById('currentli').id=document.crewchangeplan.prev201.value;
	document.getElementById('a'+x).id='currentli';
	
	//get previous select
	var getprevlen=document.crewchangeplan.prev201.value.length;
	var getprev=document.crewchangeplan.prev201.value.substring(1,getprevlen);
	
	with(document.crewchangeplan)
	{
		eval(getprev+'.id=\'\'');
		eval(x+'.id=\'current\'');
	}
	
	//for div details
	document.getElementById('x'+getprev).style.display='none';
	document.getElementById('x'+x).style.display='block';
}

</script>

</head>\n

<body style=\"\">\n

<form name=\"crewchangeplan\" method=\"POST\">\n


<div id=\"pooling\" class=\"outside\" style=\"z-index:100;position:absolute;left:20px;top:10px;width:980px;height:620px;\">
	
	<span class=\"wintitle\">POOLING</span>
	
	<div class=\"navbar\" style=\"width:50%;float:left;\">
		<table>
			<tr>
				<td>Sample only</td>
				<td><input type\"text\" /></td>
			</tr>
		</table>
	</div>
	
	<div id=\"crewdetails\" style=\"width:50%;height:570px;background:#F0FFF0;;float:right;\">
		<div id=\"tab201site\" style=\"width:100%;\">
			 <ul>
				  <li id=\"currentli\" name=\"apersonal201\"><a name=\"personal201\" onclick=\"selecttab201(this.name);\" id=\"current\">Personal</a></li> 
				  <li id=\"adocuments201\" name=\"adocuments201\"><a name=\"documents201\" onclick=\"selecttab201(this.name);\">Documents</a></li>
				  <li id=\"aexperience201\" name=\"aexperience201\"><a name=\"experience201\" onclick=\"selecttab201(this.name);\">Experience</a></li>
				  <li id=\"atraining201\" name=\"atraining201\"><a name=\"training201\" onclick=\"selecttab201(this.name);\">Training</a></li>
				  <li id=\"aperformance201\" name=\"aperformance201\"><a name=\"performance201\" onclick=\"selecttab201(this.name);\">Performance</a></li> 
				  <li id=\"amedical201\" name=\"amedical201\"><a name=\"medical201\" onclick=\"selecttab201(this.name);\">Medical</a></li>
			 </ul>
		</div>
		

		<div id=\"xpersonal201\" style=\"width:100%;display:block;\">

			<div style=\"width:100%;height:150px;\">		
				<div style=\"float:left;width:332px;height:150px;padding-top:3px;padding-left:3px;\">
					<span class=\"sectiontitle\">PERSONAL INFORMATION</span>
					<table class=\"listrow\">
						<tr>
							<th>CREW CODE</th>
							<th>:</th>
							<td><span class=\"important\">$personalcrewcode</span></td>
						</tr>
						<tr>
							<th>NAME</th>
							<th>:</th>
							<td>$personalname</td>
						</tr>
						<tr>
							<th>ADDRESS</th>
							<th>:</th>
							<td>$personaladdress</td>
						</tr>
						<tr>
							<th>TEL. NO.</th>
							<th>:</th>
							<td>$personaltelno</td>
						</tr>
						<tr>
							<th>EMAIL</th>
							<th>:</th>
							<td>$personalemail</td>
						</tr>
						<tr>
							<th>RECOMMENDED</th>
							<th>:</th>
							<td>$personalrecommendedby</td>
						</tr>
					</table>
				</div>
				
				<div style=\"float:right;width:155px;height:150px;\">
					<img src=\"d:/picture/$searchcrewcode.jpg\" width=\"150px\" height=\"150px\">
				</div>
			</div>
			
			<div style=\"width:100%;height:140px;\">		
			
				<div style=\"float:left;width:50%;padding-top:3px;padding-left:3px;\">
					<table class=\"listrow\">
						<tr>
							<th width=\"35%\">BIRTHDATE</th>
							<th width=\"5%\">:</th>
							<td width=\"60%\">$personalbdate</td>
						</tr>
						<tr>
							<th>AGE</th>
							<th>:</th>
							<td>$personalage</td>
						</tr>						
						<tr>
							<th>BIRTHPLACE</th>
							<th>:</th>
							<td>$personalbplace</td>
						</tr>
						<tr>
							<th>GENDER</th>
							<th>:</th>
							<td>$personalgender</td>
						</tr>
						<tr>
							<th>STATUS</th>
							<th>:</th>
							<td>$personalcivilstatus</td>
						</tr>
						<tr>
							<th>RELIGION</th>
							<th>:</th>
							<td>$personalreligion</td>
						</tr>
					</table>
				</div>
				<div style=\"float:right;width:49%;\">
					<table class=\"listrow\">
						<tr>
							<th>WEIGHT</th>
							<th>:</th>
							<td>$personalweight kls.</td>
						</tr>
						<tr>
							<th>HEIGHT</th>
							<th>:</th>
							<td>$personalheight cm.</td>
						</tr>
						
						<tr>
							<th>SSS NO.</th>
							<th>:</th>
							<td>$personalsss</td>
						</tr>
						<tr>
							<th>TAX ID NO.</th>
							<th>:</th>
							<td>$personaltin</td>
						</tr>
						<tr>
							<th>OFW NO.</th>
							<th>:</th>
							<td>$personalofwno</td>
						</tr>
						<tr>
							<th>PREF. ROUTE</th>
							<th>:</th>
							<td>$personalprefroute</td>
						</tr>
					</table>
				</div>
				
			</div>	
			
			
			<div style=\"width:100%;height:80px;overflow:auto;\">
			
				<span class=\"sectiontitle\">REMARKS</span>
				
				<table class=\"listrow\">
					<tr>
						<td>$personalremarks</td>
					</tr>
				</table>
			</div>
			
			
			<div style=\"width:100%;\">
			
				<span class=\"sectiontitle\">FAMILY BACKGROUND</span>
				
				<div style=\"width:100%;overflow:auto;height:130px;\">
					<table class=\"listcol\">
						<tr>
							<th width=\"30%\">NAME</th>
							<th width=\"30%\">ADDRESS</th>
							<th width=\"20%\">RELATION</th>
							<th width=\"20%\">TELNO</th>
						</tr>
	";
					$ctr = 0;
					
					while ($rowfamilylist = mysql_fetch_array($qryfamilylist))
					{
						if (($ctr % 2) == 0)
							$class = "even";
						else 
							$class = "odd";
										
						$family1 = $rowfamilylist["NAME"];
						$family2 = $rowfamilylist["ADDRESS"];
						$family3 = $rowfamilylist["RELATION"];
						$family4 = $rowfamilylist["TELNO"];
						
	echo "
						<tr class=\"$class\">
							<td>$family1</td>
							<td>$family2</td>
							<td align=\"center\">$family3</td>
							<td align=\"center\">$family4</td>
						</tr>
	";					
						$ctr++;
					}
	
	echo "				
					</table>
				</div>
			</div>	
	
		</div>
		<div id=\"xdocuments201\" style=\"width:100%;display:none;\">
			<br />
			<span class=\"sectiontitle\">LIST OF DOCUMENTS</span>
			
			<div style=\"width:100%; overflow:auto;height:220px;\">
				<table class=\"listcol\">
					<tr>
						<th width=\"30%\">DOCUMENT</th>
						<th width=\"20%\">NO.</th>
						<th width=\"5%\">RANK</th>
						<th width=\"20%\">DATE ISSUED</th>
						<th width=\"20%\">DATE EXPIRED</th>
						<th width=\"5%\">&nbsp;</th>
					</tr>
";
				$ctr = 0;
				while ($rowdoclist = mysql_fetch_array($qrydoclist))
				{
					
					if (($ctr % 2) == 0)
						$class = "even";
					else 
						$class = "odd";
						
					$doc1 = $rowdoclist["DOCUMENT"];
					$doc2 = $rowdoclist["DOCNO"];
					$doc3 = $rowdoclist["ALIAS2"];
					if ($rowdoclist["DATEISSUED"] != "")
						$doc4 = date('m/d/Y',strtotime($rowdoclist["DATEISSUED"]));
					else 
						$doc4 = "";
					
					if ($rowdoclist["DATEEXPIRED"] != "")
						$doc5 = date('m/d/Y',strtotime($rowdoclist["DATEEXPIRED"]));
					else 
						$doc5 = "";
						
					$doccode = $rowdoclist["DOCCODE"];
					
					if (checkpath("$basedir/$personalcrewcode/$doccode.pdf"))
						$viewdisabled = "";
					else 
						$viewdisabled = "disabled=\"disabled\"";
						
						
					$target = "doc$personalcrewcode" . "_$ctr";
					
echo "
					<tr class=\"$class\">
						<td>$doc1</td>
						<td align=\"center\">$doc2</td>
						<td align=\"center\">$doc3</td>
						<td align=\"center\">$doc4</td>
						<td align=\"center\">$doc5</td>
						<td align=\"center\">
							<input type=\"button\" value=\"V\" $viewdisabled style=\"color:Red;font-weight:Bold;font-size:0.9em;\"
									onclick=\"javascript:openWindow('$basedir/$personalcrewcode/$doccode.pdf', '$target', 900, 600);\"/>	
						</td>
					</tr>
";					
					$ctr++;
				}

echo "				
				</table>
			</div>

			<br />
			<span class=\"sectiontitle\">LIST OF LICENSES</span>
			
			<div style=\"width:100%; overflow:auto;height:220px;\">
				<table class=\"listcol\">
					<tr>
						<th width=\"30%\">LICENSE</th>
						<th width=\"20%\">NO.</th>
						<th width=\"5%\">RANK</th>
						<th width=\"20%\">DATE ISSUED</th>
						<th width=\"20%\">DATE EXPIRED</th>
						<th width=\"5%\">&nbsp;</th>
					</tr>
";
				$ctr = 0;
				while ($rowliclist = mysql_fetch_array($qryliclist))
				{
					
					if (($ctr % 2) == 0)
						$class = "even";
					else 
						$class = "odd";
						
					$lic1 = $rowliclist["DOCUMENT"];
					$lic2 = $rowliclist["DOCNO"];
					$lic3 = $rowliclist["ALIAS2"];
					if ($rowliclist["DATEISSUED"] != "")
						$lic4 = date('m/d/Y',strtotime($rowliclist["DATEISSUED"]));
					else 
						$lic4 = "";
					
					if ($rowliclist["DATEEXPIRED"] != "")
						$lic5 = date('m/d/Y',strtotime($rowliclist["DATEEXPIRED"]));
					else 
						$lic5 = "";
					
					$liccode = $rowliclist["DOCCODE"];
					
					if (checkpath("$basedir/$personalcrewcode/$liccode.pdf"))
						$viewdisabled = "";
					else 
						$viewdisabled = "disabled=\"disabled\"";
					
					$target = "lic$personalcrewcode" . "_$ctr";
						
echo "
					<tr class=\"$class\">
						<td>$lic1</td>
						<td align=\"center\">$lic2</td>
						<td align=\"center\">$lic3</td>
						<td align=\"center\">$lic4</td>
						<td align=\"center\">$lic5</td>
						<td align=\"center\">
							<input type=\"button\" value=\"V\" $viewdisabled style=\"color:Red;font-weight:Bold;font-size:0.9em;\"
									onclick=\"javascript:openWindow('$basedir/$personalcrewcode/$liccode.pdf', '$target', 900, 600);\"/>	
						</td>
					</tr>
";					
					$ctr++;
				}

echo "				
				</table>
			</div>
			
		</div>
		
		<div id=\"xexperience201\" style=\"width:100%;display:none;\">
		
			<br />
			<span class=\"sectiontitle\">EXPERIENCES - OUTSIDE</span>
			
			<div style=\"width:100%; overflow:auto;height:220px;\">
				<table class=\"listcol\">
					<tr>
						<th width=\"20%\">VESSEL</th>
						<th width=\"5%\">RANK</th>
						<th width=\"5%\">TYPE</th>
						<th width=\"10%\">EMBARK</th>
						<th width=\"10%\">DISEMBARK</th>
						<th width=\"5%\">ROUTE</th>
						<th width=\"25%\">MANNING</th>
						<th width=\"20%\">REASON</th>
					</tr>
";
				$ctr = 0;
				while ($rowexperiencelist = mysql_fetch_array($qryexperiencelist))
				{
					
					if (($ctr % 2) == 0)
						$class = "even";
					else 
						$class = "odd";
						
					$experience1 = $rowexperiencelist["VESSEL"];
					$experience2 = $rowexperiencelist["RANKALIAS"];
					$experience3 = $rowexperiencelist["VESSELTYPECODE"];
					
					if ($rowexperiencelist["DATEEMB"] != "")
						$experience4 = date('m/d/Y',strtotime($rowexperiencelist["DATEEMB"]));
					else 
						$experience4 = "";
					
					if ($rowexperiencelist["DATEDISEMB"] != "")
						$experience5 = date('m/d/Y',strtotime($rowexperiencelist["DATEDISEMB"]));
					else 
						$experience5 = "";
						
					$experience6 = $rowexperiencelist["TRADEROUTECODE"];
					$experience7 = $rowexperiencelist["MANNING"];
					$experience8 = $rowexperiencelist["REASON"];
					
echo "
					<tr class=\"$class\">
						<td>$experience1</td>
						<td align=\"center\">$experience2</td>
						<td align=\"center\">$experience3</td>
						<td align=\"center\">$experience4</td>
						<td align=\"center\">$experience5</td>
						<td align=\"center\">$experience6</td>
						<td>$experience7</td>
						<td>$experience8</td>
					</tr>
";					
					$ctr++;
				}

echo "				
				</table>
			</div>
			
			<br />
			<span class=\"sectiontitle\">EXPERIENCES - VERITAS</span>
			
			<div style=\"width:100%; overflow:auto;height:220px;\">
				<table class=\"listcol\">
					<tr>
						<th width=\"25%\">VESSEL</th>
						<th width=\"10%\">RANK</th>
						<th width=\"10%\">TYPE</th>
						<th width=\"10%\">EMBARK</th>
						<th width=\"10%\">DISEMBARK</th>
						<th width=\"10%\">ROUTE</th>
						<th width=\"25%\">REASON</th>
					</tr>
";
				$ctr = 0;
				while ($rowexperiencevmclist = mysql_fetch_array($qryexperiencevmclist))
				{
					
					if (($ctr % 2) == 0)
						$class = "even";
					else 
						$class = "odd";
						
					$experiencevmc1 = $rowexperiencevmclist["VESSEL"];
					$experiencevmc2 = $rowexperiencevmclist["ALIAS2"];
					$experiencevmc3 = $rowexperiencevmclist["VESSELTYPECODE"];
					
					if ($rowexperiencevmclist["DATEEMB"] != "")
						$experiencevmc4 = date('m/d/Y',strtotime($rowexperiencevmclist["DATEEMB"]));
					else 
						$experiencevmc4 = "";
					
					if ($rowexperiencevmclist["DATEDISEMB"] != "")
						$experiencevmc5 = date('m/d/Y',strtotime($rowexperiencevmclist["DATEDISEMB"]));
					else 
						$experiencevmc5 = "";
						
					$experiencevmc6 = $rowexperiencevmclist["TRADEROUTECODE"];
					$experiencevmc7 = $rowexperiencevmclist["REASON"];
					
echo "
					<tr class=\"$class\">
						<td>$experiencevmc1</td>
						<td align=\"center\">$experiencevmc2</td>
						<td align=\"center\">$experiencevmc3</td>
						<td align=\"center\">$experiencevmc4</td>
						<td align=\"center\">$experiencevmc5</td>
						<td align=\"center\">$experiencevmc6</td>
						<td>$experiencevmc7</td>
					</tr>
";					
					$ctr++;
				}

echo "				
				</table>
			</div>			
		</div>
		
		<div id=\"xperformance201\" style=\"width:100%;display:none;\">
			<br />
			<span class=\"sectiontitle\">PERFORMANCE</span>
			
			<div style=\"width:100%; overflow:auto;height:440px;\">
";
			$ccidtemp = "";
			
			while ($rowperformancelist=mysql_fetch_array($qryperformancelist))
			{

				$ccid = $rowperformancelist["CCID"];		
				$performance1 = $rowperformancelist["VESSEL"];
				$performance2 = date('m/d/Y',strtotime($rowperformancelist["DATEEMB"]));
				$performance3 = date('m/d/Y',strtotime($rowperformancelist["DATEDISEMB"]));
				
				//PUT IF FILE EXISTS...
				
				if ($ccid != $ccidtemp)
					echo "
					<table width=\"100%\" class=\"listrow\">
						<tr style=\"background-color:#DCDCDC;\">
							<th width=\"15%\">Vessel:</th>
							<td width=\"30%\">$performance1</td>
							<th width=\"10%\">Embark:</th>
							<td width=\"15%\">$performance2</td>
							<th width=\"15%\">Disembark:</th>
							<td width=\"15%\">$performance3</td>
						</tr>
					</table>
						";

echo "				<table class=\"listcol\">
						<tr>
							<th style=\"font-size:0.8em;\">EVALDATE</th>
							<th style=\"font-size:0.8em;\">EVALNO</th>
							<th style=\"font-size:0.8em;\">GRADE</th>
							<th style=\"font-size:0.8em;\">VIEW</th>
						</tr>

";
					
					$qryperformancelist2 = mysql_query("SELECT ceh.EVALNO,ceh.EVALDATE,ceh.VMCCOMMENT,ceh.PRINCIPALCOMMENT,ceh.REMARKS
												FROM crewevalhdr ceh
												WHERE ceh.CCID=$ccid
												ORDER BY ceh.EVALNO
												") or die(mysql_error());

					$ctr = 1;
					while($rowperformancelist2 = mysql_fetch_array($qryperformancelist2))
					{
				
						$evalno = $rowperformancelist2["EVALNO"];
						$performance5 = $rowperformancelist2["EVALDATE"];
						$performance6 = $rowperformancelist2["GRADE"];
						
						
						if ($evalno < 4)
							switch ($evalno)					
							{
								case "1"	:	$evalno1 = "1st"; break;
								case "2"	:	$evalno1 = "2nd"; break;
								case "3"	:	$evalno1 = "3rd"; break;
								
							}
						else 
							$evalno = $evalno . "th";

						//PUT IF FILE EXISTS...
						$cerfile = "cer_$ccid" . "_$evalno";
						if (checkpath("$basedir/$personalcrewcode/$cerfile.pdf"))
							$viewdisabled = "";
						else 
							$viewdisabled = "disabled=\"disabled\"";
						
							
						$target = "cer$personalcrewcode" . "_$ccid" . "_$evalno";
							
						echo "
							<tr>
								<td align=\"center\">$performance5</td>
								<td align=\"center\">$evalno1</td>
								<td align=\"center\">$performance6</td>
								<td align=\"center\">
									<input type=\"button\" value=\"V\" $viewdisabled style=\"color:Red;font-weight:Bold;font-size:0.9em;\"
											onclick=\"javascript:openWindow('$basedir/$personalcrewcode/$cerfile.pdf', '$target', 900, 600);\"/>
								</td>
							</tr>
						";
						
						$ctr++;
						
					}
echo "				</table>";
					$ccidtemp = $ccid;
				}
echo "
			</div>
		</div>
		
		<div id=\"xtraining201\" style=\"width:100%;display:none;\">
			<br />
			<span class=\"sectiontitle\">TRAINING CERTIFICATES</span>
			
			<div style=\"width:100%; overflow:auto;height:220px;\">
				<table class=\"listcol\">
					<tr>
						<th width=\"30%\">CERTIFICATE</th>
						<th width=\"20%\">NO.</th>
						<th width=\"5%\">RANK</th>
						<th width=\"20%\">GRADE</th>
						<th width=\"20%\">DATE ISSUED</th>
						<th width=\"5%\">&nbsp;</th>
					</tr>
";
				$ctr = 0;
				while ($rowcertlist = mysql_fetch_array($qrycertlist))
				{
					
					if (($ctr % 2) == 0)
						$class = "even";
					else 
						$class = "odd";
						
					$cert1 = $rowcertlist["DOCUMENT"];
					$cert2 = $rowcertlist["DOCNO"];
					$cert3 = $rowcertlist["ALIAS2"];
					if ($rowcertlist["DATEISSUED"] != "")
						$cert4 = date('m/d/Y',strtotime($rowcertlist["DATEISSUED"]));
					else 
						$cert4 = "";
					
					$cert5 = $rowcertlist["GRADE"];
					$certcode = $rowcertlist["DOCCODE"];
					
					if (checkpath("$basedir/$personalcrewcode/$certcode.pdf"))
						$viewdisabled = "";
					else 
						$viewdisabled = "disabled=\"disabled\"";
						
					$target = "cert$personalcrewcode" . "_$certcode" . "_$ctr";
					
echo "
					<tr class=\"$class\">
						<td>$cert1</td>
						<td align=\"center\">$cert2</td>
						<td align=\"center\">$cert3</td>
						<td align=\"center\">$cert5</td>
						<td align=\"center\">$cert4</td>
						<td align=\"center\">
						
						<input type=\"button\" value=\"V\" $viewdisabled style=\"color:Red;font-weight:Bold;font-size:0.9em;\"
								onclick=\"javascript:openWindow('$basedir/$personalcrewcode/$certcode.pdf', '$target', 900, 600);\"/>

						</td>
					</tr>
";					
					$ctr++;
				}

echo "
					</tr>
				</table>
			</div>
		</div>
		
		<div id=\"xmedical201\" style=\"width:100%;display:none;\">
			<br />
			<span class=\"sectiontitle\">MEDICAL BACKGROUND</span>
			
			<div style=\"width:100%; overflow:auto;height:220px;\">
				<table class=\"listcol\">
					<tr>
						<th width=\"15%\">CLINIC</th>
						<th width=\"10%\">DATE</th>
						<th width=\"20%\">DIAGNOSIS</th>
						<th width=\"20%\">RECOMMENDATION</th>
						<th width=\"30%\">REMARKS</th>
						<th width=\"5%\">&nbsp;</th>
					</tr>
";
				$ctr = 0;
				while ($rowmedicallist = mysql_fetch_array($qrymedicallist))
				{
					
					if (($ctr % 2) == 0)
						$class = "even";
					else 
						$class = "odd";
						
					$medical1 = $rowmedicallist["CLINIC"];
					if ($rowmedicallist["DATECHECKUP"] != "")
						$medical2 = date('m/d/Y',strtotime($rowmedicallist["DATECHECKUP"]));
					else 
						$medical2 = "";
					
					$medical3 = $rowmedicallist["DIAGNOSIS"];
					$medical4 = $rowmedicallist["RECOMMENDATION"];
					$medical5 = $rowmedicallist["REMARKS"];
					$medcode = $rowmedicallist["DOCCODE"];
					
					if (checkpath("$basedir/$personalcrewcode/$medcode.pdf"))
						$viewdisabled = "";
					else 
						$viewdisabled = "disabled=\"disabled\"";					
					
echo "
					<tr class=\"$class\">
						<td>$medical1</td>
						<td align=\"center\">$medical2</td>
						<td align=\"center\">$medical3</td>
						<td align=\"center\">$medical4</td>
						<td align=\"center\">$medical5</td>
						<td align=\"center\">
						
							<input type=\"button\" value=\"V\" $viewdisabled style=\"color:Red;font-weight:Bold;font-size:0.9em;\"
								onclick=\"javascript:openWindow('$basedir/$personalcrewcode/$medcode.pdf', 'med$personalcrewcode', 900, 600);\"/>

						</td>
					</tr>
";					
					$ctr++;
				}	

echo "
				</table>
			</div>
		</div>
	</div>
</div>

<input type=\"hidden\" name=\"prev201\" value=\"\">

</form>

<form name=\"imageview\" action=\"imageview.php\" target=\"imageview\" method=\"POST\">
	<input type=\"hidden\" name=\"doctype\">
	<input type=\"hidden\" name=\"applicantno\">
</form>

</body>\n
</html>\n
";



?>