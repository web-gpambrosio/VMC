<?php					

	$qrychecklist = mysql_query("SELECT t.IDNO,t.TRAINCODE,tc.TRAINING,tc.ALIAS,t.RANKCODE,t.REQUIRED,
									tc.COURSETYPECODE,cy.COURSETYPE,tc.DOCCODE,tc.STATUS
								FROM trainingchecklist t
								LEFT JOIN trainingcourses tc ON tc.TRAINCODE=t.TRAINCODE
								LEFT JOIN coursetype cy ON cy.COURSETYPECODE=tc.COURSETYPECODE
								WHERE t.RANKCODE='$currentrankcode' AND tc.STATUS=1
								ORDER BY tc.COURSETYPECODE,t.TRAINCODE
								") or die(mysql_error());
	
	$qrychecklist2 = mysql_query("SELECT * FROM
								(
									SELECT 1 AS POS, tc.COURSETYPECODE,cy.COURSETYPE,t.IDNO,t.TRAINCODE,tc.TRAINING,tc.ALIAS,
									t.RANKCODE,t.REQUIRED,tc.DOCCODE,tc.STATUS
									FROM trainingchecklist t
									LEFT JOIN trainingcourses tc ON tc.TRAINCODE=t.TRAINCODE
									LEFT JOIN coursetype cy ON cy.COURSETYPECODE=tc.COURSETYPECODE
									WHERE t.RANKCODE='$currentrankcode' AND tc.STATUS=1
								
									UNION
									
									SELECT 2 AS POS, trc.COURSETYPECODE,cy.COURSETYPE,
									tr.IDNO,trc.TRAINCODE,trc.TRAINING,trc.ALIAS,tr.RANKCODE,tr.REQUIRED,trc.DOCCODE,trc.STATUS
									FROM trainingcourses trc
									LEFT JOIN trainingchecklist tr ON tr.TRAINCODE=trc.TRAINCODE
									LEFT JOIN coursetype cy ON cy.COURSETYPECODE=trc.COURSETYPECODE
									WHERE trc.TRAINCODE NOT IN (
										SELECT tx.TRAINCODE
										FROM trainingchecklist tx
										LEFT JOIN trainingcourses tcx ON tcx.TRAINCODE=tx.TRAINCODE
										WHERE tx.RANKCODE = '$currentrankcode' AND tcx.STATUS=1
									) AND trc.STATUS=1
									GROUP BY trc.TRAINCODE
								) z
								ORDER BY POS,COURSETYPE,TRAINING
								") or die(mysql_error());

			
?>