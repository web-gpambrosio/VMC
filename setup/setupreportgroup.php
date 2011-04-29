<?php

include("veritas/connectdb.php");
include("veritas/include/stylephp.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');

if (isset($_POST['actiontxt']))
	$actiontxt = $_POST['actiontxt'];
	
if (isset($_SESSION['employeeid']))
	$employeeid = $_SESSION['employeeid'];
	
$formname = "formreportgroup";
$formtitle = "REPORT GROUPING SETUP";

	

//POSTS

if (isset($_POST['reportid']))
	$reportid = $_POST['reportid'];
	
if (isset($_POST['selreportid']))
	$selreportid = $_POST['selreportid'];
	
if (isset($_POST['selsubreportid']))
	$selsubreportid = $_POST['selsubreportid'];
	
if (isset($_POST['repgroupno']))
	$repgroupno = $_POST['repgroupno'];
	
	
switch ($actiontxt)
{
	case "togroup":
		
			$qryverify = mysql_query("SELECT REPGROUPNO FROM reportlevels WHERE REPGROUPNO=$repgroupno AND REPORTID=0 AND SUBREPORTID=0") or die(mysql_error());
			
			if (mysql_num_rows($qryverify) == 0)
			{
				$qryaddcurrent = mysql_query("INSERT INTO reportlevels(REPGROUPNO,REPORTID,SUBREPORTID,MADEBY,MADEDATE)
										VALUES($repgroupno,$selreportid,$selsubreportid,'$employeeid','$currentdate')
										") or die(mysql_error());
			}
			else 
			{
				$qryupdatecurrent = mysql_query("UPDATE reportlevels SET REPORTID=$selreportid,
																	SUBREPORTID=$selsubreportid,
																	MADEBY='$employeeid',
																	MADEDATE='$currentdate'
				
											") or die(mysql_error());
			}
		
		break;
		
	case "toavailable":
		
			$qrytoavailable = mysql_query("DELETE FROM reportlevels 
										WHERE REPGROUPNO=$repgroupno AND REPORTID=$selreportid 
										AND SUBREPORTID=$selsubreportid
										") or die(mysql_error());
		
		break;
		
	case "newgroup"	:
		
			$qrynewgroup = mysql_query("SELECT MAX(REPGROUPNO) AS MAXGROUPNO FROM reportlevels") or die(mysql_error());
			
			$rownewgroup = mysql_fetch_array($qrynewgroup);
			$maxgroupno = $rownewgroup["MAXGROUPNO"];
			
			$repgroupno = $maxgroupno + 1;
			
			$qryinsertgroup = mysql_query("INSERT INTO reportlevels(REPGROUPNO,REPORTID,SUBREPORTID,MADEBY,MADEDATE)
										VALUES($repgroupno,0,0,'$employeeid','$currentdate')
									") or die(mysql_error());
		
		break;
}
	

/* LISTINGS  */

$qryreportlevellist = mysql_query("SELECT DISTINCT REPGROUPNO FROM reportlevels") or die(mysql_error());
$levelselect = "<option value=\"\">--Select One--</option>";
$selected = "";
while ($rowreportlevellist = mysql_fetch_array($qryreportlevellist))
{
	$repgroup = $rowreportlevellist["REPGROUPNO"];
	
	if ($repgroup == $repgroupno)
		$selected = "SELECTED";
	else 
		$selected = "";
	
	$levelselect .= "<option $selected value=\"$repgroup\">$repgroup</option>";
}


if (!empty($repgroupno))
{
	$qryreportavailable = mysql_query("SELECT rh.REPORTID,rh.REPORT,rd.SUBREPORTID,rd.SUBREPORT
							FROM reportshdr rh
							LEFT JOIN reportsdtl rd ON rd.REPORTID=rh.REPORTID 
							LEFT JOIN reportlevels rl ON rl.REPORTID=rd.REPORTID AND rl.SUBREPORTID=rd.SUBREPORTID AND rl.REPGROUPNO=$repgroupno
			 				WHERE rl.REPGROUPNO IS NULL
			 				ORDER BY rd.REPORTID,rd.SUBPOSITION
							") or die(mysql_error());
	
	$qryreportcurrent = mysql_query("SELECT rh.REPORTID,rh.REPORT,rd.SUBREPORTID,rd.SUBREPORT
							FROM reportlevels rl
							LEFT JOIN reportshdr rh ON rh.REPORTID=rl.REPORTID
							LEFT JOIN reportsdtl rd ON rd.REPORTID=rl.REPORTID AND rd.SUBREPORTID=rl.SUBREPORTID
							WHERE rl.REPGROUPNO=$repgroupno
							ORDER BY rl.REPORTID
							") or die(mysql_error());
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
					<th width=\"100px\">Report Group No.</th>
					<th>:</th>
					<th><select name=\"repgroupno\" onchange=\"submit();\">
							$levelselect
						</select>
						
						<input type=\"button\" value=\"Create New Group\" onclick=\"actiontxt.value='newgroup';submit();\" />
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
					$xreport = $rowreportavailable["REPORT"];
					$xsubreportid = $rowreportavailable["SUBREPORTID"];
					$xsubreport = $rowreportavailable["SUBREPORT"];
					
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
													actiontxt.value='togroup';submit();\">
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
					$zreport = $rowreportcurrent["REPORT"];
					$zsubreportid = $rowreportcurrent["SUBREPORTID"];
					$zsubreport = $rowreportcurrent["SUBREPORT"];
					
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

