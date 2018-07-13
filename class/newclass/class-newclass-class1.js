function fn_loadgrade(mtype)
{
	var dataparam = "oper=loadgrade&classid="+$('#hidclassid').val()+"&moduleid="+$('#moduleid').val()+"&scheduleid="+$('#hidscheduleid').val()+"&scheduletype="+$('#scheduletype').val()+"&mtype="+mtype;
	$.ajax({
		type	: "POST",
		cache	: false,
		url		: 'class/newclass/class-newclass-classajax.php',
		data	: dataparam,
		success : function(data) {
			$.fancybox(data);
		}
	});
	return false;
}

function fn_savewcagrade(classid,moduleid,scheduleid,scheduletype)
{	
	var title = '';
	var point = '';
	var grade = [];
	$("label[id^=wca_]").each(function()
	{
		if(title=='')
		{
			title = $(this).html();
		}
		else
		{
			title = title+"~"+$(this).html();
		}
	});
	
	$("input[id^=point_]").each(function()
	{
		if(point=='')
		{
			point = $(this).val();
		}
		else
		{
			point = point+"~"+$(this).val();
		}
	});
	
	$("input[id^=grade_]").each(function()
	{
		var newid = $(this).attr('name');		
		var cval=0;
		if($('#grade_'+newid).is(':checked')){
			cval=1;
		}
		grade.push(cval);		
	});	
	$('#hidpagetitle').val(title);
	$('#hidpoints').val(point);
	$('#hidgrades').val(grade);
	$.fancybox.close();
}



/*----
    fn_movealllistitems(leftlist,rightlist,id,courseid,lid)
	Function to move from one list to another list
		leftlist - id of the draggable left/right list box
		rightlist - id of the draggable right/left list box
		id - type of call made 0 - move all, 1 - particular item
		courseid - id of the item moved if the type is 1
		lid - lesson id 
----*/
function fn_movealllistitems(leftlist,rightlist,id,courseid,lid)
{
	if(id == 0)
	{
		$("div[id^="+leftlist+"_]").each(function()
		{
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
		});

		if(courseid!=undefined && courseid!=0 && courseid!="rotational")
		{
			fn_loadcontent($('#hidscheduleid').val(),1);
		}
		if(lid!=undefined)
		{			
		}
		
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
		if(courseid!=undefined && courseid!=0 && courseid!="rotational")
		{
			fn_loadcontent($('#hidscheduleid').val(),1);
		}
		if(lid!=undefined)
		{			
		}			
	}
	
	if(courseid=="rotational" || $('#scheduletype').val()==2 || $('#scheduletype').val()==6)
	{
		fn_checking();
	}
}

/*---- Save Step - 3
    fn_teacherstudentidmaptoclass()
	Function to save teacher and student
----*/
function fn_teacherstudentidmaptoclass(classid,flag)
{	
	var list1 = [];
	var list2 = [];
	var list3 = [];
	var list4 = [];
	
	$("div[id^=list1_]").each(function(){
		list1.push($(this).attr('id').replace('list1_',''));
	});
		
	$("div[id^=list2_]").each(function(){
		list2.push($(this).attr('id').replace('list2_',''));
	});
	
	$("div[id^=list3_]").each(function(){
		list3.push($(this).attr('id').replace('list3_',''));
	});
	
	$("div[id^=list4_]").each(function(){
		list4.push($(this).attr('id').replace('list4_',''));
	});
	
	
	if(list2=='')
	{
		showloadingalert("please select any teacher.");	 
		setTimeout('closeloadingalert()',2000);
		return false;
	}
	
	if(list4=='')
	{
		showloadingalert("please select any student.");	
		setTimeout('closeloadingalert()',2000);
		return false;
	}
				
	var dataparam = "oper=maptoclass"+"&classid="+$('#classid').val()+"&list1="+list1+"&list2="+list2+"&list3="+list3+"&list4="+list4;	
	$.ajax({
		type: "POST",
		url: 'class/newclass/class-newclass-classajax.php',
		data: dataparam,
		beforeSend:function()
		{
			showloadingalert("Loading, please wait.");
		},
		success: function(data)
		{
			closeloadingalert();
			showloadingalert("Added successfully.");
			setTimeout("closeloadingalert();",1000);
			
			var val = classid;
			
			if(flag==1)
			{
				showloadingalert("Saved Successfully");
				setTimeout("closeloadingalert();",500);
				setTimeout("removesections('#class-newclass-actions');",1000);
			}
			else
			{
				if($('#hidflag').val()!=1)
					setTimeout("removesections('#class');",500);
				else
					setTimeout("removesections('#class-newclass-actions');",500);
					
					setTimeout('showpageswithpostmethod("class-newclass-steps","class/newclass/class-newclass-steps.php","id='+val+'");',500);
			}
		}
	});
}

