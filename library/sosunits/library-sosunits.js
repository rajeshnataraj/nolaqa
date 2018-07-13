function fn_movealllistitems(leftlist,rightlist,id)
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
    fn_createunits()
	Function to save/update unit details
	id - Unit id
----*/
function fn_createunits(id)
{	
        var list10=[];
        var list9=[];

        $("div[id^=list10_]").each(function()
         {
          list10.push($(this).attr('id').replace('list10_',''));
         });	

         $("div[id^=list9_]").each(function()
         {
          list9.push($(this).attr('id').replace('list9_',''));
         });
         
	var dataparam = "oper=saveunits"+"&unitname="+escapestr($('#unitname').val())+"&uniticon="+$('#hiduploadfile').val()+"&unitid="+id+"&tags="+escapestr($('#form_tags_newunit').val())+"&list10="+list10+"&list9="+list9;
       
	if($("#unitforms").validate().form())
	{
		if($('#hiduploadfile').val()=='')
		{
			showloadingalert("Please upload Unit Icon");	
			setTimeout('closeloadingalert()',1000);
			return false;
		}
		
                
		if(id != 0){
			actionmsg = "Updating";
			alertmsg = "Unit has been updated successfully"; 
		}
		else {
			actionmsg = "Saving";
			alertmsg = "Unit has been created successfully"; 
		}
		
		$.ajax({
			type: 'post',
			url: 'library/sosunits/library-sosunits-ajax.php',
			data: dataparam,		
			beforeSend: function(){
				
				showloadingalert(actionmsg+", please wait.");	
			},
			success:function(data) {		
				if(data=="success")
				{
					$('.lb-content').html(alertmsg);
					setTimeout('closeloadingalert()',1000);
					setTimeout('removesections("#library-sos");',500);
					setTimeout('showpages("library-sosunits","library/sosunits/library-sosunits.php");',500);
				}
				else if(data=="fail")
				{
					$('.lb-content').html("Incorrect Data");
					setTimeout('closeloadingalert()',1000);
				}
			}
		});		
	}
}

/*----
    fn_deleteunits()
	Function to delete unit details
	id - Unit id
	unitname - Unit Name
----*/
function fn_deleteunits(id)
{	
	if(id!=undefined)
	{
		var dataparam = "oper=deleteunits"+"&unitid="+id;
	}
	else
	{
		var data = "success";
	}
	
	$.Zebra_Dialog('Are you sure you want to delete?',
	{
		'type': 'confirmation',
		'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {
				$.ajax({
					type: 'post',
					url: 'library/sosunits/library-sosunits-ajax.php',
					data: dataparam,
					beforeSend: function(){
						showloadingalert("Checking, please wait.");	
					},			
					success:function(data) {		
						if(data=="success")
						{
							$('.lb-content').html("Unit deleted successfully");
							setTimeout('closeloadingalert()',1000);
							setTimeout('removesections("#library-sos");',500);
							setTimeout('showpages("library-sosunits","library/sosunits/library-sosunits.php");',500);
						}
						else
						{
							$('.lb-content').html("Deleting this unit has been failed");
							setTimeout('closeloadingalert()',1000);
						}
					}
				});	
			}
		}]
	});
}