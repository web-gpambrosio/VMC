<?php

include('veritas/connectdb.php');
//include('connectdb.php');



session_start();
$getservername=$_SERVER["SERVER_NAME"];
$getserveraddr=$_SERVER['SERVER_ADDR'];

include('veritas/include/stylephp.inc');

$basedir = "docimages"; //change if different directory

$datetimenow=date("Y-m-d H:i:s");
$datenow=date("Y-m-d");
$datenowshow=date("m/d/Y");

	#*******************POST VALUES*************
if(isset($_SESSION['employeeid']))
	$employeeid=$_SESSION['employeeid'];
	
if(isset($_POST['vesselcode']))
	$vesselcode=$_POST['vesselcode'];
	
if(isset($_POST['firstentry']))
	$firstentry = 0;
else
	$firstentry = 1;
	
if(isset($_POST['shownobatch']))
{
	$shownobatch = 1;
	$batchchk = "checked=\"checked\"";
	$firstentry = 0;
}
else
{
	$shownobatch = 0;
	$batchchk = "";
}
	
if($firstentry == 1 && $shownobatch == 0)
{
	$batchchk = "checked=\"checked\"";
	$shownobatch=1;
}
	
//if(isset($_POST['rankcodehidden']))
//{
//	$rankcodehidden=$_POST['rankcodehidden'];
//
//}
//$employeeid="EMM"; //temporary for testing purpose only

//GET DIVCODE

if(isset($_GET['divcode']))
	$divcode=$_GET['divcode'];
else 
{
	$qrydivcode=mysql_query("SELECT DIVCODE FROM employee WHERE EMPLOYEEID='$employeeid'") or die(mysql_error());
	$rowdivcode=mysql_fetch_array($qrydivcode);
	$divcode=$rowdivcode["DIVCODE"];
}
	
if(isset($_GET['vsltypecode']))
	$vsltypecode=$_GET['vsltypecode'];

//FILL VESSEL FOR SELECT BOX
// include('veritas/include/qryvessellist.inc');

