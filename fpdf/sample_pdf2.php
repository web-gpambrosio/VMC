<?php
require('fpdf.php');


$dbhost = 'localhost';
$dbuser = 'gino';
$dbpass = 'shirly';
$dbname = 'test';

// This is an example opendb.php
$conn = mysql_connect($dbhost, $dbuser, $dbpass);

if (!$conn)
{
  die('Error connecting to MySQL Local Server.' . mysql_error());
}

mysql_select_db($dbname,$conn);


$query = mysql_query("select * from TABLE1 where TRANSCODE='IS'") or die(mysql_errno());

$pdf=new FPDF();
$pdf->AddPage();
$pdf->SetFont('Courier','B',8);

$X = 20;
$Y = 70;

while($row = mysql_fetch_array($query))
	{
	
	$refno = $row['REFNO'];
	$transcode = $row['TRANSCODE'];
	$msg = $row['MESSAGE'];		

	$pdf->SetXY($X,$Y);
	$pdf->Cell(30,0,$refno);
	
	$pdf->SetXY($X,$Y);	
	$pdf->Cell(30,30,$transcode);
	
	$pdf->SetXY($X,$Y);	
	$pdf->Cell(30,50,$msg);
	

	$Y += -50;

	}

$pdf->Output();
?>