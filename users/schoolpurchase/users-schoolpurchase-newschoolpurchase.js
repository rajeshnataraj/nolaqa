 
/*----
    fn_changecity()
	Function to show the city drop down
----*/
function fn_changecity(statevalue){
	var dataparam = "oper=changecity&statevalue="+statevalue;
	$.ajax({
		type: 'post',
		url: 'users/schoolpurchase/users-schoolpurchase-newschoolpurchasedb.php',
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
		url: 'users/schoolpurchase/users-schoolpurchase-newschoolpurchasedb.php',
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
		url: 'users/schoolpurchase/users-schoolpurchase-newschoolpurchasedb.php',
		data: dataparam,
		beforeSend: function(){
			$('#divddlcity1').html('<img src="img/loader.gif" width="120"  border="0" />');
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
		url: 'users/schoolpurchase/users-schoolpurchase-newschoolpurchasedb.php',
		data: dataparam,
		beforeSend: function(){
			$('#divddlzip1').html('<img src="img/loader.gif" width="120"  border="0" />');
		},
		success:function(ajaxdata) {
			$('#divddlzip1').html(ajaxdata);
		}
		
	});	
}
/*----
    fn_loaddistrict()
	Function to show the distrcit drop down
----*/
function fn_loaddistrict(cityname){
	var dataparam = "oper=changedistrict&cityname="+cityname+"&statevalue="+$('#ddlstate').val();
	$.ajax({
		type: 'post',
		url: 'users/schoolpurchase/users-schoolpurchase-newschoolpurchasedb.php',
		data: dataparam,
		beforeSend: function(){
			$('#ddldist').html('<img src="img/loader.gif" width="120"  border="0" />'); 
		},
		success:function(ajaxdata) {
			$('#divddldist').html(ajaxdata);
			if($("#dit").attr('class')=='field row error')
				$('#ddldist').valid();
		}
	});	
}
/*----
    fn_createschoolpurchase()
	Function to save the schoolpurchase details
----*/
function fn_createschoolpurchase(id){
	
	var shlname = $('#shlname').val();
	var address = $('#address').val();
	var distid = $('#ddldist').val();
	var state = $('#ddlstate').val();
	var city = $('#ddlcity').val();
	var zipcode = $('#ddlzip').val();
		
	var fname = $('#fname').val();
	var lname = $('#lname').val();
	var email = $('#email').val();
    var hubid = $('#hubid').val();
	
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
	if(id==0){	
		actionmsg = "Saving";
		alertmsg = "School purchase account is created successfully";
	}
	else{
		actionmsg = "Updating";
		alertmsg = "School purchase account is updated successfully";
	}
	
	var count =$('#hidaddlicense').val();
	if($("#shlp").validate().form())
	{
		var ddllicense = '';
		var numusers ='';
		var startdate ='';
		var enddate ='';
		var renewal ='';
		var graceipl='';
		var gracemod='';
		var rcount = '';
		var error=0;	
		var counterror=0;
		$("div[id^='lic']").each(function() {
			 var flag=0;  
			 var i = $(this).attr('id').substring(3);
			 if($('#checkbox'+i).is(':checked')){
				flag=1;
			 }
			 if($('#iplcount'+i).is(':visible')){
				 if($('#iplcount'+i).val()==0 || $('#iplcount'+i).val()==''){
					 error=1;
				 }
			 }
			 if($('#modcount'+i).is(':visible')){
				 if($('#modcount'+i).val()==0 || $('#modcount'+i).val()==''){
					 error=1;
				 }
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
			 graceipl+=$('#iplcount' + i).val()+'~';	
			 gracemod+=$('#modcount' + i).val()+'~';
			 rcount+=$('#renewalcount_' + i).val()+'~';									
		});				
		if(error==0 && counterror==0){
			var dataparam = "oper=saveschoolpurchase&shlname="+escapestr(shlname)+"&address="+escapestr(address)+"&state="+state+"&city="+city+"&zipcode="+zipcode+"&fname="+escapestr(fname)+"&lname="+escapestr(lname)+"&email="+email+"&address1="+escapestr(address1)+"&state1="+state1+"&city1="+city1+"&zipcode1="+zipcode1+"&officeno="+officeno+"&faxno="+faxno+"&mobileno="+mobileno+"&homeno="+homeno+"&photo="+photo+"&logo="+logo+"&licensecount="+count+"&ddllicense="+ddllicense+"&numusers="+numusers+"&startdate="+startdate+"&enddate="+enddate+"&tags="+escapestr($('#form_tags_newschoolp').val())+"&graceipl="+graceipl+"&gracemod="+gracemod+"&renewal="+renewal+"&id="+id+"&rcount="+rcount+"&hubid="+hubid;
			$.ajax({
				type: 'post',
				url: 'users/schoolpurchase/users-schoolpurchase-newschoolpurchasedb.php',
				data: dataparam,
				beforeSend: function(){
					showloadingalert(actionmsg+", please wait.");	
				},
				success:function(data) {
					if(data=="success"){
						$('.lb-content').html(alertmsg);					
						setTimeout('closeloadingalert();',1000);
						setTimeout("removesections('#users');",1500);
						setTimeout('showpages("users-schoolpurchase","users/schoolpurchase/users-schoolpurchase.php");',1500);
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
    fn_deletschoolpurchase()
	Function to delet the school
----*/
function fn_deletschoolpurchase(editid){
	
	var dataparam = "oper=deletschoolpurchase&editid="+editid;
	
	actionmsg = "Deleting";
	alertmsg = "School purchase account is deleted successfully";
	
	$.Zebra_Dialog('Are you sure you want to delete?',
		{
			'type': 'confirmation',
			'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {	
			
				$.ajax({
					type: 'post',
					url: 'users/schoolpurchase/users-schoolpurchase-newschoolpurchasedb.php',
					data: dataparam,
					beforeSend: function(){
						showloadingalert(actionmsg+", please wait.");	
					},
					success:function(data) {
						if(data=="success"){
							$('.lb-content').html(alertmsg);					
							setTimeout('closeloadingalert();',1000);
							setTimeout("removesections('#users');",1500);
							setTimeout('showpages("users-schoolpurchase","users/schoolpurchase/users-schoolpurchase.php");',1500);
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
    addlicdist()
	Function to add the disrtict license
----*/
function addlicsp(id){
	var ddllicense='';
	$("div[id^='lic']").each(function() {
		var i = $(this).attr('id').substring(3);
		ddllicense+=$('#ddllic' + i).val()+'~';						
	});	
	id++;	
	$('#hidaddlicense').val(id);		
	var dataparam = "oper=addlicensesp&count="+id+"&licenseid="+ddllicense;
	$.ajax({
		type: 'post',
		url: 'users/schoolpurchase/users-schoolpurchase-newschoolpurchasedb.php',
		data: dataparam,
		success:function(ajaxdata) {
			ajaxdata = ajaxdata.split('~');
			$('#addlicensehomeshl').append(ajaxdata[0]);				
							
				$('#add').addClass('dim');
			
			$("div[id^='lic']").each(function() { //remove licenses which is selected previous 
				var i = $(this).attr('id').substring(3);
				var tempi = $('#ddllic'+i).val();					
				var j = tempi.split(',');				
				$('#lic' + id+' a#option'+j[0]).addClass('dim');										
			});			
		}
		
	});	
}

/*----
    fn_upgrade()
	Function to be used for add addtional license for particular school or district 
----*/
function fn_upgrade(lid,trackid){
	var id=$('#hidaddlicense').val();
	id++;
	var dataparam = "oper=upgradelicense&count="+id+"&lid="+lid+"&trackid="+trackid;
	
	$('#hidaddlicense').val(id);
	$.ajax({
		type: 'post',
		url: 'users/schoolpurchase/users-schoolpurchase-newschoolpurchasedb.php',
		data: dataparam,
		beforeSend: function(){
					showloadingalert('Loading, please wait.');	
				},
		success:function(ajaxdata) {
			closeloadingalert();
			$('#upgrade_'+trackid).hide();				
			$('#addlicensehomeshl').append(ajaxdata);	
		}
	});	
}

function fn_licenseclicksp(id,ccount){
	$( "#sdate"+ccount).datepicker({
		onSelect: function(dateText,inst){	
			fn_endate(ccount);
		}
	});
	$('#noofusers'+ccount).removeAttr('readonly');	
	$('#sdate'+ccount).val('');
	$('#edate'+ccount).val('');
	
	var currentlicense = $('#currentlicense'+ccount).val();
	$('#currentlicense'+ccount).val(id);	
	$("div[id^='lic']").each(function() {
		var i = $(this).attr('id').substring(3);
		$('#lic' + i+' a#option'+id).addClass('dim');
		$('#lic' + i+' a#option'+currentlicense).removeClass('dim');		
	});	
	
	var dataparam = "oper=loadgrace&lid="+id+"&count="+ccount;
	$.ajax({
		type: 'post',
		url: 'users/schoolpurchase/users-schoolpurchase-newschoolpurchasedb.php',
		data: dataparam,
		beforeSend: function(){				
		},
		success:function(data) {
			$('#grace'+ccount).html(data);
		}
		
	});		
	
	var ddllicense=[];
	$("div[id^='lic']").each(function() {			   
		 var i = $(this).attr('id').substring(3);			
		 ddllicense.push($('#currentlicense' + i).val());			 							
	});		
	if((jQuery.unique(ddllicense).length)!=$('#hidtotallicense').val())
	$('#add').removeClass('dim');
}
/*----
    fn_clickrenewal()
	Function to renewal the schoolpurchase license
----*/
function fn_clickrenewal(count,trackid){
	if($('#checkbox'+count).is(':checked')){
		$('#upgrade_'+trackid).show();
	}
	else{
		$('#upgrade_'+trackid).hide();
	}
}
/*----
    fn_renewalcount()
	Function to add the license checkbox
----*/
function fn_renewalcount(count){
	if($('#checkbox'+count).is(':checked')){
		$('#rcountdiv_'+count).hide();
	}
	else{
		$('#rcountdiv_'+count).show();
	}
}
/*----
    fn_removesplicense()
	Function to remove license 
----*/
function fn_removesplicense(count,flag,trackid){
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
		var dataparam = "oper=deletelicense&trackid="+trackid+"&spid="+$('#hidspid').val();
		$.ajax({
			type: 'post',
			url: 'users/schoolpurchase/users-schoolpurchase-newschoolpurchasedb.php',
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
    fn_resetsp()
	Function to reset password
----*/
function fn_resetsp(username){
	
	var dataparam = "oper=resetsp&username="+username;
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
    fn_splicdet()
	Function to load the school purchase license
----*/
function fn_splicdet(splic,spid){
	
	actionmsg = "License details";
	var dataparam = "oper=splicdet&splic="+splic+"&spid="+spid;
	$.ajax({
		type: 'post',
		url: 'users/schoolpurchase/users-schoolpurchase-newschoolpurchasedb.php',
		data: dataparam,
		beforeSend: function(){
			showloadingalert(actionmsg+", please wait.");	
		},
		success:function(data) {
			
			closeloadingalert();
			$('#splicdetails').show();
			$('#splicdetails').html(data);
		}
		
	});	
}
/*----
    fn_clossplic()
	Function to close the school purchase license
----*/
function fn_clossplic(){
	$('#splicdetails').hide();
}

/*----
    fn_chkusercount()
	Function to be used for check user count for distict,school and individual
----*/
function fn_chkusercount(count,trackid){	
	var dataparam = "oper=chkusercount&value="+$('#noofusers'+count).val()+"&licenseid="+$('#currentlicense'+count).val()+"&trackid="+trackid;
		$.ajax({
			type: 'post',
			url: 'users/schoolpurchase/users-schoolpurchase-newschoolpurchasedb.php',
			data: dataparam,
			async:false,
			beforeSend: function(){
			},
			success:function(ajaxdata) {
				closeloadingalert();
				if(trim(ajaxdata)=='false'){
					$('#errorcount'+count).val(1);
					$('#noofusers'+count).parent().css('border','1px solid red');
					$.Zebra_Dialog("Seats must be greater than occupied seats.", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
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
    fn_endate()
	Function used to claculate end date
----*/
function fn_endate(count){	
	var dataparam = "oper=endtade&sdate="+$('#sdate'+count).val()+"&licenseid="+$('#currentlicense'+count).val();
	//alert(dataparam);
	$.ajax({
		type: 'post',
		url: 'users/schoolpurchase/users-schoolpurchase-newschoolpurchasedb.php',
		data: dataparam,
		success:function(ajaxdata) {						
			$('#edate'+count).val(ajaxdata);
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
	
	var dataparam = "oper=resendteachermail&mailid="+mailid;
	$.ajax({
			type: 'post',
			url: 'users/schoolpurchase/users-schoolpurchase-newschoolpurchasedb.php',
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
