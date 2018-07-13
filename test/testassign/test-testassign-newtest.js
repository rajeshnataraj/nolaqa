/*---- Save Step-1&2
    fn_next()
	Function to save a test
----*/
function isNumberKey(evt)
{

var charCode = (evt.which) ? evt.which : event.keyCode
if (charCode > 31 && (charCode < 48 || charCode > 57))
return false;

return true;
}

/***********Share Pitsco Assessment For Teacher Developed By Mohan M Updated By 19-8-2015*****************/

function fn_next_forpitsco_duplicate(testid)
{
     var prepost=$('#prepostid').val();
    var destid=$('#destid').val();    
    var taskid=$('#taskid').val();   
    var resid=$('#resid').val();  
    var products=$('#ddlproducts').val();
    var dataparam = '';
    if($('#ddlasstype').val()=='1')
    {
        if(prepost ==''){
            showloadingalert("Please select pre/post");	
            setTimeout('closeloadingalert()',2000);
            return false;
        }
    }

     if($('#ddlasstype').val()=='0' && $('#ddlcontents').val()!='0')
        {
           
            if(products=='0'){
                showloadingalert("Please select Product");	
                setTimeout('closeloadingalert()',2000);
                return false;
            }
        }

    if($("#testform").validate().form())
    {
        var counter=$('#counter').val();
        for(i=1;i<=counter-1;i++)
        {
                var j = i;
                j++;
                if($('#lettergrade' + i).val()=='')
                {
                        $('#lettergrade' + i).focus();
                        showloadingalert("Lettergrade is Required");	
                        setTimeout('closeloadingalert()',2000);
                        return false;
                }
                if($('#lettergrade' + i).val().match(/[^A-Z+]/i))
                {
                        showloadingalert("Lettergrade cannot have special characters and space and except + symbol");	
                        setTimeout('closeloadingalert()',2000);
                        return false;
                }

                if($('#lowerbound' + i).val()=='')
                {
                        $('#lowerbound' + i).focus();
                        showloadingalert("LowerBound is Required");	
                        setTimeout('closeloadingalert()',2000);
                        return false;
                }
                if(parseInt($('#lowerbound' + i).val()) <= parseInt($('#higherbound' + j).val()))
                {
                        $('#lowerbound' + i).val('');
                        $('#lowerbound' + i).focus();
                        showloadingalert("LowerBound is greater then or equal to Previous Higher Bound");	
                        setTimeout('closeloadingalert()',2000);
                        return false;
                }
                if(parseInt($('#lowerbound' + i).val()) >= parseInt($('#higherbound' + i).val()))
                {
                        $('#lowerbound' + i).val('');
                        $('#lowerbound' + i).focus();
                        showloadingalert("LowerBound is greater then or equal to Higher Bound");	
                        setTimeout('closeloadingalert()',2000);
                        return false;
                }
                if($('#lowerbound' + i).val()>=99)
                {
                        $('#lowerbound' + i).val('');
                        $('#lowerbound' + i).focus();
                        showloadingalert("LowerBound Only Allowed less then or equal to 100");	
                        setTimeout('closeloadingalert()',2000);
                        return false;
                }
                if($('#higherbound' + i).val()=='')
                {
                        $('#higherbound' + i).focus();
                        showloadingalert("HigherBound is Required");	
                        setTimeout('closeloadingalert()',2000);
                        return false;
                }
                if($('#higherbound' + i).val()<0 || $('#higherbound' + i).val() >100)
                {
                        $('#higherbound' + i).val('');
                        $('#higherbound' + i).focus();
                        showloadingalert("HigherBound Only Allowed less then or equal to 100");	
                        setTimeout('closeloadingalert()',2000);
                        return false;
                }

                for(j=i+1;j<=counter-1;j++)
                {						
                        if($('#lowerbound' + i).val()==$('#lowerbound' + j).val())
                        {
                                $('#lowerbound' + i).val('');
                                $('#lowerbound' + i).focus();
                                showloadingalert("LowerBound Value is not unique");	
                                setTimeout('closeloadingalert()',2000);
                                return false;
                        }
                        if($('#higherbound' + i).val()==$('#higherbound' + j).val())
                        {
                                $('#higherbound' + i).val('');
                                $('#higherbound' + i).focus();
                                showloadingalert("HigherBound Value is not unique");	
                                setTimeout('closeloadingalert()',2000);
                                return false;
                        }
                }
        }
        var lettergrade='';
        var lowerbound='';
        var higherbound='';
        var boxid='';

        for(i=1; i<=counter-1; i++)
        {
                boxid+=i+"~";
                lettergrade+= $('#lettergrade' + i).val()+"~";
                lowerbound+= $('#lowerbound' + i).val()+"~";
                higherbound+= $('#higherbound' + i).val()+"~";
        }
        lowerbound =  lowerbound.replace(/[^a-zA-Z 0-9 ~]+/g,'');
        higherbound =  higherbound.replace(/[^a-zA-Z 0-9 ~]+/g,'');

        $('#lg').val( lettergrade);
        $('#lb').val( lowerbound);
        $('#hb').val( higherbound);
        $('#boxid').val(boxid);

        if($('#grade').is(':checked'))
        {
                var val=1;
        }	
        else
        {
                var val=0;
        }

        dataparam="oper=step2forpitscoassessment&testid="+testid+"&lettergrade="+$('#lg').val()+"&lowerbound="+$('#lb').val()+"&higherbound="+$('#hb').val()+"&testname="+escapestr($('#testname').val())+"&testdes="+escapestr($('#testdes').val())+"&timelimit="+$('#timelimit').val()+"&score="+$('#score').val()+"&attempts="+$('#attempts').val()+"&boxid="+$('#boxid').val()+"&grade="+val+"&remove="+$('#removecounter').val()+"&asstype="+$('#ddlasstype').val()+"&assexp="+$('#ddlexp').val()+"&assmis="+$('#ddlmis').val()+"&tags="+escapestr($('#form_tags_test').val())+"&prepost="+prepost+"&destid="+destid+"&taskid="+taskid+"&resid="+resid+"&contentid="+$('#ddlcontents').val()+"&productid="+products;
        $.ajax({
                type: 'post',
                url: 'test/testassign/test-testassign-newtestdb.php',
                data: dataparam,		
                beforeSend: function(){
                        showloadingalert("Saving, please wait.");	
                },
                success:function(ajaxdata) {
                        var ajaxdata = ajaxdata.split("~");	
                        if(ajaxdata[0]=='success'){	
                                $('.lb-content').html("Saved");
                                closeloadingalert();
                                var val = ajaxdata[1]+","+2;

                                if($('#hidflag').val()!=1)
                                setTimeout('removesections("#test");',500);
                                else
                                setTimeout('removesections("#test-testassign-actions");',500);
                                $('#btntest-testassign-testquestion').removeClass("dim");
                                setTimeout('showpageswithpostmethod("test-testassign-steps","test/testassign/test-testassign-steps.php","id='+val+'");',500);
                        }
                        else 
                        {
                                $('.lb-content').html("Incorrect Data");
                                setTimeout('closeloadingalert()',1000);
                        }
                }
        });
    }
}

