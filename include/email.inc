<?php
require_once("/usr/local/www/data/veritas/Mailclass.inc");
require_once("/usr/local/www/data/veritas/config.php");

//include("/usr/local/www/data/veritas/Mailclass.inc");
//include("/usr/local/www/data/veritas/config.php");
//require_once("include/Mailclass.inc");
//require_once("include/config.php");

	// instantiate the class
	$mailer = new VMCMailer();
	
	// Set the subject
	$mailer->Subject = $subject;
	
	$mailer->IsHTML(true);
	//$mailer->IsSMTP();
	
	// Body
	$mailer->Body = $body;
	
	
	switch ($sendemailtype)
	{
		case "1"	:  //STAFF BIRTHDAY
			
				$bdayceleb = explode("|",$celebrants);
				$cnt = count($bdayceleb);
				
				for ($i=0;$i<$cnt;$i++)
				{
					$bdaydtls = explode(",",$bdayceleb[$i]);
					$emailto = $bdaydtls[0];
					$vmcemail = $bdaydtls[1];
					
					$mailer->AddAddress($vmcemail, $emailto);
					$mailer->AddAddress("gino.ambrosio@yahoo.com.ph", "Gino Ambrosio");
					$mailer->AddBCC("vmcgroup@veritas.com.ph", "VMC Group");
					$mailer->AddBCC("vmccomm@veritas.com.ph", "VMC Comm");
					$mailer->AddBCC("jien@veritas.com.ph", "Jien Simbulan");
//					$mailer->AddBCC("gino.ambrosio@gmail.com", "Gino Ambrosio");
				}
			
			break;
			
		case "2"	:  //CREW BIRTHDAY
					
			break;
			
		case "3"	:  //NEW APPLICANTS
		
					$mailer->AddAddress("vmcgroup@veritas.com.ph", "VMC Group");
//					$mailer->AddAddress("pia.reyes@veritas.com.ph", "Pia Reyes");
//					$mailer->AddAddress("daphne@veritas.com.ph", "Daphne");
//					$mailer->AddAddress("boy.punzalan@veritas.com.ph", "Boy Punzalan");
//					$mailer->AddAddress("irene.deguzman@veritas.com.ph", "Irene De Guzman");
//					$mailer->AddAddress("esther.tundag@veritas.com.ph", "Esther Tundag");
//					$mailer->AddBCC("gino.ambrosio@yahoo.com.ph", "From VERIPRO");
					$mailer->AddBCC("jien@veritas.com.ph", "Jien Simbulan");
//					$mailer->AddCC("kono@veritas.com.ph","Capt. Makoto Kono"); ^M
					
			
			
			break;
	}
	

	
	
	
////		$mailer->AddAddress("jmarquez@veritas.com.ph", "Jiro M. Marquez");
////		$mailer->AddAddress("dan.cardozo@veritas.com.ph", "Danilo Cardozo");
////		$mailer->AddAddress("romo@veritas.com.ph", "Felix M. Romo");
////		$mailer->AddAddress("alvaro@veritas.com.ph", "Andres F. Alvaro");
////		$mailer->AddAddress("vmcsupvr@veritas.com.ph", "VMC Supervisors");
		
//			$mailer->AddCC("jien@veritas.com.ph", "Jien Simbulan");

//			$mailer->AddBCC("gino.ambrosio@yahoo.com.ph", "Gino (BCC)");


?>