/*---- Save Step - 1 & 2
    fn_next()
	Function to save a class
----*/
function fn_saveclass(classid,flag)
{
	var dataparam = '';	
	if($("#classform").validate().form())
	{
		var counter=$('#counter').val();
		for(i=1;i<=(counter);i++)
		{			
			if($('#lettergrade' + i).val()=='')
			{
				$('#lettergrade' + i).focus();
				showloadingalert("Lettergrade is Required");	
				setTimeout('closeloadingalert()',2000);
				return false;
			}
			if($('#lettergrade' + i).val().match(/[^A-Z+]/i))
			{
				showloadingalert("Lettergrade cannot have special characters and space and except + symbol");	
				setTimeout('closeloadingalert()',2000);
				return false;
			}
	
			if($('#lowerbound' + i).val()=='')
			{
				$('#lowerbound' + i).focus();
				showloadingalert("LowerBound is Required");	
				setTimeout('closeloadingalert()',2000);
				return false;
			}
			if($('#lowerbound' + i).val()>=99)
			{
				$('#lowerbound' + i).val('');
				$('#lowerbound' + i).focus();
				showloadingalert("LowerBound Only Allowed less then or equal to 100");	
				setTimeout('closeloadingalert()',2000);
				return false;
			}
			if($('#higherbound' + i).val()=='')
			{
				$('#higherbound' + i).focus();
				showloadingalert("HigherBound is Required");	
				setTimeout('closeloadingalert()',2000);
				return false;
			}
			if($('#higherbound' + i).val()<0 || $('#higherbound' + i).val() >100)
			{
				$('#higherbound' + i).val('');
				$('#higherbound' + i).focus();
				showloadingalert("HigherBound Only Allowed less then or equal to 100");	
				setTimeout('closeloadingalert()',2000);
				return false;
			}
	
			for(j=i+1;j<=counter-1;j++)
			{						
				if($('#lowerbound' + i).val()==$('#lowerbound' + j).val())
				{
					$('#lowerbound' + i).val('');
					$('#lowerbound' + i).focus();
					showloadingalert("LowerBound Value is not unique");	
					setTimeout('closeloadingalert()',2000);
					return false;
				}
				if($('#higherbound' + i).val()==$('#higherbound' + j).val())
				{
					$('#higherbound' + i).val('');
					$('#higherbound' + i).focus();
					showloadingalert("HigherBound Value is not unique");	
					setTimeout('closeloadingalert()',2000);
					return false;
				}
			}
		}
		
		var lettergrade='';
		var lowerbound='';
		var higherbound='';
		var boxid='';
	
		for(i=1; i<=counter; i++)
		{
			boxid+=i+"~";
			lettergrade+= $('#lettergrade' + i).val()+"~";
			lowerbound+= $('#lowerbound' + i).val()+"~";
			higherbound+= $('#higherbound' + i).val()+"~";
		}
		
		lowerbound =  lowerbound.replace(/[^a-zA-Z 0-9 ~]+/g,'');
		higherbound =  higherbound.replace(/[^a-zA-Z 0-9 ~]+/g,'');
	
		$('#lg').val( lettergrade);
		$('#lb').val( lowerbound);
		$('#hb').val( higherbound);
		$('#boxid').val(boxid);
	
		if($('#grade').is(':checked')){
			var val=1;
		}	
		else{
			var val=0;
		}
	
		dataparam="oper=saveclass&classid="+classid+"&lettergrade="+$('#lg').val()+"&lowerbound="+$('#lb').val()+"&higherbound="+$('#hb').val()+"&classname="+escapestr($('#classname').val())+"&sdate1="+$('#sdate1').val()+"&edate1="+$('#edate1').val()+"&period="+$('#period').val()+"&term="+$('#term').val()+"&shedule="+$('#shedule').val()+"&boxid="+$('#boxid').val()+"&grade="+val+"&remove="+$('#removecounter').val()+"&tags="+escapestr($('#form_tags_newclass').val());
		
		$.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-classajax.php',
			data: dataparam,		
			beforeSend: function(){
				showloadingalert("Saving, please wait.");	
			},
			success:function(ajaxdata) {
				var ajaxdata = ajaxdata.split("~");	
				if(ajaxdata[0]=='success'){
					$('.lb-content').html("Saved Successfully");
					setTimeout("closeloadingalert();",500);						
					var val = ajaxdata[1];					
					if(flag==1)
					{						
						setTimeout("removesections('#home');",500);
						setTimeout('showpageswithpostmethod("class","class/class.php");',1000);
					}
					else
					{
						if($('#hidflag').val()!=1)
							setTimeout("removesections('#class');",500);
						else
							setTimeout("removesections('#class-newclass-actions');",500);
							setTimeout('showpageswithpostmethod("class-newclass-steps","class/newclass/class-newclass-steps.php","id='+val+'");',500);
					}
				}				
				else if(ajaxdata[0]=="fail")
				{
					$('.lb-content').html("Incorrect Data");
					setTimeout('closeloadingalert()',1000);
				}	
			}
		});
	}
}

