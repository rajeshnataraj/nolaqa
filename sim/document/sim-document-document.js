function fn_adddocument(docid,catid,proid,listicon)
{
	var docname = $('#documentname').val();
	
	var upload = $('#pimfilename').val();
	
	if($('#globaldoc').attr('checked')) 
	{
    	var status = '1';
	}
	else
	{
		var status = '0';
	}
	
	var dataparam = "oper=document&documentname="+ encodeURIComponent(docname)+"&upload="+upload+"&catid="+catid+"&proid="+proid+"&docid="+docid+"&tags="+escapestr($('#form_tags_adddocument').val())+"&globaldoc="+status+"&listicon="+listicon;
	//alert("result"+dataparam);
	$.ajax({
		type : 'post',
		url :'sim/document/sim-document-ajax.php',
		data : dataparam,
		beforeSend: function(){
			showloadingalert("Please Wait.");
			setTimeout('closeloadingalert()',3000); 	
		},
		success:function(data) {
			var response=trim(data);
			var data=response.split('~');
			var docid = data[1];
			var productname = data[2];
			var listicon = data[3];

			var val = proid+","+productname+","+catid+","+listicon;
			
			if(listicon == '1')
			{
				setTimeout('removesections("#sim-product-action");',500); 
				setTimeout('showpageswithpostmethod("sim-document-document","sim/document/sim-document-document.php","id='+val+'");',1000);
				
			}
			else
			{
				setTimeout('removesections("#sim-product-action");',500); 
				setTimeout('showpageswithpostmethod("sim-document-document","sim/document/sim-document-document.php","id='+val+'");',1000);
			}
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

function fn_deletedocument(docid,proid,catid,listicon)
{
	$.Zebra_Dialog('Are you sure you want to delete?',
	{
		'type': 'confirmation',
		'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {
				
				var dataparam = "oper=deletedocument&docid="+docid+"&catid="+catid+"&proid="+proid+"&listicon="+listicon;
				$.ajax({
					url: "sim/document/sim-document-ajax.php",
					data: dataparam,
					type: "POST",
					beforeSend: function(){
						showloadingalert("Checking, please wait.");	
					},	
					success: function (ajaxdata) {	
					//alert(ajaxdata);
						var response=trim(ajaxdata);
						var results=response.split('~');
						var data = results[0];
						var documentname = results[1];
						var productname = results[2];
						var listicon = results[3];
						
						var val = proid+","+productname+","+catid+","+listicon;
						
						if(data=="success") //Works if Fields Deleted
						{
							$('.lb-content').html("Document has been Deleted Successfully");
							setTimeout('closeloadingalert()',500);
							
							setTimeout('removesections("#sim-product-action");',1000);
							setTimeout('showpageswithpostmethod("sim-document-document","sim/document/sim-document-document.php","id='+val+'");',1000);
						}
						else if(data=="exists") //Works if Fields is Assigned
						{
							closeloadingalert();
							$.Zebra_Dialog("You can't delete this document as it is in use", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
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

function fn_viewdocument(docid)
{
	var dataparam = "oper=viewdoc&docid="+docid;
	//alert("result"+dataparam);
	$.ajax({
		type: 'post',
		url: 'sim/document/sim-document-ajax.php',
		data: dataparam,
		beforeSend: function(){
		},
		success:function(data) {
			var file = data;
			window.open(CONTENT_URL + '/sim/'+file+'');
			
		}
	});
}

function fn_listview() //large list view coding
{
	$("#simdocument").hide();
	$("#simdocumenticon").show();
	$("#sim-items-addnewitem").hide();
	$("#sim-items-action").hide();
	setTimeout("removesections('#simdocument');",500);
	setTimeout("removesections('#sim-items-addnewitem');",500);
	setTimeout("removesections('#sim-items-action');",500);
	
}

function fn_iconview() // icon view codeing
{
	$("#simdocumenticon").hide();
	$("#simdocument").show();
	$("#sim-items-addnewitem").hide();
	$("#sim-items-action").hide();
	setTimeout("removesections('#sim-items-addnewitem');",500);
	setTimeout("removesections('#sim-items-action');",500);
	
}
