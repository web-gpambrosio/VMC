<?php

$style = "style=\"color:Blue;font-size:0.8em;font-weight:Bold;cursor:pointer;\"";

echo "
<html>
<head>
<link rel=\"stylesheet\" type=\"text/css\" href=\"veripro.css\">
<script language=\"javascript\" src=\"veripro.js\"></script>

</head>
<body>

<form name=\"$formname\" method=\"POST\">
	
	<div style=\"width:100%;height:500px;padding:5px 20px 0 20px;overflow:hidden;\">
		<br />
		<span class=\"sectiontitle\">REPORTS</span>
		<br /><br />
<!--	
		<a href=\"#\" onclick=\"openWindow('reports/repscholarfasttrack_vmc.php', 'rep1' ,0, 0);\" $style>1. VMC Scholars & Fast Track Cadets</a>
		<br /><br />
		
		<a href=\"#\" onclick=\"openWindow('reports/repscholarlist_vmc.php', 'rep2' ,0, 0);\" $style>2. VMC Scholars (Statistical Summary)</a>
		<br /><br />
-->

		<a href=\"#\" onclick=\"openWindow('reports/scholars/ScholarsFasttracks.pdf', 'rep1' ,0, 0);\" $style>VMC Scholars and Fasttracks</a>
		<br /><br />
		
		<a href=\"#\" onclick=\"openWindow('reports/scholars/PromotedScholars.pdf', 'rep2' ,0, 0);\" $style>VMC Promoted Scholars</a>
		<br /><br />
				
		<a href=\"#\" onclick=\"openWindow('reports/scholars/CookCadets.pdf', 'rep2' ,0, 0);\" $style>VMC Cook Cadetship Program Summary</a>
		<br /><br />
				
		<a href=\"#\" onclick=\"openWindow('reports/scholars/CadetshipProgramSummary.pdf', 'rep2' ,0, 0);\" $style>VMC Cadetship Program Summary</a>
		<br /><br />
	
	</div>

</form>

</body>
</html>
";

?>

