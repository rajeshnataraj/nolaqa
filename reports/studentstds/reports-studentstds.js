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
        $('#showstate').hide();
        $('#docdiv').hide();
        $('#gradediv').hide();
	var dataparam = "oper=showschedule&classid="+classid;
	$.ajax({
		type: 'post',
		url: 'reports/studentstds/reports-studentstdsajax.php',
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
	$('#viewreportdiv').hide();
        $('#showstate').hide();
        $('#docdiv').hide();
        $('#gradediv').hide();
	var dataparam = "oper=showstudent&shudid="+shuid+"&schtype="+schtype;
	$.ajax({
		type: 'post',
		url: 'reports/studentstds/reports-studentstdsajax.php',
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

/*----
    fn_showdocuments()
	Function to load documents from AB API
	stid -> State Id
----*/
function fn_showdocuments(stid)
{
        $('#gradediv').hide();
        $('#viewreportdiv').hide();
	var dataparam = "oper=showdocuments&stid="+stid;      
        $.ajax({
		type: 'post',
		url: 'reports/studentstds/reports-studentstdsajax.php',
		data: dataparam,
		success:function(data) {
                        $('#docdiv').show();
			$('#docdiv').html(data);//Used to load the document details in the dropdown
                      
		}
	});
}

function fn_showstate(stid)
{
        $('#viewreportdiv').hide();
        $('#docdiv').hide();
        $('#gradediv').hide(); 
        
	var dataparam = "oper=showstate";      
        $.ajax({
		type: 'post',
		url: 'reports/studentstds/reports-studentstdsajax.php',
		data: dataparam,
		success:function(data) {
                        $('#statediv').show();
			$('#statediv').html(data);//Used to load the document details in the dropdown
                      
		}
	});
}

/*----
    fn_showgrades()
	Function to load grades from AB API
	stid -> State Id
----*/
function fn_showgrades(stdid,docid)
{
     var dataparam = "oper=showgrades&stdid="+stdid;       
        $.ajax({
		type: 'post',
		url: 'reports/studentstds/reports-studentstdsajax.php',
		data: dataparam,
		success:function(data) {	
                    $('#gradediv').show();
                    $('#gradediv').html(data);//Used to load the document details in the dropdown
                    
		}
	});
}

function fn_studentstds()
{	
	var val = $('#scheduleid').val()+","+$('#classid').val()+","+$('#hidstudid').val()+","+$('#selectstate').val()+","+$('#seldocument').val()+","+$('#selgrade').val();
        setTimeout('removesections("#reports-studentstds");',500);
	var oper = "studentstdsreport";
	var hidfilename = $("#hidfilename").val()+new Date().getTime();
	ajaxloadingalert('Loading, please wait.');
	setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&oper='+oper+'&filename='+hidfilename+'");',500);
}
 