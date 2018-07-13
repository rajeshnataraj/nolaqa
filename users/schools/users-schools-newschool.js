function ampreplace(str)
{
	return str.replace(/&/g,"%26");
}

function trim(stringToTrim) 
{
	return stringToTrim.replace(/^\s+|\s+$/g,"");
}

/*----
    fn_changecity()
	Function to show the city drop down
----*/	
function fn_changecity(statevalue){
	var dataparam = "oper=changecity&statevalue="+statevalue;
	$.ajax({
		type: 'post',
		url: 'users/schools/users-schools-newschooldb.php',
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
		url: 'users/schools/users-schools-newschooldb.php',
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
    fn_changecity1()
	Function to show the city1 drop down
----*/	
function fn_changecity1(statevalue){
	var dataparam = "oper=changecity1&statevalue="+statevalue;
	$.ajax({
		type: 'post',
		url: 'users/schools/users-schools-newschooldb.php',
		data: dataparam,
		beforeSend: function(){
			$('#divddlcity1').html('<img src="img/loader.gif" width="120"  border="0"/>'); 
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
		url: 'users/schools/users-schools-newschooldb.php',
		data: dataparam,
		beforeSend: function(){
			$('#divddlzip1').html('<img src="img/loader.gif" width="120"  border="0"/>'); 
		},
		success:function(ajaxdata) {
			$('#divddlzip1').html(ajaxdata);
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
		url: 'users/schools/users-schools-newschooldb.php',
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
    fn_chkusercountshl()
	Function to check the user cout for school
----*/
function fn_chkusercountshl(count,strackid){
	
	var distid = $('#ddldist').val();
	actionmsg = "Checking user count";
	var ddllicense = $('#ddllic'+count).val();
	ddllicense = ddllicense.split(',');
	var dataparam = "oper=chkusercountshl&distid="+distid+"&value="+$('#noofusers'+count).val()+"&licenseid="+$('#currentlicense'+count).val()+"&editid="+$('#hidschoolid').val()+"&trackid="+ddllicense[2]+"&strackid="+strackid;		
		$.ajax({
			type: 'post',
			url: "users/schools/users-schools-newschooldb.php",
			data: dataparam,
			async:false,
			beforeSend: function(){
			},
			success:function(ajaxdata) {
				if(trim(ajaxdata)=='false'){
					$('#errorcount'+count).val(1);
					$('#noofusers'+count).parent().css('border','1px solid red');
					$.Zebra_Dialog("Seats exceeds available student seats.", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
					return false;						
				}
				else if(trim(ajaxdata)=='false1'){
					$('#errorcount'+count).val(1);
					$('#noofusers'+count).parent().css('border','1px solid red');
					$.Zebra_Dialog("Seats exceeds available student seats.", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
					return false;
					//Seats must be greater than occupied seats
				}
				else if(trim(ajaxdata)=='false2'){
					$('#errorcount'+count).val(1);
					$('#noofusers'+count).parent().css('border','1px solid red');
					$.Zebra_Dialog("Please enter the number of seats.", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
					return false;
				}
				else{
					$('#errorcount'+count).val(0);
					$('#noofusers'+count).parent().css('border','1px solid #B7B7B7');
				}				
			}
		});		
}
/*----
    fn_createschool()
	Function to save the school deatils
----*/
function fn_createschool(temp,id){
	
	var shlname = $('#shlname').val();
	var address = $('#address').val();
	var distid = $('#ddldist').val();
	var state = $('#ddlstate').val();
	var city = $('#ddlcity').val();
    var zipcode = $('#ddlzip').val();
    var hubid = $('#hubid').val();
		
	var fname = $('#fname').val();
	var lname = $('#lname').val();
	var email = $('#email').val();
	
	var address1 = $('#address1').val();
	var state1 = $('#ddlstate1').val();
	var city1 = $('#ddlcity1').val();
	var zipcode1 = $('#ddlzip1').val();
	var officeno = $('#officeno').val();
	var faxno = $('#faxno').val();
	var mobileno = $('#mobileno').val();
	var homeno = $('#homeno').val();
	var photo = $('#hiduploadfile').val();
	var logo = $('#hiduploadfilelogo').val();
	
	var ddllicense = '';
	var numusers ='';
	var startdate ='';		
	var count =$('#hidaddlicense').val()-1;	
	
	if(id==0){
		var type='saveschool';	
		actionmsg = "Saving";
		alertmsg ="School account is created successfully";
	}
	else{
		var type='updateschool';
		actionmsg = "Updating";
		alertmsg = "School account is updated successfully";
	}
	
	if($("#shlval").validate().form())
	{
		var ddllicense = '';
		var numusers ='';
		var startdate ='';
		var enddate ='';
		var renewal ='';
		var graceipl='';
		var gracemod='';
		var error=0;
		var counterror=0;
		$("div[id^='lic']").each(function() {
			 var flag=0;  
			 var i = $(this).attr('id').substring(3);
			 if($('#checkbox'+i).is(':checked')){
				flag=1;
			 }			 
			 if($('#noofusers' + i).val()==0 || $('#noofusers' + i).val()==''){
				 error=1;
			 }
			 if($('#sdate' + i).val()==0 || $('#sdate' + i).val()==''){
				 error=1;
			 }
			 if($('#errorcount'+i).val()==1){
				 counterror=1;
			 }
			 renewal+=flag+'~';
			 ddllicense+=$('#ddllic' + i).val()+'~';
			 numusers+=$('#noofusers' + i).val()+'~';	
			 startdate+=$('#sdate' + i).val()+'~';	
			 enddate+=$('#edate' + i).val()+'~';
		});	
		if(error==0 && counterror==0){
			var dataparam = "oper="+type+"&shlname="+escapestr(shlname)+"&address="+escapestr(address)+"&distid="+distid+"&state="+state+"&city="+city+"&zipcode="+zipcode+"&fname="+escapestr(fname)+"&lname="+escapestr(lname)+"&email="+email+"&address1="+escapestr(address1)+"&state1="+state1+"&city1="+city1+"&zipcode1="+zipcode1+"&officeno="+officeno+"&faxno="+faxno+"&mobileno="+mobileno+"&homeno="+homeno+"&photo="+photo+"&logo="+logo+"&licensecount="+count+"&ddllicense="+ddllicense+"&numusers="+numusers+"&startdate="+startdate+"&enddate="+enddate+"&tags="+escapestr($('#form_tags_newschool').val())+"&id="+id+"&hubid="+hubid;
			$.ajax({
				type: 'post',
				url: 'users/schools/users-schools-newschooldb.php',
				data: dataparam,
				beforeSend: function(){
					showloadingalert(actionmsg+", please wait.");	
				},
				success:function(data) {
					if(data=="success"){
						$('.lb-content').html(alertmsg);					
						setTimeout('closeloadingalert();',1000);
						if(temp == 0){
							setTimeout("removesections('#users-districtpurchase');",1500);
						}
						if(temp == 1){
							setTimeout("removesections('#users');",1500);
						}
						setTimeout('showpages("users-schools","users/schools/users-schools.php");',1500);
					}
					else if(data=="fail"){
						$('.lb-content').html("Incorrect Data");
						setTimeout('closeloadingalert()',1000);
					}
				}
				
			});
		}
		else{
			if(error==1){
				$.Zebra_Dialog("Please fill all the information about licenses.", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
				return false;
			}
			else if(counterror==1){
				$.Zebra_Dialog("Seats exceeds available student seats.", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
				return false;
			}
		}
	}
}
/*----
    fn_deletschool()
	Function to delet the school deatils
----*/
function fn_deletschool(editid){
	
	var dataparam = "oper=deletschool&editid="+editid;
	actionmsg = "Deleting";
	alertmsg = "School account is deleted successfully";
	
	$.Zebra_Dialog('Are you sure want to delete?',
		{
			'type': 'confirmation',
			'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {	
				
				$.ajax({
					type: 'post',
					url: 'users/schools/users-schools-newschooldb.php',
					data: dataparam,
					beforeSend: function(){
						showloadingalert(actionmsg+", please wait.");	
					},
					success:function(data) {
						if(data=="success"){
							$('.lb-content').html(alertmsg);					
							setTimeout('closeloadingalert();',1000);
							setTimeout("removesections('#users');",1500);
							setTimeout('showpages("users-schools","users/schools/users-schools.php");',1500);
						}
						else if(data=="fail"){
						$('.lb-content').html("Incorrect Data");
						setTimeout('closeloadingalert()',1000);
						}
					}
				});
			}}
		]
	});
}
/*----
    addlicshl()
	Function to the add license
----*/
function addlicshl(did,id){
	id++;	
	var dataparam = "oper=addlicenseshl&count="+id+"&distid="+did;
	$('#hidaddlicense').val(id);	
	$.ajax({
		type: 'post',
		url: 'users/schools/users-schools-newschooldb.php',
		data: dataparam,
		success:function(ajaxdata) {
			ajaxdata = ajaxdata.split('~');
			$('#addlicenseshl').append(ajaxdata[0]);				
				$('#hidtotallicense').val(ajaxdata[1]);			
				$('#add').addClass('dim');
			
			$("div[id^='lic']").each(function() { //remove licenses which is selected previous 
				var i = $(this).attr('id').substring(3);
				if($(this).attr('id')!='licenselist'){
					i = $('#ddllic'+i).val();					
					i=i.split(',');													
					$('#lic' + (id)+' a#option'+i[0]).addClass('dim');
				}
			});	
					
		}
		
	});	
}
/*----
    fn_upgrade()
	Function to be used for add addtional license for Home purchase
----*/
function fn_upgrade(lid,dtrackid,strackid){
	var id=$('#hidaddlicense').val();
	id++;
	var dataparam = "oper=upgradelicense&count="+id+"&lid="+lid+"&dtrackid="+dtrackid+"&strackid="+strackid;
	
	$('#hidaddlicense').val(id);
	$.ajax({
		type: 'post',
		url: 'users/schools/users-schools-newschooldb.php',
		data: dataparam,
		beforeSend: function(){
					showloadingalert('Loading, please wait.');	
				},
		success:function(ajaxdata) {
			closeloadingalert();
			$('#upgrade_'+dtrackid).hide();				
			$('#addlicenseshl').append(ajaxdata);	
		}
	});	
}
/*----
    removeshl()
	Function to remove the school license
----*/
function fn_removeshllicense(count,flag,trackid){
	var currentlicense = $('#currentlicense'+count).val();		
	if(currentlicense!=''){
		$("div[id^='lic']").each(function() {
			var id = $(this).attr('id').substring(3);
			$('#lic' + id+' a#option'+currentlicense).removeClass('dim');			
		});			
	}
	$('#lic'+count).remove();	
	$('#upgrade_'+trackid).show();
	
	if(flag==1){
		var dataparam = "oper=deletelicense&trackid="+trackid+"&sid="+$('#hidschoolid').val();
		$.ajax({
			type: 'post',
			url: 'users/schools/users-schools-newschooldb.php',
			data: dataparam,
			beforeSend: function(){
			},
			success: function(){
				
			}
		});
	}
	$('#add').removeClass('dim');	
}
/*----
    fn_licenseclick()
	Function to add the license
----*/	
function fn_licenseclick(id,ccount,trackid){	
		
	$('#noofusers'+ccount).removeAttr('readonly');	
	$('#noofusers'+ccount).val('');
	$('#sdate'+ccount).val('');
	$('#edate'+ccount).val('');
	
	var currentlicense = $('#currentlicense'+ccount).val();
	$('#currentlicense'+ccount).val(id);	
	$("div[id^='lic']").each(function() {
		var i = $(this).attr('id').substring(3);
		$('#lic' + i+' a#option'+id).addClass('dim');
		$('#lic' + i+' a#option'+currentlicense).removeClass('dim');			
	});	
	
	var ddllicense=[];
	$("div[id^='lic']").each(function() {			   
		 var i = $(this).attr('id').substring(3);			
		 ddllicense.push($('#currentlicense' + i).val());			 							
	});		
	if((jQuery.unique(ddllicense).length)!=$('#hidtotallicense').val())
	$('#add').removeClass('dim');
	
	var cnt=0;	
	$('#lic'+ccount).find('ul').find('li').each(function() {
		if($(this).children().is(':visible'))
		{
			cnt++;
		}
	});
	if(cnt==0)
	$('#add').addClass('dim');
	fn_endate(ccount,trackid);
	var dataparam = "oper=remainusers&trackid="+trackid;
	$.ajax({
		type: 'post',
		url: 'users/schools/users-schools-newschooldb.php',
		data: dataparam,
		beforeSend: function(){
		},
		success: function(data){
			$('#remainusers'+ccount).html(trim(data));
		}
	});
}
/*----
    fn_resets()
	Function to reset password
----*/
function fn_resets(username){
	
	var dataparam = "oper=resets&username="+username;
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
    fn_shllicdet()
	Function to load the school license
----*/
function fn_shllicdet(shllic,shlid){
	
	actionmsg = "License details";
	var dataparam = "oper=shllicdet&shllic="+shllic+"&shlid="+shlid;
	$.ajax({
		type: 'post',
		url: 'users/schools/users-schools-newschooldb.php',
		data: dataparam,
		beforeSend: function(){
			showloadingalert(actionmsg+", please wait.");	
		},
		success:function(data) {
			
			closeloadingalert();
			$('#shllicdetails').show();
			$('#shllicdetails').html(data);
		}
		
	});	
}
/*----
    fn_clossplic()
	Function to close the school license
----*/
function fn_clossplic(){
	$('#shllicdetails').hide();
}
/*----
    fn_endate()
	Function used to claculate end date
----*/
function fn_endate(count,trackid){
	var dataparam = "oper=endtade&sdate="+$('#sdate'+count).val()+"&licenseid="+$('#currentlicense'+count).val()+"&trackid="+trackid;
	$.ajax({
		type: 'post',
		url: 'users/schools/users-schools-newschooldb.php',
		data: dataparam,
		success:function(ajaxdata) {
			ajaxdata=ajaxdata.split('~');
			$('#edate'+count).val(ajaxdata[1]);
			$('#sdate'+count).val(ajaxdata[0]);
		}
		
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
	
	var dataparam = "oper=saveschoolmail&mailid="+mailid;
	$.ajax({
			type: 'post',
			url: 'users/schools/users-schools-newschooldb.php',
			data: dataparam,
			beforeSend: function(){
				showloadingalert(actionmsg+", please wait.");	
			},
			success:function(data) {
				$('.lb-content').html(alertmsg);
 				setTimeout('closeloadingalert();',1000);				
			}
			
		});
}
