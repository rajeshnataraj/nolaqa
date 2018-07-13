 
/*----
    fn_changecity()
	Function to show the city drop down
----*/
function fn_changecity(statevalue){
	
	var dataparam = "oper=changecity&statevalue="+statevalue;
	$.ajax({
		type: 'post',
		url: 'users/districts/users-districts-newdistrictdb.php',
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
	$('#addzipspan').show();
	var dataparam = "oper=changezip&cityvalue="+cityvalue+"&statevalue="+$('#ddlstate').val();
	$.ajax({
		type: 'post',
		url: 'users/districts/users-districts-newdistrictdb.php',
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
		url: 'users/districts/users-districts-newdistrictdb.php',
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
    fn_changezip()
	Function to show the zip drop down
----*/
function fn_changezip1(cityvalue){
	var dataparam = "oper=changezip1&cityvalue="+cityvalue+"&statevalue="+$('#ddlstate1').val();
	$.ajax({
		type: 'post',
		url: 'users/districts/users-districts-newdistrictdb.php',
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
    addlicdist()
	Function to add the disrtict license
----*/
function addlicdist(id){
	var ddllicense='';
	$("div[id^='lic']").each(function() {
		var i = $(this).attr('id').substring(3);
		ddllicense+=$('#ddllic' + i).val()+'~';						
	});	
	id++;	
	$('#hidaddlicense').val(id);		
	var dataparam = "oper=addlicensedist&count="+id+"&licenseid="+ddllicense;
	$.ajax({
		type: 'post',
		url: 'users/districts/users-districts-newdistrictdb.php',
		data: dataparam,
		success:function(ajaxdata) {
			ajaxdata = ajaxdata.split('~');
			$('#addlicensedist').append(ajaxdata[0]);				
							
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
    fn_removedistlicense()
	Function to remove the disrtict license
----*/
function fn_removedistlicense(count,flag,trackid){
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
		var dataparam = "oper=deletelicense&trackid="+trackid+"&distid="+$('#hiddistid').val();
		$.ajax({
			type: 'post',
			url: 'users/districts/users-districts-newdistrictdb.php',
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
		url: 'users/districts/users-districts-newdistrictdb.php',
		data: dataparam,
		beforeSend: function(){
					showloadingalert('Loading, please wait.');	
				},
		success:function(ajaxdata) {
			closeloadingalert();
			$('#upgrade_'+trackid).hide();				
			$('#addlicensedist').append(ajaxdata);	
		}
	});	
}

function fn_licenseclick(id,ccount){
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
		url: 'users/districts/users-districts-newdistrictdb.php',
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
	Function to renewal the disrtict license
----*/
function fn_clickrenewal(count,trackid){
	if($('#checkbox'+count).is(':checked')){
		$('#upgrade_'+trackid).show();
	}
	else{
		$('#upgrade_'+trackid).hide();
	}
}

function fn_renewalcount(count){
	if($('#checkbox'+count).is(':checked')){
		$('#rcountdiv_'+count).hide();
	}
	else{
		$('#rcountdiv_'+count).show();
	}
}


/*----
    fn_createdistrict()
	Function to Save disrtict details
----*/
function fn_createdistrict(id){
	var distname = $('#distname').val();
	var address = $('#address').val();
	var state = $('#ddlstate').val();
	var city = $('#ddlcity').val();
	var zipcode = $('#ddlzip').val();
		
	var fname = $('#fname').val();
	var lname = $('#lname').val();
	var email = $('#email').val();
	var photo = $('#hiduploadfile').val();
    var hubid = $('#hubid').val();
	
	var address1 = $('#address1').val();
	var state1 = $('#ddlstate1').val();
	var city1 = $('#ddlcity1').val();
	var zipcode1 = $('#ddlzip1').val();
	var officeno = $('#officeno').val();
	var faxno = $('#faxno').val();
	var mobileno = $('#mobileno').val();
	var homeno = $('#homeno').val();
	if(id==0){	
		actionmsg = "Saving";
		alertmsg = "District account is created successfully";
	}
	else{
		actionmsg = "Updating";
		alertmsg = "District account is updated successfully";
	}
	
	var count =$('#hidaddlicense').val();
	if($("#distval").validate().form())
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
		var rcount = '';
		
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
			var dataparam = "oper=savedistrict&distname="+escapestr(distname)+"&address="+escapestr(address)+"&state="+state+"&city="+city+"&zipcode="+zipcode+"&fname="+escapestr(fname)+"&lname="+escapestr(lname)+"&email="+email+"&address1="+escapestr(address1)+"&state1="+state1+"&city1="+city1+"&zipcode1="+zipcode1+"&officeno="+officeno+"&faxno="+faxno+"&mobileno="+mobileno+"&homeno="+homeno+"&photo="+photo+"&licensecount="+count+"&ddllicense="+ddllicense+"&numusers="+numusers+"&startdate="+startdate+"&enddate="+enddate+"&tags="+escapestr($('#form_tags_newdist').val())+"&graceipl="+graceipl+"&gracemod="+gracemod+"&renewal="+renewal+"&id="+id+"&rcount="+rcount+"&hubid="+hubid;
			$.ajax({
				type: 'post',
				url: 'users/districts/users-districts-newdistrictdb.php',
				data: dataparam,
				beforeSend: function(){
					showloadingalert(actionmsg+", please wait.");	
				},
				success:function(data) {
					if(data=="success"){
						$('.lb-content').html(alertmsg);
						setTimeout('closeloadingalert();',1000);					
						setTimeout("removesections('#users-districtpurchase');",1500);
						setTimeout('showpages("users-districts","users/districts/users-districts.php");',1500);
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
    fn_endate()
	Function used to claculate end date
----*/
function fn_endate(count){	
	var dataparam = "oper=endtade&sdate="+$('#sdate'+count).val()+"&licenseid="+$('#currentlicense'+count).val();
	$.ajax({
		type: 'post',
		url: 'users/districts/users-districts-newdistrictdb.php',
		data: dataparam,
		success:function(ajaxdata) {						
			$('#edate'+count).val(ajaxdata);
		}
		
	});	
}
/*----
    fn_deletdistrict()
	Function to delet disrtict details
----*/
function fn_deletdistrict(editid){
	
	var dataparam = "oper=deletdistrict&editid="+editid;
	actionmsg = "Deleting";
	alertmsg = "District account is deleted successfully";
	
	$.Zebra_Dialog('Are you sure you want to delete?',
		{
			'type': 'confirmation',
			'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {	
				
				$.ajax({
					type: 'post',
					url: 'users/districts/users-districts-newdistrictdb.php',
					data: dataparam,
					beforeSend: function(){
						showloadingalert(actionmsg+", please wait.");	
					},
					success:function(data) {
						if(data=="success"){
							$('.lb-content').html(alertmsg);
							setTimeout('closeloadingalert();',1000);
							setTimeout("removesections('#users');",1500);
							setTimeout('showpages("users-districts","users/districts/users-districts.php");',1500);
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
    trim()
	Function to check the unwanted data
----*/ 
function trim(stringToTrim) 
{
	return stringToTrim.replace(/^\s+|\s+$/g,"");
}
/*----
    fn_chkusercountdist()
	Function to check the uer count for the district
----*/ 
function fn_chkusercountdist(count,trackid){	
	var dataparam = "oper=chkusercountdist&value="+$('#noofusers'+count).val()+"&licenseid="+$('#currentlicense'+count).val()+"&trackid="+trackid;
		$.ajax({
			type: 'post',
			url: 'users/districts/users-districts-newdistrictdb.php',
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
    fn_resetd()
	Function to reset password
----*/
function fn_resetd(username){
	var dataparam = "oper=resetd&username="+username;
    actionmsg = "Generating Reset Link";
    alertmsg = "A password reset link has been sent to the e-mail address associated with this account.";

    $.Zebra_Dialog('Are you sure you want to reset the password?',
        {
            'type': 'confirmation',
            'buttons': [
                {caption: 'No', callback: function() { }},
                {caption: 'Yes', callback: function() {

                    $.ajax({
                        type: 'POST',
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
    fn_distlicdet()
	Function to load the district license
----*/
function fn_distlicdet(distlic,cdistid){
	
	actionmsg = "License details";
	var dataparam = "oper=distlicdetails&cdistid="+cdistid+"&distlic="+distlic;
	$.ajax({
		type: 'post',
		url: 'users/districts/users-districts-newdistrictdb.php',
		data: dataparam,
		beforeSend: function(){
			showloadingalert("Loading, please wait.");	
		},
		success:function(data) {
			closeloadingalert();
			$('#shldetails').hide();
			$('#distlicdetails').show();
			$('#distlicdetails').html(data);
		}
		
	});	
}
/*----
    fn_loadshldetails()
	Function to load the school details
----*/
function fn_loadshldetails(lodshilid){
	
	actionmsg = "School details Loading";
	var dataparam = "oper=loadshldetails&lodshilid="+lodshilid;
	$.ajax({
		type: 'post',
		url: 'users/districts/users-districts-newdistrictdb.php',
		data: dataparam,
		beforeSend: function(){
			showloadingalert(actionmsg+", please wait.");	
		},
		success:function(data) {
			closeloadingalert();
			$('#distlicdetails').hide();
			$('#shldetails').show();
			$('#shldetails').html(data);
		}
		
	});	
}
/*----
    fn_closeshl()
	Function to close school details
----*/
function fn_closeshl(){
	$('#shldetails').hide();
}
/*----
    fn_closdistlic()
	Function to close the district license
----*/
function fn_closdistlic(){
	$('#distlicdetails').hide();
}

/*----
    fn_resendmail()
	Function to resend mail
----*/
function fn_resendmail(mailid)
{
	actionmsg = "Mail Sending";
	alertmsg = "Mail Send successfully.";
	
	var dataparam = "oper=savedistrictmail&mailid="+mailid;
	$.ajax({
			type: 'post',
			url: 'users/districts/users-districts-newdistrictdb.php',
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


function fn_addnewzip()
{
	$.fancybox.showActivity();
	var dataparam = "oper=addnewzipform&state="+$('#ddlstate').val()+"&city="+$('#ddlcity').val();
	$.ajax({
		type: 'post',
		url: 'users/districts/users-districts-newdistrictdb.php',
		data: dataparam,
		success: function(data) {
			$.fancybox(data,{'modal': true,'autoDimensions':false,'height':200});
		}
	});	
	return false;	
}

function fn_savenewzip()
{
	if($("#newzipform").validate().form())
	{
		var dataparam = "oper=savenewzip&state="+$('#ddlstate').val()+"&city="+$('#ddlcity').val()+"&zipcode="+$('#newzip').val();
		$.ajax({
				type: 'POST',
				url: 'users/districts/users-districts-newdistrictdb.php',
				data: dataparam,
				beforeSend: function(){
				},
				success:function(data) {
					if(trim(data)=='success')	{			
						$.fancybox.close();
						fn_changezip($('#ddlcity').val());
					}
				}
				
			});
	}
}