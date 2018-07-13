/* show the Mission schedular form */
function fn_missionloadcontent(lid,sid,type)
{
	        if(type==0)
                {
                   sid=$('#misschtemplateid').val();
                }
	
		dataparam="oper=missionloadcontent&lid="+lid+"&sid="+sid+"&classid="+$('#hidclassid').val()+"&type="+type;	
		$.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-mission-ajax.php',
			data: dataparam,		
			success:function(ajaxdata) {
				var classid=$('#hidclassid').val();
				$('#rotcontent').html(ajaxdata);	
				if(sid!=0)
				{
					if($('#rotationtype').val()=="update")
					{
					  setTimeout('showpageswithpostmethod("class-newclass-viewschedule_editmission","class/newclass/class-newclass-viewschedule_editmission.php","id='+sid+","+classid+'");',500);	
					}
					
				}		
			}
		});	
}


/* get Missions based on license id */
function fn_loadmission(scheduleid,type,asstype)
{
	var lid = $('#licenseid').val();		
		dataparam="oper=loadmission&licenseid="+lid+"&scheduleid="+scheduleid+"&moduletype="+type+"&assigntype="+asstype;		 	
		$.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-mission-ajax.php',
			data: dataparam,		
			success:function(ajaxdata) {
				$('#missions').html(ajaxdata);
				$('#modnxtstep').show();
				
                                if(scheduleid==0)
				{
                                    fn_missionchecking();
                                }
                                else{
                                    fn_missionass();
                                }
				
			}
        });
} 

/* Compare student and module count if student count greater then to mission count show the alert */
function fn_missionchecking(type)
{

	var list4 = [];
	var list10 = [];
	
	$("div[id^=list10_]").each(function()
		{
			list10.push($(this).attr('id').replace('list10_',''));
		});
	
	$("div[id^=list4_]").each(function()
		{
			list4.push($(this).attr('id').replace('list4_',''));
		});	
	
	if($('#studenttype').val()==2)
	{
		var studentcount=list10.length;
	}
	else
	{
		var studentcount=$('#assignstudents').val();
	}
		
	if($('#schtype').val()=="edit")
        {
               var modcount=$("#myTable05 tr").length-2;
        }
        else
        {
               var modcount=list4.length;
        }
	
	
	if($('#numberofcopies').val()>1)
	{
		modcount=modcount*parseInt(4)*parseInt($('#numberofcopies').val());
	}
	else
	{
		modcount=modcount*parseInt(4);
	}
	
	if(parseInt(studentcount)<=parseInt(modcount))
	{
			$('.tLeft').html('');
	}
	else
	{
		
		$('.tLeft').html('You have '+modcount+ ' seats for '+studentcount + ' student');
	}

}


/* Show mission list in popup */ 
function fn_showmission(scheduleid)
{	
	var tr=$("#myTable05 tr").length;
        var licenseid=$('#licenseid').val();
        var trlength=tr-2;
        if($('#numberofcopies').val()==1)
        {
            if(trlength==25)
            {
                        $.Zebra_Dialog("Total number of titles must be<br> less than or equal to 25 per class schedule.</br>", { 'type': 'information'});
                        return false;
            }
        }
	$.fancybox.showActivity();

		$.ajax({
			type	: "POST",
			cache	: false,
			url		: "class/newclass/class-newclass-mission-ajax.php",
			data		: "oper=showmission&scheduleid="+scheduleid+"&licenseid="+licenseid,
			success: function(data) {
				$.fancybox(data);
			}
		});
	
		return false;
}


