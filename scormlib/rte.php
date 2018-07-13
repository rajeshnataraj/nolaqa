<?php 
@include("table.class.php");
@include("comm_func.php");

// read SCOInstanceID from the GET parameters
$SCOInstanceID = $_REQUEST['SCOInstanceID'];
$lessonid = $_REQUEST['lessonid']; 
$zipname = $_REQUEST['zipname']; 

$foldername= str_replace('.zip','',$zipname);
$dir = "../uploaddir/webipl/".$foldername."/";
$files1 = scandir($dir);
$lessonpath = "../uploaddir/webipl/".$foldername."/".$files1[2]."/index_lms.html";
?>
<html>
<head>
	<title>VS SCORM</title>
	<script language="javascript">
		var started = false;
	
		function loadSCO() {
		  if (! started) {
			SCO.location.href = '<?php echo $lessonpath; ?>';
		  }
		  started = true;
		}
	
		function unloadSCO() {
		  setTimeout('API.LMSFinish("");',2000);
		}
	
	  </script>
</head>
    <frameset frameborder="0" framespacing="0" border="0" rows="0,*" cols="*" onbeforeunload="unloadSCO();" onUnload="unloadSCO();" style="background-image: url(../img/questionloader.gif);	background-repeat:no-repeat;background-position:center;">
      <frame src="api.php?SCOInstanceID=<?php echo $lessonid; ?>&lessonpath=<?php echo $lessonpath; ?>" name="API" id="API" noresize onload="loadSCO();">
      <frame src="blank.html" name="SCO" id="SCO">
    </frameset><noframes></noframes>
</html>