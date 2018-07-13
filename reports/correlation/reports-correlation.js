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

	if(leftlist=="list5" || leftlist=="list6" && rightlist=="list6" || rightlist=="list5"  )
	{
            fn_showgrades(0,$('#corid').val(),$('#state').val());
         }
    
    if(leftlist=="list21" || leftlist=="list22" && rightlist=="list22" || rightlist=="list21"  )
    {
               $('#taskdiv').show();
               $('#taskdiv1').show();
            var productid = [];
            $("div[id^=list8_]").each(function()
            {
                if($(this).attr("style") != "display: none;") {
                        var pid = $(this).children(":first").attr('id');
                        productid.push((pid));
}
            });
            var reportid=$('#hidrptid').val();
            fn_showtasks(productid,reportid);       
     
    }
    
    if(leftlist=="list23" || leftlist=="list24" && rightlist=="list24" || rightlist=="list23"  )
    {
        
       
             $('#allresources').show();
            var productid = [];
            $("div[id^=list8_]").each(function()
            {
                if($(this).attr("style") != "display: none;") {
                        var pid = $(this).children(":first").attr('id');
                        productid.push((pid));
            }
            });
            var reportid=$('#hidrptid').val();
            fn_showresources(productid,reportid);
        
    }
}
/*----
    fn_movealllistitemsproducts()
	Function to move all products from lest to right and right to left
----*/
function fn_movealllistitemsproducts(leftlist,rightlist,id,courseid)
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
			if($('#hidselecteddropdown').val()==$('#'+rightlist+'_'+courseid).attr('name') || $('#hidselecteddropdown').val()==0  )
			{
			$('#'+leftlist).append($('#'+rightlist+' #'+rightlist+'_'+courseid));
			$('#'+rightlist+'_'+courseid).removeClass('draglinkright').addClass('draglinkleft');
			var temp = $('#'+rightlist+'_'+courseid).attr('id').replace(rightlist,leftlist);					
			var ids='id';
			$('#'+rightlist+'_'+courseid).attr(ids,temp);
			}
		}
	}
        var typeid=courseid.split('_');
    if(typeid[1]==5)
    {
        var productid = [];
        $("div[id^=list8_]").each(function()
        {
            if($(this).attr("style") != "display: none;") {
                    var pid = $(this).children(":first").attr('id');
                    productid.push((pid));
}
        });
        var reportid=$('#hidrptid').val();
        fn_showexpe(productid,reportid);
    }
}
/*----
    fn_movenextstep()
	Function to save correlation setp data and move to next step
	id - correlation report id  0 - new report, 
	stepid - to which step to move.
----*/
function fn_movenextstep(id,stepid)
{	
	var dataparam = "";
	
	if(stepid == 2) {
		
		var sec = '';
		var chkval = 0;
                var flag="false";
		$("input[id^='check']").each(function(index, element) {
			
			if($(this).is(':checked')==true){
				chkval = 1;
                                 flag="true";
			}
			else 
                        {
				chkval = 2;	
			}
			
            if(sec == '') {
				sec = chkval;	
			}
			else {
				sec += ","+chkval;	
			}
        });
		
		if($("#frmbasicinfo").validate().form()){	
			
                        if(flag=="false")
                        {
                            $.Zebra_Dialog("Please Select Show Sections", { 'type': 'information', 'buttons': false, 'auto_close': 2000 });
                            return false;
                        }

			dataparam = "oper=savestep1&rpttitle="+$('#txtrpttitle').val()+"&ownerid="+$('#selectowner').val()+"&prepfor="+$('#txtpreparefor').val()+"&prepon="+$('#txtprepareon').val()+"&rptsytle="+$('#selectrptstyle').val()+"&selectschool="+$('#selectschool').val()+"&sec="+sec+"&rptid="+id;
	
		}
		else
		{
			return false;
		}
	}
	
	
	if(stepid == 3) {		
		var gradeid = [];
		var gradename = "";
		$("div[id^=list8_]").each(function()
		{
			var guid = $(this).attr('id').replace('list8_',''); 
			gradeid.push(guid);
			if(gradename == "") {
				gradename = $('#'+guid).attr("title");			
			}
			else{
				gradename += "~" + $('#'+guid).attr("title");			
			}
		});

		var stdid = [];
		
		$("div[id^=list6_]").each(function()
		{
			var guid = $(this).attr('id').replace('list6_','');
			stdid.push("'"+guid+"'");
			
                        
		});

		               
         
	     if(gradeid=='')
		{
		$.Zebra_Dialog("Please Select Atleast one Grades", { 'type': 'information', 'buttons': false, 'auto_close': 2000 });
				
			return false;
	}
		dataparam = "oper=savestep2&state="+$('#selectstate').val()+"&documentid="+stdid+"&gradeids="+gradeid+"&gradename="+gradename+"&rptid="+id;
	
              
	}
	
	if(stepid == 4) {		
		var productid = [];
		var tagproductid = [];
               
		var gradename = "";
		var selectproducts=$('#hidselectedproducts').val(); 
		var selecttagproducts=$('#hidselectedtagproducts').val(); 
		var show_titletype=$('#showtitle').val();
		var tagpid = $('#form_tags_products').val();
var chktype = $('input:radio[name=types]:checked').val();
		var titletype=$('#selectstate').val();
		
		if(selecttagproducts==undefined)
		{
			var selecttagproducts = '';
		}
		if(selectproducts==undefined)
		{
			var selecttagproducts = '';
		}
		
                /* chktype=='6' for tag products */
                //(chktype);
		if(chktype=='6') {
			
			var remove_selectprod = selectproducts.split(",");
				
			for (var i = 0; i < remove_selectprod.length; ++i) {
	
	 					$('#list8_'+remove_selectprod[i]).remove();
		}
	 				var selectproducts=$('#hidselectedproducts').val(''); 
	 	
	 				$("div[id^=list10_]").each(function()
		{
				if($(this).attr("style") != "display: none;") {
					var pid = $(this).children(":first").attr('id');
					tagproductid.push(escape(pid));
				}
			});
	 				dataparam = "oper=savestep4&tagproductid="+tagproductid+"&rptid="+id+"&tagpid="+tagpid;
                        
		}
		else {

                var remove_selecttagprod = selecttagproducts.split(",");

                for (var i = 0; i < remove_selecttagprod.length; ++i) {

                    $('#list10_' + remove_selecttagprod[i]).remove();
                }
                var selecttagproducts = $('#hidselectedtagproducts').val('');

                $("div[id^=list8_]").each(function()
                {
                    if ($(this).attr("style") != "display: none;") {
                        var pid = $(this).children(":first").attr('id');
                        productid.push((pid));
                    }
                });


                $("div[id^=list22_]").each(function()
                {
                    if ($(this).attr("style") != "display: none;") {
                        var guiddes = $(this).children(":first").attr('id');
                        destids.push(escape(guiddes));
                    }

                });
                $("div[id^=list24_]").each(function()
                {

                    if ($(this).attr("style") != "display: none;") {
                        var guidtask = $(this).children(":first").attr('id');
                        taskids.push(escape(guidtask));
                    }
                });
                $("div[id^=list26_]").each(function()
                {

                    if ($(this).attr("style") != "display: none;") {
                        var guidres = $(this).children(":first").attr('id');
                        resids.push(escape(guidres));
                    }

                });

                dataparam = "oper=savestep4&productid=" + productid  + "&rptid=" + id + "&titletype=" + show_titletype + "&show_titletype=" + show_titletype;
               // alert(dataparam);
              // exit;
            }
		
	}
	$.ajax({
		type: 'POST',
		url: 'reports/correlation/reports-correlation-ajax.php',
		data: dataparam,
		beforeSend: function()
		{
			showloadingalert('Loading, please wait.');
		},
		success:function(data) {		
			closeloadingalert();
			id = data;
			
			if(id != "invalid") {
			
				$('#cbasicinfo').parent().attr('name',id);
				$('#cselectproduct').parent().attr('name',id);
				$('#cselectstandard').parent().attr('name',id);
				$('#cviewreport').parent().attr('name',id);
				
				removesections('#reports-correlation-steps');
				
				if(stepid == 2){
					setTimeout('showpageswithpostmethod("reports-correlation-select_standard","reports/correlation/reports-correlation-select_standard.php","id='+id+'");',500);
				}
				else if(stepid == 3) {
					setTimeout('showpageswithpostmethod("reports-correlation-select_product","reports/correlation/reports-correlation-select_product.php","id='+id+'");',500);	
				}
				else if(stepid == 4) {
                                        setTimeout('showpageswithpostmethod("correlationreport","reports/correlation/correlationreport.php","id='+id+'");',500);
                                        $("#reports-correlation").load("reports/correlation/reports-correlation.php #reports-correlation > *", function(){
                                                           });
                                        var oper="correlationreport";
                                        var filename="correlationreport_"+new Date().getTime();
										ajaxloadingalert('Loading, please wait.');
										setTimeout('showpageswithpostmethod("reports-correlation-view_report","reports/correlation/reports-correlation-view_report.php","id='+id+'&oper='+oper+'&filename='+filename+'");',500);	
                                        
				}
			}
			else {
				showloadingalert("Duplicate Report Name");	
				setTimeout('closeloadingalert()',2000);
				
				return false;
			}
		}
	});
}

