<?php


echo "
<html>
<head>
<title>

</title>

<link rel=\"stylesheet\" type=\"text/css\" href=\"veripro.css\">
<script language=\"javascript\" src=\"veripro.js\"></script>

</head>
<body style=\"background-color:White;overflow:auto;margin-top:0;\" onload=\"if('$firstload'== 1){opener.autoclose();}\">

	<div style=\"float:left;width:900px;height:140px;background-image:url(images/header1024.jpg);background-repeat:no-repeat;\">
		<div id=\"nav\" style=\"z-index:2;\">
		
			<ul class=\"level1\">
				<li style=\"background-color:Black;\"><a href=\"veripro.php\">Home</a></li>
				<li class=\"submenu\" style=\"background-color:Black;\"><a href=\"#\">Crew</a>
					<ul class=\"level2\">
					   <li><a href=\"#\" onclick=\"window.open('crewchangeplan.php','crewchange',
								'scrollbars=yes,resizable=yes,status=yes,channelmode=yes');\">Crew Change Plan</a></li>
					   <li><a href=\"crewreports.php\">Reports</a></li>
				  	</ul>
				</li>
				<li class=\"submenu\" style=\"background-color:Black;\"><a href=\"#\">Training</a>
					<ul class=\"level2\">
					   <li><a href=\"#\">Certificates</a></li>
					   <li class=\"submenu\"><a href=\"#\">Enrollment</a>
						   <ul class=\"level3\">
							   <li><a href=\"#\">Available Slots</a></li>
							   <li><a href=\"#\">Schedules</a></li>
						   </ul>
					   </li>
				  	</ul>
				<li class=\"submenu\" style=\"background-color:Black;\"><a href=\"#\">System</a>
					<ul class=\"level2\">
					   <li><a href=\"#\">Setup</a>
					   		<ul class=\"level3\">
					   			<li><a href=\"#\">1234</a></li>
					   		</ul>
					   </li>
					</ul>
				</li>
				<li style=\"background-color:Black;\"><a href=\"crewsetup.php\">Setup</a></li>
			</ul>
		
		</div>
	</div>
";
?>