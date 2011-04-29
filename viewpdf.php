<?php
include('veritas/connectdb.php');
$getimage=$_GET["ttlimage"];
echo "

<html>
<title>View PDF</title>
<head>

</head>

<body>

<center>
<iframe id=\"showimage\" src=\"\" height=\"98%\" width=\"98%\"></iframe>
</center>

<script>
	var oMyObject = window.dialogArguments;
	var getimage = oMyObject.getimage;
	document.getElementById('showimage').src=getimage;
	if('$getimage'!='')
		document.title='$getimage';
</script>

</body>
";
					
?>