/* save the mission schedular details */
function fn_savemissionschedule(flag)
{
	$('#enddate').val('03/03/3000');
	if($("#scheduleform").validate().form() && $("#sform").validate().form())
	{
			
		var list10 = [];
		var list4 = [];
		
		
		
		$("div[id^=list10_]").each(function()
		{
			list10.push($(this).attr('id').replace('list10_',''));
		});
		
                $("div[id^=list4_]").each(function()
		{
			list4.push($(this).attr('id').replace('list4_',''));
		});
		
                if(list10=='' && $('#studenttype').val()==2)
		{
			$.Zebra_Dialog('<strong>Please select a student</strong>', {
			'buttons':  false,
			'auto_close': 3000
			});
			return false;
		}
		
		if($('#studenttype').val()==2)
		{
			var studentcount=list10.length;
		}
		else
		{
			var studentcount=$('#assignstudents').val();
		}
		
                if($('#schtype').val()=="edit")
                {
                    var modcount=$("#myTable05 tr").length-2;
                }
                else
                {
                    var modcount=list4.length;
                }
		
		if($('#numberofcopies').val()>1)
		{
			modcount=modcount*parseInt(4)*parseInt($('#numberofcopies').val());
		}
		else
		{
			modcount=modcount*parseInt(4);
		}
		
		if(parseInt(studentcount)<=parseInt(modcount))
		{
				// Dont show the dialog box
		}
		else
		{
			var count=parseInt(studentcount)-parseInt(modcount)
			$.Zebra_Dialog('<strong>You have '+modcount+ ' seats for '+studentcount + ' student</strong>', {
			'buttons':  false,
			'auto_close': 3000
			});
			return false;
		}
		
		if(list4=='')
		{
		 $.Zebra_Dialog('<strong>Please select anyone Mission</strong>', {
		'buttons':  false,
		'auto_close': 3000
		});
		return false;
		}
		
                if($('#numberofrotations').val()>17)
                {
                    $.Zebra_Dialog("Total number of rotations must be</br>less than or equal to 17.</br>", { 'type': 'information'});
                    return false;
                }
                
                if(list4.length > 25)
                {
                    $.Zebra_Dialog("Total number of titles must be<br> less than or equal to 25 per class schedule.", { 'type': 'information'});
                    return false;
                }
                
                
                /**************Mohan M ***********/
			var selectchkboxids=[];
			$("input[id^=chkboxrubric_]").each(function()
			{					
				var rubricid=$(this).attr('id').replace('chkboxrubric_','');
				var expid=$('#chkboxrubric_'+rubricid).val();
				if($("#chkboxrubric_"+rubricid).is(':checked'))
				{
					var chkteacher = 1;
					var rubricval=rubricid+"~"+expid;
					selectchkboxids.push(rubricval);
				}
			});
		/**************Mohan M ***********/
                /**************Karthi ***********/
			var selectchkboxtestids=[];
			$("input[id^=chkboxtest_]").each(function()
			{
				var testid=$(this).attr('id').replace('chkboxtest_','');
				var missid=$('#chkboxtest_'+testid).val(); 
				if($("#chkboxtest_"+testid).is(':checked'))
				{
					var testval=testid+"~"+missid;
					selectchkboxtestids.push(testval);
				}
			});
		/**************karthi ***********/
		
		
		var sname = escapestr($('#sname').val());
		var stype = $('#scheduletype').val();
		var dataparam="oper=saveschedule&sname="+sname+"&startdate="+$('#startdate').val()+"&scheduletype="+stype+"&studenttype="+$('#studenttype').val()+"&numberofcopies="+$('#numberofcopies').val()+"&numberofrotations="+$('#numberofrotations').val()+"&rotationlength="+$('#rotationlength').val()+"&sid="+$('#hidscheduleid').val()+"&students="+list10+"&missions="+list4+"&classid="+$('#hidclassid').val()+"&licenseid="+$('#licenseid').val()+"&unstudents="+list9+"&selectchkboxids="+selectchkboxids+"&selectchkboxtestids="+selectchkboxtestids+"&schflag="+flag;
	
		$.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-mission-ajax.php',
			data: dataparam,		
			async:false,
			success:function(data) {
				var data=data.split("~");
                                if(data[1]!='')
                                {
				$('#scheduleid').val(data[1]);
                                }
				var sid=$('#scheduleid').val();
				$('#hidscheduleid').val(data[1]);
				var classid=$('#hidclassid').val();
				$('#enddate').val('');
				if(trim(data[0])=="success")
				{
                                   
					if(flag==0)
					{
						if($('#rotationtype').val()=="create")
						{
							
						setTimeout("removesections('#class-newclass-newschedulestep');",500);
						setTimeout('showpageswithpostmethod("class-newclass-viewschedule_createmission","class/newclass/class-newclass-viewschedule_createmission.php","id='+sid+","+classid+'");',500);
						}
						else
						{
						
						setTimeout("removesections('#class-newclass-newschedulestep');",500);
						setTimeout('showpageswithpostmethod("class-newclass-viewschedule_editmission","class/newclass/class-newclass-viewschedule_editmission.php","id='+sid+","+classid+'");',500);
							
						}
					}
					else if(flag==1)
					{
							setTimeout("removesections('#class-newclass-steps');",500);	
							setTimeout("removesections('#class-newclass-actions');",500);			
						setTimeout('showpageswithpostmethod("class-newclass-scheduledetails","class/newclass/class-newclass-scheduledetails.php","id='+$('#hidclassid').val()+","+$('#classtypeval').val()+'");',500);
						setTimeout('showpageswithpostmethod("class-newclass-viewschedule_editmission","class/newclass/class-newclass-viewschedule_editmission.php","id='+sid+","+stype+","+classid+","+sname+',viewrot'+'");',1000);
						
				    }  
                                        else if(flag==2)
                                        {
                                             showloadingalert("Saved Sucessfully.");	
                                             setTimeout("closeloadingalert();",1000);
                                             fn_blockexpeditions();
                                             setTimeout("fn_blockexpstudent();",1000);
				}
                                         
				}
				if(trim(data[0])=="fail")
				{
					 $('.lb-content').html("Incorrect Data");
					 setTimeout('closeloadingalert()',1000);
				}
			}
		});
	}
}