/*----
    fn_showdocuments()
	Function to load documents from AB API
	stid -> State Id
----*/
function fn_showdocuments(stid,rptid,stdid)
{
	var dataparam = "oper=showdocuments&stid="+stid+"&rptid="+rptid;
	$.ajax({
		type: 'post',
		url: 'reports/correlation/reports-correlation-ajax.php',
		data: dataparam,
		success:function(data) {		
			$('#dpdocuments').html(data);//Used to load the student details in the dropdown
                        fn_showgrades(stdid,rptid,stid);
		}
	});
}

/*----
    fn_showgrades()
	Function to load grades from AB API
	stid -> State Id
----*/
function fn_showgrades(stdid,rptid,stid)
{
	

		var stdid = [];
		
		$("div[id^=list6_]").each(function()
		{
			var guid = $(this).attr('id').replace('list6_',''); 
			stdid.push("'"+guid+"'");
		});

	if(stdid.length==0 || stdid=='')
	{
		stdid="'"+stdid+"'";
	}

	var dataparam = "oper=showgrades&stdid="+stdid+"&rptid="+rptid+"&stid="+stid;
	$.ajax({
		type: 'post',
		url: 'reports/correlation/reports-correlation-ajax.php',
		data: dataparam,
		success:function(data) {	
		    $('#btnstep2').removeClass('dim');	
			$('#divdocgrades').html(data);//Used to load the student details in the dropdown
		}
	});
}
/*----
    fn_saveselect()
	Function to save the selected products
----*/
function fn_showproducts(type,rptid)
{
    //alert("type"+type);
    $('#alldestinations').show();

    var selectproducts=$('#hidselectedproducts').val(); 
    
    if(type!=0)
    {
        $('#showtitle').val(type);
    }
    

    var dataparam = "oper=showproducts&type="+type+"&rptid="+rptid+"&selectproducts="+selectproducts;

    //  alert(dataparam);
    $.ajax({
        type: 'post',
        url: 'reports/correlation/reports-correlation-ajax.php',
        data: dataparam,
        success:function(data) {		
                $('#loadproducts').html(data);//Used to load the student details in the dropdown
        }
    });
}
/*----
    fn_saveselect()
	Function to save the selected products
----*/
function fn_saveselect(){
	 $('#hidselectedproducts').val(''); 
	 var productid = [];
	 $("div[id^=list8_]").each(function()
	 {
			var pid = $(this).attr('id').replace('list8_','');
			productid.push(pid);
		});
	 $('#hidselectedproducts').val(productid); 
 }
