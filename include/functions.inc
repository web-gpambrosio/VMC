<?php

function checkpath($path)
{
	if (is_file($path))
		return true;
	else 
		return false;
}

function imageScale($image, $newWidth, $newHeight)
{
    if(!$size = @getimagesize($image))
        die("Unable to get info on image $image");
    $ratio = ($size[0] / $size[1]);
    //scale by height
    if($newWidth == -1)
    {
        $ret[1] = $newHeight;
        $ret[0] = round(($newHeight * $ratio));
    }
    else if($newHeight == -1)
    {
        $ret[0] = $newWidth;
        $ret[1] = round(($newWidth / $ratio));
    }
    else
        die("Scale Error");
    return $ret;
} 


function checkexpiry($expirydate)
{
	if (strtotime($expirydate) >= strtotime("now"))
	{
		if (strtotime($expirydate) > strtotime("+3 months"))
		{
			$colorstyle = "color:Black";  //GOOD
		}
		else 
			$colorstyle = "color:Yellow;background-color:Black;";  //EXPIRATION BEFORE 3 MONTHS
		
	}
	else 
		$colorstyle = "color:Red;background-color:Black;";  //EXPIRED ALREADY
		
	return $colorstyle;
}


function checkwatchlist($wappno,&$watchlistremarks,&$vmc_id,&$poea_id)
{
	$vmclisted = 0;
	$poealisted = 0;
	
	//Results : 
	//	0 - Clear
	//	1 - Veritas Listed
	//	2 - POEA Listed
	//  3 - Both Listed
	
	$qrycrew = mysql_query("SELECT c.APPLICANTNO,c.FNAME,c.GNAME,c.MNAME,c.TIN,c.SSS FROM crew c WHERE c.APPLICANTNO=$wappno") or die(mysql_error());
	
	$rowcrew = mysql_fetch_array($qrycrew);
	$xfname = $rowcrew["FNAME"];
	$xgname = $rowcrew["GNAME"];
	$xmname = $rowcrew["MNAME"];
	$xtin = $rowcrew["TIN"];
	$xsss = $rowcrew["SSS"];
	
	$qrypassport = mysql_query("SELECT DOCNO FROM crewdocstatus WHERE APPLICANTNO=$wappno AND DOCCODE='41'") or die(mysql_error());
	$rowpassport = mysql_fetch_array($qrypassport);
	$xpassportno = $rowpassport["DOCNO"];
	
	$qryseabook = mysql_query("SELECT DOCNO FROM crewdocstatus WHERE APPLICANTNO=$wappno AND DOCCODE='F2'") or die(mysql_error());
	$rowseabook = mysql_fetch_array($qryseabook);
	$xseabookno = $rowseabook["DOCNO"];
	
	$qrypoea = mysql_query("SELECT * FROM watchlist_poea WHERE SECONDCHANCE=0") or die(mysql_error());
	
	while ($rowpoea = mysql_fetch_array($qrypoea))
	{
		$pidno = $rowpoea["IDNO"];
		$pfname = $rowpoea["FNAME"];
		$pgname = $rowpoea["GNAME"];
		$pmname = $rowpoea["MNAME"];
		$psss = $rowpoea["SSS"];
		$ptin = $rowpoea["TIN"];
		$ppassportno = $rowpoea["PASSPORTNO"];
		$pseabookno = $rowpoea["SEABOOKNO"];
		$remarks_poea = $rowpoea["REMARKS"];
		
		if (
				(!empty($xpassportno) && !empty($ppassportno) && ($xpassportno == $ppassportno)) || 
				(!empty($xseabookno) && !empty($pseabookno) && ($xseabookno == $pseabookno)) || 
				(($pfname == $xfname) && ($pgname == $xgname))
			)
		{
			$poealisted = 1;
			$poea_id = $pidno;
			$watchlistremarks = $remarks_poea;
		}
	}
	
	$qryveritas = mysql_query("SELECT * FROM watchlist_veritas WHERE APPLICANTNO=$wappno AND SECONDCHANCE=0") or die(mysql_error());
	
	while ($rowveritas = mysql_fetch_array($qryveritas))
	{
		$vidno = $rowveritas["IDNO"];
		$vfname = $rowveritas["FNAME"];
		$vgname = $rowveritas["GNAME"];
		$vmname = $rowveritas["MNAME"];
		$vsss = $rowveritas["SSS"];
		$vtin = $rowveritas["TIN"];
		$vpassportno = $rowveritas["PASSPORTNO"];
		$vseabookno = $rowveritas["SEABOOKNO"];
		$remarks_veritas = $rowveritas["REMARKS"];
		
		if (
				(!empty($xsss) && !empty($vsss) && ($xsss == $vsss)) || 
				(!empty($xpassportno) && !empty($vpassportno) && ($xpassportno == $vpassportno)) || 
				(!empty($xseabookno) && !empty($vseabookno) && ($xseabookno == $vseabookno)) || 
				(($vfname == $xfname) && ($vgname == $xgname) && ($vmname == $xmname))
			)
		{
			$vmclisted = 1;
			$vmc_id = $vidno;
			$watchlistremarks = $remarks_veritas;
		}
	}
		
	
	if ($vmclisted == 1 && $poealisted == 1)
		return 3;
	elseif ($vmclisted == 1 && $poealisted == 0)
		return 1;
		elseif ($vmclisted == 0 && $poealisted == 1)
			return 2;
		else 
			return 0;
}


