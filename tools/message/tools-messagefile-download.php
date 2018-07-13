<?php
	@include("../../sessioncheck.php");
       
        $msgid = (isset($_REQUEST['msgid'])) ? $_REQUEST['msgid'] : '';
        $ids = (isset($_REQUEST['ids'])) ? $_REQUEST['ids'] : '';
        
        
        $filename=$ObjDB->SelectSingleValue("SELECT fld_file_name AS filname, fld_file_type AS filtype FROM itc_message_upload_mapping 
                                                 WHERE fld_messageid='".$msgid."' AND fld_id='".$ids."'");
        
        $filename = htmlspecialchars(urldecode($filename));
        if($filename != ''){
		$fullpath = _CONTENTURL_."message/".$filename;
                $ctype="application/".$filtype;
                
                header("Content-type:".$ctype); 		
		header("Content-disposition: attachment; filename=".$filename); 
		readfile($fullpath);
		
	}

	@include("footer.php");

