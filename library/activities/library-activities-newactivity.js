var filenames=[];
var filetypes=[];
/* 
	fn_createactivity()
	Function to create a new activity
*/
function fn_createactivity(id)
{	
	var formatid = $('#formatid').val();
	var description = tinyMCE.get('description').getContent();
	
	if($("#activityform").validate().form())
	{	
		if(encodeURIComponent(tinymce.get('description').getContent())==0)
		{
			showloadingalert("Please Enter a Description");	
			setTimeout('closeloadingalert()',1000);
			$('#description').focus();
			return false;
		}
		
		if(id != 'undefined' || id != 0 ){
			actionmsg = "Updating";
			alertmsg = "Activity has been updated successfully"; 
			failedmsg = "Updating the activity has been failed"; 
		}
		else {
			actionmsg = "Saving";
			alertmsg = "Activity has been created successfully"; 
			failedmsg = "Creating an activity has been failed"; 
		}
		
		var dataparam = "oper=saveactivity&activityname="+escapestr($('#activityname').val())+"&unitid="+$('#unitid').val()+"&description="+encodeURIComponent(description)+"&points="+$('#Points').val()+"&activityfilename="+escapestr($('#activityfilename').val())+"&activityid="+id+"&filetype="+$('#activityfiletype').val()+"&tags="+escapestr($('#form_tags_activity').val())+"&activityfilesize="+escapestr($('#multiactivityfilesize').val());	
		$.ajax({
			type: 'post',
			url: 'library/activities/library-activities-newactivity-ajax.php',
			data: dataparam,		
			beforeSend: function(){
				showloadingalert(actionmsg+", please wait.");	
			},
			success: function (data) {	
				if(data=="success")
				{
					$('.lb-content').html(alertmsg);
					setTimeout('closeloadingalert()',500);
					setTimeout('removesections("#library");',1000);	
					setTimeout('showpages("library-activities","library/activities/library-activities.php");',1000);
				}
				else
				{
					$('.lb-content').html(failedmsg);
					closeloadingalert();
				}
			},
		});
	}
}

/* 
	fn_delete(id)
	Function to delete an activity
	id - activity id
*/

function fn_delete(id)
{	
	var dataparam = "oper=deleteactivity"+"&id="+id;	
	$.Zebra_Dialog('Are you sure you want to delete?',
	{
		'type': 'confirmation',
		'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {		
				$.ajax({
					type: 'post',
					url: 'library/activities/library-activities-newactivity-ajax.php',
					data: dataparam,
					beforeSend: function(){
						showloadingalert('Deleting acitivity, please wait.');	
					},
					success: function (data) {	
						if(data=="success")
						{
							$('.lb-content').html("Activity deleted successfully");
							setTimeout('closeloadingalert()',1000);
							
							setTimeout('removesections("#library");',500);	
							setTimeout('showpages("library-activities","library/activities/library-activities.php");',500);
						}
						else
						{
							$('.lb-content').html("Deleting the activity has been failed");
							setTimeout('closeloadingalert()',1000);
						}
					},
				});	
			}
		}]
	});		
}
/* 
	fn_downloaddoc(filename)
	Function to download the file 
	filename - name of the file to download
*/
function fn_downloaddoc()
{
	var filename=$('#activityfilename').val();
	window.open('library/activities/library-activities-download.php?&filename='+filename,'_self');
	return false;
}




function fn_showstudentlist(classid,activityid,startdate)
{
	var dataparam = "oper=showstudentlists&classid="+classid+"&activityid="+activityid+"&startdate="+startdate;
	$.ajax({
		type: "POST",
		url: 'library/activities/library-activities-newactivity-ajax.php',
		data: dataparam,
		beforeSend:function()
		{
			showloadingalert("Loading, please wait.");
		},
		success: function(data)
		{
			closeloadingalert();
			$('#studentlist').html(data);
			fn_hideshowassignbtn();
		}
	});
}


function fn_movealllistitems(leftlist,rightlist,id)
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
	
	/* Student count is displayed*/
	var list1 = [];
	$("div[id^=list1_]").each(function(){
		list1.push($(this).attr('id').replace('list1_',''));
	});
	$('#nostudentleftdiv').html(list1.length);

	var list2 = [];
	$("div[id^=list2_]").each(function(){
		list2.push($(this).attr('id').replace('list2_',''));
	});
	$('#nostudentrightdiv').html(list2.length);
}

