<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_STRICT | E_COMPILE_ERROR | E_ERROR | E_WARNING);
if (isset($_POST["password"]) && !empty($_POST["password"]) && isset($_POST["userid"]) && !empty($_POST["userid"]) && isset($_POST["username"]) && !empty($_POST["username"])) {
    $userid = (int)($_POST["userid"]);
    $password = addslashes($_POST["password"]);
    $username = addslashes($_POST["username"]);
    $email = addslashes($_POST["email"]);
    //echo $password.' '.$userid.' '.$token;

    require_once('../includes/UserManager.php');
    $userrow = UserManager::db_fetch_userid($userid);
    $user = new UserManager($userrow);
    echo $user->update_account_settings($username, $password, $email);
}else{
    echo "This user does not exist. Please try again.";
    exit();
}
//
//require_once('../includes/UserManager.php');
//$userrow = UserManager::db_fetch_user($username);
//$user = new UserManager($userrow);
//if($user->check_forget()) {
//    echo "hi";
//}
?>