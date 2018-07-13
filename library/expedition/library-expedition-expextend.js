
var timestamp=new Date().getTime();

document.domain = ITC_DOMAIN;

var contenturl = CONTENT_URL;

/****** this function to show the popup to get extent content text form******/
function fn_showettendform(id,extid,type){
	$.fancybox.showActivity();
        $.ajax({
			type	: "POST",
			cache	: false,
			url	: "library/expedition/library-expedition-expextendajax.php",
			data:"oper=extendtxtform&_="+timestamp+"&md_id="+id+"&extid="+extid+"&type="+type,
			success: function(data) {
				$.fancybox(data,{'modal': true,'autoDimensions':false,'width':480,'autoScale':true,'height':260, 'scrolling':'no'});
				$.fancybox.resize();
			}
		});
	
		return false;
}

/****** this function to HIDE the popup to get extent content text form******/
function fn_cancelextendform()
{
	$.fancybox.close();
}

/****** this function to save the popup to get details extent content text form in library-extend-ajax.php******/
function fn_saveextendform(id,extid,type)
{
	
	if($("#moduleextendforms").validate().form())
	{
	var extendtxt=$('#txtextensionname').val();	
	$.ajax({
			type	: "POST",
			cache	: false,
			url	: "library/expedition/library-expedition-expextendajax.php",
			data:"oper=saveextendtxt&_="+timestamp+"&md_id="+id+"&extendtxt="+escapestr(extendtxt)+"&extid="+extid+"&type="+type,
			success: function(data) {
			 
			  if(type=='new' || type=='copy')
			  {	
				var response=trim(data);
				var output=response.split('~');
				var status=output[0];
				var extendid=output[1];
				var extendcratename=output[2];
				var extenduid=output[3];
				var enmoduleid=output[4];
				var moduleid=output[5];
				var modulename=output[6];
				var filename=output[7];
				var userid=output[8];
				var exptype=15;
				
				if(status=="sucess")
				{
				  fn_cancelextendform();
				  $('#module-extend-0').remove();
				  var Content1='<tr class="Btn" id="module-extend-'+extendid+'"><td width="22%" id="extendtxt-'+extendid+'" class="createnewtd">&nbsp;&nbsp;&nbsp;'+extendtxt+'</td><td width="22%" class="createnewtd">&nbsp;&nbsp;&nbsp;'+extendcratename+'</td>';
				 var Content2='<td class="createnewtd"><div style="margin-left: 74px;" ><div onclick="fn_showettendform(\''+enmoduleid+'\','+extendid+',\'rename\')" class="rename-btn"></div><div class="copy-btn" onclick="fn_showettendform(\''+enmoduleid+'\','+extendid+',\'copy\')">';
				 var Content3='</div><div class="delete-btn" onclick="deleteextendtext('+extendid+');" ></div><div onclick="loadiframes12(\''+moduleid+'\','+userid+'\,'+exptype+'\,'+extendid+');" class="edit-btn"></div></div></td></tr>';
				 var newRowContent=Content1+Content2+Content3;
				 $("#extendtable tbody").append(newRowContent);
				}
			 }
			  else
			 {				
				 fn_cancelextendform();
				 $('#extendtxt-'+extid).html(extendtxt);
			 }
			 
		 }
		
		});
	}
	
}

