<?php
// Note that !== did not exist until 4.0.0-RC2
///www/veritas/scanned/1998D165
include('veritas/connectdb.php');

echo "<b>Starting...&nbsp;&nbsp;<br /><br /></b>";
if ($handle = opendir('docs')) {
    
    /* This is the correct way to loop over the directory. */
//    rename('/www/veritas/scanned/1998D165/test/carlo','/www/veritas/scanned/1998D165/test/carlo pa rin');
	$cnt=0;
	
    while (false !== ($file = readdir($handle))) 
    {
    	if ($file != "." && $file != "..") 
    	{
    		echo $file."  >>>  ";
    		$qrychange=mysql_query("SELECT APPLICANTNO FROM crew WHERE CREWCODE='$file'") or die(mysql_error());
    		if($qrychange)
    		{
    			$rowchange=mysql_fetch_array($qrychange);
    			$applicantno=$rowchange["APPLICANTNO"];
    			
    			rename('docs/'.$file,'docs/'.$applicantno);
    			echo $applicantno."<br>";
    			
    			if ($handle2 = opendir('docs/'.$applicantno)) {
    				
				    while (false !== ($file2 = readdir($handle2))) 
				    {
						if ($file2 != "." && $file2 != "..")
						{
							rename('docs/'.$applicantno.'/'.$file2 , 'docs/' . $applicantno. '/' . $applicantno.'_'.$file2);
							copy('docs/' . $applicantno. '/' . $applicantno.'_'.$file2 , '/usr/local/www/data/veritas/scanned/'.$applicantno.'_'.$file2);
							echo 'copy from docs/'.$applicantno.'/'.$file2 . " to " . 'docs/' . $applicantno. '/' . $applicantno.'_'.$file2 . "<br>";
						}
				    	
				    }
				    closedir($handle2);
    			}
    			
    		}
//	        	copy('/www/veritas/scanned/1998D165/'.$file,'/www/veritas/scanned/1998D165/test/'.$file_parts['filename'].'_1.'.$file_parts['extension']);
//	        	rename('/www/veritas/scanned/1998D165/test/'.$file_parts['filename'].'_1.'.$file_parts['extension'],'/www/veritas/scanned/1998D165/test/'.$file);
//			unlink('/www/veritas/scanned/1998D165/11.pdf');
    	}
    	$cnt++;
    }
    closedir($handle);
    echo "<b>Finished!</b>";
//     echo "<br><br>";
//    sort($files);
//    $filearrtemp="";
//    foreach ($files as $val) {
//    	$filearr=explode("_",$val);
//    	if($filearrtemp!=$filearr[0])
//    	{
//    		echo $filearr[0]."<br>";
//    	}
////    		$files[] = $filearr[0];
//    	$filearrtemp=$filearr[0];
//    }
}

?>