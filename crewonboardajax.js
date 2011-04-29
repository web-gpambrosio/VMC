function handleHttpResponse() 
{ 
	if (http.readyState == 4) 
	{
		if (http.status == 200) 
		{
			var responsedata=http.responseText;
			// Split the comma delimited response into an array 
			results = http.responseText.split("|");
			var getfunction = "fill" + results[0]  + "(\"" + results[1] + "\")";
			
			document.getElementById('ajaxprogress').style.display='none';
			eval(getfunction);
			isWorking = false;
		}
	}
}

var isWorking = false; // this is used to know if ajax is requesting or not...
					   // you can use this if you want to stop everything in your program while requesting
function getvalues() 
{
	if(!isWorking) // if isWorking is true, do not run request
	{
		var actionajax = document.crewonboard.actionajax.value; 
		
		document.getElementById('ajaxprogress').style.display='block'; // DISABLE ALL WHILE LOADING
		
		//fill URL

//		var embapplicantnohidden = "&embapplicantnohidden=" + document.crewonboard.applicantnohidden.value;

//		var fillurl = embapplicantnohidden;
		http.open("GET", url + actionajax + fillurl + "&kups=" + new Date().getTime(), true); //kups is protection for caching (random)
		http.onreadystatechange = handleHttpResponse; 
		isWorking = true;
		http.send(null);
	}
}
function getHTTPObject() 
{ 
	var xmlhttp; 

/*@cc_on 
@if (@_jscript_version >= 5) 
try 
	{ 
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP"); 
	} 
	catch (e) { 
		try 
			{ 
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); 
			} 
		catch (E) { 
			xmlhttp = false; 
			} 
	} 
@else 
	xmlhttp = false; 
@end @*/  

if (!xmlhttp && typeof XMLHttpRequest != "undefined") 
{
	try { 
		xmlhttp = new XMLHttpRequest(); 
	} catch (e) { 
		xmlhttp = false; 
		} 
	} return xmlhttp; 
} 

var http = getHTTPObject(); // We create the HTTP Object 

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