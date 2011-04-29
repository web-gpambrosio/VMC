<?php


require("veritas/fpdf/fpdf.php");
include("veritas/connectdb.php");


$currentdate=date("d F Y");

if (isset($_GET["crewstatus"]))
	$crewstatus = $_GET["crewstatus"];

if (isset($_GET["rankcode"]))
	$rankcode = $_GET["rankcode"];

if(empty($rankcode))
	$addwherebyrank="";
else if($rankcode!="M" && $rankcode!="O" && $rankcode!="S")
	$addwherebyrank="AND IF(cpr.CCID IS NOT NULL,r1.RANKCODE,r.RANKCODE)='$rankcode'";
else 
	$addwherebyrank="AND IF(cpr.CCID IS NOT NULL,r1.RANKLEVELCODE,r.RANKLEVELCODE)='$rankcode'";
	
switch ($crewstatus)
{
	case "Onboard":
		$qrylist=mysql_query("
			SELECT CONCAT(c.FNAME,', ',c.GNAME,' ',LEFT(c.MNAME,1),'.') AS NAME,
			cc.APPLICANTNO,cc.DATEEMB,cc.DEPMNLDATE,
			IF(cpr.CCID IS NOT NULL,cc1.ARRMNLDATE,cc.ARRMNLDATE) AS ARRMNLDATE,
			IF(cpr.CCID IS NOT NULL,r1.RANK,r.RANK) AS RANK,
			IF(cpr.CCID IS NOT NULL,r1.RANKCODE,r.RANKCODE) AS RANKCODE,
			IF(cpr.CCID IS NOT NULL,r1.RANKLEVELCODE,r.RANKLEVELCODE) AS RANKLEVELCODE,
			IF(cpr.CCID IS NOT NULL,r1.RANKING,r.RANKING) AS RANKING
			FROM crewchange cc
			LEFT JOIN crewpromotionrelation cpr ON cc.CCID=cpr.CCID
			LEFT JOIN crewchange cc1 ON cpr.CCIDPROMOTE=cc1.CCID
			LEFT JOIN crewpromotionrelation cpr1 ON cc.CCID=cpr1.CCIDPROMOTE
			LEFT JOIN rank r ON cc.RANKCODE=r.RANKCODE
			LEFT JOIN rank r1 ON cc1.RANKCODE=r1.RANKCODE
			LEFT JOIN crew c ON cc.APPLICANTNO=c.APPLICANTNO
			LEFT JOIN crewdeceased cd ON cc.APPLICANTNO=cd.APPLICANTNO
			LEFT JOIN crewwithdrawal cw ON cc.APPLICANTNO=cw.APPLICANTNO AND (LIFTWITHDRAWAL=0 OR LIFTWITHDRAWAL IS NULL)
			WHERE cc.DEPMNLDATE IS NOT NULL AND IF(cpr.CCID IS NOT NULL,cc1.ARRMNLDATE,cc.ARRMNLDATE) IS NULL AND cd.APPLICANTNO IS NULL
			AND (cw.NFR=0 OR cw.NFR IS NULL) $addwherebyrank
			ORDER BY IF(cpr.CCID IS NOT NULL,r1.RANKING,r.RANKING),CONCAT(c.FNAME,', ',c.GNAME,' ',LEFT(c.MNAME,1),'.')
			") or die(mysql_error());
		$title="On-board Crew";
	break;
	case "Standby":
		$qrylist=mysql_query("
			SELECT * FROM (
				SELECT * FROM (
					SELECT CONCAT(c.FNAME,', ',c.GNAME,' ',LEFT(c.MNAME,1),'.') AS NAME,
					cc.APPLICANTNO,cc.DATEEMB,cc.DEPMNLDATE,
					IF(cpr.CCID IS NOT NULL,cc1.DATEDISEMB,cc.DATEDISEMB) AS DATEDISEMB,
					IF(cpr.CCID IS NOT NULL,cc1.ARRMNLDATE,cc.ARRMNLDATE) AS ARRMNLDATE,
					IF(cpr.CCID IS NOT NULL,r1.RANK,r.RANK) AS RANK,
					IF(cpr.CCID IS NOT NULL,r1.RANKCODE,r.RANKCODE) AS RANKCODE,
					IF(cpr.CCID IS NOT NULL,r1.RANKLEVELCODE,r.RANKLEVELCODE) AS RANKLEVELCODE,
					IF(cpr.CCID IS NOT NULL,r1.RANKING,r.RANKING) AS RANKING
					FROM crewchange cc
					LEFT JOIN crewpromotionrelation cpr ON cc.CCID=cpr.CCID
					LEFT JOIN crewchange cc1 ON cpr.CCIDPROMOTE=cc1.CCID
					LEFT JOIN crewpromotionrelation cpr1 ON cc.CCID=cpr1.CCIDPROMOTE
					LEFT JOIN rank r ON cc.RANKCODE=r.RANKCODE
					LEFT JOIN rank r1 ON cc1.RANKCODE=r1.RANKCODE
					LEFT JOIN crew c ON cc.APPLICANTNO=c.APPLICANTNO
					LEFT JOIN crewdeceased cd ON cc.APPLICANTNO=cd.APPLICANTNO
					LEFT JOIN crewwithdrawal cw ON cc.APPLICANTNO=cw.APPLICANTNO AND (LIFTWITHDRAWAL=0 OR LIFTWITHDRAWAL IS NULL)
					WHERE cd.APPLICANTNO IS NULL AND (cw.NFR=0 OR cw.NFR IS NULL) $addwherebyrank
					ORDER BY cc.APPLICANTNO,IF(cpr.CCID IS NOT NULL,cc1.DATEDISEMB,cc.DATEDISEMB) DESC
				) x
				GROUP BY APPLICANTNO
				ORDER BY RANKING,NAME
			) y
			WHERE ARRMNLDATE IS NOT NULL
			") or die(mysql_error());
		$title="Standby Crew";
	break;
}

	
class PDF extends FPDF
{
//Page header
function Header()
{
	global $currentdate,$title;
    $this->SetFont('Arial','B',15);
    $this->SetXY(5,1);
    $this->Cell(200,10,'VERITAS MARITIME CORPORATION',0,0,'C');
    $this->SetXY(5,7);
    $this->SetFont('Arial','B',12);
    $this->Cell(200,10,"As of $currentdate",0,0,'C');
    $this->SetXY(5,12);
    $this->Cell(200,10,$title,0,0,'C');

    //COLUMN HEADER
    $Y=23;
    $this->SetFont('Arial','',10);
    $this->SetXY(10,$Y);
    $this->Cell(70,4,"Name of Seaman",0,0);
    $this->Cell(25,4,"Rank",0,0,'C');
    $this->Cell(50,4,"Year Started",0,0,'C');
    $this->Cell(55,4,"Length of Service (Yrs)",0,0,'C');
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


$cntdata=0;
$cntpage=0;
$row=30;
$nametmp="";
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',9);
while($rowlist=mysql_fetch_array($qrylist))
{
	$name=$rowlist["NAME"];
	$rank=$rowlist["RANK"];
	$applicantno=$rowlist["APPLICANTNO"];
	$status1="";
	$status2="";
	$status="";
	
	//YEAR STARTED & SERVICE
	$qrydtl=mysql_query("
		SELECT MIN(DATEEMB) AS YEARSTARTED,SUM(SERVICE) AS LENGTHSERVICE FROM (
		SELECT DATE_FORMAT(cc.DATEEMB,'%Y') AS DATEEMB,
		IF(IF(cpr.CCID IS NOT NULL,cc1.ARRMNLDATE,cc.ARRMNLDATE) IS NOT NULL,DATEDIFF(IF(cpr.CCID IS NOT NULL,cc1.ARRMNLDATE,cc.ARRMNLDATE),cc.DATEEMB)/365.25,0) AS SERVICE
		FROM crewchange cc
		LEFT JOIN crewpromotionrelation cpr ON cc.CCID=cpr.CCID
		LEFT JOIN crewchange cc1 ON cpr.CCIDPROMOTE=cc1.CCID
		LEFT JOIN crewpromotionrelation cpr1 ON cc.CCID=cpr1.CCIDPROMOTE
		WHERE cc.APPLICANTNO=$applicantno AND cpr1.CCID IS NULL
		) x
		") or die(mysql_error());
	$rowdtl=mysql_fetch_array($qrydtl);
	$yearstarted=$rowdtl["YEARSTARTED"];
	$lengthservice=number_format($rowdtl["LENGTHSERVICE"],2);
//	
//	if(empty($dateemb))
//		$status2="No experience";
//	else 
//	{
//		if(empty($depmnldate))
//			$status2="Lined-up";
//		else 
//		{
//			if(empty($arrmnldate))
//				$status2="On-board";
//			else 
//			{
//				$status2="Standby";
//				$vessel="";
//			}
//		}
//	}
	
	
	if($cntpage==51)
	{
		$pdf->AddPage();
		$row=30;
		$cntpage=0;
	}
	$pdf->SetXY(8,$row);
    $pdf->Cell(2,4,$cntdata+1,0,0,'R');
    $pdf->Cell(70,4,$name,0,0);
    $pdf->Cell(25,4,$rank,0,0,'C');
    $pdf->Cell(50,4,$yearstarted,0,0,'C');
    $pdf->Cell(55,4,$lengthservice,0,0,'C');
    
	$row+=5;
	$cntdata++;
	$cntpage++;
}
	

$pdf->Output();

?>

