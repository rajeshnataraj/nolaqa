<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_STRICT | E_COMPILE_ERROR | E_ERROR | E_WARNING);
$student = isset($_REQUEST['student']) ? $_REQUEST['student'] : '';
$student = explode(",", $student);
$id = (int)$student[0];
$sdistid = $student[1];
$sshlid = $student[2];
$suserid = $student[3];
include("../../includes/UserManager.php");
$userrow = UserManager::db_fetch_userid($id);
$user = new UserManager($userrow);
$profilename = str_replace(" ", "", strtolower($user->prf_name));
if($profilename=="schooldistrictadmin"){
    $profilename = "districtadmin";
}elseif($profilename=="schooladministrator"){
    $profilename = "schooladmin";
}
?>


<section data-type='#users-individuals_profile' id='users-individuals_profile'>

    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="dialogTitle"><?php
                    if($id == 0){
                        echo "New ".$user->prf_name;
                    }else{
                        echo $user->name." (".$user->prf_name.')';
                    }
                    ?></p>
                <p class="dialogSubTitleLight">&nbsp;</p>
                <h1></h1>
            </div>
        </div>
        <div class='row'>
            <div class='twelve columns'>
                <div id="large_icon_recordlist">
                    <a class='skip btn mainBtn' href='#users-individuals-<?php echo $profilename; ?>_new<?php echo $profilename; ?>' id='btnusers-individuals-<?php echo $profilename; ?>_new<?php echo $profilename; ?>'
                       name="<?php echo $id.",".$sdistid.",".$sshlid.",".$suserid;?>">
                        <div class="icon-synergy-user">
                        </div>
                        <div class='onBtn tooltip' title="Personal Information">User Information</div>
                    </a>
                    <a class='skip btn mainBtn' href='#users-individuals-settings' id='btnusers-individuals-settings' name="<?php echo $id.",".$sdistid.",".$sshlid.",".$suserid;?>">
                        <div class="icon-synergy-tools">
                        </div>
                        <div class='onBtn tooltip' title="Account Settings">Account Settings</div>
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>