/*----
    fn_createunits()
	Function to save/update course details
	id - Course id
----*/
function fn_createcourse(id)
{	
        
	if($("#courseforms").validate().form())
	{
           
		if($('#hiduploadfile').val()=='')
		{
			showloadingalert("Please upload Course Icon");	
			setTimeout('closeloadingalert()',1000);
			return false;
		}
		
		if(id != undefined){
			actionmsg = "Updating";
			alertmsg = "Course has been updated successfully"; 
		}
		else {
			actionmsg = "Saving";
			alertmsg = "Course has been created successfully"; 
		}
                
                var dataparam = "oper=savecourse"+"&coursename="+escapestr($('#coursename').val())+"&courseicon="+$('#hiduploadfile').val()+"&courseid="+id+"&tags="+escapestr($('#form_tags_newcourse').val())+"&assetid="+escapestr($('#txtassetid').val());
		
		$.ajax({
			type: 'post',
			url: 'library/courses/library-courses-ajax.php',
			data: dataparam,		
			beforeSend: function(){
				
				showloadingalert(actionmsg+", please wait.");	
			},
			success:function(data) {		
				if(data=="success")
				{
					$('.lb-content').html(alertmsg);
					setTimeout('closeloadingalert()',1000);
					setTimeout('removesections("#library-pd");',500);
					setTimeout('showpages("library-courses","library/courses/library-courses.php");',500);
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
    fn_deletecourse()
	Function to delete course details
	id - Course id
	unitname - Course Name
----*/
function fn_deletecourses(id)
{	
	if(id!=undefined)
	{
		var dataparam = "oper=deletecourse"+"&courseid="+id;
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
					url: 'library/courses/library-courses-ajax.php',
					data: dataparam,
					beforeSend: function(){
						showloadingalert("Checking, please wait.");	
					},			
					success:function(data) {		
						if(data=="success")
						{
							$('.lb-content').html("Course deleted successfully");
							setTimeout('closeloadingalert()',1000);
							setTimeout('removesections("#library-pd");',500);
							setTimeout('showpages("library-courses","library/courses/library-courses.php");',500);
						}
						else
						{
							$('.lb-content').html("Deleting this course has been failed");
							setTimeout('closeloadingalert()',1000);
						}
					}
				});	
			}
		}]
	});
}