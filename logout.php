<?php
	session_start();
	require_once('includes/config.php');
	require_once('includes/table.class.php');
	
	$sessionid = $_SESSION['sessionid'];
	$logoutdate = date("Y-m-d H:i:s");
	$logid1 = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : 0;
	
        $logid1 = explode(",", $logid1);
        $logid =$logid1[0];
        
        $tmodid = $logid1[1];
        $tschid = $logid1[2];
        $tschtype = $logid1[3];
        $tseid = $logid1[4];
        $ttypes = $logid1[5];
        
        $_SESSION['moduleid']=$tmodid;
        $_SESSION['scheduleid']=$tschid;
        $_SESSION['scheduletype']=$tschtype;
        $_SESSION['sessionid']=$tseid;
        $_SESSION['type']=$ttypes;
        
	
	if($logid == 0 or ($logid == 1 and $_SESSION['userid1'] == '')) {
		
		setcookie("is_user_login", 0, time()+86400);	
		setcookie("username", '', time()-3600*24);	
		setcookie("userid", 0, time()-3600*24);	
		setcookie("user_profile", 0, time()-3600*24);	
		setcookie("sessionid", '', time()-3600*24);	
		setcookie("cookieflag", 0, time()+86400);
		
                if($_SESSION['page']=='sos')
                {
		session_destroy();
		
		$ObjDB->closedb();
                    header("Location: " . ITC_URL . "/sos.php");
                }
                else
                {
                    session_destroy();
		
                    $ObjDB->closedb();
                   ?>
                 <script>
                    var hostname=window.location.hostname;
                    window.location.replace("login.php");
                 </script>
            <?php
	}
	}
	else if($logid == 1 and $_SESSION['userid1'] != '' ) {
		
		$_SESSION['userid'] = $_SESSION['userid1'];
		$_SESSION['username'] = $_SESSION['username1'];
		$_SESSION['usr_full_name'] = $_SESSION['usr_full_name1'];
		
		$_SESSION['userid1'] = '';
		$_SESSION['username1'] = '';
		$_SESSION['usr_full_name1'] = '';
		
		$ObjDB->closedb();
		header("Location: " . ITC_URL . "/index.php");
	}
	else {
		
		$_SESSION['userid1'] = '';
		$_SESSION['username1'] = '';
		$_SESSION['usr_full_name1'] = '';
		
		$ObjDB->closedb();
		header("Location: " . ITC_URL . "/index.php");
	}
	
	
?>
