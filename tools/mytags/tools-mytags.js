// JavaScript Document

/*----
    fn_managelist()
	Function to be used for add new/ remove tags for selected items 
----*/
function fn_managelist(id,checkid){	
	var list=[];
	$("tr[id^='items_']").each(function() {
		list.push($(this).attr('name'));
	});						
	if(id[0]!=null && checkid==1){
		var dataparam = "oper=managetags&type=remove&tagids="+id[0]+"&itemids="+list;
	}
	else if(checkid==0){
		var dataparam = "oper=managetags&type=add&tagids="+escapestr(id[1])+"&itemids="+list;					
	}
	$.ajax({
		type: 'POST',
		url: 'tools/mytags/tools-mytags-managetags-ajax.php',
		data: dataparam,
		beforeSend: function(){
				showloadingalert("Updating, please wait.");	
		},
		success: function (data) {										
			closeloadingalert();					
		},
	});						
}

/*----
    fn_rowclick()
	Function to be used for show submit button  
----*/	
function fn_rowclick(id){
	
	if($('#tr_'+id).hasClass('selected')) {	
		$('#tr_'+id).removeClass("selected").removeClass("unselected");
		$('#tr_'+id).addClass("unselected");
		$('#tr_'+id+' td').removeAttr("style");
		$('#tr_'+id+' td:last').css('padding-left','4%');
	} else {
		$('#tr_'+id).removeClass("selected").removeClass('unselected');
		$('#tr_'+id).addClass("selected");
		$('#tr_'+id+' td').css("background-color","#F3FFD1");
		$('#submit').show();								
	}
	
	$('#submit').hide();	
			
	$("tr[id^='tr']").each(function() {
		if($(this).hasClass('selected')) {
			$('#submit').show();					
		}
	});			
}

/*----
    fn_submitlist()
	Function to be used for submit selected rows to nextstep 
----*/	
function fn_submitlist(){
	var count=0;
	var list=[];
	$("tr[id^='tr']").each(function() {
		if($(this).hasClass('selected')) {					
			list.push($(this).attr('name'));
			count++;									
		}
	});	
	removesections("#tools-mytags");
	showpageswithpostmethod("tools-mytags-selected","tools/mytags/tools-mytags-selected.php","id="+list+"&count="+count);	
}

/*----
    fn_checktagname()
	Function to be used for check tag name
----*/	
function fn_checktagname(id){
	var dataparam = "oper=checktagname&id="+id+"&tagname="+escapestr($('#txttagname_'+id).val());		
	$.ajax({
		type: 'post',
		url: "tools/mytags/tools-mytags-managetags-ajax.php",
		data: dataparam,
		async: false,
		success:function(ajaxdata) {				
			if(ajaxdata=='false'){
				showloadingalert("Tag name already exists");	
				setTimeout('closeloadingalert()',2000);
			}
			else{
				
			}
		}
	});		
}

/*----
    fn_rename()
	Function to be used for update tag name
----*/	
function fn_rename(id){	
	var dataparam = "oper=tagrename&id="+id+"&tagname="+escapestr($('#txttagname_'+id).val());		
	$.ajax({
		type: 'post',
		url: "tools/mytags/tools-mytags-managetags-ajax.php",
		data: dataparam,
		async: false,
		beforeSend: function(){
		},
		success:function(ajaxdata) {
			if(ajaxdata=='success'){
				closeloadingalert();
			}
			else if(ajaxdata=='fail'){
				closeloadingalert();
				$.Zebra_Dialog("Tag name already exists", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
			}
		}
	});	
}

/*----
    fn_deletetag()
	Function to be delete tags
----*/	
function fn_deletetag(tagids){
	var dataparam = "oper=deletetag&tagids="+tagids;
	$.Zebra_Dialog('Are you sure want to delete this tag?',
	{
		'type': 'confirmation',
		'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {
				$.ajax({
					type: 'post',
					url: "tools/mytags/tools-mytags-managetags-ajax.php",
					data: dataparam,
					beforeSend: function(){						
					},			
					success:function(data) {		
						if(data=='success'){
							$('#tr_'+tagids).remove();
							removesections("#tools-mytags");
							$('#submit').hide();
						}
					}
				});	
			}
		}]
	});	
}

/*----
    fn_deleteitem()
	Function to be remove tag items
----*/	
function fn_deleteitem(id){	
	var list=[];
	$("tr[id^='items_']").each(function() {
		list.push($(this).attr('name'));
	});	
	var dataparam = "oper=deleteitem&id="+id+"&tagids="+$('#form_tags_manage').val();	
	$.Zebra_Dialog('Are you sure want to remove this item?',
	{
		'type': 'confirmation',
		'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {
				$.ajax({
					type: 'post',
					url: "tools/mytags/tools-mytags-managetags-ajax.php",
					data: dataparam,
					beforeSend: function(){						
					},			
					success:function(data) {		
						if(data=='success'){
							$('#items_'+id).remove();
						}
					}
				});	
			}
		}]
	});	
}

/*----
    fn_changetagtype()
	Function to be change tag types
----*/	
function fn_changetagtype(id,type){
	var dataparam = "oper=changetagtype&id="+id+"&tagtype="+type;
	$.ajax({
		type: 'post',
		url: "tools/mytags/tools-mytags-managetags-ajax.php",
		data: dataparam,
		async: false,
		beforeSend: function(){						
		},			
		success:function(data) {
		}
	});	
}