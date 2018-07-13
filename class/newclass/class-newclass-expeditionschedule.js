/* show the expedition schedular form */
function fn_exploadcontent(lid,sid,type)
{
                if(type==0)
                {
                   sid=$('#expschtemplateid').val();
                }
	
		dataparam="oper=exploadcontent&lid="+lid+"&sid="+sid+"&classid="+$('#hidclassid').val()+"&type="+type;	
		$.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-expedition-ajax.php',
			data: dataparam,		
			success:function(ajaxdata) {
				var classid=$('#hidclassid').val();
				$('#rotcontent').html(ajaxdata);	
                                fn_expblockstudent();
				if(sid!=0)
				{
					if($('#rotationtype').val()=="update")
					{
					setTimeout('showpageswithpostmethod("class-newclass-viewschedule_editexp","class/newclass/class-newclass-viewschedule_editexp.php","id='+sid+","+classid+'");',500);	
					}
					
				}		
			}
		});	
}

/* get Expeditions based on license id */
function fn_loadexpedition(scheduleid,type,asstype)
{
	var lid = $('#licenseid').val();		
		dataparam="oper=loadexpedition&licenseid="+lid+"&scheduleid="+scheduleid+"&moduletype="+type+"&assigntype="+asstype;		 	
		$.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-expedition-ajax.php',
			data: dataparam,		
			success:function(ajaxdata) {
				$('#expeditions').html(ajaxdata);
				$('#modnxtstep').show();
				
                                fn_blockexpeditions();
				if(scheduleid>0)
				{
                                        fn_showschinlineass();
					fn_rotloadexpextendcontent(scheduleid,lid,"exc");
					fn_rotloadexpextendcontent1(scheduleid,lid,"exc"); // created by chandru load expedition schedule list
				}
                                else
                                {
                                    fn_checking();
                                }
				
			}
		});
} 

/* get Block students list */
function fn_expblockstudent()
{

    var list10 = [];
    var lid = $('#licenseid').val();
    
    if($('#studenttype').val()==2)
    {
        $("div[id^=list10_]").each(function()
        {
           list10.push($(this).attr('id').replace('list10_',''));

        });
    }
    
 
    dataparam="oper=blockstudents&licenseid="+lid+"&scheduleid="+$('#scheduleid').val()+"&classid="+$('#hidclassid').val()+"&students="+list10+"&studenttype="+$('#studenttype').val()+"&blockexpedition="+$('#selectblockexpedition').val();	
    
    $.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-expedition-ajax.php',
			data: dataparam,		
			success:function(ajaxdata) {
				$('#blockstudent').html(ajaxdata);
                        }
		});
}

/* Get Blocked Expeditions */
function fn_blockexpeditions()
{
    var lid = $('#licenseid').val();
    var list4 = [];
    
    $("div[id^=list4_]").each(function()
    {
            list4.push($(this).attr('id').replace('list4_',''));
            
    });
    
  
    dataparam="oper=blockexpeditions&licenseid="+lid+"&scheduleid="+$('#scheduleid').val()+"&expeditions="+list4;	
    
    $.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-expedition-ajax.php',
			data: dataparam,		
			success:function(ajaxdata) {
				$('#blockexpeditions').html(ajaxdata);
                        }
		});
}

/* Extend content of the expeditions */

function fn_rotloadexpextendcontent(scheduleid,licenseid,type)
{
    console.log("fn_rotloadexpextendcontent");
    var list4 = [];	 //module id

    $("div[id^=list4_]").each(function()
    {
        list4.push($(this).attr('id').replace('list4_',''));
    });
    console.log(list4);
    setTimeout(function(){
	if((type=="mod" && $('#excflag').val()==1) || type=="exc")
	{
	var list4 = [];	 //module id
	
	$("div[id^=list4_]").each(function()
	{
			list4.push($(this).attr('id').replace('list4_',''));
	});
        console.log(list4);
	if(list4=='' && type=="exc"){
		alert("Please select any one Expedition");
		return false;
	}
	var dataparam = "oper=loadextendcontent&list4="+list4+"&scheduleid="+scheduleid+"&licenseid="+licenseid;	
	
	$.ajax({
		type: 'post',
		url: "class/newclass/class-newclass-expedition-ajax.php",
		data: dataparam,
		beforeSend: function(){			
		},
		success:function(data) {
			console.log(data);
			$('#expextendcontent').html(data);
		}
		
	});	
	}
    }, 1000);
}

/* New Extend content of the expeditions created by chandru start line created date 05-01-2016 */

