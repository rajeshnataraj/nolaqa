/*
    Created By - Mohan.M
    Page - sos-data.js
    History:
*/

function fn_newclass()
{
    $.fancybox.showActivity();
    $.ajax({
        type	: "POST",
        cache	: false,
        url     : "sos/data/sos-data-ajax.php",
        data:"oper=newclassnameform",
        success: function(data) {
                $.fancybox(data,{'modal': true,'autoDimensions':false,'width':480,'autoScale':true,'height':177, 'scrolling':'no'});
                $.fancybox.resize();
        }
    });

    return false; 
    
}

function fn_cancelclassform()
{
	$.fancybox.close();
}

function fn_saveclassform()
{
	if($("#classnameextendforms").validate().form())
	{
	var classnametxt=$('#txtclassname').val();	
	$.ajax({
			type	: "POST",
			cache	: false,
			url     : "sos/data/sos-data-ajax.php",
			data:"oper=saveclasstxt&classnametxt="+escapestr(classnametxt),
			success: function(data) {
                            if(data=='fail')
                            {
                                showloadingalert("Either change the module name or module version number.");	
                                setTimeout('closeloadingalert()',2000);
                                return false;
                            }
                            else if(data=='success')
                            {
                                  fn_cancelclassform();
                                 
                                var dataparam = "oper=loadclassname";
                                $.ajax({
                                        url : "sos/data/sos-data-ajax.php",
                                        data: dataparam,
                                        type: "POST",
                                        beforeSend: function(){                                               
                                        },
                                        success: function (data) {	
                                            $('#classnameload').html(data);
                                               
                                        },
                                });
                        }
			 
		 }
		
		});
	}
	
}


function fn_showdsstusetting()
{
   $('#datasheetstusetting').show();		
}


function fn_addstudent()
{
    $('#datasheet').show();
    $('#btndiv').show();
}


function acceptablecarname(i,num) {
  //  b. Car Length – acceptable range 200 mm-305 mm
    if(i=='2'){       
        var headercount = $('#myTable05 th').length;        

        var currentcarname=$('#txt_'+2+'_'+num).val();       

        var k=0;
        var carnameval=new Array();
            
        if(headercount!=2){
            for(var m=2;m<=headercount;m++)
            {
                if(m==num){
            
                }
                else{
                    carnameval[k]=$('#txt_2'+'_'+m).val()+"~"+num;
                    k++;
                }

            }

            for(var i=0; i<carnameval.length; i++) 
            {
                var dup=carnameval[i].split('~');
                if (dup[0] === currentcarname)
                {
                    var data ="Car name already exists.";	  

        $.Zebra_Dialog("<div style='text-align:left'>"+data+"</div>",
        {
            'type':     'confirmation',
            'buttons':  [
                            {caption: 'OK', callback: function() {
                                            $('#txt_'+2+'_'+dup[1]).val('');
                                            $('#txt_'+2+'_'+dup[1]).focus();
                            }},
                        ]
        });
                    $('.ZebraDialog').css({"posiion":"absolute","left":"50%","top":"50%","transform":"translate(-50%,-50%)","width":"443px"});
        closeloadingalert();
        }
    }
        }
    }
}
    
