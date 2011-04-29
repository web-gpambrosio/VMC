<?php
session_start();

if ((!isset($_GET['empno']) || trim($_GET['empno']) == ''))
{
header("location:../scheduler.php");
}
include('connection/conn.php');
$empno=$_GET['empno'];
$xcrewcode = $_GET['crewcode'];
$idx = $_GET['id'];

$xexamdatex = date("Y-m-d");

	$vav=mysql_query("Select id, type from type where id='$idx'");
	if (mysql_num_rows($vav) > 0)
	{
	$examid=mysql_result($vav,0,"id");
	$examnamem=mysql_result($vav,0,"type");
	}

$search_crew_table="Select fname, gname, mname, bdate, crewcode from crew where crewcode='".$xcrewcode."'";
$search_crew_query=mysql_query("$search_crew_table",$conn);
$search_crew_row=mysql_num_rows($search_crew_query);
if ($search_crew_row!='0')
{
$xfname = mysql_result($search_crew_query,0,"fname");
$xgname = mysql_result($search_crew_query,0,"gname");
$xmname = mysql_result($search_crew_query,0,"mname");
$pbdate = mysql_result($search_crew_query,0,"bdate");
$pcrewcode = mysql_result($search_crew_query,0,"crewcode");
$xbdate = date("m/d/Y",strtotime($pbdate));
}

$n="Select count(takeno) As takeno from users where crewcode='".$xcrewcode."' order by takeno desc limit 1";
$m=mysql_query("$n",$conn);
$o=mysql_num_rows($m);
if ($o!='0')
{
$p = mysql_result($m,0,"takeno");
$xtakeno = $p + 1;
}
else
{
$xtakeno = "1";
}
	
$xcode="Select id, lname, fname, mname, position from scheduler where empno='$empno'";
$query_result=mysql_query("$xcode",$conn);
$id=mysql_result($query_result,0,"id");
$lname=mysql_result($query_result,0,"lname");
$fname=mysql_result($query_result,0,"fname");
$mname=mysql_result($query_result,0,"mname");
$position=mysql_result($query_result,0,"position");


/*if ($_POST['submit'])
{*/
	

	$xexamname = $_POST['xexamname'];
	$txtvessel = $_POST['txtprincipal'];
	$xrand = $_POST['xrand'];	
	

		$vessel_principal_manipulate = mysql_query("select pbw.type As xpbw, vessel.principal As principal, vessel.type As type, type.type As type2x from vessel, type, pbw where vessel.id='$txtvessel' and type.id='$xexamname' and vessel.type = pbw.id");
	$vessel_principal_manipulate_row=mysql_num_rows($vessel_principal_manipulate);
	
		if ($vessel_principal_manipulate_row != "0")
		{
			$txtprincipal=mysql_result($vessel_principal_manipulate,0,"principal");
			$txtprincipal_type=mysql_result($vessel_principal_manipulate,0,"type");
			$examname_exam=mysql_result($vessel_principal_manipulate,0,"type2x");
			$xxpbw=mysql_result($vessel_principal_manipulate,0,"xpbw");
		}
		else
		{
			echo '<script type="text/javascript">alert("Program Error!\n" + "Please report this error to Veritas IT Department.")</script>' ;
			echo "<script language=\"javascript\">window.location.href='connection/mylogout.php'</script>";
			mysql_close($conn);
		}
	

		$qwe = mysql_query("select distinct idcat from exam_cat where examname='$examname_exam' and principal='$txtprincipal' and type='$txtprincipal_type'");
		$qwe1=mysql_num_rows($qwe);
		
		if (($qwe1 != "") || ($qwe1 != 0))
		{

			for ($iidcat=0; $iidcat<$qwe1; $iidcat++)
			{
				
				$idcat_examcat=mysql_result($qwe,$iidcat,"idcat");
				
				$ax1 = mysql_query("select sum(total) As sum_total from exam_cat where idcat='$idcat_examcat' and examname='$examname_exam' and principal='$txtprincipal' and type='$txtprincipal_type'");
				$sum_total1=mysql_result($ax1,0,"sum_total");
				
				$select2 = mysql_query("select id from questions where category='$idcat_examcat' and examtype='$xexamname' and principal='$txtprincipal' and type='$txtprincipal_type' order by rand()");
				
				$select2ssss=mysql_num_rows($select2);
				
				if ($select2ssss != "0")
				{
					for ($inumx=0; $inumx<$sum_total1; $inumx++)
					{
						
					$catxcv=mysql_result($select2,$inumx,"id");
						mysql_query("insert into users_exam (crewcode, qid, take, examdate) values ('$xcrewcode','$catxcv','$xtakeno','$xexamdatex')") or die(mysql_error());
					}
				}
				
				else
				{
					echo '<script type="text/javascript">alert("Questionares Error!\n" + "Type of Vessel: '. $xxpbw .'\n" + "Please report this error to the ISM Adminitrator")</script>';
					echo "<script language=\"javascript\">window.location.href='scheduler_page.php?empno=$empno'</script>";
					mysql_close($conn);
				}

			}
		}
		else
		{
			echo '<script type="text/javascript">alert("Questionares Error!\n" + "Type of Exam: '. $examname_exam .'\n" + "Please report this error to the ISM Adminitrator")</script>' ;
			echo "<script language=\"javascript\">window.location.href='scheduler_page.php?empno=$empno'</script>";
			mysql_close($conn);
		}
			
$vessel_search_principaltoid=mysql_query("Select principal from vessel where id='".$txtvessel."'");
$vessel_search_principaltoid_row=mysql_num_rows($vessel_search_principaltoid);

if ($vessel_search_principaltoid_row!='0')
{
$principalj = mysql_result($vessel_search_principaltoid,0,"principal");
}
		
$queryv = "insert into users (crewcode, passcode, exam, takeno, fname, gname, mname, vessel, bdate, principal) values ('$xcrewcode', '$xrand', '$xexamname', '$xtakeno', '$xfname', '$xgname', '$xmname', '$txtvessel', '$pbdate', '$principalj')";
$resultv = mysql_query($queryv) or die ("Error in query: $query. " . mysql_error());
		
		echo "<script type='text/javascript'>alert('Exam Activated!')</script>" ;
		echo "<script language=\"javascript\">window.location.href='crew_page.php?empno=" . $empno . "'</script>";
		mysql_close($conn);

/*}*/
?>