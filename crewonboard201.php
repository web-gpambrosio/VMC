<?php
include('veritas/connectdb.php');
//include('connectdb.php');

echo "

<html>
<title>On-board crew 201</title>
<head>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='../veritas/veripro.js'></script>
<script>
function selecttab201(x)
{
	document.crewchangeplan.prev201.value=document.getElementById('currentli201').name;
	document.getElementById('currentli201').id=document.crewchangeplan.prev201.value;
	document.getElementById('a'+x).id='currentli201';
	
	//get previous select
	var getprevlen=document.crewchangeplan.prev201.value.length;
	var getprev=document.crewchangeplan.prev201.value.substring(1,getprevlen);
	
	with(document.crewchangeplan)
	{
		eval(getprev+'.id=\'\'');
		eval(x+'.id=\'current201\'');
	}
	
	//for div details
	if(document.getElementById(getprev+'list'))
	{
		document.getElementById(getprev+'list').style.display='none';
		document.getElementById(x+'list').style.display='block';
	}
}
</script>
</head>

<body onload=\"this.focus();\">
<form name=\"crewchangeplan\">
<div style=\"width:490px;height:590px;overflow:hidden;background:White;float:right;\">
	<div class=\"navbar\" id=\"selectname\" style=\"width:100%;color:Orange;text-align:center;font-size:1.2em;font-weight:bold;\">
	</div>
	<div id=\"tab201site\" style=\"width:100%;\">
		 <ul>
			  <li id=\"currentli201\" name=\"apersonal201\"><a name=\"personal201\" onclick=\"selecttab201(this.name);\" id=\"current201\">Personal</a></li> 
			  <li id=\"adocuments201\" name=\"adocuments201\"><a name=\"documents201\" onclick=\"selecttab201(this.name);\">Documents</a></li>
			  <li id=\"aexperience201\" name=\"aexperience201\"><a name=\"experience201\" onclick=\"selecttab201(this.name);\">Experience</a></li>
			  <li id=\"atraining201\" name=\"atraining201\"><a name=\"training201\" onclick=\"selecttab201(this.name);\">Training</a></li>
			  <li id=\"aperformance201\" name=\"aperformance201\"><a name=\"performance201\" onclick=\"selecttab201(this.name);\">Performance</a></li> 
			  <li id=\"amedical201\" name=\"amedical201\"><a name=\"medical201\" onclick=\"selecttab201(this.name);\">Medical</a></li>
		 </ul>
	</div>
	<div id=\"view201list\" style=\"width:100%;height:500px;\">
	
	</div>
</div>
<input type=\"hidden\" name=\"prev201\">
</form>
<script>
	var oMyObject = window.dialogArguments;
	var onboard201result = oMyObject.getonboard201result;
	var crewname = oMyObject.getcrewname;
	document.getElementById('selectname').innerHTML=crewname;
	document.getElementById('view201list').innerHTML=onboard201result;
//	alert(crewname+' ' +onboard201result);
</script>
</body>
";
					
?>