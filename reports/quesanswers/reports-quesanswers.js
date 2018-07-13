/*
	Created By - Muthukumar. D
	Page - reports-quesanswers.js
	
	History: updated By mohan kumar .v 
 * For select all students


*/

/*----
    fn_showstudent()
	Function to Load the Student Dropdown
	id -> Classid, type -> 1/2, 1->IPL,2->Module
	assignmentid-> show assignment id 
----*/
function fn_showstudent(id,assignmentid,type,typename)
{
	$("#reports-pdfviewer").hide("fade").remove();
	$('#viewreportdiv').hide();
	
	var dataparam = "oper=showstudent&classid="+id+"&assignmentid="+assignmentid+"&type="+type+"&typename="+typename;	
	$.ajax({
		type: 'post',
		url: 'reports/quesanswers/reports-quesanswers-quesanswersajax.php',
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
    fn_load_assignments()
	Function to load schedule dropdown
	id -> Studentid, classid -> Classid, type -> 1/2, 1->IPL,2->Module
----*/
function fn_load_assignments(type,classid)
{
	$("#reports-pdfviewer").hide("fade").remove();
	$('#viewreportdiv').hide();
	$('#studentdiv').hide();
	$('#iplunitdiv').hide();
	var dataparam;
	if(type==1)
		dataparam = "oper=showiplassignments&classid="+classid+"&type="+type;
		
	if(type==2)
		dataparam = "oper=loadmoduleassignments&classid="+classid+"&type="+type;	
	if(type==3)
		dataparam = "oper=loadtestassignments&classid="+classid+"&type="+type;
		
	$.ajax({
		type: 'post',
		url: 'reports/quesanswers/reports-quesanswers-quesanswersajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#assignmentdiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {		
			$('#assignmentdiv').html(data);//Used to load the schedule details in the dropdown
		}
	});
}

/*----
    fn_showipl()
	Function to Load the ipl Dropdown
	assignmentid -> Sigmath Scheduleid
----*/
function fn_showipl(assignmentid,typename,classid,type)
{
	$("#reports-pdfviewer").hide("fade").remove();
	$('#viewreportdiv').hide();
	var val = assignmentid+","+typename+","+classid+","+type;	
	var dataparam = "oper=showipl&assignmentid="+val;
	
	$.ajax({
		type: 'post',
		url: 'reports/quesanswers/reports-quesanswers-quesanswersajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#iplunitdiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {		
			$('#iplunitdiv').html(data);//Used to load the student details in the dropdown
		}
	});
}


/****Survey Report For Exp Schedule****/
function  fn_dummyexpsch(hidval)
{
    $('#hidexpsch').val(hidval);
}

/*----
    fn_questionreport()
	Function to Call the viewreport page for question & answer reports according to the type.
	$('#studentid').val() -> Studentid, $('#classid').val() -> Classid, ids[0] -> Moduleid, ids[1] -> Scheduleid
	$('#scheduleid').val()/$('#assignmentid').val() -> Scheduleid, $('#rotationid').val() -> Rotationid, $('#iplid').val() -> IPLid
	type=1/2/3/4, 1 -> Ind.IPL Question Report, 2 -> IPL Analytics Report, 3 -> IPL Summary Report, 4 -> Knowledge Survey Report
----*/
function fn_questionreport(type)
{	
	var val;
	if(type==1)
	{
		val = type+","+$('#classid').val()+","+$('#studentid').val()+","+$('#assignmentid').val()+","+$('#iplid').val();	
		setTimeout('removesections("#reports-quesanswers-indiplquestionreport");',500);
		oper="indiplquestionreport";
		filename=$("#hidquesname").val()+new Date().getTime();
	}
	else if(type==2)
	{		
		val = type+","+$('#classid').val()+","+$('#scheduleid').val()+","+$('#hidteacherid').val();
		setTimeout('removesections("#reports-quesanswers-iplanalyticsreport");',500);	
		oper="iplanalyticsreport";
		filename=$("#hidiplananame").val()+new Date().getTime();
	}
	else if(type==3)
	{
		val = type+","+$('#classid').val()+","+$('#scheduleid').val()+","+$('#hidteacherid').val()+","+$('#hidcheck').val();
		setTimeout('removesections("#reports-quesanswers-iplsummaryreport");',500);
		oper="iplsummaryreport";
		filename=$("#hidiplsumaname").val()+new Date().getTime();
	}
	else if(type==4)
	{
                var surveyrptexp= $('#hidexpsch').val();
		ids= $('#moduleid').val().split('~');
		val = type+","+$('#studentid').val()+","+ids[0]+","+ids[1]+","+ids[2]+","+$('#classid').val();		 
		setTimeout('removesections("#reports-quesanswers-surveyreport");',500);
		if(ids[2]=='15' || ids[2]=='19' || ids[2]=='20' )
                {
			oper="surveyreportexpsch"; 
                }
                else
		{	
			 oper="surveyreport";
                }
		filename=$("#hidmodname").val()+new Date().getTime();
	}
	else if(type==5)
	{
		val = type+","+$('#classid').val()+","+$('#studentid').val()+","+$('#assignmentid').val()+",0";	
		setTimeout('removesections("#reports-quesanswers-assessmentquestion");',500);
		oper="assessmentquestion";
		filename=$("#hidtestname").val()+new Date().getTime();
	}	
        ajaxloadingalert('Loading, please wait.');
	setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&oper='+oper+'&filename='+filename+'");',500);
}



/*----
    fn_load_assignments()
	Function to load schedule dropdown
	classid -> Classid
----*/
function fn_load_units(classid)
{
	$("#reports-pdfviewer").hide("fade").remove();
	$('#ipl').hide();
	var dataparam = "oper=showunits&classid="+classid;
	$.ajax({
		type: 'post',
		url: 'reports/quesanswers/reports-quesanswers-quesanswersajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#assign').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {		
			$('#assign').html(data);//Used to load the schedule details in the dropdown
		}
	});
}


