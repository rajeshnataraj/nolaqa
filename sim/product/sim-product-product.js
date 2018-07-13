
function fn_createproduct(pid,catid,proname,prover)
{
	var pname = $('#productname').val();
	var pcode = $('#productcode').val();
	var vernumber = $('#vnumber').val();
	
	if(pname =='' || pcode == '' || vernumber == ''){

	   showloadingalert("Please Fill All Fields.");
	   setTimeout('closeloadingalert()',3000);
	   return false;
	}
	
	var dataparam = "oper=createproduct&pname="+encodeURIComponent(pname)+"&pcode="+pcode+"&vernumber="+vernumber+"&catid="+catid+"&pid="+pid+"&tags="+escapestr($('#form_tags_newproduct').val());
	//alert(dataparam);
	//exit;
	$.ajax({
            type: 'post',
            url: 'sim/product/sim-product-ajax.php',
            data: dataparam,
            beforeSend: function(){
				showloadingalert("Please Wait.");
				setTimeout('closeloadingalert()',3000);
            },
            success:function(data) {
				//alert(data);
				var result = data.split('~');
				var msg = result[0];
				var productid = result[1];
				var catname = result[2];
				var val = catid+","+catname;
				if(msg == 'success')
				{
					setTimeout('removesections("#sim");',500);
					setTimeout('showpageswithpostmethod("sim-product-product","sim/product/sim-product-product.php","id='+val+'");',1000);
				}
                                else if(msg == 'fail')
				{
					$.Zebra_Dialog('This product code is not available in ITC. Please try another product code.',
					{
						'type': 'confirmation',

					});
				}
				else if(msg == 'matched')
				{
					$.Zebra_Dialog('This product and version number already exists in SIM. Please enter another name or version number.',
					{
						'type': 'confirmation',

					});	
				}
				
            }
    });
}
function fn_editproduct(pid,catid,proname,prover)
{
	var pname = $('#productname').val();
	var pcode = $('#productcode').val();
	var vernumber = $('#vnumber').val();
	
	if(pname =='' || pcode == '' || vernumber == ''){

	   showloadingalert("Please Fill All Fields.");
	   setTimeout('closeloadingalert()',3000);
	   return false;
	}
	
	var dataparam = "oper=editproduct&pname="+encodeURIComponent(pname)+"&pcode="+pcode+"&vernumber="+vernumber+"&catid="+catid+"&pid="+pid+"&tags="+escapestr($('#form_tags_newproduct').val());
	//alert(dataparam);
	//exit;
	$.ajax({
            type: 'post',
            url: 'sim/product/sim-product-ajax.php',
            data: dataparam,
            beforeSend: function(){
				showloadingalert("Please Wait.");
				setTimeout('closeloadingalert()',3000);
            },
            success:function(data) {
				//alert(data);
				var result = data.split('~');
				var msg = result[0];
				var productid = result[1];
				var catname = result[2];
				var val = catid+","+catname;
				if(msg == 'success')
				{
					setTimeout('removesections("#sim");',500);
					setTimeout('showpageswithpostmethod("sim-product-product","sim/product/sim-product-product.php","id='+val+'");',1000);
				}
				else if(msg == 'fail')
				{
					$.Zebra_Dialog('This product Code is not available in the Licence. Please enter another Name.',
					{
						'type': 'confirmation',

					});
				}
				else if(msg == 'matched')
				{
					$.Zebra_Dialog('This product and version number already exists in SIM. Please enter another name or version number.',
					{
						'type': 'confirmation',

					});	
				}
				 
            }
    });
}

function fn_cancel(pid,catid,catname)
{
	if(pid !='0')
	{
		var val = pid+","+catid;
		setTimeout('removesections("#sim-product-product");',500);
		setTimeout('showpageswithpostmethod("sim-product-action","sim/product/sim-product-action.php","id='+val+'");',1000);
	}
	else
	{
		var val = catid+","+catname;
		setTimeout('removesections("#sim");',500);
		setTimeout('showpageswithpostmethod("sim-product-product","sim/product/sim-product-product.php","id='+val+'");',1000);
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
				
				var dataparam = "oper=deleteproduct&productid="+id+"&catid="+catid;
				$.ajax({
					url: "sim/product/sim-product-ajax.php",
					data: dataparam,
                                        type: "POST",
					beforeSend: function(){
						showloadingalert("Checking, please wait.");	
					},	
					success: function (ajaxdata) {	
					//alert(ajaxdata);
						var result = ajaxdata.split('~');
						var msg = result[0];
						var catname = result[1];
						if(msg=="success") //Works if Product Deleted
						{
							$('.lb-content').html("Products has been Deleted Successfully");
							setTimeout('closeloadingalert()',500);
							
							var val = catid+","+catname;
							setTimeout('removesections("#sim");',500);
							setTimeout('showpageswithpostmethod("sim-product-product","sim/product/sim-product-product.php","id='+val+'");',1000);
						}
						else if(msg=="exists") //Works if Product is Assigned
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