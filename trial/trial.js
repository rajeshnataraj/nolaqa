// JavaScript Document
function fn_submit()
{
	actionmsg = "Loading";
	alertmsg = "Account created successfully and user details sent to your email";
	if($("#newuserform").validate().form()){
		var dataparam="oper=createtrialuser&fname="+escapestr($('#fname').val())+"&lname="+escapestr($('#lname').val())+"&email="+$('#email').val()+"&state="+$('#ddlstate').val()+"&city="+$('#ddlcity').val()+"&zip="+$('#zip').val()+"&saddress="+escapestr($('#saddress').val())+"&pnumber="+$('#pnumber').val()+"&licenseid="+$('#licenseid').val()+"&district="+$('#district').val()+"&school="+$('#school').val()+"&title="+$('#title').val();	
		$.ajax({
			type: 'post',
			url: 'trial-ajax.php',
			data: dataparam,	
			beforeSend: function(){
				showloadingalert(actionmsg+", please wait.");	
			},	
			success:function(ajaxdata) {		
				$(".lb-content").html(alertmsg);	
				closeloadingalert();
				var id=ajaxdata.split("~");	
				window.location="../index.php";
			}
		});		
	}
}

function fn_changecity(statevalue){	
	var dataparam = "oper=changecity&statevalue="+statevalue;
	$.ajax({
		type: 'post',
		url: 'trial-ajax.php',
		data: dataparam,
		beforeSend: function(){
		},
		success:function(ajaxdata, textStatus) {
			$('#divddlcity').html(ajaxdata);
		}
	});	
}

var lightbox_overlay = '<section class="black-overlay"><div class="lb-content"></div></section>'; // Container for the lightbox

/* 
Function to load the lightbox
alertmessage - message to be shown in the lightbox	
*/
function showloadingalert(alertmessage){
	var cssObjOuter = {
	  'width' : $(window).width(),
	  'height' : $(window).height()
	};
	var cssObjInner = {
	  'margin-left' : $(window).width() * .40,
	  'margin-top' : $(window).height() * .2
	};
			
	$('body').append(lightbox_overlay);
	$(".black-overlay").css(cssObjOuter);
	
	$(".lb-content").css(cssObjInner);
	$(".lb-content").html(alertmessage);
}

/* 
Function to close the lightbox
*/
function closeloadingalert(){
	setTimeout('$(".lb-content").remove();$(".black-overlay").remove();',500);	
}
