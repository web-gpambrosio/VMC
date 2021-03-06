<?php

//FOR crewchangeplanrequest.php ONLY!
if (!empty($embapplicantnohidden))
	$applicantno = $embapplicantnohidden;



$assignedrankcode = "---";
$assignedrankalias = "---";
$assignedvesselcode = "---";
$assignedvessel = "---";
$assignedetd = "---";

$lastrankcode = "---";
$lastrankalias = "---";
$lastvesselcode = "---";
$lastvessel = "---";
$lastdisembdate = "---";



if (!empty($applicantno))
{
	$disableprint = "";
	
	$qryallexperience = mysql_query("SELECT IF (cpr.CCIDPROMOTE IS NULL,0,1) AS PROMOTED,x.* FROM
									(
										SELECT '1' AS POS,cc.CCID,CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,CREWCODE,FNAME,GNAME,MNAME,
										LEFT(v.VESSEL,10) AS VESSEL,v.VESSEL AS VESSELNAME,v.VESSELCODE,r.ALIAS1 AS RANKALIAS,r.RANKCODE,
										cc.DATEEMB,IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB) AS DATEDISEMB,cc.DISEMBREASONCODE,dr.REASON,
										cc.ARRMNLDATE,cc.DEPMNLDATE,c.STATUS AS ACTIVE,
										IF(DATEDIFF(CURRENT_DATE,IF(cc.DATECHANGEDISEMB IS NULL,cc.DATEDISEMB,cc.DATECHANGEDISEMB)) > 365,1,0) AS INACTIVE,
										IF (f.FASTTRACK IS NULL,0,1) AS FASTTRACK, IF (s.DESCRIPTION IS NULL,0,1) AS SCHOLAR,c.UTILITY,
										f.FASTTRACK AS FTDESC,s.DESCRIPTION AS SCHOLARDESC
										FROM crewchange cc
										LEFT JOIN crew c ON c.APPLICANTNO=cc.APPLICANTNO
										LEFT JOIN vessel v ON v.VESSELCODE=cc.VESSELCODE
										LEFT JOIN rank r ON r.RANKCODE=cc.RANKCODE
										LEFT JOIN disembarkreason dr ON dr.DISEMBREASONCODE=cc.DISEMBREASONCODE
										LEFT JOIN crewscholar cs ON cs.APPLICANTNO=c.APPLICANTNO
										LEFT JOIN scholar s ON s.SCHOLASTICCODE=cs.SCHOLASTICCODE
										LEFT JOIN crewfasttrack cf ON cf.APPLICANTNO=c.APPLICANTNO
										LEFT JOIN fasttrack f ON f.FASTTRACKCODE=cf.FASTTRACKCODE
										WHERE cc.APPLICANTNO=$applicantno
										
										UNION
										
										SELECT '2' AS POS,ce.IDNO,CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME,CREWCODE,FNAME,GNAME,MNAME,
										LEFT(ce.VESSEL,10) AS VESSEL,ce.VESSEL AS VESSELNAME,NULL,r.ALIAS1 AS RANKALIAS,r.RANKCODE,
										DATEEMB,DATEDISEMB,ce.DISEMBREASONCODE,dr.REASON,NULL,NULL,c.STATUS AS ACTIVE,0 AS INACTIVE,
										0,0,0,NULL,NULL
										FROM crewexperience ce
										LEFT JOIN crew c ON c.APPLICANTNO=ce.APPLICANTNO
										LEFT JOIN rank r ON r.RANKCODE=ce.RANKCODE
										LEFT JOIN disembarkreason dr ON dr.DISEMBREASONCODE=ce.DISEMBREASONCODE
										WHERE ce.APPLICANTNO=$applicantno
									) x
									LEFT JOIN crewpromotionrelation cpr ON cpr.CCIDPROMOTE=x.CCID
									ORDER BY x.DATEDISEMB DESC
									LIMIT 5
								") or die(mysql_error());
	
	$cntexp = mysql_num_rows($qryallexperience);
	if ($cntexp > 0)
	{
		$cnt = 0;
		$vmcexp = 0;
		$content = "";
		$appstatus = "";
		$hehe=0;
		while ($rowallexperience = mysql_fetch_array($qryallexperience))
		{
			if ($rowallexperience["POS"] == "1")
			{
				$zexptype = "Veritas";
				$vmcexp = 1;
			}
			else 
				$zexptype = "Outside VMC";
				
			$crewname = $rowallexperience["NAME"];
			
			$crewutility = $rowallexperience["UTILITY"];
			
			if ($crewutility == 1)
				$crewutilityshow = "( UTILITY )";
			else
				$crewutilityshow = "";
			
			$crewfasttrack = $rowallexperience["FASTTRACK"];
			$crewscholar = $rowallexperience["SCHOLAR"];
			
			if (!empty($rowallexperience["FTDESC"]))
				$crewftdesc = "( " . $rowallexperience["FTDESC"] . " )";
			else
				$crewftdesc = "";
				
			if (!empty($rowallexperience["SCHOLARDESC"]))
				$crewscholardesc = "( " . $rowallexperience["SCHOLARDESC"] . " )";
			else
				$crewscholardesc = "";
			
			
			if (empty($crewname))
			{
				$crewfname = $rowallexperience["FNAME"];
				$crewgname = $rowallexperience["GNAME"];
				$crewmname = $rowallexperience["MNAME"];
				
				$crewname .= $crewfname . ", " . $crewgname . " " . $crewmname;
			}
			$crewcode = $rowallexperience["CREWCODE"];
				
			$zccid = $rowallexperience["CCID"];
			$zcrewcode = $rowallexperience["CREWCODE"];
			$zvessel = $rowallexperience["VESSEL"];
			$zvesselcode = $rowallexperience["VESSELCODE"];
			$zrankalias = $rowallexperience["RANKALIAS"];
			$zrankcode = $rowallexperience["RANKCODE"];
			$zpromoted = $rowallexperience["PROMOTED"];
			$zvesselname = $rowallexperience["VESSELNAME"];
			
			if (!empty($rowallexperience["DATEEMB"]))
				$zdateemb = date("dMY",strtotime($rowallexperience["DATEEMB"]));
			else 
				$zdateemb = "";
				
			if (!empty($rowallexperience["DATEDISEMB"]))
				$zdatedisemb = date("dMY",strtotime($rowallexperience["DATEDISEMB"]));
			else 
				$zdatedisemb = "";
				
			if (!empty($rowallexperience["ARRMNLDATE"]))
				$zarrmnldate = date("dMY",strtotime($rowallexperience["ARRMNLDATE"]));
			else 
				$zarrmnldate = "";
				
			if (!empty($rowallexperience["DEPMNLDATE"]))
				$zdepmnldate = date("dMY",strtotime($rowallexperience["DEPMNLDATE"]));
			else 
				$zdepmnldate = "";
				
			$zdisembreasoncode = $rowallexperience["DISEMBREASONCODE"];
			$zreason = $rowallexperience["REASON"];
			$zactive = $rowallexperience["ACTIVE"];
			$zinactive = $rowallexperience["INACTIVE"];
			
			
			if ($cnt == 0)
			{
				if ($zexptype == "Veritas")
				{
					if (strtotime($zdatedisemb) <= strtotime($datenow))
					{
						if ($zinactive == 0)
						{
							if ($zactive == 1)  //check if Crew is Inactive(Override)
							{
								if (!empty($zarrmnldate))
									$appstatus = "STANDBY";
								else
									$appstatus = "ONBOARD (Extended)";
							}
							else
							{
								$appstatus = "INACTIVE(Override)";
							}
						}
						else
						{
							$appstatus = "INACTIVE";
						}
						
						$lastccid = $zccid;
						$lastrankcode = $zrankcode;
						$lastrankalias = $zrankalias;
						$lastvesselcode = $zvesselcode;
						$lastvessel = $zvessel;
						$lastvesselname = $zvesselname;
						$lastembdate = $zdateemb;
						$lastdisembdate = $zdatedisemb;
						$lastdisembreasoncode= $zdisembreasoncode;
					}
					else 
					{
						if (strtotime($zdateemb) <= strtotime($datenow))
						{
							// $disablechecklist = 1;
							
							if ($zpromoted == 1)
							{
								$appstatus = "PROMOTED ONBOARD";
								$assignedccid=$zccid;
								$assignedrankcode = $zrankcode;
								$assignedrankalias = $zrankalias;
								$assignedvesselcode = $zvesselcode;
								$assignedvessel = $zvessel;
								$assignedvesselname = $zvesselname;
								$assignedetd = $zdateemb;
	//							$assignedetd2 = date('m/d/Y',strtotime($zdateemb));
							}
							else 
							{
								if (empty($zdepmnldate))
								{
									$appstatus = "STANDBY (Lined-up; Not Departed yet)";
	//								$appstatus = "ONBOARD (No Depart Manila)";
									$assignedccid=$zccid;
									$assignedrankcode = $zrankcode;
									$assignedrankalias = $zrankalias;
									$assignedvesselcode = $zvesselcode;
									$assignedvessel = $zvessel;
									$assignedvesselname = $zvesselname;
									$assignedetd = $zdateemb;
									$assignedetd2 = date('m/d/Y',strtotime($zdateemb));
								}
								else 
								{
									$appstatus = "ONBOARD";
								}
									$lastccid = $zccid;
									$lastrankcode = $zrankcode;
									$lastrankalias = $zrankalias;
									$lastvesselcode = $zvesselcode;
									$lastvessel = $zvessel;
									$lastvesselname = $zvesselname;
									$lastdisembdate = $zdatedisemb;
									$lastembdate = $zdateemb;
									$lastdisembreasoncode= $zdisembreasoncode;
							}
						}
						else 
						{
							$appstatus = "EMBARKING";
							$assignedccid=$zccid;
							$assignedrankcode = $zrankcode;
							$assignedrankalias = $zrankalias;
							$assignedvesselcode = $zvesselcode;
							$assignedvessel = $zvessel;
							$assignedvesselname = $zvesselname;
							$assignedetd = $zdateemb;
							$assignedetd2 = date('m/d/Y',strtotime($zdateemb));
						}
					}
							
					$vesselfull = $zvesselname;
							
				}
				else //OUTSIDE (NO VMC EXPERIENCE)
				{
					$qryapplicantstatus = mysql_query("SELECT ap.VMCRANKCODE,ap.VMCVESSELCODE,ap.VMCETD,r.ALIAS1 AS RANKALIAS,v.VESSEL
														FROM applicantstatus ap
														LEFT JOIN rank r ON r.RANKCODE=ap.VMCRANKCODE
														LEFT JOIN vessel v ON v.VESSELCODE=ap.VMCVESSELCODE
														WHERE ap.APPLICANTNO=$applicantno
													") or die(mysql_error());
					
					if (mysql_num_rows($qryapplicantstatus) == 1)
					{
						$rowapplicantstatus = mysql_fetch_array($qryapplicantstatus);
						
						$appstatus = "NEW HIRE";
						
						$assignedrankcode = $rowapplicantstatus["VMCRANKCODE"];
						$assignedrankalias = $rowapplicantstatus["RANKALIAS"];
						$assignedvesselcode = $rowapplicantstatus["VMCVESSELCODE"];
						$assignedvessel = $rowapplicantstatus["VESSEL"];
						
						if (!empty($rowapplicantstatus["VMCETD"]))
						{
							$assignedetd = date("dMY",strtotime($rowapplicantstatus["VMCETD"]));
							$assignedetd2 = date('m/d/Y',strtotime($assignedetd));
						}
						else 
						{
							$assignedetd = "";
							$assignedetd2 = "";
						}
					}
					else 
					{
							$appstatus = "NO VMC EXPERIENCE";
							$lastrankcode = $zrankcode;
					}
					
				}
				
				
				//SAVE TO $currentrankcode for checklist use...
				
				if (!empty($assignedrankcode) && $assignedrankcode != "---")
				{
					$currentrankcode = $assignedrankcode;
					$currentvesselcode = $assignedvesselcode;
					$currentetd = $assignedetd;
				}
				elseif (!empty($lastrankcode) && $lastrankcode != "---")
				{
					$currentrankcode = $lastrankcode;
					$currentvesselcode = "";
					$currentetd = "";
				}
				else 
				{
					$currentrankcode = "";
					$currentvesselcode = "";
					$currentetd = "";
				}
				
			}
			else  //if count > 0
			{
				
				if ($appstatus == "EMBARKING" || $appstatus == "PROMOTED ONBOARD" || $appstatus == "STANDBY (Lined-up; Not Departed yet)")
				{
					if (!empty($zdepmnldate) && $hehe==0)
					{
						$lastccid = $zccid;
						$lastrankcode = $zrankcode;
						$lastrankalias = $zrankalias;
						$lastvesselcode = $zvesselcode;
						$lastvessel = $zvessel;
						$lastvesselname = $zvesselname;
						$lastdisembdate = $zdatedisemb;
						$lastembdate = $zdateemb;
						$hehe++;
					}
				}
				else
				{
					if ($cnt == ($cntexp-1))
					{
						//
						if ($appstatus == "NO VMC EXPERIENCE")
						{
							if ($vmcexp == "1")
							{
								if ($zactive == "1")
								{
									$appstatus = "STANDBY";
									$lastccid = $zccid;
									$lastrankcode = $zrankcode;
									$lastrankalias = $zrankalias;
									$lastvesselcode = $zvesselcode;
									$lastvessel = $zvessel;
									$lastvesselname = $zvesselname;
									$lastdisembdate = $zdatedisemb;
									$lastembdate = $zdateemb;
								}
								else
								{
									$appstatus = "INACTIVE";
								}
							}
						}
					}
				}
			}
			
			$content .= "
			<tr>
	<!--			<td>$zexptype</td>	-->
				<td title=\"$zvesselname\">$zvessel</td>
				<td>$zrankalias</td>
				<td>&nbsp;$zdateemb</td>
	<!--			<td>&nbsp;$zdepmnldate</td>	-->
				<td>&nbsp;$zdatedisemb</td>
	<!--			<td>&nbsp;$zarrmnldate</td>	-->
				<td>&nbsp;$zreason</td>
			</tr>
			";
			
			$cnt++;
		}
	}
	
	if (mysql_num_rows($qryallexperience) == 0)
	{
		$qrycrewnoexp = mysql_query("
							SELECT CONCAT(c.FNAME,', ',c.GNAME,' ',c.MNAME) AS NAME,c.CREWCODE,c.FNAME,c.GNAME,c.MNAME,
							a.CHOICE1,c.UTILITY,
							IF (f.FASTTRACK IS NULL,0,1) AS FASTTRACK, IF (s.DESCRIPTION IS NULL,0,1) AS SCHOLAR,
							f.FASTTRACK AS FTDESC,s.DESCRIPTION AS SCHOLARDESC
							FROM crew c
							LEFT JOIN applicant a ON a.APPLICANTNO=c.APPLICANTNO
							LEFT JOIN applicantstatus aps ON aps.APPLICANTNO=a.APPLICANTNO
							LEFT JOIN crewscholar cs ON cs.APPLICANTNO=c.APPLICANTNO
							LEFT JOIN scholar s ON s.SCHOLASTICCODE=cs.SCHOLASTICCODE
							LEFT JOIN crewfasttrack cf ON cf.APPLICANTNO=c.APPLICANTNO
							LEFT JOIN fasttrack f ON f.FASTTRACKCODE=cf.FASTTRACKCODE
							WHERE c.APPLICANTNO=$applicantno AND aps.ENDORSEDDATE IS NOT NULL
						") or die(mysql_error());
						
		$rowcrewnoexp = mysql_fetch_array($qrycrewnoexp);

		$crewcode = $rowcrewnoexp["CREWCODE"];

		$crewfname = $rowcrewnoexp["FNAME"];
		$crewgname = $rowcrewnoexp["GNAME"];
		$crewmname = $rowcrewnoexp["MNAME"];
		
		$crewname = $crewfname . ", " . $crewgname . " " . $crewmname;
		
		$currentrankcode = $rowcrewnoexp["CHOICE1"];
		$crewutility = $rowcrewnoexp["UTILITY"];
		
		if ($crewutility == 1)
			$crewutilityshow = "( UTILITY )";
		else
			$crewutilityshow = "";
		
		$crewfasttrack = $rowcrewnoexp["FASTTRACK"];
		$crewscholar = $rowcrewnoexp["SCHOLAR"];
		
		if (!empty($rowcrewnoexp["FTDESC"]))
			$crewftdesc = "( " . $rowcrewnoexp["FTDESC"] . " )";
		else
			$crewftdesc = "";
			
		if (!empty($rowcrewnoexp["SCHOLARDESC"]))
			$crewscholardesc = "( " . $rowcrewnoexp["SCHOLARDESC"] . " )";
		else
			$crewscholardesc = "";
		
		
		$zvessel = "---";
		$zvesselcode = "";
		$zrankalias = "---";
		$zrankcode = "";
		$zvesselname = "---";
		
		
		$appstatus = "NO EXPERIENCE";
		$assignedrankcode = "---";
		$assignedrankalias = "---";
		$assignedvesselcode = "";
		$assignedvessel = "---";
		$assignedetd = "---";
		$assignedetd2 = "---";

		$lastrankcode = "";
		$lastccid = "";
		$lastrankalias = "---";
		$lastvesselcode = "";
		$lastvessel = "---";
		$lastdisembdate = "---";
		$lastembdate = "---";
		
		$content = "";	


	}

}





?>