/* Add mission to grid table */
function fn_addmission(misid,scheduleid,type)
{
	$.fancybox.close();
        var numberofrotation=parseInt($("#myTable05 th").length)-parseInt(1);
	var dataparam = "oper=addmission&misid="+misid+"&trlength="+$("#myTable05 tr:last").prev().attr('id')+"&thlength="+$("#myTable05 tr:first th").length+"&scheduleid="+scheduleid+"&scheduletype="+$('#scheduletype').val()+"&type="+type+"&classid="+$('#hidclassid').val()+"&numberofrotation="+numberofrotation+"&mode="+$('#schtype').val();

	
	$.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-mission-ajax.php',
			data: dataparam,
			beforeSend: function(){
				showloadingalert("Loading, please wait.");	
			},		
			success:function(data) {
				
				$('#myTable05').fixedHeaderTable('destroy');	
				setTimeout("closeloadingalert();",1000);
				showloadingalert("Mission added to table.");
				setTimeout("closeloadingalert();",1000);
					$('#body tr:last').remove();
					$('#body').append(data);
					$('#myTable05').fixedHeaderTable({fixedColumn: true });
                                        $(".misname").css({"height":"84px"});
					$.getScript('class/newclass/class-newclass-rotationalschedule.js');
					$(".clk").off();
					$(".clk").on();
					var stu=$('#studentcount').val();
					var modcount=($("#myTable05 tr").length-parseInt(2))*parseInt(4);
					var mod=$("#myTable05 tr").length-parseInt(2);
					var rot=$("#myTable05 th").length-parseInt(1);
					if(rot>=mod)
					{
						$('#endrotation').val(mod);
					}
					if(stu>modcount)
					{
						$('#checkseat').html('At least '+stu+ ' seats per rotation are needed');
						$('#generatebtn').addClass('dim');
					}
					else
					{
						$('#checkseat').html('');
						$('#generatebtn').removeClass('dim');
					}
					
					$('.addmodinc').removeClass('dim');
                                        fn_missionchecking();
			}
		});	
}


/* The user hover the Mission if without students in mission  title,style and function assigned to expedition */

function fn_checkcellvaluemis(rowid)
{
	
	var thlength=$("#myTable05 tr:first th").length;
	var row='true';
		for(zi=rowid;zi<=rowid;zi++)
		{
			for(zj=2;zj<=thlength;zj++)
			{
                            
				if(($('#seg1_'+zi+"_"+zj).html()!='&nbsp;') || ($('#seg2_'+zi+"_"+zj).html()!='&nbsp;') || ($('#seg3_'+zi+"_"+zj).html()!='&nbsp;') || ($('#seg4_'+zi+"_"+zj).html()!='&nbsp;'))
				{
					// don't add title and function
					row="false";
				}
                               
			}
		}
		
		
		if(row=="true")
		{
			$("#module_"+rowid).attr("title","Remove a Mission");
			$("#module_"+rowid).attr("onclick","fn_removemission("+rowid+")");
			$("#module_"+rowid).css("cursor","pointer");
				
		}
		else
		{
			$("#module_"+rowid).removeAttr("title");
			$("#module_"+rowid).attr("onclick"," ");
			$("#module_"+rowid).css("cursor","default");
		}
	
}

function fn_checkcellvalueoutmis(id) 
{
	var val=$('#'+id).attr('title');
	if(val=="Remove a Mission")
	{
		$('#'+id).removeAttr('title');
	}
}

/* Remove Mission to table */	
function fn_removemission(rowid)
{		
    var rowclassname=$('#tr_'+rowid).attr('class');
                                                       
                                                        var m=0;
                                                        var rowidm=new Array();
                                                        $.each($('.'+rowclassname),function(){
                                                                var id=this.id.split("_");
                                                                rowidm[m]= id[1]-parseInt(1);
                                                                m++;

                                                        });
                                                        
                                                        
                                                        
		$.Zebra_Dialog('Are you sure you want to delete this Mission ?',
		 {
		'type':     'confirmation',
		'buttons':  [
						{caption: 'No', callback: function() { }},
						{caption: 'Yes', callback: function() { 
						
							$('#myTable05').fixedHeaderTable('destroy');		
							$("#tr_"+rowid).remove();
							$('#myTable05').fixedHeaderTable({fixedColumn: true });	
                                                        $(".misname").css({"height":"84px"});
							var stu=$('#studentcount').val();
							var modcount=($("#myTable05 tr").length-parseInt(2))*parseInt(4);
							var mod=$("#myTable05 tr").length-parseInt(2);
							var rot=$("#myTable05 th").length-parseInt(1);
							if(rot>=mod)
							{
								$('#endrotation').val(mod);
							}
							if(stu>modcount)
							{
								$('#checkseat').html('At least '+stu+ ' seats per rotation are needed');
								$('#generatebtn').addClass('dim');
							}
							else
							{
								$('#checkseat').html('');
								$('#generatebtn').removeClass('dim');
							}
							
							$('.addmodinc').removeClass('dim');
							var tid = rowid+1;								
							$("tr[id^=tr_]").each(function(index, element) {
								var temp = $(this).attr('id').replace('tr_','');
								if(temp>rowid){
									$(this).attr('id','tr_'+rowid);
									$(this).children(":first").attr({id: 'module_'+rowid,onmouseover: 'fn_checkcellvaluemis('+rowid+')'});
									$(this).children().each(function(index, element) {
										$(this).html($(this).html().replace(new RegExp('_'+tid+'_','g'),'_'+rowid+'_'));
									});	
									rowid++;
									tid++;	
								}
							});						
                                                        
                                                        if(rowidm.length>0)
                                                        {
                                                           fn_removemissiontable(rowid,rowclassname); 
                                                        }
						}},
					]
		});
		return false;
}


function fn_removemissiontable(rowid,rowclassname)
{
    dataparam="oper=removemission&mistype="+rowclassname+"&rowid="+rowid+"&scheduleid="+$('#scheduleid').val()+"&classid="+$('#classid').val();
    
    $.ajax({
        type: 'post',
        url: 'class/newclass/class-newclass-mission-ajax.php',
        data: dataparam,
        success:function(data) 
        {
                 
             closeloadingalert();
                       
              }
        });
    
    
}

