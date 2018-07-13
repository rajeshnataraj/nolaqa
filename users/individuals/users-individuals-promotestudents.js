/*----
    fn_showstudent()
	Function to Load the Student Dropdown
	id -> Classid
----*/

function fn_showstudent()
{
        var gid=$("#ddlgrade").val();
	var dataparam = "oper=showstudent&gid="+gid;
	$.ajax({
		type: 'post',
			url: 'users/individuals/users-individuals-promotestudentsdb.php',
		data: dataparam,
		beforeSend: function(){
			$('#studentdiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {
			$('#studentdiv').show();
                        $('#selectgrade').show();
			$('#studentdiv').html(data);//Used to load the student details in the dropdown selectgrade
		}
	});
}

function fn_showsavebtn(){
    $('#savediv').show();
}




function fn_movealllistitems(leftlist,rightlist,id,courseid,lid)
{
	if(id == 0)
	{
		$("div[id^="+leftlist+"_]").each(function()
		{
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

/*----
    fn_savegrade()
	Function to save the save and update grade for selected students
----*/
function fn_savegrade()
{	
    var upgid=$("#ddlgrade1").val();
        var list10 = [];
        $("div[id^=list10_]").each(function(){
               list10.push($(this).attr('id').replace('list10_',''));
        });
        
        if(list10=='')
	{
		showloadingalert("please select any one student.");	 
		setTimeout('closeloadingalert()',2000);
		return false;
	}
        
        var dataparam = "oper=savestdgrade"+"&list10="+list10+"&upgid="+upgid;
	$.ajax({
		type: 'post',
			url: 'users/individuals/users-individuals-promotestudentsdb.php',
		data: dataparam,
		beforeSend: function(){
			showloadingalert("Loading, please wait.");
		},
		success:function(data) {
			closeloadingalert();
			showloadingalert("Promote of students has been completed.");
			setTimeout("closeloadingalert();",1000);
                        setTimeout("removesections('#users-individuals');",1000);
		}
	});
        
        
        
        
        
        
        
        
   

}

//auto save when click a category
function fn_showdeststmt(id,weight,rubid,destid,rubnameid)
{
    
    var multi=id*weight;
    var list10 = [];
    var textvalu=[];
    //multiply the value
    $('#txtscore-'+rubid).val(multi);
    
    //get the textbox value
    $('input:text[name=txtscore]').each(function() {
        textvalu.push($(this).val());
    });
   //get the selected student id
    $("div[id^=list10_]").each(function(){
           list10.push($(this).attr('id').replace('list10_',''));
    });
    
    var dataparam = "oper=saverubric&list10="+list10+"&expid="+$('#expid').val()+"&classid="+$('#classid').val()+"&txtscore="+multi+"&rubnameid="+rubnameid+"&ruborderid="+rubid+"&destid="+destid;
    $.ajax({
            url: 'users/individuals/users-individuals-promotestudentsdb.php',
            data: dataparam,
            type: "POST",
            beforeSend: function(){
                    showloadingalert("Saving, please wait...");	
            },
            success: function (data) {	
                    if(data=="success") //Works if the data saved in db
                    {
                        setTimeout('closeloadingalert()',500);

                    }
            },
    });
}