<?php

$datenow=date("Y-m-d");
if(isset($_POST["crewsortby"]))
	$crewsortby=$_POST["crewsortby"];
//else 
//	$crewsortby="cw.FNAME,cf.FNAME";
//	$crewsortby="OVERDUE1,BATCHNO,RANKINGFINAL,DISEMBFINAL";
	$crewsortby="OVERDUE1,BATCHNO,DISEMBFINAL,RANKINGFINAL,NAME";
	
if(isset($_POST["creworderby"]))
	$creworderby=$_POST["creworderby"];
else 
	$creworderby="";

$dateformat = "dMY";
$dateformat2 = "m/d/Y";

$wheredepart = "AND (c.DEPMNLDATE IS NULL OR IF(ccp.CCID IS NULL,c.ARRMNLDATE,ccp.ARRMNLDATE) IS NULL ";
$wheredepart .= "OR (CURRENT_DATE <= 
	IF(ccp.CCID IS NULL,IF(c.DATECHANGEDISEMB IS NULL,c.DATEDISEMB,c.DATECHANGEDISEMB),
	IF(ccp.DATECHANGEDISEMB IS NULL,ccp.DATEDISEMB,ccp.DATECHANGEDISEMB))))";
include("qrylistplan.inc");

echo "
<div style=\"width:610px;height:630px;background:White;float:left;\">

	<div class=\"navbar\" style=\"width:618px;height:35px;overflow:hidden;\">
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
	
	<div style=\"width:618px;height:580px;overflow:auto;\">
	
		<table id=\"crewonboardheader\" style=\"font-size:0.8em;font-weight:Bold;border-top:1px solid black;border-right:1px solid black;table-layout:fixed;\" cellspacing=\"0\" cellpadding=\"1\">
			<tr style=\"display:none;\">\n
				<td style=\"width:30px;\">&nbsp;</td>\n
				<td style=\"width:50px;\">&nbsp;</td>\n
				<td style=\"width:210px;\">&nbsp;</td>\n
				<td style=\"width:80px;\">&nbsp;</td>\n
				<td style=\"width:80px;\">&nbsp;</td>\n
<!--				<td style=\"width:100px;\">&nbsp;</td>\n	-->
				<td style=\"width:100px;\">&nbsp;</td>\n
				<td style=\"width:50px;\">&nbsp;</td>\n
			</tr>
	";
				
	$stylehdr = "font-size:1em;color:Black;text-align:center;border-right:1px solid Black;border-bottom:1px solid Black;";

	echo "
			<tr>\n
				<td style=\"$stylehdr\">&nbsp;</td>\n
				<td style=\"$stylehdr\" >RANK</td>\n
				<td style=\"$stylehdr\" >NAME</td>\n
				<td style=\"$stylehdr\" >EMBARKED DATE</td>\n
				<td style=\"$stylehdr\" >E.O.C.</td>\n
<!--				<td style=\"$stylehdr\">EMBARKED/PORT</td>\n	-->
				<td style=\"$stylehdr\">REASON</td>\n
				<td style=\"$stylehdr\">&nbsp;</td>\n
			</tr>
		</table>
	
		<table id=\"crewonboarddetails\" style=\"font-size:0.8em;font-weight:Bold;border-top:1px solid black;border-right:1px solid black;table-layout:fixed;\" cellspacing=\"0\" cellpadding=\"3\">
			<tr style=\"display:none;\">\n
				<td style=\"width:30px;\">&nbsp;</td>\n
				<td style=\"width:50px;\">&nbsp;</td>\n
				<td style=\"width:210px;\">&nbsp;</td>\n
				<td style=\"width:80px;\">&nbsp;</td>\n
				<td style=\"width:80px;\">&nbsp;</td>\n