function fn_rotloadexpextendcontent1(scheduleid,licenseid,type)
{
		
	if((type=="mod" && $('#excflag').val()==1) || type=="exc")
	{
	var list4 = [];	 //module id
	
	$("div[id^=list4_]").each(function()
	{
			list4.push($(this).attr('id').replace('list4_',''));
	});
		
	if(list4=='' && type=="exc"){
		alert("Please select any one Expedition");
		return false;
	}
	var dataparam = "oper=loadexpextendcontent1&list4="+list4+"&scheduleid="+scheduleid+"&licenseid="+licenseid;			
	
	$.ajax({
		type: 'post',
		url: "class/newclass/class-newclass-expedition-ajax.php",
		data: dataparam,
		beforeSend: function(){			
		},
		success:function(data) {
			$('#expextendcontent').html(data);
		}
		
	});	
	}
}

function fn_fillnameforexpsc(rowid,expid)
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

function fn_selectallexpsc(expid)
{	
    $('#selectallexp_'+expid).val('01');
    var finalmodname='Select All';
    $('#expname_'+expid).html(finalmodname);
    
    $('.ads_Checkbox_'+expid).prop('checked', false); // Unchecks it
    
    
}

/* New Extend content of the expeditions created by chandru end line */

/* Block expedition to students */
function fn_blockexpstudent()
{
    
    if($('#hidscheduleid').val()!='' && $('#hidscheduleid').val()!='0')
    {
    var list26=[];
    
    $("div[id^=list26_]").each(function()
    {
            list26.push($(this).attr('id').replace('list26_',''));
    });
    
    
    
    dataparam="oper=blockmodstudents&scheduleid="+$('#scheduleid').val()+"&classid="+$('#hidclassid').val()+"&students="+list26+"&blockmodule="+$('#selectblockexpedition').val();	
    
    $.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-expedition-ajax.php',
			data: dataparam,		
			success:function(ajaxdata) {
				showloadingalert("Saved Sucessfully.");	
				setTimeout("closeloadingalert();",1000);
                                fn_blockexpeditions();
                                setTimeout("fn_expblockstudent();",1000);
                        }
		});
            }
            else
            {
                fn_saveexpeditionalschedule(2);
            }
}


