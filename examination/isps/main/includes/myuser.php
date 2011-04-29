<?php
$menu = '<table width="428" border="0" cellspacing="0" cellpadding="0">'
		.'<tr>'
		.'<td width="403" height="32" align="right">'
		.'<span class="menu"><strong>'
		.'|&nbsp;&nbsp; '
		.'<a href="../includes/log.php'
		.'" class="ahref" onmouseover="this.style.textDecoration=\'underline\'" onmouseout="this.style.textDecoration=\'none\'">LOG OUT</a>'
		.' <img src="../images/logout.gif" width="10" height="10" border="0"/>'
		.'&nbsp;&nbsp;|</strong></span></td><td width="25">&nbsp;</td></tr>'
		.'</table>';
		
$txtupperonly = 'onchange="javascript:this.value=this.value.toUpperCase();"';
$txtnumonly = 'onkeypress="if (event.keyCode &lt; 45 || event.keyCode &gt; 57) event.returnValue = false;"';
$txttext = 'style="font-size:12px" size="35" height="12"';
$txtstyle = $txtupperonly . $txttext;
$header = '<tr>'
		.'<td width="3" rowspan="2" background="../images/side.gif" valign="top"><img src="../images/topt.gif" width="3" /></td>'
		.'<td width="29" height="49" background="../images/top.gif">&nbsp;</td>'
		.'<td width="296" background="../images/top.gif" valign="bottom"><img src="../images/topm1.gif" width="296" height="49" /></td>'
		.'<td width="431" background="../images/top.gif" valign="top" align="right">'
		.'<table width="396" border="0" cellspacing="0" cellpadding="0">'
		.'<tr><td colspan="2" height="12"></td></tr>'
		.'<tr><td width="369" height="26" align="right">'
		.'<span style="color:#FFFFFF; font-size:16px">'
		.'<strong>International Ship and Port Facility Security</strong></span>'
		.'</td></tr></table>'
		.'</td><td width="38" background="../images/top.gif" align="right">'
		.'<img src="../images/top.gif" /><img src="../images/topline.gif" width="22" height="49" />'
		.'</td><td width="3" rowspan="2" align="right" valign="top" background="../images/side.gif">'
		.'<img src="../images/topt.gif" width="3" /></td></tr>'
		.'<tr><td height="39">&nbsp;</td><td valign="top">'
		.'<img src="../images/topm2.gif" width="296" height="39" /></td>'
		.'<td style="border-bottom:1px solid #dddddd" align="right">'
		.$menu
		.'</td><td><img src="../images/tnkc.gif" width="25" height="40" /></td></tr>';
$xheader = '<tr>'
		.'<td width="3" rowspan="2" background="../images/side.gif" valign="top"><img src="../images/topt.gif" width="3" /></td>'
		.'<td width="29" height="49" background="../images/top.gif">&nbsp;</td>'
		.'<td width="296" background="../images/top.gif" valign="bottom"><img src="../images/topm1.gif" width="296" height="49" /></td>'
		.'<td width="431" background="../images/top.gif" valign="top" align="right">'
		.'<table width="396" border="0" cellspacing="0" cellpadding="0">'
		.'<tr><td colspan="2" height="12"></td></tr>'
		.'<tr><td width="369" height="26" align="right">'
		.'<span style="color:#FFFFFF; font-size:16px">'
		.'<strong>International Ship and Port Facility Security</strong></span>'
		.'</td></tr></table>'
		.'</td><td width="38" background="../images/top.gif" align="right">'
		.'<img src="../images/top.gif" /><img src="../images/topline.gif" width="22" height="49" />'
		.'</td><td width="3" rowspan="2" align="right" valign="top" background="../images/side.gif">'
		.'<img src="../images/topt.gif" width="3" /></td></tr>'
		.'<tr><td height="39">&nbsp;</td><td valign="top">'
		.'<img src="../images/topm2.gif" width="296" height="39" /></td>'
		.'<td style="border-bottom:1px solid #dddddd" align="right">'
		.'</td><td><img src="../images/tnkc.gif" width="25" height="40" /></td></tr>';
$footer = '<tr><td colspan="6"><img src="../images/bot.gif" width="800" height="15" /></td></tr>';
?>