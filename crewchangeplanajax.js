function fillselectcrew(x)
{
	var divclass = "class=\"navbar\"";
	var tblclass = "class=\"listcol\"";
	var th = "style=\"cursor:pointer;\" title=\"click here to sort by ascending/decending\"";
	var divht="height:410px;";
	var getheader;
	var getheaderlegend;
	//
	if(x=='excrew')
	{
		getheaderlegend="<div " + divclass  + "><table cellspacing=\"1\" cellpadding=\"0\"><tr><th><u>S</u>-SC/FT; <u>O</u>-OnBoard</th></tr></table></div>";
		getheader="<table " + tblclass  + " id=\"" + x + "header\" cellspacing=\"1\" cellpadding=\"0\"><tr>";
		getheader+="<th " + th  + " onClick=\"sort('col1');\">NAME</th>";
		getheader+="<th " + th  + " onClick=\"sort('col2');\">VSL</th>";
		getheader+="<th " + th  + " onClick=\"sort('col3');\">DISEMB</th>";
		getheader+="<th " + th  + ">STAT</th>";
		getheader+="<th>S/F</th>";
		getheader+="<th>&nbsp;</th>";
		getheader+="</tr></table>";
		getheader+="<div id=\"" + x + "listdetails\" style=\"width:100%;" + divht + "overflow:auto;\"></div>";
	}
	if(x=='newhire')
	{
		getheaderlegend="<div " + divclass  + "><table cellspacing=\"1\" cellpadding=\"0\"><tr><th><u>S</u>-SC/FT;</th></tr></table></div>";
		getheader="<table " + tblclass  + " id=\"" + x + "header\" cellspacing=\"1\" cellpadding=\"0\"><tr>";
		getheader+="<th " + th  + " onClick=\"sort('col1');\">NAME</th>";
		getheader+="<th " + th  + " onClick=\"sort('col2');\">VSL</th>";
		getheader+="<th " + th  + " onClick=\"sort('col3');\">DISEMB</th>";
//		getheader+="<th " + th  + ">STAT</th>";
		getheader+="<th>S/F</th>";
		getheader+="<th>&nbsp;</th>";
		getheader+="</tr></table>";
		getheader+="<div id=\"" + x + "listdetails\" style=\"width:100%;" + divht + "overflow:auto;\"></div>";
	}
	if(x=='scft')
	{
		getheaderlegend="<div " + divclass  + "><table cellspacing=\"1\" cellpadding=\"0\"><tr><th><u>O</u>-OnBoard; <u>P</u>-PNI(FIT)/Inactive; <u>V</u>-Vacation;</th></tr></table></div>";
		getheader="<table " + tblclass  + " id=\"" + x + "header\" cellspacing=\"1\" cellpadding=\"0\"><tr>";
		getheader+="<th " + th  + " onClick=\"sort('col1');\">NAME</th>";
		getheader+="<th " + th  + " onClick=\"sort('col2');\">VSL</th>";
		getheader+="<th " + th  + " onClick=\"sort('col3');\">DISEMB</th>";
		getheader+="<th " + th  + " onClick=\"sort('col4');\">GR</th>";
		getheader+="<th>O</th>";
		getheader+="<th>P</th>";
		getheader+="<th>V</th>";
		getheader+="<th>&nbsp;</th>";
		getheader+="</tr></table>";
		getheader+="<div id=\"" + x + "listdetails\" style=\"width:100%;" + divht + "overflow:auto;\"></div>";
	}
	if(x=='walkin')
	{
		getheaderlegend="<div " + divclass  + "><table cellspacing=\"1\" cellpadding=\"0\"><tr><th><u>F</u>-Fast Track; <u>O</u>-OnBoard; <u>P</u>-PNI(FIT)/Inactive; <u>V</u>-Vacation;</th></tr></table></div>";
		getheader="<table " + tblclass  + " id=\"" + x + "header\" cellspacing=\"1\" cellpadding=\"0\"><tr>";
		getheader+="<th " + th  + " onClick=\"sort('col1');\">NAME</th>";
		getheader+="<th " + th  + " onClick=\"sort('col2');\">VSL</th>";
		getheader+="<th " + th  + " onClick=\"sort('col3');\">DISEMB</th>";
		getheader+="<th " + th  + " onClick=\"sort('col4');\">GR</th>";
		getheader+="<th>F</th>";
		getheader+="<th>O</th>";
		getheader+="<th>P</th>";
		getheader+="<th>V</th>";
		getheader+="<th>&nbsp;</th>";
		getheader+="</tr></table>";
		getheader+="<div id=\"" + x + "listdetails\" style=\"width:100%;" + divht + "overflow:auto;\"></div>";
	}
	if(x=='inactive')
	{
		getheaderlegend="<div " + divclass  + "><table cellspacing=\"1\" cellpadding=\"0\"><tr><th><u>S</u>-Scholar/Fast Track; <u>V</u>-Vacation;</th></tr></table></div>";
		getheader="<table " + tblclass  + " id=\"" + x + "header\" cellspacing=\"1\" cellpadding=\"0\"><tr>";
		getheader+="<th " + th  + " onClick=\"sort('col1');\">NAME</th>";
		getheader+="<th " + th  + " onClick=\"sort('col2');\">VSL</th>";
		getheader+="<th " + th  + " onClick=\"sort('col3');\">DISEMB</th>";
		getheader+="<th " + th  + " onClick=\"sort('col4');\">GR</th>";
		getheader+="<th>S</th>";
		getheader+="<th>V</th>";
		getheader+="<th>&nbsp;</th>";
		getheader+="</tr></table>";
		getheader+="<div id=\"" + x + "listdetails\" style=\"width:100%;" + divht + "overflow:auto;\"></div>";
	}
	if(x=='searchcrew')
	{
		
		getheaderlegend="<div " + divclass  + "><table cellspacing=\"1\" cellpadding=\"0\"><tr><th><u>S</u>-SC/FT; <u>O</u>-OnBoard</th></tr></table></div>";
		getheader="<table " + tblclass  + " id=\"" + x + "header\" cellspacing=\"1\" cellpadding=\"0\"><tr>";
		getheader+="<th " + th  + " onClick=\"sort('col1');\">NAME</th>";
		getheader+="<th " + th  + " onClick=\"sort('col2');\">VSL</th>";
		getheader+="<th " + th  + " onClick=\"sort('col3');\">DISEMB</th>";
		getheader+="<th " + th  + ">STAT</th>";
		getheader+="<th>S/F</th>";
		getheader+="<th>&nbsp;</th>";
		getheader+="</tr></table>";
		getheader+="<div id=\"" + x + "listdetails\" style=\"width:100%;" + divht + "overflow:auto;\"></div>";
		
		
//		getheaderlegend="<div " + divclass  + "><table cellspacing=\"1\" cellpadding=\"0\"><tr><th><u>S</u>-SC/FT; <u>O</u>-OnBoard; <u>W</u>-Walk-in; <u>P</u>-PNI(FIT)/Inactive; <u>V</u>-Vacation</th></tr></table></div>";
//		getheader="<table " + tblclass  + " id=\"" + x + "header\" cellspacing=\"1\" cellpadding=\"0\"><tr>";
//		getheader+="<th " + th  + " onClick=\"sort('col1');\">NAME</th>";
//		getheader+="<th " + th  + " onClick=\"sort('col2');\">VSL</th>";
//		getheader+="<th " + th  + " onClick=\"sort('col3');\">DISEMB</th>";
//		getheader+="<th " + th  + " onClick=\"sort('col4');\">GR</th>";
//		getheader+="<th>S</th>";
//		getheader+="<th>O</th>";
//		getheader+="<th>W</th>";
//		getheader+="<th>P</th>";
//		getheader+="<th>V</th>";
//		getheader+="<th>&nbsp;</th>";
//		getheader+="</tr></table>";
//		getheader+="<div id=\"" + x + "listdetails\" style=\"width:100%;" + divht + "overflow:auto;\"></div>";
	}
	if(x=='applicant')
	{
		getheaderlegend="<div " + divclass  + "><table cellspacing=\"1\" cellpadding=\"0\"><tr><th><u>W</u>-Walk-in;</th></tr></table></div>";
		getheader="<table " + tblclass  + " id=\"" + x + "header\" cellspacing=\"1\" cellpadding=\"0\"><tr>";
		getheader+="<th " + th  + " onClick=\"sort('col1');\">NAME</th>";
		getheader+="<th " + th  + " onClick=\"sort('col2');\">VSL</th>";
		getheader+="<th " + th  + " onClick=\"sort('col3');\">DISEMB</th>";
		getheader+="<th>W</th>";
		getheader+="<th>&nbsp;</th>";
		getheader+="</tr></table>";
		getheader+="<div id=\"" + x + "listdetails\" style=\"width:100%;" + divht + "overflow:auto;\"></div>";
	}
	if(x=='foreign')
	{
		getheaderlegend="<div " + divclass  + "><table cellspacing=\"1\" cellpadding=\"0\"><tr><th></th></tr></table></div>";
		getheader="<table " + tblclass  + " id=\"" + x + "header\" cellspacing=\"1\" cellpadding=\"0\"><tr>";
		getheader+="<th " + th  + " onClick=\"sort('col1');\">NAME</th>";
//		getheader+="<th " + th  + " onClick=\"sort('col2');\">VSL</th>";
//		getheader+="<th " + th  + " onClick=\"sort('col3');\">DISEMB</th>";
//		getheader+="<th>&nbsp;</th>";
		getheader+="</tr></table>";
		getheader+="<div id=\"" + x + "listdetails\" style=\"width:100%;" + divht + "overflow:auto;\"></div>";
	}
	if(x=='cadet')
	{
		getheaderlegend="<div " + divclass  + "><table cellspacing=\"1\" cellpadding=\"0\"><tr><th><u>W</u>-Walk-in;</th></tr></table></div>";
		getheader="<table " + tblclass  + " id=\"" + x + "header\" cellspacing=\"1\" cellpadding=\"0\"><tr>";
		getheader+="<th " + th  + " onClick=\"sort('col1');\">NAME</th>";
		getheader+="<th " + th  + " onClick=\"sort('col2');\">SCHOOL</th>";
		getheader+="<th " + th  + " onClick=\"sort('col3');\">GRADUATE</th>";
		getheader+="<th>&nbsp;</th>";
		getheader+="</tr></table>";
		getheader+="<div id=\"" + x + "listdetails\" style=\"width:100%;" + divht + "overflow:auto;\"></div>";
	}
//	alert('1');
	document.getElementById('legend').innerHTML=getheaderlegend;
//	alert('2');
	document.getElementById('poolinglist').innerHTML=getheader;
//	alert(x+'listdetails');
//		alert('3');
	document.getElementById(x+'listdetails').innerHTML=results[2];
	
//	alert('4');
	setWidth(x+'header',x+'details');
//	alert('5');
}
function fillvesselselect(x)
{
	var vesseldetails=results[3].split('^');
	document.getElementById('crewchangedetails').innerHTML=results[2];
	document.getElementById('chkdiscrepancy').innerHTML=results[5];
//	setWidth('discrepancyheader','discrepancydetails');
	document.getElementById('btnccpno').onclick=function(){saveccpno();};
	document.repcrewchangeplan.reportresult.value=results[2]; // for reports
	reportresult=results[2]; // for reports
	vesselname=vesseldetails[2]; // for reports
	document.repcrewchangeplan.vesselname.value=vesselname; // for reports
	document.crewchangeplan.addnewcrew.value='';
	document.getElementById('btnadd').onclick=function(){if(chkbtnloading()==1){btnadddefault();}};
	document.getElementById('btnsave').onclick=function(){btndefault();};
	with(document.crewchangeplan)
	{
//		alert(vesseldetails[0]);
		vesselcode.value=vesseldetails[0];
		vesseltypecode.value=vesseldetails[1];
		if(results[4]) //for ccpno
		{
			var ccpnoset=results[4].split('^');
//			alert(ccpnoset[0]);
			if(ccpnoset[0]=="0")
			{
				alert('CCPNo: ' + ccpno.value + ' is not found!');
				ccpno.value='';
				vesselcode.value='';
			}
			else
			{
				ccpno.value=ccpnoset[0];
				document.repcrewchangeplan.ccpno.value=ccpnoset[0]; // for reports
			}
		}
	}
}
function fillembcountry(x)
{
	document.getElementById('embport').innerHTML=results[2];
	if(document.crewchangeplan.embporttemp.value)
	{
		document.crewchangeplan.embport.value=document.crewchangeplan.embporttemp.value;
		document.crewchangeplan.embporttemp.value='';
	}
}
function fillview201(x)
{
	document.getElementById('selectname').innerHTML=name201;
	document.getElementById('view201list').innerHTML=results[2];
	if(document.crewchangeplan.actionajax.value=='viewonboard201')
	{
		onboard201result=results[2];
		onboard201();
	}
}
function tabarrangement(y)
{
	var selectrow1;
	var selectrow2;
	var selectcombine;
	selectrow1="<div id=\"tabsite\" style=\"width:100%;background:#F2F1EA;\">";
	selectrow1+="<ul style=\"margin-top:2px;margin-bottom:2px;\">";
	selectrow1+="<li id=\"currentlipool\" name=\"aexcrew\"><a name=\"excrew\" onclick=\"selecttab(this.name,1);\" id=\"currentpool\"><span style=\"width:80px;text-align:center;\">Ex-Crew</span></a></li>";
//	selectrow1+="<li id=\"ainactive\" name=\"ainactive\"><a name=\"inactive\" onclick=\"selecttab(this.name,1);\"><span style=\"width:80px;text-align:center;\">Inactive</span></a></li>";
//	selectrow1+="<li id=\"atop10\" name=\"atop10\"><a name=\"top10\" onclick=\"alert('This tab is under construction!');\"><span style=\"width:80px;text-align:center;\">Top 10</span></a></li>";
	selectrow1+="<li id=\"anewhire\" name=\"anewhire\"><a name=\"newhire\" onclick=\"selecttab(this.name,1);\"><span style=\"width:80px;text-align:center;\">New Hire</span></a></li>";
	selectrow1+="<li id=\"aforeign\" name=\"aforeign\"><a name=\"foreign\" onclick=\"selecttab(this.name,1);\"><span style=\"width:80px;text-align:center;\">Foreign</span></a></li>";
//	selectrow1+="<li id=\"ascft\" name=\"ascft\"><a name=\"scft\" onclick=\"selecttab(this.name,1);\"><span style=\"width:80px;text-align:center;\">SC-FT</span></a></li>";
	selectrow1+="<li id=\"asearchcrew\" name=\"asearchcrew\"><a name=\"searchcrew\" onclick=\"if(searchfname.value!='' || searchgname.value!=''){selecttab(this.name,1);}else{alert('Nothing to search!');}\"><span style=\"width:80px;text-align:center;\">Search</span></a></li>";
//	selectrow1+="<li id=\"awalkin\" name=\"awalkin\"><a name=\"walkin\" onclick=\"selecttab(this.name,1);\"><span style=\"width:80px;text-align:center;\">Walk-In</span></a></li>";
	selectrow1+="</ul>";
	selectrow1+="</div>";
	
//	selectrow2="<div id=\"tabsite\" style=\"width:100%;background:#F2F1EA;\">";
//	selectrow2+="<ul style=\"width:100%;\">";
//	selectrow2+="<li id=\"ainactive\" name=\"ainactive\"><a name=\"inactive\" onclick=\"selecttab(this.name,2);\"><span style=\"width:80px;text-align:center;\">Inactive</span></a></li>";
//	selectrow2+="<li id=\"aapplicant\" name=\"aapplicant\"><a name=\"applicant\" onclick=\"selecttab(this.name,2);\"><span style=\"width:80px;text-align:center;\">Applicant</span></a></li>";
//	selectrow2+="<li id=\"asearchcrew\" name=\"asearchcrew\"><a name=\"searchcrew\" onclick=\"if(searchfname.value!='' || searchgname.value!=''){selecttab(this.name,2);}else{alert('Nothing to search!');}\"><span style=\"width:80px;text-align:center;\">Search</span></a></li>";
//	selectrow2+="<li id=\"aforeign\" name=\"aforeign\"><a name=\"foreign\" onclick=\"selecttab(this.name,2);\"><span style=\"width:80px;text-align:center;\">Foreign</span></a></li>";
//	selectrow2+="<li id=\"acadet\" name=\"acadet\"><a name=\"cadet\" onclick=\"selecttab(this.name,2);\"><span style=\"width:80px;text-align:center;\">Cadet</span></a></li>";
//	selectrow2+="</ul>";
//	selectrow2+="</div>";
	
//	if(y==1)
		selectcombine=selectrow1;
//		selectcombine=selectrow2+selectrow1;
//	else
//		selectcombine=selectrow1+selectrow2;
	
	document.getElementById("tabarrange").innerHTML=selectcombine;
}
function fillnodivcode()
{
	alert('CCP No. used in non-active vessel!');
	document.crewchangeplan.ccpno.value='';
}
function fillwrongdivcode()
{
	alert('CCP No. used in different Division!');
	document.crewchangeplan.ccpno.value='';
}