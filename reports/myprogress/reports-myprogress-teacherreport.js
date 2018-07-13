/*----
    fn_showstudent()
	Function to Load the Student Dropdown
	id -> Classid
----*/
function fn_showschedule(classid)
{
	$("#reports-pdfviewer").hide("fade").remove();
	$('#viewreportdiv').hide();
	$('#studentdiv').hide();		
      $('#rotationdiv').hide();	
	var dataparam = "oper=showschedule&classid="+classid;
	$.ajax({
		type: 'post',
		url: 'reports/myprogress/reports-myprogress-teacherreportajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#schedulediv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {
			$('#schedulediv').show();		
			$('#schedulediv').html(data);//Used to load the student details in the dropdown
		}
	});
}


function fn_showstudent(shuid,schtype)
{
	$("#reports-pdfviewer").hide("fade").remove();
    $('#rotationdiv').hide();	
	$('#viewreportdiv').hide();
	var dataparam = "oper=showstudent&shudid="+shuid+"&schtype="+schtype;
	$.ajax({
		type: 'post',
		url: 'reports/myprogress/reports-myprogress-teacherreportajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#studentdiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {
			$('#studentdiv').show();		
			$('#studentdiv').html(data);//Used to load the student details in the dropdown
		}
	});
}

/***********Show Rotation Developed By MOhan M 1-2-2016****************/

function fn_showrotation(schid,schtype)
{
	$("#reports-pdfviewer").hide("fade").remove();
	$('#viewreportdiv').hide();
    var studentid=$('#hidstudid').val();
	var dataparam = "oper=showrotation&schudid="+schid+"&schtype="+schtype+"&studentid="+studentid;
	$.ajax({
		type: 'post',
		url: 'reports/myprogress/reports-myprogress-teacherreportajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#rotationdiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {
           
			$('#rotationdiv').show();		
			$('#rotationdiv').html(data);//Used to load the student details in the dropdown           
		}
	});
}

/***********Show Rotation Developed By MOhan M 1-2-2016****************/

function fn_myprogress()
{	
    var val = $('#scheduleid').val()+","+$('#classid').val()+","+$('#hidstudid').val()+",1,"+$('#hidrotid').val();
    var stype=$('#scheduleid').val();
    var schtype = stype.split(',');
    var hidfilename = $("#hidfilename").val()+new Date().getTime();
    
    if(schtype[1]=='20' || schtype[1]=='1')
    {
        var oper = "expmyprogressreportexpandmod";
    }
    else
    {
        var oper = "expmyprogressreport";
    }
    setTimeout('removesections("#reports-myprogress-teacherreport");',500);
	
    ajaxloadingalert('Loading, please wait.');
    setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&oper='+oper+'&filename='+hidfilename+'");',500);
}
 