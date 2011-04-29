<?php

if(isset($_POST["crewsortby"]))
	$crewsortby=$_POST["crewsortby"];
//else 
	$crewsortby="cw.FNAME,cf.FNAME";
	
if(isset($_POST["creworderby"]))
	$creworderby=$_POST["creworderby"];
else 
	$creworderby="";
	
$wheredepart = "AND (c.DEPMNLDATE IS NULL OR IF(ccp.CCID IS NULL,c.ARRMNLDATE,ccp.ARRMNLDATE) IS NULL ";
$wheredepart .= "OR (CURRENT_DATE <= 
	IF(ccp.CCID IS NULL,IF(c.DATECHANGEDISEMB IS NULL,c.DATEDISEMB,c.DATECHANGEDISEMB),IF(ccp.DATECHANGEDISEMB IS NULL,ccp.DATEDISEMB,ccp.DATECHANGEDISEMB))))";
include("qrylistplan.inc");

echo "
<div style=\"width:610px;height:650px;background:Silver;float:left;\">

	<div class=\"navbar\" style=\"width:618px;height:50px;overflow:hidden;\">
		<table cellspacing=\"0\" cellpadding=\"1\">\n
			<tr>
				<td style=\"width:30px;\">
					&nbsp;&nbsp;&nbsp;Vessel
				</td>
				<td style=\"width:220px;\">
					<select name=\"vesselcode\" style=\"width:200px;\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onchange=\"actiontxt.value='reset';submit();\">\n
						<option value=\"\">-Select-</option>";
						while($rowvessellist=mysql_fetch_array($qryvessellist))
						{
							$vessel1=$rowvessellist['VESSEL'];
							$vesselcode1=$rowvessellist['VESSELCODE'];
							$vesseltypecode1=$rowvessellist['VESSELTYPECODE'];
							if($vesselcode1==$vesselcode)
								$selected="selected";
							else
								$selected="";
							echo "<option $selected value=\"$vesselcode1\">$vessel1 ($vesseltypecode1)</option>\n";
						}
				echo "
					</select>
				</td>
			</tr>
		</table>
	</div>
	
	<div style=\"width:618px;height:600px;overflow:auto;\">
	
		<table id=\"crewonboardheader\" style=\"border-top:1px solid black;border-right:1px solid black;table-layout:fixed;\" cellspacing=\"0\" cellpadding=\"0\">
			<tr height=\"30px\" style=\"display:none;\">\n
				<td style=\"width:20px;\">&nbsp;</td>\n
				<td style=\"width:39px;\">&nbsp;</td>\n
				<td style=\"width:160px;\">&nbsp;</td>\n
				<td style=\"width:70px;\">&nbsp;</td>\n
				<td style=\"width:70px;\">&nbsp;</td>\n
				<td style=\"width:100px;\">&nbsp;</td>\n
				<td style=\"width:100px;\">&nbsp;</td>\n
				<td style=\"width:40px;\">&nbsp;</td>\n
			</tr>
			<tr height=\"30px\">\n
				<td style=\"cursor:pointer;$styleheader\">&nbsp;</td>\n
				<td style=\"cursor:pointer;$styleheader\" onClick=\"chksort('r.RANKING');\">RANK</td>\n
				<td style=\"cursor:pointer;$styleheader\" onClick=\"chksort('cw.FNAME');\">NAME</td>\n
				<td style=\"cursor:pointer;$styleheader\" onClick=\"chksort('c.DATEEMB');\">EMBARKED DATE</td>\n
				<td style=\"cursor:pointer;$styleheader\" onClick=\"chksort('c.DATEDISEMB');\">E.O.C.</td>\n
				<td style=\"$styleheader\">EMBARKED/PORT</td>\n
				<td style=\"$styleheader\">REASON</td>\n
				<td style=\"$styleheader\">&nbsp;</td>\n
			</tr>
		</table>
	
		<table id=\"crewonboarddetails\" style=\"border-top:1px solid black;border-right:1px solid black;table-layout:fixed;\" cellspacing=\"0\" cellpadding=\"0\">
			<tr height=\"30px\" style=\"display:none;\">\n
				<td style=\"width:20px;\">&nbsp;</td>\n
				<td style=\"width:39px;\">&nbsp;</td>\n
				<td style=\"width:160px;\">&nbsp;</td>\n
				<td style=\"width:70px;\">&nbsp;</td>\n
				<td style=\"width:70px;\">&nbsp;</td>\n
				<td style=\"width:100px;\">&nbsp;</td>\n
				<td style=\"width:100px;\">&nbsp;</td>\n
				<td style=\"width:40px;\">&nbsp;</td>\n
			</tr>";
		$cntdata=0;
		$classtype="odd";
		while($rowlistplan=mysql_fetch_array($qrylistplan))
		{
			$datedisemb=$rowlistplan["DATEDISEMB"];
			$datedisembchanged=$rowlistplan["DATEDISEMBCHANGED"];
			if(!empty($datedisembchanged))
			{
				$datechangeby=$rowlistplan["DATECHANGEBY"];
				$datechangedate=date("d M Y",strtotime($rowlistplan["DATECHANGEDATE"]));
				$datedisembtag="*";
				$datedisembtitle1="Date ".$datedisembchanged."\nBy: ".$datechangeby."\nOn: ".$datechangedate;
			}
			else 
			{
				$datedisembtag="";
				$datedisembtitle1="";
			}
			$ccid=$rowlistplan["CCID"];
			$ccidemb=$rowlistplan["CCIDEMB"];
			$applicantno=$rowlistplan["APPLICANTNO"];
			$embapplicantno=$rowlistplan["EMBAPPLICANTNO"];
			$rankcode=$rowlistplan["RANKCODE"];
			$embrankcode=$rowlistplan["EMBRANKCODE"];
			$alias2=$rowlistplan["ALIAS2"];
			$embalias2=$rowlistplan["EMBALIAS2"];
			$name=$rowlistplan["NAME"];
			$dateemb=$rowlistplan["DATEEMB"];
			$dateembshow=date("m/d/Y",strtotime($dateemb));
			$datedisembshow=date("m/d/Y",strtotime($datedisemb));
			$dateembfuture=date("m/d/Y",strtotime($datedisemb));
			$datedisembfuture=date("m/d/Y", strtotime("$datedisemb + 9 month"));
			$embcountry=$rowlistplan["PORTCOUNTRY"];
			$embport=$rowlistplan["PORT"];
			$port=$embport.", ".$embcountry;
			$embname=$rowlistplan["EMBNAME"];
			$embdateemb=$rowlistplan["EMBDATEEMB"];
			$vesseltypecode=$rowlistplan["VESSELTYPECODE"];
			$vesselname=$rowlistplan["VESSEL"];
			$disembreason=$rowlistplan["REASON"];
			$arrmnldate=$rowlistplan["ARRMNLDATE"];
			if(!empty($arrmnldate))
				$arrmnldateshow=date("m/d/Y",strtotime($arrmnldate));
			else 
				$arrmnldateshow="";
			$depmnldate=$rowlistplan["DEPMNLDATE"];
			if(!empty($depmnldate))
				$depmnldateshow=date("m/d/Y",strtotime($depmnldate));
			else 
				$depmnldateshow="";
			if(!empty($embdateemb))
				$embdateembshow=date("M 'y",strtotime($embdateemb));
			else 
				$embdateembshow="";
			$embdatedisemb=$rowlistplan["EMBDATEDISEMB"];
			$embdateembjs=date("m/d/Y",strtotime($embdateemb));
			$embdatedisembjs=date("m/d/Y",strtotime($embdatedisemb));
			$embembcountry=$rowlistplan["EMBPORTCOUNTRY"];
			$embembport=$rowlistplan["EMBPORT"];
			$embport=$embembport.", ".$embembcountry;
			
			//IF PROMOTED
			$ccidpromote = $rowlistplan["CCIDPROMOTE"];
			$alias2promote = $rowlistplan["ALIAS2PROMOTE"];
			$dateembpromote = date('m/d/Y',strtotime($rowlistplan["DATEEMBPROMOTE"]));
			$datedisembpromote = date('m/d/Y',strtotime($rowlistplan["DATEDISEMBPROMOTE"]));
			$arrmnldatepromote =$rowlistplan["ARRMNLDATEPROMOTE"];
			$titlerem="";
			$datedisembtag="";
			$datedisembtitle1="";
			if(!empty($alias2promote))
			{
				$titlerem="($alias2 until $datedisembshow only)";
				$disembreason="Promoted ".$titlerem;
				$alias2=$alias2promote;
				$datedisembshow=$datedisembpromote;
				$ccid=$ccidpromote;
				if(empty($arrmnldatepromote))
					$arrmnldateshow="";
				else 
					$arrmnldateshow=date("m/d/Y",strtotime($arrmnldatepromote));
				$datedisembpromote=$rowlistplan["DATEDISEMBPROMOTE"];
				$datedisembchangedpromote=$rowlistplan["DATEDISEMBCHANGEDPROMOTE"];
				if(!empty($datedisembchangedpromote))
				{
					$datechangeby=$rowlistplan["DATECHANGEBYPROMOTE"];
					$datechangedate=date("d M Y",strtotime($rowlistplan["DATECHANGEDATEPROMOTE"]));
					$datedisembtag="*";
					$datedisembtitle1="Date ".$datedisembchanged."\nBy: ".$datechangeby."\nOn: ".$datechangedate;
				}
			}
			
			//GET PNI
			$qrycrewpni = mysql_query("SELECT DATEINJURED,REASON FROM crewinjury WHERE CCID=$ccid") or die(mysql_error());
			$pnidate = "";
			$titlepni = "";
			$pnireason = "";
			$placepni = "";
			if (mysql_num_rows($qrycrewpni) > 0)
			{
				$rowcrewpni = mysql_fetch_array($qrycrewpni);
				$pnireason = $rowcrewpni["REASON"];
				if ($rowcrewpni["DATEINJURED"])
				{
					$pnidate = date('m/d/Y',strtotime($rowcrewpni["DATEINJURED"]));
					$titlepni = "\nPNI Date: $pnidate\nReason: $pnireason";
					$placepni = "PNI";
				}
			}
			if(strtotime($currentdate1)<strtotime($dateembshow))
				$colorblue="color:blue";
			else 
				$colorblue="";
			if(strtotime($currentdate1)>strtotime($datedisembshow))
				$colorred="color:red";
			else 
				$colorred="";
			if(empty($rowlistplan["CHKPROMOTE"]) && empty($arrmnldateshow))
			{//getcrewname,getdateemb,getdatedisemb,getembarkport,getdepdate,getarrdate,getpnidate,getpnireason
			echo "
				<tr style=\"border-top: 1px solid gray;$colorblue;$colorred;\" $mouseovereffect class=\"$classtype\">\n
					<td title=\"$ccid - Click to select...\" style=\"cursor:pointer;text-align:center;$styledetails\" 
							 onclick=\"fillcrewinfo('$ccid','$name','$dateembshow','$datedisembshow','$port',
							 		'$depmnldateshow','$arrmnldateshow','$pnidate','$pnireason');\">
						&lt;&lt;</td>\n
					<td style=\"text-align:center;$styledetails\" title=\"$rankcode\">&nbsp;$alias2</td>\n
					<td style=\"$styledetails\">
						<span style=\"cursor:pointer;\" title=\"$ccid-$applicantno\" 
							onclick=\"crewonboardhidden.value='$name';applicantnohidden.value='$applicantno';
								actionajax.value='viewonboard201';createurl(1);\">$name</span>
					</td>\n
					<td style=\"text-align:center;$styledetails\">&nbsp;$dateembshow</td>\n
					<td style=\"text-align:center;$styledetails\" title=\"$datedisembtitle1\">&nbsp;$datedisembshow$datedisembtag</td>\n
					<td style=\"$styledetails\">&nbsp;$port</td>\n
					<td style=\"$styledetails\" title=\"$titlerem$titlepni\">&nbsp;$disembreason $placepni</td>\n	
					<td style=\"$styledetails\" align=\"left\">&nbsp;
						<input type=\"button\" style=\"font-size:0.85em;color:red;font-weight:Bold;border:1px solid Silver;background-color:Black\" 
							value=\"CER\" onclick=\"openWindow('crewevaluation.php?ccid=$ccid', 'cer' ,0, 0);\" />
					</td>\n
				</tr>";
				if($classtype=="even") { $classtype="odd";} else { $classtype="even"; }
				$cntdata++;
			}
		}
		
		echo "
		</table>
	</div>
</div>
<input type=\"hidden\" name=\"crewsortby\" value=\"$crewsortby\">
<input type=\"hidden\" name=\"creworderby\" value=\"$creworderby\">
<input type=\"hidden\" name=\"crewonboardhidden\">
<input type=\"hidden\" name=\"applicantnohidden\">

<input type=\"hidden\" name=\"ccidhidden\" value=\"$ccidhidden\">
";
?>