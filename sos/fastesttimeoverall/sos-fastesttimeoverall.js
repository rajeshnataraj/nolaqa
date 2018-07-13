
function fn_showdetails(dsid,rowid)
{
   $.fancybox.showActivity();
    $.ajax({
        type	: "POST",
        cache	: false,
        url     : "sos/fastesttimeoverall/sos-fastesttimeoverall-ajax.php",
      
        data:"oper=showcardetails&dsid="+dsid+"&rowid="+rowid,
        success: function(data) {
                $.fancybox(data,{'modal': true,'autoDimensions':false,'width':550,'autoScale':true,'height':526, 'scrolling':'no'});
                $.fancybox.resize();
                //--- New Query for next and previews button start page----//
                var teachclickstuid =$('#teachclickstuid').val();
                var previews =$('#previewsstuid').val();
                var next =$('#nextstuid').val();
                var firstid=$('#firstid').val();
                var lastid=$('#lastid').val();
                var current=$('#currentstuid').val();

                if(teachclickstuid==teachclickstuid && next==firstid && current==firstid)
                {
                    $('#btnpre').addClass('dim');
                }
                else if(next==firstid && previews==firstid)
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

/* Function pop-up close */
function fn_cancelextendform()
{
	$.fancybox.close();
}


function fastesttimeoverall_view()
{
       
    var tracklenvalue=$('#tracklen').val();
    
   if(tracklenvalue==""){
        showloadingalert("please select any Track Length.");	
           setTimeout('closeloadingalert()',2000);
           return false;
	}
       var val= tracklenvalue;   
	
        fn_cancel('sos-fastesttimeoverall');
     	setTimeout('showpageswithpostmethod("sos-fastesttimeoverall-preview","sos/fastesttimeoverall/sos-fastesttimeoverall-preview.php","id='+ val+'");',500);
    
}

function fn_fastesttimeoverall(uid)
{	
        var tracklenvalue=$('#tracklen').val();
    	setTimeout('removesections("#sos-fastesttimeoverall-preview");',500);    
    	var oper="fastesttimeoverallreport";
	var filename=$("#hidfilename").val()+new Date().getTime();
        var val=tracklenvalue+"~"+uid;
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