function fn_missionaddcolumn()
{
        var c=$("#myTable05 tr:first th").length;
        if(c > 17)
        {
                    $.Zebra_Dialog("Total number of rotations must be</br>less than or equal to 17.</br>", { 'type': 'information'});
                    return false;
        }
        
	$('#myTable05').fixedHeaderTable('destroy');
	
	$("#myTable05 th:last").removeAttr("title");
	$("#myTable05 th:last").attr("onclick"," ");
	$("#myTable05 th:last").css("cursor","default");
		
	
	var tr=$("#myTable05 tr").length;

	$("#myTable05 tr:first").append("<th class='modhead' style='font-size:24px;cursor:pointer;' title='Remove rotation' onclick='fn_missionremovecolumn()';><span style='font-size:14px;vertical-align:top;'>rotation "+c+"</th></soan>");
	
	var i='';
	for(i=2;i<=tr;i++)
	{
		var num=c+1;
		
		if(i!=tr)
		{
		$('#tr_'+i).append("<td id=stu_"+i+''+num+" style='background: #FFFFFF;width:205px;'></td>");
		var div="<div class='rowspanone clk row"+num+"' id='seg1_"+i+'_'+num+"'>&nbsp;</div><div class='imagetop' id='imagetop_"+i+'_'+num+"' title='Delete'></div><div class='rowspantwo clk row"+num+"' id='seg2_"+i+'_'+num+"'>&nbsp;</div><div class='imagebottom' id='imagebottom_"+i+'_'+num+"' title='Delete'></div>";
                var divadd="<div class='rowspanonedup clk row"+num+"' id='seg3_"+i+'_'+num+"'>&nbsp;</div><div class='imagetopdup' id='imagetopdup_"+i+'_'+num+"' title='Delete'></div><div class='rowspantwodup clk row"+num+"' id='seg4_"+i+'_'+num+"'>&nbsp;</div><div class='imagebottomdup' id='imagebottomdup_"+i+'_'+num+"' title='Delete'></div>";
                var divsum=div+divadd
                $('#stu_'+i+''+num).html(divsum);
		}
		else
		{
		$('#addmod').append("<td></td>"); 
		}
	}
	
	var rotation=parseInt($('#noofrotation').val())+parseInt(1);
	$('#noofrotation').val(rotation);
	var thcount=$("#myTable05 tr:first th").length-parseInt(1);
	var trcount=$("#myTable05 tr").length-parseInt(2);
	
	if(trcount>=thcount)
	{
		$('#endrotation').val(rotation);
	}
	
	var thlength=$("#myTable05 th").length;
	
		if(thlength==2)
		{
			$("#myTable05 th:last").attr("width","2000");
		}
		else if(thlength==3)
		{
			$("#myTable05 th:last").attr("width","2000");
			$("#myTable05 th:last").prev().attr("width","2000");
		}
		
	showloadingalert("Rotation "+c+" added successfully.");
	setTimeout("closeloadingalert();",1000);
	$('#myTable05').fixedHeaderTable({fixedColumn: true });
        $(".modhead").css({"width":"209.5px"});
        $(".misname").css({"height":"84px"});
	
	
	$('.addmodinc').removeClass('dim');
	$.getScript('class/newclass/class-newclass-rotationalschedule.js');
	$(".clk").off();
	$(".clk").on();
        dragdrop();
}


function fn_missionremovecolumn()
{
	var trlength=$("#myTable05 tr").length-parseInt(1);
	
	var thlength=$("#myTable05 th").length;
        
	var row="true";
		for(zi=2;zi<=trlength;zi++)
		{
			for(zj=thlength;zj<=thlength;zj++)
			{
                            
				if(($('#seg1_'+zi+"_"+zj).html()!='&nbsp;') || ($('#seg2_'+zi+"_"+zj).html()!='&nbsp;') || ($('#seg3_'+zi+"_"+zj).html()!='&nbsp;') || ($('#seg4_'+zi+"_"+zj).html()!='&nbsp;'))
				{
                                    alert($('#seg1_'+zi+"_"+zj).html()+"~"+'#seg1_'+zi+"_"+zj);
					row="false";
				}
			}
		}
		
		if(row=="false")
		{
			$.Zebra_Dialog('Students scheduled in this rotation');
		}
		else
		{
		
	         $.Zebra_Dialog('Are you sure you want to delete this rotation ?',
		 {
		'type':     'confirmation',
		'buttons':  [
						{caption: 'No', callback: function() { }},
						{caption: 'Yes', callback: function() { 
									
		$('#myTable05').fixedHeaderTable('destroy');	
		var thlength=$("#myTable05 th").length;
		var rotationlength=$('#noofrotation').val()+2;
		
		if(thlength==rotationlength)
		{
		showloadingalert("can't remove this rotation");
		setTimeout('closeloadingalert()',1000);
		}
		else
		{
		$("#myTable05 th:last").remove();
		var tr=$("#myTable05 tr").length;
		var i='';
		for(i=1;i<=tr;i++)
		{
		$("#tr_"+i+" td:last").remove();
		}
		
		$("#addmod td:last").remove();
		
		var rotation=parseInt($('#noofrotation').val())-parseInt(1);
		$('#noofrotation').val(rotation);
		$('#endrotation').val(rotation);
		
		thlength=thlength-1;
		showloadingalert("Rotation "+thlength+" deleted successfully.");
		setTimeout("closeloadingalert();",1000);
		}
		
		var thlength=$("#myTable05 th").length;
	
		if(thlength==2)
		{
			$("#myTable05 th:last").attr("width","2000");
		}
		else if(thlength==3)
		{
			$("#myTable05 th:last").attr("width","2000");
			$("#myTable05 th:last").prev().attr("width","2000");
		}
		
		$("#myTable05 th:last").attr("title","Remove rotation");
		$("#myTable05 th:last").attr("onclick","fn_missionremovecolumn()"); 
		$("#myTable05 th:last").css("cursor","pointer");

		$('#myTable05').fixedHeaderTable({fixedColumn: true });	
                $(".modhead").css({"width":"209.5px"});
		$('.addmodinc').removeClass('dim');
                $(".misname").css({"height":"84px"});
	}},
					]
		});
		return false;
		}
}

