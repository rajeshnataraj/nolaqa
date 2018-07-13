<?php
	@include("../../sessioncheck.php");
       
        $filename = (isset($_REQUEST['filename'])) ? $_REQUEST['filename'] : '';
        $fileformat = (isset($_REQUEST['fileformat'])) ? $_REQUEST['fileformat'] : '';
        sleep(3);
        $filename = htmlspecialchars(urldecode($filename));
        $filename = $filename.".pdf";
        if($filename != ''){
		$fullpath =REPORT_SERVER_URL.'pdf/'.$filename;
                $ctype="application/".$fileformat;
                
                header("Content-type:".$ctype); 		
		header('Content-disposition: attachment; filename='.$filename); 
		readfile($fullpath);
		
	}

	@include("footer.php");

?>