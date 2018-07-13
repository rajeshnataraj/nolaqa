
/***********************/
/* Updated By Mohan M */
/***********************/


function fn_loadgrade(mtype)
{
	var dataparam = "oper=loadgrade&classid="+$('#hidclassid').val()+"&moduleid="+$('#moduleid').val()+"&scheduleid="+$('#hidscheduleid').val()+"&scheduletype="+$('#scheduletype').val()+"&mtype="+mtype;
	$.ajax({
		type	: "POST",
		cache	: false,
		url		: 'class/newclass/class-newclass-classajax.php',
		data	: dataparam,
		success : function(data) {
			$('#wcagrades').html(data);
		}
	});
	return false;
}

function fn_savewcagrade(classid,moduleid,scheduleid,scheduletype)
{
	var title = '';
	var point = '';
	var grade = [];
	$("label[id^=wca_]").each(function()
	{
		if(title=='')
		{
			title = $(this).html();
		}
		else
		{
			title = title+"~"+$(this).html();
		}
	});
	
	$("input[id^=point_]").each(function()
	{
		if(point=='')
		{
			point = $(this).val();
		}
		else
		{
			point = point+"~"+$(this).val();
		}
	});
	
	$("input[id^=grade_]").each(function()
	{
		var newid = $(this).attr('name');
		var cval=0;
		if($('#grade_'+newid).is(':checked')){
			cval=1;
		}
		grade.push(cval);
	});
	$('#hidpagetitle').val(title);
	$('#hidpoints').val(point);
	$('#hidgrades').val(grade);
	$.fancybox.close();	
	
}



/*----
    fn_movealllistitems(leftlist,rightlist,id,courseid,lid)
	Function to move from one list to another list
		leftlist - id of the draggable left/right list box
		rightlist - id of the draggable right/left list box
		id - type of call made 0 - move all, 1 - particular item
		courseid - id of the item moved if the type is 1
		lid - lesson id 
----*/
function fn_movealllistitems(leftlist,rightlist,id,courseid,lid)
{
	if(id == 0)
	{
		$("div[id^="+leftlist+"_]").each(function()
		{
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
		});

		if(courseid!=undefined && courseid!=0 && courseid!="rotational" && courseid!="exprotational" && courseid!="modexprotational" && courseid!="mission")
		{
			fn_loadcontent($('#hidscheduleid').val(),1);
		}
		if(lid!=undefined)
		{
		}
		
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
		if(courseid!=undefined && courseid!=0 && courseid!="rotational" && courseid!="exprotational" && courseid!="modexprotational" && courseid!="mission")
		{
			fn_loadcontent($('#hidscheduleid').val(),1);
		}
		if(lid!=undefined)
		{
		}			
	}
        
       
	
	if(courseid=="rotational" || $('#scheduletype').val()==2 || $('#scheduletype').val()==6)
	{
		fn_checking(rightlist);
                if(rightlist=='list4')
                {
                    fn_blockmodules();
                }
                if(rightlist=='list10')
                {
                    fn_blockstudent();
                }
                
		$('#exc'+id[0]).remove();
		var excflag=0;
		$("tr[id^=exc]").each(function()
		{
			excflag=1;
		});
		
		if(excflag==0 && $("#rotextendcontent tr").length==1)
		{
			$('#rotextendcontent').append('<tr><td colspan="2" >No records found</td></tr>');
		}
	}
        
        if($('#scheduletype').val()==17)
	{
                if(leftlist!=list9){
                    fn_showrubric(courseid);
                    fn_showschinlineass();
                }
		fn_checking(rightlist);
                if(rightlist=='list4')
                {
                    fn_blockexpeditions();
                }
                if(rightlist=='list10')
                {
                    fn_expblockstudent();
                }
        }
        
         if($('#scheduletype').val()==19 || courseid=="modexprotational")
	{
		var couname="modexprotational";
                
                if(leftlist!=list9){
                fn_showrubric(couname);
                    fn_showinlineass();
                }
                fn_modexpchecking();
                if(rightlist=='list4' || rightlist=='list2')
                {
                    fn_blockmodexpeditions();
                }
                if(rightlist=='list10')
                {
                    fn_modexpblockstudent();
                }
        }
        
      
        
        if(courseid=="mission" || $('#scheduletype').val()==20)
        {
            var couname="mission";
            if(leftlist!=list9){
                fn_showrubric(couname);
                fn_missionass();
            }
            fn_missionchecking(rightlist);
        }
        
	/* Student count is displayed*/
        var list1 = [];
	$("div[id^=list1_]").each(function(){
		list1.push($(this).attr('id').replace('list1_',''));
	});
	$('#leftmodexpdiv').html(list1.length);

	var list2 = [];
	$("div[id^=list2_]").each(function(){
		list2.push($(this).attr('id').replace('list2_',''));
	});
	$('#rightmodexpdiv').html(list2.length);
        
	var list3 = [];
	$("div[id^=list3_]").each(function(){
		list3.push($(this).attr('id').replace('list3_',''));
	});
	$('#leftmoddiv').html(list3.length);

	var list4 = [];
	$("div[id^=list4_]").each(function(){
		list4.push($(this).attr('id').replace('list4_',''));
	});
	$('#rightmoddiv').html(list4.length);
	
	
	
	var list9 = [];
	$("div[id^=list9_]").each(function(){
		list9.push($(this).attr('id').replace('list9_',''));
	});
	$('#nostudentleftdiv1').html(list9.length);

	var list10 = [];
	$("div[id^=list10_]").each(function(){
		list10.push($(this).attr('id').replace('list10_',''));
	});
	$('#nostudentrightdiv1').html(list10.length);
        
        var list25 = [];
	$("div[id^=list25_]").each(function(){
		list25.push($(this).attr('id').replace('list25_',''));
	});
	$('#blockstudentleftdiv').html(list25.length);

	var list26 = [];
	$("div[id^=list26_]").each(function(){
		list26.push($(this).attr('id').replace('list26_',''));
	});
	$('#blockstudentrightdiv').html(list26.length);
}

// Mission ASS Starts- karthi
function fn_missionass(){
    var list4 = [];
    $("div[id^=list4_]").each(function(){
            list4.push($(this).attr('id').replace('list4_',''));
    });
    $('#showmisass').hide();
    var schid = $('#scheduleid').val();
    var schlicenseid=$('#licenseid').val();
    var dataparam = "oper=showmisass"+"&misids="+list4+"&scheduleid="+schid+"&schlicenseid="+schlicenseid;
    $.ajax({
            type: 'post',
            url: 'class/newclass/class-newclass-mission-ajax.php',
            data: dataparam,
            beforeSend: function(){
            },
            success:function(data) 
            {
                $('#showmisass').show();
                $('#showmisass').html(data);
            }
    });
}
// Mission ASS ends

// Inline exp ASS Starts- karthi
function fn_showinlineass(){
    var list2 = [];
    $("div[id^=list2_]").each(function(){
            list2.push($(this).attr('id').replace('list2_',''));
    });
      $('#showinlineass').hide();
    var schid = $('#scheduleid').val();
    var schlicenseid=$('#licenseid').val();
    var dataparam = "oper=showinlineass"+"&expids="+list2+"&scheduleid="+schid+"&schlicenseid="+schlicenseid;
    //alert(dataparam);
    $.ajax({
            type: 'post',
            url: 'class/newclass/class-newclass-modexpedition-ajax.php',
            data: dataparam,
            beforeSend: function(){
            },
            success:function(data) 
            {
                $('#showinlineass').show();
                $('#showinlineass').html(data);
            }
    });
}

function fn_showschinlineass(){
    var list4 = [];
    $("div[id^=list4_]").each(function(){
            list4.push($(this).attr('id').replace('list4_',''));
    });
      $('#showschinlineass').hide();
    var schid = $('#scheduleid').val();
    var schlicenseid=$('#licenseid').val();
    var dataparam = "oper=showschinlineass"+"&expids="+list4+"&scheduleid="+schid+"&schlicenseid="+schlicenseid;
    //alert(dataparam);
    $.ajax({
            type: 'post',
            url: 'class/newclass/class-newclass-expedition-ajax.php',
            data: dataparam,
            beforeSend: function(){
            },
            success:function(data) 
            {
                    $('#showschinlineass').show();
                    $('#showschinlineass').html(data);
            
            }
    });
}
// Inline exp ASS Ends- karthi

/********Mohan M***********/
function fn_showrubric(courseid)
{
    if(courseid=="exprotational")
    {
            $('#rubriccontent').hide();
            var list4 = [];
            $("div[id^=list4_]").each(function(){
                    list4.push($(this).attr('id').replace('list4_',''));
            });
            var testschid=$('#scheduleid').val();
            var dataparam = "oper=showrubric"+"&list4="+list4+"&testschid="+testschid;
            $.ajax({
                    type: 'post',
                    url: 'class/newclass/class-newclass-expedition-ajax.php',
                    data: dataparam,
                    beforeSend: function(){
                    },
                    success:function(data) 
                    {
                            if(data!='fail')
                            {
                                    $('#rubriccontent').show();
                                    $('#rubriccontent').html(data);
                            }
                    }
            });
    }
    else if(courseid=='mission')
    {
        $('#rubriccontent').hide();
        var list4 = [];
        $("div[id^=list4_]").each(function(){
                list4.push($(this).attr('id').replace('list4_',''));
        });
        var dataparam = "oper=showrubric"+"&list4="+list4;
        $.ajax({
                type: 'post',
                url: 'class/newclass/class-newclass-mission-ajax.php',
                data: dataparam,
                beforeSend: function(){
                },
                success:function(data) 
                {
                        if(data!='fail')
                        {
                                $('#rubriccontent').show();
                                $('#rubriccontent').html(data);
                        }
                }
        });
    }
    else if(courseid=="modexprotational")
    {
            $('#rubriccontent').hide();
            var list2 = [];
            $("div[id^=list2_]").each(function(){
                    list2.push($(this).attr('id').replace('list2_',''));
            });
            var dataparam = "oper=showrubric"+"&list4="+list2;
            $.ajax({
                    type: 'post',
                    url: 'class/newclass/class-newclass-modexpedition-ajax.php',
                    data: dataparam,
                    beforeSend: function(){
                    },
                    success:function(data) 
                    {
                            if(data!='fail')
                            {
                                    $('#rubriccontent').show();
                                    $('#rubriccontent').html(data);
                            }
                    }
            });
    }
		
}
/********Mohan M***********/

