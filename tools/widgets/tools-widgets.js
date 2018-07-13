/*----
    Created BY : MOhan M PHP Programmer.(28/10/2015)	
----*/
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
        
        /*************Turn off Widgets Individually code start here**********/
            if(leftlist=="list9" || leftlist=="list10" && rightlist=="list9" || rightlist=="list10")
            {
                var widgetsids=[];

                $("div[id^=list10_]").each(function()
                {
                    widgetsids.push($(this).attr('id').replace('list10_',''));
                });

                   $('#savereportdiv').show();
               
            }
        /*************Turn off Widgets Individually code end here**********/
        
        /*************Turn off Widgets per Content**********/
            if(leftlist=="list6" || leftlist=="list7" && rightlist=="list7" || rightlist=="list6")
            {
                var contentids=[];

                $("div[id^=list7_]").each(function()
                {
                    contentids.push($(this).attr('id').replace('list7_',''));
                });
               
                $('#savereportdiv').show();
               
            }
        /*************Turn off Widgets per Content**********/
        
        /**************Turn off Widgets based on Students Code start here*************/
           
   
}

function fn_showassignments(clsid)
{
    $('#studentdiv').hide();
    $('#savereportdiv').hide();
    var dataparam = "oper=showassignment&classid="+clsid;
	$.ajax({
		type: 'post',
		url: 'tools/widgets/tools-widgets-widgetsajax.php',
		data: dataparam,
		beforeSend: function(){
		},
		success:function(data) {
			$('#assignmentdiv').show();
			$('#assignmentdiv').html(data);//Used to load the expediations in the dropdown
		}
	});
}

function fn_showstudent(schid,type)
{
   
    var clsid=$('#classid').val(); 
    
    var dataparam = "oper=showstudent&assessid="+schid+"&schtype="+type+"&classid="+clsid;
    $.ajax({
            type: 'post',
            url: 'tools/widgets/tools-widgets-widgetsajax.php',
            data: dataparam,
            beforeSend: function()
            {
            },
            success:function(data)
            {
                $('#studentdiv').show();
                $('#studentdiv').html(data);//Used to load the expediations in the dropdown
                $('#savereportdiv').show();
            }
    });
}


/***********Turn off Widgets Individually Code start here****************/
function fn_saveind(pageid)
{
    var widgetid=[];
                
    $("div[id^=list10_]").each(function()
    {
        widgetid.push($(this).attr('id').replace('list10_',''));
    });
   
    actionmsg = "Saving";
    alertmsg = "Widgets has been disabled successfully"; 
        
    var dataparam = "oper=saveind&widgetid="+widgetid;
    $.ajax({
            type: 'post',
            url: 'tools/widgets/tools-widgets-widgetsajax.php',
            data: dataparam,
            beforeSend: function()
            {
                showloadingalert(actionmsg+", please wait.");	
            },
            success:function(data)
            { 
                $('.lb-content').html(alertmsg);
                setTimeout('closeloadingalert()',1000);
                setTimeout('$("#tools").nextAll().hide("fade").remove();',500);
                setTimeout('showpages("tools-widgets","tools/widgets/tools-widgets.php");',1000);
            }
    });
}
/***********Turn off Widgets Individually Code End here****************/

/*********Turn off Widgets per Content Code start here************/
function fn_savecont(pageid)
{
    var contentids=[];
                
    $("div[id^=list7_]").each(function()
    {
        contentids.push($(this).attr('id').replace('list7_',''));
    });
   
    actionmsg = "Saving";
    alertmsg = "Content has been Turn Off successfully"; 
    
    var dataparam = "oper=savecont&contentids="+contentids;
    $.ajax({
        type: 'post',
        url: 'tools/widgets/tools-widgets-widgetsajax.php',
        data: dataparam,
        beforeSend: function()
        {
                showloadingalert(actionmsg+", please wait.");	
        },
        success:function(data)
        {  
            $('.lb-content').html(alertmsg);
            setTimeout('closeloadingalert()',1000);
            setTimeout('$("#tools").nextAll().hide("fade").remove();',500);
            setTimeout('showpages("tools-widgets","tools/widgets/tools-widgets.php");',1000);
        }
    });
}
/*********Turn off Widgets per Content Code End here************/

/********Turn Off Based On Student Code start Here***************/
function fn_savestu(pageid)
{
    var assessids=[];
    var studids=[];
    
    if(pageid==3)
    {
        var classid=$('#classid').val();    
        var assessids=$('#assignid').val();
        var schtype=$('#schetype').val();
        
        $("div[id^=list4_]").each(function()
        {
            studids.push($(this).attr('id').replace('list4_',''));
        });        

        
    }
    actionmsg = "Saving";
    alertmsg = "Content has been Turn Off successfully"; 
    var dataparam = "oper=savestu&classid="+classid+"&assessid="+assessids+"&schtype="+schtype+"&stuid="+studids;
    $.ajax({
        type: 'post',
        url: 'tools/widgets/tools-widgets-widgetsajax.php',
        data: dataparam,
        beforeSend: function()
        {
                showloadingalert(actionmsg+", please wait.");	
        },
        success:function(data)
        {  
            $('.lb-content').html(alertmsg);
            setTimeout('closeloadingalert()',1000);
            setTimeout('$("#tools").nextAll().hide("fade").remove();',500);
            setTimeout('showpages("tools-widgets","tools/widgets/tools-widgets.php");',1000);
        }
    });
}
/********Turn Off Based On Student Code End here***************/