/***********Share Pitsco Assessment For Teacher Developed By Mohan M Updated By 19-8-2015*****************/

function fn_next(testid)
{
        if($('#ddlasstype').val()=='2'){
        var prepost=$('#misprepostid').val();
        }
        else{
        var prepost=$('#prepostid').val();
        var resid=$('#resid').val();
        }
	var destid=$('#destid').val();
	var taskid=$('#taskid').val();
	
        var dlexp =  $('#ddlexp').val();
        var dlmis =  $('#ddlmis').val();
        var products=$('#ddlproducts').val();
        
        
	var dataparam = '';
	if($('#ddlasstype').val()=='1')
	{
            if(dlexp==''){
                showloadingalert("Please select Expedition");	
                setTimeout('closeloadingalert()',2000);
                return false;
            }
        }
         if($('#ddlasstype').val()=='2')
	{
            if(dlmis==''){
                showloadingalert("Please select Mission");	
                setTimeout('closeloadingalert()',2000);
                return false;
            }
        }
        
        if($('#ddlasstype').val()=='1' || $('#ddlasstype').val()=='2')
	{
            if(prepost==''){
                showloadingalert("Please select pre/post");	
                setTimeout('closeloadingalert()',2000);
                return false;
            }
	}

        if($('#ddlasstype').val()=='0' && $('#ddlcontents').val()!='0')
        {
           
            if(products=='0'){
                showloadingalert("Please select Product");	
                setTimeout('closeloadingalert()',2000);
                return false;
            }
        }

	if($("#testform").validate().form())
	{
		var counter=$('#counter').val();
		for(i=1;i<=counter-1;i++)
		{
			var j = i;
			j++;
			if($('#lettergrade' + i).val()=='')
			{
				$('#lettergrade' + i).focus();
				showloadingalert("Lettergrade is Required");	
				setTimeout('closeloadingalert()',2000);
				return false;
			}
			if($('#lettergrade' + i).val().match(/[^A-Z+]/i))
			{
				showloadingalert("Lettergrade cannot have special characters and space and except + symbol");	
				setTimeout('closeloadingalert()',2000);
				return false;
			}
	
			if($('#lowerbound' + i).val()=='')
			{
				$('#lowerbound' + i).focus();
				showloadingalert("LowerBound is Required");	
				setTimeout('closeloadingalert()',2000);
				return false;
			}
			if(parseInt($('#lowerbound' + i).val()) <= parseInt($('#higherbound' + j).val()))
			{
				$('#lowerbound' + i).val('');
				$('#lowerbound' + i).focus();
				showloadingalert("LowerBound is greater then or equal to Previous Higher Bound");	
				setTimeout('closeloadingalert()',2000);
				return false;
			}
			if(parseInt($('#lowerbound' + i).val()) >= parseInt($('#higherbound' + i).val()))
			{
				$('#lowerbound' + i).val('');
				$('#lowerbound' + i).focus();
				showloadingalert("LowerBound is greater then or equal to Higher Bound");	
				setTimeout('closeloadingalert()',2000);
				return false;
			}
			if($('#lowerbound' + i).val()>=99)
			{
				$('#lowerbound' + i).val('');
				$('#lowerbound' + i).focus();
				showloadingalert("LowerBound Only Allowed less then or equal to 100");	
				setTimeout('closeloadingalert()',2000);
				return false;
			}
			if($('#higherbound' + i).val()=='')
			{
				$('#higherbound' + i).focus();
				showloadingalert("HigherBound is Required");	
				setTimeout('closeloadingalert()',2000);
				return false;
			}
			if($('#higherbound' + i).val()<0 || $('#higherbound' + i).val() >100)
			{
				$('#higherbound' + i).val('');
				$('#higherbound' + i).focus();
				showloadingalert("HigherBound Only Allowed less then or equal to 100");	
				setTimeout('closeloadingalert()',2000);
				return false;
			}
	
			for(j=i+1;j<=counter-1;j++)
			{						
				if($('#lowerbound' + i).val()==$('#lowerbound' + j).val())
				{
					$('#lowerbound' + i).val('');
					$('#lowerbound' + i).focus();
					showloadingalert("LowerBound Value is not unique");	
					setTimeout('closeloadingalert()',2000);
					return false;
				}
				if($('#higherbound' + i).val()==$('#higherbound' + j).val())
				{
					$('#higherbound' + i).val('');
					$('#higherbound' + i).focus();
					showloadingalert("HigherBound Value is not unique");	
					setTimeout('closeloadingalert()',2000);
					return false;
				}
			}
		}
		var lettergrade='';
		var lowerbound='';
		var higherbound='';
		var boxid='';
	
		for(i=1; i<=counter-1; i++)
		{
			boxid+=i+"~";
			lettergrade+= $('#lettergrade' + i).val()+"~";
			lowerbound+= $('#lowerbound' + i).val()+"~";
			higherbound+= $('#higherbound' + i).val()+"~";
		}
		lowerbound =  lowerbound.replace(/[^a-zA-Z 0-9 ~]+/g,'');
		higherbound =  higherbound.replace(/[^a-zA-Z 0-9 ~]+/g,'');
	
		$('#lg').val( lettergrade);
		$('#lb').val( lowerbound);
		$('#hb').val( higherbound);
		$('#boxid').val(boxid);
	
		if($('#grade').is(':checked'))
		{
			var val=1;
		}	
		else
		{
			var val=0;
		}
	
		dataparam="oper=step2&testid="+testid+"&lettergrade="+$('#lg').val()+"&lowerbound="+$('#lb').val()+"&higherbound="+$('#hb').val()+"&testname="+escapestr($('#testname').val())+"&testdes="+escapestr($('#testdes').val())+"&timelimit="+$('#timelimit').val()+"&score="+$('#score').val()+"&attempts="+$('#attempts').val()+"&boxid="+$('#boxid').val()+"&grade="+val+"&remove="+$('#removecounter').val()+"&asstype="+$('#ddlasstype').val()+"&assexp="+$('#ddlexp').val()+"&assmis="+$('#ddlmis').val()+"&tags="+escapestr($('#form_tags_test').val())+"&prepost="+prepost+"&destid="+destid+"&taskid="+taskid+"&resid="+resid+"&contentid="+$('#ddlcontents').val()+"&productid="+products;
                //alert(dataparam);
	$.ajax({
			type: 'post',
			url: 'test/testassign/test-testassign-newtestdb.php',
			data: dataparam,		
			beforeSend: function(){
				showloadingalert("Saving, please wait.");	
			},
			success:function(ajaxdata) {
				var ajaxdata = ajaxdata.split("~");	
				if(ajaxdata[0]=='success'){	
					$('.lb-content').html("Saved");
					closeloadingalert();
					var val = ajaxdata[1]+","+2;
					
					if($('#hidflag').val()!=1)
					setTimeout('removesections("#test");',500);
					else
					setTimeout('removesections("#test-testassign-actions");',500);
					$('#btntest-testassign-testquestion').removeClass("dim");
					setTimeout('showpageswithpostmethod("test-testassign-steps","test/testassign/test-testassign-steps.php","id='+val+'");',500);
				}
				else 
				{
					$('.lb-content').html("Incorrect Data");
					setTimeout('closeloadingalert()',1000);
				}
			}
		});
	}
}