/*----
    fn_saveselect()
	Function to save the selected tag products
----*/
function fn_saveselecttag(){
	 $('#hidselectedtagproducts').val(''); 
	 var tagproductid = [];
	 $("div[id^=list10_]").each(function()
	 {
			var pid = $(this).attr('id').replace('list10_','');
			tagproductid.push(pid);
		});
	 
	 $('#hidselectedtagproducts').val(tagproductid); 
 }
 function fn_removeselecttag(id){
 
	 var pid = $('#form_tags_products').val();
	
	 var dataparam = "oper=removerightroducts&rptid="+id+"&remtagproducts="+pid;
	 $.ajax({
                type: 'post',
                url: 'reports/correlation/reports-correlation-ajax.php',
               
                data: dataparam,
                success:function(data) {
                
                  var parsed=JSON.parse(data);
                
                  var ret_tagproductid = [];
                  for (var i = 0; i < parsed.length; ++i) {
                 	ret_tagproductid.push(parsed[i]['id']+'_'+parsed[i]['type']);
                  }
                  var tagproductid = [];
				 $("div[id^=list10_]").each(function()
	 			{
					var pid = $(this).attr('id').replace('list10_','');
					tagproductid.push(pid);
				});
				
				 var selectdifferentset1 = [];
				 var i = 0;
				 jQuery.grep(tagproductid, function(el) {

    			 if (jQuery.inArray(el, ret_tagproductid) == -1) selectdifferentset1.push(el);
            	    i++;
				});

				
                 	for (var i = 0; i < selectdifferentset1.length; ++i) {

	 					$('#list10_'+selectdifferentset1[i]).remove();
	 				}
                }
            }); 

 }

