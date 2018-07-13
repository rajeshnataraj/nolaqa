/*----
    fn_createunits()
	Function to save/update unit details
	id - Unit id
----*/

function fn_showphase(pid)
{
   var dataparam = "oper=showphase&phaseid="+pid;
        $.ajax({
            type: 'post',
            url: 'library/video/library-video-ajax.php',
            data: dataparam,
            beforeSend: function(){                
            },
            success:function(data) {                 
                 
                    $('#phasediv').html(data);//Used to load the class details in the listbox
            }
    });
}

function fn_createvideo(id)
{	
    if($("#videoform").validate().form())
    {
        var videodescription = '';
        videodescription = encodeURIComponent(tinymce.get('videodescription').getContent().replace(/tiny_mce\//g,""));
        $('#videodescription').html('');
                        
                        
        var dataparam = "oper=savevideo"+"&videounitid="+($('#videounitid').val())+"&videophaseid="+($('#videophaseid').val())+"&videodescription="+videodescription+"&videoname="+escapestr($('#videoname').val())+"&videoicon="+$('#hiduploadfile').val()+"&videoid="+id+"&version="+($('#webversiontxt').val())+"&videotypename="+$('#webhid').val()+"&tags="+escapestr($('#form_tags_input_3').val());
      
                if($('#webhid').val()=='')
                {
                    showloadingalert("Please upload a Video file");	
                    setTimeout('closeloadingalert()',1000);
                    return false;
                }
		if($('#hiduploadfile').val()=='')
		{
			showloadingalert("Please upload Video Icon");	
			setTimeout('closeloadingalert()',1000);
			return false;
		}
		
		if(id != 0){
			actionmsg = "Updating";
			alertmsg = "Video has been updated successfully"; 
		}
		else {
			actionmsg = "Saving";
			alertmsg = "Video has been created successfully"; 
		}
		
		$.ajax({
			type: 'post',
			url: 'library/video/library-video-ajax.php',
			data: dataparam,		
			beforeSend: function(){
				
				showloadingalert(actionmsg+", please wait.");	
			},
			success:function(data) {		
				if(data=="success")
				{
					$('.lb-content').html(alertmsg);
					setTimeout('closeloadingalert()',1000);
                                        setTimeout('removesections("#library-sos");',500);
					setTimeout('showpages("library-video","library/video/library-video.php");',500);
				}
				else if(data=="fail")
				{
					$('.lb-content').html("Incorrect Data");
					setTimeout('closeloadingalert()',1000);
				}
			}
		});		
	}
}

/*----
    fn_deleteunits()
	Function to delete unit details
	id - Unit id
	unitname - Unit Name
----*/
function  fn_deletevideo(id)
{	
	if(id!=undefined)
	{
		var dataparam = "oper=deletevideo"+"&videoid="+id;
	}
	else
	{
		var data = "success";
	}
	
	$.Zebra_Dialog('Are you sure you want to delete?',
	{
		'type': 'confirmation',
		'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {
				$.ajax({
					type: 'post',
					url: 'library/video/library-video-ajax.php',
					data: dataparam,
					beforeSend: function(){
						showloadingalert("Checking, please wait.");	
					},			
					success:function(data) {		
						if(data=="success")
						{
							$('.lb-content').html("Video deleted successfully");
							setTimeout('closeloadingalert()',1000);
							setTimeout('removesections("#library-sos");',500);
							setTimeout('showpages("library-video","library/video/library-video.php");',500);
						}
						else
						{
							$('.lb-content').html("Deleting this video has been failed");
							setTimeout('closeloadingalert()',1000);
						}
					}
				});	
			}
		}]
	});
}

function fn_viewtheactivity(videofilename,fldrname,pdfname)
{    
    var fileformat = videofilename.split('.').pop();
    setTimeout('removesections("#library-video-preview");');
    fn_cancel('library-video-newvideo');
	if(fileformat=='mp4')
	{ 
                $("#library-video-preview").remove();
                
               
		setTimeout('showpageswithpostmethod("library-video-preview","library/video/library-video-preview.php","id='+videofilename+','+fileformat+'");');
                
	}
        else{
               
               if(fldrname>0)
               {
                   var filename=videofilename.split('.');
                   var subfilename=videofilename.split('_');
                   
                   pdfname=subfilename[0];
                   videofilename=filename[0];
               }
               else
               {
                   var filename=videofilename.split('.');
                   videofilename=filename[0];
               }
               
             showfullscreenlessonpdsos(videofilename,pdfname);
        }
	
}
function showfullscreenlessonpdsos(fldrname,pdfname){
      
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
	
        $('body').append('<div id="divcustomlightbox" title="Synergy ITC"><div class="btnprevclose"><a href="javascript:void(0);" onclick="closefullscreenlesson()" class="icon-synergy-close-dark" style="color:#24485f"></a></div><iframe src="'+CLOUDFRONT_URL+'/sosvideo/'+fldrname+'/'+pdfname+'/'+pdfname+'.html" width="100%" height="100%"></iframe></div>');
	
	$('#divcustomlightbox').css(cssObjOuter);
	$('#divlbcontent').css(cssObjInner);
	$('iframe').css({ 'width':$('#divlbcontent').width(), 'height' : $('#divlbcontent').height() });
        $('.btnprevclose').css({'height':'0'});
}
