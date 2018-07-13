// JavaScript Document

/***
	fn_later()
	Function to use the test can doing later
**/

document.domain = "pitsco.com";
function fn_later()
{
	setTimeout('removesections("#home");',500);
	setTimeout('showpages("assignment","assignment/assignment.php");',500);
}
 
/***
	fn_questions()
	Function to show the question
**/
function fn_questions(testid,mapid,schid,schtype){
	var val=testid+","+mapid+","+schid+","+schtype;
	setTimeout('removesections("#assignment-assignmentengine-test");',500);
	setTimeout('showpages("assignment-assignmentengine-questions","assignment/assignmentengine/assignment-assignmentengine-questions.php?id='+val+'");',500);
	
} 

/***
	fn_anscheck()
	Function to check the answers
**/
function fn_anscheck(testid,classid,fid,oid,temptest,schid,schtype,maxstudatmpt)
{

    document.domain = "pitsco.com";
		var iframe = document.getElementById('ifrm_'+oid);
		var innerDoc = iframe.contentDocument || iframe.contentWindow.document;

		var input = innerDoc.getElementById('answertypeid_'+classid);
		var anstype=input.value;

		var input = innerDoc.getElementById('hiddquestionid_'+classid);
		var questionid=input.value;

    $("#currectquesid").val(questionid);

		var input = innerDoc.getElementById('boxcount');
		var boxcount=input.value;
    var answer ='';	
    var qorder=$('#qorder').val();
    var cqorder=$('#current_qorder').val();		
    
    var clsid= $("#hidclassid").val();
    
    if(anstype==2 || anstype==7 || anstype==9)
    {
			var answer = innerDoc.getElementById('txtsingleanswer');
			answer=encodeURIComponent(answer.value);
                        
    }
    else if(anstype==3)
    {		
            for(i=0;i<boxcount;i++){			
				var input = innerDoc.getElementById('ans'+i);
                    if(i==(boxcount-1))
					answer+=encodeURIComponent(input.value);
                    else
					answer+=encodeURIComponent(input.value)+'~';
            }											
    }
    else if(anstype==4)
    {		
            for(i=1;i<=boxcount;i++){                        
					var input = innerDoc.getElementById('txt_'+i);
					value1 = input.value;
                            value1 = value1.replace(",", "&#130 ");
                            if(i==boxcount)
							answer+=encodeURIComponent(value1);
                            else
							answer+=encodeURIComponent(value1)+'~';
            }    			
    }
    else if(anstype==5 || anstype==1 || anstype==8)
    {
			var input = innerDoc.getElementById('answer');
			var answer = input.value;			
    }
    else if(anstype==6)
    {
			var input = innerDoc.getElementById('mean1');
			answer = encodeURIComponent(input.value)+'~';
			var input = innerDoc.getElementById('mean2');
			answer+= encodeURIComponent(input.value)+'~';
			var input = innerDoc.getElementById('ext1');
			answer+= encodeURIComponent(input.value)+'~';
			var input = innerDoc.getElementById('ext2');
			answer+= encodeURIComponent(input.value);			
    }
    else if(anstype==10)
    {
            for(i=1;i<=boxcount;i++){			
				var input = innerDoc.getElementById('ans'+i);				
                    if(i==boxcount)
					answer+=input.value;
                    else
					answer+=input.value+'~';
            }				
    }
    else if(anstype==11)
    {
			var boxcount = innerDoc.getElementById('boxcount');
			boxcount = boxcount.value;
            for(i=1;i<=boxcount;i++){			
				var input = innerDoc.getElementById('pullans'+i);				
                    if(i==boxcount)
					answer+=input.value;
                    else
					answer+=input.value+'~';
            }				
    }
    else if(anstype==12)
    {
			var input = innerDoc.getElementById('ballans');
			answer = input.value;
    }
    else if(anstype==13)
    {
			var pointcount = innerDoc.getElementById('hidepointcount');
			pointcount = pointcount.value;
            for(i=1;i<=pointcount;i++){			
				var input = innerDoc.getElementById('hideimagedragpos'+i);				
                    if(i==pointcount)
					answer+=input.value;
                    else
					answer+=input.value+'~';
            }				
    }
    else if(anstype==14)
    {
			answer = $('#ifrm_'+oid).contents().find('iframe').contents().find('#hidlinevalue').val();
    }
    else if(anstype==15)
    {
			var answer = innerDoc.getElementById('txtopenresponse');
			answer=encodeURIComponent(answer.value);	
    }

     
            /************Custom Materices Code Start Here Developed by Mohan M 30-7-2015************/  
                else if(anstype==16)
		{
                    var mrow = innerDoc.getElementById('rowval');
                    mrow=encodeURIComponent(mrow.value);	
                    
                    var mcol = innerDoc.getElementById('columnval');
                    mcol=encodeURIComponent(mcol.value);
                    
                    var k=0;

                    for(var i=1;i<=mrow;i++)
                    {
                        for(var j=0;j<mcol;j++)
                        {
                            var input = innerDoc.getElementById('txt_'+i+"_"+j);
				
                            if(i==mrow && j==(mcol-1))
                                    answer+=input.value;
                            else
                                    answer+=input.value+",";
                        }
                    }
                   
		}
		 
            /************Custom Materices Code End Here Developed by Mohan M 30-7-2015************/  
    
    var dataparam="oper=answercheck&testid="+testid+"&anstype="+anstype+"&quesid="+questionid+"&answer="+answer+"&timecount="+$('#times').val()+"&schid="+schid+"&schtype="+schtype+"&maxstudatmpt="+maxstudatmpt+"&classid="+clsid;
    $.ajax({
    type: "POST",
    url: "assignment/assignmentengine/assignment-assignmentengine-testdb.php",
    data: dataparam,
    success: function(data){
            if(fid == 0){
                    $('#qbank').hide();
                    if(temptest !=1){
                    $('#finishfinal').show();
                    }
                    $('#bottommenu').hide();
            }
            }
    });	
}
/***
	fn_showquestion()
	Function to show the answered questions.
**/
function fn_showquestion()
{
	clearTimeout(timer1);
	$('#qbank').show();
	$('#finishfinal').hide();
	$('.diviplbottom').show();
}
/***
	fn_laststep()
	Function to show the last step of the test.fn_laststep(1,0)
**/
function fn_laststep(testid,temp,schid,schtype,classid){
	clearTimeout(timer1);
	$('body').css('overflow','auto');
	$('#divcustomlightbox').remove();
	$('#divlbcontent').remove();
	$("html, body").animate({ scrollTop: $(document).height() }, "slow");
        //var classid= $("#hidclassid").val();
	var val=testid+","+temp+","+schid+","+schtype+","+classid;
	setTimeout('removesections("#assignment");',500);
	setTimeout('showpages("assignment-assignmentengine-finialstep","assignment/assignmentengine/assignment-assignmentengine-finialstep.php?id='+val+'");',500);
	
}
/***
	fn_closetest()
	Function to close the test.
**/
function fn_closetest(){
	fn_closescreen();	
}
/***
	timeend()
	Function to time out the test.
**/
function timeend(testid,timex){
	
	$('body').css('overflow','auto');
	$('#divcustomlightbox').remove();
	$('#divlbcontent').remove();
	$("html, body").animate({ scrollTop: $(document).height() }, "slow");
	var val=testid+","+timex;
	alert ("Your time is Expired");
		setTimeout('removesections("#assignment");',500);
		setTimeout('showpages("assignment-assignmentengine-finialstep","assignment/assignmentengine/assignment-assignmentengine-finialstep.php?id='+val+'");',500);
	
}

