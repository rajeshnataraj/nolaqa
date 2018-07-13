/*
	Created By - Muthukumar. D
	Page - reports-gradereports.js
	
	History: updated By mohan kumar .v 
 * For select all students and order changed from class->student->assignmet to  class->assignmet->student


*/

/*----
    fn_showstudent()
	Function to Load the Student Dropdown
	id -> Classid
----*/
function fn_showstudent(type,id)
{
	$("#reports-pdfviewer").hide("fade").remove();
	$('#stupassdiv').hide();
	$('#viewreportdiv').hide();
	
	$('#showstart').hide();
	
	var dataparam = "oper=showstudent&type="+type+"&classid="+id;
	$.ajax({
		type: 'post',
		url: 'reports/gradereports/reports-gradereports-gradeajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#studentdiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {		
			$('#studentdiv').html(data);//Used to load the student details in the dropdown
		}
	});
}

/*----
    fn_load_schedule()
	Function to load schedule dropdown
	id -> class ID
----*/
function fn_load_schedule(type,id,prepostid)
{
	$("#reports-pdfviewer").hide("fade").remove();
	$('#viewreportdiv').hide();
	$('#uniddiv').hide();
        $('#rotationdiv').hide();
	$('#showstart').hide();
	fn_hide();
	$('#cuiddiv').show();
	var dataparam = "oper=showschedule&type="+type+"&classid="+id+"&preposttype="+prepostid;
	$.ajax({
		type: 'post',
		url: 'reports/gradereports/reports-gradereports-gradeajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#cuiddiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {		
			$('#cuiddiv').html(data);//Used to load the schedule details in the dropdown
		}
	});
}

/*----
    fn_load_rotation()
	Function to load schedule dropdown
	id -> schedule ID
	type -> 1- schedule 2-dyad
----*/
function fn_load_rotation(scheduleid,type)
{
	$("#reports-pdfviewer").hide("fade").remove();
	$('#viewreportdiv').hide();
        $('#expschedule').val(type);
	
	$('#uniddiv').show();
	var dataparam = "oper=showrotation&scheduleid="+scheduleid+"&type="+type;
	$.ajax({
		type: 'post',
		url: 'reports/gradereports/reports-gradereports-gradeajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#uniddiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {		
			$('#uniddiv').html(data);//Used to load the rotation details in the dropdown
		}
	});
}

