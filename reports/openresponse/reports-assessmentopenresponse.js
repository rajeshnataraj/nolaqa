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
function fn_validateassessments()
{
 var selectassess=$('#hidselectedassessments').val(); 		
}
/* list open response Q&A for selected assessment */
function  fn_openresreport(studentid)
{
	$('#hidselectedassessments').val(''); 
	var assessmentid = [];
	var seperateassess = [];
	$("div[id^=list10_]").each(function()
	{
		var assessid = $(this).attr('id').replace('list10_','');
		assessmentid.push(assessid);
	});
	if(assessmentid=='')
	{
		showloadingalert("please select any Assessment.");
		setTimeout('closeloadingalert()',2000);
		return false;
	}

	$('#hidselectedassessments').val(assessmentid);
	var assessmentid =  $('#hidselectedassessments').val();
	var dataparam = "oper=saveselect_assessment&assessmentid="+assessmentid+"&studentid="+studentid;

	$.ajax({
	type: 'POST',
	url: 'reports/openresponse/reports-openresponse-ajax.php',
	data: dataparam,
	beforeSend: function()
	{
	showloadingalert('Loading, please wait.');
	},
	success:function(data) {
		var response=trim(data);
		var ajaxdata=response.split('~');
		var sep_assessid = ajaxdata[1].split(","); 
		for (var i = 0; i < sep_assessid.length; i++) {
			seperateassess.push(sep_assessid[i]);
		}		
		var val = ajaxdata[0]+"~"+seperateassess;
		closeloadingalert();		
		setTimeout("removesections('#reports-openresponse');",500);	
		setTimeout('showpages("reports-openresponse-listopensourseans.php","reports/openresponse/reports-openresponse-listopensourseans.php?id='+val+'");',500);
		$("html,body").animate({scrollTop:$(document).height()},2000)

	}
	});

}

function fn_showanspage_ind(studentid,questionid,ansid) {

	var dataparam = "oper=view_ind_answer"+"&studid="+studentid+"&questionid="+questionid+"&answerid="+ansid;

	$.ajax({
		type: 'post',
		url: 'reports/openresponse/reports-openresponse-ajax.php',
		data: dataparam,		
		beforeSend: function(){
		showloadingalert("Loading, please wait.");	
		},
		success:function(ajaxdata) {		
			var response=trim(ajaxdata);
			var output=response.split('~');
			var status=output[0];
			var questionid=output[1];

			closeloadingalert();	
			setTimeout('closeloadingalert()',1000);
			var val = output[1]+","+studentid;
			setTimeout("removesections('#reports-openresponse-listopensourseans');",500);	
			setTimeout('showpages("reports-openresponse-showsingleanswerset","reports/openresponse/reports-openresponse-showsingleanswerpart.php?id='+val+'");',500);
			$("html,body").animate({scrollTop:$(document).height()},2000)

		}
	});
}
/* view comment box when click image */
function fn_showcommentbox(commenttext,headertxt)
{

	$.Zebra_Dialog('<div style="height:220px;overflow:auto;background:#cfd8dc;color:#000000">'+commenttext+'</div>', {
	    'title':    '<div style="color:#000000;">'+headertxt+'</div>',
	    'position': ['right -580', 'top + 200']
	});


}


function fn_viewselectedassessment(startdt,enddt,studentid)
{
	var dataparam = "oper=showassessmentlist"+"&studid="+studentid+"&startdt="+startdt+"&enddt="+enddt;
	$.ajax({
		type: 'post',
		url: 'reports/openresponse/reports-openresponse-ajax.php',
		data: dataparam,		
		beforeSend: function(){
			showloadingalert("Loading, please wait.");	
		},
		success:function(ajaxdata) {			
			$("#loadassessmentlist").show();
			$('#viewreportdiv').show();
			$("#loadassessmentlist").html(ajaxdata);
			closeloadingalert();	
			 $("html,body").animate({scrollTop:$(document).height()},2000)
		}
	});
}
