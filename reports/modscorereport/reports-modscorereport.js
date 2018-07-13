/*----
    fn_movealllistitemsproducts()
	Function to move all products from lest to right and right to left
----*/
function fn_movealllistitems(leftlist,rightlist,id,courseid)
{
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
}

/*----
    fn_validateproducts()
	Function to validate the products details
----*/
function fn_validatestudents()
{
 var selectassess=$('#hidselectedstudentids').val(); 		
}

/*----
    fn_showstudent()
	Function to Load the Student Dropdown
	id -> Classid
----*/
function fn_showschool(districtid)
{

   
	$("#reports-pdfviewer").hide("fade").remove();
	$('#viewreportdiv').hide();
        $('#schooldiv').hide();	
        $('#classdiv').hide();
        $('#assignmentdiv').hide();
 	$('#loadstudentidlist').hide();
	$('#modorrotatediv').hide();		
			
	var dataparam = "oper=showschool&districtid="+districtid;
	
	$.ajax({
		type: 'post',
		url: 'reports/modscorereport/reports-modscorereport-modscorereportajax.php',
		data: dataparam,
		beforeSend: function(){
			$('#schooldiv').html('<img src="img/loader.gif" width="200"  border="0" />'); 	
		},
		success:function(data) {

			$('#schooldiv').show();		
			$('#schooldiv').html(data);//Used to load the school names in the dropdown
		}
	});
}


