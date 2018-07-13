/*
	Created By - Mohan Kumar. V
	Page - reports-testreports.js
*/
function fn_showwaystoviewreports(schlid){
    
    var dataparam = "oper=loadwaystoviewreports&schlid="+schlid;
	$('#sortdropdiv').hide();
        $('#viewreportdiv').hide();
	$.ajax({
		type: 'post',
		url: 'reports/testreports/reports-testreports-testreportsajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#waytoview').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {		
			$('#waytoview').html(data);//Used to load the schedule details in the dropdown
		}
	});
    
}

function fn_showsortreport(viewtype,schlid){
    
    $('#viewdropdiv').hide(); 
    $('#viewdrop').show();
    $('#attemptsdiv').hide();
    $('#viewreportdiv').hide();
    var dataparam = "oper=loadsortreports&viewtype="+viewtype+"&schlid="+schlid;
		
	$.ajax({
		type: 'post',
		url: 'reports/testreports/reports-testreports-testreportsajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#sortdropdiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {		
			$('#sortdropdiv').html(data);//Used to load the schedule details in the dropdown
		}
	});
    
}
function fn_showattempts(sortaccesstype,schlid){

    $('#viewdrop').hide();
    
    $('#viewreportdiv').hide();
    
    var dataparam = "oper=loadattempts&sortaccesstype="+sortaccesstype+"&schlid="+schlid;
		
	$.ajax({
		type: 'post',
		url: 'reports/testreports/reports-testreports-testreportsajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#attemptsdiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {		
			$('#attemptsdiv').html(data);//Used to load the schedule details in the dropdown
		}
	});
    
}

function fn_showviewreport(accesstype,schlid){
    
        $("#reports-pdfviewer").hide("fade").remove();
	$('#viewreportdiv').hide();
        $('#attemptsdiv').hide();
    var dataparam = "oper=loadviewreports&accesstype="+accesstype+"&schlid="+schlid;
		
	$.ajax({
		type: 'post',
		url: 'reports/testreports/reports-testreports-testreportsajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#viewdropdiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {		
			$('#viewdropdiv').html(data);//Used to load the schedule details in the dropdown
		}
	});
    
}

/*
 * 
Function to Call the viewreport page for
student answered questions according to the type. */

function fn_testresultsreport()
{
   
    var val = $('#viewtype').val()+"~"+$('#sorttype').val()+"~"+$('#viewthereportdropid').val()+"~"+$('#schoolid').val()+"~"+$('#studentattemptid').val();
    
    setTimeout('removesections("#reports-testresultsreport");',500);
    oper="testresultsreport";
    filename=$("#hidtestname").val()+new Date().getTime();
    
        ajaxloadingalert('Loading, please wait.');
	setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&oper='+oper+'&filename='+filename+'");',500);
}