//Acceptable Range
function acceptablerange(i) {
  //  b. Car Length – acceptable range 200 mm-305 mm
    if(i=='4'){
        var carlengths=200;  
        var carlengthe=305;
        var txtcarlength=$('#txt_'+4).val();
        if(!(parseInt(txtcarlength) >= carlengths  && parseInt(txtcarlength) <= carlengthe)){
            
        var data ="Acceptable Range of Car length is 200mm - 305mm. Try again";	  

        $.Zebra_Dialog("<div style='text-align:left'>"+data+"</div>",
        {
            'type':     'confirmation',
            'buttons':  [
                            {caption: 'OK', callback: function() {
                                $('#txt_'+4).val('');
                                $('#txt_'+4).focus();
                            }},
                        ]
        });
        $('.ZebraDialog').css({"posiion":"absolute","left":"50%","top":"50%","transform":"translate(-50%,-50%)","width":"598px"});
        closeloadingalert();

        }
    }
//    
  // c. Car Weight – acceptable range 40 grams – 200 grams
    if(i=='5'){
        var txtcarweight=$('#txt_'+5).val();
        var carweights=40;  
        var carweighte=200;
        if(!(parseInt(txtcarweight) >= carweights  && parseInt(txtcarweight) <= carweighte)){
            
        var data ="Acceptable Range of Car Weight is 40grams - 200grams. Try again";	  

        $.Zebra_Dialog("<div style='text-align:left'>"+data+"</div>",
        {
            'type':     'confirmation',
            'buttons':  [
                            {caption: 'OK', callback: function() {
                                $('#txt_'+5).val('');
                                $('#txt_'+5).focus();
                            }},
                        ]
        });
        $('.ZebraDialog').css({"posiion":"absolute","left":"50%","top":"50%","transform":"translate(-50%,-50%)","width":"598px"});
        closeloadingalert();

        }
    }
    
  //d.	Wheelbase – acceptable range 105 mm-270mm      
    if(i=='6'){
        var wheelbases=105;   
        var wheelbasee=270;
        var txtwheel=$('#txt_'+6).val();
        if(!(parseInt(txtwheel) >= wheelbases  && parseInt(txtwheel) <= wheelbasee)){
            
        var data ="Acceptable Range of Wheelbase is 105mm - 270mm. Try again";	  

        $.Zebra_Dialog("<div style='text-align:left'>"+data+"</div>",
        {
            'type':     'confirmation',
            'buttons':  [
                            {caption: 'OK', callback: function() {
                                 $('#txt_'+6).val('');
                                 $('#txt_'+6).focus();
                            }},
                        ]
        });
        $('.ZebraDialog').css({"posiion":"absolute","left":"50%","top":"50%","transform":"translate(-50%,-50%)","width":"598px"});
        closeloadingalert();
  
        }
    }
    
 // e.	Distance between screw eyes – acceptable range 155 mm-270mm 
    if(i=='7'){
        var screws=155;       
        var screwe=270;
        var txtscrew=$('#txt_'+7).val();
        if(!(parseInt(txtscrew) >= screws  && parseInt(txtscrew) <= screwe)){
            
            var data ="Acceptable Range of Distance between screw eyes is 155mm - 270mm. Try again";	  

            $.Zebra_Dialog("<div style='text-align:left'>"+data+"</div>",
            {
                'type':     'confirmation',
                'buttons':  [
                                {caption: 'OK', callback: function() {
                                     $('#txt_'+7).val('');
                                     $('#txt_'+7).focus();
                                }},
                            ]
            });
            $('.ZebraDialog').css({"posiion":"absolute","left":"50%","top":"50%","transform":"translate(-50%,-50%)","width":"598px"});
            closeloadingalert();

        }
    }
  
 //f.Front Axle Body Width – acceptable range 35 mm-42 mm   
    if(i=='8'){
        var fbodys=35;       
        var fbodye=42;
        var txtfbody=$('#txt_'+8).val();
        if(!(parseInt(txtfbody) >= fbodys  && parseInt(txtfbody) <= fbodye)){
            
            var data ="Acceptable Range of Front Axle Body Width is 35mm - 42mm. Try again";	  

            $.Zebra_Dialog("<div style='text-align:left'>"+data+"</div>",
            {
                'type':     'confirmation',
                'buttons':  [
                                {caption: 'OK', callback: function() {
                                    $('#txt_'+8).val('');
                                    $('#txt_'+8).focus();
                                }},
                            ]
            });
            $('.ZebraDialog').css({"posiion":"absolute","left":"50%","top":"50%","transform":"translate(-50%,-50%)","width":"598px"});
            closeloadingalert();

        }
    }
   
 //g. Rear Axle Body Width – acceptable range 35 mm-42 mm
    if(i=='9'){
        var rbodys=35;       
        var rbodye=42;
        var txtrbody=$('#txt_'+9).val();
        if(!(parseInt(txtrbody) >= rbodys  && parseInt(txtrbody) <= rbodye)){
            
            var data ="Acceptable Range of Rear Axle Body Width is 35mm - 42mm. Try again";	  

            $.Zebra_Dialog("<div style='text-align:left'>"+data+"</div>",
            {
                'type':     'confirmation',
                'buttons':  [
                                {caption: 'OK', callback: function() {
                                    $('#txt_'+9).val('');
                                    $('#txt_'+9).focus();
                                }},
                            ]
            });
            $('.ZebraDialog').css({"posiion":"absolute","left":"50%","top":"50%","transform":"translate(-50%,-50%)","width":"598px"});
            closeloadingalert();

        }
    }
    
 // h. Front Axle Length – acceptable range 42 mm-70 mm     
    if(i=='10'){
        var flengths=42;      
        var flengthe=70;
        var txtflength=$('#txt_'+10).val();
        if(!(parseInt(txtflength) >= flengths  && parseInt(txtflength) <= flengthe)){
            
            var data ="Acceptable Range of Front Axle Length is 42mm - 70mm. Try again";	  

            $.Zebra_Dialog("<div style='text-align:left'>"+data+"</div>",
            {
                'type':     'confirmation',
                'buttons':  [
                                {caption: 'OK', callback: function() {
                                    $('#txt_'+10).val('');
                                    $('#txt_'+10).focus();
                                }},
                            ]
            });
            $('.ZebraDialog').css({"posiion":"absolute","left":"50%","top":"50%","transform":"translate(-50%,-50%)","width":"598px"});
            closeloadingalert();

        }
    }
   
  //  i. Rear Axle Length - acceptable range 42 mm-70 mm 
    if(i=='11'){
        var rlengths=42;      var rlengthe=70;
        var txtflength=$('#txt_'+11).val();
        if(!(parseInt(txtflength) >= rlengths  && parseInt(txtflength) <= rlengthe)){
            
            var data ="Acceptable Range of Rear Axle Length  is 42mm - 70mm. Try again";	  

            $.Zebra_Dialog("<div style='text-align:left'>"+data+"</div>",
            {
                'type':     'confirmation',
                'buttons':  [
                                {caption: 'OK', callback: function() {
                                     $('#txt_'+11).val('');
                                     $('#txt_'+11).focus();
                                }},
                            ]
            });
            $('.ZebraDialog').css({"posiion":"absolute","left":"50%","top":"50%","transform":"translate(-50%,-50%)","width":"598px"});
            closeloadingalert();

        }
    }
    
  //  j. Rear axle hole from rear of wooden block – acceptable range 9 mm-100 mm
    if(i=='12'){
        var rearholes=9;      var rearholee=100;
        var txtrearhole=$('#txt_'+12).val();
        if(!(parseInt(txtrearhole) >= rearholes  && parseInt(txtrearhole) <= rearholee)){
            
            var data ="Acceptable Range of Rear axle hole from rear of wooden block is 9mm - 100mm. Try again";	  

            $.Zebra_Dialog("<div style='text-align:left'>"+data+"</div>",
            {
                'type':     'confirmation',
                'buttons':  [
                                {caption: 'OK', callback: function() {
                                    $('#txt_'+12).val('');
                                    $('#txt_'+12).focus();
                                }},
                            ]
            });
            $('.ZebraDialog').css({"posiion":"absolute","left":"50%","top":"50%","transform":"translate(-50%,-50%)","width":"598px"});
            closeloadingalert();

        }
    }
    
  //  k. Bottom of front axle hole above bottom of wooden block – acceptable range 5 mm-10 mm
    if(i=='13'){
        var bottomfronts=5;   var bottomfronte=10;
        var txtbottomfront=$('#txt_'+13).val();
        if(!(parseInt(txtbottomfront) >= bottomfronts  && parseInt(txtbottomfront) <= bottomfronte)){
            
            var data ="Acceptable Range of Bottom of front axle hole above bottom of wood is 5mm - 10mm. Try again";	  

            $.Zebra_Dialog("<div style='text-align:left'>"+data+"</div>",
            {
                'type':     'confirmation',
                'buttons':  [
                                {caption: 'OK', callback: function() {
                                    $('#txt_'+13).val('');
                                    $('#txt_'+13).focus();
                                }},
                            ]
            });
            $('.ZebraDialog').css({"posiion":"absolute","left":"50%","top":"50%","transform":"translate(-50%,-50%)","width":"598px"});
            closeloadingalert();

        }
    }
    
   // l. Bottom of rear axle hole above bottom of wooden block – acceptable range 5 mm-10 mm 
     if(i=='14'){
        var bottomrears=5;    var bottomreare=10;
        var txtbottomreart=$('#txt_'+14).val();
        if(!(parseInt(txtbottomreart) >= bottomrears  && parseInt(txtbottomreart) <= bottomreare)){
            
            var data ="Acceptable Range of Bottom of rear axle hole above bottom of wood is 5mm - 10mm. Try again";	  

            $.Zebra_Dialog("<div style='text-align:left'>"+data+"</div>",
            {
                'type':     'confirmation',
                'buttons':  [
                                {caption: 'OK', callback: function() {
                                    $('#txt_'+14).val('');
                                    $('#txt_'+14).focus();
                                }},
                            ]
            });
            $('.ZebraDialog').css({"posiion":"absolute","left":"50%","top":"50%","transform":"translate(-50%,-50%)","width":"598px"});
            closeloadingalert();
 
        }
    }
    
   // m. Height of cartridge hole from bottom of wooden block to hole  – acceptable range 31 mm-35 mm
    if(i=='15'){
     var cartridges=31;    var cartridgee=35;
        var txtcartridge=$('#txt_'+15).val();
        if(!(parseInt(txtcartridge) >= cartridges  && parseInt(txtcartridge) <= cartridgee)){
            
            var data ="Acceptable Range of Height of cartridge hole from bottom of wooden block to hole  is 31mm - 35mm. Try again";	  

            $.Zebra_Dialog("<div style='text-align:left'>"+data+"</div>",
            {
                'type':     'confirmation',
                'buttons':  [
                                {caption: 'OK', callback: function() {
                                    $('#txt_'+15).val('');
                                    $('#txt_'+15).focus();
                                }},
                            ]
            });
            $('.ZebraDialog').css({"posiion":"absolute","left":"50%","top":"50%","transform":"translate(-50%,-50%)","width":"598px"});
            closeloadingalert();

        }
    }
    
  // n.	Clearance around cartridge hole – acceptable range 3 mm minimum  
    if(i=='16'){
        var clearances=3;
        var txtclearances=$('#txt_'+16).val();
        if(clearances > parseInt(txtclearances)){
            
            var data ="Acceptable Range of Clearance around cartridge hole is 3mm minimum. Try again";	  

            $.Zebra_Dialog("<div style='text-align:left'>"+data+"</div>",
            {
                'type':     'confirmation',
                'buttons':  [
                                {caption: 'OK', callback: function() {
                                    $('#txt_'+16).val('');
                                    $('#txt_'+16).focus();
                                }},
                            ]
            });
            $('.ZebraDialog').css({"posiion":"absolute","left":"50%","top":"50%","transform":"translate(-50%,-50%)","width":"598px"});
            closeloadingalert();

        }
    }

}