/*---- Save Step-6
    fn_saveclassreview()
	Function to save a class final step
----*/
function fn_saveclassreview(classid,stepid)
{	
	var dataparam = "oper=saveclassreview"+"&classid="+classid;
	$.ajax({
		type: 'post',
		url: 'class/newclass/class-newclass-classajax.php',
		data: dataparam,
		beforeSend: function(){
			showloadingalert("Loading, please wait.");	
		},
		success:function(data) {
			var data = data.split("~");	
			if(data[0]=="success")
			{
				$('#dialog-message .alert-message').html("Saved");
				closeloadingalert();	
				setTimeout("removesections('#home');",500);
				setTimeout('showpages("class-class","class/class.php");',500);
			}
		}
	});
}

/*----
    fn_deleteclass()
	Function to delete subject details
----*/
function fn_deleteclass(id)
{	
	var dataparam = "oper=deleteclass"+"&classid="+id;
	$.Zebra_Dialog('Are you sure you want to delete this class ?',
	{
		'type':     'confirmation',
		'buttons':  [
						{caption: 'No', callback: function() { }},
						{caption: 'Yes', callback: function() { 
							$.ajax({
								type: 'post',
								url: 'class/newclass/class-newclass-classajax.php',
								data: dataparam,	
								beforeSend: function(){
									showloadingalert("Checking, please wait.");	
								},		
								success:function(data) {		
									if(data=="success")
									{
										$('#dialog-message .alert-message').html("Class deleted successfully");
										closeloadingalert();					
										setTimeout("removesections('#home');",500);
										setTimeout('showpages("class-class","class/class.php");',500);
									}
								}
							});	
						}},
					]
	});
}

/*------------------ Sigmath schedule start  -----------------------*/
																
/*---- Save Step-1&2
    fn_next()
	Function to save a schedule
----*/
function fn_savesigmath(sid,scheduletypeid)
{
	var dataparam = '';	
	if($("#sform").validate().form())
	{	
		dataparam="oper=savesigmathinformation&sid="+sid+"&sname="+$('#sname').val()+"&sdate="+$('#sdate').val()+"&edate="+$('#edate').val()+"&classid="+$('#hidclassid').val();			
		$.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-sigmath-ajax.php',
			data: dataparam,		
			beforeSend: function(){
				showloadingalert("Saving, please wait.");	
			},
			success:function(ajaxdata) {
				var ajaxdata = ajaxdata.split("~");	
				if(ajaxdata[0]=='success'){	
					$('#dialog-message .alert-message').html("Saved");
					closeloadingalert();
					
					var val = ajaxdata[1];	
					var classid=$('#hidclassid').val();
					setTimeout("removesections('#class-newclass-calendar');",500);	
					setTimeout('showpages("class-newclass-schedulesteps","class/newclass/class-newclass-schedulesteps.php?id='+val+","+scheduletypeid+","+classid+'");',500);
				}
			}
		});
	}
}

function fn_loadcontent(sid,flag)
{	
	var dataparam = '';	
	var courseid='';
	if(flag==1){
		var list2 = [];	
		$("div[id^=list2_]").each(function()
		{
			list2.push($(this).attr('id').replace('list2_',''));
		});		
	}
	var lid = $('#licenseid').val();		
	dataparam="oper=loadcontent&lid="+lid+"&sid="+sid+"&flag="+flag+"&courseids="+list2;		
	$.ajax({
		type: 'post',
		url: 'class/newclass/class-newclass-sigmath-ajax.php',
		data: dataparam,		
		beforeSend: function(){
			showloadingalert("Saving, please wait.");	
		},
		success:function(ajaxdata) {			
			var ajaxdata = ajaxdata.split("~");				
			if(ajaxdata[1]==1){
				
				$('#units').hide();
				$('#ipls').hide();
				$('#courses').show();
				$('#courses').html(ajaxdata[0]);
				var list2 = [];	
				$("div[id^=list2_]").each(function()
				{
					list2.push($(this).attr('id').replace('list2_',''));
				});
				if(list2!=''){
					fn_loadcontent(sid,1);
				}
			}
			else{
				if(flag!=1){
					$('#courses').hide();
					$('#ipls').hide();
				}
				$('#units').show();
				$('#units').html(ajaxdata[0]);				
			}
			closeloadingalert();
		}
	});	
}