/*---- Save Step - 3
    fn_teacherstudentidmaptoclass()
	Function to save teacher and student
----*/
function fn_teacherstudentidmaptoclass(classid,flag)
{
	var list1 = [];
	var list2 = [];
	var list3 = [];
	var list4 = [];
	
	$("div[id^=list1_]").each(function(){
		list1.push($(this).attr('id').replace('list1_',''));
	});
		
	$("div[id^=list2_]").each(function(){
		list2.push($(this).attr('id').replace('list2_',''));
	});
	
	$("div[id^=list3_]").each(function(){
		list3.push($(this).attr('id').replace('list3_',''));
	});
	
	$("div[id^=list4_]").each(function(){
		list4.push($(this).attr('id').replace('list4_',''));
	});
	
	
	if(list2=='')
	{
		showloadingalert("please select any teacher.");	 
		setTimeout('closeloadingalert()',2000);
		return false;
	}
	
	var dataparam = "oper=maptoclass"+"&classid="+$('#classid').val()+"&list1="+list1+"&list2="+list2+"&list3="+list3+"&list4="+list4;
	$.ajax({
		type: "POST",
		url: 'class/newclass/class-newclass-classajax.php',
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
			
			var val = classid;
			
			if(flag==1)
			{
				showloadingalert("Saved Successfully");
				setTimeout("closeloadingalert();",500);
				setTimeout("removesections('#class-newclass-actions');",1000);
			}
			else
			{
				if($('#hidflag').val()!=1)
					setTimeout("removesections('#class');",500);
				else
					setTimeout("removesections('#class-newclass-actions');",500);
					
					setTimeout('showpageswithpostmethod("class-newclass-steps","class/newclass/class-newclass-steps.php","id='+val+'");',500);
			}
		}
	});
}

/*---- Save Step - 1 & 2
    fn_next()
	Function to save a class
----*/
function fn_saveclass(classid,flag)
{
	var dataparam = '';         
            
	if($("#classform").validate().form())
	{       
		var counter=$('#counter').val();
		for(i=1;i<=(counter);i++)
		{			
			if($('#lettergrade' + i).val()=='')
			{
				$('#lettergrade' + i).focus();
				showloadingalert("Lettergrade is Required");	
				setTimeout('closeloadingalert()',2000);
				return false;
			}
			if($('#lettergrade' + i).val().match(/[^A-Z0-9-.]/i))
			{
				showloadingalert("Lettergrade cannot have special characters and space");	
				setTimeout('closeloadingalert()',2000);
				return false;
			}
                        
                        if($.isNumeric($('#lettergrade' + i).val()))
                        {
                            if($('#lettergrade' + i).val()>4)
                            {
                                showloadingalert("Lettergrade highest acceptable numeric value would be 4");	
				setTimeout('closeloadingalert()',2000);
				return false;
                            }
                        }
	
			if($('#lowerbound' + i).val()=='')
			{
				$('#lowerbound' + i).focus();
				showloadingalert("LowerBound is Required");	
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
	
		for(i=1; i<=counter; i++)
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
	
		if($('#grade').is(':checked')){
			var val=1;
		}	
		else{
			var val=0;
		}
                if($("#tempyes").is(':checked') && ($("#templateselection").css("display")!="none")){
                    var tempval=1;
                    var tempname = $("#tempname").val();     
                    if(tempname=='')
                    {
                            showloadingalert("Please enter template name");	
                            setTimeout('closeloadingalert()',2000);
                            return false;
                    }
                }
                else{
                   var tempval=0;
                }
                
	
		dataparam="oper=saveclass&tempname="+tempname+"&tempyes="+tempval+"&classid="+classid+"&lettergrade="+$('#lg').val()+"&lowerbound="+$('#lb').val()+"&higherbound="+$('#hb').val()+"&classname="+escapestr($('#classname').val())+"&sdate1="+$('#sdate1').val()+"&edate1="+$('#edate1').val()+"&period="+$('#period').val()+"&term="+$('#term').val()+"&shedule="+$('#shedule').val()+"&boxid="+$('#boxid').val()+"&grade="+val+"&remove="+$('#removecounter').val()+"&tags="+escapestr($('#form_tags_newclass').val());
		$.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-classajax.php',
			data: dataparam,		
			beforeSend: function(){
				showloadingalert("Saving, please wait.");	
			},
			success:function(ajaxdata) {
				var ajaxdata = ajaxdata.split("~");	
				if(ajaxdata[0]=='success'){
					$('.lb-content').html("Saved Successfully");
					setTimeout("closeloadingalert();",500);						
					var val = ajaxdata[1];					
					if(flag==1)
					{						
						setTimeout("removesections('#home');",500);
						setTimeout('showpageswithpostmethod("class","class/class.php");',1000);
					}
					else
					{
						if($('#hidflag').val()!=1)
							setTimeout("removesections('#class');",500);
						else
							setTimeout("removesections('#class-newclass-actions');",500);
							setTimeout('showpageswithpostmethod("class-newclass-steps","class/newclass/class-newclass-steps.php","id='+val+'");',500);
					}
				}				
				else if(ajaxdata[0]=="fail")
				{
					$('.lb-content').html("Incorrect Data");
					setTimeout('closeloadingalert()',1000);
				}	
			}
		});
	}
}

/*---- Save Step-6
    fn_saveclassreview()
	Function to save a class final step
----*/
function fn_saveclassreview(classid,stepid)
{	
	var dataparam = "oper=saveclassreview"+"&classid="+classid;
	$.ajax({
		type: 'post',
		url: 'class/newclass/class-newclass-classajax.php',
		data: dataparam,
		beforeSend: function(){
			showloadingalert("Loading, please wait.");	
		},
		success:function(data) {
			var data = data.split("~");	
			if(data[0]=="success")
			{
				$('#dialog-message .alert-message').html("Saved");
				closeloadingalert();	
				setTimeout("removesections('#home');",500);
				setTimeout('showpages("class-class","class/class.php");',500);
			}
		}
	});
}

/*----
    fn_deleteclass()
	Function to delete subject details
----*/
function fn_deleteclass(id)
{	
	var dataparam = "oper=deleteclass"+"&classid="+id;
	$.Zebra_Dialog('Are you sure you want to delete this class ?',
	{
		'type':     'confirmation',
		'buttons':  [
						{caption: 'No', callback: function() { }},
						{caption: 'Yes', callback: function() { 
							$.ajax({
								type: 'post',
								url: 'class/newclass/class-newclass-classajax.php',
								data: dataparam,	
								beforeSend: function(){
									showloadingalert("Checking, please wait.");	
								},		
								success:function(data) {		
									if(data=="success")
									{
										$('#dialog-message .alert-message').html("Class deleted successfully");
										closeloadingalert();					
										setTimeout("removesections('#home');",500);
										setTimeout('showpages("class-class","class/class.php");',500);
									}
								}
							});	
						}},
					]
	});
}

/*------------------ Sigmath schedule start  -----------------------*/
																
/*---- Save Step-1&2
    fn_next()
	Function to save a schedule
----*/
function fn_savesigmath(sid,scheduletypeid)
{
	var dataparam = '';	
	if($("#sform").validate().form())
	{	
		dataparam="oper=savesigmathinformation&sid="+sid+"&sname="+$('#sname').val()+"&sdate="+$('#sdate').val()+"&edate="+$('#edate').val()+"&classid="+$('#hidclassid').val();			
		$.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-sigmath-ajax.php',
			data: dataparam,		
			beforeSend: function(){
				showloadingalert("Saving, please wait.");	
			},
			success:function(ajaxdata) {
				var ajaxdata = ajaxdata.split("~");	
				if(ajaxdata[0]=='success'){	
					$('#dialog-message .alert-message').html("Saved");
					closeloadingalert();
					
					var val = ajaxdata[1];	
					var classid=$('#hidclassid').val();
					setTimeout("removesections('#class-newclass-calendar');",500);	
					setTimeout('showpages("class-newclass-schedulesteps","class/newclass/class-newclass-schedulesteps.php?id='+val+","+scheduletypeid+","+classid+'");',500);
				}
			}
		});
	}
}

function fn_loadcontent(sid,flag)
{
	var dataparam = '';	
	var courseid='';
	if(flag==1){
		var list2 = [];	
		$("div[id^=list2_]").each(function()
		{
			list2.push($(this).attr('id').replace('list2_',''));
		});		
	}
	var lid = $('#licenseid').val();		
	dataparam="oper=loadcontent&lid="+lid+"&sid="+sid+"&flag="+flag+"&courseids="+list2;		
	$.ajax({
		type: 'post',
		url: 'class/newclass/class-newclass-sigmath-ajax.php',
		data: dataparam,		
		beforeSend: function(){
			showloadingalert("Saving, please wait.");	
		},
		success:function(ajaxdata) {
			var ajaxdata = ajaxdata.split("~");				
			if(ajaxdata[1]==1){
				
				$('#units').hide();
				$('#ipls').hide();
				$('#courses').show();
				$('#courses').html(ajaxdata[0]);
				var list2 = [];	
				$("div[id^=list2_]").each(function()
				{
					list2.push($(this).attr('id').replace('list2_',''));
				});
				if(list2!=''){
					fn_loadcontent(sid,1);
				}
			}
			else{
				if(flag!=1){
					$('#courses').hide();
					$('#ipls').hide();
				}
				$('#units').show();
				$('#units').html(ajaxdata[0]);
			}
			closeloadingalert();
		}
	});	
}



function fn_btnchedk()
{
	var list6 = [];
	$("div[id^=list6_]").each(function()
	{
		list6.push($(this).attr('id').replace('list6_',''));
	});
	if(list6!=''){
		$('#cont_nxtstep').show();
	}
	else{
		$('#cont_nxtstep').hide();
	}
}
/*---- Save Step-4
    fn_savecontent()
	Function to save course, modules, units, lessons, activities
----*/
function fn_savecontent(sid,scheduletypeid)
{
	var list1 = [];
	var list2 = [];
	var list3 = [];
	var list4 = [];
	var list5 = [];
	var list6 = [];
	var list7 = [];
	var list8 = [];
	
	licenseid=$('#licenseid').val();
	
	if($('#hidcontenttype').val()==1){
		var list = document.getElementById ("list1");
		var liTags = list.getElementsByTagName ("div");
		for (var i = 0; i < liTags.length; i++) {
			list1.push((liTags[i].id).replace('list1_',''));
		}		
		
		$("div[id^=list2_]").each(function()
		{
			list2.push($(this).attr('id').replace('list2_',''));
		});
	}
	
	var list = document.getElementById ("list3");
	var liTags = list.getElementsByTagName ("div");
	for (var i = 0; i < liTags.length; i++) {
		list3.push((liTags[i].id).replace('list3_',''));
	}		
	
	$("div[id^=list4_]").each(function()
	{
		list4.push($(this).attr('id').replace('list4_',''));
	});		
		
	var list = document.getElementById ("list5");
	var liTags = list.getElementsByTagName ("div");
	for (var i = 0; i < liTags.length; i++) {
		list5.push((liTags[i].id).replace('list5_',''));
	}		
	
	$("div[id^=list6_]").each(function()
	{
		list6.push($(this).attr('id').replace('list6_',''));
	});

	var list = document.getElementById ("list7");
	var liTags = list.getElementsByTagName ("div");
	for (var i = 0; i < liTags.length; i++) {
		list7.push((liTags[i].id).replace('list7_',''));
	}		
	
	$("div[id^=list8_]").each(function()
	{
		list8.push($(this).attr('id').replace('list8_',''));
	});
	
	var dataparam = "oper=savecontent"+"&sid="+sid+"&list1="+list1+"&list2="+list2+"&list3="+list3+"&list4="+list4+"&list5="+list5+"&list6="+list6+"&list7="+list7+"&list8="+list8+"&licenseid="+licenseid;
	$.ajax({
		type: 'post',
		url: "class/newclass/class-newclass-sigmath-ajax.php",
		data: dataparam,		
		beforeSend: function(){
			showloadingalert("Saving, please wait.");	
		},
		success:function(data) {		
			if(data=="success")
			{					
				$('#dialog-message .alert-message').html("saved");
				closeloadingalert();						
				var classid=$('#hidclassid').val();	
				setTimeout("removesections('#class-newclass-calendar');",500);	
				setTimeout('showpages("class-newclass-schedulesteps","class/newclass/class-newclass-schedulesteps.php?id='+sid+","+scheduletypeid+","+classid+'");',500);					
			}
		}
	});		
}

