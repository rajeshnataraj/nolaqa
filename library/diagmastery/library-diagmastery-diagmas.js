/*
	Created By - Muthukumar. D
	Page - library-diagmastery-diagmas.js
	History:
*/

/*----
    fn_savedetails()
	Function to save details
	id -> Diagmastery id
----*/
function fn_savedetails(id)
{
	if($("#diagmasform").validate().form())
	{	
		if(id != '0'){
			actionmsg = "Updating";
			alertmsg = "Diag/Mastery Test has been Updated Successfully"; 
		}
		else {
			actionmsg = "Saving";
			alertmsg = "Diag/Mastery Test has been Created Successfully"; 
		}
		
		var dataparam = "oper=savediagmas&unitid="+$('#unitid').val()+"&lessonid="+$('#lessonid').val()+"&lessonweight="+$('#lessonweight').val()+"&editid="+id+"&tags="+escapestr($('#form_tags_diagmastery').val());		
		$.ajax({
			type: 'post',
			url: 'library/diagmastery/library-diagmastery-testajax.php',
			data: dataparam,		
			beforeSend: function(){
				showloadingalert(actionmsg+", please wait.");	
			},
			success:function(ajaxdata) {
				var ajaxdata = ajaxdata.split("~");	
				if(ajaxdata[0]=='success'){			
					$('.lb-content').html(alertmsg);
					setTimeout('closeloadingalert()',500);
					
					var val = ajaxdata[1]+","+2;
					$('#btnlibrary-diagmastery-testdetails').attr("name",ajaxdata[1]+',1');
					$('#diagques').parent().attr("name",ajaxdata[1]+',2');
					$('#mas1ques').parent().attr("name",ajaxdata[1]+',3');
					$('#mas2ques').parent().attr("name",ajaxdata[1]+',4');
					$('#btnlibrary-diagmastery-review').attr("name",ajaxdata[1]+',5');
					setTimeout('removesections("#library-diagmastery-steps");',1000);
					setTimeout('showpageswithpostmethod("library-diagmastery-diagques","library/diagmastery/library-diagmastery-diagques.php","id='+val+'");',1000);
				}
			}
		});
	}
}

/*----
    fn_savediag()
	Function to save diagnostic/mastery1/mastery2 questions
	id -> Diagmasteryid, step -> Stepid, type -> 1/2/3
	1 -> Diagnostic, 2 -> Mastery1, 3 -> Mastery2
	quesid -> Question ID's
----*/
function fn_savediag(id,step,type)
{
	var quesid=[];
	$('tr[id^=question_]').each(function(){			
		quesid.push($(this).attr('id').replace('question_',''));
	});
	
	var dataparam = "oper=savequestions&questionsid="+quesid+"&diagmasteryid="+id+"&type="+type;
	//alert(dataparam);
	$.ajax({
		type: 'post',
		url: 'library/diagmastery/library-diagmastery-testajax.php',
		data: dataparam,		
		beforeSend: function(){
			showloadingalert("Updating, please wait.");	
		},
		success:function(ajaxdata) {
			var ajaxdata = ajaxdata.split("~");	
			if(ajaxdata[0]=='success'){			
				$('.lb-content').html("Updated Successfully");
				setTimeout('closeloadingalert()',500);
				
				setTimeout('removesections("#library-diagmastery-steps");',1000);
				if(step!=4)
				{
					step++;
					var val = id+","+step;
					
					setTimeout('showpages("library-diagmastery-diagques","library/diagmastery/library-diagmastery-diagques.php?id='+val+'");',1000);
				}
				else if(step==4)
				{
					var val = id+","+5;
					
					setTimeout('showpages("library-diagmastery-review","library/diagmastery/library-diagmastery-review.php?id='+val+'");',1000);
				}
			}
		}
	});
}

