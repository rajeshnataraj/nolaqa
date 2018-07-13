/*----
    fn_createlessons()
	Function to save/update lesson details
----*/
function fn_createlessons()
{
	var webversion = '';
	
            var ipldescription = '';
		
		
			ipldescription = encodeURIComponent(tinymce.get('ipldescription').getContent().replace(/tiny_mce\//g,""));
			$('#ipldescription').html('');
		
           var list10=[];
              var list9=[];
                
              $("div[id^=list10_]").each(function()
               {
		list10.push($(this).attr('id').replace('list10_',''));
               });	
        
               $("div[id^=list9_]").each(function()
               {
		list9.push($(this).attr('id').replace('list9_',''));
               });
          
	
	
	if($('#webflag').val()==1)	
		webversion = $('#webversiontxt').val() ;
	else
		webversion = $('#webversion').val() ;
			
	var dataparam = "oper=savelessons"+"&lessonsname="+escapestr($('#lessonsname').val())+"&unitid="+$('#unitid').val()+"&ipldescription="+ipldescription+"&Points="+$('#Points').val()+"&Days="+$('#Days').val()+"&Minutes="+$('#Minutes').val()+"&webhid="+$('#webhid').val()+"&webversion="+webversion+"&iplicon="+$('#iconhid').val()+"&tags="+escapestr($('#form_tags_input_3').val())+"&assetid="+escapestr($('#txtassetid').val())+"&lessontype="+$('#lessontype').val()+"&lessonid="+$('#lessonid').val()+"&list10="+list10+"&list9="+list9;
	
	if($("#lessonform").validate().form()){	
		
		if($('#webhid').val()==''){
			showloadingalert("Please upload a web lesson zip file");	
			setTimeout('closeloadingalert()',1000);
			return false;
		}
		else if($('#iconhid').val() == ''){
			showloadingalert("Please upload an ipl icon");	
			setTimeout('closeloadingalert()',1000);
			return false;
		}
		else if(webversion == ''){
			showloadingalert("Please enter version number");	
			setTimeout('closeloadingalert()',1000);
			return false;
		}
		
              
		if($('#lessonid').val() != 'undefined' && $('#lessonid').val() != '0'){
			actionmsg = "Updating";
			alertmsg = "IPL has been updated successfully"; 
		}
		else {
			actionmsg = "Saving";
			alertmsg = "IPL has been created successfully"; 
		}
		
		
		
		$.ajax({
			type: 'POST',
			url: 'library/lessons/library-lessons-newlessons-ajax.php',
			data: dataparam,		
			beforeSend: function(){
				showloadingalert(actionmsg+", please wait.");	
			},
			success:function(ajaxdata) {
				if(ajaxdata=='success'){			
					$('.lb-content').html(alertmsg);
					setTimeout('closeloadingalert()',1000);
					setTimeout('removesections("#library-ipls");',500);
					setTimeout('showpages("library-lessons","library/lessons/library-lessons.php");',500);
				}
				else if(ajaxdata=="fail")
				{
					$('.lb-content').html("Incorrect Data");
					setTimeout('closeloadingalert()',1000);
				}
			}
		});
	}
}


/*----
    fn_deletelesson()
	Function to delete lesson 
----*/
function fn_deletelesson(id)
{	
	var dataparam = "oper=deletelesson"+"&id="+id;	
	$.Zebra_Dialog('Are you sure you want to delete?',
	{
		'type': 'confirmation',
		'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {

				$.ajax({
					type: 'post',
					url: 'library/lessons/library-lessons-newlessons-ajax.php',
					data: dataparam,	
					beforeSend: function(){
						showloadingalert("Checking, please wait.");	
					},	
					success:function(ajaxdata) {	
						if(ajaxdata=="success")
						{
							$('.lb-content').html("IPL deleted successfully");
							setTimeout('closeloadingalert()',1000);
							setTimeout('removesections("#library-ipls");',500);
							setTimeout('showpages("library-lessons","library/lessons/library-lessons.php");',500);
						}						
						else
						{
							$('.lb-content').html("Deleting has been failed");
							setTimeout('closeloadingalert()',1000);
						}
					}
				});	
			}
		}]
	});
}

