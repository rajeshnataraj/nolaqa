<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/
@include_once('comm_func.php');

// Define a destination
$targetFolder = '/uploaddir'; // Relative to the root

$verifyToken = md5('nanonino' . $_POST['timestamp']);
if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$oper = $_POST['oper'];
	
	
		$foldername = $oper;

		
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder."/".$foldername."/";
	
	// Validate the file type
	$fileTypes = array('jpg','jpeg','gif','png','bmp','xls','xlsx','docx','pdf','txt','ppt','pptx','doc','aac','ac3','mp3','wav','wma','aa3','m4b','frg','flp',
'swf','avi','flv','mp4','mpg','wmv','divx','mpeg','zip','sbook','csv'); // File extensions
	
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	$fileExt = strtolower($fileParts['extension']);
		
	$tempfname = preg_replace("/[^a-zA-Z0-9s]/", "", $fileParts['filename']);
	$filename = trim($tempfname)."_".time();
	$targetFile = rtrim($targetPath,'/') . '/' .$filename.".".$fileExt;
	
	if (in_array($fileParts['extension'],$fileTypes)) {
		
		if(move_uploaded_file($tempFile,$targetFile)){
			
			
				echo $filename.".".$fileExt;
			
		}
		else {
			echo "upload failed";	
		}
		
	} else {
	
		echo 'Invalid file type.';
	
	}
}
