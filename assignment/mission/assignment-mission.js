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
	var misid = $('#hidmissionid').val();
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
            $('body').append('<div id="expedition-fullscreecn-header" title="Synergy ITC"><div class="expeditionprevclose" style="margin-bottom:10px;"><p class="dialogTitleFullScreexp darkTitle">'+urldecode(title)+'</p><a href="javascript:void(0);" onclick="closefullscreenmis()" class="icon-synergy-close-dark"></a></div><div id="divlbcontent" style="text-align:center;background:#FFFFFF;"><img src="img/lockimg.png" style="margin-top:130px;"></div><div class="divviewbottomexp" style="margin-top:10px;"></div></div>');
        }
        else
        {
            var newurl = CONTENT_EXP_URL + '/missionplaydev/mission.php?misid='+misid+'&media='+media+'&'+src[1]+'&height='+heigh+'&width='+weigh;
	    $('body').append('<div id="expedition-fullscreecn-header" title="Synergy ITC"><div class="expeditionprevclose" style="margin-bottom:10px;"><p class="dialogTitleFullScreexp darkTitle">'+urldecode(title)+'</p><a href="javascript:void(0);" onclick="closefullscreenmis()" class="icon-synergy-close-dark"></a></div><div id="divlbcontent" style="background:#FFFFFF;"><iframe src="'+newurl+'&hostname='+location.host+'" width="100%"></iframe></div><div class="divviewbottomexp" style="margin-top:10px;"></div></div>');
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
 
function fn_inserttpt(destid,taskid,taskguid,schid,misid){
    var dataparam = "oper=inserttpt&destid="+destid+"&taskid="+taskid+"&taskguid="+taskguid+"&schid="+schid+"&misid="+misid;
    $.ajax({
        url: "assignment/mission/assignment-mission-ajax.php",
        data: dataparam,
        type: "POST",
        beforeSend: function(){
        },
        success: function (data) {
        },
    });
}

 
function closefullscreenmis(){	
	$('body').css('overflow','auto');
	$('#expedition-fullscreecn-header').remove();
	$('#divlbcontent').remove();
	$("#destlist").load("assignment/mission/assignment-mission-preview.php #destlist > *",{"id":$('#calldestdiv').val()});
	$("#tasklist").load("assignment/mission/assignment-mission-tasks.php #tasklist > *",{"id":$('#calltaskdiv').val()});
	$("#reslist").load("assignment/mission/assignment-mission-resourses.php #reslist > *",{"id":$('#callresdiv').val()});	
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

function fn_closescreen1(expschid,misid,temp,destid,destorder,taskid){
    $('body').css('overflow','auto');
    $('#divcustomlightbox').remove();
    $('#divlbcontent').remove();
    $("html, body").animate({ scrollTop: $(document).height() }, "slow");
    if(temp == 1){
        var values = expschid+"~"+misid+"~"+18+"~"+0+"~"+0+"~"+0;
        setTimeout('removesections("#assignment");',300);
        setTimeout('showpageswithpostmethod("assignment-mission-preview","assignment/mission/assignment-mission-preview.php","id='+values+'");',400);
    }
    if(temp == 2){
        var values = destid+","+destorder+","+misid+","+expschid+","+18+","+0+","+0;
        alert(values);
        setTimeout('removesections("#assignment-mission-preview");',300);
        setTimeout('showpageswithpostmethod("assignment-mission-tasks","assignment/mission/assignment-mission-tasks.php","id='+values+'");',400);
    }
    if(temp == 3){
        var values = destid+","+taskid+","+destorder+","+misid+","+expschid+","+18+","+0+","+0;
        alert(values);
        setTimeout('removesections("#assignment-mission-tasks");',300);
        setTimeout('showpageswithpostmethod("assignment-mission-resourses","assignment/mission/assignment-mission-resourses.php","id='+values+'");',400);
    }
}

function fn_exptest(testid,etogglestatus,misid,schid){
        var exp="exp";
        var schtype="18";
	var val=testid+","+exp+","+schid+","+1+","+0+","+0+","+0;
	setTimeout('removesections("#assignment-assignmentengine-test");',500);
	setTimeout('showpages("assignment-assignmentengine-questions","assignment/assignmentengine/assignment-assignmentengine-questions.php?id='+val+'");',500);
	fn_expstatus(etogglestatus,schid,schtype,misid,testid)
}

function fn_expstatus(etogglestatus,schid,schtype,misid,testid){
    if(etogglestatus ==1){
        var dataparam = "oper=expteststatus&schid="+schid+"&schtype="+schtype+"&misid="+misid+"&testid="+testid;
    }
    $.ajax({
        url: "assignment/mission/assignment-mission-ajax.php",
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

function fn_desttest(testid,dtogglestatus,destid,misid,schid){
    var exp="exp";
    var schtype="18";
    var val=testid+","+exp+","+schid+","+1+","+0+","+0+","+0;
    setTimeout('removesections("#assignment-assignmentengine-test");',500);
    setTimeout('showpages("assignment-assignmentengine-questions","assignment/assignmentengine/assignment-assignmentengine-questions.php?id='+val+'");',500);
    fn_deststatus(dtogglestatus,schid,schtype,misid,destid,testid);
}

function fn_deststatus(dtogglestatus,schid,schtype,misid,destid,testid){   
    if(dtogglestatus ==1){
        var dataparam = "oper=destteststatus&schid="+schid+"&schtype="+schtype+"&misid="+misid+"&destid="+destid+"&preposttestid="+testid;
    } 
    $.ajax({
        url: "assignment/mission/assignment-mission-ajax.php",
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

function fn_tasktest(testid,ttogglestatus,taskid,destid,misid,schid,destorder){
    var exp="exp";
    var schtype="18";
    var val=testid+","+exp+","+schid+","+2+","+destid+","+destorder+","+0;
    setTimeout('removesections("#assignment-assignmentengine-test");',500);
    setTimeout('showpages("assignment-assignmentengine-questions","assignment/assignmentengine/assignment-assignmentengine-questions.php?id='+val+'");',500); 
    fn_taskstatus(ttogglestatus,schid,schtype,misid,taskid,destid,testid);
}


function fn_taskstatus(ttogglestatus,schid,schtype,misid,taskid,destid,testid){   
    if(ttogglestatus ==1){
        var dataparam = "oper=taskteststatus&schid="+schid+"&schtype="+schtype+"&misid="+misid+"&taskid="+taskid+"&destid="+destid+"&preposttestid="+testid;
    }   
    $.ajax({
        url: "assignment/mission/assignment-mission-ajax.php",
        data: dataparam,
        type: "POST",
        beforeSend: function(){
        },
        success: function (data) {
           var resid=0;
           fn_expeditionajaxtask(schid,schtype,misid,taskid,destid);         
        },
    });
}

function fn_restest(testid,togglestatus,resid,taskid,destid,misid,schid,taskorder){
    var exp="exp";
    var schtype="18";
    var val=testid+","+exp+","+schid+","+3+","+destid+","+taskorder+","+taskid;
    setTimeout('removesections("#assignment-assignmentengine-test");',500);
    setTimeout('showpages("assignment-assignmentengine-questions","assignment/assignmentengine/assignment-assignmentengine-questions.php?id='+val+'");',500); 
    fn_resstatus(togglestatus,schid,schtype,misid,resid,taskid,destid,testid);
 }

function fn_resstatus(togglestatus,schid,schtype,misid,resid,taskid,destid,testid){
    if(togglestatus ==1){
        var dataparam = "oper=resteststatus&schid="+schid+"&schtype="+schtype+"&misid="+misid+"&resid="+resid+"&taskid="+taskid+"&destid="+destid+"&preposttestid="+testid;
    }
    $.ajax({
        url: "assignment/mission/assignment-mission-ajax.php",
        data: dataparam,
        type: "POST",
        beforeSend: function(){
        },
        success: function (data) {
          
           fn_expeditionajaxres(schid,schtype,misid,resid,taskid,destid,testid);           
                            
          
        },
    });
}

function fn_expeditionajaxres(schid,schtype,misid,resid,taskid,destid)
{    
    var dataparam = "oper=savereadstatusrestest&misid="+misid+"&destid="+destid+"&taskid="+taskid+"&resid="+resid+"&schid="+schid+"&schtype="+schtype;    
    $.ajax({
        url: "assignment/mission/assignment-mission-ajax.php",
        data: dataparam,
        type: "POST",
        beforeSend: function(){
        },
        success: function (data) {
            $("#tasklist").load("assignment/mission/assignment-mission-tasks.php #tasklist > *",{"id":$('#calltaskdiv').val()});
            $("#destlist").load("assignment/mission/assignment-mission-preview.php #destlist > *",{"id":$('#calldestdiv').val()});
        },
    });
}

function fn_expeditionajaxtask(schid,schtype,misid,resid,taskid,destid)
{    
    var dataparam = "oper=savereadstatustasktest&misid="+misid+"&destid="+destid+"&taskid="+taskid+"&resid="+resid+"&schid="+schid+"&schtype="+schtype;   
    $.ajax({
        url: "assignment/mission/assignment-mission-ajax.php",
        data: dataparam,
        type: "POST",
        beforeSend: function(){
        },
        success: function (data) {
            $("#tasklist").load("assignment/mission/assignment-mission-tasks.php #tasklist > *",{"id":$('#calltaskdiv').val()});
            $("#destlist").load("assignment/mission/assignment-mission-preview.php #destlist > *",{"id":$('#calldestdiv').val()});
        },
    });
}



function fn_showmission(schid,schtype,misid,studentid,type)
{
     var stuid='';
     $('input:checkbox[name=students]:checked').each(function() 
     {
         if(stuid=='')
         {
            stuid=$(this).val();
         }
         else
         {
             stuid=stuid+","+$(this).val();
         }
     });
     
     if(stuid!=''){
         stuid=studentid+","+stuid;
     }
     else{
         stuid=studentid;
     }
     
     
     var id=schid+"~"+misid+"~"+schtype+"~"+stuid
     
     if (navigator.userAgent.match(/iPad/i) != null){
         removesections('#home');
    }
    else
    {
     removesections('#assignment'); 
    }
     
     if(type=="preview")
     {
        showpageswithpostmethod('assignment-mission-preview','assignment/mission/assignment-mission-preview.php','id='+id);
     }
     else if(type=="show")
     {
         showpageswithpostmethod('assignment-mission-show','assignment/mission/assignment-mission-show.php','id='+id);
     }

}