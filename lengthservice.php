<?php
include("veritas/connectdb.php");

//if(isset($_GET["rankcode"]))
//	$rankcode=$_GET["rankcode"];
	
//get rank
$qryranklist=mysql_query("SELECT RANK,RANKCODE FROM rank WHERE STATUS=1 ORDER BY RANKING") or die(mysql_error());

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

<form name=\"lengthservice\" method=\"POST\">
	
	<center>
	<div style=\"width:100%;height:500px;padding:5px 20px 0 20px;overflow:hidden;\">
		<br />
		<span class=\"sectiontitle\">REPORTS - Length of Service</span>
		
		<br />
		<span class=\"subsectiontitle\">Rank</span>
		<br /><br />
		<input type=\"radio\" name=\"crewstatus\" checked=\"checked\" onclick=\"crewstatushidden.value=this.value\" value=\"Onboard\">&nbsp;On-board
		<input type=\"radio\" name=\"crewstatus\" onclick=\"crewstatushidden.value=this.value\" value=\"Standby\">&nbsp;Standby
		<br>
		<br>Rank&nbsp;&nbsp;
		<select name=\"rankcode\">
			<option value=\"\">-All-</option>
			<option value=\"M\">-Management-</option>
			<option value=\"O\">-Operation-</option>
			<option value=\"S\">-Support-</option>";
			while($rowranklist=mysql_fetch_array($qryranklist))
			{
				$rank=$rowranklist['RANK'];
				$rankcode=$rowranklist['RANKCODE'];
				echo "<option value=\"$rankcode\">$rank</option>\n";
			}
		echo "
		</select>&nbsp;&nbsp;
		<br><br>
		<input type=\"button\" value=\"View\" onclick=\"if(crewstatushidden.value==''){alert('temp only')}else{
			openWindow('reports/replengthservice.php?crewstatus='+crewstatushidden.value+'&rankcode='+rankcode.value, 'replengthservice' ,'850px', '750px')};\" />
		<br />
	</div>
	<input type=\"hidden\" name=\"crewstatushidden\" value=\"Onboard\">
</form>

</body>
</html>
";

?>

