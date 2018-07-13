function fn_check()
{	
	 if($("input[name='shareid']:checked").length > 0)
	{
		$('#hidshareid').val(1);
	}
	else
	{
		$('#hidshareid').val(0); 
	}
}
function fn_createasset(id)
{	
var dataparam = "oper=saveasset"+"&assetname="+escapestr($('#assetname').val())+"&assetfilename="+encodeURIComponent($('#assetfilename').val())+"&assetid="+id+"&assettype="+$('#assetfileformat').val();
	
	if($("#assetform").validate().form())
	{	
		if($('#assetfilename').val()==''){
			showloadingalert("Please upload a file");	
			setTimeout('closeloadingalert()',1000);
			return false;
		}	
		if($('#assetfilename').val()==''){
			showloadingalert("Please select a file type");	
			setTimeout('closeloadingalert()',1000);
			return false;
		}
		if($('#assetid').val() != 'undefined'){
			actionmsg = "Updating";
			alertmsg = "Asset has been updated successfully"; 
			failedmsg = "Updating the asset has been failed"; 
		}
		else {
			actionmsg = "Saving";
			alertmsg = "Asset has been created successfully"; 
			failedmsg = "Creating an asset has been failed"; 
		}
		$.ajax({
			type: 'post',
			url: 'tools/asset/tools-asset-newasset-ajax.php',
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
					setTimeout('showpages("tools-asset-asset","tools/asset/tools-asset-asset.php");',1000);
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
	var dataparam = "oper=deleteasset"+"&id="+id;	
	
	$.Zebra_Dialog('Are you sure want to delete this asset?',
		{
			'type': 'confirmation',
			'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {
								
				$.ajax({
					type: 'post',
					url: 'tools/asset/tools-asset-newasset-ajax.php',
					data: dataparam,
					beforeSend: function(){
						showloadingalert('Deleting asset, please wait.');	
					},
					success: function (data) {	
						if(data=="success")
						{
							$('.lb-content').html("Asset deleted successfully");
							setTimeout('closeloadingalert()',1000);
							setTimeout('removesections("#tools");',1000);
							setTimeout('showpages("tools-asset","tools/asset/tools-asset-asset.php");',500);
						}
						else
						{
							$('.lb-content').html("Deleting the asset has been failed");
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
	var filename = $('#assetfilename').val();
	window.location=("tools/asset/tools-asset-download.php?filename="+filename);
}


function viewtheactivity()
{
	var filename =$('#assetfilename').val();
	var fileformat = $('#assetfileformat').val();
	fn_cancel('tools-asset-newasset');
	if(fileformat!='pdf')
	{
		setTimeout('showpageswithpostmethod("tools-asset-preview","tools/asset/tools-asset-preview.php","id='+filename+','+fileformat+'");',500);
	}
	else if(fileformat=='pdf')
	{
		setTimeout('showpageswithpostmethod("tools-asset-pdfviewer","tools/asset/tools-asset-pdfviewer.php","filename='+filename+'&fileformat='+fileformat+'");',500);
	}
}
