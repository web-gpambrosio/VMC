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
    		echo $file."  >>>  <br>";
    		$dir1='docimages/'.$file;
    		$handle1 = opendir($dir1);
    		while (false !== ($file1 = readdir($handle1))) 
    		{
    			if ($file1 != "." && $file1 != "..") 
    			{
    				$dirfile1=$dir1.'/'.$file1;
    				$file_parts=pathinfo($dirfile1);
			    	if($file_parts["extension"]=="pdf")
			    	{
				    	$doccode=str_replace("'","",$file_parts["filename"]);
				    	$doccode=str_replace("\"","",$doccode);
			    	}
    				$qrychkdoctype=mysql_query("SELECT DOCUMENT,TYPE FROM crewdocuments WHERE DOCCODE='$doccode'") or die(mysql_error());
    				if(mysql_num_rows($qrychkdoctype)!=0)
    				{
    					$rowchkdoctype=mysql_fetch_array($qrychkdoctype);
    					$document=$rowchkdoctype["DOCUMENT"];
    					$doctype=$rowchkdoctype["TYPE"];
    					$dir2=$dir1.'/'.$doctype;
    					if(!is_dir($dir2))
    					{
    						mkdir($dir2);
    					}
    					copy($dirfile1,$dir2.'/'.$file1);
    					unlink($dirfile1);
    				}
    			}
    		}
    		closedir($dir1);
			$cnt++;
    	}
    }
    closedir($handle);
    echo "<b>$cnt Finished!</b>";
}

?>