/*
 * get unique value for products and selected products
  */
function fn_remloadedprod()
{

	var productid = [];
    $("div[id^=list9_]").each(function()
    {
        var pid = $(this).attr('id').replace('list9_','');
        productid.push(pid);
    });
    var tagproductid = [];
	$("div[id^=list10_]").each(function()
	{
			var pid = $(this).attr('id').replace('list10_','');
			tagproductid.push(pid);
	});

	 if(tagproductid != '')
	 {
	 var selectdifferentset1 = [];
				 var i = 0;
				 jQuery.grep(tagproductid, function(el) {

    			 if (jQuery.inArray(el, productid) != -1) selectdifferentset1.push(el);
            	    i++;
				});
				
				 for (var i = 0; i < selectdifferentset1.length; ++i) {

	 					$('#list9_'+selectdifferentset1[i]).remove();
	 				}
	 			}

}

/*----
    fn_deletereport()
	Function to delete the grade details
----*/	 
function fn_deletereport(id)
{
	
	$.Zebra_Dialog('Are you sure you want to delete?',
{
			'type': 'confirmation',
			'buttons': [
			{caption: 'No', callback: function() { }},
			{caption: 'Yes', callback: function() {
			var dataparam = "oper=deletereport&id="+id;
			$.ajax({
				type: 'post',
				url: 'reports/correlation/reports-correlation-ajax.php',
				data: dataparam,
				success:function(data) {		
					setTimeout('removesections("#reports");',500);
					setTimeout('showpages("reports-correlation","reports/correlation/reports-correlation.php")',500);
					
				}
			});
			
			}},
			]
		});

	

}
/*----
    fn_validategrade()
	Function to validate the grade details
----*/
function fn_validategrade()
{
   var gradeid = [];
		var gradename = "";
		$("div[id^=list8_]").each(function()
		{
			var guid = $(this).attr('id').replace('list8_',''); 
			gradeid.push(guid);
		});
		if(gradeid!='')
		{
			$('#btnstep2').removeClass('dim');
		}
		else
		{
			$('#btnstep2').addClass('dim');
		}
}
/*----
    fn_validateproducts()
	Function to validate the products details
----*/
function fn_validateproducts()
{
 var selectproducts=$('#hidselectedproducts').val(); 
		if(selectproducts!='')
		{
			$('#btnstep3').removeClass('dim');
		}
		else
		{
			$('#btnstep3').addClass('dim');
$('#tag').removeClass('dim');//changes
		}
}
/*----
    fn_validateproducts()
	Function to validate the products details
----*/
function fn_validateproductstag()
{
 var selectproducts=$('#hidselectedtagproducts').val(); 
		if(selectproducts!='')
		{
			$('#tagbtnstep3').removeClass('dim');
		}
		else
		{
			$('#tagbtnstep3').addClass('dim');
		}
}