/*----
    fn_deletetest()
	Function to delete the test
----*/
function fn_deletetest(id)
{	
	var dataparam = "oper=deletetest"+"&testid="+id;
	
	$.Zebra_Dialog('Are you sure you want to delete this Assessment?',
		{
			'type': 'confirmation',
			'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {
					
				$.ajax({
					type: 'post',
					url: 'test/testassign/test-testassign-newtestdb.php',
					data: dataparam,	
					beforeSend: function(){
						showloadingalert("Deleting, please wait.");	
					},		
					success:function(data) {		
						if(data=="success")
						{
							$('.lb-content').html("Assessment deleted successfully");
							closeloadingalert();
							
							setTimeout('removesections("#home");',500);
							setTimeout('showpages("test","test/test.php");',500);
						}
					}
				});	
			}}
		]
	});
}
/* Show Open response Question*/
function fn_showopenresponseques(id)
{
    var dataparam = "oper=openresponse"+"&studid="+id+"&assesmentid="+$('#selectass').val();
    $.ajax({
                    type: 'post',
                    url: 'test/testassign/test-testassign-newtestdb.php',
                    data: dataparam,
                    success:function(data) {
$('body').css({'overflow': ''}); 
		$('body').removeAttr("style");
		$('.remarkContainer').remove();
$("html,body").animate({scrollTop:$(document).height()}, 1000);
                            $('#loadquestions').html(data);
$("#test-testassign-viewsingleanswerset").hide();
                    }
            });
}
/** starts Select Answer for right /wrong/partial  created by: vijayalakshmi php programmer**/
function fn_selectansw(id,studid,quesid,cnt,ptearned)
{
    var flag = 0;
	if(id=='right')
	{
		flag = 1;
		$("#tblTransactions > tbody  > tr > td.colptearned"+cnt+" > input").each(function(){
		     $(this).val(ptearned);
		});
$("#tblTransactions .colptearned"+cnt+" input").attr('disabled', true);
		$('#PAR3_'+quesid).removeClass("blue_dark");
		$('#PAR3_'+quesid).addClass("bluep_light");
		$('#PAR2_'+quesid).removeClass("blue_dark");
		$('#PAR2_'+quesid).addClass("bluew_light");
		$('#PAR1_'+quesid).addClass("blue_dark");
		$('#PAR1_'+quesid).removeClass("bluer_light");
	}
	if(id=='wrong')
	{
		flag = 0;

		$("#tblTransactions > tbody  > tr > td.colptearned"+cnt+" > input").each(function(){
		    $(this).val(ptearned);
		});
$("#tblTransactions .colptearned"+cnt+" input").attr('disabled', true);
		$('#PAR3_'+quesid).removeClass("blue_dark");
		$('#PAR3_'+quesid).addClass("bluep_light");
		$('#PAR1_'+quesid).addClass("bluer_light");
		$('#PAR1_'+quesid).removeClass("blue_dark");
		$('#PAR2_'+quesid).addClass("blue_dark");
		$('#PAR2_'+quesid).removeClass("bluew_light");
	}
	if(id=='partial')
	{
		$('#PAR1_'+quesid).addClass("bluer_light");
		$('#PAR1_'+quesid).removeClass("blue_dark");
		$('#PAR2_'+quesid).addClass("bluew_light");
		$('#PAR2_'+quesid).removeClass("blue_dark");
		$('#PAR3_'+quesid).addClass("blue_dark");
		$('#PAR3_'+quesid).removeClass("bluep_light");
		$("#tblTransactions .colptearned"+cnt+" input").removeAttr('disabled');
		$("#tblTransactions .colptearned"+cnt+" input").focus();
	}
	var dataparam = "oper=selectresulttoanswer"+"&buttid="+id+"&stuid="+studid+"&quesid="+quesid;
        
	    $.ajax({
	            type: 'post',
	            url: 'test/testassign/test-testassign-newtestdb.php',
	            data: dataparam,
	            success:function(data) { 	                   
	            }
	    });
}
    
