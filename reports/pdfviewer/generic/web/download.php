<?php
	@include("sessioncheck.php");
	
	$filename = (isset($_REQUEST['filename'])) ? $_REQUEST['filename'] : '';
	
	if($filename != ''){
		$string = $filename;
                $filename = str_replace("../../../../","",$filename);
                $pos = strpos($string, CONTENT_URL);
                $pos1 = strpos($string, "../../../../reports/correlation/correlationreports/");
                
                if($pos === false) {
                    if($pos1 === false) {
                        $pos2 = strpos($filename, "test/pdf/");
                        if($pos2 === false)
                            $fullpath = $domainame."reports/pdf/".$filename;
                        else
                            $fullpath = $domainame.$filename;
                    }
                    else {
                        $fullpath = $domainame.$filename;
                        $string = str_replace("../../../../reports/correlation/correlationreports/","",$string);
                    }
                    
                }
                else {
                    $fullpath = $filename;
                    $string = str_replace(CONTENT_URL . "/","",$string);
                }
                
                //echo $fullpath;
		header("Content-type: application/force-download"); 
		header("Content-Transfer-Encoding: Binary"); 
		header("Content-length: ".filesize($fullpath));
		header("Content-disposition: attachment; filename=".$string); 
		readfile($fullpath);
	}
	
	@include("footer.php");