<?php

include("veritas/connectdb.php");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
		
$formname = "formvesselspecs";
$formtitle = "VESSEL SPECIFICATION SETUP";

$checkgenturbo = "";
$checkmarisave = "";
$checkums = "";

//POSTS

if(isset($_POST['vesselcode']))
	$vesselcode=$_POST['vesselcode'];

if(isset($_POST['enginemain']))
	$enginemain=$_POST['enginemain'];

if(isset($_POST['enginetypecode']))
	$enginetypecode=$_POST['enginetypecode'];

if(isset($_POST['maker']))
	$maker=$_POST['maker'];

if(isset($_POST['model']))
	$model=$_POST['model'];

if(isset($_POST['enginepower']))
	$enginepower=$_POST['enginepower'];

if(isset($_POST['rpm']))
	$rpm=$_POST['rpm'];

if(isset($_POST['genengine']))
	$genengine=$_POST['genengine'];

if(isset($_POST['genmaker']))
	$genmaker=$_POST['genmaker'];

if(isset($_POST['genmodel']))
	$genmodel=$_POST['genmodel'];

if(isset($_POST['genpower']))
	$genpower=$_POST['genpower'];

if(isset($_POST['genrpm']))
	$genrpm=$_POST['genrpm'];
	
if(isset($_POST['genturbo']))
{
	$genturbo=1;
	$checkgenturbo = "checked=\"checked\"";
}
else 
	$genturbo=0;

if(isset($_POST['purifiermodel']))
	$purifiermodel=$_POST['purifiermodel'];

if(isset($_POST['aircompressor']))
	$aircompressor=$_POST['aircompressor'];

if(isset($_POST['fwgenerator']))
	$fwgenerator=$_POST['fwgenerator'];

if(isset($_POST['marisave']))
{
	$marisave=1;
	$checkmarisave = "checked=\"checked\"";
}
else 
	$marisave=0;
	
if(isset($_POST['ums']))
{
	$ums=1;
	$checkums = "checked=\"checked\"";
}
else 
	$ums=0;

if(isset($_POST['inmarsatno']))
	$inmarsatno=$_POST['inmarsatno'];

if(isset($_POST['gmdssno']))
	$gmdssno=$_POST['gmdssno'];

if(isset($_POST['faxno']))
	$faxno=$_POST['faxno'];

if(isset($_POST['imono']))
	$imono=$_POST['imono'];

if(isset($_POST['vesselid']))
	$vesselid=$_POST['vesselid'];