/*----
    fn_savetest()
	Function to save the test
	id -> Diagmastery id
----*/
function fn_savetest(id)
{
	var dataparam = "oper=savereview&diagmasteryid="+id;	
	$.ajax({
		type: 'post',
		url: 'library/diagmastery/library-diagmastery-testajax.php',
		data: dataparam,		
		beforeSend: function(){
			showloadingalert("Updating, please wait.");	
		},
		success:function(ajaxdata) {
			var ajaxdata = ajaxdata.split("~");	
			if(ajaxdata[0]=='success'){			
				$('.lb-content').html("Updated Successfully");
				setTimeout('closeloadingalert()',500);
				
				var val = id+","+1;
				
				setTimeout('removesections("#library-ipls");',1000);
				setTimeout('showpages("library-diagmastery","library/diagmastery/library-diagmastery.php");',1000);
			}
		}
	});
}

/*----
    fn_showques()
	Function to show available questions and preview a question
	id -> Available Ques (1)/Show Ques (2)
	type -> 1/2/3,	1 -> Diagnostic, 2 -> Mastery1, 3 -> Mastery2
	ques -> Default ID's(1-6)
	diagmasid -> Diagnostic/Mastery ID
----*/
function fn_showques(id,ques,type,diagmasid)
{
	//alert(ques);
	if(id==1)
	{
		var a=0;
		var text = $('#dques_'+ques).html();
		if($('#'+ques+" "+'div').html() == '+ Add a Question')
		{
			a = 1;
		}
	}
	
	if(ques==0 || a==1)
	{
		var quesid=[];
		$('tr[id^=question_]').each(function(){			
			quesid.push($(this).attr('id').replace('question_',''));
		});
		var val = ques+"~"+type+"~"+quesid+"~"+diagmasid;		
		setTimeout('removesections("#library-diagmastery-diagques");',1000);
		setTimeout('showpageswithpostmethod("library-diagmastery-availableques","library/diagmastery/library-diagmastery-availableques.php","id='+val+'");',1000);
	}
		
	else
	{
		var quesid=[];
		var quesids;
		
		if(id==2)
		{
			$('div[id^=ques_]').each(function(){			
				quesid.push($(this).attr('id').replace('ques_',''));
			});
			var quesids = ques+"~"+quesid+"~"+type;
			setTimeout('removesections("#library-diagmastery-availableques");',1000);
		}
		if(id==1)
		{
			$('div[id^=dques_]').each(function(){			
				quesid.push($(this).parent().attr('id'));
			});
			var quesids = ques+"~"+quesid+"~0";
			setTimeout('removesections("#library-diagmastery-diagques");',1000);
		}
			
		setTimeout('showpageswithpostmethod("library-diagmastery-showques","library/diagmastery/library-diagmastery-showques.php","id='+quesids+'");',1000);
	}
}

