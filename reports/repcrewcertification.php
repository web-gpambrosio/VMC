<?php
// $kups = "gino";
include('veritas/connectdb.php');
//include('connectdb.php');

session_start();



include('../include/stylephp.inc');

$datetimenow=date("Y-m-d H:i:s");
$datenow=date("Y-m-d");
$datenowshow=date("d M Y");
$issueddate=date("jS")." day of ".date("F Y");
$tablewidth="808px";
if (isset($_GET['applicantno']))
	$applicantno = $_GET['applicantno'];

if (isset($_GET['endorsement']))
	$endorsement = $_GET['endorsement'];



//get vessel details
$qrylist=mysql_query("SELECT cc.CCID,r.ALIAS1 AS RANK,v.VESSEL,LEFT(v.FLAGCODE,4) AS FLAGCODE,v.VESSELTYPECODE,v.GROSSTON,vs.ENGINEPOWER,v.TRADEROUTECODE,cc.DATEEMB,
	IF(cpr.CCID IS NULL,IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB),IF(cc1.DATECHANGEDISEMB IS NULL,cc1.DATEDISEMB,cc1.DATECHANGEDISEMB)) AS DATEDISEMB,
	IF(cpr.CCID IS NULL,'','Promoted') AS PROMOTED
	FROM crewchange cc
	LEFT JOIN crewpromotionrelation cpr ON cc.CCID=cpr.CCID
	LEFT JOIN crewchange cc1 ON cpr.CCIDPROMOTE=cc1.CCID
	LEFT JOIN crewpromotionrelation cpr1 ON cc.CCID=cpr1.CCIDPROMOTE
	LEFT JOIN vessel v ON cc.VESSELCODE=v.VESSELCODE
	LEFT JOIN vesselspecs vs ON v.VESSELCODE=vs.VESSELCODE
	LEFT JOIN rank r ON cc.RANKCODE=r.RANKCODE
	WHERE cc.APPLICANTNO=$applicantno AND cpr1.CCID IS NULL
  	AND cc.DEPMNLDATE IS NOT NULL") or die(mysql_error());

$style="font-size:12pt;font-family:Times New Roman;font-weight:bold;";

$option1 = "This is to certify further that the above officer is presently onboard to our manned vessels which is not equipped with Fast Rescue Boats and ECDIS.";
$option2 = "This is to certify further that the above officer is presently onboard to our manned vessels which is not equipped with Fast Rescue Boats and ECDIS, and will join our manned vessel not equipped with ECDIS-Fast Rescue Boats.";
$option3 = "This is to certifies that while onboard, he was a part of the navigational watchkeeping duties, ship simulator and duties, ship simulator and bridge teamwork management.";
$option4 = "This Certification is being issued upon the request of the above named seafarer for whatever legal purpose it may serve.";

$issuedby="CAPT. ANDRES F. ALVARO";
$issuedposition="General Manager";

