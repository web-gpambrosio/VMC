<?php
$kups="gino";
include("veritas/connectdb.php");
include("veritas/include/stylephp.inc");
include("veritas/include/functions.inc");

session_start();

$currentdate = date('Y-m-d H:i:s');
$datenow = date('d F Y');
$basedir = "idpics/";
$basedirdocs = "docimages/";

$dateformat = "dMY";
//$new = 0;
$disabled = "disabled=\"disabled\"";

$marked = "<span style=\"font-size:1em;font-weight:Bold;\">X</span>";

if(isset($_SESSION["employeeid"]))
	$employeeid=$_SESSION["employeeid"];

if (isset($_GET["crewname"]))
	$crewname = $_GET["crewname"];

if (isset($_GET["crewrank"]))
	$crewrank = $_GET["crewrank"];

if (isset($_GET["crewvessel"]))
	$crewvessel = $_GET["crewvessel"];

if (isset($_GET["date"]))
	$date = $_GET["date"];
	
if (isset($_POST["applicantno"]))
	$applicantno = $_POST["applicantno"];
else
	$applicantno = $_GET["applicantno"];


if (isset($_POST["idno"]))
	$idno = $_POST["idno"];
else 
	$idno = $_GET["idno"];

$print = 1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>
Crew Withdrawal Form
</title>

<link rel="StyleSheet" type="text/css" href="veripro.css" />
<script type='text/javascript' src='veripro.js'></script>
<style type="text/css">
body
{
	font-size:9px; font-family:Arial;
}
#skin0
{
	display: none;
}
.xbody { font-size:11px }
.w { border:1px solid }
.w1 { border-right:1px solid }
.w111 { border-left:1px solid }
.w11 { border-left:1px solid; border-right:1px solid }
.w2 { border-bottom:1px solid; border-left:1px solid; border-right:1px solid }
.w22 { border-bottom:1px solid; border-right:1px solid }
.w2222 { border-left:1px solid; border-bottom:1px solid }
.w222 { border-bottom:1px solid; }
.w3 { border-bottom:1px solid; border-left:1px solid; border-right:1px solid }

</style>

</head>
<body>

<form name="withdrawal" method="POST">
<table width="775" height="1221" border="0" align="left" cellpadding="0" cellspacing="0" style="margin:5px 5px 5px 5px;">
<tr><td height="5"></td>
</tr>
<tr>
<td height="1214" align="center" valign="top" style="height:600px;">

<div style="width:100%;height:50px;">
			<div style="width:80%;height:100%;font-size:12px;font-weight:Bold;float:left;">
				<center>
					VERITAS MARITIME CORPORATION <br />
					DOCUMENTS WITHDRAWAL FORM
				</center>
			</div>
			<div style="width:20%;height:100%;font-size:8px;float:left;text-align:left">
				Form 230 <br />
				March 22, 2010
			</div>
	</div>

<table width="97%">
<tr>
				  <td width="15%" align="left"><span class="xbody">NAME OF SEAMAN:</span></td>
					<td width="35%" style="border-bottom:1px solid #000000" align="left"><span class="xbody"><?php echo $crewname; ?></span>&nbsp;</td>
					<td width="14%" align="left">&nbsp;&nbsp;&nbsp;<span class="xbody">DATE:</span></td>
		    <td width="36%" style="border-bottom:1px solid #000000" align="left"><span class="xbody"><?php echo $date; ?></span>&nbsp;</td>
		  </tr>
				<tr>
					<td align="left"><span class="xbody">RANK:</span></td>
					<td style="border-bottom:1px solid #000000" align="left"><span class="xbody"><?php echo $crewrank; ?></span>&nbsp;</td>
					<td align="left">&nbsp;&nbsp;&nbsp;<span class="xbody">LAST VESSEL:</span></td>
					<td style="border-bottom:1px solid #000000" align="left"><span class="xbody"><?php echo $crewvessel; ?></span>&nbsp;</td>
				</tr>
	</table>