function fn_savesigmathstudent(sid,scheduletypeid)
{
	var list9 = [];	
	var list10 = [];
	var list = document.getElementById ("list9");
	var liTags = list.getElementsByTagName ("div");
	for (var i = 0; i < liTags.length; i++) {
		list9.push((liTags[i].id).replace('list9_',''));
	}		
	
	$("div[id^=list10_]").each(function()
	{
		list10.push($(this).attr('id').replace('list10_',''));
	});
	
	if(list10 == ''){
		alert("Please select alteast one student");	
		return false;
	}
	
	var dataparam = "oper=savesigmathstudent"+"&sid="+sid+"&list9="+list9+"&list10="+list10;
	$.ajax({
		type: 'post',
		url: "class/newclass/class-newclass-sigmath-ajax.php",
		data: dataparam,		
		beforeSend: function(){
			showloadingalert("Saving, please wait.");	
		},
		success:function(data) {		
			if(data=="success")
			{					
				$('#dialog-message .alert-message').html("saved");
				closeloadingalert();						
				var classid=$('#hidclassid').val();	
				setTimeout("removesections('#class-newclass-calendar');",500);	
				setTimeout('showpages("class-newclass-schedulesteps","class/newclass/class-newclass-schedulesteps.php?id='+sid+","+scheduletypeid+","+classid+'");',500);	
			}
			else if(data=="fail"){
				alert("Student limit exceed....");
				closeloadingalert();
			}
		}
	});
}


function fn_changeshedule(sid)
{
	var id = $('#scheduletype').val();	
	if(id==1)
	{
		var oper="sigmathform";
		var url="class/newclass/class-newclass-sigmath-ajax.php";
	}
	else if(id==2)
	{
		var oper="rotationform";
		var url="class/newclass/class-newclass-rotation-ajax.php";	
	}
	else if(id==3)
	{
		var oper="dyadform";
		var url="class/newclass/class-newclass-dyad-ajax.php";	
	}
	else
	{
		var oper="triadform";
		var url="class/newclass/class-newclass-triad-ajax.php";	
	}
	
	var dataparam = "oper="+oper+"&sid="+sid;
	$.ajax({
		type: 'post',
		url: url,
		data: dataparam,		
		beforeSend: function(){
			showloadingalert("Loading, please wait.");	
		},
		success:function(data) {
			closeloadingalert();
			$('#scheduleform').html(data);
		}
	});			
}

function fn_reviewclass(id){	
	setTimeout("removesections('#class-newclass-steps');",500);			
	setTimeout('showpageswithpostmethod("class-newclass-review","class/newclass/class-newclass-review.php","id='+id+'");',500);
}

function fn_loadinstructions(sid){
	var dataparam = "oper=loadinstructions&id="+$('#scheduletype').val()+"&classid="+$('#hidclassid').val()+"&sid="+sid;
	if($('#scheduletype').val()!=1){
		$.ajax({
			type: 'post',
			url: "class/newclass/class-newclass-sigmath-ajax.php",
			data: dataparam,
			success:function(data) {
				$('#instructionstages').html(data);
				removesections('#class-newclass-newschedulestep');
			}
		});
	}
}

function fn_loadlicensecontent(sid){

	if($('#scheduletype').val()==1){
		fn_sigmathloadcontent($('#licenseid').val(),sid);	
	}
}


function fn_saveschedule(id){
	if(id==0)
		var msg = "Saving,";
	else
		var msg = "Updating,";
	if($("#scheduleform").validate().form())
	{
		var studenttype = $('#studenttype').val();
		var list3=[];
		var list4=[];
		var list5=[];
		var list6=[];
		var list7=[];
		var list8=[];
		var list9=[];
		var list10=[];
		var extids = [];
		var gradepoint=[];
		var gradeflag=[];
                 //Math Connection
                var mgradepoint=[];
		var mgradeflag=[];
                var miplflag=[];
                var iplflag=[];//New line
		var mgrade=[];
                var  mlessid=[];
                var  unitid=[];
                var diagtestflag=[];
                var unitdiagtestflag=[];
                
		if(studenttype==2){
			$("div[id^=list10_]").each(function()
			{
				list10.push($(this).attr('id').replace('list10_',''));
			});
			$("div[id^=list9_]").each(function()
			{
				list9.push($(this).attr('id').replace('list9_',''));
			});
			if(list10==''){
				alert("Please select any one of student for schedule.");
				return false;
			}
		}
		$("div[id^=list3_]").each(function()
		{
			list3.push($(this).attr('id').replace('list3_',''));
		});
		$("div[id^=list4_]").each(function()
		{
			list4.push($(this).attr('id').replace('list4_',''));
		});
		$("div[id^=list5_]").each(function()
		{
			list5.push($(this).attr('id').replace('list5_',''));
		});
				
		$('#selectipl input[id^=ipl_]').each(function() { //new line
			var lessonid=$(this).val();
			list6.push(lessonid);
			if($('#grade_'+lessonid).is(':checked')){
				var tmpgrade=1;
			}
			else{
				var tmpgrade=0;
			}
                        if($('#ipl_'+lessonid).is(':checked')){//new line
				var tmpiplflag=1;
			}
			else{
				var tmpiplflag=0;
			} //new line
			gradepoint.push($('#gradevalue_'+lessonid).val());
			gradeflag.push(tmpgrade);      
                        iplflag.push(tmpiplflag);//new line
		});	
                //Math Connection
                $('#selectipl input[id^=mipl_]').each(function() { 
			var mlessonid=$(this).val();
			mlessid.push(mlessonid);
			if($('#mgrade_'+mlessonid).is(':checked')){
				var tmpgrade=1;
			}
			else{
				var tmpgrade=0;
			} 
                        if($('#mipl_'+mlessonid).is(':checked')){
				var tmpgrade1=1;
			}
			else{
				var tmpgrade1=0;
			} 
			mgradepoint.push($('#mgradevalue_'+mlessonid).val());
			mgradeflag.push(tmpgrade);      
                        miplflag.push(tmpgrade1);
		});
                
                $('#selectipl input[id^=unitsel_]').each(function() { 
			var munitid=$(this).val();
                        
                        if($('#unitsel_'+munitid).is(':checked')){
				var unitdiagflag=1;
			}
			else{
				var unitdiagflag=0;
			} 
                        
                        var unitdiagval=munitid+"~"+unitdiagflag;
                      
                        unitdiagtestflag.push(unitdiagval);
		});
                
                
                
                $('#selectipl input[id^=diagtest_]').each(function() { 
			var mlessonid=$(this).val();
                        
                        var diaglessonid=mlessonid.split("~");
			
			if($('#diagtest_'+diaglessonid[0]+"_"+diaglessonid[1]).is(':checked')){
				var diagflag=1;
			}
			else{
				var diagflag=0;
			} 
                        
                        var diagval=diaglessonid[0]+"~"+diagflag;
                      
                        diagtestflag.push(diagval);
		});
                
                $("input[id^=unit_]").each(function()
		{
			unitid.push($(this).attr('id').replace('unit_',''));
		});
                
               //Math Connection end
               
		$("div[id^=list7_]").each(function()
		{
			list7.push($(this).attr('id').replace('list7_',''));
		});
		$("div[id^=list8_]").each(function()
		{
			list8.push($(this).attr('id').replace('list8_',''));
		});
                if(list4==''){
			alert("Please select any unit.");
			return false;
		}
		else if(list6==''){
			alert("Please select any lesson.");
			return false;
		}
                
		$("input[id^=exid_]").each(function()
		{
		   extids.push($(this).val());
		});

		var sname = escapestr($('#sname').val());
              
                var dataparam = "oper=saveschedule&sid="+id+"&sname="+escapestr($('#sname').val())+"&startdate="+$('#startdate').val()+"&stype="+$('#scheduletype').val()+"&students="+list10+"&studenttype="+studenttype+"&classid="+$('#hidclassid').val()+"&list4="+list4+"&list6="+list6+"&list8="+list8+"&list3="+list3+"&list5="+list5+"&list7="+list7+"&licenseid="+$('#licenseid').val()+"&unstudents="+list9+"&gradeflag="+gradeflag+"&gradepoint="+gradepoint+"&mgradeflag="+mgradeflag+"&mgradepoint="+mgradepoint+"&munitid="+unitid+"&mlessid="+mlessid+"&extids="+extids+"&miplflag="+miplflag+"&iplflag="+iplflag+"&unitdiagflag="+unitdiagtestflag+"&lessondiagflag="+diagtestflag;	//new line						
		$.ajax({
			type:'post',
			url:'class/newclass/class-newclass-sigmath-ajax.php',
			data:dataparam,
			beforeSend: function(){
				showloadingalert(msg+" please wait.");	
			},	
			success: function(data){
				var data=data.split("~");
				if(data[0]=='success'){	
					$('.lb-content').html("Schedule saved successfully.");					
					removesections('#class-newclass-steps');
					removesections('#class-newclass-actions');						 		
					setTimeout('showpageswithpostmethod("class-newclass-scheduledetails","class/newclass/class-newclass-scheduledetails.php","id='+$('#hidclassid').val()+","+$('#classtypeval').val()+'");',200);
					setTimeout('showpageswithpostmethod("class-newclass-viewprogress","class/newclass/class-newclass-viewprogress.php","id='+data[1]+","+$('#hidclassid').val()+'");',2000);					
				}
				else if(data[0]=='fail'){
					$('.lb-content').html("student limit exceeds");					
				}
				else if(data[0]=='invalid'){
					$('.lb-content').html("Incorrect Data");					
				}
				setTimeout('closeloadingalert();',2000);	
			}
		});
	}	
}


