/*----
    fn_createunits()
	Function to save/update unit details
	id - Unit id
----*/

function fn_showphase(pid)
{
   var dataparam = "oper=showphase&phaseid="+pid;
        $.ajax({
            type: 'post',
            url: 'library/documents/library-documents-ajax.php',
            data: dataparam,
            beforeSend: function(){                   
            },
            success:function(data) {                 
                 
                    $('#phasediv').html(data);//Used to load the class details in the listbox
            }
    });
}

function fn_createdocument(id)
{	
    if($("#documentform").validate().form())
    {
        var documentdescription = '';
        documentdescription = encodeURIComponent(tinymce.get('docdescription').getContent().replace(/tiny_mce\//g,""));
        $('#docdescription').html('');
                        
                        
        var dataparam = "oper=savedocument"+"&docunitid="+($('#docunitid').val())+"&docphaseid="+($('#docphaseid').val())+"&docdescription="+documentdescription+"&documentname="+escapestr($('#documentname').val())+"&docicon="+$('#hiduploadfile').val()+"&docid="+id+"&version="+($('#webversiontxt').val())+"&doctypename="+$('#webhid').val()+"&tags="+escapestr($('#form_tags_input_3').val());
       
      
                if($('#webhid').val()=='')
                {
                    showloadingalert("Please upload a PDF file");	
                    setTimeout('closeloadingalert()',1000);
                    return false;
                }
		if($('#hiduploadfile').val()=='')
		{
			showloadingalert("Please upload document Icon");	
			setTimeout('closeloadingalert()',1000);
			return false;
		}
		
		if(id != 0){
			actionmsg = "Updating";
			alertmsg = "Document has been updated successfully"; 
		}
		else {
			actionmsg = "Saving";
			alertmsg = "Document has been created successfully"; 
		}
		
		$.ajax({
			type: 'post',
			url: 'library/documents/library-documents-ajax.php',
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
					setTimeout('showpages("library-documents","library/documents/library-documents.php");',500);
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
function  fn_deletedocument(id)
{	
	if(id!=undefined)
	{
		var dataparam = "oper=deletedocument"+"&docid="+id;
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
					url: 'library/documents/library-documents-ajax.php',
					data: dataparam,
					beforeSend: function(){
						showloadingalert("Checking, please wait.");	
					},			
					success:function(data) {		
						if(data=="success")
						{
							$('.lb-content').html("Document deleted successfully");
							setTimeout('closeloadingalert()',1000);
							setTimeout('removesections("#library-sos");',500);
							setTimeout('showpages("library-documents","library/documents/library-documents.php");',500);
						}
						else
						{
							$('.lb-content').html("Deleting this document has been failed");
							setTimeout('closeloadingalert()',1000);
						}
					}
				});	
			}
		}]
	});
}

function fn_viewthedocument(docfilename,fldrname,pdfname)
{
   
    var fileformat = docfilename.split('.').pop();
    setTimeout('removesections("#library-documents-preview");');
    fn_cancel('library-documents-newdocuments');
	if(fileformat=='pdf')
	{           
                $("#library-documents-preview").remove();
                
               
		setTimeout('showpageswithpostmethod("library-documents-preview","library/documents/library-documents-preview.php","id='+docfilename+','+fileformat+'");');
                
	}
       
	
}
