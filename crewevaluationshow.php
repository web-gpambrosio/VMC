<?php
require_once 'Excel/reader.php';
include('veritas/connectdb.php');
//include('connectdb.php');
header('Content-Type: text/html; charset=UTF-8');

session_start();

include('include/stylephp.inc');

if (isset($_SESSION["employeeid"]))
	$user = $_SESSION["employeeid"];

// $basedirdocs = "docimages/";
$basedirdocs = "docimg/";
	
$datetimenow=date("Y-m-d H:i:s");
$datenow=date("Y-m-d");
$datenowshow=date("d M Y");
$tablewidth="727px";
//$serverdirtmp="temp"; // temporary server dumping changing filename to CCID_EVALNO
// $serverdirtmp="docimages"; // temporary server dumping changing filename to CCID_EVALNO
$serverdirtmp="docimg"; // temporary server dumping changing filename to CCID_EVALNO
$serverdir="cer"; // final server dumping


function checkpath($path,$type)
{
	switch ($type)
	{
		case "1" :
				if (is_file($path))
					return true;
				else 
					return false;
			break;
		case "2" :
				if (is_dir($path))
					return true;
				else 
					return false;
			break;
	}
}




if (isset($_POST["actiontxt"]))
	$actiontxt = $_POST["actiontxt"];
	
if (isset($_POST["getuploadedfile"]))
	$getuploadedfile = $_POST["getuploadedfile"];
	
if (isset($_GET["ccid"]))
	$ccid = $_GET["ccid"];

if (isset($_POST["totalgrade"]))
	$totalgrade = $_POST["totalgrade"];

if (isset($_POST["evalnoradio"]))
	$evalnoradio = $_POST["evalnoradio"];
else 
	$evalnoradio = "evalno1";
$evalno=substr($evalnoradio,6,1);

$$evalnoradio="checked=\"checked\"";

include('include/crewevalisset.inc');


//disable radio box if file not found
$qryappno=mysql_query("SELECT APPLICANTNO FROM crewchange WHERE CCID=$ccid") or die(mysql_error());
$rowappno=mysql_fetch_array($qryappno);
$getappno=$rowappno["APPLICANTNO"];

$evalno1disabled="disabled=\"disabled\"";
$evalno2disabled="disabled=\"disabled\"";
$evalno3disabled="disabled=\"disabled\"";
$evalno4disabled="disabled=\"disabled\"";
$qrychkevalno=mysql_query("SELECT EVALNO FROM crewevalhdr WHERE CCID=$ccid ORDER BY EVALNO") or die(mysql_error());
while ($rowchkevalno=mysql_fetch_array($qrychkevalno)) 
{
	$getevno="evalno".$rowchkevalno["EVALNO"]."disabled";
	$$getevno="";
}