function fn_btnchedk()
{
	var list6 = [];
	$("div[id^=list6_]").each(function()
	{
		list6.push($(this).attr('id').replace('list6_',''));
	});
	if(list6!=''){
		$('#cont_nxtstep').show();
	}
	else{
		$('#cont_nxtstep').hide();
	}
}
/*---- Save Step-4
    fn_savecontent()
	Function to save course, modules, units, lessons, activities
----*/
function fn_savecontent(sid,scheduletypeid)
{
	var list1 = [];
	var list2 = [];
	var list3 = [];
	var list4 = [];
	var list5 = [];
	var list6 = [];
	var list7 = [];
	var list8 = [];
	
	licenseid=$('#licenseid').val();
	
	if($('#hidcontenttype').val()==1){
		var list = document.getElementById ("list1");
		var liTags = list.getElementsByTagName ("div");
		for (var i = 0; i < liTags.length; i++) {
			list1.push((liTags[i].id).replace('list1_',''));
		}		
		
		$("div[id^=list2_]").each(function()
		{
			list2.push($(this).attr('id').replace('list2_',''));
		});
	}
	
	var list = document.getElementById ("list3");
	var liTags = list.getElementsByTagName ("div");
	for (var i = 0; i < liTags.length; i++) {
		list3.push((liTags[i].id).replace('list3_',''));
	}		
	
	$("div[id^=list4_]").each(function()
	{
		list4.push($(this).attr('id').replace('list4_',''));
	});		
		
	var list = document.getElementById ("list5");
	var liTags = list.getElementsByTagName ("div");
	for (var i = 0; i < liTags.length; i++) {
		list5.push((liTags[i].id).replace('list5_',''));
	}		
	
	$("div[id^=list6_]").each(function()
	{
		list6.push($(this).attr('id').replace('list6_',''));
	});

	var list = document.getElementById ("list7");
	var liTags = list.getElementsByTagName ("div");
	for (var i = 0; i < liTags.length; i++) {
		list7.push((liTags[i].id).replace('list7_',''));
	}		
	
	$("div[id^=list8_]").each(function()
	{
		list8.push($(this).attr('id').replace('list8_',''));
	});
	
	var dataparam = "oper=savecontent"+"&sid="+sid+"&list1="+list1+"&list2="+list2+"&list3="+list3+"&list4="+list4+"&list5="+list5+"&list6="+list6+"&list7="+list7+"&list8="+list8+"&licenseid="+licenseid;
	$.ajax({
		type: 'post',
		url: "class/newclass/class-newclass-sigmath-ajax.php",
		data: dataparam,		
		beforeSend: function(){
			showloadingalert("Saving, please wait.");	
		},
		success:function(data) {		
			if(data=="success")
			{					
				$('#dialog-message .alert-message').html("saved");
				closeloadingalert();						
				var classid=$('#hidclassid').val();	
				setTimeout("removesections('#class-newclass-calendar');",500);	
				setTimeout('showpages("class-newclass-schedulesteps","class/newclass/class-newclass-schedulesteps.php?id='+sid+","+scheduletypeid+","+classid+'");',500);					
			}
		}
	});		
}

function fn_savesigmathstudent(sid,scheduletypeid)
{
	var list9 = [];	
	var list10 = [];
	var list = document.getElementById ("list9");
	var liTags = list.getElementsByTagName ("div");
	for (var i = 0; i < liTags.length; i++) {
		list9.push((liTags[i].id).replace('list9_',''));
	}		
	
	$("div[id^=list10_]").each(function()
	{
		list10.push($(this).attr('id').replace('list10_',''));
	});
	
	if(list10 == ''){
		alert("Please select alteast one student");	
		return false;
	}
	
	var dataparam = "oper=savesigmathstudent"+"&sid="+sid+"&list9="+list9+"&list10="+list10;
	$.ajax({
		type: 'post',
		url: "class/newclass/class-newclass-sigmath-ajax.php",
		data: dataparam,		
		beforeSend: function(){
			showloadingalert("Saving, please wait.");	
		},
		success:function(data) {		
			if(data=="success")
			{					
				$('#dialog-message .alert-message').html("saved");
				closeloadingalert();						
				var classid=$('#hidclassid').val();	
				setTimeout("removesections('#class-newclass-calendar');",500);	
				setTimeout('showpages("class-newclass-schedulesteps","class/newclass/class-newclass-schedulesteps.php?id='+sid+","+scheduletypeid+","+classid+'");',500);	
			}
			else if(data=="fail"){
				alert("Student limit exceed....");
				closeloadingalert();
			}
		}
	});
}


