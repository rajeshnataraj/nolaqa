/*----
    fn_showstudent()
	Function to Load the Student Dropdown
	id -> Classid
----*/
function fn_showschedule(classid)
{
   
	$("#reports-pdfviewer").hide("fade").remove();
	$('#viewreportdiv').hide();
        $('#statediv').hide();	
        $('#doc1div').hide();
        $('#gradediv').hide();
			
	var dataparam = "oper=showschedule&classid="+classid;	
	$.ajax({
		type: 'post',
		url: 'reports/classstandards/reports-classstandards-classstandardsajax.php',
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


function fn_showstate()
{
	$("#reports-pdfviewer").hide("fade").remove();
	$('#viewreportdiv').hide();
        $('#doc1div').hide();
        $('#gradediv').hide();
	var dataparam = "oper=showstates";
	$.ajax({
		type: 'post',
		url: 'reports/classstandards/reports-classstandards-classstandardsajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#statediv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {
			$('#statediv').show();		
			$('#statediv').html(data);//Used to load the student details in the dropdown
		}
	});
}

function fn_showdocuments(stid)
{
    $("#reports-pdfviewer").hide("fade").remove();
    $('#viewreportdiv').hide();
    $('#gradediv').hide();
    var dataparam = "oper=showdocuments&stid="+stid;
    $.ajax({
            type: 'post',
            url: 'reports/classstandards/reports-classstandards-classstandardsajax.php',
            data: dataparam,
            success:function(data) {
                $('#doc1div').show();
                $('#doc1div').html(data);//Used to load the student details in the dropdown
            }
    });
}

/*----
    fn_showgrades()
	Function to load grades from AB API
	stid -> State Id
----*/
function fn_showgrades(stdid)
{
     $("#reports-pdfviewer").hide("fade").remove();
    $('#viewreportdiv').hide();
	var dataparam = "oper=showgrades&stdid="+stdid;
	$.ajax({
		type: 'post',
		url: 'reports/classstandards/reports-classstandards-classstandardsajax.php',
		data: dataparam,
		success:function(data) {	
                    $('#gradediv').show();	
                    $('#gradediv').html(data);//Used to load the student details in the dropdown
                   
		}
	});
}

function fn_myprogress()
{	
	var val = $('#scheduleid').val()+","+$('#classid').val()+","+$('#hidstudid').val()+",1";
	setTimeout('removesections("#reports-myprogress-teacherreport");',500);
	var oper = "myprogressreport";
	var hidfilename = $("#hidfilename").val()+new Date().getTime();
	ajaxloadingalert('Loading, please wait.');
	setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&oper='+oper+'&filename='+hidfilename+'");',500);
}
function fn_classstdprogress()
{
   var val = $('#scheduleid').val()+","+$('#classid').val()+","+$('#hidstateid').val()+","+$('#seldocument').val()+","+$('#selgrade').val()+",1";
   setTimeout('removesections("#reports-classstandards");',500);
   var oper = "classstdprogress";
   var hidfilename = $("#hidfilename").val()+new Date().getTime();  
   ajaxloadingalert('Loading, please wait.');
   setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&oper='+oper+'&filename='+hidfilename+'");',500);
   
}
 