function fn_sigmathloadcontent(lid,sid,flag)
{
	$('#schenddate').hide();			
	if(sid==0 || sid=='') //show the select template dropdown is when create new schecule
	{
		$('#stemplate').show();
		$('#triadtemplate,#dyadtemplate').hide();		
	}
		
	if(flag==1)//comes from  select template
		sid = $('#stemplateid').val();
					
	dataparam="oper=loadcontent&lid="+lid+"&sid="+sid+"&flag="+flag;	
	$.ajax({
		type: 'post',
		url: 'class/newclass/class-newclass-sigmath-ajax.php',
		data: dataparam,		
		beforeSend: function(){
		},
		success:function(ajaxdata) {
			$('#units').html(ajaxdata);	
			fn_orderipls(sid,lid,flag);		
		}
	});	
}


function fn_loadscheduletemplate(lid)
{
	$('#schenddate,#triadtemplate,#dyadtemplate,#stemplate').hide();
	$('#rotcontent,#units,#ipls,#instructionstages').html('');
	        
        /* scroll down using codeing created by chandru start line*/
        var type1=$('#scrollhid').val();
        /* scroll down using codeing end line*/
        
	var dataparam = "oper=loadscheduletemplate&licenseid="+$('#licenseid').val()+"&sid="+$('#hidscheduleid').val()+"&scheduletype="+$('#hidscheduletype').val()+"&type1="+$('#scrollhid').val();
	var trackid = $('#lic_'+$('#licenseid').val()).attr('name');	
	$.ajax({
		type: 'post',
		url: 'class/newclass/class-newclass-sigmath-ajax.php',
		data: dataparam,
		beforeSend: function(){
		},
		success: function (data) {	
                        /* scroll down using codeing created by chandru start line*/
                        if(type1=='0')
                        {
                            $('html, body').animate({
                              scrollTop: '+=325'
                            }, 800);
                        }
                        $('#scrollhid').val(1);
                        /* scroll down using codeing end line*/
                        
			$('#loadtemplate').html(data);
			fn_showavailable(trackid);					
		},
	});	
}

function fn_showavailable(trackid){
	var dataparam = "oper=showremainingusers&trackid="+trackid+"&licenseid="+$('#licenseid').val();
	$.ajax({
		type: 'post',
		url: 'class/newclass/class-newclass-sigmath-ajax.php',
		data: dataparam,
		beforeSend: function(){			
		},
		success: function (data) {	
			$('#remainusers').html(data);				
		},
	});	
}

function fn_indassesment(lid,sid)
{
        /* scroll down using codeing created by chandru start line*/
        var type2=$('#scrollhid1').val();
        /* scroll down using codeing end line*/
        
	$('#schenddate').show();		
	dataparam="oper=indloadcontent&lid="+lid+"&sid="+sid+"&classid="+$('#hidclassid').val();		
	$.ajax({
		type: 'post',
		url: 'class/newclass/class-newclass-classajax.php',
		data: dataparam,		
		beforeSend: function(){
		},
		success:function(ajaxdata) {
                        /* scroll down using codeing created by chandru start line*/
                        if(type2=='0')
                        {
                            $('html, body').animate({
                              scrollTop: '+=400'
                            }, 1000);
                        }
                        $('#scrollhid1').val(1);
                        /* scroll down using codeing end line*/
                        
			$('#rotcontent').html(ajaxdata);	
		}
	});	
}

function fn_indasloadmodules(scheduleid)
{
	var lid = $('#licenseid').val();		
        
        /* scroll down using codeing created by chandru start line*/
        var moduletype= $('#moduletype').val();
        var type3=$('#scrollhid2').val();
        /* scroll down using codeing created by chandru start line*/
        
	dataparam="oper=indasloadmodules&licenseid="+lid+"&scheduleid="+scheduleid+"&moduletype="+$('#moduletype').val();	 	
	$.ajax({
		type: 'post',
		url: 'class/newclass/class-newclass-classajax.php',
		data: dataparam,		
		beforeSend: function(){
		},
		success:function(ajaxdata) {
			
                        /* scroll down using codeing created by chandru start line*/
                        if(type3=='0' && (moduletype=='1' || moduletype=='2' || moduletype=='7' || moduletype=='17'))
                        {
                            $('html, body').animate({
                              scrollTop: '+=400'
                            }, 1000);
                        }
                        $('#scrollhid2').val(1);
                        /* scroll down using codeing end line*/
                        
			$('#modules').html(ajaxdata);
			$('#modnxtstep').show();
			if(scheduleid!=0)
			{
				fn_rotloadextendcontentwca(scheduleid,lid);
			}
		}
	});
} 

function fn_saveindassesment(sid)
{
	var title = '';
	var point = '';
	var wcasess = '';
	var wcapage = '';
	var grade = [];
        var modtype=$('#moduletype').val();
	if($("#scheduleform").validate().form())
	{
		var list10 = [];
		var list9=[];
		
		$("div[id^=list9_]").each(function()
		{
			list9.push($(this).attr('id').replace('list9_',''));
		});
		
		$("div[id^=list10_]").each(function()
		{
			list10.push($(this).attr('id').replace('list10_',''));
		});
		
	
		if(list10=='' && $('#studenttype').val()==2)
		{
			$.Zebra_Dialog('<strong>Please select a student</strong>', {
			'buttons':  false,
			'auto_close': 3000
			});
			return false;
		}
		if($('#moduleid').val()==''){
			$.Zebra_Dialog('<strong>Please select a module</strong>', {
			'buttons':  false,
			'auto_close': 3000
			});
			return false;
		}
		
		$("label[id^=wca_]").each(function()
		{
			if(title=='')
			{
				title = encodeURIComponent($(this).html());
			}
			else
			{
				title = title+"~"+encodeURIComponent($(this).html());
			}
			var wcaids = $(this).attr('id').split('#');
			if(wcasess=='')
			{
				wcasess = wcaids[1];
				wcapage = wcaids[2];
			}
			else
			{
				wcasess = wcasess+"~"+wcaids[1];
				wcapage = wcapage+"~"+wcaids[2];
			}
		});
		
		$("input[id^=point_]").each(function()
		{
			if(point=='')
			{
				point = $(this).val();
			}
			else
			{
				point = point+"~"+$(this).val();
			}
		});
		
		$("input[id^=grade_]").each(function()
		{
			var newid = $(this).attr('name');
			var cval=0;
			if($('#grade_'+newid).is(':checked')){
				cval=1;
			}
			grade.push(cval);
		});
	
		var sname = escapestr($('#sname').val());		
		var dataparam="oper=saveindassesment&sname="+sname+"&startdate="+$('#startdate').val()+"&enddate="+$('#enddate').val()+"&scheduletype="+$('#scheduletype').val()+"&studenttype="+$('#studenttype').val()+"&sid="+sid+"&students="+list10+"&modules="+$('#moduleid').val()+"&classid="+$('#hidclassid').val()+"&licenseid="+$('#licenseid').val()+"&moduletype="+$('#moduletype').val()+"&unstudents="+list9+"&pagetitle="+title+"&points="+point+"&grades="+grade+"&wcasess="+wcasess+"&wcapage="+wcapage+"&extid="+$('#exid').val();
		$.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-classajax.php',
			data: dataparam,		
			beforeSend: function(){
				showloadingalert("Loading, please wait.");
			},
			success:function(data) {
				closeloadingalert();
				var data=data.split("~");
				$('#scheduleid').val(data[1]);
				var sid=$('#scheduleid').val();
				var classid=$('#hidclassid').val();								
				if(trim(data[0])=="success")
				{
					$('.lb-content').html("Saved Successfully");
					removesections('#class-newclass-steps');	
					removesections('#class-newclass-actions');			
					setTimeout('showpageswithpostmethod("class-newclass-scheduledetails","class/newclass/class-newclass-scheduledetails.php","id='+$('#hidclassid').val()+","+$('#classtypeval').val()+'");',100);
                                        if(modtype!=17){	
					setTimeout('showpageswithpostmethod("class-newclass-viewindprogress","class/newclass/class-newclass-viewindprogress.php","id='+data[1]+",5,"+$('#hidclassid').val()+'");',2500);
                                        }
                                        else{
                                            setTimeout('showpageswithpostmethod("class-newclass-viewindprogress","class/newclass/class-newclass-viewindprogress.php","id='+data[1]+",17,"+$('#hidclassid').val()+'");',2500);
				   
				}
				}
				else if(trim(data[0])=="fail")
				{
					$('.lb-content').html("Student limit exceed");					
				}
				else if(trim(data[0])=="invalid")
				{
					$('.lb-content').html("Invalid data");	
				}
			}
		});
	}
}

function fn_loadmodule(){
	$('#schenddate').hide();
	var stype = $('#scheduletype').val();
	var lid = $('#licenseid').val();
	var sid = $('#hidscheduletype').val();
	
	$('#rotcontent').html('');
	$('#units').html('');
	$('#ipls').html('');
	$('#rotcontent').html('');
	$('#instructionstages').html('');
	$('#tlab').hide('');
	$('#stemplate').hide();
	$('#triadtemplate').hide();
	$('#dyadtemplate').hide();
	
	removesections('#class-newclass-newschedulestep');
	
	if(stype==1)
	{
		fn_sigmathloadcontent(lid,sid);
	}
	else if(stype==2)
	{
		$('#schenddate').hide();
		fn_rotloadcontent(lid,sid,1);
	}
	else if(stype==6)
	{
		$('#schenddate').hide();
		fn_rotloadcontent(lid,sid,2);
	}
	else if(stype==3)
	{
		$('#schenddate').hide();
		$('#stemplate').hide();
		$('#triadtemplate').hide();
		$('#dyadtemplate').show();
		fn_dyadstage(sid,'ins',0);
	
	}
	else if(stype==4)
	{
		$('#schenddate').hide();
		$('#stemplate').hide();
		$('#triadtemplate').show();
		$('#dyadtemplate').hide();
		fn_triadstage(sid,'ins',0);
	}
	else if(stype==5)
	{
		$('#stemplate').hide();
		$('#triadtemplate').hide();
		$('#dyadtemplate').hide();
		fn_indassesment(lid,sid);
	}
	else if(stype==15)
	{
		$('#stemplate').hide();
		$('#triadtemplate').hide();
		$('#dyadtemplate').hide();
		fn_indassesmentexpedition(lid,sid);
	}
        else if(stype==16)//pd by mm
	{
		fn_pdloadcontent(lid,sid);
        }
        else if(stype==17)
	{
		$('#stemplate').hide();
		$('#triadtemplate').hide();
		$('#dyadtemplate').hide();
		fn_exploadcontent(lid,sid);
	}
        else if(stype==18)
	{
		$('#stemplate').hide();
		$('#triadtemplate').hide();
		$('#dyadtemplate').hide();
		fn_missionassesment(lid,sid);
	}
        else if(stype==19)
	{
		$('#stemplate').hide();
		$('#triadtemplate').hide();
		$('#dyadtemplate').hide();
		fn_modexploadcontent(lid,sid);
	}
        else if(stype==20)
	{
		$('#stemplate').hide();
		$('#triadtemplate').hide();
		$('#dyadtemplate').hide();
		fn_missionloadcontent(lid,sid);
	}
        //pd by mm
}

