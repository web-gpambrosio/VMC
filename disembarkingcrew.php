<?php
include('veritas/connectdb.php');


echo "
<html>
<head>
<link rel=\"stylesheet\" type=\"text/css\" href=\"veripro.css\">
<script language=\"javascript\" src=\"veripro.js\"></script>
<script language=\"javascript\" src=\"popcalendar.js\"></script>

</head>
<body>

<form name=\"disembarkingcrew\" method=\"POST\">
	
	<center>
	<div style=\"width:100%;height:500px;padding:5px 20px 0 20px;overflow:hidden;\">
		<br />
		<span class=\"sectiontitle\">REPORTS - Disembarking Crew</span>
		
		<br />
		<span class=\"subsectiontitle\">Date Range</span>
		<br /><br />
			Beg. Date&nbsp;
			<input type=\"text\" name=\"bdate\" value=\"$bdate\" onKeyPress=\"return dateonly(this);\" size=\"10\" 
				maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
			<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" 
				onclick=\"popUpCalendar(bdate, bdate, 'mm/dd/yyyy', 0, 0);return false;\">
			&nbsp;&nbsp;&nbsp;(mm/dd/yy)<br>

			End. Date &nbsp;
			<input type=\"text\" name=\"edate\" value=\"$edate\" onKeyPress=\"return dateonly(this);\" size=\"10\" 
				maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
			<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" 
				onclick=\"popUpCalendar(edate, edate, 'mm/dd/yyyy', 0, 0);return false;\">
			&nbsp;&nbsp;&nbsp;(mm/dd/yy)
		<br />
		
		
		<input type=\"button\" value=\"View\" onclick=\"if(bdate.value=='' || edate.value==''){alert('Input Beg & End date')}else{
			openWindow('reports/reponboardstandbyalpha.php?reptype=3&bdate='+bdate.value+'&edate='+edate.value, 'repdisembarkcrew' ,'850px', '750px')};\" />
		<br />
	</div>

</form>

</body>
</html>
";

?>

