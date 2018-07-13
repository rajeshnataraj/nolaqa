// JavaScript Document

/*----
    fn_signmathclear()
	Function to clear the sigmath functions
----*/
function fn_signmathclear(){
}

/*----
    fn_diagnosticstart()
	Function to start diagnostic test
----*/
function fn_diagnosticstart(sid,lessonid,testtype,moduleid){	
	$('#divlbcontent').html('');
	var dataparam = "oper=diagnosticstart"+"&sid="+sid+"&lessonid="+lessonid+"&testtype="+testtype+"&moduleid="+moduleid;
	$.ajax({
		type: 'post',
		url: 'assignment/sigmath/assignment-sigmath-test-ajax.php',
		data: dataparam,
		beforeSend: function(){			
		},
		success:function(data) {			
			data = data.split('~');	
			$('#divlbcontent').html(data[0]);
			$('#fottitle').html('Diagnostic Test');
			$('.dialogTitleFullScr').html(data[1]);
		}
	});
}


/*----
    fn_question()
	Function to show the test questons
----*/
function fn_question(sid,lessonid,testtype,qorder,anscount,maxid,mathtype,modulid)
{
	$('#divlbcontent').html('');		
	if(testtype==1)
	var msg = "Diagnostic Test";
	else if(testtype==2)
	var msg = "Mastery Test 1";
	else if(testtype==3)
	var msg = "Mastery Test 2";
	
        var moduleid = $('#modid').val();
	var dataparam="oper=questionview&sid="+sid+"&lessonid="+lessonid+"&qorder="+qorder+"&anscount="+anscount+"&testtype="+testtype+"&maxid="+maxid+"&mathtype="+mathtype+"&moduleid="+moduleid;
	$.ajax({
		type: 'post',
		url: 'assignment/sigmath/assignment-sigmath-test-ajax.php',
		data: dataparam,
		beforeSend: function(){			
		},
		success:function(data) {			
			$('#divlbcontent').html(data);			
			$('#fottitle').html(msg);	
		}
	});
}


/*----
    fn_anscheck()
	Function to check the answer for the questions
----*/
function fn_anscheck(sid,lessonid,testtype,quesid,anscount,maxid)
{		
                var anstype = $('#answertypeid').val();
                var orientationflag = $('#orientationflag').val();
                var boxcount = $('#boxcount').val();
		var answer ='';	
		var qorder=$('#qorder').val();
		var cqorder=$('#current_qorder').val();		
		if(anstype==2 || anstype==7 || anstype==9)
		{			
                        var answer = $('#txtsingleanswer').val();
                        answer=encodeURIComponent(answer);                       
		}
		else if(anstype==3)
		{		
			for(i=0;i<boxcount;i++){							
                                var ans = $('#ans'+i).val();
				if(i==(boxcount-1))					
                                        answer+=ans;    
				else					
                                        answer+=ans+'~';        
			}				
		}
		else if(anstype==4)
		{		
			for(i=1;i<=boxcount;i++){							
                                var txt = $("#txt_"+i).val();				
				if(i==boxcount)					
                                        answer+=txt;
				else					
                                         answer+=txt+'~';   
			}											
		}
		else if(anstype==5 || anstype==1 || anstype==8)
		{			
                        var answer = $('#answer').val();                       
		}
		else if(anstype==6)
		{
                    
                        var mean1 = $('#mean1').val();
                        answer = mean1+'~';
                        var mean2 = $('#mean2').val();
                        answer+=mean2+'~';
                        var ext1=$('#ext1').val();
                        answer+=ext1+'~';
                        var ext2=$('#ext2').val();
                        answer+=ext2+'~';                        
			
		}			
		var dataparam="oper=answercheck&sid="+sid+"&anstype="+anstype+"&quesid="+quesid+"&anscount="+anscount+"&answer="+answer+"&testtype="+testtype+"&cqorder="+cqorder+"&maxid="+maxid;
		$.ajax({
		type: "POST",
		url: 'assignment/sigmath/assignment-sigmath-test-ajax.php',
		data: dataparam,
		success: function(data){
			var data=data.split("~");
			
				if(testtype==1)  // diagnostic
				{
					if(qorder!=0)
					{
						fn_question(sid,lessonid,testtype,qorder,data[0],maxid);
					}
					else 
					{
						fn_diagfinish(sid,lessonid,data[0],maxid,orientationflag);
					}					
				}
				else if(testtype==2 || testtype==3)  //Maserey 1 or Mastery2
				{
					
						if(data[1]==1) // if the answer is correct
						{
							if((data[0]==3 || qorder==0 || qorder==6) && testtype==2)
							{
								fn_mastery1finish(sid,lessonid,data[0],maxid);
							}
							else if((data[0]==3 || qorder==0 || qorder==6) && testtype==3)
							{
								fn_mastery2finish(sid,lessonid,data[0],maxid);
							}							
							else
							{
								if(qorder == 2 || qorder == 4)
								{
									qorder =parseInt(qorder)+1;								
																		
								}
								
								fn_question(sid,lessonid,testtype,qorder,data[0],maxid);
							}
						}
						else  // the answer is wrong call the remediation here
						{
							fn_remediation(sid,lessonid,testtype,qorder,data[0],data[2],maxid,data[3]);
						}
								
				}				
			}
		});	
}

