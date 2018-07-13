function fn_createcategory(cid)
{
	var catname = $('#catname').val();
	if(catname ==''){

	   showloadingalert("Please Fill Category Name.");
	   setTimeout('closeloadingalert()',3000);
	   return false;
	}
	var dataparam = "oper=category&catname="+catname+"&cid="+cid;
	$.ajax({
            type: 'post',
            url: 'library/nondigicontent/library-nondigicontent-category-ajax.php',
            data: dataparam,
            beforeSend: function(){
                   //  $('#schooldiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
            },
            success:function(data) {
				
                setTimeout('removesections("#library");',500);
                setTimeout('showpages("library","library/nondigicontent/library-nondigicontent.php");',500);
            }
    });
}
function fn_cancel(cid,catname)
{
	if(cid !='0')
	{
		var val = cid;
		setTimeout('removesections("#library");',500);
                setTimeout('showpages("library","library/nondigicontent/library-nondigicontent.php","id='+val+'");',1000);
	}
	else
	{
		setTimeout('removesections("#library");',500);
		setTimeout('showpageswithpostmethod("library","library/nondigicontent/library-nondigicontent-product.php","id='+cid+'");',1000);
	}
}
function fn_deletecategory(cid)
{
	$.Zebra_Dialog('Are you sure you want to delete?',
	{
		'type': 'confirmation',
		'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {
				
				var dataparam = "oper=deletecategory&catid="+cid;
				$.ajax({
					url: "library/nondigicontent/library-nondigicontent-category-ajax.php",
					data: dataparam,
					type: "POST",
					beforeSend: function(){
						showloadingalert("Checking, please wait.");	
					},	
					success: function (ajaxdata) {	
						if(ajaxdata=="success") //Works if Product Deleted
						{
							$('.lb-content').html("Category has been Deleted Successfully");
							setTimeout('closeloadingalert()',500);
							
							setTimeout('removesections("#library");',1000);
                                                        setTimeout('showpages("library","library/nondigicontent/library-nondigicontent.php");',1000);
						}
						else if(ajaxdata=="exists") //Works if Product is Assigned
						{
							closeloadingalert();
							$.Zebra_Dialog("You can't delete this Category as it is in use", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
						}
						else
						{
							$('.lb-content').html("Deleting has been Failed"); //Works if the process fails in query.
							setTimeout('closeloadingalert()',500);
						}
					},
				});
			}
		}]
	});
}