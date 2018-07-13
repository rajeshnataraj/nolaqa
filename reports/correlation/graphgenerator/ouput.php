<?php
ob_start();
 
echo "<html><head><title>Test</title></head>";
echo "<body><h1>It works!</h1></body></html>";
 
$contents = ob_get_clean();
 
if(file_put_contents("SVGGraph3DGraph.php", $contents)) {
  echo "Success";
}
else {
  echo "Failed to save file";
}
?>