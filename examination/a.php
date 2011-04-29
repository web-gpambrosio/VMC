<?php
		$to = "paintcrush17@yahoo.com, rynellecoronacion@yahoo.com, rynelle.coronacion@veritas.com.ph";
		$subject = "ISM Online Examination";
		$message = "RESULTS OF EXAMINATION\n
					Name: ".$gname." ".$mname.". ".$fname."\n
					Type of Exam: ".$exam."\n
					To view and print the result, visit: 
					http://www.veritas.com.ph/examination/ism/ism/print.php?crewcode=". $crewcode ."&takeno=".$takeno."\n
					Thank you.";
		$from = "rynelle.coronacion@veritas.com.ph";
		$headers = "From: $from";
		mail($to,$subject,$message,$headers);
echo "Success..";
?>