<!--				<td style=\"width:100px;\">&nbsp;</td>\n	-->
				<td style=\"width:100px;\">&nbsp;</td>\n
				<td style=\"width:50px;\">&nbsp;</td>\n
			</tr>";
		$cntdata=0;
		$datebatchtemp="";
		$batchno=0;
		$position=0;
		$overduetemp=0;
		while($rowlistplan=mysql_fetch_array($qrylistplan))
		{
			$disembfinal=$rowlistplan["DISEMBFINAL"];
			$batchno=$rowlistplan["BATCHNO"];
			$datedisembchanged=$rowlistplan["DATEDISEMBCHANGED"];
			$getoverdue=$rowlistplan["OVERDUE1"];
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

			if($batchno!=$batchnotemp && $getoverdue!=0)
			{
				if($batchno==1)
					$batchnoshow=$batchno."st";
				elseif ($batchno==2)
					$batchnoshow=$batchno."nd";
				elseif ($batchno==3)
					$batchnoshow=$batchno."rd";
				elseif ($batchno==999 || $batchno==0)
					$batchnoshow="NO";
				else
					$batchnoshow=$batchno."th";
					
				$nextbatch=$batchno+1;
				$prevbatch=$batchno-1;
				echo "
				<tr>\n
					<td colspan=\"7\" style=\"$styleheader;background:black;color:yellow;font-size:1em;\">
						$batchnoshow BATCH&nbsp;&nbsp;&nbsp;";
						if($batchnoshow!="NO")
						{
							echo "						
							<span style=\"color:red;cursor:pointer;\" 
								onclick=\"if(confirm('Are you sure you want to CLEAR?')){actiontxt.value='batchchange';batchfrom.value='$batchno';batchto.value='999';submit();}\">Clr</span>&nbsp;&nbsp;&nbsp;
							<span style=\"color:red;cursor:pointer;\" 
								onclick=\"if(confirm('Are you sure you want to Move Up?')){actiontxt.value='batchchange';batchfrom.value='$batchno';batchto.value='$prevbatch';submit();}\">Up</span>&nbsp;&nbsp;&nbsp;
							<span style=\"color:red;cursor:pointer;\" 
								onclick=\"if(confirm('Are you sure you want to Move Down?')){actiontxt.value='batchchange';batchfrom.value='$batchno';batchto.value='$nextbatch';submit();}\">Down</span>
							";
						}
					echo "
					</td>\n
				</tr>";
//				$overduetemp=1;
			}
			$ccid=$rowlistplan["CCIDFINAL"];
			$tagpromote=$rowlistplan["TAGPROMOTE"];
			$ccidemb=$rowlistplan["CCIDEMB"];
			$applicantno=$rowlistplan["APPLICANTNO"];
			$embapplicantno=$rowlistplan["EMBAPPLICANTNO"];
			$rankcode=$rowlistplan["RANKCODE"];
			$embrankcode=$rowlistplan["EMBRANKCODE"];
			$alias2=$rowlistplan["ALIAS2"];
			$embalias2=$rowlistplan["EMBALIAS2"];
			$name=$rowlistplan["NAME"];
			
			$dateemb=$rowlistplan["DATEEMB"];
			$dateembshow=date($dateformat,strtotime($dateemb));
			$dateembshow2=date($dateformat2,strtotime($dateemb));
			
			$datedisemb=$rowlistplan["DATEDISEMB"];
			$datedisembshow=date($dateformat,strtotime($datedisemb));
			$datedisembshow2=date($dateformat2,strtotime($datedisemb));
			
			$dateembfuture=date($dateformat,strtotime($datedisemb));
			$datedisembfuture=date($dateformat, strtotime("$datedisemb + 9 month"));
			$embcountry=$rowlistplan["PORTCOUNTRY"];
			$embport=$rowlistplan["PORT"];
			$port=$embport.", ".$embcountry;
			$embname=$rowlistplan["EMBNAME"];
			$embdateemb=$rowlistplan["EMBDATEEMB"];
			$vesseltypecode=$rowlistplan["VESSELTYPECODE"];
			$vesselname=$rowlistplan["VESSEL"];
			$disembreason=$rowlistplan["REASON"];
			$disembreasoncode=$rowlistplan["DISEMBREASONCODE"];
			
			$arrmnldate=$rowlistplan["ARRMNLDATE"];
			if(!empty($arrmnldate))
			{
				$arrmnldateshow=date($dateformat,strtotime($arrmnldate));
				$arrmnldateshow2=date($dateformat2,strtotime($arrmnldate));
			}
			else 
			{
				$arrmnldateshow="";
				$arrmnldateshow2="";
			}
			$depmnldate=$rowlistplan["DEPMNLDATE"];
			if(!empty($depmnldate))
			{
				$depmnldateshow=date($dateformat,strtotime($depmnldate));
				$depmnldateshow2=date($dateformat2,strtotime($depmnldate));
			}
			else 
			{
				$depmnldateshow="";
				$depmnldateshow2="";
			}
			
			if(!empty($embdateemb))
				$embdateembshow=date("M 'y",strtotime($embdateemb));
			else 
				$embdateembshow="";
				
			$embdatedisemb=$rowlistplan["EMBDATEDISEMB"];
			$embdateembjs=date($dateformat,strtotime($embdateemb));
			$embdatedisembjs=date($dateformat,strtotime($embdatedisemb));
			$embembcountry=$rowlistplan["EMBPORTCOUNTRY"];
			$embembport=$rowlistplan["EMBPORT"];
//			$embport=$embembport.", ".$embembcountry;
			
			//IF PROMOTED
			$ccidpromote = $rowlistplan["CCIDPROMOTE"];
			$alias2promote = $rowlistplan["ALIAS2PROMOTE"];
			$dateembpromote = date($dateformat,strtotime($rowlistplan["DATEEMBPROMOTE"]));
			$datedisembpromote = $rowlistplan["DATEDISEMBPROMOTE"];
			$arrmnldatepromote =$rowlistplan["ARRMNLDATEPROMOTE"];
			$titlerem="";
			$datedisembtag="";
			$datedisembtitle1="";
			if(!empty($alias2promote))
			{
				$titlerem="($alias2 until $datedisembshow only)";
				$disembreason="Promoted ".$titlerem;
				$alias2=$alias2promote;
				$datedisembshow=date($dateformat,strtotime($datedisembpromote));
				$ccid=$ccidpromote;
				if(empty($arrmnldatepromote))
				{
					$arrmnldateshow="";
					$arrmnldateshow2="";
				}
				else 
				{
					$arrmnldateshow=date($dateformat,strtotime($arrmnldatepromote));
					$arrmnldateshow2=date($dateformat2,strtotime($arrmnldatepromote));
				}
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
//			if(strtotime($currentdate1)<strtotime($dateembshow))
//				$colorblue="color:blue";
//			else 
//				$colorblue="";
//			if(strtotime($currentdate1)>strtotime($datedisembshow))
//				$colorred="color:red";
//			else 
//				$colorred="";
				

					//ADDED BY GPA -- TO CHECK IF CURRENT CCID IS PREVIOUSLY PROMOTED.
					$qrycheckpromote = mysql_query("SELECT CCID FROM crewpromotionrelation WHERE CCIDPROMOTE=$ccid") or die(mysql_error());
					
					if (mysql_num_rows($qrycheckpromote) > 0)
						$ccidpromoted = 1;
					else 
						$ccidpromoted = 0;
					//END GPA
				
					// POSITION -- CREW ONBOARD BASED ON EMBARK DATE/HAS ALREADY DEPARTED MANILA/PREVIOUSLY PROMOTED ONBOARD
					if(date("Y-m-d",strtotime($dateemb))<=$datenow && date("Y-m-d",strtotime($datedisemb))>=$datenow && (!empty($depmnldate) || $ccidpromoted==1))
					{
						$position++;
						$getposition=$position;
					}
					else 
						$getposition="";
						
					//SHADING -- CREW ONBOARD BASED ON EMBARK DATE ONLY
					if (date("Y-m-d",strtotime($dateemb))<=$datenow && date("Y-m-d",strtotime($datedisemb))>=$datenow)
						$styleshade = "background-color:#D2D0FB;";
					else 
						$styleshade = "";
				
				
//			if(date("Y-m-d",strtotime($dateemb))<=$datenow && date("Y-m-d",strtotime($disembfinal))>=$datenow)
//			{
//				$position++;
//				$getposition=$position;
//			}
//			else 
//				$getposition="";
				
//			$position++;
//			$getposition=$position;

			if(!empty($getposition))
			{
				$putonclickalias2="onclick=\"if(chkbtnloading()==1){document.getElementById('changebatch').style.display='block';changebatchdisplay.value='1';crewselect.value='$name';currentbatch.value='$batchnoshow';batchselect.value='$batchno';ccidhidden.value='$ccid';}\"";
			}
			else 
			{
				$putonclickalias2="onclick=\"alert('Crew is NOT On-board!');\"";
				
			}
			
//			$styledtls = "border-bottom:1px dashed Gray;border-right:1px solid Gray;font-size:0.8em;";

			$styledtls = "border-bottom:1px dashed Gray;border-right:1px solid Gray;";
			
			if (empty($depmnldate) && $ccidpromoted==0)
				$styledepart = "font-weight:Bold;color:Red;";
			else 
				$styledepart = "";
			
			if(empty($rowlistplan["CHKPROMOTE"]) && empty($arrmnldateshow))
//			if (empty($rowlistplan["CHKPROMOTE"]))
			{//getcrewname,getdateemb,getdatedisemb,getembarkport,getdepdate,getarrdate,getpnidate,getpnireason
			echo "
				<tr style=\"border-top: 1px solid gray;$styleshade\" $mouseovereffect>\n
					<td title=\"$ccid - $getbatchno Click to select...\" style=\"cursor:pointer;text-align:center;$styledtls;\" 
							 onclick=\"fillcrewinfo('$ccid','$name','$dateembshow2','$datedisembshow2','$port',
							 		'$depmnldateshow2','$arrmnldateshow2','$pnidate','$pnireason','$embcountry','$embport','$tagpromote','$disembreasoncode');\">
						&lt;&lt;</td>\n
					<td style=\"text-align:center;$styledtls\" title=\"$rankcode\">
						<input id=\"noprint\" type=\"button\" value=\"$alias2\" title=\"Click to change batch no...\"
							$putonclickalias2
							style=\"border:0;background:inherit;cursor:pointer;font-weight:Bold;color:Navy;\">\n</td>\n
					<td style=\"$styledtls$styledepart\">
						<span style=\"cursor:pointer;\" title=\"$ccid-$applicantno\" 
							onclick=\"crewonboardhidden.value='$name';applicantnohidden.value='$applicantno';
								actionajax.value='viewonboard201';createurl(1);\">$name</span>
					</td>\n
					<td style=\"text-align:center;$styledtls\">&nbsp;$dateembshow</td>\n
					<td style=\"text-align:center;$styledtls\" title=\"$datedisembtitle1\">&nbsp;$datedisembshow$datedisembtag</td>\n
<!--					<td style=\"$styledtls\">&nbsp;$port</td>\n		-->
					<td style=\"$styledtls\" title=\"$titlerem$titlepni\">&nbsp;$disembreason $placepni</td>\n	
					<td style=\"$styledtls\" align=\"center\">&nbsp;
						<input type=\"button\" style=\"font-size:0.9em;color:red;font-weight:Bold;border:1px solid Silver;background-color:Black\" 
							value=\"CER\" onclick=\"openWindow('crewevaluation.php?ccid=$ccid', 'cer' ,0, 0);\" />
					</td>\n
				</tr>";
				$cntdata++;
			}
			$datebatchtemp=$datebatch;
			$batchnotemp=$batchno;
			$getbatchnotemp=$getbatchno;
			$yesterdaytemp=$yesterday;
		}
		
		echo "
		</table>
	</div>
