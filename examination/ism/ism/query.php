<?php
if ((!isset($_GET['crewcode']) || trim($_GET['crewcode']) == '') && (!isset($_GET['takeno']) || trim($_GET['takeno']) == ''))
{
	header("location:../scheduler.php");
}
	include('connection/conn.php');
	$crewcode=$_GET['crewcode'];
	$takeno=$_GET['takeno'];
	
	
	$xcode="Select type.type, users.fname, users.gname, users.mname from users, type where type.id = users.exam and users.crewcode='$crewcode' and takeno = '$takeno'";
	$query_result=mysql_query("$xcode",$conn);	
	$exam=mysql_result($query_result,0,"type.type");	
	$fname=mysql_result($query_result,0,"users.fname");	
	$gname=mysql_result($query_result,0,"users.gname");	
	$mname=mysql_result($query_result,0,"users.mname");
	

	$OptionID=$_POST['OptionID']; 
	$xid=$_POST['xid'];
	
	foreach ( $xid as $usersid )
	{ 
		$optionvalue=$OptionID[$usersid];
			
		$xquesry = mysql_query("select questions.anscorrect from users_exam, questions where users_exam.qid = questions.id and crewcode='$crewcode' and users_exam.id = '$usersid'");
		
		$v=mysql_num_rows($xquesry);
		if ($v != '0')
		{
			$anscorrect=mysql_result($xquesry,0,"questions.anscorrect");
			if ($anscorrect == $optionvalue)
			{
				$f = '1';
			}
			else
			{
				$f = '0';
			}
		}
		else
		{
			$f = '0';
		}
			
		mysql_query("update users_exam set ans='$optionvalue', correct='$f' where crewcode='$crewcode' and id='$usersid'");
	} 


$xcoded="select examtype.totalno from users, examtype, type where type.id = users.exam and type.type=examtype.examname and users.crewcode='$crewcode'";
$query_resultd=mysql_query("$xcoded",$conn);	
$totalnof=mysql_result($query_resultd,0,"examtype.totalno");

$query_resultf=mysql_query("select count(correct) As correct_answer from users_exam where crewcode='$crewcode' and take='$takeno' and correct ='1'",$conn);	
$scoref=mysql_result($query_resultf,0,"correct_answer");
$percentscf = (($scoref / $totalnof)*100);
$percentscoref = round($percentscf, 2);	

		$to = "rolando.durana@vom-group.com";
		$to2 = "rynelle.coronacion@veritas.com.ph, jien@veritas.com.ph, jhonald.rose@veritas.com.ph";
		$subject = "ISM Online Examination";
		$message = "RESULTS OF EXAMINATION\n
					Name: ".$gname." ".$mname.". ".$fname."\n
					Type of Exam: ".$exam."\n
					To view and print the result, visit: 
					http://www.veritas.com.ph/examination/ism/ism/print.php?crewcode=". $crewcode ."&takeno=".$takeno."\n
					Thank you.";
		$from = "ISM Online Examination <ism_onlineexam@veritas.com.ph>";
		$headers = "From: $from";
		mail($to,$subject,$message,$headers);
		mail($to2,$subject,$message,$headers);

echo "<script type='text/javascript'>alert('Your Score for ". $exam ." exam is ". $percentscoref ."%. Thank you!')</script>" ;
echo "<script language=\"javascript\">window.location.href = 'print.php?crewcode=" . $crewcode . "&takeno=" . $takeno . "';</script>";
?>
<html>
<head>
<title>ISM - International Safety Management</title>
</head>
<body style="background-image:url(images/ism.gif); background-repeat:repeat">
</body>
</html>