/*----
    fn_diagfinish()
	Function to show the diagnostic completed screen
----*/
function fn_diagfinish(sid,lessonid,anscount,maxid,orientationflag)
{
	$('#divlbcontent').html('');	
	if(anscount==3 && orientationflag!=2)  //if all the question correct
	{
		var dataparam = "oper=diagpass&maxid="+maxid+"&lessonid="+lessonid+"&sid="+sid;
	}
	else
	{
		var count=3-anscount;
		var dataparam = "oper=diagfail&maxid="+maxid+"&lessonid="+lessonid+"&count="+count+"&sid="+sid;
	}
		$.ajax({
		type: "POST",
		url: 'assignment/sigmath/assignment-sigmath-test-ajax.php',
		data: dataparam,
		beforeSend:function(){	
		},
		success: function(data){
				$('#divlbcontent').html(trim(data));
				$('#fottitle').html('Diagnostic Test Completed');			
			}
		});	
	
}


/*----
    fn_lessonplay()
	Function to show the lessons
----*/
function fn_lessonplay(sid,lessonid,maxid,orientationflag)
{
	$('#divlbcontent').html('');	
	var dataparam="oper=playlesson&lessonid="+lessonid+"&maxid="+maxid+"&sid="+sid+"&orientationflag="+orientationflag;
	$.ajax({
		type: "POST",
		url: 'assignment/sigmath/assignment-sigmath-test-ajax.php',
		data: dataparam,
		beforeSend:function(){				
			},
		success: function(data){
			$('#divlbcontent').html(trim(data));			
			$('#fottitle').html('Lesson Play');			
		}
	});	
}


/*----
    fn_slidecheck()
	Function to check the lesson is completed or not. It was called by the oper= lessonplay in ajaxpage
----*/
function fn_slidecheck(sid,lessonid,maxid,orientationflag)
{
	var dataparam="oper=slidecheck&lessonid="+lessonid+"&maxid="+maxid+"&sid="+sid;
	$.ajax({
		type: "POST",
		url: 'assignment/sigmath/assignment-sigmath-test-ajax.php',
		data: dataparam,
		success: function(data){
			if(trim(data)=='completed' || trim(data)=='passed') ///the lesson is completed then start mastery1
			{				
					fn_startmastery1(sid,lessonid,maxid,1);
				
			}
	}
	});	
}

function fn_checklockstatus(sid,lessonid,maxid)
{
	var dataparam="oper=checklockstatus&maxid="+maxid;
	$.ajax({
		type: "POST",
		url: 'assignment/sigmath/assignment-sigmath-test-ajax.php',
		async:false,
		data: dataparam,
		success: function(data){			
			if(trim(data)==1) ///the lesson is completed then start mastery1
			{				
				setTimeout('fn_startmastery2('+sid+','+lessonid+','+maxid+',1)',500);
			}
	}
	});	
}

function fn_remslidecheck(maxid)
{
	var dataparam="oper=remslidecheck&maxid="+maxid;
	$.ajax({
		type: "POST",
		url: 'assignment/sigmath/assignment-sigmath-test-ajax.php',
		data: dataparam,
		async:false,
		success: function(data){
			if(trim(data)=='completed' || trim(data)=='passed') ///the lesson is completed then start mastery1
			{
				clearInterval(remslide);
				$('#remcontinue').show();
			}
		}
	});	
}

/*----
    fn_orientationcomplete()
	Function to finish orientation
----*/
function fn_orientationcomplete(sid,lessonid,maxid){
		
		clearInterval(interval);  //this is for remove the setinterval function started from the oper= lessonplay in ajaxpage	
		var dataparam="oper=orientationcomplete&lessonid="+lessonid+"&maxid="+maxid+"&sid="+sid;	
		$.ajax({
		type: "POST",
		url: 'assignment/sigmath/assignment-sigmath-test-ajax.php',
		data: dataparam,
		beforeSend:function(){
		},
		success: function(data){
			eval(trim(data));
		}
		});	
}