function fn_classlock(id)
{
	if(trim($('#clockcontnet').html())=='unlock'){
		var flag=1;	
		var msg = 'Are you sure you want to lock this class? If you lock this class the student cannot access their assignments.';	
		$('#classloka').show();
	}
	else if(trim($('#clockcontnet').html())=='Lock'){
		var flag=0;	
		var msg = 'Are you sure you want to unlock this class? ';	
	}	
	var dataparam="oper=classlock&classid="+id+"&flag="+flag;	
	$.Zebra_Dialog(msg,
	{
		'type':     'confirmation',
		'buttons':  [
						{caption: 'No', callback: function() { }},
						{caption: 'Yes', callback: function() { 
							$.ajax({
								type: 'post',
								url: 'class/newclass/class-newclass-classajax.php',
								data: dataparam,	
								beforeSend: function(){
									showloadingalert("Loading, please wait.");	
								},		
								success:function(data) {		
									closeloadingalert();
									if(flag==1){
										$('#classlock').attr('class','icon-synergy-locked');
										$('#clockcontnet').html('Lock');
									}
									else if(flag==0){
										$('#classlock').attr('class','icon-synergy-unlocked');
										$('#clockcontnet').html('unlock');
									}
								}
							});	
						}},
					]
	});
}

function fn_addstudent(){
	$.fancybox.showActivity();
	$.ajax({
		type	: "POST",
		cache	: false,
		url		: "class/newclass/class-newclass-classajax.php",
		data	: 'oper=createstudentform',	
		success: function(data) {			
			$.fancybox(data,{'width':450});
		}
	});
	return false;
}

function fn_createstudent(flag){
	if($("#studentform").validate().form())
	{
		var dataparam="oper=savestudent&fname="+escapestr($('#fname').val())+"&lname="+escapestr($('#lname').val())+"&uname="+escapestr($('#uname').val())+"&password="+$('#password').val()+"&grade="+$('#grade').val();		 	
		$.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-classajax.php',
			data: dataparam,		
			beforeSend: function(){
			},
			success:function(ajaxdata) {
				ajaxdata = ajaxdata.split('~');
				if(trim(ajaxdata[0])=='success'){
					$('#list3').append(ajaxdata[1]);
					$.Zebra_Dialog('<strong>Student was added successfully.</strong>', {
					'buttons':  false,
					'auto_close': 3000
					});	
					$.fancybox.close();
					if(flag==1)
					setTimeout('fn_addstudent()',3100);
				}
				else{
					$.Zebra_Dialog('<strong>Student creation failed.</strong>', {
					'buttons':  false,
					'auto_close': 3000
					});		
				}			
				
			}
		});
	}
}

function fn_finishclass(id)
{
	removesections('#home');
	showpages("class-class","class/class.php");
	setTimeout('showpageswithpostmethod("class-newclass-actions","class/newclass/class-newclass-actions.php","id='+id+'");',2000);
}

function fn_changeeventdate(type,sid,date,rotation,enddate,stageid,rottype)
{
	dataparam="oper=changeeventdate&type="+type+"&sid="+sid+"&date="+date+"&rotation="+rotation+"&enddate="+enddate+"&stageid="+stageid+"&rottype="+rottype;
	
	$.ajax({
		type: 'post',
		url: 'class/newclass/class-newclass-classajax.php',
		data: dataparam,		
		beforeSend: function(){
			showloadingalert("Loading, please wait.");	
		},
		success:function(ajaxdata) {
				
			closeloadingalert();
			if(ajaxdata=="fail")
			{
				$.Zebra_Dialog('Students already attend this rotation so you can not modify the date.');
			}
			else
			{
			$.Zebra_Dialog('Event date has been changed.', {
					'buttons':  false,
					'auto_close': 2000
					});	
			var data=$('#hidclassid').val();
			removesections('#class-newclass-actions');			
			setTimeout('showpages("class-newclass-calendar","class/newclass/class-newclass-calendar.php?id='+data+",1,"+date+'");',1000);
			}
		}
	});
}

function fn_orderipls(sid,lid,flag)
{	
        var list4 = [];	
	$("div[id^=list4_]").each(function()
	{
		list4.push($(this).attr('id').replace('list4_',''));
	});
	
	dataparam="oper=loadorderipl&sid="+sid+"&unitids="+list4+"&lid="+lid+"&flag="+flag;		
	$.ajax({
		type: 'post',
		url: 'class/newclass/class-newclass-sigmath-ajax.php',
		data: dataparam,		
		beforeSend: function(){
		},
		success:function(ajaxdata) {				
			$('#ipls').html(ajaxdata);
		}
	});
}

function fn_rotloadextendcontentwca(scheduleid,licenseid)
{
	if($('#moduleid').val()==''){
		alert("Please select any module");
		return false;
	}
	
	var dataparam = "oper=loadextendcontent&scheduleid="+scheduleid+"&licenseid="+licenseid+"&moduletype="+$('#moduletype').val()+"&moduleid="+$('#moduleid').val();	
	
	$.ajax({
		type: 'post',

		url: "class/newclass/class-newclass-classajax.php",
		data: dataparam,
		beforeSend: function(){			
		},
		success:function(data) {
			$('#extendcontent').html(data);
		}
		
	});	
}

/* Expedition start */
function fn_indassesmentexpedition(lid,sid)
{
        /* scroll down using codeing created by chandru start*/
        var type4=$('#scrollhid1').val();
        /* scroll down using codeing end*/
        
	$('#schenddate').show();
	var dataparam="oper=expeditionloadcontent&licenseid="+lid+"&scheduleid="+sid+"&classid="+$('#hidclassid').val();
        $.ajax({
            type: 'post',
            url: 'class/newclass/class-newclass-classajax.php',
            data: dataparam,
            success:function(ajaxdata)
            {
                $('#rotcontent').html(ajaxdata);

                /* scroll down using codeing created by chandru start line*/
                if(type4=='0')
                {
                    $('html, body').animate({
                      scrollTop: '+=400'
                    }, 1000);
                }
                $('#scrollhid1').val(1);
                /* scroll down using codeing end line*/

                if(sid!=0)
                {
                    fn_showexpeditionsetting(lid);
                }
            }
	});
}


/* Mission start */
           
function fn_missionassesment(lid,sid)
{
	$('#schenddate').show();
	dataparam="oper=missionloadcontent&licenseid="+lid+"&scheduleid="+sid+"&classid="+$('#hidclassid').val();
	$.ajax({
	type: 'post',
	url: 'class/newclass/class-newclass-classajax.php',
	data: dataparam,
	success:function(ajaxdata) {
	$('#rotcontent').html(ajaxdata);
	if(sid!=0)
	{
	fn_showmissionsetting(lid);
	}
	}
	});
}

