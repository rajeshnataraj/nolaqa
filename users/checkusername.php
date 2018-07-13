<?php
if($_REQUEST['username'] && $_REQUEST['username'])
{
    $username=$_REQUEST['username'];
    require_once('../includes/UserManager.php');
    $userrow = UserManager::db_fetch_user($username);
    if(count($userrow) > 20){
        echo "exists";
    }
//    if($userrow == "User does not exist. Please try again."){
//        echo $userrow;
//    }else{
//        echo "success";
//    }
}else {
    echo "Invalid username input. Please contact a system administrator.";
}
?>