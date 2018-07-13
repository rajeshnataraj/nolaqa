<?php
	//header('Location: maintenance.html');
	session_start();	
	$oper = (isset($_REQUEST['hidoper'])) ? $_REQUEST['hidoper'] : 0;
	$msg = "";
	
	if($oper == "login" and $oper != '') {
		
		include("table.class.php");	
		include("comm_func.php");
			
		$username = (isset($_POST['txtusername'])) ? $_POST['txtusername'] : '';
		$password = (isset($_POST['txtpassword'])) ? $_POST['txtpassword'] : '';
		$cookie = (isset($_POST['chkremember'])) ? $_POST['chkremember'] : 0;
		$sosvalue = (isset($_POST['sosvalue'])) ? $_POST['sosvalue'] : '0';
                 
		$encryptpass = fnEncrypt($password,$encryptkey);
			
		$check = $ObjDB->Count("SELECT fld_username FROM itc_user_master 
									WHERE MD5(fld_username)='".md5($username)."' 
										AND fld_password='".$encryptpass."' 
										AND fld_delstatus='0' 
										AND fld_activestatus='1'");
										
		if($check > 0) {
                    
                    $check1 = $ObjDB->Count("SELECT fld_username FROM itc_user_master 
									WHERE MD5(fld_username)='".md5($username)."' 
										AND fld_password='".$encryptpass."' 
										AND fld_delstatus='0' 
										AND fld_activestatus='1'
                                                                                AND fld_block_status='0'");
                    if($check1 > 0){
			
			$userdetqry = $ObjDB->QueryObject("SELECT a.fld_itcteacher as itcteacher,a.fld_sosteacher as sosteacher,a.fld_id AS userid, a.fld_profile_id AS sessprofileid, a.fld_role_id AS sessroleid, 
													 b.fld_prf_main_id AS sessmasterprfid, b.fld_profile_name AS sessprofilename, 
													 CONCAT(a.fld_fname,' ',a.fld_lname) AS sessfname, a.fld_school_id AS schoolid,
													 a.fld_district_id AS distid, a.fld_user_id AS indid 
													 FROM itc_user_master AS a, itc_profile_master AS b 
													 WHERE a.fld_profile_id=b.fld_id 
													 	AND MD5(a.fld_username)='".md5($username)."' 
														AND a.fld_password='".$encryptpass."' 
														AND (b.fld_delstatus = 0 OR b.fld_delstatus=2) 
														AND a.fld_activestatus='1'
                                                                                                                AND a.fld_block_status='0'
														AND a.fld_delstatus='0'");
			$rowuserdet = $userdetqry->fetch_assoc();
			extract($rowuserdet);
			
			$sessionid = md5(generatePassword(8,8));

			$logindate = date("Y-m-d H:i:s");
			
			$chktrial=$ObjDB->Count("SELECT fld_id FROM itc_user_master 
										WHERE fld_id='".$userid."' 
											AND fld_created_by IN (SELECT fld_user_id FROM itc_trial_users)");
			if($chktrial!=0){
				$_SESSION['trialuser'] = 1;
			}
			
			$_SESSION['is_user_login'] = true;
			$_SESSION['username'] = $username;
			$_SESSION['usr_full_name'] = $sessfname;
			$_SESSION['userid'] = $userid;
                        $_SESSION['itcteacher'] = $itcteacher;
                        $_SESSION['sosteacher'] = $sosteacher;
			$_SESSION['user_profile'] = $sessprofileid;
			$_SESSION['role_id'] = $sessroleid;
			$_SESSION['prf_main_id'] = $sessmasterprfid;
			$_SESSION['prf_name'] = $sessprofilename;
			$_SESSION['sessionid'] = $sessionid;	
			$_SESSION['schoolid'] = $schoolid;	
			$_SESSION['indid'] = $indid;
			$_SESSION['distid'] = $distid;	
                        if($sosvalue=='1')
                        {
                            $_SESSION['page']='sos';
                        }
			$_SESSION['username1'] = '';
			$_SESSION['usr_full_name1'] = '';
			$_SESSION['userid1'] = '';
				
			if($cookie == 1){
				setcookie("cookieflag", 1, time()+3600*24);
				setcookie("is_user_login", 1, time()+3600*24);
				setcookie("username", $username, time()+3600*24);	
				setcookie("usr_full_name", $sessfname, time()+3600*24);
				setcookie("userid", $userid, time()+3600*24);	
				setcookie("user_profile", $sessprofileid, time()+3600*24);	
				setcookie("role_id", $sessroleid, time()+3600*24);	
				setcookie("prf_main_id", $sessmasterprfid, time()+3600*24);	
				setcookie("prf_name", $sessprofilename, time()+3600*24);	
				setcookie("sessionid", $sessionid, time()+3600*24);
				setcookie("schoolid", $schoolid, time()+3600*24);
				setcookie("distid", $distid, time()+3600*24);
				setcookie("trialuser", '0', time()+3600*24);
				setcookie("indid", '0', time()+3600*24);
                                if($sosvalue=='1')
                                {
                                    setcookie("page", 'sos', time()+3600*24);
                                }
				setcookie("username1", '', time()+3600*24);	
				setcookie("usr_full_name1", '', time()+3600*24);
				setcookie("userid1", '', time()+3600*24);
			}
			$ObjDB->NonQuery("update itc_login_track_master set fld_logout_datetime=fld_current_datetime WHERE fld_logout_datetime='0000-00-00 00:00:00' AND fld_user_id='".$userid."'");

			$ObjDB->NonQuery("INSERT INTO itc_login_track_master (fld_user_id, fld_login_datetime, fld_current_datetime, fld_ipaddress, fld_sessionid, fld_browsername) VALUES ('".$userid."','".$logindate."','".$logindate."','".$_SERVER['REMOTE_ADDR']."','".$sessionid."','".$_SERVER['HTTP_USER_AGENT']."')");
			
			if($chktrial!=0){
				header("Location:trialuserlesson.php");
			}
			else{			
				header("Location:index.php");
			}
                    }
                    else{
                        $msg1 = "Your account is currently disabled and needs to be renewed. Please contact Pitsco sales support at 800-828-5787 for further assistance with the renewal process.";
                    }
		}
		else {
			$msg = "Username and/or password is incorrect.";
		}	
	}
	else{
		$cookieflag = (isset($_COOKIE['cookieflag'])) ? $_COOKIE['cookieflag'] : 0;
			
		if($cookieflag == 1) {
			$_SESSION['is_user_login'] = true;
			$_SESSION['username'] = $_COOKIE['username'];
			$_SESSION['usr_full_name'] = $_COOKIE['usr_full_name'];
			$_SESSION['userid'] = $_COOKIE['userid'];
                        $_SESSION['itcteacher'] = $_COOKIE['itcteacher'];
                        $_SESSION['sosteacher'] = $_COOKIE['sosteacher'];
			$_SESSION['user_profile'] = $_COOKIE['user_profile'];
			$_SESSION['role_id'] = $_COOKIE['role_id'];
			$_SESSION['prf_main_id'] = $_COOKIE['prf_main_id'];
			$_SESSION['prf_name'] = $_COOKIE['prf_name'];
			$_SESSION['sessionid'] = $_COOKIE['sessionid'];
			$_SESSION['schoolid'] = $_COOKIE['schoolid'];
			$_SESSION['indid'] = $_COOKIE['indid'];
			$_SESSION['distid'] = $_COOKIE['distid'];
			$_SESSION['trialuser'] = $_COOKIE['trialuser'];
                        if($sosvalue=='1')
                        {
                            $_SESSION['page'] = $_COOKIE['page'];
                        }
			header("Location:index.php");
		}
		else {
			
			if(isset($_SESSION['is_user_login']) and $_SESSION['is_user_login'] == 1)
			{
				$_SESSION['is_user_login'] = true;				
				header("Location:index.php");
			}
		}	
	}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Synergy ITC | Login</title>

<link rel="stylesheet" type="text/css" href="css/login.window.css" media="screen" />
<link rel="stylesheet" type="text/css" href="jquery-ui/css/itc/jquery-ui-1.9.2.custom.min.css" media="screen" />

<script language="javascript" type="text/javascript" src="jquery-ui/js/jquery-1.8.3.min.js"></script>
<script language="javascript" type="text/javascript" src="jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.validate.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.validate.additional.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.placeholder.js"></script>
<script language="javascript" type="text/javascript" src="js/login.js"></script>

</head>
    
<body>
	<form id="frmlogin" name="frmlogin">
    	
        <div class="logonBaseBox">
            <div class="sosLogo"></div>
            <?php if($msg!=""){ ?> <div class="errormsg"><?php echo $msg; ?></div> <?php } ?>
            <?php if($msg1!=""){ ?> <div class="errormsg" style="height: auto;"><?php echo $msg1; ?></div> <?php } ?>
            
            <div class="logonInputDivTop">
            	<input type="text" id="txtusername" name="txtusername" placeholder="User name" tabindex="1" class="sosInput" onKeyUp="if(event.keyCode==13) { document.getElementById('btnsignin').click(); return false;}" autocomplete="off" />
            </div>
            <div class="logonInputDiv">
            	<input type="password" id="txtpassword" name="txtpassword" placeholder="Password" tabindex="2" class="sosInput" onKeyUp="if(event.keyCode==13) { document.getElementById('btnsignin').click(); return false;}" />
                <input type="hidden" id="sosvalue" name="sosvalue" value='1'/>
            </div>
            <div class="logonSubmitBtn">
            	<input tabindex="3" type="button" class="sosSubmit" id="btnsignin" name="btnsignin" onClick="fn_soslogin();" value="Sign in" />	
            </div>
            <div class="logonForgotBtn">
            	<input tabindex="4" type="button" class="sosForgot" id="btnforgotpswd" name="btnforgotpswd" onClick="fn_fpswd_page();" value="Forgot password" />	
            </div>
            <div class="trialLink">
                <a href="trial/trialform.php" tabindex="5" style="color:#6D6D6F">Need login help?</a>
                <li class="lisy-style-type" style="color:#6D6D6F">800-774-4552</li>
                <li class="lisy-style-type"><a href="mailto:support@pitsco.com" target="_blank" style="color:#1156A4">support@pitsco.com</a></li>
                <a href="http://www.science-of-speed.com/Curriculum_and_Activities" style="color:#1156A4">Learn More</a>
            </div>
            <div class="loginCopyright1">
                <li class="lisy-style-type logotop"><img src="img/logo_t.jpg" width="100px" height="30px" alt="PITSCO" /></li>
                <li class="lisy-style-type" style="color:#6D6D6F; padding-top:8px;">&copy; Pitsco, Inc. &nbsp; v1.0.0</li>
            </div>
        </div>
        
        <input type="hidden" name="hidoper" id="hidoper"  />
    </form>

	<script language="javascript" type="text/javascript">
        $(function(){
            
            $("#dialog-message" ).dialog({
            <?php if($msg != ""){  ?>    
                autoOpen: true,
            <?php }else { ?>
                autoOpen: false,	
            <?php }?>
                modal: true,
                buttons: {
                    Ok: function() {
                        $( this ).dialog( "close" );
                    }
                }
            });
            
        });
    </script>    
</body>
</html>
<?php
	if($oper == "login" and $oper != '') {
		$ObjDB->closedb();
	}

?>  
<style>
body{
     background:#949599; 
    }
    .text {
        color:#6D6D6F;
    }

    ::-webkit-input-placeholder { color:#b13d3d; }
    ::-moz-placeholder { color:#b13d3d; } /* firefox 19+ */
    :-ms-input-placeholder { color:#b13d3d; } /* ie */
    input:-moz-placeholder { color:#b13d3d; }

</style>