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

function fn_checkstu()
{
	var val = $('#hidcheckstu').val();
	if(val==0)
		$('#hidcheckstu').val('1');
	else
		$('#hidcheckstu').val('0');
}

function fn_showtracklength(clsid)
{
        $('#viewreportdiv').hide();   
        var dataparam = "oper=showtracklength&classid="+clsid;
	$.ajax({
		type: 'post',
		url: 'sos/fastesttimeclass/sos-fastesttimeclass-ajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#tracklen').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {		
			$('#tracklen').html(data);//Used to load the year in the dropdown
                        $('#viewreportdiv').show();
		}
	});
}
/* Function pop-up close */
function fn_cancelextendform()
{
	$.fancybox.close();
}

function fn_showdetails(sosclsid,dsid,rowid)
{    
   $.fancybox.showActivity();
    $.ajax({
        type	: "POST",
        cache	: false,
        url     : "sos/fastesttimeclass/sos-fastesttimeclass-ajax.php",
      
        data:"oper=showcardetails&sosclsid="+sosclsid+"&dsid="+dsid+"&rowid="+rowid,
        success: function(data) {
                $.fancybox(data,{'modal': true,'autoDimensions':false,'width':550,'autoScale':true,'height':526, 'scrolling':'no'});
                $.fancybox.resize();
                //--- New Query for next and previews button start page----//
                var teachclickstuid =$('#teachclickstuid').val();
                var previews =$('#previewsstuid').val();
                var next =$('#nextstuid').val();
                var firstid=$('#firstid').val();
                var lastid=$('#lastid').val();

                if(teachclickstuid==firstid && next==firstid && previews==firstid)
                {
                    $('#btnpre').addClass('dim');
                }
                else
                {
                    $('#btnpre').removeClass('dim');
                }
                if(teachclickstuid==lastid && next==lastid && previews==lastid)
                {
                    $('#btnnext').addClass('dim');
                }
                else
                {
                    $('#btnnext').removeClass('dim');
                }
                //--- New Query for next and previews button End page----//
        }
    });

    return false; 
}


function fn_fastesttimeclass_view()
{
	var classid = [];
    $("div[id^=list2_]").each(function()
    {
       var guid = $(this).attr('id').replace('list2_','');
       classid.push(guid);
    });

    if(classid=='')
    {
           showloadingalert("please select any class.");	
           setTimeout('closeloadingalert()',2000);
           return false;
    }
    var stunamecheck=$('#hidcheckstu').val();
    var tracklenvalue=$('#tracklen').val();

    if(tracklenvalue=='')
    {
           showloadingalert("please select Track Length.");	
           setTimeout('closeloadingalert()',2000);
           return false;
    }
    
    fn_cancel('sos-fastesttimeclass');    
    setTimeout('showpageswithpostmethod("sos-fastesttimeclass-preview","sos/fastesttimeclass/sos-fastesttimeclass-preview.php","id='+classid+'~'+stunamecheck+'~'+tracklenvalue+'");',500);
}

    
    
function fn_fastesttimeclass(uid)
{	
    var classid = [];
    $("div[id^=list2_]").each(function()
    {
       var guid = $(this).attr('id').replace('list2_','');
       classid.push(guid);
    });
    if(classid=='')
    {
           showloadingalert("please select any class.");	
           setTimeout('closeloadingalert()',2000);
           return false;
    }
    var stunamecheck=$('#hidcheckstu').val();
    var tracklenvalue=$('#tracklen').val();
    var val = stunamecheck+"~"+classid+"~"+tracklenvalue+"~"+uid; 
    
    setTimeout('removesections("#sos-fastesttimeclass-preview");',500);
    var oper="fastesttimesclassreport";
    var filename=$("#hidfilename").val()+new Date().getTime();
       
    ajaxloadingalert('Loading, please wait.');
    setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&oper='+oper+'&filename='+filename+'");',500);

        }

//---- New Query in next and previews button by chandru---------- //
function fn_nextstudent(dsid,status)
{
         
        var teachclickstuid=$('#teachclickstuid').val();
        var currentstuid=$('#currentstuid').val();
        var previousstuid=$('#previewsstuid').val();
        var nextid=$('#nextstuid').val();
 
        var dataparam = "oper=nextstudent&previousstuid="+previousstuid+"&currentstuid="+currentstuid+"&nextid="+nextid+"&dsid="+dsid+"&status="+status;
        
        $.ajax({
                type:"POST",
                url : "sos/fastesttimeclass/sos-fastesttimeclass-ajax.php",
                data: dataparam,
                beforeSend: function(){                       
                },	
                success: function (ajaxdata) {
                    var dsspltdata = ajaxdata.split("~");
                    var datavalue=JSON.parse(dsspltdata[0]);
                    var j=2;
                    
                    for(var i=0;i<=datavalue.length;i++)
                    {
                        $('#txt_'+j+'_'+teachclickstuid).html(datavalue[i]);
                        j++;
                    }
                    setTimeout('closeloadingalert()',500);                    
                    if(status==1)
                    {
                        
                        $('#previewsstuid').val(dsspltdata[1]);
                        $('#nextstuid').val(dsspltdata[1]);
                        $('#currentstuid').val(dsspltdata[1]);
                    }
                    else
                    {
                        
                        $('#previewsstuid').val(dsspltdata[2]);
                        $('#nextstuid').val(dsspltdata[2]);
                        $('#currentstuid').val(dsspltdata[2]);
                    }
                    if(dsspltdata[4]==dsspltdata[1] ){
                        $('#btnnext').addClass('dim');
                    }
                    else{
                        $('#btnnext').removeClass('dim');
                    }
                    
                    if(dsspltdata[1]=='' && dsspltdata[2]==dsspltdata[3])
                    {
                        $('#btnpre').addClass('dim');
                    }
                    else
                    {
                        $('#btnpre').removeClass('dim');
                    }
                  
                }
            });
}
//---- New Query in next and previews button by chandru---------- //
			/* ------------------ End------------------- */