function fn_savesheet(id,hiddval)
{
    if($("#dataforms").validate().form()) //Validates the Module Form
    {
        var datasheetname=$('#txtdatasheetname').val();
        var term=$('#term').val();
        var state=$('#ddlstate').val();
        var year=$('#year').val();
        var classname=$('#classname').val();
        var tracklen=$('#tracklen').val();
        var co2cart=$('#co2').val();
        var txttracksuface=$('#txttracksuface').val();

        if(state==""){
            showloadingalert("Please Select State.");
            setTimeout('closeloadingalert()',1000);
            return false;  
        }
        if(year==""){
            showloadingalert("Please Select Year.");
            setTimeout('closeloadingalert()',1000);
            return false;  
        }
        if(classname==""){
            showloadingalert("Please Select Classname.");
            setTimeout('closeloadingalert()',1000);
            return false;  
        }
        if(tracklen==""){
            showloadingalert("Please Select Track Length.");
            setTimeout('closeloadingalert()',1000);
            return false;  
        }
        if(co2cart==""){
            showloadingalert("Please Select CO2 Cartridge.");
            setTimeout('closeloadingalert()',1000);
            return false;  
        }

        var headercount = $('#myTable0 th').length;
       
        var stcount=$('#studentcount').val();
        var dashid=$('#dashid').val();
        var x=1;  
        
        var newstucount=$('#newstuid').val();

       
        var flag='0';
        for(var k=2;k<=headercount;k++)
        {
            
            for(var l=1;l<=16;l++)
            {
                var mm=$('#txt_'+l).val();
                if(mm==''){
                    flag++;
                }
            }         
        }

        var k=0;
        var detail=new Array();
        for(var i=2;i<=headercount;i++)
        {
            for(var j=1;j<=16;j++)
            {
                   detail[k] = $('#txt_'+j).val();
                    if(j==16){                       
                        detail[k] = $('#txt_'+j).val()+"^"; 
                    }
                k++;
            }            
        }

        if(flag !='0'){
            
            if(hiddval=='3')
            {                
            var data ="The empty fields can be completed at a later time.";	  

                $.Zebra_Dialog("<div style='text-align:left'>"+data+"</div>",
                {
                    'type':     'confirmation',
                    'buttons':  [
                    {caption: 'OK', callback: function() {

                    if(id=='0'){
                        actionmsg = "Saving";
                        alertmsg = "Data Sheet has been Created Successfully"; 
                    }
                    else{
                        actionmsg = "Updating";
                        alertmsg = "Data Sheet has been updated Successfully"; 
                    }
                    var dataparam = "oper=savedatasheet&sheetid="+id+"&term="+term+"&state="+state+"&year="+year+"&classname="+classname+"&datasheetname="+datasheetname+"&detail="+detail+"&headercount="+headercount+"&tracklen="+tracklen+"&co2cart="+co2cart+"&txttracksuface="+txttracksuface+"&dashid="+dashid+"&newstucount="+newstucount;                     
                    $.ajax({
                        url : "sos/data/sos-data-ajax.php",
                        data: dataparam,
                        type: "POST",
                        beforeSend: function(){
                               showloadingalert(actionmsg+", please wait.");	
                        },
                        success: function (data) {
                            var response=trim(data);
                            var output=response.split('~');
                            var status=output[0];
                            var dsid=output[1];
                            var sid=output[2];
                            var prestucnt=output[3];
                            var nxtstucnt=output[4];                               
                                if(status=="success") //Works if the data saved in db
                                {
                                    $('.lb-content').html(alertmsg);
                                    setTimeout('closeloadingalert()',500);                                    
                                    if(hiddval=='0')
                                    {
                                        $('#dashid').val(dsid);
                                        $('#currentstuid').val(sid);                                       
                                            $('#btndel').removeClass('dim');                                       

                                        if(sid!='0' && sid!='1')
                                        {
                                            $('#btnpre').removeClass('dim');
                                        }

                                        $('#prestuid').val(sid);
                                        $('#nextstuid').val(sid);
                                           $('#newstuid').val(sid);

                                        if(prestucnt!='0')
                                        {
                                            $('#btnpre').removeClass('dim');
                                        }
                                        else
                                        {
                                              $('#btnpre').addClass('dim');
                                        }

                                        if(nxtstucnt!='0')
                                        {
                                            $('#btnnext').removeClass('dim');
                                        }
                                        else
                                        {
                                             $('#btnnext').addClass('dim');
                                        }
                                    }
                                 
                                    if(hiddval=='1')
                                    {
                                        $('#newstuid').val(1);
                                        fn_nextstudent(dsid,1);
                                    }

                                    if(hiddval=='2')
                                    {
                                        $('#newstuid').val(1);
                                        fn_prestudent(dsid,1);
                                    }

                                    if(hiddval=='3')
                                    {
                                        $('#newstuid').val(1);
                                        fn_closesheet(dsid,1);
                                    }
                                 
                                }
                                else
                                {
                                    $('.lb-content').html("Invalid data So it cannot update");
                                    setTimeout('closeloadingalert()',1000);
                                }
                        },
                    });
                }},
            ]
            });
            $('.ZebraDialog').css({"posiion":"absolute","left":"50%","top":"50%","transform":"translate(-50%,-50%)","width":"598px"});
            closeloadingalert();

        }
            else
            {              

                    if(id=='0'){
                        actionmsg = "Saving";
                        alertmsg = "Data Sheet has been Created Successfully"; 
                    }
        else{
                        actionmsg = "Updating";
                        alertmsg = "Data Sheet has been updated Successfully"; 
                    }
                    var dataparam = "oper=savedatasheet&sheetid="+id+"&term="+term+"&state="+state+"&year="+year+"&classname="+classname+"&datasheetname="+datasheetname+"&detail="+detail+"&headercount="+headercount+"&tracklen="+tracklen+"&co2cart="+co2cart+"&txttracksuface="+txttracksuface+"&dashid="+dashid+"&newstucount="+newstucount;                  
                    $.ajax({
                        url : "sos/data/sos-data-ajax.php",
                        data: dataparam,
                        type: "POST",
                        beforeSend: function(){
                               showloadingalert(actionmsg+", please wait.");	
                        },
                        success: function (data) {
                            var response=trim(data);
                            var output=response.split('~');
                            var status=output[0];
                            var dsid=output[1];
                            var sid=output[2];
                            var prestucnt=output[3];
                            var nxtstucnt=output[4];                             
                                if(status=="success") //Works if the data saved in db
                                {
                                    $('.lb-content').html(alertmsg);
                                    setTimeout('closeloadingalert()',500);                                 
                                    if(hiddval=='0')
                                    {
                                        $('#dashid').val(dsid);
                                        $('#currentstuid').val(sid);                                      
                                            $('#btndel').removeClass('dim');                                       

                                        if(sid!='0' && sid!='1')
                                        {
                                            $('#btnpre').removeClass('dim');
                                        }

                                        $('#prestuid').val(sid);
                                        $('#nextstuid').val(sid);
                                           $('#newstuid').val(sid);

                                        if(prestucnt!='0')
                                        {
                                            $('#btnpre').removeClass('dim');
                                        }
                                        else
                                        {
                                              $('#btnpre').addClass('dim');
                                        }

                                        if(nxtstucnt!='0')
                                        {
                                            $('#btnnext').removeClass('dim');
                                        }
                                        else
                                        {
                                             $('#btnnext').addClass('dim');
                                        }
                                    }
                                 
                                    if(hiddval=='1')
                                    {
                                        $('#newstuid').val(1);
                                        fn_nextstudent(dsid,1);
                                    }

                                    if(hiddval=='2')
                                    {
                                        $('#newstuid').val(1);
                                        fn_prestudent(dsid,1);
                                    }

                                    if(hiddval=='3')
                                    {
                                        $('#newstuid').val(1);
                                        fn_closesheet(dsid,1);
                                    }

                                }
                                else
                                {
                                    $('.lb-content').html("Invalid data So it cannot update");
                                    setTimeout('closeloadingalert()',1000);
                                }
                        },
                    });               
            }
        }
        else{            
            if(id=='0'){
                actionmsg = "Saving";
                alertmsg = "Data Sheet has been Created Successfully"; 
            }
            else{
                actionmsg = "Updating";
                alertmsg = "Data Sheet has been updated Successfully"; 
            }

            var dataparam = "oper=savedatasheet&sheetid="+id+"&term="+term+"&state="+state+"&year="+year+"&classname="+classname+"&datasheetname="+datasheetname+"&detail="+detail+"&headercount="+headercount+"&tracklen="+tracklen+"&co2cart="+co2cart+"&txttracksuface="+txttracksuface+"&dashid="+dashid+"&newstucount="+newstucount; 
            $.ajax({
                url : "sos/data/sos-data-ajax.php",
                data: dataparam,
                type: "POST",
                beforeSend: function(){
                      showloadingalert(actionmsg+", please wait.");	
                },
                success: function (data) {
                    var response=trim(data);
                    var output=response.split('~');
                    var status=output[0];
                    var dsid=output[1];
                    var sid=output[2];
                    var prestucnt=output[3];
                    var nxtstucnt=output[4];                        
                        if(status=="success") //Works if the data saved in db
                        {
                            $('.lb-content').html(alertmsg);
                            setTimeout('closeloadingalert()',500);                          
                            if(hiddval=='0')
                            {
                            $('#dashid').val(dsid);
                            $('#currentstuid').val(sid);                          
                                $('#btndel').removeClass('dim');                           

                                if(sid!='0' && sid!='1')
                                {
                                $('#btnpre').removeClass('dim');
                            }

                            $('#prestuid').val(sid);
                            $('#nextstuid').val(sid);
                            $('#newstuid').val(sid);
                            
                            if(prestucnt!='0')
                            {
                                $('#btnpre').removeClass('dim');
                            }
                                else
                                {
                                  $('#btnpre').addClass('dim');
                            }

                            if(nxtstucnt!='0')
                            {
                                $('#btnnext').removeClass('dim');
                            }
                                else
                                {
                                 $('#btnnext').addClass('dim');
                            }
                        }

                            if(hiddval=='1')
                            {
                                $('#newstuid').val(1);
                                fn_nextstudent(dsid,1);
                            }

                            if(hiddval=='2')
                            {
                                $('#newstuid').val(1);
                                fn_prestudent(dsid,1);
                            }

                            if(hiddval=='3')
                            {
                                $('#newstuid').val(1);
                                fn_closesheet(dsid,1);
                            }
                        }
                        else
                        {
                            $('.lb-content').html("Invalid data So it cannot update");
                            setTimeout('closeloadingalert()',1000);
                        }
                },
            });
        }
    }
}


