
function fn_expend()
{
     $('#misondiv').hide();
     $('#destinationdiv').hide();
     $('#taskdiv').hide();
     $('#resourcediv').hide();
     $('#classdiv').hide();
     $('#schooldiv').hide(); 
     $('#viewreportdiv').hide();
        
    var dataparam = "oper=showexpend";    
    $.ajax({
        type: 'post',
        url: 'reports/completionreport/reports-completionreport-adminajax.php',
        data: dataparam,
        beforeSend: function(){                
        },
        success:function(data) {
                $('#expenddiv').show();	
                $('#expenddiv').html(data);//Used to load the student details in the dropdown

        }
    });
}

function fn_mison()
{
    $('#expenddiv').hide();
    $('#destinationdiv').hide();	
    $('#taskdiv').hide();
    $('#resourcediv').hide();
    $('#classdiv').hide();
    $('#schooldiv').hide(); 
    $('#viewreportdiv').hide();

       var dataparam = "oper=showmison";       
       $.ajax({
            type: 'post',
            url: 'reports/completionreport/reports-completionreport-adminajax.php',
            data: dataparam,
            beforeSend: function(){                    
            },
            success:function(data) {
                    $('#misondiv').show();	
                    $('#misondiv').html(data);//Used to load the student details in the dropdown

            }
       });
    
}

function fn_showdestinationforexpend(expid)
{
    $('#taskdiv').hide();
    $('#resourcediv').hide();
    $('#classdiv').hide();
    $('#schooldiv').hide(); 
    $('#viewreportdiv').hide();

    var dataparam = "oper=showdestinationforexpend&expid="+expid;    
    $.ajax({
        type: 'post',
        url: 'reports/completionreport/reports-completionreport-adminajax.php',
        data: dataparam,
        beforeSend: function(){                
        },
        success:function(data) {
                $('#destinationdiv').show();	
                $('#destinationdiv').html(data);//Used to load the student details in the dropdown

        }
    });
}

