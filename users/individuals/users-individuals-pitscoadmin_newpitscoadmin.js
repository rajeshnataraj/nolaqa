/*----
    fn_changecity()
	Function to show the city drop down
----*/
function fn_changecity(statevalue){
	
	var dataparam = "oper=changecity&statevalue="+statevalue;
	$.ajax({
		type: 'post',
		url: 'users/individuals/users-individuals-pitscoadmin_newpitscoadmindb.php',
		data: dataparam,
		beforeSend: function(){
			$('#divddlcity').html('<img src="img/loader.gif" width="120"  border="0" />'); 
		},
		success:function(ajaxdata) {
			$('#divddlcity').html(ajaxdata);
			if($("#cit").attr('class')=='field row error')
				$('#ddlcity').valid();
		}
		
	});	
}
/*----
    fn_changezip()
	Function to show the zip drop down
----*/
function fn_changezip(cityvalue){
	
	var dataparam = "oper=changezip&cityvalue="+cityvalue+"&statevalue="+$('#ddlstate').val();
	$.ajax({
		type: 'post',
		url: 'users/individuals/users-individuals-pitscoadmin_newpitscoadmindb.php',
		data: dataparam,
		beforeSend: function(){
			$('#divddlzip').html('<img src="img/loader.gif" width="120"  border="0" />'); 
		},
		success:function(ajaxdata) {
			$('#divddlzip').html(ajaxdata);
			if($("#zip").attr('class')=='field row error')
				$('#ddlzip').valid();
		}
		
	});	
}
/*----
    fn_createpitscoadmin()
	Function to save the pitsco admin details
----*/
function fn_createpitscoadmin(id){
	
	var fname = $('#fname').val();
	var lname = $('#lname').val();
	var email = $('#email').val();
	var photo = $('#hiduploadfile').val();
	
	var address1 = $('#address').val();
	var state1 = $('#ddlstate').val();
	var city1 = $('#ddlcity').val();
	var zipcode1 = $('#ddlzip').val();
	var officeno = $('#officeno').val();
	var faxno = $('#faxno').val();
	var mobileno = $('#mobileno').val();
	var homeno = $('#homeno').val();
	
	if(id==0){	
		actionmsg = "Saving";
		alertmsg = "Pitsco admin account is created successfully";
	}
	else{
		actionmsg = "Updating";
		alertmsg = "Pitsco admin account is updated successfully";
	}
	
	if($("#padmin").validate().form())
	{
		
	var dataparam = "oper=savepitscoadmin&fname="+escapestr(fname)+"&lname="+escapestr(lname)+"&email="+email+"&address1="+escapestr(address1)+"&state1="+state1+"&city1="+city1+"&zipcode1="+zipcode1+"&officeno="+officeno+"&faxno="+faxno+"&mobileno="+mobileno+"&homeno="+homeno+"&photo="+photo+"&tags="+escapestr($('#form_tags_newpadmin').val())+"&id="+id;
		$.ajax({
			type: 'post',
			url: 'users/individuals/users-individuals-pitscoadmin_newpitscoadmindb.php',
			data: dataparam,
			beforeSend: function(){
				showloadingalert(actionmsg+", please wait.");	
			},
			success:function(data) {
				if(trim(data)=="success"){
					$('.lb-content').html(alertmsg);					
					setTimeout('closeloadingalert();',1000);
					setTimeout("removesections('#users-individuals');",1500);
					setTimeout('showpages("users-individuals-pitscoadmin","users/individuals/users-individuals-pitscoadmin.php");',1500);
				}
				else if(trim(data)=="fail"){
					$('.lb-content').html("Incorrect Data");
					setTimeout('closeloadingalert()',1000);
				}
			}
			
		});	
	}
}
/*----
    fn_deletpitscoadmin()
	Function to delet the pitsco admin details
----*/
function fn_deletpitscoadmin(editid,pname){
	
	var dataparam = "oper=deletpitscoadmin&editid="+editid;
	
	actionmsg = "Deleting";
	alertmsg = "Pitsco admin account is deleted successfully";
	
	$.Zebra_Dialog('Are you sure you want to delete?',
		{
			'type': 'confirmation',
			'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {
					
				$.ajax({
					type: 'post',
					url: 'users/individuals/users-individuals-pitscoadmin_newpitscoadmindb.php',
					data: dataparam,
					beforeSend: function(){
						showloadingalert(actionmsg+", please wait.");	
					},
					success:function() {
						$('.lb-content').html(alertmsg);	
						setTimeout('closeloadingalert();',1000);
						setTimeout("removesections('#users-individuals');",1500);
						setTimeout('showpages("users-individuals-pitscoadmin","users/individuals/users-individuals-pitscoadmin.php");',1500);
					}
				});
			}}
		]
	});
}

/*----
    fn_resetpa()
	Function to reset password
----*/
function fn_resetpa(username){
	var dataparam = "oper=resetpa&username="+username;
    actionmsg = "Generating Reset Link";
    alertmsg = "A password reset link has been sent to the e-mail address associated with this account.";
	
	$.Zebra_Dialog('Are you sure you want to reset the password?',
		{
			'type': 'confirmation',
			'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {
					
				$.ajax({
					type: 'post',
                    url: 'users/postpassword.php',
					data: dataparam,
					beforeSend: function(){
						showloadingalert(actionmsg+", please wait.");	
					},
                    success: function (data) {
                        if(data == "success"){
                            $('.lb-content').html(alertmsg);
                            setTimeout('closeloadingalert();',2300);
                        }else{
                            $('.lb-content').html("Oops... Something went wrong. Please try again.");
                            setTimeout('closeloadingalert();',1300);
                        }
                    }
				});
			}}
		]
	});
} 
 /*----
    fn_resendmail()
	Function to resend mail
----*/
function fn_resendmail(mailid)
{
	actionmsg = "Mail Sending";
	alertmsg = "Mail Send successfully.";
	
	var dataparam = "oper=savepitscoadminmail&mailid="+mailid;
 	$.ajax({
			type: 'post',
			url: 'users/individuals/users-individuals-pitscoadmin_newpitscoadmindb.php',
			data: dataparam,
			beforeSend: function(){
				showloadingalert(actionmsg+", please wait.");	
			},
			success:function() {
				$('.lb-content').html(alertmsg);
 				setTimeout('closeloadingalert();',1000);				
			}
			
		});
}