/**
save the asssessment score regarding the result type
**/
function fn_saveassessmentscore(cntval,studid) {
        var totcnt = cntval - 1;
	var pts_earned = [];
	var grp_questnid = [];
	var pts_possible = [];
	var dummycnt = 0;
	for(i=1;i<=totcnt;i++)
	{
		$("#tblTransactions > tbody  > tr > td.colptearned"+i+" > input").each(function(){
		    if( $(this).val() == '') {
                       dummycnt = dummycnt + 1;
		    }
	   	      pts_earned.push( $(this).val() );
		});
	}
	if(dummycnt == pts_earned.length) {

	 $.Zebra_Dialog("Please Fill Any One Points Earned Entry", { 'type': 'information', 'buttons': false, 'auto_close': 2000 });
		                    return false;
	}

	for(j=1;j<=totcnt;j++)
	{
		$("#tblTransactions > tbody  > tr > td.getquestnid"+j+" > input").each(function(){
			grp_questnid.push( $(this).val() );
		});
	}

	for(k=1;k<=totcnt;k++)
	{
		$("#tblTransactions > tbody  > tr > td.colptpossble"+k+" > input").each(function(){
		pts_possible.push( $(this).val() );
		});
	}
var dataparam = "oper=saveopenresponse"+"&stuid="+studid+"&quesid="+grp_questnid+"&pts_earned="+pts_earned+"&pts_possible="+pts_possible+"&totcnt="+totcnt;

    $.ajax({

                    type: 'post',
                    url: 'test/testassign/test-testassign-newtestdb.php',
                    data: dataparam,
			beforeSend: function(){
			showloadingalert("Saving, please wait.");	
			},
                    success:function(data) {
				if(data=='success'){ 
				$('.lb-content').html("Points Earned score has been successfull Added");
				closeloadingalert();
				setTimeout('removesections("#home");',500);
				setTimeout('showpages("test","test/test.php");',500);
				}
			}
		});
}
                        
                           
//show the drag and drop page when click single answer for particular student id

