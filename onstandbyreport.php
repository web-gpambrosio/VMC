<?php
include('veritas/connectdb.php');

if(isset($_POST['divcode']))
	$divcode=$_POST['divcode'];
	
//get rank
$qryranklist=mysql_query("SELECT RANK,RANKCODE FROM rank WHERE STATUS=1 ORDER BY RANKING") or die(mysql_error());

//get divcode
$qrydivcode=mysql_query("SELECT DIVCODE FROM division ORDER BY DIVCODE") or die(mysql_error());

//get vessel
$visiblefleetno="display:none;";
if(!empty($divcode))
{
	$qryfleetlist=mysql_query("SELECT DISTINCT FLEETNO
		FROM vessel v
		WHERE STATUS=1 AND DIVCODE=$divcode AND FLEETNO IS NOT NULL 
		ORDER BY FLEETNO") or die(mysql_error());
	$visiblefleetno="";
}

echo "
<html>
<head>
<link rel=\"stylesheet\" type=\"text/css\" href=\"veripro.css\">
<script language=\"javascript\" src=\"veripro.js\"></script>

</head>
<body>

<form name=\"onstandbyrank\" method=\"POST\">
	
	<center>
	<div style=\"width:100%;height:500px;padding:5px 20px 0 20px;overflow:hidden;\">
		<br />
		<span class=\"sectiontitle\">REPORTS - Crew on Standby</span>

		<br />
		<span class=\"subsectiontitle\">Select Division</span>
		<br /><br />
		<select name=\"divcode\" onchange=\"onstandbyrank.submit();\">
			<option value=\"\">-Select-</option>";
			while($rowdivcode=mysql_fetch_array($qrydivcode))
			{
				$divcode1=$rowdivcode['DIVCODE'];
				if($divcode1==$divcode)
					$selected="selected";
				else 
					$selected="";
				echo "<option $selected value=\"$divcode1\">Div-$divcode1</option>\n";
			}
		echo "
		</select>
		
		<select name=\"fleetno\" onchange=\"\" style=\"$visiblefleetno\">
			<option value=\"\">-All Fleets-</option>";
			while($rowfleetlist=mysql_fetch_array($qryfleetlist))
			{
				$fleetno1=$rowfleetlist['FLEETNO'];
				if($fleetno1==$fleetno && !empty($fleetno1))
					$selected="selected";
				else 
					$selected="";
				echo "<option $selected value=\"$fleetno1\">$fleetno1</option>\n";
			}
		echo "
		</select>
		<br />

		<br />
		<span class=\"subsectiontitle\">By Rank</span>
		<br />
		<br />
		<select name=\"rankcode\">
			<option value=\"All\">-All-</option>
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
		<input type=\"button\" value=\"View\" onclick=\"openWindow('reports/reponstandbybyrank.php?rankcode='+rankcode.value+'&divcode='+divcode.value+'&fleetno='+fleetno.value, 'rep23' ,'950px', '750px');\" />
		<br />
		<br />
		<br />
		<br />
		<br />		
		
		<input type=\"button\" value=\"Generate Alphabetical Listing\" onclick=\"openWindow('reports/reponboardstandbyalpha.php?reptype=0', 'repstandby' , '700px', '750px');\"/>
	</div>

</form>

</body>
</html>
";

?>

