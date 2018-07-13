/************District Admin****************/

function fn_showclass(schoolid)
{
    $('#viewreportdiv').hide();
    $('#showexp').hide();
    $('#showrub').hide();
	$('#studentdiv').hide();
    $('#viewreportdiv').hide();
	var dataparam = "oper=showclass&schoolid="+schoolid;       
	$.ajax({
		type: 'post',
	    url: 'reports/digitalrubric/reports-digitalrubric-ajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#classdiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {
			$('#classdiv').show();	
			$('#classdiv').html(data);//Used to load the student details in the dropdown
                        
		}
	});
}

/************District Admin****************/


function fn_showsch(clsid)
{   
	$('#viewreportdiv').hide();
    $('#showexp').hide();
    $('#showrub').hide();
	$('#studentdiv').hide();
    $('#viewreportdiv').hide();
	var dataparam = "oper=showschedule&clsid="+clsid; 
	$.ajax({
		type: 'post',
        url: 'reports/digitalrubric/reports-digitalrubric-ajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#showsch').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data)
        {
			$('#showsch').show();	
			$('#showsch').html(data);//Used to load the student details in the dropdown
		}
	});
}


function fn_showexp(schid)
{   
    $('#viewreportdiv').hide();
    $('#showrub').hide();
    $('#studentdiv').hide();
    $('#viewreportdiv').hide();

    var clsid=$("#classid").val();
    var dataparam = "oper=showexpedition&schid="+schid+"&clsid="+clsid;   
	$.ajax({
		type: 'post',
        url: 'reports/digitalrubric/reports-digitalrubric-ajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#showexp').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {
			$('#showexp').show();	
			$('#showexp').html(data);//Used to load the student details in the dropdown
		}
	});
}

/*----
    fn_showrubric()
	Function to Load the Rubric Dropdown
	id -> Classid
----*/
function fn_showrubric(expid)
{      
	$('#viewreportdiv').hide();
	$('#studentdiv').hide();
    $('#viewreportdiv').hide();
    var clsid=$("#classid").val();
    var schid=$("#schid").val();
    
	var dataparam = "oper=showrubric&expid="+expid+"&clsid="+clsid+"&schid="+schid;     
	$.ajax({
		type: 'post',
        url: 'reports/digitalrubric/reports-digitalrubric-ajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#showrub').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {
			$('#showrub').show();	
			$('#showrub').html(data);//Used to load the student details in the dropdown
		}
	});
}


/*----
    fn_showstudent()
	Function to Load the Student Dropdown
	id -> Classid
----*/

function fn_showstudent(rubid)
{ 
    var clsid=$("#classid").val();
    var expid=$("#expid").val();
    var schid=$("#schid").val();
    
	$('#viewreportdiv').hide();
  
	var dataparam = "oper=showstudent&clsid="+clsid+"&expid="+expid+"&rubid="+rubid+"&schid="+schid;        
	$.ajax({
		type: 'post',
        url: 'reports/digitalrubric/reports-digitalrubric-ajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#studentdiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {
			$('#studentdiv').show();	
			$('#studentdiv').html(data);//Used to load the student details in the dropdown
		}
	});
}






function fn_movealllistitems(leftlist,rightlist,id,courseid)
{
    var list9 = [];
        $("div[id^=list9_]").each(function(){
                list9.push($(this).attr('id').replace('list9_',''));
        });
     
    var list10 = [];
        $("div[id^=list10_]").each(function(){
                list10.push($(this).attr('id').replace('list10_',''));
        });
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
        var list10 = [];

        $("div[id^=list10_]").each(function(){
            list10.push($(this).attr('id').replace('list10_',''));
        });

        if(list10!='')
        {
            $('#viewreportdiv').show();	
        }
        else
        {
            $('#viewreportdiv').hide();	
        }
    }
         
}


function fn_digitalrubric()
{
    
    var stuid = [];

    $("div[id^=list10_]").each(function()
    {
       var guid = $(this).attr('id').replace('list10_','');
       stuid.push(guid);
    });

    if(stuid=='')
    {
           showloadingalert("please select any student.");	
           setTimeout('closeloadingalert()',2000);
           return false;
    }
    
    var clsid=$('#classid').val();
    var schduleid=$("#schid").val();
    var expid=$('#expid').val();
    var rubid=$('#rubid').val();
   
    var val = expid+"~"+clsid+"~"+rubid+"~"+stuid+"~"+schduleid;
    
    setTimeout('removesections("#reports-digitalrubric");',500);
    oper="digitalrubricreport";
    filename=$("#hidfilename").val()+new Date().getTime();

    
    ajaxloadingalert('Loading, please wait.');
    setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&oper='+oper+'&filename='+filename+'");',500);
}


