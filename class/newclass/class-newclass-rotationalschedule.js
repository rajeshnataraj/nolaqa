// JavaScript Document

uniq = function(items, key) {
    var set = {};
    return items.filter(function(item) {
        var k = key ? key.apply(item) : item;
        return k in set ? false : set[k] = true;
    })
 }
 
 function isNumberKey(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
    {
        return false;
    }
    return true;
}
 
 function fn_schedulegenerate()
 {
     var list4 = [];
     
     $("div[id^=list4_]").each(function()
    {
            list4.push($(this).attr('id').replace('list4_',''));
    });	
	
      if(list4.length>17)
      {
            fn_generate();
            return false;
      }
                        
     var trcount=$("#myTable05 tr").length-2;
     var totcount=trcount*parseInt(2)-parseInt(2);
     var studentcount=$('#studentcount').val();
     
     if($('#packed').is(':checked'))
     {
         if($('#blockstu').val()=="null" && $('#autoblockstu').val()=="null" && $('#numberofcopies').val()>1)
         {
             fn_generateschedule(0,0,0,"regen");
         }
         else
         {
            fn_generate();
         }
     }
     else if($('#dispersed').is(':checked'))
     {
         if(studentcount==totcount || studentcount<totcount)
         {
            if($('#blockstu').val()=="null" && $('#autoblockstu').val()=="null" && $('#numberofcopies').val()>1)
            {
                fn_dispersed();
            }
            else
            {
               fn_generate();
            }
         }
         else
         {
            if($('#blockstu').val()=="null" && $('#autoblockstu').val()=="null" && $('#numberofcopies').val()>1)
            {
                fn_generateschedule(0,0,0,"regen");
            }
            else
            {
               fn_generate();
            }
         }
     }
     else
     {
         $.Zebra_Dialog("Please select anyone checkbox Packed or Dispersed");
         return false;
     }
 }
 
 function fn_dispersed()
 {
     var moddup="";
	var trlength=$("#myTable05 tr").length-1;
	var s=0;
	for(i=2;i<=2;i++)
	{
		for(j=2;j<=trlength;j++)
		{
			var rowclassname= $('#stu_'+j+i).closest('tr').attr('class');
			var m=0;
			var rowid=new Array();
			$.each($('.'+rowclassname),function(){
				var id=this.id.split("_");
				rowid[m]= id[1]-parseInt(1);
				m++;
				
			});
			
			if(rowid.length>=2)
			{
				if(moddup=="")
				{
					moddup=rowid;
				}
				else
				{
					moddup=moddup+"~"+rowid;
				}
			}
		}
	}
	
	uniqueCoords = moddup;
        
                $('#save').removeClass('dim');
                $('DIV.clk').removeClass('darkrot lightrot');
                $('#addrotimg').addClass('dim'); 
		$('.addmodinc').addClass('dim');
		
		
		var start = $('#startrotation').val();
		var startval=parseInt(start)+parseInt(1);
		var end = $('#endrotation').val();	
		var rot=$("#myTable05 th").length-1;
		var mod=$("#myTable05 tr").length-2;
                var stu=$('#studentcount').val();
                var totcount=mod*parseInt(2)-parseInt(2);
                
                if(start!=1 && stu>totcount && $('#schtype').val()=="edit" || start==1 && end==1)
		{
			fn_generate();
			return false;
		}
                else if(start!=1 && stu<=totcount && $('#schtype').val()=="edit")
                {
                        fn_generatedispersed();
			return false;
                }
		else
		{
                        $('.rowspanone').empty();
			$('.rowspantwo').empty();
			$('.rowspanone').html('&nbsp;');
			$('.rowspantwo').html('&nbsp;');
                }
                
                var s=new Array();
		s=$('#stuidname').val().split(",");
		var y=new Array('Student NN0~0');
				 
		$('#stuidname').val(arraycompare(s,y));
                
                
                	var dataparam = "oper=generatedispersed&startrot="+start+"&endrot="+end+"&module="+mod+"&student="+stu+"&moddupid="+uniqueCoords+"&scheduleid="+$('#scheduleid').val();
	
			$.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-rotation-ajax.php',
			data: dataparam,
			beforeSend:function(){
				showloadingalert('Generating the rotational schedule.');
			},
			success:function(data) {
				
				if(data!="false")
				{
					
					$('#endaddbtn').addClass("dim");
					$('#endsubbtn').addClass("dim");
					$('#staddbtn').addClass("dim");
					$('#stsubbtn').addClass("dim");
                                        
                                        var start = $('#startrotation').val();
                                        var startval=parseInt(start)+parseInt(1);
                                        var endval = $('#endrotation').val();
                                        var trcount=$("#myTable05 tr").length-2;
					
                                        var parsed=JSON.parse(data);
					
					stuidname=$('#stuidname').val().split(",");
					
                                        for(mod=2;mod<=trcount+1;mod++)
					{
						for(rot=startval;rot<=endval+1;rot++)
						{
							
                                                        var modid=mod-parseInt(1);
                                                        var rotid=rot-parseInt(1);

                                                            if(parsed[modid+','+rotid]!=undefined)
                                                            {
                                                                if($('#seg1_'+mod+'_'+rot).html()=="&nbsp;" && parsed[modid+','+rotid][0]!='' && parsed[modid+','+rotid][0]>0)
                                                                {
                                                                    var stuseg1=parsed[modid+','+rotid][0]-parseInt(1);
                                                                    if(stuidname[stuseg1]!=undefined)
                                                                    {
                                                                        var student=stuidname[stuseg1].split("~");
                                                                        $('#seg1_'+mod+'_'+rot).html('<span id="'+student[1]+'">'+student[0]+'</span>');
                                                                    }
                                                                    
                                                                }
                                                                if($('#seg2_'+mod+'_'+rot).html()=="&nbsp;" && parsed[modid+','+rotid][1]!='' && parsed[modid+','+rotid][1]>0)
                                                                {
                                                                    
                                                                     var stuseg2=parsed[modid+','+rotid][1]-parseInt(1);
                                                                     if(stuidname[stuseg2]!=undefined)
                                                                     {
                                                                        var student1=stuidname[stuseg2].split("~");
                                                                        
                                                                        
                                                                              $('#seg2_'+mod+'_'+rot).html('<span id="'+student[1]+'">'+student[0]+'</span>'); 
                                                                       
                                                                    }
                                                                }
                                                            }
                                                 }
                                        }

                                var s=new Array();
				s=$('#stuidname').val().split(",");
				var y=new Array('Student NN0~0');
				
				$('#stuidname').val(arraycompare(s,y));
				
						if($('#autoblock').is(':checked'))
						{
						var k=0;
						var cell=new Array();
						var trcount=$("#myTable05 tr").length-1;
						var thcount=$("#myTable05 th").length;
						for(i=2;i<=trcount;i++)
						{
							for(j=2;j<=thcount;j++)
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
						
						dataparam="oper=checkstudentmod"+"&celldet="+cell+"&scheduleid="+$('#scheduleid').val();
						
						$.ajax({
						type: 'post',
						url: 'class/newclass/class-newclass-rotation-ajax.php',
						data: dataparam,
						success:function(data) {
								if(data!="")
								{
									$.Zebra_Dialog("<div style='text-align:left'>"+data+"</div>");
									$('.ZebraDialog').css({"posiion":"absolute","left":"50%","top":"50%","transform":"translate(-50%,-50%)","width":"1000px"});
									$('.rowspanone').empty();
									$('.rowspantwo').empty();
									$('.rowspanone').html('&nbsp;');
									$('.rowspantwo').html('&nbsp;');
									closeloadingalert();
								}
								else
								{
									closeloadingalert();
								}
							}
						});
						
					}
					else
					{
						closeloadingalert();
					}
				
			}
			
			}
		});
     
 }
 
/* fill the students to rotational table */
function fn_generateschedule(rot,mod,stu,flag)
{
	$('#save').removeClass('dim');
	$('DIV.clk').removeClass('darkrot lightrot');	
	
	
	var moddup="";
	var trlength=$("#myTable05 tr").length-1;
	var s=0;
	for(i=2;i<=2;i++)
	{
		for(j=2;j<=trlength;j++)
		{
			var rowclassname= $('#stu_'+j+i).closest('tr').attr('class');
			var m=0;
			var rowid=new Array();
			$.each($('.'+rowclassname),function(){
				var id=this.id.split("_");
				rowid[m]= id[1]-parseInt(1);
				m++;
				
			});
			
			if(rowid.length>=2)
			{
				if(moddup=="")
				{
					moddup=rowid;
				}
				else
				{
					moddup=moddup+"~"+rowid;
				}
			}
		}
	}
	
	uniqueCoords = moddup;
	
	if(flag=="gen")
	{
		var thcount=$("#myTable05 th").length-1;
		var trcount=$("#myTable05 tr").length-2;
		var start = $('#startrotation').val();
		var end = $('#endrotation').val();
		if(start==1 && end==1)
		{
			fn_generate();
			return false;
		}
		
	}
	else if(flag=="regen")
	{
		$('#addrotimg').addClass('dim'); 
		$('.addmodinc').addClass('dim');
		
		var start = $('#startrotation').val();
		var startval=parseInt(start)+parseInt(1);
		var end = $('#endrotation').val();	
		var thcount=$("#myTable05 th").length-1;
		var trcount=$("#myTable05 tr").length-2;

		var rotcount=$("#myTable05 th").length-parseInt(1);
		var rot=$("#myTable05 tr").length-parseInt(2);
		var mod=$("#myTable05 tr").length-parseInt(2);
		var stu=$('#studentcount').val();
		var noofrotation=$('#noofrotation').val();
		
		var totcount=parseInt(start)+parseInt(end)-parseInt(1);
		
		if(start!=1 && $('#schtype').val()=="edit" || start==1 && end==1)
		{
			fn_generate();
			return false;
		}
		else
		{
			
			$('.rowspanone').empty();
			$('.rowspantwo').empty();
			$('.rowspanone').html('&nbsp;');
			$('.rowspantwo').html('&nbsp;');
					
		}
		
	}
	
	if(uniqueCoords.length>0)
	{
		var rot=$("#myTable05 th").length-parseInt(1);
	}
	
	

	var dataparam = "oper=generateschedule&rotation="+rot+"&module="+mod+"&student="+stu+"&flag="+flag+"&moddupid="+uniqueCoords+"&scheduleid="+$('#scheduleid').val();
	
			$.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-rotation-ajax.php',
			data: dataparam,
			beforeSend:function(){
				showloadingalert('Generating the rotational schedule.');
			},
			success:function(data) {
				
				
				if(data=="false")
				{
					fn_generate("gen");
					
				}
				else if(data!="false")
				{
					
					$('#endaddbtn').addClass("dim");
					$('#endsubbtn').addClass("dim");
					$('#staddbtn').addClass("dim");
					$('#stsubbtn').addClass("dim");
					
					var trcount=$("#myTable05 tr").length-2;
					var thcount=$("#myTable05 th").length-1;
					var totcount=trcount*parseInt(2);
					
					var newstu = $('#stuidname').val();
					var tempstu ='';
	
					if(parseInt($('#studentcount').val())<totcount)
					{
						var couval = totcount - parseInt($('#studentcount').val());
						for(i=0;i<couval;i++)
						{
							if(tempstu=='')
							{
								tempstu = ",Student NN0~0";
							}
							else
							{
								tempstu = tempstu+",Student NN0~0";
							}
						}
						
						var tem = newstu+tempstu;
						$('#stuidname').val(tem);
					}
					
					
					
					
					var parsed=JSON.parse(data);
					stuidname=$('#stuidname').val().split(",");
					var start = $('#startrotation').val();
					var startval=parseInt(start)+ parseInt(1);
					var end = $('#endrotation').val();
					var endval=parseInt(end)+ parseInt(1);
					
					var mod="";
					var rot="";
					for(mod=2;mod<=trcount+1;mod++)
					{
						for(rot=startval;rot<=endval;rot++)
						{
							for(i=0;i<parsed.length;i++)
							{
                                                            
                                                            
								for(j=0;j<parsed[i].length;j++)
								{
									modid=mod-parseInt(1);
									rotid=rot-parseInt(1);
									
									if(parsed[i][j][0]==modid && parsed[i][j][1]==rotid)
									{
										
										var student=stuidname[i].split("~");
										
										if($('#seg1_'+mod+'_'+rot).html()=="&nbsp;")
										{
                                                                                    
											$('#seg1_'+mod+'_'+rot).html('<span id="'+student[1]+'">'+student[0]+'</span>');
                                                                                   
										}
										else if($('#seg2_'+mod+'_'+rot).html()=="&nbsp;")
										{
										   
											$('#seg2_'+mod+'_'+rot).html('<span id="'+student[1]+'">'+student[0]+'</span>');
                                                                                   
										}
									}
									
								}
							}
					}
				}
				$('span[id^="0"]').replaceWith("&nbsp;");
				
				
				var s=new Array();
				s=$('#stuidname').val().split(",");
				var y=new Array('Student NN0~0');
				
				$('#stuidname').val(arraycompare(s,y));
				
						if($('#autoblock').is(':checked'))
						{
						var k=0;
						var cell=new Array();
						var trcount=$("#myTable05 tr").length-1;
						var thcount=$("#myTable05 th").length;
						for(i=2;i<=trcount;i++)
						{
							for(j=2;j<=thcount;j++)
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
						
						dataparam="oper=checkstudentmod"+"&celldet="+cell+"&scheduleid="+$('#scheduleid').val();
						
						$.ajax({
						type: 'post',
						url: 'class/newclass/class-newclass-rotation-ajax.php',
						data: dataparam,
						success:function(data) {
								if(data!="")
								{
									$.Zebra_Dialog("<div style='text-align:left'>"+data+"</div>");
									$('.ZebraDialog').css({"posiion":"absolute","left":"50%","top":"50%","transform":"translate(-50%,-50%)","width":"1000px"});
									$('.rowspanone').empty();
									$('.rowspantwo').empty();
									$('.rowspanone').html('&nbsp;');
									$('.rowspantwo').html('&nbsp;');
									closeloadingalert();
								}
								else
								{
									closeloadingalert();
								}
							}
						});
						
					}
					else
					{
						closeloadingalert();
					}
				
			}
			
			}
		});	
}