/*----
    fn_startmastery1()
	Function to start mastery test1
----*/
function fn_startmastery1(sid,lessonid,maxid,check)
{
	$('#divlbcontent').html('');
		if(check==1)
			clearInterval(interval);  //this is for remove the setinterval function started from the oper= lessonplay in ajaxpage	
		var dataparam="oper=mastery1start&lessonid="+lessonid+"&maxid="+maxid+"&sid="+sid;	
		$.ajax({
		type: "POST",
		url: 'assignment/sigmath/assignment-sigmath-test-ajax.php',
		data: dataparam,
		beforeSend:function(){
		},
		success: function(data){
			data = data.split('~');
			$('#divlbcontent').html(trim(data[0]));			
			$('#fottitle').html('Mastery Test 1');	
			$('.dialogTitleFullScr').html(data[1]);
			}
		});	
}


/*----
    fn_startmastery2()
	Function to start mastery test2
----*/
function fn_startmastery2(sid,lessonid,maxid,flag)
{
	$('#divlbcontent').html('');
	if(flag==1)
	clearInterval(lockstatus);
	var dataparam="oper=mastery2start&lessonid="+lessonid+"&maxid="+maxid+"&sid="+sid;
	$.ajax({
	type: "POST",
	url: 'assignment/sigmath/assignment-sigmath-test-ajax.php',
	data: dataparam,
	beforeSend:function(){	
	},
	success: function(data){
		data = data.split('~');
		$('#divlbcontent').html(trim(data[0]));			
		$('#fottitle').html('Mastery Test 2');	
		$('.dialogTitleFullScr').html(data[1]);
		}
	});	
}


/*----
    fn_remediation()
	Function to play the remediation
----*/
function fn_remediation(sid,lessonid,testtype,qorder,anscount,quesid,maxid,ansmaxid)
{
	$('#divlbcontent').html('');
		
		var dataparam="oper=remediation&lessonid="+lessonid+"&maxid="+maxid+"&testtype="+testtype+"&qorder="+qorder+"&anscount="+anscount+"&quesid="+quesid+"&sid="+sid+"&ansmaxid="+ansmaxid;		
		$.ajax({
		type: "POST",
		url: 'assignment/sigmath/assignment-sigmath-test-ajax.php',
		data: dataparam,
		beforeSend:function(){
		},
		success: function(data){
			$('#divlbcontent').html(trim(data));			
			$('#fottitle').html('Remediation Slide');			
			}
		});	
}


/*----
    fn_mastery1finish()
	Function to show the master1 finish page
----*/
function fn_mastery1finish(sid,lessonid,anscount,maxid)
{
	$('#divlbcontent').html('');
	
	if(anscount==3) //if the user get mastered
	{
		var dataparam = "oper=mastery1pass&maxid="+maxid+"&lessonid="+lessonid+"&sid="+sid;
	}
	else
	{		
		var dataparam = "oper=mastery1fail&maxid="+maxid+"&lessonid="+lessonid+"&sid="+sid;
	}
		$.ajax({
		type: "POST",
		url: 'assignment/sigmath/assignment-sigmath-test-ajax.php',
		data: dataparam,
		beforeSend:function(){						
		},
		success: function(data){
				$('#divlbcontent').html(trim(data));				
				$('#fottitle').html('Mastery Test 1 Completed');							
			}
		});	
	
}


/*----
    fn_mastery2finish()
	Function to show the master2 finish page
----*/
function fn_mastery2finish(sid,lessonid,anscount,maxid)
{
	$('#divlbcontent').html('');
	
	if(anscount==3) //if the user mastered
	{
		var dataparam = "oper=mastery2pass&maxid="+maxid+"&lessonid="+lessonid+"&sid="+sid;
	}
	else
	{		
		var dataparam = "oper=mastery2fail&maxid="+maxid+"&lessonid="+lessonid+"&sid="+sid;
	}
		$.ajax({
		type: "POST",
		url: 'assignment/sigmath/assignment-sigmath-test-ajax.php',
		data: dataparam,
		beforeSend:function(){						
		},
		success: function(data){
				$('#divlbcontent').html(trim(data));				
				$('#fottitle').html('Mastery Test 2 Completed');							
			}
		});	
	
}


