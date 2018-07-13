/*----
    fn_movealllistitems()
	Function to move from one list to another list
----*/

function fn_movealllistitems(leftlist,rightlist,id)
{
	if(id == 0)
	{
		$("div[id^="+leftlist+"_]").each(function()
		{
			var clas = $(this).attr('class');
                        
                        if(($(this).attr('class') == 'draglinkleft') || ($(this).attr('class') == 'draglinkright') ){
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
	
	/* Student count is displayed*/
	var list1 = [];
	$("div[id^=list1_]").each(function(){
		list1.push($(this).attr('id').replace('list1_',''));
	});
	$('#nostudentleftdiv').html(list1.length);

	var list2 = [];
	$("div[id^=list2_]").each(function(){
		list2.push($(this).attr('id').replace('list2_',''));
	});
	$('#nostudentrightdiv').html(list2.length);
}


/*---- 
    fn_student()
	Function to save student
----*/
function fn_schoolassign(testid)
{
	var list3 = [];
	var list4 = [];
	
	$("div[id^=list3_]").each(function(){
		list3.push($(this).attr('id').replace('list3_',''));
	});
	
	$("div[id^=list4_]").each(function(){
		list4.push($(this).attr('id').replace('list4_',''));
	});
	
					
	var dataparam = "oper=testtoshl"+"&testid="+testid+"&list3="+list3+"&list4="+list4;
	$.ajax({
		type: "POST",
		url: 'test/testassign/test-testassign-addtestdb.php',
		data: dataparam,
		beforeSend:function()
		{
			showloadingalert("Loading, please wait.");
		},
		success: function(data)
		{
			closeloadingalert();
			showloadingalert("Added Successfully");
			setTimeout("closeloadingalert();",1000);
			
			var val = testid;
			
			setTimeout('removesections("#test");',500);			
			setTimeout('showpageswithpostmethod("test-testassign-actions","test/testassign/test-testassign-actions.php","id='+val+'");',500);
		}
	});
}

/*---- 
    fn_downloadquestion()
	Function to Download the Assessment Questions
----*/
function fn_downloadquestion(val,flg){
	
	var oper = "assessmentquestionanskey";
        removesections("#test-testassign-testreviewmain");
        setTimeout('removesections("#test-testassign-testreview");',500);
	ajaxloadingalert('Loading, please wait.');
	setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&oper='+oper+'&filename='+$('#hidqfilename').val()+'&flg='+flg+'");',500);
}
function fn_downloadquestionrandom(val){
        removesections("#test-testassign-testrandomreviewmain");
        setTimeout('removesections("#test-testassign-testreview");',500);
	setTimeout('showpageswithpostmethod("test-pdfviewer","test/test-pdfviewer.php","id='+val+'&filename='+$('#hidqfilename').val()+'");',500);
       
}

function fn_downloadanswer(val,flg){
        setTimeout('removesections("#test-testassign-testreviewmain");',500);
        setTimeout('removesections("#test-testassign-testreview");',500);
        //removesections("#test-testassign-testreviewmain");
	var oper = "assessmentquestionanskey";
	ajaxloadingalert('Loading, please wait.');
	setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&oper='+oper+'&filename='+$('#hidqfilename').val()+'&flg='+flg+'");',500);
}