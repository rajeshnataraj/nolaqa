/*----
    fn_showstudent()
	Function to Load the Student Dropdown
	id -> Classid
----*/

function fn_showstudent(clsid,expid,rubid)
{  
	$("#reports-pdfviewer").hide("fade").remove();
	$('#viewreportdiv').hide();
         $('#rubricstmt').hide();
	var dataparam = "oper=showstudent&clsid="+clsid+"&expid="+expid+"&rubid="+rubid;     
	$.ajax({
		type: 'post',
			url: 'library/rubric/library-rubric-digitalrubricajax.php',
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
                    if(list10.length==4)
                    {
                        $.Zebra_Dialog("Maximum 4 Students allowed.", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
                        return false;
                    }
                    else
                    {
                        $('#'+rightlist).append($('#'+leftlist+' #'+leftlist+'_'+id));
                        $('#'+leftlist+'_'+id).removeClass('draglinkleft').addClass('draglinkright');
                        var temp = $('#'+leftlist+'_'+id).attr('id').replace(leftlist,rightlist);					
                        var ids='id';
                        $('#'+leftlist+'_'+id).attr(ids,temp);
                    }
			
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
            fn_showrupric(courseid,$('#rubricid').val(),$('#rubid').val());
           
        }
         
}

function fn_showrupric(expid,rubricid,rubid)
{
        var dataparam = "oper=showrubric&expid="+expid+"&rubricid="+rubricid+"&rubid="+rubid;       
	$.ajax({
		type: 'post',
                url: 'library/rubric/library-rubric-digitalrubricajax.php',
		data: dataparam,
		beforeSend: function(){			 	
		},
		success:function(data) {
			$('#rubricstmt').show();	
			$('#rubricstmt').html(data);//Used to load the student details in the dropdown
                        $('#viewreportdiv').show();	
		}
	});
}

function fn_deletedigitalrubric(id)
{
	$.Zebra_Dialog('Are you sure you want to delete?',
	{
		'type': 'confirmation',
		'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {
				
				var dataparam = "oper=deleterubric&rubid="+id;
				$.ajax({
                                  	url: 'library/rubric/library-rubric-digitalrubricajax.php',
                                    data: dataparam,
                                    type: "POST",
                                    beforeSend: function(){
                                            showloadingalert("Deleting please wait.");	
                                    },
                                    success: function (data) {	
                                            if(data=="success") //Works if the data saved in db
                                            {
                                                    $('.lb-content').html("Digital Rubric has been Deleted Successfully");
                                                    setTimeout('closeloadingalert()',500);

                                                    setTimeout('removesections("#reports");',1000);
                                                    setTimeout('showpages("reports-digitalrubric","reports/digitalrubric/reports-digitalrubric.php");',1000);
                                            }
                                            else
                                            {
                                                    $('.lb-content').html("Invalid data So it cannot delete");
                                                    setTimeout('closeloadingalert()',1000);
                                            }
                                    },
                            });
			}
		}]
	});
}



function fn_saverubric(id,expid)
{	
    if($("#rubricforms").validate().form()) //Validates the Rubric Form
    {
        var list10 = [];
        $("div[id^=list10_]").each(function(){
               list10.push($(this).attr('id').replace('list10_',''));
        });
        if(list10!='')
        {
            if(id!='undefined' && id!=0 && id!=''){ //Works in Editing module
                actionmsg = "Saving";
                alertmsg = "Rubric has been Saved Successfully"; 
            }
            else { //Works in Creating a New Module
                actionmsg = "Saving";
                alertmsg = "Rubric has been Saved Successfully"; 
            }
        }
        else
        {
               $.Zebra_Dialog("Please select the student for Grade Student", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
               return false;
        } 
        
				showloadingalert(actionmsg+", please wait.");	
					$('.lb-content').html(alertmsg);
					setTimeout('closeloadingalert()',500);
					setTimeout('removesections("#library-rubric-rublist");',1000);
					setTimeout('showpageswithpostmethod("library-rubric-actions","library/rubric/library-rubric-actions.php","id='+$('#expid').val()+","+id+'");',1000);
        
				}

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
            url: 'library/rubric/library-rubric-digitalrubricajax.php',
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