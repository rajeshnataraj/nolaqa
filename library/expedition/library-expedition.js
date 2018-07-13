/*
	Created By - Muthukumar. D
	Page - library-modules.js
	
	History:


*/

function fn_toggledigitallogbook(expid){
    var toggle_digital_logbook_status = 'enabled';
    if ($("#radio_toggle_digital_logbook_enabled").attr("checked") != "checked"){
        toggle_digital_logbook_status = 'disabled';
    }


    var dataparam = "oper=toggledigitallogbook&expid=" + expid + "&resstatus=" + toggle_digital_logbook_status;

    $.ajax({
                type: 'post',
                url: "library/expedition/library-expedition-ajax.php",
                data: dataparam,
                beforeSend: function(){
                        showloadingalert("Loading, please wait.");
                },
                success: function (data) {
                        if(data=="success") //Works if the data saved in db
                        {
                                $('.lb-content').html("Saved Successfully.");
                                setTimeout('closeloadingalert()',500);
                                setTimeout('removesections("#library-expedition-actions");',1000);
                        }
                        else if (data.substring(0,5) == "Error"){
                                $('.lb-content').html(data);
                                setTimeout('closeloadingalert()',1000);
                        }
                        else
                        {
                                $('.lb-content').html("Invalid data");
                                setTimeout('closeloadingalert()',1000);
                        }
                }
        });
}


/*----
    fn_createexpedition()
	Function to save/update expedition details
	id -> Module id.
----*/
function fn_createexpedition(id)
{	
        
        var expdescription = '';
		
		
			expdescription = encodeURIComponent(tinymce.get('expdescription').getContent().replace(/tiny_mce\//g,""));
			$('#expdescription').html('');
        
        
        
                 var list10=[];
                
                $("div[id^=list10_]").each(function()
                   {
                      list10.push($(this).attr('id').replace('list10_',''));
                   });	

        
		if($('#hiduploadfile').val()=='') //Works if the expedition content not uploaded/inprogress.
		{
			showloadingalert("Please Upload Expedition Content");	
			setTimeout('closeloadingalert()',500);
			return false;
		}
                
               
                    if(id!='undefined' && id!=0 && id!=''){ //Works in Editing module
			actionmsg = "Updating";
			alertmsg = "Expedition has been Updated Successfully"; 
		}
		else { //Works in Creating a New Module
			actionmsg = "Saving";
			alertmsg = "Expedition has been Created Successfully"; 
		}
               
		//sending the details expedition name, version, uploaded expeditionname(filename), expeditionid, tags to save the expedition in dataparam along with the oper 'saveexpedition'.		
		
		var dataparam = "oper=saveexpedition&expname="+$('#txtexpname').val()+"&expversion="+$('#selectversion').val()+"&filename="+$('#hiduploadfile').val()+"&editid="+id+"&tags="+$('#form_tags_exp').val()+"&assetid="+$('#txtexpassetid').val()+"&expuiid="+$('#selectui').val()+"&flag="+$('#hidflag').val()+"&list10="+list10+"&expdescription="+expdescription;
		$.ajax({
			url: "library/expedition/library-expedition-ajax.php",
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
					
					setTimeout('removesections("#library-expeditions");',1000);
					setTimeout('showpages("library-expedition","library/expedition/library-expedition.php");',1000);
				}
				else
				{
					$('.lb-content').html("Invalid data So it cannot update");
					setTimeout('closeloadingalert()',1000);
				}
			},
		});
	//}
}