/* check the student pair */
function containsAll(student1,student2, haystack){ 
  var needles=new Array(student1+":"+student2);
  var needles1=new Array(student2+":"+student1);
  
	 var val="false";
     for(var i=0; i<haystack.length; i++) {
		
        if (haystack[i] === needles[0] || haystack[i] === needles1[0]) 
		{
			val="true";
		}
    }
	
  return val;
}

/* Check the row if student exist return false */
function tablerow(trlength,i,rowclassname,studentinfo,studentinfo1)
{
	var seg1row='true';
	var seg2row='true';
	for(zi=0;zi<trlength.length;zi++)
	{
		var rowid=trlength[zi].split("_");
		for(zj=2;zj<=i;zj++)
		{
				
				if(($('#seg1_'+rowid[1]+"_"+zj).html()==studentinfo) || ($('#seg2_'+rowid[1]+"_"+zj).html()==studentinfo))
				{
					
					// don't insert the student zk	
					seg1row="false";
					
				
					break;
				}
				if(($('#seg1_'+rowid[1]+"_"+zj).html()==studentinfo1) || ($('#seg2_'+rowid[1]+"_"+zj).html()==studentinfo1))
				{
					
					// don't insert the student zk	
					seg2row="false";
					break;
				}
		}
		
		
		if(seg1row=="false" || seg2row=="false")
		{
			break;
		}
	}
	
	return seg1row+"~"+seg2row;
}

/* Check the row if student exist return false */
function tablerowfalse(trlength,i,rowclassname,studentinfo)
{
	var seg1row='true';
	for(zi=0;zi<trlength.length;zi++)
	{
		var rowid=trlength[zi].split("_");
		for(zj=2;zj<i;zj++)
		{
			
				if(($('#seg1_'+rowid[1]+"_"+zj).html()==studentinfo) || ($('#seg2_'+rowid[1]+"_"+zj).html()==studentinfo))
				{
					
					// don't insert the student zk	
					seg1row="false";
					break;
				}
		}
		
		if(seg1row=="false")
		{
			break;
		}
	}
	
	return seg1row;
}

/* Shuffle the Array values */
function arrayShuffle(oldArray) 
{
	var newArray = oldArray.slice();
	var len = newArray.length;
	var i = len;
	
	while (i--) 
	{
	var p = parseInt(Math.random()*len);
	var t = newArray[i];
	newArray[i] = newArray[p];
	newArray[p] = t;
	}
	return newArray; 
};


function fn_addcolumn()
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

	$("#myTable05 tr:first").append("<th class='modhead' style='font-size:24px;cursor:pointer;' title='Remove rotation' onclick='fn_removecolumn()';><span style='font-size:14px;vertical-align:top;'>rotation "+c+"</th></soan>");
	
	var i='';
	for(i=2;i<=tr;i++)
	{
		var num=c+1;
		
		if(i!=tr)
		{
		$('#tr_'+i).append("<td id=stu_"+i+''+num+" style='background: #FFFFFF;'></td>");
		var div="<div class='rowspanone clk row"+num+"' id='seg1_"+i+'_'+num+"'><span class='dragdrop'>&nbsp;</span></div><div class='imagetop' id='imagetop_"+i+'_'+num+"' title='Delete'></div><div class='rowspantwo clk row"+num+"' id='seg2_"+i+'_'+num+"'><span class='dragdrop'>&nbsp;</span></div><div class='imagebottom' id='imagebottom_"+i+'_'+num+"' title='Delete'>";
		$('#stu_'+i+''+num).html(div);
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
	
	
	$('.addmodinc').removeClass('dim');
	$.getScript('class/newclass/class-newclass-rotationalschedule.js');
	$(".clk").off();
	$(".clk").on();
        dragdrop();
}


function fn_removecolumn()
{
	var trlength=$("#myTable05 tr").length-1;
	
	var thlength=$("#myTable05 th").length;
	var row="true";
		for(zi=2;zi<=trlength;zi++)
		{
			for(zj=thlength;zj<=thlength;zj++)
			{
				if(($('#seg1_'+zi+"_"+zj).html()!='&nbsp;') && ($('#seg1_'+zi+"_"+zj).html()!='<span class="dragdrop ui-draggable ui-droppable">&nbsp;</span>') || ($('#seg2_'+zi+"_"+zj).html()!='&nbsp;') && ($('#seg2_'+zi+"_"+zj).html()!='<span class="dragdrop ui-draggable ui-droppable">&nbsp;</span>'))
				{
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
		$("#myTable05 th:last").attr("onclick","fn_removecolumn()"); 
		$("#myTable05 th:last").css("cursor","pointer");

		$('#myTable05').fixedHeaderTable({fixedColumn: true });	
                $(".modhead").css({"width":"209.5px"});
		$('.addmodinc').removeClass('dim');
	}},
					]
		});
		return false;
		}
}

/* Remove Module to table */	
function fn_removemodule(rowid)
{		
    var rowclassname=$('#tr_'+rowid).attr('class');
                                                       
                                                        var m=0;
                                                        var rowidm=new Array();
                                                        $.each($('.'+rowclassname),function(){
                                                                var id=this.id.split("_");
                                                                rowidm[m]= id[1]-parseInt(1);
                                                                m++;

                                                        });
                                                        
                                                        
                                                        
		$.Zebra_Dialog('Are you sure you want to delete this module ?',
		 {
		'type':     'confirmation',
		'buttons':  [
						{caption: 'No', callback: function() { }},
						{caption: 'Yes', callback: function() { 
						
							$('#myTable05').fixedHeaderTable('destroy');		
							$("#tr_"+rowid).remove();
							$('#myTable05').fixedHeaderTable({fixedColumn: true });	
                                                        $(".modhead").css({"width":"209.5px"});
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
									$(this).children(":first").attr({id: 'module_'+rowid,onmouseover: 'fn_checkcellvalue('+rowid+')'});
									$(this).children().each(function(index, element) {
										$(this).html($(this).html().replace(new RegExp('_'+tid+'_','g'),'_'+rowid+'_'));
									});	
									rowid++;
									tid++;	
								}
							});						
                                                        
                                                        if(rowidm.length>0)
                                                        {
                                                           fn_removemodulestable(rowid,rowclassname); 
                                                        }
						}},
					]
		});
		return false;
}


function fn_removemodulestable(rowid,rowclassname)
{
    dataparam="oper=removemodule&moduletype="+rowclassname+"&rowid="+rowid+"&scheduleid="+$('#scheduleid').val()+"&classid="+$('#classid').val();
    
    $.ajax({
        type: 'post',
        url: 'class/newclass/class-newclass-rotation-ajax.php',
        data: dataparam,
        success:function(data) 
        {
                 
             closeloadingalert();
                       
              }
        });
    
    
}

function fn_showalert()
{
	showloadingalert('Generating the Rotational schedule.');
}

/* Generate the student */