//request Correlation proceess start here

function fn_movealllistitemsrequest(leftlist,rightlist,id,courseid)
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
}


function fn_movealllistitems1(leftlist,rightlist,id,courseid)
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

	if(leftlist=="list55" || leftlist=="list66" && rightlist=="list66" || rightlist=="list55"  )
	{
            fn_showgrades1(0,$('#corid').val(),$('#state').val());
         }
}


function fn_showdocuments1(stid,rptid)
{
	var dataparam = "oper=showdocuments1&stid="+stid+"&rptid="+rptid;

	$.ajax({
		type: 'post',
		url: 'reports/correlation/reports-correlation-ajax.php',
		data: dataparam,
		success:function(data) {
                        $('#dpdocuments1').show();
			$('#dpdocuments1').html(data);//Used to load the student details in the dropdown
                        $('#divdocgrades1').show();
		}
	});
}

function fn_showgrades1(stdid,rptid,stid)
{
	

		var stdid = [];
		
		$("div[id^=list66_]").each(function()
		{
			var guid = $(this).attr('id').replace('list66_',''); 
			stdid.push("'"+guid+"'");
		});
	if(stdid.length==0)
	{
		stdid="'"+stdid+"'";
	}

	var dataparam = "oper=showgrades1&stdid="+stdid+"&rptid="+rptid+"&stid="+stid;
	$.ajax({
		type: 'post',
		url: 'reports/correlation/reports-correlation-ajax.php',
		data: dataparam,
		success:function(data) {	
		    $('#btnstep2').removeClass('dim');	
			$('#divdocgrades1').html(data);//Used to load the student details in the dropdown
		}
	});
}

function fn_showproducts1(type,rptid)
{
	
	var selectproducts=$('#hidselectedproducts').val(); 
	var dataparam = "oper=showproducts1&type="+type+"&rptid="+rptid+"&selectproducts="+selectproducts;
	$.ajax({
		type: 'post',
		url: 'reports/correlation/reports-correlation-ajax.php',
		data: dataparam,
		success:function(data) {		
			$('#loadproducts1').html(data);//Used to load the student details in the dropdown
		}
	});
}

function fn_showexpe(expid,rptid){
     var expids = [];
		
        $("div[id^=list8_]").each(function()
        {
                var guid = $(this).attr('id').replace('list8_',''); 
                expids.push(guid);
                
        });
    var selectdestinations=$('#hidselecteddestinations').val(); 
       var dataparam = "oper=showdestination&expid="+expids+"&reportid="+rptid+"&selectdestinations="+selectdestinations;
	$.ajax({
		type: 'post',
		url: 'reports/correlation/reports-correlation-ajax.php',
		data: dataparam,
		success:function(data) {
                    $('#destinationdiv').show();
                    $('#destinationdiv1').show();
                    $('#destinationdiv').html(data);
		}
	});
    
}

function fn_showtasks(expid,rptid)
{
    $('#alltasks').hide();
        var destids = [];
        var selecttasks=$('#hidselectedtasks').val(); 
        $("div[id^=list22_]").each(function()
        {
                var guid = $(this).attr('id').replace('list22_',''); 
                destids.push(guid);
                
        });
      
	var dataparam = "oper=showtasks&destids="+destids+"&reportid="+rptid+"&expid="+expid+"&selecttasks="+selecttasks;
	$.ajax({
		type: 'post',
		url: 'reports/correlation/reports-correlation-ajax.php',
		data: dataparam,
		success:function(data) {
                       if(data!=''){
                      $('#alltasks').show();
                       }
                        $('#taskdiv').show();
                        $('#taskdiv1').show();
			$('#taskdiv').html(data);//Used to load the student details in the dropdown
                        if($('#title').val() == 5)  {
                            fn_showresources(expid,rptid);
                        }
		}
	});
}

