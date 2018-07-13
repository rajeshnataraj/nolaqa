/*
	Created By - Sathya
	Page - reports-classroom.js
	History:
*/

/*******fn_load_school_purcahse()
		Function is used to load the school purchase
******/
function fn_schoolpurchase(type)
{	
    $('#expirationdate').hide();
    $('#viewreportdiv').hide();
    var dataparam = "oper=showschoolpurchase";
    $.ajax({
            type: 'post',
            url: 'reports/licenserenewal/reports-licenserenewal-ajax.php',
            data: dataparam,
            beforeSend: function(){                    
            },
            success:function(data) {		
                    $('#schoolsdiv').html(data);
            }
    });
	
}
/*******fn_load_home_purcahse()
		Function is used to load the home purchase
******/
function fn_homepurchase(type)
{
    $('#expirationdate').hide();
    $('#viewreportdiv').hide();
    var dataparam = "oper=showhomepurchase";
    $.ajax({
            type: 'post',
            url: 'reports/licenserenewal/reports-licenserenewal-ajax.php',
            data: dataparam,
            beforeSend: function(){                    
            },
            success:function(data) {		
                    $('#schoolsdiv').html(data);
            }
    });
   
}

/*******fn_load_home_purcahse()
		Function is used to load the home purchase
******/
function fn_distpurchase(type)
{	
    $('#expirationdate').hide();
    $('#viewreportdiv').hide();
    
    var dataparam = "oper=showdistpurchase";
    $.ajax({
            type: 'post',
            url: 'reports/licenserenewal/reports-licenserenewal-ajax.php',
            data: dataparam,
            beforeSend: function(){
            },
            success:function(data) {		
                    $('#schoolsdiv').html(data);
            }
    });
}
	


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
    
    if(leftlist=="list9" || leftlist=="list10" && rightlist=="list10" || rightlist=="list9"  )
    {
       $('#expirationdate').show();
       $('#viewreportdiv').show();
    }
    
}


function fn_licenserenewalreport()
{
    var categoryid=$('#categoryid').val();
    var sdate=$('#startdate').val();
    var schoolids = [];

    $("div[id^=list10_]").each(function()
    {
       var guid = $(this).attr('id').replace('list10_','');
       schoolids.push(guid);
    });

    if(schoolids=='')
    {
        if(categoryid==1){
            showloadingalert("please select any School.");	
            setTimeout('closeloadingalert()',2000);
            return false;
        }
        else if(categoryid==2){
            showloadingalert("please select any Users.");	
            setTimeout('closeloadingalert()',2000);
            return false;
        }
        else{
            showloadingalert("please select any District.");	
            setTimeout('closeloadingalert()',2000);
            return false;
        }
          
    }
    
    if(sdate=='')
    {
           showloadingalert("please Select the Expiration date.");	
           setTimeout('closeloadingalert()',2000);
           return false;
    }
  
    var val = categoryid+"~"+schoolids+"~"+sdate;
    
    setTimeout('removesections("#reports-licenserenewal");',500);
    oper="licenserenewalreport";
    filename=$("#hidfilename").val()+new Date().getTime();
    //alert(val);
    ajaxloadingalert('Loading, please wait.');
    setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&oper='+oper+'&filename='+filename+'");',500);
}

 function fn_exportlicreport() {
	var categoryid=$('#categoryid').val();
    var sdate=$('#startdate').val();
    var schoolids = [];

    $("div[id^=list10_]").each(function()
    {
       var guid = $(this).attr('id').replace('list10_','');
       schoolids.push(guid);
    });

    if(schoolids=='')
    {
        if(categoryid==1){
            showloadingalert("please select any School.");	
            setTimeout('closeloadingalert()',2000);
            return false;
        }
        else if(categoryid==2){
            showloadingalert("please select any Users.");	
            setTimeout('closeloadingalert()',2000);
            return false;
        }
        else{
            showloadingalert("please select any District.");	
            setTimeout('closeloadingalert()',2000);
            return false;
        }
          
    }
    
    if(sdate=='')
    {
           showloadingalert("please Select the Expiration date.");	
           setTimeout('closeloadingalert()',2000);
           return false;
    }
  
    var val = categoryid+"~"+schoolids+"~"+sdate;

     window.location='reports/licenserenewal/reports-licenserenewal-export.php?id='+val;
}

