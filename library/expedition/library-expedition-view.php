<?php
	@include("sessioncheck.php");
	
	$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '0';
	$filename = isset($_REQUEST['filename']) ? $_REQUEST['filename'] : '0';
	
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
	<?php 
	if($type == 1){ 	
		$foldername= str_replace('.zip','',$filename);
		$dir = "../../".$_SESSION['mediaurlpath'].$foldername."/";
		$files1 = scandir($dir);
		$finallessonpath= $_SESSION['mediaurlpath'].$foldername."/".$files1[2]."/story.html";
		?>
    	<iframe src="<?php echo $finallessonpath; ?>" width="100%" height="100%"></iframe>
    	<?php 
	}
	
    else if($type == 2){?>
        <video id="example_video_1" class="video-js vjs-default-skin" controls preload="none" autoplay="true" poster="<?php echo $_SESSION['mediaurlpath'].$filename; ?>" data-setup="{}" style="width:100%; height:100%">
        	<source src="<?php echo $_SESSION['mediaurlpath'].$filename; ?>" type='video/mp4' /> 
        </video>  
    <?php }
    else if($type == 4){?>
		<input type="hidden" id="hidpdfile" name="hidpdfile" value="<?php echo "http://localhost/synergy/".$_SESSION['mediaurlpath'].$filename; ?>" />
        <div id="loadImg" style="text-align:center"><img src="<?php echo __HOSTADDR__; ?>img/ajax-loader.gif"/></div>
        <iframe src="reports/pdfviewer/generic/web/viewer.html" width="100%" height="100%" onload="$('#loadImg').remove(); "></iframe>
    <?php } 
	else if($type == 5){
		$data = getimagesize($_SESSION['mediaurlpath'].$filename);
		$width = $data[0];
		$height = $data[1];
		if($width>600 or $height>900)
		{
		   $src="thumb.php?src=".$_SESSION['mediaurlpath'].$filename."&w=900&h=600&q=100";
		}
		else
		{
		   $src=$_SESSION['mediaurlpath'].$filename;
		}
		?>
		<img id="imgload" src="<?php echo $src;?>" style="border:none; margin:0 auto; max-height:600; max-width:900px; visibility:hidden">
		<script type="text/javascript" language="javascript">
			$('#imgload').attr('src', '<?php echo $src;?>').load(function() {  
				$(this).css('visibility','visible')
			});
        </script> 
    <?php } ?>
</body>
</html>    