function fn_generate(rotflag)
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
	
	var combination="true";
	
	var newstu = $('#stuidname').val();
	var tempstu ='';

	if(parseInt($('#studentcount').val())<totcount)
	{
		var couval = totcount - parseInt($('#studentcount').val());
		for(i=0;i<couval;i++)
		{
			if(tempstu=='')
			{
				tempstu = ",Student NN"+i+"~0";
			}
			else
			{
				tempstu = tempstu+",Student NN"+i+"~0";
			}
		}
		
		var tem = newstu+tempstu;
		$('#stuidname').val(tem);
	}
	
	if(rotflag!="gen")
	{
		showloadingalert("Generating the Rotational schedule.");	
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
			
			var tk=0;
			var count=0;	
			while(j<trlength) // column
			{
                            
                            
				var rowclassname= $('#stu_'+j+i).closest('tr').attr('class');
				for(zk=0;zk<stuname.length;zk++) 
				{
					
					// check the first segment,check the first segment for empty,check the first segment with student zk
					var splitstuname=stuname[zk].split("~");
					if($('#seg1_'+j+"_"+i).html()!="&nbsp;")
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
                                                        var modstuseg1=stumod[0]+"-"+stumod[1]+"-"+splitstuname[1]//join the current module and studentid
                                                        
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
							$('#seg1_'+j+"_"+i).html('<span id="'+splitstuname[1]+'">'+splitstuname[0]+'</span>');
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
                                                        var modstuseg2=stumod[0]+"-"+stumod[1]+"-"+splitstuname[1]//join the current module and studentid
                                                        
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
								if(retry==100)
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
                                                        var modstuseg1=stumod[0]+"-"+stumod[1]+"-"+splitstuname[1]//join the current module and studentid
                                                        var modstuseg2=stumod[0]+"-"+stumod[1]+"-"+splitstuname1[1]//join the current module and studentid1


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
	var s=new Array();
	s=$('#stuidname').val().split(",");
	var y=new Array();
	y=tempstu.split(",");
	$('#stuidname').val(arraycompare(s,y));
	
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
						
						dataparam="oper=checkstudentmod"+"&celldet="+cell+"&scheduleid="+$('#scheduleid').val()+"&operation=generate"+"&moduletype="+$('#moduletype').val();
						
						$.ajax({
						type: 'post',
						url: 'class/newclass/class-newclass-rotation-ajax.php',
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

                                                                                                               setTimeout("fn_showdetails();",2000);
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
/* Add Module in rotational table */

function dragdrop()
{
    jQuery.fn.swap = function(b){ 
                        // method from: http://blog.pengoworks.com/index.cfm/2008/9/24/A-quick-and-dirty-swap-method-for-jQuery
                        b = jQuery(b)[0]; 
                        var a = this[0]; 
                        var t = a.parentNode.insertBefore(document.createTextNode(''), a); 
                        b.parentNode.insertBefore(a, b); 
                        t.parentNode.insertBefore(b, t); 
                        t.parentNode.removeChild(t); 
                        return this; 
                    };

                   
                    
                    $( ".dragdrop" ).draggable({revert: true, helper: "clone", drag:function(event,ui){ $('.imagetop').removeClass('mousehoverimg'); $('.imagebottom').removeClass('mousehoverimg');$('.rowspanone').removeClass('clk'); $('.rowspantwo').removeClass('clk');$('.rowspanone').removeClass('clk'); var divid=$(this).closest("div").attr("id"); $('#'+divid+' span').css({"display":"block","width":"100px","height":"20px","border":"1px solid","background-color":"#b0c4de"}); }});

                    $( ".dragdrop" ).droppable({
                        accept: ".dragdrop",
                        activeClass: "ui-state-hover",
                        hoverClass: "ui-state-active",
                        
                        drop: function( event, ui ) {
                            
                            $('.rowspanone').addClass('clk'); $('.rowspantwo').addClass('clk');
                            $('.rowspanone').removeClass('mousehover');
                            $('.rowspantwo').removeClass('mousehover-bot');
                            
                            var draggable = ui.draggable, droppable = $(this),
                                dragPos = draggable.position(), dropPos = droppable.position();
                        
                           
                            var firstid= $(this).attr("id");
                            var firstcellid= $(this).closest("div").attr("id");
                            
                           var secondcellid=$('#tdval').val();
                           var secondid=($('#'+secondcellid+' span').attr('id'));
                            
                           
                            draggable.css({
                                left: dropPos.left+'px',
                                top: dropPos.top+'px'
                            });

                            droppable.css({
                                left: dragPos.left+'px',
                                top: dragPos.top+'px'
                            });
                            
                            var content='';
                            var content1='';
                            
                           
                            
                            if(firstid!="undefined")
                            {
                                var content=swapstudent(firstid,secondcellid,$('#'+firstid).html(),firstcellid);                                
                            }
                            
                            if(secondid!="undefined")
                            {
                                var content1=swapstudent(secondid,firstcellid,$('#'+secondid).html(),secondcellid);                               
                            }
                           
                            var mergecontent=content+content1;
                            
                            
                            
                            if(firstid!=undefined)
                            {
                                var name="Swap Confirmation";
                                var captionname="Swap";
                            }
                            else
                            {
                                var name="Move Confirmation";
                                var captionname="Move";
                            }
                            
                            if(mergecontent!='')
                            {
                                $.Zebra_Dialog(mergecontent,
							 {
							'type':     'confirmation',
							'title':    name,
							'buttons':  [
											{caption: 'Cancel', callback: function() { 
                                                                                                $('.rowspanone span').addClass("dragdrop ui-draggable ui-droppable");
                                                                                                $('.rowspantwo span').addClass("dragdrop ui-draggable ui-droppable");}},
											{caption: captionname, callback: function() { 
                                                                                        
                                                                                                        draggable.swap(droppable);
                                                                                                        
                                                                                                        $('.rowspanone span').addClass("dragdrop ui-draggable ui-droppable");
                                                                                                        $('.rowspantwo span').addClass("dragdrop ui-draggable ui-droppable");
												}},
											
										]
							});
                                                        $('.ZebraDialog').css({"width":"500px"});
							return false;
                            }
                            else
                            {
                               draggable.swap(droppable);
                               $('.rowspanone span').addClass("dragdrop ui-draggable ui-droppable");
                               $('.rowspantwo span').addClass("dragdrop ui-draggable ui-droppable");
                            }                            
                            
                            
                            
                        }
                    });
}

function swapstudent(stuid,cellid,name,cellidsec)
{    
    $('#save').removeClass('dim');
	var trlength=$("#myTable05 tr").length;
	var thlength=$("#myTable05 tr:first th").length;
	var getdivid=cellid;
	var cellid=cellid;
        var content='';        
        $('.dragdrop').removeAttr("style");
        $('.rowspanone span').removeClass("dragdrop ui-draggable ui-droppable");
        $('.rowspantwo span').removeClass("dragdrop ui-draggable ui-droppable");
        $('.rowspanone span').removeAttr("class");
        $('.rowspantwo span').removeAttr("class");
        
	getdivid=getdivid.split("_");
	
		var rowclassname= $('#stu_'+getdivid[1]+getdivid[2]).closest('tr').attr('class');
                
                var getmodid=rowclassname.split("-");
                
                var stumod=getmodid[0]+"-"+stuid
                
                // check module block
                
                if($('#blockstu').val()!='')
                {
                    var blostu=$('#blockstu').val();
                    var blockmodstu=JSON.parse(blostu);
                    var b=jQuery.inArray(stumod,blockmodstu);
                    var modulename=$("#module_"+getdivid[1]).html();
                   
                    
                    if(b!=-1)
                    {
                        var content="<br><br>"+name+' is blocked from the '+modulename+ ' module';                       
                    }
                }
                
                // End
                
                if($('#autoblock').is(':checked'))
                {
                    var blostu=$('#autoblockstu').val();
                    var blockmodstu=JSON.parse(blostu);
                    var b=jQuery.inArray(stumod,blockmodstu);
                    var modulename=$("#module_"+getdivid[1]).html();
                    if(b!=-1)
                    {
                        var content="<br><br>"+name+' is blocked from the '+modulename+ ' module';                        
                    }
                }                
               
		if(($('#seg1_'+getdivid[1]+"_"+getdivid[2]).html()=='<span id="'+stuid+'">'+name+'</span>' || $('#seg2_'+getdivid[1]+"_"+getdivid[2]).html()=='<span id="'+stuid+'">'+name+'</span>') && cellidsec!='seg1_'+j+"_"+getdivid[2] && cellidsec!='seg2_'+j+"_"+getdivid[2])
		{
			var modulename=$("#module_"+getdivid[1]).html();
			var i=getdivid[2]-1;
			content=name+' is  already assigned to '+modulename+ ' in rotation '+i;                        
			
		}
		else
		{
                        /* check column */
                        for(j=2;j<trlength;j++)
                        {                           
                            
                                if((($('#seg1_'+j+"_"+getdivid[2]).html()=='<span id="'+stuid+'">'+name+'</span>') || ($('#seg2_'+j+"_"+getdivid[2]).html()=='<span id="'+stuid+'">'+name+'</span>')) && cellidsec!='seg1_'+j+"_"+getdivid[2] && cellidsec!='seg2_'+j+"_"+getdivid[2])
                                {
                                        var i=getdivid[2]-1;
                                        content="<div style='text-align:left'>"+name +' is already in Rotation '+i+' ' +content+"</div>";                                       
                                        break;
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
                                                    
						if(($('#seg1_'+zi+"_"+zj).html()=='<span id="'+stuid+'">'+name+'</span>') || ($('#seg2_'+zi+"_"+zj).html()=='<span id="'+stuid+'">'+name+'</span>'))
						{
							
							var modulename=$("#module_"+getdivid[1]).html()
							content="<div style='text-align:left'>"+ name +' will be in '+modulename+' twice' +content+ "</div>";                                                        
                                                        break;
						}
						
					}
				}
			}
                        
            }            
            
            return content;
		
}


function fn_showdetails()
 {
     $('.ZebraDialog').remove();
     $('.ZebraDialogOverlay').remove();
     var k=0;
    var cell=new Array();
    var trcount=$("#myTable05 tr").length-1;
    var thcount=$("#myTable05 th").length;
    for(i=2;i<=trcount;i++)
    {
            for(j=2;j<=thcount;j++)
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
    
    dataparam="oper=checkstudentmod"+"&celldet="+cell+"&scheduleid="+$('#scheduleid').val()+"&operation=showdetails"+"&moduletype="+$('#moduletype').val();
                                                                                                               
    $.ajax({
    type: 'post',
    url: 'class/newclass/class-newclass-rotation-ajax.php',
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

function fn_addmodule(moduleid,scheduleid,type)
{
	$.fancybox.close();
        var numberofrotation=parseInt($("#myTable05 th").length)-parseInt(1);
	var dataparam = "oper=addmodule&moduleid="+moduleid+"&trlength="+$("#myTable05 tr:last").prev().attr('id')+"&thlength="+$("#myTable05 tr:first th").length+"&scheduleid="+scheduleid+"&scheduletype="+$('#scheduletype').val()+"&type="+type+"&classid="+$('#hidclassid').val()+"&numberofrotation="+numberofrotation;

	
	$.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-rotation-ajax.php',
			data: dataparam,
			beforeSend: function(){
				showloadingalert("Loading, please wait.");	
			},		
			success:function(data) {
				
				$('#myTable05').fixedHeaderTable('destroy');	
				setTimeout("closeloadingalert();",1000);
				showloadingalert("Module added to table.");
				setTimeout("closeloadingalert();",1000);
					$('#body tr:last').remove();
					$('#body').append(data);
					$('#myTable05').fixedHeaderTable({fixedColumn: true });
                                        $(".modhead").css({"width":"209.5px"});
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

$(".dragdrop").live({
	mouseup: function() {
            
		$('.dragdrop').removeAttr("style");
	},
    });

/* Show student list in popup table */

function fn_addstudenttotd(id,name)
{
	$('#save').removeClass('dim');
	var trlength=$("#myTable05 tr").length;
	var thlength=$("#myTable05 tr:first th").length;
	var getdivid=$('#tdval').val();
	var cellid=$('#tdval').val();
        var content='';
	var stuid=id;
        $('.dragdrop').removeAttr("style");
        $('.rowspanone span').removeClass("dragdrop ui-draggable ui-droppable");
        $('.rowspantwo span').removeClass("dragdrop ui-draggable ui-droppable");
        $('.rowspanone span').removeAttr("class");
        $('.rowspantwo span').removeAttr("class");
        
	getdivid=getdivid.split("_");
	
		var rowclassname= $('#stu_'+getdivid[1]+getdivid[2]).closest('tr').attr('class');
                
                var getmodid=rowclassname.split("-");
                
                var stumod=getmodid[0]+"-"+getmodid[1]+"-"+stuid
                
                // check module block
                
                if($('#blockstu').val()!='')
                {
                    var blostu=$('#blockstu').val();
                    var blockmodstu=JSON.parse(blostu);
                    var b=jQuery.inArray(stumod,blockmodstu);
                    var modulename=$("#module_"+getdivid[1]).html();
                    
                    
                    if(b!=-1)
                    {
                        var content="<br><br>"+name+' is blocked from the '+modulename+ ' module';
                    }
                }
                
                // End
                
                if($('#autoblock').is(':checked'))
                {
                    var blostu=$('#autoblockstu').val();
                    var blockmodstu=JSON.parse(blostu);
                    var b=jQuery.inArray(stumod,blockmodstu);
                    var modulename=$("#module_"+getdivid[1]).html();
                    if(b!=-1)
                    {
                        var content="<br><br>"+name+' is blocked from the '+modulename+ ' module';
                    }
                }
                
		if($('#seg1_'+getdivid[1]+"_"+getdivid[2]).html()=='<span id="'+stuid+'">'+name+'</span>' || $('#seg2_'+getdivid[1]+"_"+getdivid[2]).html()=='<span id="'+stuid+'">'+name+'</span>')
		{
			var modulename=$("#module_"+getdivid[1]).html();
			var i=getdivid[2]-1;
			$.Zebra_Dialog("<div style='text-align:left'>"+name+' is  already assigned to '+modulename+ ' in rotation '+i+content+"</div>");
                        $('.rowspanone span').addClass("dragdrop");
                        $('.rowspantwo span').addClass("dragdrop");
                        dragdrop();
                        $('.ZebraDialog').css({"width":"500px"});
			return false;
		}
		else
		{
				/* check column */
				for(j=2;j<trlength;j++)
				{
					if(($('#seg1_'+j+"_"+getdivid[2]).html()=='<span id="'+stuid+'">'+name+'</span>') || ($('#seg2_'+j+"_"+getdivid[2]).html()=='<span id="'+stuid+'">'+name+'</span>'))
					{
						var i=getdivid[2]-1;
						$.Zebra_Dialog("<div style='text-align:left'>"+name +' is already in Rotation '+i+'. Add anyway ?' +content+"</div>",
								 {
								'type':     'confirmation',
								'title':    'Duplicate rotation confirmation',
								'buttons':  [
												{caption: 'No', callback: function() { $('.popuptable').hide(); 
                                                                                                    $('.rowspanone span').addClass("dragdrop");
                                                                                                    $('.rowspantwo span').addClass("dragdrop");
                                                                                                    dragdrop();
                                                                                                }},
												{caption: 'Yes', callback: function() { $('.popuptable').hide();
													var tdid=$('#tdval').val();
                                                                                                        
													if($('#'+tdid).html()=='' || $('#'+tdid).html()=='<span>&nbsp;</span>' || $('#'+tdid).html()=='&nbsp;')
													{
													$('#'+tdid).html("<span id="+stuid+">"+name+"</span>");
													fillcolor(getdivid[0],cellid);
                                                                                                        $('.rowspanone span').addClass("dragdrop");
                                                                                                        $('.rowspantwo span').addClass("dragdrop");
                                                                                                        dragdrop();
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
						if(($('#seg1_'+zi+"_"+zj).html()=='<span id="'+stuid+'">'+name+'</span>') || ($('#seg2_'+zi+"_"+zj).html()=='<span id="'+stuid+'">'+name+'</span>'))
						{
							
							var modulename=$("#module_"+getdivid[1]).html()
							$.Zebra_Dialog( "<div style='text-align:left'>"+ name +' is already in '+modulename+'. Add anyway ?' +content+ "</div>",
							 {
							'type':     'confirmation',
							'title':    'Duplicate content confirmation',
							'buttons':  [
											{caption: 'No', callback: function() { 
                                                                                                $('.rowspanone span').addClass("dragdrop");
                                                                                                $('.rowspantwo span').addClass("dragdrop");
                                                                                                dragdrop();
                                                                                            }},
											{caption: 'Yes', callback: function() { $('.popuptable').hide();
												var tdid=$('#tdval').val();
												if($('#'+tdid).html()=='' || $('#'+tdid).html()=='<span>&nbsp;</span>' || $('#'+tdid).html()=='&nbsp;')
												{
													
												$('#'+tdid).html("<span id="+stuid+">"+name+"</span>");
												fillcolor(getdivid[0],cellid);
                                                                                                $('.rowspanone span').addClass("dragdrop");
                                                                                                $('.rowspantwo span').addClass("dragdrop");
                                                                                                dragdrop();
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
				
                        
                        if(column=="true" && row=="true" && content!='')
			{
                            var modulename=$("#module_"+getdivid[1]).html()
							$.Zebra_Dialog( "<div style='text-align:left'>"+content+"</div>",
							 {
							'type':     'confirmation',
							'title':    'Duplicate content confirmation',
							'buttons':  [
											{caption: 'No', callback: function() { 
                                                                                             $('.rowspanone span').addClass("dragdrop");
                                                                                             $('.rowspantwo span').addClass("dragdrop");
                                                                                             dragdrop();
                                                                                        }},
											{caption: 'Yes', callback: function() { $('.popuptable').hide();
												var tdid=$('#tdval').val();
												if($('#'+tdid).html()=='' || $('#'+tdid).html()=='<span>&nbsp;</span>' || $('#'+tdid).html()=='&nbsp;')
												{
													
												$('#'+tdid).html("<span id="+stuid+">"+name+"</span>");
												fillcolor(getdivid[0],cellid);
                                                                                                $('.rowspanone span').addClass("dragdrop");
                                                                                                $('.rowspantwo span').addClass("dragdrop");
                                                                                                dragdrop();
												}
												}},
											
										]
							});
                                                        
                                                        $('.ZebraDialog').css({"width":"500px"});
							return false;
                        }
                        
                        
				
			if(column=="true" && row=="true")
			{
				$('.popuptable').hide();
				var tdid=$('#tdval').val();
				if($('#'+tdid).html()=='' || $('#'+tdid).html()=='<span>&nbsp;</span>' || $('#'+tdid).html()=='&nbsp;')
				{
				$('#'+tdid).html("<span id="+stuid+">"+name+"</span>");
				fillcolor(getdivid[0],cellid);
                                $('.rowspanone span').addClass("dragdrop");
                                $('.rowspantwo span').addClass("dragdrop");
                                dragdrop();
				}
			}
		}
		
	}

/* Show module list in popup */ 
function fn_showmodule(scheduleid)
{	
	var tr=$("#myTable05 tr").length;
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
			url		: "class/newclass/class-newclass-rotation-ajax.php",
			data		: "oper=showmodule&scheduleid="+scheduleid,
			success: function(data) {
				$.fancybox(data);
			}
		});
	
		return false;
}


/* Generation popup start */

function fn_increment(textboxid)
{
	if($('#endrotation').val()==$('#noofrotation').val())
	{
		$('#endaddbtn').addClass("dim");
	}
	else
	{
		$('#endaddbtn').removeClass("dim");
	}
	
	var a=$('#'+textboxid).val();
	var b=1;
	var c=parseInt(a)+parseInt(b);

	if(textboxid=="startrotation")
	{
		if(c<=$('#endrotation').val())
		{
			$('#'+textboxid).val('');
			$('#'+textboxid).val(c);
		}
	}
	else
	{
		if(c>=$('#startrotation').val() && $('#'+textboxid).val()!=$('#noofrotation').val())
		{
			$('#'+textboxid).val('');
			$('#'+textboxid).val(c);
		}
	}	
	
	
	if($('#startrotation').val()==$('#endrotation').val())
	{
		$('#staddbtn').addClass("dim");
		$('#endsubbtn').addClass("dim");
		
	}
	else
	{
		$('#staddbtn').removeClass("dim");
		$('#endsubbtn').removeClass("dim");
	}
	
}

function fn_decrement(textboxid)
{
	
	var a=$('#'+textboxid).val();
	var b=1;
	var c=parseInt(a)-parseInt(b);

	if($('#schtype').val()=="create")
	{
		if(textboxid=="startrotation")
		{
			if(c!=0)
			{
				$('#'+textboxid).val('');
				$('#'+textboxid).val(c);
			}
		}
		else
		{
			if(c!=0 && c>=$('#startrotation').val())
			{
				$('#'+textboxid).val('');
				$('#'+textboxid).val(c);
			}
		}
		
	}
	else
	{
		if(textboxid=="startrotation")
		{
			if(c!=0 && c>=$('#modplaytrackrot').val() && c<=$('#endrotation').val())
			{
				$('#'+textboxid).val('');
				$('#'+textboxid).val(c);
			}
		}
		else
		{
			if(c!=0 && c>=$('#startrotation').val())
			{
				$('#'+textboxid).val('');
				$('#'+textboxid).val(c);
			}
		}
	}
	
	
	if($('#endrotation').val()==$('#noofrotation').val())
	{
		$('#endaddbtn').addClass("dim");
	}
	else
	{
		$('#endaddbtn').removeClass("dim");
	}
	
	if($('#startrotation').val()!=$('#endrotation').val())
	{
		
		$('#staddbtn').removeClass("dim");
		$('#endsubbtn').removeClass("dim");
		
	}
	else
	{
		$('#staddbtn').addClass("dim");
		$('#endsubbtn').addClass("dim");
	}

	if($('#schtype').val()=="edit")
	{
		if($('#startrotation').val()!=$('#modplaytrackrot').val() && $('#startrotation').val()!=$('#endrotation').val())
		{
			
			$('#stsubbtn').removeClass("dim");
			
		}
		else
		{
			
			$('#stsubbtn').addClass("dim");
		}
	}
}

/* Generation popup End */

/* The user hover the modules if without students in modules the title,style and function assigned to module */

function fn_checkcellvalue(rowid)
{
	
	var thlength=$("#myTable05 tr:first th").length;
	var row='true';
		for(zi=rowid;zi<=rowid;zi++)
		{
			for(zj=2;zj<=thlength;zj++)
			{
                            
				if(($('#seg1_'+zi+"_"+zj).html()!='&nbsp;') && (typeof $('#seg1_'+zi+"_"+zj).html()!='undefined') && ($('#seg1_'+zi+"_"+zj).html()!='<span class="dragdrop ui-draggable ui-droppable">&nbsp;</span>') || ($('#seg2_'+zi+"_"+zj).html()!='&nbsp;') && (typeof $('#seg2_'+zi+"_"+zj).html()!='undefined') && ($('#seg2_'+zi+"_"+zj).html()!='<span class="dragdrop ui-draggable ui-droppable">&nbsp;</span>'))
				{
					// don't add title and function
					row="false";
				}
			}
		}
		
		
		if(row=="true")
		{
			$("#module_"+rowid).attr("title","Remove a Module");
			$("#module_"+rowid).attr("onclick","fn_removemodule("+rowid+")");
			$("#module_"+rowid).css("cursor","pointer");
				
		}
		else
		{
			$("#module_"+rowid).removeAttr("title");
			$("#module_"+rowid).attr("onclick"," ");
			$("#module_"+rowid).css("cursor","default");
		}
	
}

function fn_checkcellvalueout(id)
{
	var val=$('#'+id).attr('title');
	if(val=="Remove a Module")
	{
		$('#'+id).removeAttr('title');
	}
}


/* Save rotational table details */

function fn_save()
{
	var numberofrotation=parseInt($("#myTable05 th").length)-parseInt(1);
	var trlength=$("#myTable05 tr").length;
	var thlength=$("#myTable05 tr:first th").length;
	
        var list26=[];
        $("div[id^=list26_]").each(function()
        {
			list26.push($(this).attr('id').replace('list26_',''));
	});
	
	// Get module id and name
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
	
	
	if($('#schtype').val()=="edit" && $('#hidgenerate').val()==1 && ($('#hidmodule').val()>0 || $('#hidmathmodule').val()>0))
	{
		if($('#moduletype').val()==1)
	{
		if($('#hidmodule').val()>0)
		{
			$.Zebra_Dialog('Student grades will be lost, Are you sure you want to save ?',
							 {
							'type':     'confirmation',
							'buttons':  [
											{caption: 'No', callback: function() { return false; }},
											{caption: 'Yes', callback: function() { 
											
													var dataparam="oper=saverotation&moduledet="+module+"&numberofrotation="+numberofrotation+"&celldet="+cell+"&classid="+$('#hidclassid').val()+"&scheduleid="+$('#scheduleid').val()+"&autoblock="+autoblock+"&testflag=1"+"&moduletype="+$('#moduletype').val()+"&startdate="+$('#start_date').val()+"&rotlength="+$('#rotationlength').val()+"&generatetype="+generatetype+"&blockmodule="+$('#selectblockmodule').val()+"&blockstudents="+list26;	

	$.ajax({
		type: 'post',
		url: 'class/newclass/class-newclass-rotation-ajax.php',
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
				fn_saverotationalschedule(1);
			}
		}
	});
												
											}},
										]
							});
							return false;
			
		}
	}
	else if($('#moduletype').val()==2)
	{
		if($('#hidmodule').val()>0 || $('#hidmathmodule').val()>0)
		{
			$.Zebra_Dialog('Student grades will be lost, Are you sure you want to save ?',
							 {
							'type':     'confirmation',
							'buttons':  [
											{caption: 'No', callback: function() { return false; }},
											{caption: 'Yes', callback: function() { 
												
													var dataparam="oper=saverotation&moduledet="+module+"&numberofrotation="+numberofrotation+"&celldet="+cell+"&classid="+$('#hidclassid').val()+"&scheduleid="+$('#scheduleid').val()+"&autoblock="+autoblock+"&testflag=1"+"&moduletype="+$('#moduletype').val()+"&startdate="+$('#start_date').val()+"&rotlength="+$('#rotationlength').val()+"&generatetype="+generatetype+"&blockmodule="+$('#selectblockmodule').val()+"&blockstudents="+list26;
	

	$.ajax({
		type: 'post',
		url: 'class/newclass/class-newclass-rotation-ajax.php',
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
				fn_saverotationalschedule(1);
			}
		}
	});
											}},
										]
							});
							return false;
	}
	
	}
	
	}
	else
	{
		var dataparam="oper=saverotation&moduledet="+module+"&numberofrotation="+numberofrotation+"&celldet="+cell+"&classid="+$('#hidclassid').val()+"&scheduleid="+$('#scheduleid').val()+"&autoblock="+autoblock+"&startdate="+$('#start_date').val()+"&rotlength="+$('#rotationlength').val()+"&moduletype="+$('#moduletype').val()+"&generatetype="+generatetype+"&blockmodule="+$('#selectblockmodule').val()+"&blockstudents="+list26;
	

	$.ajax({
		type: 'post',
		url: 'class/newclass/class-newclass-rotation-ajax.php',
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
				fn_saverotationalschedule(1);
			}
		}
	});
	}
}


/* if hover the mouse to particular cell the background color will added */

function fn_addcellcolor(position,cellid)
{
	var divid=cellid.split("_");
	
	if($('#'+cellid).html()!='' && position=="top")
	{
		$('#'+cellid).addClass('mousehover');
		$('#imagetop'+divid[1]+divid[2]).addClass('mousehoverimg');
	}
	
	if($('#'+cellid).html()!='' && position=="bottom")
	{
		$('#'+cellid).addClass('mousehover-bot');
		$('#imagebottom'+divid[1]+divid[2]).addClass('mousehoverimg');
	}
}

/* if mouse out to particular cell remove the background color  */

function fn_removecellcolor(position,cellid)
{
	var divid=cellid.split("_");
	
	if($('#'+cellid).html()!='' && position=="top")
	{
		$('#'+cellid).removeClass('mousehover');
		$('#imagetop'+divid[1]+divid[2]).removeClass('mousehoverimg');
	}
	
	if($('#'+cellid).html()!='' && position=="bottom")
	{
		$('#'+cellid).removeClass('mousehover-bot');
		$('#imagebottom'+divid[1]+divid[2]).removeClass('mousehoverimg');
	}
}

/* get modules based on license id */
function fn_loadmodules(scheduleid,type,asstype)
{
	var lid = $('#licenseid').val();		
		dataparam="oper=loadmodules&licenseid="+lid+"&scheduleid="+scheduleid+"&moduletype="+type+"&assigntype="+asstype;	 	
		$.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-rotation-ajax.php',
			data: dataparam,		
			success:function(ajaxdata) {
				$('#modules').html(ajaxdata);
				$('#modnxtstep').show();
				
                                fn_blockmodules();
				if(scheduleid>0)
				{
					fn_rotloadextendcontent(scheduleid,lid,"exc");
				}
                                else
                                {
                                   fn_checking(); 
                                }
				
			}
		});
} 

function fn_blockmodules()
{
    var lid = $('#licenseid').val();
    var list4 = [];
    
    if($('#scheduletype').val()==2)
    {
        var type='1';
    }
    else
    {
        var type='2';
    }
    
    $("div[id^=list4_]").each(function()
    {
            var replaceid='';
            var mtype="-"+type;
            replaceid=$(this).attr('id').replace('list4_','')
            list4.push(replaceid.replace(mtype,''));
    });
    
  
    dataparam="oper=blockmodules&licenseid="+lid+"&scheduleid="+$('#scheduleid').val()+"&moduletype="+$('#scheduletype').val()+"&modules="+list4;	
    
    $.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-rotation-ajax.php',
			data: dataparam,		
			success:function(ajaxdata) {
				$('#blockmodule').html(ajaxdata);
                        }
		});
}


function fn_blockstudent()
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
    
 
    dataparam="oper=blockstudents&licenseid="+lid+"&scheduleid="+$('#scheduleid').val()+"&classid="+$('#hidclassid').val()+"&students="+list10+"&studenttype="+$('#studenttype').val()+"&blockmodule="+$('#selectblockmodule').val();	
    
    $.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-rotation-ajax.php',
			data: dataparam,		
			success:function(ajaxdata) {
				$('#blockstudent').html(ajaxdata);
                        }
		});
}


function fn_blockmodstudent()
{
    
    if($('#hidscheduleid').val()!='' && $('#hidscheduleid').val()!='0')
    {
    var list26=[];
    
    $("div[id^=list26_]").each(function()
    {
            list26.push($(this).attr('id').replace('list26_',''));
    });
    
    var mtype = 1;
    if($('#scheduletype').val()==6){
            mtype = 2;
    }
    
    dataparam="oper=blockmodstudents&scheduleid="+$('#scheduleid').val()+"&classid="+$('#hidclassid').val()+"&students="+list26+"&blockmodule="+$('#selectblockmodule').val()+"&moduletype="+mtype;	
    
    $.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-rotation-ajax.php',
			data: dataparam,		
			success:function(ajaxdata) {
				showloadingalert("Saved Sucessfully.");	
				setTimeout("closeloadingalert();",1000);
                                fn_blockmodules();
                                setTimeout("fn_blockstudent();",1000);
                        }
		});
            }
            else
            {
                fn_saverotationalschedule(2);
            }
}




/* Compare student and module count if student count greater then to module count show the alert */
function fn_checking(type)
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
		modcount=modcount*parseInt(2)*parseInt($('#numberofcopies').val());
	}
	else
	{
		modcount=modcount*parseInt(2);
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
/* Show rotational schedule table */
function fn_viewrotationalschedule(classid,scheduleid)
{
	var scheduletypeid=2;

	setTimeout("removesections('#class-newclass-calendar');",500);  
	setTimeout('showpageswithpostmethod("class-newclass-viewschedule_edit","class/newclass/class-newclass-viewschedule_edit.php","id='+scheduleid+","+scheduletypeid+","+classid+'");',500);
}

/* events mouse - click,double click
single click - show the student list popup
douple click fill the background color top and bottom cell.
 */	
var DELAY = 250, clicks = 0, timer = null;

$(".clk").click(function(e) {
     var cellid= this.id;
    if (timer == null) {
        timer = setTimeout(function() {
           clicks = 0;
            timer = null;
			
           $('#tdval').val(cellid);
		   $('.popuptable').hide();
		   
		   var offset = $('#'+cellid).position();
		   var type=$('#schtype').val();
		   
		  
		 	   if(type=="create")
		   {
			   	var leftVal=offset.left+330;
			   	var topVal=offset.top+320;
			   }
			   else
			   {
				 var leftVal=offset.left+330;
			   	var topVal=offset.top+340; 
			   }
 
		  
		   
		   var o = {
            left: e.pageX-140,
            top: e.pageY
        }
       
		$('.popuptable').show(1000).offset(o);
			
        }, DELAY);
    }

    if(clicks === 1) {
         clearTimeout(timer);
         timer = null;
         clicks = -1;
		var id=cellid.split("_");
	
		if(id[0]=="seg1")
		{
			if($('#'+cellid).hasClass('lightrot') || $('#'+cellid).hasClass('darkrot'))
			{
				$('DIV.clk').removeClass('darkrot lightrot');
			}
			else
			{
				$('DIV.clk').removeClass('darkrot lightrot');
				var cell1='#'+cellid+" "+'span';
				var studentidup=$(cell1).attr('id');
			
				$('span[id^='+studentidup+']').each(function(){
				
						$(this).parent().addClass('lightrot');
					
					});
				
				var cell2='#seg2_'+id[1]+"_"+id[2]+" "+'span';
				
				var studentiddown=$(cell2).attr('id');
				
				$('span[id^='+studentiddown+']').each(function(){
				
						$(this).parent().addClass('darkrot');
					
					});
			}
	}
	else if(id[0]=="seg2")
	{
		if($('#'+cellid).hasClass('lightrot') || $('#'+cellid).hasClass('darkrot')) 
		{
				$('DIV.clk').removeClass('darkrot lightrot');
		}
		else
		{
			$('DIV.clk').removeClass('darkrot lightrot');
			var cell1='#'+cellid+" "+'span';
			var studentidup=$(cell1).attr('id');
			
			$('span[id^='+studentidup+']').each(function(){
			
					$(this).parent().addClass('lightrot');
					
				});
			
			var id=cellid.split("_");
			var cell2='#seg1_'+id[1]+"_"+id[2]+" "+'span';
			var studentiddown=$(cell2).attr('id');
		
			$('span[id^='+studentiddown+']').each(function(){
			
					$(this).parent().addClass('darkrot');
				
				});
		}
	}
        else if(id[0]=="seg3")
		{
			if($('#'+cellid).hasClass('lightrot') || $('#'+cellid).hasClass('darkrot'))
			{
				$('DIV.clk').removeClass('darkrot lightrot');
    }
			else
			{
				$('DIV.clk').removeClass('darkrot lightrot');
				var cell1='#'+cellid+" "+'span';
				var studentidup=$(cell1).attr('id');
			
				$('span[id^='+studentidup+']').each(function(){
				
						$(this).parent().addClass('lightrot');
					
					});
				
				var cell2='#seg4_'+id[1]+"_"+id[2]+" "+'span';
				
				var studentiddown=$(cell2).attr('id');
				
				$('span[id^='+studentiddown+']').each(function(){
				
						$(this).parent().addClass('darkrot');
					
					});
			}
	}
	else if(id[0]=="seg4")
	{
		if($('#'+cellid).hasClass('lightrot') || $('#'+cellid).hasClass('darkrot')) 
		{
				$('DIV.clk').removeClass('darkrot lightrot');
		}
		else
		{
			$('DIV.clk').removeClass('darkrot lightrot');
			var cell1='#'+cellid+" "+'span';
			var studentidup=$(cell1).attr('id');
			
			$('span[id^='+studentidup+']').each(function(){
			
					$(this).parent().addClass('lightrot');
					
				});
			
			var id=cellid.split("_");
			var cell2='#seg3_'+id[1]+"_"+id[2]+" "+'span';
			var studentiddown=$(cell2).attr('id');
		
			$('span[id^='+studentiddown+']').each(function(){
			
					$(this).parent().addClass('darkrot');
				
				});
		}
	}
    }
    clicks++;
});

/* Mouse - Over and Out
Over - Fill the color and delete image to top and bottom cell
Out  - Remove the color and delete image to top and bottom cell
*/
$(".clk").live({
	mouseover: function() {
		
	var cellid=this.id;
	
	var divid=cellid.split("_");
	var position=divid[0];
	
        if($('#'+cellid).html()!='' && $('#'+cellid).html()!='<span class="dragdrop ui-draggable ui-droppable">&nbsp;</span>' && $('#'+cellid).html()!='<span class="dragdrop">&nbsp;</span>' && $('#'+cellid).html()!="&nbsp;"  && position=="seg1")
	{
		$('#'+cellid).addClass('mousehover');
		$('#imagetop_'+divid[1]+"_"+divid[2]).addClass('mousehoverimg');
	}
	
	if($('#'+cellid).html()!='' && $('#'+cellid).html()!='<span class="dragdrop ui-draggable ui-droppable">&nbsp;</span>' && $('#'+cellid).html()!='<span class="dragdrop">&nbsp;</span>' && $('#'+cellid).html()!="&nbsp;"  && position=="seg2")
	{
		$('#'+cellid).addClass('mousehover-bot');
		$('#imagebottom_'+divid[1]+"_"+divid[2]).addClass('mousehoverimg');
	}
        
        if($('#'+cellid).html()!='' && $('#'+cellid).html()!='<span class="dragdrop ui-draggable ui-droppable">&nbsp;</span>' && $('#'+cellid).html()!='<span class="dragdrop">&nbsp;</span>' && $('#'+cellid).html()!="&nbsp;"  && position=="seg3")
	{
		$('#'+cellid).addClass('mousehover');
		$('#imagetopdup_'+divid[1]+"_"+divid[2]).addClass('mousehoverimg');
	}
        
        if($('#'+cellid).html()!='' && $('#'+cellid).html()!='<span class="dragdrop ui-draggable ui-droppable">&nbsp;</span>' && $('#'+cellid).html()!='<span class="dragdrop">&nbsp;</span>' && $('#'+cellid).html()!="&nbsp;"  && position=="seg4")
	{
		$('#'+cellid).addClass('mousehover-bot');
		$('#imagebottomdup_'+divid[1]+"_"+divid[2]).addClass('mousehoverimg');
	}
        
	},
	
	mouseout: function() {
	var cellid=this.id;
	
	var divid=cellid.split("_");
	var position=divid[0];
	
	if($('#'+cellid).html()!='' && $('#'+cellid).html()!='&nbsp;' && position=="seg1")
	{
		$('#'+cellid).removeClass('mousehover');
		$('#imagetop_'+divid[1]+"_"+divid[2]).removeClass('mousehoverimg');
	}
	
	if($('#'+cellid).html()!='' && $('#'+cellid).html()!='&nbsp;' && position=="seg2")
	{
		$('#'+cellid).removeClass('mousehover-bot');
		$('#imagebottom_'+divid[1]+"_"+divid[2]).removeClass('mousehoverimg');
	}
        
        if($('#'+cellid).html()!='' && $('#'+cellid).html()!='&nbsp;' && position=="seg3")
	{
		$('#'+cellid).removeClass('mousehover');
		$('#imagetopdup_'+divid[1]+"_"+divid[2]).removeClass('mousehoverimg');
	}
	
	if($('#'+cellid).html()!='' && $('#'+cellid).html()!='&nbsp;' && position=="seg4")
	{
		$('#'+cellid).removeClass('mousehover-bot');
		$('#imagebottomdup_'+divid[1]+"_"+divid[2]).removeClass('mousehoverimg');
	}
	},
        
        mousedown: function() {
            
            var cellid= this.id;
            $('#tdval').val(cellid);
	}
        
	});
	

/* events mouse - click,over and out */	
	$('.imagetop').die();
	$(".imagetop").live({
	
	click: function() {
		
		var stuname= new Array();
		var cellid=this.id;
		var cellid=cellid.replace("imagetop","seg1");
		studentid=($('#'+cellid+" "+'span').attr('id'));
		var getid=cellid.split("_");
		var rowclass=$('#stu_'+getid[1]+getid[2]).closest('tr').attr('class');
		var moduleid=rowclass.split("-");
		
		dataparam="oper=checkstudentscore&studentid="+studentid+"&classid="+$('#classid').val()+"&scheduleid="+$('#scheduleid').val()+"&moduleid="+moduleid[0]+"&scheduletype="+$('#scheduletype').val()+"&modtype="+moduleid[1];
		
		$.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-rotation-ajax.php',
			data: dataparam,
			baforeSend:function(){
					$.Zebra_Dialog('loading please wait', {
					'buttons':  false,
					'position': ['right - 20', 'top + 20'],
					'auto_close': 2000
				});
			},
			success:function(data) {
				
					if(data=="fail")
					{
						$.Zebra_Dialog('Are you sure you want to delete ?',
							 {
							'type':     'confirmation',
							'buttons':  [
											{caption: 'No', callback: function() { }},
											{caption: 'Yes', callback: function() { 
											
												$('#'+cellid).html('&nbsp;');	
												$('div.clk').removeClass('lightrot darkrot');
												
												
												var IDs = [];
												$("#myTable05").find("span").each(function(){ IDs.push(this.id); });
												
												if(IDs.length=='0')
												{
													$('#save').addClass('dim');
												}
											
											}},
										]
							});
							return false;
					}
					else
					{
						$.Zebra_Dialog('This student grades will be lost, Are you sure you want to delete ?',
							 {
							'type':     'confirmation',
							'buttons':  [
											{caption: 'No', callback: function() { }},
											{caption: 'Yes', callback: function() { 
											
												$('#'+cellid).html('&nbsp;');
												$('div.clk').removeClass('lightrot darkrot');
												
												var IDs = [];
												$("#myTable05").find("span").each(function(){ IDs.push(this.id); });
												
												if(IDs.length=='0')
												{
													$('#save').addClass('dim');
												}	
											
											}},
										]
							});
							return false;
					}
				}
		});
	},
	mouseover: function() {
		
	var cellid=this.id;
	var cellid=cellid.replace("imagetop","seg1");
	var divid=cellid.split("_");
	var position=divid[0];
	
	if($('#'+cellid).html()!='' && $('#'+cellid).html()!='&nbsp;' && $('#'+cellid).html()!='<span class="dragdrop ui-draggable ui-droppable">&nbsp;</span>' && $('#'+cellid).html()!='<span class="dragdrop">&nbsp;</span>' && position=="seg1")
	{
		$('#'+cellid).addClass('mousehover');
		$('#imagetop_'+divid[1]+"_"+divid[2]).addClass('mousehoverimg');
	}
	},
	
	mouseout: function() {
	var cellid=this.id;
	var cellid=cellid.replace("imagetop","seg1");
	var divid=cellid.split("_");
	var position=divid[0];
	
	if($('#'+cellid).html()!='' && $('#'+cellid).html()!='&nbsp;' && position=="seg1")
	{
		$('#'+cellid).removeClass('mousehover');
		$('#imagetop_'+divid[1]+"_"+divid[2]).removeClass('mousehoverimg');
	}
	}
	});
	
/* events mouse - click,over and out */	
$('.imagebottom').die();	
$(".imagebottom").live({
	click: function() {
		
		var stuname= new Array();
		var cellid=this.id;
		var cellid=cellid.replace("imagebottom","seg2");
		studentid=($('#'+cellid+''+'span').attr('id'));
		var getid=cellid.split("_");
		var rowclass=$('#stu_'+getid[1]+getid[2]).closest('tr').attr('class');
		var moduleid=rowclass.split("-");
		
		dataparam="oper=checkstudentscore&studentid="+studentid+"&classid="+$('#classid').val()+"&scheduleid="+$('#scheduleid').val()+"&moduleid="+moduleid[0]+"&scheduletype="+$('#scheduletype').val()+"&modtype="+moduleid[1];
		
		$.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-rotation-ajax.php',
			data: dataparam,	
			baforeSend:function(){
					$.Zebra_Dialog('loading please wait', {
					'buttons':  false,
					'position': ['right - 20', 'top + 20'],
					'auto_close': 2000
				});
			},	
			success:function(data) {
				
					if(data=="fail")
					{
						$.Zebra_Dialog('Are you sure you want to delete ?',
							 {
							'type':     'confirmation',
							'buttons':  [
											{caption: 'No', callback: function() { }},
											{caption: 'Yes', callback: function() { 
											
												$('#'+cellid).html('&nbsp;');	
												$('div.clk').removeClass('lightrot darkrot');
												
												var IDs = [];
												$("#myTable05").find("span").each(function(){ IDs.push(this.id); });
												
												if(IDs.length=='0')
												{
													$('#save').addClass('dim');
												}
											
											}},
										]
							});
							return false;
					}
					else
					{
						$.Zebra_Dialog('This student grades will be lost, Are you sure you want to delete ?',
							 {
							'type':     'confirmation',
							'buttons':  [
											{caption: 'No', callback: function() { }},
											{caption: 'Yes', callback: function() { 
											
												$('#'+cellid).html('&nbsp;');	
												$('div.clk').removeClass('lightrot darkrot'); 
												
												var IDs = [];
												$("#myTable05").find("span").each(function(){ IDs.push(this.id); });
												
												if(IDs.length=='0')
												{
													$('#save').addClass('dim');
												}
											
											}},
										]
							});
							return false;
					}
				}
		});
	},
	mouseover: function() {
		
	var cellid=this.id;
	var cellid=cellid.replace("imagebottom","seg2");
	var divid=cellid.split("_");
	var position=divid[0];
	
	if($('#'+cellid).html()!='' && $('#'+cellid).html()!='&nbsp;' && $('#'+cellid).html()!='<span class="dragdrop ui-draggable ui-droppable">&nbsp;</span>' && $('#'+cellid).html()!='<span class="dragdrop">&nbsp;</span>' && position=="seg2")
	{
		$('#'+cellid).addClass('mousehover');
		$('#imagebottom_'+divid[1]+"_"+divid[2]).addClass('mousehoverimg');
	}
	},
	
	mouseout: function() {
	var cellid=this.id;
	var cellid=cellid.replace("imagebottom","seg2");
	var divid=cellid.split("_");
	var position=divid[0];
	
	if($('#'+cellid).html()!='' && $('#'+cellid).html()!='&nbsp;' && position=="seg2")
	{
		$('#'+cellid).removeClass('mousehover');
		$('#imagebottom_'+divid[1]+"_"+divid[2]).removeClass('mousehoverimg');
	}
	}
	});

        
        
        /* events mouse - click,over and out */	
	$('.imagetopdup').die();
	$(".imagetopdup").live({
	
	click: function() {
		
		var stuname= new Array();
		var cellid=this.id;
		var cellid=cellid.replace("imagetopdup","seg3");
		studentid=($('#'+cellid+" "+'span').attr('id'));
		var getid=cellid.split("_");
		var rowclass=$('#stu_'+getid[1]+getid[2]).closest('tr').attr('class');
		var moduleid=rowclass.split("-");
		
		dataparam="oper=checkstudentscore&studentid="+studentid+"&classid="+$('#classid').val()+"&scheduleid="+$('#scheduleid').val()+"&moduleid="+moduleid[0]+"&scheduletype="+$('#scheduletype').val()+"&modtype="+moduleid[1];
		
		$.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-rotation-ajax.php',
			data: dataparam,
			baforeSend:function(){
					$.Zebra_Dialog('loading please wait', {
					'buttons':  false,
					'position': ['right - 20', 'top + 20'],
					'auto_close': 2000
				});
			},
			success:function(data) {
				
					if(data=="fail")
					{
						$.Zebra_Dialog('Are you sure you want to delete ?',
							 {
							'type':     'confirmation',
							'buttons':  [
											{caption: 'No', callback: function() { }},
											{caption: 'Yes', callback: function() { 
											
												$('#'+cellid).html('&nbsp;');	
												$('div.clk').removeClass('lightrot darkrot');
												
												
												var IDs = [];
												$("#myTable05").find("span").each(function(){ IDs.push(this.id); });
												
												if(IDs.length=='0')
												{
													$('#save').addClass('dim');
												}
											
											}},
										]
							});
							return false;
					}
					else
					{
						$.Zebra_Dialog('This student grades will be lost, Are you sure you want to delete ?',
							 {
							'type':     'confirmation',
							'buttons':  [
											{caption: 'No', callback: function() { }},
											{caption: 'Yes', callback: function() { 
											
												$('#'+cellid).html('&nbsp;');
												$('div.clk').removeClass('lightrot darkrot');
												
												var IDs = [];
												$("#myTable05").find("span").each(function(){ IDs.push(this.id); });
												
												if(IDs.length=='0')
												{
													$('#save').addClass('dim');
												}	
											
											}},
										]
							});
							return false;
					}
				}
		});
	},
	mouseover: function() {
		
	var cellid=this.id;
	var cellid=cellid.replace("imagetopdup","seg3");
	var divid=cellid.split("_");
	var position=divid[0];
	
	if($('#'+cellid).html()!='' && $('#'+cellid).html()!='&nbsp;' && $('#'+cellid).html()!='<span class="dragdrop ui-draggable ui-droppable">&nbsp;</span>' && $('#'+cellid).html()!='<span class="dragdrop">&nbsp;</span>' && position=="seg3")
	{
		$('#'+cellid).addClass('mousehover');
		$('#imagetopdup_'+divid[1]+"_"+divid[2]).addClass('mousehoverimg');
	}
	},
	
	mouseout: function() {
	var cellid=this.id;
	var cellid=cellid.replace("imagetopdup","seg3");
	var divid=cellid.split("_");
	var position=divid[0];
	
	if($('#'+cellid).html()!='' && $('#'+cellid).html()!='&nbsp;' && position=="seg3")
	{
		$('#'+cellid).removeClass('mousehover');
		$('#imagetopdup_'+divid[1]+"_"+divid[2]).removeClass('mousehoverimg');
	}
	}
	});
	
/* events mouse - click,over and out */	
$('.imagebottomdup').die();	
$(".imagebottomdup").live({
	click: function() {
		
		var stuname= new Array();
		var cellid=this.id;
		var cellid=cellid.replace("imagebottomdup","seg4");
		studentid=($('#'+cellid+''+'span').attr('id'));
		var getid=cellid.split("_");
		var rowclass=$('#stu_'+getid[1]+getid[2]).closest('tr').attr('class');
		var moduleid=rowclass.split("-");
		
		dataparam="oper=checkstudentscore&studentid="+studentid+"&classid="+$('#classid').val()+"&scheduleid="+$('#scheduleid').val()+"&moduleid="+moduleid[0]+"&scheduletype="+$('#scheduletype').val()+"&modtype="+moduleid[1];
		
		$.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-rotation-ajax.php',
			data: dataparam,	
			baforeSend:function(){
					$.Zebra_Dialog('loading please wait', {
					'buttons':  false,
					'position': ['right - 20', 'top + 20'],
					'auto_close': 2000
				});
			},	
			success:function(data) {
				
					if(data=="fail")
					{
						$.Zebra_Dialog('Are you sure you want to delete ?',
							 {
							'type':     'confirmation',
							'buttons':  [
											{caption: 'No', callback: function() { }},
											{caption: 'Yes', callback: function() { 
											
												$('#'+cellid).html('&nbsp;');	
												$('div.clk').removeClass('lightrot darkrot');
												
												var IDs = [];
												$("#myTable05").find("span").each(function(){ IDs.push(this.id); });
												
												if(IDs.length=='0')
												{
													$('#save').addClass('dim');
												}
											
											}},
										]
							});
							return false;
					}
					else
					{
						$.Zebra_Dialog('This student grades will be lost, Are you sure you want to delete ?',
							 {
							'type':     'confirmation',
							'buttons':  [
											{caption: 'No', callback: function() { }},
											{caption: 'Yes', callback: function() { 
											
												$('#'+cellid).html('&nbsp;');	
												$('div.clk').removeClass('lightrot darkrot'); 
												
												var IDs = [];
												$("#myTable05").find("span").each(function(){ IDs.push(this.id); });
												
												if(IDs.length=='0')
												{
													$('#save').addClass('dim');
												}
											
											}},
										]
							});
							return false;
					}
				}
		});
	},
	mouseover: function() {
		
	var cellid=this.id;
	var cellid=cellid.replace("imagebottomdup","seg4");
	var divid=cellid.split("_");
	var position=divid[0];
	
	if($('#'+cellid).html()!='' && $('#'+cellid).html()!='&nbsp;' && $('#'+cellid).html()!='<span class="dragdrop ui-draggable ui-droppable">&nbsp;</span>' && $('#'+cellid).html()!='<span class="dragdrop">&nbsp;</span>' && position=="seg4")
	{
		$('#'+cellid).addClass('mousehover');
		$('#imagebottomdup_'+divid[1]+"_"+divid[2]).addClass('mousehoverimg');
	}
	},
	
	mouseout: function() {
	var cellid=this.id;
	var cellid=cellid.replace("imagebottomdup","seg4");
	var divid=cellid.split("_");
	var position=divid[0];
	
	if($('#'+cellid).html()!='' && $('#'+cellid).html()!='&nbsp;' && position=="seg4")
	{
		$('#'+cellid).removeClass('mousehover');
		$('#imagebottomdup_'+divid[1]+"_"+divid[2]).removeClass('mousehoverimg');
	}
	}
	});

