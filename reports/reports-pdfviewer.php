<?php
ini_set("max_execution_time", 30);
@include("sessioncheck.php");
$method=$_REQUEST;

$id = isset($method['id']) ? $method['id'] : '0';
$oper = isset($method['oper']) ? $method['oper'] : '';
$filename = isset($method['filename']) ? $method['filename'] : '';
$studentlist = isset($method['studentlist']) ? $method['studentlist'] : '';
$assignmentlist = isset($method['assignmentlist']) ? $method['assignmentlist'] : '';
$classlist = isset($method['classlist']) ? $method['classlist'] : '';
$teacherlist = isset($method['teacherlist']) ? $method['teacherlist'] : '';
$assignments = isset($method['assignments']) ? $method['assignments'] : '';
$downloadid = isset($_REQUEST['downloadid']) ? $_REQUEST['downloadid'] : '0';

$flg = isset($_REQUEST['flg']) ? $_REQUEST['flg'] : '0'; // created by chandra

$content = ob_get_clean();

$content_url = REPORT_SERVER_URL.'index.php?id='.$id.'&oper='.$oper.'&filename='.$filename.'&downloadid='.$downloadid.'&hostname='.$_SERVER['SERVER_NAME'].'&uid='.$uid.'&sessmasterprfid='.$sessmasterprfid.'&sessionid='.$sessionid.'&schoolid='.$schoolid.'&studentlist='.$studentlist.'&assignmentlist='.$assignmentlist.'&assignments='.$assignments.'&classlist='.$classlist.'&teacherlist='.$teacherlist.'&flg='.$flg.'';
$content = file_get_contents($content_url);

//retrieve contents specified
//$cURL = curl_init($content_url);
//curl_setopt($cURL, CURLOPT_PORT, __REPORT_SERVER_PORT__);
//curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
//$content = curl_exec($cURL);

echo $content;
@include("footer.php");