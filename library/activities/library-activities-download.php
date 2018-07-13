<?php
	@include("sessioncheck.php");
	
	$filename = (isset($_REQUEST['filename'])) ? $_REQUEST['filename'] : '';
	
	if($filename != ''){
		$fullpath = __CNTPATH__."activity/".$filename;
		//echo $fullpath;
		header("Content-type: application/force-download"); 
		header("Content-Transfer-Encoding: Binary"); 
		header("Content-length: ".filesize($fullpath));
		header("Content-disposition: attachment; filename=".$filename); 
		readfile($fullpath);
	}
	
	@include("footer.php");