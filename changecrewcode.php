<?php
// Note that !== did not exist until 4.0.0-RC2
///www/veritas/scanned/1998D165
include('veritas/connectdb.php');

echo "<b>Starting...&nbsp;&nbsp;<br /><br /></b>";
if ($handle = opendir('docimages')) {
    
    /* This is the correct way to loop over the directory. */
//    rename('/www/veritas/scanned/1998D165/test/carlo','/www/veritas/scanned/1998D165/test/carlo pa rin');
	$cnt=0;
	
    while (false !== ($file = readdir($handle))) 
    {
    	if ($file != "." && $file != "..") 
    	{
    		echo $file."  >>>  ";
    		$qrychange=mysql_query("SELECT c.APPLICANTNO 
    								FROM crew c
    								LEFT JOIN oldapp o ON o.APPLICANTNO=c.APPLICANTNO
    								WHERE o.OLDAPP='$file'") or die(mysql_error());
    		if($qrychange)
    		{
    			$rowchange=mysql_fetch_array($qrychange);
    			$applicantno=$rowchange["APPLICANTNO"];
    			rename('docimages/'.$file,'docimages/'.$applicantno);
    			echo $applicantno."<br>";
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