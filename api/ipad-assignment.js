// JavaScript Document
var lightbox_overlay = '<section class="black-overlay"><div class="lb-content"></div></section>'; // Container for the lightbox
var loadcontent='<span style="text-align: center; vertical-align: middle; display: inline-block; line-height: 300px; width: 100%; height: 100%;color:#48708A;font-size:29px">Loading, please wait.</span>';

function detectmob() { 

 if(navigator.userAgent.match(/Android/i)) {
    return "android"; 
 }

 if(navigator.userAgent.match(/webOS/i) || navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPod/i)) {
    return "ios";
  }
}

function escapestr(str){
	return escape(str.replace(/<script[^>]*>([\s\S]*?)<\/script[^>]*>/,""));
}

function ampreplace(str)
{
	return str.replace(/&/g,"%26");
}

function trim(stringToTrim) {
	return stringToTrim.replace(/^\s+|\s+$/g,"");
}

function showloadingalert(alertmessage){
	var cssObjOuter = {
	  'width' : $(window).width(),
	  'height' : $(window).height()
	};
	var cssObjInner = {
	  'margin-left' : $(window).width() * .4,
	  'margin-top' : $(window).height() * .4
	};
			
	$('body').append(lightbox_overlay);
	$(".black-overlay").css(cssObjOuter);
	
	$(".lb-content").css(cssObjInner);
	$(".lb-content").html(alertmessage);
}

/* 
Function to close the lightbox
*/
function closeloadingalert(){
	setTimeout('$(".lb-content").remove();$(".black-overlay").remove();',500);	
}

/*----
    fn_signmathclear()
	Function to clear the sigmath functions
----*/
function fn_signmathclear(){	
	//clearInterval(interval);
	removesections('#home');
	setTimeout('showpages("assignment","assignment/assignment.php");',500);
}

