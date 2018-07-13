/*----
    fn_createunits()
	Function to save/update unit details
	id - Unit id
----*/
function fn_createunits(id)
{	
	var dataparam = "oper=saveunits"+"&unitname="+escapestr($('#unitname').val())+"&uniticon="+$('#hiduploadfile').val()+"&unitid="+id+"&tags="+escapestr($('#form_tags_newunit').val())+"&assetid="+escapestr($('#txtassetid').val());
	if($("#unitforms").validate().form())
	{
		if($('#hiduploadfile').val()=='')
		{
			showloadingalert("Please upload Unit Icon");	
			setTimeout('closeloadingalert()',1000);
			return false;
		}
		
		if(id != undefined){
			actionmsg = "Updating";
			alertmsg = "Unit has been updated successfully"; 
		}
		else {
			actionmsg = "Saving";
			alertmsg = "Unit has been created successfully"; 
		}
		
		$.ajax({
			type: 'post',
			url: 'library/units/library-units-ajax.php',
			data: dataparam,		
			beforeSend: function(){
				
				showloadingalert(actionmsg+", please wait.");	
			},
			success:function(data) {		
				if(data=="success")
				{
					$('.lb-content').html(alertmsg);
					setTimeout('closeloadingalert()',1000);
					setTimeout('removesections("#library-ipls");',500);
					setTimeout('showpages("library-units","library/units/library-units.php");',500);
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
					url: 'library/units/library-units-ajax.php',
					data: dataparam,
					beforeSend: function(){
						showloadingalert("Checking, please wait.");	
					},			
					success:function(data) {		
						if(data=="success")
						{
							$('.lb-content').html("Unit deleted successfully");
							setTimeout('closeloadingalert()',1000);
							setTimeout('removesections("#library-ipls");',500);
							setTimeout('showpages("library-units","library/units/library-units.php");',500);
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