// JavaScript Document
/*
	Created By - vijayalakshmi php programmer
	Page - library-extend-ajax
	Description:
	   This page is accessed by depends on the extend material content scripts .
        History:
	 no - update

*/


var timestamp=new Date().getTime();

document.domain = 'pitsco.com';

/****** this function to rename or copy the  material extent content name******/

function fn_showextendform(id,extid,type){
 
    $.fancybox.showActivity();
     $.ajax({
            type	: "POST",
            cache	: false,
            url	: "library/expedition/library-extend-ajax.php",
            data    :"oper=extendtxtform&_="+timestamp+"&materialid="+id+"&extid="+extid+"&type="+type,
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

/****** this function to delete the extent content name in library-extend-ajax.php******/

function deleteextendtext(id)
{
var dataparam = "oper=checkextendcontent&ex_id="+id;
var excflag=0;
$.ajax({
 type   : "POST",
            cache   : false,
            url     : "library/expedition/library-extend-ajax.php",
            data:dataparam,
            success: function(data) {

  var response=trim(data);
if(response=='fail')
        {
$.Zebra_Dialog('This Extend content already assigned to schedular, If you delete the Extend content will be lost in schedular, Are you sure you want to delete ?',
    {
        'type': 'confirmation',
        'buttons': [
            {caption: 'No', callback: function() { }},
            {caption: 'Yes', callback: function() {
var assnddataparam = "oper=deleteextend&_="+timestamp+"&ex_id="+id+"&schflag=1";

                            $.ajax({
                            type    : "POST",
                            cache   : false,
                            url     : "library/expedition/library-extend-ajax.php",
                            data:assnddataparam,
 success: function(data) {
 var response=trim(data);
if(response == 'sucess')
{

$("#exp-extend-"+id).remove();

                                    $("tr[id^=exp-extend-]").each(function()
                                    {
                                            excflag=1;
                                    });

                                    if(excflag==0)
                                    {
                                            $('#extendtable').append('<tr id="exp-extend-0"><td colspan="3" class="createnewtd">No Records</td></tr>');
                                    }
}


}
});


            }
        }]
    });

}
else if(response=='success')
        {
$.Zebra_Dialog('Are you sure you want to delete ?',
    {
        'type': 'confirmation',
        'buttons': [
            {caption: 'No', callback: function() { }},
            {caption: 'Yes', callback: function() {

$.ajax({
                            type    : "POST",
                            cache   : false,
                            url     : "library/expedition/library-extend-ajax.php",
                            data:"oper=deleteextend&_="+timestamp+"&ex_id="+id+"&schflag=0",
                            success: function(data) {                              
                                var response=trim(data);
                                if(response=='fail')
                                {                                     
                                }
                                else{
                                    $("#exp-extend-"+id).remove();

                                    $("tr[id^=exp-extend-]").each(function()
                                    {
                                      excflag=1;
                                    });
                                    if(excflag==0)
                                    {
                                            $('#extendtable').append('<tr id="exp-extend-0"><td colspan="3" class="createnewtd">No Records</td></tr>');
                                    }
                                }

                            }
                            });


                             }
        }]
    });


        }

}
});

}

/****** this function to show the popup to get extent content text form for material ******/

function fn_showextendpopform(id,extid,type){
  
      $.fancybox.showActivity();
      $.ajax({
                type	: "POST",
                cache	: false,
                url	: "library/expedition/library-extend-ajax.php",
                 data    :"oper=extendtxtform&_="+timestamp+"&materialid="+id+"&extid="+extid+"&type="+type,
                success: function(data) {
                        $.fancybox(data,{'modal': true,'autoDimensions':false,'width':480,'autoScale':true,'height':260, 'scrolling':'no'});
                        $.fancybox.resize();
                }
		});
	
		return false;
}

/****** this function to save the popup to get details extent content text form in library-extend-ajax.php for lesson******/

function fn_saveextendexpform(id,extid,type)
{
    	
	if($("#expextendforms").validate().form())
        {
            var extendtxt=$('#txtextensionname').val();	
            $.ajax({
			type	: "POST",
			cache	: false,
			url	: "library/expedition/library-extend-ajax.php",
			data:"oper=saveextendtxt&_="+timestamp+"&materialid="+id+"&extendtxt="+escapestr(extendtxt)+"&extid="+extid+"&type="+type,
                     
			success: function(data) {
                           
          
			  if(type=='new' || type=='copy')
			  {
                              
				var response=trim(data);
				var output=response.split('~');
				var status=output[0];
				var extendid=output[1];
				var extendcreatename=output[2];
				var extenduid=output[3];
				var enexpid=output[4];
				var expid=output[5];				
				var userid=output[6];
                                var access=output[7];
				
				if(status=="sucess")
				{
                                    closeloadingalert();	
                                    showloadingalert("Saved Sucessfully."); 
                                    setTimeout("closeloadingalert();",2000);
				  fn_cancelextendform();
				  $('#exp-extend-0').remove();
				 var Content1='<tr class="Btn" id="exp-extend-'+extendid+'"><td width="20%" id="extendtxt-'+extendid+'" class="createnewtd">&nbsp;&nbsp;&nbsp;'+extendtxt+'</td><td width="20%" class="createnewtd">&nbsp;&nbsp;&nbsp;'+extendcreatename+'</td>';
				 var Content2='<td class="createnewtd"><div style="margin-left: 10px;" ><div class="view-btn mainBtn" name="'+expid+','+extendid+'" id="btnlibrary-expedition-viewtaskmatlist"></div><div onclick="fn_showextendform(\''+enexpid+'\','+extendid+',\'rename\')" class="rename-btn"></div><div class="copy-btn" onclick="fn_showextendform(\''+enexpid+'\','+extendid+',\'copy\')">';
				 var Content3='</div><div class="delete-btn" onclick="deleteextendtext('+extendid+');" ></div><div class="edit-btn mainBtn" name="'+expid+','+extendid+','+userid+','+access+'" id="btnlibrary-expedition-viewmateriallist"></div></div></td></tr>';
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


function fn_savematerials(flag,expid,extendid,tempflag,uid)
{
    if(flag=="addmaterial") {
         if($("#extmaterialform").validate().form())
         {
            var destnname = $("#destnname").val();
            var taskname = $("#taskname").val();
            var material = $("#materialname").val();
            var expnmaterialid = $("#expmat_id").val();
            
           
            $.ajax({
                type	: "POST",
                cache	: false,
                url	: "library/expedition/library-extend-ajax.php",
                data:"oper=saveexpmaterials&_="+timestamp+"&destnname="+destnname+"&taskname="+taskname+"&material="+material+"&extendid="+extendid+"&expednname="+expid+"&expnmaterialid="+expnmaterialid,
                beforeSend: function(){
                        showloadingalert("Loading, please wait.");	
                },
                success: function(data) {
                if(data == "sucess")
                   {
                     closeloadingalert();	
                     showloadingalert("Saved Sucessfully."); 
                     setTimeout("closeloadingalert();",2000);
                     var val = expid+","+extendid+","+uid;
                     setTimeout("removesections('#library-expedition-materiallist');",500);	
                     setTimeout('showpages("library-expedition-viewmateriallist","library/expedition/library-expedition-viewmateriallist.php?id='+val+'");',500);
                   }
                }
            });
			
         }
		
    }
    
}
function fn_closematerial(expid,extendid,uid)  {
    closeloadingalert();	
    showloadingalert("Closed Sucessfully."); 
    setTimeout("closeloadingalert();",2000);
    var val = expid+","+extendid+","+uid;
    setTimeout("removesections('#library-expedition-materiallist');",500);	
    setTimeout('showpages("library-expedition-viewmateriallist","library/expedition/library-expedition-viewmateriallist.php?id='+val+'");',500);
}
function fn_loadtaskbox(destnid,extendid)
{  
     $.ajax({
                type	: "POST",
                cache	: false,
                url	: "library/expedition/library-extend-ajax.php",
                data:"oper=savetasklist&_="+timestamp+"&destnid="+destnid+"&extendid="+extendid,

                success: function(data) {
                   $('#taskselection').html(data);
                }
            });
}
function fn_loadtmaterialbox(taskid,destnid,extndid)
{
    $.ajax({
                type	: "POST",
                cache	: false,
                url	: "library/expedition/library-extend-ajax.php",
                data:"oper=savemateriallist&_="+timestamp+"&taskid="+taskid+"&destnid="+destnid+"&extndid="+extndid,

                success: function(data) { 
                   $('#materialselection').html(data);
                  ;
                }
            });
}
function fn_editexpmaterial(expmatid)
{
   dataparam="oper=showdefineexpmateril&expmatid="+expmatid;
    $.ajax({
        type: 'post',
        url: 'library/expedition/library-extend-ajax.php',
        data: dataparam,		
        beforeSend: function(){	
                showloadingalert("Loading, please wait.");
        },
        success:function(ajaxdata) {           
                closeloadingalert();
                var response=trim(ajaxdata);
		var data=response.split('~');               
                $('#materialformdet').show();
                $('#destn_name').html(data[0]);
                $('#destnname').val(data[1]);
                $('#task_name').html(data[2]);
                $('#taskname').val(data[3]);
                $('#material_name').html(data[4]);
                $('#materialname').val(data[5]);
                $('input[name=expmat_id]').val(data[6]);
                
        }
    });
}
function fn_deleteexpmaterial(expmatid,rowid)  {
    $.Zebra_Dialog('Are you sure you want to delete ?',
    {
    'type':     'confirmation',
    'buttons':  [
            {caption: 'No', callback: function() { }},
            {caption: 'Yes', callback: function() { 

            var dataparam = "oper=deletedefineexpmaterial&expmatid="+expmatid;
            $.ajax({
                    type: 'post',
                    url: "library/expedition/library-extend-ajax.php",
                    data: dataparam,
                    baforeSend:function(){
                         showloadingalert("Loading, please wait.");
                    },
                    success:function(data) {
                            showloadingalert("Deleted Successfully.");
                             $('.'+rowid).remove();
                            setTimeout("closeloadingalert()",1000);
             
                    }
            });	
                       
             }},
                ]
    });
	return false;
}
function fn_close(id)
{
 alert(id);
	$('#'+id).nextAll('section').hide("fade").remove();
}