switch ($vsltypecode)
{
	case "PCC":
		$qryvessellist=mysql_query("SELECT v.VESSELCODE,VESSEL,v.VESSELTYPECODE
			FROM vessel v
			WHERE STATUS=1 AND VESSELTYPECODE='PCC'
			ORDER BY VESSEL") or die(mysql_error());
	break;
	case "BULK":
		$qryvessellist=mysql_query("SELECT v.VESSELCODE,VESSEL,v.VESSELTYPECODE
			FROM vessel v
			WHERE STATUS=1 AND VESSELTYPECODE='BULK'
			ORDER BY VESSEL") or die(mysql_error());
	break;
}




//FILL EMBARK PORT (COUNTRY AND PORT)
$qryembcountry=mysql_query("SELECT DISTINCT PORTCOUNTRY FROM port ORDER BY PORTCOUNTRY") or die(mysql_error());

//FILL RANK 
$qryrankcode=mysql_query("SELECT ALIAS1,RANKCODE,ALIAS2 FROM rank ORDER BY RANKING") or die(mysql_error());
			
//FILL VESSELTYPE
$qryvesseltype=mysql_query("SELECT VESSELTYPECODE,VESSELTYPE FROM vesseltype ORDER BY VESSELTYPE") or die(mysql_error());

//FILL DISEMBREASONCODE
$qrydisemb=mysql_query("SELECT REASON,DISEMBREASONCODE FROM disembarkreason ORDER BY REASON") or die(mysql_error());

echo	"<html>\n
<title>CREW CHANGE PLAN</title>
<head>\n
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script type='text/javascript' src='../veritas/veripro.js'></script>
<script type='text/javascript' src='../veritas/crewchangeplanajax.js'></script>
<script type='text/javascript' src='../veritas/ajax.js'></script>

<style>

.listgray {
	color: Black;
  font-size:0.7em;
	background-color: #DCDCDC;
}
.listsilver {
	color: Black;
  font-size:0.7em;
	background-color: #CCCCCC;
}

.details {
    font-size:0.7em;
    font-family:Arial;
}

</style>


<script>
var represults;

function switchajax(x)
{
	if(x==1) // 1 if loading box is visible
	{
		var strdisplay='block';
		var strdisable='disabled';
	}
	else
	{
		var strdisplay='none';
		var strdisable='';
	}
	if(document.getElementById('crewchangedate').style.display=='none'
			&& document.getElementById('changebatch').style.display=='none')
		document.getElementById('ajaxprogress').style.display=strdisplay;
	
	with(document.crewchangeplan)
	{
		vesselcode.disabled=strdisable;
		batchlimit.disabled=strdisable;
		ccpno.disabled=strdisable;
		rankcode.disabled=strdisable;
		vesseltypecode.disabled=strdisable;
		searchfname.disabled=strdisable;
		searchgname.disabled=strdisable;
		if(ccpno.value!='')
		{
			vesselcode.disabled=true;
			batchlimit.disabled=true;
		}
	}
}
var name201;
function chkloading(getactionajax,resetno)
{
	if(document.getElementById('ajaxprogress').style.display=='none') // DO NOT RUN IF AJAX IS BUSY
	{
		if(getactionajax=='view201')
			document.getElementById('crewdetails').style.display='block';
		else
			document.getElementById('crewdetails').style.display='none';
			
		with(document.crewchangeplan)
		{
			actionajax.value=getactionajax;
		}
		createurl();
		resetdata(resetno);
	}
}
function chkbtnloading()
{
	if(document.getElementById('ajaxprogress').style.display=='none' && 
		document.getElementById('crewchangedate').style.display=='none'
		&& document.getElementById('changebatch').style.display=='none')
		return 1;
	else
		return 0;
}
function chkchangesave()
{
	var rem='';
	if(document.getElementById('newdate').value=='')
	{
		rem='New Date';
	}
//	if(document.getElementById('newdate').value!=document.getElementById('existdate').value)
//	{
//		if(document.getElementById('disembreasoncode').value=='')
//		{
//			if(rem=='')
//				rem='Reason';
//			else
//				rem=rem + ' and Reason';
//		}
//	}
	if(rem=='')
	{
		with(document.crewchangeplan)
		{
			actionajax.value='savechangedate';
		}
		document.getElementById('ajaxprogress').style.display='block';
		createurl();
		resetdata(0);
		document.getElementById('ajaxprogress').style.display='none';
		document.getElementById('crewchangedate').style.display='none';
		with(document.crewchangeplan)
		{
			newdate.value='';
//			disembreasoncode.value='';
		}
	}
	else
		alert('Input ' + rem + ' first!');
}
function chkchangebatch()
{
	with(document.crewchangeplan)
	{
		if(batchselect.value!='')
		{
			actionajax.value='savechangebatch';
			batchnohidden.value=batchselect.value;
			document.getElementById('ajaxprogress').style.display='block';
			createurl();
			resetdata(0);
			document.getElementById('ajaxprogress').style.display='none';
			batchnohidden.value='';
			crewselect.focus();
		}
		else
			alert('You have to select a batch!');
	}
	
}
var fillurl;
var url;
function createurl(x)
{
	url = \"http://$getserveraddr/veritas/crewchangeplanrequest.php?actionajax=\"; // The server-side script 
	// url = \"http://localhost/veritas/crewchangeplanrequest.php?actionajax=\"; // The server-side script 
	var actionajax = document.crewchangeplan.actionajax.value; 
		
	//fill URL
	if(document.crewchangeplan.onboardcrew.value)
	{
		var rankaliasarray = document.crewchangeplan.onboardcrew.value.split('(');
		rankaliasarray1=rankaliasarray[1].split(')');
		var rankalias = '&rankalias=' + rankaliasarray1[0];
	}
	else
		var rankalias = '&rankalias=';
	
	var employeeid = '&employeeid=$employeeid';
	var divcode = '&divcode=$divcode';
//	alert(employeeid);
	var rankcode = '&rankcode=' + document.crewchangeplan.rankcode.value;
	var rankcodehidden = '&rankcodehidden=' + document.crewchangeplan.rankcodehidden.value;
	var poolchoice = '&poolchoice=' + document.crewchangeplan.poolchoice.value;
	var vesselcode = '&vesselcode=' + document.crewchangeplan.vesselcode.value;
	var vesseltypecode = '&vesseltypecode=' + document.crewchangeplan.vesseltypecode.value;
	var batchlimit = '&batchlimit=' + document.crewchangeplan.batchlimit.value;
	var sortby = '&sortby=' + document.crewchangeplan.sortby.value;
	var orderby = '&orderby=' + document.crewchangeplan.orderby.value;
	var embcountry = '&embcountry=' + document.crewchangeplan.embcountry.value;
	var embport = '&embport=' + document.crewchangeplan.embport.value;
	var ccpno = '&ccpno=' + document.crewchangeplan.ccpno.value;
	var batchnohidden = '&batchnohidden=' + document.crewchangeplan.batchnohidden.value;
	var shownobatch = '&shownobatch=' + $shownobatch;
	
	//details for saving
	var ccidhidden = '&ccidhidden=' + document.crewchangeplan.ccidhidden.value;
	var embccidhidden = '&embccidhidden=' + document.crewchangeplan.embccidhidden.value;
	var dateemb = '&dateemb=' + document.crewchangeplan.dateemb.value;
	var datedisemb = '&datedisemb=' + document.crewchangeplan.datedisemb.value;
	var embapplicantnohidden = '&embapplicantnohidden=' + document.crewchangeplan.embapplicantnohidden.value;
	var addnewcrew = '&addnewcrew=' + document.crewchangeplan.addnewcrew.value;
	
	//search crew name
	var searchfname = '&searchfname=' + document.crewchangeplan.searchfname.value;
	var searchgname = '&searchgname=' + document.crewchangeplan.searchgname.value;
	
	//details for date change disembark
	var changedatedisemb = '&changedatedisemb=' + document.crewchangeplan.newdate.value;
	var estimatedate = '&estimatedate=' + document.crewchangeplan.estdate.value;
//	var disembreasoncode = '&disembreasoncode=' + document.crewchangeplan.disembreasoncode.value;
	
	//promotion relation
	var promoteembdate = '&promoteembdate=' + document.crewchangeplan.promoteembdate.value;
	var promoteccid = '&promoteccid=' + document.crewchangeplan.promoteccid.value;
	var promoteappno = '&promoteappno=' + document.crewchangeplan.promoteappno.value;
						
	fillurl = actionajax + poolchoice + rankalias + rankcode + rankcodehidden + vesselcode + vesseltypecode + batchlimit 
					+ sortby + orderby + embcountry + embport
					+ ccpno + ccidhidden + embccidhidden + dateemb + datedisemb + embapplicantnohidden + addnewcrew 
					+ searchfname + searchgname + changedatedisemb + employeeid + estimatedate
					+ promoteembdate + promoteccid + promoteappno + batchnohidden + divcode + shownobatch;	
					
//	fillurl = actionajax + poolchoice + rankalias + rankcode + rankcodehidden + vesselcode + vesseltypecode + batchlimit 
//					+ sortby + orderby + embcountry + embport
//					+ ccpno + ccidhidden + embccidhidden + dateemb + datedisemb + embapplicantnohidden + addnewcrew 
//					+ changedatedisemb + employeeid 
//					+ promoteembdate + promoteccid + promoteappno + batchnohidden + divcode + shownobatch;

 // alert(fillurl);
	getvalues();
}
</script>

<SCRIPT language=JavaScript>\n

var prevbgcolor,prevfontcolor;
var overbgcolor='#ADD2F1';
var overfontcolor='#2D00B0';
function addeditcrew(ccid,getrankcode,getrankalias,onboardname,eoc,ccidemb,embembport,embembcountry,
	embdateemb,embdatedisemb,rankalias,embapplicantno,applicantno,embname)
{
	document.getElementById('btnsave').onclick=function(){chksave();};
	document.getElementById('btnadd').onclick=function(){if(chkbtnloading()==1){poolingdata(1);selecttab('excrew',1);}};
	with(document.crewchangeplan)
	{
		onboardcrew.value=onboardname + '  (' + getrankalias + ')';
		dateemb.value=embdateemb;
		datedisemb.value=embdatedisemb;
		crewname.value=embname;
		ccidhidden.value=ccid;
		embccidhidden.value=ccidemb;
		rankcode.value=getrankcode;
		rankcodehidden.value=getrankcode;
		dateemb.disabled=false;
		datedisemb.disabled=false;
		embcountry.disabled=false;
		if(ccidemb!='')
		{
			embcountry.value=embembcountry;
			chkloading('embcountry',10);
			embporttemp.value=embembport;
		}
		else
		{
			embcountry.value='';
			embport.value='';
			embport.disabled=true;
		}
		dateemb.select();
	}
}
function poolingdata(x) // 0 - closing of pooling div; 1 - opening of pooling div
{
	if(x==0)
	{
		var strdisplayopen='none';
		var strdisplayclosed='block';
		var strdisabled='';
	}
	else
	{
		var strdisplayopen='block';
		var strdisplayclosed='none';
		var strdisabled='true';
	}
	
	document.getElementById('pooling').style.display=strdisplayopen;
	with(document.crewchangeplan)
	{
		batchlimit.style.display=strdisplayclosed;
		vesselcode.style.display=strdisplayclosed;
		embcountry.style.display=strdisplayclosed;
		embport.style.display=strdisplayclosed;
		rankcode.value=rankcodehidden.value;
//		poolshow.value=x;
	}
}
function selecttab(x,y)
{
	if(chkbtnloading()==1)
	{
		tabarrangement(y);
		
		sortcol1='SORT_ASC';
		sortcol2='';
		sortcol3='';
		sortcol4='';
		sortcol5='';
		sortcol6='';
		sortcol7='';
		sortcol8='';
		sortcol9='';
		sortcol10='';
		sortcol11='';
		document.crewchangeplan.sortby.value='';
		
		if(document.getElementById('ajaxprogress').style.display=='none')
		{
			document.crewchangeplan.prevpool.value=document.getElementById('currentlipool').name;
			document.getElementById('currentlipool').id=document.crewchangeplan.prevpool.value;
			document.getElementById('a'+x).id='currentlipool';
			
			//get previous select
			var getprevlen=document.crewchangeplan.prevpool.value.length;
			var getprev=document.crewchangeplan.prevpool.value.substring(1,getprevlen);
			
			
			with(document.crewchangeplan)
			{
				actionajax.value='selectcrew';
				poolchoice.value=x;
				eval(getprev+'.id=\'\'');
				eval(x+'.id=\'currentpool\'');
			}
			document.getElementById('crewdetails').style.display='none';
			createurl();
		}
	}
}
function selecttab201(x)
{
	if(document.getElementById('ajaxprogress').style.display=='none')
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
}


var sortcol1='SORT_ASC';
var sortcol2,sortcol3,sortcol4,sortcol5,sortcol6,sortcol7,sortcol8,sortcol9,sortcol10,sortcol11;
function sort(x)
{
	if(chkbtnloading()==1)
	{
		getsort = eval('sort'+x);
		sortcol1='';
		sortcol2='';
		sortcol3='';
		sortcol4='';
		sortcol5='';
		sortcol6='';
		sortcol7='';
		sortcol8='';
		sortcol9='';
		sortcol10='';
		sortcol11='';
		
		if(getsort=='SORT_ASC')
		{
			eval('sort'+x+'=\'SORT_DESC\'');
			document.crewchangeplan.orderby.value='SORT_DESC';
		}
		else
		{
			eval('sort'+x+'=\'SORT_ASC\'');
			document.crewchangeplan.orderby.value='SORT_ASC';
		}
		document.crewchangeplan.sortby.value=x;
		document.crewchangeplan.actionajax.value='selectcrew';
		document.getElementById('crewdetails').style.display='none';
		createurl();
	}
}
function chksave()
{
	var rem='';
	with(document.crewchangeplan)
	{
		if(crewname.value=='')
			rem='Crew Replacement';
		if(dateemb.value=='')
		{
			if(rem=='')
				rem='Embark Date';
			else
				rem+=', Embark Date';
		}
		if(datedisemb.value=='')
		{
			if(rem=='')
				rem='DisEmbark Date';
			else
				rem+=', DisEmbark Date';
		}
		if(embcountry.value!='' && embport.value=='')
		{
			if(rem=='')
				rem='Port';
			else
				rem+=', Port';
		}
		if(rem=='')
			chkloading('saveembarkcrew',0)
		else
			alert('Input ' + rem + ' first!');
	}
}
function chkcadet(poolcrewname,showcrewname,embapplicantno,embrankcode)
{
	var rankcode1=document.crewchangeplan.rankcodehidden.value;
	var chkgo=0;
	if(rankcode1=='')
		alert('You must choose a rank first!');
	else
	{
		if(!(rankcode1=='D41' || rankcode1=='D49' || rankcode1=='E41' || rankcode1=='E49'))
		{
			if(confirm('Rank is not for a Cadet. Continue?'))
				chkgo=1;
		}
		else
			chkgo=1;
	}
	if(chkgo==1)
	{
		selectcrew(poolcrewname,showcrewname,embapplicantno,embrankcode)
	}
}
function selectcrew(poolcrewname,showcrewname,embapplicantno,embrankcode)
{
	var allowrank;
	var rcode=document.crewchangeplan.rankcode.value;
	
	
	if(document.getElementById('ajaxprogress').style.display=='none') // DO NOT RUN IF AJAX IS BUSY
	{
		x=poolcrewname.split('(');
		y=x[1].split(')');
		z=y[0];
		
		with(document.crewchangeplan)
		{
			crewname.value = showcrewname;
			
			if(dateemb.value == '')
			{
				var d = new Date();
				
				dateemb.value = (d.getMonth() + 1) + '/' + d.getDate() + '/' + d.getFullYear();
				datedisemb.value = ((d.getMonth() + 1) + 9) + '/' + d.getDate() + '/' + d.getFullYear();
			}
		
			if (onboardcrew.value=='' && rankcodehidden.value=='' && z=='')
				allowrank = 0;
			else
			{
				allowrank = 1;
				embapplicantnohidden.value=embapplicantno;
				if(onboardcrew.value=='')
				{
					if (embrankcode == '')
					{
						if (z != '')
							rankcodehidden.value=z;
					}
					else
					{
						if (rcode != '')
						{
							if (rcode != embrankcode)
							{
								if(confirm(showcrewname + '. Change Rank to ' + y[1] + '? Click Cancel to retain original rank.'))
								{
									rankcodehidden.value=rcode;
									crewname.value=x[0] + '(' + y[1] + ')';
								}
								else
								{
									rankcodehidden.value=embrankcode;
									crewname.value=showcrewname;
								}	
							}
						}
						else
						{
							rankcodehidden.value=embrankcode;
							crewname.value=showcrewname;
						}
					}
				}
			}
		}
		
		if (allowrank == 1)
			poolingdata(0);
		else
			alert('Please specify rank.');
	}
}

function resetdata(x)
{
	with(document.crewchangeplan)
	{
		if(x==0) //VESSELCODE IS SELECTED
		{
			batchlimit.value='';
			ccpno.value='';
		}
		if(x<=1) //BATCHLIMIT IS SELECTED
		{
			onboardcrew.value='';
			crewname.value='';
			dateemb.value='';
			datedisemb.value='';
			embcountry.value='';
			embport.value='';
			rankcodehidden.value='';
			//DISABLE FIELDS
//			btnadd.disabled=true;
//			btnsave.disabled=true;
			ccpno.disabled=true;
			dateemb.disabled=true;
			datedisemb.disabled=true;
			embcountry.disabled=true;
			embport.disabled=true;
		}
		
//		ccidhidden.value='';
//		eochidden.value='';
//		embccidhidden.value='';
//		embapplicantnohidden.value='';
//		applicantnohidden.value='';
	}
}
function validateccpno()
{
	with(document.crewchangeplan)
	{
		batchlimit.value='';
		chkloading('ccpno',1);
	}
}
function saveccpno()
{
	if(document.crewchangeplan.ccpno.value=='')
	{
		if(confirm('Are you sure you want to get CCPNo?'))
		{
			chkloading('getccpno',1);
		}
	}
	else
	{
		alert('CCPNo exists! Clear CCPNo first.');
	}
}

function printbatch()
{
	// alert(document.crewchangeplan.vesselcode.value + ' / ' + document.crewchangeplan.batchnoprint.value);
	document.repcrewchangeplanbatch.vslcode.value=document.crewchangeplan.vesselcode.value;
	document.repcrewchangeplanbatch.bno.value=document.crewchangeplan.batchnoprint.value;

	window.open(document.repcrewchangeplanbatch.action,document.repcrewchangeplanbatch.target,'scrollbars=yes,resizable=yes,channelmode=yes,status=yes');
	repcrewchangeplanbatch.submit();
}

function printccp()
{
	var printok=1;
	document.repcrewchangeplan.ccpno.value=document.crewchangeplan.ccpno.value;
	if(document.crewchangeplan.ccpno.value=='')
	{
		if(!confirm('CCP No. does not exist! Continue anyway?'))
			printok=0;
		else
			document.repcrewchangeplan.ccpno.value='Not submitted';
	}
	
	if(printok==1)
	{
//		document.repcrewchangeplan.vesselname.value=vesselname;
		window.open(repcrewchangeplan.action,repcrewchangeplan.target,
			'scrollbars=yes,resizable=yes,channelmode=yes,status=yes');
		repcrewchangeplan.submit();
	}
}
// function printccp1()
// {
	// var printok=1;
	// var setccpno=document.crewchangeplan.ccpno.value;
	// if(document.crewchangeplan.ccpno.value=='')
	// {
		// if(!confirm('CCP No. does not exist! Continue anyway?'))
			// printok=0;
		// else
			// setccpno='Not submitted';
	// }
		
	// if(printok==1)
	// {
		// var myObject = new Object();
	    // myObject.getreportresult = reportresult;
	    // myObject.getvesselname = vesselname;
	    // myObject.getccpno = setccpno;
	    // window.showModalDialog('crewonboard201.php', myObject, 'dialogHeight:768px; dialogWidth:1030px;resizable:yes'); 
	// }
// }
function test()
{
	window.opener=1;
	window.close();
}
function btnadddefault()
{
	with(document.crewchangeplan)
	{
		if(vesselcode.value=='')
		{
			alert('No vessel yet.');
		}
		else
		{
			if(confirm('You are about to add a NEW crew (without replacing anybody). Do you want to continue?'))
			{
				document.getElementById('crewname').value='';
				document.getElementById('btnsave').onclick=function(){chksave();};
				dateemb.disabled=false;
//				dateemb.value=
				datedisemb.disabled=false;
				embcountry.disabled=false;
//				rankcode.value='';
//				vesseltypecode.value='';
				addnewcrew.value=1;
				poolingdata(1);
				selecttab('searchcrew',2);
			}
		}
	}
}
function btndefault()
{
	alert('You can\'t use this button yet');
}

//initialize variables for ajax & other reports

var onboard201result;

function onboard201()
{
	var myObject = new Object();
    myObject.getonboard201result = onboard201result;
    myObject.getcrewname = document.crewchangeplan.crewonboard.value;
    window.showModalDialog('crewonboard201.php', myObject, 'dialogHeight:650px; dialogWidth:500px;status=no'); 
}

</script>\n

</head>\n

<body onload=\"if('$vesselcode'!=''){chkloading('vesselselect',0);}\" style=\"overflow:hidden;\">\n

<form name=\"crewchangeplan\" id=\"crewchangeplan1\" method=\"POST\">\n

<span class=\"wintitle\">CREW CHANGE PLAN - $vsltypecode</span>

<div id=\"crewchangetitle\" style=\"width:100%;overflow:hidden;\">

	<div class=\"navbar\" style=\"width:100%\">
		<table class=\"navbar\" cellspacing=\"0\" cellpadding=\"1\">\n
			<tr>
				<td style=\"width:30px;\">
					&nbsp;&nbsp;&nbsp;Vessel
				</td>
				<td class=\"given\" style=\"width:220px;\">
					<select name=\"vesselcode\" style=\"width:200px;\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onchange=\"chkloading('vesselselect',0);\">\n
						<option value=\"\">-Select-</option>";
						while($rowvessellist=mysql_fetch_array($qryvessellist))
						{
							$vessel1=$rowvessellist['VESSEL'];
							$vesselcode1=$rowvessellist['VESSELCODE'];
							$vesseltypecode1=$rowvessellist['VESSELTYPECODE'];
							$selected="";
							if($vesselcode1==$vesselcode)
								$selected="selected";
							echo "<option $selected value=\"$vesselcode1\">$vessel1 ($vesseltypecode1)</option>\n";
						}
				echo "
					</select>
				</td>
				<td style=\"width:100px;\">
					Batch Limit.
				</td>
				<td style=\"width:40px;\">
					<select name=\"batchlimit\" style=\"width:60px;\" 
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onchange=\"if(vesselcode.value!=''){chkloading('vesselselect',1);}else{alert('No vessel yet!');this.value='';}\">\n
						<option value=\"\">All</option>";
						for($i=1;$i<=20;$i++)
						{
							if($i==1)
								$batchlistshow="1st";
							elseif($i==2)
								$batchlistshow="2nd";
							elseif($i==3)
								$batchlistshow="3rd";
							else 
								$batchlistshow=$i."th";
							echo "<option value=\"$i\">$batchlistshow</option>\n";
						}
				echo "
					</select>
				</td>
				<td style=\"\">
					&nbsp;
				</td>
				<td style=\"width:160px;\">
					Crew Change Plan No.
				</td>
				<td style=\"width:80px;\">
					<input type=\"text\" name=\"ccpno\" size=\"8\" style=\"margin-bottom:3px;\"
						onKeyPress=\"return numbersonly(this, event)\" 
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onblur=\"validateccpno();\">&nbsp;
				</td>
				<td style=\"width:200px;\">
					<img id=\"btngo\" src=\"images/buttons/btn_go_def.gif\" style=\"cursor:pointer;\"
						onmousedown=\"this.src='images/buttons/btn_go_click.gif';\"
						onmouseup=\"this.src='images/buttons/btn_go_def.gif';\"
						onmouseout=\"this.src='images/buttons/btn_go_def.gif';\"
						onclick=\"validateccpno();\">&nbsp;
					<img id=\"btnclear\" src=\"images/buttons/btn_clear_def.gif\" style=\"cursor:pointer;\"
						onmousedown=\"this.src='images/buttons/btn_clear_click.gif';\"
						onmouseup=\"this.src='images/buttons/btn_clear_def.gif';\"
						onmouseout=\"this.src='images/buttons/btn_clear_def.gif';\"
						onclick=\"ccpno.value='';validateccpno();\">&nbsp;
						
					<img id=\"btnprint\" src=\"images/buttons/btn_print_def.gif\" style=\"cursor:pointer;\"
						onmousedown=\"this.src='images/buttons/btn_print_click.gif';\"
						onmouseup=\"this.src='images/buttons/btn_print_def.gif';\"
						onmouseout=\"this.src='images/buttons/btn_print_def.gif';\"
						onclick=\"if(chkbtnloading()==1){if(vesselcode.value==''){alert('You must select a vessel first!');}else{printccp();}}\">
				</td>
			</tr>
		</table>
	</div>
	
</div>
<div id=\"crewchangeheader\" style=\"width:100%;background-color:White;border:1px solid White;\">
	<div style=\"width:49%;overflow:hidden;float:left;background-color:#DC9B3A;\">
		<table class=\"editrow\" cellspacing=\"0\" cellpadding=\"2\" border=1 width=\"100%\">
			<tr>
				<th style=\"font-size:0.9em;font-weight:Bold;\">On-board Crew</th>
				<td colspan=\"2\" align=\"right\" style=\"background-color:Black;\">
					<input type=\"text\" id=\"onboardcrew\" name=\"onboardcrew\" size=\"50\"
						style=\"border:0;background:inherit;font-weight:bold;color:Red;\" readOnly=\"readOnly\">
				</td>
			</tr>
			<tr>
				<th style=\"font-size:0.9em;font-weight:Bold;\">Replaced By</th>
				<td colspan=\"2\" align=\"right\" style=\"background-color:Black;\">
					<input type=\"text\" id=\"crewname\" name=\"crewname\" size=\"50\"
						style=\"border:0;background:inherit;font-weight:bold;color:Yellow;\" readonly=\"readonly\">
				</td>
			</tr>
			<tr>
				<th style=\"font-size:0.9em;font-weight:Bold;\">Embark</th>
				<td valign=\"center\">
					<input type=\"text\" id=\"dateemb\" name=\"dateemb\" size=\"9\" disabled=\"disabled\"
						onkeypress=\"return dateonly(this);\" 
						onblur=\"if(this.value!=''){chkdate(this);}else{this.value='$dateemb'}\" 
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\">
					&nbsp;&nbsp;<span style=\"font-size:0.9em;font-weight:Bold;\"><i>(mm/dd/yyyy)</i></span>
				</td>
				<td rowspan=\"2\" align=\"center\">
					<img id=\"btnadd\" src=\"images/buttons/btn_addcrew_def.gif\" style=\"cursor:pointer;\"
						onmousedown=\"this.src='images/buttons/btn_addcrew_click.gif';\"
						onmouseup=\"this.src='images/buttons/btn_addcrew_def.gif';\"
						onmouseout=\"this.src='images/buttons/btn_addcrew_def.gif';\"
						onclick=\"if(chkbtnloading()==1){btnadddefault();}\">	
				</td>
			</tr>
			<tr>
				<th style=\"font-size:0.9em;font-weight:Bold;\">Disembark</th>
				<td valign=\"center\">
					<input type=\"text\" id=\"datedisemb\" name=\"datedisemb\" size=\"9\" disabled=\"disabled\"
						onkeypress=\"return dateonly(this);\" 
						onblur=\"if(this.value!=''){chkdate(this)}else{this.value='$datedisemb'}\" 
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\">
					&nbsp;&nbsp;<span style=\"font-size:0.9em;font-weight:Bold;\"><i>(mm/dd/yyyy)</i></span>
				</td>
			</tr>
			<tr>
				<th style=\"font-size:0.9em;font-weight:Bold;\">Port</th>
				<td>
					<select name=\"embcountry\" style=\"width:170px;\" disabled=\"disabled\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onchange=\"chkloading('embcountry',10);\">\n
						<option value=\"\">-Country-</option>";
						while($rowembcountry=mysql_fetch_array($qryembcountry))
						{
							$embcountry1=$rowembcountry['PORTCOUNTRY'];
							echo "<option value=\"$embcountry1\">$embcountry1</option>\n";
						}
				echo "
					</select>
				</td>
				<td id=\"embport\">
					<select name=\"embport\" style=\"width:100px;\" disabled=\"disabled\">
						<option value=\"\">-Port-</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>
					<input type=\"checkbox\" $batchchk name=\"shownobatch\" onclick=\"submit();\" /> 
					<span style=\"font-size:0.9em;font-weight:Bold;color:Blue;\">NO BATCH</span>
				</th>
				<td colspan=\"2\">
					<img id=\"btnsave\" src=\"images/buttons/btn_save_def.gif\" style=\"cursor:pointer;\"
						onmousedown=\"this.src='images/buttons/btn_save_click.gif';\"
						onmouseup=\"this.src='images/buttons/btn_save_def.gif';\"
						onmouseout=\"this.src='images/buttons/btn_save_def.gif';\"
						onclick=\"btndefault();\">
					<img id=\"btncancel\" src=\"images/buttons/btn_cancel_def.gif\" style=\"cursor:pointer;\"
						onmousedown=\"this.src='images/buttons/btn_cancel_click.gif';\"
						onmouseup=\"this.src='images/buttons/btn_cancel_def.gif';\"
						onmouseout=\"this.src='images/buttons/btn_cancel_def.gif';\"
						onclick=\"submit();\">
						
						&nbsp;&nbsp;&nbsp;&nbsp;
					<img id=\"btnccpno\" src=\"images/buttons/btn_getccpno_def.gif\" style=\"cursor:pointer;\"
						onmousedown=\"this.src='images/buttons/btn_getccpno_click.gif';\"
						onmouseup=\"this.src='images/buttons/btn_getccpno_def.gif';\"
						onmouseout=\"this.src='images/buttons/btn_getccpno_def.gif';\"
						onclick=\"alert('No Vessel to get CCPNo');\">	
						
					&nbsp;&nbsp;
					Batch <input type=\"text\" name=\"batchnoprint\" size=\"4\" />
					<input type=\"button\" value=\"Print Batch\" onclick=\"printbatch();\" />
				</td>
			</tr>
		</table>
	</div>
	<div id=\"chkdiscrepancy\" style=\"width:50%;overflow:hidden;float:right;\">
		
	</div>
</div>
<div id=\"crewchangedetails\" style=\"width:100%;height:450px;overflow:auto;\">
	
</div>


<div id=\"pooling\" 
	style=\"background:white;z-index:100;position:absolute;left:20px;top:10px;width:980px;height:620px;border:3px solid red;
		display:none;\">
	<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
		<tr>
			<td width=\"90%\"><span class=\"wintitle\">&nbsp;&nbsp;&nbsp;POOLING</span></td>
			<td>
				<span class=\"wintitle\" width=\"60px\">&nbsp;
					<img id=\"btnclosepooling\" src=\"images/buttons/btn_close_def.gif\" 
						style=\"cursor:pointer;vertical-align:middle;\"
						onmousedown=\"this.src='images/buttons/btn_close_click.gif';\"
						onmouseup=\"this.src='images/buttons/btn_close_def.gif';\"
						onmouseout=\"this.src='images/buttons/btn_close_def.gif';\"
						onclick=\"if(document.getElementById('ajaxprogress').style.display=='none'){poolingdata(0);}\">
				</span>
			</td>
		</tr>
	</table>
	
	<div style=\"padding-left:2px;width:485px;height:590px;overflow:hidden;float:left;background:#F2F1EA;\">
		<div class=\"navbar\" style=\"border-bottom:0;\">
			<table cellspacing=\"0\" cellpadding=\"0\">
				<tr>
					<th>Search:</th>
					<th>FName:</th>
					<th>
						<input type=\"text\" name=\"searchfname\" size=\"12\" value=\"$searchfname\" 
							onkeydown=\"if(event.keyCode==13){if(searchfname.value!='' || searchgname.value!=''){selecttab('searchcrew',2);}}\"
							onkeyup=\"this.value=this.value.toUpperCase();\" >
					</th>
					<th>GName:</th>
					<th>
						<input type=\"text\" name=\"searchgname\" size=\"12\" value=\"$searchgname\" 
							onkeydown=\"if(event.keyCode==13){if(searchfname.value!='' || searchgname.value!=''){selecttab('searchcrew',2);}}\"
							onkeyup=\"this.value=this.value.toUpperCase();\" >
					</th>
					<th>
						<img id=\"btnsearchgo\" src=\"images/buttons/btn_go_def.gif\" 
							style=\"cursor:pointer;\"
							onmousedown=\"this.src='images/buttons/btn_go_click.gif';\"
							onmouseup=\"this.src='images/buttons/btn_go_def.gif';\"
							onmouseout=\"this.src='images/buttons/btn_go_def.gif';\"
							onclick=\"if(searchfname.value!='' || searchgname.value!=''){selecttab('searchcrew',2);}\">
					</th>
				</tr>
			</table>
		</div>
		<div class=\"navbar\" style=\"width:100%;height:25px;overflow:hidden;float:left;border-bottom:0;\">
			<table cellspacing=\"0\" cellpadding=\"0\">
				<tr>
					<th>Rank:&nbsp; 
						<select name=\"rankcode\"
							onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
							onchange=\"if(onboardcrew.value==''){rankcodehidden.value=this.value;};chkloading('selectcrew',10);\">";
							while($rowrankcode=mysql_fetch_array($qryrankcode))
							{
								$rankcode1=$rowrankcode['RANKCODE'];
								$rankalias1=$rowrankcode['ALIAS1'];
								echo "<option value=\"$rankcode1\">$rankalias1</option>\n";
							}
					echo "
						</select>
					</th>
					<th>Vessel Type:&nbsp; 
						<select name=\"vesseltypecode\"
							onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
							onchange=\"chkloading('selectcrew',10);\">";
							while($rowvesseltype=mysql_fetch_array($qryvesseltype))
							{
								$vesseltypecode1=$rowvesseltype['VESSELTYPECODE'];
								$vesseltype1=$rowvesseltype['VESSELTYPE'];
								echo "<option value=\"$vesseltypecode1\">$vesseltype1</option>\n";
							}
					echo " 
						</select>
					</th>
				</tr>
			</table>
		</div>
		<div class=\"navbar\" style=\"width:100%;height:25px;overflow:hidden;float:left;border-bottom:0;\">
			<table cellspacing=\"0\" cellpadding=\"0\">
				<tr>
					<th>Crew List</th>
				</tr>
			</table>
		</div>
		
		<div id=\"legend\" class=\"navbar\" style=\"border-top:0;\">
			<div class=\"navbar\"><table cellspacing=\"1\" cellpadding=\"0\"><tr><th>Legend</th></tr></table></div>
		</div>
		<div id=\"tabarrange\" style=\"width:100%;background:#F2F1EA;\">
			
		</div>
		<br>

		<div id=\"poolinglist\" style=\"width:100%;overflow:hidden;\">
			
		</div>
	</div>
	
	
	<div id=\"crewdetails\" style=\"width:50%;height:590px;overflow:hidden;background:White;float:right;display:none;\">
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
		<div id=\"view201list\" style=\"width:100%;display:block;height:500px;\">
		
		</div>
	</div>
</div>



<div id=\"crewchangedate\"
	style=\"background:#DCDCDC;z-index:200;position:absolute;left:300px;top:270px;width:500px;height:350px;
		border:3px solid black;display:none;\">
	<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
		<tr>
			<td width=\"90%\"><span class=\"wintitle\">CHANGE DISEMBARK DATE</span></td>
		</tr>
	</table>
	<br>
	<center>
	<table class=\"editrow\" width=\"80%\" cellspacing=\"0\" cellpadding=\"0\" border=1>
		<tr>
			<th align=\"left\">Crew:</th>
			<th><input type=\"text\" id=\"cname\" name=\"cname\" size=\"30\" 
					style=\"font-size:1.2em;border:0;background:inherit;font-weight:bold;color:blue;\" readOnly=\"readOnly\"> </th>
		</tr>
		<tr>
			<th align=\"left\">Embark Date:<br />
				<input type=\"text\" id=\"existdateemb\" name=\"existdateemb\" size=\"12\" 
					style=\"font-size:1.2em;border:0;background:inherit;font-weight:bold;color:blue;\" readOnly=\"readOnly\">
			</th>
			<th align=\"left\">Disembark Date:<br />
				<input type=\"text\" id=\"existdate\" name=\"existdate\" size=\"12\" 
					onclick=\"newdate.value=this.value;\"
					style=\"font-size:1.2em;border:0;background:inherit;font-weight:bold;color:blue;\" readOnly=\"readOnly\">
			</td>
		</tr>
		<tr>
			<td align=\"left\">Status</td>
			<td align=\"center\">
				<input type=\"text\" id=\"changeremarks\" name=\"changeremarks\" size=\"12\" 
					style=\"font-size:1.5em;border:0;background:inherit;font-weight:bold;color:Red;\" readOnly=\"readOnly\">
			</td>
		</tr>
		<tr>
			<td align=\"left\">Changed To (Disembark Date)</td>
			<td align=\"center\">
				<input type=\"text\" id=\"changeto\" name=\"changeto\" size=\"12\" 
					style=\"border:0;background:inherit;font-weight:bold;color:Green;\" readOnly=\"readOnly\">
			</td>
		</tr>
		<tr>
			<td align=\"left\">Reason</td>
			<td align=\"center\">
				<input type=\"text\" id=\"changereason\" name=\"changereason\" size=\"12\" 
					style=\"border:0;background:inherit;font-weight:bold;color:Green;\" readOnly=\"readOnly\">
			</td>
		</tr>
		<tr>
			<td align=\"left\">Changed By</td>
			<td align=\"center\">
				<input type=\"text\" id=\"changeby\" name=\"changeby\" size=\"20\" 
					style=\"border:0;background:inherit;font-weight:bold;color:Green;\" readOnly=\"readOnly\">
			</td>
		</tr>
		<tr>
			<td align=\"left\">Changed Date</td>
			<td align=\"center\">
				<input type=\"text\" id=\"changedate\" name=\"changedate\" size=\"12\" 
					style=\"border:0;background:inherit;font-weight:bold;color:Green;\" readOnly=\"readOnly\">
			</td>
		</tr>
		<tr>
			<th colspan=\"2\">&nbsp;</th>
		</tr>
		<tr>
			<th>New Date:</th>
			<td>
				<input type=\"text\" id=\"newdate\" name=\"newdate\" size=\"10\"
					onkeypress=\"return dateonly(this);\" 
					onblur=\"if(this.value!=''){chkdate(this)}\" 
					onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\">
					
				&nbsp;&nbsp;&nbsp;
				<span style=\"font-weight:Bold;\"><i>(mm/dd/yyyy)</i></span>
			</td>
		</tr>
<!--
		<tr>
			<th>Reason:</th>
			<td>
				<select name=\"disembreasoncode\" style=\"width:200px;\"
					onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\">\n
					<option value=\"\">-Select-</option>";
					while($rowdisemb=mysql_fetch_array($qrydisemb))
					{
						$reason1=$rowdisemb['REASON'];
						$disembreasoncode1=$rowdisemb['DISEMBREASONCODE'];
						echo "<option value=\"$disembreasoncode1\">$reason1</option>\n";
					}
			echo "
				</select>
			</td>
		</tr>
-->
		<tr>
			<th>Estimated Date:</th>
			<td>
				<input type=\"text\" id=\"estdate\" name=\"estdate\" size=\"10\"
					onkeypress=\"return dateonly(this);\" 
					onblur=\"if(this.value!=''){chkdate(this)}\" 
					onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\">
					
				&nbsp;&nbsp;&nbsp;
				<span style=\"font-weight:Bold;\"><i>(mm/dd/yyyy)</i></span>
			</td>
		</tr>
		<tr>
			<td colspan=\"2\" align=\"center\">
				<img id=\"btnchangesave\" src=\"images/buttons/btn_save_def.gif\" style=\"cursor:pointer;\"
					onmousedown=\"this.src='images/buttons/btn_save_click.gif';\"
					onmouseup=\"this.src='images/buttons/btn_save_def.gif';\"
					onmouseout=\"this.src='images/buttons/btn_save_def.gif';\"
					onclick=\"if(getstringdatetime(newdate.value)>getstringdatetime(existdateemb.value)){
						chkchangesave();}else{alert('New date is earlier than Embark date!');newdate.value='';newdate.focus();}\">
				<img id=\"btnchangeclose\" src=\"images/buttons/btn_close_def.gif\" 
						style=\"cursor:pointer;\"
						onmousedown=\"this.src='images/buttons/btn_close_click.gif';\"
						onmouseup=\"this.src='images/buttons/btn_close_def.gif';\"
						onmouseout=\"this.src='images/buttons/btn_close_def.gif';\"
						onclick=\"embccidhidden.value='';document.getElementById('crewchangedate').style.display='none';
							newdate.value='';switchajax(0);\">
			</td>
		</tr>
	</table>
	</center>
</div>
<div id=\"changebatch\"
	style=\"background:silver;z-index:200;position:absolute;left:600px;top:100px;width:400px;height:180px;
		border:3px solid black;display:none;\">
	<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
		<tr>
			<td width=\"90%\"><span class=\"wintitle\">CHANGE BATCH NO</span></td>
		</tr>
	</table>
	<br>
	<center>
	<table class=\"editrow\" cellspacing=\"0\" cellpadding=\"0\">
		<tr>
			<th>Crew.</th>
			<td>&nbsp;
				<input type=\"text\" id=\"crewselect\" name=\"crewselect\" size=\"30\" 
					style=\"border:0;background:inherit;font-weight:bold;color:blue;\" readOnly=\"readOnly\">
			</td>
		</tr>
		<tr>
			<th>Current Batch.</th>
			<td>&nbsp;
				<input type=\"text\" id=\"currentbatch\" name=\"currentbatch\" size=\"12\" 
					style=\"border:0;background:inherit;font-weight:bold;color:blue;\" readOnly=\"readOnly\">
			</td>
		</tr>
		<tr>
			<th>Select Batch.</th>
			<td>&nbsp;
				<select name=\"batchselect\" style=\"\" 
					onchange=\"chkchangebatch();\">
					<option value=\"\">-Select-</option>
					<option value=\"0\">No Batch</option>";
					for($i=1;$i<=20;$i++)
					{
						if($i==1)
							$batchlistshow="1st";
						elseif($i==2)
							$batchlistshow="2nd";
						elseif($i==3)
							$batchlistshow="3rd";
						else 
							$batchlistshow=$i."th";
						echo "<option value=\"$i\">$batchlistshow</option>\n";
					}
			echo "
				</select>
			</td>
		</tr>
		<tr>
			<th>&nbsp;</th>
		</tr>
		<tr>
			<th>&nbsp;</th>
			<td>
				<img id=\"btnchangebatchclose\" src=\"images/buttons/btn_close_def.gif\" 
						style=\"cursor:pointer;\"
						onmousedown=\"this.src='images/buttons/btn_close_click.gif';\"
						onmouseup=\"this.src='images/buttons/btn_close_def.gif';\"
						onmouseout=\"this.src='images/buttons/btn_close_def.gif';\"
						onclick=\"embccidhidden.value='';document.getElementById('changebatch').style.display='none';
							switchajax(0);\">
			</td>
		</tr>
	</table>
	</center>
</div>";
include("veritas/include/ajaxprogress.inc");

echo "
<input type=\"hidden\" name=\"actionajax\">
<input type=\"hidden\" name=\"prev201\">
<input type=\"hidden\" name=\"prevpool\">
<br>
<input type=\"hidden\" name=\"rankcodehidden\" value=\"\">
<input type=\"hidden\" name=\"poolchoice\">
<input type=\"hidden\" name=\"embapplicantnohidden\">
<input type=\"hidden\" name=\"ccidhidden\">
<input type=\"hidden\" name=\"embccidhidden\">
<input type=\"hidden\" name=\"embporttemp\">
<input type=\"hidden\" name=\"sortby\">
<input type=\"hidden\" name=\"orderby\">
<input type=\"hidden\" name=\"crewonboard\">
<input type=\"hidden\" name=\"addnewcrew\">
<input type=\"hidden\" name=\"batchnohidden\">
<input type=\"hidden\" name=\"promoteembdate\">
<input type=\"hidden\" name=\"promoteccid\">
<input type=\"hidden\" name=\"promoteappno\">
<input type=\"hidden\" name=\"firstentry\" value=\"$firstentry\">

</form>
<form name=\"repcrewchangeplan\" action=\"repcrewchangeplan.php\" target=\"repcrewchangeplan\" method=\"POST\">
	<input type=\"hidden\" name=\"reportresult\">
	<input type=\"hidden\" name=\"ccpno\">
	<input type=\"hidden\" name=\"vesselname\">
</form>
<form name=\"repcrewchangeplanbatch\" action=\"repcrewchangeplanbatch.php\" target=\"repcrewchangeplanbatch\" method=\"GET\">
	<input type=\"hidden\" name=\"vslcode\">
	<input type=\"hidden\" name=\"bno\">
</form>


</body>\n
</html>\n";

?>