function fn_showexpeditionsetting(lid)
{
    
	var dataparam="oper=expsetting&scheduleid="+$('#hidscheduleid').val()+"&expid="+$('#expid').val()+"&licenseid="+lid;
        //alert(dataparam);
	$.ajax({
	type: 'post',
	url: 'class/newclass/class-newclass-classajax.php',
	data: dataparam,
	beforeSend: function(){
	showloadingalert("Loading, please wait.");
	},
	success:function(ajaxdata) {
	closeloadingalert();
	$('#expsetting').html(ajaxdata);
	}
	});

}
function fn_showmissionsetting(lid)
{
	dataparam="oper=missionsetting&scheduleid="+$('#hidscheduleid').val()+"&missionid="+$('#misionid').val()+"&licenseid="+lid;
	$.ajax({
	type: 'post',
	url: 'class/newclass/class-newclass-classajax.php',
	data: dataparam,
	beforeSend: function(){
	showloadingalert("Loading, please wait.");
	},
	success:function(ajaxdata) {
	closeloadingalert();
	$('#missionsetting').html(ajaxdata);
	}
	});

}
function fn_saveindassesmentexpedition(sid)
{

	if($("#scheduleform").validate().form())
	{
		var list10 = [];
		var list9=[];
                /* added by chandru start line */
                var extids = [];
                var expids = [];
		var selectallexpids=[];
                /* expedition code added by chandru end line */
		
		$("div[id^=list9_]").each(function()
		{
		list9.push($(this).attr('id').replace('list9_',''));
		});
		
		$("div[id^=list10_]").each(function()
		{
		list10.push($(this).attr('id').replace('list10_',''));
		});
		
		if(list10=='' && $('#studenttype').val()==2)
		{
		$.Zebra_Dialog('<strong>Please select a student</strong>', {
		'buttons': false,
		'auto_close': 3000
		});
		return false;
		}
		
		if($('#expid').val()==''){
		$.Zebra_Dialog('<strong>Please select anyone Title</strong>', {
		'buttons': false,
		'auto_close': 3000
		});
		return false;
		}
                
                /* expedition code added by chandru start line */
                $("input[id^=exid1_]").each(function()
		{
			extids.push($(this).val());
		});
                /* added by chandru start line */
                
                 /***********Chandru Updated by [18-12-2015] one or more Extend Content option code start here*********/
                    $("input[id^=mod_]").each(function()
                    {
                            var mlessonid=$(this).attr('name').replace('mod_','');
                            if($('#mod_'+mlessonid).is(':checked')){
                                expids.push(mlessonid);
                                
                            }
                    });
                    
                    $("input[id^=selectallexp_]").each(function()
                    {
                            var mlessonid1=$(this).attr('id').replace('selectallexp_','');
                             var existmod=$('#selectallexp_'+mlessonid1).val();
                             if(existmod=='01'){
                                var selallexpids=$(this).attr('name').replace('selectallexp_','')+"~";
                                selectallexpids.push(selallexpids);
                             }
                    });
               /***********Chandru Updated by [18-12-2015] one or more Extend Content option code start here*********/
		/**************MOhan M ***********/
			var selectchkboxids=[];
			$("input[id^=chkboxrubric_]").each(function()
			{
				var rubricid=$(this).attr('id').replace('chkboxrubric_','');
				var exppid=$('#expid').val(); 
				if($("#chkboxrubric_"+rubricid).is(':checked'))
				{
					var chkteacher = 1;
					var rubricval=rubricid+"~"+exppid;
					selectchkboxids.push(rubricval);
				}
			});
		/**************MOhan M ***********/
		
		
                // Exp Inline test save and Edit Starts- By Karthi 
                var exppre='';  
                var exptest='';
				
                $("input[name^=exppre_]").each(function(e)
                {
                    if(exppre=='')
                        {
                            exppre = $(this).attr('name').replace('exppre_','');
                            exppre = exppre+"_"+$(this).val();              
                            exptest = exppre+"_"+$("#exppost_"+e).val();
                        }
                    else
                        {
                        exppre = $(this).attr('name').replace('exppre_','');
                        exptest = exptest+"~"+exppre+"_"+$(this).val()+"_"+$("#exppost_"+e).val();	
                        }

                });
                 //alert("exp"+exptest);
              

                 // Destination
                var destpre='';   
                var desttest='';
                $("input[name^=destpre_]").each(function(e)
                {
                    if(destpre=='')
                        {
                            destpre = $(this).attr('name').replace('destpre_','');
                            destpre = destpre+"_"+$(this).val();               
                            desttest = destpre+"_"+$("#destpost_"+e).val();
                        }
                    else
                        {
                        destpre = $(this).attr('name').replace('destpre_','');
                        desttest = desttest+"~"+destpre+"_"+$(this).val()+"_"+$("#destpost_"+e).val();	
                        }

                });
                //alert("Dest"+desttest);

                // Task

                var taskpre='';   
                var tasktest='';
                $("input[name^=taskpre_]").each(function(e)
                {
                    if(taskpre=='')
                        {
                            taskpre = $(this).attr('name').replace('taskpre_','');
                            taskpre = taskpre+"_"+$(this).val();               
                            tasktest = taskpre+"_"+$("#taskpost_"+e).val();
                        }
                    else
                        {
                        taskpre = $(this).attr('name').replace('taskpre_','');
                        tasktest = tasktest+"~"+taskpre+"_"+$(this).val()+"_"+$("#taskpost_"+e).val();	
                        }

                });
                //alert("Task"+tasktest);

                 // Resource

                var respre='';   
                var restest='';
                $("input[name^=respre_]").each(function(e)
                {
                    if(respre=='')
                        {
                            respre = $(this).attr('name').replace('respre_','');
                            respre = respre+"_"+$(this).val();               
                            restest = respre+"_"+$("#respost_"+e).val();
                        }
                    else
                        {
                        respre = $(this).attr('name').replace('respre_','');
                        restest = restest+"~"+respre+"_"+$(this).val()+"_"+$("#respost_"+e).val();	
                        }

                });
                //alert("Res"+restest);
                // Exp Inline test save and Edit Ends
				
		var sname = escapestr($('#sname').val());
		
		var dataparam="oper=saveindassesmentexpedition&sname="+sname+"&startdate="+$('#startdate').val()+"&enddate="+$('#enddate').val()+"&scheduletype="+$('#scheduletype').val()+"&studenttype="+$('#studenttype').val()+"&sid="+sid+"&students="+list10+"&expeditionid="+$('#expid').val()+"&classid="+$('#hidclassid').val()+"&licenseid="+$('#licenseid').val()+"&unstudents="+list9+"&extid="+$('#exid').val()+"&extids="+extids+"&extid1="+extids+"&expids="+expids+"&selectallexpids="+selectallexpids+"&selectchkboxids="+selectchkboxids+"&exptest="+exptest+"&desttest="+desttest+"&tasktest="+tasktest+"&restest="+restest; //+"&exptpp="+$('#ppet').val()+"&pppa="+$('#pppa').html()+"&pwpa="+$('#pwpa').val()+"&ppsa="+$('#ppsa').html()+"&pwsa="+$('#pwsa').val()
		$.ajax({
		type: 'post',
		url: 'class/newclass/class-newclass-classajax.php',
		data: dataparam,
		beforeSend: function(){
		showloadingalert("Loading, please wait.");
		},
		success:function(data) {
		closeloadingalert();
		var data=data.split("~");
		$('#scheduleid').val(data[1]);
		var sid=$('#scheduleid').val();
		var classid=$('#hidclassid').val();
		if(trim(data[0])=="success")
		{
		$('.lb-content').html("Saved Successfully");
		removesections('#class-newclass-steps');
		removesections('#class-newclass-actions');
		setTimeout('showpageswithpostmethod("class-newclass-scheduledetails","class/newclass/class-newclass-scheduledetails.php","id='+$('#hidclassid').val()+","+$('#classtypeval').val()+'");',100);
		
		}
		else if(trim(data[0])=="fail")
		{
		$('.lb-content').html("Student limit exceed");
		}
		else if(trim(data[0])=="invalid")
		{
		$('.lb-content').html("Invalid data");
		}
		}
		});
	}
}

/* Function for Mission save */
function fn_saveindassesmentmission(sid)
{

	if($("#scheduleform").validate().form())
	{
		var list10 = [];
		var list9=[];
		
		$("div[id^=list9_]").each(function()
		{
		list9.push($(this).attr('id').replace('list9_',''));
		});
		
		$("div[id^=list10_]").each(function()
		{
		list10.push($(this).attr('id').replace('list10_',''));
		});
		
		if(list10=='' && $('#studenttype').val()==2)
		{
		$.Zebra_Dialog('<strong>Please select a student</strong>', {
		'buttons': false,
		'auto_close': 3000
		});
		return false;
		}
		
		if($('#misionid').val()==''){
		$.Zebra_Dialog('<strong>Please select anyone Title</strong>', {
		'buttons': false,
		'auto_close': 3000
		});
		return false;
		}
		
		/**************MOhan M ***********/
			var selectchkboxids=[];
			$("input[id^=chkboxrubric_]").each(function()
			{
				var rubricid=$(this).attr('id').replace('chkboxrubric_','');
				var misionid=$('#misionid').val();
				if($("#chkboxrubric_"+rubricid).is(':checked'))
				{
					var chkteacher = 1;
					var rubricval=rubricid+"~"+misionid;
					selectchkboxids.push(rubricval);
				}
			});
		/**************MOhan M ***********/
                /**************Karthi ***********/
			var selectchkboxtestids=[];
			$("input[id^=chkboxtest_]").each(function()
			{
				var testid=$(this).attr('id').replace('chkboxtest_','');
				var missid=$('#misionid').val(); 
				if($("#chkboxtest_"+testid).is(':checked'))
				{
					var testval=testid+"~"+missid;
					selectchkboxtestids.push(testval);
				}
			});
		/**************karthi ***********/
		
		
		if($('#ppet').val()=='' || $('#ppet').val()=='0'){
		$.Zebra_Dialog('<strong>Points possible is required</strong>', {
		'buttons': false,
		'auto_close': 3000
		});
		return false;
		}
		
		if($('#pwpa').val()=='' || $('#pwpa').val()=='0' || $('#pwsa').val()=='' || $('#pwsa').val()=='0'){
		$.Zebra_Dialog('<strong>Percentage weight is required</strong>', {
		'buttons': false,
		'auto_close': 3000
		});
		return false;
		}
		
		
		
		var sname = escapestr($('#sname').val());
		
		var dataparam="oper=saveindassesmentmission&sname="+sname+"&startdate="+$('#startdate').val()+"&enddate="+$('#enddate').val()+"&scheduletype="+$('#scheduletype').val()+"&studenttype="+$('#studenttype').val()+"&sid="+sid+"&students="+list10+"&missionid="+$('#misionid').val()+"&classid="+$('#hidclassid').val()+"&licenseid="+$('#licenseid').val()+"&unstudents="+list9+"&extid="+$('#misid').val()+"&selectchkboxids="+selectchkboxids+"&selectchkboxtestids="+selectchkboxtestids;
	
		$.ajax({
		type: 'post',
		url: 'class/newclass/class-newclass-classajax.php',
		data: dataparam,
		beforeSend: function(){
		showloadingalert("Loading, please wait.");
		},
		success:function(data) {
		closeloadingalert();
		var data=data.split("~");
		$('#scheduleid').val(data[1]);
		var sid=$('#scheduleid').val();
		var classid=$('#hidclassid').val();
		if(trim(data[0])=="success")
		{
		$('.lb-content').html("Saved Successfully");
		removesections('#class-newclass-steps');
		removesections('#class-newclass-actions');
		setTimeout('showpageswithpostmethod("class-newclass-scheduledetails","class/newclass/class-newclass-scheduledetails.php","id='+$('#hidclassid').val()+","+$('#classtypeval').val()+'");',100);
		
		}
		else if(trim(data[0])=="fail")
		{
		$('.lb-content').html("Student limit exceed");
		}
		else if(trim(data[0])=="invalid")
		{
		$('.lb-content').html("Invalid data");
		}
		}
		});
	}
}
/* Function for Mission save END */

$("#ppet").keyup(function() {

	var pointpossible=$('#ppet').val();
	
	if(pointpossible>=2)
	{
	var val=pointpossible/2;
	
	$('#pppa').html(val);
	$('#ppsa').html(val);
	
	var val1=pointpossible/val;
	
	$('#pwpa').val(100/val1);
	$('#pwsa').val(100/val1);
	
	}
	else
	{
	$('#pppa').html('');
	$('#ppsa').html('');
	$('#pwpa').val('');
	$('#pwsa').val('');
	}

});


$("#pwpa").keyup(function() {

	var pwpa=$("#pwpa").val();
	var pointpossible=$('#ppet').val();
	
	if(pwpa>100)
	{
	alert("The percentage weight should be lessthen or equal to 100");
	$('#pppa').html('');
	$('#ppsa').html('');
	$('#pwpa').val('');
	$('#pwsa').val('');
	}
	
	if(pwpa>0 && pwpa<=100)
	{
	$('#pppa').html((pointpossible/100)*pwpa);
	var val=100-pwpa;
	$('#pwsa').val(val);
	$('#ppsa').html((pointpossible/100)*val);
	}

});


$("#pwsa").keyup(function() {

	var pwsa=$("#pwsa").val();
	var pointpossible=$('#ppet').val();
	
	if(pwsa>100)
	{
	alert("The percentage weight should be lessthen or equal to 100");
	$('#pppa').html('');
	$('#ppsa').html('');
	$('#pwpa').val('');
	$('#pwsa').val('');
	}
	
	if(pwsa>0 && pwsa<=100)
	{
	$('#ppsa').html((pointpossible/100)*pwsa);
	var val=100-pwsa;
	$('#pwpa').val(val);
	$('#pppa').html((pointpossible/100)*val);
	}

});
function fn_extndcontforexpedn(scheduleid) {
   var dataparam = "oper=loadexpextendcontent&scheduleid="+scheduleid+"&expednid="+$('#expid').val()+"&licenseid="+$('#licenseid').val();
   $.ajax({
		type: 'post',
		url: "class/newclass/class-newclass-classajax.php",
		data: dataparam,
		beforeSend: function(){			
		},
		success:function(data) {
                  
			$('#expextendcontent').html(data);
		}
		
	});	
}

/* Expend Contact list Function start - created by chandra */
function fn_extndcontforexpedcont(scheduleid) {
   var dataparam = "oper=loadexpextendcontent1&scheduleid="+scheduleid+"&expednid="+$('#expid').val()+"&licenseid="+$('#licenseid').val();
   $.ajax({
		type: 'post',
		url: "class/newclass/class-newclass-classajax.php",
		data: dataparam,
		beforeSend: function(){			
		},
		success:function(data) {
                  
			$('#expeextendcontent').html(data);
		}
		
	});	
}

