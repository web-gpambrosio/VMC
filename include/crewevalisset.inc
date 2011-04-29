<?php

if (isset($_POST["evaldate"]))
{
	$evaldateshow=$_POST["evaldate"];
	$evaldate = date("Y-m-d",strtotime($evaldateshow));
}
else 
	$evaldateshow = date("Y-m-d");

if (isset($_POST["vpc1"]))
	$vpc1 = $_POST["vpc1"];

if (isset($_POST["vpc2"]))
	$vpc2 = $_POST["vpc2"];

if (isset($_POST["vpc3"]))
	$vpc3 = $_POST["vpc3"];

if (isset($_POST["vpc4"]))
	$vpc4 = $_POST["vpc4"];

if (isset($_POST["vpc5"]))
	$vpc5 = $_POST["vpc5"];

if (isset($_POST["vpc6"]))
	$vpc6 = $_POST["vpc6"];

if (isset($_POST["vpc1rem"]))
	$vpc1rem = $_POST["vpc1rem"];

if (isset($_POST["vpc2rem"]))
	$vpc2rem = $_POST["vpc2rem"];

if (isset($_POST["vpc3rem"]))
	$vpc3rem = $_POST["vpc3rem"];

if (isset($_POST["vpc4rem"]))
	$vpc4rem = $_POST["vpc4rem"];

if (isset($_POST["vpc5rem"]))
	$vpc5rem = $_POST["vpc5rem"];

if (isset($_POST["vpc6rem"]))
	$vpc6rem = $_POST["vpc6rem"];

if (isset($_POST["vee1"]))
	$vee1 = $_POST["vee1"];

if (isset($_POST["vee1rem"]))
	$vee1rem = $_POST["vee1rem"];

if (isset($_POST["fleetkept"]))
	$fleetkept = $_POST["fleetkept"];

if (isset($_POST["vep0"]))
	$vep0 = $_POST["vep0"];

if (isset($_POST["vep0rem"]))
	$vep0rem = $_POST["vep0rem"];

if (isset($_POST["nextrank"]))
	$nextrank = $_POST["nextrank"];

if (isset($_POST["vep1"]))
	$vep1 = $_POST["vep1"];

if (isset($_POST["vep2"]))
	$vep2 = $_POST["vep2"];

if (isset($_POST["vep3"]))
	$vep3 = $_POST["vep3"];

if (isset($_POST["vep4"]))
	$vep4 = $_POST["vep4"];

if (isset($_POST["negdiscussed"]))
	$negdiscussed = $_POST["negdiscussed"];

if (isset($_POST["var1"]))
	$var1 = $_POST["var1"];

if (isset($_POST["var2"]))
	$var2 = $_POST["var2"];

if (isset($_POST["var3"]))
	$var3 = $_POST["var3"];

if (isset($_POST["var4"]))
	$var4 = $_POST["var4"];

if (isset($_POST["vpj1"]))
	$vpj1 = $_POST["vpj1"];

if (isset($_POST["vpj2"]))
	$vpj2 = $_POST["vpj2"];

if (isset($_POST["vpj3"]))
	$vpj3 = $_POST["vpj3"];

if (isset($_POST["vpj4"]))
	$vpj4 = $_POST["vpj4"];

if (isset($_POST["vsm1"]))
	$vsm1 = $_POST["vsm1"];

if (isset($_POST["vsm2"]))
	$vsm2 = $_POST["vsm2"];

if (isset($_POST["vsm3"]))
	$vsm3 = $_POST["vsm3"];

if (isset($_POST["vpr1"]))
	$vpr1 = $_POST["vpr1"];

if (isset($_POST["vpr2"]))
	$vpr2 = $_POST["vpr2"];

if (isset($_POST["vpr3"]))
	$vpr3 = $_POST["vpr3"];

if (isset($_POST["vbe1"]))
	$vbe1 = $_POST["vbe1"];

if (isset($_POST["vbe2"]))
	$vbe2 = $_POST["vbe2"];

if (isset($_POST["vbe3"]))
	$vbe3 = $_POST["vbe3"];

