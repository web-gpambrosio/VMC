<?php


echo "
			<br><br><br><br>
	<span style=\"font-size:1.5em;font-weight:Bold;color:Blue;\">$title</span>
	<br /><br />
	
	<div style=\"width:100%;font-size:1.1em;\">
		<div style=\"width:50%;height:70px;float:left;background-color:#DCDCDC;\">
			<table width=\"100%\" class=\"listrow\">
				<tr>
					<th>NAME</th>
					<th>:</th>
					<td style=\"font-size:1.2em;\">$crewname</td>
				</tr>
				<tr>
					<th>CREWCODE</th>
					<th>:</th>
					<td>$crewcode</td>
				</tr>
				<tr>
					<th>APPLICANT NO</th>
					<th>:</th>
					<td>$applicantno</td>
				</tr>
			</table>
		</div>
		
		<div style=\"width:49%;height:70px;float:right;background-color:#DCDCDC;\">
		
			<table width=\"100%\" class=\"listrow\">
				<tr>
					<th>CURRENT RANK</th>
					<th>:</th>
					<td>$currentrank</td>
				</tr>
				<tr>
					<th>PRINT DATE</th>
					<th>:</th>
					<td>$currentdate</td>
				</tr>
			</table>
		</div>
	</div>
	
	<hr />

";

?>