/* save the Expedition schedular details */
function fn_saveexpeditionalschedule(flag)
{
	$('#enddate').val('03/03/3000');
	if($("#scheduleform").validate().form() && $("#sform").validate().form())
	{
			
		var list10 = [];
		var list4 = [];
		var list9=[];
                var list26=[];
		var extids = [];
		
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
		
                $("div[id^=list26_]").each(function()
		{
			list26.push($(this).attr('id').replace('list26_',''));
		});
		
		$("div[id^=list4_]").each(function()
		{
			list4.push($(this).attr('id').replace('list4_',''));
		});
		
		/* expedition code added by chandru start line */
		$("input[id^=exid_]").each(function()
		{
			extids.push($(this).val());			
		});
		/* added by chandru start line */
	
		 /***********Chandru Updated by [18-12-2015] one or more Extend Content option code start here*********/
			$("input[id^=mod_]").each(function()
			{
					var mlessonid=$(this).val();
					var mlessonid=$(this).attr('name').replace('mod_','');
					if($('#mod_'+mlessonid).is(':checked')){
						expids.push(mlessonid);

					}
			});

			$("input[id^=selectallexp_]").each(function()
			{
					var mlessonid1=$(this).val();
					var mlessonid1=$(this).attr('id').replace('selectallexp_','');					
					 var existmod=$('#selectallexp_'+mlessonid1).val();
					 if(existmod=='01'){
						var selallexpids=$(this).attr('name').replace('selectallexp_','')+"~";					    
						selectallexpids.push(selallexpids);
					 }
			});		
		  
	   /***********Chandru Updated by [18-12-2015] one or more Extend Content option code end here*********/
		
	
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
			modcount=modcount*parseInt(2)*parseInt($('#numberofcopies').val());
		}
		else
		{
			modcount=modcount*parseInt(2);
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
		 $.Zebra_Dialog('<strong>Please select anyone Expedition</strong>', {
		'buttons':  false,
		'auto_close': 3000
		});
		return false;
		}
		
		
		$("input[id^=exid_]").each(function()
		{
			extids.push($(this).val());
		});
                
                if($('#numberofrotations').val()>17)
                {
                    $.Zebra_Dialog("Total number of rotations must be</br>less than or equal to 17.</br>", { 'type': 'information'});
                    return false;
                }
                
                if(list4.length > 25)
                {
                    $.Zebra_Dialog("Total number of titles must be<br> less than or equal to 25 per class schedule.</br>", { 'type': 'information'});
                    return false;
                }
		
		
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
		var stype = $('#scheduletype').val();
		var dataparam="oper=saveschedule&sname="+sname+"&startdate="+$('#startdate').val()+"&scheduletype="+stype+"&studenttype="+$('#studenttype').val()+"&numberofcopies="+$('#numberofcopies').val()+"&numberofrotations="+$('#numberofrotations').val()+"&rotationlength="+$('#rotationlength').val()+"&sid="+$('#hidscheduleid').val()+"&students="+list10+"&expeditions="+list4+"&classid="+$('#hidclassid').val()+"&licenseid="+$('#licenseid').val()+"&unstudents="+list9+"&extids="+extids+"&blockmodule="+$('#selectblockexpedition').val()+"&blockstudents="+list26+"&expids="+expids+"&selectallexpids="+selectallexpids+"&selectchkboxids="+selectchkboxids+"&exptest="+exptest+"&desttest="+desttest+"&tasktest="+tasktest+"&restest="+restest+"&schflag="+flag; //add last two values by chandru
	
		$.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-expedition-ajax.php',
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
						setTimeout('showpageswithpostmethod("class-newclass-viewschedule_createexp","class/newclass/class-newclass-viewschedule_createexp.php","id='+sid+","+classid+'");',500);
						}
						else
						{
						
						setTimeout("removesections('#class-newclass-newschedulestep');",500);
						setTimeout('showpageswithpostmethod("class-newclass-viewschedule_editexp","class/newclass/class-newclass-viewschedule_editexp.php","id='+sid+","+classid+'");',500);
							
						}
					}
					else if(flag==1)
					{
							setTimeout("removesections('#class-newclass-steps');",500);	
							setTimeout("removesections('#class-newclass-actions');",500);			
						setTimeout('showpageswithpostmethod("class-newclass-scheduledetails","class/newclass/class-newclass-scheduledetails.php","id='+$('#hidclassid').val()+","+$('#classtypeval').val()+'");',500);
						setTimeout('showpageswithpostmethod("class-newclass-viewschedule_editexp","class/newclass/class-newclass-viewschedule_editexp.php","id='+sid+","+stype+","+classid+","+sname+',viewrot'+'");',1000);
						
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

/* Get Blocked Expeditions */

function fn_autoblockexp(sid)
{
    $('#autoblockstu').val('null');

    if($('#autoblock').is(':checked'))
    {
        var trlength=$("#myTable05 tr").length;
        var j=0;
        var expid='';
        var expedition=new Array();
        $('#generatebtn').addClass('dim');

        for(i=2;i<trlength;i++)
	{
		expid = $('#tr_'+i).attr('class');	

                if(expid!='undefined')
		{
                    expedition[j]=expid;
		}
		j++;
	}
                                                                      
        dataparam="oper=autoblock"+"&scheduleid="+sid+"&expid="+expedition;
						
        $.ajax({
        type: 'post',
        url: 'class/newclass/class-newclass-expedition-ajax.php',
        data: dataparam,
        success:function(data) {
                if(data=='')
                {
                    data="null";
                }
                $('#autoblockstu').val(data);
                $('#generatebtn').removeClass('dim');

                }
        });
        
    }
}


/* Generate the student */

function fn_expgenerate(rotflag)
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
        $('.dragdrop').removeAttr("style");
        $('.rowspanone span').removeClass("dragdrop ui-draggable ui-droppable");
        $('.rowspantwo span').removeClass("dragdrop ui-draggable ui-droppable");
        $('.rowspanone span').removeAttr("class");
        $('.rowspantwo span').removeAttr("class");
	
	if(totcount==studentcount)
	{
		var combination="true";
	}	
	
	
	var newstu = $('#stuidname').val();
	var tempstu ='';
        var disstu='';
	
        if($('#packed').is(':checked'))
	{
            if(parseInt($('#studentcount').val())%parseInt(2)!=0)
		{
                tempstu = ",Student NN1"+"~0";
                var tem = newstu+tempstu;
                $('#stuidname').val(tem);
			}
            
            var combination="true";
        }
			else
			{
            var y = $('#stuidname').val().split(',');
            var removeItem = "Student NN1~0";
		
            y = jQuery.grep(y, function(value) {
            return value != removeItem;
            });
        
            $('#stuidname').val(y);
        }
        
        if(rotflag!="gen")
	{
		showloadingalert("Generating the Expedition schedule.");	
	}
	
	var stuname=$('#stuidname').val().split(',');
	
	stuname = arrayShuffle(stuname);  // shuffle the array
	var stunamecopy = arrayShuffle(stuname);  // shuffle the array
	

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
        for(k=2;k<=trcount+parseInt(1);k++)
        {
            seatno[z]=k;
            z++;
        }

	
	i=start;
		if(combination!="true")
		{
		var retry=0;
		var pairstudenttemp=new Array();
		var pairstudentper=new Array();
                var blostu=$('#blockstu').val();
                var blockmodstu=JSON.parse(blostu);
                                                        
                var autoblockstu=$('#autoblockstu').val();
                var autoblockmodstu=JSON.parse(autoblockstu);

		while(i<=end)  // Row 
		{
			stuname = arrayShuffle(stunamecopy);
			var j=2;
			var p=0;
                        seatno=arrayShuffle(seatno); 
			var tk=0;
			var count=0;	
			while(j<trlength) // column
			{
                            
                            
				var rowclassname= $('#stu_'+seatno[p]+i).closest('tr').attr('class');
				for(zk=0;zk<stuname.length;zk++) 
				{
					
					// check the first segment,check the first segment for empty,check the first segment with student zk
					var splitstuname=stuname[zk].split("~");
					if($('#seg1_'+seatno[p]+"_"+i).html()!="&nbsp;")
					{
						// don't insert the student zk	
						break;
					}
					else
					{
						// check the same student with the same row and same column,check the same student with the same row
						
						var m=0;
						var rowid=new Array();
						$.each($('.'+rowclassname),function(){
							rowid[m]= this.id;
							m++;
							
						});
						
                                                /*******block student ***********/
                                                        var stumod = new Array();
                                                        stumod=rowclassname.split("-");
                                                        var modstuseg1=stumod[0]+"-"+splitstuname[1]//join the current module and studentid
                                                        
                                                        var a=jQuery.inArray(modstuseg1,blockmodstu);
                                                        var c=jQuery.inArray(modstuseg1,autoblockmodstu);
                                                        
                                                
                                                if(a==-1)
                                                {
                                                     /*******block student ***********/
						seg1row=tablerowfalse(rowid,i,rowclassname,'<span id="'+splitstuname[1]+'">'+splitstuname[0]+'</span>');
						
						if(seg1row=="false") // student in same row or same column 
						{
							// don't insert the student zk
						}
						else
						{
                                                       if(c==-1 || retry>50)
                                                       {
							// insert the student zk in the first segment
							$('#seg1_'+seatno[p]+"_"+i).html('<span id="'+splitstuname[1]+'">'+splitstuname[0]+'</span>');
							var seg1='<span id="'+splitstuname[1]+'">'+splitstuname[0]+'</span>';
							var studet=new Array(splitstuname[0]+"~"+splitstuname[1]);
							stuname=arraycompare(stuname,studet);
							count++;
							break;
                                                     }
							
						}
                                                }/*******block student ***********/
					}
				}
				
				
						j++;
						tk++;
                                                p++;
					
					
				} // j loop end
                                
                                if(trcount!=studentcount)
                                {
                                var j=2;
                                var p=0;
                                seatno=arrayShuffle(seatno);  
                                while(j<trlength) // column
                                {
                                var rowclassname= $('#stu_'+seatno[p]+i).closest('tr').attr('class');
				for(zk=0;zk<stuname.length;zk++) 
				{
					// check the second segment,check the second segment for empty,check the second segment with student zk
					var splitstuname=stuname[zk].split("~");
					if(($('#seg2_'+seatno[p]+"_"+i).html()!="&nbsp;"))
					{
						// don't insert the student zk	
						seg2col="false";
						seg2row="false";
						break;
					}
					else
					{
						// check the same student with the same row and same column,check the same student with the same row
						
						var n=0;
						var rowidt=new Array();
						$.each($('.'+rowclassname), function(){
							rowidt[n]= this.id;
							n++;
							
						});
						
                                                /*******block student ***********/
                                                        var stumod = new Array();
                                                        stumod=rowclassname.split("-");
                                                        var modstuseg2=stumod[0]+"-"+splitstuname[1]//join the current module and studentid
                                                        
                                                        var b=jQuery.inArray(modstuseg2,blockmodstu);
                                                        var d=jQuery.inArray(modstuseg2,autoblockmodstu);
                                                        
                                                        if(b==-1)
                                                        {     /*******block student ***********/
						seg2row=tablerowfalse(rowidt,i,rowclassname,'<span id="'+splitstuname[1]+'">'+splitstuname[0]+'</span>');
						
						if(seg2row=="false") // student in same row or same column 
						{
							// don't insert the student zk
							
						}
						else
						{
							// insert the student zk in the second segment
							var seg2='<span id="'+splitstuname[1]+'">'+splitstuname[0]+'</span>';
                                                        var seg1=$('#seg1_'+seatno[p]+"_"+i).html();
							if(containsAll(seg1,seg2,pairstudentper)=="false" && d==-1 || retry>=15)
							{
									var seg2='<span id="'+splitstuname[1]+'">'+splitstuname[0]+'</span>';
									$('#seg2_'+seatno[p]+"_"+i).html('<span id="'+splitstuname[1]+'">'+splitstuname[0]+'</span>');
									var studet1=new Array(splitstuname[0]+"~"+splitstuname[1]);
									stuname=arraycompare(stuname,studet1);
                                                                            pairstudenttemp.push(seg1+":"+seg2);
									count++;
									break;
									
							}
							
						}
                                                        }/*******block student ***********/
					}
				}
                                j++;
                                p++;
						
                            }
                                }
			
			
							var seat='';
							if(combination!="true" && numberofcopies==1 && trcount+parseInt(1)>=i)
							{
								if(retry==500)
								{
									var seat="true";
									retry=0;
                                                                        $('.row'+i).empty();
									$('.row'+i).html('&nbsp;');
                                                                        closeloadingalert();
                                                                        return false;
								}
								else
								{
									if(totseats>totstucount)
									{
										
										if(count!=studentcount)
										{
											seat="false";
										}
										retry++;
									}
									
								}
							}
							
							
							
							if(seat=="false")
							{
								var i=i;
								seat='';
								pairstudenttemp=[];
								
								$('.row'+i).empty();
								$('.row'+i).html('&nbsp;');
								
							}
							else
							{
								i++;
								retry=0;
								for(y=0;y<pairstudenttemp.length;y++)
								{
									pairstudentper.push(pairstudenttemp[y]);
								}
								pairstudenttemp=[];
							}
						
		} // i while loop end
	} // if end
	else
	{
                var blostu=$('#blockstu').val();
                var blockmodstu=JSON.parse(blostu);
                                                        
                var autoblockstu=$('#autoblockstu').val();
                var autoblockmodstu=JSON.parse(autoblockstu);
		var retry=0;
		var pairstudenttemp=new Array();
		var pairstudentper=new Array();
		var studettemp=new Array();
		while(i<=end)
		{
			
			stuname = arrayShuffle(stunamecopy);
			
			var j=2;
			var zkk=0;
                        var p=0;
                        seatno=arrayShuffle(seatno); 
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
							var m=0;
							var rowid=new Array();
							$.each($('.'+rowclassname), function() {
								rowid[m]= this.id;
								m++;
							});
							
                                                        /*******block student ***********/
                                                        //block module and studentid
                                                        
                                                        
                                                        var stumod = new Array();
                                                        stumod=rowclassname.split("-");
                                                        var modstuseg1=stumod[0]+"-"+splitstuname[1]//join the current module and studentid
                                                        var modstuseg2=stumod[0]+"-"+splitstuname1[1]//join the current module and studentid1


                                                        var a=jQuery.inArray(modstuseg1,blockmodstu);
                                                        var b=jQuery.inArray(modstuseg2,blockmodstu);
                                                        var c=jQuery.inArray(modstuseg1,autoblockmodstu);
                                                        var d=jQuery.inArray(modstuseg2,autoblockmodstu);
                                                       
                                                        if(a==-1)
                                                        {
                                                            /*******block student ***********/
									segrow=tablerow(rowid,i,rowclassname,'<span id="'+splitstuname[1]+'">'+splitstuname[0]+'</span>','<span id="'+splitstuname1[1]+'">'+splitstuname1[0]+'</span>');
									segrowarr=segrow.split("~");
									
								if(segrowarr[0]=="true" && segrowarr[1]=="true")
								{
										if(containsAll('<span id="'+splitstuname[1]+'">'+splitstuname[0]+'</span>','<span id="'+splitstuname1[1]+'">'+splitstuname1[0]+'</span>',pairstudentper)=="false" || retry>=5)
										{                                                                        
                                                                        if(c==-1 || retry>50)
                                                                        {
                                                                           
                                                                                            $('#seg1_'+seatno[p]+"_"+i).html('<span id="'+splitstuname[1]+'">'+splitstuname[0]+'</span>');
                                                                        }
                                                                         if(b==-1){
                                                                             
                                                                            if(d==-1 || retry>50)
                                                                            {
                                                                                            $('#seg2_'+seatno[p]+"_"+i).html('<span id="'+splitstuname1[1]+'">'+splitstuname1[0]+'</span>');
                                                                            }
                                                                          
                                                                         }
                                                                         
                                                                                if(a==-1 && b==-1 && c==-1 && d==-1 || retry>100)
                                                                                {
                                                                                            var studet=new Array(splitstuname[0]+"~"+splitstuname[1],splitstuname1[0]+"~"+splitstuname1[1]);
										stuname=arraycompare(stuname,studet);
										stuname = arrayShuffle(stuname);
										pairstudenttemp.push('<span id="'+splitstuname[1]+'">'+splitstuname[0]+'</span>'+":"+'<span id="'+splitstuname1[1]+'">'+splitstuname1[0]+'</span>');
										zkforloopbreakchk=1;
										break;
                                                                                }
									 }  // duplicate pair check if end
					  			}  // Row check if end 
                                                        } ///block student   if end 
						
					  } // for loop ends
					  if(zkforloopbreakchk==1)
					  {
						  break;
					  }
					}   // for loop end
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
							if(combination=="true" && i<trlength && numberofcopies==1)
							{
								if(retry==200)
								{
									/*******block student ***********/
                                                                        if($('#blockstu').val()=='null')
                                                                        {
									$('.row'+i).empty();
									$('.row'+i).html('&nbsp;');
									$.Zebra_Dialog('Too many attempts made to build schedule. Exceeded maximum 500 attempts. It may be possible to generate a schedule with an additional request. InnerException message: Run out of content for '+splitstuname[0]);
                                                                        }
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
								pairstudenttemp=[];
								studettemp=[];
								
									$('.row'+i).empty();
									$('.row'+i).html('&nbsp;');
							}
							else
							{
								i++;
								retry=0;
								for(y=0;y<pairstudenttemp.length;y++)
								{
									pairstudentper.push(pairstudenttemp[y]);
								}
								pairstudenttemp=[];
								studettemp=[];
								
							}
						
		}
	}
        
        $('span[id^="0"]').replaceWith("<span class='dragdrop'>&nbsp;</span>");
        $('.rowspanone span').addClass("dragdrop");
        $('.rowspantwo span').addClass("dragdrop");
        dragdrop();
	var y = $('#stuidname').val().split(',');
        var removeItem = "Student NN1~0";
	
        y = jQuery.grep(y, function(value) {
        return value != removeItem;
        });

        $('#stuidname').val(y);
	
						if($('#autoblock').is(':checked'))
						{
						var k=0;
						var cell=new Array();
						var trcount=$("#myTable05 tr").length-1;
						var thcount=$("#myTable05 th").length;
						for(i=2;i<=trcount;i++)
						{
							for(j=start;j<=thcount;j++)
							{
								
								moduleid = $('#tr_'+i).attr('class');
								rotationid=j;
								studentid=($('#seg1_'+i+"_"+j+' span').attr('id'));
								studentname=$('#'+studentid).html();
											
								cell[k]=moduleid+"~"+studentid;
								k++;
								
								studentid=($('#seg2_'+i+"_"+j+' span').attr('id'));
								studentname=$('#'+studentid).html();
								
								cell[k]=moduleid+"~"+studentid;
								k++;
							
							}
						}
						
						dataparam="oper=checkstudentmod"+"&celldet="+cell+"&scheduleid="+$('#scheduleid').val()+"&operation=generate";
						
						$.ajax({
						type: 'post',
						url: 'class/newclass/class-newclass-expedition-ajax.php',
						data: dataparam,
						success:function(data) {
								if(data!="")
								{
									$.Zebra_Dialog("<div style='text-align:left'>"+data+"</div>",
                                                                         {
                                                                        'type':     'confirmation',
                                                                        'buttons':  [
                                                                                                        {caption: 'OK', callback: function() {
                                                                                                            $('.rowspanone').empty();
                                                                                                            $('.rowspantwo').empty();
                                                                                                            $('.rowspanone').html('&nbsp;');
                                                                                                            $('.rowspantwo').html('&nbsp;');
                                                                                                        }},
                                                                                                        {caption: 'Show Details', callback: function() { 

                                                                                                               setTimeout("fn_showdetailsexp();",2000);
                                                                                                        }},
                                                                                                ]
                                                                        });
									$('.ZebraDialog').css({"left":"370px","width":"800px"});
									
									setTimeout("closeloadingalert();",1000);
								}
								else
								{
									setTimeout("closeloadingalert();",1000);
								}
							}
						});
						
					}
					else
					{
						setTimeout("closeloadingalert();",1000);
					}
}