/****** this function to delete the extent content text form in library-extend-ajax.php******/
function deleteextendtext(id)
{
	$.Zebra_Dialog('Are you sure you want to delete ?',
        {
       'type':     'confirmation',
       'buttons':  [
                        {caption: 'No', callback: function() { return false; }},
                        {caption: 'Yes', callback: function() { 

	var excflag=0;
	$.ajax({
			type	: "POST",
			cache	: false,
			url	: "library/expedition/library-expedition-expextendajax.php",
			data:"oper=checkextendcontent&ex_id="+id,
			success: function(data) {
				var response=trim(data);
				if(response=='fail')
				{
					$.Zebra_Dialog('This Extend content already assigned to schedular, If you delete the Extend content will be lost in schedular, Are you sure you want to delete ?',
							 {
							'type':     'confirmation',
							'buttons':  [
                                                                        {caption: 'No', callback: function() { return false; }},
                                                                        {caption: 'Yes', callback: function() { 

                                                                                $.ajax({
                                                                                            type	: "POST",
                                                                                            cache	: false,
                                                                                            url		: "library/expedition/library-expedition-expextendajax.php",
                                                                                            data:"oper=deleteextend&_="+timestamp+"&ex_id="+id+"&schflag=1",
                                                                                            success: function(data) {
                                                                                                    var response=trim(data);
                                                                                                    if(response=='fail')
                                                                                                    {                                                                                                            

                                                                                                    }
                                                                                                    else{

                                                                                                            $("#module-extend-"+id).remove();

                                                                                                            $("tr[id^=module-extend-]").each(function()
                                                                                                            {
                                                                                                                    excflag=1;
                                                                                                            });

                                                                                                            if(excflag==0)
                                                                                                            {
                                                                                                                    $('#extendtable').append('<tr id="module-extend-0"><td colspan="3" class="createnewtd">No Records</td></tr>');
                                                                                                            }
                                                                                                    }
                                                                                            }
                                                                                        });
                                                                        }},
                                                                    ]
							});
							return false;
				}
				else
				{						
						$.ajax({
								type	: "POST",
								cache	: false,
								url		: "library/expedition/library-expedition-expextendajax.php",
								data:"oper=deleteextend&_="+timestamp+"&ex_id="+id+"&schflag=0",
								success: function(data) {
									var response=trim(data);
									if(response=='fail')
									{										
									}
									else{
											$("#module-extend-"+id).remove();
											
											$("tr[id^=module-extend-]").each(function()
											{
																	excflag=1;
											});
											
											
											
											if(excflag==0)
											{
												$('#extendtable').append('<tr id="module-extend-0"><td colspan="3" class="createnewtd">No Records</td></tr>');
											}
									}
									
								}
						});
				}
				
			}
	});
	
	
													
	   }},
	]
 });
 return false;
	
}


// my new coding
function loadiframes12(expid,uid,exptype,exttendid)
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
    if(exptype == '1'){
		$('body').append('<div id="expedition-fullscreecn-header" title="Synergy ITC"><div id="divlbcontent"  style="background:#FFFFFF;"><iframe id="emapiframe" name="emapiframe" src="'+ CONTENT_EXP_URL + '/emaps-masterclassic/index.php?expid='+expid+'&uid='+uid+'&exttendid='+exttendid+'&height='+heigh+'&width='+weigh+'&hostname='+location.host+'" width="100%"></iframe></div></div>');
	}
    else if(exptype == '2'){
        $('body').append('<div id="expedition-fullscreecn-header" title="Synergy ITC"><div id="divlbcontent"  style="background:#FFFFFF;"><iframe id="emapiframe" name="emapiframe" src="'+ CONTENT_EXP_URL + '/emaps-masterstem/index.php?expid='+expid+'&uid='+uid+'&exttendid='+exttendid+'&height='+heigh+'&width='+weigh+'&hostname='+location.host+'" width="100%"></iframe></div></div>');
    }
    else {		
		if(expid=='54')
		{
        	$('body').append('<div id="expedition-fullscreecn-header" title="Synergy ITC"><div id="divlbcontent" style="background:#FFFFFF;"><iframe src="'+ CONTENT_EXP_URL + '/emaps-mastertest/index.php?expid='+expid+'&uid='+uid+'&exttendid='+exttendid+'&height='+heigh+'&width='+weigh+'&hostname='+location.host+'" width="100%"></iframe></div></div>');
		}
		else
		{
			$('body').append('<div id="expedition-fullscreecn-header" title="Synergy ITC"><div id="divlbcontent" style="background:#FFFFFF;"><iframe src="' + CONTENT_EXP_URL + '/emaps-master/index.php?expid='+expid+'&uid='+uid+'&exttendid='+exttendid+'&height='+heigh+'&width='+weigh+'&hostname='+location.host+'" width="100%"></iframe></div></div>');
		}
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
              'height' : $(window).height(),
            };

            $('#expedition-fullscreecn-header').css(cssObjOuter);
            $('#divlbcontent').css(cssObjInner);
            $('iframe').css({ 'width':$('#divlbcontent').width(), 'height' : $('#divlbcontent').height() });
            $('iframe').contents().find('body').css('backgroundColor', 'white');
        }
    });
}