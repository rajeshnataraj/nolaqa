/*----
    fn_changecity()
	Function to show the city drop down
----*/
function fn_changecity(statevalue){
	
	var dataparam = "oper=changecity&statevalue="+statevalue;
	$.ajax({
		type: 'post',
		url: 'users/individuals/users-individuals-schooladmin_newschooladmindb.php',
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
    fn_loaddistrict()
	Function to show the district drop down
----*/
function fn_loaddistrict(cityname){
	
	var dataparam = "oper=changedistrict&cityname="+cityname+"&statevalue="+$('#ddlstate').val();
	$.ajax({
		type: 'post',
		url: 'users/individuals/users-individuals-schooladmin_newschooladmindb.php',
		data: dataparam,
		beforeSend: function(){
			$('#divddldist').html('<img src="img/loader.gif" width="120"  border="0" />'); 
		},
		success:function(ajaxdata) {
			$('#divddldist').html(ajaxdata);
			if($("#dit").attr('class')=='field row error')
				$('#ddldist').valid();
		}
	});	
}
/*----
    fn_loadschool()
	Function to show the school drop down
----*/
function fn_loadschool(state,city){
	
	var distid = $('#ddldist').val();
	var dataparam = "oper=changeschool&distid="+distid+"&state="+state+"&city="+city;
	$.ajax({
		type: 'post',
		url: 'users/individuals/users-individuals-schooladmin_newschooladmindb.php',
		data: dataparam,
		beforeSend: function(){
			$('#divddlshl').html('<img src="img/loader.gif" width="120"  border="0" />'); 
		},
		success:function(ajaxdata) {
			$('#divddlshl').html(ajaxdata);
			if($("#shl").attr('class')=='field row error')
				$('#ddlshl').valid();
		}
	});	
}
/*----
    fn_changecity1()
	Function to show the city1 drop down
----*/
function fn_changecity1(statevalue){
	var dataparam = "oper=changecity1&statevalue="+statevalue;
	$.ajax({
		type: 'post',
		url: 'users/individuals/users-individuals-schooladmin_newschooladmindb.php',
		data: dataparam,
		beforeSend: function(){
			$('#divddlcity1').html('<img src="img/loader.gif" style="float: left;margin-left: 485px;margin-top: -40px;width: 120px;" />'); 
		},
		success:function(ajaxdata) {
			$('#divddlcity1').html(ajaxdata);
		}
		
	});	
}
/*----
    fn_changezip1()
	Function to show the zip1 drop down
----*/
function fn_changezip1(cityvalue){
	var dataparam = "oper=changezip1&cityvalue="+cityvalue+"&statevalue="+$('#ddlstate1').val();
	$.ajax({
		type: 'post',
		url: 'users/individuals/users-individuals-schooladmin_newschooladmindb.php',
		data: dataparam,
		beforeSend: function(){
			$('#divddlzip1').html('<img src="img/loader.gif" style="float: left;margin-left: 485px;margin-top: -40px;width: 120px;" />'); 
		},
		success:function(ajaxdata) {
			$('#divddlzip1').html(ajaxdata);
		}
		
	});	
}
/*----
    fn_createschooladmin()
	Function to save the school admin details
----*/
function fn_createschooladmin(id){
	
	var state = $('#ddlstate').val();
	var city = $('#ddlcity').val();
	var distid = $('#ddldist').val();
	var shlid = $('#ddlshl').val();
	var address1 = $('#address').val();
		
	var fname = $('#fname').val();
	var lname = $('#lname').val();
	var email = $('#email').val();
	
	var state1 = $('#ddlstate1').val();
	var city1 = $('#ddlcity1').val();
	var zipcode1 = $('#ddlzip1').val();
	var officeno = $('#officeno').val();
	var faxno = $('#faxno').val();
	var mobileno = $('#mobileno').val();
	var photo = $('#hiduploadfile').val();
	
	if(id==0){	
		actionmsg = "Saving";
		alertmsg = "School admin account is created successfully";
	}
	else{
		actionmsg = "Updating";
		alertmsg = "School admin account is updated successfully";
	}
	if($("#dadminval").validate().form())
	{
		var dataparam = "oper=saveschooladmin&state="+state+"&city="+city+"&distid="+distid+"&shlid="+shlid+"&fname="+escapestr(fname)+"&lname="+escapestr(lname)+"&address1="+escapestr(address1)+"&email="+email+"&state1="+state1+"&city1="+city1+"&zipcode1="+zipcode1+"&officeno="+officeno+"&faxno="+faxno+"&mobileno="+mobileno+"&photo="+photo+"&tags="+escapestr($('#form_tags_newschool').val())+"&id="+id;
		$.ajax({
			type: 'post',
			url: 'users/individuals/users-individuals-schooladmin_newschooladmindb.php',
			data: dataparam,
			beforeSend: function(){
				showloadingalert(actionmsg+", please wait.");	
			},
			success:function(data) {
				if(data=="success"){
					$('.lb-content').html(alertmsg);					
					setTimeout('closeloadingalert();',1000);
					setTimeout("removesections('#users-individuals');",1500);
					setTimeout('showpages("users-individuals-schooladmin","users/individuals/users-individuals-schooladmin.php");',1500);
				}
				else if(data=="fail"){
					$('.lb-content').html("Incorrect Data");
					setTimeout('closeloadingalert()',1000);
				}
			}
			
		});
	}
}
/*----
    fn_deletschooladmin()
	Function to delet the school admin details
----*/
function fn_deletschooladmin(id){
	
	var dataparam = "oper=deletschooladmin&editid="+id;
	actionmsg = "Deleting";
	alertmsg = "School admin account is deleted successfully"; 
	alertmsg1 = "School admin cannot deleted. Because he/she is primary admin in school"; 
	
	$.Zebra_Dialog('Are you sure you want to delete?',
		{
			'type': 'confirmation',
			'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {	
			
				$.ajax({
					type: 'post',
					url: 'users/individuals/users-individuals-schooladmin_newschooladmindb.php',
					data: dataparam,
					beforeSend: function(){
						showloadingalert(actionmsg+", please wait.");	
					},
					success:function(ajaxdata) {
						if(ajaxdata == "success")
						{
							$('.lb-content').html(alertmsg);
						}
						else{
							$('.lb-content').html(alertmsg1);
						}
										
							setTimeout('closeloadingalert();',1500);
							setTimeout("removesections('#users-individuals');",1700);
							setTimeout('showpages("users-individuals-schooladmin","users/individuals/users-individuals-schooladmin.php");',1700);
					}
				});	
			}}
		]
	});
}
/*----
    fn_resetca()
	Function to reset password
----*/
function fn_resetsa(username){
	
	var dataparam = "oper=resetsa&username="+username;
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
	
	var dataparam = "oper=saveschooladminmail&mailid="+mailid;
 	$.ajax({
			type: 'post',
			url: 'users/individuals/users-individuals-schooladmin_newschooladmindb.php',
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
