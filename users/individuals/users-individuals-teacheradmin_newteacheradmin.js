/*----
    fn_changecity()
	Function to show the city drop down
----*/
function fn_changecity(statevalue){
	
	var dataparam = "oper=changecity&statevalue="+statevalue;
	$.ajax({
		type: 'post',
		url: 'users/individuals/users-individuals-teacheradmin_newteacheradmindb.php',
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
		url: 'users/individuals/users-individuals-teacheradmin_newteacheradmindb.php',
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
		url: 'users/individuals/users-individuals-teacheradmin_newteacheradmindb.php',
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
		url: 'users/individuals/users-individuals-teacheradmin_newteacheradmindb.php',
		data: dataparam,
		beforeSend: function(){
			$('#divddlcity1').html('<img src="img/loader.gif" style="float: left;margin-left: 485px;margin-top: -30px;width: 120px;" />'); 
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
		url: 'users/individuals/users-individuals-teacheradmin_newteacheradmindb.php',
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
    fn_createteacheradmin()
	Function to save the teacher admin details
----*/
function fn_createteacheradmin(id){
	
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
	var mobileno = $('#mobileno').val();
	var homeno = $('#homeno').val();
	var photo = $('#hiduploadfile').val();
	
//       New Line for training teacher or not start 
        if($("#chkbox").is(':checked')){
		var chkteacher = 1;
        }
	else{
		var chkteacher = 0;
	}
        
        if($("#chkboxitc").is(':checked')){
		var chkitct = 1;
        }
	else{
		var chkitct = 0;
	}
        
        if($("#chkboxsos").is(':checked')){
		var chksost = 1;
        }
	else{
		var chksost = 0;
	}
//        New Line for training teacher or not End
        
       if(!(chkitct ==1 || chksost == 1)){
        
           showloadingalert("Please select any one of ITC or SOS Teacher.");
           setTimeout('closeloadingalert()',3000);
           return false;
       }
        
        
	if(id==0){	
		actionmsg = "Saving";
		alertmsg = "Teacher admin account is created successfully";
	}
	else{
		actionmsg = "Updating";
		alertmsg = "Teacher admin account is updated successfully";
	}
	
	if($("#tadminval").validate().form())
	{
		var dataparam = "oper=saveteacheradmin&state="+state+"&city="+city+"&distid="+distid+"&shlid="+shlid+"&fname="+fname+"&lname="+lname+"&address1="+address1+"&email="+email+"&state1="+state1+"&city1="+city1+"&zipcode1="+zipcode1+"&mobileno="+mobileno+"&homeno="+homeno+"&photo="+photo+"&tags="+$('#form_tags_newteacheradmin').val()+"&id="+id+"&chkteacher="+chkteacher+"&chkitct="+chkitct+"&chksost="+chksost;
		$.ajax({
			type: 'post',
			url: 'users/individuals/users-individuals-teacheradmin_newteacheradmindb.php',
			data: dataparam,
			beforeSend: function(){
				showloadingalert(actionmsg+", please wait.");	
			},
			success:function(data) {
				if(data=="success"){
					$('.lb-content').html(alertmsg);					
					setTimeout('closeloadingalert();',1000);
					setTimeout("removesections('#users-individuals');",1500);
					setTimeout('showpages("users-individuals-teacheradmin","users/individuals/users-individuals-teacheradmin.php");',1500);
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
    fn_deletteacheradmin()
	Function to delet the teacher admin details
----*/
function fn_deletteacheradmin(editid){

	var dataparam = "oper=deletteacheradmin&editid="+editid;
	//alert(dataparam);
	actionmsg = "Deleting";
	alertmsg = "Teacher admin account is deleted successfully";

	$.Zebra_Dialog('Are you sure you want to delete?',
		{
			'type': 'confirmation',
			'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {

				$.ajax({
					type: 'post',
					url: 'users/individuals/users-individuals-teacheradmin_newteacheradmindb.php',
					data: dataparam,
					beforeSend: function(){
						showloadingalert(actionmsg+", please wait.");
					},
					success:function() {
						$('.lb-content').html(alertmsg);
						setTimeout('closeloadingalert();',1000);
						setTimeout("removesections('#users-individuals');",1500);
						setTimeout('showpages("users-individuals-teacheradmin","users/individuals/users-individuals-teacheradmin.php");',1500);
					}
				});
			}}
		]
	});
}
/*----
    fn_resetta()
	Function to reset password
----*/

function fn_resetta(username){

    var dataparam = "oper=resetta&username="+username;
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
	
	var dataparam = "oper=saveteacheradminmail&mailid="+mailid;
	$.ajax({
			type: 'post',
			url: 'users/individuals/users-individuals-teacheradmin_newteacheradmindb.php',
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


function fn_showschoolind(distid)
{ 
    
    var dataparam = "oper=showschools&distid="+distid;
    
    $.ajax({
            type: 'post',
            url: 'users/individuals/users-individuals-teacheradmin_newteacheradmindb.php',
            data: dataparam,
            beforeSend: function(){
            },
            success:function(data) {
                    $('#schooldiv').show();   
                    $('#schooldiv').html(data);//Used to load the student details in the dropdown
            }
    });
}

function fn_session(schoolid,distid){
   
    var dataparam = "oper=session&distid="+distid+"&schoolid="+schoolid;		
    $.ajax({
            type: 'post',
            url: 'users/individuals/users-individuals-student_newstudentdb.php',
            data: dataparam,
            success:function(data) {
            
            }
     });
}


function fn_showteacheradmin(schoolid,distid){
    
    if(schoolid=='0' && distid=='0')
    {
        var schoolid=$('#schoolid').val();
        var distid=$('#districtid').val();
    }
    
    
    var dataparam = "oper=showteacheradmin&distid="+distid+"&schoolid="+schoolid;		
    $.ajax({
            type: 'post',
            url: 'users/individuals/users-individuals-teacheradmin_newteacheradmindb.php',
            data: dataparam,
            success:function(data) {
            $("#teacheradminlist").html(data);
            }
     });
}

function fn_schoolpurchaseteacher()
{ 
    
    var dataparam = "oper=schoolpurchase";
    
    $.ajax({
            type: 'post',
            url: 'users/individuals/users-individuals-teacheradmin_newteacheradmindb.php',
            data: dataparam,
            beforeSend: function(){
            },
            success:function(data) {
                    $('#districtind').hide();
                    $('#shpurchase').show();   
                    $('#purchasediv').html(data);
            }
    });
}

function fn_homepurchaseteacher(){
    
    
    var dataparam = "oper=homepurchaseteacher";		
    $.ajax({
            type: 'post',
            url: 'users/individuals/users-individuals-teacheradmin_newteacheradmindb.php',
            data: dataparam,
            success:function(data) {
            $("#teacheradminlist").html(data);
            }
     });
}

