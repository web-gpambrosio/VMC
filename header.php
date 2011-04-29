<?php

$currentdate = date('His');

if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
	
if (isset($_POST['firstloadhidden']))
	$firstloadhidden = $_POST['firstloadhidden'];
	
//	$employeeid = "JMM";

//$qrygetaccesshdr = mysql_query("SELECT distinct MENU
//								FROM access a
//								WHERE EMPLOYEEID='$employeeid'
//								ORDER BY MENU") or die(mysql_error());

$qrygetaccesshdr = mysql_query("SELECT distinct al.MENU
								FROM access a
								LEFT JOIN accesslevels al ON a.ACCESSID=al.ACCESSID
								WHERE a.EMPLOYEEID='$employeeid'
								ORDER BY al.POSITION") or die(mysql_error());

echo "
<html>
<head>
<title>
VERIPRO - Veritas Resource Integration Program
</title>

<link rel=\"stylesheet\" type=\"text/css\" href=\"veripro.css\">
<script type='text/javascript' src='../veritas/veripro.js'></script>
<script language=\"javascript\" src=\"popcalendar.js\"></script>

<script>
	
	function autoclose2()
	{
		window.opener = 2;
		window.close();
	}

	function checkwindow(phpfile,newwin)
	{
		var mydate=new Date();
		var z=mydate.getTime();
//		document.getElementById('nav').id='nav1';
//		document.getElementById('nav1').style.display = 'none';
//		document.getElementById('nav1').id='nav';
//		document.getElementById('nav').style.display = 'block';
//		alert(mydate.getTime());

		if(newwin==1)
		{
			if(phpfile=='generatecertificate.php')
				openWindow(phpfile, z ,900, 650);
			else
				openWindow(phpfile, z ,0, 0);
		}
		else
		{
//			document.getElementById('welcome').style.display = 'none';
//			document.getElementById('setup').style.display = 'none';
//			document.getElementById('report').style.display = 'none';
//			document.getElementById(phpfile).style.display = 'block';
			document.getElementById('showbody').src=phpfile;
		}
	
	}

</script>
</head>
<body style=\"overflow:hidden;background-color:White;margin-top:0;\" 
	onload=\"if('$firstload'== '1' && '$firstloadhidden'==''){document.formmain.firstloadhidden.value='1';opener.autoclose();}\">
	<form name=\"formmain\" method=\"POST\">
	<input type=\"hidden\" name=\"firstloadhidden\" value=\"$firstloadhidden\" />
	<div style=\"float:left;width:1000px;height:140px;background-image:url(images/header.gif);background-repeat:no-repeat;\">
		<div id=\"nav\" style=\"z-index:2;\">";

		
		echo "	
		<ul class=\"menu\">\n
		";
		
		if (empty($managementcode))
		{
			echo "
			<li style=\"background-color:#DCDCDC;font-size:1em;font-weight:bold;\"><a href=\"#\" onclick=\"checkwindow('home.php','0');\">HOME</a>\n</li>\n
			";
		}
		else 
		{
			echo "
			<li style=\"background-color:#DCDCDC;font-size:1em;font-weight:bold;\"><a href=\"#\" onclick=\"checkwindow('principal.php?mcode=$managementcode','0');\">HOME</a>\n</li>\n
			";
		}
		
		while ($rowgetaccesshdr=mysql_fetch_array($qrygetaccesshdr))
		{
			$menu = $rowgetaccesshdr["MENU"];
			
			echo "
			<li class=\"submenu\" style=\"background-color:#DCDCDC;font-size:1em;font-weight:bold;\"><a href=\"#\">$menu</a>\n";	
				
			$qrygetaccesscrew = mysql_query("SELECT SUBMENU1,SUBMENU2,SUBMENU3,PHPFILE,NEWWINDOW
								FROM access a
								LEFT JOIN accesslevels al ON al.ACCESSID=a.ACCESSID
								WHERE a.EMPLOYEEID='$employeeid' AND al.MENU='$menu' 
								ORDER BY POSITION,SUBMENU1,SUBMENU2,SUBMENU3") or die(mysql_error());
			$submenu1tmp = "";
			$submenu2tmp = "";
			$submenu3tmp = "";
			$endlevel = 0;
			
			while($rowgetaccesscrew = mysql_fetch_array($qrygetaccesscrew))
			{
				$putarrow = "";
				
				$submenu1 = $rowgetaccesscrew["SUBMENU1"];
				$submenu2 = $rowgetaccesscrew["SUBMENU2"];
				$submenu3 = $rowgetaccesscrew["SUBMENU3"];
				$phpfile = $rowgetaccesscrew["PHPFILE"];
				$newwin = $rowgetaccesscrew["NEWWINDOW"];
				
				if ($submenu2tmp != $submenu2 && !empty($submenu3tmp))
				{
						echo "</li></ul><!--1 -->\n";
					$submenu3tmp = "";
				}
	
				if ($submenu1tmp != $submenu1 && !empty($submenu2tmp))
				{
					echo "</li></ul><!--2 -->\n";
					$submenu2tmp = "";
					$submenu3tmp = "";
				}
	
				if ($submenu1tmp == $submenu1)
				{
					if ($submenu2tmp == $submenu2)
					{	
						if (!empty($submenu3))
							$putarrow = "class=\"arrow\"";
						else 
							$putarrow = "";
						if (empty($submenu3tmp))
							echo "<ul class=\"submenu3\">\n";
						else 	
							echo "</li>\n";
						echo "<li>\n<a href=\"#\" onclick=\"checkwindow('$phpfile','$newwin');\">$submenu3</a>\n";
						$endlevel = 3;
					}
					else 
					{
						if (!empty($submenu3))
							$putarrow = "class=\"arrow\"";
						else 
							$putarrow = "";
						if (empty($submenu2tmp))
							echo "<ul class=\"submenu2\">\n";
						else 	
							echo "</li>\n";
							
						echo "<li $putarrow>\n<a href=\"#\" onclick=\"checkwindow('$phpfile','$newwin');\">$submenu2</a>\n";
						$endlevel = 2;
					}
				}
				else 
				{
					if (empty($submenu1tmp))
						echo "<ul class=\"submenu1\">\n";
					
					if (!empty($submenu2))
						$putarrow = "class=\"arrow\"";
					else 
						$putarrow = "";
					echo "<li $putarrow>\n<a href=\"#\" onclick=\"checkwindow('$phpfile','$newwin');\">$submenu1</a>\n";
					$endlevel = 1;
					if (!empty($submenu2))
					{		
						if (empty($submenu2tmp))
							echo "<ul class=\"submenu2\">\n";
						if (!empty($submenu3))
							$putarrow = "class=\"arrow\"";
						else 
							$putarrow = "";
						echo "<li $putarrow>\n<a href=\"#\" onclick=\"checkwindow('$phpfile','$newwin');\">$submenu2</a>\n";
						
						if (!empty($submenu3))
						{
							if (empty($submenu3tmp))
								echo "<ul class=\"submenu3\">\n";
							echo "<li>\n<a href=\"#\" onclick=\"checkwindow('$phpfile','$newwin');\">$submenu3</a>\n";
						}
						$endlevel = 2;
					}
					
				}
	
				$submenu1tmp = $submenu1;
				$submenu2tmp = $submenu2;
				$submenu3tmp = $submenu3;
				
			}
			
			for ($i=1;$i<=$endlevel;$i++)
			{
				echo "</li>\n</ul><!--vvv$i -->\n"; 
			}
			
			echo "</li>"; 
		}
		
		echo "</ul>";

echo "
		</div>
	</div>
";
?>