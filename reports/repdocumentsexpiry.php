<?php


require("veritas/fpdf/fpdf.php");
include("veritas/connectdb.php");


$currentdate=date("d F Y");

if (isset($_GET["bdate"]))
	$getfromdate = $_GET["bdate"];
	
if (isset($_GET["edate"]))
	$gettodate = $_GET["edate"];
	
$fromdate = date("F Y",strtotime($getfromdate));
$todate = date("F Y",strtotime($gettodate));

$fromdateraw = date("Ymd",strtotime($getfromdate));
$todateraw = date("Ymd",strtotime($gettodate));

//$fromdate="test";

class PDF extends FPDF
{
//Page header
function Header()
{
	global $fromdate,$todate,$currentdate;
    $this->SetFont('Arial','B',15);
    $this->SetXY(5,1);
    $this->Cell(200,10,'VERITAS MARITIME CORPORATION',0,0,'C');
    $this->SetXY(5,6);
    $this->SetFont('Arial','B',10);
    $this->Cell(200,10,'CREW ONBOARD LIST, PROJECTED DOCUMENTS EXPIRY',0,0,'C');
    $this->SetXY(5,10);
    $this->SetFont('Arial','',10);
    $this->Cell(200,10,"For the period covered from $fromdate to $todate",0,0,'C');
    $this->SetXY(5,14);
    $this->Cell(200,10,"Run Date : $currentdate",0,0,'C');
//    $this->SetXY(5,20);
    $this->SetFont('Arial','',9);
    $this->Text(5,28,"Actions Taken :");
    $this->Text(32,28,"(A) Documents not with VMC, already requested from vessel");
    $this->Text(32,32,"(B) Documents with VMC, for Application");
    $this->Text(32,36,"(C) Documents already applied");
    $this->Text(130,28,"(D) Documents renewed for sending to vessel");
    $this->Text(130,32,"(E) Disembarking before expiration");
    $this->Text(130,36,"(F) Not Applicable");
    
    $Y=46;
    $this->Text(5,$Y-4,"All date format is YMD");
    $this->Text(5,$Y,"Expired Docs: COC");
    $this->SetLineWidth(1);
    //COC
    $this->SetXY(34,$Y-1.5);
    $this->SetDrawColor(0,0,255);
    $this->Cell(7,1," ",1,0,'C');
    //SEAMAN'S BOOK
    $this->Text(45,$Y,"Seaman's Book");
    $this->SetXY(69,$Y-1.5);
    $this->SetDrawColor(0,128,0);
    $this->Cell(7,1," ",1,0,'C');
    //FLAG BOOK
    $this->Text(80,$Y,"Flag Book");
    $this->SetXY(96,$Y-1.5);
    $this->SetDrawColor(128,128,0);
    $this->Cell(7,1," ",1,0,'C');
    //PASSPORT
    $this->Text(107,$Y,"Passport");
    $this->SetXY(121,$Y-1.5);
    $this->SetDrawColor(128,0,0);
    $this->Cell(7,1," ",1,0,'C');
    //PAN GMDSS
    $this->Text(132,$Y,"Pan GMDSS");
    $this->SetXY(152,$Y-1.5);
    $this->SetDrawColor(0,128,128);
    $this->Cell(7,1," ",1,0,'C');
    //GOC
    $this->Text(163,$Y,"GOC");
    $this->SetXY(172,$Y-1.5);
    $this->SetDrawColor(255,0,0);
    $this->Cell(7,1," ",1,0,'C');
    //USV
    $this->Text(183,$Y,"USV");
    $this->SetXY(192,$Y-1.5);
    $this->SetDrawColor(255,128,255);
    $this->Cell(7,1," ",1,0,'C');
    
    //COLUMN HEADER
    $Y=52;
    $this->SetFont('Arial','',8);
    $this->SetLineWidth(0);
    $this->SetXY(5,$Y);
    $this->SetDrawColor(0,0,0);
    $this->Cell(35,4,"Name of Seaman",0,0);
    $this->Cell(8,4,"Flag",0,0,'C');
    $this->Cell(8,4,"Vsl",0,0,'C');
    $this->Cell(8,4,"Rank",0,0,'C');
    $this->Cell(12,4,"Contract",0,0,'C');
    $this->Cell(12,4,"C.O.C.",0,0,'C');
    $this->Cell(5,4,"AT",0,0,'C');
    $this->Cell(12,4,"SCBD",0,0,'C');
    $this->Cell(5,4,"AT",0,0,'C');
    $this->SetXY(110,$Y-4);
    $this->MultiCell(12,4,"Flag Book",0,'C');
    $this->SetXY(122,$Y);
    $this->Cell(5,4,"AT",0,0,'C');
    $this->Cell(12,4,"Passport",0,0,'C');
    $this->Cell(5,4,"AT",0,0,'C');
    $this->SetXY(144,$Y-4);
    $this->MultiCell(13,4,"Panama GMDSS",0,'C');
    $this->SetXY(157,$Y);
    $this->Cell(5,4,"AT",0,0,'C');
    $this->Cell(12,4,"GOC",0,0,'C');
    $this->Cell(5,4,"AT",0,0,'C');
    $this->Cell(12,4,"USV",0,0,'C');
    $this->Cell(5,4,"AT",0,0,'C');
}

//Page footer
function Footer()
{
    //Position at 1.5 cm from bottom
    $this->SetY(-12);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}

$PaperSize='A4';
$PO='P';

$pdf=new PDF($PO,'mm',$PaperSize);
$pdf->SetAutoPageBreak('false',1);
$pdf->SetMargins(0.2,0.2);

$pdf->SetFont('Arial','',10);

$qrylist=mysql_query("
	SELECT * FROM (
	SELECT IF (c.ARRMNLDATE IS NOT NULL,'0','1') AS CREWONBOARD,c.CCID,c.APPLICANTNO,cr.CREWCODE,
	CONCAT(cr.FNAME,', ',LEFT(cr.GNAME,1),'. ',LEFT(cr.MNAME,1),'.') AS NAME,v.DIVCODE,
	r.RANKCODE,r.RANKFULL,r.RANKLEVELCODE,r.RANKING,r.ALIAS1,LEFT(v.VESSEL,10) AS VESSEL,v.ALIAS1 AS VALIAS,
	IF(c.DATECHANGEDISEMB IS NULL,c.DATEDISEMB,c.DATECHANGEDISEMB) AS DATEDISEMB,c.DATEEMB,c.ARRMNLDATE,
	IF(DATEDIFF(CURRENT_DATE,IF(c.DATECHANGEDISEMB IS NULL,c.DATEDISEMB,c.DATECHANGEDISEMB)) > 365,1,0) AS INACTIVE,
	DATEDIFF(CURRENT_DATE,IF(DATECHANGEDISEMB IS NULL,DATEDISEMB,DATECHANGEDISEMB)) AS DIFF,LEFT(v.FLAGCODE,2) AS FLAG,r.ALIAS2
	FROM
	(
		SELECT MAX(CCID) AS CCID,APPLICANTNO
		FROM
		(
			SELECT c.CCID,APPLICANTNO,IF(DATECHANGEDISEMB IS NULL,DATEDISEMB,DATECHANGEDISEMB) AS DATEDISEMB,DEPMNLDATE,ARRMNLDATE
			FROM crewchange c
			LEFT JOIN vessel v ON v.VESSELCODE=c.VESSELCODE
			LEFT JOIN crewpromotionrelation cpr ON cpr.CCIDPROMOTE=c.CCID
			LEFT JOIN crewpromotionrelation cr ON cr.CCID=c.CCID
			WHERE (DEPMNLDATE IS NOT NULL OR cpr.CCID IS NOT NULL) AND cr.CCID IS NULL
		) x
		GROUP BY APPLICANTNO
		ORDER BY APPLICANTNO,DATEDISEMB DESC
	) y
	LEFT JOIN crewchange c ON c.CCID=y.CCID
	LEFT JOIN crew cr ON cr.APPLICANTNO=c.APPLICANTNO
	LEFT JOIN rank r ON r.RANKCODE=c.RANKCODE
	LEFT JOIN vessel v ON v.VESSELCODE=c.VESSELCODE
	LEFT JOIN crewtransfer ct ON ct.APPLICANTNO=c.APPLICANTNO
	LEFT JOIN crewnfr cn ON cn.APPLICANTNO=c.APPLICANTNO
	WHERE cr.STATUS=1 AND ct.APPLICANTNO IS NULL AND cn.APPLICANTNO IS NULL
	) z
	WHERE CREWONBOARD = 1 AND INACTIVE = 0
	ORDER BY NAME
	") or die(mysql_error());

$cntdata=0;
$cntpage=0;
$row=59;
$nametmp="";
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',8);
while($rowlist=mysql_fetch_array($qrylist))
{
	$applicantno=$rowlist["APPLICANTNO"];
	$name=$rowlist["NAME"];
	$flag=$rowlist["FLAG"];
	$vesselalias=$rowlist["VALIAS"];
	$rank=$rowlist["ALIAS2"];
	$ranklevel=$rowlist["RANKLEVELCODE"];
	$contract=date("ymd",strtotime($rowlist["DATEDISEMB"]));
	$contractraw=date("Ymd",strtotime($rowlist["DATEDISEMB"]));
	
	
	//COC
	if($ranklevel=="M" || $ranklevel=="O")
		$cocdoc="18";
	else 
		$cocdoc="C0";
	$qrycoc=mysql_query("SELECT DATEEXPIRED AS COCDATE 
		FROM crewdocstatus 
		WHERE APPLICANTNO=$applicantno AND DOCCODE='$cocdoc'
		ORDER BY DATEEXPIRED DESC
		LIMIT 1") or die(mysql_error());
	$rowcoc=mysql_fetch_array($qrycoc);
	$cocat="";
	$coccolor=0;
	if(empty($rowcoc["COCDATE"]))
		$cocdate="";
	else
	{
		$cocdate=date("ymd",strtotime($rowcoc["COCDATE"]));
		$cocdateraw=date("Ymd",strtotime($rowcoc["COCDATE"]));
		if($cocdateraw<=$todateraw)
		{
			$coccolor=1;
			if($contractraw<$cocdateraw)
				$cocat="E";
		}
	}
	
	//SEAMAN'S BOOK
	$qryscbd=mysql_query("SELECT DATEEXPIRED AS SCBDDATE 
		FROM crewdocstatus 
		WHERE APPLICANTNO=$applicantno AND DOCCODE='F2'
		ORDER BY DATEEXPIRED DESC
		LIMIT 1") or die(mysql_error());
	$rowscbd=mysql_fetch_array($qryscbd);
	$scbddateraw1=$rowscbd["SCBDDATE"];
	$scbdat="";
	$scbdcolor=0;
	if(empty($scbddateraw1))
		$cocdate="";
	else
	{
		$scbddate=date("ymd",strtotime($scbddateraw1));
		$scbddateraw=date("Ymd",strtotime($scbddateraw1));
		if($scbddateraw<=$todateraw)
		{
			$scbdcolor=1;
			if($contractraw<$scbddateraw)
				$scbdat="E";
		}
	}
	
	//FLAG BOOK
	if($flag=="BA")
		$fbkdoc="B2";
	else if($flag=="JP" || $flag=="JA")
		$fbkdoc="J2";
	else if($flag=="PA")
		$fbkdoc="P2";
	$qryfbk=mysql_query("SELECT DATEEXPIRED AS FBKDATE 
		FROM crewdocstatus 
		WHERE APPLICANTNO=$applicantno AND DOCCODE='$fbkdoc'
		ORDER BY DATEEXPIRED DESC
		LIMIT 1") or die(mysql_error());
	$rowfbk=mysql_fetch_array($qryfbk);
	$fbkdateraw1=$rowfbk["FBKDATE"];
	$fbkat="";
	$fbkcolor=0;
	if(empty($fbkdateraw1))
		$fbkdate="";
	else
	{
		$fbkdate=date("ymd",strtotime($fbkdateraw1));
		$fbkdateraw=date("Ymd",strtotime($fbkdateraw1));
		if($fbkdateraw<=$todateraw)
		{
			$fbkcolor=1;
			if($contractraw<$fbkdateraw)
				$fbkat="E";
		}
	}
	
	//PASSPORT
	$qrypassport=mysql_query("SELECT DATEEXPIRED AS PASSPORTDATE 
		FROM crewdocstatus 
		WHERE APPLICANTNO=$applicantno AND DOCCODE='41'
		ORDER BY DATEEXPIRED DESC
		LIMIT 1") or die(mysql_error());
	$rowpassport=mysql_fetch_array($qrypassport);
	$passportdateraw1=$rowpassport["PASSPORTDATE"];
	$passportat="";
	$passportcolor=0;
	if(empty($passportdateraw1))
		$passportdate="";
	else
	{
		$passportdate=date("ymd",strtotime($passportdateraw1));
		$passportdateraw=date("Ymd",strtotime($passportdateraw1));
		if($passportdateraw<=$todateraw)
		{
			$passportcolor=1;
			if($contractraw<$passportdateraw)
				$passportat="E";
		}
	}
	
	//PAN-GMDSS
	$qrygmdss=mysql_query("SELECT DATEEXPIRED AS GMDSSDATE 
		FROM crewdocstatus 
		WHERE APPLICANTNO=$applicantno AND DOCCODE='44'
		ORDER BY DATEEXPIRED DESC
		LIMIT 1") or die(mysql_error());
	$rowgmdss=mysql_fetch_array($qrygmdss);
	$gmdssdateraw1=$rowgmdss["GMDSSDATE"];
	$gmdssat="";
	$gmdsscolor=0;
	if(empty($gmdssdateraw1))
		$gmdssdate="";
	else
	{
		$gmdssdate=date("ymd",strtotime($gmdssdateraw1));
		$gmdssdateraw=date("Ymd",strtotime($gmdssdateraw1));
		if($gmdssdateraw<=$todateraw)
		{
			$gmdsscolor=1;
			if($contractraw<$gmdssdateraw)
				$gmdssat="E";
		}
	}
	
	//GOC
	$qrygoc=mysql_query("SELECT DATEEXPIRED AS GOCDATE 
		FROM crewdocstatus 
		WHERE APPLICANTNO=$applicantno AND DOCCODE='27'
		ORDER BY DATEEXPIRED DESC
		LIMIT 1") or die(mysql_error());
	$rowgoc=mysql_fetch_array($qrygoc);
	$gocdateraw1=$rowgoc["GOCDATE"];
	$gocat="";
	$goccolor=0;
	if(empty($gocdateraw1))
		$gocdate="";
	else
	{
		$gocdate=date("ymd",strtotime($gocdateraw1));
		$gocdateraw=date("Ymd",strtotime($gocdateraw1));
		if($gocdateraw<=$todateraw)
		{
			$goccolor=1;
			if($contractraw<$gocdateraw)
				$gocat="E";
		}
	}
	
	//USV
	$qryusv=mysql_query("SELECT DATEEXPIRED AS USVDATE 
		FROM crewdocstatus 
		WHERE APPLICANTNO=$applicantno AND DOCCODE='42'
		ORDER BY DATEEXPIRED DESC
		LIMIT 1") or die(mysql_error());
	$rowusv=mysql_fetch_array($qryusv);
	$usvdateraw1=$rowusv["USVDATE"];
	$usvat="";
	$usvcolor=0;
	if(empty($usvdateraw1))
		$usvdate="";
	else
	{
		$usvdate=date("ymd",strtotime($usvdateraw1));
		$usvdateraw=date("Ymd",strtotime($usvdateraw1));
		if($usvdateraw<=$todateraw)
		{
			$usvcolor=1;
			if($contractraw<$usvdateraw)
				$usvat="E";
		}
	}
	
		
	if($cntpage==57)
	{
		$pdf->AddPage();
		$row=59;
		$cntpage=0;
	}
	$pdf->SetXY(5,$row);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(35,4,$name,0,0);
    $pdf->Cell(8,4,$flag,0,0,'C');
    $pdf->Cell(8,4,$vesselalias,0,0,'C');
    $pdf->Cell(8,4,$rank,0,0,'C');
    $pdf->Cell(12,4,$contract,0,0,'C');
    if($coccolor==1)
    	$pdf->SetTextColor(0,0,255);
    else 
    	$pdf->SetTextColor(0,0,0);
    $pdf->Cell(12,4,$cocdate,0,0,'C');
    $pdf->Cell(5,4,$cocat,0,0,'C');
    if($scbdcolor==1)
    	$pdf->SetTextColor(0,128,0);
    else 
    	$pdf->SetTextColor(0,0,0);
    $pdf->Cell(12,4,$scbddate,0,0,'C');
    $pdf->Cell(5,4,$scbdat,0,0,'C');
    if($fbkcolor==1)
    	$pdf->SetTextColor(128,128,0);
    else 
    	$pdf->SetTextColor(0,0,0);
    $pdf->Cell(12,4,$fbkdate,0,0,'C');
    $pdf->Cell(5,4,$fbkat,0,0,'C');
    if($passportcolor==1)
    	$pdf->SetTextColor(128,0,0);
    else 
    	$pdf->SetTextColor(0,0,0);
    $pdf->Cell(12,4,$passportdate,0,0,'C');
    $pdf->Cell(5,4,$passportat,0,0,'C');
    if($gmdsscolor==1)
    	$pdf->SetTextColor(0,128,128);
    else 
    	$pdf->SetTextColor(0,0,0);
    $pdf->Cell(13,4,$gmdssdate,0,0,'C');
    $pdf->Cell(5,4,$gmdssat,0,0,'C');
    if($goccolor==1)
    	$pdf->SetTextColor(255,0,0);
    else 
    	$pdf->SetTextColor(0,0,0);
    $pdf->Cell(12,4,$gocdate,0,0,'C');
    $pdf->Cell(5,4,$gocat,0,0,'C');
    if($usvcolor==1)
    	$pdf->SetTextColor(255,128,255);
    else 
    	$pdf->SetTextColor(0,0,0);
    $pdf->Cell(12,4,$usvdate,0,0,'C');
    $pdf->Cell(5,4,$usvat,0,0,'C');
    $pdf->SetTextColor(0,0,0);
	$row+=4;
	$cntdata++;
	$cntpage++;
}
	

$pdf->Output();

?>

