/*
created by: vijayalakshmi (PHP Programmer)

Details: 


*/

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
       
    if(leftlist=="list3" || leftlist=="list4" && rightlist=="list4" || rightlist=="list3")
    {

        var assessids = [];
        
        $("div[id^=list4_]").each(function()
        {
            var guid = $(this).attr('id').replace('list4_','');
            assessids.push(guid);
        });
        
    
        if(assessids=='')
        {
            $('#viewreportdiv').hide();
        }
        else
        {
            $('#viewreportdiv').show();
        }
       
    }

}



function fn_viewreport(uid,id)
{	
    
    var assessids = [];
        
    $("div[id^=list4_]").each(function()
    {
        var guid = $(this).attr('id').replace('list4_','');
        assessids.push(guid);
    });
    
    if(id==1)
    {
        setTimeout('removesections("#reports-orassessment-byquestion");',500);
	var oper = "byquestion";
	var hidfilename = $("#hidquestn").val()+new Date().getTime();
        var val = assessids+"~"+uid+"~"+id;      
	ajaxloadingalert('Loading, please wait.');
	setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&oper='+oper+'&filename='+hidfilename+'");',500);
    }
    else if(id==2)
    {
        setTimeout('removesections("#reports-orassessment-byquestion");',500);
	var oper = "bystudents";
	var hidfilename = $("#hidstudent").val()+new Date().getTime();
        var val = assessids+"~"+uid+"~"+id;   
	ajaxloadingalert('Loading, please wait.');
	setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&oper='+oper+'&filename='+hidfilename+'");',500);
    }
    else
    {
        setTimeout('removesections("#reports-orassessment-byquestion");',500);
        var oper = "bystandard";
        var hidfilename = $("#hidquestand").val()+new Date().getTime();
        var val = assessids+"~"+uid+"~"+id;    
        ajaxloadingalert('Loading, please wait.');
        setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&oper='+oper+'&filename='+hidfilename+'");',500);
    }
    
}
