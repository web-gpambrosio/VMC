<?php


echo "

		<div style=\"width:1000px;height:140px;\">

			<div style=\"width:50%;float:right;text-align:center;z-index:100;margin-top:20px;text-align:right;border:1px solid gray;\">
				<table class=\"listrow\" width=\"100%\">
					<tr>
						<td style=\"font-weight:Bold;border-right:1px solid Gray;\"><a href=\"#\" onclick=\"openWindow('iso/index.php', 'iso', 900, 600);\">ISO MANUAL</a></td>
						<td style=\"font-weight:Bold;border-right:1px solid Gray;\"><a href=\"#\" onclick=\"openWindow('reports/VMCDIR.pdf', 'iso', 900, 600);\">VMC DIR</a></td>
						<td style=\"font-weight:Bold;border-right:1px solid Gray;\"><a href=\"#\" onclick=\"document.getElementById('showbody').src='home.php?showsearch=1';\">SEARCH</a></td>
						<td style=\"font-weight:Bold;border-right:1px solid Gray;\"><a href=\"#\" onclick=\"openWindow('dailyreport.php', 'dailyrep', 900, 600);\">APP REPORT</a></td>
						<td style=\"font-weight:Bold;border-right:1px solid Gray;\"><a href=\"#\" onclick=\"openWindow('application.php?application=2', 'onlineapp', 900, 600);\">ONLINE APP</a></td>
						<td style=\"font-weight:Bold;border-right:1px solid Gray;\"><a href=\"#\" onclick=\"openWindow('kiosk.php', 'kiosk', 900, 600);\">KIOSK</a></td>
						<td style=\"font-weight:Bold;border-right:1px solid Gray;\"><a href=\"#\" onclick=\"openWindow('staffdb.php', 'staffdb', 0, 0);\">STAFF DB</a></td>
						<td style=\"font-weight:Bold;border-right:1px solid Gray;\"><a href=\"#\" onclick=\"openWindow('index.php?logout=1', 'index', 0, 0);\">LOGOUT</a></td>
					</tr>
				</table>
			</div>
			<img src=\"images/footer.gif\" align=\"right\" style=\"z-index:5;\"/>
		</div>
	
		<input type=\"hidden\" name=\"actiontxt\" />
		<input type=\"hidden\" name=\"applicantno\" />
		
		
	</form>
</body>


</html>
";
?>
