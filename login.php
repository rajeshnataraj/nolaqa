<?php

session_start();
$oper = (isset($_REQUEST['hidoper'])) ? $_REQUEST['hidoper'] : 0;
$msg = (isset($_REQUEST['msg'])) ? $_REQUEST['msg'] : "";
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

    <!-- Start of nolaedu Zendesk Widget script -->
  <script>/*<![CDATA[*/window.zE||(function(e,t,s){var n=window.zE=window.zEmbed=function(){n._.push(arguments)},a=n.s=e.createElement(t),r=e.getElementsByTagName(t)[0];n.set=function(e){ n.set._.push(e)},n._=[],n.set._=[],a.async=true,a.setAttribute("charset","utf-8"), a.src="https://static.zdassets.com/ekr/asset_composer.js?key="+s, n.t=+new Date,a.type="text/javascript",r.parentNode.insertBefore(a,r)})(document,"script","ee68e503-b121-47ae-82f1-47dce3ede795");/*]]>*/</script>
  <!-- End of nolaedu Zendesk Widget script -->
</head>

<body>
<form id="loginform" name="loginform">

    <div class="logonBaseBox">
        <div class="logonLogo"><img src="img/pitsco.logo.login.png" alt="NOLAED" /></div>

        <div class="errormsg" style="height: auto; padding: 10px; width: 280px; font-size: 12.5px; font-weight: bold; display:none;"></div>
        <div class="waitmsg" style="text-align: center !important; height: 24px; margin-top:24px; padding: 2px 0; width: 100%; font-size: 12px; font-style: italic; font-weight: bold; display:none;">LOGGING IN... DO NOT REFRESH PAGE.</div>

        <div class="logonInputDivTop">
            <input type="text" id="txtusername" name="username" placeholder="User name" tabindex="1" class="logonInput" onKeyUp="if(event.keyCode==13) { document.getElementById('btnsignin').click(); return false;}" autocomplete="off" />
        </div>
        <div class="logonInputDiv">
            <input type="password" id="txtpassword" name="password" placeholder="Password" tabindex="2" class="logonInput" onKeyUp="if(event.keyCode==13) { document.getElementById('btnsignin').click(); return false;}" />
        </div>
        <div class="logonSubmitBtn">
            <input tabindex="3" type="submit" class="logonSubmit" id="btnsignin" name="btnsignin" value="Sign in" />
        </div>
        <div class="logonForgotBtn">
            <input tabindex="4" type="button" class="logonForgot" id="btnforgotpswd" name="btnforgotpswd" onClick="fn_fpswd_page();" value="Forgot password" />
        </div>
        <div class="loginCopyright">
              &copy; NOLA Education, LLC &nbsp; v2.0.0
        </div>
    </div>

    <input type="hidden" name="hidoper" id="hidoper"  />
</form>


<script>
    $(function () {
        var loginform = $('#loginform');
        loginform.submit(function (ev) {
            $(".errormsg").html("").hide();
            $(".waitmsg").slideDown();
            var output = loginform.serialize();
            $.ajax({
                type: 'POST',
                url: 'users/postlogin.php',
                data: output,
                success: function (data) {
                    if(data != "success"){
                        $(".waitmsg").hide();
                        $(".errormsg").html(data).slideDown();
                    }else if(data == "trial"){
                        window.location.replace("trialuserlesson.php");
                    }else{
                        window.location.replace("index.php");
                    }
                }
            });
            ev.preventDefault();
        });
    });

    function fn_fpswd_page(){
        window.location.replace("forgot-password.php");
    }
</script>

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
