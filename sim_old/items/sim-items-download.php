<?php
	@include("../../sessioncheck.php");
       
        $filename = (isset($_REQUEST['filename'])) ? $_REQUEST['filename'] : '';
        
       
        if($filename != ''){
		$fullpath = _CONTENTURL_."pim/".$filename;
                $ctype="application/".$fileformat;
                
                header("Content-type:".$ctype); 		
		header("Content-disposition: attachment; filename=".$filename); 
		readfile($fullpath);
		
	}

	@include("footer.php");