function fn_showsingleanspage(studentid,questionid,ansid) {

	$('body').css({'overflow': ''}); 
	$('body').removeAttr("style");
	$('.remarkContainer').remove();
	var dataparam = "oper=show_ind_answer"+"&studid="+studentid+"&questionid="+questionid+"&answerid="+ansid;
       
	$.ajax({
		type: 'post',
		url: 'test/testassign/test-testassign-newtestdb.php',
		data: dataparam,		
		beforeSend: function(){
			showloadingalert("Loading, please wait.");	
		},
		success:function(ajaxdata) {
			var response=trim(ajaxdata);
			var output=response.split('~');
			var status=output[0];
			var questionid=output[1];
			if(status == "success") {
				 closeloadingalert();	
				setTimeout('closeloadingalert()',1000);
	                    	var val = output[1]+","+studentid;
                     		setTimeout("removesections('#test-testassign-testopenquestion');",500);	
				$('.remarkContainer').remove();
                     		setTimeout('showpages("test-testassign-viewsingleanswerset","test/testassign/test-testassign-viewsingleanswerset.php?id='+val+'");',500);
                    }
			else
			{
				closeloadingalert();	
				setTimeout('closeloadingalert()',1000);
				var val = ansid+","+studentid;
                     		setTimeout("removesections('#test-testassign-testopenquestion');",500);	
				$('.remarkContainer').remove();
                     		setTimeout('showpages("test-testassign-viewsingleanswerset","test/testassign/test-testassign-viewsingleanswerset.php?id='+val+'");',500);
 			}
		}
    });
}
// click OK button to save the comment text from textarea box

