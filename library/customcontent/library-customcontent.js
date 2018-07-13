/*----
    fn_createcustomcontent()
	Function to save/update content details
	id - Content id
----*/
function fn_createcustomcontent(id)
{	
	var dataparam = "oper=savecustomcontent"+"&contentname="+escapestr($('#contentname').val())+"&customcontentid="+id+"&tags="+escapestr($('#form_tags_newcustomcontent').val())+"&pointspossible="+escapestr($('#txtpp').val());
	if($("#customcontentforms").validate().form())
	{
		if(id != undefined && id!=0){
			actionmsg = "Updating";
			alertmsg = "Custom content has been updated successfully"; 
		}
		else {
			actionmsg = "Saving";
			alertmsg = "Custom content has been created successfully"; 
		}
		
		$.ajax({
			type: 'post',
			url: 'library/customcontent/library-customcontent-ajax.php',
			data: dataparam,		
			beforeSend: function(){
				
				showloadingalert(actionmsg+", please wait.");	
			},
			success:function(data) {		
				if(data=="success")
				{
					$('.lb-content').html(alertmsg);
					setTimeout('closeloadingalert()',1000);
					setTimeout('removesections("#library");',500);
					setTimeout('showpages("library-customcontent","library/customcontent/library-customcontent.php");',500);
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
    fn_deletecustomcontent()
	Function to delete customcontent details
	id - content id
----*/
function fn_deletecustomcontent(id)
{	
	if(id!=undefined)
	{
		var dataparam = "oper=deletecustomcontent"+"&ccid="+id;
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
					url: 'library/customcontent/library-customcontent-ajax.php',
					data: dataparam,
					beforeSend: function(){
						showloadingalert("Checking, please wait.");	
					},			
					success:function(data) {		
						if(data=="success")
						{
							$('.lb-content').html("Custom content deleted successfully");
							setTimeout('closeloadingalert()',1000);
							setTimeout('removesections("#library");',500);
							setTimeout('showpages("library-customcontent","library/customcontent/library-customcontent.php");',500);
						}
					}
				});	
			}
		}]
	});
}