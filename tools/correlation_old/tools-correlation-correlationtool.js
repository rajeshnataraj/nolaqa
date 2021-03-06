

function fn_showstate(){
    
    $('#loadstate').show();
    $('#dpdocuments').hide();
    $('#divdocgrades').hide();
    $('#loadstandards').hide();
    $('#innerstandard').hide(); 
    
    var dataparam = "oper=showstate";
    
       $.ajax({
		type: 'post',
		url: 'tools/correlation/tools-correlation-correlationtoolajax.php',
		data: dataparam,
		success:function(data) {
			$('#loadstate').html(data);//Used to load the student details in the dropdown
		}
	});
    
}
/*----
    fn_showdocuments()
	Function to load documents from AB API
	stid -> State Id
----*/
function fn_showdocuments(stid)
{
    $('#divdocgrades').hide();
    $('#loadstandards').hide();
    $('#innerstandard').hide(); 
    
    var dataparam = "oper=showdocuments&stid="+stid;
    $.ajax({
            type: 'post',
            url: 'tools/correlation/tools-correlation-correlationtoolajax.php',
            data: dataparam,
            success:function(data) {
                $('#dpdocuments').show();
                    $('#dpdocuments').html(data);//Used to load the student details in the dropdown
            }
    });
}

/*----
    fn_showgrades()
	Function to load grades from AB API
	stid -> State Id
----*/
function fn_showgrades(subid)
{
    $('#loadstandards').hide();  
    $('#innerstandard').hide(); 
    var dataparam = "oper=showgrades&subid="+subid;
    $.ajax({
            type: 'post',
            url: 'tools/correlation/tools-correlation-correlationtoolajax.php',
            data: dataparam,
            success:function(data) {	
                 $('#divdocgrades').show();
                    $('#divdocgrades').html(data);//Used to load the student details in the dropdown
            }
    });
}

function fn_showinnerstandards(gradeid){
    $('#innerstandard').show();
    var dataparam = "oper=showinnerstandrads&gradeids="+gradeid;
    $.ajax({
        type: 'post',
        url: 'tools/correlation/tools-correlation-correlationtoolajax.php',
        data: dataparam,
        success:function(data) {

                $('#loadinnerstandards').html(data);//Used to load the student details in the dropdown
        }
    });
}
function fn_showtitles()
{
    
    var guid = [];
    $("input:checked").each(function () {
        var pid=$(this).val();
        guid.push(pid);
    });
   $('#loadproducts').hide();
     var dataparam = "oper=showtitles";
        $.ajax({
            type: 'post',
            url: 'tools/correlation/tools-correlation-correlationtoolajax.php',
            data: dataparam,
            success:function(data)
            {
                $('#loadtitles').show();
                    $('#loadtitles').html(data);

                }
        });
    
}
/*----
    fn_saveselect()
	Function to save the selected products
----*/
function fn_showproducts(type)
{
    if(type!=5){
            $('#destinationdiv').hide(); 
            $('#taskdiv').hide();
            $('#loadresource').hide();
         }
    var dataparam = "oper=showproducts&type="+type;

	$.ajax({
		type: 'post',
		url: 'tools/correlation/tools-correlation-correlationtoolajax.php',
		data: dataparam,
		success:function(data) {
                     $('#loadproducts').show();
			$('#loadproducts').html(data);//Used to load the student details in the dropdown
		}
	});
}





 
/************Expedition code start here**************/

 function fn_showdestination(productids)
{
     
     var dataparam = "oper=showdestination&expid="+productids;      
    $.ajax({
             type: 'post',
             url: 'tools/correlation/tools-correlation-correlationtoolajax.php',
             data: dataparam,
             success:function(data) {                   
                    $('#destinationdiv').show();
                    $('#destinationdiv').html(data);
             }
     });
}

function fn_showtasks()
{
        var destids = [];

        $("div[id^=list10_]").each(function()
        {
                var guid = $(this).attr('id').replace('list10_',''); 
                destids.push(guid);

        });
      
	var dataparam = "oper=showtasks&destids="+destids;
    $.ajax({
             type: 'post',
             url: 'tools/correlation/tools-correlation-correlationtoolajax.php',
             data: dataparam,
             success:function(data) {
                $('#taskdiv').html(data);//Used to load the student details in the dropdown
             }
     });
}



