<?php

include("veritas/connectdb.php");
session_start();

$currentdate = date('Y-m-d H:i:s');
// $basedirdocs = "docimages";
$basedirdocs = "docimg";
$basedirrotation = "rotation";

//if(isset($_SESSION["employeeid"]))
//	$employeeid=$_SESSION["employeeid"];

//if(isset($_GET["managementcode"]))
//	$managementcode=$_GET["managementcode"];
//else 
//	$managementcode = "105";

if (isset($_GET["vesselcode"]))
	$vesselcode = $_GET["vesselcode"];
	
//if (isset($_POST["actiontxt"]))
//	$actiontxt = $_POST["actiontxt"];
	
	
function checkpath($path)
{
	if (is_file($path))
		return true;
	else 
		return false;
}
	

//switch ($actiontxt)
//{
//	case "showlist"	:
//		
//
//			
//		break;
//}

$qrygetvessel = mysql_query("SELECT VESSEL,VESSELTYPE,VESSELSIZE
							FROM vessel v
							LEFT JOIN vesseltype vt ON vt.VESSELTYPECODE=v.VESSELTYPECODE
							LEFT JOIN vesselsize vs ON vs.VESSELSIZECODE=v.VESSELSIZECODE
							WHERE v.VESSELCODE='$vesselcode' AND v.STATUS=1") or die(mysql_error());

$rowgetvessel = mysql_fetch_array($qrygetvessel);

$vesselname = $rowgetvessel["VESSEL"];
$vesseltype = $rowgetvessel["VESSELTYPE"];
$vesselsize = $rowgetvessel["VESSELSIZE"];


//$qrygetinfo1 = mysql_query("SELECT cc.APPLICANTNO,r.ALIAS1,CONCAT(c.FNAME,', ',c.GNAME,' ',c.MNAME) AS NAME,c.BIRTHDATE,
//							IF(cc.DEPMNLDATE IS NULL OR cc.DEPMNLDATE = '',IF (cpr.CCID IS NOT NULL,cc2.DATEEMB,cc.DATEEMB),cc.DATEEMB) AS SIGNON,
//							cc.RANKCODE,c.NATIONALITY
//							FROM crewchange cc
//							LEFT JOIN crew c ON c.APPLICANTNO=cc.APPLICANTNO
//							LEFT JOIN rank r ON r.RANKCODE=cc.RANKCODE
//							LEFT JOIN crewpromotionrelation cpr ON cpr.CCIDPROMOTE=cc.CCID
//							LEFT JOIN crewchange cc2 ON cc2.CCID=cpr.CCID
//							WHERE cc.VESSELCODE='$vesselcode'
//							AND (DATEEMB < CURRENT_DATE AND DATEDISEMB > CURRENT_DATE)
//							ORDER BY r.RANKING
//							") or die(mysql_error());

$qrygetinfo1 = mysql_query("SELECT cc.APPLICANTNO,r.ALIAS1,CONCAT(c.FNAME,', ',c.GNAME,' ',c.MNAME) AS NAME,c.BIRTHDATE,
							IF(cc.DEPMNLDATE IS NULL OR cc.DEPMNLDATE = '',IF (cpr.CCID IS NOT NULL,cc2.DATEEMB,cc.DATEEMB),cc.DATEEMB) AS SIGNON,
							cc.RANKCODE,c.NATIONALITY
							FROM crewchange cc
							LEFT JOIN crew c ON c.APPLICANTNO=cc.APPLICANTNO
							LEFT JOIN rank r ON r.RANKCODE=cc.RANKCODE
							LEFT JOIN crewpromotionrelation cpr ON cpr.CCIDPROMOTE=cc.CCID
							LEFT JOIN crewchange cc2 ON cc2.CCID=cpr.CCID
							WHERE cc.VESSELCODE='$vesselcode'
							AND cc.DATEDISEMB>=NOW() AND cc.DEPMNLDATE IS NOT NULL
							ORDER BY r.RANKING
							") or die(mysql_error());

//$qryvessellist = mysql_query("SELECT VESSELCODE,VESSEL FROM vessel WHERE STATUS=1 AND MANAGEMENTCODE='$managementcode'") or die(mysql_error());

echo "
<html>\n
<head>\n
<title>
Crew Onboard Listing
</title>

<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='veripro.js'></script>
<script language=\"javascript\" src=\"popcalendar.js\"></script>	
</head>
<body>\n

<form name=\"onboard\" method=\"POST\">\n

<span class=\"wintitle\">CREW ONBOARD LIST</span>

	<div style=\"width:1100px;height:60px;background-color:#DCDCDC;overflow:auto;padding:10px;\">
	";
	$style1 = "style=\"font-size:0.8em;font-weight:Bold;color:Black;\"";
	$style2 = "style=\"font-size:0.8em;font-weight:Bold;color:Green;\"";
	echo "
		<table>
			<tr>
				<td $style1>Vessel Name</td>
				<td $style1>:</td>
				<td $style2>$vesselname</td>
			</tr>
			<tr>
				<td $style1>Vessel Type</td>
				<td $style1>:</td>
				<td $style2>$vesseltype</td>
			</tr>
		</table>

	</div>

	<div style=\"width:1100px;height:500px;background-color:White;overflow:auto;padding:10px;\">
	<table width=\"1100\" class=\"listcol\" style=\"padding-left:3px;\">
		<tr>
			<th width=\"10px\">NO.</th>
			<th width=\"35px\" align=\"center\">RANK</th>
			<th width=\"200px\">NAME</th>
			<th width=\"15px\">NAT.</th>
			<th width=\"35px\">BIRTH DATE</th>
			<th width=\"10px\">AGE</th>
			<th width=\"20px\">SIGN ON</th>
			<th width=\"10px\">DAYS</th>
			<th width=\"20px\">LAST VESSEL</th>
			<th width=\"20px\">LAST SIGNOFF</th>
			<th width=\"10px\">EXP. AS RANK</th>
			<th width=\"20px\" align=\"center\">COC GRADE</th>
			<th width=\"20px\">COC EXPIRY</th>
			<th width=\"20px\" align=\"center\">GOC EXPIRY</th>			
			<th width=\"20px\">STCW ISSUED</th>			
			<th width=\"20px\">SEAMAN BOOK EXPIRY</th>		
			<th width=\"20px\">PASSPORT EXPIRY</th>			
			<th width=\"20px\">MEDICAL EXAM DATE</th>			
			<th width=\"20px\">SSO ISSUED</th>		
			<th width=\"20px\">SEAMAN BOOK NO.</th>				
		</tr>
	";	
		$cnt = 1;
		
		while ($rowgetinfo1 = mysql_fetch_array($qrygetinfo1))
		{
			$hlite = "";
			$css = "odd";
			if($cnt % 2)
				$css = "even";
			
			
			$applicantno = $rowgetinfo1["APPLICANTNO"];
			$rankalias = $rowgetinfo1["ALIAS1"];
			$name = $rowgetinfo1["NAME"];
			$currentrankcode = $rowgetinfo1["RANKCODE"];
			$nationality = $rowgetinfo1["NATIONALITY"];
			
			if ($rowgetinfo1["BIRTHDATE"] != "")
				$bdate = date("Y-m-d",strtotime($rowgetinfo1["BIRTHDATE"]));
			else 
				$bdate = "";
				
			$age = floor((strtotime($currentdate) - strtotime($bdate)) / (86400*365.25));
//			$age = floor((strtotime($currentdate) - strtotime($bdate)) / (86400*365.25));
				
			$signon = $rowgetinfo1["SIGNON"];
			
			$days = round((strtotime("NOW") - strtotime($signon)) / 86400);
			
			$qrylastvessel = mysql_query("SELECT * FROM
										(
											SELECT VESSEL,ALIAS1,DATEDISEMB,DEPMNLDATE
											FROM crewchange cc
											LEFT JOIN vessel v ON v.VESSELCODE=cc.VESSELCODE
											WHERE APPLICANTNO=$applicantno
											AND DATEDISEMB < CURRENT_DATE
											
											UNION
											
											SELECT left(VESSEL,4) AS VESSEL,NULL,DATEDISEMB,NULL
											FROM crewexperience ce
											WHERE APPLICANTNO=$applicantno
											AND DATEDISEMB < CURRENT_DATE
										) x
										WHERE DEPMNLDATE IS NOT NULL
										ORDER BY x.DATEDISEMB DESC
										LIMIT 1
										") or die(mysql_error());
			
			$rowvessellist = mysql_fetch_array($qrylastvessel);
			
			if ($rowvessellist["ALIAS1"] != "")
				$lastvessel = $rowvessellist["ALIAS1"];
			else 
				$lastvessel = $rowvessellist["VESSEL"];
			
			if ($rowvessellist["DATEDISEMB"] != "")
				$lastdisemb = date("Y-m-d",strtotime($rowvessellist["DATEDISEMB"]));
			else 
				$lastdisemb = "---";
			
			
			$qryexpasrank = mysql_query("SELECT y.RANKCODE,RANK,ALIAS1,SUM(MONTHS) AS EXPASRANK
										FROM
											(
												SELECT '1' AS VMC,RANKCODE,SUM(DIFF) AS MONTHS
												FROM
												(
													SELECT RANKCODE,ROUND(DATEDIFF(IF(DATECHANGEDISEMB IS NULL,DATEDISEMB,DATECHANGEDISEMB),DATEEMB)/30) AS DIFF
													FROM crewchange where APPLICANTNO=$applicantno
													ORDER BY DATEDISEMB DESC
												) x
												GROUP BY RANKCODE
											
												UNION
												
												SELECT '2' AS VMC,RANKCODE,SUM(DIFF) AS MONTHS
												FROM
												(
													SELECT RANKCODE,ROUND(DATEDIFF(DATEDISEMB,DATEEMB)/30) AS DIFF
													FROM crewexperience where APPLICANTNO=$applicantno
													ORDER BY DATEDISEMB DESC
												) x
												GROUP BY RANKCODE
											) y
										LEFT JOIN rank r ON r.RANKCODE=y.RANKCODE
										WHERE y.RANKCODE='$currentrankcode'
										GROUP BY y.RANKCODE
								") or die(mysql_error());
			
			$rowexpasrank = mysql_fetch_array($qryexpasrank);
			$expcurrentrank = $rowexpasrank["EXPASRANK"];  //NO. OF MONTHS
//			$exprankyears = floor($expcurrentrank / 12);
//			$exprankmonths = $expcurrentrank % 12;


			$qrydocuments = mysql_query("SELECT y.*,r.ALIAS1,r.RANK 
										FROM (
												SELECT cd.DOCCODE AS DOCCODE1,x.*,cd.DOCUMENT
												FROM crewdocuments cd
												LEFT JOIN (SELECT cds.DOCCODE,cds.DOCNO,cds.DATEISSUED,cds.DATEEXPIRED,cds.RANKCODE
															FROM crewdocstatus cds
															WHERE cds.DOCCODE IN ('18','27','F2','41','P7')
															AND cds.APPLICANTNO=$applicantno
														) x ON x.DOCCODE=cd.DOCCODE
												WHERE cd.DOCCODE IN ('18','27','F2','41','P7')
											) y
										LEFT JOIN rank r ON r.RANKCODE=y.RANKCODE
										
										UNION
										
										SELECT y.*,r.ALIAS1,r.RANK 
										FROM (
												SELECT cd.DOCCODE AS DOCCODE1,x.*,cd.DOCUMENT
												FROM crewdocuments cd
												LEFT JOIN (SELECT ccs.DOCCODE,ccs.DOCNO,ccs.DATEISSUED,ccs.GRADE,ccs.RANKCODE
															FROM crewcertstatus ccs
															WHERE ccs.DOCCODE IN ('40','49')
															AND ccs.APPLICANTNO=$applicantno
														) x ON x.DOCCODE=cd.DOCCODE
												WHERE cd.DOCCODE IN ('40','49')
											) y
										LEFT JOIN rank r ON r.RANKCODE=y.RANKCODE
										
										") or die(mysql_error());
			

				while($rowdocuments= mysql_fetch_array($qrydocuments))
				{
					$doc1 = $rowdocuments["DOCCODE"];
					
					if ($rowdocuments["DATEEXPIRED"] != "")
						$expiredshow = date("m-d-Y",strtotime($rowdocuments["DATEEXPIRED"]));
					else 
						$expiredshow = "---";
						
					if ($rowdocuments["DATEISSUED"] != "")
						$issuedshow = date("m-d-Y",strtotime($rowdocuments["DATEISSUED"]));
					else 
						$issuedshow = "---";
					
					switch ($doc1)
					{
						case "18": // SP COC
								$coc_grade = $rowdocuments["RANKCODE"];
								$coc_expiry = $expiredshow;
							break;
						case "27"://GOC
								$goc_expiry = $expiredshow;
							break;
						case "F2"://SEAMAN BOOK
								$sbook_expiry = $expiredshow;
								$sbookno = $rowdocuments["DOCNO"];
							break;
						case "41"://PASSPORT
								$passport_expiry = $expiredshow;
								$passportno = $rowdocuments["DOCNO"];
							break;
						case "40"://STCW
								$stcw_issued = $issuedshow;
								$stcwno = $rowdocuments["DOCNO"];
							break;
						case "49"://SSO
								$sso_issued = $issuedshow;
								$ssnono = $rowdocuments["DOCNO"];
//						case "P7"://MEDICAL PEME
//								$medical_issued = $issuedshow;
//								$medicalno = $rowdocuments["DOCNO"];
//							break;
					}								
				}
				
				
			$qrymedical = mysql_query("SELECT DATECHECKUP FROM crewmedical WHERE APPLICANTNO=$applicantno ORDER BY DATECHECKUP DESC LIMIT 1") or die(mysql_error());
			$rowmedical = mysql_fetch_array($qrymedical);
			
			if ($rowmedical["DATECHECKUP"] != "")
				$medical = date("m-d-Y",strtotime($rowmedical["DATECHECKUP"]));
			else 
				$medical = "---";
			
				
		echo "
			<tr class=\"$css\">
				<td class=>$cnt.</td>
				<td class= align=\"center\">$rankalias</td>
				<td class=><a href=\"javascript:openWindow('crewdatasheet.php?applicantno=$applicantno', 'crewsheet$cnt', 1000, 650);\">$name</a></td>
				<td class= align=\"center\">&nbsp;$nationality</td>
				<td class= align=\"center\">$bdate</td>
				<td class= align=\"center\">$age</td>
				<td class= align=\"center\">$signon</td>
				<td class= align=\"center\">$days</td>
				<td class= align=\"center\">$lastvessel</td>
				<td class= align=\"center\">$lastdisemb</td>
				<td class= align=\"center\">$expcurrentrank</td>
				<td class= align=\"center\">$coc_grade</td>
		";
		
				if (checkpath("$basedirdocs/$applicantno/L/18.pdf"))
					echo "<td align=\"center\"><a href=\"javascript:openWindow('$basedirdocs/$applicantno/L/18.pdf', 'showdoc18$cnt', 900, 600);\">$coc_expiry</a></td>";
				else if (checkpath("$basedirdocs/$applicantno/L/C0.pdf"))
						echo "<td align=\"center\"><a href=\"javascript:openWindow('$basedirdocs/$applicantno/L/C0.pdf', 'showdoc18$cnt', 900, 600);\">$coc_expiry</a></td>";
					else 
						echo "<td align=\"center\">$coc_expiry</td>";
				
				if (checkpath("$basedirdocs/$applicantno/D/27.pdf"))					
					echo "<td align=\"center\"><a href=\"javascript:openWindow('$basedirdocs/$applicantno/D/27.pdf', 'showdoc27$cnt', 900, 600);\">$goc_expiry</a></td>";
				else 
					echo "<td align=\"center\">$goc_expiry</td>";
				
				if (checkpath("$basedirdocs/$applicantno/C/40.pdf"))
					echo "<td align=\"center\"><a href=\"javascript:openWindow('$basedirdocs/$applicantno/C/40.pdf', 'showdoc40$cnt', 900, 600);\">$stcw_issued</a></td>";
				else 
					echo "<td align=\"center\">$stcw_issued</td>";
				
				if (checkpath("$basedirdocs/$applicantno/D/F2.pdf"))
					echo "<td align=\"center\"><a href=\"javascript:openWindow('$basedirdocs/$applicantno/D/F2.pdf', 'showdocF2$cnt', 900, 600);\">$sbook_expiry</a></td>";
				else 
					echo "<td align=\"center\">$sbook_expiry</td>";
					
				if (checkpath("$basedirdocs/$applicantno/D/41.pdf"))
					echo "<td align=\"center\"><a href=\"javascript:openWindow('$basedirdocs/$applicantno/D/41.pdf', 'showdoc41$cnt', 900, 600);\">$passport_expiry</a></td>";
				else 
					echo "<td align=\"center\">$passport_expiry</td>";
					
				if (checkpath("$basedirdocs/$applicantno/D/P7.pdf"))
					echo "<td align=\"center\"><a href=\"javascript:openWindow('$basedirdocs/$applicantno/D/P7.pdf', 'showdocP7$cnt', 900, 600);\">$medical</a></td>";
				else 
					echo "<td align=\"center\">$medical</td>";
					
				if (checkpath("$basedirdocs/$applicantno/C/49.pdf"))
					echo "<td align=\"center\"><a href=\"javascript:openWindow('$basedirdocs/$applicantno/C/49.pdf', 'showdoc49$cnt', 900, 600);\">$sso_issued</a></td>";
				else 
					echo "<td align=\"center\">$sso_issued</td>";
					
				if (checkpath("$basedirdocs/$applicantno/D/F2.pdf"))
					echo "<td align=\"center\"><a href=\"javascript:openWindow('$basedirdocs/$applicantno/D/F2.pdf', 'showdocF2$cnt', 900, 600);\">$sbookno</a></td>";
				else
					echo "<td align=\"center\">$sbookno</td>";

			$cnt++;
		}
					
	echo "
			</tr>
	</table>
	<br />
	";
	
	if (checkpath("$basedirrotation/$vesselcode.pdf"))
		echo "
			<input type=\"button\" value=\"View Crew Rotation\" onclick=\"javascript:openWindow('$basedirrotation/$vesselcode.pdf', 'showdocF2$cnt', 900, 600);\" />
		";
		
	echo "
	</div>
	<input type=\"hidden\" name=\"actiontxt\" />
</form>

</body>

</html>
";
	
?>