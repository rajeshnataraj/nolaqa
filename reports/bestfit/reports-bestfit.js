/*----
    fn_movealllistitems()
	Function to move all items from left to right and right to left
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
}
/*----
    fn_movealllistitemsproducts()
	Function to move all products from left to right and right to left
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
            $('#'+leftlist).append($('#'+rightlist+' #'+rightlist+'_'+courseid));
            $('#'+rightlist+'_'+courseid).removeClass('draglinkright').addClass('draglinkleft');
            if(rightlist=='list8')
            {
                $('#list9_'+courseid).remove();
                $('#list10_'+courseid).remove();
            }            
            var temp = $('#'+rightlist+'_'+courseid).attr('id').replace(rightlist,leftlist);					
            var ids='id';
            $('#'+rightlist+'_'+courseid).attr(ids,temp);
            
        }
    }
	if(leftlist=='list7' || leftlist=='list8' || rightlist=='list7' || rightlist=='list8')
        {	
		if($('input:checkbox[name=check_1]').is(':checked'))
                {
			 
			 $("#check").prop('checked', false);
			 $('#list10_'+courseid).val('');
			 $('#requiredproducts').hide();
			 $('#trecomm').val('');
			$('#maxrecomm').val('');
			$('#totcombi').val('N/A');
		}
		else
                {
                    $('#trecomm').val('');
                    $('#maxrecomm').val('');
                    $('#totcombi').val('N/A');
                }
        }
	if(leftlist=='list9' || leftlist=='list10' || rightlist=='list9' || rightlist=='list10')
	{
                    $('#trecomm').val('');
                    $('#maxrecomm').val('');
                    $('#totcombi').val('N/A');
                }
	if(rightlist=='list8')
            {
                $('#list9_'+courseid).remove();
                $('#list10_'+courseid).remove();
            }
            
            
            if(leftlist=='list11' || leftlist=='list12' || rightlist=='list11' || rightlist=='list12')
        {	
		if($('input:checkbox[name=check_2]').is(':checked'))
                                            {
		
                                                    $("#check2").prop('checked', false);
                                                    $('#list14_'+courseid).val('');
                                                    $('#tagrequiredproducts').hide();
                                                    $('#tagtrecomm').val('');
                                                   $('#tagmaxrecomm').val('');
                                                   $('#tagtotcombi').val('N/A');
}
		else
                {
                    $('#tagtrecomm').val('');
                    $('#tagmaxrecomm').val('');
                    $('#tagtotcombi').val('N/A');
                }
        }
	if(leftlist=='list13' || leftlist=='list14' || rightlist=='list13' || rightlist=='list14')
	{

                    $('#tagtrecomm').val('');
                    $('#tagmaxrecomm').val('');
                    $('#tagtotcombi').val('N/A');
                    }
                    if(rightlist=='list12')
            {
                $('#list13_'+courseid).remove();
                $('#list14_'+courseid).remove();
            }
}

/*----
    fn_movenextstep()
	Function to save bestfit setp data and move to next step
	id - bestfit report id  0 - new report, 
	stepid - to which step to move.
----*/
function fn_movenextstep(id,stepid)
{	
	var dataparam = "";
	
	if(stepid == 1) {
		
		var sec = '';
		var chkval = 0;
        var flag="false";
		$("input[id^='check']").each(function(index, element) {			
			if($(this).is(':checked')==true){
				chkval = 1;
                flag="true";
			}
			else if($(this).is(':checked')==false) {
				chkval = 2;	
			}
                        
                 if(sec == '') 
                  {
                    sec = chkval;	
                      }
                else 
                {
                    sec += ","+chkval;	
                    }
                 });
                 
                var gradeid = [];
	        var gradename = "";
                
		$("div[id^=list8_]").each(function()
		{
			var guid = $(this).attr('id').replace('list8_',''); 
			gradeid.push(guid);
                        
			if(gradename == "") 
                        {
			   gradename = $('#'+guid).html();			
			}
			else
                        {
			   gradename += "~" + $('#'+guid).html();			
			} 
		});
                
/* For multiple select standard */
                
                var stdid = [];
		
		$("div[id^=list6_]").each(function()
		{
			var guid = $(this).attr('id').replace('list6_','');
			stdid.push("'"+guid+"'");
                        
		});
                
		
		if($("#frmbasicinfo").validate().form())
                {
                    
                     if(flag=="false")
                    {
                        $.Zebra_Dialog("Please Select Show Sections", { 'type': 'information', 'buttons': false, 'auto_close': 2000 });
                        return false;
                    }
                    
                     if(stdid=='')
                     {
                        $.Zebra_Dialog("Please Select Atleast one documents", { 'type': 'information', 'buttons': false, 'auto_close': 2000 });
                        return false;
                    }
                    
                   if(gradeid=='')
                   {
                        $.Zebra_Dialog("Please Select Atleast one Grades", { 'type': 'information', 'buttons': false, 'auto_close': 2000 });
                        return false;
                   }
		 dataparam = "oper=savestep1&rpttitle="+$('#txtrpttitle').val()+"&ownerid="+$('#selectowner').val()+"&prepfor="+$('#txtpreparefor').val()+"&prepon="+$('#txtprepareon').val()
                                +"&rptsytle="+$('#selectrptstyle').val()+"&selectschool="+$('#selectschool').val()+"&sec="+sec
                                +"&state="+$('#selectstate').val()+"&documentid="+stdid+"&gradeids="+gradeid+"&gradename="+gradename+"&rptid="+id;                              
		}
		else
		{
			return false;
		}
	}
        
	if(stepid == 2) {
				var combi='';
				var newcom='';
				var productid = [];
                                var tagproductid = [];
                                var reqtagproductid=[];
				var reqproductid= [];
				var gradename = "";
                var notitrecomm=$('#trecomm').val();
                var maxrecom=$('#maxrecomm').val();
                var totcombi=$('#totcombi').val();
                var combi=$('#jsonval').val();
				var newcom=$('#newcombi').val();
                var reqcombi=$('#reqcombi').val();
                var tagpid = $('#form_tags_products').val();
                var chktype = $('input:radio[name=types]:checked').val();
                var notagrecomm=$('#tagtrecomm').val();
                var maxtagrecom=$('#tagmaxrecomm').val();
                var tottagcombi=$('#tagtotcombi').val();
				var show_titletype=$('#showtitle').val();                
          
               if($('input:checkbox[name=check_1]').is(':checked') == true)
                   {
                       var truchk = 1;
                   }
                   else if($('input:checkbox[name=check_1]').is(':checked') == false)
                   {
                       var truchk = 0;
                   }                 
                                   
                  if($('input:checkbox[name=check_2]').is(':checked') == true)
                   {
                       var tagtruchk = 1;
                   }
                   else if($('input:checkbox[name=check_2]').is(':checked') == false)
                   {
                       var tagtruchk = 0;
                   }
                    
                                   
                if($('#trecomm').val() == '' && $('#tagtrecomm').val() == ''){
                    
                    $.Zebra_Dialog("Please enter a value for</br>No of Title in each recommendation.", { 'type': 'information'});
					
                  return false;
                  }
                  
                if($('#maxrecomm').val() == '' && $('#tagmaxrecomm').val() == ''){
                    
                  $.Zebra_Dialog("Please enter a value for</br>Max no of recommendation .", { 'type': 'information'});
               
                return false;
                }              
                
		if(combi!='')
		{
                    if(chktype == '5') {
			$("div[id^=list8_]").each(function()
			{
                    if($(this).attr("style") != "display: none;") {
					var pid = $(this).children(":first").attr('id');
					productid.push(escape(pid));
                                    }
				
			});                     
                $("div[id^=list10_]").each(function()
				{
                    if($(this).attr("style") != "display: none;") {
                    var pid = $(this).children(":first").attr('id');
                    reqproductid.push(escape(pid));
                    }
                });
                tagproductid='';
                reqtagproductid = '';
            }else {
                $("div[id^=list12_]").each(function()
			{
                    if($(this).attr("style") != "display: none;") {
					var pid = $(this).children(":first").attr('id');
					tagproductid.push(escape(pid));
                                    }
                
			});
                $("div[id^=list14_]").each(function()
                {
            if($(this).attr("style") != "display: none;") {
                                var pid = $(this).children(":first").attr('id');
                                reqtagproductid.push(escape(pid));
                            }

                });
                 productid='';
                reqproductid = '';
            }    
                
                var cntreq_prod = reqproductid.length;               
				if($('input:checkbox[name=check_1]').is(':checked'))
                {		 
			if(reqproductid=='')
					{
					$.Zebra_Dialog("Please Select Atleast one products", { 'type': 'information'});
							
						return false;
					}					 
				}
                             var cnttagreq_prod = reqtagproductid.length;
                
                if($('input:checkbox[name=check_2]').is(':checked'))
                {		 
	if(reqtagproductid=='')
                                    {
                                    $.Zebra_Dialog("Please Select Atleast one products", { 'type': 'information'});

                                            return false;
                                    }					 
                            }
                                if(productid !='' || reqproductid!='' )
                                {
		dataparam = "oper=savestep2&productid="+productid+"&rptid="+id+"&reqproductid="+reqproductid
                                +"&notitrecomm="+notitrecomm+"&maxrecom="+maxrecom+"&totcombi="+totcombi+"&cntreq_prod="+cntreq_prod+"&truchk="+truchk+"&show_titletype="+show_titletype;
                                
                                }
                                else if(tagproductid!='' || reqtagproductid!='')
                                {
                                    dataparam = "oper=savestep2&tagproductid="+tagproductid+"&rptid="+id+"&tagreqproductid="+reqtagproductid
                             +"&notitrecomm="+notagrecomm+"&maxrecom="+maxtagrecom+"&totcombi="+tottagcombi+"&cntreq_prod="+cnttagreq_prod+"&truchk="+tagtruchk+"&tagpid="+tagpid;
                                }
		
        }
       }
       if(stepid == 3)
       {
           /*generate page*/
                 var selcombi=$('#selcombi').val();                
       }
	$.ajax({
		type: 'POST',
		url: 'reports/bestfit/reports-bestfit-ajax.php',
		data: dataparam,
		beforeSend: function()
		{
			showloadingalert('Loading, please wait.');
		},
		success:function(data) {                 
			closeloadingalert();
			id = trim(data);
                        
			if(trim(id) != "invalid") {
			
				$('#bbasicstandardinfo').parent().attr('name',id);
				$('#bselectproduct').parent().attr('name',id);
				$('#bgenerate').parent().attr('name',id);
				$('#bviewreport').parent().attr('name',id);
				
				removesections('#reports-bestfit-steps');
				
				if(stepid == 1){
					setTimeout('showpageswithpostmethod("reports-bestfit-select_product","reports/bestfit/reports-bestfit-select_product.php","id='+id+'");',500);
                                        
				}
				else if(stepid == 2) {				
					
					ajaxloadingalert('Loading, please wait.');
					setTimeout('showpageswithpostmethod("reports-bestfit-generate_report","reports/correlation/reports-bestfit-generate_report.php","id='+id+'");',500);
                                }
				else if(stepid == 3) {					
                                        ajaxloadingalert('Loading, please wait.');
                                        setTimeout('showpageswithpostmethod("reports-bestfit-view_report","reports/bestfit/reports-bestfit-view_report.php","id='+id+'");',500);
                                      
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


function fn_totcombival(id,stepid){

				var totcombival=$('#totcombi').val();

			if(parseInt(totcombival) > 10000){

			 $.Zebra_Dialog("Total no of combinations must be</br>less than 10,000.</br>", { 'type': 'information'});
					
		          return false;

			}
else {
		fn_movenextstep(id,stepid);

}
			
}

/*----
    fn_viewreport()
	Function to generate view report

----*/
function fn_viewreport(selecombi,id,maxrec,checbox,notitles,totcombi,docid)
		{
		ajaxloadingalert('Loading, please wait.');
removesections('#reports-bestfit-generate_report');
                oper="bestfitreport";
                filename="bestfitreport_"+new Date().getTime();
		showpageswithpostmethod("reports-bestfit-view_report","reports/bestfit/reports-bestfit-view_report.php","id="+selecombi+'~'+id+'~'+maxrec+'~'+checbox+'~'+notitles+'~'+totcombi+'~'+docid+'&oper='+oper+'&filename='+filename);
		
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
		url: 'reports/bestfit/reports-bestfit-ajax.php',
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
		url: 'reports/bestfit/reports-bestfit-ajax.php',
		data: dataparam,
		success:function(data) {
                   $('#btnstep1').removeClass('dim');	
			$('#divdocgrades').html(data);//Used to load the student details in the dropdown
		}
	});
}
/*----
    fn_showproducts()
	Function to show the selected products
----*/
function fn_showproducts(type,rptid)
{
	
	var selectproducts=$('#hidlist8').val();
    
        var dataparam = "oper=showproducts&type="+type+"&rptid="+rptid+"&selectproducts="+selectproducts;
       
	$.ajax({
		type: 'post',
		url: 'reports/bestfit/reports-bestfit-ajax.php',
		data: dataparam,
		success:function(data) {                  
			$('#loadproducts').html(data);//Used to load the student details in the dropdown
		}
	});
}
/*fn_changecombi
 * Function to show the selected products in required combinations */

function fn_changecombi()

		{
		
		//validation for no.of titles
			if($('#trecomm').val()!='')
			{
				
				var list8=[];
				var list8length = 0;
				$("div[id^=list8_]").each(function()
				{
					if($(this).attr('class') != 'draglinkright dim')
					{
						list8.push($(this).attr('id').replace('list8_',''));
						list8length++;
					}
				});
				   var list9=[];
					var list9length = 0;
					$("div[id^=list9_]").each(function()
					{
						if($(this).attr('class') != 'draglinkleft dim')
						{
							list9.push($(this).attr('id').replace('list9_',''));
							
							list9length++;
						}
					});
				
				
				var list10=[];
				var list10length = 0;
				$("div[id^=list10_]").each(function()
				{
					if($(this).attr('class') != 'draglinkright dim')
					{
						list10.push($(this).attr('id').replace('list10_',''));
						list10length++;
					}
				}); 
				
				 
													
				for(var i=0;i<$('#trecomm').val()-list10length;i++)
				{
					for(var j=i+1;j<=$('#trecomm').val()-list10length;j++)
					{
						console.log(i+" "+j+" ~ ");
					}
				}
			}
			
			var list8;
			var list10;
			var ded_n;
			var n = list8.length;
			var n1 = list10.length;
                        if(n1 == "")	{
				var r = $('#trecomm').val();
				var tcom='';
			
				var f=1;
				for(var i=1;i<=n;i++)
				{
				  f=f*i;
				} 

				var r1=n-r;
				var f1=1;
				for(i=1;i<=r;i++)
				{
				  f1=f1*i;
				}
				var f2=1;
				for(i=1;i<=r1;i++)
				{
				  f2=f2*i;
				}
				var res=f1*f2;
				var res1=f/res;
				tcom = Math.round(res1*Math.pow(10,2))/Math.pow(10,2);
			   
				$('#totcombi').val(tcom);
			 }
			 else
			 {
				 var rw = $('#trecomm').val();
				 var tcom='';
				 
				 if(rw == n1)
				 {
					 tcom = 1;
					 $('#totcombi').val(tcom);
				 }
				 else
				 {
					 var rw = $('#trecomm').val();
					 var tcom='';
					 var diff_list=[];
					 
					 diff_list = arr_diff(list8,list10);
				  
					 var n2 = diff_list.length;
					 var rw1 = rw - n1;
					 var f=1;
					 for(var i=1;i<=n2;i++)
					 {
					   f=f*i;
					 } 
					var rw2=n2-rw1;
					var f1=1;
					for(i=1;i<=rw1;i++)
					{
					  f1=f1*i;
					}
					var f2=1;
					for(i=1;i<=rw2;i++)
					{
					  f2=f2*i;
					}
					
					var res=f1*f2;
					var res1=f/res;
				   
					tcom = Math.round(res1*Math.pow(10,2))/Math.pow(10,2);
					
					 $('#totcombi').val(tcom);
					 }
				}
	}


        
        function fn_tagchangecombi()

		{
      
                  	if($('#tagtrecomm').val()!='') {
                            var list12=[];
                            var list12length = 0;
                            $("div[id^=list12_]").each(function()
                            {
                                var pid = $(this).attr('id').replace('list12_','');
                                list12.push(pid);
	     });
                           var list13=[];
                            var list13length = 0;
                            $("div[id^=list13_]").each(function()
                            {
                                    if($(this).attr('class') != 'draglinkleft dim')
                                    {
                                            list13.push($(this).attr('id').replace('list13_',''));

                                            list13length++;
                                    }
                            });
				
                            var list14=[];
                            var list14length = 0;
                            $("div[id^=list14_]").each(function()
                            {
                                    if($(this).attr('class') != 'draglinkright dim')
                                    {
                                            list14.push($(this).attr('id').replace('list14_',''));
                                            list14length++;
                                    }
                            });
                            for(var i=0;i<$('#tagtrecomm').val()-list14length;i++)
                            {
                                    for(var j=i+1;j<=$('#tagtrecomm').val()-list14length;j++)
                                    {
                                            console.log(i+" "+j+" ~ ");
                                    }
                            }
			}
                        
                    var ded_n;
                    var n = list12.length;
                    var n1 = "";
                     if(n1 == "")	{
                            var r = $('#tagtrecomm').val();
                            var tcom='';

                            var f=1;
                            for(var i=1;i<=n;i++)
                            {
                              f=f*i;
                            } 

                            var r1=n-r;
                            var f1=1;
                            for(i=1;i<=r;i++)
                            {
                              f1=f1*i;
                            }
                            var f2=1;
                            for(i=1;i<=r1;i++)
                            {
                              f2=f2*i;
                            }
                            var res=f1*f2;
                            var res1=f/res;
                            tcom = Math.round(res1*Math.pow(10,2))/Math.pow(10,2);

                            $('#tagtotcombi').val(tcom);				
                        }
                        else
                        {
                                var rw = $('#tagtrecomm').val();
                                var tcom='';

                                if(rw == n1)
                                {
                                        tcom = 1;
                                        $('#tagtotcombi').val(tcom);
                                }
                                else
                                {
                                        var rw = $('#tagtrecomm').val();
                                        var tcom='';
                                        var diff_list=[];

                                        diff_list = arr_diff(list8,list10);

                                        var n2 = diff_list.length;
                                        var rw1 = rw - n1;
                                        var f=1;
                                        for(var i=1;i<=n2;i++)
                                        {
                                          f=f*i;
                                        } 
                                       var rw2=n2-rw1;
                                       var f1=1;
                                       for(i=1;i<=rw1;i++)
                                       {
                                         f1=f1*i;
                                       }
                                       var f2=1;
                                       for(i=1;i<=rw2;i++)
                                       {
                                         f2=f2*i;
                                       }

                                       var res=f1*f2;
                                       var res1=f/res;

                                       tcom = Math.round(res1*Math.pow(10,2))/Math.pow(10,2);

                                        $('#tagtotcombi').val(tcom);
                                        }
                               }
 
	}




/*fn_showselectedpro
 * Function to show the selected products in required field*/

function fn_showselectedpro(rptid,seletag)
{
		
	var selectproducts=$('#hidlist8').val();
	
    if(seletag==6){
       
                        var tagproductid= [];

                    $("div[id^=list12_]").each(function()
                  {
                      if($(this).attr("style") != "display: none;") {
                      var pid = $(this).children(":first").attr('id');
                      tagproductid.push(escape(pid));
                          }
                   });
                  

               var reqtagproductid= [];

               $("div[id^=list14_]").each(function()
                   {
                              if($(this).attr("style") != "display: none;") {
                              var pid = $(this).children(":first").attr('id');
                              reqtagproductid.push(escape(pid));
                          }

                   });
             var dataparam = "oper=showtagselectedpro&selectproducts="+tagproductid+"&rptid="+rptid+"&reqproducts="+reqtagproductid+"&seletag="+seletag;

        }
        else if(seletag==5){
        
    var productid= [];
   $("div[id^=list8_]").each(function()
       {
           if($(this).attr("style") != "display: none;") {
           var pid = $(this).children(":first").attr('id');
           productid.push(escape(pid));
               }
        });
    var selectproducts=$('#hidlist8').val();   
    var reqproductid= [];
    $("div[id^=list10_]").each(function()
        {
                 if($(this).attr("style") != "display: none;") {
	var reqpid = $(this).children(":first").attr('id');				
                reqproductid.push(escape(reqpid));
                 }			
        });

    var dataparam = "oper=showselectedpro&selectproducts="+selectproducts+"&rptid="+rptid+"&reqproducts="+reqproductid;
    }	

    $.ajax({
        type: 'post',
        url: 'reports/bestfit/reports-bestfit-ajax.php',
        data: dataparam,
        success:function(data) {           
            if($('input:checkbox[name=check_1]').is(':checked'))
            {
               
                $('#requiredproducts').show();
                $('#requiredproducts').html(data);
				if($('#trecomm').val() == '' && $('#maxrecomm').val() == '' && $('#totcombi').val() == ''){
                $('#trecomm').val('');
                $('#maxrecomm').val('');
                $('#totcombi').val('N/A');
				}
                }
            else if($('input:checkbox[name=check_2]').is(':checked'))
            {             
           
               $('#tagrequiredproducts').show();
                $('#tagrequiredproducts').html(data);
	if($('#tagtrecomm').val() == '' && $('#tagmaxrecomm').val() == '' && $('#tagtotcombi').val() == ''){
                $('#tagtrecomm').val('');
                $('#tagmaxrecomm').val('');
                $('#tagtotcombi').val('N/A');
				}
            }
            else{
                $('#requiredproducts').hide();
                $('#tagrequiredproducts').hide();
                }            
        }
    });
}

/*----
    fn_saveselect()
	Function to save the selected products
----*/
function fn_saveselect(){
    
     $('#hidlist8').val(''); 
	 
        var productid = [];
         
	 $("div[id^=list8_]").each(function()
	 {
            var pid = $(this).attr('id').replace('list8_','');
            productid.push(pid);
	 });
	$('#hidlist8').val(productid);

 
    var list8=[];
    $("div[id^=list8_]").each(function()
    {
        if($(this).attr('class') != 'draglinkright dim')
        list8.push($(this).attr('id').replace('list8_',''));
            
    }); 
    var list10=[];
    $("div[id^=list10_]").each(function()
    {
        if($(this).attr('class') !='draglinkright dim')
        list10.push($(this).attr('id').replace('list10_',''));
    });      
         
 if(productid!='')
    {
        $('#btnstep2').removeClass('dim');
        $('#checkbox').show();
    }
    else
    {
        $('#checkbox').hide();
        $('#check_1').removeClass('checked');
        $('#requiredproducts').hide();
        $('#btnstep2').addClass('dim');
    }
   $('#hidlist8').val(list8);
    
     var difflist=[];
     difflist=arr_diff(list8,list10);
     var req_lenth = list10.length;
     var selpart_diff = difflist.length;
     var selectedlen=list8.length;
 
 }
    function arr_diff(a1, a2)
    {
      var a=[], diff=[];
      for(var i=0;i<a1.length;i++)
        a[a1[i]]=true;
      for(var i=0;i<a2.length;i++)
        if(a[a2[i]]) delete a[a2[i]];
        else a[a2[i]]=true;
      for(var k in a)
        diff.push(k);
        return diff;
    }
    
 /*----
    fn_saveselect()
	Function to save the selected tag products
----*/
function fn_saveselecttag(){
    
	
            $('#hidlist12').val(''); 
	 
        var productid = [];
         
	 $("div[id^=list12_]").each(function()
	 {
            var pid = $(this).attr('id').replace('list12_','');
            productid.push(pid);
	 });
	$('#hidlist12').val(productid);

 
    var list12=[];
    $("div[id^=list12_]").each(function()
    {
        if($(this).attr('class') != 'draglinkright dim')
        list12.push($(this).attr('id').replace('list12_',''));
            
    }); 
    var list14=[];
    $("div[id^=list14_]").each(function()
    {
        if($(this).attr('class') !='draglinkright dim')
        list14.push($(this).attr('id').replace('list14_',''));
    });  
         
 if(productid!='')
    {
        $('#tagbtnstep2').removeClass('dim');
        $('#checkbox2').show();
    }
    else
    {      
        $('#checkbox2').hide();
        $('#check_2').removeClass('checked');
        $('#tagrequiredproducts').hide();
        $('#tagbtnstep2').addClass('dim');
    }
   $('#hidlist12').val(list12);
 
 }
 

 function fn_removeselecttag(id){
 
	 var pid = $('#form_tags_products').val();       
         if(pid =='')
         {
             var remtagproductid = [];
              $("div[id^=list12_]").each(function()
                {
                        var pid = $(this).attr('id').replace('list12_','');
                        remtagproductid.push(pid);
                });
                    for (var i = 0; i < remtagproductid.length; ++i) {
         
                                $('#list12_'+remtagproductid[i]).remove();
                        } 
                    }
	 var dataparam = "oper=removerightroducts&rptid="+id+"&remtagproducts="+pid;
	 $.ajax({
                type: 'post',
                url: 'reports/bestfit/reports-bestfit-ajax.php',
              
                data: dataparam,
                success:function(data) {                
					 var parsed=JSON.parse(data);
                 var ret_tagproductid = [];
                  for (var i = 0; i < parsed.length; ++i) {
                 	ret_tagproductid.push(parsed[i]['id']+'_'+parsed[i]['type']);
                  }
                  
                  var tagproductid = [];
				 $("div[id^=list12_]").each(function()
	 			{
					var pid = $(this).attr('id').replace('list12_','');
					tagproductid.push(pid);
				});
				
				 var selectdifferentset1 = [];
				 var i = 0;
				 jQuery.grep(tagproductid, function(el) {

    			 if (jQuery.inArray(el, ret_tagproductid) == -1) selectdifferentset1.push(el);
            	    i++;
				});

				
                 	for (var i = 0; i < selectdifferentset1.length; ++i) {

	 					$('#list12_'+selectdifferentset1[i]).remove();
                                                $('#list13_'+selectdifferentset1[i]).remove();
                                                $('#list14_'+selectdifferentset1[i]).remove();
	 				} 
                }
            }); 
	 
	

 }

/*----
    fn_viewpdf()
	Function to view pdf reports
----*/
function fn_view(id)
{
        ajaxloadingalert('Loading, please wait.');
        setTimeout('removesections("#reports-bestfit-actions");',500);
	setTimeout('showpageswithpostmethod("reports-bestfit-generate_report","reports/bestfit/reports-bestfit-generate_report.php","id='+id+'")',500);
}

/*
 * get unique value for products and selected products
  */
function fn_remloadedprod()
{

	var productid = [];
    $("div[id^=list11_]").each(function()
    {
        var pid = $(this).attr('id').replace('list11_','');
        productid.push(pid);
    });
    var tagproductid = [];
	$("div[id^=list12_]").each(function()
	{
			var pid = $(this).attr('id').replace('list12_','');
			tagproductid.push(pid);
	});
	
           if(tagproductid==''){
            
        $('#checkbox').hide();
        $('#check_2').removeClass('checked');
        $('#tagrequiredproducts').hide();
        $('#tagbtnstep2').addClass('dim');
            
        }
	
     	 if(tagproductid != '')
	 {
	 var selectdifferentset1 = [];
				 var i = 0;
				 jQuery.grep(tagproductid, function(el) {

    			 if (jQuery.inArray(el, productid) != -1) selectdifferentset1.push(el);
            	    i++;
				});
				
				 for (var i = 0; i < selectdifferentset1.length; ++i) {

	 					$('#list11_'+selectdifferentset1[i]).remove();
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
				url: 'reports/bestfit/reports-bestfit-ajax.php',
				data: dataparam,
				success:function(data) {		
					setTimeout('removesections("#reports");',500);
					setTimeout('showpages("reports-bestfit","reports/bestfit/reports-bestfit.php")',500);
					
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
			$('#btnstep1').removeClass('dim');
		}
		else
		{
			$('#btnstep1').addClass('dim');
		}
}
/*----
    fn_validateproducts()
	Function to validate the products details
----*/
function fn_validateproducts()
{
    var selectproducts=$('#hidlist8').val(); 
    
    if(selectproducts!='')
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
function fn_request(id)
{
        var requestcomments = '';
      
        
			requestcomments = encodeURIComponent(tinymce.get('requestcomments').getContent().replace(/tiny_mce\//g,""));
			$('#requestcomments').html('');	                      
      
		
     var dataparam ="oper=sendmail"+"&id="+id+"&requestcomments="+requestcomments+"&requestdate="+$('#requestdate').val()+"&rid="+$('#rid').val();	
	if($("#frmrequest").validate().form()){	
		

            if($('#id').val() != 'undefined' && $('#id').val() != '0'){            	
                    actionmsg = "SENDING";
                    alertmsg = "MAIL HAS BEEN SEND SUCCESSFULLY"; 
            }
            else {

            	actionmsg = "SENDING";
                    alertmsg = "MAIL HAS BEEN FAILED"; 
                    
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