function okFn($object,$studentid,$id,$top,$left,$idname,$classname) {

       var $container;
       if($object.hasClass("remarkContainer"))
          $container = $object;
        else
            $container = $object.closest(".remarkContainer");

  	var $commenttext = $container.find('textarea').val(); 

 	$container.data('$button').data('textContent', $container.find('textarea').val());
	$container.data('$button').attr('data-remarkDisplayed',"false");
 $container.remove();
      
	$.ajax({
                type	: "POST",
                cache	: false,
                url	: "test/testassign/test-testassign-newtestdb.php",
                data:"oper=savecommented&studentid="+$studentid+"&id="+$id+"&top="+$top+"&left="+$left+"&commenttext="+escapestr($commenttext)+"&idname="+$idname+"&classname="+$classname,

                success: function(data) {		  
		   $('#x'+$idname).remove();
               }
            });
}
// click cancel button when cancel the comment text from text area

function cancelFn($object,imgidname) {
       var $container;
       if($object.hasClass("remarkContainer"))
          $container = $object;
        else
            $container = $object.closest(".remarkContainer");
  
        $container.data('$button').attr('data-remarkDisplayed',"false");
        $container.remove();
}

// start position for drag images from parent div

  function getdragposition(xpos,ypos,ansid,studentid) {
	var dataparam = "oper=delete_ind_answer"+"&xpos="+xpos+"&ypos="+ypos+"&answerid="+ansid+"&studid="+studentid;

	$.ajax({
		type: 'post',
		url: 'test/testassign/test-testassign-newtestdb.php',
		data: dataparam,
		success: function(data) {		
		}
	});
  }
  function  getcontent_textarea($commentField,$studentid,$id,$top,$left,$idname,$classname)
  {

	var dataparam = "oper=get_content_text"+"&xpos="+$left+"&ypos="+$top+"&answerid="+$id+"&studid="+$studentid+"&idname="+$idname+"&classname="+$classname;

	$.ajax({
		type: 'post',
		url: 'test/testassign/test-testassign-newtestdb.php',
		data: dataparam,
	success: function(data) {
		var data = data.split("~");	
		if(data[0]=='success'){	
		$commentField.find('textarea').val(data[1]); 
		}
		else {
		$commentField.find('textarea').val(''); 
		}
	}
	});
  }


