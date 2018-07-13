/*----
    fn_updatemyinfo()
	Function to update the personal details
----*/
function fn_updatemyinfo(userid)
{ 
	var frmvalid = false;
	frmvalid =  $("#myinfoform").validate().form();
	
	if($("#myinfoform").validate().form())
	{
		dataparam = "oper=update&fname="+escapestr($('#txtfirstname').val())+"&lname="+escapestr($('#txtlastname').val())+"&email="+$("#txtemail").val()+"&username="+escapestr($('#txtusername').val())+"&password="+$('#txtconfirmpassword').val()+"&office="+$('#txtofficeno').val()+"&fax="+$('#txtfaxno').val()+"&mobile="+$('#txtmobileno').val()+"&home="+$('#txthomeno').val()+"&address="+escapestr($('#txtaddress').val())+"&state="+$('#ddlstate').val()+"&city="+$('#ddlcity').val()+"&zipcode="+$('#ddlzip').val()+"&hidimage="+$('#hiduploadfile').val()+"&uid="+userid;
		
		$.ajax({
			type: "POST",
			url: "tools/myinfo/tools-myinfo-ajax.php",
			data: dataparam,
			beforeSend:function(){
				showloadingalert('Updating, your details please wait.');
			},
			success: function(data){
				if(data == "success")
				{
					$('.lb-content').html('Details is updated successfully.');
					setTimeout('closeloadingalert()',2000);
					setTimeout("removesections('#tools');",1500);
					setTimeout('showpages("tools-myinfo","tools/myinfo/tools-myinfo.php");',1500);
				}
			}
		});
	}
}
/*----
    fn_changecity()
	Function to show the city drop down
----*/
function fn_changecity(statevalue){
	var dataparam = "oper=changecity&statevalue="+statevalue;
	$.ajax({
		type: 'post',
		url: 'tools/myinfo/tools-myinfo-ajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#divddlcity').html('<img src="img/loader.gif" width="200"  border="0" />'); 
		},
		success:function(ajaxdata) {
			$('#divddlcity').html(ajaxdata);
			if($("#cit").attr('class')=='field row error')
				$('#txtcity').valid();
		}
		
	});	
}
/*----
    fn_changezip()
	Function to show the zip1 drop down
----*/
function fn_changezip(cityvalue){
	
	var dataparam = "oper=changezip&cityvalue="+cityvalue;
	$.ajax({
		type: 'post',
		url: 'tools/myinfo/tools-myinfo-ajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#divddlzip').html('<img src="img/loader.gif" width="200"  border="0" />'); 
		},
		success:function(ajaxdata) {
			$('#divddlzip').html(ajaxdata);
			if($("#zip").attr('class')=='field row error')
			$('#txtzipcode').valid();
		}
		
	});	
}