/*----
    fn_gradereport()
	Function to Call the viewreport page for gradereports according to the type.
	$('#studentid').val() -> Studentid, $('#classid').val() -> Classid 
	$('#scheduleid').val() -> Scheduleid, $('#rotationid').val() -> Rotationid
	$('#assignmentid').val() -> Assignmentid
	type -> 1/2/3/4,  1 -> Class Schedule Score Report,  2 -> Individual Grade Report,  3 -> Class Report,  4 -> Individual Assignment Grade Report.
	20 -> Module and Expedition schedule
----*/
function fn_gradereport(type)
{	
	var val;
	if(type==1)
	{
		var rotationid = '';
		$("input[id^=check_]").each(function()
		{
			var newid = $(this).attr('name');
			if($('#check_'+newid).is(':checked')){
				if(rotationid=='')
				{
					rotationid = newid;
				}
				else
				{
					rotationid = rotationid+"~"+newid;
				}
			}
		});
		var typeid=$('#typeids').val();
		val = type+","+$('#scheduleid').val()+","+$('#classid').val()+","+rotationid+","+typeid;		
		setTimeout('removesections("#reports-gradereports-classschedule");',500);	
		if(typeid==20) // Module and expedition schedule Developed by Mohan M
		{
			var oper="classschedulemodexp";
		}
		else
		{
			var oper="classschedule";
		}
		var filename=$("#hidclsschname").val()+new Date().getTime();
	}
	else if(type==2)
	{
		val = type+","+$('#studentid').val()+","+$('#classid').val()+","+$('#hidcheckstu').val()+","+$('#startdate1').val()+","+$('#enddate1').val();	
		setTimeout('removesections("#reports-gradereports-individualgrade");',500);
		oper="individualgrade";
		filename=$("#hidindname").val()+new Date().getTime();
	}
	else if(type==3)
	{
		val = type+","+$('#classid').val()+","+$('#startdate1').val()+","+$('#enddate1').val()+","+$('#hidcheckstu').val();	
		setTimeout('removesections("#reports-gradereports-classreport");',500);
		oper="classreporttest";
		filename=$("#hidclsname").val()+new Date().getTime();
	}
	else if(type==4)
	{
		var list4 = [];
		$("div[id^=list4_]").each(function(){
                    var id=$(this).attr('id').replace('list4_','');
                    var name="-"+$(this).attr('title')
                    var concat=id+name;
                    list4.push(concat);
                });
                
		val = type+"~"+$('#classid').val()+"~"+$('#studentidnew').val()+"~"+list4+"~"+$('#hidcheckstu').val();
		setTimeout('removesections("#reports-gradereports-indvidualassignment");',500);
		oper="indvidualassignment";
		filename=$("#hidindassname").val()+new Date().getTime();
	}
	else if(type==5)
	{
		val = type+","+$('#studentid').val()+","+$('#classid').val(); //+","+$('#hidcheckstu').val();
		setTimeout('removesections("#reports-gradereports-assementengine");',500);
		oper="assementengine";
		filename=$("#hidassengname").val()+new Date().getTime();
	}
	else if(type==6)
	{
		val = type+","+$('#scheduleid').val()+","+$('#classid').val()+","+$('#schtype').val();
		setTimeout('removesections("#reports-gradereports-prepost");',500);	
		oper="preposttest";
		filename=$("#hidprepostname").val()+new Date().getTime();
	}
	else if(type==7)
	{
            var exporexpsch= $('#expschedule').val();
            if(exporexpsch==15)
			{
				val = type+","+$('#classid').val()+","+$('#scheduleid').val()+","+$('#startdate1').val()+","+$('#enddate1').val();	
				setTimeout('removesections("#reports-gradereports-classexpedition");',500);
		oper="classexpeditionreport";
				filename=$("#hidrepname").val()+new Date().getTime();
			}
			else if(exporexpsch==20) // Module and expedition schedule Developed by Mohan M
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
                
                val = type+"~"+$('#classid').val()+"~"+$('#scheduleid').val()+"~"+exprotationid+"~"+$('#expschtypeids').val();
				setTimeout('removesections("#reports-gradereports-classexpedition");',500);
				oper="classmodexprescheduleport";
				filename=$("#hidexpschname").val()+new Date().getTime();
			}
			else
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
                
                val = type+"~"+$('#classid').val()+"~"+$('#scheduleid').val()+"~"+exprotationid+"~"+$('#expschtypeids').val()+"~"+$('#sendistid').val()+"~"+$('#schoolid').val();
				setTimeout('removesections("#reports-gradereports-classexpedition");',500);
				oper="classexprescheduleport";
				filename=$("#hidexpschname").val()+new Date().getTime();
            }
	}
	ajaxloadingalert('Loading, please wait.');
	setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&oper='+oper+'&filename='+filename+'");',500);
}