function fn_changeshedule(sid)
{
	var id = $('#scheduletype').val();	
	if(id==1)
	{
		var oper="sigmathform";
		var url="class/newclass/class-newclass-sigmath-ajax.php";
	}
	else if(id==2)
	{
		var oper="rotationform";
		var url="class/newclass/class-newclass-rotation-ajax.php";	
	}
	else if(id==3)
	{
		var oper="dyadform";
		var url="class/newclass/class-newclass-dyad-ajax.php";	
	}
	else
	{
		var oper="triadform";
		var url="class/newclass/class-newclass-triad-ajax.php";	
	}
	
	var dataparam = "oper="+oper+"&sid="+sid;
	$.ajax({
		type: 'post',
		url: url,
		data: dataparam,		
		beforeSend: function(){
			showloadingalert("Loading, please wait.");	
		},
		success:function(data) {
			closeloadingalert();
			$('#scheduleform').html(data);
		}
	});			
}

function fn_reviewclass(id){	
	setTimeout("removesections('#class-newclass-steps');",500);			
	setTimeout('showpageswithpostmethod("class-newclass-review","class/newclass/class-newclass-review.php","id='+id+'");',500);
}

function fn_loadinstructions(sid){
	var dataparam = "oper=loadinstructions&id="+$('#scheduletype').val()+"&classid="+$('#hidclassid').val()+"&sid="+sid;
	if($('#scheduletype').val()!=1){
		$.ajax({
			type: 'post',
			url: "class/newclass/class-newclass-sigmath-ajax.php",
			data: dataparam,
			success:function(data) {
				$('#instructionstages').html(data);
				removesections('#class-newclass-newschedulestep');
			}
		});
	}
}

function fn_loadlicensecontent(sid){
	if($('#scheduletype').val()==1){
		fn_sigmathloadcontent($('#licenseid').val(),sid);	
	}
}


function fn_saveschedule(id){
	if(id==0)
		var msg = "Saving,";
	else
		var msg = "Updating,";
	if($("#scheduleform").validate().form())
	{
		var studenttype = $('#studenttype').val();
		var list3=[];
		var list4=[];
		var list5=[];
		var list6=[];
		var list7=[];
		var list8=[];
		var list9=[];
		var list10=[];
		var gradepoint=[];
		var gradeflag=[];
		
		if(studenttype==2){
			$("div[id^=list10_]").each(function()
			{
				list10.push($(this).attr('id').replace('list10_',''));
			});
			$("div[id^=list9_]").each(function()
			{
				list9.push($(this).attr('id').replace('list9_',''));
			});
			if(list10==''){
				alert("Please select any one of student for schedule.");
				return false;
			}
		}
		$("div[id^=list3_]").each(function()
		{
			list3.push($(this).attr('id').replace('list3_',''));
		});
		$("div[id^=list4_]").each(function()
		{
			list4.push($(this).attr('id').replace('list4_',''));
		});
		$("div[id^=list5_]").each(function()
		{
			list5.push($(this).attr('id').replace('list5_',''));
		});		
		
		$('#selectipl input[id^=ipl_]:checked').each(function() { 
			var lessonid=$(this).val();
			list6.push(lessonid);
			if($('#grade_'+lessonid).is(':checked')){
				var tmpgrade=1;
			}
			else{
				var tmpgrade=0;
			}
			gradepoint.push($('#gradevalue_'+lessonid).val());
			gradeflag.push(tmpgrade);      
		});	
		$("div[id^=list7_]").each(function()
		{
			list7.push($(this).attr('id').replace('list7_',''));
		});
		$("div[id^=list8_]").each(function()
		{
			list8.push($(this).attr('id').replace('list8_',''));
		});
		if(list4==''){
			alert("Please select any unit.");
			return false;
		}
		else if(list6==''){
			alert("Please select any lesson.");
			return false;
		}
		var sname = escapestr($('#sname').val());
		var dataparam = "oper=saveschedule&sid="+id+"&sname="+escapestr($('#sname').val())+"&startdate="+$('#startdate').val()+"&stype="+$('#scheduletype').val()+"&students="+list10+"&studenttype="+studenttype+"&classid="+$('#hidclassid').val()+"&list4="+list4+"&list6="+list6+"&list8="+list8+"&list3="+list3+"&list5="+list5+"&list7="+list7+"&licenseid="+$('#licenseid').val()+"&unstudents="+list9+"&gradeflag="+gradeflag+"&gradepoint="+gradepoint;									
		$.ajax({
			type:'post',
			url:'class/newclass/class-newclass-sigmath-ajax.php',
			data:dataparam,
			beforeSend: function(){
				showloadingalert(msg+" please wait.");	
			},	
			success: function(data){
				var data=data.split("~");
				if(data[0]=='success'){	
					$('.lb-content').html("Schedule saved successfully.");					
					removesections('#class-newclass-steps');
					removesections('#class-newclass-actions');						 		
					setTimeout('showpageswithpostmethod("class-newclass-scheduledetails","class/newclass/class-newclass-scheduledetails.php","id='+$('#hidclassid').val()+","+$('#classtypeval').val()+'");',200);
					setTimeout('showpageswithpostmethod("class-newclass-viewprogress","class/newclass/class-newclass-viewprogress.php","id='+data[1]+","+$('#hidclassid').val()+'");',2000);					
				}
				else if(data[0]=='fail'){
					$('.lb-content').html("student limit exceeds");					
				}
				else if(data[0]=='invalid'){
					$('.lb-content').html("Incorrect Data");					
				}
				setTimeout('closeloadingalert();',2000);	
			}
		});
	}	
}