function fn_loadquestion(testid,schid,schtype,maxcount){
	var dataparam="oper=loadquestion&testid="+testid+"&schid="+schid+"&schtype="+schtype+"&maxcount="+maxcount;     
	$.ajax({
		type: "POST",
		url: "assignment/assignmentengine/assignment-assignmentengine-questionsdb.php",
		data: dataparam,
		beforeSend: function(){			
		},
		success: function(ajaxdata){
			$("#divlbcontent").html(ajaxdata);
		}
	});	
}
/***
	fn_takequestion()
	Function is used for take the question number in finial step.
**/
function fn_takequestion(testid,temptest,schid,schtype){
	var qusorder= $("#hidquestionorder").val();
        
        var maxstudatmptcount= $("#maxstudatmptcount").val();//mohan m	
	var classid= $("#hidclassid").val();
	var iframe = document.getElementById('ifrm_'+qusorder);
	var innerDoc = iframe.contentDocument || iframe.contentWindow.document;
	
	var input = innerDoc.getElementById('hidqorderquesid_'+qusorder);
	var qusid=input.value;
	$('.diviplbottom').hide();	
	fn_anscheck(testid,qusid,0,qusorder,temptest,schid,schtype,maxstudatmptcount); //mohan m
        if(temptest !=1){
            timer1 = setTimeout('fn_laststep('+testid+',0,schid,schtype,'+classid+')',30000);
}
}

/***
	fn_closescreen()
	Function is used to close the iframe window.
**/
function fn_closescreen(testid,closetemp){
	
	if(closetemp == 1){
		var dataparam="oper=cltestwopause&testid="+testid;
		$.ajax({
			type: "POST",
			url: "assignment/assignmentengine/assignment-assignmentengine-testdb.php",
			data: dataparam,
		});	
		
	}
	
	$('body').css('overflow','auto');
	$('#divcustomlightbox').remove();
	$('#divlbcontent').remove();
	$("html, body").animate({ scrollTop: $(document).height() }, "slow");
	setTimeout('removesections("#home");',500);
	setTimeout('showpages("assignment","assignment/assignment.php");',500);
	
	
}
/***
	fn_pausetest()
	Function is used to pause the test.
**/
function fn_pausetest(testid){
	
	var quesids= $("#hidquesids").val();
	var currectquesids= $("#currectquesid").val();
	var classid= $("#hidclassid").val();	
	
	$.Zebra_Dialog('Are you sure you want to pause the test.?',
		{
			'type': 'confirmation',
			'buttons': [
			{caption: 'No', callback: function() { $('#timecount').countdown('resume'); }},
			{caption: 'Yes', callback: function() {	
				
				
			var dataparam="oper=pausetest&testid="+testid+"&quesids="+quesids+"&timepause="+$('#times').val()+"&currectquesids="+currectquesids+"&classid="+classid;
				$.ajax({
				type: "POST",
				url: "assignment/assignmentengine/assignment-assignmentengine-testdb.php",
				data: dataparam,
				beforeSend: function(){
					showloadingalert("please wait.");	
				},
				success: function(data){
					$('body').css('overflow','auto');
					$('#divcustomlightbox').remove();
					$('#divlbcontent').remove();
					$("html, body").animate({ scrollTop: $(document).height() }, "slow");
					setTimeout('removesections("#home");',500);
					setTimeout('showpages("assignment","assignment/assignment.php");',500);
					}
				});	
			}}
		]
	});
}


