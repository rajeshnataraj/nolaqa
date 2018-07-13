/*----
    fn_importstudents()
	Function to shoe the import students page
----*/
function fn_importstudents(path){
	var actionmsg ="Saving Students";
	var shl=$('#hidshl').val();
        var classid = $("#selectclass").val();
	if($('#chkboxuser').attr('checked')) 
	{
    	var chkboxuser = 'checked';
	}
	if($('#chkboxpass').attr('checked')) 
	{
    	var chkboxpass = 'checked';
	}

	dataparam="oper=importstudents&path="+path+"&classid="+classid+"&chkboxuser="+chkboxuser+"&chkboxpass="+chkboxpass;
	$.ajax({
		type: 'post',
		url:'users/individuals/users-individuals-importstudentsdb.php',
		data: dataparam,
		beforeSend: function(){
			showloadingalert(actionmsg+", please wait.");	
		},
		success:function(ajaxdata){

			closeloadingalert();
			$.Zebra_Dialog('The spreadsheet has been successfully uploaded and the student accounts imported!',
			{
				'type': 'confirmation',

			});			
			

			$('#duplicate').html('');
			$('#duplicate').html(ajaxdata);
			
			if($.trim($('#duplicate').text()) === "All students are added successfully") {
	
				$('#searchclass').html('Select Class');
				$("#selectclass").val('');

		}

		}

	});

}
/*----
    fn_duplicaterecord()
	Function to show the duplicate record List
----*/

function fn_duplicaterecord(path,id)
{
	dataparam="oper=duplicate&path="+path+"&duplicateid="+id;
	$.ajax({
		type: 'post',
		url:'users/individuals/users-individuals-importstudentsdb.php',
		data: dataparam,
		success:function(ajaxdata){
			$('#duplicate').html(ajaxdata);
		}
	});
}
/*
edit the username to add into itc_user_master table
*/
function fn_edituser(cntval,fname,lname,uname,pwd) {

	if(fname != $('#chkfname'+cntval).val())
	{
             $('#chkfname'+cntval).val(fname);
	} 
	if(lname != $('#chklname'+cntval).val())
	{
             $('#chklname'+cntval).val(lname);
	}  
	if(uname != $('#chkusername'+cntval).val())
	{
             $('#chkusername'+cntval).val(uname);
	} 
	if(pwd != $('#chkpwd'+cntval).val())
	{
             $('#chkpwd'+cntval).val(pwd);
	} 


}

function fn_addusername(cntval) {

	var currentfname = $('#chkfname'+cntval).val();
	var currentlname = $('#chklname'+cntval).val();
 	var currentusername = $('#chkusername'+cntval).val();
	var currentpwd = $('#chkpwd'+cntval).val();
	var classid = $("#selectclass").val();	
	var excflag=0;

	dataparam="oper=addusername&fname="+currentfname+"&lname="+currentlname+"&uname="+currentusername+"&password="+currentpwd+"&cntval="+cntval+"&classid="+classid;

		$.ajax({
			type: 'post',
			url:'users/individuals/users-individuals-importstudentsdb.php',
			data: dataparam,
			success:function(ajaxdata){
			    var response=trim(ajaxdata);
			    var data=response.split('~');
				if(data[0] == "success") {

					showloadingalert("Updating please wait.");
		
					$('table#mytable tr#deleterow'+data[1]).remove();
					$("tr[id^=deleterow]").each(function()
					{
					   excflag=1;
					});

					if(excflag==0)
					{
					   $('#mytable').append('<tr id="empty_tr"><td colspan="7" class="noMouse" style="cursor:default;">No Records</td></tr>');
					   $('#searchclass').html('Select Class');
					   $("#selectclass").val('');
					}
				} else if(data[0] == "empty") {

				showloadingalert(data[1]);

				}
				else {
				
				showloadingalert(data[1]);

				}
 setTimeout('closeloadingalert()',1000);

			}
		});

}
/***** click download link (Show alert msg and excel sheet downlod code start line) *****/
function fn_link()
{
	if($('#chkboxuser').attr('checked')) 
	{
    	var chkboxuser = 'checked';
	}
	if($('#chkboxpass').attr('checked')) 
	{
    	var chkboxpass = 'checked';
	}
	
	if(chkboxuser == 'checked' && chkboxpass == 'checked')
	{
		$.Zebra_Dialog('The download of the spreadsheet is about to start.<br><br>You have selected:<br>- Auto-generate username<br>- Auto-generate password<br><br>Although you notice that the spreadsheet includes the columns mentioned above, you can leave them empty and the system will generate them automatically',
		{
			'type': 'confirmation',
			'buttons': [
			{caption: 'Ok', callback: function() {
				 window.location.href= ITC_URL + '/import_students.xls';
			}}
			]
		});
	}
	else if(chkboxuser == 'checked')
	{
		$.Zebra_Dialog('The download of the spreadsheet is about to start.<br><br>You have selected:<br>- Auto-generate username<br><br>Although you notice that the spreadsheet includes the columns mentioned above, you can leave them empty and the system will generate them automatically',
		{
			'type': 'confirmation',
			'buttons': [
			{caption: 'Ok', callback: function() {
				 window.location.href= ITC_URL + '/import_students.xls';
			}}
			]
		});
	}
	else if(chkboxpass == 'checked')
	{
		$.Zebra_Dialog('The download of the spreadsheet is about to start.<br><br>You have selected:<br>- Auto-generate password<br><br>Although you notice that the spreadsheet includes the columns mentioned above, you can leave them empty and the system will generate them automatically',
		{
			'type': 'confirmation',
			'buttons': [
			{caption: 'Ok', callback: function() {
				 window.location.href= ITC_URL + '/import_students.xls';
			}}
			]
		});
	}
	else 
	{
		$.Zebra_Dialog('The download of the spreadsheet is about to start.',
		{
			'type': 'confirmation',
			'buttons': [
			{caption: 'Ok', callback: function() {
				 window.location.href= ITC_URL + '/import_students.xls';
			}}
			]
		});
	}
	
}
/***** Show alert msg and excel sheet downlod code end line *****/
