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
    fn_classassign()
	Function to save student
----*/
function fn_classassign(testid,empty,testname)
{
	var list1 = [];
	var list2 = [];
	
	$("div[id^=list1_]").each(function(){
		list1.push($(this).attr('id').replace('list1_',''));
	});
		
	$("div[id^=list2_]").each(function(){
		list2.push($(this).attr('id').replace('list2_',''));
	});
			
	var dataparam = "oper=maptoclass"+"&testid="+testid+"&list1="+list1+"&list2="+list2;	
	$.ajax({
		type: "POST",
		url: 'test/testassign/test-testassign-addstudentsdb.php',
		data: dataparam,
		beforeSend:function()
		{
			showloadingalert("Loading, please wait.");
		},
		success: function(data)
		{
			closeloadingalert();
			setTimeout("closeloadingalert();",1000);
			var val = testid+","+empty+","+$('#testnames').val();
			setTimeout('removesections("#test");',500);			
			setTimeout('showpages("test-testassign-actions","test/testassign/test-testassign-actions.php?id='+val+'");',500);
		}
	});
}


/*---- 
    fn_studentassign()
	Function to save student
----*/
function fn_studentassign(testid)
{
	var list1 = [];
	var list2 = [];
	
	if($("#frmselectstandard").validate().form())
	{
		$("div[id^=list1_]").each(function(){
			list1.push($(this).attr('id').replace('list1_',''));
		});
		
		$("div[id^=list2_]").each(function(){
			list2.push($(this).attr('id').replace('list2_',''));
		});
		
		var clasid=$('#classid').val();
		
		var dataparam = "oper=maptotest&testid="+testid+"&list1="+list1+"&list2="+list2+"&clasid="+clasid+"&sdate1="+$('#sdate1').val()+"&predate="+$('#predate').val()+"&edate1="+$('#edate1').val();
		$.ajax({
			type: "POST",
			url: 'test/testassign/test-testassign-addstudentsdb.php',
			data: dataparam,
			beforeSend:function()
			{
				showloadingalert("Loading, please wait.");
			},
			success: function(data)
			{
				closeloadingalert();
				showloadingalert("Added successfully.");
				setTimeout("closeloadingalert();",1000);
				var val = testid+","+0+","+$('#testnames').val();
				
				setTimeout('removesections("#test-testassign-actions");',500);
				setTimeout('showpages("test-testassign-assign","test/testassign/test-testassign-assign.php?id='+val+'");',500);
			}
		});
	}
}


function fn_showstudentlist(classid,testid,startdate)
{
	var dataparam = "oper=showstudentlists&classid="+classid+"&testid="+testid+"&startdate="+startdate;
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
			$('#studentlist').html(data);
			fn_hideshowassignbtn();
		}
	});
}


 function fn_hideshowassignbtn()
{
	   var list2 = [];
	   $("div[id^=list2_]").each(function(){
			list2.push($(this).attr('id').replace('list2_',''));
		});
	 if(list2=='')
	 {
		 $('#btnstep').addClass('dim');
	 }
	 else if(list2!='')
	 {
		 $('#btnstep').removeClass('dim');
	 }
}

/******This is for re-assign the test for student***************/
function fn_testreassign(testid,studentid,classid)
{
	if($("#frmreassign").validate().form())
	{
		var predate = $('#hidpredate').val();
		var dataparam = "oper=reassigntest&testid="+testid+"&classid="+classid+"&studentid="+studentid+"&startdate1="+$('#startdate1').val()+"&predate="+predate+"&enddate1="+$('#enddate1').val();
		$.ajax({
			type: "POST",
			url: 'test/testassign/test-testassign-addstudentsdb.php',
			data: dataparam,
			beforeSend:function()
			{
				showloadingalert("Loading, please wait.");
			},
			success: function(data)
			{
				closeloadingalert();
				showloadingalert("Re-Assign successfully.");
				setTimeout("closeloadingalert();",1000);
				 var val = testid+","+0+","+$('#testnames').val();
				
				setTimeout('removesections("#test-testassign-actions");',500);
				setTimeout('showpages("test-testassign-assign","test/testassign/test-testassign-assign.php?id='+val+'");',750);
			}
		});
	}
}
