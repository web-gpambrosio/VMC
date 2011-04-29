<?php
session_start();

include('veritas/connectdb.php');
//include('connectdb.php');

include('veritas/include/stylephp.inc');

$datetimenow=date("Y-m-d H:i:s");
$datenow=date("Y-m-d");
$datenowshow=date("m/d/Y");

$localdir="dumping"; // local dumping
$serverdir="scanned"; // server dumping with addl subdirectory (APPLICANTNO)

if(isset($_SESSION['employeeid']))
	$employeeid=$_SESSION['employeeid'];
$employeeid="123"; //temporary for testing purpose only

$actionajax = $_GET['actionajax'];
$doctype = $_GET['doctype'];


switch ($actionajax)
{
	case "doctype":
		//GET DOCUMENT TYPE
		$qrygetdoctype=mysql_query("SELECT DISTINCT TYPE FROM crewdocuments WHERE TYPE IS NOT NULL AND TYPE<>'' ORDER BY TYPE") or die(mysql_error());
		
		//GET DOCUMENT
		if(!empty($doctype))
			$wheredoctype="AND TYPE='$doctype'";
		$qrygetdoc=mysql_query("SELECT DOCCODE,DOCUMENT,TYPE FROM crewdocuments 
			WHERE TYPE IS NOT NULL AND TYPE<>'' 
			$wheredoctype ORDER BY DOCUMENT") or die(mysql_error());
		$doctypetemp="
		<table>
			<tr>
				<td>
					Type:&nbsp;&nbsp;
					<select name=\"doctype\" style=\"font-size:8pt;\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onchange=\"chkloading('doctype',10);\">\n
						<option value=\"\">--</option>";
						while($rowgetdoctype=mysql_fetch_array($qrygetdoctype))
						{
							$doctype1=$rowgetdoctype["TYPE"];
							if($doctype1==$doctype)
								$selected="selected";
							else
								$selected="";
							$doctypetemp .= "<option $selected value=\"$doctype1\">$doctype1</option>\n";
						}
				$doctypetemp .= "
					</select>
				</td>
			</tr>
			<tr>
				<td>
					<select name=\"doccodelist\" style=\"font-size:8pt;width:340px;\"
						onkeydown=\"if(event.keyCode==13){event.keyCode=9;}\"
						onchange=\"doccodearray=this.value.split('*');doccodeinput.value=doccodearray[0];\">\n
						<option value=\"\">-Select-</option>";
						while($rowgetdoc=mysql_fetch_array($qrygetdoc))
						{
							$doccode1=$rowgetdoc["DOCCODE"];
							$document1=$rowgetdoc["DOCUMENT"];
							$doctype1=$rowgetdoc["TYPE"];
							if($doccode1==$doccode[1])
								$selected="selected";
							else
								$selected="";
							if(empty($doctype))
								$doctypetemp .= "<option $selected value=\"$document1*$doccode1*$doctype1\">$document1 ($doctype1)</option>\n";
							else
								$doctypetemp .= "<option $selected value=\"$document1*$doccode1*$doctype1\">$document1</option>\n";
						}
				$doctypetemp .= "
					</select>
				</td>
			</tr>
			<tr>
				<td>
					<input type=\"text\" name=\"doccodeinput\" value=\"{$doccode[0]}\" style=\"font-size:8pt;\"
						onkeydown=\"if(event.keyCode==13) {event.keyCode=9}\" 
						onkeyup=\"listSearch(this.form,0,'doccodelist','doccodeinput');\" 
						onblur=\"btnselect.click();\" size=\"60\" />
					<input type=\"button\" style=\"border:0;background-color:gray;\" name=\"btnselect\" 
						onfocus=\"listSearch(this.form,1,'doccodelist','doccodeinput');\">
				</td>
			</tr>
			<tr>
				<td>
					<input type=\"button\" name=\"btnrename\" onclick=\"chkrename();\" value=\"Rename\">&nbsp;
					<input type=\"button\" name=\"btndelete\" onclick=\"chkdelete();\" value=\"Delete\">
				</td>
			</tr>
		</table>";
		$resulttemp=$doctypetemp;
	break;
}
$result=$actionajax."|".$resulttemp;
echo $result;
?>