<?php
if($_GET['userid'] && $_GET['token'])
{
    $userid=$_GET['userid'];
    $token=$_GET['token'];
//    echo "userid: ".$userid.'<br>';
//    echo "token: ".$token.'<br>';
    require_once('../includes/UserManager.php');
    if(!UserManager::check_password_reset($userid, $token)) {
        header("Location: ../login.php?msg=Invalid Password Reset Token");
        exit();
    }
}else {
    header("Location: ../login.php?msg=Invalid Password Reset Token");
    exit();
}
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Forgot Password</title>

    <link rel="stylesheet" type="text/css" href="../css/login.window.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="../jquery-ui/css/itc/jquery-ui-1.9.2.custom.min.css" media="screen" />

    <link rel="stylesheet" href="../css/strength.css">
    <script language="javascript" type="text/javascript" src="../jquery-ui/js/jquery-1.8.3.min.js"></script>
    <script language="javascript" type="text/javascript" src="../jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
    <script language="javascript" type="text/javascript" src="../js/jquery.validate.js"></script>
    <script language="javascript" type="text/javascript" src="../js/jquery.validate.additional.js"></script>
    <script language="javascript" type="text/javascript" src="../js/jquery.placeholder.js"></script>
    <script language="javascript" type="text/javascript" src="../js/strength.js"></script>

</head>

<body>

<form id="resetform" name="resetform">
    <div class="logonBaseBox frgtPwdBox">
        <div class="logonLogo"><img src="../img/pitsco.logo.login.png" alt="PITSCO" /></div>
        <div class="smallDialogHeadline" style="text-align: center; color:#468; font-size:20px; margin-top:32px; padding:0px; text-transform: uppercase; font-weight:bold; ">Reset Your Password</div>
        <div class="smallHeadline" style="text-align: center; color:#579; font-size:14px; width:100%; max-width:316px; margin:0 auto; margin-top: 12px; font-weight:bold; padding:0 2px;">Enter your new Synergy ITC password below.<br>
            <i style="display: block; margin-top:12px; color:#C43; opacity: 0.9; font-size: 11px; letter-spacing:-0.1px;"><?= $password_fails_specification_error_message ?></i>
        </div>
        <div class="waitmsg" style="text-align: center !important; height: 24px; margin-top:24px; padding: 2px 0; width: 100%; font-size: 12px; font-style: italic; font-weight: bold; display:none;">UPDATING PASSWORD... PLEASE WAIT.</div>

        <div class="sent_confirmation" style="text-align: center; color:#689; font-size:12.5px; width:300px; font-weight: bold; margin:0 auto; padding:0px; display: none">Your password reset link should arrive shortly.<br>if not, check your spam folder.</div>

        <div class="logonInputDivTop" style="width:315px; margin-left:25px;">
            <input type="password" id="txtpassword" name="password" placeholder="New Password" tabindex="1" class="logonInput" autocomplete="off"  style="">
        </div>
        <div class="logonInputDiv" style="padding-top:10px; width:315px;  margin-left:25px;">
            <input type="password" id="txtpassword2" name="password2" placeholder="Confirm Password" tabindex="2" class="logonInput" autocomplete="off" />
        </div>
        <div class="logonSubmitBtn" style="width:315px; margin-left:25px;">
            <input tabindex="3" type="submit" class="logonSubmit" id="btnsignin" name="btnsignin" value="Save New Password" />
        </div>
        <div class="trialLink" style="text-align: center; color:#49708A; font-size:14px; line-height:1.6em; width:280px; margin:16px auto; padding:24px;"><span class="remembertext">Remember Your Password? <br></span><a href="../login.php"><b style="font-size:16px;">Click Here To Login</b></a></div>
        <div class="loginCopyright">
            &copy; Pitsco, Inc. &nbsp; v2.0.0
        </div>
    </div>

    <input type="hidden" name="hidoper" id="hidoper"  />
</form>


<script>
    $(document).ready(function($) {
        $('#txtpassword').strength({
            strengthClass: 'strength',
            strengthMeterClass: 'strength_meter',
            strengthButtonClass: 'button_strength',
            strengthButtonText: 'Show Password',
            strengthButtonTextToggle: 'Hide Password'
        });
        $(".strength_meter").hide();
    });
    $(function () {
        $('#txtpassword').keypress(function() {
            $(".strength_meter").slideDown();
        });
        $('#txtpassword').blur(function() {
            if($("#txtpassword").hasClass("passwordstrong")){
                $(".strength_meter").slideUp();
            }
        });

        var passresetform = $('#resetform');
        passresetform.submit(function (ev) {
            ev.preventDefault();
            var password = $("#txtpassword").val();
            var password2 = $("#txtpassword2").val();
            $(".smallHeadline").hide();
            if(password != password2){
                $(".sent_confirmation").slideDown().html("<div style='font-weight: bold; color:#D65; font-size:14px; margin-top:8px;'>Error - Passwords do not match. Please try again.</div>");
            }
            else if(password.length < 8 || password2.length < 8){
                $(".sent_confirmation").slideDown().html("<div style='font-weight: bold; color:#D65; font-size:14px; margin-top:8px;'>Error - Password must be a minimum of 8 characters in length. Please try again.</div>");
            }
            else if(!(password.match(<?= $password_regex?>))){
                $(".sent_confirmation").slideDown().html("<div style='font-weight: bold; color:#D65; font-size:14px; margin-top:8px;'><?= $password_fails_specification_error_message ?></div>");
            }
            else{
                $(".sent_confirmation, .strength_meter").hide();
                $(".logonInputDivTop, .logonSubmitBtn, .logonInputDiv").hide();
                $(".waitmsg").slideDown();
                var output = "userid=<?php echo $userid; ?>&token=<?php echo $token; ?>&password=" + password;
                $.ajax({
                    type: 'POST',
                    url: 'save-password.php',
                    data: output,
                    success: function (data) {
                        $(".waitmsg").hide();
                        if(data != "success"){
                            $(".sent_confirmation").slideDown().html("<div style='font-weight: bold; color:#820; font-size:14px; margin-top:8px;'>" + data + "</div>");
                            $(".logonInputDivTop, .logonSubmitBtn, .logonInputDiv, .strength_meter").show();
                        }else{
                            $(".remembertext").hide();
                            $(".sent_confirmation").html("<div style='color:#0A5; font-size:14px; margin-top:16px;'>Your password has been updated successfully.<br></div>").slideDown();
                        }
                    }
                });
            }

//
        });
    });
</script>
</body>
</html>