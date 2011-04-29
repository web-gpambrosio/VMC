
function onboard201()
{
	var myObject = new Object();
    myObject.getonboard201result = onboard201result;
    myObject.getcrewname = document.crewonboard.crewonboardhidden.value;
    window.showModalDialog("crewonboard201.php", myObject, "dialogHeight:650px; dialogWidth:500px;status=no"); 
}

var onboard201result;
function fillview201(x)
{
	if(document.crewonboard.actionajax.value=="viewonboard201")
	{
		onboard201result=results[2];
		onboard201();
	}
}

function fillcrewinfotmp(x)
{
	var crewinfo=results[1].split('^');
	var arrivaldep=results[2].split('^');
	var pni=results[3].split('^');
//	alert(arrivaldep);
	with (document.crewonboard)
	{
		xcrewname.value=crewinfo[0];
		xembdate.value=crewinfo[1];
		xdisembdate.value=crewinfo[2];
		xembport.value=crewinfo[3];
		
//		if (crewinfo[4] != "")
//			mandisembreasoncode.value=crewinfo[4];
//			
//		mandisembdate.value="";
		
		mandepartmnl.value=arrivaldep[0];
		manarrivemnl.value=arrivaldep[1];
		if (arrivaldep[3]!="") //CONFIRMDEPDATE
		{
			mandepartmnl.disabled=true;
			if (arrivaldep[5]!="") //CONFIRMARRMNLDATE
				manarrivemnl.disabled=true;
			else
				manarrivemnl.disabled=false;	
		}
		else
		{
			mandepartmnl.disabled=false;
			manarrivemnl.disabled=true;
		}
			
		
		maninjurydate.value = pni[0];
		maninjuryreason.value = pni[1];
		
//		btnupdate1.disabled=false;
		btnupdate2.disabled=false;
		btnupdate3.disabled=false;
	}
}
function fillcrewinfo(getccid,getcrewname,getdateemb,getdatedisemb,getembarkport,getdepdate,getarrdate,getpnidate,getpnireason,getembcountry,getembport,gettagpromote,getdisembreasoncode)
{
	with (document.crewonboard)
	{
		batch.value='';
		btnupdate2.disabled=false;
		btnupdate3.disabled=false;
		if(document.getElementById('xarrival').style.display=='block')
		{
			manarrivemnl.disabled=false;
			mandepartmnl.disabled=false;
		}
		if(document.getElementById('xpni').style.display=='block')
		{
			maninjurydate.disabled=false;
			maninjuryreason.disabled=false;
		}
		
		mandisembreasoncode.value=getdisembreasoncode;
		
		xcrewname.value=getcrewname;
		embcountry.value=getembcountry;
		embporthidden.value=getembport;
		embdate.value=getdateemb;
		disembdate.value=getdatedisemb;
		xembdate.value=getdateemb;
		xdisembdate.value=getdatedisemb;
		xembport.value=getembarkport;
//		alert('y '+getdepdate + '/' + getarrdate)
		mandepartmnl.value=getdepdate;
		manarrivemnl.value=getarrdate;
		maninjurydate.value = getpnidate;
		maninjuryreason.value = getpnireason;
		ccidhidden.value = getccid;
		if(gettagpromote==1)
		{
			embdate.readOnly="readOnly";
			tagpromotehidden.value=gettagpromote;
		}
//		if (getdepdate!="") //CONFIRMDEPDATE
//		{
//			mandepartmnl.disabled=true;
//			if (getarrdate!="") //CONFIRMARRMNLDATE
//			{
//				manarrivemnl.disabled=true;
//				embdate.disabled=true;
//				btnupdate2.disabled=true;
//			}
//			else
//			{
//				manarrivemnl.disabled=false;
//				if(document.getElementById('xarrival').style.display=='block')
//					manarrivemnl.focus();
//			}
//		}
//		else
//		{
//			mandepartmnl.disabled=false;
//			manarrivemnl.disabled=true;
//			if(document.getElementById('xarrival').style.display=='block')
//				mandepartmnl.focus();
//		}
		if (getpnidate!="") //PNI DATE
		{
			maninjurydate.disabled=true;
			maninjuryreason.disabled=true;
			btnupdate3.disabled=true;
		}
		else
		{
			if(document.getElementById('xpni').style.display=='block')
				maninjurydate.focus();
		}
//		if(getembcountry!='')
			submit();
	}
}
function chksort(crewsort)
{
	with(document.crewonboard)
	{
		if(crewsort==crewsortby.value)
		{
			if(creworderby.value=='')
				creworderby.value='DESC';
			else
				creworderby.value='';
		}
		else
		{
			crewsortby.value=crewsort;
			creworderby.value='';
		}
		submit();
	}
}