/* Save expedition table details */
function fn_saveexpdetails()
{
	var numberofrotation=parseInt($("#myTable05 th").length)-parseInt(1);
	var trlength=$("#myTable05 tr").length;
	var thlength=$("#myTable05 tr:first th").length;
	
        var list26=[];
        $("div[id^=list26_]").each(function()
        {
			list26.push($(this).attr('id').replace('list26_',''));
	});
	
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
			studentname=$('#'+studentid).html();
						
			cell[k]=moduleid+"~"+rotationid+"~"+"seg1_"+i+"_"+j+"~"+studentid;
			k++;
			
			studentid=($('#seg2_'+i+"_"+j+' span').attr('id'));
			studentname=$('#'+studentid).html();
			
			cell[k]=moduleid+"~"+rotationid+"~"+"seg2_"+i+"_"+j+"~"+studentid;
			k++;
		
		}
	}
	
	if($('#autoblock').is(':checked'))
	{
		var autoblock=1;
	}
	else
	{
		var autoblock=0;
	}
        
         if($('#packed').is(':checked'))
	{
		var generatetype=1;
	}
	else
	{
		var generatetype=2;
	}
	
	
	
        var dataparam="oper=saverotation&moduledet="+module+"&numberofrotation="+numberofrotation+"&celldet="+cell+"&classid="+$('#hidclassid').val()+"&scheduleid="+$('#scheduleid').val()+"&autoblock="+autoblock+"&startdate="+$('#start_date').val()+"&rotlength="+$('#rotationlength').val()+"&generatetype="+generatetype+"&blockmodule="+$('#selectblockexpedition').val()+"&blockstudents="+list26;
	

	$.ajax({
		type: 'post',
		url: 'class/newclass/class-newclass-expedition-ajax.php',
		data: dataparam,
		beforeSend: function(){
			showloadingalert("Loading, please wait.");	
		},
		success:function(data) {			
			if(data=="fail")
			{
				closeloadingalert();
				showloadingalert("Turn off auto block");	
				setTimeout("closeloadingalert();",1000);
			}
			else
			{
				closeloadingalert();	
				showloadingalert("Saved Sucessfully.");	
				setTimeout("closeloadingalert();",1000);
				fn_saveexpeditionalschedule(1);
			}
		}
	});
    }
    
    