/*----
    loads()
	Function to change row, add/remove class for up/down/remove 
----*/
function loads()
{
	var quesid=0;
	$('tr[id^=question_]').each(function(){			
		if($(this).attr('id').replace('question_','')==0)
		{
			quesid=1;
		}
	});
	if(quesid!=1){
		$('#shownext').show();
	}
	else
	{
		$('#shownext').hide();
	}
	
	$('div#up_1').each(function(index, element){
		if(index==0)
		{
			$(this).addClass('dim');
		}
		else
		{
			var row2 = $(this).parents("tr:first").children('td:first').next().children('div').html();
			if(row2!='+ Add a Question')
				$(this).removeClass('dim');
			if(row2=='+ Add a Question')
				$(this).addClass('dim');
		}
	});

	var total = $('div#down_1').length;	 
	$('div#down_1').each(function(index, element){
		if(index==total-1)
		{
			$(this).addClass('dim');
		}
		else
		{
			var row2 = $(this).parents("tr:first").children('td:first').next().children('div').html();
			if(row2!='+ Add a Question')
				$(this).removeClass('dim');
			if(row2=='+ Add a Question')
				$(this).addClass('dim');
		}
	});	
	var remaining = 0;
	$('div#remove_1').each(function(index, element){
		var row2 = $(this).parents("tr:first").children('td:first').next().children('div').html();
		if(row2!='+ Add a Question')
			$(this).removeClass('dim');
		if(row2=='+ Add a Question')
		{
			$(this).addClass('dim');
			remaining++;
		}
	}); 
	$('#remainques').val(remaining);
}
function fn_addques(type)
{
	var count=0;
	var ques=[];
	var list=[];
	$("tr").each(function() {
		if($(this).hasClass('selected')) {	
			list.push($(this).attr('name'));				
			ques.push($('#ques_'+$(this).attr('name')).next().html());
			count++;									
		}	
	});	
	
	$('#create').hide();
	$('#questions').show();
	
	var value=[];
	$('div[id^=dques_]').each(function(){	
		var text = $(this).html();
		if(text == '+ Add a Question')
		{
			value.push($(this).attr('id').replace('dques_',''));
		}
	});
	for(i=0;i<7;i++)
	{
		$('#dques_'+value[i]).parent().parent().attr('id',('question_'+list[i]));
		$('#dques_'+value[i]).parent().attr('id',(list[i]));
		$('#questionid_'+value[i]).val(1);
		$('#dques_'+value[i]).html('');
		$('#dques_'+value[i]).html(ques[i]);
	}
	
	loads();
	setTimeout('removesections("#library-diagmastery-diagques");',500);
}
/*----
    fn_showlesson()
	Function to load lesson dropdown
	id -> Lesson ID
----*/
function fn_showlesson(id)
{
	var dataparam = "oper=showlesson"+"&unitid="+id;
	//alert(dataparam);
	$.ajax({
		type: 'post',
		url: 'library/diagmastery/library-diagmastery-testajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#lesson').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {
			$('#lesson').html(data);
			if($("#iplid").attr('class')=='field row error')
				$('#lessonid').valid();
		}
	});
}
/*----
    fn_deletediag()
	Function to delet the Dia/Mastery test
	id -> Lesson ID
----*/
function fn_deletediag(diagmasid)
{	
	var dataparam = "oper=deletetest&diagmasid="+diagmasid;
	$.Zebra_Dialog('Are you sure you want to delete?',
	{
		'type': 'confirmation',
		'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {
			
				$.ajax({
					type: 'post',
					url: 'library/diagmastery/library-diagmastery-testajax.php',
					data: dataparam,	
					beforeSend: function(){
						showloadingalert("Deleting, please wait.");	
					},		
					success:function(data) {		
						if(data=="success")
						{
							$('#dialog-message .alert-message').html("Diagnostic Mastery Deleted Successfully");
							closeloadingalert();
							setTimeout('removesections("#library-ipls");',1000);
							setTimeout('showpages("library-diagmastery","library/diagmastery/library-diagmastery.php");',1000);
						}
						else if(data=="exists")
						{
							closeloadingalert();
							$.Zebra_Dialog("You can't delete the this test. It is in use", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
						}					
					}
				});	
			}
		}]
	});

}


/*----
    fn_rowclick()
	Function to select the the clicked item in questions
----*/
function fn_rowclick(id)
{	
	var remain = $('#remainques').val();
	if($('#tr_'+id).hasClass('selected')) {
		$('#tr_'+id).removeClass("selected").removeClass("unselected");
		$('#tr_'+id).addClass("unselected");	
		$('#tr_'+id+' td').removeAttr("style");
	} else {
		$('#tr_'+id).removeClass("selected").removeClass("unselected");
		$('#tr_'+id).addClass("selected");
		$('#tr_'+id+' td').css("background-color","#F3FFD1");				
		$('#submit').show();								
	}	
	$('#submit').hide();	
	var cnt = 0;
	$("tr").each(function() {
		if($(this).hasClass('selected')) {
			cnt++;
		}
	});		
		if(cnt==remain)		
			$('#submit').show();
		else
			$('#submit').hide();	
}