/*----
    fn_review()
	Function to review the lesson
----*/
function fn_review(sid,lessonid,maxid)
{
	$('#divlbcontent').html('');	
	var dataparam = "oper=review&lessonid="+lessonid+"&maxid="+maxid+"&sid="+sid;
	$.ajax({
		type: "POST",
		url: 'assignment/sigmath/assignment-sigmath-test-ajax.php',
		data: dataparam,
		beforeSend:function(){			
			},
		success: function(data){
			$('#divlbcontent').html(trim(data));			
			$('#fottitle').html('Review the Lesson');	
	}
	});	
}


/*----
    fn_completed()
	Function to show the completed state of schedule
----*/
function fn_completed(sid)
{
	$('#divlbcontent').html('');	
	var dataparam = "oper=completed&sid="+sid;	 
	$.ajax({
		type: "POST",
		url: 'assignment/sigmath/assignment-sigmath-test-ajax.php',
		data: dataparam,
		beforeSend:function(){						
		},
		success: function(data){			
			$('#divlbcontent').html(trim(data));				
			$('#fottitle').html('Completed');	
		}
	});	
}


/*----
    fn_loadschedule()
	Function to load the schedule page
----*/
function fn_loadschedule()
{	
	closefullscreenlesson(); // its used for close the fullscreen of the test page	
	showpages("assignment","assignment/assignment.php");
}


/*----
    fn_closescreen()
	Function to call unload function the when the user terminate the test using close button
----*/
function fn_closescreen()
{
	var testtype = $('#testtype').val();
	var mathtype = $('#mathtype').val();
	var msg='';
	if(testtype == 1) // diagnostic
	{
		var msg = "If you exit this page you will fail in this Diagnostic Test";
	}
	if(testtype == 2) ///mastery1
	{
		var msg = "If you exit this page you will fail in this Mastery Test 1";
	}
	if(testtype == 3) //mastery2
	{
		var msg = "If you exit this page you will fail in this Mastery Test 2";
	}
	if(testtype==0)
		clearInterval(interval);
	if(testtype==5)
		clearInterval(lockstatus);
	if(msg!=''){		
		if(confirm(msg)){
			fn_unload(testtype);
			fn_calldiv();
			closefullscreenlesson();
			if(mathtype!=2 && mathtype!=5)
				fn_loadschedule();
		}
	}
	else{
		fn_calldiv();
		closefullscreenlesson();	
		if(mathtype!=2 && mathtype!=5)
			fn_loadschedule();
	}	
}

function fn_calldiv()
{
	var dataparam="oper=reloaddiv";
	$.ajax({
		type: "POST",
		url: 'assignment/sigmath/assignment-sigmath-test-ajax.php',
		data: dataparam,
		success: function(data){
			$('#loaddetails').html(data);
		}
	});	
}
/*----
    fn_mathmodulenextlesson()
	Function to fail the current test while terminate the test
----*/
function fn_mathmodulenextlesson(sid,lessonid,mathtype)
{			
	var dataparam="oper=nextlesson&sid="+sid+"&lessonids="+$('#lessonids').val()+"&mathtype="+mathtype;
	$.ajax({
		type: "POST",
		url: 'assignment/sigmath/assignment-sigmath-test-ajax.php',
		data: dataparam,
		success: function(data){
			var data = data.split('~');
			if(data[0]!='completed'){
				$('.dialogTitleFullScr').html(data[1]);
				eval(data[0]);
			}
			else{
				fn_closescreen();
			}
		}
	});	
}

/*----
    fn_unload()
	Function to fail the current test while terminate the test
----*/
function fn_unload(testtype)
{	
	var maxid=$('#maxid').val();	
	var dataparam="oper=unload&testtype="+testtype+"&maxid="+maxid;
	$.ajax({
		type: "POST",
		url: 'assignment/sigmath/assignment-sigmath-test-ajax.php',
		data: dataparam,
		success: function(data){					
	}
	});	
}

/*----
    goodbye()
	Function to call the unload while the user close or reload the window
----*/
function goodbye(e) {
	if(!e) e = window.event;
	var testtype = $('#testtype').val();	
	if(testtype==0)
		clearInterval(interval);
	if(testtype == 1 || testtype == 2 || testtype == 3)
	{
		if(testtype == 1)
		{
			var msg = "If you are exit this page you will fail in this Diagnostic Test";
		}
		if(testtype == 2)
		{
			var msg = "If you are exit this page you will fail in this Mastery Test 1";
		}
		if(testtype == 3)
		{
			var msg = "If you are exit this page you will fail in this Mastery Test 2";
		}
		if(testtype == 4)
		{
			var msg = "If you are exit this page you will fail in this Test";
		}
		fn_unload(testtype);
		return msg;
	}
}
window.onbeforeunload= goodbye;