/* Show expedition list in popup */ 
function fn_showexpedition(scheduleid)
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
			url		: "class/newclass/class-newclass-expedition-ajax.php",
			data		: "oper=showexpedition&scheduleid="+scheduleid+"&licenseid="+licenseid,
			success: function(data) {
				$.fancybox(data);
			}
		});
	
		return false;
}


/* Add expedition to grid table */
function fn_addexpedition(expid,scheduleid,type)
{
	$.fancybox.close();
        var numberofrotation=parseInt($("#myTable05 th").length)-parseInt(1);
	var dataparam = "oper=addexpedition&expid="+expid+"&trlength="+$("#myTable05 tr:last").prev().attr('id')+"&thlength="+$("#myTable05 tr:first th").length+"&scheduleid="+scheduleid+"&scheduletype="+$('#scheduletype').val()+"&type="+type+"&classid="+$('#hidclassid').val()+"&numberofrotation="+numberofrotation;

	
	$.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-expedition-ajax.php',
			data: dataparam,
			beforeSend: function(){
				showloadingalert("Loading, please wait.");	
			},		
			success:function(data) {
				
				$('#myTable05').fixedHeaderTable('destroy');	
				setTimeout("closeloadingalert();",1000);
				showloadingalert("Expedition added to table.");
				setTimeout("closeloadingalert();",1000);
					$('#body tr:last').remove();
					$('#body').append(data);
					$('#myTable05').fixedHeaderTable({fixedColumn: true });
					$.getScript('class/newclass/class-newclass-rotationalschedule.js');
					$(".clk").off();
					$(".clk").on();
					var stu=$('#studentcount').val();
					var modcount=($("#myTable05 tr").length-parseInt(2))*parseInt(2);
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
                                        fn_checking();
			}
		});	
}

