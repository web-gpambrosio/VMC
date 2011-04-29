function filldoctype(x)
{
	document.getElementById('imgrename').innerHTML=results[1];
//	if(document.crewchangeplan.embporttemp.value)
//	{
//		document.crewchangeplan.embport.value=document.crewchangeplan.embporttemp.value;
//		document.crewchangeplan.embporttemp.value='';
//	}
}
function fillview201(x)
{
//	alert('y');
//	document.getElementById('selectname').innerHTML=name201;
//	document.getElementById('view201list').innerHTML=results[2];
//    alert('u');
	if(document.filemanipulation)
	{
		if(document.filemanipulation.actionajax.value=='viewonboard201')
		{
			onboard201result=results[2];
			onboard201();
		}
	}
	if(document.filemanipulationcleanup)
	{
		if(document.filemanipulationcleanup.actionajax.value=='viewonboard201')
		{
			onboard201result=results[2];
			onboard201();
		}
	}
}
function onboard201()
{
	var myObject = new Object();
    myObject.getonboard201result = onboard201result;
    if(document.filemanipulation)
    	myObject.getcrewname = document.filemanipulation.crewname.value;
    if(document.filemanipulationcleanup)
    	myObject.getcrewname = document.filemanipulationcleanup.crewname.value;
    window.showModalDialog('crewonboard201.php', myObject, 'dialogHeight:650px; dialogWidth:500px;status=no'); 
}