/*----
    fn_deleteexpedition()
	Function to delete expedition
	id -> Expedition id.
----*/
function fn_deleteexpedition(id)
{
	$.Zebra_Dialog('Are you sure you want to delete?',
	{
		'type': 'confirmation',
		'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {
				
				var dataparam = "oper=deleteexpedition&expid="+id;
				$.ajax({
					url: "library/expedition/library-expedition-ajax.php",
					data: dataparam,
                                        type: "POST",
					beforeSend: function(){
						showloadingalert("Checking, please wait.");	
					},	
					success: function (ajaxdata) {						
						if(ajaxdata=="success") //Works if Expedition Deleted
						{
							$('.lb-content').html("Expedition has been Deleted Successfully");
							setTimeout('closeloadingalert()',500);
							
							setTimeout('removesections("#library");',1000);
							setTimeout('showpages("library-expedition","library/expedition/library-expedition.php");',1000);
						}
						else if(ajaxdata=="exists") //Works if Expedition is Assigned
						{
							closeloadingalert();
							$.Zebra_Dialog("You can't delete this expedition as it is in use", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
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
    fn_changeexpeditionname()
	Function to change expedition name acc. to version
	version -> Expedition version.
----*/
function fn_changeexpeditionname(expid)
{
	var dataparam = "oper=changeexpeditionname&expname="+escapestr($('#txtexpname').val())+"&expd="+expid;
	$.ajax({
		type: 'post',
		url: "library/expedition/library-expedition-ajax.php",
		data: dataparam,
		beforeSend: function(){
		},
		success:function(data) { 
			$("#checkversions").html(data);
		}
	});
}

function fn_changeorder(variable,resid,changeresid,totalcnt,type)
{
	var oldresid = $('#select'+variable+resid).val();
	var typename = '';
	if(type=='dest')
		typename = "Destination";
	else if(type=='task')
		typename = "Task";
	else if(type=='res')
		typename = "Resource";
	for(i=0;i<totalcnt-1;i++)
	{
		var newresid = $('#select'+variable+i).val();
		
		if(changeresid == newresid)	
		{	
			$('#select'+variable+i).val(0);
			$("a[id^="+variable+"]").each(function() {
				var currentres = $(this).attr('id').replace(variable,'');
				$('#'+type+"_"+i).html('Select The Next '+typename);
				if($(this).attr('data-option')==newresid && currentres != resid)
					$(this).hide();	
				else if($(this).attr('data-option')==oldresid && i != resid)
					$(this).show();
			});
		}
	}
}

function fn_saveorder(expid)
{
	var destid = '';
	var nextdestid = '';
	var taskid = '';
	var nexttaskid = '';
	var resid = '';
	var nextresid = '';
	var destflag = 0;
	var taskflag = 0;
	var resflag = 0;
	
	$("input[id^=selectnextdest_]").each(function()
	{
		if($(this).val()==0)
		{
			destflag = 1;
		}
		
		if(destid=='')
		{
			destid = $(this).attr('name').replace('selectnextdest_','');
			nextdestid = $(this).val();
		}
		else
		{
			destid = destid+"~"+$(this).attr('name').replace('selectnextdest_','');
			nextdestid = nextdestid+"~"+$(this).val();
		}
	});
	
	$("input[id^=selectnexttask_]").each(function()
	{
		if($(this).val()==0)
		{
			taskflag = 1;
		}
		
		if(taskid=='')
		{
			taskid = $(this).attr('name').replace('selectnexttask_','');
			nexttaskid = $(this).val();
		}
		else
		{
			taskid = taskid+"~"+$(this).attr('name').replace('selectnexttask_','');
			nexttaskid = nexttaskid+"~"+$(this).val();
		}
	});
	
	$("input[id^=selectnextres_]").each(function()
	{
		if($(this).val()==0)
		{
			resflag = 1;
		}
		
		if(resid=='')
		{
			resid = $(this).attr('name').replace('selectnextres_','');
			nextresid = $(this).val();
		}
		else
		{
			resid = resid+"~"+$(this).attr('name').replace('selectnextres_','');
			nextresid = nextresid+"~"+$(this).val();
		}
	});
	
	if(destflag==1)
	{
		showloadingalert("Select All The Destinations.");	
		setTimeout('closeloadingalert()',2000);
		return false;
	}
	else if(taskflag==1)
	{
		showloadingalert("Select All The Task.");	
		setTimeout('closeloadingalert()',2000);
		return false;
	}
	else if(resflag==1)
	{
		showloadingalert("Select All The Resource.");	
		setTimeout('closeloadingalert()',2000);
		return false;
	}
	else
	{		
		var dataparam = "oper=saveorder&destid="+destid+"&nextdestid="+nextdestid+"&taskid="+taskid+"&nexttaskid="+nexttaskid+"&resid="+resid+"&nextresid="+nextresid;
		$.ajax({
			type: 'post',
			url: "library/expedition/library-expedition-ajax.php",
			data: dataparam,
			beforeSend: function(){
				showloadingalert("Loading, please wait.");	
			},
			success: function (data) {	
				if(data=="success") //Works if the data saved in db
				{
					$('.lb-content').html("Saved Successfully.");
					setTimeout('closeloadingalert()',500);
					
					setTimeout('removesections("#library");',1000);
					setTimeout('showpages("library-expedition","library/expedition/library-expedition.php");',1000);
				}
				else
				{
					$('.lb-content').html("Invalid data");
					setTimeout('closeloadingalert()',1000);
				}
			},
		});
	}
}

function fn_saveresstatus(expid)
{
	var destid = '';
	var deststatus = '';
	var taskid = '';
	var taskstatus = '';
	var resid = '';
	var resstatus = '';
	
	$("input[name^=radiodest_]").each(function()
	{
                if($(this).attr('checked')=='checked')
                {
                    if(destid=='')
                    {
                            destid = $(this).attr('name').replace('radiodest_','');
                            deststatus = $(this).val();
                    }
                    else
                    {
                            destid = destid+"~"+$(this).attr('name').replace('radiodest_','');
                            deststatus = deststatus+"~"+$(this).val();
                    }
                }
	});
        
        $("input[name^=radiotask_]:checked").each(function()
	{
            var tasktemp = $(this).attr('name').replace('radiotask_','').split("_");
            if(taskid==''){
                taskid = tasktemp[1];
                taskstatus = $(this).val();
            }
            else {
                taskid = taskid+"~"+tasktemp[1];
                taskstatus = taskstatus+"~"+$(this).val();
            }
	});
        
        $("input[name^=radiores_]:checked").each(function()
	{
            var restemp = $(this).attr('name').replace('radiores_','').split("_");
                    if(resid=='')
                    {
                        resid = restemp[2];
                        resstatus = $(this).val();
                    }
                    else
                    {
                        resid = resid+"~"+restemp[2];
                        resstatus = resstatus+"~"+$(this).val();
                    }
               
	});
	
        var dataparam = "oper=savestatus&expid="+expid+"&destid="+destid+"&deststatus="+deststatus+"&taskid="+taskid+"&taskstatus="+taskstatus+"&resid="+resid+"&resstatus="+resstatus;
        $.ajax({
                type: 'post',
                url: "library/expedition/library-expedition-ajax.php",
                data: dataparam,
                beforeSend: function(){
                        showloadingalert("Loading, please wait.");	
                },
                success: function (data) {	
                        if(data=="success") //Works if the data saved in db
                        {
                            $('.lb-content').html("Saved Successfully.");
                            setTimeout('closeloadingalert()',500);

                            setTimeout('removesections("#library-expedition-actions");',1000);
                            setTimeout('showpageswithpostmethod("library-expedition-toggle","library/expedition/library-expedition-toggle.php","id='+expid+'");',1000);
                        }
                        else
                        {
                            $('.lb-content').html("Invalid data");
                            setTimeout('closeloadingalert()',1000);
                        }
                },
        });
}

function fn_resetdft(expid,tuid,schid,indid){
    var dataparam = "oper=resetdft&expid="+expid+"&uid="+tuid+"&schid="+schid+"&indid="+indid;
        $.ajax({
                type: 'post',
                url: "library/expedition/library-expedition-ajax.php",
                data: dataparam,
                beforeSend: function(){
                        showloadingalert("Loading, please wait.");	
                },
                success: function (data) {
                        if(data=="success") //Works if the data saved in db
                        {
                            $('.lb-content').html("Saved Successfully.");
                            setTimeout('closeloadingalert()',500);
        
                            setTimeout('removesections("#library-expedition-actions");',1000);
                            setTimeout('showpageswithpostmethod("library-expedition-toggle","library/expedition/library-expedition-toggle.php","id='+expid+'");',1000);
                        }
                        else
                        {
                            $('.lb-content').html("Invalid data");
                            setTimeout('closeloadingalert()',1000);
                        }
                },
        });
}
        
function fn_savetoggleassesment(expid)
{
        var expeditionid='';
        var expstatus='';
        var destid = '';
	var deststatus = '';
	var taskid = '';
	var taskstatus = '';
	var resid = '';
	var resstatus = '';
	
        $("input[name^=radioexp_]").each(function()
	{
                if($(this).attr('checked')=='checked')
                {
                    if(expeditionid=='')
                    {
                            expeditionid = $(this).attr('name').replace('radioexp_','');
                            expstatus = $(this).val();
                    }
                    else
                    {
                            expeditionid = expeditionid+"~"+$(this).attr('name').replace('radioexp_','');
                            expstatus = expstatus+"~"+$(this).val();
                    }
                }
	});
        
	$("input[name^=radiodest_]").each(function()
	{
                if($(this).attr('checked')=='checked')
                {
                    if(destid=='')
                    {
                            destid = $(this).attr('name').replace('radiodest_','');
                            deststatus = $(this).val();
                    }
                    else
                    {
                            destid = destid+"~"+$(this).attr('name').replace('radiodest_','');
                            deststatus = deststatus+"~"+$(this).val();
                    }
                }
	});
        
        $("input[name^=radiotask_]:checked").each(function()
	{
             if($(this).attr('checked')=='checked')
             {
                    if(taskid==''){
                            taskid = $(this).attr('name').replace('radiotask_','');
                            taskstatus = $(this).val();
                    }
                    else {
                            taskid = taskid+"~"+$(this).attr('name').replace('radiotask_','');
                            taskstatus = taskstatus+"~"+$(this).val();
                    }
             }
	});
        
        $("input[name^=radiores_]:checked").each(function()
	{
            if($(this).attr('checked')=='checked')
            {
                if(resid=='')
                 {
                         resid = $(this).attr('name').replace('radiores_','');
                         resstatus = $(this).val();
                 }
                 else
                 {
                         resid = resid+"~"+$(this).attr('name').replace('radiores_','');
                         resstatus = resstatus+"~"+$(this).val();
                 }
             }
               
	});	
	
        var dataparam = "oper=savetoggleassesment&expid="+expid+"&expeditionid="+expeditionid+"&expstatus="+expstatus+"&destid="+destid+"&deststatus="+deststatus+"&taskid="+taskid+"&taskstatus="+taskstatus+"&resid="+resid+"&resstatus="+resstatus;
       
        $.ajax({
                type: 'post',
                url: "library/expedition/library-expedition-ajax.php",
                data: dataparam,
                beforeSend: function(){
                        showloadingalert("Loading, please wait.");	
                },
                success: function (data) {	
                        if(data=="success") //Works if the data saved in db
                        {
                                $('.lb-content').html("Saved Successfully.");
                                setTimeout('closeloadingalert()',500);

                                setTimeout('removesections("#library-expedition-actions");',1000);
                                setTimeout('showpageswithpostmethod("library-expedition-toggleassessment","library/expedition/library-expedition-toggleassessment.php","id='+expid+'");',1000);
                        }
                        else
                        {
                                $('.lb-content').html("Invalid data");
                                setTimeout('closeloadingalert()',1000);
                        }
                },
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
	
	
}
function fn_savecontenttagstatus(expid){
    
    var destid = '';
	var deststatus = '';
	var taskid = '';
	var taskstatus = '';
	var resid = '';
	var resstatus = '';
	
	$("input[id^=form_mytags_desti_]").each(function()
	{
                   if(destid=='')
                    {
                            destid = $(this).attr('id').replace('form_mytags_desti_','');
                            deststatus = $(this).val();
                             
                    }
                    else
                    {
                            destid = destid+"~"+$(this).attr('id').replace('form_mytags_desti_','');
                            deststatus = deststatus+"~"+$(this).val();
                           
                    }
            
	});
      
        $("input[id^=form_mytags_task_]").each(function()
	{
            var tasktemp = $(this).attr('id').replace('form_mytags_task_','').split("_");
            
            if(taskid==''){
                    taskid = tasktemp;
                    taskstatus = $(this).val();
                    
            }
            else {
                    taskid = taskid+"~"+tasktemp;
                    taskstatus = taskstatus+"~"+$(this).val();
            }
            
	});
       
        $("input[id^=form_mytags_resor_]").each(function()
	{
            var restemp = $(this).attr('id').replace('form_mytags_resor_','').split("_");
           
            if(resid=='')
             {
                     resid = restemp;
                     resstatus = $(this).val();
             }
             else
             {
                     resid = resid+"~"+restemp;
                     resstatus = resstatus+"~"+$(this).val();
             }
               
	});
	
	  var dataparam = "oper=savecontenttagdetails&expid="+expid+"&destid="+destid+"&deststatus="+deststatus+"&taskid="+taskid+"&taskstatus="+taskstatus+"&resid="+resid+"&resstatus="+resstatus;
  
    $.ajax({
                type: 'post',
                url: "library/expedition/library-expedition-ajax.php",
                data: dataparam,
                beforeSend: function(){
                        showloadingalert("Loading, please wait.");	
                },
                success: function (data) {	
                        if(data=="success") //Works if the data saved in db
                        {
                                $('.lb-content').html("Saved Successfully.");
                                setTimeout('closeloadingalert()',500);

                                setTimeout('removesections("#library-expedition-actions");',1000);
                                setTimeout('showpageswithpostmethod("library-expedition-mytags","library/expedition/library-expedition-mytags.php","id='+expid+'");',1000);
                        }
                        else
                        {
                                $('.lb-content').html("Invalid data");
                                setTimeout('closeloadingalert()',1000);
                        }
                },
        });
}