/*---- Function To Display Expeditions in a class ----*/
/*----
        Page - Passport
	Description:
		1.Teacher can block/unblock Expedition Resource hyperlink in student passport
		2.Student can view the expedition using passport 
	History:
	Created BY : Vijayalakshmi PHP Programmer.(7/9/2014)	
----*/
var contenturl = CONTENT_URL;

function fn_showexpedition(id)
{

	var dataparam = "oper=showexpedition&classid="+id;
	$.ajax({
		type: 'post',
		url: 'tools/passport/tools-passport-passportajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#expeditiondiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {
			$('#expeditiondiv').show();
			$('#expeditiondiv').html(data);//Used to load the expediations in the dropdown
		}
	});
}

function fn_showschedules(classid,expid) {

	var dataparam = "oper=showschedule&classid="+classid+"&expid="+expid;
	$.ajax({
		type: 'post',
		url: 'tools/passport/tools-passport-passportajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#schedulediv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {
		$('#schedulediv').show();		
		$('#schedulediv').html(data);//Used to load the schedule details in the dropdown
		}
	});
}

function fn_Expeditionstatus() {
	var val = $('#classid').val()+","+$('#expeditionid').val()+","+$('#hidscheduleid').val();
	
	setTimeout("closeloadingalert();",2000);
	setTimeout('removesections("#tools-passport-passport");',1000);
	setTimeout('showpageswithpostmethod("tools-passport-passportlock","tools/passport/tools-passport-passportlock.php","id='+val+'");',1000);
}



function fn_savelockexp(classid,scheduleid,expid) {

	
	var chkresArray = [];
	/* look for all checkboxes that have a class 'chk' attached to it and check if it was checked */
	$(".subchild:checked").each(function() {
		chkresArray.push($(this).val());
	});
	var selectedres;
	selectedres = chkresArray.join('@') + ",";
	 if(selectedres.length > 1){
             
                var dataparam = "oper=savereslocked&classid="+classid+"&scheduleid="+scheduleid+"&expid="+expid+"&selectedres="+selectedres;
                $.ajax({
                type: 'post',
                url: 'tools/passport/tools-passport-passportajax.php',
                data: dataparam,
                success:function(data) {
    		    closeloadingalert();
                    var response=trim(data);
                    var sepres_data=response.split('~');
                    if(sepres_data[0] == "success")
                    {
                        var val = sepres_data[1]+","+sepres_data[2]+","+sepres_data[3];
                        showloadingalert("Locked Sucessfully."); 
                        setTimeout("closeloadingalert();",2000);
                        setTimeout('removesections("#tools-passport-passport");',1000);
                        setTimeout('showpageswithpostmethod("tools-passport-passportlock","tools/passport/tools-passport-passportlock.php","id='+val+'");',1000);
    
                    }
            
                }
                });
            }
	else
	{
	 var dataparam = "oper=saveresunlocked&classid="+classid+"&scheduleid="+scheduleid+"&expid="+expid;
			$.ajax({
			type: 'post',
			url: 'tools/passport/tools-passport-passportajax.php',
			data: dataparam,
			success:function(data) {
	    		    closeloadingalert();
			    var response=trim(data);
			    var sepres_data=response.split('~');
			    if(sepres_data[0] == "success")
			    {
			        var val = sepres_data[1]+","+sepres_data[2]+","+sepres_data[3];
			        showloadingalert("Locked Sucessfully."); 
			        setTimeout("closeloadingalert();",2000);
			        setTimeout('removesections("#tools-passport-passport");',1000);
			        setTimeout('showpageswithpostmethod("tools-passport-passportlock","tools/passport/tools-passport-passportlock.php","id='+val+'");',1000);
	    
			    }
		    
			}
			});


	}

}

function loadiframes(src,title,expid,murlcnt) {

 var media = $('.mediaurl'+murlcnt).val();

	
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

	$('body').append('<div id="expedition-fullscreecn-header" title="Synergy ITC"><div class="expeditionprevclose" style="margin-bottom:10px;"><p class="dialogTitleFullScreexp darkTitle">'+urldecode(title)+'</p><a href="javascript:void(0);" onclick="closefullscreenexp()" class="icon-synergy-close-dark"></a></div><div id="divlbcontent" style="background:#FFFFFF;"><iframe src="'+ CONTENT_URL +'/expplaydev/expedition.php?expid='+expid+'&media='+media+'&'+src[1]+'&height='+heigh+'&width='+weigh+'&hostname='+location.host+'" width="100%"></iframe></div><div class="divviewbottomexp" style="margin-top:10px;"></div></div>');
	
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

function urldecode (str) {
	return decodeURIComponent((str + '').replace(/%(?![\da-f]{2})/gi, function () {
		return '%25';
	}).replace(/\+/g, '%20'));
}

function closefullscreenexp(){	

	$('body').css('overflow','auto');
	$('#moduleplayer').removeAttr('src');
	$('#expedition-fullscreecn-header').remove();
	setTimeout(function(){$('#expedition-fullscreecn-header').remove();$('#divlbcontent').remove();},500);
	$("html, body").animate({ scrollTop: $(document).height() }, "slow");
}




function loadiframespassport(src,title,expid,media)
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
    var heigh =$(window).height() - 90;
    var weigh =$('body').width();
    var src = src.split('?');

    $('body').append('<div id="expedition-fullscreecn-header" title="Synergy ITC"><div class="expeditionprevclose" style="margin-bottom:10px;"><p class="dialogTitleFullScreexp darkTitle">'+urldecode(title)+'</p><a href="javascript:void(0);" onclick="closefullscreenexp()" class="icon-synergy-close-dark"></a></div><div id="divlbcontent" style="background:#FFFFFF;"><iframe id="contentiframe" src="'+ CONTENT_URL +'/expplaydev/expedition.php?expid='+expid+'&media='+media+'&'+src[1]+'&height='+heigh+'&width='+weigh+'&hostname='+location.host+'" width="100%"></iframe></div><div class="divviewbottomexp" style="margin-top:10px;"></div></div>');

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
 