function fn_showresource()
{
     var taskids = [];
		
        $("div[id^=list12_]").each(function()
        {
                var guid = $(this).attr('id').replace('list12_',''); 
                taskids.push(guid);
                
        });
        
    $('#taskresource').show();
    
    var dataparam = "oper=showresource&taskids="+taskids;
    $.ajax({
             type: 'post',
             url: 'tools/correlation/tools-correlation-correlationtoolajax.php',
             data: dataparam,
             success:function(data) {
                $('#loadresource').show();
                $('#loadresource').html(data);//Used to load the student details in the dropdown
             }
     });
}

/*----
    fn_saveselect()
	Function to save the selected products
----*/
function fn_saveselect(alignmenttype){
    

    var prdtypes = [];
    var dataparam;

        var guids = [];
        $("input:checkbox[name=deepinnerid]:checked").each(function () {
            var pid=$(this).val();
            guids.push(pid);
        });
        
        $("div[id^=list6_]").each(function()
        {
            var guid = $(this).attr('id').replace('list6_',''); 
            prdtypes.push(guid);
     
        });
       
        var prdids=[];
        $("div[id^=list8_]").each(function()
        {
            var guid = $(this).attr('id').replace('list8_','');
            prdids.push(guid);
        });
        var resprdids=[];
                $("div[id^=list12_]").each(function()
                {
                    var guid = $(this).attr('id').replace('list12_','');
                    resprdids.push(guid);
                });
            
            for(var j=0;j<prdids.length;j++)
            {
                var prdidtyp=prdids[j];
                var typeid=prdidtyp.split('_');

                 var expprdids=[];
                 var produids=[];
                 if(typeid[1]==5)
                 {
                     var deastprdids=[];
                    $("div[id^=list10_]").each(function()
                        {
                            var guid = $(this).attr('id').replace('list10_','');
                            deastprdids.push(guid);
                        });
                    var taskprdids=[];
                    $("div[id^=list12_]").each(function()
                    {
                        var guid = $(this).attr('id').replace('list12_','');
                        taskprdids.push(guid);
                    });
                    var resprdids=[];
                    $("div[id^=list12_]").each(function()
                    {
                        var guid = $(this).attr('id').replace('list12_','');
                        resprdids.push(guid);
                    });                    
                     var guids = [];
                    $("input:checkbox[name=deepinnerid]:checked").each(function () {
                        var pid=$(this).val();
                        guids.push(pid);
                    });
                       
                    var stateid=$('#stateid').val();
                    var documentid=$('#documentsubid').val();
                    var grades=$('#grades').val();
    
                    if(stateid=='')
                    {
                    $.Zebra_Dialog("Please Select State", { 'type': 'information', 'buttons': false, 'auto_close': 1000 });

                    return false;
    }
                    if(documentid=='')
    {
                    $.Zebra_Dialog("Please Select Documents", { 'type': 'information', 'buttons': false, 'auto_close': 1000 });

                    return false;
                    }
                    if(grades=='')
                    {
                    $.Zebra_Dialog("Please Select Grades", { 'type': 'information', 'buttons': false, 'auto_close': 1000 });

                    return false;
                    }
                     if(guids=='')
                    {
                    $.Zebra_Dialog("Please Select Checkbox to Make Alignment", { 'type': 'information', 'buttons': false, 'auto_close': 1000 });

                    return false;
                    }
                    if(prdtypes=='')
                    {
    $.Zebra_Dialog("Please Select Titles", { 'type': 'information', 'buttons': false, 'auto_close': 1000 });

    return false;
    }
                    if(prdids=='')
    {
    $.Zebra_Dialog("Please Select Product", { 'type': 'information', 'buttons': false, 'auto_close': 1000 });

    return false;
    }
                    if(deastprdids=='')
                    {
                    $.Zebra_Dialog("Please Select Destination", { 'type': 'information', 'buttons': false, 'auto_close': 1000 });

                    return false;
                    }
                    if(taskprdids=='')
                    {
                    $.Zebra_Dialog("Please Select Task", { 'type': 'information', 'buttons': false, 'auto_close': 1000 });

                    return false;
                    }
                    if(resprdids=='')
                    {
                    $.Zebra_Dialog("Please Select Resource", { 'type': 'information', 'buttons': false, 'auto_close': 1000 });

                    return false;
                    }
                }
                 else
                 {
                     
                    var guids = [];
                       $("input:checkbox[name=deepinnerid]:checked").each(function () {
                       var pid=$(this).val();
                       guids.push(pid);
                       });
                       
                    var stateid=$('#stateid').val();
                    var documentid=$('#documentsubid').val();
                    var grades=$('#grades').val();
                    
                    if(stateid=='')
                    {
                    $.Zebra_Dialog("Please Select State", { 'type': 'information', 'buttons': false, 'auto_close': 1000 });

                    return false;
                    }
                    if(documentid=='')
                    {
                    $.Zebra_Dialog("Please Select Documents", { 'type': 'information', 'buttons': false, 'auto_close': 1000 });

                    return false;
                    }
                    if(grades=='')
                    {
                    $.Zebra_Dialog("Please Select Grades", { 'type': 'information', 'buttons': false, 'auto_close': 1000 });

                    return false;
                    }
                                     if(guids=='')
                    {
                                    $.Zebra_Dialog("Please Select Checkbox to Make Alignment", { 'type': 'information', 'buttons': false, 'auto_close': 1000 });

                    return false;
                    }
                                    if(prdtypes=='')
                                    {
                                    $.Zebra_Dialog("Please Select Titles", { 'type': 'information', 'buttons': false, 'auto_close': 1000 });

                    return false;
                    }
                                    if(prdids=='')
                    {
                                    $.Zebra_Dialog("Please Select Product", { 'type': 'information', 'buttons': false, 'auto_close': 1000 });

                    return false;
                    }
            } // else ends 

            } // for ends
            
            dataparam = "oper=makecorrelation&ptype="+prdtypes+"&guids="+guids+"&productid="+prdids+"&resid="+resprdids;            
                        $.ajax({
                        type: 'post',
                        url: 'tools/correlation/tools-correlation-correlationtoolajax.php',
                        data: dataparam,
                        beforeSend: function()
                        {
                                showloadingalert('Loading, please wait...');
                        },
                        success:function(data) {
                        
                                var employeeData = JSON.parse(data);
                                fn_createalignment(employeeData,alignmenttype);
                        }
                        });

 }
 
 function fn_createalignment(stdassetid,alignmenttype){    
     
     var stdlen=stdassetid.length;
 
     for(var i=0;i<stdassetid.length;i++){
        
         var assetseparation=stdassetid[i].split("~");
        
         var dataparam = "oper=correlationsignature&passetguid="+assetseparation[1]+"&standguids="+assetseparation[0]+"&icnt="+i+"&ptype="+assetseparation[2]+"&prdid="+assetseparation[3];
        $.ajax({
		type: 'post',
		url: 'tools/correlation/tools-correlation-correlationtoolajax.php',
		data: dataparam,
		success:function(data) {
                   
                        var icount=data.split("~");
                        var ival=icount[0];
                        var apiurl=icount[1];
                        var ptype= icount[2];
                        var prdid= icount[3];
                        var standardid= icount[4];
                        var prdassetid= icount[5];
                        var stateid=$('#stateid').val();
                        var documentid=$('#documentsubid').val();
                        var grades=$('#grades').val();
                        
                        var guid = [];
                        $("input:checkbox[name=deepinnerid]:checked").each(function () {
                        var pid=$(this).val();
                        guid.push(pid);
                        });
                        if(alignmenttype===1) // create alignment
                        {
                            fn_alignmentcompleted(apiurl,ival,stdlen,ptype,prdid,stateid,documentid,grades,standardid,prdassetid); // 
                        }
                        else if(alignmenttype===2) // delete alignment
                        {
                            fn_alignmentdeleted(apiurl,ival,stdlen,ptype,prdid,stateid,documentid,grades,standardid,prdassetid,alignmenttype);
                        }
                    
		}
	   });
       }
 }
 
 function fn_alignmentcompleted(apicall,ival,stdlen,ptype,prdid,stateid,documentid,grades,standardid,prdassetid){
    var tot=stdlen-1;    
     $.ajax({
		type: 'post',
		url: apicall,
                dataType: "json",
                success: function(data) {
                    
                    closeloadingalert();
                    fn_savealignment(ptype,prdid,stateid,documentid,grades,standardid,prdassetid,ival,stdlen);
                }
                }).fail(function($xhr) {
                    
                        if($xhr.status==409)
                        {
                             if(ival==tot){
                            closeloadingalert();
                            $.Zebra_Dialog("Alignment Exists ! \n\
                                            Please change the parameters and create a new one or exclude the existing one.", { 'type': 'information', 'buttons': false, 'auto_close': 4500 });
                            return false;
                             }
                        }
                        
                    });
        
                
}
 
 function fn_alignmentdeleted(apicall,ival,stdlen,ptype,prdid,stateid,documentid,grades,standardid,prdassetid,alignmenttype){
    var tot=stdlen-1;
    
     $.ajax({
		type: 'DELETE',
		url: apicall,
                dataType: "json",
                success: function(data) {
                    closeloadingalert();
                    fn_savedeletedalignment(ptype,prdid,stateid,documentid,grades,standardid,prdassetid,ival,stdlen,alignmenttype);
                }
                }).fail(function($xhr) {
                    
                    closeloadingalert();
                        
                    });
        
                
}
 
 function fn_savealignment(ptype,prdid,stateid,documentid,grades,standardid,prdassetid,ival,stdlen,alignmenttype)
 {
 var tot=stdlen-1;
  var dataparam = "oper=savealignment&ptype="+ptype+"&prdid="+prdid+"&stateid="+stateid+"&documentid="+documentid+"&grades="+grades+"&guid="+standardid+"&prdassetid="+prdassetid+"&alignmenttype="+alignmenttype;
 
  $.ajax({
        type: 'post',
        url: 'tools/correlation/tools-correlation-correlationtoolajax.php',
        data: dataparam,
        success:function()
        {
            if(ival==tot)
            {
                $.Zebra_Dialog("Alignment Created Successfully !", { 'type': 'information', 'buttons': false, 'auto_close': 1000 });
                return false;
            }
        }
    });
}

 function fn_savedeletedalignment(ptype,prdid,stateid,documentid,grades,standardid,prdassetid,ival,stdlen,alignmenttype)
 {
 var tot=stdlen-1;
  var dataparam = "oper=savealignment&ptype="+ptype+"&prdid="+prdid+"&stateid="+stateid+"&documentid="+documentid+"&grades="+grades+"&guid="+standardid+"&prdassetid="+prdassetid+"&alignmenttype="+alignmenttype;
 
  $.ajax({
        type: 'post',
        url: 'tools/correlation/tools-correlation-correlationtoolajax.php',
        data: dataparam,
        success:function()
        {
            if(ival==tot)
            {
                $.Zebra_Dialog("Alignment Excluded !", { 'type': 'information', 'buttons': false, 'auto_close': 1000 });
                return false;
            }
        }
    });
}

