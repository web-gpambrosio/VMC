<?php

include('veritas/connectdb.php');
//include('connectdb.php');

session_start();

include('../include/stylephp.inc');

$datetimenow=date("Y-m-d H:i:s");
$datenow=date("Y-m-d");
$datenowshow=date("d M Y");


	#*******************POST VALUES*************

echo	"<html>\n
<head>\n
<link rel=\"StyleSheet\" type=\"text/css\" href=\"../veripro.css\" />
<style>
#noprint
{
	display: none;
}
</style>
<script>

</script>\n

</head>\n

<body onload=\"\" style=\"\">\n

<form name=\"scholarfasttrack\" id=\"scholarfasttrack\" method=\"POST\">\n
<table>
	<tr height=\"27px\">\n
		<td style=\"width:896px;text-align:center;font-size:16pt;font-weight:bold;\">VERITAS MARITIME CORPORATION</td>\n
	</tr>
	<tr height=\"27px\">\n
		<td style=\"width:896px;text-align:center;font-size:16pt;font-weight:bold;\">List of Deck Scholars (According to License) as of $datenowshow</td>\n
	</tr>
</table>
<table style=\"border:2px solid black;table-layout:fixed;\" cellspacing=\"0\" cellpadding=\"0\">
	$fixedcolumn
	<tr height=\"35px\" style=\"text-align:center;valign:middle;\">\n
		<td style=\"border-right:1px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;\">&nbsp;</td>\n
		<td style=\"border-right:1px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;\">RANK</td>\n
		<td style=\"border-right:1px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;\"\">NAME</td>\n
		<td style=\"border-right:1px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;\">SCHOOL</td>\n
		<td style=\"border-right:1px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;\">YEAR GRAD</td>\n
		<td style=\"border-right:1px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;\">VESSEL</td>\n
		<td style=\"border-right:1px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;\">EMBARK</td>\n
		<td style=\"border-right:1px solid black;font-size:9pt;font-family:Arial Narrow;font-weight:bold;\">END OF CONTRACT</td>\n
		<td style=\"font-size:9pt;font-family:Arial Narrow;font-weight:bold;\">REMARKS</td>\n
	</tr>
</table>
<br>
<table style=\"border-top:1px solid black;border-right:1px solid black;table-layout:fixed;\" cellspacing=\"0\" cellpadding=\"0\">
	
		<tr height=\"18px\" style=\"text-align:center;valign:middle;\">\n
			<td style=\"$styledetails font-family:Arial Narrow;\">$cntrowtype</td>\n
			<td style=\"$styledetails font-family:Arial Narrow;\">$lastrank</td>\n
			<td style=\"$styledetails font-family:Arial Narrow;text-align:left;\" title=\"$applicantno\">&nbsp;&nbsp;$name</td>\n
			<td style=\"$styledetails font-family:Arial Narrow;\">&nbsp;$school</td>\n
			<td style=\"$styledetails font-family:Arial Narrow;\">&nbsp;$yeargraduate</td>\n
			<td style=\"$styledetails font-family:Arial Narrow;\">&nbsp;$vessel</td>\n
			<td style=\"$styledetails font-family:Arial Narrow;\">$dateembshow</td>\n
			<td style=\"$styledetails font-family:Arial Narrow;\">$datedisembshow</td>\n
			<td style=\"$styledetails font-family:Arial Narrow;\">$rem</td>\n
		</tr>";
echo "
</table>

</form>";


include('../include/printclose.inc');
echo "
</body>\n
</html>\n";

?>
