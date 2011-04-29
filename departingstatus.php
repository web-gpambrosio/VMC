<?php

include("veritas/connectdb.php");
include("veritas/include/stylephp.inc");

$currentdate = date("Y-m-d H:i:s");
$gethttphost=$_SERVER["HTTP_HOST"];

if (isset($_POST["vesselcode"]))
	$vesselcode = $_POST["vesselcode"];

switch ($actiontxt)
{
	case ""	:
			
	break;
}

//get vessel list
$qryvessellist=mysql_query("SELECT VESSELCODE,VESSEL,DIVCODE
	FROM vessel
	WHERE STATUS=1 
	ORDER BY DIVCODE,VESSEL") or die(mysql_error());

if(!empty($vesselcode))
{
	$qryvesseldtl=mysql_query("SELECT CCID,cc.APPLICANTNO,CONCAT(FNAME,', ',GNAME) AS CREWNAME,DATEEMB,r.RANK
		FROM crewchange cc
		LEFT JOIN rank r ON cc.RANKCODE=r.RANKCODE
		LEFT JOIN crew c ON cc.APPLICANTNO=c.APPLICANTNO
		WHERE VESSELCODE='$vesselcode' AND DEPMNLDATE IS NULL
		ORDER BY DATEEMB,r.RANKING") or die(mysql_error());
}

echo "
<html>

<head>
	<title>Departing Status Report</title>
	<script language=\"javascript\" src=\"popcalendar.js\"></script>	
	<script language=\"javascript\" src=\"veripro.js\"></script>
	<script type=\"text/javascript\" src=\"crewchangeplanajax.js\"></script>	
	<script type='text/javascript' src='ajax.js'></script>
	<link rel=\"stylesheet\" href=\"veripro.css\">

<script>
function createurl(z) // 
{
	url = \"http://$gethttphost/veritas/crewchangeplanrequest.php?actionajax=\"; // The server-side script 
	
	var actionajax = document.departingstatus.actionajax.value; 
		
	//fill URL
	var applicantno = '&embapplicantnohidden=' + z;
	fillurl = actionajax + applicantno;
	
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
	if(document.departingstatus.actionajax.value=='viewonboard201')
	{
		onboard201result=results[2];
		onboard201();
	}
}
function onboard201()
{
	var myObject = new Object();
    myObject.getonboard201result = onboard201result;
    myObject.getcrewname = '';
    window.showModalDialog('crewonboard201.php', myObject, 'dialogHeight:650px; dialogWidth:500px;status=no'); 
}
				
</script>
	
</head>

<body>

<form name=\"departingstatus\" method=\"POST\">

	<center>
	<div style=\"width:100%;height:650px;padding:2px;border:1px solid black;\">
	
		<span class=\"wintitle\">DEPARTING STATUS REPORT (BY VESSEL)</span>
		<br />
		<table width=\"50%\" style=\"font-size:0.8em;\">
			<tr>
				<th valign=\"center\" align=\"center\">Vessel</th>
				<td align=\"center\">
					<select name=\"vesselcode\" onchange=\"if(this.value!='$vesselcode'){submit();}\">
						<option value=\"\">-Select-</option>";
						$divcodetmp="";
						while ($rowvessellist=mysql_fetch_array($qryvessellist))
						{
							$vessel=$rowvessellist["VESSEL"];
							$vesselcode1=$rowvessellist["VESSELCODE"];
							$divcode=$rowvessellist["DIVCODE"];
							$divshow="DIV-".$divcode;
							if(empty($divcodetmp) || $divcodetmp!=$divcode)
							{
								echo "<option value=\"\">--------------</option>";
								echo "<option value=\"\">$divshow</option>";
								echo "<option value=\"\">--------------</option>";
							}
							if($vesselcode1==$vesselcode)
								$selected="selected";
							else 
								$selected="";
							echo "<option $selected value=\"$vesselcode1\">$vessel</option>";
							$divcodetmp=$divcode;
						}
						echo "
					</select>
				</td>
				<th valign=\"center\" colspan=\"2\" align=\"left\">
					<input type=\"button\" value=\"Refresh\" onclick=\"submit();\" />			
				</th>
			</tr>
		</table>
		
		<hr />
		
		<div style=\"width:100%;height:550px;background-color:#DCDCDC;margin:3 5 3 5;border:1px solid Black;overflow:auto;\">
			<span class=\"sectiontitle\">CURRENT STATUS OF VESSEL</span>
			
			<table width=\"100%\" cellpadding=\"5\" cellspacing=\"1\" style=\"font-size:0.7em;background-color:#F0F1E9;border:1px solid black;\">
				<tr style=\"font-size:0.1em;\">
					<td width=\"15%\" align=\"center\">&nbsp;</td>
					<td width=\"8%\" align=\"center\">&nbsp;</td>
					<td width=\"8%\" align=\"center\">&nbsp;</td>
					<td width=\"8%\" align=\"center\">&nbsp;</td>
					<td width=\"8%\" align=\"center\">&nbsp;</td>
					<td width=\"8%\" align=\"center\">&nbsp;</td>
					<td width=\"8%\" align=\"center\">&nbsp;</td>
					<td width=\"8%\" align=\"center\">&nbsp;</td>
					<td width=\"8%\" align=\"center\">&nbsp;</td>
					<td width=\"8%\" align=\"center\">&nbsp;</td>
					<td align=\"center\">&nbsp;</td>
				</tr>
				<tr style=\"font-weight:bold;background-color:Black;color:White;\">
					<td rowspan=\"2\" align=\"center\">CREW NAME</td>
					<td rowspan=\"2\" align=\"center\">RANK</td>
					<td rowspan=\"2\" align=\"center\">PRE-DEP CHECKLIST</td>
					<td rowspan=\"2\" align=\"center\">PRE-EMB MEDICAL</td>
					<td colspan=\"4\" align=\"center\">POEA DOCUMENTS</td>
					<td rowspan=\"2\" align=\"center\">VMC CONTRACT</td>
					<td rowspan=\"2\" align=\"center\">TENTATIVE VSL LINE-UP</td>
					<td rowspan=\"2\" align=\"center\">OTHER DOCUMENTS</td>
				</tr>
				<tr style=\"font-weight:bold;background-color:Black;color:White;\">
					<td align=\"center\">CONTRACT</td>
					<td align=\"center\">AMOSUP</td>
					<td align=\"center\">INFO SHT</td>
					<td align=\"center\">RPS</td>
				</tr>
			
		";
		//initialize documents
		$doc1="Z2";
		$doc2="P7";
		$doc3="VC";
		$doc4="Z4";
		$doc5="Z5";
		$doc6="Z6";
		$doc7="Z7";
		$doc8="Z8";
		$doc9="Z9";
		
		$formatdate="dMY";
		$stylespangreen="style=\"font-weight:bold;cursor:pointer;color:green;\"";
		$stylespanred="style=\"font-weight:bold;cursor:pointer;color:red;\"";
		$styledtl = "style=\"font-weight:0.7em;border-bottom:1px solid Gray;border-left:1px dashed Blue;\"";
		$dateembtmp="";
		if(mysql_num_rows($qryvesseldtl)!=0)
		{
			while($rowvesseldtl=mysql_fetch_array($qryvesseldtl))
			{//CREWNAME,DATEEMB,r.RANK
				$ccid=$rowvesseldtl["CCID"];
				$applicantno=$rowvesseldtl["APPLICANTNO"];
				$crewname=$rowvesseldtl["CREWNAME"];
				$dateemb=date("dMY",strtotime($rowvesseldtl["DATEEMB"]));
				$rank=$rowvesseldtl["RANK"];
				
				//initialize doc location
				$docdir="docimages/$applicantno/D/CCID$ccid/";
				
				//get remarks
				$qrydeparthdr=mysql_query("SELECT * FROM departhdr WHERE CCID=$ccid") or die(mysql_error());
				$cntdeparthdr=mysql_num_rows($qrydeparthdr);
				$valdocsrem="<i>[No Remarks]</i>";
				if($cntdeparthdr!=0)
				{
					$rowdeparthdr=mysql_fetch_array($qrydeparthdr);
					
					if(!empty($rowdeparthdr["PREDEPREM"]))
						$predeprem=$rowdeparthdr["PREDEPREM"];
					if(!empty($rowdeparthdr["PREDEPDATE"]))
					{
						$predeprem.="<br><span style=\"color:blue;font-style:italic;\">".$rowdeparthdr["PREDEPBY"]."/";
						$predeprem.=date($formatdate,strtotime($rowdeparthdr["PREDEPDATE"]))."</span>";
					}
					if(!empty($rowdeparthdr["PREEMBREM"]))
						$preembrem=$rowdeparthdr["PREEMBREM"];
					if(!empty($rowdeparthdr["PREEMBDATE"]))
					{
						$preembrem.="<br><span style=\"color:blue;font-style:italic;\">".$rowdeparthdr["PREEMBBY"]."/";
						$preembrem.=date($formatdate,strtotime($rowdeparthdr["PREEMBDATE"]))."</span>";
					}
					if(!empty($rowdeparthdr["CONTRACTREM"]))
						$contractrem=$rowdeparthdr["CONTRACTREM"];
					if(!empty($rowdeparthdr["CONTRACTDATE"]))
					{
						$contractrem.="<br><span style=\"color:blue;font-style:italic;\">".$rowdeparthdr["CONTRACTBY"]."/";
						$contractrem.=date($formatdate,strtotime($rowdeparthdr["CONTRACTDATE"]))."</span>";
					}
					if(!empty($rowdeparthdr["AMOSUPREM"]))
						$amosuprem=$rowdeparthdr["AMOSUPREM"];
					if(!empty($rowdeparthdr["AMOSUPDATE"]))
					{
						$amosuprem.="<br><span style=\"color:blue;font-style:italic;\">".$rowdeparthdr["AMOSUPBY"]."/";
						$amosuprem.=date($formatdate,strtotime($rowdeparthdr["AMOSUPDATE"]))."</span>";
					}
					if(!empty($rowdeparthdr["INFOSHTREM"]))
						$infoshtrem=$rowdeparthdr["INFOSHTREM"];
					if(!empty($rowdeparthdr["INFOSHTDATE"]))
					{
						$infoshtrem.="<br><span style=\"color:blue;font-style:italic;\">".$rowdeparthdr["INFOSHTBY"]."/";
						$infoshtrem.=date($formatdate,strtotime($rowdeparthdr["INFOSHTDATE"]))."</span>";
					}
					if(!empty($rowdeparthdr["RPSREM"]))
						$rpsrem=$rowdeparthdr["RPSREM"];
					if(!empty($rowdeparthdr["RPSDATE"]))
					{
						$rpsrem.="<br><span style=\"color:blue;font-style:italic;\">".$rowdeparthdr["RPSBY"]."/";
						$rpsrem.=date($formatdate,strtotime($rowdeparthdr["RPSDATE"]))."</span>";
					}
					if(!empty($rowdeparthdr["CONSIGNREM"]))
						$consignrem=$rowdeparthdr["CONSIGNREM"];
					if(!empty($rowdeparthdr["CONSIGNDATE"]))
					{
						$consignrem.="<br><span style=\"color:blue;font-style:italic;\">".$rowdeparthdr["CONSIGNBY"]."/";
						$consignrem.=date($formatdate,strtotime($rowdeparthdr["CONSIGNDATE"]))."</span>";
					}
					if(!empty($rowdeparthdr["TENTLINEREM"]))
						$tentlinerem=$rowdeparthdr["TENTLINEREM"];
					if(!empty($rowdeparthdr["TENTLINEDATE"]))
					{
						$tentlinerem.="<br><span style=\"color:blue;font-style:italic;\">".$rowdeparthdr["TENTLINEBY"]."/";
						$tentlinerem.=date($formatdate,strtotime($rowdeparthdr["TENTLINEDATE"]))."</span>";
					}
					if(!empty($rowdeparthdr["VALDOCSREM"]))
						$valdocsrem=$rowdeparthdr["VALDOCSREM"];
					if(!empty($rowdeparthdr["VALDOCSDATE"]))
					{
						$valdocsrem.="<br><span style=\"color:blue;font-style:italic;\">".$rowdeparthdr["VALDOCSBY"]."/";
						$valdocsrem.=date($formatdate,strtotime($rowdeparthdr["VALDOCSDATE"]))."</span>";
					}
				}
				
				
				//check Pre-Departure Checklist
				if(is_file($docdir.$doc1.".pdf"))
					$predep="<span $stylespangreen onclick=\"openWindow('$docdir$doc1','$doc1','500px','600px');\">OK</span>";
				else 
					$predep="<span $stylespanred onclick=\"alert('No PDF file found!');\">NONE</span>";
				
				//check Pre-Embarkation Medical Exam
				if(is_file("docimages/$applicantno/D/$doc2.pdf"))
					$preemb="<span $stylespangreen onclick=\"openWindow('docimages/$applicantno/D/$doc2','$doc2','500px','600px');\">OK</span>";
				else 
					$preemb="<span $stylespanred onclick=\"alert('No PDF file found!');\">NONE</span>";
				
				//check Contract
				if(is_file($docdir.$doc3.".pdf"))
					$contract="<span $stylespangreen onclick=\"openWindow('$docdir$doc3','$doc3','500px','600px');\">OK</span>";
				else 
					$contract="<span $stylespanred onclick=\"openWindow('repdepartingpoea.php?type=1&ccid=$ccid&applicantno=$applicantno&print=1','contract',0,0);\">NONE</span>";
				
				//check AMOSUP Contract
				if(is_file($docdir.$doc4.".pdf"))
					$amosup="<span $stylespangreen onclick=\"openWindow('$docdir$doc4','$doc4','500px','600px');\">OK</span>";
				else 
					$amosup="<span $stylespanred onclick=\"openWindow('repdepartingpoea.php?type=2&ccid=$ccid&applicantno=$applicantno&print=1','amosup',0,0);\">NONE</span>";
				
				//check Information Sheet
				if(is_file($docdir.$doc5.".pdf"))
					$infosht="<span $stylespangreen onclick=\"openWindow('$docdir$doc5','$doc5','500px','600px');\">OK</span>";
				else 
					$infosht="<span $stylespanred onclick=\"openWindow('repdepartingpoea.php?type=3&ccid=$ccid&applicantno=$applicantno&print=1','infosheet',0,0);\">NONE</span>";
				
				//check RPS
				if(is_file($docdir.$doc6.".pdf"))
					$rps="<span $stylespangreen onclick=\"openWindow('$docdir$doc6','$doc6','500px','600px');\">OK</span>";
				else 
					$rps="<span $stylespanred onclick=\"openWindow('repdepartingpoea.php?type=4&ccid=$ccid&applicantno=$applicantno&print=1','infosheet',0,0);\">NONE</span>";
				
				//check Contract Signing
				if(is_file($docdir.$doc7.".pdf"))
					$consign="<span $stylespangreen onclick=\"openWindow('$docdir$doc7','$doc7','500px','600px');\">OK</span>";
				else 
					$consign="<span $stylespanred onclick=\"alert('Wala pa!');\">NONE</span>";
				
				//check Tentative Vessel Line-up
				if(is_file($docdir.$doc8.".pdf"))
					$tentline="<span $stylespangreen onclick=\"openWindow('$docdir$doc8','$doc8','500px','600px');\">OK</span>";
				else 
					$tentline="<span $stylespanred onclick=\"openWindow('repdepartingpoea.php?type=5&ccid=$ccid&applicantno=$applicantno&print=1','infosheet',0,0);\">NONE</span>";
				
				
				
				if ($dateembtmp != $dateemb)
				{
					echo "
					<tr>
						<td style=\"font-size:1.2em;font-weight:Bold;color:Yellow;background-color:Green;\" colspan=\"11\"><u>$dateemb</u></td>
					</tr>
					";
				}
				
				echo "	
				<tr $mouseovereffect style=\"cursor:pointer;\" align=\"center\">
					<td $styledtl valign=\"top\" align=\"left\" 
						onclick=\"actionajax.value='viewonboard201';createurl('$applicantno');\">$crewname</td>
					<td $styledtl valign=\"top\">$rank</td>
					<td $styledtl valign=\"top\">$predep<br>$predeprem</td>
					<td $styledtl valign=\"top\">$preemb<br>$preembrem</td>
					<td $styledtl valign=\"top\">$contract<br>$contractrem</td>
					<td $styledtl valign=\"top\">$amosup<br>$amosuprem</td>
					<td $styledtl valign=\"top\">$infosht<br>$infoshtrem</td>
					<td $styledtl valign=\"top\">$rps<br>$rpsrem</td>
					<td $styledtl valign=\"top\">$consign<br>$consignrem</td>
					<td $styledtl valign=\"top\">$tentline<br>$tentlinerem</td>
					<td $styledtl valign=\"top\">$valdocsrem</td>
				</tr>";
				$dateembtmp=$dateemb;
			}
		}
		echo "
			</table>
		</div>
	</div>

	<input type=\"hidden\" name=\"actiontxt\">
	<input type=\"hidden\" name=\"actionajax\">
</form>";
		
include("veritas/include/ajaxprogress.inc");
echo "
	
<form name=\"repdebriefingstatus\" action=\"repdebriefingstatus.php\" target=\"repdebriefingstatus\" method=\"POST\">
	<input type=\"hidden\" name=\"transdatefrom\">
	<input type=\"hidden\" name=\"transdateto\">
	<input type=\"hidden\" name=\"status2\">
</form>

</body>
</html>
";

?>