function fn_deletedatasheet(id)
{
	$.Zebra_Dialog('Are you sure you want to delete?',
	{
		'type': 'confirmation',
		'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {
				
				var dataparam = "oper=deletedatasheet"+"&dataid="+id;
				$.ajax({
                                        type:"POST",
                                        url : "sos/data/sos-data-ajax.php",
					data: dataparam,
					beforeSend: function(){
						showloadingalert("Checking, please wait.");	
					},	
					success: function (ajaxdata) {	
						if(ajaxdata=="success") //Works if Module Deleted
						{
							$('.lb-content').html("Data Sheet has been Deleted Successfully");
							setTimeout('closeloadingalert()',500);
							setTimeout('removesections("#sos");',1000);
                                                        setTimeout('showpages("sos-data","sos/data/sos-data.php");',1000);
						}
						else
						{
							$('.lb-content').html("Deleting has been Failed"); //Works if the process fails in query.
							setTimeout('closeloadingalert()',500);
						}
                                                
					},
				});
			}
		}]
	});
}



/*----
    fn_importstudents()
	Function to shoe the import students page
----*/
function fn_importstudents(path,dataid)
{
    if($("#dataforms").validate().form()) //Validates the Module Form
    {
        var datasheetname=$('#txtdatasheetname').val();
        var term=$('#term').val();
        var state=$('#ddlstate').val();
        var year=$('#year').val();
        var classname=$('#classname').val();
        var tracklen=$('#tracklen').val();
        var co2cart=$('#co2').val();
        var txttracksuface=$('#txttracksuface').val();
        
        var dashid=$('#dashid').val();
        
        if(state==""){
            showloadingalert("Please Select State.");
            setTimeout('closeloadingalert()',1000);
            return false;  
        }
        if(year==""){
            showloadingalert("Please Select Year.");
            setTimeout('closeloadingalert()',1000);
            return false;  
        }
        if(classname==""){
            showloadingalert("Please Select Classname.");
            setTimeout('closeloadingalert()',1000);
            return false;  
        }
        if(tracklen==""){
            showloadingalert("Please Select Track Length.");
            setTimeout('closeloadingalert()',1000);
            return false;  
        }
        if(co2cart==""){
            showloadingalert("Please Select CO2 Cartridge.");
            setTimeout('closeloadingalert()',1000);
            return false;  
        }    
  
	
	var actionmsg ="Saving Students";
	var shl=$('#hidshl').val(); 
        var flagg=$('#import').val();
     
	var dataparam="oper=importstudents&path="+path+"&flagg="+flagg+"&term="+term+"&state="+state+"&year="+year+"&classname="+classname+"&datasheetname="+datasheetname+"&tracklen="+tracklen+"&co2cart="+co2cart+"&txttracksuface="+txttracksuface+"&dashid="+dashid;
	$.ajax({
		type: 'post',
                url : "sos/data/sos-data-ajax.php",
		data: dataparam,
		beforeSend: function(){
			showloadingalert(actionmsg+", please wait.");	
		},
		success:function(ajaxdata){
                    var response=trim(ajaxdata);
                    var output=response.split('~');
                    var status=output[0];
                    var dsid=output[1];
                    var stucntid=output[2];
                    var nxtstuid=output[3];
                    var prestucnt=output[4];
                    var nxtstucnt=output[5];                   
                    if(status=="success") //Works if the data saved in db
                    {
                        
                        if(dataid!='0'){
                            
                            $('#dashid').val(dsid);
                            $('#currentstuid').val(nxtstuid);
                            $('#prestuid').val(nxtstuid);
                            $('#nextstuid').val(nxtstuid);
                            $('#newstuid').val(nxtstuid);
                            $('#studentcount').val(stucntid);                         

                            if(nxtstuid!='0')
                            {
                                $('#btndel').removeClass('dim');
                            }

                            if(prestucnt!='0')
                            {
                                $('#btnpre').removeClass('dim');
                            }
                            else{
                                  $('#btnpre').addClass('dim');
                            }

                            if(nxtstucnt!='0')
                            {
                                $('#btnnext').removeClass('dim');
                            }
                            else{
                                 $('#btnnext').addClass('dim');
                            }
                         $('#datasheet').show();                       
                             setTimeout('closeloadingalert()',500);
                        }
                        else{
                            setTimeout('closeloadingalert()',500);
                            setTimeout('removesections("#sos");',500);
                            setTimeout('showpages("sos-data","sos/data/sos-data.php");',500);
                            setTimeout('showpageswithpostmethod("sos-data-actions","sos/data/sos-data-actions.php","id='+dsid+'");',500);
                            setTimeout('showpageswithpostmethod("sos-data-newdata","sos/data/sos-data-newdata.php","id='+dsid+'");',500);   
                        }
                        
                    }
                    else
                    {
                        $('.lb-content').html("Invalid data So it cannot update");
                        setTimeout('closeloadingalert()',1000);
                    }                   
                 }
	});
        
    }

}

