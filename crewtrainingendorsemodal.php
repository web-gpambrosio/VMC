<?php
session_start();
include('veritas/connectdb.php');
include("veritas/include/stylephp.inc");

$datetimenow=date("Y-m-d H:i:s");
$datenow=date("Y-m-d");

if(isset($_SESSION["user"]))
	$user=$_SESSION["user"];
	
if(isset($_POST["actiontxt"]))
	$actiontxt=$_POST["actiontxt"];
	
if(isset($_POST["retvalhidden"]))
	$retvalhidden=$_POST["retvalhidden"];
	
if(isset($_GET["selvesselcode"]))
	$selvesselcode=$_GET["selvesselcode"];
	
if(isset($_GET["crewname"]))
	$crewname=$_GET["crewname"];
	
if(isset($_GET["selrankcode"]))
	$selrankcode=$_GET["selrankcode"];
	
if(isset($_GET["applicantno"]))
	$applicantno=$_GET["applicantno"];
	
if(isset($_GET["seletd"]))
	$seletd=str_replace("_","/",$_GET["seletd"]);

if(empty($seletd))
	$seletd=date("m/d/Y");
	
$seletdraw=date("Y-m-d",strtotime($seletd));

$seldisemb = date("m/d/Y",strtotime("$seletd + 9 month"));

$qrygetranking = mysql_query("SELECT RANKING FROM rank WHERE RANKCODE='$selrankcode'") or die(mysql_error());
$rowgetranking = mysql_fetch_array($qrygetranking);
$ranking = $rowgetranking["RANKING"];

$ranklow = $ranking + 1;
$rankhigh = $ranking - 1;

$whereranks = "";

$qrygetranks = mysql_query("SELECT RANKCODE FROM rank WHERE RANKING IN ($ranklow,$ranking,$rankhigh)") or die(mysql_error());
while ($rowgetranks=mysql_fetch_array($qrygetranks))
{
	$rcode=$rowgetranks["RANKCODE"];
	if (empty($whereranks))
		$whereranks .= "cc.RANKCODE IN ('$rcode'";
	else
		$whereranks .= ",'$rcode'";
}

$whereranks .= ")";
	
$whereranks="cc.RANKCODE='$selrankcode'";
	