</div>
<div id=\"changebatch\"
	style=\"background:silver;z-index:200;position:absolute;left:600px;top:100px;width:400px;height:180px;
		border:3px solid black;display:$batchdisplay;\">
	<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
		<tr>
			<td width=\"90%\"><span class=\"wintitle\">CHANGE BATCH NO</span></td>
		</tr>
	</table>
	<br>
	<center>
	<table class=\"editrow\" cellspacing=\"0\" cellpadding=\"0\">
		<tr>
			<th>Crew.</th>
			<td>&nbsp;
				<input type=\"text\" id=\"crewselect\" name=\"crewselect\" size=\"30\" 
					style=\"border:0;background:inherit;font-weight:bold;color:blue;\" readOnly=\"readOnly\">
			</td>
		</tr>
		<tr>
			<th>Current Batch.</th>
			<td>&nbsp;
				<input type=\"text\" id=\"currentbatch\" name=\"currentbatch\" size=\"12\" 
					style=\"border:0;background:inherit;font-weight:bold;color:blue;\" readOnly=\"readOnly\">
			</td>
		</tr>
		<tr>
			<th>Select Batch.</th>
			<td>&nbsp;
				<select name=\"batchselect\" style=\"\" 
					onchange=\"chkchangebatch();\">
					<option value=\"\">-Select-</option>
					<option value=\"0\">No Batch</option>";
					for($i=1;$i<=20;$i++)
					{
						if($i==1)
							$batchlistshow="1st";
						elseif($i==2)
							$batchlistshow="2nd";
						elseif($i==3)
							$batchlistshow="3rd";
						else 
							$batchlistshow=$i."th";
						echo "<option value=\"$i\">$batchlistshow</option>\n";
					}
			echo "
				</select>
			</td>
		</tr>
		<tr>
			<th>&nbsp;</th>
		</tr>
		<tr>
			<th>&nbsp;</th>
			<td>
				<img id=\"btnchangebatchclose\" src=\"images/buttons/btn_close_def.gif\" 
						style=\"cursor:pointer;\"
						onmousedown=\"this.src='images/buttons/btn_close_click.gif';\"
						onmouseup=\"this.src='images/buttons/btn_close_def.gif';\"
						onmouseout=\"this.src='images/buttons/btn_close_def.gif';\"
						onclick=\"document.getElementById('changebatch').style.display='none';changebatchdisplay.value='';\">
			</td>
		</tr>
	</table>
	</center>
</div>
<input type=\"hidden\" name=\"crewsortby\" value=\"$crewsortby\">
<input type=\"hidden\" name=\"creworderby\" value=\"$creworderby\">
<input type=\"hidden\" name=\"crewonboardhidden\">
<input type=\"hidden\" name=\"applicantnohidden\">
<input type=\"hidden\" name=\"changebatchdisplay\">

<input type=\"hidden\" name=\"ccidhidden\" value=\"$ccidhidden\">
";
?>