// JavaScript Document
/*----
    fn_rowclick()
	Function to select the the clicked item in questions
----*/
function fn_rowclick(id){
	if($('#tr_'+id).hasClass('selected')) {
		$('#tr_'+id).removeClass("selected").removeClass("unselected");
		$('#tr_'+id).addClass("unselected");	
		$('#tr_'+id+' td').removeAttr("style");
                $('#span_'+id).removeClass("icon-synergy-add-small").removeClass("icon-minus"); 
                $('#span_'+id).addClass("icon-synergy-add-small");
        
	} else {
		$('#tr_'+id).removeClass("selected").removeClass("unselected");
		$('#tr_'+id).addClass("selected");
		$('#tr_'+id+' td').css("background-color","#F3FFD1");				
                $('#span_'+id).removeClass("icon-synergy-add-small").removeClass("icon-minus"); 
                $('#span_'+id).addClass("icon-minus");
		$('#submit').show();								
                       
	}
	
	$('#submit').hide();			
	$("tr").each(function() {
		if($(this).hasClass('selected')) {
			$('#submit').show();					
		}
	});			
        var rowscount= $('.selected').length;
        if(rowscount == 0){
        rowscount='';
}
        $('#qcount').html(rowscount);
        
}
/*----
    fn_submitlist()
	Function to submit the questions 
----*/
function fn_submitlist(testid){
	var count=0;
	var list=[];
	$("tr").each(function() {
		if($(this).hasClass('selected')) {					
			list.push($(this).attr('name'));
			count++;									
		}	
		
	});		
	var dataparam = "oper=addquestion&testid="+testid+"&list="+list;	
	$.ajax({
		type: "POST",
		url: 'test/testassign/test-testassign-addquestiondb.php',
		data: dataparam,
		beforeSend:function()
		{
			showloadingalert("Loading, please wait.");
		},
		success: function(data)
		{
			$('.lb-content').html("Question Added Successfully");
			setTimeout('closeloadingalert()',2000);
			var val = data;
				
			setTimeout('removesections("#test-testassign-steps");',500);
			setTimeout('showpages("test-testassign-testquestion","test/testassign/test-testassign-testquestion.php?id='+val+'");',500);
		}
	});	
	
}
/*----
    fn_next1()
	Function to save the questions in particular test. 
----*/
function fn_next1(testid,totalq)
{
	var id=[];
	$('tr[name^=question_]').each(function(index, element) {
          
		  id.push($(this).attr('name').replace(/question_/,''));
        });	
	if( id == ""){
		showloadingalert("Please select Question");	
		setTimeout('closeloadingalert()',2000);		
		return false;
	}
	var dataparam = "oper=savequestion&testid="+testid+"&list="+id+"&totalq="+totalq;	
		$.ajax({
		type: 'post',
		url: 'test/testassign/test-testassign-addquestiondb.php',
		data: dataparam,		
		beforeSend: function(){
			showloadingalert("Saving, please wait.");	
		},
		success:function() {
				$('.lb-content').html("Question(s) Saved");
				setTimeout('closeloadingalert()',2000);
				var val = testid+","+3;

				setTimeout('removesections("#test-testassign-steps");',500);
				$('#btntest-testassign-testreview').removeClass("dim");
				setTimeout('showpages("test-testassign-testreview","test/testassign/test-testassign-testreview.php?id='+val+'");',500);
			}
	});
	
}
/*----
    fn_savetest()
	Function to save the final step in test details. 
----*/
function fn_savetest(testid)
{	
	var dataparam = "oper=savereview"+"&testid="+testid;
	$.ajax({
		type: 'post',
		url: 'test/testassign/test-testassign-addquestiondb.php',
		data: dataparam,
		beforeSend: function(){
			showloadingalert("Loading, please wait.");	
		},
		success:function(data) {
				$('.lb-content').html("Assessment Saved");
				setTimeout('closeloadingalert()',2000);				
				var val = testid+","+1;				
				setTimeout('removesections("#home");',500);
				setTimeout('showpages("test","test/test.php");',500);
				
		}
	});
}
/*----
    fn_close()
	Function to close the question review 
----*/
function fn_close(option,testid)
{
	var val = testid;
	
	if(option == "create"){
		setTimeout('removesections("#test-testassign-testquestion");',500);
		setTimeout('showpages("test-testassign-addquestion","test/testassign/test-testassign-addquestion.php?id='+val+'");',1000);
	}
	
	if(option == 0){
		setTimeout('removesections("#test-testassign-actions");',500);
		setTimeout('showpages("test-testassign-testreviewmain","test/testassign/test-testassign-testreviewmain.php?id='+val+'");',500);
	}
	else{
		setTimeout('removesections("#test-testassign-steps");',500);
		setTimeout('showpages("test-testassign-testquestion","test/testassign/test-testassign-testquestion.php?id='+val+'");',500);
	}
}
/*----
    fn_showquestion()
	Function to view the question details  
----*/
function fn_showquestion(testid,questionid){
	var val=0+"_"+questionid+"_"+testid;
	showloadingalert("Loading, please wait.");	
	closeloadingalert();
	setTimeout('removesections("#test-testassign-testreview");',500);
	setTimeout('showpages("test-testassign-review","test/testassign/test-testassign-review.php?id='+val+'");',750);
}
/*----
    fn_showquestionrandom()
	Function to view the question details  
----*/
function fn_showquestionrandom(testid,questionid){
	var val=0+"_"+questionid+"_"+testid;
	showloadingalert("Loading, please wait.");	
	closeloadingalert();
	setTimeout('removesections("#test-testassign-testrandomreviewmain");',500);
	setTimeout('showpages("test-testassign-review","test/testassign/test-testassign-review.php?id='+val+'");',750);
}
function fn_showquestionrandominner(testid,questionid){
	var val=0+"_"+questionid+"_"+testid;
	showloadingalert("Loading, please wait.");	
	closeloadingalert();
	setTimeout('removesections("#test-testassign-testrandomreview");',500);
	setTimeout('showpages("test-testassign-review","test/testassign/test-testassign-review.php?id='+val+'");',750);
}

