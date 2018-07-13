
/*
	Created By - 
	Page - library-pd-newpd.js
	
	History:
            no update

*/
/*----
    fn_createpd()
	Function to save/update pd details
----*/
function fn_createpd()
{
	var webversion = '';
        var list16=[];
        var pddescription = '';
	//validate pddescription
	pddescription = encodeURIComponent(tinymce.get('pddescription').getContent().replace(/tiny_mce\//g,""));
        $('#pddescription').html('');
	$("div[id^=list16_]").each(function()
        {
         list16.push($(this).attr('id').replace('list16_',''));
         });	

	if($('#webflag').val()==1)	
		webversion = $('#webversiontxt').val() ;
	else
		webversion = $('#webversion').val() ;
			
	var dataparam = "oper=savepd"+"&pdname="+escapestr($('#pdname').val())+"&webhid="+$('#webhid').val()+"&webversion="+webversion+"&pdicon="+$('#iconhid').val()+"&tags="+escapestr($('#form_tags_input_3').val())+"&assetid="+escapestr($('#txtassetid').val())+"&pdtype="+$('#pdtype').val()+"&pdid="+$('#pdid').val()+"&list16="+list16+"&pddescription="+pddescription+"&courseid="+$('#courseid').val();
	if($("#lessonform").validate().form()){     //Validates the pd Form
		
		if($('#webhid').val()==''){ //Works if the web PD zip file not uploaded/inprogress
			showloadingalert("Please upload a web PD zip file");	
			setTimeout('closeloadingalert()',1000);
			return false;
		}
		else if($('#iconhid').val() == ''){ //Works if the PD icon not uploaded/inprogress.
			showloadingalert("Please upload an PD icon");	
			setTimeout('closeloadingalert()',1000);
			return false;
		}
		else if(webversion == ''){
			showloadingalert("Please enter version number");	
			setTimeout('closeloadingalert()',1000);
			return false;
		}
		 if(list16!='')
                {
                            if($('#pdid').val() != 'undefined' && $('#pdid').val() != '0'){ //Works in Editing pd
                                    actionmsg = "Updating";
                                    alertmsg = "PD has been updated successfully"; 
                            }
                            else {      //Works in Creating a New IPL
                                    actionmsg = "Saving";
                                    alertmsg = "PD has been created successfully"; 
                            }
		  }
                 else
                    {
                       $.Zebra_Dialog("Please assign the content to create license", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
                       return false;
                     } 
		$.ajax({
			type: 'POST',
			url: 'library/pdlessons/library-pdlessons-newpd-ajax.php',
			data: dataparam,		
			beforeSend: function(){
				showloadingalert(actionmsg+", please wait.");	
			},
			success:function(ajaxdata) {                          
				if(ajaxdata=='success'){    //Works if the data saved in db					
					$('.lb-content').html(alertmsg);
					setTimeout('closeloadingalert()',1000);
					setTimeout('removesections("#library-pd");',500);
					setTimeout('showpages("library-pdlessons","library/pdlessons/library-pdlessons.php");',500);
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
    fn_deletepd()
	Function to delete pd 
----*/
function fn_deletepd(id)
{	
	var dataparam = "oper=deletepd"+"&id="+id;	
	$.Zebra_Dialog('Are you sure you want to delete?',
	{
		'type': 'confirmation',
		'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {

				$.ajax({
					type: 'post',
					url: 'library/pdlessons/library-pdlessons-newpd-ajax.php',
					data: dataparam,	
					beforeSend: function(){
						showloadingalert("Checking, please wait.");	
					},	
					success:function(ajaxdata) {	
						if(ajaxdata=="success") //Works if pd Deleted
						{
							$('.lb-content').html("PD deleted successfully");
							setTimeout('closeloadingalert()',1000);
							setTimeout('removesections("#library-pd");',500);
							setTimeout('showpages("library-pdlessons","library/pdlessons/library-pdlessons.php");',500);
						}
						else if(ajaxdata=="exists")
						{
							closeloadingalert();
							$.Zebra_Dialog("You can't delete this PD as it is in use", { 'type': 'information' });
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
    fn_weppdversion()
	Function to load webipl version
----*/
function fn_webpdversion(id)
{
	var dataparam = "oper=weppdversion&pdid="+id;
	$.ajax({
		type: 'post',
		url: 'library/pd/library-pdlessons-newpd-ajax.php',
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
    fn_changewebpdname()
	Function to load webpdname
----*/
function fn_changewebpdname(version)
{
	var dataparam = "oper=changewebiplname&pdid="+$('#pdid').val()+"&webversion="+version;
	$.ajax({
		type: 'post',
		url: 'library/pd/library-pdlessons-newpd-ajax.php',
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
    fn_previewlesson()
	Function to preview pd
----*/
function fn_previewpdlesson(zipname,pdid)
{
    alert("ok");
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
	var contenturl = CONTENT_URL;

        if(location.host == "localhost") {
            contenturl = "http://localhost";
        }
	$('body').append('<div id="divcustomlightbox" title="Synergy ITC"><div class="btnprevclose"><p class="dialogTitleFullScr">Preview Lesson</p><a href="javascript:void(0);" onclick="closefullscreenlesson();" class="icon-synergy-close-dark"></a></div><div id="divlbcontent"><iframe src="'+CONTENT_URL+'/webpdlesson/pdcontent.php?id='+zipname+'" width="100%"></iframe></div><div class="diviplbotto"><p class="dialogTitleFullScr" id="fottitle"></p></div></div>');
	$('#divcustomlightbox').css(cssObjOuter);
	$('#divlbcontent').css(cssObjInner);
	$('iframe').css({ 'width':$('#divlbcontent').width(), 'height' : $('#divlbcontent').height() });
}
/*----
    fn_movealllistitems(leftlist,rightlist,id,courseid,lid)
	Function to move from one list to another list
		leftlist - id of the draggable left/right list box
		rightlist - id of the draggable right/left list box
		id - type of call made 0 - move all, 1 - particular item
		courseid - id of the item moved if the type is 1
		
----*/
function fn_movealllistitems(leftlist,rightlist,id,courseid)
{
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