<table width=97% height="1102" border="0" align="center" cellpadding="0" cellspacing="0">
<tr><td height="11"></td>
</tr>
<tr>
<td height="1090" align="center" valign="top" style="height:600px;">


		  <div>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
              <tr>
              <td width="18%" align="left">REASON FOR WITHDRAWAL:</td>
              <td width="82%" style="border-bottom:1px solid Black;">&nbsp;</td>
              </tr>
              <tr><td colspan="2" style="border-bottom:1px solid Black;">&nbsp;</td></tr>
              <tr><td colspan="2" style="border-bottom:1px solid Black;">&nbsp;</td></tr>
              <tr><td colspan="2" height="8"></td></tr>
            </table>
            </div>
            

            <table width="100%" height="191" border="0" cellpadding="0" cellspacing="0" class="w">
              <tr>
                <td width="9%" height="169" align="center" class="w222">REMARKS</td>
                <td width="23%" class="w2">&nbsp;</td>
                <td width="23%" class="w22">&nbsp;</td>
                <td width="23%" class="w22">&nbsp;</td>
                <td width="22%" class="w222">&nbsp;</td>
              </tr>
              <tr align="center" valign="middle">
                <td height="20">SIGNATURE</td>
                <td class="w11">CREWING OFFICER</td>
                <td class="w1">DIVISION MANAGER</td>
                <td class="w1">TRAINING / PBF<span style="font-size:8px">(cadetship only)</span></td>
                <td>JMM/AFA</td>
              </tr>
            </table>


            <table width=100% border="0" cellspacing="0" cellpadding="0" class="w3">
              <tr>
                <td width="50%" height="12" align="left">&nbsp;</td>
                <td width="50%" align="left" class="w111">&nbsp;</td>
              </tr>
              <tr>
                <td height="376" align="left" class="w222" valign="top"><table width="357" border="0" cellspacing="0" cellpadding="0">
                  <tr valign="top">
                    <td width="12" rowspan="21">&nbsp;</td>
                    <td width="345" height="16" valign="top"><strong class="xbody">OPERATIONS DEPARTMENT:</strong></td>
                  </tr>
                  <tr valign="top">
                    <td height="15">EXPENSES FOR REIMBURSEMENT:</td>
                  </tr>
                  <tr valign="top">
                    <td height="16"><table width="187" border="0" cellspacing="0" cellpadding="0">
                      <tr valign="top">
                        <td width="10" class="w" style="font-size:8px">&nbsp;</td>
                        <td width="12">&nbsp;</td>
                        <td width="162">MEDICAL</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr valign="top">
                    <td height="16"><table width="187" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="10" class="w" style="font-size:8px">&nbsp;</td>
                        <td width="12">&nbsp;</td>
                        <td width="162">VISA FEE</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr valign="top">
                    <td height="16"><table width="187" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="10" class="w" style="font-size:8px">&nbsp;</td>
                        <td width="12">&nbsp;</td>
                        <td width="162">PROCESSING FEE (POEA/OWWA)</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td height="16"><table width="187" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="10" class="w" style="font-size:8px">&nbsp;</td>
                        <td width="12">&nbsp;</td>
                        <td width="162">LICENSE: (Please specify)</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td><table width="335" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="10">&nbsp;</td>
                        <td width="12">&nbsp;</td>
                        <td width="313" class="w222">&nbsp;</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td height="16"><table width="335" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="10">&nbsp;</td>
                        <td width="12">&nbsp;</td>
                        <td width="313" class="w222">&nbsp;</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td><table width="187" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="10" class="w" style="font-size:8px">&nbsp;</td>
                        <td width="12">&nbsp;</td>
                        <td width="162">OTHERS: (Please specify)</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td><table width="335" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="10">&nbsp;</td>
                        <td width="12">&nbsp;</td>
                        <td width="313" class="w222">&nbsp;</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td height="22" valign="top"><table width="335" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="10">&nbsp;</td>
                        <td width="12">&nbsp;</td>
                        <td width="313" class="w222">&nbsp;</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td height="15" valign="top">CERTIFICATIONS:</td>
                  </tr>
                  <tr>
                    <td height="16"><table width="187" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="10" class="w" style="font-size:8px">&nbsp;</td>
                        <td width="12">&nbsp;</td>
                        <td width="162">EMPLOYMENT CONTRACT</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td height="16"><table width="187" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="10" class="w" style="font-size:8px">&nbsp;</td>
                        <td width="12">&nbsp;</td>
                        <td width="162">SSS CERTIFICATE</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td height="16"><table width="187" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="10" class="w" style="font-size:8px">&nbsp;</td>
                        <td width="12">&nbsp;</td>
                        <td width="162">PROCESSING FEE (POEA/OWWA)</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td height="16"><table width="187" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="10" class="w" style="font-size:8px">&nbsp;</td>
                        <td width="12">&nbsp;</td>
                        <td width="162">OTHERS: (Please specify)</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td><table width="335" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="10">&nbsp;</td>
                        <td width="12">&nbsp;</td>
                        <td width="313" class="w222">&nbsp;</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td height="16" valign="top"><table width="335" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="10">&nbsp;</td>
                        <td width="12">&nbsp;</td>
                        <td width="313" class="w222">&nbsp;</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td><table width="187" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="10" class="w" style="font-size:8px">&nbsp;</td>
                          <td width="12">&nbsp;</td>
                          <td width="162">REMARKS: (Please specify)</td>
                        </tr>
                    </table></td>
                    </tr>
                  <tr>
                    <td valign="top"><table width="335" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="10">&nbsp;</td>
                        <td width="12">&nbsp;</td>
                        <td width="313" class="w222">&nbsp;</td>
                      </tr>
                    </table></td>
                    </tr>
                  <tr>
                    <td height="64" valign="top"><table width="335" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td>&nbsp;</td>
                        <td height="42" class="w222">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td class="w222">&nbsp;</td>
                      </tr>
                        <tr>
                          <td width="20" align="center">&nbsp;</td>
                          <td width="188" height="14" align="center">EVANGELINE B. PUNZALAN</td>
                          <td width="38">&nbsp;</td>
                          <td width="89" align="center">DATE</td>
                        </tr>
                    </table></td>
                    </tr>
                </table></td>
                <td rowspan="3" align="left" class="w111" valign="top"><table width="357" border="0" cellspacing="0" cellpadding="0">
                  <tr valign="top">
                    <td width="12" rowspan="21">&nbsp;</td>
                    <td width="345" height="16" valign="top"><strong class="xbody">ACCOUNTING / FINANCE DEPARTMENT:</strong></td>
                  </tr>
                  <tr valign="top">
                    <td height="12">&nbsp;</td>
                  </tr>
                  <tr valign="top">
                    <td height="16"><table width="337" border="0" cellspacing="0" cellpadding="0">
                        <tr valign="top">
                          <td width="10" class="w" style="font-size:8px">&nbsp;</td>
                          <td width="12">&nbsp;</td>
                          <td width="192">LOANS</td>
                          <td width="27" align="right">P</td>
                          <td width="96" class="w222">&nbsp;</td>
                        </tr>
                    </table></td>
                  </tr>
                  <tr valign="top">
                    <td height="16"><table width="337" border="0" cellspacing="0" cellpadding="0">
                      <tr valign="top">
                        <td width="10" class="w" style="font-size:8px">&nbsp;</td>
                        <td width="12">&nbsp;</td>
                        <td width="192">CASH ADVANCE</td>
                        <td width="27" align="right">P</td>
                        <td width="96" class="w222">&nbsp;</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr valign="top">
                    <td height="16"><table width="337" border="0" cellspacing="0" cellpadding="0">
                      <tr valign="top">
                        <td width="10" class="w" style="font-size:8px">&nbsp;</td>
                        <td width="12">&nbsp;</td>
                        <td width="193">LICENSES</td>
                        <td width="26" align="right">P</td>
                        <td width="96" class="w222">&nbsp;</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td height="16"><table width="337" border="0" cellspacing="0" cellpadding="0">
                      <tr valign="top">
                        <td width="10" class="w" style="font-size:8px">&nbsp;</td>
                        <td width="12">&nbsp;</td>
                        <td width="192">SIGNING BONUS</td>
                        <td width="27" align="right">P</td>
                        <td width="96" class="w222">&nbsp;</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td height="16"><table width="337" border="0" cellspacing="0" cellpadding="0">
                      <tr valign="top">
                        <td width="10" class="w" style="font-size:8px">&nbsp;</td>
                        <td width="12">&nbsp;</td>
                        <td width="192">STAND-BY-PAY</td>
                        <td width="27" align="right">P</td>
                        <td width="96" class="w222">&nbsp;</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td height="16"><table width="337" border="0" cellspacing="0" cellpadding="0">
                      <tr valign="top">
                        <td width="10" class="w" style="font-size:8px">&nbsp;</td>
                        <td width="12">&nbsp;</td>
                        <td width="192">OTHERS:  (Please specify)</td>
                        <td width="27" align="right">P</td>
                        <td width="96" class="w222">&nbsp;</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td height="16"><table width="337" border="0" cellspacing="0" cellpadding="0">
                      <tr valign="top">
                        <td width="10">&nbsp;</td>
                        <td width="12">&nbsp;</td>
                        <td width="192" class="w222">&nbsp;</td>
                        <td width="27" align="right">P</td>
                        <td width="96" class="w222">&nbsp;</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td height="16"><table width="337" border="0" cellspacing="0" cellpadding="0">
                      <tr valign="top">
                        <td width="10">&nbsp;</td>
                        <td width="12">&nbsp;</td>
                        <td width="192" class="w222">&nbsp;</td>
                        <td width="27" align="right">P</td>
                        <td width="96" class="w222">&nbsp;</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td height="16" valign="top"><table width="337" border="0" cellspacing="0" cellpadding="0">
                      <tr valign="top">
                        <td width="10">&nbsp;</td>
                        <td width="12">&nbsp;</td>
                        <td width="192" class="w222">&nbsp;</td>
                        <td width="27" align="right">P</td>
                        <td width="96" class="w222">&nbsp;</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td height="54" valign="top"><table width="335" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td>&nbsp;</td>
                        <td height="35" class="w222">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td width="20" align="center">&nbsp;</td>
                        <td width="188" height="14" align="center">ELIZABETH G. MANANO</td>
                        <td width="38">&nbsp;</td>
                        <td width="89" align="center">&nbsp;</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td height="16"><table width="187" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="10" class="w" style="font-size:8px">&nbsp;</td>
                          <td width="12">&nbsp;</td>
                          <td width="162">REMARKS:  (Please specify)</td>
                        </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td height="16"><table width="337" border="0" cellspacing="0" cellpadding="0">
                      <tr valign="top">
                        <td width="10">&nbsp;</td>
                        <td width="12">&nbsp;</td>
                        <td width="192" class="w222">&nbsp;</td>
                        <td width="27" align="right">P</td>
                        <td width="96" class="w222">&nbsp;</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td height="16"><table width="337" border="0" cellspacing="0" cellpadding="0">
                      <tr valign="top">
                        <td width="10">&nbsp;</td>
                        <td width="12">&nbsp;</td>
                        <td width="192" class="w222">&nbsp;</td>
                        <td width="27" align="right">P</td>
                        <td width="96" class="w222">&nbsp;</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td height="16"><table width="337" border="0" cellspacing="0" cellpadding="0">
                      <tr valign="top">
                        <td width="10">&nbsp;</td>
                        <td width="12">&nbsp;</td>
                        <td width="192" class="w222">&nbsp;</td>
                        <td width="27" align="right">P</td>
                        <td width="96" class="w222">&nbsp;</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td><table width="337" border="0" cellspacing="0" cellpadding="0">
                      <tr valign="top">
                        <td width="10">&nbsp;</td>
                        <td width="12">&nbsp;</td>
                        <td width="192" class="w222">&nbsp;</td>
                        <td width="27" align="right">P</td>
                        <td width="96" class="w222">&nbsp;</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td height="16" valign="top"><table width="337" border="0" cellspacing="0" cellpadding="0">
                      <tr valign="top">
                        <td width="10">&nbsp;</td>
                        <td width="12">&nbsp;</td>
                        <td width="192" align="center">GRAND TOTAL:</td>
                        <td width="27" align="right">P</td>
                        <td width="96" class="w222">&nbsp;</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td height="61"><table width="335" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td>&nbsp;</td>
                        <td height="35" class="w222">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td class="w222">&nbsp;</td>
                      </tr>
                      <tr>
                        <td width="20" align="center">&nbsp;</td>
                        <td width="188" height="14" align="center">FORTUNATA G. PI&Ntilde;EZ</td>
                        <td width="38">&nbsp;</td>
                        <td width="89" align="center">DATE</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td height="53" valign="top"><table width="262" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td>&nbsp;</td>
                        <td height="34" class="w222">&nbsp;</td>
                        </tr>
                      <tr>
                        <td width="98" align="center">&nbsp;</td>
                        <td width="164" height="14" align="center">GILBERTO L. MANALOTO</td>
                        </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td height="342" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="w">
                      <tr>
                        <td height="16" colspan="2" class="w222"><strong class="xbody">&nbsp;&nbsp;DOCUMENTS RELEASE APPROVAL</strong></td>
                        </tr>
                      <tr>
                        <td width="48%" height="48" align="center" valign="bottom" class="w222">CREWING OFFICER</td>
                        <td width="52%" align="center" valign="bottom" class="w2222">DIVISION MANAGER</td>
                      </tr>
                      <tr>
                        <td height="47" align="center" valign="bottom" class="w222">JMM / AFA</td>
                        <td align="center" valign="bottom" class="w2222">EMM / MK</td>
                      </tr>

                      <tr>
                        <td height="20" colspan="2" align="left" valign="middle">
                        <table width="211" border="0" cellspacing="0" cellpadding="0">
                          <tr valign="top">
                            <td width="26" height="12" align="right">&nbsp;</td>
                            <td width="80" valign="bottom">With MOA</td>
                            <td width="35" valign="bottom">YES</td>
                            <td width="12" align="right" class="w">&nbsp;</td>
                            <td width="16" align="right">&nbsp;</td>
                            <td width="30" valign="bottom">NO</td>
                            <td width="12" class="w">&nbsp;</td>
                          </tr>
                        </table></td>
                        </tr>
                    </table>
                      <table width="334" border="0" cellspacing="0" cellpadding="0">
                        <tr valign="top">
                          <td width="330">&nbsp;</td>
                        </tr>
                        <tr valign="top">
                          <td width="330" height="14" align="center"><strong>ACKNOWLEDGEMENT OF RECEIPT</strong></td>
                        </tr>
                        <tr valign="top">
                          <td>&nbsp;</td>
                        </tr>
                        <tr valign="top">
                          <td height="32" align="left"><table width="302" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td width="317" height="24" align="left" valign="top" style="font-size:8px">I hereby certify that I have received the documents below from VERITAS MARITIME CORPORATION</td>
                                </tr>
                          </table></td>
                        </tr>
                        <tr>
                          <td height="16"><table width="187" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td width="10" class="w" style="font-size:8px">&nbsp;</td>
                                <td width="12">&nbsp;</td>
                                <td width="162">SEAMAN'S BOOK</td>
                              </tr>
                          </table></td>
                        </tr>
                        <tr>
                          <td><table width="187" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td width="10" class="w" style="font-size:8px">&nbsp;</td>
                                <td width="12">&nbsp;</td>
                                <td width="162">PASSPORT</td>
                              </tr>
                          </table></td>
                        </tr>
                        <tr>
                          <td height="16"><table width="187" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td width="10" class="w" style="font-size:8px">&nbsp;</td>
                              <td width="12">&nbsp;</td>
                              <td width="162">LICENSE</td>
                            </tr>
                          </table></td>
                        </tr>
                        <tr>
                          <td><table width="187" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td width="10" class="w" style="font-size:8px">&nbsp;</td>
                              <td width="12">&nbsp;</td>
                              <td width="162">OTHERS: (Please specify)</td>
                            </tr>
                          </table></td>
                        </tr>
                        <tr>
                          <td height="16"><table width="337" border="0" cellspacing="0" cellpadding="0">
                            <tr valign="top">
                              <td width="10">&nbsp;</td>
                              <td width="12">&nbsp;</td>
                              <td class="w222">&nbsp;</td>
                              </tr>
                          </table></td>
                        </tr>
                        <tr>
                          <td><table width="337" border="0" cellspacing="0" cellpadding="0">
                            <tr valign="top">
                              <td width="10">&nbsp;</td>
                              <td width="12">&nbsp;</td>
                              <td class="w222">&nbsp;</td>
                            </tr>
                          </table></td>
                        </tr>
                        <tr>
                          <td height="16"><table width="337" border="0" cellspacing="0" cellpadding="0">
                            <tr valign="top">
                              <td width="10">&nbsp;</td>
                              <td width="12">&nbsp;</td>
                              <td class="w222">&nbsp;</td>
                            </tr>
                          </table></td>
                        </tr>
                        <tr>
                          <td><table width="337" border="0" cellspacing="0" cellpadding="0">
                            <tr valign="top">
                              <td width="10">&nbsp;</td>
                              <td width="12">&nbsp;</td>
                              <td class="w222">&nbsp;</td>
                            </tr>
                          </table></td>
                        </tr>
                      </table>
                      <table width="335" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td>&nbsp;</td>
                          <td height="42" class="w222">&nbsp;</td>
                          <td>&nbsp;</td>
                          <td class="w222">&nbsp;</td>
                        </tr>
                        <tr>
                          <td width="20" align="center">&nbsp;</td>
                          <td width="188" height="14" align="center">CREW SIGNATURE</td>
                          <td width="38">&nbsp;</td>
                          <td width="89" align="center">DATE</td>
                        </tr>
                      </table></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td height="83" align="left" class="w222"><table width="357" border="0" cellspacing="0" cellpadding="0">
                  <tr valign="top">
                    <td width="12" rowspan="7">&nbsp;</td>
                    <td width="345">&nbsp;</td>
                  </tr>
                  <tr valign="top">
                    <td width="345"><strong class="xbody">ADMINISTRATIVE DEPARTMENT:</strong></td>
                    </tr>
                  <tr valign="top">
                    <td>&nbsp;</td>
                    </tr>
                  <tr valign="top">
                    <td height="16"><table width="308" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="10" class="w" style="font-size:8px">&nbsp;</td>
                          <td width="12">&nbsp;</td>
                          <td width="286">COMMUNICATIONS/ OTHER CHARGES: (Please specify)</td>
                        </tr>
                    </table></td>
                  </tr>
                  <tr valign="top">
                    <td height="67"><table width="339" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="10" height="64">&nbsp;</td>
                        <td width="12">&nbsp;</td>
                        <td width="317"><table width="311" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="187" height="16" class="w222">&nbsp;</td>
                            <td width="23" align="right">P </td>
                            <td width="101" class="w222">&nbsp;</td>
                          </tr>
                          <tr>
                            <td height="16" class="w222">&nbsp;</td>
                            <td align="right">P </td>
                            <td class="w222">&nbsp;</td>
                          </tr>
                          <tr>
                            <td height="16" class="w222">&nbsp;</td>
                            <td align="right">P </td>
                            <td class="w222">&nbsp;</td>
                          </tr>
                          <tr>
                            <td height="16" class="w222">&nbsp;</td>
                            <td align="right">P </td>
                            <td class="w222">&nbsp;</td>
                          </tr>

                        </table></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td height="16"><table width="335" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td>&nbsp;</td>
                        <td height="42" class="w222">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td class="w222">&nbsp;</td>
                      </tr>
                      <tr>
                        <td width="20" align="center">&nbsp;</td>
                        <td width="188" height="14" align="center">CECILE LOMBOS</td>
                        <td width="38">&nbsp;</td>
                        <td width="89" align="center">DATE</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                  </tr>
                </table></td>
                </tr>
              <tr>
                <td height="268" align="left" valign="top"><table width="357" border="0" cellspacing="0" cellpadding="0">
                  <tr valign="top">
                    <td width="12" rowspan="7">&nbsp;</td>
                    <td width="345">&nbsp;</td>
                  </tr>
                  <tr valign="top">
                    <td width="345"><strong class="xbody">TRAINING DEPARTMENT:</strong></td>
                  </tr>
                  <tr valign="top">
                    <td>&nbsp;</td>
                  </tr>
                  <tr valign="top">
                    <td height="97"><table width="331" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td height="14">TRAINING TAKEN:</td>
                        <td class="w222">&nbsp;</td>
                        <td align="right">P </td>
                        <td class="w222">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="16">&nbsp;</td>
                        <td class="w222">&nbsp;</td>
                        <td align="right">P </td>
                        <td class="w222">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="16">&nbsp;</td>
                        <td class="w222">&nbsp;</td>
                        <td align="right">P </td>
                        <td class="w222">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="16">&nbsp;</td>
                        <td class="w222">&nbsp;</td>
                        <td align="right">P </td>
                        <td class="w222">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="16">&nbsp;</td>
                        <td class="w222">&nbsp;</td>
                        <td align="right">P </td>
                        <td class="w222">&nbsp;</td>
                      </tr>
                        <tr>
                          <td width="87" height="14">&nbsp;</td>
                          <td width="120" class="w222">&nbsp;</td>
                          <td width="24" align="right">P </td>
                          <td width="100" class="w222">&nbsp;</td>
                        </tr>
                    </table></td>
                  </tr>
                  <tr valign="top">
                    <td height="48"><table width="331" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td height="15" colspan="4" valign="top">BOARDING HOUSE ACCOMODATION / OTHERS: (Please specify) </td>
                        </tr>
                      <tr>
                        <td width="87" height="16">&nbsp;</td>
                        <td width="120" class="w222">&nbsp;</td>
                        <td width="24" align="right">P </td>
                        <td width="100" class="w222">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="16">&nbsp;</td>
                        <td class="w222">&nbsp;</td>
                        <td align="right">P </td>
                        <td class="w222">&nbsp;</td>
                      </tr>

                    </table></td>
                  </tr>
                  <tr>
                    <td height="16"><table width="335" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td>&nbsp;</td>
                        <td height="42" class="w222">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td class="w222">&nbsp;</td>
                      </tr>
                      <tr>
                        <td width="20" align="center">&nbsp;</td>
                        <td width="188" height="14" align="center">CAPT. LEX CALUMPANG</td>
                        <td width="38">&nbsp;</td>
                        <td width="89" align="center">DATE</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                  </tr>
                </table></td>
                </tr>
            </table></td>
</tr>
</table>

</td>
</tr>
</table>


  <input type="hidden" name="applicantno" value="<?php echo $applicantno; ?>"/>
</form>
<?php
if ($print == 1)
	include('include/printclose.inc');
?>
</body>
</html>
