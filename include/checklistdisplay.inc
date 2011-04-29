<?php					

		if ($applicantno != "")
		{
//			CHECK IF APPLICANT IS 
//				1 = NEW HIRE
//				0 = EX-CREW
			
			
			$qrycheckstatus = mysql_query("SELECT c.APPLICANTNO 
											FROM crew c
											LEFT JOIN applicantstatus a ON a.APPLICANTNO=c.APPLICANTNO
											WHERE c.APPLICANTNO=$applicantno AND c.CREWCODE IS NULL
										") or die(mysql_error());
			
			if (mysql_num_rows($qrycheckstatus) > 0)
				$applicanttype = "1";
			else 
				$applicanttype = "0";
			
			
			switch ($applicanttype)
			{
				case "1"	: //NEW HIRE

					$qrychecklist = mysql_query("SELECT * FROM (
												SELECT t.IDNO,t.RANKCODE,t.TRAINGROUPNO,t.TRAINCODE,tc.TRAINING,t.REQUIRED,tc.ALIAS,
												th.SCHEDID,th.DATEFROM,th.DATETO,td.INSTRUCTOR,tv.TRAINVENUE,tc.COURSETYPECODE,cy.COURSETYPE
							                    FROM applicantstatus ap
												LEFT JOIN trainingchecklist t ON t.RANKCODE=ap.VMCRANKCODE
							                    LEFT JOIN crewtrainstatus ct ON ct.APPLICANTNO=ap.APPLICANTNO
												LEFT JOIN trainingcourses tc ON tc.TRAINCODE=t.TRAINCODE
							                    LEFT JOIN trainingschedhdr th ON th.SCHEDID=ct.SCHEDID AND th.TRAINCODE=t.TRAINCODE
							                    LEFT JOIN trainingscheddtl td ON td.SCHEDID=th.SCHEDID
							                    LEFT JOIN trainingvenue tv ON tv.TRAINVENUECODE=td.TRAINVENUECODE
												LEFT JOIN coursetype cy ON cy.COURSETYPECODE=tc.COURSETYPECODE
												WHERE t.STATUS=1 AND ap.APPLICANTNO=$applicantno AND (ct.SCHEDID=th.SCHEDID OR th.SCHEDID IS NULL)
		                						ORDER BY t.IDNO,th.SCHEDID DESC
												) x
												GROUP BY IDNO
												ORDER BY x.COURSETYPECODE,x.TRAINCODE
												") or die(mysql_error());
				
				break;
				
				case "0"	: //EX-CREW
				
					$qrychecklist = mysql_query("SELECT * FROM (
												SELECT t.IDNO,t.RANKCODE,t.TRAINGROUPNO,t.TRAINCODE,tc.TRAINING,t.REQUIRED,tc.ALIAS,
												th.SCHEDID,th.DATEFROM,th.DATETO,td.INSTRUCTOR,tv.TRAINVENUE,tc.COURSETYPECODE,cy.COURSETYPE
							                    FROM applicantstatus ap
												LEFT JOIN trainingchecklist t ON t.RANKCODE=ap.VMCRANKCODE
							                    LEFT JOIN crewtrainstatus ct ON ct.APPLICANTNO=ap.APPLICANTNO
												LEFT JOIN trainingcourses tc ON tc.TRAINCODE=t.TRAINCODE
							                    LEFT JOIN trainingschedhdr th ON th.SCHEDID=ct.SCHEDID AND th.TRAINCODE=t.TRAINCODE
							                    LEFT JOIN trainingscheddtl td ON td.SCHEDID=th.SCHEDID
							                    LEFT JOIN trainingvenue tv ON tv.TRAINVENUECODE=td.TRAINVENUECODE
												LEFT JOIN coursetype cy ON cy.COURSETYPECODE=tc.COURSETYPECODE
												WHERE t.STATUS=1 AND ap.APPLICANTNO=$applicantno AND (ct.SCHEDID=th.SCHEDID OR th.SCHEDID IS NULL)
		                						ORDER BY t.IDNO,th.SCHEDID DESC
												) x
												GROUP BY IDNO
												ORDER BY x.COURSETYPECODE,x.TRAINCODE
												") or die(mysql_error());
				
				break;
			}
				

		}
					
echo "			
			<table style=\"width:100%;background-color:#333333;\" class=\"listrow\">
				<tr>
					<th style=\"color:White;\">Applicant No:&nbsp;&nbsp;<span style=\"font-size:1.2em;font-weight:Bold;color:Lime;\">$applicantno</span></th>
					<th style=\"color:White;\">Name:&nbsp;&nbsp;<span style=\"font-size:1.2em;font-weight:Bold;color:Lime;\">$applicantname</span></th>
					<th style=\"color:White;\">Rank:&nbsp;&nbsp;<span style=\"font-size:1.2em;font-weight:Bold;color:Lime;\">$applicantrank</span></th>
				</tr>
			</table>
";
					
			if (mysql_num_rows($qrychecklist) > 0)
			{
				
				echo "
				<div style=\"width:100%;height:100%;overflow:auto;\">
					
					<table width=\"100%\" style=\"font-size:0.7em;background-color:#D0D2FF;border:1px solid black;\">
						<tr style=\"font-weight:bold;background-color:Black;color:White;\">
							<td width=\"40%\" align=\"center\">COURSE</td>
							<td width=\"30%\" align=\"center\">STATUS</td>
							<td width=\"30%\" align=\"center\">SCHEDULE</td>
						</tr>
						";
							$tmpgroup = "";
							//th.SCHEDID,th.DATEFROM,th.DATETO,td.INSTRUCTOR,tv.TRAINVENUE
							while ($rowchecklist = mysql_fetch_array($qrychecklist))
							{
								$chkidno = $rowchecklist["IDNO"];
								$chkrankcode = $rowchecklist["RANKCODE"];
								$chktraingroupno = $rowchecklist["TRAINGROUPNO"];
								$chkcoursetypecode = $rowchecklist["COURSETYPECODE"];
								$chkcoursetype = $rowchecklist["COURSETYPE"];
								$chktraincode = $rowchecklist["TRAINCODE"];
								$chktraining = $rowchecklist["TRAINING"];
								$chkrequired = $rowchecklist["REQUIRED"];
								$chktype = $rowchecklist["TYPE"];
								$chkalias = $rowchecklist["ALIAS"];
								$chkschedid = $rowchecklist["SCHEDID"];
								$chkdatefrom = date('dMY',strtotime($rowchecklist["DATEFROM"]));
								$chkdateto = date('dMY',strtotime($rowchecklist["DATETO"]));
								$chkinstructor = $rowchecklist["INSTRUCTOR"];
								$chktrainvenue = $rowchecklist["TRAINVENUE"];
								
								if ($chkcoursetypecode != $tmpgroup)
								{
									$style = "style=\"background-color:Navy;color:Yellow;font-weight:Bold;font-size:1.2em;text-align:center;\"";
									
									echo "<tr><td colspan=\"4\" $style>$chkcoursetype</td></tr>";
									
								}
								
								if ($chkschedid == "")
								{
									echo "
									<tr style=\"border:thin solid black;\">
										<td style=\"border:1px solid gray;font-weight:Bold;\">$chktraining</td>
										<td style=\"border:1px solid gray;\" align=\"center\">---</td>
										<td style=\"border:1px solid gray;\" align=\"center\">
										[NO SCHEDULE YET]
					<!--
											<a href=\"#\" onclick=\"openWindow('enrollment.php?traincode=$chktraincode&applicantno=$applicantno','enrollment' ,800, 565);\" 
												style=\"font-size:1em;font-weight:bold;color:#FF6600;\">[SCHEDULE]</a>
					-->
										</td>
									</tr>
									";
								}
								else 
								{
									echo "
									<tr style=\"border:thin solid black;background-color:#FFFF66;\" title=\"Instructor: $chkinstructor; Venue: $chktrainvenue \">
										<td style=\"border:1px solid gray;font-weight:Bold;\">$chktraining</td>
										<td colspan=\"2\" style=\"border:1px solid gray;\" align=\"center\">
											<b>Scheduled on</b> &nbsp;&nbsp;
											<a href=\"#\" onclick=\"\" style=\"font-size:1em;font-weight:bold;color:#006600;\">$chkdatefrom - $chkdateto</a>
										</td>
									</tr>
									";
								}
								
								$tmpgroup = $chkcoursetypecode;
							}
				echo "
					</table>
				</div>
				";
			}
			else 
			{
				echo "
				<div style=\"width:100%;height:400px;overflow:auto;\">
					<br /><br /><br /><br /><br /><br /><br />
					<center>
						<span style=\"font:bold 1.3em;color:gray;\"><i>[ CHECKLIST Preview ]</i></span>
					</center>
				</div>
				
				";
			}
			
?>