<?php
include('veritas/connectdb.php');

if(isset($_POST['divcode']))
	$divcode=$_POST['divcode'];
	
//get divcode
$qrydivcode=mysql_query("SELECT DIVCODE FROM division ORDER BY DIVCODE") or die(mysql_error());

//get rank
//$qryranklist=mysql_query("SELECT RANK,RANKCODE FROM rank WHERE STATUS=1 ORDER BY RANKING") or die(mysql_error());

//get vessel
//if(!empty($divcode))
//{
//	$qryvessellist=mysql_query("SELECT VESSEL,VESSELCODE
//		FROM vessel v
//		WHERE STATUS=1 AND DIVCODE=$divcode ORDER BY VESSEL") or die(mysql_error());
//}

echo "
<html>
<head>
<link rel=\"stylesheet\" type=\"text/css\" href=\"veripro.css\">
<script language=\"javascript\" src=\"veripro.js\"></script>

</head>
<body>

<form name=\"rotationreport\" method=\"POST\">
	
	<center>
	<div style=\"width:100%;height:500px;padding:5px 20px 0 20px;overflow:hidden;\">
		<br />
		<span class=\"sectiontitle\">REPORTS</span>
		<br />
		<span class=\"subsectiontitle\">Select Division</span>
		<br />
		<br />
		<select name=\"divcode\" onchange=\"onboardbyrank.submit();\">
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
		<input type=\"button\" value=\"View Rotation\" onclick=\"if(divcode.value){openWindow('reports/repcrewrotation.php?divcode='+divcode.value, 'rep1' ,0, 0);}else{alert('Select Division first');}\" />
	</div>

</form>

</body>
</html>
";

?>

