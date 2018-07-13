 
/*----
    fn_changecity()
	Function to show the city drop down
----*/

function fn_changecity(statevalue){
	
	var dataparam = "oper=changecity&statevalue="+statevalue;
	$.ajax({
		type: 'post',
		url: 'users/individuals/users-individuals-districtadmin_newdistrictadmindb.php',
		data: dataparam,
		beforeSend: function(){
			$('#divddlcity').html('<img src="img/loader.gif" width="200"  border="0" />'); 
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
		url: 'users/individuals/users-individuals-districtadmin_newdistrictadmindb.php',
		data: dataparam,
		beforeSend: function(){
			$('#divddldist').html('<img src="img/loader.gif" width="200"  border="0" />'); 
		},
		success:function(ajaxdata) {
			$('#divddldist').html(ajaxdata);
			if($("#dit").attr('class')=='field row error')
				$('#ddldist').valid();
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
		url: 'users/individuals/users-individuals-districtadmin_newdistrictadmindb.php',
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
		url: 'users/individuals/users-individuals-districtadmin_newdistrictadmindb.php',
		data: dataparam,
		beforeSend: function(){
			$('#divddlzip1').html('<img src="img/loader.gif" style="float: left;margin-left: 485px;margin-top: -50px;width: 120px;" />'); 
		},
		success:function(ajaxdata) {
			$('#divddlzip1').html(ajaxdata);
		}
		
	});	
}
/*----
    fn_updatedistrictadmin()
	Function to update the district admin details
----*/
function fn_updatedistrictadmin(editid){
	
	var state = $('#ddlstate').val();
	var city = $('#ddlcity').val();
	var distid = $('#ddldist').val();
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
	
	actionmsg = "Updating";
	alertmsg = /*fname+" "+lname+*/"District admin account is updated successfully"; 
	
	if($("#dadminval").validate().form())
	{
		var dataparam = "oper=updatedistrictadmin&state="+state+"&city="+city+"&distid="+distid+"&fname="+escapestr(fname)+"&lname="+escapestr(lname)+"&address1="+escapestr(address1)+"&email="+email+"&state1="+state1+"&city1="+city1+"&zipcode1="+zipcode1+"&officeno="+officeno+"&faxno="+faxno+"&mobileno="+mobileno+"&photo="+photo+"&editid="+editid+"&tags="+escapestr($('#form_tags_newdistrict').val());
		$.ajax({
			type: 'post',
			url: 'users/individuals/users-individuals-districtadmin_newdistrictadmindb.php',
			data: dataparam,
			beforeSend: function(){
				showloadingalert(actionmsg+", please wait.");	
			},
			success:function() {
				$('.lb-content').html(alertmsg);					
					setTimeout('closeloadingalert();',1000);
					setTimeout("removesections('#users-individuals');",1500);
					setTimeout('showpages("users-individuals-districtadmin","users/individuals/users-individuals-districtadmin.php");',1500);
			}
			
		});
	}
}
/*----
    fn_createdistrictadmin()
	Function to save the district admin details
----*/
function fn_createdistrictadmin(id){
	
	var state = $('#ddlstate').val();
	var city = $('#ddlcity').val();
	var distid = $('#ddldist').val();
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
		alertmsg = "District admin account is created successfully";
	}
	else{
		actionmsg = "Updating";
		alertmsg = "District admin account is updated successfully";
	}
	if($("#dadminval").validate().form())
	{
		var dataparam = "oper=savedistrictadmin&state="+state+"&city="+city+"&distid="+distid+"&fname="+escapestr(fname)+"&lname="+escapestr(lname)+"&address1="+escapestr(address1)+"&email="+email+"&state1="+state1+"&city1="+city1+"&zipcode1="+zipcode1+"&officeno="+officeno+"&faxno="+faxno+"&mobileno="+mobileno+"&photo="+photo+"&tags="+escapestr($('#form_tags_newdistrict').val())+"&id="+id;
		$.ajax({
			type: 'post',
			url: 'users/individuals/users-individuals-districtadmin_newdistrictadmindb.php',
			data: dataparam,
			beforeSend: function(){
				showloadingalert(actionmsg+", please wait.");	
			},
			success:function(data) {
				if(data=="success"){
					$('.lb-content').html(alertmsg);					
					setTimeout('closeloadingalert();',1000);
					setTimeout("removesections('#users-individuals');",1500);
					setTimeout('showpages("users-individuals-districtadmin","users/individuals/users-individuals-districtadmin.php");',1500);
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
    fn_deletdistrictadmin()
	Function to delet the district admin details
----*/
function fn_deletdistrictadmin(id){
	
	var dataparam = "oper=deletdistrictadmin&editid="+id;
	actionmsg = "Deleting";
	alertmsg = "District admin account is deleted successfully"; 
	alertmsg1 = "District deleted. Because he/she is primary admin in district"; 
	
	$.Zebra_Dialog('Are you sure you want to delete?',
		{
			'type': 'confirmation',
			'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {
					
				$.ajax({
					type: 'post',
					url: 'users/individuals/users-individuals-districtadmin_newdistrictadmindb.php',
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
							setTimeout('showpages("users-individuals-districtadmin","users/individuals/users-individuals-districtadmin.php");',1700);
					}
				});	
			}}
		]
	});
}
/*----
    fn_loadpage()
	Function to cancel the page
----*/
function fn_loadpage(){
	setTimeout("removesections('#users-individuals');",500);
	setTimeout('showpages("users-individuals-districtadmin","users/individuals/users-individuals-districtadmin.php");',500);
}

/*----
    fn_resetda()
	Function to reset password
----*/
function fn_resetda(username){
	
	var dataparam = "oper=resetda&username="+username;
    actionmsg = "Generating Reset Link";
    alertmsg = "A password reset link has been sent to the e-mail address associated with this account.";
	
	$.Zebra_Dialog('Are you sure you want to reset the password?',
		{
			'type': 'confirmation',
			'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {
					
				$.ajax({
					method: 'post',
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
	
	var dataparam = "oper=savedistrictadminmail&mailid="+mailid;
	$.ajax({
		method: 'post',
		url: 'users/individuals/users-individuals-districtadmin_newdistrictadmindb.php',
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
