<?php

session_start();
$oper = (isset($_REQUEST['hidoper'])) ? $_REQUEST['hidoper'] : 0;
$msg1 = (isset($_REQUEST['msg'])) ? $_REQUEST['msg'] : "";
?>
    <!doctype html>
    <html>
<head>
    <meta charset="utf-8">
    <title>Forgot Password</title>

    <link rel="stylesheet" type="text/css" href="css/login.window.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="jquery-ui/css/itc/jquery-ui-1.9.2.custom.min.css" media="screen" />

    <script language="javascript" type="text/javascript" src="jquery-ui/js/jquery-1.8.3.min.js"></script>
    <script language="javascript" type="text/javascript" src="jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
    <script language="javascript" type="text/javascript" src="js/jquery.validate.js"></script>
    <script language="javascript" type="text/javascript" src="js/jquery.validate.additional.js"></script>
    <script language="javascript" type="text/javascript" src="js/jquery.placeholder.js"></script>

</head>

<body>

<form id="passwordform" name="passwordform">
    <div class="logonBaseBox frgtPwdBox">
        <div class="logonLogo"><img src="img/pitsco.logo.login.png" alt="PITSCO" /></div>
        <div class="smallDialogHeadline" style="text-align: center; color:#468; font-size:20px; margin-top:32px; padding:0px; text-transform: uppercase; font-weight:bold; ">Forget Your Password?</div>
        <div class="smallHeadline" style="text-align: center; color:#579; font-size:13px; width:280px; margin:0 auto; font-weight:bold; padding:8px;">Enter your username below and we will email you a link to reset your password.</div>
        <div class="waitmsg" style="text-align: center !important; height: 24px; margin-top:24px; padding: 2px 0; width: 100%; font-size: 12px; font-style: italic; font-weight: bold; display:none;">GENERATING PASSWORD RESET LINK...</div>

        <div class="sent_confirmation" style="text-align: center; color:#689; font-size:12.5px; width:300px; font-weight: bold; margin:0 auto; padding:0px; display: none">Your password reset link should arrive shortly.<br>if not, check your spam folder.</div>

        <div class="logonInputDivTop">
            <input type="text" id="txtusername" name="username" placeholder="Username" tabindex="1" class="logonInput" autocomplete="on" />
        </div>
        <div class="logonSubmitBtn">
            <input tabindex="3" type="submit" class="logonSubmit" id="btnsignin" name="btnsignin" value="Email Password Reset Link" />
        </div>
        <div class="trialLink" style="text-align: center; color:#49708A; font-size:14px; line-height:1.6em; width:280px; margin:16px auto; padding:24px;">Remember Your Password? <br><a href="login.php"><b style="font-size:16px;">Click Here To Login</b></a></div>
        <div class="loginCopyright">
            &copy; Pitsco, Inc. &nbsp; v2.0.0
        </div>
    </div>

    <input type="hidden" name="hidoper" id="hidoper"  />
</form>


<script>
    $(function () {
        var passresetform = $('#passwordform');
        passresetform.submit(function (ev) {
            $(".smallHeadline").hide();
            $(".sent_confirmation").hide();
            $(".waitmsg").slideDown();
            var output = passresetform.serialize();
            console.log(output);
            $.ajax({
                type: 'POST',
                url: 'users/postpassword.php',
                data: output,
                success: function (data) {
                    $(".waitmsg").hide();
                    if(data != "success"){
                        $(".sent_confirmation").slideDown().html("<div style='font-weight: bold; color:#820; font-size:14px; margin-top:8px;'>" + data + "</div>");
                        $(".logonInputDivTop, .logonSubmitBtn").show();
                    }else{
                        $(".logonInputDivTop, .logonSubmitBtn").hide();
                        $(".sent_confirmation").html("<div style='color:#0A5; font-size:15px; margin-top:16px;'>Your password reset link should arrive shortly.</div><br><i>If you do not receive the reset password e-mail message, please check your mailbox spam folder.</i>").slideDown();
                    }
                }
            });
            ev.preventDefault();
        });
    });
</script>
</body>
</html>