function fn_sigmathloadcontent(lid,sid,flag)
{
	$('#schenddate').hide();			
	if(sid==0 || sid=='') //show the select template dropdown is when create new schecule
	{
		$('#stemplate').show();
		$('#triadtemplate,#dyadtemplate').hide();		
	}
		
	if(flag==1)//comes from  select template
		sid = $('#stemplateid').val();
					
	dataparam="oper=loadcontent&lid="+lid+"&sid="+sid+"&flag="+flag;	
	$.ajax({
		type: 'post',
		url: 'class/newclass/class-newclass-sigmath-ajax.php',
		data: dataparam,		
		beforeSend: function(){				
		},
		success:function(ajaxdata) {			
			$('#units').html(ajaxdata);				
			fn_orderipls(sid,lid,flag);		
		}
	});	
}


function fn_loadscheduletemplate(lid)
{
	$('#schenddate,#triadtemplate,#dyadtemplate,#stemplate').hide();
	$('#rotcontent,#units,#ipls,#instructionstages').html('');	
	var dataparam = "oper=loadscheduletemplate&licenseid="+$('#licenseid').val()+"&sid="+$('#hidscheduleid').val()+"&scheduletype="+$('#hidscheduletype').val();
	var trackid = $('#lic_'+$('#licenseid').val()).attr('name');	
	$.ajax({
		type: 'post',
		url: 'class/newclass/class-newclass-sigmath-ajax.php',
		data: dataparam,
		beforeSend: function(){				
		},
		success: function (data) {			
			$('#loadtemplate').html(data);
			fn_showavailable(trackid);					
		},
	});	
}

function fn_showavailable(trackid){
	var dataparam = "oper=showremainingusers&trackid="+trackid+"&licenseid="+$('#licenseid').val();
	$.ajax({
		type: 'post',
		url: 'class/newclass/class-newclass-sigmath-ajax.php',
		data: dataparam,
		beforeSend: function(){			
		},
		success: function (data) {	
			$('#remainusers').html(data);				
		},
	});	
}

function fn_indassesment(lid,sid)
{
	$('#schenddate').show();		
	dataparam="oper=indloadcontent&lid="+lid+"&sid="+sid+"&classid="+$('#hidclassid').val();		
	$.ajax({
		type: 'post',
		url: 'class/newclass/class-newclass-classajax.php',
		data: dataparam,		
		beforeSend: function(){			
		},
		success:function(ajaxdata) {			
			$('#rotcontent').html(ajaxdata);	
		}
	});	
}