/**********Show Imported Student**********/
function fn_showimportstudent(id)
{
	var dataparam = "oper=showimportstudent&sheetid="+id;	
	$.ajax({
		type: 'post',
		url : "sos/data/sos-data-ajax.php",
		data: dataparam,
		beforeSend: function(){                   	
		},
		success:function(data) {              
                  $('#datasheet').hide();
                    $('#importdatasheet').show();
                   
                    $('#importdatasheet').html(data);
                    $('#btndiv').show();
		}
	});
    
    
}
/**********Show Imported Student**********/


/*********Close Sheet Code Start Here***************/
function fn_closesheet(id,updateid)
{
    var newstucount=$('#newstuid').val();
    if(newstucount=='0')
    {
        fn_savesheet(id,3);
    }
    else    
    {
        if(updateid=='0')
        {
              fn_savesheet(id,3);
        }
        else
        {
            setTimeout('removesections("#sos");',500);
            setTimeout('showpages("sos-data","sos/data/sos-data.php");',500);
        }
    }
}
/*********Close Sheet Code End Here***************/


/*********New Student Code Start Here***************/
function fn_newstudent()
{
    for(var l=1;l<=16;l++)
    {
        $('#txt_'+l).val('');
    }
     $('#newstuid').val('0');
    
}
/*********New Student Code End Here***************/   


