<?php

include("veritas/connectdb.php");
include("veritas/include/stylephp.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
	
$formname = "formreportlevels";
$formtitle = "REPORT LEVELS SETUP";

	

//POSTS

if (isset($_POST['reportid']))
	$reportid = $_POST['reportid'];
else 
	$reportid = 0;
	
if (isset($_POST['selreportid']))
	$selreportid = $_POST['selreportid'];
	
if (isset($_POST['selsubreportid']))
	$selsubreportid = $_POST['selsubreportid'];
	
if (isset($_POST['empid']))
	$empid = $_POST['empid'];
	
if (isset($_POST['reportgroup']))
	$reportgroup = $_POST['reportgroup'];
	
	
	
switch ($actiontxt)
{
	case "tocurrent":
	
			$qryaddcurrent = mysql_query("INSERT INTO reportaccess(EMPLOYEEID,REPORTID,SUBREPORTID,MADEBY,MADEDATE)
										VALUES('$empid',$selreportid,$selsubreportid,'$employeeid','$currentdate')
									") or die(mysql_error());
		
		break;
		
	case "toavailable":
		
			$qrytoavailable = mysql_query("DELETE FROM reportaccess 
										WHERE EMPLOYEEID='$empid' AND REPORTID=$selreportid 
										AND SUBREPORTID=$selsubreportid
										") or die(mysql_error());
		
		break;
		
	case "reportgroup":
		
			//DELETE ALL CURRENT REPORT ACCESS
			$qryresetaccess = mysql_query("DELETE FROM reportaccess WHERE EMPLOYEEID='$empid'") or die(mysql_error());
			
			//POPULATE REPORT ACCESS BASED ON REPORT GROUPING(LEVELS)
			$qryapplygroup = mysql_query("SELECT REPGROUPNO,REPORTID,SUBREPORTID 
										FROM reportlevels
										WHERE REPGROUPNO=$reportgroup
										ORDER BY REPORTID,SUBREPORTID") or die(mysql_error());
			
			while ($rowapplygroup = mysql_fetch_array($qryapplygroup))
			{
				$xreportid = $rowapplygroup["REPORTID"];
				$xsubreportid = $rowapplygroup["SUBREPORTID"];
				
				$qryinsertaccess = mysql_query("INSERT INTO reportaccess(EMPLOYEEID,REPORTID,SUBREPORTID,MADEBY,MADEDATE)
												VALUES('$empid',$xreportid,$xsubreportid,'$employeeid','$currentdate')
												") or die(mysql_error());
			}
			
			echo "<script>alert('Report Group No. $reportgroup was successfully applied!');</script>";
		
		break;
}
	

/* LISTINGS  */

$qryemployeelist = mysql_query("SELECT EMPLOYEEID,CONCAT(FNAME,', ',GNAME,' ',MNAME) AS NAME FROM employee ORDER BY FNAME") or die(mysql_error());
$empselect = "<option value=\"\">--Select One--</option>";
$selected = "";
while ($rowemployeelist = mysql_fetch_array($qryemployeelist))
{
	$eid = $rowemployeelist["EMPLOYEEID"];
	$ename = $rowemployeelist["NAME"];
	
	if ($eid == $empid)
		$selected = "SELECTED";
	else 
		$selected = "";
	
	$empselect .= "<option $selected value=\"$eid\">$ename&nbsp;($eid)</option>";
}

$qryreportshdrlist = mysql_query("SELECT REPORTID,REPORT FROM reportshdr") or die(mysql_error());
$hdrselect = "<option value=\"0\">--All Current--</option>";
$selected = "";
while ($rowreportshdrlist = mysql_fetch_array($qryreportshdrlist))
{
	$rid = $rowreportshdrlist["REPORTID"];
	$rep = $rowreportshdrlist["REPORT"];
	
	if ($rid == $reportid)
		$selected = "SELECTED";
	else 
		$selected = "";
	
	$hdrselect .= "<option $selected value=\"$rid\">$rep&nbsp;</option>";
}

$qryreportlevellist = mysql_query("SELECT DISTINCT REPGROUPNO FROM reportlevels") or die(mysql_error());
$levelselect = "<option value=\"\">--Select One--</option>";
$selected = "";
while ($rowreportlevellist = mysql_fetch_array($qryreportlevellist))
{
	$repgroup = $rowreportlevellist["REPGROUPNO"];
	
	if ($repgroup == $reportgroup)
		$selected = "SELECTED";
	else 
		$selected = "";
	
	$levelselect .= "<option $selected value=\"$repgroup\">$repgroup</option>";
}



if (!empty($empid))
{
	if ($reportid != 0)
	{
		$wherereport = "AND rd.REPORTID=$reportid";
	}
	else 
		$wherereport = "";
	
	
	$qryreportavailable = mysql_query("SELECT rd.REPORTID,rh.REPORT,rd.SUBREPORTID,rd.SUBREPORT
							FROM reportsdtl rd 
							LEFT JOIN reportshdr rh ON rh.REPORTID=rd.REPORTID
							LEFT JOIN reportaccess ra ON ra.REPORTID=rd.REPORTID AND ra.SUBREPORTID=rd.SUBREPORTID AND ra.EMPLOYEEID='$empid'
			 				WHERE ra.REPORTID IS NULL $wherereport
							ORDER BY rd.REPORTID,rd.SUBREPORTID
							") or die(mysql_error());
	
	$qryreportcurrent = mysql_query("SELECT rd.REPORTID,rh.REPORT,rd.REPORTID,rd.SUBREPORTID,rd.SUBREPORT
							FROM reportsdtl rd
							LEFT JOIN reportshdr rh ON rh.REPORTID=rd.REPORTID
							LEFT JOIN reportaccess ra ON ra.REPORTID=rd.REPORTID AND ra.SUBREPORTID=rd.SUBREPORTID AND ra.EMPLOYEEID='$empid'
			 				WHERE ra.REPORTID IS NOT NULL $wherereport
							ORDER BY rd.REPORTID,rd.SUBREPORTID
							") or die(mysql_error());
	
//	$qryreportcurrent = mysql_query("SELECT rd.REPORTID,rd.SUBREPORTID,rd.SUBREPORT
//							FROM reportaccess ra
//							LEFT JOIN reportsdtl rd ON rd.REPORTID=ra.REPORTID AND rd.SUBREPORTID=ra.SUBREPORTID
//							WHERE ra.REPORTID=$reportid AND ra.EMPLOYEEID='$empid'
//							") or die(mysql_error());
}

/*END OF LISTINGS*/


echo "
<html>
<head>
<link rel=\"stylesheet\" type=\"text/css\" href=\"../veripro.css\">
<script language=\"javascript\" src=\"../veripro.js\"></script>

</head>
<body>

<form name=\"$formname\" method=\"POST\">
	
	<center>
	<div style=\"width:100%;height:550px;padding:5px 20px 0 20px;overflow:hidden;\">
			
		<div style=\"width:100%;padding:5px 20px 0 20px;overflow:hidden;\">
			<br />
			<span class=\"sectiontitle\">$formtitle</span>
			<br />
			
			<table class=\"setup\" width=\"100%\" >	
				<tr>
					<th>Employee</th>
					<th>:</th>
					<th><select name=\"empid\" onchange=\"submit();\">
							$empselect
						</select>
					
					</th>
				</tr>	
				<tr>
					<th>Report Header</th>
					<th>:</th>
					<th><select name=\"reportid\" onchange=\"submit();\">
							$hdrselect
						</select>
					
					</th>
				</tr>	
				<tr>
					<th>Apply Report Grouping</th>
					<th>:</th>
					<th><select name=\"reportgroup\">
							$levelselect
						</select>
						<input type=\"button\" value=\"Apply\" 
						onclick=\"if(reportgroup.value!=''){
						if(confirm('This option will delete ALL existing Report Access and will be re-configured based on the selected Report Group. Continue?'))
						{actiontxt.value='reportgroup';submit();}
						}
						else
						{alert('Please Select a NEW Report Group No. first, then click Apply.');}
						\"
						
						/>
					</th>
				</tr>
			</table>
			<br /><br />
			<div style=\"width:49%;height:350px;float:left;overflow:auto;border:1px solid Gray;\">
				<span class=\"sectiontitle\">AVAILABLE ACCESS</span>
				<br />
				
				<table class=\"listcol\">
					<tr>
						<th width=\"20px\">SUBREPORTID</th>
						<th>SUBREPORT</th>
					</tr>
				";
				$tmpreportid = "";

				while ($rowreportavailable = mysql_fetch_array($qryreportavailable))
				{
					$xreportid = $rowreportavailable["REPORTID"];
					$xsubreportid = $rowreportavailable["SUBREPORTID"];
					$xsubreport = $rowreportavailable["SUBREPORT"];
					$xreport = $rowreportavailable["REPORT"];
					
					if ($tmpreportid != $xreportid)
					{
						echo "
						<tr>
							<td colspan=\"2\" style=\"font-size:1.0em;font-weight:Bold;background-color:Blue;color:Yellow;\">$xreport</td>
						</tr>
						";
					}
					
					echo "
					<tr $mouseovereffect ondblclick=\"selreportid.value='$xreportid';selsubreportid.value='$xsubreportid';
													actiontxt.value='tocurrent';submit();\">
						<td align=\"center\" style=\"cursor:pointer;\">$xsubreportid</td>
						<td style=\"cursor:pointer;\">$xsubreport</td>
					</tr>
					";
					
					
					$tmpreportid = $xreportid;
					
				}


				echo "
				</table>
			</div>

			<div style=\"width:49%;height:350px;float:right;overflow:auto;border:1px solid Gray;\">
				<span class=\"sectiontitle\">CURRENT ACCESS</span>
				<br />
				
				<table class=\"listcol\">
					<tr>
						<th width=\"20px\">SUBREPORTID</th>
						<th>SUBREPORT</th>
					</tr>
				";
				
				$tmpreportid = "";
				
				while ($rowreportcurrent = mysql_fetch_array($qryreportcurrent))
				{
					$zreportid = $rowreportcurrent["REPORTID"];
					$zsubreportid = $rowreportcurrent["SUBREPORTID"];
					$zsubreport = $rowreportcurrent["SUBREPORT"];
					$zreport = $rowreportcurrent["REPORT"];
					
					if ($tmpreportid != $zreportid)
					{
						echo "
						<tr>
							<td colspan=\"2\" style=\"font-size:1.0em;font-weight:Bold;background-color:Blue;color:Yellow;\">$zreport</td>
						</tr>
						";
					}
					
					echo "
					<tr $mouseovereffect ondblclick=\"selreportid.value='$zreportid';selsubreportid.value='$zsubreportid';
													actiontxt.value='toavailable';submit();\">
						<td align=\"center\" style=\"cursor:pointer;\">$zsubreportid</td>
						<td style=\"cursor:pointer;\">$zsubreport</td>
					</tr>
					";
					
					$tmpreportid = $zreportid;
				}


				echo "
				</table>
			</div>
			
			
			
		</div>
	</div>
	</center>
	
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"selreportid\" />
	<input type=\"hidden\" name=\"selsubreportid\" />
</form>

</body>
</html>
";

?>