if(!empty($actiontxt))
{
	switch($actiontxt)
	{
		case "save":
//			$qrychkdata=mysql_query("SELECT * FROM crewevalhdr WHERE CCID=$ccid AND EVALNO=$evalno") or die(mysql_error());
//			if(mysql_num_rows($qrychkdata)==0)
//			{
//				include('include/crewevalempty.inc');
//				
//				$getsave="INSERT INTO crewevalhdr (CCID,EVALNO,EVALDATE,VPC1,VPC2,VPC3,VPC4,VPC5,VPC6,VPC1REM,VPC2REM,
//					VPC3REM,VPC4REM,VPC5REM,VPC6REM,VEE1,VEE1REM,FLEETKEPT,VEP0,VEP0REM,NEXTRANK,VEP1,VEP2,VEP3,VEP4,
//					NEGDISCUSSED,VAR1,VAR2,VAR3,VAR4,VPJ1,VPJ2,VPJ3,VPJ4,VSM1,VSM2,VSM3,VPR1,VPR2,VPR3,VBE1,VBE2,VBE3,
//					VSC1,VSC2,VSC3,CAPTNAME1,DATE1,CAPTNAME2,DATE2,PRINCIPALCOMMENT,
//					VMCCOMMENT,EXTRATRAIN,REPEXTRATRAIN,REPEXTRATRAINREM,NEWTRAINCODE1,NEWTRAINOTH1,NEWTRAINCODE2,
//					NEWTRAINOTH2,NEWTRAINCODE3,NEWTRAINOTH3,EFE1,EFEBY1,EFEDATE1,EFE2,EFEBY2,EFEDATE2,EFE3,EFEBY3,EFEDATE3,
//					EPR1,EPRBY1,EPRDATE1,EPR2,EPRBY2,EPRDATE2,EPR3,EPRBY3,EPRDATE3,MADEBY,MADEDATE) VALUES ($ccid,$evalno,
//					'$evaldate',$vpc1,$vpc2,$vpc3,$vpc4,$vpc5,$vpc6,$vpc1rem,$vpc2rem,$vpc3rem,$vpc4rem,
//					$vpc5rem,$vpc6rem,$vee1,$vee1rem,$fleetkept,
//					$vep0,$vep0rem,$nextrank,$vep1,$vep2,$vep3,$vep4,$negdiscussed,$var1,$var2,$var3,
//					$var4,$vpj1,$vpj2,$vpj3,$vpj4,$vsm1,$vsm2,
//					$vsm3,$vpr1,$vpr2,$vpr3,$vbe1,$vbe2,$vbe3,$vsc1,$vsc2,$vsc3,$captname1,$date1,
//					$captname2,$date2,$principalcomment,
//					$vmccomment,$extratrain,$repextratrain,$repextratrainrem,$newtraincode1,$newtrainoth1,
//					$newtraincode2,
//					$newtrainoth2,$newtraincode3,$newtrainoth3,$efe1,$efeby1,$efedate1,$efe2,$efeby2,
//					$efedate2,$efe3,$efeby3,$efedate3,
//					$epr1,$eprby1,$eprdate1,$epr2,$eprby2,$eprdate2,$epr3,$eprby3,$eprdate3,'$user',
//					'$datetimenow')";
////				echo $getsave;
//				$qrysavedata=mysql_query("$getsave") or die(mysql_error());
//			}
		break;
		case  "uploadpdf":
//			$qrygetapplicantno=mysql_query("SELECT v.VESSEL,c.FNAME,c.GNAME,c.APPLICANTNO
//				FROM crewchange cc
//				LEFT JOIN vessel v ON cc.VESSELCODE=v.VESSELCODE
//				LEFT JOIN crew c ON cc.APPLICANTNO=c.APPLICANTNO
//				WHERE CCID=$ccid") or die(mysql_error());
//			
//			$rowgetapplicantno=mysql_fetch_array($qrygetapplicantno);
//			$vesselname=$rowgetapplicantno["VESSEL"];
//			$fname=$rowgetapplicantno["FNAME"];
//			$gname=$rowgetapplicantno["GNAME"];
//			$appno=$rowgetapplicantno["APPLICANTNO"];
//			
//			//PDF FILE UPLOAD
//			
//			$doneupload2 = 0;
//			$uploadmsg2 = "";
//			
//			$filename = $_FILES["uploadedfile_pdf"]["name"];
//			
//			$getfilename = explode(".",$filename);
//			$ext = $getfilename[1];
//			
//			$dir=$serverdirtmp.'/'. $appno . '/' . 'CER';  
//			
//			if (!checkpath($dir,2))  //CHECK IF docimages/$applicantno/CER directory exists
//				mkdir($dir,0777);
//				
//			if ($ext == "pdf")  //CHECK IF extension is PDF...
//			{
//				$file = $dir . '/' . $ccid . '_' . $evalno . '.' . $ext;
//				
//				if (!checkpath($file,1))
//				{
//					$tmp_name = $_FILES['uploadedfile_pdf']['tmp_name'];
//					$error = $_FILES['uploadedfile_pdf']['error'];
//		
//					if ($error==UPLOAD_ERR_OK) 
//					{
//						if ($_FILES['uploadedfile_pdf']['size'] > 0)
//						{
//							if(move_uploaded_file($tmp_name, $file))
//							{
//					      		$doneupload2 = 1;
//					      		$uploadmsg2 = "File: $filename uploaded successfully.";
//							}
//					      	else 
//					      	{
//					      		$doneupload2 = 0;
//					      		$uploadmsg2 = "File upload failed.";
//					      	}
//						}
//						else
//						{
//							$uploadmsg2 = "File: $filename is empty.";
//						}
//					}
//					else if ($error==UPLOAD_ERR_NO_FILE) 
//						{
//							$uploadmsg2 = "No files specified.";
//						} 
//						else
//						{
//							$uploadmsg2 = "File Upload failed.";
//						}
//				}
//			}
				
			//END OF PDF FILE UPLOAD			

			
		break;
		case "upload":
			//get CCID details
			$qrygetapplicantno=mysql_query("SELECT v.VESSEL,c.FNAME,c.GNAME,c.APPLICANTNO
				FROM crewchange cc
				LEFT JOIN vessel v ON cc.VESSELCODE=v.VESSELCODE
				LEFT JOIN crew c ON cc.APPLICANTNO=c.APPLICANTNO
				WHERE CCID=$ccid") or die(mysql_error());
			
			$rowgetapplicantno=mysql_fetch_array($qrygetapplicantno);
			$vesselname=$rowgetapplicantno["VESSEL"];
			$fname=$rowgetapplicantno["FNAME"];
			$gname=$rowgetapplicantno["GNAME"];
			$appno=$rowgetapplicantno["APPLICANTNO"];
			
			//EXCEL FILE UPLOAD
			
			$doneupload = 0;
			$uploadmsg = "";
			
			$filename = $_FILES["uploadedfile_xls"]["name"];
			
			$getfilename = explode(".",$filename);
			$ext = $getfilename[1];
			
			$dir=$serverdirtmp.'/'. $appno . '/' . 'CER';  
			
			if (!checkpath($dir,2))  //CHECK IF docimages/$applicantno/CER directory exists
				mkdir($dir,0777);
				
			if ($ext == "xls")  //CHECK IF extension is XLS...
			{
				$file = $dir . '/' . $ccid . '_' . $evalno . '.' . $ext;
				
				if (!checkpath($file,1))
				{
			
					$tmp_name = $_FILES['uploadedfile_xls']['tmp_name'];
					$error = $_FILES['uploadedfile_xls']['error'];
		
					if ($error==UPLOAD_ERR_OK) 
					{
						if ($_FILES['uploadedfile_xls']['size'] > 0)
						{
							if(move_uploaded_file($tmp_name, $file))
							{
					      		$doneupload = 1;
//					      		$uploadmsg = "File: $filename uploaded successfully.";
							}
					      	else 
					      	{
					      		$doneupload = 0;
					      		$uploadmsg = "File upload failed.";
					      	}
						}
						else
						{
							$uploadmsg = "File: $filename is empty.";
						}
					}
					else if ($error==UPLOAD_ERR_NO_FILE) 
						{
							$uploadmsg = "No files specified.";
						} 
						else
						{
							$uploadmsg = "File Upload failed.";
						}
				}
			}
				
			//END OF EXCEL FILE UPLOAD
				
			if ($uploadmsg == "")
			{
				$Sheet=0;
				$data = new Spreadsheet_Excel_Reader();
				// Set output Encoding.
	//			$data->setOutputEncoding('CP1251');
				$data->read($file);
				//check data if accurate
//				$xclgname = "XXX";
				
				$xclgname=$data->sheets[$Sheet]['cells'][8][23];
				$xclfname=$data->sheets[$Sheet]['cells'][8][32];
				$xclvesselname=$data->sheets[$Sheet]['cells'][4][7];
				$evalnochk=$data->sheets[$Sheet]['cells'][3][22]; //EVALNO 1
				if(empty($evalnochk))
				{
					$evalnochk=$data->sheets[$Sheet]['cells'][4][22]; //EVALNO 2
					if(empty($evalnochk))
					{
						$evalnochk=$data->sheets[$Sheet]['cells'][5][22]; //EVALNO 3
						if(empty($evalnochk))
						{
							$evalnochk=$data->sheets[$Sheet]['cells'][5][31]; //EVALNO 4
							if(empty($evalnochk))
								$xclevalno=0;
							else 
								$xclevalno=4;
						}
						else 
							$xclevalno=3;
					}
					else 
						$xclevalno=2;
				}
				else 
					$xclevalno=1;
				
				$rem="";
				if($xclgname!=$gname)
				{
					$rem.="Crew First Name: \'".$gname. "\' / Excel First Name: \'".$xclgname."\'\\n";
				}
				if($xclfname!=$fname)
				{
					$rem.="Crew Family Name: \'".$fname. "\' / Excel Family Name: \'".$xclfname."\'\\n";
				}
				if($xclvesselname!=$vesselname)
				{
					$rem.="Crew Vessel Name: \'".$vesselname. "\' / Excel Vessel Name: \'".$xclvesselname."\'\\n";
				}
				if($xclevalno!=$evalno)
				{
					$rem.="Crew Evalutaion No: \'".$evalno. "\' / Excel Evalutaion No: \'".$xclevalno."\'\\n";
				}
				if(!empty($rem))
				{
					echo "<script>alert(\"Error: ($file)\\n$rem\")</script>";
					unlink($file);
				}
				else 
				{
					$xclvpc1=$data->sheets[$Sheet]['cells'][31][11]; //VPC1
					$xclvpc2=$data->sheets[$Sheet]['cells'][32][11]; //VPC2
					$xclvpc3=$data->sheets[$Sheet]['cells'][33][11]; //VPC3
					$xclvpc4=$data->sheets[$Sheet]['cells'][34][11]; //VPC4
					$xclvpc5=$data->sheets[$Sheet]['cells'][35][11]; //VPC5
					$xclvpc6=$data->sheets[$Sheet]['cells'][36][11]; //VPC6
					$xclvpc1rem=$data->sheets[$Sheet]['cells'][31][20]; //VPC1REM
					$xclvpc2rem=$data->sheets[$Sheet]['cells'][32][20]; //VPC2REM
					$xclvpc3rem=$data->sheets[$Sheet]['cells'][33][20]; //VPC3REM
					$xclvpc4rem=$data->sheets[$Sheet]['cells'][34][20]; //VPC4REM
					$xclvpc5rem=$data->sheets[$Sheet]['cells'][35][20]; //VPC5REM
					$xclvpc6rem=$data->sheets[$Sheet]['cells'][36][20]; //VPC6REM
					$xclnewtraincode1=$data->sheets[$Sheet]['cells'][40][3]; //NEWTRAINCODE1
					$xclnewtraincode2=$data->sheets[$Sheet]['cells'][41][3]; //NEWTRAINCODE1
					$xclnewtraincode3=$data->sheets[$Sheet]['cells'][42][3]; //NEWTRAINCODE1
					$xclnewtrainoth1=$data->sheets[$Sheet]['cells'][40][21]; //NEWTRAINOTH1
					$xclnewtrainoth2=$data->sheets[$Sheet]['cells'][41][21]; //NEWTRAINOTH2
					$xclnewtrainoth3=$data->sheets[$Sheet]['cells'][42][21]; //NEWTRAINOTH3
					$xclvee1=$data->sheets[$Sheet]['cells'][44][15]; //VEE1
					$xclvee1rem=$data->sheets[$Sheet]['cells'][50][3]; //VEE1REM 1st ROW
					$xclvee1rem.=$data->sheets[$Sheet]['cells'][51][3]; //VEE1REM 2nd ROW
					$xclvee1rem.=$data->sheets[$Sheet]['cells'][52][3]; //VEE1REM 3rd ROW
					$xclfleetkept=$data->sheets[$Sheet]['cells'][48][25]; //FLEET KEPT
					$xclvep0=$data->sheets[$Sheet]['cells'][55][12]; //VEP0
					$xclvep0rem=$data->sheets[$Sheet]['cells'][60][3]; //VEP0REM 1st ROW
					$xclvep0rem.=$data->sheets[$Sheet]['cells'][61][3]; //VEP0REM 2nd ROW
					$xclvep0rem.=$data->sheets[$Sheet]['cells'][62][3]; //VEP0REM 3rd ROW
					$xclnextrank=$data->sheets[$Sheet]['cells'][57][5]; //NEXT RANK
	//				$xclnegdiscussed=$data->sheets[$Sheet]['cells'][57][5]; //NEXT RANK
					$xclcaptname1=$data->sheets[$Sheet]['cells'][64][14]; //CAPTAIN NAME1
					$xclcaptname2=$data->sheets[$Sheet]['cells'][64][28]; //CAPTAIN NAME2
					$xcldate1=$data->sheets[$Sheet]['cells'][66][19]; //DATE1
					$xcldate2=$data->sheets[$Sheet]['cells'][66][32]; //DATE2
					$xclvar1=$data->sheets[$Sheet]['cells'][13][65]; //VAR1
					$xclvar2=$data->sheets[$Sheet]['cells'][14][65]; //VAR2
					$xclvar3=$data->sheets[$Sheet]['cells'][15][65]; //VAR3
					$xclvar4=$data->sheets[$Sheet]['cells'][16][65]; //VAR4
					$xclvpj1=$data->sheets[$Sheet]['cells'][20][65]; //VPJ1
					$xclvpj2=$data->sheets[$Sheet]['cells'][21][65]; //VPJ2
					$xclvpj3=$data->sheets[$Sheet]['cells'][22][65]; //VPJ3
					$xclvpj4=$data->sheets[$Sheet]['cells'][23][65]; //VPJ4
					$xclvsm1=$data->sheets[$Sheet]['cells'][27][65]; //VSM1
					$xclvsm2=$data->sheets[$Sheet]['cells'][28][65]; //VSM2
					$xclvsm3=$data->sheets[$Sheet]['cells'][29][65]; //VSM3
					$xclvpr1=$data->sheets[$Sheet]['cells'][33][65]; //VPR1
					$xclvpr2=$data->sheets[$Sheet]['cells'][34][65]; //VPR2
					$xclvpr3=$data->sheets[$Sheet]['cells'][35][65]; //VPR3
					$xclvbe1=$data->sheets[$Sheet]['cells'][39][65]; //VBE1
					$xclvbe2=$data->sheets[$Sheet]['cells'][40][65]; //VBE2
					$xclvbe3=$data->sheets[$Sheet]['cells'][41][65]; //VBE3
					$xclvsc1=$data->sheets[$Sheet]['cells'][45][50]; //VSC1
					$xclvsc2=$data->sheets[$Sheet]['cells'][46][50]; //VSC2
					$xclvsc3=$data->sheets[$Sheet]['cells'][47][50]; //VSC3
					
					//START OF TEST
					$vpc1=$data->sheets[$Sheet]['cells'][31][11]; //VPC1
					$vpc2=$data->sheets[$Sheet]['cells'][32][11]; //VPC2
					$vpc3=$data->sheets[$Sheet]['cells'][33][11]; //VPC3
					$vpc4=$data->sheets[$Sheet]['cells'][34][11]; //VPC4
					$vpc5=$data->sheets[$Sheet]['cells'][35][11]; //VPC5
					$vpc6=$data->sheets[$Sheet]['cells'][36][11]; //VPC6
					$vpc1rem=$data->sheets[$Sheet]['cells'][31][20]; //VPC1REM
					$vpc2rem=$data->sheets[$Sheet]['cells'][32][20]; //VPC2REM
					$vpc3rem=$data->sheets[$Sheet]['cells'][33][20]; //VPC3REM
					$vpc4rem=$data->sheets[$Sheet]['cells'][34][20]; //VPC4REM
					$vpc5rem=$data->sheets[$Sheet]['cells'][35][20]; //VPC5REM
					$vpc6rem=$data->sheets[$Sheet]['cells'][36][20]; //VPC6REM
					$newtraincode1=$data->sheets[$Sheet]['cells'][40][3]; //NEWTRAINCODE1
					$newtraincode2=$data->sheets[$Sheet]['cells'][41][3]; //NEWTRAINCODE1
					$newtraincode3=$data->sheets[$Sheet]['cells'][42][3]; //NEWTRAINCODE1
					$newtrainoth1=$data->sheets[$Sheet]['cells'][40][21]; //NEWTRAINOTH1
					$newtrainoth2=$data->sheets[$Sheet]['cells'][41][21]; //NEWTRAINOTH2
					$newtrainoth3=$data->sheets[$Sheet]['cells'][42][21]; //NEWTRAINOTH3
					$vee1=$data->sheets[$Sheet]['cells'][44][15]; //VEE1
					$vee1rem=$data->sheets[$Sheet]['cells'][50][3]; //VEE1REM 1st ROW
					$vee1rem.=$data->sheets[$Sheet]['cells'][51][3]; //VEE1REM 2nd ROW
					$vee1rem.=$data->sheets[$Sheet]['cells'][52][3]; //VEE1REM 3rd ROW
					$fleetkept=$data->sheets[$Sheet]['cells'][48][25]; //FLEET KEPT
					$vep0=$data->sheets[$Sheet]['cells'][55][12]; //VEP0
					$vep0rem=$data->sheets[$Sheet]['cells'][60][3]; //VEP0REM 1st ROW
					$vep0rem.=$data->sheets[$Sheet]['cells'][61][3]; //VEP0REM 2nd ROW
					$vep0rem.=$data->sheets[$Sheet]['cells'][62][3]; //VEP0REM 3rd ROW
					$nextrank=$data->sheets[$Sheet]['cells'][57][5]; //NEXT RANK
	//				$negdiscussed=$data->sheets[$Sheet]['cells'][57][5]; //NEXT RANK
					$captname1=$data->sheets[$Sheet]['cells'][64][14]; //CAPTAIN NAME1
					$captname2=$data->sheets[$Sheet]['cells'][64][28]; //CAPTAIN NAME2
					$xcldatediff=25569+(8/24);
					$date1=$data->sheets[$Sheet]['cells'][66][19]; //DATE1
					if(!empty($date1))
						$date1=date("Y-m-d",($date1-$xcldatediff)*24*60*60);
					$date2=$data->sheets[$Sheet]['cells'][66][32]; //DATE2
					if(!empty($date2))
						$date2=date("Y-m-d",($date2-$xcldatediff)*24*60*60);
					$var1=$data->sheets[$Sheet]['cells'][13][65]; //VAR1
					$var2=$data->sheets[$Sheet]['cells'][14][65]; //VAR2
					$var3=$data->sheets[$Sheet]['cells'][15][65]; //VAR3
					$var4=$data->sheets[$Sheet]['cells'][16][65]; //VAR4
					$vpj1=$data->sheets[$Sheet]['cells'][20][65]; //VPJ1
					$vpj2=$data->sheets[$Sheet]['cells'][21][65]; //VPJ2
					$vpj3=$data->sheets[$Sheet]['cells'][22][65]; //VPJ3
					$vpj4=$data->sheets[$Sheet]['cells'][23][65]; //VPJ4
					$vsm1=$data->sheets[$Sheet]['cells'][27][65]; //VSM1
					$vsm2=$data->sheets[$Sheet]['cells'][28][65]; //VSM2
					$vsm3=$data->sheets[$Sheet]['cells'][29][65]; //VSM3
					$vpr1=$data->sheets[$Sheet]['cells'][33][65]; //VPR1
					$vpr2=$data->sheets[$Sheet]['cells'][34][65]; //VPR2
					$vpr3=$data->sheets[$Sheet]['cells'][35][65]; //VPR3
					$vbe1=$data->sheets[$Sheet]['cells'][39][65]; //VBE1
					$vbe2=$data->sheets[$Sheet]['cells'][40][65]; //VBE2
					$vbe3=$data->sheets[$Sheet]['cells'][41][65]; //VBE3
					$vsc1=$data->sheets[$Sheet]['cells'][45][50]; //VSC1
					$vsc2=$data->sheets[$Sheet]['cells'][46][50]; //VSC2
					$vsc3=$data->sheets[$Sheet]['cells'][47][50]; //VSC3
					//END OF TEST
				}
			}
//			echo $evalno." YYY";
			$uploaded=1;
		break;
	}
}

//get CREW APPLICANTNO & other details
$qrygetapplicantno=mysql_query("SELECT cc.APPLICANTNO,cc.DATEEMB,v.VESSEL,c.BIRTHDATE,c.NATIONALITY,c.FNAME,c.GNAME,c.MNAME,
	r.RANK,cs.SCHOLASTICCODE
	FROM crewchange cc
	LEFT JOIN vessel v ON cc.VESSELCODE=v.VESSELCODE
	LEFT JOIN crew c ON cc.APPLICANTNO=c.APPLICANTNO
	LEFT JOIN rank r ON cc.RANKCODE=r.RANKCODE
	LEFT JOIN crewscholar cs ON cc.APPLICANTNO=cs.APPLICANTNO
	WHERE CCID=$ccid") or die(mysql_error());

$rowgetapplicantno=mysql_fetch_array($qrygetapplicantno);
$applicantno=$rowgetapplicantno["APPLICANTNO"];
$dateembcur=$rowgetapplicantno["DATEEMB"];
$vesselname=$rowgetapplicantno["VESSEL"];
$birthdate=$rowgetapplicantno["BIRTHDATE"];
$nationality=$rowgetapplicantno["NATIONALITY"];
$fname=$rowgetapplicantno["FNAME"];
$gname=$rowgetapplicantno["GNAME"];
$mname=substr($rowgetapplicantno["MNAME"],0,1).".";
$fullname=$gname." ".$mname." ".$fname;
$rank=$rowgetapplicantno["RANK"];
$scholasticcode=$rowgetapplicantno["SCHOLASTICCODE"];
if(!empty($scholasticcode))
	$scholasticshow="Y";
$birthdate1=substr($birthdate,0,1);
$birthdate2=substr($birthdate,1,1);
$birthdate3=substr($birthdate,2,1);
$birthdate4=substr($birthdate,3,1);
$birthdate5=substr($birthdate,5,1);
$birthdate6=substr($birthdate,6,1);
$birthdate7=substr($birthdate,8,1);
$birthdate8=substr($birthdate,9,1);

//get CREW SEA DETAILS
$qryseadetails=mysql_query("SELECT cc.DATEEMB,cc.DATEDISEMB,v.VESSEL,v.GROSSTON,vs.ENGINEPOWER,v.VESSELTYPECODE,
	r.ALIAS2,mn.ALIAS,r.RANKTYPECODE,r.RANKING
	FROM crewchange cc
	LEFT JOIN vessel v ON cc.VESSELCODE=v.VESSELCODE
	LEFT JOIN rank r ON cc.RANKCODE=r.RANKCODE
	LEFT JOIN vesselspecs vs ON cc.VESSELCODE=vs.VESSELCODE
	LEFT JOIN management m ON v.MANAGEMENTCODE=m.MANAGEMENTCODE
	LEFT JOIN manning mn ON m.MANNINGCODE=mn.MANNINGCODE
	WHERE cc.DATEEMB<='$dateembcur' AND cc.APPLICANTNO=$applicantno
	ORDER BY cc.DATEEMB DESC
	LIMIT 4") or die(mysql_error());

$cntdata=0;
$rankingmax=10000;
while($rowseadetails=mysql_fetch_array($qryseadetails))
{
	$ranktypecode=$rowseadetails["RANKTYPECODE"];
	$ranking=$rowseadetails["RANKING"];
	$dateemb[$cntdata]=$rowseadetails["DATEEMB"];
	if($dateemb[$cntdata]==$dateembcur)
		$datedisemb[$cntdata]="PRESENT SHIP";
	else 
		$datedisemb[$cntdata]=$rowseadetails["DATEDISEMB"];
	$vessel[$cntdata]=$rowseadetails["VESSEL"];
	$grosston[$cntdata]=$rowseadetails["GROSSTON"];
	$enginepower[$cntdata]=$rowseadetails["ENGINEPOWER"];
	$vesseltypecode[$cntdata]=$rowseadetails["VESSELTYPECODE"];
	$rankalias[$cntdata]=$rowseadetails["ALIAS2"];
	$manningalias[$cntdata]=$rowseadetails["ALIAS"];
	//get highest rank
	if($rankingmax>$ranking)
	{
		$rankingmax=$ranking;
		$ranktypecodemax=$ranktypecode;
	}
	$cntdata++;
}
//EMPTY CELLS IF NOT COMPLETE
if($cntdata<=3)
{
	for($i=$cntdata;$i<=3;$i++)
	{
		$dateemb[$i]="&nbsp";
		$datedisemb[$i]="&nbsp;";
		$vessel[$i]="&nbsp;";
		$grosston[$i]="&nbsp;";
		$enginepower[$i]="&nbsp;";
		$vesseltypecode[$i]="&nbsp;";
		$rankalias[$i]="&nbsp;";
		$manningalias[$i]="&nbsp;";
	}
}
//GET LIST OF RANK HIGHER THAN RANKINGMAX
$qrygethigherrank=mysql_query("SELECT RANKCODE,ALIAS2 
	FROM rank
	WHERE RANKING<$rankingmax AND RANKTYPECODE='$ranktypecodemax'") or die(mysql_error());


//CREATE COLUMNS
$fixedcolumn="
<tr height=\"18px\" style=\"display:none;\">\n
	<td style=\"width:12px;\">&nbsp;</td>\n		<!--  1  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  2  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  3  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  4  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  5  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  6  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  7  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  8  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  9  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  10  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  11  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  12  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  13  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  14  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  15  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  16  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  17  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  18  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  19  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  20  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  21  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  22  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  23  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  24  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  25  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  26 -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  27  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  28  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  29  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  30  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  31  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  32  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  33  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  34  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  35  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  36  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  37  -->
	<td style=\"width:19px;\">&nbsp;</td>\n		<!--  38  -->
	<td style=\"width:12px;\">&nbsp;</td>\n		<!--  39  -->
</tr>
";
$cerstyle="border-top:1px solid black;border-left:1px solid black;";
$cerstylefont="font-size:10pt;font-family:Arial;";

//GET DATA FOR GIVEN CCID & EVALNO
if($uploaded!=1)
{
	$qrygetevaldetails=mysql_query("SELECT * FROM crewevalhdr WHERE CCID=$ccid AND EVALNO=$evalno") or die(mysql_error());
	if(mysql_num_rows($qrygetevaldetails)!=0)
	{
		$rowdtls=mysql_fetch_array($qrygetevaldetails);
		$evaldate = $rowdtls["EVALDATE"];
		$evaldateshow=date("Y-m-d",strtotime($evaldate));
		$vpc1 = $rowdtls["VPC1"];
		$vpc2 = $rowdtls["VPC2"];
		$vpc3 = $rowdtls["VPC3"];
		$vpc4 = $rowdtls["VPC4"];
		$vpc5 = $rowdtls["VPC5"];
		$vpc6 = $rowdtls["VPC6"];
		$vpc1rem = stripslashes($rowdtls["VPC1REM"]);
		$vpc2rem = stripslashes($rowdtls["VPC2REM"]);
		$vpc3rem = stripslashes($rowdtls["VPC3REM"]);
		$vpc4rem = stripslashes($rowdtls["VPC4REM"]);
		$vpc5rem = stripslashes($rowdtls["VPC5REM"]);
		$vpc6rem = stripslashes($rowdtls["VPC6REM"]);
		$vee1 = $rowdtls["VEE1"];
		$vee1rem = stripslashes($rowdtls["VEE1REM"]);
		$fleetkept = $rowdtls["FLEETKEPT"];
		$vep0 = $rowdtls["VEP0"];
		$vep0rem = stripslashes($rowdtls["VEP0REM"]);
		$nextrank = $rowdtls["NEXTRANK"];
		$vep1 = $rowdtls["VEP1"];
		$vep2 = $rowdtls["VEP2"];
		$vep3 = $rowdtls["VEP3"];
		$vep4 = $rowdtls["VEP4"];
		$negdiscussed = $rowdtls["NEGDISCUSSED"];
		$var1 = $rowdtls["VAR1"];
		$var2 = $rowdtls["VAR2"];
		$var3 = $rowdtls["VAR3"];
		$var4 = $rowdtls["VAR4"];
		$vpj1 = $rowdtls["VPJ1"];
		$vpj2 = $rowdtls["VPJ2"];
		$vpj3 = $rowdtls["VPJ3"];
		$vpj4 = $rowdtls["VPJ4"];
		$vsm1 = $rowdtls["VSM1"];
		$vsm2 = $rowdtls["VSM2"];
		$vsm3 = $rowdtls["VSM3"];
		$vpr1 = $rowdtls["VPR1"];
		$vpr2 = $rowdtls["VPR2"];
		$vpr3 = $rowdtls["VPR3"];
		$vbe1 = $rowdtls["VBE1"];
		$vbe2 = $rowdtls["VBE2"];
		$vbe3 = $rowdtls["VBE3"];
		$vsc1 = $rowdtls["VSC1"];
		$vsc2 = $rowdtls["VSC2"];
		$vsc3 = $rowdtls["VSC3"];
		$captname1 = $rowdtls["CAPTNAME1"];
		$date1 = $rowdtls["DATE1"];
		$captname2 = $rowdtls["CAPTNAME2"];
		$date2 = $rowdtls["DATE2"];
		$principalcomment = stripslashes($rowdtls["PRINCIPALCOMMENT"]);
		$vmccomment = stripslashes($rowdtls["VMCCOMMENT"]);
		$extratrain = $rowdtls["EXTRATRAIN"];
		$repextratrain = $rowdtls["REPEXTRATRAIN"];
		$repextratrainrem = stripslashes($rowdtls["REPEXTRATRAINREM"]);
		$remarks = $rowdtls["REMARKS"];
		$newtraincode1 = $rowdtls["NEWTRAINCODE1"];
		$newtrainoth1 = stripslashes($rowdtls["NEWTRAINOTH1"]);
		$newtraincode2 = $rowdtls["NEWTRAINCODE2"];
		$newtrainoth2 = stripslashes($rowdtls["NEWTRAINOTH2"]);
		$newtraincode3 = $rowdtls["NEWTRAINCODE3"];
		$newtrainoth3 = stripslashes($rowdtls["NEWTRAINOTH3"]);
		$efe1 = $rowdtls["EFE1"];
		$efeby1 = $rowdtls["EFEBY1"];
		$efedate1 = $rowdtls["EFEDATE1"];
		$efe2 = $rowdtls["EFE2"];
		$efeby2 = $rowdtls["EFEBY2"];
		$efedate2 = $rowdtls["EFEDATE2"];
		$efe3 = $rowdtls["EFE3"];
		$efeby3 = $rowdtls["EFEBY3"];
		$efedate3 = $rowdtls["EFEDATE3"];
		$epr1 = $rowdtls["EPR1"];
		$eprby1 = $rowdtls["EPRBY1"];
		$eprdate1 = $rowdtls["EPRDATE1"];
		$epr2 = $rowdtls["EPR2"];
		$eprby2 = $rowdtls["EPRBY2"];
		$eprdate2 = $rowdtls["EPRDATE2"];
		$epr3 = $rowdtls["EPR3"];
		$eprby3 = $rowdtls["EPRBY3"];
		$eprdate3 = $rowdtls["EPRDATE3"];
		$madeby = $rowdtls["MADEBY"];
		$madedate = $rowdtls["MADEDATE"];
		$closedby = $rowdtls["CLOSEDBY"];
		$closeddate = $rowdtls["CLOSEDDATE"];
		//arrange radiobox
		$efe1val="efe1".$efe1;
		$$efe1val="checked=\"checked\"";
		$efe2val="efe2".$efe2;
		$$efe2val="checked=\"checked\"";
		$efe3val="efe3".$efe3;
		$$efe3val="checked=\"checked\"";
		$epr1val="epr1".$epr1;
		$$epr1val="checked=\"checked\"";
		$epr2val="epr2".$epr2;
		$$epr2val="checked=\"checked\"";
		$epr3val="epr3".$epr3;
		$$epr3val="checked=\"checked\"";
		$extratrainval="extratrain".$extratrain;
		$$extratrainval="checked=\"checked\"";
		$repextratrainval="repextratrain".$repextratrain;
		$$repextratrainval="checked=\"checked\"";
	}
	else 
	{
		$placemsg="&nbsp;&nbsp;<span style=\"font-size:1.5em;color:red;font-weight:bold;\"><i>(No data found!)</i></span>";
		$evaldateshow = date("Y-m-d");
		$vpc1 = "";
		$vpc2 = "";
		$vpc3 = "";
		$vpc4 = "";
		$vpc5 = "";
		$vpc6 = "";
		$vpc1rem = "";
		$vpc2rem = "";
		$vpc3rem = "";
		$vpc4rem = "";
		$vpc5rem = "";
		$vpc6rem = "";
		$vee1 = "";
		$vee1rem = "";
		$fleetkept = "";
		$vep0 = "";
		$vep0rem = "";
		$nextrank = "";
		$vep1 = "";
		$vep2 = "";
		$vep3 = "";
		$vep4 = "";
		$negdiscussed = "";
		$var1 = "";
		$var2 = "";
		$var3 = "";
		$var4 = "";
		$vpj1 = "";
		$vpj2 = "";
		$vpj3 = "";
		$vpj4 = "";
		$vsm1 = "";
		$vsm2 = "";
		$vsm3 = "";
		$vpr1 = "";
		$vpr2 = "";
		$vpr3 = "";
		$vbe1 = "";
		$vbe2 = "";
		$vbe3 = "";
		$vsc1 = "";
		$vsc2 = "";
		$vsc3 = "";
		$captname1 = "";
		$date1 = "";
		$captname2 = "";
		$date2 = "";
		$principalcomment = "";
		$vmccomment = "";
		$extratrain = "";
		$repextratrain = "";
		$repextratrainrem = "";
		$remarks = "";
		$newtraincode1 = "";
		$newtrain1 = "";
		$newtrainoth1 = "";
		$newtraincode2 = "";
		$newtrain2 = "";
		$newtrainoth2 = "";
		$newtraincode3 = "";
		$newtrain3 = "";
		$newtrainoth3 = "";
		$efe1 = "";
		$efeby1 = "";
		$efedate1 = "";
		$efe2 = "";
		$efeby2 = "";
		$efedate2 = "";
		$efe3 = "";
		$efeby3 = "";
		$efedate3 = "";
		$epr1 = "";
		$eprby1 = "";
		$eprdate1 = "";
		$epr2 = "";
		$eprby2 = "";
		$eprdate2 = "";
		$epr3 = "";
		$eprby3 = "";
		$eprdate3 = "";
		$madeby = "";
		$madedate = "";
	}
}
//disable if closed
if(!empty($closeddate))
{
	$disableall="disabled=\"disabled\"";
	$disable=1;
	$bgcolor="background:white;";
}
else 
	$bgcolor="background:white";

$dirfilename = $basedirdocs . $applicantno . "/CER/" . $ccid . "_" . $evalno . ".pdf";

if (checkpath($dirfilename,1))
	$btnviewpdf = "<input type=\"button\" onclick=\"openWindow('$dirfilename', 'cerpdf' ,800, 500);\" value=\"View PDF\">";
else 
	$btnviewpdf = "<span style=\"color:Red;font-size:0.8em;font-weight:Bold;\">(NO SCANNED PDF)</scan>";

$btnnext="<input type=\"button\" onclick=\"switchdisplay('faceb');\" value=\"Next >>\">";
$btnprev="<input type=\"button\" onclick=\"switchdisplay('facea');\" value=\"<< Previous\">";
$btnsave="<input $disableall type=\"button\" onclick=\"actiontxt.value='save';crewevaluation.submit();\" value=\"Save\">";

echo	"<html>\n
<title>CREW EVALUATION REPORT</title>
<head>\n
<link rel=\"StyleSheet\" type=\"text/css\" href=\"../veripro.css\" />
<style>
#noprint
{
	display: none;
}
</style>
<script type='text/javascript' src='../veritas/veripro.js'></script>
<script>
function chkdate(which)
{
	now = new Date;
	getyr=now.getFullYear();
	locs = which.value.split('-');
	chkdelete='';
	mm=locs[1];
	dd=locs[2];
	yy=locs[0];
	if(locs.length < 2)
	{
		chkdelete='yes';
	}
	else
	{
		if(dd=='')
			chkdelete='yes';
		if(mm.length==1)
			mm='0' + mm;
		else if(mm.length==2)
			if(mm > 12 || mm < 01)
				chkdelete='yes';
		else if(mm.length > 2)
			chkdelete='yes';
		
		if(yy!=null || yy=='')
		{
//			alert(yy);
			if(yy.length == 0)
				yy=getyr;
			else if(yy.length == 1)
				yy='200' + yy;
			else if(yy.length == 2)
				yy='20' + yy;
			else if(yy.length == 3)
				chkdelete='yes';
			else if(yy.length > 4)
				chkdelete='yes';
			if(yy > getyr+1 || yy < (getyr-1))
				chkdelete='yes';
		}
		else
		{
			yy=getyr;
		}
		
		if(dd.length==1)
			dd='0' + dd;
		else if(dd.length==2)
		{
			if(mm==02)
			{
				if(yy==2008 || yy==2012 || yy==2016 || yy==2020)
				{
					if(dd > 29 || dd < 01)
						chkdelete='yes';
				}
				else
				{
					if(dd > 28 || dd < 01)
						chkdelete='yes';
				}
			}
			else if(mm == 04 || mm == 06 || mm == 09 || mm == 11)
			{
				if(dd > 30 || dd < 01)
					chkdelete='yes';
				//alert(dd);
			}					
			else if(mm == 01 || mm == 03 || mm == 05 || mm == 07 || mm == 08 || mm == 10 || mm == 12)
			{
				if(dd > 31 || dd < 01)
					chkdelete='yes';
				//alert(dd);
			}
			
		}
		else if(dd.length > 2)
			chkdelete='yes';
		//alert(chkdelete);
	}
	if(chkdelete=='yes')
	{
		which.value='';
		which.focus();
	}
	else
	{
		which.value=yy + '-' + mm + '-' + dd;
		event.keyCode=9;
		//leaddate=delivery.lead.value.substring(0,10);
		//checkDate(leaddate,which.value);
	}
}
function switchdisplay(x)
{
	document.getElementById('facea').style.display='none';
	document.getElementById('faceb').style.display='none';
	document.getElementById(x).style.display='block';
}
var var1arr=new Array(0,0,2,6,8);
var var2arr=new Array(0,0,3,9,12);
var var3arr=new Array(0,0,1,3,4);
var var4arr=new Array(0,0,1,3,4);

var vpj1arr=new Array(0,0,2,6,8);
var vpj2arr=new Array(0,0,2,6,8);
var vpj3arr=new Array(0,0,2,6,8);
var vpj4arr=new Array(0,0,1,3,4);

var vsm1arr=new Array(0,0,1,3,4);
var vsm2arr=new Array(0,0,1,3,4);
var vsm3arr=new Array(0,0,1,3,4);

var vpr1arr=new Array(0,0,1,3,4);
var vpr2arr=new Array(0,0,2,6,8);
var vpr3arr=new Array(0,0,1,3,4);

var vbe1arr=new Array(0,0,2,6,8);
var vbe2arr=new Array(0,0,1,3,4);
var vbe3arr=new Array(0,0,1,3,4);


function codefunc(x,y,z) //x-code; y-no.of selection; z-no. of subcode
{
	if(document.crewevaluation(x).value<=y)
	{
		document.getElementById(x+'1').innerHTML='&nbsp;';
		if(y>=2)
			document.getElementById(x+'2').innerHTML='&nbsp;';
		if(y>=3)
			document.getElementById(x+'3').innerHTML='&nbsp;';
		if(y>=4)
			document.getElementById(x+'4').innerHTML='&nbsp;';
		if(document.crewevaluation(x).value!='')
		{
			if(document.crewevaluation(x).value!='')
			{
				document.getElementById(x+document.crewevaluation(x).value).innerHTML='X';
			}
		}
		if(z)
		{
			var getcode=x.substring(0,3);
			var subtot=0;
			for(var i=1;i<=z;i++)
			{
				if(document.crewevaluation(getcode+i).value)
					subtot+=eval(getcode+i+'arr['+document.crewevaluation(getcode+i).value+']');
			}
			document.getElementById(getcode+'sub').value=subtot;
			//get TOTAL SCORE
			var totscore=0;
			if(document.getElementById('varsub').value)
				totscore += document.getElementById('varsub').value*1;
			if(document.getElementById('vpjsub').value)
				totscore += document.getElementById('vpjsub').value*1;
			if(document.getElementById('vsmsub').value)
				totscore += document.getElementById('vsmsub').value*1;
			if(document.getElementById('vprsub').value)
				totscore += document.getElementById('vprsub').value*1;
			if(document.getElementById('vbesub').value)
				totscore += document.getElementById('vbesub').value*1;
			document.getElementById('totalscore').value=totscore;
		}
	}
	else
	{
		alert('Value should be 1 to ' + y);
		document.crewevaluation(x).value='';
	}
}


var trainingcode = new Array();\n
var training = new Array();\n";
//for additional training details
$qrytraining=mysql_query("SELECT TRAINCODE,TRAINING
	FROM trainingcourses
	ORDER BY TRAINCODE") or die(mysql_error());
$cntdata=0;
$getselecttraining="";
while($rowtraining=mysql_fetch_array($qrytraining))
{
	$traincode=addslashes($rowtraining["TRAINCODE"]);
	$training=addslashes($rowtraining["TRAINING"]);
	echo "
	trainingcode[$cntdata]=new Array('$traincode','$training');\n
	training['$traincode']='$training'";
	if($newtraincode1==$traincode)
		$selected1="selected";
	else 
		$selected1="";
	if($newtraincode2==$traincode)
		$selected2="selected";
	else 
		$selected2="";
	if($newtraincode3==$traincode)
		$selected3="selected";
	else 
		$selected3="";
	$getselecttraining1 .= "<option $selected1 value='$traincode'>$training</option>";
	$getselecttraining2 .= "<option $selected2 value='$traincode'>$training</option>";
	$getselecttraining3 .= "<option $selected3 value='$traincode'>$training</option>";
	$cntdata++;
}
echo "
function selecttrainingcode(x,y) //x - array name; y - show row
{
	if(training[x])
		document.crewevaluation('newtrain'+y).value=x;
	else
	{
		document.crewevaluation('newtraincode'+y).value='';
		alert('No Code found!');
	}
}
function chkchosen(x,y)//x - id; y - 1, loading & 0, not loading
{
	//check status
	if('$disable'!=1 || y==1)
	{
		if(document.getElementById('neg'+x).style.textDecoration=='none')
		{
			document.getElementById('negyes').style.textDecoration='none'
			document.getElementById('negno').style.textDecoration='none'
			document.getElementById('neg'+x).style.textDecoration='underline';
			document.crewevaluation.neghidden.value=x;
		}
		else
		{
			document.getElementById('neg'+x).style.textDecoration='none';
			document.crewevaluation.neghidden.value='NULL';
		}
	}
	else
		alert('CER already approved!');
}
</script>\n

</head>\n

<body onload=\"\" style=\"\">\n

<center>
<form name=\"crewevaluation\" id=\"crewevaluation\" method=\"POST\" enctype=\"multipart/form-data\">\n
	<div id=\"facea\">
	";
	if (!empty($uploadmsg))
		echo "<span style=\"font-size:0.9em;font-weight:Bold;color:Red;\">Excel Upload Message: $uploadmsg</span><br />";
		
	if (!empty($uploadmsg2))
		echo "<span style=\"font-size:0.9em;font-weight:Bold;color:Red;\">PDF Upload Message: $uploadmsg2</span><br />";
		
	echo "
		$btnnext&nbsp;&nbsp;&nbsp;&nbsp;$btnviewpdf
<!--
		<table cellspacing=\"0\" cellpadding=\"0\" width=\"$tablewidth\" 
			style=\"table-layout:fixed;font-family:Arial;font-size:0.8em;\">
			<tr>
				<td width=\"7%\">$btnnext</td>
				<td width=\"7%\">$btnsave</td>

				<td style=\"text-align:right;\">
					<b>Upload Excel file:</b>&nbsp;
					<input $disableall name=\"uploadedfile_xls\" type=\"file\" size=\"20\" style=\"visibility:show\" />
					<input $disableall name=\"uploaded\" type=\"button\" 
						onclick=\"if (uploadedfile_xls.value != ''){actiontxt.value='upload';crewevaluation.submit();}
						else {alert('No File Selected!');}\"
						value=\"Upload\" />
					<br />
					<b>Upload PDF file:</b>&nbsp;
					<input $disableall name=\"uploadedfile_pdf\" type=\"file\" size=\"20\" style=\"visibility:show\" />
					<input $disableall name=\"uploaded\" type=\"button\" 
						onclick=\"if (uploadedfile_pdf.value != ''){actiontxt.value='uploadpdf';crewevaluation.submit();}
						else {alert('No File Selected!');}\"
						value=\"Upload\" />
					<br />

				</td>
			</tr>
		</table>
-->
		<hr />


		<!--<input type=\"button\" style=\"float:left;\" onclick=\"switchdisplay('faceb');\" value=\"Next\">-->
		<table cellspacing=\"0\" cellpadding=\"0\" width=\"$tablewidth\" 
			style=\"table-layout:fixed;font-family:Arial;\">
			$fixedcolumn
			<!--HEADER-->
			<tr height=\"25px\" style=\"text-align:center;valign:middle;\">\n
				<td colspan=\"39\" style=\"text-align:center;font-size:12pt;font-weight:bold;\">
					( C R E W&nbsp;&nbsp;&nbsp;E V A L U A T I O N&nbsp;&nbsp;&nbsp;R E P O R T)
				</td>\n
			</tr>
			<tr height=\"17px\" style=\"text-align:center;valign:middle;\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"18\" style=\"text-align:left;font-size:8pt;\">(FACE-A)</td>\n
				<td colspan=\"19\" style=\"text-align:right;font-size:8pt;\">2006.4.1</td>\n
			</tr>
		</table>
		<table cellspacing=\"0\" cellpadding=\"0\" width=\"$tablewidth\" 
			style=\"table-layout:fixed;border:1px solid black;font-family:Arial;font-size:8pt;\">
			$fixedcolumn
			<tr height=\"16px\">\n
				<td colspan=\"21\">&nbsp;</td>\n
				<td style=\"border-bottom:1px dotted black;border-right:1px dotted black;border-left:1px dotted black;cursor:pointer;\"><input type=\"radio\" name=\"evalnoradio\" $evalno1 $evalno1disabled onclick=\"crewevaluation.submit();\" value=\"evalno1\"></td>\n
				<td colspan=\"17\">&nbsp;&nbsp;1.&nbsp;&nbsp;TO BE EVALUATED 4 MONTHS AFTER EMBARKATION</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"5\">SHIP NAME</td>\n
				<td colspan=\"14\" style=\"font-weight:bold;\">$vesselname $placemsg</td>\n
				<td>&nbsp;</td>\n
				<td style=\"border-bottom:1px dotted black;border-right:1px dotted black;border-left:1px dotted black;cursor:pointer;\"><input type=\"radio\" name=\"evalnoradio\" $evalno2 $evalno2disabled onclick=\"crewevaluation.submit();\" value=\"evalno2\"></td>\n
				<td colspan=\"17\">&nbsp;&nbsp;2.&nbsp;&nbsp;TO BE EVALUATED 1 MONTH BEFORE DISEMBARKING</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"21\">&nbsp;</td>\n
				<td style=\"border-bottom:1px dotted black;border-right:1px dotted black;border-left:1px dotted black;cursor:pointer;\"><input type=\"radio\" name=\"evalnoradio\" $evalno3 $evalno3disabled onclick=\"crewevaluation.submit();\" value=\"evalno3\"></td>\n
				<td colspan=\"8\" style=\"text-align:left;\">&nbsp;&nbsp;3.&nbsp;&nbsp;SIGN OFF MASTER</td>\n
				<td style=\"border-top:1px dotted black;border-bottom:1px dotted black;border-right:1px dotted black;border-left:1px dotted black;cursor:pointer;\"><input type=\"radio\" name=\"evalnoradio\" $evalno4 $evalno4disabled onclick=\"crewevaluation.submit();\" value=\"evalno4\"></td>\n
				<td colspan=\"7\">&nbsp;&nbsp;4.&nbsp;&nbsp;SIGN OFF CH.ENGR.</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"20\">(1) PERSONAL DATA</td>\n
				<td colspan=\"17\">&nbsp;&nbsp; &#x2191;To be classified</td>\n
			</tr>
			<tr height=\"13px\">\n
				<td colspan=\"8\">&nbsp;</td>\n
				<td colspan=\"4\" style=\"text-align:center;\">YEAR</td>\n
				<td colspan=\"2\" style=\"text-align:center;\">MO</td>\n
				<td colspan=\"2\" style=\"text-align:center;\">DATE</td>\n
				<td colspan=\"6\">&nbsp;</td>\n
				<td colspan=\"7\" style=\"text-align:center;\">FIRST</td>\n
				<td colspan=\"2\" style=\"text-align:center;\">MIDDLE</td>\n
				<td colspan=\"7\" style=\"text-align:center;\">FAMILY</td>\n
			</tr>
			<tr height=\"21px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"4\">DATE OF BIRTH</td>\n
				<td colspan=\"2\" style=\"text-align:center;\">:</td>\n
				<td style=\"border-top:1px solid black;border-bottom:1px solid black;border-right:1px dotted black;border-left:1px solid black;font-weight:bold;text-align:center;\">$birthdate1</td>\n
				<td style=\"border-top:1px solid black;border-bottom:1px solid black;border-right:1px dotted black;font-weight:bold;text-align:center;\">$birthdate2</td>\n
				<td style=\"border-top:1px solid black;border-bottom:1px solid black;border-right:1px dotted black;font-weight:bold;text-align:center;\">$birthdate3</td>\n
				<td style=\"border-top:1px solid black;border-bottom:1px solid black;border-right:1px solid black;font-weight:bold;text-align:center;\">$birthdate4</td>\n
				<td style=\"border-top:1px solid black;border-bottom:1px solid black;border-right:1px dotted black;font-weight:bold;text-align:center;\">$birthdate5</td>\n
				<td style=\"border-top:1px solid black;border-bottom:1px solid black;border-right:1px solid black;font-weight:bold;text-align:center;\">$birthdate6</td>\n
				<td style=\"border-top:1px solid black;border-bottom:1px solid black;border-right:1px dotted black;font-weight:bold;text-align:center;\">$birthdate7</td>\n
				<td style=\"border-top:1px solid black;border-bottom:1px solid black;border-right:1px solid black;font-weight:bold;text-align:center;\">$birthdate8</td>\n
				<td colspan=\"3\">&nbsp;</td>\n
				<td colspan=\"2\">NAME</td>\n
				<td style=\"text-align:center;\">:</td>\n
				<td colspan=\"7\" style=\"border-top:1px solid black;border-bottom:1px solid black;border-right:1px dotted black;border-left:1px solid black;font-weight:bold;text-align:center;\">$gname</td>\n
				<td colspan=\"2\" style=\"border-top:1px solid black;border-bottom:1px solid black;border-right:1px dotted black;font-weight:bold;text-align:center;\">$mname</td>\n
				<td colspan=\"7\" style=\"border-top:1px solid black;border-bottom:1px solid black;border-right:1px solid black;font-weight:bold;text-align:center;\">$fname</td>\n
			</tr>
			<tr height=\"12px\">\n
				<td>&nbsp;</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"4\">NATIONALITY</td>\n
				<td colspan=\"2\" style=\"text-align:center;\">:</td>\n
				<td colspan=\"9\" style=\"border-bottom:1px solid black;font-weight:bold;text-align:center;\">$nationality</td>\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"2\">RANK</td>\n
				<td style=\"text-align:center;\">:</td>\n
				<td colspan=\"8\" style=\"border-bottom:1px solid black;font-weight:bold;text-align:center;\">$rank</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"24\">&nbsp;</td>\n
				<td colspan=\"7\">&#x2193;(Enter \"Y\" if he is.)</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"20\">&nbsp;</td>\n
				<td colspan=\"4\">Scholarship:</td>\n
				<td style=\"border-bottom:1px solid black;font-weight:bold;text-align:center;\">$scholasticshow</td>\n
				<td>&nbsp;</td>\n
				<td colspan=\"2\">(Type:</td>\n
				<td colspan=\"9\" style=\"border-bottom:1px solid black;font-weight:bold;\">&nbsp;$scholasticcode</td>\n
				<td>)</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td rowspan=\"2\">&nbsp;</td>\n
				<td rowspan=\"2\" colspan=\"25\">(2) LICENSE OF COMPETENCE [KIND OF LICENSE etc.]&nbsp;&nbsp;(Master(D1), 1AE(E2), … etc.)</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"3\" style=\"font-size:6pt;align:bottom;\">OTHER</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"6\" style=\"text-align:right;\">NATIONAL :</td>\n
				<td colspan=\"6\" style=\"border-bottom:1px solid black;\">&nbsp;</td>\n
				<td>&nbsp;</td>\n
				<td colspan=\"6\" style=\"text-align:right;\">FLAG(PAN,LIB etc) :</td>\n
				<td colspan=\"6\" style=\"border-bottom:1px solid black;\">&nbsp;</td>\n
				<td>&nbsp;</td>\n
				<td style=\"text-align:right;\">(</td>\n
				<td colspan=\"4\" style=\"border-bottom:1px solid black;\">&nbsp;</td>\n
				<td>) :</td>\n
				<td colspan=\"6\" style=\"border-bottom:1px solid black;\">&nbsp;</td>\n
			</tr>
			<tr height=\"8px\">\n
				<td>&nbsp;</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"10\">(3) HISTORY OF SEA SERVICE</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"6\" style=\"text-align:center;border-top:1px solid black;border-left:1px solid black;border-right:1px solid black;border-bottom:1px dotted black;\">ON DATE</td>\n
				<td colspan=\"8\" rowspan=\"2\" style=\"text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;\">SHIP NAME</td>\n
				<td colspan=\"6\" style=\"text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom:1px dotted black;\">GROSS TONNAGE</td>\n
				<td colspan=\"4\" rowspan=\"2\" style=\"text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;\">VSL TYPE</td>\n
				<td colspan=\"4\" rowspan=\"2\" style=\"text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;\">RANK</td>\n
				<td colspan=\"4\" rowspan=\"2\" style=\"text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;\">MANNING CO.</td>\n
				<td colspan=\"4\" rowspan=\"2\" style=\"text-align:center;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;\">OPERATOR</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"6\" style=\"text-align:center;border-bottom:1px solid black;border-left:1px solid black;border-right:1px solid black;\">OFF DATE</td>\n
				<td colspan=\"6\" style=\"text-align:center;border-bottom:1px solid black;border-right:1px solid black;\">PROP.POWER</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"6\" style=\"text-align:center;border-left:1px solid black;border-right:1px solid black;border-bottom:1px dotted black;font-weight:bold;text-align:center;\">{$dateemb[3]}</td>\n
				<td colspan=\"8\" rowspan=\"2\" style=\"text-align:center;border-right:1px solid black;border-bottom:1px solid black;font-weight:bold;text-align:center;\">{$vessel[3]}</td>\n
				<td colspan=\"6\" style=\"text-align:center;border-right:1px solid black;border-bottom:1px dotted black;font-weight:bold;text-align:center;\">{$grosston[3]}</td>\n
				<td colspan=\"4\" rowspan=\"2\" style=\"text-align:center;border-right:1px solid black;border-bottom:1px solid black;font-weight:bold;text-align:center;\">{$vesseltypecode[3]}</td>\n
				<td colspan=\"4\" rowspan=\"2\" style=\"text-align:center;border-right:1px solid black;border-bottom:1px solid black;font-weight:bold;text-align:center;\">{$rankalias[3]}</td>\n
				<td colspan=\"4\" rowspan=\"2\" style=\"text-align:center;border-right:1px solid black;border-bottom:1px solid black;font-weight:bold;text-align:center;\">&nbsp;{$manningalias[3]}</td>\n
				<td colspan=\"4\" rowspan=\"2\" style=\"text-align:center;border-right:1px solid black;border-bottom:1px solid black;font-weight:bold;text-align:center;\">&nbsp;{$manningalias[3]}</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"6\" style=\"text-align:center;border-bottom:1px solid black;border-left:1px solid black;border-right:1px solid black;font-weight:bold;text-align:center;\">{$datedisemb[3]}</td>\n
				<td colspan=\"6\" style=\"text-align:center;border-bottom:1px solid black;border-right:1px solid black;font-weight:bold;text-align:center;\">{$enginepower[3]}</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"6\" style=\"text-align:center;border-left:1px solid black;border-right:1px solid black;border-bottom:1px dotted black;font-weight:bold;text-align:center;\">{$dateemb[2]}</td>\n
				<td colspan=\"8\" rowspan=\"2\" style=\"text-align:center;border-right:1px solid black;border-bottom:1px solid black;font-weight:bold;text-align:center;\">{$vessel[2]}</td>\n
				<td colspan=\"6\" style=\"text-align:center;border-right:1px solid black;border-bottom:1px dotted black;font-weight:bold;text-align:center;\">{$grosston[2]}</td>\n
				<td colspan=\"4\" rowspan=\"2\" style=\"text-align:center;border-right:1px solid black;border-bottom:1px solid black;font-weight:bold;text-align:center;\">{$vesseltypecode[2]}</td>\n
				<td colspan=\"4\" rowspan=\"2\" style=\"text-align:center;border-right:1px solid black;border-bottom:1px solid black;font-weight:bold;text-align:center;\">{$rankalias[2]}</td>\n
				<td colspan=\"4\" rowspan=\"2\" style=\"text-align:center;border-right:1px solid black;border-bottom:1px solid black;font-weight:bold;text-align:center;\">&nbsp;{$manningalias[2]}</td>\n
				<td colspan=\"4\" rowspan=\"2\" style=\"text-align:center;border-right:1px solid black;border-bottom:1px solid black;font-weight:bold;text-align:center;\">&nbsp;{$manningalias[2]}</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"6\" style=\"text-align:center;border-bottom:1px solid black;border-left:1px solid black;border-right:1px solid black;font-weight:bold;text-align:center;\">{$datedisemb[2]}</td>\n
				<td colspan=\"6\" style=\"text-align:center;border-bottom:1px solid black;border-right:1px solid black;font-weight:bold;text-align:center;\">{$enginepower[2]}</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"6\" style=\"text-align:center;border-left:1px solid black;border-right:1px solid black;border-bottom:1px dotted black;font-weight:bold;text-align:center;\">{$dateemb[1]}</td>\n
				<td colspan=\"8\" rowspan=\"2\" style=\"text-align:center;border-right:1px solid black;border-bottom:1px solid black;font-weight:bold;text-align:center;\">{$vessel[1]}</td>\n
				<td colspan=\"6\" style=\"text-align:center;border-right:1px solid black;border-bottom:1px dotted black;font-weight:bold;text-align:center;\">{$grosston[1]}</td>\n
				<td colspan=\"4\" rowspan=\"2\" style=\"text-align:center;border-right:1px solid black;border-bottom:1px solid black;font-weight:bold;text-align:center;\">{$vesseltypecode[1]}</td>\n
				<td colspan=\"4\" rowspan=\"2\" style=\"text-align:center;border-right:1px solid black;border-bottom:1px solid black;font-weight:bold;text-align:center;\">{$rankalias[1]}</td>\n
				<td colspan=\"4\" rowspan=\"2\" style=\"text-align:center;border-right:1px solid black;border-bottom:1px solid black;font-weight:bold;text-align:center;\">&nbsp;{$manningalias[1]}</td>\n
				<td colspan=\"4\" rowspan=\"2\" style=\"text-align:center;border-right:1px solid black;border-bottom:1px solid black;font-weight:bold;text-align:center;\">&nbsp;{$manningalias[1]}</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"6\" style=\"text-align:center;border-bottom:1px solid black;border-left:1px solid black;border-right:1px solid black;font-weight:bold;text-align:center;\">{$datedisemb[1]}</td>\n
				<td colspan=\"6\" style=\"text-align:center;border-bottom:1px solid black;border-right:1px solid black;font-weight:bold;text-align:center;\">{$enginepower[1]}</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"6\" style=\"text-align:center;border-left:1px solid black;border-right:1px solid black;border-bottom:1px dotted black;font-weight:bold;text-align:center;\">{$dateemb[0]}</td>\n
				<td colspan=\"8\" rowspan=\"2\" style=\"text-align:center;border-right:1px solid black;border-bottom:1px solid black;font-weight:bold;text-align:center;\">{$vessel[0]}</td>\n
				<td colspan=\"6\" style=\"text-align:center;border-right:1px solid black;border-bottom:1px dotted black;font-weight:bold;text-align:center;\">{$grosston[0]}</td>\n
				<td colspan=\"4\" rowspan=\"2\" style=\"text-align:center;border-right:1px solid black;border-bottom:1px solid black;font-weight:bold;text-align:center;\">{$vesseltypecode[0]}</td>\n
				<td colspan=\"4\" rowspan=\"2\" style=\"text-align:center;border-right:1px solid black;border-bottom:1px solid black;font-weight:bold;text-align:center;\">{$rankalias[0]}</td>\n
				<td colspan=\"4\" rowspan=\"2\" style=\"text-align:center;border-right:1px solid black;border-bottom:1px solid black;font-weight:bold;text-align:center;\">&nbsp;{$manningalias[0]}</td>\n
				<td colspan=\"4\" rowspan=\"2\" style=\"text-align:center;border-right:1px solid black;border-bottom:1px solid black;font-weight:bold;text-align:center;\">&nbsp;{$manningalias[0]}</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"6\" style=\"text-align:center;border-bottom:1px solid black;border-left:1px solid black;border-right:1px solid black;font-weight:bold;text-align:center;\">{$datedisemb[0]}</td>\n
				<td colspan=\"6\" style=\"text-align:center;border-bottom:1px solid black;border-right:1px solid black;font-weight:bold;text-align:center;\">{$enginepower[0]}</td>\n
			</tr>
			<tr height=\"8px\">\n
				<td>&nbsp;</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"18\">(4) PERSONAL CHARACTER / PARTICULAR MATTER</td>\n
				<td colspan=\"19\">COMMENT for Personal \"Strength & Weakness\" and others</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"9\" style=\"text-align:right;border:1px solid black;\">INPUT\"1\"-\"4\"&#x2193;&nbsp;</td>\n
				<td colspan=\"2\" style=\"text-align:center;border-top:1px solid black;border-bottom:1px solid black;\">Exc.</td>\n
				<td colspan=\"2\" style=\"text-align:center;border-top:1px solid black;border-bottom:1px solid black;\">Good</td>\n
				<td colspan=\"2\" style=\"text-align:center;border-top:1px solid black;border-bottom:1px solid black;\">Poor</td>\n
				<td colspan=\"2\" style=\"text-align:center;border-top:1px solid black;border-bottom:1px solid black;border-right:1px solid black;\">Bad</td>\n
				<td colspan=\"7\" style=\"border-bottom:1px solid black;\">&nbsp;</td>\n
				<td colspan=\"12\" style=\"border-bottom:1px solid black;\">-To be filled by Master / CH.Engr</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"8\" style=\"border-left:1px solid black;border-bottom:1px dotted black;\">&nbsp;(VPC1) sense of responsibility</td>\n
				<td style=\"border-right:1px solid black;border-bottom:1px dotted black;\">
					<input type=\"hidden\" name=\"vpc1\" size=\"1\" value=\"$vpc1\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;font-weight:bold;border:0;text-align:center;\" 
						onkeypress=\"return numbersonly(this);\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"codefunc('vpc1',4);\">
						$vpc1
				</td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px dotted black;\"><span id=\"vpc14\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px dotted black;\"><span id=\"vpc13\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px dotted black;\"><span id=\"vpc12\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px dotted black;border-right:1px solid black;\"><span id=\"vpc11\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"19\" style=\"text-align:center;border-bottom:1px dotted black;border-right:1px solid black;\">
					<input type=\"hidden\" 
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;\" 
						size=\"67\" name=\"vpc1rem\" value=\"$vpc1rem\">
					$vpc1rem
				</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"8\" style=\"border-left:1px solid black;border-bottom:1px dotted black;\">&nbsp;(VPC2)  activity</td>\n
				<td style=\"border-right:1px solid black;border-bottom:1px dotted black;\">
					<input type=\"hidden\" name=\"vpc2\" size=\"1\"  value=\"$vpc2\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\" 
						onkeypress=\"return numbersonly(this);\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"codefunc('vpc2',4);\">
					$vpc2
				</td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px dotted black;\"><span id=\"vpc24\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px dotted black;\"><span id=\"vpc23\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px dotted black;\"><span id=\"vpc22\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px dotted black;border-right:1px solid black;\"><span id=\"vpc21\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"19\" style=\"text-align:center;border-bottom:1px dotted black;border-right:1px solid black;\">
					<input type=\"hidden\" 
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;\" 
						size=\"67\" name=\"vpc2rem\" value=\"$vpc2rem\">
					$vpc2rem
				</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"8\" style=\"border-left:1px solid black;border-bottom:1px dotted black;\">&nbsp;(VPC3)  cooperation</td>\n
				<td style=\"border-right:1px solid black;border-bottom:1px dotted black;\">
					<input type=\"hidden\" name=\"vpc3\" size=\"1\"  value=\"$vpc3\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\" 
						onkeypress=\"return numbersonly(this);\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"codefunc('vpc3',4);\">
					$vpc3
				</td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px dotted black;\"><span id=\"vpc34\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px dotted black;\"><span id=\"vpc33\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px dotted black;\"><span id=\"vpc32\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px dotted black;border-right:1px solid black;\"><span id=\"vpc31\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"19\" style=\"text-align:center;border-bottom:1px dotted black;border-right:1px solid black;\">
					<input type=\"hidden\" 
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;\" 
						size=\"67\" name=\"vpc3rem\" value=\"$vpc3rem\">
					$vpc3rem
				</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"8\" style=\"border-left:1px solid black;border-bottom:1px dotted black;\">&nbsp;(VPC4)  patience</td>\n
				<td style=\"border-right:1px solid black;border-bottom:1px dotted black;\">
					<input type=\"hidden\" name=\"vpc4\" size=\"1\"  value=\"$vpc4\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\" 
						onkeypress=\"return numbersonly(this);\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"codefunc('vpc4',4);\">
					$vpc4
				</td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px dotted black;\"><span id=\"vpc44\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px dotted black;\"><span id=\"vpc43\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px dotted black;\"><span id=\"vpc42\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px dotted black;border-right:1px solid black;\"><span id=\"vpc41\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"19\" style=\"text-align:center;border-bottom:1px dotted black;border-right:1px solid black;\">
					<input type=\"hidden\" 
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;\" 
						size=\"67\" name=\"vpc4rem\" value=\"$vpc4rem\">
					$vpc4rem
				</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"8\" style=\"border-left:1px solid black;border-bottom:1px dotted black;\">&nbsp;(VPC5)  honesty</td>\n
				<td style=\"border-right:1px solid black;border-bottom:1px dotted black;\">
					<input type=\"hidden\" name=\"vpc5\" size=\"1\" value=\"$vpc5\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\" 
						onkeypress=\"return numbersonly(this);\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"codefunc('vpc5',4);\">
					$vpc5
				</td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px dotted black;\"><span id=\"vpc54\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px dotted black;\"><span id=\"vpc53\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px dotted black;\"><span id=\"vpc52\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px dotted black;border-right:1px solid black;\"><span id=\"vpc51\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"19\" style=\"text-align:center;border-bottom:1px dotted black;border-right:1px solid black;\">
					<input type=\"hidden\" 
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;\" 
						size=\"67\" name=\"vpc5rem\" value=\"$vpc5rem\">
					$vpc5rem
				</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"8\" style=\"border-left:1px solid black;border-bottom:1px solid black;\">&nbsp;(VPC6)  loyalty</td>\n
				<td style=\"border-right:1px solid black;border-bottom:1px solid black;\">
					<input type=\"hidden\" name=\"vpc6\" size=\"1\" value=\"$vpc6\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\" 
						onkeypress=\"return numbersonly(this);\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"codefunc('vpc6',4);\">
					$vpc6
				</td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px solid black;\"><span id=\"vpc64\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px solid black;\"><span id=\"vpc63\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px solid black;\"><span id=\"vpc62\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px solid black;border-right:1px solid black;\"><span id=\"vpc61\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"19\" style=\"text-align:center;border-bottom:1px solid black;border-right:1px solid black;\">
					<input type=\"hidden\" 
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;\" 
						size=\"67\" name=\"vpc6rem\" value=\"$vpc6rem\">
					$vpc6rem
				</td>\n
			</tr>
			<tr height=\"8px\">\n
				<td>&nbsp;</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"37\">(5) REVIEW or NEW SHORE BASE TRAINING REQUIRED FOR THE CREW</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"17\" style=\"border-top:1px solid black;border-left:1px solid black;border-right:1px dotted black;\">&nbsp;&#x2193;ENTER CODE (\"T10\"-\"T7X) selected from Code List attached</td>\n
				<td style=\"border-top:1px solid black;\">&nbsp;</td>\n
				<td colspan=\"18\" style=\"border-top:1px solid black;border-right:1px solid black;\">Others (describe details):</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"2\" style=\"border-left:1px solid black;\">&nbsp;
					<input type=\"hidden\" name=\"newtraincode1\" size=\"1\" value=\"$newtraincode1\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"if(this.value!=''){selecttrainingcode(this.value,1)}else{newtrain1.value=''}\"
						style=\"font-size:8pt;border:0;$bgcolor;font-weight:bold;text-align:center;\">
					$newtraincode1
				</td>\n
				<td colspan=\"15\" style=\"border-right:1px dotted black;\">
					<select $disableall name=\"newtrain1\" onclick=\"newtraincode1.value=this.value;\" disabled=\"disabled\"
						style=\"font-size:8pt;border:0;width:280px;border-style:outset;\">
						<option value=\"\"></option>
						$getselecttraining1
					</select>
				</td>\n
				<td>&nbsp;</td>\n
				<td colspan=\"18\" style=\"border-bottom:1px dotted black;border-right:1px solid black;\">
					<input type=\"text\" 
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;\" 
						size=\"64\" name=\"newtrainoth1\" value=\"$newtrainoth1\">
				</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"2\" style=\"border-left:1px solid black;\">&nbsp;
					<input type=\"hidden\" name=\"newtraincode2\" size=\"1\" value=\"$newtraincode2\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"if(this.value!=''){selecttrainingcode(this.value,2)}else{newtrain2.value=''}\"
						style=\"font-size:8pt;border:0;$bgcolor;font-weight:bold;text-align:center;\">
					$newtraincode2
				</td>\n
				<td colspan=\"15\" style=\"border-right:1px dotted black;\">
					<select $disableall name=\"newtrain2\" onclick=\"newtraincode2.value=this.value;\" disabled=\"disabled\"
						style=\"font-size:8pt;border:0;width:280px;border-style:outset;\">
						<option value=\"\"></option>
						$getselecttraining2
					</select>
				</td>\n
				<td>&nbsp;</td>\n
				<td colspan=\"18\" style=\"border-bottom:1px dotted black;border-right:1px solid black;\">
					<input type=\"text\" 
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;\" 
						size=\"64\" name=\"newtrainoth2\" value=\"$newtrainoth2\">
				</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"2\" style=\"border-bottom:1px solid black;border-left:1px solid black;\">&nbsp;
					<input type=\"hidden\" name=\"newtraincode3\" size=\"1\" value=\"$newtraincode3\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"if(this.value!=''){selecttrainingcode(this.value,3)}else{newtrain3.value=''}\"
						style=\"font-size:8pt;border:0;$bgcolor;font-weight:bold;text-align:center;\">
					$newtraincode3
				</td>\n
				<td colspan=\"15\" style=\"border-bottom:1px solid black;border-right:1px dotted black;\">
					<select $disableall name=\"newtrain3\" onclick=\"newtraincode3.value=this.value;\" disabled=\"disabled\"
						style=\"font-size:8pt;border:0;width:280px;border-style:outset;\">
						<option value=\"\"></option>
						$getselecttraining3
					</select>
				</td>\n
				<td style=\"border-bottom:1px solid black;\">&nbsp;</td>\n
				<td colspan=\"18\" style=\"border-bottom:1px solid black;border-right:1px solid black;\">
					<input type=\"hidden\" 
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;\" 
						size=\"64\" name=\"newtrainoth3\" value=\"$newtrainoth3\">
					$newtrainoth3
				</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"13\">&nbsp;</td>\n
				<td colspan=\"25\" style=\"font-size:6pt;\"><NOTE> Management Co. : Showing company's order & Manning Co. : Reporting for the status of training enforcement on Face-B </td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"13\">(6) EVALUATION FOR EMPLOYMENT (VEE1)</td>\n
				<td>
					<input type=\"hidden\" name=\"vee1\" size=\"1\" value=\"$vee1\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\" 
						onkeypress=\"return numbersonly(this);\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"codefunc('vee1',4);\">
					$vee1
				</td>\n
				<td colspan=\"5\">&#x2190;INPUT \"1\"-\"4\"</td>\n
				<td colspan=\"7\">&nbsp;</td>\n
				<td colspan=\"11\">-To be filled by Master / CH.Engr</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"2\" style=\"border-top:1px solid black;border-left:1px solid black;text-align:center;\"><span id=\"vee11\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"34\" style=\"border-top:1px solid black;border-right:1px solid black;\">1.&nbsp;&nbsp;&nbsp;&nbsp;DEFINITELY NOT TO BE RE-HIRED</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"2\" style=\"border-left:1px solid black;text-align:center;\"><span id=\"vee12\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"18\" style=\"border-right:1px solid black;\">2.&nbsp;&nbsp;&nbsp;&nbsp;NEGATIVE OPINION FOR RE-HIRE</td>\n
				<td colspan=\"16\" style=\"border-right:1px solid black;border-top:1px solid black;\">&nbsp;FOR PETTY OFFICER[BSN,No.1OLR,C/CK] ONLY</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"2\" style=\"border-left:1px solid black;text-align:center;\"><span id=\"vee13\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"18\" style=\"border-right:1px solid black;\">3.&nbsp;&nbsp;&nbsp;&nbsp;TO TAKE DUE OBSERVATION ON NEXT SERVICE</td>\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"14\" style=\"border-right:1px solid black;\">&nbsp;&#x2193;INPUT \"Y\" IF YOU PRAISE</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"2\" style=\"border-left:1px solid black;border-bottom:1px solid black;text-align:center;\"><span id=\"vee14\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"18\" style=\"border-right:1px solid black;border-bottom:1px solid black;\">4.&nbsp;&nbsp;&nbsp;&nbsp;RECOMMEND FOR FURTHER EMPLOYMENT</td>\n
				<td colspan=\"2\" style=\"border-bottom:1px solid black;\">&nbsp;</td>\n
				<td style=\"border-bottom:1px solid black;border-left:1px dotted black;border-top:1px dotted black;border-right:1px dotted black;\">
					<input type=\"hidden\" name=\"fleetkept\" size=\"1\" value=\"$fleetkept\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\" 
						onkeydown=\"if(event.keyCode==13){}\">
					$fleetkept
				</td>\n
				<td colspan=\"13\" style=\"border-bottom:1px solid black;border-right:1px solid black;\">&nbsp;&nbsp;TO BE KEPT FOR OUR FLEET</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"36\" style=\"border-left:1px solid black;border-bottom:1px dotted black;border-right:1px solid black;\">&nbsp;
					COMMENT ON ABOVE EVALUATION&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*Your comment MUST be applied , if you evaluate to \"1\" or \"2\" above
				</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"36\" rowspan=\"2\">
					<!-- <textarea $disableall name=\"vee1rem\" style=\"border:0;$bgcolor;font-weight:bold;\" cols=\"83\" rows=\"3\">$vee1rem</textarea>  -->
					$vee1rem
				</td>\n
			</tr>
			<tr height=\"8px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
			</tr>
			<tr height=\"8px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"17\">(7) EVALUATION / ABILITY FOR PROMOTION (VEP0)</td>\n
				<td colspan=\"12\" style=\"text-align:right;border:1px solid black;\">INPUT \"1\"-\"4\"&#x2193;</td>\n
				<td colspan=\"2\" style=\"text-align:center;border-top:1px solid black;border-bottom:1px solid black;\">Exc.</td>\n
				<td colspan=\"2\" style=\"text-align:center;border-top:1px solid black;border-bottom:1px solid black;\">Good</td>\n
				<td colspan=\"2\" style=\"text-align:center;border-top:1px solid black;border-bottom:1px solid black;\">Poor</td>\n
				<td colspan=\"2\" style=\"text-align:center;border-top:1px solid black;border-bottom:1px solid black;border-right:1px solid black;\">Bad</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"9\" style=\"border-left:1px solid black;border-top:1px solid black;\">&nbsp;EVALUATION FOR PROMOTION</td>\n
				<td style=\"border-left:1px solid black;border-top:1px solid black;border-bottom:1px solid black;\">
					<input type=\"hidden\" name=\"vep0\" size=\"1\" value=\"$vep0\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\" 
						onkeypress=\"return numbersonly(this);\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"codefunc('vep0',3);\">
					$vep0
				</td>\n
				<td colspan=\"5\" style=\"border-right:1px solid black;border-top:1px solid black;border-bottom:1px solid black;\">&#x2190;INPUT \"1\"-\"3\"</td>\n
				<td>&nbsp;</td>\n
				<td colspan=\"11\" style=\"border-left:1px solid black;border-bottom:1px dotted black;\">&nbsp;(VEP1)  KNOWLEDGE FOR NEXT RANK</td>\n
				<td style=\"border-right:1px solid black;border-bottom:1px dotted black;\">
					<input type=\"hidden\" name=\"vep1\" size=\"1\" value=\"$vep1\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\" 
						onkeypress=\"return numbersonly(this);\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"codefunc('vep1',4);\">
					$vep1
				</td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;text-align:center;\"><span id=\"vep14\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;text-align:center;\"><span id=\"vep13\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;text-align:center;\"><span id=\"vep12\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;border-right:1px solid black;text-align:center;\"><span id=\"vep11\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"9\" style=\"border-left:1px solid black;\">&nbsp;NEXT RANK:</td>\n
				<td style=\"border-left:1px solid black;text-align:center;\"><span id=\"vep01\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"5\" style=\"border-right:1px solid black;\">1.&nbsp;&nbsp;&nbsp;&nbsp;NEGATIVE</td>\n
				<td>&nbsp;</td>\n
				<td colspan=\"11\" style=\"border-left:1px solid black;border-bottom:1px dotted black;\">&nbsp;(VEP2)  SKILL FOR NEXT RANK</td>\n
				<td style=\"border-right:1px solid black;border-bottom:1px dotted black;\">
					<input type=\"hidden\" name=\"vep2\" size=\"1\" value=\"$vep2\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\" 
						onkeypress=\"return numbersonly(this);\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"codefunc('vep2',4);\">
					$vep2
				</td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;text-align:center;\"><span id=\"vep24\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;text-align:center;\"><span id=\"vep23\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;text-align:center;\"><span id=\"vep22\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;border-right:1px solid black;text-align:center;\"><span id=\"vep21\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"2\" rowspan=\"2\" style=\"text-align:right;text-font:12pt;border-left:1px solid black;border-bottom:1px solid black;\">[</td>\n
				<td colspan=\"6\" rowspan=\"2\" style=\"border-bottom:1px solid black;text-align:center;\">
					<select $disableall name=\"nextrank\" style=\"font-size:14pt;\" onchange=\"document.getElementById('nxtrank').innerHTML=this.value;\">
						<option value=\"\"></option>";
						while($rowgethigherrank=mysql_fetch_array($qrygethigherrank))
						{
							$nextrankcode=$rowgethigherrank["RANKCODE"];
							$nextrankalias=$rowgethigherrank["ALIAS2"];
							if($nextrankcode==$nextrank)
								$selected="selected";
							else
								$selected="";
							echo "<option $selected value=\"$nextrankcode\">$nextrankalias</option>";
						}
				echo "
					</select>
				</td>\n
				<td rowspan=\"2\" style=\"text-font:12pt;border-bottom:1px solid black;\">]</td>\n
				<td style=\"border-left:1px solid black;text-align:center;\"><span id=\"vep02\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"5\" style=\"border-right:1px solid black;\">2.&nbsp;&nbsp;&nbsp;&nbsp;PREMATURE</td>\n
				<td>&nbsp;</td>\n
				<td colspan=\"11\" style=\"border-left:1px solid black;border-bottom:1px dotted black;\">&nbsp;(VEP3)  ASPIRATION</td>\n
				<td style=\"border-right:1px solid black;border-bottom:1px dotted black;\">
					<input type=\"hidden\" name=\"vep3\" size=\"1\" value=\"$vep3\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\" 
						onkeypress=\"return numbersonly(this);\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"codefunc('vep3',4);\">
					$vep3
				</td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;text-align:center;\"><span id=\"vep34\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;text-align:center;\"><span id=\"vep33\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;text-align:center;\"><span id=\"vep32\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;border-right:1px solid black;text-align:center;\"><span id=\"vep31\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
			</tr>
			
			<tr height=\"16px\">\n
				<td colspan=\"11\">&nbsp;</td>\n
				<td style=\"border-left:1px solid black;border-bottom:1px solid black;text-align:center;\"><span id=\"vep03\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"5\" style=\"border-right:1px solid black;border-bottom:1px solid black;\">3.&nbsp;&nbsp;&nbsp;&nbsp;RECOMMEND</td>\n
				<td style=\"border-bottom:1px solid black;\">&nbsp;</td>\n
				<td colspan=\"11\" style=\"border-left:1px solid black;border-bottom:1px solid black;\">&nbsp;(VEP4)  LEADERSHIP</td>\n
				<td style=\"border-right:1px solid black;border-bottom:1px solid black;\">
					<input type=\"hidden\" name=\"vep4\" size=\"1\" value=\"$vep4\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\" 
						onkeypress=\"return numbersonly(this);\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"codefunc('vep4',4);\">
					$vep4
				</td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;text-align:center;\"><span id=\"vep44\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;text-align:center;\"><span id=\"vep43\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;text-align:center;\"><span id=\"vep42\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;border-right:1px solid black;text-align:center;\"><span id=\"vep41\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"24\" style=\"border-left:1px solid black;border-bottom:1px dotted black;\">&nbsp;
					COMMENT ON ABOVE EVALUATION  to PROMOTION
				</td>\n
				<td colspan=\"12\" style=\"border-bottom:1px dotted black;border-right:1px solid black;\">&nbsp;
					-To be filled by Master / CH.Engr
				</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"36\" rowspan=\"2\">
					<!-- <textarea $disableall name=\"vep0rem\" style=\"border:0;$bgcolor;font-weight:bold;\" cols=\"83\" rows=\"3\">$vep0rem</textarea>   -->
					$vep0rem
				</td>\n
			</tr>
			<tr height=\"8px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"3\">&nbsp;</td>\n
				<td colspan=\"35\">If any negative report, has it been discussed with the individual ---- <span id=\"negyes\" style=\"text-decoration:none;cursor:pointer;\" onclick=\"chkchosen('yes',0);\">YES</span>&nbsp;&nbsp;/&nbsp;&nbsp;<span id=\"negno\" style=\"text-decoration:none;cursor:pointer;\" onclick=\"chkchosen('no',0);\">NO</span></td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"10\" style=\"border-left:1px solid black;border-top:1px solid black;\">&nbsp;</td>\n
				<td colspan=\"2\" rowspan=\"3\" style=\"text-align:center;border:1px solid black;\">1st</td>\n
				<td colspan=\"12\" style=\"border-top:1px solid black;\">&nbsp;
					<input type=\"hidden\" name=\"captname1\" size=\"40\" value=\"$captname1\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;\" 
						onkeydown=\"if(event.keyCode==13){}\">
					$captname1
				</td>\n
				<td colspan=\"2\" rowspan=\"3\" style=\"text-align:center;border:1px solid black;\">2nd</td>\n
				<td colspan=\"11\" style=\"border-right:1px solid black;border-top:1px solid black;\">&nbsp;
					<input type=\"hidden\" name=\"captname2\" size=\"40\" value=\"$captname2\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;\" 
						onkeydown=\"if(event.keyCode==13){}\">
					$captname2
				</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"10\" style=\"border-left:1px solid black;\">&nbsp;&nbsp;JUDGEMENT BY:</td>\n
				<td colspan=\"12\" style=\"font-size:6pt;border-bottom:1px solid black;\">&nbsp;(RANK)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(NAME IN CAPITAL LETTERS)</td>\n
				<td colspan=\"11\" style=\"font-size:6pt;border-bottom:1px solid black;border-right:1px solid black;\">&nbsp;(RANK)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(NAME IN CAPITAL LETTERS)</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"10\" style=\"text-align:center;border-left:1px solid black;border-bottom:1px solid black;\">CAPT. OR C/ENGINEER</td>\n
				<td colspan=\"5\" style=\"font-size:6pt;text-align:center;border-bottom:1px solid black;border-right:1px solid black;\">DATE</td>\n
				<td colspan=\"7\" style=\"text-align:center;border-bottom:1px solid black;\">&nbsp;
					<input type=\"hidden\" name=\"date1\" size=\"20\" value=\"$date1\"
						onkeydown=\"if(event.keyCode==13){if(this.value!=''){event.keyCode=9;}}\"
						onblur=\"chkdate(this);\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\">
					$date1
				</td>\n
				<td colspan=\"4\" style=\"font-size:6pt;text-align:center;border-bottom:1px solid black;border-right:1px solid black;\">DATE</td>\n
				<td colspan=\"7\" style=\"text-align:center;border-bottom:1px solid black;border-right:1px solid black;\">&nbsp;
					<input type=\"hidden\" name=\"date2\" size=\"20\" value=\"$date2\"
						onkeydown=\"if(event.keyCode==13){if(this.value!=''){event.keyCode=9;}}\"
						onblur=\"chkdate(this);\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\">
					$date2
				</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
			</tr>
		</table>
		$btnnext&nbsp;&nbsp;&nbsp;&nbsp;$btnviewpdf
	</div>
	<div id=\"faceb\" style=\"display:none;\">
		$btnprev&nbsp;&nbsp;
		<table cellspacing=\"0\" cellpadding=\"0\" width=\"$tablewidth\" 
			style=\"table-layout:fixed;font-family:Arial;\">
			$fixedcolumn
			<!--HEADER-->
			<tr height=\"25px\" style=\"text-align:center;valign:middle;\">\n
				<td colspan=\"39\" style=\"text-align:center;font-size:12pt;font-weight:bold;\">
					( C R E W   E V A L U A T I O N&nbsp;&nbsp;&nbsp;R E P O R T)
				</td>\n
			</tr>
			<tr height=\"17px\" style=\"text-align:center;valign:middle;\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"18\" style=\"text-align:left;font-size:8pt;\">(FACE-B)</td>\n
				<td colspan=\"19\" style=\"text-align:right;font-size:8pt;\">2006.4.1</td>\n
			</tr>
		</table>
		<table cellspacing=\"0\" cellpadding=\"0\" width=\"$tablewidth\" 
			style=\"table-layout:fixed;border:1px solid black;font-family:Arial;font-size:8pt;\">
			$fixedcolumn
			<tr height=\"32px\">\n
				<td colspan=\"39\" rowspan=\"2\" style=\"font-size:12pt;text-align:center;\">EVALUATION FOR GENERAL</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
			</tr>
			<tr height=\"20px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"4\">SHIP NAME</td>\n
				<td>:</td>\n
				<td colspan=\"15\" style=\"border-bottom:1px solid black;font-size:11pt;font-weight:bold;text-align:center;\">$vesselname</td>\n
				<td colspan=\"3\">&nbsp;</td>\n
				<td colspan=\"2\">DATE</td>\n
				<td>:</td>\n
				<td colspan=\"10\" style=\"border-bottom:1px solid black;font-size:11pt;font-weight:bold;text-align:center;\">
					<input type=\"text\" name=\"evaldate\" value=\"$evaldateshow\" 
						onkeydown=\"if(event.keyCode==13){if(this.value!=''){event.keyCode=9;}}\"
						onblur=\"chkdate(this);\"
						style=\"border:0;font-family:Arial;font-size:11pt;font-weight:bold;\">
				</td>\n
			</tr>
			<tr height=\"13px\">\n
				<td>&nbsp;</td>\n
			</tr>
			<tr height=\"21px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"5\">PERSONAL DATA</td>\n
				<td colspan=\"2\" style=\"text-align:right;\">RANK</td>\n
				<td>:</td>\n
				<td colspan=\"6\" style=\"border-bottom:1px solid black;font-size:11pt;font-weight:bold;text-align:center;\">$rank</td>\n
				<td colspan=\"3\" style=\"text-align:right;\">NAME</td>\n
				<td>:</td>\n
				<td colspan=\"18\" style=\"border-bottom:1px solid black;font-size:11pt;font-weight:bold;text-align:center;\">$fullname</td>\n
			</tr>
			<tr height=\"28px\">\n
				<td>&nbsp;</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"36\">ENCIRCLE THE APPROPRIATE CORRESPONDING EVALUATION FOR EACH ITEM AND SUM UP THE POINTS.</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"12\" rowspan=\"5\" style=\"font-size:12pt;text-align:center;border:1px solid black;\">ABILITY FOR PRESENT RANK</td>\n
				<td colspan=\"12\" style=\"text-align:right;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;\">INPUT\"1\"-\"4\"&#x2193;</td>\n
				<td colspan=\"3\" style=\"text-align:center;border-top:1px solid black;border-bottom:1px solid black;\">Exc.</td>\n
				<td colspan=\"3\" style=\"text-align:center;border-top:1px solid black;border-bottom:1px solid black;\">Good</td>\n
				<td colspan=\"3\" style=\"text-align:center;border-top:1px solid black;border-bottom:1px solid black;\">Poor</td>\n
				<td colspan=\"3\" style=\"text-align:center;border-top:1px solid black;border-bottom:1px solid black;border-right:1px solid black;\">Bad</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"11\" style=\"border-bottom:1px dotted black;\">&nbsp;(VAR1)  KNOWLEDGE</td>\n
				<td style=\"border-bottom:1px dotted black;border-right:1px solid black;\">
					<input type=\"hidden\" name=\"var1\" size=\"1\" value=\"$var1\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\" 
						onkeypress=\"return numbersonly(this);\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"codefunc('var1',4,4);\">
					$var1
				</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"var14\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;\">&nbsp;8</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"var13\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;\">&nbsp;6</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"var12\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;\">&nbsp;2</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"var11\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;border-right:1px solid black;\">&nbsp;0</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"11\" style=\"border-bottom:1px dotted black;\">&nbsp;(VAR2)  SKILL</td>\n
				<td style=\"border-bottom:1px dotted black;border-right:1px solid black;\">
					<input type=\"hidden\" name=\"var2\" size=\"1\" value=\"$var2\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\" 
						onkeypress=\"return numbersonly(this);\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"codefunc('var2',4,4);\">
					$var2
				</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"var24\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;\">&nbsp;12</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"var23\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;\">&nbsp;9</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"var22\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;\">&nbsp;3</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"var21\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;border-right:1px solid black;\">&nbsp;0</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"11\" style=\"border-bottom:1px dotted black;\">&nbsp;(VAR3)  DILIGENCE</td>\n
				<td style=\"border-bottom:1px dotted black;border-right:1px solid black;\">
					<input type=\"hidden\" name=\"var3\" size=\"1\" value=\"$var3\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\" 
						onkeypress=\"return numbersonly(this);\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"codefunc('var3',4,4);\">
					$var3
				</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"var34\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;\">&nbsp;4</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"var33\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;\">&nbsp;3</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"var32\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;\">&nbsp;1</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"var31\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;border-right:1px solid black;\">&nbsp;0</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"11\" style=\"border-bottom:1px solid black;\">&nbsp;(VAR4)  STRENGTH</td>\n
				<td style=\"border-bottom:1px solid black;border-right:1px solid black;\">
					<input type=\"hidden\" name=\"var4\" size=\"1\" value=\"$var4\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\" 
						onkeypress=\"return numbersonly(this);\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"codefunc('var4',4,4);\">
					$var4
				</td>\n
				<td style=\"text-align:center;border-bottom:1px solid black;text-align:center;\"><span id=\"var44\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px solid black;\">&nbsp;4</td>\n
				<td style=\"text-align:center;border-bottom:1px solid black;text-align:center;\"><span id=\"var43\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px solid black;\">&nbsp;3</td>\n
				<td style=\"text-align:center;border-bottom:1px solid black;text-align:center;\"><span id=\"var42\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px solid black;\">&nbsp;1</td>\n
				<td style=\"text-align:center;border-bottom:1px solid black;text-align:center;\"><span id=\"var41\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px solid black;border-right:1px solid black;\">&nbsp;0</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"20\">&nbsp;</td>\n
				<td colspan=\"5\" style=\"text-align:center;border-bottom:1px solid black;border-left:1px solid black;border-right:1px solid black;\">SUB TOTAL</td>\n
				<td colspan=\"5\" style=\"border-bottom:1px solid black;\">&nbsp;</td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px solid black;font-weight:bold;\"><input type=\"text\" size=\"5\" style=\"border:0;font-family:Arial;font-weight:bold;\" id=\"varsub\">0</td>\n
				<td colspan=\"3\" style=\"border-bottom:1px solid black;\">&nbsp;</td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px solid black;border-right:1px solid black;\">(28)</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"12\" rowspan=\"5\" style=\"font-size:12pt;text-align:center;border:1px solid black;\">PERFORMANCE OF JOB</td>\n
				<td colspan=\"12\" style=\"text-align:right;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;\">INPUT\"1\"-\"4\"&#x2193;</td>\n
				<td colspan=\"3\" style=\"text-align:center;border-top:1px solid black;border-bottom:1px solid black;\">Exc.</td>\n
				<td colspan=\"3\" style=\"text-align:center;border-top:1px solid black;border-bottom:1px solid black;\">Good</td>\n
				<td colspan=\"3\" style=\"text-align:center;border-top:1px solid black;border-bottom:1px solid black;\">Poor</td>\n
				<td colspan=\"3\" style=\"text-align:center;border-top:1px solid black;border-bottom:1px solid black;border-right:1px solid black;\">Bad</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"11\" style=\"border-bottom:1px dotted black;\">&nbsp;(VPJ1)  ATTITUDE ON DUTY</td>\n
				<td style=\"border-bottom:1px dotted black;border-right:1px solid black;\">
					<input type=\"hidden\" name=\"vpj1\" size=\"1\" value=\"$vpj1\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\" 
						onkeypress=\"return numbersonly(this);\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"codefunc('vpj1',4,4);\">
					$vpj1
				</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"vpj14\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;\">&nbsp;8</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"vpj13\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;\">&nbsp;6</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"vpj12\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;\">&nbsp;2</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"vpj11\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;border-right:1px solid black;\">&nbsp;0</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"11\" style=\"border-bottom:1px dotted black;\">&nbsp;(VPJ2)  EXECUTION OF ORDER</td>\n
				<td style=\"border-bottom:1px dotted black;border-right:1px solid black;\">
					<input type=\"hidden\" name=\"vpj2\" size=\"1\" value=\"$vpj2\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\" 
						onkeypress=\"return numbersonly(this);\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"codefunc('vpj2',4,4);\">
					$vpj2
				</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"vpj24\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;\">&nbsp;8</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"vpj23\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;\">&nbsp;6</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"vpj22\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;\">&nbsp;2</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"vpj21\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;border-right:1px solid black;\">&nbsp;0</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"11\" style=\"border-bottom:1px dotted black;\">&nbsp;(VPJ3)  DEPENDABILITY</td>\n
				<td style=\"border-bottom:1px dotted black;border-right:1px solid black;\">
					<input type=\"hidden\" name=\"vpj3\" size=\"1\" value=\"$vpj3\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\" 
						onkeypress=\"return numbersonly(this);\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"codefunc('vpj3',4,4);\">
					$vpj3
				</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"vpj34\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;\">&nbsp;8</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"vpj33\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;\">&nbsp;6</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"vpj32\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;\">&nbsp;2</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"vpj31\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;border-right:1px solid black;\">&nbsp;0</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"11\" style=\"border-bottom:1px solid black;\">&nbsp;(VPJ4)  LEADERSHIP or It's Potential</td>\n
				<td style=\"border-bottom:1px solid black;border-right:1px solid black;\">
					<input type=\"hidden\" name=\"vpj4\" size=\"1\" value=\"$vpj4\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\" 
						onkeypress=\"return numbersonly(this);\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"codefunc('vpj4',4,4);\">
					$vpj4
				</td>\n
				<td style=\"text-align:center;border-bottom:1px solid black;text-align:center;\"><span id=\"vpj44\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px solid black;\">&nbsp;4</td>\n
				<td style=\"text-align:center;border-bottom:1px solid black;text-align:center;\"><span id=\"vpj43\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px solid black;\">&nbsp;3</td>\n
				<td style=\"text-align:center;border-bottom:1px solid black;text-align:center;\"><span id=\"vpj42\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px solid black;\">&nbsp;1</td>\n
				<td style=\"text-align:center;border-bottom:1px solid black;text-align:center;\"><span id=\"vpj41\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px solid black;border-right:1px solid black;\">&nbsp;0</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"20\">&nbsp;</td>\n
				<td colspan=\"5\" style=\"text-align:center;border-bottom:1px solid black;border-left:1px solid black;border-right:1px solid black;\">SUB TOTAL</td>\n
				<td colspan=\"5\" style=\"border-bottom:1px solid black;\">&nbsp;</td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px solid black;font-weight:bold;\"><input type=\"text\" size=\"5\" style=\"border:0;font-family:Arial;font-weight:bold;\" id=\"vpjsub\">0</td>\n
				<td colspan=\"3\" style=\"border-bottom:1px solid black;\">&nbsp;</td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px solid black;border-right:1px solid black;\">(28)</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"12\" rowspan=\"4\" style=\"font-size:12pt;text-align:center;border:1px solid black;\">SAFETY MANAGEMENT SYSTEM (S.M.S)</td>\n
				<td colspan=\"12\" style=\"text-align:right;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;\">INPUT\"1\"-\"4\"&#x2193;</td>\n
				<td colspan=\"3\" style=\"text-align:center;border-top:1px solid black;border-bottom:1px solid black;\">Exc.</td>\n
				<td colspan=\"3\" style=\"text-align:center;border-top:1px solid black;border-bottom:1px solid black;\">Good</td>\n
				<td colspan=\"3\" style=\"text-align:center;border-top:1px solid black;border-bottom:1px solid black;\">Poor</td>\n
				<td colspan=\"3\" style=\"text-align:center;border-top:1px solid black;border-bottom:1px solid black;border-right:1px solid black;\">Bad</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"11\" style=\"border-bottom:1px dotted black;\">&nbsp;(VSM1)  KNOWLEDGE FOR S.M.S</td>\n
				<td style=\"border-bottom:1px dotted black;border-right:1px solid black;\">
					<input type=\"hidden\" name=\"vsm1\" size=\"1\" value=\"$vsm1\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\" 
						onkeypress=\"return numbersonly(this);\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"codefunc('vsm1',4,3);\">
					$vsm1
				</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"vsm14\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;\">&nbsp;4</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"vsm13\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;\">&nbsp;3</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"vsm12\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;\">&nbsp;1</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"vsm11\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;border-right:1px solid black;\">&nbsp;0</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"11\" style=\"border-bottom:1px dotted black;\">&nbsp;(VSM2)  EMERGENCY ABILITY</td>\n
				<td style=\"border-bottom:1px dotted black;border-right:1px solid black;\">
					<input type=\"hidden\" name=\"vsm2\" size=\"1\" value=\"$vsm2\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\" 
						onkeypress=\"return numbersonly(this);\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"codefunc('vsm2',4,3);\">
					$vsm2
				</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"vsm24\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;\">&nbsp;4</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"vsm23\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;\">&nbsp;3</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"vsm22\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;\">&nbsp;1</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"vsm21\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;border-right:1px solid black;\">&nbsp;0</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"11\" style=\"border-bottom:1px solid black;\">&nbsp;(VSM3)  ENGLISH</td>\n
				<td style=\"border-bottom:1px solid black;border-right:1px solid black;\">
					<input type=\"hidden\" name=\"vsm3\" size=\"1\" value=\"$vsm3\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\" 
						onkeypress=\"return numbersonly(this);\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"codefunc('vsm3',4,3);\">
					$vsm3
				</td>\n
				<td style=\"text-align:center;border-bottom:1px solid black;text-align:center;\"><span id=\"vsm34\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px solid black;\">&nbsp;4</td>\n
				<td style=\"text-align:center;border-bottom:1px solid black;text-align:center;\"><span id=\"vsm33\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px solid black;\">&nbsp;3</td>\n
				<td style=\"text-align:center;border-bottom:1px solid black;text-align:center;\"><span id=\"vsm32\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px solid black;\">&nbsp;1</td>\n
				<td style=\"text-align:center;border-bottom:1px solid black;text-align:center;\"><span id=\"vsm31\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px solid black;border-right:1px solid black;\">&nbsp;0</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"20\">&nbsp;</td>\n
				<td colspan=\"5\" style=\"text-align:center;border-bottom:1px solid black;border-left:1px solid black;border-right:1px solid black;\">SUB TOTAL</td>\n
				<td colspan=\"5\" style=\"border-bottom:1px solid black;\">&nbsp;</td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px solid black;font-weight:bold;\"><input type=\"text\" size=\"5\" style=\"border:0;font-family:Arial;font-weight:bold;\" id=\"vsmsub\">0</td>\n
				<td colspan=\"3\" style=\"border-bottom:1px solid black;\">&nbsp;</td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px solid black;border-right:1px solid black;\">(12)</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"12\" rowspan=\"4\" style=\"font-size:12pt;text-align:center;border:1px solid black;\">PROMISING</td>\n
				<td colspan=\"12\" style=\"text-align:right;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;\">INPUT\"1\"-\"4\"&#x2193;</td>\n
				<td colspan=\"3\" style=\"text-align:center;border-top:1px solid black;border-bottom:1px solid black;\">Exc.</td>\n
				<td colspan=\"3\" style=\"text-align:center;border-top:1px solid black;border-bottom:1px solid black;\">Good</td>\n
				<td colspan=\"3\" style=\"text-align:center;border-top:1px solid black;border-bottom:1px solid black;\">Poor</td>\n
				<td colspan=\"3\" style=\"text-align:center;border-top:1px solid black;border-bottom:1px solid black;border-right:1px solid black;\">Bad</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"11\" style=\"border-bottom:1px dotted black;\">&nbsp;(VPR1)  CREATIVITY</td>\n
				<td style=\"border-bottom:1px dotted black;border-right:1px solid black;\">
					<input type=\"hidden\" name=\"vpr1\" size=\"1\" value=\"$vpr1\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\" 
						onkeypress=\"return numbersonly(this);\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"codefunc('vpr1',4,3);\">
					$vpr1
				</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"vpr14\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;\">&nbsp;4</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"vpr13\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;\">&nbsp;3</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"vpr12\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;\">&nbsp;1</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"vpr11\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;border-right:1px solid black;\">&nbsp;0</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"11\" style=\"border-bottom:1px dotted black;\">&nbsp;(VPR2)  ASPIRATION</td>\n
				<td style=\"border-bottom:1px dotted black;border-right:1px solid black;\">
					<input type=\"hidden\" name=\"vpr2\" size=\"1\" value=\"$vpr2\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\" 
						onkeypress=\"return numbersonly(this);\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"codefunc('vpr2',4,3);\">
					$vpr2
				</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"vpr24\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;\">&nbsp;8</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"vpr23\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;\">&nbsp;6</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"vpr22\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;\">&nbsp;2</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"vpr21\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;border-right:1px solid black;\">&nbsp;0</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"11\" style=\"border-bottom:1px solid black;\">&nbsp;(VPR3)  EXPECTATIONAL GROWTH</td>\n
				<td style=\"border-bottom:1px solid black;border-right:1px solid black;\">
					<input type=\"hidden\" name=\"vpr3\" size=\"1\" value=\"$vpr3\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\" 
						onkeypress=\"return numbersonly(this);\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"codefunc('vpr3',4,3);\">
					$vpr3
				</td>\n
				<td style=\"text-align:center;border-bottom:1px solid black;text-align:center;\"><span id=\"vpr34\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px solid black;\">&nbsp;4</td>\n
				<td style=\"text-align:center;border-bottom:1px solid black;text-align:center;\"><span id=\"vpr33\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px solid black;\">&nbsp;3</td>\n
				<td style=\"text-align:center;border-bottom:1px solid black;text-align:center;\"><span id=\"vpr32\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px solid black;\">&nbsp;1</td>\n
				<td style=\"text-align:center;border-bottom:1px solid black;text-align:center;\"><span id=\"vpr31\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px solid black;border-right:1px solid black;\">&nbsp;0</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"20\">&nbsp;</td>\n
				<td colspan=\"5\" style=\"text-align:center;border-bottom:1px solid black;border-left:1px solid black;border-right:1px solid black;\">SUB TOTAL</td>\n
				<td colspan=\"5\" style=\"border-bottom:1px solid black;\">&nbsp;</td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px solid black;font-weight:bold;\"><input type=\"text\" size=\"5\" style=\"border:0;font-family:Arial;font-weight:bold;\" id=\"vprsub\">0</td>\n
				<td colspan=\"3\" style=\"border-bottom:1px solid black;\">&nbsp;</td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px solid black;border-right:1px solid black;\">(16)</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"12\" rowspan=\"4\" style=\"font-size:12pt;text-align:center;border:1px solid black;\">BEHAVIOR</td>\n
				<td colspan=\"12\" style=\"text-align:right;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;\">INPUT\"1\"-\"4\"&#x2193;</td>\n
				<td colspan=\"3\" style=\"text-align:center;border-top:1px solid black;border-bottom:1px solid black;\">Exc.</td>\n
				<td colspan=\"3\" style=\"text-align:center;border-top:1px solid black;border-bottom:1px solid black;\">Good</td>\n
				<td colspan=\"3\" style=\"text-align:center;border-top:1px solid black;border-bottom:1px solid black;\">Poor</td>\n
				<td colspan=\"3\" style=\"text-align:center;border-top:1px solid black;border-bottom:1px solid black;border-right:1px solid black;\">Bad</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"11\" style=\"border-bottom:1px dotted black;\">&nbsp;(VBE1)  RULE OBEDIENCE</td>\n
				<td style=\"border-bottom:1px dotted black;border-right:1px solid black;\">
					<input type=\"hidden\" name=\"vbe1\" size=\"1\" value=\"$vbe1\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\" 
						onkeypress=\"return numbersonly(this);\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"codefunc('vbe1',4,3);\">
					$vbe1
				</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"vbe14\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;\">&nbsp;8</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"vbe13\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;\">&nbsp;6</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"vbe12\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;\">&nbsp;2</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"vbe11\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;border-right:1px solid black;\">&nbsp;0</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"11\" style=\"border-bottom:1px dotted black;\">&nbsp;(VBE2)  TIMEKEEPING</td>\n
				<td style=\"border-bottom:1px dotted black;border-right:1px solid black;\">
					<input type=\"hidden\" name=\"vbe2\" size=\"1\" value=\"$vbe2\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\" 
						onkeypress=\"return numbersonly(this);\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"codefunc('vbe2',4,3);\">
					$vbe2
				</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"vbe24\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;\">&nbsp;4</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"vbe23\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;\">&nbsp;3</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"vbe22\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;\">&nbsp;1</td>\n
				<td style=\"text-align:center;border-bottom:1px dotted black;text-align:center;\"><span id=\"vbe21\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px dotted black;border-right:1px solid black;\">&nbsp;0</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"11\" style=\"border-bottom:1px solid black;\">&nbsp;(VBE3)  HEALTH CONTROL</td>\n
				<td style=\"border-bottom:1px solid black;border-right:1px solid black;\">
					<input type=\"hidden\" name=\"vbe3\" size=\"1\" value=\"$vbe3\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\" 
						onkeypress=\"return numbersonly(this);\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"codefunc('vbe3',4,3);\">
					$vbe3
				</td>\n
				<td style=\"text-align:center;border-bottom:1px solid black;text-align:center;\"><span id=\"vbe34\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px solid black;\">&nbsp;4</td>\n
				<td style=\"text-align:center;border-bottom:1px solid black;text-align:center;\"><span id=\"vbe33\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px solid black;\">&nbsp;3</td>\n
				<td style=\"text-align:center;border-bottom:1px solid black;text-align:center;\"><span id=\"vbe32\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px solid black;\">&nbsp;1</td>\n
				<td style=\"text-align:center;border-bottom:1px solid black;text-align:center;\"><span id=\"vbe31\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"border-bottom:1px solid black;border-right:1px solid black;\">&nbsp;0</td>\n
			</tr>
			<tr height=\"8px\">\n
				<td colspan=\"19\" style=\"border-bottom:1px dashed black;\">&nbsp;</td>\n
				<td>&nbsp;</td>\n
				<td colspan=\"5\" rowspan=\"2\" style=\"text-align:center;border-bottom:1px solid black;border-left:1px solid black;border-right:1px solid black;\">SUB TOTAL</td>\n
				<td colspan=\"5\" rowspan=\"2\" style=\"border-bottom:1px solid black;\">&nbsp;</td>\n
				<td colspan=\"2\" rowspan=\"2\" style=\"text-align:center;border-bottom:1px solid black;font-weight:bold;\"><input type=\"text\" size=\"5\" style=\"border:0;font-family:Arial;font-weight:bold;\" id=\"vbesub\">0</td>\n
				<td colspan=\"3\" rowspan=\"2\" style=\"border-bottom:1px solid black;\">&nbsp;</td>\n
				<td colspan=\"2\" rowspan=\"2\" style=\"text-align:center;border-bottom:1px solid black;border-right:1px solid black;\">(16)</td>\n
			</tr>
			<tr height=\"8px\">\n
				<td colspan=\"19\" style=\"border-right:1px dashed black;\">&nbsp;</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"17\" style=\"font-size:10pt;border-right:1px dashed black;\">SKILLFULNESS OF COMPUTER</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"9\" style=\"text-align:right;border:1px solid black;\">INPUT\"1\"-\"4\"&#x2193;</td>\n
				<td colspan=\"2\" style=\"text-align:center;border-top:1px solid black;border-bottom:1px solid black;\">Exc.</td>\n
				<td colspan=\"2\" style=\"text-align:center;border-top:1px solid black;border-bottom:1px solid black;\">Good</td>\n
				<td colspan=\"2\" style=\"text-align:center;border-top:1px solid black;border-bottom:1px solid black;\">Poor</td>\n
				<td colspan=\"2\" style=\"text-align:center;border-top:1px solid black;border-bottom:1px solid black;border-right:1px solid black;\">Bad</td>\n
				<td style=\"border-right:1px dashed black;\">&nbsp;</td>\n
				<td colspan=\"5\">&nbsp;</td>\n
				<td colspan=\"6\" rowspan=\"2\" style=\"font-size:10pt;font-weight:bold;text-align:center;border-left:2px solid black;border-top:2px solid black;border-bottom:2px solid black;\">TOTAL SCORE</td>\n
				<td colspan=\"4\" rowspan=\"2\" style=\"font-size:10pt;font-weight:bold;text-align:center;border-top:2px solid black;border-bottom:2px solid black;\"><input type=\"text\" size=\"5\" style=\"border:0;font-family:Arial;font-weight:bold;font-size:10pt;\" id=\"totalscore\"></td>\n
				<td colspan=\"3\" rowspan=\"2\" style=\"font-size:10pt;text-align:center;border-right:2px solid black;border-top:2px solid black;border-bottom:2px solid black;\">(100)</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"8\" style=\"border-left:1px solid black;border-bottom:1px dashed black;\">(VSC1)  Use  PC as a Tool</td>\n
				<td style=\"border-right:1px solid black;border-bottom:1px dashed black;\">
					<input type=\"hidden\" name=\"vsc1\" size=\"1\" value=\"$vsc1\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\" 
						onkeypress=\"return numbersonly(this);\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"codefunc('vsc1',4);\">
					$vsc1
				</td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px dashed black;\"><span id=\"vsc14\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px dashed black;\"><span id=\"vsc13\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px dashed black;\"><span id=\"vsc12\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px dashed black;border-right:1px solid black;\"><span id=\"vsc11\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td style=\"border-right:1px dashed black;\">&nbsp;</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"8\" style=\"border-left:1px solid black;border-bottom:1px dashed black;\">(VSC2)  PC documentation</td>\n
				<td style=\"border-right:1px solid black;border-bottom:1px dashed black;\">
					<input type=\"hidden\" name=\"vsc2\" size=\"1\" value=\"$vsc2\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\" 
						onkeypress=\"return numbersonly(this);\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"codefunc('vsc2',4);\">
					$vsc2
				</td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px dashed black;\"><span id=\"vsc24\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px dashed black;\"><span id=\"vsc23\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px dashed black;\"><span id=\"vsc22\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px dashed black;border-right:1px solid black;\"><span id=\"vsc21\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td style=\"border-right:1px dashed black;\">&nbsp;</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
				<td colspan=\"8\" style=\"border-left:1px solid black;border-bottom:1px solid black;\">(VSC3)  BASS usage</td>\n
				<td style=\"border-right:1px solid black;border-bottom:1px solid black;\">
					<input type=\"hidden\" name=\"vsc3\" size=\"1\" value=\"$vsc3\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\" 
						onkeypress=\"return numbersonly(this);\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"codefunc('vsc3',4);\">
					$vsc3
				</td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px solid black;\"><span id=\"vsc34\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px solid black;\"><span id=\"vsc33\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px solid black;\"><span id=\"vsc32\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td colspan=\"2\" style=\"text-align:center;border-bottom:1px solid black;border-right:1px solid black;\"><span id=\"vsc31\" style=\"font-weight:bold;\">&nbsp;</span></td>\n
				<td style=\"border-right:1px dashed black;\">&nbsp;</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"18\">&nbsp;</td>\n
				<td style=\"border-right:1px dashed black;\">&nbsp;</td>\n
			</tr>
			<tr height=\"8px\">\n
				<td colspan=\"39\" style=\"border-bottom:4px dashed black;\">&nbsp;</td>\n
			</tr>
			<tr height=\"8px\">\n
				<td>&nbsp;</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"10\" style=\"font-size:10pt;\">&lt;COMPANY USE&gt;</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"20\" style=\"font-size:9pt;\">Total Evaluation for Further Employment</td>\n
				<td colspan=\"15\" style=\"font-size:9pt;\"><u>Additional Comment, if any </u></td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"10\" style=\"text-align:right;border:1px solid black;\">Judged by:&#x2192;</td>\n
				<td colspan=\"3\" style=\"text-align:center;border:1px solid black;border-left:0;\">
					<input type=\"hidden\" name=\"efeby1\" size=\"6\" value=\"$efeby1\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\">
					$efeby1
				</td>\n
				<td colspan=\"3\" style=\"text-align:right;border:1px solid black;border-left:0;\">
					<input type=\"hidden\" name=\"efeby2\" size=\"6\" value=\"$efeby2\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\">
					$efeby2
				</td>\n
				<td colspan=\"3\" style=\"text-align:right;border:1px solid black;border-left:0;\">
					<input type=\"hidden\" name=\"efeby3\" size=\"6\" value=\"$efeby3\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\">
					$efeby3
				</td>\n
				<td>&nbsp;</td>\n
				<td colspan=\"15\">&lt; Management Company &gt;</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td style=\"border-left:1px solid black;border-bottom:1px dashed black;\">&nbsp;1.</td>\n
				<td colspan=\"9\" style=\"border-right:1px solid black;border-bottom:1px dashed black;\">&nbsp;To terminate to employ</td>\n
				<td colspan=\"3\" style=\"border-right:1px solid black;border-bottom:1px dashed black;text-align:center;\">
					<input type=\"radio\" $disableall name=\"efe1\" $efe11 value=\"1\">
				</td>\n
				<td colspan=\"3\" style=\"border-right:1px solid black;border-bottom:1px dashed black;text-align:center;\">
					<input type=\"radio\" $disableall name=\"efe2\" $efe21 value=\"1\">
				</td>\n
				<td colspan=\"3\" style=\"border-right:1px solid black;border-bottom:1px dashed black;text-align:center;\">
					<input type=\"radio\" $disableall name=\"efe3\" $efe31 value=\"1\">
				</td>\n
				<td>&nbsp;</td>\n
				<td colspan=\"15\" rowspan=\"6\">
					<textarea $disableall name=\"principalcomment\" style=\"border:0;$bgcolor;font-weight:bold;\" cols=\"33\" rows=\"6\">$principalcomment</textarea>
				</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td style=\"border-left:1px solid black;border-bottom:1px dashed black;\">&nbsp;2.</td>\n
				<td colspan=\"9\" style=\"font-size:7pt;border-right:1px solid black;border-bottom:1px dashed black;\">&nbsp;To take due observation on next services</td>\n
				<td colspan=\"3\" style=\"border-right:1px solid black;border-bottom:1px dashed black;text-align:center;\">
					<input type=\"radio\" $disableall name=\"efe1\" $efe12 value=\"2\">
				</td>\n
				<td colspan=\"3\" style=\"border-right:1px solid black;border-bottom:1px dashed black;text-align:center;\">
					<input type=\"radio\" $disableall name=\"efe2\" $efe22 value=\"2\">
				</td>\n
				<td colspan=\"3\" style=\"border-right:1px solid black;border-bottom:1px dashed black;text-align:center;\">
					<input type=\"radio\" $disableall name=\"efe3\" $efe32 value=\"2\">
				</td>\n
				<td>&nbsp;</td>\n
				<!--<td colspan=\"15\" style=\"border-bottom:1px dashed black;\">&nbsp;</td>\n-->
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td style=\"border-left:1px solid black;border-bottom:1px dashed black;\">&nbsp;3.</td>\n
				<td colspan=\"9\" style=\"border-right:1px solid black;border-bottom:1px dashed black;\">&nbsp;To secure for further employment</td>\n
				<td colspan=\"3\" style=\"border-right:1px solid black;border-bottom:1px dashed black;text-align:center;\">
					<input type=\"radio\" $disableall name=\"efe1\" $efe13 value=\"3\">
				</td>\n
				<td colspan=\"3\" style=\"border-right:1px solid black;border-bottom:1px dashed black;text-align:center;\">
					<input type=\"radio\" $disableall name=\"efe2\" $efe23 value=\"3\">
				</td>\n
				<td colspan=\"3\" style=\"border-right:1px solid black;border-bottom:1px dashed black;text-align:center;\">
					<input type=\"radio\" $disableall name=\"efe3\" $efe33 value=\"3\">
				</td>\n
				<td>&nbsp;</td>\n
				<!--<td colspan=\"15\" style=\"border-bottom:1px dashed black;\">&nbsp;</td>\n-->
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td style=\"border-left:1px solid black;border-bottom:1px solid black;\">&nbsp;4.</td>\n
				<td colspan=\"9\" style=\"border-right:1px solid black;border-bottom:1px solid black;\">&nbsp;Others</td>\n
				<td colspan=\"3\" style=\"border-right:1px solid black;border-bottom:1px solid black;text-align:center;\">
					<input type=\"radio\" $disableall name=\"efe1\" $efe14 value=\"4\">
				</td>\n
				<td colspan=\"3\" style=\"border-right:1px solid black;border-bottom:1px solid black;text-align:center;\">
					<input type=\"radio\" $disableall name=\"efe2\" $efe24 value=\"4\">
				</td>\n
				<td colspan=\"3\" style=\"border-right:1px solid black;border-bottom:1px solid black;text-align:center;\">
					<input type=\"radio\" $disableall name=\"efe3\" $efe34 value=\"4\">
				</td>\n
				<td>&nbsp;</td>\n
				<!--<td colspan=\"15\" style=\"border-bottom:1px dashed black;\">&nbsp;</td>\n-->
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"22\">&nbsp;</td>\n
				<!--<td colspan=\"15\" style=\"border-bottom:1px dashed black;\">&nbsp;</td>\n-->
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"10\" style=\"font-size:9pt;\">Evaluation for Promotion to rank (</td>\n
				<td colspan=\"3\" style=\"font-size:9pt;text-align:center;\"><span id=\"nxtrank\" style=\"font-weight:bold;text-align:center;\">&nbsp;</span></td>\n
				<td colspan=\"7\" style=\"font-size:9pt;\">)</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"10\" style=\"text-align:right;border:1px solid black;\">Judged by:&#x2192;</td>\n
				<td colspan=\"3\" style=\"text-align:right;border:1px solid black;border-left:0;\">
					<input type=\"hidden\" name=\"eprby1\" size=\"6\" value=\"$eprby1\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\">
					$eprby1
				</td>\n
				<td colspan=\"3\" style=\"text-align:center;border:1px solid black;border-left:0;\">
					<input type=\"hidden\" name=\"eprby2\" size=\"6\" value=\"$eprby2\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\">
					$eprby2
				</td>\n
				<td colspan=\"3\" style=\"text-align:center;border:1px solid black;border-left:0;\">
					<input type=\"hidden\" name=\"eprby3\" size=\"6\" value=\"$eprby3\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;text-align:center;\">
					$eprby3
				</td>\n
				<td>&nbsp;</td>\n
				<td colspan=\"15\">&lt; Manning Company &gt;</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td style=\"border-left:1px solid black;border-bottom:1px dashed black;\">&nbsp;1.</td>\n
				<td colspan=\"9\" style=\"border-right:1px solid black;border-bottom:1px dashed black;\">&nbsp;Negative</td>\n
				<td colspan=\"3\" style=\"border-right:1px solid black;border-bottom:1px dashed black;text-align:center;\">
					<input type=\"radio\" $disableall name=\"epr1\" $epr11 value=\"1\">
				</td>\n
				<td colspan=\"3\" style=\"border-right:1px solid black;border-bottom:1px dashed black;text-align:center;\">
					<input type=\"radio\" $disableall name=\"epr2\" $epr21 value=\"1\">
				</td>\n
				<td colspan=\"3\" style=\"border-right:1px solid black;border-bottom:1px dashed black;text-align:center;\">
					<input type=\"radio\" $disableall name=\"epr3\" $epr31 value=\"1\">
				</td>\n
				<td>&nbsp;</td>\n
				<td colspan=\"15\" rowspan=\"5\" >
					<textarea $disableall name=\"vmccomment\" style=\"border:0;$bgcolor;font-weight:bold;\" cols=\"33\" rows=\"5\">$vmccomment</textarea>
				</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td style=\"border-left:1px solid black;border-bottom:1px dashed black;\">&nbsp;2.</td>\n
				<td colspan=\"9\" style=\"border-right:1px solid black;border-bottom:1px dashed black;\">&nbsp;Premature</td>\n
				<td colspan=\"3\" style=\"border-right:1px solid black;border-bottom:1px dashed black;text-align:center;\">
					<input type=\"radio\" $disableall name=\"epr1\" $epr12 value=\"2\">
				</td>\n
				<td colspan=\"3\" style=\"border-right:1px solid black;border-bottom:1px dashed black;text-align:center;\">
					<input type=\"radio\" $disableall name=\"epr2\" $epr22 value=\"2\">
				</td>\n
				<td colspan=\"3\" style=\"border-right:1px solid black;border-bottom:1px dashed black;text-align:center;\">
					<input type=\"radio\" $disableall name=\"epr3\" $epr32 value=\"2\">
				</td>\n
				<td>&nbsp;</td>\n
				<!--<td colspan=\"15\" style=\"border-bottom:1px dashed black;\">&nbsp;</td>\n-->
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td style=\"border-left:1px solid black;border-bottom:1px dashed black;\">&nbsp;3.</td>\n
				<td colspan=\"9\" style=\"border-right:1px solid black;border-bottom:1px dashed black;\">&nbsp;Recommended with subjects</td>\n
				<td colspan=\"3\" style=\"border-right:1px solid black;border-bottom:1px dashed black;text-align:center;\">
					<input type=\"radio\" $disableall name=\"epr1\" $epr13 value=\"3\">
				</td>\n
				<td colspan=\"3\" style=\"border-right:1px solid black;border-bottom:1px dashed black;text-align:center;\">
					<input type=\"radio\" $disableall name=\"epr2\" $epr23 value=\"3\">
				</td>\n
				<td colspan=\"3\" style=\"border-right:1px solid black;border-bottom:1px dashed black;text-align:center;\">
					<input type=\"radio\" $disableall name=\"epr3\" $epr33 value=\"3\">
				</td>\n
				<td>&nbsp;</td>\n
				<!--<td colspan=\"15\" style=\"border-bottom:1px dashed black;\">&nbsp;</td>\n-->
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td style=\"border-left:1px solid black;border-bottom:1px solid black;\">&nbsp;4.</td>\n
				<td colspan=\"9\" style=\"border-right:1px solid black;border-bottom:1px solid black;\">&nbsp;To promote on the next service</td>\n
				<td colspan=\"3\" style=\"border-right:1px solid black;border-bottom:1px solid black;text-align:center;\">
					<input type=\"radio\" $disableall name=\"epr1\" $epr14 value=\"4\">
				</td>\n
				<td colspan=\"3\" style=\"border-right:1px solid black;border-bottom:1px solid black;text-align:center;\">
					<input type=\"radio\" $disableall name=\"epr2\" $epr24 value=\"4\">
				</td>\n
				<td colspan=\"3\" style=\"border-right:1px solid black;border-bottom:1px solid black;text-align:center;\">
					<input type=\"radio\" $disableall name=\"epr3\" $epr34 value=\"4\">
				</td>\n
				<td>&nbsp;</td>\n
				<!--<td colspan=\"15\" style=\"border-bottom:1px dashed black;\">&nbsp;</td>\n-->
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"17\">Management Company's instruction for Extra Trainings</td>\n
				<td colspan=\"5\">To do without fail</td>\n
				<td style=\"border:1px dashed black;text-align:center;\">
					<input type=\"radio\" $disableall name=\"extratrain\" $extratrain1 value=\"extratrain1\">
				</td>\n
				<td style=\"text-align:center;\">/</td>\n
				<td colspan=\"7\">Not required at the moment</td>\n
				<td style=\"border:1px dashed black;text-align:center;\">
					<input type=\"radio\" $disableall name=\"extratrain\" $extratrain2 value=\"extratrain2\">
				</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td colspan=\"2\">&nbsp;</td>\n
				<td colspan=\"17\">Report of enforcement for Extra Trainings (by Manning Co. )</td>\n
				<td colspan=\"2\">Done</td>\n
				<td style=\"border:1px dashed black;text-align:center;\">
					<input type=\"radio\" $disableall name=\"repextratrain\" $repextratrain1 value=\"repextratrain1\">
				</td>\n
				<td colspan=\"4\" style=\"text-align:center;\">/&nbsp;&nbsp;Not Yet</td>\n
				<td style=\"border:1px dashed black;text-align:center;\">
					<input type=\"radio\" $disableall name=\"repextratrain\" $repextratrain2 value=\"repextratrain2\">
				</td>\n
				<td>&nbsp;</td>\n
				<td colspan=\"9\" style=\"border-bottom:1px dashed black;\">Others:&nbsp;
					<input type=\"text\" name=\"repextratrainrem\" size=\"20\" value=\"$repextratrainrem\"
						$disableall style=\"font-size:9pt;$bgcolor;font-weight:bold;border:0;\">
				</td>\n
			</tr>
			<tr height=\"16px\">\n
				<td>&nbsp;</td>\n
			</tr>
		</table>
		$btnprev&nbsp;&nbsp;
	</div>
	<input type=\"hidden\" name=\"neghidden\">
	<input type=\"hidden\" name=\"actiontxt\">
	<input type=\"hidden\" name=\"totalgrade\">
	<script>
		codefunc('vpc1',4);
		codefunc('vpc2',4);
		codefunc('vpc3',4);
		codefunc('vpc4',4);
		codefunc('vpc5',4);
		codefunc('vpc6',4);
		codefunc('vee1',4);
		codefunc('vep0',3);
		codefunc('vep1',4);
		codefunc('vep2',4);
		codefunc('vep3',4);
		codefunc('vep4',4);
		codefunc('var1',4,4);
		codefunc('var2',4,4);
		codefunc('var3',4,4);
		codefunc('var4',4,4);
		codefunc('vpj1',4,4);
		codefunc('vpj2',4,4);
		codefunc('vpj3',4,4);
		codefunc('vpj4',4,4);
		codefunc('vsm1',4,3);
		codefunc('vsm2',4,3);
		codefunc('vsm3',4,3);
		codefunc('vpr1',4,3);
		codefunc('vpr2',4,3);
		codefunc('vpr3',4,3);
		codefunc('vbe1',4,3);
		codefunc('vbe2',4,3);
		codefunc('vbe3',4,3);
		codefunc('vsc1',4);
		codefunc('vsc2',4);
		codefunc('vsc3',4);
		if('$negdiscussed'=='Y')
			chkchosen('yes',1);
		if('$negdiscussed'=='N')
			chkchosen('no',1);
		
	</script>
</form>
</center>
";
include('veritas/include/printclose.inc');
echo "
</body>\n
</html>\n";

?>
