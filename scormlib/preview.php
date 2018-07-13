<?php 

$zipname = isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';

$foldername= str_replace('.zip','',$zipname);
$dir = "../uploaddir/webipl/".$foldername."/";
$files1 = scandir($dir);

$files2 = scandir($dir.$files1[2]."/");
$storyflag = 0;

for($i=0;$i<sizeof($files2);$i++){
	if($files2[$i] == "story.html") {
		$storyflag = 1;	
	}
}

if($storyflag == 1) {
	$webiplpath = "story.html";	
}
else {
	$webiplpath = "player.html";	
}

?>
<iframe src="uploaddir/webipl/<?php echo $foldername; ?>/<?php echo $files1[2]; ?>/<?php echo $webiplpath; ?>" width="100%" height="100%;" style="border:none;margin:0 auto;"></iframe>