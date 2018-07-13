
function fn_createfield(did,catid,pid)
{
	var fname = $('#fieldname').val();
	
	if(fname =='')
	{
		showloadingalert("Please Fill Field Name.");
		setTimeout('closeloadingalert()',3000);
		return false;
	}
	
	var dataparam = "oper=itemfield&fname="+fname+"&catid="+catid+"&pid="+pid+"&did="+did+"&tags="+escapestr($('#form_tags_newitems').val());
	
	$.ajax({
		type : 'post',
		url :'sim/items/sim-items-ajax.php',
		data : dataparam,
		beforeSend: function(){
			showloadingalert("Please Wait.");
			setTimeout('closeloadingalert()',3000);	
		},
		success:function(data) {
			
			var did = data.split('~');
			var dfid = did[1];
			var productname = did[2];
			
			var val = pid+","+productname+","+catid+","+dfid;
			setTimeout('removesections("#sim-product-action");',500); 
			setTimeout('showpageswithpostmethod("sim-items-items","sim/items/sim-items-items.php","id='+val+'");',1000);
		}
	})
}

function fn_additem(catid,desname,proid,desid,ditemid)
{
	var itemname = $('#itemname').val();
	var message = encodeURIComponent(tinymce.get('message').getContent());
	if(message == ''){
		$.Zebra_Dialog("Please enter the Content.", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
		return false;
	}
	
	var upload = $('#pimfilename').val();
	
	var dataparam = "oper=additem&itemname="+itemname+"&message="+message+"&upload="+upload+"&catid="+catid+"&proid="+proid+"&desid="+desid+"&ditemid="+ditemid+"&tags="+escapestr($('#form_tags_addnewitems').val());	
	$.ajax({
		type : 'post',
		url :'sim/items/sim-items-ajax.php',
		data : dataparam,
		beforeSend: function(){
			showloadingalert("Please Wait.");
			setTimeout('closeloadingalert()',3000); 	
		},
		success:function(data) {
			var response=trim(data);
			var data=response.split('~');
			var additemid = data[1];
			
			var val = catid+","+desname+","+proid+","+desid;
			
			setTimeout('removesections("#sim-items-action");',500); 
			setTimeout('showpageswithpostmethod("sim-items-items","sim/items/sim-items-newfielditem.php","id='+val+'");',1000);
		}
	})
}

function fn_cancel(did,catid,pid)
{
	var fname="mm";
	var val = catid+","+fname+","+pid+","+did;
	setTimeout('removesections("#sim-items-items");',500);
	setTimeout('showpageswithpostmethod("sim-items-action","sim/items/sim-items-action.php","id='+val+'");',1000);
}
function fn_download(uploadname)
{
	window.location=("sim/items/sim-items-download.php?filename="+uploadname);
}

function fn_deletefields(fid,catid,pid)
{
	$.Zebra_Dialog('Are you sure you want to delete?',
	{
		'type': 'confirmation',
		'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {
				
				var dataparam = "oper=deletefields&fieldid="+fid+"&catid="+catid+"&pid="+pid;
				$.ajax({
					url: "sim/items/sim-items-ajax.php",
					data: dataparam,
                                        type: "POST",
					beforeSend: function(){
						showloadingalert("Checking, please wait.");	
					},	
					success: function (ajaxdata) {						
						var response=trim(ajaxdata);
						var results=response.split('~');
						var data = results[0];
						var pname = results[1];
						
						var val = pid+","+pname+","+catid+","+fid;
						
						if(data=="success") //Works if Fields Deleted
						{
							$('.lb-content').html("Fields has been Deleted Successfully");
							setTimeout('closeloadingalert()',500);
							
							setTimeout('removesections("#sim-product-action");',1000);
							setTimeout('showpageswithpostmethod("sim-items-items","sim/items/sim-items-items.php","id='+val+'");',1000);
						}
						else if(data=="exists") //Works if Fields is Assigned
						{
							closeloadingalert();
							$.Zebra_Dialog("You can't delete this fields as it is in use", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
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

function fn_deleteitems(catid,proid,desid,ditemid)
{
	$.Zebra_Dialog('Are you sure you want to delete?',
	{
		'type': 'confirmation',
		'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {
				
				var dataparam = "oper=deleteitems&itemid="+ditemid+"&desid="+desid;
				$.ajax({
					url: "sim/items/sim-items-ajax.php",
					data: dataparam,
                                        type: "POST",
					beforeSend: function(){
						showloadingalert("Checking, please wait.");	
					},	
					success: function (ajaxdata) {						
						var response=trim(ajaxdata);
						var results=response.split('~');
						var data = results[0];
						var fname = results[1];
						var val = catid+","+fname+","+proid+","+desid;
						
						if(data=="success") //Works if Fields Deleted
						{
							$('.lb-content').html("Fields has been Deleted Successfully");
							setTimeout('closeloadingalert()',500);
			
							setTimeout('removesections("#sim-items-action");',500); 
							setTimeout('showpageswithpostmethod("sim-items-items","sim/items/sim-items-newfielditem.php","id='+val+'");',1000);
						}
						else if(data=="exists") //Works if Fields is Assigned
						{
							closeloadingalert();
							$.Zebra_Dialog("You can't delete this fields as it is in use", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
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