if (isset($_POST["vsc1"]))
	$vsc1 = $_POST["vsc1"];

if (isset($_POST["vsc2"]))
	$vsc2 = $_POST["vsc2"];

if (isset($_POST["vsc3"]))
	$vsc3 = $_POST["vsc3"];

if (isset($_POST["captname1"]))
	$captname1 = $_POST["captname1"];

if (isset($_POST["date1"]))
	$date1 = $_POST["date1"];

if (isset($_POST["captname2"]))
	$captname2 = $_POST["captname2"];

if (isset($_POST["date2"]))
	$date2 = $_POST["date2"];

if (isset($_POST["principalcomment"]))
	$principalcomment = $_POST["principalcomment"];

if (isset($_POST["vmccomment"]))
	$vmccomment = $_POST["vmccomment"];

if (isset($_POST["extratrain"]))
	$extratrain = $_POST["extratrain"];

if (isset($_POST["repextratrain"]))
	$repextratrain = $_POST["repextratrain"];

if (isset($_POST["repextratrainrem"]))
	$repextratrainrem = $_POST["repextratrainrem"];

if (isset($_POST["remarks"]))
	$remarks = $_POST["remarks"];

if (isset($_POST["newtraincode1"]))
	$newtraincode1 = $_POST["newtraincode1"];

if (isset($_POST["newtrain1"]))
	$newtrain1 = $_POST["newtrain1"];

if (isset($_POST["newtrainoth1"]))
	$newtrainoth1 = $_POST["newtrainoth1"];

if (isset($_POST["newtraincode2"]))
	$newtraincode2 = $_POST["newtraincode2"];

if (isset($_POST["newtrain2"]))
	$newtrain2 = $_POST["newtrain2"];

if (isset($_POST["newtrainoth2"]))
	$newtrainoth2 = $_POST["newtrainoth2"];

if (isset($_POST["newtraincode3"]))
	$newtraincode3 = $_POST["newtraincode3"];

if (isset($_POST["newtrain3"]))
	$newtrain3 = $_POST["newtrain3"];

if (isset($_POST["newtrainoth3"]))
	$newtrainoth3 = $_POST["newtrainoth3"];

if (isset($_POST["efe1"]))
	$efe1 = $_POST["efe1"];

if (isset($_POST["efeby1"]))
	$efeby1 = $_POST["efeby1"];

if (isset($_POST["efedate1"]))
	$efedate1 = $_POST["efedate1"];

if (isset($_POST["efe2"]))
	$efe2 = $_POST["efe2"];

if (isset($_POST["efeby2"]))
	$efeby2 = $_POST["efeby2"];

if (isset($_POST["efedate2"]))
	$efedate2 = $_POST["efedate2"];

if (isset($_POST["efe3"]))
	$efe3 = $_POST["efe3"];

if (isset($_POST["efeby3"]))
	$efeby3 = $_POST["efeby3"];

if (isset($_POST["efedate3"]))
	$efedate3 = $_POST["efedate3"];

if (isset($_POST["epr1"]))
	$epr1 = $_POST["epr1"];

if (isset($_POST["eprby1"]))
	$eprby1 = $_POST["eprby1"];

if (isset($_POST["eprdate1"]))
	$eprdate1 = $_POST["eprdate1"];

if (isset($_POST["epr2"]))
	$epr2 = $_POST["epr2"];

if (isset($_POST["eprby2"]))
	$eprby2 = $_POST["eprby2"];

if (isset($_POST["eprdate2"]))
	$eprdate2 = $_POST["eprdate2"];

if (isset($_POST["epr3"]))
	$epr3 = $_POST["epr3"];

if (isset($_POST["eprby3"]))
	$eprby3 = $_POST["eprby3"];

if (isset($_POST["eprdate3"]))
	$eprdate3 = $_POST["eprdate3"];

if (isset($_POST["madeby"]))
	$madeby = $_POST["madeby"];

if (isset($_POST["madedate"]))
	$madedate = $_POST["madedate"];

?>