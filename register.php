<?php
	@include('config.php');
    @include("table.class.php");
	
	$enc_userid = (isset($_REQUEST['e'])) ? $_REQUEST['e'] : '';
	$flag = 1;
	$photo = "img/no-image.png";
	$headlogo = '';
	$headtitle = '';
	$fname='';
	$lname='';
	if($enc_userid != '' and $enc_userid != 'd9a22d7a8178d5b42a8750123cbfe5b1') 
	{

		$checkstatus = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
													FROM itc_user_master 
													WHERE MD5(fld_id)='".$enc_userid."' AND fld_activestatus='0' AND fld_delstatus='0' "); 
		if($checkstatus > 0)
		 {

			$flag = 1;

			$getdetails = $ObjDB->QueryObject("SELECT a.fld_id AS id, a.fld_fname AS fname, a.fld_lname AS lname, a.fld_email AS email, 
												a.fld_profile_id AS profileid, a.fld_role_id AS roleid,
												a.fld_district_id AS districtid, a.fld_school_id AS schoolid,
												a.fld_profile_pic AS profilepic,b.fld_prf_main_id AS profileid 
											FROM itc_user_master AS a 
											LEFT JOIN itc_profile_master AS b ON b.fld_id=a.fld_profile_id WHERE MD5(a.fld_id)='".$enc_userid."'"); 

			$rowgetdet = $getdetails->fetch_assoc();
			extract($rowgetdet);
		}
		else
		{
			$flag = 0;
		}
	}
	else if($enc_userid == 'd9a22d7a8178d5b42a8750123cbfe5b1') 
	{
		$flag = 2;
	}
	else 
	{
		header("Location: login.php");
		exit();
	}
?>
    <!DOCTYPE html  "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" PUBLIC>
    <html>
    <head>
    <meta charset="utf-8">
    <title>Synergy ITC | PITSCO Education</title>
    <link href="css/register.window.css" rel="stylesheet" type="text/css"  />
    <link href='jquery-ui/css/itc/jquery-ui-1.9.2.custom.min.css' type="text/css" rel='stylesheet'>
    <link href='css/zebra_dialog.css' type="text/css" rel='stylesheet' />
    <link rel="stylesheet" href="css/strength.css">
    
    <script language="javascript" type="text/javascript" src="jquery-ui/js/jquery-1.8.3.min.js"></script>
    <script language="javascript" type="text/javascript" src='jquery-ui/js/jquery-ui-1.9.2.custom.min.js'></script>
    <script language="javascript" type="text/javascript" src="js/jquery.validate.js"></script>
    <script language="javascript" type="text/javascript" src="js/jquery.validate.additional.js"></script>
    <script language="javascript" type="text/javascript" src="js/jquery.placeholder.js"></script> 
    <script language="javascript" type="text/javascript" src="js/register.js"></script>
    <script language="javascript" type="text/javascript" src="js/zebra_dialog.js"></script>
        <script language="javascript" type="text/javascript" src="js/strength.js"></script>

	</head>
	<body>
	<?php
    if($flag==0)
    {
    ?>
    	<div class="logonBaseBox">
        	<div class="logonLogo"><img src="img/synergy.logo.svg" alt="PITSCO" /></div>
            <div class="smallDialogHeadline">Account Activated</div>
            <div class="loginCopyright">Your account has been already activated.</div>
        </div>
    <?php
    }
	else if($flag==1)
	{
	?>
        <form id="frmregister" name="frmregister">
        	<div class="logonBaseBox" style="text-align: left;">
                <div class="logonLogo"><img src="img/synergy.logo.svg" alt="PITSCO" /></div>
                
                <div class="smallDialogHeadline">Activate Your Synergy ITC Account</div>
                <div class="smallHeadline">Please fill in the fields below to activate your account.<br>
                    <i style="display: block; margin-top:12px; color:#C43; opacity: 0.9; font-size: 11px; letter-spacing:-0.1px;"><?= $password_fails_specification_error_message ?></i></div>

                <div class="sent_confirmation" style="text-align: center; color:#689; font-size:12.5px; width:300px; font-weight: bold; margin:0 auto; padding:0px; display: none">Your account has been successfully activated.</div>
                <div class="logonInputDiv">
                	First Name:
                    <input type="text" id="txtfirstname" name="txtfirstname" placeholder="Enter your first name" tabindex="1"  required="" class="logonInput" autocomplete="off" value="<?php echo $fname; ?>" />
                </div>
                <div class="logonInputDiv InputMar20">
                	Last Name:
                    <input type="text" id="txtlastname" name="txtlastname" placeholder="Enter your last name" tabindex="2"  required="" class="logonInput" autocomplete="off" value="<?php echo $lname; ?>" />
                </div>
                <div class="logonInputDiv InputMar20">
                    E-Mail Address:
                    <input type="email" id="txtemail" name="txtemail" placeholder="Enter a valid e-mail address"  required="" tabindex="3" class="logonInput" autocomplete="on" value="<?php echo $email; ?>"/>
                </div>
                <div class="logonInputDiv InputMar20">
                    User Name:
                    <input type="text" id="txtusername" name="txtusername" onblur="checkusername(this)" placeholder="Choose your Synergy ITC Username"  required="" tabindex="3" class="logonInput" autocomplete="off"/>
                </div>

                <div class="logonInputDiv InputMar20">
                	Password:
                    <input type="password" id="txtpassword" name="txtpassword" placeholder="Create password" tabindex="4"  required="" class="logonInput" maxlength="14"  />
                </div>
                <div class="logonInputDiv InputMar20" style="display: inline-block; width: calc(100% - 40px);">
	                Re-enter Password:
                    <input type="password" id="txtrpassword2" name="txtrpassword2" placeholder="Re-enter password" required="" tabindex="5" class="logonInput txtpassword2" maxlength="14"  />
                </div>
                
                <div class="logonSubmitBtn">
                    <input tabindex="6" type="submit" class="logonSubmit" id="btnsignin" name="btnsignin" value="Activate my Account" />
                    <input type="hidden" id="hidusrid" name="hidusrid" value="<?php echo $enc_userid; ?>"  />
                </div>
                
            </div>
        </form>
        <script>

            $(function () {
                $('#txtpassword').strength({
                    strengthClass: 'strength',
                    strengthMeterClass: 'strength_meter',
                    strengthButtonClass: 'button_strength',
                    strengthButtonText: 'Show Password',
                    strengthButtonTextToggle: 'Hide Password'
                });
                $(".strength_meter").hide();


                $('#txtpassword').keypress(function() {
                    $(".strength_meter").slideDown();
                });
                $('#txtpassword').blur(function() {
                    if($("#txtpassword").hasClass("passwordstrong")){
                        $(".strength_meter").slideUp();
                    }
                });

            });

            function checkusername(txtusername){
                var username = $(txtusername).val();
                if(username != "<?php echo $user->username; ?>") {
                    $.ajax({
                        type: 'POST',
                        url: 'users/checkusername.php',
                        data: "username=" + username,
                        success: function (data) {
                            if (data == "exists") {
                                $(".sent_confirmation").slideDown().html("<div style='font-weight: bold; color:#ef6638; font-size:14px; margin-top:8px;'>This username already exists. <br>Please enter a new unique username.</div>");
                            }else{
                                $(".sent_confirmation").hide().html("");
                            }
                        }
                    });
                }
            }


                var passresetform = $('#frmregister');
                passresetform.submit(function (ev) {
                    ev.preventDefault();

                    var password1 = $("#txtpassword").val();
                    var password2 = $("#txtrpassword2").val();
                    var username = $("#txtusername").val();
                    var email = $("#txtemail").val();
                    var fname = $("#txtfirstname").val();
                    var lname = $("#txtlastname").val();
                    $(".smallHeadline").hide();
                    if(password1 != password2){
                        $(".sent_confirmation").slideDown().html('<div style="font-weight: bold; color:#D65; font-size:14px; margin-top:8px;">Error - Passwords do not match. Please try again.</div>');
                    }
                    else if(password1.length < 8 || password2.length < 8){
                        $(".sent_confirmation").slideDown().html('<div style="font-weight: bold; color:#D65; font-size:14px; margin-top:8px;">Error - Password must be a minimum of 8 characters in length. Please try again.</div>');
                    }
                    else if(!(password1.match(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d!@#$%&*]{8,}$/))){
                        $(".sent_confirmation").slideDown().html("<div style='font-weight: bold; color:#D65; font-size:14px; margin-top:8px;'><?= $password_fails_specification_error_message ?></div>");
                    }
                    else{
                        $.ajax({
                            type: 'POST',
                            url: 'users/checkusername.php',
                            data: "username=" + username,
                            success: function (data) {
                                if (data == "exists") {
                                    $(".sent_confirmation").slideDown().html("<div style='font-weight: bold; color:#ef6638; font-size:14px; margin-top:8px;'>This username already exists. <br>Please enter a new unique username.</div>");
                                }else{
                                    $(".sent_confirmation").hide().html("");

                                    $(".strength_meter").hide();
                                    $(".logonSubmitBtn, .logonInputDiv").hide();
                                    var output = "userid=<?php echo $id; ?>&username="+username+"&fname="+fname+"&lname="+lname+"&email="+email+"&password="+password1;
                                    $.ajax({
                                        type: 'POST',
                                        url: 'users/activateuser.php',
                                        data: output,
                                        beforeSend: function(){
                                            $(".sent_confirmation").slideDown().html("<div style='color:#0A5; font-size:14px; margin-top:16px;'>Activating Your Account, please wait.<br><br><br><br></div>").slideDown();
                                        },
                                        success: function (data) {
                                            if(data == "success"){
                                                $('.sent_confirmation').slideDown().html("<div style='color:#0A5; font-size:14px; margin-top:16px;'>Your Account has been activated. <br>Please log in with your username and password.<br><br><br><br></div>").slideDown();
                                                setTimeout(function(){
                                                    window.location.href = "login.php";
                                                }, 2500);
                                            }else{
                                                $(".sent_confirmation").slideDown().html("<div style='font-weight: bold; color:#ef6638; font-size:14px; margin-top:8px;'>" + data + "</div>");
                                            }
                                        }
                                    });
                                }
                            }
                        });
                    }
                });
        </script>
	<?php
	}
	else
	{
    }
    ?>
</body>
</html>
<?php
	@include("footer.php");