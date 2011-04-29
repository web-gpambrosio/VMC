<?php

if(isset($_POST["crewsortby"]))
	$crewsortby=$_POST["crewsortby"];
else 
	$crewsortby="r.RANKING";
	
if(isset($_POST["creworderby"]))
	$creworderby=$_POST["creworderby"];
else 
	$creworderby="";
include("qrylistplan.inc");

echo "
<div style=\"width:60%;height:600;background:yellow;float:left;\">
	<table class=\"navbar\" cellspacing=\"0\" cellpadding=\"1\">\n
		<tr>
			<td style=\"width:30px;\">
				&nbsp;&nbsp;&nbsp;Vessel
			</td>
			<td class=\"given\" style=\"width:220px;\">
				<select name=\"vesselcode\" style=\"width:200px;\"
					onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
					onchange=\"submit();\">\n
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
	<table id=\"crewonboardheader\" style=\"border-top:1px solid black;border-right:1px solid black;table-layout:fixed;\" cellspacing=\"0\" cellpadding=\"0\">
		<tr height=\"30px\" style=\"display:none;\">\n
			<td style=\"width:25px;\">&nbsp;</td>\n
			<td style=\"width:39px;\">&nbsp;</td>\n
			<td style=\"width:165px;\">&nbsp;</td>\n
			<td style=\"width:70px;\">&nbsp;</td>\n
			<td style=\"width:70px;\">&nbsp;</td>\n
			<td style=\"width:125px;\">&nbsp;</td>\n
			<td style=\"width:125px;\">&nbsp;</td>\n
		</tr>
		<tr height=\"30px\">\n
			<td style=\"cursor:pointer;$styleheader\">&nbsp;</td>\n
			<td style=\"cursor:pointer;$styleheader\" onClick=\"chksort('r.RANKING');\">RANK</td>\n
			<td style=\"cursor:pointer;$styleheader\" onClick=\"chksort('cw.FNAME');\">NAME</td>\n
			<td style=\"cursor:pointer;$styleheader\" onClick=\"chksort('c.DATEEMB');\">EMBARKED DATE</td>\n
			<td style=\"cursor:pointer;$styleheader\" onClick=\"chksort('c.DATEDISEMB');\">E.O.C.</td>\n
			<td style=\"$styleheader\">EMBARKED/PORT</td>\n
			<td style=\"$styleheader\">REASON</td>\n
		</tr>
	</table>
	<div style=\"width:100%;height:400px;\">
		<table id=\"crewonboarddetails\" style=\"border-top:1px solid black;border-right:1px solid black;table-layout:fixed;\" cellspacing=\"0\" cellpadding=\"0\">
			<tr height=\"30px\" style=\"display:none;\">\n
				<td style=\"width:25px;\">&nbsp;</td>\n
				<td style=\"width:39px;\">&nbsp;</td>\n
				<td style=\"width:165px;\">&nbsp;</td>\n
				<td style=\"width:70px;\">&nbsp;</td>\n
				<td style=\"width:70px;\">&nbsp;</td>\n
				<td style=\"width:125px;\">&nbsp;</td>\n
				<td style=\"width:125px;\">&nbsp;</td>\n
			</tr>";
		$cntdata=0;
		$classtype="odd";
		while($rowlistplan=mysql_fetch_array($qrylistplan))
		{
			$datedisemb=$rowlistplan["DATEDISEMB"];
			$datedisembchanged=$rowlistplan["DATEDISEMBCHANGED"];
			if($datedisembchanged!="0")
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
			$dateembshow=date("d-M-y",strtotime($dateemb));
			$datedisembshow=date("d-M-y",strtotime($datedisemb));
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
			
			echo "
				<tr style=\"border-top: 1px solid gray;\" $mouseovereffect class=\"$classtype\">\n
					<td style=\"cursor:pointer;text-align:center;$styledetails\" title=\"Click to select...\"
							 onclick=\"crewname.value='$name';embdate.value='$dateembshow';disembdate.value='$datedisembshow';\">&lt;&lt;</td>\n
					<td style=\"text-align:center;$styledetails\" title=\"$rankcode\">&nbsp;$alias2</td>\n
					<td style=\"$styledetails\">
						<span style=\"cursor:pointer;\" title=\"$ccid-$applicantno\" 
							onclick=\"crewonboardhidden.value='$name';applicantnohidden.value='$applicantno';actionajax.value='viewonboard201';getvalues();\">$name</span>
					</td>\n
					<td style=\"text-align:center;$styledetails\">&nbsp;$dateembshow</td>\n
					<td style=\"text-align:center;$styledetails\" title=\"$datedisembtitle1\">&nbsp;$datedisembshow$datedisembtag</td>\n
					<td style=\"$styledetails\">&nbsp;$port</td>\n
					<td style=\"$styledetails\">&nbsp;$disembreason</td>\n
				</tr>";
				if($classtype=="even") { $classtype="odd";} else { $classtype="even"; }
			
			$cntdata++;
		}
		
		echo "
	</table>
	</div>
</div>
<input type=\"hidden\" name=\"crewsortby\" value=\"$crewsortby\">
<input type=\"hidden\" name=\"creworderby\" value=\"$creworderby\">
<input type=\"hidden\" name=\"crewonboardhidden\">
<input type=\"hidden\" name=\"applicantnohidden\">";
?>