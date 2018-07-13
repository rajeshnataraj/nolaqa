<?php
/**
 * Created by PhpStorm.
 * User: barney
 * Date: 2017-03-15
 * Time: 11:54 AM
 */
$oper = isset($method['oper']) ? $method['oper'] : '';
$editid =  isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
if($editid != 0) {
    $id = explode(",", $editid);
    $editid = $id[0];
    $sdistid = $id[1];
    $sshlid = $id[2];
    $suserid = $id[3];

    require_once('../../includes/UserManager.php');
    $userrow = UserManager::db_fetch_userid($editid);
    $user = new UserManager($userrow);
}

?>
<link rel="stylesheet" href="css/strength.css">
<section data-type='#users-individuals' id='users-individuals-settings'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="dialogTitle">
                    <?php
                    if($editid == 0){
                        echo "New Student";
                    }
                    else {
                        echo $user->name." Account Settings";
                    }
                    ?>
                </p>
                <p class="dialogSubTitleLight">&nbsp;</p>
                <h1></h1>
            </div>
        </div>


        <div class='row formBase'>

            <div class='ten columns centered insideForm'>
                <form name="settingsform" id="settingsform">
                    <div class="row rowspacer">
                        <div class="twelve columns">
                            <div class="settingsmsg" style="text-align: left; color:#689; font-size:12px; font-weight: bold; margin:0 auto; padding:0px; display: none"></div>
                        </div>
                    </div>
                    <div class="row rowspacer">
                        <div class="eight columns">
                            User Name<span class="fldreq">*</span>
                            <dl class="field row">
                                <dt class="text">
                                    <input id="txtusername" onblur="checkusername(this)" name="username" placeholder="User name" tabindex="2" type="text" value="<?php echo $user->username ?>" required autocomplete="off">
                                </dt>
                            </dl>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="eight columns">
                            Email <i style="font-size: 85%; color:#AAA;">(optional for students)</i>
                            <dl class='field row'>
                                <dt class='text'>
                                    <input id="txtemail" name="email" placeholder='Email' tabindex="3" type="email"  value="<?php echo $user->email;?>"  autocomplete="off">
                                </dt>
                            </dl>
                        </div>
                    </div>
                    <div class="row">
                        <div class="eight columns">
                            Password
                            <dl class='field row'>
                                <dt class='text' style="padding:0; overflow: hidden; margin-bottom:10px;">
                                    <input id="txtpassword" name="password" placeholder='Password' tabindex="4" type='password' required autocomplete="off" style="padding:10px; border: 1px solid #DDD; border-radius: 4px;">
                                </dt>
                            </dl>
                        </div>
                    </div>
                    <div class="row">
                        <div class="eight columns">
                            Verify Password<span class="fldreq">*</span>
                            <dl class='field row'>
                                <dt class='text'>
                                    <input id="txtpassword2" name="password2" placeholder='Verify Password' tabindex="5" type='password' required autocomplete="off">
                                </dt>
                            </dl>
                        </div>
                    </div>

                    <div class="row rowspacer">
                        <div class="eight columns" id="userphoto">
                            <div class="logonSubmitBtn">
                                <input type="submit" class="logonSubmit" id="btnsignin" name="btnsignin" tabindex="6"  value="Save Account Settings" />
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="hidoper" id="hidoper"  />
                </form>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript" src="js/strength.js"></script>

<script>



    function checkusername(txtusername){
        var username = $(txtusername).val();
        if(username != "<?php echo $user->username; ?>") {
            $.ajax({
                type: 'POST',
                url: 'users/checkusername.php',
                data: "username=" + username,
                success: function (data) {
                    if (data == "exists") {
                        $(".settingsmsg").slideDown().html("<div style='font-weight: bold; color:#ef6638; font-size:14px; margin-top:8px;'>This username already exists. <br>Please enter a new unique username.</div>");
                    }else{
                        $(".settingsmsg").hide().html("");
                    }
                }
            });
        }
    }
    $(function () {
        var settingsform = $('#settingsform');
        settingsform.submit(function (ev) {
            ev.preventDefault();
            $(".settingsmsg").slideDown().html("Updating Account Settings<br> Do not refresh page.");

            var txtusername = $("#txtusername");
            var username = $(txtusername).val();
            if(username != "<?php echo $user->username; ?>"){
                $.ajax({
                    type: 'POST',
                    url: 'users/checkusername.php',
                    data: "username="+username,
                    success: function (data) {
                        if(data == "exists"){
                            $(".settingsmsg").slideDown().html("<div style='font-weight: bold; color:#ef6638; font-size:14px; margin-top:8px;'>This username already exists. <br>Please enter a new unique username.</div>");
                        }else{
                            updateSettings();
                        }
                    }
                });
            }else{
                updateSettings();
            }

        });
        function updateSettings(){
            var username = $("#txtusername").val();
            var email = $("#txtemail").val();
            var password = $("#txtpassword").val();
            var password2 = $("#txtpassword2").val();
            if(password != password2){
                $(".settingsmsg").slideDown().html("<div style='font-weight: bold; color:#ef6638; font-size:14px; margin-top:8px;'>Error - Passwords do not match. Please try again.</div>");
            }
            else if(password.length < 1){
                $(".settingsmsg").slideDown().html("<div style='font-weight: bold; color:#ef6638; font-size:14px; margin-top:8px;'>Error - Password cannot be empty.</div>");
            }
            else{
                var output = "userid=<?php echo $user->id; ?>&username="+username+"&email="+email+"&password="+password;
                $.ajax({
                    type: 'POST',
                    url: 'users/updatesettings.php',
                    data: output,
                    beforeSend: function(){
                        showloadingalert("Updating Account Settings, please wait.");
                    },
                    success: function (data) {
                        if(data == "success"){
                            $(".settingsmsg").hide();
                            if(data=="success"){
                                $('.lb-content').html("Account Settings have been updated successfully.");
                                setTimeout('closeloadingalert();',800);
                                setTimeout(function(){
                                    $("#users-individuals_profile, #users-individuals-student_newstudent, #users-individuals-settings").remove();
                                }, 850);
                                setTimeout("removesections('#users-individuals');",850);
                                setTimeout('showpages("users-individuals-student","users/individuals/users-individuals-student.php");',1100);
                            }
                        }else{
                            $(".settingsmsg").slideDown().html("<div style='font-weight: bold; color:#ef6638; font-size:14px; margin-top:8px;'>" + data + "</div>");
                        }
                    }
                });
            }
        }
    });
</script>