<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_STRICT | E_COMPILE_ERROR | E_ERROR | E_WARNING);
if (isset($_POST["password"]) && !empty($_POST["password"]) && isset($_POST["userid"]) && !empty($_POST["userid"]) && isset($_POST["token"]) && !empty($_POST["token"])) {
    $password = addslashes($_POST["password"]);
    $userid = (int)($_POST["userid"]);
    $token = addslashes((string)$_POST["token"]);
    //echo $password.' '.$userid.' '.$token;

    require_once('../includes/UserManager.php');
    if(!UserManager::check_password_reset($userid, $token)) {
        echo "The password for this user has already been updated. Please log in.";
        exit();
    }else{
        $userrow = UserManager::db_fetch_userid($userid);
        $user = new UserManager($userrow);
        $login = $user->update_password($password);
        echo $login;
    }

}else{
    echo "Please enter a valid password.";
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