function fn_studentassign(activityid,flag)
{
	
	var list1 = [];
	var list2 = [];
	
	if($("#frmselectstandard").validate().form())
	{
		$("div[id^=list1_]").each(function(){
			list1.push($(this).attr('id').replace('list1_',''));
		});
		
		$("div[id^=list2_]").each(function(){
			list2.push($(this).attr('id').replace('list2_',''));
		});
		
		var clasid=$('#classid').val();
		
		var dataparam = "oper=maptoactivity&activityid="+activityid+"&list1="+list1+"&list2="+list2+"&clasid="+clasid+"&sdate1="+$('#sdate1').val()+"&predate="+$('#predate').val()+"&flag="+flag+"&edate1="+$('#edate1').val();
		$.ajax({
			type: "POST",
			url: 'library/activities/library-activities-newactivity-ajax.php',
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
				var val = activityid+","+0;
				
				setTimeout('removesections("#library-activities-actions");',500);
				setTimeout('showpages("library-activities-assign","library/activities/library-activities-assign.php?id='+val+'");',500);
			}
		});
	}
}


function fn_hideshowassignbtn()
{
	var list2 = [];
	$("div[id^=list2_]").each(function(){
		list2.push($(this).attr('id').replace('list2_',''));
	});
	if(list2=='')
	{
		$('#btnstep').addClass('dim');
	}
	else if(list2!='')
	{
		$('#btnstep').removeClass('dim');
	}
}

/*****new changes by selva*/
function deleteactivityfile(type,id,trid)
{
	
       
	Array.prototype.clear = function()
{
    this.length = 0;
};  
    var activityarr=[];
	var activitytypearr=[];
					
	if(type==1)
	{
		filename=$('#trrow_'+trid).find('td:first').html();
		var dataparam = "oper=unlinkactivityfiles&filename="+filename;
		$.ajax({
			type: "POST",
			url: 'library/activities/library-activities-newactivity-ajax.php',
			data: dataparam,
			success: function(data)
			{
				
				if(trim(data)=='success')
				{
				 $('#trrow_'+trid).hide(1000);
				  $('#trrow_'+trid).remove();
				 if($('tr[id^=trrow_]').length==0)
				 {
				    $('#appendcontenttable').hide(1000);
					$('#activityfilename').val('');
					$('#activityfiletype').val('');
				 }
				
					
					var activityname=$('#activityfilename').val().split(','); 
					var activitytype=$('#activityfiletype').val().split(','); 
					filenames.clear();
					filetypes.clear();
					for(an=0;an<activityname.length;an++)
					{
						if(activityname[an]!=filename)
						{
							filenames.push(activityname[an]);
							filetypes.push(activitytype[an]);
						}
						
					}
					$('#activityfilename').val('');
					$('#activityfiletype').val('');
					$('#activityfilename').val(filenames);
					$('#activityfiletype').val(filetypes);
					
				 
				}
				
			}
		});
	}
	else if(type==2)
	{
		var dataparam = "oper=deleteactivityfiles&activityfileid="+id;
		$.ajax({
			type: "POST",
			url: 'library/activities/library-activities-newactivity-ajax.php',
			data: dataparam,
			success: function(data)
			{
				if(trim(data)=='success')
				{
				 $('#trrow_'+trid).hide(1000);
				  $('#trrow_'+trid).remove();
				 if($('tr[id^=trrow_]').length==0)
				 {
				    $('#appendcontenttable').hide(1000); 
				 }
				}
			}
		});
		
		
	}
	
	fn_checkncancel('library-activities-newactivity',filename);
	
}
function viewtheactivity(fileformat,filename)
{
	var downloadarray=['xlsx','xls','txt','ppt','pptx','aac','ac3','frg','flp','m4b','aa3','doc','docx'];
	typeaccess=$.inArray(fileformat, downloadarray);
	if(typeaccess==-1)
	{
		fn_cancel('library-activities-newactivity');
		if(fileformat!='pdf')
		{
		 setTimeout('showpageswithpostmethod("library-activities-preview","library/activities/library-activities-preview.php","filename='+filename+'&fileformat='+fileformat+'");',500);
		}
		else if(fileformat=='pdf')
		{
			setTimeout('showpageswithpostmethod("library-activities-pdfviewer","library/activities/library-activities-pdfviewer.php","filename='+filename+'&fileformat='+fileformat+'");',500);
			
		}
	}
	else
	{
		window.open('library/activities/library-activities-download.php?&filename='+filename,'_self');
		return false;
	}
}

function downloadactivityfiles(fileformat,filename)
{
	window.open('library/activities/library-activities-download.php?&filename='+filename,'_self');
		return false;
}

function viewactivityfrompreview(fileformat,filename)
{
	    
		fn_cancel('library-activities-viewactivity');
		if(fileformat!='pdf')
		{
		 setTimeout('showpageswithpostmethod("library-activities-preview","library/activities/library-activities-preview.php","filename='+filename+'&fileformat='+fileformat+'");',500);
		}
		else if(fileformat=='pdf')
		{
			setTimeout('showpageswithpostmethod("library-activities-pdfviewer","library/activities/library-activities-pdfviewer.php","filename='+filename+'&fileformat='+fileformat+'");',500);
			
		}
}

function fn_checkncancel(id,activityname)
{
	activname=$('#'+id).nextAll('section').attr('title');;
	if(activname=activityname)
	{
	 $('#'+id).nextAll('section').hide("fade").remove();
	}
}