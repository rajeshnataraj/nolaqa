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
			
	var dataparam = "oper=showschool&districtid="+districtid;	
	$.ajax({
		type: 'post',
		url: 'reports/mksreport/reports-mksreport-mksreportajax.php',
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

	var dataparam = "oper=showclass&districtid="+districtid+"&schoolid="+schoolid;
	$.ajax({
		type: 'post',
		url: 'reports/mksreport/reports-mksreport-mksreportajax.php',
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

function fn_showassignments(classid)
{
    $("#reports-pdfviewer").hide("fade").remove();
    $('#viewreportdiv').hide();
    $('#assignmentdiv').hide();
    $('#loadstudentidlist').hide();

    var dataparam = "oper=showassignments&classid="+classid;
    $.ajax({
            type: 'post',
            url: 'reports/mksreport/reports-mksreport-mksreportajax.php',
            data: dataparam,
            success:function(data) {
                $('#assignmentdiv').show();
                $('#assignmentdiv').html(data);//Used to load the student details in the dropdown
            }
    });
}

function fn_showstudentlist(id,assignmentid,type)
{

    $("#reports-pdfviewer").hide("fade").remove();
    $('#viewreportdiv').hide();
    $('#loadstudentidlist').hide();

var dataparam = "oper=showstudent&classid="+id+"&assignmentid="+assignmentid+"&type="+type;	
$.ajax({
		type: 'post',
		url: 'reports/mksreport/reports-mksreport-mksreportajax.php',
		data: dataparam,		
		beforeSend: function(){
			showloadingalert("Loading, please wait.");	
		},
		success:function(ajaxdata) {			
			$("#loadstudentidlist").show();
                        
                         var surveyrptexp= $('#hidexpsch').val();
                            if(surveyrptexp==0)
                            {
			$('#viewreportdiv').show();
                                $('#viewreportdivforexpsch').hide(); 
                            }
                            else
                            {
                                $('#viewreportdiv').hide();
                                $('#viewreportdivforexpsch').show(); 
                            }
                        
			
			$("#loadstudentidlist").html(ajaxdata);
			closeloadingalert();				
		}
	});

}


/****Survey Report For Exp Schedule****/
function  fn_dummyexpsch(hidval)
{
    $('#hidexpsch').val(hidval);
}


function fn_mksreport()
{

	$('#hidselectedstudentids').val(''); 
	var studentid = [];

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

	$('#hidselectedstudentids').val(studentid);
	var studentid =  $('#hidselectedstudentids').val();

	ids= $('#moduleid').val().split('~');
        var surveyrptexp= $('#hidexpsch').val();
	
	var hidfilename = $("#hidfilename").val()+new Date().getTime();     
        if(surveyrptexp==0)
        {
	var val = $('#districtid').val()+","+$('#schoolid').val()+","+$('#hidclassid').val()+","+ids[0]+","+ids[1]+","+ids[2];
            var oper = "mksreport";
        }
        else
        {
            var val = $('#districtid').val()+","+$('#schoolid').val()+","+$('#hidclassid').val()+","+ids[0]+","+ids[1]+","+ids[2];
            oper = "mksexpschreport";
        }

	setTimeout('removesections("#reports-mksreport");',500);
	ajaxloadingalert('Loading, please wait.');
	setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&studentlist='+studentid+'&oper='+oper+'&filename='+hidfilename+'");',500);


   
}
function fn_exportmksreport() {
	$('#hidselectedstudentids').val(''); 
	var studentid = [];

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

	$('#hidselectedstudentids').val(studentid);
	var studentid =  $('#hidselectedstudentids').val();

	ids= $('#moduleid').val().split('~');
	var val = $('#districtid').val()+","+$('#schoolid').val()+","+$('#hidclassid').val()+","+ids[0]+","+ids[1]+","+ids[2];

     window.location='reports/mksreport/reports-mksreport-export.php?id='+val+'&studentlist='+studentid;
}


