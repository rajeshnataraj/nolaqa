/*******fn_showclass()
		Function is used to load the class names
******/
function fn_showclass()
{	
	var dataparam = "oper=showclass";
        $.ajax({
            type: 'post',
            url: 'reports/schooladministrator/reports-schooladministrator-ajax.php',
            data: dataparam,
            beforeSend: function(){                  
            },
            success:function(data) {
                    $('#classdiv').show();   
                    $('#classdiv').html(data);//Used to load the class details in the listbox
            }
    });
}

function fn_showclasspitsco(schoolid,distid)
{	
	var dataparam = "oper=showclass&schlid="+schoolid+"&distid="+distid;
        $.ajax({
            type: 'post',
            url: 'reports/schooladministrator/reports-schooladministrator-ajax.php',
            data: dataparam,
            beforeSend: function(){                   
            },
            success:function(data) {
                    $('#classdiv').show();   
                    $('#classdiv').html(data);//Used to load the class details in the listbox
            }
    });
}

function fn_movealllistitems(leftlist,rightlist,id)
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
        
        if(leftlist=='list15' || leftlist=='list16' || rightlist=='list15' || rightlist=='list16')
        {
            fn_showteachers();
            fn_showassignments();
        }
        
        if(leftlist=='list17' || leftlist=='list18' || rightlist=='list17' || rightlist=='list18')
        {
            fn_showassignments();
        }
	
	
}

function fn_showschool(distid)
{ 
    
    var dataparam = "oper=showschools&distid="+distid;
    
    $.ajax({
            type: 'post',
            url: 'reports/schooladministrator/reports-schooladministrator-ajax.php',
            data: dataparam,
            beforeSend: function(){                   
            },
            success:function(data) {
                    $('#schooldiv').show();   
                    $('#schooldiv').html(data);//Used to load the student details in the dropdown
            }
    });
}


function fn_showteachers()
{
    var list16 = [];
	$("div[id^=list16_]").each(function(){
		list16.push($(this).attr('id').replace('list16_',''));
	});
    var dataparam = "oper=showteachers&classid="+list16;
    
    $.ajax({
            type: 'post',
            url: 'reports/schooladministrator/reports-schooladministrator-ajax.php',
            data: dataparam,
            beforeSend: function(){
            },
            success:function(data) {
                    $('#teacherdiv').show();  
                    $('#teacherdiv').html(data);//Used to load the student details in the dropdown
            }
    });
}


function fn_showassignments()
{
    var list16 = [];
	$("div[id^=list16_]").each(function(){
		list16.push($(this).attr('id').replace('list16_',''));
	});
        
  var list18 = [];
	$("div[id^=list18_]").each(function(){
		list18.push($(this).attr('id').replace('list18_',''));
	});
        
    var dataparam = "oper=showassignments&classid="+list16+"&teacherid="+list18;
    
    $.ajax({
            type: 'post',
            url: 'reports/schooladministrator/reports-schooladministrator-ajax.php',
            data: dataparam,
            beforeSend: function(){                   
            },
            success:function(data) {
                    $('#assignmentsdiv').show();  
                    $('#viewreportdiv').show();
                    $('#assignmentsdiv').html(data);//Used to load the student details in the dropdown
            }
    });
}



function fn_schooladministratorreport(profileid)
{
        var assignmentid=[];
        var classid=[];
        var teacherid=[];

        $("div[id^=list16_]").each(function()
	{
	  var classlist = $(this).attr('id').replace('list16_','');
	  classid.push(classlist);
	});
        
        $("div[id^=list18_]").each(function()
	{
	  var teacherlist = $(this).attr('id').replace('list18_','');
	  teacherid.push(teacherlist);
	});
        
        
	$("div[id^=list20_]").each(function()
	{
	var assignmentlist = $(this).attr('id').replace('list20_','');
	assignmentid.push(assignmentlist);
	});

	if(assignmentid=='')
	{
	showloadingalert("please select any one Assignment.");
	setTimeout('closeloadingalert()',2000);
	return false;
	}
        
        if(profileid==2)
        {
            if($('#categoryid').val()==3)
            {
            var val = $('#districtid').val()+","+$('#schoolid').val();
        }
            else if($('#categoryid').val()==1)
            {
                var val = "0,"+$('#schoolid').val();
            }
        else
        {
                var val = "0,0";
            }
        }
        else
        {
            var val='';
        }
        
        
        setTimeout('removesections("#reports-schooladministrator");',500);
	var oper = "schooladministratorreport";
	var hidfilename = $("#hidfilename").val()+new Date().getTime();
	ajaxloadingalert('Loading, please wait.');
	setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&assignments='+assignmentid+'&classlist='+classid+'&teacherlist='+teacherid+'&oper='+oper+'&filename='+hidfilename+'");',500);


   
}


/*******fn_load_school_purcahse()
		Function is used to load the school purchase
******/
function fn_schoolpurchase(type)
{	
    $('#districtdiv').hide(); 
    $('#schooldiv').hide(); 
    $('#classdiv').hide(); 
    $('#teacherdiv').hide();
    $('#assignmentsdiv').hide();
    $('#viewreportdiv').hide();
    var dataparam = "oper=showschoolpurchase";
    $.ajax({
            type: 'post',
            url: 'reports/schooladministrator/reports-schooladministrator-ajax.php',
            data: dataparam,
            beforeSend: function(){                    
            },
            success:function(data) {	
                    $('#schooldiv').show();   
                    $('#schooldiv').html(data);
            }
    });
	
}
/*******fn_load_home_purcahse()
		Function is used to load the home purchase
******/
function fn_homepurchase(type)
{
    $('#districtdiv').hide(); 
    $('#schooldiv').hide(); 
    $('#classdiv').hide(); 
    $('#teacherdiv').hide();
    $('#assignmentsdiv').hide();
    $('#viewreportdiv').hide();
    var dataparam = "oper=showhomepurchase";
    $.ajax({
            type: 'post',
            url: 'reports/schooladministrator/reports-schooladministrator-ajax.php',
            data: dataparam,
            beforeSend: function(){                    
            },
            success:function(data) {	
                    $('#classdiv').show();
                    $('#classdiv').html(data);
            }
    });
   
}

/*******fn_load_home_purcahse()
		Function is used to load the home purchase
******/
function fn_distpurchase(type)
{	
    $('#districtdiv').hide(); 
    $('#schooldiv').hide(); 
    $('#classdiv').hide(); 
    $('#teacherdiv').hide();
    $('#assignmentsdiv').hide();
    $('#viewreportdiv').hide();
    var dataparam = "oper=showdistpurchase";
    $.ajax({
            type: 'post',
            url: 'reports/schooladministrator/reports-schooladministrator-ajax.php',
            data: dataparam,
            beforeSend: function(){                  
            },
            success:function(data) {	
                    $('#districtdiv').show();   
                    $('#districtdiv').html(data);
            }
    });
}