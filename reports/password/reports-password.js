/*
	Created By - Muthukumar. D
	Page - reports-classroom.js
	History:
*/
/*******fn_school()
		Function is used to change the school based on the district
******/
function fn_school(id)
{	
	$("#reports-pdfviewer").hide("fade").remove();
	var dataparam = "oper=showschool&districtid="+id;
	$.ajax({
		type: 'post',
		url: 'reports/password/reports-password-passwordajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#schools').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {		
			$('#schools').html(data);//Used to load the student details in the dropdown
		}
	});
	$('#dist').show();
}

/*----
    fn_showpassreport()
	Function to Call the viewreport page for student password
	$('#studentid').val() -> Studentid, $('#classid').val() -> Classid
----*/
function fn_showpassreport(type)
{	
	setTimeout('removesections("#reports-password");',500);
	var val = $('#districtid').val()+"~"+$('#schoolid').val()+"~"+type;
	$.Zebra_Dialog('Download the report as ', {
    'type':     'question',
	'custom_class':  'myclass',
    'title':    'Export Users report',
	'overlay_close':false,
    'buttons':  [
                    {caption: 'PDF', callback: function() { 
					oper="userpassword";
					filename=$("#hidpassname").val()+new Date().getTime();
                                        ajaxloadingalert('Loading, please wait.');
					setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&oper='+oper+'&filename='+filename+'");',500);
					
					}},
                    {caption: 'Excel', callback: function() { 
					window.open("reports/password/reports-password-excelviewer.php?id="+val);
					}},
					 {caption: 'Cancel', callback: function() { 
					}}
					]
});
}
/*******fn_load_school_purcahse()
		Function is used to load the school purchase
******/
function fn_load_school_purcahse()
{	
	$("#reports-pdfviewer").hide("fade").remove();
	var dataparam = "oper=showschoolpurchase";
	$.ajax({
		type: 'post',
		url: 'reports/password/reports-password-passwordajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#schools').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {		
			$('#schools').html(data);//Used to load the student details in the dropdown
		}
	});
	$('#dist').show();
}
/*******fn_load_home_purcahse()
		Function is used to load the home purchase
******/
function fn_load_home_purcahse()
{	
	$("#reports-pdfviewer").hide("fade").remove();
	var dataparam = "oper=showhomepurchase";
	$.ajax({
		type: 'post',
		url: 'reports/password/reports-password-passwordajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#schools').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {		
			$('#schools').html(data);//Used to load the student details in the dropdown
		}
	});
	$('#dist').show();
}

