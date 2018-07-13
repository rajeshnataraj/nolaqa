// JavaScript Document
/*
	Created By - vijayalakshmi php programmer
	Page - library-extend-ajax
	Description:
	   
	   This page is accessed by depends on the extend lessson content scripts .
	   
	History:
	 no - update

*/


var timestamp=new Date().getTime();

document.domain = 'pitsco.com';

/****** this function to rename or copy the  lesson extent content name******/

function fn_showextendform(id,extid,type){
    $.fancybox.showActivity();
     $.ajax({
            type	: "POST",
            cache	: false,
            url	: "library/lessons/library-extend-ajax.php",
            data    :"oper=extendtxtform&_="+timestamp+"&ln_id="+id+"&extid="+extid+"&type="+type,
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
            url		: "library/modules/library-extend-ajax.php",
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

                    if(status=="sucess")
                    {
                      fn_cancelextendform();
                      $('#lesson-extend-0').remove();
                      var Content1='<tr class="Btn" id="lesson-extend-'+extendid+'"><td width="22%" id="extendtxt-'+extendid+'" class="createnewtd">&nbsp;&nbsp;&nbsp;'+extendtxt+'</td><td width="22%" class="createnewtd">&nbsp;&nbsp;&nbsp;'+extendcratename+'</td>';
                     var Content2='<td class="createnewtd"><div style="margin-left: 74px;" ><div onclick="fn_showettendform(\''+enmoduleid+'\','+extendid+',\'rename\')" class="rename-btn"></div><div class="copy-btn" onclick="fn_showettendform(\''+enmoduleid+'\','+extendid+',\'copy\')">';
                     var Content3='</div><div class="delete-btn" onclick="deleteextendtext('+extendid+');" ></div><div onclick="showfullscreenmoduleextend(\'0,'+moduleid+',0,'+extendid+',1,'+userid+'\');" class="edit-btn"></div></div></td></tr>';
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

/****** this function to delete the extent content name in library-extend-ajax.php******/

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
            url		: "library/modules/library-extend-ajax.php",
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
                            url		: "library/lessons/library-extend-ajax.php",
                            data:"oper=deleteextend&_="+timestamp+"&ex_id="+id+"&schflag=1",
                            success: function(data) {
                                var response=trim(data);
                                if(response=='fail')
                                {                                     

                                }
                                else{

                                    $("#lesson-extend-"+id).remove();

                                    $("tr[id^=lesson-extend-]").each(function()
                                    {
                                            excflag=1;
                                    });

                                    if(excflag==0)
                                    {
                                            $('#extendtable').append('<tr id="lesson-extend-0"><td colspan="3" class="createnewtd">No Records</td></tr>');
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
                            url		: "library/lessons/library-extend-ajax.php",
                            data:"oper=deleteextend&_="+timestamp+"&ex_id="+id+"&schflag=0",
                            success: function(data) {
                                var response=trim(data);
                                if(response=='fail')
                                {                                       
                                }
                                else{
                                    $("#lesson-extend-"+id).remove();

                                    $("tr[id^=lesson-extend-]").each(function()
                                    {
                                      excflag=1;
                                    });

                                    if(excflag==0)
                                    {
                                            $('#extendtable').append('<tr id="lesson-extend-0"><td colspan="3" class="createnewtd">No Records</td></tr>');
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

/*
	function for fullscreen lesson play along with edit content using tinymce
*/

function showfullscreenlessonextend(fldrname,fldrnameinner,lessonextid,userid,access){	
  
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
	
    $('body').append('<div id="divcustomlightbox" title="Synergy ITC"><div class="btnprevclose"><p class="dialogTitleFullScr">Preview</p><a href="javascript:void(0);" onclick="closefullscreenlesson()" class="icon-synergy-close-dark"></a></div><div id="divlbcontent"><iframe src="'+CONTENT_URL+'/scormlib/lessonplayerdemo.php?Extend_ID='+lessonextid+'&lessonid='+fldrnameinner+'&zipname='+fldrname+'&uid='+userid+'&access='+access+'&hostname='+location.host+'" width="100%"></iframe></div><div class="diviplbottom"></div></div>');

    $('#divcustomlightbox').css(cssObjOuter);
    $('#divlbcontent').css(cssObjInner);
    $('iframe').css({ 'width':$('#divlbcontent').width(), 'height' : $('#divlbcontent').height() });
}

/****** this function to show the popup to get extent content text form for lessons ******/

function fn_showextendpopform(id,extid,type){
  
      $.fancybox.showActivity();
      $.ajax({
                type	: "POST",
                cache	: false,
                url	: "library/lessons/library-extend-ajax.php",
                data    :"oper=extendtxtform&_="+timestamp+"&ln_id="+id+"&extid="+extid+"&type="+type,
                success: function(data) {
                        $.fancybox(data,{'modal': true,'autoDimensions':false,'width':480,'autoScale':true,'height':260, 'scrolling':'no'});
                        $.fancybox.resize();
                }
		});
	
		return false;
}

/****** this function to save the popup to get details extent content text form in library-extend-ajax.php for lesson******/

function fn_saveextendlessonform(id,extid,type)
{
   
	
	if($("#lessonextendforms").validate().form())
        {
            var extendtxt=$('#txtextensionname').val();	
            $.ajax({
			type	: "POST",
			cache	: false,
			url	: "library/lessons/library-extend-ajax.php",
			data:"oper=saveextendtxt&_="+timestamp+"&ln_id="+id+"&extendtxt="+escapestr(extendtxt)+"&extid="+extid+"&type="+type,
                     
			success: function(data) {
          
			  if(type=='new' || type=='copy')
			  {
                              
				var response=trim(data);
				var output=response.split('~');
				var status=output[0];
				var extendid=output[1];
				var extendcreatename=output[2];
				var extenduid=output[3];
				var enlessonid=output[4];
				var lessonid=output[5];
				var lessonname=output[6];
				var filename=output[7];
				var userid=output[8];
                                var access=output[9];
				
				if(status=="sucess")
				{
                                
				  fn_cancelextendform();
				  $('#lesson-extend-0').remove();
				 var Content1='<tr class="Btn" id="lesson-extend-'+extendid+'"><td width="22%" id="extendtxt-'+extendid+'" class="createnewtd">&nbsp;&nbsp;&nbsp;'+extendtxt+'</td><td width="22%" class="createnewtd">&nbsp;&nbsp;&nbsp;'+extendcreatename+'</td>';
				 var Content2='<td class="createnewtd"><div style="margin-left: 74px;" ><div onclick="fn_showextendform(\''+enlessonid+'\','+extendid+',\'rename\')" class="rename-btn"></div><div class="copy-btn" onclick="fn_showettendform(\''+enlessonid+'\','+extendid+',\'copy\')">';
				 var Content3='</div><div class="delete-btn" onclick="deleteextendtext('+extendid+');" ></div><div onclick="showfullscreenlessonextend(\''+enlessonid+'\','+lessonid+','+extendid+','+userid+','+access+');" class="edit-btn"></div></div></td></tr>';
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
