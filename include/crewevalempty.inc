<?php

function filterentry($entry)
{
	$newentry = str_replace("'","\'",$entry);
	return $newentry;
}


if (empty($vpc1))
	$vpc1 = "NULL";
	
if (empty($vpc2))
	$vpc2 = "NULL";

if (empty($vpc3))
	$vpc3 = "NULL";

if (empty($vpc4))
	$vpc4 = "NULL";

if (empty($vpc5))
	$vpc5 = "NULL";

if (empty($vpc6))
	$vpc6 = "NULL";

if (empty($vpc1rem))
	$vpc1rem = "NULL";
else 
	$vpc1rem = "'" . filterentry($vpc1rem) . "'";

if (empty($vpc2rem))
	$vpc2rem = "NULL";
else 
	$vpc2rem = "'" . filterentry($vpc2rem) . "'";

if (empty($vpc3rem))
	$vpc3rem = "NULL";
else 
	$vpc3rem = "'" . filterentry($vpc3rem) . "'";

if (empty($vpc4rem))
	$vpc4rem = "NULL";
else 
	$vpc4rem = "'" . filterentry($vpc4rem) . "'";

if (empty($vpc5rem))
	$vpc5rem = "NULL";
else 
	$vpc5rem = "'" . filterentry($vpc5rem) . "'";

if (empty($vpc6rem))
	$vpc6rem = "NULL";
else 
	$vpc6rem = "'" . filterentry($vpc6rem) . "'";

	
	
if (empty($vee1))
	$vee1 = "NULL";

if (empty($vee1rem))
	$vee1rem = "NULL";
else 
	$vee1rem = "'" . filterentry($vee1rem) . "'";

if (empty($fleetkept))
	$fleetkept = "NULL";
else 
	$fleetkept = "'$fleetkept'";

if (empty($vep0))
	$vep0 = "NULL";

if (empty($vep0rem))
	$vep0rem = "NULL";
else 
	$vep0rem = "'" . filterentry($vep0rem) . "'";

if (empty($nextrank))
	$nextrank = "NULL";
else 
	$nextrank = "'$nextrank'";

if (empty($vep1))
	$vep1 = "NULL";

if (empty($vep2))
	$vep2 = "NULL";

if (empty($vep3))
	$vep3 = "NULL";

if (empty($vep4))
	$vep4 = "NULL";

if (empty($negdiscussed))
	$negdiscussed = "NULL";

if (empty($var1))
{
	$var1 = "NULL";
	$allowsave = 0;
}

if (empty($var2))
{
	$var2 = "NULL";
	$allowsave = 0;
}

if (empty($var3))
{
	$var3 = "NULL";
	$allowsave = 0;
}

if (empty($var4))
{
	$var4 = "NULL";
	$allowsave = 0;
}

if (empty($vpj1))
{
	$vpj1 = "NULL";
	$allowsave = 0;
}

if (empty($vpj2))
{
	$vpj2 = "NULL";
	$allowsave = 0;
}

if (empty($vpj3))
{
	$vpj3 = "NULL";
	$allowsave = 0;
}

if (empty($vpj4))
{
	$vpj4 = "NULL";
	$allowsave = 0;
}

if (empty($vsm1))
{
	$vsm1 = "NULL";
	$allowsave = 0;
}

if (empty($vsm2))
{
	$vsm2 = "NULL";
	$allowsave = 0;
}

if (empty($vsm3))
{
	$vsm3 = "NULL";
	$allowsave = 0;
}

if (empty($vpr1))
{
	$vpr1 = "NULL";
	$allowsave = 0;
}

if (empty($vpr2))
{
	$vpr2 = "NULL";
	$allowsave = 0;
}

if (empty($vpr3))
{
	$vpr3 = "NULL";
	$allowsave = 0;
}

if (empty($vbe1))
{
	$vbe1 = "NULL";
	$allowsave = 0;
}

if (empty($vbe2))
{
	$vbe2 = "NULL";
	$allowsave = 0;
}

if (empty($vbe3))
{
	$vbe3 = "NULL";
	$allowsave = 0;
}

if (empty($vsc1))
	$vsc1 = "NULL";

if (empty($vsc2))
	$vsc2 = "NULL";

if (empty($vsc3))
	$vsc3 = "NULL";

if (empty($captname1))
	$captname1 = "NULL";
else 
	$captname1 = "'$captname1'";

