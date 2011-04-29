<?php
include("veritas/connectdb.php");

//if(isset($_GET["rankcode"]))
//	$rankcode=$_GET["rankcode"];
	
//get rank
$qrylist=mysql_query("SELECT RANK,RANKCODE FROM rank 
	WHERE RANKCODE IN ('D21','D22','D23','D32','E21','E22','E23','E31','E32')
	ORDER BY RANKCODE") or die(mysql_error());


echo "
<html>
<head>
<link rel=\"stylesheet\" type=\"text/css\" href=\"veripro.css\">
<script language=\"javascript\" src=\"veripro.js\"></script>
<script language=\"javascript\" src=\"popcalendar.js\"></script>

</head>
<body>

<form name=\"documentsexpiry\" method=\"POST\">
	
	<center>
	<div style=\"width:100%;height:500px;padding:5px 20px 0 20px;overflow:hidden;\">
		<br />
		<span class=\"sectiontitle\">REPORTS - Higher License</span>
		
		<br />
		<span class=\"subsectiontitle\">Rank</span>
		<br /><br />
			<select name=\"rankcode\">
				<option value=\"\">-Select-</option>";
				while($rowlist=mysql_fetch_array($qrylist))
				{
					$rank1=$rowlist["RANK"];
					$rankcode1=$rowlist["RANKCODE"];
					if($rankcode1==$rankcode)
						$selected="selected";
					else 
						$selected="";
					echo "<option $selected value=\"$rankcode1\">$rank1</option>\n";
				}
			echo "
			</select>&nbsp;&nbsp;

		
		<input type=\"button\" value=\"View\" onclick=\"if(rankcode.value==''){alert('Select Rank!')}else{
			openWindow('reports/rephigherlicense.php?rankcode='+rankcode.value, 'rephigherlic' ,'850px', '750px')};\" />
		<br />
	</div>

</form>

</body>
</html>
";

?>

