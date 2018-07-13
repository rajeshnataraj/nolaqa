<?php
//ini_set('display_errors', true);
//error_reporting(E_ALL);
include("sessioncheck.php");
include("includes/UserManager.php");
//Students should not have access to this page
if ($_SESSION['user_profile'] == 10){
    exit;
}
//upon success, returns success
//upon failure, prints a list of errors.
function process_student_passwords(){
    //get a list of students and their new password.

    $errors = array();
    foreach ($_REQUEST as $key => $value) {
        if (substr($key, 0, 8) == "student_") {
            $student_id = intval(substr($key, 8));
            $new_password = (string)$value;

            //If the empty string was passed in as the password, then
            if ($new_password != '') {
                //should check whether or not the password is valid.
                $student_info = UserManager::db_fetch_userid($student_id);
                $student = new UserManager($student_info);

                $response = $student->update_password($new_password);
                if ($response != 'success')
                    $errors[] = "Failed to update password for student with username ".$student->username.".";
            }
        }
    }
    if (count($errors) > 0)
        echo "number of errors:" . count($errors);
    else
        echo "success";
}
process_student_passwords();
?>