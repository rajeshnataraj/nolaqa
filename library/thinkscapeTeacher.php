<?php
require_once("../sessioncheck.php");
require_once("../includes/config.php");
# ----------------------
# Configuration
//echo $uid;
//echo "gi";
//exit();
$sid = isset($_REQUEST['sid']) ? $_REQUEST['sid'] : '0';
$stype = isset($_REQUEST['stype']) ? $_REQUEST['stype'] : '0';
$classid = isset($_REQUEST['classid']) ? $_REQUEST['classid'] : '0';
$date=date("Y-m-d H:i:s");
//if($type==1)//expedition
//{
//
//    $launch_url = $ObjDB->SelectSingleValue("SELECT fld_thinksp FROM itc_exp_master WHERE  fld_id='".$expid."' AND fld_delstatus='0'");
//
//    $resname = $ObjDB->SelectSingleValue("SELECT fld_exp_name FROM itc_exp_master WHERE  fld_id='".$expid."' AND fld_delstatus='0'");
//}
//if($type==2)//destination
//{
//    $launch_url = $ObjDB->SelectSingleValue("SELECT fld_thinksp FROM itc_exp_destination_master WHERE  fld_id='".$destid."' AND fld_delstatus='0'");
//    $resname = $ObjDB->SelectSingleValue("SELECT fld_dest_name FROM itc_exp_destination_master WHERE  fld_id='".$destid."' AND fld_delstatus='0'");
//}
//if($type==3)//task
//{
//    $launch_url = $ObjDB->SelectSingleValue("SELECT fld_thinksp FROM itc_exp_task_master WHERE  fld_id='".$taskid."' AND fld_delstatus='0'");
//    $resname = $ObjDB->SelectSingleValue("SELECT fld_task_name FROM itc_exp_task_master WHERE  fld_id='".$taskid."' AND fld_delstatus='0'");
//}
//if($type==4)//resources
//{
//    $launch_url = $ObjDB->SelectSingleValue("SELECT fld_thinksp FROM itc_exp_resource_master WHERE  fld_id='".$resid."' AND fld_delstatus='0'");
//    $resname = $ObjDB->SelectSingleValue("SELECT fld_res_name FROM itc_exp_resource_master WHERE  fld_id='".$resid."' AND fld_delstatus='0'");
//}
//if($stype ==='15')
//{
//    $tablename = "itc_class_indasexpedition_master"; // Expedition
////    $scheduletype="Expedition";
//}
//if($stype ==='19'){
//    $tablename = "itc_class_rotation_expschedule_mastertemp"; // Expedition sch
////    $scheduletype="Expedition schedule";
//}
//if($stype ==='20'){
//    $tablename = "itc_class_rotation_modexpschedule_mastertemp"; // Expedition and module Sch
////    $scheduletype="Expedition and schedule";
//}
//if($sessprofileid != 10)
//{
//    $classid = $ObjDB->SelectSingleValue("SELECT fld_class_id  FROM ".$tablename." WHERE fld_id='".$sid."'");
//
//    $classname = $ObjDB->SelectSingleValue("SELECT fld_class_name  FROM itc_class_master WHERE fld_id='".$classid."'");
//}
//else
//{
$launch_url =
    $classname = $ObjDB->SelectSingleValue("SELECT fld_class_name  FROM itc_class_master WHERE fld_id='".$classid."'");
//}
$role = $ObjDB->SelectSingleValue("SELECT b.fld_profile_name FROM itc_user_master AS a LEFT JOIN itc_profile_master AS b ON a.fld_profile_id=b.fld_id  WHERE  a.fld_id='".$uid."' AND a.fld_delstatus='0' AND b.fld_delstatus='2'");
//$qryschool = $ObjDB->QueryObject("SELECT  fld_username AS uname,fld_email AS email,fld_fname AS fname,fld_lname AS lname  FROM itc_user_master WHERE fld_id='".$id."'");
//$rowqryschool = $qryschool->fetch_assoc();
//extract($rowqryschool);
$key = THINKSCAPE_KEY;
$secret = THINKSCAPE_SECRET;
//
//
$launch_data = array(
    "user_id" => $uid,
    "roles" => $role,
    "lis_person_name_full" => $username,
    "lis_person_name_family" => $sessusrfullname,
    "lis_person_name_given" => $sessusrfullname,
    "lis_person_contact_email_primary" => $email,
    "lis_person_sourcedid" => "school.edu:user",
    "context_id" => $sid,
    "context_title" => $classname,
    "context_label" => $classname,
    "context_type" => "Classroom",
    "tool_consumer_instance_guid" => "localhost",
    "tool_consumer_instance_description" => "Synergy"
);
//# End of Configuration
//# ----------------------
//
$now = new DateTime();
//
//
$launch_data["lti_version"] = "LTI-1p0";
$launch_data["lti_message_type"] = "basic-lti-launch-request";
//
//# Basic LTI uses OAuth to sign requests
//# OAuth Core 1.0 spec: http://oauth.net/core/1.0/
$launch_data["oauth_callback"] = "about:blank";
$launch_data["oauth_consumer_key"] = $key;
$launch_data["oauth_version"] = "1.0";
$launch_data["oauth_nonce"] = uniqid('', true);
$launch_data["oauth_timestamp"] = $now->getTimestamp();
$launch_data["oauth_signature_method"] = "HMAC-SHA1";
//
//# Next we need to deal with any query parameters that may be in the LTI URL.
$launch_url = "https://mythinkscape.com/lti/synergy_dashboard/$sid?direct=1";
$url_parts = parse_url($launch_url);
if ($url_parts['port']) {
    $base_url = $url_parts['scheme'] . '://' . $url_parts['host'] . ":" . $url_parts['port'] . $url_parts['path'];
} else {
    $base_url = $url_parts['scheme'] . '://' . $url_parts['host'] . $url_parts['path'];
}
// devstudent.laboctopus.com/lti/synergy_dashboard/<context_id> POST
//# We next need to add query parameters to the launch data so that Oauth properly signs the request.
parse_str($url_parts['query'], $query_params);
foreach($query_params as $key => $value) {
    $launch_data[$key] = $value;
}
//
//# In OAuth, request parameters must be sorted by name
$launch_data_keys = array_keys($launch_data);
sort($launch_data_keys);
//
$launch_params = array();
foreach ($launch_data_keys as $key) {
    array_push($launch_params, $key . "=" . rawurlencode($launch_data[$key]));
}
//
$base_string = "POST&" . urlencode($base_url) . "&" . rawurlencode(implode("&", $launch_params));
$secret = urlencode($secret) . "&";
$signature = base64_encode(hash_hmac("sha1", $base_string, $secret, true));

?>

<html>
<head></head>
<body onload="document.ltiLaunchForm.submit();">
<!--<body>-->
<form id="ltiLaunchForm" name="ltiLaunchForm" method="POST" action="<?php printf($launch_url); ?>">
    <?php foreach ($launch_data as $k => $v ) { ?>
        <input type="hidden" name="<?php echo $k ?>" value="<?php echo $v ?>">
    <?php } ?>
    <input type="hidden" name="oauth_signature" value="<?php echo $signature ?>">
<!--    <button type="submit">Launch</button>-->
</form>

</body>
</html>

<script type="text/javascript">
    document.ltiLaunchForm.submit();
    //    console.log("hai"+schedulename);
    //    console.log("<?php //echo $classid.' '.$sid.' '.$stype; ?>//");
    console.log("<?php json_encode($launch_data); ?>");
</script>