function fn_fillnameformod(expid)
{
    var expids = [];
    $('.ads_Checkbox_'+expid+':checked').each(function(){
           expids.push($(this).val());
    });
    

    var finalmodname='';
        
    for(i=0;i<expids.length;i++){
        var data=expids[i].split("_");
        if(i==0){
             finalmodname=data[2];    
        }
        else if(i>=3){
             finalmodname= finalmodname+"...";     
        }
        else{
             finalmodname= finalmodname+","+data[2];     
        }
    }
    if(expids.length==0)
    {
        finalmodname= "Select Extend Content";
    }

    $('#expname_'+expid).html(finalmodname);
    
    $('#selectallexp_'+expids).val('0');
                
}

function fn_selectallmod(expid)
{
    $('#selectallexp_'+expid).val('01');
    var finalmodname='Select All';
    $('#expname_'+expid).html(finalmodname);
    
    $('.ads_Checkbox_'+expid).prop('checked', false); // Unchecks it
    
    
}

/* Expend Contact list Function start */

/* Function extend content for mission  */
function fn_extndcontformission(scheduleid) {
   var dataparam = "oper=loadmissionextendcontent&scheduleid="+scheduleid+"&missionid="+$('#misionid').val()+"&licenseid="+$('#licenseid').val();
    $.ajax({
		type: 'post',
		url: "class/newclass/class-newclass-classajax.php",
		data: dataparam,
		beforeSend: function(){			
		},
		success:function(data) {

			$('#missionextendcontent').html(data);
        }
		
	});	
    }

function fn_blockcheck(value)
{
    if(value=='1')
    {
        if($('#scheduletype').val()=='2' || $('#scheduletype').val()=='6')
        {
           fn_blockstudent(); 
        }
    }
}


function fn_wcaexplock(id)
{
	if(trim($('#wcaexpcontent'+id).html())=='unlock'){
		var flag=1;	
		var msg = 'Are you sure you want to lock this expedition ?';	
		$('#wcaexplock').show();
	}
	else if(trim($('#wcaexpcontent'+id).html())=='Lock'){
		var flag=0;	
		var msg = 'Are you sure you want to unlock this expedition ? ';	
	}	
	var dataparam="oper=wcaexplock&scheduleid="+id+"&flag="+flag;	
	$.Zebra_Dialog(msg,
	{
		'type':     'confirmation',
		'buttons':  [
                {caption: 'No', callback: function() { }},
                {caption: 'Yes', callback: function() { 
                        $.ajax({
                                type: 'post',
                                url: 'class/newclass/class-newclass-classajax.php',
                                data: dataparam,	
                                beforeSend: function(){
                                        showloadingalert("Loading, please wait.");	
                                },		
                                success:function(data) {		
                                        closeloadingalert();
                                        if(flag==1){
                                            $('#wcaexplock'+id).attr('class','icon-synergy-locked');
                                            $('#wcaexpcontent'+id).html('Lock');
                                        }
                                        else if(flag==0){
                                            $('#wcaexplock'+id).attr('class','icon-synergy-unlocked');
                                            $('#wcaexpcontent'+id).html('unlock');
                                        }
                                }
                        });	
                }},
            ]
	});
}

function fn_wcamissionlock(id)
{
	if(trim($('#wcamiscontent'+id).html())=='unlock'){
            var flag=1;	
            var msg = 'Are you sure to lock the expedition?';	
            $('#wcamislock').show();
	}
	else if(trim($('#wcamiscontent'+id).html())=='Lock'){
            var flag=0;	
            var msg = 'Are you sure to unlock the expedition?';	
	}	
	var dataparam="oper=wcamislock&schid="+id+"&flag="+flag;	
	$.Zebra_Dialog(msg,
	{
            'type':     'confirmation',
            'buttons':  [
            {caption: 'No', callback: function() { }},
            {caption: 'Yes', callback: function() { 
                    $.ajax({
                            type: 'post',
                            url: 'class/newclass/class-newclass-classajax.php',
                            data: dataparam,	
                            beforeSend: function(){
                                    showloadingalert("Loading, please wait.");	
                            },		
                            success:function(data) {		
                                    closeloadingalert();
                                    if(flag==1){
                                            $('#wcamislock'+id).attr('class','icon-synergy-locked');
                                            $('#wcamiscontent'+id).html('Lock');
                                    }
                                    else if(flag==0){
                                            $('#wcamislock'+id).attr('class','icon-synergy-unlocked');
                                            $('#wcamiscontent'+id).html('unlock');
                                    }
                            }
                    });	
            }},
            ]
	});
}

function fn_selradio(rvalue){
    if(rvalue==1){
        $('#Types1').removeClass('dim');
        $('#Types2').addClass('dim');	
        $('#repeatweekday').hide();
        	
    }
    else if(rvalue==2){
        $('#Types1').addClass('dim');
        $('#startdate').val('');
        $('#enddate').val('');
        $('#Types2').removeClass('dim');	
       $('#repeatweekday').show();
    }
 }

/*****Function to used Save the Lock Class Start*****/
function fn_savelockclass(clsid)
{
    if($("#lockclassform").validate().form())
    {
    var startdate=$('#startdate').val();
    var enddate=$('#enddate').val();
        var timezone=$('#timezones').val();

        var byrowid=$('#byrowid').val();
        var dayrange=$('#dayrange').val();
    
	  	if(byrowid==undefined)
        {
			var wchkflag=[];
			var wchkval=[];
    
    
			$('input[id^=wdaychk]').each(function() 
			{ 
				var mlessonid=$(this).val();
				wchkval.push(mlessonid);
    
				if($('#wdaychk'+mlessonid).is(':checked'))
				{
					var tmpgrade1=1;
				}
				else
				{
					var tmpgrade1=0;
				} 
				wchkflag.push(tmpgrade1);
			});
    
		}
		else
    {
			var wchkflage=[];
			var wchkvale=[];
          
			$('input[id^=wdaychke]').each(function() 
			{ 
				var mlessonide=$(this).val();
				wchkvale.push(mlessonide);

				if($('#wdaychke'+mlessonide).is(':checked'))
				{
					var tmpgrade1e=1;
				}
				else
				{
					var tmpgrade1e=0;
				} 
				wchkflage.push(tmpgrade1e);
			});
        
		}
        if($('#startdate').val()=='')
        {
            showloadingalert("Please Select Start Date");	
            setTimeout('closeloadingalert()',2000);
            return false;
        }
        
        if($('#enddate').val()=='')
        {
            showloadingalert("Please Select End Date");	
            setTimeout('closeloadingalert()',2000);
            return false;
        }
          
    
       
          
        var shour=$('#bydateshour').val();
        var smin=$('#bydatesmin').val();
        var sampm=$('#bydatesampm').val();

        var ehour=$('#bydateehour').val();
        var emin=$('#bydateemin').val();
        var eampm=$('#bydateeampm').val();
        
        if(shour=='')
        {
            showloadingalert("Please Select Start Date Hour");	
            setTimeout('closeloadingalert()',2000);
            return false;
        }
        
        if(ehour=='')
        {
            showloadingalert("Please Select End Date Hour");	
            setTimeout('closeloadingalert()',2000);
            return false;
        }
       
        
        if(startdate==enddate)
        {
            if(eampm=='AM')
            {
                 if(ehour=='12')
                 {
                       var flag=1;
                 }
            }

            if(flag==1)
            {
               showloadingalert("End Time should not be less than Starting Time");	
               setTimeout('closeloadingalert()',3000);
               return false;
            }
        }
        

        if(shour.length=='1')
        {
            shour="0"+shour;
        }

        if(ehour.length=='1')
        {
            ehour="0"+ehour;
        }   


        /* Starting Hour is Must be Greater then End Hour */
            if(startdate==enddate)
            {
                if(sampm=='AM'){
                    if(eampm=='AM'){
                        if(shour==ehour){
                             if(smin<=emin){
                                 var flag=0;
                             }
                             else{
                                 flag=1;
                             }
                         }
                         else if(shour<=ehour){
                          flag=0;
                         }
                         else{
                          flag=1;
                         }
                    }
                    else if(eampm=='PM'){
                        var flag=0;
                    }
                    else{
                      flag=0;
                    }
                }
                else if(sampm=='PM'){
                    if(eampm=='PM'){
                        if(shour==ehour){
                             if(smin<=emin){
                                 var flag=0;
                             }
                             else{
                                 flag=1;
                             }
                         }
                         else if(shour<=ehour){
                          flag=0;
                         }
                         else{
                          flag=1;
                         }
                    }
                    else if(eampm=='AM'){
                        var flag=1;
                    }
                    else{
                        flag=0;
                    }
                }
               if(flag==1){
                    showloadingalert("Starting Hour is Must be Greater then End Hour");	
                    setTimeout('closeloadingalert()',3000);
                    return false;
                }

            }
        /* Starting Hour is Must be Greater then End Hour */


        /*new Line */
        var currentDate = $('#startdate').val();
        var dateArr     = currentDate.split('/');
        var val         = dateArr[2] + '-' + dateArr[0]  + '-' + dateArr[1];

        var cstsdate=$('#cstsdate').val();
        var cstsdatetimeh=$('#cstsdatetimeh').val();
        var cstsdatetimem=$('#cstsdatetimem').val();
        var cstsdatetimea=$('#cstsdatetimea').val();
        
        
        if(sampm=="PM" && shour<12) 
        {
           var shour= parseInt(shour) + parseInt(12);
        }
        
        if(sampm=="AM" && shour==12) 
        {
             var shour= parseInt(shour) - parseInt(12);
        } 
        
        
        if(cstsdatetimea=="PM" && cstsdatetimeh<12) 
        {
           var cstsdatetimeh= parseInt(cstsdatetimeh) + parseInt(12);
        }
        
        if(cstsdatetimea=="AM" && cstsdatetimeh==12) 
        {
             var cstsdatetimeh= parseInt(cstsdatetimeh) - parseInt(12);
        }
    
    if(byrowid==undefined)
    {
        actionmsg = "Saving";
        alertmsg = "Lock Class Automation has been created successfully"; 
		 	var dataparam = "oper=savelockclass&classid="+clsid+"&startdate="+startdate+"&enddate="+enddate+"&bydateshour="+$('#bydateshour').val()+"&bydatesmin="+$('#bydatesmin').val()+"&bydatesampm="+$('#bydatesampm').val()+"&bydateehour="+$('#bydateehour').val()+"&bydateemin="+$('#bydateemin').val()+"&bydateeampm="+$('#bydateeampm').val()+"&byrowid="+byrowid+"&timezone="+timezone+"&dayrange="+$('#dayrange').val()+"&wchkval="+wchkval+"&wchkflag="+wchkflag;
    }
    else
    {
         actionmsg = "Updating";
         alertmsg = "Lock Class Automation has been updated successfully";   
			 var dataparam = "oper=savelockclass&classid="+clsid+"&startdate="+startdate+"&enddate="+enddate+"&bydateshour="+$('#bydateshour').val()+"&bydatesmin="+$('#bydatesmin').val()+"&bydatesampm="+$('#bydatesampm').val()+"&bydateehour="+$('#bydateehour').val()+"&bydateemin="+$('#bydateemin').val()+"&bydateeampm="+$('#bydateeampm').val()+"&byrowid="+byrowid+"&timezone="+timezone+"&dayrange="+$('#dayrange').val()+"&wchkval="+wchkvale+"&wchkflag="+wchkflage;
    }
    $.ajax({
        url: "class/newclass/class-newclass-lockclassautomation-ajax.php",
        data: dataparam,
        type: "POST",
        beforeSend: function()
        {
                showloadingalert(actionmsg+", please wait.");
        },
        success: function (data) 
        {                
            if(data=="success") //Works if the data saved in db
            {
                $('.lb-content').html(alertmsg);
                setTimeout("closeloadingalert();",500);
                setTimeout("removesections('#class-newclass-actions');",1000);
                setTimeout('showpageswithpostmethod("class-newclass-lockclassautomation","class/newclass/class-newclass-lockclassautomation.php","id='+clsid+'");',1500);
            }
            else
            {
                $('.lb-content').html("That cant occur and the user should change or cancel one of the lock events to prevent conflict");
                setTimeout('closeloadingalert()',3500);
            }
        },
    });
    } // validation if condition
}
/*****Function to used Save the Lock Class End*****/


