
function fn_createproduct(pid,catid)
{
	var pname = $('#productname').val();
	var pcode = $('#productkey').val();
	
	if(pname =='' || pcode == ''){

	   showloadingalert("Please Fill All Fields.");
	   setTimeout('closeloadingalert()',3000);
	   return false;
	}
	var dataparam = "oper=product&pname="+pname+"&pcode="+pcode+"&catid="+catid+"&pid="+pid+"&tags="+escapestr($('#form_tags_newproduct').val());	

	$.ajax({
            type: 'post',
            url: 'sim/product/sim-product-ajax.php',
            data: dataparam,
            beforeSend: function(){
				showloadingalert("Please Wait.");
				setTimeout('closeloadingalert()',3000);
            },
            success:function(data) {
				
				setTimeout('removesections("#sim-category-action");',500);
				setTimeout('showpageswithpostmethod("sim-product-product","sim/product/sim-product-product.php","id='+catid+'");',1000);
                       
            }
    });
}



function fn_cancel(pid,catid)
{
	if(pid !='0')
	{
		var val = pid+","+catid;
		setTimeout('removesections("#sim-product-product");',500);
		setTimeout('showpageswithpostmethod("sim-product-action","sim/product/sim-product-action.php","id='+val+'");',1000);
	}
	else
	{
		setTimeout('removesections("#sim-category-action");',500);
		setTimeout('showpageswithpostmethod("sim-product-product","sim/product/sim-product-product.php","id='+catid+'");',1000);
	}
}

function fn_deleteproduct(id,catid)
{
	$.Zebra_Dialog('Are you sure you want to delete?',
	{
		'type': 'confirmation',
		'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {
				
				var dataparam = "oper=deleteexproduct&productid="+id;
				$.ajax({
					url: "sim/product/sim-product-ajax.php",
					data: dataparam,
                                        type: "POST",
					beforeSend: function(){
						showloadingalert("Checking, please wait.");	
					},	
					success: function (ajaxdata) {						
						if(ajaxdata=="success") //Works if Product Deleted
						{
							$('.lb-content').html("Products has been Deleted Successfully");
							setTimeout('closeloadingalert()',500);
							
							setTimeout('removesections("#sim-category-action");',1000);
							setTimeout('showpageswithpostmethod("sim-product-product","sim/product/sim-product-product.php","id='+catid+'");',1000);
						}
						else if(ajaxdata=="exists") //Works if Product is Assigned
						{
							closeloadingalert();
							$.Zebra_Dialog("You can't delete this products as it is in use", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
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