if (empty($date1))
	$date1 = "NULL";
else 
{
	$date1 = date("Y-m-d",strtotime($date1));
	$date1 = "'$date1'";
}

if (empty($captname2))
	$captname2 = "NULL";
else 
	$captname2 = "'$captname2'";

if (empty($date2))
	$date2 = "NULL";
else 
{
	$date2 = date("Y-m-d",strtotime($date2));
	$date2 = "'$date2'";
}

if (empty($principalcomment))
	$principalcomment = "NULL";
else 
	$principalcomment = "'" . filterentry($principalcomment) . "'";

if (empty($vmccomment))
	$vmccomment = "NULL";
else 
	$vmccomment = "'" . filterentry($vmccomment) . "'";

if (empty($extratrain))
	$extratrain = "NULL";

if (empty($repextratrain))
	$repextratrain = "NULL";

if (empty($repextratrainrem))
	$repextratrainrem = "NULL";
else 
	$repextratrainrem = "'" . filterentry($repextratrainrem) . "'";

if (empty($remarks))
	$remarks = "NULL";
else 
	$remarks = "'" . filterentry($remarks) . "'";

if (empty($newtraincode1))
	$newtraincode1 = "NULL";
else 
	$newtraincode1 = "'$newtraincode1'";

if (empty($newtrain1))
	$newtrain1 = "NULL";
else 
	$newtrain1 = "'$newtrain1'";

if (empty($newtrainoth1))
	$newtrainoth1 = "NULL";
else 
	$newtrainoth1 = "'" . filterentry($newtrainoth1) . "'";

if (empty($newtraincode2))
	$newtraincode2 = "NULL";
else 
	$newtraincode2 = "'$newtraincode2'";

if (empty($newtrain2))
	$newtrain2 = "NULL";
else 
	$newtrain2 = "'$newtrain2'";

if (empty($newtrainoth2))
	$newtrainoth2 = "NULL";
else 
	$newtrainoth2 = "'" . filterentry($newtrainoth2) . "'";

if (empty($newtraincode3))
	$newtraincode3 = "NULL";
else 
	$newtraincode3 = "'$newtraincode3'";

if (empty($newtrain3))
	$newtrain3 = "NULL";
else 
	$newtrain3 = "'$newtrain3'";

if (empty($newtrainoth3))
	$newtrainoth3 = "NULL";
else 
	$newtrainoth3 = "'" . filterentry($newtrainoth3) . "'";

if (empty($efe1))
	$efe1 = "NULL";

if (empty($efeby1))
	$efeby1 = "NULL";
else 
	$efeby1 = "'$efeby1'";

if (empty($efedate1))
	$efedate1 = "NULL";
else 
	$efedate1 = "'$efedate1'";

if (empty($efe2))
	$efe2 = "NULL";

if (empty($efeby2))
	$efeby2 = "NULL";
else 
	$efeby2 = "'$efeby2'";

if (empty($efedate2))
	$efedate2 = "NULL";
else 
	$efedate2 = "'$efedate2'";

if (empty($efe3))
	$efe3 = "NULL";

if (empty($efeby3))
	$efeby3 = "NULL";
else 
	$efeby3 = "'$efeby3'";

if (empty($efedate3))
	$efedate3 = "NULL";
else 
	$efedate3 = "'$efedate3'";

if (empty($epr1))
	$epr1 = "NULL";

if (empty($eprby1))
	$eprby1 = "NULL";
else 
	$eprby1 = "'$eprby1'";

if (empty($eprdate1))
	$eprdate1 = "NULL";
else 
	$eprdate1 = "'$eprdate1'";

if (empty($epr2))
	$epr2 = "NULL";

if (empty($eprby2))
	$eprby2 = "NULL";
else 
	$eprby2 = "'$eprby2'";

if (empty($eprdate2))
	$eprdate2 = "NULL";
else 
	$eprdate2 = "'$eprdate2'";

if (empty($epr3))
	$epr3 = "NULL";

if (empty($eprby3))
	$eprby3 = "NULL";
else 
	$eprby3 = "'$eprby3'";

if (empty($eprdate3))
	$eprdate3 = "NULL";
else 
	$eprdate3 = "'$eprdate3'";

if (empty($madeby))
	$madeby = "NULL";
else 
	$madeby = "'$madeby'";

if (empty($madedate))
	$madedate = "NULL";
else 
	$madedate = "'$madedate'";
?>