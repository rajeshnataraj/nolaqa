// JavaScript Document

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


function loadiframes(src,title,flag)
{	
	var expid = $('#hidexpeditionid').val();
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
	var contenturl = CONTENT_EXP_URL;

        if(location.host == "localhost") {
            contenturl = "http://localhost";
        }
        
       
        if(flag=='1')
        {
            $('body').append('<div id="expedition-fullscreecn-header" title="Synergy ITC"><div class="expeditionprevclose" style="margin-bottom:10px;"><p class="dialogTitleFullScreexp darkTitle">'+urldecode(title)+'</p><a href="javascript:void(0);" onclick="closefullscreenexp()" class="icon-synergy-close-dark"></a></div><div id="divlbcontent" style="text-align:center;background:#FFFFFF;"><img src="img/lockimg.png" style="margin-top:130px;"></div><div class="divviewbottomexp" style="margin-top:10px;"></div></div>');
        }
        else
        {
           var newurl = CONTENT_EXP_URL + '/expplaydev/expedition.php?expid='+expid+'&media='+media+'&'+src[1]+'&height='+heigh+'&width='+weigh;
	 $('body').append('<div id="expedition-fullscreecn-header" title="Synergy ITC"><div class="expeditionprevclose" style="margin-bottom:10px;"><p class="dialogTitleFullScreexp darkTitle">'+urldecode(title)+'</p><a href="javascript:void(0);" onclick="closefullscreenexp()" class="icon-synergy-close-dark"></a></div><div id="divlbcontent" style="background:#FFFFFF;"><iframe src="'+newurl+'&hostname='+location.host+'" width="100%"></iframe></div><div class="divviewbottomexp" style="margin-top:10px;"></div></div>'); 
        }
	
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
		}
	});
}
 
function closefullscreenexp(){	
	$('body').css('overflow','auto');
	$('#expedition-fullscreecn-header').remove();
	$('#divlbcontent').remove();
	$("#destlist").load("assignment/expedition/assignment-expedition-preview.php #destlist > *",{"id":$('#calldestdiv').val()});
	$("#tasklist").load("assignment/expedition/assignment-expedition-tasks.php #tasklist > *",{"id":$('#calltaskdiv').val()});
	$("#reslist").load("assignment/expedition/assignment-expedition-resourses.php #reslist > *",{"id":$('#callresdiv').val()});	
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
	$('#audioControl_'+newid).addClass("d-listimg");
}

function fn_closescreen1(expschid,expid,temp,destid,destorder,taskid,schtype){
    $('body').css('overflow','auto');
    $('#divcustomlightbox').remove();
    $('#divlbcontent').remove();
    $("html, body").animate({ scrollTop: $(document).height() }, "slow");
    if(temp == 1){
        var values = expschid+"~"+expid+"~"+schtype+"~"+0+"~"+0+"~"+0;
        setTimeout('removesections("#assignment");',300);
        setTimeout('showpageswithpostmethod("assignment-expedition-preview","assignment/expedition/assignment-expedition-preview.php","id='+values+'");',400);
    }
    if(temp == 2){
        var values = destid+","+destorder+","+expid+","+expschid+","+schtype+","+0+","+0;
        setTimeout('removesections("#assignment-expedition-preview");',300);
        setTimeout('showpageswithpostmethod("assignment-expedition-tasks","assignment/expedition/assignment-expedition-tasks.php","id='+values+'");',400);
    }
    if(temp == 3){
        var values = destid+","+taskid+","+destorder+","+expid+","+expschid+","+schtype+","+0+","+0;
        setTimeout('removesections("#assignment-expedition-tasks");',300);
        setTimeout('showpageswithpostmethod("assignment-expedition-resourses","assignment/expedition/assignment-expedition-resourses.php","id='+values+'");',400);
    }
}

function fn_exptest(testid,etogglestatus,expid,schid,schtype){
        var exp="exp";        
	var val=testid+","+exp+","+schid+","+1+","+0+","+0+","+0+","+schtype;
	setTimeout('removesections("#assignment-assignmentengine-test");',500);
	setTimeout('showpages("assignment-assignmentengine-questions","assignment/assignmentengine/assignment-assignmentengine-questions.php?id='+val+'");',500);
	fn_expstatus(etogglestatus,schid,schtype,expid,testid)
}

function fn_expstatus(etogglestatus,schid,schtype,expid,testid){
    if(etogglestatus ==1){
        var dataparam = "oper=expteststatus&schid="+schid+"&schtype="+schtype+"&expid="+expid+"&testid="+testid;
    }
    $.ajax({
        url: "assignment/expedition/assignment-expedition-ajax.php",
        data: dataparam,
        type: "POST",
        beforeSend: function(){
        },
        success: function (data) {
            var resid =0;
            var taskid =0;            
        },
    });
}