/*----
    fn_diagnosticstart()
	Function to start diagnostic test
----*/
function fn_diagnosticstart(sid,lessonid,testtype){	
	var iframe = document.createElement("IFRAME");
	iframe.setAttribute("src", "js-framestart:"+lessonid);
	document.documentElement.appendChild(iframe);
	iframe.parentNode.removeChild(iframe);
        
        var browcheck = detectmob();
        if(browcheck == "android" ) {
            //alert("lesson id"+lessonid);
            AndroidFunction.getValue("js-framestart:"+lessonid);
        }
        
	iframe = null;
	var uid = $('#uid').val();	
	var dataparam = "oper=diagnosticstart"+"&sid="+sid+"&lessonid="+lessonid+"&testtype="+testtype+"&uid="+uid;	
	$.ajax({
		type: 'post',
		url: 'ipad-assignment-ajax.php',
		data: dataparam,
		beforeSend: function(){
			//showloadingalert("Loading, please wait.");	
		},
		success:function(data) {
			//closeloadingalert();
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
function fn_question(sid,lessonid,testtype,qorder,anscount,maxid,mathtype)
{
	var iframe = document.createElement("IFRAME");
	iframe.setAttribute("src", "js-framequestion");
	document.documentElement.appendChild(iframe);
	iframe.parentNode.removeChild(iframe);
        
        var browcheck = detectmob();
        if(browcheck == "android" ) {
            AndroidFunction.getValue("js-framequestion");
        }
        
	iframe = null;
	var uid = $('#uid').val();
	if(testtype==1)
		var msg = "Diagnostic Test";
	else if(testtype==2)
		var msg = "Mastery Test 1";
	else if(testtype==3)
		var msg = "Mastery Test 2";
	
	var dataparam="oper=questionview&sid="+sid+"&lessonid="+lessonid+"&qorder="+qorder+"&anscount="+anscount+"&testtype="+testtype+"&maxid="+maxid+"&mathtype="+mathtype+"&uid="+uid;	
	//alert(dataparam);
	$.ajax({
		type: 'post',
		url: 'ipad-assignment-ajax.php',
		data: dataparam,
		beforeSend: function(){
			//showloadingalert("Loading, please wait.");	
			$('#divlbcontent').html(loadcontent);			
		},
		success:function(data) {
			//closeloadingalert();	
			$('#divlbcontent').html('');			
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
	
	var uid = $('#uid').val();
		var iframe = document.getElementById('ifrm');
		var innerDoc = iframe.contentDocument || iframe.contentWindow.document;
		
		var input = innerDoc.getElementById('answertypeid');
		var anstype=input.value;
		
		var input = innerDoc.getElementById('orientationflag');
		var orientationflag=input.value;
		
		var input = innerDoc.getElementById('boxcount');
		var boxcount=input.value;
		var answer ='';	
		var qorder=$('#qorder').val();
		var cqorder=$('#current_qorder').val();
		//alert(qorder);
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
			//alert(answer);							
		}
		else if(anstype==4)
		{		
			for(i=1;i<=boxcount;i++){			
				var input = innerDoc.getElementById('txt_'+i);
				//alert(input.value);
				if(i==boxcount)
					answer+=encodeURIComponent(input.value);
				else
					answer+=encodeURIComponent(input.value)+'~';
			}	
			//alert(answer);							
		}
		else if(anstype==5 || anstype==1 || anstype==8)
		{
			var input = innerDoc.getElementById('answer');
			var answer = encodeURIComponent(input.value);
			//alert(answer);
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
			//alert(answer);
		}			
		var dataparam="oper=answercheck&sid="+sid+"&anstype="+anstype+"&quesid="+quesid+"&anscount="+anscount+"&answer="+answer+"&testtype="+testtype+"&cqorder="+cqorder+"&maxid="+maxid+"&uid="+uid;	
		$.ajax({
		type: "POST",
		url: 'ipad-assignment-ajax.php',
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
						/*if(qorder==0){
							if(testtype==2)
							{
								fn_mastery1finish(sid,lessonid,data[0],maxid);		
							}
							else
							{
								fn_mastery2finish(sid,lessonid,data[0],maxid);
							}
						}
						else
						{
							fn_question(sid,lessonid,testtype,qorder,data[0],maxid);
						} */
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
	var iframe = document.createElement("IFRAME");
	iframe.setAttribute("src", "js-framestart:"+lessonid);
	document.documentElement.appendChild(iframe);
	iframe.parentNode.removeChild(iframe);
        var browcheck = detectmob();
	 if(browcheck == "android" ) {
    	AndroidFunction.getValue("js-framestart:"+lessonid);
     }	
	iframe = null;	
	var uid = $('#uid').val();
	if(anscount==3 && orientationflag!=2)  //if all the question correct
	{
		var dataparam = "oper=diagpass&maxid="+maxid+"&lessonid="+lessonid+"&sid="+sid+"&uid="+uid;	
	}
	else
	{
		var count=3-anscount;
		var dataparam = "oper=diagfail&maxid="+maxid+"&lessonid="+lessonid+"&count="+count+"&sid="+sid+"&uid="+uid;	
	}
		$.ajax({
		type: "POST",
		url: 'ipad-assignment-ajax.php',
		data: dataparam,
		beforeSend:function(){			
			//showloadingalert("Loading, please wait.");
			$('#divlbcontent').html(loadcontent);
		},
		success: function(data){
				$('#divlbcontent').html('');				
				$('#divlbcontent').html(trim(data));				
				//closeloadingalert();
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
	var iframe = document.createElement("IFRAME");
	iframe.setAttribute("src", "js-framestart:"+lessonid);
	document.documentElement.appendChild(iframe);
	iframe.parentNode.removeChild(iframe);
        var browcheck = detectmob();
	 if(browcheck == "android" ) {
    	AndroidFunction.getValue("js-framestart:"+lessonid);
        //alert("lessonid->"+lessonid);
        }
        
	iframe = null;
	var uid = $('#uid').val();	
	var dataparam="oper=playlesson&lessonid="+lessonid+"&maxid="+maxid+"&sid="+sid+"&orientationflag="+orientationflag+"&uid="+uid;	
	$.ajax({
		type: "POST",
		url: 'ipad-assignment-ajax.php',
		data: dataparam,
		beforeSend:function(){
				//showloadingalert("Loading, please wait.");
				$('#divlbcontent').html(loadcontent);
                                $('#fottitle').html('');
			},
		success: function(data){
			var iframe = document.createElement("IFRAME");
			iframe.setAttribute("src", "js-framelesson:"+sid+"~"+lessonid+"~"+maxid);
			document.documentElement.appendChild(iframe);
			iframe.parentNode.removeChild(iframe);
                        
                        if(browcheck == "android" ) {
                   AndroidFunction.getValue("js-framelesson:"+sid+"~"+lessonid+"~"+maxid);
                   }
			iframe = null;		
		}
	});	
}


/*----
    fn_slidecheck()
	Function to check the lesson is completed or not. It was called by the oper= lessonplay in ajaxpage
----*/
function fn_slidecheck(sid,lessonid,maxid,orientationflag)
{
	var uid = $('#uid').val();
	var dataparam="oper=slidecheck&lessonid="+lessonid+"&maxid="+maxid+"&sid="+sid+"&uid="+uid;	
	$.ajax({
		type: "POST",
		url: 'ipad-assignment-ajax.php',
		data: dataparam,
		success: function(data){
			if(trim(data)=='completed' || trim(data)=='passed') ///the lesson is completed then start mastery1
			{
				//$('.bottom_continue').show();
				/*if(orientationflag==1)
				{
					fn_orientationcomplete(sid,lessonid,maxid);
				}
				else
				{*/
					fn_startmastery1(sid,lessonid,maxid,1);
				//}
			}
	}
	});	
}

function fn_checklockstatus(sid,lessonid,maxid)
{
	var uid = $('#uid').val();
	var dataparam="oper=checklockstatus&maxid="+maxid+"&uid="+uid;	
	$.ajax({
		type: "POST",
		url: 'ipad-assignment-ajax.php',
		data: dataparam,
		async:false,
		success: function(data){
			if(trim(data)==1) ///the lesson is completed then start mastery1
			{
				var iframe = document.createElement("IFRAME");
				iframe.setAttribute("src", "js-frame:lockrelease");
				document.documentElement.appendChild(iframe);
				iframe.parentNode.removeChild(iframe);
				iframe = null;
				clearInterval(lockstatus);
				fn_startmastery2(sid,lessonid,maxid,1)
			}
	}
	});	
}

function fn_remslidecheck(maxid)
{	 
	var dataparam="oper=remslidecheck&maxid="+maxid;
	$.ajax({
		type: "POST",
		url: 'ipad-assignment-ajax.php',
		data: dataparam,
		async:false,
		success: function(data){
			if(trim(data)=='completed' || trim(data)=='passed') ///the lesson is completed then start mastery1
			{
				
				/*var iframe = document.createElement("IFRAME");
				iframe.setAttribute("src", "js-frame1:~completed");
				document.documentElement.appendChild(iframe);
				iframe.parentNode.removeChild(iframe);
				iframe = null;*/
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
		var uid = $('#uid').val();
		clearInterval(interval);  //this is for remove the setinterval function started from the oper= lessonplay in ajaxpage	
		var dataparam="oper=orientationcomplete&lessonid="+lessonid+"&maxid="+maxid+"&sid="+sid+"&uid="+uid;		
		$.ajax({
		type: "POST",
		url: 'ipad-assignment-ajax.php',
		data: dataparam,
		beforeSend:function(){			
			//showloadingalert("Loading, please wait.");
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
        $('#fottitle').html('');
	//alert(sid+"~"+lessonid+"~"+maxid);
	if(sid=='' || sid==undefined){
	var sid = $('#scheduleid').val();
	var lessonid = $('#lessonid').val();
	var maxid = $('#maxid').val();
	}
	var iframe = document.createElement("IFRAME");
	iframe.setAttribute("src", "js-framestart:"+lessonid);
	document.documentElement.appendChild(iframe);
	iframe.parentNode.removeChild(iframe);

        var browcheck = detectmob();
	 if(browcheck == "android" ) {
    	AndroidFunction.getValue("js-framestart:"+lessonid);
     }
	iframe = null;
	var uid = $('#uid').val();
		//if(check==1)
			//clearInterval(interval);  //this is for remove the setinterval function started from the oper= lessonplay in ajaxpage	
		var dataparam="oper=mastery1start&lessonid="+lessonid+"&maxid="+maxid+"&sid="+sid+"&uid="+uid;		
		$.ajax({
		type: "POST",
		url: 'ipad-assignment-ajax.php',
		data: dataparam,
		beforeSend:function(){			
			//showloadingalert("Loading, please wait.");
			$('#divlbcontent').html(loadcontent);
		},
		success: function(data){
			data = data.split('~');				
			$('.dialogTitleFullScr').html(data[1]);			
			$('#divlbcontent').html('');
			$('#divlbcontent').html(trim(data[0]));
			//closeloadingalert();
			$('#fottitle').html('Mastery Test 1');	
			}
		});	
}


/*----
    fn_startmastery2()
	Function to start mastery test2
----*/
function fn_startmastery2(sid,lessonid,maxid,check)
{
	var iframe = document.createElement("IFRAME");
	iframe.setAttribute("src", "js-framestart:"+lessonid);
	document.documentElement.appendChild(iframe);
	iframe.parentNode.removeChild(iframe);
        var browcheck = detectmob();
	 if(browcheck == "android" ) {
    	AndroidFunction.getValue("js-framestart:"+lessonid);
     }	 
	iframe = null;
	if(check==1)
		clearInterval(lockstatus);
	var uid = $('#uid').val();
	var dataparam="oper=mastery2start&lessonid="+lessonid+"&maxid="+maxid+"&sid="+sid+"&uid="+uid;	
	$.ajax({
	type: "POST",
	url: 'ipad-assignment-ajax.php',
	data: dataparam,
	beforeSend:function(){			
		//showloadingalert("Loading, please wait.");
		$('#divlbcontent').html(loadcontent);
	},
	success: function(data){
		data = data.split('~');				
		$('.dialogTitleFullScr').html(data[1]);	
		$('#divlbcontent').html('');
		$('#divlbcontent').html(trim(data[0]));	
		//closeloadingalert();
		$('#fottitle').html('Mastery Test 2');	
		}
	});	
}


/*----
    fn_remediation()
	Function to play the remediation
----*/
function fn_remediation(sid,lessonid,testtype,qorder,anscount,quesid,maxid,ansmaxid)
{
	//alert("rem");
	var uid = $('#uid').val();
		
		var dataparam="oper=remediation&lessonid="+lessonid+"&maxid="+maxid+"&testtype="+testtype+"&qorder="+qorder+"&anscount="+anscount+"&quesid="+quesid+"&sid="+sid+"&uid="+uid+"&ansmaxid="+ansmaxid;			
		$.ajax({
		type: "POST",
		url: 'ipad-assignment-ajax.php',
		data: dataparam,
		beforeSend:function(){			
			//showloadingalert("Loading, please wait.");
			$('#divlbcontent').html(loadcontent);
		},
		success: function(data){
			data = data.split('~');
			//$('#divlbcontent').html('');	
			//$('#divlbcontent').html(trim(data));	
			var iframe = document.createElement("IFRAME");
			iframe.setAttribute("src", "js-framerem:"+data[1]);
			document.documentElement.appendChild(iframe);
			iframe.parentNode.removeChild(iframe);
			iframe = null;
			console.log(data[0]);
			eval(data[0]);
			//closeloadingalert();
			//$('#fottitle').html('Remediation Slide');			
			}
		});	
}

function iostest(){ //str
	alert("iPad test");
	//$('#fottitle').html(str);	
}
/*----
    fn_mastery1finish()
	Function to show the master1 finish page
----*/
function fn_mastery1finish(sid,lessonid,anscount,maxid)
{
	var iframe = document.createElement("IFRAME");
	iframe.setAttribute("src", "js-framestart:"+lessonid);
	document.documentElement.appendChild(iframe);
	iframe.parentNode.removeChild(iframe);
        var browcheck = detectmob();
	 if(browcheck == "android" ) {
    	AndroidFunction.getValue("js-framestart:"+lessonid);
     //alert("lessonid->"+lessonid); 
    }	
	iframe = null;
	var uid = $('#uid').val();
	
	if(anscount==3) //if the user get mastered
	{
		var dataparam = "oper=mastery1pass&maxid="+maxid+"&lessonid="+lessonid+"&sid="+sid+"&uid="+uid;	
	}
	else
	{		
		var dataparam = "oper=mastery1fail&maxid="+maxid+"&lessonid="+lessonid+"&sid="+sid+"&uid="+uid;	
	}
		$.ajax({
		type: "POST",
		url: 'ipad-assignment-ajax.php',
		data: dataparam,
		beforeSend:function(){			
			//showloadingalert("Loading, please wait.");
			$('#divlbcontent').html();
		},
		success: function(data){
				data = data.split('~');				
				//$('.dialogTitleFullScr').html(data[1]);	
			    $('#divlbcontent').html('');
				$('#divlbcontent').html(trim(data[0]));
				//closeloadingalert();
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
	var iframe = document.createElement("IFRAME");
	iframe.setAttribute("src", "js-frame-lessoncompleted:"+lessonid);
	document.documentElement.appendChild(iframe);
	iframe.parentNode.removeChild(iframe);

                    if(browcheck == "android" ) {
                   AndroidFunction.getValue("js-frame-lessoncompleted:"+lessonid);
                }
	iframe = null;
	var uid = $('#uid').val();
	
	if(anscount==3) //if the user mastered
	{
		var dataparam = "oper=mastery2pass&maxid="+maxid+"&lessonid="+lessonid+"&sid="+sid+"&uid="+uid;	
	}
	else
	{		
		var dataparam = "oper=mastery2fail&maxid="+maxid+"&lessonid="+lessonid+"&sid="+sid+"&uid="+uid;	
	}
		$.ajax({
		type: "POST",
		url: 'ipad-assignment-ajax.php',
		data: dataparam,
		beforeSend:function(){			
			//showloadingalert("Loading, please wait.");
			$('#divlbcontent').html(loadcontent);
		},
		success: function(data){
				data = data.split('~');				
				//$('.dialogTitleFullScr').html(data[1]);	
				$('#divlbcontent').html('');
				$('#divlbcontent').html(trim(data[0]));
				//closeloadingalert();
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
	var iframe = document.createElement("IFRAME");
	iframe.setAttribute("src", "js-framestart:"+lessonid);
	document.documentElement.appendChild(iframe);
	iframe.parentNode.removeChild(iframe);
        
        var browcheck = detectmob();
               if(browcheck == "android" ) {
              AndroidFunction.getValue("js-framestart:"+lessonid);
           }
	iframe = null;	
	var iframe = document.createElement("IFRAME");
	iframe.setAttribute("src", "js-framereviewlesson:"+sid+"~"+lessonid+"~"+maxid);
	document.documentElement.appendChild(iframe);
	iframe.parentNode.removeChild(iframe);
        var browcheck = detectmob();
               if(browcheck == "android" ) {
              AndroidFunction.getValue("js-framereviewlesson:"+sid+"~"+lessonid+"~"+maxid);
           }
	iframe = null;	
	var uid = $('#uid').val();
	var dataparam = "oper=review&lessonid="+lessonid+"&maxid="+maxid+"&sid="+sid+"&uid="+uid;	
	$.ajax({
		type: "POST",
		url: 'ipad-assignment-ajax.php',
		data: dataparam,
		beforeSend:function(){
				//showloadingalert("Loading, please wait.");
				//$('#divlbcontent').html(loadcontent);
			},
		success: function(data){
			/*$('#divlbcontent').html('');
			$('#divlbcontent').html(trim(data));
			//closeloadingalert();
			$('#fottitle').html('Review the Lesson');*/	
	}
	});	
}


/*----
    fn_completed()
	Function to show the completed state of schedule
----*/
function fn_completed(sid)
{
	var iframe = document.createElement("IFRAME");
	iframe.setAttribute("src", "js-framescheduleCompleted:"+sid);
	document.documentElement.appendChild(iframe);
	iframe.parentNode.removeChild(iframe);
        var browcheck = detectmob();
	 if(browcheck == "android" ) {
    	AndroidFunction.getValue("js-framescheduleCompleted:"+sid);
        }
	iframe = null;

	var uid = $('#uid').val();	
	var dataparam = "oper=completed&sid="+sid+"&uid="+uid; 
	$.ajax({
		type: "POST",
		url: 'ipad-assignment-ajax.php',
		data: dataparam,
		beforeSend:function(){			
			//showloadingalert("Loading, please wait.");
			$('#divlbcontent').html(loadcontent);
		},
		success: function(data){			
			$('#divlbcontent').html('');
			$('#divlbcontent').html(trim(data));	
			//closeloadingalert();
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
	setTimeout("removesections('#home');",500);
	setTimeout('showpages("assignment","assignment/assignment.php");',500);
}


/*----
    fn_closescreen()
	Function to call unload function the when the user terminate the test using close button
----*/
function fn_closescreen()
{
	var testtype = $('#testtype').val();
	var mathtype = $('#mathtype').val();
	var sid = $('#scheduleid').val();
	var lid = $('#lid').val();
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
			var iframe = document.createElement("IFRAME");
    iframe.setAttribute("src", "js-frame:~"+sid+'~'+lid);
    document.documentElement.appendChild(iframe);
    iframe.parentNode.removeChild(iframe);
    
    var browcheck = detectmob();
			if(browcheck == "android" ) {
				AndroidFunction.getValue("js-frame:~"+sid+'~'+lid);
			}
    iframe = null;
						
			/*closefullscreenlesson();
			if(mathtype!=2 && mathtype!=5)
			fn_loadschedule();*/
		}
	}
	else{
		var iframe = document.createElement("IFRAME");
    iframe.setAttribute("src", "js-frame:~"+sid+'~'+lid);
    document.documentElement.appendChild(iframe);
    iframe.parentNode.removeChild(iframe);
    
    var browcheck = detectmob();
		if(browcheck == "android" ) {
			AndroidFunction.getValue("js-frame:~"+sid+'~'+lid);
		}
                
    iframe = null;
				
	}	
}


/*----
    fn_mathmodulenextlesson()
	Function to fail the current test while terminate the test
----*/
function fn_mathmodulenextlesson(sid,lessonid,mathtype)
{			
	var uid = $('#uid').val();
	var dataparam="oper=nextlesson&sid="+sid+"&lessonids="+$('#lessonids').val()+"&mathtype="+mathtype+"&uid="+uid;	
	$.ajax({
		type: "POST",
		url: 'ipad-assignment-ajax.php',
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
	//alert("unload");
	var uid = $('#uid').val();	
	var maxid=$('#maxid').val();	
	var dataparam="oper=unload&testtype="+testtype+"&maxid="+maxid+"&uid="+uid;	
	//alert(dataparam);
	$.ajax({
		type: "POST",
		url: 'ipad-assignment-ajax.php',
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