function fn_showdestinationformison(misid)
{
    $('#taskdiv').hide();
    $('#resourcediv').hide();
    $('#classdiv').hide();
    $('#schooldiv').hide();
    $('#viewreportdiv').hide();

    var dataparam = "oper=showdestinationformison&misid="+misid;    
    $.ajax({
        type: 'post',
        url: 'reports/completionreport/reports-completionreport-adminajax.php',
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
        var profid=$('#profileid').val();
      
        if(typeid == 0)
        {
            fn_showresources(courseid); 
        }
        else
        {
            if(profid=="6")
            {
                fn_showschools($('#expid').val()); 
            }
            else
            {
                fn_showclass($('#expid').val()); 
            }
            
        }
    }
    if(leftlist=="list13" || leftlist=="list14" && rightlist=="list14" || rightlist=="list13"  )
    {
        var profid=$('#profileid').val();
        if(profid=="6"){
            
          if(typeid == 0){
            
            fn_showschools($('#expid').val());
          } 
          else{
             fn_showschools($('#expid').val()); 
          }
          
        }
        else{
            
            if(typeid == 0){
                 fn_showclass($('#expid').val()); 
            } 
           else{
               fn_showclass($('#expid').val()); 
           }
           
        }
       
    }
    if(leftlist=="list15" || leftlist=="list16" && rightlist=="list16" || rightlist=="list15"  )
    {
         $('#viewreportdiv').show();
    }
}

 

function fn_showtasks(id)
{
    var typeid= $("#typeid").val();     
    
    $('#resourcediv').hide();
    $('#classdiv').hide();
    $('#schooldiv').hide(); 
    $('#viewreportdiv').hide();

    var destids = [];

    $("div[id^=list10_]").each(function()
    {
            var guid = $(this).attr('id').replace('list10_',''); 
            destids.push(guid);

    });

    var dataparam = "oper=showtasks&destids="+destids+"&id="+id+"&typeid="+typeid;
    
    $.ajax({
            type: 'post',
            url: 'reports/completionreport/reports-completionreport-adminajax.php',
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
    
    var typeid= $("#typeid").val(); 
    
    $('#classdiv').hide();
    $('#schooldiv').hide(); 
    $('#viewreportdiv').hide();
        
    var taskids = [];

    $("div[id^=list12_]").each(function()
    {
            var taskid = $(this).attr('id').replace('list12_',''); 
            taskids.push(taskid);

    });

    var dataparam = "oper=showresources&taskids="+taskids+"&id="+id+"&typeid="+typeid;   
    $.ajax({
        type: 'post',
        url: 'reports/completionreport/reports-completionreport-adminajax.php',
        data: dataparam,
        beforeSend: function(){                
        },
        success:function(data) {
                $('#resourcediv').show();	
                $('#resourcediv').html(data);//Used to load the student details in the dropdown
        }
    });
}

function fn_showclass(id)
{
    var typeid= $("#typeid").val(); 
    $('#viewreportdiv').hide();

    var dataparam = "oper=showclass&id="+id+"&typeid="+typeid;    
    $.ajax({
           type: 'post',
           url: 'reports/completionreport/reports-completionreport-adminajax.php',
           data: dataparam,
           beforeSend: function(){
                   $('#classdiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
           },
           success:function(data) {               
                   $('#classdiv').show();		
                   $('#classdiv').html(data);//Used to load the student details in the dropdown
           }
    });
}


function fn_savecompletionrpt()
{	
    var typeid= $("#typeid").val(); 
    var profid=$('#profileid').val();
    
    if(profid=="6"){
        var bystuflag = '4';

        var schlid = [];
        $("div[id^=list16_]").each(function()
        {
            var guid = $(this).attr('id').replace('list16_','');
            schlid.push(guid);
        });
    }
    else{
        var bystuflag = '3';

        var clsid = [];
        $("div[id^=list16_]").each(function()
        {
           var guid = $(this).attr('name').replace('list16_','');
           clsid.push(guid);
        });
    }

    var expid=$('#expid').val();
    var usrid=$('#loginid').val();
    var distid=$('#distid').val();
    var destid = [];
    var tskid = [];
    var rsorceid = [];
      
    $("div[id^=list10_]").each(function()
    {
       var guid = $(this).attr('id').replace('list10_','');
       destid.push(guid);
    });
    
    $("div[id^=list12_]").each(function()
    {
       var guid = $(this).attr('id').replace('list12_','');
       tskid.push(guid);
    });
    
    $("div[id^=list14_]").each(function()
    {
       var guid = $(this).attr('id').replace('list14_','');
       rsorceid.push(guid);
    });
    
    if(profid=="6"){
        if(schlid == ''){
            showloadingalert("please select any one School.");	 
            setTimeout('closeloadingalert()',2000);
            return false;
        }
        else
        {
            if(typeid==0)
            {
                var val = bystuflag+"-"+typeid+"~"+schlid+"~"+expid+"~"+destid+"~"+tskid+"~"+rsorceid+"~"+usrid+"~"+distid;
            }
            else
            {
               var val = bystuflag+"-"+typeid+"~"+schlid+"~"+expid+"~"+destid+"~"+tskid+"~"+tskid+"~"+usrid+"~"+distid; 
            }
        }
    }
    else{
        if(clsid == ''){
            showloadingalert("please select any one class.");	 
            setTimeout('closeloadingalert()',2000);
            return false;
        }
        else
        {
            if(typeid==0)
            {
                var val = bystuflag+"-"+typeid+"~"+clsid+"~"+expid+"~"+destid+"~"+tskid+"~"+rsorceid+"~"+usrid;  
            }
            else
            {
                var val = bystuflag+"-"+typeid+"~"+clsid+"~"+expid+"~"+destid+"~"+tskid+"~"+tskid+"~"+usrid;
            }
        }
    }
    
    setTimeout('removesections("#reports-completionreport-admin");',500);
    var oper = "completionreporttest";
    var hidfilename = $("#hidfilename").val()+new Date().getTime();
    ajaxloadingalert('Loading, please wait.');   
    setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&oper='+oper+'&filename='+hidfilename+'");',500);
    
}


function fn_showschools(id)
{ 
   
    var typeid= $("#typeid").val(); 
    var dataparam = "oper=showschools&id="+id+"&typeid="+typeid;    
    $.ajax({
            type: 'post',
            url: 'reports/completionreport/reports-completionreport-adminajax.php',
            data: dataparam,
            beforeSend: function(){                   
            },
            success:function(data) {
                    $('#schooldiv').show();   
                    $('#schooldiv').html(data);//Used to load the student details in the dropdown
            }
    });
}
 