function fn_addstudenttotdmission(id,name)
{
	$('#save').removeClass('dim');
	var trlength=$("#myTable05 tr").length;
	var thlength=$("#myTable05 tr:first th").length;
	var getdivid=$('#tdval').val();
	var cellid=$('#tdval').val();
        var content='';
	var stuid=id;
        
	getdivid=getdivid.split("_");
	
		var rowclassname= $('#stu_'+getdivid[1]+getdivid[2]).closest('tr').attr('class');
                
                alert(rowclassname);
                
                var getmodid=rowclassname.split("-");
                
                var stumod=getmodid[0]+"-"+stuid
                
                if($('#seg1_'+getdivid[1]+"_"+getdivid[2]).html()=='<span id="'+stuid+'">'+name+'</span>' || $('#seg2_'+getdivid[1]+"_"+getdivid[2]).html()=='<span id="'+stuid+'">'+name+'</span>' || $('#seg3_'+getdivid[1]+"_"+getdivid[2]).html()=='<span id="'+stuid+'">'+name+'</span>' || $('#seg4_'+getdivid[1]+"_"+getdivid[2]).html()=='<span id="'+stuid+'">'+name+'</span>')
		{
			var modulename=$("#module_"+getdivid[1]).html();
			var i=getdivid[2]-1;
			$.Zebra_Dialog("<div style='text-align:left'>"+name+' is  already assigned to '+modulename+ ' in rotation '+i+"</div>");
                        $('.ZebraDialog').css({"width":"500px"});
			return false;
		}
		else
		{
				/* check column */
				for(j=2;j<trlength;j++)
				{
					if(($('#seg1_'+j+"_"+getdivid[2]).html()=='<span id="'+stuid+'">'+name+'</span>') || ($('#seg2_'+j+"_"+getdivid[2]).html()=='<span id="'+stuid+'">'+name+'</span>') || ($('#seg3_'+j+"_"+getdivid[2]).html()=='<span id="'+stuid+'">'+name+'</span>') || ($('#seg4_'+j+"_"+getdivid[2]).html()=='<span id="'+stuid+'">'+name+'</span>'))
					{
						var i=getdivid[2]-1;
						$.Zebra_Dialog("<div style='text-align:left'>"+name +' is already in Rotation '+i+' Add anyway ?'+"</div>",
								 {
								'type':     'confirmation',
								'title':    'Duplicate rotation confirmation',
								'buttons':  [
												{caption: 'No', callback: function() { $('.popuptable').hide(); 
                                                                                                   
                                                                                                }},
												{caption: 'Yes', callback: function() { $('.popuptable').hide();
													var tdid=$('#tdval').val();
                                                                                                        
													if($('#'+tdid).html()=='' || $('#'+tdid).html()=='<span>&nbsp;</span>' || $('#'+tdid).html()=='&nbsp;')
													{
													$('#'+tdid).html("<span id="+stuid+">"+name+"</span>");
													fillcolor(getdivid[0],cellid);
                                                                                                        
												}
											}},
												
											]
								});
                                                                $('.ZebraDialog').css({"width":"500px"});
								return false;
					}
					else
					{
						var column="true";
					}
				}
		
			/* check row */
			var row='';
			for(zi=2;zi<trlength;zi++)
			{
				for(zj=2;zj<=thlength;zj++)
				{
					var rowclassnamedup = $('#stu_'+zi+zj).closest('tr').attr('class');
						if(rowclassname==rowclassnamedup)
						{
						if(($('#seg1_'+zi+"_"+zj).html()=='<span id="'+stuid+'">'+name+'</span>') || ($('#seg2_'+zi+"_"+zj).html()=='<span id="'+stuid+'">'+name+'</span>') || ($('#seg3_'+zi+"_"+zj).html()=='<span id="'+stuid+'">'+name+'</span>') || ($('#seg4_'+zi+"_"+zj).html()=='<span id="'+stuid+'">'+name+'</span>'))
						{
							
							var modulename=$("#module_"+getdivid[1]).html()
							$.Zebra_Dialog( "<div style='text-align:left'>"+ name +' is already in '+modulename+' Add anyway ?'+"</div>",
							 {
							'type':     'confirmation',
							'title':    'Duplicate content confirmation',
							'buttons':  [
											{caption: 'No', callback: function() { 
                                                                                                
                                                                                            }},
											{caption: 'Yes', callback: function() { $('.popuptable').hide();
												var tdid=$('#tdval').val();
												if($('#'+tdid).html()=='' || $('#'+tdid).html()=='<span>&nbsp;</span>' || $('#'+tdid).html()=='&nbsp;')
												{
													
												$('#'+tdid).html("<span id="+stuid+">"+name+"</span>");
												fillcolor(getdivid[0],cellid);
                                                                                               
												}
												}},
											
										]
							});
                                                        $('.ZebraDialog').css({"width":"500px"});
							return false;
						}
						else
						{
							row="true";	
							
						}
					}
				}
			}
				
                        if(column=="true" && row=="true")
			{
				$('.popuptable').hide();
				var tdid=$('#tdval').val();
				if($('#'+tdid).html()=='' || $('#'+tdid).html()=='<span>&nbsp;</span>' || $('#'+tdid).html()=='&nbsp;')
				{
				$('#'+tdid).html("<span id="+stuid+">"+name+"</span>");
				fillcolor(getdivid[0],cellid);
                                
				}
			}
		}
		
	}
        
        
        
      function fn_missiongenerate(rotflag)
      {
	
	$('#save').removeClass('dim');
	$('#endaddbtn').addClass("dim");
	$('#endsubbtn').addClass("dim");
	$('#staddbtn').addClass("dim");
	$('#stsubbtn').addClass("dim");
	$('#addrotimg').addClass('dim'); 
	$('.addmodinc').addClass('dim');
	$('div.clk').removeClass('lightrot darkrot');
	var trcount=$("#myTable05 tr").length-2;
	var thcount=$("#myTable05 th").length-1;
	var totcount=trcount*parseInt(2);
	var studentcount=$('#studentcount').val();
        var numberofcopies=$('#numberofcopies').val();
        
	
	if(totcount==studentcount)
	{
		var combination="true";
	}	
	
	var newstu = $('#stuidname').val();
	var tempstuori ='';
        var disstu='';

	var stucountcheck=$('#studentcount').val();
        var countflag=0;
	
        if(parseInt($('#studentcount').val())%parseInt(2)!=0)
        {
            var stucountcheck=parseInt($('#studentcount').val())+parseInt(1);
            tempstuori = ",Student NN0"+"~0";
            var tem = newstu+tempstuori;
            $('#stuidname').val(tem);
            countflag=1;
        }
        
        var newstu = $('#stuidname').val();
	var tempstu ='';
       
        if(parseInt(stucountcheck)%parseInt(4)!=0)
        {
            var recount=parseInt(stucountcheck)%parseInt(4);
            var j=0;
            if(countflag==1)
            {
                j=j+parseInt(1);
            }
            for(i=0;i<recount;i++)
            {
                
                    if(tempstu=='')
                    {
                            tempstu = ",Student NN"+j+"~0";
                    }
                    else
                    {
                            tempstu = tempstu+",Student NN"+j+"~0";
                    }
                    
                    j++;
            }
		
            var tem = newstu+tempstu;
            $('#stuidname').val(tem);
            
            
        }
            
        var combination="true";
       
	
        if(rotflag!="gen")
	{
		showloadingalert("Generating the Rotational schedule.");	
	}
	
	var stuname=$('#stuidname').val().split(',');
	
        if($('#samegroup').is(':checked'))
        {
	    var stunamecopy = stuname; 
        }
        else
        {
	stuname = arrayShuffle(stuname);  // shuffle the array
	var stunamecopy = stuname;  // shuffle the array
        }
	

	var trlength=$("#myTable05 tr").length;
	var thlength=$("#myTable05 tr:first th").length;
	var trcount=$("#myTable05 tr").length-2;
	var thcount=$("#myTable05 th").length-1;
	var modrotcount=trcount*thcount;
	var totseats=modrotcount*2;
	var totstucount=thcount*studentcount;
	
	var start = $('#startrotation').val();
	start = parseInt(start)+parseInt(1);
	var end = $('#endrotation').val();	
	end = parseInt(end)+parseInt(1);
	
	
	for(i=start;i<=thcount+1;i++)
	{
		$('.row'+i).empty();
		$('.row'+i).html('&nbsp;');
	}
        
        var seatno=new Array();
        var z=0;
        var k=2;
        for(k=2;k<=trcount+parseInt(1);k++)
        {
            seatno[z]=k;
            z++;
        }
        
       seatno = arrayShuffle(seatno);
       
                i=start;
		
                var retry=0;
		var pairstudenttemp=new Array();
		var pairstudentper=new Array();
		var studettemp=new Array();
                var prevrotflagten=0;
                var prevrotflagtwenty=0;
		while(i<=end)
		{
			if($("input[name='group']:checked").val()==1)
		        {
                            stuname=stunamecopy;
                        }
                        else
                        {
			stuname = arrayShuffle(stunamecopy);
                        }
			
			var j=2;
			var zkk=0;
                        var p=0;
                        
                        if(i!=2)
                        {
                                arraymove(seatno,0,trcount);
                                }
                       
			while(j<trlength)
			{
                           
                                var rowclassname= $('#stu_'+seatno[p]+i).closest('tr').attr('class');
				if($('#seg1_'+seatno[p]+"_"+i).html()!="&nbsp;" && $('#seg2_'+seatno[p]+"_"+i).html()!="&nbsp;" || trcount+parseInt(1)<i)
				{
					// don't insert the student zk	
					segcol="false";
					segrow="false";
					break;
								
				}
				else
				{
					
					var noofstudentscombchk=0;
					var zkforloopbreakchk=0;
					for(zk=0;zk<stuname.length;zk++) 
					{
						// check the first segment,check the first segment for empty,check the first segment with student zk
						var splitstuname=stuname[zk].split("~");
						for(zk1=zk+1;zk1<stuname.length;zk1++) // for loop starts
						{
						    var splitstuname1=stuname[zk1].split("~");
                                                    for(zk2=zk1+1;zk2<stuname.length;zk2++) // for loop starts
						    {
						         var splitstuname2=stuname[zk2].split("~");
                                                        for(zk3=zk2+1;zk3<stuname.length;zk3++) // for loop starts
                                                        {
						            var splitstuname3=stuname[zk3].split("~");
                                                        
                                                            var m=0;
                                                            var rowid=new Array();
                                                            $.each($('.'+rowclassname), function() {
                                                                    rowid[m]= this.id;
                                                                    m++;
                                                            });
							
                                                        
							segrow=tablerowmission(rowid,i,rowclassname,'<span id="'+splitstuname[1]+'">'+splitstuname[0]+'</span>','<span id="'+splitstuname1[1]+'">'+splitstuname1[0]+'</span>','<span id="'+splitstuname2[1]+'">'+splitstuname2[0]+'</span>','<span id="'+splitstuname3[1]+'">'+splitstuname3[0]+'</span>');
                                                        
							segrowarr=segrow.split("~");
									
							if(segrowarr[0]=="true" && segrowarr[1]=="true" && segrowarr[2]=="true" && segrowarr[3]=="true")
							{
									
                                                                       $('#seg1_'+seatno[p]+"_"+i).html('<span id="'+splitstuname[1]+'">'+splitstuname[0]+'</span>');
                                                                       $('#seg2_'+seatno[p]+"_"+i).html('<span id="'+splitstuname1[1]+'">'+splitstuname1[0]+'</span>');
                                                                       $('#seg3_'+seatno[p]+"_"+i).html('<span id="'+splitstuname2[1]+'">'+splitstuname2[0]+'</span>');
                                                                       $('#seg4_'+seatno[p]+"_"+i).html('<span id="'+splitstuname3[1]+'">'+splitstuname3[0]+'</span>');
                                                                           
                                                                                
                                                                        var studet=new Array(splitstuname[0]+"~"+splitstuname[1],splitstuname1[0]+"~"+splitstuname1[1],splitstuname2[0]+"~"+splitstuname2[1],splitstuname3[0]+"~"+splitstuname3[1]);
                                                                        stuname=arraycompare(stuname,studet);
                                                                        if($("input[name='group']:checked").val()==1)
                                                                        {
                                                                           stuname=stuname;
                                                                        }
                                                                        else
                                                                        {
                                                                        stuname = arrayShuffle(stuname);
                                                                        }
                                                                        zkforloopbreakchk=1;
                                                                        break;
                                                                               
									
                                                        }  // Row check if end 
                                                        
                                                        } // fourth for loop end
                                                        
                                                        if(zkforloopbreakchk==1)
                                                        {
                                                                break;
                                                        }
                                                        
                                                    } // Third for loop ends
                                                    
                                                    if(zkforloopbreakchk==1)
                                                    {
                                                            break;
                                                    }
                                                    
                                                 } // second for loop ends
                                                 
                                            if(zkforloopbreakchk==1)
                                            {
                                                    break;
                                            }
					}   // first for loop end
				} // main else end
				
				if(zkforloopbreakchk==0)
				{
					var count=1;
				}
                               
                                if(stuname.length==0)
                                {
                                    count=0;
                                    break;
                                }
				j++;
				zkk++;
                                p++;
			
					
			}
							// Check empty seats
							var seat='';
							if(combination=="true" && i<trlength)
							{
								if(retry==30)
								{
                                                                    
                                                                        $('.row'+i).empty();
									$('.row'+i).html('&nbsp;');									
                                                                        closeloadingalert();
                                                                        $('span[id^="0"]').replaceWith("&nbsp;");
									return false;
									/*******block student ***********/
									
								}
								else
								{
									if(trcount+parseInt(1)>=i)
									{
										
										if(count==1)
										{
											var seat="false";
											count=0;
										}
										
										
									}
									
									retry++;
								}
							}
							
							
							if(seat=="false")
							{
                                                           
								var i=i;
								seat='';
                                                                $('.row'+i).empty();
								$('.row'+i).html('&nbsp;');
							
							}
							else
							{
								i++;
                                                                
								retry=0;
								
								
							}
						
		}
	
        $('span[id^="0"]').replaceWith("&nbsp;");
        var s=new Array();
	s=$('#stuidname').val().split(",");
	var y=new Array();
        var temp=tempstuori+tempstu;
	y=temp.split(",");
	$('#stuidname').val(arraycompare(s,y));

        setTimeout("closeloadingalert();",1000);
                                        
                                        
}

