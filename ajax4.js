function handleHttpResponse() 
{ 
	if (http.readyState == 4) 
	{
		if (http.status == 200) 
		{
			var responsedata=http.responseText;
			// Split the comma delimited response into an array 
			results = http.responseText.split("|");
			if(results[1])
				var includex=results[1];
			else
				var includex="";
				
			var getfunction = "fill" + results[0]  + "('" + includex + "')";
//			alert(results);
			eval(getfunction);
			switchajax(0);
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
		switchajax(1); // DISABLE ALL WHILE LOADING
		
//		alert(fillurl);
		http.open("GET", url + fillurl + "&kups=" + new Date().getTime(), true); //kups is protection for caching (random)
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


