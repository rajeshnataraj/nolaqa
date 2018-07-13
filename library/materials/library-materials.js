/*
	Created By - Vijayalakshmi. G
	Page - library-material.js
	History:
*/
/*----
    fn_creatematerials()
	Function to save/update material details
	
----*/
function fn_creatematerials(id,usersessid)
{
	 if($("#exp_materialform1").validate().form())
         {
             if(id != undefined){
                    actionmsg = "Updating";
                    alertmsg = "Material has been updated successfully"; 
            }
            else {
                    actionmsg = "Saving";
                    alertmsg = "Material has been created successfully"; 
            }
          
            var thumbimgurl=escapestr($('#thumbimgurl').val());
            var catalogurl=escapestr($('#catalogurl').val());
            var uploadimg = $('#hiduploadfile').val();
            if(uploadimg !='' && thumbimgurl == '')
            {
                $("#uploadmaterialicon").show();
            }
            
            if(thumbimgurl !='' && uploadimg !='' && catalogurl !='')
            {
               alerterror = "Please type either Materialimage URL / Upload image.";
               showloadingalert(alerterror+", please wait.");
                $('#catalogurl').val('');
                $('#thumbimgurl').val('');
                $('#hiduploadfile').val('');
                $("#uploadmaterialicon").hide();
                $('.lb-content').html(alerterror);
                setTimeout('closeloadingalert()',1000);
                if($('#hiduploadfile').val() != '') {
             $("#uploadmaterialicon").html('');
            }
            }
            else
            {
            var thumb_url = decodeURIComponent(thumbimgurl);
            var catalog_url = decodeURIComponent(catalogurl);
        
            var dataparam = "oper=savematerials"+"&materialid="+id+"&usersessnid="+usersessid+"&material_name="+escapestr($('#materialname').val())+"&thumb_imageurl="+thumb_url+"&catalogurl="+catalog_url+"&uploadimage="+uploadimg+"&material_desc="+escapestr($('#materialdescription').val())+"&tags="+escapestr($('#form_tags_mewmaterial').val())+"&material_size="+escapestr($('#hiduploadfilesize').val());
			$.ajax({
                    url: "library/materials/library-materials-ajax.php",
                    data: dataparam,
                    type: "POST",
                    beforeSend: function(){
                            showloadingalert(actionmsg+", please wait.");	
                    },
                    success: function (data) {	

                            if(data=="success") //Works if the data saved in db
                            {
                                    $('.lb-content').html(alertmsg);
                                    setTimeout('closeloadingalert()',500);
                                    setTimeout('removesections("#library-expeditions");',1000);
                                    setTimeout('showpages("library-materials","library/materials/library-materials.php");',1000);
                            }
                            else
                            {
                                    $('.lb-content').html("Invalid data So it cannot update");
                                    setTimeout('closeloadingalert()',1000);
                            }
                    },
	       });
         }
     }

}
/*----
    fn_deletematerials()
	Function to delete material details
	
----*/
function fn_deletematerials(id,chk)
{
		var dataparam = "oper=deletematerials"+"&materialid="+id;
	
        if(chk ==1){
            var chkstatus = "This Material name already assigned to schedule, If you delete the Material will be lost in schedule, Are you sure you want to delete?";
	}
        else {
            var chkstatus = "Are you sure you want to delete?";
	}
	$.Zebra_Dialog(chkstatus,
	{
		'type': 'confirmation',
		'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {
				$.ajax({
					type: 'post',
					url: 'library/materials/library-materials-ajax.php',
					data: dataparam,
					beforeSend: function(){
						showloadingalert("Checking, please wait.");	
					},			
					success:function(data) {
                                            
						if(data=="success")
						{
							$('.lb-content').html("Material deleted successfully");
							setTimeout('closeloadingalert()',1000);
							setTimeout('removesections("#library-expeditions");',500);
							setTimeout('showpages("library-materials","library/materials/library-materials.php");',500);
						}
						else
						{
							$('.lb-content').html("Deleting this material has been failed");
							setTimeout('closeloadingalert()',1000);
						}
					}
				});	
			}
		}]
	}); 
}