$qrycrew=mysql_query("SELECT cc.CCID,r.RANK,c.FNAME,c.GNAME,cc.DATEEMB,cc.DATEDISEMB,cc.DATECHANGEDISEMB
	FROM crewchange cc
	LEFT JOIN rank r ON cc.RANKCODE=r.RANKCODE
	LEFT JOIN crew c ON cc.APPLICANTNO=c.APPLICANTNO
	LEFT JOIN crewchangerelation cr ON cc.CCID=cr.CCID
	LEFT JOIN crewpromotionrelation cpr ON cpr.CCID=cc.CCID
	WHERE cc.VESSELCODE='$selvesselcode' AND $whereranks AND cr.CCIDEMB IS NULL AND cc.APPLICANTNO<>$applicantno
	AND ARRMNLDATE IS NULL AND cpr.CCID IS NULL AND cc.DATEEMB <= '$seletdraw'
	ORDER BY r.RANKING DESC
	") or die(mysql_error());

echo "
<html>
<head>
<title>Update Crew Change</title>
<link rel=\"StyleSheet\" type=\"text/css\" href=\"veripro.css\" />
<script language=\"javascript\" src=\"popcalendar.js\"></script>
<script type='text/javascript' src='veripro.js'></script>

<script language=\"JavaScript\"> 
function retval()
{
	window.returnValue=document.crewtrainingendorsemodal.retvalhidden.value;
	window.close();
}
function chkescape()
{
	if(event.keyCode==27)
	{
		window.returnValue='';
		window.close();
	}
}
function chkupdate()
{
	var rem='';
	with(document.crewtrainingendorsemodal)
	{
		if(ccidhidden.value=='')
		{
			rem='Selection';
		}
		if(dateemb.value=='')
		{
			if(rem=='')
				rem='Embark Date';
			else
				rem=rem + ', Embark Date';
		}
		if(datedisemb.value=='')
		{
			if(rem=='')
				rem='Disembark Date';
			else
				rem=rem + ', Disembark Date';
		}
		if(rem=='')
		{
			retvalhidden.value=ccidhidden.value+'|'+dateemb.value+'|'+datedisemb.value;
			retval();
		}
		else
			alert(rem + ' should not be NULL!');
	}
}
</script>
</head>
<body style=\"overflow:hidden;\" onload=\"document.crewtrainingendorsemodal.dateemb.focus();document.crewtrainingendorsemodal.dateemb.select();\" 
	onunload=\"retval();\" onkeyup=\"chkescape();\">
<form name=\"crewtrainingendorsemodal\" method=\"POST\">
	<br />
	<center>
	<span style=\"color:Blue;font-weight:Bold;font-size:1em;\">Note: Any changes made will directly affect CREW CHANGE PLAN.</span>
	</center>
	<br /><br />
	<span class=\"sectiontitle\">DISEMBRAKING CREW</span>
		<div style=\"width:100%;height:100%;overflow:auto;background:White;float:left;\">
			<center>
			<table cellspacing=\"0\" cellpadding=\"1\" width=\"100%\">";
				$stylehdr = "font-size:0.85em;color:Green;text-align:center;border-right:1px solid Black;border-bottom:1px solid Black;";
				$styledtls = "font-size:0.9em;border-bottom:1px dashed Gray;border-right:1px solid Gray;";
				echo "
				<tr>\n
					<td style=\"$stylehdr\">&nbsp;</td>\n
					<td style=\"$stylehdr\">RANK</td>\n
					<td style=\"$stylehdr\">NAME</td>\n
					<td style=\"$stylehdr\">EMBARK</td>\n
					<td style=\"$stylehdr\">DISEMBARK</td>\n
					<td style=\"$stylehdr\">E.O.C.</td>\n
				</tr>";
				$cntcrew=mysql_num_rows($qrycrew);
				if($cntcrew==0)
					echo "
					<script>
						alert('No disembarking crew!');
					</script>";
				else
				{
					while($rowcrew=mysql_fetch_array($qrycrew))
					{
						$ccid=$rowcrew["CCID"];
						$rank=$rowcrew["RANK"];
						$name=$rowcrew["FNAME"].", ".$rowcrew["GNAME"];
						$dateemb=$rowcrew["DATEEMB"];
						$dateembshow=date("m/d/Y",strtotime($dateemb));
						$datedisemb=$rowcrew["DATEDISEMB"];
						$datedisembshow=date("m/d/Y",strtotime($datedisemb));
						$datechangedisemb=$rowcrew["DATECHANGEDISEMB"];
						if(empty($datechangedisemb))
							$datechangedisembshow=$datedisembshow;
						else 
							$datechangedisembshow=date("m/d/Y",strtotime($datechangedisemb));
						if($cntcrew==1)
						{
							$checked="checked";
							$ccidchk=$ccid;
						}
						echo "
						<tr $mouseovereffect>\n
							<td style=\"text-align:center;$styledtls\">
								<input id=\"idhawb\" type=\"radio\" name=\"rdselect\" $checked
								onclick=\"ccidhidden.value='$ccid';\">
							</td>\n
							<td style=\"text-align:center;$styledtls\">$rank</td>\n
							<td style=\"$styledtls\">$name</td>\n
							<td style=\"text-align:center;$styledtls\">$dateembshow</td>\n
							<td style=\"text-align:center;$styledtls\">$datedisembshow</td>\n
							<td style=\"text-align:center;$styledtls\">$datechangedisembshow</td>\n
						</tr>";
					}
				}
			echo "
			</table><br>
			<span class=\"sectiontitle\">EMBARKING CREW</span>
			<table cellspacing=\"0\" cellpadding=\"1\" width=\"100%\">
				<tr>\n
					<td style=\"$stylehdr\">RANK</td>\n
					<td style=\"$stylehdr\">NAME</td>\n
					<td style=\"$stylehdr\">EMBARK</td>\n
					<td style=\"$stylehdr\">DISEMBARK</td>\n
				</tr>
				<tr>
					<td style=\"text-align:center;$styledtls\">$rank</td>
					<td style=\"text-align:center;$styledtls\">$crewname</td>
					<td style=\"text-align:center;$styledtls\">
						<input type=\"text\" name=\"dateemb\" value=\"$seletd\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					</td>
					<td style=\"text-align:center;$styledtls\">
						<input type=\"text\" name=\"datedisemb\" value=\"$seldisemb\" onKeyPress=\"return dateonly(this);\" size=\"10\" maxlength=\"10\" onkeydown=\"chkdatedown(this);\">
					</td>
				</tr>
			</table><br>
			<input type=\"button\" value=\"Update\" onclick=\"chkupdate();\"
					style=\"font-size:0.8em;font-weight:Bold;color:Lime;background-color:Black;border:thin solid white;cursor:pointer;\" />
			<input type=\"button\" value=\"Cancel\" onclick=\"window.returnValue='';window.close();\"
					style=\"font-size:0.8em;font-weight:Bold;color:Lime;background-color:Black;border:thin solid white;cursor:pointer;\" />
			</center>
		</div>
	<input type=\"hidden\" name=\"actiontxt\" />
	<input type=\"hidden\" name=\"retvalhidden\" />
	<input type=\"hidden\" name=\"ccidhidden\" value=\"$ccidchk\"/>
</form>
</body>
</html>";
?>