/* The user hover the Expedition if without students in expedition  title,style and function assigned to expedition */

function fn_checkcellvalueexp(rowid)
{
	
	var thlength=$("#myTable05 tr:first th").length;
	var row='true';
		for(zi=rowid;zi<=rowid;zi++)
		{
			for(zj=2;zj<=thlength;zj++)
			{
                            
				if(($('#seg1_'+zi+"_"+zj).html()!='&nbsp;') && ($('#seg1_'+zi+"_"+zj).html()!='<span class="dragdrop ui-draggable ui-droppable">&nbsp;</span>') || ($('#seg2_'+zi+"_"+zj).html()!='&nbsp;') && ($('#seg2_'+zi+"_"+zj).html()!='<span class="dragdrop ui-draggable ui-droppable">&nbsp;</span>'))
				{
					// don't add title and function
					row="false";
				}
			}
		}
		
		
		if(row=="true")
		{
			$("#module_"+rowid).attr("title","Remove a Expedition");
			$("#module_"+rowid).attr("onclick","fn_removeexpedition("+rowid+")");
			$("#module_"+rowid).css("cursor","pointer");
				
		}
		else
		{
			$("#module_"+rowid).removeAttr("title");
			$("#module_"+rowid).attr("onclick"," ");
			$("#module_"+rowid).css("cursor","default");
		}
	
}

