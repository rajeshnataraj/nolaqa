<?php
$name = $_REQUEST['filename'];
$type = (isset($_REQUEST['type']) and ($_REQUEST['type'])) ? $_REQUEST['type'] : 1;
@require('aws/aws-autoloader.php');
use Aws\S3\S3Client;

$client = S3Client::factory(array(
	'key' => 'AKIAIOHHMSQBTQTV5T3A',
	'secret' => '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'
));

$i = 1;

function find_all_files($dir) 
{
	global $i;
	 
	$root = scandir($dir); 
	foreach($root as $value) 
	{ 
		if($value === '.' || $value === '..') {continue;} 
		if(is_file("$dir/$value")) { $result[] = "$dir/$value"; continue;} 
		foreach(find_all_files("$dir/$value") as $value) 
		{ 
			$result[] = $value; 				
			$i++;
		} 
	} 
	return $result; 
} 

if($type == 1) {	
	if($name != ''){		
		$filename = explode(".",$name);
		
		$src = 'uploaddir/webipl/'.$filename[0];
		$dest = 'uploaddir/s3/webipl/';
		
		$totalfiles = find_all_files($src);
		
		for($i=0;$i<sizeof($totalfiles);$i++){
			$sourcefile = str_replace("uploaddir/","",$totalfiles[$i]);
			$result = $client->putObject(array(
			 'Bucket' => 'pitscos3',
			 'Key' => $sourcefile,
			 'SourceFile' => $totalfiles[$i],
			));
		}		
		$url = "/var/www/uploaddir/webipl/".$name;
		mail("sanjay@nanonino.com","IPL uploaded",$url);
		
		
	}
}
else if($type == 2) {
	if($name != ''){
		$filename = explode(".",$name);
		
		$zip = new ZipArchive;
		$res = $zip->open('uploaddir/question/remediations/'.$name);
		$zip->extractTo('uploaddir/question/remediations/'.$filename[0]);
		$zip->close();
		
		$src = 'uploaddir/question/remediations/'.$filename[0];
		$dest = 'uploaddir/s3/question/remediations/';
		
		$totalfiles = find_all_files($src);
		
		for($i=0;$i<sizeof($totalfiles);$i++){
			$sourcefile = str_replace("uploaddir/","",$totalfiles[$i]);
			$result = $client->putObject(array(
			 'Bucket' => 'pitscos3',
			 'Key' => $sourcefile,
			 'SourceFile' => $totalfiles[$i],
			));
		}

		$url = "/var/www/uploaddir/question/remediations/".$name;
		mail("sanjay@nanonino.com","Remediation uploaded",$url);
		unlink($url);
	}
}
else {	
	if($name != ''){
		$filename = explode(".",$name);
		
		$zip = new ZipArchive;
		$res = $zip->open('uploaddir/modules/'.$name);
		$zip->extractTo('uploaddir/modules/'.$filename[0]);
		$zip->close();
		
		$src = 'uploaddir/modules/'.$filename[0];
		$dest = 'uploaddir/s3/modules/';
		
		$totalfiles = find_all_files($src);
		
		for($i=0;$i<sizeof($totalfiles);$i++){
			$sourcefile = str_replace("uploaddir/","",$totalfiles[$i]);
			$result = $client->putObject(array(
			 'Bucket' => 'pitscos3',
			 'Key' => $sourcefile,
			 'SourceFile' => $totalfiles[$i],
			));
		}
		
		$url = "/var/www/uploaddir/modules/".$name;
		mail("saravananc@nanonino.in","Live module uploaded",$url);
                mail("rajesh@nanonino.com","Live module uploaded",$url);
                mail("karthick@nanonino.in","Live module uploaded",$url);
		unlink($url);
	}	
}