function fn_check()
{
	//alert(shareid);
	 if($("input[name='shareid']:checked").length > 0)
	{
		$('#hidshareid').val(1);
	}
	else
	{
		$('#hidshareid').val(0);
	}
}
function fn_createrepository(id)
{	
var dataparam = "oper=saverepository"+"&repositoryname="+escapestr($('#repositoryname').val())+"&repositoryfilename="+encodeURIComponent($('#repositoryfilename').val())+"&repositoryid="+id+"&repositorytype="+$('#repositoryfileformat').val();
	if($("#repositoryform").validate().form())
	{	
		if($('#repositoryfilename').val()==''){
			showloadingalert("Please upload a file");	
			setTimeout('closeloadingalert()',1000);
			return false;
		}	
		if($('#repositoryfilename').val()==''){
			showloadingalert("Please select a file type");	
			setTimeout('closeloadingalert()',1000);
			return false;
		}
		if($('#repositoryid').val() != 'undefined'){
			actionmsg = "Updating";
			alertmsg = "Repository has been updated successfully"; 
			failedmsg = "Updating the repository has been failed"; 
		}
		else {
			actionmsg = "Saving";
			alertmsg = "Repository has been created successfully"; 
			failedmsg = "Creating an repository has been failed"; 
		}
		$.ajax({
			type: 'post',
			url: 'tools/repository/tools-repository-newrepository-ajax.php',
			data: dataparam,		
			beforeSend: function(){
				showloadingalert(actionmsg+", please wait.");	
			},
			success: function (data) {	
				if(data=="success")
				{
					$('.lb-content').html(alertmsg);
					setTimeout('closeloadingalert()',1000);
					setTimeout('$("#tools").nextAll().hide("fade").remove();',500);
					setTimeout('showpages("tools-repository-repository","tools/repository/tools-repository-repository.php");',1000);
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
	var dataparam = "oper=deleterepository"+"&id="+id;	
	$.Zebra_Dialog('Are you sure want to delete this repository?',
		{
			'type': 'confirmation',
			'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {
								
				$.ajax({
					type: 'post',
					url: 'tools/repository/tools-repository-newrepository-ajax.php',
					data: dataparam,
					beforeSend: function(){
						showloadingalert('Deleting repository, please wait.');	
					},
					success: function (data) {	
						if(data=="success")
						{
							$('.lb-content').html("Repository deleted successfully");
							setTimeout('closeloadingalert()',1000);
							setTimeout('removesections("#tools");',1000);
							setTimeout('showpages("tools-repository","tools/repository/tools-repository-repository.php");',500);
						}
						else
						{
							$('.lb-content').html("Deleting the repository has been failed");
							setTimeout('closeloadingalert()',1000);
						}
					}
				});	
			}}
		]
	});
}



/* 
	fn_downloaddoc(filename)
	Function to download the file 
	filename - name of the file to download
*/
function fn_downloaddoc(filename)
{
	var filename = $('#repositoryfilename').val();
        var fileformat = $('#repositoryfileformat').val();
	window.location=("tools/repository/tools-repository-download.php?filename="+filename+'&fileformat='+fileformat);
}


function viewtheactivity()
{
	var filename =$('#repositoryfilename').val();
	var fileformat = $('#repositoryfileformat').val();
	fn_cancel('tools-repository-newrepository');
	if(fileformat!='pdf')
	{
		setTimeout('showpageswithpostmethod("tools-repository-preview","tools/repository/tools-repository-preview.php","id='+filename+','+fileformat+'");',500);
	}
	else if(fileformat=='pdf')
	{
		setTimeout('showpageswithpostmethod("tools-repository-pdfviewer","tools/repository/tools-repository-pdfviewer.php","filename='+filename+'&fileformat='+fileformat+'");',500);
	}
}
