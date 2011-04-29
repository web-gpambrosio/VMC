<?php
require('fpdf.php');


$dbhost = 'localhost';
$dbuser = 'carlo';
$dbpass = 'focc';
$dbname = 'focc';

// This is an example opendb.php
$conn = mysql_connect($dbhost, $dbuser, $dbpass);

if (!$conn)
{
  die('Error connecting to MySQL Local Server.' . mysql_error());
}

mysql_select_db($dbname,$conn);


if (isset($_POST['button']))
{$refno = $_POST['refno'];
$transcode = $_POST['transcode'];
$terms = $_POST['terms'];

if ($_POST['button'] == '') {
	if ($transcode != null) {
	$query=mysql_query("select REFNO from MAINHEADER where TRANSCODE='$transcode'");
	$row=mysql_fetch_array($query);
	if ($row['REFNO'] != null)
		echo $row['REFNO'];
	else { 
		echo "WALANG " . $transcode . " DITO!";
		$transcode=NULL;
	}
	}

}
	


if ($_POST['button'] == 'Save')
$query = mysql_query("CALL ADD_REFNO('$refno', '$transcode', '$terms')") or die(mysql_errno());


if ($_POST['button'] == 'Show') 
{
$query = mysql_query("select * from MAINHEADER where REFNO='$refno'") or die(mysql_errno());	



$pdf=new FPDF();
$pdf->AddPage();
//$pdf->SetFont('Courier','B',8);
$pdf->SetFont('Arial','U',16);

$X = 20;
$Y = 70;

while($row = mysql_fetch_array($query))
	{
	
	$refno = $row['REFNO'];
	$transcode = $row['TRANSCODE'];
	$msg = $row['TERMSDELIVERYCODE'];		

	$pdf->SetXY($X,$Y);
	$pdf->Cell(30,0,$refno);
	
	$pdf->SetXY($X,$Y);	
	$pdf->Cell(30,30,$transcode);
	
	$pdf->SetXY($X,$Y);	
	$pdf->Cell(30,50,$msg);
	

	$Y += -50;

	}

$pdf->Output();
}
}
?>

<html>
<head>
<title>
</title>
<script language="Javascript">

function setvisible() 
{	
		document.getElementById("btn").style.visibility="hidden";
		<?php
			if (isset($_POST['transcode']))
			 if ($transcode == null) {
		?>	
			carlo.transcode.focus();
		<?php
			}
			else {
		?>
			carlo.terms.focus();
		<?php
		}
		?>
}

function valid(transcode) 
{
		carlo.button[0].focus();
		carlo.button[0].click();
		
		
}
</script>

</head>

<body> 

<form name="carlo" action="sample_pdf.php" method="POST" >
Refno :
<input id="ref" type="text" name="refno" value="<?php echo $refno;?>" /><br />
TransCode :
<input type="text" name="transcode" onchange="valid(this)" value="<?php echo $transcode;?>"/>
<input id="btn" type="submit" name="button" value="" size="1" /><br />
Terms of Delivery :
<input type="text" name="terms" value="<?php echo $terms;?>"/><br />
<input type="submit" name="button" value="Save" />

 <input type="submit" name="button" value="Show"/>
 
</form>
<script language="Javascript">
	setvisible();
</script>
</body>


</html>







































