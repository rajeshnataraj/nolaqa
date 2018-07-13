.<?php
	session_start();
	@include("table.class.php");
	
	$sessionid = $_SESSION['sessionid'];
	$logoutdate = date("Y-m-d H:i:s");
	$logid = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : 0;
	
	if($logid == 0 or ($logid == 1 and $_SESSION['userid1'] == '')) {
		
		setcookie("is_user_login", 0, time()+86400);	
		setcookie("username", '', time()-3600*24);	
		setcookie("userid", 0, time()-3600*24);	
		setcookie("user_profile", 0, time()-3600*24);	
		setcookie("sessionid", '', time()-3600*24);	
		setcookie("cookieflag", 0, time()+86400);
		
		session_destroy();
		
		$ObjDB->closedb();
		header("Location: login.php");
	}
	else if($logid == 1 and $_SESSION['userid1'] != '' ) {
				
		$_SESSION['userid'] = $_SESSION['userid1'];
		$_SESSION['username'] = $_SESSION['username1'];
		$_SESSION['usr_full_name'] = $_SESSION['usr_full_name1'];
		
		$_SESSION['userid1'] = '';
		$_SESSION['username1'] = '';
		$_SESSION['usr_full_name1'] = '';
		
		$ObjDB->closedb();
		header("Location: index.php");
	}
	else {
		
		$_SESSION['userid1'] = '';
		$_SESSION['username1'] = '';
		$_SESSION['usr_full_name1'] = '';
		
		$ObjDB->closedb();
		header("Location: index.php");
	}
	
	
?>