function fn_showclass(districtid,schoolid)
{
	$("#reports-pdfviewer").hide("fade").remove();
	$('#viewreportdiv').hide();
       $('#classdiv').hide();
        $('#assignmentdiv').hide();
 	$('#loadstudentidlist').hide();
        $('#modorrotatediv').hide();

	var dataparam = "oper=showclass&districtid="+districtid+"&schoolid="+schoolid;
	$.ajax({
		type: 'post',
		url: 'reports/modscorereport/reports-modscorereport-modscorereportajax.php',
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

/* Function check */

    function fn_check(id){
    
    $('#viewreportdiv').hide();
    $('#assignmentdiv').hide();
    $('#loadstudentidlist').hide();

    var classid=$("#hidclassid").val();
    var stdate = $('#startdate1').val();
    var enddate = $('#enddate1').val();  

    if(id==1){
        var dataparam = "oper=showassignments&classid="+classid+"&stdate="+stdate+"&enddate="+enddate;
    }
    else{
        var dataparam = "oper=showassignmentsrotate&classid="+classid+"&stdate="+stdate+"&enddate="+enddate+"&id="+id;
    }

    $.ajax({
            type: 'post',
            url: 'reports/modscorereport/reports-modscorereport-modscorereportajax.php',
            data: dataparam,
            success:function(data) {
      
                $('#assignmentdiv').show();
                $('#assignmentdiv').html(data);//Used to load the student details in the dropdown
            }
    });

}

/* Modulelist For Rotation */

    function fn_showmodulelist(schid,stype){

        $('#viewreportdiv').hide();
        $('#modschedulediv').show();

        var classid=$("#hidclassid").val();

        var dataparam = "oper=showmodule&schid="+schid+"&stype="+stype+"&classid="+classid;

         $.ajax({
                type: 'post',
                url: 'reports/modscorereport/reports-modscorereport-modscorereportajax.php',
                data: dataparam,
                success:function(data) {
  
                    $('#modschedulediv').show();
                    $('#modschedulediv').html(data);//Used to load the student details in the dropdown
                    fn_showstudentlist2(classid,schid,stype);
                }
        });
    }

/* Student display List */

function fn_showstudentlist(id,assignmentid,type)
{

    $("#reports-pdfviewer").hide("fade").remove();
    $('#viewreportdiv').hide();
    $('#loadstudentidlist').hide();

var dataparam = "oper=showstudent&classid="+id+"&assignmentid="+assignmentid+"&type="+type;

$.ajax({
		type: 'post',
		url: 'reports/modscorereport/reports-modscorereport-modscorereportajax.php',
		data: dataparam,		
		beforeSend: function(){
			showloadingalert("Loading, please wait.");	
		},
		success:function(ajaxdata) {			
			$("#loadstudentidlist").show();
			$('#viewreportdiv').show();
                        $('#stunameid').show();
			$("#loadstudentidlist").html(ajaxdata);
			closeloadingalert();				
		}
	});

}
 function fn_showstudentlist2(id,assignmentid,type)
 {

        $("#reports-pdfviewer").hide("fade").remove();
        $('#viewreportdiv').hide();


        var dataparam = "oper=showstudent2&classid="+id+"&assignmentid="+assignmentid+"&type="+type;

        $.ajax({
                        type: 'post',
                        url: 'reports/modscorereport/reports-modscorereport-modscorereportajax.php',
                        data: dataparam,		
                        beforeSend: function(){
                                showloadingalert("Loading, please wait.");	
                        },
                        success:function(ajaxdata) {                               
                                $("#loadstudentidlist").show();
                                $('#viewreportdiv').show();
                                $('#stunameid').show();
                                $("#loadstudentidlist").html(ajaxdata);
                                closeloadingalert();	
                               
                        }
                });

    }

function fn_modscorereport()
{

	$('#hidselectedstudentids').val(''); 
	var studentid = [];
        var moduleid = [];

	$("div[id^=list10_]").each(function()
	{
	var studentlist = $(this).attr('id').replace('list10_','');
	studentid.push(studentlist);
	});
        
        var radioval= $('input[type="radio"]:checked').val();
        
        
        
        if(radioval==2)
        {
            $("div[id^=list12_]").each(function()
            {
                var modulelist = $(this).attr('id').replace('list12_','');
                moduleid.push(modulelist); 
           });
           
            if(moduleid=='')
            {
            showloadingalert("please select any Module ID.");
            setTimeout('closeloadingalert()',2000);
            return false;
            }
        }
        
     
	if(studentid=='')
	{
	showloadingalert("please select any Student ID.");
	setTimeout('closeloadingalert()',2000);
	return false;
	}
        
        
        var stuname='0';
        var unicid='0'

        if($("#stuname").is(':checked'))
        {
            stuname='1';
        }
        
        if($("#stuid").is(':checked'))
        {
            unicid='1';
        }
        
        if(stuname=='0' && unicid=='0')
        {
            showloadingalert("please select any one check box.");
            setTimeout('closeloadingalert()',2000);
            return false;
        }

	$('#hidselectedstudentids').val(studentid);
	var studentid =  $('#hidselectedstudentids').val();

	ids= $('#moduleid').val().split('~');
	var val = $('#districtid').val()+","+$('#schoolid').val()+","+$('#hidclassid').val()+","+ids[0]+","+ids[1]+","+ids[2]+","+stuname+","+unicid;
        
        
        
        

	
        
        if(radioval==1)
        {
            setTimeout('removesections("#reports-modscorereport");',500);
            var oper = "modscorereport";
            var hidfilename = $("#hidfilename").val()+new Date().getTime();
            ajaxloadingalert('Loading, please wait.');
            setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&studentlist='+studentid+'&oper='+oper+'&filename='+hidfilename+'");',500);
        }
        else
        {
            setTimeout('removesections("#reports-modscorereport");',500);
            var oper = "modscorereportschedule";
            var hidfilename = $("#hidfilename").val()+new Date().getTime();
            ajaxloadingalert('Loading, please wait.');
            setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&studentlist='+studentid+'&assignmentlist='+moduleid+'&oper='+oper+'&filename='+hidfilename+'");',500);
        }


   
   
}
function fn_exportmodscorereport() {
	$('#hidselectedstudentids').val(''); 
	var studentid = [];
        var moduleid = [];
        
        var radioval= $('input[type="radio"]:checked').val();
        
        if(radioval==2)
        {
            
           $("div[id^=list12_]").each(function()
            {
                var modulelist = $(this).attr('id').replace('list12_','');
                moduleid.push(modulelist); 
           });
           
            if(moduleid=='')
            {
            showloadingalert("please select any Module ID.");
            setTimeout('closeloadingalert()',2000);
            return false;
            }
        }

	$("div[id^=list10_]").each(function()
	{
	var studentlist = $(this).attr('id').replace('list10_','');
	studentid.push(studentlist);
	});

	if(studentid=='')
	{
	showloadingalert("please select any Student ID.");
	setTimeout('closeloadingalert()',2000);
	return false;
	}
        
        var stuname='0';
        var unicid='0'

        if($("#stuname").is(':checked'))
        {
            stuname='1';
        }
        
        if($("#stuid").is(':checked'))
        {
            unicid='1';
        }
        
        if(stuname=='0' && unicid=='0')
        {
            showloadingalert("please select any one check box.");
            setTimeout('closeloadingalert()',2000);
            return false;
        }

	$('#hidselectedstudentids').val(studentid);
	var studentid =  $('#hidselectedstudentids').val();

	ids= $('#moduleid').val().split('~');
	var val = $('#districtid').val()+","+$('#schoolid').val()+","+$('#hidclassid').val()+","+ids[0]+","+ids[1]+","+ids[2]+","+stuname+","+unicid;
       
        if(radioval==1)
        {
            window.location='reports/modscorereport/reports-modscorereport-export.php?id='+val+'&studentlist='+studentid;
        }
        else
        {
            window.location='reports/modscorereport/reports-modscorereport-exportschedule.php?id='+val+'&studentlist='+studentid+'&assignmentlist='+moduleid;
        }
}


