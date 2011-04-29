<?php


	
$formname = "formreportsothers";
$formtitle = "OTHER REPORTS";

	

//POSTS


//$style = "style=\"border:2px solid red;background-color:black;color:Yellow;font-size:0.8em;font-weight:Bold;cursor:pointer;\"";
$style = "style=\"color:Blue;font-size:0.8em;font-weight:Bold;cursor:pointer;\"";

echo "
<html>
<head>
<link rel=\"stylesheet\" type=\"text/css\" href=\"veripro.css\">
<script language=\"javascript\" src=\"veripro.js\"></script>

<script>

	
</script>
</head>
<body>

<form name=\"$formname\" method=\"POST\">
	

	<div style=\"width:100%;height:500px;padding:5px 20px 0 20px;overflow:hidden;\">
			
		<div style=\"width:100%;height:250px;padding:5px 20px 0 20px;overflow:hidden;\">
			<br />
			<span class=\"sectiontitle\">$formtitle</span>
			<br /><br />
			
			<a href=\"#\" onclick=\"openWindow('reports/US_VISA.pdf', 'report1' ,0, 0);\" $style>US VISA</a>
			<br /><br />
			<a href=\"#\" onclick=\"openWindow('reports/AUS_VISA.pdf', 'report2' ,0, 0);\" $style>Australian Maritime Crew VISA</a>
			<br /><br />
			<a href=\"#\" onclick=\"openWindow('reports/Crew_Withdrawal.pdf', 'report4' ,0, 0);\" $style>Crew Withdrawal</a>
			<br /><br />
			<a href=\"#\" onclick=\"openWindow('reports/WeeklyReport.pdf', 'report5' ,0, 0);\" $style>Weekly Report</a>
			<br /><br />
			<a href=\"#\" onclick=\"openWindow('reports/WeeklyTrainingReport.pdf', 'report3' ,0, 0);\" $style>Weekly Training Report</a>
			<br /><br />
		</div>
	</div>

	
	<input type=\"hidden\" name=\"actiontxt\" />
</form>

</body>
</html>
";

?>

