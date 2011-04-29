<?php

include("veritas/connectdb.php");

if (isset($_GET["action"]))
	$action = $_GET["action"];
	
$datenow = date("Y-m-d");
	
	
switch ($action)
{
	case "1"	:  //UPDATES ALL ENTRIES WITH THE SAME DATEDISEMB AND DATECHANGEDISEMB
		
			$qrycrewchange = mysql_query("SELECT CCID,CONFIRMDEPDATE,CONFIRMDEPBY,DEPMNLDATE,DATEEMB,DATEDISEMB,DATECHANGEDISEMB,
										DISEMBREASONCODE,ARRMNLDATE,CONFIRMARRMNLDATE,CONFIRMARRMNLBY
										FROM crewchange where DATEDISEMB=DATECHANGEDISEMB
										ORDER BY DATEEMB
										") or die(mysql_error());
			
			echo "1. <b>UPDATING OF DATEDISEMB</b> <br /><br />";
			$cnt = 0;
			while ($rowcrewchange = mysql_fetch_array($qrycrewchange))
			{
				$cnt++;
				$ccid = $rowcrewchange["CCID"];
				$confirmdepdate = $rowcrewchange["CONFIRMDEPDATE"];
				$confirmdepby = $rowcrewchange["CONFIRMDEPBY"];
				$depmnldate = $rowcrewchange["DEPMNLDATE"];
				$dateemb = $rowcrewchange["DATEEMB"];
				$datedisemb = $rowcrewchange["DATEDISEMB"];
				$datechangedisemb = $rowcrewchange["DATECHANGEDISEMB"];
				$disembreasoncode = $rowcrewchange["DISEMBREASONCODE"];
				$arrmnldate = $rowcrewchange["ARRMNLDATE"];
				$confirmarrmnldate = $rowcrewchange["CONFIRMARRMNLDATE"];
				$confirmarrmnlby = $rowcrewchange["CONFIRMARRMNLBY"];
				
				$tmpdisemb = date("Y-m-d",strtotime("$dateemb + 9 month"));
				
				$qryupdatedisemb = mysql_query("UPDATE crewchange SET DATEDISEMB='$tmpdisemb' WHERE CCID=$ccid") or die(mysql_error());
				
				echo "$cnt. &nbsp; CCID = <b>$ccid</b>---> Updated DATEDISEMB from <b>$datedisemb</b> to <b>$tmpdisemb</b> <br />";
				
				if ($disembreasoncode == "OTH")
				{
					$qryupdatedisemb = mysql_query("UPDATE crewchange SET DISEMBREASONCODE=NULL WHERE CCID=$ccid") or die(mysql_error());
					echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;---> Updated DISEMBREASONCODE to <b>NULL</b> <br />";
				}
			}
			
			echo "Finished! Total of $cnt records.";
			
		break;
	case "2"	:  //
		
			$qrycrewchange = mysql_query("SELECT cc.CCID,CONFIRMDEPDATE,CONFIRMDEPBY,DEPMNLDATE,DATEEMB,DATEDISEMB,DATECHANGEDISEMB,
										DISEMBREASONCODE,ARRMNLDATE,CONFIRMARRMNLDATE,CONFIRMARRMNLBY
										FROM crewchange cc
										LEFT JOIN crewpromotionrelation cp ON cp.CCIDPROMOTE=cc.CCID
                    					WHERE DEPMNLDATE IS NULL
										AND cp.IDNO IS NULL AND DATEEMB < '2008-01-01'
										") or die(mysql_error());
			
			echo "2. <b>UPDATING OF DEPMNLDATE</b> <br /><br />";
			$cnt = 0;
			while ($rowcrewchange = mysql_fetch_array($qrycrewchange))
			{
				$cnt++;
				$ccid = $rowcrewchange["CCID"];
				$confirmdepdate = $rowcrewchange["CONFIRMDEPDATE"];
				$confirmdepby = $rowcrewchange["CONFIRMDEPBY"];
				$depmnldate = $rowcrewchange["DEPMNLDATE"];
				$dateemb = $rowcrewchange["DATEEMB"];
				$datedisemb = $rowcrewchange["DATEDISEMB"];
				$datechangedisemb = $rowcrewchange["DATECHANGEDISEMB"];
				$disembreasoncode = $rowcrewchange["DISEMBREASONCODE"];
				$arrmnldate = $rowcrewchange["ARRMNLDATE"];
				$confirmarrmnldate = $rowcrewchange["CONFIRMARRMNLDATE"];
				$confirmarrmnlby = $rowcrewchange["CONFIRMARRMNLBY"];
				
				if (empty($depmnldate))
				{
					$tmpdepmnldate = date("Y-m-d",strtotime("$dateemb - 1 day"));
					
					$qryupdatedepmnldate = mysql_query("UPDATE crewchange SET DEPMNLDATE='$tmpdepmnldate',
														CONFIRMDEPDATE='$datenow',
														CONFIRMDEPBY='SYS'
														WHERE CCID=$ccid
														") or die(mysql_error());
					
					echo "$cnt. &nbsp; CCID = <b>$ccid</b> ---> Updated DEPMNLDATE to $tmpdepmnldate. (NO DEPMNLDATE) <br />";
				}
			}
			
			echo "Finished! Total of $cnt records.";
		break;
	case "3"	:
		
			$qrycrewchange = mysql_query("SELECT CCID,APPLICANTNO,DATEEMB,DATEDISEMB,DATECHANGEDISEMB,DISEMBREASONCODE
								FROM crewchange 
								WHERE ARRMNLDATE IS NULL AND DATECHANGEDISEMB IS NULL 
								AND DISEMBREASONCODE IS NOT NULL AND DISEMBREASONCODE <> 'PR' AND DISEMBREASONCODE <> ''
							") or die(mysql_error());
			
			echo "3. <b>UPDATING OF ARRMNLDATE -- WHERE DISEMBREASONCODE IS NOT NULL AND ARRMNLDATE IS NULL</b> <br /><br />";
			$cnt = 0;

			while ($rowcrewchange = mysql_fetch_array($qrycrewchange))
			{
				$cnt++;
				$ccid = $rowcrewchange["CCID"];
				$dateemb = $rowcrewchange["DATEEMB"];
				$datedisemb = $rowcrewchange["DATEDISEMB"];
				$disembreasoncode = $rowcrewchange["DISEMBREASONCODE"];
				
				$tmparrmnldate = date("Y-m-d",strtotime("$datedisemb + 1 day"));
				
				$qryupdatearrmnldate = mysql_query("UPDATE crewchange SET ARRMNLDATE='$tmparrmnldate',
																		CONFIRMARRMNLDATE='$datenow',
																		CONFIRMARRMNLBY='SYS' 
													WHERE CCID=$ccid") or die(mysql_error());
				
				echo "$cnt. &nbsp; CCID = <b>$ccid</b>---> Updated ARRMNLDATE from <b>NULL</b> to <b>$tmparrmnldate</b> <br />";
			}

			echo "Finished! Total of $cnt records.";
		break;
//	case "4"	:
//		
//			$qrycrewchange = mysql_query("SELECT CCID,APPLICANTNO,DATEEMB,DATEDISEMB,DATECHANGEDISEMB,DISEMBREASONCODE,
//								CONFIRMARRMNLBY,CONFIRMARRMNLDATE
//								FROM crewchange where ARRMNLDATE IS NULL
//								AND DATECHANGEDISEMB IS NULL AND DISEMBREASONCODE IS NOT NULL AND DISEMBREASONCODE <> 'PR'
//								AND DISEMBREASONCODE <> ''
//							") or die(mysql_error());
//			
//			echo "3. <b>UPDATING OF DISEMBREASONCODE 'OTH' TO 'FC'</b> <br /><br />";
//			$cnt = 0;
//
//			while ($rowcrewchange = mysql_fetch_array($qrycrewchange))
//			{
//				$cnt++;
//				$ccid = $rowcrewchange["CCID"];
//				$confirmdepdate = $rowcrewchange["CONFIRMDEPDATE"];
//				$confirmdepby = $rowcrewchange["CONFIRMDEPBY"];
//				$depmnldate = $rowcrewchange["DEPMNLDATE"];
//				$dateemb = $rowcrewchange["DATEEMB"];
//				$datedisemb = $rowcrewchange["DATEDISEMB"];
//				$datechangedisemb = $rowcrewchange["DATECHANGEDISEMB"];
//				$disembreasoncode = $rowcrewchange["DISEMBREASONCODE"];
//				$arrmnldate = $rowcrewchange["ARRMNLDATE"];
//				$confirmarrmnldate = $rowcrewchange["CONFIRMARRMNLDATE"];
//				$confirmarrmnlby = $rowcrewchange["CONFIRMARRMNLBY"];
//				
//				if (strtotime($datechangedisemb) < strtotime($datenow))
//				{
//					if (!empty($arrmnldate) && $disembreasoncode == "OTH") // Arrived already AND Disembreasoncode is "OTH"
//					{
//						$qryupdatereason = mysql_query("UPDATE crewchange SET DISEMBREASONCODE='FC' WHERE CCID=$ccid") or die(mysql_error());
//						echo "---> Updated DISEMBREASONCODE from 'OTH' to 'FC' (Since DATECHANGEDISEMB is $datechangedisemb and ArriveManila is $arrmnldate <br />";
//					}
//					
//				}
//			}
//
//			echo "Finished! Total of $cnt records.";
//		break;
	
	
}








?>