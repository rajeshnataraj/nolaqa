<?php
@include('sessioncheck.php');
@include('UserManager.php');
$oper = (isset($method['oper'])) ? $method['oper'] : 0;
if($oper == "duallogin" and $oper != '') {
	$username = (isset($method['username'])) ? $method['username'] : '';
	$password = (isset($method['password'])) ? $method['password'] : '';

//      require_once('../includes/UserManager.php');
	$userrow = UserManager::db_fetch_user($username);
	$user = new UserManager($userrow);
	$login = $user->verify_password($password);
	if($login == "success"){
		$userrow = $user->userrow;
		$_SESSION['is_user_login'] = true;
		$_SESSION['username1'] = $user->username;
		$_SESSION['userid1'] = $user->id;
		$_SESSION['usr_full_name1'] = $user->name;
		echo "success";
	}
	else
	{
		echo "fail";
	}
}

if($oper == "dualloginform" and $oper != '') 
{
?>
	<form id="frmduallogin" name="frmduallogin">
		<div class='row' style="min-width:350px;">
        	Username
            <dl class='field row'>
                <dt class='text'>
                    <input type="text" placeholder="Enter the username"  id="dualusername" name="dualusername"  tabindex="1"  onKeyUp="if(event.keyCode==13) { document.getElementById('btnsignin').click(); return false;}"  />
                </dt>
            </dl>
            Password
            <dl class='field row'>
                <dt class='text'>
                    <input type="password" placeholder="Enter the password" id="dualpassword" name="dualpassword"  tabindex="2" maxlength="14" onKeyUp="if(event.keyCode==13) {fn_duallogin(); return false;}"  />
                </dt>
            </dl>
            <div class='row'>
            	<div class="six columns">
                	<input class="logonSubmit" type="button" value="log in" onClick="fn_duallogin();" tabindex="3" /> <input type="hidden" name="hidoper" id="hidoper"  />
                </div>
                <div class="six columns">
                	<input class="logonSubmit" type="button" value="cancel" onClick="closeloadingalert();" tabindex="4" id="btndualcancel" /> <input type="hidden" name="hidoper" id="hidoper"  />
                </div>
            </div>
		</div>	
    </form>
    
  	<script language="javascript" type="text/javascript">
		$('#dualusername').focus();
		$(function(){
			$("#frmduallogin").validate({
				errorElement: "dd",
				rules: { 
					dualusername: { required: true },
					dualpassword: { required: true }
				}, 
				messages: { 
					dualusername: { required : "user name is required." },
					dualpassword: { required : "password is required." }
				},
				errorPlacement: function(error, element) {
					$(element).parents('dl').addClass('error');
					error.appendTo($(element).parents('dl'));
					error.addClass('msg'); 
				},
				highlight: function(element, errorClass, validClass) {
					$(element).parent('dl').addClass(errorClass);
					$(element).addClass(errorClass).removeClass(validClass);
				},
				unhighlight: function(element, errorClass, validClass) {
					if($(element).attr('class') == 'error'){
						$(element).parents('dl').removeClass(errorClass);
						$(element).removeClass(errorClass).addClass(validClass);
					}
				},
				onkeyup: false,
				onblur: true
			});
			
			$('#btndualcancel').blur(function(e) {
				$('#dualusername').focus();
			});
		});
	</script>
   <?php
}

if($oper == "duallogout" and $oper != '') 
{
	$_SESSION['username1'] = "";
	$_SESSION['userid1'] = "";
	echo "success";
}

@include("footer.php");