function fn_checkall(){
    $('#selecctall').click(function(event) {  //on click
    if(this.checked) { // check select status
        $('.checkbox-class').each(function() { //loop through each checkbox
            this.checked = true;  //select all checkboxes with class "checkbox1"              
        });
    }else{
        $('.checkbox-class').each(function() { //loop through each checkbox
            this.checked = false; //deselect all checkboxes with class "checkbox1"                      
        });        
    }
});

}

/*----
    fn_movealllistitems()
	Function to move all items from lest to right and right to left
----*/
function fn_movealllistitems(leftlist,rightlist,id,courseid)
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
		var clas=$('#'+leftlist+'_'+courseid).attr('class');
		
		if(clas=="draglinkleft")
		{
			$('#'+rightlist).append($('#'+leftlist+' #'+leftlist+'_'+courseid));
			$('#'+leftlist+'_'+courseid).removeClass('draglinkleft').addClass('draglinkright');
			
			var temp = $('#'+leftlist+'_'+courseid).attr('id').replace(leftlist,rightlist);					
			var ids='id';
			$('#'+leftlist+'_'+courseid).attr(ids,temp);
		}
		else 
		{	
			$('#'+leftlist).append($('#'+rightlist+' #'+rightlist+'_'+courseid));
			$('#'+rightlist+'_'+courseid).removeClass('draglinkright').addClass('draglinkleft');
		
			var temp = $('#'+rightlist+'_'+courseid).attr('id').replace(rightlist,leftlist);					
			var ids='id';
			$('#'+rightlist+'_'+courseid).attr(ids,temp);
		}
	}

	if(leftlist=="list5" || leftlist=="list6" && rightlist=="list6" || rightlist=="list5")
	{
            var typeids=[];
            $("div[id^=list6_]").each(function()
		{
			var guid = $(this).attr('id').replace('list6_','');
			typeids.push(guid);
			
                        
		});
            fn_showproducts(typeids);
         }
         if(leftlist=="list7" || leftlist=="list8" && rightlist=="list8" || rightlist=="list7")
	{
            
              var typeid=courseid.split('_');
             
                if(typeid[1]==5)
                {
                    fn_showdestination(courseid);
                }
                else
                {
                    fn_showstate();
        }
        }
        if(leftlist=="list9" || leftlist=="list10" && rightlist=="list10" || rightlist=="list9"  )
        {           
           $('#taskdiv').show();
           fn_showtasks();
        }
        if(leftlist=="list11" || leftlist=="list12" && rightlist=="list12" || rightlist=="list11"  )
        {          
           $('#resourcediv').show();
           fn_showresource();
        }
        if(leftlist=="list13" || leftlist=="list14" && rightlist=="list14" || rightlist=="list13" )
        {
            fn_showstate();
 }
 }
 