function fn_indasloadmodules(scheduleid)
{
	var lid = $('#licenseid').val();		
	dataparam="oper=indasloadmodules&licenseid="+lid+"&scheduleid="+scheduleid+"&moduletype="+$('#moduletype').val();	 	
	$.ajax({
		type: 'post',
		url: 'class/newclass/class-newclass-classajax.php',
		data: dataparam,		
		beforeSend: function(){			
		},
		success:function(ajaxdata) {			
			
			$('#modules').html(ajaxdata);
			$('#modnxtstep').show();
		}
	});
} 

function fn_saveindassesment(sid)
{
	if($("#scheduleform").validate().form())
	{
		var list10 = [];
		var list9=[];
		
		$("div[id^=list9_]").each(function()
		{
			list9.push($(this).attr('id').replace('list9_',''));
		});
		
		$("div[id^=list10_]").each(function()
		{
			list10.push($(this).attr('id').replace('list10_',''));
		});
		
	
		if(list10=='' && $('#studenttype').val()==2)
		{
			$.Zebra_Dialog('<strong>Please select a student</strong>', {
			'buttons':  false,
			'auto_close': 3000
			});
			return false;
		}
		if($('#moduleid').val()==''){
			$.Zebra_Dialog('<strong>Please select a module</strong>', {
			'buttons':  false,
			'auto_close': 3000
			});
			return false;
		}
		var sname = escapestr($('#sname').val());		
		var dataparam="oper=saveindassesment&sname="+sname+"&startdate="+$('#startdate').val()+"&enddate="+$('#enddate').val()+"&scheduletype="+$('#scheduletype').val()+"&studenttype="+$('#studenttype').val()+"&sid="+sid+"&students="+list10+"&modules="+$('#moduleid').val()+"&classid="+$('#hidclassid').val()+"&licenseid="+$('#licenseid').val()+"&moduletype="+$('#moduletype').val()+"&unstudents="+list9+"&pagetitle="+$('#hidpagetitle').val()+"&points="+$('#hidpoints').val()+"&grades="+$('#hidgrades').val();
		
		$.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-classajax.php',
			data: dataparam,		
			beforeSend: function(){
				showloadingalert("Loading, please wait.");
			},
			success:function(data) {
				closeloadingalert();
				var data=data.split("~");
				$('#scheduleid').val(data[1]);
				var sid=$('#scheduleid').val();
				var classid=$('#hidclassid').val();								
				if(trim(data[0])=="success")
				{
					$('.lb-content').html("Saved Successfully");
					removesections('#class-newclass-steps');	
					removesections('#class-newclass-actions');			
					setTimeout('showpageswithpostmethod("class-newclass-scheduledetails","class/newclass/class-newclass-scheduledetails.php","id='+$('#hidclassid').val()+","+$('#classtypeval').val()+'");',100);
					setTimeout('showpageswithpostmethod("class-newclass-viewindprogress","class/newclass/class-newclass-viewindprogress.php","id='+data[1]+",5,"+$('#hidclassid').val()+'");',2500);
				   
				}
				else if(trim(data[0])=="fail")
				{
					$('.lb-content').html("Student limit exceed");					
				}
				else if(trim(data[0])=="invalid")
				{
					$('.lb-content').html("Invalid data");	
				}
			}
		});
	}
}

function fn_loadmodule(){
	$('#schenddate').hide();
	var stype = $('#scheduletype').val();
	var lid = $('#licenseid').val();
	var sid = $('#hidscheduletype').val();
	
	$('#rotcontent').html('');
	$('#units').html('');
	$('#ipls').html('');
	$('#rotcontent').html('');
	$('#instructionstages').html('');
	$('#tlab').hide('');
	$('#stemplate').hide();
	$('#triadtemplate').hide();
	$('#dyadtemplate').hide();
	
	removesections('#class-newclass-newschedulestep');
	
	if(stype==1)
	{
		fn_sigmathloadcontent(lid,sid);
	}
	else if(stype==2)
	{
		$('#schenddate').hide();
		fn_rotloadcontent(lid,sid,1);
	}
	else if(stype==6)
	{
		$('#schenddate').hide();
		fn_rotloadcontent(lid,sid,2);
	}
	else if(stype==3)
	{
		$('#schenddate').hide();
		$('#stemplate').hide();
		$('#triadtemplate').hide();
		$('#dyadtemplate').show();
		fn_dyadstage(sid,'ins',0);
	
	}
	else if(stype==4)
	{
		$('#schenddate').hide();
		$('#stemplate').hide();
		$('#triadtemplate').show();
		$('#dyadtemplate').hide();
		fn_triadstage(sid,'ins',0);
	}
	else if(stype==5)
	{
		$('#stemplate').hide();
		$('#triadtemplate').hide();
		$('#dyadtemplate').hide();
		fn_indassesment(lid,sid);
	}
}