function fn_export(type)
{
	var val;
	if(type==1)
	{
		var rotationid = '';
		$("input[id^=check_]").each(function()
		{
			var newid = $(this).attr('name');
			if($('#check_'+newid).is(':checked')){
				if(rotationid=='')
				{
					rotationid = newid;
				}
				else
				{
					rotationid = rotationid+"~"+newid;
				}
			}
		});
		
		
		val = type+","+$('#scheduleid').val()+","+$('#classid').val()+","+rotationid+","+$('#typeids').val();
	}
	else if(type==2)
	{
		val = type+","+$('#studentid').val()+","+$('#classid').val()+","+$('#hidcheckstu').val()+","+$('#startdate1').val()+","+$('#enddate1').val();	
	}
	else if(type==3)
	{
		val = type+","+$('#classid').val()+","+$('#startdate1').val()+","+$('#enddate1').val()+","+$('#hidcheckstu').val();	
	}
	else if(type==4)
	{
		var list4 = [];
		$("div[id^=list4_]").each(function(){
                    var id=$(this).attr('id').replace('list4_','');
                    var name="-"+$(this).attr('title')
                    var concat=id+name;
                    list4.push(concat);
                });
                
		val = type+"~"+$('#classid').val()+"~"+$('#studentidnew').val()+"~"+list4+"~"+$('#hidcheckstu').val();
		
	}
	else if(type==5)
	{
		val = type+","+$('#studentid').val()+","+$('#classid').val(); 
	}
	else if(type==6)
	{
		val = type+","+$('#scheduleid').val()+","+$('#classid').val()+","+$('#schtype').val();
	}
	else if(type==7)
	{
		var exporexpsch= $('#expschedule').val();
		if(exporexpsch==15)
		{
			val = type+","+$('#classid').val()+","+$('#scheduleid').val()+","+$('#startdate1').val()+","+$('#enddate1').val();
		}
		else if(exporexpsch==20)
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
			val = type+"~"+$('#classid').val()+"~"+$('#scheduleid').val()+"~"+exprotationid+"~"+$('#expschtypeids').val();
		}
		else
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
			val = type+"~"+$('#classid').val()+"~"+$('#scheduleid').val()+"~"+exprotationid+"~"+$('#expschtypeids').val();
		}
	}
      
	if(type!=7 & type !=4)
	{
            window.location='reports/gradereports/reports-gradereports-export.php?id='+val;
	}
	else if(type == 4)
	{
            window.location='reports/gradereports/reports-gradereports-newexport.php?id='+val;
	}
	else
	{
            var exporexpsch= $('#expschedule').val();
            if(exporexpsch==15)
            {
                window.location='reports/gradereports/reports-gradereports-excel.php?id='+val;
            }
            else if(exporexpsch==20)
            {
                window.location='reports/gradereports/reports-gradereports-expmodschexcel.php?id='+val;
            }
            else
            {
                window.location='reports/gradereports/reports-gradereports-expschexcel.php?id='+val;
            }

	}
}

function fn_load_assignment(classid)
{
	$("#reports-pdfviewer").hide("fade").remove();
	$('#stupassdiv').hide();
	$('#studentdiv').hide();
	$('#viewreportdiv').hide();
	
	var dataparam = "oper=showassignment&classid="+classid;
	$.ajax({
		type: 'post',
		url: 'reports/gradereports/reports-gradereports-gradeajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#rotationdiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {		
			$('#rotationdiv').html(data);//Used to load the rotation details in the dropdown
			
		}
	});
}

function fn_checkstu()
{
	var val = $('#hidcheckstu').val();
	if(val==0)
		$('#hidcheckstu').val('1');
	else
		$('#hidcheckstu').val('0');
}


/*--- District/Pitsco ---*/
function fn_showteachers(schid,indid,val)
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
	var dataparam = "oper=showteachers&schoolid="+schid+"&individualid="+indid+"&val="+val;
	$.ajax({
		type: 'post',
		url: 'reports/gradereports/reports-gradereports-gradeajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#teachersdiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {		
			$('#teachersdiv').html(data);//Used to load the student details in the dropdown
		}
	});
}

function fn_showclass(id,val)
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
	var dataparam = "oper=showclass&teacherid="+id+"&val="+val;
	$.ajax({
		type: 'post',
		url: 'reports/gradereports/reports-gradereports-gradeajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#classdiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {		
			$('#classdiv').html(data);
		}
	});
}

