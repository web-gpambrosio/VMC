<?php


require("veritas/fpdf/fpdf.php");
include("veritas/connectdb.php");


$currentdate=date("d F Y");

if (isset($_GET["rankcode"]))
	$rankcode = $_GET["rankcode"];
	
$qryhighestrank=mysql_query("SELECT ALIAS1 FROM rank WHERE RANKCODE='$rankcode'") or die(mysql_error());
$rowhighestrank=mysql_fetch_array($qryhighestrank);
$highestrank=$rowhighestrank[0];

//$fromdate="test";

class PDF extends FPDF
{
//Page header
function Header()
{
	global $highestrank,$rankcode,$currentdate;
    $this->SetFont('Arial','B',15);
    $this->SetXY(5,1);
    $this->Cell(200,10,'VERITAS MARITIME CORPORATION',0,0,'C');
    $this->SetXY(5,6);
    $this->SetFont('Arial','B',10);
    $this->Cell(200,10,"As of $currentdate",0,0,'C');
    $this->SetXY(5,10);
    $this->Cell(200,10,"$highestrank with Higher License",0,0,'C');

    //COLUMN HEADER
    $Y=30;
    $this->SetFont('Arial','',10);
    $this->SetXY(7,$Y);
    $this->Cell(70,4,"Name of Seaman",0,0);
    $this->SetXY(77,$Y-4);
    $this->MultiCell(25,4,"Higher License",0,'C');
    $this->SetXY(102,$Y);
    $this->Cell(50,4,"Status",0,0,'L');
    $this->Cell(55,4,"Vessel",0,0,'L');
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
		SELECT CONCAT(c.FNAME,', ',c.GNAME,' ',LEFT(c.MNAME,1),'.') AS NAME,r.ALIAS1,
		cc.CCID,cc.APPLICANTNO,cc.RANKCODE,r1.RANKING,cc.DATEDISEMB,
		cds.APPLICANTNO AS APPLICANTNO1,cds.RANKCODE AS RANKCODE1,r.RANKING AS RANKING1
		FROM crewdocstatus cds
		LEFT JOIN crewchange cc ON cc.APPLICANTNO=cds.APPLICANTNO
		  AND cc.RANKCODE IN ('D21','D22','D23','D32','E21','E22','E23','E31','E32')
		LEFT JOIN crewwithdrawal cw ON cc.APPLICANTNO=cw.APPLICANTNO
		  AND (LIFTWITHDRAWAL=0 OR LIFTWITHDRAWAL IS NULL)
		LEFT JOIN rank r ON cds.RANKCODE=r.RANKCODE
		LEFT JOIN rank r1 ON cc.RANKCODE=r1.RANKCODE
		LEFT JOIN crew c ON cds.APPLICANTNO=c.APPLICANTNO
		LEFT JOIN crewdeceased cd ON cds.APPLICANTNO=cd.APPLICANTNO
		WHERE cw.APPLICANTNO IS NULL AND cds.DOCCODE='F1' AND r1.RANKING>r.RANKING
		  AND cd.APPLICANTNO IS NULL
		ORDER BY cds.APPLICANTNO,r1.RANKING,r.RANKING
		) x
	WHERE DATEDISEMB>'2008-01-01' AND RANKCODE='$rankcode'
	GROUP BY APPLICANTNO
	") or die(mysql_error());

$cntdata=0;
$cntpage=0;
$row=37;
$nametmp="";
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',9);
while($rowlist=mysql_fetch_array($qrylist))
{
	$name=$rowlist["NAME"];
	$alias1=$rowlist["ALIAS1"];
	$applicantno=$rowlist["APPLICANTNO"];
	$status1="";
	$status2="";
	$status="";
	
	//STATUS AND VESSEL
	$qrydtl=mysql_query("
		SELECT cc.CCID,VESSEL,cc.DATEEMB,cc.DEPMNLDATE,
		IF(cpr.CCID IS NOT NULL,cc1.ARRMNLDATE,cc.ARRMNLDATE) AS ARRMNLDATE
		FROM crewchange cc
		LEFT JOIN crewpromotionrelation cpr ON cc.CCID=cpr.CCID
		LEFT JOIN crewchange cc1 ON cpr.CCIDPROMOTE=cc1.CCID
		LEFT JOIN crewpromotionrelation cpr1 ON cc.CCID=cpr1.CCIDPROMOTE
		LEFT JOIN vessel v ON cc.VESSELCODE=v.VESSELCODE
		WHERE cc.APPLICANTNO=$applicantno AND cpr1.CCID IS NULL
		ORDER BY IF(cpr.CCID IS NOT NULL,cc1.DATEDISEMB,cc.DATEDISEMB) DESC
		LIMIT 1
		") or die(mysql_error());
	$rowdtl=mysql_fetch_array($qrydtl);
	$ccid=$rowdtl["CCID"];
	$vessel=$rowdtl["VESSEL"];
	$dateemb=$rowdtl["DATEEMB"];
	$depmnldate=$rowdtl["DEPMNLDATE"];
	$arrmnldate=$rowdtl["ARRMNLDATE"];
	
	if(empty($dateemb))
		$status2="No experience";
	else 
	{
		if(empty($depmnldate))
			$status2="Lined-up";
		else 
		{
			if(empty($arrmnldate))
				$status2="On-board";
			else 
			{
				$status2="Standby";
				$vessel="";
			}
		}
	}
	
	//CHECK IF EX-CREW
	$qryvmc=mysql_query("SELECT CCID FROM crewchange WHERE APPLICANTNO=$applicantno AND CCID<>$ccid") or die(mysql_error());
	$rowvmc=mysql_fetch_array($qryvmc);
	if(mysql_num_rows($qryvmc)==0 || $status2=="Standby")
		$status=$status2;
	else 
		
		$status="Ex-crew ".$status2;
	
	if($cntpage==57)
	{
		$pdf->AddPage();
		$row=40;
		$cntpage=0;
	}
	$pdf->SetXY(5,$row);
    $pdf->Cell(2,4,$cntdata+1,0,0,'R');
    $pdf->Cell(70,4,$name,0,0);
    $pdf->Cell(25,4,$alias1,0,0,'C');
    $pdf->Cell(50,4,$status,0,0,'L');
    $pdf->Cell(55,4,$vessel,0,0,'L');
    
//	$pdf->SetXY(5,$row);
//    $pdf->SetTextColor(0,0,0);
//    $pdf->Cell(35,4,$name,0,0);
//    $pdf->Cell(8,4,$flag,0,0,'C');
//    $pdf->Cell(8,4,$vesselalias,0,0,'C');
//    $pdf->Cell(8,4,$rank,0,0,'C');
//    $pdf->Cell(12,4,$contract,0,0,'C');
    
	$row+=5;
	$cntdata++;
	$cntpage++;
}
	

$pdf->Output();

?>

