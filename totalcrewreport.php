<?php
include('veritas/connectdb.php');

if(isset($_POST['divcode']))
	$divcode=$_POST['divcode'];
	
//get principal
$qryprincipallist=mysql_query("SELECT PRINCIPAL,PRINCIPALCODE FROM principal WHERE STATUS=1 ORDER BY PRINCIPAL") or die(mysql_error());

//get vessel type
$qryvesseltypelist=mysql_query("SELECT VESSELTYPE,VESSELTYPECODE FROM vesseltype WHERE STATUS=1 ORDER BY VESSELTYPE") or die(mysql_error());

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

<form name=\"totalcrew\" method=\"POST\">
	
	<center>
	<div style=\"width:100%;height:500px;padding:5px 20px 0 20px;overflow:hidden;\">
		<br />
		<span class=\"sectiontitle\">REPORTS - Total Crew</span>
		
		<br />
		<span class=\"subsectiontitle\">Select Division / Fleet</span>
		<br /><br />
		<select name=\"divcode\" onchange=\"totalcrew.submit();\">
			<option value=\"\">-All-</option>";
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
		<span class=\"subsectiontitle\">By Principal</span>
		<br />
		<br />
		<select name=\"principalcode\">
			<option value=\"\">-All-</option>";
			while($rowprincipallist=mysql_fetch_array($qryprincipallist))
			{
				$principal=$rowprincipallist['PRINCIPAL'];
				$principalcode=$rowprincipallist['PRINCIPALCODE'];
				echo "<option value=\"$principalcode\">$principal</option>\n";
			}
		echo "
		</select>
		<br />
		
		<br />
		<span class=\"subsectiontitle\">By Vessel Type</span>
		<br />
		<br />
		<select name=\"vesseltypecode\">
			<option value=\"\">-All-</option>";
			while($rowvesseltypelist=mysql_fetch_array($qryvesseltypelist))
			{
				$vesseltype=$rowvesseltypelist['VESSELTYPE'];
				$vesseltypecode=$rowvesseltypelist['VESSELTYPECODE'];
				echo "<option value=\"$vesseltypecode\">$vesseltype</option>\n";
			}
		echo "
		</select>
		<br />
		<br />
		<input type=\"button\" value=\"View\" onclick=\"openWindow('reports/reptotalcrew.php?divcode='+divcode.value+'&fleetno='+fleetno.value+'&principalcode='+principalcode.value+'&vesseltypecode='+vesseltypecode.value, 'rep23' ,'950px', '700px');\" />
		<br />
	</div>

</form>

</body>
</html>
";

?>

