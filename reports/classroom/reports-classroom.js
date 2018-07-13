/*
	Created By - Muthukumar. D
	Page - reports-classroom.js
	
	History:


*/

/*---- Function To Display Student Password ----*/
/*----
    fn_selecttype()
	Function to Show/Hide the Class/Student Dropdown
	id -> 1/2, 1 -> Show Class dropdown, 2 -> Show Student dropdown
----*/
function fn_selecttype(id)
{	
	$("#reports-pdfviewer").hide("fade").remove();
	if(id==1)//Show Class dropdown
	{
		$('#studentid').val('');
		$('#stupassdiv').hide();//Hide Student dropdown
		$('#clspassdiv').show();//Show Class dropdown
	}
	if(id==2)//Show Student dropdown
	{
		$('#classid').val('');
		$('#clspassdiv').hide();//Hide Class dropdown
		$('#stupassdiv').show();//Show Student dropdown
	}
	$('#viewreportdiv').hide();
}

/*----
    fn_showpassreport()
	Function to Call the viewreport page for student password
	$('#studentid').val() -> Studentid, $('#classid').val() -> Classid
----*/
function fn_showpassreport(type)
{	
	var schid = $('#schoolid').val();
	var inid = $('#indid').val();
	var stuid = $('#studentid').val();
	var clsid = $('#classid').val();
	
	if(schid==undefined)
		schid='0';
	if(inid==undefined)
		inid='0';
	if(stuid==undefined)
		stuid='';
	if(clsid==undefined)
		clsid='';
		
	var val = type+","+stuid+","+clsid+","+schid+","+inid;	
	if(type==1)
	{
		setTimeout('removesections("#reports-classroom-stupassword");',500);
		var oper = "password";
	}
	
	else if(type==2)
	{
		setTimeout('removesections("#reports-classroom-stuschedule");',500);
		val = val+","+$('#hidcheckstu').val()+","+$('#hidcheckpass').val();
		var oper = "schedulereport";
    }
	
	else if(type==3)
	{
		setTimeout('removesections("#reports-classroom-masterschedule");',500);
		val = type+","+$('#classid').val()+","+$('#sciencetypeid').val();
		var oper = "masterschedulereport";
    }
	
	else if(type==4)
	{
		setTimeout('removesections("#reports-classroom-stageschedule");',500);
		val = type+","+$('#classid').val()+","+$('#sciencetypeid').val()+","+$('#stageid').val();
		var oper = "stageschedulereport";
    }
	
	else if(type==5)
	{
		setTimeout('removesections("#reports-classroom-indstuschedule");',500);
		val = val+","+$('#hidcheckstu').val()+","+$('#hidcheckpass').val()+","+$('#sciencetypeid').val();
		var oper = "indstudentschedulereport";
    }
	var hidfile = $("#hidfilename").val()+new Date().getTime();
        ajaxloadingalert('Loading, please wait.');
	setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&oper='+oper+'&filename='+hidfile+'");',500);
}

/*---- Function To Display Individual Student Reports ----*/
/*----
    fn_showstudent()
	Function to Load the Student Dropdown
	id -> Classid
----*/
function fn_showstudent(id)
{
	$("#reports-pdfviewer").hide("fade").remove();
	$('#sch').hide();
	$('#studentdiv').show();
	$('#stupassdiv').hide();
	$('#viewreportdiv').hide();
	
	var dataparam = "oper=showstudent&classid="+id;
	$.ajax({
		type: 'post',
		url: 'reports/classroom/reports-classroom-classroomajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#studentdiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {		
			$('#studentdiv').html(data);//Used to load the student details in the dropdown
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

function fn_checkpass()
{
	var val = $('#hidcheckpass').val();
	if(val==0)
		$('#hidcheckpass').val('1');
	else
		$('#hidcheckpass').val('0');
}



/*--- District/Pitsco ---*/
function fn_showteachers(schid,indid,val)
{	
	$('#viewreportdiv').hide();
	$('#classstudiv').hide();
	$('#classdiv').hide();
	$('#sctypediv').hide();
	$('#studentdiv').hide();
	$('#stupassdiv').hide();
	
	$('#stage').hide();
	$('#pass').hide();
	$('#sch').hide();
	
	$('#schoolid').val(schid);
	$('#indid').val(indid);
	
	$("#reports-pdfviewer").hide("fade").remove();
	var dataparam = "oper=showteachers&schoolid="+schid+"&individualid="+indid+"&val="+val;
	$.ajax({
		type: 'post',
		url: 'reports/classroom/reports-classroom-classroomajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#teachersdiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {		
			$('#teachersdiv').html(data);//Used to load the student details in the dropdown
		}
	});
}

function fn_showclassstu(val)
{	
	$('#viewreportdiv').hide();
	$('#classstudiv').show();
	$("#reports-pdfviewer").hide("fade").remove();
	
	if(val==1)//Show Class dropdown
		$('#studentid').val('');
	if(val==2)//Show Student dropdown
		$('#classid').val('');
		
	
	if($('#teacherid').val()=='' || $('#teacherid').val()==undefined)
		var teacherid = $('#hidteacher').val();
	else
		var teacherid = $('#teacherid').val();
		
	var dataparam = "oper=showclassstu&teacherid="+teacherid+"&type="+val+"&schoolid="+$('#schoolid').val()+"&indid="+$('#indid').val();
	$.ajax({
		type: 'post',
		url: 'reports/classroom/reports-classroom-classroomajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#classstudiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {		
			$('#classstudiv').html(data);
		}
	});
}

function fn_showclass(id,val)
{	
	$('#classdiv').show();
	$('#viewreportdiv').hide();
	$('#studentdiv').hide();
	$('#stupassdiv').hide();
	$('#sctypediv').hide();
	
	$('#stage').hide();
	$('#pass').hide();
	
	
	$("#reports-pdfviewer").hide("fade").remove();
	var dataparam = "oper=showclass&teacherid="+id+"&val="+val;
	$.ajax({
		type: 'post',
		url: 'reports/classroom/reports-classroom-classroomajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#classdiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {		
			$('#classdiv').html(data);
		}
	});
}
function fn_getallstudent() {

	$('#stupassdiv').show();
	$( '.showallst' ).show();
	$("label:first").addClass("checked");
	$("label:first").addClass("dim");
  	$( '.showsinglest' ).show();
	$("label:last").removeClass("checked");
	$('#hidcheckstu').val('1');
	$('#hidcheckpass').val('0');
	$('#viewreportdiv').show();

}
function fn_getselectedstudent() {

	$('#stupassdiv').show();
	$( '.showallst' ).show();
	$("label:first").removeClass("checked");
	$("label:first").removeClass("dim");
	$('#stuname').removeAttr('checked');
	$( '.showsinglest' ).show();
	$("label:last").removeClass("checked");
	$('#hidcheckpass').val('0');
	$('#hidcheckstu').val('0');
	$('#viewreportdiv').show();

}