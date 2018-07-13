
function fn_showexp(){
    $('#misondiv').hide();
    $('#destinationdiv').hide();
    $('#taskdiv').hide();
    $('#resourcediv').hide();
    $('#districtdiv').hide();
    $('#schooldiv').hide();
    $('#viewreportdiv').hide(); 
        
	var dataparam = "oper=showexpforpitsco";      
	$.ajax({
		type: 'post',
		url: 'reports/completionreport/reports-completionreport-byschoolajax.php',
		data: dataparam,
		beforeSend: function(){
		},
		success:function(data) {
			$('#expdiv').show();	
			$('#expdiv').html(data);//Used to load the student details in the dropdown
                        
		}
	});

}

function fn_showmis(){
    $('#expdiv').hide();
    $('#destinationdiv').hide();
    $('#taskdiv').hide();
    $('#resourcediv').hide();
    $('#districtdiv').hide();
    $('#schooldiv').hide();
    $('#viewreportdiv').hide(); 
        
	var dataparam = "oper=showmisforpitsco";        
	$.ajax({
		type: 'post',
		url: 'reports/completionreport/reports-completionreport-byschoolajax.php',
		data: dataparam,
		beforeSend: function(){
		},
		success:function(data) {
			$('#misondiv').show();	
			$('#misondiv').html(data);//Used to load the student details in the dropdown
                        
		}
	});
     
}


function fn_showdestinationforexp(expid)
{
        
        $('#taskdiv').hide();
        $('#resourcediv').hide();
         $('#districtdiv').hide();
        $('#schooldiv').hide();
        $('#viewreportdiv').hide();
        
	var dataparam = "oper=showdestinationforexp&expid="+expid;        
	$.ajax({
		type: 'post',
		url: 'reports/completionreport/reports-completionreport-byschoolajax.php',
		data: dataparam,
		beforeSend: function(){			
		},
		success:function(data) {
			$('#destinationdiv').show();	
			$('#destinationdiv').html(data);//Used to load the student details in the dropdown
                        
		}
	});
}
/* Destionation function for mission */
function fn_showdestinationformis(misid)
{

        $('#taskdiv').hide();
        $('#resourcediv').hide();
        $('#districtdiv').hide();
        $('#schooldiv').hide();
        $('#viewreportdiv').hide();

	var dataparam = "oper=showdestinationformis&misid="+misid;        
	$.ajax({
		type: 'post',
		url: 'reports/completionreport/reports-completionreport-byschoolajax.php',
		data: dataparam,
		beforeSend: function(){			
		},
		success:function(data) {
			$('#destinationdiv').show();	
			$('#destinationdiv').html(data);//Used to load the student details in the dropdown

		}
	});
}


