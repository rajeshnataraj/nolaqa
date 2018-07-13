
/*----
    fn_showstudent()
	Function to Load the Student Dropdown
	id -> Classid
----*/

function fn_showexpedition(schid,type,clsid)
{ 
    $('#missiondiv').hide(); 
    $('#destinationdiv').hide();
    $('#taskdiv').hide();
    $('#resourcediv').hide();
	 $('#viewreportdiv').hide();
        
        
	var dataparam = "oper=showexpeditionbyclass&schid="+schid+"&type="+type+"&clsid="+clsid;   
	$.ajax({
		type: 'post',
		url: 'reports/completionreport/reports-completionreport-byclassajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#expeditiondiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {
			$('#expeditiondiv').show();	
			$('#expeditiondiv').html(data);//Used to load the student details in the dropdown
		}
	});
}
function fn_showexpeditionschedule(clsid)
{   
    $('#expeditiondiv').hide();
    $('#missiondiv').hide(); 
    $('#destinationdiv').hide();
    $('#taskdiv').hide();
    $('#resourcediv').hide();
	 $('#viewreportdiv').hide();


	var dataparam = "oper=showexpeditionschedulebyclass&clsid="+clsid;       
	$.ajax({
		type: 'post',
		url: 'reports/completionreport/reports-completionreport-byclassajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#expeditionschedulediv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {
			$('#expeditionschedulediv').show();	
			$('#expeditionschedulediv').html(data);//Used to load the student details in the dropdown
		}
	});
}



function fn_showclass(id)
{
           
    $("#classid").show();
    $('#expeditionschedulediv').hide();
    $('#missionschedulediv').hide();
    $('#expeditiondiv').hide(); 
    $('#missiondiv').hide(); 
    $('#destinationdiv').hide();
    $('#taskdiv').hide();
    $('#resourcediv').hide();
    $('#viewreportdiv').hide();
     
    var dataparam = "oper=showclass&id="+id;    
    $.ajax({
            type: 'post',
            url: 'reports/completionreport/reports-completionreport-byclassajax.php',
            data: dataparam,

            success:function(data) {		
                    $('#clasid').html(data);
            }
    });

}
    
/* function for mission */

function fn_showmissionschedule(clsid)
{
    $('#expeditiondiv').hide();
    $('#missiondiv').hide(); 
    $('#destinationdiv').hide();
    $('#taskdiv').hide();
    $('#resourcediv').hide();
	 $('#viewreportdiv').hide();
        
        
	var dataparam = "oper=showmisschedulebyclass&clsid="+clsid;       
	$.ajax({
		type: 'post',
		url: 'reports/completionreport/reports-completionreport-byclassajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#missionschedulediv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {
			$('#missionschedulediv').show();	
			$('#missionschedulediv').html(data);//Used to load the student details in the dropdown
		}
	});
}