function fn_desttest(testid,dtogglestatus,destid,expid,schid,schtype){
    var exp="exp";    
    var val=testid+","+exp+","+schid+","+1+","+0+","+0+","+0+","+schtype;
    setTimeout('removesections("#assignment-assignmentengine-test");',500);
    setTimeout('showpages("assignment-assignmentengine-questions","assignment/assignmentengine/assignment-assignmentengine-questions.php?id='+val+'");',500);
    fn_deststatus(dtogglestatus,schid,schtype,expid,destid,testid);
}

function fn_deststatus(dtogglestatus,schid,schtype,expid,destid,testid){  
    if(dtogglestatus ==1){
        var dataparam = "oper=destteststatus&schid="+schid+"&schtype="+schtype+"&expid="+expid+"&destid="+destid+"&preposttestid="+testid;
    }  
    $.ajax({
        url: "assignment/expedition/assignment-expedition-ajax.php",
        data: dataparam,
        type: "POST",
        beforeSend: function(){
        },
        success: function (data) {
            var resid =0;
            var taskid =0;           
        },
    });
}

function fn_tasktest(testid,ttogglestatus,taskid,destid,expid,schid,destorder,schtype){
    var exp="exp";    
    var val=testid+","+exp+","+schid+","+2+","+destid+","+destorder+","+0+","+schtype;
    setTimeout('removesections("#assignment-assignmentengine-test");',500);
    setTimeout('showpages("assignment-assignmentengine-questions","assignment/assignmentengine/assignment-assignmentengine-questions.php?id='+val+'");',500); 
    fn_taskstatus(ttogglestatus,schid,schtype,expid,taskid,destid,testid);
}


function fn_taskstatus(ttogglestatus,schid,schtype,expid,taskid,destid,testid){  
    if(ttogglestatus ==1){
        var dataparam = "oper=taskteststatus&schid="+schid+"&schtype="+schtype+"&expid="+expid+"&taskid="+taskid+"&destid="+destid+"&preposttestid="+testid;
    }   
    $.ajax({
        url: "assignment/expedition/assignment-expedition-ajax.php",
        data: dataparam,
        type: "POST",
        beforeSend: function(){
        },
        success: function (data) {
           var resid=0;
           fn_expeditionajaxtask(schid,schtype,expid,taskid,destid);         
        },
    });
}

function fn_restest(testid,togglestatus,resid,taskid,destid,expid,schid,taskorder,schtype){
    var exp="exp";    
    var val=testid+","+exp+","+schid+","+3+","+destid+","+taskorder+","+taskid+","+schtype;   
    setTimeout('removesections("#assignment-assignmentengine-test");',500);
    setTimeout('showpages("assignment-assignmentengine-questions","assignment/assignmentengine/assignment-assignmentengine-questions.php?id='+val+'");',500); 
    fn_resstatus(togglestatus,schid,schtype,expid,resid,taskid,destid,testid);
 }

function fn_resstatus(togglestatus,schid,schtype,expid,resid,taskid,destid,testid){
    if(togglestatus ==1){
        var dataparam = "oper=resteststatus&schid="+schid+"&schtype="+schtype+"&expid="+expid+"&resid="+resid+"&taskid="+taskid+"&destid="+destid+"&preposttestid="+testid;
    }
    $.ajax({
        url: "assignment/expedition/assignment-expedition-ajax.php",
        data: dataparam,
        type: "POST",
        beforeSend: function(){
        },
        success: function (data) {
          
           fn_expeditionajaxres(schid,schtype,expid,resid,taskid,destid,testid);           
                            
           
        },
    });
}

function fn_expeditionajaxres(schid,schtype,expid,resid,taskid,destid)
{    
    var dataparam = "oper=savereadstatusrestest&expid="+expid+"&destid="+destid+"&taskid="+taskid+"&resid="+resid+"&schid="+schid+"&schtype="+schtype;   
    $.ajax({
        url: "assignment/expedition/assignment-expedition-ajax.php",
        data: dataparam,
        type: "POST",
        beforeSend: function(){
        },
        success: function (data) {
            $("#tasklist").load("assignment/expedition/assignment-expedition-tasks.php #tasklist > *",{"id":$('#calltaskdiv').val()});
            $("#destlist").load("assignment/expedition/assignment-expedition-preview.php #destlist > *",{"id":$('#calldestdiv').val()});
        },
    });
}

function fn_expeditionajaxtask(schid,schtype,expid,resid,taskid,destid)
{    
    var dataparam = "oper=savereadstatustasktest&expid="+expid+"&destid="+destid+"&taskid="+taskid+"&resid="+resid+"&schid="+schid+"&schtype="+schtype;   
    $.ajax({
        url: "assignment/expedition/assignment-expedition-ajax.php",
        data: dataparam,
        type: "POST",
        beforeSend: function(){
        },
        success: function (data) {
            $("#tasklist").load("assignment/expedition/assignment-expedition-tasks.php #tasklist > *",{"id":$('#calltaskdiv').val()});
            $("#destlist").load("assignment/expedition/assignment-expedition-preview.php #destlist > *",{"id":$('#calldestdiv').val()});
        },
    });
}