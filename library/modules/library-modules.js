/*
	Created By - Muthukumar. D
	Page - library-modules.js
	
	History:


*/
/*----
    fn_createmodule()
	Function to save/update module details
	id -> Module id.
----*/
function fn_createmodule(id)
{
        if($('#hiduploadfile').val()=='') //Works if the module content not uploaded/inprogress.
        {
            showloadingalert("Please Upload Module Content");
            setTimeout('closeloadingalert()',500);
            return false;
        }

	if($("#moduleforms").validate().form()) //Validates the Module Form
	{

            var moduledescription = '';
		
		
			moduledescription = encodeURIComponent(tinymce.get('moduledescription').getContent().replace(/tiny_mce\//g,""));
			$('#moduledescription').html('');
		
           var list10=[];
                
              $("div[id^=list10_]").each(function()
               {
		list10.push($(this).attr('id').replace('list10_',''));
               });	
                
                
                
		var dataparam = "oper=changemodulename&modname="+escapestr($('#txtmodname').val())+"&modid="+id+"&modversion="+$('#selectversion').val();
		$.ajax({
			type: "POST",
			url: "library/modules/library-modules-playerajax.php",
			data: dataparam,
			async: false,
			success:function(data) { 
				if(data=='fail')
				{
					showloadingalert("Either change the module name or module version number.");	
					setTimeout('closeloadingalert()',2000);
					return false;
				}
				else if(data=='success')
				{
					
                                        if(id!='undefined' && id!='0' && id!=''){ //Works in Editing module
						actionmsg = "Updating";
						alertmsg = "Module has been Updated Successfully"; 
					}
					else { //Works in Creating a New Module
						actionmsg = "Saving";
						alertmsg = "Module has been Created Successfully"; 
					}
                                        
                                      
                                
                                
                                
					//sending the details module name, phase of yhe module, module description, minutes, days, version, uploaded modulename(filename), moduleid, tags to save the module in dataparam along with the oper 'savemodule'.
					var dataparam = "oper=savemodule&modname="+escapestr($('#txtmodname').val())+"&modphase="+$('#selectphase').val()+"&modminutes="+$('#txtmodminutes').val()+"&moddays="+$('#txtmoddays').val()+"&modversion="+$('#selectversion').val()+"&filename="+$('#hiduploadfile').val()+"&editid="+id+"&tags="+escapestr($('#form_tags_mod').val())+"&assetid="+escapestr($('#txtassetid').val())+"&moduletype="+$('#moduletypes').val()+"&performance="+$('#performance').val()+"&points="+$('#points').val()+"&quesid="+$('#quesid').val()+"&ansid="+$('#ansid').val()+"&anstext="+escapestr($('#anstext').val())+"&correct="+$('#correct').val()+"&sectiontitle="+$('#sectiontitle').val()+"&attenpoints="+$('#attenpoints').val()+"&partipoints="+$('#partipoints').val()+"&pagecnt="+$('#pagecnt').val()+"&pagetitles="+$('#hidtitle').val()+"&pagegrades="+$('#hidgrade').val()+"&qcount="+$('#hidqcount').val()+"&list10="+list10+"&lid="+$('#draglinkleft').val()+"&moduledescription="+moduledescription;
					
					$.ajax({
						url: "library/modules/library-modules-playerajax.php",
						data: dataparam,
						type: "POST",
						beforeSend: function(){
							showloadingalert(actionmsg+", please wait.");	
						},
						success: function (data) {	
							if(data=="success") //Works if the data saved in db
							{
								$('.lb-content').html(alertmsg);
								setTimeout('closeloadingalert()',500);
								
								setTimeout('removesections("#library");',1000);
								setTimeout('showpages("library-modules","library/modules/library-modules.php");',1000);
							}
							else
							{
								$('.lb-content').html("Invalid data So it cannot update");
								setTimeout('closeloadingalert()',1000);
								
								
							}
						},
					});
				}
			}
		});
	}
}

/*----
    fn_deletemodule()
	Function to delete module
	id -> Module id.
----*/
function fn_deletemodule(id)
{
	$.Zebra_Dialog('Are you sure you want to delete?',
	{
		'type': 'confirmation',
		'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {
				
				var dataparam = "oper=deletemodule"+"&modid="+id;
				$.ajax({
                                        type:"POST",
					url: "library/modules/library-modules-playerajax.php",
					data: dataparam,
					beforeSend: function(){
						showloadingalert("Checking, please wait.");	
					},	
					success: function (ajaxdata) {	
						if(ajaxdata=="success") //Works if Module Deleted
						{
							$('.lb-content').html("Module has been Deleted Successfully");
							setTimeout('closeloadingalert()',500);
							
							setTimeout('removesections("#library");',1000);
							setTimeout('showpages("library-modules","library/modules/library-modules.php");',1000);
						}
						else if(ajaxdata=="exists") //Works if Module is Assigned
						{
							$('.lb-content').html("You can't delete this module as it is in use");
                                                        setTimeout('closeloadingalert()',2000);
                                                        
						}
						else if(ajaxdata=="mathexists") //Works if Module is Assigned
						{
							
							$('.lb-content').html("You can't delete this module as it is assigned to mathmodule");
                                                        setTimeout('closeloadingalert()',2000);
						}
						else
						{
							$('.lb-content').html("Deleting has been Failed"); //Works if the process fails in query.
							setTimeout('closeloadingalert()',500);
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
function fn_changemodulename(version,modid)
{
	var dataparam = "oper=changemodulefilename&version="+version+"&modid="+modid;
	$.ajax({
		type: 'post',
		url: "library/modules/library-modules-playerajax.php",
		data: dataparam,
		beforeSend: function(){
		},
		success:function(data) { 
			$(".profile-preview").html(data);
                        $('#hiduploadfile').val(data);
		}
	});
}

function fn_checkversion(modid)
{
	var dataparam = "oper=changemodulename&modname="+escapestr($('#txtmodname').val())+"&modid="+modid;
	$.ajax({
		type: 'post',
		url: "library/modules/library-modules-playerajax.php",
		data: dataparam,
		beforeSend: function(){
		},
		success:function(data) { 
			$("#checkversions").html(data);
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
	//alert(dataparam);
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
	
	
	$("input[id^=grade_]").each(function()
	{
            var gid = $(this).attr('id');
            var gid1=gid.split("grade_");
		var newid = $(this).attr('name');
		var cval=0;
		if($('#grade_'+newid).is(':checked')){
			cval=1;
		}
		grade.push(cval);
                /**********************************************/
                $("input[id^=point_]").each(function()
                {
                    var pid = $(this).attr('id');
                    var pid1=pid.split("point_");
                    if($(this).val()=='' && pid1[1]==gid1[1] && cval==1)
                    {
                        $.Zebra_Dialog("Please enter points");
                        return false();
                    }
                    if(point=='')
                    {
                        if($(this).val()=='')
                        {
                            point='0';
                        }
                        else
                        {
                            point = $(this).val();
                        }
                    }
                    else
                    {
                            point = point+"~"+$(this).val();
                    }
	});
                /***************************************************/
	});
	var dataparam = "oper=savegrade&moduleid="+moduleid+"&pagetitle="+title+"&points="+point+"&grades="+grade+"&wcasess="+wcasess+"&wcapage="+wcapage;
	$.ajax({
		type: 'post',
		url: 'library/modules/library-modules-playerajax.php',
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
				
				setTimeout('removesections("#library-modules");',1000);
				setTimeout('showpageswithpostmethod("library-modules-actions","library/modules/library-modules-actions.php","id='+moduleid+'");',1000);
			}
			else
			{
				$('.lb-content').html("Invalid data");
				setTimeout('closeloadingalert()',500);
			}
		}
	});
}