function fn_deleteques(quesid)
{	
	var dataparam = "oper=deleteques&quesid="+quesid;	
	$.Zebra_Dialog('Are you sure you want to delete this Question?',
		{
			'type': 'confirmation',
			'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {
					
				$.ajax({
					type: 'post',
					url: 'test/testassign/test-testassign-addquestiondb.php',
					data: dataparam,	
					beforeSend: function(){
						showloadingalert("Deleting, please wait.");	
					},		
					success:function(data) {		
						if(data=="success")
						{
							$('.lb-content').html("Question deleted successfully");
							setTimeout('closeloadingalert()',2000);
							var sid = $('#form_tags').val();
							if(sid=='')
							sid = "4_testengine";
							setTimeout('removesections("#test");',500);
							setTimeout('showpages("test-testassign-testenginequestion","test/testassign/test-testassign-testenginequestion.php?sid='+sid+'");',500);
						}
						else
						{
							$('.lb-content').html("Question is assigned to a Test");
							setTimeout('closeloadingalert()',2000);
						}
					}
				});	
			}}
		]
	});
}
/*----
    fn_testtype()
	Function to save the test type  
----*/
function fn_testtype(typeid,testid){
   var dataparam = "oper=testtype"+"&typeid="+typeid+"&testid="+testid;
  	$.ajax({
		type: 'post',
		url: 'test/testassign/test-testassign-addquestiondb.php',
		data: dataparam
	});
    
}
/*----
    fn_randomsubmit()
	Function to save section in single. 
----*/
function fn_randomsubmit(testid,sectionid,submitflag)
{	
        var pct ='';
        var fpct1 ='';
        var toatalpct1 =0;
        var finalcount =0;
        
        $("tr[id^='qtags']").each(function() {
                var i = $(this).attr('id').substring(6);
                if( i != sectionid){
                    fpct1+=$('#pct' + i).val()+'~';
                    toatalpct1=parseInt(toatalpct1)+parseInt($('#pct' + i).val());
            }
        });
        
        var qncounts = $('#tagqncount').html().trim();
        var qusass = $('#qusass').val();
        var pect = $('#pect').val();
        var tagids = $('#form_tags').val();
        if(qusass == ''){
            $.Zebra_Dialog("Please enter a value for questions assigned.", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
            return false;
        }
        if(pect == ''){
            $.Zebra_Dialog("Please enter the Percentage Weight.", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
            return false;
        }
        
        finalcount = parseInt(toatalpct1)+parseInt(pect);
        if(100 < parseInt(finalcount)){
            $.Zebra_Dialog("Percentage weight should not be greater than assessment score.", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
            $('#pect').val('');
            return false;
        }
        
	var dataparam = "oper=randomqndetails"+"&testid="+testid+"&sectionid="+sectionid+"&tagids="+tagids+"&qncounts="+qncounts+"&qusass="+qusass+"&pect="+pect;    
	$.ajax({
		type: 'post',
		url: 'test/testassign/test-testassign-addquestiondb.php',
		data: dataparam,
		beforeSend: function(){
			showloadingalert("Loading, please wait.");	
		},
		success:function(data) {
                   
                        $('.lb-content').html("Tag Question(s) Saved");
                        setTimeout('closeloadingalert()',2000);
                        var val = testid+","+submitflag;
                        setTimeout('removesections("#test-testassign-steps");',500);
                        setTimeout('showpages("test-testassign-testquestion","test/testassign/test-testassign-testquestion.php?id='+val+'");',500);
				
		}
	});
}
/*----
    fn_nextr()
	Function to save more then one section. 
----*/
function fn_nextr(testid,fflag,qtpct){
    
        var fsectionid ='';
        var ftags = '';
	var fquscount ='';
        
	var fqnassig ='';
        var fpct ='';
        var toatalpct =0;
        var checkpct ='';
        
        
        $("tr[id^='qtags']").each(function() {
            var i = $(this).attr('id').substring(6);
            fsectionid+=i+'~';
            ftags+=$('#form_tags' + i).val()+'~';
            fquscount+=$('#quscount' + i).val()+'~';
            if($('#qnassig' + i).val() == ''){
                $.Zebra_Dialog("Please enter a value for questions assigned.", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
                  checkpct = 1;
            }
            fqnassig+=$('#qnassig' + i).val()+'~';
             if($('#pct' + i).val() == ''){
                $.Zebra_Dialog("Please enter the percentage weight.", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
                  checkpct = 1;
            }
            fpct+=$('#pct' + i).val()+'~';
            toatalpct=parseInt(toatalpct)+parseInt($('#pct' + i).val());
    });	
    
   if(100 < parseInt(toatalpct)){
        $.Zebra_Dialog("Percentage weight should not be greater than assessment score.", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
        return false;
    }
    if(checkpct ==1){
        return false;
    }
    
    var dataparam = "oper=randomqsection"+"&testid="+testid+"&fsectionid="+fsectionid+"&ftags="+ftags+"&fquscount="+fquscount+"&fqnassig="+fqnassig+"&fpct="+fpct+"&fflag="+fflag;  
    $.ajax({
		type: 'post',
		url: 'test/testassign/test-testassign-addquestiondb.php',
		data: dataparam,
		beforeSend: function(){
			showloadingalert("Loading, please wait.");	
		},
		success:function(data) {
                    fn_saverandomquestion(testid);
               }
	});
   
    }
    
function fn_nextempty(testid){
    var emptyflagnow = $('#emptyflag').val();
    
    if(emptyflagnow == 1){
        $.Zebra_Dialog('Are you sure you want to without update the section?',
		{
                    'type': 'confirmation',
                    'buttons': [
                    {caption: 'No', callback: function() { }},
                    {caption: 'Yes', callback: function() { 
                            showloadingalert("Loading, please wait.");
                            setTimeout('closeloadingalert()',2000);
                            var val = testid+","+3;
                            setTimeout('removesections("#test-testassign-steps");',500);
                            $('#btntest-testassign-testrandomreview').removeClass("dim");
                            setTimeout('showpages("test-testassign-testrandomreview","test/testassign/test-testassign-testrandomreview.php?id='+val+'");',500);
                        
                        }}
		]
	});
        
    }
}
/*----
    fn_saverandomquestion()
	Function to save the random questions. 
----*/   
function fn_saverandomquestion(testid){
    
    var fsectionid ='';
    var ftags = '';
    var fquscount ='';
    var fqnassig ='';
    var fpct ='';

    $("tr[id^='qtags']").each(function() {
        var i = $(this).attr('id').substring(6);
        fsectionid+=i+'~';
        ftags+=$('#form_tags' + i).val()+'~';
        fquscount+=$('#quscount' + i).val()+'~';	
        fqnassig+=$('#qnassig' + i).val()+'~';	
        fpct+=$('#pct' + i).val()+'~';
});

var dataparam = "oper=saverandomquestion"+"&testid="+testid+"&fsectionid="+fsectionid+"&ftags="+ftags+"&fquscount="+fquscount+"&fqnassig="+fqnassig+"&fpct="+fpct;
$.ajax({
            type: 'post',
            url: 'test/testassign/test-testassign-addquestiondb.php',
            data: dataparam,
            beforeSend: function(){                   	
            },
            success:function(data) {
                $('.lb-content').html("Question(s) Saved");
                setTimeout('closeloadingalert()',2000);
                var val = testid+","+3;

                setTimeout('removesections("#test-testassign-steps");',500);
                $('#btntest-testassign-testrandomreview').removeClass("dim");
                setTimeout('showpages("test-testassign-testrandomreview","test/testassign/test-testassign-testrandomreview.php?id='+val+'");',500);		
            }
    });
    
}
/*----
    fn_chkqnassigsection()
	Function to check the avl questions for particular section. 
----*/ 
function fn_chkqnassigsection(){
    
    var avlqust = $('#tagqncount').html().trim();
    var cassq = $('#qusass').val();
    if(parseInt(avlqust) < parseInt(cassq)){
       
        $('#qusass').val('');
        $.Zebra_Dialog("Questions assigned count should not be greater than available questions.", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
        $('#qusass').focus();
        return false;
         
    }
    
}

function fn_chkqnassig(sectionid,testid){
  
    var assq = $('#qnassig'+sectionid).val();
    if(assq == ''){
        $.Zebra_Dialog("Please enter a value for questions assigned.", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
        return false;
    }
    var dataparam = "oper=chkqnassig"+"&testid="+testid+"&sectionid="+sectionid+"&assq="+assq;    
    $.ajax({
		type: 'post',
		url: 'test/testassign/test-testassign-addquestiondb.php',
		data: dataparam,
		success:function(data) {
                if(trim(data)=='false'){
                            $('#qnassig'+sectionid).val('');
                            $.Zebra_Dialog("Questions assigned count should not be greater than available questions.", { 'type': 'information', 'buttons':  false, 'auto_close': 2000  });
                            return false;
                           
                    }
		}
	});  
    
}
function fn_showsection(sectionid){
        var val=1+"_"+sectionid;
        showloadingalert("Loading, please wait.");	
	closeloadingalert();
	setTimeout('removesections("#test-testassign-testquestion");',500);
	setTimeout('showpages("test-testassign-addquestionrandom","test/testassign/test-testassign-addquestionrandom.php?sectid='+val+'");',750);
}
