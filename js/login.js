function fn_login() {

	if($("#frmlogin").validate().form()){
		
		if($("input[id='chkremember']:checked").length > 0)
		{  
		   $("input[id='chkremember']").val(1);
		}
		else {
			$("input[id='chkremember']").val(0);
		}	
		
		var frm = document.frmlogin;
		frm.method = "post";
		frm.action = "login.php";
		frm.hidoper.value = "login";
		frm.submit();
	}
}

function fn_soslogin() {

	if($("#frmlogin").validate().form()){
		
		if($("input[id='chkremember']:checked").length > 0)
		{  
		   $("input[id='chkremember']").val(1);
		}
		else {
			$("input[id='chkremember']").val(0);
		}	
		
		var frm = document.frmlogin;
		frm.method = "post";
		frm.action = "sos.php";
		frm.hidoper.value = "login";
		frm.submit();
	}
}

function fn_fpswd_page() {
	window.location="forgot-password.php";	
}

function fn_forgotpassword() {
	if($("#frmfgpswd").validate().form()){
		
		
	var username=$('#txtusername').val();
	var code=$('#txtverifycode').val();
	var dataparam = "oper=checkusername&username="+username;
	$.ajax({
		type: 'post',
		url: 'ajaxpages/resetpassword-ajax.php',
		data: dataparam,
		beforeSend: function(){
			showloadingalert("Verifying, please wait.");
		},
		success:function(ajaxdata) {
			
			if(ajaxdata=='student')
			{
				$.Zebra_Dialog('Please contact your teacher to retrieve your Password.',
				{
					'type': 'information',
					'buttons': [
						{caption: 'Ok', callback: function() {
							window.location = "login.php"; //window.location = "login.php";		
						}
					}]
				});
			}
			
			if(ajaxdata=="available")
			{
				var dataparam = "oper=checkverfication&code="+code;
				$.ajax({
					type: 'post',
					url: 'ajaxpages/resetpassword-ajax.php',
					data: dataparam,
					success:function(ajaxdata) 
					{
						if(ajaxdata=="verified")
						{
							var dataparam = "oper=sentpasswordtoemail&username="+username;
							$.ajax({
								type: 'post',
								url: 'ajaxpages/resetpassword-ajax.php',
								data: dataparam,
								success:function(ajaxdata) 
								{
									closeloadingalert();
									if(ajaxdata=="success")
									{
										$.Zebra_Dialog('Your password has been sent to your email id. Please check you mail inbox.',
										{
											'type': 'confirmation',
											'buttons': [
												{caption: 'Ok', callback: function() {
													window.location = "login.php"; //window.location = "login.php";		
												}
											}]
										});
									}
								}
							});				
						} 
						else if(ajaxdata != "verified")	
						{
							closeloadingalert();
							$.Zebra_Dialog('Sorry, verification code is incorrect. Please try again.',
							{
								'type': 'error',
								'buttons': [
									{caption: 'Ok', callback: function() {
										$('.recaptchaimg').trigger('click');
										$('#txtverifycode').val('')
										$('#txtverifycode').focus();
									}
								}]
							});
						}	
					}
				});
			}
			else if(ajaxdata=="exist")
			{
				$.Zebra_Dialog('Sorry, we couldn&lsquo;t find the username. Please try again.',
				{
					'type': 'error',
					'buttons': [
						{caption: 'Ok', callback: function() {
							window.location = "forgot-password.php"; //window.location = "login.php";		
						}
					}]
				});
								
			 }
			
			
		}
		
	});
  }
}

$(document).ready(function() {
	$('#txtusername').focus();
	
	$('#txtpassword').bind('copy paste cut',function(e) {
		e.preventDefault(); //disable cut,copy,paste
	});
	
	$(':input[placeholder]').placeholder();
	
		$("#frmlogin").validate({
		rules: { 
			txtusername: { required: true, placeholder: true },
			txtpassword: { required: true, placeholder: true }
		}, 
		messages: { 
			txtusername: { required : "", placeholder: "" },
			txtpassword: { required : "", placeholder: "" }
		},
		errorPlacement: function(error, element) {
			var offset = $(element).offset();
			error.insertAfter(element);
			error.offset({ top: (offset.top-15), left: (offset.left) });
		},
		onkeyup: false,
		onblur: true
	});
});

function fn_trialform()
{
	var title = "Trial user registration";	
	$.colorbox({
		href:"trial-ajax.php?oper=trialform",
		type: 'POST',
		title: title,
		overlayClose: false,
		escKey: false
	});
}

function fn_createtrialuser()
{
	frmvalid =  $("#newuserform").validate().form();
	if(frmvalid)
	{
		var dataparam="oper=createtrialuser&fname="+$('#fname').val()+"&lname="+$('#lname').val()+"&email="+$('#email').val()+"&users="+4+"&uname="+$('#uname').val()+"&pwd="+$('#pwd').val();	
		$.ajax({
		url:'trial-ajax.php',
		data: dataparam,
		beforeSend:function(){
					showalertbox('LMS - Alert','Loading, please wait.');
		},
		success:function(ajaxdata){		
			hidealertbox();				
			alert("Your account has been created. \n\nWe have sent your login details to your email! Enjoy the trial!");						
			window.location="login.php";
		}
		});
	}
}