$qryname=mysql_query("SELECT CONCAT(GNAME,' ',LEFT(MNAME,1),'. ',FNAME) AS NAME,DOCNO,BIRTHDATE
	FROM crew c 
	LEFT JOIN crewdocstatus cds ON c.APPLICANTNO=cds.APPLICANTNO
	WHERE c.APPLICANTNO=$applicantno AND cds.DOCCODE='F2'
	ORDER BY cds.DATEEXPIRED DESC") or die(mysql_error());
$rowname=mysql_fetch_array($qryname);
$crewname=$rowname["NAME"];
$seamansno=$rowname["DOCNO"];
$birthdate=$rowname["BIRTHDATE"];
$birthdateshow=date("F d, Y",strtotime($birthdate));
$fontstyle="font-family:Times New Roman;";

if($endorsement==1)
{
	$gettitle="ENDORSEMENT";
	$standardformat="This is to endorse <b>$crewname</b> who had been an employee of our <b>Company, VERITAS MARITIME CORPORATION</b>, to <b>avail of his AJSU - Retirement Benefit with AMOSUP</b>.  He was not contracted to board on any of our AJSU manned vessels for Radio Officer was phase out.  For your guidance find details of his employment:";
	//for age format
	$getdiff=strtotime($datenow)-strtotime($birthdate);
	$getage=floor($getdiff/(60*60*24*365.25));
	$ageformat="This is to endorse <b>$crewname</b> who had been an employee of our <b>Company, VERITAS MARITIME CORPORATION</b>, on a contractual basis, to <b>avail of his AJSU - Retirement Benefit with AMOSUP</b>.  He is now ". numtowords($getage). "($getage) years old with birthdate $birthdateshow.  For your guidance find details of his employment:";
}
else 
	$gettitle="CERTIFICATION";
	

function numtowords($inputnumber)
{
	$splitnumber=explode(".",$inputnumber);
	$leftdec=$splitnumber[0];
	$rightdec=$splitnumber[1];

	$length = strlen($leftdec);
    $nopckgrev=strrev($leftdec);

    $chr1="X";
    $chr2="X";
    $chr3="X";
    $chr4="X";
    $chr5="X";
    $chr6="X";
    $chr7="X";
    $strWORD="";
    if($length>=1)
    	$chr1=mb_strimwidth($nopckgrev,0,1);
    if($length>=2)
    {
		$chr2=mb_strimwidth($nopckgrev,1,1);
		$chr20=$chr2."0";
		#echo $chr20;
    }
	if($length>=3)
		$chr3=mb_strimwidth($nopckgrev,2,1);
	if($length>=4)
		$chr4=mb_strimwidth($nopckgrev,3,1);
	if($length>=5)
	{
		$chr5=mb_strimwidth($nopckgrev,4,1);
        $chr50=$chr5."0";
	}
	if($length>=6)
	{
		$chr6=mb_strimwidth($nopckgrev,5,1);
        $chr60=$chr6."00";
	}
	if($length>=7)
		$chr7=mb_strimwidth($nopckgrev,6,1);
	if($chr7<>"X")
    {
    	$qryinwords=mysql_query("SELECT * FROM inwords WHERE innumber=$chr7")  or die(mysql_error());
		$rowinwords=mysql_fetch_array($qryinwords);
		$strWORD=$strWORD." ".$rowinwords['INWORDS']." "."MILLION ";
    }
	if($chr6<>"X")
    {
	   	switch ($chr6)
		{
			case '0':
			break;
			default:
				$qryinwords=mysql_query("SELECT * FROM inwords WHERE innumber=$chr6")  or die(mysql_error());
			    $rowinwords=mysql_fetch_array($qryinwords);
			    $strWORD=$strWORD." ".$rowinwords['INWORDS']." "."HUNDRED ";
			break;
		}
	}
	if($chr5<>"X")
    {
	   	switch ($chr5)
		{
			case '1':
		    	$qryinwords=mysql_query("SELECT * FROM inwords WHERE innumber=$chr5$chr4")  or die(mysql_error());
				$rowinwords=mysql_fetch_array($qryinwords);
				$strWORD=$strWORD.$rowinwords['INWORDS'];
			break;
		    case '0':
		    	$qryinwords=mysql_query("SELECT * FROM inwords WHERE innumber=$chr4")  or die(mysql_error());
				$rowinwords=mysql_fetch_array($qryinwords);
				$strWORD=$strWORD.$rowinwords['INWORDS'];
			break;
	        default:
				$qryinwords=mysql_query("SELECT * FROM inwords WHERE innumber=$chr50")  or die(mysql_error());
				$rowinwords=mysql_fetch_array($qryinwords);
				$strWORD=$strWORD.$rowinwords['INWORDS'];

				$qryinwords=mysql_query("SELECT * FROM inwords WHERE innumber=$chr4")  or die(mysql_error());
				$rowinwords=mysql_fetch_array($qryinwords);
				$strWORD=$strWORD." ".$rowinwords['INWORDS'];
			break;
		}
		$strWORD=$strWORD." "."THOUSAND";
		$chr4="X";
    }
    if($chr4<>"X")
    {
    	$chr4=mb_strimwidth($nopckgrev,3,1);
    	$qryinwords=mysql_query("SELECT * FROM inwords WHERE innumber=$chr4")  or die(mysql_error());
        $rowinwords=mysql_fetch_array($qryinwords);
        $strWORD=$strWORD." ".$rowinwords['INWORDS']." "."THOUSAND";
    }

    if($chr3<>"X")
    {
    	switch ($chr3)
		{
			case '0':
			break;
			default:
			    $qryinwords=mysql_query("SELECT * FROM inwords WHERE innumber=$chr3")  or die(mysql_error());
			    $rowinwords=mysql_fetch_array($qryinwords);
			    $strWORD=$strWORD." ".$rowinwords['INWORDS']." "."HUNDRED";
			break;
		}
    }

    if($chr2<>"X")
    {
		switch ($chr2)
		{
			case '1':
			    $qryinwords=mysql_query("SELECT * FROM inwords WHERE innumber=$chr2$chr1")  or die(mysql_error());
				$rowinwords=mysql_fetch_array($qryinwords);
				$strWORD=$strWORD." ".$rowinwords['INWORDS'];
				#echo "SELECT * FROM inwords WHERE innumber=$chr2.$chr1";
				#echo "YYY".$strWORD."xxx".$chr2.$chr1;
			break;
	        case '0':
			    $qryinwords=mysql_query("SELECT * FROM inwords WHERE innumber=$chr1")  or die(mysql_error());
				$rowinwords=mysql_fetch_array($qryinwords);
				$strWORD=$strWORD." ".$rowinwords['INWORDS'];
			break;
			default:
				$qryinwords=mysql_query("SELECT * FROM inwords WHERE innumber=$chr20")  or die(mysql_error());
				$rowinwords=mysql_fetch_array($qryinwords);
				$strWORD=$strWORD." ".$rowinwords['INWORDS'];

				$qryinwords=mysql_query("SELECT * FROM inwords WHERE innumber=$chr1")  or die(mysql_error());
				$rowinwords=mysql_fetch_array($qryinwords);
				$strWORD=$strWORD." ".$rowinwords['INWORDS'];
			break;
		}
		$chr1="X";
	}

    if($chr1<>"X")
    {
    	$qryinwords=mysql_query("SELECT * FROM inwords WHERE innumber=$chr1")  or die(mysql_error());
		$rowinwords=mysql_fetch_array($qryinwords);
		$strWORD=$strWORD." ".$rowinwords['INWORDS'];
    }
    if($rightdec!="" && $rightdec!=null)
    	$strWORD=$strWORD." & $rightdec/100 ONLY";
    else
    	$strWORD=strtolower($strWORD);
    return $strWORD;
}
	
echo	"<html>\n
<head>\n
<link rel=\"StyleSheet\" type=\"text/css\" href=\"../veripro.css\" />
<style>
#noprint
{
	display: none;
}

</style>
<style type='text/css'>
@media print 
{
	#PAScreenOut { display: none; }
	#PAPrintOut { display: block; }
}
@media screen 
{
	#PAScreenOut { display: block; }
	#PAPrintOut { display: none; }
}
</style>
<script>

</script>\n

</head>\n

<body onload=\"\" style=\"\">\n

<form name=\"repcrewcertification\" id=\"repcrewcertification\" method=\"POST\">\n
	<table id=\"PAScreenOut\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\" style=\"table-layout:fixed;border:1px Black Solid;\">
		<tr height=\"40px\" style=\"text-align:center;valign:middle;\">\n
			<td style=\"$style text-align:left;width:25px;\">&nbsp;</td>\n
			<td style=\"$style text-align:left;width:130px;\">RANK</td>\n
			<td style=\"$style text-align:left;width:200px;\">VESSEL</td>\n
			<td style=\"$style text-align:center;width:50px;\">FLAG</td>\n
			<td style=\"$style text-align:center;width:48px;\">TYPE</td>\n
			<td style=\"$style text-align:center;width:120px;\">GRT / KW</td>\n
			<td style=\"$style text-align:center;width:60px;\">TRADE ROUTE</td>\n
			<td style=\"$style text-align:left;width:200px;\">INCLUSIVE DATE</td>\n
		</tr>
	";
	
	$format = "dMY";
	$cntdata=1;
	while($rowlist=mysql_fetch_array($qrylist))
	{
		$ccid=$rowlist["CCID"];
		$rank=$rowlist["RANK"];
		$promoted=$rowlist["PROMOTED"];
		$vessel=$rowlist["VESSEL"];
		$flagcode=$rowlist["FLAGCODE"];
		$vesseltypecode=$rowlist["VESSELTYPECODE"];
		$grosston = number_format($rowlist["GROSSTON_SBOOK"],0);
		$enginepower=$rowlist["ENGINEPOWER"];
		$kw = number_format($enginepower*0.746,0);
		$traderoutecode=$rowlist["TRADEROUTECODE"];
		$dateemb=date($format,strtotime($rowlist["DATEEMB"]));
		
		if(!empty($rowlist["DATEDISEMB"]))
			$datedisemb=date($format,strtotime($rowlist["DATEDISEMB"]));
		else 
			$datedisemb="Present";
		
		
		echo "
		<tr height=\"23px\" style=\"valign:middle;\">\n
			<td style=\"$style2 text-align:center;\">
				<input type=\"checkbox\" checked onclick=\"if(this.checked){document.getElementById('$ccid').style.display='block'}else{document.getElementById('$ccid').style.display='none'};\">
			</td>\n
			<td style=\"$style2 text-align:left;\">$rank $promoted</td>\n
			<td style=\"$style2 text-align:left;\">$vessel</td>\n
			<td style=\"$style2 text-align:center;\">$flagcode</td>\n
			<td style=\"$style2 text-align:center;\">$vesseltypecode</td>\n
			<td style=\"$style2 text-align:center;\">$grosston / $kw</td>\n
			<td style=\"$style2 text-align:center;\">$traderoutecode</td>\n
			<td style=\"$style2 text-align:left;\">
				<input type=\"text\" value=\"$dateemb - $datedisemb\" size=\"25\" onblur=\"document.getElementById('inc$ccid').value=this.value;\">
			</td>\n
		</tr>";
		if($endorsement==1)
		{
			$addrow.="
			<tr height=\"23px\" style=\"valign:middle;\" id=\"$ccid\">\n
				<td style=\"$style2 text-align:left;\">$rank $promoted</td>\n
				<td style=\"$style2 text-align:left;\">$vessel</td>\n
				<td style=\"$style2 text-align:center;\">$flagcode</td>\n
				<td style=\"$style2 text-align:left;\">
					<input type=\"text\" style=\"border:0;$fontstyle;font-size:12pt;\" id=\"inc$ccid\" value=\"$dateemb - $datedisemb\" size=\"25\">
				</td>\n
			</tr>";
		}
		else 
		{
			$addrow.="
			<tr height=\"23px\" style=\"valign:middle;\" id=\"$ccid\">\n
				<td style=\"$style2 text-align:left;\">$rank $promoted</td>\n
				<td style=\"$style2 text-align:left;\">$vessel</td>\n
				<td style=\"$style2 text-align:center;\">$flagcode</td>\n
				<td style=\"$style2 text-align:center;\">$vesseltypecode</td>\n
				<td style=\"$style2 text-align:center;\">$grosston / $kw</td>\n
				<td style=\"$style2 text-align:center;\">$traderoutecode</td>\n
				<td style=\"$style2 text-align:left;\">
					<input type=\"text\" style=\"border:0;$fontstyle;font-size:12pt;\" id=\"inc$ccid\" value=\"$dateemb - $datedisemb\" size=\"25\">
				</td>\n
			</tr>";
		}
		$cntdata++;
	}

echo "
	</table>
	";
	if($endorsement==1)
	{
		echo "
		<div id=\"PAScreenOut\" style=\"width:100%;border:1px Solid Black;\">
			<input type=\"radio\" name=\"certoption\" checked=\"checked\" onclick=\"document.getElementById('chooseformat').innerHTML='$standardformat'\" value=\"Standard\">&nbsp;Standard&nbsp;&nbsp;&nbsp;&nbsp;
			<input type=\"radio\" name=\"certoption\" onclick=\"document.getElementById('chooseformat').innerHTML='$ageformat'\" value=\"Age\">&nbsp;Age<br>
		</div>";
	}
	else 
	{
		echo "
		<div id=\"PAScreenOut\" style=\"width:100%;border:1px Solid Black;\">
			<input type=\"radio\" name=\"certoption\" checked=\"checked\" onclick=\"chosenoption.value=this.value;document.getElementById('chosenoptionspan').innerHTML=this.value;chosenoption.disabled='';\" value=\"$option1\">&nbsp;$option1<br>
			<input type=\"radio\" name=\"certoption\" onclick=\"chosenoption.value=this.value;document.getElementById('chosenoptionspan').innerHTML=this.value;chosenoption.value=this.value;document.getElementById('extrarowspan').innerHTML=extrarow.value;chosenoption.disabled='';\" value=\"$option2\">&nbsp;$option2<br>
			<input type=\"radio\" name=\"certoption\" onclick=\"chosenoption.value=this.value;document.getElementById('chosenoptionspan').innerHTML=this.value;document.getElementById('extrarowspan').innerHTML=extrarow.value;chosenoption.disabled='';\" value=\"$option3\">&nbsp;$option3<br>
			<input type=\"radio\" name=\"certoption\" onclick=\"chosenoption.value=this.value;document.getElementById('chosenoptionspan').innerHTML='';document.getElementById('extrarowspan').innerHTML=this.value;chosenoption.disabled='true';\" value=\"$option4\">&nbsp;$option4<br>
			
			<textarea name=\"chosenoption\" cols=\"100%\" rows=\"4\" onblur=\"document.getElementById('chosenoptionspan').innerHTML=this.value;\">$option1</textarea>
			<input type=\"hidden\" name=\"extrarow\" value=\"This certification is being issued upon the request of the above-mentioned seaman for PRC Requirement (STCW Endorsement).\">
		</div>";
	}
	echo "
	<br>
	<div id=\"PAScreenOut\" style=\"width:100%;border:1px Solid Black;\">
		<!--
		Issued on:&nbsp;<input type=\"text\" name=\"issueddate\" onblur=\"document.getElementById('issueddatespan').innerHTML=this.value;\" value=\"$issueddate\" size=\"30\"><br>
		-->
		Issued by:&nbsp;<input type=\"text\" name=\"issuedby\" onblur=\"document.getElementById('issuedbyspan').innerHTML=this.value;\" value=\"$issuedby\" size=\"50\"><br>
		Position:&nbsp;<input type=\"text\" name=\"issuedposition\" onblur=\"document.getElementById('issuedpositionspan').innerHTML=this.value;\" value=\"$issuedposition\" size=\"50\">
	</div><br>
	
	
	
	<div id=\"PAPrintOut\" style=\"$fontstyle;\">
		<span style=\"font-weight:bold;text-align:center;width:$tablewidth;font-size:16pt;\">$gettitle</span>
		<br><br>";
		if($endorsement==1)
		{
			echo "
			<span style=\"width:$tablewidth;\" id=\"chooseformat\">$standardformat</span>";
		}
		else 
		{
			echo "
			<span style=\"font-weight:bold;width:$tablewidth;\">TO WHOM IT MAY CONCERN,</span>
			<br><br>
			<span style=\"width:$tablewidth;\">This is to certify that <b>$crewname</b> holder of Seaman's Book Number <b>$seamansno</b>
				has served onboard vessel of the Company under the following details:
			</span>";
		}
		if($endorsement==1)
		{
			echo "
			<br><br>
			<center>
			<table cellspacing=\"1\" cellpadding=\"1\" style=\"table-layout:fixed;\">
				<tr height=\"40px\" style=\"text-align:center;valign:middle;\">\n
					<td style=\"$style text-align:left;width:160px;\">RANK</td>\n
					<td style=\"$style text-align:left;width:200px;\">VESSEL</td>\n
					<td style=\"$style text-align:center;width:100px;\">FLAG</td>\n
					<td style=\"$style text-align:left;width:250px;\">INCLUSIVE DATE</td>\n
				</tr>
				$addrow
			</table>
			</center>";
		}
		else 
		{
			echo "
			<br><br>
			<table cellspacing=\"1\" cellpadding=\"1\" style=\"table-layout:fixed;\">
				<tr height=\"40px\" style=\"text-align:center;valign:middle;\">\n
					<td style=\"$style text-align:left;width:130px;\">RANK</td>\n
					<td style=\"$style text-align:left;width:200px;\">VESSEL</td>\n
					<td style=\"$style text-align:center;width:50px;\">FLAG</td>\n
					<td style=\"$style text-align:center;width:48px;\">TYPE</td>\n
					<td style=\"$style text-align:center;width:120px;\">GRT / KW</td>\n
					<td style=\"$style text-align:center;width:60px;\">TRADE ROUTE</td>\n
					<td style=\"$style text-align:left;width:200px;\">INCLUSIVE DATE</td>\n
				</tr>
				$addrow
			</table>";
		}
		echo "
		
		<br><br>
		";
		if($endorsement==1)
		{
			echo "
			<span style=\"width:$tablewidth;\">This endorsement is being issued upon the request of <b>$crewname</b> for the above-mentioned purposes.
			</span>";
		}
		else 
		{
			echo "
			<span id=\"chosenoptionspan\" style=\"width:$tablewidth;\">$option1</span><br><br>
			<span id=\"extrarowspan\" style=\"width:$tablewidth;\">This certification is being issued upon the request of the above-mentioned seaman for PRC Requirement (STCW Endorsement).</span>";
		}
		
		echo "
		<br><br>
		<span style=\"width:$tablewidth;\">Issued this <b id=\"issueddatespan\">$issueddate</b> in the City of Manila, Philippines.</span>
		<br><br>
		<span style=\"width:$tablewidth;text-align:right;\"><span style=\"width:300px;text-align:center;\"><b>VERITAS MARITIME CORPORATION</b></span></span>
		<br>
		<span style=\"width:$tablewidth;text-align:right;\"><span style=\"width:300px;text-align:left;\"><b>By :</b></span></span>
		<br><br><br>
		<span style=\"width:$tablewidth;text-align:right;\"><span style=\"width:300px;text-align:left;border-bottom:1px Solid Black;\">&nbsp;</span></span>
		<br>
		<span style=\"width:$tablewidth;text-align:right;\"><span id=\"issuedbyspan\" style=\"width:300px;text-align:center;\"><b>$issuedby</b></span></span>
		<br>
		<span style=\"width:$tablewidth;text-align:right;\"><span id=\"issuedpositionspan\" style=\"width:300px;text-align:center;\">$issuedposition</span></span>
		<br><br><br><br>
		";
		if($endorsement!=1)
		{
			echo "
			<span style=\"font-size:8pt;\"><i>Not valid without Company Seal.</i></span><br>
			<span style=\"font-size:8pt;\"><i>Erasures and/or alterations</i></span><br>
			<span style=\"font-size:8pt;\"><i>will render this Certification</i></span><br>
			<span style=\"font-size:8pt;\"><i>Null and void</i></span><br>";
		}
		echo "
	</div>
</form>";
include('veritas/include/printclose.inc');
echo "
</body>\n
</html>\n";

?>
