/*
	Created By - Muthukumar. D
	Page - library-mathmodules.js
	
	History:


*/

/*----
    fn_showsessday2()
	Function to load session day2 dropdown
	id - session day1 id
----*/
function fn_showsessday2(id)
{
	var dataparam = "oper=showsessday2&sessday1id="+id;
	
	$.ajax({
		type: 'post',
		url: "library/mathmodules/library-mathmodules-ajax.php",
		data: dataparam,
		beforeSend: function(){
			$('#dsessday2').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},		
		success:function(data) {
			$('#dsessday2').html(data);	
			if($("#sessid").attr('class')=='field row error')
				$('#sessday2').valid();
		}
	});
}



/*----
    fn_showiplday2()
	Function to load ipl day2 dropdown
	id - ipl day1 id
----*/
function fn_showiplday2(id,val1,val2,val3,val4)
{
	var ipl2ids = val1+","+val2+","+val3+","+val4;

	var dataparam = "oper=showiplday2&iplday1id="+id+"&ipl2ids="+ipl2ids;
	
	$.ajax({
		type: 'post',
		url: "library/mathmodules/library-mathmodules-ajax.php",
		data: dataparam,
		beforeSend: function(){
			$('#list8').html('<img src="img/AjaxLoader.gif" width="50" height="50" border="0" style="margin-top:150px; margin-left:130px;"/>'); 	
		},		
		success:function(data) {
			$('#list8').html(data);	
		}
	});
}



/*----
    fn_createmodule()
	Function to save/update module details
	id -> Module id.
----*/
function fn_createmathmodule(id)
{
	if($("#mathmoduleforms").validate().form()) //Validates the Module Form
	{
               
                var mathmoduledescription = '';
		
		
			mathmoduledescription = encodeURIComponent(tinymce.get('mathmoduledescription').getContent().replace(/tiny_mce\//g,""));
			$('#mathmoduledescription').html('');
        
        
        
        
        
		var cnt=0;
                var list10=[];
		$("div[id^='chk_']").each(function() {
		if($(this).hasClass('checkedokmod')) {
				cnt++;				
			}
		});		
		
		if(cnt<4) 
		{
			showloadingalert("Please Select Four IPLs for Diagnostic Day1");	
			setTimeout('closeloadingalert()',1000);
			return false;
		}
		
		var cnt1=0;
		$("div[id^='chk1_']").each(function() {
		if($(this).hasClass('checkedokmod')) {
				cnt1++;				
			}
		});		
		
		if(cnt1<4) 
		{
			showloadingalert("Please Select Four IPLs for Diagnostic Day2");	
			setTimeout('closeloadingalert()',1000);
			return false;
		}
		
                 $("div[id^=list10_]").each(function()
                     {
                            list10.push($(this).attr('id').replace('list10_',''));
                     });
		
                
                
		if(id!=0){ //Works in Editing module
			actionmsg = "Updating";
			alertmsg = "Math Module has been Updated Successfully"; 
		}
		else { //Works in Creating a New Module
			actionmsg = "Saving";
			alertmsg = "Math Module has been Created Successfully"; 
		}
		
                
                
                
		//sending the details module name, phase of the module, module description, minutes, days, version, uploaded modulename(filename), moduleid, tags to save the module in dataparam along with the oper 'savemodule'.
		
		var dataparam = "oper=savemathmodule&mathmodname="+escapestr($('#txtmathmodname').val())+"&mouledid="+$('#selectmodule').val()+"&modphase="+$('#selectphase').val()+"&sessday1="+$('#sessday1').val()+"&modminutes="+$('#txtmodminutes').val()+"&moddays="+$('#txtmoddays').val()+"&sessday2="+$('#sessday2').val()+"&iplday1="+$('#iplday1').val()+"&iplday2="+$('#iplday2').val()+"&editid="+id+"&tags="+escapestr($('#form_tags_mathmod').val())+"&list10="+list10+"&mathmoduledescription="+mathmoduledescription;
		
		$.ajax({
			type:"POST",
			url: "library/mathmodules/library-mathmodules-ajax.php",
			data: dataparam,
			beforeSend: function(){
				showloadingalert(actionmsg+", please wait.");	
			},
			success: function (data) {	
				if(data=="success") //Works if the data saved in db
				{
					$('.lb-content').html(alertmsg);
					setTimeout('closeloadingalert()',1000);
					
					setTimeout('removesections("#library");',500);
					setTimeout('showpages("library-mathmodules","library/mathmodules/library-mathmodules.php");',500);
				}
				else if(data=="fail")
				{
					$('.lb-content').html("Incorrect Data");
					setTimeout('closeloadingalert()',1000);
				}
			},
		});
	}
}

/*----
    fn_deletemodule()
	Function to delete module
	id -> Module id.
----*/
function fn_deletemathmodule(id)
{
	$.Zebra_Dialog('Are you sure you want to delete?',
	{
		'type': 'confirmation',
		'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {
				var dataparam = "oper=deletemathmodule&mathmoduleid="+id;
				$.ajax({
					type:"POST",
					url: "library/mathmodules/library-mathmodules-ajax.php",
					data: dataparam,
					beforeSend: function(){
						showloadingalert("Checking, please wait.");	
					},	
					success: function (ajaxdata) {	
						if(ajaxdata=="success") //Works if Module Deleted
						{
							$('.lb-content').html("Math Module has been Deleted Successfully");
							setTimeout('closeloadingalert()',1000);
							
							setTimeout('removesections("#library");',500);
							setTimeout('showpages("library-mathmodules","library/mathmodules/library-mathmodules.php");',500);
						}
						else if(ajaxdata=="exists") //Works if Module is Assigned
						{
							
							$('.lb-content').html("You can't delete this module as it is in use");
                                                        setTimeout('closeloadingalert()',2000);
						}
						else
						{
							$('.lb-content').html("Deleting has been Failed"); //Works if the process fails in query.
							setTimeout('closeloadingalert()',2000);
						}
                                                
					},
				});
			}
		}]
	});
}