function fn_showmission(schid,type,clsid)
{ 
    $('#expeditiondiv').hide();
    $('#destinationdiv').hide();
    $('#taskdiv').hide();
    $('#resourcediv').hide();
    $('#viewreportdiv').hide();
        
    var dataparam = "oper=showmissionbyclass&schid="+schid+"&type="+type+"&clsid="+clsid;  
    $.ajax({
            type: 'post',
            url: 'reports/completionreport/reports-completionreport-byclassajax.php',
            data: dataparam,
            beforeSend: function(){
                    $('#missiondiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
            },
            success:function(data) {
                    $('#missiondiv').show();	
                    $('#missiondiv').html(data);//Used to load the student details in the dropdown
            }
    });
}
/* function for mission ends */

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
        
    if(leftlist=="list7" || leftlist=="list8" && rightlist=="list8" || rightlist=="list7")
    {
        if(typeid==0){
        fn_showdestination($('#expid').val(),$('#schedid').val());  
        $('#destinationdiv').show();   
                
    }
        else{
            fn_showdestination($('#misid').val(),$('#schedid').val());  
            $('#destinationdiv').show();   
        }
    }
    
    if(leftlist=="list9" || leftlist=="list10" && rightlist=="list10" || rightlist=="list9")
    {
        $('#taskdiv').show();
        fn_showtasks($('#scheid').val());
    
                
    }
    if(leftlist=="list11" || leftlist=="list12" && rightlist=="list12" || rightlist=="list11")
    {
        var profid=$('#profileid').val();
        
         if(typeid==0)
         {
            if(profid=="2")
            {
                $('#resourcediv').show();
                fn_showresources($('#scheid').val());
            }
            else
            {

                $('#resourcediv').show();
                $('#viewreportdiv').show();
                fn_showresources($('#scheid').val());

            }
         }
         else
         {
            if(profid=="2")
            {
                 $('#districtdiv').show();
                fn_showdistricts($('#missionid').val());
            }
            else
            {

                $('#resourcediv').show();
                $('#viewreportdiv').show();                

            }
         }
        
        
    }
    if(leftlist=="list13" || leftlist=="list14" && rightlist=="list14" || rightlist=="list13")
    {
        var profid=$('#profileid').val();
        if(profid=="2")
        {
            if(typeid==0)
            {
                $('#districtdiv').show();
                fn_showdistricts($('#expeditionid').val());
            }   
            else
            {
                $('#districtdiv').show();
                fn_showdistricts($('#missionid').val());
            }
        }   
    }
    if(leftlist=="list15" || leftlist=="list16" && rightlist=="list16" || rightlist=="list15")
    {
        var profid=$('#profileid').val();
        if(profid=="2"){
            $('#viewreportdiv').show();
        }   
    }
}


function fn_showdestination()
{
    $('#taskdiv').hide();
    $('#resourcediv').hide();
    $('#viewreportdiv').hide();
    
    var typeid= $("#typeid").val();
    var expids = [];
    var misids = [];

    $("div[id^=list8_]").each(function()
    {
        if( typeid==0){
            var guid = $(this).attr('id').replace('list8_',''); 
            expids.push(guid);
        }
        else{
            var guid = $(this).attr('id').replace('list8_',''); 
            misids.push(guid);
        }
    });

   
    var dataparam = "oper=showdestination&expids="+expids+'&misids='+misids+'&typeid='+typeid; 
    $.ajax({
            type: 'post',
            url: 'reports/completionreport/reports-completionreport-byclassajax.php',
            data: dataparam,
            beforeSend: function(){                   
            },
            success:function(data) {

                    $('#destinationdiv').html(data);//Used to load the student details in the dropdown
            }
    });
}

function fn_showtasks()
{
    $('#resourcediv').hide();
    $('#districtdiv').hide(); 
    $('#schooldiv').hide();  
    $('#classdivforpitsco').hide();
    $('#viewreportdiv').hide();
    
    var typeid= $("#typeid").val();
    var destids = [];
		
    $("div[id^=list10_]").each(function()
    {
            var guid = $(this).attr('id').replace('list10_',''); 
            destids.push(guid);

    });

    var dataparam = "oper=showtasks&destids="+destids+'&typeid='+typeid;
    $.ajax({
            type: 'post',
            url: 'reports/completionreport/reports-completionreport-byclassajax.php',
            data: dataparam,
            beforeSend: function(){                    
            },
            success:function(data) {
                    $('#taskdiv').html(data);//Used to load the student details in the dropdown

            }
    });
}

function fn_showresources()
{
    $('#districtdiv').hide(); 
    $('#schooldiv').hide();  
    $('#classdivforpitsco').hide();
    
       
    var typeid= $("#typeid").val();
    var taskids = [];

    $("div[id^=list12_]").each(function()
    {
            var taskid = $(this).attr('id').replace('list12_',''); 
            taskids.push(taskid);

    });

    var dataparam = "oper=showresources&taskids="+taskids+'&typeid='+typeid;
    $.ajax({
            type: 'post',
            url: 'reports/completionreport/reports-completionreport-byclassajax.php',
            data: dataparam,
            beforeSend: function(){               
            },
            success:function(data) {
                $('#resourcediv').html(data);//Used to load the student details in the dropdown
            }
    });
}

