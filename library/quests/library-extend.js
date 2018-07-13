// JavaScript Document
/*
	Created By - Selvakumar .VA
	Page - library-extend-ajax
	Description:
	   
	   This page can work on the depends extend module content scripts are be here .
	   
	History:
	 no - update

*/


var timestamp=new Date().getTime();

document.domain = 'pitsco.com';

/****** this function to show the popup to get extent content text form******/
function fn_showettendform(id,extid,type){
	$.fancybox.showActivity();
      $.ajax({
			type	: "POST",
			cache	: false,
			url		: "library/quests/library-extend-ajax.php",
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
			url		: "library/quests/library-extend-ajax.php",
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
				  $('#module-extend-0').remove();
				  var Content1='<tr class="Btn" id="module-extend-'+extendid+'"><td id="extendtxt-'+extendid+'" class="createnewtd">&nbsp;&nbsp;&nbsp;'+extendtxt+'</td><td class="createnewtd">&nbsp;&nbsp;&nbsp;'+extendcratename+'</td>';
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
			url		: "library/quests/library-extend-ajax.php",
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
														url		: "library/quests/library-extend-ajax.php",
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
								url		: "library/quests/library-extend-ajax.php",
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
/****** this function to play the module content using library/modules/library-modules-playerdemo.php******/

/****** this function to save the module  content guide tips which given by the tacher using library/modules/library-modules-ajax.php******/

function showfullscreenmoduleextend(fldrname,type){	
	$('html, body').animate({scrollTop: '0px'}, 0);
	$('body').css('overflow','hidden');
	
	var cssObjOuter = {
      'display' : 'block',
      'width' : $('body').width(),
	  'height' : $(window).height()
    };
	
	var inner_fldr = fldrname.split(",");
	var ifrpath = '';	
	var contenturl = CONTENT_URL;

        if(location.host == "localhost") {
            contenturl = "http://localhost";
        }
	ifrpath = CONTENT_URL+'/moduleplay/questsplayerdemo.php';
	
	$('body').append('<div id="divcustomlightbox" title="Synergy ITC"><div id=""></div><iframe src="'+ifrpath+'?id='+escape(fldrname)+','+$(window).height()+'&hostname='+location.host+'" width="100%" height="100%"></iframe></div>');
	
	$('#divcustomlightbox').css(cssObjOuter);
	$('iframe').css({ 'width':$('#divcustomlightbox').width(), 'height' : $('#divcustomlightbox').height() });
	
}