/*----
    fn_changemodulename()
	Function to change module name acc. to version
	version -> Module version.
----*/
function fn_changemodulename(version)
{
	var dataparam = "oper=changemodulename&modid="+$('#hid_moduleid').val()+"&version="+version;
	$.ajax({
		type: 'post',
		url: "library/modules/library-modules-playerajax.php",
		data: dataparam,
		success:function(data) { //Loads the Unit details in the dropdown
			$("#fineUploader span.qq-upload-file").html(data);
		}
	});
}


/*--- FRE ---*/
/*----
    getCurrentUserName()
	Function to print the user name.
	0 -> First Student, 1 -> Second Student, -1 -> Both Student
----*/
function getCurrentUserName(index)
{
	if(index==0)
		document.write($('#hidfullname').val());
	if(index==1)
		document.write($('#hidfullname1').val());
	if(index==-1)
		document.write($('#hidfullname').val()+","+$('#hidfullname1').val());
}

/*----
    getClassInfoStr()
----*/
function getClassInfoStr()
{
	document.write();
}

/*----
    getUserVar()
	Function to print the values for the particular variable
----*/
function getUserVar(variable)
{
	var dataparam = "oper=printvariables&scheduleid="+$('#hidscheduleid').val()+"&moduleid="+$('#hidmoduleid').val()+"&testerid="+$('#hidtesterid').val()+"&testerid1="+$('#hidtesterid1').val()+"&printvariable="+variable;

	$.ajax({
		type: 'post',
		url: '../../library/modules/library-modules-playerajax.php',
		data: dataparam,
		async:false,
		success:function(ajaxdata) {
			document.write(ajaxdata);
		}
	});
}

/*----
    savegrade()
	Function to update the grade values for the particular module
----*/
function savegrade(moduleid)
{
	var title = '';
	var point = '';
	var wcasess = '';
	var wcapage = '';
	var grade = [];
	
	$("label[id^=wca_]").each(function()
	{
		if(title=='')
		{
			title = encodeURIComponent($(this).html());
		}
		else
		{
			title = title+"~"+encodeURIComponent($(this).html());
		}
		var wcaids = $(this).attr('id').split('#');
		if(wcasess=='')
		{
			wcasess = wcaids[1];
			wcapage = wcaids[2];
		}
		else
		{
			wcasess = wcasess+"~"+wcaids[1];
			wcapage = wcapage+"~"+wcaids[2];
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
		
	var dataparam = "oper=savegrade&moduleid="+moduleid+"&pagetitle="+title+"&points="+point+"&grades="+grade+"&wcasess="+wcasess+"&wcapage="+wcapage;
	
	$.ajax({
		type: 'post',
		url: 'library/mathmodules/library-mathmodules-ajax.php',
		data: dataparam,
		async:false,
		beforeSend: function(){
			showloadingalert("Loading, please wait.");	
		},	
		success:function(ajaxdata) {
			if(ajaxdata=="success")
			{
				$('.lb-content').html("Saved Successfully");
				setTimeout('closeloadingalert()',500);
				
				setTimeout('removesections("#library-mathmodules");',1000);
				setTimeout('showpageswithpostmethod("library-mathmodules-actions","library/mathmodules/library-mathmodules-actions.php","id='+moduleid+'");',1000);
			}
			else
			{
				$('.lb-content').html("Invalid data");
				setTimeout('closeloadingalert()',500);
			}
		}
	});
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
	
	if(leftlist=="list5" || leftlist=="list6" && rightlist=="list6" || rightlist=="list5"  )
	{
		fn_checking();
	}
}
