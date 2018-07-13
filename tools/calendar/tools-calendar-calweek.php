<?php 
@include("sessioncheck.php");
$id = isset($method['id']) ? $method['id'] : '';
?>

<script type="text/javascript">
	$.getScript("tools/calendar/tools-calendar-calendarjs.js");
</script>
<section data-type='#tools-calendar' id='tools-calendar-calweek'>
<div class='container'>
            <div class='row'>
                    <div class="span10">
                         <p class="dialogTitle">The week of <?php $today = getdate(); echo date("M")." ".($today['mday'] - $today['wday'])." - ".($today['mday'] - $today['wday']+6)." ".date("Y");?> </p>
                        <p class="dialogSubTitleLight">View and edit your appointment details below. Click an empty time-slot to create a new appointment.</p>
                    </div>
                </div>
    	<div class='row'>
            <div class='twelve columns formBase'>
            <div class="row" style="padding-bottom:20px;">
            	<div class="eleven columns formbase" style="width:97%;">
				<div id='calendarweek' style="margin-left:40px; margin-top:30px;"></div>
                </div>
            </div>
            </div>
        </div>
    </div>
</section>
<script type='text/javascript'>
	$(document).ready(function() {
	
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		$('#calendarweek').fullCalendar({
			header: {
			left: 'prev',
			center: 'title',
			right:'next',
			},
			defaultView: 'agendaWeek',
		
			editable: true,	
			<?php
			$qry = $ObjDB->QueryObject("SELECT 0 AS eventid,a.fld_schedule_name AS sname,b.fld_rotation AS rotation,b.fld_startdate AS startdate,b.fld_enddate AS                   enddate,0 AS starttime,0 AS endtime 
                   FROM itc_class_rotation_schedule_mastertemp AS a 
				   LEFT JOIN itc_class_rotation_schedulegriddet AS b ON b.fld_schedule_id=a.fld_id 
				   WHERE b.fld_student_id='".$uid."' AND a.fld_delstatus=0 AND b.fld_flag=1 GROUP BY b.fld_rotation,b.fld_schedule_id 
				   UNION 
				   SELECT 0 AS eventid, a.fld_schedule_name AS sname,b.fld_rotation AS rotation,b.fld_startdate AS startdate,b.fld_enddate AS enddate,0 AS                   starttime,0 AS endtime FROM itc_class_triad_schedulemaster AS a 
				   LEFT JOIN itc_class_triad_schedulegriddet AS b ON b.fld_schedule_id=a.fld_id 
				   WHERE b.fld_student_id='".$uid."' AND a.fld_delstatus=0 AND b.fld_flag=1 GROUP BY b.fld_rotation,b.fld_schedule_id 
				   UNION 
				   SELECT 0 AS eventid,a.fld_schedule_name AS sname,b.fld_rotation AS rotation,b.fld_startdate AS startdate,b.fld_enddate AS enddate,0 AS                   starttime,0 AS endtime 
				   FROM itc_class_dyad_schedulemaster AS a 
				   LEFT JOIN itc_class_dyad_schedulegriddet AS b ON b.fld_schedule_id=a.fld_id 
				   WHERE b.fld_student_id='".$uid."' AND a.fld_delstatus=0 AND b.fld_flag=1 GROUP BY b.fld_rotation,b.fld_schedule_id 
				   UNION 
				   SELECT fld_id AS eventid,fld_app_name AS sname,0 AS rotation, fld_startdate AS startdate,fld_enddate AS enddate, fld_starttime AS starttime,                   fld_endtime AS endtime 
                   FROM itc_calendar_master  WHERE fld_created_by='".$uid."' AND fld_delstatus='0'");
			?>
			events: [
				<?php
					if($qry->num_rows!=0)
						{
						$cnt=0;                                           
						while($row=$qry->fetch_assoc())
							{
								extract($row);
								
								?>
								{
									editable: <?php if($eventid!=0){ echo "true"; }else{ echo "false"; }?>,
									id:<?php echo $eventid;?>,
									title :'<?php echo $sname; ?> ',
									start : '<?php  echo $startdate; ?> <?php echo $starttime;?>',
									
									end  : '<?php echo $enddate;?> <?php echo $endtime;?>',
									allDay : false
								}
								<?php if($qry->num_rows!=$cnt){ ?>,<?php }
								$cnt++;
					 } 
					}?>
			],
			eventResize: function(event,dayDelta,minuteDelta/*,revertFunc*/,eventid) {
						var dataparam = "oper=updatetime&endtime="+minuteDelta+"&eventid="+event.id;
							$.ajax({
									type: 'post',
									url: 'tools/calendar/tools-calendar-calendar-ajax.php',
									data: dataparam,
									beforeSend: function(){
										showloadingalert('Adding event, please wait.');	
									},
									success: function (data) {	
									setTimeout('closeloadingalert()',1000);
									setTimeout('$("#tools-calendar-calendar").nextAll().hide("fade").remove();',500);
									setTimeout('showpageswithpostmethod("tools-calendar-calweek","tools/calendar/tools-calendar-calweek.php");',500);
									
									
									}
							});
				},
					
				eventDrop: function(event,dayDelta,minuteDelta,allDay) {
					
					var datestart = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");
					var dateend = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd HH:mm:ss");
					var dataparam = "oper=updatehour&endtime="+minuteDelta+"&eventid="+event.id+"&changedays="+dayDelta+"&datestart="+datestart+"&dateend="+dateend;
							$.ajax({
									type: 'post',
									url: 'tools/calendar/tools-calendar-calendar-ajax.php',
									data: dataparam,
									beforeSend: function(){
										showloadingalert('Adding event, please wait.');	
									},
									success: function (data) {	
									setTimeout('closeloadingalert()',1000);
									setTimeout('$("#tools").nextAll().hide("fade").remove();',500);
									setTimeout('showpageswithpostmethod("tools-calendar-calendar","tools/calendar/tools-calendar-calendar.php");',500);
									setTimeout('showpageswithpostmethod("tools-calendar-calweek","tools/calendar/tools-calendar-calweek.php");',500);
									}
							});
			
				},
			
				eventClick: function(event)
				{
					var eventid=event.id;
					clickevent(eventid,2);
				},
				
			dayClick: function(date, allDay, jsEvent, view, agendaWeek, event)
			 {
				 
					clickevent();
					
			 }
		});
		
	});

</script>
<?php
	@include("footer.php");

