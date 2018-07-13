// JavaScript Document
function clickpage()
{
	showloadingalert("Loading, please wait.");
	setTimeout('closeloadingalert()',1000);
	setTimeout('removesections("#tools-calendar-calendar");',500);
	setTimeout('showpageswithpostmethod("tools-calendar-calweek","tools/calendar/tools-calendar-calweek.php");',500);
}

function clickevent(eventid,type)
{
	setTimeout('closeloadingalert()',1000);
	setTimeout('removesections("#tools-calendar-calendar");',1000);
	setTimeout('showpageswithpostmethod("tools-calendar-editappointment","tools/calendar/tools-calendar-editappointment.php","eventid='+eventid+'&type='+type+'");',1000);
}

function minFromMidnight(tm){
 var ampm= tm.substr(-2)
 var clk = tm.substr(0, 5);
 var m  = parseInt(clk.match(/\d+$/)[0], 10);
 var h  = parseInt(clk.match(/^\d+/)[0], 10);
 h += (ampm.match(/pm/i))? 12: 0;
 return h*60+m;
}

function fn_saveevent(eventid,pagetype)
{
	if($("#eventform").validate().form())
	{
		if($('#startdate').val()==$('#enddate').val())
		{
			st = minFromMidnight($('#starttime').val());
			et = minFromMidnight($('#endtime').val());
			
			if(parseInt(st)>parseInt(et)){
				$.Zebra_Dialog('End time must be greater than start time', {
				'buttons':  false,
				'auto_close': 3000
			});
			return false;
			}
		}
		
		var dataparam = "oper=saveevent"+"&eventid="+eventid+"&eventtitle="+escapestr($('#eventtitle').val())+"&startdate="+$('#startdate').val()+"&starttime="+$('#starttime').val()+"&enddate="+$('#enddate').val()+"&endtime="+$('#endtime').val();
		$.ajax({
				type: 'post',
				url: 'tools/calendar/tools-calendar-calendar-ajax.php',
				data: dataparam,
				beforeSend: function(){
					if(eventid==0)
					{
						showloadingalert('Adding appointment, please wait.');	
					}
					else
					{
						showloadingalert('Updating appointment, please wait.');
					}
				},
				success: function (data) {	
					if(data=="success")
					{
						setTimeout('closeloadingalert()',1000);
						setTimeout('removesections("#tools");',500);
						setTimeout('showpageswithpostmethod("tools-calendar-calendar","tools/calendar/tools-calendar-calendar.php");',1000);
					}
					else
					{
						$('.lb-content').html("Incorrect Data");
					 	setTimeout('closeloadingalert()',1000);
					}
				
				}
		});
	}
}

function fn_delete(eventid)
{
	
	$.Zebra_Dialog('This Appointment will be lost, Are you sure you want to delete ?',
	{
	'type': 'confirmation',
	'buttons': [
	{caption: 'No', callback: function() { }},
	{caption: 'Yes', callback: function() {
		var dataparam = "oper=deleteevent"+"&eventid="+eventid;
			$.ajax({
			type: 'post',
			url: 'tools/calendar/tools-calendar-calendar-ajax.php',
			data: dataparam,		
			beforeSend: function(){
				showloadingalert("Appointment deleting please wait");	
			},
			success: function (data) {	
			setTimeout('closeloadingalert()',1000);
			setTimeout('removesections("#tools");',500);
			setTimeout('showpageswithpostmethod("tools-calendar-calendar","tools/calendar/tools-calendar-calendar.php");',1000);
			}
		});
		}},
	]
	});

}