function fn_showresources(expid,rptid)
{
     
        var taskids = [];
        var selectres=$('#hidselectedresources').val(); 
		
        $("div[id^=list24_]").each(function()
        {
                var guid = $(this).attr('id').replace('list24_',''); 
                taskids.push(guid);
                
        });
    
	var dataparam = "oper=showresources&taskids="+taskids+"&reportid="+rptid+"&expid="+expid+"&selectres="+selectres;
	$.ajax({
		type: 'post',
		url: 'reports/correlation/reports-correlation-ajax.php',
		data: dataparam,
		success:function(data) {
                
              if(data!=''){
                      $('#allresources').show();
                       }
                    $('#resourcediv').show();
                    $('#resourcediv1').show();
                    $('#resourcediv').html(data);
		}
	});
}
function fn_request(id)
{
        var requestcomments = '';
      
        
			requestcomments = encodeURIComponent(tinymce.get('requestcomments').getContent().replace(/tiny_mce\//g,""));
			$('#requestcomments').html('');		
                      
        var list66=[];
        $("div[id^=list66_]").each(function()
        {
         list66.push($(this).attr('id').replace('list66_',''));
        });
         var list18=[];
        $("div[id^=list18_]").each(function()
        {
         list18.push($(this).attr('id').replace('list18_',''));
        }); 
        var list21=[];
        $("div[id^=list21_]").each(function()
        {
         list21.push($(this).attr('id').replace('list21_',''));
        });
		

        if(list66=='')
        {
        $.Zebra_Dialog("Please Select Atleast one Documents", { 'type': 'information', 'buttons': false, 'auto_close': 2000 });

        return false;
        }

        if(list18=='')
        {
        $.Zebra_Dialog("Please Select Atleast one Grades", { 'type': 'information', 'buttons': false, 'auto_close': 2000 });

        return false;
        }

        if(list21=='')
        {
        $.Zebra_Dialog("Please Select Atleast one Products", { 'type': 'information', 'buttons': false, 'auto_close': 2000 });

        return false;
        }

	if($("#frmrequest").validate().form()){	
            var dataparam ="oper=sendmail"+"&id="+id+"&requestcomments="+requestcomments+"&requestdate="+$('#requestdate').val()+"&rid="+$('#rid').val()+"&selectstate="+$('#selectstate').val()+"&list66="+list66+"&list18="+list18+"&list21="+list21;
		
            if($('#id').val() != 'undefined' && $('#id').val() != '0'){
                    actionmsg = "Sending";
                    alertmsg = "Mail has been sent successfully"; 
            }
            else {

            	actionmsg = "Sending";
                    alertmsg = "Mail sending failed "; 
                    
            }
        $.ajax({
			type: 'POST',
			url: 'reports/correlation/reports-correlation-ajax.php',
			data: dataparam,		
			beforeSend: function(){
				showloadingalert(actionmsg+", please wait...");	
			},
			                                
                    
                    success:function(data) {
				if(data=='success'){			
					$('.lb-content').html(alertmsg);
					setTimeout('closeloadingalert()',1000);
					setTimeout('removesections("#reports");',500);
					setTimeout('showpages("reports-correlation","reports/correlation/reports-correlation.php");',500);
				}
				else if(data=="fail")
				{
					$('.lb-content').html("Incorrect Data");
					setTimeout('closeloadingalert()',1000);
				}
			}
                                    
		});
	}
	
}
/*correlation page function*/
function fn_correlation(id)
{
setTimeout('removesections("#reports-correlation-request");',500);
	ajaxloadingalert('Loading, please wait.');
setTimeout('showpageswithpostmethod("reports-correlation-request","reports/correlation/reports-correlation-request.php","id='+id+'")',500);
}


//request Correlation proceess End here

/*----
    fn_viewpdf()
	Function to view pdf reports
----*/
function fn_viewpdf(id)
{

	setTimeout('removesections("#reports-correlation-actions");',500);
	ajaxloadingalert('Loading, please wait.');
	setTimeout('showpageswithpostmethod("reports-correlation-view_report","reports/correlation/reports-correlation-view_report.php","id='+id+'")',500);
}
