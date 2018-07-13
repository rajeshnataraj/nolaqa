function trim(stringToTrim) {
	return stringToTrim.replace(/^\s+|\s+$/g,"");
}


/*----
    fn_delete_image()
	Function to delete the profile picture or school logo
----*/

function fn_delete_image(deleteflag) {
	
	var dataparam = "oper=deleteimage&imagepath="+$("."+deleteflag+" img").attr('src')+"&imgtype=profilepic";
	
	$.ajax({
		type: 'post',
		url: 'ajaxpages/register-ajax.php',
		data: dataparam,
		success:function(ajaxdata, textStatus) {
			$("."+deleteflag+"").html(ajaxdata);
		},
		error: function(){
			window.location = '';
		}
	});
	
}

function fn_register(userid){ 
					
	var frmvalid = false;
	frmvalid =  $("#frmregister").validate().form();
	if(frmvalid){
		 
		actionmsg = "Loading"; 
		alertmsg ="Your account has been activated. your login credentials has been sent to your e-mail id.";
		var dataparam = "oper=register&firstname="+$('#txtfirstname').val()+"&lastname="+$('#txtlastname').val()+"&usernamec="+$('#txtusername').val()+"&password="+$('#txtpassword').val()+"&usersid="+$('#hidusrid').val(); //+"&hidimage="+$('#hiduploadfile').val()
		$.ajax({
			type: "POST",
			url: "ajaxpages/register-ajax.php",
			data: dataparam,
			beforeSend:function(){
				var cssobj = { 'width' : $('.logonBaseBox').width(),
				 			   'height' : (($('.logonBaseBox').height() -2)/2),
							   'position' : 'absolute',
							   'background' : '#FFF',
							   'top' : '22px',
							   'padding-top' : '18%',
							   'text-align' : 'center'
							 };
				$('.logonBaseBox').append('<div class="regload"><img src="img/AjaxLoader.gif" /></div>');
				$('.regload').css(cssobj);
			},
			success: function(data){
				if(trim(data)=="success")
				{
					$('.regload').hide();
					$.Zebra_Dialog('Your account has been activated. Your log in credentials have been sent to your email ID. Please click log in to proceed.',
					{
						'type': 'confirmation',
						'buttons': [
							{caption: 'Login', callback: function() {
								window.location = "login.php"; //window.location = "login.php";		
							}
						}]
					});
				}
			}
		});
	}
}

function fn_confirmreset(userid){
	if(fn_checkpassword() && fn_recheckpassword()) {

		actionmsg = "Resetting your password";
		var dataparam = "oper=confirmresetpswd&password="+$('#txtpassword').val()+"&usersid="+userid;
				
		$.ajax({
			type: "POST",
			url: "ajaxpages/register-ajax.php",
			data: dataparam,
			beforeSend:function(){
				showloadingalert(actionmsg+", please wait.");
			},
			success: function(data){
				//alert(data);
				if(trim(data) == '1'){
					closeloadingalert();
					$('.reg-main').html('<div class="reg-title">PITSCO - Reset Password</div><br /><br /> <center>Your account password has been reset. <br /><a href="login.php">Click here</a> to login.</center><p>&nbsp;</p>')
				}
			}
		});
	}
}


$(document).ready(function() {
	$('#txtfirstname').focus();
	//$(':input[placeholder]').placeholder();
	
	//$('.logonInputDivSmall label').css('top','-17px');
	//$('.logonInputDiv label').css('top','-17px');
	
});