function fn_byclassrpt()
{	
    var profid=$('#profileid').val();
    var schtype = $('#schtype').val();   // schedule type from hidden 
    var schedid = $('#schedid').val();      // schedule ID from hidden 
    if(profid=="2")
    {
        var byclassflag = '6';
        
        var usrid=$('#loginid').val();
        var expid = $('#expeditionid').val();
       
        var schlid=$('#schoolid').val();
        var distid = $('#distid').val();
        
        var destids = [];
        var taskids = [];
        var rsorceids = [];
        var clsids= [];

         var typeid= $("#typeid").val();
         var misid = $('#missionid').val();
          
        $("div[id^=list8_]").each(function()
        {
            if(typeid==0){
           var guid = $(this).attr('id').replace('list8_','');
           expid.push(guid);
            }
            else{
                 var guid = $(this).attr('id').replace('list8_','');
                 misid.push(guid);
             }  
        });

        $("div[id^=list10_]").each(function()
        {
           var guid = $(this).attr('id').replace('list10_','');
           destids.push(guid);
        });

        $("div[id^=list12_]").each(function()
        {
           var guid = $(this).attr('id').replace('list12_','');
           taskids.push(guid);
        });

        $("div[id^=list14_]").each(function()
        {
           var guid = $(this).attr('id').replace('list14_','');
           rsorceids.push(guid);
        });
    
        $("div[id^=list16_]").each(function()
        {
           var guid = $(this).attr('name').replace('list16_','');
           clsids.push(guid);
        });
    
    
        if(clsid == ''){
            showloadingalert("please select any one class.");	 
            setTimeout('closeloadingalert()',2000);
            return false;
        }
        if(typeid==0){
            var val = byclassflag+"-"+typeid+"~"+distid+"~"+schlid+"~"+expid+"~"+destids+"~"+taskids+"~"+rsorceids+"~"+clsids+"~"+usrid+"~"+misid+"~"+schtype+"~"+schedid;
        }
        else{
            var val = byclassflag+"-"+typeid+"~"+distid+"~"+schlid+"~"+misid+"~"+destids+"~"+taskids+"~"+taskids+"~"+clsids+"~"+usrid+"~"+misid+"~"+schtype+"~"+schedid;
        }

         
    }
    else
    {
        var clsid=$('#classid').val();
        var byclassflag = '2';
        var usrid=$('#loginid').val();
        var expids = [];
        var destids = [];
        var taskids = [];
        var rsorceid = [];

        var typeid= $("#typeid").val();

        $("div[id^=list8_]").each(function()
        {
           var guid = $(this).attr('id').replace('list8_','');
           expids.push(guid);
        });

        $("div[id^=list10_]").each(function()
        {
           var guid = $(this).attr('id').replace('list10_','');
           destids.push(guid);
        });

        $("div[id^=list12_]").each(function()
        {
           var guid = $(this).attr('id').replace('list12_','');
           taskids.push(guid);
        });

        $("div[id^=list14_]").each(function()
        {
           var guid = $(this).attr('id').replace('list14_','');
           rsorceid.push(guid);
        });
        if(typeid==0)
        {
            var val = byclassflag+"-"+typeid+"~"+clsid+"~"+expids+"~"+destids+"~"+taskids+"~"+rsorceid+"~"+usrid+"~"+schtype+"~"+schedid;
        }
        else
        {
            var val = byclassflag+"-"+typeid+"~"+clsid+"~"+expids+"~"+destids+"~"+taskids+"~"+taskids+"~"+usrid+"~"+schtype+"~"+schedid;
        }
        
    }
    
    setTimeout('removesections("#reports-completionreport-byclass");',500);
    var oper = "completionreporttest";
    var hidfilename = $("#hidfilename").val()+new Date().getTime();
    ajaxloadingalert('Loading, please wait.');
    setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&oper='+oper+'&filename='+hidfilename+'");',500);
}




/*******Pitsco Level Code Start Here*******/

