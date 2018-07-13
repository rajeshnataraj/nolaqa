
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
	
	
}

/*---- Save Step - 3
    fn_teacherstudentidmaptoclass()
	Function to save teacher and student
----*/
function fn_savearchiveclass()
{	
	
	var list2 = [];
	
	
	$("div[id^=list2_]").each(function(){
		list2.push($(this).attr('id').replace('list2_',''));
	});
	
			
	var dataparam = "oper=savearchiveclass"+"&list2="+list2;
        
	
	$.ajax({
		type: "POST",
		url: 'class/newclass/class-newclass-archiveajax.php',
		data: dataparam,
		beforeSend:function()
		{
			showloadingalert("Loading, please wait.");
		},
		success: function(data)
		{
			closeloadingalert();
			showloadingalert("Saved successfully.");
			setTimeout("closeloadingalert();",1000);
                        setTimeout("removesections('#home');",500);
                        setTimeout('showpageswithpostmethod("class","class/class.php");',1000);
			
                }
	

        });
}
