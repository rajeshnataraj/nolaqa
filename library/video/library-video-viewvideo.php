<?php
	@include("sessioncheck.php");
	
	$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '0';
	echo $filename = isset($_REQUEST['filename']) ? $_REQUEST['filename'] : '0';
        
	
	header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
	header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
	header( "Cache-Control: no-cache, must-revalidate" );
	header( "Pragma: no-cache" );
	
	$content = ob_get_clean();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../../css/video-js.css" rel="stylesheet" type="text/css">

</head>

<body>
<video id="example_video_1" class="video-js vjs-default-skin" controls preload="none" autoplay="true" poster="<?php echo _CONTENTURL_; ?>sosvideo/<?php echo $filename; ?>" data-setup="{}" style="width:100%; height:100%">
<source src="<?php echo _CONTENTURL_; ?>sosvideo/<?php echo $filename; ?>" type='video/mp4' /> 
</video>  
    
</body>
</html>    