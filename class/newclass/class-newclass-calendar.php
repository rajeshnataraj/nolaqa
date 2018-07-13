<?php 
@include("sessioncheck.php");
$tempid = isset($method['id']) ? $method['id'] : '';
$tempid=explode(",",$tempid);
$id=$tempid[0];
$flag=$tempid[1];
$tempdate=explode("-",$tempid[2]);
?>
<script language="javascript">
	$('#classdetails').removeClass("active-first");
	$('#review').removeClass("active-last");
	$('#people').removeClass("active-mid");
	$('#schedule').parents().removeClass("dim");
	$('#schedule').addClass("active-mid");
</script>

<section data-type='#class-newclass' id='class-newclass-calendar'>
	<div class='container'>
        <div class='row'>
            <div class="span10">
                <p class="dialogTitle">Class Calendar</p>
                <p class="dialogSubTitleLight">&nbsp;</p>
            </div>
        </div>
	
        <div class='row'>
            <div class='twelve columns formBase'>
                <div class='row'>
                    <div class='eleven columns centered insideForm'>
                    	<div class="row">
	                        <div class='twelve columns'>
		                    	<div id='calendar' style="margin-left:20px;"></div>
                           	</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row rowspacer"></div>
 	</div>
    
    <script type='text/javascript'>
		$(document).ready(function() {
		
			var date = new Date();
			var d = date.getDate();
			var m = date.getMonth();
			var y = date.getFullYear();
			
			$('#calendar').fullCalendar({
				header: {
					right: 'prev,title,next',
				},
				<?php
					if($tempdate[0]!='')
					{
				?>
				year: <?php echo $tempdate[0];?>,
 				month: <?php echo $tempdate[1]-1;?>,
 				date: <?php echo $tempdate[2];?>,
				<?php
					}
				?>
				editable: false,	
				<?php
				$qryscheduledet = $ObjDB->QueryObject("SELECT fld_id AS sid, fld_schedule_name AS sname,fld_start_date AS startdate,fld_end_date AS enddate,fld_step_id AS rotation,'true' AS editable, 'Sigmath' AS typename,'' AS background FROM itc_class_sigmath_master WHERE fld_class_id='".$id."' AND fld_delstatus='0'
				UNION ALL	
SELECT b.fld_schedule_id AS sid, a.fld_schedule_name AS sname,b.fld_startdate AS startdate, b.fld_enddate AS enddate,b.fld_rotation AS rotation,'true' AS editable,'rotation' AS typename,'' AS background FROM itc_class_rotation_schedule_mastertemp AS a LEFT JOIN itc_class_rotation_scheduledate AS b ON b.fld_schedule_id=a.fld_id WHERE a.fld_class_id='".$id."' AND a.fld_delstatus=0 AND b.fld_flag=1 GROUP BY b.fld_rotation, b.fld_schedule_id
				UNION ALL
SELECT a.fld_id AS sid, a.fld_schedule_name AS sname, a.fld_startdate AS startdate, a.fld_enddate AS enddate, '' AS rotation, 'true' AS editable, 'assesment' AS typename, '#008000' AS background FROM itc_class_indassesment_master AS a LEFT JOIN  itc_class_indassesment_student_mapping AS b ON b.fld_schedule_id = a.fld_id WHERE a.fld_class_id = '".$id."' AND a.fld_delstatus = '0' AND b.fld_flag = '1' GROUP BY b.fld_schedule_id 
				UNION ALL
SELECT b.fld_schedule_id AS sid,a.fld_schedule_name AS sname,b.fld_startdate AS startdate,b.fld_enddate AS enddate,b.fld_rotation AS rotation,'true' AS editable,'dyad' AS typename,b.fld_stageid AS background  FROM itc_class_dyad_schedulemaster AS a LEFT JOIN itc_class_dyad_schedulegriddet AS b ON b.fld_schedule_id=a.fld_id WHERE a.fld_class_id='".$id."' AND a.fld_delstatus=0 AND b.fld_flag=1 GROUP BY b.fld_rotation,b.fld_schedule_id
				UNION ALL 
SELECT b.fld_schedule_id AS sid,concat(a.fld_schedule_name,'-',b.fld_stagename) AS sname,b.fld_startdate AS startdate,b.fld_enddate AS enddate,'' AS rotation,'true' AS editable,'dyad' AS typename,b.fld_id AS background 
FROM itc_class_dyad_schedulemaster AS a 
LEFT JOIN itc_class_dyad_schedule_insstagemap AS b ON b.fld_schedule_id=a.fld_id 
WHERE a.fld_class_id='".$id."' AND a.fld_delstatus=0 AND b.fld_flag=1 AND a.fld_dyadtableflg='1' AND b.fld_stagetype='1' AND b.fld_startdate<>'0000-00-00'
				UNION ALL
SELECT b.fld_schedule_id AS sid,a.fld_schedule_name AS sname,b.fld_startdate AS startdate,b.fld_enddate AS enddate,b.fld_rotation AS rotation,'true' AS editable,'triad' AS typename,b.fld_stageid AS background  FROM itc_class_triad_schedulemaster AS a LEFT JOIN itc_class_triad_schedulegriddet AS b ON b.fld_schedule_id=a.fld_id WHERE a.fld_class_id='".$id."' AND a.fld_delstatus=0 AND b.fld_flag=1 GROUP BY b.fld_rotation,b.fld_schedule_id
				UNION ALL 
SELECT b.fld_schedule_id AS sid,concat(a.fld_schedule_name,'-',b.fld_stagename) AS sname,b.fld_startdate AS startdate,b.fld_enddate AS enddate,'' AS rotation,'true' AS editable,'triad' AS typename,b.fld_id AS background 
FROM itc_class_triad_schedulemaster AS a 
LEFT JOIN itc_class_triad_schedule_insstagemap AS b ON b.fld_schedule_id=a.fld_id 
WHERE a.fld_class_id='".$id."' AND a.fld_delstatus=0 AND b.fld_flag=1 AND a.fld_triadtableflg='1' AND b.fld_stagetype='1' AND b.fld_startdate<>'0000-00-00'
                              UNION ALL	
SELECT b.fld_schedule_id AS sid, a.fld_schedule_name AS sname,b.fld_startdate AS startdate, b.fld_enddate AS enddate,b.fld_rotation AS rotation,'true' AS editable,'exprotation' AS typename,'' AS background FROM itc_class_rotation_expschedule_mastertemp AS a LEFT JOIN itc_class_rotation_expscheduledate AS b ON b.fld_schedule_id=a.fld_id WHERE a.fld_class_id='".$id."' AND a.fld_delstatus=0 AND b.fld_flag=1 GROUP BY b.fld_rotation, b.fld_schedule_id
                             UNION ALL	
SELECT b.fld_schedule_id AS sid, a.fld_schedule_name AS sname,b.fld_startdate AS startdate, b.fld_enddate AS enddate,b.fld_rotation AS rotation,'true' AS editable,'modexprotation' AS typename,'' AS background FROM itc_class_rotation_modexpschedule_mastertemp AS a LEFT JOIN itc_class_rotation_modexpscheduledate AS b ON b.fld_schedule_id=a.fld_id WHERE a.fld_class_id='".$id."' AND a.fld_delstatus=0 AND b.fld_flag=1 GROUP BY b.fld_rotation, b.fld_schedule_id
    
 UNION ALL	
SELECT b.fld_schedule_id AS sid, a.fld_schedule_name AS sname,b.fld_startdate AS startdate, b.fld_enddate AS enddate,b.fld_rotation AS rotation,'true' AS editable,'missionrot' AS typename,'' AS background FROM itc_class_rotation_mission_mastertemp AS a LEFT JOIN itc_class_rotation_missionscheduledate AS b ON b.fld_schedule_id=a.fld_id WHERE a.fld_class_id='".$id."' AND a.fld_delstatus=0 AND b.fld_flag=1 GROUP BY b.fld_rotation, b.fld_schedule_id
    
UNION ALL
SELECT fld_id AS sid, fld_schedule_name AS sname,fld_startdate AS startdate,fld_enddate AS enddate,'' AS rotation,'true' AS editable,'wcamission' AS typename,'#008000' AS background  FROM itc_class_indasmission_master WHERE fld_class_id='".$id."' AND fld_delstatus='0'
                                        UNION ALL
SELECT fld_id AS sid, fld_schedule_name AS sname,fld_start_date AS startdate,fld_end_date AS enddate,'' AS rotation,'true' AS editable, 'pdschedule' AS typename,'' AS background FROM itc_class_pdschedule_master WHERE fld_class_id='".$id."' AND fld_delstatus='0'
                                        UNION ALL
SELECT fld_id AS sid, fld_schedule_name AS sname,fld_startdate AS startdate,fld_enddate AS enddate,'' AS rotation,'true' AS editable,'wcaexpedition' AS typename,'#008000' AS background  FROM itc_class_indasexpedition_master WHERE fld_class_id='".$id."' AND fld_delstatus='0'");								
				?>
				events: [
						<?php  // Sigmath start
						 if($qryscheduledet->num_rows!=0){
							$cnt=0;                                           
							while($row=$qryscheduledet->fetch_assoc()){
							 extract($row);
							 $sdate='';
							 $edate='';
							 
							 $sdate=$startdate;
							 $edate=$enddate;
							 $sdate=explode("-",$sdate);
							 $edate=explode("-",$edate);
						?>
						{
							editable: <?php echo $editable;?>,
							type:"<?php echo $typename;?>",
							sid:"<?php echo $sid;?>",
							rotation:"<?php echo $rotation;?>",
							backgroundColor: "<?php echo $background;?>",
							<?php
							if($typename=="Sigmath" or $typename=="assesment" or $typename=="wcaexpedition" or $typename=="wcamission" or $typename=="pdschedule")
							{
							?>
							title: "<?php echo stripslashes($sname);?>",
							<?php
							}
							else
							{
								if($typename=="rotation" or $typename=="exprotation" or $typename=="modexprotation" or $typename=="missionrot" )
								{
									$rot=$rotation-1;
								}
								else
								{
									$rot=$rotation;
								}
								
								if($rot!=0)
								{
							?>
							title: "<?php  echo $sname."-Rotation".$rot;?>",
							<?php
								}
								else if($rot=='0')
								{
							?>
							title: "<?php  echo $sname."-Orientation";?>",
							<?php
								}
								else
								{
							?>
							title: "<?php  echo $sname;?>",
							<?php
								}
							}
							?>
							start: new Date(<?php $smonth=$sdate[1]-1;  echo $sdate[0].",".$smonth.",".$sdate[2]?>),
							end: new Date(<?php $emonth=$edate[1]-1; echo $edate[0].",".$emonth.",".$edate[2]?>)
						},
							
						<?php 
							$cnt++;
							}
						 } 
					?> 
				],
				
				eventDrop: function(event,dayDelta,minuteDelta,allDay,revertFunc) 
				{
				 	var type= event.type;
				 	var sid=event.sid;
				 	var date=event.start;
					var enddate=event.end;
					var day=date.getDate();
					var month=date.getMonth()+1;
					var year=date.getFullYear();
					var eday=enddate.getDate();
					var emonth=enddate.getMonth()+1;
					var eyear=enddate.getFullYear();
					var rotation=event.rotation;
					var stageid=event.backgroundColor;
					
					var curdate=year+"-"+month+"-"+day;
					var curedate=eyear+"-"+emonth+"-"+eday;
					
					if(type=="rotation" || type=="dyad" || type=="triad" || type=="exprotation" || type=="modexprotation" || type=="missionrot")
					{
					dataparam="oper=checkrotdate&sid="+sid+"&date="+curdate+"&rotation="+rotation+"&enddate="+curedate+"&type="+type+"&stageid="+stageid;
	
						$.ajax({
							type: 'post',
							url: 'class/newclass/class-newclass-classajax.php',
							data: dataparam,		
							success:function(ajaxdata) {
								if(ajaxdata=="success")
								{
					    			fn_changeeventdate(type,sid,curdate,rotation,curedate,stageid,'move');
								}
								else
								{
									$.Zebra_Dialog(ajaxdata);
								    revertFunc();
								}
							}
						});
					}
					else
					{
						fn_changeeventdate(type,sid,curdate,rotation,curedate,stageid);
					}
				},
				eventResize: function(event,dayDelta,minuteDelta,revertFunc) {
					var type= event.type;
					var sid=event.sid;
					var date=event.start;
					var enddate=event.end;
					var day=date.getDate();
					var month=date.getMonth()+1;
					var year=date.getFullYear();
					var eday=enddate.getDate();
					var emonth=enddate.getMonth()+1;
					var eyear=enddate.getFullYear();
					var rotation=event.rotation;
					var stageid=event.backgroundColor;
					
					var curdate=year+"-"+month+"-"+day;
					var curedate=eyear+"-"+emonth+"-"+eday;
					
					
						fn_changeeventdate(type,sid,curdate,rotation,curedate,stageid,'extend');
					
				}
			});
			
			$('.fc-header-left').html('<span class="fc-header-title"><h1>Class Calendar</h1></span>');
		});
	</script>
</section>
<?php
	@include("footer.php");