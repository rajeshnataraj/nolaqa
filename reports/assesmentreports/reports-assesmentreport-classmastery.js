/*
	Created By - MOhan. M
        Created Date : 2-7-2015

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
     
        var classids = [];
        
        $("div[id^=list4_]").each(function()
        {
            var guid = $(this).attr('id').replace('list4_','');
            classids.push(guid);
        });
        
        
        fn_assessment(classids);
    }

}


function fn_assessment(clsid)
{
    	 $('#assessmentdiv').show();
         if(clsid==''){
             clsid='0';
         }
	var dataparam = "oper=showassessment&classid="+clsid;
	$.ajax({
		type: 'post',
		url: 'reports/assesmentreports/reports-assesmentreports-classmasteryajax.php',
		data: dataparam,
		beforeSend: function(){			
		},
		success:function(data) {
			$('#assessmentdiv').html(data);//Used to load the student details in the dropdown
		}
	});
}



function fn_viewshow()
{
    var assessids = [];

    $("div[id^=list6_]").each(function()
    {
            var guid = $(this).attr('id').replace('list6_','');
            assessids.push(guid);
    });
    if(assessids=='')
    {
        $('#viewreportdiv').hide();
    }
    else
    {
           $('#viewreportdiv').show();
         $('#standardsdiv').show();
    }
}


function getradioval(rval)
{

    $('#hidradioval').val(rval);
}

function fn_viewclassmastery(uid)
{	
    var classids = [];
    var assessids = [];

    $("div[id^=list4_]").each(function()
    {
        var guid = $(this).attr('id').replace('list4_','');
        classids.push(guid);
    });

    $("div[id^=list6_]").each(function()
    {
            var guid = $(this).attr('name').replace('list6_','');
            assessids.push(guid);
    });

    if(assessids=='')
    {
        $('#viewreportdiv').hide();
    }

    $("#reports-pdfviewer").hide("fade").remove();

    
    setTimeout('removesections("#reports-assesmentreports-classmastery");',500);
    var oper="classmasteryreport";
    var filename=$("#hidfilename").val()+new Date().getTime();
    var radioval = $('#hidradioval').val();
    var val=assessids+"~"+classids+"~"+uid+"~"+radioval;    

    
    ajaxloadingalert('Loading, please wait.');
    setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&oper='+oper+'&filename='+filename+'");',500);
}

