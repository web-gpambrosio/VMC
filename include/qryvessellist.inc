<?php
if(isset($_POST["vesselcode"]))
	$vesselcode=$_POST["vesselcode"];

//FILL VESSEL FOR SELECT BOX

if ($divcode != "")
{
	$qryvessellist=mysql_query("SELECT v.VESSELCODE,VESSEL,v.VESSELTYPECODE
		FROM vessel v
		WHERE DIVCODE=$divcode AND STATUS=1
		ORDER BY VESSEL") or die(mysql_error());
}
else 
{
	$qryvessellist=mysql_query("SELECT v.VESSELCODE,VESSEL,v.VESSELTYPECODE
		FROM vessel v
		WHERE STATUS=1
		ORDER BY VESSEL") or die(mysql_error());
}
?>