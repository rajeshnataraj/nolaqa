/*----
    fn_showstudent()
	Function to Load the Student Dropdown
	id -> Classid
----*/
function fn_showschedule(classid,studentid)
{
	$("#reports-pdfviewer").hide("fade").remove();
	$('#viewreportdiv').hide();
	 $('#rotationdiv').hide();	
	var dataparam = "oper=showschedule&classid="+classid+"&studentid="+studentid;
	$.ajax({
		type: 'post',
		url: 'reports/myprogress/reports-myprogress-myprogressajax.php',
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

function fn_showclass(studentid)
{
	$("#reports-pdfviewer").hide("fade").remove();
	$('#viewreportdiv').hide();
	$('#schedulediv').hide();
	$('#studentid').val(studentid);
	var dataparam = "oper=showclass&studentid="+studentid;
	$.ajax({
		type: 'post',
		url: 'reports/myprogress/reports-myprogress-myprogressajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#showclsdiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {
			$('#showclsdiv').show();		
			$('#showclsdiv').html(data);//Used to load the student details in the dropdown
		}
	});
}




/***********Show Rotation Developed By MOhan M 1-2-2016****************/

function fn_showrotation(schid,schtype)
{
	$("#reports-pdfviewer").hide("fade").remove();
	$('#viewreportdiv').hide();
   
	var dataparam = "oper=showrotation&schudid="+schid+"&schtype="+schtype;
	$.ajax({
		type: 'post',
		url: 'reports/myprogress/reports-myprogress-myprogressajax.php',
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
    var val = $('#scheduleid').val()+","+$('#classid').val()+","+$('#studentid').val()+",0,"+$('#hidrotid').val();
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
    
    setTimeout('removesections("#reports-myprogress");',500);
    ajaxloadingalert('Loading, please wait.');
    setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&oper='+oper+'&filename='+hidfilename+'");',500);
}