/*
fn_rotationalexport - Export
id - Schedule id
*/
function fn_rotationalexport(sid,stype,classid,sname)
{
	window.location='class/newclass/class-newclass-rotationalexport.php?id='+sid+","+stype+","+classid+","+sname+'';
}

/*
fn_deleterotationschedule - Delete schedule
scheduleid
type - rotational or dyad or triad
classid
*/
function fn_deleterotationschedule(scheduleid,type,classid)
{
	
	$.Zebra_Dialog('Are you sure you want to delete ?',
							 {
							'type':     'confirmation',
							'buttons':  [
											{caption: 'No', callback: function() { }},
											{caption: 'Yes', callback: function() { 
											
											dataparam="oper=deleteschedule&scheduleid="+scheduleid+"&type="+type;	
											 	
											$.ajax({
												type: 'post',
												url: 'class/newclass/class-newclass-rotation-ajax.php',
												data: dataparam,		
												beforeSend: function(){
													showloadingalert('Loading, Please wait.');
												},
												success:function(data) {
													console.log(data);
													setTimeout('closeloadingalert()',1000);
													setTimeout("showloadingalert('Deleted Successfully.');",500);
													setTimeout("closeloadingalert();",1000);
													
													setTimeout("removesections('#class-newclass-steps');",500);	
							setTimeout("removesections('#class-newclass-actions');",500);			
						setTimeout('showpageswithpostmethod("class-newclass-scheduledetails","class/newclass/class-newclass-scheduledetails.php","id='+classid+","+$('#classtypeval').val()+'");',500);		
													
												}
											});
											
											}},
											
										]
							});
	
}