/*--- Delete the lock class statement Start---*/
function fn_deletelockclassdet(lockclassid,classid,delordisable)
{
    var dataparam = "oper=deleteelockclass"+"&lockclassid="+lockclassid+"&classid="+classid+"&delordisable="+delordisable;	
	if(delordisable=='1') //Disable
	{
		var msg='Are you sure want to disable this Event?';
	}
	else //Delete
	{
		var msg='Are you sure want to delete this Event?';
	}
	
	$.Zebra_Dialog(msg,
	{
			'type': 'confirmation',
			'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {
				$.ajax({
					type: 'post',
                                        url: "class/newclass/class-newclass-lockclassautomation-ajax.php",
					data: dataparam,
					beforeSend: function()
					{
						if(delordisable=='1') //Disable
						{
							showloadingalert('Disabling, please wait.');	
						}
						else //Delete
						{
						showloadingalert('Deleting, please wait.');	
						}
					},
					success: function (data) 
					{	
						if(data=="success")
						{
							closeloadingalert();	

							if(delordisable=='1') //Disable
							{
								showloadingalert("Event disabled successfully");
								setTimeout("closeloadingalert();",1000);
								$('#disable_btn_'+lockclassid).addClass('dim');
								$('#enable_btn_'+lockclassid).removeClass('dim');
								$('.edit_btn_'+lockclassid).addClass('dim');
							}
							else //Delete
							{
								showloadingalert("Event deleted successfully");
								setTimeout("closeloadingalert();",1000);
								setTimeout("removesections('#class-newclass-actions');",1000);
								setTimeout('showpageswithpostmethod("class-newclass-lockclassautomation","class/newclass/class-newclass-lockclassautomation.php","id='+classid+'");',1500);
							}
						}
						else
						{
							closeloadingalert();	
							showloadingalert("Deleting the Event has been failed");
							setTimeout('closeloadingalert()',1000);
						}
					}
				});	
			}}
		]
	});
}
/*--- Delete the lock class statement End---*/

/*******Edit Lock Class Start*******/
function fn_editlockclassdet(rowid,classid,locktype,sdate,shour,smin,sampm,edate,ehour,emin,eampm,timezone,timezonename)
{
    var dataparam = "oper=bydateform&classid="+classid+"&rowid="+rowid+"&type="+locktype;
    $('#tz').html(timezonename);
    $('#timezones').val(timezone);
    fn_hiddenval(timezone);
     $('#dayrange').val(locktype);
	if(locktype=='2')
	{
		$('#repeatevent').hide();
	}
	else
	{
		$('#repeatevent').show();
	}
    $.ajax({
		type: 'post',
                url: "class/newclass/class-newclass-lockclassautomation-ajax.php",
		data: dataparam,
		beforeSend: function(){
			showloadingalert('Loading, please wait.');
		},
		success:function(data) 
		{
			setTimeout("closeloadingalert();",500);
			if(locktype==1)
			{

				$('#bydatea').html(data);
				$('#btnstep').val('Update');
			}
			else
			{
				$('#bydatea').html(data);
				$('#btnstep').val('Update');
			}
                        
		}
	});
}

function fn_hiddenval(timezonetype)
{
    var dataparam = "oper=hiddenval&timezonetype="+timezonetype;
    $.ajax({
		type: 'post',
                url: "class/newclass/class-newclass-lockclassautomation-ajax.php",
		data: dataparam,
		beforeSend: function(){
		},
		success:function(data) {
                    var response=trim(data);
                    var output=response.split('~');
                    var status=output[0];
                    var startdatecst=output[1];
                    var sdatecsttimeh=output[2];
                    var sdatecsttimem=output[3];
                    var sdatecsttimea=output[4];
                    var sdatecstday=output[5];
                    
                    
                    $('#cstsdate').val(startdatecst);
                    $('#cstsdatetimeh').val(sdatecsttimeh);
                    $('#cstsdatetimem').val(sdatecsttimem);
                    $('#cstsdatetimea').val(sdatecsttimea);
                    $('#cstsday').val(sdatecstday);
                        
		}
	});
}
/*******Edit Lock Class End*******/


///no use for this function
/*****Day Light Saving time or not******/
function fn_checkdaylight()
{
    if (!$('input.checkbox_check').is(':checked')) 
    {
          $('#daylightchkbox').val(1);
    }
    else
    {
          $('#daylightchkbox').val(0);
    }
}
/*****Day Light Saving time or not******/

///no use for this function
function fn_showrepeatweek(sdayoreday)
{
    
        var sweekday=$('#sweekday').val();
        var eweekday=$('#eweekday').val();
   
        var dataparam = "oper=showrepeatweek&sweekday="+sweekday+"&eweekday="+eweekday+"&sdayoreday="+sdayoreday;
        $.ajax({
                type: 'post',
                url: "class/newclass/class-newclass-lockclassautomation-ajax.php",
                data: dataparam,
                beforeSend: function(){
                },
                success:function(data) {
                    $('#repeatweekday').html(data);

                }
        });
    
   
}

function fn_dayrange(type)
{
    if($("#lockclassform").validate().form())
    {
        var startdate=$('#startdate').val();
        var enddate=$('#enddate').val();
        
        var timezone=$('#timezones').val();
        
		var shour=$('#bydateshour').val();
        var smin=$('#bydatesmin').val();
        var sampm=$('#bydatesampm').val();

        var ehour=$('#bydateehour').val();
        var emin=$('#bydateemin').val();
        var eampm=$('#bydateeampm').val();
		
		if(type=='1')
		{
			var startDay = new Date(startdate);
			var endDay = new Date(enddate);
			var millisecondsPerDay = 1000 * 60 * 60 * 24;

			var millisBetween = startDay.getTime() - endDay.getTime();
			var days = millisBetween / millisecondsPerDay;
			// Round down.
			var differdays=Math.floor(days);
			if(differdays==-1 || differdays==-0)
			{
				$('#repeatevent').show();
				$('#dayrange').val(1);
			}
			else
			{
				$('#repeatevent').hide();
				$('#dayrange').val(2);
			}
                }
		else
		{
			
			if(enddate=='' || ehour=='00')
			{
				$('#repeatevent').hide();
				$('#dayrange').val(2);
				return false;
			}
			
			var dataparam = "oper=calculatedayrange&startdate="+startdate+"&enddate="+enddate+"&timezone="+timezone+"&bydateshour="+shour+"&bydatesmin="+smin+"&bydatesampm="+sampm+"&bydateehour="+ehour+"&bydateemin="+emin+"&bydateeampm="+eampm;
			
			$.ajax({
				type: 'post',
				url: "class/newclass/class-newclass-lockclassautomation-ajax.php",
				data: dataparam,
				beforeSend: function(){
				},
				success:function(data) {
				  
					var response=trim(data);
					var output=response.split('~');
					var status=output[0];
					var hours=output[1];
					var minutes=output[2];
					var differdays=output[3];
					
					if(differdays==1)
					{
						flag=1;
					}
					else
					{
						if(hours<24)
						{
							flag=1;
						}
						else if(hours==24)
						{
							if(minutes=='0')
							{
								flag=1;
							}
							else
							{
								flag=0;
							}
						}
						else
						{
							flag=0;
						}
					}
					
					if(flag==1)
					{
						$('#repeatevent').show();
						$('#dayrange').val(1);
					}
					else
					{
						$('#repeatevent').hide();
						$('#dayrange').val(2);
						$('#wdaychk1').prop('checked', false);
						$('#wdaychk2').prop('checked', false);
						$('#wdaychk3').prop('checked', false);
						$('#wdaychk4').prop('checked', false);
						$('#wdaychk5').prop('checked', false);
						$('#wdaychk6').prop('checked', false);
						$('#wdaychk7').prop('checked', false);
					}
				}
			});
		}
    }
}

function fn_enablelockclassdet(rowid,clsid,locktype,sdate,shour,smin,sampm,edate,ehour,emin,eampm,timezone,timezonename,wchkvale,wchkflage)
{
 	var wchkval=JSON.parse(wchkvale);
 	var wchkflag=JSON.parse(wchkflage);
	
	actionmsg = "Enabling";
 	alertmsg = "This Event has been enabled successfully";   
 	
	var dataparam = "oper=savelockclass&classid="+clsid+"&startdate="+sdate+"&enddate="+edate+"&bydateshour="+shour+"&bydatesmin="+smin+"&bydatesampm="+sampm+"&bydateehour="+ehour+"&bydateemin="+emin+"&bydateeampm="+eampm+"&byrowid="+rowid+"&timezone="+timezone+"&dayrange="+locktype+"&wchkval="+wchkval+"&wchkflag="+wchkflag+"&enableflag=1";
  	$.ajax({
		url: "class/newclass/class-newclass-lockclassautomation-ajax.php",
		data: dataparam,
		type: "POST",
		beforeSend: function()
		{
		    showloadingalert(actionmsg+", please wait.");
		},
		success: function (data) 
		{
			
			if(data=="success") //Works if the data saved in db
			{
				$('.lb-content').html(alertmsg);
				setTimeout("closeloadingalert();",1000);
				$('#disable_btn_'+rowid).removeClass('dim');
				$('.edit_btn_'+rowid).removeClass('dim');
				$('#enable_btn_'+rowid).addClass('dim');
			}
			else
			{
				$('.lb-content').html("That cant occur and the user should change or cancel one of the lock events to prevent conflict");
				setTimeout('closeloadingalert()',3500);
			}
		},
	});
}



    