function fn_checkcellvalueoutexp(id) 
{
	var val=$('#'+id).attr('title');
	if(val=="Remove a Expedition")
	{
		$('#'+id).removeAttr('title');
	}
}

/* Remove Expedition to table */	
function fn_removeexpedition(rowid)
{		
    var rowclassname=$('#tr_'+rowid).attr('class');
                                                       
                                                        var m=0;
                                                        var rowidm=new Array();
                                                        $.each($('.'+rowclassname),function(){
                                                                var id=this.id.split("_");
                                                                rowidm[m]= id[1]-parseInt(1);
                                                                m++;

                                                        });
                                                        
                                                        
                                                        
		$.Zebra_Dialog('Are you sure you want to delete this Expedition ?',
		 {
		'type':     'confirmation',
		'buttons':  [
						{caption: 'No', callback: function() { }},
						{caption: 'Yes', callback: function() { 
						
							$('#myTable05').fixedHeaderTable('destroy');		
							$("#tr_"+rowid).remove();
							$('#myTable05').fixedHeaderTable({fixedColumn: true });	
							var stu=$('#studentcount').val();
							var modcount=($("#myTable05 tr").length-parseInt(2))*parseInt(2);
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
									$(this).children(":first").attr({id: 'module_'+rowid,onmouseover: 'fn_checkcellvalueexp('+rowid+')'});
									$(this).children().each(function(index, element) {
										$(this).html($(this).html().replace(new RegExp('_'+tid+'_','g'),'_'+rowid+'_'));
									});	
									rowid++;
									tid++;	
								}
							});						
                                                        
                                                        if(rowidm.length>0)
                                                        {
                                                           fn_removeexpeditionstable(rowid,rowclassname); 
                                                        }
						}},
					]
		});
		return false;
}


