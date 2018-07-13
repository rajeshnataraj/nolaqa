<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_STRICT | E_COMPILE_ERROR | E_ERROR | E_WARNING);
if (isset($_POST["username"]) && !empty($_POST["username"])) {
    $username = addslashes($_POST["username"]);
}else{
    echo "Please enter a valid username.";
    exit();
}
if (isset($_POST["password"]) && !empty($_POST["password"])) {
    $password = $_POST["password"];
}else{
    echo "Please enter your password.";
    exit();
}

require_once('../includes/UserManager.php');
$userrow = UserManager::db_fetch_user($username);
$user = new UserManager($userrow);
$login = $user->verify_password($password);
if($login == "success"){
    $userrow = $user->userrow;
    $_SESSION['is_user_login'] = true;
    $_SESSION['username'] = $user->username;
    $_SESSION['usr_full_name'] = $user->name;
    $_SESSION['userid'] = $user->id;
    $_SESSION['itcteacher'] = $user->itcteacher;
    $_SESSION['sosteacher'] = $user->sosteacher;
    $_SESSION['user_profile'] = $user->user_profile;
    $_SESSION['role_id'] = $user->role_id;
    $_SESSION['prf_main_id'] = $user->prf_main_id;

    $_SESSION['prf_name'] = $user->prf_name;
    $_SESSION['sessionid'] = $user->sessionid;
    $_SESSION['schoolid'] = $user->schoolid;
    $_SESSION['indid'] = $user->indid;
    $_SESSION['distid'] = $user->distid;

    $_SESSION['username1'] = '';
    $_SESSION['usr_full_name1'] = '';
    $_SESSION['userid1'] = '';
    if($user->isTrial()){
        $_SESSION['trialuser'] = 1;
        echo "trial";
        exit();
    }
}
echo $login;

?>