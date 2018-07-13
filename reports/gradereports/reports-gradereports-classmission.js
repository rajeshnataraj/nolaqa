/*
	Created By - Mohan. D
	Page - reports-gradereports-classmission.js

*/

/*--- District---*/
function fn_showteachers1(schid,indid,val)
{	
	$('#classdiv').hide();
	$('#cuiddiv').hide();
	$('#uniddiv').hide();
	$('#viewreportdiv').hide();
	$('#studentdiv').hide();
	$('#stupassdiv').hide();
	$('#rotationdiv').hide();
	$('#showstart').hide();
	fn_hide();
	$("#reports-pdfviewer").hide("fade").remove();
	var dataparam = "oper=showteachers1&schoolid="+schid+"&individualid="+indid+"&val="+val;
	$.ajax({
		type: 'post',
		url: 'reports/gradereports/reports-gradereports-classmissionajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#teachersdiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {		
			$('#teachersdiv').html(data);//Used to load the student details in the dropdown
		}
	});
}

/*--- District---*/
function fn_showclass1(id,val)
{	
	$('#classdiv').show();
	$('#viewreportdiv').hide();
	$('#uniddiv').hide();
	$('#cuiddiv').hide();
	$('#stupassdiv').hide();
	$('#studentdiv').hide();
	$('#rotationdiv').hide();
	$('#showstart').hide();
	fn_hide();
	$("#reports-pdfviewer").hide("fade").remove();
	var dataparam = "oper=showclass1&teacherid="+id+"&val="+val;
	$.ajax({
		type: 'post',
		url: 'reports/gradereports/reports-gradereports-classmissionajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#classdiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {		
			$('#classdiv').html(data);
		}
	});
}

/*--- District---*/
function fn_load_schedule1(type,id,prepostid)
{
	$("#reports-pdfviewer").hide("fade").remove();
	$('#viewreportdiv').hide();
	$('#uniddiv').hide();
	$('#showstart').hide();
        $('#rotationdiv').hide();
	fn_hide();
	$('#cuiddiv').show();
	var dataparam = "oper=showschedule1&type="+type+"&classid="+id+"&preposttype="+prepostid;
	$.ajax({
		type: 'post',
		url: 'reports/gradereports/reports-gradereports-classmissionajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#cuiddiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {		
			$('#cuiddiv').html(data);//Used to load the schedule details in the dropdown
		}
	});
}

function fn_expschload_rotation1(scheduleid,type)
{
	$("#reports-pdfviewer").hide("fade").remove();
	$('#viewreportdiv').hide();
	$('#expschedule').val(type);
	
	$('#rotationdiv').show();
	var dataparam = "oper=showexpschrotation&scheduleid="+scheduleid+"&type="+type;   
	$.ajax({
		type: 'post',
		url: 'reports/gradereports/reports-gradereports-classmissionajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#rotationdiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {		
			$('#rotationdiv').html(data);//Used to load the rotation details in the dropdown
		}
	});
}

function fn_checkexpschrotation1(id,rotid)
{
	var count = 0;
	
	if(id==0)
		$('label[for^=expcheck_'+rotid+']').attr('id','1');
	if(id==1)
		$('label[for^=expcheck_'+rotid+']').attr('id','0');
		
	$("input[id^=expcheck_]").each(function()
	{
		var newid = $(this).attr('name');
		
		if($('label[for^=expcheck_'+newid+']').attr('id')==1){
			count = 1;
		}
	});
	
	if(count==0)
		$('#viewreportdiv').hide();
	if(count==1)
		$('#viewreportdiv').show();
            
	
}


function fn_hide()
{
	$("#reports-pdfviewer").hide("fade").remove();
	$('#startdate1').val(''); 
	$('#enddate1').val('');
	$('#viewreportdiv').hide(); 
	$('#showend').hide();
}


function fn_gradereportmis(type,sessid)
{	
	var val;
	
        if(type==8)
	{
            var misormissch= $('#typeids').val();
            if(misormissch==18)
            {
                val = type+","+$('#classid').val()+","+$('#scheduleid').val()+","+$('#startdate1').val()+","+$('#enddate1').val()+","+$('#teacherid').val()+","+sessid;	
                oper="classmissionreport";
            }
            else if(misormissch==21)
            {
                var exprotationid = '';
                var exprotationid = [];
                $("input[id^=expcheck_]").each(function()
                {
                    var newid = $(this).attr('name');
                    if($('#expcheck_'+newid).is(':checked'))
                    {
                        exprotationid.push(newid);
                    }
                });
                
                val = type+"~"+$('#classid').val()+"~"+$('#scheduleid').val()+"~"+exprotationid+"~"+misormissch+"~"+$('#sendistid').val()+"~"+$('#schoolid').val();
                oper="classmissionschreport";

            }
	}
        ajaxloadingalert('Loading, please wait.');
        setTimeout('removesections("#reports-gradereports-classmission");',500);
        filename=$("#hidrepname").val()+new Date().getTime();
	setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&oper='+oper+'&filename='+filename+'");',500);	
}


function fn_exportmis(type,sessid,uid)
{
    var val;
    if(type==8)
    {
        var misormissch= $('#typeids').val();
        if(misormissch==18)
        {
            val = type+","+$('#classid').val()+","+$('#scheduleid').val()+","+$('#startdate1').val()+","+$('#enddate1').val()+","+$('#teacherid').val()+","+sessid+","+uid;
            window.location='reports/gradereports/reports-gradereports-missionexcel.php?id='+val;
        }
        else if(misormissch==21)
        {
            var exprotationid = '';
            var exprotationid = [];
            $("input[id^=expcheck_]").each(function()
            {
                var newid = $(this).attr('name');
                if($('#expcheck_'+newid).is(':checked'))
                {
                    exprotationid.push(newid);
                }
            });

            val = type+"~"+$('#classid').val()+"~"+$('#scheduleid').val()+"~"+exprotationid+"~"+misormissch+"~"+$('#sendistid').val()+"~"+$('#schoolid').val();
            window.location='reports/gradereports/reports-gradereports-missionschexcel.php?id='+val;

        }
    }
}