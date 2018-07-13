
/*******fn_sendmsg()
		Function is used to send message******/
function fn_sendmsg()
{
	var list6=[];

	if($("#chkbox").is(':checked')){
		var chkalert = 1;
	}
	else{
		var chkalert = 0;
	}
	if($("#mailform").validate().form())
	{
		if($('#hiddropdowntype').val()==1)
		{
			msg="&msgto="+$('#msgto').val();
		}
		else if($('#hiddropdowntype').val()==2)
		{
			msg="&msgto="+$('#msgto1').val();
		}
		else if($('#hiddropdowntype').val()==3)
		{
			msg="&msgto="+$('#teacherto').val();
		}
                else if($('#hiddropdowntype').val()==4)
		{
			$("div[id^=list6_]").each(function(){
			list6.push($(this).attr('id').replace('list6_',''));
			});
			msg="&msgto="+list6;			
		}
		var message = encodeURIComponent(tinymce.get('message').getContent());
                if(message == ''){
                    $.Zebra_Dialog("Please enter the Content.", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
                    return false;
		}
                var messageupload=$('#multiuploadfilename').val();
                var filetype=$('#filetypeformat').val();
				var filesize=$('#multifilesize').val();
		var dataparam = "oper=sendmsg"+msg+"&msgsubject="+escapestr($('#msgsubject').val())+"&message="+message+"&dropdowntype="+$('#hiddropdowntype').val()+"&chkalert="+chkalert+"&messageupload="+messageupload+"&filetype="+filetype+"&filesize="+filesize;
		
		$.ajax({
			type: 'post',
			url: 'tools/message/tools-message-message-ajax.php',
			data: dataparam,		
			beforeSend: function(){
				showloadingalert("Message sending, please wait.");	
			},
			success: function (data) {	
			if(data=="success"){
				setTimeout('closeloadingalert()',500);
				showloadingalert("Message sent successfully");
				setTimeout('closeloadingalert()',1000);
				setTimeout('$("#tools-message-message").nextAll().hide("fade").remove();',500);
				}
			else if(data=="fail"){
					$('.lb-content').html("Incorrect Data");
					setTimeout('closeloadingalert()',1000);
				}
			}
		});
	}
		
}
/*******fn_showmsg()
		Function is used to show message******/
function fn_showmsg(msgid,id)
{
	
	$("tr").each(function() {
		if($(this).hasClass('selected')) {
			$(this).removeClass("selected").removeClass("unselected");
			$(this).addClass("unselected");				
			$('td').css("background-color","")
		}
	});	
	
	$('#tr_'+msgid).removeClass("selected").removeClass("unselected");
	$('#tr_'+msgid).addClass("selected");
	$('#tr_'+msgid+' td').css("background-color","#F3FFD1");	
	
	setTimeout('closeloadingalert()',1000);
	setTimeout('removesections("#tools-message");',500);
	setTimeout('showpageswithpostmethod("tools-message-view","tools/message/tools-message-view.php","msgid='+msgid+'&id='+id+'");',1000);
}
/*******fn_archive()
		Function is used to move message to archive******/
function fn_archive(msgid,id)
{
		var dataparam = "oper=archivemsg"+"&msgid="+msgid;
		$.ajax({
		type: 'post',
		url: 'tools/message/tools-message-message-ajax.php',
		data: dataparam,		
		beforeSend: function(){
			showloadingalert("Message storing in archive please wait");	
		},
		success: function (data) {	
		setTimeout('closeloadingalert()',500);
		showloadingalert("Message stored successfully");
		setTimeout('closeloadingalert()',1000);
		setTimeout('removesections("#tools-message-message");',500);
		setTimeout('showpageswithpostmethod("tools-message","tools/message/tools-message.php","id='+id+'");',1000);
		}
	});
}
/*******fn_replymsg()
		Function is used to show the reply message******/
function fn_replymsg(msgid)
{	
	removesections("#tools-message-view");
	showpageswithpostmethod("tools-message-reply","tools/message/tools-message-reply.php","msgid="+msgid);
}
/*******fn_forward()
		Function is used to forward the message******/
function fn_forward(msgid)
{	
	removesections("#tools-message-view");
	showpageswithpostmethod("tools-message-forward","tools/message/tools-message-forward.php","msgid="+msgid);
	
}
/*******fn_reply()
		Function is used to reply the message******/
function fn_reply(sender,msgid,subject)
{
	var messagereply = encodeURIComponent(tinymce.get('messagereply').getContent());
        if(messagereply == ''){
            $.Zebra_Dialog("Please enter the Content.", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
            return false;
        }
	var dataparam = "oper=replymsg&subject="+subject+"&sender="+sender+"&msgid="+msgid+"&messagereply="+messagereply;
			$.ajax({
			type: 'post',
			url: 'tools/message/tools-message-message-ajax.php',
			data: dataparam,		
			beforeSend: function(){
				showloadingalert("Message sending please wait");	
			},
			success: function (data) {	
			setTimeout('closeloadingalert()',500);
			showloadingalert("Message sent successfully");
			setTimeout('closeloadingalert()',1000);
			setTimeout('$("#tools-message-view").nextAll().hide("fade").remove();',500);
			}
		});
	
}
/*******fn_forwardmsg()
		Function is used to forwarding the message******/
function fn_forwardmsg(subject)
{
	if($("#forwardform").validate().form())
	{
		if($('#hiddropdowntype').val()==1)
		{
			msg="&msgto="+$('#msgto').val();
		}
		else if($('#hiddropdowntype').val()==2)
		{
			msg="&msgto="+$('#msgto1').val();
		}
		else if($('#hiddropdowntype').val()==3)
		{
			msg="&msgto="+$('#teacherto').val();
		}
		
		var messagefwd = encodeURIComponent(tinymce.get('messagefwd').getContent());
		if(messagefwd == ''){
                    $.Zebra_Dialog("Please enter the Content.", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
                    return false;
                }		
		var dataparam = "oper=forwardmsg"+msg+"&fwdmessage="+messagefwd+"&dropdowntype="+$('#hiddropdowntype').val()+"&subject="+subject;
		$.ajax({
			type: 'post',
			url: 'tools/message/tools-message-message-ajax.php',
			data: dataparam,		
			beforeSend: function(){
				showloadingalert("Message sending, please wait.");	
			},
			success: function (data) {
				if(data=="success"){		
					setTimeout('closeloadingalert()',500);
					showloadingalert("Message sent successfully");
					setTimeout('closeloadingalert()',1000);
					setTimeout('$("#tools-message-view").nextAll().hide("fade").remove();',500);
				}
				else if(data=="fail"){
					$('.lb-content').html("Incorrect Data");
					setTimeout('closeloadingalert()',1000);
				}
			}
		});
	}
	
}
/*******fn_delete()
		Function is used to delete the message******/
function fn_delete(msgid,id)
{
	$.Zebra_Dialog('This Message will be lost, Are you sure you want to delete ?',
	{
	'type': 'confirmation',
	'buttons': [
	{caption: 'No', callback: function() { }},
	{caption: 'Yes', callback: function() {
	var dataparam = "oper=deletemsg"+"&msgid="+msgid+"&id="+id;
		$.ajax({
		type: 'post',
		url: 'tools/message/tools-message-message-ajax.php',
		data: dataparam,		
		beforeSend: function(){
			showloadingalert("Message deleting, please wait.");	
		},
		success: function (data) {	
		setTimeout('closeloadingalert()',500);
		showloadingalert("Message deleted successfully");
		setTimeout('closeloadingalert()',1000);
		setTimeout('removesections("#tools-message-message");',500);
		setTimeout('showpageswithpostmethod("tools-message","tools/message/tools-message.php","id='+id+'");',1000);
		}
		});
	  }},
	]
  });
	
}

function fn_showusers(userid){
    var list6=[];
    if($('#dpusers').html()!='')
    {
                        $("div[id^=list6_]").each(function(){
			list6.push($(this).attr('id').replace('list6_',''));
			});
                        
    }
                        
               
    var dataparam = "oper=showusers"+"&userid="+userid+"&list6="+list6;
		$.ajax({
		type: 'post',
		url: 'tools/message/tools-message-message-ajax.php',
		data: dataparam,		
		success: function (data) {	
                    $('#dpusers').html(data);
		}
	});
    
}

  /*----
    fn_movealllistitems()
	Function to move all items from lest to right and right to left
----*/
function fn_movealllistitems(leftlist,rightlist,id,userid)
{	
	if(id == 0)
	{
		$("div[id^="+leftlist+"_]").each(function()
		{                    
			var clas = $(this).attr('class');
			var temp = $(this).attr('id').replace(leftlist,rightlist);
			
			$(this).attr('id',temp);
			$('#'+rightlist).append($(this));
			
			if($(this).attr('class') == 'draglinkleft') {
				$(this).removeClass("draglinkleft draglinkright");
				$(this).addClass("draglinkright");
			} else {
				$(this).removeClass("draglinkleft draglinkright");
				$(this).addClass("draglinkleft");				
			}
		});
	}
	else
	{
		var clas=$('#'+leftlist+'_'+userid).attr('class');
		
		if(clas=="draglinkleft")
		{                   
			$('#'+rightlist).append($('#'+leftlist+' #'+leftlist+'_'+userid));
			$('#'+leftlist+'_'+userid).removeClass('draglinkleft').addClass('draglinkright');
			
			var temp = $('#'+leftlist+'_'+userid).attr('id').replace(leftlist,rightlist);					
			var ids='id';
			$('#'+leftlist+'_'+userid).attr(ids,temp);
		}
		else 
		{	
			$('#'+leftlist).append($('#'+rightlist+' #'+rightlist+'_'+userid));
			$('#'+rightlist+'_'+userid).removeClass('draglinkright').addClass('draglinkleft');
		
			var temp = $('#'+rightlist+'_'+userid).attr('id').replace(rightlist,leftlist);					
			var ids='id';
			$('#'+rightlist+'_'+userid).attr(ids,temp);
		}
	}

	
}

/* file upload codeing start line */
function fn_download(msgid,ids)
{
	window.location=("tools/message/tools-messagefile-download.php?msgid="+msgid+'&ids='+ids);
}


function fn_preview(msgid,ids,prgpath)
{
    	
        if(ids!='')
        {
           
            window.open(prgpath);
        }
        
        
}
/* file upload codeing start line */


/*******Delete All the Message code developed by Mohan M 21-11-2015************/
function fn_deleteall(uid)
{
    $.Zebra_Dialog('Are you sure you want to delete all the Messages?',
    {
        'type': 'confirmation',
        'buttons': [
            {caption: 'No', callback: function() { }},
            {caption: 'Yes', callback: function() {
            var dataparam = "oper=deleteallmsg"+"&userid="+uid;
                $.ajax({
                    type: 'post',
                    url: 'tools/message/tools-message-message-ajax.php',
                    data: dataparam,		
                    beforeSend: function(){
                            showloadingalert("Message deleting, please wait.");	
                    },
                    success: function (data) 
                    {	
                        setTimeout('closeloadingalert()',500);
                        showloadingalert("Message deleted successfully");
                        setTimeout('closeloadingalert()',1000);
                        setTimeout('removesections("#tools-message-message");',500);
                        setTimeout('showpageswithpostmethod("tools-message","tools/message/tools-message.php","id=1");',1000);
                    }
                });
          }},
        ]
  });
}

/*******Delete All the Message code developed by Mohan M 21-11-2015************/