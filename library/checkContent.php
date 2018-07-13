<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_STRICT | E_COMPILE_ERROR | E_ERROR | E_WARNING);
@include("ContentManager.php");
if (isset($_POST["content_id"]) && !empty($_POST["category"])) {
    $content_id = (int)($_POST["content_id"]);
    $category = $_POST["category"];

    $content = new contentManager($content_id, $category);
    if($content->disabled){
        if($content->note != null && $content->note != ""){
            echo $content->note;
        }else{
            echo 'We apologize for any inconvenience. This product will be available shortly.';
        }
    }
}
?>