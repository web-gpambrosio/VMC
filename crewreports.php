<?php

//include("veritas/header.php");
include("header.php");


echo "

	<div style=\"width:900px;height:440px;border-bottom:0;overflow:auto;padding:0;\">
		
		<div style=\"width:15%;height:100%;float:left;\">
		
		</div>
		
		<div style=\"width:85%;height:100%;float:right;padding:5px;\">
		
			<b>Crew Reports Screen</b>
			<hr><br />
			
			<a href=\"javascript:openWindow('repcrewrotation.php?divcode=1', 'div1', 1024, 768);\">Crew Rotation - Div 1</a><br />
			<a href=\"javascript:openWindow('repcrewrotation.php?divcode=2', 'div2', 1024, 768);\">Crew Rotation - Div 2</a>
			
			
		</div>
		
	</div>

";



//include("veritas/footer.php");
include("footer.php");

?>

