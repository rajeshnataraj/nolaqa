

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

function fn_select()
{   
     var schooldistid=[];
     
     $("div[id^=list2_]").each(function()
    {
        schooldistid.push($(this).attr('id').replace('list2_',''));
    });
    
    var teacherid=[];
                
    $("div[id^=list12_]").each(function()
    {
        teacherid.push($(this).attr('id').replace('list12_',''));
    });
    
    
    
    if(schooldistid=='' && teacherid=='')
    {
        showloadingalert("Please select any one School Assessments or Teacher Assessments.");	
        setTimeout('closeloadingalert()',2500);
        return false;
        
    }
    var dataparam = "oper=showselect&schooldistid="+schooldistid+"&teacherid="+teacherid;  
 
    $.ajax({
            type: 'post',
            url: 'test/testassign/test-testassign-testteacherdb.php',
            data: dataparam,
            beforeSend: function(){
                showloadingalert("Loading, please wait.");	
            },
            success:function(data) 
            {              
                if(data=='success')
                {
                    $('.lb-content').html("Assessment Saved");
                    setTimeout('closeloadingalert()',2000);
                    setTimeout('removesections("#home");',500);
                    setTimeout('showpages("test","test/test.php");',500);
                }
                else
                {

                }
            }
    });
}