function fillcolor(cellname,cellid)
{
	var id=cellid.split("_");
	if(cellname=="seg1")
	{
	
		$('DIV.clk').removeClass('darkrot lightrot');
		
		$('DIV.clk').removeClass('darkrot lightrot');
		var cell1='#'+cellid+" "+'span';
		var studentidup=$(cell1).attr('id');
		
		$('span[id^='+studentidup+']').each(function(){
		
		$(this).parent().addClass('lightrot');
		
		});
		
		var cell2='#seg2_'+id[1]+"_"+id[2]+" "+'span';
		
		var studentiddown=$(cell2).attr('id');
		
		$('span[id^='+studentiddown+']').each(function(){
		
		$(this).parent().addClass('darkrot');
		
		});
	
	}
	else
	{
	
		$('DIV.clk').removeClass('darkrot lightrot');
		
		$('DIV.clk').removeClass('darkrot lightrot');
		var cell1='#'+cellid+" "+'span';
		var studentidup=$(cell1).attr('id');
		
		$('span[id^='+studentidup+']').each(function(){
		
		$(this).parent().addClass('lightrot');
		
		});
		
		var id=cellid.split("_");
		var cell2='#seg1_'+id[1]+"_"+id[2]+" "+'span';
		var studentiddown=$(cell2).attr('id');
		
		$('span[id^='+studentiddown+']').each(function(){
		
		$(this).parent().addClass('darkrot');
		
		});
	}
}