function fn_movealllistitems(leftlist,rightlist,id,courseid)
{
    var typeid= $("#typeid").val();
    if(id == 0)
    { 
        $("div[id^="+leftlist+"_]").each(function()
        {
                if(!$(this).hasClass('dim')){
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
    
    if(leftlist=="list9" || leftlist=="list10" && rightlist=="list10" || rightlist=="list9"  )
    {

        fn_showtasks(courseid);
    }
    if(leftlist=="list11" || leftlist=="list12" && rightlist=="list12" || rightlist=="list11"  )
    {
        if(typeid==0)
        {              
              fn_showresources(courseid);
        }
        else
        {
            fn_showdistricts($('#misid').val());   
        }
       
    }
    if(leftlist=="list13" || leftlist=="list14" && rightlist=="list14" || rightlist=="list13"  )
    {
      if(typeid==0){  
            fn_showdistricts($('#expid').val()); 
        }
        else{
            fn_showdistricts($('#misid').val());   
        }
    }
    if(leftlist=="list15" || leftlist=="list16" && rightlist=="list16" || rightlist=="list15"  )
    {
         $('#viewreportdiv').show();
    }
     if(leftlist=="list17" || leftlist=="list18" && rightlist=="list17" || rightlist=="list18"  )
    {
         $('#viewreportdiv').show();
    }
    if(leftlist=="list19" || leftlist=="list20" && rightlist=="list20" || rightlist=="list19"  )
    {
         $('#viewreportdiv').show();
    }

}
 


function fn_showtasks(id)
{
        var typeid= $("#typeid").val();   
            
        $('#resourcediv').hide();
         $('#districtdiv').hide();
        $('#schooldiv').hide();
        $('#viewreportdiv').hide();

        var destids = [];
		
        $("div[id^=list10_]").each(function()
        {
                var guid = $(this).attr('id').replace('list10_',''); 
                destids.push(guid);
                
        });
      
	var dataparam = "oper=showtasks&destids="+destids+"&id="+id+'&typeid='+typeid;       
        
	$.ajax({
		type: 'post',
		url: 'reports/completionreport/reports-completionreport-byschoolajax.php',
		data: dataparam,
		beforeSend: function(){			
		},
		success:function(data) {
			$('#taskdiv').show();	
			$('#taskdiv').html(data);//Used to load the student details in the dropdown
                        
		}
	});
}


function  fn_showresources(id)
{ 
        $('#schooldiv').hide();
        $('#viewreportdiv').hide();
        
        var typeid= $("#typeid").val();   
        var taskids = [];
		
        $("div[id^=list12_]").each(function()
        {
                var taskid = $(this).attr('id').replace('list12_',''); 
                taskids.push(taskid);
                
        });

	    
	var dataparam = "oper=showresources&taskids="+taskids+"&id="+id+'&typeid='+typeid;
	$.ajax({
		type: 'post',
		url: 'reports/completionreport/reports-completionreport-byschoolajax.php',
		data: dataparam,
                beforeSend: function(){		
		},
		success:function(data) {
                        $('#resourcediv').show();	
			$('#resourcediv').html(data);//Used to load the student details in the dropdown
		}
	});
}

function fn_showdistricts(id)
{ 
    var typeid= $("#typeid").val(); 
    var dataparam = "oper=showdistricts&id="+id+'&typeid='+typeid;    
    $.ajax({
            type: 'post',
            url: 'reports/completionreport/reports-completionreport-byschoolajax.php',
            data: dataparam,
            beforeSend: function(){
                   $('#districtdiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
            },
            success:function(data) {
                    $('#districtdiv').show();   
                    $('#districtdiv').html(data);//Used to load the student details in the dropdown
            }
    });
}


function fn_showschools(distid,id,type)
{ 
    var typeid= $("#typeid").val(); 
    var dataparam = "oper=showschools&id="+id+"&distid="+distid+"&type="+type+'&typeid='+typeid;    
    $.ajax({
            type: 'post',
            url: 'reports/completionreport/reports-completionreport-byschoolajax.php',
            data: dataparam,
            beforeSend: function(){                   
            },
            success:function(data) {
                    $('#schooldiv').show();   
                    $('#schooldiv').html(data);//Used to load the student details in the dropdown
            }
    });
}


function fn_savecompletionrpt()
{	
    var typeidexpormis= $("#typeid").val(); 
   
    var expid=$('#expid').val();
    var misid=$('#misid').val();
    var usrid=$('#loginid').val();
    var bystuflag = '5';
    var distid = $('#distid').val();
    var destids = [];
    var tskids = [];
    var rsorceids = [];
    var schlids = [];
    var typeid = $('#type').val();
    
    $("div[id^=list10_]").each(function()
    {
       var guid = $(this).attr('id').replace('list10_','');
       destids.push(guid);
    });
    
    $("div[id^=list12_]").each(function()
    {
       var guid = $(this).attr('id').replace('list12_','');
       tskids.push(guid);
    });
    
    $("div[id^=list14_]").each(function()
    {
       var guid = $(this).attr('id').replace('list14_','');
       rsorceids.push(guid);
    });
    
    $("div[id^=list16_]").each(function()
    {
       var guid = $(this).attr('id').replace('list16_','');
       schlids.push(guid);
    });
    if(typeid == 1){
    $("div[id^=list18_]").each(function()
    {
       var guid = $(this).attr('id').replace('list18_','');
       schlids.push(guid);
    });
    }
    if(typeid == 2){
    $("div[id^=list20_]").each(function()
    {
       var guid = $(this).attr('id').replace('list20_','');
       schlids.push(guid);
    });
    }
    if(typeidexpormis=='0'){
         var val = bystuflag+"-"+typeidexpormis+"~"+distid+"~"+schlids+"~"+expid+"~"+destids+"~"+tskids+"~"+rsorceids+"~"+usrid+"~"+typeid;
    }
    else
    {
        var val = bystuflag+"-"+typeidexpormis+"~"+distid+"~"+schlids+"~"+misid+"~"+destids+"~"+tskids+"~"+tskids+"~"+usrid+"~"+typeid;
    }
       
        
         
        setTimeout('removesections("#reports-completionreport-byschool");',500);
        var oper = "completionreporttest";
        var hidfilename = $("#hidfilename").val()+new Date().getTime();
        ajaxloadingalert('Loading, please wait.');   
        setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&oper='+oper+'&filename='+hidfilename+'");',500);
    }

/*******fn_load_home_purcahse()
		Function is used to load the home purchase
******/
function fn_load_home_purchase(typeid)
{	
	var dataparam = "oper=showhomepurchase&typeid="+typeid;
        $.ajax({
            type: 'post',
            url: 'reports/completionreport/reports-completionreport-byschoolajax.php',
            data: dataparam,
            beforeSend: function(){                   
            },
            success:function(data) {
                    $('#schooldiv').show();   
                    $('#schooldiv').html(data);//Used to load the student details in the dropdown
}
    });
}