/* Check the row if student exist return false */
function tablerowmission(trlength,i,rowclassname,studentinfo,studentinfo1,studentinfo2,studentinfo3)
{
	var seg1row='true';
	var seg2row='true';
        var seg3row='true';
        var seg4row='true';
         
	for(zi=0;zi<trlength.length;zi++)
	{
		var rowid=trlength[zi].split("_");
		for(zj=2;zj<=i;zj++)
		{
				
				if(($('#seg1_'+rowid[1]+"_"+zj).html()==studentinfo) || ($('#seg2_'+rowid[1]+"_"+zj).html()==studentinfo) || ($('#seg3_'+rowid[1]+"_"+zj).html()==studentinfo) || ($('#seg4_'+rowid[1]+"_"+zj).html()==studentinfo))
				{
					
					// don't insert the student zk	
					seg1row="false";
					
				
					break;
				}
				if(($('#seg1_'+rowid[1]+"_"+zj).html()==studentinfo1) || ($('#seg2_'+rowid[1]+"_"+zj).html()==studentinfo1) || ($('#seg3_'+rowid[1]+"_"+zj).html()==studentinfo1) || ($('#seg4_'+rowid[1]+"_"+zj).html()==studentinfo1))
				{
					
					// don't insert the student zk	
					seg2row="false";
					break;
				}
                                if(($('#seg1_'+rowid[1]+"_"+zj).html()==studentinfo2) || ($('#seg2_'+rowid[1]+"_"+zj).html()==studentinfo2) || ($('#seg3_'+rowid[1]+"_"+zj).html()==studentinfo2) || ($('#seg4_'+rowid[1]+"_"+zj).html()==studentinfo2))
				{
					
					// don't insert the student zk	
					seg3row="false";
					break;
				}
                                if(($('#seg1_'+rowid[1]+"_"+zj).html()==studentinfo3) || ($('#seg2_'+rowid[1]+"_"+zj).html()==studentinfo3) || ($('#seg3_'+rowid[1]+"_"+zj).html()==studentinfo3) || ($('#seg4_'+rowid[1]+"_"+zj).html()==studentinfo3))
				{
					
					// don't insert the student zk	
					seg4row="false";
					break;
				}
		}
		
		
		if(seg1row=="false" || seg2row=="false" || seg3row=="false" || seg4row=="false")
		{
			break;
		}
	}
        
        return seg1row+"~"+seg2row+"~"+seg3row+"~"+seg4row;
}