function fn_removeexpeditionstable(rowid,rowclassname)
{
    dataparam="oper=removeexpedition&exptype="+rowclassname+"&rowid="+rowid+"&scheduleid="+$('#scheduleid').val()+"&classid="+$('#classid').val();
    
    $.ajax({
        type: 'post',
        url: 'class/newclass/class-newclass-expedition-ajax.php',
        data: dataparam,
        success:function(data) 
        {
                 
             closeloadingalert();
                       
              }
        });
    
    
}


function fn_showdetailsexp()
 {
     $('.ZebraDialog').remove();
     $('.ZebraDialogOverlay').remove();
     var k=0;
    var expid='';
    var rotationid='';
    var studentid='';
    var studentname='';
    var cell=new Array();
    var trcount=$("#myTable05 tr").length-1;
    var thcount=$("#myTable05 th").length;
    for(i=2;i<=trcount;i++)
    {
            for(j=2;j<=thcount;j++)
            {

                    expid = $('#tr_'+i).attr('class');
                    rotationid=j;
                    studentid=($('#seg1_'+i+"_"+j+' span').attr('id'));
                    studentname=$('#'+studentid).html();

                    cell[k]=expid+"~"+studentid;
                    k++;

                    studentid=($('#seg2_'+i+"_"+j+' span').attr('id'));
                    studentname=$('#'+studentid).html();

                    cell[k]=expid+"~"+studentid;
                    k++;

            }
    }
    
    dataparam="oper=checkstudentmod"+"&celldet="+cell+"&scheduleid="+$('#scheduleid').val()+"&operation=showdetails";
                                                                                                               
    $.ajax({
    type: 'post',
    url: 'class/newclass/class-newclass-expedition-ajax.php',
    data: dataparam,
    success:function(data) {

        $.Zebra_Dialog("<div style='text-align:left'>"+data+"</div>");
        $('.ZebraDialog').css({"posiion":"absolute","left":"50%","top":"50%","transform":"translate(-50%,-50%)","width":"1000px"});
        $('.rowspanone').empty();
        $('.rowspantwo').empty();
        $('.rowspanone').html('&nbsp;');
        $('.rowspantwo').html('&nbsp;');

      }
   });
 }