/** ends Select Answer for right /wrong/partial  created by: vijayalakshmi php programmer**/

function fn_deletecommenttext(xpos,ypos,ansid,studentid,imgidname,id) {


$.Zebra_Dialog('Are you sure you want to delete ?',
    {
    'type':     'confirmation',
    'buttons':  [
            {caption: 'No', callback: function() { $("."+imgidname).hide(); }},
            {caption: 'Yes', callback: function() { 

           var dataparam = "oper=delete_ind_commenttext"+"&xpos="+xpos+"&ypos="+ypos+"&answerid="+ansid+"&studid="+studentid+"&id="+id;
            $.ajax({
                    type: 'post',
                    url: 'test/testassign/test-testassign-newtestdb.php',
                    data: dataparam,
                    baforeSend:function(){
                         showloadingalert("Loading, please wait.");
                    },
                    success:function(data) {
                            showloadingalert("Deleted Successfully.");
                            setTimeout("closeloadingalert()",1000);
 			    $("#x"+imgidname).remove();
var val = ansid+","+studentid;
                     		setTimeout("removesections('#test-testassign-testopenquestion');",500);	
                     		setTimeout('showpages("test-testassign-viewsingleanswerset","test/testassign/test-testassign-viewsingleanswerset.php?id='+val+'");',500);
             
                    }
            });	
                       
             }},
                ]
    });
	return false;
}

function savedropposition(xpos,ypos,ansid,studentid,classname,idname,$button) {

var dataparam = "oper=savedrop_ind_tag"+"&xpos="+xpos+"&ypos="+ypos+"&answerid="+ansid+"&studid="+studentid+"&classname="+classname+"&idname="+idname;

	$.ajax({
		type: 'post',
		url: 'test/testassign/test-testassign-newtestdb.php',
		data: dataparam,
		success: function(data) {
		   commentdatabox(xpos,ypos,ansid,studentid,$button,classname,idname,data)
}
	});
}

function commentdatabox(OffsetX,OffsetY,questionid,studentid,$button,imgclassname,imgidname,id) {


var $commentField = $('<div id=\"x'+imgidname+'\" class="remarkContainer" style="display:none;background-color:white;"><textarea style="resize: none;" rows="4" cols="25"></textarea><br>&nbsp;&nbsp;<button class="icon-synergy-create" style="font-size:18px;" title="Save"></button>&nbsp;&nbsp;<button class="icon-synergy-close" style="font-size:18px;" title="Cancel" ></button>&nbsp;&nbsp;<button class="icon-synergy-trash" style="font-size:18px;" title="Deleted" onclick="fn_deletecommenttext(\''+OffsetY+'\',\''+OffsetX+'\',\''+questionid+'\',\''+studentid+'\',\''+imgidname+'\',\''+id+'\');" ></button><br></div>');

       	$button.attr("data-remarkDisplayed","true");
getcontent_textarea($commentField,studentid,questionid,OffsetY,OffsetX,imgidname,imgclassname)

	$commentField
	    .data('$button', $button)
	    .css({
		position: 'absolute',
		left: $button.offset().left,
                top: $button.offset().top + $button.outerHeight()
	    })
	.find('textarea')	 
	    .css({
		width: 200,
		height: 100,
	    })
	.keypress(function(e) {
	    if (e.which === 13) {
		e.preventDefault();
		okFn($(this));
	    }
	})
	.end()
	.find('button:eq(0)')
	.click(function() {

	     okFn($(this),studentid,questionid,OffsetY,OffsetX,imgidname,imgclassname);
	   
	})
	.next()
	.click(function() {	

	    cancelFn($(this),imgidname);
	})

	.end()
	.end()
	.appendTo(document.body).fadeIn("slow");

}