/*----
    fn_wepiplversion()
	Function to load webipl version
----*/
function fn_webiplversion(id)
{
	var dataparam = "oper=wepiplversion&lessonid="+id;
	$.ajax({
		type: 'post',
		url: 'library/lessons/library-lessons-newlessons-ajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#webiplversion').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {		
			$('#webiplversion').html(data);
		}
	});
}

/*----
    fn_changewebiplname()
	Function to load webiplname
----*/
function fn_changewebiplname(version)
{
	var dataparam = "oper=changewebiplname&lessonid="+$('#lessonid').val()+"&webversion="+version;
	$.ajax({
		type: 'post',
		url: 'library/lessons/library-lessons-newlessons-ajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#webiplUploader span.qq-upload-file').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {			
			$("#webiplUploader span.qq-upload-file").html(data);	
			$('#lessons-preview').attr("onClick", "showfullscreenlesson('"+data+"');");
			$('#webhid').val(data);		
		}
	});
}

/*----
    fn_changewebiplname()
	Function to load webiplname
----*/
function fn_previewlesson(zipname,lessonid)
{
	$('html, body').animate({scrollTop: '0px'}, 0);
	$('body').css('overflow','hidden');
	
	var cssObjOuter = {
	  'display' : 'block',
	  'width' : $('body').width(),
	  'height' : $(window).height()
	};
	
	var cssObjInner = {
	  'display' : 'block',
	  'width' : $('body').width(),
	  'height' : $(window).height() - 90
	};
	var contenturl = "itccontent.pitsco.com";

        if(location.host == "localhost") {
            contenturl = "localhost";
        }
	$('body').append('<div id="divcustomlightbox" title="Synergy ITC"><div class="btnprevclose"><p class="dialogTitleFullScr">Preview Lesson</p><a href="javascript:void(0);" onclick="closefullscreenlesson();" class="icon-synergy-close-dark"></a></div><div id="divlbcontent"><iframe src="http://'+contenturl+'/scormlib/preview.php?id='+zipname+'" width="100%"></iframe></div><div class="diviplbotto"><p class="dialogTitleFullScr" id="fottitle"></p></div></div>');
	$('#divcustomlightbox').css(cssObjOuter);
	$('#divlbcontent').css(cssObjInner);
	$('iframe').css({ 'width':$('#divlbcontent').width(), 'height' : $('#divlbcontent').height() });
}

function fn_movealllistitems(leftlist,rightlist,id,courseid)
{
 //alert(id);
    if(id == 0)
	{
		$("div[id^="+leftlist+"_]").each(function()
		{
			if(!$(this).hasClass('dim')){
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
			}

		});
	}
	else
	{
		var clas=$('#'+leftlist+'_'+id).attr('class');
		if(clas=="draglinkleft")
		{
			$('#'+rightlist).append($('#'+leftlist+' #'+leftlist+'_'+id));
			$('#'+leftlist+'_'+id).removeClass('draglinkleft').addClass('draglinkright');
			var temp = $('#'+leftlist+'_'+id).attr('id').replace(leftlist,rightlist);					
			var ids='id';
			$('#'+leftlist+'_'+id).attr(ids,temp);
		}
		else 
		{	
			$('#'+leftlist).append($('#'+rightlist+' #'+rightlist+'_'+id));
			$('#'+rightlist+'_'+id).removeClass('draglinkright').addClass('draglinkleft');
			var temp = $('#'+rightlist+'_'+id).attr('id').replace(rightlist,leftlist);					
			var ids='id';
			$('#'+rightlist+'_'+id).attr(ids,temp);
		}
				
	}
}