/*********Previous Student Code Start Here***************/
function fn_prestudent(dsid,updateid)
{
    var newstucount=$('#newstuid').val();
    if(newstucount=='0')
    {
        fn_savesheet(dsid,2);
    }
    else
    {
        if(updateid=='0')
        {
            fn_savesheet(dsid,2);
        }
        else
        {
			var stcount=$('#studentcount').val(); 
			var currentstuid=$('#currentstuid').val();
			var previousstuid=$('#prestuid').val();

			var dashid=$('#dashid').val();

			var dataparam = "oper=prestudent&stcount="+stcount+"&currentstuid="+previousstuid+"&dashid="+dashid;
			$.ajax({
					type:"POST",
					url : "sos/data/sos-data-ajax.php",
					data: dataparam,
					beforeSend: function(){
							showloadingalert("Loading, please wait.");	
					},	
					success: function (ajaxdata) {
						var dsspltdata = ajaxdata.split("~");
						var datavalue=JSON.parse(dsspltdata[0]);
						var j=1;

						for(var i=0;i<=datavalue.length;i++)
						{
						   $('#txt_'+j).val(datavalue[i]);
						   j++;
						}

						setTimeout('closeloadingalert()',500);

						if(dsspltdata[1]==0){
							  $('#btnpre').addClass('dim');
						}

						$('#prestuid').val(dsspltdata[2]);
						$('#nextstuid').val(dsspltdata[2]);
						$('#newstuid').val(dsspltdata[2]);

						if(dsspltdata[2]==0){
							$('#btnnext').addClass('dim');
						}
						else{
							$('#btnnext').removeClass('dim');
						}


						if(dsid!='0'){
							$('#currentstuid').val(dsspltdata[2]);
						}
					},
			});
		}
	}
}
/*********Previous Student Code End Here***************/