function fn_checkrotation(id,rotid)
{
	var count = 0;
	
	if(id==0)
		$('label[for^=check_'+rotid+']').attr('id','1');
	if(id==1)
		$('label[for^=check_'+rotid+']').attr('id','0');
		
	$("input[id^=check_]").each(function()
	{
		var newid = $(this).attr('name');
		
		if($('label[for^=check_'+newid+']').attr('id')==1){
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

function fn_checkstu(flag)
{
    $('#hidcheckstu').val('0');
    if(flag==1)
    {
        $('#chkusername').attr('checked', false);
        $('#chkusername').removeClass('checked');
        $('#chkpass').attr('checked', false);
        $('#chkpass').removeClass('checked');
        
       
            $("#hidcheckstu").val('1');  // checked
       
	
    }
    else if(flag==2)
    {
        $('#chkname').attr('checked', false);
        $('#chkname').removeClass('checked');
        $('#chkpass').attr('checked', false);
        $('#chkpass').removeClass('checked');
        
        $("#hidcheckstu").val('2');  // checked
       
       
    }
    else if(flag==3)
    {
        $('#chkname').attr('checked', false);
        $('#chkname').removeClass('checked');
        $('#chkusername').attr('checked', false);
        $('#chkusername').removeClass('checked');
        
       
            $("#hidcheckstu").val('3');  // checked
        
        
        
    }
}

/********Expedition Schedule Code Start here Developed by Mohan M***********/
function fn_expschload_rotation(scheduleid,type)
{
	$("#reports-pdfviewer").hide("fade").remove();
	$('#viewreportdiv').hide();
	$('#expschedule').val(type);
	
	$('#rotationdiv').show();
	var dataparam = "oper=showexpschrotation&scheduleid="+scheduleid+"&type="+type;
	$.ajax({
		type: 'post',
		url: 'reports/gradereports/reports-gradereports-gradeajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#rotationdiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {		
			$('#rotationdiv').html(data);//Used to load the rotation details in the dropdown
		}
	});
}


function fn_checkexpschrotation(id,rotid)
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
/********Expedition Schedule Code End here Developed by Mohan M***********/

/* Chandru new task query start here */
function fn_load_studentnew(classid)
{
	$("#reports-pdfviewer").hide("fade").remove();
	$('#stupassdiv').hide();
	$('#studentdiv').hide();
	$('#viewreportdiv').hide();
	
	var dataparam = "oper=showstudentnew&classid="+classid;
	$.ajax({
		type: 'post',
		url: 'reports/gradereports/reports-gradereports-gradeajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#rotationdiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {		
			$('#rotationdiv').html(data);//Used to load the rotation details in the dropdown
			
		}
	});
}

function fn_showscdulenew(classid,studentid)
{
	var dataparam = "oper=showscdulenew&classid="+classid+"&studentid="+studentid;
	$.ajax({
		type: 'post',
		url: 'reports/gradereports/reports-gradereports-gradeajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#scdulediv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {	
			$('#scdulediv').show();
			$('#scdulediv').html(data);//Used to load the rotation details in the dropdown
			
		}
	});
}

function fn_movealllistitems(leftlist,rightlist,id,courseid)
{
    if(id == 0)
    {
        $("div[id^="+leftlist+"_]").each(function()
        {
                if(!$(this).hasClass('dim')){
                        var clas = $(this).attr('class');
                        var temp = $(this).attr('id').replace(leftlist,rightlist);

                        $(this).attr('id',temp);
                        $('#'+rightlist).append($(this));

                        if($(this).attr('class') == 'draglinkleft') {
                                $(this).removeClass("draglinkleft draglinkright");
                                $(this).addClass("draglinkright");
                        } else {
                                $(this).removeClass("draglinkleft draglinkright");
                                $(this).addClass("draglinkleft");
                        }
                }
        });
    }
    else
    {
        var clas=$('#'+leftlist+'_'+id).attr('class');
        if(clas=="draglinkleft")
        {
                $('#'+rightlist).append($('#'+leftlist+' #'+leftlist+'_'+id));
                $('#'+leftlist+'_'+id).removeClass('draglinkleft').addClass('draglinkright');
                var temp = $('#'+leftlist+'_'+id).attr('id').replace(leftlist,rightlist);					
                var ids='id';
                $('#'+leftlist+'_'+id).attr(ids,temp);
        }
        else 
        {	
                $('#'+leftlist).append($('#'+rightlist+' #'+rightlist+'_'+id));
                $('#'+rightlist+'_'+id).removeClass('draglinkright').addClass('draglinkleft');
                var temp = $('#'+rightlist+'_'+id).attr('id').replace(rightlist,leftlist);					
                var ids='id';
                $('#'+rightlist+'_'+id).attr(ids,temp);
        }
				
    }
 	var scheduleids = [];
	$("div[id^=list4_]").each(function()
    {
		var guid = $(this).attr('id').replace('list4_',''); 
		scheduleids.push(guid);
		//alert(scheduleids);
		if(scheduleids!='')
		{
			$('#viewreportdiv').show();
		}
		else
		{
			$('#viewreportdiv').hide();
		}

    });

    
}
/*Chandru new task query end here */