/* show the rotational schedular form */
function fn_rotloadcontent(lid,sid,type)
{		
	if(type==0)
	{
	   sid=$('#moduletemplateid').val();
	}		
		dataparam="oper=rotloadcontent&lid="+lid+"&sid="+sid+"&classid="+$('#hidclassid').val()+"&type="+type;		
		$.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-rotation-ajax.php',
			data: dataparam,		
			success:function(ajaxdata) {
				var classid=$('#hidclassid').val();
				$('#rotcontent').html(ajaxdata);	
                                fn_blockstudent();
				if(sid!=0)
				{
					if($('#rotationtype').val()=="update")
					{
					setTimeout('showpageswithpostmethod("class-newclass-viewschedule_edit","class/newclass/class-newclass-viewschedule_edit.php","id='+sid+","+classid+'");',500);	
					}
					
				}		
			}
		});	
}

/***********Mohan M Updated by [11-8-2015] one or more Extend Content option code start here*********/
function fn_fillnameformod(rowid,modid)
{
    var modids = [];   
    $('.ads_Checkbox_'+modid+':checked').each(function(){
           modids.push($(this).val());
    });
    
    

    var finalmodname='';   
        
    for(i=0;i<modids.length;i++){
        var data=modids[i].split("_");       
        if(i==0){
             finalmodname=data[3];    
        }
        else if(i>=3){
             finalmodname= finalmodname+"...";     
        }
        else{
             finalmodname= finalmodname+","+data[3];     
        }
    }   
    if(modids.length==0){
          finalmodname= "Select Extend Content";
    }

    $('#modname_'+modid).html(finalmodname);
    
    $('#selectallmod_'+modid).val('0');                
   
}
function fn_selectallmod(modid)
{
   
    $('#selectallmod_'+modid).val('01');
    var finalmodname='Select All';
    $('#modname_'+modid).html(finalmodname);
    
    $('.ads_Checkbox_'+modid).prop('checked', false); // Unchecks it
    
    
}


