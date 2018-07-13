/*----
    fn_createunits()
	Function to save/update unit details
	id - Unit id
----*/
function fn_createphase(id)
{	
	var dataparam = "oper=savephase"+"&unitid="+($('#unitid').val())+"&phasename="+escapestr($('#phasename').val())+"&phaseicon="+$('#hiduploadfile').val()+"&phaseid="+id+"&tags="+escapestr($('#form_tags_newunit').val());
       
	if($("#phaseforms").validate().form())
	{
		if($('#hiduploadfile').val()=='')
		{
			showloadingalert("Please upload Phase Icon");	
			setTimeout('closeloadingalert()',1000);
			return false;
		}
		
		if(id != 0){
			actionmsg = "Updating";
			alertmsg = "Phase has been updated successfully"; 
		}
		else {
			actionmsg = "Saving";
			alertmsg = "Phase has been created successfully"; 
		}
		
		$.ajax({
			type: 'post',
			url: 'library/phase/library-phase-ajax.php',
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
					setTimeout('showpages("library-phase","library/phase/library-phase.php");',500);
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
function fn_deletephase(id)
{	
	if(id!=undefined)
	{
		var dataparam = "oper=deletephase"+"&phaseid="+id;
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
					url: 'library/phase/library-phase-ajax.php',
					data: dataparam,
					beforeSend: function(){
						showloadingalert("Checking, please wait.");	
					},			
					success:function(data) {		
						if(data=="success")
						{
							$('.lb-content').html("Phase deleted successfully");
							setTimeout('closeloadingalert()',1000);
							setTimeout('removesections("#library-sos");',500);
							setTimeout('showpages("library-phase","library/phase/library-phase.php");',500);
						}
						else
						{
							$('.lb-content').html("Deleting this phase has been failed");
							setTimeout('closeloadingalert()',1000);
						}
					}
				});	
			}
		}]
	});
}