function computegrade($comp_ccid,$comp_evalno)
{
	
	$certotalgrade = 0;
	
	$wherepart = "";
	if (!empty($comp_evalno))
	{
		$wherepart = "AND EVALNO=$comp_evalno";
	}
	
//	echo "SELECT IDNO,CCID,EVALNO,VAR1,VAR2,VAR3,VAR4,VPJ1,VPJ2,VPJ3,VPJ4,VSM1,VSM2,VSM3,VPR1,VPR2,VPR3,VBE1,VBE2,VBE3
//									FROM crewevalhdr ceh
//									WHERE CCID=$comp_ccid $wherepart
//									ORDER BY EVALNO DESC
//									LIMIT 1
//							";
	
	$qrygetevaluation = mysql_query("SELECT IDNO,CCID,EVALNO,VAR1,VAR2,VAR3,VAR4,VPJ1,VPJ2,VPJ3,VPJ4,VSM1,VSM2,VSM3,VPR1,VPR2,VPR3,VBE1,VBE2,VBE3,FINALGRADE
									FROM crewevalhdr ceh
									WHERE CCID=$comp_ccid $wherepart
									ORDER BY EVALDATE DESC
									LIMIT 1
							") or die(mysql_error());
	
	if (mysql_num_rows($qrygetevaluation) > 0)
	{
		$rowgetevaluation = mysql_fetch_array($qrygetevaluation);
		
		$finalgrade = $rowgetevaluation["FINALGRADE"];
		
		$var1 = $rowgetevaluation["VAR1"];
		$var2 = $rowgetevaluation["VAR2"];
		$var3 = $rowgetevaluation["VAR3"];
		$var4 = $rowgetevaluation["VAR4"];
		
		$vpj1 = $rowgetevaluation["VPJ1"];
		$vpj2 = $rowgetevaluation["VPJ2"];
		$vpj3 = $rowgetevaluation["VPJ3"];
		$vpj4 = $rowgetevaluation["VPJ4"];
		
		$vsm1 = $rowgetevaluation["VSM1"];
		$vsm2 = $rowgetevaluation["VSM2"];
		$vsm3 = $rowgetevaluation["VSM3"];
		
		$vpr1 = $rowgetevaluation["VPR1"];
		$vpr2 = $rowgetevaluation["VPR2"];
		$vpr3 = $rowgetevaluation["VPR3"];
		
		$vbe1 = $rowgetevaluation["VBE1"];
		$vbe2 = $rowgetevaluation["VBE2"];
		$vbe3 = $rowgetevaluation["VBE3"];
		
		
		if (empty($finalgrade) || $finalgrade == '0')
		{
			if (
				!empty($var1) && !empty($var2) && !empty($var3) && !empty($var4) &&
				!empty($vpj1) && !empty($vpj2) && !empty($vpj3) && !empty($vpj4) &&
				!empty($vsm1) && !empty($vsm2) && !empty($vsm3) &&
				!empty($vpr1) && !empty($vpr2) && !empty($vpr3) &&
				!empty($vbe1) && !empty($vbe2) && !empty($vbe3)
			)
			{
			
			//COMPUTE VAR
			
				for ($sectionid=1;$sectionid <= 5;$sectionid++)  //SECTIONS
				{
					switch ($sectionid)
					{
						case 1	:	//VAR
						
								for ($rowid=1;$rowid <= 4;$rowid++)
								{
									switch ($rowid)
									{
										case 1	:	$selection = $var1; break;
										case 2	:	$selection = $var2; break;
										case 3	:	$selection = $var3; break;
										case 4	:	$selection = $var4; break;
									}
									
									$qrygetpoints = mysql_query("SELECT POINTS 
																FROM crewevalgradedtl 
																WHERE SECTIONID=$sectionid AND ROWID=$rowid AND SELECTION=$selection
																") or die(mysql_error());
									
									$rowgetpoints = mysql_fetch_array($qrygetpoints);
									if (!empty($rowgetpoints["POINTS"]))
										$points = $rowgetpoints["POINTS"];
									else 
										$points = 0;
									
									$certotalgrade += $points;
								}
								
		//						echo "VAR=$certotalgrade";
						
							break;
						case 2	:	//VPJ
						
								for ($rowid=1;$rowid <= 4;$rowid++)
								{
									switch ($rowid)
									{
										case 1	:	$selection = $vpj1; break;
										case 2	:	$selection = $vpj2; break;
										case 3	:	$selection = $vpj3; break;
										case 4	:	$selection = $vpj4; break;
									}
									
									$qrygetpoints = mysql_query("SELECT POINTS 
																FROM crewevalgradedtl 
																WHERE SECTIONID=$sectionid AND ROWID=$rowid AND SELECTION=$selection
																") or die(mysql_error());
									
									$rowgetpoints = mysql_fetch_array($qrygetpoints);
									if (!empty($rowgetpoints["POINTS"]))
										$points = $rowgetpoints["POINTS"];
									else 
										$points = 0;
									
									$certotalgrade += $points;
								}
								
		//						echo "VPJ=$certotalgrade";
						
							break;
						case 3	:	//VSM
						
								for ($rowid=1;$rowid <= 3;$rowid++)
								{
									switch ($rowid)
									{
										case 1	:	$selection = $vsm1; break;
										case 2	:	$selection = $vsm2; break;
										case 3	:	$selection = $vsm3; break;
									}
									
									$qrygetpoints = mysql_query("SELECT POINTS 
																FROM crewevalgradedtl 
																WHERE SECTIONID=$sectionid AND ROWID=$rowid AND SELECTION=$selection
																") or die(mysql_error());
									
									$rowgetpoints = mysql_fetch_array($qrygetpoints);
									if (!empty($rowgetpoints["POINTS"]))
										$points = $rowgetpoints["POINTS"];
									else 
										$points = 0;
									
									$certotalgrade += $points;
								}
								
		//						echo "VSM=$certotalgrade";
						
							break;
						case 4	:	//VPR
						
								for ($rowid=1;$rowid <= 3;$rowid++)
								{
									switch ($rowid)
									{
										case 1	:	$selection = $vpr1; break;
										case 2	:	$selection = $vpr2; break;
										case 3	:	$selection = $vpr3; break;
									}
									
									$qrygetpoints = mysql_query("SELECT POINTS 
																FROM crewevalgradedtl 
																WHERE SECTIONID=$sectionid AND ROWID=$rowid AND SELECTION=$selection
																") or die(mysql_error());
									
									$rowgetpoints = mysql_fetch_array($qrygetpoints);
									if (!empty($rowgetpoints["POINTS"]))
										$points = $rowgetpoints["POINTS"];
									else 
										$points = 0;
									
									$certotalgrade += $points;
								}
								
		//						echo "VPR=$certotalgrade";
						
							break;
						case 5	:	//VBE
						
								for ($rowid=1;$rowid <= 3;$rowid++)
								{
									switch ($rowid)
									{
										case 1	:	$selection = $vbe1; break;
										case 2	:	$selection = $vbe2; break;
										case 3	:	$selection = $vbe3; break;
									}
									
									$qrygetpoints = mysql_query("SELECT POINTS 
																FROM crewevalgradedtl 
																WHERE SECTIONID=$sectionid AND ROWID=$rowid AND SELECTION=$selection
																") or die(mysql_error());
									
									$rowgetpoints = mysql_fetch_array($qrygetpoints);
									if (!empty($rowgetpoints["POINTS"]))
										$points = $rowgetpoints["POINTS"];
									else 
										$points = 0;
									
									$certotalgrade += $points;
								}
								
		//						echo "VBE=$certotalgrade";
						
							break;
					}
				}
			}
			else 
			{
				$certotalgrade = 101;
			}
		}
		else 
		{
			$certotalgrade = $finalgrade;
		}
	}
	
	return $certotalgrade;
}

?>