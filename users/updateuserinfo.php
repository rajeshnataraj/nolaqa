<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_STRICT | E_COMPILE_ERROR | E_ERROR | E_WARNING);
if (isset($_POST["password"]) && !empty($_POST["password"]) && isset($_POST["userid"]) && !empty($_POST["userid"]) && isset($_POST["email"]) && !empty($_POST["email"])) {
    $userid = (int)($_POST["userid"]);
    $password = addslashes($_POST["password"]);
    $email = addslashes($_POST["email"]);

    require_once('../includes/UserManager.php');
    $userrow = UserManager::db_fetch_userid($userid);
    $user = new UserManager($userrow);
    echo $user->update_account_info($password, $email);
}else{
    echo "success";
    exit();
}
?>