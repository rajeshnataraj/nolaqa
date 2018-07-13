<?php 
@include("sessioncheck.php");
?>
<script type="text/javascript">
	$.getScript("tools/calendar/tools-calendar-calendarjs.js");
</script>
<section data-type='#tools-calendar' id='tools-calendar-calendar'>
<div class='container'>
            <div class='row'>
                    <div class="span10">
                        <p class="dialogTitle">My Calendar</p>
                        <!--<p class="dialogSubTitleLight">Select a date to create a new appointment or view appointment details.</p>-->
                        <p class="dialogSubTitleLight">Select a date to create a new appointment, or to view appointment details.</p>
                    </div>
                </div>
    	<div class='row'>
            <div class='twelve columns formBase rowspacer'>
            <div class='six columns' >
            <span style=" margin-left: 20px;margin-top: 35px;position: absolute;z-index: 100;"><h3 style="color:#88ABC2;"><?php echo $username?>'s calendar</h3></span>
            </div>
            <div class="row" style="padding-bottom:20px;">
            	<div class="eleven columns formbase" style="width:97%;">
				<div id='calendar' style="margin-left:40px; margin-top:30px;"></div>
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
	
		$('#calendar').fullCalendar({
			header: {
				right: 'prev,title,next',
				center:'month,agendaWeek',
				
			},			
			<?php
				
			$qry = $ObjDB->QueryObject("SELECT fld_id AS eventid,fld_app_name AS sname,0 AS rotation, fld_startdate AS startdate,fld_enddate AS enddate, fld_starttime AS starttime,fld_endtime AS endtime FROM itc_calendar_master WHERE fld_created_by='".$uid."' AND fld_delstatus='0'");
                        
                        $qrylockclass=$ObjDB->QueryObject("select b.fld_class_name as classname,a.fld_id as eventid,a.fld_startdate as startdate,a.fld_enddate as enddate,
                                                            a.fld_starthour AS shour,a.fld_startmin AS smin,a.fld_startampm as sampm,
                                                            a.fld_endhour AS ehour,a.fld_endmin AS emin,a.fld_endampm AS eampm 
                                                             from itc_class_lockclassautomation as a 
                                                            left join itc_class_master as b on b.fld_id=a.fld_class_id
                                                            where  a.fld_delstatus='0' and b.fld_delstatus='0' and a.fld_created_by='".$uid."' AND b.fld_lock='1' AND a.fld_flag='1'");
			?>
				events: [
					<?php
						
						if($qry->num_rows!=0)
						{
						$cnt=0;
						while($row=$qry->fetch_assoc())
						{
						extract($row);?>
						{
							editable: <?php if($eventid !=0){ echo "true"; }else{ echo "false"; }?>,
							id:<?php echo $eventid;?>,
							title :'<?php echo $sname; ?> ',
							<?php if($starttime !='0' && $starttime !='' && $endtime !='0' && $endtime !=''){?>
							start : '<?php echo $startdate;?> <?php echo $starttime;?>',
							end : '<?php echo $enddate?> <?php echo "00:05"?>',
						<?php }
						else
						{?>
							start : '<?php echo $startdate;?>',
							end : '<?php echo $enddate?>',
						<?php }?>
						allDay : false
						}
						<?php if($qry->num_rows!=$cnt){ ?>,<?php }
						$cnt++;
						}
						}

                                                if($qrylockclass->num_rows!=0)
						{
                                                   $cnt=0; 
                                                   while($row=$qrylockclass->fetch_assoc())
                                                    {
                                                      extract($row);
                                                    ?>
                                                     {
                                                        editable:false,
							id:<?php echo $eventid;?>,
							title :'<?php echo "Class lock -". $classname; ?> ',
							start : '<?php echo $startdate;?>',
							end : '<?php echo $enddate?>'
                                                    },
                                                <?php
                                                }
                                                }
                                                ?>

				],
				
			
				eventResize: function(event,dayDelta,minuteDelta,revertFunc) {

					var view = $('#calendar').fullCalendar('getView');
					var viewname= view.name;
					var closedate = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd HH:mm:ss");
					if(viewname=='month')
					{
						var dataparam = "oper=updatedate&enddate="+closedate+"&eventid="+event.id;
					}
					else
					{
						var dataparam = "oper=updatetime&endtime="+minuteDelta+"&eventid="+event.id;
					}
							$.ajax({
									type: 'post',
									url: 'tools/calendar/tools-calendar-calendar-ajax.php',
									data: dataparam,
									beforeSend: function(){
										showloadingalert('Updating appointment, please wait.');	
									},
									success: function (data) {	
									setTimeout('closeloadingalert()',1000);
									setTimeout('removesections("#tools");',500);
									setTimeout('showpageswithpostmethod("tools-calendar-calendar","tools/calendar/tools-calendar-calendar.php");',500);
									
									
									}
							});
			
				},
				eventDrop: function(event,dayDelta,minuteDelta,allDay,revertFunc) {
					var view = $('#calendar').fullCalendar('getView');
					var viewname= view.name;
					
					if(viewname=='month')
					{
						var datestart = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");
						var enddate=event.end;
						var dateend = $.fullCalendar.formatDate(enddate, "yyyy-MM-dd HH:mm:ss");
						var dataparam = "oper=updateday&eventid="+event.id+"&datestart="+datestart+"&dateend="+dateend;
						
					}
					else
					{
						var datestart = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");
						var dateend = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd HH:mm:ss");
						var dataparam = "oper=updatehour&endtime="+minuteDelta+"&eventid="+event.id+"&changedays="+dayDelta+"&datestart="+datestart+"&dateend="+dateend;
						
					}
							$.ajax({
									type: 'post',
									url: 'tools/calendar/tools-calendar-calendar-ajax.php',
									data: dataparam,
									beforeSend: function(){
										showloadingalert('Updating appointment, please wait.');	
									},
									success: function (data) {	
									setTimeout('closeloadingalert()',1000);
									setTimeout('removesections("#tools");',500);
									setTimeout('showpageswithpostmethod("tools-calendar-calendar","tools/calendar/tools-calendar-calendar.php");',1000);
									
									
									}
							});
							
							
					var dataparam = "oper=updatehour&endtime="+minuteDelta+"&eventid="+event.id+"&changedays="+dayDelta+"&datestart="+datestart+"&dateend="+dateend;
							
			
				},
				 dayClick: function(event,date, allDay, jsEvent, view ) {
					if (allDay) {
						clickevent();
					}else{
						clickevent();
					}
			    },
				
				eventClick: function(event)
				{
					
					  var eventid=event.id;
					  if(eventid!=0)
					  {
					  	clickevent(eventid,1);
					  }
				},
				timeFormat: 'h(:mm) tt '
	
		});
		
	});
</script>
<?php
	@include("footer.php");

