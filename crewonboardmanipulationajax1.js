
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
	alert('a');
	if(document.crewonboard.actionajax.value=="viewonboard201")
	{
		onboard201result=results[2];
		onboard201();
	}
}

function fillcrewinfo(x)
{
	with (document.crewonboard)
	{
		xcrewname.value=
		xembdate.value=
		xdisembdate.value=
		xembport.value=
		
		manembdate.value=
		mandisembdate.value=
		manreasoncode.value=
		manpnireason.value=
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