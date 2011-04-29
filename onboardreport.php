<?php
include('veritas/connectdb.php');

if(isset($_POST['divcode']))
	$divcode=$_POST['divcode'];
	
if(isset($_POST['dateonboard']))
	$dateonboard=$_POST['dateonboard'];
	
//get divcode
$qrydivcode=mysql_query("SELECT DIVCODE FROM division ORDER BY DIVCODE") or die(mysql_error());

//get rank
$qryranklist=mysql_query("SELECT RANK,RANKCODE FROM rank WHERE STATUS=1 ORDER BY RANKING") or die(mysql_error());

//get vessel
$visiblefleetno="display:none;";
if(!empty($divcode))
{
	$qryfleetlist=mysql_query("SELECT DISTINCT FLEETNO
		FROM vessel v
		WHERE STATUS=1 AND DIVCODE=$divcode AND FLEETNO IS NOT NULL 
		ORDER BY FLEETNO") or die(mysql_error());
	$visiblefleetno="";
	$addwheredivcode="AND DIVCODE=$divcode";
}

$qryvessellist=mysql_query("SELECT VESSEL,VESSELCODE
		FROM vessel v
		WHERE STATUS=1 $addwheredivcode ORDER BY VESSEL") or die(mysql_error());

echo "
<html>
<head>
<link rel=\"stylesheet\" type=\"text/css\" href=\"veripro.css\">
<script language=\"javascript\" src=\"popcalendar.js\"></script>
<script language=\"javascript\" src=\"veripro.js\"></script>

</head>
<body>

<form name=\"onboardbyrank\" method=\"POST\">
	
	<center>
	<div style=\"width:100%;height:500px;padding:5px 20px 0 20px;overflow:hidden;\">
		<br />
		<span class=\"sectiontitle\">REPORTS - Crew Onboard</span>
		<br />
		<span class=\"subsectiontitle\">Select Division</span>
		<br />
		<br />
		<nobr>
		<select name=\"divcode\" onchange=\"onboardbyrank.submit();\">
			<option value=\"\">-All Divisions-</option>";
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
		</select>&nbsp;&nbsp;
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
		</nobr>
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
		<input type=\"button\" value=\"View\" onclick=\"openWindow('reports/reponboardbyrank.php?divcode='+divcode.value+'&rankcode='+rankcode.value+'&fleetno='+fleetno.value, 'rep1' , '700px', '750px');\" />
		<br />
		<br />
		<span class=\"subsectiontitle\">By Vessel (if selected, Division will not be considered)</span>
		<br />
		<br />
		<select name=\"vesselcode\">
			<option value=\"\">-Select-</option>";
			if($qryvessellist)
			{
				while($rowvessellist=mysql_fetch_array($qryvessellist))
				{
					$vessel=$rowvessellist['VESSEL'];
					$vesselcode=$rowvessellist['VESSELCODE'];
					echo "<option value=\"$vesselcode\">$vessel</option>\n";
				}
			}
		echo "
		</select>&nbsp;&nbsp;
		<input type=\"button\" value=\"View\" 
			onclick=\"if(vesselcode.value!=''){openWindow('reports/reponboardbyvessel.php?divcode='+divcode.value+'&vesselcode='+vesselcode.value, 'rep2' , '700px', '750px');}
						else{alert('Select Vessel!')}\" />
		<br />
		<br />
		<br />
		<br />
		<span class=\"subsectiontitle\">HISTORY</span>
		<br />
		<br />
		Date Onboard&nbsp;
		<input type=\"text\" name=\"dateonboard\" value=\"$dateonboard\" onKeyPress=\"return dateonly(this);\" size=\"10\" 
			maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
		<img src=\"calendaricon.gif\" width=\"17\" height=\"17\" border=\"0\" 
			onclick=\"popUpCalendar(dateonboard, dateonboard, 'mm/dd/yyyy', 0, 0);return false;\">
		&nbsp;&nbsp;&nbsp;(mm/dd/yy)&nbsp;&nbsp;
		<input type=\"button\" value=\"View\" 
			onclick=\"if(dateonboard.value!='')
						{
						if(vesselcode.value!='')
							{openWindow('reports/reponboardbyvessel.php?divcode='+divcode.value+'&vesselcode='+vesselcode.value+'&dateonboard='+dateonboard.value, 'rep2' , '700px', '750px');}
						else
							{openWindow('reports/reponboardbyrank.php?divcode='+divcode.value+'&rankcode='+rankcode.value+'&fleetno='+fleetno.value+'&dateonboard='+dateonboard.value, 'rep1' , '700px', '750px');}
						}
						else
						{alert('Input Date Onboard!')}\"/>
		<br />
		<br />
		<br />
		
		<input type=\"button\" value=\"Generate Alphabetical Listing\" onclick=\"openWindow('reports/reponboardstandbyalpha.php?reptype=1', 'reponboard' , '700px', '750px');\"/>
		<br />	
		<br />	
		<input type=\"button\" value=\"Crew Over 9 Months\" onclick=\"openWindow('reports/reponboardstandbyalpha.php?reptype=2', 'reponboard' , '700px', '750px');\"/>
	</div>

</form>

</body>
</html>
";

?>

