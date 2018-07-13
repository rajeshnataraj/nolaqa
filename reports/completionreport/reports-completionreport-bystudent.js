

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
     $('#studentdiv').hide();
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
        
        
	var dataparam = "oper=showmissionschedulebystudent&clsid="+clsid;
	$.ajax({
		type: 'post',
		url: 'reports/completionreport/reports-completionreport-bystudentajax.php',
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

function fn_showclsschedpitsco(clsid, typeid)
{
    $('#studentdiv').hide();
    $('#viewreportdiv').hide();
    if(typeid == 0){
        var missionid= $("#expeditionid").val();
    }
    else {
        var missionid= $("#missionid").val();
    }
    
        
        
	var dataparam = "oper=showmisschpitsco&clsid="+clsid+"&misid="+missionid+"&typeid="+typeid;    
	$.ajax({
		type: 'post',
		url: 'reports/completionreport/reports-completionreport-bystudentajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#classscheddivforpitsco').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {
			$('#classscheddivforpitsco').show();	
			$('#classscheddivforpitsco').html(data);//Used to load the student details in the dropdown
		}
	});
}


function fn_showmission(schid,type,clsid)
{ 
   
    $('#expeditiondiv').hide();
    $('#destinationdiv').hide();
    $('#taskdiv').hide();
    $('#resourcediv').hide();
    $('#studentlist').hide();
    $('#viewreportdiv').hide();
        
    var dataparam = "oper=showmission&schid="+schid+"&type="+type+"&clsid="+clsid; 
    $.ajax({
            type: 'post',
            url: 'reports/completionreport/reports-completionreport-bystudentajax.php',
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


function fn_showexpedition(schid,type,clsid)
{
    $('#viewreportdiv').hide();
    $('#destinationdiv').hide();
    $('#taskdiv').hide();
    $('#resourcediv').hide();
    $('#studentdiv').hide();

    var dataparam = "oper=showexpedition&schid="+schid+"&type="+type+"&clsid="+clsid;
    $.ajax({
           type: 'post',
           url: 'reports/completionreport/reports-completionreport-bystudentajax.php',
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
        
        
	var dataparam = "oper=showexpeditionschedulebystudent&clsid="+clsid;    
	$.ajax({
		type: 'post',
		url: 'reports/completionreport/reports-completionreport-bystudentajax.php',
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

function fn_showdestinationexp(expid,schid)
{   
    $('#viewreportdiv').hide();
    $('#taskdiv').hide();
    $('#resourcediv').hide();
    $('#studentdiv').hide();

    var dataparam = "oper=showdestinationexp&expid="+expid+"&schid="+schid;  
    $.ajax({
           type: 'post',
           url: 'reports/completionreport/reports-completionreport-bystudentajax.php',
           data: dataparam,
           beforeSend: function(){
                   $('#destinationdiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
           },
           success:function(data) {
                   $('#destinationdiv').show();	
                   $('#destinationdiv').html(data);//Used to load the student details in the dropdown

           }
    });
}

function fn_showdestinationmison(misid,schid)
{
    $('#viewreportdiv').hide();
    $('#taskdiv').hide();
    $('#resourcediv').hide();
    $('#studentdiv').hide();
      var typeid= $("#typeid").val();

    var dataparam = "oper=showdestinationmison&misid="+misid+"&schid="+schid+"&typeid="+typeid; 
    $.ajax({
           type: 'post',
           url: 'reports/completionreport/reports-completionreport-bystudentajax.php',
           data: dataparam,
           beforeSend: function(){
                   $('#destinationdiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
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
        
    if(leftlist=="list7" || leftlist=="list8" && rightlist=="list8" || rightlist=="list7"  )
    {
       $('#viewreportdiv').show();
    }
    
    if(leftlist=="list9" || leftlist=="list10" && rightlist=="list10" || rightlist=="list9"  )
    {
       $('#taskdiv').show();
       fn_showtasks($('#scheid').val());
    }
    if(leftlist=="list11" || leftlist=="list12" && rightlist=="list12" || rightlist=="list11"  )
    {      
       var profid=$('#profileid').val();   
       $('#resourcediv').show();
        if(typeid==0){   
            fn_showresources($('#scheid').val());
        }
        else
        {
            if(profid=="2")
            {

                $('#districtdiv').show();
                fn_showdistricts($('#missionid').val());

            } 
            else{              
                $('#studentdiv').show();
                fn_showstudent($('#scheid').val());
            }  
            
        }
       
    }
         
    if(leftlist=="list13" || leftlist=="list14" && rightlist=="list14" || rightlist=="list13"  )
    {
        var profid=$('#profileid').val();
        if(profid=="2")
        {
                
              if(typeid==0){             
                $('#districtdiv').show();
                fn_showdistricts($('#expeditionid').val());
              }
              else{
                 $('#districtdiv').show();
                fn_showdistricts($('#missionid').val());
             }
            
        } 
        else{
            $('#studentdiv').show();
            fn_showstudent($('#scheid').val());
        }  
       
    }
}

function fn_showtasks(scheid)
{
    $('#resourcediv').hide(); 
    $('#districtdiv').hide(); 
    $('#schooldiv').hide(); 
    $('#classdivforpitsco').hide(); 
    $('#studentdiv').hide(); 

    var destids = [];
    var typeid= $("#typeid").val();

    $("div[id^=list10_]").each(function()
    {
            var guid = $(this).attr('id').replace('list10_',''); 
            destids.push(guid);

    });

    var dataparam = "oper=showtasks&destids="+destids+"&scheid="+scheid+'&typeid='+typeid;
    $.ajax({
        type: 'post',
        url: 'reports/completionreport/reports-completionreport-bystudentajax.php',
        data: dataparam,
        success:function(data) {

                $('#taskdiv').html(data);//Used to load the student details in the dropdown

        }
    });
}

function fn_showresources(scheid)
{
    $('#districtdiv').hide(); 
    $('#schoolid').hide(); 
    $('#classid').hide(); 
    $('#studentdiv').hide(); 

    var taskids = [];
    var typeid= $("#typeid").val();

    $("div[id^=list12_]").each(function()
    {
            var taskid = $(this).attr('id').replace('list12_',''); 
            taskids.push(taskid);

    });

    var dataparam = "oper=showresources&taskids="+taskids+"&scheid="+scheid+'&typeid='+typeid;
    $.ajax({
            type: 'post',
            url: 'reports/completionreport/reports-completionreport-bystudentajax.php',
            data: dataparam,
            success:function(data) {            
                    $('#resourcediv').html(data);//Used to load the student details in the dropdown
            }
    });
}

function fn_showstudent(scheid)
{
    var typeid= $("#typeid").val();
    var schtype= $("#schtype").val();
    var dataparam = "oper=showstudent&scheid="+scheid+'&typeid='+typeid+'&schtype='+schtype;  
    $.ajax({
            type: 'post',
            url: 'reports/completionreport/reports-completionreport-bystudentajax.php',
            data: dataparam,
            success:function(data) {            
                $('#studentdiv').show();
                    $('#studentdiv').html(data);//Used to load the student details in the dropdown
            }
    });
}

function fn_showstudentpitsco(scheid,type,clsid)
{
     var typeid= $("#typeid").val();
    var dataparam = "oper=showstudent&scheid="+scheid+'&typeid='+typeid+'&clsid='+clsid+"&schtype="+type;
    $.ajax({
            type: 'post',
            url: 'reports/completionreport/reports-completionreport-bystudentajax.php',
            data: dataparam,
            success:function(data) {         
                $('#studentdiv').show();
                    $('#studentdiv').html(data);//Used to load the student details in the dropdown
            }
    });
}
/*******Pitsco Level Code Start Here*******/

function fn_showexpforpitsco(){
   $('#missiondiv').hide();
   $('#viewreportdiv').hide();
   $('#destinationdiv').hide();
   $('#taskdiv').hide();
   $('#resourcediv').hide();
   $('#studentdiv').hide();
   $('#districtdiv').hide();   
   $('#schooldiv').hide();
   $('#classdivforpitsco').hide(); 
        
    var dataparam = "oper=showexpforpitsco"; 
    $.ajax({
        type: 'post',
        url: 'reports/completionreport/reports-completionreport-bystudentajax.php',
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

function fn_showmisonforpitsco(){
   $('#expeditiondiv').hide();	
   $('#viewreportdiv').hide();
   $('#destinationdiv').hide();
   $('#taskdiv').hide();
   $('#resourcediv').hide();
   $('#studentdiv').hide();
   $('#districtdiv').hide();   
   $('#schooldiv').hide();
   $('#classdivforpitsco').hide();	 
        
    var dataparam = "oper=showmisonforpitsco"; 
    $.ajax({
        type: 'post',
        url: 'reports/completionreport/reports-completionreport-bystudentajax.php',
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


function fn_showdestinationforpitsco(expid)
{ 
    $('#taskdiv').hide();
    $('#resourcediv').hide();
    $('#districtdiv').hide();   
    $('#schooldiv').hide();
    $('#classdivforpitsco').hide();
    $('#viewreportdiv').hide();
  
    var dataparam = "oper=showdestinationforpitsco&expid="+expid;
    $.ajax({
            type: 'post',
            url: 'reports/completionreport/reports-completionreport-bystudentajax.php',
            data: dataparam,
            beforeSend: function(){                   	
            },
            success:function(data) {
                    $('#destinationdiv').show();   
                    $('#destinationdiv').html(data);//Used to load the student details in the dropdown
            }
    });
}

function fn_showmisondestinationforpitsco(misid)
{ 
    $('#taskdiv').hide();
    $('#resourcediv').hide();
    $('#districtdiv').hide();   
    $('#schooldiv').hide();
    $('#viewreportdiv').hide();
    $('#classdivforpitsco').hide(); 
     var typeid= $("#typeid").val();
  
    var dataparam = "oper=showmisondestinationforpitsco&misid="+misid+'&typeid='+typeid; 
    $.ajax({
            type: 'post',
            url: 'reports/completionreport/reports-completionreport-bystudentajax.php',
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
    $('#studentdiv').hide(); 
    $('#classdivforpitsco').hide();
    $('#viewreportdiv').hide();
  
    var typeid= $("#typeid").val();
    
    if(typeid==0){
        var dataparam = "oper=showdistricts&id="+id+'&typeid='+typeid;
    }
    else{
        var dataparam = "oper=showdistricts&id="+id+'&typeid='+typeid;
    }    

 
    $.ajax({
            type: 'post',
            url: 'reports/completionreport/reports-completionreport-bystudentajax.php',
            data: dataparam,
            beforeSend: function(){                  
            },
            success:function(data) {
                    $('#districtdiv').show();   
                    $('#districtdiv').html(data);//Used to load the student details in the dropdown
            }
    });
}


function fn_showschools(distid,expid)
{ 
    $('#classdivforpitsco').hide();
    $('#studentdiv').hide(); 
    $('#classdivforpitsco').hide();
    $('#viewreportdiv').hide();  
    
    var dataparam = "oper=showschools&expid="+expid+"&distid="+distid;  
    $.ajax({
            type: 'post',
            url: 'reports/completionreport/reports-completionreport-bystudentajax.php',
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
 
function fn_showclasses(schoolid,distid,expid)
{
    $('#studentdiv').hide();
    $('#viewreportdiv').hide(); 
    var typeid= $("#typeid").val();

    var dataparam = "oper=showclassforpitsco&schlid="+schoolid+"&distid="+distid+"&expid="+expid+"&typeid="+typeid;
    $.ajax({
            type: 'post',
            url: 'reports/completionreport/reports-completionreport-bystudentajax.php',
            data: dataparam,
            beforeSend: function(){                    
            },
            success:function(data) {
                    $('#classdivforpitsco').show();		
                    $('#classdivforpitsco').html(data);//Used to load the student details in the dropdown

            }
    });
}

/*******Pitsco Level Code End Here*******/
function fn_studentstds()
{	
    var typeid= $("#typeid").val();
    var profid=$('#profileid').val();
    var schtype = $('#schtype').val();      // schedule type from hidden 
    var schedid = $('#schedid').val();      // schedule ID from hidden 
    if(profid=="2")
    {
        var bystuflag = '7';
        var usrid=$('#loginid').val();
        var expid = $('#expeditionid').val();
        var misid = $('#missionid').val();
        var schlid=$('#schoolid').val();
        var distid = $('#distid').val();
        var clsid=$('#classid').val();
        var scheid=$('#scheffdid').val();
    }
    else{
        var clsid=$('#classid').val();
        var scheid=$('#scheid').val();
        var expid=$('#expeditionid').val();
        var misid = $('#missionid').val();
        var usrid=$('#loginid').val();
        var bystuflag = '1';
    }
    
    
    var destid = [];
    var tskid = [];
    var rsorceid = [];
    var studid = [];
    
    
    $("div[id^=list8_]").each(function()
    {
       var guid = $(this).attr('id').replace('list8_','');
       studid.push(guid);
    });
    
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
    
    if(studid == ''){
    showloadingalert("No Students selected to view Report.");	 
    setTimeout('closeloadingalert()',2000);
    return false;
    }
    else
    {
        if(profid=="2")
        {
            if(typeid==0){
                var val = bystuflag+"-"+typeid+"~"+distid+"~"+schlid+"~"+clsid+"~"+scheid+"~"+expid+"~"+destid+"~"+tskid+"~"+rsorceid+"~"+studid+"~"+usrid+"~"+schtype+"~"+schedid;

           }else{
                var val = bystuflag+"-"+typeid+"~"+distid+"~"+schlid+"~"+clsid+"~"+scheid+"~"+misid+"~"+destid+"~"+tskid+"~"+tskid+"~"+studid+"~"+usrid+"~"+schtype+"~"+schedid;
           }          
        }
        else
        {
            if(typeid==0){
                var val = bystuflag+"-"+typeid+"~"+clsid+"~"+scheid+"~"+expid+"~"+destid+"~"+tskid+"~"+rsorceid+"~"+studid+"~"+usrid+"~"+schtype+"~"+schedid;
            }else{
                var val = bystuflag+"-"+typeid+"~"+clsid+"~"+scheid+"~"+misid+"~"+destid+"~"+tskid+"~"+tskid+"~"+studid+"~"+usrid+"~"+schtype+"~"+schedid;
            }           
        }      
        setTimeout('removesections("#reports-completionreport-bystudent");',500);
        var oper = "completionreporttest";
        var hidfilename = $("#hidfilename").val()+new Date().getTime();
        ajaxloadingalert('Loading, please wait.');
        setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&oper='+oper+'&filename='+hidfilename+'");',500);
    }
}