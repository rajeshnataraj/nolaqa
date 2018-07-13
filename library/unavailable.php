<?php
//require_once('includes/UserManager.php');
//$userrow = UserManager::db_fetch_userid($uid);
//$currentuser = new UserManager($userrow);
//$useremail = $currentuser->email;
?>
<link rel="stylesheet" type="text/css" href="css/password.css">
<form id="accountSettings"  style="display:none;">

    <div class="logonBaseBox frgtPwdBox">
        <div class="logonLogo"></div>
        <div class="verifyHeadline" style="margin:10px 0;">
            Verify Your Account Information
        </div>
        <hr class="logonhr" style="margin:0 0 20px 0;">
        <div class="verifySubHeadline">
            Please verify that the e-mail address associated with this account is valid and up-to-date. All e-mails from Synergy ITC addressed to you will be sent to this address - including important security updates, password reset requests, and license information.<br>
        </div>

        <div class="verifyConfirmation">Your account settings have been successfully updated!</div>

        <div class="logonInputDiv" style="width:calc(100% - 12px); max-width: 300px; margin:0 auto; margin-top:16px; margin-bottom: 24px;">
            <input type="email" id="txtemail" name="email" placeholder="Enter A Valid E-Mail Address" tabindex="1" class="logonInput" autocomplete="on" required style="" value="">
        </div>
        <hr class="logonhr">
        <div class="verifySubHeadline" style="margin-top:20px;">
            Once each year we ask that teachers, administrators and other school officials update their password for Synergy ITC. Please set a new password below.<br>
        </div>
        <div class="verifySubHeadline">
            <i></i>
        </div>
        <div class="logonInputDiv" style="width:calc(100% - 12px); max-width: 300px; margin:0 auto; margin-top:16px;">
            <input type="password" id="txtpassword" name="password" placeholder="New Password" tabindex="2" class="logonInput" autocomplete="off"  style=""  required="">
        </div>
        <div class="logonInputDiv" style="width:calc(100% - 12px); max-width: 300px; margin:0 auto; margin-top:16px; margin-bottom: 24px;">
            <input type="password" id="txtpassword2" name="password2" placeholder="Confirm Password" tabindex="3" class="logonInput" autocomplete="off"  required="" />
        </div>
        <hr class="logonhr">
        <div class="verifyWaitmsg">UPDATING PASSWORD... PLEASE WAIT.</div>
        <div class="logonSubmitBtn" style="width:calc(100% - 12px); max-width: 300px; margin:0 auto; margin-top:20px;">
            <input tabindex="4" type="submit" class="logonSubmit" id="btnsignin" name="btnsignin" value="Save Account Information" />
        </div>
        <div class="loginCopyright">
            &copy; Pitsco, Inc. &nbsp; v2.0.0
        </div>
    </div>

</form>

<script type="text/javascript" src="js/jquery.blockUI.js" ></script>
<script type="text/javascript" src="js/password.js" ></script>
<script>
    $( function() {
        $.blockUI({ css: {
            border: '5px solid rgba(73,112,162,0.8)',
            padding: '3px',
            backgroundColor: "#FFF",
            'border-radius': '6px',
            'cursor': 'default',
            'box-shadow': "0 0 40px rgba(0,10,30,0.2), 0 0 10px rgba(0,10,30,0.4)",
            color: '#357',
            top: '8%',
            'width': '100%',
            'min-width': '320px',
            'max-width': '500px',
            'text-align': 'center'
        },
            message: $('#accountSettings')});


    });
    $(function () {
        $("#accountSettings").slideDown();
        $(function () {

        });
    });
</script>