/*----
    fn_load_assignments()
	Function to load schedule dropdown
	classid -> Classid
----*/
function fn_load_schedules(classid)
{
	$("#reports-pdfviewer").hide("fade").remove();
	$('#viewreportdiv').hide();
	var dataparam = "oper=showschedules&classid="+classid;
	$.ajax({
		type: 'post',
		url: 'reports/quesanswers/reports-quesanswers-quesanswersajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#schedulediv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {		
			$('#schedulediv').html(data);//Used to load the schedule details in the dropdown
		}
	});
}

function fn_check()
{
	var val = $('#hidcheck').val();
	if(val==0)
		$('#hidcheck').val('1');
	else
		$('#hidcheck').val('0');
}


/*--- District/Pitsco ---*/
function fn_showteachers(schid,indid,val)
{	
	$('#studentdiv').hide();
	$('#viewreportdiv').hide();
	$('#assignmentdiv').hide();
	$('#iplunitdiv').hide();
	$('#classdiv').hide();
	$('#schedulediv').hide();
	$('#stupassdiv').hide();
	
	$("#reports-pdfviewer").hide("fade").remove();
	var dataparam = "oper=showteachers&schoolid="+schid+"&individualid="+indid+"&val="+val;
	$.ajax({
		type: 'post',
		url: 'reports/quesanswers/reports-quesanswers-quesanswersajax.php',
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
	$('#studentdiv').hide();
	$('#assignmentdiv').hide();
	$('#iplunitdiv').hide();
	$('#schedulediv').hide();
	$('#stupassdiv').hide();
	
	$("#reports-pdfviewer").hide("fade").remove();
	var dataparam = "oper=showclass&teacherid="+id+"&val="+val;
	$.ajax({
		type: 'post',
		url: 'reports/quesanswers/reports-quesanswers-quesanswersajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#classdiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {		
			$('#classdiv').html(data);
		}
	});
}