/* Show Open response student*/
function fn_showopenresponsestudent(id)
{

    var dataparam = "oper=openresponsestudent&assesmentid="+id;
    
    $.ajax({
                    type: 'post',
                    url: 'test/testassign/test-testassign-newtestdb.php',
                    data: dataparam,
                    success:function(data) {

	              $('#loadstudent').html(data);

		    }
            });

}

function fn_showdest(expid,destid,type)
{   
        $('#destbox').removeClass('dim');
        if(type=='2'){
        $('#misprepost').removeClass('dim');
        
        } else {
        $('#prepost').removeClass('dim');
        }				
 	var dataparam = "oper=showdest&expid="+expid+"&dest="+destid+"&type="+type;
	$.ajax({
		type: 'post',
		url: 'test/testassign/test-testassign-newtestdb.php',
		data: dataparam,
		beforeSend: function(){
			 	
		},
		success:function(data)		
		
		 {		 	
		 	$('#destbox').show();		
    		$('#taskbox').show();	
                if(type!='2'){
    		$('#resbox').show();
                $('#misprepost').hide();
                }
                else{
                $('#resbox').hide();
                $('#prepost').hide();
                $('#misprepost').show();
                }
					
			$('#destbox').html(data);//Used to load the student details in the dropdown
		}
	});
}

function fn_showtask(destid,taskid,type)
{  
    $('#taskbox').removeClass('dim');
    var dataparam = "oper=showtask&destid="+destid+"&task1="+taskid+"&type="+type;   
    $.ajax({
            type: 'post',
            url: 'test/testassign/test-testassign-newtestdb.php',
            data: dataparam,
            beforeSend: function(){

            },
            success:function(data)
             {                   
                    $('#taskbox').show();		
                    $('#taskbox').html(data);//Used to load the student details in the dropdown
            }
    });
}

function fn_showres(taskid,resid,type)
{
        if(type!='2'){
        $('#resbox').removeClass('dim');
        
        }
        else
        $('#resbox').hide();
        
  	var dataparam = "oper=showres&taskid="+taskid+"&res="+resid+"&type="+type;	
	$.ajax({
		type: 'post',
		url: 'test/testassign/test-testassign-newtestdb.php',
		data: dataparam,
		beforeSend: function(){
			 	
		},
		success:function(data)		
		
		 {		 	
			if(type!='2'){
			$('#resbox').show();		
                        $('#misprepost').hide();
			$('#resbox').html(data);//Used to load the student details in the dropdown
                        }else{
                        $('#resbox').hide();	
			$('#misresbox').show();
                        $('#prepost').hide();
		}
                }
	});
}

function fn_showproducts()
{

    var dataparam="oper=showproducts&contentid="+$('#ddlcontents').val()+"&productid="+$('#productid').val();
    
    $.ajax({
            type: 'post',
            url: 'test/testassign/test-testassign-newtestdb.php',
            data: dataparam,
            beforeSend: function(){

            },
            success:function(data)
            {
                    
                    $('#productsdiv').show();		
                    $('#productsdiv').html(data);//Used to load the products details in the dropdown
            }
    });
}