/* Save expedition table details */
function fn_savemissiondetails()
{
	var numberofrotation=parseInt($("#myTable05 th").length)-parseInt(1);
	var trlength=$("#myTable05 tr").length;
	var thlength=$("#myTable05 tr:first th").length;
	
        // Get expedition id and name
	var module=new Array();
	var moduleid='';
	var rotationid='';
	var modulename='';
	var studentid='';
	var studentname='';
	var cell=new Array();
	var newDt='';
	var enddate='';
	var tempdate='';
	var j=0;
	for(i=2;i<trlength;i++)
	{
		moduleid = $('#tr_'+i).attr('class');	
		modulename=$('#module_'+i).html();

		if(moduleid!='undefined')
		{		
		module[j]=moduleid;
		}
		j++;
	}
	
	var k=0;
	for(i=2;i<trlength;i++)
	{
		for(j=2;j<=thlength;j++)
		{
			
			moduleid = $('#tr_'+i).attr('class');
			rotationid=j;
			studentid=($('#seg1_'+i+"_"+j+' span').attr('id'));
			
						
			cell[k]=moduleid+"~"+rotationid+"~"+"seg1_"+i+"_"+j+"~"+studentid;
			k++;
			
			studentid=($('#seg2_'+i+"_"+j+' span').attr('id'));
			
			
			cell[k]=moduleid+"~"+rotationid+"~"+"seg2_"+i+"_"+j+"~"+studentid;
			k++;
                        
                        studentid=($('#seg3_'+i+"_"+j+' span').attr('id'));
			
			
			cell[k]=moduleid+"~"+rotationid+"~"+"seg3_"+i+"_"+j+"~"+studentid;
			k++;
                        
                        studentid=($('#seg4_'+i+"_"+j+' span').attr('id'));
			
			
			cell[k]=moduleid+"~"+rotationid+"~"+"seg4_"+i+"_"+j+"~"+studentid;
			k++;
		
		}
	}
	
	
	var dataparam="oper=saverotation&moduledet="+module+"&numberofrotation="+numberofrotation+"&celldet="+cell+"&classid="+$('#hidclassid').val()+"&scheduleid="+$('#scheduleid').val()+"&startdate="+$('#start_date').val()+"&rotlength="+$('#rotationlength').val();
	

	$.ajax({
		type: 'post',
		url: 'class/newclass/class-newclass-mission-ajax.php',
		data: dataparam,
		beforeSend: function(){
			showloadingalert("Loading, please wait.");	
		},
		success:function(data) {			
			if(data=="fail")
			{
				closeloadingalert();
				setTimeout("closeloadingalert();",1000);
			}
			else
			{
				closeloadingalert();	
				showloadingalert("Saved Sucessfully.");	
				setTimeout("closeloadingalert();",1000);
				fn_savemissionschedule(1);
			}
		}
	});
    }