switch ($actiontxt)
{
	case "save"		:
		
			$qryvesselspecssave = mysql_query("UPDATE vesselspecs SET ENGINEMAIN='$enginemain',
																	ENGINETYPECODE='$enginetypecode',
																	MAKER='$maker',
																	MODEL='$model',
																	ENGINEPOWER='$enginepower',
																	RPM='$rpm',
																	GENENGINE='$genengine',
																	GENMAKER='$genmaker',
																	GENMODEL='$genmodel',
																	GENPOWER='$genpower',
																	GENRPM='$genrpm',
																	GENTURBO=$genturbo,
																	PURIFIERMODEL='$purifiermodel',
																	AIRCOMPRESSOR='$aircompressor',
																	FWGENERATOR='$fwgenerator',
																	MARISAVE=$marisave,
																	UMS=$ums,
																	INMARSATNO='$inmarsatno',
																	GMDSSNO='$gmdssno',
																	FAXNO='$faxno',
																	IMONO='$imono',
																	VESSELID='$vesselid',
																	MADEBY='$employeeid',
																	MADEDATE='$currentdate'
															WHERE VESSELCODE='$vesselcode'
											") or die(mysql_error());

			$vesselcode = "";
			$enginemain = "";
			$enginetypecode = "";
			$maker = "";
			$model = "";
			$enginepower = "";
			$rpm = "";
			$genengine = "";
			$genmaker = "";
			$genmodel = "";
			$genpower = "";
			$genrpm = "";
			$genturbo = 0;
			$purifiermodel = "";
			$aircompressor = "";
			$fwgenerator = "";
			$marisave = 0;
			$ums = 0;
			$inmarsatno = "";
			$gmdssno = "";
			$faxno = "";
			$imono = "";
			$vesselid = "";
			
		break;
		
	case "cancel"	:
		
			$vesselcode = "";
			$enginemain = "";
			$enginetypecode = "";
			$maker = "";
			$model = "";
			$enginepower = "";
			$rpm = "";
			$genengine = "";
			$genmaker = "";
			$genmodel = "";
			$genpower = "";
			$genrpm = "";
			$genturbo = 0;
			$purifiermodel = "";
			$aircompressor = "";
			$fwgenerator = "";
			$marisave = 0;
			$ums = 0;
			$inmarsatno = "";
			$gmdssno = "";
			$faxno = "";
			$imono = "";
			$vesselid = "";

		break;
		
	case "search"	:
		
			$qryvesselsearch = mysql_query("SELECT * FROM vesselspecs WHERE VESSELCODE='$vesselcode'") or die(mysql_error());
			
			if (mysql_num_rows($qryvesselsearch) == 1)
			{
				$rowvesselspecs = mysql_fetch_array($qryvesselsearch);
				
				$vesselcode = $rowvesselspecs["VESSELCODE"];
				$enginemain = $rowvesselspecs["ENGINEMAIN"];
				$enginetypecode = $rowvesselspecs["ENGINETYPECODE"];
				$maker = $rowvesselspecs["MAKER"];
				$model = $rowvesselspecs["MODEL"];
				$enginepower = $rowvesselspecs["ENGINEPOWER"];
				$rpm = $rowvesselspecs["RPM"];
				$genengine = $rowvesselspecs["GENENGINE"];
				$genmaker = $rowvesselspecs["GENMAKER"];
				$genmodel = $rowvesselspecs["GENMODEL"];
				$genpower = $rowvesselspecs["GENPOWER"];
				$genrpm = $rowvesselspecs["GENRPM"];
				
				if ($rowvesselspecs["GENTURBO"])
				{
					$genturbo = 1;
					$checkgenturbo = "checked=\"checked\"";
				}
				else 
				{
					$genturbo = 0;
					$checkgenturbo = "";
				}
				
				$purifiermodel = $rowvesselspecs["PURIFIERMODEL"];
				$aircompressor = $rowvesselspecs["AIRCOMPRESSOR"];
				$fwgenerator = $rowvesselspecs["FWGENERATOR"];
				
				if ($rowvesselspecs["MARISAVE"])
				{
					$marisave = 1;
					$checkmarisave = "checked=\"checked\"";
				}
				else 
				{
					$marisave = 0;
					$checkmarisave = "";
				}
				
				if ($rowvesselspecs["UMS"])
				{
					$ums = 1;
					$checkums = "checked=\"checked\"";
				}
				else 
				{
					$ums = 0;
					$checkums = "";
				}
				
				$inmarsatno = $rowvesselspecs["INMARSATNO"];
				$gmdssno = $rowvesselspecs["GMDSSNO"];
				$faxno = $rowvesselspecs["FAXNO"];
				$imono = $rowvesselspecs["IMONO"];
				$vesselid = $rowvesselspecs["VESSELID"];
			}

		break;
}
	


/* LISTINGS  */

$qryvesselsel = mysql_query("SELECT VESSELCODE,VESSEL FROM vessel WHERE STATUS=1 ORDER BY VESSEL") or die(mysql_error());

	$select1 = "<option selected value=\"\">--Select One--</option>";
	while($rowvesselsel = mysql_fetch_array($qryvesselsel))
	{
		$vslcode = $rowvesselsel["VESSELCODE"];
		$vsl = $rowvesselsel["VESSEL"];
		
		$selected1 = "";
		
		if ($vesselcode == $vslcode)
			$selected1 = "SELECTED";
			
		$select1 .= "<option $selected1 value=\"$vslcode\">$vsl</option>";
	}

$qryenginetypesel = mysql_query("SELECT ENGINETYPECODE,ENGINETYPE FROM enginetype") or die(mysql_error());

	$select2 = "<option selected value=\"\">--Select One--</option>";
	while($rowenginetypesel = mysql_fetch_array($qryenginetypesel))
	{
		$engtypecode = $rowenginetypesel["ENGINETYPECODE"];
		$engtype = $rowenginetypesel["ENGINETYPE"];
		
		$selected1 = "";
		
		if ($enginetypecode == $engtypecode)
			$selected1 = "SELECTED";
			
		$select2 .= "<option $selected1 value=\"$engtypecode\">$engtype</option>";
	}


$qryvesselspecslist = mysql_query("SELECT vs.VESSELCODE,v.VESSEL,vs.ENGINEMAIN,e.ENGINETYPE,vs.ENGINETYPECODE,
									vs.MAKER,vs.MODEL,vs.MADEBY,vs.MADEDATE
									FROM vesselspecs vs
									LEFT JOIN vessel v ON v.VESSELCODE=vs.VESSELCODE
									LEFT JOIN enginetype e ON e.ENGINETYPECODE=vs.ENGINETYPECODE
									WHERE v.STATUS=1
									") or die(mysql_error());
	
/*END OF LISTINGS*/


echo "
<html>
<head>
<link rel=\"stylesheet\" type=\"text/css\" href=\"../veripro.css\">
<script language=\"javascript\" src=\"../veripro.js\"></script>

<script>

function checksave(x)
{
	var rem = '';
	
	with ($formname)
	{

	}
			
	if(rem=='')
	{
		$formname.submit();
	}
	else
		alert('Please CHECK the following: ' + rem + ' before saving!');		

}
	
</script>
</head>
<body>

<form name=\"$formname\" method=\"POST\">
	
	<center>
	<div style=\"width:100%;height:500px;padding:5px 20px 0 20px;\">
			
		<div style=\"width:100%;height:700px;float:right;padding:5px 20px 0 20px;\">
			<br />
			<span class=\"sectiontitle\">$formtitle</span>
			<br />
			
			<table class=\"setup\" width=\"100%\" >
				<tr>
					<th>Vessel</th>
					<th>:</th>
					<th><select name=\"vesselcode\" onchange=\"actiontxt.value='search';$formname.submit();\">
							$select1
						</select>
					</th>
				</tr>
				<tr>
					<th>Main Engine</th>
					<th>:</th>
					<th><input type=\"text\" name=\"enginemain\" value=\"$enginemain\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Engine Type</th>
					<th>:</th>
					<th><select name=\"enginetypecode\">
							$select2
						</select>
					</th>
				</tr>
				<tr>
					<th>Maker</th>
					<th>:</th>
					<th><input type=\"text\" name=\"maker\" value=\"$maker\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Model</th>
					<th>:</th>
					<th><input type=\"text\" name=\"model\" value=\"$model\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Engine Power</th>
					<th>:</th>
					<th><input type=\"text\" name=\"enginepower\" value=\"$enginepower\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>RPM</th>
					<th>:</th>
					<th><input type=\"text\" name=\"rpm\" value=\"$rpm\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Gen. Engine</th>
					<th>:</th>
					<th><input type=\"text\" name=\"genengine\" value=\"$genengine\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Gen. Maker</th>
					<th>:</th>
					<th><input type=\"text\" name=\"genmaker\" value=\"$genmaker\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Gen. Model</th>
					<th>:</th>
					<th><input type=\"text\" name=\"genmodel\" value=\"$genmodel\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Gen. Power</th>
					<th>:</th>
					<th><input type=\"text\" name=\"genpower\" value=\"$genpower\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Gen. RPM</th>
					<th>:</th>
					<th><input type=\"text\" name=\"genrpm\" value=\"$genrpm\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Gen. Turbo?</th>
					<th>:</th>
					<th>
						<input type=\"checkbox\" name=\"genturbo\" $genturbo />
					</th>
				</tr>
				<tr>
					<th>Purifier Model</th>
					<th>:</th>
					<th><input type=\"text\" name=\"purifiermodel\" value=\"$purifiermodel\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Air Compressor</th>
					<th>:</th>
					<th><input type=\"text\" name=\"aircompressor\" value=\"$aircompressor\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>FW Generator</th>
					<th>:</th>
					<th><input type=\"text\" name=\"fwgenerator\" value=\"$fwgenerator\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Marisave?</th>
					<th>:</th>
					<th>
						<input type=\"checkbox\" name=\"marisave\" $marisave />
					</th>
				</tr>
				<tr>
					<th>UMS?</th>
					<th>:</th>
					<th>
						<input type=\"checkbox\" name=\"ums\" $ums />
					</th>
				</tr>
				<tr>
					<th>INMARSAT No.</th>
					<th>:</th>
					<th><input type=\"text\" name=\"inmarsatno\" value=\"$inmarsatno\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>GMDSS No.</th>
					<th>:</th>
					<th><input type=\"text\" name=\"gmdssno\" value=\"$gmdssno\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>FAX No.</th>
					<th>:</th>
					<th><input type=\"text\" name=\"faxno\" value=\"$faxno\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>IMO No.</th>
					<th>:</th>
					<th><input type=\"text\" name=\"imono\" value=\"$imono\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>Vessel ID</th>
					<th>:</th>
					<th><input type=\"text\" name=\"vesselid\" value=\"$vesselid\" size=\"30\" onKeyPress=\"return alphanumericonly(this);\"
							 onkeyup=\"this.value=this.value.toUpperCase()\" />
					</th>
				</tr>
				<tr>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
					<th><input type=\"button\" name=\"\" value=\"Save\" onclick=\"actiontxt.value='save';checksave();\" />
						<input type=\"button\" name=\"\" value=\"Cancel\" onclick=\"actiontxt.value='cancel';$formname.submit();\" />
					</th>
				</tr>			
			</table>
		</div>
		
		<div style=\"width:100%;height:350px;padding:5px 20px 0 20px;overflow:hidden;\">
			<br />
			<span class=\"sectiontitle\">CURRENT LISTING</span>
			<br />
			
			<div style=\"width:100%;height:250px;padding:0;overflow:auto;\">
				<table width=\"100%\" class=\"listcol\">
					<tr>
						<th>VESSEL</th>
						<th>ENGINETYPE</th>
						<th>MAKER</th>
						<th>MODEL</th>
						<th>MADE BY</th>
						<th>MADE DATE</th>
					</tr>
	";
				while ($rowvesselspecslist=mysql_fetch_array($qryvesselspecslist))
				{
					$list1 = $rowvesselspecslist["VESSELCODE"];
					$list2 = $rowvesselspecslist["VESSEL"];
					$list3 = $rowvesselspecslist["ENGINETYPE"];
					$list4 = $rowvesselspecslist["MAKER"];
					$list5 = $rowvesselspecslist["MODEL"];
					$list6 = $rowvesselspecslist["MADEBY"];
					$list7 = date('m-d-Y',strtotime($rowvesselspecslist["MADEDATE"]));
					
					echo "
					<tr ondblclick=\"document.$formname.vesselcode.value='$list1';actiontxt.value='search';$formname.submit();\">
						
						<td>$list2</td>
						<td>$list3</td>
						<td>$list4</td>
						<td>$list5</td>
						<td>$list6</td>
						<td>$list7</td>
					</tr>
	";
				}
	echo "
				</table>
			</div>
		</div>
		
	</div>
	</center>
	
	<input type=\"hidden\" name=\"actiontxt\" />
</form>

</body>
</html>
";

?>

