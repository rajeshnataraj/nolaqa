<?php
	@include("../../sessioncheck.php");
       
        $filename = (isset($_REQUEST['filename'])) ? $_REQUEST['filename'] : '';
        $fileformat = (isset($_REQUEST['fileformat'])) ? $_REQUEST['fileformat'] : '';
        $filename = htmlspecialchars(urldecode($filename));
        if($filename != ''){
		$fullpath = _CONTENTURL_."asset/".$filename;
                $ctype="application/".$fileformat;
                
                header("Content-type:".$ctype); 
		header("Content-disposition: attachment; filename=".$filename); 
		readfile($fullpath);
		
	}

	@include("footer.php");