/*********Next Student Code Start Here***************/
function fn_nextstudent(dsid,updateid)
{
    var newstucount=$('#newstuid').val();
    if(newstucount=='0')
    {
        fn_savesheet(dsid,1);
    }
    else
    {
		
	 	if(updateid=='0')
        {
            fn_savesheet(dsid,1);
        }
        else
        {
   
			var stcount=$('#studentcount').val(); 
			var currentstuid=$('#currentstuid').val();
			var previousstuid=$('#prestuid').val();
			var dashid=$('#dashid').val();

			var dataparam = "oper=nextstudent&stcount="+stcount+"&currentstuid="+previousstuid+"&dashid="+dashid;			
			$.ajax({
					type:"POST",
					url : "sos/data/sos-data-ajax.php",
					data: dataparam,
					beforeSend: function(){
							showloadingalert("Loading, please wait.");	
					},	
					success: function (ajaxdata) {

						var dsspltdata = ajaxdata.split("~");
						var datavalue=JSON.parse(dsspltdata[0]);
						var j=1;

						for(var i=0;i<=datavalue.length;i++){

						   $('#txt_'+j).val(datavalue[i]);
						   j++;
						}
						setTimeout('closeloadingalert()',500);

						if(dsspltdata[1]==0){
							$('#btnnext').addClass('dim');
						}

						$('#nextstuid').val(dsspltdata[2]);
						$('#prestuid').val(dsspltdata[2]);
						$('#newstuid').val(dsspltdata[2]);

						$('#btnpre').removeClass('dim');

						if(dsid!='0'){
							$('#currentstuid').val(dsspltdata[2]);
						}
					},
			});
		}
	}
}
/*********Next Student Code Start End***************/

