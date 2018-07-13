// JavaScript Document
var contenturl = CONTENT_EXP_URL;
if(location.host == "localhost") {
    contenturl = "http://localhost";
}

function loadiframes1(expid,uid,exptype)
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
        'height' : $(window).height(),
    };
    var heigh =$(window).height();
    var weigh =$('body').width();

    //Missions do not have graphical maps, so always load emaps-missioninline4sdsg/index.php
    $('body').append('<div id="expedition-fullscreecn-header" title="Synergy ITC"><div id="divlbcontent" style="background:#FFFFFF;"><iframe src="'+CONTENT_EXP_URL +'/emaps-missioninline4sdsg/index.php?expid='+expid+'&uid='+uid+'&height='+heigh+'&width='+weigh+'&hostname='+location.host+'" width="100%"></iframe></div></div>');

    $('#expedition-fullscreecn-header').css(cssObjOuter);
    $('#divlbcontent').css(cssObjInner);
    $('iframe').css({ 'width':$('#divlbcontent').width(), 'height' : $('#divlbcontent').height() });

    if (navigator.userAgent.match(/iPad/i) != null){
         removesections('#home');
    }
   

    $(window).resize(function() {
        if($('#expedition-fullscreecn-header').length){
            var cssObjOuter = {
              'display' : 'block',
              'width' : $('body').width(),
              'height' : $(window).height()
            };

            var cssObjInner = {
              'display' : 'block',
              'width' : $('body').width(),
              'height' : $(window).height(),
            };

            $('#expedition-fullscreecn-header').css(cssObjOuter);
            $('#divlbcontent').css(cssObjInner);
            $('iframe').css({ 'width':$('#divlbcontent').width(), 'height' : $('#divlbcontent').height() });
            $('iframe').contents().find('body').css('backgroundColor', 'white');
        }
    });
}

function fn_pause(id)
{
	$("audio[id^='yourAudio_']").each(function(i){
		var a = $(this).attr('id');
		var audids = a.replace('yourAudio_','');
		
		if(id!=audids)
		{
			if($('#audioControl_'+audids).hasClass("exp-pause") == true)
			{
				$('#audioControl_'+audids).removeClass("exp-pause").addClass("exp-play");
				method = 'pause';
				
				var yourAudio = "yourAudio_"+audids;
				yourAudio = eval(yourAudio);
				yourAudio[method]();
				yourAudio.currentTime = 0;
			}
		}
	});
}

function urldecode (str) {
	return decodeURIComponent((str + '').replace(/%(?![\da-f]{2})/gi, function () {
		return '%25';
	}).replace(/\+/g, '%20'));
}


function loadiframes(src,title)
{	
	var expid = $('#hidmissionid').val();
	var media = $('#mediaurl').val();
	fn_pause(0);
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
	var heigh =$(window).height() - 90;
	var weigh =$('body').width();
	var src = src.split('?');
        
	$('body').append('<div id="expedition-fullscreecn-header" title="Synergy ITC"><div class="expeditionprevclose" style="margin-bottom:10px;"><p class="dialogTitleFullScreexp darkTitle">'+urldecode(title)+'</p><a href="javascript:void(0);" onclick="closefullscreenexp()" class="icon-synergy-close-dark"></a></div><div id="divlbcontent" style="background:#FFFFFF;"><iframe src="' + CONTENT_EXP_URL + '/expplaydev/expedition.php?expid='+expid+'&media='+media+'&'+src[1]+'&height='+heigh+'&width='+weigh+'&hostname='+location.host+'" width="100%"></iframe></div><div class="divviewbottomexp" style="margin-top:10px;"></div></div>');
	
	$('#expedition-fullscreecn-header').css(cssObjOuter);
	$('#divlbcontent').css(cssObjInner);
	$('iframe').css({ 'width':$('#divlbcontent').width(), 'height' : $('#divlbcontent').height() });
	
	$(window).resize(function() {
		if($('#expedition-fullscreecn-header').length){
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
			
			$('#expedition-fullscreecn-header').css(cssObjOuter);
			$('#divlbcontent').css(cssObjInner);
			$('iframe').css({ 'width':$('#divlbcontent').width(), 'height' : $('#divlbcontent').height() });
			$('iframe').contents().find('body').css('backgroundColor', 'white');
		}
	});
}
 
 
function closefullscreenexp(){	
	$('body').css('overflow','auto');
	$('#moduleplayer').removeAttr('src');
	$('#expedition-fullscreecn-header').remove();
	setTimeout(function(){$('#expedition-fullscreecn-header').remove();$('#divlbcontent').remove();},500);
	$("html, body").animate({ scrollTop: $(document).height() }, "slow");	
}

function fn_playaudio(newid)
{
	fn_pause(newid);
	
	if(($('#audioControl_'+newid).hasClass("exp-play") == true) || ($('#audioControl_'+newid).hasClass("d-listimg") == true))
	{
		$('#audioControl_'+newid).removeClass("d-listimg");
		$('#audioControl_'+newid).removeClass("exp-play").addClass("exp-pause");
		method = 'play';
	}
	else if($('#audioControl_'+newid).hasClass("exp-pause") == true)
	{
		$('#audioControl_'+newid).removeClass("exp-pause").addClass("exp-play");
		method = 'pause';
	}
	
	var yourAudio = "yourAudio_"+newid;
	yourAudio = eval(yourAudio);
	yourAudio[method]();
	yourAudio.volume = 0.5;
	return false;
}

function fn_end(newid)
{
	$('#audioControl_'+newid).removeClass("exp-play").removeClass("exp-pause");
	$('#audioControl_'+newid).addClass("d-listimg")
}


/*----
    fn_changestatus()
	Function to be change tag types
----*/	
function fn_resstatus(id,type){
    if(type == 1){
       var alertmsg = "without resources?"; 
    }
    else{
        var alertmsg = "with resources?";
    }
    $.Zebra_Dialog('Are you sure you want to '+alertmsg,
		{
			'type': 'confirmation',
			'buttons': [
			{caption: 'No', callback: function() {
                                if(type == 1){
                                    $('#radio1_'+id).prop('checked', false);
                                    $('#radio2_'+id).prop('checked', true);
                                }
                                if(type == 0){
                                    $('#radio1_'+id).prop('checked', true);
                                    $('#radio2_'+id).prop('checked', false);
                                }
                            }},
			{caption: 'Yes', callback: function() {	
                            var dataparam = "oper=resstatus&id="+id+"&type="+type;
                            $.ajax({
                                    type: 'post',
                                    url: 'library/expedition/library-expedition-ajax.php',
                                    data: dataparam,
                                    async: false,
                                    beforeSend: function(){						
                                    },			
                                    success:function(data) {
                                    }
                            }); 
				
			}}
		]
	});
}