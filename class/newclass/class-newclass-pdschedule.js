
function fn_pdloadcontent(lid,sid,flag)
{
  $('#schenddate').hide();	
  
    if(sid==0 || sid=='') //show the select template dropdown is when create new schecule
    {
           $('#pdtemplate').show();
            $('#triadtemplate,#dyadtemplate,#stemplate').hide();		
    }

    if(flag==1)//comes from  select template
            sid = $('#pdtemplateid').val();

    dataparam="oper=loadpdcontent&lid="+lid+"&sid="+sid+"&flag="+flag;	
    $.ajax({
            type: 'post',
            url: 'class/newclass/class-newclass-pdschedule-ajax.php',
            data: dataparam,		
            beforeSend: function(){                    	
            },
            success:function(ajaxdata) {                    
                    $('#units').html(ajaxdata);	                    
                    fn_orderpdlessons(sid,lid,flag);		
            }
    });	
}


function fn_orderpdlessons(sid,lid,flag)
{
        var list15 = [];
        $("div[id^=list15_]").each(function()
	{
		list15.push($(this).attr('id').replace('list15_',''));
	});	
	dataparam="oper=loadorderpdlessons&sid="+sid+"&courseids="+list15+"&lid="+lid+"&flag="+flag;       
	$.ajax({
		type: 'post',
		url: 'class/newclass/class-newclass-pdschedule-ajax.php',
		data: dataparam,		
		beforeSend: function(){			
		},
		success:function(ajaxdata) {							
			$('#ipls').html(ajaxdata);
		}
	});
}

function fn_savepdschedule(id){
    if(id==0)
        var msg = "Saving,";
    else
        var msg = "Updating,";
    if($("#scheduleform").validate().form())
    {
        var studenttype = $('#studenttype').val();
        var list9=[];
        var list10=[];
        var list14=[];
        var list15=[];
        var pdgradepoint=[];
        var pdgradeflag=[];
        var lessid=[];
        var pdlessonflag=[];
        var unitid=[];
        if(studenttype==2){
            $("div[id^=list10_]").each(function()
            {
                    list10.push($(this).attr('id').replace('list10_',''));
            });
            $("div[id^=list9_]").each(function()
            {
                    list9.push($(this).attr('id').replace('list9_',''));
            });
            if(list10==''){
                    alert("Please select any one of student for schedule.");
                    return false;
            }
        }

        $("div[id^=list14_]").each(function()
        {
                list14.push($(this).attr('id').replace('list14_',''));
        });
        $("div[id^=list15_]").each(function()
        {
                list15.push($(this).attr('id').replace('list15_',''));
        });

        $('#selectpd input[id^=pdipl_]').each(function() { //new line
                var lessonid=$(this).val();
                lessid.push(lessonid);                
                if($('#pdgrade_'+lessonid).is(':checked')){
                        var tmpgrade=1;
                }
                else{
                        var tmpgrade=0;
                }
                if($('#pdipl_'+lessonid).is(':checked')){//new line
                        var tmpiplflag=1;
                }
                else{
                        var tmpiplflag=0;
                } //new line
                pdgradepoint.push($('#pdgradevalue_'+lessonid).val());
                pdgradeflag.push(tmpgrade); 
                pdlessonflag.push(tmpiplflag);//new line
        });	

        $("input[id^=course_]").each(function()
        {
                unitid.push($(this).attr('id').replace('course_',''));                
        });


        if(list15==''){
                alert("Please select any courses.");
                return false;
        }
        else if(lessid==''){
                alert("Please select any PDlesson.");
                return false;
        }

        var dataparam = "oper=savepdschedule&sid="+id+"&sname="+escapestr($('#sname').val())+"&startdate="+$('#startdate').val()+"&stype="+$('#scheduletype').val()+"&students="+list10+"&studenttype="+studenttype+"&classid="+$('#hidclassid').val()+"&list15="+list15+"&lessid="+lessid+"&licenseid="+$('#licenseid').val()+"&unstudents="+list9+"&pdgradeflag="+pdgradeflag+"&pdgradepoint="+pdgradepoint+"&pdlessonflag="+pdlessonflag;	//new line						
        $.ajax({
                type:'post',
                 url: 'class/newclass/class-newclass-pdschedule-ajax.php',
                data:dataparam,
                beforeSend: function(){
                        showloadingalert(msg+" please wait.");	
                },	
                success: function(data){                    
                        var data=data.split("~");
                        if(data[0]=='success'){	
                                $('.lb-content').html("Schedule saved successfully.");					
                                removesections('#class-newclass-steps');
                                removesections('#class-newclass-actions');						 		
                                setTimeout('showpageswithpostmethod("class-newclass-scheduledetails","class/newclass/class-newclass-scheduledetails.php","id='+$('#hidclassid').val()+","+$('#classtypeval').val()+'");',200);
                              setTimeout('showpageswithpostmethod("class-newclass-viewpdprogress","class/newclass/class-newclass-viewpdprogress.php","id='+data[1]+","+$('#hidclassid').val()+'");',2000);					
                        }
                        else if(data[0]=='fail'){
                                $('.lb-content').html("student limit exceeds");					
                        }
                        else if(data[0]=='invalid'){
                                $('.lb-content').html("Incorrect Data");					
                        }
                        setTimeout('closeloadingalert();',2000);	
                }
        });
    }	
}

function fn_deletepdschedule(scheduleid,type,classid)
{
  $.Zebra_Dialog('Are you sure you want to delete ?',
    {
    'type':     'confirmation',
    'buttons':  [
                {caption: 'No', callback: function() { }},
                {caption: 'Yes', callback: function() { 

                    dataparam="oper=deletepdschedule&scheduleid="+scheduleid+"&type="+type;	
                    $.ajax({
                        type: 'post',
                        url: 'class/newclass/class-newclass-pdschedule-ajax.php',
                        data: dataparam,		
                        beforeSend: function(){
                                showloadingalert('Loading, Please wait.');
                        },
                        success:function() {
                            setTimeout('closeloadingalert()',1000);
                            setTimeout("showloadingalert('Deleted Successfully.');",500);
                            setTimeout("closeloadingalert();",1000);

                            setTimeout("removesections('#class-newclass-steps');",500);	
                            setTimeout("removesections('#class-newclass-actions');",500);			
                            setTimeout('showpageswithpostmethod("class-newclass-scheduledetails","class/newclass/class-newclass-scheduledetails.php","id='+classid+","+$('#classtypeval').val()+'");',500);		

                        }
                    });
                }},

            ]
    });
}