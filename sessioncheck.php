<?php
	session_start();
	@include("table.class.php");
	@include("comm_func.php");
@include("ContentManager.php");


	if(isset($_SESSION['is_user_login']) and $_SESSION['is_user_login'] === true) {

		$uid = $_SESSION['userid'];
		$uid1 = $_SESSION['userid1'];

		$username = $_SESSION['username'];
		$username1 = $_SESSION['username1'];
                $itcteacher=$_SESSION['itcteacher'];
                $sosteacher=$_SESSION['sosteacher'];

		$sessusrfullname = $_SESSION['usr_full_name'];
		$sessusrfullname1 = $_SESSION['usr_full_name1'];

		$trialuser = $_SESSION['trialuser'];
		$schoolid = $_SESSION['schoolid'];
		$districtid = $_SESSION['distid'];
		$indid = $_SESSION['indid'];
		$senshlid = $_SESSION['schoolid'];
		$sendistid = $_SESSION['distid'];
		$sessroleid = $_SESSION['role_id'];
		$sessmasterprfid = $_SESSION['prf_main_id'];
		$sessprofileid = $_SESSION['user_profile'];
		$sessprofilename = $_SESSION['prf_name'];
		$sessionid = $_SESSION['sessionid'];
		$method=$_POST;

	}
	else {
            ?>
                 <script>
                    var hostname=window.location.hostname;
                    window.location.replace("http://"+hostname+"/login.php");
                 </script>
            <?php
	}
?>
