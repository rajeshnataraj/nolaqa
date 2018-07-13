
function fn_showsch(clsid,type)
{  
	$('#viewreportdiv').hide();
    $('#showexp').hide();
    $('#showrub').hide();
	$('#studentdiv').hide();
    $('#rubricstmt').hide();
	var dataparam = "oper=showschedule&clsid="+clsid+"&type="+type;      
	$.ajax({
		type: 'post',
        url: 'library/missionrubric/library-missionrubric-gradestudentrubricajax.php',
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


function fn_showexp(schid,type)
{
    $('#viewreportdiv').hide();
    $('#showrub').hide();
    $('#studentdiv').hide();
    $('#rubricstmt').hide();
    
    var clsid=$("#classid").val();
    var dataparam = "oper=showexpedition&schid="+schid+"&type="+type+"&clsid="+clsid;
	$.ajax({
		type: 'post',
        url: 'library/missionrubric/library-missionrubric-gradestudentrubricajax.php',
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


function fn_showrubric(expid,type)
{
    $('#viewreportdiv').hide();
	$('#studentdiv').hide();
    $('#rubricstmt').hide();
    var clsid=$("#classid").val();
    var schid=$("#schid").val();
    
	var dataparam = "oper=showrubric&expid="+expid+"&type="+type+"&clsid="+clsid+"&schid="+schid;
	$.ajax({
		type: 'post',
			url: 'library/missionrubric/library-missionrubric-gradestudentrubricajax.php',
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



function fn_showstudent(rubid,type)
{
    var clsid=$("#classid").val();
    var expid=$("#expid").val();
    var schid=$("#schid").val();
    
	$('#viewreportdiv').hide();
    $('#rubricstmt').hide();
    
	var dataparam = "oper=showstudent&clsid="+clsid+"&expid="+expid+"&rubid="+rubid+"&type="+type+"&schid="+schid;
	$.ajax({
		type: 'post',
			url: 'library/missionrubric/library-missionrubric-gradestudentrubricajax.php',
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


function fn_movealllistitems(leftlist,rightlist,id,courseid,typeid)
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
            var rubid=$("#rubid").val();
            var list10 = [];

            $("div[id^=list10_]").each(function(){
                list10.push($(this).attr('id').replace('list10_',''));
            });

            if(list10!='')
            {
                fn_showrubricstmt(courseid,rubid,typeid);
            }
            else
            {
                $('#rubricstmt').hide();	
                $('#viewreportdiv').hide();	
            }
        }
}


function fn_showrubricstmt(expid,rubid,typeid)
{
    var dataparam = "oper=showrubricstmt&expid="+expid+"&rubid="+rubid+"&type="+typeid;
	$.ajax({
		type: 'post',
                url: 'library/missionrubric/library-missionrubric-gradestudentrubricajax.php',
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

function fn_saverubric(id,expid)
{	
	var ids = [];
	var score = [];
	
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
        
        $('input[id^=rubrictxtoldval_]').each(function() 
		{
			score.push($(this).val());
   		});
		
		$("input[id^=ids_]").each(function()
		{
			ids.push($(this).attr('id').replace('ids_',''));
		})
		var schid=$("#schid").val();
		var classid=$("#classid").val();
		
		var dataparam = "oper=saverubricval&list10="+list10+"&expid="+expid+"&rubid="+id+"&ids="+ids+"&classid="+classid+"&schid="+schid+"&score="+score;
	   	$.ajax({
				url: 'library/missionrubric/library-missionrubric-gradestudentrubricajax.php',
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

}
//finish button clicking created by chandru 
function fn_finishrubric(id,expid)
{	
	var ids = [];
	var score = []; 
	$.Zebra_Dialog('Are you sure you are sure you want to finish and close?',
	{
		'type':     'confirmation',
		'buttons':  [
						{caption: 'Cancel', callback: function() { }},
						{caption: 'Don’t save', callback: function() { 
							setTimeout('removesections("#library-rubrics");',1000);
							setTimeout('showpages("library-missionrubric","library/missionrubric/library-missionrubric.php");',1000);
						}},
						{caption: 'Save', callback: function() { 
							
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
								
								$('input[id^=rubrictxtoldval_]').each(function() 
								{
									score.push($(this).val());

								});

								$("input[id^=ids_]").each(function()
								{
									ids.push($(this).attr('id').replace('ids_',''));
								})
								var schid=$("#schid").val();
								var classid=$("#classid").val();


								var dataparam = "oper=saverubricval&list10="+list10+"&expid="+expid+"&rubid="+id+"&ids="+ids+"&classid="+classid+"&schid="+schid+"&score="+score;
								$.ajax({
										url: 'library/missionrubric/library-missionrubric-gradestudentrubricajax.php',
										data: dataparam,
										type: "POST",
										beforeSend: function(){
												showloadingalert("Saving, please wait...");	
										},
										success: function (data) {	
											
												if(data=="success") //Works if the data saved in db
												{
													showloadingalert(actionmsg+", please wait.");	
													$('.lb-content').html(alertmsg);
													setTimeout('closeloadingalert()',500);
													setTimeout('removesections("#library-rubrics");',1000);
													setTimeout('showpages("library-missionrubric","library/missionrubric/library-missionrubric.php");',1000);

												}

										},
								});

								
							}
						}},
					]
	});
}

//reset button clicking created by chandru 
function fn_resetrubric(id,expid)
{
	var txtscore = [];
	
	$.Zebra_Dialog('Are you sure you want to reset all entered values to empty ?',
	{
		'type':     'confirmation',
		'buttons':  [
						{caption: 'No', callback: function() { }},
						{caption: 'Yes', callback: function() { 
							
							$("input[id^=txtscore-]").each(function()
							{
								txtscore.push($(this).attr('id').replace('txtscore-',''));
								var tscore = '';
								for(var i=0; i<txtscore.length; i++)
								{
									var text = txtscore[i];
									var res = text.split(",");
									if(tscore=='')
									{
										var textscore=res[0];
									}
									else
									{
										var textscore=textscore+","+res[0];
									}
									
									$('#txtscore-'+textscore).val('');
									$('#rubrictxtoldval_'+textscore).val('');
								}
							});
							$('.studentscore').text('');
							$('#totalscore').val('');
							$('.centerText').removeClass("td_select");
						}},
					]
	});
}
//auto save when click a category
function fn_showdeststmt(id,weight,rubid,destid,rubnameid,type)
{
    var multi=id*weight;
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
    
    
    var totalscore=  $('#totalscore').val();
    var prescore=  $('#rubrictxtoldval_'+rubid).val();
    var curscore=  multi;

    if(totalscore=='')
    {
        totalscore=0;
    }
    if(prescore=='')
    {
        prescore=0;
    }
    
    var tscore=parseInt(totalscore)-parseInt(prescore);
    var ftscore=parseInt(tscore)+parseInt(curscore);  
    
    $('#totalscore').val(ftscore);
    $('#rubrictxtoldval_'+rubid).val(curscore);
    $('#studentscore').html(ftscore); 
   
    var schid=$("#schid").val();   
}