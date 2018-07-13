/*
	Created By - SenthilNathan. S
	
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
	
    if(leftlist=="list5" || leftlist=="list6" && rightlist=="list6" || rightlist=="list5")
    {
        $('#studentdiv').show();
        var testids = [];
        var list6 = [];
        $("div[id^=list6_]").each(function()
        {
            var guid = $(this).attr('id').replace('list6_','');
            testids.push(guid);
        });
        
        
        fn_showstudent($('#classid').val(),testids);
    }
    
    if(leftlist=="list7" || leftlist=="list8" && rightlist=="list8" || rightlist=="list7")
    {
        $('#viewreportdiv').show();
        $('#standardsdiv').show();
       
    }
}


function fn_assessment(id)
{
    	$('#studentdiv').hide;
	var dataparam = "oper=showassessment&classid="+id; 
	$.ajax({
		type: 'post',
		url: 'reports/assesmentreports/reports-assesmentreports-gradeajax.php',
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
     var classid = [];

                   $("div[id^=list4_]").each(function()
                   {
                           var guid = $(this).attr('id').replace('list4_','');
                           classid.push(guid);


                   });
                    if(classid=='')
                    {
                       
                    $('#viewreportdiv').hide();
                    
                    }
}

function fn_showstudent(clsid,testid)
{

        $('#viewreportdiv').hide();
        if(testid==''){
             testid='0';
         }
	var dataparam = "oper=showstudent&testid="+testid+"&classid="+clsid;
	$.ajax({
		type: 'post',
		url: 'reports/assesmentreports/reports-assesmentreports-gradeajax.php',
		data: dataparam,
		beforeSend: function(){			
		},
		success:function(data) {		
			$('#studentdiv').html(data);//Used to load the student details in the dropdown
                       
		}
	});
        
        
        
}

function getradioval(rval)
{

    $('#hidradioval').val(rval);
}

function fn_gradereport(type,uid)
{	
	var val;
	
        if(type==1)
	{
            
                var assessid = [];
                var studid = [];
                $("div[id^=list6_]").each(function()
                {
                           var guid = $(this).attr('name').replace('list6_','');
                           assessid.push(guid);


                });
                $("div[id^=list8_]").each(function()
                {
                        var guid = $(this).attr('id').replace('list8_','');
                        studid.push(guid);


                });
                   
               
                var radioval = $('#hidradioval').val();
                
                
                
                $("#reports-pdfviewer").hide("fade").remove();
                   
		val = type+"~"+assessid+"~"+studid+"~"+$('#classid').val()+"~"+uid+"~"+radioval; 

		setTimeout('removesections("#reports-assesmentreports-studentmastery");',500);
		var oper="studentmastery";
		var filename=$("#hidassengname").val()+new Date().getTime();
                    }
            
        ajaxloadingalert('Loading, please wait.');
	setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&oper='+oper+'&filename='+filename+'");',500);	
}