function fn_classlock(id)
{
	if(trim($('#clockcontnet').html())=='unlock'){
		var flag=1;	
		var msg = 'Are you sure you want to lock this class? If you lock this class the student cannot access their assignments.';	
		$('#classloka').show();
	}
	else if(trim($('#clockcontnet').html())=='Lock'){
		var flag=0;	
		var msg = 'Are you sure you want to unlock this class? ';	
	}	
	var dataparam="oper=classlock&classid="+id+"&flag="+flag;	
	$.Zebra_Dialog(msg,
	{
		'type':     'confirmation',
		'buttons':  [
						{caption: 'No', callback: function() { }},
						{caption: 'Yes', callback: function() { 
							$.ajax({
								type: 'post',
								url: 'class/newclass/class-newclass-classajax.php',
								data: dataparam,	
								beforeSend: function(){
									showloadingalert("Loading, please wait.");	
								},		
								success:function(data) {		
									closeloadingalert();
									if(flag==1){
										$('#classlock').attr('class','icon-synergy-locked');
										$('#clockcontnet').html('Lock');
									}
									else if(flag==0){
										$('#classlock').attr('class','icon-synergy-unlocked');
										$('#clockcontnet').html('Unlock');
									}
								}
							});	
						}},
					]
	});
}

function fn_addstudent(){
	$.fancybox.showActivity();
	$.ajax({
		type	: "POST",
		cache	: false,
		url		: "class/newclass/class-newclass-classajax.php",
		data	: 'oper=createstudentform',	
		success: function(data) {			
			$.fancybox(data,{'width':450});			
		}
	});
	return false;
}

function fn_createstudent(flag){
	if($("#studentform").validate().form())
	{
		var dataparam="oper=savestudent&fname="+escapestr($('#fname').val())+"&lname="+escapestr($('#lname').val())+"&uname="+escapestr($('#uname').val())+"&password="+$('#password').val();		 	
		$.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-classajax.php',
			data: dataparam,		
			beforeSend: function(){				
			},
			success:function(ajaxdata) {				
				ajaxdata = ajaxdata.split('~');
				if(trim(ajaxdata[0])=='success'){
					$('#list3').append(ajaxdata[1]);
					$.Zebra_Dialog('<strong>Student was added successfully.</strong>', {
					'buttons':  false,
					'auto_close': 3000
					});	
					$.fancybox.close();
					if(flag==1)
					setTimeout('fn_addstudent()',3100);
				}
				else{
					$.Zebra_Dialog('<strong>Student creation failed.</strong>', {
					'buttons':  false,
					'auto_close': 3000
					});							
				}			
				
			}
		});
	}
}

function fn_finishclass(id)
{
	removesections('#home');
	showpages("class-class","class/class.php");
	setTimeout('showpageswithpostmethod("class-newclass-actions","class/newclass/class-newclass-actions.php","id='+id+'");',2000);
}

function fn_changeeventdate(type,sid,date,rotation,enddate,stageid)
{
	dataparam="oper=changeeventdate&type="+type+"&sid="+sid+"&date="+date+"&rotation="+rotation+"&enddate="+enddate+"&stageid="+stageid;
	
	$.ajax({
		type: 'post',
		url: 'class/newclass/class-newclass-classajax.php',
		data: dataparam,		
		beforeSend: function(){
			showloadingalert("Loading, please wait.");	
		},
		success:function(ajaxdata) {
				
			closeloadingalert();
			if(ajaxdata=="fail")
			{
				$.Zebra_Dialog('Students already attend this rotation so you can not modify the date.');
			}
			else
			{
			$.Zebra_Dialog('Event date has been changed.', {
					'buttons':  false,
					'auto_close': 2000
					});				
			}
		}
	});
}

function fn_orderipls(sid,lid,flag)
{	
	var list4 = [];	
	$("div[id^=list4_]").each(function()
	{
		list4.push($(this).attr('id').replace('list4_',''));
	});	
	dataparam="oper=loadorderipl&sid="+sid+"&unitids="+list4+"&lid="+lid+"&flag="+flag;		
	$.ajax({
		type: 'post',
		url: 'class/newclass/class-newclass-sigmath-ajax.php',
		data: dataparam,		
		beforeSend: function(){			
		},
		success:function(ajaxdata) {							
			$('#ipls').html(ajaxdata);
		}
	});
}