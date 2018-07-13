/*
	Created By - Mohan M
	Page - backupreports.js
*/

function fn_showclass(schoolid,distid)
{	
	$('#classdiv').show();
	$('#viewreportdiv').hide();
	
	$("#reports-pdfviewer").hide("fade").remove();
	var dataparam = "oper=showclass&schoolid="+schoolid+"&distid="+distid;
	$.ajax({
		type: 'post',
		url: 'reports/backupreports/reports-backupreports-ajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#classdiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {		
			$('#classdiv').html(data);
		}
	});
}

function fn_showpassreport(type)
{	
	var schid = $('#schoolid').val();
	var clsid = $('#classid').val();
	var oper = "preposttestrestore";
	var hidfile = $("#hidfilename").val()+new Date().getTime();
	//var schid='90-39';
	//var clsid='6948';
	
	var val = schid+","+clsid;
	
	var val = schid+","+clsid;
	//alert(val);
	setTimeout('removesections("#reports-backupreports");',500);
	ajaxloadingalert('Loading, please wait.');
	setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&oper='+oper+'&filename='+hidfile+'");',500);
}

function fn_export(type)
{	
	var schid = $('#schoolid').val();
	var clsid = $('#classid').val();
	var val = schid+","+clsid;
	
    if(type=='1')
    {
        window.location='reports/backupreports/reports-backupreports-export.php?id='+val;
    }
    else
    {
        window.location='reports/backupreports/reports-backupreports-unformattedexcel.php?id='+val; 
    }
}