/*********Delete Student Code Start Here***************/
function fn_delstudent(dsid)
{
    var currentstuid=$('#currentstuid').val();
    
    $.Zebra_Dialog('Are you sure you want to delete this Student?',
	{
		'type': 'confirmation',
		'buttons': [
                    {caption: 'No', callback: function() { }},
                    {caption: 'Yes', callback: function() {

                        var dataparam = "oper=deletestudent"+"&dataid="+dsid+"&currentstuid="+currentstuid;                      
                        $.ajax({
                                type:"POST",
                                url : "sos/data/sos-data-ajax.php",
                                data: dataparam,
                                beforeSend: function(){
                                     showloadingalert("Checking, please wait.");	
                                },	
                                success: function (ajaxdata) {                                
                                       $('.lb-content').html("Data Sheet Student has been Deleted Successfully");
                                  
                                    var dsspltdata = ajaxdata.split("~");
                                    if(dsspltdata[4]=='2')
                                    {                                    
                                          setTimeout('closeloadingalert()',1000);
                                        var datavalue=JSON.parse(dsspltdata[0]);
                                        var j=1;

                                        for(var i=0;i<=datavalue.length;i++)
                                        {
                                           $('#txt_'+j).val(datavalue[i]);
                                           j++;
                                        }

                                         if(dsspltdata[1]==0){
                                            $('#btnnext').addClass('dim');
                                        }
                                        else{
                                             $('#btnnext').removeClass('dim');
                                        }


                                        if(dsspltdata[3]==0){
                                            $('#btnpre').addClass('dim');
                                        }
                                        else{
                                             $('#btnpre').removeClass('dim');
                                        }


                                        $('#nextstuid').val(dsspltdata[2]);
                                        $('#prestuid').val(dsspltdata[2]);
                                        $('#newstuid').val(dsspltdata[2]);
                                        
                                        $('#currentstuid').val(dsspltdata[2]);

                                    }
                                    else if(dsspltdata[4]=='1')
                                    {                                      
                                          setTimeout('closeloadingalert()',1000);
                                        var datavalue=JSON.parse(dsspltdata[0]);
                                        var j=1;

                                        for(var i=0;i<=datavalue.length;i++)
                                        {
                                           $('#txt_'+j).val(datavalue[i]);
                                           j++;
                                        }

                                        setTimeout('closeloadingalert()',1000);

                                        if(dsspltdata[1]==0){
                                              $('#btnpre').addClass('dim');
                                        }
                                        else{
                                            $('#btnpre').removeClass('dim');
                                        }

                                        $('#prestuid').val(dsspltdata[2]);
                                        $('#nextstuid').val(dsspltdata[2]);
                                        $('#newstuid').val(dsspltdata[2]);
                                        $('#currentstuid').val(dsspltdata[2]);

                                        if(dsspltdata[3]==0){
                                            $('#btnnext').addClass('dim');
                                        }
                                        else{
                                            $('#btnnext').removeClass('dim');
                                        }
                                    }
                                    else
                                    {                                    
                                           setTimeout('closeloadingalert()',1000);
                                        for(var l=1;l<=16;l++)
                                        {
                                            $('#txt_'+l).val('');
                                        }
                                        $('#prestuid').val(0);
                                        $('#nextstuid').val(0);
                                        $('#newstuid').val(0);
                                        $('#currentstuid').val(0);
                                        
                                        $('#btnpre').addClass('dim');
                                        $('#btnnext').addClass('dim');
                                        $('#btndel').addClass('dim');
                                        
                                    }
                                 
                                },
                        });
                    }
		}]
	});
    
}
/*********Delete Student Code End Here***************/