/***********Mohan M Updated by [11-8-2015] one or more Extend Content option code start here*********/


/* save the rotational schedular details */
function fn_saverotationalschedule(flag)
{
	$('#enddate').val('03/03/3000');
	if($("#scheduleform").validate().form() && $("#sform").validate().form())
	{
			
		var list10 = [];
		var list4 = [];
		var list9=[];
                var list26=[];
		var extids = [];
		
                var modids = [];
		var selectallmodids=[];
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
		 $.Zebra_Dialog('<strong>Please select anyone module</strong>', {
		'buttons':  false,
		'auto_close': 3000
		});
		return false;
		}
		var mtype = 1;
		if($('#scheduletype').val()==6){
			mtype = 2;
		}
		
		$("input[id^=exid_]").each(function()
		{
			extids.push($(this).val());
		});
                
              /***********Mohan M Updated by [11-8-2015] one or more Extend Content option code start here*********/
                    $("input[id^=mod_]").each(function()
                    {                            
                            var mlessonid=$(this).attr('name').replace('mod_','');                     
                            if($('#mod_'+mlessonid).is(':checked')){
                                modids.push(mlessonid);
                            }
                    });
                    
                    $("input[id^=selectallmod_]").each(function()
                    {                            
                            var mlessonid1=$(this).attr('id').replace('selectallmod_','');                     
                             var existmod=$('#selectallmod_'+mlessonid1).val();
                             if(existmod=='01'){
                                var selallmodids=$(this).attr('name').replace('selectallmod_','')+"~";                              
                                selectallmodids.push(selallmodids);
                             }
                    });                   
               /***********Mohan M Updated by [11-8-2015] one or more Extend Content option code start here*********/
                
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
		
		
		var sname = escapestr($('#sname').val());
		var stype = $('#scheduletype').val();
		var dataparam="oper=saveschedule&sname="+sname+"&startdate="+$('#startdate').val()+"&scheduletype="+stype+"&studenttype="+$('#studenttype').val()+"&numberofcopies="+$('#numberofcopies').val()+"&numberofrotations="+$('#numberofrotations').val()+"&rotationlength="+$('#rotationlength').val()+"&sid="+$('#hidscheduleid').val()+"&students="+list10+"&modules="+list4+"&classid="+$('#hidclassid').val()+"&licenseid="+$('#licenseid').val()+"&moduletype="+mtype+"&unstudents="+list9+"&extids="+extids+"&blockmodule="+$('#selectblockmodule').val()+"&blockstudents="+list26+"&modids="+modids+"&selectallmodids="+selectallmodids; 
	
		
		$.ajax({
			type: 'post',
			url: 'class/newclass/class-newclass-rotation-ajax.php',
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
						setTimeout('showpageswithpostmethod("class-newclass-viewschedule_create","class/newclass/class-newclass-viewschedule_create.php","id='+sid+","+classid+'");',500);
						}
						else
						{
						
						setTimeout("removesections('#class-newclass-newschedulestep');",500);
						setTimeout('showpageswithpostmethod("class-newclass-viewschedule_edit","class/newclass/class-newclass-viewschedule_edit.php","id='+sid+","+classid+'");',500);
							
						}
					}
					else if(flag==1)
					{
							setTimeout("removesections('#class-newclass-steps');",500);	
							setTimeout("removesections('#class-newclass-actions');",500);			
						setTimeout('showpageswithpostmethod("class-newclass-scheduledetails","class/newclass/class-newclass-scheduledetails.php","id='+$('#hidclassid').val()+","+$('#classtypeval').val()+'");',500);
						setTimeout('showpageswithpostmethod("class-newclass-viewschedule_edit","class/newclass/class-newclass-viewschedule_edit.php","id='+sid+","+stype+","+classid+","+sname+',viewrot'+'");',1000);
						
				    }  
                                        else if(flag==2)
                                        {
                                             showloadingalert("Saved Sucessfully.");	
                                             setTimeout("closeloadingalert();",1000);
                                             fn_blockmodules();
                                             setTimeout("fn_blockstudent();",1000);
                                        }
                                         
				}
                                
                                if(trim(data[0])=="exceed")
				{
                                   
					 showloadingalert("Student limit exceed");
					 setTimeout('closeloadingalert()',1000);
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

function fn_rotloadextendcontent(scheduleid,licenseid,type)
{
	
	if((type=="mod" && $('#excflag').val()==1) || type=="exc")
	{
	var list4 = [];	 //module id
	
	$("div[id^=list4_]").each(function()
	{
			list4.push($(this).attr('id').replace('list4_',''));
	});
		
	if(list4=='' && type=="exc"){
		alert("Please select any module");
		return false;
	}
	var dataparam = "oper=loadextendcontent&list4="+list4+"&scheduleid="+scheduleid+"&licenseid="+licenseid;	
	
	$.ajax({
		type: 'post',
		url: "class/newclass/class-newclass-rotation-ajax.php",
		data: dataparam,
		beforeSend: function(){			
		},
		success:function(data) {
			$('#extendcontent').html(data);
		}
		
	});	
	}
}

function fn_generatedispersed(rotflag)
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
	
	var combination="true";
	
	var newstu = $('#stuidname').val();
	var tempstu ='';
	
	if(rotflag!="gen")
	{
		showloadingalert("Generating the Rotational schedule.");	
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

	
                i=start;
                var retry=0;
		var pairstudenttemp=new Array();
		var pairstudentper=new Array();

		while(i<=end)  // Row 
		{
			stuname = arrayShuffle(stunamecopy);
			var j=2;
                        var tk=0;
			var count=0;	
			while(j<trlength) // column
			{
				var rowclassname= $('#stu_'+j+i).closest('tr').attr('class');
				for(zk=0;zk<stuname.length;zk++) 
				{
					
					// check the first segment,check the first segment for empty,check the first segment with student zk
					var splitstuname=stuname[zk].split("~");
					if($('#seg1_'+j+"_"+i).html()!="&nbsp;")
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
                                                
                                                
						
						seg1row=tabledisrowfalse(rowid,thcount,rowclassname,'<span id="'+splitstuname[1]+'">'+splitstuname[0]+'</span>');
                                                
                                               
						
						if(seg1row=="false") // student in same row or same column 
						{
							// don't insert the student zk
						}
						else
						{
							// insert the student zk in the first segment
							$('#seg1_'+j+"_"+i).html('<span id="'+splitstuname[1]+'">'+splitstuname[0]+'</span>');
							var studet=new Array(splitstuname[0]+"~"+splitstuname[1]);
							stuname=arraycompare(stuname,studet);
							count++;
							break;
							
						}
					}
				}
				
                                j++;
				tk++;
					
					
				} // j loop end
			
			
							var seat='';
							if(trcount+parseInt(1)>=i)
							{
								if(retry==100)
								{
									var seat="true";
									retry=0;
								}
								else
								{
									if(totseats>totstucount)
									{
										
										if(count!=trcount)
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
								
								$('.row'+i).empty();
								$('.row'+i).html('&nbsp;');
								
							}
							else
							{
								i++;
								retry=0;
							}
						
		} // i while loop end
                
                i=start;
                var retry=0;
		var pairstudenttemp=new Array();
		var pairstudentper=new Array();

		while(i<=end)  // Row 
		{
			stuname = arrayShuffle(stunamecopy);
			var j=2;
                        var tk=0;
			var count=0;	
			while(j<trlength) // column
			{
				var rowclassname= $('#stu_'+j+i).closest('tr').attr('class');
				for(zk=0;zk<stuname.length;zk++) 
				{
					
					// check the first segment,check the first segment for empty,check the first segment with student zk
					var splitstuname=stuname[zk].split("~");
					if($('#seg2_'+j+"_"+i).html()!="&nbsp;")
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
						
						seg1row=tabledisrowfalse(rowid,i,rowclassname,'<span id="'+splitstuname[1]+'">'+splitstuname[0]+'</span>');
                                                
                                                 seg1col=tablecolfalse(trcount,i,rowclassname,'<span id="'+splitstuname[1]+'">'+splitstuname[0]+'</span>');
						
						if(seg1row=="false" || seg1col=="false") // student in same row or same column 
						{
							// don't insert the student zk
						}
						else
						{
							// insert the student zk in the first segment
							$('#seg2_'+j+"_"+i).html('<span id="'+splitstuname[1]+'">'+splitstuname[0]+'</span>');
							var studet=new Array(splitstuname[0]+"~"+splitstuname[1]);
							stuname=arraycompare(stuname,studet);
							count++;
							break;
							
						}
					}
				}
				
                                j++;
				tk++;
					
					
				} // j loop end
			
			
							var seat='';
							if(trcount+parseInt(1)>=i)
							{
								if(retry==100)
								{
                                                                         $('.row'+i).empty();
                                                                       $('.row'+i).html('&nbsp;');
                                                                       var c=0;
                                                                       c=i;
                                                                       while(c<=i)  // Row 
                                                                       {
                                                                                stuname = arrayShuffle(stunamecopy);
                                                                                var j=2;
                                                                                var tk=0;
                                                                                var count=0;	
                                                                                while(j<trlength) // column
                                                                                {
                                                                                        var rowclassname= $('#stu_'+j+i).closest('tr').attr('class');
                                                                                        for(zk=0;zk<stuname.length;zk++) 
                                                                                        {

                                                                                                // check the first segment,check the first segment for empty,check the first segment with student zk
                                                                                                var splitstuname=stuname[zk].split("~");
                                                                                                if($('#seg1_'+j+"_"+i).html()!="&nbsp;")
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



                                                                                                        seg1row=tabledisrowfalse(rowid,thcount,rowclassname,'<span id="'+splitstuname[1]+'">'+splitstuname[0]+'</span>');



                                                                                                        if(seg1row=="false") // student in same row or same column 
                                                                                                        {
                                                                                                                // don't insert the student zk
                                                                                                        }
                                                                                                        else
                                                                                                        {
                                                                                                                // insert the student zk in the first segment
                                                                                                                $('#seg1_'+j+"_"+i).html('<span id="'+splitstuname[1]+'">'+splitstuname[0]+'</span>');
                                                                                                                var studet=new Array(splitstuname[0]+"~"+splitstuname[1]);
                                                                                                                stuname=arraycompare(stuname,studet);
                                                                                                                count++;
                                                                                                                break;

                                                                                                        }
                                                                                                }
                                                                                        }

                                                                                        j++;
                                                                                        tk++;


                                                                                        } // j loop end


                                                                                                                var seat='';
                                                                                                                if(trcount+parseInt(1)>=i)
                                                                                                                {
                                                                                                                        if(retry==50)
                                                                                                                        {
                                                                                                                                var seat="true";
                                                                                                                                retry=0;
                                                                                                                        }
                                                                                                                        else
                                                                                                                        {
                                                                                                                                if(totseats>totstucount)
                                                                                                                                {

                                                                                                                                        if(count!=trcount)
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

                                                                                                                        $('.row'+i).empty();
                                                                                                                        $('.row'+i).html('&nbsp;');

                                                                                                                }
                                                                                                                else
                                                                                                                {
                                                                                                                        c++;
                                                                                                                        retry=0;
                                                                                                                }

                                                                        }
                                                                        
                                                        var d=0;
                                                        d=i;                
                                                        while(d<=i)  // Row 
                                                        {
                                                                stuname = arrayShuffle(stunamecopy);
                                                                var j=2;
                                                                var tk=0;
                                                                var count=0;	
                                                                while(j<trlength) // column
                                                                {
                                                                        var rowclassname= $('#stu_'+j+i).closest('tr').attr('class');
                                                                        for(zk=0;zk<stuname.length;zk++) 
                                                                        {

                                                                                // check the first segment,check the first segment for empty,check the first segment with student zk
                                                                                var splitstuname=stuname[zk].split("~");
                                                                                if($('#seg2_'+j+"_"+i).html()!="&nbsp;")
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

                                                                                        seg1row=tabledisrowfalse(rowid,i,rowclassname,'<span id="'+splitstuname[1]+'">'+splitstuname[0]+'</span>');

                                                                                         seg1col=tablecolfalse(trcount,i,rowclassname,'<span id="'+splitstuname[1]+'">'+splitstuname[0]+'</span>');

                                                                                        if(seg1row=="false" || seg1col=="false") // student in same row or same column 
                                                                                        {
                                                                                                // don't insert the student zk
                                                                                        }
                                                                                        else
                                                                                        {
                                                                                                // insert the student zk in the first segment
                                                                                                $('#seg2_'+j+"_"+i).html('<span id="'+splitstuname[1]+'">'+splitstuname[0]+'</span>');
                                                                                                var studet=new Array(splitstuname[0]+"~"+splitstuname[1]);
                                                                                                stuname=arraycompare(stuname,studet);
                                                                                                count++;
                                                                                                break;

                                                                                        }
                                                                                }
                                                                        }

                                                                        j++;
                                                                        tk++;


                                                                        } // j loop end


                                                                                                var seat='';
                                                                                                if(trcount+parseInt(1)>=i)
                                                                                                {
                                                                                                        if(retry==50)
                                                                                                        {
                                                                                                                var seat="true";
                                                                                                                retry=0;
                                                                                                        }
                                                                                                        else
                                                                                                        {
                                                                                                                if(totseats>totstucount)
                                                                                                                {
                                                                                                                        count=count+parseInt(trcount);
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

                                                                                                        for(z=2;z<=trlength+parseInt(1);z++)
                                                                                                        {
                                                                                                           $('#seg2_'+z+"_"+i).html('&nbsp;'); 
                                                                                                        }

                                                                                                }
                                                                                                else
                                                                                                {
                                                                                                        d++;
                                                                                                        retry=0;
                                                                                                        
                                                                                                }

                                                        } // i while loop end
                                                                        var seat="true";
									retry=0;
								}
								else
								{
									if(totseats>totstucount)
									{
										count=count+parseInt(trcount);
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
								
								for(z=2;z<=trlength+parseInt(1);z++)
                                                                {
                                                                   $('#seg2_'+z+"_"+i).html('&nbsp;'); 
                                                                }
								
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
	
	
	$('span[id^="0"]').replaceWith("&nbsp;");
	var s=new Array();
	s=$('#stuidname').val().split(",");
	var y=new Array();
	y=tempstu.split(",");
	$('#stuidname').val(arraycompare(s,y));
	
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
						
						dataparam="oper=checkstudentmod"+"&celldet="+cell+"&scheduleid="+$('#scheduleid').val();
						
						$.ajax({
						type: 'post',
						url: 'class/newclass/class-newclass-rotation-ajax.php',
						data: dataparam,
						success:function(data) {
								if(data!="")
								{
									$.Zebra_Dialog("<div style='text-align:left'>"+data+"</div>");
									$('.ZebraDialog').css({"left":"250px","width":"1100px"});
									for(i=start;i<=thcount;i++)
									{
										$('.row'+i).empty();
										$('.row'+i).html('&nbsp;');
									}
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


function tablecolfalse(trlength,i,rowclassname,studentinfo)
{
	var seg1col='true';
	for(zi=2;zi<=trlength+parseInt(1);zi++)
	{
                if(($('#seg1_'+zi+"_"+i).html()==studentinfo) || ($('#seg2_'+zi+"_"+i).html()==studentinfo))
                {

                        // don't insert the student zk	
                        seg1col="false";
                        break;
                }
		
		
		if(seg1col=="false")
		{
			break;
		}
	}
	
	return seg1col;
}

function tabledisrowfalse(trlength,i,rowclassname,studentinfo)
{
	var seg1row='true';
	for(zi=0;zi<trlength.length;zi++)
	{
		var rowid=trlength[zi].split("_");
		for(zj=2;zj<=i+parseInt(1);zj++)
		{
			
				if(($('#seg1_'+rowid[1]+"_"+zj).html()==studentinfo) || ($('#seg2_'+rowid[1]+"_"+zj).html()==studentinfo))
				{
					
					// don't insert the student zk	
					seg1row="false";
					break;
				}
		}
		
		if(seg1row=="false")
		{
			break;
		}
	}
	
	return seg1row;
}

function fn_autoblock(sid)
{
    $('#autoblockstu').val('null');

    if($('#autoblock').is(':checked'))
    {
        var trlength=$("#myTable05 tr").length;
        var j=0;
        var moduleid='';
        var module=new Array();
        $('#generatebtn').addClass('dim');

        for(i=2;i<trlength;i++)
	{
		moduleid = $('#tr_'+i).attr('class');	

                if(moduleid!='undefined')
		{
                    module[j]=moduleid;
		}
		j++;
	}
                                                                      
        dataparam="oper=autoblock"+"&scheduleid="+sid+"&moduleid="+module+"&moduletype="+$('#moduletype').val();
						
        $.ajax({
        type: 'post',
        url: 'class/newclass/class-newclass-rotation-ajax.php',
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





                                                                      
