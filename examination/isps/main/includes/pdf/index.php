<?
require('html2fpdf.php');
$pdf=new HTML2FPDF();
$pdf->AddPage();
$fp = fopen("index.php","r");
$strContent = fread($fp, filesize("index.php"));
fclose($fp);
$pdf->WriteHTML($strContent);
$pdf->Output("index.pdf");
/*echo '<script>window.location.href("sample.pdf?a=aaaaaaaaaaaaaaaaaaaaaaaa")</script>';*/
?>
<html>
<body>
asdasdasdasdasd
</body>
</html>