function fn_expendpitscoadmin()
{   
    $('#missiondiv').hide();	
    $('#viewreportdiv').hide();
    $('#destinationdiv').hide();
    $('#taskdiv').hide(); 
    $('#districtdiv').hide();    
    $('#resourcediv').hide(); 
    $('#schooldiv').hide();  
    $('#classdivforpitsco').hide();
 
    var dataparam = "oper=showexpforpitsco";
    $.ajax({
            type: 'post',
            url: 'reports/completionreport/reports-completionreport-byclassajax.php',
            data: dataparam,
            beforeSend: function(){
                    $('#expeditiondiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
            },
            success:function(data) {
                    $('#expeditiondiv').show();	
                    $('#expeditiondiv').html(data);//Used to load the student details in the dropdown
            }
    });
   
}

/* function for mission */

 function fn_missionpitscoadmin()
 {    
    $('#expeditiondiv').hide();
    $('#viewreportdiv').hide();
    $('#destinationdiv').hide();
    $('#taskdiv').hide(); 
    $('#districtdiv').hide();   
    $('#resourcediv').hide();
    $('#schooldiv').hide();
    $('#classdivforpitsco').hide(); 

     var dataparam = "oper=showmisonforpitsco";   
     $.ajax({
             type: 'post',
             url: 'reports/completionreport/reports-completionreport-byclassajax.php',
             data: dataparam,
             beforeSend: function(){
                     $('#missiondiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
             },
             success:function(data) {
                     $('#missiondiv').show();	
                     $('#missiondiv').html(data);//Used to load the student details in the dropdown
             }
     });
 }

/* function for mission ENDS */

function fn_showdestinationforpitsco(id)
{ 
    
    $('#taskdiv').hide();
    $('#resourcediv').hide();
    $('#districtdiv').hide();   
    $('#schooldiv').hide();
    $('#viewreportdiv').hide();
    $('#classdivforpitsco').hide();  

     var typeid= $("#typeid").val();
     

    var dataparam = "oper=showdestinationforpitsco&id="+id+'&typeid='+typeid;
 
    $.ajax({
        type: 'post',
        url: 'reports/completionreport/reports-completionreport-byclassajax.php',
        data: dataparam,
        beforeSend: function(){                
        },
        success:function(data) {
                $('#destinationdiv').show();   
                $('#destinationdiv').html(data);//Used to load the student details in the dropdown
        }
    });
}

function fn_showdistricts(id)
{ 
    $('#schooldiv').hide();  
    $('#classdivforpitsco').hide();

    var typeid= $("#typeid").val();
    var dataparam = "oper=showdistricts&id="+id+'&typeid='+typeid;  
    $.ajax({
        type: 'post',
        url: 'reports/completionreport/reports-completionreport-byclassajax.php',
        data: dataparam,
        beforeSend: function(){             
        },
        success:function(data) {
                $('#districtdiv').show();   
                $('#districtdiv').html(data);//Used to load the student details in the dropdown
        }
    });
}


function fn_showschools(distid,id)
{ 
    $('#classdivforpitsco').hide();

    var typeid= $("#typeid").val();

    var dataparam = "oper=showschools&id="+id+"&distid="+distid; 
    $.ajax({
        type: 'post',
        url: 'reports/completionreport/reports-completionreport-byclassajax.php',
        data: dataparam,
        beforeSend: function(){
                 $('#schooldiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
        },
        success:function(data) {
                $('#schooldiv').show();   
                $('#schooldiv').html(data);//Used to load the student details in the dropdown
        }
    });
}

function fn_showclasses(schoolid,distid,id)
{
    var typeid= $("#typeid").val();

    $('#viewreportdiv').hide();

    var dataparam = "oper=showclasses&schlid="+schoolid+"&distid="+distid+"&id="+id+'&typeid='+typeid; 
    $.ajax({
        type: 'post',
        url: 'reports/completionreport/reports-completionreport-byclassajax.php',
        data: dataparam,
        beforeSend: function(){                	
        },
        success:function(data) {
                $('#classdivforpitsco').show();		
                $('#classdivforpitsco').html(data);//Used to load the student details in the dropdown

        }
    });
}

/*******Pitsco Level Code End Here Here*******/

 